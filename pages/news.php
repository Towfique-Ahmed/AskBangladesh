<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Trending Bangladesh news in tech, tourism and the economy, filterable by topic. */

$news    = bd_news();
$asOf    = bd_news_date((string) (bd_data('news')['as_of'] ?? date('Y-m-d')));
$topics  = ['Tech' => '💻', 'Tourism' => '🧳', 'Economy' => '📈'];

$faqs = [
    'Where does this news come from?'
        => 'Each item is a short summary written by AskBangladesh, with a link to the original report by a named news outlet or publisher. We do not republish articles; open the source link for the full story.',
    'How often is the news section updated?'
        => 'The list was last reviewed on ' . $asOf . '. Older items are removed as they go stale. Check the linked source for anything that may have changed since.',
    'Which topics are covered?'
        => 'Technology and the digital economy, tourism and travel, and economy and trade, with a focus on stories that affect people living in, visiting or doing business with Bangladesh.',
];

$seo = bd_seo(bd_page_seo('news') + [
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'News'],
    ],
    'jsonld' => [
        bd_jsonld_item_list(
            'Trending Bangladesh News',
            $news,
            static fn (array $n): array => [
                '@type'         => 'NewsArticle',
                'headline'      => $n['title'],
                'description'   => $n['summary'],
                'datePublished' => $n['date'],
                'url'           => $n['url'],
                'articleSection' => $n['category'],
                'publisher'     => ['@type' => 'Organization', 'name' => $n['source']],
            ]
        ),
        bd_jsonld_faq($faqs),
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">📰 Trending now</span>
  <h1>Bangladesh News: Tech, Tourism &amp; Economy</h1>
  <p>
    What is moving in Bangladesh right now - digital jobs and startups, reopened islands and a new
    tourism master plan, trade status and the taka. Short summaries, each linked to the original
    report. Last reviewed <?= e($asOf) ?>.
  </p>
</div>

<div class="card" data-reveal style="margin-bottom:1.4rem">
  <div class="field" style="margin-bottom:.9rem">
    <label for="news-filter">Search the news</label>
    <input type="search" class="input" id="news-filter" data-filter-input
           placeholder="Try “St Martin”, “jobs”, “remittance” or “AI”…">
  </div>
  <div class="chips">
    <button type="button" class="chip is-active" data-filter-chip="all">Everything</button>
    <?php foreach ($topics as $topic => $icon): ?>
      <button type="button" class="chip" data-filter-chip="<?= e($topic) ?>"><?= $icon ?> <?= e($topic) ?></button>
    <?php endforeach; ?>
  </div>
  <p style="margin:.9rem 0 0;color:var(--text-mute);font-size:.85rem">
    Showing <strong data-filter-count><?= count($news) ?></strong> of <?= count($news) ?> stories.
  </p>
</div>

<div class="grid grid--3">
  <?php foreach ($news as $i => $n): ?>
    <article class="tile" data-reveal="<?= ($i % 9) * 35 ?>"
             data-filter-item="<?= e($n['title'] . ' ' . $n['summary'] . ' ' . $n['category'] . ' ' . $n['source']) ?>"
             data-filter-group="<?= e($n['category']) ?>">
      <span class="tile__icon" aria-hidden="true"><?= $n['emoji'] ?></span>
      <div style="display:flex;gap:.4rem;flex-wrap:wrap;margin-bottom:.6rem">
        <span class="badge"><?= e($n['category']) ?></span>
        <span class="badge badge--gold"><time datetime="<?= e($n['date']) ?>"><?= e(bd_news_date($n['date'])) ?></time></span>
      </div>
      <h3><?= e($n['title']) ?></h3>
      <p style="margin-bottom:.9rem"><?= e($n['summary']) ?></p>
      <a class="btn btn--ghost" href="<?= e($n['url']) ?>" target="_blank" rel="noopener noreferrer">
        Read on <?= e($n['source']) ?> →
      </a>
    </article>
  <?php endforeach; ?>
</div>

<p data-filter-empty style="display:none;text-align:center;color:var(--text-mute);padding:3rem 1rem">
  Nothing matches that search. Try “tech”, “tourism” or “economy”.
</p>

<?= bd_render_faq($faqs, 'Frequently asked questions about this news section') ?>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
