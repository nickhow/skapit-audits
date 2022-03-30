<?php 
namespace App\Controllers;
use App\Models\UploadModel;
use CodeIgniter\Controller;
helper('filesystem');

class UploadController extends Controller
{
    // delete a file
    public function deleteFile($filename = null){
        $uploadModel = new UploadModel();
        if($filename == null){
            return;
        }
        //check the file exists
        $dbfile = $uploadModel->getWhere(['file_name' => $filename], 1, 0)->getRow();
        if(!$dbfile->audit_id){
            return;
        }
        
        //delete the file from the dir and db.
        $uploadModel->deleteFile($dbfile);
    
    }
}
?>