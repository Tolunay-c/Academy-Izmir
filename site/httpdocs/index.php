<?php
require_once __DIR__ . '/../private/bootstrap.php';
$page_id = 'home';
partial('head');
partial('header');
partial('mobile-menu');
?>

  <main class="site-main" id="main" tabindex="-1" data-menu-inert>

    <!-- 1. Hero -->
    <section class="hero">
      <div class="container hero-inner">
        <div class="hero-text">
          <p class="section-eyebrow">İzmir'de Sanat Atölyeleri ve Sahne Çalışmaları</p>
          <h1 class="hero-title">ACADEMY <em>İZMİR</em></h1>
          <p class="hero-slogan">Sanatla Keşfet, Sahneyle Büyü</p>
          <p class="hero-intro">Çocuklardan gençlere, gençlerden yetişkinlere; yaratıcı dramadan tiyatroya, baleden dansa, oyunculuktan konservatuvar ve güzel sanatlar hazırlığına uzanan tiyatro, dans ve yaratıcı sanat çalışmaları.</p>
          <div class="hero-actions">
            <a class="button button-secondary" href="<?= e(url('programs')) ?>">Atölyeleri Keşfet</a>
            <a class="button button-primary" href="<?= e(url('application')) ?>" data-track="form">Başvur</a>
          </div>
        </div>
        <figure class="hero-media" aria-label="Academy İzmir Reels videosu">
          <video class="hero-video" muted loop playsinline preload="none" poster="/assets/video/hero-reels-poster.webp" width="720" height="1280" data-hero-video>
            <source src="/assets/video/hero-reels.mp4" type="video/mp4">
          </video>
          <div class="hero-video-controls">
            <button class="hero-video-control" type="button" aria-label="Videoyu oynat" data-video-play><span class="material-symbols-outlined" aria-hidden="true">play_arrow</span></button>
            <button class="hero-video-control" type="button" aria-label="Sesi aç" aria-pressed="false" data-video-sound><span class="material-symbols-outlined" aria-hidden="true">volume_off</span></button>
          </div>
        </figure>
      </div>
    </section>

    <!-- 2. Öne çıkanlar -->
    <section class="section highlights" aria-label="Öne çıkan bilgiler">
      <div class="container">
        <ol class="highlights-list">
          <li class="highlights-item"><p class="highlights-text"><em>2011'den</em> Beri Sanatın ve Sahnenin İçinde</p></li>
          <li class="highlights-item"><p class="highlights-text"><em>15 yılı aşkın</em> süredir konservatuvar ve güzel sanatlar fakültelerine hazırlık</p></li>
          <li class="highlights-item"><p class="highlights-text">Yaklaşık <em>%80</em> yerleşme başarısı</p></li>
        </ol>
      </div>
    </section>

    <!-- 3. Atölyeler ve Sanat Grupları -->
    <section class="section" aria-labelledby="home-programs-title">
      <div class="container">
        <header class="section-header section-header-row">
          <h2 class="section-title" id="home-programs-title">Atölyeler ve Sanat Grupları</h2>
          <a class="button button-text" href="<?= e(url('programs')) ?>">Tüm Çalışmalar <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
        </header>
        <ol class="program-list">
