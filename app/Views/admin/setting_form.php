<?php
$errors = session('errors') ?? [];

// 묶음별로 나눠서 보여준다
$groups = [];
foreach ($fields as $key => $meta) {
    $groups[$meta['group']][$key] = $meta;
}
?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<form class="form" method="post" action="<?= base_url('admin/setting/update') ?>" style="max-width:620px">
  <?= csrf_field() ?>

  <?php foreach ($groups as $group_name => $group_fields): ?>
    <fieldset style="border:0;padding:0;margin:0 0 var(--s6)">
      <legend class="side__label" style="padding:0 0 var(--s3);color:var(--ink-mut)"><?= esc($group_name) ?></legend>

      <?php foreach ($group_fields as $key => $meta): ?>
        <div class="field">
          <label for="<?= esc($key, 'attr') ?>"><?= esc($meta['label']) ?></label>
          <input class="inp" type="<?= esc($meta['type'], 'attr') ?>"
                 id="<?= esc($key, 'attr') ?>" name="<?= esc($key, 'attr') ?>"
                 <?= isset($errors[$key]) ? 'aria-invalid="true"' : '' ?>
                 value="<?= esc(old($key, $values[$key] ?? '')) ?>">
          <?php if (isset($meta['help'])): ?>
            <p class="help"><?= esc($meta['help']) ?></p>
          <?php endif ?>
          <?php if (isset($errors[$key])): ?>
            <p class="err" role="alert"><?= esc($errors[$key]) ?></p>
          <?php endif ?>
        </div>
      <?php endforeach ?>
    </fieldset>
  <?php endforeach ?>

  <div class="form__act">
    <button type="submit" class="btn btn--primary">저장</button>
    <a class="btn btn--ghost" href="<?= base_url('admin/dashboard') ?>">취소</a>
  </div>
</form>

<p style="margin-top:14px;color:var(--ink-mut);font-size:13.5px;max-width:620px">
  비워 두면 홈페이지에서 그 항목이 표시되지 않습니다.
  울산 지사 번호처럼 아직 정해지지 않은 값은 비워 두셔도 됩니다.
</p>

<?= $this->endSection() ?>
