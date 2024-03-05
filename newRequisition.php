<?php include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

//check page permission
$checkPermission = permission_denied_for_section_pages($_SESSION['designation_id'],$_SESSION['accountId']);

if (!in_array('2',$checkPermission))
{
    echo "<script>window.location='index.php'</script>";
}

// Get column permission of requisition
$getColumnPermission = get_column_permission($_SESSION['designation_id'], $_SESSION['accountId'], 2);

//get member permission
// $memberCond = '';
// if (!empty(get_member_permission($_SESSION['designation_id'], $_SESSION['accountId']))) {

// $memberCond .= " AND dssp.designation_id = '".$_SESSION['designation_id']."' AND dssp.designation_section_permission_id = '2' AND type = 'member' ";
// }


//Add item charges in list
if( isset($_GET['itemCharges']) && $_GET['itemCharges'] > 0 && $_GET['feeType']=='1' )
{
    $_SESSION['itemCharges'][$_GET['feeType']][$_GET['itemCharges']] = $_GET['itemCharges'];
}

//Add order charges in list
if( isset($_GET['itemCharges']) && $_GET['itemCharges'] > 0 && $_GET['feeType']=='3' )
{
    $_SESSION['itemCharges'][$_GET['feeType']][$_GET['itemCharges']] = $_GET['itemCharges'];
}


if($_REQUEST['recMemberId'] != '')
{
    if ($_SESSION['recMemberId'] !=$_REQUEST['recMemberId'] ) {
        deleteRecusitionTempData($_SESSION['id']);
        unset( $_SESSION['deptId'] );

        if (isset($_SESSION['itemCharges'])) {
            unset($_SESSION['itemCharges']);
        }

    }

    $_SESSION['recMemberId'] = $_REQUEST['recMemberId'];
}

