<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAccounts extends Migration
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
            'name'       => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
            ],
            'group_id' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'is_group_manager' => [
                'type'       => 'tiny_int',
                'constraint' => '1',
            ],
            'email'       => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
            ],
            'phone'       => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'accommodation_name'       => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'resort'       => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
            ],
            'country'       => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
            ],
            'notes'       => [
                'type'       => 'VARCHAR',
                'constraint' => '2000',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('accounts');
    }

    public function down()
    {
        $this->forge->dropTable('accounts');
    }
}

class AddAccountAudits extends Migration
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
            'account_id' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'audit_id'       => [
                'type'       => 'VARCHAR',
                'constraint' => '32',
            ],
            'group_id' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('account_audits');
    }

    public function down()
    {
        $this->forge->dropTable('account_audits');
    }
}

class AddAnswers extends Migration
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
            'question_id' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'answer'       => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'score_ba' => [
                'type'       => 'int',
                'constraint' => '11',
                'default' => null,
            ],
            'score_abta' => [
                'type'       => 'int',
                'constraint' => '11',
                'default' => null,
            ],
            'en'       => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'fr'       => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'de'       => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'es'       => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'it'       => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'precedence' => [
                'type'       => 'int',
                'constraint' => '1',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('answers');
    }

    public function down()
    {
        $this->forge->dropTable('answers');
    }
}

class AddAudits extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '32',
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
                'default' => '0000-00-00 00:00:00',
            ],
            'waiver_signed' => [
                'type'       => 'tiny_int',
                'constraint' => '1',
            ],
            'waiver_signed_date'       => [
                'type'       => 'TIMESTAMP',
                'default' => '0000-00-00 00:00:00',
            ],
            'completed_date'       => [
                'type'       => 'TIMESTAMP',
                'default' => '0000-00-00 00:00:00',
            ],
            'audited_date'       => [
                'type'       => 'TIMESTAMP',
                'default' => '0000-00-00 00:00:00',
            ],
            'expiry_date_ba'       => [
                'type'       => 'TIMESTAMP',
                'default' => '0000-00-00 00:00:00',
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
                'default' => '0000-00-00 00:00:00',
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
        $this->forge->addUnique('id', true);
        $this->forge->createTable('audits');
    }

    public function down()
    {
        $this->forge->dropTable('audits');
    }
}

class AddContacts extends Migration
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
            'audit_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '32',
            ],
            'date'       => [
                'type'       => 'TIMESTAMP',
                'attributes' => 'on update CURRENT_TIMESTAMP'
                'default' => 'CURRENT_TIMESTAMP',
                'extra' => 'ON UPDATE CURRENT_TIMESTAMP',
            ],
            'account_id' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'is_admin' => [
                'type'       => 'tinyint',
                'constraint' => '1',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('contacts');
    }

    public function down()
    {
        $this->forge->dropTable('contacts');
    }
}

class AddEmails extends Migration
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
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'language' => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
            ],
            'html' => [
                'type'       => 'VARCHAR',
                'constraint' => '2000',
            ],
            'text' => [
                'type'       => 'VARCHAR',
                'constraint' => '2000',
            ],
            'subject' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('emails');
    }

    public function down()
    {
        $this->forge->dropTable('emails');
    }
}

class AddGroups extends Migration
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
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'is_payable' => [
                'type'       => 'tinyint',
                'constraint' => '4',
            ],
            'payable_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('groups');
    }

    public function down()
    {
        $this->forge->dropTable('groups');
    }
}

class AddQuestions extends Migration
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
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'question_number' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'question' => [
                'type'       => 'VARCHAR',
                'constraint' => '800',
            ],
            'en' => [
                'type'       => 'VARCHAR',
                'constraint' => '800',
            ],
            'fr' => [
                'type'       => 'VARCHAR',
                'constraint' => '800',
            ],
            'de' => [
                'type'       => 'VARCHAR',
                'constraint' => '800',
            ],
            'es' => [
                'type'       => 'VARCHAR',
                'constraint' => '800',
            ],
            'it' => [
                'type'       => 'VARCHAR',
                'constraint' => '800',
            ],
            'hide_for_1' => [
                'type'       => 'tinyint',
                'constraint' => '1',
                'default' => '0',
            ],
            'hide_for_2' => [
                'type'       => 'tinyint',
                'constraint' => '1',
                'default' => '0',
            ],
            'hide_for_3' => [
                'type'       => 'tinyint',
                'constraint' => '1',
                'default' => '0',
            ],
            'hide_for_4' => [
                'type'       => 'tinyint',
                'constraint' => '1',
                'default' => '0',
            ],
            'hide_for_5' => [
                'type'       => 'tinyint',
                'constraint' => '1',
                'default' => '0',
            ],
            'has_custom_answer' => [
                'type'       => 'tinyint',
                'constraint' => '1',
                'default' => '0',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('questions');
    }

    public function down()
    {
        $this->forge->dropTable('questions');
    }
}

class AddResponses extends Migration
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
            'audit_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '32',
            ],
            'question_id' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'answer_id' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'suggested_score_ba' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'suggested_score_abta' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'score_ba' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'score_abta' => [
                'type'       => 'int',
                'constraint' => '11',
            ],
            'comment' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
            ],
            'custom_answer' => [
                'type'       => 'VARCHAR',
                'constraint' => '2000',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('responses');
    }

    public function down()
    {
        $this->forge->dropTable('responses');
    }
}

class AddText extends Migration
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
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'en' => [
                'type'       => 'text',
            ],
            'fr' => [
                'type'       => 'text',
            ],
            'de' => [
                'type'       => 'text',
            ],
            'es' => [
                'type'       => 'text',
            ],
            'it' => [
                'type'       => 'text',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('text');
    }

    public function down()
    {
        $this->forge->dropTable('text');
    }
}

class AddUploads extends Migration
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
            'audit_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '32',
            ],
            'file_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'file_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'original_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('uploads');
    }

    public function down()
    {
        $this->forge->dropTable('uploads');
    }
}

class AddUsers extends Migration
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
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'is_admin' => [
                'type'       => 'tinyint',
                'constraint' => '1',
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
            ],
            'group_id' => [
                'type'       => 'int',
                'constraint' => '20',
            ],
            'is_hotelcheck' => [
                'type'       => 'tinyint',
                'constraint' => '4',
            ],
            'account_id' => [
                'type'       => 'int',
                'constraint' => '1',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}

?>