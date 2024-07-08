<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


if (!isset($_SESSION['adminidusername'])) 
{   
echo '<script>window.location ="login.php"</script>';
}

checkActions($_GET);

//if user click on cancel button then update all field with blank value
if(isset($_GET['orderId']) && $_GET['cancelPayment']==1) 
{
$updateQry = " UPDATE tbl_payment SET 
currencyId = '',
ordTotAmt = '',
bankAccountId = '',
amount = '',
paymentType = '',
paymentStatus = '',
paymentDateTime = ''
WHERE orderId = '".$_GET['orderId']."' ";
mysqli_query($con, $updateQry);
}


if(isset($_GET['orderId'])) 
{   
$sql="SELECT * FROM tbl_orders WHERE id='".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."' ";
$orderQry = mysqli_query($con, $sql);
$orderRow = mysqli_fetch_array($orderQry);

//get currency detail of order
$curDetail = getCurrencyDet($orderRow['ordCurId']);

//fetch detail of order
$sql = "SELECT tp.*, od.price ordPrice, od.curPrice ordCurPrice, od.qty ordQty, od.totalAmt, od.factor ordFactor, od.qtyReceived, IF(u.name!='',u.name,tp.unitP) purchaseUnit 
FROM tbl_order_details od 
INNER JOIN tbl_products tp 
ON(od.pId = tp.id) AND od.account_id = tp.account_id
LEFT JOIN tbl_units u 
ON(u.id = tp.unitP) AND u.account_id = tp.account_id
WHERE od.ordId = '".$_GET['orderId']."'  AND tp.account_id = '".$_SESSION['accountId']."'  ";
$ordDetail = mysqli_query($con, $sql);

//fetch payment row of that particular orderId
$payQry= " SELECT * FROM tbl_payment WHERE orderId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' order by id limit 1 ";
$paymentResult=mysqli_query($con, $payQry);
$paymentRow=mysqli_fetch_array($paymentResult);

//check if record does not exist for this orderId then insert of records in tbl_payment like (id & orderId only) for first time.
if (empty($paymentRow))
{   
$insertQry = "INSERT INTO `tbl_payment` SET account_id = '".$_SESSION['accountId']."', orderId = '".$_GET['orderId']."' ";
mysqli_query($con, $insertQry);
$paymentId = mysqli_insert_id($con);


}
else
{
//check if record exist then assign paymentId 
$sql= " SELECT * FROM tbl_payment WHERE orderId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' order by id limit 1 ";
$resultSet=mysqli_query($con, $sql);
$paymentRow=mysqli_fetch_array($resultSet);
$paymentId = $paymentRow['id'];
}

}

