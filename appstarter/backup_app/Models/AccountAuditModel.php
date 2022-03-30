<?php 
namespace App\Models;
use CodeIgniter\Model;

class AccountAuditModel extends Model
{
    protected $table = 'account_audits';

    protected $primaryKey = 'id';
    
    protected $allowedFields = ['account_id','audit_id','group_id'];
    
}

?>