<?php
/**
 * 관리자 공통 레이아웃.
 * 각 화면은 $title, $subtitle, section('actions'), section('content') 를 채운다.
 */
$menu = [
    ['url' => 'admin/dashboard', 'key' => 'dashboard', 'label' => '대시보드'],
    ['url' => 'admin/article',   'key' => 'article',   'label' => '게시판'],
    ['url' => 'admin/gallery',   'key' => 'gallery',   'label' => '갤러리'],
    ['url' => 'admin/recruit',   'key' => 'recruit',   'label' => '채용공고'],
    ['url' => 'admin/inquiry',   'key' => 'inquiry',   'label' => '문의 내역'],
];
// 사이트 콘텐츠가 아니라 '이 화면을 쓰는 사람'을 다루므로 아래쪽에 따로 둔다
$menu_sub = [
    ['url' => 'admin/setting', 'key' => 'setting', 'label' => '회사 정보'],
    ['url' => 'admin/account', 'key' => 'account', 'label' => '관리자 계정'],
];
$icons = [
    'dashboard' => '<rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/>',
    'article'   => '<path d="M4 4h16v16H4z"/><path d="M8 9h8M8 13h8M8 17h5"/>',
    'gallery'   => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9.5" r="1.5"/><path d="M21 16l-5-5-6 6-3-3-4 4"/>',
    'recruit'   => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2M3 12h18"/>',
    'inquiry'   => '<path d="M4 5h16v12H8l-4 3z"/><path d="M9 9h7M9 12.5h4"/>',
    'account'   => '<circle cx="12" cy="8" r="3.5"/><path d="M4.5 20a7.5 7.5 0 0115 0"/>',
    'setting'   => '<path d="M4 21V10M4 6V3M12 21v-8M12 9V3M20 21v-5M20 12V3"/><path d="M1 10h6M9 9h6M17 16h6"/>',
];
$current = $current ?? '';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= esc($title) ?> · (주)케이원 관리자</title>
<link rel="icon" href="<?= base_url('images/favicon.png') ?>">
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/variable/pretendardvariable-dynamic-subset.min.css">
<link rel="stylesheet" href="<?= base_url('css/admin.css') ?>?v=20260903">
</head>
<body>

<div class="adm">

  <nav class="side" aria-label="관리자 메뉴">
    <div class="side__brand">
      <img src="<?= base_url('images/mark-k1.png') ?>" alt="">
      <span>
        <b>(주)케이원</b>
        <span>Admin</span>
      </span>
    </div>

    <div class="side__nav">
      <p class="side__label">홈페이지 관리</p>
      <?php foreach ($menu as $item): ?>
        <a href="<?= base_url($item['url']) ?>"<?= $current === $item['key'] ? ' aria-current="page"' : '' ?>>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $icons[$item['key']] ?></svg>
          <?= esc($item['label']) ?>
          <?php if ($item['key'] === 'inquiry' && ! empty($unread_count)): ?>
            <span class="side__badge"><?= esc($unread_count) ?></span>
          <?php endif ?>
        </a>
      <?php endforeach ?>

      <p class="side__label">설정</p>
      <?php foreach ($menu_sub as $item): ?>
        <a href="<?= base_url($item['url']) ?>"<?= $current === $item['key'] ? ' aria-current="page"' : '' ?>>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $icons[$item['key']] ?></svg>
          <?= esc($item['label']) ?>
        </a>
      <?php endforeach ?>
    </div>

    <div class="side__foot">
      <b><?= esc(session('admin_name')) ?> 님</b>
      <a href="<?= base_url('admin/password') ?>">비밀번호 변경</a> ·
      <a href="<?= base_url('admin/logout') ?>">로그아웃</a>
    </div>
  </nav>

  <div class="main">
    <header class="top">
      <div>
        <h1><?= esc($title) ?></h1>
        <?php if (! empty($subtitle)): ?><p><?= esc($subtitle) ?></p><?php endif ?>
      </div>
      <div class="top__act"><?= $this->renderSection('actions') ?></div>
    </header>

    <main class="body" id="main">
      <?php if (session()->getFlashdata('message')): ?>
        <div class="flash flash--ok" role="status">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8.5 12.5l2.5 2.5 4.5-5"/></svg>
          <?= esc(session()->getFlashdata('message')) ?>
        </div>
      <?php endif ?>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="flash flash--error" role="alert">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7.5v5M12 16h.01"/></svg>
          <?= esc(session()->getFlashdata('error')) ?>
        </div>
      <?php endif ?>

      <?= $this->renderSection('content') ?>
    </main>
  </div>

</div>

<script src="<?= base_url('js/admin.js') ?>?v=20260903"></script>
</body>
</html>
