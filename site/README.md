# Academy İzmir — site (PHP)

`../v-1` statik prototip olarak kalır, bu klasör onun PHP'ye dönüştürülmüş, yayına hazır hâlidir. Görünen içerik v-1 ile aynıdır (tasarım değişikliği yok).

Sunucu: Windows Server, IIS, Plesk, PHP 8.2 (FastCGI). `.htaccess` çalışmaz, adresler `web.config` (IIS URL Rewrite) ile yönetilir. Kod PHP 8.2 ile uyumlu yazılmıştır (8.3 ve üstüne özgü özellik yok).

## Klasör yapısı

```
site/
  httpdocs/               sunucudaki httpdocs'a yüklenir (genel erişime açık)
    index.php, programs.php, about.php, faculty.php, winners.php, gallery.php,
    announcements.php, contact.php, application.php, privacy-notice.php, 404.php
    web.config            ÜRETİLİR (tools/build.php), elle düzenlemeyin
    sitemap.xml           ÜRETİLİR (tools/build.php)
    robots.txt
    assets/               css, js, images (kullanılanlar), video (v-1/assets'in kopyası, data klasörü yok)
  private/                sunucuda webspace köküne (httpdocs'un bir üstüne) yüklenir, genel erişime KAPALI
    bootstrap.php         ayarları yükler, yardımcı fonksiyonlar (e, url, asset, partial, image_size ...)
    components.php        site.json'dan HTML üreten bileşenler (kazanan kartı, program bölümü, galeri mozaiği ...)
    routes.php            tüm sayfaların tek listesi (adres, dosya, başlık, açıklama, menü adları)
    redirects.php         eski site adresleri -> yeni adresler (301)
    config.example.php    ayar şablonu
    config.php            GERÇEK ayarlar, repoya girmez (.gitignore)
    partials/             head, header, mobile-menu, footer, whatsapp-float, contact-bar, cta-band
    data/site.json        içerik verisi (v-1/assets/data/site.json'un kopyası)
    dev-router.php        yerel geliştirme yönlendiricisi (php -S)
    tools/build.php       web.config ve sitemap.xml üretir
```

Her sayfa `require_once __DIR__ . '/../private/bootstrap.php'` ile private'ı göreli yolla bulur. Yerelde ve sunucuda `httpdocs` ile `private` kardeş klasörlerdir, ilişki aynıdır.

## Sunucuya yükleme

| Yerel | Sunucu |
|---|---|
| `site/httpdocs/*` (web.config, robots.txt, sitemap.xml, assets dahil) | `httpdocs/` |
| `site/private/*` (config.php dahil) | webspace kökü, yani `httpdocs`'un bir üstündeki `private/` |

`private` klasörü httpdocs'un dışında olduğu için tarayıcıdan erişilemez. PHP erişebilir (open_basedir webspace kökünü kapsar). Güvenlik için `web.config` ayrıca `private` ve `.git` segmentlerini gizler.

## config.php oluşturma

```
cp site/private/config.example.php site/private/config.php
```

Sonra değerleri doldurun (`config.php` repoya girmez):

- `base_url`: `https://www.academyizmir.com` (boş bırakılırsa bu kullanılır; canonical, og:url, og:image, sitemap bundan türer)
- `mail`: başvuru formu mail ayarları (sonraki adımda kullanılacak)
- `head_scripts`: `<head>` sonuna eklenecek ham HTML parçaları dizisi (Google Tag Manager, Analytics, Ads). Boş dizi: hiçbir şey basılmaz. Parçalar kaçışlanmadan basılır, yalnızca güvenilir kod girin.
- `search_console_meta`: Search Console doğrulama kodu (meta content değeri). Boşsa etiket basılmaz.

## Yerelde çalıştırma

```
php -S localhost:8000 -t site/httpdocs site/private/dev-router.php
```

`dev-router.php`, `web.config`'teki kuralların aynısını `routes.php` ve `redirects.php`'den uygular (Türkçe adresler, 301 yönlendirmeler, 404). Yerelde `config.php` yoksa `config.example.php` kullanılır.

## Sayfalar ve adresler

