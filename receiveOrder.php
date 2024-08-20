<?php
include('inc/dbConfig.php'); //connection details

//for excel file upload with Other language
use Shuchkin\SimpleXLSX;
require_once 'SimpleXLSX.php';
$tbl_order_main_or_temp = 'tbl_order_details';


//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


$rightSideLanguage = ($getLangType == 1) ? 1 : 0; 


$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'receive_order' AND type_id = '0' AND designation_id = '".$_SESSION['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if ($permissionRow)
{
    echo "<script>window.location='index.php'</script>";
}

//update invoice number when user change it
if(isset($_POST['invoiceNumber']) && isset($_POST['orderId'])) 
{
    $sqlSet = " SELECT * FROM tbl_orders where id = '".$_POST['orderId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
    $ordQry = mysqli_query($con, $sqlSet);
    $ordResult = mysqli_fetch_array($ordQry);
    if($ordResult['status'] != 2)
    {
        $updateQry = " UPDATE `tbl_orders` SET
        `invNo` = '".$_POST['invoiceNumber']."' 
        WHERE id = '".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
        mysqli_query($con, $updateQry);
    }
    

}//end


$sqlSet = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
$resultSet = mysqli_query($con, $sqlSet);
$ordRow = mysqli_fetch_array($resultSet);

// get currency detail by order
$curDetData = getCurrencyDet($ordRow['ordCurId']);

$fileDataRows = [];
if( isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != '' )
{
    $xlsx = SimpleXLSX::parse($_FILES["uploadFile"]["tmp_name"]);


    $i=0;
    foreach($xlsx->rows() as $row)
    {
        if($i == 0)
        {
            $i++;
            continue;
        }           

        $rows[] = [
        'supplierId' => $row[0],
        'barCode' => $row[1],
        'qty' => $row[2],
        'price' => $row[3]
        ];
    }

//----------------------------------


    if( is_array($rows) && !empty($rows) )
    {
        $tbl_order_main_or_temp = 'tbl_order_details_temp_receive';

        $sqlSet = " DELETE FROM tbl_order_details_temp_receive WHERE ordId = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
        mysqli_query($con, $sqlSet);

         $sqlSet = " INSERT INTO tbl_order_details_temp_receive( `account_id`, `ordId`, `pId`, `factor`, `price`, `qty`, `qtyReceived`, `totalAmt`, `note`, `lastPrice`, `stockPrice`, `stockQty`, `curPrice`, `currencyId`, `curAmt`, `customChargeId`, `customChargeType`, `requestedQty`)
        SELECT  `account_id`, `ordId`, `pId`, `factor`, `price`, `qty`, `qtyReceived`, `totalAmt`, `note`, `lastPrice`, `stockPrice`, `stockQty`, `curPrice`, `currencyId`, `curAmt`, `customChargeId`, `customChargeType`, `requestedQty` FROM tbl_order_details
         WHERE ordId = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
        mysqli_query($con, $sqlSet);


        

        foreach($rows as $row)
        {
            
            $fileDataRows[$row['barCode']]['qty'] = trim($row['qty']);
            $fileDataRows[$row['barCode']]['price'] = isset($row['price']) ? trim($row['price']) : 0;
            $fileDataRows[$row['barCode']]['supplierId'] = trim($row['supplierId']);
        }



        //update excel data
                        $sql = "SELECT tp.*, od.price ordPrice, od.curPrice ordCurPrice, od.ordId, od.currencyId,  od.curPrice curPrice, od.curAmt curAmt, od.qty ordQty, od.totalAmt, od.factor ordFactor, IF(u.name!='',u.name,tp.unitP) purchaseUnit
                         FROM ".$tbl_order_main_or_temp." od 

                        INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id

                        LEFT JOIN tbl_units u ON(u.id = tp.unitP) AND u.account_id = tp.account_id

                        WHERE od.ordId = '".$_GET['orderId']."' AND tp.account_id = '".$_SESSION['accountId']."'   ";

                        $ordQry = mysqli_query($con, $sql);
                                                                                            
                        while($row = mysqli_fetch_array($ordQry))
                        { 
                            if( isset($fileDataRows[$row['barCode']]) )
                            { 

                                $receivedRow = $fileDataRows[$row['barCode']];
                                $qtyVal = $receivedRow['qty'];
                                $ordQty = $receivedRow['qty'];
                                $boxPrice =$receivedRow['price'] > 0 ? $receivedRow['price'] : $row['ordFactor']*$row['ordPrice'];

                                $boxPriceOther = ($boxPrice*$curDetData['amt']);

                                if ($row['currencyId'] > 0) {

                                    $curPrice = (($boxPrice/$row['ordFactor'])*$curDetData['amt']);
                                    $curAmt = ($curPrice*$row['ordFactor']*$ordQty);
                                }

                                $upQry = " UPDATE ".$tbl_order_main_or_temp." SET 
                                `qtyReceived` = '".$qtyVal."', 
                                `price` = '".($boxPrice/$row['ordFactor'])."', 
                                `curPrice` = '".$curPrice."', 
                                `totalAmt` = '".($boxPrice*$ordQty)."',
                                `curAmt` = '".$curAmt."' 
                                WHERE ordId = '".$row['ordId']."' AND pId = '".$row['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";
                                mysqli_query($con, $upQry);

                            }

                        }  //update excel data
        
    }
}
elseif( isset($_POST['updateReceiving']) )
{
    $sqlSet = " SELECT * FROM tbl_orders where id = '".$_POST['orderId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
    $ordQry = mysqli_query($con, $sqlSet);
    $ordResult = mysqli_fetch_array($ordQry);
    if($ordResult['status'] == 2)
    {
        echo "<script>window.location='receiveOrder.php?orderId=".$_POST['orderId']."&error=alreadyreceived'</script>";die();
    }

    foreach($_POST['productIds'] as $productId)//update existing products
    {
        $price = $_POST['price'][$productId]/$_POST['factor'][$productId];

        $priceOther = str_replace(",","",$_POST['priceOther'][$productId]);

        $priceOther = $ordRow['ordCurId'] > 0 ? ($priceOther/$_POST['factor'][$productId]) : 0;

        $upQry = " UPDATE  `tbl_order_details` SET
        `qtyReceived` = '".$_POST['qty'][$productId]."', 
        `price` = '".$price."', 
        `curPrice` = '".($priceOther)."', 
        `totalAmt` = '".($_POST['factor'][$productId]*$price*$_POST['qty'][$productId])."',
        `curAmt` = '".($_POST['factor'][$productId]*$priceOther*$_POST['qty'][$productId])."' 
        WHERE ordId = '".$_GET['orderId']."' AND pId = '".$productId."'  AND account_id = '".$_SESSION['accountId']."'  ";
        mysqli_query($con, $upQry);
        

        $sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$productId."'  AND account_id = '".$_SESSION['accountId']."'  ";
        $stkQry = mysqli_query($con, $sql);
        $stkRow = mysqli_fetch_array($stkQry);
        if($stkRow)
        {
            $upQry = " UPDATE  `tbl_stocks` SET
            `qty` = (qty + ".($_POST['factor'][$productId]*$_POST['qty'][$productId])."),
            `lastPrice` = '".$price."',
            `stockValue` = ( stockValue + ".($_POST['factor'][$productId]*$price*$_POST['qty'][$productId])." )
            WHERE id = '".$stkRow['id']."' AND account_id = '".$_SESSION['accountId']."' ";
            mysqli_query($con, $upQry);


            $upQry = " UPDATE  `tbl_stocks` SET
            `stockPrice` = (stockValue/qty) 
            WHERE id = '".$stkRow['id']."' AND account_id = '".$_SESSION['accountId']."'    ";
            mysqli_query($con, $upQry);
        }   
        else
        {
            $upQry = " INSERT INTO  `tbl_stocks` SET
            `pId` = '".$productId."', 
            `qty` = ".($_POST['factor'][$productId]*$_POST['qty'][$productId]).",
            `lastPrice` = '".$price."',
            `stockValue` = '".($_POST['factor'][$productId]*$_POST['qty'][$productId]*$price)."',
            `stockPrice` = '".$price."',
            `account_id` = '".$_SESSION['accountId']."'  
            ";
            mysqli_query($con, $upQry);
        }       

        $sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$productId."' AND account_id = '".$_SESSION['accountId']."'   ";
        $stkQry = mysqli_query($con, $sql);
        $stkRow = mysqli_fetch_array($stkQry);

        //update last price
        $upQry = " UPDATE  `tbl_products` SET
        `price` = '".$stkRow['lastPrice']."',
        `stockPrice` = '".$stkRow['stockPrice']."'
        WHERE id = '".$productId."'  AND account_id = '".$_SESSION['accountId']."'   ";
        mysqli_query($con, $upQry); 


        $upQry = " UPDATE  `tbl_order_details` SET
        `lastPrice` = '".$stkRow['lastPrice']."', 
        `stockPrice` = '".$stkRow['stockPrice']."',
        `stockQty` = '".$stkRow['qty']."'

        WHERE ordId = '".$_GET['orderId']."' AND pId = '".$productId."'  AND account_id = '".$_SESSION['accountId']."'  ";
        mysqli_query($con, $upQry);


} // end of foreach loop


if( isset($_POST['barCode']) && !empty($_POST['barCode']) )
{


    $i=0;
    foreach($_POST['barCode'] as $barCode)//update existing products
    {
        $sqlSet = " SELECT * FROM tbl_products WHERE barCode = '".$barCode."' AND account_id = '".$_SESSION['accountId']."'   and id not in(".implode(',', $_POST['productIds']).")  ";

        $resultSet = mysqli_query($con, $sqlSet);
        $productRes = mysqli_fetch_array($resultSet);
        if($productRes)
        {

            $Qry = " INSERT INTO  `tbl_order_details` SET
            `ordId` = '".$_GET['orderId']."',
            `pId` = '".$productRes['id']."', 
            `price` = '".($_POST['qtyReceivedPrice'][$i]/$productRes['factor'])."', 
            `factor` = '".$productRes['factor']."', 
            `qty` = 0,
            `note` = '',
            `qtyReceived` = '".$_POST['qtyReceived'][$i]."', 
            `totalAmt` = '".($_POST['qtyReceivedPrice'][$i]*$_POST['qtyReceived'][$i])."',
            `account_id` = '".$_SESSION['accountId']."'   
            ";
            mysqli_query($con, $Qry);

            $sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$productRes['id']."' AND account_id = '".$_SESSION['accountId']."'   ";
            $stkQry = mysqli_query($con, $sql);
            $stkRow = mysqli_fetch_array($stkQry);
            if($stkRow)
            {
                $upQry = " UPDATE  `tbl_stocks` SET
                `qty` = (qty + ".($productRes['factor']*$_POST['qtyReceived'][$i])."),
                `lastPrice` = '".($_POST['qtyReceivedPrice'][$i]/$productRes['factor'])."',
                `stockValue` = ( stockValue + ".($_POST['qtyReceivedPrice'][$i]*$_POST['qtyReceived'][$i])." )
                WHERE id = '".$stkRow['id']."'  AND account_id = '".$_SESSION['accountId']."'   ";
                mysqli_query($con, $upQry);

                $upQry = " UPDATE  `tbl_stocks` SET
                `stockPrice` = (stockValue/qty) 
                WHERE id = '".$stkRow['id']."'  AND account_id = '".$_SESSION['accountId']."'   ";
                mysqli_query($con, $upQry);
            }   
            else
            {
                $upQry = " INSERT INTO  `tbl_stocks` SET
                `pId` = '".$productRes['id']."', 
                `qty` = '".($productRes['factor']*$_POST['qtyReceived'][$i])."',
                `lastPrice` = '".($_POST['qtyReceivedPrice'][$i]/$productRes['factor'])."',
                `stockPrice` = '".($_POST['qtyReceivedPrice'][$i]/$productRes['factor'])."',
                `stockValue` = '".($_POST['qtyReceivedPrice'][$i]*$_POST['qtyReceived'][$i])."',
                `account_id` = '".$_SESSION['accountId']."'  

                ";
                mysqli_query($con, $upQry);
            }       

            $sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$productRes['id']."' AND account_id = '".$_SESSION['accountId']."'   ";
            $stkQry = mysqli_query($con, $sql);
            $stkRow = mysqli_fetch_array($stkQry);

                            //update last price and stock
            $upQry = " UPDATE  `tbl_products` SET
            `price` = '".$stkRow['lastPrice']."',
            `stockPrice` = '".$stkRow['stockPrice']."'
            WHERE id = '".$productRes['id']."' AND account_id = '".$_SESSION['accountId']."'    ";
            mysqli_query($con, $upQry);
                                            //update last price         

            $upQry = " UPDATE  `tbl_order_details` SET
            `lastPrice` = '".$stkRow['lastPrice']."', 
            `stockPrice` = '".$stkRow['stockPrice']."',
            `stockQty` = '".$stkRow['qty']."'

            WHERE ordId = '".$_GET['orderId']."' AND pId = '".$productRes['id']."' AND account_id = '".$_SESSION['accountId']."'   ";
            mysqli_query($con, $upQry);

            $i++;

        } // end if condition   

    }//end for each barcode

} // end if barcode condition   
    
    //update net value in orders tbl
    $id=$_SESSION['id'];
    $invNo =$_POST['invNo'];
    $orderId=$_GET['orderId'];
    receiveOrdTotal($id,$invNo,$orderId);
    
    // $sql=" DELETE  FROM  tbl_mobile_items_temp WHERE stockTakeId = '".$_GET['orderId']."' AND stockTakeType=3  AND status=1  AND account_id = '".$_SESSION['accountId']."'    ";
    // mysqli_query($con, $sql);
    
    // $sql=" DELETE  FROM  tbl_mobile_time_track WHERE stockTakeId = '".$_GET['orderId']."' AND stockTakeType=3  AND status=1  AND account_id = '".$_SESSION['accountId']."'    ";
    // mysqli_query($con, $sql);
    
    // $sql = " DELETE FROM `tbl_order_assigned_users` where  orderId = '".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."'   ";
    // mysqli_query($con, $sql);

    $sql = " SELECT SUM(totalAmt) AS totalAmt FROM 
    tbl_order_details WHERE ordId = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $resRow = mysqli_fetch_array($res);

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $ordRow = mysqli_fetch_array($res);

    $sql = " SELECT * FROM tbl_order_journey WHERE orderId = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' order by id desc limit 1 ";
    $res = mysqli_query($con, $sql);
    $ordJournDet = mysqli_fetch_array($res);

    if( round($ordJournDet['amount']) != round($ordRow['ordAmt']) )
    {
        $diffPrice = ($ordRow['ordAmt'] - $ordResult['ordAmt']);
        $notes = 'Order Received(Price Diff: '.getPriceWithCur($diffPrice, $getDefCurDet['curCode']).' )';
    }
    else
    {
        $notes = '';
    }

    
    
    $qry = " INSERT INTO `tbl_order_journey` SET 
    `account_id` = '".$_SESSION['accountId']."',
    `orderId` = '".$_GET['orderId']."',
    `userBy`  = '".$_SESSION['id']."',
    `ordDateTime` = '".date('Y-m-d h:i:s')."',
    `amount` = '".$ordRow['ordAmt']."',
    `otherCur` = '".$ordRow['ordCurAmt']."',
    `otherCurId` = '".$ordRow['ordCurId']."',
    `invoiceNo` = '".$ordResult['invNo']."',
    `orderType` = '".$ordResult['ordType']."',
    `notes` = '".$notes."',
    `action` = 'Receive' ";
    mysqli_query($con, $qry);

    echo "<script>window.location = 'history.php?updated=1'</script>";

} // end of elseif condition


$sql = "SELECT cif.itemName, cif.unit, tod.* FROM ".$tbl_order_main_or_temp." tod 
INNER JOIN tbl_custom_items_fee cif ON(tod.customChargeId = cif.id) AND tod.account_id = cif.account_id
WHERE tod.ordId = '".$_GET['orderId']."' AND tod.account_id = '".$_SESSION['accountId']."'   and tod.customChargeType=1 ORDER BY cif.itemName ";
$otherChrgQry=mysqli_query($con, $sql); 


    $sql = "SELECT tp.*, cm.amt curAmtMaster, od.price ordPrice, od.curPrice ordCurPrice, od.ordId, od.currencyId,  od.curPrice curPrice, od.curAmt curAmt, od.qty ordQty, od.totalAmt, od.factor ordFactor, IF(u.name!='',u.name,tp.unitP) purchaseUnit FROM tbl_order_details od 
    INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
    LEFT JOIN tbl_units u ON(u.id = tp.unitP) AND u.account_id = tp.account_id
    LEFT JOIN tbl_currency cm ON(cm.id = od.currencyId) AND cm.account_id = od.account_id
    
    WHERE od.ordId = '".$_GET['orderId']."' AND tp.account_id = '".$_SESSION['accountId']."'   ";
    $orderQry = mysqli_query($con, $sql);
    
?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Receive Order - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style_new.css">
    <link rel="stylesheet" href="Assets/css/style1.css">
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
                                <h1 class="h1"><?php echo showOtherLangText('Receive Order'); ?></h1>
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
                                        <h1 class="h1"><?php echo showOtherLangText('Receive Order'); ?></h1>
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

                    <section class="ordDetail">
                        <div class="tpBar-grn"></div>
                        <div class="container nordPrice mt-0">
                            <?php if(isset($_GET['tempDataCleared']) || isset($success_file_upload) || isset($_GET['edit']) || isset($_GET['delete']) || isset($_GET['errorProduct']) || isset($_GET['mes']) ) {?>
                                <div class="alert alert-success alert-dismissible fade show mt-3 mb-0" role="alert">
                                    <p>
                                    <?php 
                                    echo isset($success_file_upload) ? $success_file_upload : '';
                                    echo isset($_GET['errorProduct']) ? ' '.'Select atleast one product to make requisition successfully.'.' ' : '';
                                    echo isset($_GET['tempDataCleared']) ? ' '.'Temp data has been cleared.'.' ' : '';
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
                            <?php if(isset($_GET['error']) || (isset($error_file_upload) && $error_file_upload !='' )) { ?>
                            <div class="alert alert-danger alert-dismissible fade show mt-3 mb-0" role="alert">
                                <p><?php  if($_GET['error'] == 'alreadyreceived')
                                {
                                    echo ' '.showOtherLangText('This order has been already received').' ';
                                }
                                if($error_file_upload)
                                {
                                    echo $error_file_upload;
                                }
                            ?></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php } ?>

                        </div><!--.//container-->
                        <div class="stcPart position-relative">
                            <form action="receiveOrder.php?orderId=<?php echo $_GET['orderId'];?>" method="post" name="upload_form" id="upload_form" enctype="multipart/form-data">
                            <div class="container nwOrder-Div rcvOrder">
                                <div class="row g-3">
                                    <div class="sltSupp nwOrd-Num">
                                        <div class="ord-Box update position start-0 ms-0" style="top:1rem;">
                                            <div class="ordNum m-0">
                                                <h4 class="subTittle1"><span>Order#:</span> <span><?php echo $ordRow['ordNumber'];?></span>
                                                <span class="ps-3 ps-md-5 ms-lg-3"><?php echo date("d-m-Y"); ?></span>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ordInfo rcvInfo newFeatures">
                                        
                                        <div class="container">
                                            <div class="mbFeature">
                                                <div class="row gx-0 justify-content-end">
                                       
                                                    <div class="text-center w-100" style="max-width:fit-content;">
                                                          <style>
                                                            .featRow::after {
                                                                display:none;
                                                            }
                                                            .featRow .stockFeat .dropdown-menu {
                                                                overflow: hidden;
                                                            }
                                                            .featRow .stockFeat .dropdown-menu a {
                                                                display: flex;
                                                                gap:.5rem;
                                                                padding: 5px 16px;
                                                                
                                                            }
                                                            .featRow .stockFeat .dropdown-menu a:hover {
                                                                background-color: #e9ecef;
                                                            }
                                                            .featRow .stockFeat .dropdown-menu a svg {
                                                                width: 18px;
                                                                height: 18px;
                                                            }
                                                            .featRow .stockFeat .dropdown-menu.show {
                                                                transform: translate(0, 70px) !important;
                                                            }
                                                            .stkRow .stockFeat:hover  .dropdown-menu a{
                                                               color: #666c85 !important;
                                                            }
                                                         
                                                          </style>
                                                        <div class="row featRow stkRow divider p-0 position-relative"> 
                                                            <div class="stockFeat p-0   d-flex">
                                                            <a  href="javascript:void(0)" class="dropdown-toggle tabFet p-lg-3" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <span class="edIt"></span>
                                                                <p class="btn2 d-flex justify-content-center align-items-center gap-1">
                                                                    <span><?php echo showOtherLangText('Import receiving file'); ?></span> <i class="fa-solid fa-angle-down fa-angle-up"></i>
                                                                </p>
                                                            </a>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                <a id="btnFileUpload" href="javascript:void(0)" class="tabFet nav_sub">
                                                                    <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 22"><path d="M14 6.5a1 1 0 1 0 2 0zm-10 15a1 1 0 1 0 0-2zm-2.4-1.6.7-.7zm13-15.5-.8.5zm-.5-.6-.5.9zm-12.8.6.9.5zm.6-.6.5.9zM2 16.5V7H0v9.5zm2 3H2.6l-.3-.3-1.4 1.4q.3.5 1.2.8h.8l1.1.1zm-4-3v2.3q.2 1 .9 1.8l1.4-1.4-.2-.6-.1-2.1zm16-10V5l-.5-1.2L13.8 5l.2.4v1.2zm-4.5-2H13q.5 0 .6.2l1-1.7-1.4-.5h-1.7zm4-.7-.8-.8-1.1 1.7.2.2zM2 7V5.5q0-.5.2-.6L.5 4 0 5.2V7zm2.5-4.5H2.8a3 3 0 0 0-1.5.5l1.1 1.7.6-.2h1.5zM2.2 4.9l.2-.2-1-1.7-.9.8z" fill="#8C8FA7"/><path d="M5 3.5q.2-1.8 2-2h2a2 2 0 1 1 0 4H7a2 2 0 0 1-2-2Z" stroke="#8C8FA7" stroke-width="2"/><path d="M5 10.5h4" stroke="#8C8FA7" stroke-width="2" stroke-linecap="round"/><path d="m12.9 19 4.9-5.5.4-.5.3-.5a2 2 0 0 0-.3-2l-.4-.4-.5-.5-.6-.5a2 2 0 0 0-1.5 0l-.7.5-.4.5-4.8 5.3-.5.6-.2.7-.4 1.8v.5q-.2.4.3 1 .7.5 1 .4l.5-.2 1.5-.3.8-.4z" stroke="#8C8FA7" stroke-width="2"/></svg>
                                                                    <p class="btn2"><?php echo showOtherLangText('Import Receiving File'); ?><input type="file" id="uploadFile" name="uploadFile" style="display:none"></p>
                                                                </a>
                                                                </li>
                                                                <li>
                                                                <a href="<?php echo $rightSideLanguage == 1 ? 'excelSampleFile/hebrew/receive-items-hebrew-lang.xlsx' : 'excelSampleFile/english/receive-items-english-lang.xlsx'; ?>" target="_blank" class="tabFet nav_sub">
                                                                    <!-- <span class="sampleFile"></span> -->
                                                                    <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 31"><path d="M24.8 7.7q2.4-.1 3.1.4l.7.7q.5.8.4 3.2v11.5q.2 3.3-.7 4.2t-4.2.7H17q-3.3.2-4.2-.7t-.7-4.2V12q-.1-2.4.4-3.2l.7-.7c.6-.4 1.4-.4 3.2-.4" stroke="#8C8FA7" stroke-width="2"/><path d="M16.9 7.7c0-1.3 1-2.4 2.4-2.4h2.4a2.4 2.4 0 0 1 0 4.9h-2.4Q17 10 16.9 7.7Z" stroke="#8C8FA7" stroke-width="2"/><path d="m5 19-.7.7.7.7.7-.7zM6 7a1 1 0 0 0-2 0zM.3 15.6l4 4 1.4-1.5-4-4zm5.4 4 4-4-1.4-1.5-4 4zm.3-.8V7H4v12zm8.9 4.2v-6.6h4.4v1.1h-3v1.6H19v1.1h-2.9v1.6h3.1v1.1zm9.9-4.5-.2-.4-.3-.3-.4-.2-.5-.1q-.5 0-.9.3t-.6.7l-.2 1.1q0 .7.2 1.2t.6.8l.9.2q.5 0 .8-.2.3 0 .5-.4l.2-.8h.3-1.7v-1h2.7v.8q0 .9-.3 1.5-.4.7-1 1l-1.5.3q-1 0-1.6-.4-.8-.3-1.1-1.1-.4-.7-.4-1.8 0-.8.2-1.5l.7-1q.4-.5 1-.7.4-.2 1.2-.2l1 .1.9.5a3 3 0 0 1 .9 1.6z" fill="#8C8FA7"/></svg>
                                                                    <p class="btn2"><?php echo showOtherLangText('Download Sample File'); ?></p>
                                                                </a>
                                                                </li>
                                                                <input type="file" id="uploadFile" name="uploadFile" style="display:none">
                                                            </ul>
                                                          </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 text-end smBtn nwNxt-Btn">
                                        <div class="btnBg">
                                            <a href="javascript:void(0)" class="btn sub-btn std-btn receive-btn"><?php echo showOtherLangText('Receive');?></a>
                                        </div>
                                        <div class="btnBg mt-3">
                                            <a href="runningOrders.php" class="btn sub-btn std-btn"><?php echo showOtherLangText('Back');?></a>
                                        </div>
                                        <div class="fetBtn">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/dashboard.svg" alt="dashboard">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                             <input type="hidden" name="updateReceiving" 
                                    value="<?php echo showOtherLangText('Receive And Issue In');?>" />
                            <input class="form-control" type="hidden" name="orderId"
                                value="<?php echo $_GET['orderId'] ?>">
                            <div class="container topOrder rcvOrder">
                               
                                <div class="row">
                                    <div class="suppDetl">
                                        <div class="recvInv p-0">
                                            <p><?php echo showOtherLangText('Supplier'); ?>: <span class="supName"><?php
                                    $sqlSet = " SELECT GROUP_CONCAT(DISTINCT(s.name)) suppliers FROM tbl_orders o 

                                    INNER JOIN tbl_suppliers s ON(o.supplierId = s.id) AND o.account_id = s.account_id

                                    WHERE o.id =".$_GET['orderId']." AND s.account_id = '".$_SESSION['accountId']."' ";

                                    $resultSet = mysqli_query($con, $sqlSet);
                                    $supRow = mysqli_fetch_array($resultSet);
                                    echo  $supRow['suppliers'];

                                    ?></span></p>
                                            <div class="d-flex gap-2 align-items-center">
                                                <p class="flex-fill flex-grow-0 flex-shrink-0"><?php echo showOtherLangText('Invoice No'); ?>: </p>
                                               <input class="form-control invNum" type="text" name="invNo" id="invNo"
                                                autocomplete="off" value="<?php echo $ordRow['invNo'] ?>"
                                                oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                onChange="getInvNo(),this.setCustomValidity('')" required />
                                                <div class="error" id="invError"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="totalOrdArea" class="ordInfo rcvInfo">
                                        <div class="container">
                                            <div class="prcTable">
                                                <?php 

                       //get the sum of all product and item level charges 
                        $sqlSet="SELECT *, SUM(totalAmt) AS sum1, SUM(curAmt) AS totalAmtOther FROM ".$tbl_order_main_or_temp." 

                        WHERE account_id = '".$_SESSION['accountId']."' AND ordId='".$_GET['orderId']."' AND (customChargeType='1' OR customChargeType='0')";

                        $resultSet = mysqli_query($con, $sqlSet);
                        $chargeRow = mysqli_fetch_array($resultSet);    
                        $chargePrice=$chargeRow['sum1'];
                        $chargePriceOther=$chargeRow['totalAmtOther'];

                        //to find order level charge
                        $ordCount="SELECT * from ".$tbl_order_main_or_temp." where ordId='".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."' AND customChargeType='2' ";
                        $ordCountResult = mysqli_query($con, $ordCount);
                        $ordCountRow = mysqli_num_rows($ordCountResult);

                        if ($ordCountRow > 0)
                            { ?>
                                                <div class="price justify-content-between">
                                                    <div class="p-2 delIcn text-center"></div>
                                                    <div class="p-2 txnmRow">
                                                        <p><?php echo showOtherLangText('Sub Total');?></p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p><?php echo showPrice($chargePrice,$getDefCurDet['curCode']); ?></p>
                                                        </div>
                                                        <?php 
                                    if($ordRow['ordCurId'] > 0)
                                        {?>
                                                        <div class="p-2 otherCurr">
                                                            <p><?php echo showOtherCur($chargePriceOther, $ordRow['ordCurId']);?></p>
                                                        </div>
                                        <?php } ?>
                                                    </div>
                                                </div>
                            <?php
                                }

                        //Starts order level fixed discount charges
                        $sql = "SELECT od.*, tp.feeName FROM ".$tbl_order_main_or_temp." od 
                        INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id

                        WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

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
                                                        <!-- <a href="javascript:void(0)">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </a> -->
                                                    </div>
                                                    <div class="p-2 txnmRow">
                                                        <p><?php echo $row['feeName'];?></p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p><?php echo showPrice($row['price'],$getDefCurDet['curCode']);?></p>
                                                        </div>
                                                        <?php if($ordRow['ordCurId'] > 0){?>
                                                        <div class="p-2 otherCurr">
                                                            <p><?php echo showOtherCur($row['curAmt'], $ordRow['ordCurId']);?></p>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                        <?php
                          } //Ends order lelvel fixed discount charges


                    //Starts order level per discount charges
                      $sql = "SELECT od.*, tp.feeName FROM ".$tbl_order_main_or_temp." od 
                      INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id

                      WHERE od.ordId = '".$_GET['orderId']."' AND od.account_id = '".$_SESSION['accountId']."'   and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
                      $ordQry = mysqli_query($con, $sql);

                      $perCharges = 0;
                      $perChargesOther = 0;
                    while($row = mysqli_fetch_array($ordQry))//show here order level charges
                    {
                        $perCharges += $row['price'];
                        $perChargesOther = $row['curAmt'];

                        $calDiscount = ($chargePrice*$row['price']/100);
                        $calDiscountOther = ($chargePriceOther*$row['price']/100);

                        ?>
                                                    <div class="price justify-content-between taxRow">
                                                    <div class="p-2 delIcn text-center">
                                                        <!-- <a href="javascript:void(0)">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </a> -->
                                                    </div>
                                                    <div class="p-2 txnmRow">
                                                        <p><?php echo $row['feeName'];?>
                                                                <?php echo $row['price'] ?> %</p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p><?php echo showPrice($calDiscount,$getDefCurDet['curCode']);?></p>
                                                        </div>
                                                         <?php if($ordRow['ordCurId'] > 0){?>
                                                        <div class="p-2 otherCurr">
                                                            <p><?php echo showOtherCur($calDiscountOther, $ordRow['ordCurId']);?></p>
                                                        </div>
                                                         <?php } ?>
                                                    </div>
                                                </div>

                    <?php
                     } //Ends order lelvel per discount charges


                    $totalCalDiscount =($chargePrice*$perCharges/100);//total discount feeType=3
                    $totalCalDiscountOther = ($chargePriceOther*$perCharges/100); 


                    $sql = "SELECT od.*, tp.feeName FROM ".$tbl_order_main_or_temp." od 
                    INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id

                    WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
                    $ordQry = mysqli_query($con, $sql);

                    $taxCharges = 0;
                    $totalTaxChargesOther = 0;
                while($row = mysqli_fetch_array($ordQry))//show here order level charges
                {
                    $taxCharges += $row['price'];
                    $totalTaxChargesOther += $row['curAmt'];

                    $calTax = (($chargePrice+ $fixedCharges+$totalCalDiscount)*$row['price']/100);
                    $calTaxOther = (($chargePriceOther+ $fixedChargesOther+$totalCalDiscountOther)*$row['price']/100);


                    ?>
                      <div class="price justify-content-between taxRow">
                                                    <div class="p-2 delIcn text-center">
                                                        <!-- <a href="javascript:void(0)">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </a> -->
                                                    </div>
                                                    <div class="p-2 txnmRow">
                                                        <p> <?php echo $row['feeName'];?>
                                                                <?php echo $row['price'] ?> %</p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p><?php echo showPrice($calTax,$getDefCurDet['curCode']);?></p>
                                                        </div>
                                                         <?php 
                                                        if($ordRow['ordCurId'] > 0){?>
                                                        <div class="p-2 otherCurr">
                                                            <p>11.362 €</p>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                          <?php
                } //Ends order lelvel tax discount charges


            $totalTax =(($chargePrice+$fixedCharges+$totalCalDiscount)*$taxCharges/100);//total tax feeType=1
            $totalTaxOther =(($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther)*$taxCharges/100);


            $netTotalAmt= ($chargePrice+$fixedCharges+$totalCalDiscount+$totalTax);
            $netTotalAmtOther= ($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther+$totalTaxOther);

            ?>
                                     


                                                <div <?php if ($ordCountRow == 0)
                                { 
                                echo 'style="border-top: 0px;"';  
}  ?> class="price justify-content-between grdTtl-Row">
                                                    <div class="p-2 delIcn text-center"></div>
                                                    <div class="p-2 txnmRow">
                                                        <p><?php echo showOtherLangText('Grand Total');?></p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p><?php echo showPrice($netTotalAmt,$getDefCurDet['curCode']) ?></p>
                                                        </div>
                                                        <?php 
                                                        if($ordRow['ordCurId'] > 0){?>
                                                        <div class="p-2 otherCurr">
                                                            <p><?php echo showOtherCur($netTotalAmtOther, $ordRow['ordCurId']);?></p>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        </div>
                 
                        
                            <div class="container recPrice position-relative">
                            <!-- Item Table Head Start -->
                            <div class="d-flex align-items-center itmTable">
                                <div class="prdtImg tb-head">
                                    <p><?php echo mysqli_num_rows($orderQry) > 0 ? mysqli_num_rows($orderQry) : ''; ?></p>
                                </div>
                                <div class="prdtImg tb-head">
                                    <p><?php echo showOtherLangText('Image'); ?></p>
                                </div>
                                <div class="recItm-Name tb-head">
                                    <p><?php echo showOtherLangText('Item'); ?></p>
                                </div>
                                <div class="recQty-Code d-flex align-items-center">
                                    <div class="recItm-brCode tb-head">
                                        <p><?php echo showOtherLangText('Bar Code'); ?></p>
                                    </div>
                                    <div class="qty-Ordred tb-head">
                                        <p><?php echo showOtherLangText('Qty Ordered'); ?></p>
                                    </div>
                                </div>
                                <div class="recPrc-Unit d-flex align-items-center">
                                    <div class="recItm-Unit tb-head">
                                        <p><?php echo showOtherLangText('P Unit'); ?></p>
                                    </div>
                                    <div class="qty-Rcvd tb-head">
                                        <p><?php echo showOtherLangText('Qty Received'); ?></p>
                                    </div>
                                    <?php 
            if($ordRow['ordCurId'] > 0)
            {
                $res = mysqli_query($con, " select * from tbl_currency WHERE id='".$ordRow['ordCurId']."' ");
                $curDet = mysqli_fetch_array($res);

                ?> 
                                    <div class="recCr-Type d-flex align-items-center">
                                        <div class="dflt-RecPrc tb-head">
                                            <p><?php echo showOtherLangText('P.Price'); ?>(<?php echo $getDefCurDet['curCode'] ?>)</p>
                                        </div>
                                        <div class="othr-RecPrc tb-head">
                                            <p><?php echo showOtherLangText('P.Price'); ?>(<?php echo $curDet['curCode'];?>)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="recTtlPrc-Type d-flex align-items-center">
                                    <div class="ttlDft-RecPrc tb-head">
                                        <p><?php echo showOtherLangText('Total'); ?>(<?php echo $getDefCurDet['curCode'] ?>)</p>
                                    </div>
                                    <div class="ttlOtr-RecPrc tb-head">
                                        <p><?php echo showOtherLangText('Total'); ?>(<?php echo $curDet['curCode'];?>)</p>
                                    </div>
                                </div>
                                 <?php } else { ?>
                                    
                               <div class="recCr-Type d-flex align-items-center">
                                        <div class="dflt-RecPrc tb-head">
                                            <p><?php echo showOtherLangText('P.Price'); ?>(<?php echo $getDefCurDet['curCode'] ?>)</p>
                                        </div>
                                        <div class="othr-RecPrc tb-head">
                                            <p></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="recTtlPrc-Type d-flex align-items-center">
                                    <div class="ttlDft-RecPrc tb-head">
                                        <p><?php echo showOtherLangText('Total'); ?>(<?php echo $getDefCurDet['curCode'] ?>)</p>
                                    </div>
                                    <div class="ttlOtr-RecPrc tb-head">
                                        <p></p>
                                    </div>
                                </div>


                                
                                 <?php } ?>
                            </div>
                            <!-- Item Table Head End -->
                        </div>
                            <!-- Item Table Head End -->
                       


                        <div id="boxscroll" class="compact__tb__bdy">
                            <div class="container cntTable">
                                <!-- Item Table Body Start -->

                                <?php   

        $y = 0;
        while ($showCif= mysqli_fetch_array($otherChrgQry))
            {  
                $y++;
                ?>
                                
                     <div class="newOrdTask recOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody recOrd-TblBody">
                                        <div class="prdtImg tb-bdy">
                                            <p><?php echo $y; ?></p>
                                        </div>
                                        <div class="recItm-Name tb-bdy">
                                            <p class="recive-Item"><?php echo $showCif['itemName'];?></p>
                                        </div>
                                        <div class="recQty-Code cloneQty-Code align-items-center">
                                            <div class="recItm-brCode tb-bdy">
                                                <p></p>
                                            </div>
                                            <div class="qty-Ordred tb-bdy">
                                                <p>1 <span class="tabOn-Qty">On stock</span></p>
                                            </div>
                                        </div>
                                        <div class="recPrc-Unit d-flex">
                                            <div class="tab-RecItm"></div>
                                            <div class="recItm-Unit tb-bdy">
                                                <p>1</p>
                                            </div>
                                            <div class="qty-Rcvd tb-bdy">
                                               
                                            </div>
                                            <?php if($showCif['currencyId'] > 0){ ?>
                                            <div class="recCr-Type d-flex align-items-center">
                                                <div class="dflt-RecPrc tb-bdy">
                                                 <?php echo showPrice($showCif['price'],$getDefCurDet['curCode']);?>
                                                </div>
                                                <div class="othr-RecPrc tb-bdy">
                                                  <?php echo showOtherCur($showCif['curAmt'], $showCif['currencyId']);?>
                                                </div>
                                            </div>
                                          <?php } else { ?>
                                            <div class="recCr-Type d-flex align-items-center">
                                                <div class="dflt-RecPrc tb-bdy">
                                                <?php echo showPrice($showCif['price'],$getDefCurDet['curCode']);?>
                                                </div>
                                               
                                            </div>
                                          <?php } ?>
                                        </div>
                                          <?php if($showCif['currencyId'] > 0){ ?>
                                        <div class="recTtlPrc-Type d-flex align-items-center">
                                            <div class="tabTtl-Price"></div>
                                            <div class="ttlDft-RecPrc tb-bdy">
                                                <p><?php echo showPrice($showCif['price'],$getDefCurDet['curCode']);?></p>
                                            </div>
                                            <div class="ttlOtr-RecPrc tb-bdy">
                                                <p><?php echo showOtherCur($showCif['curAmt'], $showCif['currencyId']);?></p>
                                            </div>
                                        </div>
                                        <?php }else{ ?>
                                        
                                        <?php } ?>
                                        <div class="recBr-Hide">
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                
                                <?php   }


    $x= 0;
    $subTotalAmtDol = 0;
    $subTotalAmtOther = 0;
    while($row = mysqli_fetch_array($orderQry))
    {   

        $x++;
        $y++;

        $sql=" SELECT * FROM  tbl_mobile_items_temp WHERE stockTakeId = '".$ordRow['id']."'
        AND account_id = '".$_SESSION['accountId']."'   AND stockTakeType=3  AND status=1 AND pId = '".$row['id']."'  ";
        $itemTempQry = mysqli_query($con, $sql);
        $itemTempRes = mysqli_fetch_array($itemTempQry);

        if( isset($fileDataRows[$row['barCode']]) )
        { 
            $receivedRow = $fileDataRows[$row['barCode']];
            $qtyVal = $receivedRow['qty'];
            $ordQty = $receivedRow['qty'];
            $boxPrice =$receivedRow['price'] > 0 ? $receivedRow['price'] : $row['ordFactor']*$row['ordPrice'];

            $boxPriceOther = $receivedRow['priceOther'] > 0 ? $receivedRow['priceOther'] : $row['ordFactor']*$row['curPrice'];

            unset($fileDataRows[$row['barCode']]);

        }
        elseif($itemTempRes)
        { 
            $qtyVal = $itemTempRes['qty'];
            $ordQty = $itemTempRes['qty'];

            $boxPrice = ($itemTempRes['curId'] > 0 && $curDet['is_default'] == 0 && $curDet['amt']) ? ($itemTempRes['amt']/$curDet['amt']) : $itemTempRes['amt'];
            $boxPriceOther = $itemTempRes['curId'] > 0 ? $itemTempRes['amt'] : ( ($itemTempRes['amt']*$curDet['amt']) );
            $subTotalAmtDol += ($boxPrice*$qtyVal);
            $subTotalAmtOther += ($boxPriceOther*$qtyVal);
        }
        else
        { 
            $boxPrice = ($row['ordPrice']*$row['ordFactor']);
            $boxPriceOther = ($row['ordCurPrice']*$row['ordFactor']);           
            $ordQty = $row['ordQty'];
            $qtyVal = $row['ordQty'];   
        }




        ?>

                                    <input type="hidden" name="productIds[]" id="productId<?php echo $x;?>"
                                        value="<?php echo $row['id'];?>" />
                                    <input type="hidden" id="<?php echo $x;?>" value="<?php echo $row['price'];?>" />
                                    <input type="hidden" name="factor[<?php echo $row['id'];?>]"
                                        id="factor<?php echo $x;?>" value="<?php echo $row['factor'];?>" />

                                    
                                <div class="newOrdTask recOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody recOrd-TblBody">
                                        <div class="prdtImg tb-bdy"><?php echo $y; ?></div>
                                        <div class="prdtImg tb-bdy">
                                            <?php $img = '';
            if( $row['imgName'] != ''  && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$row['imgName'] )  )
            {   
                echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$row['imgName'].'" alt="Item" class="ordItm-Img">';
            }?>
                                        </div>
                                        <div class="recItm-Name tb-bdy">
                                            <p class="recive-Item"><?php echo $row['itemName'];?></p>
                                        </div>
                                        <div class="recQty-Code cloneQty-Code align-items-center">
                                            <div class="recItm-brCode tb-bdy">
                                                <p><?php echo $row['barCode'];?></p>
                                            </div>
                                            <div class="qty-Ordred tb-bdy">
                                                <p><?php echo $row['ordQty'];?></strong> <span class="tabOn-Qty">On stock</span></p>
                                            </div>
                                        </div>
                                        <div class="recPrc-Unit d-flex">
                                            <div class="tab-RecItm"></div>
                                            <div class="recItm-Unit tb-bdy">
                                                <p><?php echo $row['purchaseUnit'];?></p>
                                            </div>
                                            <div class="qty-Rcvd tb-bdy">
                                                <input type="text" id="qty<?php echo $x;?>"
                                                    name="qty[<?php echo $row['id'];?>]" autocomplete="off"
                                                    onChange="showTotal('<?php echo $x;?>')"
                                                    value="<?php echo  $ordQty;?>" class="form-control qty-itm recQty-Receive"
                                                    placeholder="1">
                                            </div>
                                            <div class="recCr-Type d-flex align-items-center">
                                                <div class="dflt-RecPrc tb-bdy">
                                                    <input type="text" id="priceId<?php echo $x;?>"
                                                    name="price[<?php echo $row['id'];?>]" autocomplete="off"
                                                    value="<?php echo getPrice($boxPrice);?>" size="5"
                                                    onChange="showTotal('<?php echo $x;?>')" class="form-control qty-itm" placeholder="1"><?php echo $getDefCurDet['curCode'] ?>
                                                </div>
                                                 <?php 
                if( isset($curDet) )
                {
                    $showOtherCurrency = showOtherCur($boxPriceOther, $curDet['id']);
                    $showOtherCurrency = trim($showOtherCurrency, $curDet['curCode']);

                ?><div class="ttlDft-RecPrc tb-bdy">
                                                    <p><input type="text" id="priceIdOther<?php echo $x;?>" name="priceOther[<?php echo $row['id'];?>]"
                                                    autocomplete="off" value="<?php echo $showOtherCurrency; ?>"
                                                    size="5" class="form-control qty-itm recQty-Receive" onChange="showTotalOther('<?php echo $x;?>')">
                                                <?php echo $curDet['curCode'];?>
                                            </p>
                                                </div>
                                        <?php
            }
            ?>
                                            </div>
                                        </div>
                                        <div class="recTtlPrc-Type d-flex align-items-center">
                                            <div class="tabTtl-Price"></div>
                                            <div class="ttlDft-RecPrc tb-bdy">
                                                <p id="totalPrice<?php echo $x;?>"><?php echo showPrice($boxPrice*$qtyVal,$getDefCurDet['curCode']);?></p>
                                            </div>
                                            <div class="ttlOtr-RecPrc tb-bdy">
                                                <p id="totalPrice<?php echo $x;?>"><?php 
                if( isset($curDet) )
                {
                    $newCurAmt = ($boxPriceOther*$qtyVal);
                    echo '<span id="totalPriceOther'.$x.'" style="font-weight: bold;">'.showOtherCur($newCurAmt, $curDet['id']).'</span>';
                }
                ?></p>
                                            </div>
                                        </div>
                                        <div class="recBr-Hide">
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                        <?php   }

            

        $notFoundProducts = [];
        if( isset($fileDataRows) && !empty($fileDataRows) )
        {
            foreach($fileDataRows as $barCode=>$receivedRow)
            {
                $x++;
                
                
                $sql = "SELECT tp.*, IF(u.name!='',u.name,tp.unitP) purchaseUnit FROM tbl_products tp 
    LEFT JOIN tbl_units u ON(u.id = tp.unitP) AND u.account_id = tp.account_id
    WHERE tp.barCode = '".$barCode."' AND tp.account_id = '".$_SESSION['accountId']."'   ";
                $resultSet = mysqli_query($con, $sql);
                $productRes = mysqli_fetch_array($resultSet);
    
    
                if(!$productRes || $productRes['barCode'] == '')
                {
                    continue;
                }

                $boxPrice = $receivedRow['price'] > 0 ? $receivedRow['price'] : $productRes['factor']*$productRes['price'];
                $qty = $receivedRow['qty'];
                ?>
                                    <tr><input type="hidden" id="factor<?php echo $x;?>" name="factor[]">
                                        <input type="hidden" id="supplierId<?php echo $x;?>" name="supplierId[]"
                                            size="5" value="<?php echo $receivedRow['supplierId'];?>">
                                
                                 <div class="newOrdTask recOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody recOrd-TblBody">
                                        <div class="recItm-Name tb-bdy">
                                            <p class="recive-Item"><?php echo $x;?></p>
                                        </div>
                                        <div class="prdtImg tb-bdy">
                                           <?php $img = '';
            if( $productRes['imgName'] != ''  && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$productRes['imgName'] )  )
            {   
                echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$productRes['imgName'].'" width="60" height="60">';
            }?>
                                        </div>
                                        <div class="recItm-Name tb-bdy">
                                            <p class="recive-Item"><?php echo $productRes['itemName'];?></p>
                                        </div>
                                        <div class="recQty-Code cloneQty-Code align-items-center">
                                            <div class="recItm-brCode tb-bdy">
                                                <p><?php echo $productRes['barCode'];?><input type="hidden"
                                                id="barCode<?php echo $x;?>" name="barCode[]"
                                                value="<?php echo $productRes['barCode'];?>" size="10"></p>
                                            </div>
                                            <div class="qty-Ordred tb-bdy">
                                                <p> 0 <span class="tabOn-Qty">On stock</span></p>
                                            </div>
                                        </div>
                                        <div class="recPrc-Unit d-flex">
                                            <div class="tab-RecItm"></div>
                                            <div class="recItm-Unit tb-bdy">
                                                <p id="purUnit<?php echo $x;?>"><?php echo $productRes['purchaseUnit'];?></p>
                                            </div>
                                            <div class="qty-Rcvd tb-bdy">
                                                <input class="form-control qty-itm recQty-Receive" type="text" id="qty<?php echo $x;?>" name="qtyReceived[]"
                                                onChange="showTotal(<?php echo $x;?>)" size="5"
                                                value="<?php echo $qty;?>">
                                            </div>
                                            <div class="recCr-Type d-flex align-items-center">
                                                <div class="dflt-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" id="priceId<?php echo $x;?>" name="qtyReceivedPrice[]"
                                                value="<?php echo $boxPrice;?>" size="5"
                                                onChange="showTotal(<?php echo $x;?>)">$
                                                </div>
                                                <div class="othr-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="recTtlPrc-Type d-flex align-items-center">
                                            <div class="tabTtl-Price"></div>
                                            <div class="ttlDft-RecPrc tb-bdy">
                                                <p><span
                                                id="totalPrice<?php echo $x;?>"><?php echo showPrice($boxPrice*$qty, $getDefCurDet['curCode']);?></span></p>
                                            </div>
                                           
                                        </div>
                                        <div class="recBr-Hide">
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                 <?php }
            } ?>


                                <!-- Item Table Body End -->
                            </div>
                        </form>
                        </div>
                    </section>
                   

                </div>
            </div>
        </div>
    </main>

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
</body>

</html>
<script>
    function showTotal(slno) {
        var unitPrice = $('#priceId' + slno).val();
        var unitPrice = parseFloat(unitPrice.replaceAll(",", ""));
        var qty = $('#qty' + slno).val();
        var factor = $('#factor' + slno).val();
        var pId = $('#productId' + slno).val();

        var totalPriceVal = (qty * unitPrice).toFixed(4);

        $('#totalPrice' + slno).html(totalPriceVal + ' <?php echo $getDefCurDet['curCode'] ?>');

        var otherCurAmt = '<?php echo $curDet['amt'];?>';

        var totalPriceOther = (qty * unitPrice * otherCurAmt).toFixed(4);

        var priceIdOther = (unitPrice * otherCurAmt).toFixed(4);

        $.ajax({
                method: "POST",
                url: "receiveOrderAjax.php",
                dataType: 'json',
                data: {
                    orderId: '<?php echo $_GET['orderId'] ?>',
                    qty: qty,
                    pId: pId,
                    unitPrice: unitPrice,
                    factor: factor,
                    totalPriceVal: totalPriceVal,
                    curAmtVal: otherCurAmt,
                    priceIdOther: priceIdOther,
                    currencyId: '<?php echo $curDet['id']; ?>'

                }
            })
            .done(function(responseObj) {

                $('#totalOrdArea').html(responseObj.resHtml);
                $('#priceIdOther' + slno).val(responseObj.priceIdOther);
                $('#totalPriceOther' + slno).html(responseObj.totalPriceOther);

            });

    }

     function showTotalOther(slno, otherAmt = 1) {
        var unitPrice = $('#priceIdOther' + slno).val();
        var unitPrice = parseFloat(unitPrice.replaceAll(",", ""));
        var qty = $('#qty' + slno).val();
        var factor = $('#factor' + slno).val();
        var pId = $('#productId' + slno).val();
        var totalPriceOther = (qty * unitPrice);
        var otherCurAmt = '<?php echo $curDet['amt'];?>';
        var dolPrice = (qty * unitPrice) / otherCurAmt;
        var priceId = (unitPrice / otherCurAmt);

        $.ajax({
                method: "POST",
                url: "receiveOrderAjax.php",
                dataType: 'json',
                data: {
                    orderId: '<?php echo $_GET['orderId'] ?>',
                    qty: qty,
                    pId: pId,
                    factor: factor,
                    unitPrice: unitPrice,
                    priceId: priceId,
                    curAmtVal: otherCurAmt,
                    currencyId: '<?php echo $curDet['id']; ?>',
                    otherAmt: otherAmt

                }
            })
            .done(function(responseObj) {

                $('#totalOrdArea').html(responseObj.resHtml);
                $('#totalPrice' + slno).html(responseObj.totalPrice);
                $('#totalPriceOther' + slno).html(responseObj.totalPriceOther);
                $('#priceId' + slno).val(responseObj.priceId);

            });

    }

    $('.receive-btn').click(function(){
         var invNo = document.getElementById("invNo");
         var invError = document.getElementById("invError");

         if (!invNo.checkValidity()) {
              invNo.reportValidity()
              
        } else {
            
            $('#upload_form').submit();
        }
        


    });
</script>
<script>
    //update invoice number when user change/fill invoice section
    function getInvNo() {

        var invNumber = $("#invNo").val();

        if (invNumber !== '') {
            $.ajax({

                method: "POST",
                url: "receiveOrder.php",
                data: {
                    invoiceNumber: invNumber,
                    orderId: '<?php echo $_GET['orderId'] ?>'
                }
            })

        }

    }
    </script>
    <script type="text/javascript">
    window.onload = function() {
        var fileupload = document.getElementById("uploadFile");
        var button = document.getElementById("btnFileUpload");
        button.onclick = function() {
            fileupload.click();
        };
        fileupload.onchange = function() {
            document.getElementById('upload_form').submit();
        };
    };
    </script>