<?php $errors = session('errors') ?? []; ?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<form class="form" method="post" action="<?= base_url('admin/password') ?>" style="max-width:480px">
  <?= csrf_field() ?>

  <div class="field">
    <label for="current_password">현재 비밀번호</label>
    <input class="inp" type="password" id="current_password" name="current_password"
           autocomplete="current-password" required>
    <?php if (isset($errors['current_password'])): ?>
      <p class="err" role="alert"><?= esc($errors['current_password']) ?></p>
    <?php endif ?>
  </div>

  <div class="field">
    <label for="new_password">새 비밀번호</label>
    <input class="inp" type="password" id="new_password" name="new_password"
           autocomplete="new-password" minlength="8" required>
    <p class="help">8자 이상으로, 다른 곳에서 쓰지 않는 비밀번호로 정해 주세요.</p>
    <?php if (isset($errors['new_password'])): ?>
      <p class="err" role="alert"><?= esc($errors['new_password']) ?></p>
    <?php endif ?>
  </div>

  <div class="field">
    <label for="confirm_password">새 비밀번호 확인</label>
    <input class="inp" type="password" id="confirm_password" name="confirm_password"
           autocomplete="new-password" minlength="8" required>
    <?php if (isset($errors['confirm_password'])): ?>
      <p class="err" role="alert"><?= esc($errors['confirm_password']) ?></p>
    <?php endif ?>
  </div>

  <div class="form__act">
    <button type="submit" class="btn btn--primary">비밀번호 변경</button>
    <a class="btn btn--ghost" href="<?= base_url('admin/dashboard') ?>">취소</a>
  </div>
</form>

<?= $this->endSection() ?>