| Adres | Dosya |
|---|---|
| `/` | `index.php` |
| `/egitimler` | `programs.php` |
| `/hakkimizda` | `about.php` |
| `/egitmen-kadromuz` | `faculty.php` |
| `/kazananlarimiz` | `winners.php` |
| `/galeri` | `gallery.php` |
| `/duyurular` | `announcements.php` |
| `/iletisim` | `contact.php` |
| `/on-basvuru` | `application.php` |
| `/kvkk-aydinlatma-metni` | `privacy-notice.php` |

Sayfa içi bağlantılar `url('programs', '#theatre')` gibi çağrılarla `routes.php`'den gelir. `?program=` ve `#anchor` korunur.

## routes.php'ye yeni sayfa ekleme

1. `httpdocs/` altına sayfayı kopyalayarak oluşturun: başında `require_once`, `$page_id = '...'`, `partial('head'); partial('header'); partial('mobile-menu');`, `<main>`, sonunda `partial('footer'); partial('whatsapp-float'); partial('contact-bar');`.
2. `private/routes.php`'ye kaydı ekleyin (`path`, `file`, `title`, `description`, `label`, `nav`, `mobile`, `footer`, `priority`). Menüler, title, description, canonical ve og etiketleri buradan gelir.
3. `php site/private/tools/build.php` ile `web.config` ve `sitemap.xml` yeniden üretin.

## web.config üretimi

```
php site/private/tools/build.php
```

`routes.php` ve `redirects.php`'den `httpdocs/web.config` ve `httpdocs/sitemap.xml` üretir (lastmod: üretim günü). Sayfa, adres ya da yönlendirme değiştiğinde yeniden çalıştırıp çıktıyı yükleyin.

`web.config` özeti: varsayılan belge `index.php`; 301 yönlendirmeleri (apex `academyizmir.com` ve http -> `https://www.academyizmir.com`, `/index.php` ve `/index.html` -> `/`, sondaki eğik çizgi kaldırma, eski site adresleri, doğrudan `.php` erişimi -> Türkçe adres); Türkçe adreslerin PHP dosyasına yeniden yazılması; özel 404 (`404.php`, durum kodu 404); güvenlik başlıkları (nosniff, strict-origin-when-cross-origin, SAMEORIGIN, Permissions-Policy; Content-Security-Policy bilerek yok); statik ve dinamik sıkıştırma; `assets` için 30 gün önbellek (dosya değişince ad ya da `?v=` değiştirin); `.json .md .example .log .bak .sql .ini` ve `private` erişimi engeli. https ve www yönlendirmeleri yalnızca canlı alan adına uygulanır, test adreslerine dokunmaz.

## Eski site yönlendirme tablosu

