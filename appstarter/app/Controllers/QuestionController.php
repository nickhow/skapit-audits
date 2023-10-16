<?php 
namespace App\Controllers;
use App\Models\QuestionModel;
use CodeIgniter\Controller;
use App\Models\UploadModel;


class QuestionController extends Controller
{
    // show question list
    public function index(){
        $questionModel = new QuestionModel();

        $data['questions'] = $questionModel->orderBy('question_number', 'ASC')->findAll();
        
        echo view('templates/header');
        echo view('view_questions', $data);
        echo view('templates/footer');
    }

    // show single question
    public function singleQuestion($id = null){
        $questionModel = new QuestionModel();
        $uploadModel = new UploadModel();
        $helper_location = 'helper_images/'.$id;

        $data['question_obj'] = $questionModel->where('id', $id)->first();
        $data['file'] = $uploadModel->where('audit_id',$helper_location)->first();


        echo view('templates/header');
        echo view('single-question', $data);
        echo view('templates/footer');
    }

    // update question data
    public function update(){
        $questionModel = new QuestionModel();
        $uploadModel = new UploadModel();
        $session = session();
        $has_helper = 0;

        $id = $this->request->getVar('id');
        $file = $this->request->getFile('helper_image');

        if($file->isValid()){
            $location = 'helper_images/'.$id;
            $uploadModel->uploadFile($file, $location);
            $has_helper = 1;
        }

        if($this->request->getVar('helper_image_exists')){
            $has_helper = 1;
        }

        if($this->request->getVar('helper_url') != ""){
            $has_helper = 1;
        }

       
        $data = [
            'question' => $this->request->getVar('question'),
            'en' => $this->request->getVar('en'),
            'es' => $this->request->getVar('es'),
            'de' => $this->request->getVar('de'),
            'fr' => $this->request->getVar('fr'),
            'it' => $this->request->getVar('it'),
            'helper_url' => $this->request->getVar('helper_url'),
            'has_helper' => $has_helper,
        ];
        $questionModel->update($id, $data);
        $session->setFlashdata('msg', 'Question updated.');
        return $this->response->redirect(site_url('/questions'));
    }

}
?>