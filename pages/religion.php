<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Faiths of Bangladesh and the festival calendar. */

$religions = bd_nation('religions');
$festivals = bd_nation('festivals');

$festivalSchemas = bd_jsonld_festivals($festivals);
$seo = bd_seo(bd_page_seo('religion') + [
    'type'        => 'article',
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Religions'],
    ],
    'jsonld' => [
        bd_jsonld_item_list(
            'Religions of Bangladesh',
            $religions,
            static fn (array $r): array => [
                '@type'       => 'Thing',
                'name'        => $r['name'],
                'description' => $r['note'],
            ]
        ),
        ...$festivalSchemas,
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🕊️ Faith &amp; festivals</span>
  <h1>Religions of Bangladesh</h1>
  <p>
    Bangladesh is a Muslim-majority country whose constitution guarantees the right of every citizen
    to practise their own faith. Eid, Durga Puja, Buddha Purnima and Christmas are all national
    public holidays, and Pohela Boishakh belongs to everyone.
  </p>
</div>

<!-- ------------------------------------------------------------ breakdown -->
<div class="card" data-reveal>
  <h3>Population by religion</h3>
  <?php foreach ($religions as $religion): ?>
    <div style="margin-bottom:1rem">
      <div style="display:flex;justify-content:space-between;font-size:.9rem;margin-bottom:.35rem">
        <strong><?= $religion['emoji'] ?> <?= e($religion['name']) ?></strong>
        <span style="font-family:var(--mono);color:var(--green-300)"><?= $religion['share'] ?>%</span>
      </div>
      <div class="bar">
        <div class="bar__fill" data-width="<?= max(0.6, $religion['share']) ?>"
             style="background:<?= e($religion['color']) ?>"></div>
      </div>
    </div>
  <?php endforeach; ?>
  <p style="font-size:.8rem;color:var(--text-mute);margin:0">
    Shares follow the most recent national population and housing census.
  </p>
</div>

<!-- ------------------------------------------------------------- faiths -->
<section class="section">
  <div class="section__head"><h2>Each faith, and where to find it</h2></div>

  <div class="grid grid--2">
    <?php foreach ($religions as $i => $religion): ?>
      <article class="card" data-reveal="<?= $i * 55 ?>"
               style="border-left:3px solid <?= e($religion['color']) ?>">
        <div style="font-size:2rem"><?= $religion['emoji'] ?></div>
        <h3 style="margin:.3rem 0 .1rem"><?= e($religion['name']) ?></h3>
        <div style="color:var(--green-300);font-size:.95rem;margin-bottom:.7rem"><?= e($religion['bn']) ?></div>
        <p><?= e($religion['note']) ?></p>
        <h4 style="font-size:.72rem;text-transform:uppercase;letter-spacing:.12em;color:var(--text-mute);margin:1rem 0 .4rem">
          Notable places
        </h4>
        <ul style="margin:0;padding-left:1.1rem;font-size:.86rem;color:var(--text-dim)">
          <?php foreach ($religion['sites'] as $site): ?>
            <li><?= e($site) ?></li>
          <?php endforeach; ?>
        </ul>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<!-- ---------------------------------------------------------- festivals -->
<section class="section">
  <div class="section__head">
    <h2>The festival calendar</h2>
    <p>Religious, seasonal and national — the days the whole country stops for.</p>
  </div>

  <div class="grid grid--3">
    <?php foreach ($festivals as $i => $festival): ?>
      <article class="tile" data-reveal="<?= ($i % 9) * 40 ?>">
        <span class="tile__icon" aria-hidden="true"><?= $festival['emoji'] ?></span>
        <h3><?= e($festival['name']) ?></h3>
        <div style="color:var(--green-300);font-size:.9rem;margin-bottom:.5rem"><?= e($festival['bn']) ?></div>
        <p style="margin-bottom:.7rem"><?= e($festival['desc']) ?></p>
        <span class="badge badge--gold"><?= e($festival['when']) ?></span>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="section">
  <div class="grid grid--2">
    <a class="card" href="<?= e(bd_url('sunrise-sunset')) ?>" data-reveal>
      <h3>🌅 Sunrise &amp; sunset by district</h3>
      <p>Daily sunrise, sunset, day length and golden hour for any of the 64 districts, calculated
      from each district's own coordinates.</p>
      <span class="tile__arrow">Open sunrise &amp; sunset →</span>
    </a>
    <a class="card" href="<?= e(bd_url('travel')) ?>" data-reveal="80">
      <h3>🛕 Visit the sites</h3>
      <p>Mosques, temples, monasteries and churches across the country, from the Sixty Dome Mosque
      to Somapura Mahavihara.</p>
      <span class="tile__arrow">Open the travel guide →</span>
    </a>
  </div>
</section>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
