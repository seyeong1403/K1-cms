<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * 지금 홈페이지에 적혀 있는 값을 그대로 설정으로 옮긴다.
 *   php spark db:seed SettingSeeder
 * 이미 값이 있으면 건드리지 않는다(관리자가 고친 값을 되돌리지 않도록).
 */
class SettingSeeder extends Seeder
{
    private const CURRENT = [
        'tel'          => '055-736-5959',
        'fax'          => '055-736-6969',
        'email'        => 'minseon.kim@k1tnc.co.kr',
        'hq_addr'      => '경남 거제시 옥포대첩로 59, 2F',
        'yeongam_addr' => '전남 영암군 삼호읍 대불로 93',
        'yeongam_tel'  => '061-460-3937',
        'yeongam_fax'  => '061-462-7037',
        'ulsan_addr'   => '울산 동구 방어진순환도로 400',
        'ulsan_tel'    => '',   // 업체 미수령
        'ulsan_fax'    => '',   // 업체 미수령
    ];

    public function run()
    {
        $now   = date('Y-m-d H:i:s');
        $added = 0;

        foreach (self::CURRENT as $key => $value) {
            $exists = $this->db->table('settings')->where('setting_key', $key)->countAllResults();
            if ($exists > 0) {
                continue;
            }

            $this->db->table('settings')->insert([
                'setting_key'   => $key,
                'setting_value' => $value,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
            $added++;
        }

        echo '설정 ' . $added . '건을 넣었습니다.' . PHP_EOL;
    }
}
