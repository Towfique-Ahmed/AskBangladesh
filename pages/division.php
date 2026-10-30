<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** A page per division, listing its districts. */

$division = bd_find_division($route['slug']);

if ($division === null) {
    http_response_code(404);
    require APP_ROOT . '/pages/404.php';
    return;
}

$name      = $division['name'];
$districts = bd_division_districts($name);
$places    = array_values(array_filter(
    bd_places('travel'),
    static function (array $p) use ($districts): bool {
        foreach ($districts as $d) {
            if ($d['name'] === $p['district']) { return true; }
        }
        return false;
    }
));

$seo = bd_seo([
    'title'       => tf('%s Division - Districts & Population', t($name)),
    'description' => tf('%s division of Bangladesh covers %s km² with about %s people across %s districts. Established in %s.', t($name), bd_n($division['area']), bd_lang() === 'bn' ? bd_bn_digits(bd_compact($division['population'])) : bd_compact($division['population']), bd_n(count($districts)), bd_lang() === 'bn' ? bd_bn_digits((string) $division['established']) : (string) $division['established']),
    'keywords'    => $name . ' division, districts of ' . $name . ', ' . $name . ' Bangladesh',
    'path'        => 'division/' . bd_slug($name),
    'type'        => 'article',
    'breadcrumbs' => [
        ['name' => t('Home'),      'url' => bd_url()],
        ['name' => t('Districts'), 'url' => bd_url('districts')],
        ['name' => tf('%s Division', t($name))],
    ],
    'jsonld' => [
        [
            '@context' => 'https://schema.org',
            '@type'    => 'AdministrativeArea',
            'name'     => tf('%s Division', t($name)),
            'url'      => bd_abs_url('division/' . bd_slug($name)),
            'geo'      => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => $division['lat'],
                'longitude' => $division['lon'],
            ],
            'containedInPlace' => ['@type' => 'Country', 'name' => 'Bangladesh'],
        ],
        [
            '@context'        => 'https://schema.org',
            '@type'           => 'ItemList',
            'name'            => 'Districts of ' . $name . ' Division',
            'numberOfItems'   => count($districts),
            'itemListElement' => array_map(
                static fn (int $i, array $d): array => [
                    '@type'    => 'ListItem',
                    'position' => $i + 1,
                    'name'     => $d['name'],
                    'url'      => SITE_URL . bd_district_url($d),
                ],
                array_keys($districts),
                $districts
            ),
        ],
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<article>
  <div class="pagehead">
    <span class="pagehead__eyebrow" style="color:<?= e($division['color']) ?>">🗺️ <?= e(t('Division')) ?></span>
    <h1><?= e(tf('%s Division', t($name))) ?></h1>
    <p>
      <?= e(tf('%s is one of the eight administrative divisions of Bangladesh. It was established in %s, covers %s km², and contains %s districts with a combined population of about %s.', t($name), bd_lang() === 'bn' ? bd_bn_digits((string) $division['established']) : (string) $division['established'], bd_n($division['area']), bd_n(count($districts)), bd_lang() === 'bn' ? bd_bn_digits(bd_compact($division['population'])) : bd_compact($division['population']))) ?>
    </p>
  </div>

  <div class="statgrid" data-reveal>
    <div class="stat"><div class="stat__value" data-count-to="<?= count($districts) ?>">0</div><div class="stat__label"><?= e(t('Districts')) ?></div></div>
    <div class="stat"><div class="stat__value" data-count-to="<?= $division['area'] ?>">0</div><div class="stat__label"><?= e(t('Area (km²)')) ?></div></div>
    <div class="stat"><div class="stat__value" data-count-to="<?= $division['population'] ?>">0</div><div class="stat__label"><?= e(t('Population')) ?></div></div>
    <div class="stat"><div class="stat__value"><?= e(bd_lang() === 'bn' ? bd_bn_digits((string) $division['established']) : (string) $division['established']) ?></div><div class="stat__label"><?= e(t('Established')) ?></div></div>
  </div>

  <section class="section">
    <div class="section__head">
      <h2><?= e(tf('Districts of %s Division', t($name))) ?></h2>
      <p><?= e(tf('All %s, with what each is known for.', bd_n(count($districts)))) ?></p>
    </div>
    <div class="grid grid--3">
      <?php foreach ($districts as $i => $district): ?>
        <a class="card" href="<?= e(bd_district_url($district)) ?>" data-reveal="<?= ($i % 9) * 35 ?>">
          <h3><?= e(bd_name($district)) ?></h3>
          <div style="color:var(--green-300);font-size:.9rem;margin-bottom:.5rem"><?= e(bd_lang() === 'bn' ? $district['name'] : $district['bn']) ?></div>
          <p style="margin-bottom:.4rem"><?= e(bd_l($district, 'famous')) ?></p>
          <?php if (!empty($district['economy'])): ?>
            <p style="margin-bottom:.7rem;color:var(--text-mute);font-size:.85rem"><?= e(bd_l($district, 'economy')) ?></p>
          <?php endif; ?>
          <span class="badge badge--gold"><?= bd_n($district['area'], 0) ?> <?= e(t('km²')) ?></span>
          <span class="badge badge--red"><?= e(bd_lang() === 'bn' ? bd_bn_digits(bd_compact($district['population'])) : bd_compact($district['population'])) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <?php if ($places): ?>
    <section class="section">
      <div class="section__head"><h2><?= e(tf('Places to visit in %s', t($name))) ?></h2></div>
      <div class="grid grid--3">
        <?php foreach ($places as $i => $place): ?>
          <a class="tile" href="<?= e(bd_travel_url($place)) ?>" data-reveal="<?= ($i % 9) * 40 ?>">
            <span class="tile__icon" aria-hidden="true"><?= $place['emoji'] ?></span>
            <h3><?= e(bd_name($place)) ?></h3>
            <p style="margin-bottom:.7rem"><?= e(bd_l($place, 'desc')) ?></p>
            <span class="badge"><?= e(bd_district_name($place['district'])) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <section class="section">
    <div class="section__head"><h2><?= e(t('The other divisions')) ?></h2></div>
    <div class="grid grid--4">
      <?php foreach (bd_divisions() as $other => $meta):
          if ($other === $name) { continue; } ?>
        <a class="card" href="<?= e(bd_division_url($other)) ?>">
          <div style="display:flex;align-items:center;gap:.5rem">
            <span style="width:10px;height:10px;border-radius:50%;background:<?= e($meta['color']) ?>"></span>
            <h3 style="margin:0;font-size:1rem"><?= e(t($other)) ?></h3>
          </div>
          <p style="margin-top:.4rem"><?= bd_n(count(bd_division_districts($other))) ?> <?= e(t('districts')) ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </section>
</article>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
