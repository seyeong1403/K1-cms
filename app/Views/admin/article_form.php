<?php
$is_edit = $article !== null;
$action  = $is_edit ? base_url('admin/article/update/' . $article['id']) : base_url('admin/article/store');
$errors  = session('errors') ?? [];
?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('actions') ?>
  <a class="btn btn--ghost" href="<?= base_url('admin/article') ?>">목록으로</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<form class="form" method="post" action="<?= $action ?>">
  <?= csrf_field() ?>

  <div class="field">
    <label for="title">제목 <span class="req" aria-hidden="true">*</span></label>
    <input class="inp" type="text" id="title" name="title" maxlength="200" required
           <?= isset($errors['title']) ? 'aria-invalid="true" aria-describedby="title-err"' : '' ?>
           value="<?= esc(old('title', $is_edit ? $article['title'] : '')) ?>">
    <?php if (isset($errors['title'])): ?>
      <p class="err" id="title-err" role="alert"><?= esc($errors['title']) ?></p>
    <?php endif ?>
  </div>

  <div class="field">
    <label for="content">내용 <span class="req" aria-hidden="true">*</span></label>
    <textarea class="txt" id="content" name="content" required
              <?= isset($errors['content']) ? 'aria-invalid="true" aria-describedby="content-err"' : '' ?>
    ><?= esc(old('content', $is_edit ? $article['content'] : '')) ?></textarea>
    <p class="help">줄을 바꾼 그대로 홈페이지에 표시됩니다.</p>
    <?php if (isset($errors['content'])): ?>
      <p class="err" id="content-err" role="alert"><?= esc($errors['content']) ?></p>
    <?php endif ?>
  </div>

  <div class="field">
    <label class="check">
      <input type="checkbox" name="is_notice" value="1"
             <?= old('is_notice', $is_edit ? $article['is_notice'] : 0) ? 'checked' : '' ?>>
      목록 맨 위에 고정 (공지)
    </label>
    <p class="help">중요한 공지사항을 항상 첫 줄에 보이게 합니다.</p>
  </div>

  <div class="form__act">
    <button type="submit" class="btn btn--primary"><?= $is_edit ? '수정 저장' : '등록' ?></button>
    <a class="btn btn--ghost" href="<?= base_url('admin/article') ?>">취소</a>

    <?php if ($is_edit): ?>
      <span class="right"></span>
    <?php endif ?>
  </div>
</form>

<?php if ($is_edit): ?>
  <form class="form form--bare" method="post" style="margin-top:16px"
        action="<?= base_url('admin/article/delete/' . $article['id']) ?>"
        data-confirm="이 글을 삭제할까요? 되돌릴 수 없습니다.">
    <?= csrf_field() ?>
    <button type="submit" class="btn btn--danger">이 글 삭제</button>
  </form>
<?php endif ?>

<?= $this->endSection() ?>
