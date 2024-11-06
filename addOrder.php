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
  `isTaxFee` = '".$_POST['isTaxFee']."',
  `account_id` = '".$_SESSION['accountId']."' ";

  mysqli_query($con, $sql);

  $itemCharges = mysqli_insert_id($con);

  $_SESSION['itemCharges'][3][$itemCharges] = $itemCharges;

  echo '<script>window.location="addOrder.php?currencyId='.$_POST['currencyPopupForm'].'"</script>';

}

//place order
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
  `supplierId` = '".$_SESSION['supplierId']."',
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
  `notes` = '".showOtherLangText('Added new order')."',
  `action` = '".showOtherLangText('submit')."' ";
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


$supplierId = $_SESSION['supplierId'] > 0 ? $_SESSION['supplierId'] : -1;

//Main query to fetch all the product according to supplier
 $sqlSet = " SELECT 
p.*, 
IF(u.name!='',u.name,p.unitP) AS purchaseUnit, 
s.qty AS stockQty 
FROM tbl_products p

INNER JOIN tbl_productsuppliers ps ON(p.id = ps.productId)  AND ps.supplierId = '".$supplierId."' AND p.account_id = ps.account_id

LEFT JOIN tbl_stocks s
ON(s.pId=p.id) AND p.account_id = s.account_id
LEFT JOIN tbl_units u 
ON(u.id=p.unitP) AND u.account_id = p.account_id

LEFT JOIN tbl_order_items_temp t ON(t.pId = p.id) AND t.account_id = p.account_id AND t.userId='".$_SESSION['id']."' 

WHERE   p.status=1   AND p.account_id = '".$_SESSION['accountId']."' GROUP BY p.id ORDER BY  ".$orderBy;

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
<link rel="stylesheet" href="Assets/css/style1.css">

<style>
    .fa-angle-right, .fa-angles-left {padding: 0; font-size: 1rem; width: 30px;height:30px; }
