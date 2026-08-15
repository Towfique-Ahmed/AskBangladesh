</main>

<footer class="footer">
  <div class="footer__inner">
    <div class="footer__brand">
      <h3>Ask<em>Bangladesh</em></h3>
      <p>A single window on the land of rivers — 64 districts, the world’s largest delta, and everything a Bangladeshi or a visitor might want to look up.</p>
      <p class="footer__flag" aria-hidden="true">🇧🇩</p>
    </div>

    <div class="footer__cols">
      <div>
        <h4>Explore</h4>
        <a href="<?= e(bd_url('map')) ?>">Interactive map</a>
        <a href="<?= e(bd_url('districts')) ?>">All 64 districts</a>
        <a href="<?= e(bd_url('geography')) ?>">Geography &amp; mountains</a>
        <a href="<?= e(bd_url('travel')) ?>">Travel places</a>
        <a href="<?= e(bd_url('transport')) ?>">Roads &amp; transport</a>
      </div>
      <div>
        <h4>Tools</h4>
        <a href="<?= e(bd_url('time')) ?>">Clock &amp; time converter</a>
        <a href="<?= e(bd_url('currency')) ?>">Currency converter</a>
        <a href="<?= e(bd_url('gold')) ?>">Gold price &amp; converter</a>
        <a href="<?= e(bd_url('prayer')) ?>">Prayer times</a>
        <a href="<?= e(bd_url('search')) ?>">Global search</a>
      </div>
      <div>
        <h4>Know Bangladesh</h4>
        <a href="<?= e(bd_url('government')) ?>">Government &amp; e-services</a>
        <a href="<?= e(bd_url('religion')) ?>">Religions &amp; festivals</a>
        <a href="<?= e(bd_url('about')) ?>">National profile</a>
        <a href="<?= e(bd_url('about', ['q' => 'history'])) ?>">History timeline</a>
        <a href="<?= e(bd_url('about', ['q' => 'facts'])) ?>">Surprising facts</a>
      </div>
      <div>
        <h4>Emergency</h4>
        <a href="tel:999">999 — National emergency</a>
        <a href="tel:333">333 — Government info</a>
        <a href="tel:109">109 — Women &amp; children</a>
        <a href="tel:1098">1098 — Child helpline</a>
        <a href="tel:16263">16263 — Health line</a>
      </div>
    </div>
  </div>

  <div class="footer__bar">
    <p>© <?= date('Y') ?> <?= e(APP_NAME) ?> v<?= e(APP_VERSION) ?> — built with PHP, no framework, no tracker.</p>
    <p class="footer__note">Exchange, gold and prayer figures are indicative. Confirm with Bangladesh Bank, BAJUS and your local mosque before acting on them.</p>
  </div>
</footer>

<button type="button" class="totop" id="to-top" aria-label="Back to top">↑</button>

<script src="assets/js/app.js?v=<?= e(APP_VERSION) ?>" defer></script>
</body>
</html>
