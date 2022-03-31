<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResponses extends Migration
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
            'question_id' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'answer_id' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'suggested_score_ba' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'suggested_score_abta' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'score_ba' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'score_abta' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'comment' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
            ],
            'custom_answer' => [
                'type'       => 'VARCHAR',
                'constraint' => '2000',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('responses');
    }

    public function down()
    {
        $this->forge->dropTable('responses');
    }
}

?>