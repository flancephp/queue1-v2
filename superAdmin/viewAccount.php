<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>queue1.com</title>
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

		body{

			background-color:#f1f2f6;

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
		.top-section {
			padding: 20px;
			background: #D9D9D9;
		}
		.top-section p {
			font-weight: 700;
		}
		.bottom-section {
			padding: 20px;
		}
		.bottom-section p {
			font-weight: 700;
		}
		.top-left-section {
			background-color:#fff; border-radius: 7px;
		}
		.show-account-no {
			padding-top: 10px; padding-bottom: 10px;
		}
		.top-left-section img {
			border-radius: 10px; width: 200px;
		}
		.show-account-name {
			margin-top: 10px; margin-bottom: 10px;
		}
		.show-account-status {
			color : Green; padding-bottom: 10px;
		}
		.account-info-txt {
			color: red;

		}
		.title-title-val {
			display: inline-flex;
			margin-bottom: 6px;
			grid-gap: 10px;
		}
		.title {
			padding: 7px;
			border-radius: 7px;
			border: 1px solid #BCBCBC;
			background: #BCBCBC;
			color: #000;
			width: 150px;
		}
		.title-val {
			padding: 7px;
			border-radius: 7px;
			border: 1px solid #fff;
			background: #fff;
			color: #000;
			width: 150px;
		}
		.bottom-left-section {
			background: #D9D9D9;
		    padding: 16px;
		    border-radius: 7px;
		}
		.bottom-right-section {
			background: #D9D9D9;
		    padding: 16px;
		    border-radius: 7px;
		}
	</style>
</head>
<body>

	<?php require_once('header.php'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-2 col-sm-2">
				<?php require_once('nav.php'); ?>
			</div>

			<div class="col-md-10 col-sm-10">
				<div class="full-panel">
					<div class="panel-title">
						<h5>Accounts Detail</h5>
					</div>

					<div class="account-info-txt">
						<h5>Account info</h5>
					</div>

					<div class="top-section">
						<div class="row">

							<div class="col-md-4 col-sm-4">
								<div class="top-left-section text-center">
									<p class="show-account-no">00001</p>
									<img src="dist/img/our-zanzibar.jpg">
									<p class="show-account-name">Our Zanzibar</p>
									<p class="show-account-status">Active</p>
								</div>
							</div>

							<div class="col-md-4 col-sm-4">
								<div class="top-middle-section">

									<div class="title-title-val">
										<div class="title">
											<h5>Client Name</h5>
										</div>
										<div class="title-val">
											<h5>Saleh Diab</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Joined Date</h5>
										</div>
										<div class="title-val">
											<h5>12/01/2022</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Account Type</h5>
										</div>
										<div class="title-val">
											<h5>Free</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Users No.</h5>
										</div>
										<div class="title-val">
											<h5>10</h5>
										</div>
									</div>

								</div>
							</div>


							<div class="col-md-4 col-sm-4">
								<div class="top-right-section">

									<div class="title-title-val">
										<div class="title">
											<h5>Total Activity</h5>
										</div>
										<div class="title-val">
											<h5>1 year 8 month</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Monthly Fee</h5>
										</div>
										<div class="title-val">
											<h5>0 $</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Payment Type</h5>
										</div>
										<div class="title-val">
											<h5>-----</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Balance</h5>
										</div>
										<div class="title-val">
											<h5>Open</h5>
										</div>
									</div>
									
								</div>
							</div>


						</div>

					</div>
							
				<!-- bottom-section --->
					<div class="bottom-section">
						<div class="row">

							<div class="col-md-8 col-sm-8">
								<div class="account-info-txt">
									<h5>Account owner info</h5>
								</div>

								<div class="bottom-left-section">

									<div class="title-title-val">
										<div class="title">
											<h5>Name</h5>
										</div>
										<div class="title-val">
											<h5>Saleh Diab</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Email ID</h5>
										</div>
										<div class="title-val">
											<h5 style="overflow-wrap: anywhere;">saleh1@gmail.com</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Phone</h5>
										</div>
										<div class="title-val">
											<h5>+012368426896</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Tax ID</h5>
										</div>
										<div class="title-val">
											<h5>1463594228</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Address Street</h5>
										</div>
										<div class="title-val">
											<h5>Main street 48</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>City</h5>
										</div>
										<div class="title-val">
											<h5>Tama</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Country</h5>
										</div>
										<div class="title-val">
											<h5>Israel</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Zip</h5>
										</div>
										<div class="title-val">
											<h5>141104</h5>
										</div>
									</div>

								</div>
							</div>

							
							<div class="col-md-4 col-sm-4">
								<div class="account-info-txt">
								   <h5>Section</h5>
							    </div>
								<div class="bottom-right-section">

									<div class="title-title-val">
										<div class="title">
											<h5>Queue1 System</h5>
										</div>
										<div class="title-val">
											<h5>Yes</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Multi User</h5>
										</div>
										<div class="title-val">
											<h5>Yes</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Request User</h5>
										</div>
										<div class="title-val">
											<h5>Yes</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Mobile User</h5>
										</div>
										<div class="title-val">
											<h5>Yes</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Revenue Center</h5>
										</div>
										<div class="title-val">
											<h5>Yes</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Accounts</h5>
										</div>
										<div class="title-val">
											<h5>Yes</h5>
										</div>
									</div>

									<div class="title-title-val">
										<div class="title">
											<h5>Max no. of Users</h5>
										</div>
										<div class="title-val">
											<h5>6</h5>
										</div>
									</div>
									
								</div>
							</div>


						</div>

					</div>


				</div>
			</div>

		</div>
	</div>

</body>
</html>






