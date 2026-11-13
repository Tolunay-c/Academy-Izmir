<?php
require_once __DIR__ . '/../private/bootstrap.php';
$page_id = 'about';
partial('head');
partial('header');
partial('mobile-menu');
?>

  <main class="site-main" id="main" tabindex="-1" data-menu-inert>
    <header class="page-header">
      <div class="container page-header-inner">
        <p class="section-eyebrow">Academy İzmir</p>
        <h1 class="page-header-title">Hakkımızda</h1>
      </div>
    </header>
    <section class="section about-intro" aria-label="Giriş">
      <div class="container about-intro-grid">
        <p class="about-slogan">Sanatla Keşfet, Sahneyle Büyü</p>
        <div class="about-intro-text">
          <p class="about-lead">Her insanın kendine özgü bir sesi, anlatacak bir hikâyesi ve keşfedilmeyi bekleyen bir potansiyeli vardır.</p>
          <p class="about-intro-body">Academy İzmir, bu seslere alan açmak, hikâyeleri birlikte büyütmek ve sanatın dönüştürücü gücünü hayatın bir parçası kılmak için var.</p>
        </div>
      </div>
    </section>

    <section class="section about-story" aria-label="Hakkımızda">
      <div class="container about-flow">
        <p>Biz, İzmir'de sanat eğitimine başka bir gözle baktık. 2011 yılından bu yana sanatın yalnızca öğretildiği değil; deneyimlendiği, sorgulandığı, birlikte üretildiği ve yaşandığı bir dünya kuruyoruz.</p>
        <p>Yılların sahne birikimini, sanat üretmeye devam eden profesyonel sanatçıların deneyimiyle buluşturuyor; yeteneği emekle, disiplini yaratıcılıkla, deneyimi özgür düşünceyle besliyoruz.</p>
        <p class="about-quote" aria-hidden="true">Bizim için sanat eğitimi yalnızca sahneye hazırlanmak değil; düşünmek, hissetmek, sorgulamak ve birlikte üretmektir.</p>
        <figure class="about-figure is-wide">
          <img src="/assets/images/galeri/bvn01064-800.webp" srcset="/assets/images/galeri/bvn01064-800.webp 800w, /assets/images/galeri/bvn01064-1600.webp 1600w" sizes="(min-width: 48em) 60vw, 100vw" width="1600" height="1067" loading="lazy" alt="Sahnede beyaz üstlü bir grup, bir kısmı oturuyor, bir kısmı ayakta.">
        </figure>
        <div class="why-statement about-statement">
          <p><span class="why-statement-lead">Sanatın bir ayrıcalık değil, herkesin hakkı olduğuna inanıyoruz.</span> <span class="why-statement-rest">Bu yüzden nitelikli sanat eğitimini ulaşılabilir kılmayı önemsiyor, farklı sosyoekonomik koşullardan gelen öğrencileri aynı çatı altında buluşturuyoruz. Yıllardır burslu öğrencilerimize kapılarımızı açarken, hiçbir hayalin ekonomik engellere takılmaması için çabalıyoruz. Çünkü bizim için gerçek kazanım, bir insanın hayatında açabildiğimiz yeni ufuklar ve birlikte büyüttüğümüz hayallerdir.</span></p>
        </div>
        <p>Tiyatro, yaratıcı drama, bale, dans, konservatuvar ve güzel sanatlara hazırlık ile retorika eğitimlerimizde her yaştan bireyin kendi sesini bulmasını, özgünlüğünü keşfetmesini ve yaratıcı cesaretini geliştirmesini önemsiyoruz.</p>
        <div class="about-figure-pair">
          <figure class="about-figure is-portrait">
          <img src="/assets/images/galeri/bvn00232-800.webp" srcset="/assets/images/galeri/bvn00232-800.webp 800w, /assets/images/galeri/bvn00232-1600.webp 1600w" sizes="(min-width: 48em) 60vw, 100vw" width="1067" height="1600" loading="lazy" alt="Sahnede desenli kostümlü bir oyuncu öne eğilmiş, arkasında sahnede duran katılımcılar var.">
        </figure>
          <figure class="about-figure is-landscape">
          <img src="/assets/images/galeri/bvn02449-800.webp" srcset="/assets/images/galeri/bvn02449-800.webp 800w, /assets/images/galeri/bvn02449-1600.webp 1600w" sizes="(min-width: 48em) 60vw, 100vw" width="1600" height="1067" loading="lazy" alt="Spot ışığı altında beyaz kostümlü bir grup sahnede birbirine yakın duruyor.">
        </figure>
        </div>
        <p>Bizim için sanat eğitimi yalnızca sahneye hazırlanmak değil; düşünmek, hissetmek, sorgulamak ve birlikte üretmektir.</p>
        <p>Hayat bazen alışkanlıkların, tekrarların ve sorumlulukların arasında tekdüzeleşir. Sanat ise o alışılmış dünyanın kapılarını aralar; merakı uyandırır, hayal gücünü özgürleştirir, yaşama yeni renkler ve anlamlar katar.</p>
      </div>
    </section>

    <section class="section" aria-labelledby="about-founders-title">
      <div class="container">
        <header class="section-header">
          <h2 class="section-title" id="about-founders-title">Kurucularımız</h2>
        </header>
        <ul class="founders-cards">
<?php foreach (faculty_sorted() as $f): if (!in_array($f['id'], ['ozlem-baskaya', 'ozgur-baskaya'], true)) { continue; } ?>
          <li>
            <?= instructor_card($f) ?>

          </li>
<?php endforeach; ?>
        </ul>
      </div>
    </section>

    <?php partial('cta-band', ['cta_id' => 'about', 'cta_lead' => 'Gelin, hayatın bize sunduğuyla yetinmeyelim; birlikte daha fazlasını hayal edelim, üretelim ve yaşayalım.']); ?>
  </main>

<?php
partial('footer');
partial('whatsapp-float');
partial('contact-bar');
?>
</body>
</html>
