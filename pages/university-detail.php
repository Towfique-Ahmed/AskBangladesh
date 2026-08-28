<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** A page per university. */

$uni = bd_find_university($route['slug']);

if ($uni === null) {
    http_response_code(404);
    require APP_ROOT . '/pages/404.php';
    return;
}

$districtName = $uni['district'];
$district     = null;
foreach (bd_districts() as $d) {
    if ($d['name'] === $districtName) { $district = $d; break; }
}

$categoryLabel = $uni['category'] === 'public' ? 'Public' : 'Private';

// Nearby: other universities of the same category, then the same district.
$sameCategory = array_values(array_filter(
    bd_all_universities(),
    static fn (array $u): bool => $u['category'] === $uni['category'] && $u['name'] !== $uni['name']
));
$sameDistrict = array_values(array_filter(
    bd_all_universities(),
    static fn (array $u): bool => $u['district'] === $districtName && $u['name'] !== $uni['name']
));
$related = array_slice(array_merge($sameDistrict, $sameCategory), 0, 6);

$faqs = [
    'When was ' . $uni['name'] . ' established?'
        => $uni['name'] . ' was established in ' . $uni['established'] . '.',
    'Where is ' . $uni['name'] . ' located?'
        => $uni['name'] . ' is located in ' . $uni['city'] . ', ' . $districtName . ' district, Bangladesh.',
    'Is ' . $uni['name'] . ' a public or private university?'
        => $uni['name'] . ' is a ' . strtolower($categoryLabel) . ' university in Bangladesh.',
];

