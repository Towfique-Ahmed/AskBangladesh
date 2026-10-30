<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** A page per district - the main indexable long-tail content of the site. */

$district = bd_find_district($route['slug']);

if ($district === null) {
    http_response_code(404);
    require APP_ROOT . '/pages/404.php';
    return;
}

$divisions = bd_divisions();
$division  = $divisions[$district['division']] ?? null;
$color     = $division['color'] ?? '#00a651';
$sun       = bd_sun_times((float) $district['lat'], (float) $district['lon']);

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

$dname   = bd_name($district);
$famous  = bd_l($district, 'famous');
$density = $district['area'] > 0 ? $district['population'] / $district['area'] : 0;

// One source for both the FAQPage markup and the visible block below it.
$faqs = [
    tf('Which division is %s in?', $dname)
        => tf('%s district belongs to %s division, one of the eight divisions of Bangladesh.', $dname, t($district['division'])),
    tf('What is the population of %s?', $dname)
        => tf('%s has a population of roughly %s, across an area of %s square kilometres.', $dname, bd_n($district['population']), bd_n($district['area'], 1)),
    tf('What is %s famous for?', $dname)
        => $famous,
    tf('What time is sunrise and sunset in %s?', $dname)
        => tf('The sun rises at %s and sets at %s today in %s, giving %s of daylight.', $sun['Sunrise'], $sun['Sunset'], $dname, $sun['Day length']),
    tf('What is the main industry in %s?', $dname)
        => bd_l($district, 'economy'),
];

