<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** All 64 districts, filterable by division and free text. */

$districts = bd_districts();
$divisions = bd_divisions();
$division  = trim((string) ($_GET['division'] ?? ''));

$seo = bd_seo(bd_page_seo('districts') + [
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Districts'],
    ],
]);

require APP_ROOT . '/includes/layout/header.php';

?>

<div class="pagehead">
  <span class="pagehead__eyebrow">📍 Administrative Bangladesh</span>
  <h1>All 64 districts</h1>
  <p>
    Bangladesh is divided into 8 divisions, 64 districts (zila), <?= bd_num(bd_nation('profile')['upazilas']) ?>
    upazilas and <?= bd_num(bd_nation('profile')['unions']) ?> union councils. Filter by division or
    search for a district, a Bangla name or what a place is famous for.
  </p>
</div>


<div class="card" data-reveal style="margin-bottom:1.4rem">
  <div class="field" style="margin-bottom:.9rem">
    <label for="district-filter">Search districts</label>
    <input type="search" class="input" id="district-filter" data-filter-input
           placeholder="Try “Sylhet”, “tea”, “beach”, “ঢাকা” or “mango”…">
  </div>

  <div class="chips">
    <button type="button" class="chip<?= $division === '' ? ' is-active' : '' ?>" data-filter-chip="all">All divisions</button>
    <?php foreach ($divisions as $name => $meta): ?>
      <button type="button" class="chip<?= strcasecmp($division, $name) === 0 ? ' is-active' : '' ?>"
              data-filter-chip="<?= e($name) ?>"><?= e($name) ?></button>
    <?php endforeach; ?>
  </div>

  <p style="margin:.9rem 0 0;color:var(--text-mute);font-size:.85rem">
    Showing <strong data-filter-count>64</strong> of 64 districts.
  </p>
</div>

<div class="grid grid--3">
  <?php foreach ($districts as $i => $district):
      $color = $divisions[$district['division']]['color'] ?? '#00a651';
      $needle = $district['name'] . ' ' . $district['bn'] . ' ' . $district['division'] . ' ' . $district['famous'];
  ?>
    <a class="card" href="<?= e(bd_district_url($district)) ?>" data-reveal="<?= ($i % 9) * 30 ?>"
             data-filter-item="<?= e($needle) ?>"
             data-filter-group="<?= e($district['division']) ?>">
      <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.3rem">
        <span style="width:10px;height:10px;border-radius:50%;background:<?= e($color) ?>;flex-shrink:0"></span>
        <h3 style="margin:0"><?= e($district['name']) ?></h3>
      </div>
      <div style="color:var(--green-300);font-size:.92rem;margin-bottom:.5rem"><?= e($district['bn']) ?></div>
      <p style="margin-bottom:.8rem"><?= e($district['famous']) ?></p>
      <div style="display:flex;gap:.4rem;flex-wrap:wrap">
        <span class="badge"><?= e($district['division']) ?></span>
        <span class="badge badge--gold"><?= bd_num($district['area'], 0) ?> km²</span>
        <span class="badge badge--red"><?= bd_compact($district['population']) ?> people</span>
      </div>
      <span class="tile__arrow">Open <?= e($district['name']) ?> →</span>
    </a>
  <?php endforeach; ?>
</div>

<p data-filter-empty style="display:none;text-align:center;color:var(--text-mute);padding:3rem 1rem">
  No district matches that search. Try a division name, a Bangla name, or something a place is known for.
</p>

<!-- --------------------------------------------------------- division table -->
<section class="section">
  <div class="section__head"><h2>Divisions compared</h2></div>
  <div class="tablewrap" data-reveal>
    <table>
      <thead>
        <tr><th>Division</th><th>Established</th><th class="num">Area (km²)</th><th class="num">Population</th><th class="num">Districts</th></tr>
      </thead>
      <tbody>
        <?php foreach ($divisions as $name => $meta):
            $count = count(array_filter($districts, static fn (array $d): bool => $d['division'] === $name));
        ?>
          <tr>
            <td>
              <span style="display:inline-block;width:9px;height:9px;border-radius:50%;background:<?= e($meta['color']) ?>;margin-right:.5rem"></span>
              <a href="<?= e(bd_division_url($name)) ?>"><?= e($name) ?></a>
            </td>
            <td><?= $meta['established'] ?></td>
            <td class="num"><?= bd_num($meta['area']) ?></td>
            <td class="num"><?= bd_num($meta['population']) ?></td>
            <td class="num"><?= $count ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
