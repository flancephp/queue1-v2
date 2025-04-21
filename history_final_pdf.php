<?php
include('inc/dbConfig.php'); //connection details

/*echo '<pre>';

print_r($_REQUEST);
die;*/
//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$checkIfPermissionToNewOrderSec = checkIfPermissionToNewOrderSec($_SESSION['designation_id'], $_SESSION['accountId']);
$checkIfPermissionToNewOrderSec = mysqli_num_rows($checkIfPermissionToNewOrderSec);

$checkIfPermissionToNewReqSec = checkIfPermissionToNewReqSec($_SESSION['designation_id'], $_SESSION['accountId']);
$checkIfPermissionToNewReqSec = mysqli_num_rows($checkIfPermissionToNewReqSec);

$cond = '';
$cond1 = '';

//From Date || To Date Filter
if (isset($_SESSION['getVals']['fromDate']) && $_SESSION['getVals']['fromDate'] != '' && $_SESSION['getVals']['toDate'] != '') {
    $cond = " AND DATE(setDateTime) BETWEEN '" . date('Y-m-d', strtotime($_SESSION['getVals']['fromDate'])) . "' AND '" . date('Y-m-d', strtotime($_SESSION['getVals']['toDate'])) . "'   ";
    $cond1 = $cond;
    $fromDate = $_SESSION['getVals']['fromDate'];
    $toDate = $_SESSION['getVals']['toDate'];
} elseif (isset($_SESSION['fromDate']) && $_SESSION['fromDate'] != '' && $_SESSION['toDate'] != '') {
    $cond = " AND DATE(o.setDateTime) BETWEEN '" . date('Y-m-d', strtotime($_SESSION['fromDate'])) . "' AND '" . date('Y-m-d', strtotime($_SESSION['toDate'])) . "' ";
    $cond1 = $cond;
    $fromDate = $_SESSION['fromDate'];
    $toDate = $_SESSION['toDate'];
} else {
    $_GET['fromDate'] = date('d-m-Y', strtotime('-3 days'));
    $_GET['toDate'] = date('d-m-Y');

    $cond = " AND DATE(o.setDateTime) BETWEEN '" . date('Y-m-d', strtotime($_GET['fromDate'])) . "' AND '" . date('Y-m-d', strtotime($_GET['toDate'])) . "' ";
    $cond1 = $cond;

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

WHERE o.status = 2 AND o.account_id = '" . $_SESSION['accountId'] . "'

AND (o.ordType=1 AND  dp.id > 0 OR o.ordType=2 AND  dp1.id > 0 OR o.ordType=3 OR o.ordType=4 )
" . $cond . " ";

$historyQry = mysqli_query($con, $mainSqlQry);

// Get full address and their logo of client
$sql = " SELECT c.name AS countryName, cl.* FROM tbl_client cl
LEFT JOIN tbl_country c ON(cl.country=c.id)
WHERE cl.id = '" . $_SESSION['accountId'] . "' ";
$result = mysqli_query($con, $sql);
$clientDetRow = mysqli_fetch_array($result);


// get issued in total and issued out total
$sql = " SELECT o.ordAmt AS totalOrdAmt, 
o.ordType, 
o.paymentStatus, 
od.currencyId,
o.supplierId, o.recMemberId
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
    } elseif ($inAndOutRow['paymentStatus'] > 0) { // Sum of total paid order amount
        $totalSumAmt = $inAndOutRow['totalOrdAmt'];
        $issuedInOutPaidArr[$inAndOutRow['ordType']][$inAndOutRow['paymentStatus']] += $totalSumAmt;
    }
}



$otherCurrRowArr = [];
$otherCurrTotalValueArr = [];



$sql = " SELECT od.currencyId, c.curCode, o.ordCurAmt AS totalOrdCurAmt, o.paymentStatus FROM tbl_order_details od
    INNER JOIN tbl_orders o 
        ON(o.id=od.ordId) AND o.account_id=od.account_Id
    INNER JOIN tbl_currency c
        ON(od.currencyId=c.id) AND od.account_id=c.account_Id
    WHERE o.status = '2' AND  o.account_id = '" . $_SESSION['accountId'] . "'  " . $cond . " ";
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

$bankAccountIdsStr = getAccountIds($_SESSION['accountId'], $condWithoutGroup); //get this to pass into below query in account list section




