<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * 최초 관리자 계정 1개를 만든다.
 *   php spark db:seed AdminUserSeeder
 *
 * 여기 적힌 비밀번호는 개발·설치용이다.
 * 실제 서버에 올린 뒤에는 반드시 관리자 화면에서 바꿀 것.
 */
class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $exists = $this->db->table('admin_users')->where('username', 'k1admin')->countAllResults();

        if ($exists > 0) {
            echo '이미 관리자 계정이 있어 건너뜁니다.' . PHP_EOL;

            return;
        }

        $this->db->table('admin_users')->insert([
            'username'   => 'k1admin',
            'password'   => password_hash('k1admin!2026', PASSWORD_DEFAULT),
            'name'       => '케이원 관리자',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        echo '관리자 계정 생성: k1admin' . PHP_EOL;
    }
}
