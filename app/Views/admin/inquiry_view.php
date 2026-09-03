<?php
$mail_subject = rawurlencode('[(주)케이원] 문의해 주셔서 감사합니다');
?>
<?= $this->extend('admin/layout') ?>

<?= $this->section('actions') ?>
  <a class="btn btn--primary" href="mailto:<?= esc($inquiry['email'], 'attr') ?>?subject=<?= $mail_subject ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
    메일로 답장
  </a>
  <a class="btn btn--ghost" href="<?= base_url('admin/inquiry') ?>">목록으로</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="panel" style="max-width:840px">
  <div class="panel__head">
    <h2><?= esc($inquiry['purpose']) ?></h2>
    <span class="cnt"><?= esc(date('Y-m-d H:i', strtotime($inquiry['created_at']))) ?> 접수</span>
  </div>

  <dl class="dl">
    <div>
      <dt>이름</dt>
      <dd><?= esc($inquiry['name']) ?></dd>
    </div>
    <?php if ($inquiry['company']): ?>
      <div>
        <dt>회사 / 기관</dt>
        <dd><?= esc($inquiry['company']) ?></dd>
      </div>
    <?php endif ?>
    <div>
      <dt>이메일</dt>
      <dd><a href="mailto:<?= esc($inquiry['email'], 'attr') ?>"><?= esc($inquiry['email']) ?></a></dd>
    </div>
    <?php if ($inquiry['phone']): ?>
      <div>
        <dt>연락처</dt>
        <dd><a href="tel:<?= esc(preg_replace('/[^0-9+]/', '', $inquiry['phone']), 'attr') ?>"><?= esc($inquiry['phone']) ?></a></dd>
      </div>
    <?php endif ?>
    <div>
      <dt>문의 내용</dt>
      <dd class="msg"><?= esc($inquiry['message']) ?></dd>
    </div>
  </dl>
</section>

<form class="form form--bare" method="post" style="margin-top:16px"
      action="<?= base_url('admin/inquiry/delete/' . $inquiry['id']) ?>"
      data-confirm="이 문의를 삭제할까요? 되돌릴 수 없습니다.">
  <?= csrf_field() ?>
  <button type="submit" class="btn btn--danger">이 문의 삭제</button>
</form>

<?= $this->endSection() ?>