.d-flex.align-items-center.itmTable > div:first-child, .itmBody.newOrd-CntPrt > div:first-child { width: 4rem; }
.prdtImg { width: 5rem;text-align: center; }
.prdtCnt-Fst { width: calc(85% - 9rem); }
.prdt-Hide { width: 15%; }
/* .prdtCr-Unit { width: 38%; } */
.curRow { font-size: 1.125rem; }
.prcTable { font-size:1rem; }
@media(max-width:991px) { 
/* .Itm-Name, .prdtCr-Unit { width: 50%; } */
.prdt-Hide { width: 100%;margin-top: 10px; }
/* .res__label__item::before {
content: attr(data-text);display: block;font-size: 9px;color: #777;line-height: 1.4;
} */
.mb-brCode .ord-brCode, .mb-brCode .ord-StockQty { width: 50% !important; }
}
@media screen and (min-width: 1600px) {
  .itmBody { padding-top: 12px;padding-bottom: 12px; }
}
@media(max-width:575px) { 
.Itm-Name{ width: 100%; } .prdtCr-Unit { width: 70%; }
.prdtCnt-Scnd { width: 30%; } 
.col-7 .form-control { font-size:12px; }
} 
.Itm-brCode { width: 14%; }
.prdtCr-Unit { width: 30%; }
.Itm-Name { width: 18%; }
.ttlCr-Type { width: 21% !important; }
.prdtStk-Qty { width: 9%; }
.prdtCnt-Scnd { width: 8%; }
.ttlDft-Crcy.tb-bdy { font-weight:600; }
html[dir='rtl'] .ttlDft-Crcy.tb-bdy, html[dir='rtl'] .ttlOtr-Crcy { text-align: right; }
.form-control.qty-itm { font-size:.875rem;font-weight:700; }
.Itm-Name { font-weight:500; }
.tb-head, .prdtImg.tb-head { font-weight: 500; }
.ttlOtr-Crcy.tb-bdy.col { font-weight:600; }
.cntTable { color: #232859;font-weight: 400; }
.filder__btns.col-md-7 { max-width:472px; }
/* .ttlDft-Crcy.tb-head { width: 100%; } */
.nwNxt-Btn .btnBg { max-width:10.875rem; } 
@media screen and (min-width: 1600px) {
  .itmBody { font-size: 1rem;line-height: 1.3; } 
  .tb-head { padding: 8px 8px 10px; } 
}
@media screen and (min-width: 992px) {
    .nwNxt-Btn .btnBg { margin-left: auto; }
    html[dir='rtl'] .nwNxt-Btn .btnBg { margin-left: 0;margin-right: auto; }
}
@media screen and (max-width: 1700px) {
  .itmBody, .itmTable { font-size: 14px; } 
  .fa-angle-right, .fa-angles-left {width: 24px;height:24px; }
  .mbFeature .ordFeature > a { padding: 20px 0; }
  .filder__btns.col-md-7 { max-width: 428px; }
  .mbFeature .ordFeature:before { height: 37px; }
}

@media (max-width:991px) {
    .smBtn .btn { height:40px;padding:0 16px !important;}
    .smBtn .fetBtn img { height:40px; }
    .topOrder { padding: 8px 10px 0 10px !important; }
    .prdtCnt-Fst { width: 76% !important;position: relative; }
    .prdtCnt-Scnd { position: absolute;top: -.5rem;right: 0;width: 35% !important;padding-right: 0;}
    html[dir='rtl'] .prdtCnt-Scnd { right: auto;left:0;}
    .Itm-Name { width: 70% !important; }
    .Itm-Name p { overflow: hidden;text-overflow: ellipsis;-webkit-line-clamp: 1;display: -webkit-box;-webkit-box-orient: vertical; white-space: normal; }
    .prdtCr-Unit { width: 65% !important;margin-top: .5rem;flex-direction: row-reverse;justify-content: flex-end; }
    .prdtCr-Unit > div { width: auto !important; }
    .mb-brCode.d-block { display:flex !important; }
    .itmBody { position: relative; }
    .ttlCr-Type { width: 35% !important; }
    .dflt-Currency, .othr-Currency { width: auto; }
    html[dir=rtl] .ordInfo { padding: 0; }
    .crncy-Type.col.d-flex { flex:none; }
    .curRow { font-size: 1rem; }
    .filder__btns.col-md-7 { margin-top: 1rem; }
}
@media (max-width:767px) {
    .newOrd-CntPrt > div:first-child { position: absolute;top: 0.25rem;left: .2rem;text-align: center;background: rgba(255, 255, 255, 0.8); }
    html[dir="rtl"] .newOrd-CntPrt > div:first-child { right: .2rem;left:auto; }
    html[dir="rtl"] .newOrd-CntPrt > div:nth-child(2) { padding-right: 0;text-align: center; }
    
    .prcTable { margin-top: 8px; }
    .ordFeature { width: 24.33% !important; padding: 0 5px; }
    .mbFeature .ordFeature > a { padding: 13px 0; }
    .mbFeature .ordFeature::before { height: 26px; }
    .prdtImg { width: 4rem;padding-left: 0 !important; }
    .prdtCnt-Fst { width: calc(100% - 4rem) !important;position: relative; } 
    .res__label__item p { font-size: 14px; }
    .compact__tb__bdy .tb-bdy .stkImgcol .imgItm, .compact__tb__bdy .tb-bdy .ordItm-Img { position:relative;top:1rem;height:55px; }
    
    .prdtCr-Unit .col.d-flex { flex-wrap: wrap;}
    .prdtCr-Unit .itm-Uni.tb-bdy.col-3.res__label__item { width: 4rem !important;}
    .dflt-Currency, .othr-Currency { width: 100% !important;flex: 0 0 100%; }
    .prdtCr-Unit .dflt-Currency { padding-left: 0; }
}
.curRow .p-2 { text-align: left; }
html[dir="rtl"] .curRow .p-2 { text-align: right; }
@media(min-width: 1200px) {
    .curRow .p-2 { padding-left: 15% !important; } 
    .txnmRow { width: 35%; }
    .curRow { width: 56%; }
}
@media(min-width:576px){ .container.cntTable, .nordPrice, .topOrder, .container.erdOrder, .recPrice, .nwOrder-Div {padding-left: 1.5rem !important;padding-right: 1.5rem !important;} }
@media(min-width:992px){ .container.cntTable, .nordPrice, .topOrder, .container.erdOrder, .recPrice, .nwOrder-Div {padding-left: 2.5rem !important;padding-right: 2.5rem !important;} }
@media(min-width:1600px){ .container.cntTable, .nordPrice, .topOrder, .container.erdOrder, .recPrice, .nwOrder-Div {padding-left: 3.5rem !important;padding-right: 3.5rem !important;} }
@media screen and (max-width: 992px) {
  .ttlDft-Crcy, .ttlOtr-Crcy { font-weight: 600;flex: 0 0 100%;}
}
@media (min-width: 992px) { 
    html[dir="rtl"] .subTittle1 .ms-lg-3 { margin-right: 1rem !important;margin-left: 0 !important; } 
    .mb-brCode .ord-brCode { display: none;}
}
@media (min-width: 768px) { html[dir="rtl"] .subTittle1 .ps-md-5 { padding-right: 3rem !important;padding-left: 0 !important; } }
@media screen and (max-width: 992px) {
    .ord-Box { width: 100% !important;padding: 14px 1rem !important; }
    html[dir="rtl"] .ord-Box { padding: 4px 1rem !important; }
    html[dir="rtl"] .ord-Box .ps-3 { padding-left: 0 !important; }
    .ord-Box .ordDate { width: 100% !important; }
    .ord-Box .ordDate .subTittle1 { width: 100% !important;display:flex;justify-content:space-between; }
}
@media(max-width:991px) {
   /* .innerDrop .submenu.large { display:none !important;left: -12rem !important;box-shadow: 0 0 4px rgba(0,0,0,0.1);width: 12rem;right:auto; }
    .innerDrop .submenu.large.show { display:block !important; } */
     
    .mb-brCode { justify-content: flex-start;padding-left: 0px; }
}
/* .tabFet.show { background-color: var(--color-primary);color:var(--color-white); } */
</style>
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

  <?php require_once('header.php'); ?>
</div>
</div>
</section>

<section id="landScape">
<div class="container">
<h1 class="h1 text-center">For better experience, Please use portrait view.</h1>
</div>
</section>


<section class="ordDetail pb-5 pb-sm-4 mb-4 mb-sm-0">
<div class="tpBar-grn"></div>
<div class="container nordPrice py-0 mt-0"> 
<?php if(isset($_GET['added']) || isset($_GET['edit']) || isset($_GET['delete']) || isset($_GET['imported']) || isset($_GET['mes']) ) {?>
<div class="alert alert-success alert-dismissible fade show mb-0 mt-3" role="alert">
<p>
<?php  
echo isset($_GET['edit']) ? ' '.showOtherLangText('Item Edited Successfully').' ' : '';
echo isset($_GET['added']) ? ' '.showOtherLangText('Item Added Successfully').' ' : '';
echo isset($_GET['imported']) ? ' '.showOtherLangText('Item imported Successfully').' ' : '';
echo isset($_GET['mes']) ? $_GET['mes'] : '';

if( isset($_GET['delete']) && $_GET['delete'] == 1 )
{
    echo ' '.showOtherLangText('Item Deleted Successfully').' ';
}
elseif( isset($_GET['delete']) && $_GET['delete'] == 2 )
{
    echo ' '.showOtherLangText('This item is in stock or ordered by someone so cannot be deleted').' ';
}

?>
</p>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php } ?>
<?php if(isset($_GET['errorProduct'])) { ?>
<div class="alert alert-danger alert-dismissible fade show lg__left__margin mb-0  mt-3" role="alert">
<p><?php echo showOtherLangText('Select atleast one product to make order successfully') ?></p>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php } ?>
</div>
<div class="stcPart position-relative">
<div class="container  pt-3  cntTable">
<form action="" id="frm" class="frm" name="frm" method="post" autocomplete="off">
<div class="row">
<div class="sltSupp nwOrd-Num">
<div class="ord-Box ms-0 position start-0 ps-4 pl-g" style="top:1rem;">
    <!-- <div class="ordNum">
        <h4 class="subTittle1"><span>Order#:</span> <span>332974</span></h4>
    </div> -->
    <div class="ordDate">
        <h4 class="subTittle1">
            <!-- #:  332974 -->
            <span></span>
            <span class="ps-3 ps-md-5 ms-lg-3"><?php echo date("d-m-Y"); ?></span>
        </h4>
    </div>
</div>
</div>
<div class="ordInfo newFeatures">
<div class="container">
    <div class="mbFeature">
        <div class="row g-0 justify-content-end">
            <div class="col-md-7 text-center filder__btns w-100">
                <div class="row featRow p-0">
                    <div class="col-md-3 ordFeature">
<?php
if (checkSupplierForMinLevelProducts($_SESSION['supplierId']) > 0)
{
?>
        
          <a href="addOrder.php?autoFill=1&supplierIdVal=<?php echo $_SESSION['supplierId'];?>&currencyId=<?php echo $_SESSION['currencyId'] ?>" class="tabFet">
                            <span class="autoFill"></span>
                            <p class="btn2">
                            <?php echo showOtherLangText('Auto Fill'); ?></p>
                        </a>
        <?php
}
else
{
?>

<a style="opacity: 0.5;cursor: default;
pointer-events: none;" href="javascript:void(0)" class="tabFet">
                            <span class="autoFill"></span>
                            <p class="btn2">
                            <?php echo showOtherLangText('Auto Fill'); ?></p>
                        </a>

        <?php
}
?>
                    </div>
                  
                    <div class="col-md-3 ordFeature "><a href="javascript:void(0)"onClick="getClearNumb('<?php echo $_SESSION['supplierId'] ?>')"
                            class="tabFet">
                            <span class="clear"></span>
                            <p class="btn2"><?php echo showOtherLangText('Clear'); ?></p>
                    </a></div>

                    <div
                        class="col-md-3 ordFeature dropdown drpCurr position-relative">
                        <a href="javascript:void(0)" class="dropdown-toggle tabFet"
                            role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <span class="currency"></span>
                            <p class="btn2"><span id="add_currency"><?php echo showOtherLangText('Currency') ?> <i
                                    class="fa-solid fa-angle-down"></i></span></p>
                        </a>

                        <ul class="dropdown-menu currency_dropdown">
                    <li data-id="0" data-value="<?php echo showOtherLangText('Currency') ?>">
                    <a class="dropdown-item" href="javascript:void(0)"><?php echo showOtherLangText('Currency') ?>
                    </a>
                    </li>        
                    <?php
                    while($curRow =mysqli_fetch_array($currResultSet))
                    {

                    $sel = $_SESSION['currencyId'] == $curRow['id'] ? 'selected' : '';

                    echo '<li data-id="'.$curRow['id'].'" data-value="'.$curRow['currency'].'"><a class="dropdown-item '.$sel.'"
                                    href="javascript:void(0)">'.$curRow['currency'].'</a></li>';

                    } ?>
                        <!-- <input type="hidden" id="currencyId" name="currencyId" value=""> -->
                    </ul>

                    </div>
                    <div class="col-md-3 ordFeature drpFee position-relative">
                        <a href="javascript:void(0)" class="dropdown-toggle tabFet" id="dropBtn"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="fee"></span>
                            <p class="btn2"><?php echo showOtherLangText('Fee'); ?> <i
                                    class="fa-solid fa-angle-down"></i>
                            </p>
                        </a>

                        <ul class="item dropdown-menu dropdown__menu" id="dropdownMenu">
                            <li class="dropdown innerDrop">
                                <a class="item dropdown-item d-flex justify-content-between align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo showOtherLangText('Service Item'); ?><i
                                class="fa-solid fa-angle-down"></i></a>
                                <ul class="subitem submenu large list-unstyled dropdown-menu dropdown__menu">
                                    <?php
                //add item fee & custom fee modal box 
                $sql = " SELECT * 
                FROM tbl_custom_items_fee 
                WHERE visibility='1' AND account_id='".$_SESSION['accountId']."' ";
                $customItemsResult = mysqli_query($con, $sql);

                //$liCount = 0;
                while ($resultRow = mysqli_fetch_array($customItemsResult)) 
                {
                    //$liCount++;
                    echo "<li class='innerLi'><a class='dropdown-item' tabindex='-1' href='addOrder.php?feeType=1&itemCharges=".$resultRow['id']."&currencyId=".$_SESSION['currencyId']." ' >".$resultRow['itemName']."</a></li>";
                } 
                ?>
                                </ul>
                            </li>
                            <li><a class="dropdown-item" class="sub-btn std-btn mb-usrBkbtn" data-bs-toggle="modal" data-bs-target="#new-service-item" href="javascript:void(0)"><?php echo showOtherLangText('New Service Item'); ?></a></li>
                            <li class="dropdown innerDrop">
                                <a class="item dropdown-item d-flex justify-content-between align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo showOtherLangText('Fee'); ?><i
                                class="fa-solid fa-angle-down"></i></a>
                                <ul class="subitem submenu large list-unstyled dropdown-menu dropdown__menu">
                                    <?php
                                    //add item fee & custom fee modal box 
                                    $sqlQry = " SELECT * FROM tbl_order_fee WHERE visibility='1' AND account_id='".$_SESSION['accountId']."' ";
                                    $ordFeeFetch = mysqli_query($con, $sqlQry);
                                    //$innerLiCount = 0;
                                    while ($resultRow = mysqli_fetch_array($ordFeeFetch))
                                    {
                                        // $innerLiCount++;
                                        echo "<li class='innerLi'><a class='dropdown-item' tabindex='-1' href='addOrder.php?feeType=3&itemCharges=".$resultRow['id']."&currencyId=".$_SESSION['currencyId']." '>".$resultRow['feeName']."</a> ";
                                    } 
                                    ?>
                                </ul>
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#new-fees-item"><?php echo showOtherLangText('New Fee') ?></a></li> 
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

    <a href="javascript:void(0)" class="btn btn-primary submit_new_order d-inline-flex px-lg-2 px-xxl-3 w-100 align-items-center justify-content-center justify-content-xl-end gap-2"><span
            class="align-middle"><?php echo showOtherLangText('Submit'); ?></span> <i
            class="fa-solid fa-angle-right d-none d-xl-inline-flex justify-content-center align-items-center m-0"></i></a>
</div>
<div class="fetBtn">
    <a href="javascript:void(0)">
        <img src="Assets/icons/dashboard.svg" alt="dashboard">
    </a>
</div>
</div>
</div>
</div>


<div class="container topOrder mt-1">
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
    $sel = $_SESSION['supplierId'] == $supRow['id'] ? 'selected' : '';

    $color = checkSupplierForMinLevelProducts($supRow['id']) > 0 ? 'pink' : 'white';
    ?>    
    <li style="background-color:<?php echo $color;?>" data-id=<?php echo $supRow['id'];?> data-value="<?php echo $supRow['name'];?>"><a class="dropdown-item <?php echo $sel; ?>" href="javascript:void(0)"><?php echo  $supRow['name'] ?></a></li>
    <?php } ?>
    </ul> 
    
