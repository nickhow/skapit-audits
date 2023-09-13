<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddeJhResponseScore extends Migration
{
    public function up()
    {
        $fields = [
            'suggested_score_ejh'       => [
                'type'       => 'INT',
                'constraint' => '11',
            ],
            'score_ejh'       => [
                'type'       => 'INT',
                'constraint' => '11',
                'default'    => null,
            ],
        ];
        $this->forge->addColumn('responses', $fields);
    }

    public function down()
    {
        $fields = [
            'score_ejh','suggested_score_ejh',
        ];
        $this->forge->dropColumn('responses', $fields);
    }
}
?>