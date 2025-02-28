<?php
include('inc/dbConfig.php'); //connection details


if (!isset($_SESSION['adminidusername'])) {
    echo '<script>window.location = "login.php"</script>';
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

//check page permission

if (!in_array('4', $checkPermission)) {
    echo "<script>window.location='index.php'</script>";
}

$getAllHistoryPermissions = get_history_permissions($_SESSION['designation_id'], $_SESSION['accountId']);



//Access Xcel File and PDF permission for user
$accessHistoryXclPdfPermission = $getAllHistoryPermissions['access_history_xcl_pdf'];

//Access Accounts related permission for user
$accessHistoryAccountsPermission = $getAllHistoryPermissions['access_accounts_detail'];

//Access payment permission for user
$accessPaymentPermission = get_access_payment_permission($_SESSION['designation_id'], $_SESSION['accountId']);
//Access Invoice permission for user
$accessInvoicePermission = get_access_invoice_permission($_SESSION['designation_id'], $_SESSION['accountId']);


if (isset($_POST['showFields'])) {


    $updateQry = " UPDATE tbl_user SET historyUserFilterFields = '" . implode(',', ($_POST['showFields'])) . "' WHERE id = '" . $_SESSION['id'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ";

    mysqli_query($con, $updateQry);
} elseif (isset($_REQUEST['clearshowFields'])) {
    $updateQry = " UPDATE tbl_user SET historyUserFilterFields = '' WHERE id = '" . $_SESSION['id'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ";
    mysqli_query($con, $updateQry);
}



$sql = "SELECT * FROM tbl_user  WHERE id = '" . $_SESSION['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
$result = mysqli_query($con, $sql);
$userDetails = mysqli_fetch_array($result);
$historyUserFilterFields = $userDetails['historyUserFilterFields'] ?    explode(',', $userDetails['historyUserFilterFields']) : null;

$timenow = date('Y-m-d H:i:s');
if (isset($_GET['orderId']) && $_GET['paymentStatus'] == 1) {
    //change supplier payment status here
    $selQry = " SELECT tp.bankAccountId, tp.amount,tp.currencyId, c.curCode AS curCode, ac.* FROM tbl_accounts ac
INNER JOIN tbl_payment tp ON(tp.bankAccountId = ac.id) AND tp.account_id = ac.account_id
LEFT JOIN tbl_currency c ON(tp.currencyId = c.id) AND tp.account_id = c.account_id
WHERE tp.orderId ='" . $_GET['orderId'] . "' ";
    $paymentResult = mysqli_query($con, $selQry);
    $paymentRow = mysqli_fetch_array($paymentResult);

    $currCode = $paymentRow['curCode'];
    $bankAccountId = $paymentRow['bankAccountId'];
    $paymentStatus = "1";

    $amount = $paymentRow['amount'];
    if ($paymentRow['currencyId'] > 0) {
        $trimedAmount = trim($amount, $currCode);
    } else {
        $trimedAmount = trim($amount, '$');
    }
    $replacedAmount = str_replace(',', '', $trimedAmount);


    $insQry = " UPDATE tbl_orders SET paymentStatus ='" . $paymentStatus . "', paymentDateTime ='" . $timenow . "', bankAccountId ='" . $bankAccountId . "' WHERE id='" . $_GET['orderId'] . "' ";
    mysqli_query($con, $insQry);

    //insert few record in order journey table when user have done their payment
    $sql = " SELECT * FROM tbl_orders WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $res = mysqli_query($con, $sql);
    $resRow = mysqli_fetch_array($res);

    $qry = " INSERT INTO `tbl_order_journey` SET 
`account_id` = '" . $_SESSION['accountId'] . "',
`orderId` = '" . $_GET['orderId'] . "',
`userBy`  = '" . $_SESSION['id'] . "',
`ordDateTime` = '" . date('Y-m-d h:i:s') . "',
`amount` = '" . $resRow['ordAmt'] . "',
`otherCur` = '" . $resRow['ordCurAmt'] . "',
`otherCurId` = '" . $resRow['ordCurId'] . "',
`invoiceNo` = '" . $resRow['invNo'] . "',
`orderType` = '" . $resRow['ordType'] . "',
 `notes` = '" . showOtherLangText('Paid') . "',
`action` = '" . showOtherLangText('payment') . "' ";
    mysqli_query($con, $qry);


    $insQry = " UPDATE tbl_payment SET paymentStatus ='" . $paymentStatus . "' WHERE orderId='" . $_GET['orderId'] . "' ";
    mysqli_query($con, $insQry);

    $sqlQry = " UPDATE tbl_accounts SET balanceAmt=(balanceAmt-$replacedAmount) WHERE id='$bankAccountId'   AND account_id = '" . $_SESSION['accountId'] . "'  ";
    $qrySet = mysqli_query($con, $sqlQry);

    echo '<script>window.location="history.php"</script>';
} elseif (isset($_GET['orderId']) && $_GET['reqPaymentStatus'] == 1) {

    //change requisition payment status here
    $selQry = " SELECT tp.bankAccountId, tp.amount,tp.currencyId, c.curCode AS curCode, ac.* FROM tbl_accounts ac
INNER JOIN tbl_req_payment tp ON(tp.bankAccountId = ac.id) AND tp.account_id = ac.account_id
LEFT JOIN tbl_currency c ON(tp.currencyId = c.id) AND tp.account_id = c.account_id
WHERE tp.orderId ='" . $_GET['orderId'] . "' ";
    $paymentResult = mysqli_query($con, $selQry);
    $paymentRow = mysqli_fetch_array($paymentResult);

    $currCode = $paymentRow['curCode'];
    $bankAccountId = $paymentRow['bankAccountId'];
    $paymentStatus = "1";

    $amount = $paymentRow['amount'];
    if ($paymentRow['currencyId'] > 0) {
        $trimedAmount = trim($amount, $currCode);
    } else {
        $trimedAmount = trim($amount, '$');
    }

    $replacedAmount = str_replace(',', '', $trimedAmount);

    $insQry = " UPDATE tbl_orders SET paymentStatus ='" . $paymentStatus . "', paymentDateTime ='" . $timenow . "', bankAccountId ='" . $bankAccountId . "' WHERE id='" . $_GET['orderId'] . "' ";
    mysqli_query($con, $insQry);

    $insQry = " UPDATE tbl_req_payment SET paymentStatus ='" . $paymentStatus . "' WHERE orderId='" . $_GET['orderId'] . "' ";
    mysqli_query($con, $insQry);

    $sqlQry = " UPDATE tbl_accounts SET balanceAmt=(balanceAmt+$replacedAmount) WHERE id='$bankAccountId'   AND account_id = '" . $_SESSION['accountId'] . "'  ";
    $qrySet = mysqli_query($con, $sqlQry);

    echo '<script>window.location="history.php"</script>';
}

if (isset($_POST['delOrderId'])) {
    $sql = "SELECT * FROM  tbl_user WHERE id='" . $_SESSION['id'] . "' AND password = '" . $_POST['password'] . "' AND status = 1 AND account_id = '" . $_SESSION['accountId'] . "'  ";
    $result = mysqli_query($con, $sql);
    $curUserRes = mysqli_fetch_array($result);

    if ($curUserRes['id'] > 0) {
        $sql = "DELETE FROM tbl_orders  WHERE id='" . $_POST['delOrderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
        mysqli_query($con, $sql);

        echo "<script>window.location = 'history.php?delete=1'</script>";
        die;
    } else {
        echo "<script>window.location = 'history.php?deleteerror=1&delOrderId=" . $_POST['delOrderId'] . "#del'</script>";
        die;
    }
}

if ($_GET['clearSearch'] == 1) {
    unset($_SESSION['fromDate']);
    unset($_SESSION['toDate']);
}

$cond = '';
$cond1 = '';
$condWithoutGroup = '';
if (isset($_GET['fromDate']) && isset($_GET['toDate']) && $_GET['fromDate'] && $_GET['toDate']) {
    $_SESSION['fromDate'] = $_GET['fromDate'];
    $_SESSION['toDate'] = $_GET['toDate'];
} elseif (isset($_GET['fromDateMobile']) && isset($_GET['toDateMobile']) && $_GET['fromDateMobile'] && $_GET['toDateMobile']) {
    $_SESSION['fromDate'] = $_GET['fromDateMobile'];
    $_SESSION['toDate'] = $_GET['toDateMobile'];
}




if (isset($_SESSION['fromDate']) && $_SESSION['fromDate'] != '' && $_SESSION['toDate'] != '') {

    $cond = " AND DATE(o.setDateTime) BETWEEN '" . date('Y-m-d', strtotime($_SESSION['fromDate'])) . "' AND '" . date('Y-m-d', strtotime($_SESSION['toDate'])) . "'  ";
    $cond1 = $cond;
} else {
    $_GET['fromDate'] = date('d-m-Y', strtotime('-3 days'));
    $_GET['toDate'] = date('d-m-Y');
    $_SESSION['fromDate'] = $_GET['fromDate'];
    $_SESSION['toDate'] = $_GET['toDate'];

    $cond = " AND DATE(o.setDateTime) BETWEEN '" . date('Y-m-d', strtotime($_GET['fromDate'])) . "' AND '" . date('Y-m-d', strtotime($_GET['toDate'])) . "' ";
    $cond1 = $cond;
}

if (isset($_GET['ordType']) && $_GET['ordType']) {
    $cond .= " AND o.ordType = '" . $_GET['ordType'] . "'   ";
    $cond1 = $cond;
} else {
    //$cond1 .= " AND o.ordType IN(2,1)  ";
}


// if($_SESSION['adminUser'] != 1)
// {
//     $cond .= " AND o.orderBy = '".$_SESSION['id']."'   ";
//     $cond1 = $cond;
// }
// else
if (isset($_GET['userId']) && $_GET['userId']) {
    $cond .= " AND o.orderBy = '" . $_GET['userId'] . "'   ";
    $cond1 = $cond;
}

// for payment status filter Paid = 1 | Received = 2 | Pending = 3
if ($_GET['statusType'] == 1 && $_GET['ordType'] == 1) {
    $cond .= " AND o.paymentStatus = '1' ";
    $cond1 = $cond;
}

if ($_GET['statusType'] == 3 && $_GET['ordType'] == 1) {
    $cond .= " AND (o.paymentStatus = 0 OR o.paymentStatus = 2) ";
    $cond1 = $cond;
}

if ($_GET['statusType'] == 2 && $_GET['ordType'] == 2) {
    $cond .= " AND o.paymentStatus = '1' ";
    $cond1 = $cond;
}

if ($_GET['statusType'] == 3 && $_GET['ordType'] == 2) {
    $cond .= " AND (o.paymentStatus = 0 OR o.paymentStatus = 2) ";
    $cond1 = $cond;
}

if ($_GET['statusType'] == 3 && $_GET['ordType'] == '') {
    $cond .= " AND (o.paymentStatus = 0 OR o.paymentStatus = 2) AND o.ordType IN(2,1) ";
    $cond1 = $cond;
}

if ($_GET['statusType'] == 1 && $_GET['ordType'] == '' && $_GET['accountNo'] == '') {
    $cond .= " AND o.paymentStatus = 1 AND o.ordType = 1 ";
    $cond1 = $cond;
}

if ($_GET['statusType'] == 2 && $_GET['ordType'] == '' && $_GET['accountNo'] == '') {
    $cond .= " AND o.paymentStatus = 1 AND o.ordType = 2 ";
    $cond1 = $cond;
}




// for account of payment
if (isset($_GET['accountNo']) && $_GET['accountNo'] != '') {
    if ($_GET['statusType'] == '') {
        $cond .= " AND o.bankAccountId = '" . $_GET['accountNo'] . "' AND o.paymentStatus = 1 ";
        $cond1 = $cond;
    }
    if ($_GET['statusType'] == 1) {
        $cond .= " AND o.bankAccountId = '" . $_GET['accountNo'] . "' AND o.paymentStatus = 1 AND o.ordType = 1 ";
        $cond1 = $cond;
    }
    if ($_GET['statusType'] == 2) {
        $cond .= " AND o.bankAccountId = '" . $_GET['accountNo'] . "' AND o.paymentStatus = 1 AND o.ordType = 2 ";
        $cond1 = $cond;
    }
}


if (isset($_GET['suppMemStoreId']) || $_GET['suppMemStoreId'] != '') {
    $suppMemStoreId = $_GET['suppMemStoreId'];
    $getSupMemStoreId = explode('_', $suppMemStoreId);
    $getTxtById = $getSupMemStoreId[0];
    $getId = $getSupMemStoreId[1];

    if ($getTxtById == 'suppId') {
        $cond .= " AND o.supplierId = " . $getId . "   ";
        $cond1 = $cond;
    }

    if ($getTxtById == 'deptUserId') {
        $cond .= " AND o.recMemberId = " . $getId . "   ";
        $cond1 = $cond;
    }

    if ($getTxtById == 'storeId') {
        $cond .= " AND o.storeId = " . $getId . "   ";
        $cond1 = $cond;
    }
}

// for stores filter
if (isset($_GET['storeId']) && $_GET['storeId']) {
    $cond .= " AND o.storeId = '" . $_GET['storeId'] . "'   ";
    $cond1 = $cond;
}
if (isset($_GET['deptId']) && $_GET['deptId']) {
    $cond .= " AND od.deptId = '" . $_GET['deptId'] . "'   ";
    $cond1 = $cond;
}


if ($cond != '') {
    $_SESSION['getVals'] = $_GET;
} else {
    unset($_SESSION['getVals']);
}

if (isset($_GET['accountNo']) && $_GET['accountNo'] > 0) {
    $cond .= " AND o.bankAccountId = '" . $_GET['accountNo'] . "' AND o.paymentStatus = 1 ";
    $cond1 = $cond;
}

$condWithoutGroup = $cond;

if ($_GET['dateType'] == '') {
    $cond .= " GROUP BY o.id ORDER BY o.sortingNo desc "; //in case of default sorting use sortingNo to give correct sorting
    $cond1 = $cond;
}

if ($_GET['dateType'] != '' && $_GET['dateType'] == 1) { //in case of submit date use ordNumber to give correct sorting
    $cond .= " GROUP BY o.id ORDER BY o.ordNumber desc ";
    $cond1 = $cond;
}
if ($_GET['dateType'] != '' && $_GET['dateType'] == 2) { //in case of settled date use sortingNo to give correct sorting
    $cond .= " GROUP BY o.id ORDER BY o.sortingNo desc ";
    $cond1 = $cond;
}
if ($_GET['dateType'] != '' && $_GET['dateType'] == 3) {

    $cond .= " GROUP BY o.id ORDER BY o.paymentDateTime desc, o.sortingNo desc ";
    $cond1 = $cond;
}




$mainSqlQry = " SELECT
o.*,
du.name AS deptUserName,
u.name AS userName,
s.name AS storeName,
sp.name AS suppName,
od.pId,
ac.accountName,
du.receive_inv,
GROUP_CONCAT(DISTINCT(sp.name)) suppliers,
GROUP_CONCAT(DISTINCT(d.name)) departments
FROM
tbl_order_details od
INNER JOIN tbl_orders o ON
(o.id = od.ordId) AND o.account_id = od.account_id

LEFT JOIN tbl_payment p ON
(p.orderId = o.id) AND p.account_id = o.account_id

LEFT JOIN tbl_req_payment rp ON
(rp.orderId = o.id)  AND rp.account_id = o.account_id

LEFT JOIN tbl_accounts ac ON
(o.bankAccountId = ac.id) AND o.account_id = ac.account_id

LEFT JOIN tbl_department d ON
(d.id = o.deptId) AND d.account_id = o.account_id

LEFT JOIN tbl_deptusers du ON
(o.recMemberId = du.id) AND o.account_id = du.account_id
LEFT JOIN tbl_stores s ON
(o.storeId = s.id) AND o.account_id = s.account_id
LEFT JOIN tbl_user u ON
(o.orderBy = u.id) AND o.account_id = u.account_id
LEFT JOIN tbl_suppliers sp ON
(o.supplierId = sp.id) AND o.account_id = sp.account_id
WHERE o.status = 2 AND o.account_id = '" . $_SESSION['accountId'] . "' " . $cond . " ";
$historyQry = mysqli_query($con, $mainSqlQry);

$bankAccountIdsStr = getAccountIds($_SESSION['accountId'], $condWithoutGroup); //get this to pass into below query in account list section

// $cond = '';
// $cond1 = '';

// //From Date || To Date Filter
// if( isset($_SESSION['getVals']['fromDate']) && $_SESSION['getVals']['fromDate'] != '' && $_SESSION['getVals']['toDate'] != '')
// {
//     $cond = " AND DATE(setDateTime) BETWEEN '".date('Y-m-d', strtotime($_SESSION['getVals']['fromDate']) )."' AND '".date('Y-m-d', strtotime($_SESSION['getVals']['toDate']) )."'   ";
//     $cond1 = $cond;
//     $fromDate = $_SESSION['getVals']['fromDate'];
//     $toDate = $_SESSION['getVals']['toDate'];
// }
// elseif (isset($_SESSION['fromDate']) && $_SESSION['fromDate'] != '' && $_SESSION['toDate'] != '') {
//     $cond = " AND DATE(o.setDateTime) BETWEEN '".date('Y-m-d', strtotime($_SESSION['fromDate']) )."' AND '".date('Y-m-d', strtotime($_SESSION['toDate']) )."' ";
//     $cond1 = $cond;
//     $fromDate = $_SESSION['fromDate'];
//     $toDate = $_SESSION['toDate'];
// }
// else
// {
//     $_GET['fromDate'] = date('d-m-Y', strtotime('-3 days') );
//     $_GET['toDate'] = date('d-m-Y');

//     $cond = " AND DATE(o.setDateTime) BETWEEN '".date('Y-m-d', strtotime($_GET['fromDate']) )."' AND '".date('Y-m-d', strtotime($_GET['toDate']) )."' ";
//     $cond1 = $cond;
//     $fromDate = $_GET['fromDate'];
//     $toDate = $_GET['toDate'];
// }
//get issued in total and issued out total
$sql = " SELECT o.ordAmt AS totalOrdAmt, o.ordType, o.paymentStatus,od.currencyId 
FROM tbl_order_details od 

INNER JOIN tbl_orders o 
ON(o.id = od.ordId) AND o.account_id = od.account_id

LEFT JOIN tbl_payment p 
ON(p.orderId = o.id) AND p.account_id = o.account_id

LEFT JOIN tbl_req_payment rp 
ON(rp.orderId = o.id) AND rp.account_id = o.account_id

WHERE o.status = '2' AND o.account_id = '" . $_SESSION['accountId'] . "'" . $cond1 . "  ";

$issueInAndOutQry = mysqli_query($con, $sql);

$issuedInOutArr = [];
$issuedInOutArrBills = [];
while ($inAndOutRow = mysqli_fetch_array($issueInAndOutQry)) {

    if ($inAndOutRow['ordType'] == 1) {
        $issueInTotal += $inAndOutRow['totalOrdAmt'];
    }
    if ($inAndOutRow['ordType'] == 2) {
        $issueOutTotal += $inAndOutRow['totalOrdAmt'];
    }

    if ($inAndOutRow['paymentStatus'] == 0 || $inAndOutRow['paymentStatus'] == 2) { // Sum of total pending order amount and regund which status is 2
        $totalSumAmt = $inAndOutRow['totalOrdAmt'];
        $issuedInOutPendingArr[$inAndOutRow['ordType']][0] += $totalSumAmt;

        //$issuedInOutCurCode = $inAndOutRow['currencyId'];
    } elseif ($inAndOutRow['paymentStatus'] == 1) { // Sum of total paid order amount
        $totalSumAmt = $inAndOutRow['totalOrdAmt'];
        $issuedInOutPaidArr[$inAndOutRow['ordType']][$inAndOutRow['paymentStatus']] += $totalSumAmt;
    }
}

$otherCurrRowArr = [];
$otherCurrTotalValueArr = [];
$otherCurrPendingTotalValueArr = [];
$issueInTotal_defaultcurrency = 0;
$issueInTotal_defaultcurrency_pending = 0;
$issueInTotal_defaultcurrency_paid = 0;
$sqlfordefaultCurrencyOnly = " SELECT od.currencyId, o.ordAmt AS totalOrdCurAmt, o.paymentStatus,o.ordType FROM tbl_order_details od INNER JOIN tbl_orders o 
    ON(o.id=od.ordId) AND o.account_id=od.account_Id WHERE o.status = '2' AND o.ordCurAmt = 0 AND o.account_id = '" . $_SESSION['accountId'] . "' " . $cond . " ";

$defaultCurrencyOnly = mysqli_query($con, $sqlfordefaultCurrencyOnly);
while ($inRowfordefaultcurr = mysqli_fetch_array($defaultCurrencyOnly)) {

    if ($inRowfordefaultcurr['ordType'] == 1) {
        $issueInTotal_defaultcurrency += $inRowfordefaultcurr['totalOrdCurAmt'];


        if ($inRowfordefaultcurr['paymentStatus'] == 0 || $inRowfordefaultcurr['paymentStatus'] == 2) {
            $totalSumAmt = $inRowfordefaultcurr['totalOrdCurAmt'];
            $issueInTotal_defaultcurrency_pending += $totalSumAmt;
        } elseif ($inRowfordefaultcurr['paymentStatus'] == 1) {
            $totalSumAmt = $inRowfordefaultcurr['totalOrdCurAmt'];
            $issueInTotal_defaultcurrency_paid += $totalSumAmt;
        }
    }
}

if ($checkIfPermissionToNewOrderSec > 0) {

    $sql = " SELECT od.currencyId, c.curCode, o.ordCurAmt AS totalOrdCurAmt, o.paymentStatus FROM tbl_order_details od
                    INNER JOIN tbl_orders o 
                        ON(o.id=od.ordId) AND o.account_id=od.account_Id
                    INNER JOIN tbl_currency c
                        ON(od.currencyId=c.id) AND od.account_id=c.account_Id
                    WHERE o.status = '2' AND o.account_id = '" . $_SESSION['accountId'] . "' " . $cond . " ";

    $result = mysqli_query($con, $sql);
    while ($otherCurrRows = mysqli_fetch_array($result)) {
        if ($otherCurrRows['currencyId'] > 0 && ($otherCurrRows['paymentStatus'] == 0 || $otherCurrRows['paymentStatus'] == 2)) {

            // Total rows of other currency pending amount
            $otherCurrRowArr[$otherCurrRows['currencyId']] = $otherCurrRows;

            // Sum of total values of pending amount 
            $totalSumAmt = $otherCurrRows['totalOrdCurAmt'];
            $otherCurrPendingTotalValueArr[$otherCurrRows['currencyId']] += $totalSumAmt;
        } elseif ($otherCurrRows['currencyId'] > 0 && $otherCurrRows['paymentStatus'] == 1) {

            // Total rows of other currency paid amount
            $otherCurrRowArr[$otherCurrRows['currencyId']] = $otherCurrRows;

            // Sum of total values of paid amount 
            $totalSumAmt = $otherCurrRows['totalOrdCurAmt'];
            $otherCurrPaidTotalValueArr[$otherCurrRows['currencyId']] += $totalSumAmt;
        }

        if ($otherCurrRows['currencyId'] > 0) {

            // Total rows of other currency paid amount
            $otherCurrRowArr[$otherCurrRows['currencyId']] = $otherCurrRows;

            // Sum of total values of paid amount 
            $totalSumAmt = $otherCurrRows['totalOrdCurAmt'];
            $otherCurrTotalValueArr[$otherCurrRows['currencyId']] += $totalSumAmt;
        }
    }

    $count = 0;
    foreach ($otherCurrRowArr as $otherCurrRow) {
        $count++;
    }
}

//---------------------------------------------

$typeArr = [1 => '' . showOtherLangText('Issued In') . '', 2 => '' . showOtherLangText('Issued Out') . '', 3 => '' . showOtherLangText('Stock Take') . '', 4 => '' . showOtherLangText('Raw Item Convert') . ''];


$typeOptions = '<ul class="dropdown-menu type_dropdown">';
$typeOptions .= '<li data-id="" data-value="' . showOtherLangText('Type') . '"><a class="dropdown-item" href="javascript:void(0)">' . showOtherLangText('Type') . '</a></li>';
foreach ($typeArr as $typeKey => $typeVal) {
    $sel = isset($_GET['ordType']) && $_GET['ordType'] == $typeKey  ? 'selected' : '';
    $typeOptions .= '<li data-id="' . $typeKey . '" data-value="' . $typeVal . '"><a class="dropdown-item ' . $sel . '" href="javascript:void(0)">' . $typeVal . '</a></li>';
}
$typeOptions .= '</ul>';



// for date 

if ($accessHistoryAccountsPermission['type_id'] == 1) {

    $dateArr = [1 => '' . showOtherLangText('Submit Date') . '', 2 => '' . showOtherLangText('Settle Date') . '', 3 => '' . showOtherLangText('Payment Date') . ''];
} else {
    $dateArr = [1 => '' . showOtherLangText('Submit Date') . '', 2 => '' . showOtherLangText('Settle Date') . ''];
}



$dateTypeOptions = '<ul class="dropdown-menu date_type">';
$dateTypeOptions .= '<li data-id="" data-value="' . showOtherLangText('Date') . '"><a class="dropdown-item" href="javascript:void(0)">' . showOtherLangText('Date') . '</a></li>';
foreach ($dateArr as $dateKey => $dateVal) {
    $sel = isset($_GET['dateType']) && $_GET['dateType'] == $dateKey  ? 'selected' : '';
    $dateTypeOptions .= '<li data-id="' . $dateKey . '" data-value="' . $dateVal . '"><a  class="dropdown-item ' . $sel . '" href="javascript:void(0)">' . $dateVal . '</a></li>';
}
$dateTypeOptions .= '</ul>';



// for payment status
if ($_GET['ordType'] == '' || $_GET['suppMemStoreId'] == '') {

    $statusArr = [1 => '' . showOtherLangText('Paid') . '', 2 => '' . showOtherLangText('Received') . '', 3 => '' . showOtherLangText('Pending') . ''];
}
if (($_GET['ordType'] == 1 || $getTxtById == 'suppId') || ($_GET['ordType'] == 1 && $getTxtById == 'suppId')) {
    $statusArr = [1 => '' . showOtherLangText('Paid') . '', 3 => '' . showOtherLangText('Pending') . ''];
}

if (($_GET['ordType'] == 2 || $getTxtById == 'deptUserId') || ($_GET['ordType'] == 2 && $getTxtById == 'deptUserId')) {
    $statusArr = [2 => '' . showOtherLangText('Received') . '', 3 => '' . showOtherLangText('Pending') . ''];
}

if ($_GET['dateType'] == 3 || $_GET['accountNo'] > 0) {
    $statusArr = [1 => '' . showOtherLangText('Paid') . '', 2 => '' . showOtherLangText('Received') . ''];
}

if ($_GET['dateType'] == 3 && $_GET['ordType'] == 1) {
    $statusArr = [1 => '' . showOtherLangText('Paid') . ''];
}

if ($_GET['dateType'] == 3 && $_GET['ordType'] == 2) {
    $statusArr = [2 => '' . showOtherLangText('Received') . ''];
}

if ($_GET['dateType'] == 3 && $getTxtById == 'suppId') {
    $statusArr = [1 => '' . showOtherLangText('Paid') . ''];
}

if ($_GET['dateType'] == 3 && $getTxtById == 'deptUserId') {
    $statusArr = [2 => '' . showOtherLangText('Received') . ''];
}
if ($accessHistoryAccountsPermission['type_id'] == 0) {

    $statusArr = [3 => '' . showOtherLangText('Pending') . ''];
}

$statusTypeOptions = '<ul class="dropdown-menu status_type">';
$statusTypeOptions .= '<li data-id="" data-value="' . showOtherLangText('Status') . '"><a class="dropdown-item" href="javascript:void(0)">' . showOtherLangText('Status') . '</a></li>';
foreach ($statusArr as $statusKey => $statusVal) {
    $sel = isset($_GET['statusType']) && $_GET['statusType'] == $statusKey  ? 'selected' : '';
    $statusTypeOptions .= '<li data-id="' . $statusKey . '" data-value="' . $statusVal . '"><a class="dropdown-item ' . $sel . '" href="javascript:void(0)">' . $statusVal . '</a>
                                                       </li>
';
}
$statusTypeOptions .= '</ul>';

//--------------------------------------------------------------------------------

if (isset($_SESSION['fromDate']) && $_SESSION['fromDate'] != '' && $_SESSION['toDate'] != '') {
    $date = " AND DATE(setDateTime) BETWEEN '" . date('Y-m-d', strtotime($_SESSION['fromDate'])) . "' AND '" . date('Y-m-d', strtotime($_SESSION['toDate'])) . "'  ";
} else {
    $_GET['fromDate'] = date('d-m-Y', strtotime('-3 days'));
    $_GET['toDate'] = date('d-m-Y');
    $date = " AND DATE(setDateTime) BETWEEN '" . date('Y-m-d', strtotime($_GET['fromDate'])) . "' AND '" . date('Y-m-d', strtotime($_GET['toDate'])) . "' ";
}

// for user type
$status = '';
if (isset($_GET['ordType']) && $_GET['ordType'] > 0) {

    $status = "AND o.ordType = '" . $_GET['ordType'] . "' ";
}

if ($_GET['statusType'] == 1) {

    $status .= "AND o.paymentStatus = 1 AND o.ordType = 1 ";
}
if ($_GET['statusType'] == 2) {

    $status .= "AND o.paymentStatus = 1 AND o.ordType = 2 ";
}
if ($_GET['statusType'] == 3) {

    $status .= "AND (o.paymentStatus = 2 OR o.paymentStatus = 0) ";
}

if (isset($_GET['accountNo']) && $_GET['accountNo'] > 0) {
    $status .= " AND o.bankAccountId = '" . $_GET['accountNo'] . "'  ";
}
if (isset($_GET['dateType']) && $_GET['dateType'] == 3) {
    $status .= " AND o.paymentStatus = 1  ";
}

$checkIfPermissionToNewOrderSec = checkIfPermissionToNewOrderSec($_SESSION['designation_id'], $_SESSION['accountId']);

if (mysqli_num_rows($checkIfPermissionToNewOrderSec) < 1) {
    $status .= " AND o.ordType = 2  ";
}

$checkIfPermissionToNewReqSec = checkIfPermissionToNewReqSec($_SESSION['designation_id'], $_SESSION['accountId']);

if (mysqli_num_rows($checkIfPermissionToNewReqSec) < 1) {
    $status .= " AND o.ordType = 1  ";
}

$sqlSet = " SELECT u.* FROM  tbl_user u 
INNER JOIN tbl_orders o 
ON (u.id=o.orderBy)

INNER JOIN tbl_order_details od 
ON (o.id=od.ordId)

WHERE o.status = 2 " . $date . " " . $status . " AND u.account_id = '" . $_SESSION['accountId'] . "' GROUP BY o.orderBy  ORDER BY u.username ";
$resultSet = mysqli_query($con, $sqlSet);

$userOptions = '<ul class="dropdown-menu user_type_dropdown">';
$userOptions .= '<li data-id="" data-value="' . showOtherLangText('User') . '"><a class="dropdown-item" href="javascript:void(0)">' . showOtherLangText('User') . '</a></li>';
while ($userRow = mysqli_fetch_array($resultSet)) {

    $sel = isset($_GET['userId']) && $_GET['userId'] == $userRow['id']  ? 'selected' : '';

    $userOptions .= '<li data-id="' . $userRow['id'] . '" data-value="' . $userRow['name'] . '" ><a class="dropdown-item isuIn-grReq ' . $sel . ' " href="javascript:void(0)">' . $userRow['name'] . '</a></li>';
}
$userOptions .= '</ul>';

//for account
$showAccount = '';
if ($_GET['ordType'] != '' &&  ($_GET['ordType'] == 1 || $_GET['ordType'] == 2)) {

    $showAccount .= " AND o.ordType = '" . $_GET['ordType'] . "' ";
}
if ($getTxtById != '' && $getTxtById == 'deptUserId') {

    $showAccount .= " AND o.recMemberId = " . $getId . " ";
}
if ($getTxtById != '' && $getTxtById == 'suppId') {

    $showAccount .= " AND o.supplierId = " . $getId . " ";
}

$sqlSet = " SELECT a.* FROM  tbl_accounts a 
INNER JOIN tbl_orders o 
ON (a.id=o.bankAccountId)
INNER JOIN tbl_order_details od 
ON (o.id=od.ordId)
WHERE o.status = 2 " . $date . " " . $showAccount . " AND o.paymentStatus = 1 AND a.account_id = '" . $_SESSION['accountId'] . "' GROUP BY o.bankAccountId ORDER BY id ";
$resultSet = mysqli_query($con, $sqlSet);

$accountOptions = '<ul class="dropdown-menu account_dropdown">';
$accountOptions .= '<li data-id="" data-value="' . showOtherLangText('Account') . '"><a class="dropdown-item" href="javascript:void(0)">' . showOtherLangText('Account') . '</a></li>';
while ($accountRow = mysqli_fetch_array($resultSet)) {
    $sel = isset($_GET['accountNo']) && $_GET['accountNo'] == $accountRow['id']  ? 'selected' : '';
    // $accountOptions .= '<option value="'.$accountRow['id'].'" '.$sel.'>'.$accountRow['accountName'].'</option>';
    $accountOptions .= '<li data-id="' . $accountRow['id'] . '" data-value="' . $accountRow['accountName'] . '"><a class="dropdown-item ' . $sel . '" href="javascript:void(0)">' . $accountRow['accountName'] . '</a></li>';
}
$accountOptions .= '</ul>';


// supplier | deptUser | Store all data fetch
$suppDetail = '';
if ($_GET['dateType'] == 3) {
    $suppDetail = " AND o.paymentStatus = 1 ";
}
if ($_GET['statusType'] == 1) {
    $suppDetail .= " AND o.paymentStatus = 1 ";
}
if ($_GET['statusType'] == 3) {
    $suppDetail .= " AND o.paymentStatus = 0 OR o.paymentStatus = 2  ";
}
if (isset($_GET['accountNo']) && $_GET['accountNo'] > 0) {
    $suppDetail .= " AND o.paymentStatus = 1 AND o.bankAccountId = '" . $_GET['accountNo'] . "'  ";
}

$sqlQry = " SELECT s.* FROM  tbl_suppliers s 
INNER JOIN tbl_orders o 
ON (s.id=o.supplierId)
INNER JOIN tbl_order_details od 
ON (o.id=od.ordId)
WHERE o.status = 2 " . $date . " " . $suppDetail . " AND s.account_id = '" . $_SESSION['accountId'] . "' GROUP BY o.supplierId ORDER BY name ";
$result = mysqli_query($con, $sqlQry);

$suppMemStoreOptions = '<ul class="dropdown-menu referto_dropdown">';
$suppMemStoreOptions .= '<li data-id="" data-value="' . showOtherLangText('Refer To') . '"><a class="dropdown-item" href="javascript:void(0)">' . showOtherLangText('Refer To') . '</a></li>';
while ($suppRow = mysqli_fetch_array($result)) {
    $checkorderRow = checkorderPermissionRow($_SESSION['designation_id'], $_SESSION['accountId'], $suppRow['id']);
    if (mysqli_num_rows($checkorderRow) < 1) {
        continue; //exclude this order as user don't have its permission
    }
    $sel = ($getId == $suppRow['id']) ? 'selected' : '';
    if ($_GET['ordType'] == 1 || ($_GET['ordType'] == '' && $_GET['statusType'] != 2) || ($_GET['ordType'] == '' && $_GET['statusType'] == '')) {

        $suppMemStoreOptions .=  '<li data-id="suppId_' . $suppRow['id'] . '" data-value="' . $suppRow['name'] . '" ><a class="dropdown-item isuIn-grReq ' . $sel . ' " href="javascript:void(0)">' . $suppRow['name'] . '</a></li>';
    }
}


// for member type
$deptDetail = '';
if ($_GET['dateType'] == 3) {
    $deptDetail = " AND o.paymentStatus = 1 ";
}
if ($_GET['statusType'] == 2) {
    $deptDetail .= " AND o.paymentStatus = 1 ";
}
if ($_GET['statusType'] == 3) {
    $deptDetail .= " AND o.paymentStatus = 0 OR o.paymentStatus = 2  ";
}
if (isset($_GET['accountNo']) && $_GET['accountNo'] > 0) {
    $deptDetail .= " AND o.paymentStatus = 1 AND o.bankAccountId = '" . $_GET['accountNo'] . "'  ";
}

$sqlSet = " SELECT du.* FROM  tbl_deptusers du 
INNER JOIN tbl_orders o 
ON (du.id=o.recMemberId)
INNER JOIN tbl_order_details od 
ON (o.id=od.ordId)
WHERE o.status = 2 " . $date . " " . $deptDetail . " AND du.account_id = '" . $_SESSION['accountId'] . "' GROUP BY o.recMemberId ORDER BY name ";
$resultSet = mysqli_query($con, $sqlSet);
$memId = '';
while ($deptUserRow = mysqli_fetch_array($resultSet)) {
    $checkRow = checkreqpermissionRow($_SESSION['designation_id'], $_SESSION['accountId'], $deptUserRow['id']);
    if (mysqli_num_rows($checkRow) < 1) {
        continue; //exclude this order as user don't have its permission
    }
    $sel = ($getId == $deptUserRow['id']) ? 'selected' : '';

    if ($_GET['ordType'] == 2  || ($_GET['ordType'] == '' && $_GET['statusType'] != 1) || ($_GET['ordType'] == '' && $_GET['statusType'] == '')) {


        $suppMemStoreOptions .=  '<li data-id="deptUserId_' . $deptUserRow['id'] . '" data-value="' . $deptUserRow['name'] . '"><a class="dropdown-item isuOut-rdSup ' . $sel . '" href="javascript:void(0)">' . $deptUserRow['name'] . '</a></li>';
    }
}


// for store type

$sqlSet = " SELECT st.* FROM  tbl_stores st 
INNER JOIN tbl_orders o 
ON (st.id=o.storeId)
INNER JOIN tbl_order_details od 
ON (o.id=od.ordId)
WHERE o.status = 2 $date  AND st.account_id = '" . $_SESSION['accountId'] . "' GROUP BY o.storeId ORDER BY name ";
$resultSet = mysqli_query($con, $sqlSet);

while ($storeRow = mysqli_fetch_array($resultSet)) {

    $sel = ($getId == $storeRow['id']) ? 'selected' : '';
    if ($_GET['ordType'] == 3 || ($_GET['ordType'] == '' && $_GET['statusType'] == '' && $_GET['accountNo'] == '' && $_GET['dateType'] != 3)) {


        $suppMemStoreOptions .=  '<li data-id="storeId_' . $storeRow['id'] . '" data-value="' . $storeRow['name'] . '" ><a class="dropdown-item stockTake-pr ' . $sel . ' " href="javascript:void(0)">' . $storeRow['name'] . '</a></li>';
    }
}

$suppMemStoreOptions .= '</ul>';


//-----------------------------------------------

//open popup when supplier payment is done
if (isset($_GET['orderId']) && isset($_GET['paymentStatus']) && $_GET['paymentStatus'] == 1) {

    echo '<script>window.open("showPaymentDetailPopup.php?orderId=' . $_GET['orderId'] . '", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500");</script>';
}

//open popup when requisition payment is done
if (isset($_GET['orderId']) && isset($_GET['reqPaymentStatus']) && $_GET['reqPaymentStatus'] == 1) {
    echo '<script>window.open("requisitionPaymentSummaryPopup.php?orderId=' . $_GET['orderId'] . '", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500");</script>';
}

$colsArr = [

    1 => '' . showOtherLangText('Number') . '',
    2 => '' . showOtherLangText('Date') . '',
    3 => '' . showOtherLangText('User') . '',
    4 => '' . showOtherLangText('Type') . '',
    7 => '' . showOtherLangText('Refer To') . '',
    8 => '' . showOtherLangText('Supplier Invoice No.') . '',
    10 => '' . showOtherLangText('Value') . '',
    14 => '' . showOtherLangText('Payment Status') . '',
    15 => '' . showOtherLangText('Payment No.') . '',
    16 => '' . showOtherLangText('Invoice No.') . '',
    17 => '' . showOtherLangText('Account') . '',

];


if (isset($_GET['ordType'])) {
    switch ($_GET['ordType']) {
        case 1: //issuein

            //unset( $colsArr[5] );
            unset($colsArr[6]);
            unset($colsArr[9]);
            unset($colsArr[11]);
            unset($colsArr[12]);
            unset($colsArr[13]);
            //unset( $colsArr[14] );
            //unset( $colsArr[15] );
            unset($colsArr[16]);
            //unset( $colsArr[17] );
            //unset( $colsArr[18] );
            break;


        case 2: //issueOut

            //unset( $colsArr[5] );
            //unset( $colsArr[7] );
            unset($colsArr[8]);
            unset($colsArr[9]);
            unset($colsArr[11]);
            //unset( $colsArr[12] );
            //unset( $colsArr[13] );
            unset($colsArr[15]);
            break;

        case 3: //stock Take

            unset($colsArr[5]);
            unset($colsArr[6]);
            //unset( $colsArr[7] );
            unset($colsArr[8]);
            unset($colsArr[11]);
            unset($colsArr[14]);
            unset($colsArr[15]);
            unset($colsArr[16]);
            unset($colsArr[17]);
            //unset( $colsArr[18] );
            break;

        case 4: //raw item converted


            unset($colsArr[5]);
            unset($colsArr[6]);
            unset($colsArr[7]);
            unset($colsArr[8]);
            unset($colsArr[9]);
            unset($colsArr[11]);
            //unset( $colsArr[12] );
            //unset( $colsArr[13] );
            unset($colsArr[14]);
            unset($colsArr[15]);
            unset($colsArr[16]);
            unset($colsArr[17]);
            //unset( $colsArr[18] );
            break;
    }
}

if ($accessHistoryAccountsPermission['type_id'] == 0) {
    unset($colsArr[14]);
    unset($colsArr[15]);
    unset($colsArr[16]);
    unset($colsArr[17]);
}

// for paid payment
if ($_GET['statusType'] == 1) {
    unset($colsArr[6]);
    unset($colsArr[9]);
    unset($colsArr[11]);
    //unset( $colsArr[12] );
    //unset( $colsArr[13] );  
    unset($colsArr[16]);
}

// for received payment
if ($_GET['statusType'] == 2) {
    //unset( $colsArr[7] );
    unset($colsArr[8]);
    unset($colsArr[9]);
    unset($colsArr[11]);
    //unset( $colsArr[12] );
    //unset( $colsArr[13] );
    unset($colsArr[15]);
}

// for panding payment
if ($_GET['statusType'] == 3) {

    unset($colsArr[11]);
    unset($colsArr[15]);
    unset($colsArr[16]);
    unset($colsArr[17]);
}

// for supplier only
if ($getTxtById == 'suppId') {
    unset($colsArr[11]);
    unset($colsArr[6]);
    unset($colsArr[9]);
    //unset( $colsArr[12] );
    //unset( $colsArr[13] );  
    unset($colsArr[16]);
}

// for department user only
if ($getTxtById == 'deptUserId') {
    unset($colsArr[8]);
    unset($colsArr[9]);
    unset($colsArr[11]);
    //unset( $colsArr[12] );
    //unset( $colsArr[13] );
    unset($colsArr[15]);
}

// for store only 
if ($getTxtById == 'storeId') {
    unset($colsArr[5]);
    unset($colsArr[6]);
    unset($colsArr[8]);
    unset($colsArr[11]);
    unset($colsArr[14]);
    unset($colsArr[15]);
    unset($colsArr[16]);
    unset($colsArr[17]);
}

//print_r($colsArr);

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css?v=1">
    <link rel="stylesheet" href="Assets/css/module-A.css">
    <link rel="stylesheet" href="Assets/css/history-page.css?v=3">
    <!-- <link rel="stylesheet" href="Assets/css/style_p.css"> -->


</head>

<body>

    <div class="container-fluid newOrder update">
        <div class="row">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                <?php require_once('nav.php'); ?>
            </div>
            <div class="cntArea">
                <section class="usr-info position-relative">
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
                                    <h1 class="h1"><?php echo showOtherLangText('History'); ?></h1>
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
                <!-- <form method="get" action="history_pdf_download.php" target="_blank"> -->
                <input type="hidden" name="history_pdf_page" value="1" id="history_pdf_page">
                <section class="hisParent-sec">

                    <section class="ordDetail hisTory">

                        <?php if (isset($_GET['delete']) || isset($_GET['status']) || isset($_GET['paymentStatus'])) { ?>
                            <div class="alrtMessage mx-lg-4 mt-4">
                                <div class="container">
                                    <a name="del"></a>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <p>
                                            <?php

                                            echo isset($_GET['status']) ? ' ' . showOtherLangText('New record added successfully') . ' ' : '';

                                            echo isset($_GET['delete']) ? ' ' . showOtherLangText('Record deleted successfully.') . ' ' : '';
                                            if (isset($_GET['paymentStatus']) && $_GET['paymentStatus'] == 2) {
                                                echo ' ' . showOtherLangText('We have initiated a refund in your account') . ' ';
                                            }

                                            ?>
                                        </p>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?php echo showOtherLangText('Close'); ?>"></button>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (isset($_GET['deleteerror'])) { ?>
                            <div class="alrtMessage mx-lg-4 mt-4">
                                <div class="container">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <p><?php

                                            echo showOtherLangText('Invalid Password');

                                            ?>
                                        </p>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?php echo showOtherLangText('Close'); ?>"></button>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>


                        <form name="frm" id="frm" method="get" action="">
                            <input type="hidden" name="downloadType" id="downloadType" value="" />
                            <input type="hidden" name="downloadType" id="downloadType" value="" />
                            <input type="hidden" name="ordType" id="ordType" value="<?php if (isset($_GET['ordType'])) {
                                                                                        echo $_GET['ordType'];
                                                                                    } ?>">

                            <input type="hidden" name="dateType" id="dateType" value="<?php if (isset($_GET['dateType'])) {
                                                                                            echo $_GET['dateType'];
                                                                                        } ?>">
                            <input type="hidden" name="statusType" id="statusType" value="<?php if (isset($_GET['statusType'])) {
                                                                                                echo $_GET['statusType'];
                                                                                            } ?>">
                            <input type="hidden" name="accountNo" id="accountNo" value="<?php if (isset($_GET['accountNo'])) {
                                                                                            echo $_GET['accountNo'];
                                                                                        } ?>">
                            <input type="hidden" name="suppMemStoreId" id="suppMemStoreId" value="<?php if (isset($_GET['suppMemStoreId'])) {
                                                                                                        echo $_GET['suppMemStoreId'];
                                                                                                    } ?>">
                            <input type="hidden" name="userId" id="userId" value="<?php if (isset($_GET['userId'])) {
                                                                                        echo $_GET['userId'];
                                                                                    } ?>">
                            <?php


                            $col_class_one = 0;
                            $col_class_two = 0;

                            if (isset($historyUserFilterFields)) {
                                foreach ($historyUserFilterFields as $key => $val) {

                                    if (in_array($val, [1, 2, 3, 4, 7])) {
                                        $col_class_one++;
                                    }

                                    if (in_array($val, [14, 15, 16, 17])) {
                                        $col_class_two++;
                                    }
                            ?>

                            <?php
                                }
                            }
                            ?>
                            <div class="container hisData">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="hstCal">
                                            <div class="his-featBtn">
                                                <button class="btn btn-primary fab__search__btn d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                    <i class="fa-regular fa-calendar p-0"></i>
                                                </button>
                                                <div class="cal-Ender d-none d-lg-inline-block">
                                                    <a href="javascript:void(0)">
                                                        <i class="fa-regular fa-calendar"></i>
                                                    </a>
                                                </div>
                                                <!-- Filter Btn Start -->
                                                <div class="his-filtBtn">
                                                    <a href="javascript:void(0)" class="head-Filter">
                                                        <img src="Assets/icons/filter.svg" alt="Filter">
                                                    </a>
                                                </div>
                                                <!-- Filter Btn End -->
                                            </div>
                                            <!-- Date Box Start -->
                                            <div class="prtDate">
                                                <div class="hstDate">
                                                    <input type="text" size="10" class="datepicker" placeholder="<?php echo showOtherLangText('From date') ?>" name="fromDate" autocomplete="off" value="<?php echo isset($_SESSION['fromDate']) ? $_SESSION['fromDate'] : $_GET['fromDate']; ?>">
                                                    <span>-</span>
                                                    <input type="text" size="10" class="datepicker" placeholder="<?php echo showOtherLangText('To date') ?>" name="toDate" autocomplete="off" value="<?php echo isset($_SESSION['toDate']) ? $_SESSION['toDate'] : $_GET['toDate']; ?>">
                                                </div>
                                                <div class="reloadBtn date-box-search">
                                                    <a href="javascript:void(0)"><i class="fa-solid fa-arrows-rotate"></i></a>
                                                </div>
                                                <?php
                                                if ($cond != '') {
                                                ?>
                                                    <div class="reloadBtn">
                                                        <a onClick="window.location.href='history.php?clearSearch=1';" href="javascript:void(0)"><i class="fa-solid fa-xmark"></i></a>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <!-- Date Box End -->
                                        </div>

                                    </div>
                                    <?php if ($accessHistoryXclPdfPermission['type_id'] == 1 && mysqli_num_rows($historyQry) > 0) { ?>
                                        <div class="col-md-6 expStrdt d-flex justify-content-end align-items-end">
                                            <div class="d-flex justify-content-end align-items-center">
                                                <!-- <div class="chkStore">
                                        <a href="javascript:void(0)">
                                            <img src="Assets/icons/history-stock.svg" alt="history stock">
                                        </a>
                                    </div> -->
                                                <div class="chkStore">
                                                    <a href="history_excel.php" target="_blank">
                                                        <img src="Assets/icons/stock-xcl.svg" alt="Stock Xcl">
                                                    </a>
                                                </div>


                                                <div class="chkStore">
                                                    <a href="javascript:void(0)" target="_blank" data-bs-toggle="modal" onclick="showOrdersHistory();">
                                                        <img src="Assets/icons/stock-pdf.svg" alt="Stock PDF">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div><!-- /.row -->

                                <div class="collapse" id="collapseSearch">
                                    <!-- <div class="mt-4 d-flex gap-2 res__search__box"> 
                                    <div class="hstDate border-0 p-0">
                                        <input type="text" size="10" class="datepicker" placeholder="<?php echo showOtherLangText('From date') ?>" name="fromDate" autocomplete="off" value="<?php echo isset($_SESSION['fromDate']) ? $_SESSION['fromDate'] : $_GET['fromDate']; ?>">
                                        <span>-</span>
                                        <input type="text" size="10" class="datepicker" placeholder="<?php echo showOtherLangText('To date') ?>" name="toDate" autocomplete="off" value="<?php echo isset($_SESSION['toDate']) ? $_SESSION['toDate'] : $_GET['toDate']; ?>">
                                    </div>
                                    <div class="reloadBtn date-box-search m-0">
                                        <a href="javascript:void(0)"><i class="fa-solid fa-arrows-rotate"></i></a>
                                    </div>
                                    <?php if ($cond != '') { ?>
                                        <div class="reloadBtn m-0">
                                            <a onClick="window.location.href='history.php?clearSearch=1';" href="javascript:void(0)"><i class="fa-solid fa-xmark"></i></a>
                                        </div>
                                    <?php } ?>
                                </div> -->
                                </div>

                                <div class="collapse" id="collapseExample">
                                    <div class="mt-4 d-flex gap-2 res__search__box">
                                        <div class="hstDate p-0 border-0">
                                            <input type="text" size="10" class="datepicker"
                                                placeholder="15/01/2023" name="fromDateMobile" id="fromDateMobile"
                                                autocomplete="off" value="<?php echo isset($_SESSION['fromDate']) ? $_SESSION['fromDate'] : $_GET['fromDateMobile']; ?>">
                                            <span>-</span>
                                            <input type="text" size="10" class="datepicker"
                                                placeholder="15/02/2023" name="toDateMobile" id="toDateMobile"
                                                autocomplete="off" value="<?php echo isset($_SESSION['toDate']) ? $_SESSION['toDate'] : $_GET['toDateMobile']; ?>">
                                        </div>
                                        <div class="reloadBtn m-0">
                                            <a onClick="document.frm.submit();" href="javascript:void(0)"><i
                                                    class="fa-solid fa-arrows-rotate"></i></a>
                                        </div>
                                        <div class="reloadBtn m-0">
                                            <a
                                                href="history.php?clearSearch=1"><i class="fa-solid fa-xmark"></i></a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                        <!-- Mobile Date Box Start -->
                        <div class="container mb-hisDate">
                            <div class="date-flx"></div>
                        </div>
                        <!-- Mobile Date Box End -->

                        <?php if ($issueInTotal > 0 || $issueOutTotal > 0) { ?>

                            <div class="container mb-hisDtl">
                                <div class="row">

                                    <?php if (isset($issueInTotal) && $issueInTotal > 0) { ?>
                                        <div class="col-md-5 is-Incol">
                                            <p class="gr-Out">Issue in</p>
                                            <p class="ttlAmount"><?php

                                                                    if (isset($issueInTotal)) {
                                                                        $checkIfPermissionToNewOrderSec = checkIfPermissionToNewOrderSec($_SESSION['designation_id'], $_SESSION['accountId']);


                                                                        if (mysqli_num_rows($checkIfPermissionToNewOrderSec) > 0) {
                                                                            //$issuedOut = showPrice($issuedInOutArr[1], $getDefCurDet['curCode']);
                                                                            echo showPrice($issueInTotal, $getDefCurDet['curCode']);
                                                                        } else {
                                                                            echo '0' . ' ' . $getDefCurDet['curCode'];
                                                                        }
                                                                    } else {
                                                                        echo '0' . ' ' . $getDefCurDet['curCode'];
                                                                    }

                                                                    ?></p>
                                        </div>
                                    <?php } ?>


                                    <?php if (isset($issueOutTotal) && $issueOutTotal > 0) { ?>
                                        <div class="col-md-5 is-Outcol">
                                            <p class="rd-In">Issue Out</p>
                                            <p class="ttlAmount-rec"><?php

                                                                        $checkIfPermissionToNewReqSec = checkIfPermissionToNewReqSec($_SESSION['designation_id'], $_SESSION['accountId']);

                                                                        if (mysqli_num_rows($checkIfPermissionToNewReqSec) > 0) {
                                                                            echo isset($issueOutTotal) ? showPrice($issueOutTotal, $getDefCurDet['curCode']) : 0;
                                                                        } else {
                                                                            echo '0' . ' ' . $getDefCurDet['curCode'];
                                                                        }



                                                                        ?></p>
                                        </div>
                                    <?php } ?>

                                    <?php if ($issueInTotal > 0) { ?>
                                        <div class="col-md-2 maxBtn">
                                            <a href="javascript:void(0)" class="maxLink">
                                                <img src="Assets/icons/maximize.svg" alt="Maximize">
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>

                        <?php
                        if (isset($_GET['delOrderId'])) {
                        ?><br>

                            <div class="hbrw-DelHstry">
                                <form method="post" action="" autocomplete="off">
                                    <input type="hidden" name="delOrderId" value="<?php echo $_GET['delOrderId']; ?>" />
                                    <div class="row g-3 pb-3">
                                        <div class="col-sm-6">
                                            <div class="row g-0 p-0 justify-content-center justify-content-sm-start align-items-center">
                                                <label for="password" class="col-6 col-sm-5 col-md-4 text-center"><?php echo showOtherLangText('Account Password') ?>:</label>
                                                <div class="col">
                                                    <input type="password" value="" placeholder="" class="form-control bg-white border py-3 px-3" id="password" name="password" style="border-radius:1rem;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-primary"><?php echo showOtherLangText('Delete Now') ?></button>
                                                <button type="button" onclick="location.href='history.php'" class="btn btn-primary"><?php echo showOtherLangText('Cancel') ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php }


                        if (mysqli_num_rows($historyQry) > 0) {

                            $classNameForHeadingSec = 0;

                            if ((!isset($_GET['ordType']) || !$_GET['ordType'] || ($_GET['ordType'] == 1)) && $issueInTotal > 0) {
                                $classNameForHeadingSec++;
                            }

                            if ((!isset($_GET['ordType']) || !$_GET['ordType'] || ($_GET['ordType'] == 2)) && $issueOutTotal > 0) {
                                $classNameForHeadingSec++;
                            }

                            if (!isset($_GET['ordType']) || !$_GET['ordType'] || ($_GET['ordType'] == 3)) {
                                $variancesPosTot = 0;
                                $variancesNevTot = 0;
                                $varaincesVal = 0;

                                //variance starts here
                                // if ($_SESSION['getVals']['ordType'] == '' || $_SESSION['getVals']['ordType'] == 3) {

                                $varDataAvl = false;
                                $sqlSet = " SELECT od.* FROM tbl_order_details od
                                    INNER JOIN tbl_orders o
                                        ON(o.id=od.ordId) AND o.account_id=od.account_Id
                                    WHERE o.ordType = '3' AND o.status = '2' AND o.account_id = '" . $_SESSION['accountId'] . "' " . $condWithoutGroup . " ";
                                $resultSet = mysqli_query($con, $sqlSet);
                                while ($resRow = mysqli_fetch_array($resultSet)) {

                                    $varDataAvl = true;
                                    $varaincesVal = $resRow['qtyReceived'] - $resRow['qty'];

                                    if ($varaincesVal > 0) {
                                        $variancesPosTot += ($varaincesVal * $resRow['stockPrice']);
                                    } elseif ($varaincesVal < 0) {
                                        $variancesNevTot += ($varaincesVal * $resRow['stockPrice']);
                                    }
                                }

                                if ($varDataAvl) {
                                    $classNameForHeadingSec++;
                                }
                            }

                            if (!isset($_GET['ordType']) || !$_GET['ordType'] || ($_GET['ordType'] == 4)) {

                                $sqlSet = " SELECT SUM(ordAmt) totConvertedAmt FROM  tbl_orders o  WHERE ordType = '4' 
                AND status = '2' AND account_id = '" . $_SESSION['accountId'] . "' " . $condWithoutGroup . " GROUP BY ordType ";

                                $resultSet = mysqli_query($con, $sqlSet);
                                $convrtedRow = mysqli_fetch_array($resultSet);

                                if ($convrtedRow['totConvertedAmt'] > 0) {
                                    $classNameForHeadingSec++;
                                }
                            }

                            $classArr = [1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four'];

                            $classNameForHeading = '';
                            $classNameForHeadingOnActiveIssueIN = '';

                            if ($classNameForHeadingSec) {
                                $classNameForHeading = $classArr[$classNameForHeadingSec] . 'Item';
                                $classNameForHeadingOnActiveIssueIN = $classArr[$classNameForHeadingSec] . 'Item';
                            }

                            $sql = " SELECT od.currencyId, c.curCode, o.ordCurAmt AS totalOrdCurAmt, o.paymentStatus FROM tbl_order_details od
                                                        INNER JOIN tbl_orders o 
                                                            ON(o.id=od.ordId) AND o.account_id=od.account_Id
                                                        INNER JOIN tbl_currency c
                                                            ON(od.currencyId=c.id) AND od.account_id=c.account_Id
                                                        WHERE o.status = '2' AND o.ordCurAmt>0 AND o.account_id = '" . $_SESSION['accountId'] . "' " . $cond1 . "";
                            $resultCurQry = mysqli_query($con, $sql);
                            $curCount = mysqli_num_rows($resultCurQry);







                        ?>

                            <div class="container detailPrice">
                                <div class="tab-mbDtl">
                                    <a href="javascript:void(0)" class="tab-revLnk"><i class="fa-solid fa-arrow-left"></i></a>
                                </div>
                                <div class="scroller pb-5 pb-sm-0">
                                    <div class="row align-items-start issueDtl_accntDtl_main">
                                        <div class="issueDtl px-xl-4 px-xxl-5 flex-wrap d-block">
                                            <div class="overflow-x-auto">
                                                <div class="d-flex w-100 flex-wrap flex-md-nowrap jsPricebox <?php echo $classNameForHeading; ?>">

                                                    <?php if ((!isset($_GET['ordType']) || !$_GET['ordType'] || ($_GET['ordType'] == 1)) && $issueInTotal > 0) { ?>
                                                        <div class="issueIn">
                                                            <!--  -->
                                                            <!-- <div class="dspBlk">
                                                            </div> -->
                                                            <div class="paidIsue d-flex align-items-end">
                                                                <div class="col-md-3">
                                                                    <p class="pdStatus"><?php echo showOtherLangText('Paid'); ?></p>
                                                                    <p class="pendStatus"><?php echo showOtherLangText('Pending'); ?></p>
                                                                </div>
                                                                <div class="flex-grow-1 text-center" style="min-width: max-content;">
                                                                    <p class="gr-Out px-2"><?php echo showOtherLangText('Issued In'); ?></p>
                                                                    <p class="ttlAmount px-2"><?php echo showprice($issueInTotal, $getDefCurDet['curCode']); ?></p>
                                                                    <p class="pdAmount px-2"><?php echo ($issuedInOutPaidArr[1][1] > 0) ? showPrice($issuedInOutPaidArr[1][1], $getDefCurDet['curCode']) : '0'; ?></p>
                                                                    <p class="pendAmount px-2"><?php echo ($issuedInOutPendingArr[1][0] > 0) ? showPrice($issuedInOutPendingArr[1][0], $getDefCurDet['curCode']) : '0'; ?></p>
                                                                </div>
                                                            </div>
                                                            <?php

                                                            if ($curCount > 0) { ?>
                                                                <!-- added on 31-7-24 -->
                                                                <div class="usdCurr col-4 text-center">
                                                                    <div class="paidIsue d-flex">
                                                                        <div class="col-md-3">
                                                                            <p class="pdStatus"><?php echo showOtherLangText('Paid'); ?></p>
                                                                            <p class="pendStatus"><?php echo showOtherLangText('Pending'); ?></p>
                                                                        </div>
                                                                        <div class="col-md-9 text-center fw-normal">
                                                                            <p class="usd-In px-3">$</p>
                                                                            <p class="ttlAmount px-3"><?php echo showprice($issueInTotal_defaultcurrency, $getDefCurDet['curCode']); ?></p>
                                                                            <p class="pdAmount px-3"><?php echo showprice($issueInTotal_defaultcurrency_paid, $getDefCurDet['curCode']); ?></p>
                                                                            <p class="pendAmount px-3"><?php echo showprice($issueInTotal_defaultcurrency_pending, $getDefCurDet['curCode']); ?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                            }

                                                            $otherCurrRowArr = [];
                                                            $otherCurrTotalValueArr = [];
                                                            $otherCurrPendingTotalValueArr = [];
                                                            while ($otherCurrRows = mysqli_fetch_array($resultCurQry)) {
                                                                if ($otherCurrRows['currencyId'] > 0 && ($otherCurrRows['paymentStatus'] == 0 || $otherCurrRows['paymentStatus'] == 2)) {

                                                                    // Total rows of other currency pending amount
                                                                    $otherCurrRowArr[$otherCurrRows['currencyId']] = $otherCurrRows;

                                                                    // Sum of total values of pending amount 
                                                                    $totalSumAmt = $otherCurrRows['totalOrdCurAmt'];
                                                                    $otherCurrPendingTotalValueArr[$otherCurrRows['currencyId']] += $totalSumAmt;
                                                                } elseif ($otherCurrRows['currencyId'] > 0 && $otherCurrRows['paymentStatus'] == 1) {

                                                                    // Total rows of other currency paid amount
                                                                    $otherCurrRowArr[$otherCurrRows['currencyId']] = $otherCurrRows;

                                                                    // Sum of total values of paid amount 
                                                                    $totalSumAmt = $otherCurrRows['totalOrdCurAmt'];
                                                                    $otherCurrPaidTotalValueArr[$otherCurrRows['currencyId']] += $totalSumAmt;
                                                                }

                                                                if ($otherCurrRows['currencyId'] > 0) {

                                                                    // Total rows of other currency paid amount
                                                                    $otherCurrRowArr[$otherCurrRows['currencyId']] = $otherCurrRows;

                                                                    // Sum of total values of paid amount 
                                                                    $totalSumAmt = $otherCurrRows['totalOrdCurAmt'];
                                                                    $otherCurrTotalValueArr[$otherCurrRows['currencyId']] += $totalSumAmt;
                                                                }
                                                            }

                                                            $curCount = count(otherCurrRowArr);
                                                            $curCount++;
                                                            if ($curCount > 0) {
                                                                $classNameForHeadingOnActiveIssueIN .= 'Cur' . $classArr[$curCount];
                                                            }


                                                            foreach ($otherCurrRowArr as $otherCurrRow) {  ?>


                                                                <div class="usdCurr col-4 text-center">
                                                                    <div class="paidIsue d-flex">
                                                                        <div class="col-md-3">
                                                                            <p class="pdStatus"><?php echo showOtherLangText('Paid'); ?></p>
                                                                            <p class="pendStatus"><?php echo showOtherLangText('Pending'); ?></p>
                                                                        </div>
                                                                        <div class="col-md-9 text-center fw-normal">
                                                                            <p class="usd-In px-3"><?php echo ($otherCurrRow['curCode']); ?></p>
                                                                            <p class="ttlAmount px-3"><?php echo showOtherCur($otherCurrTotalValueArr[$otherCurrRow['currencyId']], $otherCurrRow['currencyId']); ?></p>
                                                                            <p class="pdAmount px-3"><?php echo $otherCurrPaidTotalValueArr[$otherCurrRow['currencyId']] != '' ? showOtherCur($otherCurrPaidTotalValueArr[$otherCurrRow['currencyId']], $otherCurrRow['currencyId']) : '0 &nbsp;'; ?></p>
                                                                            <p class="pendAmount px-3"><?php echo showOtherCur($otherCurrPendingTotalValueArr[$otherCurrRow['currencyId']], $otherCurrRow['currencyId']); ?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            <?php } ?>


                                                            <div style="padding-left:.5rem;">
                                                                <a class="<?php
                                                                            if (mysqli_num_rows($resultCurQry) == 0) {
                                                                                echo 'toggle-currency-btn-disabled';
                                                                            } else {
                                                                                echo 'toggle-currency-btn';
                                                                            }
                                                                            ?>">$/¥</a>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    } //end issue in
                                                    ?>
                                                    <?php if ((!isset($_GET['ordType']) || !$_GET['ordType'] || ($_GET['ordType'] == 2)) && $issueOutTotal > 0) { ?>
                                                        <div class="issueOut">
                                                            <div class="recIsue d-flex">
                                                                <div class="col-md-5">
                                                                    <p class="recStatus"><?php echo showOtherLangText('Received'); ?></p>
                                                                    <p class="pendStatus"><?php echo showOtherLangText('Pending'); ?></p>
                                                                </div>
                                                                <div class="col-md-7 text-center">
                                                                    <p class="rd-In px-3"><?php echo showOtherLangText('Issued Out'); ?></p>
                                                                    <p class="ttlAmount-rec px-3"><?php echo showprice($issueOutTotal, $getDefCurDet['curCode']); ?></p>
                                                                    <p class="pdAmount-rec px-3"> <?php echo ($issuedInOutPaidArr[2][1]) ? showPrice($issuedInOutPaidArr[2][1], $getDefCurDet['curCode']) : '0'; ?></p>
                                                                    <p class="pendAmount-rec px-3"><?php echo ($issuedInOutPendingArr[2][0] > 0) ? showPrice($issuedInOutPendingArr[2][0], $getDefCurDet['curCode']) : '0'; ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    } //end issue out

                                                    if (!isset($_GET['ordType']) || !$_GET['ordType'] || ($_GET['ordType'] == 3)) {

                                                    ?>
                                                        <?php if ($varDataAvl): ?>
                                                            <div class="Variance text-center">
                                                                <p class="varDtl px-2"><?php echo showOtherLangText('Variances'); ?></p>
                                                                <p class="varValue px-2"><?php echo getPriceWithCur($variancesPosTot, $getDefCurDet['curCode']) ?></p>
                                                                <p class="varDif px-2"><?php echo  getPriceWithCur($variancesNevTot, $getDefCurDet['curCode']); ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php
                                                    } //end variance
                                                    if (!isset($_GET['ordType']) || !$_GET['ordType'] || ($_GET['ordType'] == 4)) {


                                                    ?>
                                                        <?php if ($convrtedRow['totConvertedAmt'] > 0): ?>
                                                            <div class="Variance text-center">
                                                                <p class="varDtl px-2"><?php echo showOtherLangText('Converted'); ?></p>
                                                                <p class="varValue px-2"><?php echo getPriceWithCur($convrtedRow['totConvertedAmt'], $getDefCurDet['curCode']) ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php } ?>

                                                </div>
                                            </div><!-- /.d-flex-->
                                        </div><!-- /.overflow-x-->

                                        <?php
                                        if ($accessHistoryAccountsPermission['type_id'] == 1 && isset($historyUserFilterFields) && in_array(17, $historyUserFilterFields)) {

                                            $sqlSet = " SELECT c.curCode, a.* FROM  tbl_accounts a 
                                            INNER JOIN tbl_currency c 
                                                ON( c.id=a.currencyId) AND c.account_Id=a.account_Id
                                            WHERE a.account_id = '" . $_SESSION['accountId'] . "' AND a.id IN(" . $bankAccountIdsStr . ") ";
                                            $result = mysqli_query($con, $sqlSet);

                                            $totalAccounts = mysqli_num_rows($result);
                                            if ($totalAccounts > 0) {
                                        ?>
                                                <div class="accntDtl">
                                                    <p class="accHead text-center"><?php echo showOtherLangText('Accounts'); ?></p>
                                                <?php
                                            }


                                            while ($resultRow = mysqli_fetch_array($result)) {

                                                $curCode = $resultRow['curCode'];
                                                $balanceAmt = round($resultRow['balanceAmt'], 4);
                                                ?>

                                                    <div class="d-flex gap-2 py-2 w-100">
                                                        <p class="w-50"><?php echo $resultRow['accountName'] ?></p>

                                                        <p class="posBlnc w-50"><?php echo number_format($balanceAmt); ?>
                                                            <?php echo $curCode; ?></p>
                                                    </div>

                                                <?php
                                            }

                                            if ($totalAccounts > 0) {
                                                echo '</div>';
                                            } else {
                                                //echo '<div class="accntDtl">&nbsp;</div>';
                                            }
                                                ?>

                                            <?php }  ?>

                                                </div>
                                    </div>
                                </div>




                                <div id="resFilterBox" class="container position-relative hstTbl-head px-0 res__filter__box <?php echo count($historyUserFilterFields) < 11 ? 'hstTbl-head1' : ''; ?>">

                                    <!-- Item Table Head Start -->
                                    <div class=" mt-2 itmTable position-relative <?php echo count($historyUserFilterFields) < 11 ? 'itmTable1' : ''; ?>">
                                        <button type="button" id="closeResFilterBox" class="btn btn-primary d-flex justify-content-center align-items-center d-lg-none back__btn p-0">
                                            <svg width="15" height="10" viewBox="0 0 15 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2 4.68552L1.2969 3.97443L0.577728 4.68552L1.2969 5.39662L2 4.68552ZM14 5.68552C14.5523 5.68552 15 5.23781 15 4.68552C15 4.13324 14.5523 3.68552 14 3.68552V5.68552ZM5.2969 0.0193784L1.2969 3.97443L2.7031 5.39662L6.7031 1.44156L5.2969 0.0193784ZM1.2969 5.39662L5.2969 9.35167L6.7031 7.92949L2.7031 3.97443L1.2969 5.39662ZM2 5.68552H14V3.68552H2V5.68552Z" fill="white" />
                                            </svg>
                                        </button>


                                        <div class="align-items-center d-flex dropdnbtns fgcolSize<?php echo $col_class_one; ?> lgcolSize<?php echo $col_class_two; ?>">
                                            <div class="numRef numRef1 align-items-center colSize<?php echo $col_class_one; ?>">
                                                <div class="tb-bdy srHisclm">
                                                    <p class="serial"><?php echo mysqli_num_rows($historyQry) > 0 ? mysqli_num_rows($historyQry) : ''; ?></p>
                                                </div>

                                                <?php if (isset($historyUserFilterFields) && in_array(1, $historyUserFilterFields)) { ?>

                                                    <div class="tb-bdy numItmclm">
                                                        <div class="d-flex align-items-center">
                                                            <p><?php echo showOtherLangText('Number'); ?></p>
                                                            <span class="dblArrow">
                                                                <a onclick="sortTableByColumn('.newHistoryTask', '.hisOrd','asc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                                <a onclick="sortTableByColumn('.newHistoryTask', '.hisOrd','desc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php } ?>




                                                <?php if (isset($historyUserFilterFields) && in_array(2, $historyUserFilterFields)) { ?>

                                                    <div class="tb-bdy date-dpd">
                                                        <div class="d-flex align-items-center" style=" background:inherit">
                                                            <div class="dropdown d-flex position-relative">
                                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <span id="dateTypeText"><?php echo showOtherLangText('Date'); ?></span> <i class="fa-solid fa-angle-down"></i>
                                                                </a>

                                                                <?php echo $dateTypeOptions; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>


                                                <?php if (isset($historyUserFilterFields) && in_array(3, $historyUserFilterFields)) { ?>

                                                    <div class="tb-bdy user-dpd">
                                                        <div class="d-flex align-items-center" style="background:inherit;">
                                                            <div class="dropdown d-flex position-relative">
                                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <span id="userText"><?php echo showOtherLangText('User'); ?></span> <i class="fa-solid fa-angle-down"></i>
                                                                </a>
                                                                <?php echo $userOptions; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>



                                                <?php if (isset($historyUserFilterFields) && in_array(4, $historyUserFilterFields)) { ?>

                                                    <div class="tb-bdy type-dpd">
                                                        <div class="d-flex align-items-center" style=" background:inherit">
                                                            <div class="dropdown d-flex position-relative w-100">
                                                                <a class="dropdown-toggle body3 w-100" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <span id="TypeText"><?php echo showOtherLangText('Type'); ?></span> <i class="fa-solid fa-angle-down"></i>
                                                                </a>
                                                                <?php echo $typeOptions; ?>
                                                            </div>
                                                        </div>

                                                    </div>
                                                <?php } ?>




                                                <?php if (isset($historyUserFilterFields) && in_array(7, $historyUserFilterFields) || !isset($colsArr[7])) { ?>

                                                    <div class="tb-bdy refer-to-dpd">
                                                        <!--  style="width:100px;min-width:100px" -->
                                                        <div class="d-flex align-items-center">
                                                            <div class="dropdown d-flex position-relative w-100 lg__7">
                                                                <a class="dropdown-toggle body3 w-100" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <span id="refertotext"><?php echo showOtherLangText('Refer To'); ?></span> <i class="fa-solid fa-angle-down"></i>
                                                                </a>
                                                                <?php echo $suppMemStoreOptions; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                            </div>




                                            <?php if (isset($historyUserFilterFields) && in_array(8, $historyUserFilterFields) || !isset($colsArr[8])) { ?>

                                                <div class="tb-bdy hisValclm">
                                                    <div class="d-flex align-items-center">
                                                        <p><?php echo showOtherLangText('Supplier inv no'); ?></p>
                                                    </div>
                                                </div>
                                            <?php } ?>



                                            <?php if (isset($historyUserFilterFields) && in_array(10, $historyUserFilterFields)) { ?>

                                                <div class="tb-bdy hisValclm">
                                                    <div class="d-flex align-items-center">
                                                        <p><?php echo showOtherLangText('Value'); ?></p>
                                                    </div>
                                                </div>
                                            <?php } ?>



                                            <div class="stsHiscol d-flex align-items-center colSize<?php echo $col_class_two; ?>">

                                                <?php if (isset($historyUserFilterFields) && in_array(14, $historyUserFilterFields) || !isset($colsArr[14])) { ?>

                                                    <div class="tb-bdy hisStatusclm sm__ml">
                                                        <div class="d-flex align-items-center">
                                                            <div style="width: 80%;" class="dropdown d-flex align-items-center  position-relative">
                                                                <a class="dropdown-toggle body3 status" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <span id="statusText">
                                                                        <?php echo showOtherLangText('Status'); ?>
                                                                    </span>
                                                                    <i class="fa-solid fa-angle-down"></i>
                                                                </a>
                                                                <?php echo $statusTypeOptions; ?>
                                                                <span class="dblArrow d-none">
                                                                    <a onclick="sortTableByColumn('.newHistoryTask', '.his-pendStatus','asc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                                    <a onclick="sortTableByColumn('.newHistoryTask', '.his-pendStatus','desc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>




                                                <?php if (isset($historyUserFilterFields) && in_array(15, $historyUserFilterFields) || !isset($colsArr[15])) { ?>

                                                    <div class="tb-bdy hisStatusclm d-none d-lg-block">
                                                        <div class="d-flex align-items-center justify-content-between" style=" min-width: fit-content !important;">
                                                            <p><?php echo showOtherLangText('Payment No.'); ?></p>
                                                        </div>
                                                    </div>
                                                <?php } ?>



                                                <?php if (isset($historyUserFilterFields) && in_array(16, $historyUserFilterFields) || !isset($colsArr[16])) { ?>

                                                    <div class="tb-bdy hisStatusclm d-none d-lg-block">
                                                        <div class="d-flex align-items-center justify-content-between" style=" min-width: fit-content !important;">
                                                            <p style="color: #666c85; font-weight:600; "><?php echo showOtherLangText('Inv No.'); ?></p>
                                                        </div>
                                                    </div>
                                                <?php } ?>



                                                <?php if (isset($historyUserFilterFields) && in_array(17, $historyUserFilterFields) || !isset($colsArr[17])) { ?>

                                                    <div class="tb-bdy hisAcntclm" style="padding-left: 0px;">

                                                        <div class="d-flex align-items-center">
                                                            <div class="dropdown d-flex align-items-center w-100 position-relative">
                                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <span id="accountTxt">
                                                                        <span class="d-none d-lg-inline-block"><?php echo showOtherLangText('Account'); ?></span>
                                                                        <span class="d-lg-none"><?php echo showOtherLangText('Account'); ?></span>
                                                                    </span>
                                                                    <i class="fa-solid fa-angle-down"></i>
                                                                </a>
                                                                <?php echo $accountOptions; ?>
                                                            </div>
                                                        </div>

                                                    </div>
                                                <?php } ?>
                                            </div>


                                            <div class="d-flex align-items-center justify-content-lg-end justify-content-between his-Paybtn">
                                                <div class="label__box action-column-p">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <p class="d-none d-lg-block"><?php echo showOtherLangText('Action'); ?></p>
                                                        <p class="d-lg-none fs-6"><?php echo showOtherLangText('Filter'); ?></p>
                                                    </div>
                                                </div>
                                                <!-- check box model icon -->
                                                <div class="chkStore">
                                                    <a class="dropdown-item p-0" style="width: fit-content;" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#checkbox_module">
                                                        <img src="Assets/icons/chkColumn.svg" alt="Check Column">
                                                    </a>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- <div class="align-items-center mbTask">
                                <a href="javascript:void(0)" class="statusLink mb-hisLink"><i class="fa-solid fa-angle-down"></i></a>
                            </div> -->
                                    </div>
                                    <!-- Item Table Head End -->
                                </div>

                            <?php } else {
                            echo '<div id="resFilterBox" class="container" style="text-align:center;min-height:100px;">No records found.</div>';
                        } ?>
                            <!-- hide main divs if no records found -->
                    </section>


                    <section class="hisTblbody <?php echo count($historyUserFilterFields) < 11 ? 'hisTblbody1' : ''; ?>">
                        <div id="boxscroll">
                            <div class="container position-relative hstTbl-bd cntTableData">
                                <!-- Item Table Body Start -->
                                <?php
                                $variances = [];
                                $x = 0;
                                $varianceAmtText = '';
                                while ($orderRow = mysqli_fetch_array($historyQry)) {

                                    //this is temporary fix need to do with function or main query above
                                    if ($orderRow['ordType'] == 1 || $orderRow['ordType'] == 2) {

                                        if ($orderRow['ordType'] == 1) {
                                            $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '" . $_SESSION['designation_id'] . "' AND type = 'order_supplier' AND account_id = '" . $_SESSION['accountId'] . "' and type_id = '" . $orderRow['supplierId'] . "' and designation_section_permission_id=1 ";
                                        } else {
                                            $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '" . $_SESSION['designation_id'] . "' AND type = 'member' AND account_id = '" . $_SESSION['accountId'] . "' and type_id = '" . $orderRow['recMemberId'] . "' and designation_section_permission_id=2 ";
                                        }

                                        $checkSubPerQry = mysqli_query($con, $sql);

                                        if (mysqli_num_rows($checkSubPerQry) < 1) {
                                            continue; //exclude this order as user don't have its permission
                                        }
                                    } //end temp query

                                    $x++;
                                    $variancesTotAmt = 0;
                                    if ($orderRow['suppName'] == '' && $orderRow['storeName'] == '') {
                                        $suppMemStoreId = $orderRow['deptUserName'];
                                    } elseif ($orderRow['deptUserName'] == '' && $orderRow['storeName'] == '') {
                                        $suppMemStoreId = $orderRow['suppName'];
                                    } else {
                                        $suppMemStoreId = $orderRow['storeName'];
                                    }

                                    if ($getTxtById == 'suppId') {
                                        $suppMemStoreId = $orderRow['suppName'];
                                    } elseif ($getTxtById == 'deptUserId') {
                                        $suppMemStoreId = $orderRow['deptUserName'];
                                    } elseif ($getTxtById == 'storeId') {
                                        $suppMemStoreId = $orderRow['storeName'];
                                    }

                                    // for date time filter
                                    if ($_GET['dateType'] == 1) {
                                        $dateType = ($orderRow['ordDateTime'] != '0000-00-00 00:00:00' ? date('d/m/y h:i', strtotime($orderRow['ordDateTime'])) : '');
                                    } elseif ($_GET['dateType'] == 2) {
                                        $dateType = ($orderRow['setDateTime'] != '0000-00-00 00:00:00' ? date('d/m/y h:i', strtotime($orderRow['setDateTime'])) : '');
                                    } elseif ($_GET['dateType'] == 3) {
                                        $dateType = ($orderRow['paymentDateTime'] != '0000-00-00 00:00:00' ? date('d/m/y h:i', strtotime($orderRow['paymentDateTime'])) : date('d/m/y h:i', strtotime($orderRow['setDateTime'])));
                                    } else {
                                        $dateType = ($orderRow['setDateTime'] != '0000-00-00 00:00:00' ? date('d/m/y h:i', strtotime($orderRow['setDateTime'])) : '');
                                    }
                                    $dateType .= "<br><span>" . $orderRow['sortingNo'] . "</span>";

                                    $userName = $orderRow['userName'];

                                    if ($orderRow['ordType'] == 1) {
                                        $ordType = showOtherLangText('Issued In');
                                    } elseif ($orderRow['ordType'] == 2) {
                                        $ordType = showOtherLangText('Issued Out');
                                    } elseif ($orderRow['ordType'] == 3) {

                                        if (!isset($variances[$orderRow['id']])) {
                                            $variances[$orderRow['id']] = getOrdItemVariancesAmt($orderRow['id']);
                                        }

                                        $variancesTotAmt = $variances[$orderRow['id']]['variancesTot'];

                                        $varPosAmt = $variances[$orderRow['id']]['varPosAmt'];
                                        $varNegAmt = $variances[$orderRow['id']]['varNegAmt'];
                                        //style="color:black"
                                        $varianceAmtText = '<span >' . getNumFormtPrice($varPosAmt, $getDefCurDet['curCode']) . '</span><br>';
                                        $varianceAmtText .= '<span style="color:#F37870;">' . getNumFormtPrice($varNegAmt, $getDefCurDet['curCode']) . '</span>';


                                        $ordType = showOtherLangText('Stock Take');
                                    } else {
                                        $ordType = showOtherLangText('Raw Item Convert');
                                    }


                                    $curDet =  getCurrencyDet($orderRow['ordCurId']);

                                    $paymentId = ($orderRow['paymentId']) ? $orderRow['paymentId'] : '';

                                    if ($orderRow['paymentStatus'] == 1 && $orderRow['ordType'] == 1) {
                                        $paymentStatus = '<strong style="color:#1C9FFF;">' . showOtherLangText('Paid') . '</strong>';
                                    } elseif ($orderRow['paymentStatus'] == 1 && $orderRow['ordType'] == 2) {
                                        $paymentStatus = '<strong style="color:#038F00;">' . showOtherLangText('Received') . '</strong>';
                                    } elseif ($orderRow['ordType'] == 3 || $orderRow['ordType'] == 4) {
                                        $paymentStatus = '<strong>&nbsp;</strong>';
                                    } elseif (($orderRow['ordType'] == 2 && $orderRow['receive_inv'] == 0) || $orderRow['ordType'] == 2 && $accessInvoicePermission['type_id'] == 0) {
                                        $paymentStatus = '<strong>&nbsp;</strong>';
                                    } elseif ($orderRow['ordType'] == 1 && $accessPaymentPermission['type_id'] == 0) {
                                        $paymentStatus = '<strong>&nbsp;</strong>';
                                    } else {
                                        $paymentStatus = '<strong style="color:#FC9C2C;">' . showOtherLangText('Pending') . '</strong>';
                                    }


                                    $InvoiceNumber = $orderRow['invNo'];

                                    $colsValArr =
                                        [
                                            1 => $orderRow['ordNumber'],
                                            2 => $dateType,
                                            3 => $userName,
                                            4 => $ordType,
                                            7 => $suppMemStoreId,
                                            8 => ($orderRow['ordType'] == 1) ? $InvoiceNumber : ' ',
                                            10 => ($orderRow['ordType'] == 3) ? $varianceAmtText : getNumFormtPrice($orderRow['ordAmt'], $getDefCurDet['curCode'])
                                                . '<br>' .
                                                ($orderRow['ordCurAmt'] > 0 ? showOtherCur($orderRow['ordCurAmt'], $curDet['id']) : ''),
                                            14 => $paymentStatus,
                                            15 => ($orderRow['paymentStatus'] > 0 ? setPaymentId($paymentId) : ''),
                                            16 => ($orderRow['ordType'] == 2) ? $InvoiceNumber : ' ',
                                            17 => ($orderRow['paymentStatus'] == 1 ? $orderRow['accountName'] : ''),

                                        ];



                                    if (isset($_GET['ordType'])) {
                                        switch ($_GET['ordType']) {

                                            case 1:

                                                unset($colsValArr[5]);
                                                unset($colsValArr[6]);
                                                unset($colsValArr[9]);
                                                unset($colsValArr[11]);
                                                unset($colsValArr[16]);
                                                break;

                                            case 2:

                                                unset($colsValArr[8]);
                                                unset($colsValArr[9]);
                                                unset($colsValArr[11]);
                                                unset($colsValArr[15]);
                                                break;

                                            case 3:

                                                unset($colsValArr[5]);
                                                unset($colsValArr[6]);
                                                unset($colsValArr[8]);
                                                unset($colsValArr[11]);
                                                unset($colsValArr[14]);
                                                unset($colsValArr[15]);
                                                unset($colsValArr[16]);
                                                unset($colsValArr[17]);
                                                unset($colsValArr[18]);
                                                break;


                                            case 4:

                                                unset($colsValArr[5]);
                                                unset($colsValArr[6]);
                                                unset($colsValArr[7]);
                                                unset($colsValArr[8]);
                                                unset($colsValArr[9]);
                                                unset($colsValArr[11]);
                                                unset($colsValArr[14]);
                                                unset($colsValArr[15]);
                                                unset($colsValArr[16]);
                                                unset($colsValArr[17]);
                                                unset($colsValArr[18]);
                                                break;
                                        }
                                    }

                                    if ($accessHistoryAccountsPermission['type_id'] == 0) {
                                        unset($colsValArr[14]);
                                        unset($colsValArr[15]);
                                        unset($colsValArr[16]);
                                        unset($colsValArr[17]);
                                    }

                                    if ($_GET['statusType'] == 1) {
                                        unset($colsValArr[5]);
                                        unset($colsValArr[6]);
                                        unset($colsValArr[9]);
                                        unset($colsValArr[11]);
                                        unset($colsValArr[16]);
                                    }

                                    if ($_GET['statusType'] == 2) {
                                        unset($colsValArr[8]);
                                        unset($colsValArr[9]);
                                        unset($colsValArr[11]);
                                        unset($colsValArr[15]);
                                    }

                                    // for panding payment
                                    if ($_GET['statusType'] == 3) {
                                        unset($colsValArr[11]);
                                        unset($colsValArr[15]);
                                        unset($colsValArr[16]);
                                        unset($colsValArr[17]);
                                    }

                                    if ($getTxtById == 'suppId') {
                                        unset($colsValArr[6]);
                                        unset($colsValArr[9]);
                                        unset($colsValArr[11]);
                                        unset($colsValArr[16]);
                                    }

                                    if ($getTxtById == 'deptUserId') {
                                        unset($colsValArr[8]);
                                        unset($colsValArr[9]);
                                        unset($colsValArr[11]);
                                        unset($colsValArr[15]);
                                    }
                                    // for store
                                    if ($getTxtById == 'storeId') {
                                        unset($colsValArr[5]);
                                        unset($colsValArr[6]);
                                        unset($colsValArr[8]);
                                        unset($colsValArr[11]);
                                        unset($colsValArr[14]);
                                        unset($colsValArr[15]);
                                        unset($colsValArr[16]);
                                        unset($colsValArr[17]);
                                        unset($colsValArr[18]);
                                    }

                                    if ($orderRow['ordType'] == 1) {
                                        $mclass = 'mb-hstryBareq';
                                    } elseif ($orderRow['ordType'] == 2) {
                                        $mclass = 'mb-hstryBarord';
                                    } elseif ($orderRow['ordType'] == 3) {
                                        $mclass = 'mb-hstryBarstk';
                                    } else {
                                        $mclass = 'mb-hstryBarstk';
                                    }
                                ?>


                                    <div class="hisTask newHistoryTask <?php if ($x > 1) {
                                                                            echo 'mt-2';
                                                                        } ?>">
                                        <div class="<?php echo $mclass; ?>">&nbsp;</div>
                                        <div class="align-items-center itmBody fgcolSize<?php echo $col_class_one; ?> lgcolSize<?php echo $col_class_two; ?>">
                                            <div class="numRef numRef2 align-items-center colSize<?php echo $col_class_one; ?>">
                                                <div class="tb-bdy srHisclm" style="min-width: fit-content;">
                                                    <p class="serial"><?php echo $x; ?></p>
                                                </div>

                                                <?php if (isset($historyUserFilterFields) && in_array(1, $historyUserFilterFields)) { ?>

                                                    <div class="tb-bdy numItmclm order_num">
                                                        <p class="hisOrd">#<?php echo $orderRow['ordNumber']; ?></p>
                                                    </div>
                                                <?php } ?>

                                                <?php if (isset($historyUserFilterFields) && in_array(2, $historyUserFilterFields)) { ?>

                                                    <div class="tb-bdy hisDateclm date">
                                                        <p class="fstDt"><?php echo $dateType; ?></p>
                                                    </div>
                                                <?php } ?>



                                                <?php if (isset($historyUserFilterFields) && in_array(3, $historyUserFilterFields)) { ?>

                                                    <div class="tb-bdy hisDateclm"><?php echo $userName; ?> </div>
                                                <?php } ?>

                                                <?php
                                                if ($orderRow['ordType'] == 1) {
                                                    $class = 'reqBar-gr';
                                                    $class1 = 'hisReq-typ';
                                                } elseif ($orderRow['ordType'] == 2) {
                                                    $class = 'ordBar-rd';
                                                    $class1 = 'hisOrd-typ';
                                                } elseif ($orderRow['ordType'] == 3) {
                                                    $class = 'stckBar-bl';
                                                    $class1 = 'hisStk-typ';
                                                } else {
                                                    $class = 'stckBar-bl';
                                                    $class1 = 'hisStk-typ';
                                                }
                                                ?>

                                                <?php if (isset($historyUserFilterFields) && in_array(4, $historyUserFilterFields)) { ?>
                                                    <div class="tb-bdy hisTypclm">
                                                        <div class=" d-flex align-items-center <?php echo $class1; ?>">
                                                            <div class="<?php echo $class; ?>">&nbsp;</div>
                                                            <p class="col"><?php echo $ordType; ?></p>
                                                        </div>
                                                    </div>
                                                <?php } ?>


                                                <?php if (isset($historyUserFilterFields) && in_array(7, $historyUserFilterFields) || !isset($colsArr[7])) { ?>

                                                    <div class="tb-bdy hisRefrclm price__box refer">
                                                        <p class="refTomember"><?php echo $suppMemStoreId; ?></p>
                                                    </div>
                                                <?php } ?>

                                            </div>

                                            <?php if (isset($historyUserFilterFields) && in_array(8, $historyUserFilterFields)) { ?>
                                                <div class="tb-bdy hisValclm inv__box">
                                                    <p class="dolValcurr"><?php echo $orderRow['ordType'] == 1 ? $InvoiceNumber : ''; ?></p>
                                                </div>
                                            <?php } ?>



                                            <?php if (isset($historyUserFilterFields) && in_array(10, $historyUserFilterFields)) { ?>
                                                <div class="tb-bdy hisValclm price__box">
                                                    <p class="dolValcurr"><?php echo ($orderRow['ordType'] == 3) ? $varianceAmtText : getNumFormtPrice($orderRow['ordAmt'], $getDefCurDet['curCode'])
                                                                                . '<br>' .
                                                                                ($orderRow['ordCurAmt'] > 0 ? showOtherCur($orderRow['ordCurAmt'], $curDet['id']) : ''); ?></p>
                                                </div>
                                            <?php } ?>

                                            <div class="stsHiscol d-flex align-items-center lg_w_35 colSize<?php echo $col_class_two; ?>">

                                                <?php if (isset($historyUserFilterFields) && in_array(14, $historyUserFilterFields)) { ?>
                                                    <div class="tb-bdy flex hisStatusclm ps-0 lg_pr_8">
                                                        <p class="his-pendStatus"><?php echo $paymentStatus; ?></p>
                                                    </div>
                                                <?php } ?>


                                                <?php if (isset($historyUserFilterFields) && in_array(15, $historyUserFilterFields)) { ?>
                                                    <div class="tb-bdy flex hisStatusclm lg_pl_0">
                                                        <p class="his-pendStatus"><?php echo ($orderRow['paymentStatus'] > 0 ? setPaymentId($paymentId) : ''); ?></p>
                                                    </div>
                                                <?php } ?>



                                                <?php if (isset($historyUserFilterFields) && in_array(16, $historyUserFilterFields)) { ?>

                                                    <div class="tb-bdy flex hisStatusclm lg_pl_0">
                                                        <p class="his-pendStatus"><?php echo ($orderRow['ordType'] == 2) ? $InvoiceNumber : ' '; ?></p>
                                                    </div> <?php } ?>



                                                <?php if (isset($historyUserFilterFields) && in_array(17, $historyUserFilterFields)) { ?>

                                                    <div class="tb-bdy flex hisAcntclm">
                                                        <p class="hisAccount"><?php echo ($orderRow['paymentStatus'] == 1 ? $orderRow['accountName'] : ''); ?></p>
                                                    </div><?php } ?>


                                            </div>

                                            <div class="tb-bdy shrtHisclm">
                                                <div class="mb-Acntdetail">
                                                    <div class="tb-bdy">
                                                        <p>Account</p>
                                                        <p class="mb-Acntnum"></p>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2 align-items-center justify-content-end his-Paybtn">
                                                    <?php
                                                    if ($orderRow['ordType'] == 1) //order
                                                    {

                                                        if ($accessHistoryAccountsPermission['type_id'] == 1) {
                                                            if ($accessPaymentPermission['type_id'] == 1) {

                                                                getOrderPaymentLink($orderRow['id']);
                                                            } else {
                                                    ?><span style="width: 53%;">&nbsp;</span><?php
                                                                                            }
                                                                                        } else {
                                                                                                ?><span style="width: 53%;">&nbsp;</span><?php
                                                                                                                                        }
                                                                                                                                    } elseif ($orderRow['ordType'] == 2) {
                                                                                                                                        if ($accessHistoryAccountsPermission['type_id'] == 1) {
                                                                                                                                            if ($accessInvoicePermission['type_id'] == 1) {

                                                                                                                                                getrequisitionPaymentLink($orderRow['id']);
                                                                                                                                            } else {
                                                                                                                                            ?><span style="width: 66%;">&nbsp;</span><?php
                                                                                                                                                                                    }
                                                                                                                                                                                } else {
                                                                                                                                                                                        ?><span style="width: 66%;">&nbsp;</span><?php
                                                                                                                                                                                                                                }
                                                                                                                                                                                                                            } else {

                                                                                                                                                                                                                                echo '<div class="cnfrm" style=" border: none; background: transparent !important; box-shadow: none;"></div>';
                                                                                                                                                                                                                            }

                                                                                                                                                                                                                                    ?>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="doc-bx text-center d-flex justify-content-center align-items-center position-relative">
                                                            <a href="javascript:void(0)" class="dropdown-toggle runLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <!-- <span class="docMent"></span> -->
                                                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <rect x="5.04102" y="4" width="14" height="17" rx="2" stroke="#8C8FA7" stroke-width="2" />
                                                                    <path d="M9.04102 9H15.041" stroke="#8C8FA7" stroke-width="2" stroke-linecap="round" />
                                                                    <path d="M9.04102 13H15.041" stroke="#8C8FA7" stroke-width="2" stroke-linecap="round" />
                                                                    <path d="M9.04102 17H13.041" stroke="#8C8FA7" stroke-width="2" stroke-linecap="round" />
                                                                </svg>

                                                                <p class="btn2"><?php echo showOtherLangText('Documents'); ?> <i class="fa-solid fa-angle-down"></i>
                                                                </p>
                                                            </a>

                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="javascript:void(0)" onClick="return openPopup('<?php echo $orderRow['ordType']; ?>', '<?php echo $orderRow['id']; ?>')"><i class="far fa-square pe-2"></i><?php echo showOtherLangText('View Details'); ?></a>
                                                                </li>
                                                                <?php
                                                                if ($orderRow['ordType'] == 1) {

                                                                ?>
                                                                    <li>
                                                                        <a class="dropdown-item" href="javascript:void(0)" onclick="return showOrderJourney('<?php echo $orderRow['id']; ?>','<?php echo $orderRow['ordType']; ?>', '1');"><i class="far fa-share-square"></i>&nbsp;<?php echo showOtherLangText('Details(Supplier)') ?></a>
                                                                    </li>
                                                                <?php
                                                                }
                                                                if ($orderRow['ordType'] == 1) //order
                                                                {

                                                                    if ($accessHistoryAccountsPermission['type_id'] == 1) {
                                                                        if ($accessPaymentPermission['type_id'] == 1) {

                                                                            getPayPopup($orderRow['id']);
                                                                        }
                                                                    }
                                                                }

                                                                if ($orderRow['ordType'] == 2) {
                                                                    if ($accessHistoryAccountsPermission['type_id'] == 1) {
                                                                        if ($accessInvoicePermission['type_id'] == 1) {

                                                                            getrequisitionPopup($orderRow['id']);
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                        <?php
                                                        access_delete_history_file($getAllHistoryPermissions['access_delete_history'], $orderRow['id']);
                                                        ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="align-items-center mbTask">
                                            <a href="javascript:void(0)" class="statusLink mb-hisLink"><i class="fa-solid fa-angle-down"></i></a>
                                        </div>
                                    </div>
                                <?php  } ?>


                            </div>
                        </div>
                    </section>

                </section>

            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" id="delete-popup" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1 mt-3"><?php echo showOtherLangText('Are you sure to delete this record?') ?> </h1>
                </div>

                <div class="modal-footer justify-content-start gap-3">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-primary std-btn m-0"><?php echo showOtherLangText('No'); ?></button>
                    <button type="button" onclick="" class="deletelink btn btn-primary std-btn m-0"><?php echo showOtherLangText('Yes'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- View Detail Popup End -->

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
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

            $(".user_type_dropdown").on("click", "a", function(e) {
                var $this = $(this).parent();
                $("#userText").text($this.data("value"));
                $("#userId").val($this.data("id"));
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
            if (urlParams.has('userId')) {
                var userId = urlParams.get('userId');
                if (userId !== '') {
                    $("#userText").text($(".user_type_dropdown .selected").text());
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
            var newOnClick = "window.location.href='history.php?delOrderId=" + delOrderId + "#del'";
            $('.deletelink').attr('onclick', newOnClick);
            $('#delete-popup').modal('show');

        }

        $('.date-box-search').click(function() {

            $('#frm').submit();

        });

        function hideCheckbox(targetId) {

            if ($('#' + targetId).is(":visible")) {
                $('#' + targetId).css('display', 'none');
            } else {
                $('#' + targetId).css('display', 'block');
            }


        }

        function openPopup(ordType, ordId) {
            if (ordType == 1) {

                showOrderJourney(ordId, ordType);
                return false;

            } else if (ordType == 2) {

                showRequisitionJourney(ordId);
                return false;

            } else {

                showRawItemAndStockTakeOrder(ordType, ordId);
                return false;

            }


        }

        $('.toggle-currency-btn').click(function() {
            $(this).toggleClass('active');
            $('.usdCurr').toggle();

            if ($(this).hasClass('active')) {
                $(".jsPricebox").removeClass("<?php echo $classNameForHeading; ?>");
                $(".jsPricebox").addClass("<?php echo $classNameForHeadingOnActiveIssueIN; ?>");
            } else {
                $(".jsPricebox").removeClass("<?php echo $classNameForHeadingOnActiveIssueIN; ?>");

                $(".jsPricebox").addClass("<?php echo $classNameForHeading; ?>");
            }


        });

        function showRawItemAndStockTakeOrder(ordType, ordId) {
            if (ordType == 4) {
                showRawItemOrder(ordId);
            } else {
                showStockTakeOrder(ordId);
            }
        }

        function showRawItemOrder(ordId) {

            $.ajax({
                    method: "POST",
                    url: "rawConvertLightbox_ajax.php",

                    data: {
                        orderId: ordId
                    }
                })
                .done(function(htmlRes) {
                    $('#Raw_Convert_Item_details_content').html(htmlRes);
                    $('#Raw_Convert_Item_details').modal('show');

                    //orderAndReqJsCode();
                });

        }

        function showStockTakeOrder(ordId) {

            $.ajax({
                    method: "POST",
                    url: "stockTakeLightbox_ajax.php",

                    data: {
                        orderId: ordId
                    }
                })
                .done(function(htmlRes) {
                    $('#Stock_take_details_content').html(htmlRes);
                    $('#Stock_take_details').modal('show');

                });

        }

        function showOrderJourney(ordId, ordType, isSupOrder = 0) {
            $.ajax({
                    method: "POST",
                    url: "ordershare_ajax_pdf.php",

                    data: {
                        orderId: ordId,
                        orderType: ordType,
                        isSupDet: isSupOrder,
                        page: 'history'
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
        }

        function openSupPaymentPopup(ordId) {

            $.ajax({
                    method: "POST",
                    url: "showPaymentDetailPopupAjax.php",

                    data: {
                        orderId: ordId
                    }
                })
                .done(function(htmlRes) {
                    $('#view_payment_paid_content').html(htmlRes);
                    $('#view_payment_paid').modal('show');


                    //orderAndReqJsCode();
                });
        }


        function openReqPaymentPopup(ordId) {

            $.ajax({
                    method: "POST",
                    url: "requisitionPaymentSummaryPopupAjax.php",

                    data: {
                        orderId: ordId
                    }
                })
                .done(function(htmlRes) {
                    $('#view_payment_paid_content').html(htmlRes);
                    $('#view_payment_paid').modal('show');
                });
        }

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

            if ($('.' + targetId).is(":visible")) {
                $('.' + targetId).css('display', 'none');


            } else {
                $('.' + targetId).css('display', 'block');

                if (!$('.show-smry-cls').is(":visible")) {

                    $('.show-smry-cls').css('display', 'block');
                }

                if (targetId == 'smryDef_Val' || targetId == 'smryOtr_Val') {
                    $('.sumBreakupAmtText').css('display', 'block');
                }


            }

        }

        $('body').on('click', '.smryChk-All', function() {

            $('#show-smry').css('display', 'block');
            $('#show-header').css('display', 'none');
            $('#show-itm').css('display', 'none');


            if ($(".smryChk-All:checked").length == 1) {

                $('.show-smry-cls').css('display', 'block');
                $('#smrySuplr').css('display', 'block');
                $('#smryPayment').css('display', 'block');
                $('.smryHead').css('display', 'block');
                $('.smryDef_Val').css('display', 'block');
                $('.smryOtr_Val').css('display', 'block');

                $("#summary").prop('checked', true);
                $(".smryCheckbox").prop('checked', true);

            } else {
                $("#summary").prop('checked', false);
                $(".smryCheckbox").prop('checked', false);
                $('.show-smry-cls').css('display', 'none');
                $('#smrySuplr').css('display', 'none');
                $('#smryPayment').css('display', 'none');
                $('.smryHead').css('display', 'none');
                $('.smryDef_Val').css('display', 'none');
                $('.smryOtr_Val').css('display', 'none');

            }


        });

        $('body').on('change', '.summary-default-currency, .summary-second-currency', function() {

            updateVisibility();
        });

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

        $('body').on('click', '.itemChk-All', function() {

            $('#show-itm').css('display', 'block');


            if ($(".itemChk-All:checked").length == 1) {
                $("#itemTable").prop('checked', true);
                $(".itmTblCheckbox").prop('checked', true);

                $('.photo').css('display', 'block');
                $('.itmProd').css('display', 'block');
                $('.itmCode').css('display', 'block');

                $('.itmTotal').css('display', 'block');
                $('.itmPrc').css('display', 'block');
                $('.otherCurPrice').css('display', 'block');
                $('.itmPrcunit').css('display', 'block');
                $('.itmPurqty').css('display', 'block');
                $('.itmRecqty').css('display', 'block');
                $('.otherCurTotal').css('display', 'block');
                $('.itmNote').css('display', 'block');


            } else {
                $("#itemTable").prop('checked', false);
                $(".itmTblCheckbox").prop('checked', false);

                $('.photo').css('display', 'none');
                $('.itmProd').css('display', 'none');
                $('.itmCode').css('display', 'none');
                $('.itmTotal').css('display', 'none');
                $('.itmPrc').css('display', 'none');
                $('.otherCurPrice').css('display', 'none');
                $('.itmPrcunit').css('display', 'none');
                $('.itmPurqty').css('display', 'none');
                $('.itmRecqty').css('display', 'none');
                $('.otherCurTotal').css('display', 'none');
                $('.itmNote').css('display', 'none');
            }

        });

        function showOrdersHistory() {

            $.ajax({
                    method: "POST",
                    url: "history_pdf_ajax.php",
                })
                .done(function(htmlRes) {
                    $('#order_history_popup').html(htmlRes);
                    $('#history_pdf').modal('show');


                    //orderAndReqJsCode();
                });



        }

        $(document).ready(function() {

            var totalCount = $('.optionCheck').length;

            var totalCheckedCount = $('.optionCheck:checked').length;

            if (totalCount == totalCheckedCount) {

                $('#CheckAllOptions').prop('checked', true);
            } else {
                $('#CheckAllOptions').prop('checked', false);
            }

            $("#CheckAllOptions").on('click', function() {

                $('.optionCheck:checkbox').not(this).prop('checked', this.checked);
            });

        });


        function sortTableByColumn(table, field, order) {
            sortElementsByText(table, field, order);
        }

        function sortElementsByText(container, textElement, order) {
            var elements = $(container).get();

            elements.sort(function(a, b) {
                var textA = $(a).find(textElement).text().trim();
                var textB = $(b).find(textElement).text().trim();

                var isNumA = !isNaN(parseFloat(textA)) && isFinite(textA);
                var isNumB = !isNaN(parseFloat(textB)) && isFinite(textB);

                if (isNumA && isNumB) {
                    // If both are numbers, compare them as numbers
                    return order === 'asc' ? parseFloat(textA) - parseFloat(textB) : parseFloat(textB) - parseFloat(
                        textA);
                } else {
                    // Otherwise, compare them as strings
                    return order === 'asc' ? textA.localeCompare(textB) : textB.localeCompare(textA);
                }
            });

            $.each(elements, function(index, element) {
                $('.cntTableData').append(element);
            });
        }
    </script>
    <div id="dialog" style="display: none;">
        <?php echo showOtherLangText('Are you sure to delete this record?') ?>
    </div>
    <div class="modal" tabindex="-1" id="order_details" aria-labelledby="orderdetails" aria-hidden="true">
        <div class="modal-dialog  modal-md site-modal">
            <div id="order_details_supplier" class="modal-content p-2">

            </div>
        </div>
    </div>
    <!-- View ckecknox Popup Start -->
    <div class="modal" tabindex="-1" id="checkbox_module" aria-labelledby="checkbox_module" aria-hidden="true">
        <div class="modal-dialog modal-md site-modal">
            <div class="modal-content ">
                <div>
                    <div class="mdlHead-Popup d-flex justify-content-between align-items-center">
                        <span class="fs-4">
                            <strong><?php echo showOtherLangText('Check columns to show in list') ?>:</strong>
                        </span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <br>

                    <strong class="checkAllSectionBox">
                        <input type="checkbox" class="CheckAllOptions" id="CheckAllOptions">
                        <label>
                            <?php echo showOtherLangText('Check All') ?>
                        </label>
                    </strong>
                    <?php

                    $newOrderColumns = [
                        1 => '' . showOtherLangText('Number') . '',
                        2 => '' . showOtherLangText('Submit Date') . '',
                        3 => '' . showOtherLangText('User') . '',
                        4 => '' . showOtherLangText('Type') . '',
                        7 => '' . showOtherLangText('Refer To') . '',
                        8 => '' . showOtherLangText('Supplier Invoice No.') . '',
                        10 => '' . showOtherLangText('Value') . '',
                        14 => '' . showOtherLangText('Payment Status') . '',
                        15 => '' . showOtherLangText('Payment No.') . '',
                        16 => '' . showOtherLangText('Invoice No.') . '',
                        17 => '' . showOtherLangText('Account') . '',
                    ];

                    ?>
                    <form method="post" action="" class="shortList-ChkAll">
                        <?php foreach ($newOrderColumns as $key => $col) {

                            if (isset($historyUserFilterFields)) {
                                $sel =  (in_array($key, $historyUserFilterFields) || empty($historyUserFilterFields)) ? ' checked="checked" ' : '';
                            }

                        ?>
                            <input type="checkbox" id="optionCheck" class="optionCheck" name="showFields[]" <?php echo $sel; ?> value="<?php echo $key; ?>">&nbsp;<?php echo $col; ?><br>

                        <?php } ?>

                        <br>
                        <div class="d-flex flex-row gap-3">
                            <button type="submit" class="btn btn-primary dropdown-toggle py-2" style="border:1px solid #7a89ff; background-color: #7a89ff; color: #fff;"><?php echo showOtherLangText('Show'); ?></button>
                            <?php if (isset($historyUserFilterFields)) { ?>
                                <a class="btn btn-primary dropdown-toggle py-2" onClick="window.location.href='history.php?clearshowFields=1'" style="border:1px solid #7a89ff; background-color: #7a89ff; color: #fff;"><?php echo showOtherLangText('Clear filter'); ?> </a>

                            <?php } ?>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
    <!-- View checkbox Popup End -->
    <!-- ===== History pdf popup new in div format======= -->
    <div class="modal" tabindex="-1" id="history_pdf" aria-labelledby="history_pdfModal" aria-hidden="true">
        <div class="modal-dialog site-modal" style="max-width:1000px">
            <div id="order_history_popup" class="modal-content p-2">


            </div>
        </div>
    </div>
    <!-- raw materiels Popup Start -->
    <div class="modal" tabindex="-1" id="Raw_Convert_Item_details" aria-labelledby="Raw-Convert-Item-details" aria-hidden="true">
        <div class="modal-dialog modal-md site-modal">
            <div id="Raw_Convert_Item_details_content" class="modal-content p-2">


            </div>
        </div>
    </div>
    <!-- raw materiels 1 Popup End -->

    <!-- stock take details Popup Start -->
    <div class="modal" tabindex="-1" id="Stock_take_details" aria-labelledby="Stock-take-details" aria-hidden="true">
        <div class="modal-dialog modal-md site-modal">
            <div id="Stock_take_details_content" class="modal-content p-2">


            </div>
        </div>
    </div>
    <!-- stock take details Popup End -->
    <div class="modal" tabindex="-1" id="view_payment_paid" aria-labelledby="view-payment-paid" aria-hidden="true">
        <div class="modal-dialog modal-md site-modal">
            <div class="modal-content p-2" id="view_payment_paid_content">

            </div>
            <!-- payment Detail Popup End -->



        </div>
    </div>
    <!-- payment Detail Popup End -->
    </div>

    </div>
    <!-- <script>
document.addEventListener('DOMContentLoaded', function() {
const parentClasses = ['.numRef1', '.numRef2', '.stsHiscol'];

parentClasses.forEach(parentClass => {
    const parentDivs = document.querySelectorAll(parentClass);

    parentDivs.forEach(parentDiv => {
        const childDivs = parentDiv.querySelectorAll('.tb-bdy');

        if (childDivs.length > 1) {
            parentDiv.style.cssText = 'flex-grow: 1 !important; display: flex !important;';
        } else if (childDivs.length === 1) {
            parentDiv.style.cssText = 'flex-grow: 0 !important; display: flex !important;';
        } else {
            parentDiv.style.cssText = 'display: none !important;';
        }
    });
});
});

</script> -->

    <script>
        // set filter box responsive class remove script 
        if (document.getElementById("resFilterBox")) {
            var closeBtn = document.getElementById("closeResFilterBox");
            var closeBox = document.getElementById("resFilterBox");
            closeBtn.addEventListener('click', function() {
                closeBox.classList.remove('hstTable-show');
            });

        }
    </script>
    <script>
        if (document.querySelector('.toggle-currency-btn')) {
            // Add a click event listener to the toggle-currency-btn button
            document.querySelector('.toggle-currency-btn').addEventListener('click', function() {
                // Loop through all .issueIn elements
                document.querySelectorAll('.issueDtl').forEach(function(issueInElement) {
                    // Check all .usdCurr elements within the current .issueIn element
                    const usdCurrElements = issueInElement.querySelectorAll('.usdCurr');

                    // If any .usdCurr has display: none, add the class .issueIn1 to the parent .issueIn
                    let isHidden = false;
                    usdCurrElements.forEach(function(usdCurrElement) {
                        if (window.getComputedStyle(usdCurrElement).display === 'none') {
                            isHidden = true;
                        }
                    });

                    // Add the class .issueIn1 if the condition is met
                    if (isHidden) {
                        issueInElement.classList.remove('issueDtl1');
                    } else {
                        issueInElement.classList.add('issueDtl1');
                    }
                });
            });
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var numRefElements = document.querySelectorAll('.numRef1, .numRef2');

            numRefElements.forEach(function(numRef) {
                var childDivs = numRef.children; // Get all direct child elements

                // Count only div elements
                var divCount = Array.from(childDivs).filter(child => child.tagName === 'DIV').length;

                if (divCount <= 2) {
                    numRef.classList.add('numRef3');
                } else if (divCount === 3) {
                    numRef.classList.add('numRef4');
                }
            });
        });
    </script>
    <script>
        function styleFirstGrandTotalRow() {
            const grandTotalRows = document.querySelectorAll('.grand-total-tabel .grand-total');
            console.log("runned")

            if (grandTotalRows.length > 0) {
                const firstGrandTotalRow = grandTotalRows[0];
                firstGrandTotalRow.classList.add('active__th__row');
                firstGrandTotalRow.style.backgroundColor = '#7a89ff';
                firstGrandTotalRow.style.color = 'white';
            }
        }

        function showHideByClassSummary(targetId) {

            if ($('.' + targetId).is(":visible")) {
                $('.' + targetId).css('display', 'none');


            } else {
                $('.' + targetId).css('display', 'block');

                if (!$('.show-smry-cls').is(":visible")) {

                    $('.show-smry-cls').css('display', 'block');
                }

                if (targetId == 'smryDef_Val' || targetId == 'smryOtr_Val') {
                    $('.sumBreakupAmtText').css('display', 'block');
                }


            }

        }
    </script>


    <?php
    include_once('orderAndReqJourneyJsCode.php');
    ?>

    <?php
    include_once('historyPdfJsCode.php');
    ?>

</body>

</html>