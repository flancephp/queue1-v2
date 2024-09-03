<?php 
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


if ( !isset($_SESSION['adminidusername']))
{           
echo '<script>window.location = "login.php"</script>';
}  


if (isset($_GET['orderId']))
{
$sql = "SELECT tp.*, od.price ordPrice, od.curPrice ordCurPrice, od.qty ordQty, od.totalAmt, od.factor ordFactor, od.qtyReceived, IF(u.name!='',u.name,tp.unitP) purchaseUnit FROM tbl_order_details od 
INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
LEFT JOIN tbl_units u ON(u.id = tp.unitP) AND u.account_id = tp.account_id
WHERE od.ordId = '".$_GET['orderId']."'  AND tp.account_id = '".$_SESSION['accountId']."'  ";
$ordQry = mysqli_query($con, $sql);
}

$orderQry="SELECT * FROM tbl_orders WHERE id='".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."' ";
$orderQry = mysqli_query($con, $orderQry);
$orderRow = mysqli_fetch_array($orderQry);

$curDetail = getCurrencyDet($orderRow['ordCurId']);

$payQry = "SELECT * FROM tbl_payment WHERE orderId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' order by id limit 1 "; 
$paymentResult = mysqli_query($con, $payQry);
$paymentRow = mysqli_fetch_array($paymentResult);

$curQry = " SELECT * FROM tbl_currency WHERE id='".$paymentRow['currencyId']."' AND account_id = '".$_SESSION['accountId']."' ";
$curResult = mysqli_query($con, $curQry);
$curRow = mysqli_fetch_array($curResult);

$currencyCode = $curRow['curCode'];


$payModeQry = " SELECT * FROM tbl_payment_mode WHERE id='".$paymentRow['paymentType']."'  AND account_id = '".$_SESSION['accountId']."' ";
$payModeResult = mysqli_query($con, $payModeQry);
$payModeRow = mysqli_fetch_array($payModeResult);


$payInfoQry = "SELECT * FROM tbl_supplier_payment_info WHERE  orderId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
$payInfoResult = mysqli_query($con, $payInfoQry);
$payInfoRow = mysqli_fetch_array($payInfoResult);

