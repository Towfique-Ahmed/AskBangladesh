<?php
/** Prayer times for any Bangladeshi district, with a live next-prayer timer. */

$districts    = bd_districts();
$meta         = bd_prayer_meta();
$selectedName = trim((string) ($_GET['district'] ?? 'Dhaka'));
$school       = ($_GET['school'] ?? 'hanafi') === 'shafi' ? 'shafi' : 'hanafi';

$selected = null;
foreach ($districts as $district) {
    if (strcasecmp($district['name'], $selectedName) === 0) { $selected = $district; break; }
}
$selected ??= $districts[0];

$today = bd_prayer_times((float) $selected['lat'], (float) $selected['lon'], time(), $school);

$pageTitle       = 'Prayer times in ' . $selected['name'] . ', Bangladesh';
$pageDescription = 'Today’s Fajr, Dhuhr, Asr, Maghrib and Isha times for ' . $selected['name'] . ' and every district of Bangladesh, with a live countdown to the next prayer.';

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
<form class="card" data-reveal method="get" action="index.php" style="margin-bottom:1.6rem">
  <input type="hidden" name="p" value="prayer">
  <div class="converter__row" style="grid-template-columns:1fr 1fr auto">
    <div class="field">
      <label for="prayer-district">District</label>
      <select class="input" id="prayer-district" name="district">
        <?php foreach ($districts as $district): ?>
          <option value="<?= e($district['name']) ?>"<?= $district['name'] === $selected['name'] ? ' selected' : '' ?>>
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
            <td><a href="<?= e(bd_url('prayer', ['district' => $district['name'], 'school' => $school])) ?>"><strong><?= e($district['name']) ?></strong></a></td>
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

<div class="notice" data-reveal>
  <span>ℹ️</span>
  <p style="margin:0">
    These times are computed astronomically from each district’s coordinates. Local mosque and
    Islamic Foundation Bangladesh timetables may differ by a few minutes because of local
    conventions and precautionary margins — follow your local mosque for jamaat times.
  </p>
</div>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
