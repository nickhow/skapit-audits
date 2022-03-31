<?php

namespace App\Controllers;

class Migrate extends \CodeIgniter\Controller
{
    public function index()
    {
        $migrate = \Config\Services::migrations();

        try {
            $migrate->latest();
        } catch (\Throwable $e) {
            // Do something with the error here...
                    echo "<pre>";
                    var_dump($e);
                    echo "</pre>"
        }
    }
}
?>