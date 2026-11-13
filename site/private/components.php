<?php
/**
 * Tekrarlayan bileşenler: site.json verisinden HTML üretir (v-1'deki statik HTML ile aynı çıktı).
 * Dinamik her değer e() ile kaçışlanır.
 */
declare(strict_types=1);

/** WhatsApp resmi logosunun tek renkli inline SVG hâli. */
function whatsapp_svg(): string
{
    return '<svg class="icon-svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>';
}

/** Instagram çizgi simgesi (iletişim listesi). */
function instagram_svg(): string
{
    return '<svg class="icon-svg icon-svg-outline" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4.2"/><circle cx="17.4" cy="6.6" r="1.1" fill="currentColor" stroke="none"/></svg>';
}

/** İki renkli inline SVG logo (assets/images/logo/academy-izmir-logo.svg ile aynı). */
function logo_svg(): string
{
    $file = PUBLIC_DIR . '/assets/images/logo/academy-izmir-logo.svg';
    $svg = trim((string) file_get_contents($file));
    return str_replace('<svg xmlns="http://www.w3.org/2000/svg"', '<svg class="site-logo-mark" focusable="false"', $svg);
}

/** Cümle bazlı vurgu: ilk cümle (lead) ve geri kalan (rest). */
function split_lead(string $text): array
{
    $pos = strpos($text, '. ');
    if ($pos === false) {
        return [$text, ''];
    }
    return [substr($text, 0, $pos + 1), substr($text, $pos + 2)];
}

/** Telefon / WhatsApp / başvur eylemleri (kapanış bandı ve duyurular). */
function phone_link(string $class = 'button button-secondary'): string
{
    $c = contact()['phone'];
    return '<a class="' . e($class) . '" href="' . e($c['tel']) . '" data-track="phone"><span class="material-symbols-outlined" aria-hidden="true">call</span> ' . nbsp($c['display']) . '</a>';
}

function whatsapp_link(string $class = 'button button-secondary'): string
{
    $c = contact()['whatsapp'];
    return '<a class="' . e($class) . '" href="' . e($c['url']) . '" target="_blank" rel="noopener" data-track="whatsapp">' . whatsapp_svg() . ' WhatsApp<span class="visually-hidden"> (yeni sekmede açılır)</span></a>';
}

/** Programı id ile bulur. */
function program_by_id(string $id): array
{
    foreach (site()['programs'] as $program) {
        if ($program['id'] === $id) {
            return $program;
        }
    }
    throw new InvalidArgumentException('Bilinmeyen program: ' . $id);
}

/** Galeri fotoğrafını id ile bulur. */
function gallery_item(string $id): array
{
    foreach (site()['gallery'] as $item) {
        if ($item['id'] === $id) {
            return $item;
        }
    }
    throw new InvalidArgumentException('Bilinmeyen galeri öğesi: ' . $id);
}

/** Video kaydı. */
function video_by_id(string $id): array
{
    foreach (site()['videos'] as $video) {
        if ($video['id'] === $id) {
            return $video;
        }
    }
    throw new InvalidArgumentException('Bilinmeyen video: ' . $id);
}

/** <img> etiketi; width/height gerçek dosyadan okunur. */
function img_tag(string $path, string $alt, bool $lazy = true, string $extra = ''): string
{
    [$w, $h] = image_size($path);
    return '<img src="' . e(asset($path)) . '"' . $extra . ' width="' . $w . '" height="' . $h . '"' . ($lazy ? ' loading="lazy"' : '') . ' alt="' . e($alt) . '">';
}

/** Oynatıcı (otomatik oynatma yok, poster var). */
function video_player(string $id, string $label): string
{
    $v = video_by_id($id);
    return '<div class="video-player" data-video-player>
          <video preload="none" poster="' . e(asset($v['poster'])) . '" width="1280" height="720" controls playsinline aria-label="' . e($label) . '">
            <source src="' . e(asset($v['src'])) . '" type="video/mp4">
          </video>
          <button class="video-player-play" type="button" aria-label="Videoyu oynat" data-video-start><span class="material-symbols-outlined" aria-hidden="true">play_arrow</span></button>
        </div>';
}

