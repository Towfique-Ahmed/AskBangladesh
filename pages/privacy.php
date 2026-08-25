<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');

$seo = bd_seo(bd_page_seo('privacy') + [
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Privacy Policy'],
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🔒 Legal</span>
  <h1>Privacy Policy</h1>
  <p>Last updated: 25 August 2026.</p>
</div>

<section class="section" style="max-width:52rem">
  <div class="card" data-reveal>
    <h2>Who we are</h2>
    <p><?= e(APP_NAME) ?> (<a href="<?= e(bd_abs_url()) ?>"><?= e(bd_abs_url()) ?></a>) is an informational website about Bangladesh, operated by <a href="https://towfique.com" target="_blank" rel="noopener noreferrer">towfique.com</a>.</p>
  </div>

  <div class="card" data-reveal="40" style="margin-top:1rem">
    <h2>Information we collect</h2>
    <p>We do <strong>not</strong> collect personal information such as names, email addresses, or phone numbers through this website. We do not have user accounts, login forms, or registration systems.</p>
    <h3>Analytics</h3>
    <p>We use Google Analytics 4 to understand how visitors use the site. Google Analytics collects anonymised data including:</p>
    <ul>
      <li>Pages visited and time spent</li>
      <li>Approximate geographic location (country/city level)</li>
      <li>Device type, browser and operating system</li>
      <li>Referral source</li>
    </ul>
    <p>This data is aggregated and cannot identify individual visitors. You can opt out of Google Analytics by installing the <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener noreferrer">Google Analytics Opt-out Browser Add-on</a>.</p>
  </div>

  <div class="card" data-reveal="80" style="margin-top:1rem">
    <h2>Cookies</h2>
    <p>This site uses cookies only for Google Analytics and for remembering your theme preference (light/dark mode). We do not use advertising cookies or tracking pixels.</p>
  </div>

  <div class="card" data-reveal="120" style="margin-top:1rem">
    <h2>Third-party services</h2>
    <p>We use the following external services:</p>
    <ul>
      <li><strong>Google Analytics</strong> — for anonymous usage statistics</li>
      <li><strong>open.er-api.com</strong> — for live currency exchange rates</li>
      <li><strong>gold-api.com</strong> — for live gold price data</li>
    </ul>
    <p>These services have their own privacy policies. We do not share any personal data with them.</p>
  </div>

  <div class="card" data-reveal="160" style="margin-top:1rem">
    <h2>Data security</h2>
    <p>The website is served over HTTPS. No personal data is stored on our servers. Cached API responses (exchange rates, gold prices) contain no user data.</p>
  </div>

  <div class="card" data-reveal="200" style="margin-top:1rem">
    <h2>Children's privacy</h2>
    <p>This site does not knowingly collect any information from children under 13. The site contains general reference information about Bangladesh suitable for all ages.</p>
  </div>

  <div class="card" data-reveal="240" style="margin-top:1rem">
    <h2>Changes to this policy</h2>
    <p>We may update this privacy policy from time to time. Any changes will be posted on this page with an updated date.</p>
  </div>

  <div class="card" data-reveal="280" style="margin-top:1rem">
    <h2>Contact</h2>
    <p>If you have questions about this privacy policy, please <a href="<?= e(bd_url('contact')) ?>">contact us</a>.</p>
  </div>
</section>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
