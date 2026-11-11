<?php /** Mobil alt iletişim çubuğu. */ $c = contact(); ?>
  <nav class="contact-bar" aria-label="Hızlı iletişim" data-menu-inert>
    <a class="contact-bar-link" href="<?= e($c['phone']['tel']) ?>" data-track="phone">
      <span class="material-symbols-outlined" aria-hidden="true">call</span>
      Ara
    </a>
    <a class="contact-bar-link" href="<?= e($c['whatsapp']['url']) ?>" target="_blank" rel="noopener" data-track="whatsapp">
      <?= whatsapp_svg() ?>

      WhatsApp<span class="visually-hidden"> (yeni sekmede açılır)</span></a>
    <a class="contact-bar-link contact-bar-link-primary" href="<?= e(url('application')) ?>" data-track="form">
      <span class="material-symbols-outlined" aria-hidden="true">edit_square</span>
      Başvur
    </a>
  </nav>
