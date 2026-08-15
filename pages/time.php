<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Live Bangladesh clock, world clocks, time-zone converter and countdowns. */

$clocks     = bd_world_clocks();
$countdowns = bd_countdowns();

$seo = bd_seo(bd_page_seo('time') + [
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Time'],
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🕰️ Time in Bangladesh</span>
  <h1>What time is it in Bangladesh?</h1>
  <p>
    Bangladesh Standard Time (BST) is <strong>UTC+06:00</strong> across the whole country, all year
    round. There is no daylight saving, so the offset from your own city never changes with the seasons.
  </p>
</div>

<div class="grid grid--2">
  <div class="clockface" data-reveal>
    <div class="clockface__time" data-bd-clock>--:--:--</div>
    <div class="clockface__date" data-bd-date>&nbsp;</div>
    <div class="clockface__zone">Asia/Dhaka · UTC+06:00 · BST</div>
  </div>

  <div class="clockface" data-reveal="80">
    <svg class="analog" viewBox="0 0 200 200" aria-hidden="true">
      <circle class="analog__face" cx="100" cy="100" r="94"/>
      <?php for ($i = 0; $i < 12; $i++):
          $angle = deg2rad($i * 30);
          $x1 = 100 + sin($angle) * 82; $y1 = 100 - cos($angle) * 82;
          $x2 = 100 + sin($angle) * 72; $y2 = 100 - cos($angle) * 72;
      ?>
        <line class="analog__tick" x1="<?= round($x1, 1) ?>" y1="<?= round($y1, 1) ?>"
              x2="<?= round($x2, 1) ?>" y2="<?= round($y2, 1) ?>"/>
      <?php endfor; ?>
      <line class="analog__hand analog__hand--h" data-hand="h" x1="100" y1="100" x2="100" y2="56"/>
      <line class="analog__hand analog__hand--m" data-hand="m" x1="100" y1="100" x2="100" y2="36"/>
      <line class="analog__hand analog__hand--s" data-hand="s" x1="100" y1="112" x2="100" y2="28"/>
      <circle cx="100" cy="100" r="5" fill="var(--red-500)"/>
    </svg>
    <div class="clockface__zone">Dhaka, live</div>
  </div>
</div>

<!-- ------------------------------------------------------------- converter -->
<section class="section">
  <div class="section__head">
    <h2>Time converter</h2>
    <p>Pick a moment in one city and read it in another.</p>
  </div>

  <div class="card" data-reveal>
    <form class="converter" id="time-tool" onsubmit="return false">
      <div class="field">
        <label for="tc-datetime">Date and time</label>
        <input type="datetime-local" class="input" id="tc-datetime">
      </div>

      <div class="converter__row">
        <div class="field">
          <label for="tc-from">From</label>
          <select class="input" id="tc-from">
            <?php foreach ($clocks as $clock): ?>
              <option value="<?= e($clock['zone']) ?>"<?= $clock['zone'] === 'Asia/Dhaka' ? ' selected' : '' ?>>
                <?= $clock['flag'] ?> <?= e($clock['city']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <button type="button" class="swapbtn" id="tc-swap" aria-label="Swap the two cities">⇄</button>

        <div class="field">
          <label for="tc-to">To</label>
          <select class="input" id="tc-to">
            <?php foreach ($clocks as $clock): ?>
              <option value="<?= e($clock['zone']) ?>"<?= $clock['zone'] === 'America/New_York' ? ' selected' : '' ?>>
                <?= $clock['flag'] ?> <?= e($clock['city']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="result">
        <div class="result__value" id="tc-result">—</div>
        <div class="result__meta" id="tc-meta"></div>
      </div>
    </form>
  </div>
</section>

<!-- ---------------------------------------------------------- world clocks -->
<section class="section">
  <div class="section__head">
    <h2>World clocks</h2>
    <p>Live, and correct for daylight saving wherever it applies.</p>
  </div>

  <div class="grid grid--5">
    <?php foreach ($clocks as $i => $clock): ?>
      <div class="card" data-reveal="<?= ($i % 10) * 30 ?>" style="padding:1rem">
        <div style="font-size:1.3rem"><?= $clock['flag'] ?></div>
        <h3 style="font-size:.94rem;margin:.2rem 0 .1rem"><?= e($clock['city']) ?></h3>
        <div style="font:700 1.25rem/1.2 var(--mono);color:var(--green-300)"
             data-zone-clock="<?= e($clock['zone']) ?>">--:--:--</div>
        <div style="font-size:.72rem;color:var(--text-mute)" data-zone-day="<?= e($clock['zone']) ?>"></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ------------------------------------------------------------ countdowns -->
<section class="section">
  <div class="section__head">
    <h2>Counting down</h2>
    <p>Live timers to the next national days.</p>
  </div>

  <div class="grid grid--2">
    <?php foreach ($countdowns as $i => $day): ?>
      <div class="card" data-reveal="<?= $i * 60 ?>">
        <h3><?= $day['emoji'] ?> <?= e($day['name']) ?></h3>
        <p style="margin-bottom:.9rem">
          <?= e((new DateTimeImmutable($day['target']))->format('l, j F Y')) ?>
        </p>
        <div class="countdown" data-countdown="<?= e($day['target']) ?>">
          <div class="countdown__cell"><div class="countdown__num" data-cd-d>0</div><div class="countdown__lbl">Days</div></div>
          <div class="countdown__cell"><div class="countdown__num" data-cd-h>00</div><div class="countdown__lbl">Hours</div></div>
          <div class="countdown__cell"><div class="countdown__num" data-cd-m>00</div><div class="countdown__lbl">Minutes</div></div>
          <div class="countdown__cell"><div class="countdown__num" data-cd-s>00</div><div class="countdown__lbl">Seconds</div></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<div class="notice" data-reveal>
  <span>🌍</span>
  <p style="margin:0">
    Working with Bangladesh from abroad? Dhaka is 6 hours ahead of UTC, 1 hour ahead of Dubai,
    2 hours behind Tokyo, and 10 or 11 hours ahead of New York depending on US daylight saving.
  </p>
</div>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
