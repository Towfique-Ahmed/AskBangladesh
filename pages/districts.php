<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** All 64 districts, filterable by division and free text. */

$districts = bd_districts();
$divisions = bd_divisions();
$division  = trim((string) ($_GET['division'] ?? ''));

$seo = bd_seo(bd_page_seo('districts') + [
    'breadcrumbs' => [
        ['name' => t('Home'), 'url' => bd_url()],
        ['name' => t('Districts')],
    ],
    'jsonld' => [
        bd_jsonld_item_list(
            'All 64 Districts of Bangladesh',
            $districts,
            static fn (array $d): array => [
                '@type' => 'AdministrativeArea',
                'name'  => $d['name'] . ' District',
                'url'   => bd_abs_url('district/' . bd_slug($d['name'])),
                'containedInPlace' => ['@type' => 'AdministrativeArea', 'name' => $d['division'] . ' Division'],
            ]
        ),
    ],
]);

require APP_ROOT . '/includes/layout/header.php';

?>

<div class="pagehead">
  <span class="pagehead__eyebrow">📍 <?= e(t('Administrative Bangladesh')) ?></span>
  <h1><?= e(t('All 64 districts')) ?></h1>
  <p>
    <?= e(tf('Bangladesh is divided into 8 divisions, 64 districts (zila), %s upazilas and %s union councils. Filter by division or search for a district, a Bangla name or what a place is famous for.', bd_n(bd_nation('profile')['upazilas']), bd_n(bd_nation('profile')['unions']))) ?>
  </p>
</div>


<div class="card" data-reveal style="margin-bottom:1.4rem">
  <div class="field" style="margin-bottom:.9rem">
    <label for="district-filter"><?= e(t('Search districts')) ?></label>
    <input type="search" class="input" id="district-filter" data-filter-input
           placeholder="<?= e(t('Try “Sylhet”, “tea”, “beach”, “ঢাকা” or “mango”…')) ?>">
  </div>

  <div class="chips">
    <button type="button" class="chip<?= $division === '' ? ' is-active' : '' ?>" data-filter-chip="all"><?= e(t('All divisions')) ?></button>
    <?php foreach ($divisions as $name => $meta): ?>
      <button type="button" class="chip<?= strcasecmp($division, $name) === 0 ? ' is-active' : '' ?>"
              data-filter-chip="<?= e($name) ?>"><?= e(t($name)) ?></button>
    <?php endforeach; ?>
  </div>

  <p style="margin:.9rem 0 0;color:var(--text-mute);font-size:.85rem">
    <?= e(t('Showing')) ?> <strong data-filter-count><?= bd_n(64) ?></strong> <?= e(t('of')) ?> <?= bd_n(64) ?> <?= e(t('districts.')) ?>
  </p>
</div>

<div class="grid grid--3">
  <?php foreach ($districts as $i => $district):
      $color = $divisions[$district['division']]['color'] ?? '#00a651';
      $needle = $district['name'] . ' ' . $district['bn'] . ' ' . $district['division'] . ' ' . $district['famous'] . ' ' . ($district['famous_bn'] ?? '')
          . ' ' . ($district['economy'] ?? '') . ' ' . ($district['culture'] ?? '');
  ?>
    <a class="card" href="<?= e(bd_district_url($district)) ?>" data-reveal="<?= ($i % 9) * 30 ?>"
             data-filter-item="<?= e($needle) ?>"
             data-filter-group="<?= e($district['division']) ?>">
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem">
        <span style="width:10px;height:10px;border-radius:50%;background:<?= e($color) ?>;flex-shrink:0"></span>
        <h3 style="margin:0"><?= e(bd_name($district)) ?></h3>
      </div>
      <div style="color:var(--green-300);font-size:.92rem;margin-bottom:.5rem"><?= e(bd_lang() === 'bn' ? $district['name'] : $district['bn']) ?></div>
      <p style="margin-bottom:.4rem"><?= e(bd_l($district, 'famous')) ?></p>
      <?php if (!empty($district['economy'])): ?>
        <p style="margin-bottom:.8rem;color:var(--text-mute);font-size:.85rem"><?= e(bd_l($district, 'economy')) ?></p>
      <?php endif; ?>
      <div style="display:flex;gap:.4rem;flex-wrap:wrap">
        <span class="badge"><?= e(t($district['division'])) ?></span>
        <span class="badge badge--gold"><?= bd_n($district['area'], 0) ?> <?= e(t('km²')) ?></span>
        <span class="badge badge--red"><?= e(bd_lang() === 'bn' ? bd_bn_digits(bd_compact($district['population']) ) : bd_compact($district['population'])) ?> <?= e(t('people')) ?></span>
      </div>
      <span class="tile__arrow"><?= e(t('Open')) ?> <?= e(bd_name($district)) ?> →</span>
    </a>
  <?php endforeach; ?>
</div>

<p data-filter-empty style="display:none;text-align:center;color:var(--text-mute);padding:3rem 1rem">
  <?= e(t('No district matches that search. Try a division name, a Bangla name, or something a place is known for.')) ?>
</p>

<!-- --------------------------------------------------------- division table -->
<section class="section">
  <div class="section__head"><h2><?= e(t('Divisions compared')) ?></h2></div>
  <div class="tablewrap" data-reveal>
    <table>
      <thead>
        <tr><th><?= e(t('Division')) ?></th><th><?= e(t('Established')) ?></th><th class="num"><?= e(t('Area (km²)')) ?></th><th class="num"><?= e(t('Population')) ?></th><th class="num"><?= e(t('Districts')) ?></th></tr>
      </thead>
      <tbody>
        <?php foreach ($divisions as $name => $meta):
            $count = count(array_filter($districts, static fn (array $d): bool => $d['division'] === $name));
        ?>
          <tr>
            <td>
              <span style="display:inline-block;width:9px;height:9px;border-radius:50%;background:<?= e($meta['color']) ?>;margin-right:.5rem"></span>
              <a href="<?= e(bd_division_url($name)) ?>"><?= e(t($name)) ?></a>
            </td>
            <td><?= e(bd_n($meta['established'], 0) === '' ? '' : (bd_lang() === 'bn' ? bd_bn_digits((string) $meta['established']) : (string) $meta['established'])) ?></td>
            <td class="num"><?= bd_n($meta['area']) ?></td>
            <td class="num"><?= bd_n($meta['population']) ?></td>
            <td class="num"><?= bd_n($count) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
