<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * 최초 관리자 계정을 만든다.
 *   php spark db:seed AdminUserSeeder
 *
 * 비밀번호는 코드에 두지 않는다. 실행 전에 .env 에 적어 둘 것.
 *
 *   admin.initialUsername = k1admin
 *   admin.initialPassword = <직접 정한 비밀번호>
 *   admin.initialName     = 케이원 관리자
 *
 * 계정을 만든 뒤에는 .env 에서 비밀번호 줄을 지워도 된다.
 */
class AdminUserSeeder extends Seeder
{
    private const MIN_LENGTH = 8;

    public function run()
    {
        $username = env('admin.initialUsername', 'k1admin');
        $password = (string) env('admin.initialPassword', '');
        $name     = env('admin.initialName', '관리자');

        if ($password === '') {
            echo '중단: .env 에 admin.initialPassword 를 먼저 적어 주세요.' . PHP_EOL;
            echo '      (보안상 비밀번호를 코드에 두지 않습니다)' . PHP_EOL;

            return;
        }

        if (mb_strlen($password) < self::MIN_LENGTH) {
            echo '중단: 비밀번호는 ' . self::MIN_LENGTH . '자 이상이어야 합니다.' . PHP_EOL;

            return;
        }

        $exists = $this->db->table('admin_users')->where('username', $username)->countAllResults();

        if ($exists > 0) {
            echo '이미 ' . $username . ' 계정이 있어 건너뜁니다.' . PHP_EOL;

            return;
        }

        $now = date('Y-m-d H:i:s');
        $this->db->table('admin_users')->insert([
            'username'   => $username,
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'name'       => $name,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        echo '관리자 계정을 만들었습니다: ' . $username . PHP_EOL;
        echo '로그인 후 비밀번호를 한 번 더 바꾸는 것을 권합니다.' . PHP_EOL;
    }
}
