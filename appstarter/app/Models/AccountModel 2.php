<?php 
namespace App\Models;
use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table = 'accounts';

    protected $primaryKey = 'id';
    
    protected $allowedFields = ['name', 'email', 'group_id','is_group_manager', 'phone', 'accommodation_name', 'resort', 'country', 'notes' ];
    
    //Return data with groups table joined to show group name rather than group_id
    public function getAccountsWithGroup(){
    //run a query to get joined data
    $query = $this->db->query("SELECT $this->table.*, groups.name AS group_name
                               FROM $this->table
                               LEFT JOIN groups
                               ON ($this->table.group_id = groups.id)");
    return $query->getResultArray();
        
    }
    
     public function getAccountsWithGroupById($id){
        $query = $this->db->query("SELECT $this->table.*, groups.name AS group_name
                               FROM $this->table
                               LEFT JOIN groups
                               ON ($this->table.group_id = groups.id)
                               WHERE group_id = '".$id."'");
        return $query->getResultArray();
        
    }
    
}



?>