<?php
/** <!DOCTYPE> ile <body> açılışına kadar: title, meta, canonical, og, fontlar, CSS, JS. */
$p = page();
$canonical = absolute_url($p['path']);
$ogImage = base_url() . '/assets/images/og-image.jpg';
$searchConsole = trim((string) config('search_console_meta', ''));
$headScripts = (array) config('head_scripts', []);
$hidden = !empty($p['hidden']);
?>
<!DOCTYPE html>
<html lang="tr" class="no-js">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($p['title']) ?></title>
  <meta name="description" content="<?= e($p['description']) ?>">
<?php if ($hidden): ?>
  <meta name="robots" content="noindex">
<?php else: ?>
  <link rel="canonical" href="<?= e($canonical) ?>">
<?php endif; ?>
<?php if ($searchConsole !== ''): ?>
  <meta name="google-site-verification" content="<?= e($searchConsole) ?>">
<?php endif; ?>
  <meta property="og:title" content="<?= e($p['title']) ?>">
  <meta property="og:description" content="<?= e($p['description']) ?>">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="tr_TR">
<?php if (!$hidden): ?>
  <meta property="og:url" content="<?= e($canonical) ?>">
<?php endif; ?>
  <meta property="og:image" content="<?= e($ogImage) ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="Academy İzmir logosu">
  <link rel="icon" href="/favicon.ico" sizes="48x48">
  <link rel="icon" href="/favicon.svg" type="image/svg+xml">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">
  <link rel="manifest" href="/site.webmanifest">
  <meta name="theme-color" content="#0f0d0b">

  <!-- Fontlar: Cormorant (başlık) + Jost (metin). latin-ext alt kümesi otomatik yüklenir. -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,500;0,600;1,500&family=Jost:wght@400;500;600&display=swap" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,500;0,600;1,500&family=Jost:wght@400;500;600&display=swap"></noscript>

  <!-- İkonlar: Material Symbols Outlined, yalnızca kullanılan ikonlar (icon_names alfabetik sıralı) -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0&amp;icon_names=arrow_back,arrow_forward,call,close,directions,edit_square,expand_more,location_on,menu,pause,play_arrow,volume_off,volume_up&amp;display=block">

  <link rel="stylesheet" href="/assets/css/tokens.css">
  <link rel="stylesheet" href="/assets/css/base.css">
  <link rel="stylesheet" href="/assets/css/components.css">
  <script src="/assets/js/main.js" defer></script>
<?php foreach ($headScripts as $snippet): ?>
  <?= $snippet ?>

<?php endforeach; ?>
</head>
<body class="site-body">
