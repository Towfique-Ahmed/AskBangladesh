<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Currency converter for the Bangladeshi Taka. */

$data       = bd_exchange_rates();
$rates      = $data['rates'];
$currencies = bd_currencies();

$seo = bd_seo(bd_page_seo('currency') + [
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Currency Converter'],
    ],
]);

require APP_ROOT . '/includes/layout/header.php';

// The corridors that matter most for remittances into Bangladesh.
$corridors = ['USD', 'SAR', 'AED', 'MYR', 'GBP', 'KWD', 'EUR', 'QAR', 'OMR', 'SGD', 'INR', 'CAD'];
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">💱 Money</span>
  <h1>Taka currency converter</h1>
  <p>
    The Bangladeshi Taka (BDT, ৳) against <?= count($currencies) - 1 ?> world currencies.
    <?php if ($data['live']): ?>
      Rates are live from <?= e($data['source']) ?>, refreshed hourly.
    <?php else: ?>
      Live rates are unreachable right now, so these are bundled indicative figures.
    <?php endif; ?>
  </p>
</div>

<div class="card" data-reveal>
  <form class="converter" id="currency-tool" onsubmit="return false">
    <div class="field">
      <label for="cur-amount">Amount</label>
      <input type="number" class="input" id="cur-amount" value="1000" min="0" step="any" inputmode="decimal">
    </div>

    <div class="chips">
      <?php foreach ([100, 500, 1000, 5000, 10000, 100000] as $quick): ?>
        <button type="button" class="chip" data-quick-amount="<?= $quick ?>"><?= bd_num($quick) ?></button>
      <?php endforeach; ?>
    </div>

    <div class="converter__row">
      <div class="field">
        <label for="cur-from">From</label>
        <select class="input" id="cur-from">
          <?php foreach ($currencies as $code => $meta): ?>
            <option value="<?= e($code) ?>"<?= $code === 'BDT' ? ' selected' : '' ?>>
              <?= $meta['flag'] ?> <?= e($code) ?> — <?= e($meta['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <button type="button" class="swapbtn" id="cur-swap" aria-label="Swap the two currencies">⇄</button>

      <div class="field">
        <label for="cur-to">To</label>
        <select class="input" id="cur-to">
          <?php foreach ($currencies as $code => $meta): ?>
            <option value="<?= e($code) ?>"<?= $code === 'USD' ? ' selected' : '' ?>>
              <?= $meta['flag'] ?> <?= e($code) ?> — <?= e($meta['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="result">
      <div class="result__value" id="cur-result">—</div>
      <div class="result__meta" id="cur-meta"></div>
    </div>
  </form>
</div>

<script type="application/json" id="currency-rates"><?= json_encode($rates) ?></script>

<!-- ------------------------------------------------------------ corridors -->
<section class="section">
  <div class="section__head">
    <h2>Remittance corridors</h2>
    <p>What one unit is worth in Taka — the currencies Bangladeshis abroad send home most.</p>
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
    <h2>Full rate table</h2>
    <p>Updated <?= e((new DateTimeImmutable($data['updated']))->setTimezone(new DateTimeZone(BD_TIMEZONE))->format('j M Y, H:i')) ?> BST.</p>
  </div>

  <div class="card" data-reveal style="margin-bottom:1rem">
    <div class="field">
      <label for="rate-filter">Filter currencies</label>
      <input type="search" class="input" id="rate-filter" data-filter-input placeholder="Try “dirham”, “EUR”, “rupee”…">
    </div>
  </div>

  <div class="tablewrap" data-reveal>
    <table>
      <thead>
        <tr><th>Currency</th><th>Code</th><th class="num">1 unit in ৳</th><th class="num">৳1 in units</th></tr>
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
    These are mid-market reference rates. Banks, exchange houses and remittance operators apply
    their own spread and fees, and Bangladesh Bank sets the official interbank rate. Check with your
    bank before making a transfer.
  </p>
</div>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
