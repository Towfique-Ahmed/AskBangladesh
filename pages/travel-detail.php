<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** A page per travel destination. */

$place = bd_find_travel($route['slug']);

if ($place === null) {
    http_response_code(404);
    require APP_ROOT . '/pages/404.php';
    return;
}

$districtName = $place['district'];
$district     = null;
foreach (bd_districts() as $d) {
    if ($d['name'] === $districtName) { $district = $d; break; }
}

// Nearby: other destinations in the same district, then the same type.
$sameDistrict = array_values(array_filter(
    bd_places('travel'),
    static fn (array $p): bool => $p['district'] === $districtName && $p['name'] !== $place['name']
));
$sameType = array_values(array_filter(
    bd_places('travel'),
    static fn (array $p): bool => $p['type'] === $place['type'] && $p['name'] !== $place['name']
));
$related = array_slice(array_merge($sameDistrict, $sameType), 0, 6);

$seo = bd_seo([
    'title'       => $place['name'] . ' — Bangladesh Travel Guide',
    'description' => rtrim($place['desc'], '.') . '. Located in ' . $districtName
        . ' district, Bangladesh. Best time to visit: ' . $place['best'] . '.',
    'keywords'    => $place['name'] . ', ' . $place['name'] . ' Bangladesh, '
        . $districtName . ' tourism, ' . strtolower($place['type']) . ' in Bangladesh',
    'path'        => 'travel/' . bd_slug($place['name']),
    'type'        => 'article',
    'breadcrumbs' => [
        ['name' => 'Home',   'url' => bd_url()],
        ['name' => 'Travel', 'url' => bd_url('travel')],
        ['name' => $place['name']],
    ],
    'jsonld' => [
        [
            '@context'    => 'https://schema.org',
            '@type'       => 'TouristAttraction',
            'name'        => $place['name'],
            'description' => $place['desc'],
            'url'         => bd_abs_url('travel/' . bd_slug($place['name'])),
            'geo'         => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => $place['lat'],
                'longitude' => $place['lon'],
            ],
            'address' => [
                '@type'           => 'PostalAddress',
                'addressLocality' => $districtName,
                'addressCountry'  => 'BD',
            ],
            'isAccessibleForFree'  => true,
            'touristType'          => $place['type'],
            'publicAccess'         => true,
        ],
        bd_jsonld_faq([
            'Where is ' . $place['name'] . '?'
                => $place['name'] . ' is in ' . $districtName . ' district, Bangladesh, at '
                   . number_format((float) $place['lat'], 4) . '°N, '
                   . number_format((float) $place['lon'], 4) . '°E.',
            'When is the best time to visit ' . $place['name'] . '?'
                => 'The best season to visit ' . $place['name'] . ' is ' . $place['best'] . '.',
            'What is ' . $place['name'] . ' known for?'
                => $place['desc'],
        ]),
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<article>
  <div class="pagehead">
    <span class="pagehead__eyebrow">🧳 <?= e($place['type']) ?> · <?= e($districtName) ?></span>
    <div style="font-size:3.4rem;line-height:1"><?= $place['emoji'] ?></div>
    <h1><?= e($place['name']) ?></h1>
    <p style="font-size:1.08rem"><?= e($place['desc']) ?></p>
    <div style="display:flex;gap:.45rem;flex-wrap:wrap;margin-top:1rem">
      <span class="badge"><?= e($place['type']) ?></span>
      <span class="badge badge--red"><?= e($districtName) ?> district</span>
      <span class="badge badge--gold">Best season: <?= e($place['best']) ?></span>
    </div>
  </div>

  <div class="grid grid--2">
    <div class="card" data-reveal>
      <h3>📍 Location &amp; access</h3>
      <div class="mapinfo__rows">
        <div class="mapinfo__row"><span>District</span>
          <span><?php if ($district): ?><a href="<?= e(bd_district_url($district)) ?>"><?= e($districtName) ?></a><?php else: ?><?= e($districtName) ?><?php endif; ?></span></div>
        <?php if ($district): ?>
          <div class="mapinfo__row"><span>Division</span><span><a href="<?= e(bd_division_url($district['division'])) ?>"><?= e($district['division']) ?></a></span></div>
        <?php endif; ?>
        <div class="mapinfo__row"><span>Type</span><span><?= e($place['type']) ?></span></div>
        <div class="mapinfo__row"><span>Coordinates</span><span><?= number_format((float) $place['lat'], 4) ?>°N, <?= number_format((float) $place['lon'], 4) ?>°E</span></div>
        <div class="mapinfo__row"><span>Best time</span><span><?= e($place['best']) ?></span></div>
      </div>
      <div style="margin-top:1rem;display:flex;gap:.5rem;flex-wrap:wrap">
        <a class="btn btn--ghost" href="<?= e(bd_url('map')) ?>">Find it on the map →</a>
        <a class="btn btn--ghost" href="<?= e(bd_url('transport')) ?>">How to travel →</a>
      </div>
    </div>

    <div class="card" data-reveal="80">
      <h3>🧭 Planning your trip</h3>
      <p>
        The best window for <?= e($place['name']) ?> is <strong><?= e($place['best']) ?></strong>.
        November to February is the easiest season for travel across Bangladesh generally — cool
        and dry — while the monsoon between June and September is when the haors, waterfalls and
        hills are at their most dramatic.
      </p>
      <?php if ($district): ?>
        <p style="margin-bottom:0">
          <?= e($districtName) ?> is known for <?= e(lcfirst(rtrim($district['famous'], '.'))) ?>.
        </p>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($district): ?>
    <section class="section">
      <div class="section__head">
        <h2>Prayer times in <?= e($districtName) ?> today</h2>
      </div>
      <div class="grid grid--3">
        <?php foreach (bd_prayer_times((float) $district['lat'], (float) $district['lon']) as $pname => $time):
            $info = bd_prayer_meta()[$pname] ?? ['bn' => '', 'emoji' => '🕌']; ?>
          <div class="prayercard" data-reveal>
            <span class="prayercard__emoji" aria-hidden="true"><?= $info['emoji'] ?></span>
            <span><span class="prayercard__name"><?= e($pname) ?></span>
              <span class="prayercard__bn"><?= e($info['bn']) ?></span></span>
            <span class="prayercard__time"><?= e($time) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <?php if ($related): ?>
    <section class="section">
      <div class="section__head">
        <h2>More places like this</h2>
        <p><a href="<?= e(bd_url('travel')) ?>" style="color:var(--green-300)">All destinations →</a></p>
      </div>
      <div class="grid grid--3">
        <?php foreach ($related as $i => $other): ?>
          <a class="tile" href="<?= e(bd_travel_url($other)) ?>" data-reveal="<?= $i * 40 ?>">
            <span class="tile__icon" aria-hidden="true"><?= $other['emoji'] ?></span>
            <h3><?= e($other['name']) ?></h3>
            <p style="margin-bottom:.7rem"><?= e($other['desc']) ?></p>
            <span class="badge"><?= e($other['type']) ?></span>
            <span class="badge badge--red"><?= e($other['district']) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
</article>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
