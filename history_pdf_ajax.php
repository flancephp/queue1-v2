<?php
include('inc/dbConfig.php'); //connection details


if ( !isset($_SESSION['adminidusername']))
{
  echo '<script>window.location = "login.php"</script>';
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$checkIfPermissionToNewOrderSec = checkIfPermissionToNewOrderSec($_SESSION['designation_id'],$_SESSION['accountId']);
$checkIfPermissionToNewOrderSec = mysqli_num_rows($checkIfPermissionToNewOrderSec);

$checkIfPermissionToNewReqSec = checkIfPermissionToNewReqSec($_SESSION['designation_id'],$_SESSION['accountId']);
$checkIfPermissionToNewReqSec = mysqli_num_rows($checkIfPermissionToNewReqSec);   

$sql = "SELECT * FROM tbl_user  WHERE id = '".$_SESSION['id']."' AND account_id = '".$_SESSION['accountId']."'  ";
$result = mysqli_query($con, $sql);
$userDetails = mysqli_fetch_array($result);
$historyUserFilterFields = $userDetails['historyUserFilterFields'] ?    explode(',',$userDetails['historyUserFilterFields']) : null;


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


$cond = '';
$cond1 = '';

//From Date || To Date Filter
if( isset($_SESSION['getVals']['fromDate']) && $_SESSION['getVals']['fromDate'] != '' && $_SESSION['getVals']['toDate'] != '')
{
    $cond = " AND DATE(setDateTime) BETWEEN '".date('Y-m-d', strtotime($_SESSION['getVals']['fromDate']) )."' AND '".date('Y-m-d', strtotime($_SESSION['getVals']['toDate']) )."'   ";
    $cond1 = $cond;
    $fromDate = $_SESSION['getVals']['fromDate'];
    $toDate = $_SESSION['getVals']['toDate'];
}
elseif (isset($_SESSION['fromDate']) && $_SESSION['fromDate'] != '' && $_SESSION['toDate'] != '') {
    $cond = " AND DATE(o.setDateTime) BETWEEN '".date('Y-m-d', strtotime($_SESSION['fromDate']) )."' AND '".date('Y-m-d', strtotime($_SESSION['toDate']) )."' ";
    $cond1 = $cond;
    $fromDate = $_SESSION['fromDate'];
    $toDate = $_SESSION['toDate'];
}
else
{
    $_GET['fromDate'] = date('d-m-Y', strtotime('-3 days') );
    $_GET['toDate'] = date('d-m-Y');

    $cond = " AND DATE(o.setDateTime) BETWEEN '".date('Y-m-d', strtotime($_GET['fromDate']) )."' AND '".date('Y-m-d', strtotime($_GET['toDate']) )."' ";
    $cond1 = $cond;
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
}


// Order type filter
if( isset($_SESSION['getVals']['ordType']) && $_SESSION['getVals']['ordType'])
{
    $cond .= " AND o.ordType = '".$_SESSION['getVals']['ordType']."'   ";
}


// supplier || Member || store Filter
if( isset($_SESSION['getVals']['suppMemStoreId']) && $_SESSION['getVals']['suppMemStoreId'])
{
    $suppMemStoreId = $_SESSION['getVals']['suppMemStoreId'];
    $getSupMemStoreId = explode('_', $suppMemStoreId);
    $getTxtById = $getSupMemStoreId[0];
    $getId = $getSupMemStoreId[1];

    if ($getTxtById == 'suppId') {
      $cond .= " AND o.supplierId = ".$getId."  ";
    }

    if ($getTxtById == 'deptUserId') {
    $cond .= " AND o.recMemberId = ".$getId."  ";
    }

    if ($getTxtById == 'storeId') {
    $cond .= " AND o.storeId = ".$getId."  ";
    }
}

// user filter
if( isset($_SESSION['getVals']['userId']) && $_SESSION['getVals']['userId'])
{
    $cond .= " AND o.orderBy = '".$_SESSION['getVals']['userId']."' ";
}

// Payment Status Filter
if ($_SESSION['getVals']['statusType'] == 1 ) {
    
   $cond .= "AND o.paymentStatus = 1 AND o.ordType = 1 ";
}
if ($_SESSION['getVals']['statusType'] == 2 ) {
    
   $cond .= "AND o.paymentStatus = 1 AND o.ordType = 2 ";
}
if ($_SESSION['getVals']['statusType'] == 3 ) {
    
   $cond .= "AND (o.paymentStatus = 2 OR o.paymentStatus = 0) AND o.ordType IN(2,1) ";
}

// for account of payment
if (isset($_SESSION['getVals']['accountNo']) && $_SESSION['getVals']['accountNo'] != '')
{
   if ($_SESSION['getVals']['statusType'] == '') {
      $cond .= " AND o.bankAccountId = '".$_SESSION['getVals']['accountNo']."' AND o.paymentStatus = 1 ";
   }
   if ($_SESSION['getVals']['statusType'] == 1) {
      $cond .= " AND o.bankAccountId = '".$_SESSION['getVals']['accountNo']."' AND o.paymentStatus = 1 AND o.ordType = 1 ";
   }
   if ($_SESSION['getVals']['statusType'] == 2) {
      $cond .= " AND o.bankAccountId = '".$_SESSION['getVals']['accountNo']."' AND o.paymentStatus = 1 AND o.ordType = 2 ";
   }
    
}


// Date sorting
if ($_SESSION['getVals']['dateType'] =='') {
   $cond .= " GROUP BY o.id ORDER BY o.id desc ";
}
if ($_SESSION['getVals']['dateType'] !='' && $_SESSION['getVals']['dateType'] == 1) {
   $cond .= " GROUP BY o.id ORDER BY o.ordDateTime desc ";
}
if ($_SESSION['getVals']['dateType'] !='' && $_SESSION['getVals']['dateType'] == 2) {
   $cond .= " GROUP BY o.id ORDER BY o.setDateTime desc ";
}
if ($_SESSION['getVals']['dateType'] !='' && $_SESSION['getVals']['dateType'] == 3) {
   $cond .= " GROUP BY o.id ORDER BY o.paymentDateTime desc, o.setDateTime desc ";
}


//Access Invoice permission for user
$accessInvoicePermission = get_access_invoice_permission($_SESSION['designation_id'],$_SESSION['accountId']);
//Access payment permission for user
$accessPaymentPermission = get_access_payment_permission($_SESSION['designation_id'],$_SESSION['accountId']);


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
 o.account_id = dp.account_id AND dp.designation_id = ".$_SESSION['designation_id']." AND dp.type = 'order_supplier' AND dp.account_id = ".$_SESSION['accountId']."
 AND dp.designation_section_permission_id=1 AND o.ordType=1

 LEFT JOIN tbl_designation_sub_section_permission dp1 ON(dp1.type_id = o.recMemberId) AND o.account_id = dp1.account_id AND
 dp1.designation_id = ".$_SESSION['designation_id']." AND dp1.type = 'member' AND dp1.account_id = ".$_SESSION['accountId']."
 AND dp1.designation_section_permission_id=2 AND o.ordType=2
 
WHERE o.status = 2  AND o.account_id = '".$_SESSION['accountId']."'

AND (o.ordType=1 AND  dp.id > 0 OR o.ordType=2 AND  dp1.id > 0 OR o.ordType=3 OR o.ordType=4 )
".$cond." ";

$historyQry = mysqli_query($con, $mainSqlQry);

// Get full address and their logo of client
$sql = " SELECT c.name AS countryName, cl.* FROM tbl_client cl
LEFT JOIN tbl_country c ON(cl.country=c.id)
WHERE cl.id = '".$_SESSION['accountId']."' ";
$result = mysqli_query($con, $sql);
$clientDetRow = mysqli_fetch_array($result);
// ==========================================




$content = '<div class="modal-header pb-3">
                    <div class="d-md-flex align-items-center justify-content-between w-100 ">
                        <div class="d-flex align-items-start w-100 gap-3 w-auto mb-md-0 mb-2 modal-head-btn">
                        
                                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#modalfiltertop">
                                    <i class="fa fa-filter"></i>
                                </button>
                            
                                <div class="collapse" id="modalfiltertop">
                                    <div class="d-flex gap-3 modal-head-row">
                                    

                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        '.showOtherLangText('Headers').'<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul id="show-headerHistory" class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="headChk-AllHistory form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Check All').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address"
                                                    id="headChk-BxHistory" 
                                                    class="headCheckboxHistory header-addressHistory form-check-input"
                                                    onclick="showHideByClassHistory(\'address-sectionHistory\')" value="1">
                                                <span class="fs-13">'.showOtherLangText('Address').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="logo" 
                                                id="headChk-BxHistory"
                                                class="headCheckboxHistory header-logoHistory form-check-input"
                                                onclick="showHideByClassHistory(\'logo-sectionHistory\')" value="1">
                                                <span class="fs-13">'.showOtherLangText('Logo').'</span>
                                            </li>
                                        </ul>
                                    </div>                                  

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        '.showOtherLangText('Summary').'<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="smryChk-AllHistory form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Check All').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="issuedIn" 
                                                onclick="showHideByClassHistory(\'issueInSection\')" class="smryCheckboxHistory summary-issue-in form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Issued In').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="defaultCurrency" class="smryCheckboxHistory summary-default-currencyHistory form-check-input" onclick="showHideByClassHistory(\'defaultCurSection\')" value="1">
                                                <span class="fs-13">'.showOtherLangText('Default Currency').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="otherCurrency" onclick="showHideByClassHistory(\'otherCurSection\')" class="smryCheckboxHistory summary-other-currencyHistory form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Other Currency').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="paid" class="smryCheckboxHistory summary-paid form-check-input"
                                                onclick="showHideByClassHistory(\'paidSection\')" value="1">
                                                <span class="fs-13">'.showOtherLangText('Paid') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked"
                                                  onclick="showHideByClassHistory(\'pendingSection\')" name="pending" class="form-check-input smryCheckboxHistory summary-issue-out-pending" value="1">
                                                <span class="fs-13">'.showOtherLangText('Pending') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="issuedOut" 
                                                    onclick="showHideByClassHistory(\'issueOutSection\')" class="smryCheckboxHistory summary-issue-out form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Issued Out').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="received"
                                                    onclick="showHideByClassHistory(\'receiveSection\')" class="smryCheckboxHistory summary-issue-out-receive form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Received').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="issuedOutPending" class="smryCheckboxHistory summary-issue-out-pending form-check-input"
                                                    onclick="showHideByClassHistory(\'issueOutPendingSection\')" value="1">
                                                <span class="fs-13">'.showOtherLangText('Issued Out Pending').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="variances" onclick="showHideByClassHistory(\'varianceRow\')" class="smryCheckboxHistory summary-variance form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Variances').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="accounts" onclick="showHideByClassHistory(\'accountSection\')"  class="smryCheckboxHistory summary-account form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Accounts') .'</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        '.showOtherLangText('Item Table').'<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Check All') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="taskNo" class="form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Task No.') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="date" class="form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Date') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="user" class="form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('User') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="supInvoiceNo" class="form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Sup Invoice No.') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="type" class="form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Type') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="referTo" class="form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Refer to') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="value" class="form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Value') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="defaultCurrencyTotal" class="form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Default Currency Total') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="secondCurrencyTotal" class="form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Second Currency Total') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="status" class="form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Status') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="paymentNo" class="form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Payment no.') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="invoiceNo" class="form-check-input" value="12">
                                                <span class="fs-13">'.showOtherLangText('Invoice no.') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="accounts" class="form-check-input" value="12">
                                                <span class="fs-13">'.showOtherLangText('Accounts') .'</span>
                                            </li>
                                        </ul>
                                    </div>

                                    </div>
                                
                                </div>

                        

                        </div>
                        <a href="historypdf.html" class="btn"><span class="align-middle">'.showOtherLangText('Press') .'</span> <i class="fa-solid fa-download ps-1"></i></a>
                    </div>
                </div> 
                <div class="modal-body px-2 py-3">
                    <div class="row pb-3">
                        <div class="col-6">
                            <div class="address-sectionHistory modal-address ">
                                <h6 class="semibold fs-14">'.$clientDetRow['accountName'].'</h6>
                                <div class="fs-13 ">
                                    <p>'.$clientDetRow['address_one'].'</p>
                                    <p>'.$clientDetRow['address_two'].'</p>
                                    <p>'.$clientDetRow['city'].', '.$clientDetRow['countryName'] .'</p>
                                    <p>'.$clientDetRow['email'].'</p>
                                    <p>'.$clientDetRow['phone'].'</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <div class="logo-sectionHistory modal-logo">';
                        if($clientDetRow['logo'] !='' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath.'/clientLogo/'.$clientDetRow['logo'] ))
                        {  
                        $content .= '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/clientLogo/'.$clientDetRow['logo'].'" style="object-fit: scale-down; height: 50px; width: auto;">';
                        }
                        else
                        {
                        $content .= '<img src="https://queue1.com//uploads/pdf-logo-sample.png" alt="Logo">';
                        }
                         $content .= '</div>
                        </div>
                    </div>

                    <div class="model-title-with-date">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h6 class="semibold">'.showOtherLangText('History Report').'</h6>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-end fs-15"><small>'.showOtherLangText('From') .'</small> '.$fromDate .' <small>'.showOtherLangText('To') .'</small>'.$toDate .'</p>
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

                    WHERE o.status = '2' AND o.account_id = '".$_SESSION['accountId']."' ".$cond." ";

                    $issueInAndOutQry = mysqli_query($con, $sql);

                    $issuedInOutArr = [];
                    $issueInTotal = 0;
                    $issueOutTotal = 0;
                    while( $inAndOutRow = mysqli_fetch_array($issueInAndOutQry) )
                    {   
                    

                          //this is temporary fix need to do with function or main query above
                            if( $inAndOutRow['ordType'] == 1 || $inAndOutRow['ordType'] == 2 )
                            {
                            
                                if($inAndOutRow['ordType'] == 1)
                                {
                                    $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$_SESSION['designation_id']."' AND type = 'order_supplier' AND account_id = '".$_SESSION['accountId']."' and type_id = '".$inAndOutRow['supplierId']."' and designation_section_permission_id=1 ";
                                    
                                }
                                else
                                {
                                    $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$_SESSION['designation_id']."' AND type = 'member' AND account_id = '".$_SESSION['accountId']."' and type_id = '".$inAndOutRow['recMemberId']."' and designation_section_permission_id=2 ";
                                }
                                
                                $checkSubPerQry = mysqli_query($con, $sql);
                                
                                if( mysqli_num_rows($checkSubPerQry) < 1)
                                {
                                    continue;//exclude this order as user don't have its permission
                                }
                            }//end temp query
                        
                            
                        if($inAndOutRow['ordType'] == 1)
                        {
                            $issueInTotal += $inAndOutRow['totalOrdAmt'];
                        }
                        if($inAndOutRow['ordType'] == 2)
                        {
                            $issueOutTotal += $inAndOutRow['totalOrdAmt'];
                        }
                        
                        if ($inAndOutRow['paymentStatus'] == 0 || $inAndOutRow['paymentStatus'] == 2) { // Sum of total pending order amount and regund which status is 2
                            $totalSumAmt = $inAndOutRow['totalOrdAmt'];

                                $issuedInOutPendingArr[$inAndOutRow['ordType']][0] += $totalSumAmt;
                            

                            //$issuedInOutCurCode = $inAndOutRow['currencyId'];
                        }
                        elseif ($inAndOutRow['paymentStatus'] == 1) { // Sum of total paid order amount
                            $totalSumAmt = $inAndOutRow['totalOrdAmt'];

                                $issuedInOutPaidArr[$inAndOutRow['ordType']][$inAndOutRow['paymentStatus']] += $totalSumAmt;
                            
                        }
                    }


                   
                    $otherCurrRowArr = [];
                    $otherCurrTotalValueArr = [];
                    $otherCurrPendingTotalValueArr = [];

                    if( $checkIfPermissionToNewOrderSec > 0)
                    {

                        $sql = " SELECT od.currencyId, c.curCode, o.ordCurAmt AS totalOrdCurAmt, o.paymentStatus FROM tbl_order_details od
                        INNER JOIN tbl_orders o 
                            ON(o.id=od.ordId) AND o.account_id=od.account_Id
                        INNER JOIN tbl_currency c
                            ON(od.currencyId=c.id) AND od.account_id=c.account_Id
                        WHERE o.status = '2' AND o.account_id = '".$_SESSION['accountId']."' ".$cond." ";
                        $result = mysqli_query($con, $sql);
                            while($otherCurrRows = mysqli_fetch_array($result))
                            {
                                if ($otherCurrRows['currencyId'] > 0 && ($otherCurrRows['paymentStatus'] == 0 || $otherCurrRows['paymentStatus'] == 2) ) {

                                    // Total rows of other currency pending amount
                                    $otherCurrRowArr[$otherCurrRows['currencyId']] = $otherCurrRows;

                                    // Sum of total values of pending amount 
                                    $totalSumAmt = $otherCurrRows['totalOrdCurAmt'];
                                    $otherCurrPendingTotalValueArr[$otherCurrRows['currencyId']] += $totalSumAmt;

                                }
                                elseif ($otherCurrRows['currencyId'] > 0 && $otherCurrRows['paymentStatus'] == 1) {

                                    // Total rows of other currency paid amount
                                    $otherCurrRowArr[$otherCurrRows['currencyId']] = $otherCurrRows;

                                    // Sum of total values of paid amount 
                                    $totalSumAmt = $otherCurrRows['totalOrdCurAmt'];
                                    $otherCurrPaidTotalValueArr[$otherCurrRows['currencyId']] += $totalSumAmt;
                                }
                                
                                if ($otherCurrRows['currencyId'] > 0 ) {

                                    // Total rows of other currency paid amount
                                    $otherCurrRowArr[$otherCurrRows['currencyId']] = $otherCurrRows;

                                    // Sum of total values of paid amount 
                                    $totalSumAmt = $otherCurrRows['totalOrdCurAmt'];
                                    $otherCurrTotalValueArr[$otherCurrRows['currencyId']] += $totalSumAmt;
                                }
                            }
                            
                            $count = 0;
                            foreach ($otherCurrRowArr as $otherCurrRow)
                            {
                                $count++;
                            
                            }                    
                   
                    }
                     
                   
                     $issueinClass='';
                     $issueoutClass='';
                     $varianceClass='';
                    
                    if($count >= 1){
                        $issueinClass='col-md-12';
                    ?>

                    <script>
                        if($('.varianceRow').css('display') == 'none')
                        {
                            
                            <?php  $issueoutClass='col-md-12'; ?>
                        }
                        else{
                            <?php  $issueoutClass='col-md-6'; ?>
                        }

                        if($('.issueOutSection ').css('display') == 'none')
                        {
                            <?php  $varianceClass='col-md-12'; ?>
                        }
                        else{
                            <?php  $varianceClass ='col-md-6'; ?>
                        }
                    </script>

                    <?php
                        
                        
                     }                     
                    


                     if($checkIfPermissionToNewOrderSec > 0 && $checkIfPermissionToNewReqSec > 0)
                        {
                            if($count >= 1){
                                $issueinClass='col-md-12';
                                $issueoutClass='col-md-6';
                                $varianceClass='col-md-6';
                            }
                            else
                            {
                                $issueinClass='col-md-3';
                                $issueoutClass='col-md-3';
                                $varianceClass='col-md-6';
                            }
                        }
                        elseif($checkIfPermissionToNewOrderSec > 0 )
                        {
                            if($count >= 1){
                                $issueinClass='col-md-12';
                                $issueoutClass='col-md-3';
                                $varianceClass='col-md-12';
                            }
                            else
                            {
                                $issueinClass='col-md-3';
                                $issueoutClass='col-md-3';
                                $varianceClass='col-md-6';
                            }
                        }
                        elseif($checkIfPermissionToNewReqSec > 0 )
                        {
                            $issueinClass='col-md-3';
                            $issueoutClass='col-md-6';
                            $varianceClass='col-md-6';
                        }
                        else
                        {
                            $issueinClass='col-md-3';
                            $issueoutClass='col-md-3';
                            $varianceClass='col-md-12';
                        }

                   
                        if($_SESSION['getVals']['ordType'] != '')
                        {
                            if($_SESSION['getVals']['ordType'] == 1)
                            {
                                $issueinClass='col-md-12';
                            }
                            elseif($_SESSION['getVals']['ordType'] == 2)
                            {
                                $issueoutClass='col-md-12';
                            }
                            elseif($_SESSION['getVals']['ordType'] == 3)
                            {
                                $varianceClass='col-md-12';
                            }
                        }
                //issue in starts here
                if( $checkIfPermissionToNewOrderSec > 0 && ($_SESSION['getVals']['ordType'] == '' || $_SESSION['getVals']['ordType'] == 1) )
                {
                $content .= '<div class="summery-row">
                        <div class="row"><input type="hidden" name="totalOtherCur" id="totalOtherCur" value="'.count($otherCurrRowArr).'"/>
                            <div class="col-7 issueInSection pe-1">
                                <div class="modal-table fs-12 w-100">
                                    <div class="table-row header-row">
                                        <div class="table-cell">&nbsp;</div>
                                        <div class="table-cell medium">'.showOtherLangText('Issued In').'</div>';
                                        
                                    foreach ($otherCurrRowArr as $otherCurrRow)
                                    {     
                                        $content .= '<div class="table-cell medium">( $ )</div>';
                                    }
                                    $content .=  '</div>
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
                            </div>';
                        }
                            $content .= '<div class="col-3 issueOutSection pe-1 ps-0">
                                <div class="modal-table fs-12 w-100">
                                    <div class="table-row header-row">
                                        <div class="table-cell">&nbsp;</div>
                                        <div class="table-cell medium">'. showOtherLangText('Issued Out') .'</div>
                                    </div>
                                    <div class="table-row thead">
                                        <div class="table-cell">'. showOtherLangText('Total') .'</div>
                                        <div class="table-cell">'. getPriceWithCur($issueOutTotal, $getDefCurDet['curCode']).'</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">'. showOtherLangText('Received').'</div>
                                        <div class="table-cell font-bold">'. ( ($issuedInOutPaidArr[2][1]) ? getPriceWithCur($issuedInOutPaidArr[2][1], $getDefCurDet['curCode']) : '' ).'</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">'. showOtherLangText('Pending').'</div>
                                        <div class="table-cell font-bold">'. ( ($issuedInOutPendingArr[2][0] > 0) ? getPriceWithCur($issuedInOutPendingArr[2][0], $getDefCurDet['curCode']) : '' ).'</div>
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
                </div>';
echo $content;

    