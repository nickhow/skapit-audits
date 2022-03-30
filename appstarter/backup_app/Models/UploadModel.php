<?php 
namespace App\Models;
use CodeIgniter\Model;
helper('filesystem');

class UploadModel extends Model
{
    protected $table = 'uploads';

    protected $primaryKey = 'id';
    
    protected $allowedFields = ['audit_id', 'file_name','file_type', 'original_name', 'description'];
    
        
    function uploadFile($file=null, $id=null, $description=''){
            // does a file of this description already exist - remove it...
            if($description !== ''){
                $previousFile = $this->getWhere(['description' => $description, 'audit_id' => $id])->getRow();
                if(isset($previousFile->id)){
                    $this->deleteFile($previousFile);
                }
            }
        
            $original_name = $file->getClientName();
            $filename = $file->getRandomName();
            $file->move('uploads/'.$id,$filename);
            $filedata = [
                'audit_id' => $id,   
                'file_name' =>  $file->getName(),   
                'original_name'  => $original_name,
                'file_type'  => $file->getClientMimeType(),
                'description' => $description,
            ];
            $this->insert($filedata);
    }
    
    
    function deleteFile($dbfile = null){

        $path = ("uploads/".$dbfile->audit_id."/".$dbfile->file_name);
    
        if(unlink($path)){
            echo "file deleted";
        } else {
            return;
        };  
        
        //remove from the db
        $this->delete(['id' => $dbfile->id]);
    
    }
    
    function deleteAllAuditFiles($audit_id = null){
        //get all the files for this audit ...
        $file_id = '';
        //get the file info
        $file = $this->where(['id' => $file_id]);
        
        //delete the file
        delete_files('./path/to/directory/',true);
        //remove from the db
        $this->delete(['id' => $file_id]);
    }
    
}

?>