<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** A page per district — the main indexable long-tail content of the site. */

$district = bd_find_district($route['slug']);

if ($district === null) {
    http_response_code(404);
    require APP_ROOT . '/pages/404.php';
    return;
}

$divisions = bd_divisions();
$division  = $divisions[$district['division']] ?? null;
$color     = $division['color'] ?? '#00a651';
$prayer    = bd_prayer_times((float) $district['lat'], (float) $district['lon']);

// Related content that genuinely sits in this district.
$places = array_values(array_filter(
    bd_places('travel'),
    static fn (array $p): bool => $p['district'] === $district['name']
));
$peaks = array_values(array_filter(
    bd_places('mountains'),
    static fn (array $m): bool => $m['district'] === $district['name']
));
$siblings = array_values(array_filter(
    bd_division_districts($district['division']),
    static fn (array $d): bool => $d['name'] !== $district['name']
));

$density = $district['area'] > 0 ? $district['population'] / $district['area'] : 0;

// One source for both the FAQPage markup and the visible block below it.
$faqs = [
    'Which division is ' . $district['name'] . ' in?'
        => $district['name'] . ' district belongs to ' . $district['division']
           . ' division, one of the eight divisions of Bangladesh.',
    'What is the population of ' . $district['name'] . '?'
        => $district['name'] . ' has a population of roughly ' . bd_num($district['population'])
           . ', across an area of ' . bd_num($district['area'], 1) . ' square kilometres.',
    'What is ' . $district['name'] . ' famous for?'
        => $district['famous'],
    'What time is Fajr in ' . $district['name'] . ' today?'
        => 'Fajr begins at ' . $prayer['Fajr'] . ' and Maghrib at ' . $prayer['Maghrib']
           . ' today in ' . $district['name'] . ', Bangladesh Standard Time.',
];