/** Galeri fotoğrafı: srcset 800/1600 (ana sayfa mozaiği, program ve hakkımızda fotoğrafları). */
function gallery_srcset_img(string $id, string $sizes, bool $lazy = true): string
{
    $g = gallery_item($id);
    [$w, $h] = image_size($g['src_1600']);
    return '<img src="' . e(asset($g['src_800'])) . '" srcset="' . e(asset($g['src_800'])) . ' 800w, ' . e(asset($g['src_1600'])) . ' 1600w" sizes="' . e($sizes) . '" width="' . $w . '" height="' . $h . '"' . ($lazy ? ' loading="lazy"' : '') . ' alt="' . e($g['alt']) . '">';
}

/** Sanatçı kartı (ana sayfa şeridi ve Kurucularımız). */
function instructor_card(array $f): string
{
    $roles = '';
    foreach ($f['titles'] as $title) {
        $roles .= '<li>' . e($title) . '</li>';
    }
    return '<article class="instructor-card is-linked">
              <div class="instructor-card-media">
                ' . img_tag($f['image'], $f['alt']) . '
              </div>
              <div class="instructor-card-body">
                <h3 class="instructor-card-name"><a class="instructor-card-link" href="' . e(url('faculty', '#' . $f['id'])) . '">' . e($f['name']) . '</a></h3>
                <ul class="instructor-card-roles">' . $roles . '</ul>
              </div>
            </article>';
}

/** order alanına göre sıralı sanatçılar. */
function faculty_sorted(): array
{
    $faculty = site()['faculty'];
    usort($faculty, static fn (array $a, array $b): int => $a['order'] <=> $b['order']);
    return $faculty;
}

/** Belirli yılın kazananları (site.json sırası korunur). */
function winners_of_year(int $year): array
{
    return array_values(array_filter(site()['winners'], static fn (array $w): bool => (int) $w['year'] === $year));
}

/** Kazanan kartı. $grouped: kazananlar sayfası (is-grouped). */
function winner_card(array $w, bool $grouped, bool $lazy = true): string
{
    $places = '';
    if (count($w['placements']) > 0) {
        $places = '<ul class="winner-card-placements">';
        foreach ($w['placements'] as $p) {
            $places .= '<li>
                <p class="winner-card-school">' . e($p['university']) . '</p>
                <p class="winner-card-program"><span>' . e($p['faculty']) . '</span> <span>' . e($p['program']) . '</span></p>'
                . (!empty($p['scholarship']) ? '<p class="winner-card-badge">' . e($p['scholarship']) . '</p>' : '')
                . '</li>';
        }
        $places .= '</ul>';
    } else {
        $places = '<p class="winner-card-pending pending-note">[İÇERİK GELECEK]</p>';
    }
    return '<article class="winner-card' . ($grouped ? ' is-grouped' : '') . '">
                <div class="winner-card-media">
                  ' . img_tag($w['image'], $w['alt'], $lazy) . '
                </div>
                <p class="winner-card-year">' . e($w['year']) . '</p>
                <h3 class="winner-card-name">' . e($w['name']) . '</h3>
                ' . $places . '
              </article>';
}

/** Yatay şerit içindeki kazanan kartları (ana sayfa ve konservatuvar bölümü). */
function winner_strip_items(int $year): void
{
    foreach (winners_of_year($year) as $w) {
        echo '<li class="strip-item">' . winner_card($w, false) . '</li>' . "\n";
    }
}

/**
 * Satır paketleme: fotoğrafları satırlara böler. Her satırın en-boy toplamı hedefe (4,4) en yakın olsun;
 * son satır kısa kalabilirse cezasızdır. Dinamik programlama (satır başına en çok 8 fotoğraf).
 * Dönen: satır başına fotoğraf sayıları.
 */
function pack_rows(array $ratios, float $target = 4.4, int $maxPerRow = 8): array
{
    $n = count($ratios);
    $best = array_fill(0, $n + 1, INF);
    $prev = array_fill(0, $n + 1, 0);
    $best[0] = 0.0;
    for ($j = 1; $j <= $n; $j++) {
        for ($i = max(0, $j - $maxPerRow); $i < $j; $i++) {
            $sum = array_sum(array_slice($ratios, $i, $j - $i));
            $cost = abs($sum - $target);
            if ($j === $n && $sum < $target) {
                $cost = 0.0;
            }
            // Eşit maliyette (kayan nokta gürültüsü payıyla) en küçük i seçilir: sonuç her ortamda aynı olur.
            if ($best[$i] + $cost < $best[$j] - 1e-9) {
                $best[$j] = $best[$i] + $cost;
                $prev[$j] = $i;
            }
        }
    }
    $rows = [];
    for ($j = $n; $j > 0; $j = $prev[$j]) {
        array_unshift($rows, $j - $prev[$j]);
    }
    return $rows;
}

