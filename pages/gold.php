<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Gold and silver price board plus a bhori / gram / ana converter. */

$prices = bd_gold_prices();
$units  = bd_weight_units();

$seo = bd_seo(bd_page_seo('gold') + [
    'breadcrumbs' => [
        ['name' => t('Home'), 'url' => bd_url()],
        ['name' => t('Gold Price')],
    ],
    'jsonld' => [
        bd_jsonld_web_app(
            'Bangladesh Gold Price Calculator',
            'Today\'s gold and silver prices in Bangladesh per bhori, gram and ana for 22K, 21K, 18K and traditional gold, with a weight converter.',
            'gold'
        ),
    ],
]);

require APP_ROOT . '/includes/layout/header.php';

// The converter needs grams-per-unit and a karat -> price-per-bhori lookup.
$unitGrams = [];
foreach ($units as $key => $unit) { $unitGrams[$key] = $unit['grams']; }

$priceLookup = ['gold' => [], 'silver' => []];
foreach ($prices['gold'] as $row)   { $priceLookup['gold'][$row['karat']]   = $row['bhori']; }
foreach ($prices['silver'] as $row) { $priceLookup['silver'][$row['karat']] = $row['bhori']; }
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🥇 <?= e(t('Gold & silver')) ?></span>
  <h1><?= e(t('Gold price in Bangladesh')) ?></h1>
  <p>
    <?= t('Prices follow the BAJUS category structure - 22, 21 and 18 karat hallmark gold plus traditional (sanatan) gold - quoted per <strong>bhori</strong> (11.664 g), the unit every jeweller in the country works in.') ?>
    <?= e($prices['live'] ? t('Derived from the live international spot price.') : t('Live spot pricing is unreachable, so these are indicative figures.')) ?>
  </p>
</div>

<!-- ---------------------------------------------------------------- board -->
<section class="section" style="margin-top:0">
  <div class="section__head">
    <h2><?= e(t('Today’s gold board')) ?></h2>
    <p><?= e(bd_lang() === 'bn' ? bd_today_long() . ' · ' . bd_bn_digits((new DateTimeImmutable($prices['updated']))->setTimezone(new DateTimeZone(BD_TIMEZONE))->format('H:i')) . ' ' . t('BST') : (new DateTimeImmutable($prices['updated']))->setTimezone(new DateTimeZone(BD_TIMEZONE))->format('l, j F Y · H:i') . ' BST') ?></p>
  </div>

  <div class="grid grid--4">
    <?php foreach ($prices['gold'] as $i => $row): ?>
      <div class="card" data-reveal="<?= $i * 55 ?>" style="border-color:color-mix(in srgb, var(--gold-500) 30%, transparent)">
        <span class="badge badge--gold"><?= e($row['karat']) ?> · <?= e(sprintf(t('%s%% pure'), bd_n((float) $row['purity'], 1))) ?></span>
        <h3 style="margin:.6rem 0 .2rem"><?= e(t($row['label'])) ?></h3>
        <div style="font:700 1.7rem/1.15 var(--mono);color:var(--gold-500)">৳ <?= bd_n($row['bhori']) ?></div>
        <div style="font-size:.78rem;color:var(--text-mute);margin-bottom:.7rem"><?= e(t('per bhori')) ?></div>
        <div class="mapinfo__rows">
          <div class="mapinfo__row"><span><?= e(t('Per gram')) ?></span><span>৳ <?= bd_n($row['gram'], 2) ?></span></div>
          <div class="mapinfo__row"><span><?= e(t('Per ana')) ?></span><span>৳ <?= bd_n($row['ana'], 2) ?></span></div>
          <div class="mapinfo__row"><span><?= e(t('Per troy oz')) ?></span><span>৳ <?= bd_n($row['ounce'], 0) ?></span></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ------------------------------------------------------------ converter -->
<section class="section">
  <div class="section__head">
    <h2><?= e(t('Weight & price converter')) ?></h2>
    <p><?= t('Enter any weight in any unit and read the value instantly.') ?></p>
  </div>

  <div class="card" data-reveal>
    <form class="converter" id="gold-tool" onsubmit="return false">
      <div class="converter__row" style="grid-template-columns:1fr 1fr">
        <div class="field">
          <label for="gold-qty"><?= e(t('Quantity')) ?></label>
          <input type="number" class="input" id="gold-qty" value="1" min="0" step="any" inputmode="decimal">
        </div>
        <div class="field">
          <label for="gold-unit"><?= e(t('Unit')) ?></label>
          <select class="input" id="gold-unit">
            <?php foreach ($units as $key => $unit): ?>
              <option value="<?= e($key) ?>"<?= $key === 'bhori' ? ' selected' : '' ?>>
                <?= e(t($unit['label'])) ?> (<?= rtrim(rtrim(number_format($unit['grams'], 4), '0'), '.') ?> g)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="converter__row" style="grid-template-columns:1fr 1fr">
        <div class="field">
          <label for="gold-metal"><?= e(t('Metal')) ?></label>
          <select class="input" id="gold-metal">
            <option value="gold" selected><?= e(t('🥇 Gold')) ?></option>
            <option value="silver"><?= e(t('🥈 Silver')) ?></option>
          </select>
        </div>
        <div class="field">
          <label for="gold-karat"><?= e(t('Category')) ?></label>
          <select class="input" id="gold-karat">
            <?php foreach ($prices['gold'] as $row): ?>
              <option value="<?= e($row['karat']) ?>"<?= $row['karat'] === '22K' ? ' selected' : '' ?>>
                <?= e(t($row['label'])) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="result">
        <div class="result__value" id="gold-result">-</div>
        <div class="result__meta" id="gold-meta"></div>
      </div>
    </form>
  </div>
