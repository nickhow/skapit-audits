<?php 
namespace App\Models;
use CodeIgniter\Model;

class AuditModel extends Model
{
    protected $table = 'audits';

    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'id', 'type','waiver_signed','
        waiver_signed_date','comment',
        'total_score_ba','total_score_abta',
        'status','result_ba','result_abta',
        'sent_date','last_updated','completed_date',
        'audited_date','expiry_date_ba','expiry_date_abta',
        'language', 'created_date','next_chase','paid',
        'waiver_extra_info_included', 'waiver_extra_info', 'waiver_name', 'waiver_job_title','waiver_email',
        'is_payable','is_paid', 'payment_id', 'payable_amount','added_to_salesforce','highlight_failures'
        ];
    
    
    
    public function generateID($test = null){
        helper('text');
        $goodKey = false;
        $keyToCheck='';
        
        while(!$goodKey){
            $keyToCheck = random_string('alnum',32);
            $goodKey = $this->checkID($keyToCheck);
        }
        return $keyToCheck;
    }
    
    function checkID($checkid){
        $db = db_connect();
        $query = $db->query("SELECT * FROM audits WHERE id = '".$checkid."'");

        if($query) {
            if($query->getNumRows() > 0){ 
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
    
    
    
}

?>