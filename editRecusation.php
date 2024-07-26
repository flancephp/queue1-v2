<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'edit_requisition' AND type_id = '0' AND designation_id = '".$_SESSION['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if ($permissionRow)
{
echo "<script>window.location='index.php'</script>";
}


// Get column permission of requisition
$getColumnPermission = get_column_permission($_SESSION['designation_id'], $_SESSION['accountId'], 2);

if (!isset($_SESSION['ordDeptId']))
{
	$selQry = " SELECT * FROM tbl_orders WHERE id='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."' ";
	$ordResult = mysqli_query($con, $selQry);
	$ordRow = mysqli_fetch_array($ordResult);

	$_SESSION['ordDeptId'] = $ordRow['deptId'];
}

if (isset($_GET['orderId'])) 
{

	$selQry = " SELECT * FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."' ";
	$tempOrdDetResult = mysqli_query($con, $selQry);
	$tempOrdDetRow = mysqli_fetch_array($tempOrdDetResult);
	
	if ( empty($tempOrdDetRow) )
	{
			$selQry = " SELECT * FROM tbl_order_details WHERE ordId='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."' ";
			$ordDetResult = mysqli_query($con, $selQry);
			while($ordDetRow = mysqli_fetch_array($ordDetResult))
			{
				$values[] .= " (NULL, 
				'".$_SESSION['accountId']."',
				'".$_GET['orderId']."', 
				'".$ordDetRow['pId']."',
				'".$ordDetRow['factor']."',
				'".$ordDetRow['price']."',
				'".$ordDetRow['requestedQty']."',
				'".$ordDetRow['qty']."',
				'".$ordDetRow['qtyReceived']."',
				'".$ordDetRow['totalAmt']."',
				'".$ordDetRow['note']."',
				'".$ordDetRow['lastPrice']."',
				'".$ordDetRow['stockPrice']."',
				'".$ordDetRow['stockQty']."',
				'".$ordDetRow['curPrice']."',
				'".$ordDetRow['currencyId']."',
				'".$ordDetRow['curAmt']."',
				'".$ordDetRow['customChargeId']."',
				'".$ordDetRow['customChargeType']."') ";
		
			}
			
			$insertQry = " INSERT INTO `tbl_order_details_temp` (`id`, `account_id`, `ordId`, `pId`, `factor`, `price`, `requestedQty`, `qty`, `qtyReceived`, `totalAmt`,`note`, `lastPrice`, `stockPrice`, `stockQty`, `curPrice`, `currencyId`, `curAmt`, `customChargeId`, `customChargeType`) VALUES  ".implode(',', $values);
				mysqli_query($con, $insertQry);
	}

}



