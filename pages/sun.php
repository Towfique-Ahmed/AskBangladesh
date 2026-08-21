<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Sunrise and sunset times for any Bangladeshi district. */

$districts = bd_districts();
$meta      = bd_sun_meta();

/*
 * The district selector submits ?district=<slug>. Redirect that to the
 * canonical /sunrise-sunset/<slug> so only one URL per district is indexed.
 */
if (($route['slug'] ?? '') === '' && isset($_GET['district'])) {
    $wanted = bd_find_district(bd_slug((string) $_GET['district']));
    if ($wanted !== null) {
        header('Location: ' . bd_sun_url($wanted), true, 301);
        exit;
    }
}

$slug     = $route['slug'] ?? '';
$selected = $slug !== '' ? bd_find_district($slug) : null;

if ($slug !== '' && $selected === null) {
    http_response_code(404);
    require APP_ROOT . '/pages/404.php';
    return;
}

$isDistrictPage = $selected !== null;
$selected ??= bd_find_district('dhaka') ?? $districts[0];

$today    = bd_sun_times((float) $selected['lat'], (float) $selected['lon']);
$tomorrow = bd_sun_times((float) $selected['lat'], (float) $selected['lon'], strtotime('+1 day'));

// How the day is changing, which is the thing people actually notice.
$toMin = static function (string $hhmm): ?int {
    if (!preg_match('/^(\d{2}):(\d{2})$/', $hhmm, $m)) { return null; }
    return ((int) $m[1]) * 60 + (int) $m[2];
};
$todayLen    = $toMin($today['Day length']);
$tomorrowLen = $toMin($tomorrow['Day length']);
$delta       = ($todayLen !== null && $tomorrowLen !== null) ? $tomorrowLen - $todayLen : null;
$deltaText   = $delta === null ? ''
    : ($delta === 0 ? 'the same length as today'
        : abs($delta) . ' ' . (abs($delta) === 1 ? 'minute' : 'minutes')
          . ' ' . ($delta > 0 ? 'longer' : 'shorter') . ' than today');

// One source for both the FAQPage markup and the visible block below it.
$faqs = [
    'What time is sunrise in ' . $selected['name'] . ' today?'
        => 'The sun rises at ' . $today['Sunrise'] . ' today in ' . $selected['name']
           . ', Bangladesh Standard Time.',
    'What time is sunset in ' . $selected['name'] . ' today?'
        => 'The sun sets at ' . $today['Sunset'] . ' today in ' . $selected['name'] . '.',
    'How long is the day in ' . $selected['name'] . ' today?'
        => 'Today runs to ' . $today['Day length'] . ' of daylight between sunrise and sunset'
           . ($deltaText !== '' ? '. Tomorrow will be ' . $deltaText : '') . '.',
    'When is golden hour in ' . $selected['name'] . '?'
        => 'The morning golden hour runs from sunrise at ' . $today['Sunrise'] . ' until '
           . $today['Golden hour ends'] . ', and the evening golden hour from '
           . $today['Golden hour begins'] . ' until sunset at ' . $today['Sunset'] . '.',
];

if ($isDistrictPage) {
    $seo = bd_seo([
        'title'       => $selected['name'] . ' Sunrise & Sunset Times Today',
        'description' => 'Sunrise and sunset in ' . $selected['name'] . ', Bangladesh today: sunrise '
            . $today['Sunrise'] . ', sunset ' . $today['Sunset'] . ', with '
            . $today['Day length'] . ' of daylight, solar noon and golden hour times.',
        'keywords'    => $selected['name'] . ' sunrise time, ' . $selected['name'] . ' sunset time, '
            . $selected['name'] . ' day length, golden hour ' . $selected['name'],
        'path'        => 'sunrise-sunset/' . bd_slug($selected['name']),
        'breadcrumbs' => [
            ['name' => 'Home',              'url' => bd_url()],
            ['name' => 'Sunrise & Sunset',  'url' => bd_url('sunrise-sunset')],
            ['name' => $selected['name']],
        ],
        'jsonld' => [bd_jsonld_faq($faqs)],
    ]);
} else {
    $seo = bd_seo(bd_page_seo('sun') + [
        'breadcrumbs' => [
            ['name' => 'Home', 'url' => bd_url()],
            ['name' => 'Sunrise & Sunset'],
        ],
        'jsonld' => [bd_jsonld_faq($faqs)],
    ]);
}

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🌅 Sunrise &amp; sunset</span>
  <h1>Sunrise and sunset in <?= e($selected['name']) ?></h1>
  <p>
    Calculated for <?= e($selected['name']) ?> (<?= number_format((float) $selected['lat'], 3) ?>°N,
    <?= number_format((float) $selected['lon'], 3) ?>°E) from the sun's position, in Bangladesh
    Standard Time. Bangladesh keeps a single time zone, so times shift by up to half an hour
    between the eastern hills and the western border.
  </p>
</div>

