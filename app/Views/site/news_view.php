<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($article['title']) ?> · (주)케이원</title>
<meta name="description" content="<?= esc(mb_substr(strip_tags($article['content']), 0, 120), 'attr') ?>\">
<link rel="canonical" href="https://k1tnc.co.kr/news.html">
<link rel="alternate" hreflang="ko" href="https://k1tnc.co.kr/news.html">
<link rel="alternate" hreflang="en" href="https://k1tnc.co.kr/en/news.html">
<link rel="alternate" hreflang="x-default" href="https://k1tnc.co.kr/news.html">
<meta property="og:type" content="website">
<meta property="og:site_name" content="(주)케이원 · K1 OCEAN">
<meta property="og:locale" content="ko_KR">
<meta property="og:title" content="홍보센터 · (주)케이원 K1 OCEAN">
<meta property="og:description" content="(주)케이원 홍보센터 — 게시판, 갤러리, CI(심볼 마크·마스코트).">
<meta property="og:url" content="https://k1tnc.co.kr/news.html">
<meta property="og:image" content="https://k1tnc.co.kr/assets/og-image.jpg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="홍보센터 · (주)케이원 K1 OCEAN">
<meta name="twitter:description" content="(주)케이원 홍보센터 — 게시판, 갤러리, CI(심볼 마크·마스코트).">
<meta name="twitter:image" content="https://k1tnc.co.kr/assets/og-image.jpg">
<link rel="icon" type="image/png" href="<?= base_url('assets/favicon.png') ?>">
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/variable/pretendardvariable-dynamic-subset.min.css">
<link rel="stylesheet" href="<?= base_url('css/v2.css?v=20260902') ?>">
</head>
<body class="sub">
<a class="skip" href="#main">본문 바로가기</a>

<!-- HEADER -->
<header class="head" id="head">
  <div class="head__in">
    <a class="brand" href="<?= base_url('index.html') ?>" aria-label="(주)케이원 홈">
      <img class="brand--dark" src="<?= base_url('assets/logo-k1-dark.png') ?>" alt="(주)케이원 · Korea No.1">
      <img class="brand--light" src="<?= base_url('assets/logo-k1.png') ?>" alt="(주)케이원 · Korea No.1">
    </a>
    <div class="gnbwrap">
      <nav aria-label="주 메뉴"><ul class="gnb">
        <li><a href="<?= base_url('about.html') ?>">회사소개</a></li>
        <li><a href="<?= base_url('business.html') ?>">사업분야</a></li>
        <li><a href="<?= base_url('projects.html') ?>">프로젝트 실적</a></li>
        <li><a href="<?= base_url('recruit.html') ?>">인재채용</a></li>
        <li><a href="<?= base_url('news.html') ?>">홍보센터</a></li>
        <li><a href="<?= base_url('contact.html') ?>">고객문의</a></li>
      </ul></nav>
      <div class="mega"><div class="mega__in">
        <div class="mega__col"><h5>회사소개</h5><a href="<?= base_url('about.html#ceo') ?>">CEO 인사말</a><a href="<?= base_url('about.html#overview') ?>">회사개요</a><a href="<?= base_url('about.html#org') ?>">조직도</a></div>
        <div class="mega__col"><h5>사업분야</h5><a href="<?= base_url('business.html#commissioning') ?>">시운전</a><a href="<?= base_url('business.html#support') ?>">기술지원</a><a href="<?= base_url('business.html#maintenance') ?>">유지보수</a></div>
        <div class="mega__col"><h5>프로젝트 실적</h5><a href="<?= base_url('projects.html#domestic') ?>">국내 프로젝트</a><a href="<?= base_url('projects.html#overseas') ?>">해외 프로젝트</a><a href="<?= base_url('projects.html#clients') ?>">주요 고객사</a></div>
        <div class="mega__col"><h5>인재채용</h5><a href="<?= base_url('recruit.html') ?>">채용안내</a><a href="<?= base_url('recruit.html#jobs') ?>">채용공고</a></div>
        <div class="mega__col"><h5>홍보센터</h5><a href="<?= base_url('news.html') ?>">게시판</a><a href="<?= base_url('news.html#gallery') ?>">갤러리</a><a href="<?= base_url('news.html#ci') ?>">CI</a></div>
        <div class="mega__col"><h5>고객문의</h5><a href="<?= base_url('contact.html#inquiry') ?>">문의하기</a><a href="<?= base_url('contact.html#map') ?>">오시는 길</a></div>
      </div></div>
    </div>
    <div class="head__r">
      <div class="lang" role="group" aria-label="Language"><a class="lang__b is-on" href="#" aria-current="true">KR</a><a class="lang__b" href="<?= base_url('en/news.html') ?>">EN</a></div>
      <a class="head__cta" href="<?= base_url('contact.html') ?>">고객문의</a>
      <button class="burger" id="burger" aria-label="메뉴 열기" aria-expanded="false"><span></span><span></span><span></span></button>
    </div>
  </div>
