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

// One source for both the FAQPage markup and the visible block below it.
$pname  = bd_name($place);
$pdesc  = bd_l($place, 'desc');
$pbest  = bd_season($place['best']);
$dName  = bd_district_name($districtName);
$coords = bd_lang() === 'bn'
    ? bd_bn_digits(number_format((float) $place['lat'], 4) . '°উ, ' . number_format((float) $place['lon'], 4) . '°পূ')
    : number_format((float) $place['lat'], 4) . '°N, ' . number_format((float) $place['lon'], 4) . '°E';

$faqs = [
    tf('Where is %s?', $pname)
        => tf('%s is in %s district, Bangladesh, at %s.', $pname, $dName, $coords),
    tf('When is the best time to visit %s?', $pname)
        => tf('The best season to visit %s is %s.', $pname, $pbest),
    tf('What is %s known for?', $pname)
        => $pdesc,
];
if (!empty($place['how_to_reach'])) {
    $faqs[tf('How do I get to %s?', $pname)] = bd_l($place, 'how_to_reach');
}
if (!empty($place['cost'])) {
    $faqs[tf('How much does it cost to visit %s?', $pname)] = bd_l($place, 'cost') . (bd_lang() === 'bn' ? '' : '.');
}

$seo = bd_seo([
    'title'       => tf('%s - Bangladesh Travel Guide', $pname),
    'description' => tf('%s. Located in %s district, Bangladesh. Best time to visit: %s.', rtrim($pdesc, '.'), $dName, $pbest),
    'keywords'    => $place['name'] . ', ' . $place['name'] . ' Bangladesh, '
        . $districtName . ' tourism, ' . strtolower($place['type']) . ' in Bangladesh',
    'path'        => 'travel/' . bd_slug($place['name']),
    'type'        => 'article',
    'breadcrumbs' => [
        ['name' => t('Home'),   'url' => bd_url()],
        ['name' => t('Travel'), 'url' => bd_url('travel')],
        ['name' => $pname],
    ],
    'jsonld' => [
        [
            '@context'    => 'https://schema.org',
            '@type'       => 'TouristAttraction',
            'name'        => $pname,
            'description' => $pdesc,
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
        bd_jsonld_faq($faqs),
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<article>
  <div class="pagehead">
    <span class="pagehead__eyebrow">🧳 <?= e(t($place['type'])) ?> · <?= e($dName) ?></span>
    <div style="font-size:3.4rem;line-height:1"><?= $place['emoji'] ?></div>
    <h1><?= e($pname) ?></h1>
    <p style="font-size:1.08rem"><?= e($pdesc) ?></p>
    <div style="display:flex;gap:.45rem;flex-wrap:wrap;margin-top:1rem">
      <span class="badge"><?= e(t($place['type'])) ?></span>
      <span class="badge badge--red"><?= e(tf('%s district', $dName)) ?></span>
      <span class="badge badge--gold"><?= e(t('Best season:')) ?> <?= e($pbest) ?></span>
    </div>
  </div>

  <div class="grid grid--2">
    <div class="card" data-reveal>
      <h3>📍 <?= e(t('Location & access')) ?></h3>
      <div class="mapinfo__rows">
        <div class="mapinfo__row"><span><?= e(t('District')) ?></span>
          <span><?php if ($district): ?><a href="<?= e(bd_district_url($district)) ?>"><?= e($dName) ?></a><?php else: ?><?= e($dName) ?><?php endif; ?></span></div>
        <?php if ($district): ?>
          <div class="mapinfo__row"><span><?= e(t('Division')) ?></span><span><a href="<?= e(bd_division_url($district['division'])) ?>"><?= e(t($district['division'])) ?></a></span></div>
        <?php endif; ?>
        <div class="mapinfo__row"><span><?= e(t('Type')) ?></span><span><?= e(t($place['type'])) ?></span></div>
        <div class="mapinfo__row"><span><?= e(t('Coordinates')) ?></span><span><?= e($coords) ?></span></div>
        <div class="mapinfo__row"><span><?= e(t('Best time')) ?></span><span><?= e($pbest) ?></span></div>
        <?php if (!empty($place['cost'])): ?>
          <div class="mapinfo__row"><span><?= e(t('Cost')) ?></span><span><?= e(bd_l($place, 'cost')) ?></span></div>
        <?php endif; ?>
      </div>
      <div style="margin-top:1rem;display:flex;gap:.5rem;flex-wrap:wrap">
        <a class="btn btn--ghost" href="<?= e(bd_url('map')) ?>"><?= e(t('Find it on the map →')) ?></a>
        <a class="btn btn--ghost" href="<?= e(bd_url('transport')) ?>"><?= e(t('How to travel →')) ?></a>
      </div>
    </div>

    <div class="card" data-reveal="80">
      <h3>🧭 <?= e(t('Planning your trip')) ?></h3>
      <p>
        <?= tf('The best window for %s is <strong>%s</strong>. November to February is the easiest season for travel across Bangladesh generally - cool and dry - while the monsoon between June and September is when the haors, waterfalls and hills are at their most dramatic.', e($pname), e($pbest)) ?>
      </p>
      <?php if (!empty($place['how_to_reach'])): ?>
        <p><strong><?= e(t('How to reach:')) ?></strong> <?= e(bd_l($place, 'how_to_reach')) ?></p>
      <?php endif; ?>
      <?php if ($district): ?>
        <p style="margin-bottom:0">
          <?= e(tf('%s is known for %s.', $dName, lcfirst(rtrim(bd_l($district, 'famous'), '.')))) ?>
        </p>
      <?php endif; ?>
    </div>
  </div>

  <?php if (!empty($place['history'])): ?>
    <section class="section">
      <div class="section__head"><h2><?= e(t('History')) ?></h2></div>
      <div class="card" data-reveal>
        <p style="margin-bottom:0"><?= e(bd_l($place, 'history')) ?></p>
      </div>
    </section>
  <?php endif; ?>

  <?php if (!empty($place['tips']) || !empty($place['nearby'])): ?>
    <section class="section">
      <div class="grid grid--2">
        <?php if (!empty($place['tips'])): ?>
          <div class="card" data-reveal>
            <h3>✅ <?= e(t('Tips for visiting')) ?></h3>
            <ul style="margin:0;padding-left:1.1rem;font-size:.9rem;color:var(--text-dim)">
              <?php foreach ((bd_lang() === 'bn' && !empty($place['tips_bn'])) ? $place['tips_bn'] : $place['tips'] as $tip): ?>
                <li style="margin-bottom:.4rem"><?= e($tip) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
        <?php if (!empty($place['nearby'])): ?>
          <div class="card" data-reveal="80">
            <h3>🧭 <?= e(t('Nearby')) ?></h3>
            <ul style="margin:0;padding-left:1.1rem;font-size:.9rem;color:var(--text-dim)">
              <?php foreach ((bd_lang() === 'bn' && !empty($place['nearby_bn'])) ? $place['nearby_bn'] : $place['nearby'] as $spot): ?>
                <li style="margin-bottom:.4rem"><?= e($spot) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>

  <?php if ($district):
      $sun = bd_sun_times((float) $district['lat'], (float) $district['lon']); ?>
    <section class="section">
      <div class="section__head">
        <h2><?= e(tf('Sunrise and sunset at %s today', $pname)) ?></h2>
        <p><?= e(tf('Useful for planning the golden hour - %s of daylight.', $sun['Day length'])) ?></p>
      </div>
      <div class="grid grid--3">
        <?php foreach (['Sunrise', 'Golden hour begins', 'Sunset'] as $sname):
            $info = bd_sun_meta()[$sname] ?? ['bn' => '', 'emoji' => '☀️']; ?>
          <div class="suncard" data-reveal>
            <span class="suncard__emoji" aria-hidden="true"><?= $info['emoji'] ?></span>
            <span><span class="suncard__name"><?= e(t($sname)) ?></span>
              <span class="suncard__bn"><?= e(bd_lang() === 'bn' ? $sname : $info['bn']) ?></span></span>
            <span class="suncard__time"><?= e($sun[$sname]) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
      <p style="margin-top:1rem">
        <a class="btn btn--ghost" href="<?= e(bd_sun_url($district)) ?>">
          <?= e(tf('Full sun times for %s →', $dName)) ?>
        </a>
      </p>
    </section>
  <?php endif; ?>

  <?php if ($related): ?>
    <section class="section">
      <div class="section__head">
        <h2><?= e(t('More places like this')) ?></h2>
        <p><a href="<?= e(bd_url('travel')) ?>" style="color:var(--green-300)"><?= e(t('All destinations →')) ?></a></p>
      </div>
      <div class="grid grid--3">
        <?php foreach ($related as $i => $other): ?>
          <a class="tile" href="<?= e(bd_travel_url($other)) ?>" data-reveal="<?= $i * 40 ?>">
            <span class="tile__icon" aria-hidden="true"><?= $other['emoji'] ?></span>
            <h3><?= e(bd_name($other)) ?></h3>
            <p style="margin-bottom:.7rem"><?= e(bd_l($other, 'desc')) ?></p>
            <span class="badge"><?= e(t($other['type'])) ?></span>
            <span class="badge badge--red"><?= e(bd_district_name($other['district'])) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
  <?= bd_render_faq($faqs, tf('Frequently asked questions about %s', $pname)) ?>
</article>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
