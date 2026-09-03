<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** About Us: the people behind AskBangladesh and how the site is built. */

$seo = bd_seo(bd_page_seo('about-us') + [
    'type'        => 'article',
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'About Us'],
    ],
    'jsonld' => [bd_jsonld_about_page()],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">👋 Who we are</span>
  <h1>About Us</h1>
  <p>
    <?= e(APP_NAME) ?> is a small, independent project with one job: make reliable information
    about Bangladesh easy to find, easy to read and free for everyone. This page is about the
    team behind the site — for the country itself, see
    <a href="<?= e(bd_url('about')) ?>">About Bangladesh</a>.
  </p>
</div>

<!-- ---------------------------------------------------------------- team -->
<section class="section" id="team">
  <div class="section__head">
    <h2><?= e(APP_NAME) ?> Team</h2>
    <p>Who builds this site, and why.</p>
  </div>

  <div class="card founder" data-reveal>
    <div class="founder__avatar" aria-hidden="true">🇧🇩</div>
    <div class="founder__body">
      <h3 style="margin-top:0">Towfique Ahmed — Founder</h3>
      <p>
        Towfique Ahmed is the founder of AskBangladesh.com. In my own words, the web has been an
        integral part of my working life for well over a decade. I have built and shipped many
        sites, both simple and complex, and I have spent years helping other people fix theirs.
      </p>
      <p>
        Along the way I kept hitting the same wall: finding clear, accurate, up-to-date
        information about Bangladesh in English was far harder than it should be. Facts sat in
        scanned PDFs, half-abandoned government pages and news articles that contradicted each
        other. I accidentally got caught up in collecting that data myself — districts, upazilas,
        population, symbols, history — and eventually launched AskBangladesh with a simple
        thought, <em>“put data first; let people choose what they want.”</em>
      </p>
      <p>
        Everything published here is checked against official sources and updated as the country
        changes. I am continually learning and improving how we source and verify things to reduce
        any inaccuracies. If you spot something wrong, tell me — I would rather fix it than defend it.
      </p>
      <p style="margin-bottom:0">
        <a class="btn btn--primary" href="https://www.linkedin.com/in/towfiq28/" target="_blank" rel="noopener noreferrer">
          Connect on LinkedIn
        </a>
      </p>
    </div>
  </div>
</section>

<!-- --------------------------------------------------------- what we do -->
<section class="section">
  <div class="section__head">
    <h2>What we stand for</h2>
    <p>The rules we hold ourselves to on every page.</p>
  </div>

  <div class="grid grid--3">
    <div class="tile" data-reveal>
      <span class="tile__icon" aria-hidden="true">📊</span>
      <h3>Data first</h3>
      <p>Numbers come from official sources — the Bangladesh Bureau of Statistics, Bangladesh Bank
        and government portals — not from other blogs.</p>
    </div>
    <div class="tile" data-reveal="60">
      <span class="tile__icon" aria-hidden="true">🔄</span>
      <h3>Kept current</h3>
      <p>Rates, office holders and figures are reviewed on a schedule, so a page you read today
        reflects the country today.</p>
    </div>
    <div class="tile" data-reveal="120">
      <span class="tile__icon" aria-hidden="true">🆓</span>
      <h3>Free and open</h3>
      <p>No paywalls, no sign-up walls. Every tool and dataset on the site is available to anyone
        who lands on it.</p>
    </div>
    <div class="tile" data-reveal="180">
      <span class="tile__icon" aria-hidden="true">⚡</span>
      <h3>Fast on any phone</h3>
      <p>Most visitors read on mobile data, so pages are built light and load quickly on a slow
        connection.</p>
    </div>
    <div class="tile" data-reveal="240">
      <span class="tile__icon" aria-hidden="true">🧭</span>
      <h3>Plain English</h3>
      <p>No jargon and no filler. If a fact needs context to make sense, we give the context.</p>
    </div>
    <div class="tile" data-reveal="300">
      <span class="tile__icon" aria-hidden="true">✉️</span>
      <h3>Open to corrections</h3>
      <p>Found an error or have data to share? <a href="<?= e(bd_url('contact')) ?>">Contact us</a>
        and we will look into it.</p>
    </div>
  </div>
</section>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
