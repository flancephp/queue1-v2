<?php
require_once('../inc/dbConfig.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name=”viewport” content=”width=device-width, initial-scale=1.0”>
	<title></title>
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,800,700,400italic,600italic,700italic,800italic,300italic" rel="stylesheet" type="text/css">
	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'> 
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<!-- <link rel="stylesheet" href="dist/css/site.min.css"> -->
	<link rel="stylesheet" href="dist/css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

	<script type="text/javascript" src="dist/js/site.min.js"></script>
	<script type="text/javascript" src="dist/js/rj.js"></script>
</head>
<body>

<div class="panel-heading">
    <h3 class="title-pannel"> Super Admin</h3>
</div>
<div class="container-fluid">
    <div class="row row-pad">
        <div class="col-md-9 col-sm-9 col-lg-9 fl-left">
            <span class="Q-span"><i><strong>Q</strong></i></span><span class="rest-span"><i>ueue1.com</i></span>
        </div>
        <div class="col-md-3 col-sm-3 col-lg-3">
                <a class="mobile-btn fl-right" id="mobile-btn" onclick="mobileIconBar()">
                    <i class="fa fa-bars"></i>
                </a>
        </div>
    </div>
</div>
