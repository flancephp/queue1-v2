<?php 
require_once('common/header.php'); 

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && $_POST['password']){
  $username  = $_POST['username'];
  $password  = $_POST['password'];
  $deptQry = "SELECT id,name,email FROM tbl_superadmin WHERE username='".$username."' AND password='".$password."' AND status = 1";
	$deptResult = mysqli_query($con, $deptQry);
	$deptRow = mysqli_fetch_assoc($deptResult);
  if(isset($deptRow['email'])) {
    $_SESSION['superAdminEmail'] = $deptRow['email'];
    echo "<script>window.location='index.php'</script>";
    //header("Location:index.php");
  } else {
    $_SESSION['success'] = "Invalid credentials";
  }
   
}
?>

<div class="container">
  <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
      <div class="panel-heading">
        <div class="panel-title">Sign In</div>
        <div style="float:right; font-size: 80%; position: relative; top:-10px">
          <a href="#">Forgot password?</a>
        </div>
      </div>
      <div style="padding-top:30px" class="panel-body">
        <?php if(isset($_SESSION['success'])){?>
          <div class="alert alert-warning alert-dismissible"><i class="fa fa-check-circle"></i><strong>Warning!</strong> <?php echo $_SESSION['success'];?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
        <?php unset($_SESSION['success']); } ?>
        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
        <form action="login.php" class="form-horizontal" role="form" method="POST">
          <div style="margin-bottom: 25px" class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-user"></i>
            </span>
            <input id="login-username" type="text" class="form-control" name="username" value="" placeholder="username or email">
          </div>
          <div style="margin-bottom: 25px" class="input-group">
            <span class="input-group-addon">
              <i class="glyphicon glyphicon-lock"></i>
            </span>
            <input id="login-password" type="password" class="form-control" name="password" placeholder="password">
          </div>
          <div class="form-group">
            <div class="input-group">
              <div class="checkbox">
                <label class="">
                  <div class="icheckbox_flat">
                    <input type="checkbox" name="cookieChk" value="1">
                    <ins class="iCheck-helper"></ins>
                  </div>&nbsp;Remember me
                </label>
              </div>
            </div>
          </div>
          <div style="margin-top:10px" class="form-group">
            <!-- Button -->
            <div class="col-sm-12 controls">
              <button type="submit" name="login" class="btn btn-success">Login </a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once('common/footer.php'); ?>