<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUploads extends Migration
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
            'file_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'file_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'original_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('uploads');
    }

    public function down()
    {
        $this->forge->dropTable('uploads');
    }
}

?>