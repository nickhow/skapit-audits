<!doctype html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Auditing System</title>
  <link rel="shortcut icon" type="image/png" href="<?= site_url() ?>/images/skapit.png"/>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg  navbar-dark bg-dark" style="z-index:999">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url('/audits')?>">
              <img src="<?= site_url() ?>/images/skapit-logo.png" alt="SKAPIT Logo" width="" height="40" class="d-inline-block align-text-top">
              
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
              <ul class="navbar-nav ">
                <li class="nav-item">
                  <a class="nav-link" href="<?= site_url('/audits') ?>">Audits</a>
                </li>
                 <li class="nav-item px-3">
                  <a class="nav-link btn-outline-danger" href="<?= site_url('/signout') ?>">Sign out</a>
                </li>
              </ul>
            </div>
        </div>
    </nav>