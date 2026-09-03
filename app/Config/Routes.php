<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 고정 페이지 — 주소는 기존 링크를 그대로 쓰도록 .html 을 유지한다
$routes->get('/', 'Page::show/index');
$routes->get('(index|about|business|projects|contact|privacy)\.html', 'Page::show/$1');
$routes->get('en', 'Page::english/index');
$routes->get('en/', 'Page::english/index');
$routes->get('en/(index|about|business|projects|contact|privacy|news|recruit)\.html', 'Page::english/$1');

// ---------------------------------------------------------------------
// 홈페이지(공개) — 관리자에서 올린 내용이 나오는 화면
// 나머지 페이지는 아직 정적 HTML 이라 주소를 .html 그대로 유지한다.
// ---------------------------------------------------------------------
$routes->get('news.html', 'News::index');
$routes->get('news', 'News::index');
$routes->get('news/view/(:num)', 'News::view/$1');

$routes->get('recruit.html', 'Recruit::index');
$routes->get('recruit', 'Recruit::index');
$routes->get('recruit/view/(:num)', 'Recruit::view/$1');

// ---------------------------------------------------------------------
// API — 문의 접수
// ---------------------------------------------------------------------
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    $routes->post('contact', 'Contact::submit');
});

// ---------------------------------------------------------------------
// 관리자 — /admin
// ---------------------------------------------------------------------
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], static function ($routes) {
    // 로그인 화면은 필터 밖에 둔다(로그인하러 가는 길까지 막으면 안 되므로).
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::doLogin');
    $routes->get('logout', 'Auth::logout');

    // 여기서부터는 로그인해야 들어올 수 있다.
    $routes->group('', ['filter' => 'adminAuth'], static function ($routes) {
        $routes->get('/', 'Dashboard::index');
        $routes->get('dashboard', 'Dashboard::index');

        // 게시판
        $routes->get('article', 'Article::index');
        $routes->get('article/create', 'Article::create');
        $routes->post('article/store', 'Article::store');
        $routes->get('article/edit/(:num)', 'Article::edit/$1');
        $routes->post('article/update/(:num)', 'Article::update/$1');
        $routes->post('article/delete/(:num)', 'Article::delete/$1');

        // 갤러리
        $routes->get('gallery', 'Gallery::index');
        $routes->get('gallery/create', 'Gallery::create');
        $routes->post('gallery/store', 'Gallery::store');
        $routes->get('gallery/edit/(:num)', 'Gallery::edit/$1');
        $routes->post('gallery/update/(:num)', 'Gallery::update/$1');
        $routes->post('gallery/delete/(:num)', 'Gallery::delete/$1');

        // 채용공고
        $routes->get('recruit', 'Recruit::index');
        $routes->get('recruit/create', 'Recruit::create');
        $routes->post('recruit/store', 'Recruit::store');
        $routes->get('recruit/edit/(:num)', 'Recruit::edit/$1');
        $routes->post('recruit/update/(:num)', 'Recruit::update/$1');
        $routes->post('recruit/delete/(:num)', 'Recruit::delete/$1');

        // 문의 내역 (읽기·삭제만, 관리자가 쓰는 화면이 아니다)
        $routes->get('inquiry', 'Inquiry::index');
        $routes->get('inquiry/view/(:num)', 'Inquiry::view/$1');
        $routes->post('inquiry/delete/(:num)', 'Inquiry::delete/$1');

        // 회사 정보
        $routes->get('setting', 'Setting::index');
        $routes->post('setting/update', 'Setting::update');

        // 관리자 계정
        $routes->get('account', 'Account::index');
        $routes->get('account/create', 'Account::create');
        $routes->post('account/store', 'Account::store');
        $routes->get('account/edit/(:num)', 'Account::edit/$1');
        $routes->post('account/update/(:num)', 'Account::update/$1');
        $routes->post('account/delete/(:num)', 'Account::delete/$1');

        // 비밀번호 변경
        $routes->get('password', 'Auth::password');
        $routes->post('password', 'Auth::updatePassword');
    });
});

// 없는 주소는 우리 404 화면으로
$routes->set404Override('App\Controllers\Page::notFound');
