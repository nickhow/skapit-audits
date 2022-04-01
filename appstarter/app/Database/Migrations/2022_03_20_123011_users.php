<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUsers extends Migration
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
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'is_admin' => [
                'type'       => 'tinyint',
                'constraint' => '1',
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
            ],
            'group_id' => [
                'type'       => 'int',
                'constraint' => '20',
            ],
            'is_hotelcheck' => [
                'type'       => 'tinyint',
                'constraint' => '4',
            ],
            'account_id' => [
                'type'       => 'int',
                'constraint' => '1',
                'comment'    => 'For account level users',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}

?>