# -*- coding: utf-8 -*-
"""정적 홈페이지(k1-homepage)와 CI4 화면(k1-cms)의 '글'이 어긋났는지 검사한다.

두 곳에 같은 페이지가 있다. 업체 수정 요청을 한쪽에만 반영하면
오픈했을 때 옛 문구가 나간다. 그걸 막기 위한 검사다.

파일을 그대로 비교하지 않는다. CI4 쪽은 연락처가 설정값으로 바뀌어 있고
문의 폼도 구조가 다르기 때문이다. 대신 **화면에 보이는 글자**만 뽑아 비교한다.

    python _tools/check_sync.py            # 검사
    python _tools/check_sync.py --detail   # 다른 부분을 줄 단위로 보여줌
"""
import html
import os
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SRC  = os.path.join(os.path.dirname(ROOT), 'k1-homepage')
VIEW = os.path.join(ROOT, 'app', 'Views', 'site')

# 원본 파일 → CI4 화면
PAIRS = [
    ('index.html',    'pages/index.php'),
    ('about.html',    'pages/about.php'),
    ('business.html', 'pages/business.php'),
    ('projects.html', 'pages/projects.php'),
    ('contact.html',  'pages/contact.php'),
    ('privacy.html',  'pages/privacy.php'),
    ('404.html',      'pages/404.php'),
    ('news.html',     'news.php'),
    ('recruit.html',  'recruit.php'),
]
PAIRS += [('en/%s.html' % n, 'pages/en/%s.php' % n)
          for n in ['index', 'about', 'business', 'projects', 'contact', 'privacy', 'news', 'recruit']]

# 일부러 다르게 만든 곳 — 다르다고 나와도 정상이다.
EXPECTED = {
    'contact.html':    '문의 폼을 서버 접수 방식으로 바꿔 안내 문구가 다르다',
    'en/contact.html': '문의 폼을 서버 접수 방식으로 바꿔 안내 문구가 다르다',
    'news.html':       '게시판·갤러리가 DB 연동이라 비어 있을 때 문구만 같다',
    'recruit.html':    '채용공고가 DB 연동이라 비어 있을 때 문구만 같다',
}


# 설정으로 옮긴 주소들 — 원본에는 글자로, CI4 에는 설정값으로 들어 있다
ADDRESSES = [
    '경남 거제시 옥포대첩로 59, 2F',
    '전남 영암군 삼호읍 대불로 93',
    '울산 동구 방어진순환도로 400',
]


def text_of(path):
    """화면에 보이는 글자만 남긴다."""
    s = open(path, encoding='utf-8').read()

    s = re.sub(r'<\?.*?\?>', ' ', s, flags=re.S)          # PHP 부분
    s = re.sub(r'<script.*?</script>', ' ', s, flags=re.S | re.I)
    s = re.sub(r'<style.*?</style>', ' ', s, flags=re.S | re.I)
    s = re.sub(r'<!--.*?-->', ' ', s, flags=re.S)
    s = re.sub(r'<[^>]+>', ' ', s)                        # 태그
    s = html.unescape(s)
    s = re.sub(r'\s+', ' ', s)

    # 연락처·주소는 CI4 쪽에서 설정값으로 빠져 있으므로 비교에서 뺀다
    # (이 값들이 어긋났는지는 관리자 '회사 정보' 한 곳만 보면 된다)
    s = re.sub(r'0\d{1,2}-\d{3,4}-\d{4}', '', s)
    s = re.sub(r'[\w.+-]+@[\w.-]+', '', s)
    for addr in ADDRESSES:
        s = s.replace(addr, '')

    return s.strip()


def words(t):
    return [w for w in t.split(' ') if w]


def main():
    detail = '--detail' in sys.argv
    if not os.path.isdir(SRC):
        raise SystemExit('원본 폴더를 찾지 못했습니다: ' + SRC)

    problems, expected_hits, missing = [], [], []

    for src_rel, view_rel in PAIRS:
        a = os.path.join(SRC, src_rel)
        b = os.path.join(VIEW, view_rel)
        if not os.path.exists(a) or not os.path.exists(b):
            missing.append(src_rel)
            continue

        wa, wb = words(text_of(a)), words(text_of(b))
        only_src = [w for w in wa if w not in wb]
        only_cms = [w for w in wb if w not in wa]

        if not only_src and not only_cms:
            continue

        if src_rel in EXPECTED:
            expected_hits.append(src_rel)
            continue

        problems.append((src_rel, only_src, only_cms))

    print('원본 : %s' % SRC)
    print('화면 : %s\n' % VIEW)

    if missing:
        print('짝을 찾지 못함 (%d개)' % len(missing))
        for m in missing:
            print('   ?', m)
        print()

    if not problems:
        print('글이 어긋난 페이지가 없습니다.')
    else:
        print('글이 다른 페이지 (%d개)' % len(problems))
        for rel, only_src, only_cms in problems:
            print('   *', rel)
            if detail:
                if only_src:
                    print('       원본에만 :', ' '.join(only_src[:12]))
                if only_cms:
                    print('       CI4에만  :', ' '.join(only_cms[:12]))
        if not detail:
            print('\n   --detail 을 붙이면 어떤 낱말이 다른지 보여줍니다.')

    if expected_hits:
        print('\n(정상) 아래는 일부러 다르게 만든 곳입니다')
        for rel in expected_hits:
            print('   -', rel, '—', EXPECTED[rel])

    return 1 if problems else 0


if __name__ == '__main__':
    sys.exit(main())
