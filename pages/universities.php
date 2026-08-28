<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Best public and private universities of Bangladesh, filterable by category. */

$universities = bd_all_universities();

$seo = bd_seo(bd_page_seo('universities') + [
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Universities'],
    ],
    'jsonld' => [
        bd_jsonld_item_list(
            'Top Universities in Bangladesh',
            $universities,
            static fn (array $u): array => [
                '@type'          => 'CollegeOrUniversity',
                'name'           => $u['name'],
                'description'    => $u['desc'],
                'url'            => bd_abs_url('universities/' . bd_slug($u['name'])),
                'foundingDate'   => (string) $u['established'],
                'address'        => ['@type' => 'PostalAddress', 'addressLocality' => $u['city'], 'addressCountry' => 'BD'],
            ]
        ),
    ],
]);

require APP_ROOT . '/includes/layout/header.php';

?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🎓 Higher Education</span>
  <h1>Universities of Bangladesh</h1>
  <p>
    The 20 best public universities and 20 best private universities in Bangladesh — from the
    century-old University of Dhaka to fast-growing private campuses in Dhaka, Chattogram and Sylhet.
  </p>
</div>

<div class="card" data-reveal style="margin-bottom:1.4rem">
  <div class="field" style="margin-bottom:.9rem">
    <label for="university-filter">Search universities</label>
    <input type="search" class="input" id="university-filter" data-filter-input
           placeholder="Try “Dhaka”, “engineering”, “BRAC” or “Chattogram”…">
  </div>
  <div class="chips">
    <button type="button" class="chip is-active" data-filter-chip="all">Everything</button>
    <button type="button" class="chip" data-filter-chip="public">Public</button>
    <button type="button" class="chip" data-filter-chip="private">Private</button>
  </div>
  <p style="margin:.9rem 0 0;color:var(--text-mute);font-size:.85rem">
    Showing <strong data-filter-count><?= count($universities) ?></strong> of <?= count($universities) ?> universities.
  </p>
</div>

<div class="grid grid--3">
  <?php foreach ($universities as $i => $u): ?>
    <a class="tile" href="<?= e(bd_university_url($u)) ?>" data-reveal="<?= ($i % 9) * 35 ?>"
             data-filter-item="<?= e($u['name'] . ' ' . $u['short'] . ' ' . $u['city'] . ' ' . $u['type'] . ' ' . $u['desc']) ?>"
             data-filter-group="<?= e($u['category']) ?>">
      <span class="tile__icon" aria-hidden="true">🎓</span>
      <h3><?= e($u['name']) ?></h3>
      <p style="margin-bottom:.9rem"><?= e($u['desc']) ?></p>
      <div style="display:flex;gap:.4rem;flex-wrap:wrap">
        <span class="badge"><?= $u['category'] === 'public' ? 'Public' : 'Private' ?></span>
        <span class="badge badge--red"><?= e($u['city']) ?></span>
        <span class="badge badge--gold">Est. <?= e((string) $u['established']) ?></span>
      </div>
    </a>
  <?php endforeach; ?>
</div>

<p data-filter-empty style="display:none;text-align:center;color:var(--text-mute);padding:3rem 1rem">
  Nothing matches that search. Try “public”, “private”, “engineering” or a city name.
</p>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
