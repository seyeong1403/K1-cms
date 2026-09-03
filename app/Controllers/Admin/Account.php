<?php

namespace App\Controllers\Admin;

use App\Models\AdminUserModel;

/**
 * 관리자 계정 관리.
 * 담당자가 바뀌어도 업체가 직접 계정을 만들고 지울 수 있어야 한다.
 */
class Account extends AdminController
{
    protected string $menu_key = 'account';

    public function index()
    {
        return $this->render('admin/account_list', [
            'title'        => '관리자 계정',
            'subtitle'     => '이 화면에 들어올 수 있는 사람을 관리합니다.',
            'account_list' => (new AdminUserModel())->orderBy('id', 'ASC')->findAll(),
        ]);
    }

    public function create()
    {
        return $this->render('admin/account_form', [
            'title'    => '계정 추가',
            'subtitle' => '새 담당자에게 줄 계정을 만듭니다.',
            'account'  => null,
        ]);
    }

    public function edit($id)
    {
        $account = (new AdminUserModel())->find($id);

        if ($account === null) {
            return redirect()->to('/admin/account')->with('error', '없는 계정입니다.');
        }

        return $this->render('admin/account_form', [
            'title'    => '계정 수정',
            'subtitle' => $account['username'],
            'account'  => $account,
        ]);
    }

    public function store()
    {
        $rules = [
            'username' => 'required|min_length[4]|max_length[50]|alpha_dash|is_unique[admin_users.username]',
            'name'     => 'required|max_length[50]',
            'password' => 'required|min_length[8]',
        ];

        if (! $this->validate($rules, $this->messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new AdminUserModel())->insert([
            'username' => $this->request->getPost('username'),
            'name'     => trim((string) $this->request->getPost('name')),
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/admin/account')->with('message', '계정을 추가했습니다.');
    }

    public function update($id)
    {
        $model   = new AdminUserModel();
        $account = $model->find($id);

        if ($account === null) {
            return redirect()->to('/admin/account')->with('error', '없는 계정입니다.');
        }

        // 아이디는 바꾸지 않는다(로그인 기록과 어긋나므로). 이름과 비밀번호만 손본다.
        $rules = ['name' => 'required|max_length[50]'];
        $new_password = (string) $this->request->getPost('password');

        if ($new_password !== '') {
            $rules['password'] = 'min_length[8]';
        }

        if (! $this->validate($rules, $this->messages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = ['name' => trim((string) $this->request->getPost('name'))];
        if ($new_password !== '') {
            $data['password'] = password_hash($new_password, PASSWORD_DEFAULT);
        }
        $model->update($id, $data);

        return redirect()->to('/admin/account')->with('message', '계정을 수정했습니다.');
    }

    public function delete($id)
    {
        $model = new AdminUserModel();
        $id    = (int) $id;

        // 자기 자신을 지우면 그 자리에서 로그아웃된다.
        if ($id === (int) session('admin_id')) {
            return redirect()->to('/admin/account')
                ->with('error', '지금 로그인한 계정은 지울 수 없습니다.');
        }

        // 마지막 한 명까지 지우면 아무도 들어올 수 없게 된다.
        if ($model->countAllResults() <= 1) {
            return redirect()->to('/admin/account')
                ->with('error', '관리자 계정은 최소 한 개가 있어야 합니다.');
        }

        $model->delete($id);

        return redirect()->to('/admin/account')->with('message', '계정을 삭제했습니다.');
    }

    private function messages(): array
    {
        return [
            'username' => [
                'required'   => '아이디를 입력해 주세요.',
                'min_length' => '아이디는 4자 이상이어야 합니다.',
                'alpha_dash' => '아이디는 영문·숫자와 - _ 만 쓸 수 있습니다.',
                'is_unique'  => '이미 쓰고 있는 아이디입니다.',
            ],
            'name'     => ['required' => '이름을 입력해 주세요.'],
            'password' => [
                'required'   => '비밀번호를 입력해 주세요.',
                'min_length' => '비밀번호는 8자 이상이어야 합니다.',
            ],
        ];
    }
}
