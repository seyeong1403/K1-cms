<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table         = 'settings';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['setting_key', 'setting_value'];
    protected $useTimestamps = true;

    /**
     * 관리자 화면에 보여줄 항목 정의.
     * 여기에 한 줄 더하면 화면·치환이 함께 늘어난다.
     *
     * label   화면에 보이는 이름
     * group   묶음 제목
     * help    입력란 아래 설명 (없으면 생략)
     * type    tel | email | text
     */
    public const FIELDS = [
        'tel'          => ['label' => '대표 전화',   'group' => '대표 연락처', 'type' => 'tel'],
        'fax'          => ['label' => '대표 팩스',   'group' => '대표 연락처', 'type' => 'tel'],
        'email'        => ['label' => '대표 이메일', 'group' => '대표 연락처', 'type' => 'email',
                           'help'  => '홈페이지 문의가 이 주소로 갑니다.'],

        'hq_addr'      => ['label' => '본사 주소',   'group' => '거제 본사',   'type' => 'text'],

        'yeongam_addr' => ['label' => '영암 지사 주소', 'group' => '영암 지사', 'type' => 'text'],
        'yeongam_tel'  => ['label' => '영암 지사 전화', 'group' => '영암 지사', 'type' => 'tel'],
        'yeongam_fax'  => ['label' => '영암 지사 팩스', 'group' => '영암 지사', 'type' => 'tel'],

        'ulsan_addr'   => ['label' => '울산 지사 주소', 'group' => '울산 지사', 'type' => 'text'],
        'ulsan_tel'    => ['label' => '울산 지사 전화', 'group' => '울산 지사', 'type' => 'tel',
                           'help'  => '아직 받지 못한 번호입니다. 받으면 여기에 넣어 주세요.'],
        'ulsan_fax'    => ['label' => '울산 지사 팩스', 'group' => '울산 지사', 'type' => 'tel'],
    ];

    /**
     * 모든 설정을 [키 => 값] 으로 한 번에 읽는다.
     * 화면마다 쓰이므로 한 요청 안에서는 한 번만 조회한다.
     */
    public function all(): array
    {
        static $cache = null;

        if ($cache !== null) {
            return $cache;
        }

        $cache = array_column($this->findAll(), 'setting_value', 'setting_key');

        // 아직 저장된 적 없는 항목은 빈 값으로 채워 둔다(뷰에서 오류가 나지 않도록).
        foreach (array_keys(self::FIELDS) as $key) {
            $cache[$key] = $cache[$key] ?? '';
        }

        return $cache;
    }

    /**
     * 여러 항목을 한 번에 저장한다. 정의에 없는 키는 무시한다.
     */
    public function saveMany(array $values): void
    {
        foreach ($values as $key => $value) {
            if (! isset(self::FIELDS[$key])) {
                continue;
            }

            $row   = $this->where('setting_key', $key)->first();
            $value = trim((string) $value);

            if ($row === null) {
                $this->insert(['setting_key' => $key, 'setting_value' => $value]);
            } else {
                $this->update($row['id'], ['setting_value' => $value]);
            }
        }
    }
}
