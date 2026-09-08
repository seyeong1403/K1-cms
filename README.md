# (주)케이원 홈페이지 CMS

조선·해양·에너지 시운전 전문기업 **(주)케이원**(k1tnc.co.kr) 홈페이지와 관리자페이지.
디자인은 클라이언트 최종 컨펌 완료, **오픈 전 단계**입니다.

- **PHP 8.2+ / CodeIgniter 4.7.4 / MySQL** (로컬 개발은 SQLite)
- 사내 표준 `AI 웹사이트 제작 표준`(PHP 8.2 + CI4 + MySQL)을 따랐습니다.
- 상세한 진행 상황·함정·오픈 절차는 **[`_작업노트.md`](_작업노트.md)** 에 있습니다. **먼저 읽어 주세요.**

---

## 개발자님께 먼저 여쭙고 싶은 것

**카페24 웹호스팅에서 FTP 접속 시 `www` 폴더 위로 올라갈 수 있나요?**

CI4는 `public/`이 문서 루트여야 하는데 카페24는 `www` 고정이라고 알고 있습니다.

- **올라갈 수 있으면** — 표준 구조 그대로, `www`를 `public/`에 연결
- **올라갈 수 없으면** — `public/` 내용물을 `www`에 두고, `public/index.php`의
  `require FCPATH . '../app/Config/Paths.php';` 와 `app/Config/Paths.php` 의 상대 경로를 수정

어느 쪽인지 알려주시면 그 기준으로 배포 구조를 맞추겠습니다. 호스팅은 아직 개설 전입니다.

---

## 로컬에서 띄우기

```bash
composer install
cp env .env
# .env 편집: CI_ENVIRONMENT, app.baseURL, database.* (아래 참고)
php spark migrate
php spark db:seed AdminUserSeeder     # 관리자 계정 1개 생성
php spark db:seed SettingSeeder       # 연락처·주소 초기값
php spark serve
```

`http://localhost:8080` 홈페이지 · `http://localhost:8080/admin/login` 관리자

**초기 계정**: 비밀번호는 코드에 두지 않습니다. `.env` 에 아래를 적고 시드를 실행하세요.

```ini
admin.initialUsername = k1admin
admin.initialPassword = <직접 정한 8자 이상 비밀번호>
admin.initialName     = 케이원 관리자
```

### DB 설정

로컬은 SQLite, 서버는 MySQL입니다. 마이그레이션이 동일해 코드 수정은 없습니다.

```ini
# 로컬 (SQLite) — ⚠️ 파일명만 적습니다. 경로를 쓰면 CI4가 WRITEPATH를 앞에 또 붙입니다.
database.default.DBDriver = SQLite3
database.default.database = k1.db

# 서버 (MySQL)
database.default.DBDriver = MySQLi
database.default.hostname = localhost
database.default.database = <DB명>
database.default.username = <계정>
database.default.password = <비밀번호>
```

---

## 구조

```
app/
├── Controllers/
│   ├── Page.php          고정 페이지(메인·회사소개·사업분야·실적·문의·처리방침, 국문/영문)
│   ├── News.php          홍보센터 — 게시판 목록/상세 + 갤러리
│   ├── Recruit.php       인재채용 — 공고 목록/상세
│   ├── Admin/            관리자 (AdminController 상속)
│   └── Api/Contact.php   문의 접수 (POST /api/contact)
├── Models/               Article · Gallery · Recruit · Inquiry · AdminUser · Setting
├── Views/
│   ├── admin/            관리자 화면 (layout.php 공통)
│   └── site/
│       ├── pages/        고정 페이지 (en/ 하위에 영문)
│       ├── news.php      recruit.php      목록
│       └── news_view.php recruit_view.php 상세
├── Filters/AdminAuth.php 로그인 검사
└── Database/
    ├── Migrations/       표 6개
    └── Seeds/            AdminUser · Setting · Demo · DemoClear · ThumbBackfill

public/                   자산만 (css · js · assets · images · uploads)
_tools/check_sync.py      정적 원본과 문구가 어긋났는지 검사
_preview/build_preview.py 관리자 화면 정적 미리보기 생성
```

### 주소 규칙

기존 링크를 손대지 않으려고 **`.html` 확장자를 유지**했습니다.

| 주소 | 처리 |
| --- | --- |
| `/`, `/index.html`, `/about.html` … | `Page::show` |
| `/en/`, `/en/about.html` … | `Page::english` |
| `/news.html`, `/news/view/{id}` | `News` |
| `/recruit.html`, `/recruit/view/{id}` | `Recruit` |
| `/admin/**` | `Admin\*` (adminAuth 필터) |
| `POST /api/contact` | 문의 접수 |

