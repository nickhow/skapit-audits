<?php 
namespace App\Controllers;
use App\Models\EmailModel;
use CodeIgniter\Controller;

class EmailController extends Controller
{
    // Get Email HTML
    public function getEmailHtml($type,$lang) {
        $emailModel = new EmailModel();
        echo ($emailModel->getEmailHtml($type,$lang));
    }
    
}
?>