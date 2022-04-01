<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
class AddCreatedToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'created_date timestamp default current_timestamp',
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $fields = [
            'created_date',
        ];
        $this->forge->dropColumn('users', $fields);
    }
}
?>