<?php home_programs(); ?>
        </ol>
      </div>
    </section>

    <!-- 4. Neden Academy İzmir? -->
    <section class="section" aria-labelledby="home-why-title">
      <div class="container why-grid">
        <header class="why-head">
          <h2 class="section-title" id="home-why-title">Neden Academy <em>İzmir?</em></h2>
          <a class="button button-text" href="<?= e(url('about')) ?>">Hakkımızda <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
        </header>
        <div class="why-body">
          <p>Her insanın kendine özgü bir sesi, anlatacak bir hikâyesi ve keşfedilmeyi bekleyen bir potansiyeli vardır.</p>
          <p>Academy İzmir, bu seslere alan açmak, hikâyeleri birlikte büyütmek ve sanatın dönüştürücü gücünü hayatın bir parçası kılmak için var.</p>
          <p>Biz, İzmir'de sanat eğitimine başka bir gözle baktık. 2011 yılından bu yana sanatın yalnızca öğretildiği değil; deneyimlendiği, sorgulandığı, birlikte üretildiği ve yaşandığı bir dünya kuruyoruz.</p>
        </div>
        <div class="why-statement">
          <p><span class="why-statement-lead">Sanatın bir ayrıcalık değil, herkesin hakkı olduğuna inanıyoruz.</span> <span class="why-statement-rest">Bu yüzden nitelikli sanat eğitimini ulaşılabilir kılmayı önemsiyor, farklı sosyoekonomik koşullardan gelen öğrencileri aynı çatı altında buluşturuyoruz. Yıllardır burslu öğrencilerimize kapılarımızı açarken, hiçbir hayalin ekonomik engellere takılmaması için çabalıyoruz. Çünkü bizim için gerçek kazanım, bir insanın hayatında açabildiğimiz yeni ufuklar ve birlikte büyüttüğümüz hayallerdir.</span></p>
        </div>
      </div>
    </section>

    <!-- 5. Sanatçı Kadromuz -->
    <section class="section" aria-labelledby="home-faculty-title">
      <div class="container">
        <header class="section-header section-header-row">
          <h2 class="section-title" id="home-faculty-title">Sanatçı <em>Kadromuz</em></h2>
          <div class="strip-controls strip-controls-compact">
            <button class="strip-control" type="button" aria-label="Önceki sanatçılar" aria-controls="faculty-strip" aria-disabled="true" data-strip-prev><span class="material-symbols-outlined" aria-hidden="true">arrow_back</span></button>
            <button class="strip-control" type="button" aria-label="Sonraki sanatçılar" aria-controls="faculty-strip" aria-disabled="false" data-strip-next><span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></button>
          </div>
        </header>
        <section class="strip strip-collapse" id="faculty-strip" aria-label="Sanatçı kadrosu" tabindex="0" data-strip>
          <ul class="strip-list">
<?php foreach (array_slice(faculty_sorted(), 0, 4) as $f): ?>
        <li class="strip-item"><?= instructor_card($f) ?></li>
<?php endforeach; ?>
          </ul>
        </section>
        <a class="button button-text" href="<?= e(url('faculty')) ?>">Sanatçılarımız <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
      </div>
    </section>

    <!-- 6. Kazananlarımız -->
    <section class="section" aria-labelledby="home-winners-title">
      <div class="container">
        <header class="section-header section-header-row">
          <div class="section-header-text">
            <h2 class="section-title" id="home-winners-title">Kazananlarımız</h2>
            <p class="section-lead">Yaklaşık %80 yerleşme başarısı</p>
          </div>
          <div class="strip-controls">
            <button class="strip-control" type="button" aria-label="Önceki kazananlar" aria-controls="winner-strip" aria-disabled="true" data-strip-prev><span class="material-symbols-outlined" aria-hidden="true">arrow_back</span></button>
            <button class="strip-control" type="button" aria-label="Sonraki kazananlar" aria-controls="winner-strip" aria-disabled="false" data-strip-next><span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></button>
          </div>
        </header>
        <section class="strip" id="winner-strip" aria-label="2026 kazanan öğrenciler" tabindex="0" data-strip>
          <ul class="strip-list">
<?php winner_strip_items(2026); ?>
          </ul>
        </section>
        <a class="button button-text" href="<?= e(url('winners')) ?>">Tüm Kazananlar <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
      </div>
    </section>

    <!-- 7. Sahneden -->
    <section class="section" aria-labelledby="home-stage-title">
      <div class="container">
        <header class="section-header section-header-row">
          <h2 class="section-title" id="home-stage-title"><em>Sahneden</em></h2>
          <a class="button button-text" href="<?= e(url('gallery')) ?>">Galeri <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
        </header>
        <div class="mosaic">
<?php home_mosaic(); ?>
        </div>
      </div>
    </section>

    <!-- 8. Başvuru -->
    <?php partial('cta-band', ['cta_id' => 'home']); ?>
  </main>

<?php
partial('footer');
partial('whatsapp-float');
partial('contact-bar');
?>
</body>
</html>
