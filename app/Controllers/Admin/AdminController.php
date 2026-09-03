<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\InquiryModel;

/**
 * 관리자 화면 공통 부모.
 * 모든 화면이 필요로 하는 값(좌측 메뉴 표시, 안 읽은 문의 수)을 한 곳에서 채운다.
 */
abstract class AdminController extends BaseController
{
    /** 좌측 메뉴에서 현재 위치로 표시할 항목 */
    protected string $menu_key = '';

    /** 한 페이지에 보여줄 목록 개수 */
    protected int $per_page = 15;

    protected function render(string $view, array $data = [])
    {
        $data['current']      = $data['current'] ?? $this->menu_key;
        $data['unread_count'] = (new InquiryModel())->countUnread();

        return view($view, $data);
    }

    /**
     * 목록 화면의 현재 페이지 번호. 1보다 작은 값은 1로 본다.
     */
    protected function currentPage(): int
    {
        return max(1, (int) $this->request->getGet('page'));
    }
}
