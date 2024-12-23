<?php include('inc/dbConfig.php'); //connection details

$getLangType = getLangType($_SESSION['language_id']);


//check page permission
$checkPermission = permission_denied_for_section_pages($_SESSION['designation_id'], $_SESSION['accountId']);

if (!isset($_SESSION['adminidusername'])) {
    echo '<script>window.location ="login.php"</script>';
    exit;
}
// Get column permission of requisition

if (!in_array('2', $checkPermission)) {
    echo "<script>window.location='index.php'</script>";
    exit;
}

// Get column permission of requisition
$getColumnPermission = get_column_permission($_SESSION['designation_id'], $_SESSION['accountId'], 2);

//get member permission
// $memberCond = '';
// if (!empty(get_member_permission($_SESSION['designation_id'], $_SESSION['accountId']))) {

// $memberCond .= " AND dssp.designation_id = '".$_SESSION['designation_id']."' AND dssp.designation_section_permission_id = '2' AND type = 'member' ";
// }


//Add item charges in list
if (isset($_GET['itemCharges']) && $_GET['itemCharges'] > 0 && $_GET['feeType'] == '1') {
    $_SESSION['itemChargesReq'][$_GET['feeType']][$_GET['itemCharges']] = $_GET['itemCharges'];
}

//Add order charges in list
if (isset($_GET['itemCharges']) && $_GET['itemCharges'] > 0 && $_GET['feeType'] == '3') {
    $_SESSION['itemChargesReq'][$_GET['feeType']][$_GET['itemCharges']] = $_GET['itemCharges'];
}


if ($_REQUEST['recMemberId'] != '') {
    if ($_SESSION['recMemberId'] != $_REQUEST['recMemberId']) {
        deleteRecusitionTempData($_SESSION['id']);
        unset($_SESSION['deptId']);

        if (isset($_SESSION['itemChargesReq'])) {
            unset($_SESSION['itemChargesReq']);
        }
    }

    $_SESSION['recMemberId'] = $_REQUEST['recMemberId'];
}



if (isset($_SESSION['recMemberId'])) {
    $sqlQry = " SELECT * FROM tbl_deptusers WHERE id = '" . $_SESSION['recMemberId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $deptRes = mysqli_query($con, $sqlQry);
    $deptRow = mysqli_fetch_array($deptRes);
    $_SESSION['deptId'] = $deptRow['deptId'];
}


if (isset($_GET['clearTempPro'])) {
    deleteRecusitionTempData($_SESSION['id']);
    unset($_SESSION['itemChargesReq']);
    unset($_SESSION['deptId']);
    unset($_SESSION['recMemberId']);

    echo '<script>window.location = "addRecusation.php?tempDataCleared=1"</script>';
}

$disableClickClass = !isset($_SESSION['recMemberId']) ? 'disableClick' : '';


//add item fee & custom fee modal box 
$sql = " SELECT * FROM tbl_custom_items_fee WHERE visibility='1' AND account_id='" . $_SESSION['accountId'] . "' ";
$result = mysqli_query($con, $sql);

$sqlQry = " SELECT * FROM tbl_order_fee WHERE visibility='1' AND account_id='" . $_SESSION['accountId'] . "' ";
$ordFeeFetch = mysqli_query($con, $sqlQry);



//insert custom charge 
if (!empty($_POST['itemName'])) {
    $showHideInList = isset($_POST['visibility']) ? 1 : 0;

    $sql = "INSERT INTO `tbl_custom_items_fee` SET 
    `itemName` = '" . $_POST['itemName'] . "',
    `unit` = '" . $_POST['unit'] . "',
    `amt` = '" . $_POST['itemFeeAmt'] . "',
    `visibility` = '" . $showHideInList . "',
    `account_id` = '" . $_SESSION['accountId'] . "'  ";
    mysqli_query($con, $sql);
    $itemCharges = mysqli_insert_id($con);
    $_SESSION['itemChargesReq'][1][$itemCharges] = $itemCharges;
    echo '<script>window.location = "addRecusation.php"</script>';
}


//insert order charge
if (!empty($_POST['feeName'])) {
    $showHideInList = isset($_POST['visibility']) ? 1 : 0;

    $sql = "INSERT INTO `tbl_order_fee` SET 
    `feeName` = '" . $_POST['feeName'] . "',
    `feeType` = '" . $_POST['feeType'] . "',
    `amt` = '" . $_POST['amt'] . "',
    `visibility` = '" . $showHideInList . "',
    `account_id` = '" . $_SESSION['accountId'] . "'  ";

    mysqli_query($con, $sql);

    $itemCharges = mysqli_insert_id($con);

    $_SESSION['itemChargesReq'][3][$itemCharges] = $itemCharges;

    echo '<script>window.location = "addRecusation.php"</script>';
}


$recRowsTemp = getRecusitionTempData($_SESSION['id']);
$proTempRows = [];
if (!empty($recRowsTemp['proDetails'])) {
    $proTempRows = $recRowsTemp['proDetails'];
    $proTempNotes = $recRowsTemp['notesDetail'];
}

//place order or confirm order
if (isset($_POST['placeOrder'])) {

    unset($_SESSION['productIds']);
    $noProductQtyFilled = true;
    //Update for new items
    foreach ($_POST['productIds'] as $productId) {

        if (isset($_SESSION['productIds'][$productId]) && ($_POST['qty'][$productId] == '' || $_POST['qty'][$productId] == 0)) {
            unset($_SESSION['productIds'][$productId]);
        } elseif ($_POST['qty'][$productId] > 0) {
            $noProductQtyFilled = false;

            $_SESSION['productIds'][$productId]['qty'] = $_POST['qty'][$productId];
            $_SESSION['productIds'][$productId]['price'] = $_POST['price'][$productId];
            $_SESSION['productIds'][$productId]['notes'] = $_POST['notes'][$productId];
        } //end elseif 

    } //End foreach


    if ($noProductQtyFilled) {
        echo '<script>window.location = "addRecusation.php?errorProduct=1"</script>';
        exit;
    }


    deleteRecusitionTempData($_SESSION['id']);

    $sqlSet = " SELECT ordNumber FROM tbl_orders WHERE account_id = '" . $_SESSION['accountId'] . "'  ORDER BY id DESC LIMIT 1 ";
    $ordQry = mysqli_query($con, $sqlSet);
    $ordResult = mysqli_fetch_array($ordQry);
    $ordNumber = $ordResult['ordNumber'] > 0 ? ($ordResult['ordNumber'] + 1) : 100000;

    $qry = " INSERT INTO `tbl_orders` SET 
    `ordType` = 2,
    `ordNumber` = '" . $ordNumber . "',
    `recMemberId`  = '" . $_SESSION['recMemberId'] . "',
    `orderBy`  = '" . $_SESSION['id'] . "',
    `deptId` = '" . $_SESSION['deptId'] . "',
    `ordDateTime` = '" . date('Y-m-d h:i:s') . "',
    `ordAmt` = 0,
    `account_id` = '" . $_SESSION['accountId'] . "', 
    `status` = 1  ";
    mysqli_query($con, $qry);
    $ordId = mysqli_insert_id($con);


    $ordAmt = 0;
    foreach ($_SESSION['productIds'] as $productId => $productRow) {


        if ($productRow['qty'] == '') {
            continue;
        }


        $autFillQty = 0;
        if (isset($_POST['autoFillQty'][$productId])) {
            $autFillQty = $_POST['autoFillQty'][$productId];
        }

        $prodPrice = str_replace(',', '', $productRow['price']);

        //check stock qty
        $sql = "SELECT *  FROM tbl_stocks  WHERE pId = '" . $productId . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
        $stkQry = mysqli_query($con, $sql);
        $stkRow = mysqli_fetch_array($stkQry);

        $values[] .= " (NULL, 
        '" . $_SESSION['accountId'] . "',
        '" . $ordId . "',
        '" . $productId . "', 
        '" . $prodPrice . "',
        '" . $productRow['qty'] . "',
        '" . $productRow['qty'] . "',
        '" . ($prodPrice * $productRow['qty']) . "',
        '" . $autFillQty . "',
        '" . $productRow['notes'] . "',
        '" . $stkRow['lastPrice'] . "',
        '" . $stkRow['stockPrice'] . "',
        '" . ($stkRow['qty'] - $productRow['qty']) . "'  ) ";

        $ordAmt += ($prodPrice * $productRow['qty']);
    }


    //insert custom charges ----------------
    $noteArr = $_POST['itemNotes'];
    $ordTotArr = insertReqCustomCharges($ordId, $ordAmt, $noteArr, $_SESSION['deptId']);
    $ordAmt += $ordTotArr['chargesTot'];
    //end----------------------


    $insertQry = " INSERT INTO `tbl_order_details` (`id`, `account_id`, `ordId`, `pId`, `price`, `requestedQty`, `qty`, `totalAmt`, `autoFillQty`, `note`, `lastPrice`, `stockPrice`, `stockQty` ) VALUES  " . implode(',', $values);

    mysqli_query($con, $insertQry);

    $updateQry = " UPDATE `tbl_orders` SET `ordAmt` = '" . $ordAmt . "' WHERE id = '" . $ordId . "' AND account_id='" . $accountId . "' ";
    mysqli_query($con, $updateQry);

    $productsRows = $_SESSION['productIds'];
    unset($_SESSION['productIds']);



    requisitionTotalValue($ordId);

    if (isset($_SESSION['itemChargesReq']) || isset($_SESSION['deptId']) || isset($_SESSION['recMemberId'])) {
        unset($_SESSION['itemChargesReq']);
        unset($_SESSION['deptId']);
        unset($_SESSION['recMemberId']);
    }

    //Insert few data in order journey tbl to show journey 
    $sql = " SELECT * FROM tbl_orders WHERE id = '" . $ordId . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $res = mysqli_query($con, $sql);
    $resRow = mysqli_fetch_array($res);

    $qry = " INSERT INTO `tbl_order_journey` SET 
        `account_id` = '" . $_SESSION['accountId'] . "',
        `orderId` = '" . $ordId . "',
        `userBy`  = '" . $_SESSION['id'] . "',
        `ordDateTime` = '" . date('Y-m-d h:i:s') . "',
        `amount` = '" . $resRow['ordAmt'] . "',
        `orderType` = '" . $resRow['ordType'] . "',
        `notes` = '" . showOtherLangText('Added new Requisition') . "',
        `action` = '" . showOtherLangText('submit') . "' ";

    mysqli_query($con, $qry);

    echo '<script>window.location = "runningOrders.php?requisitionAdded=1&orderId=' . $ordId . '"</script>';
}

