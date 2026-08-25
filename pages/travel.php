<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Travel destinations, filterable by type. */

$travel = bd_places('travel');

$types = [];
foreach ($travel as $place) { $types[$place['type']] = true; }
$types = array_keys($types);
sort($types);

$seo = bd_seo(bd_page_seo('travel') + [
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Travel'],
    ],
    'jsonld' => [
        bd_jsonld_item_list(
            'Best Places to Visit in Bangladesh',
            $travel,
            static fn (array $p): array => [
                '@type'       => 'TouristAttraction',
                'name'        => $p['name'],
                'description' => $p['desc'],
                'url'         => bd_abs_url('travel/' . bd_slug($p['name'])),
                'touristType' => $p['type'],
                'address'     => ['@type' => 'PostalAddress', 'addressCountry' => 'BD', 'addressLocality' => $p['district']],
            ]
        ),
    ],
]);

require APP_ROOT . '/includes/layout/header.php';

?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🧳 Travel Bangladesh</span>
  <h1>Where to go</h1>
  <p>
    The longest natural sea beach on Earth, the largest mangrove forest, a valley above the clouds,
    tea gardens that run to the horizon and three UNESCO World Heritage Sites — all inside a country
    smaller than the state of Illinois.
  </p>
</div>


<div class="card" data-reveal style="margin-bottom:1.4rem">
  <div class="field" style="margin-bottom:.9rem">
    <label for="travel-filter">Search destinations</label>
    <input type="search" class="input" id="travel-filter" data-filter-input
           placeholder="Try “beach”, “tea”, “UNESCO”, “Sylhet” or “island”…">
  </div>
  <div class="chips">
    <button type="button" class="chip is-active" data-filter-chip="all">Everything</button>
    <?php foreach ($types as $type): ?>
      <button type="button" class="chip" data-filter-chip="<?= e($type) ?>"><?= e($type) ?></button>
    <?php endforeach; ?>
  </div>
  <p style="margin:.9rem 0 0;color:var(--text-mute);font-size:.85rem">
    Showing <strong data-filter-count><?= count($travel) ?></strong> of <?= count($travel) ?> places.
  </p>
</div>

<div class="grid grid--3">
  <?php foreach ($travel as $i => $place): ?>
    <a class="tile" href="<?= e(bd_travel_url($place)) ?>" data-reveal="<?= ($i % 9) * 35 ?>"
             data-filter-item="<?= e($place['name'] . ' ' . $place['type'] . ' ' . $place['district'] . ' ' . $place['desc'] . ' ' . $place['best']) ?>"
             data-filter-group="<?= e($place['type']) ?>">
      <span class="tile__icon" aria-hidden="true"><?= $place['emoji'] ?></span>
      <h3><?= e($place['name']) ?></h3>
      <p style="margin-bottom:.9rem"><?= e($place['desc']) ?></p>
      <div style="display:flex;gap:.4rem;flex-wrap:wrap">
        <span class="badge"><?= e($place['type']) ?></span>
        <span class="badge badge--red"><?= e($place['district']) ?></span>
        <span class="badge badge--gold"><?= e($place['best']) ?></span>
      </div>
    </a>
  <?php endforeach; ?>
</div>

<p data-filter-empty style="display:none;text-align:center;color:var(--text-mute);padding:3rem 1rem">
  Nothing matches that search. Try “beach”, “hills”, “heritage” or a district name.
</p>

<!-- ------------------------------------------------------- travel advice -->
<section class="section">
  <div class="section__head"><h2>Practical notes for visitors</h2></div>
  <div class="grid grid--3">
    <div class="card" data-reveal>
      <h3>🛂 Getting in</h3>
      <p>Most nationalities need a visa. Visa on arrival is available at Dhaka and Chattogram airports
      for citizens of a set list of countries — check with a Bangladesh mission before you fly.</p>
    </div>
    <div class="card" data-reveal="60">
      <h3>💵 Money</h3>
      <p>The Taka (৳) is the only currency accepted. Cards work in city hotels and malls; carry cash
      outside the big cities. Mobile wallets like bKash and Nagad are everywhere.</p>
    </div>
    <div class="card" data-reveal="120">
      <h3>📅 Best time</h3>
      <p>November to February is cool, dry and the easiest for travel. The monsoon from June to
      September is when the haors, waterfalls and hills are at their most spectacular.</p>
    </div>
    <div class="card" data-reveal="180">
      <h3>🗣️ Language</h3>
      <p>Bangla is spoken everywhere. English is widely understood in cities and by younger people.
      “Dhonnobad” (thank you) and “kemon achen?” (how are you?) go a long way.</p>
    </div>
    <div class="card" data-reveal="240">
      <h3>🏔️ Hill Tracts permits</h3>
      <p>Parts of Bandarban, Rangamati and Khagrachhari need permission and a local guide. Check
      current rules with the district administration before planning a trek.</p>
    </div>
    <div class="card" data-reveal="300">
      <h3>🆘 If something goes wrong</h3>
      <p>Dial <strong>999</strong> for any emergency, or the Tourist Police on
      <strong>01320222222</strong>. Government information is on <strong>333</strong>.</p>
    </div>
  </div>
</section>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
