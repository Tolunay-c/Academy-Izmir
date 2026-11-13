<?php /** Atlama bağlantısı ve site header'ı (masaüstü menüsü routes.php'deki "nav" alanından). */ ?>
  <a class="skip-link" href="#main">İçeriğe atla</a>
  <header class="site-header" data-site-header>
    <div class="site-header-inner">
      <!-- Logo tek bileşen: inline SVG (currentColor), kaynak assets/images/logo/academy-izmir-logo.svg -->
      <a class="site-logo" href="<?= e(url('home')) ?>"<?= current_attr('home') ?>><?= logo_svg() ?></a>
      <nav class="site-nav" aria-label="Ana menü">
        <ul class="site-nav-list">
<?php foreach (routes() as $id => $r): if ($r['nav'] === null) { continue; } ?>
          <li><a class="site-nav-link" href="<?= e($r['path']) ?>"<?= current_attr($id) ?>><?= e($r['nav']) ?></a></li>
<?php endforeach; ?>
        </ul>
      </nav>
      <div class="site-header-actions">
        <a class="button button-primary site-header-cta" href="<?= e(url('application')) ?>"<?= current_attr('application') ?> data-track="form"><span class="site-header-cta-short">Katılım Formu</span><span class="site-header-cta-full">Katılım ve Bilgi Formu</span></a>
        <a class="site-header-toggle-link" href="#footer-nav" aria-label="Menüye git"><span class="material-symbols-outlined" aria-hidden="true">menu</span></a>
        <button class="site-header-toggle" type="button" aria-expanded="false" aria-controls="mobile-menu" aria-label="Menüyü aç" data-menu-toggle>
          <span class="material-symbols-outlined site-header-toggle-open" aria-hidden="true">menu</span>
          <span class="material-symbols-outlined site-header-toggle-close" aria-hidden="true">close</span>
        </button>
      </div>
    </div>
  </header>
