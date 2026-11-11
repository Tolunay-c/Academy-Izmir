<?php
/**
 * Ön başvuru formu back-end'i: oturum, CSRF, bal tuzağı, zaman tuzağı, hız sınırı, sunucu tarafı doğrulama,
 * PRG (Post/Redirect/Get) ve e-posta gönderimi. Yalnızca application.php kullanır.
 * Başvurular sunucuda KAYDEDİLMEZ (yalnızca e-posta). Loga kişisel veri yazılmaz.
 * PHP 8.2 uyumlu.
 */
declare(strict_types=1);

date_default_timezone_set('Europe/Istanbul');

const APP_MIN_SECONDS = 3;          // bundan hızlı gönderimler sessizce yok sayılır
const APP_RATE_LIMIT = 5;           // aynı IP'den saatte en fazla başvuru
const APP_MESSAGE_MAX = 2000;
const APP_BIRTHDATE_MIN = '1930-01-01';

/** Kullanıcıya gösterilen hata iletileri (main.js ile aynı). */
function app_messages(): array
{
    return [
        'name' => 'Lütfen adınızı yazın.',
        'birthdate' => 'Lütfen doğum tarihinizi seçin.',
        'birthdate_invalid' => 'Lütfen geçerli bir doğum tarihi seçin.',
        'age-group' => 'Lütfen bir yaş grubu seçin.',
        'education' => 'Lütfen eğitim durumunuzu seçin.',
        'program' => 'Lütfen bir eğitim seçin.',
        'phone' => 'Lütfen geçerli bir telefon numarası yazın.',
        'email' => 'Lütfen geçerli bir e-posta adresi yazın.',
        'art-experience' => 'Lütfen listedeki seçeneklerden birini seçin.',
        'message' => 'Mesajınız en fazla ' . APP_MESSAGE_MAX . ' karakter olabilir.',
        'consent' => 'Devam etmek için aydınlatma metnini okuduğunuzu onaylayın.',
        'form' => 'Güvenlik doğrulaması başarısız oldu. Lütfen formu yeniden gönderin.',
    ];
}

// ---- Depolama ve log ---------------------------------------------------------------------------

/** private/storage altındaki klasörü döndürür (yoksa oluşturur). Yazılamıyorsa null. */
function storage_dir(string $sub = ''): ?string
{
    $dir = PRIVATE_DIR . '/storage' . ($sub !== '' ? '/' . $sub : '');
    if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
        return null;
    }
    return is_writable($dir) ? $dir : null;
}

/**
 * Yalnızca hata kaydı: zaman, hata türü, kısa ileti. Kişisel veri (e-posta, uzun sayı dizileri) iletiden temizlenir.
 */
function log_error(string $type, string $message = ''): void
{
    $dir = storage_dir('logs');
    if ($dir === null) {
        return;
    }
    $message = preg_replace('/[^\s@<>"\']+@[^\s@<>"\']+/', '[e-posta]', $message) ?? '';
    $message = preg_replace('/\d{7,}/', '[sayi]', $message) ?? '';
    $message = trim(preg_replace('/\s+/', ' ', $message) ?? '');
    $message = function_exists('mb_substr') ? mb_substr($message, 0, 300) : substr($message, 0, 300);
    @file_put_contents($dir . '/errors-' . date('Y-m') . '.log', date('Y-m-d H:i:s') . ' | ' . $type . ' | ' . $message . "\n", FILE_APPEND | LOCK_EX);
}

// ---- Ayarlar -----------------------------------------------------------------------------------

/** İmza ve IP özeti için anahtar. Ayarlanmamışsa kuruluma özgü bir yedek kullanılır. */
function app_secret(): string
{
    $secret = (string) config('app_secret', '');
    return $secret !== '' ? $secret : hash('sha256', PRIVATE_DIR . '|academy-izmir');
}

/** Mail gönderebilmek için gerçek config.php ve doldurulmuş ayarlar gerekir. */
function mail_configured(): bool
{
    if (!is_file(PRIVATE_DIR . '/config.php')) {
        return false;
    }
    $to = array_filter((array) config('mail_to', []), static fn ($a): bool => is_string($a) && trim($a) !== '');
    return trim((string) config('smtp_host', '')) !== '' && trim((string) config('mail_from', '')) !== '' && count($to) > 0;
}

// ---- Oturum, CSRF, zaman tuzağı -------------------------------------------------------------------