Canlı site (https://www.academyizmir.com, 2016'dan kalma) taranarak bulunan 40 adres (.html ve .aspx), hepsi 301. `#` sunucuya gitmediği için bölüm bağlantıları kullanılmaz. Karşılığı olmayan eski branşlar (resim, fotoğrafçılık, jimnastik, sinema-tv, müzik) `/egitimler`'e gider. Kaynak: `private/redirects.php`.

| Eski adres | Yeni adres |
|---|---|
| `/asli-sinke.html` | `/egitmen-kadromuz` |
| `/bolumler.html` | `/egitimler` |
| `/cocuklar-icin-bale.html` | `/egitimler` |
| `/cocuklar-icin-drama.html` | `/egitimler` |
| `/dans.html` | `/egitimler` |
| `/derya-tiryaki.html` | `/egitmen-kadromuz` |
| `/dilek-kutsav.html` | `/egitmen-kadromuz` |
| `/etkinlikler.html` | `/duyurular` |
| `/firat-halavut.html` | `/egitmen-kadromuz` |
| `/fotografcilik.html` | `/egitimler` |
| `/galeri.html` | `/galeri` |
| `/galeri_bale.html` | `/galeri` |
| `/galeri_etkinlikler.html` | `/galeri` |
| `/galeri_piyano.html` | `/galeri` |
| `/galeri_resim.html` | `/galeri` |
| `/galeri_sene_sonu.html` | `/galeri` |
| `/galeri_tiyatro.html` | `/galeri` |
| `/galeri_yaratici_drama.html` | `/galeri` |
| `/galeri_yetenek_sinavi.html` | `/galeri` |
| `/hakkimizda.html` | `/hakkimizda` |
| `/iletisim.aspx` | `/iletisim` |
| `/index.html` | `/` |
| `/jimnastik.html` | `/egitimler` |
| `/kadromuz.html` | `/egitmen-kadromuz` |
| `/kazanan-listesi.html` | `/kazananlarimiz` |
| `/kazananlar.html` | `/kazananlarimiz` |
| `/konusma-egitimi.html` | `/egitimler` |
| `/muzik-egitimi.html` | `/egitimler` |
| `/ozel-yetenek-sinavi.html` | `/egitimler` |
| `/ozgur-baskaya.html` | `/egitmen-kadromuz` |
| `/ozlem-baskaya.html` | `/egitmen-kadromuz` |
| `/ozlem-gur.html` | `/egitmen-kadromuz` |
| `/resim-kurslari.html` | `/egitimler` |
| `/selda-uzunkaya.html` | `/egitmen-kadromuz` |
| `/sinema-televizyon.html` | `/egitimler` |
| `/sinema-ve-dizi-oyunculugu.html` | `/egitimler` |
| `/tamer-yilmaz.html` | `/egitmen-kadromuz` |
| `/tiyatro.html` | `/egitimler` |
| `/yaratici-drama.html` | `/egitimler` |
| `/yesim-turgut.html` | `/egitmen-kadromuz` |

## Yayın öncesi kontrol listesi

1. `config.php` oluşturuldu ve dolduruldu (`base_url`, `head_scripts`, `search_console_meta`).
2. `php site/private/tools/build.php` çalıştırıldı, `web.config` ve `sitemap.xml` güncel.
3. Plesk'te PHP 8.2 (FastCGI) seçili. IIS'te URL Rewrite modülü kurulu.
4. `httpdocs/` ve `private/` doğru yere yüklendi. `https://www.academyizmir.com/private/config.example.php` ve `/assets/data/site.json` 404 vermeli.
5. 10 sayfa 200, bilinmeyen adres 404 (HTTP durum kodu gerçekten 404), `http://`, `academyizmir.com`, `/index.php`, `/egitimler/`, `/programs.php`, eski site adresleri 301 versin (hedef tek adımda).
6. Yanıt başlıklarında `X-Content-Type-Options`, `Referrer-Policy`, `X-Frame-Options`, `Permissions-Policy` görünmeli, `X-Powered-By` olmamalı.
7. Search Console'a `https://www.academyizmir.com/sitemap.xml` gönderilir, eski site için adres değişikliği izlenir.
8. `[İÇERİK GELECEK]` ve `[TEYİT EDİLECEK]` yazıları temizlendi (README.md ve brief/icerik.md'ye bakın), form gönderimi ve `data-prototype` kodu bağlandı (AI-25).
9. Sunucudaki gerçek bir dosya önbellek denetimi: CSS/JS değişince `?v=` ile adres değiştirilir.
10. Lighthouse ve gerçek cihaz testi yayında yeniden yapılır.

## v-1 ile bilinçli farklar

- Bağlantı adresleri Türkçe temiz adreslerdir (`/egitimler`, `/on-basvuru?program=theatre` vb.), varlıklar kök göreli (`/assets/...`).
- Görsellerin `width`/`height` değerleri gerçek dosyadan okunur: 2024 kazananlarının görselleri 278×278'dir (v-1'de 400 yazıyordu, oran aynı).
- Kazananlar sayfasında 2025 grubu görselleri `loading="lazy"` alır (v-1'de eksikti), yalnızca ilk 3 görsel hemen yüklenir.
- `head`: canonical, `og:url` ve `og:image` tam adreslerle. 404 sayfası `noindex`.
- `assets/` yalnızca kullanılan dosyaları içerir (orijinal kaynak klasörleri, kazananlar `.jpg` posterleri, `kisa-10`, styleguide ve `data/` yok).
