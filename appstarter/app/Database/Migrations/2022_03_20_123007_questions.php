<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddQuestions extends Migration
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
            'question_number' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'question' => [
                'type'       => 'VARCHAR',
                'constraint' => '800',
            ],
            'en' => [
                'type'       => 'VARCHAR',
                'constraint' => '800',
            ],
            'fr' => [
                'type'       => 'VARCHAR',
                'constraint' => '800',
            ],
            'de' => [
                'type'       => 'VARCHAR',
                'constraint' => '800',
            ],
            'es' => [
                'type'       => 'VARCHAR',
                'constraint' => '800',
            ],
            'it' => [
                'type'       => 'VARCHAR',
                'constraint' => '800',
            ],
            'hide_for_1' => [
                'type'       => 'tinyint',
                'constraint' => '1',
                'default' => '0',
            ],
            'hide_for_2' => [
                'type'       => 'tinyint',
                'constraint' => '1',
                'default' => '0',
            ],
            'hide_for_3' => [
                'type'       => 'tinyint',
                'constraint' => '1',
                'default' => '0',
            ],
            'hide_for_4' => [
                'type'       => 'tinyint',
                'constraint' => '1',
                'default' => '0',
            ],
            'hide_for_5' => [
                'type'       => 'tinyint',
                'constraint' => '1',
                'default' => '0',
            ],
            'has_custom_answer' => [
                'type'       => 'tinyint',
                'constraint' => '1',
                'default' => '0',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('questions');
    }

    public function down()
    {
        $this->forge->dropTable('questions');
    }
}

?>