</div>

<input type="hidden" name="placeOrder"  value="<?php echo showOtherLangText('Submit'); ?>" />
<div class="input-group srchBx">
    <input type="search" class="form-control" onKeyUp="myFunction()" placeholder="<?php echo showOtherLangText('Search'); ?>" name="search" id="search"
        aria-label="Search">
    <div class="input-group-append">
        <button class="btn" type="button">
            <i class="fa fa-search"></i>
        </button>
    </div>
</div>

</div>

<div id="totalOrdArea" class="ordInfo">
<?php

$sqlQry=" SELECT * FROM  tbl_order_items_temp WHERE supplierId = '".$_SESSION['supplierId']."' AND account_id = '".$_SESSION['accountId']."'  AND `userId` = '".$_SESSION['id']."'  ";
$ordTempResult=mysqli_query($con, $sqlQry);


while($ordTempRow=mysqli_fetch_array($ordTempResult)) 
{
$prodQry=" SELECT * FROM tbl_products WHERE id= '".$ordTempRow['pId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
$prodResult= mysqli_query($con, $prodQry);
$prodRow= mysqli_fetch_array($prodResult);

$productPrice += $prodRow['price']*$prodRow['factor']*$ordTempRow['qty'];
}

//for custom item fee charges
$customCharge=0;
if( isset($_SESSION['itemCharges'][1]) && count($_SESSION['itemCharges'][1]) > 0  )
{
$itemIds = implode(',', $_SESSION['itemCharges'][1]);

$sqlSet = " SELECT * FROM tbl_custom_items_fee WHERE id IN(".$itemIds.") AND account_id = '".$_SESSION['accountId']."'   ";
$customFeeResult = mysqli_query($con, $sqlSet);

while( $customFeeRow = mysqli_fetch_array($customFeeResult))
{
$customCharge += $customFeeRow['amt'];
}

}//end of custom item fee charges

//total sum value of custom charge and product charge in both currency
$totalChargePrice= ($productPrice+ $customCharge);
$totalChargePriceOther = ($totalChargePrice*$curDet['amt']);
?>
<div class="container">
    <div class="prcTable">
        <?php
        if(isset($_SESSION['itemCharges'][3]) && count($_SESSION['itemCharges'][3]) > 0)
        { ?>
        <div class="price justify-content-between">
            <div class="p-2 delIcn text-center"></div>
            <div class="p-2 txnmRow">
                <p><?php echo showOtherLangText('Sub Total'); ?></p>
            </div>
            <div class="d-flex align-items-center justify-content-end curRow">
                <div class="p-2">
                    <p><?php showPrice($totalChargePrice, $getDefCurDet['curCode']) ?></p>
                </div>
                <?php
                if ( isset($_SESSION['currencyId']) && $_SESSION['currencyId'] == $curDet['id'] ) 
                { 
                ?>
                <div class="p-2 otherCurr">
                    <p><?php echo showOtherCur($totalChargePriceOther, $_SESSION['currencyId']);?></p>
                </div>
                  <?php 
                 } ?>
            </div>
        </div>
          <?php 
} ?>
<?php 
$taxCharges=0;
$fixedCharges=0;
$perCharges=0;

if(isset($_SESSION['itemCharges'][3]) && count($_SESSION['itemCharges'][3]) > 0)
{
$itemIds = implode(',', $_SESSION['itemCharges'][3]);

//start order level fixed discount charges
$sqlSet = " SELECT * FROM tbl_order_fee WHERE id IN(".$itemIds.") AND account_id = '".$_SESSION['accountId']."'  AND feeType =2 ";

$ordFeeResult = mysqli_query($con, $sqlSet);

while($fixedDiscountRow = mysqli_fetch_array($ordFeeResult)) 
{
$fixedDiscFeeName = $fixedDiscountRow['feeName'];
$fixedCharges += $fixedDiscountRow['amt'];
$fixedChargesOther += ($fixedDiscountRow['amt']*$curDet['amt']);
?>
        <div class="price justify-content-between taxRow">
            <div class="p-2 delIcn text-center">
                <a href="javascript:void(0)" onClick="getDelNumb('<?php echo $fixedDiscountRow['id'] ?>', '3', '<?php echo $_SESSION['currencyId'] ?>');">
                    <i class="fa-solid fa-trash-can"></i>
                </a>
            </div>
            <div class="p-2 txnmRow">
                <p><?php echo $fixedDiscFeeName ?></p>
            </div>
            <div class="d-flex align-items-center justify-content-end curRow">
                <div class="p-2">
                    <p><?php showPrice($fixedDiscountRow['amt'], $getDefCurDet['curCode']); ?></p>
                </div>
                <?php
if ( !empty($_SESSION['currencyId']) && $_SESSION['currencyId'] == $curDet['id'] )
{ 
?>                                                  <div class="p-2 otherCurr">
                    <p><?php echo showOtherCur(($fixedDiscountRow['amt']*$curDet['amt']), $_SESSION['currencyId']); ?></p>
                </div>
                <?php 
} ?>
                
            </div>
        </div>
   
<?php 
} 
//while loop close 

$sqlSet = " SELECT * FROM tbl_order_fee WHERE id IN(".$itemIds.")  AND account_id = '".$_SESSION['accountId']."' AND feeType = 3  ";
$resRows = mysqli_query($con, $sqlSet);
while($perRow = mysqli_fetch_array($resRows))
{

$feeName = $perRow['feeName'];
$perCharges += $perRow['amt'];
$perChargesOther += $perRow['amt'];
$perChargeTotal = ($totalChargePrice*$perRow['amt']/100);
$perChargeTotalOther = ($totalChargePriceOther*$perRow['amt']/100);
?>
            <div class="price justify-content-between taxRow">
            <div class="p-2 delIcn text-center">
                <a onClick="getDelNumb('<?php echo $perRow['id'] ?>', '3', '<?php echo $_SESSION['currencyId'] ?>');" href="javascript:void(0)">
                    <i class="fa-solid fa-trash-can"></i>
                </a>
            </div>
            <div class="p-2 txnmRow">
                <p><?php echo $feeName;?> <?php echo $perRow['amt']; ?> %</p>
            </div>
            <div class="d-flex align-items-center justify-content-end curRow">
                <div class="p-2">
                    <p><?php showPrice($perChargeTotal, $getDefCurDet['curCode']) ?></p>
                </div>
               
                <?php
if ( !empty($_SESSION['currencyId']) && $_SESSION['currencyId'] == $curDet['id'] ) 
{ 
?>                                                   <div class="p-2 otherCurr">
                    <p><?php echo showOtherCur($perChargeTotalOther, $_SESSION['currencyId']); ?></p>
                </div>
                <?php 
} ?>
            </div>
        </div>
            <?php 
}//end of while loop
$sqlSetQry = " SELECT * FROM tbl_order_fee WHERE id IN(".$itemIds.")  AND account_id = '".$_SESSION['accountId']."' AND feeType =1 ";
$resultRows = mysqli_query($con, $sqlSetQry);

$totalFixedCharges= $fixedCharges;
$totalPerCharges= ($totalChargePrice*$perCharges/100);
$totalFixedChargesOther= $fixedChargesOther;
$totalPerChargesOther= ($totalChargePriceOther*$perCharges/100);

while( $taxRow = mysqli_fetch_array($resultRows) ) 
{ 
$feeName = $taxRow['feeName'];
$taxCharges += $taxRow['amt'];
$taxChargesOther += $taxRow['amt'];
$taxPerChargesTotal = ( ($totalChargePrice+$totalFixedCharges+$totalPerCharges )*$taxRow['amt']/100 );
$taxPerChargesTotalOther = ( ($totalChargePriceOther+$totalFixedChargesOther+$totalPerChargesOther )*$taxRow['amt']/100 ); ?>
            <div class="price justify-content-between taxRow">
            <div class="p-2 delIcn text-center">
                <a onClick="getDelNumb('<?php echo $taxRow['id'] ?>', '3', '<?php echo $_SESSION['currencyId'] ?>');" href="javascript:void(0)">
                    <i class="fa-solid fa-trash-can"></i>
                </a>
            </div>
            <div class="p-2 txnmRow">
                <p><?php echo $feeName ?> <?php echo $taxRow['amt'] ?> %</p>
            </div>
            <div class="d-flex align-items-center justify-content-end curRow">
                <div class="p-2">
                    <p><?php showPrice($taxPerChargesTotal, $getDefCurDet['curCode']) ?></p>
                </div>
               
                <?php
if ( !empty($_SESSION['currencyId']) && $_SESSION['currencyId'] == $curDet['id'] ) 
{ 
?>                                                   <div class="p-2 otherCurr">
                    <p><?php echo showOtherCur($taxPerChargesTotalOther, $_SESSION['currencyId']); ?></p>
                </div>
                <?php 
} ?>
            </div>
        </div>
            <?php 
}}//end of while loop
$totalTaxCharges= ( ($totalChargePrice+$totalFixedCharges+$totalPerCharges)*$taxCharges/100);//calculating total tax value 
$totalTaxChargesOther= ( ($totalChargePriceOther+$totalFixedChargesOther+$totalPerChargesOther)*$taxChargesOther/100);//calculating total tax value other 
$netTotalValue= ($totalChargePrice+$totalFixedCharges+$totalPerCharges+$totalTaxCharges);
$netTotalValueOther= ($totalChargePriceOther+$totalFixedChargesOther+$totalPerChargesOther+$totalTaxChargesOther);   
?>
        <div <?php
        if(!isset($_SESSION['itemCharges'][3]) || count($_SESSION['itemCharges'][3]) == 0)
        { 
echo 'style="border-top: 0px;"';  
} ?> class="price justify-content-between grdTtl-Row">
            <div class="p-2 delIcn text-center"></div>
            <div class="p-2 txnmRow">
                <p><?php echo showOtherLangText('Grand Total'); ?></p>
            </div>
            <div class="d-flex align-items-center justify-content-end curRow">
                <div class="p-2">
                    <p><?php showPrice($netTotalValue, $getDefCurDet['curCode']) ?></p>
                </div>
                <?php
if ( !empty($_SESSION['currencyId']) && $_SESSION['currencyId'] == $curDet['id'] ) 
{  ?>
                <div class="p-2 otherCurr">
                    <p><?php echo showOtherCur($netTotalValueOther, $_SESSION['currencyId']); ?></p>
                </div>
<?php 

} ?>
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
<p><?php echo mysqli_num_rows($proresultSet) > 0 ? mysqli_num_rows($proresultSet) : ''; ?></p>
</div>
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

    <div class="dflt-Currency tb-head col d-flex">
        <p class="col"><?php echo showOtherLangText('P.Price'); ?>(<?php echo $getDefCurDet['curCode'] ?>)</p>
        <?php 
        $curAmt = 1;
        if( !empty($curDet) )
        {
        ?>
        <!-- <div class="othr-Currency tb-head w-100"> -->
            <p class="col"><?php echo showOtherLangText('P.Price'); ?>(<?php echo $curDet['curCode'] ?>)</p>
        <!-- </div> -->
        <?php
        $curAmt = $curDet['amt'];
        }
        ?>
    </div>
  
    <div class="itm-Uni tb-head col-3">
        <p><?php echo showOtherLangText('P.Unit'); ?></p>
    </div>
</div>
<div class="prdtStk-Qty tb-head">
<p><?php echo showOtherLangText('S.Qty'); ?></p>
</div>
<div class="prdtCnt-Scnd d-flex align-items-center">
<div class="itm-Quantity tb-head w-100">
        <div class="d-flex align-items-center">
                <p><?php echo showOtherLangText('Qty'); ?></p>
                <span class="dblArrow">
                    <a href="addOrder.php?sort=qty" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                    <a href="addOrder.php?sort=qty" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                </span>
        </div>
</div> 
</div>
<div class="ttlCr-Type d-flex align-items-center">
            <div class="ttlDft-Crcy tb-head col w-auto">
                <p><?php echo showOtherLangText('Total'); ?>(<?php echo $getDefCurDet['curCode'] ?>)</p>
            </div>
            <?php 
            $curAmt = 1;
            if( !empty($curDet) )
            {
            ?>
            <div class="ttlOtr-Crcy tb-head col w-auto">
            <p><?php echo showOtherLangText('Total'); ?>(<?php echo $curDet['curCode'] ?>)</p>
            </div>
            <?php
            $curAmt = $curDet['amt'];
            }
            ?>
        </div>
</div>
<div class="prdt-Hide">
<div class="prdt-Note tb-bdy">
<div class="row g-0 align-items-baseline"> 
    <div class="col-12"> 
        <div class="mb-brCode"></div>
        <p><?php echo showOtherLangText('Note'); ?></p>
    </div>
</div>
</div>
</div>
</div>
<!-- Item Table Head End -->
</div>

<div id="boxscroll" class="compact__tb__bdy">
<div class="container cntTable">
<?php 
if(isset($_SESSION['itemCharges'][1]) && count($_SESSION['itemCharges'][1]) > 0)
{
while( $row = mysqli_fetch_array($resRows) ) //start custom item charge loop
{
$x++;
$totalCustomCharges += $row['amt'];
?>                          
<div class="newOrdTask">
<div class="d-flex align-items-center border-bottom itmBody newOrd-CntPrt">
  <div class="prdtImg tb-bdy">
        <p><?php echo $x; ?></p>
    </div>
    <div class="prdtImg tb-bdy">
    <a title="<?php echo showOtherLangText('Delete') ?>" href="javascript:void(0)"
        onClick="getDelNumb('<?php echo $row['id'] ?>', '1', '<?php echo $_SESSION['currencyId'] ?>');"
        style="color:#808080"><span class="dlTe"></span></a>
</div>
<div class="prdtCnt-Fst d-flex align-items-center">
    
    <div class="Itm-Name tb-bdy">
        <p><?php echo $row['itemName'];?></p>
    </div>
    <div class="Itm-brCode tb-bdy">
        <p class="ord-brCode"><?php echo $row['barCode'];?></p>
    </div>
    <div class="prdtCr-Unit d-flex">
        <!-- <div class=" crncy-Type col-7"> -->
        <div class="crncy-Type col d-flex">
            <div class="dflt-Currency tb-bdy w-auto col">
                <p><?php  showPrice($row['amt'], $getDefCurDet['curCode']);?></p>
            </div>
             <?php 
            if( !empty($curDet) )
            {
            $newCurAmt = ($row['amt']*$curDet['amt']);
            $newCurAmt = $newCurAmt > 0 ? showOtherCur($newCurAmt, $curDet['id'], 1) : $newCurAmt;
            echo '<div class="othr-Currency tb-bdy w-auto col ps-0"><p>'.$newCurAmt.'</p></div>';
            } ?>
        </div>
        <div class="itm-Uni tb-bdy col-3">
            <p><?php echo $row['unit'];?></p>
        </div>
    </div>
    <div class="prdtStk-Qty tb-bdy">
        <div class="itm-Uni tb-bdy">
            <p>&nbsp;</p>
        </div>
    </div>
    <div  class="prdtCnt-Scnd d-flex align-items-center">
        <div class="itm-Quantity tb-bdy w-100">
            1
        </div> 
    </div>
    <div class="ttlCr-Type d-flex align-items-center">
        <div class="ttlDft-Crcy tb-bdy col">
            <p><?php  showPrice($row['amt'], $getDefCurDet['curCode']);?></p>
        </div>
        <?php 

        if( !empty($curDet) )
        { 
        $newCurAmt = ($row['amt']*$curDet['amt']);
        $newCurAmt = $newCurAmt > 0 ? showOtherCur($newCurAmt, $curDet['id'], 1) : $newCurAmt;
        echo '<div class="ttlDft-Crcy tb-bdy col">
                    <p>'.$newCurAmt.'</p>
                </div>';
        }

        ?>
    </div>
</div>
<div class="prdt-Hide">
    <div class="prdt-Note tb-bdy">
        <div class="row g-0 align-items-center"> 
            <div class="col-12"> 
                <div class="mb-brCode"></div>
                <!-- <input type="text" class="form-control note-itm" placeholder="Note"> -->
                <input type="text" class="form-control note-itm" autocomplete="off" id="notes" 
                placeholder="<?php echo showOtherLangText('Note'); ?>"
                name="itemNotes[<?php echo $row['id'];?>]" value="">
            </div>
        </div>
    </div>
</div>
</div>
<div class="mbLnk-Order">
<a href="javascript:void(0)" class="orderLink">
    <i class="fa-solid fa-angle-down"></i>
</a>
</div>

</div>
<?php 

}//end custom item charge loop

}
?>  <?php 
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
        <p><?php echo $x; ?></p>
</div>
<div class="prdtImg tb-bdy">
<?php 
if( $row['imgName'] != '' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$row['imgName'] ) )
{   
echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$row['imgName'].'" class="ordItm-Img">';
}
?>
</div>
<div class="prdtCnt-Fst d-flex align-items-center">
    <div class="Itm-Name tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Item'); ?>">
        <p><?php echo $row['itemName'];?></p>
    </div>
    <div class="Itm-brCode tb-bdy" id="barCode<?php echo $row['id'];?>">
        <p class="ord-brCode"><?php echo $row['barCode'];?></p>
    </div>
    <div class="prdtCr-Unit d-flex">
        <div class="col d-flex"> 
            <div class="dflt-Currency tb-bdy w-auto col res__label__item" data-text="<?php echo showOtherLangText('P.Price'); ?>">
                <p><?php showPrice($row['price']*$row['factor'], $getDefCurDet['curCode']);?></p>
            </div>
            <?php 
                if( !empty($curDet) )
                {
                    $newCurAmt = ($row['price']*$row['factor']*$curDet['amt']);
                    $newCurAmt = $newCurAmt > 0 ? showOtherCur($newCurAmt, $curDet['id']) : $newCurAmt;
                    echo '<div class="othr-Currency tb-bdy col ps-0"><p>'.$newCurAmt.'</p></div>';
                }
            ?> 
        </div>
        
        <div class="itm-Uni tb-bdy col-3 res__label__item" data-text="<?php echo showOtherLangText('P.Unit'); ?>">
            <p><?php echo $row['purchaseUnit'];?></p>
        </div>
    </div>
    <div class="prdtStk-Qty tb-bdy" id="stockQty<?php echo $row['id'];?>">
        <p class="ord-StockQty" <?php echo ( ($row['minLevel'] == 0 && $stockQty < $row['minLevel']) || (round($stockQty/$row['factor']) < round($row['minLevel']/$row['factor']))  ) ? 'style="display: flex;flex-direction: column;justify-content: center;align-items: center;background-color: pink;width: 43px;text-align: center;height: 30px;"' : 'style="width:43px;"';?>><?php echo round(($stockQty/$row['factor']), 1) ;?> <span class="tabOn-Stk">On stock</span></p>
    </div>
    <div  class="prdtCnt-Scnd d-flex align-items-center">
        <div class="itm-Quantity tb-bdy w-100 res__label__item" data-text="<?php echo showOtherLangText('Qty'); ?>">
            <strong><input type="text" class="form-control qty-itm"
                name="qty[<?php echo $row['id'];?>]"
                onChange="showTotal(this.value, '<?php echo $x;?>', '<?php echo $row['id'];?>')"
                value="<?php echo $showQtyMinValue ? $showQtyMinValue: '';?>" size="1"
                autocomplete="off"></strong>
        </div> 
    </div>
    <div class="ttlCr-Type d-flex align-items-center text-start res__label__item" data-text="<?php echo showOtherLangText('Total'); ?>">
        <div id="totalPrice<?php echo $x;?>" class="ttlDft-Crcy tb-bdy col">
            <p><?php $totalPriceVal = isset($_SESSION['productIds'][$row['id']]['qty'])  ? ($_SESSION['productIds'][$row['id']]['qty'] * $_SESSION['productIds'][$row['id']]['price']) : ($showQtyMinValue*$row['price']*$row['factor']); showPrice($totalPriceVal, $getDefCurDet['curCode']);?></p>
        </div>
        <?php 
            if( !empty($curDet) )
            { 
            $newCurAmt = ($totalPriceVal*$curDet['amt']);
            echo '<div id="totalPriceOther'.$x.'" class="ttlOtr-Crcy tb-bdy col"><p>'.showOtherCur($newCurAmt, $curDet['id'], 1).'</p></div>';
            } 
        ?>
    </div>
