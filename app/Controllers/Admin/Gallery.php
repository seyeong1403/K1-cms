<?php

namespace App\Controllers\Admin;

use App\Models\GalleryModel;

class Gallery extends AdminController
{
    protected string $menu_key = 'gallery';
    protected int $per_page    = 20;

    /** 올릴 수 있는 사진 형식과 최대 용량 */
    private const ALLOWED  = ['jpg', 'jpeg', 'png', 'webp'];
    private const MAX_KB   = 5120;
    private const SAVE_DIR    = 'uploads/gallery';
    private const MAX_WIDTH   = 1600;   // 원본을 이 폭으로 줄여 보관
    private const THUMB_WIDTH = 640;    // 목록·타일용

    public function index()
    {
        $model = new GalleryModel();

        return $this->render('admin/gallery_list', [
            'title'        => '갤러리',
            'subtitle'     => '홈페이지 홍보센터 갤러리에 노출되는 현장 사진입니다.',
            'gallery_list' => $model->getList($this->per_page, $this->currentPage()),
            'pager'        => $model->pager,
            'total_count'  => $model->pager->getTotal(),
        ]);
    }

    public function create()
    {
        return $this->render('admin/gallery_form', [
            'title'    => '사진 올리기',
            'subtitle' => 'JPG · PNG · WebP, 한 장에 5MB까지.',
            'gallery'  => null,
        ]);
    }

    public function edit($id)
    {
        $gallery = (new GalleryModel())->find($id);

        if ($gallery === null) {
            return redirect()->to('/admin/gallery')->with('error', '없는 사진입니다.');
        }

        return $this->render('admin/gallery_form', [
            'title'    => '사진 수정',
            'subtitle' => esc($gallery['title']),
            'gallery'  => $gallery,
        ]);
    }

    public function store()
    {
        $model = new GalleryModel();
        $title = trim((string) $this->request->getPost('title'));

        $image_path = $this->saveImage();

        if ($image_path === null) {
            return redirect()->back()->withInput()
                ->with('error', session('upload_error') ?? '사진을 선택해 주세요.');
        }

        $saved = $model->save([
            'title'      => $title,
            'image_path' => $image_path,
            'thumb_path' => $this->thumbPath($image_path),
            'sort_order' => (int) ($this->request->getPost('sort_order') ?: $model->nextSortOrder()),
        ]);

        if (! $saved) {
            $this->removeImage($image_path);
            $this->removeImage($this->thumbPath($image_path));

            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/admin/gallery')->with('message', '사진을 올렸습니다.');
    }

    public function update($id)
    {
        $model   = new GalleryModel();
        $gallery = $model->find($id);

        if ($gallery === null) {
            return redirect()->to('/admin/gallery')->with('error', '없는 사진입니다.');
        }

        $data = [
            'title'      => trim((string) $this->request->getPost('title')),
            'sort_order' => (int) $this->request->getPost('sort_order'),
        ];

        // 새 사진을 골랐을 때만 파일을 바꾼다.
        $new_path = $this->saveImage();
        if ($new_path !== null) {
            $data['image_path'] = $new_path;
            $data['thumb_path'] = $this->thumbPath($new_path);
        } elseif (session('upload_error')) {
            return redirect()->back()->withInput()->with('error', session('upload_error'));
        }

        if (! $model->update($id, $data)) {
            if ($new_path !== null) {
                $this->removeImage($new_path);
                $this->removeImage($this->thumbPath($new_path));
            }

            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        // 교체가 끝난 뒤에 옛 파일을 지운다(저장이 실패하면 원본이 남아 있어야 하므로).
        if ($new_path !== null) {
            $this->removeImage($gallery['image_path']);
            $this->removeImage($gallery['thumb_path'] ?? null);
        }

        return redirect()->to('/admin/gallery')->with('message', '사진을 수정했습니다.');
    }

    public function delete($id)
    {
        $model   = new GalleryModel();
        $gallery = $model->find($id);

        if ($gallery !== null) {
            $model->delete($id);
            $this->removeImage($gallery['image_path']);
            $this->removeImage($gallery['thumb_path'] ?? null);
        }

        return redirect()->to('/admin/gallery')->with('message', '사진을 삭제했습니다.');
    }

    /**
     * 올라온 사진을 검사하고 저장한다.
     * 파일을 고르지 않았으면 null 을 돌려주고, 문제가 있으면 사유를 세션에 남긴다.
     *
     * 원본 파일명은 쓰지 않고 새 이름을 만든다 — 한글·공백·확장자 위장 파일을 걸러내기 위해.
     */
    private function saveImage(): ?string
    {
        $file = $this->request->getFile('image');

        if ($file === null || ! $file->isValid()) {
            return null;
        }

        $ext = strtolower($file->getClientExtension());

        if (! in_array($ext, self::ALLOWED, true)) {
            session()->setFlashdata('upload_error', 'JPG · PNG · WebP 형식만 올릴 수 있습니다.');

            return null;
        }

        if ($file->getSizeByUnit('kb') > self::MAX_KB) {
            session()->setFlashdata('upload_error', '사진 한 장은 5MB까지 올릴 수 있습니다.');

            return null;
        }

        // 확장자만 바꾼 위장 파일을 막기 위해 실제 이미지인지 확인한다.
        if (@getimagesize($file->getTempName()) === false) {
            session()->setFlashdata('upload_error', '이미지 파일이 아닙니다.');

            return null;
        }

        $dir = FCPATH . self::SAVE_DIR;
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $name = $file->getRandomName();
        $file->move($dir, $name);

        $path = self::SAVE_DIR . '/' . $name;
        $this->shrink($dir . '/' . $name, self::MAX_WIDTH);
        $this->makeThumb($dir, $name);

        return $path;
    }

    /**
     * 큰 사진은 가로 기준으로 줄인다.
     * 휴대폰으로 찍은 사진이 그대로 홈페이지에 걸리면 방문자 쪽이 느려진다.
     */
    private function shrink(string $full, int $width): void
    {
        [$w] = @getimagesize($full) ?: [0];
        if ($w === 0 || $w <= $width) {
            return;
        }

        service('image')->withFile($full)->resize($width, $width, true, 'width')->save($full, 82);
    }

    /**
     * 목록·타일에 쓸 작은 사진을 따로 만든다. 파일명은 원본 앞에 thumb_ 를 붙인다.
     */
    private function makeThumb(string $dir, string $name): void
    {
        $thumb = 'thumb_' . $name;
        try {
            service('image')->withFile($dir . '/' . $name)
                ->resize(self::THUMB_WIDTH, self::THUMB_WIDTH, true, 'width')
                ->save($dir . '/' . $thumb, 78);
        } catch (\Throwable $e) {
            return;   // 축소본을 못 만들어도 원본으로 보여주면 된다
        }
    }

    /**
     * 원본 경로로부터 축소본 경로를 만든다(없으면 null).
     */
    private function thumbPath(?string $image_path): ?string
    {
        if ($image_path === null) {
            return null;
        }
        $dir  = dirname($image_path);
        $file = 'thumb_' . basename($image_path);

        return is_file(FCPATH . $dir . '/' . $file) ? $dir . '/' . $file : null;
    }

    /**
     * 업로드 폴더 안의 파일만 지운다(경로 조작으로 다른 파일이 지워지지 않게).
     */
    private function removeImage(?string $path): void
    {
        if ($path === null || ! str_starts_with($path, self::SAVE_DIR . '/')) {
            return;
        }

        $full = FCPATH . $path;
        if (is_file($full)) {
            @unlink($full);
        }
    }
}
