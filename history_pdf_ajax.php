<?php
include('inc/dbConfig.php'); //connection details


if (!isset($_SESSION['adminidusername'])) {
    echo '<script>window.location = "login.php"</script>';
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$checkIfPermissionToNewOrderSec = checkIfPermissionToNewOrderSec($_SESSION['designation_id'], $_SESSION['accountId']);
$checkIfPermissionToNewOrderSec = mysqli_num_rows($checkIfPermissionToNewOrderSec);

$checkIfPermissionToNewReqSec = checkIfPermissionToNewReqSec($_SESSION['designation_id'], $_SESSION['accountId']);
$checkIfPermissionToNewReqSec = mysqli_num_rows($checkIfPermissionToNewReqSec);

$sql = "SELECT * FROM tbl_user  WHERE id = '" . $_SESSION['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
$result = mysqli_query($con, $sql);
$userDetails = mysqli_fetch_array($result);
$historyUserFilterFields = $userDetails['historyUserFilterFields'] ?    explode(',', $userDetails['historyUserFilterFields']) : null;


$headerArrOptions  = [

    2 => 1,

    3 => 2,

    4 => 3,

    5 => 8,

    6 => 4,

    7 => 7,

    8 => 10,

    9 => 14,

    10 => 15,

    11 => 16,

    12 => 17
];


// $cond = '';
// $cond1 = '';

//From Date || To Date Filter
if (isset($_SESSION['getVals']['fromDate']) && $_SESSION['getVals']['fromDate'] != '' && $_SESSION['getVals']['toDate'] != '') {
    $cond .= " AND DATE(setDateTime) BETWEEN '" . date('Y-m-d', strtotime($_SESSION['getVals']['fromDate'])) . "' AND '" . date('Y-m-d', strtotime($_SESSION['getVals']['toDate'])) . "'   ";
    // $cond1 = $cond;
    $fromDate = $_SESSION['getVals']['fromDate'];
    $toDate = $_SESSION['getVals']['toDate'];
} elseif (isset($_SESSION['fromDate']) && $_SESSION['fromDate'] != '' && $_SESSION['toDate'] != '') {
    $cond .= " AND DATE(o.setDateTime) BETWEEN '" . date('Y-m-d', strtotime($_SESSION['fromDate'])) . "' AND '" . date('Y-m-d', strtotime($_SESSION['toDate'])) . "' ";
    // $cond1 = $cond;
    $fromDate = $_SESSION['fromDate'];
    $toDate = $_SESSION['toDate'];
} else {
    $_GET['fromDate'] = date('d-m-Y', strtotime('-3 days'));
    $_GET['toDate'] = date('d-m-Y');

    $cond .= " AND DATE(o.setDateTime) BETWEEN '" . date('Y-m-d', strtotime($_GET['fromDate'])) . "' AND '" . date('Y-m-d', strtotime($_GET['toDate'])) . "' ";

    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
}


// Order type filter
if (isset($_SESSION['getVals']['ordType']) && $_SESSION['getVals']['ordType']) {
    $cond .= " AND o.ordType = '" . $_SESSION['getVals']['ordType'] . "'   ";
}


// supplier || Member || store Filter
if (isset($_SESSION['getVals']['suppMemStoreId']) && $_SESSION['getVals']['suppMemStoreId']) {
    $suppMemStoreId = $_SESSION['getVals']['suppMemStoreId'];
    $getSupMemStoreId = explode('_', $suppMemStoreId);
    $getTxtById = $getSupMemStoreId[0];
    $getId = $getSupMemStoreId[1];

    if ($getTxtById == 'suppId') {
        $cond .= " AND o.supplierId = " . $getId . "  ";
    }

    if ($getTxtById == 'deptUserId') {
        $cond .= " AND o.recMemberId = " . $getId . "  ";
    }

    if ($getTxtById == 'storeId') {
        $cond .= " AND o.storeId = " . $getId . "  ";
    }
}

// user filter
if (isset($_SESSION['getVals']['userId']) && $_SESSION['getVals']['userId']) {
    $cond .= " AND o.orderBy = '" . $_SESSION['getVals']['userId'] . "' ";
}

// Payment Status Filter
if ($_SESSION['getVals']['statusType'] == 1) {

    $cond .= "AND o.paymentStatus = 1 AND o.ordType = 1 ";
}
if ($_SESSION['getVals']['statusType'] == 2) {

    $cond .= "AND o.paymentStatus = 1 AND o.ordType = 2 ";
}
if ($_SESSION['getVals']['statusType'] == 3) {

    $cond .= "AND (o.paymentStatus = 2 OR o.paymentStatus = 0) AND o.ordType IN(2,1) ";
}

// for account of payment
if (isset($_SESSION['getVals']['accountNo']) && $_SESSION['getVals']['accountNo'] != '') {
    if ($_SESSION['getVals']['statusType'] == '') {
        $cond .= " AND o.bankAccountId = '" . $_SESSION['getVals']['accountNo'] . "' AND o.paymentStatus = 1 ";
    }
    if ($_SESSION['getVals']['statusType'] == 1) {
        $cond .= " AND o.bankAccountId = '" . $_SESSION['getVals']['accountNo'] . "' AND o.paymentStatus = 1 AND o.ordType = 1 ";
    }
    if ($_SESSION['getVals']['statusType'] == 2) {
        $cond .= " AND o.bankAccountId = '" . $_SESSION['getVals']['accountNo'] . "' AND o.paymentStatus = 1 AND o.ordType = 2 ";
    }
}

$condWithoutGroup = $cond;

// Date sorting
if ($_SESSION['getVals']['dateType'] == '') {
    $cond .= " GROUP BY o.id ORDER BY o.id desc ";
}
if ($_SESSION['getVals']['dateType'] != '' && $_SESSION['getVals']['dateType'] == 1) {
    $cond .= " GROUP BY o.id ORDER BY o.ordDateTime desc ";
}
if ($_SESSION['getVals']['dateType'] != '' && $_SESSION['getVals']['dateType'] == 2) {
    $cond .= " GROUP BY o.id ORDER BY o.setDateTime desc ";
}
if ($_SESSION['getVals']['dateType'] != '' && $_SESSION['getVals']['dateType'] == 3) {
    $cond .= " GROUP BY o.id ORDER BY o.paymentDateTime desc, o.setDateTime desc ";
}


//Access Invoice permission for user
$accessInvoicePermission = get_access_invoice_permission($_SESSION['designation_id'], $_SESSION['accountId']);
//Access payment permission for user
$accessPaymentPermission = get_access_payment_permission($_SESSION['designation_id'], $_SESSION['accountId']);


$mainSqlQry = " SELECT o.*, 
od.pId,
du.name AS deptUserName,
s.name AS storeName,
sp.name AS suppName,
ac.accountName,
du.receive_inv
FROM tbl_order_details od 

INNER JOIN tbl_orders o 
    ON(o.id = od.ordId) AND o.account_id = od.account_id
LEFT JOIN tbl_accounts ac ON
    (o.bankAccountId = ac.id) AND o.account_id = ac.account_id
LEFT JOIN tbl_deptusers du ON
    (o.recMemberId = du.id) AND o.account_id = du.account_id
LEFT JOIN tbl_stores s ON
    (o.storeId = s.id) AND o.account_id = s.account_id
LEFT JOIN tbl_suppliers sp ON
    (o.supplierId = sp.id) AND o.account_id = sp.account_id


    LEFT JOIN tbl_designation_sub_section_permission dp ON(dp.type_id = o.supplierId) AND
 o.account_id = dp.account_id AND dp.designation_id = " . $_SESSION['designation_id'] . " AND dp.type = 'order_supplier' AND dp.account_id = " . $_SESSION['accountId'] . "
 AND dp.designation_section_permission_id=1 AND o.ordType=1

 LEFT JOIN tbl_designation_sub_section_permission dp1 ON(dp1.type_id = o.recMemberId) AND o.account_id = dp1.account_id AND
 dp1.designation_id = " . $_SESSION['designation_id'] . " AND dp1.type = 'member' AND dp1.account_id = " . $_SESSION['accountId'] . "
 AND dp1.designation_section_permission_id=2 AND o.ordType=2
 
WHERE o.status = 2  AND o.account_id = '" . $_SESSION['accountId'] . "'

AND (o.ordType=1 AND  dp.id > 0 OR o.ordType=2 AND  dp1.id > 0 OR o.ordType=3 OR o.ordType=4 )
" . $cond . " ";

$historyQry = mysqli_query($con, $mainSqlQry);


$bankAccountIdsStr = getAccountIds($_SESSION['accountId'], $condWithoutGroup); //get this to pass into below query in account list section


// Get full address and their logo of client
$sql = " SELECT c.name AS countryName, cl.* FROM tbl_client cl
LEFT JOIN tbl_country c ON(cl.country=c.id)
WHERE cl.id = '" . $_SESSION['accountId'] . "' ";
$result = mysqli_query($con, $sql);
$clientDetRow = mysqli_fetch_array($result);
// ==========================================
$cond1 = $cond;

$content = '<form action="history_pdf_download.php" target="_blank" method="get"><div class="modal-header pb-3">

<input type="hidden" name="getLangType" value="' . $getLangType . '"/>

    <div class="w-100 p-2 pt-0 d-flex justify-content-end d-md-none">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                    <div class="d-md-flex align-items-center justify-content-between w-100 position-relative">
                        <div class="d-flex align-items-center justify-content-between w-100 "> 
                            <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#modalfiltertop">
                                <i class="fa fa-filter"></i>
                            </button>
                         
                            <button type="submit" class="btn btn-primary dwnBtn"><span class="align-middle">' . showOtherLangText('Press') . '</span> <i class="fa-solid fa-download ps-1"></i></button>
                            
                        </div>
                        <div class="collapse" id="modalfiltertop">
                            <div class="d-flex gap-3 modal-head-row">
                            

                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                ' . showOtherLangText('Headers') . '<i class="fa-solid fa-angle-down ps-1"></i>
                                </button>
                                <ul id="show-headerHistory" class="dropdown-menu px-3" aria-labelledby="headers">
                                    <li>
                                        <input type="checkbox" name="checkAll" class="headChk-AllHistory form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Check All') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" name="address"
                                            id="headChk-BxHistory" 
                                            class="headCheckboxHistory header-addressHistory form-check-input"
                                            onclick="showHideByClassHistory(\'address-sectionHistory\')" value="1">
                                        <span class="fs-13">' . showOtherLangText('Address') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" name="logo" 
                                        id="headChk-BxHistory"
                                        class="headCheckboxHistory header-logoHistory form-check-input"
                                        onclick="showHideByClassHistory(\'logo-sectionHistory\')" value="1">
                                        <span class="fs-13">' . showOtherLangText('Logo') . '</span>
                                    </li>
                                </ul>
                            </div>                                  

                            <div class=" dropdown">
                                <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                ' . showOtherLangText('Summary') . '<i class="fa-solid fa-angle-down ps-1"></i>
                                </button>
                                <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                    <li>
                                        <input type="checkbox" checked="checked" name="checkAll" class="smryChk-AllHistory form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Check All') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" name="issueInSummary" 
                                        onclick="showHideByClassHistory(\'issueInSection\')" class="smryCheckboxHistory summary-issue-in form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Issued In') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" name="defaultCurrency" class="smryCheckboxHistory summary-default-currencyHistory form-check-input" onclick="showHideByClassHistory(\'defaultCurSection\')" value="1">
                                        <span class="fs-13">' . showOtherLangText('Default Currency') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" name="otherCurrency" onclick="showHideByClassHistory(\'otherCurSection\')" class="smryCheckboxHistory summary-other-currencyHistory form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Other Currency') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" name="paidSection" class="smryCheckboxHistory summary-paid form-check-input"
                                        onclick="showHideByClassHistory(\'paidSection\')" value="1">
                                        <span class="fs-13">' . showOtherLangText('Paid') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked"
                                            onclick="showHideByClassHistory(\'pendingSection\')" name="pendingSection" class="form-check-input smryCheckboxHistory summary-pending" value="1">
                                        <span class="fs-13">' . showOtherLangText('Pending') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" name="issuedOut" 
                                            onclick="showHideByClassHistory(\'issueOutSection\')" class="smryCheckboxHistory summary-issue-out form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Issued Out') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" name="receiveSection"
                                            onclick="showHideByClassHistory(\'receiveSection\')" class="smryCheckboxHistory summary-issue-out-receive form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Received') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" name="issueOutPendingSection" class="smryCheckboxHistory summary-issue-out-pending form-check-input"
                                            onclick="showHideByClassHistory(\'issueOutPendingSection\')" value="1">
                                        <span class="fs-13">' . showOtherLangText('Issued Out Pending') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" name="variance" onclick="showHideByClassHistory(\'varianceRow\')" class="smryCheckboxHistory summary-variance form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Variances') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" name="converted" onclick="showHideByClassHistory(\'convertedRow\')" class="smryCheckboxHistory summary-convertedRow form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Converted') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" name="summaryAccount" onclick="showHideByClassHistory(\'accountSection\')"  class="smryCheckboxHistory summary-account form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Accounts') . '</span>
                                    </li>
                                </ul>
                            </div>

                            <div class=" dropdown">
                                <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                ' . showOtherLangText('Item Table') . '<i class="fa-solid fa-angle-down ps-1"></i>
                                </button>
                                <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                    <li>
                                        <input type="checkbox" class="itemChk-AllHistory form-check-input" checked="checked">
                                        <span class="fs-13">' . showOtherLangText('Check All') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" name="itemTaskNo" onclick="showHideByClassHistoryItem(\'head2\')"

                                ' . (isset($historyUserFilterFields) && !in_array(1, $historyUserFilterFields) ? '' : 'checked="checked"') . '
                                    

                                
                                    class="form-check-input itmTblCheckboxHistory item-taskNo" value="1">
                                        <span class="fs-13">' . showOtherLangText('Task No.') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" name="itemDate" onclick="showHideByClassHistoryItem(\'head3\')" 
                                
                                ' . (isset($historyUserFilterFields) && !in_array(2, $historyUserFilterFields) ? '' : 'checked="checked"') . '

                                    class="form-check-input itmTblCheckboxHistory item-date" value="1">
                                        <span class="fs-13">' . showOtherLangText('Date') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" name="itemUser" onclick="showHideByClassHistoryItem(\'head4\')" 

                                ' . (isset($historyUserFilterFields) && !in_array(3, $historyUserFilterFields) ? '' : 'checked="checked"') . 'class="itmTblCheckboxHistory item-user form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('User') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox"  name="itemSupInvNo" onclick="showHideByClassHistoryItem(\'head5\')" 

                                ' . (isset($historyUserFilterFields) && !in_array(8, $historyUserFilterFields) ? '' : 'checked="checked"') . 'class="itmTblCheckboxHistory item-sup-invNo form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Sup Invoice No.') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" name="itemType" class="itmTblCheckboxHistory item-type form-check-input" onclick="showHideByClassHistoryItem(\'head6\')" 

                                ' . (isset($historyUserFilterFields) && !in_array(4, $historyUserFilterFields) ? '' : 'checked="checked"') . ' value="1">
                                        <span class="fs-13">' . showOtherLangText('Type') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox"  onclick="showHideByClassHistoryItem(\'head7\')" 

                                ' . (isset($historyUserFilterFields) && !in_array(7, $historyUserFilterFields) ? '' : 'checked="checked"') . 'name="itemReferTo" class="itmTblCheckboxHistory item-referTo form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Refer to') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox"  name="itemValue" onclick="showHideByClassHistoryItem(\'head8\')" 

                                ' . (isset($historyUserFilterFields) && !in_array(10, $historyUserFilterFields) ? '' : 'checked="checked"') . 'class="itmTblCheckboxHistory item-value form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Value') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox"  name="itemDefCurrValue" onclick="showHideByClassHistoryItem(\'itemTable-def-curr\')" 

                                ' . (isset($historyUserFilterFields) && !in_array(10, $historyUserFilterFields) ? '' : 'checked="checked"') . ' class="itmTblCheckboxHistory item-def-curr-value form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Default Currency Total') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" name="itemSecCurrValue" onclick="showHideByClassHistoryItem(\'itemTable-other-curr\')" 

                                ' . (isset($historyUserFilterFields) && !in_array(10, $historyUserFilterFields) ? '' : 'checked="checked"') . '  class="itmTblCheckboxHistory item-sec-curr-value form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Second Currency Total') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" name="itemStatus" onclick="showHideByClassHistoryItem(\'head9\')" 

                                ' . (isset($historyUserFilterFields) && !in_array(14, $historyUserFilterFields) ? '' : 'checked="checked"') . 'class="itmTblCheckboxHistory item-status form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Status') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" name="itemPaymentNo" onclick="showHideByClassHistoryItem(\'head10\')" 

                                ' . (isset($historyUserFilterFields) && !in_array(15, $historyUserFilterFields) ? '' : 'checked="checked"') . 'class="itmTblCheckboxHistory item-paymentNo form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Payment no.') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" name="itemInvNo" onclick="showHideByClassHistoryItem(\'head11\')" 

                                ' . (isset($historyUserFilterFields) && !in_array(16, $historyUserFilterFields) ? '' : 'checked="checked"') . '

                                    class="form-check-input itmTblCheckboxHistory item-invNo" value="1">
                                        <span class="fs-13">' . showOtherLangText('Invoice no.') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" name="itemAccount" onclick="showHideByClassHistoryItem(\'head12\')" 
                                ' . (isset($historyUserFilterFields) && !in_array(17, $historyUserFilterFields) ? '' : 'checked="checked"') . '

                                    class="form-check-input itmTblCheckboxHistory item-account" value="1">
                                        <span class="fs-13">' . showOtherLangText('Accounts') . '</span>
                                    </li>
                                </ul>
                            </div>

                            </div>
                        
                        </div>
                        
                    </div>
                </div></form> 
                <div class="modal-body px-2 py-3">
                    <div class="row pb-3">
                        <div class="col-6">
                            <div class="address-sectionHistory modal-address"  style="display:none;">
                                <h6 class="semibold fs-14">' . $clientDetRow['accountName'] . '</h6>
                                <div class="fs-13 ">
                                    <p>' . $clientDetRow['address_one'] . '</p>
                                    <p>' . $clientDetRow['address_two'] . '</p>
                                    <p>' . $clientDetRow['city'] . ', ' . $clientDetRow['countryName'] . '</p>
                                    <p>' . $clientDetRow['email'] . '</p>
                                    <p>' . $clientDetRow['phone'] . '</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <div class="logo-sectionHistory modal-logo"  style="display:none;">';
