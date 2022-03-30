<?php

namespace App\Controllers;
use CodeIgniter\Controller;

use App\Models\EmailModel;

class PDF extends Controller {

    public function index() 
	{
        return view('pdf_index');
    }    

    function convertToPdf($html){
        
        $dompdf = new \Dompdf\Dompdf();
        
        $options = $dompdf->getOptions();
        $options->setDefaultFont('Courier');
        $options->setIsRemoteEnabled(true);
        $options->setIsHtml5ParserEnabled(true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        
       // $fileatt = $dompdf->output();
        
        return ($dompdf->output());
        
      //  $emailModel = new EmailModel();
       // $emailModel->pdfEmail($fileatt);
        

        
        //download
        //$dompdf->stream();
    }    

} ?>