<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>로그인 · (주)케이원 관리자</title>
<link rel="icon" href="<?= base_url('images/favicon.png') ?>">
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/variable/pretendardvariable-dynamic-subset.min.css">
<link rel="stylesheet" href="<?= base_url('css/admin.css') ?>?v=20260903">
</head>
<body>

<div class="login">
  <div class="login__box">

    <div class="login__brand">
      <img src="<?= base_url('images/mark-k1.png') ?>" alt="(주)케이원">
      <b>(주)케이원 홈페이지 관리</b>
      <span>K1 Co., Ltd.</span>
    </div>

    <div class="login__card">
      <?php if (session()->getFlashdata('error')): ?>
        <div class="flash flash--error" role="alert">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7.5v5M12 16h.01"/></svg>
          <?= esc(session()->getFlashdata('error')) ?>
        </div>
      <?php endif ?>

      <form method="post" action="<?= base_url('admin/login') ?>">
        <?= csrf_field() ?>

        <div class="field">
          <label for="username">아이디</label>
          <input class="inp" type="text" id="username" name="username"
                 value="<?= esc(old('username')) ?>" autocomplete="username" autofocus required>
        </div>

        <div class="field">
          <label for="password">비밀번호</label>
          <input class="inp" type="password" id="password" name="password"
                 autocomplete="current-password" required>
        </div>

        <button type="submit" class="btn btn--primary">로그인</button>
      </form>
    </div>

    <p class="login__foot">관리자 전용 화면입니다.</p>

  </div>
</div>

</body>
</html>