</div>
<div class="prdt-Hide">
    <div class="prdt-Note tb-bdy">
        <div class="row g-0 align-items-center"> 
            <div class="col-12">  
                <div class="mb-brCode d-bloc test">
                    <p class="ord-brCode"><?php echo $row['barCode'];?></p>
                    <p class="ord-StockQty" <?php echo ( ($row['minLevel'] == 0 && $stockQty < $row['minLevel']) || (round($stockQty/$row['factor']) < round($row['minLevel']/$row['factor']))  ) ? 'style="display: flex;flex-direction: column;justify-content: center;align-items: center;background-color: pink;width: 43px;text-align: center;height: 30px;"' : 'style="width:43px;"';?>><?php echo round(($stockQty/$row['factor']), 1) ;?> <span class="tabOn-Stk">On stock</span></p>
                      
                </div>
                <!-- <input type="text" class="form-control note-itm" placeholder="Note"> -->
                <input type="text" class="form-control note-itm" autocomplete="off" id="notes"
                placeholder="<?php echo showOtherLangText('Note'); ?>"
                name="notes[<?php echo $row['id'];?>]" value="">
            </div>
        </div>
    </div>
</div>
</div>
<div class="mbLnk-Order">
<a href="javascript:void(0)" class="orderLink" onclick="showHideExtraData('<?php echo $row['id'];?>');">
    <i class="fa-solid fa-angle-down"></i>
