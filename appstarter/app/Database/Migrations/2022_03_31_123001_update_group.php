<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
class UpdateGroup extends Migration
{
    $fields = [
        'uses_sub_groups'       => [
            'type'       => 'tinyint',
            'constraint' => '1',
        ],
        'is_sub_group' => [
            'type'       => 'int',
            'constraint' => '11',
        ],
    ];
    public function up()
    {
        $this->forge->addColumn('groups', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('groups', $fields);
    }
}
?>