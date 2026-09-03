# -*- coding: utf-8 -*-
"""관리자 화면을 정적 HTML로 떠서 미리보기 폴더를 만든다.

관리자페이지는 서버에서 도는 프로그램이라 GitHub Pages 에 그대로 올릴 수 없다.
그래서 로컬 서버에 로그인해 각 화면의 HTML 을 받아 오고, 링크를 파일끼리 연결해
'눌러서 화면을 돌아다닐 수는 있는' 디자인 미리보기를 만든다.
저장·삭제 같은 실제 동작은 되지 않으므로 상단에 안내 띠를 붙인다.

    python _preview/build_preview.py          # 로컬 서버(8080)가 떠 있어야 한다
"""
import http.cookiejar
import os
import re
import shutil
import sys
import urllib.parse
import urllib.request

sys.stdout.reconfigure(encoding='utf-8')

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUT  = os.path.join(ROOT, '_preview', 'dist')
BASE = 'http://localhost:8080'
ID, PW = 'k1admin', 'k1admin!2026'

# 떠 올 화면 — (주소, 저장할 파일명)
PAGES = [
    ('/admin/login',            'login.html'),
    ('/admin/dashboard',        'index.html'),
    ('/admin/article',          'article.html'),
    ('/admin/article/create',   'article-create.html'),
    ('/admin/article/edit/1',   'article-edit.html'),
    ('/admin/gallery',          'gallery.html'),
    ('/admin/gallery/create',   'gallery-create.html'),
    ('/admin/gallery/edit/1',   'gallery-edit.html'),
    ('/admin/recruit',          'recruit.html'),
    ('/admin/recruit/create',   'recruit-create.html'),
    ('/admin/recruit/edit/1',   'recruit-edit.html'),
    ('/admin/inquiry',          'inquiry.html'),
    ('/admin/inquiry/view/1',   'inquiry-view.html'),
    ('/admin/password',         'password.html'),
]

# 링크 바꾸기 — 긴 주소부터 먼저 검사해야 짧은 규칙에 먼저 걸리지 않는다
LINKS = [
    ('/admin/article/create',  'article-create.html'),
    ('/admin/gallery/create',  'gallery-create.html'),
    ('/admin/recruit/create',  'recruit-create.html'),
    ('/admin/article/edit/',   'article-edit.html'),
    ('/admin/gallery/edit/',   'gallery-edit.html'),
    ('/admin/recruit/edit/',   'recruit-edit.html'),
    ('/admin/inquiry/view/',   'inquiry-view.html'),
    ('/admin/article/delete/', '#'),
    ('/admin/gallery/delete/', '#'),
    ('/admin/recruit/delete/', '#'),
    ('/admin/inquiry/delete/', '#'),
    ('/admin/article',         'article.html'),
    ('/admin/gallery',         'gallery.html'),
    ('/admin/recruit',         'recruit.html'),
    ('/admin/inquiry',         'inquiry.html'),
    ('/admin/password',        'password.html'),
    ('/admin/dashboard',       'index.html'),
    ('/admin/logout',          'login.html'),
    ('/admin/login',           'login.html'),
    ('/admin/',                'index.html'),
]

NOTICE = """<div class="pv-note" role="note">
  <b>디자인 미리보기</b>
  <span>화면 구성을 보시는 용도입니다. 메뉴 이동은 되지만 저장·삭제·업로드는 동작하지 않습니다.
  내용은 모두 <b>샘플</b>이며 실제 자료가 아닙니다.</span>
</div>
<style>
.pv-note{position:sticky;top:0;z-index:100;display:flex;flex-wrap:wrap;align-items:baseline;gap:4px 10px;
  padding:10px 20px;background:#0E2233;color:#CFE0EC;font-size:13.5px;line-height:1.5}
.pv-note b{color:#fff;font-weight:800}
.pv-note span b{color:#23A4D6}
.adm .top{top:41px}
@media (max-width:820px){.pv-note{position:static}.adm .top{top:0}}
</style>
"""


