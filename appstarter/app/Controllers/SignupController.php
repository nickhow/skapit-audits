<?php 

namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\GroupModel;
use App\Models\GroupMappingModel;
use App\Models\AccountModel;
use App\Models\ResetModel;

use CodeIgniter\I18n\Time;


class SignupController extends Controller
{
    public function index()
    {
        helper(['form']);
        $data = [];
        
        $groupModel = new GroupModel();
        $groupMappingModel = new GroupMappingModel();
        $session = session();
        $admin = $session->get('is_admin');
        
        if($admin){ //admin - get all accounts
            $data['groups'] = $groupModel->orderBy('id', 'DESC')->findAll();
            echo view('templates/header');
        } elseif($session->get('enable_groups')){
            
            $groups = $groupMappingModel->where('group_id',$session->get('group_id'))->findColumn('sub_group_id');
            array_push($groups, $session->get('group_id'));
            $data['groups'] = $groupModel->whereIn('id',$groups)->orderBy('id', 'DESC')->findAll();
           
            echo view('templates/header-group');
        } else {
            return $this->response->redirect(site_url('/'));
        }
        

        echo view('signup', $data);
        echo view('templates/footer');
    }
    
    public function store()
    {
        helper(['form']);
        $rules = [
            'name'          => 'required|min_length[2]|max_length[50]',
            'email'         => 'required|min_length[2]|max_length[80]|is_unique[users.email]',
            'username'         => 'required|min_length[4]|max_length[100]|is_unique[users.username]',
            'password'      => 'required|min_length[4]|max_length[50]',
            'confirmpassword'  => 'matches[password]'
        ];
        $errors = [
            'name' => [
                'required' => 'Name is a required field. Please enter a name.',
                'min_length' => 'The name must be at least 2 characters.',
                'max_length' => 'The username must not be more than 50 characters',
                ],
            'email' => [
                    'required' => 'Email is a required field. Please enter an email address.',
                    'min_length' => 'The email is too short.',
                    'max_length' => 'The email is too long',
                    'is_unique' => 'This email has used already.',
                    ],
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
          
        if($this->validate($rules, $errors)){
            $userModel = new UserModel();
            
            $group_id = 0;
            if( null !== ($this->request->getVar('group_id'))){
                $group_id = $this->request->getVar('group_id');
            }
            
            $account_id = 0;
            if( null !== ($this->request->getVar('account_id'))){
                $account_id = $this->request->getVar('account_id');
            }
            
            
            $data = [
                'name'  => $this->request->getVar('name'),
                'username'  => $this->request->getVar('username'),
                'email' => $this->request->getVar('email'),
                'group_id'  => $group_id,
                'account_id' => $account_id,
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];

            $userModel->save($data);

            $redirect = '/audits';
            
            if(Session()->get('isAdmin')){
                $redirect = '/users';
            }
            
            return redirect()->to($redirect);

        }else{
            helper(['form']);
            $data['validation'] = $this->validator;

            $groupModel = new GroupModel();
            $session = session();
            $admin = $session->get('is_admin');
                
            if($admin){ //admin - get all accounts
                $data['groups'] = $groupModel->orderBy('id', 'DESC')->findAll();
            }
                
            echo view('templates/header');
            echo view('signup', $data);
            echo view('templates/footer');
        }
          
    }

    public function selfserve()
    {
        helper(['form']);
        $data = [];
    
        echo view('templates/header');
        echo view('self_serve',$data);
        echo view('templates/footer');
    }

    public function store_selfserve()
    {
        helper(['form']);
        $rules = [
            'name'          => 'required|min_length[2]|max_length[50]',
            'email'         => 'required|min_length[2]|max_length[80]|is_unique[users.user_email]',
            'password'      => 'required|min_length[4]|max_length[50]',
            'confirmpassword'  => 'matches[password]'
        ];
        $errors = [
            'name' => [
                'required' => 'Name is a required field. Please enter a name.',
                'min_length' => 'The name must be at least 2 characters.',
                'max_length' => 'The username must not be more than 50 characters',
                ],
            'email' => [
                    'required' => 'Email is a required field. Please enter an email address.',
                    'min_length' => 'The email is too short.',
                    'max_length' => 'The email is too long',
                    'is_unique' => 'This email has been used already.',
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
          
        if($this->validate($rules, $errors)){
            $userModel = new UserModel();
   
            $group_id = 0;
            $account_id = 0;

            $data = [
                'name'  => $this->request->getVar('name'),
                'username'  => $this->request->getVar('email'),
                'user_email' => $this->request->getVar('email'),
                'group_id'  => $group_id,
                'account_id' => $account_id,
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];

            $userModel->save($data);

            $accountModel = new AccountModel();
            $account_data = [
                'name' => $this->request->getVar('name'),
                'email'  => $this->request->getVar('email'),
                'group_id'  => $group_id,
                'is_group_manager'  => $this->request->getVar('is_group_manager'),
                'phone'  => $this->request->getVar('phone'),
                'accommodation_name'  => $this->request->getVar('accommodation_name'),
                'resort'  => $this->request->getVar('resort'),
                'country'  => $this->request->getVar('country'),
                'notes'  => $this->request->getVar('notes'),
            ];
            $accountModel->save($account_data);

        //    $redirect = '/audits';

        //    return redirect()->to($redirect);

        }else{
            helper(['form']);
            $data['validation'] = $this->validator;

            echo view('templates/header');
            echo view('self_serve', $data);
            echo view('templates/footer');
        }
          
    }

    public function reset_request(){
        echo view('templates/header');
        echo view('request_reset');
        echo view('templates/footer');
    }

    public function init_reset(){
        $userModel = new UserModel();
        $resetModel = new ResetModel();
        // check email
        $email_to_reset = $this->request->getVar('email');
        $user_to_reset = $userModel->where('user_email',$email_to_reset)->first();
        if( is_null( $user_to_reset ) ) {
            //stop - no user with this email
        } else {

        // create a reset row and remove any old ones for this account
        $resetModel->where('email', $email_to_reset)->delete();
        
        $selector = bin2hex(random_bytes(8));
        $token = random_bytes(32);
        $expires = new Time('+1 hour');

        $reset_data = [
            'email' => $email_to_reset,
            'selector'  => $selector,
            'token'  => hash('sha256', $token),
            'expires'  => $expires
        ];

        print_r($reset_data);

        $query_string = http_build_query(['selector' => $selector, 'validator' => bin2hex($token)]);

        $link = site_url('/reset-password'.$query_string);
        print_r($$link);
        
        $resetModel->save($reset_data);

        //email the reset link

        }
       


        //return message and show page
        echo view('templates/header');
        echo view('request_reset');
        echo view('templates/footer');
    }

    public function reset_password(){
        echo view('templates/header');
        echo view('reset_password');
        echo view('templates/footer');
    }

    public function process_reset(){
        // check token
        
        // Update password

        //return message and show page then redirect to login
        echo view('templates/header');
        echo view('reset_password');
        echo view('templates/footer');
    }
 
}