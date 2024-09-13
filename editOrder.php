<?php 
include('inc/dbConfig.php'); //connection details


//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

//delete new items which are in temp
$selQry = " DELETE FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."' AND editOrdNewItemStatus=1  ";
mysqli_query($con, $selQry);

$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'edit_order' AND type_id = '0' AND designation_id = '".$_SESSION['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if ($permissionRow)
{
    echo "<script>window.location='index.php'</script>";
}

if(isset($_REQUEST['currencyId'])) 
{  
    $curDet = getCurrencyDet($_REQUEST['currencyId']);
    $curAmtVal = $curDet['amt'];
    $currencyId = $_REQUEST['currencyId'];
    
    $updateQry = " UPDATE  `tbl_orders` SET 
    `ordCurId` = '".$currencyId."'
    WHERE id='".$_GET['orderId']."'  ";
    mysqli_query($con, $updateQry);
}



if (!isset($_SESSION['supplierIdOrd']))
{
    $selQry = " SELECT * FROM tbl_orders WHERE id='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."' ";
    $orderResult = mysqli_query($con, $selQry);
    $orderRow = mysqli_fetch_array($orderResult);
    $_SESSION['supplierIdOrd'] = $orderRow['supplierId'];
}


//insert data in order_details_temp table when user land on this page
if (isset($_GET['orderId'])) 
{
   
    $selQry = " SELECT * FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."' AND editOrdNewItemStatus=0 limit 1 ";
    $tempOrdDetResult = mysqli_query($con, $selQry);
    
    $tempOrdDetRow = mysqli_fetch_array($tempOrdDetResult);
    
    if (!$tempOrdDetRow)
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

        $insertQry = " INSERT INTO `tbl_order_details_temp` (`id`, `account_id`, `ordId`, `pId`, `factor`, `price`, `qty`, `qtyReceived`, `totalAmt`,`note`, `lastPrice`, `stockPrice`, `stockQty`, `curPrice`, `currencyId`, `curAmt`, `customChargeId`, `customChargeType`) VALUES  ".implode(',', $values);
        mysqli_query($con, $insertQry);   

    }

}//end



$sqlSet = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
$resultSet = mysqli_query($con, $sqlSet);
$ordRow = mysqli_fetch_array($resultSet);


$curAmtVal = 0;
$otherCurAmt = 0;
$currencyId = 0;
if($ordRow['ordCurId'] > 0)
{
    $curDet = getCurrencyDet($ordRow['ordCurId']);

    if(!empty($curDet))
    {
        $otherCurAmt = $curDet['amt'];
        $currencyId = $curDet['id'];
        $curAmtVal = $curDet['amt'];
    }
    
}

//Add item charges in list
if( isset($_GET['itemCharges']) && $_GET['itemCharges'] > 0 && $_GET['feeType']=='1' )
{
    editCustomCharge($_GET['orderId'],$_GET['feeType'], $_GET['itemCharges'], $_SESSION['supplierIdOrd'], 1);
    orderNetValue($_GET['orderId'],$ordRow['ordCurId']);
}


//Add order charges in list
if( isset($_GET['itemCharges']) && $_GET['itemCharges'] > 0 && $_GET['feeType']=='3' )
{
    editCustomCharge($_GET['orderId'],$_GET['feeType'], $_GET['itemCharges'], $_SESSION['supplierIdOrd'], 1);
    orderNetValue($_GET['orderId'],$ordRow['ordCurId']);
}


if( !empty($_POST['itemName']) )
{ 
    $showHideInList = isset($_POST['visibility']) ? 1 : 0;

    $sql = " INSERT INTO `tbl_custom_items_fee` SET 
    `itemName` = '".$_POST['itemName']."',
    `unit` = '".$_POST['unit']."',
    `amt` = '".$_POST['itemFeeAmt']."',
    `visibility` = '".$showHideInList."',
    `account_id` = '".$_SESSION['accountId']."'  ";
    mysqli_query($con, $sql);
    $itemCharges = mysqli_insert_id($con);

    editCustomCharge($_GET['orderId'],1,$itemCharges, $_SESSION['supplierIdOrd'], 1);
    orderNetValue($_GET['orderId'],$ordRow['ordCurId']);
    
    echo '<script>window.location="editOrder.php?orderId='.$_GET['orderId'].'&supplierId='.$_SESSION['supplierIdOrd'].'&currencyId='.$ordRow['ordCurId'].' "</script>';
}


if( !empty($_POST['feeName']) )
{ 
    $showHideInList = isset($_POST['visibility']) ? 1 : 0;

    $sql = " INSERT INTO `tbl_order_fee` SET 
    `feeName` = '".$_POST['feeName']."',
    `feeType` = '".$_POST['feeType']."',
    `amt` = '".$_POST['amt']."',
    `visibility` = '".$showHideInList."',
    `isTaxFee` = '".$_POST['isTaxFee']."',
    `account_id` = '".$_SESSION['accountId']."'  ";

    mysqli_query($con, $sql);

    $itemCharges = mysqli_insert_id($con);

    editCustomCharge($_GET['orderId'],3,$itemCharges, $_SESSION['supplierIdOrd'], 1);
    orderNetValue($_GET['orderId'],$ordRow['ordCurId']);

    echo '<script>window.location="editOrder.php?orderId='.$_GET['orderId'].'&supplierId='.$_SESSION['supplierIdOrd'].'&currencyId='.$ordRow['ordCurId'].' "</script>';
}



if(isset($_REQUEST['currencyId']))
{ 
    $sql = " SELECT * FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' AND editOrdNewItemStatus=0  "; 
    $resultSet= mysqli_query($con, $sql);
    
    while($resultRow= mysqli_fetch_array($resultSet))
    { 
        $price=$resultRow['price'];
        $totalAmt=$resultRow['totalAmt'];
        
        $update = " UPDATE tbl_order_details_temp SET 
        `currencyId`='".$currencyId."',
        `curPrice` = '".($price*$otherCurAmt)."',
        `curAmt` = '".($totalAmt*$otherCurAmt)."'
        WHERE id= '".$resultRow['id']."' AND account_id = '".$_SESSION['accountId']."'  ";
        $result= mysqli_query($con, $update);    
        
    }
       orderNetValue($_GET['orderId'],$currencyId);

}



