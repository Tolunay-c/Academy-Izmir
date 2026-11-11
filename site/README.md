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
    application-form.php  ön başvuru formu back-end'i (oturum, CSRF, tuzaklar, hız sınırı, doğrulama, PRG)
    application-mail.php  başvuru e-postası (PHPMailer)
    vendor/, composer.*   PHPMailer (repoda, sunucuda composer ÇALIŞTIRILMAZ, dosya olarak yüklenir)
    storage/              çalışırken oluşur: logs/, ratelimit/, sessions/ (repoya girmez, yazma izni gerekir)
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

Sunucuda `private/config.php` dosyasını oluşturup aşağıdaki değerleri doldurun (`config.php` repoya girmez):

| Alan | Anlamı |
|---|---|
| `base_url` | `https://www.academyizmir.com`. Boşsa bu kullanılır (canonical, og:url, og:image, sitemap bundan türer). |
| `smtp_host` | Giden posta sunucusu, örn. `mail.academyizmir.com`. |
| `smtp_port` | `587` (STARTTLS) ya da `465` (SSL). |
| `smtp_secure` | `tls` (587), `ssl` (465) ya da boş (şifreleme yok, yalnızca yerel test). |
| `smtp_user`, `smtp_pass` | Gönderen posta kutusunun (örn. `noreply@academyizmir.com`) kullanıcı adı ve şifresi. Plesk'te önce bu posta kutusunu oluşturun. |
| `smtp_verify_peer` | `true` önerilir. Sunucunun sertifikası alan adıyla eşleşmiyorsa (bağlantı "certificate verify failed" ile kopuyorsa) geçici çözüm olarak `false` yapılabilir. Kalıcı çözüm: `smtp_host` değerini sertifikadaki alan adıyla aynı yazmak. |
| `mail_from`, `mail_from_name` | Gönderen adresi ve adı. SPF/DKIM için sunucunun alan adıyla aynı domain'den olmalı (`noreply@academyizmir.com`). |
| `mail_to` | Alıcı adresler dizisi. Başvuru, hepsine tek e-posta olarak gider (hepsi To alanında): `info@academyizmir.com`, `ocicaney@gmail.com`. |
| `app_secret` | Zaman tuzağı imzası ve IP özeti için rastgele uzun anahtar. Üretmek için: `php -r "echo bin2hex(random_bytes(32));"` (64 karakter). Gizli tutun, değiştirirseniz açık form sayfaları bir kez geçersiz olur. |
| `head_scripts` | `<head>` sonuna eklenecek ham HTML parçaları dizisi (Google Tag Manager, Analytics, Ads). Boş dizi: hiçbir şey basılmaz. Parçalar kaçışlanmadan basılır, yalnızca güvenilir kod girin. |
| `search_console_meta` | Search Console doğrulama kodu (meta content değeri). Boşsa etiket basılmaz. |

`config.php` yoksa ya da mail ayarları boşsa form hata vermez, "Başvurunuz şu anda gönderilemedi" ekranını gösterir ve nedeni loglar (`mail_not_configured`).

## Ön başvuru formu (/on-basvuru)

Form aynı adrese POST eder (`application.php`), JS kapalıyken de sunucu tarafı doğrulamayla çalışır. Kurallar `main.js` ile aynıdır (ad harf filtresi ve en az 2 harf, doğum tarihi 1930-01-01 ile bugün arası, seçenekler `site.json` `application` ve `programs` kayıtlarından, telefon 0 ile başlayan 11 rakam, e-posta isteğe bağlı, mesaj en fazla 2000 karakter, KVKK onayı zorunlu).

- **Sonuç:** POST sonrası PRG: `?durum=basarili` ya da `?durum=hata` adresine 303. Sonuç oturumdaki tek kullanımlık mesajla gösterilir, adres elle yazılarak tetiklenemez, sayfa yenilenince form tekrar gönderilmez.
- **Başarı ekranında** `window.dataLayer` varsa `application_submit` olayı gönderilir (Google kodu gelince dönüşüm ölçümü), yoksa hiçbir şey yapılmaz.
- **Spam koruması:** oturuma bağlı CSRF belirteci (çerez Secure, HttpOnly, SameSite=Lax; oturum yalnızca bu sayfada başlar), görünmez `website` alanı (bal tuzağı), imzalı zaman damgası (3 saniyeden kısa gönderimler), aynı IP'den saatte en fazla 5 başvuru. Tuzağa düşen gönderimlere başarı mesajı gösterilir, e-posta gitmez. Hız sınırı aşılırsa kullanıcı "gönderilemedi" ekranını görür.
- **Veri:** başvurular sunucuda dosyaya ya da veritabanına KAYDEDİLMEZ, yalnızca e-posta gönderilir. E-postada yalnızca formdaki alanlar vardır (IP, tarayıcı bilgisi yok). Gönderim hatasında formun değerleri, hata ekranını göstermek için oturumda geçici tutulur ve gösterildikten sonra silinir. Hız sınırı kaydında IP'nin kendisi değil `app_secret` ile özeti tutulur.
- **Log:** `private/storage/logs/errors-YYYY-MM.log`. Yalnızca hata türü ve kısa ileti (zaman, `csrf_failed`, `rate_limit`, `mail_send_failed`, `mail_not_configured`). Ad, telefon, e-posta, mesaj yazılmaz (e-posta adresleri ve uzun sayılar iletiden silinir).

### storage klasörü ve yazma izni

