<?php
/**
 * Ön başvuru e-postası (PHPMailer, SMTP). Yalnızca formdaki alanlar gönderilir: IP adresi, tarayıcı bilgisi gibi
 * KVKK metninde olmayan veriler e-postaya eklenmez. Başvurular sunucuda saklanmaz.
 */
declare(strict_types=1);

use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;

require_once PRIVATE_DIR . '/vendor/autoload.php';

/** Doğrulanmış başvuru değerlerinden [etiket, değer] satırları (formdaki sırayla, görünen metinlerle). */
function application_mail_rows(array $v): array
{
    $app = site()['application'];
    $birth = DateTimeImmutable::createFromFormat('!Y-m-d', $v['birthdate']);
    return [
        ['Ad Soyad', $v['name']],
        ['Doğum Tarihi', $birth !== false ? $birth->format('d.m.Y') : $v['birthdate']],
        ['Yaş Grubu', (string) option_label($app['age_groups'], $v['age-group'])],
        ['Eğitim Durumu', (string) option_label($app['education_levels'], $v['education'])],
        ['Başvurmak İstediği Eğitim', (string) option_label(program_options(), $v['program'])],
        ['Telefon', $v['phone']],
        ['E-posta', $v['email'] !== '' ? $v['email'] : '-'],
        ['Sanat Eğitimi Deneyimi', (string) option_label($app['art_experience'], $v['art-experience'])],
        ['Eklemek İstedikleri', $v['message'] !== '' ? $v['message'] : '-'],
        ['KVKK onayı', 'Evet'],
        ['Gönderim zamanı', (new DateTimeImmutable('now', new DateTimeZone('Europe/Istanbul')))->format('d.m.Y H:i')],
    ];
}

/** Başvuru e-postasını gönderir. Başarısızlıkta false döner ve (kişisel veri olmadan) loglar. */
function send_application_mail(array $v): bool
{
    if (!mail_configured()) {
        log_error('mail_not_configured', 'config.php yok ya da mail ayarlari bos');
        return false;
    }
    $rows = application_mail_rows($v);
    $program = (string) option_label(program_options(), $v['program']);

    $plain = '';
    $html = '<table role="presentation" cellpadding="8" cellspacing="0" border="0" style="border-collapse:collapse;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#222">';
    foreach ($rows as [$label, $value]) {
        $plain .= $label . ': ' . $value . "\n";
        $html .= '<tr><th align="left" valign="top" style="border-bottom:1px solid #ddd;white-space:nowrap">' . e($label) . '</th>'
            . '<td valign="top" style="border-bottom:1px solid #ddd">' . nl2br(e($value), false) . '</td></tr>';
    }
    $html .= '</table>';

    try {
        $mail = new PHPMailer(true);
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->Encoding = PHPMailer::ENCODING_BASE64;
        $mail->isSMTP();
        $mail->Host = (string) config('smtp_host');
        $mail->Port = (int) config('smtp_port', 587);
        $user = (string) config('smtp_user', '');
        $mail->SMTPAuth = $user !== '';
        $mail->Username = $user;
        $mail->Password = (string) config('smtp_pass', '');
        $secure = strtolower((string) config('smtp_secure', ''));
        if ($secure === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($secure === 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $mail->SMTPSecure = '';
            $mail->SMTPAutoTLS = false;
        }
        if (config('smtp_verify_peer', true) === false) {
            $mail->SMTPOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]];
        }
        $mail->Timeout = 10;

        $mail->setFrom((string) config('mail_from'), (string) config('mail_from_name', ''));
        foreach ((array) config('mail_to', []) as $to) {
            if (is_string($to) && trim($to) !== '') {
                $mail->addAddress(trim($to));
            }
        }
        if ($v['email'] !== '') {
            $mail->addReplyTo($v['email'], $v['name']);
        }
        $mail->Subject = 'Yeni Ön Başvuru: ' . $v['name'] . ' - ' . $program;
        $mail->isHTML(true);
        $mail->Body = $html;
        $mail->AltBody = $plain;
        $mail->send();
        return true;
    } catch (MailException | Throwable $ex) {
        // PHPMailer iletisi alıcı adreslerini içerebilir: log_error e-postaları ve uzun sayıları siler.
        log_error('mail_send_failed', isset($mail) ? (string) $mail->ErrorInfo : $ex->getMessage());
        return false;
    }
}
