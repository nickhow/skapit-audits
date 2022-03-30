<?php 
namespace App\Controllers;
use App\Models\GroupModel;
use App\Models\GroupMappingModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class GroupCrud extends Controller
{
    // show groups list
    public function index(){
        $groupModel = new GroupModel();
        $groupMappingModel = new GroupMappingModel();
        
        if(session()->get('is_admin')){
            $data['groups'] = $groupModel->orderBy('id', 'DESC')->findAll();
            echo view('templates/header');
        } elseif(session()->get('enable_groups')) {
            $subGroups = $groupMappingModel->where('group_id',session()->get('group_id'))->findColumn('sub_group_id');
            $data['groups'] = $groupModel->whereIn('id',$subGroups)->orderBy('id','DESC')->findAll();
            echo view('templates/header-group');
        }else{
            return $this->response->redirect(site_url());
        }
        
        echo view('view_groups', $data);
        echo view('templates/footer');
    }
    
    // add group form
    public function create(){
                
        helper(['form']);
        $groupModel = new GroupModel();
        $data = [];
        
        if(session()->get('is_admin')){
            $data['groups'] = $groupModel->where('uses_sub_groups', '1')->findAll();
            echo view('templates/header');
        } else {
            echo view('templates/header-group');
        }
        echo view('add_group', $data);
        echo view('templates/footer');
    }
 
    // insert data
    public function store() {
        $groupModel = new GroupModel();
        $groupMappingModel = new GroupMappingModel();
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
            
            if(session()->get('is_admin')){
                $data = [
                    'name' => $this->request->getVar('group_name'),
                    'is_payable' => $this->request->getVar('is_payable'),
                ];
                
                $uses_sub_groups = 0;
                $is_sub_group = 0;
                $master_group_id = $this->request->getVar('group_mapping');
                if($this->request->getVar('uses_sub_groups')){
                    if($this->request->getVar('uses_sub_groups') == 1){
                        $uses_sub_groups = 1;
                    } elseif($this->request->getVar('uses_sub_groups') == 2) {
                        $is_sub_group = 1;
                    }
                }
                $data += [
                    'uses_sub_groups' => $uses_sub_groups,
                    'is_sub_group' => $is_sub_group,
                    ];
                
                if($this->request->getVar('is_payable')){
                    $data += [ 
                        'payable_amount' => $this->request->getVar('payable_amount'),
                    ];
                } // for now we'll leave any old data here rather than resetting this to 0.
            } else { //not admin, must be sub group
                $is_sub_group = 1;
                $master_group_id = session()->get('group_id');
                $data = [
                    'name' => $this->request->getVar('group_name'),
                    'is_payable' => 0,
                    'payable_amount' => 0,
                    'uses_sub_groups' => 0,
                    'is_sub_group' => 1,
                ];
            }
            
            $groupModel->insert($data);
            $group_id = $groupModel->where('name', $this->request->getVar('group_name'))->first();   //  $groupModel->select('id')->where ...
            
            
            // If it is a sub group, add the mapping in now.
            if($is_sub_group == 1){
                $mappingData = [
                    'group_id' =>  $master_group_id,
                    'sub_group_id' => $group_id['id']
                    ];
                
               $groupMappingModel->insert($mappingData);
            }

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
            $groupModel = new GroupModel();
            $data['groups'] = $groupModel->where('uses_sub_groups', '1')->findAll();
            
            echo view('templates/header');
            echo view('add_group', $data);
            echo view('templates/footer');
        
        }
        

    }

    // show single group
    public function singleGroup($id = null){
        helper(['form']);
        $groupModel = new GroupModel();
        $groupMappingModel = new GroupMappingModel();
        //are you admin
        if(!session()->get('is_admin')){ 
            //is it your group
            if(session()->get('group_id') !== $id){
                //is it one of your sub groups
                $subGroups = $groupMappingModel->where('group_id',session()->get('group_id'))->findColumn('sub_group_id');
                $access = false;
                foreach($subGroups as $subGroup){
                    if($subGroup == $id) { $access = true;}
                }
                if($access == false){
                    return $this->response->redirect(site_url('/groups'));
                }
            }
            
        }
        
        $data['groups'] = $groupModel->where('uses_sub_groups', '1')->findAll();
        $data['group_obj'] = $groupModel->where('id', $id)->first();
        if($data['group_obj']['is_sub_group']) {
            $mapping = $groupMappingModel->where('sub_group_id',$id)->first();
            $data['parent_group'] = $groupModel->where('id',$mapping['group_id'])->first();
        }
        
        if(session()->get('is_admin')){ 
            echo view('templates/header');
        } else {
            echo view('templates/header-group');
        }
        echo view('single-group', $data);
        echo view('templates/footer');
    }

    // update group data
    public function update(){
        $groupModel = new GroupModel();
        $session = session();
        $id = $this->request->getVar('id');
        $groupMappingModel = new GroupMappingModel();
        
    //can I edit
        //are you admin
        if(!session()->get('is_admin')){ 
            //is it your group
            if(session()->get('group_id') !== $id){
                //is it one of your sub groups
                $subGroups = $groupMappingModel->where('group_id',session()->get('group_id'))->findColumn('sub_group_id');
                $access = false;
                foreach($subGroups as $subGroup){
                    if($subGroup == $id) { $access = true;}
                }
                if($access == false){
                    return $this->response->redirect(site_url('/groups'));
                }
            }
        }

        
        // admin or group group - name only
        $data = [    
            'name' => $this->request->getVar('name'),
        ];
        
        // admin + is_payable and if payable then amount
        if(session()->get('is_admin')) {
            
            //if it is a parent we have the sub-group and charge settings
            $uses_sub_groups = 0;
            $is_sub_group = 0;
            $master_group_id = $this->request->getVar('group_mapping');
            if($this->request->getVar('uses_sub_groups')){
                if($this->request->getVar('uses_sub_groups') == 1){
                    $uses_sub_groups = 1;
                } elseif($this->request->getVar('uses_sub_groups') == 2) {
                    $is_sub_group = 1;
                }
            }
            $data += [
                'uses_sub_groups' => $uses_sub_groups,
                'is_sub_group' => $is_sub_group,
                'is_payable' => $this->request->getVar('is_payable'),
            ];
        
            if($this->request->getVar('is_payable')){
                $data += [ 
                    'payable_amount' => $this->request->getVar('payable_amount'),
                ];
            } // for now we'll leave any old data here rather than resetting this to 0.
        }

        $groupModel->update($id, $data);
        $session->setFlashdata('msg', 'Group updated.');
        
        
        // If it is a sub group, add the mapping in now.
        if($is_sub_group == 1){
            $mappingData = [
                'group_id' =>  $master_group_id,
                'sub_group_id' => $id
            ];
            
            //is there already a row to update
            $mapping = $groupMappingModel->where('sub_group_id',$id)->first();
            if(!$mapping['id'] || $mapping['id'] == null){
                $groupMappingModel->insert($mappingData);
            } else {
                $groupMappingModel->update($mapping['id'], $mappingData);
            }
        }
        
        
        return $this->response->redirect(site_url('/groups'));
    }
 
    // delete group
    public function delete($id = null){
        $groupModel = new GroupModel();
        $groupMappingModel = new GroupMappingModel();
        $userModel = new UserModel();
        $session = session();
        
        
    //can I delete
        //are you admin
        if(!session()->get('is_admin')){ 
            //is it your group
            if(session()->get('group_id') !== $id){
                //is it one of your sub groups
                $subGroups = $groupMappingModel->where('group_id',session()->get('group_id'))->findColumn('sub_group_id');
                $access = false;
                foreach($subGroups as $subGroup){
                    if($subGroup == $id) { $access = true;}
                }
                if($access == false){
                    return $this->response->redirect(site_url('/groups'));
                }
            }
        }
        

        $data['users'] = $userModel->where('group_id', $id)->delete();
                
        $data['groups'] = $groupModel->where('id', $id)->delete($id);
        $session->setFlashdata('msg', 'Group deleted.');
        return $this->response->redirect(site_url('/groups'));
    }    
}
?>