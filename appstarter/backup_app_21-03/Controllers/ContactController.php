<?php 
namespace App\Controllers;
use App\Models\ContactModel;
use CodeIgniter\Controller;

class ContactController extends Controller
{
    // insert data
    public function store() {
        $contactModel = new ContactModel();
        $audit_id = $this->request->getVar('audit_id');
        $comment = $this->request->getVar('comment');
        $account_id = $this->request->getVar('account_id');
        $is_admin = 0;
        $contactModel->addComment($audit_id,$comment,$account_id,$is_admin);
        return;
    }
    
    public function storeAdmin() {
        $contactModel = new ContactModel();
        $audit_id = $this->request->getVar('audit_id');
        $comment = $this->request->getVar('comment');
        $account_id = $this->request->getVar('account_id');
        $is_admin = 1;
        $contactModel->addComment($audit_id,$comment,$account_id,$is_admin);
        return;
    }
    
 
    // delete group
    public function delete(){
        $contactModel = new ContactModel();
        $id = $this->request->getVar('id');
        $contactModel->where('id', $id)->delete($id);
        return;
    }    
}
?>