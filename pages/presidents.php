<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Every President of Bangladesh since independence in 1971. */

$presidents = bd_leaders('presidents');

$seo = bd_seo(bd_page_seo('presidents') + [
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Presidents'],
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🎖️ Head of state</span>
  <h1>Presidents of Bangladesh</h1>
  <p>
    Every President of Bangladesh since the Liberation War of 1971, in order. The President is
    the ceremonial head of state, elected by the Jatiya Sangsad (parliament) for a five-year term.
  </p>
</div>

<section class="section" style="margin-top:0">
  <div class="section__head">
    <h2>Full list, 1971 – present</h2>
    <p><?= count($presidents) ?> entries, including acting heads of state.</p>
  </div>

  <div class="tablewrap" data-reveal>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Term</th>
          <th>Notes</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($presidents as $p): ?>
          <tr>
            <td><?= e((string) $p['no']) ?></td>
            <td><strong><?= e($p['name']) ?></strong></td>
            <td><?= e($p['term']) ?></td>
            <td><?= e($p['note']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<div class="notice" data-reveal>
  <span>ℹ️</span>
  <p style="margin:0">
    Dates for the earliest and most turbulent years (1971–1991) vary slightly between sources;
    the ones above reflect the commonly cited swearing-in and departure dates. See the
    <a href="<?= e(bd_url('government')) ?>">government page</a> for the current officeholders and
    official portals.
  </p>
</div>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
