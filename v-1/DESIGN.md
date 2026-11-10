# Academy İzmir — Tasarım Sistemi (v-1)

Tüm değerlerin tek kaynağı `assets/css/tokens.css`. Bu dosya kararların gerekçesidir. Canlı örnekler: `styleguide.html`.

## 1. Konsept

Bir tiyatro programı ya da opera sezon kitapçığı gibi: sıcak siyah bir zeminde fildişi metin, tek bir mat pirinç altın vurgu ve geniş boşluklar. Başlıklar karakterli bir serif ile, italik bir vurgu kelimesiyle yazılır. Gerçek sahne fotoğrafları ve portreler sayfayı taşır, süs ve ikon azdır.

Ui-ux-pro-max verisinden **Editorial Grid / Magazine** stili (asimetrik grid, editoryal tipografi, ince bölücüler) seçildi, koyu zemin için **Dark Mode (OLED)** kuralları (derin siyah zemin, 4.5:1 metin kontrastı, abartısız parıltı) uygulandı. Paleti **Luxury/Premium Brand** satırı yönlendirdi (stone ailesi, `#1C1917` zemin, altın vurgu). Satırdaki parlak altın (`#CA8A04`) yerine koyu zeminde daha ağırbaşlı bir mat pirinç (`#C9A66B`) türetildi.

## 2. Renkler

| Token | Değer | Rol | Zemin (`#0F0D0B`) kontrastı |
|---|---|---|---|
| `--color-bg` | `#0F0D0B` | Sayfa zemini | — |
| `--color-surface` | `#1A1714` | Kart ve form yüzeyi | — |
| `--color-surface-raised` | `#26211B` | Öne çıkan yüzey, hover zemini | — |
| `--color-text` | `#F2ECE1` | Ana metin | 16.50:1 (AAA) |
| `--color-text-muted` | `#B5AB9C` | İkincil metin | 8.56:1 (AAA), yüzeyde 7.88:1 |
| `--color-accent` | `#C9A66B` | Tek vurgu: üst etiket, link, birincil buton | 8.45:1 |
| `--color-accent-hover` | `#DDBE8A` | Vurgu hover, focus halkası | 10.90:1 |
| `--color-on-accent` | `#0F0D0B` | Vurgu zemini üstündeki metin | vurguya karşı 8.45:1 |
| `--color-line` | `#3A332B` | Dekoratif ayırıcı çizgi | 1.56:1 (yalnızca süs) |
| `--color-line-strong` | `#7A6F61` | Form kenarlığı | 3.95:1 (bileşen sınırı için 3:1 yeterli) |
| `--color-success` | `#8CC09A` | Başarı durumu | 9.35:1 |
| `--color-error` | `#E8907F` | Hata durumu | 8.06:1, yüzeyde 7.42:1 |

