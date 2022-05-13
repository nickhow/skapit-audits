<?php
namespace App\Scripts;
use App\Models\ResponseModel;
class UpdateResponses extends App\Controllers\Controller
{
    function main(){
        $db = db_connect();

        //get the unique IDs from the responses table
        $ids = $db->query("SELECT audit_id FROM `audits` WHERE `status` IN ('reviewed','reviewing','complete')")->getResult();

        //generate the basic response to satisfy the new question
        $responseModel = new ResponseModel();
        foreach($ids as $id){
            $response = [
                'audit_id' => $id,
                'question_id' => 129,  
                'answer_id' => 10002,
                'suggested_score_ba' => 0,
                'suggested_score_abta' => 0,
                'custom_answer' => "",
            ];

            $responseModel->insert($response);
        }
    }
}
?>