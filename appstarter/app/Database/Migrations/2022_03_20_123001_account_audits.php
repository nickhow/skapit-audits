<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAccountAudits extends Migration
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
            'account_id' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'audit_id'       => [
                'type'       => 'VARCHAR',
                'constraint' => '32',
            ],
            'group_id' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('account_audits');
    }
    public function down()
    {
        $this->forge->dropTable('account_audits');
    }
}
?>