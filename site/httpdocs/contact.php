<?php
require_once __DIR__ . '/../private/bootstrap.php';
$page_id = 'contact';
partial('head');
partial('header');
partial('mobile-menu');
?>

  <main class="site-main" id="main" tabindex="-1" data-menu-inert>
    <header class="page-header">
      <div class="container page-header-inner">
        <p class="section-eyebrow">Academy İzmir</p>
        <h1 class="page-header-title">İletişim ve Başvuru</h1>
      </div>
    </header>
    <section class="section" aria-label="İletişim">
      <div class="container contact-layout">
        <section class="contact-callout" aria-labelledby="contact-callout-title">
          <div class="contact-callout-text">
            <h2 class="section-title" id="contact-callout-title">Eğitim Ön Başvuru <em>Formu</em></h2>
            <p class="section-lead">Eğitim programlarımız hakkında bilgi almak ve ön başvurunuzu oluşturmak için formu doldurabilirsiniz.</p>
          </div>
          <a class="button button-primary" href="<?= e(url('application')) ?>" data-track="form">Ön Başvuru Formuna Git <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
        </section>

        <div class="contact-actions">
          <h2 class="section-title" id="contact-info-title">İletişim <em>bilgileri</em></h2>
          <div class="hero-actions">
            <?= phone_link('button button-secondary') ?>
            <?= whatsapp_link('button button-secondary') ?>
          </div>
        </div>

        <section class="contact-details" aria-labelledby="contact-info-title">
        <?php $c = contact(); ?>
        <dl class="contact-list">
            <div class="contact-list-item"><dt class="contact-list-label">Telefon</dt><dd class="contact-list-value"><?= nbsp($c['phone']['display']) ?></dd></div>
            <div class="contact-list-item"><dt class="contact-list-label">WhatsApp</dt><dd class="contact-list-value"><a class="contact-link" href="<?= e($c['whatsapp']['url']) ?>" target="_blank" rel="noopener" data-track="whatsapp"><?= nbsp($c['whatsapp']['display']) ?><span class="visually-hidden"> (yeni sekmede açılır)</span></a></dd></div>
            <div class="contact-list-item"><dt class="contact-list-label">E-posta</dt><dd class="contact-list-value"><a class="contact-link" href="<?= e($c['email']['url']) ?>" data-track="email"><?= e($c['email']['value']) ?></a></dd></div>
            <div class="contact-list-item"><dt class="contact-list-label">Instagram</dt><dd class="contact-list-value"><a class="contact-link" href="<?= e($c['instagram']['url']) ?>" target="_blank" rel="noopener" data-track="instagram"><?= instagram_svg() ?> @<?= e($c['instagram']['handle']) ?><span class="visually-hidden"> (yeni sekmede açılır)</span></a></dd></div>
            <div class="contact-list-item"><dt class="contact-list-label">Adres</dt><dd class="contact-list-value"><address><?= e($c['address']['value']) ?></address></dd></div>
        </dl>
        </section>

        <div class="contact-map hero-actions">
          <a class="button button-secondary" href="<?= e($c['address']['map_url']) ?>" target="_blank" rel="noopener" data-track="map"><span class="material-symbols-outlined" aria-hidden="true">location_on</span> Haritada Gör<span class="visually-hidden"> (yeni sekmede açılır)</span></a>
          <a class="button button-secondary" href="<?= e($c['address']['directions_url']) ?>" target="_blank" rel="noopener" data-track="directions"><span class="material-symbols-outlined" aria-hidden="true">directions</span> Yol Tarifi Al<span class="visually-hidden"> (yeni sekmede açılır)</span></a>
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
