<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Prayer times for any Bangladeshi district, with a live next-prayer timer. */

$districts = bd_districts();
$meta      = bd_prayer_meta();
$school    = ($_GET['school'] ?? 'hanafi') === 'shafi' ? 'shafi' : 'hanafi';

/*
 * The district selector submits ?district=<slug> to /prayer. Redirect that to
 * the canonical /prayer/<slug> so only one URL per district is ever indexed.
 */
if (($route['slug'] ?? '') === '' && isset($_GET['district'])) {
    $wanted = bd_find_district(bd_slug((string) $_GET['district']));
    if ($wanted !== null) {
        $target = bd_prayer_url($wanted) . ($school === 'shafi' ? '?school=shafi' : '');
        header('Location: ' . $target, true, 301);
        exit;
    }
}

// /prayer/<district-slug> selects a district; bare /prayer defaults to Dhaka.
$slug     = $route['slug'] ?? '';
$selected = $slug !== '' ? bd_find_district($slug) : null;

if ($slug !== '' && $selected === null) {
    http_response_code(404);
    require APP_ROOT . '/pages/404.php';
    return;
}

$isDistrictPage = $selected !== null;
$selected ??= bd_find_district('dhaka') ?? $districts[0];

$today = bd_prayer_times((float) $selected['lat'], (float) $selected['lon'], time(), $school);

if ($isDistrictPage) {
    $seo = bd_seo([
        'title'       => $selected['name'] . ' Prayer Times Today — Namaz Schedule',
        'description' => 'Today’s namaz times in ' . $selected['name'] . ', Bangladesh: Fajr '
            . $today['Fajr'] . ', Dhuhr ' . $today['Dhuhr'] . ', Asr ' . $today['Asr']
            . ', Maghrib ' . $today['Maghrib'] . ', Isha ' . $today['Isha']
            . '. With sehri and iftar times.',
        'keywords'    => $selected['name'] . ' prayer time, ' . $selected['name'] . ' namaz time, '
            . $selected['name'] . ' sehri iftar time',
        'path'        => 'prayer/' . bd_slug($selected['name']),
        'breadcrumbs' => [
            ['name' => 'Home',         'url' => bd_url()],
            ['name' => 'Prayer Times', 'url' => bd_url('prayer')],
            ['name' => $selected['name']],
        ],
        'jsonld' => [bd_jsonld_faq([
            'What time is Fajr in ' . $selected['name'] . ' today?'
                => 'Fajr begins at ' . $today['Fajr'] . ' today in ' . $selected['name']
                   . ', Bangladesh Standard Time.',
            'What time is iftar in ' . $selected['name'] . ' today?'
                => 'Iftar begins at Maghrib, ' . $today['Maghrib'] . ', in ' . $selected['name'] . ' today.',
            'What time does sehri end in ' . $selected['name'] . '?'
                => 'Sehri ends at the start of Fajr, ' . $today['Fajr'] . ', in ' . $selected['name'] . '.',
        ])],
    ]);
} else {
    $seo = bd_seo(bd_page_seo('prayer') + [
        'breadcrumbs' => [
            ['name' => 'Home', 'url' => bd_url()],
            ['name' => 'Prayer Times'],
        ],
    ]);
}

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🕌 Namaz / Salah</span>
  <h1>Prayer times in <?= e($selected['name']) ?></h1>
  <p>
    Calculated for <?= e($selected['name']) ?> (<?= number_format((float) $selected['lat'], 3) ?>°N,
    <?= number_format((float) $selected['lon'], 3) ?>°E) using the University of Islamic Sciences,
    Karachi method — Fajr and Isha at 18° — with the
    <?= $school === 'hanafi' ? 'Hanafi' : 'Shafi' ?> Asr calculation.
  </p>
</div>

