<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    /**
     * 로그인하지 않았으면 로그인 화면으로 보낸다.
     * 원래 가려던 주소를 세션에 남겨 로그인 후 그 화면으로 돌아가게 한다.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('admin_id')) {
            $session->set('redirect_url', current_url());

            return redirect()->to('/admin/login')
                ->with('error', '로그인이 필요합니다.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