$variancesPosTot = 0;
$variancesPosQtyTot = 0;
$variancesNevQtyTot = 0;
$variancesNevTot = 0;
$varaincesVal = 0;

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
}



$sqlSet = " SELECT c.curCode, a.* FROM  tbl_accounts a 
INNER JOIN tbl_currency c 
    ON( c.id=a.currencyId) AND c.account_Id=a.account_Id
WHERE a.account_id = '" . $_SESSION['accountId'] . "' AND a.id IN(" . $bankAccountIdsStr . ") ";
$accountResult = mysqli_query($con, $sqlSet);

// ==========================================

$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#198754" d="m12 4l-.707-.707l.707-.707l.707.707zm1 15a1 1 0 1 1-2 0zM5.293 9.293l6-6l1.414 1.414l-6 6zm7.414-6l6 6l-1.414 1.414l-6-6zM13 4v15h-2V4z" /></svg>';
$svgDown = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="#dc3545" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 19l6-6m-6 6l-6-6m6 6V5" /></svg>';


$content = '<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>PDF Design</title>
      <link rel="preconnect" href="https://fonts.googleapis.com/">
      <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
      <link rel="preconnect" href="https://fonts.googleapis.com/css2?family=Noto+Sans+Hebrew:wght@300;400&display=swap" rel="stylesheet">
      <link href="./PDF Design_files/css2" rel="stylesheet">
     <style>
        @page { margin: 10px 10px; }
        th {
            text-align:left;
        }
    </style>
   </head>';
$content .= '<body style="' . ($getLangType == '1'
    ? 'font-family: firefly, DejaVu Sans, sans-serif, Inter; color: #232859; font-weight: 400; font-size: 12px; line-height: 14px;'
    : 'font-family: \'Inter\', sans-serif; color: #232859; font-weight: 400; font-size: 12px; line-height: 14px;') . '">';
$content .= '<table style="width: 100%; border-spacing: 0; padding-bottom: 16px;">
         <tbody>';
if ($_GET['address'] == 1 || $_GET['logo'] == 1) {
    $content .= '<tr valign="top">';
    if ($_GET['address'] == 1) {
        $content .= '<td width="50%">';
        $content .= '<table style="width: 100%; border-spacing: 0;">
                     <tbody>
                        <tr>
                           <td>' . $clientDetRow['accountName'] . '</td>
                        </tr>
                     </tbody>
                     </table>';
        $content .= '<table style="width: 100%; border-spacing: 0; margin-top: 5px;">
                     <tbody>
                        <tr>
                           <td style="font-size: 13px; line-height: 15px; font-weight: 400;">
                              ' . $clientDetRow['address_one'] . ' <br>' . $clientDetRow['address_two'] . '
                              ' . $clientDetRow['city'] . ', ' . $clientDetRow['countryName'] . '<br>
                              ' . $clientDetRow['email'] . '<br>
                              ' . $clientDetRow['phone'] . '
                           </td>
                        </tr>
                     </tbody>
                  </table>';
        $content .= '</td>';
    }

    if ($_GET['logo'] == 1) {
        $content .= '<td width="50%" align="right">';
        if ($clientDetRow["logo"] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/clientLogo/" . $clientDetRow["logo"])) {
            $content .= '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/clientLogo/' . $clientDetRow['logo'] . '" style="object-fit: scale-down; height: 60px; width: auto;">';
        } else {
            $content .= '<img src="' . $siteUrl . 'uploads/pdf-logo-sample.png" alt="Logo" style="object-fit: scale-down; height: 60px; width: auto;">';
        }
        $content .= '</td>';
    }
    $content .= '</tr>';
}
$content .= '</tbody>
      </table>
      <table style="width: 100%; border-collapse: collapse; margin-bottom: 32px;">
        <tr>
            <td style="width: 50%; border-top: 1px solid #d2d2d2; border-bottom: 1px solid #d2d2d2; padding-top: 15px;">
                <div style="display: flex; align-items: center;">
                    <div style="flex: 1;">
                        <h6 style="font-weight: bold; font-size: 15px; margin: 0;line-height:18px;">' . showOtherLangText('History Report') . '</h6>
                    </div>
                    <div style="flex: 1; text-align: right; font-size: 15px;margin:0;line-height:18px;">
                        <small>' . showOtherLangText('From') . '</small> ' . $fromDate . '<small> ' . showOtherLangText('To') . '</small> ' . $toDate . '
                    </div>
                </div>
            </td>
        </tr>
    </table>';
