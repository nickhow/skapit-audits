<?php 

namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\GroupModel;
  

class SigninController extends Controller
{
    public function index()
    {   
        helper(['form']);
        echo view('signin');
    } 
  
    public function loginAuth()
    {
        $session = session();

        $userModel = new UserModel();
        $groupModel = new GroupModel();
        
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        
        $data = $userModel->where('username', $username)->first();
        
        if($data){
            $group = $groupModel->where('id',$data['group_id'])->first();
            if (empty($group)) {
                $group['uses_sub_groups'] = false;
            }

            $pass = $data['password'];
            $authenticatePassword = password_verify($password, $pass);
            if($authenticatePassword){
                $ses_data = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'email' => $data['user_email'],
                    'is_admin' => $data['is_admin'],
                    'is_hotelcheck' => $data['is_hotelcheck'],
                    'group_id'=> $data['group_id'],
                    'account_id'=> $data['account_id'],
                    'isLoggedIn' => TRUE,
                    'enable_groups' => $group['uses_sub_groups']
                ];
                
                $uri = new \CodeIgniter\HTTP\URI(getallheaders()['Referer']);
                $uri = $uri->getQuery(['only' => ['refer']]);
                $uri = substr($uri, 6);  //6 is to strip out "refer=""
                echo $uri;
                
                $session->set($ses_data);
                
                if(isset($uri) && !$uri == "" ) {
                   
                    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
                    $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
                    $uri = str_replace($entities, $replacements, $uri);
    
                    return redirect()->to($uri);
                } else {
                    return redirect()->to('/audits');
                }
            
            }else{
                $session->setFlashdata('msg', 'Password is incorrect.');
                return redirect()->to('/signin');
            }

        }else{
            $session->setFlashdata('msg', 'Username does not exist.');
            return redirect()->to('/signin');
        }
    }
    
    public function signout() {
         $session = session();
         $session->destroy();
         return redirect()->to('/signin');
    }
}