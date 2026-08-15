<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Not found. */

$pageTitle       = 'Page not found';
$pageDescription = 'That page does not exist on AskBangladesh.';

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="card" data-reveal style="text-align:center;padding:4rem 1.5rem;margin-top:2rem">
  <div style="font-size:4rem">🧭</div>
  <h1 style="font-size:clamp(2rem,6vw,3.4rem)">404</h1>
  <p style="color:var(--text-dim);max-width:52ch;margin:0 auto 1.6rem">
    That page is not on the map. Nothing is lost for long in a country with 907 rivers — try the
    search box above, or start again from the front page.
  </p>
  <div style="display:flex;gap:.7rem;justify-content:center;flex-wrap:wrap">
    <a class="btn btn--primary" href="<?= e(bd_url('home')) ?>">🏠 Back to the front page</a>
    <a class="btn" href="<?= e(bd_url('search')) ?>">🔍 Search everything</a>
    <a class="btn" href="<?= e(bd_url('map')) ?>">🗺️ Open the map</a>
  </div>
</div>

<section class="section">
  <div class="section__head"><h2>Popular sections</h2></div>
  <div class="grid grid--3">
    <?php foreach (array_slice(bd_tools(), 0, 6) as $i => $tool): ?>
      <a class="tile" href="<?= e($tool['url']) ?>" data-reveal="<?= $i * 45 ?>">
        <span class="tile__icon" aria-hidden="true"><?= $tool['emoji'] ?></span>
        <h3><?= e($tool['name']) ?></h3>
        <p><?= e($tool['desc']) ?></p>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
