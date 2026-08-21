<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Landing page — the front door to every tool and dataset in the app. */

$profile   = bd_nation('profile');
$facts     = bd_nation('facts');
$tools     = bd_tools();
$travel    = array_slice(bd_places('travel'), 0, 6);
$countdown = bd_countdowns();
$next      = $countdown[0];
$symbols   = array_slice(bd_nation('symbols'), 0, 6);

$seo = bd_seo(bd_page_seo('home'));

$typer = json_encode([
    'the world’s largest river delta.',
    'all 64 districts, mapped and searchable.',
    'today’s gold price, in bhori and grams.',
    'sunrise and sunset for your own district.',
    'the Taka against 30+ world currencies.',
    'the longest sea beach on the planet.',
    'every government e-service link you need.',
], JSON_UNESCAPED_UNICODE);

require APP_ROOT . '/includes/layout/header.php';
?>

<section class="hero">
  <div class="hero__inner">
    <div>
      <span class="hero__badge" data-reveal>🇧🇩 <?= e($profile['bn_name']) ?></span>
      <h1 data-reveal="60">Ask anything about<br><span class="grad">Bangladesh</span></h1>
      <p class="hero__typer" data-typer='<?= e($typer) ?>' data-reveal="120"></p>
      <p class="hero__lead" data-reveal="160">
        One place for the people of Bangladesh and for everyone curious about it — an interactive
        map, live clocks and converters, district data, travel guides, sun times, gold rates
        and the government directory, all searchable from a single box.
      </p>

      <div class="hero__cta" data-reveal="220">
        <a class="btn btn--primary" href="<?= e(bd_url('map')) ?>">🗺️ Open the interactive map</a>
        <a class="btn" href="<?= e(bd_url('search')) ?>">🔍 Search everything</a>
      </div>

      <div class="hero__stats" data-reveal="280">
        <div class="hero__stat">
          <strong data-count-to="64">64</strong>
          <span>Districts</span>
        </div>
        <div class="hero__stat">
          <strong data-count-to="8">8</strong>
          <span>Divisions</span>
        </div>
        <div class="hero__stat">
          <strong data-count-to="907">907</strong>
          <span>Rivers</span>
        </div>
        <div class="hero__stat">
          <strong data-count-to="171" data-count-suffix="M">171M</strong>
          <span>People</span>
        </div>
      </div>
    </div>

    <div class="flagwrap" data-reveal="100">
      <div class="flagcard">
        <span class="flagcard__disc"></span>
        <span class="flagcard__shine"></span>
      </div>
    </div>
  </div>
</section>

<div class="marquee" aria-hidden="true">
  <div class="marquee__track">
    <?php for ($i = 0; $i < 2; $i++): ?>
      <?php foreach (array_slice($facts, 0, 6) as $fact): ?>
        <span class="marquee__item">💡 <?= e($fact) ?></span>
      <?php endforeach; ?>
    <?php endfor; ?>
  </div>
</div>

