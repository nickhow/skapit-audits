<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
class AddSubGroupsToGroups extends Migration
{
    public function up()
    {
        $fields = [
            'uses_sub_groups'       => [
                'type'       => 'tinyint',
                'constraint' => '1',
                'default'    => '0',
            ],
            'is_sub_group' => [
                'type'       => 'int',
                'constraint' => '11',
                'default'    => '0',
            ],
        ];
        $this->forge->addColumn('groups', $fields);
    }

    public function down()
    {
        $fields = [
            'uses_sub_groups',
            'is_sub_group',
        ];
        $this->forge->dropColumn('groups', $fields);
    }
}
?>