//add to cart
if( isset($_POST['updateOrder']) )
{

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $resRowOld = mysqli_fetch_array($res);
    
    //Update for new items
    foreach($_POST['productIds'] as $productId)
    { 
        if($_POST['qty'][$productId] > 0)
        { 
            //Add new product in tbl_order_details on update button
           $sqlQry = " SELECT * FROM tbl_order_details WHERE ordId='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."' AND pId='".$productId."' ";
            $orderDetailsResult = mysqli_query($con, $sqlQry);
            $orderDetailsResRow = mysqli_fetch_array($orderDetailsResult);

            if ( !isset($orderDetailsResRow['pId']) )
            {
               $sql = "INSERT INTO `tbl_order_details` SET

                `currencyId` = '".$currencyId."',
                `ordId` = '".$_GET['orderId']."',
                `factor` = '".($_POST['factor'][$productId])."', 
                `pId` = '".$productId."',
                `price` = '".($_POST['price'][$productId]/$_POST['factor'][$productId])."',
                `curPrice` = '".(($_POST['price'][$productId]/$_POST['factor'][$productId])*$curAmtVal)."', 
                `curAmt` = '".($_POST['price'][$productId]*$_POST['qty'][$productId]*$curAmtVal)."',
                `qty` = '".$_POST['qty'][$productId]."',
                `totalAmt` = '".($_POST['price'][$productId]*$_POST['qty'][$productId])."',
                `note` = '".$_POST['notes'][$productId]."',
                `account_id` = '".$_SESSION['accountId']."'   ";

                mysqli_query($con, $sql);

            }
            else
            {

                $upQry = " UPDATE  `tbl_order_details` SET
                `currencyId` = '".$currencyId."',
                `price` = '".($_POST['price'][$productId]/$_POST['factor'][$productId])."', 
                `curPrice` = '".(($_POST['price'][$productId]/$_POST['factor'][$productId])*$curAmtVal)."', 
                `qty` = '".$_POST['qty'][$productId]."', 
                `totalAmt` = '".($_POST['price'][$productId]*$_POST['qty'][$productId])."',
                `curAmt` = '".($_POST['price'][$productId]*$_POST['qty'][$productId]*$curAmtVal)."',
                `note` = '".$_POST['notes'][$productId]."'
                WHERE ordId = '".$_GET['orderId']."' AND pId = '".$productId."' AND account_id = '".$_SESSION['accountId']."'  ";
                mysqli_query($con, $upQry);
                
                orderNetValue($_GET['orderId'],$currencyId);
                
            }
       
        } 
        else
        {
            $delQry=" DELETE FROM tbl_order_details WHERE ordId='".$_GET['orderId']."' AND pId='".$productId."' AND account_id='".$_SESSION['accountId']."' ";
            mysqli_query($con, $delQry);
            
            orderNetValue($_GET['orderId'],$currencyId);
        }
    }//End foreach


    $sql=" SELECT * FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' and customChargeType > 0 AND editOrdNewItemStatus=0 ";
    $sqlSet= mysqli_query($con, $sql);  
    while($tempOrdDetRow = mysqli_fetch_array($sqlSet))
    {
        $sql=" SELECT * FROM tbl_order_details WHERE ordId='".$tempOrdDetRow['ordId']."' AND account_id = '".$_SESSION['accountId']."' and customChargeId='".$tempOrdDetRow['customChargeId']."' and customChargeType='".$tempOrdDetRow['customChargeType']."'  ";
        $ordQryData= mysqli_query($con, $sql);  
        $ordDetRowCheck = mysqli_fetch_array($ordQryData);

        if ($ordDetRowCheck)
        { 
            $updateQry = " UPDATE tbl_order_details SET 
            `note` = '".$tempOrdDetRow['note']."',
            `curPrice` = '".$tempOrdDetRow['curPrice']."',
            `currencyId` = '".$tempOrdDetRow['currencyId']."',
            `curAmt` = '".$tempOrdDetRow['curAmt']."'

            WHERE ordId='".$tempOrdDetRow['ordId']."' AND account_id = '".$_SESSION['accountId']."' and customChargeId='".$tempOrdDetRow['customChargeId']."' and customChargeType='".$tempOrdDetRow['customChargeType']."' ";
            mysqli_query($con, $updateQry); 
                
        }
        else
        {
        
            $insertQry = " INSERT INTO `tbl_order_details` SET

              `account_id` = '".$tempOrdDetRow['account_id']."',
              `ordId` = '".$tempOrdDetRow['ordId']."',
              `note` = '".$tempOrdDetRow['note']."',
              `customChargeType` = '".$tempOrdDetRow['customChargeType']."',
              `customChargeId` = '".$tempOrdDetRow['customChargeId']."',
              `price` = '".$tempOrdDetRow['price']."',
              `curPrice` = '".$tempOrdDetRow['curPrice']."',
              `currencyId` = '".$tempOrdDetRow['currencyId']."',
              `curAmt` = '".$tempOrdDetRow['curAmt']."',
              `qty` = '1',
              `totalAmt` = '".$tempOrdDetRow['totalAmt']."' ";

            mysqli_query($con, $insertQry); 
            
        }

    }//end while


   

    //show order net value
    orderNetValue($_GET['orderId'],$currencyId);
    
    //Insert few data in order journey tbl to show journey 
    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $resRow = mysqli_fetch_array($res);
//echo $resRowOld['ordAmt'] .'-'. $resRow['ordAmt'];
//echo "<br>";
    $diffPrice =  ($resRow['ordAmt'] - $resRowOld['ordAmt']);
    $notes = 'Order edited(Price Diff: '.getPriceWithCur($diffPrice, $getDefCurDet['curCode']).' )';

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
    `action` = 'edit' ";
    mysqli_query($con, $qry);



    //delete order_details_temp data after form submit
    $delQry=" DELETE FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."' ";
    mysqli_query($con, $delQry);

    echo '<script>window.location="runningOrders.php?updated=1"</script>';
    exit;
    
}
   
