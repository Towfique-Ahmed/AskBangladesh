<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Best public and private universities of Bangladesh, filterable by category. */

$universities = bd_all_universities();

$seo = bd_seo(bd_page_seo('universities') + [
    'breadcrumbs' => [
        ['name' => t('Home'), 'url' => bd_url()],
        ['name' => t('Universities')],
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
  <span class="pagehead__eyebrow">🎓 <?= e(t('Higher Education')) ?></span>
  <h1><?= e(t('Universities of Bangladesh')) ?></h1>
  <p>
    <?= e(t('The top public and private universities in Bangladesh - from the century-old University of Dhaka to fast-growing private campuses in Dhaka, Chattogram, Sylhet and Rajshahi.')) ?>
  </p>
</div>

<div class="card" data-reveal style="margin-bottom:1.4rem">
  <div class="field" style="margin-bottom:.9rem">
    <label for="university-filter"><?= e(t('Search universities')) ?></label>
    <input type="search" class="input" id="university-filter" data-filter-input
           placeholder="<?= e(t('Try “Dhaka”, “engineering”, “BRAC” or “Chattogram”…')) ?>">
  </div>
  <div class="chips">
    <button type="button" class="chip is-active" data-filter-chip="all"><?= e(t('Everything')) ?></button>
    <button type="button" class="chip" data-filter-chip="public"><?= e(t('Public')) ?></button>
    <button type="button" class="chip" data-filter-chip="private"><?= e(t('Private')) ?></button>
  </div>
  <p style="margin:.9rem 0 0;color:var(--text-mute);font-size:.85rem">
    <?= e(t('Showing')) ?> <strong data-filter-count><?= bd_n(count($universities)) ?></strong> <?= e(t('of')) ?> <?= bd_n(count($universities)) ?> <?= e(t('universities.')) ?>
  </p>
</div>

<div class="grid grid--3">
  <?php foreach ($universities as $i => $u): ?>
    <a class="tile" href="<?= e(bd_university_url($u)) ?>" data-reveal="<?= ($i % 9) * 35 ?>"
             data-filter-item="<?= e($u['name'] . ' ' . $u['short'] . ' ' . ($u['bn'] ?? '') . ' ' . t($u['type']) . ' ' . $u['city'] . ' ' . $u['type'] . ' ' . $u['desc']) ?>"
             data-filter-group="<?= e($u['category']) ?>">
      <span class="tile__icon" aria-hidden="true">🎓</span>
      <h3><?= e(bd_name($u)) ?></h3>
      <p style="margin-bottom:.9rem"><?= e($u['desc']) ?></p>
      <div style="display:flex;gap:.4rem;flex-wrap:wrap">
        <span class="badge"><?= e($u['category'] === 'public' ? t('Public') : t('Private')) ?></span>
        <span class="badge badge--red"><?= e($u['city']) ?></span>
        <span class="badge badge--gold"><?= e(t('Est.')) ?> <?= e(bd_lang() === 'bn' ? bd_bn_digits((string) $u['established']) : (string) $u['established']) ?></span>
        <span class="badge"><?= e(t($u['type'])) ?></span>
        <?php if (!empty($u['programs'])): ?>
          <span class="badge"><?= bd_n(count($u['programs'])) ?> <?= e(t('key programs')) ?></span>
        <?php endif; ?>
      </div>
    </a>
  <?php endforeach; ?>
</div>

<p data-filter-empty style="display:none;text-align:center;color:var(--text-mute);padding:3rem 1rem">
  <?= e(t('Nothing matches that search. Try “public”, “private”, “engineering” or a city name.')) ?>
</p>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
