<!doctype html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Ski API Technologies - Health and Safety Audit System</title>
  <link rel="shortcut icon" type="image/png" href="<?= site_url() ?>/public/images/skapit.png"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

  <body class="bg-light">
    <div class="container">
        <div class="row justify-content-md-center ">
            <div class="col-10 col-md-6 col-lg-4 position-absolute top-50 start-50 translate-middle bg-white rounded pt-2 pb-4">
                
                <div class="justify-content-md-center pb-2">
                    <img src="<?= site_url() ?>/images/ski-api-technologies.png" class="mx-auto d-block" alt="Ski API Technologies Logo" style="width:20%; min-width:50px;">
                </div>
                

                <?php if(session()->getFlashdata('msg')):?>
                    <div class="alert alert-warning">
                       <?= session()->getFlashdata('msg') ?>
                    </div>
                <?php endif;?>
                <form action="<?php echo base_url(); ?>/SigninController/loginAuth" method="post">
                    <div class="form-floating mb-3">
                      <input type="username" class="form-control" name="username" id="username" placeholder="Username" value="<?= set_value('username') ?>">
                      <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                      <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                      <label for="password">Password</label>
                    </div>
                    <div class="d-grid">
                         <button type="submit" class="btn btn-primary">Sign in</button>
                    </div>     
                </form>
            </div>
              
        </div>
    </div>
  </body>
</html>