<?php
include('inc/dbConfig.php'); //connection details

if (isset($_SESSION['adminidusername'])) 
{
  echo "<script>window.location='index.php'</script>";
}


if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1)
{
	$url = 'mobile/index.php';
	echo "<script>window.location.href='".$url."';</script>";
	exit;
}
		


if (isset($_POST['languageType'])) 
{
	
	$_SESSION['languageType'] = $_POST['languageType'];

	if (isset($_SESSION['languageType'])) {
		$getOtherTextArr = getOtherText($_SESSION['languageType']);
		$getLangType = getLangType($_SESSION['languageType']);
	}

}

$msg = ""; //initial status

if(isset($_POST['username']) && $_POST['username'] != '' && $_POST['chksubmit'] > 0)
{
	// Retrieve username and password from database according to user's input, preventing sql injection
	$query =" SELECT c.language_id, u.* FROM tbl_client c INNER JOIN tbl_user
	u ON(u.account_Id = c.id) AND u.account_Id = c.id WHERE u.
	username='".$_POST['username']."' AND (u.password='".$_POST['password']."' OR u.password='".md5($_POST['password'])."') AND u.status = '1'  AND c.accountNumber='". $_POST['accountNumber']."' ";
	$result = mysqli_query($con, $query);
 	$res = mysqli_fetch_array($result);

	// Check username and password match
	if (!empty($res)) 
	{
		// Set username session variable
		$_SESSION['adminidusername'] = $_POST['username'];
		$_SESSION['id'] = $res['id'];
		$_SESSION['designation_id'] = $res['designation_id'];
		$_SESSION['isAdmin'] = $res['userType'];
		$_SESSION['adminUser'] = $res['isAdmin'];
		$_SESSION['language_id'] = $res['language_id']; 
		$_SESSION['name'] = $res['name']; 
    $_SESSION['accountId'] = $res['account_id'];
		
		if(isset($_POST['cookieChk']) && $_POST['cookieChk'] !='')
		{
			setcookie('usernameChk', $_POST['username'], time() + (86400 * 30 * 30), "/"); // 86400 = 1 day
			setcookie('passwordChk', $_POST['password'], time() + (86400 * 30 * 30), "/"); // 86400 = 1 day
		}
		else
		{
			setcookie('usernameChk', '', time() - (86400 * 30 * 30), "/"); // 86400 = 1 day
			setcookie('passwordChk', '', time() - (86400 * 30 * 30), "/"); // 86400 = 1 day
		}
		
		$url = 'runningOrders.php';

		if($res['userType'] == 1)
		{
			$url = 'mobile/index.php';
		}
		echo "<script>window.location.href='".$url."';</script>";
			
	}
	else
	{
		$msg = ' '.showOtherLangText('Username And Password Not Matched Or May Be Your Account Is Inactive.').' ';
	}

} 


?>

<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>New Order - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">

</head>

<body>

    <div class="container-fluid ">
        <div class="logo pt-3 pb-md-5 pb-3">
            <img src="Assets/icons/logo_Q.svg" alt="Logo" >            
        </div>


        <div class="row justify-content-center">
            
            <div class="col-md-8">
                <div class="login-outer">

                    <div>
                    <h1 class="h1 text-center pb-5"><?php echo showOtherLangText('Login'); ?></h1>
                    <?php if(isset($msg) && $msg!='') { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo isset($msg) ? $msg:''; ?>
 </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                    <form class="login-form" name="loginForm" id="loginForm" role="form" action="" method="post">
                        <input type="hidden" name="chksubmit" id="chksubmit" value="">
                        <div class="d-flex gap-2 align-items-center drp-btn input-group mb-2">
                            <div class="input-group-append">
                            <span class="btn"><i class=" fa fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" id="username" name="username" placeholder="<?php echo showOtherLangText('Username') ?>" autocomplete="off">
                        </div>


                        <div class="d-flex gap-2 align-items-center drp-btn input-group mb-2">
                            <div class="input-group-append">
                            <span class="btn"><i class=" fa fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" id="password" name="password" placeholder="<?php echo showOtherLangText('Password') ?>" autocomplete="off">

                            <div class="input-group-append password-toggle-icon">
                                <span class="btn"><i class=" fa fa-eye-slash"></i></span>
                            </div>
                        </div>


                        <div class="d-flex gap-2 align-items-center drp-btn input-group mb-2">
                            <div class="input-group-append">
                            <span class="btn"><b><?php echo showOtherLangText('ID') ?></b></span>
                            </div>
                            <input type="text" class="form-control" id="accountNumber" name="accountNumber" placeholder="<?php echo showOtherLangText('Account id') ?>" autocomplete="off">
                        </div>

                        <div class="py-3 d-flex align-items-center justify-content-center gap-3 mb-2 login-remember">

                            <label for="remember"><?php echo showOtherLangText('Remember Me') ?></label>
                            <input type="checkbox" name="remember" id="remember" class="form-check-input" checked>

                        </div>


                        <div class="text-center">
                            <button type="submit" onclick="checksubmit();" class="btn sub-btn std-btn mb-usrBkbtn fs-20 py-2 px-4">
                                <span class="dsktp-Btn px-2 d-inline"><?php echo showOtherLangText('Sign In') ?></span></button>
                        </div>



                        <div>

                        </div>

                    </form>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>





</body>

</html>
<script>
function checksubmit() {
    $('#chksubmit').val('1');
}
</script>