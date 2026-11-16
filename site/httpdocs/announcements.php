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
        <div class="announcements-intro">
          <p class="section-lead">Sanatın, yaratıcılığın ve birlikte üretmenin heyecanını paylaşıyoruz.</p>
          <p class="section-lead">Academy İzmir bünyesinde gerçekleşen atölyeler, sahne çalışmaları, sanatsal projeler ve etkinliklere ilişkin gelişmeleri bu sayfadan takip edebilirsiniz.</p>
          <p class="section-lead">Sanatla buluşmaya devam ediyoruz.</p>
        </div>
<?php $announcements = announcements_sorted(); if (count($announcements) === 0): ?>
        <p class="empty-state">Güncel duyurularımız yakında burada olacak.</p>
<?php else: ?>
        <div class="announcement-list">
<?php announcement_cards($announcements); ?>
        </div>
<?php endif; ?>
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