/** Oturumu yalnızca bu sayfada başlatır: Secure (https'te), HttpOnly, SameSite=Lax. */
function app_start_session(): bool
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return true;
    }
    $https = (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')
        || strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';
    session_name('academy_sid');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    $sessions = storage_dir('sessions');
    if ($sessions !== null) {
        session_save_path($sessions);
    }
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    return @session_start();
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf']) || !is_string($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/** Form oluşturulma zamanı, app_secret ile imzalı: "<unix>.<hmac>". */
function form_timestamp(): string
{
    $now = (string) time();
    return $now . '.' . hash_hmac('sha256', $now, app_secret());
}

/** İmza geçerliyse unix zamanını, değilse null döndürür. */
function verify_timestamp(string $value): ?int
{
    $parts = explode('.', $value, 2);
    if (count($parts) !== 2 || !ctype_digit($parts[0])) {
        return null;
    }
    return hash_equals(hash_hmac('sha256', $parts[0], app_secret()), $parts[1]) ? (int) $parts[0] : null;
}

// ---- Hız sınırı ---------------------------------------------------------------------------------

/** IP'nin kendisi değil, app_secret ile özeti. */
function client_hash(): string
{
    return hash_hmac('sha256', (string) ($_SERVER['REMOTE_ADDR'] ?? ''), app_secret());
}

/**
 * Aynı IP'den saatte en fazla APP_RATE_LIMIT başvuruya izin verir ve bu başvuruyu sayar.
 * Depolama yazılamazsa sınır devre dışı kalır (form çalışır, log yazılır).
 */
function rate_limit_allow(): bool
{
    $dir = storage_dir('ratelimit');
    if ($dir === null) {
        log_error('rate_limit_disabled', 'storage yazilamiyor');
        return true;
    }
    $fp = @fopen($dir . '/' . client_hash() . '.json', 'c+');
    if ($fp === false) {
        log_error('rate_limit_disabled', 'dosya acilamadi');
        return true;
    }
    flock($fp, LOCK_EX);
    $times = json_decode((string) stream_get_contents($fp), true);
    $now = time();
    $times = is_array($times) ? array_values(array_filter($times, static fn ($t): bool => is_int($t) && $t > $now - 3600)) : [];
    $allowed = count($times) < APP_RATE_LIMIT;
    if ($allowed) {
        $times[] = $now;
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, (string) json_encode($times));
    }
    flock($fp, LOCK_UN);
    fclose($fp);
    if (random_int(1, 20) === 1) { // eski kayıtları ara sıra temizle
        foreach (glob($dir . '/*.json') ?: [] as $file) {
            if (@filemtime($file) < $now - 86400) {
                @unlink($file);
            }
        }
    }
    return $allowed;
}

// ---- Doğrulama ----------------------------------------------------------------------------------

/** Seçenek listesinde id'ye karşılık gelen görünen metin, yoksa null. */
function option_label(array $options, string $id): ?string
{
    foreach ($options as $o) {
        if ($o['id'] === $id) {
            return $o['label'];
        }
    }
    return null;
}

/** Telefon: rakamlar alınır, 90 ile başlayan 12 hane 0'a çevrilir, başında 0 yoksa eklenir; 0 ile başlayan 11 rakam. */
function normalize_phone(string $raw): ?string
{
    $digits = preg_replace('/\D/', '', $raw) ?? '';
    if (strlen($digits) === 12 && strncmp($digits, '90', 2) === 0) {
        $digits = '0' . substr($digits, 2);
    } elseif ($digits !== '' && $digits[0] !== '0') {
        $digits = '0' . $digits;
    }
    return preg_match('/^0\d{10}$/', $digits) === 1 ? $digits : null;
}

/** 05321234567 => 0 (532) 123 45 67 */
function format_phone(string $digits): string
{
    return substr($digits, 0, 1) . ' (' . substr($digits, 1, 3) . ') ' . substr($digits, 4, 3) . ' ' . substr($digits, 7, 2) . ' ' . substr($digits, 9, 2);
}

/** Varsayılan form değerleri. */
function application_defaults(): array
{
    return ['name' => '', 'birthdate' => '', 'age-group' => '', 'education' => '', 'program' => '', 'phone' => '', 'email' => '',
        'art-experience' => 'no', 'message' => '', 'consent' => false];
}

