<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * 화면 확인용으로 넣었던 샘플 데이터를 모두 지운다.
 *   php spark db:seed DemoClearSeeder
 *
 * 지우는 것: 게시글 · 갤러리(사진 파일 포함) · 채용공고 · 문의
 * 남기는 것: 관리자 계정, 회사 정보(연락처·주소)
 *
 * 오픈 직전에 한 번 돌린다. 실제 자료가 들어간 뒤에는 절대 돌리지 말 것.
 */
class DemoClearSeeder extends Seeder
{
    public function run()
    {
        // 갤러리는 파일도 함께 지운다(소프트 딜리트된 것까지).
        $photos = $this->db->table('galleries')->get()->getResultArray();
        $files  = 0;

        foreach ($photos as $photo) {
            foreach (['image_path', 'thumb_path'] as $key) {
                $path = $photo[$key] ?? '';
                // 업로드 폴더 안의 파일만 지운다.
                if ($path === '' || ! str_starts_with($path, 'uploads/gallery/')) {
                    continue;
                }
                if (is_file(FCPATH . $path)) {
                    @unlink(FCPATH . $path);
                    $files++;
                }
            }
        }

        $counts = [];
        foreach (['articles', 'galleries', 'recruits', 'inquiries'] as $table) {
            $counts[$table] = $this->db->table($table)->countAllResults();
            $this->db->table($table)->truncate();
        }

        echo '지웠습니다 — 게시글 ' . $counts['articles'] . '건, 갤러리 ' . $counts['galleries'] . '장'
            . '(파일 ' . $files . '개), 채용공고 ' . $counts['recruits'] . '건, 문의 ' . $counts['inquiries'] . '건'
            . PHP_EOL;
        echo '관리자 계정과 회사 정보는 그대로 두었습니다.' . PHP_EOL;
    }
}
