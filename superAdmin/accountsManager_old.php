<!DOCTYPE html>
<html>
<head>
  <title>Queue1.com</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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

    * {
      box-sizing: border-box;
    }
    body {
      background-color:#f1f2f6;
      overflow:inherit;
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
      <?php require_once('nav.php');?>
    </div>

    <div class="col-md-10 col-sm-9 col-lg-10">
     <div width: 30%; style="display: flex; grid-gap: 15px;">
      <div><button class="btn btn-info" onclick="window.location.href= 'addAccount.php' ">Add Account</button></div>
      <div><button class="btn btn-info" onclick="window.location.href= 'accountsManager.php' ">Back</button></div>
     </div>
    </div>

  <div class="col-md-10 col-sm-9 col-lg-10">
   <div class="table-responsive" style="height: 470px; overflow: scroll;">

    <table class="table table-bordered">

      <tr class="bc-color">
        <th scope="col">Serial no.</th>
        <th scope="col">Account no.</th>
        <th scope="col">Account Name</th>
        <th scope="col">No. of users</th>
        <th scope="col">Status</th>
        <th scope="col">Join Date</th>
        <th scope="col">Total Activity</th>
        <th scope="col">Account Type</th>
        <th scope="col">Fee</th>
        <th scope="col">Payment Type</th>
        <th scope="col">Balance</th>
        <th scope="col">Action</th>
      </tr>

      <tr>
        <td>Serial no.</td>
        <td>Account no.</td>
        <td>Account Name</td>
        <td>No. of users</td>
        <td>Status</td>
        <td>Join Date</td>
        <td>Total Activity</td>
        <td>Account Type</td>
        <td>Fee</td>
        <td>Payment Type</td>
        <td>Balance</td>
        <td>
          <span class="glyphicon glyphicon-edit"  title="Edit" onclick="window.location.href='editAccount.php' "></span>
           | <i class="fa fa-file-text" title="View"  onclick="window.location.href='viewAccount.php' "></i>
        </td>
     </tr>

  </table>
  
</div>
</div>

</div>

</div>

</body>
</html>