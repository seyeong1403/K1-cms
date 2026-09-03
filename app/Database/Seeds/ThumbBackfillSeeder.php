<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Throwable;

/**
 * 축소본이 없는 갤러리 사진에 축소본을 만들어 채운다.
 *   php spark db:seed ThumbBackfillSeeder
 *
 * 축소본 기능을 넣기 전에 올린 사진들을 위한 것이며, 여러 번 돌려도 안전하다.
 */
class ThumbBackfillSeeder extends Seeder
{
    private const THUMB_WIDTH = 640;

    public function run()
    {
        $rows = $this->db->table('galleries')
            ->groupStart()->where('thumb_path', null)->orWhere('thumb_path', '')->groupEnd()
            ->get()->getResultArray();

        if ($rows === []) {
            echo '축소본이 없는 사진이 없습니다.' . PHP_EOL;

            return;
        }

        $made = 0;
        foreach ($rows as $row) {
            $source = FCPATH . $row['image_path'];
            if (! is_file($source)) {
                echo '원본 없음: ' . $row['image_path'] . PHP_EOL;
                continue;
            }

            $thumb = dirname($row['image_path']) . '/thumb_' . basename($row['image_path']);

            try {
                service('image')->withFile($source)
                    ->resize(self::THUMB_WIDTH, self::THUMB_WIDTH, true, 'width')
                    ->save(FCPATH . $thumb, 78);
            } catch (Throwable $e) {
                echo '만들지 못함: ' . $row['image_path'] . ' — ' . $e->getMessage() . PHP_EOL;
                continue;
            }

            $this->db->table('galleries')->where('id', $row['id'])->update(['thumb_path' => $thumb]);
            $made++;
        }

        echo '축소본 ' . $made . '장을 만들었습니다.' . PHP_EOL;
    }
}