⚠️ **`public/`에 `news.html`·`recruit.html` 파일을 만들지 마세요.** 파일이 있으면 웹서버가 그걸 먼저 내보내 라우트가 무시됩니다.

---

## 관리자 기능

| 화면 | 내용 |
| --- | --- |
| 대시보드 | 건수 4종 + 최근 게시글·문의 |
| 게시판 | 목록·검색·CRUD, 공지 상단 고정, 페이지네이션 |
| 갤러리 | 업로드·교체·삭제, 노출 순서, **원본 1600px·썸네일 640px 자동 생성** |
| 채용공고 | CRUD, 모집 기간, 노출 On/Off (마감일 지나면 공개 화면에서 자동 제외) |
| 문의 내역 | 목록·상세·삭제, 열람 시 읽음 처리, 메일 답장 |
| 회사 정보 | 연락처·주소를 한 곳에서 관리 → **국문·영문 18개 화면에 반영** |
| 관리자 계정 | CRUD (본인·마지막 1개는 삭제 불가) |

### 회사 정보(`settings`)가 화면에 붙는 방식

`BaseController::initController`에서 `service('renderer')->setData(['site' => ...], 'raw')`로
전체 뷰에 공유합니다. 뷰에서는 `<?= esc($site['tel']) ?>` 형태로 사용합니다.

항목 추가는 `SettingModel::FIELDS`에 한 줄 추가하면 관리자 화면도 함께 늘어납니다.

---

## 보안

사내 표준 3장 기준으로 적용했습니다.

- **CSRF · invalidchars 필터 활성화** — CI4 기본값이 꺼져 있어 `Config/Filters.php`에서 켰습니다.
- **업로드 3중 검증** — 확장자 화이트리스트(jpg/png/webp) → 5MB 제한 → `getimagesize()`로 실제 이미지 확인.
  파일명은 `getRandomName()`으로 재생성, 삭제는 업로드 폴더 내 경로만 허용.
- **비밀번호** — `password_hash()`, `AdminUserModel::setPassword()` 경유만. 로그인 실패 시에도 해시 검증을 1회 수행(타이밍 노출 방지).
- **로그인 시도 제한** — 동일 IP 1분 6회 (`throttler`).
- **문의 폼** — 동의 필수, 1시간 5건 제한, honeypot.
- ⚠️ **문의 폼 CSRF 토큰**은 응답 JSON에 새 토큰을 실어 보내고 JS가 교체합니다
  (`Contact::freshToken()` ↔ `public/js/v2.js`). 제거하면 2회차 전송이 403이 됩니다.

---

## 배포 (오픈 시)

1. 카페24 호스팅 개설 (PHP 8.2+, MySQL) — 위 **문서 루트 질문** 확인 후 구조 결정
2. `.env`: `CI_ENVIRONMENT = production`, `app.baseURL`, MySQL 접속정보, **SMTP**
3. `composer install --no-dev` → `php spark migrate` → `db:seed AdminUserSeeder` → `db:seed SettingSeeder`
4. **정적 홈페이지 합치기** — 별도 저장소 [K1-homepage](https://github.com/seyeong1403/K1-homepage)의
   자산을 `public/`으로 (`_작업노트.md` §5)
5. `writable/`, `public/uploads/` 권한 775
6. `.htaccess` — https 강제, ErrorDocument 404
7. `php spark db:seed DemoClearSeeder` — 시험 데이터 정리
8. 관리자 비밀번호 변경, DNS **A 레코드만** 변경
   (⚠️ MX는 카카오메일 `aspmx.daum.net`이 살아 있어 건드리면 회사 메일이 끊깁니다)

---

## 아직 안 된 것

**클라이언트 회신 대기** — 호스팅 계정, 도메인(아이네임즈) 계정 복구, 울산 지사 전화·팩스,
SMTP 계정, 영문 로고, 영문 번역문 감수

**의향 확인 필요** — 프로젝트 실적 관리 화면(현재 뷰에 하드코딩), 게시글 첨부파일·본문 이미지

**미구현** — 개인정보 처리방침 값 관리(보유기간·수탁자·보호책임자·시행일),
방문자 통계 도입 여부(도입 시 처리방침 제9조 쿠키 조항 수정 필요)

---

## 관련 저장소

- **[K1-homepage](https://github.com/seyeong1403/K1-homepage)** — 정적 원본 + 클라이언트 검토용 미리보기
  (https://seyeong1403.github.io/K1-homepage/)
  같은 페이지가 두 곳에 있어, 문구 수정 후에는 `python _tools/check_sync.py`로 대조해 주세요.
