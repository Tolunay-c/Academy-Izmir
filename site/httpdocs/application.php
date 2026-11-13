<?php
require_once __DIR__ . '/../private/bootstrap.php';
require_once PRIVATE_DIR . '/application-form.php';
$page_id = 'application';
// Oturum, doğrulama, gönderim ve PRG yönlendirmesi: çıktıdan önce çalışır.
$form = application_handle();
$errors = $form['errors'];
partial('head');
partial('header');
partial('mobile-menu');
?>

  <main class="site-main" id="main" tabindex="-1" data-menu-inert>
    <header class="page-header">
      <div class="container page-header-inner">
        <p class="section-eyebrow">Academy İzmir</p>
        <h1 class="page-header-title">Katılım ve Bilgi Formu</h1>
      </div>
    </header>
    <section class="section application-section" aria-label="Katılım ve bilgi formu">
      <div class="container application-layout">
        <div class="application-form-area">
<?php if ($form['view'] === 'success'): ?>
          <div class="form-result" role="status" tabindex="-1" data-application-success>
            <h2 class="form-title">Ön başvurunuz bize ulaştı.</h2>
            <p class="section-lead">Teşekkür ederiz.</p>
            <div class="hero-actions">
              <a class="button button-primary" href="<?= e(url('home')) ?>">Ana Sayfa</a>
              <a class="button button-secondary" href="<?= e(url('programs')) ?>">Atölyeler</a>
            </div>
          </div>
<?php else: ?>
<?php if ($form['failed']): ?>
          <div class="form-notice" role="alert" tabindex="-1" data-application-failure>
            <p>Başvurunuz şu anda gönderilemedi. Lütfen daha sonra tekrar deneyin ya da telefon veya WhatsApp ile bize ulaşın.</p>
            <div class="hero-actions">
              <?= phone_link('button button-secondary') ?>

              <?= whatsapp_link('button button-secondary') ?>

            </div>
          </div>
<?php endif; ?>
          <form class="form" id="application-form" action="<?= e(url('application')) ?>" method="post" aria-labelledby="application-title" data-application-form>
            <header class="form-header">
              <h2 class="form-title" id="application-title">Sanat Yolculuğuna İlk Adımı <em>At!</em></h2>
              <p class="section-lead">Eğitim programlarımız hakkında bilgi almak ve ön başvurunuzu oluşturmak için aşağıdaki alanları doldurabilirsiniz.</p>
            </header>

            <div class="form-summary" role="alert" tabindex="-1"<?= $errors ? '' : ' hidden' ?> data-form-summary>
              <p class="form-summary-title">Formda düzeltmeniz gereken alanlar var:</p>
              <ul class="form-summary-list" data-form-summary-list>
<?php foreach ($errors as $key => $message): ?>
                <li><?php if ($key === 'form'): ?><?= e($message) ?><?php else: ?><a href="#app-<?= e($key) ?>"><?= e($message) ?></a><?php endif; ?></li>
<?php endforeach; ?>
              </ul>
            </div>

            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="ts" value="<?= e(form_timestamp()) ?>">
            <div class="visually-hidden" aria-hidden="true">
              <label for="app-extra-ref">Bu alanı boş bırakın</label>
              <input type="text" id="app-extra-ref" name="a_extra_ref" tabindex="-1" autocomplete="off" value="">
            </div>

            <div class="form-field">
              <label class="form-label" for="app-name">Ad Soyad</label>
              <input class="form-control" type="text" id="app-name" name="name" autocomplete="name" placeholder="Başvuru sahibinin adı ve soyadı" required value="<?= fv('name') ?>"<?= f_invalid('name') ?> aria-describedby="app-name-error" data-validate="name">
              <?= f_error('name') ?>

            </div>

            <div class="form-grid">
              <div class="form-field">
                <label class="form-label" for="app-birthdate">Doğum Tarihi</label>
                <input class="form-control form-control-date" type="date" id="app-birthdate" name="birthdate" autocomplete="bday" min="1930-01-01" required value="<?= fv('birthdate') ?>"<?= f_invalid('birthdate') ?> aria-describedby="app-birthdate-error" data-validate="birthdate">
                <?= f_error('birthdate') ?>

              </div>
              <div class="form-field">
                <label class="form-label" for="app-age-group">Yaş Grubu</label>
                <span class="form-select">
                  <select class="form-control" id="app-age-group" name="age-group" required<?= f_invalid('age-group') ?> aria-describedby="app-age-group-error" data-validate="age-group">