<!-- ------------------------------------------------------------- selector -->
<form class="card" data-reveal method="get" action="<?= e(bd_url('prayer')) ?>" style="margin-bottom:1.6rem">
  <div class="converter__row" style="grid-template-columns:1fr 1fr auto">
    <div class="field">
      <label for="prayer-district">District</label>
      <select class="input" id="prayer-district" name="district">
        <?php foreach ($districts as $district): ?>
          <option value="<?= e(bd_slug($district['name'])) ?>"<?= $district['name'] === $selected['name'] ? ' selected' : '' ?>>
            <?= e($district['name']) ?> — <?= e($district['bn']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label for="prayer-school">Asr calculation</label>
      <select class="input" id="prayer-school" name="school">
        <option value="hanafi"<?= $school === 'hanafi' ? ' selected' : '' ?>>Hanafi (shadow ×2)</option>
        <option value="shafi"<?= $school === 'shafi' ? ' selected' : '' ?>>Shafi / Maliki / Hanbali (shadow ×1)</option>
      </select>
    </div>
    <div class="field">
      <label>&nbsp;</label>
      <button type="submit" class="btn btn--primary">Show times</button>
    </div>
  </div>
</form>

<!-- ------------------------------------------------------------ countdown -->
<div class="result" data-reveal style="margin-bottom:1.6rem">
  <div style="font-size:.78rem;letter-spacing:.14em;text-transform:uppercase;color:var(--text-mute)">
    Next prayer · <span id="next-prayer-name">—</span>
  </div>
  <div class="result__value" id="next-prayer-timer">--:--:--</div>
  <div class="result__meta">
    <?= e(date('l, j F Y')) ?> · <?= e($selected['name']) ?>, Bangladesh · BST (UTC+06:00)
  </div>
</div>

<!-- ---------------------------------------------------------------- times -->
<div class="grid grid--2" id="prayer-list">
  <?php foreach ($today as $name => $time):
      $info = $meta[$name] ?? ['bn' => '', 'emoji' => '🕌', 'note' => ''];
  ?>
    <div class="prayercard" data-prayer="<?= e($name) ?>" data-time="<?= e($time) ?>" data-reveal>
      <?php if ($name === 'Sunrise'): ?>
        <span class="prayercard__tag">not a prayer</span>
      <?php endif; ?>
      <span class="prayercard__emoji" aria-hidden="true"><?= $info['emoji'] ?></span>
      <span>
        <span class="prayercard__name"><?= e($name) ?></span>
        <span class="prayercard__bn"><?= e($info['bn']) ?></span>
        <div style="font-size:.76rem;color:var(--text-mute)"><?= e($info['note']) ?></div>
      </span>
      <span class="prayercard__time"><?= e($time) ?></span>
    </div>
  <?php endforeach; ?>
</div>

<!-- ------------------------------------------------------- major cities -->
<section class="section">
  <div class="section__head">
    <h2>Divisional cities today</h2>
    <p>Times shift by a few minutes east to west across the country.</p>
  </div>

  <div class="tablewrap" data-reveal>
    <table>
      <thead>
        <tr><th>City</th><th>Fajr</th><th>Sunrise</th><th>Dhuhr</th><th>Asr</th><th>Maghrib</th><th>Isha</th></tr>
      </thead>
      <tbody>
        <?php foreach (array_keys(bd_divisions()) as $divisionName):
            foreach ($districts as $district) {
                if ($district['name'] !== $divisionName) { continue; }
                $times = bd_prayer_times((float) $district['lat'], (float) $district['lon'], time(), $school);
        ?>
          <tr>
            <td><a href="<?= e(bd_prayer_url($district) . ($school === 'shafi' ? '?school=shafi' : '')) ?>"><strong><?= e($district['name']) ?></strong></a></td>
            <?php foreach (['Fajr', 'Sunrise', 'Dhuhr', 'Asr', 'Maghrib', 'Isha'] as $key): ?>
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

<!-- ----------------------------------------------------------- ramadan -->
<section class="section">
  <div class="section__head"><h2>Sehri &amp; Iftar</h2></div>
  <div class="grid grid--2">
    <div class="card" data-reveal>
      <h3>🌙 Sehri ends</h3>
      <div style="font:700 2.2rem/1.2 var(--mono);color:var(--green-300)"><?= e($today['Fajr']) ?></div>
      <p>Sehri (suhoor) ends at the start of Fajr. Many families stop eating a few minutes earlier
      as a precaution — that margin is called ihtiyat.</p>
    </div>
    <div class="card" data-reveal="80">
      <h3>🌇 Iftar begins</h3>
      <div style="font:700 2.2rem/1.2 var(--mono);color:var(--gold-500)"><?= e($today['Maghrib']) ?></div>
      <p>The fast opens at Maghrib, just after sunset — traditionally with a date, then chhola,
      piyaju, beguni and a glass of lemon sharbat.</p>
    </div>
  </div>
</section>

<!-- ------------------------------------------------- every district page -->
<section class="section">
  <div class="section__head">
    <h2>Prayer times by district</h2>
    <p>All 64 districts, each with its own daily schedule.</p>
  </div>
  <div class="chips">
    <?php foreach ($districts as $district): ?>
      <a class="chip<?= $district['name'] === $selected['name'] ? ' is-active' : '' ?>"
         href="<?= e(bd_prayer_url($district)) ?>"><?= e($district['name']) ?></a>
    <?php endforeach; ?>
  </div>
</section>

<div class="notice" data-reveal>
  <span>ℹ️</span>
  <p style="margin:0">
    These times are computed astronomically from each district’s coordinates. Local mosque and
    Islamic Foundation Bangladesh timetables may differ by a few minutes because of local
    conventions and precautionary margins — follow your local mosque for jamaat times.
  </p>
</div>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
