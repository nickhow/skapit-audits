<?php 
namespace App\Controllers;
use App\Models\QuestionModel;
use CodeIgniter\Controller;

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
        $data['question_obj'] = $questionModel->where('id', $id)->first();
        
        echo view('templates/header');
        echo view('single-question', $data);
        echo view('templates/footer');
    }

    // update question data
    public function update(){
        $questionModel = new QuestionModel();
        $session = session();
        $id = $this->request->getVar('id');
        $data = [
            'question' => $this->request->getVar('question'),
            'en' => $this->request->getVar('en'),
            'es' => $this->request->getVar('es'),
            'de' => $this->request->getVar('de'),
            'fr' => $this->request->getVar('fr'),
            'it' => $this->request->getVar('it'),
        ];
        $questionModel->update($id, $data);
        $session->setFlashdata('msg', 'Question updated.');
        return $this->response->redirect(site_url('/questions'));
    }

}
?>