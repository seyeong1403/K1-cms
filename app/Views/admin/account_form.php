<?php
$is_edit = $account !== null;
$action  = $is_edit ? base_url('admin/account/update/' . $account['id']) : base_url('admin/account/store');
$errors  = session('errors') ?? [];
?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('actions') ?>
  <a class="btn btn--ghost" href="<?= base_url('admin/account') ?>">목록으로</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<form class="form" method="post" action="<?= $action ?>" style="max-width:520px">
  <?= csrf_field() ?>

  <div class="field">
    <label for="username">아이디 <?php if (! $is_edit): ?><span class="req" aria-hidden="true">*</span><?php endif ?></label>
    <?php if ($is_edit): ?>
      <input class="inp" type="text" id="username" value="<?= esc($account['username']) ?>" disabled>
      <p class="help">아이디는 바꿀 수 없습니다. 다른 아이디가 필요하면 계정을 새로 만들어 주세요.</p>
    <?php else: ?>
      <input class="inp" type="text" id="username" name="username" minlength="4" maxlength="50" required
             autocomplete="username"
             <?= isset($errors['username']) ? 'aria-invalid="true" aria-describedby="username-err"' : '' ?>
             value="<?= esc(old('username')) ?>">
      <p class="help">영문·숫자와 <code>-</code> <code>_</code> 만 쓸 수 있습니다. 4자 이상.</p>
      <?php if (isset($errors['username'])): ?>
        <p class="err" id="username-err" role="alert"><?= esc($errors['username']) ?></p>
      <?php endif ?>
    <?php endif ?>
  </div>

  <div class="field">
    <label for="name">이름 <span class="req" aria-hidden="true">*</span></label>
    <input class="inp" type="text" id="name" name="name" maxlength="50" required
           <?= isset($errors['name']) ? 'aria-invalid="true" aria-describedby="name-err"' : '' ?>
           value="<?= esc(old('name', $is_edit ? $account['name'] : '')) ?>">
    <p class="help">화면 왼쪽 아래에 표시되는 이름입니다.</p>
    <?php if (isset($errors['name'])): ?>
      <p class="err" id="name-err" role="alert"><?= esc($errors['name']) ?></p>
    <?php endif ?>
  </div>

  <div class="field">
    <label for="password">비밀번호 <?php if (! $is_edit): ?><span class="req" aria-hidden="true">*</span><?php endif ?></label>
    <input class="inp" type="password" id="password" name="password" minlength="8"
           autocomplete="new-password" <?= $is_edit ? '' : 'required' ?>
           <?= isset($errors['password']) ? 'aria-invalid="true" aria-describedby="password-err"' : '' ?>>
    <p class="help">
      8자 이상.
      <?= $is_edit ? '비워 두면 지금 비밀번호가 그대로 유지됩니다.' : '만든 뒤에는 담당자에게 안전한 방법으로 전달해 주세요.' ?>
    </p>
    <?php if (isset($errors['password'])): ?>
      <p class="err" id="password-err" role="alert"><?= esc($errors['password']) ?></p>
    <?php endif ?>
  </div>

  <div class="form__act">
    <button type="submit" class="btn btn--primary"><?= $is_edit ? '수정 저장' : '계정 만들기' ?></button>
    <a class="btn btn--ghost" href="<?= base_url('admin/account') ?>">취소</a>
  </div>
</form>

<?= $this->endSection() ?>
