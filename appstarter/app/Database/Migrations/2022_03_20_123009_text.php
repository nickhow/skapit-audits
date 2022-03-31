<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddText extends Migration
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
            'en' => [
                'type'       => 'text',
            ],
            'fr' => [
                'type'       => 'text',
            ],
            'de' => [
                'type'       => 'text',
            ],
            'es' => [
                'type'       => 'text',
            ],
            'it' => [
                'type'       => 'text',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('text');
    }

    public function down()
    {
        $this->forge->dropTable('text');
    }
}

?>