</a>
</div>

</div>
<input type="hidden" name="totalPriceShowTop[]" id="totalPriceShowTop<?php echo $x;?>"
value="<?php echo ($totalPriceShowTop);?>" />
<?php 
}} //end while
//do this for auto fill
///print_r($minProductsArr);
if( !empty($minProductsArr) )
{ 
foreach($minProductsArr as $pId=>$qty)
{

if ($qty > 0) 
{

$sql = " SELECT * FROM  tbl_order_items_temp WHERE supplierId = '".$_SESSION['supplierId']."' AND pId='".$pId."' AND account_id='".$_SESSION['accountId']."' LIMIT 1 ";
$result = mysqli_query($con, $sql);
$resultSet = mysqli_fetch_array($result);


/*if($pId == 243)
{
print_r($resultSet);die;
}*/


if ($resultSet) 
{
$upqry = " UPDATE tbl_order_items_temp SET qty='".$qty."' WHERE supplierId = '".$_SESSION['supplierId']."' AND pId='".$pId."' AND account_id='".$_SESSION['accountId']."' ";
$result = mysqli_query($con, $upqry);
}
else
{
$sql = "INSERT INTO `tbl_order_items_temp` SET
supplierId = '".$_SESSION['supplierId']."'
,`userId` = '".$_SESSION['id']."'
, `pId` = '".$pId."' 
, `qty` = '".$qty."'
, `account_id` = '".$_SESSION['accountId']."'  ";
$result = mysqli_query($con, $sql);
}

}

}
}//end autofill code
if (isset($_GET['supplierIdVal']) && $_GET['supplierIdVal'] > 0)
{
if( isset($_GET['autoFill']) && $_GET['autoFill'] == 1)
{
echo '<script>window.location="addOrder.php?autoFill=1"</script>';
}
else
{
echo '<script>window.location="addOrder.php"</script>';
}
}
?>
<!-- <div class="mbLnk-Order">
<a href="javascript:void(0)" class="orderLink">
    <i class="fa-solid fa-angle-down"></i>
