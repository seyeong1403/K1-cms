<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminUserModel extends Model
{
    protected $table         = 'admin_users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['username', 'password', 'name', 'last_login_at'];
    protected $useTimestamps = true;

    /**
     * 아이디로 관리자 한 명을 찾는다.
     */
    public function findByUsername(string $username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * 아이디·비밀번호를 확인하고 일치하면 관리자 정보를 돌려준다.
     * 존재하지 않는 아이디여도 해시 검증을 한 번 수행해
     * 응답 시간으로 아이디 존재 여부가 드러나지 않게 한다.
     */
    public function verify(string $username, string $password)
    {
        $admin = $this->findByUsername($username);
        $hash  = $admin['password'] ?? '$2y$10$invalidinvalidinvalidinvalidinvalidinvalidinvalidinvalidinv';

        if (! password_verify($password, $hash) || $admin === null) {
            return null;
        }

        return $admin;
    }

    /**
     * 비밀번호는 반드시 이 메서드로만 저장한다(평문 저장 방지).
     */
    public function setPassword(int $id, string $password): bool
    {
        return $this->update($id, [
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }
}
