<?php 
namespace App\Models;
use CodeIgniter\Model;
use App\Models\GroupMappingModel;

class GroupModel extends Model
{
    protected $table = 'groups';

    protected $primaryKey = 'id';
    
    protected $allowedFields = ['name','is_payable','payable_amount','uses_sub_groups', 'is_sub_group','created_date',];
    
    function getAllGroups(){
        $is_admin = session()->get('is_admin');
        $is_parent = session()->get('enable_groups');
        $groupMappingModel = New GroupMappingModel();
        if($is_admin){
           return $this->findAll();
        }
        if($is_parent){            
            $related_groups = $groupMappingModel->getAllRelatedGroups(session()->get('group_id'));
            $groups = [];
            foreach($related_groups as $group){
                $groups+= $this->where('id',$group['sub_group_id'])->first();
            }
            return $groups;
        }
        return ('no groups to return');
    }
}

?>