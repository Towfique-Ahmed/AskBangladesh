<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Continent, delta, mountains, rivers, climate and extreme points. */

$geo       = bd_places('geography');
$mountains = bd_places('mountains');
$rivers    = bd_places('rivers');

$seo = bd_seo(bd_page_seo('geography') + [
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Geography'],
    ],
]);

require APP_ROOT . '/includes/layout/header.php';

$tallest = max(array_column($mountains, 'height'));
$longest = max(array_column($rivers, 'length'));
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">⛰️ Land &amp; landscape</span>
  <h1>The land of rivers</h1>
  <p>
    Bangladesh sits in <strong><?= e($geo['continent']) ?></strong>, in <?= e($geo['subregion']) ?>,
    on the <?= e($geo['delta']) ?> It is mostly flat and green, until the south-east rises into the
    Chittagong Hill Tracts.
  </p>
</div>

<div class="statgrid" data-reveal>
  <div class="stat"><div class="stat__value" data-count-to="<?= $geo['area_km2'] ?>">0</div><div class="stat__label">Total area (km²)</div></div>
  <div class="stat"><div class="stat__value" data-count-to="<?= $geo['coastline_km'] ?>">0</div><div class="stat__label">Coastline (km)</div></div>
  <div class="stat"><div class="stat__value" data-count-to="<?= $geo['land_border_km'] ?>">0</div><div class="stat__label">Land border (km)</div></div>
  <div class="stat"><div class="stat__value" data-count-to="<?= $geo['rivers_count'] ?>">0</div><div class="stat__label">Rivers</div></div>
  <div class="stat"><div class="stat__value" data-count-to="<?= $geo['highest_point']['height_m'] ?>">0</div><div class="stat__label">Highest point (m)</div></div>
  <div class="stat"><div class="stat__value" data-count-to="<?= $geo['mean_elevation_m'] ?>">0</div><div class="stat__label">Mean elevation (m)</div></div>
</div>

<!-- ------------------------------------------------------------ location -->
<section class="section">
  <div class="section__head"><h2>Where exactly is it?</h2></div>
  <div class="grid grid--2">
    <div class="card" data-reveal>
      <h3>🌏 Position on Earth</h3>
      <div class="mapinfo__rows">
        <div class="mapinfo__row"><span>Continent</span><span><?= e($geo['continent']) ?></span></div>
        <div class="mapinfo__row"><span>Sub-region</span><span><?= e($geo['subregion']) ?></span></div>
        <div class="mapinfo__row"><span>Tectonic plate</span><span><?= e($geo['tectonic_plate']) ?></span></div>
        <div class="mapinfo__row"><span>Sea</span><span><?= e($geo['sea']) ?></span></div>
        <div class="mapinfo__row"><span>Time zone</span><span><?= e($geo['timezone']) ?></span></div>
        <div class="mapinfo__row"><span>Land / water</span><span><?= bd_num($geo['land_km2']) ?> / <?= bd_num($geo['water_km2']) ?> km²</span></div>
      </div>
    </div>

    <div class="card" data-reveal="80">
      <h3>🧭 Neighbours &amp; borders</h3>
      <?php foreach ($geo['borders'] as $country => $km): ?>
        <div style="margin-bottom:.9rem">
          <div style="display:flex;justify-content:space-between;font-size:.9rem;margin-bottom:.3rem">
            <strong><?= e($country) ?></strong><span><?= bd_num($km) ?> km</span>
          </div>
          <div class="bar"><div class="bar__fill" data-width="<?= round($km / $geo['land_border_km'] * 100) ?>"
               style="background:linear-gradient(90deg,var(--green-300),var(--teal-500))"></div></div>
        </div>
      <?php endforeach; ?>
      <p style="margin-top:1rem;font-size:.85rem;color:var(--text-mute)">
        Bangladesh is bordered by India on the west, north and east, by Myanmar in the south-east,
        and by the Bay of Bengal to the south.
      </p>
    </div>
  </div>
