<?php
/**
 * Kapanış bandı. Değişkenler: $cta_id (başlık id öneki, örn. "home"), $cta_lead (isteğe bağlı giriş paragrafı).
 */
$cta_lead = $cta_lead ?? null;
?>
    <section class="cta-band" aria-labelledby="<?= e($cta_id) ?>-apply-title">
      <div class="container cta-band-inner">
<?php if ($cta_lead !== null): ?>
        <p class="cta-band-lead"><?= e($cta_lead) ?></p>
<?php endif; ?>
        <h2 class="cta-band-title" id="<?= e($cta_id) ?>-apply-title">Academy İzmir'de sanatın içinde, hayatın tam <em>kalbinde</em> buluşalım!</h2>
        <div class="cta-band-actions">
          <a class="button button-primary" href="<?= e(url('application')) ?>" data-track="form">Başvur</a>
          <?= phone_link() ?>

          <?= whatsapp_link() ?>

        </div>
      </div>
    </section>
