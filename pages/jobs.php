<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Government and private job roles in Bangladesh, filterable by category. Each entry links out to the employer's own official site - this page has no per-job detail route. */

$jobs       = bd_all_jobs();
$govCount   = count(bd_jobs('government'));
$privCount  = count(bd_jobs('private'));

$faqs = [
    'Does AskBangladesh host live job applications?'
        => 'No. This page lists the kinds of government and private-sector roles regularly recruited for in Bangladesh, with each entry linking to the employer\'s own official website. Apply and check deadlines there.',
    'How do I apply for a government (BCS or non-cadre) job in Bangladesh?'
        => 'Most government recruitment is announced through the hiring organisation\'s own circular, or through the Bangladesh Public Service Commission (BPSC) for BCS cadre posts. Follow the "Apply via" link on each listing to the organisation\'s official website for the current circular.',
    'How many jobs are listed here?'
        => 'This page currently lists ' . count($jobs) . ' roles: ' . $govCount . ' government and ' . $privCount . ' private-sector positions across ministries, state-owned banks, telecoms, RMG, pharma, IT and NGOs.',
];

$seo = bd_seo(bd_page_seo('jobs') + [
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Jobs'],
    ],
    'jsonld' => [
        bd_jsonld_item_list(
            'Government and Private Jobs in Bangladesh',
            $jobs,
            static fn (array $j): array => [
                '@type'       => 'Organization',
                'name'        => $j['org'],
                'description' => $j['title'] . ' - ' . $j['sector'],
                'url'         => $j['apply_url'],
            ]
        ),
        bd_jsonld_faq($faqs),
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">💼 Careers</span>
  <h1>Government &amp; Private Jobs in Bangladesh</h1>
  <p>
    <?= count($jobs) ?> roles across ministries, state-owned banks, the armed and civil services,
    telecoms, banks, RMG, pharma, IT and NGOs - <?= $govCount ?> government and <?= $privCount ?> private-sector
    positions. Every listing links straight to the employer's own official website; there is no
    separate detail page on this site for each job.
  </p>
</div>

<div class="card" data-reveal style="margin-bottom:1.4rem">
  <p style="margin:0 0 .9rem;font-size:.88rem;color:var(--text-mute)">
    ⚠️ <strong>These are illustrative, recurring role types, not live dated circulars.</strong>
    Vacancy counts, pay and qualifications are indicative - always confirm the current opening,
    deadline and eligibility on the employer's own website (linked on each card) or its latest
    official notice before applying.
  </p>
  <div class="field" style="margin-bottom:.9rem">
    <label for="job-filter">Search jobs</label>
    <input type="search" class="input" id="job-filter" data-filter-input
           placeholder="Try “bank”, “engineer”, “teacher” or “IT”…">
  </div>
  <div class="chips">
    <button type="button" class="chip is-active" data-filter-chip="all">Everything</button>
    <button type="button" class="chip" data-filter-chip="government">Government</button>
    <button type="button" class="chip" data-filter-chip="private">Private</button>
  </div>
  <p style="margin:.9rem 0 0;color:var(--text-mute);font-size:.85rem">
    Showing <strong data-filter-count><?= count($jobs) ?></strong> of <?= count($jobs) ?> jobs.
  </p>
</div>

<div class="grid grid--3">
  <?php foreach ($jobs as $i => $j): ?>
    <div class="tile" data-reveal="<?= ($i % 9) * 35 ?>"
         data-filter-item="<?= e($j['title'] . ' ' . $j['org'] . ' ' . $j['sector'] . ' ' . $j['location'] . ' ' . $j['qualification']) ?>"
         data-filter-group="<?= e($j['category']) ?>">
      <span class="tile__icon" aria-hidden="true"><?= $j['category'] === 'government' ? '🏛️' : '💼' ?></span>
      <h3><?= e($j['title']) ?></h3>
      <p style="margin-bottom:.5rem;font-weight:600;color:var(--text)"><?= e($j['org']) ?></p>
      <div style="display:flex;gap:.4rem;flex-wrap:wrap;margin-bottom:.7rem">
        <span class="badge"><?= $j['category'] === 'government' ? 'Government' : 'Private' ?></span>
        <span class="badge badge--red"><?= e($j['location']) ?></span>
        <span class="badge badge--gold"><?= e(bd_num($j['vacancies'])) ?> vacancies</span>
      </div>
      <div class="mapinfo__rows" style="margin-bottom:.9rem">
        <div class="mapinfo__row"><span>Sector</span><span><?= e($j['sector']) ?></span></div>
        <div class="mapinfo__row"><span>Qualification</span><span><?= e($j['qualification']) ?></span></div>
        <div class="mapinfo__row"><span>Pay</span><span><?= e($j['salary']) ?></span></div>
        <div class="mapinfo__row"><span>Apply via</span><span><?= e($j['apply_via']) ?></span></div>
      </div>
      <a class="btn btn--ghost" href="<?= e($j['apply_url']) ?>" target="_blank" rel="noopener noreferrer">
        <?= e($j['org']) ?> official site →
      </a>
    </div>
  <?php endforeach; ?>
</div>

<p data-filter-empty style="display:none;text-align:center;color:var(--text-mute);padding:3rem 1rem">
  Nothing matches that search. Try “government”, “private”, “bank” or “engineer”.
</p>

<?= bd_render_faq($faqs, 'Frequently asked questions about jobs in Bangladesh') ?>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
