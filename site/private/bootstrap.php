<?php
/**
 * Ortak başlangıç: ayarları ve veriyi yükler, yardımcı fonksiyonları tanımlar.
 * Her sayfa dosyası (httpdocs/*.php) en başta bunu çağırır:
 *   require __DIR__ . '/../private/bootstrap.php';
 * PHP 8.2 uyumlu (8.3 ve üstüne özgü özellik kullanılmaz).
 */
declare(strict_types=1);

define('PRIVATE_DIR', __DIR__);
define('PUBLIC_DIR', dirname(__DIR__) . '/httpdocs');

/** Ayar okur. config.php yoksa config.example.php (boş değerler) kullanılır. */
function config(string $key, $default = null)
{
    static $config = null;
    if ($config === null) {
        $file = is_file(PRIVATE_DIR . '/config.php') ? PRIVATE_DIR . '/config.php' : PRIVATE_DIR . '/config.example.php';
        $config = require $file;
    }
    return array_key_exists($key, $config) ? $config[$key] : $default;
}

/** HTML çıktısını kaçışlar (tüm dinamik çıktı buradan geçer). */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Boşlukları bölünmez boşluğa çevirir (telefon numaraları satır sonunda bölünmesin). */
function nbsp($value): string
{
    return str_replace(' ', '&nbsp;', e($value));
}

/** site.json içeriği (tek kaynak). */
function site(): array
{
    static $data = null;
    if ($data === null) {
        $json = file_get_contents(PRIVATE_DIR . '/data/site.json');
        $data = json_decode((string) $json, true, 512, JSON_THROW_ON_ERROR);
    }
    return $data;
}

/** Sayfa listesi (routes.php). */
function routes(): array
{
    static $routes = null;
    if ($routes === null) {
        $routes = require PRIVATE_DIR . '/routes.php';
    }
    return $routes;
}

/** Sitenin tam adresi, sonunda eğik çizgi olmadan. */
function base_url(): string
{
    $base = trim((string) config('base_url', ''));
    return rtrim($base !== '' ? $base : 'https://www.academyizmir.com', '/');
}

/** Sayfa adresi: url('programs') => /atolyeler, url('programs', '#theatre') => /atolyeler#theatre. */
function url(string $id, string $suffix = ''): string
{
    $routes = routes();
    if (!isset($routes[$id])) {
        throw new InvalidArgumentException('Bilinmeyen sayfa: ' . $id);
    }
    return $routes[$id]['path'] . $suffix;
}

/** Tam (mutlak) adres: canonical, og:url, og:image, sitemap. */
function absolute_url(string $path): string
{
    return base_url() . ($path === '/' ? '/' : $path);
}

/** Varlık adresi: assets/... => /assets/... (her sayfadan aynı çalışır). */
function asset(string $path): string
{
    return '/' . ltrim($path, '/');
}

/** Görselin piksel boyutu [genişlik, yükseklik]; width/height öznitelikleri için (CLS yok). */
function image_size(string $path): array
{
    static $cache = [];
    if (!isset($cache[$path])) {
        $info = @getimagesize(PUBLIC_DIR . '/' . ltrim($path, '/'));
        $cache[$path] = $info ? [(int) $info[0], (int) $info[1]] : [0, 0];
    }
    return $cache[$path];
}

/** Partial dosyasını çalıştırır; $vars partial içinde değişken olarak görünür. */
function partial(string $name, array $vars = []): void
{
    extract($vars, EXTR_SKIP);
    require PRIVATE_DIR . '/partials/' . $name . '.php';
}

/** Mevcut sayfanın kaydı (routes.php), $page_id sayfa dosyasında tanımlanır. */
function page(): array
{
    global $page_id;
    return routes()[$page_id] + ['id' => $page_id];
}

/** Ad Soyad vb. için bağlantı ve iletişim bilgileri (site.json). */
function contact(): array
{
    return site()['contact'];
}

require_once PRIVATE_DIR . '/components.php';
