<?php

namespace App\Controllers\Admin;

use App\Models\RecruitModel;

class Recruit extends AdminController
{
    protected string $menu_key = 'recruit';

    /** 고용 형태 선택지 — 여기만 고치면 폼과 목록에 함께 반영된다. */
    public const TYPES = ['정규직', '계약직', '인턴', '프리랜서'];

    public function index()
    {
        $model = new RecruitModel();

        return $this->render('admin/recruit_list', [
            'title'        => '채용공고',
            'subtitle'     => '홈페이지 인재채용 > 채용공고에 노출됩니다.',
            'recruit_list' => $model->getList($this->per_page, $this->currentPage()),
            'recruit_model' => $model,
            'pager'        => $model->pager,
            'total_count'  => $model->pager->getTotal(),
        ]);
    }

    public function create()
    {
        return $this->render('admin/recruit_form', [
            'title'    => '공고 등록',
            'subtitle' => '등록하면 홈페이지 채용공고에 바로 올라갑니다.',
            'recruit'  => null,
        ]);
    }

    public function edit($id)
    {
        $recruit = (new RecruitModel())->find($id);

        if ($recruit === null) {
            return redirect()->to('/admin/recruit')->with('error', '없는 공고입니다.');
        }

        return $this->render('admin/recruit_form', [
            'title'    => '공고 수정',
            'subtitle' => esc($recruit['title']),
            'recruit'  => $recruit,
        ]);
    }

    public function store()
    {
        $model = new RecruitModel();

        if (! $model->save($this->postData())) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/admin/recruit')->with('message', '공고를 등록했습니다.');
    }

    public function update($id)
    {
        $model = new RecruitModel();

        if ($model->find($id) === null) {
            return redirect()->to('/admin/recruit')->with('error', '없는 공고입니다.');
        }

        if (! $model->update($id, $this->postData())) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/admin/recruit')->with('message', '공고를 수정했습니다.');
    }

    public function delete($id)
    {
        (new RecruitModel())->delete($id);

        return redirect()->to('/admin/recruit')->with('message', '공고를 삭제했습니다.');
    }

    private function postData(): array
    {
        return [
            'title'           => trim((string) $this->request->getPost('title')),
            'employment_type' => (string) $this->request->getPost('employment_type'),
            'content'         => (string) $this->request->getPost('content'),
            'starts_at'       => $this->request->getPost('starts_at') ?: null,
            'ends_at'         => $this->request->getPost('ends_at') ?: null,
            'is_open'         => $this->request->getPost('is_open') ? 1 : 0,
        ];
    }
}
