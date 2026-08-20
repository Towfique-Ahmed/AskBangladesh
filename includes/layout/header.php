<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');

/**
 * Page shell. Pages set $seo via bd_seo() before requiring this file; if they
 * do not, the static table in bd_page_seo() supplies the defaults.
 */
$seo     = $seo ?? bd_seo(bd_page_seo(bd_current_page()));
$current = bd_current_page();

$nav = [
    ['path' => '',           'page' => 'home',       'label' => 'Home',       'icon' => '🏠'],
    ['path' => 'map',        'page' => 'map',        'label' => 'Map',        'icon' => '🗺️'],
    ['path' => 'districts',  'page' => 'districts',  'label' => 'Districts',  'icon' => '📍'],
    ['path' => 'geography',  'page' => 'geography',  'label' => 'Geography',  'icon' => '⛰️'],
    ['path' => 'travel',     'page' => 'travel',     'label' => 'Travel',     'icon' => '🧳'],
    ['path' => 'transport',  'page' => 'transport',  'label' => 'Transport',  'icon' => '🛣️'],
    ['path' => 'time',       'page' => 'time',       'label' => 'Time',       'icon' => '🕰️'],
    ['path' => 'currency',   'page' => 'currency',   'label' => 'Currency',   'icon' => '💱'],
    ['path' => 'gold',       'page' => 'gold',       'label' => 'Gold',       'icon' => '🥇'],
    ['path' => 'sunrise-sunset', 'page' => 'sun',    'label' => 'Sun Times',  'icon' => '🌅'],
    ['path' => 'religion',   'page' => 'religion',   'label' => 'Religion',   'icon' => '🕊️'],
    ['path' => 'government', 'page' => 'government', 'label' => 'Government', 'icon' => '🏛️'],
    ['path' => 'about',      'page' => 'about',      'label' => 'About BD',   'icon' => '🇧🇩'],
];

$ogImage = bd_abs_url('assets/og-image.png');

// Newest data-file timestamp, used as the page's modified date.
$modified = bd_content_modified();
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
<?php if (GA_ENABLED): ?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= rawurlencode(GA_MEASUREMENT_ID) ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?= e(GA_MEASUREMENT_ID) ?>');
</script>
<?php endif; ?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= e($seo['full_title']) ?></title>
<meta name="description" content="<?= e($seo['description']) ?>">
<?php if ($seo['keywords'] !== ''): ?>
<meta name="keywords" content="<?= e($seo['keywords']) ?>">
<?php endif; ?>
<link rel="canonical" href="<?= e($seo['canonical']) ?>">

<?php if ($seo['noindex']): ?>
<meta name="robots" content="noindex, follow">
<?php else: ?>
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
<?php endif; ?>

<meta property="og:type" content="<?= e($seo['type']) ?>">
<meta property="og:site_name" content="<?= e(APP_NAME) ?>">
<meta property="og:title" content="<?= e($seo['title']) ?>">
<meta property="og:description" content="<?= e($seo['description']) ?>">
<meta property="og:url" content="<?= e($seo['canonical']) ?>">
<meta property="og:image" content="<?= e($ogImage) ?>">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="<?= e(APP_NAME) ?> — <?= e(APP_TAGLINE) ?>">
<meta property="og:locale" content="en_US">
<meta property="og:updated_time" content="<?= e($modified) ?>">
<?php if ($seo['type'] === 'article'): ?>
<meta property="article:modified_time" content="<?= e($modified) ?>">
<?php endif; ?>
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($seo['title']) ?>">
<meta name="twitter:description" content="<?= e($seo['description']) ?>">
<meta name="twitter:image" content="<?= e($ogImage) ?>">
<meta name="twitter:image:alt" content="<?= e(APP_NAME) ?> — <?= e(APP_TAGLINE) ?>">

<meta name="theme-color" content="#00a651">
<meta name="author" content="<?= e(APP_NAME) ?>">
<meta name="geo.region" content="BD">
<meta name="geo.placename" content="Bangladesh">

<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='18' fill='%23006a4e'/><circle cx='45' cy='50' r='24' fill='%23f42a41'/></svg>">
<link rel="sitemap" type="application/xml" href="<?= e(bd_url('sitemap.xml')) ?>">
<link rel="stylesheet" href="<?= e(bd_url('assets/css/app.css')) ?>?v=<?= e(APP_VERSION) ?>">

