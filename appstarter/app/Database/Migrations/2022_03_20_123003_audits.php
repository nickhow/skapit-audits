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
            'sent_date timestamp default current_timestamp ',
            'last_updated timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'waiver_signed' => [
                'type'       => 'tinyint',
                'constraint' => '1',
            ],
            'waiver_signed_date timestamp NOT NULL DEFAULT "0000-00-00 00:00:00" COMMENT "date the waiver was signed"',
            'completed_date timestamp NOT NULL DEFAULT "0000-00-00 00:00:00" COMMENT "date the form was submitted"',
            'audited_date timestamp NOT NULL DEFAULT "0000-00-00 00:00:00" COMMENT "date the form was scored"',
            'expiry_date_ba timestamp NOT NULL DEFAULT "0000-00-00 00:00:00" COMMENT "date the result expires"',
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '300',
                'comment'    => 'form completion status',
            ],
            'result_ba' => [
                'type'       => 'VARCHAR',
                'constraint' => '300',
            ],
            'comment' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'comment'    => 'overall comment',
            ],
            'total_score_ba' => [
                'type'       => 'int',
                'constraint' => '11',
                'comment'    => 'HC score for BA',
            ],
            'total_score_abta' => [
                'type'       => 'int',
                'constraint' => '11',
                'comment'    => 'HC score for ABTA',
            ],
            'language' => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
                'default'    => 'en',
            ],
            'created_date timestamp default current_timestamp',
            'next_chase' => [
                'type'       => 'int',
                'constraint' => '1',
                'default'    => '1',
            ],
            'paid' => [
                'type'       => 'tinyint',
                'constraint' => '1',
            ],
            'result_abta' => [
                'type'       => 'VARCHAR',
                'constraint' => '300',
            ],
            'expiry_date_abta timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
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