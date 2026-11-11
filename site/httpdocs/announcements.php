<?php
require_once __DIR__ . '/../private/bootstrap.php';
$page_id = 'announcements';
partial('head');
partial('header');
partial('mobile-menu');
?>

  <main class="site-main" id="main" tabindex="-1" data-menu-inert>
    <header class="page-header">
      <div class="container page-header-inner">
        <p class="section-eyebrow">Academy İzmir</p>
        <h1 class="page-header-title">Güncel Kayıtlar ve Duyurular</h1>
      </div>
    </header>
    <section class="section" aria-label="Duyurular">
      <div class="container">
        <p class="empty-state">Güncel duyurularımız yakında burada olacak.</p>
        <div class="hero-actions">
          <a class="button button-primary" href="<?= e(url('application')) ?>" data-track="form">Başvur</a>
          <?= phone_link('button button-secondary') ?>
        </div>
      </div>
    </section>
  </main>

<?php
partial('footer');
partial('whatsapp-float');
partial('contact-bar');
?>
</body>
</html>
