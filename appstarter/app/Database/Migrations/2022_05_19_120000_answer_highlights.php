<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
class AddHighlightToAudit extends Migration
{
    public function up()
    {
        $fields = [
            'highlight_failures'       => [
                'type'       => 'tinyint',
                'constraint' => '1',
                'default'    => '0',
            ],
            'is_resubmission'       => [
                'type'       => 'tinyint',
                'constraint' => '1',
                'default'    => '0',
            ],
        ];
        $this->forge->addColumn('audits', $fields);
    }

    public function down()
    {
        $fields = [
            'highlight_failures',
            'is_resubmission',
        ];
        $this->forge->dropColumn('audits', $fields);
    }
}
?>