</section>

<!-- ------------------------------------------------------------ extremes -->
<section class="section">
  <div class="section__head">
    <h2>The four corners</h2>
    <p>The furthest points in each direction.</p>
  </div>
  <div class="grid grid--4">
    <?php $i = 0; foreach ($geo['extremes'] as $direction => $point): ?>
      <div class="card" data-reveal="<?= $i * 60 ?>">
        <span class="badge"><?= e($direction) ?>ernmost</span>
        <h3 style="margin-top:.6rem"><?= e($point['name']) ?></h3>
        <p style="font-family:var(--mono);font-size:.82rem">
          <?= number_format($point['lat'], 4) ?>°N, <?= number_format($point['lon'], 4) ?>°E
        </p>
      </div>
    <?php $i++; endforeach; ?>
  </div>
</section>

<!-- ----------------------------------------------------------- mountains -->
<section class="section">
  <div class="section__head">
    <h2>Mountains &amp; peaks</h2>
    <p>Almost all of them stand in the Chittagong Hill Tracts.</p>
  </div>

  <div class="grid grid--2">
    <?php foreach ($mountains as $i => $m): ?>
      <article class="card" data-reveal="<?= ($i % 6) * 45 ?>">
        <div style="display:flex;justify-content:space-between;align-items:baseline;gap:1rem">
          <h3 style="margin:0">⛰️ <?= e($m['name']) ?></h3>
          <strong style="font-family:var(--mono);color:var(--green-300);font-size:1.15rem"><?= bd_num($m['height']) ?> m</strong>
        </div>
        <?php if ($m['alt'] !== ''): ?>
          <div style="font-size:.82rem;color:var(--text-mute)">also called <?= e($m['alt']) ?></div>
        <?php endif; ?>
        <div class="bar" style="margin:.7rem 0">
          <div class="bar__fill" data-width="<?= round($m['height'] / $tallest * 100) ?>"
               style="background:linear-gradient(90deg,var(--gold-500),var(--red-500))"></div>
        </div>
        <p><?= e($m['note']) ?></p>
        <div style="margin-top:.6rem"><span class="badge"><?= e($m['district']) ?></span>
          <span class="badge badge--gold"><?= e($m['range']) ?></span></div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<!-- -------------------------------------------------------------- rivers -->
<section class="section">
  <div class="section__head">
    <h2>Major rivers</h2>
    <p>About <?= bd_num($geo['rivers_count']) ?> rivers thread through the delta.</p>
  </div>

  <div class="tablewrap" data-reveal>
    <table>
      <thead><tr><th>River</th><th>Bangla</th><th class="num">Length in BD (km)</th><th>Why it matters</th></tr></thead>
      <tbody>
        <?php foreach ($rivers as $r): ?>
          <tr>
            <td><strong>🌊 <?= e($r['name']) ?></strong></td>
            <td style="color:var(--green-300)"><?= e($r['bn']) ?></td>
            <td class="num"><?= bd_num($r['length']) ?></td>
            <td style="color:var(--text-dim)"><?= e($r['note']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<!-- ------------------------------------------------------------- climate -->
<section class="section">
  <div class="section__head">
    <h2>Six seasons, not four</h2>
    <p>The Bengali calendar divides the year into six distinct seasons.</p>
  </div>

  <div class="grid grid--3">
    <?php foreach ($geo['climate'] as $i => $season): ?>
      <div class="card" data-reveal="<?= $i * 50 ?>">
        <h3><?= e($season['season']) ?></h3>
        <div style="color:var(--green-300);font-size:.95rem"><?= e($season['bn']) ?></div>
        <div style="display:flex;gap:.4rem;flex-wrap:wrap;margin:.7rem 0">
          <span class="badge"><?= e($season['months']) ?></span>
          <span class="badge badge--red"><?= e($season['temp']) ?></span>
        </div>
        <p><?= e($season['note']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