</section>

<script type="application/json" id="gold-units"><?= json_encode($unitGrams) ?></script>
<script type="application/json" id="gold-prices"><?= json_encode($priceLookup) ?></script>

<!-- --------------------------------------------------------------- silver -->
<section class="section">
  <div class="section__head"><h2><?= e(t('Silver board')) ?></h2></div>
  <div class="tablewrap" data-reveal>
    <table>
      <thead><tr><th><?= e(t('Category')) ?></th><th class="num"><?= e(t('Per bhori')) ?></th><th class="num"><?= e(t('Per gram')) ?></th><th class="num"><?= e(t('Per ana')) ?></th></tr></thead>
      <tbody>
        <?php foreach ($prices['silver'] as $row): ?>
          <tr>
            <td>🥈 <?= e(t($row['label'])) ?></td>
            <td class="num">৳ <?= bd_n($row['bhori']) ?></td>
            <td class="num">৳ <?= bd_n($row['gram'], 2) ?></td>
            <td class="num">৳ <?= bd_n($row['ana'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<!-- ---------------------------------------------------------------- units -->
<section class="section">
  <div class="section__head">
    <h2><?= e(t('Understanding the units')) ?></h2>
    <p><?= t('Bengali jewellery weights, and how they relate to grams.') ?></p>
  </div>

  <div class="grid grid--3">
    <div class="card" data-reveal>
      <h3><?= e(t('⚖️ Bhori (ভরি)')) ?></h3>
      <p><?= t('The standard unit of the Bangladeshi jewellery trade, identical to the old tola: <strong>11.6638 grams</strong>. Every shop board quotes prices per bhori.') ?></p>
    </div>
    <div class="card" data-reveal="60">
      <h3><?= e(t('🔸 Ana (আনা)')) ?></h3>
      <p><?= t('One-sixteenth of a bhori, about <strong>0.729 g</strong>. Small ornaments and light chains are usually priced in ana.') ?></p>
    </div>
    <div class="card" data-reveal="120">
      <h3><?= e(t('🔹 Roti (রতি)')) ?></h3>
      <p><?= t('One-sixth of an ana, about <strong>0.121 g</strong>. The finest unit still in everyday use for stones and very light pieces.') ?></p>
    </div>
  </div>
</section>

<div class="notice" data-reveal>
  <span>💡</span>
  <p style="margin:0">
    The price you pay at a shop is the gold value <em>plus</em> a making charge (mojuri) and 5% VAT.
    Ask for the hallmark certificate and the itemised bill. Source: <?= e($prices['source']) ?>.
  </p>
</div>

<!-- ------------------------------------------------------------- explainer -->
<section class="section">
  <div class="section__head">
    <h2><?= e(t('How gold pricing works in Bangladesh')) ?></h2>
  </div>
  <div class="grid grid--2">
    <div class="card" data-reveal>
      <h3><?= e(t('⚖️ How BAJUS sets the board')) ?></h3>
      <p>
        <?= t('The Bangladesh Jewellers’ Association (BAJUS) revises and publishes gold prices for the whole country whenever the international spot price moves enough to matter, rather than on a fixed daily schedule. The published figure is a base price per bhori for each purity tier - 22K, 21K, 18K and traditional (sanatan) gold - that every jeweller in the country is expected to follow, so a shop in Sylhet and a shop in Khulna quote the same base price on the same day.') ?>
      </p>
    </div>
    <div class="card" data-reveal="80">
      <h3><?= e(t('🧾 Karat, purity and what changes the price')) ?></h3>
      <p>
        <?= t('Karat measures how much of a piece is pure gold out of 24 parts: 22K is about 91.6% pure, 21K about 87.5%, and 18K about 75%, with the remainder made up of alloy metals that add strength for everyday wear. Traditional (sanatan) gold has no fixed purity standard and is priced by local convention rather than a BAJUS-certified karat. Because the underlying international gold price moves throughout the day, the Taka price per bhori shown here reflects that spot price converted at the current exchange rate, and can differ slightly from the latest physical BAJUS notice until the board catches up.') ?>
      </p>
    </div>
  </div>
</section>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
