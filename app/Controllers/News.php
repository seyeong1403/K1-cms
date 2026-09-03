<?php

namespace App\Controllers;

use App\Models\ArticleModel;
use App\Models\GalleryModel;

/**
 * 홍보센터 — 게시판·갤러리·CI.
 * 관리자에서 올린 글과 사진이 이 화면에 나온다.
 */
class News extends BaseController
{
    private const PER_PAGE = 15;

    public function index()
    {
        $article_model = new ArticleModel();
        $gallery_model = new GalleryModel();

        $page = max(1, (int) $this->request->getGet('page'));

        return view('site/news', [
            'article_list'  => $article_model->getList(self::PER_PAGE, $page),
            'article_total' => $article_model->pager->getTotal(),
            'pager'         => $article_model->pager,
            'current_page'  => $page,
            'per_page'      => self::PER_PAGE,
            'gallery_list'  => $gallery_model->orderBy('sort_order', 'ASC')->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function view($id)
    {
        $model   = new ArticleModel();
        $article = $model->find($id);

        if ($article === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // 조회수는 한 번 올리되, 화면에는 쓰지 않는다(관리자만 참고).
        $model->update($id, ['view_count' => (int) $article['view_count'] + 1]);

        return view('site/news_view', [
            'article' => $article,
            'prev'    => $model->where('id <', $id)->orderBy('id', 'DESC')->first(),
            'next'    => $model->where('id >', $id)->orderBy('id', 'ASC')->first(),
        ]);
    }
}