/**
 * Gönderilen alanları doğrular. Dönen: [temizlenmiş değerler, hatalar (alan => ileti)].
 * Kurallar main.js ile aynıdır.
 */
function validate_application(array $post): array
{
    $msg = app_messages();
    $app = site()['application'];
    $str = static fn (string $k): string => isset($post[$k]) && is_string($post[$k]) ? $post[$k] : '';
    $v = application_defaults();
    $errors = [];

    // Ad Soyad: satır sonu reddedilir (başlık enjeksiyonu), izin verilmeyen karakterler temizlenir, en az 2 harf.
    $rawName = $str('name');
    if (preg_match('/[\r\n]/', $rawName) === 1) {
        $errors['name'] = $msg['name'];
    } else {
        $name = preg_replace('/[^\p{L}\p{M} \'’\-]/u', '', $rawName) ?? '';
        $name = trim(preg_replace('/ {2,}/', ' ', $name) ?? '');
        $v['name'] = $name;
        if (preg_match_all('/\p{L}/u', $name) < 2) {
            $errors['name'] = $msg['name'];
        }
    }

    // Doğum tarihi: geçerli takvim tarihi, 1930-01-01 ile bugün (Europe/Istanbul) arası.
    $birth = trim($str('birthdate'));
    $v['birthdate'] = $birth;
    if ($birth === '') {
        $errors['birthdate'] = $msg['birthdate'];
    } else {
        $d = DateTimeImmutable::createFromFormat('!Y-m-d', $birth);
        $valid = $d !== false && $d->format('Y-m-d') === $birth;
        $today = (new DateTimeImmutable('today'))->format('Y-m-d');
        if (!$valid || $birth < APP_BIRTHDATE_MIN || $birth > $today) {
            $errors['birthdate'] = $msg['birthdate_invalid'];
        }
    }

    // Seçenekler: site.json'daki id'lerden biri olmalı.
    $choices = [
        'age-group' => $app['age_groups'],
        'education' => $app['education_levels'],
        'program' => program_options(),
    ];
    foreach ($choices as $key => $options) {
        $v[$key] = $str($key);
        if (option_label($options, $v[$key]) === null) {
            $errors[$key] = $msg[$key];
        }
    }

    // Sanat eğitimi deneyimi: isteğe bağlı (varsayılan Yok), gelirse seçeneklerden biri olmalı.
    $art = $str('art-experience') !== '' ? $str('art-experience') : 'no';
    $v['art-experience'] = $art;
    if (option_label($app['art_experience'], $art) === null) {
        $errors['art-experience'] = $msg['art-experience'];
    }

    // Telefon
    $rawPhone = $str('phone');
    $phone = normalize_phone($rawPhone);
    if ($phone === null) {
        $v['phone'] = trim($rawPhone);
        $errors['phone'] = $msg['phone'];
    } else {
        $v['phone'] = format_phone($phone);
    }

    // E-posta: isteğe bağlı, doluysa geçerli olmalı, satır sonu reddedilir.
    $rawEmail = $str('email');
    $email = trim($rawEmail);
    $v['email'] = $email;
    if ($email !== '' && (preg_match('/[\r\n]/', $rawEmail) === 1 || strlen($email) > 254 || filter_var($email, FILTER_VALIDATE_EMAIL) === false)) {
        $errors['email'] = $msg['email'];
    }

    // Mesaj: isteğe bağlı, en fazla 2000 karakter.
    $message = str_replace(["\r\n", "\r"], "\n", $str('message'));
    $message = trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $message) ?? '');
    $v['message'] = $message;
    if (mb_strlen($message, 'UTF-8') > APP_MESSAGE_MAX) {
        $errors['message'] = $msg['message'];
    }

    // KVKK onayı zorunlu
    $v['consent'] = $str('consent') !== '';
    if (!$v['consent']) {
        $errors['consent'] = $msg['consent'];
    }

    return [$v, $errors];
}

// ---- Akış ---------------------------------------------------------------------------------------

/** PRG: sonuç adresine 303 ile yönlendirir. */
function app_redirect(string $status): void
{
    header('Location: ' . url('application') . '?durum=' . $status, true, 303);
    exit;
}

/**
 * Sayfanın durumunu belirler. Dönen: ['view' => 'form'|'success', 'values' => [...], 'errors' => [...], 'failed' => bool].
 * POST sonrası PRG ile yönlendirir (exit).
 */
