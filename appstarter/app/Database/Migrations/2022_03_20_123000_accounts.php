<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAccounts extends Migration
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
            'name'       => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
            ],
            'group_id' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'is_group_manager' => [
                'type'       => 'tiny_int',
                'constraint' => '1',
            ],
            'email'       => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
            ],
            'phone'       => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'accommodation_name'       => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'resort'       => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
            ],
            'country'       => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
            ],
            'notes'       => [
                'type'       => 'VARCHAR',
                'constraint' => '2000',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('accounts');
    }

    public function down()
    {
        $this->forge->dropTable('accounts');
    }
}

?>