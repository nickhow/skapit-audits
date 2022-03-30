<?php 
namespace App\Controllers;
use App\Models\AccountModel;
use App\Models\AuditModel;
use App\Models\AccountAuditModel;
use App\Models\GroupModel;
use App\Models\EmailModel;
use App\Models\ContactModel;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;
use CodeIgniter\Controller;

class AccountCrud extends Controller
{
    // show accounts list
    public function index(){
        $accountModel = new AccountModel();
        //Gets the AccountModel -> no join so shows the IDs rather than names
        //$data['accounts'] = $accountModel->orderBy('id', 'DESC')->findAll();
        
        
        $session = session();
        $admin = $session->get('is_admin');
        $userModel = new UserModel();
        $user = $userModel->where('id', $session->get('id'))->first();
        
        //Uses the method with the join
        if($admin){
            $data['accounts'] = $accountModel->getAccountsWithGroup();
            echo view('templates/header');
        } else {
            $data['accounts'] = $accountModel->getAccountsWithGroupById($user['group_id']);
            echo view('templates/header-group');
        }
        
        echo view('view_accounts', $data);
        echo view('templates/footer');
    }
    


    // add account form
    public function create(){
        $groupModel = new GroupModel();
        $data['groups'] = $groupModel->findAll();
        
        helper(['form']);
        
        if(session()->get('is_admin')){
            echo view('templates/header');
        }else{
            echo view('templates/header-group');
        }
        
        echo view('add_account',$data);
        echo view('templates/footer');
    }
 
