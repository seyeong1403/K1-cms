<?php

namespace App\Controllers\Admin;

use App\Models\AdminUserModel;

class Auth extends AdminController
{
    /** 1분 동안 허용할 로그인 시도 횟수 */
    private const MAX_TRIES = 6;

    public function login()
    {
        // 이미 로그인해 있으면 로그인 화면을 다시 보여줄 이유가 없다.
        if (session('admin_id')) {
            return redirect()->to('/admin/dashboard');
        }

        return view('admin/login');
    }

    public function doLogin()
    {
        // 같은 곳에서 짧은 시간에 여러 번 시도하면 잠시 막는다(비밀번호 자동 대입 방지).
        if (! service('throttler')->check(md5('login-' . $this->request->getIPAddress()), self::MAX_TRIES, MINUTE)) {
            return redirect()->back()->withInput()
                ->with('error', '로그인 시도가 너무 잦습니다. 1분 후에 다시 시도해 주세요.');
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $admin = (new AdminUserModel())->verify($username, $password);

        if ($admin === null) {
            // 아이디가 틀렸는지 비밀번호가 틀렸는지 구분해서 알리지 않는다.
            return redirect()->back()->withInput()
                ->with('error', '아이디 또는 비밀번호가 올바르지 않습니다.');
        }

        // 로그인 시점에 세션 아이디를 새로 발급한다(세션 고정 공격 방지).
        session()->regenerate();
        session()->set([
            'admin_id'   => $admin['id'],
            'admin_name' => $admin['name'],
        ]);

        (new AdminUserModel())->update($admin['id'], ['last_login_at' => date('Y-m-d H:i:s')]);

        $redirect_url = session('redirect_url') ?: base_url('admin/dashboard');
        session()->remove('redirect_url');

        return redirect()->to($redirect_url);
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/admin/login')->with('message', '로그아웃되었습니다.');
    }

    public function password()
    {
        return $this->render('admin/password', [
            'title'    => '비밀번호 변경',
            'subtitle' => '주기적으로 바꿔 주세요.',
        ]);
    }

    public function updatePassword()
    {
        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]',
        ];
        $messages = [
            'current_password' => ['required' => '현재 비밀번호를 입력해 주세요.'],
            'new_password'     => [
                'required'   => '새 비밀번호를 입력해 주세요.',
                'min_length' => '새 비밀번호는 8자 이상이어야 합니다.',
            ],
            'confirm_password' => [
                'required' => '새 비밀번호를 한 번 더 입력해 주세요.',
                'matches'  => '새 비밀번호가 서로 다릅니다.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $model = new AdminUserModel();
        $admin = $model->find(session('admin_id'));

        if (! password_verify((string) $this->request->getPost('current_password'), $admin['password'])) {
            return redirect()->back()->withInput()
                ->with('error', '현재 비밀번호가 올바르지 않습니다.');
        }

        $model->setPassword((int) $admin['id'], (string) $this->request->getPost('new_password'));

        return redirect()->to('/admin/password')->with('message', '비밀번호를 변경했습니다.');
    }
}
