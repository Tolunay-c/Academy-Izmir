<?php
require_once __DIR__ . '/../private/bootstrap.php';
$page_id = 'application';
partial('head');
partial('header');
partial('mobile-menu');
?>

  <main class="site-main" id="main" tabindex="-1" data-menu-inert>
    <header class="page-header">
      <div class="container page-header-inner">
        <p class="section-eyebrow">Academy İzmir</p>
        <h1 class="page-header-title">Eğitim Ön Başvuru Formu</h1>
      </div>
    </header>
    <section class="section application-section" aria-label="Eğitim ön başvurusu">
      <div class="container application-layout">
        <div class="application-form-area">
          <form class="form" id="application-form" action="#" method="post" aria-labelledby="application-title" data-application-form>
            <header class="form-header">
              <h2 class="form-title" id="application-title">Sanat Yolculuğuna İlk Adımı <em>At!</em></h2>
              <p class="section-lead">Eğitim programlarımız hakkında bilgi almak ve ön başvurunuzu oluşturmak için aşağıdaki alanları doldurabilirsiniz.</p>
            </header>

            <div class="form-summary" role="alert" tabindex="-1" hidden data-form-summary>
              <p class="form-summary-title">Formda düzeltmeniz gereken alanlar var:</p>
              <ul class="form-summary-list" data-form-summary-list></ul>
            </div>

            <div class="form-field">
              <label class="form-label" for="app-name">Ad Soyad</label>
              <input class="form-control" type="text" id="app-name" name="name" autocomplete="name" placeholder="Başvuru sahibinin adı ve soyadı" required aria-describedby="app-name-error" data-validate="name">
              <p class="form-error" id="app-name-error" hidden></p>
            </div>

            <div class="form-grid">
              <div class="form-field">
                <label class="form-label" for="app-birthdate">Doğum Tarihi</label>
                <input class="form-control form-control-date" type="date" id="app-birthdate" name="birthdate" autocomplete="bday" min="1930-01-01" required aria-describedby="app-birthdate-error" data-validate="birthdate">
                <p class="form-error" id="app-birthdate-error" hidden></p>
              </div>
              <div class="form-field">
                <label class="form-label" for="app-age-group">Yaş Grubu</label>
                <span class="form-select">
                  <select class="form-control" id="app-age-group" name="age-group" required aria-describedby="app-age-group-error" data-validate="age-group">
<?php select_options(site()['application']['age_groups'], 'Yaş grubu seçin'); ?>
                </select>
                  <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
                </span>
                <p class="form-error" id="app-age-group-error" hidden></p>
              </div>
            </div>

            <div class="form-field">
              <label class="form-label" for="app-education">Eğitim Durumu</label>
              <span class="form-select">
                <select class="form-control" id="app-education" name="education" required aria-describedby="app-education-error" data-validate="education">
<?php select_options(site()['application']['education_levels'], 'Eğitim durumu seçin'); ?>
                </select>
                <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
              </span>
              <p class="form-error" id="app-education-error" hidden></p>
            </div>

            <div class="form-field">
              <label class="form-label" for="app-program">Başvurmak İstediğiniz Eğitim</label>
              <span class="form-select">
                <select class="form-control" id="app-program" name="program" required aria-describedby="app-program-error" data-validate="program">
<?php select_options(program_options(), 'Eğitim seçin'); ?>
                </select>
                <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
              </span>
              <p class="form-error" id="app-program-error" hidden></p>
            </div>

            <div class="form-grid">
              <div class="form-field">
                <label class="form-label" for="app-phone">Telefon Numarası</label>
                <input class="form-control" type="tel" id="app-phone" name="phone" inputmode="tel" autocomplete="tel" placeholder="0 (5XX) XXX XX XX" required pattern="\+?[0-9\(\) \-]{10,24}" aria-describedby="app-phone-error" data-validate="phone">
                <p class="form-error" id="app-phone-error" hidden></p>
              </div>
              <div class="form-field">
                <label class="form-label" for="app-email">E-posta Adresi <span class="form-optional">isteğe bağlı</span></label>
                <input class="form-control" type="email" id="app-email" name="email" autocomplete="email" placeholder="ornek@eposta.com" aria-describedby="app-email-error" data-validate="email">
                <p class="form-error" id="app-email-error" hidden></p>
              </div>
            </div>

            <fieldset class="form-fieldset">
              <legend class="form-label">Sanat Eğitimi Deneyiminiz <span class="form-optional">isteğe bağlı</span></legend>
              <div class="radio-group">
<?php foreach (site()['application']['art_experience'] as $o): ?>
                <label class="radio-option">
                  <input class="radio-option-input" type="radio" name="art-experience" value="<?= e($o['id']) ?>"<?= $o['id'] === 'no' ? ' checked' : '' ?>>
                  <span class="radio-option-text"><?= e($o['label']) ?></span>
                </label>
<?php endforeach; ?>
              </div>
            </fieldset>

            <div class="form-field">
              <label class="form-label" for="app-message">Eklemek İstedikleriniz <span class="form-optional">isteğe bağlı</span></label>
              <textarea class="form-control" id="app-message" name="message" rows="4" placeholder="Daha önce aldığınız eğitimler veya sorularınız..."></textarea>
            </div>

            <div class="form-field">
              <label class="form-check">
                <input type="checkbox" id="app-consent" name="consent" required aria-describedby="app-consent-error" data-validate="consent">
                <span>Kişisel verilerimin işlenmesine ilişkin <a class="text-link" href="<?= e(url('privacy')) ?>" target="_blank" rel="noopener">KVKK Aydınlatma Metni<span class="visually-hidden"> (yeni sekmede açılır)</span></a>'ni okudum.</span>
              </label>
              <p class="form-error" id="app-consent-error" hidden></p>
            </div>

            <div class="form-actions">
              <button class="button button-primary" type="submit" data-track="form">Ön Başvurumu Gönder <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></button>
            </div>
          </form>

          <!-- data-prototype: Yayın aşamasında bu blok ve main.js'teki "data-prototype" kodu kaldırılır. -->
          <div class="form-prototype" role="status" tabindex="-1" hidden data-prototype>
            <p>Bu bir tasarım prototipidir. Form gönderimi sitenin yayına alınması aşamasında bağlanacak.</p>
          </div>
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
