<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticleModel extends Model
{
    protected $table         = 'articles';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['title', 'content', 'is_notice', 'view_count'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    protected $validationRules = [
        'title'   => 'required|max_length[200]',
        'content' => 'required',
    ];
    protected $validationMessages = [
        'title' => [
            'required'   => '제목을 입력해 주세요.',
            'max_length' => '제목은 200자까지 입력할 수 있습니다.',
        ],
        'content' => [
            'required' => '내용을 입력해 주세요.',
        ],
    ];

    /**
     * 관리자 목록 — 공지 고정 글이 항상 위에 오고, 그 안에서 최신순.
     */
    public function getList(int $per_page, int $page = 1, string $keyword = '')
    {
        $builder = $this->orderBy('is_notice', 'DESC')->orderBy('id', 'DESC');

        if ($keyword !== '') {
            $builder = $builder->groupStart()
                ->like('title', $keyword)
                ->orLike('content', $keyword)
                ->groupEnd();
        }

        return $builder->paginate($per_page, 'default', $page);
    }
}
