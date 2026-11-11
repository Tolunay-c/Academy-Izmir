<?php $c = contact(); ?>
  <footer class="site-footer" data-menu-inert>
    <div class="container">
      <div class="site-footer-grid">
        <div class="site-footer-brand">
          <a class="site-logo" href="<?= e(url('home')) ?>"><?= logo_svg() ?></a>
          <p class="site-footer-slogan"><?= e(site()['site']['tagline']) ?></p>
        </div>
        <nav id="footer-nav" aria-label="Alt menü">
          <h2 class="site-footer-title">Menü</h2>
          <ul>
<?php foreach (routes() as $id => $r): if (!$r['footer']) { continue; } ?>
            <li><a class="site-footer-link" href="<?= e($r['path']) ?>"<?= current_attr($id) ?>><?= e($r['label']) ?></a></li>
<?php endforeach; ?>
          </ul>
        </nav>
        <div>
          <h2 class="site-footer-title">İletişim</h2>
          <ul class="site-footer-contact">
            <li class="site-footer-contact-item"><span class="site-footer-contact-label">Telefon</span><span><a class="site-footer-link" href="<?= e($c['phone']['tel']) ?>" data-track="phone"><?= nbsp($c['phone']['display']) ?></a></span></li>
            <li class="site-footer-contact-item"><span class="site-footer-contact-label">WhatsApp</span><span><a class="site-footer-link" href="<?= e($c['whatsapp']['url']) ?>" target="_blank" rel="noopener" data-track="whatsapp"><?= nbsp($c['whatsapp']['display']) ?><span class="visually-hidden"> (yeni sekmede açılır)</span></a></span></li>
            <li class="site-footer-contact-item"><span class="site-footer-contact-label">E-posta</span><span><a class="site-footer-link" href="<?= e($c['email']['url']) ?>" data-track="email"><?= e($c['email']['value']) ?></a></span></li>
            <li class="site-footer-contact-item"><span class="site-footer-contact-label">Instagram</span><a class="site-footer-link" href="<?= e($c['instagram']['url']) ?>" target="_blank" rel="noopener" data-track="instagram">@<?= e($c['instagram']['handle']) ?><span class="visually-hidden"> (yeni sekmede açılır)</span></a></li>
            <li class="site-footer-contact-item"><span class="site-footer-contact-label">Adres</span><address class="site-footer-address"><a class="site-footer-link" href="<?= e($c['address']['map_url']) ?>" target="_blank" rel="noopener" data-track="map"><?= e($c['address']['value']) ?><span class="visually-hidden"> (yeni sekmede açılır)</span></a></address></li>
          </ul>
        </div>
      </div>
      <div class="site-footer-bottom">
        <p>© <?= e(date('Y')) ?> <?= e(site()['site']['name']) ?></p>
        <a class="site-footer-link" href="<?= e(url('privacy')) ?>"<?= current_attr('privacy') ?>>KVKK Aydınlatma Metni</a>
      </div>
    </div>
  </footer>
