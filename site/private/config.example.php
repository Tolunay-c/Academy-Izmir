<?php
/**
 * Örnek yapılandırma. Sunucuda bu dosyayı config.php olarak kopyalayın ve değerleri doldurun.
 * config.php repoya GİRMEZ (.gitignore), gerçek şifreler yalnızca orada olur.
 */
return [
    // Sitenin tam adresi (sonunda eğik çizgi yok). Boş bırakılırsa https://www.academyizmir.com kullanılır.
    'base_url' => '',

    // Mail ayarları (başvuru formu gönderimi, sonraki adımda kullanılacak).
    'mail' => [
        'host' => '',
        'port' => '',
        'encryption' => '',
        'username' => '',
        'password' => '',
        'from_address' => '',
        'from_name' => '',
        'to_address' => '',
    ],

    // <head> sonuna eklenecek ham HTML parçaları (Google Tag Manager, Analytics, Ads kodları). Boş dizi: hiçbir şey basılmaz.
    'head_scripts' => [],

    // Google Search Console doğrulama kodu (meta content değeri). Boş: etiket basılmaz.
    'search_console_meta' => '',
];
