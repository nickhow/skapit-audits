<?php 
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'id';
    
    protected $allowedFields = ['name','username','password','is_admin', 'is_hotelcheck', 'group_id', 'account_id','created_date', ];
    
}

?>