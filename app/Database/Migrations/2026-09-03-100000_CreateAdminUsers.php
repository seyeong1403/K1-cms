<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdminUsers extends Migration
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
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'comment'    => '로그인 아이디',
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'comment'    => 'password_hash() 결과',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'comment'    => '관리자 이름',
            ],
            'last_login_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->createTable('admin_users');
    }

    public function down()
    {
        $this->forge->dropTable('admin_users');
    }
}