function application_handle(): array
{
    $state = ['view' => 'form', 'values' => application_defaults(), 'errors' => [], 'failed' => false];
    $sessionOk = app_start_session();
    if (!$sessionOk) {
        log_error('session_failed', 'oturum baslatilamadi');
        $_SESSION = [];
    }

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        $post = $_POST;
        $msg = app_messages();
        $keep = static function () use ($post): array {
            // Hata durumunda alanlar kullanıcının girdisiyle yeniden gösterilir (kaçışlama şablonda e() ile).
            return validate_application($post)[0];
        };

        // CSRF: oturuma bağlı token
        $token = isset($post['csrf']) && is_string($post['csrf']) ? $post['csrf'] : '';
        $session = isset($_SESSION['csrf']) && is_string($_SESSION['csrf']) ? $_SESSION['csrf'] : '';
        if ($sessionOk === false || $token === '' || $session === '' || !hash_equals($session, $token)) {
            log_error('csrf_failed', '');
            $state['values'] = $keep();
            $state['errors']['form'] = $msg['form'];
            return $state;
        }

        // Bal tuzağı ve zaman tuzağı: sessizce yok sayılır, kullanıcıya başarı gösterilir.
        $ts = verify_timestamp(isset($post['ts']) && is_string($post['ts']) ? $post['ts'] : '');
        if ((isset($post['website']) && is_string($post['website']) && trim($post['website']) !== '')
            || (isset($post['website']) && !is_string($post['website']))
            || $ts === null || time() - $ts < APP_MIN_SECONDS) {
            unset($_SESSION['csrf']);
            $_SESSION['flash'] = ['status' => 'basarili'];
            app_redirect('basarili');
        }

        [$values, $errors] = validate_application($post);
        if ($errors) {
            $state['values'] = $values;
            $state['errors'] = $errors;
            return $state;
        }

        // Hız sınırı, sonra gönderim
        $sent = false;
        if (!rate_limit_allow()) {
            log_error('rate_limit', 'saatlik sinir asildi');
        } else {
            require_once PRIVATE_DIR . '/application-mail.php';
            $sent = send_application_mail($values);
        }
        unset($_SESSION['csrf']);
        if ($sent) {
            $_SESSION['flash'] = ['status' => 'basarili'];
            app_redirect('basarili');
        }
        // Başarısız: değerler yalnızca bu tek gösterim için oturumda tutulur, gösterilince silinir.
        $_SESSION['flash'] = ['status' => 'hata', 'values' => $values];
        app_redirect('hata');
    }

    // GET: sonuç ekranı yalnızca oturumdaki tek kullanımlık mesajla gösterilir (adres elle yazılarak tetiklenemez).
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    $durum = isset($_GET['durum']) && is_string($_GET['durum']) ? $_GET['durum'] : '';
    if (is_array($flash) && ($flash['status'] ?? '') === $durum) {
        if ($durum === 'basarili') {
            $state['view'] = 'success';
        } elseif ($durum === 'hata') {
            $state['failed'] = true;
            if (isset($flash['values']) && is_array($flash['values'])) {
                $state['values'] = array_merge($state['values'], $flash['values']);
            }
        }
    } elseif (isset($_GET['program']) && is_string($_GET['program']) && option_label(program_options(), $_GET['program']) !== null) {
        $state['values']['program'] = $_GET['program']; // JS kapalıyken de ?program= ile ön seçim
    }
    return $state;
}

// ---- Şablon yardımcıları --------------------------------------------------------------------------

/** Alan değeri (kaçışlanmış). */
function fv(string $key): string
{
    $v = $GLOBALS['form']['values'][$key] ?? '';
    return e(is_bool($v) ? '' : $v);
}

/** Alanın hatası var mı. */
function f_has_error(string $key): bool
{
    return isset($GLOBALS['form']['errors'][$key]);
}

/** aria-invalid özniteliği. */
function f_invalid(string $key): string
{
    return f_has_error($key) ? ' aria-invalid="true"' : '';
}

/** Alanın hata paragrafı (hata yoksa hidden). id: app-<alan>-error. */
function f_error(string $key): string
{
    $m = $GLOBALS['form']['errors'][$key] ?? '';
    return '<p class="form-error" id="app-' . e($key) . '-error"' . ($m === '' ? ' hidden' : '') . '>' . e($m) . '</p>';
}