<!-- ------------------------------------------------------------ live strip -->
<section class="section">
  <div class="section__head">
    <h2>Live right now</h2>
    <p>Bangladesh Standard Time is UTC+06:00 all year — no daylight saving.</p>
  </div>

  <div class="grid grid--4">
    <div class="card" data-reveal>
      <h3>🕰️ Time in Bangladesh</h3>
      <div style="font:700 2rem/1.2 var(--mono); color:var(--green-300)" data-bd-clock>--:--:--</div>
      <p data-bd-date>&nbsp;</p>
    </div>

    <div class="card" data-reveal="60">
      <h3><?= e($next['emoji']) ?> Next: <?= e($next['name']) ?></h3>
      <div class="countdown" data-countdown="<?= e($next['target']) ?>" style="margin-top:.6rem">
        <div class="countdown__cell"><div class="countdown__num" data-cd-d>0</div><div class="countdown__lbl">Days</div></div>
        <div class="countdown__cell"><div class="countdown__num" data-cd-h>00</div><div class="countdown__lbl">Hrs</div></div>
        <div class="countdown__cell"><div class="countdown__num" data-cd-m>00</div><div class="countdown__lbl">Min</div></div>
        <div class="countdown__cell"><div class="countdown__num" data-cd-s>00</div><div class="countdown__lbl">Sec</div></div>
      </div>
    </div>

    <?php
    $rates = bd_exchange_rates();
    $usd   = $rates['rates']['USD'] ?? 0;
    $gold  = bd_gold_prices();
    $g22   = $gold['gold'][0] ?? null;
    ?>
    <a class="card" href="<?= e(bd_url('currency')) ?>" data-reveal="120">
      <h3>💱 US Dollar</h3>
      <div style="font:700 2rem/1.2 var(--mono); color:var(--green-300)">
        ৳ <?= $usd > 0 ? bd_num(1 / $usd, 2) : '—' ?>
      </div>
      <p>per 1 USD · <?= $rates['live'] ? 'live rate' : 'indicative' ?></p>
    </a>

    <a class="card" href="<?= e(bd_url('gold')) ?>" data-reveal="180">
      <h3>🥇 Gold 22K</h3>
      <div style="font:700 2rem/1.2 var(--mono); color:var(--gold-500)">
        ৳ <?= $g22 ? bd_num($g22['bhori']) : '—' ?>
      </div>
      <p>per bhori (11.664 g)</p>
    </a>
  </div>
</section>

<!-- ---------------------------------------------------------------- tools -->
<section class="section">
  <div class="section__head">
    <h2>Everything in one place</h2>
    <p>Twelve tools and libraries, all cross-linked and all searchable.</p>
  </div>

  <div class="grid grid--3">
    <?php foreach ($tools as $i => $tool): ?>
      <a class="tile" href="<?= e($tool['url']) ?>" data-reveal="<?= $i * 40 ?>">
        <span class="tile__icon" aria-hidden="true"><?= $tool['emoji'] ?></span>
        <h3><?= e($tool['name']) ?></h3>
        <p><?= e($tool['desc']) ?></p>
        <span class="tile__arrow">Open →</span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- --------------------------------------------------------------- travel -->
<section class="section">
  <div class="section__head">
    <h2>Where to go</h2>
    <p><a href="<?= e(bd_url('travel')) ?>" style="color:var(--green-300)">See all destinations →</a></p>
  </div>

  <div class="grid grid--3">
    <?php foreach ($travel as $i => $place): ?>
      <a class="card" href="<?= e(bd_travel_url($place)) ?>" data-reveal="<?= $i * 50 ?>">
        <div style="font-size:2rem"><?= $place['emoji'] ?></div>
        <h3><?= e($place['name']) ?></h3>
        <p style="margin-bottom:.6rem"><?= e($place['desc']) ?></p>
        <span class="badge"><?= e($place['type']) ?></span>
        <span class="badge badge--gold">Best: <?= e($place['best']) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- -------------------------------------------------------------- symbols -->
<section class="section">
  <div class="section__head">
    <h2>National identity</h2>
    <p><a href="<?= e(bd_url('about')) ?>" style="color:var(--green-300)">Full national profile →</a></p>
  </div>

  <div class="grid grid--3">
    <?php foreach ($symbols as $i => $symbol): ?>
      <div class="card" data-reveal="<?= $i * 45 ?>">
        <h3><span aria-hidden="true"><?= $symbol['emoji'] ?></span> <?= e($symbol['item']) ?></h3>
        <p><?= e($symbol['value']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ------------------------------------------------------------ emergency -->
<section class="section">
  <div class="section__head">
    <h2>If you need help right now</h2>
    <p>National hotlines, free from any Bangladeshi number.</p>
  </div>

  <div class="grid grid--4">
    <?php foreach (array_slice(bd_nation('emergency'), 0, 4) as $i => $line): ?>
      <a class="card" href="tel:<?= e($line['number']) ?>" data-reveal="<?= $i * 50 ?>">
        <div style="font-size:1.8rem"><?= $line['emoji'] ?></div>
        <div style="font:700 1.8rem/1.1 var(--mono); color:var(--red-300)"><?= e($line['number']) ?></div>
        <p><?= e($line['service']) ?></p>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
