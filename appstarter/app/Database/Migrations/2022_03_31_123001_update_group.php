<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
class UpdateGroup extends Migration
{
    public function up()
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
        $this->forge->add_column('groups', $fields);
    }

    public function down()
    {
        $this->forge->add_column('groups', $fields);
    }
}
?>