//refund related code goes here 
if ( isset($_POST['approveBtn']) && ($_POST['refund']=="refundAmt" || $_POST['refund']=="refundAll") )
{

            if ($_POST['refund']=="refundAll")
            {

            $cmd="SELECT * FROM tbl_order_details WHERE ordId='".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."' AND pId > 0 ";
            $ordersQry = mysqli_query($con, $cmd);

            while($orderRow = mysqli_fetch_array($ordersQry))
            {
            if ($orderRow['qtyReceived'] > 0) {
            $qty = $orderRow['qtyReceived'];
            }else{
            $qty = $orderRow['qty'];
            }

            $factor = $orderRow['factor'];

            $upQry = " UPDATE `tbl_stocks` SET
            `qty` = (qty - ($qty * $factor)),
            `stockValue` = ( stockValue - ".$orderRow['totalAmt']." )
            WHERE pId = '".$orderRow['pId']."' AND account_id = '".$_SESSION['accountId']."'   ";
            mysqli_query($con, $upQry);
            }

            }





if ( empty($updatePaymentRow['paymentStatus']) ) 
{

$sqlSet = " SELECT tp.amount, tp.bankAccountId,tp.currencyId, c.curCode AS curCode, ac.* FROM tbl_payment tp 
INNER JOIN tbl_accounts ac ON(tp.bankAccountId = ac.id) AND tp.account_id = ac.account_id
LEFT JOIN tbl_currency c ON(tp.currencyId = c.id) AND tp.account_id = c.account_id
WHERE tp.orderId = '".$_POST['orderId']."' order by tp.id limit 1 "; 
$resultSqlSet= mysqli_query($con, $sqlSet);
$resultRow= mysqli_fetch_array($resultSqlSet);

$currCode = $resultRow['curCode'];
$bankAccountId = $resultRow['bankAccountId'];

$amount = $resultRow['amount'];
if ($resultRow['currencyId'] > 0)
{
$trimedAmount = trim($amount,$currCode);

}else{
$trimedAmount = trim($amount,'$');
}

$replacedAmount = str_replace(',', '', $trimedAmount);

$updateQry= " UPDATE tbl_accounts SET balanceAmt=(balanceAmt+$replacedAmount ) WHERE id='".$bankAccountId."'  AND account_id = '".$_SESSION['accountId']."'  ";
$resultQry= mysqli_query($con, $updateQry);

$updtQry = " UPDATE tbl_orders SET paymentStatus='2', bankAccountId = '0', paymentDateTime = '' WHERE id='".$_POST['orderId']."' ";
$resultQry= mysqli_query($con, $updtQry);
}

$qry = "SELECT * FROM tbl_payment WHERE orderId='".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."' AND paymentStatus = 1 order by id limit 1 "; 
$result = mysqli_query($con, $qry);
$paymentRow = mysqli_fetch_array($result);

if (count($paymentRow) > 0) 
{

$insertQry = "INSERT INTO `tbl_payment_history` SET
parentPaymentId = '".$paymentRow['id']."',
account_id = '".$paymentRow['account_id']."',
orderId = '".$paymentRow['orderId']."',
bankAccountId = '".$paymentRow['bankAccountId']."',
currencyId = '".$paymentRow['currencyId']."',
ordTotAmt = '".$paymentRow['ordTotAmt']."',
amount = '".$paymentRow['amount']."',
paymentType = '".$paymentRow['paymentType']."',
paymentStatus = '".$paymentRow['paymentStatus']."',
paymentDateTime = '".$paymentRow['paymentDateTime']."' ";

mysqli_query($con, $insertQry);

$updateQry = " UPDATE tbl_payment SET 
currencyId = '',
ordTotAmt = '',
paymentType = '',
paymentStatus = '',
paymentDateTime = ''
WHERE orderId = '".$_GET['orderId']."' ";
$updatePaymentResult = mysqli_query($con, $updateQry);
$updatePaymentRow = mysqli_fetch_array($updatePaymentResult);

}
$refund = $_POST['refund']=="refundAll" ? 'Amount and Qty refund' : 'Amount refund only';

$sql = " SELECT * FROM tbl_orders WHERE id = '".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
        $res = mysqli_query($con, $sql);
        $orderDetRow = mysqli_fetch_array($res);
    
        $qry = " INSERT INTO `tbl_order_journey` SET 
            `account_id` = '".$_SESSION['accountId']."',
            `orderId` = '".$orderDetRow['id']."',
            `userBy`  = '".$_SESSION['id']."',
            `amount` = '".$orderDetRow['ordAmt']."',
            `otherCur` = '".$orderDetRow['ordCurAmt']."',
            `otherCurId` = '".$orderDetRow['ordCurId']."',
            `orderType` = '".$orderDetRow['ordType']."',
            `ordDateTime` = '".date('Y-m-d h:i:s')."',
            `notes` = '".$refund."',
            `action` = 'Payment Refunded' ";
            mysqli_query($con, $qry);

echo '<script>window.location = "history.php?orderId='.$_POST['orderId'].'&paymentStatus=2"</script>';

}
?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>History - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/style1.css">
    <link rel="stylesheet" href="Assets/css/module-A.css">
    <link rel="stylesheet" href="Assets/css/style_p.css">


    <style>
    .modal-md {
        max-width: 800px;
    }

    .modal-address p {
        padding-bottom: 2px;
    }

    .site-modal .modal-content {
        background: #f0f0f0;
    }

    .site-modal .modal-body {
        background: #ffffff;
    }


    .site-modal thead tr th {
        font-weight: 500;
        padding: 8px 5px;
    }

    .site-modal tr.thead td {
        font-weight: 500;
        padding: 8px 5px;
    }

    .site-modal .thead {
        border-top: 0;
    }

    .site-modal .thead+tr {
        border-top: 0;
    }

    .site-modal thead,
    .site-modal .thead {
        background-color: rgb(122 137 255 / 20%);
    }

    .site-modal tbody tr+tr {
        border-top: 1px solid #ddd;
    }

    .site-modal tbody tr td {
        padding: 5px 5px;
    }

    .modal-header .btn {
        background-color: #7a89ff;
        color: #fff;
        border-radius: 6px;
        font-size: 14px;
    }

    .modal-header .btn {
        background-color: #7a89ff;
        color: #fff;
        border-radius: 6px;
        font-size: 14px;
        border-color: #7a89ff;
    }

    .modal-head-row .btn {
        font-size: 13px;
        height: 34px;
    }

    .modal-head-row ul.dropdown-menu {
        box-shadow: 0 0 10px #d5d5d5;
    }

    .modal-head-row .btn i {
        font-size: 10px;
    }

    /* input select custon css for theis page  */
    .form-control {
        background-color: white;
        padding: .5rem;
    }

    .form-info tr,
    .payDetail tr {
        height: 38px !important;
    }

    .inv-detail tr {
        min-height: 38px !important;
    }

    .wht-btn:hover,
    .wht-btn:focus,
    .wht-btn:active {
        border-color: #fff;
        background: #fff;
        color: #000;
        border: 1px solid #000000;
    }

    .final-btn:hover {
        background: #ED7D31;
        border: 2px solid #AE5A21;
    }

    .wht-btn {
        font-size: 12px;
        line-height: 15px;
        font-weight: 400;
        color: #000;
        background: linear-gradient(180deg, #F0EFEE 0%, #DCDCDC 100%);
        border: 1px solid #000000;
        border-radius: 4px;
        padding: 7px 20px;
    }

    .final-btn {
        font-size: 12px;
        line-height: 15px;
        font-weight: 700;
        background: #ED7D31;
        border: 2px solid #ED7D31;
        border-radius: 4px;
        padding: 7px 15px;
        transition: all .1s ease-in-out;
        color: white;
    }
    </style>

</head>

<body>

    <div class="container-fluid newOrder">
        <div class="row">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                <?php require_once('nav.php'); ?>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('History'); ?></h1>
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
                                    <!-- <h1 class="h1"> History </h1> -->
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


                <div class="payment-status-main">
                    <!--payment pending Details Popup Start -->
                    <div class="payment-status-left payment-paid position-relative">
                        <div class="border-line"></div>
                        <div class="modal-body p-4 p-xl-5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="f-01 mb-0 payment-status-text"><?php echo showOtherLangText('PAID'); ?>
                                    </p>
                                    <p class="f-01"><?php echo showOtherLangText('PENDING'); ?></p>
                                </div>
                                <div>
                                    <?php

                        $clientQry = " SELECT * FROM tbl_client WHERE id = '".$_SESSION['accountId']."' ";
                        $clientResult = mysqli_query($con, $clientQry);
                        $clientResultRow = mysqli_fetch_array($clientResult);

                        if($clientResultRow['logo'] !='' && file_exists(dirname(__FILE__)."/uploads/".$accountImgPath."/clientLogo/".$clientResultRow['logo']))
                        {   
                            echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/clientLogo/'.$clientResultRow['logo'].'" width="100" height="100" style="object-fit: contain">';
                        }
                        ?>
                                </div>
                            </div>

                            <div class="mt-4 row g-0 payment-tabel-header">
                                <div class="col-md-7">
                                    <table class="table1 table01">
                                        <tbody>
                                            <tr>
                                                <td class="font-wt" style="white-space:nowrap">
                                                    <?php echo showOtherLangText('Supplier Invoice'); ?> #
                                                </td>
                                                <td class="in-val-p"><?php echo $payInfoRow['supplierInvoice'] ?></td>
                                            </tr>
    
                                            <tr>
                                                <td class="font-wt" style="white-space:nowrap">
                                                    <?php echo showOtherLangText('Payment'); ?> #
                                                </td>
                                                <td class="in-val-p"><?php echo setPaymentId($paymentRow['id']); ?></td>
                                            </tr>
    
                                            <tr>
                                                <td class="font-wt" style="white-space:nowrap"><?php echo showOtherLangText('Task') ?> #</td>
                                                <td class="in-val-p"><?php echo $orderRow['ordNumber'] ?></td>
                                            </tr>
    
                                            <tr>
                                                <td class="font-wt" style="white-space:nowrap"><?php echo showOtherLangText('Date'); ?> #
                                                </td>
                                                <td class="in-val-p">
                                                    <?php 
                                        if ($paymentRow['paymentStatus'] ==1 ) {
    
                                        echo $paymentRow['paymentDateTime'];
    
                                        }else{
    
                                        echo $orderRow['ordDateTime'];
                                        } ?>
    
                                                </td>
                                            </tr>
    
                                        </tbody>
                                    </table> 
                                </div>
                                <?php
                                    $sql = " SELECT * FROM tbl_country WHERE id = '".$clientResultRow['country']."' ";
                                    $resSet = mysqli_query($con, $sql);
                                    $resultRow = mysqli_fetch_array($resSet); 
                                ?>
                                <div class="col-md-5"> 
                                    <table class="w-100">
                                        <tbody class="table1 table01 fl-right cmp-dtl text-start text-md-end">
                                            <tr>
                                                <td><?php echo $clientResultRow['accountName']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $clientResultRow['address_one'].','.$clientResultRow['address_two'] ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $clientResultRow['city'].','.$resultRow['name'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $clientResultRow['email']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $clientResultRow['phone']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div> 
                            </div>

                            <br>
                            <div class="paymentto-p">
                                <p class="f-02 mb-2"><?php echo showOtherLangText('Payment To'); ?>: </p>
                                <p class="f-03 in-val-p mb-1"><?php echo $payInfoRow['supplierName']; ?></p>
                                <p class="f-03 in-val-p mb-1"><?php echo nl2br($payInfoRow['supplierAddress']); ?></p>
                                <p class="f-03 in-val-p mb-1"><?php echo $payInfoRow['supplierEmail']; ?></p>
                                <p class="f-03 in-val-p mb-1"><?php echo $payInfoRow['supplierPhone']; ?></p>
                            </div>
                            <br>

                            <div class="table-responsive mt-4">

                                <table class="modal-table fs-12 w-100 payment__table">
                                    <thead style="background: #A9B0C0 !important;">
                                        <tr class="tr-bg-1">
                                            <th>#</th>
                                            <th style="width: 22%;"><?php echo showOtherLangText('Item'); ?></th>
                                            <th><?php echo showOtherLangText('Unit'); ?></th>
                                            <th style="width: 14%;"><?php echo showOtherLangText('Ordered'); ?><br><?php echo showOtherLangText('Quantity'); ?>
                                            </th>
                                            <th style="width: 14%;"><?php echo showOtherLangText('Receive'); ?><br><?php echo showOtherLangText('Quantity'); ?>
                                            </th>
    
                                            <?php 
                                            if ($orderRow['ordCurId'] > 0)
                                            {   
                                            ?>
    
                                            <th class="th-bg-1">
                                                <?php echo showOtherLangText('Price'); ?>(<?php echo $curDetail['curCode'] ?>)
                                            </th>
    
                                            <?php }else{ ?>
    
                                            <th class="th-bg-1">
                                                <?php echo showOtherLangText('Price'); ?>(<?php echo $getDefCurDet['curCode'] ?>)
                                            </th>
    
                                            <?php 
                                            } if ($orderRow['ordCurId'] > 0) { 
                                            ?>
                                            <th class="th-bg-1">
                                                <?php echo showOtherLangText('Total'); ?>(<?php echo $getDefCurDet['curCode'] ?>)
                                            </th>
                                            <?php }else{ ?>
                                            <th class="th-bg-1">
                                                <?php echo showOtherLangText('Total'); ?>(<?php echo $getDefCurDet['curCode'] ?>)
                                            </th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody class="tabel-body-p">
                                        <?php 
                                            $sql = "SELECT cif.itemName, cif.unit,cif.amt, tod.* FROM tbl_order_details tod 
                                            INNER JOIN tbl_custom_items_fee cif ON(tod.customChargeId = cif.id) AND tod.account_id = cif.account_id
                                            WHERE tod.ordId = '".$_GET['orderId']."'  AND tod.account_id = '".$_SESSION['accountId']."'  and tod.customChargeType=1 ORDER BY cif.itemName ";
                                            $resultSet=mysqli_query($con, $sql);   
                                            $x=0;
                                            while ($showCif= mysqli_fetch_array($resultSet))
                                            {
                                                $x++;
                                        ?>
                                        <tr>
                                            <td><?php echo $x; ?></td>
                                            <td style="width: 22%;"><?php echo $showCif['itemName'];?></td>
                                            <td><?php echo $showCif['unit'];?></td>
                                            <td style="width: 14%;">1</td>
                                            <td style="width: 14%;">1</td>
                                            <?php 
                                            if ($orderRow['ordCurId'] > 0) { 
                                            ?>
    
                                            <td>
                                                <?php echo showOtherCur($showCif['amt']*$curDetail['amt'], ($orderRow['ordCurId']));?>
                                            </td>
    
                                            <?php }else{ ?>
    
                                            <td><?php showPrice($showCif['amt'],$getDefCurDet['curCode']);?></td>
    
                                            <?php } 
                                                    if ($orderRow['ordCurId'] > 0)
                                            { ?>
    
                                            <td>
                                                <?php echo showOtherCur($showCif['amt']*$curDetail['amt'],($orderRow['ordCurId']));?>
                                            </td>
    
                                            <?php 
                                             } else { ?>
    
                                            <td><?php showPrice($showCif['amt'],$getDefCurDet['curCode']); ?></td>
    
                                            <?php  }  ?>
    
                                        </tr>
                                        <?php } 
                                        while($row = mysqli_fetch_array($ordQry))
                                        { 
                                            $x++;
                                        ?>
                                        <tr>
                                            <td><?php echo $x; ?></td>
                                            <td><?php echo $row['itemName'] ?></td>
                                            <td><?php echo $row['purchaseUnit'] ?></td>
                                            <td><?php echo $row['ordQty'] ?></td>
                                            <td><?php echo $row['qtyReceived']; ?></td>
                                            <?php 
                                            //price column start here
                                            if ($orderRow['ordCurId'] > 0)
                                            { ?>
    
                                            <td class="pay-dt">
                                                <?php echo showOtherCur($row['ordCurPrice']*$row['factor'], ($orderRow['ordCurId'])); ?>
                                            </td>
    
                                            <?php  }else{ ?>
    
                                            <td class="pay-dt">
                                                <?php showPrice($row['ordPrice']*$row['factor'],$getDefCurDet['curCode']); ?>
                                            </td>
    
                                            <?php  } ?>
                                            <?php
                                            if ($orderRow['ordCurId'] > 0)
                                            { ?>
    
                                            <td class="pay-dt" style="font-weight: bold;">
                                                <?php echo $row['qtyReceived'] > 0 ? showOtherCur($row['ordCurPrice']*$row['factor']*$row['qtyReceived'],($orderRow['ordCurId'])) : showOtherCur($row['ordQty']*$row['factor']*$row['ordCurPrice'], ($orderRow['ordCurId'])); ?>
                                            </td>
    
                                            <?php }else{ ?>
    
                                            <td class="pay-dt" style="font-weight: bold;">
                                                <?php echo $row['qtyReceived'] > 0 ? showPrice($row['ordPrice']*$row['factor']*$row['qtyReceived'],$getDefCurDet['curCode']) : showPrice($row['ordPrice']*$row['factor']*$row['ordQty'],$getDefCurDet['curCode']); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <?php } ?>
    
                                    </tbody>
                                </table>
                            </div>
                            <div class="divider-blue"></div>
                            <br>
                            <div class="tabel-body-p-footer gap-3 flex-wrap">
                                <div class="table1 col-md-4">
                                    <p class="f-02 mb-1"><?php echo showOtherLangText('Payment Method'); ?></p>
                                    <?php 
                    $sqlSet = " SELECT * FROM  tbl_accounts WHERE id='".$paymentRow['bankAccountId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
                    $resultSet = mysqli_query($con, $sqlSet);
                    $accDet = mysqli_fetch_array($resultSet);
                    ?>
                                    <p class="f-03 mb-1 in-val-p"><?php echo $payModeRow['modeName'];?></p>
                                    <p class="f-03 mb-0 in-val-p"><?php echo $accDet['accountName'];?></p>
                                </div>

                                <!-- grand totale  here -->
                                <table class="grand-total-tabel col">
                                    <?php 
                $sqlSet="SELECT SUM(totalAmt) as sum1, SUM(curAmt) AS sum2 from tbl_order_details where ordId='".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."'  AND (customChargeType='1' OR customChargeType='0')";
                $resultSet = mysqli_query($con, $sqlSet);
                $chargeRow = mysqli_fetch_array($resultSet);    
                $chargePrice=$chargeRow['sum1'];
                $totalPriceOther=($chargeRow['sum2']);                  


                $ordCount="SELECT * from tbl_order_details where ordId='".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."' AND customChargeType='2' ";
                $ordCountResult = mysqli_query($con, $ordCount);
                $ordCountRow = mysqli_num_rows($ordCountResult);

                if ($ordCountRow > 0)
                {
                    if ($orderRow['ordCurId'] > 0)
                    { ?>
                                    <tr>
                                        <td><?php echo showOtherLangText('Sub Total'); ?></td>
                                        <td><?php  echo showOtherCur($totalPriceOther, $orderRow['ordCurId']);?></td>
                                    </tr>
                                    <?php }else{ ?>
                                    <tr>
                                        <td><?php echo showOtherLangText('Sub Total'); ?></td>
                                        <td><?php  showPrice($chargePrice,$getDefCurDet['curCode']);?></td>
                                    </tr>
                                    <?php } }
                                   
$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

$ordQry = mysqli_query($con, $sql);

$fixedCharges = 0;
$fixedChargesOther = 0;

$sql = " SELECT SUM(od.totalAmt) AS totalFixedCharges, tp.feeName FROM tbl_order_details od 
INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName   ";
$sumQry = mysqli_query($con, $sql);
$totalSum= mysqli_fetch_array($sumQry);

$totalFixedCharges=$totalSum['totalFixedCharges'];


while($row = mysqli_fetch_array($ordQry))//show here order level charges
{
$fixedCharges =$row['price'];
$fixedChargesOther = $row['curAmt'];
$totalFixedChargesOther = $fixedChargesOther;

if ($orderRow['ordCurId'] > 0)
    { ?>
                                    <tr>
                                        <td><?php echo $row['feeName'];?></td>
                                        <td style="font-weight: bold;">
                                            <?php  echo showOtherCur($fixedChargesOther, $orderRow['ordCurId']) ?>
                                        </td>
                                    </tr>

                                    <?php }else{ ?>

                                    <tr>
                                        <td><?php echo $row['feeName'];?></td>
                                        <td style="font-weight: bold;">
                                            <?php  showPrice($fixedCharges,$getDefCurDet['curCode']) ?>
                                        </td>
                                    </tr>

                                    <?php
    }
} //Ends order lelvel fixed discount charges



//Start order level discoutns

$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
WHERE od.ordId = '".$_GET['orderId']."' AND od.account_id = '".$_SESSION['accountId']."' and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
$ordQry = mysqli_query($con, $sql);

$perCharges = 0;
$itemIds = [];
$totalCharges = 0;
while($row = mysqli_fetch_array($ordQry))//show here order level charges
{
    $itemIds[] = $row['customChargeId'];              
    $totalCharges = $row['totalAmt'];
    $perCharges += $row['totalAmt'];

    $discountPercent=$chargePrice*$totalCharges/100;
    $discountPercentOther=$totalPriceOther*$totalCharges/100;
    $totalDiscountPercent=$chargePrice*$perCharges/100;
    $totalDiscountPercentOther=$totalPriceOther*$perCharges/100;
    if($row)
    {
        if ($orderRow['ordCurId'] > 0)
        { ?>

                                    <tr>
                                        <td><?php echo $row['feeName'];?></td>
                                        <td style="font-weight: bold;">
                                            <?php echo showOtherCur($discountPercentOther, $orderRow['ordCurId']); ?>
                                        </td>
                                    </tr>


                                    <?php }else{ ?>

                                    <tr>
                                        <td><?php echo $row['feeName'];?></td>
                                        <td style="font-weight: bold;">
                                            <?php showPrice($discountPercent,$getDefCurDet['curCode']); ?>
                                        </td>
                                    </tr>

                                    <?php
        }
    }
}//End of order level discount

//Starts order level tax discount charges
$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
$ordQry = mysqli_query($con, $sql);

$taxCharges = 0;
while($row = mysqli_fetch_array($ordQry))//show here order level charges
{
$tax = $row['price'];

$taxCharges=(($chargePrice+$totalFixedCharges+$totalDiscountPercent)*$tax/100);
$taxChargesOther=(($totalPriceOther+$totalFixedChargesOther+$totalDiscountPercentOther)*$tax/100);
$totalTaxCharges += (($chargePrice+$totalFixedCharges+$totalDiscountPercent)*$tax/100);
$totalTaxChargesOther += (($totalPriceOther+$totalFixedChargesOther+$totalDiscountPercentOther)*$tax/100);

if ($orderRow['ordCurId'] > 0)
{ ?>

                                    <tr>
                                        <td><?php echo $row['feeName'];?></td>
                                        <td style="font-weight: bold;">
                                            <?php echo showOtherCur($taxChargesOther, $orderRow['ordCurId']) ?>
                                        </td>
                                    </tr>

                                    <?php }else{ ?>

                                    <tr>
                                        <td><?php echo $row['feeName'];?></td>
                                        <td style="font-weight: bold;">
                                            <?php showPrice($taxCharges,$getDefCurDet['curCode']) ?>
                                        </td>
                                    </tr>

                                    <?php
}
} //end order level tax discount charges



$sqlSet = "SELECT SuM(totalAmt) AS totalFixedDiscount, SUM(curAmt) AS totalFixedDiscountOther FROM tbl_order_details od 
INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";
$resultSet = mysqli_query($con, $sqlSet);
$fixedDiscountRow = mysqli_fetch_array($resultSet); 
$totalFixedDiscount= $fixedDiscountRow['totalFixedDiscount'];
$totalFixedDiscountOther= $fixedDiscountRow['totalFixedDiscountOther'];

$netTotalAmt= ($chargePrice+ $totalTaxCharges+$totalDiscountPercent+$totalFixedDiscount);
$netTotalAmtOther= ($totalPriceOther+ $totalTaxChargesOther+$totalDiscountPercentOther+$totalFixedDiscountOther);


if ($orderRow['ordCurId'] > 0)
{ ?>



                                    <tr class="grand-total" style=" max-height: 38px;">
                                        <th><?php echo showOtherLangText('Grand Total'); ?>(<?php echo $curDetail['curCode'];?>)
                                        </th>
                                        <th><?php echo showOtherCur($netTotalAmtOther, $orderRow['ordCurId']) ?></th>
                                    </tr>
                                    <tr class="grand-total" style=" max-height: 38px;">
                                        <th><?php echo showOtherLangText('Grand Total'); ?>(<?php echo $getDefCurDet['curCode'];?>)
                                        </th>
                                        <th><?php showPrice($netTotalAmt,$getDefCurDet['curCode']); ?></th>
                                    </tr>
                                    <?php } else { ?>
                                    <tr class="grand-total" style=" max-height: 38px;">
                                        <th><?php echo showOtherLangText('Grand Total'); ?>
                                            (<?php echo $getDefCurDet['curCode'] ?>)
                                        </th>
                                        <th style="font-weight: bold;">
                                            <?php showPrice($netTotalAmt,$getDefCurDet['curCode']); ?>
                                        </th>
                                    </tr>

                                    <?php } ?>
                                </table>

                            </div>

                            <br>
                            <br>
                            <br>
                        </div>
                    </div>
                    <!-- payment Detail Popup End -->

                    <div class="payment-status-right">
                        <div class="bill-form container">
                            <div class="bill-form-brd">
                                <form action="" method="post" id="frm" name="frm" class="mtop"
                                    enctype="multipart/form-data">
                                    <div class="row table-responsive">
                                        <h6 class="bill-head">
                                            <img src="https://queue1.net/qa1/uploads/hand.png" alt="Payment-hand">
                                            <?php echo showOtherLangText('Payment'); ?>
                                        </h6>
                                    </div>

                                    <div class="inv-top">
                                        <table class="mr-btm">
                                            <tbody class="payDetail">
                                                <tr>
                                                    <td><?php echo showOtherLangText('Payment'); ?> #</td>
                                                    <td>
                                                        <input type="text" style="cursor: text; background:none;"
                                                            class="form-control form-control-1" onchange="getVal();"
                                                            name="paymentId" id="paymentId" autocomplete="off"
                                                            value="<?php echo setPaymentId($paymentRow['id']); ?>"
                                                            readonly="">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Task'); ?> #</td>
                                                    <td>
                                                        <input type="text" style="cursor: text; background:none;"
                                                            class="form-control form-control-1" name="ordNumber"
                                                            value="<?php echo $orderRow['ordNumber'] ?>"
                                                            autocomplete="off" readonly="">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Date'); ?> #</td>
                                                    <td class="payDate"><?php 
    if ($paymentRow['paymentStatus'] ==1 ) {
        
        echo $paymentRow['paymentDateTime'];

    }else{

        echo $orderRow['ordDateTime'];
    }

     ?></td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="inv-detail mt-3">
                                        <table cellpadding="0" cellspacing="0" width="100%">
                                            <tbody class="frm-info">
                                                <tr class="mb-adrs">
                                                    <td><?php echo showOtherLangText('Supplier Invoice'); ?> # </td>
                                                    <td><?php echo $payInfoRow['supplierInvoice'] ?></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Payment To'); ?></td>
                                                    <td>
                                                        <p class="mt-2"><?php echo $payInfoRow['supplierName'] ?></p>
                                                    </td>
                                                </tr>


                                                <tr class="mb-adrs" style="vertical-align: top;">
                                                    <td><?php echo showOtherLangText('Supplier Address'); ?></td>
                                                    <td>
                                                        <?php echo nl2br($payInfoRow['supplierAddress']) ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Email'); ?></td>
                                                    <td>
                                                        <?php echo $payInfoRow['supplierEmail'] ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Phone'); ?></td>
                                                    <td>
                                                        <?php echo $payInfoRow['supplierPhone'] ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="divider-1"></div>


                                    <div class="invTotal">
                                        <table width="100%">
                                            <tbody>
                                                <?php 
            if ($orderRow['ordCurId'] > 0)
                { ?>
                                                <tr class="payDetail">
                                                    <td class="pb-1"><?php echo showOtherLangText('Total Amount'); ?>
                                                        (<?php echo $curDetail['curCode'] ?>)</td>
                                                    <td class="pb-1">
                                                        <?php echo showOtherCur($netTotalAmtOther, $orderRow['ordCurId']); ?>
                                                    </td>
                                                </tr>
                                                <tr class="payDetail">
                                                    <td><?php echo showOtherLangText('Total Amount'); ?>
                                                        (<?php echo $getDefCurDet['curCode'] ?>)</td>
                                                    <td><?php showPrice($netTotalAmt,$getDefCurDet['curCode']); ?></td>
                                                </tr>
                                                <?php  } else { ?>
                                                <tr class="payDetail">
                                                    <td><?php echo showOtherLangText('Total Amount'); ?>
                                                        (<?php echo $getDefCurDet['curCode'] ?>)</td>
                                                    <td><?php showPrice($netTotalAmt,$getDefCurDet['curCode']); ?></td>
                                                </tr>

                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <input type="hidden" class="form-control" name="TotalAmt" id="TotalAmt"
                                        value="90.9088" autocomplete="off">
                                    <input type="hidden" class="form-control" name="currencyId" id="currencyId" value=""
                                        autocomplete="off">

                                    <div class="paySelect">
                                        <table cellpadding="0" cellspacing="0" width="100%" style="margin-top: 20px;">
                                            <tbody class="form-info">
                                                <tr class="" style="height: 48px;">
                                                    <td><?php echo showOtherLangText('Payment type'); ?></td>
                                                    <td><?php echo $payModeRow['modeName'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo showOtherLangText('Account'); ?>

                                                    </td>
                                                    <?php
                            $sqlSet = " SELECT * FROM  tbl_accounts WHERE id='".$paymentRow['bankAccountId']."' AND account_id = '".$_SESSION['accountId']."' ";
                            $resultSet = mysqli_query($con, $sqlSet);
                            $accRow = mysqli_fetch_array($resultSet);

                            $balanceAmt = $accRow['balanceAmt'];

                            if ($currencyCode =='')
                            {
                            $totalPaidAmt = trim($paymentRow['amount'],'$');

                            }else{

                            $totalPaidAmt = trim($paymentRow['amount'],$currencyCode);
                            }

                            $totalPaidAmt = str_replace(',', '', $totalPaidAmt);

                            if ($paymentRow['paymentStatus']==0)
                            {
                            $totalBalanceAmt = ($balanceAmt-$totalPaidAmt);

                            }else{

                            $totalBalanceAmt = ($balanceAmt);

                            }

                            ?>
                                                    <td><?php echo $accRow['accountName'] ?></td>
                                                </tr>


                                                <tr>
                                                    <td><?php echo showOtherLangText('Account No.'); ?></td>
                                                    <td><?php echo $accRow['accountNumber'] ?></td>
                                                </tr>
                                                <?php
                                if ($paymentRow['currencyId'] ==0) 
                                { ?>
                                                <tr>
                                                    <td><?php echo showOtherLangText('Balance'); ?></td>
                                                    <td><?php showPrice($totalBalanceAmt,$getDefCurDet['curCode']) ?>
                                                    </td>
                                                </tr>
                                                <?php }else{ ?>
                                                <tr>
                                                    <td><?php echo showOtherLangText('Balance'); ?></td>
                                                    <td><?php echo showOtherCur($totalBalanceAmt, $paymentRow['currencyId']) ?>
                                                    </td>
                                                </tr>

                                                <?php } ?>
                                                <tr>
                                                    <td><?php echo showOtherLangText('Currency'); ?> </td>
                                                    <?php  
                    $sqlSet = " SELECT * FROM tbl_currency WHERE id='".$paymentRow['currencyId']."'  AND account_id = '".$_SESSION['accountId']."' ";
                        $resultSet = mysqli_query($con, $sqlSet);
                        $currRow = mysqli_fetch_array($resultSet);

                    if ($currRow['id']==0) 
                    { ?>
                                                    <td><?php echo $getDefCurDet['currency'] ?></td>
                                                    <?php } else { ?>
                                                    <td><?php echo $currRow['currency'] ?></td>
                                                    <?php } ?>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Amount'); ?></td>
                                                    <td><?php echo $paymentRow['amount'];?></td>
                                                </tr>

                                            </tbody>
                                        </table>

                                    </div>

                                    <?php
if ($paymentRow['paymentStatus']==1) 
{ ?>
                                    <div class="allBtn my-3 d-flex justify-content-between align-items-center w-100">
                                        <div>
                                            <button class="btn wht-btn" type="button"
                                                onClick="window.location.href='history.php'"><?php echo showOtherLangText('Back'); ?></button>
                                        </div>

                                        <div>
                                            <a id="refundBtn" href="javascript:void(0)" data-bs-toggle="modal"
                                                data-bs-target="#refund_model" class="btn final-btn" type="submit"
                                                name="submitBtn"><?php echo showOtherLangText('Refund'); ?></a>
                                        </div>
                                    </div>
                                    <?php }else{ ?>
                                    <div class="allBtn my-3 d-flex justify-content-between align-items-center w-100">
                                        <div>
                                            <button class="btn wht-btn" type="button"
                                                onClick="redirectToParentPage()"><?php echo showOtherLangText('Cancel'); ?></button>
                                        </div>

                                        <div>
                                            <button class="btn final-btn" onClick="redirectToHistory()" type="button"
                                                name="submitBtn"><?php echo showOtherLangText('Done'); ?></button>
                                        </div>
                                    </div>
                                    <?php  }    ?>
                                </form>

                                <!-- start of add fee (item/order level) form -->
                                <!-- Modal box form start from here -->
                                <form action="" name="addServiceFrm" id="addServiceFrm" method="post"
                                    autocomplete="off">
                                    <!-- The Modal 1 -->
                                    <div id="myModal" class="modal">
                                        <!-- Modal content -->
                                        <div class="modal-content openModal">
                                            <div class="mdlHead-Popup">
                                                <p>Add service item</p>
                                                <span class="close">×</span>
                                            </div>

                                            <table cellpadding="0" cellspacing="0" width="100%">

                                                <tbody>
                                                    <tr>
                                                        <td>

                                                            <table cellpadding="0" cellspacing="0">

                                                                <tbody>
                                                                    <tr>
                                                                        <td class="feePopup">Service name </td>
                                                                        <td><input type="text" class="form-control"
                                                                                name="itemName" id="itemName" value=""
                                                                                autocomplete="off">
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="feePopup">Amount</td>
                                                                        <td><input type="text" class="form-control"
                                                                                name="itemFeeAmt" id="feeAmt" value=""
                                                                                autocomplete="off">
                                                                        </td>
                                                                    </tr>


                                                                    <tr>
                                                                        <td class="feePopup">Unit</td>
                                                                        <td><input type="text" class="form-control"
                                                                                name="unit" id="unit"
                                                                                autocomplete="off">
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>

                                                        </td>


                                                    </tr>
                                                </tbody>
                                            </table>

                                            <div>
                                                <div class="feeSave feeNrm">
                                                    <input type="checkbox" id="visibility" name="visibility" value="1">
                                                    <label for="visibility">
                                                        Save to fixed service item list</label><br>
                                                </div>
                                            </div>



                                            <div>
                                                <div><button class="btn wht-btn" type="submit">Add</button>&nbsp; &nbsp;
                                                    <button class="btn wht-btn" id="backBtn" type="button">Back</button>
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </form>


                                <form action="" name="addFeeFrm" id="addFeeFrm" method="post" autocomplete="off">

                                    <!-- The Modal 2 -->
                                    <div id="myModal2" class="modal">

                                        <!-- Modal content -->
                                        <div class="modal-content openModal">
                                            <div class="mdlHead-Popup">
                                                <p>Add fee</p>
                                                <span class="close2">×</span>
                                            </div>


                                            <table cellpadding="0" cellspacing="0" width="100%">

                                                <tbody>
                                                    <tr>
                                                        <td>

                                                            <table cellpadding="0" cellspacing="0">


                                                                <tbody>
                                                                    <tr>
                                                                        <td class="feePopup">Fee name </td>
                                                                        <td><input type="text" class="form-control"
                                                                                name="feeName" id="feeName" value=""
                                                                                style="width:250px;" autocomplete="off">
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="feePopup">Fee type </td>
                                                                        <td>
                                                                            <select class="form-control" name="feeType"
                                                                                id="typeOfFee">
                                                                                <option value="2">
                                                                                    Fixed fee </option>
                                                                                <option value="3">
                                                                                    Percentage fee </option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="feePopup feeFixed" id="feeFixed">
                                                                            <span>Fee amount</span>
                                                                        </td>
                                                                        <td class="feePopup feePercent" id="feePercent"
                                                                            style="display: none;">
                                                                            <span>Fee percentage %</span>
                                                                        </td>
                                                                        <td>
                                                                            <div class="stepper "><input type="number"
                                                                                    class="form-control stepper-input"
                                                                                    name="amt" id="amt" value=""
                                                                                    style="width:250px;"
                                                                                    autocomplete="off"><span
                                                                                    class="stepper-arrow up">Up</span><span
                                                                                    class="stepper-arrow down">Down</span>
                                                                            </div>

                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>


                                            <div class="fxdFee">
                                                <div class="feeSave">
                                                    <input type="checkbox" name="feeType" id="feeType" value="1">
                                                    <label for="feeType">This is a tax fee</label><br>
                                                </div>
                                                <div class="feeSave feeNrm">
                                                    <input type="checkbox" id="visibility" name="visibility" value="1">
                                                    <label for="visibility">Save to fixed service item list </label><br>
                                                </div>

                                            </div>


                                            <!-- Modal form add/back button -->
                                            <div class="modal-footer" style="border-top: 1px solid #ffffff;">
                                                <button class="btn wht-btn" type="submit"
                                                    name="AddBtn">Add</button>&nbsp; &nbsp;
                                                <button class="btn wht-btn" type="button" id="backBtn2">Back</button>
                                            </div>

                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    </div>
    <form action="" method="post" id="frm" name="frm">
        <input type="hidden" name="orderId" value="<?php echo $_GET['orderId'] ?>">
        <!-- View ckecknox Popup Start -->
        <div class="modal" tabindex="-1" id="refund_model" aria-labelledby="refund_model" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md site-modal">
                <div class="modal-content ">
                    <div>
                        <div class="mdlHead-Popup d-flex justify-content-between align-items-center">
                            <span>
                                <strong><?php echo showOtherLangText('Please select one of the following to refund'); ?>:</strong>
                            </span>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <br>

                        <div class="form-group  d-flex payModal-Flex">
                            <input type="radio" name="refund" value="refundAmt" id="refundAmt">
                            <div style="color: #000; width: 94%;">
                                <p><?php echo showOtherLangText('Refund the amount of ') . $paymentRow['amount'] . showOtherLangText(' to account only.'); ?>
                                </p>
                            </div>
                        </div>
                        <br> <br>
                        <p>
                            <button type="submit" name="approveBtn" id="approveBtn"
                                class="btn btn-secondary dropdown-toggle fs-13 py-2"
                                style="border:1px solid #7a89ff; background-color: #7a89ff; color: #fff;"><?php echo showOtherLangText('Approve'); ?></button>

                            <a class="btn btn-secondary dropdown-toggle fs-13 py-2" id="cancelBtn"
                                style="border:1px solid #7a89ff; background-color: #7a89ff; color: #fff;"><?php echo showOtherLangText('Cancel'); ?></a>


                        </p>

                    </div>

                </div>

            </div>
        </div>
    </form>
    <!-- View checkbox Popup End -->
    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
    <!-- Links for datePicker and dialog popup -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script type="text/javascript" src="cdn_js/jquery-ui-1.12.1.js"></script>
    <script>
    $(function() {
        $(".datepicker").datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $(".date_type").on("click", "a", function(e) {
            var $this = $(this).parent();
            $("#dateTypeText").text($this.data("value"));
            $("#dateType").val($this.data("id"));
            $('#frm').submit();
        });

        $(".type_dropdown").on("click", "a", function(e) {
            var $this = $(this).parent();
            $("#TypeText").text($this.data("value"));
            $("#ordType").val($this.data("id"));
            $('#frm').submit();
        });

        $(".referto_dropdown").on("click", "a", function(e) {
            var $this = $(this).parent();
            $("#refertotext").text($this.data("value"));
            $("#suppMemStoreId").val($this.data("id"));
            $('#frm').submit();
        });

        $(".status_type").on("click", "a", function(e) {
            var $this = $(this).parent();
            $("#statusText").text($this.data("value"));
            $("#statusType").val($this.data("id"));
            $('#frm').submit();
        });

        $(".account_dropdown").on("click", "a", function(e) {
            var $this = $(this).parent();
            $("#accountTxt").text($this.data("value"));
            $("#accountNo").val($this.data("id"));
            $('#frm').submit();
        });

        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('dateType')) {
            var dateTypeID = urlParams.get('dateType');
            if (dateTypeID !== '') {
                $("#dateTypeText").text($(".date_type .selected").text());
            }
        }
        if (urlParams.has('ordType')) {
            var ordTypeID = urlParams.get('ordType');
            if (ordTypeID !== '') {
                $("#TypeText").text($(".type_dropdown .selected").text());
            }
        }
        if (urlParams.has('suppMemStoreId')) {
            var suppMemStoreId = urlParams.get('suppMemStoreId');
            if (suppMemStoreId !== '') {
                $("#refertotext").text($(".referto_dropdown .selected").text());
            }
        }
        if (urlParams.has('statusType')) {
            var statusType = urlParams.get('statusType');
            if (statusType !== '') {
                $("#statusText").text($(".status_type .selected").text());
            }
        }
        if (urlParams.has('accountNo')) {
            var accountNo = urlParams.get('accountNo');
            if (accountNo !== '') {
                $("#accountTxt").text($(".account_dropdown .selected").text());
            }
        }

    });

    function getDelNumb(delOrderId) {

        $("#dialog").dialog({
            autoOpen: false,
            modal: true,
            //title     : "Title",
            buttons: {
                '<?php echo showOtherLangText('Yes') ?>': function() {
                    //Do whatever you want to do when Yes clicked
                    $(this).dialog('close');
                    window.location.href = 'history.php?delOrderId=' + delOrderId;
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

    $('.date-box-search').click(function() {

        $('#frm').submit();

    });
    </script>
    <script>
    function redirectToHistory() {
        var pageName = "history.php?orderId=<?php echo $_GET['orderId'];?>&paymentStatus=1";
        window.location.href = pageName
    }
    var cancelBtn = document.getElementById("cancelBtn");
    cancelBtn.onclick = function() {
        refund_model.style.display = "none";
    }
    </script>
    <script>
    function redirectToParentPage() {
        var pageName = "supplierPaymentDetail.php?orderId=<?php echo $_GET['orderId'];?>&cancelPayment=1";
        window.location.href = pageName
    }
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    // Find all elements with the class 'grand-total'
    const grandTotalElements = document.querySelectorAll('.grand-total');

    if (grandTotalElements.length > 0) {
        grandTotalElements[0].setAttribute('style', 'background: #7A89FF !important; color: white !important;');
    }
});
</script>
    <div id="dialog" style="display: none;">
        <?php echo showOtherLangText('Are you sure to delete this record?') ?>
    </div>
</body>

</html>