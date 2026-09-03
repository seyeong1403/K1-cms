<?= $this->extend('admin/layout') ?>

<?= $this->section('actions') ?>
  <a class="btn btn--primary" href="<?= base_url('admin/account/create') ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
    계정 추가
  </a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="panel">
  <div class="panel__head">
    <h2>관리자 계정</h2>
    <span class="cnt">전체 <?= esc(count($account_list)) ?>개</span>
  </div>

  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th class="num">번호</th>
          <th style="width:180px">아이디</th>
          <th>이름</th>
          <th class="date">마지막 접속</th>
          <th class="act">관리</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($account_list as $row): ?>
        <?php $is_me = (int) $row['id'] === (int) session('admin_id'); ?>
        <tr>
          <td class="num"><?= esc($row['id']) ?></td>
          <td>
            <a class="ttl" href="<?= base_url('admin/account/edit/' . $row['id']) ?>"><?= esc($row['username']) ?></a>
            <?php if ($is_me): ?> <span class="badge badge--open">나</span><?php endif ?>
          </td>
          <td><?= esc($row['name']) ?></td>
          <td class="date">
            <?= $row['last_login_at'] ? esc(date('Y-m-d', strtotime($row['last_login_at']))) : '—' ?>
          </td>
          <td class="act">
            <a class="btn btn--ghost btn--sm" href="<?= base_url('admin/account/edit/' . $row['id']) ?>">수정</a>
            <?php if (! $is_me && count($account_list) > 1): ?>
              <form method="post" action="<?= base_url('admin/account/delete/' . $row['id']) ?>"
                    data-confirm="‘<?= esc($row['username'], 'attr') ?>’ 계정을 삭제할까요? 이 계정으로는 더 이상 들어올 수 없습니다.">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn--danger btn--sm">삭제</button>
              </form>
            <?php endif ?>
          </td>
        </tr>
      <?php endforeach ?>
      </tbody>
    </table>
  </div>
</section>

<p class="help" style="margin-top:14px;color:var(--ink-mut);font-size:13.5px">
  담당자가 바뀌면 새 계정을 만들고 이전 계정을 지워 주세요.
  비밀번호는 저장 후 아무도 볼 수 없으므로, 잊어버린 경우에는 이 화면에서 새로 정해 주시면 됩니다.
</p>

<?= $this->endSection() ?>
