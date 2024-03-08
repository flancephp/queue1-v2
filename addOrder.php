<?php include('inc/dbConfig.php'); //connection details

if (!isset($_SESSION['adminidusername']))
{
  echo '<script>window.location ="login.php"</script>';
}

if( isset($_GET['autoFill']) )
{	
	$_SESSION['autoFill'] = 1;
}

if( isset($_REQUEST['supplierId']) && $_SESSION['supplierId'] !=$_REQUEST['supplierId'] )
{
	unset($_SESSION['autoFill']);
}

if( isset($_SESSION['autoFill']) )
{
	$_GET['autoFill'] = $_SESSION['autoFill'];
}
//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

//check New Order page permission for session user
$checkPermission = permission_denied_for_section_pages($_SESSION['designation_id'],$_SESSION['accountId']);
if (!in_array('1',$checkPermission))
{
  echo "<script>window.location='index.php'</script>";
}

//get supplier permission
// $supCond = '';
// if (!empty(get_supplier_permission($_SESSION['designation_id'], $_SESSION['accountId']))) {

// $supCond .= " AND dssp.designation_section_permission_id = '1' AND dssp.designation_id = '".$_SESSION['designation_id']."' AND type = 'order_supplier' ";
// }

// Set currency Id in session if changes by user
if($_REQUEST['currencyId'] != '')
{
  $_SESSION['currencyId'] = $_REQUEST['currencyId'];
}
if(isset($_SESSION['currencyId']))
{ 
  $curDet = getCurrencyDet($_SESSION['currencyId']);
  $currencyId = $_SESSION['currencyId'];
}


//Add item level extra charges in list
if( isset($_GET['itemCharges']) && $_GET['itemCharges'] > 0 && $_GET['feeType']=='1' )
{
  $_SESSION['itemCharges'][$_GET['feeType']][$_GET['itemCharges']] = $_GET['itemCharges'];
}

//Add order level extra charges in list
if( isset($_GET['itemCharges']) && $_GET['itemCharges'] > 0 && $_GET['feeType']=='3' )
{
  $_SESSION['itemCharges'][$_GET['feeType']][$_GET['itemCharges']] = $_GET['itemCharges'];
}

// Set supplier id in session for the first time. again if user change supplier then delete all the record of previous selected supplier, currency, Other charges.
if( $_REQUEST['supplierId'] != '' )
{
  if($_SESSION['supplierId'] !=$_REQUEST['supplierId']) 
  {
    deleteOrderTempData($_SESSION['id']);

    if (isset($_SESSION['currencyId'])) 
    {
      unset($_SESSION['currencyId']);
    }

    if (isset($_SESSION['itemCharges'])) 
    {
      unset($_SESSION['itemCharges']);
    }

  }

  $_SESSION['supplierId'] = $_REQUEST['supplierId'];

}

$ordRowsTemp = getOrderTempData($_SESSION['id']);
$proTempRows = [];
if(!empty($ordRowsTemp['proDetails'])) 
{
  $proTempRows = $ordRowsTemp['proDetails'];
  $proTempNotes = $ordRowsTemp['notesDetail'];

}


if(!empty($_POST['itemName']))
{ 
  $showHideInList = isset($_POST['visibility']) ? 1 : 0;

  $sql = "INSERT INTO `tbl_custom_items_fee` SET 
  `itemName` = '".$_POST['itemName']."',
  `unit` = '".$_POST['unit']."',
  `amt` = '".$_POST['itemFeeAmt']."',
  `visibility` = '".$showHideInList."',
  `account_id` = '".$_SESSION['accountId']."' ";
  mysqli_query($con, $sql);
  $itemCharges = mysqli_insert_id($con);

  $_SESSION['itemCharges'][1][$itemCharges] = $itemCharges;

  echo '<script>window.location="addOrder.php?currencyId='.$_POST['currencyPopupForm'].'"</script>';

}


if(!empty($_POST['feeName']))
{ 
  $showHideInList = isset($_POST['visibility']) ? 1 : 0;

  $sql = "INSERT INTO `tbl_order_fee` SET 
  `feeName` = '".$_POST['feeName']."',
  `feeType` = '".$_POST['feeType']."',
  `amt` = '".$_POST['amt']."',
  `visibility` = '".$showHideInList."',
  `account_id` = '".$_SESSION['accountId']."' ";

  mysqli_query($con, $sql);

  $itemCharges = mysqli_insert_id($con);

  $_SESSION['itemCharges'][3][$itemCharges] = $itemCharges;

  echo '<script>window.location="addOrder.php?currencyId='.$_POST['currencyPopupForm'].'"</script>';

}

