<?php
use App\Controllers\Admin\Recruit;

$is_edit = $recruit !== null;
$action  = $is_edit ? base_url('admin/recruit/update/' . $recruit['id']) : base_url('admin/recruit/store');
$errors  = session('errors') ?? [];
?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('actions') ?>
  <a class="btn btn--ghost" href="<?= base_url('admin/recruit') ?>">목록으로</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<form class="form" method="post" action="<?= $action ?>">
  <?= csrf_field() ?>

  <div class="field">
    <label for="title">공고 제목 <span class="req" aria-hidden="true">*</span></label>
    <input class="inp" type="text" id="title" name="title" maxlength="200" required
           <?= isset($errors['title']) ? 'aria-invalid="true" aria-describedby="title-err"' : '' ?>
           value="<?= esc(old('title', $is_edit ? $recruit['title'] : '')) ?>"
           placeholder="예) 전기 시운전 엔지니어 채용">
    <?php if (isset($errors['title'])): ?>
      <p class="err" id="title-err" role="alert"><?= esc($errors['title']) ?></p>
    <?php endif ?>
  </div>

  <div class="row">
    <div class="field">
      <label for="employment_type">고용 형태 <span class="req" aria-hidden="true">*</span></label>
      <?php $selected_type = old('employment_type', $is_edit ? $recruit['employment_type'] : ''); ?>
      <select class="sel" id="employment_type" name="employment_type" required>
        <option value="">선택해 주세요</option>
        <?php foreach (Recruit::TYPES as $type): ?>
          <option value="<?= esc($type, 'attr') ?>"<?= $selected_type === $type ? ' selected' : '' ?>><?= esc($type) ?></option>
        <?php endforeach ?>
      </select>
      <?php if (isset($errors['employment_type'])): ?>
        <p class="err" role="alert"><?= esc($errors['employment_type']) ?></p>
      <?php endif ?>
    </div>

    <div class="field">
      <label for="starts_at">모집 시작일</label>
      <input class="inp" type="date" id="starts_at" name="starts_at"
             value="<?= esc(old('starts_at', $is_edit ? $recruit['starts_at'] : '')) ?>">
    </div>

    <div class="field">
      <label for="ends_at">모집 마감일</label>
      <input class="inp" type="date" id="ends_at" name="ends_at"
             value="<?= esc(old('ends_at', $is_edit ? $recruit['ends_at'] : '')) ?>">
      <p class="help">비우면 상시 모집으로 표시됩니다.</p>
    </div>
  </div>

  <div class="field">
    <label for="content">공고 내용 <span class="req" aria-hidden="true">*</span></label>
    <textarea class="txt" id="content" name="content" required
              <?= isset($errors['content']) ? 'aria-invalid="true" aria-describedby="content-err"' : '' ?>
              placeholder="담당 업무, 자격 요건, 우대 사항, 근무지, 전형 절차 등을 적어 주세요."
    ><?= esc(old('content', $is_edit ? $recruit['content'] : '')) ?></textarea>
    <?php if (isset($errors['content'])): ?>
      <p class="err" id="content-err" role="alert"><?= esc($errors['content']) ?></p>
    <?php endif ?>
  </div>

  <div class="field">
    <label class="check">
      <input type="checkbox" name="is_open" value="1"
             <?= old('is_open', $is_edit ? $recruit['is_open'] : 1) ? 'checked' : '' ?>>
      홈페이지에 노출
    </label>
    <p class="help">체크를 풀면 공고가 홈페이지에서 사라집니다. 삭제하지 않고 잠시 내릴 때 쓰세요.</p>
  </div>

  <div class="form__act">
    <button type="submit" class="btn btn--primary"><?= $is_edit ? '수정 저장' : '등록' ?></button>
    <a class="btn btn--ghost" href="<?= base_url('admin/recruit') ?>">취소</a>
  </div>
</form>

<?php if ($is_edit): ?>
  <form class="form form--bare" method="post" style="margin-top:16px"
        action="<?= base_url('admin/recruit/delete/' . $recruit['id']) ?>"
        data-confirm="이 공고를 삭제할까요? 되돌릴 수 없습니다.">
    <?= csrf_field() ?>
    <button type="submit" class="btn btn--danger">이 공고 삭제</button>
  </form>
<?php endif ?>

<?= $this->endSection() ?>
