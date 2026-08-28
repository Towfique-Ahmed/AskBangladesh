<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Every Prime Minister (and caretaker Chief Adviser) of Bangladesh since 1971. */

$pms = bd_leaders('prime_ministers');

$seo = bd_seo(bd_page_seo('prime-ministers') + [
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => bd_url()],
        ['name' => 'Prime Ministers'],
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🏛️ Head of government</span>
  <h1>Prime Ministers of Bangladesh</h1>
  <p>
    Every Prime Minister of Bangladesh since 1971, including the Chief Advisers who have headed
    non-partisan caretaker and interim governments between elected terms. The Prime Minister (or
    Chief Adviser) is the chief executive, leading the council of ministers.
  </p>
</div>

<section class="section" style="margin-top:0">
  <div class="section__head">
    <h2>Full list, 1971 – present</h2>
    <p><?= count($pms) ?> entries, including caretaker and interim Chief Advisers.</p>
  </div>

  <div class="tablewrap" data-reveal>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Term</th>
          <th>Notes</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pms as $p): ?>
          <tr>
            <td><?= e((string) $p['no']) ?></td>
            <td><strong><?= e($p['name']) ?></strong></td>
            <td><?= e($p['term']) ?></td>
            <td><?= e($p['note']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<div class="notice" data-reveal>
  <span>ℹ️</span>
  <p style="margin:0">
    Between 1975 and 1984 the post of Prime Minister was intermittently vacant under presidential
    and martial-law rule. See the <a href="<?= e(bd_url('government')) ?>">government page</a> for
    the current officeholders and official portals.
  </p>
</div>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
