<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryModel extends Model
{
    protected $table          = 'galleries';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $allowedFields  = ['title', 'image_path', 'thumb_path', 'sort_order'];
    protected $useTimestamps  = true;
    protected $useSoftDeletes = true;

    protected $validationRules = [
        'title' => 'required|max_length[200]',
    ];
    protected $validationMessages = [
        'title' => [
            'required'   => '사진 설명을 입력해 주세요.',
            'max_length' => '사진 설명은 200자까지 입력할 수 있습니다.',
        ],
    ];

    /**
     * 노출 순서대로. 순서 값이 같으면 나중에 올린 사진이 앞으로 온다.
     */
    public function getList(int $per_page, int $page = 1)
    {
        return $this->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'DESC')
            ->paginate($per_page, 'default', $page);
    }

    /**
     * 새 사진의 기본 순서 값 — 항상 목록 맨 뒤에 붙는다.
     */
    public function nextSortOrder(): int
    {
        $row = $this->selectMax('sort_order')->first();

        return (int) ($row['sort_order'] ?? 0) + 10;
    }
}
