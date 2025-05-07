<!doctype html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>simple-audit.com</title>
  <link rel="shortcut icon" type="image/png" href="<?= site_url() ?>/public/images/logo.png"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

  <body class="bg-light">
    <div class="container">
        <div class="row justify-content-md-center ">
            <div class="col-10 col-md-8 bg-white rounded m-3 pt-2 pb-4">
                
                <div class="justify-content-md-center pb-2">
                    <img src="<?= site_url() ?>/public/images/ski-api-technologies.png" class="mx-auto d-block" alt="Ski API Technologies Logo" style="width:20%; min-width:50px;">
                </div>
                
                <div class="col text-center">
                    This health and safety audit has been reviewed and can no longer be edited.
                    <br/>
                    <div class="row p-3">
                        <div class="col-12">
                             <b>Audit result:</b>
                        </div>
                        <div class="col-12">
                             <h2><?php echo "BA: ". ucFirst($audit_obj['result_ba']);?></h2>
                              <h2><?php echo "ABTA: ". ucFirst($audit_obj['result_abta']);?></h2>
                        </div>
                         <p>This result expires on <?php echo  date('d/m/Y', strtotime($audit_obj['expiry_date_ba']));?> for BA, and <?php echo  date('d/m/Y', strtotime($audit_obj['expiry_date_abta']));?> for ABTA.</p>
                    </div>
                   
                </div>
                
            </div>
              
        </div>
    </div>
  </body>
</html>