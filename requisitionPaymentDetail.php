<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

if ( !isset($_SESSION['adminidusername']))
{
echo' <script>window.location = "login.php"</script>';  
}

checkRequisitionActions($_GET);

if (isset($_GET['orderId']) && $_GET['cancelPayment']==1) 
{
    $updateQry = " UPDATE tbl_req_payment SET 
    currencyId = '',
    ordTotAmt = '',
    bankAccountId = '',
    amount = '',
    paymentType = '',
    paymentStatus = ''

    WHERE orderId = '".$_GET['orderId']."' ";
    mysqli_query($con, $updateQry);
}


if( isset($_GET['orderId']))
{

$sql = "SELECT tp.*, od.price ordPrice, IF(u.name!='',u.name,tp.unitC) countingUnit, od.qty ordQty, od.totalAmt, od.factor ordFactor FROM tbl_order_details od 
INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
LEFT JOIN tbl_units u ON(u.id = tp.unitC) AND u.account_id = tp.account_id
WHERE od.ordId = '".$_GET['orderId']."'  AND tp.account_id = '".$_SESSION['accountId']."'  ";
$ordDetResult = mysqli_query($con, $sql);

$cmd="SELECT * FROM tbl_orders WHERE id='".$_GET['orderId']."'   AND account_id = '".$_SESSION['accountId']."' ";
$orderQry = mysqli_query($con, $cmd);
$orderRow = mysqli_fetch_array($orderQry);

//fetch currency detail
$curDetail = getCurrencyDet($orderRow['ordCurId']);

$sql= " SELECT * FROM tbl_req_payment WHERE orderId='".$orderRow['id']."' AND account_id = '".$_SESSION['accountId']."' order by id limit 1 ";
$resultSet=mysqli_query($con, $sql);
$paymentRow=mysqli_fetch_array($resultSet);

//check if record does not exist for this orderId then insert of records in tbl_req_payment like (id & orderId only) for first time.
if (count($paymentRow) == 0) 
{   
$insertQry = "INSERT INTO `tbl_req_payment` SET account_id = '".$_SESSION['accountId']."', orderId = '".$_GET['orderId']."' ";
mysqli_query($con, $insertQry);
$invNumber = mysqli_insert_id($con);

    

}else{

//check if record exist then assign invoiceNumber 
$sql= " SELECT * FROM tbl_req_payment WHERE orderId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' order by id limit 1 ";
$resultSet=mysqli_query($con, $sql);
$paymentRow=mysqli_fetch_array($resultSet);
$invNumber = $paymentRow['id'];
}

//fetch contact detail of payment
$qry = "SELECT * FROM tbl_requisition_payment_info WHERE orderId='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."' "; 
$reqPayInfoResult = mysqli_query($con, $qry);
$reqPayInfo = mysqli_fetch_array($reqPayInfoResult);

if (isset($reqPayInfo['invoiceNumber']))
{

$invoiceNumber = $reqPayInfo['invoiceNumber'];

}else{

$invoiceNumber = $invNumber;

}

}

//show a popup on history page when user click on issueInvoice button
if (isset($_POST['issueInvoice']) && $_POST['issueInvoice']==1)
{
$updateQry = " UPDATE `tbl_req_payment` SET `issueInvoice` = '1' 
WHERE orderId = '".$_POST['orderId']."' ";
mysqli_query($con, $updateQry);
}