<!-- --------------------------------------------------------------- selector -->
<form class="card" data-reveal method="get" action="<?= e(bd_url('sunrise-sunset')) ?>" style="margin-bottom:1.6rem">
  <div class="converter__row" style="grid-template-columns:1fr auto">
    <div class="field">
      <label for="sun-district">District</label>
      <select class="input" id="sun-district" name="district">
        <?php foreach ($districts as $district): ?>
          <option value="<?= e(bd_slug($district['name'])) ?>"<?= $district['name'] === $selected['name'] ? ' selected' : '' ?>>
            <?= e($district['name']) ?> — <?= e($district['bn']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label>&nbsp;</label>
      <button type="submit" class="btn btn--primary">Show times</button>
    </div>
  </div>
</form>

<!-- ------------------------------------------------------------- headline -->
<div class="grid grid--2" style="margin-bottom:1.6rem">
  <div class="result" data-reveal>
    <div style="font-size:.78rem;letter-spacing:.14em;text-transform:uppercase;color:var(--text-mute)">
      🌅 Sunrise
    </div>
    <div class="result__value"><?= e($today['Sunrise']) ?></div>
    <div class="result__meta"><?= e(date('l, j F Y')) ?> · <?= e($selected['name']) ?></div>
  </div>
  <div class="result" data-reveal="80"
       style="background:linear-gradient(135deg, color-mix(in srgb, var(--gold-500) 18%, transparent), color-mix(in srgb, var(--red-500) 10%, transparent));border-color:color-mix(in srgb, var(--gold-500) 32%, transparent)">
    <div style="font-size:.78rem;letter-spacing:.14em;text-transform:uppercase;color:var(--text-mute)">
      🌇 Sunset
    </div>
    <div class="result__value" style="color:var(--gold-500)"><?= e($today['Sunset']) ?></div>
    <div class="result__meta"><?= e($today['Day length']) ?> of daylight today</div>
  </div>
</div>

<!-- ------------------------------------------------------------ full table -->
<div class="grid grid--2" id="sun-list">
  <?php foreach ($today as $name => $time):
      $info = $meta[$name] ?? ['bn' => '', 'emoji' => '☀️', 'note' => ''];
  ?>
    <div class="suncard" data-reveal>
      <span class="suncard__emoji" aria-hidden="true"><?= $info['emoji'] ?></span>
      <span>
        <span class="suncard__name"><?= e($name) ?></span>
        <span class="suncard__bn"><?= e($info['bn']) ?></span>
        <div style="font-size:.76rem;color:var(--text-mute)"><?= e($info['note']) ?></div>
      </span>
      <span class="suncard__time"><?= e($time) ?></span>
    </div>
  <?php endforeach; ?>
</div>

<!-- --------------------------------------------------------------- tomorrow -->
<section class="section">
  <div class="section__head">
    <h2>Tomorrow</h2>
    <p><?= e((new DateTimeImmutable('+1 day'))->format('l, j F Y')) ?></p>
  </div>
  <div class="grid grid--3">
    <div class="card" data-reveal>
      <h3>🌅 Sunrise</h3>
      <div style="font:700 1.8rem/1.2 var(--mono);color:var(--green-300)"><?= e($tomorrow['Sunrise']) ?></div>
    </div>
    <div class="card" data-reveal="60">
      <h3>🌇 Sunset</h3>
      <div style="font:700 1.8rem/1.2 var(--mono);color:var(--gold-500)"><?= e($tomorrow['Sunset']) ?></div>
    </div>
    <div class="card" data-reveal="120">
      <h3>⏳ Daylight</h3>
      <div style="font:700 1.8rem/1.2 var(--mono);color:var(--teal-500)"><?= e($tomorrow['Day length']) ?></div>
      <?php if ($deltaText !== ''): ?>
        <p style="margin-top:.4rem"><?= e(ucfirst($deltaText)) ?>.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- --------------------------------------------------------- divisional -->
<section class="section">
  <div class="section__head">
    <h2>Divisional cities today</h2>
    <p>The sun rises in Sylhet before it reaches the western border.</p>
  </div>

  <div class="tablewrap" data-reveal>
    <table>
      <thead>
        <tr><th>City</th><th>First light</th><th>Sunrise</th><th>Solar noon</th><th>Sunset</th><th>Last light</th><th>Daylight</th></tr>
      </thead>
      <tbody>
        <?php foreach (array_keys(bd_divisions()) as $divisionName):
            foreach ($districts as $district) {
                if ($district['name'] !== $divisionName) { continue; }
                $times = bd_sun_times((float) $district['lat'], (float) $district['lon']);
        ?>
          <tr>
            <td><a href="<?= e(bd_sun_url($district)) ?>"><strong><?= e($district['name']) ?></strong></a></td>
            <?php foreach (['First light', 'Sunrise', 'Solar noon', 'Sunset', 'Last light', 'Day length'] as $key): ?>
              <td class="num"><?= e($times[$key]) ?></td>
            <?php endforeach; ?>
          </tr>
        <?php     break;
            }
        endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<!-- ------------------------------------------------- every district page -->
<section class="section">
  <div class="section__head">
    <h2>Sunrise and sunset by district</h2>
    <p>All 64 districts, each with its own daily figures.</p>
  </div>
  <div class="chips">
    <?php foreach ($districts as $district): ?>
      <a class="chip<?= $district['name'] === $selected['name'] ? ' is-active' : '' ?>"
         href="<?= e(bd_sun_url($district)) ?>"><?= e($district['name']) ?></a>
    <?php endforeach; ?>
  </div>
</section>

<?= bd_render_faq($faqs, 'Sunrise and sunset questions for ' . $selected['name']) ?>

<div class="notice" data-reveal>
  <span>ℹ️</span>
  <p style="margin:0">
    Times are computed from each district's coordinates and account for atmospheric refraction
    and the width of the solar disc. Local terrain — a ridge in the Chittagong Hill Tracts, for
    instance — can hide the sun for several minutes either side of these figures.
  </p>
</div>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
