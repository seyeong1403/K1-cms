<?php

namespace App\Models;

use CodeIgniter\Model;

class RecruitModel extends Model
{
    protected $table          = 'recruits';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $allowedFields  = ['title', 'employment_type', 'content', 'starts_at', 'ends_at', 'is_open'];
    protected $useTimestamps  = true;
    protected $useSoftDeletes = true;

    protected $validationRules = [
        'title'           => 'required|max_length[200]',
        'employment_type' => 'required|max_length[30]',
        'content'         => 'required',
    ];
    protected $validationMessages = [
        'title' => [
            'required'   => '공고 제목을 입력해 주세요.',
            'max_length' => '공고 제목은 200자까지 입력할 수 있습니다.',
        ],
        'employment_type' => [
            'required' => '고용 형태를 선택해 주세요.',
        ],
        'content' => [
            'required' => '공고 내용을 입력해 주세요.',
        ],
    ];

    public function getList(int $per_page, int $page = 1)
    {
        return $this->orderBy('is_open', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate($per_page, 'default', $page);
    }

    /**
     * 마감일이 지났는지 — 목록에서 '마감' 배지를 붙이는 데 쓴다.
     * 마감일을 비워 두면 상시 모집이므로 지나지 않은 것으로 본다.
     */
    public function isExpired(array $recruit): bool
    {
        if (empty($recruit['ends_at'])) {
            return false;
        }

        return strtotime($recruit['ends_at']) < strtotime(date('Y-m-d'));
    }
}
