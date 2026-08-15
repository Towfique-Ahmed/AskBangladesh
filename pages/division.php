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
    'title'       => $name . ' Division — Districts & Population',
    'description' => $name . ' division of Bangladesh covers ' . bd_num($division['area'])
        . ' km² with about ' . bd_compact($division['population']) . ' people across '
        . count($districts) . ' districts. Established in ' . $division['established'] . '.',
    'keywords'    => $name . ' division, districts of ' . $name . ', ' . $name . ' Bangladesh',
    'path'        => 'division/' . bd_slug($name),
    'type'        => 'article',
    'breadcrumbs' => [
        ['name' => 'Home',      'url' => bd_url()],
        ['name' => 'Districts', 'url' => bd_url('districts')],
        ['name' => $name . ' Division'],
    ],
    'jsonld' => [
        [
            '@context' => 'https://schema.org',
            '@type'    => 'AdministrativeArea',
            'name'     => $name . ' Division',
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
    <span class="pagehead__eyebrow" style="color:<?= e($division['color']) ?>">🗺️ Division</span>
    <h1><?= e($name) ?> Division</h1>
    <p>
      <?= e($name) ?> is one of the eight administrative divisions of Bangladesh. It was
      established in <?= $division['established'] ?>, covers <?= bd_num($division['area']) ?> km²,
      and contains <?= count($districts) ?> districts with a combined population of about
      <?= bd_compact($division['population']) ?>.
    </p>
  </div>

  <div class="statgrid" data-reveal>
    <div class="stat"><div class="stat__value" data-count-to="<?= count($districts) ?>">0</div><div class="stat__label">Districts</div></div>
    <div class="stat"><div class="stat__value" data-count-to="<?= $division['area'] ?>">0</div><div class="stat__label">Area (km²)</div></div>
    <div class="stat"><div class="stat__value" data-count-to="<?= $division['population'] ?>">0</div><div class="stat__label">Population</div></div>
    <div class="stat"><div class="stat__value"><?= $division['established'] ?></div><div class="stat__label">Established</div></div>
  </div>

  <section class="section">
    <div class="section__head">
      <h2>Districts of <?= e($name) ?> Division</h2>
      <p>All <?= count($districts) ?>, with what each is known for.</p>
    </div>
    <div class="grid grid--3">
      <?php foreach ($districts as $i => $district): ?>
        <a class="card" href="<?= e(bd_district_url($district)) ?>" data-reveal="<?= ($i % 9) * 35 ?>">
          <h3><?= e($district['name']) ?></h3>
          <div style="color:var(--green-300);font-size:.9rem;margin-bottom:.5rem"><?= e($district['bn']) ?></div>
          <p style="margin-bottom:.7rem"><?= e($district['famous']) ?></p>
          <span class="badge badge--gold"><?= bd_num($district['area'], 0) ?> km²</span>
          <span class="badge badge--red"><?= bd_compact($district['population']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <?php if ($places): ?>
    <section class="section">
      <div class="section__head"><h2>Places to visit in <?= e($name) ?></h2></div>
      <div class="grid grid--3">
        <?php foreach ($places as $i => $place): ?>
          <a class="tile" href="<?= e(bd_travel_url($place)) ?>" data-reveal="<?= ($i % 9) * 40 ?>">
            <span class="tile__icon" aria-hidden="true"><?= $place['emoji'] ?></span>
            <h3><?= e($place['name']) ?></h3>
            <p style="margin-bottom:.7rem"><?= e($place['desc']) ?></p>
            <span class="badge"><?= e($place['district']) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <section class="section">
    <div class="section__head"><h2>The other divisions</h2></div>
    <div class="grid grid--4">
      <?php foreach (bd_divisions() as $other => $meta):
          if ($other === $name) { continue; } ?>
        <a class="card" href="<?= e(bd_division_url($other)) ?>">
          <div style="display:flex;align-items:center;gap:.5rem">
            <span style="width:10px;height:10px;border-radius:50%;background:<?= e($meta['color']) ?>"></span>
            <h3 style="margin:0;font-size:1rem"><?= e($other) ?></h3>
          </div>
          <p style="margin-top:.4rem"><?= count(bd_division_districts($other)) ?> districts</p>
        </a>
      <?php endforeach; ?>
    </div>
  </section>
</article>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
