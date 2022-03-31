<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGroupMapping extends Migration
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
            'group_id' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'sub_group_id'       => [
                'type'       => 'int',
                'constraint' => '11',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('group_mapping');
    }

    public function down()
    {
        $this->forge->dropTable('group_mapping');
    }
}

class UpdateGroup extends Migration
{
    public function up()
    {
        $fields ([
            'uses_sub_groups'       => [
                'type'       => 'tinyint',
                'constraint' => '1',
            ],
            'is_sub_group' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
        ]);
        $this->forge->add_column('groups', $fields);
    }

    public function down()
    {
        $this->forge->add_column('groups', $fields);
    }
}