</a>
</div> -->

</div>

<!-- Item Table Body End -->
</div>

</div>

</section>
</form>

</div>
</div>
</div>
<div id="dialog" style="display: none;">
<?php echo showOtherLangText('Are you sure to clear data') ?>
</div>
<div id="dialog1" style="display: none;">
<?php echo showOtherLangText('Are you sure to delete this charges?') ?>
</div>
<!-- Add Service Item Popup Start -->

<form action="" name="addNewFee" class="addUser-Form row container glbFrm-Cont" id="addNewFee" method="post" autocomplete="off">
<div class="modal" tabindex="-1" id="new-fees-item" aria-labelledby="add-CategoryLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
<h1 class="modal-title h1"><?php echo showOtherLangText('Add Fee'); ?></h1>
</div>
<div class="modal-body">
<input type="hidden" name="currencyPopupForm" value="<?php echo $_SESSION['currencyId'] ?>">
<input type="text" class="form-control" name="feeName" id="feeName" value=""
         autocomplete="off"
        placeholder="<?php echo showOtherLangText('Fee Name'); ?>"
        oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
        onChange="this.setCustomValidity('')" required />
<select class="form-control" name="feeType" id="typeOfFee"
        oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')"
        onChange="this.setCustomValidity('')" required>
        <option value="2"><?php echo showOtherLangText('Fixed Fee'); ?></option>
        <option value="3"><?php echo showOtherLangText('Percentage Fee'); ?>
        </option>
    </select>