if(isset($_POST['placeOrder']))
{ 
  
  deleteOrderTempData($_SESSION['id']);

  $sqlSet = " SELECT ordNumber FROM tbl_orders WHERE account_id = '".$_SESSION['accountId']."'  ORDER BY id DESC LIMIT 1 ";
  $ordQry = mysqli_query($con, $sqlSet);
  $ordResult = mysqli_fetch_array($ordQry);

  //Give new order number for each new order
  $ordNumber = $ordResult['ordNumber'] > 0 ? ($ordResult['ordNumber']+1) : 100000;


  $qry = " INSERT INTO `tbl_orders` SET 
  `ordType` = 1,
  `supplierId` = '".$_POST['supplierId']."',
  `ordNumber` = '".$ordNumber."',
  `orderBy`  = '".$_SESSION['id']."',
  `ordDateTime` = '".date('Y-m-d h:i:s')."',
  `ordAmt` = 0,
  `ordCurId` = '".$currencyId."',
  `account_id` = '".$_SESSION['accountId']."', 
  `status` = 0  ";

  mysqli_query($con, $qry);
  $ordId = mysqli_insert_id($con);


  //Update for new items
  foreach($_POST['productIds'] as $productId)
  {
    if( isset($_SESSION['productIds'][$productId]) && $_POST['qty'][$productId] < 1 )
    {
      unset($_SESSION['productIds'][$productId]);
    }
    elseif($_POST['qty'][$productId] > 0)
    {
      $_SESSION['productIds'][$productId]['qty'] = $_POST['qty'][$productId];
      $_SESSION['productIds'][$productId]['price'] = $_POST['price'][$productId];
      $_SESSION['productIds'][$productId]['curAmt'] = $_POST['curAmt'][$productId];
      $_SESSION['productIds'][$productId]['factor'] = $_POST['factor'][$productId];
      $_SESSION['productIds'][$productId]['notes'] = $_POST['notes'][$productId];
      $_SESSION['productIds'][$productId]['suppliersId'] = $_SESSION['supplierId'];
    }
  }//End foreach  


  $ordAmt = 0;
  $ordAmtOtherCur = 0;
  $otherCurAmt = 0;
  if(!empty($curDet))
  { 
    $otherCurAmt = $curDet['amt'];
  }


  foreach($_SESSION['productIds'] as $productId=>$productRow)
  {       
    $values[] .= " (NULL, 
    '".$_SESSION['accountId']."', 
    '".$ordId."', 
    '".$productId."', 
    '".($productRow['factor'])."',
    '".($productRow['price'])."',
    '".($productRow['price']*$otherCurAmt)."',
    '".$productRow['qty']."',
    '".($productRow['factor']*$productRow['price']*$productRow['qty'])."',
    '".$productRow['notes']."',
    '".$currencyId."',
    '".($productRow['factor']*$productRow['price']*$productRow['qty']*$otherCurAmt)."') ";

    $ordAmt += ($productRow['factor']*$productRow['price'] * $productRow['qty']);

    $ordAmtOtherCur += ($productRow['factor']*$productRow['price']*$productRow['qty']*$otherCurAmt);

  }//End foreach

  //insert custom charges ----------------
  $noteArr= $_POST['itemNotes'];

  $ordTotArr = insertCustomCharges($ordId, $currencyId, $otherCurAmt, $ordAmt, $noteArr, $_SESSION['supplierId'] );

  $ordAmt += $ordTotArr['chargesTot'];
  $ordAmtOtherCur += $ordTotArr['chargesTotOtherCur'];
  //end----------------------

  if ($_POST['qty'][$productId] > 0)
  {

    $insertQry = " INSERT INTO `tbl_order_details` (`id`, `account_id`, `ordId`, `pId`, `factor`, `price`, `curPrice`, `qty`, `totalAmt`, `note`, `currencyId`, `curAmt`) VALUES  ".implode(',', $values);
    $result=mysqli_query($con, $insertQry); 

    orderNetValue($ordId,$currencyId);

    unset($_SESSION['productIds']);
    if(isset($_SESSION['itemCharges']) || isset($_SESSION['supplierId']) || isset($_SESSION['currencyId']))
    {
      unset($_SESSION['itemCharges']);
      unset($_SESSION['supplierId']);
      unset($_SESSION['currencyId']);
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
  `otherCur` = '".$resRow['ordCurAmt']."',
  `otherCurId` = '".$resRow['ordCurId']."',
  `orderType` = '".$resRow['ordType']."',
  `notes` = 'Added new order',
  `action` = 'submit' ";
  mysqli_query($con, $qry);


  echo '<script>window.location="runningOrders.php?added=1&orderId='.$ordId.'"</script>';


  }
  else
  {
    $currency = isset($_SESSION['currencyId']) ? $_SESSION['currencyId'] : 0;

    echo '<script>window.location="addOrder.php?errorProduct=1&currencyId='.$currencyId.'"</script>';

  }


}//End Place Order 

