<!DOCTYPE html>
<html>
<head>
<title></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
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
background-color:#f1f2f6 ;
}
td{
padding: 6px 0px 6px 10px!important;
border: none!important;
}
.text-center{
text-align: center;
}
.m-all {
margin: 10px;
}
.ml-o {
margin-left: 0px;
}
.pd-o {
padding: 0px;
}
.first-hr {
margin-top: 0px;
margin-bottom: 0px;
border: 0;
border-bottom: 1px solid #727171
}
.panel-heading {
background: #ddd; border: 1px solid #bbb1b1; border-radius: 4px 4px 0px 0px; border-bottom: none;
}
.form-body {
border: 1px solid #bbb1b1; background: #fff; border-radius: 0px 0px 4px 4px; padding: 10px 30px;
}
.second-form {
padding: 10px 0px 10px 20px;
}

.checkboxSection {
margin-top: 20px;
}
.checkboxSection td {
padding: 0px;
display: flex;
}
.fade-color {
color: #7e7b7b;

}
.txt-caption h5 {
font-weight: 700;
}
.button-group {
margin: 20px 0px 20px 0px;
}
.fl-left {
float: left;
}
.fl-right {
float: right;
}
.setUserNo {
width: 45px!important;
 display: inline-flex!important;
 height:30px!important;

}

