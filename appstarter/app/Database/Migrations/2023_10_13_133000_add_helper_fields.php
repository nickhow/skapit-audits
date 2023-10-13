<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHelperFields extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'has_helper' => [
                'type'       => 'tinyint',
                'constraint' => '1',
            ],
            'helper_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'comment'    => 'URL for helper image or video',
            ],
        ]);
        $this->forge->addColumn('questions', $fields);
    }

    public function down()
    {
        $fields = [
            'has_helper',
            'helper_url',
        ];
        $this->forge->dropColumn('questions', $fields)
    }
}
?>