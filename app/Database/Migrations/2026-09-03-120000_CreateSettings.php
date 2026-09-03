<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * 사이트 곳곳에 나오는 값(연락처·주소 등)을 한 곳에 모아 둔다.
 * 지금은 17개 파일에 같은 전화번호가 박혀 있어, 하나 바뀌면 전부 고쳐야 한다.
 */
class CreateSettings extends Migration
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
            'setting_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 60,
                'comment'    => '화면에서 쓰는 이름 (tel, fax, email …)',
            ],
            'setting_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('setting_key');
        $this->forge->createTable('settings');
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
