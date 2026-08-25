<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Roads, transport modes, fares, airports, ports and megaprojects. */

$roads     = bd_places('roads');
$transport = bd_places('transport');
$airports  = bd_places('airports');
$ports     = bd_places('ports');
$projects  = bd_places('megaprojects');

$seo = bd_seo(bd_page_seo('transport') + [
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Transport'],
    ],
    'jsonld' => [
        bd_jsonld_item_list(
            'National Highways of Bangladesh',
            $roads,
            static fn (array $r): array => [
                '@type'       => 'Thing',
                'name'        => $r['code'] . ' — ' . $r['name'],
                'description' => $r['note'],
            ]
        ),
        bd_jsonld_item_list(
            'Airports in Bangladesh',
            $airports,
            static fn (array $a): array => [
                '@type' => 'Airport',
                'name'  => $a['name'],
                'iataCode' => $a['code'],
                'address'  => ['@type' => 'PostalAddress', 'addressCountry' => 'BD', 'addressLocality' => $a['city']],
                'geo'      => ['@type' => 'GeoCoordinates', 'latitude' => $a['lat'], 'longitude' => $a['lon']],
            ]
        ),
    ],
]);

require APP_ROOT . '/includes/layout/header.php';

$longestRoad = max(array_column($roads, 'length'));
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🛣️ Getting around</span>
  <h1>Roads, rails and rivers</h1>
  <p>
    Bangladesh moves by every means at once — rickshaws and metro trains, overnight launches down
    the Meghna and eight-lane expressways over the capital. Here is how it all fits together, and
    what it costs.
  </p>
</div>

<!-- ------------------------------------------------------------- how to move -->
<section class="section">
  <div class="section__head">
    <h2>How people travel</h2>
    <p>Typical fares, and what to know before you get in.</p>
  </div>

  <div class="grid grid--3">
    <?php foreach ($transport as $i => $mode): ?>
      <article class="tile" data-reveal="<?= ($i % 9) * 40 ?>">
        <span class="tile__icon" aria-hidden="true"><?= $mode['emoji'] ?></span>
        <h3><?= e($mode['mode']) ?></h3>
        <div style="color:var(--green-300);font-size:.9rem;margin-bottom:.5rem"><?= e($mode['bn']) ?></div>
        <p style="margin-bottom:.8rem"><?= e($mode['tip']) ?></p>
        <div style="display:flex;gap:.4rem;flex-wrap:wrap">
          <span class="badge badge--gold"><?= e($mode['fare']) ?></span>
          <span class="badge"><?= e($mode['where']) ?></span>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<!-- --------------------------------------------------------------- highways -->
<section class="section">
  <div class="section__head">
    <h2>National highways</h2>
    <p>The N-numbered corridors that hold the country together.</p>
  </div>

  <div class="grid grid--2">
    <?php foreach ($roads as $i => $road): ?>
      <article class="card" data-reveal="<?= ($i % 6) * 45 ?>">
        <div style="display:flex;justify-content:space-between;align-items:baseline;gap:1rem">
          <h3 style="margin:0"><span class="badge badge--red"><?= e($road['code']) ?></span> <?= e($road['name']) ?></h3>
          <strong style="font-family:var(--mono);color:var(--green-300)"><?= bd_num($road['length']) ?> km</strong>
        </div>
        <div class="bar" style="margin:.7rem 0">
          <div class="bar__fill" data-width="<?= round($road['length'] / $longestRoad * 100) ?>"
               style="background:linear-gradient(90deg,var(--green-300),var(--teal-500))"></div>
        </div>
        <p><?= e($road['note']) ?></p>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<!-- ---------------------------------------------------------- megaprojects -->
<section class="section">
  <div class="section__head">
    <h2>The projects that changed the map</h2>
  </div>
  <div class="grid grid--3">
    <?php foreach ($projects as $i => $project): ?>
      <div class="card" data-reveal="<?= $i * 50 ?>">
        <span class="badge badge--gold">Opened <?= e((string) $project['opened']) ?></span>
        <h3 style="margin-top:.6rem">🏗️ <?= e($project['name']) ?></h3>
        <div style="font:700 1.35rem/1.2 var(--mono);color:var(--green-300);margin:.3rem 0 .5rem"><?= e($project['stat']) ?></div>
        <p><?= e($project['note']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- -------------------------------------------------------------- airports -->
<section class="section">
  <div class="section__head"><h2>Airports</h2></div>
  <div class="tablewrap" data-reveal>
    <table>
      <thead><tr><th>Airport</th><th>Code</th><th>City</th><th>Type</th><th class="num">Coordinates</th></tr></thead>
      <tbody>
        <?php foreach ($airports as $a): ?>
          <tr>
            <td><strong>✈️ <?= e($a['name']) ?></strong></td>
            <td><span class="badge"><?= e($a['code']) ?></span></td>
            <td><?= e($a['city']) ?></td>
            <td><?= e($a['type']) ?></td>
            <td class="num"><?= number_format($a['lat'], 3) ?>°N, <?= number_format($a['lon'], 3) ?>°E</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<!-- ----------------------------------------------------------------- ports -->
<section class="section">
  <div class="section__head"><h2>Sea and land ports</h2></div>
  <div class="grid grid--3">
    <?php foreach ($ports as $i => $port): ?>
      <div class="card" data-reveal="<?= $i * 55 ?>">
        <span class="badge badge--red"><?= e($port['type']) ?></span>
        <h3 style="margin-top:.6rem">⚓ <?= e($port['name']) ?></h3>
        <p><?= e($port['note']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<div class="notice" data-reveal>
  <span>🎫</span>
  <p style="margin:0">
    Train tickets go on sale exactly ten days before travel at
    <strong>eticket.railway.gov.bd</strong> and sell out within minutes on Eid weeks. Intercity bus
    and launch tickets are easiest through Shohoz, BusBD or the operator’s own counter.
  </p>
</div>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