//delete item level / order level charges
if( isset($_GET['delId']) && $_GET['orderId'])
{   

    $sql=" SELECT * FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' and id='".$_GET['delId']."' ";
    $sqlSet= mysqli_query($con, $sql);  
    $tempOrdDetRow = mysqli_fetch_array($sqlSet);

    if($tempOrdDetRow['customChargeId'] && $tempOrdDetRow['customChargeType'])
    {
        $sql= " DELETE FROM tbl_order_details WHERE ordId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' and customChargeId='".$tempOrdDetRow['customChargeId']."' and customChargeType='".$tempOrdDetRow['customChargeType']."' ";
        $resultSet= mysqli_query($con, $sql);
    }

    $sql= " DELETE FROM tbl_order_details_temp WHERE id='".$_GET['delId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $resultSet= mysqli_query($con, $sql);

    orderNetValue($_GET['orderId'],$ordRow['ordCurId']);
    
    echo '<script>window.location="editOrder.php?orderId='.$_GET['orderId'].'&supplierId='.$_SESSION['supplierIdOrd'].'&delete=1"</script>';

}//end 

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Edit Order - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/style1.css">
    <link rel="stylesheet" href="Assets/css/style_p.css">
    <style>
    .fa-angle-right,
    .fa-angles-left {
        background: linear-gradient(180deg, #7a89ff 35%, #8795ff 115%);
        box-shadow: inset 1px 4px 4px #596bf3;
        filter: blur(0.2px);
        padding: 5px;
        border-radius: 50%;
        border: 0.5px solid;
        border-image-source: linear-gradient(175.52deg, #7a89ff 26.99%, #c2c9ff 106.12%);
        margin-left: 10px;
        font-size: 10px;
        vertical-align: middle;
        width: 22px;
    }
    .tb-head, .prdtImg.tb-head { font-weight: 500;color: #232859; }
    .cntTable { color: #232859;font-weight: 400; }
    .newOrde { padding: 0 25px 0 5px; }
    .container.erdOrder{padding: 1rem 10px 0 10px;} 
    .fw-medium{ font-weight:500; }
    .fw-semibold{ font-weight:600; }
    @media(min-width:576px){ .container.erdOrder{padding: 1rem 24px 0 24px;} }
    @media(min-width:992px){ 
        html[dir=rtl] .stcPart .reqInfo, html[dir=rtl] .ordInfo { padding: 0 50px 0 0; }
        .nwNxt-Btn1 .btn__box { max-width: 174px !important;margin-left: auto; }
        html[dir="rtl"] .nwNxt-Btn1 .btn__box { margin-left: 0;margin-right: auto; } 
    }
    @media (max-width:992px) {
        .newFeatures { padding: 1rem 0 0 0; }
        .newFeatures .ms-auto { margin: 0 auto !important; }
        .btn__box { max-width: 14rem;}
        .fetBtn img { height:3rem; }
        .newOrdTask1 {background: rgba(255, 255, 255, 0.8);box-shadow: -10px 10px 30px rgba(96, 113, 175, 0.1);padding: 0;margin-bottom: 5px;border-radius:.5rem;display:flex;}
        .newOrd-CntPrt{ position: relative; }
        .itmBody > div:first-child{ position: absolute;top:0.5rem;left:0;font-size: 12px;width: 25% !important;text-align: center; }
        .itmBody > div:first-child p { font-size: 12px; }
        .prdtImg { width: 25%; }
        .prdtCnt-Fst { width: 50% !important; }
        .edit-order-section.update .prdtCnt-Scnd { width: 25% !important; }
        .edit-order-section.update .prdtCnt-Scnd .itm-Quantity,.edit-order-section.update .prdtCnt-Scnd .ttlCr-Type { width: 100% !important; }  
        .edit-order-section .itm-Unit.tb-bdy { width: 40%; }
        .prdtCr-Unit .crncy-Type.w-50 { width: 60% !important; }
        .itmBody { font-size: 12px; }
        .mb-brCode .ord-StockQty { width: 50%; }
        .btn__box .btn { height:40px;padding: 0 16px;display: inline-flex;align-items: center;justify-content:center; }
        .fetBtn img { height:40px; }
        .ttlDft-Crcy, .ttlOtr-Crcy{ min-width: 100%; }
        .edit-order-section .Itm-Name.tb-bdy p{ overflow: hidden;text-overflow: ellipsis;-webkit-line-clamp: 1;display: -webkit-box;-webkit-box-orient: vertical;white-space: normal; }
        .update .prdtImg img { position: relative;top:1rem; }   

        html[dir="rtl"] .sltSupp.nwOrd-Num.position .ord-Box{border-radius:10px !important;border-right: 1px solid #94ebc6;}
        html[dir="rtl"] .itmBody > div:first-child { left: auto; right: 0; }
        .update .sltSupp.nwOrd-Num { width: 100% !important; }
        .update .nwNxt-Btn { flex-direction: row-reverse; } 
        html[dir="rtl"] .update .ordInfo { padding: 0; } 
        .col-lg-9.col-xl-10.newFeature { order:3;padding-top: .5rem; }
        .container.pt-5.topOrder {padding:0 !important;}
        .mbFeature .ms-auto.text-center.w-100 { margin: 0 auto !important; }
    }
    @media screen and (max-width: 1024px) {
        .update .sltSupp.nwOrd-Num { width: 35%; }
        .ordNum { margin: 0; }
    }
    @media screen and (max-width: 767px) {
        .ordFeature { width: 49.3%; }
        /* .prdtCnt-Scnd { width: calc(84% - 55px); } */
        .ordDetail { padding-bottom: 3rem; }
    }
    @media screen and (max-width: 575px) {
        .btn__box{max-width:100%;flex:none;}
        .update .topOrder { padding: 8px 10px 0 10px !important; }
        .update .add-new-items.btn-primary{width: 100%;}
        .edit-order-section .ttlCr-Type.d-flex.align-items-center { flex-wrap: wrap !important; }
    }
    @media screen and (min-width: 1600px) {
        .ordInfo { padding: 0 0 0 64px; }
        .prcTable { font-size: 1rem; }
        .curRow { font-size: 1.125rem; }
        .container.topOrder.erdOrder { margin-top: -1.5rem; }
    }
    @media screen and (min-width: 1400px) { 
        .container.topOrder.erdOrder { margin-top: -1.5rem; }
    }
    @media (max-width: 1599px) {
        .itmTable { font-size: .875rem; }
        .itmBody { font-size: .812rem; }
    }
    
    .edit-order-section.update .ttlCr-Type .ttlOtr-Crcy, .edit-order-section.update .ttlCr-Type .ttlDft-Crcy, .edit-order-section.update .ttlCr-Type, .edit-order-section.update .ttlCr-Type {
    text-align: left !important;

    }
    .edit-order-section .ttlCr-Type { text-align: right; }
    .qty-itm, .note-itm { font-weight: 700;font-size: .875rem; }
    .ttlCr-Type { font-weight: 600; }
    .ordInfo { padding:0; }
    .ttlDft-Crcy { margin: auto; }
    .edit-order-section.update .ttlCr-Type .ttlOtr-Crcy, .edit-order-section.update .ttlCr-Type .ttlDft-Crcy,
    .edit-order-section.update .ttlCr-Type, .edit-order-section.update .ttlCr-Type { text-align: left !important; }
     
    html[dir="rtl"] .edit-order-section.update .ttlCr-Type .ttlOtr-Crcy, html[dir="rtl"] .edit-order-section.update .ttlCr-Type .ttlDft-Crcy, 
    html[dir="rtl"] .edit-order-section.update .ttlCr-Type, html[dir="rtl"] .edit-order-section.update .ttlCr-Type, html[dir="rtl"] .edit-order-section.update .ttlCr-Type .text-start { text-align: right !important; }
</style>
</head>

<body>
    <main>
        <div class="container-fluid newOrder">
            <div class="row g-0">
                <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                    <?php require_once('nav.php');?>
                </div>
                <div class="cntArea">
                    <section class="usr-info">
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-end">
                                <h1 class="h1"><?php echo showOtherLangText('Edit Order'); ?></h1>
                            </div>
                            <div class="col-md-8 d-flex align-items-center justify-content-end">
                                <div class="mbPage">
                                    <div class="mb-nav" id="mb-nav">
                                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#navbarSupportedContent"
                                            aria-controls="navbarSupportedContent" aria-expanded="false"
                                            aria-label="Toggle navigation">
                                            <span class="navbar-toggler-icon"><i class="fa-solid fa-bars"></i></span>
                                        </button>
                                    </div>
                                    <div class="mbpg-name">
                                        <h1 class="h1"><?php echo showOtherLangText('Edit Order'); ?></h1>
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

                    <section class="ordDetail edit-order-section update">
                        <div class="tpBar-grn"></div>
                        <div class="stcPart position-relative">
                            <div class="container erdOrder">
                                <form name="frmupdateOrder" id="frmupdateOrder"
                                    action="editOrder.php?orderId=<?php echo $_GET['orderId'];?>" method="post"
                                    autocomplete="off">
                                    <div class="row g-0">
                                        <div class="sltSupp nwOrd-Num position start-0 p-0"
                                            style="top:1rem; min-width:fit-content;">
                                            <div class="ord-Box w-100 ms-0 d-flex flex-wrap gap-2">
                                                <div class="ordNum">
                                                    <h4 class="subTittle1 flex-wrap">
                                                        <span><?php echo showOtherLangText('Task no.'); ?>#:</span>
                                                        <span><?php echo $ordRow['ordNumber'];?></span></h4>
                                                </div>
                                                <div class="ordNum">
                                                    <h4 class="subTittle1 flex-wrap">
                                                        <span><?php echo showOtherLangText('Supplier'); ?>:</span>
                                                        <span><?php
$ordDetQry = " SELECT * FROM tbl_suppliers WHERE id='".$_SESSION['supplierIdOrd']."' AND account_Id='".$_SESSION['accountId']."' ";
$ordDetRes = mysqli_query($con, $ordDetQry);
$ordDetResRow = mysqli_fetch_array($ordDetRes);

echo $ordDetResRow['name'];

?></span></h4>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-lg-9 col-xl-10 newFeature">
                                            <div class="container">
                                                <div class="mbFeature">
                                                    <div class="ms-auto text-center w-100" style="max-width:236px;">
                                                        <div class="row g-3 featRow">
                                                            <div
                                                                class="col-md-6 ordFeature dropdown drpCurr position-relative">
                                                                <a href="javascript:void(0)"
                                                                    class="dropdown-toggle tabFet" role="button"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <span class="currency"></span>
                                                                    <p class="btn2"><span
                                                                            id="add_currency"><?php echo showOtherLangText('currency') ?>
                                                                            <i
                                                                                class="fa-solid fa-angle-down"></i></span>
                                                                    </p>
                                                                </a>

                                                                <ul class="currency_dropdown dropdown-menu">
                                                                    <li data-id="0"
                                                                        data-value="<?php echo showOtherLangText('Currency') ?>">
                                                                        <a class="dropdown-item"
                                                                            href="javascript:void(0)"><?php echo showOtherLangText('Currency') ?>
                                                                        </a>
                                                                    </li>
                                                                    <?php
                            $sqlSet = " SELECT * FROM tbl_currency WHERE is_default = 0 AND account_id = '".$_SESSION['accountId']."' order by currency ";
                            $resultSet = mysqli_query($con, $sqlSet); 
                            while($curRow=mysqli_fetch_array($resultSet))
                                                            {
                                                    $sql = " SELECT * FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id ='".$_SESSION['accountId']."' ";
                                $result=mysqli_query($con, $sql);
                                $resultRow= mysqli_fetch_array($result);

                                if (!empty($resultRow['currencyId']))
                                {
                                    
                                    $sel = $resultRow['currencyId'] == $curRow['id'] ? 'selected' : '';

                                }
                                else
                                {
                                    
                                    $sel = $_REQUEST['currencyId'] == $curRow['id'] ? 'selected' : '';
                                }
                            echo '<li data-id="'.$curRow['id'].'" data-value="'.$curRow['currency'].'"><a class="dropdown-item '.$sel.' " 
                                                href="javascript:void(0)">'.$curRow['currency'].'</a></li>';
                            }
                            ?>

                                                                </ul>

                                                            </div>
                                                            <div class="col-md-6 ordFeature drpFee position-relative">
                                                                <a href="javascript:void(0)"
                                                                    class="dropdown-toggle tabFet" role="button"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <span class="fee"></span>
                                                                    <p class="btn2">
                                                                        <?php echo showOtherLangText('Fee'); ?> <i
                                                                            class="fa-solid fa-angle-down"></i>
                                                                    </p>
                                                                </a>

                                                                <ul class="item dropdown-menu">
                                                                    <li class="innerDrop dropdown">
                                                                        <a class="dropdown-item"
                                                                            href="javascript:void(0)"><?php echo showOtherLangText('Service Item'); ?></a>
                                                                        <ul class="subitem  submenu list-unstyled">
                                                                            <?php 
                                            //add item fee & custom fee modal box 
                                            $sql = " SELECT * FROM tbl_custom_items_fee WHERE visibility='1' AND account_id='".$_SESSION['accountId']."' ";
                                            $customItemsResult = mysqli_query($con, $sql);

                                            while ($resultRow = mysqli_fetch_array($customItemsResult)) 
                                            {
                                            echo "<li class='innerLi'><a class='dropdown-item' tabindex='-1' href='editOrder.php?orderId=".$_GET['orderId']."&supplierId=".$_SESSION['supplierIdOrd']."&feeType=1&itemCharges=".$resultRow['id']."&currencyId=".$ordRow['ordCurId']." '>".$resultRow['itemName']."</a></li>";
                                            }
                                        ?>
                                                                        </ul>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                            class="sub-btn std-btn mb-usrBkbtn"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#new-service-item"
                                                                            href="javascript:void(0)"><?php echo showOtherLangText('New Service Item'); ?></a>
                                                                    </li>
                                                                    <li class="innerDrop dropdown">
                                                                        <a class="dropdown-item"
                                                                            href="javascript:void(0)"><?php echo showOtherLangText('Fee'); ?></a>
                                                                        <ul
                                                                            class="subitem  submenu large list-unstyled">
                                                                            <?php
                                        //add item fee & custom fee modal box 
                                        $sqlQry = " SELECT * FROM tbl_order_fee WHERE visibility='1' AND account_id='".$_SESSION['accountId']."' ";
                                        $ordFeeFetch = mysqli_query($con, $sqlQry);
                                        //$innerLiCount = 0;
                                        while ($resultRow = mysqli_fetch_array($ordFeeFetch))
                                        {
                                            // $innerLiCount++;
                                            echo "<li class='innerLi'><a class='dropdown-item' tabindex='-1' href='editOrder.php?orderId=".$_GET['orderId']."&supplierId=".$_SESSION['supplierIdOrd']."&feeType=3&itemCharges=".$resultRow['id']."&currencyId=".$ordRow['ordCurId']."'>".$resultRow['feeName']."</a> ";
                                        } 
                                    ?>
                                                                        </ul>
                                                                    </li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0)"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#new-fees-item"><?php echo showOtherLangText('New Fee') ?></a>
                                                                    </li>

                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
$totalTax =(($chargePrice+$fixedCharges+$totalCalDiscount)*$taxCharges/100);//total tax feeType=1
$totalTaxOther =(($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther)*$taxCharges/100);

$totalDiscountOther = (($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther)*$totalTaxChargesOther/100);

$netTotalAmt= ($chargePrice+$fixedCharges+$totalCalDiscount+$totalTax);
$netTotalAmtOther= ($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther+$totalTaxOther);



?>
                                        <div class="col-lg-3 col-xl-2 nwNxt-Btn nwNxt-Btn1 d-flex justify-content-between">
                                            <div class="fetBtn">
                                                <a href="javascript:void(0)">
                                                    <img src="Assets/icons/dashboard.svg" alt="dashboard">
                                                </a>
                                            </div>
                                            <div class="d-inline-flex flex-lg-column nwNxt-Btn2  col gap-3 justify-content-end btn__box">
                                                <a href="javascript:void(0)" class=" btn btn-primary orderupdate w-100">
                                                    <span class="align-middle"><?php echo showOtherLangText('Update'); ?></span> 
                                                    <i class="fa-solid fa-angle-right d-none d-lg-inline-block"></i>
                                                </a> 
                                                <input type="hidden" name="updateOrder" value="<?php echo showOtherLangText('Update Order'); ?>" /> 
                                                <a href="runningOrders.php" class=" btn  btn-primary update w-100"><?php echo showOtherLangText('Back'); ?></a>
                                            </div>
                                            
                                        </div>
                                    </div>
                            </div>
                            <div class="container topOrder erdOrder">
                                <div class="col-lg-9 col-xl-10">
                                    <div class="row g-0 justify-content-between">
                                        <div class="sltSupp">
                                            <div id="searchBox2" class="input-group srchBx">
                                                <input type="search" class="form-control"
                                                    placeholder="<?php echo showOtherLangText('Search Item'); ?>"
                                                    name="search2" id="search2"
                                                    onKeyUp="myFunction('search2', 'totalOrdArea', 1)" aria-label="Search">
                                                <div class="input-group-append">
                                                    <button class="btn" type="button">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="ordInfo erdInfo pr-0" id="totalOrdArea">
                                            <div class="container">
                                                <div class="prcTable">
                                                    <?php 
                                                    //get the sum of all product and item level charges 

                                                    $sqlSet="SELECT *,SUM(totalAmt) AS totalAmt, SUM(curAmt) AS totalAmtOther FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."'  AND (customChargeType='1' OR customChargeType='0')";
                                                    $resultSet = mysqli_query($con, $sqlSet);
                                                    $chargeRow = mysqli_fetch_array($resultSet);    
                                                    $chargePrice=$chargeRow['totalAmt'];
                                                    $chargePriceOther=$chargeRow['totalAmtOther'];

                                                    //to find order level charge
                                                    $ordCount="SELECT * from tbl_order_details_temp where ordId='".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."' AND customChargeType='2' ";
                                                    $ordCountResult = mysqli_query($con, $ordCount);
                                                    $ordCountRow = mysqli_num_rows($ordCountResult);

                                                    if ($ordCountRow > 0)
                                                    { ?>
                                                    <div class="price justify-content-between">
                                                        <div class="p-2 delIcn text-center"></div>
                                                        <div class="p-2 txnmRow">
                                                            <p><?php echo showOtherLangText('Sub Total'); ?></p>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-end curRow">
                                                            <div class="p-2">
                                                                <p><?php showPrice($chargePrice, $getDefCurDet['curCode']); ?>
                                                                </p>
                                                            </div>
                                                            <?php
                                                            if ($chargeRow['currencyId'] > 0)
                                                                { ?>
                                                            <div class="p-2 otherCurr">
                                                                <p><?php echo showOtherCur($chargePriceOther, $chargeRow['currencyId']); ?>
                                                                </p>
                                                            </div>
                                                            <?php  } ?>
                                                        </div>
                                                    </div>
                                                    <?php  } //Starts order level fixed discount charges
                                                    $sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details_temp od 
                                                    INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                                                    WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."' and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

                                                    $ordQry = mysqli_query($con, $sql);

                                                    $fixedCharges = 0;
                                                    $fixedChargesOther = 0;
                                                    while($row = mysqli_fetch_array($ordQry))//show here order level charges
                                                    {
                                                    $fixedCharges += $row['price'];
                                                    $fixedChargesOther += $row['curAmt'];

                                                    ?>
                                                    <div class="price justify-content-between taxRow">
                                                        <div class="p-2 delIcn text-center">
                                                            <a onClick="getDelNumb('<?php echo $row['id'] ?>', '<?php echo $row['ordId'] ?>', '<?php echo $_SESSION['supplierIdOrd'] ?>');"
                                                                href="javascript:void(0)">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </a>
                                                        </div>
                                                        <div class="p-2 txnmRow">
                                                            <p><?php echo $row['feeName'];?></p>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-end curRow">
                                                            <div class="p-2">
                                                                <p><?php showPrice($row['price'], $getDefCurDet['curCode']);?>
                                                                </p>
                                                            </div>
                                                            <?php 
                                                        if($row['currencyId'] > 0) 
                                                        {   ?>
                                                            <div class="p-2 otherCurr">
                                                                <p><?php echo showOtherCur($row['curAmt'],$row['currencyId']);?>
                                                                </p>
                                                            </div>
                                                            <?php 
                                                } ?>
                                                        </div>
                                                    </div>
                                                    <?php } 
                                                    $sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details_temp od 
                                                    INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                                                    WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."' and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
                                                    $ordQry = mysqli_query($con, $sql);

                                                    $perCharges = 0;
                                                    $perChargesOther = 0;
                                                    while($row = mysqli_fetch_array($ordQry))//show here order level charges
                                                    {
                                                    $perCharges += $row['price'];
                                                    $calDiscount = ($chargePrice*$row['price']/100);
                                                    $calDiscountOther = ($chargePriceOther*$row['price']/100);


                                                    ?>
                                                    <div class="price justify-content-between taxRow">
                                                        <div class="p-2 delIcn text-center">
                                                            <a onClick="getDelNumb('<?php echo $row['id'] ?>', '<?php echo $row['ordId'] ?>', '<?php echo $_SESSION['supplierIdOrd'] ?>');"
                                                                href="javascript:void(0)">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </a>
                                                        </div>
                                                        <div class="p-2 txnmRow">
                                                            <p><?php echo $row['feeName'];?><?php echo  $row['price'];?> %
                                                            </p>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-end curRow">
                                                            <div class="p-2">
                                                                <p><?php showPrice($calDiscount, $getDefCurDet['curCode']);?>
                                                                </p>
                                                            </div>
                                                            <?php 
                                                            if ($row['currencyId'] > 0)
                                                            {    ?>
                                                            <div class="p-2 otherCurr">
                                                                <p><?php echo showOtherCur($calDiscountOther,$row['currencyId']);?>
                                                                </p>
                                                            </div>
                                                            <?php 
                                                        } ?>
                                                        </div>
                                                    </div>
                                                    <?php }  ?>
                                                    <?php 
                                                    $totalCalDiscount =($chargePrice*$perCharges/100);//total discount feeType=3
                                                    $totalCalDiscountOther = ($chargePriceOther*$perCharges/100);


                                                    $sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details_temp od 
                                                    INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                                                    WHERE od.ordId = '".$_GET['orderId']."' AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
                                                    $ordQry = mysqli_query($con, $sql);

                                                    $taxCharges = 0;
                                                    $totalTaxChargesOther = 0;

                                                    while($row = mysqli_fetch_array($ordQry))//show here order level charges
                                                    {
                                                    $taxCharges += $row['price'];
                                                    $calTax = (($chargePrice+ $fixedCharges+$totalCalDiscount)*$row['price']/100);
                                                    $calTaxOther = (($chargePriceOther+ $fixedChargesOther+$totalCalDiscountOther)*$row['price']/100);
                                                    //echo"totalDiscountOther=".$totalDiscountOther;



                                                    ?>
                                                    <div class="price justify-content-between taxRow">
                                                        <div class="p-2 delIcn text-center">
                                                            <a onClick="getDelNumb('<?php echo $row['id'] ?>', '<?php echo $row['ordId'] ?>', '<?php echo $_SESSION['supplierIdOrd'] ?>');"
                                                                href="javascript:void(0)">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </a>
                                                        </div>
                                                        <div class="p-2 txnmRow">
                                                            <p><?php echo $row['feeName'];?><?php echo $row['price'];?> %
                                                            </p>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-end curRow">
                                                            <div class="p-2">
                                                                <p><?php showPrice($calTax, $getDefCurDet['curCode']);?></p>
                                                            </div>
                                                            <?php 
                                                            if ($row['currencyId'] > 0)
                                                            {?>
                                                            <div class="p-2 otherCurr">
                                                                <p><?php echo showOtherCur($calTaxOther, $row['currencyId']);?>
                                                                </p>
                                                            </div>
                                                            <?php 
                                                            } ?>
                                                        </div>
                                                    </div>

                                                    <?php } $totalTax =(($chargePrice+$fixedCharges+$totalCalDiscount)*$taxCharges/100);//total tax feeType=1
                                                    $totalTaxOther =(($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther)*$taxCharges/100);

                                                    $totalDiscountOther = (($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther)*$totalTaxChargesOther/100);

                                                    $netTotalAmt= ($chargePrice+$fixedCharges+$totalCalDiscount+$totalTax);
                                                    $netTotalAmtOther= ($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther+$totalTaxOther);



                                                    ?>
                                                    <div <?php
                                                            if ($ordCountRow == 0)
                                                            { 
                                                    echo 'style="border-top: 0px;"';  
                                                    } ?> class="price justify-content-between grdTtl-Row">
                                                        <div class="p-2 delIcn text-center"></div>
                                                        <div class="p-2 txnmRow">
                                                            <p><?php echo showOtherLangText('Grand Total'); ?></p>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-end curRow">
                                                            <div class="p-2">
                                                                <p><?php showPrice($netTotalAmt, $getDefCurDet['curCode']) ?>
                                                                </p>
                                                            </div>
                                                            <?php 
                                                            $sql=" SELECT * FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."'  ";
                                                            $sqlSet= mysqli_query($con, $sql);
                                                            $sqlSetRow= mysqli_fetch_array($sqlSet);

                                                            if (!empty($sqlSetRow['currencyId'])) {?>
                                                            <div class="p-2 otherCurr">
                                                                <p><?php echo showOtherCur($netTotalAmtOther, $sqlSetRow['currencyId']);?>
                                                                </p>
                                                            </div>

                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="col-md-2 smBtn">
                                        <div class="btnBg">
                                            <a href="receiveOrder.php" class="btn sub-btn"><span
                                                    class="align-middle">Update</span> <i
                                                    class="fa-solid fa-angles-right"></i></a>
                                        </div>
                                        <div class="btnBg mt-3">
                                            <a href="index.php" class="sub-btn std-btn">Back</a>
                                        </div>
                                        <div class="fetBtn">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/dashboard.svg" alt="dashboard">
                                            </a>
                                        </div>
                                    </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php

//other Charge query
$totalCustomCharges = 0;
$sql = "SELECT tp.itemName, tp.unit, od.* FROM tbl_order_details_temp od 
INNER JOIN tbl_custom_items_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
WHERE od.ordId = '".$_GET['orderId']."' AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=1 ORDER BY tp.itemName ";
$chrgQry = mysqli_query($con, $sql);

//main product query
$sql = "SELECT tp.*, 
od.totalAmt AS ordPrice, 
od.qty AS ordQty, 
od.note, 
od.ordId, 
o.supplierId AS SupplierId, 
o.ordCurId, 
od.curAmt, 
s.Qty AS stockQty,
IF(u.name!='',u.name,tp.unitP) as purchaseUnit 
FROM tbl_order_details_temp od 
INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id AND tp.status=1
LEFT JOIN tbl_stocks s ON(s.pId=tp.id) AND s.account_id=tp.account_Id
INNER JOIN tbl_orders o ON(o.id = od.ordId) AND o.account_id = od.account_id
LEFT JOIN tbl_units u ON(u.id = tp.unitP) AND u.account_id = tp.account_id
WHERE od.ordId = '".$_GET['orderId']."' AND tp.account_id = '".$_SESSION['accountId']."' ORDER BY tp.itemName ";
$ordQry = mysqli_query($con, $sql);

?>
                        <div class="container nordPrice position-relative">
                            <!-- Item Table Head Start -->
                            <div class="d-flex align-items-center itmTable">
                                <div class=" tb-head" style="width: 44px;">
                                    <p><?php echo mysqli_num_rows($ordQry) > 0 ? mysqli_num_rows($ordQry) : ''; ?></p>
                                </div>
                                <div class="prdtImg tb-head ">
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
                                        <div class="crncy-Type d-flex flex-wrap align-items-center">
                                            <?php if($ordRow['ordCurId'] > 0)
{   ?>
                                            <div class="dflt-Currency tb-head">
                                                <p><?php echo showOtherLangText('P.Price'); ?>(<?php echo $getDefCurDet['curCode'] ?>)
                                                </p>
                                            </div>
                                            <div class="othr-Currency tb-head">
                                                <p><?php echo showOtherLangText('P.Price'); ?>(<?php echo $curDet['curCode'];?>)
                                                </p>
                                            </div>
                                            <?php }else{ ?>
                                            <div class="dflt-Currency tb-head">
                                                <p><?php echo showOtherLangText('P.Price'); ?>(<?php echo $getDefCurDet['curCode'] ?>)
                                                </p>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="itm-Unit tb-head">
                                            <p><?php echo showOtherLangText('P.Unit'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="prdtStk-Qty tb-head">
                                    <p><?php echo showOtherLangText('S.Qty'); ?></p>
                                </div>
                                <div class="prdtCnt-Scnd d-lg-flex align-items-center">
                                    <div class="itm-Quantity tb-head">
                                        <p><?php echo showOtherLangText('Qty'); ?></p>
                                    </div>
                                    <div class="ttlCr-Type d-flex flex-wrap align-items-center">
                                        <?php if($ordRow['ordCurId'] > 0)
     {?>
                                        <div class="ttlDft-Crcy tb-head">
                                            <p><?php echo showOtherLangText('Total'); ?>(<?php echo $getDefCurDet['curCode'] ?>)
                                            </p>
                                        </div>
                                        <div class="ttlOtr-Crcy tb-head">
                                            <p><?php echo showOtherLangText('Total'); ?>(<?php echo $curDet['curCode'];?>)
                                            </p>
                                        </div>
                                        <?php }else{ ?>
                                        <div class="ttlDft-Crcy tb-head">
                                            <p><?php echo showOtherLangText('Total'); ?>(<?php echo $getDefCurDet['curCode'] ?>)
                                            </p>
                                        </div>
                                        <?php } ?>
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

                        <div id="boxscroll" class="compact__tb__bdy">
                            <div class="container cntTable">
                                <!-- Item Table Body Start -->
                                <?php 
                                    $fixedCharges = 0;
                                    $perCharges = 0;
                                    $x = 0;
                                    $y = 0;
                                    while($row = mysqli_fetch_array($chrgQry))//show here order level charges
                                    { 

                                    $totalCustomCharges += $row['price'];
                                    $x++;
                                    $y++;
                                ?>
                                <div class="newOrdTask">
                                    <div class="d-flex align-items-lg-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="counter__box tb-bdy" style="width:44px;">
                                            <p><?php echo $y; ?></p>
                                        </div>
                                        <div class="prdtImg tb-bdy">
                                            <a title="<?php echo showOtherLangText('Delete') ?>"
                                                href="javascript:void(0)"
                                                onClick="getDelNumb('<?php echo $row['id'] ?>', '<?php echo $row['ordId'] ?>', '<?php echo $_SESSION['supplierIdOrd'] ?>');"
                                                style="color:#808080" class="glyphicon glyphicon-trash"><i
                                                    class="fa-solid fa-trash-can"></i></a>
                                        </div>
                                        <div class="prdtCnt-Fst d-flex align-items-center">
                                            <div class="Itm-Name tb-bdy fw-medium">
                                                <p><?php echo $row['itemName'];?></p>
                                            </div>
                                            <div class="Itm-brCode tb-bdy">
                                                <p class="ord-brCode"></p>
                                            </div>
                                            <div class="prdtCr-Unit d-flex">
                                                <div class="crncy-Type d-flex flex-wrap align-items-center">
                                                    <div class="dflt-Currency tb-bdy">
                                                        <p><?php showPrice($row['price'], $getDefCurDet['curCode']);?>
                                                        </p>
                                                    </div>
                                                    <?php 
                                                    if($ordRow['ordCurId'] > 0) { 
                                                        echo '<div class="othr-Currency tb-bdy"><p>'.showOtherCur($row['curAmt'], $row['currencyId']).'</p></div>';
                                                    } 
                                                    ?> 
                                                </div>
                                                <div class="itm-Unit tb-bdy">
                                                    <p><?php echo $row['unit'];?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdtStk-Qty tb-bdy">
                                            <p class="ord-StockQty"><span class="tabOn-Stk">On stock</span></p>
                                        </div>
                                        <div class="prdtCnt-Scnd d-lg-flex align-items-center">
                                            <div class="itm-Quantity tb-bdy">
                                                1
                                            </div>
                                            <div class="ttlCr-Type d-flex flex-wrap align-items-center">
                                                <div class="ttlDft-Crcy tb-bdy">
                                                    <p><?php showPrice($row['price'], $getDefCurDet['curCode']);?></p>
                                                </div>
                                                <?php 
                                                if($ordRow['ordCurId'] > 0)
                                                {
                                                    echo '<div class="ttlOtr-Crcy tb-bdy"><p>'.showOtherCur($row['curAmt'], $row['currencyId']).'</p></div>';          
                                                } 
                                                ?>
                                            </div>
                                        </div>
                                        <div class="prdt-Hide">
                                            <div class="prdt-Note tb-bdy">
                                                <div class="mb-brCode"></div>
                                                <input type="text" class="note-itm form-control" autocomplete="off"
                                                    name="itemNotes[<?php echo $row['id'];?>]"
                                                    id="itemNotes<?php echo $x;?>"
                                                    onChange="getItemNotesVal('<?php echo $x;?>', '<?php echo $row['id'];?>');"
                                                    value="<?php echo $row['note'];?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>

                                </div>
                                <?php } 
                                $productsConfirmedQtyArr = getConfirmTotalQtyReq($_SESSION['accountId']);
                                $pidArr = [];
                                $x = 0;
                                while($row = mysqli_fetch_array($ordQry))
                                {
                                $pidArr[] = $row['id'];
                                $x++;
                                $y++;

                                $stockQty = $row['stockQty'];
                                $totalProQty = isset($productsConfirmedQtyArr[$row['id']]) ? $productsConfirmedQtyArr[$row['id']] : 0;
                                $stockQty = $stockQty - $totalProQty;

                                // $sqlSet = " SELECT qty stockQty FROM tbl_stocks WHERE pId = '".$row['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";
                                // $resultSet = mysqli_query($con, $sqlSet);
                                // $stkRow = mysqli_fetch_array($resultSet);

                                // $sqlSet = " SELECT GROUP_CONCAT(s.name) suppls FROM tbl_productsuppliers ps
                                // INNER JOIN tbl_suppliers s ON(ps.supplierId = s.id) AND ps.account_id = s.account_id WHERE ps.productId = '".$row['id']."' AND ps.account_id = '".$_SESSION['accountId']."'  GROUP BY ps.productId ";
                                // $resultSet = mysqli_query($con, $sqlSet);
                                // $supRow = mysqli_fetch_array($resultSet);

                                ?>
                                <input type="hidden" name="productIds[]" value="<?php echo $row['id'];?>" />
                                <input type="hidden" name="suppliersIds[<?php echo $row['id'];?>]"
                                    value="<?php echo $row['SupplierId'];?>" />
                                <input type="hidden" name="factor[<?php echo $row['id'];?>]" id="factor<?php echo $x;?>"
                                    value="<?php echo $row['factor'];?>" />
                                <input type="hidden" name="totalPriceShowTop[]" id="totalPriceShowTop<?php echo $x;?>"
                                    value="<?php showPrice($row['ordPrice'], $getDefCurDet['curCode']);?>" />
                                <div class="newOrdTask">
                                    <div class="d-flex align-items-lg-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="tb-bdy" style="width:44px;">
                                            <p><?php echo $y; ?></p>
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
                                            <div class="Itm-Name tb-bdy fw-medium " data-text="<?php echo showOtherLangText('Item'); ?>">
                                                <p><?php echo $row['itemName'];?></p>
                                            </div>
                                            <div class="Itm-brCode tb-bdy">
                                                <p class="ord-brCode"><?php echo $row['barCode'];?></p>
                                            </div>
                                            <div class="prdtCr-Unit d-flex">
                                                <div class="crncy-Type d-flex flex-wrap align-items-center">
                                                    <div class="dflt-Currency tb-bdy " data-text="<?php echo showOtherLangText('P.Price'); ?>">
                                                        <p><?php showPrice($row['price']*$row['factor'], $getDefCurDet['curCode']);?><input
                                                                type="hidden" name="price[<?php echo $row['id'];?>]"
                                                                id="<?php echo $x;?>"
                                                                value="<?php echo ($row['price']*$row['factor']);?>" />
                                                        </p>
                                                    </div>
                                                    <?php 
                                                    if($ordRow['ordCurId'] > 0)
                                                    {   ?>
                                                    <div class="othr-Currency tb-bdy">
                                                        <p><?php  echo showOtherCur(($row['price']*$row['factor']*$curDet['amt']), $ordRow['ordCurId']);
                                                    ?></p>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="itm-Unit tb-bdy " data-text="<?php echo showOtherLangText('P.Unit'); ?>">
                                                    <p><?php echo $row['purchaseUnit'];?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdtStk-Qty tb-bdy">
                                            <p class="ord-StockQty" <?php echo ( ($row['minLevel'] == 0 && $stockQty < $row['minLevel']) || (round($stockQty/$row['factor']) < round($row['minLevel']/$row['factor']))  ) ? 'style="display: flex;flex-direction: column;justify-content: center;align-items: center;background-color: pink;width: 43px;text-align: center;height: 30px;"' : '';?>><?php echo round(($stockQty/$row['factor']), 1) ;?> <span class="tabOn-Stk">On stock</span></p>
                                        </div>
                                        <div class="prdtCnt-Scnd d-lg-flex align-items-center">
                                            <div class="itm-Quantity tb-bdy " data-text="<?php echo showOtherLangText('Qty'); ?>">
                                                <input type="text" class="form-control qty-itm"
                                                    name="qty[<?php echo $row['id'];?>]" autocomplete="off"
                                                    onChange="showTotal(this.value, '<?php echo $x;?>', '<?php echo $row['id'];?>')"
                                                    value="<?php echo $row['ordQty'];?>" size="5">
                                            </div>
                                            <div class="ttlCr-Type d-flex flex-wrap align-items-center">
                                                <div id="totalPrice<?php echo $x;?>" class="ttlDft-Crcy tb-bdy  text-start  fw-semibold" data-text="<?php echo showOtherLangText('Total'); ?>">
                                                    <p><?php showPrice($row['ordPrice'], $getDefCurDet['curCode']) ?>
                                                    </p>
                                                </div>
                                                <?php if($ordRow['ordCurId'] > 0){?>
                                                <div id="totalPriceOther<?php echo $x;?>" class="ttlOtr-Crcy tb-bdy  text-start  fw-semibold" data-text="<?php echo showOtherLangText('Total'); ?>">
                                                    <p><?php  echo showOtherCur( ($row['ordPrice']*$curAmtVal), $ordRow['ordCurId']);?></p>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="prdt-Hide">
                                            <div class="prdt-Note tb-bdy">
                                                <div class=" stock position-relative" data-text="<?php echo showOtherLangText('Bar code'); ?>" data-text-stock="<?php echo showOtherLangText('S.qty'); ?>"> 
                                                    <div class="mb-brCode" ></div>
                                                </div>
                                                <div class=" " data-text="<?php echo showOtherLangText('Note'); ?>"> 
                                                    <input type="text" class="form-control note-itm" autocomplete="off"
                                                        id="notes<?php echo $row['id'];?>"
                                                        onChange="getnotesVal('<?php echo $row['id'] ?>');"
                                                        name="notes[<?php echo $row['id'];?>]"
                                                        value="<?php echo $row['note'];?>">
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
                                <?php } ?>

                            </div>
                        </div>

                        <div>

                            <div class="container pt-5 topOrder ">
                                <div class="row g-3 g-md-4 align-items-end">
                                    <div class="col-md-5">
                                        <p class="fs-14 pb-3"><?php echo showOtherLangText('Add New Items'); ?></p>
                                        <div class="input-group srchBx" style="border-color: rgb(213, 214, 221);">
                                            <input onKeyUp="myFunction('search3', 'newOrdTask1', 2)" type="search"
                                                class="form-control" placeholder="<?php echo showOtherLangText('Search Item'); ?>" id="search3"
                                                name="search3" aria-label="Search">
                                            <div class="input-group-append">
                                                <button class="btn" type="button"
                                                    style="background-color: rgb(122, 137, 255);">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                    <div class="col-md-7">
                                        <!-- <form id="add-new-items" action="editOrderAddNewItems.php?orderId=<?php //echo $_GET['orderId'];?>" method="post" autocomplete="off">  -->
                                        <?php  
            $cond .= " AND p.id IN( SELECT ps.productId FROM tbl_productsuppliers ps WHERE ps.supplierId = '".$_SESSION['supplierIdOrd']."' AND ps.account_id = '".$_SESSION['accountId']."') ";

            if($cond != '')
            {
                if( !empty( $pidArr ) )
                {
                    $cond .= " AND p.id NOT IN(".implode(',', $pidArr).") ";
                }

                $sqlSet = " SELECT p.*, 
                IF(u.name!='',u.name,p.unitP) as purchaseUnit ,
                s.qty AS stockQty
                FROM tbl_products p
                LEFT JOIN tbl_stocks s ON(s.pId=p.id) AND s.account_id=p.account_Id
                LEFT JOIN tbl_units u ON(u.id=p.unitP) AND u.account_id = p.account_id 
                WHERE 1=1 ".$cond." AND p.status=1  AND p.account_id = '".$_SESSION['accountId']."' ORDER BY itemName ";
                $proresultSet = mysqli_query($con, $sqlSet);
        ?>
                                        <div class="btnBg text-center text-md-end">
                                            <a href="javascript:void(0);"
                                                class="btn btn-primary add-new-items"><?php echo showOtherLangText('Add New Items In Order'); ?></a>
                                        </div>
                                        <!-- </form>  -->
                                    </div>
                                </div>
                            </div>
                            <!--.//container -->
                            <form id="add-new-items"
                                action="editOrderAddNewItems.php?orderId=<?php echo $_GET['orderId'];?>" method="post"
                                autocomplete="off" class="container">


                                <?php 

                $cond .= " AND p.id IN( SELECT ps.productId FROM tbl_productsuppliers ps WHERE ps.supplierId = '".$_SESSION['supplierIdOrd']."' AND ps.account_id = '".$_SESSION['accountId']."') ";

                if($cond != '')
                {
                    if( !empty( $pidArr ) )
                    {
                        $cond .= " AND p.id NOT IN(".implode(',', $pidArr).") ";
                    }

                    $sqlSet = " SELECT p.*, 
                    IF(u.name!='',u.name,p.unitP) as purchaseUnit ,
                    s.qty AS stockQty
                    FROM tbl_products p
                    LEFT JOIN tbl_stocks s ON(s.pId=p.id) AND s.account_id=p.account_Id
                    LEFT JOIN tbl_units u ON(u.id=p.unitP) AND u.account_id = p.account_id 
                    WHERE 1=1 ".$cond." AND p.status=1  AND p.account_id = '".$_SESSION['accountId']."' ORDER BY itemName ";
                    $proresultSet = mysqli_query($con, $sqlSet);
                 ?>
                                <div class="container nordPrice position-relative">
                                    <!-- Item Table Head Start -->
                                    <div class="d-flex align-items-center itmTable">
                                        <div class="prdtImg tb-head" style="width: 44px;">
                                            <p><?php echo mysqli_num_rows($proresultSet) > 0 ? mysqli_num_rows($proresultSet) : ''; ?>
                                            </p>
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
                                                <div class="crncy-Type w-50 ">
                                                    <div class="tb-head">
                                                        <p><?php echo showOtherLangText('P.Price'); ?></p>
                                                    </div>

                                                </div>
                                                <div class="itm-Unit tb-head">
                                                    <p><?php echo showOtherLangText('P.Unit'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdtStk-Qty tb-head">
                                            <p><?php echo showOtherLangText('S.Qty'); ?></p>
                                        </div>
                                        <div class="prdtCnt-Scnd d-lg-flex align-items-center">
                                            <div class="itm-Quantity tb-head">
                                                <p><?php echo showOtherLangText('Qty'); ?></p>
                                            </div>
                                            <?php if($ordRow['ordCurId'] > 0)
     {?>
                                            <div class="ttlCr-Type w-50">
                                                <div class="ps-xl-4 tb-head">
                                                    <p><?php echo showOtherLangText('Total'); ?>(<?php echo $getDefCurDet['curCode'] ?>)
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="ttlCr-Type w-50">
                                                <div class="ps-xl-4 tb-head">
                                                    <p><?php echo showOtherLangText('Total'); ?>(<?php echo $curDet['curCode'];?>)
                                                    </p>
                                                </div>
                                            </div>
                                            <?php }else{ ?>
                                            <div class="ttlCr-Type w-50">
                                                <div class="ps-xl-5 tb-head">
                                                    <p><?php echo showOtherLangText('Total'); ?>(<?php echo $getDefCurDet['curCode'] ?>)
                                                    </p>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="prdt-Hide">
                                            <div class="prdt-Note tb-head">
                                                <div class="mb-brCode" style="display: none;"></div>
                                                <p><?php echo showOtherLangText('Note'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Item Table Head End -->
                                </div>

                                <div id="boxscroll" class="compact__tb__bdy">
                                    <div class="container cntTable  ">
                                        <!-- Item Table Body Start -->
                                        <?php 

$x= $x+1;
$i = 0;
$y = 0;
$productsConfirmedQtyArr = getConfirmTotalQtyReq($_SESSION['accountId']);
while($row = mysqli_fetch_array($proresultSet))
{
$x++;
$y++;
$i=1;

$stockQty = $row['stockQty'];
$totalProQty = isset($productsConfirmedQtyArr[$row['id']]) ? $productsConfirmedQtyArr[$row['id']] : 0;
$stockQty = $stockQty - $totalProQty;
?>
                                        <input type="hidden" name="productIds[]" value="<?php echo $row['id'];?>" />
                                        <input type="hidden" name="suppliersIds[<?php echo $row['id'];?>]"
                                            value="<?php echo $row['supplierId'];?>" />
                                        <input type="hidden" name="factor[<?php echo $row['id'];?>]"
                                            id="factor<?php echo $x;?>" value="<?php echo $row['factor'];?>" />
                                        <input type="hidden" name="totalPriceShowTop[]"
                                            id="totalPriceShowTop<?php echo $x;?>" value="" />
                                        <div class="newOrdTask1">
                                            <div class="d-flex align-items-lg-center border-bottom itmBody newOrd-CntPrt">
                                                <div class="tb-bdy" style="width:44px;">
                                                    <?php echo $y;?>
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
                                                    <div class="Itm-Name tb-bdy fw-medium " data-text="<?php echo showOtherLangText('Item'); ?>">
                                                        <p><?php echo $row['itemName'];?></p>
                                                    </div>
                                                    <div class="Itm-brCode tb-bdy">
                                                        <p class="ord-brCode"><?php echo $row['barCode'];?></p>
                                                    </div>
                                                    <div class="prdtCr-Unit d-flex">
                                                        <div class="crncy-Type w-50 ">
                                                            <div class=" tb-bdy " data-text="<?php echo showOtherLangText('P.Price'); ?>">
                                                                <p><?php showPrice($row['price']*$row['factor'], $getDefCurDet['curCode']);?><input
                                                                        type="hidden"
                                                                        name="price[<?php echo $row['id'];?>]"
                                                                        id="<?php echo $x;?>"
                                                                        value="<?php echo getPrice($row['price']);?>" />


                                                                    <input type="hidden"
                                                                        name="curAmt[<?php echo $row['id'];?>]"
                                                                        value="<?php echo getPrice($row['curAmt']);?>" />
                                                                </p>
                                                            </div>

                                                        </div>
                                                        <div class="itm-Unit tb-bdy " data-text="<?php echo showOtherLangText('P.Unit'); ?>">
                                                            <p><?php echo $row['purchaseUnit'];?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="prdtStk-Qty tb-bdy">
                                                    <p class="ord-StockQty" <?php echo ( ($row['minLevel'] == 0 && $stockQty < $row['minLevel']) || (round($stockQty/$row['factor']) < round($row['minLevel']/$row['factor']))  ) ? 'style="display: flex;flex-direction: column;justify-content: center;align-items: center;background-color: pink;width: 43px;text-align: center;: 30px;"' : '';?>>
                                                        <?php echo round(($stockQty/$row['factor']), 1);?> <span
                                                            class="tabOn-Stk">On stock</span></p>
                                                </div>
                                                <div class="prdtCnt-Scnd d-lg-flex align-items-center">
                                                    <div class="itm-Quantity tb-bdy " data-text="<?php echo showOtherLangText('Qty'); ?>">
                                                        <input type="text" class="form-control qty-itm editQty-Rec"
                                                            name="qty[<?php echo $row['id'];?>]" autocomplete="off"
                                                            onChange="showTotal(this.value, '<?php echo $x;?>', '<?php echo $row['id'];?>', '<?php echo $_POST['supplierId'];?>', '1')"
                                                            value="" size="5">
                                                    </div>
                                                    <div class="ttlCr-Type w-50 ps-xl-4">
                                                        <div class=" tb-bdy  text-start" data-text="<?php echo showOtherLangText('Total'); ?>">
                                                            <p id="totalPrice<?php echo $x;?>">0</p>
                                                        </div>

                                                    </div>
                                                    <?php if($ordRow['ordCurId'] > 0){?>
                                                    <div class="ttlCr-Type w-50 ps-xl-4">
                                                        <div class=" tb-bdy">
                                                            <p id="totalPriceOther<?php echo $x;?>">0</p>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="prdt-Hide">
                                                    <div class="prdt-Note tb-bdy " data-text="<?php echo showOtherLangText('Note'); ?>">
                                                        <div class="mb-brCode" style="display: none;"></div>

                                                        <input type="text" class="form-control  note-itm"
                                                            autocomplete="off" id="notes<?php echo $row['id'];?>"
                                                            onChange="getnotesVal('<?php echo $row['id'] ?>');"
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

                                        <?php 
}
if($i > 0)
{
?>

                                        <?php }else{
echo '<div class="newOrdTask">
    <div class="d-flex align-items-lg-center border-bottom itmBody newOrd-CntPrt p-3 justify-content-center">'.showOtherLangText('No Product Found').'</div> </div>';
} ?>

                                    </div>
                                </div>
                                <?php }} ?>

                                <div class="container pb-4 topOrder">
                                    <div class="btnBg my-3">
                                        <a href="#"
                                            class="btn btn-primary add-new-items"><?php echo showOtherLangText('Add New Items In Order'); ?></a>

                                    </div>
                            </form>
                        </div>
                </div>

                </section>


            </div>
        </div>
        </div>
    </main>
    <div id="dialog" style="display: none;">
        <?php echo showOtherLangText('Are you sure to delete this record?') ?>
    </div>
    <form id="currency_frm_id" name="currency_frm_id" method="post" action="">
        <input type="text" id="currencyId" name="currencyId" value="">
        <input type="hidden" id="donotsubmit" name="donotsubmit" value="true">
    </form>
    <form action="" name="addServiceFeeFrm" class="addUser-Form row container glbFrm-Cont" id="addServiceFeeFrm"
        method="post" autocomplete="off">
        <div class="modal" tabindex="-1" id="new-service-item" aria-labelledby="add-CategoryLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h1 class="modal-title h1"><?php echo showOtherLangText('Service Name'); ?></h1>
                    </div>
                    <div class="modal-body">
                        <input type="text" required class="form-control" id="itemName" name="itemName"
                            placeholder="<?php echo showOtherLangText('Service Name');?>" autocomplete="off"
                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                            onChange="this.setCustomValidity('')" required>
                        <input type="number" required class="form-control" id="feeAmt" name="itemFeeAmt"
                            placeholder="<?php echo showOtherLangText('Amount').' '.$getDefCurDet['curCode']; ?>"
                            autocomplete="off"
                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                            onChange="this.setCustomValidity('')" required>
                        <input type="text" required class="form-control" id="unit" name="unit"
                            placeholder="<?php echo showOtherLangText('Unit'); ?>" autocomplete="off"
                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                            onChange="this.setCustomValidity('')" required>
                    </div>
                    <div>
                        <div class="feeSave">
                            <input type="checkbox" id="visibility" name="visibility" value="1">
                            <span class="subTittle1" style="vertical-align:text-top;">
                                <?php echo showOtherLangText('save to fixed service item list'); ?></span><br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btnBg">
                            <button type="submit" id="addFee" name="addFee"
                                class="sub-btn btn btn-primary std-btn"><?php echo showOtherLangText('Add'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form action="" name="addNewFee" class="addUser-Form row container glbFrm-Cont" id="addNewFee" method="post"
        autocomplete="off">
        <div class="modal" tabindex="-1" id="new-fees-item" aria-labelledby="add-CategoryLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h1 class="modal-title h1"><?php echo showOtherLangText('Add Fee'); ?></h1>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="currencyPopupForm" value="<?php echo $_SESSION['currencyId'] ?>">
                        <input type="text" class="form-control" name="feeName" id="feeName" value="" autocomplete="off"
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
                        <input type="text" class="form-control" id="amt" name="amt" value="" autocomplete="off"
                            placeholder="<?php echo showOtherLangText('Fee Amount').' '.$getDefCurDet['curCode']; ?>"
                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                            onChange="this.setCustomValidity('')" required />

                    </div>
                    <div>
                        <input type="checkbox" name="feeType" id="feeType" value="1">
                        <span class="subTittle1"
                            style="vertical-align:text-top;"><?php echo showOtherLangText('Tax fee'); ?></span>
                        <div class="feeSave">
                            <input type="checkbox" id="visibility" name="visibility" value="1">
                            <span class="subTittle1" style="vertical-align:text-top;"> <?php echo showOtherLangText('save to fixed service item
list'); ?></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btnBg">
                            <button type="submit" id="feesave_add" name="feesave_add"
                                class="sub-btn btn btn-primary std-btn"><?php echo showOtherLangText('Add'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php require_once('footer.php');?>
    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script type="text/javascript">
    function showTotal(qty, priceId, pId, supplierId = '<?php echo $_SESSION['supplierIdOrd'] ?>',
        editOrdNewItemStatusVal = 0) {

        if (isNaN(qty) || qty == '') {
            qty = 0;
        }

        if (isNaN(supplierId) || supplierId == '') {
            supplierId = 0;
        } else {
            supplierId = '<?php echo $_SESSION['supplierIdOrd'] ?>';
        }

        $.ajax({
                method: "POST",
                url: "editOrderAjax.php",
                dataType: 'json',
                data: {
                    orderId: '<?php echo $_GET['orderId'];?>',
                    supplierId: supplierId,
                    pId: pId,
                    editOrdNewItemStatus: editOrdNewItemStatusVal,
                    qty: qty,
                    curAmtVal: '<?php echo $curDet['amt']; ?>',
                    currencyId: '<?php echo $curDet['id']; ?>'

                }
            })
            .done(function(responseObj) {
                $('#totalOrdArea').html(responseObj.resHtml);

                $('#totalPrice' + priceId).html(responseObj.productPrice);
                $('#totalPriceOther' + priceId).html(responseObj.totalPriceOther);
            });

    } //end  
    </script>
    <script>
    function getDelNumb(delId, orderId, supplierId) {
        var newOnClick = "window.location.href='editOrder.php?delId=" + delId + "&orderId=" + orderId + "&supplierId=" +
            supplierId + "'";

        //console.log('click',newOnClick);
        //return false;

        $('.deletelink').attr('onclick', newOnClick);
        $('#delete-popup').modal('show');

    }
    // function getDelNumb(delId, orderId, supplierId) {

    //     $("#dialog").dialog({
    //         autoOpen: false,
    //         modal: true,
    //         //title     : "Title",
    //         buttons: {
    //             '<?php echo showOtherLangText('Yes') ?>': function() {
    //                 //Do whatever you want to do when Yes clicked
    //                 $(this).dialog('close');
    //                 window.location.href = 'editOrder.php?delId=' + delId + '&orderId=' + orderId +
    //                     '&supplierId=' + supplierId;
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

    $(".currency_dropdown").on("click", "a", function(e) {
        var $this = $(this).parent();
        $("#add_currency").text($this.data("value"));
        $("#currencyId").val($this.data("id"));
        //console.log('ss',$this.data("id"));
        $('#currency_frm_id').submit();
    });

    $(document).ready(function() {
        if ($(".currency_dropdown .selected").length > 0) {
            $("#add_currency").text($(".currency_dropdown .selected").text());
        }
    });

    function myFunction(searchId, tableId, searchBoxNo) {
        var input = document.getElementById(searchId);
        var filter = input.value.toLowerCase();
        if (searchBoxNo == 1) {
            console.log('searchId', searchBoxNo);
            var nodes = document.querySelectorAll('.newOrdTask');
        } else {
            console.log('searchId', searchBoxNo);
            var nodes = document.querySelectorAll('.newOrdTask1');
        }

        for (i = 0; i < nodes.length; i++) {
            if (nodes[i].innerText.toLowerCase().includes(filter)) {
                nodes[i].style.setProperty("display", "block", "important");
            } else {
                nodes[i].style.setProperty("display", "none", "important");
            }
        }

    }

    $("#search2").on("search", function(evt) {
        if ($(this).val().length == 0) {
            resetData();
        }
    });

    $("#search3").on("search", function(evt) {
        if ($(this).val().length == 0) {
            resetData1();
        }
    });

    function resetData() {

        $('#search2').val('');
        myFunction('search2', 'totalOrdArea', 1);
    }

    function resetData1() {

        $('#search3').val('');
        myFunction('search3', 'totalOrdArea1', 2);
    }

    $(document).ready(function() {

        $('#typeOfFee').change(function() {
            var getValue = $('#typeOfFee').val();

            if (getValue == '2') {

                $('#amt').attr("placeholder", "Fee amount $");

            } else {
                $('#amt').attr("placeholder", "Fee percentage %");
            }

        });
        $('.orderupdate').click(function() {
            $('#frmupdateOrder').submit();
        });
        $('.add-new-items').click(function() {
            $('#add-new-items').submit();
        });
    });

    function getItemNotesVal(indexVal, itemNotesId) {

        var itemNotes = $('#itemNotes' + indexVal).val();

        $.ajax({
                method: "POST",
                url: "editOrderAjax.php",

                data: {
                    itemNotes: itemNotes,
                    itemNotesId: itemNotesId,
                    orderId: '<?php echo $_GET['orderId'] ?>'
                }
            })
            .done(function(responseObj) {


            });


    }

    function getnotesVal(pId) {

        var notes = $('#notes' + pId).val();

        $.ajax({
                method: "POST",
                url: "editOrderAjax.php",

                data: {
                    notes: notes,
                    pId: pId,
                    orderId: '<?php echo $_GET['orderId'] ?>'
                }
            })
            .done(function(responseObj) {


            });


    } //end 
    </script>
    <div class="modal" tabindex="-1" id="delete-popup" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php echo showOtherLangText('Are you sure to delete this record?') ?>
                    </h1>
                </div>

                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="button" data-bs-dismiss="modal"
                            class="btn btn-primary std-btn"><?php echo showOtherLangText('No'); ?></button>
                    </div>
                    <div class="btnBg">
                        <button type="button" onclick=""
                            class="deletelink btn btn-primary std-btn"><?php echo showOtherLangText('Yes'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
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