$seo = bd_seo([
    'title'       => tf('%s District - Area, Population & Places', $dname),
    'description' => tf('%s district in %s division, Bangladesh: %s km², about %s people. %s.', $dname, t($district['division']), bd_n($district['area'], 0), bd_lang() === 'bn' ? bd_bn_digits(bd_compact($district['population'])) : bd_compact($district['population']), rtrim($famous, '.')),
    'keywords'    => $district['name'] . ' district, ' . $district['name'] . ' Bangladesh, '
        . $district['division'] . ' division, ' . $district['name'] . ' population',
    'path'        => 'district/' . bd_slug($district['name']),
    'type'        => 'article',
    'breadcrumbs' => [
        ['name' => t('Home'),      'url' => bd_url()],
        ['name' => t('Districts'), 'url' => bd_url('districts')],
        ['name' => tf('%s Division', t($district['division'])), 'url' => bd_division_url($district['division'])],
        ['name' => $dname],
    ],
    'jsonld' => [
        [
            '@context'  => 'https://schema.org',
            '@type'     => 'AdministrativeArea',
            'name'      => $district['name'] . ' District',
            'alternateName' => $district['bn'],
            'description'   => $famous,
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
      📍 <?= e(tf('%s Division', t($district['division']))) ?>
    </span>
    <h1><?= e(tf('%s District', $dname)) ?></h1>
    <p style="font-size:1.3rem;color:var(--green-300);margin-bottom:.6rem"><?= e(bd_lang() === 'bn' ? $district['name'] : $district['bn']) ?></p>
    <p>
      <?= e(tf('%s is one of the 64 districts of Bangladesh and sits in %s division. It covers %s km² and is home to roughly %s people. It is best known for %s.', $dname, t($district['division']), bd_n($district['area'], 1), bd_n($district['population']), lcfirst(rtrim($famous, '.')))) ?>
    </p>
  </div>

  <!-- --------------------------------------------------------- history -->
  <section class="section">
    <div class="section__head"><h2><?= e(tf('History of %s', $dname)) ?></h2></div>
    <p><?= e(bd_l($district, 'history')) ?></p>
  </section>

  <div class="statgrid" data-reveal>
    <div class="stat"><div class="stat__value" data-count-to="<?= $district['area'] ?>" data-count-dec="1">0</div><div class="stat__label"><?= e(t('Area (km²)')) ?></div></div>
    <div class="stat"><div class="stat__value" data-count-to="<?= $district['population'] ?>">0</div><div class="stat__label"><?= e(t('Population')) ?></div></div>
    <div class="stat"><div class="stat__value" data-count-to="<?= round($density) ?>">0</div><div class="stat__label"><?= e(t('People per km²')) ?></div></div>
    <div class="stat"><div class="stat__value"><?= number_format((float) $district['lat'], 2) ?>°N</div><div class="stat__label"><?= e(t('Latitude')) ?></div></div>
    <div class="stat"><div class="stat__value"><?= number_format((float) $district['lon'], 2) ?>°E</div><div class="stat__label"><?= e(t('Longitude')) ?></div></div>
  </div>

  <!-- ------------------------------------------------------ sun times -->
  <section class="section">
    <div class="section__head">
      <h2><?= e(tf('Sunrise and sunset in %s today', $dname)) ?></h2>
      <p><?= e(bd_today_long()) ?></p>
    </div>
    <div class="grid grid--3">
      <?php foreach (['Sunrise', 'Solar noon', 'Sunset', 'First light', 'Last light', 'Day length'] as $name):
          $info = bd_sun_meta()[$name] ?? ['bn' => '', 'emoji' => '☀️'];
      ?>
        <div class="suncard" data-reveal>
          <span class="suncard__emoji" aria-hidden="true"><?= $info['emoji'] ?></span>
          <span>
            <span class="suncard__name"><?= e(t($name)) ?></span>
            <span class="suncard__bn"><?= e(bd_lang() === 'bn' ? $name : $info['bn']) ?></span>
          </span>
          <span class="suncard__time"><?= e($sun[$name]) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
    <p style="margin-top:1rem">
      <a class="btn btn--ghost" href="<?= e(bd_sun_url($district)) ?>">
        <?= e(tf('Full sun times for %s →', $dname)) ?>
      </a>
    </p>
  </section>

  <!-- ---------------------------------------------------------- facts -->
  <section class="section">
    <div class="section__head"><h2><?= e(tf('About %s', $dname)) ?></h2></div>
    <div class="grid grid--2">
      <div class="card" data-reveal>
        <h3>📋 <?= e(t('District profile')) ?></h3>
        <div class="mapinfo__rows">
          <div class="mapinfo__row"><span><?= e(t('Bangla name')) ?></span><span><?= e($district['bn']) ?></span></div>
          <div class="mapinfo__row"><span><?= e(t('Division')) ?></span><span><a href="<?= e(bd_division_url($district['division'])) ?>"><?= e(t($district['division'])) ?></a></span></div>
          <div class="mapinfo__row"><span><?= e(t('Area')) ?></span><span><?= bd_n($district['area'], 1) ?> <?= e(t('km²')) ?></span></div>
          <div class="mapinfo__row"><span><?= e(t('Population')) ?></span><span><?= bd_n($district['population']) ?></span></div>
          <div class="mapinfo__row"><span><?= e(t('Density')) ?></span><span><?= bd_n($density) ?> / <?= e(t('km²')) ?></span></div>
          <div class="mapinfo__row"><span><?= e(t('Coordinates')) ?></span><span><?= e(bd_lang() === 'bn' ? bd_bn_digits(number_format((float) $district['lat'], 4) . '°উ, ' . number_format((float) $district['lon'], 4) . '°পূ') : number_format((float) $district['lat'], 4) . '°N, ' . number_format((float) $district['lon'], 4) . '°E') ?></span></div>
          <div class="mapinfo__row"><span><?= e(t('Time zone')) ?></span><span><?= e(t('BST (UTC+06:00)')) ?></span></div>
          <div class="mapinfo__row"><span><?= e(t('Upazilas')) ?></span><span><?= bd_n((int) $district['upazilas']) ?></span></div>
        </div>
      </div>

      <div class="card" data-reveal="80">
        <h3>⭐ <?= e(t('Known for')) ?></h3>
        <p style="font-size:1rem"><?= e($famous) ?></p>
        <?php if (!empty($district['attractions'])): ?>
          <h4 style="font-size:.72rem;text-transform:uppercase;letter-spacing:.12em;color:var(--text-mute);margin:1.2rem 0 .4rem"><?= e(t('Top attractions')) ?></h4>
          <div style="display:flex;gap:.4rem;flex-wrap:wrap">
            <?php foreach ($district['attractions'] as $attraction): ?>
              <span class="badge"><?= e($attraction) ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <?php if ($peaks): ?>
          <h4 style="font-size:.72rem;text-transform:uppercase;letter-spacing:.12em;color:var(--text-mute);margin:1.2rem 0 .4rem"><?= e(t('Peaks in this district')) ?></h4>
          <ul style="margin:0;padding-left:1.1rem;font-size:.88rem;color:var(--text-dim)">
            <?php foreach ($peaks as $peak): ?>
              <li><?= e($peak['name']) ?> - <?= bd_n($peak['height']) ?> <?= e(t('m')) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <div style="margin-top:1.2rem;display:flex;gap:.5rem;flex-wrap:wrap">
          <a class="btn btn--ghost" href="<?= e(bd_url('map')) ?>"><?= e(t('See on the map →')) ?></a>
        </div>
      </div>
    </div>
  </section>

  <!-- ------------------------------------------------- economy & culture -->
  <section class="section">
    <div class="section__head"><h2><?= e(t('Economy & culture')) ?></h2></div>
    <div class="grid grid--2">
      <div class="card" data-reveal>
        <h3>💼 <?= e(t('Economy')) ?></h3>
        <p style="font-size:1rem"><?= e(bd_l($district, 'economy')) ?></p>
      </div>
      <div class="card" data-reveal="80">
        <h3>🎭 <?= e(t('Culture & cuisine')) ?></h3>
        <p style="font-size:1rem"><?= e(bd_l($district, 'culture')) ?></p>
      </div>
    </div>
  </section>

  <?php if ($places): ?>
    <section class="section">
      <div class="section__head">
        <h2><?= e(tf('Places to visit in %s', $dname)) ?></h2>
      </div>
      <div class="grid grid--3">
        <?php foreach ($places as $i => $place): ?>
          <a class="tile" href="<?= e(bd_travel_url($place)) ?>" data-reveal="<?= $i * 45 ?>">
            <span class="tile__icon" aria-hidden="true"><?= $place['emoji'] ?></span>
            <h3><?= e(bd_name($place)) ?></h3>
            <p style="margin-bottom:.7rem"><?= e(bd_l($place, 'desc')) ?></p>
            <span class="badge badge--gold"><?= e(t('Best:')) ?> <?= e(bd_season($place['best'])) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <!-- ------------------------------------------------------- siblings -->
  <section class="section">
    <div class="section__head">
      <h2><?= e(tf('Other districts in %s', t($district['division']))) ?></h2>
      <p><a href="<?= e(bd_division_url($district['division'])) ?>" style="color:var(--green-300)"><?= e(tf('All of %s →', t($district['division']))) ?></a></p>
    </div>
    <div class="grid grid--4">
      <?php foreach ($siblings as $i => $sibling): ?>
        <a class="card" href="<?= e(bd_district_url($sibling)) ?>" data-reveal="<?= ($i % 8) * 35 ?>">
          <h3 style="font-size:.98rem;margin-bottom:.15rem"><?= e(bd_name($sibling)) ?></h3>
          <div style="color:var(--green-300);font-size:.85rem"><?= e(bd_lang() === 'bn' ? $sibling['name'] : $sibling['bn']) ?></div>
        </a>
      <?php endforeach; ?>
    </div>
  </section>
  <?= bd_render_faq($faqs, tf('Frequently asked questions about %s', $dname)) ?>
</article>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
