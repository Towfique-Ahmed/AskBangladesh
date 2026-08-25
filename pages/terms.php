<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');

$seo = bd_seo(bd_page_seo('terms') + [
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Terms & Conditions'],
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">📜 Legal</span>
  <h1>Terms &amp; Conditions</h1>
  <p>Last updated: 25 August 2026.</p>
</div>

<section class="section" style="max-width:52rem">
  <div class="card" data-reveal>
    <h2>Acceptance of terms</h2>
    <p>By accessing and using <?= e(APP_NAME) ?> (<a href="<?= e(bd_abs_url()) ?>"><?= e(bd_abs_url()) ?></a>), you agree to these terms and conditions. If you do not agree, please do not use the website.</p>
  </div>

  <div class="card" data-reveal="40" style="margin-top:1rem">
    <h2>Nature of content</h2>
    <p><?= e(APP_NAME) ?> is a free, informational reference site about Bangladesh. The content is provided for general information purposes only and should not be relied upon as professional, legal, financial or medical advice.</p>
    <ul>
      <li><strong>Exchange rates</strong> are mid-market reference rates and may differ from what banks and remittance operators offer.</li>
      <li><strong>Gold and silver prices</strong> are derived from international spot prices and are indicative — confirm with BAJUS or your jeweller before transacting.</li>
      <li><strong>Sunrise and sunset times</strong> are computed from geographic coordinates and may vary by a minute or two from official almanacs.</li>
      <li><strong>Government service links</strong> point to official .gov.bd domains. We are not affiliated with the Government of Bangladesh.</li>
    </ul>
  </div>

  <div class="card" data-reveal="80" style="margin-top:1rem">
    <h2>Intellectual property</h2>
    <p>All original content, design, code and branding on this site are the property of <?= e(APP_NAME) ?> and <a href="https://towfique.com" target="_blank" rel="noopener noreferrer">towfique.com</a>. You may not reproduce, distribute or create derivative works without prior written permission.</p>
    <p>Factual data about Bangladesh (district statistics, geographic coordinates, government structures) is in the public domain and is not claimed.</p>
  </div>

  <div class="card" data-reveal="120" style="margin-top:1rem">
    <h2>Limitation of liability</h2>
    <p><?= e(APP_NAME) ?> is provided "as is" without warranties of any kind, express or implied. We do not guarantee the accuracy, completeness or timeliness of any information on this site. In no event shall <?= e(APP_NAME) ?> or its operators be liable for any damages arising from the use of this website.</p>
  </div>

  <div class="card" data-reveal="160" style="margin-top:1rem">
    <h2>External links</h2>
    <p>This site links to government portals, external services and other websites. We are not responsible for the content, availability or privacy practices of those sites.</p>
  </div>

  <div class="card" data-reveal="200" style="margin-top:1rem">
    <h2>Modifications</h2>
    <p>We reserve the right to modify these terms at any time. Continued use of the site after changes constitutes acceptance of the updated terms.</p>
  </div>

  <div class="card" data-reveal="240" style="margin-top:1rem">
    <h2>Contact</h2>
    <p>Questions about these terms? <a href="<?= e(bd_url('contact')) ?>">Contact us</a>.</p>
  </div>
</section>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
