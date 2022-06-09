<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
class AddAliasToAdminUser extends Migration
{
    public function up()
    {
        $fields = [
            'alias'       => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
            ],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $fields = [
            'alias',
        ];
        $this->forge->dropColumn('users', $fields);
    }
}
?>