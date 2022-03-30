<?php 
namespace App\Models;
use CodeIgniter\Model;

class TextModel extends Model
{
    protected $table = 'text';

    protected $primaryKey = 'id';
    
    protected $allowedFields = ['name','en','fr','de','es','it'];
    
}

?>