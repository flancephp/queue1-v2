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
                    <!-- invoice Details Start -->
                    <div id="invoice-Details" class="payment-status-left payment-pending position-relative">
                        <div class="border-line"></div>
                        <div class="modal-body p-5">
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

                                    <tr class="grand-total" style=" max-height: 38px;">
                                        <th class="px-3">Grand Total ($) </th>
                                        <th class="px-3"> 238.2235 $ </th>
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
                                                    <td>invoice to</td>
                                                    <td>
                                                        <input type="text" class="form-control" name="supplierInvoice" id="supplierInvoice" onchange="getVal();" oninput="changeSupInv();" value="Home (Salim )" autocomplete="off">
                                                    </td>
                                                </tr>

                                                <tr class="mb-adrs">
                                                    <td>address</td>
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
                                                    <td >Total amount ($)</td>
                                                    <td>
                                                        <input type="text" style="width: 100%; cursor: text; text-align:center; background:none;" class="form-control form-control-01" name="totalAmount" id="totalAmount" value="90.9088 $" autocomplete="off" readonly="">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <input type="hidden" class="form-control" name="TotalAmt" id="TotalAmt" value="90.9088" autocomplete="off">
                                    <input type="hidden" class="form-control" name="currencyId" id="currencyId" value="" autocomplete="off">

                                    <div class="issueInvoice">
                                    <a class="btn splitBtn-button"  href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#issue-Invoice">Issue invoice</a>
                                </div>
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

                                                        </span>
                                                    </td>
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

     <!-- View Details Popup Start -->
     <div class="modal" tabindex="-1" id="issue-Invoice" aria-labelledby="issue-Invoice" aria-hidden="true">
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
                        <a href="#" class="btn" target="_blank"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
                    </div>
                </div>
                <div class="modal-body m-0 p-0">
                <!-- invoice Details Start -->
                <div id="invoice-Details" class="payment-status-left payment-pending position-relative">
                        <div class="border-line"></div>
                        <div class="modal-body p-5">
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
                    <!-- invoice Detail End -->
            </div>
        </div>
    </div>
    <!-- invoice issue Popup End -->






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