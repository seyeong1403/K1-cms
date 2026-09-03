<?php

namespace App\Models;

use CodeIgniter\Model;

class InquiryModel extends Model
{
    protected $table          = 'inquiries';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $allowedFields  = ['purpose', 'company', 'name', 'phone', 'email', 'message', 'is_read'];
    protected $useTimestamps  = true;
    protected $useSoftDeletes = true;

    protected $validationRules = [
        'purpose' => 'required|max_length[50]',
        'name'    => 'required|max_length[50]',
        'email'   => 'required|valid_email|max_length[100]',
        'message' => 'required',
    ];
    protected $validationMessages = [
        'purpose' => ['required' => '문의 목적을 선택해 주세요.'],
        'name'    => ['required' => '이름을 입력해 주세요.'],
        'email'   => [
            'required'    => '이메일을 입력해 주세요.',
            'valid_email' => '이메일 주소를 다시 확인해 주세요.',
        ],
        'message' => ['required' => '문의 내용을 입력해 주세요.'],
    ];

    public function getList(int $per_page, int $page = 1)
    {
        return $this->orderBy('id', 'DESC')->paginate($per_page, 'default', $page);
    }

    /**
     * 아직 읽지 않은 문의 수 — 대시보드와 좌측 메뉴 배지에 쓴다.
     */
    public function countUnread(): int
    {
        return $this->where('is_read', 0)->countAllResults();
    }
}
