<?php
require_once __DIR__ . '/../private/bootstrap.php';
$page_id = 'programs';
partial('head');
partial('header');
partial('mobile-menu');
?>

  <main class="site-main" id="main" tabindex="-1" data-menu-inert>
    <header class="page-header">
      <div class="container page-header-inner">
        <p class="section-eyebrow">Academy İzmir</p>
        <h1 class="page-header-title">Eğitimlerimiz</h1>
      </div>
    </header>
    <nav class="page-nav" aria-label="Eğitimlerimiz" data-page-nav>
      <div class="page-nav-inner">
        <ul class="page-nav-list" data-page-nav-list>
<?php foreach (site()['programs'] as $program): ?>
          <li class="page-nav-item"><a class="page-nav-link" href="#<?= e($program['id']) ?>" data-page-nav-link><?= e($program['title']) ?></a></li>
<?php endforeach; ?>
        </ul>
      </div>
    </nav>

    <div class="program-sections">
<?php foreach (site()['programs'] as $program): program_section($program); endforeach; ?>
    </div>
  </main>

<?php
partial('footer');
partial('whatsapp-float');
partial('contact-bar');
?>
</body>
</html>
