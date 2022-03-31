<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmails extends Migration
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
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'language' => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
            ],
            'html' => [
                'type'       => 'VARCHAR',
                'constraint' => '2000',
            ],
            'text' => [
                'type'       => 'VARCHAR',
                'constraint' => '2000',
            ],
            'subject' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('emails');
    }

    public function down()
    {
        $this->forge->dropTable('emails');
    }
}

?>