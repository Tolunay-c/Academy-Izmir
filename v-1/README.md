# Academy İzmir — v-1 (statik prototip)

Academy İzmir sitesinin yeniden tasarımı. Saf HTML, CSS ve vanilla JS; framework ve build adımı yok. Sonradan PHP'ye bölünecek. İçeriğin tek kaynağı `../brief/icerik.md`, tasarım kararları `DESIGN.md` içindedir.

## Klasör yapısı

```
v-1/
  index.html ... contact.html     8 sayfa (statik HTML)
  application.html                Eğitim Ön Başvuru Formu (menüde yok, header butonu, mobil menü ve footer menüsünden bağlanır)
  privacy-notice.html             KVKK Aydınlatma Metni (menüde yok, footer alt satırından ve form onay kutusundan bağlanır)
  styleguide.html                 Bileşen rehberi (noindex, sitedeki hiçbir sayfadan bağlanmaz)
  DESIGN.md                       Tasarım sistemi ve bileşen listesi
  README.md                       Bu dosya
  assets/
    css/        tokens.css, base.css, components.css, styleguide.css
    js/         main.js
    data/       site.json (içerik verisi)
    images/     logo/, og-image.jpg, kadro/, kazananlar/2024/, kazananlar/2026/, galeri/ (+ orijinal kaynak klasörleri, aşağıya bak)
    video/      hero-reels, kisa-3, kisa-9, kisa-10 (+ poster .webp dosyaları)
```

## Sayfalar

| Dosya | Menü adı | İçerik |
|---|---|---|
| `index.html` | Ana Sayfa | Hero (dikey video), öne çıkanlar, eğitimler listesi, Neden Academy İzmir, ilk 4 eğitmen, 2026 kazananları, galeri mozaiği, başvuru kapanışı |
| `programs.html` | Eğitimlerimiz | Sayfa içi navigasyon, 5 program bölümü (detay metinleri, alt programlar, video, fotoğraf, ders programı yer tutucusu, "Bu Eğitime Başvur") |
| `about.html` | Hakkımızda | Hakkımızda metninin tamamı, fotoğraflar, Kurucularımız, kapanış |
| `faculty.html` | Eğitmen Kadromuz | Kadro dizini, 10 eğitmen profili (özgeçmişler doğrudan açık) |
| `winners.html` | Kazananlarımız | Başarı bloğu, yıl ve kategori filtresi (URL parametreli), yıllara göre kazanan kartları |
| `gallery.html` | Sanatsal Projelerimiz ve Galeri | Proje yer tutucusu, 2 video, 33 fotoğraflık mozaik, lightbox |
| `announcements.html` | Güncel Kayıtlar ve Duyurular | Boş durum (duyuru yok), Başvur ve telefon eylemleri |
| `application.html` | Eğitim Ön Başvuru Formu | Ön başvuru formu (`form#application-form`), yanda telefon/WhatsApp. Kapanış bandı yok. `?program=<id>` ile ana program seçili gelir |
| `contact.html` | İletişim ve Başvuru | "Eğitim Ön Başvuru Formu" bloğu (`application.html`'e gider), telefon/WhatsApp, iletişim listesi, harita bağlantıları. Form bu sayfada yok |
| `privacy-notice.html` | (menüde yok) | KVKK Aydınlatma Metni (`../brief/kvkk.md`, 8 bölüm, içindekiler listesi). Footer alt satırında ve form onay kutusunda bağlantı |

Sayfalar elle düzenlenir. Metinler `site.json` ve `icerik.md` ile birebir aynı tutulur, HTML'e statik yazılmıştır (arama motorları görsün, PHP'de sunucuda üretilecek).

## CSS dosyaları

| Dosya | Görevi |
|---|---|
| `tokens.css` | Tüm tasarım değerleri tek yerde: renk, font aileleri ve boyutları (fluid `clamp()`), satır yüksekliği, letter-spacing, spacing, radius, gölge, süre, easing, z-index, oranlar, ikon ayarları. Başka CSS dosyasında sabit değer yazılmaz. Breakpoint değerleri yorum olarak listelenir (`@media` içinde `var()` çalışmadığı için sorgularda aynen yazılır). |
| `base.css` | Reset, html/body, tipografi temeli, link, `:focus-visible`, görseller, `prefers-reduced-motion`. |
| `components.css` | Tüm bileşenler (buton, header, menü, footer, kartlar, form, strip, mozaik, lightbox ...). Yalnızca token'lar. |
| `styleguide.css` | Yalnızca `styleguide.html` yerleşimi. Canlı sayfalara yüklenmez. |

## site.json yapısı

