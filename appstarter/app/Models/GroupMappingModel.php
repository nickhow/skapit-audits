<?php 
namespace App\Models;
use CodeIgniter\Model;

class GroupMappingModel extends Model
{
    protected $table = 'group_mapping';

    protected $primaryKey = 'id';
    
    protected $allowedFields = ['group_id','sub_group_id'];
    
}

?>