<?php
session_start();
//error_reporting(E_ALL);
ini_set('display_errors', '');
//$con = new mysqli("localhost", "root", "", "queue1live_db");
$con = new mysqli("localhost", "root", "", "queueone_devqa");

if ($con->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

//$siteUrl = "http://localhost/queue1/development/";
$siteUrl = "http://localhost/queue1-v2/";
//$domainUrl = "https://queue1.com/devsite/";
//error_reporting(1);
date_default_timezone_set('Africa/Dar_es_Salaam');

ini_set('max_input_vars', 3000);

if($_SESSION['accountId'] == 3)
{
   $hotelsArr = [29624 => '4555475120c8484eaa-88b9-11ec-b'];
}
else
{
   $hotelsArr = [21866 => '3676523932f9fc21cc-8225-11eb-b', 21930 => '44036096186cee9400-d661-11eb-b'];
} 

include('function.php');

//server should keep session data for AT LEAST 1 hour
//ini_set('session.gc_maxlifetime', 9000);

//each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(9000);

//Indicate the original media type of the resource 
header('Content-Type: text/html; charset=utf-8');



?>