//to delete item/order level charges
if(isset($_GET['delId']) && $_GET['delId'])
{
  unset($_SESSION['itemCharges'][$_GET['feeType']][$_GET['delId']]);

  echo '<script>window.location="addOrder.php?delete=1&supplierId='.$_SESSION['supplierId'].'&currencyId='.$_SESSION['currencyId'].'"</script>';
}

if( $_GET['clear']==1 )
{
  unset($_SESSION['itemCharges']);
  unset($_SESSION['supplierId']);
  unset($_SESSION['currencyId']);
  deleteOrderTempData($_SESSION['id']);
  echo '<script>window.location ="addOrder.php"</script>';
}

//Add this part of code in main query where clause if supplier is set
$cond = '';
if( $_SESSION['supplierId'] != '' )
{
  $cond .= " AND p.id IN( SELECT productId FROM tbl_productsuppliers WHERE supplierId = '".$_SESSION['supplierId']."' AND account_id = '".$_SESSION['accountId']."' ) ";
}



$orderBy = isset($_GET['sort']) ?  ' t.qty desc' : 'p.itemName';

//Main query to fetch all the product according to supplier
 $sqlSet = " SELECT 
p.*, 
IF(u.name!='',u.name,p.unitP) AS purchaseUnit, 
s.qty AS stockQty 
FROM tbl_products p
LEFT JOIN tbl_stocks s
ON(s.pId=p.id) AND p.account_id = s.account_id
LEFT JOIN tbl_units u 
ON(u.id=p.unitP) AND u.account_id = p.account_id

LEFT JOIN tbl_order_items_temp t ON(t.pId = p.id) AND t.account_id = p.account_id AND t.userId='".$_SESSION['id']."' AND t.supplierId = '".$_SESSION['supplierId']."'

WHERE   p.status=1 ".$cond. "  AND p.account_id = '".$_SESSION['accountId']."' ORDER BY  ".$orderBy;

$proresultSet = mysqli_query($con, $sqlSet);

