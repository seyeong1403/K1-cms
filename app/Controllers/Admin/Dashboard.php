<?php

namespace App\Controllers\Admin;

use App\Models\ArticleModel;
use App\Models\GalleryModel;
use App\Models\InquiryModel;
use App\Models\RecruitModel;

class Dashboard extends AdminController
{
    protected string $menu_key = 'dashboard';

    public function index()
    {
        $article_model = new ArticleModel();
        $gallery_model = new GalleryModel();
        $recruit_model = new RecruitModel();
        $inquiry_model = new InquiryModel();

        return $this->render('admin/dashboard', [
            'title'    => '대시보드',
            'subtitle' => '홈페이지에 올라가 있는 내용을 한눈에 봅니다.',
            'stats'    => [
                'article' => $article_model->countAllResults(),
                'gallery' => $gallery_model->countAllResults(),
                'recruit' => $recruit_model->where('is_open', 1)->countAllResults(),
                'unread'  => $inquiry_model->countUnread(),
            ],
            // '최근'이라고 적었으니 번호가 아니라 날짜순으로 보여준다
            'recent_articles'  => $article_model->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->findAll(5),
            'recent_inquiries' => $inquiry_model->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->findAll(5),
        ]);
    }
}
