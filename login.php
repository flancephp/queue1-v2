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
    <title>Queue1.com</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">

</head>

<body class="mb-Bgbdy">

    <div class="container-fluid newOrder">
        <div class="row">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                <nav class="navbar d-flex flex-wrap align-items-stretch">
                    <div>
                        <div class="logo">
                            <img src="Assets/icons/logo_Q.svg" alt="Logo" class="lg-Img">
                            <div class="clsBar" id="clsBar">
                                <a href="javascript:void(0)"><i class="fa-solid fa-arrow-left"></i></a>
                            </div>
                        </div>
                        
                    </div>
                    
                </nav>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('Login'); ?></h1>
                            
                        </div>
                        <div class="col-md-8 d-flex align-items-center justify-content-end">
                            <div class="mbPage">
                                <div class="mb-nav" id="mb-nav">
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                        aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"><i class="fa-solid fa-bars"></i></span>
                                    </button>
                                </div>
                                <div class="mbpg-name">
                                    <h1 class="h1"><?php echo showOtherLangText('Login'); ?></h1>
                                </div>
                                
                            </div>
                            <div class="acc-info">
                                <img src="Assets/icons/Q.svg" alt="Logo" class="q-Logo">
                                <div class="dropdown d-flex">
                                    <a class="dropdown-toggle body3" data-bs-toggle="dropdown">
                                        <span> <?php echo showOtherLangText('Language') ?></span> <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0)">English</a></li>
                                        
                                       
                                   
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="landScape">
                    <div class="container">
                        <h1 class="h1 text-center">For better experience, Please use portrait view.</h1>
                    </div>
                </section>

                <section class="ordDetail userDetail">
                    <div class="container">
                    <form class="addUser-Form acntSetup-Form row" name="loginForm" id="loginForm" role="form" action="" method="post">    
                        <input type="hidden" name="chksubmit" id="chksubmit" value="">
                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                   <p><?php echo showOtherLangText('Please sign in') ?></p>
                                </div>
                                <h6 class="form-signin-heading" style="color:red;">
                        <?php 

					echo $msg;

					if( isset($_GET['activate']) )
					{
						echo $_GET['activate'] == 1 ? ' '.showOtherLangText('Your account activated successfully.').' ' : ' '.showOtherLangText('Your activation code is either incorrect or account activated already.').' ';
					}

				?>
                    </h6>
                            </div>
                            <div class="usrAd-Btn">
                                <div class="btnBg">
                                    <button type="submit" onclick="checksubmit();" class="btn sub-btn std-btn mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-plus"></i></span>
                                        <span class="dsktp-Btn">Login</span></button>

                                </div>
                            </div>
                        </div>

                        <div class="acntStp">
                         <div class="acnt-Div">
                                    <div class="row align-items-center acntStp-Row">
                                        <div class="col-md-3">
                                            <label for="accountName" class="form-label"><?php echo showOtherLangText('Username') ?></label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="username" name="username"
                                                placeholder="<?php echo showOtherLangText('Username') ?>" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="row align-items-center acntStp-Row">
                                        <div class="col-md-3">
                                            <label for="ownerName" class="form-label"><?php echo showOtherLangText('Password') ?></label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" autocomplete="off" id="password" name="password" placeholder="<?php echo showOtherLangText('Password') ?>">
                                        </div>
                                    </div>

                                    <div class="row align-items-center acntStp-Row">
                                        <div class="col-md-3">
                                            <label for="ownerName" class="form-label"><?php echo showOtherLangText('Account id') ?></label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" autocomplete="off" id="accountNumber" name="accountNumber" placeholder="<?php echo showOtherLangText('Account id') ?>">
                                        </div>
                                    </div>
                            </form>
                        </div>


                    </div>
                </section>

            </div>
        </div>
    </div>

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
    <script type="text/javascript" src="Assets/js/accountsetup.js"></script>
</body>

</html>
<script>
function checksubmit() {
    $('#chksubmit').val('1');
}
</script>