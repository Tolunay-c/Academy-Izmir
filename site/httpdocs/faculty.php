<?php
require_once __DIR__ . '/../private/bootstrap.php';
$page_id = 'faculty';
partial('head');
partial('header');
partial('mobile-menu');
?>

  <main class="site-main" id="main" tabindex="-1" data-menu-inert>
    <header class="page-header">
      <div class="container page-header-inner">
        <p class="section-eyebrow">Academy İzmir</p>
        <h1 class="page-header-title">Sanatçı Kadromuz</h1>
      </div>
    </header>
    <section class="section" aria-labelledby="faculty-index-title">
      <div class="container">
        <header class="section-header">
          <h2 class="section-title" id="faculty-index-title">Kadro <em>dizini</em></h2>
        </header>
        <ol class="faculty-index-list">
<?php foreach (faculty_sorted() as $f): ?>
          <li class="faculty-index-item"><a class="faculty-index-link" href="#<?= e($f['id']) ?>"><?= e($f['name']) ?></a></li>
<?php endforeach; ?>
        </ol>
      </div>
    </section>

    <div class="faculty-profiles">
<?php foreach (faculty_sorted() as $i => $f): faculty_profile($f, $i === 0); endforeach; ?>
    </div>

    <?php partial('cta-band', ['cta_id' => 'faculty']); ?>
  </main>

<?php
partial('footer');
partial('whatsapp-float');
partial('contact-bar');
?>
</body>
</html>