//Add item charges in list
if( isset($_GET['id']) && $_GET['id'] > 0  && $_GET['feeType']=='openFixedFee' )
{
    editCustomCharge($_GET['orderId'],1, $_GET['id'], $orderRow['deptId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $orderDetRowOld = mysqli_fetch_array($res);

    requisitionTotalValue($_GET['orderId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $orderDetRow = mysqli_fetch_array($res);

    $diffPrice = ($orderDetRow['ordAmt'] - $orderDetRowOld['ordAmt']);
    $notes = 'Service Charge added(Price Diff: '.getPriceWithCur($diffPrice, $getDefCurDet['curCode']).' )';

    if($diffPrice != 0)
    {
        $qry = " INSERT INTO `tbl_order_journey` SET 
                `account_id` = '".$_SESSION['accountId']."',
                `orderId` = '".$_GET['orderId']."',
                `userBy`  = '".$_SESSION['id']."',
                `amount` = '".$orderDetRow['ordAmt']."',
                `otherCur` = '".$orderDetRow['ordCurAmt']."',
                `otherCurId` = '".$orderDetRow['ordCurId']."',
                `orderType` = '".$orderDetRow['ordType']."',
                `ordDateTime` = '".date('Y-m-d h:i:s')."',
                `notes` = '".$notes."',
                `action` = 'Invoice' ";
                mysqli_query($con, $qry);
    }
}

//Add order charges in list
if( isset($_GET['id']) && $_GET['id'] > 0 && $_GET['feeType']=='openFixedPerFee' )
{
    editCustomCharge($_GET['orderId'],3, $_GET['id'], $orderRow['deptId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $orderDetRowOld = mysqli_fetch_array($res);

    requisitionTotalValue($_GET['orderId']);    

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $orderDetRow = mysqli_fetch_array($res);

    $diffPrice = ($orderDetRow['ordAmt'] - $orderDetRowOld['ordAmt']);
    $notes = 'Order fee added(Price Diff: '.getPriceWithCur($diffPrice, $getDefCurDet['curCode']).' )';

    if($diffPrice != 0)
    {
        $qry = " INSERT INTO `tbl_order_journey` SET 
                `account_id` = '".$_SESSION['accountId']."',
                `orderId` = '".$_GET['orderId']."',
                `userBy`  = '".$_SESSION['id']."',
                `amount` = '".$orderDetRow['ordAmt']."',
                `otherCur` = '".$orderDetRow['ordCurAmt']."',
                `otherCurId` = '".$orderDetRow['ordCurId']."',
                `orderType` = '".$orderDetRow['ordType']."',
                `ordDateTime` = '".date('Y-m-d h:i:s')."',
                `notes` = '".$notes."',
                `action` = 'Invoice' ";
                mysqli_query($con, $qry);
    }
}


//member name fetch query
$sql = "SELECT dpt.name AS  departmentName, dpt.email AS departmentEmail, dpt.address AS departmentAddress, dpt.phone AS departmentPhone, o.* FROM tbl_orders o
LEFT JOIN tbl_deptusers dpt ON(dpt.id = o.recMemberId) AND dpt.account_id = o.account_id
WHERE o.account_id='".$_SESSION['accountId']."' AND o.id='".$_GET['orderId']."' ";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);
$invName = $row['departmentName'];
$email = $row['departmentEmail'];
$address = $row['departmentAddress'];
$phone = $row['departmentPhone'];

// echo '<pre>';
// print_r($_POST);
// exit;
//insert requesition contact details
if (isset($_POST['invoiceEmail']) && isset($_POST['invoiceAddress']) && isset($_POST['invoiceNumber']) && isset($_POST['invoiceName']))
{   
// echo 'hi'; exit;
//Update invoice Number in tbl_orders when user fill it by itself.
$upQry = " UPDATE tbl_orders SET `invNo` = '".$_POST['invoiceNumber']."' 
WHERE id='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."'  ";
mysqli_query($con, $upQry);

//check if orderId wise payment info exist in table tbl_requisition_payment_info then update else insert
$payInfoQry = " SELECT * FROM tbl_requisition_payment_info WHERE  orderId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' order by id "; 
$payInfoResult = mysqli_query($con, $payInfoQry);
$payInfoRow = mysqli_num_rows($payInfoResult);

if ($payInfoRow > 0)
{

$updatePayInfoQry = " UPDATE tbl_requisition_payment_info SET
`orderId` = '".$_GET['orderId']."',
`invoiceName` = '".$_POST['invoiceName']."', 
`invoiceNumber` = '".$_POST['invoiceNumber']."', 
`invoiceAddress` = '".$_POST['invoiceAddress']."', 
`invoiceEmail` = '".$_POST['invoiceEmail']."',
`invoicePhone` = '".$_POST['invoicePhone']."'

WHERE orderId ='".$_GET['orderId']."' ";

mysqli_query($con, $updatePayInfoQry); 

}
else
{

$sql = "INSERT INTO `tbl_requisition_payment_info` SET
`orderId` = '".$_GET['orderId']."',
`invoiceName` = '".$_POST['invoiceName']."', 
`invoiceNumber` = '".$_POST['invoiceNumber']."', 
`invoiceAddress` = '".$_POST['invoiceAddress']."', 
`invoiceEmail` = '".$_POST['invoiceEmail']."',
`invoicePhone` = '".$_POST['invoicePhone']."',
`account_id` = '".$_SESSION['accountId']."'  ";

mysqli_query($con, $sql);
}

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
        $chargeId = mysqli_insert_id($con);

        editCustomCharge($_GET['orderId'],1, $chargeId, $orderRow['deptId']);

        $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
        $res = mysqli_query($con, $sql);
        $orderDetRowOld = mysqli_fetch_array($res);

        requisitionTotalValue($_GET['orderId']);//update value of order/item level charge



            
        $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
        $res = mysqli_query($con, $sql);
        $orderDetRow = mysqli_fetch_array($res);
    
        $diffPrice = ($orderDetRow['ordAmt'] - $orderDetRowOld['ordAmt']);
        $notes = 'Service Item added(Price Diff: '.getPriceWithCur($diffPrice, $getDefCurDet['curCode']).' )';
    
        $qry = " INSERT INTO `tbl_order_journey` SET 
                `account_id` = '".$_SESSION['accountId']."',
                `orderId` = '".$_GET['orderId']."',
                `userBy`  = '".$_SESSION['id']."',
                `amount` = '".$orderDetRow['ordAmt']."',
                `otherCur` = '".$orderDetRow['ordCurAmt']."',
                `otherCurId` = '".$orderDetRow['ordCurId']."',
                `orderType` = '".$orderDetRow['ordType']."',
                `ordDateTime` = '".date('Y-m-d h:i:s')."',
                `notes` = '".$notes."',
                `action` = 'Invoice' ";
                mysqli_query($con, $qry);

        echo '<script>window.location = "requisitionPaymentDetail.php?page=history&action=pay&orderId='.$orderRow['id'].'"</script>';

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

    $chargeId = mysqli_insert_id($con);

    editCustomCharge($_GET['orderId'],3, $chargeId, $orderRow['deptId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $orderDetRowOld = mysqli_fetch_array($res);


    requisitionTotalValue($_GET['orderId']);//update value of order/item level charge

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $orderDetRow = mysqli_fetch_array($res);

    $diffPrice = ($orderDetRow['ordAmt'] - $orderDetRowOld['ordAmt']);
    $notes = 'Fee Added(Price Diff: '.getPriceWithCur($diffPrice, $getDefCurDet['curCode']).' )';

    $qry = " INSERT INTO `tbl_order_journey` SET 
            `account_id` = '".$_SESSION['accountId']."',
            `orderId` = '".$_GET['orderId']."',
            `userBy`  = '".$_SESSION['id']."',
            `amount` = '".$orderDetRow['ordAmt']."',
            `otherCur` = '".$orderDetRow['ordCurAmt']."',
            `otherCurId` = '".$orderDetRow['ordCurId']."',
            `orderType` = '".$orderDetRow['ordType']."',
            `ordDateTime` = '".date('Y-m-d h:i:s')."',
            `notes` = '".$notes."',
            `action` = 'Invoice' ";
            mysqli_query($con, $qry);

    echo '<script>window.location = "requisitionPaymentDetail.php?page=history&action=pay&orderId='.$orderRow['id'].'"</script>';

}


if( isset($_POST['submitBtn']) )
{

$account_id = $_SESSION['accountId']; 
$orderId=$_GET['orderId'];
$ordTotAmt=$_POST['totalAmount'];
$paymentType = $_POST['paymentType'];
$bankAccountId= $_POST['accountId']; 
$currencyId = $_POST['currencyId'];
$status="0";
$amount= $_POST['amount'];
$timenow=date('Y-m-d H:i:s');


$insQry = " UPDATE tbl_orders SET paymentStatus ='".$status."' WHERE id='".$_GET['orderId']."' ";
mysqli_query($con, $insQry);


$sql= " SELECT * FROM tbl_req_payment WHERE orderId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' order by id limit 1 ";
$resultSet=mysqli_query($con, $sql);
$paymentRow=mysqli_fetch_array($resultSet);

if (count($paymentRow) > 0)
{
$updatePayQry = " UPDATE tbl_req_payment SET 
account_id = '".$account_id."',
orderId = '".$orderId."',
bankAccountId = '".$bankAccountId."',
currencyId = '".$currencyId."',
ordTotAmt = '".$ordTotAmt."',
amount = '".$amount."',
paymentType = '".$paymentType."',
paymentStatus = '".$status."',
paymentDateTime = '".$timenow."'
WHERE orderId = '".$_GET['orderId']."' ";
mysqli_query($con, $updatePayQry);



    $sql = " SELECT * FROM tbl_orders WHERE id = '".$orderId."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $orderDetRow = mysqli_fetch_array($res);

    $curDetail = getCurrencyDet($currencyId);
    
    $qry = " INSERT INTO `tbl_order_journey` SET 
        `account_id` = '".$_SESSION['accountId']."',
        `orderId` = '".$orderId."',
        `userBy`  = '".$_SESSION['id']."',
        `amount` = '".$orderDetRow['ordAmt']."',
        `orderType` = '".$orderDetRow['ordType']."',
        `ordDateTime` = '".date('Y-m-d h:i:s')."',
        `notes` = 'Paid',
        `action` = 'Invoice Payment Done' ";
        mysqli_query($con, $qry);

}

echo '<script>window.location ="requisitionSuccessPayment.php?orderId='.$_GET['orderId'].'"</script>';//redirect page

}//end of form submition

//delete item level / order level charges
if(isset($_GET['delId']) && $_GET['orderId'])
{ 

        $sql=" SELECT * FROM tbl_order_details WHERE ordId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' and id='".$_GET['delId']."' ";
        $sqlSet= mysqli_query($con, $sql);  
        $OrdDetRow = mysqli_fetch_array($sqlSet);

        $sql= " DELETE FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' and customChargeId='".$OrdDetRow['customChargeId']."' and customChargeType='".$OrdDetRow['customChargeType']."' ";
        $resultSet= mysqli_query($con, $sql);


        $sql= " DELETE FROM tbl_order_details WHERE id= '".$_GET['delId']."' AND account_id = '".$_SESSION['accountId']."'   ";
        $resultSet= mysqli_query($con, $sql);

        $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
        $res = mysqli_query($con, $sql);
        $orderDetRowOld = mysqli_fetch_array($res);


        requisitionTotalValue($_GET['orderId']);

        $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
        $res = mysqli_query($con, $sql);
        $orderDetRow = mysqli_fetch_array($res);

        $diffPrice = ($orderDetRow['ordAmt'] - $orderDetRowOld['ordAmt']);
        $notes = 'Order fee deleted(Price Diff: '.getPriceWithCur($diffPrice, $getDefCurDet['curCode']).' )';

        if($diffPrice != 0)
        {
            $qry = " INSERT INTO `tbl_order_journey` SET 
                    `account_id` = '".$_SESSION['accountId']."',
                    `orderId` = '".$_GET['orderId']."',
                    `userBy`  = '".$_SESSION['id']."',
                    `amount` = '".$orderDetRow['ordAmt']."',
                    `otherCur` = '".$orderDetRow['ordCurAmt']."',
                    `otherCurId` = '".$orderDetRow['ordCurId']."',
                    `orderType` = '".$orderDetRow['ordType']."',
                    `ordDateTime` = '".date('Y-m-d h:i:s')."',
                    `notes` = '".$notes."',
                    `action` = 'Payment' ";
                    mysqli_query($con, $qry);
        }


        echo '<script>window.location = "requisitionPaymentDetail.php?page=history&action=pay&orderId='.$_GET['orderId'].'"</script>';

}//end 

?>
<!DOCTYPE html>
<html lang="en">

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

    .issueDtl {
        min-height: 160px !important;
    }

    .accntDtl {
        max-height: 160px !important;
        overflow-y: scroll;
    }

    .paySelect tr {
        height: 35px;
    }

    @media (min-width:1137px) {
        /* 29 date tabel css */

        .hisTypclm,
        .hisRefrclm {
            width: initial !important;
        }

        .hisTypclm .dropdown-toggle,
        .hisRefrclm .dropdown-toggle {
            width: fit-content !important;
        }

        .hisStatusclm .dropdown-toggle,
        .hisAcntclm .dropdown-toggle {
            width: fit-content !important;
        }

        .hisStatusclm,
        .hisAcntclm {
            width: 25% !important;
        }


        .hisValclm {
            width: 7% !important;
        }

        .numItmclm {
            width: 15% !important;
        }

        .srHisclm {
            min-width: fit-content;
        }
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
                    <!-- invoice Details Start -->
                    <div id="invoice-Details" class="payment-status-left payment-pending position-relative">
                        <div class="border-line"></div>
                        <div class="modal-body p-4 p-xl-5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="f-01 mb-0 payment-status-text"><?php echo showOtherLangText('PENDING'); ?>
                                    </p>
                                    <p class="f-01"><?php echo showOtherLangText('INVOICE'); ?></p>
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
                                    <!-- <img src="" alt=""> -->
                                </div>
                            </div>

                            <div class="mt-4 row g-0 payment-tabel-header">
                                <div class="col-sm-6">
                                    <table class="table1 table01">
                                        <tbody>
                                            <tr>
                                                <td class="font-wt">
                                                    <?php echo showOtherLangText('Invoice'); ?> #
                                                </td>
                                                <td id="leftInvoiceNum"><?php echo getinvoiceNumber($invoiceNumber);?></td>
                                            </tr>

                                            <tr>
                                                <td class="font-wt"><?php echo showOtherLangText('Task'); ?> #</td>
                                                <td><?php echo $orderRow['ordNumber'] ?></td>
                                            </tr>

                                            <tr>
                                                <td class="font-wt"><?php echo showOtherLangText('Date'); ?> #
                                                </td>
                                                <td><?php echo $orderRow['ordDateTime']?></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <?php 
                                    $sql = " SELECT * FROM tbl_country WHERE id = '".$clientResultRow['country']."' ";
                                    $resSet = mysqli_query($con, $sql);
                                    $resultRow = mysqli_fetch_array($resSet); 
                                ?>
                                <div class="col-sm-6">
                                    <table class="w-100">
                                        <tbody class="table1 table01 fl-right cmp-dtl  text-start text-sm-end">
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
                            <div class="">
                                <p class="f-02 mb-0"><?php echo showOtherLangText('Invoice To'); ?>: </p>
                                <p id="name" class="f-03 mb-0">
                                    <?php echo isset($reqPayInfo['invoiceName']) ? $reqPayInfo['invoiceName'] : $invName; ?>
                                </p>
                            </div>
                            <div class="">
                                <p id="address" class="f-02 mb-0">
                                    <?php echo isset($reqPayInfo['invoiceAddress']) ? $reqPayInfo['invoiceAddress'] : $address ;?>
                                </p>
                                <p id="email" class="f-03 mb-0">
                                    <?php echo isset($reqPayInfo['invoiceEmail']) ? $reqPayInfo['invoiceEmail'] : $email ; ?>
                                </p>
                                <p id="phone" class="f-03 mb-0">
                                    <?php echo isset($reqPayInfo['invoicePhone']) ? $reqPayInfo['invoicePhone'] : $phone ; ?>
                                </p>
                            </div>
                            <br>

                            <div class="table-responsive mt-4 mt-md-4">
                                <table class="modal-table fs-12 w-100 payment__table">
                                    <thead style="background: #A9B0C0 !important;">
                                        <tr class="tr-bg-1">
                                            <th>#</th>
                                            <th style="width: 30%;"><?php echo showOtherLangText('Item'); ?></th>
                                            <th><?php echo showOtherLangText('Unit'); ?></th>
                                            <th><?php echo showOtherLangText('Quantity'); ?></th>
                                            <th class="th-bg-1">
                                                <?php echo showOtherLangText('Price').' '.$getDefCurDet['curCode']; ?></th>
                                            <th class="th-bg-1">
                                                <?php echo showOtherLangText('Total').' '.$getDefCurDet['curCode']; ?></th>
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
                                            <td><a title="<?php echo showOtherLangText('Delete') ?>"
                                                    href="javascript:void(0)"
                                                    onClick="getDelNumb('<?php echo $showCif['id'];?>', '<?php echo $_GET['orderId'];?>');"
                                                    style="color:#808080" class="glyphicon glyphicon-trash"><span
                                                        class="dlTe"></span></a></td>
                                            <td style="width: 30%;"><?php echo $showCif['itemName'];?></td>
                                            <td><?php echo $showCif['unit'];?></td>
                                            <td>1</td>
                                            <td><?php showPrice($showCif['amt'],$getDefCurDet['curCode']);?></td>
                                            <td><?php showPrice($showCif['amt'],$getDefCurDet['curCode']);?></td>
                                        </tr>
                                        <?php  } ?>
                                        <?php 
                                            $sum=0;
                                            while($row = mysqli_fetch_array($ordDetResult)) 
                                            {
                                            $x++;
                                        ?>
                                        <tr>
                                            <td><?php echo $x; ?></td>
                                            <td style="width: 30%;"><?php echo $row['itemName'] ?></td>
                                            <td><?php echo $row['countingUnit'] ?></td>
                                            <td><?php echo $row['ordQty'] ?></td>
                                            <td><?php showPrice($row['ordPrice'],$getDefCurDet['curCode']);?></td>
                                            <td><?php showPrice($row['ordQty'] * $row['ordPrice'],$getDefCurDet['curCode']) ?>
                                            </td>
                                        </tr>

                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="divider-blue"></div>
                             
                            <div class="tabel-body-p-footer  gap-3 flex-wrap mt-3 mt-md-4">
                                <div class="table1 col-md-4">
                                    <p class="f-02 mb-2 mb-lg-0"><?php echo showOtherLangText('Payment Method'); ?>:</p>
                                    <!-- <p class="f-03 mb-0">Cash</p>
                                    <p class="f-03 mb-0">Main Safe USD</p> -->
                                </div>

                                <!-- grand totale  here -->
                                <table class="grand-total-tabel col">
                                    <?php 
                                    //get the sum of all product and item level charges  
                                        $sqlSet="SELECT SUM(totalAmt) as sum1 from tbl_order_details where ordId='".$_GET['orderId']."'   AND account_id = '".$_SESSION['accountId']."' AND (customChargeType='1' OR customChargeType='0')";
                                        $resultSet = mysqli_query($con, $sqlSet);
                                        $chargeRow = mysqli_fetch_array($resultSet);    
                                        $chargePrice=$chargeRow['sum1'];                    


                                        $ordCount="SELECT * from tbl_order_details where ordId='".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."' AND customChargeType='2' ";
                                        $ordCountResult = mysqli_query($con, $ordCount);
                                        $ordCountRow = mysqli_num_rows($ordCountResult);

                                        if ($ordCountRow > 0) { 
                                    ?>
                                    <tr>
                                        <td><?php echo showOtherLangText('Sub Total'); ?></td>
                                        <td><?php showPrice($chargePrice,$getDefCurDet['curCode']);?></td>

                                    </tr>


                                    <?php
                                            }
                                    //Starts order level fixed discount charges
                                            $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
                                            INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                                            WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

                                            $ordDetResult = mysqli_query($con, $sql);

                                            $fixedCharges = 0;

                                            $sql = " SELECT SUM(od.totalAmt) AS totalFixedCharges, tp.feeName FROM tbl_order_details od 
                                            INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                                            WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName   ";
                                            $sumQry = mysqli_query($con, $sql);
                                            $totalSum= mysqli_fetch_array($sumQry);

                                            $totalFixedCharges=$totalSum['totalFixedCharges'];


                                    while($row = mysqli_fetch_array($ordDetResult))//show here order level charges 
                                    {
                                    ?>
                                    <tr>
                                        <td class="d-flex gap-1"><a title="<?php echo showOtherLangText('Delete') ?>"
                                                href="javascript:void(0)"
                                                onClick="getDelNumb('<?php echo $row['id'];?>', '<?php echo $_GET['orderId'];?>');"
                                                style="color:#808080" class="glyphicon glyphicon-trash"><span
                                                    class="dlTe"></span></a>

                                            &nbsp;<?php echo $row['feeName'];?></td>
                                        <td><?php showprice($fixedCharges,$getDefCurDet['curCode']);?></td>
                                    </tr>
                                    <?php

                          
} //Ends order lelvel fixed discount charges
?>
                                    <?php
//Start order level discoutns

$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
$ordDetResult = mysqli_query($con, $sql);

$perCharges = 0;
$itemIds = [];
$totalCharges = 0;
while($row = mysqli_fetch_array($ordDetResult))//show here order level charges
{
$itemIds[] = $row['customChargeId'];              
$totalCharges = $row['totalAmt'];
$perCharges += $row['totalAmt'];

$discountPercent=$chargePrice*$totalCharges/100;
$totalDiscountPercent=$chargePrice*$perCharges/100;
if($row)
{
    ?>
                                    <tr>
                                        <td class="d-flex gap-1"><a title="<?php echo showOtherLangText('Delete') ?>"
                                                href="javascript:void(0)"
                                                onClick="getDelNumb('<?php echo $showCif['id'];?>', '<?php echo $_GET['orderId'];?>');"
                                                style="color:#808080" class="glyphicon glyphicon-trash"><span
                                                    class="dlTe"></span></a>

                                            &nbsp;<?php echo $row['feeName'];?>
                                            <?php echo $row['totalAmt'] ?> %</td>
                                        <td><?php showprice($discountPercent,$getDefCurDet['curCode']); ?>
                                        </td>
                                    </tr>

                                    <?php
}
}//End of order level discount

//Starts order level tax discount charges
$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
$ordDetResult = mysqli_query($con, $sql);

$taxCharges = 0;
while($row = mysqli_fetch_array($ordDetResult))//show here order level charges
{
$tax = $row['price'];

$taxCharges=(($chargePrice+$totalFixedCharges+$totalDiscountPercent)*$tax/100);
$totalTaxCharges += (($chargePrice+$totalFixedCharges+$totalDiscountPercent)*$tax/100);
?>
                                    <tr>
                                        <td class="d-flex gap-1"><a title="<?php echo showOtherLangText('Delete') ?>"
                                                href="javascript:void(0)"
                                                onClick="getDelNumb('<?php echo $row['id'];?>', '<?php echo $_GET['orderId'];?>');"
                                                style="color:#808080" class="glyphicon glyphicon-trash"><span
                                                    class="dlTe"></span></a>

                                            &nbsp;<?php echo $row['feeName'];?>
                                            <?php echo $row['price'] ?> %</td>
                                        <td><?php showprice($taxCharges,$getDefCurDet['curCode']) ?></td>
                                    </tr>
                                    <?php
} //Ends order lelvel tax discount charges

$sqlSet = "SELECT SuM(totalAmt) AS totalFixedDiscount FROM tbl_order_details od 
INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
WHERE od.ordId = '".$_GET['orderId']."'   AND od.account_id = '".$_SESSION['accountId']."' and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";
$resultSet = mysqli_query($con, $sqlSet);
$fixedDiscountRow = mysqli_fetch_array($resultSet); 
$totalFixedDiscount= $fixedDiscountRow['totalFixedDiscount'];

$netTotalAmt= ($chargePrice+ $totalTaxCharges+$totalDiscountPercent+$totalFixedDiscount);
?>



                                    <tr class="grand-total">
                                        <th class="px-3"><?php echo showOtherLangText('Grand Total'); ?></th>
                                        <th class="px-3"><?php showprice($netTotalAmt,$getDefCurDet['curCode']); ?></th>
                                    </tr> 
                                </table>

                            </div>

                            <br>
                            <br>
                            <br>
                        </div>
                    </div>
                    <!-- invoice Detail End -->

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
                                                    <td><?php echo showOtherLangText('Invoice'); ?> #</td>
                                                    <td id="rightInvoiceNum"><?php echo getinvoiceNumber($invoiceNumber);?></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Task'); ?> #</td>
                                                    <td><?php echo $orderRow['ordNumber'];?></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Date'); ?> #</td>
                                                    <td class="payDate"><?php echo $orderRow['ordDateTime']?></td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="inv-detail">
                                        <table cellpadding="0" cellspacing="0" width="100%">
                                            <tbody class="frm-info" style="line-height: 40px;">
                                                <tr class="mb-adrs">
                                                    <td><?php echo showOtherLangText('Invoice To'); ?></td>
                                                    <td>
                                                        <input type="text" class="form-control" name="invoiceName"
                                                            id="invoiceName" onchange="getVal();"
                                                            oninput="invNameValueChange()"
                                                            value="<?php echo isset($reqPayInfo['invoiceName']) ? $reqPayInfo['invoiceName'] : $invName; ?>"
                                                            autocomplete="off" />
                                                    </td>
                                                </tr>

                                                <tr class="mb-adrs">
                                                    <td><?php echo showOtherLangText('Address'); ?></td>
                                                    <td>
                                                        <textarea class="form-control" name="invoiceAddress"
                                                            id="invoiceAddress" onchange="getVal();" autocomplete="off"
                                                            placeholder="<?php echo showOtherLangText('Address') ?>"
                                                            oninput="addressValueChange()"><?php echo isset($reqPayInfo['invoiceAddress']) ? $reqPayInfo['invoiceAddress'] : $address ;?></textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Email'); ?></td>
                                                    <td>
                                                        <input type="text" class="form-control" name="invoiceEmail"
                                                            id="invoiceEmail" onchange="getVal();" autocomplete="off"
                                                            placeholder="<?php echo showOtherLangText('Email') ?>"
                                                            oninput="emailValueChange()"
                                                            value="<?php echo isset($reqPayInfo['invoiceEmail']) ? $reqPayInfo['invoiceEmail'] : $email ;?>" />
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Phone'); ?></td>
                                                    <td>
                                                        <input type="text" class="form-control" name="invoicePhone"
                                                            id="invoicePhone" onchange="getVal();" autocomplete="off"
                                                            placeholder="<?php echo showOtherLangText('Phone no') ?>"
                                                            oninput="phoneValueChange()"
                                                            value="<?php echo isset($reqPayInfo['invoicePhone']) ? $reqPayInfo['invoicePhone'] : $phone ;?>" />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="divider-1"></div>


                                    <div class="invTotal">
                                        <table>
                                            <tbody>
                                                <tr class="payDetail">
                                                    <td><?php echo showOtherLangText('Total Amount'); ?></td>
                                                    <td>
                                                        <input type="text"
                                                            style="width: 100%; cursor: text; text-align:center; background:none;"
                                                            class="form-control form-control-01" name="totalAmount"
                                                            id="totalAmount"
                                                            value="<?php showprice($netTotalAmt,$getDefCurDet['curCode']); ?>"
                                                            autocomplete="off" readonly="">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <input type="hidden" class="form-control" name="TotalAmt" id="TotalAmt"
                                        value="<?php echo $netTotalAmt ?>" autocomplete="off" />

                                    <input type="hidden" class="form-control" name="currencyId" id="currencyId" value=""
                                        autocomplete="off" />

                                    <div class="issueInvoice">
                                        <a class="btn splitBtn-button" href="javascript:void(0)"
                                            onClick="openPopup1('<?php echo $_GET['orderId'];?>'); getOnClickVal();"><?php echo showOtherLangText('Issue Invoice'); ?></a>
                                    </div>
                                    <!-- Dropdown menu -->
                                    <div class="dropdown mt-2">
                                        <button class="btn btn-2 dropdown-toggle  d-j-b" type="button"
                                            data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                            aria-expanded="false">
                                            <?php echo showOtherLangText('Add Fee'); ?> <i
                                                class="fa-solid fa-angle-down"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <!-- Default dropend button -->
                                                <div class="btn-group dropend dropdown-hover w-100">
                                                    <a type="button"
                                                        class="dropdown-item  dropdown-toggle dropdown-toggle-hover  d-j-b"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <?php echo showOtherLangText('Service Item'); ?> <i
                                                            class="fa-solid fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <!-- Dropdown menu links -->
                                                        <?php
                            $sql = " SELECT * FROM tbl_custom_items_fee WHERE visibility='1' AND account_id='".$_SESSION['accountId']."' ";
                            $result = mysqli_query($con, $sql);
                            while ($resultRow = mysqli_fetch_array($result)) 
                            {
                            echo "<li><a class='dropdown-item' tabindex='-1' href='requisitionPaymentDetail.php?page=history&orderId=".$orderRow['id']."&action=pay&feeType=openFixedFee&id=".$resultRow['id']." ' >".$resultRow['itemName']."</a></li>";
                            }
                            ?>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#new-service-item"
                                                    class="dropdown-item"
                                                    href="javascript:void(0)"><?php echo showOtherLangText('New Service Item'); ?></a>
                                            </li>

                                            <li>
                                            <li>
                                                <!-- Default dropend button -->
                                                <div class="btn-group dropend dropdown-hover w-100">
                                                    <a type="button"
                                                        class="dropdown-item  dropdown-toggle dropdown-toggle-hover  d-j-b"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <?php echo showOtherLangText('Fee'); ?> <i
                                                            class="fa-solid fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <?php
                    $sqlQry = " SELECT * FROM tbl_order_fee WHERE visibility='1' AND account_id='".$_SESSION['accountId']."' ";
                    $ordFeeFetch = mysqli_query($con, $sqlQry);
                    while ($resultRow = mysqli_fetch_array($ordFeeFetch)) 
                    {
                   echo "<li><a class='dropdown-item' tabindex='-1' href='requisitionPaymentDetail.php?page=history&orderId=".$orderRow['id']."&action=pay&feeType=openFixedPerFee&id=".$resultRow['id']." '>".$resultRow['feeName']."</a></li>";

                         }
                    ?>
                                                    </ul>
                                                </div>
                                            </li>
                                            </li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#new-fees-item"
                                                    class="dropdown-item"
                                                    href="javascript:void(0)"><?php echo showOtherLangText('New Fee') ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- End of dropdown menu -->


                                    <div class="paySelect">
                                        <table cellpadding="0" cellspacing="0" width="100%" style="margin-top: 20px;">
                                            <tbody class="form-info">
                                                <tr class="" style="height: 48px;">
                                                    <td><?php echo showOtherLangText('Payment Type'); ?></td>
                                                    <td>
                                                        <select class="form-select form-select-1"
                                                            aria-label="Default select example" name="paymentType"
                                                            id="paymentType" class="form-control"
                                                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')"
                                                            onchange="this.setCustomValidity('')" required
                                                            onchange="this.setCustomValidity('')" required="">
                                                            <option value=""><?php echo showOtherLangText('Select'); ?>
                                                            </option>
                                                            <?php
$sqlSet = " SELECT * FROM tbl_payment_mode WHERE account_id = '".$_SESSION['accountId']."' ";
$resultSet = mysqli_query($con, $sqlSet);
while( $payModeRow = mysqli_fetch_array($resultSet))
{

   ?>
                                                            <option value="<?php echo $payModeRow['id'] ?>">
                                                                <?php echo $payModeRow['modeName'] ?></option>

                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Account'); ?></td>
                                                    <td>
                                                        <select name="accountId" id="accountId"
                                                            class="form-select form-select-1"
                                                            oninvalid="this.setCustomValidity('Please fill out this field.')"
                                                            onchange="getAccountVal(this.value),this.setCustomValidity('')"
                                                            required="">

                                                            <option value=""><?php echo showOtherLangText('Select'); ?>
                                                            </option>

                                                            <?php
$sqlSet = " SELECT * FROM  tbl_accounts WHERE account_id = '".$_SESSION['accountId']."'  ";
$resultSet = mysqli_query($con, $sqlSet);
while( $accRow = mysqli_fetch_array($resultSet) ){

?>
                                                            <option value="<?php echo $accRow['id'];?>">
                                                                <?php echo  $accRow['accountName'];?></option>

                                                            <?php } ?>


                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Account No.'); ?></td>
                                                    <td id="accountNumber"></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Balance'); ?></td>
                                                    <td id="accountBalance"></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Currency'); ?></td>
                                                    <td>
                                                        <span id="currencyName">

                                                        </span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Amount'); ?></td>
                                                    <td>
                                                        <input type="text" style="border: none; padding:0px;"
                                                            class="form-control" name="amount" id="amount" value=""
                                                            autocomplete="off" readonly />
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>

                                    </div>


                                    <div class="allBtn my-3 d-flex justify-content-between align-items-center w-100">
                                        <div>
                                            <button class="btn wht-btn" type="button"
                                                onclick="window.location.href='history.php'"><?php echo showOtherLangText('Back'); ?></button>
                                        </div>

                                        <div>
                                            <button class="btn final-btn" type="submit"
                                                name="submitBtn"><?php echo showOtherLangText('Finalize Invoice'); ?></button>
                                        </div>
                                    </div>

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
                                                <div><button class="btn wht-btn btn-primary"
                                                        type="submit">Add</button>&nbsp; &nbsp;
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
                            class="btn sub-btn std-btn"><?php echo showOtherLangText('No'); ?></button>
                    </div>
                    <div class="btnBg">
                        <button type="button" onclick=""
                            class="deletelink btn sub-btn std-btn"><?php echo showOtherLangText('Yes'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- View invoice Popup End -->
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
                            placeholder="<?php echo showOtherLangText('Service Name');?> *" autocomplete="off"
                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                            onChange="this.setCustomValidity('')" required>
                        <input type="number" required class="form-control" id="feeAmt" name="itemFeeAmt"
                            placeholder="<?php echo showOtherLangText('Amount').' '.$getDefCurDet['curCode']; ?> *"
                            autocomplete="off"
                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                            onChange="this.setCustomValidity('')" required>
                        <input type="text" required class="form-control" id="unit" name="unit"
                            placeholder="<?php echo showOtherLangText('Unit'); ?> *" autocomplete="off"
                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                            onChange="this.setCustomValidity('')" required>
                    </div>
                    <div>
                        <div class="feeSave">
                            <input type="checkbox" class="optionCheck" id="visibility" name="visibility" value="1">
                            <span class="subTittle1" style="vertical-align:text-top;"><?php echo showOtherLangText('save to fixed service item
list'); ?></span><br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btnBg">
                            <button type="submit" id="addFee" name="addFee"
                                class="btn sub-btn std-btn"><?php echo showOtherLangText('Add'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- View Details Popup Start -->
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
                        <input type="checkbox" name="feeType" id="feeType" class="optionCheck" value="1">
                        <span class="subTittle1"
                            style="vertical-align:text-top;"><?php echo showOtherLangText('Tax fee'); ?></span>
                        <div class="feeSave">
                            <input type="checkbox" class="optionCheck" id="visibility" name="visibility" value="1">
                            <span class="subTittle1" style="vertical-align:text-top;"><?php echo showOtherLangText('save to fixed service item
list'); ?></span><br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btnBg">
                            <button type="submit" id="feesave_add" name="feesave_add"
                                class="btn sub-btn std-btn"><?php echo showOtherLangText('Add'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="modal" tabindex="-1" id="view_invoice" aria-labelledby="view-invoice" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md site-modal">
            <div id="view_invoice_content" class="modal-content p-2">

            </div>
            <!-- payment Detail Popup End -->
        </div>

    </div>

    <div class="modal-body m-0 p-0">
        <!-- invoice Details Start -->
        <div id="invoice-Details" class="payment-status-left payment-pending position-relative">
            <div class="border-line"></div>
            <div class="modal-body p-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="f-01 mb-0 payment-status-text"><?php echo showOtherLangText('PENDING'); ?></p>
                        <p class="f-01"><?php echo showOtherLangText('INVOICE'); ?></p>
                    </div>
                    <div>
                        <?php

                        $clientQry = " SELECT * FROM tbl_client WHERE id = '".$_SESSION['accountId']."' ";
                        $clientResult = mysqli_query($con, $clientQry);
                        $clientResultRow = mysqli_fetch_array($clientResult);

                        if($clientResultRow['logo'] !='' && file_exists(dirname(__FILE__)."/uploads/".$accountImgPath."/clientLogo/".$clientResultRow['logo']))
                        {   
                        echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/clientLogo/'.$clientResultRow['logo'].'" width="100" height="100" style="object-fit: contain;">';
                        }

                        ?>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between payment-tabel-header">
                    <table class="table1 table01">
                        <tbody>
                            <tr>
                                <td class="font-wt">
                                    <?php echo showOtherLangText('Invoice'); ?> #
                                </td>
                                <td><?php echo getinvoiceNumber($invoiceNumber);?></td>
                            </tr>

                            <tr>
                                <td class="font-wt"><?php echo showOtherLangText('Task'); ?> #</td>
                                <td><?php echo $orderRow['ordNumber'] ?></td>
                            </tr>

                            <tr>
                                <td class="font-wt"><?php echo showOtherLangText('Date'); ?> #
                                </td>
                                <td><?php echo $orderRow['ordDateTime']?></td>
                            </tr>

                        </tbody>
                    </table>
                    <?php

                            $sql = " SELECT * FROM tbl_country WHERE id = '".$clientResultRow['country']."' ";
                            $resSet = mysqli_query($con, $sql);
                            $resultRow = mysqli_fetch_array($resSet);

                            ?>
                    <table>
                        <tbody class="table1 table01 fl-right cmp-dtl text-end">
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

                <br>
                <div class="">
                    <p class="f-02 mb-0"><?php echo showOtherLangText('Invoice To'); ?>: </p>
                    <p id="name" class="f-03 mb-0">
                        <?php echo isset($reqPayInfo['invoiceName']) ? $reqPayInfo['invoiceName'] : $invName; ?></p>
                </div>
                <br>

                <div class="divider-blue"></div>
                <br>
                <div class="tabel-body-p-footer">
                    <div class="table1 ">
                        <p class="f-02 mb-0"><?php echo showOtherLangText('Payment Method'); ?>:</p>
                        <!-- <p class="f-03 mb-0">Cash</p>
                                    <p class="f-03 mb-0">Main Safe USD</p> -->
                    </div>

                    <!-- grand totale  here -->
                    <table class="grand-total-tabel">

                        <tr class="grand-total" style=" max-height: 38px;">
                            <th><?php echo showOtherLangText('Grand Total'); ?> ($) </th>
                            <th><?php showprice($netTotalAmt,$getDefCurDet['curCode']); ?></th>
                        </tr>
                        <tr></tr>
                    </table>

                </div>

                <br>
                <br>
                <br>
            </div>
        </div>
        <!-- invoice Detail End -->
    </div>
    </div>
    </div>


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

    function getDelNumb(delId, orderId) {



        var newOnClick = "window.location.href='requisitionPaymentDetail.php?delId=" + delId + '&orderId=' + orderId +
            "'";
        $('.deletelink').attr('onclick', newOnClick);
        $('#delete-popup').modal('show');



    }

    $('.date-box-search').click(function() {

        $('#frm').submit();

    });
    </script>
    <script>
    //get other currencyAmount and set the total other currency value
    function getAccountVal(value) {

        var amount = document.getElementById('amount').value;
        var account = document.getElementById('accountId').value;
        var TotalAmount = document.getElementById('totalAmount').value;
        if (account > 0) {

            $.ajax({
                    method: "POST",
                    url: "requisitionAccountChange.php",
                    dataType: 'json',
                    data: {
                        accountId: account,
                        TotalAmount: TotalAmount,
                        action: "payment",
                        amount: amount
                    }

                })
                .done(function(val) {
                    $('#currencyName').html(val[0]);
                    $('#amount').val(val[1]);
                    $('#accountBalance').html(val[2]);
                    $('#accountNumber').html(val[3]);
                    $('#currencyId').val(val[4]);

                });

        }

    } //End of fetching other currency value
    </script>
    <script>
    function getOnClickVal() {

        $.ajax({
            method: "POST",
            url: "requisitionPaymentDetail.php",

            data: {
                issueInvoice: 1,
                orderId: <?php echo $_GET['orderId'] ?>
            }


        })

    }

    function openPopup1(orderId) {

        $.ajax({
                method: "POST",
                url: "requisitionPaymentSummaryPopupAjax.php",
                data: {
                    orderId: orderId
                }
            })
            .done(function(htmlRes) {
                $('#view_invoice_content').html(htmlRes);
                //document.getElementById("view_invoice").style.display = "block";
                $('#view_invoice').modal('show');
                //historyPdfJsCode();
            });



    }
    </script>
    <script>
    function invNameValueChange() {
        var invoiceName = document.getElementById('invoiceName').value;
        var name = document.getElementById('name');
        name.innerHTML = invoiceName;
    }

    function addressValueChange() {

        var invoiceAddress = document.getElementById('invoiceAddress').value;
        var address = document.getElementById('address');
        address.innerHTML = invoiceAddress;

    }



    function emailValueChange() {
        var invoiceEmail = document.getElementById('invoiceEmail').value;
        var email = document.getElementById('email');
        email.innerHTML = invoiceEmail;
    }

    function phoneValueChange() {
        var invoicePhone = document.getElementById('invoicePhone').value;
        var phone = document.getElementById('phone');
        phone.innerHTML = invoicePhone;
    }
    </script>
    <script>
$(document).ready(function() {

var invoiceNumber = $('#rightInvoiceNum').text();
var invoiceName = $('#invoiceName').val();
var invoiceAddress = $('#invoiceAddress').val();
var invoiceEmail = $('#invoiceEmail').val();
var invoicePhone = $('#invoicePhone').val();

if (invoiceNumber != '' && invoiceName != '' && invoiceAddress != '' && invoiceEmail != '' &&
invoicePhone != '') {

$.ajax({
method: "POST",
data: {
invoiceNumber: invoiceNumber,
invoiceName: invoiceName,
invoiceAddress: invoiceAddress,
invoiceEmail: invoiceEmail,
invoicePhone: invoicePhone
}

})

}

})


function getVal() {
var invoiceNumber = $('#rightInvoiceNum').text();
var invoiceName = $('#invoiceName').val();
var invoiceAddress = $('#invoiceAddress').val();
var invoiceEmail = $('#invoiceEmail').val();
var invoicePhone = $('#invoicePhone').val();

if (invoiceNumber != '' && invoiceName != '' && invoiceAddress != '' && invoiceEmail != '' && invoicePhone !=
'') {

$.ajax({
method: "POST",
data: {
invoiceNumber: invoiceNumber,
invoiceName: invoiceName,
invoiceAddress: invoiceAddress,
invoiceEmail: invoiceEmail,
invoicePhone: invoicePhone
}

})

}

}
</script>
    <div id="dialog" style="display: none;">
        <?php echo showOtherLangText('Are you sure to delete this record?') ?>
    </div>
</body>

</html>