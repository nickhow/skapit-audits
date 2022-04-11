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
                <img src="<?= site_url() ?>/images/ski-api-technologies.png" alt="" width="200">
            </div>

            <div class="col-8">
            <div class="row">
                <div class="col-12">
                    <p style="font-size: xx-large;">Property Details</p>
                </div>
                <div class="col-12">
                    <table style="width:100%; text-align:center;">
                        <thead>
                            <tr>
                                <th>Property Name</th>
                                <th>Resort</th>
                                <th>Country</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $account['accommodation_name']; ?></td>
                                <td><?php echo $account['resort']; ?></td>
                                <td><?php echo $account['country']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="row p-4">
            <div class="col-12">
                <p style="font-size: xx-large;">Audit Results</p>
            </div>
            <div>
                <div class="col-12">
                    <p>Audit Reviewed Date: <?php echo date('Y-m-d', strtotime($audit['audited_date'])); ?></p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-10">
                    
                    <table style="width:100%; text-align:center;">
                        <thead class="border-bottom">
                            <tr>
                                <th></th>
                                <th>Result</th>
                                <th>Expiry Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><b>British Airways</b></td>
                                <td><?php echo strtoupper($audit['result_ba']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($audit['expiry_date_ba'])); ?></td>
                            </tr>
                            <tr>
                                <td><b>ABTA</b></td>
                                <td><?php echo strtoupper($audit['result_abta']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($audit['expiry_date_abta'])); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row p-4">
            <div class="col-12">
                <p style="font-size: xx-large;">Audit Comments</p>
            </div>
            <div>
                <div class="col-12">
                    <p><?php echo ($audit['comment']) ?></p>
                </div>
            </div>
        </div>

        <!-- per question -->

        <?php print_r($questions); ?>

        <?php foreach($questions as $question) : ?>
        <div class="row p-4">
            <div class="col-12">
                <div class="form-group"> 
                    <label class="pb-2">
                        <b><?php // $responses['question'] ?></b>
                    </label>
                    <input type="text" class="form-control" value="<?php // $response['answers']['en_ans'] ?>" disabled="">
                    <div class="text-secondary">
                        <small>
                            <b><i>Feedback: </i></b><i><?php // $response['response']['comment'] ?></i>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- end of per question -->
    </div>
</body>
</html>