<?php
include('inc/dbConfig.php'); //connection details


if (!isset($_SESSION['adminidusername'])) {

    echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


//check page permission

if (!in_array('3', $checkPermission)) {
    echo "<script>window.location='index.php'</script>";
    exit;
}


if (isset($_POST['clickApproveBtn']) || isset($_POST['clickBackBtn']) || isset($_POST['clickAnywhere']) && ($_POST['clickApproveBtn'] == 1 || $_POST['clickBackBtn'] == 1 || $_POST['clickAnywhere'] == 1)) {
    unset($_SESSION['errorQty']);
    unset($_SESSION['errorStockPriceChanged']);
    unset($_SESSION['errorQtyOrderId']);
    die();
}


if (isset($_SESSION['supplierIdOrd'])) {
    unset($_SESSION['supplierIdOrd']);
}


if (isset($_SESSION['ordDeptId'])) {
    unset($_SESSION['ordDeptId']);
}

//when click on delete order this code will perform
if (isset($_GET['canId']) && $_GET['canId']) {
    $sql = "DELETE FROM tbl_orders WHERE id='" . $_GET['canId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ";
    mysqli_query($con, $sql);

    $sql = " SELECT * FROM tbl_order_details WHERE ordId = '" . $_GET['canId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' AND pId > 0 ";
    $getOrdDetRes = mysqli_query($con, $sql);
    while ($getOrdDetRow = mysqli_fetch_array($getOrdDetRes)) {
        $updateStockQry = " UPDATE tbl_stocks SET tempQty = (tempQty - '" . $getOrdDetRow['qty'] . "') WHERE pId = '" . $getOrdDetRow['pId'] . "' And account_id = '" . $_SESSION['accountId'] . "' ";
        mysqli_query($con, $updateStockQry);
    }

    $sql = "DELETE FROM tbl_order_details WHERE ordId='" . $_GET['canId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ";
    mysqli_query($con, $sql);

    echo "<script>window.location='runningOrders.php?cancel=1&type=" . $_GET['type'] . " '</script>";
}
//$_SESSION['errorQty'] = 1;

if (isset($_GET['orderId']) && isset($_GET['confirm'])) {

    $error = '';
    if ($_GET['confirm'] == 2) //for recusation
    {
        //this is for light box popup once quanity is being updated 

        $updateNetTotal = false;
        if (isset($_POST['reqQty']) && !empty($_POST['reqQty'])) {

            foreach ($_POST['reqQty'] as $productId => $qty) {
                $sql = " UPDATE tbl_order_details SET qty = '" . $qty . "',
			totalAmt = (price*$qty) WHERE pId = '" . $productId . "' AND ordId = '" . $_POST['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
                mysqli_query($con, $sql);
            }

            $updateNetTotal = true;
        }

        if (isset($_POST['stockPriceChangedConfirmedOrdId']) && !empty($_POST['stockPriceChangedConfirmedPids'])) {

            foreach ($_POST['stockPriceChangedConfirmedPids'] as $pId) {
                $sql = "SELECT * FROM tbl_stocks WHERE pId = '" . $pId . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
                $stkQry = mysqli_query($con, $sql);
                $stkRow = mysqli_fetch_array($stkQry);

                $stockPrice = $stkRow['stockPrice'];
                $sql = " UPDATE tbl_order_details SET price = '" . $stkRow['stockPrice'] . "',stockPrice = '" . $stkRow['stockPrice'] . "',
                lastPrice = '" . $stkRow['lastPrice'] . "',
                totalAmt = ($stockPrice*qty) WHERE pId = '" . $pId . "' AND ordId = '" . $_POST['stockPriceChangedConfirmedOrdId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
                mysqli_query($con, $sql);
            }
            $updateNetTotal = true;

            unset($_SESSION['errorStockPriceChanged']);
            unset($_SESSION['errorQtyOrderId']);
        }

        if ($updateNetTotal) {
            requisitionTotalValue($_POST['orderId']);
        }


        $sqlSet = " SELECT * FROM tbl_orders where id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
        $ordQry = mysqli_query($con, $sqlSet);
        $ordResult = mysqli_fetch_array($ordQry);
        if ($ordResult['status'] == 2) {
            echo "<script>window.location='runningOrders.php?error=alreadyIssuedOut'</script>";
            die();
        }

        //Check stock qty with item qty
        $sql = "SELECT * FROM tbl_order_details WHERE ordId = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' AND qty > 0 ";
        $ordQry = mysqli_query($con, $sql);

        $productIds = [];
        $errorQtyOrPriceChanged = false;
        while ($ordRow = mysqli_fetch_array($ordQry)) {
            $sql = "SELECT * FROM tbl_stocks WHERE pId = '" . $ordRow['pId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
            $stkQry = mysqli_query($con, $sql);
            $stkRow = mysqli_fetch_array($stkQry);

            $productIds[$ordRow['pId']]['qty'] = $ordRow['qty'];

            $pId = $stkRow['pId'];

            if ($pId > 0) {


                //check if stock price in order details and current stock differ
                if (floor($ordRow['price']) != floor($stkRow['stockPrice']) && !isset($_POST['stockPriceChangedConfirmedOrdId'])) {

                    $_SESSION['errorStockPriceChanged'] = 1;
                    $_SESSION['errorQtyOrderId'] = $_GET['orderId'];
                    $errorQtyOrPriceChanged = true;
                }
                //end --------------------------------------------
                if ($stkRow['qty'] < $ordRow['qty']) {
                    $_SESSION['errorQty'] = 1;
                    $_SESSION['errorQtyOrderId'] = $_GET['orderId'];
                    $errorQtyOrPriceChanged = true;
                }
            }
        } //End of loop

        if ($errorQtyOrPriceChanged) {
            echo "<script>window.location='runningOrders.php?orderId=" . $_GET['orderId'] . "'</script>";
            die();
        }



        //Give new sorting number for each new order
        $sortingNo = getSortingNumber($_SESSION['accountId']);

        $updateQry = " UPDATE `tbl_orders` SET setDateTime = '" . date('Y-m-d h:i:s') . "', sortingNo='" . $sortingNo . "' WHERE id = '" . $_GET['orderId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
        mysqli_query($con, $updateQry);


        $sql = "SELECT * FROM tbl_order_details WHERE ordId = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
        $ordQry = mysqli_query($con, $sql);
        while ($ordRow = mysqli_fetch_array($ordQry)) {
            $upQry = " UPDATE  `tbl_stocks` SET
		`qty` = (qty - " . $ordRow['qty'] . "),
		`stockValue` = ( stockValue - " . $ordRow['totalAmt'] . " )
		WHERE pId = '" . $ordRow['pId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'   ";
            mysqli_query($con, $upQry);

            //update stock data in order details
            $sql = "SELECT *  FROM tbl_stocks  WHERE pId = '" . $ordRow['pId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ";
            $stkQry = mysqli_query($con, $sql);
            $stkRow = mysqli_fetch_array($stkQry);

            $upQry = " UPDATE  `tbl_order_details` SET
        `lastPrice` = '" . $stkRow['lastPrice'] . "', 
        `stockPrice` = '" . $stkRow['stockPrice'] . "',
        `stockQty` = '" . $stkRow['qty'] . "'

        WHERE ordId = '" . $_GET['orderId'] . "' AND pId = '" . $ordRow['pId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
            mysqli_query($con, $upQry);
            //end 

        }

        //add details in report
        addIssueOutInReport($productIds, $ordResult['recMemberId']);

        addAutoFillReqReport($_GET['orderId'], $_SESSION['accountId'], $ordResult['recMemberId']);
        //end report part

        requisitionTotalValue($_GET['orderId']);
    } //end issue out part

    $sql = "UPDATE tbl_orders SET status = '" . $_GET['confirm'] . "'  WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
    mysqli_query($con, $sql);

    //Insert few data in order journey tbl to show journey 
    $sql = " SELECT * FROM tbl_orders WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $res = mysqli_query($con, $sql);
    $resRow = mysqli_fetch_array($res);

    $action = ($_GET['confirm'] == 1 || $_GET['confirm'] == 3) ? 'Confirm' : 'Issue Out';

    $qry = " INSERT INTO `tbl_order_journey` SET 
`account_id` = '" . $_SESSION['accountId'] . "',
`orderId` = '" . $resRow['id'] . "',
`userBy`  = '" . $_SESSION['id'] . "',
`ordDateTime` = '" . date('Y-m-d h:i:s') . "',
`amount` = '" . $resRow['ordAmt'] . "',
`otherCur` = '" . $resRow['ordCurAmt'] . "',
`otherCurId` = '" . $resRow['ordCurId'] . "',
`orderType` = '" . $resRow['ordType'] . "',
`action` = '" . showOtherLangText($action) . "' ";
    mysqli_query($con, $qry);

    $red = ($_GET['confirm'] == 1 || $_GET['confirm'] == 3) ? 'runningOrders.php?status=1' : 'history.php?status=1';



    echo "<script>window.location='" . $red . "'</script>";
    die();
}


//Assign order to mobile user code goes here

if (isset($_POST['checkedTotal']) && $_POST['userIds'] == 0) {
    $sql = " DELETE FROM `tbl_order_assigned_users` 
WHERE orderType = '" . $_POST['orderType'] . "' AND account_id = '" . $_SESSION['accountId'] . "' AND orderId = '" . $_POST['orderId'] . "' ";
    $assignQry = mysqli_query($con, $sql);


    $qry = " INSERT INTO `tbl_order_journey` SET 
`account_id` = '" . $_SESSION['accountId'] . "',
`orderId` = '" . $resRow['id'] . "',
`userBy`  = '" . $_SESSION['id'] . "',
`ordDateTime` = '" . date('Y-m-d h:i:s') . "',
`amount` = '" . $resRow['ordAmt'] . "',
`otherCur` = '" . $resRow['ordCurAmt'] . "',
`otherCurId` = '" . $resRow['ordCurId'] . "',
`orderType` = '" . $resRow['ordType'] . "',
`notes` = 'All Users unAssigned',
 `action` = '" . showOtherLangText('Unassigned') . "' ";
    mysqli_query($con, $qry);

    echo "<script>window.location='runningOrders.php?unAssigned=1'</script>";
    die();
}

if (isset($_POST['userIds']) && !empty($_POST['userIds'])) {
    $sql = " DELETE FROM `tbl_order_assigned_users` 
WHERE orderType = '" . $_POST['orderType'] . "' AND account_id = '" . $_SESSION['accountId'] . "' AND orderId = '" . $_POST['orderId'] . "' ";
    $assignQry = mysqli_query($con, $sql);

    //Insert few data in order journey tbl to show journey 
    $sql = " SELECT * FROM tbl_orders WHERE id = '" . $_POST['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $res = mysqli_query($con, $sql);
    $resRow = mysqli_fetch_array($res);

    foreach ($_POST['userIds'] as $userId) {

        $qry = " INSERT INTO tbl_order_assigned_users 
SET 
orderType = '" . $_POST['orderType'] . "',
orderId = '" . $_POST['orderId'] . "',
userId = '" . $userId . "',
account_id = '" . $_SESSION['accountId'] . "' ";
        mysqli_query($con, $qry);
    }

    $sql = " SELECT GROUP_CONCAT(`name`) as names FROM tbl_user WHERE id IN(" . implode(',', $_POST['userIds']) . ") 
AND account_id = '" . $_SESSION['accountId'] . "' GROUP BY account_id ";
    $res = mysqli_query($con, $sql);
    $userRow = mysqli_fetch_array($res);

    $notes = showOtherLangText('Assigned To') . ': ' . $userRow['names'];
    $qry = " INSERT INTO `tbl_order_journey` SET 
`account_id` = '" . $_SESSION['accountId'] . "',
`orderId` = '" . $resRow['id'] . "',
`userBy`  = '" . $_SESSION['id'] . "',
`ordDateTime` = '" . date('Y-m-d h:i:s') . "',
`amount` = '" . $resRow['ordAmt'] . "',
`otherCur` = '" . $resRow['ordCurAmt'] . "',
`otherCurId` = '" . $resRow['ordCurId'] . "',
`orderType` = '" . $resRow['ordType'] . "',
`notes` = '" . $notes . "',
`action` = '" . showOtherLangText('assigned') . "' ";
    mysqli_query($con, $qry);

    echo "<script>window.location='runningOrders.php?assigned=1'</script>";
    die();
}



if (isset($_POST['assignedOrderType']) && isset($_POST['assignedOrderId'])) {

    $sql = " SELECT * FROM `tbl_order_assigned_users` where orderType = '" . $_POST['assignedOrderType'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  AND orderId = '" . $_POST['assignedOrderId'] . "' ";

    $assignQry = mysqli_query($con, $sql);
    $userIdsArr = [];
    while ($assignRow = mysqli_fetch_array($assignQry)) {
        $userIdsArr[] = $assignRow['userId'];
    }

    $type = ($_POST['assignedOrderType'] == 1) ? 'receiving_order' : 'issuing_out';

    $sql = " SELECT u.* FROM tbl_user u
LEFT JOIN tbl_designation_sub_section_permission dssp
ON(u.designation_id=dssp.designation_id) AND u.account_id=dssp.account_id

WHERE u.userType=1 AND dssp.is_mobile = 1 AND dssp.type = '" . $type . "' AND dssp.type_id = 1 AND u.account_id = '" . $_SESSION['accountId'] . "'   GROUP BY u.id ORDER BY u.name ";

    $usrqry = mysqli_query($con, $sql);
    $checkBox = '';
    while ($userRes = mysqli_fetch_array($usrqry)) {
        $checked = in_array($userRes['id'], $userIdsArr) ? 'checked="checked"' : '';

        $checkBox .= '<input type="checkbox" id="optionCheck" class="optionCheck" name="userIds[]" value="' . $userRes['id'] . '" ' . $checked . ' /> ' . $userRes['name'] . '<br>';
    }
    echo $checkBox;

    die();
}




?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Running Tasks - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css?v=3">
    <link rel="stylesheet" href="Assets/css/style1.css">
    <link rel="stylesheet" href="Assets/css/running-orders-css.css">
    <style>
        .doc-bx .dropdown-item,
        .modal .table-cell:nth-child(1) {
            white-space: nowrap;
        }
        @media screen and (max-width: 767px) {

            html[dir="rtl"] .val-run { text-align: left; }
        }
    </style>

</head>

<body>

    <div class="container-fluid newOrder">
        <div class="row g-0">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                <?php require_once('nav.php'); ?>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('Running Tasks'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Running Tasks'); ?></h1>
                                </div>
                            </div>
                            <?php require_once('header.php'); ?>
                        </div>
                    </div>
                </section>

                <!-- <div class="alrtMessage">
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <p><strong>Hello User!</strong> Your order has been placed Successfully.</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <p><strong>Hello User!</strong> You should check your order carefully.</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div> -->
                <?php

                //$cond = $_SESSION['adminUser'] != 1 ? " AND o.orderBy = '".$_SESSION['id']."' " : "";

                $sql = "SELECT o.*, s.name as supplierName, au.id as assignedUserTblId, cur.amt as curAmt, mt.id as mtId, u.name as orderBy, dm.name as deptMem FROM tbl_orders o

INNER JOIN tbl_user u ON(u.id=o.orderBy) AND u.account_id=o.account_id

LEFT JOIN tbl_suppliers s ON(o.supplierId = s.id) AND s.account_id = o.account_id

LEFT JOIN tbl_department dpt ON(dpt.id = o.deptId) AND dpt.account_id = o.account_id

LEFT JOIN tbl_order_assigned_users au ON(au.orderId=o.id) AND au.account_id=o.account_id

LEFT JOIN tbl_currency cur ON(cur.id=o.ordCurId) AND cur.account_id=o.account_id

LEFT JOIN tbl_mobile_time_track mt ON(mt.stockTakeId=o.id) AND  mt.status=1 AND mt.account_id=o.account_id

LEFT JOIN tbl_deptusers dm ON(dm.id=o.recMemberId) AND dm.account_id=o.account_id


LEFT JOIN tbl_designation_sub_section_permission dp ON(dp.type_id = o.supplierId) AND
o.account_id = dp.account_id AND dp.designation_id = " . $_SESSION['designation_id'] . " AND dp.type = 'order_supplier' AND dp.account_id = " . $_SESSION['accountId'] . "
AND dp.designation_section_permission_id=1 AND o.ordType=1

LEFT JOIN tbl_designation_sub_section_permission dp1 ON(dp1.type_id = o.recMemberId) AND o.account_id = dp1.account_id AND
dp1.designation_id = " . $_SESSION['designation_id'] . " AND dp1.type = 'member' AND dp1.account_id = " . $_SESSION['accountId'] . "
AND dp1.designation_section_permission_id=2 AND o.ordType=2

WHERE o.status !=2 AND o.ordType IN(1,2,3) AND o.account_id='" . $_SESSION['accountId'] . "' 

AND (o.ordType=1 AND  dp.id > 0 OR o.ordType=2 AND  dp1.id > 0 OR o.ordType=3 OR o.ordType=4 )


" . $cond . " GROUP BY o.id Order by o.id desc";

                $result = mysqli_query($con, $sql);

                ?>
                <section class="rntskHead px-0">
                    <div class="container">
                        <?php if (isset($_GET['cancel']) || isset($_GET['added']) || isset($_GET['requisitionAdded']) || isset($_GET['storageAdded']) || isset($_GET['storageAdded']) || isset($_GET['status']) || isset($_GET['assigned']) || isset($_GET['unAssigned']) || isset($_GET['updated'])) { ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p><?php

                                    if ($_GET['type'] == 2) {
                                        $typeMes = '' . showOtherLangText('Requisition') . '';
                                    } elseif ($_GET['type'] == 3) {
                                        $typeMes = '' . showOtherLangText('StockTake') . '';
                                    } else {
                                        $typeMes = '' . showOtherLangText('Order') . '';
                                    }

                                    //$typeMes = ($_GET['type'] == 2) ? ''.showOtherLangText('Requisition').'' : ($_GET['type'] == 3) ? ''.showOtherLangText('StockTake').'' : ''.showOtherLangText('Order').'';

                                    echo isset($_GET['cancel']) ? ' ' . showOtherLangText('Selected ' . $typeMes . ' has been cancelled') . ' ' : '';

                                    echo isset($_GET['added']) ? ' ' . showOtherLangText('New record added successfully') . ' ' : '';

                                    echo isset($_GET['requisitionAdded']) ? ' ' . showOtherLangText('New record added successfully') . ' ' : '';

                                    echo isset($_GET['storageAdded']) ? ' ' . showOtherLangText('New StockTake added successfully') . ' ' : '';

                                    echo isset($_GET['status']) ? ' ' . showOtherLangText('Order Confirmed') . ' ' : '';

                                    echo isset($_GET['updated']) ? ' ' . showOtherLangText('Order Updated') . ' ' : '';

                                    echo isset($_GET['assigned']) ? ' ' . showOtherLangText('User has been assigned successfully for selected order') . ' ' : '';

                                    echo isset($_GET['unAssigned']) ? ' ' . showOtherLangText('User has been unassigned successfully for selected order') . ' ' : '';

                                    ?>
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php } ?>
                        <?php if ((isset($_GET['error']) && $_GET['error'] != '') || isset($_GET['error_already_exist'])) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo isset($_GET['error']) ? ' ' . showOtherLangText('This Currency is used in order so it cannot be deleted.') . ' ' : ''; ?>
                                    <?php if (isset($_GET['error']) && $_GET['error'] == 'alreadyIssuedOut') {

                                        echo ' ' . showOtherLangText('This order has been already issued out') . ' ';
                                    } ?>
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php } ?>
                        <div class="d-flex align-items-center taskHead">
                            <div style="width: 3%;font-weight:600;"><?php echo mysqli_num_rows($result) > 0 ? mysqli_num_rows($result) : ''; ?></div>
                            <div class="d-flex align-items-center runTable py-1" style="width: 97%;">

                                <div class="d-flex align-items-center" style="width: 45%;">
                                    <div style="width: 3%;">&nbsp;</div>
                                    <div class="d-flex align-items-center" style="width: 72%;">
                                        <div class="p-1" style="width: 39%;">
                                            <p><?php echo showOtherLangText('Refer to') ?></p>
                                        </div>
                                        <div class="p-1" style="width: 17%;">
                                            <p><?php echo showOtherLangText('Date') ?></p>
                                        </div>
                                        <div class="rnTsk-Num">
                                            <p><?php echo showOtherLangText('Number') ?></p>
                                        </div>
                                    </div>
                                    <div style="width: 25%;">
                                        <div class="p-1">
                                            <p><?php echo showOtherLangText('Value') ?></p>
                                        </div>
                                    </div>


                                </div>
                                <div class="d-flex align-items-center" style="width: 55%;">
                                    <div class="p-1 text-center" style="width: calc(100% - 305px);">
                                        <p><?php echo showOtherLangText('Status') ?></p>
                                    </div>
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

                <section class="runTask pb-5 mx-0">

                    <div class="container">
                        <?php
                        $x = 0;
                        while ($orderRow = mysqli_fetch_array($result)) {

                            if ($orderRow['ordType'] == 1 || $orderRow['ordType'] == 2) {

                                if ($orderRow['ordType'] == 1) {

                                    orderNetValue($orderRow['id'], $orderRow['ordCurId']); //update nettotal to fix temporary tables update mismatch on receive order page

                                    $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '" . $_SESSION['designation_id'] . "' AND type = 'order_supplier' AND account_id = '" . $_SESSION['accountId'] . "' and type_id = '" . $orderRow['supplierId'] . "' and designation_section_permission_id=1 ";
                                } else {
                                    $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '" . $_SESSION['designation_id'] . "' AND type = 'member' AND account_id = '" . $_SESSION['accountId'] . "' and type_id = '" . $orderRow['recMemberId'] . "' and designation_section_permission_id=2 ";
                                }

                                $checkSubPerQry = mysqli_query($con, $sql);

                                if (mysqli_num_rows($checkSubPerQry) < 1) {
                                    continue; //exclude this order as user don't have its permission
                                }
                            }

                            //end temporary
                            $x++;

                            $checkOrdAssing = $orderRow['assignedUserTblId'];

                            $curAmt = $orderRow['curAmt'] > 0 ? $orderRow['curAmt'] : 0;

                            $timeTrackDet = $orderRow['mtId']; //assing mobile time track id to check below
                            $suppliers = '';
                            $deprtName = '';

                        ?>
                            <div class="task update">
                                <!-- Confirmed Member Part Start -->
                                <div
                                    class="d-flex align-items-center <?php if ($x == 1) {
                                                                            echo 'mt-0';
                                                                        } else { ?> mt-3 <?php } ?> mainRuntsk">
                                    <div class="srNum">
                                        <p><?php echo $x; ?></p>
                                    </div>
                                    <div class="fxdhtBox">
                                        <div class="<?php if ($orderRow['ordType'] == 1) {
                                                        echo 'mbSbarord';
                                                    } else {
                                                        echo 'mbSbar';
                                                    } ?>">&nbsp;</div>
                                        <div class="align-items-center dskDetail">
                                            <div
                                                class="d-flex align-items-center runDetail <?php if ($orderRow['ordType'] == 1) {
                                                                                                echo 'reqDtl-Bg';
                                                                                            } else {
                                                                                                echo 'mbrDtl-Bg';
                                                                                            } ?>">
                                                <div
                                                    class="<?php if ($orderRow['ordType'] == 1) {
                                                                echo 'ordType';
                                                            } else {
                                                                echo 'reqType';
                                                            } ?> ">
                                                    &nbsp;</div>
                                                <div class="d-flex align-items-center ordeReq">
                                                    <div
                                                        class="p-1 reqDtl <?php if ($orderRow['ordType'] == 1) {
                                                                                echo 'supMem-Name';
                                                                            } else {
                                                                                echo 'member-Name';
                                                                            } ?> ">
                                                        <p><?php if ($orderRow['ordType'] == 1) {
                                                                echo $orderRow['supplierName'];
                                                            } else {
                                                                echo $orderRow['deptMem'];
                                                            } ?>
                                                        </p>
                                                    </div>

                                                    <div class="p-1 dt-run">
                                                        <p><?php echo date('d-m-y', strtotime($orderRow['ordDateTime'])); ?>
                                                        </p>
                                                    </div>
                                                    <div class="p-1 mbrRuntsk">
                                                        <p># <?php echo $orderRow['ordNumber']; ?></p>
                                                    </div>
                                                </div>
                                                <div class="p-1 val-run">
                                                    <p class="otherCurr"><?php
                                                                            if ($orderRow['ordCurAmt'] > 0) {
                                                                                echo showOtherCur($orderRow['ordCurAmt'], $orderRow['ordCurId']);
                                                                            }
                                                                            ?></p>
                                                    <p><?php echo showPrice($orderRow['ordAmt'], $getDefCurDet['curCode']); ?>
                                                    </p>
                                                </div>

                                            </div>
                                            <div class="align-items-center tmpStatus togleOrder task-list-status-col-p">
                                                <div class="py-1 px-1 d-flex align-items-center stsBar new__layout gap-2">
                                                    <div
                                                        class="task-status <?php echo $orderRow['status'] . '---' . $orderRow['ordType'];
                                                                            if ($orderRow['status'] == '1' && $orderRow['ordType'] == 1) {
                                                                                echo 'status-done';
                                                                            } ?> <?php if ($orderRow['status'] == '0' || $orderRow['status'] == null) {
                                                                                        echo 'status-pending';
                                                                                    } ?><?php if ($orderRow['status'] == '3' && $orderRow['ordType'] == 1) {
                                                                                            echo 'status-req';
                                                                                        } ?><?php if ($orderRow['status'] == '3' && $orderRow['ordType'] == 2) {
                                                                                                echo 'status-assigned';
                                                                                            } ?>">
                                                        <p><?php
                                                            if ($orderRow['ordType'] == 1) {
                                                                get_all_type_of_status_of_orderType($orderRow['ordType'], $orderRow['id'], $orderRow['status'], $checkOrdAssing, $timeTrackDet);
                                                            } elseif ($orderRow['ordType'] == 2) {
                                                                get_all_type_of_status_of_requisitionType($orderRow['ordType'], $orderRow['id'], $orderRow['status'], $checkOrdAssing, $timeTrackDet);
                                                            } elseif ($orderRow['ordType'] == 3) {
                                                                get_all_type_of_status_of_new_stockTake($orderRow['ordType'], $orderRow['status']);
                                                            }
                                                            ?></p>
                                                    </div>
                                                    <div class="d-flex align-items-center payOptnreq ">
                                                        <?php
                                                        if ($orderRow['ordType'] == 1) {
                                                            get_all_order_action_of_order_type($orderRow['ordType'], $orderRow['status'], $checkOrdAssing, $timeTrackDet, $orderRow['id'], $_SESSION['designation_id'], $_SESSION['accountId'], $orderRow['orderReceivedByMobUser']);
                                                        }

                                                        if ($orderRow['ordType'] == 2) {
                                                            get_all_order_action_of_requisition_type($orderRow['ordType'], $orderRow['status'], $checkOrdAssing, $timeTrackDet, $orderRow['id'], $_SESSION['designation_id'], $_SESSION['accountId']);
                                                        }

                                                        if ($orderRow['ordType'] == 3) {
                                                            // get_editStockTake_permission($_SESSION['designation_id'],$_SESSION['accountId'],$orderRow['id']);
                                                        }

                                                        ?>

                                                    </div>
                                                    <div class="d-flex align-items-center dleOptnreq">

                                                        <div
                                                            class="doc-bx text-center d-flex justify-content-center align-items-center position-relative doc__btn document">
                                                            <a href="javascript:void(0)" class="dropdown-toggle runLink"
                                                                role="button" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <svg style="width: 1.5rem; height:1.5rem;" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 25 24">
                                                                    <rect x="5" y="4" width="14" height="17" rx="2"
                                                                        stroke="#8C8FA7" stroke-width="2" />
                                                                    <path d="M9 9h6m-6 4h6m-6 4h4" stroke="#8C8FA7"
                                                                        stroke-width="2" stroke-linecap="round" />
                                                                </svg>
                                                                <p class="btn2"><?php echo showOtherLangText('Documents'); ?> <i
                                                                        class="fa-solid fa-angle-down"></i></p>
                                                            </a>

                                                            <ul class="dropdown-menu">

                                                                <li>
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                        onClick="return openPopup('<?php echo $orderRow['ordType']; ?>', '<?php echo $orderRow['id']; ?>')"><i
                                                                            class="far fa-square pe-2"></i> <?php echo showOtherLangText('View Details'); ?>
                                                                    </a>
                                                                </li>
                                                                <?php if ($orderRow['ordType'] == 1) { ?>
                                                                    <li>
                                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                                            onclick="return showOrderJourney('<?php echo $orderRow['id']; ?>','<?php echo $orderRow['ordType']; ?>', '1');">
                                                                            <i class="far fa-share-square pe-2"></i>
                                                                            <?php echo showOtherLangText('Details(Supplier)') ?>
                                                                        </a>
                                                                    </li>
                                                                <?php
                                                                } elseif ($orderRow['ordType'] == 2) {
                                                                    //issue out
                                                                ?>

                                                                <?php
                                                                } ?>
                                                            </ul>
                                                        </div>
                                                        <?php
                                                        if ($orderRow['ordType'] == 1) {
                                                            get_edit_doctype_order_action_of_order_type($orderRow['ordType'], $orderRow['status'], $checkOrdAssing, $timeTrackDet, $orderRow['id'], $_SESSION['designation_id'], $_SESSION['accountId'], $orderRow['orderReceivedByMobUser']);
                                                        }

                                                        if ($orderRow['ordType'] == 2) {
                                                            get_edit_doctype_action_of_requisition_type($orderRow['ordType'], $orderRow['status'], $checkOrdAssing, $timeTrackDet, $orderRow['id'], $_SESSION['designation_id'], $_SESSION['accountId']);
                                                        }

                                                        ?>
                                                        <?php
                                                        get_deleteOrder_permission($_SESSION['designation_id'], $_SESSION['accountId'], $orderRow['id'], $orderRow['ordType']);

                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <!-- <div class="py-1 tmpBar">&nbsp;</div> -->
                                        <div class="drpOpn d-flex justify-content-center align-items-center">
                                            <a href="javascript:void(0)" class="statusLink run-stsLnk"><i
                                                    class="fa-solid fa-angle-down"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Confirmed Member Part End -->
                            </div>
                        <?php
                        } //End of whileloop

                        ?>

                    </div>

                </section>


            </div>
        </div>
    </div>

    <div id="dialog" style="display: none;">
        <?php echo showOtherLangText('Are you sure to delete this record?') ?>
    </div>
    <?php require_once('footer.php'); ?>
    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <form action="" method="post" id="assignUser" name="assignUser">
        <div class="modal" tabindex="-1" id="assign-order" aria-labelledby="edit-Assign-OrderLabel" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h1 class="modal-title h1 mt-3"><?php echo showOtherLangText('Assign Order to User') ?></h1>
                    </div>
                    <div class="modal-body">

                        <div>
                            <input type="hidden" name="orderType" class="orderType" value="">
                            <input type="hidden" name="orderId" class="orderId" value="">
                            <input type="hidden" name="checkedTotal" id="checkedTotal" value="">
                        </div>
                        <div>
                            <strong class="checkAllSectionBox">
                                <input type="checkbox" class="CheckAllOptions" id="CheckAllOptions">
                                <label>
                                    <?php echo showOtherLangText('Check All') ?>
                                </label>
                            </strong>
                        </div>
                        <div class="mobUserList">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btnBg">
                            <button type="submit"
                                class="btn btn-primary std-btn"><?php echo showOtherLangText('Save'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form method="POST" id="frm_issueOutPopUpFrm" name="issueOutPopUpFrm">
        <div class="modal" tabindex="-1" id="issue-out" aria-labelledby="edit-Assign-OrderLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-body text-center fs-13">
                        <p class="modal-title h1 mt-3"><?php echo showOtherLangText('Please approve one more time the issue out'); ?></p>
                        <input type="hidden" name="orderId" class="issueOutOrdId" value="">
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?php echo showOtherLangText('Back'); ?></button>
                        <button type="button"
                            class="approveBtn btn btn-primary"><?php echo showOtherLangText('Issue Out'); ?></button>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <form action="runningOrders.php?confirm=2&orderId=<?php echo $_SESSION['errorQtyOrderId'] ?>" method="POST"
        id="frm_issueOutPopUpFinalFrm" name="issueOutPopUpFrm">
        <div class="modal" id="errorQtyModal" tabindex="-1" aria-labelledby="issueout2label" aria-hidden="true">
            <div class="modal-dialog  modal-550 modal-dialog-centered" style="align-items:center !important;">


                <?php
                if ((isset($_SESSION['errorQty']) && $_SESSION['errorQty'] == 1) || (isset($_SESSION['errorStockPriceChanged']) && $_SESSION['errorStockPriceChanged'] == 1)) {

                    $errorStockPriceChanged = isset($_SESSION['errorStockPriceChanged']) ? $_SESSION['errorStockPriceChanged'] : '';
                    checkStockQtyRequisition($_SESSION['errorQtyOrderId'], $_SESSION['accountId'], $errorStockPriceChanged);
                }
                ?>

            </div>
        </div>

    </form>

    <div class="modal" tabindex="-1" id="order_details" aria-labelledby="orderdetails" aria-hidden="true">
        <div class="modal-dialog  modal-md site-modal">
            <div id="order_details_supplier" class="modal-content overflow-hidden p-2">

            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" id="delete-popup" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1 mt-3"><?php echo showOtherLangText('Are you sure to delete this record?') ?>
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

<script>
    // function getDelNumb(canId, ordType) {

    //     $("#dialog").dialog({
    //         autoOpen: false,
    //         modal: true,
    //         //title     : "Title",
    //         buttons: {
    //             '<?php echo showOtherLangText('Yes') ?>': function() {
    //                 //Do whatever you want to do when Yes clicked
    //                 $(this).dialog('close');
    //                 window.location.href = 'runningOrders.php?canId=' + canId + '&type=' + ordType;
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

    function show_popup_issue_out() {

        $("#dialog").dialog({
            autoOpen: false,
            modal: true,
            //title     : "Title",
            buttons: {
                '<?php echo showOtherLangText('Yes') ?>': function() {
                    //Do whatever you want to do when Yes clicked
                    $(this).dialog('close');
                    window.location.href = 'runningOrders.php?canId=' + canId + '&type=' + ordType;
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

    function AssignOrder(orderType, orderId) {

        $('.orderType').val(orderType);
        $('.orderId').val(orderId);

        $.ajax({
                method: "POST",

                data: {
                    assignedOrderType: orderType,
                    assignedOrderId: orderId
                }
            })
            .done(function(htmlRes) {
                $('.mobUserList').html(htmlRes);

                $("#CheckAllOptions").on('click', function() {

                    $('.optionCheck:checkbox').not(this).prop('checked', this.checked);

                    var totalCount = $('.optionCheck').length;
                    var totalCheckedCount = $('.optionCheck:checked').length;

                    if (totalCheckedCount == 0) {
                        $('#checkedTotal').val(0);
                    } else {
                        $('#checkedTotal').val(1);
                    }
                });

                var totalCount = $('.optionCheck').length;
                var totalCheckedCount = $('.optionCheck:checked').length;

                if (totalCheckedCount == 0) {
                    $('#checkedTotal').val(0);
                } else {
                    $('#checkedTotal').val(1);
                }

                if (totalCount == totalCheckedCount) {
                    $('#CheckAllOptions').prop('checked', true);
                } else {
                    $('#CheckAllOptions').prop('checked', false);
                }

                $(".optionCheck").on('click', function() {

                    var totalCount = $('.optionCheck').length;
                    var totalCheckedCount = $('.optionCheck:checked').length;

                    if (totalCheckedCount == 0) {
                        $('#checkedTotal').val(0);
                    } else {
                        $('#checkedTotal').val(1);
                    }

                    if (totalCount == totalCheckedCount) {

                        $('#CheckAllOptions').prop('checked', true);
                    } else {
                        $('#CheckAllOptions').prop('checked', false);
                    }
                });
            });

    }



    $(document).ready(function() {
        $('.approveBtn').click(function() {
            $.ajax({
                method: "POST",
                url: "runningOrders.php",

                data: {
                    clickApproveBtn: 1
                },
                success: function(data) {
                    let orderId = $('.issueOutOrdId').val();
                    window.location = "runningOrders.php?confirm=2&orderId=" + orderId;
                },
            })

        });

        $('.submitFinalIssueOut').click(function() {
            $.ajax({
                method: "POST",
                url: "runningOrders.php",

                data: {
                    clickApproveBtn: 1
                },
                success: function(data) {
                    $('#frm_issueOutPopUpFinalFrm').submit();
                },
            })

        });

        var errorQty = '<?php echo $_SESSION['errorQty'] ?>';
        var errorStockPriceChnd = '<?php echo $_SESSION['errorStockPriceChanged']; ?>';
        var errorQtyOrderId = '<?php echo $_SESSION['errorQtyOrderId']; ?>';
        var orderId = '<?php echo $_GET['orderId']; ?>';


        if (orderId == errorQtyOrderId && (errorQty == 1 || errorStockPriceChnd == 1)) {
            $('#errorQtyModal').modal('show');
        }

        window.onclick = function(event) {
            console.log('event.target', event.target);
            // if (event.target == modal) {
            //     modal.style.display = "none";
            // }
            // if (event.target == errorQtyModal) {
            //     errorQtyModal.style.display = "none";

            $.ajax({
                method: "POST",
                url: "runningOrders.php",

                data: {
                    clickAnywhere: 1
                }
            })

            // if (event.target == assignModalPopup) {
            //     assignModalPopup.style.display = "none";
            // }

        }
    });

    function cnfIssueOut(orderId) {

        $('.issueOutOrdId').val(orderId);
    }

    function getDelNumb(canId, ordType) {
        var newOnClick = "window.location.href='runningOrders.php?canId=" + canId + "&type=" + ordType + "'";

        $('.deletelink').attr('onclick', newOnClick);
        $('#delete-popup').modal('show');

    }

    function openPopup(ordType, ordId) {
        if (ordType == 1) {

            showOrderJourney(ordId, ordType);
            return false;

        } else if (ordType == 2) {

            showRequisitionJourney(ordId);
            return false;

        }


    }

    function showOrderJourney(ordId, ordType, isSupOrder = 0) {
        $.ajax({
                method: "POST",
                url: "ordershare_ajax_pdf.php",

                data: {
                    orderId: ordId,
                    orderType: ordType,
                    isSupDet: isSupOrder,
                    page: 'order'
                }
            })
            .done(function(htmlRes) {
                $('#order_details_supplier').html(htmlRes);
                $('#order_details').modal('show');


                //orderAndReqJsCode();
            });



    }

    function showRequisitionJourney(ordId) {

        $.ajax({
                method: "POST",
                url: "requisitionshare_ajax_pdf.php",

                data: {
                    orderId: ordId
                }
            })
            .done(function(htmlRes) {
                $('#order_details_supplier').html(htmlRes);
                $('#order_details').modal('show');

                //orderAndReqJsCode();
            });





    } //end requisition journey function

    function hideCheckbox(targetId) {

        if ($('#' + targetId).is(":visible")) {
            $('#' + targetId).css('display', 'none');
        } else {
            $('#' + targetId).css('display', 'block');
        }
    }

    $('body').on('change', '.headCheckbox', function() {
        if ($(".headCheckbox").length == $(".headCheckbox:checked").length)
            $(".headChk-All").prop('checked', true);
        else
            $(".headChk-All").prop('checked', false);
    });

    $('body').on('change', '.itmTblCheckbox', function() {

        console.log('sssss', $(".itmTblCheckbox").length, $(".itmTblCheckbox:checked").length);
        if ($(".itmTblCheckbox").length == $(".itmTblCheckbox:checked").length)
            $(".itemChk-All").prop('checked', true);
        else
            $(".itemChk-All").prop('checked', false);
    });

    $('body').on('change', '.smryCheckbox', function() {

        if ($(".smryCheckbox").length == $(".smryCheckbox:checked").length)
            $(".smryChk-All").prop('checked', true);
        else
            $(".smryChk-All").prop('checked', false);
    });

    $('body').on('click', '.headChk-All', function() {

        //$('#show-header').show();
        $('#show-header').css('display', 'block');

        if ($(".headChk-All:checked").length == 1) {
            $("#header").prop('checked', true);
            $(".headCheckbox").prop('checked', true);
            $('.headerTxt').css('display', 'block');
        } else {
            $("#header").prop('checked', false);
            $(".headCheckbox").prop('checked', false);
            $('.headerTxt').css('display', 'none');
        }

    });

    function showHideByClassItems(targetId) {

        if ($('.' + targetId).is(":visible")) {
            $('.' + targetId).css('display', 'none');


        } else {
            $('.' + targetId).css('display', 'block');

            if (!$('#itemDiv').is(":visible")) {

                $('#itemDiv').css('display', 'block');
            }

        }

    }

    function showHideByClassSummary(targetId) {


        if (!$(".summary-default-currency").is(":checked") && !$(".summary-second-currency").is(":checked")) {
            $('.totalAmtHeading').css('display', 'none');
        }


        if ($('.' + targetId).is(":visible")) {
            $('.' + targetId).css('display', 'none');


        } else {


            $('.sumMainDiv').css('display', 'table-row');
            $('.' + targetId).css('display', 'block');

            if (!$('.show-smry-cls').is(":visible")) {

                $('.show-smry-cls').css('display', 'block');
            }

            if (targetId == 'smryDef_Val' || targetId == 'smryOtr_Val') {
                $('.sumBreakupAmtText').css('display', 'block');
                $('.totalAmtHeading').css('display', 'block');
            }


        }

    }


    function updateVisibility() {

        var otherCurId = $('#ordCurId').val();

        if (!$(".summary-default-currency").is(":checked") && (!$(".summary-second-currency").is(
                ":checked") || otherCurId == 0)) {

            $('.amountSections').css('display', 'none');
            $('.SummaryItems').css('display', 'none');

            // $('.smryTtl').css('display', 'none');

        } else {
            $('.sumBreakupAmtText').css('display', 'block');
            $('.SummaryItems').css('display', 'table-row');
        }
    }
</script>
<?php
include_once('orderAndReqJourneyJsCode.php');
?>