$seo = bd_seo([
    'title'       => $uni['name'] . ' (' . $uni['short'] . ') — Bangladesh Universities',
    'description' => rtrim($uni['desc'], '.') . '. Established ' . $uni['established']
        . ', located in ' . $uni['city'] . ', a ' . strtolower($categoryLabel) . ' university in Bangladesh.',
    'keywords'    => $uni['name'] . ', ' . $uni['short'] . ', ' . $districtName
        . ' university, ' . strtolower($categoryLabel) . ' university Bangladesh',
    'path'        => 'universities/' . bd_slug($uni['name']),
    'type'        => 'article',
    'breadcrumbs' => [
        ['name' => 'Home',         'url' => bd_url()],
        ['name' => 'Universities', 'url' => bd_url('universities')],
        ['name' => $uni['name']],
    ],
    'jsonld' => [
        [
            '@context'    => 'https://schema.org',
            '@type'       => 'CollegeOrUniversity',
            'name'        => $uni['name'],
            'alternateName' => $uni['short'],
            'description' => $uni['desc'],
            'url'         => bd_abs_url('universities/' . bd_slug($uni['name'])),
            'foundingDate' => (string) $uni['established'],
            'sameAs'      => $uni['website'] !== '' ? [$uni['website']] : [],
            'geo'         => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => $uni['lat'],
                'longitude' => $uni['lon'],
            ],
            'address' => [
                '@type'           => 'PostalAddress',
                'addressLocality' => $uni['city'],
                'addressCountry'  => 'BD',
            ],
        ],
        bd_jsonld_faq($faqs),
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<article>
  <div class="pagehead">
    <span class="pagehead__eyebrow"><?= e($categoryLabel) ?> University · <?= e($districtName) ?></span>
    <div style="font-size:3.4rem;line-height:1">🎓</div>
    <h1><?= e($uni['name']) ?> <?php if ($uni['short'] !== ''): ?><span style="color:var(--text-mute);font-weight:400">(<?= e($uni['short']) ?>)</span><?php endif; ?></h1>
    <?php if ($uni['bn'] !== ''): ?><p style="font-size:1.15rem;margin:.2rem 0"><?= e($uni['bn']) ?></p><?php endif; ?>
    <p style="font-size:1.08rem"><?= e($uni['desc']) ?></p>
    <div style="display:flex;gap:.45rem;flex-wrap:wrap;margin-top:1rem">
      <span class="badge"><?= e($categoryLabel) ?></span>
      <span class="badge badge--red"><?= e($districtName) ?> district</span>
      <span class="badge badge--gold">Established <?= e((string) $uni['established']) ?></span>
    </div>
  </div>

  <div class="grid grid--2">
    <div class="card" data-reveal>
      <h3>📍 Location &amp; overview</h3>
      <div class="mapinfo__rows">
        <div class="mapinfo__row"><span>City / area</span><span><?= e($uni['city']) ?></span></div>
        <div class="mapinfo__row"><span>District</span>
          <span><?php if ($district): ?><a href="<?= e(bd_district_url($district)) ?>"><?= e($districtName) ?></a><?php else: ?><?= e($districtName) ?><?php endif; ?></span></div>
        <?php if ($district): ?>
          <div class="mapinfo__row"><span>Division</span><span><a href="<?= e(bd_division_url($district['division'])) ?>"><?= e($district['division']) ?></a></span></div>
        <?php endif; ?>
        <div class="mapinfo__row"><span>Type</span><span><?= e($uni['type']) ?></span></div>
        <div class="mapinfo__row"><span>Established</span><span><?= e((string) $uni['established']) ?></span></div>
        <?php if ($uni['motto'] !== ''): ?>
          <div class="mapinfo__row"><span>Motto</span><span><?= e($uni['motto']) ?></span></div>
        <?php endif; ?>
        <div class="mapinfo__row"><span>Coordinates</span><span><?= number_format((float) $uni['lat'], 4) ?>°N, <?= number_format((float) $uni['lon'], 4) ?>°E</span></div>
      </div>
      <?php if ($uni['website'] !== ''): ?>
        <div style="margin-top:1rem">
          <a class="btn btn--ghost" href="<?= e($uni['website']) ?>" target="_blank" rel="noopener noreferrer">Official website →</a>
        </div>
      <?php endif; ?>
    </div>

    <div class="card" data-reveal="80">
      <h3>📊 At a glance</h3>
      <div class="mapinfo__rows">
        <?php if ($uni['students'] > 0): ?>
          <div class="mapinfo__row"><span>Students</span><span><?= e(bd_num($uni['students'])) ?>+</span></div>
        <?php endif; ?>
        <?php if ($uni['faculties'] > 0): ?>
          <div class="mapinfo__row"><span>Faculties / schools</span><span><?= e((string) $uni['faculties']) ?></span></div>
        <?php endif; ?>
        <?php if ($uni['departments'] > 0): ?>
          <div class="mapinfo__row"><span>Departments</span><span><?= e((string) $uni['departments']) ?></span></div>
        <?php endif; ?>
        <div class="mapinfo__row"><span>Category</span><span><?= e($categoryLabel) ?> university</span></div>
      </div>
      <?php if ($district): ?>
        <p style="margin:1rem 0 0">
          <?= e($districtName) ?> is known for <?= e(lcfirst(rtrim($district['famous'], '.'))) ?>.
        </p>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($related): ?>
    <section class="section">
      <div class="section__head">
        <h2>More universities like this</h2>
        <p><a href="<?= e(bd_url('universities')) ?>" style="color:var(--green-300)">All universities →</a></p>
      </div>
      <div class="grid grid--3">
        <?php foreach ($related as $i => $other): ?>
          <a class="tile" href="<?= e(bd_university_url($other)) ?>" data-reveal="<?= $i * 40 ?>">
            <span class="tile__icon" aria-hidden="true">🎓</span>
            <h3><?= e($other['name']) ?></h3>
            <p style="margin-bottom:.7rem"><?= e($other['desc']) ?></p>
            <span class="badge"><?= $other['category'] === 'public' ? 'Public' : 'Private' ?></span>
            <span class="badge badge--red"><?= e($other['city']) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
  <?= bd_render_faq($faqs, 'Frequently asked questions about ' . $uni['name']) ?>
</article>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
