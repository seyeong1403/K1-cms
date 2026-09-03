<?= $this->extend('admin/layout') ?>

<?= $this->section('actions') ?>
  <a class="btn btn--primary" href="<?= base_url('admin/gallery/create') ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
    사진 올리기
  </a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="panel">
  <div class="panel__head">
    <h2>갤러리 사진</h2>
    <span class="cnt">전체 <?= esc($total_count) ?>장</span>
  </div>

  <?php if ($gallery_list === []): ?>
    <div class="empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9.5" r="1.5"/><path d="M21 16l-5-5-6 6-3-3-4 4"/></svg>
      <b>아직 올린 사진이 없습니다</b>
      <p>현장·설비·프로젝트 사진을 올리면 홈페이지 갤러리에 표시됩니다.</p>
      <a class="btn btn--primary btn--sm" href="<?= base_url('admin/gallery/create') ?>">첫 사진 올리기</a>
    </div>
  <?php else: ?>
    <div class="tbl-wrap">
      <table class="tbl">
        <thead>
          <tr>
            <th class="num">순서</th>
            <th class="thumb">사진</th>
            <th>설명</th>
            <th class="date">올린 날짜</th>
            <th class="act">관리</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($gallery_list as $row): ?>
          <tr>
            <td class="num"><?= esc($row['sort_order']) ?></td>
            <td class="thumb">
              <img src="<?= base_url($row['thumb_path'] ?: $row['image_path']) ?>" alt="<?= esc($row['title']) ?>" loading="lazy">
            </td>
            <td>
              <a class="ttl" href="<?= base_url('admin/gallery/edit/' . $row['id']) ?>"><?= esc($row['title']) ?></a>
            </td>
            <td class="date"><?= esc(date('Y-m-d', strtotime($row['created_at']))) ?></td>
            <td class="act">
              <a class="btn btn--ghost btn--sm" href="<?= base_url('admin/gallery/edit/' . $row['id']) ?>">수정</a>
              <form method="post" action="<?= base_url('admin/gallery/delete/' . $row['id']) ?>"
                    data-confirm="‘<?= esc($row['title'], 'attr') ?>’ 사진을 삭제할까요?">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn--danger btn--sm">삭제</button>
              </form>
            </td>
          </tr>
        <?php endforeach ?>
        </tbody>
      </table>
    </div>

    <?php if ($pager->getPageCount() > 1): ?>
      <nav class="pager" aria-label="페이지 이동"><?= $pager->links('default', 'admin_pager') ?></nav>
    <?php endif ?>
  <?php endif ?>
</section>

<?= $this->endSection() ?>
