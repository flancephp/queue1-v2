<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'currency' AND type_id = '0' AND designation_id = '".$_SESSION['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if ($permissionRow)
{
  echo "<script>window.location='index.php'</script>";
}

if( isset($_GET['delId']) && $_GET['delId'] )
{
	$ordQry = " SELECT * FROM tbl_orders WHERE ordCurId='".$_GET['delId']."' ";
	$ordResult = mysqli_query($con, $ordQry);
	 $ordResultRow = mysqli_num_rows($ordResult); 

	if ($ordResultRow > 0)
	{	

		echo "<script>window.location='manageCurrency.php?error=1'</script>";

	}
	else
	{
		
		$sql = "DELETE FROM tbl_currency WHERE id = '".$_GET['delId']."'  AND account_id = '".$_SESSION['accountId']."'";
		$result = mysqli_query($con, $sql);
		

		echo "<script>window.location='manageCurrency.php?delete=1'</script>";

	}

	
	
}

//////////////////Pagination goes here/////////////////////////////////////////
$sql = "SELECT * FROM tbl_currency WHERE is_default != '1' AND account_id = '".$_SESSION['accountId']."' order by id desc ";
$curResult = mysqli_query($con, $sql);

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Manage Currency - Queue1</title>
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
                  <?php require_once('nav.php');?>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('Manage Currency') ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Manage Currency') ?></h1>
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
                    <?php if(isset($_GET['edit']) || isset($_GET['added']) || isset($_GET['delete']) || isset($_GET['mainCurEdit'])) {?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p><?php 

                            echo isset($_GET['edit']) ? ' '.showOtherLangText('Currency Edited Successfully').' ' : '';

							echo isset($_GET['mainCurEdit']) ? ' '.showOtherLangText('Main Currency Edited Successfully').' ' : '';

							echo isset($_GET['added']) ? ' '.showOtherLangText('Currency Added Successfully').' ' : '';

							echo isset($_GET['delete']) ? ' '.showOtherLangText('Currency Deleted Successfully').' ' : '';

?>
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                            <?php if(isset($_GET['error']) || isset($_GET['error_already_exist'])) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo isset($_GET['error']) ? ' '.showOtherLangText('This Currency is used in order so it cannot be deleted.').' ' : ''; ?>
 </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="setup.php" class="btn btn-primary mb-usrBkbtn"><span class="mb-UsrBtn"><i
                                                class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                </div>
                            </div>
                            <div class="usrAd-Btn">
                                <div class="curExt-Btn">
                                    <div class="btnBg">
                                        <a href="editMainCurrency.php?currencyType=1" class="btn btn-primary mb-usrBkbtn"><span
                                                class="mb-UsrBtn"><i class="fa-solid fa-ellipsis"></i>
                                                <span class="nstdSpan"><?php echo showOtherLangText('Main Currency'); ?></span></span> <span
                                                class="dsktp-Btn"><?php echo showOtherLangText('Edit Main Currency');?></span></a>
                                    </div>
                                    <div class="btnBg">
                                        <a href="addCurrency.php" class="btn btn-primary mb-usrBkbtn"><span
                                                class="mb-UsrBtn"><i class="fa-solid fa-plus"></i></span>
                                            <span class="dsktp-Btn"><?php echo showOtherLangText('Add'); ?></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mngCurTable">
                            <div class="container">
                                <!-- Table Head Start -->
                                <div class="mngCurTbl-Head align-items-center itmTable">
                                    <div class="mngCurTbl-Cnt d-flex align-items-center">
                                    <div class="tb-head mngCur-Clm">
                                            <p><?php echo showOtherLangText('#') ?></p>
                                            
                                        </div>
                                        <div class="tb-head mngCur-Clm">
                                            <p><?php echo showOtherLangText('Currency') ?></p>
                                        </div>
                                        <div class="tb-head mngRate-Clm">
                                            <p><?php echo showOtherLangText('Rate For') ?> ($)</p>
                                        </div>
                                        <div class="tb-head mngDcml-Clm">
                                            <p><?php echo showOtherLangText('Decimal digits') ?></p>
                                        </div>
                                    </div>
                                    <div class="mngCurTbl-Icns">
                                        <div class="tb-head mngCurOpt-Clm text-center">
                                            <p><?php echo showOtherLangText('Options') ?></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Table Head End -->

                                <!-- Table Body Start -->
                                <?php 
								$x= 0;
								while($curRow = mysqli_fetch_array($curResult))
								{
									$color = ($x%2 == 0)? 'white': '#FFFFCC';
									$x++;

									?>
                                <div class="mngCurTask">
                                    <div class="mngCurTbl-body align-items-center itmBody">
                                        <div class="mngCurTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy mngCur-Clm">
                                                <p><?php echo $x;?></p>
                                            </div>
                                            <div class="tb-bdy mngCur-Clm">
                                                <p><?php echo $curRow['currency'];?></p>
                                            </div>
                                            <div class="tb-bdy mngRate-Clm">
                                                <p class="mngCr-Rate"> <span class="mb-MngCr">Rate (S)</span> <?php echo $curRow['amt'];?></p>
                                            </div>
                                            <div class="tb-bdy mngDcml-Clm">
                                                <p class="mngDcm-Dgt"> <span class="mb-MngCr">Decimal digits</span> <?php echo $curRow['decPlace'];?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mngCurTbl-Icns">
                                            <div class="tb-bdy mngCurOpt-Clm d-flex align-items-center">
                                                <a href="editCurrency.php?id=<?php echo $curRow['id'];?>" class="userLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" onClick="getDelNumb('<?php echo $curRow['id'];?>');" class="userLink">
                                                    <img src="Assets/icons/delete.svg"  alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
								}
								?>  
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                <!-- Table Body End -->

                            </div>
                        </div>

                    </div>
                </section>

            </div>
        </div>
    </div>
    <div id="dialog" style="display: none;">
    <?php echo showOtherLangText('Are you sure to delete this record?') ?>  
    </div>
    <?php require_once('footer.php');?>
    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
   <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
</body>
<div class="modal" tabindex="-1" id="delete-popup" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php echo showOtherLangText('Are you sure to delete this record?') ?> </h1>
                </div>
                
                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="button" data-bs-dismiss="modal" class="btn sub-btn std-btn"><?php echo showOtherLangText('No'); ?></button>
                    </div>
                    <div class="btnBg">
                        <button type="button" onclick="" class="deletelink btn sub-btn std-btn"><?php echo showOtherLangText('Yes'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</html>

<script>  
 function getDelNumb(delId){

   var newOnClick = "window.location.href='manageCurrency.php?delId=" + delId + "'";

      $('.deletelink').attr('onclick', newOnClick);
     $('#delete-popup').modal('show');
}  
</script>