if ($clientDetRow['logo'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . '/clientLogo/' . $clientDetRow['logo'])) {
    $content .= '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/clientLogo/' . $clientDetRow['logo'] . '" style="object-fit: scale-down; height: 50px; width: auto;">';
} else {
    $content .= '<img src="https://queue1.com//uploads/pdf-logo-sample.png" alt="Logo">';
}
$content .= '</div>
                        </div>
                    </div>

                    <div class="model-title-with-date">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h6 class="semibold">' . showOtherLangText('History Report') . '</h6>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-end fs-15 align-items-end gap-2"><small>' . showOtherLangText('From') . '</small> ' . $fromDate . ' <small>' . showOtherLangText('To') . ' </small>' . $toDate . '</p>
                            </div>
                        </div>
                    </div>';
//get issued in total and issued out total
$sql = " SELECT o.ordAmt AS totalOrdAmt, 
                    o.ordType, 
                    o.paymentStatus, 
                    od.currencyId,
                    o.supplierId,
                    o.recMemberId
                    FROM tbl_order_details od 

                    INNER JOIN tbl_orders o 
                        ON(o.id = od.ordId) AND o.account_id = od.account_id

                    WHERE o.status = '2' AND o.account_id = '" . $_SESSION['accountId'] . "' " . $cond . " ";

