<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
class AddAuditToSalesforce extends Migration
{
    public function up()
    {
        $fields = [
            'added_to_salesforce' => [
                'type'       => 'tinyint',
                'constraint' => '1',
            ],
        ];
        $this->forge->addColumn('audits', $fields);
    }

    public function down()
    {
        $fields = [
            'added_to_salesforce',
        ];
        $this->forge->dropColumn('audits', $fields);
    }
}
?>