$count = 0;
foreach ($otherCurrRowArr as $otherCurrRow) {
    $count++;
    $otherCurrRow;
}


if ($count > 1 && $_GET['issueInSummary'] == 1 && $_GET['issuedOut'] == 1) {

    $issueinClass = 'width: 70%';
    $issueoutClass = 'width: 30%';
} elseif ($_GET['issueInSummary'] == 1 && $_GET['issuedOut'] == 1) {
    $issueinClass = 'width: 50%';
    $issueoutClass = 'width: 50%';
} elseif ($_GET['issueInSummary'] == 1) {
    $issueinClass = 'width: 100%';
} elseif ($_GET['issuedOut'] == 1) {
    $issueoutClass = 'width: 100%';
}


if ($_GET['variance'] == 1 && $_GET['converted'] == 1) {

    $varianceClass = 'width: 50%';
    $convertedClass = 'width: 50%';
} elseif ($_GET['variance'] == 1) {

    $varianceClass = 'width: 100%';
} elseif ($_GET['converted'] == 1) {

    $convertedClass = 'width: 100%';
}

$tabelrowClass = 'display:table-cell';
$tdwidth = 'width:100%';


/*if ($_GET['issuedOut'] == 1 && $_GET['issueInSummary'] == 1) {
    $issueinClass = 'width: 50%;';
    $issueoutClass = 'width: 50%;';
    $tabelrowClass = 'display:table-cell';
    $tdwidth = 'width:100%';
} elseif ($_GET['issuedOut'] == 1) {
    $issueoutClass = 'width: 100%;';
    $tabelrowClass = 'display:table-cell';
    $tdwidth = 'width:100%';
} elseif ($_GET['issueInSummary'] == 1) {
    $issueinClass = 'width: 100%;';
    $tabelrowClass = 'display:table-cell';
    $tdwidth = 'width:100%';
}

if ($_GET['variance'] == 1 && $_GET['converted'] == 1) {

    $varianceClass = 'width: 50%;';
    $convertedClass = 'width: 50%;';
    $tabelrowClass = 'display:table-cell';
    $tdwidth = 'width:100%';
} elseif ($_GET['variance'] == 1) {

    $varianceClass = 'width: 100%;';
    $tabelrowClass = 'display:table-cell';
    $tdwidth = 'width:100%';
} elseif ($_GET['converted'] == 1) {

    $convertedClass = 'width: 100%;';
    $tabelrowClass = 'display:table-cell';
    $tdwidth = 'width:100%';
}*/



if ($checkIfPermissionToNewOrderSec == 0 || ($_SESSION['getVals']['ordType'] != '' && $_SESSION['getVals']['ordType'] != 1)) {
    $_GET['issueInSummary'] = 0;
}

if ($checkIfPermissionToNewReqSec == 0 || ($_SESSION['getVals']['ordType'] != '' && $_SESSION['getVals']['ordType'] != 2)) {
    $_GET['issuedOut'] = 0;
}

if (($_SESSION['getVals']['ordType'] != '' && $_SESSION['getVals']['ordType'] != 3)) {
    $_GET['variance'] = 0;
}

if (($_SESSION['getVals']['ordType'] != '' && $_SESSION['getVals']['ordType'] != 4)) {
    $_GET['converted'] = 0;
}

$content .= '<table style="font-size:12px;" width="98%">
        <tr style="vertical-align: baseline;">';

