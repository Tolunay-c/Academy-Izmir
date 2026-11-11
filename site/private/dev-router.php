<?php
/**
 * Yerel geliştirme yönlendiricisi (php -S). web.config'teki kuralların aynısını routes.php ve redirects.php'den uygular:
 *   php -S localhost:8000 -t site/httpdocs site/private/dev-router.php
 * Üretimde kullanılmaz (IIS web.config kullanır).
 */
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$uri = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = rawurldecode($uri);
$query = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '' ? '?' . $_SERVER['QUERY_STRING'] : '';

$redirect = static function (string $to, string $query): void {
    header('Location: ' . $to . $query, true, 301);
    exit;
};

// Gerçek statik dosyalar (assets, robots.txt, sitemap.xml) olduğu gibi sunulur; PHP dosyaları doğrudan sunulmaz.
$file = PUBLIC_DIR . $path;
if ($path !== '/' && is_file($file) && !str_ends_with($path, '.php')) {
    $blocked = ['json', 'md', 'example', 'config'];
    if (in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $blocked, true)) {
        http_response_code(404);
        $page_id = 'not_found';
        require PUBLIC_DIR . '/404.php';
        return true;
    }
    return false;
}

$routes = routes();
$byPath = [];
$byFile = [];
foreach ($routes as $id => $r) {
    if (!empty($r['hidden'])) {
        continue;
    }
    $byPath[$r['path']] = $r['file'];
    $byFile['/' . $r['file']] = $r['path'];
}

// 1. /index.php ve /index.html -> /
if ($path === '/index.php' || $path === '/index.html') {
    $redirect('/', $query);
}
// 2. Sondaki eğik çizgi tek tip: /egitimler/ -> /egitimler
if ($path !== '/' && substr($path, -1) === '/') {
    $redirect(rtrim($path, '/'), $query);
}
// 3. Doğrudan .php erişimi -> Türkçe adres
if (isset($byFile[$path])) {
    $redirect($byFile[$path], $query);
}
// 4. Eski site adresleri -> yeni adresler
$old = require PRIVATE_DIR . '/redirects.php';
if (isset($old[$path])) {
    $redirect($old[$path], $query);
}
// 5. Sayfalar
if (isset($byPath[$path])) {
    require PUBLIC_DIR . '/' . $byPath[$path];
    return true;
}
// 6. 404
http_response_code(404);
require PUBLIC_DIR . '/404.php';
return true;