//Add item charges in list
if( isset($_GET['id']) && $_GET['id'] > 0  && $_GET['feeType']=='openFixedFee' )
{
    editCustomCharge($_GET['orderId'],1, $_GET['id'], $orderRow['supplierId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $orderDetRowOld = mysqli_fetch_array($res);

    orderNetValue($_GET['orderId'], $orderRow['ordCurId']);
    
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
                `action` = 'Payment' ";
                mysqli_query($con, $qry);
    }
}

//Add order charges in list
if( isset($_GET['id']) && $_GET['id'] > 0 && $_GET['feeType']=='openFixedPerFee' )
{
    editCustomCharge($_GET['orderId'],3, $_GET['id'], $orderRow['supplierId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $orderDetRowOld = mysqli_fetch_array($res);

    orderNetValue($_GET['orderId'], $orderRow['ordCurId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $orderDetRow = mysqli_fetch_array($res);

    $diffPrice = ($orderDetRow['ordAmt'] - $orderDetRowOld['ordAmt']);
    $notes = 'Order fee added(Price Diff: '.getPriceWithCur($diffPrice, $getDefCurDet['curCode']).' )';

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


//get supplier name
$qry = " SELECT s.name AS supplierName, s.address AS supplierAddress, s.email AS supplierEmail, s.phone AS supplierPhone, o.* FROM tbl_orders o
INNER JOIN tbl_suppliers s ON(o.supplierId = s.id) AND o.account_id=s.account_Id
WHERE o.id ='".$_GET['orderId']."' AND o.account_id = '".$_SESSION['accountId']."' limit 1 ";
$supNameResult = mysqli_query($con, $qry);
$supNameRow = mysqli_fetch_array($supNameResult);
$email = $supNameRow['supplierEmail'];
$address = $supNameRow['supplierAddress'];
$phone = $supNameRow['supplierPhone'];


//Add supplier contact details
if(isset($_POST['supplierAddress']) && isset($_POST['supplierEmail']) && isset($_POST['supplierInvoice']) && isset($_POST['supplierName'])) 
{
//check if orderId wise payment info exist in table tbl_supplier_payment_info then update else insert
$payInfoQry = " SELECT * FROM tbl_supplier_payment_info WHERE  orderId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' order by id "; 
$payInfoResult = mysqli_query($con, $payInfoQry);
$payInfoRow = mysqli_num_rows($payInfoResult);

if ($payInfoRow > 0)
{
$updatePayInfoQry = " UPDATE tbl_supplier_payment_info SET
`orderId` = '".$_GET['orderId']."',
`supplierInvoice` = '".$_POST['supplierInvoice']."', 
`supplierName` = '".$_POST['supplierName']."', 
`supplierAddress` = '".$_POST['supplierAddress']."', 
`supplierEmail` = '".$_POST['supplierEmail']."',
`supplierPhone` = '".$_POST['supplierPhone']."',
`account_id` = '".$_SESSION['accountId']."' 
WHERE orderId ='".$_GET['orderId']."' ";
mysqli_query($con, $updatePayInfoQry);

$upOrdQry = " UPDATE tbl_orders SET
`invNo` = '".$_POST['supplierInvoice']."'
WHERE id ='".$_GET['orderId']."' ";
mysqli_query($con, $upOrdQry);
                    
}else{

$sql = "INSERT INTO `tbl_supplier_payment_info` SET
`orderId` = '".$_GET['orderId']."',
`supplierInvoice` = '".$_POST['supplierInvoice']."', 
`supplierName` = '".$_POST['supplierName']."', 
`supplierAddress` = '".$_POST['supplierAddress']."', 
`supplierEmail` = '".$_POST['supplierEmail']."',
`supplierPhone` = '".$_POST['supplierPhone']."',
`account_id` = '".$_SESSION['accountId']."'  ";
mysqli_query($con, $sql);

$upOrdQry = " UPDATE tbl_orders SET
`invNo` = '".$_POST['supplierInvoice']."'
WHERE id ='".$_GET['orderId']."' ";
mysqli_query($con, $upOrdQry);
}
}

//fetch contact detail of supplier payment
$payInfoQry = " SELECT * FROM tbl_supplier_payment_info WHERE  orderId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' "; 
$payInfoResult = mysqli_query($con, $payInfoQry);
$payInfoRow = mysqli_fetch_array($payInfoResult);

$supplierInvoice= isset($payInfoRow['supplierInvoice']) ? $payInfoRow['supplierInvoice'] : $orderRow['invNo'];
$supName = isset($payInfoRow['supplierName']) ? $payInfoRow['supplierName'] : $supNameRow['supplierName'];

//Add custom charge from add fee modal box
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
    $chargeId = mysqli_insert_id($con);

    editCustomCharge($_GET['orderId'],1, $chargeId, $orderRow['supplierId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $orderDetRowOld = mysqli_fetch_array($res);

    orderNetValue($_GET['orderId'], $orderRow['ordCurId']);

   

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
            `action` = 'Payment' ";
            mysqli_query($con, $qry);

    echo '<script>window.location = "supplierPaymentDetail.php?page=history&action=pay&orderId='.$orderRow['id'].'"</script>';

}

//Add order charge from add fee modal box
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

    $chargeId = mysqli_insert_id($con);

    editCustomCharge($_GET['orderId'],3, $chargeId, $orderRow['supplierId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $orderDetRowOld = mysqli_fetch_array($res);

    orderNetValue($_GET['orderId'], $orderRow['ordCurId']);

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
            `action` = 'Payment' ";
            mysqli_query($con, $qry);


    echo '<script>window.location = "supplierPaymentDetail.php?page=history&action=pay&orderId='.$orderRow['id'].'"</script>';

}

//Finalize Payment
if(isset($_POST['submitBtn']))
{ 
$account_id = $_SESSION['accountId']; 
$orderId = $_GET['orderId'];
$ordTotAmt = $_POST['totalAmount'];
$paymentType = $_POST['paymentType'];
$bankAccountId = $_POST['accountId'];
$currencyId = $_POST['currencyId'];
$status = "0";//pending payment status
$amount = $_POST['amount'];
$timenow = date('Y-m-d H:i:s');

$insQry = " UPDATE tbl_orders SET paymentStatus ='".$status."', paymentId ='".$paymentId."' WHERE id='".$_GET['orderId']."' ";
mysqli_query($con, $insQry);

$sql= " SELECT * FROM tbl_payment WHERE orderId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' order by id limit 1 ";
$resultSet=mysqli_query($con, $sql);
$paymentRow=mysqli_fetch_array($resultSet);

if (count($paymentRow) > 0)
{

$updatePayQry = " UPDATE tbl_payment SET 
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


    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $orderDetRow = mysqli_fetch_array($res);

    $qry = " INSERT INTO `tbl_order_journey` SET 
            `account_id` = '".$_SESSION['accountId']."',
            `orderId` = '".$_GET['orderId']."',
            `userBy`  = '".$_SESSION['id']."',
            `amount` = '".$orderDetRow['ordAmt']."',
            `otherCur` = '".$orderDetRow['ordCurAmt']."',
            `otherCurId` = '".$orderDetRow['ordCurId']."',
            `orderType` = '".$orderDetRow['ordType']."',
            `ordDateTime` = '".date('Y-m-d h:i:s')."',
            `notes` = 'Paid',
            `action` = 'Payment' ";
            mysqli_query($con, $qry);


}

echo '<script>window.location ="supplierSuccessPayment.php?orderId='.$_GET['orderId'].'"</script>';//redirect page

}




//delete item level / order level charges
if(isset($_GET['delId'])  && $_GET['orderId'])
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

    orderNetValue($_GET['orderId'], $orderRow['ordCurId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $orderDetRow = mysqli_fetch_array($res);

    $diffPrice = ($orderDetRow['ordAmt'] - $orderDetRowOld['ordAmt']);
    $notes = 'Order fee deleted(Price Diff: '.getPriceWithCur($diffPrice, $getDefCurDet['curCode']).' )';

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

    echo '<script>window.location = "supplierPaymentDetail.php?page=history&action=pay&orderId='.$_GET['orderId'].'"</script>';

}//end 

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Queue1.com</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">
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
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"><i class="fa-solid fa-bars"></i></span>
                                    </button>
                                </div>
                                <div class="mbpg-name">
                                    <!-- <h1 class="h1"> History </h1> -->
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


                <div class="payment-status-main">
                    <!--payment pending Details Popup Start -->
                    <div class="payment-status-left payment-pending position-relative">
                        <div class="border-line"></div>
                        <div class="modal-body p-5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="f-01 mb-0 payment-status-text"><?php echo showOtherLangText('PENDING') ?></p>
                                    <p class="f-01"><?php echo showOtherLangText('PAYMENT') ?></p>
                                </div>
                                <div>
                                   <?php

    $clientQry = " SELECT * FROM tbl_client WHERE id = '".$_SESSION['accountId']."' ";
    $clientResult = mysqli_query($con, $clientQry);
    $clientResultRow = mysqli_fetch_array($clientResult);

    if($clientResultRow['logo'] !='' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath.'/clientLogo/'.$clientResultRow['logo'] ))
    {  
        echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/clientLogo/'.$clientResultRow['logo'].'" width="100" height="100" style="margin-top: 25px;">';
    }

?>
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-between payment-tabel-header">
                                <table class="table1 table01">
                                    <tbody>
                                        <tr>
                                            <td class="font-wt">
                                                <?php echo showOtherLangText('Supplier Invoice'); ?> #
                                            </td>
                                            <td><?php echo $supplierInvoice;?></td>
                                        </tr>

                                        <tr>
                                            <td class="font-wt">
                                                <?php echo showOtherLangText('PAYMENT') ?> #
                                            </td>
                                            <td><?php echo setPaymentId($paymentId);?></td>
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
                                            <td><?php echo $clientResultRow['address_one'].','.$clientResultRow['address_two'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo $clientResultRow['city'].','.$resultRow['name'] ?> </td>
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
                                <p class="f-02 mb-0"><?php echo showOtherLangText('PAYMENT To') ?></p>
                                <p class="f-03 mb-0"><?php echo $supName; ?></p>
                                <p class="f-03 mb-0"><?php echo isset($payInfoRow['supplierAddress']) ? $payInfoRow['supplierAddress'] : $address;?></p>
                                <p class="f-03 mb-0"><?php echo isset($payInfoRow['supplierEmail']) ? $payInfoRow['supplierEmail'] : $email;?></p>
                                <p class="f-03 mb-0"><?php echo isset($payInfoRow['supplierPhone']) ? $payInfoRow['supplierPhone'] : $phone;?></p>
                            </div>
                            <br>

                            <table class="modal-table fs-12 w-100 mt-4">
                                <thead style="background: #A9B0C0 !important;">
                                    <tr class="tr-bg-1">
                                        <th>#</th>
                                        <th style="width: 30%;"><?php echo showOtherLangText('Item'); ?></th>
                                        <th><?php echo showOtherLangText('Unit'); ?></th>
                                        <th><?php echo showOtherLangText('Ordered'); ?><br><?php echo showOtherLangText('Quantity'); ?></th>
                                        <th><?php echo showOtherLangText('Received'); ?><br><?php echo showOtherLangText('Quantity'); ?></th>
                                         <?php 
                            if ($orderRow['ordCurId'] > 0) 
                                { ?>

                                    <th class="th-bg-1">
                                        <?php echo showOtherLangText('Price'); ?>(<?php echo $curDetail['curCode'];?>)
                                    </th>

                                    <?php }else{ ?>

                                    <th class="th-bg-1"><?php echo showOtherLangText('Price'); ?>(<?php echo $getDefCurDet['curCode'] ?>)</th>

                                    <?php } 
                                        if ($orderRow['ordCurId'] > 0) 
                                { ?>

                                    <th class="th-bg-1">
                                        <?php echo showOtherLangText('Total'); ?>(<?php echo $curDetail['curCode'];?>)
                                    </th>

                                    <?php }else{?>

                                    <th class="th-bg-1"><?php echo showOtherLangText('Total'); ?>(<?php echo $getDefCurDet['curCode'] ?>)</th>

                                    <?php } ?> 
                                    </tr>
                                </thead>
                                <tbody class="tabel-body-p">
                             <?php
                        //item level fee listing start here 
                        $sql = "SELECT cif.itemName, cif.unit, cif.amt, tod.* FROM tbl_order_details tod 
                        INNER JOIN tbl_custom_items_fee cif ON(tod.customChargeId = cif.id) AND tod.account_id = cif.account_id
                        WHERE tod.ordId = '".$_GET['orderId']."' AND tod.account_id = '".$_SESSION['accountId']."' AND tod.customChargeType=1 ORDER BY cif.itemName ";
                        $custFeeResult=mysqli_query($con, $sql);   
                        $x=0;
                        while ($custFeeRow= mysqli_fetch_array($custFeeResult))
                        {
                            $x++;
                            ?>
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;"><?php echo $custFeeRow['itemName'];?></td>
                                        <td><?php echo $custFeeRow['unit'];?></td>
                                        <td>1</td>
                                        <td>1</td>
                                        <?php 
                                //price column
                                if ($orderRow['ordCurId'] > 0) 
                                { ?>
                                    <td><?php echo showOtherCur(($custFeeRow['amt']*$curDetail['amt']), $orderRow['ordCurId']);?></td>
                                    <?php }else{ ?>
                                    <td class="pay-dt"><?php echo showPrice($custFeeRow['amt'],$getDefCurDet['curCode']);?>
                                        <td><?php echo showPrice($custFeeRow['amt'],$getDefCurDet['curCode']);?></td>
                                    </tr>
                        <?php } }?>  
                                    
                                </tbody>
                            </table>
                            <div class="divider-blue"></div>
                            <br>
                            <div class="tabel-body-p-footer">
                                <div class="table1 ">
                                    <p class="f-02 mb-0">Payment Method:</p>
                                    <p class="f-03 mb-0">Cash</p>
                                    <p class="f-03 mb-0">Main Safe USD</p>
                                </div>

                                <!-- grand totale  here -->
                                <table class="grand-total-tabel">
                                  

                                <tr class="grand-total" style=" max-height: 38px;">
                                        <th>Grand Total ($) </th>
                                        <th> 238.2235 $ </th>
                                    </tr>
                                    <tr></tr>
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
                                <form action="" method="post" id="frm" name="frm" class="mtop" enctype="multipart/form-data">
                                    <div class="row table-responsive">
                                        <h6 class="bill-head">
                                            <img src="https://queue1.net/qa1/uploads/hand.png" alt="Payment-hand">
                                            Payment
                                        </h6>
                                    </div>

                                    <div class="inv-top">
                                        <table class="mr-btm">
                                            <tbody class="payDetail">
                                                <tr>
                                                    <td>Payment #</td>
                                                    <td>
                                                        <input type="text" style="cursor: text; background:none;" class="form-control form-control-1" onchange="getVal();" name="paymentId" id="paymentId" autocomplete="off" value="001527" readonly="">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Task #</td>
                                                    <td>
                                                        <input type="text" style="cursor: text; background:none;" class="form-control form-control-1" name="ordNumber" value="126487" autocomplete="off" readonly="">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Date #</td>
                                                    <td class="payDate">2024-06-06 10:28:37</td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="inv-detail">
                                        <table cellpadding="0" cellspacing="0" width="100%">
                                            <tbody class="frm-info" style="line-height: 40px;">
                                                <tr class="mb-adrs">
                                                    <td>Supplier invoice # </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="supplierInvoice" id="supplierInvoice" onchange="getVal();" oninput="changeSupInv();" value="2432434" autocomplete="off">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Payment to</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="supplierName" id="supplierName" onchange="getVal();" oninput="changeSupName();" value="Active Store" autocomplete="off">
                                                    </td>
                                                </tr>


                                                <tr class="mb-adrs">
                                                    <td> Supplier address</td>
                                                    <td>
                                                        <textarea class="form-control" name="supplierAddress" id="supplierAddress" onchange="getVal();" oninput="changeSupAddress();" autocomplete="off" placeholder="Address">Paje</textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Email</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="supplierEmail" id="supplierEmail" onchange="getVal();" oninput="changeSupEmail()" value="ActiveStore@active.com" autocomplete="off" placeholder="Email">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Phone</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="supplierPhone" id="supplierPhone" onchange="getVal();" oninput="changeSupPhone()" value="+255 774 062 22" autocomplete="off" placeholder="Phone no">
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
                                                    <td>Total amount ($)</td>
                                                    <td>
                                                        <input type="text" style="cursor: text; text-align:center; background:none;" class="form-control form-control-01" name="totalAmount" id="totalAmount" value="90.9088 $" autocomplete="off" readonly="">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <input type="hidden" class="form-control" name="TotalAmt" id="TotalAmt" value="90.9088" autocomplete="off">
                                    <input type="hidden" class="form-control" name="currencyId" id="currencyId" value="" autocomplete="off">

                                    <!-- Dropdown menu -->
                                        <div class="dropdown mt-2">
                                            <button class="btn btn-2 dropdown-toggle  d-j-b" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                                Add Fee <i class="fa-solid fa-angle-down"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <!-- Default dropend button -->
                                                    <div class="btn-group dropend dropdown-hover w-100">
                                                        <a type="button" class="dropdown-item  dropdown-toggle dropdown-toggle-hover  d-j-b" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Service item <i class="fa-solid fa-angle-down"></i>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <!-- Dropdown menu links -->
                                                            <li><a class="dropdown-item" href="#">Transport</a></li>
                                                            <li><a class="dropdown-item" href="#">Helper</a></li>
                                                            <li><a class="dropdown-item" href="#">Test Fee</a></li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li><a class="dropdown-item" href="#">Service item</a></li>

                                                <li>
                                                <li>
                                                    <!-- Default dropend button -->
                                                    <div class="btn-group dropend dropdown-hover w-100">
                                                        <a type="button" class="dropdown-item  dropdown-toggle dropdown-toggle-hover  d-j-b" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Fee <i class="fa-solid fa-angle-down"></i>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <!-- Dropdown menu links -->
                                                            <li><a class="dropdown-item" href="#">Discount</a></li>
                                                            <li><a class="dropdown-item" href="#">VAT</a></li>
                                                            <li><a class="dropdown-item" href="#">Transport Fee Hotel</a></li>
                                                            <li><a class="dropdown-item" href="#">Discount 5%</a></li>
                                                            <li><a class="dropdown-item" href="#">Storage Fee</a></li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                </li>
                                                <li><a class="dropdown-item" href="#">New Fee</a></li>
                                            </ul>
                                        </div>
                                    <!-- End of dropdown menu -->


                                    <div class="paySelect">
                                        <table cellpadding="0" cellspacing="0" width="100%" style="margin-top: 20px;">
                                            <tbody class="form-info">
                                                <tr class="" style="height: 48px;">
                                                    <td>Payment type</td>
                                                    <td>
                                                        <select class="form-select form-select-1" aria-label="Default select example" name="paymentType" id="paymentType" class="form-control" oninvalid="this.setCustomValidity('Please select an item in the list.')" onchange="this.setCustomValidity('')" required="">
                                                         <option value="">Select</option>
                                                            <option value="1">Cash</option>
                                                            <option value="2">Debit Card</option>
                                                            <option value="3">Credit Card</option>
                                                            <option value="4">UPI</option>
                                                            <option value="5">Net Banking</option>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Account</td>
                                                    <td>
                                                        <select name="accountId" id="accountId" class="form-select form-select-1" oninvalid="this.setCustomValidity('Please fill out this field.')" onchange="fetchAccountDetail(this.value),this.setCustomValidity('')" required="">

                                                            <option value="">Select</option>

                                                            <option value="1">
                                                                Main Safe TSH</option>

                                                            <option value="2">
                                                                2 Safe TSH</option>

                                                            <option value="3">
                                                                Main Safe USD</option>

                                                            <option value="4">
                                                                Fun Beach DPO Account</option>

                                                            <option value="5">
                                                                CASA DPO Account</option>

                                                            <option value="6">
                                                                Nur DPO Account</option>

                                                            <option value="7">
                                                                Qambani DPO Account</option>

                                                            <option value="8">
                                                                Fun Beach Bank Account</option>

                                                            <option value="9">
                                                                Mwamba Bank Account</option>

                                                            <option value="10">
                                                                Safe test 01</option>


                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Account no.</td>
                                                    <td id="accountNumber"></td>
                                                </tr>

                                                <tr>
                                                    <td>Balance</td>
                                                    <td id="accountBalance"></td>
                                                </tr>

                                                <tr>
                                                    <td>Currency </td>
                                                    <td>
                                                        <span id="currencyName">

                                                        </span></td>
                                                </tr>

                                                <tr>
                                                    <td>Amount</td>
                                                    <td>
                                                    <span id="AmountName"></span> 
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>

                                    </div>


                                    <div class="allBtn my-3 d-flex justify-content-between align-items-center w-100">
                                        <div>
                                            <button class="btn wht-btn" type="button" onclick="window.location.href='history.php'">Back</button>
                                        </div>

                                        <div>
                                            <button class="btn final-btn" type="submit" name="submitBtn">Finalize payment</button>
                                        </div>
                                    </div>

                                </form>

                                <!-- start of add fee (item/order level) form -->
                                <!-- Modal box form start from here -->
                                <form action="" name="addServiceFrm" id="addServiceFrm" method="post" autocomplete="off">
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
                                                                        <td><input type="text" class="form-control" name="itemName" id="itemName" value="" autocomplete="off">
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="feePopup">Amount</td>
                                                                        <td><input type="text" class="form-control" name="itemFeeAmt" id="feeAmt" value="" autocomplete="off">
                                                                        </td>
                                                                    </tr>


                                                                    <tr>
                                                                        <td class="feePopup">Unit</td>
                                                                        <td><input type="text" class="form-control" name="unit" id="unit" autocomplete="off">
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
                                                                        <td><input type="text" class="form-control" name="feeName" id="feeName" value="" style="width:250px;" autocomplete="off">
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="feePopup">Fee type </td>
                                                                        <td>
                                                                            <select class="form-control" name="feeType" id="typeOfFee">
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
                                                                        <td class="feePopup feePercent" id="feePercent" style="display: none;">
                                                                            <span>Fee percentage %</span>
                                                                        </td>
                                                                        <td>
                                                                            <div class="stepper "><input type="number" class="form-control stepper-input" name="amt" id="amt" value="" style="width:250px;" autocomplete="off"><span class="stepper-arrow up">Up</span><span class="stepper-arrow down">Down</span></div>

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
                                                <button class="btn wht-btn" type="submit" name="AddBtn">Add</button>&nbsp; &nbsp;
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
            $('.ui-dialog-content').prepend('<div class="custom-header-text"><span><?php echo showOtherLangText('Queue1.com Says') ?></span></div>');
        }

        $('.date-box-search').click(function() {

            $('#frm').submit();

        });
    </script>
    <div id="dialog" style="display: none;">
        <?php echo showOtherLangText('Are you sure to delete this record?') ?>
    </div>
</body>

</html>