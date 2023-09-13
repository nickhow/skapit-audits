<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddeJhAuditScore extends Migration
{
    public function up()
    {
        $fields = [
            'result_ejh'       => [
                'type'       => 'varchar',
                'constraint' => '300',
            ],
            'expiry_date_ejh timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'total_score_ejh'       => [
                'type'       => 'INT',
                'constraint' => '11',
            ],
        ];
        $this->forge->addColumn('audits', $fields);
    }

    public function down()
    {
        $fields = [
            'score_ejh','expiry_date_ejh','total_score_ejh',
        ];
        $this->forge->dropColumn('audits', $fields);
    }
}
?>