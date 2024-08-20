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

$updateQry = " UPDATE tbl_user SET historyUserFilterFields = '" . implode(',',array_unique($_REQUEST['showFields'])) . "' WHERE id = '" . $_SESSION['id'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ";

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
`otherCur` = '".$resRow['ordCurAmt']."',
`otherCurId` = '".$resRow['ordCurId']."',
`invoiceNo` = '" . $resRow['invNo'] . "',
`orderType` = '" . $resRow['ordType'] . "',
 `notes` = 'Paid',
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


// $cond = '';
// $cond1 = '';

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

if ($_GET['dateType'] == '') {
$cond .= " GROUP BY o.id ORDER BY o.id desc ";
$cond1 = $cond;
}

if ($_GET['dateType'] != '' && $_GET['dateType'] == 1) {
$cond .= " GROUP BY o.id ORDER BY o.ordDateTime desc ";
$cond1 = $cond;
}
if ($_GET['dateType'] != '' && $_GET['dateType'] == 2) {
$cond .= " GROUP BY o.id ORDER BY o.setDateTime desc ";
$cond1 = $cond;
}
if ($_GET['dateType'] != '' && $_GET['dateType'] == 3) {

$cond .= " GROUP BY o.id ORDER BY o.paymentDateTime desc, o.setDateTime desc ";
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

                  1 =>''.showOtherLangText('Number').'',
                  2 =>''.showOtherLangText('Date').'',
                  3 => ''.showOtherLangText('User').'', 
                  4 => ''.showOtherLangText('Type').'',
                  7 =>''.showOtherLangText('Refer To').'',
                  8 => ''.showOtherLangText('Supplier Invoice No.').'',
                  10 => ''.showOtherLangText('Value').'',
                  14 => ''.showOtherLangText('Payment Status').'',
                  15 => ''.showOtherLangText('Payment No.').'',
                  16 => ''.showOtherLangText('Invoice No.').'',
                  17 => ''.showOtherLangText('Account').'',

                ];

                    
                if( isset($_GET['ordType']) )
                {
                   switch($_GET['ordType'])
                   {    
                      case 1: //issuein

                      //unset( $colsArr[5] );
                      unset( $colsArr[6] ); 
                      unset( $colsArr[9] );
                      unset( $colsArr[11] );                    
                      unset( $colsArr[12] );
                      unset( $colsArr[13] );
                      //unset( $colsArr[14] );
                      //unset( $colsArr[15] );
                      unset( $colsArr[16] );
                      //unset( $colsArr[17] );
                      //unset( $colsArr[18] );
                      break;


                      case 2: //issueOut

                      //unset( $colsArr[5] );
                      //unset( $colsArr[7] );
                      unset( $colsArr[8] );
                      unset( $colsArr[9] );
                      unset( $colsArr[11] );
                      //unset( $colsArr[12] );
                      //unset( $colsArr[13] );
                      unset( $colsArr[15] );
                      break;

                      case 3: //stock Take

                      unset( $colsArr[5] );
                      unset( $colsArr[6] );
                      //unset( $colsArr[7] );
                      unset( $colsArr[8] );
                      unset( $colsArr[11] );
                      unset( $colsArr[14] );
                      unset( $colsArr[15] );
                      unset( $colsArr[16] );
                      unset( $colsArr[17] );
                      //unset( $colsArr[18] );
                      break;

                      case 4: //raw item converted


                      unset( $colsArr[5] );
                      unset( $colsArr[6] );
                      unset( $colsArr[7] );
                      unset( $colsArr[8] );
                      unset( $colsArr[9] );
                      unset( $colsArr[11] );
                      //unset( $colsArr[12] );
                      //unset( $colsArr[13] );
                      unset( $colsArr[14] );
                      unset( $colsArr[15] );
                      unset( $colsArr[16] );
                      unset( $colsArr[17] );
                      //unset( $colsArr[18] );
                      break;

                    }
                }

                if ($accessHistoryAccountsPermission['type_id'] == 0) {
                    unset( $colsArr[14] );
                    unset( $colsArr[15] );
                    unset( $colsArr[16] );
                    unset( $colsArr[17] );
                }

                // for paid payment
                if ($_GET['statusType'] == 1) {
                   unset( $colsArr[6] );
                   unset( $colsArr[9] );
                   unset( $colsArr[11] );
                   //unset( $colsArr[12] );
                   //unset( $colsArr[13] );  
                   unset( $colsArr[16] );
                }

                // for received payment
               if ($_GET['statusType'] == 2) {
                    //unset( $colsArr[7] );
                    unset( $colsArr[8] );
                    unset( $colsArr[9] );
                    unset( $colsArr[11] );
                    //unset( $colsArr[12] );
                    //unset( $colsArr[13] );
                    unset( $colsArr[15] );
                }

                // for panding payment
                if ($_GET['statusType'] == 3) {

                    unset( $colsArr[11] );
                    unset( $colsArr[15] );
                    unset( $colsArr[16] );
                    unset( $colsArr[17] );
                }

                // for supplier only
                if ($getTxtById == 'suppId') {
                    unset( $colsArr[11] );
                    unset( $colsArr[6] );
                    unset( $colsArr[9] );
                    //unset( $colsArr[12] );
                    //unset( $colsArr[13] );  
                    unset( $colsArr[16] );
                }

                // for department user only
                if ($getTxtById == 'deptUserId') {
                    unset( $colsArr[8] );
                    unset( $colsArr[9] );
                    unset( $colsArr[11] );
                    //unset( $colsArr[12] );
                    //unset( $colsArr[13] );
                    unset( $colsArr[15] );
                }

                // for store only 
                if ($getTxtById == 'storeId') {
                  unset( $colsArr[5] );
                  unset( $colsArr[6] );
                  unset( $colsArr[8] );
                  unset( $colsArr[11] );
                  unset( $colsArr[14] );
                  unset( $colsArr[15] );
                  unset( $colsArr[16] );
                  unset( $colsArr[17] );
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
<link rel="stylesheet" href="Assets/css/style.css">
<link rel="stylesheet" href="Assets/css/style1.css">
<link rel="stylesheet" href="Assets/css/module-A.css">

<style>
    .modal-md { max-width: 800px; } 
    .modal-address p { padding-bottom: 2px; } 
    .site-modal .modal-content { background: #f0f0f0; } 
    .site-modal .modal-body { background: #ffffff; } 
    .site-modal thead tr th {font-weight: 500;padding: 8px 5px;}
    .site-modal tr.thead td {font-weight: 500;padding: 8px 5px;} 
    .site-modal .thead { border-top: 0; } 
    .site-modal .thead+tr { border-top: 0; } 
    .site-modal thead,
    .site-modal .thead { background-color: rgb(122 137 255 / 20%); } 
    .site-modal tbody tr+tr { border-top: 1px solid #ddd; } 
    .site-modal tbody tr td { padding: 5px 5px; }

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

    @media (min-width:1137px) {
        /* 29 date tabel css */
        .hisTypclm .dropdown-toggle,
        .hisRefrclm .dropdown-toggle {
            width: 100% !important;
        }
        .hisTypclm .dropdown,
        .hisRefrclm .dropdown {
            width: 100% !important;
        }

        .hisStatusclm .dropdown-toggle,
        .hisAcntclm .dropdown-toggle {
            width: 100% !important;
        }

        .hisStatusclm,
        .hisAcntclm {
            width: 25% !important;
            padding-left:0px;
        }


        .hisValclm {
            width: 7% !important;
            padding-left: 0;
            flex-grow: 0.5;
        } 
        .hisTask .tb-bdy  {
         padding-left: 0;
        }
         /* spacing css */
        .hisRefrclm {min-width:150px;}
        .hisDateclm {min-width: 65px;}
        .numItmclm {min-width: 70px;}
        .srHisclm {min-width: 35px !important;}
        .stsHiscol, .numRe {flex-grow: 1; justify-content:space-between;}

        .itmTable1 .numRef {
            width: auto !important;
            justify-content: space-between;         
            flex-grow: 1; 
            gap:.5rem
        }
       
        .hisTblbody1 .numRef {
            width: auto !important;
            justify-content: space-between;
           flex-grow: 1; 
           gap:.5rem
        }
        .hisTblbody1   .hisStatusclm  ,  .itmTable1  .hisStatusclm {
            max-width:75px;
        }
        .date-dpd {width: 15%; min-width: 70px;}
        .user-dpd {width: 15%; min-width: 65px;}
        .type-dpd {width: 30%; min-width: 105px;}
        .refer-to-dpd {width: 30%; min-width: 155px;}
        .type-dpd .dropdown-toggle {max-width:130px;}
        .usdCurr {display: none;}
        /* .srHisclm {
            min-width: fit-content;
        } */
    }
/*       .hisTblbody1 .numRef .tb-bdy,
    .hstTbl-head1 .numRef .tb-bdy{
       flex-grow: 1;
       width: auto !important;
    } */

    .itmTable .tb-bdy {
        padding-left:0;
    }

 .itmTable .dropdown {
    width: 100%;
 }
 .itmTable .dropdown a span{
    overflow: hidden;
 }
 .itmTable .dropdown-toggle{
    color: #666c85;
    text-decoration: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    border: 1px solid #dfe0e8;
    border-radius: 10px;
    padding: 7px 12px;
    font-weight: 700;
    width: 100%;
  }
  .itmTable .dropdown-menu {
    max-height: 250px;
    overflow-y: scroll;
}
  
  @media (max-width:1024px) {
    .numRef {
        width: 100%;
    }
    .hisTask .itmBody {
        gap: 3px;
    }
    .hisTask .itmBody .hisValclm {
         width: 49% !important;
    }
    .hisTask .itmBody .hisValclm .dolValcurr {
        text-align:start !important;
    }
    .stsHiscol {
        padding:0 .5rem;
    }
    .tb-bdy.hisStatusclm p {
        text-align:start !important;
    }
    .dropdnbtns {
        flex-direction: column;
    }
    .hstTbl-head .stsHiscol {
     flex-wrap: nowrap;
    }
    
  }
  .toggle-currency-btn {
width: 40px;
height: 30px;
display: flex;
color: #666C85;
font-size: 14px;
font-weight: 600;
line-height: 19.36px;
border: 1px solid #A9B0C0;
align-items: center;
justify-content: center;
border-radius: 10px;
cursor: pointer;
  }
  .toggle-currency-btn:hover {
    color: #fff !important;
    background-color: #7a89ff;
    border-color:  #7a89ff ;
  }

 .paidIsue .pdAmount,
 .paidIsue .pendAmount,
 .recIsue .pdAmount-rec,
 .recIsue .pendAmount-rec {
    font-weight: 400 !important;
  }
  /* hover effects */
  .reloadBtn a:hover {
    color: #fff !important;
    background-color: #7a89ff;
    border-color:  #7a89ff ;
  }
  .chkStore a:hover img {
     scale:1.1;
  }

  

#itemDiv, #taskDiv{ background-color: #fff !important;}

.issueDtl {padding: 9px 10px !important;}
.issueDtl .Variance {padding-right:  0px !important; width: 14% !important;}
.issueOut {width: 31% !important;} 
.issueIn { width: 55% !important; }
.detailPrice {padding: 11px 11px 0 !important;}

@media (max-width:767px) {
     #order_details_supplier {
        margin: 0;
     }
}

@media(max-width:991px) {
    .account-name-section { position: absolute;top:1rem;right:9rem; }
    .issueIn { padding-right: 2rem; }
}
@media(max-width:575px) {
    .account-name-section { top:4rem;right: 0;text-align: center;width: 100%; }
    .container.hisData { margin-top: 1rem; }
    .issueIn { padding-right: 0;width: 100% !important;margin-bottom: 2rem; }
    .issueOut {width: 60% !important;} 
    .issueDtl .Variance { width: 40% !important; }
}

/* ---- Responsive Filterbox styles start -------- */
.res__filter__box .itmTable { border: none;box-shadow: none;  border-radius: 10px 10px 0 0;padding: 3px 12px; }
.res__filter__box .his-Paybtn { width:17%; }
@media (min-width: 992px) {
    .res__filter__box .label__box { width:155px; }
    .res__filter__box .lg__70 { max-width:70%; }
}
@media (max-width: 1024px) {
  .hstTable-show{ top: 3rem;padding-top: 0 !important;background: transparent; }
  .detailPrice.detailPrc-show { padding: 0% !important;top: 3.5rem;background-color: #fff;display: block; } 
  .detailPrice.detailPrc-show .tab-mbDtl { padding: 10px;margin-bottom: 0; } 
  .detailPrice.detailPrc-show .scroller { max-height: calc(100vh - 7rem);overflow-y: auto; } 
}
@media (max-width:991px) {
    .res__filter__box .itmTable { min-height: calc(100vh - 3rem);padding: .75rem .75rem .5rem .75rem; }
    .res__filter__box .back__btn { width: 2.5rem;height: 2.5rem;}
    .res__filter__box .his-Paybtn { width:50%;position: absolute;top:0;right:0;flex-direction: row;padding-top: .875rem;padding-right: 1rem; }
    .hstTbl-head .numRef, .hstTbl-head .stsHiscol { width: 100%;justify-content: space-between; }
    .res__filter__box .itmTable .tb-bdy { width: 49%;padding-top: .45rem;padding-bottom: .45rem; }
    .res__filter__box .itmTable .tb-bdy .dropdown, .res__filter__box .itmTable .dropdown-toggle { width: 100%; } 
    .res__filter__box .itmTable .dropdown-toggle { min-height: 3.25rem; } 
    .res__filter__box .dropdnbtns { margin-top: 1rem; }
}
@media (max-width:575px) { 
    .res__filter__box .itmTable .tb-bdy, .res__filter__box .itmTable .tb-bdy { width: 100%; } 
    .res__filter__box .stsHiscol.d-flex.align-items-center{flex-wrap: wrap;padding-left: 0;padding-right: 0;}
}
@media(min-width:576px) {
    .sm__ml { margin-left: -0.3rem; }
}
/* ---- Responsive Filterbox styles end -------- */
.toggle-currency-btn-disabled {width: 40px;height: 30px; display: flex;color: #666C85;font-size: 14px;font-weight: 600;line-height: 19.36px;border: 1px solid #A9B0C0;align-items: center;justify-content: center;border-radius: 10px;cursor: pointer;}
.toggle-currency-btn-disabled:hover ,.toggle-currency-btn-disabled:focus{color: #666C85;cursor:default;}
.input-group {align-items: center;padding: 1rem 2rem;gap: .5rem;}
.input-group button {border-radius: 10px !important;}
.input-group input {background-color: white;border-radius: 10px !important;max-width: 200px;height: 32px;padding: 0 .5rem;}
.srHisclm { width: 10%; }
html[dir=rtl]   .dropdown-item .fa-square { padding: 0 0 0 .5rem !important; }
.overflowTable { background-color: #fff; }
.site-modal thead tr th { font-size:12px; }
.modal-content {overflow-x:hidden; }
@media(max-width:767px){
    .modal .modal-table { font-size: 5px }
    .modal .table-cell { padding: 1px 5px;font-size:5px; }
    .modal .fs-13 {font-size: 8px;line-height: 1.2; }
    .modal .headerTxt.modal-date {font-size: 9px; }
    .modal .headerTxt {font-size: 12px; }
    .modal .modal-header { padding:0; }
    .site-modal thead tr th {font-size: 6px;padding: 4px 5px;line-height: 1.2;}
    .site-modal .table1 tr td { padding: 0px 5px; }
    .grand-total-tabel tr { height: 28px; }
}
@media(min-width:992px) {
    #modalfiltertop { position: absolute;top:.5rem;left:4rem; }
}
.info__table td:nth-child(1){width:55%}
</style>


</head>

<body>

<div class="container-fluid newOrder">
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

                    <div class="alrtMessage">
                        <div class="container">

                            <?php if (isset($_GET['delete']) || isset($_GET['status']) || isset($_GET['paymentStatus'])) { ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <p><?php

                                        echo isset($_GET['status']) ? ' ' . showOtherLangText('New record added successfully') . ' ' : '';

                                        echo isset($_GET['delete']) ? ' ' . showOtherLangText('Record deleted successfully.') . ' ' : '';
                                        if (isset($_GET['paymentStatus']) && $_GET['paymentStatus'] == 2) {
                                            echo ' ' . showOtherLangText('We have initiated a refund in your account') . ' ';
                                        }

                                        ?>
                                    </p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php } ?>


                        </div>
                    </div>
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
                                            <a href="javascript:void(0)" target="_blank" data-bs-toggle="modal" onclick="showOrdersHistory();">
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
                              
                            }
                            
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
                        <div class="tab-mbDtl">
                            <a href="javascript:void(0)" class="tab-revLnk"><i class="fa-solid fa-arrow-left"></i></a>
                        </div>
                        <div class="scroller"> 
                            <div class="row align-items-start">
                                <div class="issueDtl">
                                    <div class="issueIn">
                                        <div class="dspBlk">
                                            <div class="paidIsue d-flex">
                                                <div class="col-md-3">
                                                    <p class="pdStatus"><?php echo showOtherLangText('Paid'); ?></p>
                                                    <p class="pendStatus"><?php echo showOtherLangText('Pending'); ?></p>
                                                </div>
                                                <div class="col-md-9 text-center">
                                                    <p class="gr-Out"><?php echo showOtherLangText('Issued In'); ?></p>
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
                                    WHERE o.status = '2' AND o.ordCurAmt>0 AND o.account_id = '" . $_SESSION['accountId'] . "' " . $cond1 . "";
                                        $result = mysqli_query($con, $sql);
                                        if(mysqli_num_rows($result)>0){?>
                                        <!-- added on 31-7-24 -->
                                        <div class="usdCurr text-center">
                                                <div class="paidIsue d-flex">
                                                    <div class="col-md-3">
                                                        <p class="pdStatus">Paid</p>
                                                        <p class="pendStatus">Pending</p>
                                                    </div>
                                                    <div class="col-md-9 text-center">
                                                        <p class="usd-In">$</p>
                                                        <p class="ttlAmount"><?php echo showprice($issueInTotal_defaultcurrency, $getDefCurDet['curCode']); ?></p>
                                                        <p class="pdAmount"><?php echo showprice($issueInTotal_defaultcurrency_paid, $getDefCurDet['curCode']); ?></p>
                                                        <p class="pendAmount"><?php echo showprice($issueInTotal_defaultcurrency_pending, $getDefCurDet['curCode']); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                         }
                                        
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
                                                        <p class="pdStatus"><?php echo showOtherLangText('Paid'); ?></p>
                                                        <p class="pendStatus"><?php echo showOtherLangText('Pending'); ?></p>
                                                    </div>
                                                    <div class="col-md-9 text-center">
                                                        <p class="usd-In"><?php echo ($otherCurrRow['curCode']); ?></p>
                                                        <p class="ttlAmount"><?php echo showOtherCur($otherCurrTotalValueArr[$otherCurrRow['currencyId']], $otherCurrRow['currencyId']); ?></p>
                                                        <p class="pdAmount"><?php echo $otherCurrPaidTotalValueArr[$otherCurrRow['currencyId']] != '' ? showOtherCur($otherCurrPaidTotalValueArr[$otherCurrRow['currencyId']], $otherCurrRow['currencyId']) : '&nbsp;'; ?></p>
                                                        <p class="pendAmount"><?php echo showOtherCur($otherCurrPendingTotalValueArr[$otherCurrRow['currencyId']], $otherCurrRow['currencyId']); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                        <?php } ?>
                                        
                                        <div style="padding-left:.5rem;">
                                    <a class="<?php 
                                    if (mysqli_num_rows($result) == 0) { 
                                    echo 'toggle-currency-btn-disabled'; 
                                    } else { 
                                    echo 'toggle-currency-btn'; 
                                    } 
                                    ?>">$/¥</a>
                                        </div>
                                    </div>
                                    <div class="issueOut">
                                        <div class="recIsue d-flex">
                                            <div class="col-md-5">
                                                <p class="recStatus"><?php echo showOtherLangText('Received'); ?></p>
                                                <p class="pendStatus"><?php echo showOtherLangText('Pending'); ?></p>
                                            </div>
                                            <div class="col-md-7 text-center">
                                                <p class="rd-In"><?php echo showOtherLangText('Issued Out'); ?></p>
                                                <p class="ttlAmount-rec"><?php echo showprice($issueOutTotal, $getDefCurDet['curCode']); ?></p>
                                                <p class="pdAmount-rec"> <?php echo ($issuedInOutPaidArr[2][1]) ? showPrice($issuedInOutPaidArr[2][1], $getDefCurDet['curCode']) : '0'; ?></p>
                                                <p class="pendAmount-rec"><?php echo ($issuedInOutPendingArr[2][0] > 0) ? showPrice($issuedInOutPendingArr[2][0], $getDefCurDet['curCode']) : '0'; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $variancesPosTot = 0;
                                    $variancesPosQtyTot = 0;
                                    $variancesNevQtyTot = 0;
                                    $variancesNevTot = 0;
                                    $varaincesVal = 0;
    
                                    //variance starts here
                                    // if ($_SESSION['getVals']['ordType'] == '' || $_SESSION['getVals']['ordType'] == 3) {
    
                                     $sqlSet = " SELECT od.* FROM tbl_order_details od
                        INNER JOIN tbl_orders o
                            ON(o.id=od.ordId) AND o.account_id=od.account_Id
                        WHERE o.ordType = '3' AND o.status = '2' AND o.account_id = '" . $_SESSION['accountId'] . "' " . $cond1 . " ";
                                        $resultSet = mysqli_query($con, $sqlSet);
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
                                            <p class="varDtl"><?php echo showOtherLangText('Variances'); ?></p>
                                            <p class="varValue"><?php echo  getPriceWithCur($variancesNevTot, $getDefCurDet['curCode']); ?></p>
                                            <p class="varDif"><?php echo getPriceWithCur($variancesPosTot, $getDefCurDet['curCode']) ?></p>
                                        </div>
                                   
                                </div>
                                <?php
                                if ($accessHistoryAccountsPermission['type_id'] == 1) {
                                ?>
                                    <div class="accntDtl">
                                        <p class="accHead text-center"><?php echo showOtherLangText('Accounts'); ?></p>
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
                    </div>




                    <div id="resFilterBox" class="container position-relative hstTbl-head px-0 res__filter__box <?php echo count($historyUserFilterFields)<11?'hstTbl-head1':''; ?>">
                        
                        <!-- Item Table Head Start -->
                        <div class=" mt-2 itmTable position-relative <?php echo count($historyUserFilterFields)<11?'itmTable1':''; ?>">
                            <button type="button" id="closeResFilterBox" class="btn btn-primary d-flex justify-content-center align-items-center d-lg-none back__btn p-0">
                                <svg width="15" height="10" viewBox="0 0 15 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 4.68552L1.2969 3.97443L0.577728 4.68552L1.2969 5.39662L2 4.68552ZM14 5.68552C14.5523 5.68552 15 5.23781 15 4.68552C15 4.13324 14.5523 3.68552 14 3.68552V5.68552ZM5.2969 0.0193784L1.2969 3.97443L2.7031 5.39662L6.7031 1.44156L5.2969 0.0193784ZM1.2969 5.39662L5.2969 9.35167L6.7031 7.92949L2.7031 3.97443L1.2969 5.39662ZM2 5.68552H14V3.68552H2V5.68552Z" fill="white"/>
                                </svg> 
                            </button>
                            <div class="align-items-center d-flex dropdnbtns">
                                <div class="numRef numRef1 align-items-center">
                                    <div class="tb-bdy srHisclm">
                                        <p><?php echo mysqli_num_rows($historyQry) > 0 ? mysqli_num_rows($historyQry) : ''; ?></p>
                                    </div>
                                        
                                        <?php if (isset($historyUserFilterFields) && !in_array(1, $historyUserFilterFields)) { ?>
                                        <?php } else { ?>
                                            <div class="tb-bdy numItmclm"><div class="d-flex align-items-center">
                                                <p style="font-size: 12px;"><?php echo showOtherLangText('Number'); ?></p>
                                                <span class="dblArrow">
                                                    <a onclick="sortTableByColumn('.newHistoryTask', '.hisOrd','asc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a onclick="sortTableByColumn('.newHistoryTask', '.hisOrd','desc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div></div>
                                        <?php } ?>
                                    


                                    
                                            <?php if (isset($historyUserFilterFields) && !in_array(2, $historyUserFilterFields)) { ?>
                                            <?php } else { ?>
                                                <div class="tb-bdy date-dpd" >
                                        <div class="d-flex align-items-center" style=" background:inherit"><div class="dropdown d-flex position-relative">
                                                    <a class="dropdown-toggle body3" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span id="dateTypeText"><?php echo showOtherLangText('Date'); ?></span> <i class="fa-solid fa-angle-down"></i>
                                                    </a>

                                                    <?php echo $dateTypeOptions; ?>
                                                </div></div></div>
                                            <?php } ?>
                                        
                                    
                                            <?php if (isset($historyUserFilterFields) && !in_array(3, $historyUserFilterFields)) { ?>
                                            <?php } else { ?>
                                                <div class="tb-bdy user-dpd">
                                                  <div class="d-flex align-items-center" style="background:inherit;"><div class="dropdown d-flex position-relative">
                                                    <a class="dropdown-toggle body3" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span id="userText"><?php echo showOtherLangText('User'); ?></span> <i class="fa-solid fa-angle-down"></i>
                                                    </a>
                                                    <?php echo $userOptions; ?>
                                                </div></div></div>
                                            <?php } ?>
                                        

                                    
                                            <?php if (isset($historyUserFilterFields) && !in_array(4, $historyUserFilterFields)) { ?>
                                            <?php } else { ?>
                                       <div class="tb-bdy type-dpd">
                                        <div class="d-flex align-items-center" style=" background:inherit"><div class="dropdown d-flex position-relative w-100">
                                                    <a class="dropdown-toggle body3 w-100" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span id="TypeText"><?php echo showOtherLangText('Type'); ?></span> <i class="fa-solid fa-angle-down"></i>
                                                    </a>
                                                    <?php echo $typeOptions; ?>
                                                </div></div>

                                    </div>
                                            <?php } ?>
                                        

                                    
                                    
                                            <?php if (isset($historyUserFilterFields) && !in_array(7, $historyUserFilterFields) || !isset($colsArr[7])) { ?>
                                            <?php } else { ?>
                                                <div class="tb-bdy refer-to-dpd">
                                        <div class="d-flex align-items-center"><div class="dropdown d-flex position-relative w-100 lg__70">
                                                    <a class="dropdown-toggle body3 w-100" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span id="refertotext"><?php echo showOtherLangText('Refer To'); ?></span> <i class="fa-solid fa-angle-down"></i>
                                                    </a>
                                                    <?php echo $suppMemStoreOptions; ?>
                                                </div> </div>
                                    </div>
                                            <?php } ?>
                                       
                                </div>



                                    
                                        <?php if (isset($historyUserFilterFields) && !in_array(8, $historyUserFilterFields) || !isset($colsArr[8])) { ?>
                                        <?php } else { ?>
                                            <div class="tb-head hisValclm"><div class="d-flex align-items-center">
                            <p><?php echo showOtherLangText('Supplier inv no'); ?></p>
                                            </div></div>
                                        <?php } ?>
                                    

                                    
                                        <?php if (isset($historyUserFilterFields) && !in_array(10, $historyUserFilterFields)) { ?>
                                        <?php } else { ?>
                                            <div class="tb-head hisValclm"><div class="d-flex align-items-center">
                                                <p><?php echo showOtherLangText('Value'); ?></p>
                                            </div></div>
                                        <?php } ?>
                                    


                                <div class="stsHiscol d-flex align-items-center">
                                    
                                            <?php if (isset($historyUserFilterFields) && !in_array(14, $historyUserFilterFields) || !isset($colsArr[14]) ) { ?>
                                            <?php } else { ?>
                                                <div class="tb-bdy hisStatusclm sm__ml">
                                        <div class="d-flex align-items-center"  ><div style="width: 80%;" class="dropdown d-flex align-items-center  position-relative">
                                                    <a class="dropdown-toggle body3 " data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span id="statusText">
                                                            <span class="d-none d-lg-inline-block"><?php echo showOtherLangText('Status'); ?></span>
                                                            <span class="d-lg-none"><?php echo showOtherLangText('Status'); ?></span>
                                                        </span> 
                                                        <i class="fa-solid fa-angle-down"></i>
                                                    </a>
                                                    <?php echo $statusTypeOptions; ?>
                                                    <span class="dblArrow d-none d-lg-block">
                                                        <a onclick="sortTableByColumn('.newHistoryTask', '.his-pendStatus','asc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                        <a onclick="sortTableByColumn('.newHistoryTask', '.his-pendStatus','desc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                    </span>
                                                </div> </div>
                                    </div>
                                            <?php } ?>
                                       


                                    
                                        <?php if (isset($historyUserFilterFields) && !in_array(15, $historyUserFilterFields) || !isset($colsArr[15]) ) { ?>
                                        <?php } else { ?>
                                            <div class="tb-bdy hisStatusclm d-none d-lg-block"><div class="d-flex align-items-center justify-content-between" style=" min-width: fit-content !important;">
                                                <p style="color: #666c85; font-size: 12px; font-weight:600;"><?php echo showOtherLangText('Payment No.'); ?></p>
                                            </div></div>
                                        <?php } ?>
                                    

                                   
                                        <?php if (isset($historyUserFilterFields) && !in_array(16, $historyUserFilterFields) || !isset($colsArr[16])) { ?>
                                        <?php } else { ?>
                                             <div class="tb-bdy hisStatusclm d-none d-lg-block" ><div class="d-flex align-items-center justify-content-between" style=" min-width: fit-content !important;">
                                    <p style="color: #666c85; font-weight:600; "><?php echo showOtherLangText('Inv No.'); ?></p>
                                            </div> </div>
                                        <?php } ?>
                                   

                                    
                                            <?php if (isset($historyUserFilterFields) && !in_array(17, $historyUserFilterFields) || !isset($colsArr[17]) ) { ?>
                                            <?php } else { ?>
                                                <div class="tb-bdy hisAcntclm" style="padding-left: 0px;">

                                        <div class="d-flex align-items-center"><div class="dropdown d-flex align-items-center w-100 position-relative">
                                                    <a class="dropdown-toggle body3" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span id="accountTxt">
                                                            <span class="d-none d-lg-inline-block"><?php echo showOtherLangText('Account'); ?></span>
                                                            <span class="d-lg-none"><?php echo showOtherLangText('Account'); ?></span>
                                                        </span> 
                                                        <i class="fa-solid fa-angle-down"></i>
                                                    </a>
                                                    <?php echo $accountOptions; ?>
                                                </div> </div>

                                    </div>
                                            <?php } ?>
                                </div>
                                

                                <div class="d-flex align-items-center justify-content-lg-end justify-content-between his-Paybtn">
                                    <div class="label__box">
                                        <div class="d-flex align-items-center">
                                            <p class="d-none d-lg-block"><?php echo showOtherLangText('Action'); ?></p>
                                            <p class="d-lg-none fs-6"><?php echo showOtherLangText('Filter'); ?></p>
                                        </div>
                                    </div>
                                    <!-- check box model icon -->
                                    <div class="chkStore">
                                        <a class="dropdown-item p-0" style="width: fit-content;" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#checkbox_module">
                                            <img src="Assets/icons/chkColumn.svg" style="width: 22px;" alt="Check Column">
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
                </section>


                <section class="hisTblbody <?php echo count($historyUserFilterFields)<11?'hisTblbody1':''; ?>">
                    <div id="boxscroll">
                        <div class="container position-relative hstTbl-bd cntTableData">
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
                                    <div class="align-items-center itmBody">
                                        <div class="numRef numRef2 align-items-center">
                                            <div class="tb-bdy srHisclm" style="min-width: fit-content;">
                                                <p><?php echo $x; ?></p>
                                            </div>
                                            
                                                <?php if (isset($historyUserFilterFields) && !in_array(1, $historyUserFilterFields)) { ?>
                                                <?php } else { ?>
                                                    <div class="tb-bdy numItmclm"><p class="hisOrd">#<?php echo $orderRow['ordNumber']; ?></p> </div>
                                                <?php } ?>
                                           
                                            <?php if (isset($historyUserFilterFields) && !in_array(2, $historyUserFilterFields)) { ?>
                                                <?php } else { ?>
                                                    <div class="tb-bdy hisDateclm "><p class="fstDt"><?php echo $dateType; ?></p> </div>
                                                <?php } ?>
                                           

                                           
                                                <?php if (isset($historyUserFilterFields) && !in_array(3, $historyUserFilterFields)) { ?>
                                                <?php } else { ?>
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
                                            
                                                <?php if (isset($historyUserFilterFields) && !in_array(4, $historyUserFilterFields)) { ?>
                                                <?php } else { ?> <div class="tb-bdy hisTypclm"><div class=" d-flex align-items-center <?php echo $class1; ?>">
                                                        <div class="<?php echo $class; ?>">&nbsp;</div>
                                                        <p><?php echo $ordType; ?></p>
                                                    </div> </div>
                                                <?php } ?>

                                           
                                            <?php if (isset($historyUserFilterFields) && !in_array(7, $historyUserFilterFields) || !isset($colsArr[7])) { ?>
                                                <?php } else { ?>
                                                    <div class="tb-bdy hisRefrclm"><p class="refTomember"><?php echo $suppMemStoreId; ?></p></div>
                                                <?php } ?>
                                            
                                        </div>
                                      
<?php if (isset($historyUserFilterFields) && !in_array(8, $historyUserFilterFields) || !isset($colsArr[8]) ) { ?>
<?php } else { ?>  <div class="tb-bdy hisValclm"><p class="dolValcurr"><?php echo $InvoiceNumber; ?></p></div>
                                       <?php } ?>
                                        

                                       
                                            <?php if (isset($historyUserFilterFields) && !in_array(10, $historyUserFilterFields)) { ?>
                                        <?php } else { ?>  <div class="tb-bdy hisValclm"><p class="dolValcurr"><?php echo ($orderRow['ordType'] == 3) ? getNumFormtPrice($variancesTotAmt, $getDefCurDet['curCode']) : getNumFormtPrice($orderRow['ordAmt'], $getDefCurDet['curCode'])
                                        . '<br>' .
                                        ($orderRow['ordCurAmt'] > 0 ? showOtherCur($orderRow['ordCurAmt'], $curDet['id']) : ''); ?></p> </div>
                                        <?php } ?>
                                       
                                        <div class="stsHiscol d-flex align-items-center">
                                           
                                                <?php if (isset($historyUserFilterFields) && !in_array(14, $historyUserFilterFields) || !isset($colsArr[14])) { ?>
                                                <?php } else { ?> <div class="tb-bdy hisStatusclm" style=" padding-left:0px;"><p class="his-pendStatus"><?php echo $paymentStatus; ?></p></div>
                                                <?php } ?>
                                            
                                            
                                                <?php if (isset($historyUserFilterFields) && !in_array(15, $historyUserFilterFields) || !$colsArr[15]) { ?>
                                                <?php } else { ?><div class="tb-bdy hisStatusclm" slot="padding-left:0px;"><p class="his-pendStatus"><?php echo ($orderRow['paymentStatus'] > 0 ? setPaymentId($paymentId) : ''); ?></p></div>
                                                <?php } ?>
                                            

                                           
                                                <?php if (isset($historyUserFilterFields) && !in_array(16, $historyUserFilterFields) || !$colsArr[16]) { ?>
                                                <?php } else { ?>
                                                     <div class="tb-bdy hisStatusclm" slot="padding-left:0px;"><p class="his-pendStatus"><?php echo ($orderRow['ordType'] == 2) ? $InvoiceNumber : ' '; ?></p></div> <?php } ?>
                                            

                                            
                                                <?php if (isset($historyUserFilterFields) && !in_array(17, $historyUserFilterFields) || !isset($colsArr[17])) { ?>
                                                <?php } else { ?>
                                                    <div class="tb-bdy hisAcntclm"><p class="hisAccount"><?php echo ($orderRow['paymentStatus'] == 1 ? $orderRow['accountName'] : ''); ?></p></div><?php } ?>
                                            

                                        </div>

                                        <div class="tb-bdy shrtHisclm">
                                            <div class="mb-Acntdetail">
                                                <div class="tb-bdy">
                                                    <p>Account</p>
                                                    <p class="mb-Acntnum"></p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-end his-Paybtn">
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
                }

                        elseif ($orderRow['ordType'] == 2) 
                        {
                            if($accessHistoryAccountsPermission['type_id'] == 1) {
                                if ($accessInvoicePermission['type_id'] == 1) {

                                    getrequisitionPaymentLink($orderRow['id']);
                                } else {
                                ?><span style="width: 66%;">&nbsp;</span><?php
                                        }
                                    } else {
                                            ?><span style="width: 66%;">&nbsp;</span><?php
                                    }
                                } else {

                    echo '<div class="cnfrm" style=" border: none; background: transparent; box-shadow: none;"></div>';
                }

                                        ?>
                                                <div class="d-flex align-items-center">
                                                    <div class="doc-bx text-center d-flex justify-content-center align-items-center position-relative">
                                                        <a href="javascript:void(0)" class="dropdown-toggle runLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <span class="docMent"></span>
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
                                            <input type="password" value="" placeholder="" class="form-control" name="password">&nbsp;&nbsp;
                                            <button type="submit" class="btn btn-primary"><?php echo showOtherLangText('Delete Now') ?></button>
                                            &nbsp;&nbsp;

                                            <a href="history.php" class="class=" btn-primary><?php echo showOtherLangText('Cancel') ?></a>

                                        </div>
                                    </form>
                                </div>
                            <?php } ?>

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

            <div class="modal-footer justify-content-center">
                <div class="btnBg">
                    <button type="button" data-bs-dismiss="modal" class="btn sub-btn std-btn"><?php echo showOtherLangText('No'); ?></button>
                </div>
                <div class="btnBg">
                    <button type="button" onclick="" class="deletelink btn sub-btn std-btn"><?php echo showOtherLangText('Yes'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- View Detail Popup End -->

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
        var newOnClick = "window.location.href='history.php?delOrderId=" + delOrderId + "'";
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
    
    $('.toggle-currency-btn').click(function(){
       $('.usdCurr').toggle();
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
                    isSupDet: isSupOrder
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
<div class="modal"  tabindex="-1" id="order_details" aria-labelledby="orderdetails" aria-hidden="true">
    <div class="modal-dialog  modal-md site-modal modal-dialog-centered">
        <div id="order_details_supplier" class="modal-content p-2">

        </div>
    </div>
</div>
<!-- View ckecknox Popup Start -->
<div class="modal" tabindex="-1" id="checkbox_module" aria-labelledby="checkbox_module" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md site-modal">
        <div class="modal-content ">
            <div>
                <div class="mdlHead-Popup d-flex justify-content-between align-items-center">
                    <span>
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
                            $sel =  in_array($key, $historyUserFilterFields) ? ' checked="checked" ' : '';
                        }

                    ?>
                        <input type="checkbox" id="optionCheck" class="optionCheck" name="showFields[]" <?php echo $sel; ?> value="<?php echo $key; ?>">&nbsp;<?php echo $col; ?><br>

                    <?php } ?>

                    <br>
                    <p>
                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" style="border:1px solid #7a89ff; background-color: #7a89ff; color: #fff;"><?php echo showOtherLangText('Show'); ?></button>
                        <?php if (isset($historyUserFilterFields)) { ?>
                            <a class="btn btn-secondary dropdown-toggle fs-13 py-2" onClick="window.location.href='history.php?clearshowFields=1'" style="border:1px solid #7a89ff; background-color: #7a89ff; color: #fff;"><?php echo showOtherLangText('Clear filter'); ?> </a>

                        <?php } ?>
                    </p>
                </form>
            </div>

        </div>

    </div>
</div>
<!-- View checkbox Popup End -->
<!-- ===== History pdf popup new in div format======= -->
<div class="modal" tabindex="-1" id="history_pdf" aria-labelledby="history_pdfModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered site-modal" style="max-width:830px">
        <div id="order_history_popup" class="modal-content p-2">


        </div>
    </div>
</div>
<!-- raw materiels Popup Start -->
<div class="modal" tabindex="-1" id="Raw_Convert_Item_details" aria-labelledby="Raw-Convert-Item-details" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md site-modal">
        <div id="Raw_Convert_Item_details_content" class="modal-content p-2">


        </div>
    </div>
</div>
<!-- raw materiels 1 Popup End -->

<!-- stock take details Popup Start -->
<div class="modal" tabindex="-1" id="Stock_take_details" aria-labelledby="Stock-take-details" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md site-modal">
        <div id="Stock_take_details_content" class="modal-content p-2">


        </div>
    </div>
</div>
<!-- stock take details Popup End -->
<div class="modal" tabindex="-1" id="view_payment_paid" aria-labelledby="view-payment-paid" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md site-modal">
        <div class="modal-content p-2" id="view_payment_paid_content">

        </div>
        <!-- payment Detail Popup End -->



    </div>
</div>
<!-- payment Detail Popup End -->
</div>

</div>
<script>
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

</script>

<script>
    // set filter box responsive class remove script 
    var closeBtn = document.getElementById("closeResFilterBox");
    var closeBox = document.getElementById("resFilterBox");
    closeBtn.addEventListener('click', function(){
        closeBox.classList.remove('hstTable-show');
    })
</script>

<?php
include_once('historyPdfJsCode.php');
?>

</body>

</html>