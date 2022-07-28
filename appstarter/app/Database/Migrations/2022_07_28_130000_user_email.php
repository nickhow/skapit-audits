<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
class AddEmailToUser extends Migration
{
    public function up()
    {
        $fields = [
            'user_email'       => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $fields = [
            'user_email',
        ];
        $this->forge->dropColumn('users', $fields);
    }
}
?>