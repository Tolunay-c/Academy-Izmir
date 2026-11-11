<?php
require_once __DIR__ . '/../private/bootstrap.php';
$page_id = 'winners';
partial('head');
partial('header');
partial('mobile-menu');
?>

  <main class="site-main" id="main" tabindex="-1" data-menu-inert>
    <header class="page-header">
      <div class="container page-header-inner">
        <p class="section-eyebrow">Academy İzmir</p>
        <h1 class="page-header-title">Kazananlarımız</h1>
      </div>
    </header>
    <section class="section winners-intro" aria-label="Başarı">
      <div class="container winners-intro-body">
        <div class="program-statement">
          <p><span class="program-statement-lead">15 yılı aşkın süredir öğrencilerimizi konservatuvar ve güzel sanatlar fakültelerinin yetenek sınavlarına hazırlıyoruz.</span> <span class="program-statement-rest">Oyunculuk, doğaçlama, tirat, ses, hareket ve sahne çalışmalarını kapsayan eğitimlerimizle öğrencilerimizin Türkiye'nin önde gelen sanat eğitimi kurumlarına yerleşmesinden gurur duyuyoruz. Yaklaşık %80'lik yerleşme başarımız, bu çalışmalarımızın değerli bir sonucudur.</span></p>
        </div>
          <a class="button button-text" href="<?= e(url('programs')) ?>#conservatory-preparation">Konservatuvar ve Güzel Sanatlara Hazırlık <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
      </div>
    </section>

    <section class="section winners-list" aria-label="Kazananlar">
      <div class="container">
        <div class="filters" data-filters>
          <div class="filter-group" role="group" aria-labelledby="filter-year-label">
            <p class="filter-label" id="filter-year-label">Yıl</p>
            <div class="fade-scroll" data-fade-scroll>
              <div class="fade-scroll-list">
                <button class="filter-button" type="button" aria-pressed="true" data-filter-year="all">Tümü</button>
<?php foreach (winner_years() as $year): ?>
                <button class="filter-button" type="button" aria-pressed="false" data-filter-year="<?= e($year) ?>"><?= e($year) ?></button>
<?php endforeach; ?>
              </div>
            </div>
          </div>
          <div class="filter-group" role="group" aria-labelledby="filter-category-label">
            <p class="filter-label" id="filter-category-label">Kategori</p>
            <div class="fade-scroll" data-fade-scroll>
              <div class="fade-scroll-list">
                <button class="filter-button" type="button" aria-pressed="true" data-filter-category="all">Tümü</button>
                <button class="filter-button" type="button" aria-pressed="false" data-filter-category="university">Üniversite ve Konservatuvar</button>
                <button class="filter-button" type="button" aria-pressed="false" data-filter-category="art-high-school">Güzel Sanatlar Lisesi</button>
              </div>
            </div>
          </div>
          <p class="filter-status" role="status" aria-live="polite" data-filter-status><?= count(site()['winners']) ?> öğrenci gösteriliyor</p>
        </div>
        <div class="winners-groups" data-winners>
<?php winners_groups(); ?>
        </div>
        <p class="empty-state is-hidden" data-filter-empty>Bu liste yakında eklenecek.</p>
      </div>
    </section>

    <?php partial('cta-band', ['cta_id' => 'winners']); ?>
  </main>

<?php
partial('footer');
partial('whatsapp-float');
partial('contact-bar');
?>
</body>
</html>
