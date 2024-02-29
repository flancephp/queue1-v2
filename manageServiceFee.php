<?php
include('inc/dbConfig.php'); //connection details

if (!isset($_SESSION['adminidusername']))
{
	echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'service_item' AND type_id = '0' AND designation_id = '".$_SESSION['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if ($permissionRow)
{
  echo "<script>window.location='index.php'</script>";
}

if( isset($_GET['delId']) && $_GET['delId'] )
{


	$selQry = " SELECT * FROM tbl_order_details WHERE customChargeId = '".$_GET['delId']."' And customChargeType = 1 AND account_id = '".$_SESSION['accountId']."' ";

	$res = mysqli_query($con, $selQry);
	$resRow = mysqli_fetch_array($res);

	if ($resRow > 0) {
		
		echo '<script>window.location="manageServiceFee.php?err=1"</script>';

	}else{

		$sql = "DELETE FROM tbl_custom_items_fee WHERE id='".$_GET['delId']."' AND account_id = '".$_SESSION['accountId']."'  ";
		mysqli_query($con, $sql);

		echo '<script>window.location="manageServiceFee.php?delete=1"</script>';

	}

	
}

$sql = "SELECT * FROM tbl_custom_items_fee WHERE visibility='1'  AND account_id = '".$_SESSION['accountId']."'  ";
$result = mysqli_query($con, $sql);				


?>

<!DOCTYPE html >
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Manage Service Fee - Queue1</title>
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
                            <h1 class="h1">Manage Service Fee</h1>
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
                                    <h1 class="h1">Manage Service Fee</h1>
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

                <?php $color = (isset($_GET['delete']) && $_GET['delete'] > 0 || isset($_GET['err']) && $_GET['err'] > 0) ? 'color: #FF0000' : 'color: #038B00';?>

							<h6 style="<?php echo $color?>"><?php 

								echo isset($_GET['update']) ? ' '.showOtherLangText('Service Fee Edited Successfully').' ' : '';

								echo isset($_GET['added']) ? ' '.showOtherLangText('Service Fee Added Successfully').' ' : '';

								echo isset($_GET['delete']) ? ' '.showOtherLangText('Service Fee Deleted Successfully').' ' : '';

								echo isset($_GET['err']) ? ' '.showOtherLangText('Service Fee cannot be deleted as it is being used in order').' ' : '';

							?></h6>

                    <div class="container">
                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="setup.php" class="sub-btn std-btn mb-usrBkbtn"><span class="mb-UsrBtn"><i
                                                class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                </div>
                            </div>
                            <div class="usrAd-Btn">
                                <div class="btnBg">
                                    <a href="addServiceFee.php" class="sub-btn std-btn mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-plus"></i>
                                            <span class="nstdSpan"></span></span> <span class="dsktp-Btn"><?php echo showOtherLangText('Add'); ?></span></a>
                                </div>
                            </div>
                        </div>

                        <div class="srvFeeTable">
                            <div class="container">
                                <!-- Table Head Start -->
                                <div class="srvFeeTbl-Head align-items-center itmTable">
                                    <div class="srvFeeTbl-NmCol d-flex align-items-center">
                                        <div class="tb-head feeNum-Clm">
                                            <div class="d-flex align-items-center">
                                                <p><?php echo showOtherLangText('Number') ?></p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="tb-head feeName-Clm">
                                            <p><?php echo showOtherLangText('Fee Name') ?></p>
                                        </div>
                                    </div>
                                    <div class="srvFeeTbl-UntCol d-flex align-items-center">
                                        <div class="tb-head feeUnit-Clm">
                                            <p><?php echo showOtherLangText('Unit') ?></p>
                                        </div>
                                        <div class="tb-head feeAmnt-Clm">
                                            <p><?php echo showOtherLangText('Amount') ?></p>
                                        </div>
                                    </div>
                                    <div class="srvFeeTbl-IcnCol">
                                        <div class="tb-head feeOpt-Clm">
                                            <p><?php echo showOtherLangText('Options') ?></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Table Head End -->

                                <!-- Table Body Start -->

                                <?php 
								$x= 0;
								while($row = mysqli_fetch_array($result))
								{ 
									$color = ($x%2 == 0)? 'white': '#FFFFCC';
									$x++;
									$curDet = getDefaultCurrencyDet($_SESSION['accountId']);
									?>
                                <div class="SerFeeTask">
                                    <div class="serFeeTbl-body itmBody">
                                        <div class="srvFeeTbl-NmCol d-flex align-items-center">
                                            <div class="tb-bdy feeNum-Clm">
                                                <p class="feeSrNumber"><span class="mb-UsrSpan">No. </span><?php echo $x;?></p>
                                            </div>
                                            <div class="tb-bdy feeName-Clm">
                                                <p class="suplName"><?php echo $row['itemName'];?></p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-UntCol align-items-center">
                                            <div class="tb-head feeUnit-Clm">
                                                <p><?php echo $row['unit'];?></p>
                                            </div>
                                            <div class="tb-head feeAmnt-Clm">
                                                <p><?php echo showPrice($row['amt'], $curDet['curCode']);?></p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-IcnCol">
                                            <div class="tb-bdy feeOpt-Clm d-flex align-items-center">
                                                <a href="editServiceFee.php?id=<?php echo $row['id'];?>" class="userLink">
                                                    <img src="Assets/icons/dots.svg" alt="<?php echo showOtherLangText('Dots');?>" class="usrLnk-Img">
                                                </a>

                                                <a href="javascript:void(0)" class="userLink" onClick="getDelNumb('<?php echo $row['id'];?>');">
                                                    <img src="Assets/icons/delete.svg" alt="<?php echo showOtherLangText('Delete');?>" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask-Fee">
                                        <a href="javascript:void(0)" class="serFeeLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <?php }?>
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
   

   

    <script>  
 function getDelNumb(delId){

    $( "#dialog" ).dialog({  
        autoOpen  : false,
        modal     : true,
        //title     : "Title",
        buttons   : {
          '<?php echo showOtherLangText('Yes') ?>' : function() {
            //Do whatever you want to do when Yes clicked
            $(this).dialog('close');
            window.location.href='manageServiceFee.php?delId='+delId;
          },

          '<?php echo showOtherLangText('No') ?>' : function() {
            //Do whatever you want to do when No clicked
            $(this).dialog('close');
          }
       }    
    });

    $( "#dialog" ).dialog( "open" );
    $('.custom-header-text').remove();
    $('.ui-dialog-content').prepend('<div class="custom-header-text"><span><?php echo showOtherLangText('Queue1.com Says') ?></span></div>');
}  
</script> 
</body>

</html>