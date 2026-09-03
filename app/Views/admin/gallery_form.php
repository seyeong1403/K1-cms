<?php
$is_edit = $gallery !== null;
$action  = $is_edit ? base_url('admin/gallery/update/' . $gallery['id']) : base_url('admin/gallery/store');
$errors  = session('errors') ?? [];
?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('actions') ?>
  <a class="btn btn--ghost" href="<?= base_url('admin/gallery') ?>">목록으로</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<form class="form" method="post" action="<?= $action ?>" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <div class="field">
    <span class="field__label">사진 <?php if (! $is_edit): ?><span class="req" aria-hidden="true">*</span><?php endif ?></span>

    <?php if ($is_edit): ?>
      <div style="margin-bottom:12px">
        <img src="<?= base_url($gallery['image_path']) ?>" alt="<?= esc($gallery['title']) ?>"
             style="max-width:320px;border:1px solid var(--line);border-radius:6px">
      </div>
    <?php endif ?>

    <label class="drop" for="image" style="position:relative">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 16V4M7 9l5-5 5 5"/><path d="M4 16v3a2 2 0 002 2h12a2 2 0 002-2v-3"/></svg>
      <b data-file-name><?= $is_edit ? '다른 사진으로 바꾸기' : '사진 선택하기' ?></b>
      <span>JPG · PNG · WebP · 한 장에 5MB까지</span>
      <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp"
             data-preview="#preview" <?= $is_edit ? '' : 'required' ?>>
    </label>

    <div class="preview" id="preview">
      <img src="" alt="선택한 사진 미리보기">
    </div>

    <?php if ($is_edit): ?>
      <p class="help">새 사진을 고르지 않으면 지금 사진이 그대로 유지됩니다.</p>
    <?php endif ?>
  </div>

  <div class="field">
    <label for="title">사진 설명 <span class="req" aria-hidden="true">*</span></label>
    <input class="inp" type="text" id="title" name="title" maxlength="200" required
           <?= isset($errors['title']) ? 'aria-invalid="true" aria-describedby="title-err"' : '' ?>
           value="<?= esc(old('title', $is_edit ? $gallery['title'] : '')) ?>">
    <p class="help">사진이 안 보이는 환경에서 대신 읽히는 문구입니다. ‘현장 시운전’처럼 짧게 적어 주세요.</p>
    <?php if (isset($errors['title'])): ?>
      <p class="err" id="title-err" role="alert"><?= esc($errors['title']) ?></p>
    <?php endif ?>
  </div>

  <div class="field">
    <label for="sort_order">노출 순서</label>
    <input class="inp" type="number" id="sort_order" name="sort_order" min="0" step="10"
           style="max-width:160px"
           value="<?= esc(old('sort_order', $is_edit ? $gallery['sort_order'] : '')) ?>"
           placeholder="비우면 맨 뒤">
    <p class="help">숫자가 작을수록 앞에 나옵니다. 비워 두면 목록 맨 뒤에 붙습니다.</p>
  </div>

  <div class="form__act">
    <button type="submit" class="btn btn--primary"><?= $is_edit ? '수정 저장' : '올리기' ?></button>
    <a class="btn btn--ghost" href="<?= base_url('admin/gallery') ?>">취소</a>
  </div>
</form>

<?php if ($is_edit): ?>
  <form class="form form--bare" method="post" style="margin-top:16px"
        action="<?= base_url('admin/gallery/delete/' . $gallery['id']) ?>"
        data-confirm="이 사진을 삭제할까요? 되돌릴 수 없습니다.">
    <?= csrf_field() ?>
    <button type="submit" class="btn btn--danger">이 사진 삭제</button>
  </form>
<?php endif ?>

<?= $this->endSection() ?>