//issue in total
if ($_GET['issueInSummary'] == 1 && $issueInTotal > 0) {
    $content .= '<td style="' . $issueinClass . '"><table style="width:100%; margin-right:1px; font-size:12px; border-collapse: collapse;">
                    <tr style="font-weight:bold;">
                        <td style="padding: 8px 5px;">&nbsp;</td>';
    $content .= '<td style="padding: 8px 5px;">' . showOtherLangText('Issued In') . '</td>';
    if ($_GET['otherCurrency'] == 1 && !empty($otherCurrRowArr)) {
        foreach ($otherCurrRowArr as $otherCurrRow) {
            $content .= '<td style="padding: 8px 5px;">(' . $otherCurrRow['curCode'] . ')</td>';
        }
    }
    $content .= '</tr>
                    <tr style="background-color: rgba(122, 137, 255, 0.2); font-weight:bold;">
                        <td style="padding: 8px 5px;">' . showOtherLangText('Total') . '</td>';
    $content .= ($_GET['defaultCurrency'] == 1 && $issueInTotal > 0) ? '<td style="padding: 8px 5px;">' . getPriceWithCur($issueInTotal, $getDefCurDet['curCode']) . '</td>' : '<td style="padding: 8px 5px;">&nbsp;</td>';
    if ($_GET['otherCurrency'] == 1) {
        // Issue In other currency total
        foreach ($otherCurrRowArr as $currencyId => $countOtherCurrRow) {
            //$content .= '<td style="padding: 8px 5px;">3,200 $</td>';
            $content .= ($otherCurrTotalValueArr[$currencyId] > 0) ?  '<td style="padding: 8px 5px;">' . showOtherCur($otherCurrTotalValueArr[$currencyId], $currencyId) . '</td>' :  '<td style="padding: 8px 5px;">&nbsp;</td>';
        }
    }
    $content .= '</tr>';

    if ($_GET['paidSection'] == 1) {
        $content .=  '<tr>
                            <td style="padding: 8px 5px;">' . showOtherLangText('Paid') . '</td>';
        // $content .=  '<td style="font-weight:bold; padding: 8px 5px;">2,000 $</td>';
        $content .= ($_GET['defaultCurrency'] == 1 && $issuedInOutPaidArr[1][1] > 0) ? '<td style="font-weight:bold; padding: 8px 5px;">' . getPriceWithCur($issuedInOutPaidArr[1][1], $getDefCurDet['curCode']) . '</td>' : '<td style="font-weight: bold;padding: 5px 5px;">&nbsp;</td>';
        if ($_GET['otherCurrency'] == 1) {
            foreach ($otherCurrRowArr as $currencyId => $countOtherCurrRow) {
                //$content .=  '<td style="padding: 8px 5px;">1,200 $</td>';
                $content .= ($otherCurrPaidTotalValueArr[$currencyId] > 0) ?  '<td style="padding: 8px 5px;" class="issue-in-oth-curr">' . showOtherCur($otherCurrPaidTotalValueArr[$currencyId], $currencyId) . '</td>' :  '<td style="padding: 8px 5px;">&nbsp;</td>';
            }
        }
        $content .=  '</tr>';
    }

    if ($_GET['pendingSection'] == 1) {

        $content .=  '<tr>
                        <td style="padding: 8px 5px;">' . showOtherLangText('Pending') . '</td>';

        $content .= ($_GET['defaultCurrency'] == 1 && $issuedInOutPendingArr[1][0] > 0) ? '<td style="font-weight:bold; padding: 8px 5px;">' . getPriceWithCur($issuedInOutPendingArr[1][0], $getDefCurDet['curCode']) . '</td>' : '<td style="font-weight:bold; padding: 8px 5px;"">&nbsp;</td>';

        if ($_GET['otherCurrency'] == 1) {
            // Issue In other currency total pending
            foreach ($otherCurrRowArr as $currencyId => $countOtherCurrRow) {
                $content .= ($otherCurrPendingTotalValueArr[$currencyId] > 0) ?  '<td style=" padding: 8px 5px;">' . showOtherCur($otherCurrPendingTotalValueArr[$currencyId], $currencyId) . '</td>' : '<td style=" padding: 8px 5px;">&nbsp;</td>';
            }
            // End Issue In other currency total pending
        }
        $content .=  '</tr>';
    }
    $content .=  '
                </table>';
    $content .= '</td>';
} //end issue in section

