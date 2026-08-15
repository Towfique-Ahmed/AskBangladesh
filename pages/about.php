<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** National profile: identity, symbols, history, food and facts. */

$profile  = bd_nation('profile');
$symbols  = bd_nation('symbols');
$timeline = bd_nation('timeline');
$food     = bd_nation('food');
$facts    = bd_nation('facts');

$pageTitle       = 'About Bangladesh — national profile, symbols and history';
$pageDescription = 'The official profile of Bangladesh: government, currency, language, national symbols, a history timeline from Pundranagara to the Padma Bridge, food and surprising facts.';

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🇧🇩 National profile</span>
  <h1>About Bangladesh</h1>
  <p>
    <?= e($profile['official_name']) ?> — <?= e($profile['bn_name']) ?> — is a country of
    <?= bd_compact($profile['population']) ?> people packed into
    <?= bd_num(bd_places('geography')['area_km2']) ?> km² of delta, making it one of the most
    densely populated nations on Earth.
  </p>
</div>

<div class="statgrid" data-reveal>
  <div class="stat"><div class="stat__value" data-count-to="171" data-count-suffix="M">0</div><div class="stat__label">Population</div></div>
  <div class="stat"><div class="stat__value" data-count-to="<?= $profile['density'] ?>">0</div><div class="stat__label">People per km²</div></div>
  <div class="stat"><div class="stat__value" data-count-to="<?= $profile['literacy'] ?>" data-count-dec="1" data-count-suffix="%">0</div><div class="stat__label">Literacy rate</div></div>
  <div class="stat"><div class="stat__value" data-count-to="<?= $profile['life_expectancy'] ?>" data-count-dec="1">0</div><div class="stat__label">Life expectancy</div></div>
</div>

<!-- --------------------------------------------------------------- facts -->
<section class="section">
  <div class="section__head"><h2>The official record</h2></div>

  <div class="grid grid--2">
    <div class="card" data-reveal>
      <h3>🏛️ State &amp; government</h3>
      <div class="mapinfo__rows">
        <div class="mapinfo__row"><span>Official name</span><span><?= e($profile['official_name']) ?></span></div>
        <div class="mapinfo__row"><span>In Bangla</span><span><?= e($profile['bn_name']) ?></span></div>
        <div class="mapinfo__row"><span>Capital</span><span><?= e($profile['capital']) ?></span></div>
        <div class="mapinfo__row"><span>Government</span><span><?= e($profile['government']) ?></span></div>
        <div class="mapinfo__row"><span>Legislature</span><span><?= e($profile['legislature']) ?></span></div>
        <div class="mapinfo__row"><span>Independence</span><span><?= e($profile['independence']) ?></span></div>
        <div class="mapinfo__row"><span>Constitution</span><span><?= e($profile['constitution']) ?></span></div>
        <div class="mapinfo__row"><span>UN member since</span><span><?= e($profile['un_member']) ?></span></div>
      </div>
    </div>

    <div class="card" data-reveal="80">
      <h3>🌐 Everyday details</h3>
      <div class="mapinfo__rows">
        <div class="mapinfo__row"><span>Official language</span><span><?= e($profile['official_lang']) ?></span></div>
        <div class="mapinfo__row"><span>Currency</span><span><?= e($profile['currency']) ?></span></div>
        <div class="mapinfo__row"><span>Calling code</span><span><?= e($profile['calling_code']) ?></span></div>
        <div class="mapinfo__row"><span>Internet domain</span><span><?= e($profile['internet_tld']) ?></span></div>
        <div class="mapinfo__row"><span>Driving side</span><span><?= e($profile['driving_side']) ?></span></div>
        <div class="mapinfo__row"><span>ISO codes</span><span><?= e($profile['iso']) ?></span></div>
        <div class="mapinfo__row"><span>GDP (nominal)</span><span><?= e($profile['gdp_nominal']) ?></span></div>
        <div class="mapinfo__row"><span>GDP (PPP)</span><span><?= e($profile['gdp_ppp']) ?></span></div>
      </div>
      <div style="margin-top:.9rem;display:flex;gap:.35rem;flex-wrap:wrap">
        <?php foreach ($profile['organisations'] as $org): ?>
          <span class="badge"><?= e($org) ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ------------------------------------------------------------- symbols -->
<section class="section">
  <div class="section__head">
    <h2>National symbols</h2>
    <p>The flag, the anthem and the living things that stand for the country.</p>
  </div>

  <div class="grid grid--3">
    <?php foreach ($symbols as $i => $symbol): ?>
      <div class="tile" data-reveal="<?= ($i % 9) * 40 ?>">
        <span class="tile__icon" aria-hidden="true"><?= $symbol['emoji'] ?></span>
        <h3><?= e($symbol['item']) ?></h3>
        <p><?= e($symbol['value']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ------------------------------------------------------------ timeline -->
<section class="section">
  <div class="section__head">
    <h2>Two thousand years, briefly</h2>
    <p>From the ancient city of Pundranagara to the Padma Bridge.</p>
  </div>

  <div class="card" data-reveal>
    <div class="timeline">
      <?php foreach ($timeline as $item): ?>
        <div class="timeline__item">
          <div class="timeline__year"><?= e($item['year']) ?></div>
          <div class="timeline__text"><?= e($item['event']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ---------------------------------------------------------------- food -->
<section class="section">
  <div class="section__head">
    <h2>What Bangladesh eats</h2>
    <p>Rice, fish and mustard oil — and everything built on top of them.</p>
  </div>

  <div class="grid grid--4">
    <?php foreach ($food as $i => $dish): ?>
      <div class="card" data-reveal="<?= ($i % 8) * 45 ?>">
        <div style="font-size:2rem"><?= $dish['emoji'] ?></div>
        <h3><?= e($dish['name']) ?></h3>
        <p><?= e($dish['desc']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- --------------------------------------------------------- did you know -->
<section class="section">
  <div class="section__head"><h2>Things people get surprised by</h2></div>

  <div class="grid grid--2">
    <?php foreach ($facts as $i => $fact): ?>
      <div class="card" data-reveal="<?= ($i % 8) * 35 ?>">
        <p style="margin:0"><span style="font-size:1.2rem;margin-right:.5rem">💡</span><?= e($fact) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
