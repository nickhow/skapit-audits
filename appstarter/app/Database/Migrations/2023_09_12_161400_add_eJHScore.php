<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddeJHScoring extends Migration
{
    public function up()
    {
        $fields = [
            'score_ejh'       => [
                'type'       => 'INT',
                'constraint' => '11',
                'default'    => '0',
            ],
        ];
        $this->forge->addColumn('answers', $fields);
    }

    public function down()
    {
        $fields = [
            'score_eJh',
        ];
        $this->forge->dropColumn('answers', $fields);
    }
}
?>