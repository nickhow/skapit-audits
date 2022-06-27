<?php 
namespace App\Controllers;
use App\Models\AuditModel;
use App\Models\AccountAuditModel;
use App\Models\AccountModel;
use App\Models\ResponseModel;
use App\Models\AnswerModel;
use App\Models\QuestionModel;
use App\Models\UploadModel;
use App\Models\EmailModel;
use App\Models\TextModel;
use App\Models\ContactModel;
use App\Models\UserModel;
use App\Models\GroupModel;
use App\Models\GroupMappingModel;

use Mpdf\Mpdf;

use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

helper('filesystem');


error_reporting(E_ALL);
ini_set("display_errors", 1);


class AuditCrud extends Controller
{
    function getLastNDays($days, $format = 'Y-m-d'){
        $m = date("m"); $de= date("d"); $y= date("Y");
        $dateArray = array();
        for($i=0; $i<=$days-1; $i++){
            $dateArray[] = date($format, mktime(0,0,0,$m,($de-$i),$y)); 
        }
        return array_reverse($dateArray);
    }

    public function generateCharts($chase_time){
        $db = db_connect();
        $range = $this->getLastNDays(7);
        foreach($range as $date){
            $chart_data['label'][] = $date;
            $sent = $db->query("SELECT COUNT(id) AS count FROM audits WHERE date(sent_date) = '".$date."' AND status = 'sent'")->getResult();
            $chart_data['sent'][] = (int)$sent[0]->count;
            $progress = $db->query("SELECT COUNT(id) AS count FROM audits WHERE date(sent_date) = '".$date."' AND status = 'in progress'")->getResult();
            $chart_data['progress'][] = (int)$progress[0]->count;
            $complete = $db->query("SELECT COUNT(id) AS count FROM audits WHERE date(sent_date) = '".$date."' AND status = 'complete'")->getResult();
            $chart_data['complete'][] = (int)$complete[0]->count;
        }
        $chart_data['chart_data'] = json_encode($chart_data);
        
        $today = date('Y-m-d');
        $chart_data['chase_time'] = $chase_time;
        
        $chase = $db->query("SELECT audits.*, accounts.`name`, accounts.`accommodation_name`, accounts.`email` 
                            FROM audits
                            INNER JOIN account_audits ON account_audits.`audit_id` = audits.`id`
                            INNER JOIN accounts ON account_audits.`account_id` = accounts.`id`
                            WHERE (DATEDIFF('".$today."', `sent_date`) > ".$chase_time.") AND status = 'sent'
                            ORDER BY sent_date DESC")->getResult();
                            
        $chart_data['chase'] = $chase;

        echo view('dashboard_chart', $chart_data);
    }
    
    
    public function chaseList($chase_time = 7, $response = 'widget'){
        $db = db_connect();
        
        $today = date('Y-m-d');
        $chart_data['chase_time'] = $chase_time;
        
        $chase = $db->query("SELECT audits.*, accounts.`name`, accounts.`accommodation_name`, accounts.`email` 
                            FROM audits
                            INNER JOIN account_audits ON account_audits.`audit_id` = audits.`id`
                            INNER JOIN accounts ON account_audits.`account_id` = accounts.`id`
                            WHERE (DATEDIFF('".$today."', `sent_date`) > ".$chase_time.") AND status = 'sent'
                            ORDER BY sent_date DESC")->getResult();
                            
        $chart_data['chase'] = $chase;
        if($response == 'widget'){ 
            echo view('dashboard_chase', $chart_data);
        }
        if($response == 'full'){ 
            echo view('templates/header');
            echo view('chase', $chart_data);
            echo view('templates/footer');
        }
      
    }
    
    public function auditStats(){
        $db = db_connect();
        
        $today = date('Y-m-d');

        //total completed last 7 days
        //total reviewed this week
        //passes this week
        //total reviewed + passed, not expired
        //total expiring within 30 days
        
        $chart_data['new_completed'] = $db->query("SELECT COUNT(`id`) AS count from audits WHERE status = 'complete' AND (DATEDIFF('".$today."', `completed_date`) < 7)")->getRow();
        $chart_data['new_reviewed'] = $db->query("SELECT COUNT(`id`) AS count from audits WHERE status = 'reviewed' AND (DATEDIFF('".$today."', `audited_date`) < 7)")->getRow();
        $chart_data['new_pass'] = $db->query("SELECT COUNT(`id`) AS count from audits WHERE status = 'reviewed' AND (result_ba='suitable' OR result_abta='suitable') AND (DATEDIFF('".$today."', `audited_date`) < 7)")->getRow();
        $chart_data['total_reviewed'] = $db->query("SELECT COUNT(`id`) AS count from audits WHERE status = 'reviewed' AND (result_ba='suitable' OR result_abta='suitable') AND (expiry_date_ba > '".$today."' OR expiry_date_ba > '".$today."')" )->getRow();
        $chart_data['expire_soon'] = $db->query("SELECT COUNT(`id`) AS count from audits WHERE status = 'reviewed' AND result_ba='suitable' AND result_abta='suitable' AND ( (DATEDIFF('".$today."', `expiry_date_ba`) > -30) OR (DATEDIFF('".$today."', `expiry_date_abta`) > -30) ) " )->getRow();

       echo view('dashboard_stats', $chart_data);
    }
    
    // show audits list
    public function index($chase_time = 7){
       
        $db = db_connect();
        $session = session();
        $admin = $session->get('is_admin');
        $hotelcheck = $session->get('is_hotelcheck');
        $account_id = $session->get('account_id');
        $group_id = $session->get('group_id');
        $loggedin = $session->get('isLoggedIn');
        if(!$loggedin){
            return $this->response->redirect(site_url('/signin'));
        }
        
        $data['audits'] = [];
        
        if($admin){
            $sql = "
                SELECT audits.id AS 'id', audits.type AS 'type', audits.status AS 'status', audits.result_ba AS 'result_ba', audits.result_abta AS 'result_abta', accounts.id AS 'account_id', accounts.accommodation_name AS 'accommodation_name', audits.last_updated AS 'last_updated', audits.sent_date AS 'sent_date', audits.created_date AS 'created_date', audits.added_to_salesforce, users.alias AS 'alias'
                FROM audits
                INNER JOIN account_audits on account_audits.audit_id = audits.id
                INNER JOIN accounts on account_audits.account_id = accounts.id
                LEFT JOIN users on audits.audit_owner_id = users.id
            ";
        
        $data['audits'] = $db->query($sql)->getResultArray();

        } elseif($hotelcheck) {
            //completed ones
             $sql = "
                SELECT audits.id AS 'id', audits.type AS 'type', accounts.accommodation_name AS 'accommodation_name', audits.completed_date AS 'completed_date'
                FROM audits
                INNER JOIN account_audits on account_audits.audit_id = audits.id
                INNER JOIN accounts on account_audits.account_id = accounts.id
                WHERE status IN ('complete', 'reviewing')
                ORDER BY completed_date
            ";
        
            $data['audits'] = $db->query($sql)->getResultArray();
            
            //reviewed ones
            $sql = "
                    SELECT audits.id AS 'id', audits.type AS 'type', accounts.accommodation_name AS 'accommodation_name', audits.audited_date AS 'audited_date', audits.result_ba AS 'result_ba', audits.result_abta AS 'result_abta', audits.paid AS 'paid'
                    FROM audits
                    INNER JOIN account_audits on account_audits.audit_id = audits.id
                    INNER JOIN accounts on account_audits.account_id = accounts.id
                    WHERE status = 'reviewed'
                    ORDER BY audited_date
                ";
            
            $data['reviewed_audits'] = $db->query($sql)->getResultArray();
            
            $sql = "
                SELECT audits.id, audits.status, audits.sent_date, accounts.accommodation_name, accounts.resort, accounts.country 
                FROM audits
                INNER JOIN account_audits ON audits.id = account_audits.audit_id
                INNER JOIN accounts ON accounts.id = account_audits.account_id
                WHERE audits.status = 'sent'
            ";
        
            $data['with_hotel']['sent'] = $db->query($sql)->getResultArray();
            $sql = "
                SELECT audits.id, audits.status, audits.sent_date, accounts.accommodation_name, accounts.resort, accounts.country 
                FROM audits
                INNER JOIN account_audits ON audits.id = account_audits.audit_id
                INNER JOIN accounts ON accounts.id = account_audits.account_id
                WHERE audits.status = 'open'
            ";
        
            $data['with_hotel']['open'] = $db->query($sql)->getResultArray();
            $sql = "
                SELECT audits.id, audits.status, audits.sent_date, accounts.accommodation_name, accounts.resort, accounts.country 
                FROM audits
                INNER JOIN account_audits ON audits.id = account_audits.audit_id
                INNER JOIN accounts ON accounts.id = account_audits.account_id
                WHERE audits.status = 'in progress'
            ";
        
            $data['with_hotel']['progress'] = $db->query($sql)->getResultArray();
        
        } elseif ($group_id !== '0') {
            
            if(session()->get('enable_groups')){
                $sql = "
                    SELECT audits.id AS 'id', audits.type AS 'type', audits.status AS 'status', audits.result_ba AS 'result_ba', audits.result_abta AS 'result_abta', accounts.id AS 'account_id', accounts.accommodation_name AS 'accommodation_name', audits.last_updated AS 'last_updated', audits.sent_date AS 'sent_date', audits.created_date AS 'created_date'
                    FROM audits
                    INNER JOIN account_audits on account_audits.audit_id = audits.id
                    INNER JOIN accounts on account_audits.account_id = accounts.id
                    WHERE account_audits.group_id IN (SELECT sub_group_id FROM group_mapping WHERE group_id = '".$group_id."')
                ";
            } else {
                $sql = "
                    SELECT audits.id AS 'id', audits.type AS 'type', audits.status AS 'status', audits.result_ba AS 'result_ba', audits.result_abta AS 'result_abta', accounts.id AS 'account_id', accounts.accommodation_name AS 'accommodation_name', audits.last_updated AS 'last_updated', audits.sent_date AS 'sent_date', audits.created_date AS 'created_date'
                    FROM audits
                    INNER JOIN account_audits on account_audits.audit_id = audits.id
                    INNER JOIN accounts on account_audits.account_id = accounts.id
                    WHERE account_audits.group_id = '".$group_id."'
                ";
            }
        
        $data['audits'] = $db->query($sql)->getResultArray();
        } else { // should be an account user
            $sql = "
                SELECT audits.id AS 'id', audits.type AS 'type', audits.status AS 'status', audits.result_ba AS 'result_ba', audits.result_abta AS 'result_abta', accounts.id AS 'account_id', accounts.accommodation_name AS 'accommodation_name', audits.last_updated AS 'last_updated', audits.sent_date AS 'sent_date', audits.created_date AS 'created_date'
                FROM audits
                INNER JOIN account_audits on account_audits.audit_id = audits.id
                INNER JOIN accounts on account_audits.account_id = accounts.id
                WHERE account_audits.account_id = '".$account_id."'
            ";
        
        $data['audits'] = $db->query($sql)->getResultArray();
        }
       
        if ($admin) {
            echo view('templates/header');
            $this->generateCharts($chase_time);
            $this->auditStats();
            echo view('view_audits', $data);
            
        } elseif ($hotelcheck) {
            echo view('templates/header-hc');
            echo view('view_audits_hc', $data);
            
        } elseif ($group_id !== '0') {
            echo view('templates/header-group');
            echo view('view_audits_group', $data);
        } else {
            echo view('templates/header-hotel');
            echo view('view_audits_account', $data);
        }
        echo view('templates/footer');
    }
    
    public function new_reviews(){
        $db = db_connect();
        $session = session();
        $admin = $session->get('is_admin');
        $group_id = $session->get('group_id');
        $today = date('Y-m-d');
        $sql = "
                SELECT audits.id AS 'id', audits.type AS 'type', audits.result_ba AS 'result_ba', audits.result_abta AS 'result_abta', accounts.accommodation_name AS 'accommodation_name', audits.audited_date AS 'audited_date'
                FROM audits
                INNER JOIN account_audits on account_audits.audit_id = audits.id
                INNER JOIN accounts on account_audits.account_id = accounts.id
                WHERE (DATEDIFF('".$today."', `audited_date`) <7)
        ";
        
        if(!$admin){
            $sql.="AND account_audits.group_id = '".$group_id."'";
            echo view('templates/header-group');
        } else {
            echo view('templates/header');
        }
        
        $data['audits'] = $db->query($sql)->getResultArray();

        echo view('new_reviews',$data);
        echo view('templates/footer');
    }
    
    public function expiring(){
        $db = db_connect();
        $session = session();
        $admin = $session->get('is_admin');
        $group_id = $session->get('group_id');
        $today = date('Y-m-d');
        $sql = "
                SELECT audits.id AS 'id', audits.type AS 'type', audits.result_ba AS 'result_ba', audits.result_abta AS 'result_abta', accounts.accommodation_name AS 'accommodation_name', audits.expiry_date_ba, audits.expiry_date_abta
                FROM audits
                INNER JOIN account_audits on account_audits.audit_id = audits.id
                INNER JOIN accounts on account_audits.account_id = accounts.id
                WHERE status = 'reviewed'
                AND result_ba='suitable' 
                AND result_abta='suitable'
                AND ( (DATEDIFF('".$today."', `expiry_date_ba`) > -30) OR (DATEDIFF('".$today."', `expiry_date_abta`) > -30) )
        ";
        
        if(!$admin){
            $sql.="AND account_audits.group_id = '".$group_id."'";
            echo view('templates/header-group');
        } else {
            echo view('templates/header');
        }
        
        $data['audits'] = $db->query($sql)->getResultArray();

        echo view('expires_soon',$data);
        echo view('templates/footer');
    }
    
    public function unpaid(){
        $db = db_connect();
        $sql = "
                SELECT audits.id AS 'id', audits.type AS 'type', audits.result_ba AS 'result_ba', audits.result_abta AS 'result_abta', accounts.accommodation_name AS 'accommodation_name', audits.audited_date AS 'audited_date', audits.paid AS 'paid'
                FROM audits
                INNER JOIN account_audits on account_audits.audit_id = audits.id
                INNER JOIN accounts on account_audits.account_id = accounts.id
                WHERE audits.status = 'reviewed' AND  audits.paid = 0
        ";
        
        $data['audits'] = $db->query($sql)->getResultArray();

        echo view('templates/header');
        echo view('unpaid',$data);
        echo view('templates/footer');
    }

    // add audit form
    public function create(){
        $accountModel = new AccountModel();
        $groupMappingModel = new GroupMappingModel();
        $session = session();
        $admin = $session->get('is_admin');
        $group = $session->get('group_id');
        
        if($admin){ //admin - get all accounts
            $data['accounts'] = $accountModel->orderBy('id', 'DESC')->findAll();
            echo view('templates/header');
        } else { //otherwise get ones for this group
        
            if(session()->get('enable_groups')){
                $subGroups = $groupMappingModel->where('group_id',session()->get('group_id'))->findColumn('sub_group_id');
                $data['accounts'] = $accountModel->whereIn('group_id',$subGroups)->orderBy('id','DESC')->findAll();
            } else {
                $data['accounts'] = $accountModel->where('group_id',$group)->orderBy('id', 'DESC')->findAll();
            }
             echo view('templates/header-group');
        }
        
       
        echo view('add_audit',$data);
        echo view('templates/footer');
    }
 
    // Create new audit
    public function store() {
        $auditModel = new AuditModel();
        $accountAuditModel = new AccountAuditModel();
        $accountModel = new AccountModel();
        $groupModel = new GroupModel();
        $groupMappingModel = new GroupMappingModel();
        $session = session();
        
        $id = $auditModel->generateID();
        $account_id = $this->request->getVar('account');      
        
        if($account_id == ""){
            $session->setFlashdata('msg', "Can't send an audit without a property, please select or create a property first. </br> <a href=".site_url('/account/new').">Create New Property</a>");
            return $this->response->redirect(site_url('/audit/new'));
        }

        $data['account'] = $accountModel->where('id', $account_id)->first();
        
        $isPayable;
        $payableAmount = '0.00';
        if(!$session->get('is_admin')){ //if it's not admin, it's based on the group.
            $group = $groupModel->where('id',$data['account']['group_id'])->first();
            
            if($group['is_sub_group']){ //we need to look at the parent for the charge settings
                $mapping = $groupMappingModel->where('sub_group_id',$group['id'])->first();
                $group = $groupModel->where('id',$mapping['group_id'])->first();
            }
            
            $isPayable = $group['is_payable'];
            $payableAmount = $group['payable_amount'];
            
        } else { //if it is admin it is set per audit.
            $isPayable = $this->request->getVar('is_payable');
            if($isPayable){
                $payableAmount = $this->request->getVar('payable_amount');
            }
        }

        
        $auditData = [
            'id' => $id,
            'language' => $this->request->getVar('language'),
            'sent_date' => Time::now('Europe/London', 'en_GB'),
            'created_date' => Time::now('Europe/London', 'en_GB'),
            'status' => 'sent',
            'is_payable' => $isPayable,
            'payable_amount' => $payableAmount,
            'audit_owner_id' => $session->id,
        ];

        $accountAuditData = [
            'audit_id' => $id,
            'account_id' => $account_id,
            'group_id' => $data['account']['group_id'],
        ];
        
        $auditModel->insert($auditData);
        $accountAuditModel->insert($accountAuditData);
        
        $intro = "";
        if($this->request->getVar('custom_intro')){
            $intro = $this->request->getVar('custom_intro_text');
        }

        $url =  site_url("/audit/".$id);
        $values = array($data['account']['name'], $url,$data['account']['accommodation_name'],$data['account']['resort'],$data['account']['country']);
        
        $emailModel = new EmailModel();
        $emailModel->sendNewAudit($auditData['language'],$data['account']['email'],$values,$intro);
        
        $session->setFlashdata('msg', 'Audit created and sent.');
        return $this->response->redirect(site_url('/audits'));
    }
    
    public function chaseEmail($audit_id = null){
        $auditModel = new AuditModel();
        $accountModel = new AccountModel();
        $accountAuditModel = new AccountAuditModel();
        
        //Get the information for the chase - audit and account info
        $audit = $auditModel->where('id',$audit_id)->first();
        $accountAudit = $accountAuditModel->where('audit_id',$audit_id)->first();
        $account = $accountModel->where('id',$accountAudit['account_id'])->first();
        
        //Get the email data out of that data
        $url =  site_url("/audit/".$audit_id);
        $values = array($account['name'], $url, $account['accommodation_name'], $account['resort']);
        
        //Send the email
        $emailModel = new EmailModel();
        $emailModel->sendChase($audit['language'],$account['email'],$audit['next_chase'],$values);
        
        //Update the records
        $new_chase = $audit['next_chase']; //set the next chase at the current next chase 
        if($audit['next_chase'] < 3){  // if less than 3 then we can increment, otherwise stay at 3 (we only have 3 chase emails)
             $new_chase += 1;
        }
       
        $data = [
            'sent_date' => Time::now('Europe/London', 'en_GB'),
            'next_chase' => $new_chase,
        ];
        $auditModel->update($audit_id, $data);
        
        $session = session();
        $session->setFlashdata('msg', 'Chase email has been sent.');
            
        return $this->response->redirect(site_url('/audits'));
    }
    
    //show audit for HC review
    public function hotelCheckAudit($id) {
        $auditModel = new AuditModel();
        $accountModel = new AccountModel();
        $responseModel = new ResponseModel();
        $accountAuditModel = new AccountAuditModel();
        $uploadModel = new UploadModel();
        $answerModel = new AnswerModel();
        $textModel = new TextModel();
        $contactModel = new ContactModel();
        
        $session = session();
        $admin = $session->get('is_admin');
        
        // get the property information
        $account_id = $accountAuditModel->where('audit_id',$id)->first();
        $data['property_obj'] =  $accountModel->where('id', $account_id['account_id'])->first();
        
        // get the audit information
        $data['audit_obj'] = $auditModel->where('id', $id)->first();
        
        //get the type description
        $type = "type_".$data['audit_obj']['type'];
        $textResult = $textModel->getWhere(['name' => $type])->getRow();
        $data['text'] = ["type" => $textResult->en];
        
        //get the responses
        $data['response_obj'] = $responseModel->select('responses.*, questions.question, questions.question_number, questions.id AS question_id, answers.en AS answer')->where('audit_id', $id)->join('questions','questions.id = responses.question_id', 'inner')->join('answers','answers.id = responses.answer_id', 'inner')->findALL();
        
        //get all possible answers
        $answer;
        foreach($data['response_obj'] as $response){
             $answer[$response['question_number']] = $answerModel->where('question_id',$response['question_id'])->orderBy('precedence ASC')->findAll();
        }
        $data['answer_obj'] = $answer;
        
        //get any files uploaded
        $data['file_obj'] = $uploadModel->where('audit_id', $id)->findAll();
        
        //get any contact comments
        if($admin){
            $data['contact'] = $contactModel->where('audit_id', $id)->findAll();
        } else {
            $data['contact'] = $contactModel->where('audit_id', $id)->where('is_admin', 0)->findAll();
        }
        
        
        if ($admin) {
            echo view('templates/header');
        } else {
            echo view('templates/header-hc');
        }
            
        echo view('hc-audit', $data); 
        echo view('templates/footer');
        
    }
    
    //Let Hotel Check update an answer
    public function updateAnswer(){
        $responseModel = new ResponseModel();
        $answerModel = new AnswerModel();
        
        if($this->request->getVar('response_id') == "" || $this->request->getVar('answer_id') == ""){
            return;
        }
        $response_id = $this->request->getVar('response_id');
        $answer_id = $this->request->getVar('answer_id');
        $scores = $answerModel->db->table('answers')->getWhere(['id'=>$answer_id])->getResult(); 
        $score_ba = 0;
        $score_abta = 0;
        foreach($scores as $score){
            $score_ba = $score->score_ba;
            $score_abta =  $score->score_abta;
        }

        //populate the reponse data
        $data = [
            'answer_id' =>  $answer_id,
            'suggested_score_ba' => $score_ba,
            'suggested_score_abta' => $score_abta,
            'score_ba' => $score_ba,
            'score_abta' => $score_abta,
            'custom_answer' => $this->request->getVar('custom_answer'),
        ];
        
        $responseModel->update($response_id, $data);
        $session = session();
        $session->setFlashdata('msg', 'Answer has been updated.');
        return;
    }
    
    // Insert Hotel Check data to audit
    public function hotelCheckStore() {
        $auditModel = new AuditModel();
        $responseModel = new ResponseModel();
        $accountAuditModel = new AccountAuditModel();
        $accountModel = new AccountModel();
        $uploadModel = new UploadModel();
        $session = session();
        
        //update audit data
        $audit_id = $this->request->getVar('id');
        $data = [
            'comment'  => $this->request->getVar('audit_comment'),
            'total_score_ba'  => $this->request->getVar('audit_score_ba'),
            'total_score_abta'  => $this->request->getVar('audit_score_abta'),
            'result_ba'  => $this->request->getVar('audit_result_ba'),
            'result_abta'  => $this->request->getVar('audit_result_abta'),            
            'expiry_date_ba'  => date('Y-m-d H:i:s', strtotime($this->request->getVar('expiry_date_ba'))) ,
            'expiry_date_abta'  => date('Y-m-d H:i:s', strtotime($this->request->getVar('expiry_date_abta'))) ,
        ];
        $auditModel->update($audit_id, $data);
        
        //update response data
        $responses_to_update = $responseModel->where('audit_id',$audit_id)->findAll();
        
        foreach($responses_to_update as $response){
            if($response['answer_id'] == "8888"){ 
                    //skip this one
                    continue;
            }
            if( !isset($this->request->getVar($response['id'])['score_ba']) || !isset($this->request->getVar($response['id'])['score_abta']) ){
                print_r($this->request->getVar($response['id']));
                print_r("I broke ... ");
                print_r($response);
                return;
            } else {
                
            $data = [
            'score_ba' => $this->request->getVar($response['id'])['score_ba'],
            'score_abta' => $this->request->getVar($response['id'])['score_abta'],
            'comment' => $this->request->getVar($response['id'])['comment'],
            ];
           $responseModel->update($response['id'], $data);
            }
        }
        
        $files = $this->request->getFiles('evidence')['evidence'];
        
        foreach($files as $file){
            if($file->isValid()){
                $uploadModel->uploadFile($file, $audit_id);
            }
        }
            
        if($this->request->getVar('save')){
            $data = [
                'status' => 'reviewing',
            ];
            $auditModel->update($audit_id, $data);
            
            $session->setFlashdata('msg', 'Audit saved.');
            return $this->response->redirect(site_url('/audit/'.$audit_id.'/review'));
        }
        
        if($this->request->getVar('complete')){
            $data = [
                'status' => 'reviewed',
                'audited_date' => Time::now('Europe/London', 'en_GB'),
            ];
            $auditModel->update($audit_id, $data);
            
            $audit = $auditModel->where('id',$audit_id)->first();
            $account_audit = $accountAuditModel->where('audit_id',$audit_id)->first();
            $account = $accountModel->where('id',$account_audit['account_id'])->first();
            
            //send email to fraser
            $emailaddresses = (getenv('skapit'));
                
            $url =  site_url('/audit/'.$audit_id);
            $values = array( $account['accommodation_name'], $audit['type'], $account['resort'],$audit['result_ba'],$audit['result_abta'], $url);
            
            //generates the PDF
            $this->salesforceResultPDF($audit_id); 

            //send the email to Fraser
            $emailModel = new EmailModel();
            $emailModel->sendReviewedAudit("en",$emailaddresses,$values,$audit_id);
            

            // now we send the email to the hotel 

            //get the correct email body
            $email_content;
            $account_url;
            if($account_audit['group_id'] == 0){
                //no group, offer an account.
                $email_content = "account";
                $account_url =  site_url('/audit/'.$audit_id.'/account');
            } else {
                //part of a group, advise the group checks the audit.
                $email_content = "group";
                $account_url =  site_url('/audit/'.$audit_id);
            }
            
            $account_values = array( $account['name'], $account_url);

            //send the email to the property account
            $emailModel->pdfEmail("en", $email_content, $account['email'],$account_values,$audit_id);
    
            $session->setFlashdata('msg', 'Audit review submitted.');

            //delete the file now it's been sent
            unlink($audit_id.".pdf");
                
            return $this->response->redirect(site_url('/audits'));
        }
    }
    
    // show single audit ->for editing as admin/manager
    public function editSingleAudit($id = null){
        $auditModel = new AuditModel();
        $accountAuditModel = new AccountAuditModel();
        $groupMappingModel = new GroupMappingModel();
        $session= session();
        
        if(!$session->get('is_admin')){
            $group = $session->get('group_id');
            $accountAudit = $accountAuditModel->where('audit_id',$id)->first();

            if($accountAudit['group_id'] !== $group){
                
                //not the direct owner, is it the group owner
                $subGroups = $groupMappingModel->where('group_id',$group)->findColumn('sub_group_id');
                
                $access = false;
                foreach($subGroups as $subGroup){
                    if($subGroup == $accountAudit['group_id']) { $access = true;}
                }
                
                if($access == false){
                    return $this->response->redirect(site_url('/audits'));
                }
            }
            
            
        }
        
        $data['audit_obj'] = $auditModel->where('id', $id)->first();
        $data['account_audit_objects'] = $accountAuditModel->orderBy('id', 'DESC')->findAll();
        $data['account_obj'] = $accountModel->where('id',$accountAuditModel->where('id', $id)->findColumn('account_id'))->first();
            echo view('templates/header');
            echo view('single-audit', $data); 
            echo view('templates/footer');
    }
        
    // show single audit -> for hotel completion
    public function singleAudit($id = null){
        $auditModel = new AuditModel();
        $accountModel = new AccountModel();
        $accountAuditModel = new AccountAuditModel();
        $responseModel = new ResponseModel();
        $uploadModel = new UploadModel();
        $textModel = new TextModel();
        $db = db_connect();
        
        $session = session();
        $admin = $session->get('is_admin');
        $group = $session->get('group_id');
        $user_account = $session->get('account_id');
        $data['audit_obj'] = $auditModel->where('id', $id)->first();
        $data['account_audit_objects'] = $accountAuditModel->orderBy('id', 'DESC')->findAll();
        
        $account = $accountAuditModel->where('audit_id',$id)->first();
        $data['account_obj'] = $accountModel->where('id', $account['account_id'])->first();
        
        $sql = "SELECT name, ". $data['audit_obj']['language'] ." AS 'text' FROM text ";
        $results = $db->query($sql)->getResult();
        
        $data['text'] = [];
        foreach($results as $result){
             $data['text'][$result->name] = $result->text;
        }
        
        //hotel view
        if ($admin) {
            echo view('templates/header');
        } elseif($group) {
            echo view('templates/header-group');
        } else {
            echo view('templates/header-hotel');
        }
        
        //If no waiver signed - show the waiver
        if(!$data['audit_obj']['waiver_signed']){
            echo view('waiver',$data);
        } 
        //If no type chosen - show the type selection
        elseif(!$data['audit_obj']['type']){
            echo view('type',$data);
        }
        //The form has been submitted, now needs payment
        elseif($data['audit_obj']['status'] == "pending_payment" && !$data['audit_obj']['is_paid']){
            echo view('checkout',$data);
        }
        
        else{
            //the form ...
            
            //if user is logged in - are they a group manager, and is this one of theirs?
            if($group){  
                if(!$group == $account['group_id']){
                    return $this->response->redirect(site_url('/audits/'));
                }
            }
            if($user_account){  
                if(!$user_account == $account['id']){
                    return $this->response->redirect(site_url('/audits/'));
                }
            }
            
            //check: you're not admin or a group manager and the form is locked...
            if(!($admin || $group || $user_account) && ($data['audit_obj']['status'] == "complete" || $data['audit_obj']['status'] == "reviewing" || $data['audit_obj']['status'] == "reviewed")){
            //You're not admin and it's been submitted -> show the lock page
            echo view('locked',$data);
                
            } else { //either you are admin/manager or the form is not submitted.
                
                //are you admin looking at a completed form ...
                if(($admin || $group || $user_account) && ($data['audit_obj']['status'] == "complete" || $data['audit_obj']['status'] == "reviewing" || $data['audit_obj']['status'] == "reviewed")){
                    //yes -> lock the inputs.
                    $session->setFlashdata('locked', true);
                }
                //you might be an admin, but the form is not submitted yet ... it's a free-for-all
                $query = $db->query("SELECT id, ".$data['audit_obj']['language']." AS 'question', hide_for_1, hide_for_2, hide_for_3, hide_for_4, hide_for_5, question_number, has_custom_answer FROM questions ORDER BY question_number ASC");
                $results = array();
            
                foreach ($query->getResultArray() as $row){
                    $row['answers'] = $db->query("SELECT id, question_id, ".$data['audit_obj']['language']." AS 'answer', answer AS 'en_ans'  FROM answers WHERE question_id = '".$row['id']."' ORDER BY precedence ASC")->getResultArray();
    /* or this? */  $row['response'] = $responseModel->where(['audit_id' => $id, 'question_id' => $row['id']])->first();
                    $results[] = $row;
                }
            
                $data['questions'] = $results;
            
                $data['file_obj'] = $uploadModel->where('audit_id',$id)->findAll();
            
                //Let's try stacking this all in a single form
                echo view('hotel-form',$data);
                
            }
                
        }        
        echo view('templates/footer');
    }

    // update audit data --> as an admin / manager
    public function edit(){  
        $auditModel = new AuditModel();
        $accountAuditModel = new AccountAuditModel();
        $groupMappingModel = new GroupMappingModel();
        $session = session();
        $id = $this->request->getVar('id');
        
        if(!$session->get('is_admin')){
            $group = $session->get('group_id');
            $accountAudit = $accountAuditModel->where('audit_id',$id)->first();
            if($accountAudit['group_id'] !== $group){
                
                //not the direct owner, is it the group owner
                $subGroups = $groupMappingModel->where('group_id',$group)->findColumn('sub_group_id');
                
                $access = false;
                foreach($subGroups as $subGroup){
                    if($subGroup == $accountAudit['group_id']) { $access = true;}
                }
                
                if($access == false){
                    return $this->response->redirect(site_url('/audits'));
                }
            }
        }
        
        $data = [
            'language' => $this->request->getVar('language'),
            'last_updated' => Time::now('Europe/London', 'en_GB'),
        ];
        
        //this is only available to an admain to edit
        if($session->get('is_admin')){
            $data += [
                'is_payable' => $this->request->getVar('is_payable'),
                'paid' => $this->request->getVar('paid'), // this is paid to hotelcheck
                'added_to_salesforce' => $this->request->getVar('added_to_salesforce'), 
            ];
            if($this->request->getVar('is_payable')){
                $data += [ 
                    'payable_amount' => $this->request->getVar('payable_amount'),
                ];
            } // for now we'll leave any old data here rather than resetting this to 0.
        }
         
        $auditModel->update($id, $data);
        $session->setFlashdata('msg', 'Audit updated.');
        return $this->response->redirect(site_url('/audits'));
    }
    
    public function markPaid($id = null){
        $auditModel = new AuditModel();
        $data = [
            'paid' => 1,
            'last_updated' => Time::now('Europe/London', 'en_GB'),
        ];
        $auditModel->update($id, $data);
    }
    
    // update audit data --> as a hotel
    public function save(){
        $db = db_connect();
        $auditModel = new AuditModel();
        $responseModel = new ResponseModel();
        $answerModel = new AnswerModel();
        $questionModel = new QuestionModel();
        $uploadModel = new UploadModel();
        $accountAuditModel = new AccountAuditModel();
        $accountModel = new AccountModel();
        $textModel = new TextModel();
        $session = session();
        $id = $this->request->getVar('id');
        
        $data;
        echo 'updating ... ';
        if($this->request->getVar('save') || $this->request->getVar('complete')) { //save or complete should save the whole form.
        
        $canCommit = true; //flag for completed form or not.
        
            //save --> reload form
            echo "save ... ";
                if($this->request->getVar('waiver') == "on"){
                    echo "waiver ... ";
                    $data = [
                        'status' => 'open',
                        'waiver_signed' => '1',
                        'waiver_signed_date' =>Time::now('Europe/London', 'en_GB'),
                        'last_updated' => Time::now('Europe/London', 'en_GB'),
                        'waiver_extra_info_included' => $this->request->getVar('waiver_extra_info_included'),
                        'waiver_extra_info' => $this->request->getVar('waiver_extra_info'),
                        'waiver_name' => $this->request->getVar('waiver_name'),
                        'waiver_job_title' => $this->request->getVar('waiver_job_title'),
                        'waiver_email' => $this->request->getVar('waiver_email'),
                    ];
                    
                    //reloads the form page - if it is complete it needs a new message -> goes to the locked for review screen, maybe soften that message. If it was saved it should alert to success / failure.
                } elseif ($this->request->getVar('type')){
                    $data = [
                        'type' => $this->request->getVar('type'),
                        'last_updated' => Time::now('Europe/London', 'en_GB'),
                    ];
                } else {
                    echo "form ... ";
                    $data = [
                        'status' => 'in progress',
                        'last_updated' => Time::now('Europe/London', 'en_GB'),
                    ];
                    
                   
                    $audit_id = $this->request->getVar('id');
                    
                    $audit = $auditModel->find($audit_id);
                   
                    //get the question IDs for all questions of this type ... 
                    //$questions = $questionModel->db->table('questions')->select('id')->getWhere(['type'=>$audit['type']]); //renamed from $type['type']
                   // $questions = $questionModel->db->table('questions')->select('id'); 
                   $questions = $questionModel->findAll();

                    foreach($questions as $question){ 
                        
                        $answer_id = $this->request->getVar($question['id']);
                        $custom_answer = "";
                        
                        if($question['has_custom_answer']){
                            $answer_id = "9999";
                            $custom_answer = $this->request->getVar($question['id']);
                            
                        } else {
                        
                            //if the answer was skipped because it was hidden, find the NA option and use that.
                            if(!$answer_id){
                                $answerRow = $answerModel->getWhere(['answer'=>'N/A','question_id' => $question['id']])->getResultArray();
                                foreach ($answerRow as $row) {
                                     $answer_id = $row['id'];
                                }
                            }
                            
                            elseif($answer_id == "ignore"){
                                $answer_id="8888";
                            }
                            
                            elseif($answer_id == "Unanswered"){
                                //skip doing anything - it's unanswered
                                $canCommit = false;
                            } //else {
                        }    
                            

                            $scores = $answerModel->db->table('answers')->getWhere(['id'=>$answer_id])->getResult(); 
                            $score_ba = 0;
                            $score_abta = 0;
                            foreach($scores as $score){
                                $score_ba = $score->score_ba;
                                $score_abta =  $score->score_abta;
                            }
                            
                            //populate the reponse data
                            $response = [
                                'audit_id' => $audit_id,
                                'question_id' => $question['id'],  
                                'answer_id' => $answer_id,
                                'suggested_score_ba' => $score_ba,
                                'suggested_score_abta' => $score_abta,
                                'custom_answer' => $custom_answer,
                            ];
                                
                            //check if the  question id x audit id combo has a response and either update or insert
                            $responseCheck = $db->query("SELECT id, answer_id FROM responses WHERE audit_id = '".$audit_id."' AND question_id = '".$question['id']."'");

                            if($responseCheck->getNumRows() > 0){
                                //does exist -> update

                                // if we're on a resubmission audit then we should clear the hotel check scores 
                                // this will remove the highlighting from the audit form view.
                                
                                $original_answer = $responseCheck->getResult();

                                if( $audit['highlight_failures'] && $original_answer[0]->answer_id != $answer_id){
                                   $response += [
                                        'score_ba' => null,
                                        'score_abta' => null,
                                    ];
                                }

                                $responseModel->update($responseCheck->getResult()[0]->id, $response);
                                
                            } else {
                                //does not exist -> insert
                                echo 'inserting ... ';
                                $responseModel->insert($response);
                                
                            }
                      //  }
                        
                    }
                    
                    //check for files to delete
                    
                    $file = $this->request->getFile('file_operating_licence');
                    if ( $file->isValid()) {
                        $uploadModel->uploadFile($file, $audit_id, 'file_operating_licence');
                    }

                    $file = $this->request->getFile('file_public_liability_insurance');
                    if ( $file->isValid()) {
                        $uploadModel->uploadFile($file, $audit_id, 'file_public_liability_insurance');
                    }                       
                    
                    $file = $this->request->getFile('file_fire_certificate');
                    if ( $file->isValid()) {
                        $uploadModel->uploadFile($file, $audit_id, 'file_fire_certificate');
                    }
                    
                    //reloads the form page - if it is complete it needs a new message -> goes to the locked for review screen, maybe soften that message. If it was saved it should alert to success / failure.
                    
                    $text = $textModel->limit(1)->getWhere(['name'=>'audit_saved']);
                    $lang = $audit['language'];
                    $flashData = [
                            'msg'  => $text->getRow()->$lang,
                            'style' => 'alert-success',
                    ];
                    $session->setFlashdata($flashData);
                }
        }
        
        if($this->request->getVar('complete')) { //complete button
            
            //check for unanswered questions ...
            if(!$canCommit){
                $text = $textModel->limit(1)->getWhere(['name'=>'audit_not_completed']);
                $lang = $audit['language'];
                $flashData = [
                    'msg'  => $text->getRow()->$lang,
                    'style' => 'alert-danger',
                    'failed_complete' => true,
                ];
                $session->setFlashdata($flashData);
                return $this->response->redirect(site_url('/audit/'.$id));
            }
            
            
            //complete --> submit for review, lock form
            echo "complete ... ";
            
            
            if($audit['is_payable']){
                $data = [
                    'status' => 'pending_payment',
                    'last_updated' => Time::now('Europe/London', 'en_GB'),
                ];
            } else { // an audit that is complete and did not need payment
                $data = [
                    'status' => 'complete',
                    'completed_date' => Time::now('Europe/London', 'en_GB'),
                    'last_updated' => Time::now('Europe/London', 'en_GB'),
                    'highlight_failures' => 0,
                ];
            
                $account_audit = $accountAuditModel->where('audit_id',$audit['id'])->first();
                $account = $accountModel->where('id',$account_audit['account_id'])->first();
    
                //send email to hotelcheck and fraser
                $emailaddresses = (getenv('hotelcheck'));
                
                //Email the account about the audit
                $url =  site_url("/audit/".$id."/review");
                $values = array( $account['accommodation_name'], $audit['type'], $account['resort'], $url);
                
                $emailModel = new EmailModel();
                $emailModel->sendCompletedAudit("en",$emailaddresses,$values);
                
                //reloads the form page - if it is complete it needs a new message -> goes to the locked for review screen, maybe soften that message. If it was saved it should alert to success / failure.
                $text = $textModel->limit(1)->getWhere(['name'=>'audit_completed']);
                $lang = $audit['language'];
                    $flashData = [
                        'msg'  => $text->getRow()->$lang,
                    'style' => 'alert-success',
                ];
                $session->setFlashdata($flashData);
            }
        }

        echo ' committing to db';
        $auditModel->update($id,$data);
        
        return $this->response->redirect(site_url('/audit/'.$id));

    }

    public function privacyPolicy($lang = 'en'){
        $session = session();
        $admin = $session->get('is_admin');
        $textModel = new TextModel();
        
        $data['title'] = $textModel->select("$lang AS title" )->where('name','privacy_policy_title')->first();
        $data['policy'] = $textModel->select("$lang AS policy" )->where('name','privacy_policy')->first();
        
        if ($admin) {
            echo view('templates/header');
        } else {
            echo view('templates/header-hotel');
        }
        
        echo view('privacy-policy',$data);
        
        echo view('templates/footer');
    }
    
 
    // delete audit
    public function delete($id = null){
        $auditModel = new AuditModel();
        $accountAuditModel = new AccountAuditModel();
        $responseModel = new ResponseModel();
        $session = session();
        
        if(!$session->get('is_admin')){
            $group = $session->get('group_id');
            $accountAudit = $accountAuditModel->where('audit_id',$id)->first();
            if($accountAudit['group_id'] !== $group){
                return $this->response->redirect(site_url('/audits'));
            }
        }
        
        //delete audit
        $data['audits'] = $auditModel->where('id', $id)->delete($id);
        
        //delete audit responses
        $data['responses'] = $responseModel->where('audit_id', $id)->delete();
        $session->setFlashdata('msg', 'Audit deleted.');
        return $this->response->redirect(site_url('/audits'));
    }    
    
    // CSV for Hotel Check
    public function generateCSV(){
        //build a csv of all completed, not reviewed audits.
        $db = db_connect();
        $sql = "SELECT 
                accounts.email AS 'Hotel Email',
                accounts.accommodation_name AS 'Hotel Name', 
                accounts.name AS 'Hotel Contact Person', 
                '' AS 'Client Hotel Code', 
                accounts.phone AS 'Phone', 
                accounts.country AS 'Country', 
                '' AS 'Region', 
                accounts.resort AS 'City', 
                'Yes' AS 'Audit with Grade', 
                'Yes' AS 'Audit results to be shared at audits bank', 
                audits.language AS 'Language'
                FROM accounts INNER JOIN account_audits ON account_audits.account_id = accounts.id
                INNER JOIN audits ON audits.id = account_audits.audit_id
                WHERE audits.status = 'reviewed'
                AND audits.paid = 0" ;
                
        return $data = $db->query($sql)->getResultArray();  //getResult(); //depending on required format assoc. array or db object
    
    }
    
    //used for the cron job
    public function writeFile(){
        $data = $this->generateCSV();
        
        $handle = fopen('uploads/reviewed-audits.csv', 'w');
        
        fputcsv($handle, array_keys($data[0]));
        
        foreach ($data as $data_array) {  //content
            fputcsv($handle, $data_array);
        }
        fclose($handle);
        
        $emailModel = new EmailModel();
        $email = $emailModel->csvEmail();
        
    return print_r($email);
    }
    
    public function getResponses($id = null) {
        $responseModel = new ResponseModel();
        //get the responses
        $responses = $responseModel->select('responses.*, questions.question, questions.question_number, questions.id AS question_id, answers.en AS answer')->where('audit_id', $id)->join('questions','questions.id = responses.question_id', 'inner')->join('answers','answers.id = responses.answer_id', 'inner')->findALL();
        
        $data = [];
        foreach($responses as $response){
            $data[$response['question_id']] = $response;
        }
        
        echo json_encode($data);
    }
    
    public function register($audit = null){
        $accountAuditModel = new AccountAuditModel();
        $accountModel = new AccountModel();
        helper(['form']);
        
        $accountAudit = $accountAuditModel->where('audit_id', $audit)->first();
        $account = $accountModel->where('id',$accountAudit['account_id'])->first();
        
        $data = [
            'account_id' => $account['id'],
            'account_name' => $account['name'],
        ];
        
        echo view('templates/header-hotel');
        echo view('signup-account',$data);
        echo view('templates/footer');
        
    }

    // generate the PDF for Skapit
    public function salesforceResultPDF($audit_id = null){
        $auditModel = New AuditModel();
        $accountModel = New AccountModel();
        $accountAuditModel = New AccountAuditModel();
        $responseModel = New ResponseModel();

        $db = db_connect(); 
        $audit = $auditModel->where('id',$audit_id)->first();
        $accountAudit = $accountAuditModel->where('audit_id', $audit_id)->first();
        $account = $accountModel->where('id',$accountAudit['account_id'])->first();

        $query = $db->query("SELECT id, en AS 'question', hide_for_1, hide_for_2, hide_for_3, hide_for_4, hide_for_5, question_number, has_custom_answer FROM questions ORDER BY question_number ASC");
        $results = array();

        $query = $db->query("
        SELECT questions.question, answers.en, responses.comment, responses.answer_id, responses.custom_answer, responses.score_ba, responses.score_abta 
        FROM `responses` 
        INNER JOIN questions ON questions.id = responses.question_id 
        INNER JOIN answers ON answers.id = responses.answer_id 
        WHERE audit_id = '".$audit_id."'");
    
        foreach ($query->getResultArray() as $row){

            $line = [];
            $line['highlight'] = "none";
            if($row['score_ba'] <= -100015 || $row['score_abta'] <= -100015) {
                $line['highlight'] = "fail";
            }
            if($row['score_ba'] >= 100015 || $row['score_abta'] >= 100015) {
                $line['highlight'] = "pass";
            }
            
            if($row['answer_id'] != "9999" && $row['answer_id'] != "8888"){
                $line['question'] = $row['question'];
                $line['answer'] = $row['en'];
                $line['comment'] = $row['comment'];
            } elseif( $row['answer_id'] == "9999" ){
                $line['question'] = $row['question'];
                $line['answer'] = $row['custom_answer'];
                $line['comment'] = $row['comment'];
            } else {
                continue;
            }
            $results[] = $line;
        }

        $data['audit'] = $audit;
        $data['account'] = $account;
        $data['questions'] = $results;

        $html = view('salesforce-results-pdf',$data);

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 4.5,
            'margin_right' => 4.5,
            'margin_top' => 6,
            'margin_bottom' => 6,
            'margin_header' => 0,
            'margin_footer' => 0
        ]);
        $mpdf->WriteHTML($html);

        $filename = $audit_id.".pdf";

        $mpdf->Output($filename, 'F');

    }   
    
    public function resubmit($audit_id = null){

        //set up the objects
        $auditModel = new AuditModel();
        $accountModel = new AccountModel();
        $responseModel = new ResponseModel();
        $uploadModel = new UploadModel();
        $accountAuditModel = new AccountAuditModel();

        //get the data relating to the original audit
        $audit = $auditModel->where('id',$audit_id)->first();
        $accountAudit = $accountAuditModel->where('audit_id', $audit_id)->first();
        $uploads = $uploadModel->where('audit_id',$audit_id)->findAll();
        $responses = $responseModel->where('audit_id',$audit_id)->findAll();

        //generate a new audit id
        $newAuditId = $auditModel->generateID();

        //create the new database records with the old data and the new audit id
        //audit
        $newAudit = [
            'id' => $newAuditId,
            'type' => $audit['type'],
            'sent_date' => Time::now('Europe/London', 'en_GB'),
            'created_date' => Time::now('Europe/London', 'en_GB'),
            'waiver_signed' => $audit['waiver_signed'],
            'waiver_signed_date' => $audit['waiver_signed_date'],
            'waiver_extra_info_included' => $audit['waiver_extra_info_included'],
            'waiver_extra_info' => $audit['waiver_extra_info'],
            'waiver_name' => $audit['waiver_name'],
            'waiver_job_title' => $audit['waiver_job_title'],
            'waiver_email' => $audit['waiver_email'],
            'language' => $audit['language'],
            'status' => 'in progress',
            'is_payable' => $audit['is_payable'],
            'payable_amount' => $audit['payable_amount'],
            'highlight_failures' => 1,
            'is_resubmission' => 1,
        ];
        $auditModel->insert($newAudit);

        //audit x property mapping
        $accountAudit['id'] = null;
        $accountAudit['audit_id'] = $newAuditId;
        $accountAuditModel->insert($accountAudit);

        //responses
        foreach( $responses as $response){
            $reponse['id'] = null;
            $response['audit_id'] = $newAuditId;
            $responseModel->insert($response);
        }

        //uploads
        foreach( $uploads as $upload){
            $old = "uploads/".$audit_id."/".$upload['file_name'];
            $new = "uploads/".$newAuditId."/".$upload['file_name'];
            if(!is_dir("uploads/".$newAuditId."/")){
                mkdir("uploads/".$newAuditId."/", 0755, true);
            }
            copy($old,$new); 
            $upload['id'] = null;
            $upload['audit_id'] = $newAuditId;
            $uploadModel->insert($upload);
        }

        // send a link to the audit
        $account = $accountModel->where('id', $accountAudit['account_id'])->first();

        $url =  site_url("/audit/".$newAuditId);
        $values = array($account['name'], $url,$account['accommodation_name'],$account['resort'],$account['country']);
        
        $emailModel = new EmailModel();
        $emailModel->sendNewAudit($audit['language'],$account['email'],$values,"");

        // open the new audit
        return $this->response->redirect(site_url('/audit/'.$newAuditId));

    }
    
}
?>