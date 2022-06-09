<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
class AddAdminOwnerToAudit extends Migration
{
    public function up()
    {
        $fields = [
            'audit_owner_id'       => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
            ],
        ];
        $this->forge->addColumn('audits', $fields);
    }

    public function down()
    {
        $fields = [
            'audit_owner_id',
        ];
        $this->forge->dropColumn('audits', $fields);
    }
}
?>