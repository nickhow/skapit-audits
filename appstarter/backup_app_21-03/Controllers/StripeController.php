<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use Stripe;
use App\Models\AuditModel;
use App\Models\AccountAuditModel;
use App\Models\AccountModel;
use App\Models\EmailModel;
use App\Models\TextModel;
use CodeIgniter\I18n\Time;


require '../vendor/autoload.php';

error_reporting(E_ALL);
ini_set("display_errors", 1);

class StripeController extends Controller
{

    public function index()
    {
        return view('checkout'); //header and footer included in view.
    }
    
    function calculateOrderAmount(array $items): int {  //function not working
        // Replace this constant with a calculation of the order's amount
        // Calculate the order total on the server to prevent
        // people from directly manipulating the amount on the client
        return 5000;
    }
    
    //from the audit id get the payable amount
    function getAmount(array $items): int {
        $id = $items['0']->id;
        $auditModel = new AuditModel();
        $audit = $auditModel->where('id',$id)->first();
        return ($audit['payable_amount']*100);
    }
    
    public function createCharge()
    {
        try {
            
            // retrieve JSON from POST body
            $jsonStr = file_get_contents('php://input');
            $jsonObj = json_decode($jsonStr);
    
            Stripe\Stripe::setApiKey(getenv('stripe.secret'));
            
            $paymentIntent = \Stripe\PaymentIntent::create([
             //   'amount' => '5000',    //calculateOrderAmount($jsonObj->items)  //function not working
                'amount' => $this->getAmount($jsonObj->items)  ,
                'currency' => 'eur',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
        
            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];
        
            echo json_encode($output) ;
        } catch (Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
  
    } 

    public function paymentSuccess($id=null){
        
        
        $db = db_connect();
        $auditModel = new AuditModel();
        $accountAuditModel = new AccountAuditModel();
        $accountModel = new AccountModel();
        $textModel = new TextModel();
        $session = session();
        

        $uri = new \CodeIgniter\HTTP\URI();
        $uri = current_url(true);
        
        //get the stripe id
        $pi = $uri->getQuery(['only' => ['payment_intent']]);
        $pi = explode("=", $pi);
        
        //get the status
        $status = $uri->getQuery(['only' => ['redirect_status']]);
        $status = explode("=", $status);
        
        if($status[1] == "succeeded"){
            
            $data = [
                    'status' => 'complete',
                    'completed_date' => Time::now('Europe/London', 'en_GB'),
                    'last_updated' => Time::now('Europe/London', 'en_GB'),
                    'is_paid' => '1',
                    'payment_id' => $pi[1],
                ];
            $account_audit = $accountAuditModel->where('audit_id',$id)->first();
            $account = $accountModel->where('id',$account_audit['account_id'])->first();
            $audit = $auditModel->where('id',$id)->first();
            
            //send email to hotelcheck and fraser
           //TEST :: $emailaddresses=('nick@powderwhite.com');  
           //LIVE :: $emailaddresses=('fraser@skapit.com, a.lopez@hotelcheck-hsaudits.com');
            $emailaddresses=('fraser@skapit.com, a.lopez@hotelcheck-hsaudits.com');
                
            //Email the account about the audit
            $url =  "https://audit.ski-api-technologies.com/audit/".$id."/review";
            $values = array( $account['accommodation_name'], $audit['type'], $account['resort'], $url);
                
            $emailModel = new EmailModel();
            $emailModel->sendCompletedAudit("en",$emailaddresses,$values);
            
            //reloads the form page - if it is complete it needs a new message -> goes to the locked for review screen, maybe soften that message. If it was saved it should alert to success / failure.
            $text = $textModel->limit(1)->getWhere(['name'=>'audit_completed']);
            $lang = $audit['language'];
            $flashData = [
                'msg'  => "Payment Success! This audit has been submitted for review<br/>".$text->getRow()->$lang,
                'style' => 'alert-success',
            ];
            $session->setFlashdata($flashData);

        }

        echo ' committing to db';
        $auditModel->update($id,$data);
        
        return $this->response->redirect(site_url('/audit/'.$id));
    }

}
?>