/** Galeri sayfası mozaiği (tüm fotoğraflar, lightbox bağlantılarıyla). */
function gallery_mosaic(): void
{
    $items = site()['gallery'];
    $ratios = array_map(static fn (array $g): float => $g['orientation'] === 'landscape' ? 1.5 : 0.6667, $items);
    $index = 0;
    foreach (pack_rows($ratios) as $count) {
        echo '<div class="mosaic-row">' . "\n";
        for ($k = 0; $k < $count; $k++, $index++) {
            $g = $items[$index];
            [$bw, $bh] = image_size($g['src_1600']);
            [$w, $h] = image_size($g['src_800']);
            $eager = $index < 4;
            echo '<figure class="mosaic-item is-' . e($g['orientation']) . '">
            <a class="gallery-tile" href="' . e(asset($g['src_1600'])) . '" data-lightbox-item data-index="' . $index . '" data-w="' . $bw . '" data-h="' . $bh . '">
              <img src="' . e(asset($g['src_800'])) . '" width="' . $w . '" height="' . $h . '"' . ($eager ? '' : ' loading="lazy"') . ' alt="' . e($g['alt']) . '">
            </a>
          </figure>' . "\n";
        }
        echo '</div>' . "\n";
    }
}

/** Başvuru formundaki seçenekler (site.json "application" nesnesi). */
function select_options(array $options, string $placeholder, string $selected = ''): void
{
    echo '<option value=""' . ($selected === '' ? ' selected' : '') . '>' . e($placeholder) . '</option>' . "\n";
    foreach ($options as $o) {
        echo '<option value="' . e($o['id']) . '"' . ($o['id'] === $selected ? ' selected' : '') . '>' . e($o['label']) . '</option>' . "\n";
    }
}

/** aria-current="page" özniteliği: $id mevcut sayfaysa. */
function current_attr(string $id): string
{
    return page()['id'] === $id ? ' aria-current="page"' : '';
}

/** Başvuru formundaki atölye seçenekleri: 5 ana program (site.json başlıkları ve sırasıyla). */
function program_options(): array
{
    return array_map(static fn (array $p): array => ['id' => $p['id'], 'label' => $p['title']], site()['programs']);
}

/** Kazanan yılları, yeniden eskiye. */
function winner_years(): array
{
    $years = array_unique(array_map(static fn (array $w): int => (int) $w['year'], site()['winners']));
    rsort($years);
    return $years;
}

/** Kazananlar sayfası: yıl grupları (yeniden eskiye), her kart data-year / data-category taşır. */
function winners_groups(): void
{
    $count = 0;
    foreach (winner_years() as $year) {
        echo '<section class="winners-year" data-year-group="' . e($year) . '" aria-labelledby="winners-year-' . e($year) . '">
          <h2 class="winners-year-title" id="winners-year-' . e($year) . '">' . e($year) . '</h2>
          <ul class="winner-grid">' . "\n";
        foreach (winners_of_year($year) as $w) {
            echo '<li class="winner-grid-item" data-year="' . e($w['year']) . '" data-category="' . e($w['category']) . '">'
                . winner_card($w, true, $count >= 3) . '</li>' . "\n";
            $count++;
        }
        echo '</ul>
        </section>' . "\n";
    }
}