Kurallar: Vurgu rengi sayfa başına az kullanılır. Metin hiçbir zaman `--color-line` ile yazılmaz. Durum bilgisi yalnızca renkle verilmez, metin eşlik eder. Not: `--color-text-muted` en düşük metin rengidir, daha soluk üçüncü bir metin rengi bilerek tanımlanmadı (`#8F8576` yükseltilmiş yüzeyde 4.40:1 verip AA'yı geçemiyor).

## 3. Tipografi

- **Başlık:** Cormorant, 500 (başlıklar), 600 (nadiren), 500 italik (vurgu kelimesi). Yüksek kontrastlı, sahne afişi hissi veren bir display serif.
- **Metin:** Jost, 400 (gövde), 500 (etiket, buton), 600 (kalın). Web fontu yüklenene kadar kullanılan sistem fontu `size-adjust` ile ayarlanmış yedek font yüzleriyle (`Jost Fallback` = Arial %94,5, `Cormorant Fallback` = Georgia %88,5) web fontunun genişliğine yaklaştırılır, böylece font değişiminde sayfa kaymaz (Lighthouse CLS 0). Font CSS'i render'ı engellemeyecek şekilde (`preload` + `onload`) yüklenir. Geometrik ve okunaklı, başlıkla çatışmayan sade bir sans.
- İki font da `latin-ext` alt kümesiyle Google Fonts'tan yüklenir. Türkçe karakterler (ç ğ ı İ ö ş ü, büyük ve küçük) gerçek font dosyalarında doğrulandı.
- Cormorant ince çizgili olduğu için 1.5rem altında kullanılmaz.

Fluid boyut skalası (320 px ile 1440 px arasında akar):

| Token | Değer | Kullanım |
|---|---|---|
| `--text-label` | `clamp(0.8125rem, 0.79rem + 0.1vw, 0.875rem)` | Yalnızca büyük harfli kısa etiketler: üst etiket, buton, form etiketi, kart kategorisi, yıl, alt çubuk |
| `--text-sm` | `clamp(1rem, 0.97rem + 0.12vw, 1.0625rem)` | Kart metni, rol satırı, ipucu, hata, burs etiketi (16 px altına inmez) |
| `--text-base` | `clamp(1rem, 0.96rem + 0.2vw, 1.125rem)` | Gövde metni, form alanı |
| `--text-lg` | `clamp(1.125rem, 1.05rem + 0.4vw, 1.375rem)` | Giriş metni (lead) |
| `--text-xl` | `clamp(1.5rem, 1.3rem + 1vw, 2.125rem)` | Kart başlığı (h3) |
| `--text-2xl` | `clamp(1.875rem, 1.45rem + 2vw, 3rem)` | Alt başlık (h2 temel) |
| `--text-3xl` | `clamp(2.375rem, 1.65rem + 3.4vw, 4.25rem)` | Bölüm başlığı |
| `--text-4xl` | `clamp(2.875rem, 1.75rem + 5.4vw, 6.25rem)` | Ana sayfa ve hero başlığı (h1) |

Satır yüksekliği: `--leading-display` 1.02 (h1), `--leading-heading` 1.15 (başlıklar), `--leading-snug` 1.4 (etiket, kart alt satırı), `--leading-body` 1.65 (gövde).

Letter-spacing: `--tracking-display` -0.015em (başlıklar), `--tracking-body` 0 (gövde), `--tracking-label` 0.16em (büyük harfli küçük etiket, buton).

**Kural:** Gövde metni ve kart metinleri hiçbir genişlikte 16 px'in altına düşmez (`--text-sm` ve `--text-base` alt sınırı 1rem). 16 px altına yalnızca `--text-label` iner (13 px), o da sadece büyük harfli, kısa etiketlerde kullanılır. Etiket olmayan her metin `--text-sm` ya da üstüdür.

Eleman eşlemesi: Bölüm üst etiketi, buton, form etiketi, kart kategorisi, yıl etiketi `--text-label` + `--tracking-label` + büyük harf. Bölüm başlığı `--text-3xl`. Kart başlıkları `--text-xl`. Gövde `--text-base`, kart metni `--text-sm`.

## 4. Boşluk, container, grid

- **Spacing (4 px tabanlı):** `--space-1` 0.25rem, `-2` 0.5, `-3` 0.75, `-4` 1, `-5` 1.5, `-6` 2, `-7` 3, `-8` 4, `-9` 6, `-10` 8 rem.
- **Bölüm boşluğu:** `--space-section` `clamp(4rem, 2.6rem + 6vw, 9rem)`. **Gutter:** `--gutter` `clamp(1rem, 0.5rem + 2.5vw, 2.5rem)`.
- **Container:** `--container-narrow` 42rem (form, okuma), `--container` 72rem (varsayılan), `--container-wide` 90rem. Okuma ölçüsü `--measure` 65ch.
- **Grid:** Mobilde tek sütun. Kart ızgaraları `repeat(auto-fill, minmax(min(var(--card-min), 100%), 1fr))` ile akar, `--card-min` 16rem. Sayfa düzenlerinde 12 sütunlu asimetrik yerleşim kullanılacak (`--grid-columns`).

## 5. Radius, çizgi, gölge

- **Radius (3 değer):** `--radius-none` 0 (varsayılan: buton, kart, görsel, form), `--radius-sm` 2px (ayrılmış, şimdilik kullanılmıyor), `--radius-round` 999px (yalnızca kazanan fotoğrafı ve burs etiketi).
- **Çizgi:** `--line-width` 1px (ayırıcı, kenarlık), `--line-width-strong` 2px (kazanan kartı üst çizgisi, hover alt çizgisi). Focus halkası `--focus-ring-width` 2px, boşluk `--focus-ring-offset` 3px.
- **Gölge:** Kart gölgesi yok. Tek gölge, mobil alt çubuğun üst kenarında `--shadow-bar`.

## 6. Hareket

- Süreler: `--duration-fast` 150ms (renk, focus), `--duration-base` 250ms (buton, ikon kayması), `--duration-slow` 500ms (görsel hover yakınlaşması).
- Easing: `--ease-out` `cubic-bezier(0.22, 1, 0.36, 1)` (varsayılan), `--ease-in-out` `cubic-bezier(0.65, 0, 0.35, 1)`.
- Hover yakınlaşması `--zoom-hover` 1.03. Hover yalnızca `@media (hover: hover)` içinde çalışır, dokunmatik cihazlarda tıklama alanı değişmez.
- **prefers-reduced-motion:** `tokens.css` içinde tüm süreler 0.01ms'e iner, `--zoom-hover` 1 olur. `base.css` smooth scroll'u kapatır ve animasyon süresini sıfıra yakın tutar. Hiçbir bilgi yalnızca hareketle verilmez.

## 7. Breakpoint'ler (mobile-first, minimum 320 px)

320 px = 20em (minimum), 480 px = 30em (sm), 768 px = 48em (md), 1024 px = 64em (lg), 1280 px = 80em (xl), 1600 px = 100em (2xl).

`@media` içinde `var()` kullanılamadığı için breakpoint'ler `tokens.css`'te **yorum satırı olarak** listelenir ve sorgularda em olarak yazılır. CLAUDE.md'deki istisna gereği sorgudaki değerler bu listeyle birebir aynı olmak zorundadır. Bileşenler mümkün olduğunca akışkan (clamp, auto-fill) kurulduğundan şu an iki breakpoint kullanılıyor: `48em` (mobil alt çubuk gizlenir, footer boşluğu normale döner) ve `64em` (masaüstü menü açılır). Kısa etiketli menünün (logo + 7 öğe + Başvur, öğeler arası 16 px) header container'ı içinde ihtiyaç duyduğu genişlik 880 px'tir, 1024 px'te kullanılabilir 942 px içinde 62 px pay kalır (yedek fontla 931 px, yine sığar). Daha dar ekranlarda mobil menü kullanılır.

## 8. İkonlar

- **Material Symbols Outlined.** İnce çizgili stil, editoryal serif ile uyumlu.
- Ayarlar: `--icon-wght` 300, `--icon-fill` 0, `--icon-grad` 0, `--icon-opsz` 24. Boyut `--icon-size` 1.5rem, küçük `--icon-size-sm` 1.25rem.
- Yalnızca kullanılan ikonlar yüklenir (`icon_names`, alfabetik). Site sayfaları: `arrow_back`, `arrow_forward`, `call`, `close`, `edit_square`, `menu`, `pause`, `play_arrow`, `volume_off`, `volume_up`. Styleguide: `arrow_forward`, `call`, `edit_square`, `expand_more`. Yeni ikon eklenince 10 sayfadaki link birlikte güncellenir.
- WhatsApp için resmi logonun tek renkli inline SVG hâli (`currentColor`), `--icon-size` ölçüsünde.
- Dekoratif ikon `aria-hidden="true"` alır. Tek başına anlam taşıyan ikon butonu `aria-label` zorunlu.

## 9. Görsel kullanımı

- **Kadro fotoğrafı:** Sabit 4:5 (`--ratio-portrait`). Poster yazıları ve iletişim bilgileri, görsel üretim aşamasında 4:5 olarak kırpılıp WebP'ye çevrilerek ortadan kaldırılır (`assets/images/kadro/*.webp`, kırpılan alanın kendi çözünürlüğünde, büyütme yok). Sayfada ek kırpma ya da `object-position` kullanılmaz. Yazısız portreler gelince dosyalar değiştirilir.
- **Kazanan fotoğrafı:** Posterdeki fotoğraf alanından çerçeve ve süs hariç kare olarak önceden kırpılmış WebP (`assets/images/kazananlar/<yıl>/*.webp`). Kart içinde 1:1 daire (`--radius-round`), kart genişliğini doldurur (en fazla 24rem). Poster yazıları kullanılmaz.
- **Galeri:** Yatay 3:2 (`--ratio-landscape`), dikey 2:3 (`--ratio-portrait-tall`). Liste `-800.webp`, büyütme `-1600.webp`.
- **Program kartı görseli:** 3:2, `object-fit: cover`.
- **Video alanları:** Hero dikey 9:16 (`--ratio-reels`), kısa videolar 16:9 (`--ratio-video`). Hepsinde poster görseli, sessiz otomatik oynatma, `prefers-reduced-motion` altında otomatik oynatma kapalı.
- Tüm görseller `loading="lazy"`, `width` ve `height` ile yer ayrılmış (CLS yok), alt metinli.

## 10. Bileşenler

| Bileşen | Class | Tanım |
|---|---|---|
| Birincil buton | `button button-primary` | Dolgulu vurgu zemini, koyu metin, büyük harfli küçük etiket, min 44 px yükseklik. |
| İkincil buton | `button button-secondary` | Şeffaf, `--color-line-strong` kenarlık, hover'da vurgu rengine döner. |
| Metin butonu | `button button-text` | Çerçevesiz, vurgu renginde, sağında ok ikonu hover'da kayar. |
| Link | `text-link` | Vurgu renginde, altı çizili, min 44 px dokunma alanı. Paragraf içinde düz `a` kullanılır. |
| Bölüm üst etiketi | `section-eyebrow` | Solunda ince çizgi, vurgu renginde büyük harf etiket. |
| Bölüm başlığı | `section-title`, `section-header` | Cormorant 3xl, bir kelime italik ve vurgu renginde (`em`). |
| Site header | `site-header`, `site-logo`, `site-nav` | Sticky (akışın içinde, içeriği örtmez), yüksekliği `--header-height`. İçeriği sayfa içeriğiyle aynı `--container` genişliğindedir, logo içeriğin sol kenarıyla aynı hizadadır. Solda yazı logosu (Cormorant, "İzmir" italik vurgu), ortada 7 öğeli menü (kısa etiketler, bölüm 12'ye bak), sağda birincil "Başvur" butonu. Menüde Ana Sayfa yok, logo Ana Sayfa'ya gider. Aktif sayfa vurgu rengi + 2 px alt çizgi + `aria-current="page"`. Kaydırınca yalnızca alt çizgi belirir (`is-scrolled`), yükseklik değişmez. |
| Mobil menü | `mobile-menu`, `mobile-menu-link` | `64em` (1024 px) altında kullanılır. JS yokken (`html.no-js`) menü düğmesinin yerine footer'daki menüye (`#footer-nav`) giden bir bağlantı gösterilir (`site-header-toggle-link`). Header'ın altından başlayan tam alan panel, 8 öğe (Ana Sayfa dahil) Cormorant `--text-xl`, altında Başvur ve ikincil (ghost) stilde telefon ile WhatsApp düğmeleri, hepsi aynı genişlikte ve alt alta. `aria-expanded`/`aria-controls`, açıkken `main`/footer/alt çubuk `inert`, odak menüde döner, Esc kapatır ve odağı butona geri verir, gövde kaymaz. |
| Mobil alt iletişim çubuğu | `contact-bar`, `contact-bar-link` | Mobilde sabit, üç eşit sütun: Ara, WhatsApp, Başvur (vurgu zeminli). 768 px ve üstünde gizli. |
| Program kartı | `program-card` | 3:2 görsel, kategori etiketi, başlık, özet, metin butonu. Altında ince çizgi, gölge yok. |
| Eğitmen kartı | `instructor-card` | 4:5 önceden kırpılmış portre, isim, unvanlar (araya `/`), özgeçmiş `details` açılır alanı. |
| Kazanan kartı | `winner-card` | Yuvarlak fotoğraf, yıl, isim, yerleşme listesi (üniversite, fakülte, program), burs etiketi. Üstte 2 px vurgu çizgisi. |
| Galeri öğesi | `gallery-item` | Oranlı görsel, hover'da hafif yakınlaşma, büyük sürüme bağlanır. |
| Form alanları | `form` (en fazla `--container-narrow`, bölüm hizasında sola dayalı), `form-field`, `form-label`, `form-control`, `form-select`, `form-hint`, `form-error` | Görünür etiket, ipucu, hata `role="alert"`, 44 px alan, odakta vurgu kenarlığı. |
| Sayfa başlığı alanı | `page-header`, `page-header-title` | Üst etiket + `--text-3xl` başlık, altında ince çizgi. |
| Footer | `site-footer` | `--color-surface` zemin, üç sütun: yazı logosu + slogan, menü, iletişim. Teyit bekleyen alan kalmadı (`pending-note` yalnızca `[İÇERİK GELECEK]` için). Altta tek satırda telif ve "KVKK Aydınlatma Metni" bağlantısı (`site-footer-bottom`, flex; menüde değil, `privacy-notice.html`'de `aria-current="page"`). Mobilde alt çubuğun altında kalmaması için alt boşluğu `--contact-bar-height` kadar artar. |

## 11. Erişilebilirlik özeti

Metin kontrastı en az 4.5:1, ikincil metin 7:1 üstü. Her etkileşimli öğede görünür focus halkası. Tüm dokunma hedefleri en az 44x44 px. `lang="tr"`, atlama linki, `aria-hidden` dekoratif ikonlar, hata mesajları `role="alert"`. Hareket `prefers-reduced-motion` ile azalır.

## 12. Menü etiketleri notu

Masaüstü header menüsü yer kazanmak için kısa etiket kullanır: Eğitimler, Hakkımızda, Kadro, Kazananlar, Galeri, Duyurular, İletişim (sıra icerik.md bölüm 1 ile aynı). Header butonu "Eğitim Ön Başvuru Formu" (`application.html`): tam metinli menü 1024 px'te sığmadığı için (ölçüm: gereken 1051 px, kullanılabilir 942 px) 64em-80em arasında `site-header-cta-short` ("Ön Başvuru", gereken 923 px), 80em ve üstünde `site-header-cta-full` görünür. Görünmeyen span `display: none` olduğundan erişilebilir ad görünen metinle aynıdır. Menü öğeleri arası boşluk her genişlikte 16 px. Mobil menü, footer menüsü, sayfa başlıkları (`h1`) ve `<title>` etiketleri icerik.md bölüm 1'deki tam adları kullanır. Kısa etiketli bağlantılara `aria-label` eklenmez: görünen metin erişilebilir addır, ekran okuyucu ile görünen metin çelişmez.

## 13. Ana sayfa bileşenleri

| Bileşen | Class | Tanım |
|---|---|---|
| Hero | `hero`, `hero-text`, `hero-media`, `hero-video` | Sol metin (üst etiket, h1 "ACADEMY İZMİR" ile "İZMİR" italik vurgu, slogan, tanıtım, iki eylem), sağda dikey 9:16 video. Merkezlenmiş şablon yok. 48em altında video metnin altında, container genişliğini tamamen doldurur, 9:16 oranı korunur, yüksekliği `--hero-video-max-height` (75svh) ile sınırlıdır (uzunsa `object-fit: cover` ile üst/alttan kırpılır). 48em üstünde genişlik `--hero-video-width` ve ekran yüksekliğine göre sınırlanır (video ilk ekrana sığar). Eylem düğmeleri mobilde alt alta ve tam genişlikte, masaüstünde yan yana ve `--action-width` ile aynı genişliktedir (kapanış bandında da aynı kural). |
| Hero video kontrolleri | `hero-video-control` | Videonun sağ altında kare 44 px butonlar: oynat/duraklat (`aria-label` değişir) ve ses (`aria-pressed`, ikon değişir). Yalnızca JS varken görünür. |
| Öne çıkanlar | `highlights-list`, `highlights-item` | Numaralı tipografik satırlar (CSS sayacı), Cormorant `--text-2xl`, anahtar ifade ("2011'den", "15 yılı aşkın", "%80") vurgu renginde italik. Sayaç animasyonu ve ikon yok. |
| Program listesi | `program-list`, `program-row` | Numaralı editoryal liste: solda numara + başlık (ve varsa fotoğraf), sağda kart özeti ve alt programlar. 48em üstünde 5/7 asimetrik iki sütun. |
| Neden Academy İzmir | `why-grid`, `why-head`, `why-body`, `why-statement` | Solda başlık + bağlantı, sağda ilk üç paragraf. Burs ve erişim paragrafı kenarlıksız bir alıntı düzeniyle verilir: ilk cümle büyük, italik Cormorant (`why-statement-lead`), kalan metin gövde boyutunda okunabilir satır uzunluğunda (`why-statement-rest`). İkisi aynı `<p>` içindedir, metin bütünlüğü bozulmaz. Masaüstünde iki sütun (5/7), mobilde alt alta. |
| Yatay şerit | `strip`, `strip-list`, `strip-item`, `strip-control` | Kazanan kartları (her genişlikte) ve eğitmen kartları (yalnızca 48em altında, `strip-collapse` ile masaüstünde ızgaraya döner) yatay kaydırılır (`scroll-snap`). Şerit yalnızca kaydırılabiliyorsa `tabindex="0"` alır (JS yönetir), klavyeyle gezilir, sayfa kaymaz. Önceki/sonraki butonları JS varken görünür (`aria-disabled`). |
| Mozaik | `mosaic`, `mosaic-row`, `mosaic-item` (`is-landscape`, `is-portrait`) | Mobilde tüm fotoğraflar tam genişlikte (dikeyler `--mosaic-portrait-max-height` ile sınırlı). 48em üstünde her satır esnek: büyüme oranı görüntü oranına (`--ratio-value-*`) eşit olduğundan satırdaki fotoğraflar aynı yüksekliğe gelir ve satırın iki kenarı container ile hizalanır. Kırpma `--object-position-faces` ile yüzleri korur. `srcset` ile 800/1600 px. |
| Başvuru bandı | `cta-band` | `--color-surface` zeminli kapanış bölümü, başlık, Başvur / telefon / WhatsApp. |
| Bölüm başlığı satırı | `section-header-row` | Başlık solda, "Tüm ..." bağlantısı sağda, dar ekranda alta iner. |

Yeni token'lar: `--hero-video-width`, `--hero-video-max-height`, `--action-width`, `--strip-item-width`, `--mosaic-portrait-max-height`, `--object-position-faces`, `--ratio-value-landscape`, `--ratio-value-portrait`, `--ratio-value-reels`.

**Focus:** Odak çerçevesi yalnızca `:focus-visible` ile verilir (`base.css`), `:focus` için outline kaldırılmaz. Fare/dokunma sonrası çerçeve görünmez, klavyede görünür. Mobil menü fare/dokunma ile açılınca odak menü paneline (çerçevesiz), klavye ile açılınca ilk bağlantıya gider (`main.js` son giriş yöntemini izler). Butonların minimum yüksekliği `--touch-min` + `--line-width-strong` (46 px), ikonlu ve ikonsuz tüm butonlar aynı yüksekliktedir.

**Video davranışı:** `preload="none"`, `autoplay` attribute'u yok. Otomatik oynatma JS ile, yalnızca `prefers-reduced-motion` ve Save-Data tercihi yoksa başlar. Tercih varsa dosya hiç indirilmez, poster ve oynat butonu görünür. Ekran dışına çıkınca durur, geri gelince (kullanıcı durdurmadıysa) devam eder. Footer alt satırı (çizgi ve telif) sütunlarla aynı genişliktedir (`max-width: none`).

## 14. Eğitimlerimiz sayfası bileşenleri

| Bileşen | Class | Tanım |
|---|---|---|
| Sayfa içi navigasyon | `page-nav`, `page-nav-link` | Sayfa başlığının altında 5 programa bağlantı (01-05 numaralı). **Sticky**, header'ın hemen altında (`top: header + 1 px`), z-index header'ın altında (`--z-subnav`), akışın içinde olduğundan içeriği örtmez. Aktif bölüm `aria-current="true"` + vurgu rengi alt çizgisi, kaydırmayla JS günceller. Dar ekranda yatay kaydırılır (kaydırma çubuğu gizli), aktif bağlantı görünür tutulur. Gerekçe: uzun, 5 bölümlü bir sayfada konum bilgisi ve doğrudan atlama (ux: Active State, Sticky Navigation). |
| Program bölümü | `program-section`, `program-section-grid` | Her bölüm `<section id=program-id>`. Solda numara + h2, sağda içerik (48em üstünde 4/8). `scroll-margin-top` + `html` `scroll-padding-top` ile başlık header ve nav'ın altında kalmaz. |
| Giriş / alıntı | `program-intro`, `program-statement` | Ana metin Cormorant `--text-xl`. Psikolog vurgusu ve konservatuvar başarı bloğu aynı bileşen: ilk cümle büyük italik, kalanı gövde (aynı paragraf), üstte 2 px vurgu çizgisi. Konservatuvar bölüm sırası: detay, başarı bloğu, kazananlar şeridi + "Tüm Kazananlar", Ders Programı, Başvur. |
| Alt program | `program-sub` | h3 + detay metni, üstte ince çizgi. |
| Şiirsel metin | `poem`, `poem-line` | Retorika: her satır ayrı, Cormorant `--text-2xl`, son satır vurgu rengi italik. |
| Ders programı | `program-schedule` | "Ders Programı" alt başlığı + `[İÇERİK GELECEK]` (`pending-note`). |
| Video oynatıcı | `video-player`, `video-player-play` | 16:9 (`--ratio-video`), `preload="none"`, `autoplay` yok, poster + ortada 66 px oynat butonu (`aria-label`). Tıklayınca `controls` ile oynar, bir video başlayınca diğeri durur. JS yokken tarayıcı kontrolleri görünür. |

Yeni token'lar: `--page-nav-height`, `--play-button-size`, `--z-subnav`. `--mosaic-portrait-max-height` 60svh oldu.

Ana sayfa: kapanış butonları 64em (1024 px) ve üstünde üç eşit sütun tek satır, altında alt alta tam genişlik. Mozaikte son 3 fotoğraf yalnızca 48em üstünde (`is-desktop-only`, `display: none` + `loading="lazy"` olduğundan mobilde indirilmez). Program başlıkları `programs.html#<id>` bağlantısıdır.

**Sayfa içi navigasyon gradyanı:** Kaydırılacak içerik olan kenarda (`has-fade-start` / `has-fade-end`, JS yönetir) `--fade-width` genişliğinde, zemin rengine doğru solan gradyan görünür, uca gelince kaybolur. `pointer-events: none`, dokunmayı engellemez.

## 15. Hakkımızda sayfası bileşenleri

| Bileşen | Class | Tanım |
|---|---|---|
| Giriş | `about-intro-grid`, `about-slogan`, `about-lead` | Solda slogan (italik, vurgu rengi), sağda ilk paragraf büyük Cormorant, ikinci paragraf gövde (5/7 ızgara). |
| Ana metin akışı | `about-flow` | Metin sütunu burs vurgu bloğuyla aynı 5/7 ızgarada sağ sütunda, `--measure` okuma ölçüsü. Alıntı ve geniş fotoğraf tam genişlikte. Burs paragrafı ana sayfadaki `why-statement` bileşeniyle aynı. |
| Pull quote | `about-quote` | Cümle `--text-2xl` Cormorant (masaüstünde 4 satır), üstte vurgu çizgisi, `aria-hidden="true"`. 48em altında `display: none`. |
| Fotoğraf | `about-figure` (`is-wide` 21:9, `is-landscape`, `is-portrait`), `about-figure-pair` | Asimetrik, `srcset` 800/1600, ana sayfa mozaiğinden farklı fotoğraflar. Çiftte iki fotoğraf aynı üst hizadan başlar, yatay olan dikeyle aynı yüksekliğe uzanır (`object-fit: cover`). Mobilde tüm fotoğraflar tam genişlik, dikeyler en fazla 60svh. |
| Kurucular | `founders-grid`, `founders-cards`, `instructor-card.is-linked` | Solda `photo-placeholder` (tasarıma uyan `[İÇERİK GELECEK]` alanı), sağda iki eğitmen kartı; isim bağlantısı kartın tamamını `faculty.html#<id>`'ye götürür (`::after` ile). |
| Kapanış | `cta-band` + `cta-band-lead` | Ana sayfadaki bileşenin aynısı, başlığın üstünde giriş paragrafı. |

Yeni token'lar: `--fade-width`, `--ratio-wide`.

## 16. Eğitmen Kadromuz sayfası bileşenleri

| Bileşen | Class | Tanım |
|---|---|---|
| Kadro dizini | `faculty-index-list`, `faculty-index-link` | Numaralı (01-10) oyuncu listesi gibi isim dizini. 48em üstünde iki sütun, `grid-auto-flow: column` ile 01-05 solda, 06-10 sağda (DOM sırası = görsel sıra, Tab sırası = okuma sırası). Mobilde tek sütun. |
| Profil | `profile`, `profile-grid`, `profile-head`, `profile-media`, `profile-bio`, `profile-name` | Her eğitmen bir `<article id=eğitmen-id>`. **HTML sırası: head (numara + isim h2 + unvanlar), media (fotoğraf), bio (özgeçmişin tamamı).** Mobilde bu sırayla alt alta (isim fotoğrafın üstünde), fotoğraf `--profile-photo-max-height` (60svh) ile sınırlı. 48em üstünde `grid-template-areas` (`"media head" / "media bio"`, 4/8 sütun): fotoğraf solda, numara/isim/unvan ve özgeçmiş sağda. CSS `order` kullanılmaz. Profiller arası ince çizgi. |

**Fotoğraf konumu:** Dönüşümlü düzen kullanılmadı, fotoğraf her profilde solda. Gerekçe: sayfa bir kadro listesidir (taranarak okunur), sabit sol kenar tarama kolaylığı verir, okuma sırası = DOM sırası = görsel sıra, program bölümleriyle aynı 4/8 ızgara ve ana sayfadaki numaralı editoryal dille tutarlı. Ebru Parıltı aynı bileşenle, özgeçmiş alanı olmadan gösterilir (boş alan yok).

Yeni token: `--profile-photo-max-height`. Ana sayfadaki eğitmen kartları da `instructor-card.is-linked` (isim bağlantısı kartın tamamını `faculty.html#<id>` adresine götürür).

## 17. Kazananlarımız sayfası bileşenleri

| Bileşen | Class | Tanım |
|---|---|---|
| Giriş | `program-statement`, `winners-intro-body` | Konservatuvar başarı bloğu programs.html ile aynı bileşen (ilk cümle büyük italik, kalanı gövde, aynı `<p>`), altında programs.html#conservatory-preparation bağlantısı. |
| Filtre | `filters`, `filter-group`, `filter-label`, `filter-button` | Yıl (Tümü, 2026, 2025, 2024) ve Kategori (Tümü, Üniversite ve Konservatuvar, Güzel Sanatlar Lisesi) grupları (`role="group"`, görünür etiket). Düğmeler `aria-pressed`, seçili olan vurgu rengi dolgulu. Her satır `fade-scroll` içinde: dar ekranda yatay kaydırılır, kenar gradyanı (`has-fade-start/end`) ipucu verir, sayfa kaymaz. 64em altında iki grup alt alta, üstünde yan yana. Filtreler yalnızca `.js` varken görünür. |
| Sayaç | `filter-status` | `role="status"` + `aria-live="polite"`, "N öğrenci gösteriliyor". |
| Liste | `winners-year`, `winners-year-title`, `winner-grid`, `winner-grid-item` | Yıllara göre gruplu (en yeni üstte), her yıl h2 + `auto-fill` kart ızgarası (`winner-card` bileşeni, site.json sırası). Kartlar `data-year`/`data-category` taşır, gizleme `is-hidden` ile. Boş yıl grubu gizlenir. |
| Boş durum | `empty-state` | "Bu liste yakında eklenecek." (Güzel Sanatlar Lisesi). 2025 grubunda 11 kart var, yerleşmesi olmayan kartlarda yerleşme listesi yerine `winner-card-pending` (`pending-note`, `[İÇERİK GELECEK]`, üstte ince çizgi) görünür; kompakt mobil düzende `places` alanına oturur. |

**Adres:** `?year=2026&category=university` biçimi (`all` olan parametre yazılmaz), `history.replaceState` ile güncellenir, parametreli adres filtreyi açık getirir, geçersiz değerler `all` sayılır. Gradyan kuralı sayfa içi navigasyonla ortaktır (`.fade-scroll` ve `.page-nav-inner` aynı kurallar).

**Kazanan kartı değiştiricisi `is-grouped`** (winners.html, kartlar yıl başlığı altında): yıl etiketi gizlenir (ana sayfa ve programs.html şeritlerinde kalır). 48em altında kompakt yatay düzen: solda `--winner-avatar-compact` (5rem) yuvarlak fotoğraf, sağda isim ve yerleşmeler (`grid-template-areas`), burs etiketi korunur. 48em ve üstünde standart kart. Boş durumda `aria-live` bölgesi "Bu liste yakında eklenecek." duyurur (görsel olarak gizli, görünen boş durum mesajıyla çift olmaması için). Geçersiz `year`/`category` parametreleri açılışta `replaceState` ile temizlenir.

**Kadro mobil ayarı:** `.profile` üst boşluğu ve `scroll-margin-top` yalnızca 48em altında ayarlıdır (isim header'ın ~17 px altında), 48em üstü değişmedi.

## 18. Galeri sayfası bileşenleri

| Bileşen | Class | Tanım |
|---|---|---|
| Yer tutucu | `content-placeholder` | Sanatsal Projelerimiz için `[İÇERİK GELECEK]` + icerik.md'deki tek satır. Uydurma proje yok. |
| Video çifti | `video-pair` + `video-player` | kisa-9 ve kisa-3, programs.html'deki oynatıcı (otomatik oynatma yok, `preload="none"`). 48em üstünde yan yana. kisa-10 yok. |
| Galeri mozaiği | `mosaic gallery-mosaic`, `mosaic-row`, `mosaic-item`, `gallery-tile` | 33 fotoğraf. 48em üstünde ana sayfa mozaiğinin aynısı (justified satırlar, satır grupları derleme sırasında en-boy toplamı ~4,4 hedefiyle dinamik programlamayla paketlenir). Mobilde iki sütunlu sıkı akış (`column-count: 2`, oran korunur, kırpma yok). Izgarada -800, bağlantıda -1600. Her fotoğraf bir bağlantı (`gallery-tile`), JS yokken -1600 sürümüne gider. |
| Lightbox | `lightbox`, `lightbox-stage`, `lightbox-image`, `lightbox-button`, `lightbox-counter` | Native `<dialog>` (`showModal`). Sahne sabit boyutlu (görüntü `object-fit: contain`), değişimde kayma yok. Kapat / önceki / sonraki 44 px düğmeler (`aria-label`), uçlarda `aria-disabled` (sarmaz), ok tuşları, dokunmatik kaydırma (50 px eşiği), sayaç "3 / 33", alt metin görüntünün alt metni. Esc native, kapanınca odak tıklanan fotoğrafa döner, `body.is-lightbox-open` kaymayı kilitler. |

site.json galeri öğelerinde boş `category: null` alanı var (ileride filtre için).

Yeni token: `--winner-avatar-compact`. Yeni ikon yok (`close`, `arrow_back`, `arrow_forward` mevcut).

## 19. Duyurular ve İletişim

**Duyurular (announcements.html):** `site.json` `announcements` dizisi boşken sayfada yalnızca boş durum (`empty-state`: "Güncel duyurularımız yakında burada olacak.") ve iki eylem (Başvur, telefon) gösterilir. Duyuru kartı henüz sayfada kullanılmaz, yalnızca `styleguide.html`'de "Örnek duyuru" olarak durur. Ana sayfaya duyuru bölümü eklenmez (duyuru yokken boş bölüm olurdu).

**Duyuru kartı:** `announcement-card` (+ `-meta`, `-date`, `-tag`, `-title`, `-text`, `-link`), liste için `announcement-list` (`auto-fill` ızgara). Gerçek kullanımda tarih `<time datetime>` ile verilir.

**`announcements` dizisi öğe yapısı:**

| Alan | Tür | Açıklama |
|---|---|---|
| `id` | string | İngilizce kebab-case benzersiz kimlik. |
| `date` | string | ISO tarih (`YYYY-MM-DD`), kartta okunur biçime çevrilir ve `<time datetime>` olarak verilir. Liste en yeni tarih üstte sıralanır. |
| `title` | string | Duyuru başlığı. |
| `description` | string | Kısa açıklama. |
| `program_id` | string \| null | `programs[].id` değerlerinden biri (ilgili program etiketi için), yoksa `null`. |
| `link` | object \| null | `{ "url": string, "label": string }`, ayrıntı bağlantısı yoksa `null`. |

**İletişim (contact.html):** Üstte telefon ve WhatsApp eşit genişlikte ikincil butonlar (mobilde tam genişlik, alt alta), altında iletişim listesi (`contact-list`, `<section class="contact-details" aria-labelledby="contact-info-title">` içinde; telefon, WhatsApp, e-posta, Instagram, adres), yanda adres ve harita butonları (bölüm 21). Kapanış bandı yok (sayfa zaten başvuru sayfası). Başvuru formu `application.html`'e taşındı (bölüm 23). Tüm "Başvur" bağlantıları (kapanış bandı, announcements, mobil alt çubuk, hero) `application.html`'e, programs.html'deki "Bu Eğitime Başvur" `application.html?program=<id>` adresine gider. İletişim sayfasının düzeni bölüm 23'te.

**Eski contact formu (artık `application.html`'de, alanlar bölüm 23):** başlık formun içindedir (bağlantıyla gelince başlık görünür). Alanlar: Ad Soyad, Telefon (JS varken maskeli: yalnızca rakam, "0 (XXX) XXX XX XX", 11 rakam; yapıştırmada +90/90 öneki atılır; JS yokken `pattern` ile esnek yedek doğrulama), Ad Soyad (yalnızca harf, boşluk, kesme işareti ve tire), E-posta (isteğe bağlı), İlgilendiğiniz Eğitim (optgroup'lu select, alt programı olmayanlar tek seçenek, "Henüz karar vermedim"), Mesaj (isteğe bağlı), KVKK onayı (etiketteki "KVKK Aydınlatma Metni" `privacy-notice.html`'e yeni sekmede açılan bağlantıdır, onay kutusunu işaretlemez; hata: "Devam etmek için aydınlatma metnini okuduğunuzu onaylayın."). Görünür etiketler, isteğe bağlı alanlarda "isteğe bağlı" işareti. JS varken `noValidate`, doğrulama blur ve gönderimde, hata `aria-invalid` + `aria-describedby`, hatada özet (`role="alert"`) ve ilk hatalı alana odak. JS yokken HTML5 doğrulaması. Geçerli gönderimde form yerinde prototip mesajı (`data-prototype`) görünür, yayın aşamasında kaldırılır. `?program=<program-id>` alt programı varsa ilkini, yoksa programın kendisini seçer, geçersiz id'de seçim yapmaz (seçeneklerde `data-program`).

Yeni ikon: `expand_more` (site sayfalarının `icon_names` listesine eklendi).

## 20. İletişim düzeni ve genel kalite notları

**İletişim düzeni:** HTML sırası telefon/WhatsApp butonları, form, iletişim listesi, harita yer tutucusudur (mobil ve tablet sırası da budur). 64em ve üstünde `grid-template-areas` ("form actions" / "form list" / "form map"): form solda ana sütun, diğer üç parça sağda yan sütundur. CSS `order` kullanılmaz. Yan sütun sticky değildir: parçalar HTML sırası gereği formun iki yanında ayrı grid öğeleridir ve yan sütun kısa olduğundan sticky bir fayda sağlamaz, kısa ekranlarda içeriği kesme riski taşır. Harita yer tutucusu kalan yüksekliği doldurur. Ad Soyad doğrulaması: en az 2 harf ("Lütfen adınızı yazın.").

**Ek token'lar:** `--screen-min`, `--screen-min-dynamic`, `--screen-small-height`, `--rotate-flip`, `--duration-none`, `--space-none`.

**Telefon numarası:** görünen metinde boşluklar `&nbsp;` ile yazılır (satır sonunda bölünmez, html-validate `tel-non-breaking`).

**Landmark:** mobil menü kapsayıcısı `nav` (aria-label "Mobil menü"), içindeki bağlantılar ve eylem butonları aynı landmark içindedir. Kaydırma şeritleri `<section aria-label>`, hero videosu `<figure aria-label>` olarak işaretlidir.

**Open Graph:** `og:title`, `og:description`, `og:type` (`website`), `og:locale` (`tr_TR`) 10 sayfada var. `og:image` paylaşım görseli gelince eklenecek.

## 21. Adres ve harita

Adres teyitlidir (`site.json` `contact.address`: `value`, `pending: false`, `map_url`, `directions_url`). Footer'da adres `<address>` içinde `map_url` bağlantısıdır (`target="_blank"`, `rel="noopener"`, `data-track="map"`). İletişim sayfasında adres listede `<address>` olarak durur, harita yer tutucusu yerine iki ikincil buton vardır: "Haritada Gör" (`map_url`, `data-track="map"`, ikon `location_on`) ve "Yol Tarifi Al" (`directions_url`, `data-track="directions"`, ikon `directions`). Mobilde tam genişlik alt alta. Yeni sekmede açılan bağlantılar görsel olarak gizli "(yeni sekmede açılır)" metni taşır. Google Maps iframe gömülmez. Yeni ikonlar: `directions`, `location_on` (10 sayfada `icon_names` senkron). `address` öğesi `base.css`'te italik değildir.

**Instagram:** `site.json` `contact.instagram {handle, url, pending: false}`. Footer'da "@academyizmirr" bağlantısı (`data-track="instagram"`, yeni sekmede, gizli "(yeni sekmede açılır)" metni), iletişim listesinde aynı bağlantı + sade tek renkli inline SVG simge (`icon-svg icon-svg-outline`, `aria-hidden`, WhatsApp SVG'siyle aynı boyut token'ları). Footer'da simge yok.

## 22. KVKK Aydınlatma Metni ve dönüşüm takibi

**privacy-notice.html:** `../brief/kvkk.md` metni kelimesi kelimesine. Ana menüde ve header'da aktif öğe yok, kapanış bandı yok. Sayfa başlığı alanı (`page-header`, üst etiket "Academy İzmir", h1 "KVKK Aydınlatma Metni"), altında `legal-toc` (8 bölüme bağlantılar, `text-link`, 48em üstünde iki sütun), ardından `legal-body` (`--measure` genişlik, bölümler `legal-section`: üstte ince çizgi, `--space-8` aralık, h2 varsayılan `--text-2xl`, bağlantılar h2 id'lerine gider; başlık header altında kalmaz, `base.css` `scroll-padding-top`). Listeler `legal-list` (ince altın çizgi işareti), iletişim satırları `legal-field` (e-posta `mailto`, telefon `tel`, adres `<address>`). Bağlantılar: footer alt satırı ve application.html onay kutusu.

**data-track değerleri:** `phone`, `whatsapp`, `email`, `form`, `instagram`, `map`, `directions`.

**İletişim bilgileri:** telefon, WhatsApp ve e-posta (`info@academyizmir.com`, `mailto:`) teyitlidir (`site.json` `contact`, `pending: false`). Footer, iletişim listesi ve KVKK sayfasında e-posta bağlantısı `data-track="email"` taşır.

## 23. Eğitim Ön Başvuru Formu (application.html) ve İletişim sayfası

**Sayfa:** `application.html`, partial'lar 10 sayfada senkron. Header butonu ve footer "Eğitim Ön Başvuru Formu" bağlantısı `aria-current="page"` alır. Sayfa başlığı alanı (üst etiket "Academy İzmir", h1 "Eğitim Ön Başvuru Formu"), ardından form hemen başlar (`application-section` üst boşluğu `--space-7`). Kapanış bandı yok. İçerik kaynağı icerik.md bölüm 13.

**Yerleşim (`application-layout`):** mobilde tek sütun, form sonra "İletişim" yan sütunu altta. 64em ve üstünde iki sütun: `minmax(0, --container-narrow)` form ve `--action-width` yan sütun (`application-aside`, sticky, yalnızca bölüm etiketi h2 "İletişim" + telefon ve WhatsApp butonları, ek metin yok). Form okunabilir 42rem genişlikte kalır, yan sütun konteynerin sağ kenarına hizalıdır. Form başlığı `form-title` (`--text-2xl`, son kelime italik vurgu), altında `section-lead` açıklama.

**Alanlar (sıra):** Ad Soyad (placeholder, ad filtresi), Doğum Tarihi + Yaş Grubu (`form-grid`: 48em+ yan yana, dar ekranda alt alta), Eğitim Durumu, Başvurmak İstediğiniz Eğitim (5 ana program, optgroup yok), Telefon Numarası + E-posta Adresi, Sanat Eğitimi Deneyiminiz (radyo grubu), Eklemek İstedikleriniz, KVKK onayı, "Ön Başvurumu Gönder" (`data-track="form"`). Seçenekler `site.json` `application` nesnesinden statik üretilir. Placeholder'lar `--color-text-muted`, her alanın görünür etiketi var, telefon altındaki "Örnek" ipucu kaldırıldı.

**Yeni bileşenler:**

| Bileşen | Class | Tanım |
|---|---|---|
| Tarih alanı | `form-control form-control-date` | Native `<input type="date">`, koyu tema `:root { color-scheme: dark }` ile (takvim simgesi ve seçici koyu). `min="1930-01-01"` HTML'de, `max` JS ile bugünün yerel tarihi. |
| Radyo grubu | `form-fieldset`, `radio-group`, `radio-option`, `radio-option-input`, `radio-option-text` | `fieldset` + `legend` (etiket stili). İki seçenek eşit sütunlarda, her biri 44 px yüksekliğinde çerçeveli kutu ve solda `--check-size` daire göstergesi. Gerçek radyo girdisi kutunun üstünde saydam durur, ok tuşlarıyla seçim tarayıcı davranışıdır. Seçili: vurgu kenarlığı ve dolgulu gösterge (`--radio-dot-inset`). Focus halkası kutuda. Varsayılan "Yok". Gerekçe (ui-ux-pro-max, form/touch kuralları): küçük yerel radyo noktası yerine büyük dokunma alanı, durum yalnızca renkle değil dolgu şekliyle de verilir. |

Yeni token'lar: `--check-size`, `--radio-dot-inset`.

**Doğrulama:** mevcut yapı aynen (blur ve gönderim, `aria-invalid`, `aria-describedby`, hata özeti, ilk hatalı alana odak). Yeni mesajlar: "Lütfen doğum tarihinizi seçin." (boş), "Lütfen geçerli bir doğum tarihi seçin." (bugünden ileri, 1930'dan eski ya da yarım yazılmış), "Lütfen bir yaş grubu seçin.", "Lütfen eğitim durumunuzu seçin.". `?program=<id>` ana programı seçer, geçersiz id'de seçim yok. JS yokken HTML5 doğrulaması (`required`, `min`, `pattern`). Geçerli gönderimde `data-prototype` mesajı.

**contact.html düzeni:** form kaldırıldı. Sırayla: `contact-callout` (yüzey zeminli, üstte 2 px vurgu çizgisi, h2 "Eğitim Ön Başvuru Formu", icerik.md bölüm 13 açıklaması, "Ön Başvuru Formuna Git" birincil butonu, `data-track="form"`), "İletişim bilgileri" başlığı + telefon/WhatsApp, iletişim listesi, harita butonları. 64em ve üstünde: üstte tam genişlikte callout (metin solda, buton sağda), altında 5/7 iki sütun: solda başlık + butonlar + harita butonları, sağda iletişim listesi. Form kalktığı için boş sütun kalmadı. Mobilde HTML sırası.

