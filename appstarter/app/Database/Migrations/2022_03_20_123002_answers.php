<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAnswers extends Migration
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
            'question_id' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'answer'       => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'score_ba' => [
                'type'       => 'int',
                'constraint' => '11',
                'default' => null,
            ],
            'score_abta' => [
                'type'       => 'int',
                'constraint' => '11',
                'default' => null,
            ],
            'en'       => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'fr'       => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'de'       => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'es'       => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'it'       => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'precedence' => [
                'type'       => 'int',
                'constraint' => '1',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('answers');
    }

    public function down()
    {
        $this->forge->dropTable('answers');
    }
}

?>