//Update order
if(isset($_POST['updateOrder']))
{

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $resRowOld = mysqli_fetch_array($res);
    
	//Update for new items
	foreach($_POST['productIds'] as $productId)
	{
			
			if( isset($_POST['qty'][$productId]) && $_POST['qty'][$productId] < 1 )
			{
				 $Qry = " DELETE FROM  `tbl_order_details` WHERE ordId = '".$_GET['orderId']."' AND pId = '".$productId."' ";
				 mysqli_query($con, $Qry);
			}
			elseif( isset($_POST['qty'][$productId]) && $_POST['qty'][$productId] > 0 )
			{
			
				$prodPrice = str_replace(',', '', $_POST['price'][$productId]);
				//check stock qty
				$sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$productId."'  AND account_id = '".$_SESSION['accountId']."' ";
				$stkQry = mysqli_query($con, $sql);
				$stkRow = mysqli_fetch_array($stkQry);
			
			
				//Add new product in tbl_order_details on update button
				$sqlQry = " SELECT * FROM tbl_order_details WHERE ordId='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."' AND pId='".$productId."' ";
				$orderDetailsResult = mysqli_query($con, $sqlQry);
				$orderDetailsResRow = mysqli_fetch_array($orderDetailsResult);
			
					if ( !isset($orderDetailsResRow['pId']) )
					{
						$sql = "INSERT INTO `tbl_order_details` SET
						`ordId` = '".$_GET['orderId']."',
						`pId` = '".$productId."',
						`price` = '".$prodPrice."',
						`requestedQty` = '".$_POST['qty'][$productId]."',
						`qty` = '".$_POST['qty'][$productId]."',
						`totalAmt` = '".($prodPrice*$_POST['qty'][$productId])."',
						`note` = '".$_POST['notes'][$productId]."',
						`lastPrice` = '".$stkRow['lastPrice']."',
						`stockPrice` = '".$stkRow['stockPrice']."',
						`stockQty` = '".($stkRow['qty'] - $_POST['qty'][$productId])."',
						`account_id` = '".$_SESSION['accountId']."'   ";
						mysqli_query($con, $sql);
					}
					else
					{    
						$upQry = " UPDATE  `tbl_order_details` SET
						`price` = '".$prodPrice."', 
						`qty` = '".$_POST['qty'][$productId]."', 
						`totalAmt` = '".($prodPrice*$_POST['qty'][$productId])."', 
						`note` = '".$_POST['notes'][$productId]."',
						`lastPrice` = '".$stkRow['lastPrice']."',
						`stockPrice` = '".$stkRow['stockPrice']."',
						`stockQty` = '".($stkRow['qty'] - $_POST['qty'][$productId])."'
						WHERE ordId = '".$_GET['orderId']."' AND pId = '".$productId."'  AND account_id = '".$_SESSION['accountId']."' ";
					
						mysqli_query($con, $upQry);
					}
				
				}//end if
			
			}//End foreach

	$sql=" SELECT * FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' and customChargeType > 0 ";
	$sqlSet= mysqli_query($con, $sql);	
	while($tempOrdDetRow = mysqli_fetch_array($sqlSet))
	{
	
			$sql=" SELECT * FROM tbl_order_details WHERE ordId='".$tempOrdDetRow['ordId']."' AND account_id = '".$_SESSION['accountId']."' and customChargeId='".$tempOrdDetRow['customChargeId']."' and customChargeType='".$tempOrdDetRow['customChargeType']."'  ";
			$ordQryData= mysqli_query($con, $sql);	
			$ordDetRowCheck = mysqli_fetch_array($ordQryData);
			
			if ( $ordDetRowCheck )
			{ 
			
			$updateQry = " UPDATE tbl_order_details SET note='".$tempOrdDetRow['note']."' WHERE ordId='".$tempOrdDetRow['ordId']."' AND account_id = '".$_SESSION['accountId']."' and customChargeId='".$tempOrdDetRow['customChargeId']."' and customChargeType='".$tempOrdDetRow['customChargeType']."' ";
			mysqli_query($con, $updateQry); 
			
			}
			else
			{
			
			
			$insertQry = " INSERT INTO `tbl_order_details`
			SET `account_id` = '".$tempOrdDetRow['account_id']."',
			`ordId` = '".$tempOrdDetRow['ordId']."',
			`note` = '".$tempOrdDetRow['note']."',
			`customChargeType` = '".$tempOrdDetRow['customChargeType']."',
			`customChargeId` = '".$tempOrdDetRow['customChargeId']."',
			`price` = '".$tempOrdDetRow['price']."',
			`qty` = '1',
			`totalAmt` = '".$tempOrdDetRow['totalAmt']."' ";
			
			mysqli_query($con, $insertQry); 
			
			}
	
	}//end while


		//set order net value
		requisitionTotalValue($_GET['orderId']);
		

        //Insert few data in order journey tbl to show journey 
    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $resRow = mysqli_fetch_array($res);
//echo $resRowOld['ordAmt'] .'-'. $resRow['ordAmt'];
//echo "<br>";
    $diffPrice =  $resRow['ordAmt']-$resRowOld['ordAmt'];
     $notes = 'Requisition edited(Price Diff: '.$diffPrice .' )';

    $qry = " INSERT INTO `tbl_order_journey` SET 
    `account_id` = '".$_SESSION['accountId']."',
    `orderId` = '".$resRow['id']."',
    `userBy`  = '".$_SESSION['id']."',
    `ordDateTime` = '".date('Y-m-d h:i:s')."',
    `amount` = '".$resRow['ordAmt']."',
    `orderType` = '".$resRow['ordType']."',
    `notes` = '".$notes."',
    `action` = 'edit' ";
    mysqli_query($con, $qry);

		
		//delete order_details_temp data after form submit
		$delQry=" DELETE FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."' ";
		mysqli_query($con, $delQry);
		
		echo '<script>window.location="runningOrders.php?updated=1&orderId='.$_GET['orderId'].'"</script>';

}//end udpdate order

