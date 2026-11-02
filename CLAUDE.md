# Academy İzmir — Proje Kuralları

Bu kurallar projedeki HER görev için geçerlidir. Bir prompt bu kurallarla çelişirse dur ve sor.

## İçerik
- Tüm içerik bilgisinin tek kaynağı `brief/icerik.md`. Orada olmayan metin, isim, rakam, iletişim bilgisi UYDURULMAZ.
- Eksik içerik için `[İÇERİK GELECEK]` yer tutucusu kullanılır, teyit bekleyen bilgiler için `[TEYİT EDİLECEK]`.
- Site içeriği Türkçedir. Kod (class, dosya, değişken, fonksiyon adları) İngilizcedir.

## Çalışma şekli
- Her prompt bir ID ile gelir (AI-01, AI-02 ...). SADECE o adımda istenen işi yap, sonraki adımlara geçme.
- Her adımın sonunda prompt'ta istenen rapor formatını eksiksiz yaz.
- `v-1` ve `v-2` ayrı tasarım versiyonlarıdır. Prompt hangi klasörü söylüyorsa sadece orada çalış, diğerine dokunma.
- `assets/images` altındaki orijinal `Fwd__...` ve `drive-download-...` klasörlerine dokunma, silme, taşıma.

## Tasarım — ui-ux-pro-max ZORUNLU
- Renk, tipografi, stil, bileşen, layout, erişilebilirlik ve etkileşimle ilgili HER kararda ui-ux-pro-max skill'ini kullan.
- Skill verisinden hangi stil, palet ve font eşleşmesini seçtiğini ve nedenini raporda belirt.
- Hedef: premium bir sanat kurumu. Editoryal, sinematik, ağırbaşlı. Güzel bir tiyatro programı, bir sanat dergisi ya da bir opera sezon kitapçığı gibi hissettirmeli.

### Kesinlikle KAÇINILACAKLAR (birbirinin aynısı "yapay zeka sitesi" görünümü)
- Mor-mavi gradyanlar, neon parıltılar, her yere yayılmış glassmorphism
- Ortalanmış başlık + altında iki buton + stok ikonlar şeklindeki şablon hero
- İkonlu, eşit boyutlu 3'lü "özellik kartları" dizileri
- Emoji ve dekoratif ikon kalabalığı
- Varsayılan font tercihleri (Inter, Poppins, Roboto, Montserrat, Open Sans gibi) tek başına ana kimlik olarak
- Her şeyin aynı büyük radius ile yuvarlatılması, her kartın gölgeli kutu olması
- Anlamsız sayaç animasyonları, rastgele parallax, her elemanın kayarak gelmesi
- Genel geçer "Hayallerine ulaş" tarzı uydurma metinler

### Premium hissi için
- Güçlü tipografik hiyerarşi, cesur başlık boyutları, bol beyaz alan
- Gerçek fotoğrafların öne çıktığı, asimetrik ve ritmi olan yerleşimler
- Sınırlı ve tutarlı renk kullanımı, vurgu rengi az ve anlamlı
- Ölçülü, amaçlı hareket; `prefers-reduced-motion` desteklenir

## Tasarım token'ları — TUTARLILIK ZORUNLU
- Tüm renkler, font aileleri, font boyutları, satır yükseklikleri, letter-spacing değerleri, radius, boşluklar, gölgeler, geçiş süreleri ve breakpoint'ler TEK bir dosyada CSS custom property olarak tanımlanır: `assets/css/tokens.css`.
- Bu dosya dışında sabit (hard-coded) değer YAZILMAZ. Her değer token'dan gelir: `var(--...)`.
- Token sayısı sınırlı tutulur:
  - En fazla 2 font ailesi (biri başlık, biri metin)
  - Tek bir font boyutu skalası (fluid, `clamp()` ile)
  - Tanımlı ve sınırlı letter-spacing değerleri (örnek: başlık, gövde, büyük harfli küçük etiket)
  - En fazla 3 radius değeri (0 dahil)
  - Tek bir spacing skalası
- Aynı tür eleman sitenin her yerinde aynı token'ları kullanır (bütün bölüm başlıkları aynı, bütün butonlar aynı, bütün kartlar aynı).
- İstisna: CSS @media sorgularında var() çalışmadığı için breakpoint değerleri sorgu içinde yazılır. Bu değerler tokens.css'te yorum satırı olarak listelenen breakpoint değerleriyle birebir aynı olmak zorundadır.

## Responsive — TAM UYUM ZORUNLU
- Mobile-first yazılır. Desteklenen minimum genişlik 320 px.
- Kontrol edilecek genişlikler: 320, 360, 390, 768, 1024, 1280, 1440, 1920 px.
- Hiçbir genişlikte yatay kaydırma olmaz, metin taşmaz, görseller bozulmaz.
- Dokunma hedefleri en az 44x44 px.
- Mobilde iletişim (telefon, WhatsApp, başvuru) her zaman kolay erişilebilir olur.

## İkonlar
- Emoji KULLANILMAZ. Ne metinde, ne butonda, ne başlıkta, ne de kod yorumlarında.
- İkonlar için Google Fonts CDN üzerinden **Material Symbols** kullanılır.
  - Sitenin tamamında tek bir stil seçilir (Outlined, Rounded veya Sharp) ve değiştirilmez. Seçim ui-ux-pro-max ile tasarım sistemine uygun yapılır.
  - Ağırlık (wght), dolgu (FILL), grade (GRAD) ve optik boyut (opsz) değerleri token olarak tanımlanır, her yerde aynı kullanılır.
  - Performans için sadece kullanılan ikonlar yüklenir (Google Fonts `icon_names` parametresi ile). Yeni ikon eklendikçe bu liste güncellenir.
  - Kullanım: `<span class="material-symbols-outlined" aria-hidden="true">call</span>`. Dekoratif ikonlar `aria-hidden="true"` alır; tek başına anlam taşıyan ikon butonlarında `aria-label` zorunludur.
- Material Symbols'ta marka logoları (WhatsApp, Instagram vb.) yoktur. Bunlar için resmi logoların sade, tek renkli inline SVG hâlleri kullanılır ve aynı boyut token'larına uyar.
- İkon kullanımı ölçülü olur; dekor için ikon kalabalığı yapılmaz.

## Kod
- Saf HTML, CSS, vanilla JS. Framework yok, build adımı yok. Sonradan PHP'ye bölünecek, bu yüzden header, footer ve tekrar eden bölümler net ayrılır.
- Class adları İngilizce ve kebab-case: `site-header`, `program-card`, `faculty-grid`, `hero-video`.
  - Alt elemanlar bileşen adıyla başlar: `program-card-title`, `program-card-media`.
  - Durum class'ları: `is-active`, `is-open`, `is-hidden`.
  - JS bağlantıları için class değil `data-` attribute kullanılır: `data-filter`, `data-track`.
  - Stil için id kullanılmaz.
- Dosya ve klasör adları İngilizce, kebab-case, Türkçe karakter ve boşluk yok.
- Semantik HTML, `lang="tr"`, erişilebilir (kontrast WCAG AA, alt metinler, klavye ile gezinme, görünür focus).
- Performans: görseller `loading="lazy"`, uygun boyutlar, videolarda poster görseli; ağır kütüphane yok.
- Dönüşüm takibi için telefon, WhatsApp ve form öğelerinde `data-track="phone"`, `data-track="whatsapp"`, `data-track="form"`.