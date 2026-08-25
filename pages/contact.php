<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');

$seo = bd_seo(bd_page_seo('contact') + [
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Contact Us'],
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">📬 Get in touch</span>
  <h1>Contact us</h1>
  <p>
    Have a question, spotted an error, or want to suggest an improvement? We would love to hear
    from you.
  </p>
</div>

<section class="section" style="max-width:52rem">
  <div class="grid grid--2">
    <div class="card" data-reveal>
      <h3>🌐 Website</h3>
      <p>This project is built and maintained by:</p>
      <a class="tile__arrow" href="https://towfique.com" target="_blank" rel="noopener noreferrer">towfique.com ↗</a>
    </div>

    <div class="card" data-reveal="60">
      <h3>📧 Email</h3>
      <p>For questions, feedback or corrections:</p>
      <a class="tile__arrow" href="mailto:hello@towfique.com">hello@towfique.com</a>
    </div>
  </div>

  <div class="card" data-reveal="120" style="margin-top:1.2rem">
    <h2>What you can reach out about</h2>
    <div class="grid grid--2" style="margin-top:1rem">
      <div>
        <h3>🐛 Report an error</h3>
        <p>Found incorrect data — a wrong population figure, a broken government link, or a misplaced map pin? Let us know and we will fix it.</p>
      </div>
      <div>
        <h3>💡 Suggest a feature</h3>
        <p>Have an idea for a new tool, a missing dataset, or a page that would be useful? We are always looking to improve.</p>
      </div>
      <div>
        <h3>🤝 Collaboration</h3>
        <p>Interested in contributing data, translations, or integrations? We are open to partnerships that help make Bangladesh information more accessible.</p>
      </div>
      <div>
        <h3>📜 Legal</h3>
        <p>For any legal inquiries, DMCA requests, or questions about our <a href="<?= e(bd_url('privacy')) ?>">privacy policy</a> or <a href="<?= e(bd_url('terms')) ?>">terms</a>.</p>
      </div>
    </div>
  </div>
</section>

<div class="notice" data-reveal>
  <span>⏰</span>
  <p style="margin:0">
    We aim to respond within 48 hours. For urgent matters, please include "URGENT" in your subject line.
  </p>
</div>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
