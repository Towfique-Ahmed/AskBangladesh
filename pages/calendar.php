<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Bangla and English calendar side by side. */

$months_en = [
    ['name' => 'January',   'days' => 31],
    ['name' => 'February',  'days' => 28],
    ['name' => 'March',     'days' => 31],
    ['name' => 'April',     'days' => 30],
    ['name' => 'May',       'days' => 31],
    ['name' => 'June',      'days' => 30],
    ['name' => 'July',      'days' => 31],
    ['name' => 'August',    'days' => 31],
    ['name' => 'September', 'days' => 30],
    ['name' => 'October',   'days' => 31],
    ['name' => 'November',  'days' => 30],
    ['name' => 'December',  'days' => 31],
];

$months_bn = [
    ['name' => 'Boishakh',    'bn' => 'বৈশাখ',    'days' => 31, 'season' => 'Grishmo (Summer)',     'start' => '14 Apr'],
    ['name' => 'Jyoishtho',   'bn' => 'জ্যৈষ্ঠ',   'days' => 31, 'season' => 'Grishmo (Summer)',     'start' => '15 May'],
    ['name' => 'Asharh',      'bn' => 'আষাঢ়',     'days' => 31, 'season' => 'Borsha (Monsoon)',     'start' => '15 Jun'],
    ['name' => 'Shrabon',     'bn' => 'শ্রাবণ',    'days' => 31, 'season' => 'Borsha (Monsoon)',     'start' => '16 Jul'],
    ['name' => 'Bhadro',      'bn' => 'ভাদ্র',     'days' => 31, 'season' => 'Shorot (Autumn)',      'start' => '16 Aug'],
    ['name' => 'Ashwin',      'bn' => 'আশ্বিন',    'days' => 30, 'season' => 'Shorot (Autumn)',      'start' => '16 Sep'],
    ['name' => 'Kartik',      'bn' => 'কার্তিক',   'days' => 30, 'season' => 'Hemonto (Late Autumn)','start' => '16 Oct'],
    ['name' => 'Ogrohayon',   'bn' => 'অগ্রহায়ণ', 'days' => 30, 'season' => 'Hemonto (Late Autumn)','start' => '15 Nov'],
    ['name' => 'Poush',       'bn' => 'পৌষ',       'days' => 30, 'season' => 'Sheet (Winter)',       'start' => '15 Dec'],
    ['name' => 'Magh',        'bn' => 'মাঘ',       'days' => 30, 'season' => 'Sheet (Winter)',       'start' => '14 Jan'],
    ['name' => 'Falgun',      'bn' => 'ফাল্গুন',   'days' => 30, 'season' => 'Boshonto (Spring)',    'start' => '13 Feb'],
    ['name' => 'Choitro',     'bn' => 'চৈত্র',     'days' => 30, 'season' => 'Boshonto (Spring)',    'start' => '15 Mar'],
];

$holidays = [
    ['date' => '21 Feb', 'name' => 'International Mother Language Day', 'bn' => 'আন্তর্জাতিক মাতৃভাষা দিবস'],
    ['date' => '26 Mar', 'name' => 'Independence Day',                 'bn' => 'স্বাধীনতা দিবস'],
    ['date' => '14 Apr', 'name' => 'Pohela Boishakh (Bengali New Year)','bn' => 'পহেলা বৈশাখ'],
    ['date' => '1 May',  'name' => 'May Day',                          'bn' => 'মে দিবস'],
    ['date' => '15 Aug', 'name' => 'National Mourning Day',            'bn' => 'জাতীয় শোক দিবস'],
    ['date' => '16 Dec', 'name' => 'Victory Day',                      'bn' => 'বিজয় দিবস'],
    ['date' => '25 Dec', 'name' => 'Christmas Day',                    'bn' => 'বড়দিন'],
];

$now = new DateTimeImmutable('now', new DateTimeZone(BD_TIMEZONE));
$currentMonth = (int) $now->format('n');
$currentDay   = (int) $now->format('j');
$currentYear  = (int) $now->format('Y');

