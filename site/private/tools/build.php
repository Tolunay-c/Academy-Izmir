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
$redirect = static fn (string $to): string => '<action type="Redirect" url="' . htmlspecialchars($to, ENT_QUOTES | ENT_XML1) . '" redirectType="Permanent" appendQueryString="true" />';
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

$config = '<?xml version="1.0" encoding="UTF-8"?>
<!-- OTOMATIK ÜRETİLDİ: php site/private/tools/build.php (kaynak: routes.php, redirects.php). Elle düzenlemeyin. -->
<configuration>
  <system.webServer>

    <defaultDocument enabled="true">
      <files>
        <clear />
        <add value="index.php" />
      </files>
    </defaultDocument>

    <rewrite>
      <rules>
' . implode("\n", $rules) . '
      </rules>
    </rewrite>

    <!-- 404: sitenin tasarımında 404.php, durum kodu gerçekten 404 kalır (ExecuteURL) -->
    <httpErrors errorMode="Custom" existingResponse="Replace">
      <remove statusCode="404" subStatusCode="-1" />
      <error statusCode="404" path="/404.php" responseMode="ExecuteURL" />
    </httpErrors>

    <!-- Güvenlik başlıkları (Content-Security-Policy bilerek yok: Google kodları gelince ele alınacak) -->
    <httpProtocol>
      <customHeaders>
        <remove name="X-Powered-By" />
        <add name="X-Content-Type-Options" value="nosniff" />
        <add name="Referrer-Policy" value="strict-origin-when-cross-origin" />
        <add name="X-Frame-Options" value="SAMEORIGIN" />
        <add name="Permissions-Policy" value="camera=(), microphone=(), geolocation=()" />
      </customHeaders>
    </httpProtocol>

    <!-- Sıkıştırma -->
    <urlCompression doStaticCompression="true" doDynamicCompression="true" />

    <!-- Gizli dosyalar ve uzantılar -->
    <security>
      <requestFiltering>
        <hiddenSegments>
          <add segment="private" />
          <add segment=".git" />
        </hiddenSegments>
        <fileExtensions>
          <add fileExtension=".json" allowed="false" />
          <add fileExtension=".md" allowed="false" />
          <add fileExtension=".example" allowed="false" />
          <add fileExtension=".log" allowed="false" />
          <add fileExtension=".bak" allowed="false" />
          <add fileExtension=".sql" allowed="false" />
          <add fileExtension=".ini" allowed="false" />
        </fileExtensions>
      </requestFiltering>
    </security>

    <!-- Bazı IIS sürümlerinde eksik olan dosya türleri -->
    <staticContent>
      <remove fileExtension=".webp" />
      <mimeMap fileExtension=".webp" mimeType="image/webp" />
      <remove fileExtension=".svg" />
      <mimeMap fileExtension=".svg" mimeType="image/svg+xml" />
      <remove fileExtension=".mp4" />
      <mimeMap fileExtension=".mp4" mimeType="video/mp4" />
    </staticContent>

  </system.webServer>

  <!-- assets: uzun süreli önbellek (30 gün). Dosya değişince adı ya da ?v= parametresi değiştirilmelidir. -->
  <location path="assets">
    <system.webServer>
      <staticContent>
        <clientCache cacheControlMode="UseMaxAge" cacheControlMaxAge="30.00:00:00" />
      </staticContent>
    </system.webServer>
  </location>
</configuration>
';
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