//All currency rows fetch from currency table except default currency
$curQry = " SELECT * FROM tbl_currency 
where is_default = 0 AND account_id = '".$_SESSION['accountId']."' ORDER BY currency ";
$currResultSet = mysqli_query($con, $curQry);

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

    <div class="container-fluid newOrder">
        <div class="row">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
            <?php require_once('nav.php');?>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('New Order'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('New Order'); ?></h1>
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


                <section class="ordDetail">
                    <div class="tpBar-grn"></div>
                    <div class="stcPart">
                        <div class="container nwOrder-Div">
                            <div class="row">
                                <div class="sltSupp nwOrd-Num">
                                    <div class="ord-Box">
                                        <!-- <div class="ordNum">
                                            <h4 class="subTittle1"><span>Order#:</span> <span>332974</span></h4>
                                        </div> -->
                                        <div class="ordDate">
                                            <h4 class="subTittle1">23/05/2022</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="ordInfo newFeatures">
                                    <div class="container">
                                        <div class="mbFeature">
                                            <div class="row gx-3 justify-content-end">
                                                <div class="col-md-7 text-center">
                                                    <div class="row featRow">
                                                        <div class="col-md-3 ordFeature">
                                                            <a href="javascript:void(0)" class="tabFet">
                                                                <span class="autoFill"></span>
                                                                <p class="btn2">Auto Fill</p>
                                                            </a>
                                                        </div>
                                                      
                                                        <div class="col-md-3 ordFeature "><a href="javascript:void(0)"
                                                                class="tabFet">
                                                                <span class="clear"></span>
                                                                <p class="btn2">Clear</p>
                                                        </a></div>

                                                        <div
                                                            class="col-md-3 ordFeature dropdown drpCurr position-relative">
                                                            <a href="javascript:void(0)" class="dropdown-toggle tabFet"
                                                                role="button" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <span class="currency"></span>
                                                                <p class="btn2">Currency <i
                                                                        class="fa-solid fa-angle-down"></i></p>
                                                            </a>

                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0)">Dollar</a></li>
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0)">Euro</a></li>
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0)">Tzs</a></li>
                                                            </ul>

                                                        </div>
                                                        <div class="col-md-3 ordFeature drpFee position-relative">
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
                                    
                                        <a href="javascript:void(0)" class="btn sub-btn"><span
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
                        <form action="" id="frm" name="frm" method="post" autocomplete="off" class="container">
                        <input type="hidden" name="supplierId" id="supplierId" value="">
                        <div class="container topOrder">
                            <div class="row">
                                <div class="sltSupp">
                                    <!-- Select Supplier -->
                                    
                                    <div class="btn-group glb-btn">
                                        <button type="button"
                                            class="btn body3 drp-btn dropdown-toggle dropdown-toggle-split d-flex align-items-center justify-content-between"
                                            data-bs-toggle="dropdown" aria-expanded="false"><span id="supplierText">-<?php echo showOtherLangText('Select'); ?>-</span> <i class="fa-solid fa-angle-down"></i></button>
                                        <ul class="dropdown-menu supplier_dropdown">
                                        <?php
                                        $sqlSet = " SELECT DISTINCT(s.id),s.* 
                                        FROM tbl_suppliers s 
                                        INNER JOIN tbl_designation_sub_section_permission dssp
                                        ON(dssp.type_id=s.id) AND dssp.account_id=s.account_Id
                                        WHERE s.account_id = '".$_SESSION['accountId']."' AND designation_id = '".$_SESSION['designation_id']."' AND designation_section_permission_id = '1' AND type = 'order_supplier' order by name ";
                                        $resultSet =mysqli_query($con, $sqlSet);

                                        while($supRow = mysqli_fetch_array($resultSet))
                                        {
                                        $sel = $_SESSION['supplierId'] == $supRow['id'] ? 'selected = "selected"' : '';

                                        $color = checkSupplierForMinLevelProducts($supRow['id']) > 0 ? 'pink' : 'white';
                                        ?>    
                                        <li data-id=<?php echo $supRow['id'];?> data-value="<?php echo $supRow['name'];?>"><a class="dropdown-item" href="javascript:void(0)"><?php echo  $supRow['name'] ?></a></li>
                                        <?php } ?>
                                        </ul> 
                                        
                                    </div>
                                    <input type="hidden" name="placeOrder"  value="<?php echo showOtherLangText('Submit'); ?>" />
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
                                <div class="ordInfo">
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
                    $x= 0;
                    $totalCustomCharges = 0;
                    if(isset($_SESSION['itemCharges'][1]) && count($_SESSION['itemCharges'][1]) > 0)
                    {
                    $itemIds = implode(',', $_SESSION['itemCharges'][1]);

                    $sqlSet = " SELECT * FROM tbl_custom_items_fee WHERE id IN(".$itemIds.")  AND account_id = '".$_SESSION['accountId']."'  ";
                    $resRows = mysqli_query($con, $sqlSet);
                    }
                    ?>
                    <div class="container nordPrice position-relative">
                        <!-- Item Table Head Start -->
                        <div class="d-flex align-items-center itmTable">
                            <div class="prdtImg tb-head">
                                <p><?php echo showOtherLangText('Image'); ?></p>
                            </div>
                            <div class="prdtCnt-Fst d-flex align-items-center">
                                <div class="Itm-Name tb-head">
                                    <p><?php echo showOtherLangText('Item'); ?></p>
                                </div>
                                <div class="Itm-brCode tb-head">
                                    <p><?php echo showOtherLangText('Bar Code'); ?></p>
                                </div>
                                <div class="prdtCr-Unit d-flex align-items-center">
                                    <div class="crncy-Type d-flex align-items-center">
                                        <div class="dflt-Currency tb-head">
                                            <p><?php echo showOtherLangText('P.Price'); ?>(<?php echo $getDefCurDet['curCode'] ?>)</p>
                                        </div>
                                                                                <?php 
                                        $curAmt = 1;
                                        if( !empty($curDet) )
                                        {
                                        ?>
                                        <div class="othr-Currency tb-head">
                                            <p><?php echo showOtherLangText('P.Price'); ?>(<?php echo $curDet['curCode'] ?>)</p>
                                        </div>
                                        <?php
                                        $curAmt = $curDet['amt'];
                                        }
                                        ?>
                                       </div>
                                    <div class="itm-Unit tb-head">
                                        <p><?php echo showOtherLangText('P.Unit'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="prdtStk-Qty tb-head">
                                <p><?php echo showOtherLangText('S.Qty'); ?></p>
                            </div>
                            <div class="prdtCnt-Scnd d-flex align-items-center">
                                <div class="itm-Quantity tb-head">
                                        <div class="d-flex align-items-center">
                                                <p><?php echo showOtherLangText('Qty'); ?></p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                        </div>
                                </div>
                                <div class="ttlCr-Type d-flex align-items-center">
                                    <div class="ttlDft-Crcy tb-head">
                                        <p><?php echo showOtherLangText('Total'); ?>(<?php echo $getDefCurDet['curCode'] ?>)</p>
                                    </div>
                                    <div class="ttlOtr-Crcy tb-head">
                                        <p><?php echo showOtherLangText('Total'); ?>(<?php echo $curDet['curCode'] ?>)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="prdt-Hide">
                                <div class="prdt-Note tb-bdy">
                                    <div class="mb-brCode"></div>
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
                           if( $_SESSION['supplierId'] != '' )
                           {   
                           
                             $productsConfirmedQtyArr = getConfirmTotalQtyReq($_SESSION['accountId']);
                           
                             $minProductsArr = [];
                             while($row = mysqli_fetch_array($proresultSet))
                             { 
                               $x++;
                           
                               $stockQty = $row['stockQty'];
                               $totalProQty = isset($productsConfirmedQtyArr[$row['id']]) ? $productsConfirmedQtyArr[$row['id']] : 0;
                               $stockQty = $stockQty - $totalProQty;
                           
                               $showQtyMinValue = '';
                               $showQtyMinValueOrg ='';
                               $totalPriceShowTop = 0;
                           
                               if( showTempProQty($proTempRows, $_SESSION['supplierId'], $row['id']) > 0)
                               {    
                                 $showQtyMinValue = showTempProQty($proTempRows, $_SESSION['supplierId'], $row['id']);
                                 $showQtyMinValueOrg = $showQtyMinValue;
                           
                                 //$showQtyMinValue = ceil($showQtyMinValue/$row['factor']);
                           
                                 $totalPriceShowTop += $showQtyMinValue ? ($showQtyMinValue*$row['price']*$row['factor']) : '';
                               }
                               
                               
                               if(isset($_GET['autoFill']) && $_GET['autoFill']==1)
                               {
                           
                                 $showQtyMinValue =  ( round($stockQty, 1) < round($row['minLevel']) )  ? round( ($row['maxLevel']-$stockQty),1) : '';
                                 $showQtyMinValueOrg = $showQtyMinValue;
                                 $showQtyMinValue = ceil($showQtyMinValue/$row['factor']);
                                 $minProductsArr[$row['id']] = $showQtyMinValue;
                           
                                 $totalPriceShowTop = $showQtyMinValue*$row['price']*$row['factor'];
                               }
                           
                               ?>
                             <input type="hidden" name="productIds[]" value="<?php echo $row['id'];?>" />
                                <input type="hidden" name="price[<?php echo $row['id'];?>]" id="<?php echo $x;?>"
                                    value="<?php echo $row['price'];?>" />
                                <input type="hidden" name="factor[<?php echo $row['id'];?>]" id="factor<?php echo $x;?>"
                                    value="<?php echo $row['factor'];?>" />
                            <div class="newOrdTask">
                                <div class="d-flex align-items-center border-bottom itmBody newOrd-CntPrt">
                                    <div class="prdtImg tb-bdy">
                                    <?php 
                                    if( $row['imgName'] != '' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$row['imgName'] ) )
                                    {   
                                    echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$row['imgName'].'" width="60" height="60">';
                                    }
                                    ?>
                                    </div>
                                    <div class="prdtCnt-Fst d-flex align-items-center">
                                        <div class="Itm-Name tb-bdy">
                                            <p><?php echo $row['itemName'];?></p>
                                        </div>
                                        <div class="Itm-brCode tb-bdy">
                                            <p class="ord-brCode"><?php echo $row['barCode'];?></p>
                                        </div>
                                        <div class="prdtCr-Unit d-flex">
                                            <div class="crncy-Type d-flex align-items-center">
                                                <div class="dflt-Currency tb-bdy">
                                                    <p><?php showPrice($row['price']*$row['factor'], $getDefCurDet['curCode']);?></p>
                                                </div>
                                                <div class="othr-Currency tb-bdy">
                                                    <p><?php 
      if( !empty($curDet) )
      {
        $newCurAmt = ($row['price']*$row['factor']*$curDet['amt']);
        $newCurAmt = $newCurAmt > 0 ? showOtherCur($newCurAmt, $curDet['id']) : $newCurAmt;
        echo '<td><strong>'.$newCurAmt.'</strong></td>';
      }
      ?></p>
                                                </div>
                                            </div>
                                            <div class="itm-Unit tb-bdy">
                                                <p><?php echo $row['purchaseUnit'];?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="prdtStk-Qty tb-bdy">
                                        <p class="ord-StockQty"><?php echo round(($stockQty/$row['factor']), 1) ;?> <span class="tabOn-Stk">On stock</span></p>
                                    </div>
                                    <div class="prdtCnt-Scnd d-flex align-items-center">
                                        <div class="itm-Quantity tb-bdy">
                                            <strong><input type="text" class="form-control qty-itm"
                                                name="qty[<?php echo $row['id'];?>]"
                                                onChange="showTotal(this.value, '<?php echo $x;?>', '<?php echo $row['id'];?>')"
                                                value="<?php echo $showQtyMinValue ? $showQtyMinValue: '';?>" size="1"
                                                autocomplete="off"></strong>
                                        </div>
                                        <div class="ttlCr-Type d-flex align-items-center">
                                            <div class="ttlDft-Crcy tb-bdy">
                                                <p><?php $totalPriceVal = isset($_SESSION['productIds'][$row['id']]['qty'])  ? ($_SESSION['productIds'][$row['id']]['qty'] * $_SESSION['productIds'][$row['id']]['price']) : ($showQtyMinValue*$row['price']*$row['factor']); showPrice($totalPriceVal, $getDefCurDet['curCode']);?></p>
                                            </div>
                                            <?php 
        if( !empty($curDet) )
        {

          $newCurAmt = ($totalPriceVal*$curDet['amt']);
          echo '<div class="ttlOtr-Crcy tb-bdy"><p>'.showOtherCur($newCurAmt, $curDet['id'], 1).'</p></div>';
        }

        ?>
        </div>
                                    </div>
                                    <div class="prdt-Hide">
                                        <div class="prdt-Note tb-bdy">
                                            <div class="mb-brCode"></div>
                                            <!-- <input type="text" class="form-control note-itm" placeholder="Note"> -->
                                            <input type="text" class="form-control note-itm" autocomplete="off" id="notes"
                                            
                                            name="notes[<?php echo $row['id'];?>]" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Order">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>
                            <input type="hidden" name="totalPriceShowTop[]" id="totalPriceShowTop<?php echo $x;?>"
                                    value="<?php echo ($totalPriceShowTop);?>" />
                                <?php 
  }} //end while
?>
                                <div class="mbLnk-Order">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>

                            <!-- Item Table Body End -->
                        </div>

                    </div>
                     </form>
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
$('#supplierId').change(function() {
this.form.action = 'addOrder.php';
this.form.submit();
});

$('.sub-btn').click(function(){
$('#frm').submit();
});

$(".supplier_dropdown").on("click", "a", function(e){
        var $this = $(this).parent();
        $("#supplierText").text($this.data("value"));
        $("#supplierId").val($this.data("id"));
        $('#frm').submit();
      });
</script>