<?php 
namespace App\Models;
use CodeIgniter\Model;

class ResponseModel extends Model
{
    protected $table = 'responses';

    protected $primaryKey = 'id';
    
    protected $allowedFields = ['audit_id', 'question_id', 'answer_id', 'suggested_score_ba','suggested_score_abta','suggested_score_ejh','score_ba','score_abta','score_ejh','comment', 'custom_answer' ];

}

?>