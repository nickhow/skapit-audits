<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
  <link rel="shortcut icon" type="image/png" href="<?= site_url() ?>/public/images/skapit.png"/>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<body>
    <div class="container">
        <div class="row">
            <div class="col text-center">
                <img src="<?= site_url() ?>/public/images/ski-api-technologies.png" alt="" width="200">
            </div>
        </div>
        
        <div class="row p-4">
            <div class="col-12">
                <h2>Property Details</h2>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col">
                        <b>Property Name</b>
                    </div>
                    <div class="col">
                        <b>Resort</b>
                    </div>
                    <div class="col">
                        <b>Country</b>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <?php echo $account['accommodation_name']; ?>
                    </div>
                    <div class="col">
                        <?php echo $account['resort']; ?>
                    </div>
                    <div class="col">
                        <?php echo $account['country']; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row p-4">
            <div class="col-12">
                <h2>Audit Results</h2>
            </div>
            <div>
                <div class="col-12">
                    <p>Audit Reviewed Date: <?php echo date('Y-m-d', strtotime($audit['audited_date'])); ?></p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-10">
                    <div class="row text-center border-bottom border-bark p-3">
                        <div class="col"></div>
                        <div class="col"><b>Result</b></div>
                        <div class="col"><b>Expiry Date</b></div>
                    </div>
                    <div class="row text-center p-3">
                        <div class="col"><b>British Airways</b></div>
                        <div class="col"><?php echo strtoupper($audit['result_ba']); ?></div>
                        <div class="col"><?php echo date('Y-m-d', strtotime($audit['expiry_date_ba'])); ?></div>
                    </div>
                    <div class="row text-center p-3">
                        <div class="col"><b>ABTA</b></div>
                        <div class="col"><?php echo strtoupper($audit['result_abta']); ?></div>
                        <div class="col"><?php echo date('Y-m-d', strtotime($audit['expiry_date_abta'])); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row p-4">
            <div class="col-12">
                <H3>Audit Comments</H3>
            </div>
            <div>
                <div class="col-12">
                    <p><?php echo ($audit['comment']) ?></p>
                </div>
            </div>
    </div>
</body>
</html>