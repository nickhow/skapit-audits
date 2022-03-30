<?php 
namespace App\Models;
use CodeIgniter\Model;

class GroupModel extends Model
{
    protected $table = 'groups';

    protected $primaryKey = 'id';
    
    protected $allowedFields = ['name','is_payable','payable_amount'];
    
}

?>