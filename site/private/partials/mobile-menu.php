<?php /** Mobil tam alan menü (routes.php "mobile" alanı). */ ?>
  <nav class="mobile-menu" id="mobile-menu" aria-label="Mobil menü" tabindex="-1" data-mobile-menu>
    <div class="mobile-menu-inner">
      <ul>
<?php foreach (routes() as $id => $r): if (!$r['mobile']) { continue; } ?>
          <li><a class="mobile-menu-link" href="<?= e($r['path']) ?>"<?= current_attr($id) ?>><?= e($r['label']) ?></a></li>
<?php endforeach; ?>
      </ul>
      <div class="mobile-menu-actions">
        <a class="button button-primary button-block" href="<?= e(url('application')) ?>" data-track="form">Katılım ve Bilgi Formu</a>
        <?= phone_link('button button-secondary button-block') ?>

        <?= whatsapp_link('button button-secondary button-block') ?>

      </div>
    </div>
  </nav>