//add item fee & custom fee modal box 
$sql = " SELECT * FROM tbl_custom_items_fee WHERE visibility='1' AND account_id='".$_SESSION['accountId']."' ";
$result = mysqli_query($con, $sql);

$sqlQry = " SELECT * FROM tbl_order_fee WHERE visibility='1' AND account_id='".$_SESSION['accountId']."' ";
$ordFeeFetch = mysqli_query($con, $sqlQry);


//Add item charges in list
if( isset($_GET['itemCharges']) && $_GET['itemCharges'] > 0 && $_GET['feeType']=='1' )
{
	editCustomCharge($_GET['orderId'],$_GET['feeType'], $_GET['itemCharges'],$_SESSION['ordDeptId'], 1);
}

//Add order charges in list
if( isset($_GET['itemCharges']) && $_GET['itemCharges'] > 0 && $_GET['feeType']=='3' )
{
	editCustomCharge($_GET['orderId'],$_GET['feeType'], $_GET['itemCharges'],$_SESSION['ordDeptId'], 1);
}


if( !empty($_POST['itemName']) )
{ 
	$showHideInList = isset($_POST['visibility']) ? 1 : 0;
	
	$sql = "INSERT INTO `tbl_custom_items_fee` SET 
	`itemName` = '".$_POST['itemName']."',
	`unit` = '".$_POST['unit']."',
	`amt` = '".$_POST['itemFeeAmt']."',
	`visibility` = '".$showHideInList."',
	`account_id` = '".$_SESSION['accountId']."' 
	";
	mysqli_query($con, $sql);
	$itemCharges = mysqli_insert_id($con);
	
	editCustomCharge($_GET['orderId'],1,$itemCharges,$_SESSION['ordDeptId'], 1);
	
	echo '<script>window.location="editRecusation.php?orderId='.$_GET['orderId'].'"</script>';

}


if( !empty($_POST['feeName']) )
{ 
	$showHideInList = isset($_POST['visibility']) ? 1 : 0;
	
	$sql = "INSERT INTO `tbl_order_fee` SET 
	`feeName` = '".$_POST['feeName']."',
	`feeType` = '".$_POST['feeType']."',
	`amt` = '".$_POST['amt']."',
	`visibility` = '".$showHideInList."',
	`account_id` = '".$_SESSION['accountId']."' 
	";
	
	mysqli_query($con, $sql);
	
	$itemCharges = mysqli_insert_id($con);
	
	editCustomCharge($_GET['orderId'],3,$itemCharges,$_SESSION['ordDeptId'], 1);
	
	echo '<script>window.location="editRecusation.php?orderId='.$_GET['orderId'].'"</script>';

}
//end

//delete item level / order level charges
if( isset($_GET['delId']) && $_GET['orderId'])
{ 

	$sql=" SELECT * FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' and id='".$_GET['delId']."' ";
	$sqlSet= mysqli_query($con, $sql);	
	$tempOrdDetRow = mysqli_fetch_array($sqlSet);
	
	$sql= " DELETE FROM tbl_order_details WHERE ordId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' and customChargeId='".$tempOrdDetRow['customChargeId']."' and customChargeType='".$tempOrdDetRow['customChargeType']."' ";
	$resultSet= mysqli_query($con, $sql);
	
	$sql= " DELETE FROM tbl_order_details_temp WHERE id='".$_GET['delId']."' AND account_id = '".$_SESSION['accountId']."' ";
	$resultSet= mysqli_query($con, $sql);
	
	
	echo '<script>window.location="editRecusation.php?orderId='.$_GET['orderId'].'&delete=1"</script>';

}//end 

