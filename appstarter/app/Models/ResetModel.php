<?php 
namespace App\Models;
use CodeIgniter\Model;

class ResetModel extends Model
{
    protected $table = 'password_reset_tokens';

    protected $primaryKey = 'id';
    
    protected $allowedFields = ['email','selector','token','expires'];
    
}

?>