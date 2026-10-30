<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Every Prime Minister (and caretaker Chief Adviser) of Bangladesh since 1971. */

$pms     = bd_leaders('prime_ministers');
$leader  = bd_current_leader('prime_ministers');

$seo = bd_seo(bd_page_seo('prime-ministers') + [
    'type'        => 'article',
    'breadcrumbs' => [
        ['name' => t('Home'), 'url' => bd_url()],
        ['name' => t('Prime Ministers')],
    ],
    'jsonld' => [
        bd_jsonld_person_list('Prime Ministers of Bangladesh', $pms),
    ],
]);

require APP_ROOT . '/includes/layout/header.php';
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🏛️ <?= e(t('Head of government')) ?></span>
  <h1><?= e(t('Prime Ministers of Bangladesh')) ?></h1>
  <p>
    <?= t('Every Prime Minister of Bangladesh since 1971, including the Chief Advisers who have headed non-partisan caretaker and interim governments between elected terms. The Prime Minister (or Chief Adviser) is the chief executive, leading the council of ministers.') ?>
  </p>
</div>

<?php if ($leader): ?>
<section class="section" style="margin-top:0">
  <div class="section__head">
    <h2><?= e(t('Current Prime Minister')) ?></h2>
  </div>
  <article class="card" data-reveal>
    <span class="badge">In office</span>
    <h3 style="margin-top:.5rem">🧑‍💼 <?= e($leader['name']) ?></h3>
    <div class="mapinfo__rows">
      <div class="mapinfo__row"><span>Term</span><span><?= e($leader['term']) ?></span></div>
      <div class="mapinfo__row"><span>Seat</span><span>Prime Minister’s Office, Tejgaon, Dhaka</span></div>
    </div>
    <?php if ($leader['note'] !== ''): ?>
      <p style="margin-top:.7rem"><?= e($leader['note']) ?></p>
    <?php endif; ?>
  </article>
</section>
<?php endif; ?>

<section class="section">
  <div class="section__head">
    <h2><?= e(t('Full list, 1971 – present')) ?></h2>
    <p><?= count($pms) ?> entries, including caretaker and interim Chief Advisers.</p>
  </div>

  <div class="tablewrap" data-reveal>
    <table>
      <thead>
        <tr>
          <th><?= e(t('Name')) ?></th>
          <th><?= e(t('Role')) ?></th>
          <th><?= e(t('Term')) ?></th>
          <th><?= e(t('Notes')) ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pms as $p): ?>
          <tr>
            <td><strong><?= e($p['name']) ?></strong></td>
            <td><span class="badge"><?= e($p['role']) ?></span></td>
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
    and martial-law rule. Chief Advisers led non-partisan caretaker and interim governments between
    elected terms. See the <a href="<?= e(bd_url('government')) ?>">government page</a> for official
    portals, and the <a href="<?= e(bd_url('presidents')) ?>">presidents list</a> for heads of state.
  </p>
</div>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
