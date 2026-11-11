<?php
/**
 * ESKİ SİTE (https://www.academyizmir.com, 2016'dan kalma .html ve .aspx sayfaları) -> yeni adresler, 301.
 * Canlı site taranarak çıkarıldı (sitemap.xml ve sayfa içi bağlantılar, 38 sayfa). Adresler yol olarak yazılır.
 * # sunucuya gitmediği için bölüm bağlantıları (/egitimler#theatre) kullanılmaz, ilgili sayfaya yönlendirilir.
 * Yeni sitede karşılığı olmayan eski sayfalar (resim, fotoğrafçılık, jimnastik, sinema-tv vb.) /egitimler'e gider.
 * web.config ve dev-router.php bu listeden türer.
 */
return [
    // Ana sayfa ve genel sayfalar
    '/hakkimizda.html' => '/hakkimizda',
    '/iletisim.aspx' => '/iletisim',
    '/kadromuz.html' => '/egitmen-kadromuz',
    '/kazananlar.html' => '/kazananlarimiz',
    '/kazanan-listesi.html' => '/kazananlarimiz',
    '/etkinlikler.html' => '/duyurular',

    // Eğitimler
    '/bolumler.html' => '/egitimler',
    '/tiyatro.html' => '/egitimler',
    '/yaratici-drama.html' => '/egitimler',
    '/cocuklar-icin-drama.html' => '/egitimler',
    '/ozel-yetenek-sinavi.html' => '/egitimler',
    '/cocuklar-icin-bale.html' => '/egitimler',
    '/dans.html' => '/egitimler',
    '/konusma-egitimi.html' => '/egitimler',
    // Kaldırılan branşlar
    '/sinema-ve-dizi-oyunculugu.html' => '/egitimler',
    '/sinema-televizyon.html' => '/egitimler',
    '/fotografcilik.html' => '/egitimler',
    '/muzik-egitimi.html' => '/egitimler',
    '/resim-kurslari.html' => '/egitimler',
    '/jimnastik.html' => '/egitimler',

    // Eğitmen sayfaları: kurucular kendi bölümlerine (tarayıcı #'yi Location başlığından uygular), diğerleri kadro sayfasına
    '/ozlem-baskaya.html' => '/egitmen-kadromuz#ozlem-baskaya',
    '/ozgur-baskaya.html' => '/egitmen-kadromuz#ozgur-baskaya',
    '/tamer-yilmaz.html' => '/egitmen-kadromuz',
    '/asli-sinke.html' => '/egitmen-kadromuz',
    '/selda-uzunkaya.html' => '/egitmen-kadromuz',
    '/yesim-turgut.html' => '/egitmen-kadromuz',
    '/ozlem-gur.html' => '/egitmen-kadromuz',
    '/derya-tiryaki.html' => '/egitmen-kadromuz',
    '/dilek-kutsav.html' => '/egitmen-kadromuz',
    '/firat-halavut.html' => '/egitmen-kadromuz',

    // Galeri
    '/galeri.html' => '/galeri',
    '/galeri_bale.html' => '/galeri',
    '/galeri_etkinlikler.html' => '/galeri',
    '/galeri_yetenek_sinavi.html' => '/galeri',
    '/galeri_piyano.html' => '/galeri',
    '/galeri_resim.html' => '/galeri',
    '/galeri_tiyatro.html' => '/galeri',
    '/galeri_yaratici_drama.html' => '/galeri',
    '/galeri_sene_sonu.html' => '/galeri',
];
