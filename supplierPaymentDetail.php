<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


if (!isset($_SESSION['adminidusername'])) {
    echo '<script>window.location ="login.php"</script>';
}

checkActions($_GET);

//if user click on cancel button then update all field with blank value
if (isset($_GET['orderId']) && $_GET['cancelPayment'] == 1) {
    $updateQry = " UPDATE tbl_payment SET 
currencyId = '',
ordTotAmt = '',
bankAccountId = '',
amount = '',
paymentType = '',
paymentStatus = '',
paymentDateTime = ''
WHERE orderId = '" . $_GET['orderId'] . "' ";
    mysqli_query($con, $updateQry);
}


if (isset($_GET['orderId'])) {
    $sql = "SELECT * FROM tbl_orders WHERE id='" . $_GET['orderId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ";
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
WHERE od.ordId = '" . $_GET['orderId'] . "'  AND tp.account_id = '" . $_SESSION['accountId'] . "'  ";
    $ordDetail = mysqli_query($con, $sql);

    //fetch payment row of that particular orderId
    $payQry = " SELECT * FROM tbl_payment WHERE orderId='" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' order by id limit 1 ";
    $paymentResult = mysqli_query($con, $payQry);
    $paymentRow = mysqli_fetch_array($paymentResult);

    //check if record does not exist for this orderId then insert of records in tbl_payment like (id & orderId only) for first time.
    if (empty($paymentRow)) {
        $insertQry = "INSERT INTO `tbl_payment` SET account_id = '" . $_SESSION['accountId'] . "', orderId = '" . $_GET['orderId'] . "' ";
        mysqli_query($con, $insertQry);
        $paymentId = mysqli_insert_id($con);
    } else {
        //check if record exist then assign paymentId 
        $sql = " SELECT * FROM tbl_payment WHERE orderId='" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' order by id limit 1 ";
        $resultSet = mysqli_query($con, $sql);
        $paymentRow = mysqli_fetch_array($resultSet);
        $paymentId = $paymentRow['id'];
    }
}

//Add item charges in list
if (isset($_GET['id']) && $_GET['id'] > 0  && $_GET['feeType'] == 'openFixedFee') {
    editCustomCharge($_GET['orderId'], 1, $_GET['id'], $orderRow['supplierId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $res = mysqli_query($con, $sql);
    $orderDetRowOld = mysqli_fetch_array($res);

    orderNetValue($_GET['orderId'], $orderRow['ordCurId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $res = mysqli_query($con, $sql);
    $orderDetRow = mysqli_fetch_array($res);

    $diffPrice = ($orderDetRow['ordAmt'] - $orderDetRowOld['ordAmt']);
    $notes = 'Service Charge added(Price Diff: ' . getPriceWithCur($diffPrice, $getDefCurDet['curCode']) . ' )';

    if ($diffPrice != 0) {
        $qry = " INSERT INTO `tbl_order_journey` SET 
                `account_id` = '" . $_SESSION['accountId'] . "',
                `orderId` = '" . $_GET['orderId'] . "',
                `userBy`  = '" . $_SESSION['id'] . "',
                `amount` = '" . $orderDetRow['ordAmt'] . "',
                `otherCur` = '" . $orderDetRow['ordCurAmt'] . "',
                `otherCurId` = '" . $orderDetRow['ordCurId'] . "',
                `orderType` = '" . $orderDetRow['ordType'] . "',
                `ordDateTime` = '" . date('Y-m-d h:i:s') . "',
                `notes` = '" . $notes . "',
               `action` = '" . showOtherLangText('Payment') . "' ";
        mysqli_query($con, $qry);
    }
}

//Add order charges in list
if (isset($_GET['id']) && $_GET['id'] > 0 && $_GET['feeType'] == 'openFixedPerFee') {
    editCustomCharge($_GET['orderId'], 3, $_GET['id'], $orderRow['supplierId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $res = mysqli_query($con, $sql);
    $orderDetRowOld = mysqli_fetch_array($res);

    orderNetValue($_GET['orderId'], $orderRow['ordCurId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $res = mysqli_query($con, $sql);
    $orderDetRow = mysqli_fetch_array($res);

    $diffPrice = ($orderDetRow['ordAmt'] - $orderDetRowOld['ordAmt']);
    $orderFeeText = showOtherLangText('Order fee added');
    $priceDifText = showOtherLangText('Price Diff');

    $notes = $orderFeeText . '(' . $priceDifText . ': ' . getPriceWithCur($diffPrice, $getDefCurDet['curCode']) . ' )';



    $qry = " INSERT INTO `tbl_order_journey` SET 
            `account_id` = '" . $_SESSION['accountId'] . "',
            `orderId` = '" . $_GET['orderId'] . "',
            `userBy`  = '" . $_SESSION['id'] . "',
            `amount` = '" . $orderDetRow['ordAmt'] . "',
            `otherCur` = '" . $orderDetRow['ordCurAmt'] . "',
            `otherCurId` = '" . $orderDetRow['ordCurId'] . "',
            `orderType` = '" . $orderDetRow['ordType'] . "',
            `ordDateTime` = '" . date('Y-m-d h:i:s') . "',
            `notes` = '" . $notes . "',
            `action` = '" . showOtherLangText('Payment') . "' ";

    mysqli_query($con, $qry);
}


//get supplier name
$qry = " SELECT s.name AS supplierName, s.address AS supplierAddress, s.email AS supplierEmail, s.phone AS supplierPhone, o.* FROM tbl_orders o
INNER JOIN tbl_suppliers s ON(o.supplierId = s.id) AND o.account_id=s.account_Id
WHERE o.id ='" . $_GET['orderId'] . "' AND o.account_id = '" . $_SESSION['accountId'] . "' limit 1 ";
$supNameResult = mysqli_query($con, $qry);
$supNameRow = mysqli_fetch_array($supNameResult);
$email = $supNameRow['supplierEmail'];
$address = $supNameRow['supplierAddress'];
$phone = $supNameRow['supplierPhone'];


//Add supplier contact details
if (isset($_POST['supplierAddress']) && isset($_POST['supplierEmail']) && isset($_POST['supplierInvoice']) && isset($_POST['supplierName'])) {
    //check if orderId wise payment info exist in table tbl_supplier_payment_info then update else insert
    $payInfoQry = " SELECT * FROM tbl_supplier_payment_info WHERE  orderId='" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' order by id ";
    $payInfoResult = mysqli_query($con, $payInfoQry);
    $payInfoRow = mysqli_num_rows($payInfoResult);

    if ($payInfoRow > 0) {
        $updatePayInfoQry = " UPDATE tbl_supplier_payment_info SET
`orderId` = '" . $_GET['orderId'] . "',
`supplierInvoice` = '" . $_POST['supplierInvoice'] . "', 
`supplierName` = '" . $_POST['supplierName'] . "', 
`supplierAddress` = '" . $_POST['supplierAddress'] . "', 
`supplierEmail` = '" . $_POST['supplierEmail'] . "',
`supplierPhone` = '" . $_POST['supplierPhone'] . "',
`account_id` = '" . $_SESSION['accountId'] . "' 
WHERE orderId ='" . $_GET['orderId'] . "' ";
        mysqli_query($con, $updatePayInfoQry);

        $upOrdQry = " UPDATE tbl_orders SET
`invNo` = '" . $_POST['supplierInvoice'] . "'
WHERE id ='" . $_GET['orderId'] . "' ";
        mysqli_query($con, $upOrdQry);
    } else {

        $sql = "INSERT INTO `tbl_supplier_payment_info` SET
`orderId` = '" . $_GET['orderId'] . "',
`supplierInvoice` = '" . $_POST['supplierInvoice'] . "', 
`supplierName` = '" . $_POST['supplierName'] . "', 
`supplierAddress` = '" . $_POST['supplierAddress'] . "', 
`supplierEmail` = '" . $_POST['supplierEmail'] . "',
`supplierPhone` = '" . $_POST['supplierPhone'] . "',
`account_id` = '" . $_SESSION['accountId'] . "'  ";
        mysqli_query($con, $sql);

        $upOrdQry = " UPDATE tbl_orders SET
`invNo` = '" . $_POST['supplierInvoice'] . "'
WHERE id ='" . $_GET['orderId'] . "' ";
        mysqli_query($con, $upOrdQry);
    }
}

//fetch contact detail of supplier payment
$payInfoQry = " SELECT * FROM tbl_supplier_payment_info WHERE  orderId='" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
$payInfoResult = mysqli_query($con, $payInfoQry);
$payInfoRow = mysqli_fetch_array($payInfoResult);

$supplierInvoice = isset($payInfoRow['supplierInvoice']) ? $payInfoRow['supplierInvoice'] : $orderRow['invNo'];
$supName = isset($payInfoRow['supplierName']) ? $payInfoRow['supplierName'] : $supNameRow['supplierName'];

//Add custom charge from add fee modal box
if (!empty($_POST['itemName'])) {
    $showHideInList = isset($_POST['visibility']) ? 1 : 0;

    $sql = "INSERT INTO `tbl_custom_items_fee` SET 
    `itemName` = '" . $_POST['itemName'] . "',
    `unit` = '" . $_POST['unit'] . "',
    `amt` = '" . $_POST['itemFeeAmt'] . "',
    `visibility` = '" . $showHideInList . "',
    `account_id` = '" . $_SESSION['accountId'] . "' 
    ";
    mysqli_query($con, $sql);
    $chargeId = mysqli_insert_id($con);

    editCustomCharge($_GET['orderId'], 1, $chargeId, $orderRow['supplierId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $res = mysqli_query($con, $sql);
    $orderDetRowOld = mysqli_fetch_array($res);

    orderNetValue($_GET['orderId'], $orderRow['ordCurId']);



    $sql = " SELECT * FROM tbl_orders WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $res = mysqli_query($con, $sql);
    $orderDetRow = mysqli_fetch_array($res);

    $diffPrice = ($orderDetRow['ordAmt'] - $orderDetRowOld['ordAmt']);
    $notes = 'Service Item added(Price Diff: ' . getPriceWithCur($diffPrice, $getDefCurDet['curCode']) . ' )';

    $qry = " INSERT INTO `tbl_order_journey` SET 
            `account_id` = '" . $_SESSION['accountId'] . "',
            `orderId` = '" . $_GET['orderId'] . "',
            `userBy`  = '" . $_SESSION['id'] . "',
            `amount` = '" . $orderDetRow['ordAmt'] . "',
            `otherCur` = '" . $orderDetRow['ordCurAmt'] . "',
            `otherCurId` = '" . $orderDetRow['ordCurId'] . "',
            `orderType` = '" . $orderDetRow['ordType'] . "',
            `ordDateTime` = '" . date('Y-m-d h:i:s') . "',
            `notes` = '" . $notes . "',
           `action` = '" . showOtherLangText('Payment') . "' ";
    mysqli_query($con, $qry);

    echo '<script>window.location = "supplierPaymentDetail.php?page=history&action=pay&orderId=' . $orderRow['id'] . '"</script>';
}

//Add order charge from add fee modal box
if (!empty($_POST['feeName'])) {
    $showHideInList = isset($_POST['visibility']) ? 1 : 0;

    $sql = "INSERT INTO `tbl_order_fee` SET 
    `feeName` = '" . $_POST['feeName'] . "',
    `feeType` = '" . $_POST['feeType'] . "',
    `amt` = '" . $_POST['amt'] . "',
    `visibility` = '" . $showHideInList . "',
    `account_id` = '" . $_SESSION['accountId'] . "' 
    ";

    mysqli_query($con, $sql);

    $chargeId = mysqli_insert_id($con);

    editCustomCharge($_GET['orderId'], 3, $chargeId, $orderRow['supplierId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $res = mysqli_query($con, $sql);
    $orderDetRowOld = mysqli_fetch_array($res);

    orderNetValue($_GET['orderId'], $orderRow['ordCurId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $res = mysqli_query($con, $sql);
    $orderDetRow = mysqli_fetch_array($res);

    $diffPrice = ($orderDetRow['ordAmt'] - $orderDetRowOld['ordAmt']);
    $notes = 'Fee Added(Price Diff: ' . getPriceWithCur($diffPrice, $getDefCurDet['curCode']) . ' )';

    $qry = " INSERT INTO `tbl_order_journey` SET 
            `account_id` = '" . $_SESSION['accountId'] . "',
            `orderId` = '" . $_GET['orderId'] . "',
            `userBy`  = '" . $_SESSION['id'] . "',
            `amount` = '" . $orderDetRow['ordAmt'] . "',
            `otherCur` = '" . $orderDetRow['ordCurAmt'] . "',
            `otherCurId` = '" . $orderDetRow['ordCurId'] . "',
            `orderType` = '" . $orderDetRow['ordType'] . "',
            `ordDateTime` = '" . date('Y-m-d h:i:s') . "',
            `notes` = '" . $notes . "',
            `action` = '" . showOtherLangText('Payment') . "' ";
    mysqli_query($con, $qry);


    echo '<script>window.location = "supplierPaymentDetail.php?page=history&action=pay&orderId=' . $orderRow['id'] . '"</script>';
}

//Finalize Payment
if (isset($_POST['submitBtn'])) {
    $account_id = $_SESSION['accountId'];
    $orderId = $_GET['orderId'];
    $ordTotAmt = $_POST['totalAmount'];
    $paymentType = $_POST['paymentType'];
    $bankAccountId = $_POST['accountId'];
    $currencyId = $_POST['currencyId'];
    $status = "0"; //pending payment status
    $amount = $_POST['amount'];
    $timenow = date('Y-m-d H:i:s');

    $insQry = " UPDATE tbl_orders SET paymentStatus ='" . $status . "', paymentId ='" . $paymentId . "' WHERE id='" . $_GET['orderId'] . "' ";
    mysqli_query($con, $insQry);

    $sql = " SELECT * FROM tbl_payment WHERE orderId='" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' order by id limit 1 ";
    $resultSet = mysqli_query($con, $sql);
    $paymentRow = mysqli_fetch_array($resultSet);

    if (count($paymentRow) > 0) {

        $updatePayQry = " UPDATE tbl_payment SET 
account_id = '" . $account_id . "',
orderId = '" . $orderId . "',
bankAccountId = '" . $bankAccountId . "',
currencyId = '" . $currencyId . "',
ordTotAmt = '" . $ordTotAmt . "',
amount = '" . $amount . "',
paymentType = '" . $paymentType . "',
paymentStatus = '" . $status . "',
paymentDateTime = '" . $timenow . "'

WHERE orderId = '" . $_GET['orderId'] . "' ";
        mysqli_query($con, $updatePayQry);


        $sql = " SELECT * FROM tbl_orders WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
        $res = mysqli_query($con, $sql);
        $orderDetRow = mysqli_fetch_array($res);

        // $qry = " INSERT INTO `tbl_order_journey` SET 
        //         `account_id` = '".$_SESSION['accountId']."',
        //         `orderId` = '".$_GET['orderId']."',
        //         `userBy`  = '".$_SESSION['id']."',
        //         `amount` = '".$orderDetRow['ordAmt']."',
        //         `otherCur` = '".$orderDetRow['ordCurAmt']."',
        //         `otherCurId` = '".$orderDetRow['ordCurId']."',
        //         `orderType` = '".$orderDetRow['ordType']."',
        //         `ordDateTime` = '".date('Y-m-d h:i:s')."',
        //         `notes` = 'Paid',
        //         `action` = 'Payment' ";
        //         mysqli_query($con, $qry);


    }

    echo '<script>window.location ="supplierSuccessPayment.php?orderId=' . $_GET['orderId'] . '"</script>'; //redirect page

}




//delete item level / order level charges
if (isset($_GET['delId'])  && $_GET['orderId']) {

    $sql = " SELECT * FROM tbl_order_details WHERE ordId='" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' and id='" . $_GET['delId'] . "' ";
    $sqlSet = mysqli_query($con, $sql);
    $OrdDetRow = mysqli_fetch_array($sqlSet);

    $sql = " DELETE FROM tbl_order_details_temp WHERE ordId='" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' and customChargeId='" . $OrdDetRow['customChargeId'] . "' and customChargeType='" . $OrdDetRow['customChargeType'] . "' ";
    $resultSet = mysqli_query($con, $sql);

    $sql = " DELETE FROM tbl_order_details WHERE id= '" . $_GET['delId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'   ";
    $resultSet = mysqli_query($con, $sql);


    $sql = " SELECT * FROM tbl_orders WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $res = mysqli_query($con, $sql);
    $orderDetRowOld = mysqli_fetch_array($res);

    orderNetValue($_GET['orderId'], $orderRow['ordCurId']);

    $sql = " SELECT * FROM tbl_orders WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $res = mysqli_query($con, $sql);
    $orderDetRow = mysqli_fetch_array($res);

    $diffPrice = ($orderDetRow['ordAmt'] - $orderDetRowOld['ordAmt']);
    $notes = 'Order fee deleted(Price Diff: ' . getPriceWithCur($diffPrice, $getDefCurDet['curCode']) . ' )';

    $qry = " INSERT INTO `tbl_order_journey` SET 
            `account_id` = '" . $_SESSION['accountId'] . "',
            `orderId` = '" . $_GET['orderId'] . "',
            `userBy`  = '" . $_SESSION['id'] . "',
            `amount` = '" . $orderDetRow['ordAmt'] . "',
            `otherCur` = '" . $orderDetRow['ordCurAmt'] . "',
            `otherCurId` = '" . $orderDetRow['ordCurId'] . "',
            `orderType` = '" . $orderDetRow['ordType'] . "',
            `ordDateTime` = '" . date('Y-m-d h:i:s') . "',
            `notes` = '" . $notes . "',
            `action` = '" . showOtherLangText('Payment') . "' ";
    mysqli_query($con, $qry);

    echo '<script>window.location = "supplierPaymentDetail.php?page=history&action=pay&orderId=' . $_GET['orderId'] . '"</script>';
} //end 

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">



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

        /* .form-info tr,
        .payDetail tr {
            height: 38px !important;
        }

        .inv-detail tr {
            min-height: 38px !important;
        } */

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

        .final-btn:hover {
            border: 2px solid #ED7D31 !important;
            color: white !important;
            border-color: #AE5A21;
        }

        .Payment-to p {
            margin: 3px 0px;
        }

        .payment__table th:nth-child(5),
        .payment__table td:nth-child(5) {
            width: 14%;
        }

        .remove__input__padding .form-control {
            padding: 0 .5rem;
            line-height: 1;
            color: #232859;
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
                    <div class="payment-status-left payment-pending position-relative">
                        <div class="border-line"></div>
                        <div class="modal-body p-4 p-xl-5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-uppercase">
                                    <p class="f-01 mb-0 payment-status-text"><?php echo showOtherLangText('PENDING') ?></p>
                                    <p class="f-01"><?php echo showOtherLangText('PAYMENT') ?></p>
                                </div>
                                <div>
                                    <?php

                                    $clientQry = " SELECT * FROM tbl_client WHERE id = '" . $_SESSION['accountId'] . "' ";
                                    $clientResult = mysqli_query($con, $clientQry);
                                    $clientResultRow = mysqli_fetch_array($clientResult);

                                    if ($clientResultRow['logo'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . '/clientLogo/' . $clientResultRow['logo'])) {
                                        echo '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/clientLogo/' . $clientResultRow['logo'] . '" width="100" height="100" style="object-fit:contain;">';
                                    }

                                    ?>
                                </div>
                            </div>

                            <div class="mt-4 row g-0 payment-tabel-header">
                                <div class="col-sm-7">
                                    <table class="table1 table01">
                                        <tbody>
                                            <tr>
                                                <td class="font-wt">
                                                    <?php echo showOtherLangText('Supplier Invoice'); ?> #
                                                </td>
                                                <td class="in-val-p dark__td__color" id="supInv"><?php echo $supplierInvoice; ?></td>
                                            </tr>

                                            <tr>
                                                <td class="font-wt">
                                                    <?php echo showOtherLangText('PAYMENT') ?> #
                                                </td>
                                                <td class="in-val-p dark__td__color"><?php echo setPaymentId($paymentId); ?></td>
                                            </tr>

                                            <tr>
                                                <td class="font-wt"><?php echo showOtherLangText('Task'); ?> #</td>
                                                <td class="in-val-p dark__td__color"><?php echo $orderRow['ordNumber'] ?></td>
                                            </tr>

                                            <tr>
                                                <td class="font-wt"><?php echo showOtherLangText('Date'); ?> #
                                                </td>
                                                <td class="in-val-p dark__td__color"><?php echo $orderRow['ordDateTime'] ?></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-5">

                                    <?php
                                    $sql = " SELECT * FROM tbl_country WHERE id = '" . $clientResultRow['country'] . "' ";
                                    $resSet = mysqli_query($con, $sql);
                                    $resultRow = mysqli_fetch_array($resSet);
                                    ?>
                                    <table class="w-100">
                                        <tbody class="table1 table01 fl-right cmp-dtl text-start text-sm-end">
                                            <tr>
                                                <td><?php echo $clientResultRow['accountName']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $clientResultRow['address_one'] . ',' . $clientResultRow['address_two'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $clientResultRow['city'] . ',' . $resultRow['name'] ?> </td>
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
                            <div class="Payment-to paymentto-p">
                                <p class="f-02 mb-2"><?php echo showOtherLangText('PAYMENT To') ?></p>
                                <p id="name" class="f-03 in-val-p mb-1"><?php echo $supName; ?></p>
                                <p id="address" class="f-03 in-val-p mb-1"><?php echo isset($payInfoRow['supplierAddress']) ? nl2br($payInfoRow['supplierAddress']) : nl2br($address); ?></p>
                                <p id="email" class="f-03 in-val-p mb-1"><?php echo isset($payInfoRow['supplierEmail']) ? $payInfoRow['supplierEmail'] : $email; ?></p>
                                <p id="phone" class="f-03 in-val-p mb-1"><?php echo isset($payInfoRow['supplierPhone']) ? $payInfoRow['supplierPhone'] : $phone; ?></p>
                            </div>
                            <br>

                            <div class="table-responsive mt-4">

                                <table class="modal-table fs-12 w-100 payment__table">
                                    <thead>
                                        <tr class="tr-bg-1">
                                            <th>#</th>
                                            <th><?php echo showOtherLangText('Item'); ?></th>
                                            <th><?php echo showOtherLangText('Unit'); ?></th>
                                            <th><?php echo showOtherLangText('Ordered'); ?><br><?php echo showOtherLangText('Quantity'); ?></th>
                                            <th><?php echo showOtherLangText('Received'); ?><br><?php echo showOtherLangText('Quantity'); ?></th>
                                            <?php
                                            if ($orderRow['ordCurId'] > 0) { ?>

                                                <th class="th-bg-1">
                                                    <?php echo showOtherLangText('Price'); ?>(<?php echo $curDetail['curCode']; ?>)
                                                </th>

                                            <?php } else { ?>

                                                <th class="th-bg-1"><?php echo showOtherLangText('Price'); ?>(<?php echo $getDefCurDet['curCode'] ?>)</th>

                                            <?php }
                                            if ($orderRow['ordCurId'] > 0) { ?>

                                                <th class="th-bg-1">
                                                    <?php echo showOtherLangText('Total'); ?>(<?php echo $curDetail['curCode']; ?>)
                                                </th>

                                            <?php } else { ?>

                                                <th class="th-bg-1"><?php echo showOtherLangText('Total'); ?>(<?php echo $getDefCurDet['curCode'] ?>)</th>

                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody class="tabel-body-p">
                                        <?php
                                        //item level fee listing start here 
                                        $sql = "SELECT cif.itemName, cif.unit, cif.amt, tod.* FROM tbl_order_details tod 
                                    INNER JOIN tbl_custom_items_fee cif ON(tod.customChargeId = cif.id) AND tod.account_id = cif.account_id
                                    WHERE tod.ordId = '" . $_GET['orderId'] . "' AND tod.account_id = '" . $_SESSION['accountId'] . "' AND tod.customChargeType=1 ORDER BY cif.itemName ";
                                        $custFeeResult = mysqli_query($con, $sql);
                                        $x = 0;
                                        while ($custFeeRow = mysqli_fetch_array($custFeeResult)) {
                                            $x++;
                                        ?>
                                            <tr>
                                                <td><a title="<?php echo showOtherLangText('Delete') ?>" href="javascript:void(0)" onClick="getItemDelNumb('<?php echo $custFeeRow['id']; ?>', '<?php echo $_GET['orderId']; ?>');" style="color:#808080"><span class="dlTe"></span></a></td>
                                                <td><?php echo $custFeeRow['itemName']; ?></td>
                                                <td><?php echo $custFeeRow['unit']; ?></td>
                                                <td>1</td>
                                                <td>1</td>
                                                <?php
                                                //price column
                                                if ($orderRow['ordCurId'] > 0) { ?>
                                                    <td><?php echo showOtherCur(($custFeeRow['amt'] * $curDetail['amt']), $orderRow['ordCurId']); ?></td>
                                                <?php } else { ?><td class="pay-dt"><?php echo showPrice($custFeeRow['amt'], $getDefCurDet['curCode']); ?>

                                                    <?php }
                                                if ($orderRow['ordCurId'] > 0) { ?>

                                                    <td class="pay-dt" style="font-weight: bold;"><?php echo showOtherCur(($custFeeRow['amt'] * $curDetail['amt']), $orderRow['ordCurId']); ?></td>

                                                <?php } else { ?>

                                                    <td class="pay-dt" style="font-weight: bold;"><?php echo showPrice($custFeeRow['amt'], $getDefCurDet['curCode']); ?></td>

                                                <?php } ?>
                                            </tr>
                                        <?php }
                                        while ($ordDetailRow = mysqli_fetch_array($ordDetail)) {
                                            $x++;
                                        ?>
                                            <tr>
                                                <td><?php echo $x; ?></td>
                                                <td><?php echo $ordDetailRow['itemName']; ?></td>
                                                <td><?php echo $ordDetailRow['purchaseUnit'] ?></td>
                                                <td><?php echo $ordDetailRow['ordQty'] ?></td>
                                                <td><?php echo $ordDetailRow['qtyReceived']; ?></td>
                                                <?php
                                                //price column
                                                if ($orderRow['ordCurId'] > 0) { ?>
                                                    <td><?php echo showOtherCur(($ordDetailRow['ordCurPrice'] * $ordDetailRow['factor']), $orderRow['ordCurId']); ?></td>
                                                <?php } else { ?>
                                                    <td> <?php echo showPrice($ordDetailRow['ordPrice'] * $ordDetailRow['factor'], $getDefCurDet['curCode']); ?>
                                                    </td>

                                                <?php } ?>
                                                <?php
                                                //price column
                                                if ($orderRow['ordCurId'] > 0) { ?>
                                                    <td><?php echo $ordDetailRow['qtyReceived'] > 0 ? showOtherCur($ordDetailRow['ordCurPrice'] * $ordDetailRow['factor'] * $ordDetailRow['qtyReceived'], ($orderRow['ordCurId'])) : showOtherCur($ordDetailRow['ordQty'] * $ordDetailRow['factor'] * $ordDetailRow['ordCurPrice'], ($orderRow['ordCurId'])); ?>
                                                    </td>

                                                <?php } else { ?>
                                                    <td><?php echo $ordDetailRow['qtyReceived'] > 0 ? showPrice($ordDetailRow['ordPrice'] * $ordDetailRow['factor'] * $ordDetailRow['qtyReceived'], $getDefCurDet['curCode']) : showPrice($ordDetailRow['ordPrice'] * $ordDetailRow['factor'] * $ordDetailRow['ordQty'], $getDefCurDet['curCode']); ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- <div class="divider-blue"></div> -->
                            <br>
                            <div class="row g-4 justify-content-between total__table__res__row__reverse">
                                <div class="data__col col-md-4">
                                    <div class="table1">
                                        <p class="f-02 mb-1"><?php echo showOtherLangText('Payment Method') ?>:</p>
                                    </div>
                                </div>
                                <div class="table__col">
                                    <table class="grand-total-tabel w-100">
                                        <?php
                                        //get the sum of all product and item level charges  
                                        $sqlSet = "SELECT SUM(totalAmt) as sum1, SUM(curAmt) AS sum2 from tbl_order_details where ordId='" . $_GET['orderId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  AND (customChargeType='1' OR customChargeType='0')";
                                        $resultSet = mysqli_query($con, $sqlSet);
                                        $chargeRow = mysqli_fetch_array($resultSet);
                                        $totalPrice = $chargeRow['sum1'];
                                        $totalPriceOther = ($chargeRow['sum2']);

                                        //to find order level charge
                                        $ordCount = "SELECT * from tbl_order_details where ordId='" . $_GET['orderId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' AND customChargeType='2' ";
                                        $ordCountResult = mysqli_query($con, $ordCount);
                                        $ordCountRow = mysqli_num_rows($ordCountResult);
                                        $netTotalAmt = ($totalPrice + $totalTaxCharges + $totalDiscountPercent + $totalFixedCharges);
                                        //echo $netTotalAmt;
                                        $netTotalAmtOther = ($totalPriceOther + $totalTaxChargesOther + $totalDiscountPercentOther + $totalFixedChargesOther);
                                        if ($ordCountRow > 0) {
                                            //sub total will show only when any order level charges is in orders.
                                            if ($orderRow['ordCurId'] > 0) { ?>
                                                <!-- grand totale  here -->

                                                <tr>
                                                    <td><?php echo showOtherLangText('Sub Total'); ?></td>
                                                    <td><?php echo showOtherCur($totalPriceOther, $orderRow['ordCurId']); ?></td>
                                                </tr>
                                            <?php } else { ?>
                                                <tr>
                                                    <td><?php echo showOtherLangText('Sub Total'); ?></td>
                                                    <td style="font-weight: bold;">
                                                        <?php echo showPrice($totalPrice, $getDefCurDet['curCode']); ?></td>
                                                </tr>

                                            <?php }
                                        }


                                        //Starts order level fixed discount charges
                                        $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
WHERE od.ordId = '" . $_GET['orderId'] . "' AND od.account_id = '" . $_SESSION['accountId'] . "' and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

                                        $ordQry = mysqli_query($con, $sql);

                                        $fixedCharges = 0;
                                        $fixedChargesOther = 0;

                                        //need to add group by here and remove one query
                                        $sql = " SELECT SuM(totalAmt) AS totalFixedCharges, SUM(curAmt) AS totalFixedChargesOther, tp.feeName FROM tbl_order_details od 
INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
WHERE od.ordId = '" . $_GET['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";
                                        $sumQry = mysqli_query($con, $sql);
                                        $totalSum = mysqli_fetch_array($sumQry);

                                        $totalFixedCharges = $totalSum['totalFixedCharges'];


                                        while ($row = mysqli_fetch_array($ordQry)) //show here order level charges
                                        {
                                            $fixedCharges = $row['price'];
                                            $fixedChargesOther = $row['curAmt'];
                                            $totalFixedChargesOther += $fixedChargesOther;

                                            if ($orderRow['ordCurId'] > 0) { ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex gap-1 align-items-center">
                                                            <a title="<?php echo showOtherLangText('Delete') ?>" href="javascript:void(0)" onClick="getItemDelNumb('<?php echo $row['id']; ?>', '<?php echo $_GET['orderId']; ?>');" style="color:#808080" class="glyphicon glyphicon-trash"><span class="dlTe"></span></a>
                                                            &nbsp;<?php echo $row['feeName']; ?>
                                                        </div>
                                                    </td>
                                                    <td style="font-weight: bold;">
                                                        <?php echo showOtherCur($fixedChargesOther, $orderRow['ordCurId']); ?></td>
                                                </tr>

                                            <?php } else { ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex gap-1 align-items-center">
                                                            <a title="<?php echo showOtherLangText('Delete') ?>" href="javascript:void(0)" onClick="getItemDelNumb('<?php echo $row['id']; ?>', '<?php echo $_GET['orderId']; ?>');" style="color:#808080" class="glyphicon glyphicon-trash"><span class="dlTe"></span></a>

                                                            <?php echo $row['feeName']; ?>
                                                        </div>
                                                    </td>
                                                    <td style="font-weight: bold;">
                                                        <?php echo showprice($fixedCharges, $getDefCurDet['curCode']); ?></td>
                                                </tr>


                                        <?php

                                            }
                                        }
                                        ?>
                                        <?php
                                        //Start order level discoutns

                                        $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
WHERE od.ordId = '" . $_GET['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
                                        $ordQry = mysqli_query($con, $sql);

                                        $perCharges = 0;
                                        $itemIds = [];
                                        $totalCharges = 0;
                                        while ($row = mysqli_fetch_array($ordQry)) //show here order level charges
                                        {
                                            $itemIds[] = $row['customChargeId'];
                                            $totalCharges = $row['totalAmt'];
                                            $perCharges += $row['totalAmt'];

                                            $discountPercent = $totalPrice * $totalCharges / 100;
                                            $discountPercentOther = $totalPriceOther * $totalCharges / 100;
                                            $totalDiscountPercent = $totalPrice * $perCharges / 100;
                                            $totalDiscountPercentOther = $totalPriceOther * $perCharges / 100;
                                            if ($row) {

                                                if ($orderRow['ordCurId'] > 0) { ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex gap-1 align-items-center">
                                                                <a title="<?php echo showOtherLangText('Delete') ?>" href="javascript:void(0)" onClick="getItemDelNumb('<?php echo $row['id']; ?>', '<?php echo $_GET['orderId']; ?>');" style="color:#808080" class="glyphicon glyphicon-trash"><span class="dlTe"></span></a>
                                                                &nbsp;<?php echo $row['feeName']; ?>
                                                            </div>
                                                        </td>
                                                        <td style="font-weight: bold;">
                                                            <?php echo showOtherCur($discountPercentOther, $orderRow['ordCurId']); ?></td>
                                                    </tr>

                                                <?php } else { ?>

                                                    <tr>
                                                        <td>
                                                            <div class="d-flex gap-1 align-items-center">
                                                                <a title="<?php echo showOtherLangText('Delete') ?>" href="javascript:void(0)" onClick="getItemDelNumb('<?php echo $row['id']; ?>', '<?php echo $_GET['orderId']; ?>');" style="color:#808080" class="glyphicon glyphicon-trash"><span class="dlTe"></span></a>
                                                                &nbsp;<?php echo $row['feeName']; ?>
                                                            </div>
                                                        </td>
                                                        <td style="font-weight: bold;">
                                                            <?php echo showprice($discountPercent, $getDefCurDet['curCode']); ?></td>
                                                    </tr>

                                                <?php
                                                }
                                            }
                                        } //End of order level discount
                                        //Starts order level tax discount charges
                                        $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
        INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
        WHERE od.ordId = '" . $_GET['orderId'] . "'   AND od.account_id = '" . $_SESSION['accountId'] . "' and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
                                        $ordQry = mysqli_query($con, $sql);

                                        $taxCharges = 0;
                                        while ($row = mysqli_fetch_array($ordQry)) //show here order level charges
                                        {
                                            $tax = $row['price'];

                                            $taxCharges = (($totalPrice + $totalFixedCharges + $totalDiscountPercent) * $tax / 100);
                                            $taxChargesOther = (($totalPriceOther + $totalFixedChargesOther + $totalDiscountPercentOther) * $tax / 100);
                                            $totalTaxCharges += (($totalPrice + $totalFixedCharges + $totalDiscountPercent) * $tax / 100);
                                            $totalTaxChargesOther += (($totalPriceOther + $totalFixedChargesOther + $totalDiscountPercentOther) * $tax / 100);

                                            if ($orderRow['ordCurId'] > 0) { ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex gap-1 align-items-center">
                                                            <a title="<?php echo showOtherLangText('Delete') ?>" href="javascript:void(0)" onClick="getItemDelNumb('<?php echo $row['id']; ?>', '<?php echo $_GET['orderId']; ?>');" style="color:#808080" class="glyphicon glyphicon-trash"><span class="dlTe"></span></a>
                                                            &nbsp;<?php echo $row['feeName']; ?>
                                                        </div>
                                                    </td>
                                                    <td style="font-weight: bold;">
                                                        <?php echo showOtherCur($taxChargesOther, $orderRow['ordCurId']) ?></td>
                                                </tr>

                                            <?php } else { ?>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex gap-1 align-items-center">
                                                            <a title="<?php echo showOtherLangText('Delete') ?>" href="javascript:void(0)" onClick="getItemDelNumb('<?php echo $row['id']; ?>', '<?php echo $_GET['orderId']; ?>');" style="color:#808080" class="glyphicon glyphicon-trash"><span class="dlTe"></span></a>
                                                            &nbsp;<?php echo $row['feeName']; ?>
                                                        </div>
                                                    </td>
                                                    <td style="font-weight: bold;">
                                                        <?php echo showprice($taxCharges, $getDefCurDet['curCode']) ?></td>
                                                </tr>
                                            <?php
                                            }
                                        } //end order level tax discount charges


                                        $netTotalAmt = ($totalPrice + $totalTaxCharges + $totalDiscountPercent + $totalFixedCharges);

                                        $netTotalAmtOther = ($totalPriceOther + $totalTaxChargesOther + $totalDiscountPercentOther + $totalFixedChargesOther);

                                        if ($orderRow['ordCurId'] > 0) { ?>


                                            <tr class="grand-total" style=" max-height: 38px;">
                                                <th><?php echo showOtherLangText('Grand Total'); ?>(<?php echo $curDetail['curCode']; ?>)</th>
                                                <th><?php echo showOtherCur($netTotalAmtOther, $orderRow['ordCurId']) ?></th>
                                            </tr>
                                            <tr class="grand-total" style=" max-height: 38px;">
                                                <th><?php echo showOtherLangText('Grand Total'); ?> (<?php echo $getDefCurDet['curCode'] ?>)</th>
                                                <th><?php echo showprice($netTotalAmt, $getDefCurDet['curCode']); ?></th>
                                            </tr>
                                        <?php } else { ?>
                                            <tr class="grand-total" style=" max-height: 38px;">
                                                <th><?php echo showOtherLangText('Grand Total'); ?> (<?php echo $getDefCurDet['curCode'] ?>)
                                                </th>
                                                <th style="font-weight: bold;"><?php echo showprice($netTotalAmt, $getDefCurDet['curCode']); ?>
                                                </th>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
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
                                            <?php echo showOtherLangText('PAYMENT') ?>
                                        </h6>
                                    </div>

                                    <div class="inv-top mb-3">
                                        <table class="mr-btm remove__input__padding">
                                            <tbody class="payDetail">
                                                <tr>
                                                    <td><?php echo showOtherLangText('PAYMENT') ?> #</td>
                                                    <td>
                                                        <input type="text" style="cursor: text; background:none;" class="form-control form-control-1" onchange="getVal();" name="paymentId" id="paymentId" autocomplete="off" value="<?php echo setPaymentId($paymentId); ?>" readonly="">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Task') ?> #</td>
                                                    <td>
                                                        <input type="text" style="cursor: text; background:none;" class="form-control form-control-1" name="ordNumber" value="<?php echo $orderRow['ordNumber'] ?>" autocomplete="off" readonly="">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Date'); ?> #</td>
                                                    <td class="payDate"><?php echo $orderRow['ordDateTime'] ?></td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="inv-detail mb-3">
                                        <table cellpadding="0" cellspacing="0" width="100%">
                                            <tbody class="frm-info" style="line-height: 40px;">
                                                <tr class="mb-adrs">
                                                    <td><?php echo showOtherLangText('Supplier Invoice'); ?> # </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="supplierInvoice" id="supplierInvoice" onchange="getVal();" oninput="changeSupInv();" value="<?php echo $supplierInvoice; ?>" autocomplete="off">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Payment To'); ?></td>
                                                    <td>
                                                        <input type="text" class="form-control" name="supplierName" id="supplierName" onchange="getVal();" oninput="changeSupName();" value="<?php echo $supName; ?>" autocomplete="off">
                                                    </td>
                                                </tr>


                                                <tr class="mb-adrs">
                                                    <td><?php echo showOtherLangText('Supplier Address'); ?></td>
                                                    <td>
                                                        <textarea class="form-control" name="supplierAddress" id="supplierAddress" onchange="getVal();" oninput="changeSupAddress();" autocomplete="off" placeholder="<?php echo showOtherLangText('Address') ?>" /><?php echo isset($payInfoRow['supplierAddress']) ? ($payInfoRow['supplierAddress']) : ($address); ?></textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Email') ?></td>
                                                    <td>
                                                        <input type="text" class="form-control" name="supplierEmail" id="supplierEmail" onchange="getVal();" oninput="changeSupEmail()" value="<?php echo isset($payInfoRow['supplierAddress']) ? $payInfoRow['supplierEmail'] : $email; ?>" autocomplete="off" placeholder="<?php echo showOtherLangText('Email') ?>">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Phone') ?></td>
                                                    <td>
                                                        <input type="text" class="form-control" name="supplierPhone" id="supplierPhone" onchange="getVal();" oninput="changeSupPhone()" value="<?php echo isset($payInfoRow['supplierAddress']) ? $payInfoRow['supplierPhone'] : $phone; ?>" autocomplete="off" placeholder="<?php echo showOtherLangText('Phone no') ?>">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="divider-1"></div>


                                    <div class="invTotal">
                                        <table class="w-100">
                                            <tbody> 
                                                <!-- Added: shuvro -->
                                                <tr class="payDetail">
                                                    <td><?php echo showOtherLangText('Total Amount') . ' (' . $curDetail['curCode'] . ')'; ?></td>
                                                    <td>
                                                        <input type="text" style="cursor: text; background:none;" class="form-control form-control-01 px-0" name="totalAmountOther" id="totalAmountOther" value="<?php echo showOtherCur($netTotalAmtOther, $orderRow['ordCurId']); ?>" autocomplete="off" readonly="" >
                                                    </td>
                                                </tr>
                                                <!-- Added: shuvro -->


                                                <tr class="payDetail">
                                                    <td><?php echo showOtherLangText('Total Amount') . ' (' . $getDefCurDet['curCode'] . ')'; ?></td>
                                                    <td>
                                                        <input type="text" style="cursor: text; background:none;" class="form-control form-control-01 px-0" name="totalAmount" id="totalAmount" value="<?php echo showprice($netTotalAmt, $getDefCurDet['curCode']); ?>" autocomplete="off" readonly="" >
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
                                            <?php echo showOtherLangText('Add Fee'); ?> <i class="fa-solid fa-angle-down"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <!-- Default dropend button -->
                                                <div class="btn-group dropend dropdown-hover w-100">
                                                    <a type="button" class="dropdown-item  dropdown-toggle dropdown-toggle-hover  d-j-b" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <?php echo showOtherLangText('Service Item'); ?> <span class="caret"> <i class="fa-solid fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <!-- Dropdown menu links -->
                                                        <?php
                                                        $sql = " SELECT * FROM tbl_custom_items_fee WHERE visibility='1' AND account_id='" . $_SESSION['accountId'] . "' ";
                                                        $customItemFee = mysqli_query($con, $sql);
                                                        while ($resultRow = mysqli_fetch_array($customItemFee)) {
                                                            echo "<li><a class='dropdown-item' tabindex='-1' href='supplierPaymentDetail.php?page=runningTask&orderId=" . $orderRow['id'] . "&action=pay&feeType=openFixedFee&id=" . $resultRow['id'] . " ' >" . $resultRow['itemName'] . "</a></li>";
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#new-service-item" class="dropdown-item" href="javascript:void(0)"><?php echo showOtherLangText('New Service Item'); ?></a></li>

                                            <li>
                                            <li>
                                                <!-- Default dropend button -->
                                                <div class="btn-group dropend dropdown-hover w-100">
                                                    <a type="button" class="dropdown-item  dropdown-toggle dropdown-toggle-hover  d-j-b" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <?php echo showOtherLangText('Fee'); ?> <i class="fa-solid fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <!-- Dropdown menu links -->
                                                        <?php
                                                        $sqlQry = " SELECT * FROM tbl_order_fee WHERE visibility='1' AND account_id='" . $_SESSION['accountId'] . "' ";
                                                        $ordFeeFetch = mysqli_query($con, $sqlQry);
                                                        while ($resultRow = mysqli_fetch_array($ordFeeFetch)) {
                                                            echo "<li class='innerLi'><a class='dropdown-item' tabindex='-1' href='supplierPaymentDetail.php?page=runningTask&orderId=" . $orderRow['id'] . "&action=pay&feeType=openFixedPerFee&id=" . $resultRow['id'] . " '>" . $resultRow['feeName'] . "</a></li>";
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </li>
                                            </li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#new-fees-item" class="dropdown-item" href="javascript:void(0)"><?php echo showOtherLangText('New Fee') ?></a></li>
                                        </ul>
                                    </div>
                                    <!-- End of dropdown menu -->


                                    <div class="paySelect">
                                        <table cellpadding="0" cellspacing="0" width="100%" style="margin-top: 20px;">
                                            <tbody class="form-info">
                                                <tr class="">
                                                    <td><?php echo showOtherLangText('Payment Type') ?></td>
                                                    <td>

                                                        <select name="paymentType" id="paymentType" class="form-select  form-select-1" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')" onchange="this.setCustomValidity('')" required>

                                                            <option value=""><?php echo showOtherLangText('Select'); ?></option>

                                                            <?php
                                                            $sqlSet = " SELECT * FROM tbl_payment_mode WHERE account_id = '" . $_SESSION['accountId'] . "'  ";
                                                            $payModeResult = mysqli_query($con, $sqlSet);

                                                            while ($payModeRow = mysqli_fetch_array($payModeResult)) { ?>

                                                                <option value="<?php echo $payModeRow['id'] ?>"><?php echo $payModeRow['modeName']; ?></option>

                                                            <?php
                                                            }
                                                            ?>

                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Account') ?></td>
                                                    <td>
                                                        <!-- <select name="accountId" id="accountId" class="form-select form-select-1" oninvalid="this.setCustomValidity('Please fill out this field.')" onchange="fetchAccountDetail(this.value),this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" required="">

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


                    </select> -->
                                                        <select name="accountId" id="accountId" class="form-select form-select-1" onchange="fetchAccountDetail(this.value),this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')" required>

                                                            <option value=""><?php echo showOtherLangText('Select'); ?></option>

                                                            <?php
                                                            $sqlSet = " SELECT * FROM  tbl_accounts WHERE account_id = '" . $_SESSION['accountId'] . "'  ";
                                                            $accResultFetch = mysqli_query($con, $sqlSet);

                                                            while ($accRow = mysqli_fetch_array($accResultFetch)) { ?>
                                                                <option value="<?php echo $accRow['id']; ?>">
                                                                    <?php echo  $accRow['accountName']; ?></option>

                                                            <?php
                                                            }
                                                            ?>

                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Account No.') ?></td>
                                                    <td id="accountNumber"></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Balance') ?></td>
                                                    <td id="accountBalance"></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Currency') ?> </td>
                                                    <td>
                                                        <span id="currencyName">

                                                        </span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Amount') ?></td>
                                                    <td>
                                                        <input type="text" class="form-control" name="amount" id="amount" value="" autocomplete="off" readonly="">
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>

                                    </div>


                                    <div class="allBtn my-3 d-flex justify-content-between align-items-center w-100">
                                        <div>
                                            <button class="btn wht-btn" type="button" onclick="window.location.href='history.php'"><?php echo showOtherLangText('Back'); ?></button>
                                        </div>

                                        <div>
                                            <button class="btn final-btn" type="submit" name="submitBtn"><?php echo showOtherLangText('Finalize Payment'); ?></button>
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
                                                        <?php echo showOtherLangText('save to fixed service item list'); ?></label><br>
                                                </div>
                                            </div>



                                            <div>
                                                <div><button class="btn wht-btn" type="submit"><?php echo showOtherLangText('Add'); ?></button>&nbsp; &nbsp;
                                                    <button class="btn wht-btn" id="backBtn" type="button"><?php echo showOtherLangText('Back'); ?></button>
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </form>
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

                                                        onChange="this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" required />
                                                    <select class="form-control" name="feeType" id="typeOfFee"
                                                        oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')"
                                                        onChange="this.setCustomValidity('')" required>
                                                        <option value="2"><?php echo showOtherLangText('Fixed Fee'); ?></option>
                                                        <option value="3"><?php echo showOtherLangText('Percentage Fee'); ?>
                                                        </option>
                                                    </select>
                                                    <input type="text" class="form-control" id="amt" name="amt" value=""
                                                        autocomplete="off"
                                                        placeholder="<?php echo showOtherLangText('Fee Amount') . ' ' . $getDefCurDet['curCode']; ?>"

                                                        onChange="this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" required />

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
                                                        <button type="submit" id="feesave_add" name="feesave_add" class="sub-btn btn btn-primary std-btn"><?php echo showOtherLangText('Add'); ?></button>
                                                    </div>
                                                </div>
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
    <form action="" name="addServiceFeeFrm" class="addUser-Form row container glbFrm-Cont" id="addServiceFeeFrm" method="post" autocomplete="off">
        <div class="modal" tabindex="-1" id="new-service-item" aria-labelledby="add-CategoryLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h1 class="modal-title h1"><?php echo showOtherLangText('Service Name'); ?></h1>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" id="itemName" name="itemName" value="" placeholder="<?php echo showOtherLangText('Service Name'); ?> *" autocomplete="off"

                            onChange="this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" required>
                        <input type="number" class="form-control" id="feeAmt" name="itemFeeAmt" value="" placeholder="<?php echo showOtherLangText('Amount') . ' ' . $getDefCurDet['curCode']; ?> *" autocomplete="off"

                            onChange="this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" required>
                        <input type="text" class="form-control" id="unit" name="unit" value="" placeholder="<?php echo showOtherLangText('Unit'); ?> *" autocomplete="off"

                            onChange="this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" required>
                    </div>
                    <div>
                        <div class="feeSave">
                            <input type="checkbox" class="optionCheck" id="visibility" name="visibility" value="1">
                            <span class="subTittle1" style="vertical-align:text-top;"><?php echo showOtherLangText('save to fixed service item list'); ?></span><br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btnBg">
                            <button type="submit" id="addFee" name="addFee" class="btn sub-btn std-btn"><?php echo showOtherLangText('Add'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="modal" tabindex="-1" id="delete-popup" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php echo showOtherLangText('Are you sure to delete this record?') ?> </h1>
                </div>

                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="button" data-bs-dismiss="modal" class="btn sub-btn std-btn"><?php echo showOtherLangText('No'); ?></button>
                    </div>
                    <div class="btnBg">
                        <button type="button" onclick="" class="deletelink  btn sub-btn std-btn"><?php echo showOtherLangText('Yes'); ?></button>
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



        $('.date-box-search').click(function() {

            $('#frm').submit();

        });


        //get other currencyAmount and set the total other currency value
        function fetchAccountDetail(value) {

            var account = document.getElementById('accountId').value;
            var TotalAmount = document.getElementById('totalAmount').value;
            if (account > 0) {
                $.ajax({
                        method: "POST",
                        url: "common_ajax.php",
                        dataType: 'json',
                        data: {
                            accountId: account,
                            TotalAmount: TotalAmount,
                            action: "payment"
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
        $(document).ready(function() {

            var paymentId = $('#paymentId').val();
            var supplierInvoice = $('#supplierInvoice').val();
            var supplierName = $('#supplierName').val();
            var supplierAddress = $('#supplierAddress').val();
            var supplierEmail = $('#supplierEmail').val();
            var supplierPhone = $('#supplierPhone').val();

            if (supplierInvoice != '' && supplierName != '' && supplierAddress != '' && supplierContact != '' &&
                supplierPhone != '') {
                $.ajax({
                    method: "POST",
                    data: {
                        supplierInvoice: supplierInvoice,
                        supplierName: supplierName,
                        supplierAddress: supplierAddress,
                        supplierEmail: supplierEmail,
                        supplierPhone: supplierPhone,
                        paymentId: paymentId
                    }

                })
            }

        })

        function getVal() {
            var paymentId = $('#paymentId').val();
            var supplierInvoice = $('#supplierInvoice').val();
            var supplierName = $('#supplierName').val();
            var supplierAddress = $('#supplierAddress').val();
            var supplierEmail = $('#supplierEmail').val();
            var supplierPhone = $('#supplierPhone').val();

            if (supplierInvoice != '' && supplierName != '' && supplierAddress != '' && supplierEmail != '' &&
                supplierPhone != '') {
                $.ajax({
                    method: "POST",
                    data: {
                        supplierInvoice: supplierInvoice,
                        supplierName: supplierName,
                        supplierAddress: supplierAddress,
                        supplierEmail: supplierEmail,
                        supplierPhone: supplierPhone,
                        paymentId: paymentId
                    }

                })
            }
        }
    </script>
    <script>
        function getItemDelNumb(delId, orderId) {
            //var newOnClick = 'supplierPaymentDetail.php?delId='+delId+'&orderId='+orderId;
            var newOnClick = "window.location.href='supplierPaymentDetail.php?delId=" + delId + '&orderId=' + orderId + "'";
            $('.deletelink').attr('onclick', newOnClick);
            $('#delete-popup').modal('show');
        }
    </script>
    <script>
        function nl2br(str, is_xhtml) {
            var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
            return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
        }

        //show all input value on the left side when we fill supplier details one by one in right side input box 
        function changeSupInv() {
            var supplierInvoice = document.getElementById('supplierInvoice').value;
            var supInv = document.getElementById('supInv');
            supInv.innerHTML = supplierInvoice;
        }

        function changeSupName() {
            var supplierName = document.getElementById('supplierName').value;
            var name = document.getElementById('name');
            name.innerHTML = supplierName;
        }

        function changeSupAddress() {
            var supplierAddress = document.getElementById('supplierAddress').value;
            var address = document.getElementById('address');
            address.innerHTML = nl2br(supplierAddress);
        }

        function changeSupEmail() {
            var supplierEmail = document.getElementById('supplierEmail').value;
            var email = document.getElementById('email');
            email.innerHTML = supplierEmail;
        }

        function changeSupPhone() {
            var supplierPhone = document.getElementById('supplierPhone').value;
            var phone = document.getElementById('phone');
            phone.innerHTML = supplierPhone;
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Find all elements with the class 'grand-total'
            const grandTotalElements = document.querySelectorAll('.grand-total');

            if (grandTotalElements.length > 0) {
                grandTotalElements[0].classList.add('active__th__row');
                // grandTotalElements[0].setAttribute('style', 'background: #7A89FF !important; color: white !important;');
            }
        });
    </script>
    <div id="dialog" style="display: none;">
        <?php echo showOtherLangText('Are you sure to delete this record?') ?>
    </div>
</body>

</html>