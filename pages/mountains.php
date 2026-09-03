<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Every peak in Bangladesh, ranked by height. */

$mountains = bd_places('mountains');
usort($mountains, static fn (array $a, array $b): int => $b['height'] <=> $a['height']);
$tallest = $mountains[0] ?? null;

// One source for both the FAQPage markup and the visible block below it.
$faqs = [
    'What is the highest mountain in Bangladesh?'
        => ($tallest['name'] ?? 'Saka Haphong') . ' is the highest peak in Bangladesh at '
           . bd_num($tallest['height'] ?? 1052) . ' metres, in Bandarban district near the Myanmar border.',
    'Where are the mountains of Bangladesh?'
        => 'Almost every peak in Bangladesh stands in the Chittagong Hill Tracts — the districts of Bandarban, Rangamati and Khagrachhari in the south-east.',
    'Is Keokradong the highest peak of Bangladesh?'
        => 'No. Keokradong at 986 m was long believed to be the highest, but later surveys established Saka Haphong (Mowdok Mual) at 1,052 m as the true summit.',
];

$seo = bd_seo(bd_page_seo('mountains') + [
    'type'        => 'article',
    'breadcrumbs' => [
        ['name' => 'Home',      'url' => bd_url()],
        ['name' => 'Geography', 'url' => bd_url('geography')],
        ['name' => 'Mountains'],
    ],
]);
$seo['jsonld'] = [
    [
        '@context'        => 'https://schema.org',
        '@type'           => 'ItemList',
        'name'            => 'Highest mountains of Bangladesh',
        'numberOfItems'   => count($mountains),
        'itemListElement' => array_map(
            static fn (int $i, array $m): array => [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'item'     => [
                    '@type'       => 'Mountain',
                    'name'        => $m['name'],
                    'description' => $m['note'],
                    'elevation'   => $m['height'] . ' m',
                    'geo' => ['@type' => 'GeoCoordinates', 'latitude' => $m['lat'], 'longitude' => $m['lon']],
                ],
            ],
            array_keys($mountains),
            $mountains
        ),
    ],
    bd_jsonld_faq($faqs),
];

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">⛰️ Peaks</span>
  <h1>Mountains of Bangladesh</h1>
  <p>
    Bangladesh is overwhelmingly flat delta, but its south-eastern corner rises into the
    Chittagong Hill Tracts. Every significant peak in the country stands there, and the
    highest — <?= e($tallest['name'] ?? '') ?> at <?= bd_num($tallest['height'] ?? 0) ?> m —
    sits on the ridge along the Myanmar border.
  </p>
</div>

<div class="grid grid--2">
  <?php foreach ($mountains as $i => $m): ?>
    <article class="card" data-reveal="<?= ($i % 6) * 45 ?>">
      <div style="display:flex;justify-content:space-between;align-items:baseline;gap:1rem">
        <h2 style="margin:0;font-size:1.1rem">
          <span class="badge">#<?= $i + 1 ?></span> <?= e($m['name']) ?>
        </h2>
        <strong style="font-family:var(--mono);color:var(--green-300);font-size:1.2rem"><?= bd_num($m['height']) ?> m</strong>
      </div>
      <?php if ($m['alt'] !== ''): ?>
        <div style="font-size:.82rem;color:var(--text-mute)">also called <?= e($m['alt']) ?></div>
      <?php endif; ?>
      <div class="bar" style="margin:.7rem 0">
        <div class="bar__fill" data-width="<?= round($m['height'] / max(1, $tallest['height']) * 100) ?>"
             style="background:linear-gradient(90deg,var(--gold-500),var(--red-500))"></div>
      </div>
      <p><?= e($m['note']) ?></p>
      <div style="margin-top:.6rem">
        <span class="badge badge--gold"><?= e($m['range']) ?></span>
        <?php
        $peakDistrict = null;
        foreach (bd_districts() as $d) { if ($d['name'] === $m['district']) { $peakDistrict = $d; break; } }
        ?>
        <?php if ($peakDistrict): ?>
          <a class="badge badge--red" href="<?= e(bd_district_url($peakDistrict)) ?>"><?= e($m['district']) ?></a>
        <?php else: ?>
          <span class="badge badge--red"><?= e($m['district']) ?></span>
        <?php endif; ?>
      </div>
    </article>
  <?php endforeach; ?>
</div>

<section class="section">
  <div class="grid grid--2">
    <a class="card" href="<?= e(bd_url('geography')) ?>" data-reveal>
      <h3>🌏 Geography of Bangladesh</h3>
      <p>The delta, the climate, the six seasons and the four extreme points of the country.</p>
      <span class="tile__arrow">Open geography →</span>
    </a>
    <a class="card" href="<?= e(bd_url('rivers')) ?>" data-reveal="80">
      <h3>🌊 Rivers of Bangladesh</h3>
      <p>The Padma, Jamuna and Meghna, and the 900 other rivers that shape the country.</p>
      <span class="tile__arrow">Open rivers →</span>
    </a>
  </div>
</section>

<?= bd_render_faq($faqs, 'Questions about the mountains of Bangladesh') ?>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
