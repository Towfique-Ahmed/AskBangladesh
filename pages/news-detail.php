<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** A single AskBangladesh news article. */

$story = bd_find_news($slug);
if ($story === null) {
    http_response_code(404);
    require APP_ROOT . '/pages/404.php';
    exit;
}

$related = array_values(array_filter(
    bd_news(),
    static fn (array $n): bool => $n['title'] !== $story['title']
));
usort($related, static fn (array $a, array $b): int =>
    ($b['category'] === $story['category']) <=> ($a['category'] === $story['category']));
$related = array_slice($related, 0, 3);

$path = 'news/' . bd_slug($story['title']);

$seo = bd_seo([
    'title'       => bd_l($story, 'title'),
    'description' => bd_l($story, 'summary'),
    'keywords'    => 'Bangladesh ' . strtolower($story['category']) . ' news, ' . $story['title'],
    'path'        => $path,
    'type'        => 'article',
    'breadcrumbs' => [
        ['name' => t('Home'), 'url' => bd_url()],
        ['name' => t('News'), 'url' => bd_url('news')],
        ['name' => bd_l($story, 'title')],
    ],
    'jsonld' => [[
        '@context'         => 'https://schema.org',
        '@type'            => 'NewsArticle',
        'headline'         => bd_l($story, 'title'),
        'description'      => bd_l($story, 'summary'),
        'datePublished'    => $story['date'],
        'articleSection'   => $story['category'],
        'mainEntityOfPage' => bd_abs_url($path),
        'author'           => ['@type' => 'Organization', 'name' => APP_NAME],
        'publisher'        => ['@type' => 'Organization', 'name' => APP_NAME],
    ]],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow"><?= $story['emoji'] ?> <?= e(t($story['category'])) ?> · <?= e(t('News')) ?></span>
  <h1><?= e(bd_l($story, 'title')) ?></h1>
  <p><?= e(bd_l($story, 'summary')) ?></p>
  <div style="display:flex;gap:.4rem;flex-wrap:wrap;margin-top:.8rem">
    <span class="badge"><?= e(t($story['category'])) ?></span>
    <span class="badge badge--gold"><time datetime="<?= e($story['date']) ?>"><?= e(bd_news_date($story['date'])) ?></time></span>
  </div>
</div>

<article class="card" data-reveal style="max-width:760px;margin:0 auto 1.6rem">
  <?php
  $paragraphs = (bd_lang() === 'bn' && !empty($story['body_bn'])) ? $story['body_bn'] : $story['body'];
  if (bd_lang() === 'bn' && empty($story['body_bn'])): ?>
    <p class="langnote" role="note">এই সংবাদটির পূর্ণ বিবরণ আপাতত ইংরেজিতে রয়েছে।</p>
  <?php endif;
  foreach ($paragraphs as $paragraph): ?>
    <p style="line-height:1.75;margin:0 0 1rem"><?= e($paragraph) ?></p>
  <?php endforeach; ?>
  <p style="margin:1.2rem 0 0;color:var(--text-mute);font-size:.85rem">
    <?= e(tf('By the AskBangladesh news desk. Based on %s. Figures and plans may change after publication.', bd_source($story['source']))) ?>
  </p>
</article>

<?php if ($related !== []): ?>
<section class="section">
  <div class="section__head">
    <h2><?= e(t('More news')) ?></h2>
    <p><a href="<?= e(bd_url('news')) ?>" style="color:var(--green-300)"><?= e(t('All news →')) ?></a></p>
  </div>
  <div class="grid grid--3">
    <?php foreach ($related as $i => $n): ?>
      <a class="card" href="<?= e(bd_news_url($n)) ?>" data-reveal="<?= $i * 50 ?>">
        <div style="font-size:2rem"><?= $n['emoji'] ?></div>
        <h3><?= e(bd_l($n, 'title')) ?></h3>
        <span class="badge"><?= e(t($n['category'])) ?></span>
        <span class="badge badge--gold"><?= e(bd_news_date($n['date'])) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
