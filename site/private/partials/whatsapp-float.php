<?php /** Sabit WhatsApp bağlantısı (yalnızca 48em ve üstünde görünür). */ ?>
  <aside class="whatsapp-float" aria-label="WhatsApp">
    <a class="whatsapp-float-link" href="<?= e(contact()['whatsapp']['url']) ?>" target="_blank" rel="noopener" data-track="whatsapp" aria-label="WhatsApp ile yazın (yeni sekmede açılır)">
      <?= whatsapp_svg() ?>

    </a>
  </aside>