//starts issue out section
if ($_GET['issuedOut'] == 1 && $issueOutTotal > 0) {
    $content .= '<td style="' . $issueoutClass . '">
                <table style="width:100%; margin-right:1%; font-size:12px; border-collapse: collapse;">';
    $content .= '<tr style="font-weight:bold;">
                        <td style="padding: 8px 5px;">&nbsp;</td>
                        <td style="width:50%; padding: 8px 5px;">' . showOtherLangText('Issued Out') . '</td>
                        </tr>';
    $content .= '<tr style="background-color: rgba(122, 137, 255, 0.2); font-weight:bold;">
                        <td style="padding: 8px 5px;">' . showOtherLangText('Total') . '</td>';
    $content .= ($issueOutTotal > 0) ? '<td style="padding: 8px 5px;">' . getPriceWithCur($issueOutTotal, $getDefCurDet['curCode']) . '</td>' : '<td>&nbsp;</td>';

    if ($_GET['receiveSection'] == 1) {
        $content .= '</tr>
                        <tr>
                            <td style="padding: 8px 5px;">' . showOtherLangText('Received') . '</td>';

        $content .= ($issuedInOutPaidArr[2][1]) ? '<td style="font-weight:bold; padding: 8px 5px;">' . getPriceWithCur($issuedInOutPaidArr[2][1], $getDefCurDet['curCode']) . '</td>' : '<td>&nbsp;</td>';
        $content .= '</tr>';
    }


    if ($_GET['issueOutPendingSection'] == 1) {
        $content .= '<tr>
                                    <td style="padding: 8px 5px;">' . showOtherLangText('Pending') . '</td>';
        // $content .= '<td style="font-weight:bold; padding: 8px 5px;">1,279.69 $</td>';

        $content .= ($issuedInOutPendingArr[2][0] > 0) ? '<td style="font-weight:bold; padding: 8px 5px;">' . getPriceWithCur($issuedInOutPendingArr[2][0], $getDefCurDet['curCode']) . '</td>' : '<td>&nbsp;</td>';
        $content .= '</tr>';
    }

    $content .= '</table></td>';
} //end issue out section


$content .= '</tr>
    </table>';



$content .= '<table style="font-size:12px;" width="98%"><tr>';

if ($_GET['variance'] == 1 && ($variancesPosTot || $variancesNevTot)) {
    $content .= '<td style="' . $varianceClass . '">
                    <table style="width:100%; font-size:12px; border-collapse: collapse;">';
    $content .= '<tr style="font-weight:bold; padding: 8px 5px;">
                               <td style="width:50%; padding: 8px 5px;">' . showOtherLangText('Variances') . '</td>
                                </tr>';

    $content .= '<tr style="background-color: rgba(122, 137, 255, 0.2); font-weight:bold;">
                            <td style="color: #198754; padding: 8px 5px;"><img src="data:image/svg+xml;base64,' . base64_encode($svg) . '" alt="icon"  width="18" height="18" />' . getPriceWithCur($variancesPosTot, $getDefCurDet['curCode']) . '</td>
                            <td style="color: #dc3545; padding: 8px 5px;"><img src="data:image/svg+xml;base64,' . base64_encode($svgDown) . '" alt="icon"  width="18" height="18" />' . getPriceWithCur($variancesNevTot, $getDefCurDet['curCode']) . '</td>
                        </tr>
                    </table>
                </td>';
}

if ($_GET['converted'] == 1) {
    //get converted total
    $varOrConverted = true;
    $sqlSet = " SELECT SUM(ordAmt) totConvertedAmt FROM  tbl_orders o  WHERE ordType = '4' 
                                    AND status = '2' AND account_id = '" . $_SESSION['accountId'] . "' " . $condWithoutGroup . " GROUP BY ordType ";

    $resultSet = mysqli_query($con, $sqlSet);
    $resRow = mysqli_fetch_array($resultSet);

    if ($resRow['totConvertedAmt'] > 0) {
        $content .= '<td style="' . $convertedClass . '">
        <table style="width:100%; font-size:12px; border-collapse: collapse;">';

        $content .= '<tr style="font-weight:bold; padding: 8px 5px;">
                            <td style="width:50%; padding: 8px 5px;">' . showOtherLangText('Converted') . '</td>
                                </tr>';

        $content .= '<tr style="background-color: rgba(122, 137, 255, 0.2); font-weight:bold;">
                            <td style="padding: 8px 5px;">' . getPriceWithCur($resRow['totConvertedAmt'], $getDefCurDet['curCode']) . '</td>
                        </tr>
                    </table></td>
                ';
    }
}


$content .= '</tr>
    </table>';
