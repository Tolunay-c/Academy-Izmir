<?php
/**
 * routes.php ve redirects.php'den httpdocs/web.config ve httpdocs/sitemap.xml üretir.
 * Kullanım (repo kökünden):  php site/private/tools/build.php
 * Elle düzenlemeyin: değişiklik routes.php / redirects.php / bu dosyada yapılır, sonra yeniden üretilir.
 */
declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

$routes = array_filter(routes(), static fn (array $r): bool => empty($r['hidden']));
$old = require PRIVATE_DIR . '/redirects.php';
$host = (string) parse_url(base_url(), PHP_URL_HOST);          // www.academyizmir.com
$apex = preg_replace('/^www\./', '', $host);                    // academyizmir.com
$hostRegex = '^(www\.)?' . preg_quote($apex, '/') . '$';

$x = static fn (string $s): string => htmlspecialchars($s, ENT_QUOTES | ENT_XML1, 'UTF-8');
$pat = static fn (string $path): string => '^' . preg_quote(ltrim($path, '/'), '/') . '$';

$rules = [];
$add = static function (string $name, string $match, string $action, string $conditions = '') use (&$rules, $x): void {
    $rules[] = '        <rule name="' . $x($name) . '" stopProcessing="true">' . "\n"
        . '          <match url="' . $x($match) . '" />' . "\n"
        . ($conditions !== '' ? '          <conditions logicalGrouping="MatchAll" trackAllCaptures="false">' . "\n" . $conditions . '          </conditions>' . "\n" : '')
        . '          ' . $action . "\n"
        . '        </rule>';
};
$redirect = static fn (string $to): string => '<action type="Redirect" url="' . htmlspecialchars($to, ENT_QUOTES | ENT_XML1) . '" redirectType="Permanent" appendQueryString="' . (strpos($to, '#') === false ? 'true' : 'false') . '" />';
$rewrite = static fn (string $to): string => '<action type="Rewrite" url="' . htmlspecialchars($to, ENT_QUOTES | ENT_XML1) . '" appendQueryString="true" />';
$cond = static fn (string $input, string $pattern, bool $negate = false): string => '            <add input="' . htmlspecialchars($input, ENT_QUOTES | ENT_XML1) . '" pattern="' . htmlspecialchars($pattern, ENT_QUOTES | ENT_XML1) . '"' . ($negate ? ' negate="true"' : '') . ' />' . "\n";

$canonical = 'https://' . $host . '/{R:1}';
// 1-2. https ve www: tek adımda (çift yönlendirme yok). Yalnızca canlı alan adlarına uygulanır, test adreslerine dokunmaz.
$add('Apex alan adi -> www (https)', '(.*)', $redirect($canonical),
    $cond('{HTTP_HOST}', '^' . preg_quote($apex, '/') . '$'));
$add('http -> https', '(.*)', $redirect($canonical),
    $cond('{HTTPS}', 'off') . $cond('{HTTP_HOST}', $hostRegex));
// 3. /index.php ve /index.html -> /
$add('index.php ve index.html -> /', '^index\.(php|html)$', $redirect('/'));
// 4. Sondaki eğik çizgi: /egitimler/ -> /egitimler
$add('Sondaki egik cizgiyi kaldir', '^(.+)/$', $redirect('/{R:1}'),
    '            <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />' . "\n"
    . '            <add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />' . "\n");
// 5. Eski site adresleri (301)
foreach ($old as $from => $to) {
    $add('Eski site: ' . $from, $pat($from), $redirect($to));
}
// 6. Doğrudan .php erişimi -> Türkçe adres (yalnızca dışarıdan gelen istek, iç yeniden yazmayı etkilemez)
foreach ($routes as $id => $r) {
    $add('PHP dosyasi -> adres: ' . $r['file'], $pat($r['file']), $redirect($r['path']),
        $cond('{THE_REQUEST}', '^[A-Z]{3,9}\s/' . preg_quote($r['file'], '/') . '(\?|\s)'));
}
// 7. Türkçe adresleri PHP dosyasına yeniden yaz
foreach ($routes as $id => $r) {
    $match = $r['path'] === '/' ? '^$' : $pat($r['path']);
    $add('Adres -> ' . $r['file'], $match, $rewrite('/' . $r['file']));
}

// web.config bölümleri. Paylaşımlı Plesk/IIS'te 500.19 vermemesi için: kilitli olabilecek öznitelikler kullanılmaz,
// sunucuda zaten tanımlı olabilecek her öğeden önce <remove> yazılır.
$sections = [];
$sections['defaultDocument'] = '    <defaultDocument enabled="true">
      <files>
        <remove value="index.php" />
        <add value="index.php" />
      </files>
    </defaultDocument>';
$sections['rewrite'] = '    <rewrite>
      <rules>
' . implode("\n", $rules) . '
      </rules>
    </rewrite>';
