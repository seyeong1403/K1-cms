<?= $this->extend('admin/layout') ?>

<?= $this->section('actions') ?>
  <a class="btn btn--ghost" href="<?= base_url() ?>" target="_blank" rel="noopener">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><path d="M15 3h6v6M10 14L21 3"/></svg>
    홈페이지 보기
  </a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="stats">
  <a class="stat" href="<?= base_url('admin/article') ?>">
    <span class="stat__k">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16v16H4z"/><path d="M8 9h8M8 13h8M8 17h5"/></svg>
      게시글
    </span>
    <span class="stat__v"><?= esc($stats['article']) ?><em>건</em></span>
  </a>
  <a class="stat" href="<?= base_url('admin/gallery') ?>">
    <span class="stat__k">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9.5" r="1.5"/><path d="M21 16l-5-5-6 6-3-3-4 4"/></svg>
      갤러리 사진
    </span>
    <span class="stat__v"><?= esc($stats['gallery']) ?><em>장</em></span>
  </a>
  <a class="stat" href="<?= base_url('admin/recruit') ?>">
    <span class="stat__k">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2M3 12h18"/></svg>
      모집 중 공고
    </span>
    <span class="stat__v"><?= esc($stats['recruit']) ?><em>건</em></span>
  </a>
  <a class="stat" href="<?= base_url('admin/inquiry') ?>">
    <span class="stat__k">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16v12H8l-4 3z"/><path d="M9 9h7M9 12.5h4"/></svg>
      안 읽은 문의
    </span>
    <span class="stat__v"><?= esc($stats['unread']) ?><em>건</em></span>
  </a>
</div>

<div class="cols">

  <section class="panel">
    <div class="panel__head">
      <h2>최근 게시글</h2>
      <form><a class="btn btn--ghost btn--sm" href="<?= base_url('admin/article') ?>">전체 보기</a></form>
    </div>
    <?php if ($recent_articles === []): ?>
      <div class="empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16v16H4z"/><path d="M8 9h8M8 13h8M8 17h5"/></svg>
        <b>아직 등록된 게시글이 없습니다</b>
        <p>공지사항이나 소식을 등록해 보세요.</p>
        <a class="btn btn--primary btn--sm" href="<?= base_url('admin/article/create') ?>">글쓰기</a>
      </div>
    <?php else: ?>
      <div class="tbl-wrap">
        <table class="tbl">
          <thead><tr><th>제목</th><th class="date">등록일</th></tr></thead>
          <tbody>
          <?php foreach ($recent_articles as $row): ?>
            <tr>
              <td>
                <?php if ($row['is_notice']): ?><span class="badge badge--notice">공지</span> <?php endif ?>
                <a class="ttl" href="<?= base_url('admin/article/edit/' . $row['id']) ?>"><?= esc($row['title']) ?></a>
              </td>
              <td class="date"><?= esc(date('Y-m-d', strtotime($row['created_at']))) ?></td>
            </tr>
          <?php endforeach ?>
          </tbody>
        </table>
      </div>
    <?php endif ?>
  </section>

  <section class="panel">
    <div class="panel__head">
      <h2>최근 문의</h2>
      <form><a class="btn btn--ghost btn--sm" href="<?= base_url('admin/inquiry') ?>">전체 보기</a></form>
    </div>
    <?php if ($recent_inquiries === []): ?>
      <div class="empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16v12H8l-4 3z"/><path d="M9 9h7M9 12.5h4"/></svg>
        <b>아직 들어온 문의가 없습니다</b>
        <p>홈페이지 고객문의로 접수된 내용이 여기에 쌓입니다.</p>
      </div>
    <?php else: ?>
      <div class="tbl-wrap">
        <table class="tbl">
          <thead><tr><th>보낸 사람</th><th>문의 목적</th><th class="date">접수일</th></tr></thead>
          <tbody>
          <?php foreach ($recent_inquiries as $row): ?>
            <tr>
              <td>
                <?php if (! $row['is_read']): ?><span class="badge badge--new">새 글</span> <?php endif ?>
                <a class="ttl" href="<?= base_url('admin/inquiry/view/' . $row['id']) ?>"><?= esc($row['name']) ?></a>
              </td>
              <td><?= esc($row['purpose']) ?></td>
              <td class="date"><?= esc(date('Y-m-d', strtotime($row['created_at']))) ?></td>
            </tr>
          <?php endforeach ?>
          </tbody>
        </table>
      </div>
    <?php endif ?>
  </section>

</div>

<?= $this->endSection() ?>