| Alan | İçerik |
|---|---|
| `site` | `name`, `description`, `home_title`, `tagline`, `intro`, `founded`, `highlights[]` |
| `contact` | `phone {display, tel, pending}`, `whatsapp {display, url, pending}`, `email {value, url, pending}` (hepsi teyitli, `pending: false`), `address {value, pending, map_url, directions_url}` ve `instagram {handle, url, pending}` (teyitli) |
| `programs[]` | `id`, `title`, `main_text`, `card_summary`, `detail[]` (paragraflar), `success_block`, `subprograms[] {id, title, card_summary, detail[]}` |
| `faculty[]` | `id`, `order` (1-10, sıra zorunlu), `name`, `titles[]`, `bio` (Ebru Parıltı için `null`), `image` (kırpılmış 4:5 webp), `alt` |
| `winners[]` | `id`, `name`, `year`, `category` (`university` / `art-high-school`), `image`, `alt`, `placements[] {university, faculty, program, scholarship}` |
| `gallery[]` | `id`, `src_800`, `src_1600`, `orientation`, `alt`, `category` (şimdilik `null`, ileride filtre için) |
| `videos[]` | `id`, `src`, `poster`, `orientation` (`kisa-10` bilerek yok) |
| `application` | Başvuru formu seçenekleri, her biri `{id, label}`: `age_groups[]` (5), `education_levels[]` (8, müşteri onayı bekliyor: icerik.md Bölüm 11 madde 15), `art_experience[]` (Var/Yok). Form HTML'i bu veriden statik üretilir. Program seçenekleri `programs[]` başlıklarından gelir |
| `announcements[]` | Şimdilik boş. Öğe yapısı: `id`, `date` (ISO), `title`, `description`, `program_id` veya `null`, `link {url, label}` veya `null` (ayrıntı DESIGN.md bölüm 19) |

Not: `site.json` şu an çalışma zamanında okunmuyor, sayfalar statik. PHP aşamasında şablonlar bu veriyi okuyacak.

## main.js modülleri

Tek dosya, her blok kendi `data-` attribute'larını arar ve ilgili öğe yoksa hiçbir şey yapmaz.