def set_env(mode):
    """CI4 는 요청마다 .env 를 읽는다. 미리보기를 뜨는 동안만 운영 모드로 바꾼다."""
    p = os.path.join(ROOT, '.env')
    s = open(p, encoding='utf-8').read()
    s = re.sub(r'^CI_ENVIRONMENT = .*$', 'CI_ENVIRONMENT = ' + mode, s, flags=re.M)
    open(p, 'w', encoding='utf-8', newline='').write(s)


def fetch(opener, path):
    with opener.open(BASE + path, timeout=20) as r:
        return r.read().decode('utf-8')


def rewrite(html):
    """절대 주소를 미리보기 파일 이름으로 바꾸고, 서버가 필요한 동작은 막는다."""
    for src, dst in LINKS:
        if src.endswith('/'):
            # 뒤에 붙는 글 번호까지 함께 걷어낸다 (article-edit.html3 같은 주소가 생기지 않게)
            html = re.sub(re.escape(BASE + src) + r'\d+', dst, html)
        else:
            html = html.replace(BASE + src, dst)
    # 남은 정적 자산 주소는 상대 경로로
    html = html.replace(BASE + '/', '')
    # 폼은 눌러도 아무 데도 가지 않게
    html = re.sub(r'<form([^>]*?)\saction="[^"]*"', r'<form\1 action="#" onsubmit="return false"', html)
    # 확인창·버튼 잠금 스크립트는 미리보기에서 방해가 된다
    html = html.replace('<script src="js/admin.js', '<script data-off src="js/admin.js')
    html = re.sub(r'<script data-off src="[^"]*"></script>', '', html)
    # 안내 띠를 body 맨 앞에 붙인다
    html = html.replace('<body>', '<body>\n' + NOTICE, 1)
    return html


def main():
    if os.path.isdir(OUT):
        shutil.rmtree(OUT)
    os.makedirs(OUT)

    set_env('production')   # 디버그 도구가 붙지 않도록
    try:
        collect(opener_new())
    finally:
        set_env('development')


def opener_new():
    jar = http.cookiejar.CookieJar()
    return urllib.request.build_opener(urllib.request.HTTPCookieProcessor(jar))


def collect(opener):
    # 로그인 (CSRF 토큰을 먼저 받아야 한다)
    login_html = fetch(opener, '/admin/login')
    m = re.search(r'name="csrf_test_name" value="([^"]+)"', login_html)
    if not m:
        raise SystemExit('CSRF 토큰을 찾지 못했습니다. 서버가 떠 있는지 확인하세요.')
    body = urllib.parse.urlencode({
        'csrf_test_name': m.group(1), 'username': ID, 'password': PW,
    }).encode()
    opener.open(BASE + '/admin/login', body, timeout=20).read()

    for path, name in PAGES:
        try:
            html = fetch(opener, path)
        except Exception as e:  # 샘플 글이 없는 화면은 건너뛴다
            print('건너뜀:', path, e)
            continue
        open(os.path.join(OUT, name), 'w', encoding='utf-8', newline='').write(rewrite(html))
        print('만듦:', name)

    # 자산 복사
    for folder in ('css', 'js', 'images', 'uploads'):
        src = os.path.join(ROOT, 'public', folder)
        if os.path.isdir(src):
            shutil.copytree(src, os.path.join(OUT, folder))
    # 업로드 폴더의 .gitignore 는 실제 서버용이다.
    # 미리보기에는 사진이 보여야 하므로 따라오면 안 된다(따라오면 사진이 통째로 빠진다).
    for root, _dirs, names in os.walk(os.path.join(OUT, 'uploads')):
        for n in names:
            if n == '.gitignore':
                os.remove(os.path.join(root, n))
    print('자산 복사 완료')

    # 검색엔진에 잡히지 않게
    open(os.path.join(OUT, 'robots.txt'), 'w', encoding='utf-8').write('User-agent: *\nDisallow: /\n')

    left = sum(open(os.path.join(OUT, n), encoding='utf-8').read().count(BASE)
               for _, n in PAGES if os.path.exists(os.path.join(OUT, n)))
    print('\n남은 localhost 주소:', left, '개')


if __name__ == '__main__':
    main()