`private/storage/` yoksa çalışırken oluşturulur (içinde `logs`, `ratelimit`, `sessions`). Plesk Windows'ta PHP'nin çalıştığı kullanıcıya bu klasör için **Değiştir (Modify)** izni gerekir: Plesk'te *Dosyalar* ▸ `private` klasörü ▸ *İzinler* ▸ alan adının IIS kullanıcısı (`IIS AppPool\<alan adı>` ya da `IUSR`) için yazma izni. Yazılamazsa form yine çalışır: hız sınırı devre dışı kalır, oturum PHP'nin varsayılan klasörüne düşer, loglar yazılmaz.

### SMTP sorunlarında kontrol edilecekler

1. Log: `mail_send_failed` satırındaki ileti ("Could not connect", "authenticate", "certificate" ...).
2. Port ve şifreleme eşleşmeli: 587 + `tls`, 465 + `ssl`. Sunucunun giden bağlantıları engelleyen güvenlik duvarı olmamalı.
3. `smtp_user` ve `smtp_pass` posta kutusunun gerçek bilgileri olmalı, kutu Plesk'te oluşturulmuş olmalı.
4. Sertifika hatası ("certificate verify failed"): `smtp_host` sertifikadaki alan adıyla aynı olmalı. Olmuyorsa geçici olarak `smtp_verify_peer` => `false`.
5. `mail_from` adresi, `smtp_user` ile aynı domain'den olmalı (aksi hâlde sunucu reddedebilir ya da Gmail spam'e atar).
6. PHP'de `openssl` uzantısı açık olmalı (STARTTLS/SSL için).

### Formu canlıda test etme

1. `config.php` dolduruldu, `private/storage` yazılabilir.
2. https://www.academyizmir.com/on-basvuru adresinde formu gerçek bilgilerle (kendi e-postanızı yazarak) doldurup gönderin. En az 3 saniye bekleyin, daha hızlı gönderimler sessizce yok sayılır.
3. Başarı ekranı ("Ön başvurunuz bize ulaştı.") görünmeli, `info@academyizmir.com` ve `ocicaney@gmail.com` kutularına tek bir e-posta gelmeli. Konu: `Yeni Ön Başvuru: <Ad Soyad> - <Eğitim>`, Reply-To: yazdığınız e-posta.
4. Gmail'e gelen test e-postasında **"Orijinali göster"** ile **SPF, DKIM ve DMARC** sonuçlarının **PASS** olduğunu kontrol edin. PASS değilse alan adının DNS kayıtlarını (SPF, DKIM, DMARC) düzeltin, yoksa başvurular spam'e düşer.
5. E-posta gelmediyse önce spam klasörüne, sonra `private/storage/logs` dosyasına bakın.
6. Hata ekranını denemek için geçici olarak `smtp_pass` değerini bozun, gönderin, sonra düzeltin: "Başvurunuz şu anda gönderilemedi" bildirimi ve dolu form görünmeli.
7. Test başvurusunu hızla 6 kez göndermeyin: aynı IP'den saatte en fazla 5 başvuru kabul edilir.

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
| `/ozgur-baskaya.html` | `/egitmen-kadromuz#ozgur-baskaya` |
| `/ozlem-baskaya.html` | `/egitmen-kadromuz#ozlem-baskaya` |
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

1. `config.php` oluşturuldu ve dolduruldu (SMTP ayarları, `mail_to`, `app_secret`, `head_scripts`, `search_console_meta`). `private/vendor` (PHPMailer) yüklendi, `private/storage` yazılabilir.
2. `php site/private/tools/build.php` çalıştırıldı, `web.config` ve `sitemap.xml` güncel.
3. Plesk'te PHP 8.2 (FastCGI) seçili. IIS'te URL Rewrite modülü kurulu.
4. `httpdocs/` ve `private/` doğru yere yüklendi. `https://www.academyizmir.com/private/config.example.php` ve `/assets/data/site.json` 404 vermeli.
5. 10 sayfa 200, bilinmeyen adres 404 (HTTP durum kodu gerçekten 404), `http://`, `academyizmir.com`, `/index.php`, `/egitimler/`, `/programs.php`, eski site adresleri 301 versin (hedef tek adımda).
6. Yanıt başlıklarında `X-Content-Type-Options`, `Referrer-Policy`, `X-Frame-Options`, `Permissions-Policy` görünmeli, `X-Powered-By` olmamalı.
7. Search Console'a `https://www.academyizmir.com/sitemap.xml` gönderilir, eski site için adres değişikliği izlenir.
8. `[İÇERİK GELECEK]` ve `[TEYİT EDİLECEK]` yazıları temizlendi (README.md ve brief/icerik.md'ye bakın). Ön başvuru formu canlıda test edildi (yukarıdaki "Formu canlıda test etme" adımları, Gmail'de SPF/DKIM/DMARC PASS).
9. Sunucudaki gerçek bir dosya önbellek denetimi: CSS/JS değişince `?v=` ile adres değiştirilir.
10. Lighthouse ve gerçek cihaz testi yayında yeniden yapılır.

## v-1 ile bilinçli farklar

- Bağlantı adresleri Türkçe temiz adreslerdir (`/egitimler`, `/on-basvuru?program=theatre` vb.), varlıklar kök göreli (`/assets/...`).
- Görsellerin `width`/`height` değerleri gerçek dosyadan okunur: 2024 kazananlarının görselleri 278×278'dir (v-1'de 400 yazıyordu, oran aynı).
- Kazananlar sayfasında 2025 grubu görselleri `loading="lazy"` alır (v-1'de eksikti), yalnızca ilk 3 görsel hemen yüklenir.
- `head`: canonical, `og:url` ve `og:image` tam adreslerle. 404 sayfası `noindex`.
- `assets/` yalnızca kullanılan dosyaları içerir (orijinal kaynak klasörleri, kazananlar `.jpg` posterleri, `kisa-10`, styleguide ve `data/` yok).
