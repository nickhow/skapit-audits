<?php 
namespace App\Models;
use CodeIgniter\Model;

class EmailModel extends Model
{
    protected $table = 'emails';

    protected $primaryKey = 'id';
    
    protected $allowedFields = ['type','language','html','text','subject'];
    
    //Prepare and send message for sending New Audit email.
    /**
     * Expects:
     *  Language code  i.e. en
     *  Email address - Can be single, comma-delimited list
     *  Array containing : Account name, Audit URL, Property name, resort & country
     **/
    public function sendNewAudit($language="en", $emailAddresses, $values=[], $intro=""){
        $whereCondition = array('type'=>'new_audit','language'=>$language);
        $email = $this->where($whereCondition)->first();
            
        //Email settings
        $subject = $email['subject'];
        $message = $email['html'];
        $text = $email['text'];

        //custom intro ahead of main email
        $message = str_replace("__custom_intro__",$intro,$message);
        
        //Tags to search the text for -> this needs to be aligned to the $values[] Array
        $tags = array("__name__", "__url__","__propertyname__","__resort__","__country__");
        
        $message = str_replace($tags,$values,$message);
        $text = str_replace($tags,$values,$text);
            
        $this->sendEmail($emailAddresses, $message, $text, $subject);
        
    }
    
    //Prepare and send message for sending chase email.
    /**
     * Expects:
     *  Language code  i.e. en
     *  Email address - Can be single, comma-delimited list
     *  next chase number (1 .. 3)
     *  Array containing : Name and URL
     **/
    public function sendChase($language="en", $emailAddresses, $chase, $values=[]){
        
        $chase_email = "chase_".$chase;
        
        $whereCondition = array('type'=>$chase_email,'language'=>$language);
        $email = $this->where($whereCondition)->first();
            
        //Email settings
        $subject = $email['subject'];
        $message = $email['html'];
        $text = $email['text'];
        
        //Tags to search the text for -> this needs to be aligned to the $values[] Array
        $tags = array("__name__", "__url__","__propertyname__","__resort__");
        
        $message = str_replace($tags,$values,$message);
        $text = str_replace($tags,$values,$text);
            
        $this->sendEmail($emailAddresses, $message, $text, $subject);
        
    }
    
    //Prepare and send message for newly completed audit.
    /**
     * Expects:
     *  Language code  i.e. en
     *  Email address - Can be single, comma-delimited list
     *  Array containing : property name, type, resort, URL
     **/
    public function sendCompletedAudit($language="en", $emailAddresses, $values=[]){
        $whereCondition = array('type'=>'audit_completed','language'=>$language);
        $email = $this->where($whereCondition)->first();
            
        //Email settings
        $subject = $email['subject'];
        $message = $email['html'];
        $text = $email['text'];
        
        //Tags to search the text for -> this needs to be aligned to the $values[] Array
        $tags = array("__name__","__type__","__resort__","__url__");
        
        $message = str_replace($tags,$values,$message);
        $text = str_replace($tags,$values,$text);
            
        $this->sendEmail($emailAddresses, $message, $text, $subject);
        
    }
    
    //Prepare and send message for newly reviewed audit.
    /**
     * Expects:
     *  Language code  i.e. en
     *  Email address - Can be single, comma-delimited list
     *  Array containing : property name, type, resort, result_ba, result_abta, result_dnata URL
     * 
     * TODO : Add to the tags / values the btn url so that is also env specific - currently hardcoded in email template to the live url
     **/
    public function sendReviewedAudit($language="en", $emailAddresses, $values=[],$audit_id){
        $whereCondition = array('type'=>'audit_reviewed','language'=>$language);
        $email = $this->where($whereCondition)->first();
            
        //Email settings
        $subject = $email['subject'];
        $message = $email['html'];
        $text = $email['text'];
        $attachment = $audit_id.".pdf";
        
        //Tags to search the text for -> this needs to be aligned to the $values[] Array
        $tags = array("__name__","__type__","__resort__","__result_ba__","__result_abta__","__result_dnata__","__url__");
        
        $message = str_replace($tags,$values,$message);
        $text = str_replace($tags,$values,$text);
                
        return ( $this->sendEmailWithAttachment($emailAddresses, $message, $text, $subject, $attachment) );
    }
    