$cond = '';

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>New Requisition - Queue1</title>
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
                            <h1 class="h1"><?php echo showOtherLangText('Edit Requisition'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Edit Requisition'); ?></h1>
                                </div>
                            </div>
                            <?php
                            $sqlSet = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
                            $resultSet = mysqli_query($con, $sqlSet);
                            $ordRow = mysqli_fetch_array($resultSet);
                            ?>
                            <div class="user d-flex align-items-center">
                                <img src="Assets/images/user.png" alt="user">
                                <p class="body3 m-0 d-inline-block"><?php echo showOtherLangText('User'); ?></p>
                            </div>
                            <div class="acc-info">
                                <img src="Assets/icons/Q.svg" alt="Logo" class="q-Logo">
                                <!-- <h1>Q</h1> -->
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

                <section class="ordDetail">
                    <div class="tpBar-grn"></div>
                    <div class="stcPart">
                        <div class="container topOrder newReq nwOrder-Div">
                            <div class="row">
                                <div class="sltSupp nwOrd-Num">
                                    <div class="ord-Box">
                                        <div class="ordNum">
                                            <h4 class="subTittle1"><span>Order#:</span> <span><?php echo $ordRow['ordNumber'];?></span></h4>
                                        </div>
                                        <div class="ordDate">
                                            <h4 class="subTittle1">23/05/2022</h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="ordInfo reqInfo newFeatures">
                                    <div class="container">
                                        <div class="mbFeature">
                                            <div class="row gx-3 justify-content-end">
                                                <div class="col-md-5 text-center">
                                                    <div class="row featRow">
                                                        <div class="col-md-4 ordFeature">
                                                            <a href="javascript:void(0)" class="tabFet">
                                                                <span class="saveList"></span>
                                                                <p class="btn2">Save list</p>
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 ordFeature border-start border-end">
                                                            <a href="javascript:void(0)" class="tabFet">
                                                                <span class="loadList"></span>
                                                                <p class="btn2">Load List</p>
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 ordFeature"><a href="javascript:void(0)"
                                                                class="tabFet">
                                                                <span class="clear"></span>
                                                                <p class="btn2">Clear</p>
                                                            </a></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 text-center dskAuto">
                                                    <div class="row featRow">
                                                        <div class="ordFeature">
                                                            <a href="javascript:void(0)" class="tabFet">
                                                                <span class="autoFill"></span>
                                                                <p class="btn2">Auto Fill</p>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 text-center">
                                                    <div class="row featRow">
                                                        <div class="col-md-4 ordFeature mbAuto">
                                                            <a href="javascript:void(0)" class="tabFet">
                                                                <span class="autoFill"></span>
                                                                <p class="btn2">Auto Fill</p>
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 ordFeature border-start border-end">
                                                            <a href="javascript:void(0)" class="tabFet">
                                                                <span class="viewOrder"></span>
                                                                <p class="btn2">View Order</p>
                                                            </a>
                                                        </div>
                                                        <div class="col-md-4 ordFeature drpFee position-relative">
                                                            <a href="javascript:void(0)" class="dropdown-toggle tabFet"
                                                                role="button" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <span class="fee"></span>
                                                                <p class="btn2">Fee <i
                                                                        class="fa-solid fa-angle-down"></i>
                                                                </p>
                                                            </a>

                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0)">Fixed
                                                                        Fee</a></li>
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0)">Open
                                                                        Fee</a></li>
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0)">Other</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="smBtn nwNxt-Btn">
                                    <div class="btnBg">
                                        <a href="editRequisition.php" class="btn sub-btn"><span
                                                class="align-middle">Submit</span> <i
                                                class="fa-solid fa-angle-right"></i></a>
                                    </div>
                                    <div class="fetBtn">
                                        <a href="javascript:void(0)">
                                            <img src="Assets/icons/dashboard.svg" alt="dashboard">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container topOrder newReq">
                            <div class="row">
                                <div class="sltSupp">
                                    <!-- Select Supplier  -->
                                    <div class="btn-group glb-btn">
                                        <button type="button"
                                            class="btn body3 drp-btn dropdown-toggle dropdown-toggle-split d-flex align-items-center justify-content-between"
                                            data-bs-toggle="dropdown" aria-expanded="false"><span>Bar</span> <i
                                                class="fa-solid fa-angle-down"></i></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0)">Kitchen</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">House Keeping</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Others</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="btn-group glb-btn">
                                        <button type="button"
                                            class="btn body3 drp-btn dropdown-toggle dropdown-toggle-split d-flex align-items-center justify-content-between"
                                            data-bs-toggle="dropdown" aria-expanded="false"><span>Requisition By:</span>
                                            <i class="fa-solid fa-angle-down"></i></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0)">Requisition 1</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Requisition 2</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Requisition 3</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="input-group srchBx">
                                        <input type="search" class="form-control" placeholder="Search" id="srch"
                                            aria-label="Search">
                                        <div class="input-group-append">
                                            <button class="btn" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                                <div class="ordInfo reqInfo">
                                    <div class="container">
                                        <div class="prcTable">
                                            <div class="price justify-content-between">
                                                <div class="p-2 delIcn text-center"></div>
                                                <div class="p-2 txnmRow">
                                                    <p>Total</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                        <p>23,990 $</p>
                                                    </div>
                                                    <div class="p-2 otherCurr">
                                                        <p>23,990 €</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="price justify-content-between taxRow">
                                                <div class="p-2 delIcn text-center">
                                                    <a href="javascript:void(0)">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </a>
                                                </div>
                                                <div class="p-2 txnmRow">
                                                    <p>VAT 19%</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                        <p>11.362 $</p>
                                                    </div>
                                                    <div class="p-2 otherCurr">
                                                        <p>11.362 €</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="price justify-content-between grdTtl-Row">
                                                <div class="p-2 delIcn text-center"></div>
                                                <div class="p-2 txnmRow">
                                                    <p>Grand Total</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                        <p>23,990 $</p>
                                                    </div>
                                                    <div class="p-2 otherCurr">
                                                        <p>23,990 €</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container nordPrice position-relative">
                        <!-- Item Table Head Start -->
                        <div class="d-flex align-items-center itmTable">
                            <div class="reqImg tb-head">
                                <p><?php echo showOtherLangText('Image11'); ?></p>
                            </div>
                            <div class="reqCnt-Fst d-flex align-items-center">
                                <div class="reqClm-Itm tb-head">
                                    <p><?php echo showOtherLangText('Item'); ?></p>
                                </div>
                                <div class="reqClm-Br tb-head">
                                    <p><?php echo showOtherLangText('Bar Code'); ?></p>
                                </div>
                                <?php echo $getColumnPermission['item_price'] == 1 ? '<div class="reqClm-Prc tb-head">
                                    <p>'.showOtherLangText('Price').'</p></div>' : ''; ?>
                                
                                <div class="reqClm-Unit tb-head">
                                    <p><?php echo showOtherLangText('C.Unit'); ?></p>
                                </div>
                            </div>
                            <div class="reqSt-Qty tb-head">
                                <p>S.Quantity</p>
                            </div>
                            <div class="reqCnt-Scnd d-flex align-items-center">
                                <div class="reqClm-Qty tb-head">
                                    <p>Quantity</p>
                                </div>
                                <div class="reqClm-Ttl tb-head">
                                    <p>Total</p>
                                </div>
                            </div>
                            <div class="requi-Hide">
                                <div class="reqClm-Note tb-head">
                                    <div class="mb-ReqCode"></div>
                                    <p>Note</p>
                                </div>
                            </div>
                        </div>
                        <!-- Item Table Head End -->
                    </div>

                    <div id="boxscroll">
                        <div class="container cntTable">
                            <!-- Item Table Body Start -->

                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>Mango</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>Kg</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">On stock</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="Note">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>
                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>Mango</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>Kg</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">On stock</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="Note">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>
                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>Mango</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>Kg</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">On stock</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="Note">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>
                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>Mango</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>Kg</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">On stock</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="Note">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>
                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>Mango</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>Kg</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">On stock</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="Note">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>
                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>Mango</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>Kg</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">On stock</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="Note">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>
                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>Mango</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>Kg</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">On stock</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="Note">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>
                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>Mango</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>Kg</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">On stock</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="Note">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>
                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>Mango</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>Kg</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">On stock</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="Note">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>


                            <!-- Item Table Body End -->
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