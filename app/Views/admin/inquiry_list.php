<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<section class="panel">
  <div class="panel__head">
    <h2>접수된 문의</h2>
    <span class="cnt">전체 <?= esc($total_count) ?>건<?= $unread_count ? ' · 안 읽음 ' . esc($unread_count) . '건' : '' ?></span>
  </div>

  <?php if ($inquiry_list === []): ?>
    <div class="empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16v12H8l-4 3z"/><path d="M9 9h7M9 12.5h4"/></svg>
      <b>아직 들어온 문의가 없습니다</b>
      <p>홈페이지 고객문의 양식으로 접수되면 이곳에 쌓입니다.</p>
    </div>
  <?php else: ?>
    <div class="tbl-wrap">
      <table class="tbl">
        <thead>
          <tr>
            <th class="num">번호</th>
            <th style="width:120px">문의 목적</th>
            <th>보낸 사람</th>
            <th style="width:190px">연락처</th>
            <th class="date">접수일</th>
            <th class="act">관리</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($inquiry_list as $row): ?>
          <tr>
            <td class="num"><?= esc($row['id']) ?></td>
            <td><?= esc($row['purpose']) ?></td>
            <td>
              <?php if (! $row['is_read']): ?><span class="badge badge--new">안 읽음</span> <?php endif ?>
              <a class="ttl" href="<?= base_url('admin/inquiry/view/' . $row['id']) ?>"><?= esc($row['name']) ?></a>
              <?php if ($row['company']): ?>
                <span style="color:var(--ink-mut)"> · <?= esc($row['company']) ?></span>
              <?php endif ?>
            </td>
            <td style="color:var(--ink-mut)"><?= esc($row['phone'] ?: $row['email']) ?></td>
            <td class="date"><?= esc(date('Y-m-d', strtotime($row['created_at']))) ?></td>
            <td class="act">
              <a class="btn btn--ghost btn--sm" href="<?= base_url('admin/inquiry/view/' . $row['id']) ?>">보기</a>
              <form method="post" action="<?= base_url('admin/inquiry/delete/' . $row['id']) ?>"
                    data-confirm="<?= esc($row['name'], 'attr') ?> 님의 문의를 삭제할까요?">
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
