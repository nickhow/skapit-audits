<?php

$servername = "localhost";
$username = "skiapite_dev";
$password = "fx6QhvFWa8Sya4j";
$dbname = "skiapite_dev_audit";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//get the unique IDs from the responses table
$sql_select = "SELECT id FROM audits WHERE `status` IN ('complete','reviewed','reviewing')";
if($ids = mysqli_query($conn, $sql_select)){
    //generate the basic response to satisfy the new question
    foreach($ids as $id){
        $sql_insert = "INSERT INTO responses (audit_id, question_id, answer_id, suggested_score_ba, suggested_score_abta, custom_answer) VALUES($id,129,10002,0,0,'')";
        mysqli_query($conn, $sql_insert);
    }
} else {
    echo "failed to get IDs";
}


?>