input[type=checkbox], input[type=radio] {
    margin: 0px!important;
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
<div class="panel-heading">
<h3 class="panel-title">Edit Account</h3>
</div>
<div class="form-body">

<form action="" method="post" enctype="multipart/form-data">
<div class="row">
<!-- Start of left side form -->
<div class="col-md-6 col-sm-6">
<div class="txt-caption">
  <h5>Account Setup</h5>
</div>

<table class="table">
  <tr>
    <td>Account Name</td>
    <td><input type="text" id="accountName" name="accountName"  class="form-control" ></td>
  </tr>

  <tr>
    <td>Account Type</td>
    <td><select id="accountType" name="accountType"  class="form-control" >
      <option value="Test">Select</option>
      <option value="Test">Test</option>
      <option value="Test">Test</option>
      <option value="Test">Test</option>
    </select></td>
  </tr>

  <tr>
    <td>Monthly Fee</td>
    <td><input type="text" name="fee"  class="form-control" ></td>
  </tr>

  <tr>
    <td>Payment Type</td>
    <td><select id="paymentType" name="paymentType"  class="form-control" >
      <option value="Test">Select</option>
      <option value="Test">Test</option>
      <option value="Test">Test</option>
      <option value="Test">Test</option>
    </select></td>
  </tr>

  <tr>
    <td>Status</td>
    <td><select id="status" name="status"  class="form-control" >
      <option value="Test">Select</option>
      <option value="Test">Test</option>
      <option value="Test">Test</option>
      <option value="Test">Test</option>
    </select></td>
  </tr>

  <tr>
    <td>Language</td>
    <td><select id="language" name="language"  class="form-control" >
      <option value="Test">Select</option>
      <option value="Test">Test</option>
      <option value="Test">Test</option>
      <option value="Test">Test</option>
    </select></td>
  </tr>

  <tr>
    <td>Main Currency</td>
    <td><select id="mainCurrency" name="mainCurrency"  class="form-control" >
      <option value="Test">Select</option>
      <option value="Test">Test</option>
      <option value="Test">Test</option>
      <option value="Test">Test</option>
    </select></td>
  </tr>

  <tr>
    <td>Business Type</td>
    <td><select id="businessType" name="businessType"  class="form-control" >
      <option value="Test">Select</option>
      <option value="Test">Test</option>
      <option value="Test">Test</option>
      <option value="Test">Test</option>
    </select></td>
  </tr>

  <tr>
    <td>Logo</td>
    <td>
      <input type="file" id="logo" name="logo" class="form-control">
    </td>
  </tr>

  <!-- Ezee section start here -->
  <tr>
    <td class="txt-caption">
      <h5>Ezee Setup</h5>
    </td>
  </tr>

  <tr>
    <td>Hotel Id</td>
    <td><input type="text" id="HotelId" name="HotelId" class="form-control"></td>
  </tr>

  <tr>
    <td>Merchant Id</td>
    <td><input type="text" id="MerchantId" name="MerchantId" class="form-control"></td>
  </tr>
  <!-- End of ezee section -->
</table>

</div>
<!-- End of left side form -->

<!-- Start of right side form -->
<div class="col-md-6 col-sm-6">
	<div class="txt-caption">
			<h5>Account Owner Info</h5>
	</div>

  <!-- Hidden form section start from here -->
  <div id="second-form"  class="second-form">

    <table class="table">
      <tr><td>Name</td><td><input type="text" id="name" name="name" class="form-control"></td></tr>
      <tr>
        <td>Address Street</td>
        <td>
          <select id="addressStreet1" name="addressStreet1" class="form-control" >
            <option value="">Select</option>
            <option value="Test">Test</option>
            <option value="Test">Test</option>
            <option value="Test">Test</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Address Street</td>
        <td>
          <select id="addressStreet2" name="addressStreet2" class="form-control" >
            <option value="">Select</option>
            <option value="Test">Test</option>
            <option value="Test">Test</option>
            <option value="Test">Test</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Zip</td>
        <td>
          <input type="text" id="zip" name="zip" class="form-control">
        </td>
      </tr>
      <tr>
        <td>City</td>
        <td>
          <input type="text" id="city" name="city" class="form-control">
        </td>
      </tr>
      <tr>
        <td>Country</td>
        <td>
          <select id="country" name="country" class="form-control" >
            <option value="">Select</option>
            <option value="Test">Test</option>
            <option value="Test">Test</option>
            <option value="Test">Test</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Email</td>
        <td>
          <input type="text" id="email" name="email" class="form-control">
        </td>
      </tr>
      <tr>
        <td>Phone</td>
        <td>
          <input type="text" id="phone" name="phone" class="form-control">
        </td>
      </tr>
      <tr>
        <td>Tax Id</td>
        <td>
          <input type="text" id="taxId" name="taxId" class="form-control">
        </td>
      </tr>
    </table>
  </div>
  <!-- Hidden form section end here -->


  <!-- section area start here -->
  <div class="txt-caption checkboxSection">
    <table style="width: max-content;">

      <div class="txt-caption"><h5>Sections</h5></div>

      <tr>
        <td>
          <input type="checkbox" id="queue1System" name="queue1System" value="" checked />&nbsp;
          <h5 class="fade-color">Queue1 System</h5>
        </td>
      </tr>

      <tr>
        <td>
          <input type="checkbox" id="multiUser" name="multiUser" value="" checked />&nbsp;
          <h5 class="fade-color">Multi User</h5>
        </td>

        <td>
          <h5 class="fade-color" style="font-size: 12px;">Set max number of users</h5>
          <input type="text" id="userNumber" name="userNumber" value="" class="form-control setUserNo" />&nbsp;
        </td>

      </tr>

      <tr>
        <td>
          <input type="checkbox" id="requestUser" name="requestUser" value="" />&nbsp;
          <h5>Request User</h5>
        </td>
      </tr>

      <tr>
        <td>
          <input type="checkbox" id="moblieUser" name="moblieUser" value=""  />&nbsp;
          <h5>Mobile User</h5>
        </td>
      </tr>

      <tr>
        <td>
          <input type="checkbox" id="revenueCenter" name="revenueCenter" value="" />&nbsp;
          <h5>Revenue Center</h5>
        </td>
      </tr>

      <tr>
        <td>
          <input type="checkbox" id="accounts" name="accounts" value="" />&nbsp;
          <h5>Accounts</h5>
          <td>
      </tr>
    </table>
  </div>
      <!-- end of section area -->

    </div>
    <!-- start of group button -->
    <div class="col-md-12 col-sm-12 button-group">
      <button class="btn btn-info fl-left" onclick="window.location.href='accountsManager.php' ">Back</button>
      <button class="btn btn-info fl-right" onclick="window.location.href= 'accountsManager.php' ">Done</button>
    </div>
    <!-- end of group butto -->
  </div>
</form>
</div>
</div>
</div>
</div>
<!-- End of HTML part -->
</body>
</html>