<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Government structure, online services and emergency hotlines. */

$profile   = bd_nation('profile');
$offices   = bd_nation('government');
$services  = bd_nation('eservices');
$emergency = bd_nation('emergency');

$seo = bd_seo(bd_page_seo('government') + [
    'type'        => 'article',
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Government'],
    ],
    'jsonld' => [bd_jsonld_government_services($services)],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🏛️ The state</span>
  <h1>Government &amp; public services</h1>
  <p>
    <?= e($profile['official_name']) ?> is a <?= e(strtolower($profile['government'])) ?>.
    Below: how the state is organised, the online services citizens use most, and the numbers to
    call when something goes wrong.
  </p>
</div>

<!-- ------------------------------------------------------------ emergency -->
<section class="section" style="margin-top:0">
  <div class="section__head">
    <h2>Emergency numbers</h2>
    <p>Free to call from any Bangladeshi number, 24 hours a day.</p>
  </div>

  <div class="grid grid--3">
    <?php foreach ($emergency as $i => $line): ?>
      <a class="card" href="tel:<?= e($line['number']) ?>" data-reveal="<?= ($i % 9) * 40 ?>"
         style="border-color:color-mix(in srgb, var(--red-500) 26%, transparent)">
        <div style="font-size:1.7rem"><?= $line['emoji'] ?></div>
        <div style="font:700 1.9rem/1.1 var(--mono);color:var(--red-300)"><?= e($line['number']) ?></div>
        <p style="margin-top:.3rem"><?= e($line['service']) ?></p>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- ------------------------------------------------------------- structure -->
<section class="section">
  <div class="section__head">
    <h2>How the state is organised</h2>
  </div>

  <div class="grid grid--2">
    <?php foreach ($offices as $i => $office): ?>
      <article class="card" data-reveal="<?= ($i % 6) * 50 ?>">
        <h3>🏛️ <?= e($office['office']) ?></h3>
        <p style="margin-bottom:.7rem"><?= e($office['role']) ?></p>
        <div class="mapinfo__rows">
          <div class="mapinfo__row"><span>Seat</span><span><?= e($office['seat']) ?></span></div>
        </div>
        <a class="tile__arrow" href="<?= e($office['web']) ?>" target="_blank" rel="noopener noreferrer">
          <?= e(parse_url($office['web'], PHP_URL_HOST) ?: $office['web']) ?> ↗
        </a>
      </article>
    <?php endforeach; ?>
  </div>

  <?php
  $curPresident = bd_current_leader('presidents');
  $curPm        = bd_current_leader('prime_ministers');
  ?>
  <div class="grid grid--2" style="margin-top:1.2rem">
    <a class="tile" href="<?= e(bd_url('presidents')) ?>">
      <span class="tile__icon" aria-hidden="true">🎖️</span>
      <h3>Presidents of Bangladesh</h3>
      <?php if ($curPresident): ?>
        <p style="margin-bottom:.7rem">
          Current head of state: <strong><?= e($curPresident['name']) ?></strong>,
          in office since <?= e(explode(' – ', $curPresident['term'])[0]) ?>.
        </p>
      <?php endif; ?>
      <span class="tile__arrow">See every President since 1971 →</span>
    </a>
    <a class="tile" href="<?= e(bd_url('prime-ministers')) ?>">
      <span class="tile__icon" aria-hidden="true">🧑‍💼</span>
      <h3>Prime Ministers of Bangladesh</h3>
      <?php if ($curPm): ?>
        <p style="margin-bottom:.7rem">
          Current head of government: <strong><?= e($curPm['name']) ?></strong>,
          in office since <?= e(explode(' – ', $curPm['term'])[0]) ?>.
        </p>
      <?php endif; ?>
      <span class="tile__arrow">See every Prime Minister since 1971 →</span>
    </a>
  </div>
</section>

<!-- ------------------------------------------------------------- services -->
<section class="section">
  <div class="section__head">
    <h2>Online services</h2>
    <p>The e-government portals citizens use most.</p>
  </div>

  <div class="card" data-reveal style="margin-bottom:1.2rem">
    <div class="field">
      <label for="service-filter">Find a service</label>
      <input type="search" class="input" id="service-filter" data-filter-input
             placeholder="Try “passport”, “NID”, “tax”, “licence”, “land”…">
    </div>
  </div>

  <div class="grid grid--3">
    <?php foreach ($services as $i => $service): ?>
      <a class="tile" href="<?= e($service['url']) ?>" target="_blank" rel="noopener noreferrer"
         data-reveal="<?= ($i % 9) * 40 ?>"
         data-filter-item="<?= e($service['name'] . ' ' . $service['desc'] . ' ' . $service['url']) ?>">
        <span class="tile__icon" aria-hidden="true"><?= $service['emoji'] ?></span>
        <h3><?= e($service['name']) ?></h3>
        <p style="margin-bottom:.7rem"><?= e($service['desc']) ?></p>
        <span class="tile__arrow"><?= e(parse_url($service['url'], PHP_URL_HOST) ?: '') ?> ↗</span>
      </a>
    <?php endforeach; ?>
  </div>

  <p data-filter-empty style="display:none;text-align:center;color:var(--text-mute);padding:2.5rem 1rem">
    No service matches that. Try “passport”, “birth”, “tax” or “police”.
  </p>
</section>

<!-- --------------------------------------------------------- admin layers -->
<section class="section">
  <div class="section__head">
    <h2>Administrative layers</h2>
    <p>From the capital down to the union council.</p>
  </div>

  <div class="statgrid" data-reveal>
    <div class="stat"><div class="stat__value" data-count-to="<?= $profile['divisions'] ?>">0</div><div class="stat__label">Divisions</div></div>
    <div class="stat"><div class="stat__value" data-count-to="<?= $profile['districts'] ?>">0</div><div class="stat__label">Districts</div></div>
    <div class="stat"><div class="stat__value" data-count-to="<?= $profile['upazilas'] ?>">0</div><div class="stat__label">Upazilas</div></div>
    <div class="stat"><div class="stat__value" data-count-to="<?= $profile['unions'] ?>">0</div><div class="stat__label">Union councils</div></div>
    <div class="stat"><div class="stat__value" data-count-to="<?= $profile['city_corps'] ?>">0</div><div class="stat__label">City corporations</div></div>
  </div>
</section>

<div class="notice" data-reveal>
  <span>🔗</span>
  <p style="margin:0">
    Every link here points to an official <code>.gov.bd</code> domain. Government portals change
    addresses from time to time — if a link fails, start at
    <strong>bangladesh.gov.bd</strong>, the national portal, or dial <strong>333</strong>.
  </p>
</div>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
