<?php
require_once __DIR__ . '/../private/bootstrap.php';
$page_id = 'gallery';
partial('head');
partial('header');
partial('mobile-menu');
?>

  <main class="site-main" id="main" tabindex="-1" data-menu-inert>
    <header class="page-header">
      <div class="container page-header-inner">
        <p class="section-eyebrow">Academy İzmir</p>
        <h1 class="page-header-title">Sanatsal Projelerimiz ve Galeri</h1>
      </div>
    </header>
    <section class="section" aria-labelledby="gallery-projects-title">
      <div class="container">
        <header class="section-header">
          <h2 class="section-title" id="gallery-projects-title">Sanatsal <em>Projelerimiz</em></h2>
        </header>
        <div class="content-placeholder">
          <p class="pending-note">[İÇERİK GELECEK]</p>
          <p class="content-placeholder-text">Tiyatro yapımları, sahne çalışmaları ve Academy İzmir Trio</p>
        </div>
      </div>
    </section>

    <section class="section" aria-labelledby="gallery-videos-title">
      <div class="container">
        <header class="section-header">
          <h2 class="section-title" id="gallery-videos-title">Videolar</h2>
        </header>
        <div class="video-pair">
          <?= video_player('kisa-9', 'Academy İzmir kısa video 1') ?>

          <?= video_player('kisa-3', 'Academy İzmir kısa video 2') ?>

        </div>
      </div>
    </section>

    <section class="section" aria-labelledby="gallery-photos-title">
      <div class="container">
        <header class="section-header">
          <h2 class="section-title" id="gallery-photos-title">Galeri</h2>
        </header>
        <div class="mosaic gallery-mosaic" data-gallery>
<?php gallery_mosaic(); ?>
        </div>
      </div>
    </section>

    <dialog class="lightbox" aria-label="Büyük görüntü" data-lightbox>
      <div class="lightbox-bar">
        <button class="lightbox-button" type="button" aria-label="Büyük görüntüyü kapat" data-lightbox-close><span class="material-symbols-outlined" aria-hidden="true">close</span></button>
      </div>
      <div class="lightbox-stage" data-lightbox-stage>
        <img class="lightbox-image" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="" width="1600" height="1067" data-lightbox-image>
      </div>
      <div class="lightbox-nav">
        <button class="lightbox-button" type="button" aria-label="Önceki fotoğraf" data-lightbox-prev><span class="material-symbols-outlined" aria-hidden="true">arrow_back</span></button>
        <p class="lightbox-counter" data-lightbox-counter>1 / <?= count(site()['gallery']) ?></p>
        <button class="lightbox-button" type="button" aria-label="Sonraki fotoğraf" data-lightbox-next><span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></button>
      </div>
    </dialog>

    <?php partial('cta-band', ['cta_id' => 'gallery']); ?>
  </main>

<?php
partial('footer');
partial('whatsapp-float');
partial('contact-bar');
?>
</body>
</html>
