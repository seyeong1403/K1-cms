<?php $pager->setSurroundCount(2) ?>

<?php if ($pager->hasPrevious()): ?>
  <a href="<?= $pager->getPrevious() ?>" aria-label="이전 페이지">‹</a>
<?php endif ?>

<?php foreach ($pager->links() as $link): ?>
  <?php if ($link['active']): ?>
    <strong aria-current="page"><?= esc($link['title']) ?></strong>
  <?php else: ?>
    <a href="<?= $link['uri'] ?>"><?= esc($link['title']) ?></a>
  <?php endif ?>
<?php endforeach ?>

<?php if ($pager->hasNext()): ?>
  <a href="<?= $pager->getNext() ?>" aria-label="다음 페이지">›</a>
<?php endif ?>