$headers = [
    'X-Content-Type-Options' => 'nosniff',
    'Referrer-Policy' => 'strict-origin-when-cross-origin',
    'X-Frame-Options' => 'SAMEORIGIN',
    'Permissions-Policy' => 'camera=(), microphone=(), geolocation=()',
];
$headerLines = '        <remove name="X-Powered-By" />' . "\n";
foreach ($headers as $name => $value) {
    $headerLines .= '        <remove name="' . $x($name) . '" />' . "\n"
        . '        <add name="' . $x($name) . '" value="' . $x($value) . '" />' . "\n";
}
// Content-Security-Policy bilerek yok: Google kodları gelince ele alınacak.
$sections['customHeaders'] = '    <httpProtocol>
      <customHeaders>
' . rtrim($headerLines) . '
      </customHeaders>
    </httpProtocol>';
// 404: sitenin tasarımında 404.php, durum kodu gerçekten 404 kalır (ExecuteURL)
$sections['httpErrors'] = '    <httpErrors>
      <remove statusCode="404" subStatusCode="-1" />
      <error statusCode="404" path="/404.php" responseMode="ExecuteURL" />
    </httpErrors>';
$mime = ['.webp' => 'image/webp', '.svg' => 'image/svg+xml', '.mp4' => 'video/mp4', '.webmanifest' => 'application/manifest+json'];
$mimeLines = '';
foreach ($mime as $ext => $type) {
    $mimeLines .= '      <remove fileExtension="' . $ext . '" />' . "\n" . '      <mimeMap fileExtension="' . $ext . '" mimeType="' . $type . '" />' . "\n";
}
$sections['staticContent'] = '    <staticContent>
' . rtrim($mimeLines) . '
    </staticContent>';
$sections['urlCompression'] = '    <urlCompression doStaticCompression="true" />';
$hidden = ['private', '.git'];
$denied = ['.json', '.md', '.example', '.log', '.bak', '.sql', '.ini'];
$hl = '';
foreach ($hidden as $seg) {
    $hl .= '          <remove segment="' . $seg . '" />' . "\n" . '          <add segment="' . $seg . '" />' . "\n";
}
$fl = '';
foreach ($denied as $ext) {
    $fl .= '          <remove fileExtension="' . $ext . '" />' . "\n" . '          <add fileExtension="' . $ext . '" allowed="false" />' . "\n";
}
$sections['requestFiltering'] = '    <security>
      <requestFiltering>
        <hiddenSegments>
' . rtrim($hl) . '
        </hiddenSegments>
        <fileExtensions>
' . rtrim($fl) . '
        </fileExtensions>
      </requestFiltering>
    </security>';
// assets: uzun süreli önbellek (30 gün). Dosya değişince adı ya da ?v= parametresi değiştirilmelidir.
$location = '  <location path="assets">
    <system.webServer>
      <staticContent>
        <clientCache cacheControlMode="UseMaxAge" cacheControlMaxAge="30.00:00:00" />
      </staticContent>
    </system.webServer>
  </location>';

$buildConfig = static function (array $keys, bool $withLocation, string $note) use ($sections, $location): string {
    $body = [];
    foreach ($keys as $k) {
        $body[] = $sections[$k];
    }
    return '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
        . '<!-- ' . $note . ' -->' . "\n"
        . "<configuration>\n  <system.webServer>\n\n"
        . implode("\n\n", $body) . "\n\n  </system.webServer>\n"
        . ($withLocation ? "\n" . $location . "\n" : '')
        . "</configuration>\n";
};
$full = ['defaultDocument', 'rewrite', 'customHeaders', 'httpErrors', 'staticContent', 'urlCompression', 'requestFiltering'];
$config = $buildConfig($full, true, 'OTOMATIK ÜRETİLDİ: php site/private/tools/build.php (kaynak: routes.php, redirects.php). Elle düzenlemeyin.');

// Hata ayıklama: bölümleri sırayla ekleyen 5 ayrı web.config (repo dışı deploy/webconfig-test/)
$testDir = dirname(PUBLIC_DIR, 2) . '/deploy/webconfig-test';
if (!is_dir($testDir)) {
    mkdir($testDir, 0775, true);
}
$steps = [
    1 => [['defaultDocument'], false],
    2 => [['defaultDocument', 'rewrite'], false],
    3 => [['defaultDocument', 'rewrite', 'customHeaders'], false],
    4 => [['defaultDocument', 'rewrite', 'customHeaders', 'httpErrors'], false],
    5 => [$full, true],
];
foreach ($steps as $n => [$keys, $loc]) {
    file_put_contents($testDir . '/' . $n . '-web.config', $buildConfig($keys, $loc, 'TEST ' . $n . '/5: ' . implode(', ', $keys) . ($loc ? ', clientCache (assets)' : '')));
}
file_put_contents(PUBLIC_DIR . '/web.config', $config);

// sitemap.xml: gizli olmayan sayfalar, lastmod = üretim günü
$today = date('Y-m-d');
$sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($routes as $r) {
    $sitemap .= "  <url>\n    <loc>" . $x(absolute_url($r['path'])) . "</loc>\n    <lastmod>" . $today . "</lastmod>\n    <priority>" . $r['priority'] . "</priority>\n  </url>\n";
}
$sitemap .= "</urlset>\n";
file_put_contents(PUBLIC_DIR . '/sitemap.xml', $sitemap);

echo 'web.config: ' . count($rules) . " kural\nsitemap.xml: " . count($routes) . " sayfa (lastmod " . $today . ")\n";
