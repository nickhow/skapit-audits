<?php 
namespace App\Models;
use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class ContactModel extends Model
{
    protected $table = 'contacts';

    protected $primaryKey = 'id';
    
    protected $allowedFields = ['audit_id','date','comment', 'account_id', 'is_admin'];
    
    function addComment($audit_id, $comment, $account_id, $is_admin){
        $data = [
            'audit_id' =>  $audit_id,
            'date' => Time::now('Europe/London', 'en_GB'),
            'comment' => $comment,
            'account_id' => $account_id,
            'is_admin' => $is_admin,
        ];
        $this->insert($data);
    }
}
?>