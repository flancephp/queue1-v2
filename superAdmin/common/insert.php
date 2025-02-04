<?php
session_start();
include('../../inc/dbConfig.php'); //connection details
$data = array("name"=>'queue1',
        "email"=>'queue1@gmail.com',
        "username"=>'queue1',
        "password"=>'queue1',
        "status"=>'1'
    );

$sql = "INSERT INTO `tbl_superadmin` SET 
`name` = '".$data['name']."',
`email` = '".$data['email']."',
`username` = '".$data['username']."',
`password` = '".md5($data['password'])."',
`status` = '".$data['status']."' ";
mysqli_query($con, $sql);

?>