| Modül | Çalıştığı sayfa | Görevi |
|---|---|---|
| `no-js` → `js` | hepsi | `<html>` class'ını değiştirir (JS'siz yedek stilleri için) |
| Header ve mobil menü | hepsi | Kaydırınca header çizgisi, tam alan menü (odak yönetimi, Esc, `inert`, kaydırma kilidi) |
| Hero video | index | Otomatik oynatma (reduced-motion ve Save-Data'da kapalı), oynat/durdur, ses |
| Yatay şerit (`data-strip`) | index, programs | Önceki/sonraki düğmeleri, klavye ile gezinme |
| Sayfa içi navigasyon | programs | Aktif bölüm (`aria-current`), kenar gradyanı |
| Video oynatıcı | programs, gallery | Tıkla-oynat, otomatik oynatma yok |
| Kenar gradyanlı kaydırma (`data-fade-scroll`) | winners | Filtre satırları ipucu |
| Kazananlar filtresi | winners | Yıl/kategori filtresi, `aria-live` sayaç, `replaceState` URL |
| Lightbox | gallery | Native `<dialog>`, ok tuşları, kaydırma, sayaç |
| Ön başvuru formu (`data-application-form`) | application | Telefon maskesi, ad filtresi, doğrulama (doğum tarihi `max` = bugün dahil), hata özeti, `?program=`, **prototip gönderim mesajı (`data-prototype`)** |

## Partial blokları (PHP'ye geçiş)

10 sayfada (9 sayfa + `application.html`) birebir aynı olan dört blok HTML yorumlarıyla işaretlidir (yalnızca `aria-current="page"` sayfaya göre değişir):

```
<!-- partial:header:start -->      ... <!-- partial:header:end -->       (skip link + site-header)
<!-- partial:mobile-menu:start --> ... <!-- partial:mobile-menu:end -->
<!-- partial:footer:start -->      ... <!-- partial:footer:end -->
<!-- partial:contact-bar:start --> ... <!-- partial:contact-bar:end -->  (mobil alt iletişim çubuğu)
```

PHP'de her blok ayrı dosyaya (`partials/header.php`, `mobile-menu.php`, `footer.php`, `contact-bar.php`) taşınır, `aria-current` mevcut sayfaya göre üretilir. `<head>` içindeki ortak bağlantılar (font, ikon, CSS, JS) ve `icon_names` listesi 10 sayfada aynıdır, tek bir `head.php`'de toplanabilir. Menü eşiği ve CSS breakpoint'leri `tokens.css` yorum listesiyle birebir aynıdır.

## Yerelde çalıştırma

```
cd v-1
python3 -m http.server 8000
```

Sonra `http://localhost:8000/index.html` adresini açın. `file://` ile açmayın (bazı tarayıcılar `<dialog>`, font ve veri isteklerini kısıtlar). Fontlar ve ikonlar Google Fonts CDN'den geldiği için internet gerekir.

## Yayın öncesi yapılacaklar

1. **Prototip kodunu kaldır:** `application.html` içindeki `data-prototype` bloğu ve `main.js`'teki ilgili kod (form şu an sunucuya bağlı değil).
2. **Form gönderimini bağla:** başvuruların gideceği e-posta (icerik.md Bölüm 11, madde 13), sunucu tarafı doğrulama, spam koruması.
3. **KVKK:** aydınlatma metni eklendi (`privacy-notice.html`, kaynak `../brief/kvkk.md`). Yayından önce müşteri ya da hukukçu onayı önerilir. Açık rıza metni ayrıca gerekiyorsa (reklam, kampanya, ticari ileti) eklenecek.
4. **Logo:** geçici olarak mevcut görselden hazırlandı (270 px'lik `academy-izmir-logo-Photoroom.png` kaynaklı potrace vektörü, `assets/images/logo/academy-izmir-logo.svg`, DESIGN.md bölüm 24). Orijinal vektör dosyası (SVG, AI, PDF ya da EPS) gelince bu dosya değiştirilecek, header ve footer'daki inline SVG'ler de güncellenecek (10 sayfa).
5. **Favicon:** şu an `<link rel="icon" href="data:,">`.
6. **Paylaşım görseli:** eklendi (`assets/images/og-image.jpg`, 1200×630). 10 sayfada `og:image` göreli yoldur, yayında tam URL'ye (`https://alanadi/assets/images/og-image.jpg`) çevrilecek. `og:title`, `og:description`, `og:type`, `og:locale`, `og:image:width`, `og:image:height`, `og:image:alt` mevcut.
7. **Google etiketleri ve dönüşüm takibi:** `data-track="phone"`, `"whatsapp"`, `"form"`, `"email"` işaretleri hazır (ayrıca `"instagram"`, `"map"`, `"directions"`), etiket bilgileri bekleniyor.
8. **Eski URL'lerden 301 yönlendirmeleri** yeni sayfalara. Sayfa dosya adları İngilizce, URL'lerin Türkçe olup olmayacağı PHP aşamasında kararlaştırılacak.
9. **Sunucuda sıkıştırma ve önbellek:** gzip/brotli, görsel/CSS/JS/font için uzun ömürlü `Cache-Control`.
10. **Lighthouse'u yayında yeniden ölç:** yerel ölçümlerde Performance sıkıştırmasız dosyalar yüzünden düşük çıkıyor.
11. **Yayına alınmayacak dosyalar:** `assets/images/Fwd__*` ve `drive-download-*` orijinal klasörleri (yaklaşık 900 MB), `kazananlar/*/*.jpg` kaynak posterleri, kullanılmayan `kisa-10.mp4` (onay yok, `icerik.md` Bölüm 7 ve Bölüm 11 madde 7), `styleguide.html` ve `styleguide.css`.
12. **Yer tutucuları temizle:** sitede görünen `[İÇERİK GELECEK]` ve `[TEYİT EDİLECEK]` yazılarının hiçbiri yayında kalmamalı.
13. **Kullanılmayan CSS'i gözden geçir** (sadece styleguide'da kullanılan bileşenler dahil).

## Müşteriden beklenen içerikler

`../brief/icerik.md` Bölüm 11 "Açık Sorular" (15 madde):

- 1: Güzel Sanatlar Lisesine Hazırlık alt program olacak mı?
- 2: Yazısız kadro portreleri
- 3: (yanıtlandı)
- 4: (yanıtlandı)
- 5: Yönetim paneli ihtiyacı
- 6: Efe Pak'ın ikinci yerleşmesi
- 7: kisa-10 videosu
- 8: Kazananların poster olmayan orijinal fotoğrafları
- 9: Logonun vektörel dosyaları
- 10: Çocuk fotoğrafları için veli izinleri
- 11: Galeri kategorileri ve fotoğrafların hangi etkinliğe ait olduğu
- 12: (yanıtlandı)
- 13: Başvuruların gideceği e-posta adresi
- 15: Başvuru formundaki Eğitim Durumu seçeneklerinin onayı
- 14: 2025 kazananlarının üniversite, fakülte ve bölüm bilgileri; Beren Özdemir, İlay Kıngır ve Rasim Tuna Yılmaz'ın 2025'te de kazanıp kazanmadığı

Ayrıca bekleyenler: ders programları, 2025 kazananlarının yerleşme bilgileri (kartlarda `[İÇERİK GELECEK]`), Güzel Sanatlar Lisesi kazananları, Sanatsal Projelerimiz kapsamı (Academy İzmir Trio dahil), kurucuların güncel birlikte fotoğrafı, duyurular, sayfa paylaşım görseli.
