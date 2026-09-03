<?php

namespace App\Controllers;

use App\Models\RecruitModel;

/**
 * 인재채용 — 채용안내와 진행 중인 공고.
 */
class Recruit extends BaseController
{
    public function index()
    {
        $model = new RecruitModel();

        return view('site/recruit', [
            // 노출을 켜 둔 공고만, 마감일이 지난 것은 빼고 보여준다.
            'recruit_list' => array_values(array_filter(
                $model->where('is_open', 1)->orderBy('id', 'DESC')->findAll(),
                static fn ($job) => ! $model->isExpired($job)
            )),
        ]);
    }

    public function view($id)
    {
        $model   = new RecruitModel();
        $recruit = $model->find($id);

        if ($recruit === null || ! $recruit['is_open']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('site/recruit_view', [
            'recruit'    => $recruit,
            'is_expired' => $model->isExpired($recruit),
        ]);
    }
}