<?php select_options(site()['application']['age_groups'], 'Yaş grubu seçin', (string) $form['values']['age-group']); ?>
                  </select>
                  <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
                </span>
                <?= f_error('age-group') ?>

              </div>
            </div>

            <div class="form-field">
              <label class="form-label" for="app-education">Eğitim Durumu</label>
              <span class="form-select">
                <select class="form-control" id="app-education" name="education" required<?= f_invalid('education') ?> aria-describedby="app-education-error" data-validate="education">
<?php select_options(site()['application']['education_levels'], 'Eğitim durumu seçin', (string) $form['values']['education']); ?>
                </select>
                <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
              </span>
              <?= f_error('education') ?>

            </div>

            <div class="form-field">
              <label class="form-label" for="app-program">Katılmak İstediğiniz Atölye</label>
              <span class="form-select">
                <select class="form-control" id="app-program" name="program" required<?= f_invalid('program') ?> aria-describedby="app-program-error" data-validate="program">
<?php select_options(program_options(), 'Atölye seçin', (string) $form['values']['program']); ?>
                </select>
                <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
              </span>
              <?= f_error('program') ?>

            </div>

            <div class="form-grid">
              <div class="form-field">
                <label class="form-label" for="app-phone">Telefon Numarası</label>
                <input class="form-control" type="tel" id="app-phone" name="phone" inputmode="tel" autocomplete="tel" placeholder="0 (5XX) XXX XX XX" required pattern="\+?[0-9\(\) \-]{10,24}" value="<?= fv('phone') ?>"<?= f_invalid('phone') ?> aria-describedby="app-phone-error" data-validate="phone">
                <?= f_error('phone') ?>

              </div>
              <div class="form-field">
                <label class="form-label" for="app-email">E-posta Adresi <span class="form-optional">isteğe bağlı</span></label>
                <input class="form-control" type="email" id="app-email" name="email" autocomplete="email" placeholder="ornek@eposta.com" value="<?= fv('email') ?>"<?= f_invalid('email') ?> aria-describedby="app-email-error" data-validate="email">
                <?= f_error('email') ?>

              </div>
            </div>

            <fieldset class="form-fieldset" id="app-art-experience">
              <legend class="form-label">Sanat Eğitimi Deneyiminiz <span class="form-optional">isteğe bağlı</span></legend>
              <div class="radio-group">
<?php foreach (site()['application']['art_experience'] as $o): ?>
                <label class="radio-option">
                  <input class="radio-option-input" type="radio" name="art-experience" value="<?= e($o['id']) ?>"<?= $o['id'] === $form['values']['art-experience'] ? ' checked' : '' ?><?= f_invalid('art-experience') ?> aria-describedby="app-art-experience-error">
                  <span class="radio-option-text"><?= e($o['label']) ?></span>
                </label>
<?php endforeach; ?>
              </div>
              <?= f_error('art-experience') ?>

            </fieldset>

            <div class="form-field">
              <label class="form-label" for="app-message">Eklemek İstedikleriniz <span class="form-optional">isteğe bağlı</span></label>
              <textarea class="form-control" id="app-message" name="message" rows="4" maxlength="2000" placeholder="Daha önce aldığınız eğitimler veya sorularınız..."<?= f_invalid('message') ?> aria-describedby="app-message-error"><?= fv('message') ?></textarea>
              <?= f_error('message') ?>

            </div>

            <div class="form-field">
              <label class="form-check">
                <input type="checkbox" id="app-consent" name="consent" value="1" required<?= $form['values']['consent'] ? ' checked' : '' ?><?= f_invalid('consent') ?> aria-describedby="app-consent-error" data-validate="consent">
                <span>Kişisel verilerimin işlenmesine ilişkin <a class="text-link" href="<?= e(url('privacy')) ?>" target="_blank" rel="noopener">KVKK Aydınlatma Metni<span class="visually-hidden"> (yeni sekmede açılır)</span></a>'ni okudum.</span>
              </label>
              <?= f_error('consent') ?>

            </div>

            <div class="form-actions">
              <button class="button button-primary" type="submit" data-track="form"><span data-submit-label>Ön Başvurumu Gönder</span> <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></button>
            </div>
          </form>
<?php endif; ?>
        </div>

        <aside class="application-aside" aria-labelledby="application-contact-title">
          <h2 class="section-eyebrow" id="application-contact-title">İletişim</h2>
          <div class="hero-actions">
            <?= phone_link('button button-secondary') ?>

            <?= whatsapp_link('button button-secondary') ?>

          </div>
        </aside>
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