if (isset($_SESSION['recMemberId'])) {
    $sqlQry = " SELECT * FROM tbl_deptusers WHERE id = '".$_SESSION['recMemberId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $deptRes = mysqli_query($con,$sqlQry);
    $deptRow = mysqli_fetch_array($deptRes);
    $_SESSION['deptId'] = $deptRow['deptId'];
}


if( isset($_GET['clearTempPro']) )
{
    deleteRecusitionTempData($_SESSION['id']);
    unset( $_SESSION['itemCharges'] );
    unset( $_SESSION['deptId'] );
    unset( $_SESSION['recMemberId'] );

    echo '<script>window.location = "addRecusation.php?tempDataCleared=1"</script>';
}


//add item fee & custom fee modal box 
$sql = " SELECT * FROM tbl_custom_items_fee WHERE visibility='1' AND account_id='".$_SESSION['accountId']."' ";
$result = mysqli_query($con, $sql);

$sqlQry = " SELECT * FROM tbl_order_fee WHERE visibility='1' AND account_id='".$_SESSION['accountId']."' ";
$ordFeeFetch = mysqli_query($con, $sqlQry);



//insert custom charge 
if( !empty($_POST['itemName']) )
{ 
    $showHideInList = isset($_POST['visibility']) ? 1 : 0;

    $sql = "INSERT INTO `tbl_custom_items_fee` SET 
    `itemName` = '".$_POST['itemName']."',
    `unit` = '".$_POST['unit']."',
    `amt` = '".$_POST['itemFeeAmt']."',
    `visibility` = '".$showHideInList."',
    `account_id` = '".$_SESSION['accountId']."'  ";
    mysqli_query($con, $sql);
    $itemCharges = mysqli_insert_id($con);
    $_SESSION['itemCharges'][1][$itemCharges] = $itemCharges;
    echo '<script>window.location = "addRecusation.php"</script>';
}


//insert order charge
if( !empty($_POST['feeName']) )
{ 
    $showHideInList = isset($_POST['visibility']) ? 1 : 0;

    $sql = "INSERT INTO `tbl_order_fee` SET 
    `feeName` = '".$_POST['feeName']."',
    `feeType` = '".$_POST['feeType']."',
    `amt` = '".$_POST['amt']."',
    `visibility` = '".$showHideInList."',
    `account_id` = '".$_SESSION['accountId']."'  ";

    mysqli_query($con, $sql);

    $itemCharges = mysqli_insert_id($con);

    $_SESSION['itemCharges'][3][$itemCharges] = $itemCharges;

    echo '<script>window.location = "addRecusation.php"</script>';

}


$recRowsTemp = getRecusitionTempData($_SESSION['id']);
$proTempRows = [];
if( !empty($recRowsTemp['proDetails']) ) 
{
    $proTempRows = $recRowsTemp['proDetails'];
    $proTempNotes = $recRowsTemp['notesDetail'];
}

//place order or confirm order
if(isset($_POST['placeOrder']))
{
	
	unset($_SESSION['productIds']);
	$noProductQtyFilled = true;
    //Update for new items
    foreach($_POST['productIds'] as $productId)
    {	

        if( isset($_SESSION['productIds'][$productId]) && ( $_POST['qty'][$productId] == '' || $_POST['qty'][$productId] == 0) )
        {
            unset($_SESSION['productIds'][$productId]);
        }
        elseif( $_POST['qty'][$productId] > 0 )
        {	
			$noProductQtyFilled = false;
			
            $_SESSION['productIds'][$productId]['qty'] = $_POST['qty'][$productId];
            $_SESSION['productIds'][$productId]['price'] = $_POST['price'][$productId];
            $_SESSION['productIds'][$productId]['notes'] = $_POST['notes'][$productId];

        }//end elseif 

    }//End foreach
	
	
	if( $noProductQtyFilled )
    {
        echo '<script>window.location = "addRecusation.php?errorProduct=1"</script>';
		exit;
    }


    deleteRecusitionTempData($_SESSION['id']);

    $sqlSet = " SELECT ordNumber FROM tbl_orders WHERE account_id = '".$_SESSION['accountId']."'  ORDER BY id DESC LIMIT 1 ";
    $ordQry = mysqli_query($con, $sqlSet);
    $ordResult = mysqli_fetch_array($ordQry);
    $ordNumber = $ordResult['ordNumber'] > 0 ? ($ordResult['ordNumber']+1) : 100000;

    $qry = " INSERT INTO `tbl_orders` SET 
    `ordType` = 2,
    `ordNumber` = '".$ordNumber."',
    `recMemberId`  = '".$_POST['recMemberId']."',
    `orderBy`  = '".$_SESSION['id']."',
    `deptId` = '".$_SESSION['deptId']."',
    `ordDateTime` = '".date('Y-m-d h:i:s')."',
    `ordAmt` = 0,
    `account_id` = '".$_SESSION['accountId']."', 
    `status` = 1  ";
    mysqli_query($con, $qry);
    $ordId = mysqli_insert_id($con);


    $ordAmt = 0;
    foreach($_SESSION['productIds'] as $productId=>$productRow)
    {	
		
		
		if($productRow['qty'] == '')
		{
			continue;
		}
		

        $autFillQty = 0;
        if( isset($_POST['autoFillQty'][$productId]) )
        {
            $autFillQty = $_POST['autoFillQty'][$productId];
        }

        $prodPrice = str_replace(',', '', $productRow['price']);
		
		 //check stock qty
		$sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$productId."' AND account_id = '".$_SESSION['accountId']."'  ";
		$stkQry = mysqli_query($con, $sql);
		$stkRow = mysqli_fetch_array($stkQry);

        $values[] .= " (NULL, 
        '".$_SESSION['accountId']."',
        '".$ordId."',
        '".$productId."', 
        '".$prodPrice."',
        '".$productRow['qty']."',
        '".$productRow['qty']."',
        '".($prodPrice*$productRow['qty'])."',
        '".$autFillQty."',
        '".$productRow['notes']."',
        '".$stkRow['lastPrice']."',
        '".$stkRow['stockPrice']."',
        '".($stkRow['qty'] - $productRow['qty'])."'  ) ";

        $ordAmt += ($prodPrice * $productRow['qty']);
    }


    //insert custom charges ----------------
    $noteArr= $_POST['itemNotes'];
    $ordTotArr = insertReqCustomCharges($ordId, $ordAmt, $noteArr,$_SESSION['deptId'] );
    $ordAmt += $ordTotArr['chargesTot'];
    //end----------------------


    $insertQry = " INSERT INTO `tbl_order_details` (`id`, `account_id`, `ordId`, `pId`, `price`, `requestedQty`, `qty`, `totalAmt`, `autoFillQty`, `note`, `lastPrice`, `stockPrice`, `stockQty` ) VALUES  ".implode(',', $values);

    mysqli_query($con, $insertQry);

    $updateQry = " UPDATE `tbl_orders` SET `ordAmt` = '".$ordAmt."' WHERE id = '".$ordId."' AND account_id='".$accountId."' ";
    mysqli_query($con, $updateQry);

    $productsRows = $_SESSION['productIds'];
    unset($_SESSION['productIds']);

    if ($_POST['qty'][$productId] >= 0) 
    {

        requisitionTotalValue($ordId);

        if( isset($_SESSION['itemCharges']) || isset($_SESSION['deptId']) || isset($_SESSION['recMemberId']))
        {
            unset($_SESSION['itemCharges']);
            unset($_SESSION['deptId']);
            unset($_SESSION['recMemberId']);	
        }

        //Insert few data in order journey tbl to show journey 
        $sql = " SELECT * FROM tbl_orders WHERE id = '".$ordId."' AND account_id = '".$_SESSION['accountId']."' ";
        $res = mysqli_query($con, $sql);
        $resRow = mysqli_fetch_array($res);

        $qry = " INSERT INTO `tbl_order_journey` SET 
        `account_id` = '".$_SESSION['accountId']."',
        `orderId` = '".$ordId."',
        `userBy`  = '".$_SESSION['id']."',
        `ordDateTime` = '".date('Y-m-d h:i:s')."',
        `amount` = '".$resRow['ordAmt']."',
        `orderType` = '".$resRow['ordType']."',
        `notes` = 'Added new Requisition',
        `action` = 'submit' ";
        mysqli_query($con, $qry);

        echo '<script>window.location = "runningOrders.php?requisitionAdded=1&orderId='.$ordId.'"</script>';

    }
    else
    {
        echo '<script>window.location = "addRecusation.php?errorProduct=1"</script>';
    }

}