<input type="text" class="form-control" id="amt" name="amt" value=""
         autocomplete="off"
        placeholder="<?php echo showOtherLangText('Fee Amount').' '.$getDefCurDet['curCode']; ?>"
        oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
        onChange="this.setCustomValidity('')" required />
        
</div>
<div>
<input type="checkbox" name="feeType" id="feeType" class="optionCheck" value="1">
<span class="subTittle1" style="vertical-align:text-top;"><?php echo showOtherLangText('Tax fee'); ?></span>
<div class="feeSave">
<input type="checkbox" class="optionCheck" id="visibility" name="visibility" value="1">
<span class="subTittle1" style="vertical-align:text-top;"><?php echo showOtherLangText('save to fixed service item list'); ?></span><br>
</div>
</div>
<div class="modal-footer">
<div class="btnBg">
<button type="submit" id="feesave_add" name="feesave_add" class="sub-btn btn-primary btn std-btn"><?php echo showOtherLangText('Add'); ?></button>
</div>
</div>
</div>
</div>
</div>
</form>
<form action="" name="addServiceFeeFrm" class="addUser-Form row container glbFrm-Cont" id="addServiceFeeFrm" method="post" autocomplete="off">
<div class="modal" tabindex="-1" id="new-service-item" aria-labelledby="add-CategoryLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
<h1 class="modal-title h1"><?php echo showOtherLangText('Service Name'); ?></h1>
</div>
<div class="modal-body">
<input type="text" required class="form-control" id="itemName" name="itemName" placeholder="<?php echo showOtherLangText('Service Name');?> *" autocomplete="off"
        oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
        onChange="this.setCustomValidity('')" required>
<input type="number" required class="form-control" id="feeAmt" name="itemFeeAmt" placeholder="<?php echo showOtherLangText('Amount').' '.$getDefCurDet['curCode']; ?> *" autocomplete="off"
        oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
        onChange="this.setCustomValidity('')" required>
<input type="text" required class="form-control" id="unit" name="unit" placeholder="<?php echo showOtherLangText('Unit'); ?> *" autocomplete="off"
        oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
        onChange="this.setCustomValidity('')" required>
