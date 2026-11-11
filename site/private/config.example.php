<?php
/**
 * Örnek yapılandırma. Sunucuda bu dosyayı config.php olarak kopyalayın ve değerleri doldurun.
 * config.php repoya GİRMEZ (.gitignore), gerçek şifreler yalnızca orada olur.
 * Ayrıntı: site/README.md ("config.php oluşturma").
 */
return [
    // Sitenin tam adresi (sonunda eğik çizgi yok). Boş bırakılırsa https://www.academyizmir.com kullanılır.
    'base_url' => '',

    // ---- Ön başvuru formu e-postası (SMTP) ----
    'smtp_host' => 'mail.academyizmir.com',
    'smtp_port' => 587,
    'smtp_secure' => 'tls',               // 'tls' (STARTTLS, 587), 'ssl' (465) ya da '' (şifreleme yok, yalnızca yerel test)
    'smtp_user' => 'noreply@academyizmir.com',
    'smtp_pass' => '',                    // posta kutusunun şifresi
    'smtp_verify_peer' => true,           // sunucu sertifikası sorun çıkarırsa false yapılabilir (README'ye bakın)
    'mail_from' => 'noreply@academyizmir.com',
    'mail_from_name' => 'Academy İzmir Web Sitesi',
    // Başvuru e-postası bu adreslerin hepsine tek e-posta olarak gider (hepsi To alanında).
    'mail_to' => ['info@academyizmir.com', 'ocicaney@gmail.com'],
    // Zaman tuzağı imzası ve IP özeti için rastgele uzun anahtar (README'de nasıl üretileceği yazıyor).
    'app_secret' => '',

    // <head> sonuna eklenecek ham HTML parçaları (Google Tag Manager, Analytics, Ads kodları). Boş dizi: hiçbir şey basılmaz.
    'head_scripts' => [],

    // Google Search Console doğrulama kodu (meta content değeri). Boş: etiket basılmaz.
    'search_console_meta' => '',
];