if ($_GET['summaryAccount'] == 1 && mysqli_num_rows($accountResult) > 0) {
    $content .= '<table style="width:100%; font-size:12px; margin-block-start: 24px;">
        <tr style="font-weight:bold;">
            <td style="padding: 8px 5px;">' . showOtherLangText('Accounts') . '</td>
            <td style="padding: 8px 5px;">&nbsp;</td>
            <td style="padding: 8px 5px;">&nbsp;</td>
            <td style="padding: 8px 5px;">&nbsp;</td>
            <td style="padding: 8px 5px;">&nbsp;</td>
            <td style="padding: 8px 5px;">&nbsp;</td>
        </tr>';
    $content .= '<tr style="background-color: rgba(122, 137, 255, 0.2); font-weight:bold;">';

    $num = 0;

    while ($resultRow = mysqli_fetch_array($accountResult)) {


        if (($num % 6) == 0 && $num > 1) $content .= '</tr><tr style="background-color: rgba(122, 137, 255, 0.2); font-weight:bold;">';
        $num++;
        $curCode = $resultRow['curCode'];
        $balanceAmt = round($resultRow['balanceAmt'], 4);
        $content .= '<td style="padding: 8px 5px;">
                                                                
                                                        ' . number_format($balanceAmt) . '
                                                        ' . $curCode . '<small style="display: block; font-weight: 500;font-size: 10px;">' . $resultRow['accountName'] . '</small></td>';
    }

    $content .= '</tr></table>';
}




