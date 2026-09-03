<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRecruits extends Migration
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
                'comment'    => '공고 제목',
            ],
            'employment_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'comment'    => '정규직 / 계약직 / 인턴 등',
            ],
            'content' => [
                'type' => 'TEXT',
            ],
            'starts_at' => ['type' => 'DATE', 'null' => true],
            'ends_at'   => ['type' => 'DATE', 'null' => true, 'comment' => '비우면 상시 모집'],
            'is_open' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1이면 홈페이지에 노출',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('recruits');
    }

    public function down()
    {
        $this->forge->dropTable('recruits');
    }
}
