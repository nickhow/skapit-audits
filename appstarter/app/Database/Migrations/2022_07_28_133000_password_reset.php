<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPasswordReset extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'email' => [
                'type'       => 'varchar',
                'constraint' => '255',
            ],
            'selector'       => [
                'type'       => 'varchar',
                'constraint' => '16',
            ],
            'token'       => [
                'type'       => 'varchar',
                'constraint' => '64',
            ],
            'expires datetime',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('password_reset_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('password_reset_tokens');
    }
}
?>