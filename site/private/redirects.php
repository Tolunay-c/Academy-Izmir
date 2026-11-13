<?php
/**
 * ESKİ SİTE (https://www.academyizmir.com, 2016'dan kalma .html ve .aspx sayfaları) -> yeni adresler, 301.
 * Canlı site taranarak çıkarıldı (sitemap.xml ve sayfa içi bağlantılar, 38 sayfa). Adresler yol olarak yazılır.
 * # sunucuya gitmediği için bölüm bağlantıları (/atolyeler#theatre) kullanılmaz, ilgili sayfaya yönlendirilir.
 * Yeni sitede karşılığı olmayan eski sayfalar (resim, fotoğrafçılık, jimnastik, sinema-tv vb.) /atolyeler'e gider.
 * Yeni sitenin ilk günlerinde yayınlanan ve sonradan adı değişen adresler de buradadır (en altta); hepsi tek adımda
 * güncel adrese gider (zincir yok). ?program= gibi sorgu dizesi korunur, #bağlantı tarayıcıda zaten korunur.
 * web.config ve dev-router.php bu listeden türer.
 */
return [
    // Ana sayfa ve genel sayfalar
    '/hakkimizda.html' => '/hakkimizda',
    '/iletisim.aspx' => '/iletisim',
    '/kadromuz.html' => '/sanatci-kadromuz',
    '/kazananlar.html' => '/kazananlarimiz',
    '/kazanan-listesi.html' => '/kazananlarimiz',
    '/etkinlikler.html' => '/duyurular',

    // Atölyeler
    '/bolumler.html' => '/atolyeler',
    '/tiyatro.html' => '/atolyeler',
    '/yaratici-drama.html' => '/atolyeler',
    '/cocuklar-icin-drama.html' => '/atolyeler',
    '/ozel-yetenek-sinavi.html' => '/atolyeler',
    '/cocuklar-icin-bale.html' => '/atolyeler',
    '/dans.html' => '/atolyeler',
    '/konusma-egitimi.html' => '/atolyeler',
    // Kaldırılan branşlar
    '/sinema-ve-dizi-oyunculugu.html' => '/atolyeler',
    '/sinema-televizyon.html' => '/atolyeler',
    '/fotografcilik.html' => '/atolyeler',
    '/muzik-egitimi.html' => '/atolyeler',
    '/resim-kurslari.html' => '/atolyeler',
    '/jimnastik.html' => '/atolyeler',

    // Sanatçı sayfaları: kurucular kendi bölümlerine (tarayıcı #'yi Location başlığından uygular), diğerleri kadro sayfasına
    '/ozlem-baskaya.html' => '/sanatci-kadromuz#ozlem-baskaya',
    '/ozgur-baskaya.html' => '/sanatci-kadromuz#ozgur-baskaya',
    '/tamer-yilmaz.html' => '/sanatci-kadromuz',
    '/asli-sinke.html' => '/sanatci-kadromuz',
    '/selda-uzunkaya.html' => '/sanatci-kadromuz',
    '/yesim-turgut.html' => '/sanatci-kadromuz',
    '/ozlem-gur.html' => '/sanatci-kadromuz',
    '/derya-tiryaki.html' => '/sanatci-kadromuz',
    '/dilek-kutsav.html' => '/sanatci-kadromuz',
    '/firat-halavut.html' => '/sanatci-kadromuz',

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

    // Yeni sitenin eski adları (ilk yayın) -> güncel adresler
    '/egitimler' => '/atolyeler',
    '/egitmen-kadromuz' => '/sanatci-kadromuz',
    '/on-basvuru' => '/katilim-formu',
];