//delete item/order level charges
if (isset($_GET['delId']) && $_GET['delId']) {
    unset($_SESSION['itemChargesReq'][$_GET['feeType']][$_GET['delId']]);

    echo '<script>window.location = "addRecusation.php?delete=1&deptId=' . $_SESSION['deptId'] . '"</script>';
}


$cond = '';
if ($_SESSION['deptId'] != '') {
    $cond .= " AND pd.deptId = '" . $_SESSION['deptId'] . "' ";

    $orderBy = isset($_GET['sort']) ?  ' t.qty desc' : 'p.itemName';

    $sqlSet = " SELECT p.*, t.notes, IF(u.name!='',u.name,p.unitC) as countingUnit, s.qty stockQty FROM tbl_products p
    LEFT JOIN tbl_units u ON(u.id=p.unitC) AND u.account_id = p.account_id 
    INNER JOIN tbl_productdepartments pd ON(p.id = pd.productId) AND p.account_id = pd.account_id
    INNER JOIN tbl_stocks s ON(s.pId = p.id) AND s.account_id = p.account_id 
	

	LEFT JOIN tbl_recusition_items_temp t ON(t.pId = p.id) AND t.account_id = p.account_id AND t.deptId = '" . $_SESSION['deptId'] . "' AND t.userId='" . $_SESSION['id'] . "'
	
    WHERE p.account_id = '" . $_SESSION['accountId'] . "' " . $cond . " AND p.status=1 GROUP BY(id)  ORDER BY " . $orderBy;
    $proresultSet = mysqli_query($con, $sqlSet);
}

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

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
    <link rel="stylesheet" href="Assets/css/style1.css">

    <style>
        .tpBar-red {
            border-top: 6px solid #F05D53 !important;
        }

        .ord-Box.position {
            border-color: #f05d53;
        }

        .fa-angle-right,
        .fa-angles-left {
            padding: 0;
            font-size: 1rem;
            width: 30px;
            height: 30px;
        }

        .d-flex.align-items-center.itmTable>div:first-child,
        .itmBody>div:first-child {
            width: 4rem;
        }

        .reqImg {
            width: 6rem;
        }

        .tb-head,
        .reqClm-Itm {
            font-weight: 500;
        }

        .reqCnt-Fst {
            width: calc(54% - 10rem);
        }

        .reqCnt-Scnd {
            width: 20%;
        }

        .cntTable {
            color: #232859;
            font-weight: 400;
        }

        .reqClm-Ttl.tb-bdy {
            font-weight: 600;
        }

        @media screen and (min-width: 1600px) {

            /* .newFeatures { padding: 0 39px 0 88px; } */
            .reqInfo {
                padding-right: 60px;
            }
        }

        @media screen and (max-width: 1700px) {
            .reqInfo {
                padding-right: 48px;
            }

            .itmTable,
            .itmBody {
                font-size: 14px;
            }

            .reqImg {
                width: 4rem;
            }

            .reqCnt-Fst {
                width: calc(54% - 8rem);
            }

            .reqClm-Qty {
                width: 45%;
            }

            .reqClm-Ttl {
                width: 55%;
            }

            .fa-angle-right,
            .fa-angles-left {
                width: 24px;
                height: 24px;
            }

            .mbFeature .ordFeature>a {
                padding: 20px 0;
            }

            .mbFeature .ordFeature:before {
                height: 37px;
            }
        }
        @media screen and (max-width: 991px) {
            .reqInfo {
                padding-right: 0;
            }
        }

        @media screen and (max-width: 1024px) {
            .newFeatures {
                padding-right: 0px !important;
            }
        }

        @media(min-width: 992px) and (max-width:1024px) {
            .filter__width {
                margin-right: 25px !important;
            }
        }

        @media screen and (max-width: 991px) {

            .nwNxt-Btn .btn,
            .fetBtn img {
                height: 40px;
            }

            .topOrder {
                padding: 14px 10px 0 10px !important;
            }

            .reqCnt-Scnd {
                width: 30%;
            }

            .reqCnt-Fst {
                width: calc(70% - 8rem);
            }

            .reqClm-Qty,
            .reqClm-Ttl {
                width: 100%;
                text-align: left;
            }

            html[dir='rtl'] .reqClm-Qty,
            html[dir='rtl'] .reqClm-Ttl {
                text-align: right;
            }

            .reqCnt-Scnd.d-flex.align-items-center {
                flex-wrap: wrap;
            }

            .reqClm-Unit {
                width: auto;
            }

            html[dir=rtl] .stcPart .reqInfo,
            html[dir=rtl] .ordInfo {
                padding: 0;
            }

            .newReq .glb-btn {
                margin-bottom: 10px;
            }

            html[dir=rtl] .ord-Box {
                padding: 0px 15px;
            }

            .ordDate,
            .ord-Box {
                width: 100%;
            }

            .subTittle1 {
                display: flex;
                justify-content: space-between;
            }

            html[dir="rtl"] .mbFeature .d-flex.justify-content-end.mt-3.mt-md-0 {
                justify-content: flex-start !important;
            }

            .ord-Box {
                padding: 13px 1rem !important;
            }
        }

        @media screen and (max-width: 767px) {
            .itmBody {
                position: relative;
            }

            .itmBody>div:first-child {
                position: absolute;
                top: 0.25rem;
                left: .2rem;
                text-align: center;
                background: rgba(255, 255, 255, 0.8);
                display: block;
            }

            html[dir='rtl'] .itmBody>div:first-child {
                left: auto;
                right: 0;
            }

            .reqCnt-Fst {
                width: calc(70% - 4rem);
            }

            .reqClm-Itm p {
                overflow: hidden;
                text-overflow: ellipsis;
                -webkit-line-clamp: 1;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                white-space: normal;
            }

            .reqImg.tb-bdy {
                padding-left: 0;
                text-align: center;
            }

            .ordFeature.margin {
                margin-left: auto;
                margin-right: auto;
            }

            .featRow {
                justify-content: center;
            }

            .compact__tb__bdy .tb-bdy .stkImgcol .imgItm,
            .compact__tb__bdy .tb-bdy .ordItm-Img {
                position: relative;
                top: 1rem;
            }
        }

        .form-control.qty-itm {
            font-weight: 700;
            font-size: .875rem;max-width: 5rem;
        }

        .curRow .p-2 {
            text-align: left;
            width: 100%;
            flex: none;
        }

        html[dir="rtl"] .curRow .p-2 {
            text-align: right;
        }

        @media(min-width: 1200px) {
            .curRow {
                font-size: 1.125rem;
            }

            .curRow .p-2 {
                padding-left: 15% !important;
            }
        }

        .txnmRow {
            width: 40%;
        }

        .curRow {
            width: 50%;
        }

        @media screen and (min-width: 1600px) {
            .prcTable {
                font-size: 1rem;
            }

            .nwNxt-Btn .btnBg {
                max-width: 10.875rem;
                margin-left: auto;
            }

            html[dir="rtl"] .nwNxt-Btn .btnBg {
                margin-left: 0;
                margin-right: auto;
            }

            .nwNxt-Btn .btnBg .btn {
                width: 100%;
            }
        }

        @media(min-width:576px) {

            .container.cntTable,
            .nordPrice,
            .topOrder,
            .container.erdOrder,
            .recPrice,
            .nwOrder-Div {
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }
        }

        @media(min-width:992px) {

            .container.cntTable,
            .nordPrice,
            .topOrder,
            .container.erdOrder,
            .recPrice,
            .nwOrder-Div {
                padding-left: 2.5rem !important;
                padding-right: 2.5rem !important;
            }
        }

        @media(min-width:1600px) {

            .container.cntTable,
            .nordPrice,
            .topOrder,
            .container.erdOrder,
            .recPrice,
            .nwOrder-Div {
                padding-left: 3.5rem !important;
                padding-right: 3.5rem !important;
            }
        }

        @media screen and (max-width: 767px) {
            .ordFeature {
                width: 50%;
            }

            .mbFeature .ordFeature>a {
                padding: 13px 0;
            }

            .mbFeature .ordFeature::before {
                height: 26px;
            }
        }

        .filter__width {
            max-width: 255px;
        }

        html[dir="rtl"] .filter__width {
            margin-right: auto !important;
        }

        @media(min-width: 768px) {
            .filter__width {
                max-width: 355px;
            }
        }

        @media screen and (max-width: 991px) {
            html[dir="rtl"] .ord-Box {
                padding: 4px 15px !important;
                width: 100%;
            }

            html[dir="rtl"] .ord-Box .ps-3 {
                padding-left: 0 !important;
            }
        }

        @media (min-width: 992px) {
            html[dir="rtl"] .subTittle1 .ms-lg-3 {
                margin-right: 1rem !important;
                margin-left: 0 !important;
            }
        }

        @media (min-width: 768px) {
            html[dir="rtl"] .subTittle1 .ps-md-5 {
                padding-right: 3rem !important;
                padding-left: 0 !important;
            }
        }

        @media(max-width:991px) {
            .innerDrop .dropdown-menu.show {
                display: block;
                left: -12rem !important;
                box-shadow: 0 0 4px rgba(0, 0, 0, 0.1);
                width: 12rem;
            }

            html[dir="rtl"] .innerDrop .dropdown-menu.show {
                right: -12rem !important;
                left: auto;
            }

            .dropdown-item {
                font-size: 13px;
            }

            .innerDrop .dropdown-menu .dropdown-item {
                padding: .25rem .5rem;
            }

            .mb-brCode {
                justify-content: flex-start;
                padding-left: 0px;
            }

            /* .filter__width { margin-top: 10px !important; } */
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
                    <div class="tpBar-grn  tpBar-red"></div>
                    <div class="container nordPrice d-block mt-0">
                        <?php if (isset($_GET['tempDataCleared']) || isset($_GET['edit']) || isset($_GET['delete']) || isset($_GET['errorProduct']) || isset($_GET['mes'])) { ?>
                            <div
                                class="alert  alert-dismissible fade show lg__left__margin mb-0 mt-3
                                    <?php echo isset($_GET['errorProduct']) ? 'alert-danger' : 'alert-success' ?>
                                " role="alert">
                                <p>
                                    <?php
                                    echo isset($_GET['errorProduct']) ? ' ' . showOtherLangText('Select atleast one product to make requisition successfully.') . ' ' : '';
                                    echo isset($_GET['tempDataCleared']) ? ' ' . showOtherLangText('Temp data has been cleared.') . ' ' : '';
                                    echo isset($_GET['added']) ? ' ' . showOtherLangText('Item Added Successfully') . ' ' : '';
                                    echo isset($_GET['imported']) ? ' ' . showOtherLangText('Item imported Successfully') . ' ' : '';
                                    echo isset($_GET['mes']) ? $_GET['mes'] : '';

                                    if (isset($_GET['delete']) && $_GET['delete'] == 1) {
                                        echo ' ' . showOtherLangText('Item Deleted Successfully') . ' ';
                                    } elseif (isset($_GET['delete']) && $_GET['delete'] == 2) {
                                        echo ' ' . showOtherLangText('This item is in stock or ordered by someone so cannot be deleted') . ' ';
                                    }
                                    ?>
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="stcPart position-relative">
                        <div class="container cntTable pt-3"><!-- topOrder newReq nwOrder-Div-->
                            <div class="row">
                                <div class="sltSupp nwOrd-Num">
                                    <div class="ord-Box ms-0 position start-0 ps-4" style="top:1rem;">
                                        <div class="ordDate ms-0">
                                            <h4 class="subTittle1">
                                                <!-- #:  332974 -->
                                                <span></span>
                                                <span class="ps-3 ps-md-5 ms-lg-3"><?php echo date("d-m-Y"); ?></span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="ordInfo reqInfo newFeatures">
                                    <div class="container">

                                        <div class="mbFeature">
                                            <div class="d-flex justify-content-end mt-3 mt-lg-0">
                                                <div class="w-100 text-center row gx-2 gx-lg-3 gy-0 filter__width">
                                                    <div class="col-4">
                                                        <div class="featRow w-100 p-0">

                                                            <div class="ordFeature w-100">

                                                                <?php
                                                                $ActiveReqAutoFill = ActiveReqAutoFill($cond);

                                                                ?>
                                                                <?php if ($ActiveReqAutoFill > 0) { ?>

                                                                    <a href="addRecusation.php?autoFill=1"
                                                                        class="tabFet">
                                                                        <span class="autoFill <?php echo $disableClickClass; ?>"></span>
                                                                        <p class="btn2">
                                                                            <?php echo showOtherLangText('Auto Fill'); ?></p>
                                                                    </a>
                                                                <?php } else { ?>

                                                                    <a href="javascript:void(0)" class="tabFet disableClick">
                                                                        <span class="autoFill"></span>
                                                                        <p class="btn2">
                                                                            <?php echo showOtherLangText('Auto Fill'); ?></p>
                                                                    </a>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-8">

                                                        <div class="featRow p-0 row g-0 w-100">
                                                            <div class="col-6 ordFeature ">
                                                                <a href="javascript:void(0)" onClick="clearTempItem()" class="tabFet <?php echo $disableClickClass; ?>">
                                                                    <span class="clear"></span>
                                                                    <p class="btn2">
                                                                        <?php echo showOtherLangText('Clear'); ?></p>
                                                                </a>
                                                            </div>
                                                            <div class="col-6 ordFeature drpFee position-relative">
                                                                <a href="javascript:void(0)" class="dropdown-toggle tabFet <?php echo $disableClickClass; ?>" id="dropBtn"
                                                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <span class="fee"></span>
                                                                    <p class="btn2"><?php echo showOtherLangText('Fee'); ?>
                                                                        <i class="fa-solid fa-angle-down"></i>
                                                                    </p>
                                                                </a>

                                                                <ul class="item dropdown-menu dropdown__menu" id="dropdownMenu">
                                                                    <li class="dropdown innerDrop">
                                                                        <a class="dropdown-item d-flex justify-content-between align-items-center" href="javascript:void(0)" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo showOtherLangText('Service Item'); ?> <i
                                                                                class="fa-solid fa-angle-down"></i></a>
                                                                        <ul class="subitem submenu dropdown-menu dropdown__menu">
                                                                            <?php
                                                                            //add item fee & custom fee modal box 
                                                                            $sql = " SELECT * 
                                                                            FROM tbl_custom_items_fee 
                                                                            WHERE visibility='1' AND account_id='" . $_SESSION['accountId'] . "' ";
                                                                            $customItemsResult = mysqli_query($con, $sql);

                                                                            //$liCount = 0;
                                                                            while ($resultRow = mysqli_fetch_array($customItemsResult)) {
                                                                                //$liCount++;
                                                                                echo "<li class='innerLi dropdown-item px-2'><a tabindex='-1' href='addRecusation.php?feeType=1&itemCharges=" . $resultRow['id'] . "&currencyId=" . $_SESSION['currencyId'] . " ' >" . $resultRow['itemName'] . "</a></li>";
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
                                                                    <li class="dropdown innerDrop">
                                                                        <a class="item dropdown-item d-flex justify-content-between align-items-center" href="javascript:void(0)" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo showOtherLangText('Fee'); ?><i class="fa-solid fa-angle-down"></i></a>
                                                                        <ul class="subitem submenu large dropdown-menu dropdown__menu">
                                                                            <?php
                                                                            //add item fee & custom fee modal box 
                                                                            $sqlQry = " SELECT * FROM tbl_order_fee WHERE visibility='1' AND account_id='" . $_SESSION['accountId'] . "' ";
                                                                            $ordFeeFetch = mysqli_query($con, $sqlQry);
                                                                            //$innerLiCount = 0;
                                                                            while ($resultRow = mysqli_fetch_array($ordFeeFetch)) {
                                                                                // $innerLiCount++;
                                                                                echo "<li class='innerLi dropdown-item px-2'><a tabindex='-1' href='addRecusation.php?feeType=3&itemCharges=" . $resultRow['id'] . "&currencyId=" . $_SESSION['currencyId'] . " '>" . $resultRow['feeName'] . "</a> ";
                                                                            }
                                                                            ?>
                                                                        </ul>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#new-fees-item"><?php echo showOtherLangText('New Fee') ?></a>
                                                                    </li>


                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="smBtn nwNxt-Btn">
                                    <div class="btnBg">
                                        <a class="btn btn-primary form-submit-btn d-inline-flex align-items-center justify-content-center justify-content-xl-end gap-2 px-lg-2 px-xxl-3 w-100"><span class="align-middle"><?php echo showOtherLangText('Submit'); ?></span>
                                            <i class="fa-solid fa-angle-right d-none d-xl-inline-flex justify-content-center align-items-center m-0"></i></a>
                                    </div>
                                    <div class="fetBtn">
                                        <a href="javascript:void(0)">
                                            <img src="Assets/icons/dashboard.svg" alt="dashboard">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                        <div class="container topOrder newReq">

                            <div class="row">
                                <div class="sltSupp">
                                    <!-- Select Supplier  -->
                                    <!-- <div class="btn-group glb-btn">
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
                                    </div> -->
                                    <form action="" id="frm" name="frm" method="post" autocomplete="off">
                                        <input type="hidden" name="placeOrder" class="btn btn-primary"
                                            value="<?php echo showOtherLangText('Submit'); ?>" />
                                        <div class="btn-group glb-btn">
                                            <button type="button"
                                                class="btn body3 drp-btn dropdown-toggle dropdown-toggle-split d-flex align-items-center justify-content-between"
                                                data-bs-toggle="dropdown" aria-expanded="false"><span
                                                    id="requisitionText"><?php echo showOtherLangText('Requisition By'); ?>:</span>
                                                <i class="fa-solid fa-angle-down"></i></button>
                                            <?php

                                            $sqlSet = " SELECT DISTINCT(du.id),du.* FROM tbl_deptusers du
                                                INNER JOIN tbl_designation_sub_section_permission dssp ON(dssp.type_id=du.id) AND dssp.account_Id=du.account_Id 

                                                WHERE du.account_id = '" . $_SESSION['accountId'] . "' AND dssp.designation_id = '" . $_SESSION['designation_id'] . "' AND dssp.designation_section_permission_id = '2' AND type = 'member'  order by name  ";

                                            $resultSet = mysqli_query($con, $sqlSet);

                                            ?>
                                            <ul class="dropdown-menu requisition_dropdown" style="height: 300px; overflow-y: scroll;">
                                                <?php
                                                while ($deptUserRow = mysqli_fetch_array($resultSet)) {
                                                    $sel = $_SESSION['recMemberId'] == $deptUserRow['id'] ? 'selected' : '';
                                                ?>
                                                    <li data-id=<?php echo $deptUserRow['id']; ?>
                                                        data-value="<?php echo $deptUserRow['name']; ?>"><a
                                                            class="dropdown-item <?php echo $sel; ?>"
                                                            href="javascript:void(0)"><?php echo $deptUserRow['name'] ?></a>
                                                    </li>
                                                <?php
                                                }
                                                ?>
                                                <!--  <li><a class="dropdown-item" href="javascript:void(0)">Requisition 2</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Requisition 3</a>
                                            </li> -->
                                            </ul>
                                        </div>

                                        <div class="input-group srchBx">
                                            <input type="search" class="form-control"
                                                placeholder="<?php echo showOtherLangText('Search Item'); ?>"
                                                onKeyUp="myFunction()" name="search" id="search" aria-label="Search">
                                            <div class="input-group-append">
                                                <button class="btn" type="button">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>

                                </div>
                                <div id="totalOrdArea" class="ordInfo reqInfo">
                                    <?php
                                    $sql = " SELECT * FROM  tbl_recusition_items_temp WHERE deptId = '" . $_SESSION['deptId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  AND `userId` = '" . $_SESSION['id'] . "'  ";
                                    $result = mysqli_query($con, $sql);
                                    while ($row = mysqli_fetch_array($result)) {
                                        $sqlSet = " SELECT * FROM tbl_products WHERE id= '" . $row['pId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
                                        $resultSet = mysqli_query($con, $sqlSet);
                                        $prodRow = mysqli_fetch_array($resultSet);
                                        $productPrice += $prodRow['stockPrice'] * $row['qty'];
                                    }

                                    //for custom item fee charges
                                    $customCharge = 0;
                                    if (isset($_SESSION['itemChargesReq'][1]) && count($_SESSION['itemChargesReq'][1]) > 0) {
                                        $itemIds = implode(',', $_SESSION['itemChargesReq'][1]);
                                        $sqlSet = " SELECT * FROM tbl_custom_items_fee WHERE id IN(" . $itemIds . ")  AND account_id = '" . $_SESSION['accountId'] . "'  ";
                                        $resRows = mysqli_query($con, $sqlSet);
                                        while ($row = mysqli_fetch_array($resRows)) //start custom item charge loop
                                        {
                                            $customCharge += $row['amt'];
                                        }
                                    } //end of custom item fee charges
                                    $totalChargePrice = ($productPrice + $customCharge); //total sum value of custom charge and product charge 
                                    ?>
                                    <div class="container">
                                        <div class="prcTable">
                                            <?php
                                            if (isset($_SESSION['itemChargesReq'][3]) && count($_SESSION['itemChargesReq'][3]) > 0) { ?>
                                                <div class="price justify-content-between">
                                                    <div class="p-2 delIcn text-center"></div>
                                                    <div class="p-2 txnmRow">
                                                        <p><?php echo showOtherLangText('Sub Total'); ?></p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p><?php echo getPriceWithCur($totalChargePrice, $getDefCurDet['curCode']) ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            } //show here order level fixed/ percent/ tax charges       
                                            $taxCharges = 0;
                                            $fixedCharges = 0;
                                            $perCharges = 0;
                                            if (isset($_SESSION['itemChargesReq'][3]) && count($_SESSION['itemChargesReq'][3]) > 0) {
                                                $itemIds = implode(',', $_SESSION['itemChargesReq'][3]);
                                                //start order level item fixed charges
                                                $sqlSet = " SELECT * FROM tbl_order_fee WHERE id IN(" . $itemIds . ") AND account_id = '" . $_SESSION['accountId'] . "'  AND feeType =2 ";
                                                $resRows = mysqli_query($con, $sqlSet);

                                                while ($fixRow = mysqli_fetch_array($resRows)) {
                                                    $feeName = $fixRow['feeName'];
                                                    $fixedCharges += $fixRow['amt'];
                                                ?>

                                                    <div class="price justify-content-between taxRow">
                                                        <div class="p-2 delIcn text-center">
                                                            <a onClick="getDelNumb('<?php echo $fixRow['id'] ?>', 3)"
                                                                href="javascript:void(0)">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </a>
                                                        </div>
                                                        <div class="p-2 txnmRow">
                                                            <p><?php echo $feeName ?></p>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-end curRow">
                                                            <div class="p-2">
                                                                <p><?php echo getPriceWithCur($fixRow['amt'], $getDefCurDet['curCode']); ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                } //start order level item percent charges
                                                $sqlSet = " SELECT * FROM tbl_order_fee WHERE id IN(" . $itemIds . ")  AND account_id = '" . $_SESSION['accountId'] . "'  AND feeType = 3  ";
                                                $resRows = mysqli_query($con, $sqlSet);
                                                while ($perRow = mysqli_fetch_array($resRows)) {
                                                    $feeName = $perRow['feeName'];
                                                    $perCharges += $perRow['amt'];
                                                    $perChargeTotal = ($totalChargePrice * $perRow['amt'] / 100);
                                                ?>
                                                    <div class="price justify-content-between taxRow">
                                                        <div class="p-2 delIcn text-center">
                                                            <a onClick="getDelNumb('<?php echo $perRow['id'] ?>', 3)"
                                                                href="javascript:void(0)">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </a>
                                                        </div>
                                                        <div class="p-2 txnmRow">
                                                            <p><?php echo $feeName ?> <?php echo $perRow['amt'] ?> %</p>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-end curRow">
                                                            <div class="p-2">
                                                                <p><?php echo getPriceWithCur($perChargeTotal, $getDefCurDet['curCode']) ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php
                                                } //start order level item tax charges
                                                $sqlSetQry = " SELECT * FROM tbl_order_fee WHERE id IN(" . $itemIds . ")  AND account_id = '" . $_SESSION['accountId'] . "'  AND feeType =1 ";
                                                $resultRows = mysqli_query($con, $sqlSetQry);
                                                //calculating tax charges
                                                $totalFixedCharges = $fixedCharges; //calculating total fixed charge
                                                $totalPerCharges = ($totalChargePrice * $perCharges / 100); //calculating total per charge
                                                while ($taxRow = mysqli_fetch_array($resultRows)) {
                                                    $feeName = $taxRow['feeName'];
                                                    $taxCharges += $taxRow['amt'];
                                                    $taxPerChargesTotal = (($totalChargePrice + $totalFixedCharges + $totalPerCharges) * $taxRow['amt'] / 100);
                                                ?>
                                                    <div class="price justify-content-between taxRow">
                                                        <div class="p-2 delIcn text-center">
                                                            <a onClick="getDelNumb('<?php echo $taxRow['id'] ?>', 3)"
                                                                href="javascript:void(0)">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </a>
                                                        </div>
                                                        <div class="p-2 txnmRow">
                                                            <p><?php echo $feeName ?> <?php echo $taxRow['amt'] ?> %</p>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-end curRow">
                                                            <div class="p-2">
                                                                <p><?php echo getPriceWithCur($taxPerChargesTotal, $getDefCurDet['curCode']) ?>
                                                                </p>
                                                            </div>

                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            } //calculating net value here
                                            $totalTaxCharges = (($totalChargePrice + $totalFixedCharges + $totalPerCharges) * $taxCharges / 100); //calculating total tax value 
                                            $netTotalValue = ($totalChargePrice + $totalFixedCharges + $totalPerCharges + $totalTaxCharges);
                                            ?>
                                            <div <?php if (!isset($_SESSION['itemChargesReq'][3]) || count($_SESSION['itemChargesReq'][3]) == 0) {
                                                        echo 'style="border-top: 0px;"';
                                                    }  ?> class="price justify-content-between grdTtl-Row">
                                                <div class="p-2 delIcn text-center"></div>
                                                <div class="p-2 txnmRow">
                                                    <p><?php echo showOtherLangText('Grand Total'); ?></p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                        <p><?php echo getPriceWithCur($netTotalValue, $getDefCurDet['curCode']) ?>
                                                        </p>
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
                    $countCustomItems = 0;
                    //$x = 0;
                    if (isset($_SESSION['itemChargesReq'][1]) && count($_SESSION['itemChargesReq'][1]) > 0) {
                        $itemIds = implode(',', $_SESSION['itemChargesReq'][1]);

                        $sqlSet = " SELECT f.*, i.notes FROM tbl_custom_items_fee f
                            LEFT JOIN tbl_recusition_items_temp i ON(i.pId = f.id)
                             AND f.account_id = i.account_id AND i.itemType=2
                         WHERE f.id IN(" . $itemIds . ") AND f.account_id = '" . $_SESSION['accountId'] . "'  ";
                        $resRows = mysqli_query($con, $sqlSet);

                        $countCustomItems =   mysqli_num_rows($resRows) > 0 ? mysqli_num_rows($resRows) : 0;
                    }
                    ?>
                    <div class="container nordPrice position-relative">
                        <!-- Item Table Head Start -->
                        <div class="d-flex align-items-center itmTable">
                            <div class="reqImg tb-head">
                                <p><?php $totalItems = mysqli_num_rows($proresultSet) > 0 ? mysqli_num_rows($proresultSet) : '';

                                    echo  $totalItems += $countCustomItems;

                                    ?></p>
                            </div>
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

                                <?php if ($getColumnPermission['item_price'] == 1) { ?>
                                    <div class="reqClm-Prc tb-head">
                                        <p><?php echo showOtherLangText('Price'); ?></p>
                                    </div>
                                <?php } ?>

                                <div class="reqClm-Unit tb-head">
                                    <p>
                                    <p><?php echo showOtherLangText('C.Unit'); ?></p>
                                    </p>
                                </div>
                            </div>
                            <div class="reqSt-Qty tb-head">
                                <p><?php echo showOtherLangText('S.qty'); ?></p>
                            </div>
                            <div class="reqCnt-Scnd d-flex align-items-center flex-wrap">
                                <div class="reqClm-Qty tb-head">
                                    <div class="d-flex align-items-center">
                                        <p><?php echo showOtherLangText('Qty'); ?></p>
                                        <span class="dblArrow">
                                            <a href="addRecusation.php?sort=qty" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-up"></i></a>
                                            <a href="addRecusation.php?sort=qty" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-down"></i></a>
                                        </span>
                                    </div>
                                </div>
                                <?php if ($getColumnPermission['item_price'] == 1) { ?>
                                    <div class="reqClm-Ttl tb-head">
                                        <p><?php echo showOtherLangText('Total'); ?></p>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="requi-Hide">
                                <div class="reqClm-Note tb-head">
                                    <div class="mb-ReqCode">


                                    </div>
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
                            $x = 0;
                            while ($row = mysqli_fetch_array($resRows)) //start custom item charge loop
                            {
                                $x++;
                                $totalCustomCharges += $row['amt'];
                            ?>
                                <div class="newReqTask">
                                    <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                        <div class="reqImg tb-bdy">
                                            <p><?php echo $x; ?></p>
                                        </div>
                                        <div class="reqImg tb-bdy">
                                            <a title="<?php echo showOtherLangText('Delete') ?>" href="javascript:void(0)"
                                                onClick="getDelNumb('<?php echo $row['id'] ?>', 1)" style="color:#808080"><i
                                                    class="fa-solid fa-trash-can"></i></a>
                                        </div>
                                        <div class="reqCnt-Fst d-flex align-items-center">
                                            <div class="reqClm-Itm tb-bdy">
                                                <p><?php echo $row['itemName']; ?></p>
                                            </div>
                                            <div class="reqClm-Br tb-bdy">
                                                <p class="reqBarCode"></p>
                                            </div>
                                            <?php echo $getColumnPermission['item_price'] == 1 ? '<div class="reqClm-Prc tb-bdy">
                                            <p>' . getPriceWithCur($row['amt'], $getDefCurDet['curCode']) . '</p>
                                        </div>' : ''; ?>
                                            <div class="reqClm-Unit tb-bdy">
                                                <p><?php echo $row['unit']; ?></p>
                                            </div>
                                        </div>

                                        <div class="reqSt-Qty tb-bdy">
                                            <p class="reqStockQty">1 <span class="mbl-ReqStk">On stock</span></p>
                                        </div>
                                        <div class="reqCnt-Scnd d-flex align-items-center">
                                            <div class="reqClm-Qty tb-bdy">
                                                1
                                            </div>
                                            <?php echo $getColumnPermission['item_price'] == 1 ? '<div class="reqClm-Ttl tb-bdy">
                                            <p>' . getPriceWithCur($row['amt'], $getDefCurDet['curCode']) . '</p>
                                        </div>' : ''; ?>
                                        </div>
                                        <div class="requi-Hide">
                                            <div class="reqClm-Note tb-bdy">
                                                <div class="mb-ReqCode">



                                                </div>
                                                <input type="text" class="form-control note-itm" placeholder="<?php echo showOtherLangText('Note'); ?>"
                                                    id="notes<?php echo $row['id']; ?>" onChange="notesVal('<?php echo $row['id'] ?>', '2');"
                                                    autocomplete="off" name="itemNotes[<?php echo $row['id']; ?>]" value="<?php echo $row['notes']; ?>">
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
                            }
                            if ($_SESSION['deptId'] != '') {
                                $productsConfirmedQtyArr = getConfirmTotalQtyReq($_SESSION['accountId']);
                                $x = $x ? $x : 0;
                                $autoFillArr = [];
                                while ($row = mysqli_fetch_array($proresultSet)) {
                                    $x++;

                                    $selQry = " SELECT * FROM tbl_recusition_items_temp WHERE autoFill = '1' AND account_id = '" . $_SESSION['accountId'] . "' AND pId = '" . $row['id'] . "' ";
                                    $resultSet = mysqli_query($con, $selQry);
                                    $resultRow = mysqli_fetch_array($resultSet);
                                    $showAutoFillQty = $resultRow['qty'];
                                    $autoFillArr[$resultRow['pId']] = $showAutoFillQty;

                                    $totalProQty = isset($productsConfirmedQtyArr[$row['id']]) ? $productsConfirmedQtyArr[$row['id']] : 0;

                                    $availableQty = $row['stockQty'] - $totalProQty;

                                    $proQty = '';
                                    $totalQtyPrice = '';
                                    $totalPriceShowTop = 0;
                                    if (showTempProQty($proTempRows, $_SESSION['deptId'], $row['id']) > 0) {
                                        $proQty = showTempProQty($proTempRows, $_SESSION['deptId'], $row['id']);
                                        $totalQtyPrice = $proQty ? ($proQty * $row['stockPrice']) : '';
                                        $totalPriceShowTop = $totalQtyPrice;
                                    } elseif (isset($_SESSION['productIds'][$row['id']]['qty'])) {
                                        $totalPriceShowTop = $_SESSION['productIds'][$row['id']]['qty'] * $_SESSION['productIds'][$row['id']]['price'];
                                        $totalQtyPrice = $totalPriceShowTop;
                                    } elseif (isset($_GET['autoFill']) && $_GET['autoFill'] == 1) {
                                        $date = date('Y-m-d', strtotime('-1 day'));

                                        $autoFillQry = " SELECT oi.minQty minQty, oi.maxQty maxQty, oi.pId, oi.factor proFactor, dii.* FROM tbl_daily_import_items dii
                    INNER JOIN tbl_outlet_items oi ON(oi.outLetId = dii.outLetId) AND (oi.account_id = dii.account_id) 
                    INNER JOIN tbl_revenue_center_departments rcd ON(rcd.id = dii.outLetId) AND (rcd.account_id = dii.account_id)   
                    WHERE importedOn = '" . $date . "' AND dii.account_id = '" . $_SESSION['accountId'] . "' AND rcd.deptId = '" . $_SESSION['recMemberId'] . "' AND dii.barCode = '" . $row['barCode'] . "' AND oi.pId = '" . $row['id'] . "' ";



                                        $autoFillResult = mysqli_query($con, $autoFillQry);

                                        $autoFillResultRow = mysqli_fetch_array($autoFillResult);

                                        $requisition = $autoFillResultRow['requisition'];

                                        $closeStock = $autoFillResultRow['closeStock']; //ceil($autoFillResultRow['closeStock']/$autoFillResultRow['proFactor']);
                                        $closeStockDone = $autoFillResultRow['closeStockDone'];
                                        $usageAvg = $autoFillResultRow['usageAvg'];

                                        $minQty = $autoFillResultRow['minQty'];
                                        $maxQty = $autoFillResultRow['maxQty'];
                                        $proFactor = $autoFillResultRow['proFactor'];
                                        $showAutoFillQty = 0;


                                        if ($requisition < 1) {
                                            $showAutoFillQty = ($closeStock < $minQty) && ($closeStockDone) ? ($maxQty - $closeStock) : '';
                                            $showAutoFillQty = $showAutoFillQty && $autoFillResultRow['proFactor'] ? ceil($showAutoFillQty / $autoFillResultRow['proFactor']) : '';
                                            $autoFillArr[$row['id']] = $showAutoFillQty;
                                            $proQty = $showAutoFillQty;
                                            $totalQtyPrice = $showAutoFillQty * $row['stockPrice'];
                                        } else {
                                            $showAutoFillQty = (($maxQty - $closeStock) - ($requisition * $autoFillResultRow['proFactor']));
                                            $showAutoFillQty = $showAutoFillQty && $autoFillResultRow['proFactor'] ? ceil($showAutoFillQty / $autoFillResultRow['proFactor']) : '';

                                            $autoFillArr[$row['id']] = $showAutoFillQty;
                                            $proQty = $showAutoFillQty;
                                            $totalQtyPrice = $showAutoFillQty * $row['stockPrice'];
                                        }
                                    }
                                ?>
                                    <input type="hidden" name="productIds[]" value="<?php echo $row['id']; ?>" />
                                    <input type="hidden" name="price[<?php echo $row['id']; ?>]" id="<?php echo $x; ?>"
                                        value="<?php echo getPriceWithCur($row['stockPrice'], $getDefCurDet['curCode']); ?>" />
                                    <?php
                                    if ($showAutoFillQty > 0) {
                                    ?>
                                        <input type="hidden" name="autoFillQty[<?php echo $row['id']; ?>]" id="autoFill"
                                            value="<?php echo $showAutoFillQty; ?>" />
                                    <?php
                                    }
                                    ?>
                                    <input type="hidden" name="totalPriceShowTop[]" id="totalPriceShowTop<?php echo $x; ?>"
                                        value="<?php echo $totalPriceShowTop; ?>" />
                                    <input type="hidden" name="factor[<?php echo $row['id']; ?>]" id="factor<?php echo $x; ?>"
                                        value="<?php echo $row['factor']; ?>" />

                                    <div class="newReqTask">
                                        <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                            <div class="reqImg tb-bdy">
                                                <p><?php echo $x; ?></p>
                                            </div>
                                            <div class="reqImg tb-bdy">

                                                <?php $img = '';
                                                if ($row['imgName'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/products/" . $row['imgName'])) {
                                                    echo '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/products/' . $row['imgName'] . '" class="ordItm-Img">';
                                                } ?>


                                            </div>
                                            <div class="reqCnt-Fst d-flex align-items-center">

                                                <div class="reqClm-Itm tb-bdy">
                                                    <p><?php echo $row['itemName']; ?></p>
                                                </div>
                                                <div class="reqClm-Br tb-bdy">
                                                    <p class="reqBarCode"><?php echo $row['barCode']; ?></p>
                                                </div>
                                                <?php echo $getColumnPermission['item_price'] == 1 ? '<div class="reqClm-Br tb-bdy">
                                            ' . getPriceWithCur($row['stockPrice'], $getDefCurDet['curCode']) . '</div>' : ''; ?>
                                                <div class="reqClm-Unit tb-bdy">
                                                    <p><?php echo $row['countingUnit']; ?></p>
                                                </div>
                                            </div>
                                            <?php echo ($getColumnPermission['available_quantity'] == 1) ? '<div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">' . $availableQty . '<span class="mbl-ReqStk">On stock</span></p>
                                    </div>' : ''; ?>
                                            <div class="reqCnt-Scnd d-flex align-items-center">
                                                <div class="reqClm-Qty tb-bdy">
                                                    <input type="text" class="form-control qty-itm"
                                                        name="qty[<?php echo $row['id']; ?>]"
                                                        onChange="showTotal(this.value, '<?php echo $x; ?>', '<?php echo $availableQty > 0 ? $availableQty : 0; ?>', '<?php echo $row['id']; ?>', '<?php echo $row['proType']; ?>')"
                                                        value="<?php echo $proQty; ?>" autocomplete="off" size="1">
                                                </div>
                                                <?php echo $getColumnPermission['item_price'] == 1 ? '<div class="reqClm-Ttl tb-bdy"><p id="totalPrice' . $x . '">' . getPriceWithCur($totalQtyPrice, $getDefCurDet['curCode']) . '</p></div>' : ''; ?>
                                            </div>
                                            <div class="requi-Hide">
                                                <div class="reqClm-Note tb-bdy">
                                                    <div class="mb-ReqCode">

                                                        <p class="reqBarCode"><?php echo $row['barCode']; ?></p>
                                                        <?php echo ($getColumnPermission['available_quantity'] == 1) ? '
                                        <p class="reqStockQty">' . $availableQty . '<span class="mbl-ReqStk">On stock</span></p>
                                   ' : ''; ?>

                                                    </div>
                                                    <input type="text" name="notes[<?php echo $row['id']; ?>]"
                                                        id="notes<?php echo $row['id']; ?>"
                                                        onChange="notesVal('<?php echo $row['id'] ?>', '1');"
                                                        class="form-control note-itm"
                                                        placeholder="<?php echo showOtherLangText('Note'); ?>" value="<?php echo $row['notes']; ?>">
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
                                }
                            } //End of loop 

                            //do this for auto fill
                            if (!empty($autoFillArr)) {
                                foreach ($autoFillArr as $pId => $qty) {
                                    if ($qty > 0) {
                                        $sql = " SELECT * FROM  tbl_recusition_items_temp WHERE autoFill = '1' AND pId='" . $pId . "' AND account_id='" . $_SESSION['accountId'] . "' ";
                                        $result = mysqli_query($con, $sql);
                                        $resultSet = mysqli_fetch_array($result);

                                        if ($resultSet) {
                                            $upqry = " UPDATE tbl_recusition_items_temp SET qty='" . $qty . "' WHERE autoFill = '1' AND pId='" . $pId . "' AND account_id='" . $_SESSION['accountId'] . "' ";

                                            mysqli_query($con, $upqry);
                                        } else {
                                            $sql = "INSERT INTO `tbl_recusition_items_temp` SET
                     `deptId` = '" . $_SESSION['deptId'] . "',
                     `userId` = '" . $_SESSION['id'] . "',
                     `pId` = '" . $pId . "',
                     `qty` = '" . $qty . "',
                     `autoFill` = 1 ,
                     `account_id` = '" . $_SESSION['accountId'] . "'  ";
                                            mysqli_query($con, $sql);
                                        }
                                    }
                                }
                            } //end autofill code 

                            if (isset($_GET['autoFill'])) {
                                echo '<script>window.location="addRecusation.php"</script>';
                                exit;
                            }

                            ?>





                            <!-- Item Table Body End -->
                        </div>
                    </div>

                </section>

                </form>
            </div>
        </div>
    </div>


    <?php require_once('footer.php'); ?>

    <form action="" name="addNewFee" class="addUser-Form row container glbFrm-Cont" id="addNewFee" method="post"
        autocomplete="off">
        <div class="modal" tabindex="-1" id="new-fees-item" aria-labelledby="add-CategoryLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h1 class="modal-title h1"><?php echo showOtherLangText('Add Fee'); ?></h1>
                    </div>
                    <div class="addUser-Form modal-body">
                        <input type="hidden" name="currencyPopupForm" value="<?php echo $_SESSION['currencyId'] ?>">
                        <div class="d-flex flex-column gap-3">
                            <div>
                                <input
                                    type="text" class="form-control" name="feeName" id="feeName" value="" autocomplete="off"
                                    placeholder="<?php echo showOtherLangText('Fee Name'); ?>"

                                    onChange="this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" required />
                            </div>
                            <div>
                                <select class="form-control" name="feeType" id="typeOfFee"
                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')"
                                    onChange="this.setCustomValidity('')" required>
                                    <option value="2"><?php echo showOtherLangText('Fixed Fee'); ?></option>
                                    <option value="3"><?php echo showOtherLangText('Percentage Fee'); ?>
                                    </option>
                                </select>
                            </div>
                            <div>
                                <input
                                    type="text" class="form-control" id="amt" name="amt" value="" autocomplete="off"
                                    placeholder="<?php echo showOtherLangText('Fee Amount') . ' ' . $getDefCurDet['curCode']; ?>"

                                    onChange="this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" required />
                            </div>
                        </div>


                    </div>
                    <div class="feeSave">
                        <input type="checkbox" class="optionCheck" name="feeType" id="feeType" value="1">
                        <span class="subTittle1" style="vertical-align:text-top;">
                            <?php echo showOtherLangText('Tax fee'); ?></span><br>

                    </div>
                    <div class="feeSave">
                        <input type="checkbox" class="optionCheck" id="visibility" name="visibility" value="1">
                        <span class="subTittle1" style="vertical-align:text-top;"> <?php echo showOtherLangText('save to fixed service item list'); ?></span><br>

                    </div>
                    <div class="modal-footer">
                        <div class="btnBg">
                            <button type="submit" id="feesave_add" name="feesave_add" class="btn btn-primary std-btn"><?php echo showOtherLangText('Add'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                        <input type="text" class="form-control" id="itemName" name="itemName" value=""
                            placeholder="<?php echo showOtherLangText('Service Name'); ?>" autocomplete="off"

                            onChange="this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" required>
                        <input type="number" class="form-control" id="feeAmt" name="itemFeeAmt" value=""
                            placeholder="<?php echo showOtherLangText('Amount') . ' ' . $getDefCurDet['curCode']; ?>"
                            autocomplete="off"

                            onChange="this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" required>
                        <input type="text" class="form-control" id="unit" name="unit" value=""
                            placeholder="<?php echo showOtherLangText('Unit'); ?>" autocomplete="off"

                            onChange="this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" required>
                    </div>
                    <div>
                        <div class="feeSave">
                            <input type="checkbox" class="optionCheck" id="visibility" name="visibility" value="1">
                            <span class="subTittle1" style="vertical-align:text-top;"> <?php echo showOtherLangText('save to fixed service item list'); ?></span><br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btnBg">
                            <button type="submit" id="addFee" name="addFee"
                                class="btn btn-primary std-btn"><?php echo showOtherLangText('Add'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div id="dialog" style="display: none;">
        <?php echo showOtherLangText('Are you sure to delete this charges?') ?>
    </div>
    <div id="dialog4" style="display: none;">
        <?php echo showOtherLangText('Are you sure to clear Temp data?') ?>
    </div>
    <form id="requisition_frm_id" name="requisition_frm_id" method="post" action="">
        <input type="text" name="recMemberId" id="recMemberId" value="">
    </form>
    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script>
        $(".requisition_dropdown").on("click", "a", function(e) {
            var $this = $(this).parent();

            $("#requisitionText").text($this.data("value"));
            $("#requisition_frm_id #recMemberId").val($this.data("id"));
            $('#requisition_frm_id').submit();
        });

        if ($(".requisition_dropdown .selected").length > 0) {
            $("#requisitionText").text($(".requisition_dropdown .selected").text());
        }

        function myFunction() {
            // Declare variables
            var input = document.getElementById("search");
            var filter = input.value.toLowerCase();
            var nodes = document.querySelectorAll('.newReqTask');

            for (i = 0; i < nodes.length; i++) {
                if (nodes[i].innerText.toLowerCase().includes(filter)) {
                    nodes[i].style.setProperty("display", "flex", "important");
                } else {
                    nodes[i].style.setProperty("display", "none", "important");
                }
            }


        }

        $("#search").on("search", function(evt) {
            if ($(this).val().length == 0) {
                resetData();
            }
        });

        function resetData() {

            $('#search').val('');
            myFunction();
        }

        function getDelNumb(delId, feeType) {
            var newOnClick = "window.location.href='addRecusation.php?delId=" + delId + "&feeType=" + feeType + "'";

            //console.log('click',newOnClick);
            //return false;

            $('.deletelink').attr('onclick', newOnClick);
            $('#delete-popup').modal('show');

        }
        // function getDelNumb(delId, feeType) {

        //      $("#dialog").dialog({
        //          autoOpen: false,
        //          modal: true,
        //          //title     : "Title",
        //          buttons: {
        //              '<?php echo showOtherLangText('Yes') ?>': function() {
        //                  //Do whatever you want to do when Yes clicked
        //                  $(this).dialog('close');
        //                  window.location.href = 'addRecusation.php?delId=' + delId + '&feeType=' + feeType;
        //              },

        //              '<?php echo showOtherLangText('No') ?>': function() {
        //                  //Do whatever you want to do when No clicked
        //                  $(this).dialog('close');
        //              }
        //          }
        //      });

        //      $("#dialog").dialog("open");
        //      $('.custom-header-text').remove();
        //      $('.ui-dialog-content').prepend(
        //          '<div class="custom-header-text"><span><?php echo showOtherLangText('Queue1.com Says') ?></span></div>');
        //  }
        function showTotal(qty, indexVal, availableQty, pId, proType) {

            var availableQty = parseFloat(availableQty);
            // if (qty > availableQty) {
            //     showMsg1();
            //     $('#qty' + indexVal).val();
            //     return false;
            // }

            if (isNaN(qty) || qty == '') {
                qty = 0;
            }


            $.ajax({
                    method: "POST",
                    url: "addRequisitionAjax.php",
                    dataType: 'json',
                    data: {
                        deptId: '<?php echo $_SESSION['deptId']; ?>',
                        pId: pId,
                        qty: qty
                    }
                })
                .done(function(responseObj) {
                    $('#totalOrdArea').html(responseObj.resHtml);
                    $('#totalPrice' + indexVal).html(responseObj.productPrice);
                });

        } //end
        // $('.sub-btn').click(function(){
        // $('#frm').submit();
        // });
        // function clearTempItem() {

        //     $("#dialog4").dialog({
        //         autoOpen: false,
        //         modal: true,
        //         //title     : "Title",
        //         buttons: {
        //             '<?php echo showOtherLangText('Yes') ?>': function() {
        //                 //Do whatever you want to do when Yes clicked
        //                 $(this).dialog('close');
        //                 window.location.href = 'addRecusation.php?clearTempPro';
        //             },

        //             '<?php echo showOtherLangText('No') ?>': function() {
        //                 //Do whatever you want to do when No clicked
        //                 $(this).dialog('close');
        //             }
        //         }
        //     });

        //     $("#dialog4").dialog("open");
        //     $('.custom-header-text').remove();
        //     $('.ui-dialog-content').prepend(
        //         '<div class="custom-header-text"><span><?php echo showOtherLangText('Queue1.com Says') ?></span></div>');
        // }

        function clearTempItem() {
            var newOnClick = "window.location.href = 'addRecusation.php?clearTempPro'";
            $('.deletelink').attr('onclick', newOnClick);
            $('#clear-popup').modal('show');

        }

        $('.form-submit-btn').click(function() {
            $('#frm').submit();
        });

        //update or insert notes
        function notesVal(pId, productType) {

            var notes = $('#notes' + pId).val();

            $.ajax({
                    method: "POST",
                    url: "addRequisitionAjax.php",

                    data: {
                        pId: pId,
                        notes: notes,
                        deptId: '<?php echo $_SESSION['deptId'] ?>',
                        itemType: productType
                    }
                })
                .done(function(val) {
                    //$('#notes' + pId).val(val);
                });

        } //end
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
    <div class="modal" tabindex="-1" id="delete-popup" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php echo showOtherLangText('Are you sure to delete this record?') ?>
                    </h1>
                </div>

                <div class="modal-footer">
                    <div class="d-flex gap-3">
                        <button type="button" data-bs-dismiss="modal"
                            class="btn btn-primary std-btn"><?php echo showOtherLangText('No'); ?></button>
                        <button type="button" onclick=""
                            class="deletelink btn btn-primary std-btn"><?php echo showOtherLangText('Yes'); ?></button>
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