<?php

namespace App\Controllers\Admin;

use App\Models\InquiryModel;

class Inquiry extends AdminController
{
    protected string $menu_key = 'inquiry';

    public function index()
    {
        $model = new InquiryModel();

        return $this->render('admin/inquiry_list', [
            'title'        => '문의 내역',
            'subtitle'     => '홈페이지 고객문의로 접수된 내용입니다.',
            'inquiry_list' => $model->getList($this->per_page, $this->currentPage()),
            'pager'        => $model->pager,
            'total_count'  => $model->pager->getTotal(),
        ]);
    }

    public function view($id)
    {
        $model   = new InquiryModel();
        $inquiry = $model->find($id);

        if ($inquiry === null) {
            return redirect()->to('/admin/inquiry')->with('error', '없는 문의입니다.');
        }

        // 열어 본 순간 읽음으로 표시한다.
        if (! $inquiry['is_read']) {
            $model->update($id, ['is_read' => 1]);
            $inquiry['is_read'] = 1;
        }

        return $this->render('admin/inquiry_view', [
            'title'    => '문의 내용',
            'subtitle' => $inquiry['name'] . ' 님 · ' . date('Y-m-d H:i', strtotime($inquiry['created_at'])),
            'inquiry'  => $inquiry,
        ]);
    }

    public function delete($id)
    {
        (new InquiryModel())->delete($id);

        return redirect()->to('/admin/inquiry')->with('message', '문의를 삭제했습니다.');
    }
}
