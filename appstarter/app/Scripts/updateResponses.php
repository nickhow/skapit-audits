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
$sql_select = "SELECT DISTINCT id FROM audits WHERE `status` IN ('complete','reviewed','reviewing')";
$result = mysqli_query($conn, $sql_select);

while ($row = mysqli_fetch_row($result)){
    $sql_insert = "INSERT INTO responses (audit_id, question_id, answer_id, suggested_score_ba, suggested_score_abta, custom_answer) VALUES('$row[0]','129','10002','0','0','')";
    if(mysqli_query($conn, $sql_insert)){
        echo "Insert success for id: ".$row[0]."\n";
    } else {
        echo "Failed to insert for id: ".$row[0]."\n";
    }
}




?>