</header>

<div class="drawer" id="drawer" aria-hidden="true">
  <div class="drawer__grp"><b><a href="<?= base_url('about.html') ?>">회사소개</a></b><div class="sub"><a href="<?= base_url('about.html#ceo') ?>">CEO 인사말</a><a href="<?= base_url('about.html#overview') ?>">회사개요</a><a href="<?= base_url('about.html#org') ?>">조직도</a></div></div>
  <div class="drawer__grp"><b><a href="<?= base_url('business.html') ?>">사업분야</a></b><div class="sub"><a href="<?= base_url('business.html#commissioning') ?>">시운전</a><a href="<?= base_url('business.html#support') ?>">기술지원</a><a href="<?= base_url('business.html#maintenance') ?>">유지보수</a></div></div>
  <div class="drawer__grp"><b><a href="<?= base_url('projects.html') ?>">프로젝트 실적</a></b><div class="sub"><a href="<?= base_url('projects.html#domestic') ?>">국내</a><a href="<?= base_url('projects.html#overseas') ?>">해외</a><a href="<?= base_url('projects.html#clients') ?>">주요 고객사</a></div></div>
  <div class="drawer__grp"><b><a href="<?= base_url('recruit.html') ?>">인재채용</a></b><div class="sub"><a href="<?= base_url('recruit.html') ?>">채용안내</a><a href="<?= base_url('recruit.html#jobs') ?>">채용공고</a></div></div>
  <div class="drawer__grp"><b><a href="<?= base_url('news.html') ?>">홍보센터</a></b><div class="sub"><a href="<?= base_url('news.html') ?>">게시판</a><a href="<?= base_url('news.html#gallery') ?>">갤러리</a><a href="<?= base_url('news.html#ci') ?>">CI</a></div></div>
  <div class="drawer__grp"><b><a href="<?= base_url('contact.html') ?>">고객문의</a></b><div class="sub"><a href="<?= base_url('contact.html#inquiry') ?>">문의하기</a><a href="<?= base_url('contact.html#map') ?>">오시는 길</a></div></div>
      <div class="lang lang--drawer" role="group" aria-label="Language"><a class="lang__b is-on" href="#" aria-current="true">KR</a><a class="lang__b" href="<?= base_url('en/news.html') ?>">EN</a></div>
  <a class="btn btn--solid drawer__cta" href="<?= base_url('contact.html') ?>"><span>고객문의</span>
    <svg viewBox="0 0 40 12" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M0 6h37M32 1l6 5-6 5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
</div>

<!-- SUB HERO -->
<section class="subhero" data-nav="dark">
  <div class="wrap">
    <div class="bc"><a href="<?= base_url() ?>">홈</a> <b>›</b> <a href="<?= base_url('news.html') ?>">홍보센터</a> <b>›</b> 게시판</div>
    <h1>게시판</h1>
    <div class="en">Notice Board</div>
  </div>
</section>

<main id="main">

<section class="sec sec--paper" data-nav="light">
  <div class="wrap">
    <article class="post rv">

      <header class="post__head">
        <?php if ($article['is_notice']): ?>
          <span class="post__badge">공지</span>
        <?php endif ?>
        <h2><?= esc($article['title']) ?></h2>
        <p class="post__meta">
          <time datetime="<?= esc(date('Y-m-d', strtotime($article['created_at'])), 'attr') ?>">
            <?= esc(date('Y. m. d.', strtotime($article['created_at']))) ?>
          </time>
        </p>
      </header>

      <div class="post__body"><?= nl2br(esc($article['content'])) ?></div>

      <nav class="post__nav" aria-label="다른 글">
        <?php if ($prev !== null): ?>
          <a class="post__link" href="<?= base_url('news/view/' . $prev['id']) ?>">
            <span>이전 글</span>
            <b><?= esc($prev['title']) ?></b>
          </a>
        <?php endif ?>
        <?php if ($next !== null): ?>
          <a class="post__link post__link--next" href="<?= base_url('news/view/' . $next['id']) ?>">
            <span>다음 글</span>
            <b><?= esc($next['title']) ?></b>
          </a>
        <?php endif ?>
      </nav>

      <p class="post__back">
        <a class="btn btn--solid" href="<?= base_url('news.html') ?>"><span>목록으로</span>
          <svg viewBox="0 0 40 12" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M0 6h37M32 1l6 5-6 5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
      </p>

    </article>
  </div>
