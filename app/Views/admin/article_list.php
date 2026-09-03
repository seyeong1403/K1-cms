<?= $this->extend('admin/layout') ?>

<?= $this->section('actions') ?>
  <a class="btn btn--primary" href="<?= base_url('admin/article/create') ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
    글쓰기
  </a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="panel">
  <div class="panel__head">
    <h2>게시글</h2>
    <span class="cnt">전체 <?= esc($total_count) ?>건</span>
    <form method="get" action="<?= base_url('admin/article') ?>" data-no-lock>
      <label class="field__label" for="q" hidden>검색어</label>
      <input class="inp" type="search" id="q" name="q" value="<?= esc($keyword) ?>"
             placeholder="제목·내용 검색" style="min-width:220px">
      <button type="submit" class="btn btn--ghost">검색</button>
      <?php if ($keyword !== ''): ?>
        <a class="btn btn--ghost" href="<?= base_url('admin/article') ?>">전체</a>
      <?php endif ?>
    </form>
  </div>

  <?php if ($article_list === []): ?>
    <div class="empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16v16H4z"/><path d="M8 9h8M8 13h8M8 17h5"/></svg>
      <?php if ($keyword !== ''): ?>
        <b>‘<?= esc($keyword) ?>’ 검색 결과가 없습니다</b>
        <p>다른 낱말로 찾아보세요.</p>
        <a class="btn btn--ghost btn--sm" href="<?= base_url('admin/article') ?>">전체 목록 보기</a>
      <?php else: ?>
        <b>아직 등록된 게시글이 없습니다</b>
        <p>공지사항이나 소식을 등록하면 홈페이지 게시판에 바로 올라갑니다.</p>
        <a class="btn btn--primary btn--sm" href="<?= base_url('admin/article/create') ?>">첫 글 쓰기</a>
      <?php endif ?>
    </div>
  <?php else: ?>
    <div class="tbl-wrap">
      <table class="tbl">
        <thead>
          <tr>
            <th class="num">번호</th>
            <th>제목</th>
            <th class="date">등록일</th>
            <th class="act">관리</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($article_list as $row): ?>
          <tr>
            <td class="num"><?= esc($row['id']) ?></td>
            <td>
              <?php if ($row['is_notice']): ?><span class="badge badge--notice">공지</span> <?php endif ?>
              <a class="ttl" href="<?= base_url('admin/article/edit/' . $row['id']) ?>"><?= esc($row['title']) ?></a>
            </td>
            <td class="date"><?= esc(date('Y-m-d', strtotime($row['created_at']))) ?></td>
            <td class="act">
              <a class="btn btn--ghost btn--sm" href="<?= base_url('admin/article/edit/' . $row['id']) ?>">수정</a>
              <form method="post" action="<?= base_url('admin/article/delete/' . $row['id']) ?>"
                    data-confirm="‘<?= esc($row['title'], 'attr') ?>’ 글을 삭제할까요?">
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
