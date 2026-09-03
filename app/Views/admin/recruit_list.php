<?= $this->extend('admin/layout') ?>

<?= $this->section('actions') ?>
  <a class="btn btn--primary" href="<?= base_url('admin/recruit/create') ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
    공고 등록
  </a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="panel">
  <div class="panel__head">
    <h2>채용공고</h2>
    <span class="cnt">전체 <?= esc($total_count) ?>건</span>
  </div>

  <?php if ($recruit_list === []): ?>
    <div class="empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2M3 12h18"/></svg>
      <b>아직 등록된 공고가 없습니다</b>
      <p>공고가 없을 때 홈페이지에는 상시 지원 안내가 대신 표시됩니다.</p>
      <a class="btn btn--primary btn--sm" href="<?= base_url('admin/recruit/create') ?>">첫 공고 등록</a>
    </div>
  <?php else: ?>
    <div class="tbl-wrap">
      <table class="tbl">
        <thead>
          <tr>
            <th class="num">번호</th>
            <th>공고 제목</th>
            <th style="width:100px">고용 형태</th>
            <th style="width:190px">모집 기간</th>
            <th class="act">관리</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($recruit_list as $row): ?>
          <?php $expired = $recruit_model->isExpired($row); ?>
          <tr>
            <td class="num"><?= esc($row['id']) ?></td>
            <td>
              <?php if (! $row['is_open']): ?>
                <span class="badge badge--closed">숨김</span>
              <?php elseif ($expired): ?>
                <span class="badge badge--closed">마감</span>
              <?php else: ?>
                <span class="badge badge--open">모집 중</span>
              <?php endif ?>
              <a class="ttl" href="<?= base_url('admin/recruit/edit/' . $row['id']) ?>"><?= esc($row['title']) ?></a>
            </td>
            <td><?= esc($row['employment_type']) ?></td>
            <td class="date">
              <?php if ($row['starts_at'] || $row['ends_at']): ?>
                <?= esc($row['starts_at'] ?: '') ?> ~ <?= esc($row['ends_at'] ?: '상시') ?>
              <?php else: ?>
                상시 모집
              <?php endif ?>
            </td>
            <td class="act">
              <a class="btn btn--ghost btn--sm" href="<?= base_url('admin/recruit/edit/' . $row['id']) ?>">수정</a>
              <form method="post" action="<?= base_url('admin/recruit/delete/' . $row['id']) ?>"
                    data-confirm="‘<?= esc($row['title'], 'attr') ?>’ 공고를 삭제할까요?">
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
