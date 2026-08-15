<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** @var string $pageTitle */
/** @var string $pageDescription */
$pageTitle       = $pageTitle       ?? APP_NAME;
$pageDescription = $pageDescription ?? APP_TAGLINE;
$current         = bd_current_page();

$nav = [
    ['p' => 'home',       'label' => 'Home',       'icon' => '🏠'],
    ['p' => 'map',        'label' => 'Map',        'icon' => '🗺️'],
    ['p' => 'districts',  'label' => 'Districts',  'icon' => '📍'],
    ['p' => 'geography',  'label' => 'Geography',  'icon' => '⛰️'],
    ['p' => 'travel',     'label' => 'Travel',     'icon' => '🧳'],
    ['p' => 'transport',  'label' => 'Transport',  'icon' => '🛣️'],
    ['p' => 'time',       'label' => 'Time',       'icon' => '🕰️'],
    ['p' => 'currency',   'label' => 'Currency',   'icon' => '💱'],
    ['p' => 'gold',       'label' => 'Gold',       'icon' => '🥇'],
    ['p' => 'prayer',     'label' => 'Prayer',     'icon' => '🕌'],
    ['p' => 'religion',   'label' => 'Religion',   'icon' => '🕊️'],
    ['p' => 'government', 'label' => 'Government', 'icon' => '🏛️'],
    ['p' => 'about',      'label' => 'About BD',   'icon' => '🇧🇩'],
];
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?> · <?= e(APP_NAME) ?></title>
<meta name="description" content="<?= e($pageDescription) ?>">
<meta name="theme-color" content="#00a651">
<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='18' fill='%23006a4e'/><circle cx='45' cy='50' r='24' fill='%23f42a41'/></svg>">
<link rel="stylesheet" href="assets/css/app.css?v=<?= e(APP_VERSION) ?>">
</head>
<body data-page="<?= e($current) ?>">

<div class="aurora" aria-hidden="true">
  <span class="aurora__blob aurora__blob--green"></span>
  <span class="aurora__blob aurora__blob--red"></span>
  <span class="aurora__blob aurora__blob--teal"></span>
  <div class="aurora__grid"></div>
</div>

<a class="skip-link" href="#main">Skip to content</a>

<header class="topbar" id="topbar">
  <div class="topbar__inner">
    <a class="brand" href="<?= e(bd_url('home')) ?>" aria-label="<?= e(APP_NAME) ?> home">
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

    <form class="globalsearch" role="search" action="index.php" method="get" autocomplete="off">
      <input type="hidden" name="p" value="search">
      <span class="globalsearch__icon" aria-hidden="true">🔍</span>
      <input
        type="search"
        id="global-search-input"
        name="q"
        class="globalsearch__input"
        placeholder="Search anything — districts, gold, prayer times, visas, rivers…"
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
          <a href="<?= e(bd_url($item['p'])) ?>" class="mainnav__link<?= bd_active($item['p']) ?>">
            <span aria-hidden="true"><?= $item['icon'] ?></span><?= e($item['label']) ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </nav>
  <div class="scrollbar" id="scroll-progress" aria-hidden="true"></div>
</header>

<main id="main" class="main">