<script type="application/ld+json"><?= json_encode(bd_jsonld_site(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
<script type="application/ld+json"><?= json_encode(bd_jsonld_organization(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
<?php if (!empty($seo['breadcrumbs'])): ?>
<script type="application/ld+json"><?= json_encode(bd_jsonld_breadcrumbs($seo['breadcrumbs']), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
<?php endif; ?>
<?php foreach ($seo['jsonld'] as $block): ?>
<script type="application/ld+json"><?= json_encode($block, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
<?php endforeach; ?>
</head>
<body data-page="<?= e($current) ?>" data-base="<?= e(bd_url()) ?>">

<div class="aurora" aria-hidden="true">
  <span class="aurora__blob aurora__blob--green"></span>
  <span class="aurora__blob aurora__blob--red"></span>
  <span class="aurora__blob aurora__blob--teal"></span>
  <div class="aurora__grid"></div>
</div>

<a class="skip-link" href="#main">Skip to content</a>

<header class="topbar" id="topbar">
  <div class="topbar__inner">
    <a class="brand" href="<?= e(bd_url()) ?>" aria-label="<?= e(APP_NAME) ?> home">
      <span class="brand__mark" aria-hidden="true">
        <svg viewBox="0 0 100 100" width="38" height="38">
          <rect width="100" height="100" rx="20" fill="#006a4e"/>
          <circle cx="45" cy="50" r="23" fill="#f42a41">
            <animate attributeName="r" values="23;25;23" dur="3.6s" repeatCount="indefinite"/>
          </circle>
        </svg>
      </span>
      <span class="brand__text">
        <strong>Ask<em>Bangladesh</em></strong>
        <small><?= e(APP_TAGLINE) ?></small>
      </span>
    </a>

    <form class="globalsearch" role="search" action="<?= e(bd_url('search')) ?>" method="get" autocomplete="off">
      <span class="globalsearch__icon" aria-hidden="true">🔍</span>
      <input
        type="search"
        id="global-search-input"
        name="q"
        class="globalsearch__input"
        placeholder="Search anything — districts, gold, sunset times, visas, rivers…"
        value="<?= e($_GET['q'] ?? '') ?>"
        aria-label="Search everything about Bangladesh"
        aria-expanded="false"
        aria-controls="global-search-results"
        role="combobox">
      <kbd class="globalsearch__kbd">/</kbd>
      <button type="submit" class="globalsearch__go">Search</button>
      <div class="globalsearch__panel" id="global-search-results" role="listbox" hidden></div>
    </form>

    <div class="topbar__actions">
      <button type="button" class="iconbtn" id="theme-toggle" aria-label="Switch colour theme" title="Switch theme">
        <span class="iconbtn__sun">☀️</span><span class="iconbtn__moon">🌙</span>
      </button>
      <div class="livechip" title="Bangladesh Standard Time (UTC+6)">
        <span class="livechip__dot"></span>
        <span id="topbar-clock" data-bd-clock>--:--:--</span>
        <small>BST</small>
      </div>
      <button type="button" class="iconbtn navtoggle" id="nav-toggle" aria-label="Open menu" aria-expanded="false">☰</button>
    </div>
  </div>

  <nav class="mainnav" id="mainnav" aria-label="Primary">
    <ul>
      <?php foreach ($nav as $item): ?>
        <li>
          <a href="<?= e(bd_url($item['path'])) ?>" class="mainnav__link<?= bd_active($item['page']) ?>">
            <span aria-hidden="true"><?= $item['icon'] ?></span><?= e($item['label']) ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </nav>
  <div class="scrollbar" id="scroll-progress" aria-hidden="true"></div>
</header>

<main id="main" class="main">
<?php if (!empty($seo['breadcrumbs']) && count($seo['breadcrumbs']) > 1): ?>
  <nav class="breadcrumbs" aria-label="Breadcrumb">
    <ol>
      <?php foreach ($seo['breadcrumbs'] as $i => $crumb): ?>
        <li>
          <?php if (!empty($crumb['url']) && $i < count($seo['breadcrumbs']) - 1): ?>
            <a href="<?= e($crumb['url']) ?>"><?= e($crumb['name']) ?></a>
          <?php else: ?>
            <span aria-current="page"><?= e($crumb['name']) ?></span>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ol>
  </nav>
<?php endif; ?>
