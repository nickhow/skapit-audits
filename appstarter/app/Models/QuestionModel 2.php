<?php 
namespace App\Models;
use CodeIgniter\Model;

class QuestionModel extends Model
{
    protected $table = 'questions';

    protected $primaryKey = 'id';
    
    protected $allowedFields = ['question_number','question', 'en','fr','es','de','it','hide_for_1','hide_for_2','hide_for_3','hide_for_4','hide_for_5','has_custom_answer'];
    
}

?>