    // insert data
    public function store() {
        $accountModel = new AccountModel();
        $auditModel = new AuditModel();
        $accountAuditModel = new AccountAuditModel();
        $groupModel = new GroupModel();
        $session = session();
        $db = db_connect();
        
        if($this->request->getVar('is_group_manager')){
            helper(['form']);
            $rules = [
                'username'         => 'required|min_length[4]|max_length[100]|is_unique[users.username]',
                'password'      => 'required|min_length[4]|max_length[50]',
                'confirmpassword'  => 'matches[password]'
            ];
            $errors = [
                'username' => [
                    'required' => 'Username is a required field. Please enter a username.',
                    'min_length' => 'The username must be at least 4 characters.',
                    'max_length' => 'The username must not be more than 100 characters',
                    'is_unique' => 'This username has been taken, please enter another username.',
                    ],
                'password' => [
                    'required' => 'Password is a required field. Please enter a password.',
                    'min_length' => 'The password must be at least 4 characters.',
                    'max_length' => 'The username must not be more than 50 characters',
                    ],
                'confirmpassword' => [
                    'matches' => 'The passwords do not match.'
                    ]
              ];
                
            if($this->validate($rules,$errors)){
                //ok to proceed
                
            }else{
                $groupModel = new GroupModel();
                $data['groups'] = $groupModel->findAll();
                $data['validation'] = $this->validator;
                
                if(session()->get('is_admin')){
                    echo view('templates/header');
                }else{
                    echo view('templates/header-group');
                }
                
                echo view('add_account', $data);
                echo view('templates/footer');
                return;
            }
        }
        
        //could put in a check here that the group id wasn't modified
        if(!$session->get('is_admin')){
            if($session->get('group_id') !== $this->request->getVar('group_id')) {
                $session->setFlashdata('msg', 'Failed to connect account with your group, please try again.');
                return $this->response->redirect(site_url('/accounts'));
            }
        }
            
        //Account
        $data = [
            'name' => $this->request->getVar('name'),
            'email'  => $this->request->getVar('email'),
            'group_id'  => $this->request->getVar('group_id'),
            'is_group_manager'  => $this->request->getVar('is_group_manager'),
            'phone'  => $this->request->getVar('phone'),
            'accommodation_name'  => $this->request->getVar('accommodation_name'),
            'resort'  => $this->request->getVar('resort'),
            'country'  => $this->request->getVar('country'),
            'notes'  => $this->request->getVar('notes'),
        ];
        //Insert the Account
        $accountModel->insert($data);
                        
        //Get the ID
        $account_id = $db->insertID();
                        
        $session->setFlashdata('msg', 'Account added.');

        if($this->request->getVar('is_group_manager')){
            $userModel = new UserModel();
            $data = [
                'name'     => $this->request->getVar('name'),
                'username'    => $this->request->getVar('username'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'group_id' => $this->request->getVar('group_id')
            ];
            $userModel->save($data);
        }
        
        //IF- do Audit now ...
        $lang = $this->request->getVar('language');
        if(isset($lang) && $lang !== ""){
            
            //Generate the ID
            $id = $auditModel->generateID();
            //$account_id = $this->request->getVar('account');        
            $data['account'] = $accountModel->where('id', $account_id)->first();
            
            $isPayable = 0;
            $payableAmount = '0.00';
            if(!$session->get('is_admin')){ //if it's not admin, it's based on the group.
                $group = $groupModel->where('id',$data['account']['group_id'])->first();
                $isPayable = $group['is_payable'];
                if($isPayable){
                    $payableAmount = $group['payable_amount'];
                }
                    
            } else { //if it is admin it is set per audit.
                if(null !== ($this->request->getVar('is_payable'))) {
                    $isPayable = $this->request->getVar('is_payable');
                    if($isPayable){
                        $payableAmount = $this->request->getVar('payable_amount');
                    }
                } else {
                    $group = $groupModel->where('id',$this->request->getVar('group_id'))->first();
                    $isPayable = $group['is_payable'];
                    if($isPayable){
                        $payableAmount = $group['payable_amount'];
                    }
                }
            }
        
        
            //Collect audit data
            $auditData = [
                'id' => $id,
                'language' => $this->request->getVar('language'),
                'sent_date' => Time::now('Europe/London', 'en_GB'),
                'created_date' => Time::now('Europe/London', 'en_GB'),
                'status' => 'sent',
                'is_payable' => $isPayable,
                'payable_amount' => $payableAmount,
            ];

            //Collect accountAudit data
            $accountAuditData = [
                'audit_id' => $id,
                'account_id' => $account_id,
                'group_id' => $this->request->getVar('group_id'),
            ];
            
            //Insert data for the audit        
            $auditModel->insert($auditData);
            $accountAuditModel->insert($accountAuditData);
           
            //Email the account about the audit
            $url =  "https://audit.ski-api-technologies.com/audit/".$id;
            $values = array($data['name'], $url,$data['accommodation_name'],$data['resort'],$data['country']);
            
            $emailModel = new EmailModel();
            $emailModel->sendNewAudit($auditData['language'],$data['email'],$values);
            
            $session->setFlashdata('msg', 'Account created. Audit '.$id.' also created.');
            
        }
        
        //Finish and return to Accounts
        return $this->response->redirect(site_url('/accounts'));
    }

    // show single account
    public function singleAccount($id = null){
        $accountModel = new AccountModel();
        $groupModel = new GroupModel();
        $auditModel = new AuditModel();
        $contactModel = new ContactModel();
        $accountAuditModel = new AccountAuditModel();
        
        //Get the account by ID
        $data['account_obj'] = $accountModel->where('id', $id)->first();
        
        $session = session();
        $admin = $session->get('is_admin');
        if(!$admin){
            //check the logged in user is part of the group who owns the account
            $userModel = new UserModel();
            $user = $userModel->where('id', $session->get('id'))->first();
            if(!($user['group_id'] == $data['account_obj']['group_id'])){
                return $this->response->redirect(site_url('/accounts'));
            }
        }

        
        //Get all the groups, for the drop down
        $data['group_objects'] = $groupModel->orderBy('id', 'DESC')->findAll();
        
        //Get this accounts latest audit
        $db = db_connect();
        $sql = "SELECT audits.id, audits.sent_date FROM audits INNER JOIN account_audits ON account_audits.audit_id = audits.id INNER JOIN accounts ON accounts.id = account_audits.account_id WHERE account_id = ".$id." ORDER BY sent_date DESC LIMIT 1";
        $data['audit_objects'] = $db->query($sql)->getResult();
        
        //get any contact comments
        $data['contact'] = $contactModel->where('account_id', $id)->findAll();
        
        
        //get all the audits ...
        $query = $db->table('audits')->join('account_audits','audits.id = account_audits.audit_id', 'inner')->where('account_audits.account_id',$id)->get();
        $data['audits'] = $query->getResultArray();
        
        if(session()->get('is_admin')){
            echo view('templates/header');
        }else{
            echo view('templates/header-group');
        }
        
        echo view('single-account', $data);
        echo view('templates/footer');
    }

    // update account data
    public function update(){
        $accountModel = new AccountModel();
        $accountAuditModel = new AccountAuditModel();
        $auditModel = new AuditModel();
        $session = session();
        
        $id = $this->request->getVar('id');
        
        //if not admin, check the user is allowed to do this        
        if(!$session->get('is_admin')){
            $group = $session->get('group_id');
            $account = $accountModel->where('id',$id)->first();
            if($account['group_id'] !== $group){
                return $this->response->redirect(site_url('/accounts'));
            }
        }
        
        $data = [
            'name' => $this->request->getVar('name'),
            'email'  => $this->request->getVar('email'),
            'group_id'  => $this->request->getVar('group_id'),
         //   'is_group_manager'  => $this->request->getVar('is_group_manager'),   //doesn't really do anything.
            'phone'  => $this->request->getVar('phone'),
            'accommodation_name'  => $this->request->getVar('accommodation_name'),
            'resort'  => $this->request->getVar('resort'),
            'country'  => $this->request->getVar('country'),
            'notes'  => $this->request->getVar('notes'),
        ];
        $accountModel->update($id, $data);
        
        //update all the audits to the new last contact time
        $account_audits = $accountAuditModel->where('account_id',$id)->findAll();
      
        if($account_audits){
            foreach($account_audits as $audit){
                $audit_id = $audit['audit_id'];
                $data = [ 'sent_date' => $this->request->getVar('sent_date'), ];
                $auditModel->update($audit_id, $data);
            }
        }
        $session->setFlashdata('msg', 'Account updated.');
        return $this->response->redirect(site_url('/accounts'));
    }
 
    // delete account
    public function delete($id = null){
        $accountModel = new AccountModel();
        $session = session();
        
        //if not admin, check the user is allowed to do this
        if(!$session->get('is_admin')){
            $group = $session->get('group_id');
            $account = $accountModel->where('id',$id)->first();
            if($account['group_id'] !== $group){
                return $this->response->redirect(site_url('/accounts'));
            }
        }
        
        $data['accounts'] = $accountModel->where('id', $id)->delete($id);
        $session->setFlashdata('msg', 'Account deleted.');
        return $this->response->redirect(site_url('/accounts'));
    }    
}
?>