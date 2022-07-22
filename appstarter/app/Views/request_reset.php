<!doctype html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Ski API Technologies - Health and Safety Audit System</title>
  <link rel="shortcut icon" type="image/png" href="<?= site_url() ?>/images/skapit.png"/>
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
        <div class="row m-0 ">

            <div class="col-12 col-md-4 col-lg-3 align-self-center mx-auto bg-white p-0 pt-2 pt-md-0 pb-4 pb-md-0 formbg">
                
                <div class="col-12 col-md-10 mx-auto text-center pt-2 pb-4" >
                
                    <div class="d-md-none p-2 text-center">
                        <h1 class="fs-1">Reset my password</h1>
                    </div>
                    
                    <div class="justify-content-md-center pb-2">
                        <img src="<?= site_url() ?>/images/ski-api-technologies.png" class="mx-auto d-block" alt="Ski API Technologies Logo" style="width:40%; min-width:50px;">
                    </div>
                    
    
                    <?php if(session()->getFlashdata('msg')):?>
                        <div class="alert alert-warning">
                           <?= session()->getFlashdata('msg') ?>
                        </div>
                    <?php endif;?>
                    <form action="<?php echo base_url(); ?>/SignupController/init_reset" method="post">
                        <div class="form-floating mb-3">
                          <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                          <label for="email">Email</label>
                        </div>
                        <div class="d-grid">
                             <button type="submit" class="btn btn-primary">Request password reset</button>
                        </div>     
                    </form>
                
                </div>
            </div> 
              
        </div>
    </div>
  </body>
</html>