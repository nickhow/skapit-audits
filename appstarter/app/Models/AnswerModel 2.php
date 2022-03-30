<?php 
namespace App\Models;
use CodeIgniter\Model;

class AnswerModel extends Model
{
    protected $table = 'answers';

    protected $primaryKey = 'id';
    
    protected $allowedFields = ['question_id', 'answer', 'score_ba', 'score_abta', 'en', 'fr','de','es','it','precedence' ];

    
}



?>