if ($_GET['itemTaskNo'] == 1 || $_GET['itemDate'] == 1 || $_GET['itemUser'] == 1 || $_GET['itemType'] == 1 || $_GET['itemReferTo'] == 1 || $_GET['itemSupInvNo'] == 1 || $_GET['itemValue'] == 1 || $_GET['itemDefCurrValue'] == 1 || $_GET['itemSecCurrValue'] == 1 || $_GET['itemPaymentNo'] == 1 || $_GET['itemInvNo'] == 1 || $_GET['itemStatus'] == 1 || $_GET['itemAccount'] == 1) {
    $content .= '<table border="0" cellpadding="5" cellspacing="0" width="100%" style="padding-top:10px;width:100%; font-size:12px; margin-block-start: 24px; border-collapse: collapse;"><tbody>
        <tr style="font-weight:bold; background-color: rgba(122, 137, 255, 0.2);">';
    $pmntInvText = 'Pay/Inv No.';

    if ($_GET['itemPaymentNo'] == 1 && $_GET['itemInvNo'] != 1) {
        $pmntInvText = 'Payment No';
    } elseif ($_GET['itemInvNo'] == 1 && $_GET['itemPaymentNo'] != 1) {
        $pmntInvText = 'Invoice No';
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


        11 => '' . showOtherLangText($pmntInvText) . '',

        12 => '' . showOtherLangText('Account') . '',
    ];

    // ==================================================

    if (isset($_SESSION['getVals']['ordType'])) {
        switch ($_SESSION['getVals']['ordType']) {
            case 1: //IssueIn
                unset($headerArr[10]);
                break;

            case 2: //IssueOut
                unset($headerArr[5]);
                // unset( $headerArr[9] );
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
        unset($headerArr[7]);
        unset($headerArr[9]);
        unset($headerArr[10]);
        unset($headerArr[11]);
        unset($headerArr[12]);
    }

    // Logic to show/hide column of the item table from previous page checked value
    if ($_GET['itemTaskNo'] == 0) {
        unset($headerArr[2]);
    }
    if ($_GET['itemDate'] == 0) {
        unset($headerArr[3]);
    }
    if ($_GET['itemUser'] == 0) {
        unset($headerArr[4]);
    }

    if ($_GET['itemSupInvNo'] == 0) {
        unset($headerArr[5]);
    }

    if ($_GET['itemType'] == 0) {
        unset($headerArr[6]);
    }
    if ($_GET['itemReferTo'] == 0) {
        unset($headerArr[7]);
    }

    if ($_GET['itemValue'] == 0) {
        unset($headerArr[8]);
    }
    if ($_GET['itemStatus'] == 0) {
        unset($headerArr[9]);
    }
    if ($_GET['itemPaymentNo'] == 0 && $_GET['itemInvNo'] == 0) {
        unset($headerArr[11]);
    }


    if ($_GET['itemAccount'] == 0) {
        unset($headerArr[12]);
    }


    foreach ($headerArr as $key => $header) {
        $content .=   '<td style="padding: 8px 5px;">' . $header . '</td>';
    }
    $content .=   '</tr>';

    $i = 0;
    while ($orderRow = mysqli_fetch_array($historyQry)) {




        $i++;
        $bgColor = ($i % 2 != 0) ? 'white' : '#F9F9FB';
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
            $paymentStatus = '<span style="color:#038F00;font-weight: bold;">' . showOtherLangText('Received') . '</span>';
        } elseif ($orderRow['ordType'] == 3 || $orderRow['ordType'] == 4) {
            $paymentStatus = ' ';
        } elseif (($orderRow['ordType'] == 2 && $orderRow['receive_inv'] == 0) || $orderRow['ordType'] == 2 && $accessInvoicePermission['type_id'] == 0) {
            $paymentStatus = ' ';
        } elseif ($orderRow['ordType'] == 1 && $accessPaymentPermission['type_id'] == 0) {
            $paymentStatus = ' ';
        } else {
            $paymentStatus = '<span style="color:#FF9244;font-weight: bold;">' . showOtherLangText('Pending') . '</span>';
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

        if ($_GET['itemDefCurrValue'] == 1) {
            if ($orderRow['ordAmt'] > 0) {
                $total = '<span style="font-weight: bold;" class="itemTable-def-curr">' . getPrice($orderRow['ordAmt']) . ' ' . $getDefCurDet['curCode'] . '</span>';
            } else {
                $total = '<span style="font-weight: bold;" class="itemTable-def-curr" style="color:green">' . getPrice($variancesPosTotAmt) . ' ' . $getDefCurDet['curCode'] . '</span>' . '<br>' . '<span style="font-weight: bold;color:#f00" class="itemTable-def-curr">-' . getPrice($variancesNevTotAmt) . ' ' . $getDefCurDet['curCode'] . '</span>';
            }
        }

        $totalOther = '';

        if ($_GET['itemSecCurrValue'] == 1) {
            if ($orderRow['ordCurAmt'] > 0) {
                $totalOther = '<span style="font-weight: bold;" class="itemTable-other-curr">' . showOtherCur($orderRow['ordCurAmt'], $curDet['id']) . '</span>';
            }
        }

        $paymntOrInv = '';
        if ($orderRow['ordType'] == 2 && $_GET['itemInvNo'] == 1) {
            $paymntOrInv = $InvoiceNumber;
        } elseif ($orderRow['paymentStatus'] > 0 && $_GET['itemPaymentNo'] == 1) {
            $paymntOrInv = setPaymentId($paymentId);
        }

        $colsValArr = [
            1 => $i,

            2 => $orderRow['ordNumber'],

            3 => $dateType,

            4 => $user,

            5 => ($orderRow['ordType'] == 1  ? $InvoiceNumber : ''),

            6 => $ordType,

            7 => $suppMemStoreId,

            8 => $total . "<br>" . $totalOther,

            9 =>  $paymentStatus,


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
                    // unset( $colsValArr[9] );
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
                    //  unset( $colsValArr[6] );
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
            unset($colsValArr[7]);
            unset($colsValArr[9]);
            unset($colsValArr[10]);
            unset($colsValArr[11]);
            unset($colsValArr[12]);
        }

        if ($_GET['itemTaskNo'] == 0) {
            unset($colsValArr[2]);
        }


        // Logic to show/hide column of the item table from previous page checked value
        if ($_GET['itemTaskNo'] == 0) {
            unset($colsValArr[2]);
        }
        if ($_GET['itemDate'] == 0) {
            unset($colsValArr[3]);
        }
        if ($_GET['itemUser'] == 0) {
            unset($colsValArr[4]);
        }

        if ($_GET['itemSupInvNo'] == 0) {
            unset($colsValArr[5]);
        }

        if ($_GET['itemType'] == 0) {
            unset($colsValArr[6]);
        }
        if ($_GET['itemReferTo'] == 0) {
            unset($colsValArr[7]);
        }

        if ($_GET['itemValue'] == 0) {
            unset($colsValArr[8]);
        }


        if ($_GET['itemStatus'] == 0) {
            unset($colsValArr[9]);
        }

        if ($_GET['itemPaymentNo'] == 0 && $_GET['itemInvNo'] == 0) {
            unset($colsValArr[11]);
        }

        if ($_GET['itemAccount'] == 0) {
            unset($colsValArr[12]);
        }

        // Open tr tag
        $content .= '<tr style="background-color: '. $bgColor .';">';

        foreach ($colsValArr as $key => $columnVal) {


            $content .= '<td style="padding: 8px 5px;border-bottom: 1px solid #ede0e8;">' . $columnVal . '</td>'; // pint all data row wise in td

        }

        $content .= '</tr>'; // Close tr tag

    }
}



$content .= '</tbody></table></body>
</html>';

//echo $content;