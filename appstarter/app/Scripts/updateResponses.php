<?php

$servername = "localhost";
$username = "skiapitechnologi";
$password = "pIkQoMT9X.8u";
$dbname = "skiapite_partners";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//get the unique IDs from the responses table
$sql_select = "SELECT audit_id FROM `audits` WHERE `status` IN ('reviewed','reviewing','complete')";
$ids = mysqli_query($conn, $sql_select);

//generate the basic response to satisfy the new question
foreach($ids as $id){
    $sql_insert = "INSERT INTO responses (audit_id, question_id, answer_id, suggested_score_ba, suggested_score_abta, custom_answer) VALUES('$id',129,10002,0,0,'')";
    mysqli_query($conn, $sql_insert);
}
?>