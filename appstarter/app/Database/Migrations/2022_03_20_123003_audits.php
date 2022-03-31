<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAudits extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '32',
                'unique'         => true,
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => '32',
            ],
            'sent_date'       => [
                'type'       => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'last_updated'       => [
                'type'       => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'sent_date'       => [
                'type'       => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
            'waiver_signed' => [
                'type'       => 'tiny_int',
                'constraint' => '1',
            ],
            'waiver_signed_date'       => [
                'type'       => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
            'completed_date'       => [
                'type'       => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
            'audited_date'       => [
                'type'       => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
            'expiry_date_ba'       => [
                'type'       => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '300',
            ],
            'result_ba' => [
                'type'       => 'VARCHAR',
                'constraint' => '300',
            ],
            'comment' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
            ],
            'total_score_ba' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'total_score_abta' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'language' => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
            ],
            'created_date'       => [
                'type'       => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'next_chase' => [
                'type'       => 'int',
                'constraint' => '1',
            ],
            'paid' => [
                'type'       => 'tinyint',
                'constraint' => '1',
            ],
            'result_abta' => [
                'type'       => 'VARCHAR',
                'constraint' => '300',
            ],
            'expiry_date_abta'       => [
                'type'       => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
            'waiver_extra_info_included' => [
                'type'       => 'tinyint',
                'constraint' => '1',
            ],
            'waiver_extra_info' => [
                'type'       => 'VARCHAR',
                'constraint' => '2000',
            ],
            'waiver_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'waiver_job_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'waiver_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'is_payable' => [
                'type'       => 'tinyint',
                'constraint' => '4',
            ],
            'is_paid' => [
                'type'       => 'tinyint',
                'constraint' => '4',
            ],
            'payment_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'payable_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('audits');
    }

    public function down()
    {
        $this->forge->dropTable('audits');
    }
}

?>