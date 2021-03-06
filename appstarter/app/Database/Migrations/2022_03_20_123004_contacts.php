<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddContacts extends Migration
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
            'audit_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '32',
            ],
            'date timestamp default current_timestamp on update current_timestamp',
            'comment' => [
                'type'       => 'VARCHAR',
                'constraint' => '2000',
            ],
            'account_id' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'is_admin' => [
                'type'       => 'tinyint',
                'constraint' => '1',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('contacts');
    }

    public function down()
    {
        $this->forge->dropTable('contacts');
    }
}

?>