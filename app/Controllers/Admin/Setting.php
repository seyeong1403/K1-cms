<?php

namespace App\Controllers\Admin;

use App\Models\SettingModel;

/**
 * 회사 정보 — 홈페이지 곳곳에 나오는 연락처·주소를 한 곳에서 고친다.
 */
class Setting extends AdminController
{
    protected string $menu_key = 'setting';

    public function index()
    {
        return $this->render('admin/setting_form', [
            'title'    => '회사 정보',
            'subtitle' => '여기서 고치면 홈페이지 전체(국문·영문)에 함께 반영됩니다.',
            'values'   => (new SettingModel())->all(),
            'fields'   => SettingModel::FIELDS,
        ]);
    }

    public function update()
    {
        $rules = [
            'tel'   => 'permit_empty|max_length[30]',
            'fax'   => 'permit_empty|max_length[30]',
            'email' => 'permit_empty|valid_email|max_length[100]',
        ];
        $messages = [
            'email' => ['valid_email' => '이메일 주소를 다시 확인해 주세요.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new SettingModel())->saveMany($this->request->getPost());

        return redirect()->to('/admin/setting')->with('message', '회사 정보를 저장했습니다.');
    }
}