    //used for email audit complete
    function pdfEmail($language="en", $email_content, $emailAddresses="nick@skapit.com", $values=[], $audit_id){
        
        $whereCondition;
        if($email_content == "account"){
            $whereCondition = array('type'=>'hotel_audit_reviewed_account','language'=>$language);
        } else {
            $whereCondition = array('type'=>'hotel_audit_reviewed_group','language'=>$language);
        }
        
        $emailContent = $this->where($whereCondition)->first();
        
        //Email settings
        $subject = $emailContent['subject'];
        $message = $emailContent['html'];
        
        //Tags to search the text for -> this needs to be aligned to the $values[] Array
        $tags = array("__name__","__url__");
        
        $message = str_replace($tags,$values,$message);
        
        $email = \Config\Services::email();
        
        $email->clear(true); //reset $email to empty state (true - include attachments)

        $email->setFrom('hands@skapit.com', 'SKAPIT Health & Safety');
        $email->setTo($emailAddresses);  
        $email->setSubject($subject);
        $email->setMessage($message);

        $attachment = $audit_id.".pdf";

        $email->attach($attachment, 'application/pdf' , 'results.pdf');
        
       if($email->send()){
          return "ok"; 
       } else {
            $data = $email->printDebugger(['headers']);
            return ($data);
       }
    }

    //used for cron job
    function csvEmail(){
        
        $emailaddresses = (getenv('alex'));
        
        $email = \Config\Services::email();
        $email->clear(true); //reset $email to empty state (true - include attachments)
        
        $email->setFrom('hands@skapit.com', 'SKAPIT Health & Safety');
        //$email->setTo('a.lopez@hotelcheck-hsaudits.com');  // can be single, comma-delimited list 'a@me.com, b@me.com' or array ['a@me.com','b@me.com']
        $email->setTo($emailaddresses);
        $email->setSubject('Reviewed Audit Order');
        $email->setMessage('Audit order document attached.');
        $email->attach('uploads/reviewed-audits.csv');        
        
       if($email->send()){
          return "ok"; 
       } else {
            $data = $email->printDebugger(['headers']);
            return ($data);
       }
    }
    
    function sendEmail($emailaddress="nick@skapit.com", $message = "" , $text = "", $subject =""){
        $session = session();

        $email = \Config\Services::email();
        $email->clear(true); //reset $email to empty state (true - include attachments)

        $email->setFrom('hands@skapit.com', 'SKAPIT Health & Safety');
        
        $email->setTo($emailaddress);  // can be single, comma-delimited list 'a@me.com, b@me.com' or array ['a@me.com','b@me.com']
       
        if($subject == "") {
                $subject = "Test Email";
        }
        $email->setSubject($subject);
        
        if($message == "") {
            $message = "Test Email message";
        }
        $email->setMessage($message);

        if($text == "") {
            $text = "Test Email message";
        }
        $email->setAltMessage($text);
        
        
       if($email->send()){
          echo "ok"; 
          $session->setFlashdata('msg', 'Email sent.');
       } else {
            $data = $email->printDebugger(['headers']);
            print_r($data);
            $session->setFlashdata('msg', 'Email failed to send.');
       }
    }

    function sendEmailWithAttachment($emailaddress="nick@skapit.com", $message = "" , $text = "", $subject ="", $attachment=""){
        $session = session();
        
        $email = \Config\Services::email();

        $email->clear(true); //reset $email to empty state (true - include attachments)

        $email->setFrom('hands@skapit.com', 'SKAPIT Health & Safety');
        
        $email->setTo($emailaddress);  // can be single, comma-delimited list 'a@me.com, b@me.com' or array ['a@me.com','b@me.com']
       
        if($subject == "") {
                $subject = "Test Email";
        }
        $email->setSubject($subject);
        
        if($message == "") {
            $message = "Test Email message";
        }
        $email->setMessage($message);

        if($text == "") {
            $text = "Test Email message";
        }
        $email->setAltMessage($text);
        
        $email->attach($attachment, 'application/pdf' , 'audit-results.pdf');
        
       if($email->send()){
          echo "ok"; 
          $session->setFlashdata('msg', 'Email sent.');
       } else {
            $data = $email->printDebugger(['headers']);
            print_r($data);
            $session->setFlashdata('msg', 'Email failed to send.');
       }
    }


    function getEmailHtml($type = 'new_audit', $lang='en'){
        $whereCondition = array('type'=>$type,'language'=>$lang);
        $html = $this->where($whereCondition)->first();
        return $html;
    }


    function sendResetEmail($user, $link, $lang='en'){
 
        $whereCondition = array('type'=>'password_reset','language'=>$lang);
        $emailContent = $this->where($whereCondition)->first();
        
        //Email settings
        $subject = $emailContent['subject'];
        $message = $emailContent['html'];
        
        //Tags to search the text for -> this needs to be aligned to the $values[] Array
        $tags = array("__name__","__url__");
        $values =[
            'name' => $user['name'],
            'url' => $link,
        ];

        $message = str_replace($tags,$values,$message);
        
        $email = \Config\Services::email();
        
        $email->clear(true); //reset $email to empty state (true - include attachments)

        $email->setFrom('hands@skapit.com', 'SKAPIT Health & Safety');
        $email->setTo($user['user_email']);  
        $email->setSubject($subject);
        $email->setMessage($message);
        
       if($email->send()){
          //return "ok"; 
          return ($message);
       } else {
            $data = $email->printDebugger(['headers']);
            return ($data);
       }
    }

}

?>