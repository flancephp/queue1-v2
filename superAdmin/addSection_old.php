<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link rel="shortcut icon" href="favicon_16.ico"/>
  <link rel="bookmark" href="favicon_16.ico"/>
  <!-- site css -->
  <link rel="stylesheet" href="dist/css/site.min.css">
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,800,700,400italic,600italic,700italic,800italic,300italic" rel="stylesheet" type="text/css">
  <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="dist/js/site.min.js"></script>

  <style>
  	body {
      font: 14px/1 Arial, sans-serif;
      background-color:#f1f2f6 ;
    }
  	.full-panel {
		border: 1px solid grey;
		border-radius: 4px;
	}
	.panel-title {
		border-bottom: 1px solid grey;
	}
	.panel-title h5 {
		font-weight: 700;
		padding-left: 10px;

	}
	td {
      padding: 25px;
    }
    label {
      padding: 0px 40px 0px 0px;
    }
    .button-group {
        margin: 20px 0px 20px 0px;
    }
  </style>
</head>
<body>
	<?php require_once('header.php'); ?>

	<div class="container-fluid">
	  <div class="row">
	    <div class="col-md-2 col-sm-2">
	      <?php require_once('nav.php');?>
	    </div>
	    <div class="col-md-10 col-sm-10">
	    	<div class="full-panel">
				<div class="panel-title">
					<h5>Add Section</h5>
				</div>
				<div style="background-color:#fff;">
					<table class="tb-box">
				        <tr>
				          <td>Section : </td>
				          <td>
				            <input type="text" name="language" id="language">
				          </td>
				        </tr>
				        <tr>
				          <td>Default : </td>
				          <td>
				            <input type="radio" name="languageType" id="languageType">
				            <label>Yes</label>
				            <input type="radio" name="languageType" id="languageType">
				            <label>No</label>
				          </td>
				        </tr>

				        
				          <tr class="button-group">
				            <td>
				              <button class="btn btn-info fl-right" onclick="window.location.href= 'manageSection.php' ">Add</button>
				            </td>
				            <td>
				              <button class="btn btn-info fl-left"  onclick="window.location.href= 'manageSection.php' ">Back</button>
				            </td>
				          </tr>  
     				</table>
				</div>
			</div>
	    </div>
	  </div>
	</div>

</body>
</html>