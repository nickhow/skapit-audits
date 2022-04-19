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

        <table>
            <tr>
                <td rowspan="5"><img src="<?= site_url() ?>/images/ski-api-technologies.png" alt="" width="200"></td>
            </tr>
            <tr>
                <td style="width:70%"><p style="font-size: xx-large;"><?php echo $account['accommodation_name'] ."</p> <p>" .$account['resort'].", ".$account['country']; ?></p></td>
            <tr>
            <tr>
                <td>
                    <table style="width:100%;">
                        <thead class="border-bottom">
                            <tr>
                                <th>Standard</th>
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
                </td>
            </tr>
            <tr>
                <td>
                    <p><?php echo ($audit['comment']) ?></p>
                </td>
            </tr>
        </table>

        <div class="row p-2">
            <table style="width:100%;">
                <tr>
                    <td style="width:70%">
                        <p style="font-size: xx-large;">Audit</p>
                    </td>
                    <td>
                        <p class="py-2" style="text-align: right!important;">Reviewed Date: <?php echo date('d-m-Y', strtotime($audit['audited_date'])); ?></p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- per question -->

        <?php //  print_r( $questions ); ?>
       
        <?php foreach($questions as $question) : ?>
        <div class="row p-2">
            <div class="col-12">
                <div class="form-group <?php if($question['highlight'] != "none"){ echo "pt-2"; } ?>" style="<? if($question['highlight'] == "fail"){ echo "background-color: rgba(141, 0, 0, 0.5);"; } elseif($question['highlight'] == "pass") { echo "background-color: rgba(0, 141, 11, 0.5);"; }  ?>"> 
                    <label class="pb-2">
                        <b><?php echo $question['question'] ?></b>
                    </label>
                    <input type="text" class="form-control" value="<?php echo $question['answer'] ?>" disabled="">
                    <div class="text-secondary <? if($question['comment'] != ""){ echo "my-2 p-1 alert alert-warning";} ?>">
                        <small>
                            <b><i>Feedback: </i></b><i><?php echo $question['comment'] ?></i>
                        </small>
                    </div>
                    <div>
                        <?php 
                            if($question['highlight'] == "fail"){
                                echo "<b>This answer contributes towards a failure.</b>";
                            } elseif($question['highlight'] == "pass"){
                                echo "<b>This answer provides a redemption from a previous failure.</b>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- end of per question -->
    </div>
</body>
</html>

