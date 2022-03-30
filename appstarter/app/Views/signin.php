<!doctype html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Ski API Technologies - Health and Safety Audit System</title>
  <link rel="shortcut icon" type="image/png" href="<?= site_url() ?>/public/images/skapit.png"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
<style>
    @media only screen and (max-width: 768px) {
        .mobbg{
            position: relative;
            background-image: url(/images/login.jpg);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .formbg{
            background: rgba(255, 255, 255, 0.8)!important;
        }
    }
</style>
</head>

  <body class="bg-white h-100">
    <div class="container-fluid p-0 mobbg" >
        <div class="row m-0 vh-100">
            <div class="d-none d-md-block col-8 p-0" style="position:relative; background-image: url('<?php site_url();?>/images/login.jpg');  background-position: center; background-repeat: no-repeat; background-size: cover;">

                    <div class="col-10 mx-auto rounded pt-2" style=" background: rgba(255, 255, 255, 0.5); position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        
                        <div class="p-2 text-center">
                            <h1 class="fs-1">Health & Safety <br> <span class="fs-4">made simple with SKAPIT</span></h1>
                            <div class="row p-2">
                                <div class="col">
                                    <i class="fas fa-stopwatch fa-2x"></i>
                                </div>
                                <div class="col">
                                    <i class="fas fa-language fa-2x"></i>
                                </div>
                                <div class="col">
                                    <i class="fas fa-globe fa-2x"></i>
                                </div>
                            </div>
                            <div class="row p-2">
                                <div class="col">
                                    <p>Easy and quick to complete audit form</p>
                                </div>
                                <div class="col">
                                    <p>Available in 5 languages</p>
                                </div>
                                <div class="col">
                                    <p>Set to meet International Health and Safety Standards</p>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>

            </div>
            <div class="col-12 col-md-4 align-self-center bg-white p-0 pt-2 pt-md-0 pb-4 pb-md-0 formbg">
                
                <div class="col-10 mx-auto pt-2 pb-4" >
                
                    <div class="d-md-none p-2 text-center">
                        <h1 class="fs-1">Health & Safety <br> <span class="fs-4">made simple with SKAPIT</span></h1>
                    </div>
                    
                    <div class="justify-content-md-center pb-2">
                        <img src="<?= site_url() ?>/images/ski-api-technologies.png" class="mx-auto d-block" alt="Ski API Technologies Logo" style="width:40%; min-width:50px;">
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
    </div>
  </body>
</html>