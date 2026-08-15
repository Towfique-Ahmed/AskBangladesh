<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** The major rivers of Bangladesh. */

$rivers = bd_places('rivers');
$geo    = bd_places('geography');
usort($rivers, static fn (array $a, array $b): int => $b['length'] <=> $a['length']);
$longest = $rivers[0]['length'] ?? 1;

$seo = bd_seo(bd_page_seo('rivers') + [
    'breadcrumbs' => [
        ['name' => 'Home',      'url' => bd_url()],
        ['name' => 'Geography', 'url' => bd_url('geography')],
        ['name' => 'Rivers'],
    ],
]);
$seo['jsonld'] = [
    bd_jsonld_faq([
        'How many rivers are there in Bangladesh?'
            => 'Bangladesh has about ' . bd_num($geo['rivers_count']) . ' rivers, which is why it is often called the land of rivers.',
        'What are the three main rivers of Bangladesh?'
            => 'The Padma, the Jamuna and the Meghna. Together they form the Ganges–Brahmaputra–Meghna delta, the largest river delta in the world.',
        'Which is the longest river in Bangladesh?'
            => ($rivers[0]['name'] ?? 'The Padma') . ' runs about ' . bd_num($longest) . ' km inside Bangladesh.',
    ]),
];

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🌊 Waters</span>
  <h1>Rivers of Bangladesh</h1>
  <p>
    About <?= bd_num($geo['rivers_count']) ?> rivers thread through Bangladesh, which is built
    almost entirely on the <?= e($geo['delta']) ?> The Padma, Jamuna and Meghna carry the
    combined flow of the Himalaya to the Bay of Bengal.
  </p>
</div>

<div class="statgrid" data-reveal>
  <div class="stat"><div class="stat__value" data-count-to="<?= $geo['rivers_count'] ?>">0</div><div class="stat__label">Rivers</div></div>
  <div class="stat"><div class="stat__value" data-count-to="<?= $geo['coastline_km'] ?>">0</div><div class="stat__label">Coastline (km)</div></div>
  <div class="stat"><div class="stat__value" data-count-to="<?= $geo['water_km2'] ?>">0</div><div class="stat__label">Water area (km²)</div></div>
</div>

<section class="section">
  <div class="grid grid--2">
    <?php foreach ($rivers as $i => $r): ?>
      <article class="card" data-reveal="<?= ($i % 6) * 40 ?>">
        <div style="display:flex;justify-content:space-between;align-items:baseline;gap:1rem">
          <h2 style="margin:0;font-size:1.1rem">🌊 <?= e($r['name']) ?></h2>
          <strong style="font-family:var(--mono);color:var(--green-300)"><?= bd_num($r['length']) ?> km</strong>
        </div>
        <div style="color:var(--green-300);font-size:.95rem"><?= e($r['bn']) ?></div>
        <div class="bar" style="margin:.7rem 0">
          <div class="bar__fill" data-width="<?= round($r['length'] / max(1, $longest) * 100) ?>"
               style="background:linear-gradient(90deg,var(--teal-500),var(--green-300))"></div>
        </div>
        <p><?= e($r['note']) ?></p>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="section">
  <div class="grid grid--2">
    <a class="card" href="<?= e(bd_url('geography')) ?>" data-reveal>
      <h3>🌏 Geography of Bangladesh</h3>
      <p>The delta, the climate, the six seasons and the extreme points of the country.</p>
      <span class="tile__arrow">Open geography →</span>
    </a>
    <a class="card" href="<?= e(bd_url('mountains')) ?>" data-reveal="80">
      <h3>⛰️ Mountains of Bangladesh</h3>
      <p>Saka Haphong, Keokradong, Tazing Dong and every other peak, ranked by height.</p>
      <span class="tile__arrow">Open mountains →</span>
    </a>
  </div>
</section>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
