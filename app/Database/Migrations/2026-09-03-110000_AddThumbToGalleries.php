<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * 갤러리 목록·홈페이지 타일에 쓸 작은 사진 경로.
 * 원본을 그대로 내려받게 하면 방문자 쪽이 느려진다.
 */
class AddThumbToGalleries extends Migration
{
    public function up()
    {
        $this->forge->addColumn('galleries', [
            'thumb_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'image_path',
                'comment'    => '축소본 경로. 없으면 원본을 쓴다',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('galleries', 'thumb_path');
    }
}
