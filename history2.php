<?php
include('inc/dbConfig.php'); //connection details


if (!isset($_SESSION['adminidusername'])) {
    echo '<script>window.location = "login.php"</script>';
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

//check page permission
$checkPermission = permission_denied_for_section_pages($_SESSION['designation_id'], $_SESSION['accountId']);

if (!in_array('4', $checkPermission)) {
    echo "<script>window.location='index.php'</script>";
}


//Access payment permission for user
$accessPaymentPermission = get_access_payment_permission($_SESSION['designation_id'], $_SESSION['accountId']);

//Access Invoice permission for user
$accessInvoicePermission = get_access_invoice_permission($_SESSION['designation_id'], $_SESSION['accountId']);

//Access Xcel File and PDF permission for user
$accessHistoryXclPdfPermission = access_history_xcl_pdf_file($_SESSION['designation_id'], $_SESSION['accountId']);

//Access Accounts related permission for user
$accessHistoryAccountsPermission = access_history_accounts_detail($_SESSION['designation_id'], $_SESSION['accountId']);


if (isset($_REQUEST['showFields'])) {
    $updateQry = " UPDATE tbl_user SET historyUserFilterFields = '" . implode(',', $_REQUEST['showFields']) . "' WHERE id = '" . $_SESSION['id'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ";
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
    `invoiceNo` = '" . $resRow['invNo'] . "',
    `orderType` = '" . $resRow['ordType'] . "',
    `action` = 'payment' ";
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

if (isset($_GET['fromDate']) && isset($_GET['toDate'])) {
    $_SESSION['fromDate'] = $_GET['fromDate'];
    $_SESSION['toDate'] = $_GET['toDate'];
}
if (isset($_SESSION['fromDate']) && $_SESSION['fromDate'] != '' && $_SESSION['toDate'] != '') {

    $cond = " AND DATE(o.setDateTime) BETWEEN '" . date('Y-m-d', strtotime($_SESSION['fromDate'])) . "' AND '" . date('Y-m-d', strtotime($_SESSION['toDate'])) . "'  ";
    $cond1 = $cond;
} else {
    $_GET['fromDate'] = date('d-m-Y', strtotime('-3 days'));
    $_GET['toDate'] = date('d-m-Y');

    $cond = " AND DATE(o.setDateTime) BETWEEN '" . date('Y-m-d', strtotime($_GET['fromDate'])) . "' AND '" . date('Y-m-d', strtotime($_GET['toDate'])) . "' ";
    $cond1 = $cond;
}

if (isset($_GET['ordType']) && $_GET['ordType']) {
    $cond .= " AND o.ordType = '" . $_GET['ordType'] . "'   ";
    $cond1 = $cond;
} else {
    $cond1 .= " AND o.ordType IN(2,1)  ";
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
}

if ($_GET['dateType'] == '') {
    $cond .= " GROUP BY o.id ORDER BY o.id desc ";
}

if ($_GET['dateType'] != '' && $_GET['dateType'] == 1) {
    $cond .= " GROUP BY o.id ORDER BY o.ordDateTime desc ";
}
if ($_GET['dateType'] != '' && $_GET['dateType'] == 2) {
    $cond .= " GROUP BY o.id ORDER BY o.setDateTime desc ";
}
if ($_GET['dateType'] != '' && $_GET['dateType'] == 3) {

    $cond .= " GROUP BY o.id ORDER BY o.paymentDateTime desc, o.setDateTime desc ";
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


//get issued in total and issued out total
$sql = " SELECT o.ordAmt AS totalOrdAmt, o.ordType, o.paymentStatus,od.currencyId 
FROM tbl_order_details od 

INNER JOIN tbl_orders o 
    ON(o.id = od.ordId) AND o.account_id = od.account_id

LEFT JOIN tbl_payment p 
    ON(p.orderId = o.id) AND p.account_id = o.account_id

LEFT JOIN tbl_req_payment rp 
    ON(rp.orderId = o.id) AND rp.account_id = o.account_id

WHERE o.status = '2' " . $cond1 . " AND o.account_id = '" . $_SESSION['accountId'] . "'  GROUP BY od.ordId ";

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



//---------------------------------------------

$typeArr = [1 => '' . showOtherLangText('Issued In') . '', 2 => '' . showOtherLangText('Issued Out') . '', 3 => '' . showOtherLangText('Stock Take') . '', 4 => '' . showOtherLangText('Raw Item Convert') . ''];


$typeOptions = '<ul class="dropdown-menu type_dropdown">';
//$typeOptions .= '<option value="">'.showOtherLangText('Type').'</option>';
foreach ($typeArr as $typeKey => $typeVal) {
    $sel = isset($_GET['ordType']) && $_GET['ordType'] == $typeKey  ? 'selected' : '';
    //$typeOptions .= '<option value="'.$typeKey.'" '.$sel.'>'.$typeVal.'</option>';
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

$userOptions = '<select class="form-control" name="userId" onchange="this.form.submit()">';
$userOptions .= '<option value="">' . showOtherLangText('User') . '</option>';
while ($userRow = mysqli_fetch_array($resultSet)) {

    $sel = isset($_GET['userId']) && $_GET['userId'] == $userRow['id']  ? 'selected="selected"' : '';
    $userOptions .= '<option value="' . $userRow['id'] . '" ' . $sel . '>' . $userRow['name'] . '</option>';
}
$userOptions .= '</select>';

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
//$accountOptions .= '<option value="">'.showOtherLangText('Account').'</option>';
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
//$suppMemStoreOptions .= '<option value="">'.showOtherLangText('Refer To').'</option>';
while ($suppRow = mysqli_fetch_array($result)) {
    $checkorderRow = checkorderPermissionRow($_SESSION['designation_id'], $_SESSION['accountId'], $suppRow['id']);
    if (mysqli_num_rows($checkorderRow) < 1) {
        continue; //exclude this order as user don't have its permission
    }
    $sel = ($getId == $suppRow['id']) ? 'selected' : '';
    if ($_GET['ordType'] == 1 || ($_GET['ordType'] == '' && $_GET['statusType'] != 2) || ($_GET['ordType'] == '' && $_GET['statusType'] == '')) {

        // $suppMemStoreOptions .= '<option style="color:green;" value="suppId_'.$suppRow['id'].'" '.$sel.'>'.$suppRow['name'].'</option>';
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

        // $suppMemStoreOptions .= '<option style="color:Red;" value=deptUserId_'.$deptUserRow['id'].' '.$sel.'>'.$deptUserRow['name'].'</option>';
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

        //$suppMemStoreOptions .= '<option style="color:blue;" value=storeId_'.$storeRow['id'].' '.$sel.'>'.$storeRow['name'].'</option>';
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

        .accntDtl {
            height: 100%;
            padding-top: 0px;
            overflow-y: scroll;
        }


        @media (min-width:1137px) {
            /* 29 date tabel css */

            .hisTypclm,
            .hisRefrclm {
                width: fit-content !important;
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

            .numRef:first-child {
                width: 45% !important;
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
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"><i class="fa-solid fa-bars"></i></span>
                                    </button>
                                </div>
                                <div class="mbpg-name">
                                    <h1 class="h1"><?php echo showOtherLangText('History'); ?></h1>
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

                <section class="hisParent-sec">

                    <section class="ordDetail hisTory">

                        <div class="alrtMessage">
                            <div class="container">

                                <?php if (isset($_GET['delete']) || isset($_GET['status'])) { ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <p><?php

                                            echo isset($_GET['status']) ? ' ' . showOtherLangText('New record added successfully') . ' ' : '';

                                            echo isset($_GET['delete']) ? ' ' . showOtherLangText('Record deleted successfully.') . ' ' : '';


                                            ?>
                                        </p>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php } ?>


                                <!-- <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hello User!</strong> You should check your order carefully.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div> -->
                            </div>
                        </div>
                        <form name="frm" id="frm" method="get" action="">
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
                            <?php
                            if (isset($historyUserFilterFields)) {
                                foreach ($historyUserFilterFields as $key => $val) {
                            ?>
                                    <input type="hidden" name="showFields[<?php echo $key; ?>]" <?php echo $sel; ?> value="<?php echo $val; ?>">

                            <?php
                                }
                            }
                            ?>
                            <div class="container hisData">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="hstCal">
                                            <div class="his-featBtn">
                                                <div class="cal-Ender">
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
                                                <a href="javascript:void(0)" target="_blank" data-bs-toggle="modal" data-bs-target="#history_pdf">
                                                    <img src="Assets/icons/stock-pdf.svg" alt="Stock PDF">
                                                </a>
                                            </div>
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

                        <div class="container mb-hisDtl">
                            <div class="row">
                                <div class="col-md-5 is-Incol">
                                    <p class="rd-In">Issue in</p>
                                    <p class="ttlAmount"><?php

                                                            if (isset($issuedInOutArr[1])) {
                                                                $checkIfPermissionToNewOrderSec = checkIfPermissionToNewOrderSec($_SESSION['designation_id'], $_SESSION['accountId']);


                                                                if (mysqli_num_rows($checkIfPermissionToNewOrderSec) > 0) {
                                                                    //$issuedOut = showPrice($issuedInOutArr[1], $getDefCurDet['curCode']);
                                                                    echo isset($issuedInOutArr[1]) ? showPrice($issuedInOutArr[1], $getDefCurDet['curCode']) : 0;
                                                                } else {
                                                                    echo '0' . ' ' . $getDefCurDet['curCode'];
                                                                }
                                                                //isset( $issuedInOutArr[4] ) ? showPrice($issuedInOutArr[1]+($issuedInOutArr[4]/2), $getDefCurDet['curCode']) : $issuedOut;
                                                            }
                                                            // elseif( isset( $issuedInOutArr[4] ) )
                                                            // {
                                                            //    showPrice($issuedInOutArr[4]/2, $getDefCurDet['curCode']);
                                                            // }
                                                            else {
                                                                echo '0' . ' ' . $getDefCurDet['curCode'];
                                                            }

                                                            ?></p>
                                </div>
                                <div class="col-md-5 is-Outcol">
                                    <p class="gr-Out">Issue Out</p>
                                    <p class="ttlAmount-rec"><?php

                                                                $checkIfPermissionToNewReqSec = checkIfPermissionToNewReqSec($_SESSION['designation_id'], $_SESSION['accountId']);

                                                                if (mysqli_num_rows($checkIfPermissionToNewReqSec) > 0) {
                                                                    echo isset($issuedInOutArr[2]) ? showPrice($issuedInOutArr[2], $getDefCurDet['curCode']) : 0;
                                                                } else {
                                                                    echo '0' . ' ' . $getDefCurDet['curCode'];
                                                                }



                                                                ?></p>
                                </div>
                                <div class="col-md-2 maxBtn">
                                    <a href="javascript:void(0)" class="maxLink">
                                        <img src="Assets/icons/maximize.svg" alt="Maximize">
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="container detailPrice">
                            <div class="row align-items-start" style="height: 156px;">
                                <div class="tab-mbDtl">
                                    <a href="javascript:void(0)" class="tab-revLnk"><i class="fa-solid fa-arrow-left"></i></a>
                                </div>
                                <div class="issueDtl">
                                    <div class="issueIn">
                                        <div class="dspBlk">
                                            <div class="paidIsue d-flex">
                                                <div class="col-md-3">
                                                    <p class="pdStatus">Paid</p>
                                                    <p class="pendStatus">Pending</p>
                                                </div>
                                                <div class="col-md-9 text-center">
                                                    <p class="rd-In"><?php echo showOtherLangText('Issued In'); ?></p>
                                                    <p class="ttlAmount"><?php echo showprice($issueInTotal, $getDefCurDet['curCode']); ?></p>
                                                    <p class="pdAmount"><?php echo ($issuedInOutPaidArr[1][1] > 0) ? showPrice($issuedInOutPaidArr[1][1], $getDefCurDet['curCode']) : '0'; ?></p>
                                                    <p class="pendAmount"><?php echo ($issuedInOutPendingArr[1][0] > 0) ? showPrice($issuedInOutPendingArr[1][0], $getDefCurDet['curCode']) : '0'; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php

                                        $sql = " SELECT od.currencyId, c.curCode, o.ordCurAmt AS totalOrdCurAmt, o.paymentStatus FROM tbl_order_details od
                                    INNER JOIN tbl_orders o 
                                        ON(o.id=od.ordId) AND o.account_id=od.account_Id
                                    INNER JOIN tbl_currency c
                                        ON(od.currencyId=c.id) AND od.account_id=c.account_Id
                                    WHERE o.status = '2' " . $cond1 . " AND o.account_id = '" . $_SESSION['accountId'] . "' GROUP BY o.id ORDER BY o.id desc ";
                                        $result = mysqli_query($con, $sql);
                                        $otherCurrRowArr = [];
                                        $otherCurrTotalValueArr = [];
                                        $otherCurrPendingTotalValueArr = [];
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



                                        foreach ($otherCurrRowArr as $otherCurrRow) {  ?>
                                            <div class="usdCurr text-center">
                                                <div class="paidIsue d-flex">
                                                    <div class="col-md-3">
                                                        <p class="pdStatus">Paid</p>
                                                        <p class="pendStatus">Pending</p>
                                                    </div>
                                                    <div class="col-md-9 text-center">
                                                        <p class="usd-In"><?php echo ($otherCurrRow['curCode']); ?></p>
                                                        <p class="ttlAmount">xxxx $</p>
                                                        <p class="pdAmount">xxxx $</p>
                                                        <p class="pendAmount">xxxx $</p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <!-- <div class="otrCurr text-center">
                                            <div class="paidIsue d-flex">
                                                <div class="col-md-3">
                                                    <p class="pdStatus">Paid</p>
                                                    <p class="pendStatus">Pending</p>
                                                </div>
                                                <div class="col-md-9 text-center">
                                                    <p class="otr-In">Tzs</p>
                                                    <p class="ttlAmount">xxxx Tzs</p>
                                                    <p class="pdAmount">xxxx Tzs</p>
                                                    <p class="pendAmount">xxxx Tzs</p>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="issueOut">
                                        <div class="recIsue d-flex">
                                            <div class="col-md-5">
                                                <p class="recStatus">Received</p>
                                                <p class="pendStatus">Pending</p>
                                            </div>
                                            <div class="col-md-7 text-center">
                                                <p class="gr-Out"><?php echo showOtherLangText('Issued Out'); ?></p>
                                                <p class="ttlAmount-rec"><?php echo showprice($issueOutTotal, $getDefCurDet['curCode']); ?></p>
                                                <p class="pdAmount-rec"> <?php echo ($issuedInOutPaidArr[2][1]) ? showPrice($issuedInOutPaidArr[2][1], $getDefCurDet['curCode']) : '0'; ?></p>
                                                <p class="pendAmount-rec"><?php echo ($issuedInOutPendingArr[2][0] > 0) ? showPrice($issuedInOutPendingArr[2][0], $getDefCurDet['curCode']) : '0'; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $sqlSet = " SELECT od.* FROM tbl_order_details od
                                                    INNER JOIN tbl_orders o
                                                        ON(o.id=od.ordId) AND o.account_id=od.account_Id
                                                    WHERE o.ordType = '3' AND o.status = '2' " . $cond1 . " ";
                                    $resultSet = mysqli_query($con, $sqlSet);

                                    $variancesPosTot = 0;
                                    $variancesPosQtyTot = 0;
                                    $variancesNevQtyTot = 0;
                                    $variancesNevTot = 0;
                                    $varaincesVal = 0;
                                    while ($resRow = mysqli_fetch_array($resultSet)) {
                                        if ($resRow['qtyReceived'] < $resRow['qty']) {
                                            $varaincesVal = $resRow['qty'] - $resRow['qtyReceived'];
                                            $variancesPosQtyTot += $varaincesVal;
                                            $variancesPosTot += ($varaincesVal * $resRow['lastPrice']);
                                        } elseif ($resRow['qtyReceived'] > $resRow['qty']) {
                                            $varaincesVal = $resRow['qtyReceived'] - $resRow['qty'];

                                            $variancesNevQtyTot += $varaincesVal;
                                            $variancesNevTot += ($varaincesVal * $resRow['lastPrice']);
                                        }
                                    }
                                    ?>
                                    <div class="Variance text-center">
                                        <p class="varDtl">Variances</p>
                                        <p class="varValue"><?php echo showPrice($variancesNevTot, $getDefCurDet['curCode']) ?></p>
                                        <p class="varDif"><?php echo showPrice($variancesPosTot, $getDefCurDet['curCode']) ?></p>
                                    </div>
                                </div>
                                <?php
                                if ($accessHistoryAccountsPermission['type_id'] == 1) {
                                ?>
                                    <div class="accntDtl">
                                        <p class="accHead text-center">Accounts</p>
                                        <?php
                                        $sqlSet = " SELECT c.curCode, a.* FROM  tbl_accounts a 
            INNER JOIN tbl_currency c 
                ON( c.id=a.currencyId) AND c.account_Id=a.account_Id
            WHERE a.account_id = '" . $_SESSION['accountId'] . "' ";
                                        $result = mysqli_query($con, $sqlSet);

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
                                        ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>




                        <div class="container position-relative hstTbl-head px-0">
                            <!-- check box model icon -->
                            <div class="d-flex justify-content-end container">
                                <a class="dropdown-item p-0" style="width: fit-content;" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#checkbox_module">
                                    <img src="Assets/icons/chkColumn.svg" style="width: 30px;" alt="Check Column">
                                </a>
                            </div>
                            <!-- Item Table Head Start -->
                            <div class=" mt-2 px-0 itmTable " style="background:transparent;  border: none ; box-shadow: none;  border-radius: 10px;  padding: 3px 12px;">
                                <div class="mb-hstryBareq">&nbsp;</div>
                                <div class="align-items-center d-flex w-100">
                                    <div class="numRef align-items-center">
                                        <div class="tb-bdy srHisclm">
                                            <p></p>
                                        </div>
                                        <div class="tb-bdy numItmclm">
                                            <div class="d-flex align-items-center" style="min-width: 40px;">
                                                <p>Number</p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>


                                        <div class="tb-bdy hisTypclm">
                                            <div class="d-flex align-items-center" style="background:inherit">
                                                <div class="dropdown d-flex position-relative">
                                                    <a class="dropdown-toggle body3" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span id="TypeText">Date</span> <i class="fa-solid fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu date_type">
                                                        <li data-id="1" data-value="Submit date"><a class="dropdown-item " href="javascript:void(0)">Submit date</a></li>
                                                        <li data-id="2" data-value="Settle date"><a class="dropdown-item " href="javascript:void(0)">Settle date</a></li>
                                                        <li data-id="3" data-value="Payment date"><a class="dropdown-item " href="javascript:void(0)">Payment date</a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tb-bdy hisTypclm">
                                            <div class="d-flex align-items-center" style=" background:inherit">
                                                <div class="dropdown d-flex position-relative">
                                                    <a class="dropdown-toggle body3" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span id="TypeText">User</span> <i class="fa-solid fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu date_type">
                                                        <li data-id="1" data-value="Submit date"><a class="dropdown-item " href="javascript:void(0)">Submit date</a></li>
                                                        <li data-id="2" data-value="Settle date"><a class="dropdown-item " href="javascript:void(0)">Settle date</a></li>
                                                        <li data-id="3" data-value="Payment date"><a class="dropdown-item " href="javascript:void(0)">Payment date</a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="tb-bdy hisTypclm">
                                            <div class="d-flex align-items-center" style=" background:inherit">
                                                <div class="dropdown d-flex position-relative">
                                                    <a class="dropdown-toggle body3" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span id="TypeText">Type</span> <i class="fa-solid fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu type_dropdown">
                                                        <li data-id="1" data-value="Issued in"><a class="dropdown-item " href="javascript:void(0)">Issued in</a></li>
                                                        <li data-id="2" data-value="Issued out"><a class="dropdown-item " href="javascript:void(0)">Issued out</a></li>
                                                        <li data-id="3" data-value="Stock take"><a class="dropdown-item " href="javascript:void(0)">Stock take</a></li>
                                                        <li data-id="4" data-value="Raw item convert"><a class="dropdown-item " href="javascript:void(0)">Raw item convert</a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="tb-bdy hisRefrclm">
                                            <div class="d-flex align-items-center">
                                                <div class="dropdown d-flex position-relative">
                                                    <a class="dropdown-toggle body3 w-auto" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span id="refertotext">Refer to</span> <i class="fa-solid fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu referto_dropdown">
                                                        <li data-id="suppId_37" data-value="Active Store"><a class="dropdown-item isuIn-grReq  " href="javascript:void(0)">Active Store</a></li>
                                                        <li data-id="suppId_12" data-value="Azam Uhai-water (town) "><a class="dropdown-item isuIn-grReq  " href="javascript:void(0)">Azam Uhai-water (town) </a></li>
                                                        <li data-id="suppId_18" data-value="Local Fish Market "><a class="dropdown-item isuIn-grReq  " href="javascript:void(0)">Local Fish Market </a></li>
                                                        <li data-id="suppId_26" data-value="Mushus Detergent"><a class="dropdown-item isuIn-grReq  " href="javascript:void(0)">Mushus Detergent</a></li>
                                                        <li data-id="suppId_17" data-value="Paje One"><a class="dropdown-item isuIn-grReq  " href="javascript:void(0)">Paje One</a></li>
                                                        <li data-id="suppId_14" data-value="Suvacor Kahawa (Town)"><a class="dropdown-item isuIn-grReq  " href="javascript:void(0)">Suvacor Kahawa (Town)</a></li>
                                                        <li data-id="suppId_11" data-value="Zmmi "><a class="dropdown-item isuIn-grReq  " href="javascript:void(0)">Zmmi </a></li>
                                                        <li data-id="deptUserId_24" data-value="Casa Bar "><a class="dropdown-item isuOut-rdSup " href="javascript:void(0)">Casa Bar </a></li>
                                                        <li data-id="deptUserId_23" data-value="Casa Kitchen "><a class="dropdown-item isuOut-rdSup " href="javascript:void(0)">Casa Kitchen </a></li>
                                                        <li data-id="deptUserId_13" data-value="Fun Bar "><a class="dropdown-item isuOut-rdSup " href="javascript:void(0)">Fun Bar </a></li>
                                                        <li data-id="deptUserId_8" data-value="Home (Salim )"><a class="dropdown-item isuOut-rdSup " href="javascript:void(0)">Home (Salim )</a></li>
                                                        <li data-id="deptUserId_225" data-value="Test3"><a class="dropdown-item isuOut-rdSup " href="javascript:void(0)">Test3</a></li>
                                                        <li data-id="storeId_5" data-value="Drinks Store"><a class="dropdown-item stockTake-pr  " href="javascript:void(0)">Drinks Store</a></li>
                                                        <li data-id="storeId_10" data-value="Dry Goods Central Store "><a class="dropdown-item stockTake-pr  " href="javascript:void(0)">Dry Goods Central Store </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="tb-bdy hisValclm">
                                        <div class="tb-head hisValclm" style="width: fit-content !important; padding-left:0;">
                                            <div class="d-flex align-items-center">
                                                <p>Supplier <br> inv no</p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tb-bdy hisValclm">
                                        <div class="tb-head hisValclm" style="width: fit-content !important; padding-left:0;">
                                            <div class="d-flex align-items-center">
                                                <p>Value</p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="stsHiscol d-flex align-items-center" style="min-width: fit-content;">
                                        <div class="tb-bdy hisStatusclm" style="width: 70px !important; padding-left:0px;">
                                            <div class="d-flex align-items-center" style="margin-left:-1rem;">
                                                <div class="dropdown d-flex position-relative">
                                                    <a class="dropdown-toggle body3" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span id="statusText">Status</span> <i class="fa-solid fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu status_type">
                                                        <li data-id="1" data-value="Paid"><a class="dropdown-item " href="javascript:void(0)">Paid</a>
                                                        </li>
                                                        <li data-id="2" data-value="Received"><a class="dropdown-item " href="javascript:void(0)">Received</a>
                                                        </li>
                                                        <li data-id="3" data-value="Pending"><a class="dropdown-item " href="javascript:void(0)">Pending</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="tb-bdy hisStatusclm">
                                            <div class="d-flex align-items-center justify-content-between" style=" width: fit-content !important;">
                                                <p style="color: #666c85; font-weight:600;">pay <br> no.</p>
                                                <span class="dblArrow hisValclm">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="tb-bdy hisStatusclm" style=" width: 18% !important;">
                                            <div class="d-flex align-items-center justify-content-between" style=" width: fit-content !important;">
                                                <p style="color: #666c85; font-weight:600; ">Inv <br> no.</p>
                                                <span class="dblArrow hisValclm">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="tb-bdy hisAcntclm ">

                                            <div class="d-flex align-items-center">
                                                <div class="dropdown d-flex position-relative">
                                                    <a class="dropdown-toggle body3" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span id="accountTxt">Account</span> <i class="fa-solid fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu account_dropdown">
                                                        <li data-id="1" data-value="Main Safe TSH"><a class="dropdown-item " href="javascript:void(0)">Main Safe TSH</a></li>
                                                        <li data-id="2" data-value="2 Safe TSH"><a class="dropdown-item " href="javascript:void(0)">2 Safe TSH</a></li>
                                                        <li data-id="3" data-value="Main Safe USD"><a class="dropdown-item " href="javascript:void(0)">Main Safe USD</a></li>
                                                        <li data-id="4" data-value="Fun Beach DPO Account"><a class="dropdown-item " href="javascript:void(0)">Fun Beach DPO Account</a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="flex-grow-1" style="margin-left: 1rem;">
                                        <div class="d-flex align-items-center">
                                            <p>Action</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <a href="javascript:void(0)" class="statusLink mb-hisLink"><i class="fa-solid fa-angle-down"></i></a>
                                </div>
                            </div>
                            <!-- Item Table Head End -->
                        </div>
                    </section>


                    <section class="hisTblbody">
                        <div id="boxscroll">
                            <div class="container position-relative hstTbl-bd">
                                <!-- Item Table Body Start -->
                                <?php
                                $variances = [];
                                $x = 0;
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
                                            10 => ($orderRow['ordType'] == 3) ? getNumFormtPrice($variancesTotAmt, $getDefCurDet['curCode']) : getNumFormtPrice($orderRow['ordAmt'], $getDefCurDet['curCode'])
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

                                ?>


                                    <div class="hisTask <?php if ($x > 1) {
                                                            echo 'mt-2';
                                                        } ?>">
                                        <div class="mb-hstryBareq">&nbsp;</div>
                                        <div class="align-items-center itmBody">
                                            <div class="numRef align-items-center">
                                                <div class="tb-bdy srHisclm">
                                                    <p><?php echo $x; ?></p>
                                                </div>
                                                <div class="tb-bdy numItmclm">
                                                    <!-- <p class="hisNo">No. V0002349</p> -->
                                                    <p class="hisOrd">#<?php echo $orderRow['ordNumber']; ?></p>
                                                </div>
                                                <div class="tb-bdy hisDateclm">
                                                    <p class="fstDt"><?php echo $dateType; ?></p>
                                                    <!-- <p class="lstDt">23.05.22</p> -->
                                                </div>

                                                <div class="tb-bdy hisDateclm">
                                                    Admin
                                                </div>

                                                <div class="tb-bdy hisTypclm">
                                                    <div class="d-flex align-items-center hisOrd-typ">
                                                        <?php if ($ordType == 'Issued in') { ?>
                                                            <div class="ordBar-rd">&nbsp;</div>
                                                        <?php } else { ?>
                                                            <div class="reqBar-gr">&nbsp;</div>
                                                        <?php } ?>
                                                        <p><?php echo $ordType; ?></p>
                                                    </div>

                                                </div>
                                                <div class="tb-bdy hisRefrclm">
                                                    <p class="refTomember"><?php echo $suppMemStoreId; ?></p>
                                                </div>
                                            </div>
                                            <div class="tb-bdy hisValclm">
                                                <p class="dolValcurr"><?php echo ($orderRow['ordType'] == 3) ? getNumFormtPrice($variancesTotAmt, $getDefCurDet['curCode']) : getNumFormtPrice($orderRow['ordAmt'], $getDefCurDet['curCode'])
                                                                            . '<br>' .
                                                                            ($orderRow['ordCurAmt'] > 0 ? showOtherCur($orderRow['ordCurAmt'], $curDet['id']) : ''); ?></p>
                                                <!-- <p class="othrValcurr">357,900 Tzs</p> -->
                                            </div>

                                            <div class="tb-bdy hisValclm">
                                                <p class="dolValcurr">357,900</p>
                                                <!-- <p class="othrValcurr">357,900 Tzs</p> -->
                                            </div>
                                            <div class="stsHiscol d-flex align-items-center flex-grow-1">
                                                <div class="tb-bdy hisStatusclm" style="width: 70px !important; padding-left:0px;">
                                                    <p class="his-pendStatus"><?php echo $paymentStatus; ?></p>
                                                </div>
                                                <div class="tb-bdy hisStatusclm" slot="padding-left:0px;">
                                                    <p class="his-pendStatus">pymt no</p>
                                                </div>

                                                <div class="tb-bdy hisStatusclm" slot="padding-left:0px;">
                                                    <p class="his-pendStatus">inv no</p>
                                                </div>

                                                <div class="tb-bdy hisAcntclm flex-grow-1">
                                                    <p class="hisAccount"><?php echo ($orderRow['paymentStatus'] == 1 ? $orderRow['accountName'] : ''); ?></p>
                                                </div>

                                            </div>

                                            <div class="tb-bdy shrtHisclm" style="width: fit-content !important;">
                                                <div class="mb-Acntdetail">
                                                    <div class="tb-bdy">
                                                        <p>Account</p>
                                                        <p class="mb-Acntnum"></p>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-end his-Paybtn">
                                                    <div class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                        <a href="javascript:void(0)">
                                                            <p class="h3">Inv</p>
                                                        </a>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="doc-bx text-center d-flex justify-content-center align-items-center position-relative">
                                                            <a href="javascript:void(0)" class="dropdown-toggle runLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <span class="docMent"></span>
                                                                <p class="btn2">Documents <i class="fa-solid fa-angle-down"></i>
                                                                </p>
                                                            </a>

                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#view_details"><i class="far fa-square pe-2"></i>View Details</a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#Raw_Convert_Item_details"><i class="far fa-square pe-2"></i>Raw details</a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#Stock_take_details"><i class="far fa-square pe-2"></i>Stock details</a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#view_payment"><i class="far fa-square pe-2"></i>view payment</a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#view_payment_paid"><i class="far fa-square pe-2"></i>view payment paid</a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#view_invoice"><i class="far fa-square pe-2"></i>view invoice</a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#view_invoice_recived"><i class="far fa-square pe-2"></i>view invoice recived</a>
                                                                </li>

                                                            </ul>
                                                        </div>
                                                        <?php
                                                        access_delete_history_file($_SESSION['designation_id'], $_SESSION['accountId'], $orderRow['id']);
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
                                <?php
                                if (isset($_GET['delOrderId'])) {
                                ?><br>

                                    <div class="hbrw-DelHstry">
                                        <form method="post" action="" autocomplete="off">
                                            <input type="hidden" name="delOrderId" value="<?php echo $_GET['delOrderId']; ?>" />

                                            <div class="input-group"><?php echo showOtherLangText('Account Password') ?>:
                                                <input type="password" value="" placeholder="" name="password">&nbsp;&nbsp;
                                                <button type="submit" class=" btn-primary"><?php echo showOtherLangText('Delete Now') ?></button>
                                                &nbsp;&nbsp;

                                                <a href="history.php" class="class=" btn-primary><?php echo showOtherLangText('Cancel') ?></a>

                                            </div>
                                        </form>
                                    </div>
                                <?php } ?>
                                <!-- <div class="hisTask mt-2">
                                    <div class="mb-hstryBarord">&nbsp;</div>
                                    <div class="align-items-center itmBody">
                                        <div class="numRef align-items-center">
                                            <div class="tb-bdy srHisclm">
                                                <p>2</p>
                                            </div>
                                            <div class="tb-bdy numItmclm">
                                                <p class="hisNo  hide-hisNo">No. V0002349</p>
                                                <p class="hisOrd">#10221855</p>
                                            </div>
                                            <div class="tb-bdy hisDateclm">
                                                <p class="fstDt">23.05.22</p>
                                                <p class="lstDt">23.05.22</p>
                                            </div>
                                            <div class="tb-bdy hisTypclm">
                                                <div class="d-flex align-items-center hisReq-typ">
                                                    <div class="reqBar-gr">&nbsp;</div>
                                                    <p>Order</p>
                                                </div>
                                               
                                            </div>
                                            <div class="tb-bdy hisRefrclm">
                                                <p class="refTomember">Supplier</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy hisValclm">
                                            <p class="dolValcurr">174.30 $</p>
                                            <p class="othrValcurr">357,900 Tzs</p>
                                        </div>
                                        <div class="stsHiscol d-flex align-items-center">
                                            <div class="tb-bdy hisStatusclm">
                                                <p class="his-pendStatus">Pending</p>
                                            </div>
                                            <div class="tb-bdy hisAcntclm">
                                                <p class="hisAccount">Safe 02 $</p>
                                            </div>
                                        </div>

                                        <div class="tb-bdy shrtHisclm">
                                            <div class="mb-Acntdetail">
                                                <div class="tb-bdy">
                                                    <p>Account</p>
                                                    <p class="mb-Acntnum"></p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-end his-Paybtn">
                                                <div
                                                    class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                    <a href="javascript:void(0)">
                                                        <p class="h3">Pay</p>
                                                    </a>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="doc-bx text-center d-flex justify-content-center align-items-center position-relative">
                                                        <a href="javascript:void(0)" class="dropdown-toggle runLink"
                                                            role="button" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <span class="docMent"></span>
                                                            <p class="btn2">Documents <i
                                                                    class="fa-solid fa-angle-down"></i>
                                                            </p>
                                                        </a>

                                                        <ul class="dropdown-menu">
                                                          
                                                          <li><a class="dropdown-item"
                                                                  href="javascript:void(0)"><i class="far fa-square pe-2"></i>View Details</a>
                                                          </li>
                                                          <li><a class="dropdown-item"
                                                                    href="javascript:void(0)"><i class="fas  fa-share-square pe-2"></i>View Supplier Details</a>
                                                            </li>
                                                          
                                                      </ul>
                                                    </div>
                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="dlTe"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                                <div class="hisTask mt-2">
                                    <div class="mb-hstryBarord">&nbsp;</div>
                                    <div class="align-items-center itmBody">
                                        <div class="numRef align-items-center">
                                            <div class="tb-bdy srHisclm">
                                                <p>3</p>
                                            </div>
                                            <div class="tb-bdy numItmclm">
                                                <p class="hisNo hide-hisNo">No. V0002349</p>
                                                <p class="hisOrd">#10221855</p>
                                            </div>
                                            <div class="tb-bdy hisDateclm">
                                                <p class="fstDt">23.05.22</p>
                                                <p class="lstDt">23.05.22</p>
                                            </div>
                                            <div class="tb-bdy hisTypclm">
                                                <div class="d-flex align-items-center hisReq-typ">
                                                    <div class="reqBar-gr">&nbsp;</div>
                                                    <p>Order</p>
                                                </div>
                                            </div>
                                            <div class="tb-bdy hisRefrclm">
                                                <p class="refTomember">Supplier</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy hisValclm">
                                            <p class="dolValcurr">174.30 $</p>
                                            <!-- <p class="othrValcurr">357,900 Tzs</p> 
                                        </div>
                                        <div class="stsHiscol d-flex align-items-center">
                                            <div class="tb-bdy hisStatusclm">
                                                <p class="his-paidStatus">Paid</p>
                                            </div>
                                            <div class="tb-bdy hisAcntclm">
                                                <p class="hisAccount">Safe 02 $</p>
                                            </div>
                                        </div>

                                        <div class="tb-bdy shrtHisclm">
                                            <div class="mb-Acntdetail">
                                                <div class="tb-bdy">
                                                    <p>Account</p>
                                                    <p class="mb-Acntnum"></p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-end his-Paybtn">
                                                <div
                                                    class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                    <a href="javascript:void(0)">
                                                        <p class="h3">Pay</p>
                                                    </a>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="doc-bx text-center d-flex justify-content-center align-items-center position-relative">
                                                        <a href="javascript:void(0)" class="dropdown-toggle runLink"
                                                            role="button" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <span class="docMent"></span>
                                                            <p class="btn2">Documents <i
                                                                    class="fa-solid fa-angle-down"></i>
                                                            </p>
                                                        </a>

                                                        <ul class="dropdown-menu">
                                                          
                                                          <li><a class="dropdown-item"
                                                                  href="javascript:void(0)"><i class="far fa-square pe-2"></i>View Details</a>
                                                          </li>
                                                          <li><a class="dropdown-item"
                                                                    href="javascript:void(0)"><i class="fas  fa-share-square pe-2"></i>View Supplier Details</a>
                                                            </li>
                                                          
                                                      </ul>
                                                    </div>
                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="dlTe"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                                <div class="hisTask mt-2">
                                    <div class="mb-hstryBarstk">&nbsp;</div>
                                    <div class="align-items-center itmBody">
                                        <div class="numRef align-items-center">
                                            <div class="tb-bdy srHisclm">
                                                <p>4</p>
                                            </div>
                                            <div class="tb-bdy numItmclm">
                                                <p class="hisNo  hide-hisNo">No. V0002349</p>
                                                <p class="hisOrd">#10221855</p>
                                            </div>
                                            <div class="tb-bdy hisDateclm">
                                                <p class="fstDt">23.05.22</p>
                                                <p class="lstDt">23.05.22</p>
                                            </div>
                                            <div class="tb-bdy hisTypclm">
                                                <div class="d-flex align-items-center hisStk-typ">
                                                    <div class="stckBar-bl">&nbsp;</div>
                                                    <p>Stock Take</p>
                                                </div>
                                            </div>
                                            <div class="tb-bdy hisRefrclm">
                                                <p class="refTomember">Storage</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy hisValclm">
                                            <p class="dolValcurr">174.30 $</p>
                                            <p class="othrValcurr-ngive">-144.30 $</p>
                                        </div>
                                        <div class="tb-bdy shrtHisclm stkCol-blnk">
                                            <div class="d-flex align-items-center justify-content-end his-Paybtn">
                                                <div
                                                    class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                    <a href="javascript:void(0)" class="runLink">
                                                        <span class="vwDtl"></span>
                                                    </a>
                                                </div>
                                                <div
                                                    class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                    <a href="javascript:void(0)" class="runLink">
                                                        <span class="dlTe"></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                                <div class="hisTask mt-2">
                                    <div class="mb-hstryBareq">&nbsp;</div>
                                    <div class="align-items-center itmBody">
                                        <div class="numRef align-items-center">
                                            <div class="tb-bdy srHisclm">
                                                <p>5</p>
                                            </div>
                                            <div class="tb-bdy numItmclm">
                                                <p class="hisNo hide-hisNo">No. V0002349</p>
                                                <p class="hisOrd">#10221855</p>
                                            </div>
                                            <div class="tb-bdy hisDateclm">
                                                <p class="fstDt">23.05.22</p>
                                                <p class="lstDt">23.05.22</p>
                                            </div>
                                            <div class="tb-bdy hisTypclm">
                                                <div class="d-flex align-items-center hisOrd-typ">
                                                    <div class="ordBar-rd">&nbsp;</div>
                                                    <p>Requisition</p>
                                                </div>
                                            </div>
                                            <div class="tb-bdy hisRefrclm">
                                                <p class="refTomember">Member</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy hisValclm">
                                            <p class="dolValcurr">174.30 $</p>
                                            <p class="othrValcurr">357,900 Tzs</p> 
                                        </div>
                                        <div class="stsHiscol d-flex align-items-center">
                                            <div class="tb-bdy hisStatusclm">
                                                <p class="his-recStatus">Received</p>
                                            </div>
                                            <div class="tb-bdy hisAcntclm">
                                                <p class="hisAccount">Safe 02 $</p>
                                            </div>
                                        </div>

                                        <div class="tb-bdy shrtHisclm">
                                            <div class="mb-Acntdetail">
                                                <div class="tb-bdy">
                                                    <p>Account</p>
                                                    <p class="mb-Acntnum"></p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-end his-Paybtn">
                                                <div
                                                    class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                    <a href="javascript:void(0)">
                                                        <p class="h3">Inv</p>
                                                    </a>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="doc-bx text-center d-flex justify-content-center align-items-center position-relative">
                                                        <a href="javascript:void(0)" class="dropdown-toggle runLink"
                                                            role="button" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <span class="docMent"></span>
                                                            <p class="btn2">Documents <i
                                                                    class="fa-solid fa-angle-down"></i>
                                                            </p>
                                                        </a>

                                                        <ul class="dropdown-menu">
                                                          
                                                          <li><a class="dropdown-item"
                                                                  href="javascript:void(0)"><i class="far fa-square pe-2"></i>View Details</a>
                                                          </li>
                                                          
                                                      </ul>
                                                    </div>
                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="dlTe"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div> -->
                                <!-- Item Table Body End -->
                            </div>
                        </div>
                    </section>

                </section>

            </div>
        </div>
    </div>

    <!-- View Details Popup Start -->
    <div class="modal" tabindex="-1" id="view_details" aria-labelledby="view-details" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md site-modal">
            <div class="modal-content p-2">
                <div class="modal-header pb-3">
                    <div class="d-md-flex align-items-center justify-content-between w-100 ">
                        <div class="d-flex align-items-start w-100 gap-3 w-auto mb-md-0 mb-2">

                            <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#modalfiltertop">
                                <i class="fa fa-filter"></i>
                            </button>

                            <div class="collapse" id="modalfiltertop">
                                <div class="d-flex gap-3 modal-head-row">


                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                            Headers<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="1">
                                                <span class="fs-13">Address</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="2">
                                                <span class="fs-13">Order details</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="3">
                                                <span class="fs-13">Logo</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="4">
                                                <span class="fs-13">Current Date</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                            Summary<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="1">
                                                <span class="fs-13">Address</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="2">
                                                <span class="fs-13">Order details</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="3">
                                                <span class="fs-13">Logo</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="4">
                                                <span class="fs-13">Current Date</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                            Item Table<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="1">
                                                <span class="fs-13">Address</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="2">
                                                <span class="fs-13">Order details</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="3">
                                                <span class="fs-13">Logo</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="4">
                                                <span class="fs-13">Current Date</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                            Task Record<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="1">
                                                <span class="fs-13">Address</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="2">
                                                <span class="fs-13">Order details</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="3">
                                                <span class="fs-13">Logo</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="4">
                                                <span class="fs-13">Current Date</span>
                                            </li>
                                        </ul>
                                    </div>

                                </div>

                            </div>



                        </div>
                        <a href="historypdf.html" class="btn" target="_blank"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
                    </div>
                </div>
                <div class="modal-body px-2 py-3">
                    <div class="row pb-3">
                        <div class="col-md-4">
                            <div class="modal-address ">
                                <h6 class="semibold fs-14">Our Zazibar</h6>
                                <div class="fs-13 ">
                                    <p>P.o Box 4146</p>
                                    <p>Jambiani</p>
                                    <p>Zanzibar, TANZANIA</p>
                                    <p>inventory@our-zanzibar.com</p>
                                    <p>+255743419217</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h4 class="text-center semibold">Order details</h4>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="modal-logo">
                                <img src="Assets/icons/logo_Q.svg" alt="Logo">
                            </div>
                            <div class="modal-date pt-1">
                                <p>08/03/2024</p>
                            </div>
                        </div>
                    </div>

                    <table class="modal-table fs-12 w-100">
                        <tbody>
                            <tr>
                                <td class="medium">Task No.</td>
                                <td class="medium">Supplier</td>
                                <td class="p-0">
                                    <table class="w-100">
                                        <tr>
                                            <td width="40%">&nbsp;</td>
                                            <td width="40%" class="medium">Total</td>
                                            <td width="20%">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr class="thead">
                                <td>123095</td>
                                <td>Active Store</td>
                                <td class="p-0">
                                    <table class="w-100">
                                        <tr>
                                            <td width="40%">&nbsp;</td>
                                            <td width="40%">29.28$</td>
                                            <td width="20%">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td width="33%" valign="top">
                                    <table class="w-100">
                                        <tr>
                                            <td># Supplier Invoice</td>
                                            <td>3425234</td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="33%"></td>
                                <td width="34%">
                                    <table class="w-100">
                                        <tr>
                                            <td>Sub Total</td>
                                            <td>157 $</td>
                                            <td>138.888 €</td>
                                        </tr>
                                        <tr>
                                            <td>discount</td>
                                            <td>157 $</td>
                                            <td>138.888 €</td>
                                        </tr>
                                        <tr>
                                            <td>Grand Total</td>
                                            <td>157 $</td>
                                            <td>138.888 €</td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                    <table class="modal-table fs-12 w-100 mt-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Item</th>
                                <th>Barcode</th>
                                <th>Price</th>
                                <th>Unit</th>
                                <th>Qty.</th>
                                <th>Rec Qty.</th>
                                <th>Total($)</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><img src="Assets/images/Tomato.png" alt="Item" class="imgItm"></td>
                                <td>Bitter Lemon 300 Ml</td>
                                <td>569856285</td>
                                <td>5.32 $</td>
                                <td>box</td>
                                <td>3</td>
                                <td>3</td>
                                <td>13.32 $</td>
                                <td>Lorem Ipsum dolor sit amet</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td><img src="Assets/images/Tomato.png" alt="Item" class="imgItm"></td>
                                <td>Bitter Lemon 300 Ml</td>
                                <td>569856285</td>
                                <td>5.32 $</td>
                                <td>box</td>
                                <td>3</td>
                                <td>3</td>
                                <td>13.32 $</td>
                                <td>Lorem Ipsum dolor sit amet</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td><img src="Assets/images/Tomato.png" alt="Item" class="imgItm"></td>
                                <td>Bitter Lemon 300 Ml</td>
                                <td>569856285</td>
                                <td>5.32 $</td>
                                <td>box</td>
                                <td>3</td>
                                <td>3</td>
                                <td>13.32 $</td>
                                <td>Lorem Ipsum dolor sit amet</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td><img src="Assets/images/Tomato.png" alt="Item" class="imgItm"></td>
                                <td>Bitter Lemon 300 Ml</td>
                                <td>569856285</td>
                                <td>5.32 $</td>
                                <td>box</td>
                                <td>3</td>
                                <td>3</td>
                                <td>13.32 $</td>
                                <td>Lorem Ipsum dolor sit amet</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="modal-table fs-12 w-100 mt-4">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Date</th>
                                <th>User</th>
                                <th>Price($)</th>
                                <th>Price(€)</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Submit</td>
                                <td>27/02/2024 01:30AM</td>
                                <td>Our Zazibar(Admin)</td>
                                <td>29.28 $</td>
                                <td>33.54 €</td>
                                <td>Lorem Ipsum dolor sit amet</td>
                            </tr>

                            <tr>
                                <td>Submit</td>
                                <td>27/02/2024 01:30AM</td>
                                <td>Our Zazibar(Admin)</td>
                                <td>29.28 $</td>
                                <td>33.54 €</td>
                                <td>Lorem Ipsum dolor sit amet</td>
                            </tr>

                            <tr>
                                <td>Submit</td>
                                <td>27/02/2024 01:30AM</td>
                                <td>Our Zazibar(Admin)</td>
                                <td>29.28 $</td>
                                <td>33.54 €</td>
                                <td>Lorem Ipsum dolor sit amet</td>
                            </tr>

                            <tr>
                                <td>Submit</td>
                                <td>27/02/2024 01:30AM</td>
                                <td>Our Zazibar(Admin)</td>
                                <td>29.28 $</td>
                                <td>33.54 €</td>
                                <td>Lorem Ipsum dolor sit amet</td>
                            </tr>

                            <tr>
                                <td>Submit</td>
                                <td>27/02/2024 01:30AM</td>
                                <td>Our Zazibar(Admin)</td>
                                <td>29.28 $</td>
                                <td>33.54 €</td>
                                <td>Lorem Ipsum dolor sit amet</td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- View Detail Popup End -->



    <!-- raw materiels Popup Start -->
    <div class="modal" tabindex="-1" id="Raw_Convert_Item_details" aria-labelledby="Raw-Convert-Item-details" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md site-modal">
            <div class="modal-content p-2">
                <div class="modal-header pb-3">
                    <div class="d-md-flex align-items-center justify-content-end w-100">
                        <a href="./raw_material_pdf.html" class="btn" target="_blank"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
                    </div>
                </div>
                <div class="modal-body px-2 py-3">
                    <div class="test-center">
                        <h4 class="text-center semibold">Raw Convert Item details</h4>
                    </div>

                    <table class="modal-table fs-12 w-100 mt-4">
                        <thead style="background-color: white !important;">
                            <tr>
                                <th>Task no.</th>
                                <th>Order by</th>
                                <th>Date</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody style="background-color: rgb(122 137 255 / 20%);">
                            <tr>
                                <td>110001</td>
                                <td>Our Zazibar</td>
                                <td>28-03-24 10:10</td>
                                <td>11.04 $</td>
                            </tr>
                        </tbody>
                    </table>


                    <br>
                    <table class="modal-table fs-12 w-100 mt-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Bar Code</th>
                                <th>Price</th>
                                <th>P.Unit</th>
                                <th>Converted Qty</th>
                                <th>Qty Before</th>
                                <th>Qty After</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Chicken kg</td>
                                <td>998889998</td>
                                <td>11.0b $</td>
                                <td>KG</td>
                                <td>1</td>
                                <td>55</td>
                                <td>54</td>
                                <td>11.03 $</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Chicken 150g</td>
                                <td>998889998</td>
                                <td>0.92b $</td>
                                <td>Portion</td>
                                <td>12</td>
                                <td>43</td>
                                <td>55</td>
                                <td>11.04 $</td>
                            </tr>

                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>
    <!-- raw materiels 1 Popup End -->

    <!-- stock take details Popup Start -->
    <div class="modal" tabindex="-1" id="Stock_take_details" aria-labelledby="Stock-take-details" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md site-modal">
            <div class="modal-content p-2">
                <div class="modal-header pb-3">
                    <div class="d-md-flex align-items-center justify-content-end w-100">
                        <a href="./Stock_take_details_pdf.html" class="btn" target="_blank"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
                    </div>
                </div>
                <div class="modal-body px-2 py-3">
                    <div class="test-center">
                        <h4 class="text-center semibold">Stock take details <br></h4>
                        <h4 style="font-size: 12px; text-align:center; margin-top:-.5rem;"> Dry Goods Central Store</h4>
                    </div>

                    <table class="modal-table fs-12 w-100 mt-4">
                        <thead style="background-color: white !important;">
                            <tr>
                                <th>Task no.</th>
                                <th>Order by</th>
                                <th>Date</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody style="background-color: rgb(122 137 255 / 20%);">
                            <tr>
                                <td>110001</td>
                                <td>Our Zazibar</td>
                                <td>28-03-24 10:10</td>
                                <td>11.04 $</td>
                            </tr>
                        </tbody>
                    </table>


                    <br>
                    <table class="modal-table fs-12 w-100 mt-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Bar Code</th>
                                <th>Unit</th>
                                <th>Stock Qty</th>
                                <th>Stock Take</th>
                                <th>Variances</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>pen</td>
                                <td>998889998</td>
                                <td>Pc</td>
                                <td>3</td>
                                <td>3</td>
                                <td>0</td>
                                <td>11.03 $</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>pen</td>
                                <td>998889998</td>
                                <td>Pc</td>
                                <td>3</td>
                                <td>3</td>
                                <td>0</td>
                                <td>11.03 $</td>
                            </tr>

                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>
    <!-- stock take details Popup End -->

    <!-- View payment pending Details Popup Start -->
    <div class="modal" tabindex="-1" id="view_payment" aria-labelledby="view-payment" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md site-modal">
            <div class="modal-content p-2">
                <div class="modal-header pb-3">
                    <div class="d-md-flex align-items-center justify-content-end w-100">
                        <a href="supplierPaymentDetail_pdf.html" class="btn" target="_blank"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
                    </div>
                </div>
                <div class="modal-body p-0">
                    <!--payment pending Details Popup Start -->
                    <div class="payment-status-left payment-pending position-relative">
                        <div class="border-line"></div>
                        <div class=" p-5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="f-01 mb-0 payment-status-text">PENDING</p>
                                    <p class="f-01">PAYMENT</p>
                                </div>
                                <div>
                                    logo here
                                    <!-- <img src="" alt=""> -->
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-between payment-tabel-header">
                                <table class="table1 table01">
                                    <tbody>
                                        <tr>
                                            <td class="font-wt">
                                                Supplier invoice #
                                            </td>
                                            <td>1233</td>
                                        </tr>

                                        <tr>
                                            <td class="font-wt">
                                                Payment #
                                            </td>
                                            <td>001538</td>
                                        </tr>

                                        <tr>
                                            <td class="font-wt">Task #</td>
                                            <td>126515</td>
                                        </tr>

                                        <tr>
                                            <td class="font-wt">Date #
                                            </td>
                                            <td>2024-06-23 07:23:50</td>
                                        </tr>

                                    </tbody>
                                </table>

                                <table>
                                    <tbody class="table1 table01 fl-right cmp-dtl text-end">
                                        <tr>
                                            <td>Our Zazibar</td>
                                        </tr>
                                        <tr>
                                            <td>P.o Box 4146,Jambiani </td>
                                        </tr>
                                        <tr>
                                            <td>Zanzibar,TANZANIA </td>
                                        </tr>
                                        <tr>
                                            <td>inventory@our-zanzibar.com</td>
                                        </tr>
                                        <tr>
                                            <td>+255742998277</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                            <br>
                            <div class="">
                                <p class="f-02 mb-0">Payment To: </p>
                                <p class="f-03 mb-0">Green Grocery</p>
                            </div>
                            <br>

                            <table class="modal-table fs-12 w-100 mt-4">
                                <thead style="background: #A9B0C0 !important;">
                                    <tr class="tr-bg-1">
                                        <th>#</th>
                                        <th style="width: 30%;">Item</th>
                                        <th>Unit</th>
                                        <th>Ordered <br> Quantity</th>
                                        <th>Receive <br> Quantity</th>
                                        <th class="th-bg-1">Price ($)</th>
                                        <th class="th-bg-1">Total ($)</th>
                                    </tr>
                                </thead>
                                <tbody class="tabel-body-p">
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;">Item</td>
                                        <td>Kg</td>
                                        <td>20</td>
                                        <td>20</td>
                                        <td>3.0000 $</td>
                                        <td>2.0000 $</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;">Item</td>
                                        <td>Kg</td>
                                        <td>20</td>
                                        <td>20</td>
                                        <td>3.0000 $</td>
                                        <td>2.0000 $</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;">Item</td>
                                        <td>Kg</td>
                                        <td>20</td>
                                        <td>20</td>
                                        <td>3.0000 $</td>
                                        <td>2.0000 $</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;">Item</td>
                                        <td>Kg</td>
                                        <td>20</td>
                                        <td>20</td>
                                        <td>3.0000 $</td>
                                        <td>2.0000 $</td>
                                    </tr>
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



                </div>
            </div>
            <!-- payment Detail Popup End -->
        </div>

    </div>
    <!-- View payment pending Popup End -->

    <!-- View payment sucess Details Popup Start -->
    <div class="modal" tabindex="-1" id="view_payment_paid" aria-labelledby="view-payment-paid" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md site-modal">
            <div class="modal-content p-2">
                <div class="modal-header pb-3">
                    <div class="d-md-flex align-items-center justify-content-end w-100">
                        <a href="supplierSuccessPayment_pdf.html" class="btn" target="_blank"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
                    </div>
                </div>
                <div class="modal-body p-0">
                    <!--payment paid Details Popup Start -->
                    <div class="payment-status-left payment-paid position-relative">
                        <div class="border-line"></div>
                        <div class="modal-body p-5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="f-01 mb-0 payment-status-text">PAID</p>
                                    <p class="f-01">PAYMENT</p>
                                </div>
                                <div>
                                    logo here
                                    <!-- <img src="" alt=""> -->
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-between payment-tabel-header">
                                <table class="table1 table01">
                                    <tbody>
                                        <tr>
                                            <td class="font-wt">
                                                Supplier invoice #
                                            </td>
                                            <td>1233</td>
                                        </tr>

                                        <tr>
                                            <td class="font-wt">
                                                Payment #
                                            </td>
                                            <td>001538</td>
                                        </tr>

                                        <tr>
                                            <td class="font-wt">Task #</td>
                                            <td>126515</td>
                                        </tr>

                                        <tr>
                                            <td class="font-wt">Date #
                                            </td>
                                            <td>2024-06-23 07:23:50</td>
                                        </tr>

                                    </tbody>
                                </table>

                                <table>
                                    <tbody class="table1 table01 fl-right cmp-dtl text-end">
                                        <tr>
                                            <td>Our Zazibar</td>
                                        </tr>
                                        <tr>
                                            <td>P.o Box 4146,Jambiani </td>
                                        </tr>
                                        <tr>
                                            <td>Zanzibar,TANZANIA </td>
                                        </tr>
                                        <tr>
                                            <td>inventory@our-zanzibar.com</td>
                                        </tr>
                                        <tr>
                                            <td>+255742998277</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                            <br>
                            <div class="">
                                <p class="f-02 mb-0">Payment To: </p>
                                <p class="f-03 mb-0">Green Grocery</p>
                            </div>
                            <br>

                            <table class="modal-table fs-12 w-100 mt-4">
                                <thead style="background: #A9B0C0 !important;">
                                    <tr class="tr-bg-1">
                                        <th>#</th>
                                        <th style="width: 30%;">Item</th>
                                        <th>Unit</th>
                                        <th>Ordered <br> Quantity</th>
                                        <th>Receive <br> Quantity</th>
                                        <th class="th-bg-1">Price ($)</th>
                                        <th class="th-bg-1">Total ($)</th>
                                    </tr>
                                </thead>
                                <tbody class="tabel-body-p">
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;">Item</td>
                                        <td>Kg</td>
                                        <td>20</td>
                                        <td>20</td>
                                        <td>3.0000 $</td>
                                        <td>2.0000 $</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;">Item</td>
                                        <td>Kg</td>
                                        <td>20</td>
                                        <td>20</td>
                                        <td>3.0000 $</td>
                                        <td>2.0000 $</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;">Item</td>
                                        <td>Kg</td>
                                        <td>20</td>
                                        <td>20</td>
                                        <td>3.0000 $</td>
                                        <td>2.0000 $</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;">Item</td>
                                        <td>Kg</td>
                                        <td>20</td>
                                        <td>20</td>
                                        <td>3.0000 $</td>
                                        <td>2.0000 $</td>
                                    </tr>
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
                                    <tbody><tr>
                                        <td>Sub Total </td>
                                        <td>216.5668 $</td>
                                    </tr>
                                    <tr>
                                        <td>Storage fee</td>
                                        <td>21.6567 $</td>
                                    </tr>

                                    <tr class="grand-total" style=" max-height: 38px;">
                                        <th>Grand Total ($) </th>
                                        <th> 238.2235 $ </th>
                                    </tr>
                                    <tr></tr>
                                </tbody></table>

                            </div>

                            <br>
                            <br>
                            <br>
                        </div>
                    </div>
                    <!-- payment Detail Popup End -->



                </div>
            </div>
            <!-- payment Detail Popup End -->
        </div>

    </div>
    <!-- View payment sucess Popup End -->


    <!-- View invoice pending Details Popup Start -->
    <div class="modal" tabindex="-1" id="view_invoice" aria-labelledby="view_invoice" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md site-modal">
            <div class="modal-content p-2">
                <div class="modal-header pb-3">
                    <div class="d-md-flex align-items-center justify-content-end w-100">
                        <a href="requisitionPaymentDetail_pdf.html" class="btn" target="_blank"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
                    </div>
                </div>
                <div class="modal-body m-0 p-0">
                    <!-- invoice Details Start -->
                    <div id="invoice-Details" class="payment-status-left payment-pending position-relative">
                        <div class="border-line"></div>
                        <div class=" p-5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="f-01 mb-0 payment-status-text">PENDING</p>
                                    <p class="f-01">INVOICE</p>
                                </div>
                                <div>
                                    logo here
                                    <!-- <img src="" alt=""> -->
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-between payment-tabel-header">
                                <table class="table1 table01">
                                    <tbody>
                                        <tr>
                                            <td class="font-wt">
                                                Invoice #
                                            </td>
                                            <td>001538</td>
                                        </tr>

                                        <tr>
                                            <td class="font-wt">Task #</td>
                                            <td>126515</td>
                                        </tr>

                                        <tr>
                                            <td class="font-wt">Date #
                                            </td>
                                            <td>2024-06-23 07:23:50</td>
                                        </tr>

                                    </tbody>
                                </table>

                                <table>
                                    <tbody class="table1 table01 fl-right cmp-dtl text-end">
                                        <tr>
                                            <td>Our Zazibar</td>
                                        </tr>
                                        <tr>
                                            <td>P.o Box 4146,Jambiani </td>
                                        </tr>
                                        <tr>
                                            <td>Zanzibar,TANZANIA </td>
                                        </tr>
                                        <tr>
                                            <td>inventory@our-zanzibar.com</td>
                                        </tr>
                                        <tr>
                                            <td>+255742998277</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                            <br>
                            <div class="">
                                <p class="f-02 mb-0">Invoice To: </p>
                                <p class="f-03 mb-0">Casa Bar</p>
                            </div>
                            <br>

                            <table class="modal-table fs-12 w-100 mt-4">
                                <thead style="background: #A9B0C0 !important;">
                                    <tr class="tr-bg-1">
                                        <th>#</th>
                                        <th style="width: 30%;">Item</th>
                                        <th>Unit</th>
                                        <th>Quantity</th>
                                        <th class="th-bg-1">Price ($)</th>
                                        <th class="th-bg-1">Total ($)</th>
                                    </tr>
                                </thead>
                                <tbody class="tabel-body-p">
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;">Item</td>
                                        <td>Kg</td>
                                        <td>20</td>
                                        <td>3.0000 $</td>
                                        <td>2.0000 $</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;">Item</td>
                                        <td>Kg</td>
                                        <td>20</td>
                                        <td>3.0000 $</td>
                                        <td>2.0000 $</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;">Item</td>
                                        <td>Kg</td>
                                        <td>20</td>
                                        <td>3.0000 $</td>
                                        <td>2.0000 $</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;">Item</td>
                                        <td>Kg</td>
                                        <td>20</td>
                                        <td>3.0000 $</td>
                                        <td>2.0000 $</td>
                                    </tr>
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

                                    <tbody>
                                        <tr class="grand-total" style=" max-height: 38px;">
                                            <th>Grand Total ($) </th>
                                            <th> 238.2235 $ </th>
                                        </tr>
                                        <tr></tr>
                                    </tbody>
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
            <!-- payment Detail Popup End -->
        </div>

    </div>
    <!-- View invoice Popup End -->

    <!-- View invoice recived Details Popup Start -->
    <div class="modal" tabindex="-1" id="view_invoice_recived" aria-labelledby="view-invoice-recived" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md site-modal">
            <div class="modal-content p-2">
                <div class="modal-header pb-3">
                    <div class="d-md-flex align-items-center justify-content-end w-100">
                        <a href="requisitionSuccessPayment_pdf.html" class="btn" target="_blank"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
                    </div>
                </div>
                <div class="modal-body m-0 p-0">
                    <!-- invoice Details Start -->
                    <div class="payment-status-left recived-invoice position-relative">
                        <div class="border-line"></div>
                        <div class="modal-body p-5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="f-01 mb-0 payment-status-text">RECEIVED</p>
                                    <p class="f-01">INVOICE</p>
                                </div>
                                <div>
                                    logo here
                                    <!-- <img src="" alt=""> -->
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-between payment-tabel-header">
                                <table class="table1 table01">
                                    <tbody>
                                        <tr>
                                            <td class="font-wt">
                                                Invoice #
                                            </td>
                                            <td>001538</td>
                                        </tr>

                                        <tr>
                                            <td class="font-wt">Task #</td>
                                            <td>126515</td>
                                        </tr>

                                        <tr>
                                            <td class="font-wt">Date #
                                            </td>
                                            <td>2024-06-23 07:23:50</td>
                                        </tr>

                                    </tbody>
                                </table>

                                <table>
                                    <tbody class="table1 table01 fl-right cmp-dtl text-end">
                                        <tr>
                                            <td>Our Zazibar</td>
                                        </tr>
                                        <tr>
                                            <td>P.o Box 4146,Jambiani </td>
                                        </tr>
                                        <tr>
                                            <td>Zanzibar,TANZANIA </td>
                                        </tr>
                                        <tr>
                                            <td>inventory@our-zanzibar.com</td>
                                        </tr>
                                        <tr>
                                            <td>+255742998277</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                            <br>
                            <div class="">
                                <p class="f-02 mb-0">Invoice To: </p>
                                <p class="f-03 mb-0">Casa Bar</p>
                                <p class="f-03 mb-0">Casa Hotel</p>
                                <p class="f-03 mb-0">07711122</p>
                            </div>
                            <br>

                            <table class="modal-table fs-12 w-100 mt-4">
                                <thead style="background: #A9B0C0 !important;">
                                    <tr class="tr-bg-1">
                                        <th>#</th>
                                        <th style="width: 30%;">Item</th>
                                        <th>Unit</th>
                                        <th>Quantity</th>
                                       
                                        <th class="th-bg-1">Price ($)</th>
                                        <th class="th-bg-1">Total ($)</th>
                                    </tr>
                                </thead>
                                <tbody class="tabel-body-p">
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;">Item</td>
                                        <td>Kg</td>
                                        <td>20</td>
                                        <td>3.0000 $</td>
                                        <td>2.0000 $</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;">Item</td>
                                        <td>Kg</td>
                                        <td>20</td>
                                        <td>3.0000 $</td>
                                        <td>2.0000 $</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;">Item</td>
                                        <td>Kg</td>
                                        <td>20</td>
                                        <td>3.0000 $</td>
                                        <td>2.0000 $</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td style="width: 30%;">Item</td>
                                        <td>Kg</td>
                                        <td>20</td>
                                        <td>3.0000 $</td>
                                        <td>2.0000 $</td>
                                    </tr>
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
                                    <tbody><tr>
                                        <td>Sub Total </td>
                                        <td>216.5668 $</td>
                                    </tr>
                                    <tr>
                                        <td>Storage fee</td>
                                        <td>21.6567 $</td>
                                    </tr>

                                    <tr class="grand-total" style=" max-height: 38px;">
                                        <th>Grand Total ($) </th>
                                        <th> 238.2235 $ </th>
                                    </tr>
                                    <tr></tr>
                                </tbody></table>

                            </div>

                            <br>
                            <br>
                            <br>
                        </div>
                    </div>
                    <!-- invoice Detail End -->
                </div>
            </div>
            <!-- payment Detail Popup End -->
        </div>

    </div>
    <!-- View invoice Popup End -->

    <!-- View ckecknox Popup Start -->
    <div class="modal" tabindex="-1" id="checkbox_module" aria-labelledby="checkbox_module" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md site-modal">
            <div class="modal-content ">
                <div>
                    <div class="mdlHead-Popup d-flex justify-content-between align-items-center">
                        <span>
                            <strong>Check columns to show in list:</strong>
                        </span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <br>

                    <strong class="checkAllSectionBox">
                        <input type="checkbox" class="CheckAllOptions" id="CheckAllOptions">
                        <label>
                            Check all
                        </label>
                    </strong>

                    <form method="post" action="" class="shortList-ChkAll">
                        <input type="checkbox" id="optionCheck" class="optionCheck" name="showFields[]" checked="checked" value="1">&nbsp;Number<br>

                        <input type="checkbox" id="optionCheck" class="optionCheck" name="showFields[]" checked="checked" value="2">&nbsp;Submit date<br>

                        <input type="checkbox" id="optionCheck" class="optionCheck" name="showFields[]" checked="checked" value="3">&nbsp;User<br>

                        <input type="checkbox" id="optionCheck" class="optionCheck" name="showFields[]" checked="checked" value="4">&nbsp;Type<br>

                        <input type="checkbox" id="optionCheck" class="optionCheck" name="showFields[]" checked="checked" value="7">&nbsp;Refer to<br>

                        <input type="checkbox" id="optionCheck" class="optionCheck" name="showFields[]" checked="checked" value="8">&nbsp;Supplier invoice no.<br>

                        <input type="checkbox" id="optionCheck" class="optionCheck" name="showFields[]" checked="checked" value="10">&nbsp;Value<br>

                        <input type="checkbox" id="optionCheck" class="optionCheck" name="showFields[]" checked="checked" value="14">&nbsp;Payment status<br>

                        <input type="checkbox" id="optionCheck" class="optionCheck" name="showFields[]" checked="checked" value="15">&nbsp;Payment no.<br>

                        <input type="checkbox" id="optionCheck" class="optionCheck" name="showFields[]" checked="checked" value="16">&nbsp;Invoice no.<br>

                        <input type="checkbox" id="optionCheck" class="optionCheck" name="showFields[]" checked="checked" value="17">&nbsp;Account<br>


                        <br>
                        <p>
                            <button class="btn btn-secondary dropdown-toggle fs-13 py-2" style="border:1px solid #7a89ff; background-color: #7a89ff; color: #fff;"> Show</button>
                            <button class="btn btn-secondary dropdown-toggle fs-13 py-2" style="border:1px solid #7a89ff; background-color: #7a89ff; color: #fff;"> Clear filter</button>
                        </p>
                    </form>
                </div>

            </div>

        </div>
    </div>
    <!-- View checkbox Popup End -->


    <!-- ===== History pdf popup new in div format======= -->
    <div class="modal" tabindex="-1" id="history_pdf" aria-labelledby="history_pdfModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md site-modal">
            <div class="modal-content p-2">
                <div class="modal-header pb-3">
                    <div class="d-md-flex align-items-center justify-content-between w-100 ">
                        <div class="d-flex align-items-start w-100 gap-3 w-auto mb-md-0 mb-2 modal-head-btn">

                            <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#modalfiltertop">
                                <i class="fa fa-filter"></i>
                            </button>

                            <div class="collapse" id="modalfiltertop">
                                <div class="d-flex gap-3 modal-head-row">


                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                            Headers<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="form-check-input" value="1">
                                                <span class="fs-13">Check All</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="2">
                                                <span class="fs-13">Address</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="logo" class="form-check-input" value="4">
                                                <span class="fs-13">Logo</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                            Summary<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="form-check-input" value="1">
                                                <span class="fs-13">Check All</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="issuedIn" class="form-check-input" value="2">
                                                <span class="fs-13">Issued in</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="defaultCurrency" class="form-check-input" value="3">
                                                <span class="fs-13">Default currency</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="otherCurrency" class="form-check-input" value="4">
                                                <span class="fs-13">Other currency</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="paid" class="form-check-input" value="5">
                                                <span class="fs-13">Paid</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="pending" class="form-check-input" value="5">
                                                <span class="fs-13">Pending</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="issuedOut" class="form-check-input" value="5">
                                                <span class="fs-13">Issued out</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="received" class="form-check-input" value="5">
                                                <span class="fs-13">Received</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="issuedOutPending" class="form-check-input" value="5">
                                                <span class="fs-13">Issued out pending</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="variances" class="form-check-input" value="5">
                                                <span class="fs-13">Variances</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="accounts" class="form-check-input" value="5">
                                                <span class="fs-13">Accounts</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                            Item Table<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="form-check-input" value="1">
                                                <span class="fs-13">Check All</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="taskNo" class="form-check-input" value="2">
                                                <span class="fs-13">Task no.</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="date" class="form-check-input" value="3">
                                                <span class="fs-13">Date</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="user" class="form-check-input" value="4">
                                                <span class="fs-13">User</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="supInvoiceNo" class="form-check-input" value="5">
                                                <span class="fs-13">Sup invoice no.</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="type" class="form-check-input" value="6">
                                                <span class="fs-13">Type</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="referTo" class="form-check-input" value="7">
                                                <span class="fs-13">Refer to</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="value" class="form-check-input" value="8">
                                                <span class="fs-13">Value</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="defaultCurrencyTotal" class="form-check-input" value="9">
                                                <span class="fs-13">Default currency total</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="secondCurrencyTotal" class="form-check-input" value="10">
                                                <span class="fs-13">Second currency total</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="status" class="form-check-input" value="12">
                                                <span class="fs-13">Status</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="paymentNo" class="form-check-input" value="12">
                                                <span class="fs-13">Payment no.</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="invoiceNo" class="form-check-input" value="12">
                                                <span class="fs-13">Invoice no.</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="accounts" class="form-check-input" value="12">
                                                <span class="fs-13">Accounts</span>
                                            </li>
                                        </ul>
                                    </div>

                                </div>

                            </div>



                        </div>
                        <a href="historypdf.html" class="btn"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
                    </div>
                </div>
                <div class="modal-body px-2 py-3">
                    <div class="row pb-3">
                        <div class="col-6">
                            <div class="modal-address ">
                                <h6 class="semibold fs-14">Our Zazibar</h6>
                                <div class="fs-13 ">
                                    <p>P.o Box 4146</p>
                                    <p>Jambiani</p>
                                    <p>Zanzibar, TANZANIA</p>
                                    <p>inventory@our-zanzibar.com</p>
                                    <p>+255743419217</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <div class="modal-logo">
                                <img src="Assets/icons/logo_Q.svg" alt="Logo">
                            </div>
                        </div>
                    </div>

                    <div class="model-title-with-date">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h6 class="semibold">History report</h6>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-end fs-15"><small>From</small> 16-04-2024 <small>To</small> 19-04-2024</p>
                            </div>
                        </div>
                    </div>

                    <div class="summery-row">
                        <div class="row">
                            <div class="col-7 issueInSection pe-1">
                                <div class="modal-table fs-12 w-100">
                                    <div class="table-row header-row">
                                        <div class="table-cell">&nbsp;</div>
                                        <div class="table-cell medium">Issued in</div>
                                        <div class="table-cell medium">( $ )</div>
                                        <div class="table-cell medium">( € )</div>
                                        <div class="table-cell medium">( Tzs )</div>
                                    </div>
                                    <div class="table-row thead">
                                        <div class="table-cell">Total</div>
                                        <div class="table-cell">5,602.56 $</div>
                                        <div class="table-cell">3,200 $</div>
                                        <div class="table-cell">2973.23 €</div>
                                        <div class="table-cell">5,520,000 Tzs</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Paid </div>
                                        <div class="table-cell font-bold">2,000 $ </div>
                                        <div class="table-cell">1,200 $ </div>
                                        <div class="table-cell">1114.96 € </div>
                                        <div class="table-cell">1,840,000 Tzs </div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Pending</div>
                                        <div class="table-cell font-bold">3,602.56 $</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 issueOutSection pe-1 ps-0">
                                <div class="modal-table fs-12 w-100">
                                    <div class="table-row header-row">
                                        <div class="table-cell">&nbsp;</div>
                                        <div class="table-cell medium">Issued out</div>
                                    </div>
                                    <div class="table-row thead">
                                        <div class="table-cell">Total</div>
                                        <div class="table-cell">4,882.23 $</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Received</div>
                                        <div class="table-cell font-bold">3,602.56 $</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Pending</div>
                                        <div class="table-cell font-bold">1,279.69 $</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 varianceRow ps-0">
                                <div class="modal-table fs-12 w-100">
                                    <div class="table-row header-row">
                                        <div class="table-cell medium">Variance</div>
                                    </div>
                                    <div class="table-row thead">
                                        <div class="table-cell text-success"><i class="fa-solid fa-long-arrow-up pe-1"></i>50 $</div>
                                        <div class="table-cell text-danger"><i class="fa-solid fa-long-arrow-down pe-1"></i>-20 $</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overflowTable">
                        <div class="modal-table fs-12 w-100 mt-4 historyAccountSection">
                            <div class="table-row header-row">
                                <div class="table-cell medium">Accounts</div>
                                <div class="table-cell">&nbsp;</div>
                                <div class="table-cell">&nbsp;</div>
                                <div class="table-cell">&nbsp;</div>
                                <div class="table-cell">&nbsp;</div>
                                <div class="table-cell">&nbsp;</div>
                            </div>
                            <div class="table-row thead">
                                <div class="table-cell">1000 $<small>HDFC bank</small></div>
                                <div class="table-cell">2000 $ <small>UPI bank</small></div>
                                <div class="table-cell">-25 $<small>Yes bank</small></div>
                                <div class="table-cell">1000 $ <small>Paypal</small></div>
                                <div class="table-cell">1000 $<small>ICICI bank</small></div>
                                <div class="table-cell">1000 $ <small>ICICI bank</small></div>
                            </div>
                            <div class="table-row thead">
                                <div class="table-cell">1000 $<small>HDFC bank</small></div>
                                <div class="table-cell">2000 $ <small>UPI bank</small></div>
                                <div class="table-cell">-25 $<small>Yes bank</small></div>
                                <div class="table-cell">&nbsp;</div>
                                <div class="table-cell">&nbsp;</div>
                                <div class="table-cell">&nbsp;</div>
                            </div>
                        </div>
                    </div>

                    <div class="overflowTable">
                        <div class="modal-table fs-12 w-100 mt-4">
                            <div class="table-row thead">
                                <div class="table-cell">#</div>
                                <div class="table-cell">Task no.</div>
                                <div class="table-cell">Date</div>
                                <div class="table-cell">User</div>
                                <div class="table-cell">Supplier <br>Invoice No.</div>
                                <div class="table-cell">Type</div>
                                <div class="table-cell">Refer to</div>
                                <div class="table-cell">Value</div>
                                <div class="table-cell">Status</div>
                                <div class="table-cell">Payment # <br>/Invoice #</div>
                                <div class="table-cell">Account</div>
                            </div>
                            <div class="table-row">
                                <div class="table-cell">1</div>
                                <div class="table-cell">110001</div>
                                <div class="table-cell">02/10/23</div>
                                <div class="table-cell">Saleh</div>
                                <div class="table-cell">55221123</div>
                                <div class="table-cell medium">Issued in</div>
                                <div class="table-cell">Green Grocery</div>
                                <div class="table-cell medium">100 $</div>
                                <div class="table-cell textStatusPaid medium">Paid</div>
                                <div class="table-cell">000202</div>
                                <div class="table-cell">Yes bank</div>
                            </div>
                            <div class="table-row">
                                <div class="table-cell">2</div>
                                <div class="table-cell">110001</div>
                                <div class="table-cell">02/10/23</div>
                                <div class="table-cell">Saleh</div>
                                <div class="table-cell">5234455</div>
                                <div class="table-cell medium">Issued in</div>
                                <div class="table-cell">Active Store</div>
                                <div class="table-cell medium">20 $ <br>50,100 Tzs</div>
                                <div class="table-cell textStatusPending medium">Pending</div>
                                <div class="table-cell">&nbsp;</div>
                                <div class="table-cell">&nbsp;</div>
                            </div>
                            <div class="table-row">
                                <div class="table-cell">3</div>
                                <div class="table-cell">110001</div>
                                <div class="table-cell">02/10/23</div>
                                <div class="table-cell">Saleh</div>
                                <div class="table-cell">&nbsp;</div>
                                <div class="table-cell medium">Issued Out</div>
                                <div class="table-cell">Casa bar</div>
                                <div class="table-cell medium">40 $</div>
                                <div class="table-cell textStatusPending medium">Pending</div>
                                <div class="table-cell">&nbsp;</div>
                                <div class="table-cell">&nbsp;</div>
                            </div>
                            <div class="table-row">
                                <div class="table-cell">4</div>
                                <div class="table-cell">110001</div>
                                <div class="table-cell">02/10/23</div>
                                <div class="table-cell">Saleh</div>
                                <div class="table-cell">&nbsp;</div>
                                <div class="table-cell medium">Issued Out</div>
                                <div class="table-cell">Fun Kitchen</div>
                                <div class="table-cell medium">30 $</div>
                                <div class="table-cell textStatusReceived medium">Received</div>
                                <div class="table-cell">112233</div>
                                <div class="table-cell">HDFC bank</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- ===== History pdf popup new in div format======= -->

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