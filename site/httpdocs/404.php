<?php
require_once __DIR__ . '/../private/bootstrap.php';
http_response_code(404);
$page_id = 'not_found';
partial('head');
partial('header');
partial('mobile-menu');
?>

  <main class="site-main" id="main" tabindex="-1" data-menu-inert>
    <header class="page-header">
      <div class="container page-header-inner">
        <p class="section-eyebrow">Academy İzmir</p>
        <h1 class="page-header-title">Sayfa bulunamadı</h1>
      </div>
    </header>
    <section class="section" aria-label="Yönlendirme">
      <div class="container">
        <div class="hero-actions">
          <a class="button button-primary" href="<?= e(url('home')) ?>">Ana Sayfa</a>
          <a class="button button-secondary" href="<?= e(url('programs')) ?>">Atölyeler</a>
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
