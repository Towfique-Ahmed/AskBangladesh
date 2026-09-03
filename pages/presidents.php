<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Every President of Bangladesh since independence in 1971. */

$presidents = bd_leaders('presidents');
$leader     = bd_current_leader('presidents');

$seo = bd_seo(bd_page_seo('presidents') + [
    'type'        => 'article',
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Presidents'],
    ],
    'jsonld' => [
        bd_jsonld_person_list('Presidents of Bangladesh', $presidents),
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

<?php if ($leader): ?>
<section class="section" style="margin-top:0">
  <div class="section__head">
    <h2>Current President</h2>
  </div>
  <article class="card" data-reveal>
    <span class="badge">In office</span>
    <h3 style="margin-top:.5rem">🎖️ <?= e($leader['name']) ?></h3>
    <div class="mapinfo__rows">
      <div class="mapinfo__row"><span>Term</span><span><?= e($leader['term']) ?></span></div>
      <div class="mapinfo__row"><span>Seat</span><span>Bangabhaban, Dhaka</span></div>
    </div>
    <?php if ($leader['note'] !== ''): ?>
      <p style="margin-top:.7rem"><?= e($leader['note']) ?></p>
    <?php endif; ?>
  </article>
</section>
<?php endif; ?>

<section class="section">
  <div class="section__head">
    <h2>Full list, 1971 – present</h2>
    <p><?= count($presidents) ?> entries, including acting heads of state.</p>
  </div>

  <div class="tablewrap" data-reveal>
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Role</th>
          <th>Term</th>
          <th>Notes</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($presidents as $p): ?>
          <tr>
            <td><strong><?= e($p['name']) ?></strong></td>
            <td><span class="badge"><?= e($p['role']) ?></span></td>
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
    Sources count the presidents differently depending on how they treat acting and repeat terms,
    so this list is ordered chronologically rather than numbered. Dates from the most turbulent
    years (1971–1991) also vary slightly between sources. See the
    <a href="<?= e(bd_url('government')) ?>">government page</a> for official portals, and the
    <a href="<?= e(bd_url('prime-ministers')) ?>">prime ministers list</a> for heads of government.
  </p>
</div>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
