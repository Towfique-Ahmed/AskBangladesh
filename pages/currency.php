<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Currency converter for the Bangladeshi Taka. */

$data       = bd_exchange_rates();
$rates      = $data['rates'];
$currencies = bd_currencies();

$seo = bd_seo(bd_page_seo('currency') + [
    'breadcrumbs' => [
        ['name' => t('Home'), 'url' => bd_url()],
        ['name' => t('Currency Converter')],
    ],
    'jsonld' => [
        bd_jsonld_web_app(
            'BDT Currency Converter',
            'Convert the Bangladeshi Taka against 30+ world currencies with live exchange rates.',
            'currency'
        ),
    ],
]);

require APP_ROOT . '/includes/layout/header.php';

// The corridors that matter most for remittances into Bangladesh.
$corridors = ['USD', 'SAR', 'AED', 'MYR', 'GBP', 'KWD', 'EUR', 'QAR', 'OMR', 'SGD', 'INR', 'CAD'];
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">💱 <?= e(t('Money')) ?></span>
  <h1><?= e(t('Taka currency converter')) ?></h1>
  <p>
    <?= e(tf('The Bangladeshi Taka (BDT, ৳) against %s world currencies.', bd_n(count($currencies) - 1))) ?>
    <?php if ($data['live']): ?>
      <?= e(tf('Rates are live from %s, refreshed hourly.', $data['source'])) ?>
    <?php else: ?>
      <?= e(t('Live rates are unreachable right now, so these are bundled indicative figures.')) ?>
    <?php endif; ?>
  </p>
</div>

<div class="card" data-reveal>
  <form class="converter" id="currency-tool" onsubmit="return false">
    <div class="field">
      <label for="cur-amount"><?= e(t('Amount')) ?></label>
      <input type="number" class="input" id="cur-amount" value="1000" min="0" step="any" inputmode="decimal">
    </div>

    <div class="chips">
      <?php foreach ([100, 500, 1000, 5000, 10000, 100000] as $quick): ?>
        <button type="button" class="chip" data-quick-amount="<?= $quick ?>"><?= bd_num($quick) ?></button>
      <?php endforeach; ?>
    </div>

    <div class="converter__row">
      <div class="field">
        <label for="cur-from"><?= e(t('From')) ?></label>
        <select class="input" id="cur-from">
          <?php foreach ($currencies as $code => $meta): ?>
            <option value="<?= e($code) ?>"<?= $code === 'BDT' ? ' selected' : '' ?>>
              <?= $meta['flag'] ?> <?= e($code) ?> - <?= e($meta['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <button type="button" class="swapbtn" id="cur-swap" aria-label="Swap the two currencies">⇄</button>

      <div class="field">
        <label for="cur-to"><?= e(t('To')) ?></label>
        <select class="input" id="cur-to">
          <?php foreach ($currencies as $code => $meta): ?>
            <option value="<?= e($code) ?>"<?= $code === 'USD' ? ' selected' : '' ?>>
              <?= $meta['flag'] ?> <?= e($code) ?> - <?= e($meta['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="result">
      <div class="result__value" id="cur-result">-</div>
      <div class="result__meta" id="cur-meta"></div>
    </div>
  </form>
</div>

<script type="application/json" id="currency-rates"><?= json_encode($rates) ?></script>

<!-- ------------------------------------------------------------ corridors -->
<section class="section">
  <div class="section__head">
    <h2><?= e(t('Remittance corridors')) ?></h2>
    <p><?= t('What one unit is worth in Taka - the currencies Bangladeshis abroad send home most.') ?></p>
  </div>

  <div class="grid grid--4">
    <?php foreach ($corridors as $i => $code):
        if (!isset($rates[$code], $currencies[$code]) || $rates[$code] <= 0) { continue; }
        $perUnit = 1 / $rates[$code];
    ?>
      <div class="card" data-reveal="<?= ($i % 8) * 35 ?>">
        <div style="font-size:1.5rem"><?= $currencies[$code]['flag'] ?></div>
        <h3 style="margin:.3rem 0 0"><?= e($code) ?></h3>
        <div style="font:700 1.5rem/1.2 var(--mono);color:var(--green-300)">
          ৳ <?= bd_num($perUnit, 2) ?>
        </div>
        <p style="font-size:.8rem"><?= e($currencies[$code]['name']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ----------------------------------------------------------- rate table -->
<section class="section">
  <div class="section__head">
    <h2><?= e(t('Full rate table')) ?></h2>
    <p><?= e(t('Updated')) ?> <?= e((new DateTimeImmutable($data['updated']))->setTimezone(new DateTimeZone(BD_TIMEZONE))->format('j M Y, H:i')) ?> <?= e(t('BST')) ?>.</p>
  </div>

  <div class="card" data-reveal style="margin-bottom:1rem">
    <div class="field">
      <label for="rate-filter"><?= e(t('Filter currencies')) ?></label>
      <input type="search" class="input" id="rate-filter" data-filter-input placeholder="Try “dirham”, “EUR”, “rupee”…">
    </div>
  </div>

  <div class="tablewrap" data-reveal>
    <table>
      <thead>
        <tr><th><?= e(t('Currency')) ?></th><th><?= e(t('Code')) ?></th><th class="num"><?= e(t('1 unit in ৳')) ?></th><th class="num"><?= e(t('৳1 in units')) ?></th></tr>
      </thead>
      <tbody>
        <?php foreach ($currencies as $code => $meta):
            if ($code === 'BDT' || !isset($rates[$code]) || $rates[$code] <= 0) { continue; }
        ?>
          <tr data-filter-item="<?= e($code . ' ' . $meta['name']) ?>">
            <td><?= $meta['flag'] ?> <?= e($meta['name']) ?></td>
            <td><span class="badge"><?= e($code) ?></span></td>
            <td class="num">৳ <?= bd_num(1 / $rates[$code], 4) ?></td>
            <td class="num"><?= bd_num($rates[$code], 6) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<div class="notice" data-reveal>
  <span>⚠️</span>
  <p style="margin:0">
    <?= t('These are mid-market reference rates. Banks, exchange houses and remittance operators apply their own spread and fees, and Bangladesh Bank sets the official interbank rate. Check with your bank before making a transfer.') ?>
  </p>
</div>

<!-- ---------------------------------------------------------------- notes -->
<section class="section">
  <div class="section__head">
    <h2><?= e(t('Using this converter well')) ?></h2>
  </div>
  <div class="grid grid--2">
    <div class="card" data-reveal>
      <h3><?= e(t('💡 Mid-market vs. what you actually get')) ?></h3>
      <p>
        <?= t('The rate shown here is the mid-market rate - the midpoint between the buy and sell price on the wholesale currency market, with no markup added. A bank, exchange house or mobile remittance service always applies its own spread on top, and sometimes a flat fee as well, so the amount that lands in a beneficiary\'s account is a little less than a simple mid-market calculation suggests. Comparing the effective rate an operator actually offers against the mid-market rate here is the easiest way to see how competitive it is.') ?>
      </p>
    </div>
    <div class="card" data-reveal="80">
      <h3><?= e(t('🏦 Why the Taka is managed, not freely floating')) ?></h3>
      <p>
        <?= t('Bangladesh Bank, the central bank, oversees the exchange rate regime rather than leaving the Taka to float entirely freely, intervening from time to time to smooth large swings and to manage the country\'s foreign exchange reserves. This matters most for remittances and import bills, which is why the rate quoted by a bank or a mobile money service can differ slightly from both the mid-market rate and from what a rival operator quotes on the same day.') ?>
      </p>
    </div>
  </div>
</section>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