//delete item/order level charges
if( isset($_GET['delId']) && $_GET['delId'] )
{
    unset($_SESSION['itemCharges'][$_GET['feeType']][$_GET['delId']]);

    echo '<script>window.location = "addRecusation.php?delete=1&deptId='.$_SESSION['deptId'].'"</script>';
}


$cond = '';
if($_SESSION['deptId'] != '')
{
    $cond .= " AND pd.deptId = '".$_SESSION['deptId']."' ";
	
	$orderBy = isset($_GET['sort']) ?  ' t.qty desc' : 'p.itemName';

     $sqlSet = " SELECT p.*, IF(u.name!='',u.name,p.unitC) as countingUnit, s.qty stockQty FROM tbl_products p
    LEFT JOIN tbl_units u ON(u.id=p.unitC) AND u.account_id = p.account_id 
    INNER JOIN tbl_productdepartments pd ON(p.id = pd.productId) AND p.account_id = pd.account_id
    INNER JOIN tbl_stocks s ON(s.pId = p.id) AND s.account_id = p.account_id 
	

	LEFT JOIN tbl_recusition_items_temp t ON(t.pId = p.id) AND t.account_id = p.account_id AND t.deptId = '".$_SESSION['deptId']."' AND t.userId='".$_SESSION['id']."'
	
    WHERE p.account_id = '".$_SESSION['accountId']."' ".$cond. " AND p.status=1 GROUP BY(id)  ORDER BY ".$orderBy;
    $proresultSet = mysqli_query($con, $sqlSet);
}

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
                            <h1 class="h1"><?php echo showOtherLangText('New Requisition'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('New Requisition'); ?></h1>
                                </div>
                            </div>
                            <div class="user d-flex align-items-center">
                                <img src="Assets/images/user.png" alt="user">
                                <p class="body3 m-0 d-inline-block">User</p>
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
                                            <h4 class="subTittle1"><span>Order#:</span> <span>332974</span></h4>
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
                    <?php

                    //Add custom item charge Here
                    $totalCustomCharges = 0;
                    //$x = 0;
                    if( isset($_SESSION['itemCharges'][1]) && count($_SESSION['itemCharges'][1]) > 0  )
                    {
                        $itemIds = implode(',', $_SESSION['itemCharges'][1]);
                        
                        $sqlSet = " SELECT * FROM tbl_custom_items_fee WHERE id IN(".$itemIds.")  AND account_id = '".$_SESSION['accountId']."'  ";
                        $resRows = mysqli_query($con, $sqlSet);

                    }
                    ?>
                    <div class="container nordPrice position-relative">
                        <!-- Item Table Head Start -->
                        <div class="d-flex align-items-center itmTable">
                            <div class="reqImg tb-head">
                                <p><?php echo showOtherLangText('Image'); ?></p>
                            </div>
                            <div class="reqCnt-Fst d-flex align-items-center">
                                <div class="reqClm-Itm tb-head">
                                    <p><?php echo showOtherLangText('Item'); ?></p>
                                </div>
                                <div class="reqClm-Br tb-head">
                                    <p><?php echo showOtherLangText('Bar Code'); ?></p>
                                </div>
                                <div class="reqClm-Prc tb-head">
                                    <p><?php echo showOtherLangText('Price'); ?></p>
                                </div>
                                <div class="reqClm-Unit tb-head">
                                    <p><p><?php echo showOtherLangText('C.Unit'); ?></p></p>
                                </div>
                            </div>
                            <div class="reqSt-Qty tb-head">
                                <p><?php echo showOtherLangText('S.Quantity'); ?></p>
                            </div>
                            <div class="reqCnt-Scnd d-flex align-items-center">
                                <div class="reqClm-Qty tb-head">
                                    <p><?php echo showOtherLangText('Quantity'); ?></p>
                                </div>
                                <div class="reqClm-Ttl tb-head">
                                    <p><?php echo showOtherLangText('Total'); ?></p>
                                </div>
                            </div>
                            <div class="requi-Hide">
                                <div class="reqClm-Note tb-head">
                                    <div class="mb-ReqCode"></div>
                                    <p><?php echo showOtherLangText('Note'); ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- Item Table Head End -->
                    </div>

                    <div id="boxscroll">
                        <div class="container cntTable">
                            <!-- Item Table Body Start -->
                        <?php 

                        while( $row = mysqli_fetch_array($resRows) ) //start custom item charge loop
                        {	
                            //$x++;
                            $totalCustomCharges += $row['amt'];
                            ?>
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
                            <?php 
                            } ?>


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