</section>
</main>

<!-- FOOTER -->
<footer class="foot" id="contact" data-nav="dark">
  <div class="foot__cta">
    <div class="wrap">
      <h2>프로젝트의 마지막 순간,<br><em>케이원이 함께하겠습니다</em></h2>
      <a class="btn btn--solid" href="<?= base_url('contact.html') ?>"><span>고객문의</span>
        <svg viewBox="0 0 40 12" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M0 6h37M32 1l6 5-6 5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
    </div>
  </div>
  <div class="wrap foot__top">
    <div class="foot__brand">
      <div class="foot__logo">
        <img src="<?= base_url('assets/logo-k1-dark.png') ?>" alt="(주)케이원 · Korea No.1">
      </div>
      <p>조선 · 해양 · 에너지 산업의 Commissioning 전 과정을 하나의 책임체계로 수행하는 Turnkey 전문기업.</p>
    </div>
    <div class="foot__map">
      <div class="foot__col"><h4>회사소개</h4><a href="<?= base_url('about.html#ceo') ?>">CEO 인사말</a><a href="<?= base_url('about.html#overview') ?>">회사개요</a><a href="<?= base_url('about.html#org') ?>">조직도</a></div>
      <div class="foot__col"><h4>사업분야</h4><a href="<?= base_url('business.html#commissioning') ?>">시운전</a><a href="<?= base_url('business.html#support') ?>">기술지원</a><a href="<?= base_url('business.html#maintenance') ?>">유지보수</a></div>
      <div class="foot__col"><h4>프로젝트 실적</h4><a href="<?= base_url('projects.html#domestic') ?>">국내 프로젝트</a><a href="<?= base_url('projects.html#overseas') ?>">해외 프로젝트</a><a href="<?= base_url('projects.html#clients') ?>">주요 고객사</a></div>
      <div class="foot__col"><h4>인재채용</h4><a href="<?= base_url('recruit.html') ?>">채용안내</a><a href="<?= base_url('recruit.html#jobs') ?>">채용공고</a></div>
      <div class="foot__col"><h4>홍보센터</h4><a href="<?= base_url('news.html') ?>">게시판</a><a href="<?= base_url('news.html#gallery') ?>">갤러리</a><a href="<?= base_url('news.html#ci') ?>">CI</a></div>
      <div class="foot__col"><h4>고객문의</h4><a href="<?= base_url('contact.html#inquiry') ?>">문의하기</a><a href="<?= base_url('contact.html#map') ?>">오시는 길</a></div>
    </div>
  </div>
  <div class="wrap foot__info">
    <span><b>K1</b> <?= esc($site['hq_addr']) ?> · <?= esc($site['ulsan_addr']) ?></span>
    <span><b>K1 OCEAN</b> <?= esc($site['yeongam_addr']) ?></span>
    <span><b>TEL</b> <?= esc($site['tel']) ?> · <b>FAX</b> <?= esc($site['fax']) ?></span>
    <span><b>E-mail</b> <?= esc($site['email']) ?></span>
  </div>
  <div class="wrap foot__bot"><a class="foot__pp" href="<?= base_url('privacy.html') ?>">개인정보 처리방침</a><span>© 2026 (주)케이원 (K1 Co., Ltd.) · K1 OCEAN — All rights reserved.</span></div>
</footer>

<script src="<?= base_url('js/v2.js?v=20260902') ?>"></script>
<script>
(function(){
  var links=[].slice.call(document.querySelectorAll('.pagenav a'));
  var secs=links.map(function(a){return document.querySelector(a.getAttribute('href'));});
  var onScroll=function(){var y=window.scrollY+170;var idx=0;secs.forEach(function(s,i){if(s&&s.offsetTop<=y)idx=i;});links.forEach(function(a,i){a.classList.toggle('on',i===idx);});};
  window.addEventListener('scroll',onScroll,{passive:true});onScroll();
})();
</script>
</body>
</html>
