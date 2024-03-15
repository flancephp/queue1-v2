<?php 
include('inc/dbConfig.php'); //connection details


if(!isset($_SESSION['adminidusername']))
{
echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


//check page permission
$checkPermission = permission_denied_for_section_pages($_SESSION['designation_id'],$_SESSION['accountId']);


if (!in_array('3',$checkPermission))
{
echo "<script>window.location='index.php'</script>";
}


if( isset($_POST['clickApproveBtn']) || isset($_POST['clickBackBtn']) || isset($_POST['clickAnywhere']) && ($_POST['clickApproveBtn'] == 1 || $_POST['clickBackBtn'] == 1 || $_POST['clickAnywhere'] == 1) )
{
	unset($_SESSION['errorQty']);
	unset($_SESSION['errorQtyOrderId']);
	die();
}


if (isset($_SESSION['supplierIdOrd']))
{	
unset($_SESSION['supplierIdOrd']);
}


if (isset($_SESSION['ordDeptId']))
{	
unset($_SESSION['ordDeptId']);
}

//when click on delete order this code will perform
if( isset($_GET['canId']) && $_GET['canId'] )
{
	$sql = "DELETE FROM tbl_orders WHERE id='".$_GET['canId']."'  AND account_id = '".$_SESSION['accountId']."' ";
	mysqli_query($con, $sql);

	$sql = " SELECT * FROM tbl_order_details WHERE ordId = '".$_GET['canId']."' AND account_id = '".$_SESSION['accountId']."' AND pId > 0 ";
	$getOrdDetRes = mysqli_query($con, $sql);
	while ($getOrdDetRow = mysqli_fetch_array($getOrdDetRes)) 
	{
		$updateStockQry = " UPDATE tbl_stocks SET tempQty = (tempQty - '".$getOrdDetRow['qty']."') WHERE pId = '".$getOrdDetRow['pId']."' And account_id = '".$_SESSION['accountId']."' ";
		mysqli_query($con, $updateStockQry);
	}

	$sql = "DELETE FROM tbl_order_details WHERE ordId='".$_GET['canId']."'  AND account_id = '".$_SESSION['accountId']."' ";
	mysqli_query($con, $sql);

	echo "<script>window.location='runningOrders.php?cancel=1&type=".$_GET['type']." '</script>";

}

if( isset($_GET['orderId']) && isset($_GET['confirm']) )
{

$error = '';
if($_GET['confirm'] == 2)//for recusation
{	
			//this is for light box popup once quanity is being updated 
			if (isset($_POST['reqQty']) && !empty($_POST['reqQty']) ) {
				
				foreach ($_POST['reqQty'] as $productId => $qty) 
				{
					$sql = " UPDATE tbl_order_details SET qty = '".$qty."',
					totalAmt = price*$qty WHERE pId = '".$productId."' AND ordId = '".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
					mysqli_query($con, $sql);
				}
				
               // requisitionTotalValue($_POST['orderId']);
			}

           
			
				
			$sqlSet = " SELECT * FROM tbl_orders where id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."'  ";
			$ordQry = mysqli_query($con, $sqlSet);
			$ordResult = mysqli_fetch_array($ordQry);
			if($ordResult['status'] == 2)
			{
				echo "<script>window.location='runningOrders.php?error=alreadyIssuedOut'</script>";die();
			}	    
				
			//Check stock qty with item qty
			$sql = "SELECT * FROM tbl_order_details WHERE ordId = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' AND qty > 0 ";
			$ordQry = mysqli_query($con, $sql);
			
			$productIds = [];
			while($ordRow = mysqli_fetch_array($ordQry) )
			{ 	
				$sql = "SELECT * FROM tbl_stocks WHERE pId = '".$ordRow['pId']."' AND account_id = '".$_SESSION['accountId']."' ";
				$stkQry = mysqli_query($con, $sql);
				$stkRow = mysqli_fetch_array($stkQry);
			
				$productIds[$ordRow['pId']]['qty'] = $ordRow['qty'];
			
				if ($stkRow['pId'] > 0)
				{
					if( $stkRow['qty'] < $ordRow['qty']  )
					{
						$_SESSION['errorQty'] = 1;
						$_SESSION['errorQtyOrderId'] = $_GET['orderId'];
			
						echo "<script>window.location='runningOrders.php'</script>";die();
					}
				}
			
			}//End of loop
			
			
			$updateQry = " UPDATE `tbl_orders` SET setDateTime = '".date('Y-m-d h:i:s')."' WHERE id = '".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
			mysqli_query($con, $updateQry);
			
			
			$sql = "SELECT * FROM tbl_order_details WHERE ordId = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."'  ";
			$ordQry = mysqli_query($con, $sql);
			while($ordRow = mysqli_fetch_array($ordQry) )
			{
				$upQry = " UPDATE  `tbl_stocks` SET
				`qty` = (qty - ".$ordRow['qty']."),
				`stockValue` = ( stockValue - ".$ordRow['totalAmt']." )
				WHERE pId = '".$ordRow['pId']."' AND account_id = '".$_SESSION['accountId']."'   ";
				mysqli_query($con, $upQry);
			
			}
			
			//add details in report
			addIssueOutInReport($productIds, $ordResult['recMemberId']);
			
			addAutoFillReqReport($_GET['orderId'], $_SESSION['accountId'], $ordResult['recMemberId']);
			//end report part

            requisitionTotalValue($_GET['orderId']);
			
}//end issue out part

$sql = "UPDATE tbl_orders SET status = '".$_GET['confirm']."'  WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."'  ";
mysqli_query($con, $sql);

//Insert few data in order journey tbl to show journey 
$sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
$res = mysqli_query($con, $sql);
$resRow = mysqli_fetch_array($res);

$action = ($_GET['confirm'] == 1 || $_GET['confirm'] == 3) ? 'Confirm' : 'Issue Out';

$qry = " INSERT INTO `tbl_order_journey` SET 
`account_id` = '".$_SESSION['accountId']."',
`orderId` = '".$resRow['id']."',
`userBy`  = '".$_SESSION['id']."',
`ordDateTime` = '".date('Y-m-d h:i:s')."',
`amount` = '".$resRow['ordAmt']."',
`otherCur` = '".$resRow['ordCurAmt']."',
`otherCurId` = '".$resRow['ordCurId']."',
`orderType` = '".$resRow['ordType']."',
`action` = '".$action."' ";
mysqli_query($con, $qry);

$red = ($_GET['confirm'] == 1 || $_GET['confirm'] == 3) ? 'runningOrders.php?status=1' : 'history.php?status=1';



echo "<script>window.location='".$red."&error=".$error." '</script>";die();

}


//Assign order to mobile user code goes here

if( isset($_POST['checkedTotal']) && $_POST['userIds'] == 0 )
{
	$sql = " DELETE FROM `tbl_order_assigned_users` 
	WHERE orderType = '".$_POST['orderType']."' AND account_id = '".$_SESSION['accountId']."' AND orderId = '".$_POST['orderId']."' ";
	$assignQry = mysqli_query($con, $sql);


    $qry = " INSERT INTO `tbl_order_journey` SET 
		`account_id` = '".$_SESSION['accountId']."',
		`orderId` = '".$resRow['id']."',
		`userBy`  = '".$_SESSION['id']."',
		`ordDateTime` = '".date('Y-m-d h:i:s')."',
		`amount` = '".$resRow['ordAmt']."',
        `otherCur` = '".$resRow['ordCurAmt']."',
        `otherCurId` = '".$resRow['ordCurId']."',
		`orderType` = '".$resRow['ordType']."',
        `notes` = 'All Users unAssigned',
		`action` = 'Unassigned' ";
		mysqli_query($con, $qry);

	echo "<script>window.location='runningOrders.php?unAssigned=1'</script>";die();
}

if( isset($_POST['userIds']) && !empty($_POST['userIds']) )
{
	$sql = " DELETE FROM `tbl_order_assigned_users` 
	WHERE orderType = '".$_POST['orderType']."' AND account_id = '".$_SESSION['accountId']."' AND orderId = '".$_POST['orderId']."' ";
	$assignQry = mysqli_query($con, $sql);

	//Insert few data in order journey tbl to show journey 
	$sql = " SELECT * FROM tbl_orders WHERE id = '".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
	$res = mysqli_query($con, $sql);
	$resRow = mysqli_fetch_array($res);

	foreach($_POST['userIds'] as $userId)
	{

		$qry = " INSERT INTO tbl_order_assigned_users 
		SET 
		orderType = '".$_POST['orderType']."',
		orderId = '".$_POST['orderId']."',
		userId = '".$userId."',
		account_id = '".$_SESSION['accountId']."' ";
		mysqli_query($con, $qry);



	}

    $sql = " SELECT GROUP_CONCAT(`name`) as names FROM tbl_user WHERE id IN(" .implode(',', $_POST['userIds']). ") 
    AND account_id = '".$_SESSION['accountId']."' GROUP BY account_id ";
	$res = mysqli_query($con, $sql);
	$userRow = mysqli_fetch_array($res);

    $notes = 'Assigned To: '.$userRow['names'];
    $qry = " INSERT INTO `tbl_order_journey` SET 
    `account_id` = '".$_SESSION['accountId']."',
    `orderId` = '".$resRow['id']."',
    `userBy`  = '".$_SESSION['id']."',
    `ordDateTime` = '".date('Y-m-d h:i:s')."',
    `amount` = '".$resRow['ordAmt']."',
    `otherCur` = '".$resRow['ordCurAmt']."',
    `otherCurId` = '".$resRow['ordCurId']."',
    `orderType` = '".$resRow['ordType']."',
    `notes` = '".$notes."',
    `action` = 'assigned' ";
    mysqli_query($con, $qry);

	echo "<script>window.location='runningOrders.php?assigned=1'</script>";die();
}



if (isset($_POST['assignedOrderType']) && isset($_POST['assignedOrderId'])) {
	
	 $sql = " SELECT * FROM `tbl_order_assigned_users` where orderType = '".$_POST['assignedOrderType']."'  AND account_id = '".$_SESSION['accountId']."'  AND orderId = '".$_POST['assignedOrderId']."' ";
	
	$assignQry = mysqli_query($con, $sql);
	$userIdsArr = [];
	while($assignRow = mysqli_fetch_array($assignQry) )
	{
		$userIdsArr[] = $assignRow['userId'];
	}

	$type = ($_POST['assignedOrderType'] == 1) ? 'receiving_order' : 'issuing_out'; 

	$sql = " SELECT u.* FROM tbl_user u
	LEFT JOIN tbl_designation_sub_section_permission dssp
		ON(u.designation_id=dssp.designation_id) AND u.account_id=dssp.account_id

	WHERE u.userType=1 AND dssp.is_mobile = 1 AND dssp.type = '".$type."' AND dssp.type_id = 1 AND u.account_id = '".$_SESSION['accountId']."'   GROUP BY u.id ORDER BY u.name ";
	
	$usrqry = mysqli_query($con, $sql);
	$checkBox = '';
	while($userRes = mysqli_fetch_array($usrqry))
	{
	 	$checked = in_array($userRes['id'], $userIdsArr) ? 'checked="checked"' : '';

		$checkBox .= '<input type="checkbox" id="optionCheck" class="optionCheck" name="userIds[]" value="'.$userRes['id'].'" '.$checked.' /> '.$userRes['name'].'<br>';
	}
	echo $checkBox;

	die();
}




?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Running Tasks - Queue1</title>
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

    <div class="container-fluid newOrder">
        <div class="row">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
            <?php require_once('nav.php');?>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('Running Tasks'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Running Tasks'); ?></h1>
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

                <!-- <div class="alrtMessage">
                    <div class="container">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <p><strong>Hello User!</strong> Your order has been placed Successfully.</p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <p><strong>Hello User!</strong> You should check your order carefully.</p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div> -->
                <?php

                //$cond = $_SESSION['adminUser'] != 1 ? " AND o.orderBy = '".$_SESSION['id']."' " : "";

                $sql = "SELECT o.*, s.name as supplierName, au.id as assignedUserTblId, cur.amt as curAmt, mt.id as mtId, u.name as orderBy, dm.name as deptMem FROM tbl_orders o

                INNER JOIN tbl_user u ON(u.id=o.orderBy) AND u.account_id=o.account_id

                LEFT JOIN tbl_suppliers s ON(o.supplierId = s.id) AND s.account_id = o.account_id

                LEFT JOIN tbl_department dpt ON(dpt.id = o.deptId) AND dpt.account_id = o.account_id

                LEFT JOIN tbl_order_assigned_users au ON(au.orderId=o.id) AND au.account_id=o.account_id

                LEFT JOIN tbl_currency cur ON(cur.id=o.ordCurId) AND cur.account_id=o.account_id

                LEFT JOIN tbl_mobile_time_track mt ON(mt.stockTakeId=o.id) AND  mt.status=1 AND mt.account_id=o.account_id

                LEFT JOIN tbl_deptusers dm ON(dm.id=o.recMemberId) AND dm.account_id=o.account_id

                WHERE o.status !=2 AND o.ordType IN(1,2,3) AND o.account_id='".$_SESSION['accountId']."' ".$cond." GROUP BY o.id Order by o.id desc";

                $result = mysqli_query($con, $sql);

                ?>
                <section class="rntskHead">
                    <div class="container">
                    <?php if(isset($_GET['cancel']) || isset($_GET['added']) || isset($_GET['requisitionAdded']) || isset($_GET['storageAdded']) || isset($_GET['storageAdded']) || isset($_GET['status']) || isset($_GET['assigned']) || isset($_GET['unAssigned']) || isset($_GET['updated']) ) {?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p><?php 

if ($_GET['type'] == 2) {
    $typeMes = ''.showOtherLangText('Requisition').'';
}elseif ($_GET['type'] == 3) {
    $typeMes = ''.showOtherLangText('StockTake').'';
}else{
    $typeMes = ''.showOtherLangText('Order').'';
}

//$typeMes = ($_GET['type'] == 2) ? ''.showOtherLangText('Requisition').'' : ($_GET['type'] == 3) ? ''.showOtherLangText('StockTake').'' : ''.showOtherLangText('Order').'';

echo isset($_GET['cancel']) ? ' '.showOtherLangText('Selected '.$typeMes.' has been cancelled').' ' : '';

echo isset($_GET['added']) ? ' '.showOtherLangText('New record added successfully').' ' : '';

echo isset($_GET['requisitionAdded']) ? ' '.showOtherLangText('New record added successfully').' ' : '';

echo isset($_GET['storageAdded']) ? ' '.showOtherLangText('New StockTake added successfully').' ' : '';

echo isset($_GET['status']) ? ' '.showOtherLangText('Order Confirmed').' ' : '';

echo isset($_GET['updated']) ? ' '.showOtherLangText('Order Updated').' ' : '';

echo isset($_GET['assigned']) ? ' '.showOtherLangText('User has been assigned successfully for selected order').' ' : '';

echo isset($_GET['unAssigned']) ? ' '.showOtherLangText('User has been unassigned successfully for selected order').' ' : '';
?>
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                            <?php if((isset($_GET['error']) && $_GET['error']!='') || isset($_GET['error_already_exist'])) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo isset($_GET['error']) ? ' '.showOtherLangText('This Currency is used in order so it cannot be deleted.').' ' : ''; ?>
 </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                        <div class="d-flex align-items-center taskHead">
                            <div style="width: 3%;">&nbsp;</div>
                            <div class="d-flex align-items-center runTable py-1" style="width: 97%;">

                                <div class="d-flex align-items-center" style="width: 60%;">
                                    <div style="width: 3%;">&nbsp;</div>
                                    <div class="d-flex align-items-center">
                                        <div class="p-1" style="width: 25%;">
                                            <p><?php echo showOtherLangText('Number'); ?></p>
                                        </div>
                                        <div class="p-1" style="width: 25%;">
                                            <p><?php echo showOtherLangText('Member'); ?></p>
                                        </div>
                                        <div class="rnTsk-Num">
                                            <p><?php echo showOtherLangText('User'); ?></p>
                                        </div>
                                        <div class="p-1" style="width: 25%;">
                                            <p><?php echo showOtherLangText('Supplier'); ?></p>
                                        </div>
                                        <div class="p-1" style="width: 25%;">
                                            <p><?php echo showOtherLangText('Date'); ?></p>
                                        </div>
                                        <div class="p-1">
                                            <p><?php echo showOtherLangText('Value'); ?>(<?php echo $getDefCurDet['curCode'] ?>)</p>
                                        </div>
                                        <!-- <div class="p-1">
                                            <p><?php //echo showOtherLangText('Other Cur.'); ?></p>
                                        </div> -->
                                      
                                    </div>
                                    


                                </div>
                                <div class="d-flex align-items-center" style="width: 30%;">
                                    <div class="p-1">
                                        <p><?php echo showOtherLangText('Status'); ?></p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center" style="width: 30%;">
                                    <div class="p-1">
                                        <p><?php echo showOtherLangText('Action'); ?></p>
                                    </div>
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

                <section class="runTask">

                    <div class="container">
                    <?php
		$x = 0;
		while($orderRow = mysqli_fetch_array($result))
		{
				
				if( $orderRow['ordType'] == 1 || $orderRow['ordType'] == 2 )
				{
				
					if($orderRow['ordType'] == 1)
					{
						$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$_SESSION['designation_id']."' AND type = 'order_supplier' AND account_id = '".$_SESSION['accountId']."' and type_id = '".$orderRow['supplierId']."' and designation_section_permission_id=1 ";
						
					}
					else
					{
						$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$_SESSION['designation_id']."' AND type = 'member' AND account_id = '".$_SESSION['accountId']."' and type_id = '".$orderRow['recMemberId']."' and designation_section_permission_id=2 ";
					}
					
					$checkSubPerQry = mysqli_query($con, $sql);
					
					if( mysqli_num_rows($checkSubPerQry) < 1)
					{
						continue;//exclude this order as user don't have its permission
					}
				}
				
				//end temporary
                        $x++;

                        $checkOrdAssing = $orderRow['assignedUserTblId'];

                        $curAmt = $orderRow['curAmt'] > 0 ? $orderRow['curAmt'] : 0;

                        $timeTrackDet = $orderRow['mtId'];//assing mobile time track id to check below
                        $suppliers = '';
                        $deprtName = '';
                
                    ?>
                        <div class="task">
                            <!-- Confirmed Member Part Start -->
                            <div class="d-flex align-items-center mt-0 mainRuntsk">
                                <div class="srNum">
                                    <p><?php echo $x;?></p>
                                </div>
                                <div class="fxdhtBox">
                                    <div class="mbSbar">&nbsp;</div>
                                    <div class="align-items-center dskDetail">
                                        <div class="d-flex align-items-center runDetail mbrDtl-Bg">
                                            <div class="reqType">&nbsp;</div>
                                            <div class="d-flex align-items-center ordeReq">
                                                <div class="p-1" style="width: 35%;">
                                                    <p><?php echo $orderRow['ordNumber'];?></p>
                                                </div>
                                                <div class="p-1"  style="width: 35%;">
                                                    <p><?php echo $orderRow['deptMem'];?></p>
                                                </div>
                                                <div class="p-1"  style="width: 35%;">
                                                    <p><?php echo $orderRow['orderBy'];?></p>
                                                </div>
                                                <div class="p-1" style="width: 35%;">
                                                    <p> <?php echo $orderRow['supplierName'];?></p>
                                                </div>
                                                <div class="p-1" style="width: 35%;">
                                                    <p><?php echo date('d-m-y h:i', strtotime($orderRow['ordDateTime']) );?></p>
                                                </div>
                                                <!-- <div class="p-1 mbrRuntsk" style="width: 30%;">
                                                    <p><?php //echo showPrice($orderRow['ordAmt'], $getDefCurDet['curCode']); ?></p>
                                                </div> -->
                                                <!-- <div class="p-1 mbrRuntsk" style="width: 30%;">
                                                    <p></p>
                                                </div> -->
                                                
                                            </div>
                                            <div class="p-1 val-run">
                                                <p class="otherCurr"><?php echo showPrice($orderRow['ordAmt'], $getDefCurDet['curCode']); ?></p>
                                            </div>

                                        </div>
                                        <div class="align-items-center tmpStatus togleOrder">
                                            <div class="py-1 px-1 d-flex align-items-center stsBar">
                                                <div class="task-status">
                                                    <p><?php
				if ($orderRow['ordType'] == 1)
				{
					get_all_type_of_status_of_orderType($orderRow['ordType'],$orderRow['id'],$orderRow['status'],$checkOrdAssing,$timeTrackDet);
				}
				elseif ($orderRow['ordType'] == 2)
				{
					get_all_type_of_status_of_requisitionType($orderRow['ordType'],$orderRow['id'],$orderRow['status'],$checkOrdAssing,$timeTrackDet);
				}
				elseif ($orderRow['ordType'] == 3)
				{
					get_all_type_of_status_of_new_stockTake($orderRow['ordType'],$orderRow['status']);
				}
				?></p>
                                                </div>
                                                <div class="d-flex align-items-center payOptnreq" style="
    width: 32%;
">   
                                 <?php
				if ($orderRow['ordType'] == 1)
				{
					get_all_order_action_of_order_type($orderRow['ordType'],$orderRow['status'],$checkOrdAssing,$timeTrackDet,$orderRow['id'],$_SESSION['designation_id'],$_SESSION['accountId'], $orderRow['orderReceivedByMobUser']);
				}

				if ($orderRow['ordType'] == 2) 
				{
					get_all_order_action_of_requisition_type($orderRow['ordType'],$orderRow['status'],$checkOrdAssing,$timeTrackDet,$orderRow['id'],$_SESSION['designation_id'],$_SESSION['accountId']);
				}

				if ($orderRow['ordType'] == 3) 
				{
					// get_editStockTake_permission($_SESSION['designation_id'],$_SESSION['accountId'],$orderRow['id']);
				}
				
				?>
                                                    <!-- <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="isuOut"></span>
                                                            <p class="btn2 cn-btn">Issue out</p>
                                                        </a>
                                                    </div>
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="assIgn"></span>
                                                            <p class="btn2">Assign</p>
                                                        </a>
                                                    </div> -->
                                                    <!-- <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                        <a href="javascript:void(0)">
                                                            <p class="h3">Inv</p>
                                                        </a>
                                                    </div> -->
                                                </div>
                                                <div class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="assIgn"></span>
                                                            <p class="btn2">View Details</p>
                                                        </a>
                                                 </div>
                                                 <?php 
			if($orderRow['ordType'] == 1)
			{
			//issue in
				?>

                                                <div class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="assIgn"></span>
                                                            <p class="btn2">Details(Supplier)</p>
                                                        </a>
                                                 </div>

                                                <?php 
			} 
			elseif($orderRow['ordType'] == 2)
			{
			//issue out
				?>

                                              

                                                <?php
			} ?>
                                                <div class="d-flex align-items-center dleOptnreq">
                                                	<?php 
                                                    get_deleteOrder_permission($_SESSION['designation_id'], $_SESSION['accountId'], $orderRow['id'], $orderRow['ordType']);

                                                	?>
                                                 </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <!-- <div class="py-1 tmpBar">&nbsp;</div> -->
                                    <div class="drpOpn d-flex justify-content-center align-items-center">
                                        <a href="javascript:void(0)" class="statusLink run-stsLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!-- Confirmed Member Part End -->
                        </div>
                        <?php 
                        }//End of whileloop

                        ?>
                        
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
   <form action="" method="post" id="assignUser" name="assignUser">
    <div class="modal" tabindex="-1" id="assign-order" aria-labelledby="edit-Assign-OrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php echo showOtherLangText('Assign Order to User') ?></h1>
                </div>
                <div class="modal-body">
                    
                    <div>
                    <input type="hidden" name="orderType" class="orderType" value="">
                    <input type="hidden" name="orderId" class="orderId" value="">
                    <input type="hidden" name="checkedTotal" id="checkedTotal" value="">
                </div>
                   <div>
                    <strong class="checkAllSectionBox">
                        <input type="checkbox" class="CheckAllOptions" id="CheckAllOptions">
                        <label>
                            <?php echo showOtherLangText('Check All') ?>
                        </label>
                    </strong>
                </div>
                <div class="mobUserList">
                    
                </div>   
                </div>
                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="submit" class="btn sub-btn std-btn"><?php echo showOtherLangText('Save'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
function getDelNumb(canId, ordType) {

    $("#dialog").dialog({
        autoOpen: false,
        modal: true,
        //title     : "Title",
        buttons: {
            '<?php echo showOtherLangText('Yes') ?>': function() {
                //Do whatever you want to do when Yes clicked
                $(this).dialog('close');
                window.location.href = 'runningOrders.php?canId=' + canId + '&type=' + ordType;
            },

            '<?php echo showOtherLangText('No') ?>': function() {
                //Do whatever you want to do when No clicked
                $(this).dialog('close');
            }
        }
    });

    $("#dialog").dialog("open");
    $('.custom-header-text').remove();
    $('.ui-dialog-content').prepend(
        '<div class="custom-header-text"><span><?php echo showOtherLangText('Queue1.com Says') ?></span></div>');
}

function AssignOrder(orderType, orderId) {

    $('.orderType').val(orderType);
    $('.orderId').val(orderId);

    $.ajax({
            method: "POST",

            data: {
                assignedOrderType: orderType,
                assignedOrderId: orderId
            }
        })
        .done(function(htmlRes) {
            $('.mobUserList').html(htmlRes);

            $("#CheckAllOptions").on('click', function() {

                $('.optionCheck:checkbox').not(this).prop('checked', this.checked);

                var totalCount = $('.optionCheck').length;
                var totalCheckedCount = $('.optionCheck:checked').length;

                if (totalCheckedCount == 0) {
                    $('#checkedTotal').val(0);
                } else {
                    $('#checkedTotal').val(1);
                }
            });

            var totalCount = $('.optionCheck').length;
            var totalCheckedCount = $('.optionCheck:checked').length;

            if (totalCheckedCount == 0) {
                $('#checkedTotal').val(0);
            } else {
                $('#checkedTotal').val(1);
            }

            if (totalCount == totalCheckedCount) {
                $('#CheckAllOptions').prop('checked', true);
            } else {
                $('#CheckAllOptions').prop('checked', false);
            }

            $(".optionCheck").on('click', function() {

                var totalCount = $('.optionCheck').length;
                var totalCheckedCount = $('.optionCheck:checked').length;

                if (totalCheckedCount == 0) {
                    $('#checkedTotal').val(0);
                } else {
                    $('#checkedTotal').val(1);
                }

                if (totalCount == totalCheckedCount) {

                    $('#CheckAllOptions').prop('checked', true);
                } else {
                    $('#CheckAllOptions').prop('checked', false);
                }
            });
        });

}
</script>