$seo = bd_seo([
    'title'       => $district['name'] . ' District — Area, Population & Places',
    'description' => $district['name'] . ' district in ' . $district['division'] . ' division, Bangladesh: '
        . bd_num($district['area'], 0) . ' km², about ' . bd_compact($district['population'])
        . ' people. ' . rtrim($district['famous'], '.') . '.',
    'keywords'    => $district['name'] . ' district, ' . $district['name'] . ' Bangladesh, '
        . $district['division'] . ' division, ' . $district['name'] . ' population',
    'path'        => 'district/' . bd_slug($district['name']),
    'type'        => 'article',
    'breadcrumbs' => [
        ['name' => 'Home',      'url' => bd_url()],
        ['name' => 'Districts', 'url' => bd_url('districts')],
        ['name' => $district['division'] . ' Division', 'url' => bd_division_url($district['division'])],
        ['name' => $district['name']],
    ],
    'jsonld' => [
        [
            '@context'  => 'https://schema.org',
            '@type'     => 'AdministrativeArea',
            'name'      => $district['name'] . ' District',
            'alternateName' => $district['bn'],
            'description'   => $district['famous'],
            'url'       => bd_abs_url('district/' . bd_slug($district['name'])),
            'geo'       => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => $district['lat'],
                'longitude' => $district['lon'],
            ],
            'containedInPlace' => [
                '@type' => 'AdministrativeArea',
                'name'  => $district['division'] . ' Division',
                'url'   => bd_abs_url('division/' . bd_slug($district['division'])),
            ],
            'address' => [
                '@type'          => 'PostalAddress',
                'addressRegion'  => $district['division'],
                'addressCountry' => 'BD',
            ],
        ],
        bd_jsonld_faq($faqs),
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<article>
  <div class="pagehead">
    <span class="pagehead__eyebrow" style="color:<?= e($color) ?>">
      📍 <?= e($district['division']) ?> Division
    </span>
    <h1><?= e($district['name']) ?> District</h1>
    <p style="font-size:1.3rem;color:var(--green-300);margin-bottom:.6rem"><?= e($district['bn']) ?></p>
    <p>
      <?= e($district['name']) ?> is one of the 64 districts of Bangladesh and sits in
      <?= e($district['division']) ?> division. It covers <?= bd_num($district['area'], 1) ?> km²
      and is home to roughly <?= bd_num($district['population']) ?> people.
      It is best known for <?= e(lcfirst(rtrim($district['famous'], '.'))) ?>.
    </p>
  </div>

  <div class="statgrid" data-reveal>
    <div class="stat"><div class="stat__value" data-count-to="<?= $district['area'] ?>" data-count-dec="1">0</div><div class="stat__label">Area (km²)</div></div>
    <div class="stat"><div class="stat__value" data-count-to="<?= $district['population'] ?>">0</div><div class="stat__label">Population</div></div>
    <div class="stat"><div class="stat__value" data-count-to="<?= round($density) ?>">0</div><div class="stat__label">People per km²</div></div>
    <div class="stat"><div class="stat__value"><?= number_format((float) $district['lat'], 2) ?>°N</div><div class="stat__label">Latitude</div></div>
    <div class="stat"><div class="stat__value"><?= number_format((float) $district['lon'], 2) ?>°E</div><div class="stat__label">Longitude</div></div>
  </div>

  <!-- --------------------------------------------------------- prayer -->
  <section class="section">
    <div class="section__head">
      <h2>Prayer times in <?= e($district['name']) ?> today</h2>
      <p><?= e(date('l, j F Y')) ?></p>
    </div>
    <div class="grid grid--3">
      <?php foreach ($prayer as $name => $time):
          $info = bd_prayer_meta()[$name] ?? ['bn' => '', 'emoji' => '🕌'];
      ?>
        <div class="prayercard" data-reveal>
          <span class="prayercard__emoji" aria-hidden="true"><?= $info['emoji'] ?></span>
          <span>
            <span class="prayercard__name"><?= e($name) ?></span>
            <span class="prayercard__bn"><?= e($info['bn']) ?></span>
          </span>
          <span class="prayercard__time"><?= e($time) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
    <p style="margin-top:1rem">
      <a class="btn btn--ghost" href="<?= e(bd_prayer_url($district)) ?>">
        Full prayer schedule for <?= e($district['name']) ?> →
      </a>
    </p>
  </section>

  <!-- ---------------------------------------------------------- facts -->
  <section class="section">
    <div class="section__head"><h2>About <?= e($district['name']) ?></h2></div>
    <div class="grid grid--2">
      <div class="card" data-reveal>
        <h3>📋 District profile</h3>
        <div class="mapinfo__rows">
          <div class="mapinfo__row"><span>Bangla name</span><span><?= e($district['bn']) ?></span></div>
          <div class="mapinfo__row"><span>Division</span><span><a href="<?= e(bd_division_url($district['division'])) ?>"><?= e($district['division']) ?></a></span></div>
          <div class="mapinfo__row"><span>Area</span><span><?= bd_num($district['area'], 1) ?> km²</span></div>
          <div class="mapinfo__row"><span>Population</span><span><?= bd_num($district['population']) ?></span></div>
          <div class="mapinfo__row"><span>Density</span><span><?= bd_num($density) ?> / km²</span></div>
          <div class="mapinfo__row"><span>Coordinates</span><span><?= number_format((float) $district['lat'], 4) ?>°N, <?= number_format((float) $district['lon'], 4) ?>°E</span></div>
          <div class="mapinfo__row"><span>Time zone</span><span>BST (UTC+06:00)</span></div>
        </div>
      </div>

      <div class="card" data-reveal="80">
        <h3>⭐ Known for</h3>
        <p style="font-size:1rem"><?= e($district['famous']) ?></p>
        <?php if ($peaks): ?>
          <h4 style="font-size:.72rem;text-transform:uppercase;letter-spacing:.12em;color:var(--text-mute);margin:1.2rem 0 .4rem">Peaks in this district</h4>
          <ul style="margin:0;padding-left:1.1rem;font-size:.88rem;color:var(--text-dim)">
            <?php foreach ($peaks as $peak): ?>
              <li><?= e($peak['name']) ?> — <?= bd_num($peak['height']) ?> m</li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <div style="margin-top:1.2rem;display:flex;gap:.5rem;flex-wrap:wrap">
          <a class="btn btn--ghost" href="<?= e(bd_url('map')) ?>">See on the map →</a>
        </div>
      </div>
    </div>
  </section>

  <?php if ($places): ?>
    <section class="section">
      <div class="section__head">
        <h2>Places to visit in <?= e($district['name']) ?></h2>
      </div>
      <div class="grid grid--3">
        <?php foreach ($places as $i => $place): ?>
          <a class="tile" href="<?= e(bd_travel_url($place)) ?>" data-reveal="<?= $i * 45 ?>">
            <span class="tile__icon" aria-hidden="true"><?= $place['emoji'] ?></span>
            <h3><?= e($place['name']) ?></h3>
            <p style="margin-bottom:.7rem"><?= e($place['desc']) ?></p>
            <span class="badge badge--gold">Best: <?= e($place['best']) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <!-- ------------------------------------------------------- siblings -->
  <section class="section">
    <div class="section__head">
      <h2>Other districts in <?= e($district['division']) ?></h2>
      <p><a href="<?= e(bd_division_url($district['division'])) ?>" style="color:var(--green-300)">All of <?= e($district['division']) ?> →</a></p>
    </div>
    <div class="grid grid--4">
      <?php foreach ($siblings as $i => $sibling): ?>
        <a class="card" href="<?= e(bd_district_url($sibling)) ?>" data-reveal="<?= ($i % 8) * 35 ?>">
          <h3 style="font-size:.98rem;margin-bottom:.15rem"><?= e($sibling['name']) ?></h3>
          <div style="color:var(--green-300);font-size:.85rem"><?= e($sibling['bn']) ?></div>
        </a>
      <?php endforeach; ?>
    </div>
  </section>
  <?= bd_render_faq($faqs, 'Frequently asked questions about ' . $district['name']) ?>
</article>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
