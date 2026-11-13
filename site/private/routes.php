<?php
/**
 * Tüm sayfaların tek listesi. Adresler Türkçe, dosyalar İngilizce.
 * web.config (yeniden yazma ve yönlendirme), dev-router.php, sitemap.xml, menüler, title, description,
 * canonical ve og etiketleri bu listeden türer. Yeni sayfa eklemek için README.md'ye bakın.
 *
 * Alanlar:
 *   path        genel adres (başta eğik çizgi, sonda yok)
 *   file        httpdocs içindeki PHP dosyası
 *   title       <title> ve og:title
 *   description meta description ve og:description
 *   label       mobil menü ve footer menüsü adı
 *   nav         masaüstü header menüsü kısa adı (null: header menüsünde yok)
 *   mobile      mobil menüde listelensin
 *   footer      footer menüsünde listelensin
 *   priority    sitemap önceliği
 *   hidden      true: sitemap, canonical ve yeniden yazma kurallarına girmez (404 sayfası)
 */
return [
    'home' => [
        'path' => '/',
        'file' => 'index.php',
        'title' => 'Ana Sayfa | Academy İzmir',
        'description' => '2011 yılından bu yana sanatın yalnızca öğretildiği değil; deneyimlendiği, sorgulandığı, birlikte üretildiği ve yaşandığı bir dünya kuruyoruz.',
        'label' => 'Ana Sayfa',
        'nav' => null,
        'mobile' => true,
        'footer' => true,
        'priority' => 1.0,
    ],
    'programs' => [
        'path' => '/atolyeler',
        'file' => 'programs.php',
        'title' => 'Atölyeler ve Sanat Grupları | Academy İzmir',
        'description' => 'Klasik bale, modern dans, Latin ve salon dansları ile çocukları, gençleri ve yetişkinleri hareketin ve sanatın dünyasıyla buluşturuyoruz.',
        'label' => 'Atölyeler ve Sanat Grupları',
        'nav' => 'Atölyeler',
        'mobile' => true,
        'footer' => true,
        'priority' => 0.8,
    ],
    'about' => [
        'path' => '/hakkimizda',
        'file' => 'about.php',
        'title' => 'Hakkımızda | Academy İzmir',
        'description' => 'Her insanın kendine özgü bir sesi, anlatacak bir hikâyesi ve keşfedilmeyi bekleyen bir potansiyeli vardır.',
        'label' => 'Hakkımızda',
        'nav' => 'Hakkımızda',
        'mobile' => true,
        'footer' => true,
        'priority' => 0.8,
    ],
    'faculty' => [
        'path' => '/sanatci-kadromuz',
        'file' => 'faculty.php',
        'title' => 'Sanatçı Kadromuz | Academy İzmir',
        'description' => 'Kırk yılı aşkın sanat birikimini ve sahne deneyimini yeni kuşaklarla paylaşmayı sürdürmektedir.',
        'label' => 'Sanatçı Kadromuz',
        'nav' => 'Kadro',
        'mobile' => true,
        'footer' => true,
        'priority' => 0.8,
    ],
    'winners' => [
        'path' => '/kazananlarimiz',
        'file' => 'winners.php',
        'title' => 'Kazananlarımız | Academy İzmir',
        'description' => '15 yılı aşkın süredir öğrencilerimizi konservatuvar ve güzel sanatlar fakültelerinin yetenek sınavlarına hazırlıyoruz. Yaklaşık %80\'lik yerleşme başarısı.',
        'label' => 'Kazananlarımız',
        'nav' => 'Kazananlar',
        'mobile' => true,
        'footer' => true,
        'priority' => 0.8,
    ],
    'gallery' => [
        'path' => '/galeri',
        'file' => 'gallery.php',
        'title' => 'Sanatsal Projelerimiz ve Galeri | Academy İzmir',
        'description' => 'Yılların sahne birikimini, sanat üretmeye devam eden profesyonel sanatçıların deneyimiyle buluşturuyor; yeteneği emekle, disiplini yaratıcılıkla, deneyimi özgür düşünceyle besliyoruz.',
        'label' => 'Sanatsal Projelerimiz ve Galeri',
        'nav' => 'Galeri',
        'mobile' => true,
        'footer' => true,
        'priority' => 0.8,
    ],
    'announcements' => [
        'path' => '/duyurular',
        'file' => 'announcements.php',
        'title' => 'Güncel Kayıtlar ve Duyurular | Academy İzmir',
        'description' => 'Yeni gruplar, atölyeler ve etkinlikler için duyuru alanı.',
        'label' => 'Güncel Kayıtlar ve Duyurular',
        'nav' => 'Duyurular',
        'mobile' => true,
        'footer' => true,
        'priority' => 0.8,
    ],
    'application' => [
        'path' => '/katilim-formu',
        'file' => 'application.php',
        'title' => 'Katılım ve Bilgi Formu | Academy İzmir',
        'description' => 'Academy İzmir atölyeleri hakkında bilgi almak ve katılım formunuzu oluşturmak için: tiyatro, yaratıcı drama, konservatuvar hazırlık, bale ve dans, retorika.',
        'label' => 'Katılım ve Bilgi Formu',
        'nav' => null,
        'mobile' => false,
        'footer' => true,
        'priority' => 0.8,
    ],
    'contact' => [
        'path' => '/iletisim',
        'file' => 'contact.php',
        'title' => 'İletişim ve Başvuru | Academy İzmir',
        'description' => 'Gelin, hayatın bize sunduğuyla yetinmeyelim; birlikte daha fazlasını hayal edelim, üretelim ve yaşayalım.',
        'label' => 'İletişim ve Başvuru',
        'nav' => 'İletişim',
        'mobile' => true,
        'footer' => true,
        'priority' => 0.8,
    ],
    'privacy' => [
        'path' => '/kvkk-aydinlatma-metni',
        'file' => 'privacy-notice.php',
        'title' => 'KVKK Aydınlatma Metni | Academy İzmir',
        'description' => 'Academy İzmir internet sitesi üzerinden yapılan iletişim ve atölye başvurularına ilişkin kişisel verilerin işlenmesi hakkında aydınlatma metni.',
        'label' => 'KVKK Aydınlatma Metni',
        'nav' => null,
        'mobile' => false,
        'footer' => false,
        'priority' => 0.8,
    ],
    'not_found' => [
        'path' => '/404',
        'file' => '404.php',
        'title' => 'Sayfa bulunamadı | Academy İzmir',
        'description' => 'İzmir\'de Sanat Atölyeleri ve Sahne Çalışmaları',
        'label' => 'Sayfa bulunamadı',
        'nav' => null,
        'mobile' => false,
        'footer' => false,
        'priority' => '0.0',
        'hidden' => true,
    ],
];
