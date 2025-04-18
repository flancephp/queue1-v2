<?php
include('inc/dbConfig.php'); //connection details


phpinfo();die;



$qry = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='queue2_10308048';";

$sqlQry = mysqli_query($con, $qry);

while ($res = mysqli_fetch_array($sqlQry)) {

    $sql = "ALTER TABLE " . $res['TABLE_NAME'] . " ADD `newOldId` INT NOT NULL;";


   // mysqli_query($con, $sql);
}
