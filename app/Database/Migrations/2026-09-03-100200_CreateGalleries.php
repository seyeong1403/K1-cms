<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGalleries extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'comment'    => '사진 설명 (대체 텍스트로도 사용)',
            ],
            'image_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'comment'    => 'public 기준 상대 경로',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => '작을수록 앞에 노출',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('galleries');
    }

    public function down()
    {
        $this->forge->dropTable('galleries');
    }
}
