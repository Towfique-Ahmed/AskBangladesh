<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Trending Bangladesh news in tech, tourism and the economy, filterable by topic. */

$news    = bd_news();
$asOf    = bd_news_date((string) (bd_data('news')['as_of'] ?? date('Y-m-d')));
$icons   = ['Tech' => '💻', 'Tourism' => '🧳', 'Economy' => '📈', 'Environment' => '🌿', 'Education' => '🎓', 'Society' => '👥', 'Health' => '🩺', 'Culture' => '🎨'];
$topics  = [];
foreach ($news as $n) {
    $topics[$n['category']] = $icons[$n['category']] ?? '📰';
}

$faqs = [
    t('Where does this news come from?')
        => t('Every story is written by the AskBangladesh news desk and published on this site. Each article names the reporting it draws on at the bottom of the page.'),
    t('How often is the news section updated?')
        => tf('The list was last reviewed on %s. Older items are removed as they go stale. Check the linked source for anything that may have changed since.', $asOf),
    t('Which topics are covered?')
        => t('Technology, tourism and travel, economy and trade, environment and climate, and education, with a focus on stories that affect people living in, visiting or doing business with Bangladesh.'),
];

$seo = bd_seo(bd_page_seo('news') + [
    // Summaries of other outlets' reporting, not original journalism.
    'noindex'     => true,
    'breadcrumbs' => [
        ['name' => t('Home'), 'url' => bd_url()],
        ['name' => t('News')],
    ],
    'jsonld' => [
        bd_jsonld_item_list(
            t('Trending Bangladesh News'),
            $news,
            static fn (array $n): array => [
                '@type'         => 'NewsArticle',
                'headline'      => bd_l($n, 'title'),
                'description'   => bd_l($n, 'summary'),
                'datePublished' => $n['date'],
                'url'           => bd_abs_url('news/' . bd_slug($n['title'])),
                'articleSection' => $n['category'],
                'publisher'     => ['@type' => 'Organization', 'name' => APP_NAME],
            ]
        ),
        bd_jsonld_faq($faqs),
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">📰 <?= e(t('Trending now')) ?></span>
  <h1><?= e(t('Bangladesh News: Economy, Tech & Tourism')) ?></h1>
  <p>
    <?= e(tf('What is moving in Bangladesh right now - trade talks and investment, digital jobs, reopened islands, the taka, climate and wildlife. Original stories from the AskBangladesh news desk, written and published here. Last reviewed %s.', $asOf)) ?>
  </p>
</div>

<div class="card" data-reveal style="margin-bottom:1.4rem">
  <div class="field" style="margin-bottom:.9rem">
    <label for="news-filter"><?= e(t('Search the news')) ?></label>
    <input type="search" class="input" id="news-filter" data-filter-input
           placeholder="<?= e(t('Try “St Martin”, “jobs”, “remittance” or “AI”…')) ?>">
  </div>
  <div class="chips">
    <button type="button" class="chip is-active" data-filter-chip="all"><?= e(t('Everything')) ?></button>
    <?php foreach ($topics as $topic => $icon): ?>
      <button type="button" class="chip" data-filter-chip="<?= e($topic) ?>"><?= $icon ?> <?= e(t($topic)) ?></button>
    <?php endforeach; ?>
  </div>
  <p style="margin:.9rem 0 0;color:var(--text-mute);font-size:.85rem">
    <?= e(t('Showing')) ?> <strong data-filter-count><?= count($news) ?></strong> <?= e(t('of')) ?> <?= count($news) ?> <?= e(t('stories.')) ?>
  </p>
</div>

<div class="grid grid--3">
  <?php foreach ($news as $i => $n): ?>
    <article class="tile" data-reveal="<?= ($i % 9) * 35 ?>"
             data-filter-item="<?= e($n['title'] . ' ' . $n['summary'] . ' ' . $n['category']) ?>"
             data-filter-group="<?= e($n['category']) ?>">
      <span class="tile__icon" aria-hidden="true"><?= $n['emoji'] ?></span>
      <div style="display:flex;gap:.4rem;flex-wrap:wrap;margin-bottom:.6rem">
        <span class="badge"><?= e(t($n['category'])) ?></span>
        <span class="badge badge--gold"><time datetime="<?= e($n['date']) ?>"><?= e(bd_news_date($n['date'])) ?></time></span>
      </div>
      <h3><?= e(bd_l($n, 'title')) ?></h3>
      <p style="margin-bottom:.9rem"><?= e(bd_l($n, 'summary')) ?></p>
      <a class="btn btn--ghost" href="<?= e(bd_news_url($n)) ?>"><?= e(t('Read the story →')) ?></a>
    </article>
  <?php endforeach; ?>
</div>

<p data-filter-empty style="display:none;text-align:center;color:var(--text-mute);padding:3rem 1rem">
  <?= e(t('Nothing matches that search. Try “tech”, “tourism” or “economy”.')) ?>
</p>

<?= bd_render_faq($faqs, t('Frequently asked questions about this news section')) ?>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