</div>
<div>
<div class="feeSave">
<input type="checkbox" class="optionCheck" id="visibility" name="visibility" value="1">
<span class="subTittle1" style="vertical-align:text-top;"><?php echo showOtherLangText('save to fixed service item list'); ?></span><br>
</div>
</div>
<div class="modal-footer">
<div class="btnBg">
<button type="submit" id="addFee" name="addFee" class="btn btn-primary std-btn"><?php echo showOtherLangText('Add'); ?></button>
</div>
</div>
</div>
</div>
</div>
</form>
<form id="supplier_frm_id" name="supplier_frm_id" method="post" action="addOrder.php">
<input type="hidden" name="supplierId" id="supplierId" value="">
</form>
<form id="currency_frm_id" name="currency_frm_id" method="post" action="">
<input type="hidden" id="currencyId" name="currencyId" value="">
<input type="hidden" id="donotsubmit" name="donotsubmit" value="true">
</form>

<?php require_once('footer.php');?>
<link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<div class="modal" tabindex="-1" id="delete-popup" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
<h1 class="modal-title h1"><?php echo showOtherLangText('Are you sure to delete this record?') ?> </h1>
</div>

<div class="modal-footer">
<div class="d-flex gap-3">
<button type="button" data-bs-dismiss="modal" class="btn btn-primary std-btn"><?php echo showOtherLangText('No'); ?></button>
<button type="button" onclick="" class="deletelink btn btn-primary std-btn"><?php echo showOtherLangText('Yes'); ?></button>
</div>
 
</div>
</div>
</div>
</div>

<div class="modal" tabindex="-1" id="clear-popup" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
<h1 class="modal-title h1"><?php echo showOtherLangText('Are you sure to clear data') ?> </h1>
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

</body>

</html>
<script>
// $('#supplierId').change(function() {
// this.form.action = 'addOrder.php';
// this.form.submit();
// });

$('.submit_new_order').click(function(){
$('#frm').submit();
});

$(".supplier_dropdown").on("click", "a", function(e){
var $this = $(this).parent();
$("#supplierText").text($this.data("value"));
$("#supplier_frm_id #supplierId").val($this.data("id"));
$('#supplier_frm_id').submit();
});

if($(".supplier_dropdown .selected").length>0)
{
$("#supplierText").text($(".supplier_dropdown .selected").text());
}
if($(".currency_dropdown .selected").length>0)
{
$("#add_currency").text($(".currency_dropdown .selected").text());
}
function myFunction() {
// Declare variables
var input = document.getElementById("search");
var filter = input.value.toLowerCase();
var nodes = document.querySelectorAll('.newOrdTask');

for (i = 0; i < nodes.length; i++) {
if (nodes[i].innerText.toLowerCase().includes(filter)) {
nodes[i].style.setProperty("display", "flex", "important");
} else {
nodes[i].style.setProperty("display", "none", "important");
}
}


}

$("#search").on("search", function(evt){
if($(this).val().length == 0){
resetData();
}
});

function resetData() {

$('#search').val('');
myFunction();
}

$(".currency_dropdown").on("click", "a", function(e){
var $this = $(this).parent();
$("#add_currency").text($this.data("value"));
$("#currencyId").val($this.data("id"));
$('#currency_frm_id').submit();
});

</script>
<script>
function getDelNumb(delId, feeType,currencyId){
var newOnClick = "window.location.href='addOrder.php?delId=" + delId + "&feeType=" + feeType + "&currencyId=" + currencyId + "'";

//console.log('click',newOnClick);
//return false;

$('.deletelink').attr('onclick', newOnClick);
$('#delete-popup').modal('show');

}
// function getDelNumb(delId, feeType, currencyId) {

//     $("#dialog1").dialog({
//         autoOpen: false,
//         modal: true,
//         //title     : "Title",
//         buttons: {
//             '<?php echo showOtherLangText('Yes') ?>': function() {
//                 //Do whatever you want to do when Yes clicked
//                 $(this).dialog('close');
//                 window.location.href = 'addOrder.php?delId=' + delId + '&feeType=' + feeType +
//                     '&currencyId=' + currencyId;
//             },

//             '<?php echo showOtherLangText('No') ?>': function() {
//                 //Do whatever you want to do when No clicked
//                 $(this).dialog('close');
//             }
//         }
//     });

//     $("#dialog1").dialog("open");
//     $('.custom-header-text').remove();
//     $('.ui-dialog-content').prepend(
//         '<div class="custom-header-text"><span><?php echo showOtherLangText('Queue1.com Says') ?></span></div>');
// }
function getClearNumb(supplierIdVal){
var newOnClick = "window.location.href = 'addOrder.php?supplierIdVal=" + supplierIdVal + "&clear=1'";


//console.log('click',newOnClick);
//return false;

$('.deletelink').attr('onclick', newOnClick);
$('#clear-popup').modal('show');

}
// function getClearNumb(supplierIdVal) {

//      $("#dialog").dialog({
//         autoOpen: false,
//         modal: true,
//         //title     : "Title",
//         buttons: {
//             '<?php echo showOtherLangText('Yes') ?>': function() {
//                 //Do whatever you want to do when Yes clicked
//                 $(this).dialog('close');
//                 window.location.href = 'addOrder.php?supplierIdVal=' + supplierIdVal + '&clear=' + 1;
//             },

//             '<?php echo showOtherLangText('No') ?>': function() {
//                 //Do whatever you want to do when No clicked
//                 $(this).dialog('close');
//             }
//         }
//     });

//     $("#dialog").dialog("open");
//     $('.custom-header-text').remove();
//     $('.ui-dialog-content').prepend(
//         '<div class="custom-header-text"><span><?php echo showOtherLangText('Queue1.com Says') ?></span></div>');
// }

function showTotal(qty, priceId, pId) {
//console.log('hello');
//call ajax to save qty changes in database
if (isNaN(qty) || qty == '') {
qty = 0;
}

$.ajax({
method: "POST",
url: "addOrderAjax.php",
dataType: 'json',
data: {
supplierId: '<?php echo $_SESSION['supplierId'];?>',
pId: pId,
qty: qty,
currencyId: '<?php echo $curDet['id'] ?>'
}
})
.done(function(responseObj) {
$('#totalOrdArea').html(responseObj.resHtml);

$('#totalPrice' + priceId).html(responseObj.productPrice);

$('#totalPriceOther' + priceId).html(responseObj.totalPriceOther);
});

//end  

}
</script>
<script>
//order Fee type Fixed/Percentage
$(document).ready(function() {

$('#typeOfFee').change(function() {
var getValue = $('#typeOfFee').val();

if (getValue == '2') {

$('#amt').attr("placeholder", "Fee amount $");

} else {
$('#amt').attr("placeholder", "Fee percentage %");
}

});

});

$(document).ready(function() {
$("#feesave_add").submit(function(e) {
// Validate required fields
e.preventDefault(); 
});
});
</script>
<style>
.subitem {
display: none;
}
</style>
<script>
$('.item').on('mouseover', 'li', function() {
$(this).children(".subitem").show().end().siblings().find('.subitem').hide();
}).on('mouseleave', function() {
$('.subitem', this).hide();
});
</script>