/** Sanatçı profili (kadro sayfası). Özgeçmişi olmayan kişide özgeçmiş alanı hiç çıkmaz. */
function faculty_profile(array $f, bool $first): void
{
    $roles = '';
    foreach ($f['titles'] as $title) {
        $roles .= '<li>' . e($title) . '</li>';
    }
    echo '<article class="profile" id="' . e($f['id']) . '" aria-labelledby="' . e($f['id']) . '-name">
      <div class="container profile-grid">
        <header class="profile-head">
          <h2 class="profile-name" id="' . e($f['id']) . '-name">' . e($f['name']) . '</h2>
          <ul class="instructor-card-roles">' . $roles . '</ul>
        </header>
        <div class="profile-media">
          ' . img_tag($f['image'], $f['alt'], !$first) . '
        </div>'
        . ($f['bio'] !== null ? '
        <p class="profile-bio">' . e($f['bio']) . '</p>' : '') . '
      </div>
    </article>' . "\n";
}

/** Ana sayfa "Atölyeler ve Sanat Grupları" listesi: özet, alt programlar ve (varsa) fotoğraf site.json'dan. */
function home_programs(): void
{
    $media = ['ballet-dance' => 'bvn09928']; // satır fotoğrafı: galeri öğesi
    foreach (site()['programs'] as $p) {
        echo '<li class="program-row">
        <div class="program-row-head">
          <h3 class="program-row-title"><a href="' . e(url('programs', '#' . $p['id'])) . '">' . e($p['title']) . '</a></h3>';
        if (isset($media[$p['id']])) {
            $g = gallery_item($media[$p['id']]);
            echo '
          <div class="program-row-media">
            ' . img_tag($g['src_800'], $g['alt']) . '
          </div>';
        }
        echo '
        </div>
        <div class="program-row-body">';
        if (!empty($p['card_summary'])) {
            echo '
          <p class="program-row-summary">' . e($p['card_summary']) . '</p>';
        }
        if (count($p['subprograms']) > 0) {
            echo '
          <ul class="program-row-subs">';
            foreach ($p['subprograms'] as $s) {
                echo '
            <li class="program-row-sub">
              <h4 class="program-row-sub-title">' . e($s['title']) . '</h4>
              <p class="program-row-sub-text">' . e($s['card_summary']) . '</p>
            </li>';
            }
            echo '
          </ul>';
        }
        echo '
        </div>
      </li>' . "\n";
    }
}

/** Ana sayfa "Sahneden" mozaiği: seçilmiş 7 fotoğraf, iki satır. Son üçü yalnızca masaüstünde. */
function home_mosaic(): void
{
    $rows = [
        [['bvn02226', false], ['bvn00962', false], ['bvn01366', false]],
        [['bvn02466', false], ['bvn02468', true], ['bvn09060', true], ['bvn01767', true]],
    ];
    foreach ($rows as $row) {
        echo '<div class="mosaic-row">' . "\n";
        foreach ($row as [$id, $desktopOnly]) {
            $g = gallery_item($id);
            echo '<figure class="mosaic-item is-' . e($g['orientation']) . ($desktopOnly ? ' is-desktop-only' : '') . '">
            ' . gallery_srcset_img($id, '(min-width: 48em) 35vw, 100vw') . '
          </figure>' . "\n";
        }
        echo '</div>' . "\n";
    }
}

/** Program bölümündeki yeni atölye metni: alt başlıklı (h3) bloklar. Son blokta, çağrı cümlesinin hemen altına iletişim ve form düğmeleri gelir. */
function program_blocks(array $sections, string $id, bool $withActions): string
{
    $out = '';
    $last = count($sections) - 1;
    foreach ($sections as $i => $section) {
        $out .= '<div class="program-block">
          <h3 class="program-block-title">' . e($section['title']) . '</h3>' . "\n";
        foreach ($section['paragraphs'] as $para) {
            $out .= '<p>' . e($para) . '</p>' . "\n";
        }
        if ($withActions && $i === $last) {
            $out .= '<div class="program-section-actions">
          ' . phone_link() . '
          ' . whatsapp_link() . '
          <a class="button button-primary" href="' . e(url('application', '?program=' . $id)) . '" data-track="form">Katılım ve Bilgi Formu</a>
        </div>' . "\n";
        }
        $out .= '</div>' . "\n";
    }
    return $out;
}

/** Atölyeler sayfasındaki tek program bölümü. Metinler site.json'dan (workshop_text), yerleşim programa göre. */
function program_section(array $p): void
{
    $id = $p['id'];
    $sections = $p['workshop_text']['sections'];
    echo '<section class="program-section" id="' . e($id) . '" aria-labelledby="' . e($id) . '-title">
      <div class="container program-section-grid">
        <header class="program-section-head">
          <h2 class="program-section-title" id="' . e($id) . '-title">' . e($p['title']) . '</h2>
        </header>
        <div class="program-section-body">' . "\n";

    $statement = static function (string $text): string {
        [$lead, $rest] = split_lead($text);
        return '<div class="program-statement">
          <p><span class="program-statement-lead">' . e($lead) . '</span> <span class="program-statement-rest">' . e($rest) . '</span></p>
        </div>' . "\n";
    };
    $subs = static function (array $p, string $field): string {
        $out = '';
        foreach ($p['subprograms'] as $s) {
            $text = $field === 'detail' ? ($s['detail'][0] ?? '') : $s['card_summary'];
            $out .= '<div class="program-sub">
          <h3 class="program-sub-title">' . e($s['title']) . '</h3>
          <p>' . e($text) . '</p>
        </div>' . "\n";
        }
        return $out;
    };
    $prose = static function (array $p): string {
        $out = '<div class="program-prose">' . "\n";
        foreach ($p['workshop_text']['intro'] as $para) {
            $out .= '<p>' . e($para) . '</p>' . "\n";
        }
        return $out . '</div>' . "\n";
    };

    echo $prose($p);
    switch ($id) {
        case 'theatre':
            echo $subs($p, 'detail');
            echo video_player('kisa-9', 'Tiyatro atölyesi videosu') . "\n";
            echo program_blocks($sections, $id, true);
            break;
        case 'creative-drama':
            echo $subs($p, 'detail');
            echo program_blocks($sections, $id, true);
            break;
        case 'conservatory-preparation':
            echo program_blocks(array_slice($sections, 0, 1), $id, false);
            echo $statement($p['success_block']);
            echo '</div>
        <div class="program-section-wide">
          <header class="section-header-row">
            <h3 class="program-sub-title">Kazananlarımız</h3>
            <div class="strip-controls">
              <button class="strip-control" type="button" aria-label="Önceki kazananlar" aria-controls="program-winner-strip" aria-disabled="true" data-strip-prev><span class="material-symbols-outlined" aria-hidden="true">arrow_back</span></button>
              <button class="strip-control" type="button" aria-label="Sonraki kazananlar" aria-controls="program-winner-strip" aria-disabled="false" data-strip-next><span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></button>
            </div>
          </header>
          <section class="strip" id="program-winner-strip" aria-label="2026 kazanan öğrenciler" tabindex="0" data-strip>
            <ul class="strip-list">' . "\n";
            winner_strip_items(2026);
            echo '</ul>
          </section>
          <a class="button button-text" href="' . e(url('winners')) . '">Tüm Kazananlar <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
        </div>
        <div class="program-section-body program-section-tail">' . "\n";
            echo program_blocks(array_slice($sections, 1), $id, true);
            break;
        case 'ballet-dance':
            echo $subs($p, 'summary');
            echo '<figure class="program-media">
          ' . gallery_srcset_img('bvn09928', '(min-width: 48em) 60vw, 100vw') . '
        </figure>' . "\n";
            echo video_player('kisa-3', 'Bale ve dans atölyesi videosu') . "\n";
            echo program_blocks($sections, $id, true);
            break;
        default:
            echo program_blocks($sections, $id, true);
    }

    echo '</div>
      </div>
    </section>' . "\n";
}

/** ISO tarihi (YYYY-MM-DD) Türkçe okunur biçime çevirir: 2026-11-10 => 10 Kasım 2026. */
function format_date_tr(string $iso): string
{
    $months = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
    $d = DateTimeImmutable::createFromFormat('!Y-m-d', $iso);
    if ($d === false) {
        return $iso;
    }
    return (int) $d->format('j') . ' ' . $months[(int) $d->format('n') - 1] . ' ' . $d->format('Y');
}

/** Duyurular, en yeni tarih üstte. */
function announcements_sorted(): array
{
    $items = site()['announcements'];
    usort($items, static fn (array $a, array $b): int => strcmp((string) $b['date'], (string) $a['date']));
    return $items;
}

/** Duyuru kartları (announcement-card bileşeni, DESIGN.md bölüm 19 veri yapısı). */
function announcement_cards(array $items): void
{
    foreach ($items as $a) {
        $tag = '';
        if (!empty($a['program_id'])) {
            try {
                $tag = '<span class="announcement-card-tag">' . e(program_by_id((string) $a['program_id'])['title']) . '</span>';
            } catch (InvalidArgumentException $ex) {
                $tag = '';
            }
        }
        echo '<article class="announcement-card">
          <div class="announcement-card-meta">
            <time class="announcement-card-date" datetime="' . e($a['date']) . '">' . e(format_date_tr((string) $a['date'])) . '</time>' . $tag . '
          </div>
          <h3 class="announcement-card-title">' . e($a['title']) . '</h3>
          <p class="announcement-card-text">' . e($a['description']) . '</p>'
            . (!empty($a['link']) ? '
          <a class="button button-text announcement-card-link" href="' . e($a['link']['url']) . '">' . e($a['link']['label']) . ' <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>' : '') . '
        </article>' . "\n";
    }
}
