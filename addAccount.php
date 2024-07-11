<?php include('inc/dbConfig.php'); //connection details


if (!isset($_SESSION['adminidusername']))
{
	echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'account' AND type_id = '0' AND designation_id = '".$_SESSION['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if ($permissionRow)
{
  echo "<script>window.location='index.php'</script>";
}

if( isset($_POST['accountName'])   )
{
	

	if (empty($_POST['currencyId']))
	{

		$currencyId = 'USD';

	}
	else
	{

		$currencyId = $_POST['currencyId'];

	}


	$sql = "INSERT INTO `tbl_accounts` SET 
	`accountName` = '".$_POST['accountName']."',
	`currencyId` = '".$currencyId."',
	`accountNumber` = '".$_POST['accountNumber']."',
	`balanceAmt` = '".$_POST['balanceAmt']."',
	`account_id` = '".$_SESSION['accountId']."'	";

	
	
	$error = '';
	
	if( !empty($_POST['balanceAmt']) )
	{

		 $query ="SELECT * FROM tbl_user WHERE id = '" . $_SESSION['id'] . "' AND password = '" . $_POST['pass'] . "' AND status = 1  AND account_id = '".$_SESSION['accountId']."' ";
		$result = mysqli_query($con, $query);
		$res = mysqli_fetch_array($result);

		if ( empty($res) )
		{
			$error = ''.showOtherLangText('Invalid Password').'';
            echo "<script>window.location='addAccount.php?error_invalid_pass=1'</script>";
		}
       
	}
	
	if($error == '')
	{

		mysqli_query($con, $sql);
		echo "<script>window.location='manageAccounts.php?added=1'</script>";

	}
	
	
}


?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Add Account - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/style1.css">

</head>

<body class="mb-Bgbdy">

    <div class="container-fluid newOrder">
        <div class="row">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
            <?php require_once('nav.php');?>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('Add Account'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Add Account'); ?></h1>
                                </div>
                            </div>
                            <div class="user d-flex align-items-center">
                                <img src="Assets/images/user.png" alt="user">
                                <p class="body3 m-0 d-inline-block">User</p>
                            </div>
                            <div class="acc-info">
                                <img src="Assets/icons/Q.svg" alt="Logo" class="q-Logo">
                                <div class="dropdown d-flex">
                                    <a class="dropdown-toggle body3" data-bs-toggle="dropdown">
                                        <span> Account</span> <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 1</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 2</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 3</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 4</a></li>
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
                    <?php if(isset($_GET['error_invalid_pass'])) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo isset($_GET['error_invalid_pass']) ? ' '.showOtherLangText('Invalid Password').' ' : ''; ?>
 </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                    <form  role="form" action="" method="post" class="addUser-Form acntSetup-Form" enctype="multipart/form-data">
                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="manageAccounts.php" class="sub-btn std-btn mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                </div>
                            </div>
                            <div class="usrAd-Btn">
                                <div class="btnBg">
                                    <button type="submit" class="btn sub-btn std-btn mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-regular fa-floppy-disk"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Save'); ?></span></button>
                                </div>
                            </div>
                        </div>

                        <div class="edtSup-Div">
                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="accountName" class="form-label"><?php echo showOtherLangText('Account Name'); ?> <span class="requiredsign">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control"  autocomplete="off" required name="accountName" id="accountName" value="<?php echo isset($_POST['accountName']) ? $_POST['accountName'] : ''; ?>"
                                            placeholder="Main Sale USD">
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="accountNumber" class="form-label"><?php echo showOtherLangText('Account Number'); ?> <span class="requiredsign">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control"name="accountNumber" id="accountNumber" value="<?php echo isset($_POST['accountNumber']) ? $_POST['accountNumber'] : ''; ?>" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" onChange="this.setCustomValidity('')" autocomplete="off" required  placeholder="0003">
                                    </div>
                                </div>


                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="accountCurrency" class="form-label"><?php echo showOtherLangText('Account Currency'); ?> <span class="requiredsign">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                         <?php
											$sqlSet = " SELECT * FROM tbl_currency WHERE account_id='".$_SESSION['accountId']."' order by id  ";
											$resultSet = mysqli_query($con, $sqlSet);
											?>				
											<select class="form-select" aria-label="Default select example" name="currencyId" id="currencyId">
												<option value=""><?php echo showOtherLangText('Select'); ?></option>

												<?php while($cur = mysqli_fetch_array($resultSet)){
													
													?> 
													<option value="<?php echo $cur['id'];?>"><?php echo $cur['currency'];?></option>
													<?php 
												} 
											?>
											</select>
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="accountBalance" class="form-label"><?php echo showOtherLangText('Balance'); ?> <span class="requiredsign">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" onChange="showHidePass(this.value);" name="balanceAmt" id="balanceAmt" autocomplete="off" id="accountBalance"
                                            placeholder="3220.7939">
                                    </div>
                                </div>
                                <div id="password" style="display:none;" class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="accountBalance" class="form-label"><?php echo showOtherLangText('Password'); ?></label>
                                    </div>
                                    <div  id="passArea" class="col-md-9">
                                        
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
</body>

</html>
<script>
	function showHidePass(val)
	{
		if(val == '')
		{
			$('#password').hide();
			$("#passArea").html('');

		}
		else
		{
			
			$('#password').show();
			$("#passArea").html('<input type="password" name="pass" id="pass" class="form-control"   autocomplete="off" oninvalid="this.setCustomValidity(<?php echo showOtherLangText('Please fill out this field.');?>)" onchange="this.setCustomValidity(<?php echo showOtherLangText('Please fill out this field.');?>)" required  />');
		}
	}
</script>  