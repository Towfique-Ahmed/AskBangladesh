<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** All thanas (police stations) of Bangladesh, grouped by district. */

$thanas    = bd_data('thanas');
$districts = bd_districts();

$totalThanas = 0;
foreach ($thanas as $list) { $totalThanas += count($list); }

$seo = bd_seo(bd_page_seo('thana') + [
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Thanas'],
    ],
    'jsonld' => [
        bd_jsonld_item_list(
            'All Thanas of Bangladesh',
            array_keys($thanas),
            static fn (string $district): array => [
                '@type' => 'AdministrativeArea',
                'name'  => $district . ' District Thanas',
                'containedInPlace' => ['@type' => 'Country', 'name' => 'Bangladesh'],
            ]
        ),
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🏢 Police stations</span>
  <h1>All thanas of Bangladesh</h1>
  <p>
    Bangladesh has <?= bd_num($totalThanas) ?> thanas (police stations) across 64 districts.
    A thana is the smallest administrative unit of law enforcement — each covers an upazila or a
    metropolitan ward.
  </p>
</div>

<div class="statgrid" data-reveal>
  <div class="stat"><div class="stat__value" data-count-to="<?= $totalThanas ?>">0</div><div class="stat__label">Total thanas</div></div>
  <div class="stat"><div class="stat__value" data-count-to="64">0</div><div class="stat__label">Districts</div></div>
  <div class="stat"><div class="stat__value" data-count-to="8">0</div><div class="stat__label">Divisions</div></div>
</div>

<div class="card" data-reveal style="margin-bottom:1.4rem">
  <div class="field">
    <label for="thana-filter">Search thanas</label>
    <input type="search" class="input" id="thana-filter" data-filter-input
           placeholder="Try a thana name or district — &quot;Gulshan&quot;, &quot;Sylhet&quot;, &quot;Savar&quot;…">
  </div>
</div>

<?php foreach ($thanas as $district => $stationList):
    $districtData = null;
    foreach ($districts as $d) { if ($d['name'] === $district) { $districtData = $d; break; } }
?>
<section class="section" data-filter-item="<?= e($district . ' ' . implode(' ', $stationList)) ?>">
  <div class="section__head">
    <h2>
      <?php if ($districtData): ?>
        <a href="<?= e(bd_district_url($districtData)) ?>" style="color:var(--green-300)"><?= e($district) ?></a>
      <?php else: ?>
        <?= e($district) ?>
      <?php endif; ?>
      <span class="badge" style="margin-left:.5rem"><?= count($stationList) ?> thanas</span>
    </h2>
  </div>

  <div class="grid grid--4">
    <?php foreach ($stationList as $i => $thana): ?>
      <div class="card" data-reveal="<?= ($i % 8) * 30 ?>" style="padding:.8rem 1rem"
           data-filter-item="<?= e($district . ' ' . $thana) ?>">
        <h3 style="font-size:.92rem;margin:0">🏢 <?= e($thana) ?></h3>
        <div style="font-size:.78rem;color:var(--text-mute)"><?= e($district) ?> District</div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endforeach; ?>

<p data-filter-empty style="display:none;text-align:center;color:var(--text-mute);padding:3rem 1rem">
  No thana matches that search. Try a district name or a different spelling.
</p>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
