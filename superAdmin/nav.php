<!DOCTYPE html>
<html>
<head>
	<style>
		.home{
			background: #f1f2f6;
			border: 1px solid #ddd;
    		border-radius: 4px 4px 0px 0px;
		}
		.home h3{
			margin: 10px;
			font-size: 14px;
		    color: #434A54;
		    font-weight: 400;

		}
		.nav-tab {
		    background-color: #FFF;
		   	padding: 0px;
		   	list-style: none;
			margin-bottom: 0px;
		    -webkit-box-shadow: 0 1px 1px rgb(0 0 0 / 10%);
		    box-shadow: 0 1px 1px rgb(0 0 0 / 10%);
		    border: 1px solid #ddd;
		    border-radius: 0px;
		}
		.list-group {
		    display: block;
		    padding: 4px 9px;
		    margin-bottom: 0px;
		    background-color: #fff;
		    border: none;
		    border-radius: 0px;
		    box-shadow: 0 0px 0px rgb(0 0 0 / 10%);
		    border-bottom: 1px solid #ddd;
		}
		.list-group a {
		    color: #434a54;
		}
		li:hover{
			background-color: #3BAFDA;
		}
		li a:hover{
			color: #fff;
			text-decoration: none;
		}
		.d-flex {
			display: inline-flex;
		}
		.nav-tab li a i {
			    margin-top: 10px;
		}
			.nav-tab li a {
			    display: inline-flex;
		}
		@media (max-width: 768px)  {
			.nav-body {
				display: none;
			}
		}

	</style>
	<title></title>
</head>
<body>
  <div class="nav-body" id="nav-body">
	<div class="home">
	  <h3 class="">Dashboard</h3>
	</div>      
	  <ul class="nav-tab">
		<li class="list-group"><a href="accountsManager.php"><i class="glyphicon glyphicon-th-list">&nbsp;</i><h5>Accounts Manager</h5></a></li>
		<li class="list-group"><a href="accountsActivity.php"><i class="glyphicon glyphicon-th-list">&nbsp;</i><h5>Accounts Activity</h5></a></li>
		<li class="list-group"><a href="manageLanguage.php"><i class="glyphicon glyphicon-th-list">&nbsp;</i><h5>Manage Language</h5></a></li>
		<li class="list-group"><a href="manageSection.php"><i class="glyphicon glyphicon-th-list">&nbsp;</i><h5>Manage Section</h5></a></li>
		<li class="list-group"><a href="manageBusiness.php"><i class="glyphicon glyphicon-th-list">&nbsp;</i><h5>Manage Business</h5></a></li>
		<li class="list-group"><a href="manageCurrency.php"><i class="glyphicon glyphicon-th-list">&nbsp;</i><h5>Manage Currency</h5></a></li>
		<li class="list-group"><a href="languageSetup.php"><i class="glyphicon glyphicon-th-list">&nbsp;</i><h5>Language Setup</h5></a></li>
		<li class="list-group"><a href=""><i class="glyphicon glyphicon-th-list">&nbsp;</i><h5>Logout</h5></a></li>
	  </ul>                 
  </div>

</body>
</html>
  