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
$uname = bd_name($uni);
$udesc = bd_l($uni, 'desc');
$dName = bd_district_name($districtName);
$estYear = bd_lang() === 'bn' ? bd_bn_digits((string) $uni['established']) : (string) $uni['established'];

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
    tf('When was %s established?', $uname)
        => tf('%s was established in %s.', $uname, $estYear),
    tf('Where is %s located?', $uname)
        => tf('%s is located in %s, %s district, Bangladesh.', $uname, $uni['city'], $dName),
    tf('Is %s a public or private university?', $uname)
        => tf('%s is a %s university in Bangladesh.', $uname, bd_lang() === 'bn' ? t($categoryLabel) : strtolower($categoryLabel)),
];

if (!empty($uni['admission'])) {
    $faqs[tf('How can I get admission into %s?', $uname)] = $uni['admission'];
}
if (!empty($uni['programs'])) {
    $faqs[tf('What programmes does %s offer?', $uname)]
        = tf('%s offers programmes including %s.', $uname, implode(', ', array_slice($uni['programs'], 0, 5)));
}

$seo = bd_seo([
    'title'       => tf('%s (%s) - Bangladesh Universities', $uname, $uni['short']),
    'description' => tf('%s. Established %s, located in %s, a %s university in Bangladesh.', rtrim($udesc, '.'), $estYear, $uni['city'], bd_lang() === 'bn' ? t($categoryLabel) : strtolower($categoryLabel)),
    'keywords'    => $uni['name'] . ', ' . $uni['short'] . ', ' . $districtName
        . ' university, ' . strtolower($categoryLabel) . ' university Bangladesh',
    'path'        => 'universities/' . bd_slug($uni['name']),
    'type'        => 'article',
    'breadcrumbs' => [
        ['name' => t('Home'),         'url' => bd_url()],
        ['name' => t('Universities'), 'url' => bd_url('universities')],
        ['name' => $uname],
    ],
    'jsonld' => [
        [
            '@context'    => 'https://schema.org',
            '@type'       => 'CollegeOrUniversity',
            'name'        => $uname,
            'alternateName' => $uni['short'],
            'description' => $udesc,
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
    <span class="pagehead__eyebrow"><?= e(tf('%s University', t($categoryLabel))) ?> · <?= e($dName) ?></span>
    <div style="font-size:3.4rem;line-height:1">🎓</div>
    <h1><?= e($uname) ?> <?php if ($uni['short'] !== ''): ?><span style="color:var(--text-mute);font-weight:400">(<?= e($uni['short']) ?>)</span><?php endif; ?></h1>
    <?php if ($uni['bn'] !== ''): ?><p style="font-size:1.15rem;margin:.2rem 0"><?= e(bd_lang() === 'bn' ? $uni['name'] : $uni['bn']) ?></p><?php endif; ?>
    <p style="font-size:1.08rem"><?= e($udesc) ?></p>
    <div style="display:flex;gap:.45rem;flex-wrap:wrap;margin-top:1rem">
      <span class="badge"><?= e(t($categoryLabel)) ?></span>
      <span class="badge badge--red"><?= e(tf('%s district', $dName)) ?></span>
      <span class="badge badge--gold"><?= e(t('Established')) ?> <?= e($estYear) ?></span>
    </div>
  </div>

  <?php if (!empty($uni['history'])): ?>
    <section class="section">
      <div class="section__head"><h2><?= e(t('History')) ?></h2></div>
      <p style="font-size:1rem"><?= e($uni['history']) ?></p>
      <?php if (!empty($uni['campus'])): ?>
        <h3 style="margin-top:1.2rem">🏫 <?= e(t('Campus')) ?></h3>
        <p style="font-size:1rem"><?= e($uni['campus']) ?></p>
      <?php endif; ?>
    </section>
  <?php endif; ?>

  <div class="grid grid--2">
    <div class="card" data-reveal>
      <h3>📍 <?= e(t('Location & overview')) ?></h3>
      <div class="mapinfo__rows">
        <div class="mapinfo__row"><span><?= e(t('City / area')) ?></span><span><?= e($uni['city']) ?></span></div>
        <div class="mapinfo__row"><span><?= e(t('District')) ?></span>
          <span><?php if ($district): ?><a href="<?= e(bd_district_url($district)) ?>"><?= e($dName) ?></a><?php else: ?><?= e($dName) ?><?php endif; ?></span></div>
        <?php if ($district): ?>
          <div class="mapinfo__row"><span><?= e(t('Division')) ?></span><span><a href="<?= e(bd_division_url($district['division'])) ?>"><?= e(t($district['division'])) ?></a></span></div>
        <?php endif; ?>
        <div class="mapinfo__row"><span><?= e(t('Type')) ?></span><span><?= e(t($uni['type'])) ?></span></div>
        <div class="mapinfo__row"><span><?= e(t('Established')) ?></span><span><?= e($estYear) ?></span></div>
        <?php if ($uni['motto'] !== ''): ?>
          <div class="mapinfo__row"><span><?= e(t('Motto')) ?></span><span><?= e($uni['motto']) ?></span></div>
        <?php endif; ?>
        <div class="mapinfo__row"><span><?= e(t('Coordinates')) ?></span><span><?= e(bd_lang() === 'bn' ? bd_bn_digits(number_format((float) $uni['lat'], 4) . '°উ, ' . number_format((float) $uni['lon'], 4) . '°পূ') : number_format((float) $uni['lat'], 4) . '°N, ' . number_format((float) $uni['lon'], 4) . '°E') ?></span></div>
      </div>
      <?php if ($uni['website'] !== ''): ?>
        <div style="margin-top:1rem">
          <a class="btn btn--ghost" href="<?= e($uni['website']) ?>" target="_blank" rel="noopener noreferrer"><?= e(t('Official website →')) ?></a>
        </div>
      <?php endif; ?>
    </div>

    <div class="card" data-reveal="80">
      <h3>📊 <?= e(t('At a glance')) ?></h3>
      <div class="mapinfo__rows">
        <?php if ($uni['students'] > 0): ?>
          <div class="mapinfo__row"><span><?= e(t('Students')) ?></span><span><?= e(bd_n($uni['students'])) ?>+</span></div>
        <?php endif; ?>
        <?php if ($uni['faculties'] > 0): ?>
          <div class="mapinfo__row"><span><?= e(t('Faculties / schools')) ?></span><span><?= e(bd_n((int) $uni['faculties'])) ?></span></div>
        <?php endif; ?>
        <?php if ($uni['departments'] > 0): ?>
          <div class="mapinfo__row"><span><?= e(t('Departments')) ?></span><span><?= e(bd_n((int) $uni['departments'])) ?></span></div>
        <?php endif; ?>
        <div class="mapinfo__row"><span><?= e(t('Category')) ?></span><span><?= e(tf('%s university', t($categoryLabel))) ?></span></div>
      </div>
      <?php if ($district): ?>
        <p style="margin:1rem 0 0">
          <?= e(tf('%s is known for %s.', $dName, lcfirst(rtrim(bd_l($district, 'famous'), '.')))) ?>
        </p>
      <?php endif; ?>
    </div>
  </div>

  <?php if (!empty($uni['admission']) || !empty($uni['programs'])): ?>
    <section class="section">
      <div class="section__head"><h2><?= e(t('Admission & programs')) ?></h2></div>
      <div class="grid grid--2">
        <?php if (!empty($uni['admission'])): ?>
          <div class="card" data-reveal>
            <h3>📝 <?= e(t('Admission')) ?></h3>
            <p style="font-size:1rem"><?= e($uni['admission']) ?></p>
          </div>
        <?php endif; ?>
        <?php if (!empty($uni['programs'])): ?>
          <div class="card" data-reveal="80">
            <h3>🎓 <?= e(t('Programs & faculties')) ?></h3>
            <div style="display:flex;gap:.4rem;flex-wrap:wrap">
              <?php foreach ($uni['programs'] as $program): ?>
                <span class="badge"><?= e($program) ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>

  <?php if (!empty($uni['notable_alumni'])): ?>
    <section class="section">
      <div class="section__head"><h2><?= e(t('Notable alumni')) ?></h2></div>
      <div class="card" data-reveal>
        <ul style="margin:0;padding-left:1.1rem;font-size:.95rem;line-height:1.7">
          <?php foreach ($uni['notable_alumni'] as $alum): ?>
            <li><?= e($alum) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>
  <?php endif; ?>

  <?php if ($related): ?>
    <section class="section">
      <div class="section__head">
        <h2><?= e(t('More universities like this')) ?></h2>
        <p><a href="<?= e(bd_url('universities')) ?>" style="color:var(--green-300)"><?= e(t('All universities →')) ?></a></p>
      </div>
      <div class="grid grid--3">
        <?php foreach ($related as $i => $other): ?>
          <a class="tile" href="<?= e(bd_university_url($other)) ?>" data-reveal="<?= $i * 40 ?>">
            <span class="tile__icon" aria-hidden="true">🎓</span>
            <h3><?= e(bd_name($other)) ?></h3>
            <p style="margin-bottom:.7rem"><?= e(bd_l($other, 'desc')) ?></p>
            <span class="badge"><?= e($other['category'] === 'public' ? t('Public') : t('Private')) ?></span>
            <span class="badge badge--red"><?= e($other['city']) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
  <?= bd_render_faq($faqs, tf('Frequently asked questions about %s', $uname)) ?>
</article>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
