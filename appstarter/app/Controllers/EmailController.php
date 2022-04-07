<?php 
namespace App\Controllers;
use App\Models\EmailModel;
use CodeIgniter\Controller;

class EmailController extends Controller
{
    // Get Email HTML
    public function getEmailHtml($type,$lang) {
        $emailModel = new EmailModel();
        return ($emailModel->getEmailHtml($type,$lang));
    }
    
}
?>