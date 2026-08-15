<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Full-page global search across every dataset in the app. */

$query   = trim((string) ($_GET['q'] ?? ''));
$results = $query !== '' ? bd_search($query, 60) : [];

// Group hits by category so the page reads as an index, not a flat list.
$grouped = [];
foreach ($results as $result) {
    $grouped[$result['category']][] = $result;
}

$pageTitle       = $query !== '' ? 'Search: ' . $query : 'Search everything about Bangladesh';
$pageDescription = 'Search districts, travel places, rivers, mountains, government services, gold rates, prayer times and facts about Bangladesh from one box.';

require APP_ROOT . '/includes/layout/header.php';

$suggestions = ['Sylhet', 'Cox’s Bazar', 'gold price', 'prayer times', 'Padma Bridge',
                'Sundarbans', 'e-passport', '999', 'Tazing Dong', 'hilsa', 'Pohela Boishakh', 'metro rail'];
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🔍 Global search</span>
  <h1><?= $query !== '' ? 'Results for “' . e($query) . '”' : 'Search everything' ?></h1>
  <p>
    <?php if ($query !== ''): ?>
      Found <strong><?= count($results) ?></strong>
      <?= count($results) === 1 ? 'match' : 'matches' ?> across
      <?= count($grouped) ?> <?= count($grouped) === 1 ? 'category' : 'categories' ?>.
    <?php else: ?>
      One box across every district, mountain, river, road, destination, government service,
      festival, hotline and fact in the app. Press <kbd>/</kbd> anywhere to jump to the search bar.
    <?php endif; ?>
  </p>
</div>

<form class="card" data-reveal method="get" action="index.php" style="margin-bottom:1.6rem">
  <input type="hidden" name="p" value="search">
  <div class="field">
    <label for="page-search">Your search</label>
    <input type="search" class="input" id="page-search" name="q" value="<?= e($query) ?>"
           placeholder="A district, a river, “gold”, “visa”, “999”, “Kuakata”…" autofocus>
  </div>
  <div style="display:flex;gap:.6rem;margin-top:.9rem;flex-wrap:wrap">
    <button type="submit" class="btn btn--primary">Search</button>
    <?php if ($query !== ''): ?>
      <a class="btn btn--ghost" href="<?= e(bd_url('search')) ?>">Clear</a>
    <?php endif; ?>
  </div>
</form>

<?php if ($query === ''): ?>

  <section class="section" style="margin-top:1.5rem">
    <div class="section__head"><h2>Try one of these</h2></div>
    <div class="chips">
      <?php foreach ($suggestions as $suggestion): ?>
        <a class="chip" href="<?= e(bd_url('search', ['q' => $suggestion])) ?>"><?= e($suggestion) ?></a>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="section">
    <div class="section__head">
      <h2>Or browse by section</h2>
      <p>Everything indexed here is also reachable from these pages.</p>
    </div>
    <div class="grid grid--3">
      <?php foreach (bd_tools() as $i => $tool): ?>
        <a class="tile" href="<?= e($tool['url']) ?>" data-reveal="<?= ($i % 9) * 40 ?>">
          <span class="tile__icon" aria-hidden="true"><?= $tool['emoji'] ?></span>
          <h3><?= e($tool['name']) ?></h3>
          <p><?= e($tool['desc']) ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

<?php elseif (!$results): ?>

  <div class="card" data-reveal style="text-align:center;padding:3rem 1.5rem">
    <div style="font-size:3rem">🤔</div>
    <h2>Nothing matched “<?= e($query) ?>”</h2>
    <p style="color:var(--text-dim)">
      Try a shorter phrase, a district name, or one of these:
    </p>
    <div class="chips" style="justify-content:center;margin-top:1rem">
      <?php foreach (array_slice($suggestions, 0, 8) as $suggestion): ?>
        <a class="chip" href="<?= e(bd_url('search', ['q' => $suggestion])) ?>"><?= e($suggestion) ?></a>
      <?php endforeach; ?>
    </div>
  </div>

<?php else: ?>

  <?php $offset = 0; foreach ($grouped as $category => $items): ?>
    <section class="section" style="margin-top:2rem">
      <div class="section__head">
        <h2><?= e($category) ?></h2>
        <p><?= count($items) ?> <?= count($items) === 1 ? 'result' : 'results' ?></p>
      </div>

      <div class="grid grid--2">
        <?php foreach ($items as $item): ?>
          <a class="card" href="<?= e($item['url']) ?>" data-reveal="<?= (($offset++) % 8) * 35 ?>">
            <div style="display:flex;gap:.8rem;align-items:flex-start">
              <span style="font-size:1.6rem;line-height:1.2"><?= e($item['icon']) ?></span>
              <span>
                <h3 style="margin:0 0 .15rem"><?= e($item['title']) ?></h3>
                <?php if ($item['subtitle'] !== ''): ?>
                  <div style="font-size:.82rem;color:var(--green-300);margin-bottom:.4rem"><?= e($item['subtitle']) ?></div>
                <?php endif; ?>
                <p><?= e(mb_strimwidth($item['body'], 0, 190, '…')) ?></p>
              </span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endforeach; ?>

<?php endif; ?>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