$seo = bd_seo(bd_page_seo('calendar') + [
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Calendar'],
    ],
    'jsonld' => [
        bd_jsonld_web_app(
            'Bangladesh Calendar — Bangla & English',
            'Bangla (Bengali) and English Gregorian calendar side by side with the six seasons and national holidays of Bangladesh.',
            'calendar'
        ),
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">📅 Calendar</span>
  <h1>Bangla &amp; English Calendar</h1>
  <p>
    Bangladesh follows two calendars: the Gregorian (English) calendar for official business and the
    Bangla calendar (বঙ্গাব্দ) for cultural and agricultural life. The Bangla year starts on
    <strong>14 April</strong> (Pohela Boishakh) and divides into six seasons of two months each.
  </p>
</div>

<div class="statgrid" data-reveal>
  <div class="stat"><div class="stat__value"><?= e($now->format('l')) ?></div><div class="stat__label">Today</div></div>
  <div class="stat"><div class="stat__value"><?= e($now->format('j M Y')) ?></div><div class="stat__label">Gregorian date</div></div>
  <div class="stat"><div class="stat__value">6</div><div class="stat__label">Seasons</div></div>
  <div class="stat"><div class="stat__value">12</div><div class="stat__label">Bangla months</div></div>
</div>

<!-- -------------------------------------------------------- English calendar -->
<section class="section">
  <div class="section__head">
    <h2>English (Gregorian) Calendar <?= $currentYear ?></h2>
    <p>The standard calendar used for government, business and international affairs.</p>
  </div>

  <div class="grid grid--3">
    <?php foreach ($months_en as $i => $month):
        $isLeap = ($currentYear % 4 === 0 && ($currentYear % 100 !== 0 || $currentYear % 400 === 0));
        $days = ($i === 1 && $isLeap) ? 29 : $month['days'];
        $isCurrent = ($i + 1 === $currentMonth);
    ?>
      <div class="card<?= $isCurrent ? '' : '' ?>" data-reveal="<?= ($i % 6) * 40 ?>"
           style="<?= $isCurrent ? 'border-color:var(--green-300)' : '' ?>">
        <div style="display:flex;justify-content:space-between;align-items:baseline">
          <h3 style="margin:0"><?= e($month['name']) ?></h3>
          <span class="badge<?= $isCurrent ? ' badge--gold' : '' ?>"><?= $days ?> days</span>
        </div>
        <?php if ($isCurrent): ?>
          <div style="color:var(--green-300);font-size:.85rem;margin-top:.3rem">Current month — Day <?= $currentDay ?></div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- --------------------------------------------------------- Bangla calendar -->
<section class="section">
  <div class="section__head">
    <h2>Bangla Calendar (বঙ্গাব্দ)</h2>
    <p>Twelve months, six seasons — each pair of months is linked to a season of the Bengali year.</p>
  </div>

  <div class="grid grid--2">
    <?php foreach ($months_bn as $i => $month): ?>
      <article class="card" data-reveal="<?= ($i % 6) * 45 ?>">
        <div style="display:flex;justify-content:space-between;align-items:baseline;gap:1rem">
          <h3 style="margin:0"><?= e($month['name']) ?></h3>
          <span class="badge badge--gold"><?= $month['days'] ?> days</span>
        </div>
        <div style="color:var(--green-300);font-size:1.05rem;margin:.2rem 0"><?= e($month['bn']) ?></div>
        <div style="display:flex;gap:.4rem;flex-wrap:wrap;margin-top:.5rem">
          <span class="badge"><?= e($month['season']) ?></span>
          <span class="badge badge--red">Starts <?= e($month['start']) ?></span>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<!-- ----------------------------------------------------------- six seasons -->
<section class="section">
  <div class="section__head">
    <h2>The six seasons</h2>
    <p>Where most of the world counts four seasons, Bangladesh recognises six.</p>
  </div>

  <div class="grid grid--3">
    <div class="card" data-reveal>
      <h3>☀️ Grishmo (গ্রীষ্ম)</h3>
      <p><strong>Summer</strong> — Boishakh &amp; Jyoishtho (Apr–Jun). The hottest months, with temperatures above 35 °C.</p>
    </div>
    <div class="card" data-reveal="50">
      <h3>🌧️ Borsha (বর্ষা)</h3>
      <p><strong>Monsoon</strong> — Asharh &amp; Shrabon (Jun–Aug). Heavy rains, lush green landscapes, swelling rivers.</p>
    </div>
    <div class="card" data-reveal="100">
      <h3>🍂 Shorot (শরৎ)</h3>
      <p><strong>Autumn</strong> — Bhadro &amp; Ashwin (Aug–Oct). Clear skies after the monsoon, kash phool blooms.</p>
    </div>
    <div class="card" data-reveal="150">
      <h3>🌾 Hemonto (হেমন্ত)</h3>
      <p><strong>Late Autumn</strong> — Kartik &amp; Ogrohayon (Oct–Dec). Harvest season, Nabanna festival.</p>
    </div>
    <div class="card" data-reveal="200">
      <h3>❄️ Sheet (শীত)</h3>
      <p><strong>Winter</strong> — Poush &amp; Magh (Dec–Feb). Cool, dry, foggy mornings — the best travel season.</p>
    </div>
    <div class="card" data-reveal="250">
      <h3>🌸 Boshonto (বসন্ত)</h3>
      <p><strong>Spring</strong> — Falgun &amp; Choitro (Feb–Apr). Flowers bloom, Pohela Falgun is celebrated in red and yellow.</p>
    </div>
  </div>
</section>

<!-- -------------------------------------------------------- national holidays -->
<section class="section">
  <div class="section__head">
    <h2>National holidays</h2>
    <p>Fixed-date public holidays observed every year.</p>
  </div>

  <div class="tablewrap" data-reveal>
    <table>
      <thead><tr><th>Date</th><th>Holiday</th><th>Bangla</th></tr></thead>
      <tbody>
        <?php foreach ($holidays as $h): ?>
          <tr>
            <td><span class="badge badge--red"><?= e($h['date']) ?></span></td>
            <td><strong><?= e($h['name']) ?></strong></td>
            <td style="color:var(--green-300)"><?= e($h['bn']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<div class="notice" data-reveal>
  <span>📅</span>
  <p style="margin:0">
    Islamic holidays (Eid ul-Fitr, Eid ul-Adha, Shab-e-Barat) and Hindu holidays (Durga Puja)
    follow the lunar calendar, so their Gregorian dates shift each year. Check the government
    gazette for the current year's exact dates.
  </p>
</div>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
