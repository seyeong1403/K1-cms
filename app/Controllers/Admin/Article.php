<?php

namespace App\Controllers\Admin;

use App\Models\ArticleModel;

class Article extends AdminController
{
    protected string $menu_key = 'article';

    public function index()
    {
        $model   = new ArticleModel();
        $keyword = trim((string) $this->request->getGet('q'));

        return $this->render('admin/article_list', [
            'title'        => '게시판',
            'subtitle'     => '홈페이지 홍보센터에 노출되는 공지사항과 소식입니다.',
            'article_list' => $model->getList($this->per_page, $this->currentPage(), $keyword),
            'pager'        => $model->pager,
            'total_count'  => $model->pager->getTotal(),
            'keyword'      => $keyword,
        ]);
    }

    public function create()
    {
        return $this->render('admin/article_form', [
            'title'    => '글쓰기',
            'subtitle' => '등록하면 홈페이지 게시판에 바로 올라갑니다.',
            'article'  => null,
        ]);
    }

    public function edit($id)
    {
        $article = (new ArticleModel())->find($id);

        if ($article === null) {
            return redirect()->to('/admin/article')->with('error', '없는 글입니다.');
        }

        return $this->render('admin/article_form', [
            'title'    => '글 수정',
            'subtitle' => esc($article['title']),
            'article'  => $article,
        ]);
    }

    public function store()
    {
        $model = new ArticleModel();

        if (! $model->save($this->postData())) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/admin/article')->with('message', '글을 등록했습니다.');
    }

    public function update($id)
    {
        $model = new ArticleModel();

        if ($model->find($id) === null) {
            return redirect()->to('/admin/article')->with('error', '없는 글입니다.');
        }

        if (! $model->update($id, $this->postData())) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/admin/article')->with('message', '글을 수정했습니다.');
    }

    public function delete($id)
    {
        (new ArticleModel())->delete($id);

        return redirect()->to('/admin/article')->with('message', '글을 삭제했습니다.');
    }

    /**
     * 폼에서 넘어온 값만 골라 담는다.
     * 체크박스는 꺼져 있으면 아예 전송되지 않으므로 여기서 0을 채운다.
     */
    private function postData(): array
    {
        return [
            'title'     => trim((string) $this->request->getPost('title')),
            'content'   => (string) $this->request->getPost('content'),
            'is_notice' => $this->request->getPost('is_notice') ? 1 : 0,
        ];
    }
}
