<?php 
namespace App\Controllers;
use App\Models\GroupModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class GroupCrud extends Controller
{
    // show groups list
    public function index(){
        $groupModel = new GroupModel();
        $data['groups'] = $groupModel->orderBy('id', 'DESC')->findAll();
        
        echo view('templates/header');
        echo view('view_groups', $data);
        echo view('templates/footer');
    }
    
    // add group form
    public function create(){
                
        helper(['form']);
        $data = [];
        
        echo view('templates/header');
        echo view('add_group', $data);
        echo view('templates/footer');
    }
 
    // insert data
    public function store() {
        $groupModel = new GroupModel();
        $session = session();
        
        helper(['form']);
        $rules = [
            'group_name'         => 'required|min_length[2]|max_length[50]|is_unique[groups.name]',
            'group_manager_name'          => 'required|min_length[2]|max_length[50]',
            'username'         => 'required|min_length[4]|max_length[100]|is_unique[users.username]',
            'password'      => 'required|min_length[4]|max_length[50]',
            'confirmpassword'  => 'matches[password]'
        ];
        
        $errors = [
            'group_name' => [
                'required' => 'Group name is a required field. Please enter a name.',
                'min_length' => 'The group name must be at least 2 characters.',
                'max_length' => 'The group name must not be more than 50 characters',
                'is_unique' => 'This group name has been taken, please enter another name.'
                ],
            'group_manager_name' => [
                'required' => 'Group manager name is a required field. Please enter a name.',
                'min_length' => 'The group manager name must be at least 2 characters.',
                'max_length' => 'The group manager name must not be more than 50 characters',
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
            
            $data = [
                'name' => $this->request->getVar('group_name'),
                'is_payable' => $this->request->getVar('is_payable'),
            ];
            $groupModel->insert($data);
            $group_id = $groupModel->select('id')->where('name', $this->request->getVar('group_name'))->first();
            
            $userModel = new UserModel();
            $data = [
                'name'     => $this->request->getVar('group_manager_name'),
                'username'    => $this->request->getVar('username'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'group_id' => $group_id['id']
            ];
            $userModel->save($data);
            
            $session->setFlashdata('msg', 'Group added. Group manager created, please share their password with them.');
            return $this->response->redirect(site_url('/groups'));
            
        }else{
            $data['validation'] = $this->validator;
            echo view('templates/header');
            echo view('add_group', $data);
            echo view('templates/footer');
        
        }
        

    }

    // show single group
    public function singleGroup($id = null){
        $groupModel = new GroupModel();
        $data['group_obj'] = $groupModel->where('id', $id)->first();
        
        echo view('templates/header');
        echo view('single-group', $data);
        echo view('templates/footer');
    }

    // update group data
    public function update(){
        $groupModel = new GroupModel();
        $session = session();
        $id = $this->request->getVar('id');
        $data = [
            'name' => $this->request->getVar('name'),
            'is_payable' => $this->request->getVar('is_payable'),
        ];
        $groupModel->update($id, $data);
        $session->setFlashdata('msg', 'Group updated.');
        return $this->response->redirect(site_url('/groups'));
    }
 
    // delete group
    public function delete($id = null){
        $groupModel = new GroupModel();
        $userModel = new UserModel();
        $session = session();

        $data['users'] = $userModel->where('group_id', $id)->delete();
                
        $data['groups'] = $groupModel->where('id', $id)->delete($id);
        $session->setFlashdata('msg', 'Group deleted.');
        return $this->response->redirect(site_url('/groups'));
    }    
}
?>