$issueInAndOutQry = mysqli_query($con, $sql);

$issuedInOutArr = [];
$issueInTotal = 0;
$issueOutTotal = 0;
while ($inAndOutRow = mysqli_fetch_array($issueInAndOutQry)) {


    //this is temporary fix need to do with function or main query above
    if ($inAndOutRow['ordType'] == 1 || $inAndOutRow['ordType'] == 2) {

        if ($inAndOutRow['ordType'] == 1) {
            $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '" . $_SESSION['designation_id'] . "' AND type = 'order_supplier' AND account_id = '" . $_SESSION['accountId'] . "' and type_id = '" . $inAndOutRow['supplierId'] . "' and designation_section_permission_id=1 ";
        } else {
            $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '" . $_SESSION['designation_id'] . "' AND type = 'member' AND account_id = '" . $_SESSION['accountId'] . "' and type_id = '" . $inAndOutRow['recMemberId'] . "' and designation_section_permission_id=2 ";
        }

        $checkSubPerQry = mysqli_query($con, $sql);

        if (mysqli_num_rows($checkSubPerQry) < 1) {
            continue; //exclude this order as user don't have its permission
        }
    } //end temp query


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


$issueinClass = '';
$issueoutClass = '';
$varianceClass = '';

if ($count > 1) {
    $issueinClass = ' col-12';
?>

    <script>
        if ($('.varianceRow').css('display') == 'none' || $('.convertedRow').css('display') == 'none') {

            <?php $issueoutClass = ' col-12'; ?>
        } else {
            <?php $issueoutClass = 'col-6'; ?>
        }



        if ($('.issueOutSection ').css('display') == 'none') {
            <?php $varianceClass = ' col-12'; ?>
        } else {
            <?php $varianceClass = ' col-6'; ?>
        }
    </script>

<?php


}


//width condition
$issueinClass = 'col-12';
$issueoutClass = 'col-12';
$issueInShowing = false;
$issueOutShowing = false;
if ($issueInTotal > 0 && $checkIfPermissionToNewOrderSec > 0 && ($_SESSION['getVals']['ordType'] == '' || $_SESSION['getVals']['ordType'] == 1)) {
    $issueInShowing = true;
}

if ($issueOutTotal > 0 && $checkIfPermissionToNewReqSec > 0 && ($_SESSION['getVals']['ordType'] == '' || $_SESSION['getVals']['ordType'] == 2)) {
    $issueOutShowing = true;
}

if ($issueInShowing && $issueOutShowing &&  ($count < 4)) {
    $issueinClass = 'col-6';
    $issueoutClass = 'col-6';
}


//end width conditions

//overwrite above in case order type is selcted from history page from drop list
if ($_SESSION['getVals']['ordType'] != '') {
    if ($_SESSION['getVals']['ordType'] == 1) {
        $issueinClass = 'col-12';
    } elseif ($_SESSION['getVals']['ordType'] == 2) {
        $issueoutClass = 'col-12';
    } elseif ($_SESSION['getVals']['ordType'] == 3) {
        $varianceClass = 'col-12';
    } elseif ($_SESSION['getVals']['ordType'] == 4) {
        $convertedClass = 'col-12';
    }
}

//issue in starts here
if ($issueInTotal > 0 && $checkIfPermissionToNewOrderSec > 0 && ($_SESSION['getVals']['ordType'] == '' || $_SESSION['getVals']['ordType'] == 1)) {

    $content .= '<div class="summery-row summery-row1">
                        <div class="row m-0"><input type="hidden" name="totalOtherCur" id="totalOtherCur" value="' . count($otherCurrRowArr) . '"/>
                            <div class=" ' . $issueinClass . ' issueInSection px-0 pe-1 summaryPart">
                                <div class="modal-table fs-12 w-100">
                                    <div class="table-row header-row">
                                        <div class="table-cell">&nbsp;</div>
                                        <div class="table-cell medium">' . showOtherLangText('Issued In') . '</div>';
    foreach ($otherCurrRowArr as $otherCurrRow) {

        $content .= '<div class="table-cell medium"><span class="otherCurSection summaryPart">(' . $otherCurrRow['curCode'] . ')</span></div>';
    }

    $content .= '</div>
                                    <div class="table-row thead">
                                        <div class="table-cell">' . showOtherLangText('Total') . '</div>
                                        <div class="table-cell"><span class="defaultCurSection summaryPart">' . getPriceWithCur($issueInTotal, $getDefCurDet['curCode']) . '</span></div>';
    foreach ($otherCurrTotalValueArr as $currencyId => $countOtherCurrRow) {
        $content .=  ($otherCurrTotalValueArr[$currencyId] > 0) ? '<div class="table-cell"><span class="otherCurSection summaryPart">' . showOtherCur($otherCurrTotalValueArr[$currencyId], $currencyId) . '</span></div>' : '<div class="table-cell">&nbsp;</div>';
    }

    $content .= '</div>';
    $content .= '<div class="table-row">
                                        <div class="table-cell paidSection summaryPartCell">' . showOtherLangText('Paid') . '</div>
                                        <div class="table-cell font-bold issue-in-def-curr paidSection summaryPartCell"><span class="defaultCurSection summaryPartCell"> ' . (($issuedInOutPaidArr[1][1] > 0) ? getPriceWithCur($issuedInOutPaidArr[1][1], $getDefCurDet['curCode']) : '') . '</span></div>';
    foreach ($otherCurrRowArr as $currencyId => $countOtherCurrRow) {
        $content .=  ($otherCurrPaidTotalValueArr[$currencyId] > 0) ? '<div class="table-cell issue-in-oth-curr paidSection summaryPartCell"><span class="otherCurSection summaryPartCell">' . showOtherCur($otherCurrPaidTotalValueArr[$currencyId], $currencyId) . '</span></div>' : '<div class="table-cell paidSection summaryPartCell">&nbsp;</div>';
    }

    $content .= '</div>
                                    <div class="table-row">
                                        <div class="pendingSection summaryPartCell table-cell">' . showOtherLangText('Pending') . '</div>
                                        <div class="issue-in-def-curr pendingSection summaryPartCell table-cell font-bold"><span class="pendingSection defaultCurSection summaryPartCell">' . (($issuedInOutPendingArr[1][0] > 0) ? getPriceWithCur($issuedInOutPendingArr[1][0], $getDefCurDet['curCode']) : '') . '</span></div>
                                    ';
    foreach ($otherCurrRowArr as $currencyId => $countOtherCurrRow) {
        $content .= ($otherCurrPendingTotalValueArr[$currencyId] > 0) ? '<div class="issue-in-def-curr pendingSection  summaryPartCell table-cell"><span class="pendingSection otherCurSection  summaryPartCell">' . showOtherCur($otherCurrPendingTotalValueArr[$currencyId], $currencyId) . '</span></div>' : '<div></div>';
    }
    $content .= '</div></div>
                            </div>';
} //end issue in section

//issue out starts here
if ($issueOutTotal > 0 && $checkIfPermissionToNewReqSec > 0 && ($_SESSION['getVals']['ordType'] == '' || $_SESSION['getVals']['ordType'] == 2)) {
    $content .= '<div class=" ' . $issueoutClass . ' issueOutSection pe-1 ps-0 summaryPart">
                                <div class="modal-table fs-12 w-100">
                                    <div class="table-row header-row">
                                        <div class="table-cell">&nbsp;</div>
                                        <div class="table-cell medium">' . showOtherLangText('Issued Out') . '</div>
                                    </div>
                                    <div class="table-row thead">
                                        <div class="table-cell">
                                                        ' . showOtherLangText('Total') . '</div>
                                        <div class="issue-out-section table-cell">
                                                        ' . getPriceWithCur($issueOutTotal, $getDefCurDet['curCode']) . '</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell receiveSection summaryPartCell">' . showOtherLangText('Received') . '</div>
                                        <div class="receiveSection summaryPartCell table-cell font-bold">' . (($issuedInOutPaidArr[2][1]) ? getPriceWithCur($issuedInOutPaidArr[2][1], $getDefCurDet['curCode']) : '') . '</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="issueOutPendingSection summaryPartCell table-cell">' . showOtherLangText('Pending') . '</div>
                                        <div class="issueOutPendingSection summaryPartCell table-cell font-bold">' . (($issuedInOutPendingArr[2][0] > 0) ? getPriceWithCur($issuedInOutPendingArr[2][0], $getDefCurDet['curCode']) : '') . '</div>
                                    </div>
                                </div>
                            </div>';
} //end issue out
$variancesPosTot = 0;
$variancesPosQtyTot = 0;
$variancesNevQtyTot = 0;
$variancesNevTot = 0;
$varaincesVal = 0;

$varOrConverted = false;

//converted starts condition only starts so that in vairance width cond.. can be added
if ($_SESSION['getVals']['ordType'] == '' || $_SESSION['getVals']['ordType'] == 4) {

    $varOrConverted = true;
    $sqlSet = " SELECT SUM(ordAmt) totConvertedAmt FROM  tbl_orders o  WHERE ordType = '4' 
                                        AND status = '2' AND account_id = '" . $_SESSION['accountId'] . "' " . $condWithoutGroup . " GROUP BY ordType ";

    $resultSet = mysqli_query($con, $sqlSet);
    $convertedResRow = mysqli_fetch_array($resultSet);
}

//variance starts here
$varianceClass = 'col-12';
if ($_SESSION['getVals']['ordType'] == '' || $_SESSION['getVals']['ordType'] == 3) {


    $sqlSet = " SELECT od.* FROM tbl_order_details od
                        INNER JOIN tbl_orders o
                            ON(o.id=od.ordId) AND o.account_id=od.account_Id
                        WHERE o.ordType = '3' AND o.status = '2' AND o.account_id = '" . $_SESSION['accountId'] . "' " . $condWithoutGroup . " ";
    $resultSet = mysqli_query($con, $sqlSet);
    while ($resRow = mysqli_fetch_array($resultSet)) {
        $varaincesVal = $resRow['qtyReceived'] - $resRow['qty'];

        if ($varaincesVal > 0) {
            $variancesPosTot += ($varaincesVal * $resRow['stockPrice']);
        } elseif ($varaincesVal < 0) {
            $variancesNevTot += ($varaincesVal * $resRow['stockPrice']);
        }
    }

    if ($variancesPosTot || $variancesNevTot) {

        $varianceClass = $varOrConverted ? 'col-6' : 'col-12';

        $content .= '<div class=" ' . $varianceClass . ' varianceRow ps-0 pe-1 summaryPart" id="varianceId">
                                <div class="table-row header-row" style=" font-size: 12px; line-height: normal;">
                    <div class="table-cell medium">' . showOtherLangText('Variance') . '</div>
                                    </div>
                                <div class="modal-table fs-12 w-100">
                                    <div class="table-row thead">
                                        <div class="table-cell text-success"><i class="fa-solid fa-long-arrow-up pe-1"></i>' . getPriceWithCur($variancesPosTot, $getDefCurDet['curCode']) . '</div>
                                        <div class="table-cell text-danger"><i class="fa-solid fa-long-arrow-down pe-1"></i>' . getPriceWithCur($variancesNevTot, $getDefCurDet['curCode']) . '</div>
                                    </div>
                                </div>
                            </div>
                           ';
    }

    $varOrConverted = true;
}


//converted starts here
if ($_SESSION['getVals']['ordType'] == '' || $_SESSION['getVals']['ordType'] == 4) {


    if ($convertedResRow['totConvertedAmt']) {

        $convertedClass = $varianceClass;

        $content .= '<div class="' . $convertedClass . '  convertedRow ps-0 pe-1 summaryPart" id="convertedId">
                                    <div class="table-row header-row" style=" font-size: 12px; line-height: normal;">
                                        <div class="table-cell medium">' . showOtherLangText('Converted') . '</div>
                                    </div>
                                    <div class="modal-table fs-12 w-100">
                                        <div class="table-row thead">
                                            <div class="table-cell">' . getPriceWithCur($convertedResRow['totConvertedAmt'], $getDefCurDet['curCode']) . '</div>
                                        </div>
                                    </div>
                            </div>';
    }
}


$_SESSION['issueinClass'] = $issueinClass;
$_SESSION['issueoutClass'] = $issueoutClass;
$_SESSION['convertedClass'] = $convertedClass;
$_SESSION['varianceClass'] = $varianceClass;

if ($varOrConverted) {
    $content .= ' </div></div></div>';
}





$sqlSet = " SELECT c.curCode, a.* FROM  tbl_accounts a 
            INNER JOIN tbl_currency c 
                ON( c.id=a.currencyId) AND c.account_Id=a.account_Id
            WHERE a.account_id = '" . $_SESSION['accountId'] . "' AND a.id IN(" . $bankAccountIdsStr . ") ";
$result = mysqli_query($con, $sqlSet);

if (mysqli_num_rows($result) > 0) {

    $content .= '<div  class="overflowTable accountSection summaryPart px-2"> 
                        <div class="modal-table fs-12 w-100 mt-4 historyAccountSection">
                            <div class="table-row header-row">
                                <div class="table-cell medium">' . showOtherLangText('Accounts') . '</div>
                                <div class="table-cell">&nbsp;</div>
                                <div class="table-cell">&nbsp;</div>
                                <div class="table-cell">&nbsp;</div>
                                <div class="table-cell">&nbsp;</div>
                                <div class="table-cell">&nbsp;</div>
                            </div>';

    $content .= '<div class="table-row thead">';
    $i = 0;
    while ($resultRow = mysqli_fetch_array($result)) {
        if ($i % 6 == 0 && $i != 0) {
            $content .= '</div><div class="table-row thead">';
        }
        $curCode = $resultRow['curCode'];
        $balanceAmt = round($resultRow['balanceAmt'], 4);

        $content .= '<div class="table-cell"> ' . number_format($balanceAmt) . '
                        ' . $curCode . '<small>' . $resultRow['accountName'] . '</small></div>';
        $i++;
    }
    $content .= '</div>';


    $content .= '</div>
                    </div>';
} //end account cond

$content .= '<div class="overflowTable px-2 table__striped__bg"> 
                        <div class="modal-table fs-12 w-100 mt-4">
                            <div class="table-row thead">';
$payInvText = '<span class="head10 itemSectionPart">' . showOtherLangText('Pay') . '</span> <span class="head11">' . showOtherLangText('Inv No.') . '</span>';

if (isset($historyUserFilterFields) && !in_array($headerArrOptions[10], $historyUserFilterFields)) {
    $payInvText = '<span class="head10 itemSectionPart" style="display:none;">' . showOtherLangText('Pay') . '</span> <span class="head11">' . showOtherLangText('Inv No.') . '</span>';
}

if (isset($historyUserFilterFields)  && !in_array($headerArrOptions[11], $historyUserFilterFields)) {
    $payInvText = '<span class="head10 itemSectionPart">' . showOtherLangText('Pay') . '</span> <span class="head11" style="display:none;">' . showOtherLangText('Inv No.') . '</span>';
}

if (isset($historyUserFilterFields) && !in_array($headerArrOptions[10], $historyUserFilterFields) && !in_array($headerArrOptions[11], $historyUserFilterFields)) {
    $payInvText = '<span class="head10 itemSectionPart" style="display:none;">' . showOtherLangText('Pay') . '</span> <span class="head11" style="display:none;">' . showOtherLangText('Inv No.') . '</span>';
}


$headerArr  = [
    1 => '' . showOtherLangText('#') . '',

    2 => '' . showOtherLangText('Task No.') . '',

    3 => '' . showOtherLangText('Date') . '',

    4 => '' . showOtherLangText('User') . '',

    5 => '' . showOtherLangText('Sup inv') . '',

    6 => '' . showOtherLangText('Type') . '',

    7 => '' . showOtherLangText('Refer To') . '',

    8 => '' . showOtherLangText('Value') . '',

    9 => '' . showOtherLangText('Status') . '',

    11 => $payInvText,

    12 => '' . showOtherLangText('Account') . '',
];
if (isset($_SESSION['getVals']['ordType'])) {
    switch ($_SESSION['getVals']['ordType']) {
        case 1: //IssueIn
            unset($headerArr[10]);
            break;

        case 2: //IssueOut
            unset($headerArr[5]);
            //unset( $headerArr[9] );
            break;

        case 3: //stockTake
            unset($headerArr[5]);
            //unset( $headerArr[7] );
            unset($headerArr[9]);
            unset($headerArr[10]);
            unset($headerArr[11]);
            unset($headerArr[12]);
            break;

        case 4: //raw convert item
            // unset( $headerArr[6] );
            unset($headerArr[5]);
            unset($headerArr[7]);
            unset($headerArr[9]);
            unset($headerArr[10]);
            unset($headerArr[11]);
            unset($headerArr[12]);
            break;
    }
}

if (isset($_SESSION['getVals']['suppMemStoreId']) && $getTxtById == 'storeId') {
    // unset( $headerArr[7] );
    unset($headerArr[5]);
    unset($headerArr[9]);
    unset($headerArr[10]);
    unset($headerArr[11]);
    unset($headerArr[12]);
}

if (isset($_SESSION['getVals']['suppMemStoreId']) && $getTxtById == 'deptUserId') {
    unset($headerArr[5]);
    // unset( $headerArr[9] );
}

if (isset($_SESSION['getVals']['suppMemStoreId']) && $getTxtById == 'suppId') {
    // unset( $headerArr[7] );
    // unset( $headerArr[9] );
    unset($headerArr[10]);
    //unset( $headerArr[11] );
    //unset( $headerArr[12] );
}

if (isset($_SESSION['getVals']['statusType']) && $_SESSION['getVals']['statusType'] == 2) {
    //unset( $colsArr[7] );
    unset($headerArr[5]);
}
if (isset($_SESSION['getVals']['statusType']) && $_SESSION['getVals']['statusType'] == 3) {
    //unset( $colsArr[7] );
    unset($headerArr[11]);
    unset($headerArr[12]);
}

foreach ($headerArr as $key => $header) {

    $columnClass = '';
    $style = "";
    if ($key == 11) {
        $key = '';
    } else {


        if ($key != 1 && $key != 11 && isset($historyUserFilterFields) && !in_array($headerArrOptions[$key], $historyUserFilterFields)) {
            $style = 'style="display:none;"';
        }
    }

    if ($key == 8) {
        $columnClass = 'valueItem';
    }

    $content .= '<div class="table-cell"><span class="head' . $key . ' itemSectionPart ' . $columnClass . '" ' . $style . '>' . $header . '</span></div>';
    // $content .=  '<th><span class="head'.$key.' itemSectionPart '.$columnClass.'" '.$style.'>'.$header.'</div></th>';
}
$content .= '</div>';
$i = 0;
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

    $i++;
    // supplier Store Member name
    if ($orderRow['suppName'] == '' && $orderRow['storeName'] == '') {
        $suppMemStoreId = $orderRow['deptUserName'];
    } elseif ($orderRow['deptUserName'] == '' && $orderRow['storeName'] == '') {
        $suppMemStoreId = $orderRow['suppName'];
    } else {
        $suppMemStoreId = $orderRow['storeName'];
    }

    $paymentId = ($orderRow['paymentId']) ? $orderRow['paymentId'] : '';
    $InvoiceNumber = $orderRow['invNo'];

    //Payment status
    if ($orderRow['paymentStatus'] == 1 && $orderRow['ordType'] == 1) {
        $paymentStatus = '<span style="color:#16C0FF;font-weight: bold;">' . showOtherLangText('Paid') . '</span>';
    } elseif ($orderRow['paymentStatus'] == 1 && $orderRow['ordType'] == 2) {
        $paymentStatus = '<span style="color:#038F00;font-weight: bold;" class="issue-out-section summaryPart">' . showOtherLangText('Received') . '</span>';
    } elseif ($orderRow['ordType'] == 3 || $orderRow['ordType'] == 4) {
        $paymentStatus = ' ';
    } elseif (($orderRow['ordType'] == 2 && $orderRow['receive_inv'] == 0) || $orderRow['ordType'] == 2 && $accessInvoicePermission['type_id'] == 0) {
        $paymentStatus = ' ';
    } elseif ($orderRow['ordType'] == 1 && $accessPaymentPermission['type_id'] == 0) {
        $paymentStatus = ' ';
    } else {
        $paymentStatus = '<span style="color:#FF9244;font-weight: bold;" class="issue-out-section summaryPart">' . showOtherLangText('Pending') . '</span>';
    }


    // for date time filter
    if ($_SESSION['getVals']['dateType'] == 1) {
        $dateType = ($orderRow['ordDateTime'] != '0000-00-00 00:00:00' ? date('d/m/y h:i', strtotime($orderRow['ordDateTime'])) : '');
    } elseif ($_SESSION['getVals']['dateType'] == 2) {
        $dateType = ($orderRow['setDateTime'] != '0000-00-00 00:00:00' ? date('d/m/y h:i', strtotime($orderRow['setDateTime'])) : '');
    } elseif ($_SESSION['getVals']['dateType'] == 3) {
        $dateType = ($orderRow['paymentDateTime'] != '0000-00-00 00:00:00' ? date('d/m/y h:i', strtotime($orderRow['paymentDateTime'])) : date('d/m/y h:i', strtotime($orderRow['setDateTime'])));
    } else {
        $dateType = ($orderRow['setDateTime'] != '0000-00-00 00:00:00' ? date('d/m/y h:i', strtotime($orderRow['setDateTime'])) : '');
    }



    $sqlSet = " SELECT * FROM tbl_user WHERE id = '" . $orderRow['orderBy'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";

    $resultSet = mysqli_query($con, $sqlSet);

    $userArr = mysqli_fetch_array($resultSet);

    $user = $userArr['name'];

    $curDet =  getCurrencyDet($orderRow['ordCurId']);

    if ($orderRow['ordType'] == 1) {
        $ordType = '<span style="font-weight: bold;">' . showOtherLangText('Issued In') . '</span>';
    } elseif ($orderRow['ordType'] == 2) {
        $ordType = '<span style="font-weight: bold;">' . showOtherLangText('Issued Out') . '</span>';
    } elseif ($orderRow['ordType'] == 3) {
        $variances = getOrdItemVariancesAmtNew($orderRow['id']);

        $variancesPosTotAmt = $variances['variancesPosTot'];
        $variancesNevTotAmt = $variances['variancesNevTot'];

        $ordType = '<span style="font-weight: bold;">' . showOtherLangText('Stock Take') . '</span>';
    } else {
        $variances = getOrdItemVariancesAmt($orderRow['id']);

        $variancesTotAmt = $variances['variancesTot'];

        $ordType = '<span style="font-weight: bold;">' . showOtherLangText('Raw Item Convert') . '</span>';
    }

    $columnVal = $ordType;

    $paymntOrInv = '';
    if ($orderRow['ordType'] == 2) {
        $paymntOrInv = $InvoiceNumber;
    } elseif ($orderRow['paymentStatus'] > 0) {
        $paymntOrInv = setPaymentId($paymentId);
    }

    $colsValArr = [
        1 => $i,

        2 => $orderRow['ordNumber'],

        3 => $dateType,

        4 => $user,

        5 => ($orderRow['ordType'] == 1) ? $InvoiceNumber : '',

        6 => $ordType,

        7 => $suppMemStoreId,

        8 => (($orderRow['ordAmt'] > 0) ? '<span style="font-weight: bold;" class="itemSectionPart itemTable-def-curr">' . getPrice($orderRow['ordAmt']) . ' ' . $getDefCurDet['curCode'] . '</span>' : '<span style="color:#000;font-weight: bold;" class="itemTable-def-curr">' . getPrice($variancesPosTotAmt) . ' ' . $getDefCurDet['curCode'] . '</span>' . '<br>' . '<span style="font-weight: bold; color:#f00" class="itemTable-def-curr">-' . getPrice($variancesNevTotAmt) . ' ' . $getDefCurDet['curCode'] . '</span>')

            . "<br>" .

            (($orderRow['ordCurAmt'] > 0) ? '<span style="font-weight: bold;" class="itemSectionPart itemTable-other-curr">' . showOtherCur($orderRow['ordCurAmt'], $curDet['id']) . '</span>' : ''),

        9 => $paymentStatus,

        11 => $paymntOrInv,

        12 => ($orderRow['paymentStatus'] == 1 ? $orderRow['accountName'] : ''),
    ];


    if (isset($_SESSION['getVals']['ordType'])) {
        switch ($_SESSION['getVals']['ordType']) {
            case 1: //IssueIn
                unset($colsValArr[10]);
                break;

            case 2: //IssueOut
                unset($colsValArr[5]);
                //unset( $colsValArr[9] );
                break;

            case 3: //stockTake
                unset($colsValArr[5]);
                // unset( $colsValArr[7] );
                unset($colsValArr[9]);
                unset($colsValArr[10]);
                unset($colsValArr[11]);
                unset($colsValArr[12]);
                break;

            case 4: //raw convert item
                // unset( $colsValArr[6] );
                unset($colsValArr[5]);
                unset($colsValArr[7]);
                unset($colsValArr[9]);
                unset($colsValArr[10]);
                unset($colsValArr[11]);
                unset($colsValArr[12]);
                break;
        }
    }

    if (isset($_SESSION['getVals']['suppMemStoreId']) && $getTxtById == 'storeId') {
        unset($colsValArr[5]);
        unset($colsValArr[9]);
        unset($colsValArr[10]);
        unset($colsValArr[11]);
        unset($colsValArr[12]);
    }

    if (isset($_SESSION['getVals']['statusType']) && $_SESSION['getVals']['statusType'] == 2) {
        unset($colsValArr[5]);
    }

    if (isset($_SESSION['getVals']['statusType']) && $_SESSION['getVals']['statusType'] == 3) {
        unset($colsValArr[11]);
        unset($colsValArr[12]);
    }

    if (isset($_SESSION['getVals']['suppMemStoreId']) && $getTxtById == 'deptUserId') {
        unset($colsValArr[5]);
        //unset( $colsValArr[9] );
    }

    if (isset($_SESSION['getVals']['suppMemStoreId']) && $getTxtById == 'suppId') {
        // unset( $colsValArr[7] );
        // unset( $colsValArr[9] );
        unset($colsValArr[10]);
        //  unset( $colsValArr[11] );
        //  unset( $colsValArr[12] );
    }

    // Open tr tag
    $content .=  '<div class="table-row">';

    foreach ($colsValArr as $key => $columnVal) {

        $columnClass = '';
        if ($key == 8) {
            $columnClass = 'valueItem';
        }

        $style = "";
        if ($key != 1 && $key != 11 && isset($historyUserFilterFields) && !in_array($headerArrOptions[$key], $historyUserFilterFields)) {
            $style = 'style="display:none;"';
        }


        if ($orderRow['ordType'] == 2 && $key == 11) {
            if (isset($historyUserFilterFields) && !in_array($headerArrOptions[11], $historyUserFilterFields)) {
                $style = 'style="display:none;"';
            }
            $content .= '<div class="table-cell"> <span class="itemSectionPart head11" ' . $style . ' >' . $columnVal . '</span></div>'; // to handle show hide of issue out which is invoice
        } elseif ($orderRow['paymentStatus'] > 0 && $key == 11) {
            if (isset($historyUserFilterFields) && !in_array($headerArrOptions[10], $historyUserFilterFields)) {
                $style = 'style="display:none;"';
            }
            $content .= '<div class="table-cell"> <span class="itemSectionPart head10" ' . $style . ' >' . $columnVal . '</span></div>'; // to handle show hide of issue in which is payment
        } else {
            $content .= '<div class="table-cell"><span class="itemSectionPart head' . $key . ' ' . $columnClass . '" ' . $style . ' ' . $columnClass . '>' . $columnVal . '</span></div>'; // print all data row wise in td
        }
    }

    $content .= '</div>'; // Close tr tag
}




$content .= '</div></div>
                        </div>
                    </div>
                </div>';
echo $content;
