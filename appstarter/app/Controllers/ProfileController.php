<?php 

namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\GroupModel;
use App\Models\GroupMappingModel;
use App\Models\AccountModel;

class ProfileController extends Controller
{
    public function index()
    {
        $userModel = new UserModel();
        $groupMappingModel = new GroupMappingModel();
        $session = session();
        $db = db_connect();
        $admin = $session->get('is_admin');
        $group_id = $session->get('group_id');
        
        $sql = "SELECT users.id, users.name as 'name', username, users.group_id, users.account_id, users.created_date, groups.name as 'group_name', accounts.accommodation_name as 'account_name' FROM users LEFT JOIN groups ON users.group_id = groups.id LEFT JOIN accounts ON users.account_id = accounts.id WHERE users.is_admin <> '1' AND users.is_hotelcheck <> '1'";
        
        if($admin){
            echo view('templates/header');
        } elseif($session->get('enable_groups')) {
            $groups = $groupMappingModel->where('group_id',$group_id)->findColumn('sub_group_id');
            $sql.=" AND users.group_id IN (".$group_id."," . implode(',', $groups) . ")";       //gets the current user (parent group) id and the sub groups so we show all users.
            echo view('templates/header-group');
        } else {
            $sql.=" AND users.group_id = '".$group_id."'";
            echo view('templates/header-group');
        }

        $data['users'] = $db->query($sql)->getResultArray();
        
        echo view('view_users', $data);
        echo view('templates/footer');
    }
    
    public function delete($id = null){
        $userModel = new UserModel();
        $groupMappingModel = new GroupMappingModel();
        $session = session();
        $admin = $session->get('is_admin');
        $group_id = $session->get('group_id');
        
        $user = $userModel->where('id', $id)->first();

        if($id = session()->get('id')) {
            //you can't delete yourself
            $this->response->redirect(site_url('/users'));
        }

        if(!$admin){
            if($user['group_id'] != $group_id || $user['group_id'] == 0) {  // 0 is admin or account level
                
                //not the direct owner, is it the group owner
                $subGroups = $groupMappingModel->where('group_id',$group_id)->findColumn('sub_group_id');
                
                $access = false;
                foreach($subGroups as $group){
                    if($group == $user['group_id']) { $access = true;}
                }
                
                if($access == false){
                    return $this->response->redirect(site_url('/users'));
                }
            }
        }
        
        //delete user
        $data['users'] = $userModel->where('id', $id)->delete();
        return $this->response->redirect(site_url('/users'));
    }  
    
    public function singleUser($id = null) {
        helper(['form']);
        
        $userModel = new UserModel();
        $groupModel = new GroupModel();
        $accountModel = new AccountModel();
        
        //Get the account by ID
        $data['user_obj'] = $userModel->where('id', $id)->first();
        $data['account_obj'] = $accountModel->where('id',$data['user_obj']['account_id'])->first();
        $session = session();
        $admin = $session->get('is_admin');
        if(!$admin){
            //check the logged in user is part of the group who owns the account
            if($session->get('group_id') != $data['user_obj']['group_id']){
                return $this->response->redirect(site_url('/users'));
            }
        } else {
            $data['groups'] = $groupModel->orderBy('id', 'DESC')->findAll();
        }
        
        if(session()->get('is_admin')){
            echo view('templates/header');
        }else{
            echo view('templates/header-group');
        }
        
        echo view('single-user', $data);
        echo view('templates/footer');
    }
    
    public function update(){
    $userModel = new UserModel();
    helper(['form']);
        $rules = [
            'name'          => 'required|min_length[2]|max_length[50]',
            'password'      => 'required|min_length[4]|max_length[50]',
        ];
        $errors = [
            'username' => [
                'required' => 'Name is a required field. Please enter a name.',
                'min_length' => 'The name must be at least 2 characters.',
                'max_length' => 'The username must not be more than 50 characters',
                ],
            'password' => [
                'min_length' => 'The password must be at least 4 characters.',
                'max_length' => 'The username must not be more than 50 characters',
                ],
          ];
        
        $session = session();
        $admin = $session->get('is_admin');
        
        $id = $this->request->getVar('id');
        $user = $userModel->where('id',$id)->first();

        if(!$admin){
            //check the logged in user is part of the group who owns the account
            if($session->get('group_id') != $this->request->getVar('group_id') || $this->request->getVar('group_id') != $user['group_id']) { //check the logged in user is allowded to edit this user and the group id is not changed.
                $session->setFlashdata('msg', 'Failed to update the user, please try again.');
                return $this->response->redirect(site_url('/users'));
            }
        }
          
        if($this->validate($rules,$errors)){
            
            $data = [
                'name'  => $this->request->getVar('name'),
                'group_id'  => $this->request->getVar('group_id'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];

            $userModel->update($id,$data);
            $session->setFlashdata('msg', 'User updated.');
            return $this->response->redirect(site_url('/users'));
            
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
            echo view('single-user', $data);
            echo view('templates/footer');
        }

    }
    
}