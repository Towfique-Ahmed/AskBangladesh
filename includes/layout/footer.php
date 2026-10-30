<?php defined('APP_ROOT') || exit('Direct access is not permitted.'); ?>
</main>

<footer class="footer">
  <div class="footer__inner">
    <div class="footer__brand">
      <h3>Ask<em>Bangladesh</em></h3>
      <p><?= e(t('A single window on the land of rivers - 64 districts, the world’s largest delta, and everything a Bangladeshi or a visitor might want to look up.')) ?></p>
      <p class="footer__flag" aria-hidden="true">🇧🇩</p>
    </div>

    <div class="footer__cols">
      <div>
        <h4><?= e(t('Explore')) ?></h4>
        <a href="<?= e(bd_url('map')) ?>"><?= e(t('Interactive map')) ?></a>
        <a href="<?= e(bd_url('districts')) ?>"><?= e(t('All 64 districts')) ?></a>
        <a href="<?= e(bd_url('geography')) ?>"><?= e(t('Geography & mountains')) ?></a>
        <a href="<?= e(bd_url('news')) ?>"><?= e(t('Trending news')) ?></a>
        <a href="<?= e(bd_url('travel')) ?>"><?= e(t('Travel places')) ?></a>
        <a href="<?= e(bd_url('transport')) ?>"><?= e(t('Roads & transport')) ?></a>
      </div>
      <div>
        <h4><?= e(t('Tools')) ?></h4>
        <a href="<?= e(bd_url('time')) ?>"><?= e(t('Clock & time converter')) ?></a>
        <a href="<?= e(bd_url('currency')) ?>"><?= e(t('Currency converter')) ?></a>
        <a href="<?= e(bd_url('gold')) ?>"><?= e(t('Gold price & converter')) ?></a>
        <a href="<?= e(bd_url('sunrise-sunset')) ?>"><?= e(t('Sunrise & sunset')) ?></a>
        <a href="<?= e(bd_url('search')) ?>"><?= e(t('Global search')) ?></a>
      </div>
      <div>
        <h4><?= e(t('Know Bangladesh')) ?></h4>
        <a href="<?= e(bd_url('government')) ?>"><?= e(t('Government & e-services')) ?></a>
        <a href="<?= e(bd_url('religion')) ?>"><?= e(t('Religions & festivals')) ?></a>
        <a href="<?= e(bd_url('calendar')) ?>"><?= e(t('Bangla & English Calendar')) ?></a>
        <a href="<?= e(bd_url('thana')) ?>"><?= e(t('All thanas')) ?></a>
        <a href="<?= e(bd_url('about')) ?>"><?= e(t('National profile')) ?></a>
        <a href="<?= e(bd_url('about-us')) ?>"><?= e(t('About Us')) ?></a>
      </div>
      <div>
        <h4><?= e(t('Emergency')) ?></h4>
        <a href="tel:999"><?= e(t('999 - National emergency')) ?></a>
        <a href="tel:333"><?= e(t('333 - Government info')) ?></a>
        <a href="tel:109"><?= e(t('109 - Women & children')) ?></a>
        <a href="tel:1098"><?= e(t('1098 - Child helpline')) ?></a>
        <a href="tel:16263"><?= e(t('16263 - Health line')) ?></a>
      </div>
    </div>
  </div>

  <div class="footer__bar">
    <p>© <?= date('Y') ?> <?= e(APP_NAME) ?> v<?= e(APP_VERSION) ?>. <?= e(t('All rights reserved by AskBangladesh.com.')) ?></p>
    <p style="margin-top:.4rem">
      <a href="<?= e(bd_url('about-us')) ?>"><?= e(t('About Us')) ?></a> ·
      <a href="<?= e(bd_url('privacy')) ?>"><?= e(t('Privacy Policy')) ?></a> ·
      <a href="<?= e(bd_url('terms')) ?>"><?= e(t('Terms & Conditions')) ?></a> ·
      <a href="<?= e(bd_url('contact')) ?>"><?= e(t('Contact Us')) ?></a>
    </p>
    <p class="footer__note"><?= e(t('Exchange rates and gold prices are indicative. Confirm with Bangladesh Bank or BAJUS before acting on them.')) ?></p>
  </div>
</footer>

<button type="button" class="totop" id="to-top" aria-label="<?= e(t('Back to top')) ?>">↑</button>

<script src="<?= e(bd_url('assets/js/app.js')) ?>?v=<?= e(APP_VERSION) ?>" defer></script>
</body>
</html>
