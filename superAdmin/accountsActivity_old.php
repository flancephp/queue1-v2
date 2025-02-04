<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link rel="shortcut icon" href="favicon_16.ico"/>
	<link rel="bookmark" href="favicon_16.ico"/>
	<!-- site css -->
	<!--   <link rel="stylesheet" href="dist/css/site.min.css"> -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,800,700,400italic,600italic,700italic,800italic,300italic" rel="stylesheet" type="text/css">
	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'> 
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

	<script type="text/javascript" src="dist/js/site.min.js"></script>

	<style>
		
	* {
      box-sizing: border-box;
    }
     body {
      background-color:#f1f2f6;
      overflow:inherit;
    }
    .date-form {
		padding: 0px 0px 15px 0px;
	}
 		table {
      border-collapse: collapse;
      background: white;
      width: 100%;
    }
    th {
      position: sticky;
      background-color: #3bafda;
      top: 0;
      z-index: 1;
    }
    th, td {
      padding: 10px 6px;
    }
    .bc-color{
    	background-color: #3bafda;
      color: #fff;
    }
	</style>
</head>
<body>
	<?php require_once('header.php'); ?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-2 col-sm-3 col-lg-2">
				<?php require_once('nav.php'); ?>
			</div>
			<div class="col-md-10 col-sm-9 col-lg-10">
				<div class="full-panel">
					<div class="date-form">
						<form action="" id="frm" name="frm" method="get">
						From Date: <input type="date" size="10" class="datepicker" placeholder="Click here" name="fromDate" id="fromDate" autocomplete="off" value="">&nbsp;&nbsp; To Date: <input size="10" autocomplete="off" type="date" class="datepicker" name="toDate" id="toDate" placeholder="Click here" value="">&nbsp;&nbsp;
						<img  src="dist/img/refresh.png" width="26" height="26" onClick="submitFrm(this);" style="cursor: pointer;" title="Search">&nbsp;&nbsp;
						
						<button type="button" class=" btn-info" onClick="window.location.href='accountsActivity.php';">Clear Search</button>
						</form>
					</div>

					<div class="table-responsive" style="height: 470px; overflow: scroll;">

			    <table class="table table-bordered">

			      <tr class="bc-color">
			        <th scope="col">Serial no.</th>
					     <th scope="col">Account no.</th>
					     <th scope="col">Account Name</th>
					     <th scope="col">Total Task No.</th>
					     <th scope="col">Issue In</th>
					     <th scope="col">Issue Out</th>
					     <th scope="col">S.Take</th>
					     <th scope="col">Outlet S.Take</th>
					     <th scope="col">Items No.</th>
					     <th scope="col">Efficiency</th>
					     <th scope="col">SignedIn Users</th>
			      </tr>

			      <tr>
		          <td>Serial no.</td>
		          <td>Account no.</td>
		          <td>Account Name</td>
		          <td>Total Task No.</td>
		          <td>Issue In</td>
		          <td>Issue Out</td>
		          <td>S.Take</td>
		          <td>Outlet S.Take</td>
		          <td>Items No.</td>
		          <td>Efficiency</td>
		          <td>SignedIn Users</td>
		        </tr>

			  </table>
			  
			</div>

				</div>
			</div>
		</div>
	</div>

</body>
</html>