<?php
include('inc/dbConfig.php'); //connection details


if ( !isset($_SESSION['adminidusername']))
{
  echo '<script>window.location = "login.php"</script>';
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


$cond = '';
$cond1 = '';

//From Date || To Date Filter
if( isset($_SESSION['getVals']['fromDate']) && $_SESSION['getVals']['fromDate'] != '' && $_SESSION['getVals']['toDate'] != '')
{
    $cond = " AND DATE(setDateTime) BETWEEN '".date('Y-m-d', strtotime($_SESSION['getVals']['fromDate']) )."' AND '".date('Y-m-d', strtotime($_SESSION['getVals']['toDate']) )."'  AND o.account_id = '".$_SESSION['accountId']."' ";
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
    $cond .= " AND o.ordType = '".$_SESSION['getVals']['ordType']."' AND o.account_id = '".$_SESSION['accountId']."'  ";
}


// supplier || Member || store Filter
if( isset($_SESSION['getVals']['suppMemStoreId']) && $_SESSION['getVals']['suppMemStoreId'])
{
    $suppMemStoreId = $_SESSION['getVals']['suppMemStoreId'];
    $getSupMemStoreId = explode('_', $suppMemStoreId);
    $getTxtById = $getSupMemStoreId[0];
    $getId = $getSupMemStoreId[1];

    if ($getTxtById == 'suppId') {
      $cond .= " AND o.supplierId = ".$getId." AND o.account_id = '".$_SESSION['accountId']."' ";
    }

    if ($getTxtById == 'deptUserId') {
    $cond .= " AND o.recMemberId = ".$getId." AND o.account_id = '".$_SESSION['accountId']."' ";
    }

    if ($getTxtById == 'storeId') {
    $cond .= " AND o.storeId = ".$getId." AND o.account_id = '".$_SESSION['accountId']."' ";
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

WHERE o.status = 2 ".$cond." ";

$historyQry = mysqli_query($con, $mainSqlQry);

// Get full address and their logo of client
$sql = " SELECT c.name AS countryName, cl.* FROM tbl_client cl
LEFT JOIN tbl_country c ON(cl.country=c.id)
WHERE cl.id = '".$_SESSION['accountId']."' ";
$result = mysqli_query($con, $sql);
$clientDetRow = mysqli_fetch_array($result);
// ==========================================
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Queue1.com</title>
    <link rel="shortcut icon" href="favicon_16.ico" />
    <link rel="bookmark" href="favicon_16.ico" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- <link rel="stylesheet" href="dist/css/site.min.css"> -->

    <!-- <link rel="stylesheet" href="css/custom.css"> -->




    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    /*@page {
            size: A4;
            margin: 5cm 1.2cm 1.2cm 8cm;
        }*/

    body {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: #000;
        padding: 15px;
        background: #E5E5E5;
    }

    p {
        margin-bottom: 0;
        font-size: 12px;
    }

    .cstm-Btn {
        display: inline-block;
        text-decoration: none;
        color: #000;
        border-radius: 4px;
        border: 1px solid #e6e9ed;
        font-size: 14px;
        font-weight: 500;
        padding: 4px 6px;
        outline: none;
    }

    .cstm-Btn:hover {
        color: #000;
        border: 1px solid #3BAFDA;
    }

    .cstm-Btn:focus {
        color: #000;
        border: 1px solid #0185B6;
        font-weight: bold;
    }

    .cstm-Btn span,
    .cstm-Btn .fa-solid {
        vertical-align: middle;
    }

    .cstClm {
        display: flex;
        align-items: center;
    }

    .dwnBtn {
        border-radius: 6px;
        background-color: #3BAFDA;
        border: 2px solid #3BAFDA;
        outline: none;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
    }

    .dwnBtn:hover,
    .dwnBtn:active {
        background-color: #3BAFDA !important;
        border: 2px solid #0185b6 !important;
        outline: none;
    }

    .dwnBtn:focus-visible,
    .dwnBtn:focus {
        background-color: #0185b6 !important;
        border: 2px solid #0185b6 !important;
        outline: none;
    }

    .dwnBtn img {
        width: auto;
        height: 14px;
        vertical-align: middle;
        padding-left: 5px;
    }

    .dwnBtn span {
        vertical-align: middle;
    }

    .form-check-inline {
        margin-right: 2rem;
    }

    /* Dropdown CSS Start */
    #show-header,
    #show-smry,
    #show-itm {
        display: none;
        position: absolute;
        border-radius: 7px;
        border: 1px solid #D0D0D0;
        background: #FFF;
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
        padding: 10px;
    }

    #show-header p,
    #show-smry p,
    #show-itm p {
        display: inline-block;
    }

    .checkbox-list p {
        font-weight: 600;
        vertical-align: middle;
        display: inline-block;
    }

    .checkbox-list input {
        vertical-align: middle;
    }

    .checkbox-list {
        cursor: pointer;
    }

    /* Dropdown CSS End */

    .pdfCntr {
        background: #FFF;
        padding: 20px;
    }

    .pdfCntr h6 {
        margin-bottom: 0;
        font-size: 15px;
    }

    .headClm h5,
    .datePdf {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 0;
    }

    .datePdf {
        padding: 0 5px;
    }

    .headRow {
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
        padding: 10px 0;
        margin: 0;
    }

    .table>:not(caption)>*>* {
        padding: 5px 10px !important;
    }

    .table tr {
        height: 46px;
    }

    .logo-section img {
        object-fit: scale-down;
        height: auto;
        width: 80px;
    }
    </style>

</head>

<body>
    <header class="container-fluid p-0">
        <h5><?php echo showOtherLangText('PDF Export') ?></h5>
        <div class="row btnRow mt-3">
            <div class="col-md-9 cstClm">
                <a href="javascript:void(0)"  class="cstm-Btn"><img src="uploads/custom.svg" alt="slider">
                    <span><?php echo showOtherLangText('Custom') ?></span>
                </a>
                <form action="history_pdf_download.php" target="_blank" name="frm" id="frm" method="get">
                    <div class="checkRow ms-4" style="display: none;">
                        <div class="form-check form-check-inline">
                            <div class="checkbox-list">
                                <input type="checkbox" name="header" id="header" value="1">
                                <p class="header-txt"><?php echo showOtherLangText('Headers') ?> <i
                                        class="fa-solid fa-angle-down"></i></p>
                            </div>

                            <div id="show-header">
                                <div class="lblTxt">
                                    <input type="checkbox" class="headChk-All">
                                    <p><?php echo showOtherLangText('Check All') ?></p>
                                </div>
                                <div class="header-main-listbox">
                                    <div class="header-inner-listbox">
                                        <input type="checkbox" name="address" id="headChk-Bx"
                                            class="headCheckbox header-address" value="1">
                                        <p><?php echo showOtherLangText('Address') ?></p>
                                    </div>
                                    <div class="header-inner-listbox">
                                        <input type="checkbox" name="logo" id="headChk-Bx"
                                            class="headCheckbox header-logo" value="1">
                                        <p><?php echo showOtherLangText('Logo') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-check form-check-inline">
                            <div class="checkbox-list">
                                <input type="checkbox" name="" id="summary" value="1">
                                <p class="summary-txt"><?php echo showOtherLangText('Summary') ?> <i
                                        class="fa-solid fa-angle-down"></i></p>
                            </div>

                            <div id="show-smry">
                                <div class="lblTxt">
                                    <input type="checkbox" class="smryChk-All">
                                    <p><?php echo showOtherLangText('Check All') ?></p>
                                </div>
                                <div class="smry-main-listbox">
                                    <div class="smry-inner-listbox">
                                        <input type="checkbox" name="issueInSummary" id="smryChk-Bx"
                                            class="smryCheckbox summary-issue-in" value="1">
                                        <p><?php echo showOtherLangText('Issued In') ?></p>
                                    </div>
                                    <div class="smry-inner-listbox">
                                        <input type="checkbox" name="defaultCurrency" id="smryChk-Bx"
                                            class="smryCheckbox summary-default-currency" value="1">
                                        <p><?php echo showOtherLangText('Default Currency') ?></p>
                                    </div>
                                    <div class="smry-inner-listbox">
                                        <input type="checkbox" name="otherCurrency" id="smryChk-Bx"
                                            class="smryCheckbox summary-other-currency" value="1">
                                        <p><?php echo showOtherLangText('Other Currency') ?></p>
                                    </div>
                                    <div class="smry-inner-listbox">
                                        <input type="checkbox" name="issuedOut" id="smryChk-Bx"
                                            class="smryCheckbox summary-issue-out" value="1">
                                        <p><?php echo showOtherLangText('Issued Out') ?></p>
                                    </div>
                                    <div class="smry-inner-listbox">
                                        <input type="checkbox" name="variance" id="smryChk-Bx"
                                            class="smryCheckbox summary-variance" value="1">
                                        <p><?php echo showOtherLangText('Variances') ?></p>
                                    </div>
                                    <div class="smry-inner-listbox">
                                        <input type="checkbox" name="production" id="smryChk-Bx"
                                            class="smryCheckbox summary-production" value="1">
                                        <p><?php echo showOtherLangText('Production') ?></p>
                                    </div>
                                    <div class="smry-inner-listbox">
                                        <input type="checkbox" name="accountsIssueIn" id="smryChk-Bx"
                                            class="smryCheckbox summary-account" value="1">
                                        <p><?php echo showOtherLangText('Accounts') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-check form-check-inline">
                            <div class="checkbox-list">
                                <input type="checkbox" name="" id="itemTable" value="1">
                                <p class="itemTable-txt"><?php echo showOtherLangText('Item Table') ?> <i
                                        class="fa-solid fa-angle-down"></i></p>
                            </div>

                            <div id="show-itm">
                                <div class="lblTxt">
                                    <input type="checkbox" class="itemChk-All">
                                    <p><?php echo showOtherLangText('Check All') ?></p>
                                </div>
                                <div class="item-main-listbox">
                                    <div class="item-inner-listbox">
                                        <input type="checkbox" name="taskNo" id="itemChk-Bx"
                                            class="itmTblCheckbox item-taskNo" value="1">
                                        <p><?php echo showOtherLangText('Task No.') ?></p>
                                    </div>
                                    <div class="item-inner-listbox">
                                        <input type="checkbox" name="date" id="itemChk-Bx"
                                            class="itmTblCheckbox item-date" value="1">
                                        <p><?php echo showOtherLangText('Date') ?></p>
                                    </div>
                                    <div class="item-inner-listbox">
                                        <input type="checkbox" name="user" id="itemChk-Bx"
                                            class="itmTblCheckbox item-user" value="1">
                                        <p><?php echo showOtherLangText('User') ?></p>
                                    </div>

                                    <div class="item-inner-listbox">
                                        <input type="checkbox" name="supInvNo" id="itemChk-Bx"
                                            class="itmTblCheckbox item-sup-invNo" value="1">
                                        <p><?php echo showOtherLangText('Supplier Invoice No.') ?></p>
                                    </div>

                                    <div class="item-inner-listbox">
                                        <input type="checkbox" name="type" id="itemChk-Bx"
                                            class="itmTblCheckbox item-type" value="1">
                                        <p><?php echo showOtherLangText('Type') ?></p>
                                    </div>
                                    <div class="item-inner-listbox">
                                        <input type="checkbox" name="referTo" id="itemChk-Bx"
                                            class="itmTblCheckbox item-referTo" value="1">
                                        <p><?php echo showOtherLangText('Refer to') ?></p>
                                    </div>

                                    <div class="item-inner-listbox">
                                        <input type="checkbox" name="value" id="itemChk-Bx"
                                            class="itmTblCheckbox item-value" value="1">
                                        <p><?php echo showOtherLangText('Value') ?></p>
                                    </div>
                                    <div class="item-inner-listbox">
                                        <input type="checkbox" name="dCurVal" id="itemChk-Bx"
                                            class="itmTblCheckbox item-def-curr-value" value="1">
                                        <p><?php echo showOtherLangText('Default Currency Total') ?></p>
                                    </div>
                                    <div class="item-inner-listbox">
                                        <input type="checkbox" name="sCurVal" id="itemChk-Bx"
                                            class="itmTblCheckbox item-sec-curr-value" value="1">
                                        <p><?php echo showOtherLangText('Second Currency Total') ?></p>
                                    </div>

                                    <div class="item-inner-listbox">
                                        <input type="checkbox" name="status" id="itemChk-Bx"
                                            class="itmTblCheckbox item-status" value="1">
                                        <p><?php echo showOtherLangText('Status') ?></p>
                                    </div>

                                    <div class="item-inner-listbox">
                                        <input type="checkbox" name="pmntNo" id="itemChk-Bx"
                                            class="itmTblCheckbox item-paymentNo" value="1">
                                        <p><?php echo showOtherLangText('Payment no.') ?></p>
                                    </div>
                                    <div class="item-inner-listbox">
                                        <input type="checkbox" name="invNo" id="itemChk-Bx"
                                            class="itmTblCheckbox item-invNo" value="1">
                                        <p><?php echo showOtherLangText('Invoice no.') ?></p>
                                    </div>

                                    <div class="item-inner-listbox">
                                        <input type="checkbox" name="accounts" id="itemChk-Bx"
                                            class="itmTblCheckbox item-account" value="1">
                                        <p><?php echo showOtherLangText('Accounts') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-3 text-end">
                <button type="button"
                    class="btn btn-primary dwnBtn downloadBtn"><span><?php echo showOtherLangText('Press') ?></span>
                    <img src="./uploads/download.svg" alt="download"></button>
            </div>
        </div>
    </header>

    <section class="pdfFile mt-5">
        <div class="container-fluid pdfCntr">
            <div class="row adrsLogo-row">
                <div class="col-md-6">
                    <div class="address-section">
                        <h6><?php echo $clientDetRow['accountName']; ?></h6>
                        <p><?php echo $clientDetRow['address_one']; ?> <br><?php echo $clientDetRow['address_two']; ?>
                        </p>
                        <p><?php echo $clientDetRow['city'].', '.$clientDetRow['countryName'] ?></p>
                        <p><?php echo $clientDetRow['email']; ?></p>
                        <p><?php echo $clientDetRow['phone']; ?></p>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="logo-section">
                        <?php
                        if($clientDetRow['logo'] !='' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath.'/clientLogo/'.$clientDetRow['logo'] ))
                        {  
                            echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/clientLogo/'.$clientDetRow['logo'].'" width="100" height="100">';
                        }
                        else
                        {
                            echo '<img src="uploads/pdf-logo-sample.png" alt="Logo">';
                        }
                        ?>
                    </div>

                </div>
            </div>

            <div class="row headRow mt-4">
                <div class="col-md-6 headClm">
                    <h5><?php echo showOtherLangText('History Report') ?></h5>
                </div>
                <div class="col-md-6 dtClm text-end">
                    <p><?php echo showOtherLangText('From') ?> <span class="datePdf"><?php echo $fromDate ?></span>
                        <?php echo showOtherLangText('To') ?> <span class="datePdf"><?php echo $toDate ?></span></p>
                </div>
            </div>

            <div class="container-fluid pdfTbl summary-row" style="padding: 30px 0 10px;">
                <div class="row" style="margin: 0;">

                    <?php
                    //get issued in total and issued out total
                    $sql = " SELECT o.ordAmt AS totalOrdAmt, 
                    o.ordType, 
                    o.paymentStatus, 
                    od.currencyId 
                    FROM tbl_order_details od 

                    INNER JOIN tbl_orders o 
                        ON(o.id = od.ordId) AND o.account_id = od.account_id

                    WHERE o.status = '2' ".$cond." ";

                    $issueInAndOutQry = mysqli_query($con, $sql);

                    $issuedInOutArr = [];
					$issueInTotal = 0;
					$issueOutTotal = 0;
                    while( $inAndOutRow = mysqli_fetch_array($issueInAndOutQry) )
                    {   
					
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

                    ?>
                    <div class="col-md-5 isnPdf-Tbl" style="padding-right: 1px; padding-left: 0;">
                        <table class="table table-borderless issue-in-table"
                            style="color: #000; vertical-align: middle; font-size: 12px; line-height: 14px; width: 100%;">
                            <thead style="vertical-align: middle;">
                                <tr>
                                    <th scope="col">&nbsp;</th>
                                    <th scope="col" class="issue-in-def-curr">
                                        <?php echo showOtherLangText('Issued In') ?></th>
                                    <?php
                                   $sql = " SELECT od.currencyId, c.curCode, o.ordCurAmt AS totalOrdCurAmt, o.paymentStatus FROM tbl_order_details od
                                    INNER JOIN tbl_orders o 
                                        ON(o.id=od.ordId) AND o.account_id=od.account_Id
                                    INNER JOIN tbl_currency c
                                        ON(od.currencyId=c.id) AND od.account_id=c.account_Id
                                    WHERE o.status = '2' ".$cond." ";
                                    $result = mysqli_query($con, $sql);
                                    $otherCurrRowArr = [];
                                    $otherCurrTotalValueArr = [];
									$otherCurrPendingTotalValueArr = [];
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
                                    //echo "<pre>";
                                    //print_r($countOtherCurrArr);

                                    foreach ($otherCurrRowArr as $otherCurrRow)
                                    {
                                        echo '<th scope="col" class="issue-in-oth-curr">('.$otherCurrRow['curCode'].')</th>';
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background-color: #D2D2D2 !important;">
                                    <!-- Issue In Total -->
                                    <th style="font-weight: bold;"><?php echo showOtherLangText('Total') ?></th>

                                    <td style="font-weight: bold;" class="issue-in-def-curr">
                                        <?php echo showprice($issueInTotal, $getDefCurDet['curCode']);?>
                                    </td>
                                    <!-- Issue In Total End -->
                                    <?php

                                    // Issue In other currency total
                                    foreach ($otherCurrTotalValueArr as $currencyId => $countOtherCurrRow)
                                    {
                                        echo ($otherCurrTotalValueArr[$currencyId] > 0) ? '<td class="issue-in-oth-curr">'.showOtherCur($otherCurrTotalValueArr[$currencyId], $currencyId).'</td>' : '<td>&nbsp;</td>';
                                    }
                                    // Issue In other currency total end
									
									
                                    // Issue In other currency total end
                                    ?>
                                </tr>
                                <tr>
                                    <!-- Issue In total paid -->
                                    <td><?php echo showOtherLangText('Paid') ?></td>
                                    <td style="font-weight: bold;" class="issue-in-def-curr">
                                        <?php echo ($issuedInOutPaidArr[1][1] > 0) ? showPrice($issuedInOutPaidArr[1][1], $getDefCurDet['curCode']) : ''; ?>
                                    </td>
                                    <!-- End Issue In total paid -->
                                    <?php
                                    // Issue In other currency total paid
                                    foreach ($otherCurrRowArr as $currencyId => $countOtherCurrRow)
                                    {
                                        echo ($otherCurrPaidTotalValueArr[$currencyId] > 0) ? '<td class="issue-in-oth-curr">'.showOtherCur($otherCurrPaidTotalValueArr[$currencyId], $currencyId).'</td>' : '<td>&nbsp;</td>';
                                    }
                                    // End Issue In other currency total paid
                                    ?>
                                </tr>
                                <tr>
                                    <!-- Issue In total pending -->
                                    <td><?php echo showOtherLangText('Pending') ?></td>
                                    <td style="font-weight: bold;" class="issue-in-def-curr">
                                        <?php echo ($issuedInOutPendingArr[1][0] > 0) ? showPrice($issuedInOutPendingArr[1][0], $getDefCurDet['curCode']) : ''; ?>
                                    </td>
                                    <!-- End Issue In total pending -->
                                    <?php
                                    // Issue In other currency total pending
                                    foreach ($otherCurrRowArr as $currencyId => $countOtherCurrRow)
                                    {
                                        echo ($otherCurrPendingTotalValueArr[$currencyId] > 0) ? '<td class="issue-in-oth-curr">'.showOtherCur($otherCurrPendingTotalValueArr[$currencyId], $currencyId).'</td>' : '<td></td>';
                                    }
                                    // End Issue In other currency total pending
                                    ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>



                    <div class="col-md-7 isotPdf-Tbl" style="padding-left: 1px; padding-right: 0;">
                        <table class="table table-borderless"
                            style="color: #000; vertical-align: top; font-size: 12px; line-height: 14px;">
                            <tbody>
                                <tr>
                                    <td style="padding: 0 !important;">
                                        <table class="table table-borderless"
                                            style="color: #000; vertical-align: middle; font-size: 12px; line-height: 14px;">
                                            <thead style="vertical-align: middle;">
                                                <tr>
                                                    <th scope="col" class="issue-out-head-blank">&nbsp;</th>
                                                    <th scope="col" style="border-right: 2px solid #fff;"
                                                        class="issue-out-section">
                                                        <?php echo showOtherLangText('Issued Out') ?></th>
                                                    <th scope="col" style="border-right: 2px solid #fff;"
                                                        class="variance-section">
                                                        <?php echo showOtherLangText('Variances') ?></th>
                                                    <th scope="col" class="production-section">
                                                        <?php echo showOtherLangText('Production') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background-color: #D2D2D2 !important;">
                                                    <!-- Issue Out Total value -->
                                                    <th style="font-weight: bold;" class="issue-out-total">
                                                        <?php echo showOtherLangText('Total') ?>
                                                    </th>
                                                    <td style="font-weight: bold; border-right: 2px solid #fff;"
                                                        class="issue-out-section">
                                                        <?php echo showprice($issueOutTotal, $getDefCurDet['curCode']);?>
                                                    </td>
                                                    <!-- End Issue Out Total value -->
                                                    <!-- Total variances amount -->
                                                    <?php
                                                    $sqlSet = " SELECT od.* FROM tbl_order_details od
                                                    INNER JOIN tbl_orders o
                                                        ON(o.id=od.ordId) AND o.account_id=od.account_Id
                                                    WHERE o.ordType = '3' AND o.status = '2' ".$cond1." ";
                                                    $resultSet = mysqli_query($con, $sqlSet);
                                                    
                                                    $variancesPosTot = 0;
                                                    $variancesPosQtyTot = 0;
                                                    $variancesNevQtyTot = 0;
                                                    $variancesNevTot = 0;
                                                    $varaincesVal = 0;
                                                    while( $resRow = mysqli_fetch_array($resultSet) )
                                                    {
                                                        if ($resRow['qtyReceived'] < $resRow['qty'])
                                                        {
                                                            $varaincesVal = $resRow['qty']-$resRow['qtyReceived'];
                                                            $variancesPosQtyTot += $varaincesVal;
                                                            $variancesPosTot += ($varaincesVal*$resRow['stockPrice']);
                                                        }
                                                        elseif($resRow['qtyReceived'] > $resRow['qty'])
                                                        {
                                                            $varaincesVal = $resRow['qtyReceived']-$resRow['qty'];
                                                            
                                                            $variancesNevQtyTot += $varaincesVal;
                                                            $variancesNevTot += ($varaincesVal*$resRow['stockPrice']);
                                                        }
                                                            
                                                    }
                                                    ?>
                                                    <td style="border-right: 2px solid #fff;" class="variance-section">
                                                        <span><img src="uploads/grArrow.svg" alt="Up">
                                                            <?php echo showPrice($variancesNevTot, $getDefCurDet['curCode']) ?></span>
                                                        <br>
                                                        <span
                                                            style=" padding-top: 8px; display: inline-block; color: #f00; font-weight: 600;"><img
                                                                src="uploads/redArrow.svg" alt="Down"> -
                                                            <?php echo showPrice($variancesPosTot, $getDefCurDet['curCode']) ?></span>
                                                    </td>
                                                    <td class="production-section">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <!-- Issue Out Received Total -->
                                                    <td class="issue-out-received">
                                                        <?php echo showOtherLangText('Received') ?></td>
                                                    <td style="font-weight: bold;" class="issue-out-section">
                                                        <?php echo ($issuedInOutPaidArr[2][1]) ? showPrice($issuedInOutPaidArr[2][1], $getDefCurDet['curCode']) : ''; ?>
                                                    </td>
                                                    <!-- End Issue Out Received Total -->
                                                    <td class="variance-section">&nbsp;</td>
                                                    <td class="production-section">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <!-- Issue Out pending total -->
                                                    <td class="issue-out-pending">
                                                        <?php echo showOtherLangText('Pending') ?></td>
                                                    <td style="font-weight: bold;" class="issue-out-section">
                                                        <?php echo ($issuedInOutPendingArr[2][0] > 0) ? showPrice($issuedInOutPendingArr[2][0], $getDefCurDet['curCode']) : ''; ?>
                                                    </td>
                                                    <!-- End Issue Out pending total -->
                                                    <td class="variance-section">&nbsp;</td>
                                                    <td class="production-section">&nbsp;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <!-- Accounts table start from here -->
                                    <td style="padding: 0 !important;">
                                        <table class="table table-borderless account-table"
                                            style="color: #000; vertical-align: middle; font-size: 12px; line-height: 14px;">
                                            <thead style="vertical-align: middle;">
                                                <tr>
                                                    <th scope="col" style="border-left: 2px solid #fff;">
                                                        <?php echo showOtherLangText('Accounts') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php   
                                            $sqlSet = " SELECT c.curCode, a.* FROM  tbl_accounts a 
                                            INNER JOIN tbl_currency c 
                                                ON( c.id=a.currencyId) AND c.account_Id=a.account_Id
                                            WHERE a.account_id = '".$_SESSION['accountId']."' ";
                                            $result = mysqli_query($con, $sqlSet);

                                            while($resultRow = mysqli_fetch_array($result))
                                            {
                                                $curCode = $resultRow['curCode'];
                                                $balanceAmt = round($resultRow['balanceAmt'], 4);
                                                ?>
                                                <tr style="background-color: #D2D2D2 !important;">
                                                    <td style="font-weight: bold; border-left: 2px solid #fff;">
                                                        <?php echo number_format($balanceAmt); ?>
                                                        <?php echo $curCode; ?>
                                                        <br>
                                                        <small
                                                            style="font-weight: 400;"><?php echo $resultRow['accountName']?></small>
                                                    </td>

                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="container-fluid mainData-Tbl" style="padding: 0;">
                <table class="table table-borderless item-table"
                    style="color: #000; vertical-align: middle; font-size: 12px; line-height: 14px; margin-top: 10px;">
                    <thead style="vertical-align: middle;">
                        <tr style="background-color: #D2D2D2 !important;">
                            <?php
                            $headerArr  = [
                                1 => ''.showOtherLangText('#').'',

                                2 => ''.showOtherLangText('Task No.').'',

                                3 => ''.showOtherLangText('Date').'',

                                4 => ''.showOtherLangText('User').'',

                                5 => ''.showOtherLangText('supplier invoice No.').'',

                                6 => ''.showOtherLangText('Type').'',

                                7 => ''.showOtherLangText('Refer To').'',
                                
                                8 => ''.showOtherLangText('Value').'',

                                9 => ''.showOtherLangText('Status').'',
                                
                                11 => '<span class="head10">'.showOtherLangText('Payment').'</span><span class="pmntInvHash">/</span><span class="head11">'.showOtherLangText('Invoice No.').'</span>',

                                12 => ''.showOtherLangText('Account').'',
                            ];

                            // ==================================================

                            if( isset($_SESSION['getVals']['ordType']) )
                            {
                                switch($_SESSION['getVals']['ordType'])
                                {    
                                    case 1: //IssueIn
                                    unset( $headerArr[10] );
                                    break;

                                    case 2: //IssueOut
                                    unset( $headerArr[7] );
                                    unset( $headerArr[9] );
                                    break;

                                    case 3: //stockTake
                                    unset( $headerArr[7] );
                                    unset( $headerArr[9] );
                                    unset( $headerArr[10] );
                                    unset( $headerArr[11] );
                                    unset( $headerArr[12] );
                                    break;

                                    case 4: //raw convert item
                                    unset( $headerArr[6] );
                                    unset( $headerArr[7] );
                                    unset( $headerArr[9] );
                                    unset( $headerArr[10] );
                                    unset( $headerArr[11] );
                                    unset( $headerArr[12] );
                                    break;
                                }
                            }

                            if( isset($_SESSION['getVals']['suppMemStoreId']) && $getTxtById == 'storeId' )
                            {
                               unset( $headerArr[7] );
                               unset( $headerArr[9] );
                               unset( $headerArr[10] );
                               unset( $headerArr[11] );
                               unset( $headerArr[12] );
                            }

                            if( isset($_SESSION['getVals']['suppMemStoreId']) && $getTxtById == 'deptUserId')
                            {   
                               unset( $headerArr[7] );
                               unset( $headerArr[9] );
                            }

                            if( isset($_SESSION['getVals']['suppMemStoreId']) && $getTxtById == 'suppId' )
                            {
                               unset( $headerArr[7] );
                               unset( $headerArr[9] );
                               unset( $headerArr[10] );
                               unset( $headerArr[11] );
                               unset( $headerArr[12] );
                            }


                            foreach($headerArr as $key=>$header)
                            {
								if($key == 11)
								{
									$key = '';
								}
                                echo '<th class="head'.$key.'">'.$header.'</th>';
                            }
                            // End order table header part

                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=0;
                        while($orderRow = mysqli_fetch_array($historyQry) )
                        {   
                            $i++;
                            // supplier Store Member name
                            if ($orderRow['suppName'] =='' && $orderRow['storeName'] =='')
                            {
                                $suppMemStoreId = $orderRow['deptUserName'];
                            }
                            elseif ($orderRow['deptUserName'] =='' && $orderRow['storeName'] =='') {
                                $suppMemStoreId = $orderRow['suppName'];
                            }
                            else
                            {
                                $suppMemStoreId = $orderRow['storeName'];
                            }

                            $paymentId = ($orderRow['paymentId']) ? $orderRow['paymentId'] : '';
                            $InvoiceNumber = $orderRow['invNo'];

                           //Payment status
                            if ($orderRow['paymentStatus']==1 && $orderRow['ordType']==1)
                            {
                                $paymentStatus = '<span style="color:#16C0FF;font-weight: bold;">'.showOtherLangText('Paid').'</span>';
                            }
                            elseif($orderRow['paymentStatus']==1 && $orderRow['ordType']==2) 
                            {
                                $paymentStatus = '<span style="color:#038F00;font-weight: bold;">'.showOtherLangText('Received').'</span>';  
                            }
                            elseif($orderRow['ordType']==3 || $orderRow['ordType']==4)
                            {
                                $paymentStatus = ' ';  
                            }
                            elseif(($orderRow['ordType'] == 2 && $orderRow['receive_inv'] == 0) || $orderRow['ordType'] == 2 && $accessInvoicePermission['type_id'] == 0)
                            {
                                $paymentStatus = ' ';  
                            }
                            elseif($orderRow['ordType'] == 1 && $accessPaymentPermission['type_id']==0)
                            {
                                $paymentStatus = ' ';  
                            }
                            else
                            {
                                $paymentStatus = '<span style="color:#FF9244;font-weight: bold;">'.showOtherLangText('Pending').'</span>';
                            }


                            // for date time filter
                            if ($_SESSION['getVals']['dateType'] == 1)
                            {
                               $dateType = ($orderRow['ordDateTime'] != '0000-00-00 00:00:00' ? date('d/m/y h:i', strtotime($orderRow['ordDateTime']) ) : '');
                            }
                            elseif($_SESSION['getVals']['dateType'] == 2)
                            {
                               $dateType = ($orderRow['setDateTime'] != '0000-00-00 00:00:00' ? date('d/m/y h:i', strtotime($orderRow['setDateTime']) ) : '');
                            }
                            elseif($_SESSION['getVals']['dateType'] == 3)
                            {
                               $dateType = ( $orderRow['paymentDateTime'] != '0000-00-00 00:00:00' ? date('d/m/y h:i', strtotime($orderRow['paymentDateTime']) ) : date('d/m/y h:i', strtotime($orderRow['setDateTime'])) );
                            }
                            else
                            {
                               $dateType = ($orderRow['setDateTime'] != '0000-00-00 00:00:00' ? date('d/m/y h:i', strtotime($orderRow['setDateTime']) ) : '');
                            }
                            


                            $sqlSet = " SELECT * FROM tbl_user WHERE id = '".$orderRow['orderBy']."'  AND account_id = '".$_SESSION['accountId']."'  ";

                            $resultSet = mysqli_query($con, $sqlSet);

                            $userArr = mysqli_fetch_array($resultSet);

                            $user = $userArr['name']; 

                            $curDet =  getCurrencyDet($orderRow['ordCurId']);

                            if($orderRow['ordType'] == 1)
                            {
                                $ordType = '<span style="font-weight: bold;">'.showOtherLangText('Issued In').'</span>';
                            }
                            elseif($orderRow['ordType'] == 2)
                            {
                                $ordType = '<span style="font-weight: bold;">'.showOtherLangText('Issued Out').'</span>';
                            }
                            elseif($orderRow['ordType'] == 3)
                            {
                                $variances = getOrdItemVariancesAmtNew($orderRow['id']);

                                $variancesPosTotAmt = $variances['variancesPosTot'];
                                $variancesNevTotAmt = $variances['variancesNevTot'];

                                $ordType = '<span style="font-weight: bold;">'.showOtherLangText('Stock Take').'</span>';
                            }
                            else
                            {
                                $variances = getOrdItemVariancesAmt($orderRow['id']);

                                $variancesTotAmt = $variances['variancesTot'];

                                $ordType = '<span style="font-weight: bold;">'.showOtherLangText('Raw Item Convert').'</span>';
                            }

                            $columnVal = $ordType;
							
							$paymntOrInv = '';
							if($orderRow['ordType']==2)
							{
								$paymntOrInv = $InvoiceNumber;
							}
							elseif($orderRow['paymentStatus'] > 0)
							{
								$paymntOrInv = setPaymentId($paymentId);
							}

                            $colsValArr = [
                                1 => $i,

                                2 => $orderRow['ordNumber'],

                                3 => $dateType,

                                4 => $user,

                                5 => ($orderRow['ordType']==1 ) ? $InvoiceNumber : '',

                                6 => $ordType,

                                7 => $suppMemStoreId,

                                8 => ( ($orderRow['ordAmt'] > 0) ? '<span style="font-weight: bold;" class="itemTable-def-curr">'.getPrice($orderRow['ordAmt']) .' '.$getDefCurDet['curCode'].'</span>' : '<span style="color:#000;font-weight: bold;" class="itemTable-def-curr">'.getPrice($variancesPosTotAmt).' '.$getDefCurDet['curCode'].'</span>'.'<br>'.'<span style="font-weight: bold; color:#f00" class="itemTable-def-curr">-'.getPrice($variancesNevTotAmt).' '.$getDefCurDet['curCode'].'</span>' )
                                    
                                    ."<br>".
                                    
                                    ( ($orderRow['ordCurAmt'] > 0) ? '<span style="font-weight: bold;" class="itemTable-other-curr">'.showOtherCur($orderRow['ordCurAmt'], $curDet['id']).'</span>' : '' ),

                                9 => $paymentStatus,

                                11 => $paymntOrInv,
                                
                                12 => ($orderRow['paymentStatus']==1 ? $orderRow['accountName'] : ''),
                            ];


                            if( isset($_SESSION['getVals']['ordType']) )
                            {
                                switch($_SESSION['getVals']['ordType'])
                                {   
                                    case 1: //IssueIn
                                    unset( $colsValArr[10] );
                                    break;

                                    case 2: //IssueOut
                                    unset( $colsValArr[7] );
                                    unset( $colsValArr[9] );
                                    break;

                                    case 3: //stockTake
                                    unset( $colsValArr[7] );
                                    unset( $colsValArr[9] );
                                    unset( $colsValArr[10] );
                                    unset( $colsValArr[11] );
                                    unset( $colsValArr[12] );
                                    break;

                                    case 4: //raw convert item
                                    unset( $colsValArr[6] );
                                    unset( $colsValArr[7] );
                                    unset( $colsValArr[9] );
                                    unset( $colsValArr[10] );
                                    unset( $colsValArr[11] );
                                    unset( $colsValArr[12] );
                                    break;
                                }
                            }

                            if( isset($_SESSION['getVals']['suppMemStoreId']) && $getTxtById == 'storeId')
                            {   
                               unset( $colsValArr[7] );
                               unset( $colsValArr[9] );
                               unset( $colsValArr[10] );
                               unset( $colsValArr[11] );
                               unset( $colsValArr[12] );
                            }

                            if( isset($_SESSION['getVals']['suppMemStoreId']) && $getTxtById == 'deptUserId')
                            {   
                               unset( $colsValArr[7] );
                               unset( $colsValArr[9] );
                            }

                            if( isset($_SESSION['getVals']['suppMemStoreId']) && $getTxtById == 'suppId')
                            {   
                               unset( $colsValArr[7] );
                               unset( $colsValArr[9] );
                               unset( $colsValArr[10] );
                               unset( $colsValArr[11] );
                               unset( $colsValArr[12] );
                            }

                            // Open tr tag
                            echo '<tr style="border-bottom: 2px solid #CBCBCB;">';

                            foreach($colsValArr as $key=>$columnVal)
                            {
								
								if($orderRow['ordType']==2 && $key == 11)
								{
									echo '<td> <span class="body11">'.$columnVal.'</span></td>'; // to handle show hide of issue out which is invoice
								}
								elseif($orderRow['paymentStatus'] > 0 && $key == 11)
								{
									echo '<td> <span class="body10">'.$columnVal.'</span></td>'; // to handle show hide of issue in which is payment
								}
								else
								{
								
									echo '<td class="body'.$key.'" >'.$columnVal.'</td>'; // print all data row wise in td
								}
								
								
							
                                
                            }

                            echo '</tr>'; // Close tr tag

                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script type="text/javascript" src="cdn_js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="cdn_js/jquery.min.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {

        // Show/Hide all three section checkBox on custom button click.
        $('.cstm-Btn').click(function() {

            var allCustomSection = $('.checkRow').css('display');

            if (allCustomSection == "block") {
                $('.checkRow').css('display', 'none');
            } else {
                $('.checkRow').css('display', 'block');
            }

        })
        // End
        // By default checked all three section checkBox
        $('#header').prop('checked', true);
        $('#summary').prop('checked', true);
        $('#itemTable').prop('checked', true);
        // End
        //////////////////////////////////////////////////////////////////////////
        // Header section start
        // Open header sub section list when click on header text or arrow

        $(document).click(function(event) {
            if (!$(event.target).closest('.header-txt, #show-header').length) {
                $('#show-header').css('display', 'none');
            }
        });

        $('.header-txt').click(function() {
            event.stopPropagation();
            $('#show-header').toggle();
            $('#show-smry').css('display', 'none');
            $('#show-itm').css('display', 'none');

            $('.headChk-All, .header-address, .header-logo').prop('checked', true);

            var headerAddress = $('.address-section').css('display');
            var headerLogo = $('.logo-section').css('display');

            if (headerAddress == "none") {
                $('.header-address:checkbox').prop('checked', false);
            }

            if (headerLogo == "none") {
                $('.header-logo:checkbox').prop('checked', false);
            }

            var totalCount = $('.headCheckbox:checkbox').length;
            var totalCheckedCount = $('.headCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.headChk-All:checkbox').prop('checked', true);
            } else {
                $('.headChk-All:checkbox').prop('checked', false);
            }

        })
        // Do this if click on header check all
        $(".headChk-All, #header").click(function() {

            $('.headChk-All:checkbox').not(this).prop('checked', this.checked);
            $('.headCheckbox:checkbox').not(this).prop('checked', this.checked);
            // Hide/Show address and logo section
            var addressSection = $('.address-section').css('display');

            if (addressSection == "block" && $('.headChk-All:checkbox').prop('checked') == false) {
                $('.address-section').css('display', 'none')
            } else {
                $('.address-section').css('display', 'block')
            }

            var logoSection = $('.logo-section').css('display');

            if (logoSection == "block" && $('.headChk-All:checkbox').prop('checked') == false) {
                $('.logo-section').css('display', 'none')
            } else {
                $('.logo-section').css('display', 'block')
            }

            // Change value of each checkbox 1 to 0 and 0 to 1 on click
            if ($('.header-address').prop('checked') == false) {
                $('.header-address').val(0);
            } else {
                $('.header-address').val(1);
            }

            if ($('.header-logo').prop('checked') == false) {
                $('.header-logo').val(0);
            } else {
                $('.header-logo').val(1);
            }

        });
        // Do this if click on address check box
        $('.header-address').click(function() {

            var headAddress = $('.header-address').val();
            if (headAddress == 1) {
                $('.header-address').val(0);
            } else {
                $('.header-address').val(1);
            }

            var totalCount = $('.headCheckbox:checkbox').length;
            var totalCheckedCount = $('.headCheckbox:checked').length;

            if (totalCount == totalCheckedCount) {
                $('.headChk-All:checkbox').prop('checked', true);
            } else {
                $('.headChk-All:checkbox').prop('checked', false);
            }

            // Hide/show address section
            var addressSection = $('.address-section').css('display');

            if (addressSection == "block") {
                $('.address-section').css('display', 'none')
            } else {
                $('.address-section').css('display', 'block')
            }

            // Change value of each checkbox 1 to 0 and 0 to 1 on click
            if ($('.header-address').prop('checked') == false) {
                $('.header-address').val(0);
            } else {
                $('.header-address').val(1);
            }

        })
        // Do this if click on logo check box
        $('.header-logo').click(function() {

            var headLogo = $('.header-logo').val();
            if (headLogo == 1) {
                $('.header-logo').val(0);
            } else {
                $('.header-logo').val(1);
            }

            var totalCount = $('.headCheckbox:checkbox').length;
            var totalCheckedCount = $('.headCheckbox:checked').length;

            if (totalCount == totalCheckedCount) {
                $('.headChk-All:checkbox').prop('checked', true);
            } else {
                $('.headChk-All:checkbox').prop('checked', false);
            }
            // Hide/show logo section
            var logoSection = $('.logo-section').css('display');

            if (logoSection == "block") {
                $('.logo-section').css('display', 'none')
            } else {
                $('.logo-section').css('display', 'block')
            }

            if ($('.header-logo').prop('checked') == false) {
                $('.header-logo').val(0);
            } else {
                $('.header-logo').val(1);
            }

        })

        /////////////////////////////////////////////////////////////////////////
        // Summary section start

        $(document).click(function(event) {
            if (!$(event.target).closest('.summary-txt, #show-smry').length) {
                $('#show-smry').css('display', 'none');
            }
        });

        $('.summary-txt').click(function() {
            event.stopPropagation();
            $('#show-smry').toggle();
            $('#show-header').css('display', 'none');
            $('#show-itm').css('display', 'none');

            var summaryIssueIn = $('.issue-in-table').css('display');
            var summaryDefCur = $('.issue-in-def-curr').css('display');
            var summaryOtherCur = $('.issue-in-oth-curr').css('display');
            var summaryIssueOut = $('.issue-out-section').css('display');
            var summaryVariance = $('.variance-section').css('display');
            var summaryProduction = $('.production-section').css('display');
            var summaryAccount = $('.account-table').css('display');

            $('.smryChk-All:checkbox').prop('checked', true);
            $('.smryCheckbox:checkbox').prop('checked', true);

            if (summaryIssueIn == "none") {
                $('.summary-issue-in:checkbox').prop('checked', false);
            }

            if (summaryDefCur == "none") {
                $('.summary-default-currency:checkbox').prop('checked', false);
            }

            if (summaryOtherCur == "none") {
                $('.summary-other-currency:checkbox').prop('checked', false);
            }

            if (summaryIssueOut == "none") {
                $('.summary-issue-out:checkbox').prop('checked', false);
            }

            if (summaryVariance == "none") {
                $('.summary-variance:checkbox').prop('checked', false);
            }

            if (summaryProduction == "none") {
                $('.summary-production:checkbox').prop('checked', false);
            }

            if (summaryAccount == "none") {
                $('.summary-account:checkbox').prop('checked', false);
            }

            var totalCount = $('.smryCheckbox:checkbox').length;
            var totalCheckedCount = $('.smryCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.smryChk-All:checkbox').prop('checked', true);
            } else {
                $('.smryChk-All:checkbox').prop('checked', false);
            }

        })

        // Do this if click on summary section check all
        $(".smryChk-All, #summary").click(function() {

            $('.smryChk-All:checkbox').not(this).prop('checked', this.checked);
            $('.smryCheckbox:checkbox').not(this).prop('checked', this.checked);

            // Hide/Show all summary section
            // var summaryRow = $('.summary-row').css('display');
            // if (summaryRow == "block" && $('.smryChk-All:checkbox').prop('checked') == false) { $('.summary-row').css('display','none') }else{ $('.summary-row').css('display','block') }

            var issueInTable = $('.issue-in-table').css('display');

            if (issueInTable == "table" && $('.smryChk-All:checkbox').prop('checked') == false) {
                $('.issue-in-table').css('display', 'none')
            } else {
                $('.issue-in-table').css('display', 'table')
            }

            var issueInDefCurr = $('.issue-in-def-curr').css('display');

            if (issueInDefCurr == "table-cell" && $('.smryChk-All:checkbox').prop('checked') == false) {
                $('.issue-in-def-curr').css('display', 'none')
            } else {
                $('.issue-in-def-curr').css('display', 'table-cell')
            }

            var issueInSmry = $('.issue-in-oth-curr').css('display');

            if (issueInSmry == "table-cell" && $('.smryChk-All:checkbox').prop('checked') == false) {
                $('.issue-in-oth-curr').css('display', 'none')
            } else {
                $('.issue-in-oth-curr').css('display', 'table-cell')
            }

            var issueOutHeadBlank = $('.issue-out-head-blank').css('display');
            if (issueOutHeadBlank == "table-cell" && $('.smryChk-All:checkbox').prop('checked') ==
                false) {
                $('.issue-out-head-blank').css('display', 'none')
            } else {
                $('.issue-out-head-blank').css('display', 'table-cell')
            }


            var issueOutTotal = $('.issue-out-total').css('display');

            if (issueOutTotal == "table-cell" && $('.smryChk-All:checkbox').prop('checked') == false) {
                $('.issue-out-total').css('display', 'none')
            } else {
                $('.issue-out-total').css('display', 'table-cell')
            }

            var issueOutReceived = $('.issue-out-received').css('display');

            if (issueOutReceived == "table-cell" && $('.smryChk-All:checkbox').prop('checked') ==
                false) {
                $('.issue-out-received').css('display', 'none')
            } else {
                $('.issue-out-received').css('display', 'table-cell')
            }

            var issueOutPending = $('.issue-out-pending').css('display');

            if (issueOutPending == "table-cell" && $('.smryChk-All:checkbox').prop('checked') ==
                false) {
                $('.issue-out-pending').css('display', 'none')
            } else {
                $('.issue-out-pending').css('display', 'table-cell')
            }


            var issueOutSection = $('.issue-out-section').css('display');

            if (issueOutSection == "table-cell" && $('.smryChk-All:checkbox').prop('checked') ==
                false) {
                $('.issue-out-section').css('display', 'none')
            } else {
                $('.issue-out-section').css('display', 'table-cell')
            }

            var varianceSection = $('.variance-section').css('display');

            if (varianceSection == "table-cell" && $('.smryChk-All:checkbox').prop('checked') ==
                false) {
                $('.variance-section').css('display', 'none')
            } else {
                $('.variance-section').css('display', 'table-cell')
            }

            var productionSection = $('.production-section').css('display');

            if (productionSection == "table-cell" && $('.smryChk-All:checkbox').prop('checked') ==
                false) {
                $('.production-section').css('display', 'none')
            } else {
                $('.production-section').css('display', 'table-cell')
            }

            var accountTable = $('.account-table').css('display');

            if (accountTable == "table" && $('.smryChk-All:checkbox').prop('checked') == false) {
                $('.account-table').css('display', 'none')
            } else {
                $('.account-table').css('display', 'table')
            }

            // Change value of each checkbox 1 to 0 and 0 to 1 on click
            if ($('.summary-issue-in').prop('checked') == false) {
                $('.summary-issue-in').val(0);
            } else {
                $('.summary-issue-in').val(1);
            }

            if ($('.summary-default-currency').prop('checked') == false) {
                $('.summary-default-currency').val(0);
            } else {
                $('.summary-default-currency').val(1);
            }

            if ($('.summary-other-currency').prop('checked') == false) {
                $('.summary-other-currency').val(0);
            } else {
                $('.summary-other-currency').val(1);
            }

            if ($('.summary-issue-out').prop('checked') == false) {
                $('.summary-issue-out').val(0);
            } else {
                $('.summary-issue-out').val(1);
            }

            if ($('.summary-variance').prop('checked') == false) {
                $('.summary-variance').val(0);
            } else {
                $('.summary-variance').val(1);
            }

            if ($('.summary-production').prop('checked') == false) {
                $('.summary-production').val(0);
            } else {
                $('.summary-production').val(1);
            }

            if ($('.summary-account').prop('checked') == false) {
                $('.summary-account').val(0);
            } else {
                $('.summary-account').val(1);
            }

        });

        // Show/Hide Summary issue In section
        $('.summary-issue-in').click(function() {

            var totalCount = $('.smryCheckbox:checkbox').length;
            var totalCheckedCount = $('.smryCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.smryChk-All:checkbox').prop('checked', true);
            } else {
                $('.smryChk-All:checkbox').prop('checked', false);
            }

            var issueInTable = $('.issue-in-table').css('display');

            if (issueInTable == "table" && $('.smryChk-All:checkbox').prop('checked') == false) {
                $('.issue-in-table').css('display', 'none')
            } else {
                $('.issue-in-table').css('display', 'table')
            }

            // Change value of each checkbox 1 to 0 and 0 to 1 on click
            if ($('.summary-issue-in').prop('checked') == false) {
                $('.summary-issue-in').val(0);
            } else {
                $('.summary-issue-in').val(1);
            }

        })

        // Show/Hide Summary default currency
        $('.summary-default-currency').click(function() {

            var totalCount = $('.smryCheckbox:checkbox').length;
            var totalCheckedCount = $('.smryCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.smryChk-All:checkbox').prop('checked', true);
            } else {
                $('.smryChk-All:checkbox').prop('checked', false);
            }

            var issueInDefCurr = $('.issue-in-def-curr').css('display');

            if (issueInDefCurr == "table-cell" && $('.smryChk-All:checkbox').prop('checked') == false) {
                $('.issue-in-def-curr').css('display', 'none')
            } else {
                $('.issue-in-def-curr').css('display', 'table-cell')
            }


            if ($('.summary-default-currency').prop('checked') == false) {
                $('.summary-default-currency').val(0);
            } else {
                $('.summary-default-currency').val(1);
            }

        })

        // Show/Hide Summary other currency
        $('.summary-other-currency').click(function() {

            var totalCount = $('.smryCheckbox:checkbox').length;
            var totalCheckedCount = $('.smryCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.smryChk-All:checkbox').prop('checked', true);
            } else {
                $('.smryChk-All:checkbox').prop('checked', false);
            }


            var issueInSmry = $('.issue-in-oth-curr').css('display');

            if (issueInSmry == "table-cell" && $('.smryChk-All:checkbox').prop('checked') == false) {
                $('.issue-in-oth-curr').css('display', 'none')
            } else {
                $('.issue-in-oth-curr').css('display', 'table-cell')
            }


            if ($('.summary-other-currency').prop('checked') == false) {
                $('.summary-other-currency').val(0);
            } else {
                $('.summary-other-currency').val(1);
            }

        })

        // Show/Hide issueOut section of summary section
        $('.summary-issue-out').click(function() {

            var totalCount = $('.smryCheckbox:checkbox').length;
            var totalCheckedCount = $('.smryCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.smryChk-All:checkbox').prop('checked', true);
            } else {
                $('.smryChk-All:checkbox').prop('checked', false);
            }


            var issueOutSection = $('.issue-out-section').css('display');
            var issueOutHeadBlank = $('.issue-out-head-blank').css('display');
            var issueOutTotal = $('.issue-out-total').css('display');
            var issueOutReceived = $('.issue-out-received').css('display');
            var issueOutPending = $('.issue-out-pending').css('display');

            if (issueOutSection == "table-cell" && $('.smryChk-All:checkbox').prop('checked') ==
                false) {
                $('.issue-out-section').css('display', 'none')
            } else {
                $('.issue-out-section').css('display', 'table-cell')
            }

            var varianceSection = $('.variance-section').css('display');
            var productionSection = $('.production-section').css('display');
            var accountTable = $('.account-table').css('display');

            if (varianceSection == 'table-cell' || productionSection == 'table-cell' || accountTable ==
                'table') {
                if (issueOutHeadBlank == "table-cell" && $('.smryChk-All:checkbox').prop('checked') ==
                    false) {
                    $('.issue-out-head-blank').css('display', 'none')
                } else {
                    $('.issue-out-head-blank').css('display', 'table-cell')
                }
            }

            if (issueOutTotal == "table-cell" && $('.smryChk-All:checkbox').prop('checked') == false) {
                $('.issue-out-total').css('display', 'none')
            } else {
                $('.issue-out-total').css('display', 'table-cell')
            }

            if (issueOutReceived == "table-cell" && $('.smryChk-All:checkbox').prop('checked') ==
                false) {
                $('.issue-out-received').css('display', 'none')
            } else {
                $('.issue-out-received').css('display', 'table-cell')
            }

            if (issueOutPending == "table-cell" && $('.smryChk-All:checkbox').prop('checked') ==
                false) {
                $('.issue-out-pending').css('display', 'none')
            } else {
                $('.issue-out-pending').css('display', 'table-cell')
            }


            if ($('.summary-issue-out').prop('checked') == false) {
                $('.summary-issue-out').val(0);
            } else {
                $('.summary-issue-out').val(1);
            }

        })

        // Show/Hide variances section of summary section
        $('.summary-variance').click(function() {

            var totalCount = $('.smryCheckbox:checkbox').length;
            var totalCheckedCount = $('.smryCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.smryChk-All:checkbox').prop('checked', true);
            } else {
                $('.smryChk-All:checkbox').prop('checked', false);
            }

            // var issueOutSection = $('.issue-out-section').css('display');
            var summaryIssueOut = $('.summary-issue-out').val();
            if (summaryIssueOut == 0) {

                $('.issue-out-head-blank').css('display', 'none');
            }


            var varianceSection = $('.variance-section').css('display');

            if (varianceSection == "table-cell" && $('.smryChk-All:checkbox').prop('checked') ==
                false) {
                $('.variance-section').css('display', 'none')
            } else {
                $('.variance-section').css('display', 'table-cell')
            }

            if ($('.summary-variance').prop('checked') == false) {
                $('.summary-variance').val(0);
            } else {
                $('.summary-variance').val(1);
            }

        })

        // Show/Hide production section of summary section
        $('.summary-production').click(function() {

            var totalCount = $('.smryCheckbox:checkbox').length;
            var totalCheckedCount = $('.smryCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.smryChk-All:checkbox').prop('checked', true);
            } else {
                $('.smryChk-All:checkbox').prop('checked', false);
            }

            var issueOutSection = $('.issue-out-section').css('display');
            var issueOutHeadBlank = $('.issue-out-head-blank').css('display');

            var summaryIssueOut = $('.summary-issue-out').val();
            if (summaryIssueOut == 0) {

                $('.issue-out-head-blank').css('display', 'none');
            }

            var productionSection = $('.production-section').css('display');

            if (productionSection == "table-cell" && $('.smryChk-All:checkbox').prop('checked') ==
                false) {
                $('.production-section').css('display', 'none')
            } else {
                $('.production-section').css('display', 'table-cell')
            }

            if ($('.summary-production').prop('checked') == false) {
                $('.summary-production').val(0);
            } else {
                $('.summary-production').val(1);
            }

        })

        // Show/Hide accounts section of summary section
        $('.summary-account').click(function() {

            var totalCount = $('.smryCheckbox:checkbox').length;
            var totalCheckedCount = $('.smryCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.smryChk-All:checkbox').prop('checked', true);
            } else {
                $('.smryChk-All:checkbox').prop('checked', false);
            }

            var accountTable = $('.account-table').css('display');

            if (accountTable == "table" && $('.smryChk-All:checkbox').prop('checked') == false) {
                $('.account-table').css('display', 'none')
            } else {
                $('.account-table').css('display', 'table')
            }

            if ($('.summary-account').prop('checked') == false) {
                $('.summary-account').val(0);
            } else {
                $('.summary-account').val(1);
            }

        })

        ////////////////////////////////////////////////////////////////////////////
        // Item table section start

        $(document).click(function(event) {
            if (!$(event.target).closest('.itemTable-txt, #show-itm').length) {
                $('#show-itm').css('display', 'none');
            }
        });

        $('.itemTable-txt').click(function() {
            event.stopPropagation();
            $('#show-itm').toggle();
            $('#show-smry').css('display', 'none');
            $('#show-header').css('display', 'none');

            var taskNo = $('.head2, .body2').css('display');
            var itemDate = $('.head3, .body3').css('display');
            var itemUser = $('.head4, .body4').css('display');
            var itemSupInvNo = $('.head5, .body5').css('display');
            var itemType = $('.head6, .body6').css('display');
            var itemReferTo = $('.head7, .body7').css('display');

            var itemValue = $('.head8, .body8').css('display');
            var itemDefCurrValue = $('.itemTable-def-curr').css('display');
            var itemOtherCurrValue = $('.itemTable-other-curr').css('display');

            var itemStatus = $('.head9, .body9').css('display');

            var itemPaymentNo = $('.head10, .body10').css('display');
            var itemInvNo = $('.head11, .body11').css('display');

            var itemAccount = $('.head12, .body12').css('display');

            $('.itemChk-All:checkbox').prop('checked', true);
            $('.itmTblCheckbox:checkbox').prop('checked', true);

            if (taskNo == "none") {
                $('.item-taskNo:checkbox').prop('checked', false);
            }
            if (itemDate == "none") {
                $('.item-date:checkbox').prop('checked', false);
            }
            if (itemUser == "none") {
                $('.item-user:checkbox').prop('checked', false);
            }
            if (itemType == "none") {
                $('.item-type:checkbox').prop('checked', false);
            }
            if (itemReferTo == "none") {
                $('.item-referTo:checkbox').prop('checked', false);
            }
            if (itemSupInvNo == "none") {
                $('.item-sup-invNo:checkbox').prop('checked', false);
            }
            if (itemValue == "none") {
                $('.item-value:checkbox').prop('checked', false);
            }
            if (itemDefCurrValue == "none") {
                $('.item-def-curr-value:checkbox').prop('checked', false);
            }
            if (itemOtherCurrValue == "none") {
                $('.item-sec-curr-value:checkbox').prop('checked', false);
            }
            if (itemPaymentNo == "none") {
                $('.item-paymentNo:checkbox').prop('checked', false);
            }
            if (itemInvNo == "none") {
                $('.item-invNo:checkbox').prop('checked', false);
            }
            if (itemStatus == "none") {
                $('.item-status:checkbox').prop('checked', false);
            }
            if (itemAccount == "none") {
                $('.item-account:checkbox').prop('checked', false);
            }


            var totalCount = $('.itmTblCheckbox:checkbox').length;
            var totalCheckedCount = $('.itmTblCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.itemChk-All:checkbox').prop('checked', true);
            } else {
                $('.itemChk-All:checkbox').prop('checked', false);
            }

        })

        // Do this if click on item section check all
        $(".itemChk-All, #itemTable").click(function() {

            $('.itemChk-All:checkbox').not(this).prop('checked', this.checked);
            $('.itmTblCheckbox:checkbox').not(this).prop('checked', this.checked);

            // Hide/Show all item table section
            var itemTable = $('.item-table').css('display');
            if (itemTable == "table" && $('.itemChk-All:checkbox').prop('checked') == false) {
                $('.item-table').css('display', 'none');
            } else {
                $('.item-table').css('display', 'table');
            }

            var taskNo = $('.head2, .body2').css('display');
            if (taskNo == "table-cell" && $('.itemChk-All:checkbox').prop('checked') == false) {
                $('.head2, .body2').css('display', 'none');
            } else {
                $('.head2, .body2').css('display', 'table-cell');
            }


            var itemDate = $('.head3, .body3').css('display');
            if (itemDate == "table-cell" && $('.itemChk-All:checkbox').prop('checked') == false) {
                $('.head3, .body3').css('display', 'none');
            } else {
                $('.head3, .body3').css('display', 'table-cell');
            }


            var itemUser = $('.head4, .body4').css('display');
            if (itemUser == "table-cell" && $('.itemChk-All:checkbox').prop('checked') == false) {
                $('.head4, .body4').css('display', 'none');
            } else {
                $('.head4, .body4').css('display', 'table-cell');
            }



            var itemSupInvNo = $('.head5, .body5').css('display');
            if (itemSupInvNo == "table-cell" && $('.itemChk-All:checkbox').prop('checked') == false) {
                $('.head5, .body5').css('display', 'none');
            } else {
                $('.head5, .body5').css('display', 'table-cell');
            }

            var itemType = $('.head6, .body6').css('display');
            if (itemType == "table-cell" && $('.itemChk-All:checkbox').prop('checked') == false) {
                $('.head6, .body6').css('display', 'none');
            } else {
                $('.head6, .body6').css('display', 'table-cell');
            }


            var itemReferTo = $('.head7, .body7').css('display');
            if (itemReferTo == "table-cell" && $('.itemChk-All:checkbox').prop('checked') == false) {
                $('.head7, .body7').css('display', 'none');
            } else {
                $('.head7, .body7').css('display', 'table-cell');
            }





            var itemValue = $('.head8, .body8').css('display');
            if (itemValue == "table-cell" && $('.itemChk-All:checkbox').prop('checked') == false) {
                $('.head8, .body8').css('display', 'none');
            } else {
                $('.head8, .body8').css('display', 'table-cell');
            }


            var itemDefCurrValue = $('.itemTable-def-curr').css('display');
            if (itemDefCurrValue == "inline" && $('.itemChk-All:checkbox').prop('checked') == false) {
                $('.itemTable-def-curr').css('display', 'none');
            } else {
                $('.itemTable-def-curr').css('display', 'inline');
            }


            var itemOtherCurrValue = $('.itemTable-other-curr').css('display');
            if (itemOtherCurrValue == "inline" && $('.itemChk-All:checkbox').prop('checked') == false) {
                $('.itemTable-other-curr').css('display', 'none');
            } else {
                $('.itemTable-other-curr').css('display', 'inline');
            }


            var itemStatus = $('.head9, .body9').css('display');
            if (itemStatus == "table-cell" && $('.itemChk-All:checkbox').prop('checked') == false) {
                $('.head9, .body9').css('display', 'none');
            } else {
                $('.head9, .body9').css('display', 'table-cell');
            }

            var itemPmntNo = $('.head10, .body10').css('display');
            if (itemPmntNo == "table-cell" && $('.itemChk-All:checkbox').prop('checked') == false) {
                $('.head10, .body10').css('display', 'none');
                $('.pmntInvHash').css('display', 'none');

            } else {
                $('.head10, .body10').css('display', 'table-cell');
                //$('.pmntInvHash').css('display', 'table-cell');
            }

            var itemInvNo = $('.head11, .body11').css('display');
            if (itemInvNo == "table-cell" && $('.itemChk-All:checkbox').prop('checked') == false) {
                $('.head11, .body11').css('display', 'none');
                $('.pmntInvHash').css('display', 'none');
            } else {
                $('.head11, .body11').css('display', 'table-cell');
                //$('.pmntInvHash').css('display', 'table-cell');
            }



            var itemAccount = $('.head12, .body12').css('display');
            if (itemAccount == "table-cell" && $('.itemChk-All:checkbox').prop('checked') == false) {
                $('.head12, .body12').css('display', 'none');
            } else {
                $('.head12, .body12').css('display', 'table-cell');
            }

            // Change value of each checkbox 1 to 0 and 0 to 1 on click
            if ($('.item-taskNo').prop('checked') == false) {
                $('.item-taskNo').val(0);
            } else {
                $('.item-taskNo').val(1);
            }

            if ($('.item-date').prop('checked') == false) {
                $('.item-date').val(0);
            } else {
                $('.item-date').val(1);
            }

            if ($('.item-user').prop('checked') == false) {
                $('.item-user').val(0);
            } else {
                $('.item-user').val(1);
            }

            if ($('.item-type').prop('checked') == false) {
                $('.item-type').val(0);
            } else {
                $('.item-type').val(1);
            }

            if ($('.item-referTo').prop('checked') == false) {
                $('.item-referTo').val(0);
            } else {
                $('.item-referTo').val(1);
            }

            if ($('.item-sup-invNo').prop('checked') == false) {
                $('.item-sup-invNo').val(0);
            } else {
                $('.item-sup-invNo').val(1);
            }

            if ($('.item-value').prop('checked') == false) {
                $('.item-value').val(0);
            } else {
                $('.item-value').val(1);
            }

            if ($('.item-def-curr-value').prop('checked') == false) {
                $('.item-def-curr-value').val(0);
            } else {
                $('.item-def-curr-value').val(1);
            }

            if ($('.item-sec-curr-value').prop('checked') == false) {
                $('.item-sec-curr-value').val(0);
            } else {
                $('.item-sec-curr-value').val(1);
            }

            if ($('.item-paymentNo').prop('checked') == false) {
                $('.item-paymentNo').val(0);
            } else {
                $('.item-paymentNo').val(1);
            }

            if ($('.item-invNo').prop('checked') == false) {
                $('.item-invNo').val(0);
            } else {
                $('.item-invNo').val(1);
            }

            if ($('.item-status').prop('checked') == false) {
                $('.item-status').val(0);
            } else {
                $('.item-status').val(1);
            }

            if ($('.item-account').prop('checked') == false) {
                $('.item-account').val(0);
            } else {
                $('.item-account').val(1);
            }

        });

        // Show/Hide task no in item table
        $(".item-taskNo").click(function() {

            var itemTable = $('.item-table').css('display');
            if (itemTable == "none") {
                $('.item-table').css('display', 'table');
            }

            var totalCount = $('.itmTblCheckbox:checkbox').length;
            var totalCheckedCount = $('.itmTblCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.itemChk-All:checkbox').prop('checked', true);
            } else {
                $('.itemChk-All:checkbox').prop('checked', false);
            }

            var taskNo = $('.head2, .body2').css('display');

            if (taskNo == "none") {
                $('.head2, .body2').css('display', 'table-cell');
            } else {
                $('.head2, .body2').css('display', 'none');
            }

            // Change value of each checkbox 1 to 0 and 0 to 1 on click
            if ($('.item-taskNo').prop('checked') == false) {
                $('.item-taskNo').val(0);
            } else {
                $('.item-taskNo').val(1);
            }

        });

        // Show/Hide Date in item table
        $(".item-date").click(function() {

            var itemTable = $('.item-table').css('display');
            if (itemTable == "none") {
                $('.item-table').css('display', 'table');
            }

            var totalCount = $('.itmTblCheckbox:checkbox').length;
            var totalCheckedCount = $('.itmTblCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.itemChk-All:checkbox').prop('checked', true);
            } else {
                $('.itemChk-All:checkbox').prop('checked', false);
            }

            var itemDate = $('.head3, .body3').css('display');

            if (itemDate == "none") {
                $('.head3, .body3').css('display', 'table-cell');
            } else {
                $('.head3, .body3').css('display', 'none');
            }

            if ($('.item-date').prop('checked') == false) {
                $('.item-date').val(0);
            } else {
                $('.item-date').val(1);
            }

        });

        // Show/Hide user in item table
        $(".item-user").click(function() {

            var itemTable = $('.item-table').css('display');
            if (itemTable == "none") {
                $('.item-table').css('display', 'table');
            }

            var totalCount = $('.itmTblCheckbox:checkbox').length;
            var totalCheckedCount = $('.itmTblCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.itemChk-All:checkbox').prop('checked', true);
            } else {
                $('.itemChk-All:checkbox').prop('checked', false);
            }

            var itemUser = $('.head4, .body4').css('display');

            if (itemUser == "none") {
                $('.head4, .body4').css('display', 'table-cell');
            } else {
                $('.head4, .body4').css('display', 'none');
            }

            if ($('.item-user').prop('checked') == false) {
                $('.item-user').val(0);
            } else {
                $('.item-user').val(1);
            }


        });



        // Show/Hide supplier Invoice no in item table
        $(".item-sup-invNo").click(function() {

            var itemTable = $('.item-table').css('display');
            if (itemTable == "none") {
                $('.item-table').css('display', 'table');
            }

            var totalCount = $('.itmTblCheckbox:checkbox').length;
            var totalCheckedCount = $('.itmTblCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.itemChk-All:checkbox').prop('checked', true);
            } else {
                $('.itemChk-All:checkbox').prop('checked', false);
            }

            var itemSupInvNo = $('.head5, .body5').css('display');

            if (itemSupInvNo == "none") {
                $('.head5, .body5').css('display', 'table-cell');
            } else {
                $('.head5, .body5').css('display', 'none');
            }

            if ($('.item-sup-invNo').prop('checked') == false) {
                $('.item-sup-invNo').val(0);
            } else {
                $('.item-sup-invNo').val(1);
            }


        });

        // Show/Hide type in item table
        $(".item-type").click(function() {

            var itemTable = $('.item-table').css('display');
            if (itemTable == "none") {
                $('.item-table').css('display', 'table');
            }

            var totalCount = $('.itmTblCheckbox:checkbox').length;
            var totalCheckedCount = $('.itmTblCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.itemChk-All:checkbox').prop('checked', true);
            } else {
                $('.itemChk-All:checkbox').prop('checked', false);
            }

            var itemType = $('.head6, .body6').css('display');

            if (itemType == "none") {
                $('.head6, .body6').css('display', 'table-cell');
            } else {
                $('.head6, .body6').css('display', 'none');
            }

            if ($('.item-type').prop('checked') == false) {
                $('.item-type').val(0);
            } else {
                $('.item-type').val(1);
            }


        });

        // Show/Hide Refer To in item table
        $(".item-referTo").click(function() {

            var itemTable = $('.item-table').css('display');
            if (itemTable == "none") {
                $('.item-table').css('display', 'table');
            }

            var totalCount = $('.itmTblCheckbox:checkbox').length;
            var totalCheckedCount = $('.itmTblCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.itemChk-All:checkbox').prop('checked', true);
            } else {
                $('.itemChk-All:checkbox').prop('checked', false);
            }

            var itemReferTo = $('.head7, .body7').css('display');

            if (itemReferTo == "none") {
                $('.head7, .body7').css('display', 'table-cell');
            } else {
                $('.head7, .body7').css('display', 'none');
            }

            if ($('.item-referTo').prop('checked') == false) {
                $('.item-referTo').val(0);
            } else {
                $('.item-referTo').val(1);
            }


        });


        // Show/Hide Value in item table
        $(".item-value").click(function() {

            var itemTable = $('.item-table').css('display');
            if (itemTable == "none") {
                $('.item-table').css('display', 'table');
            }

            var totalCount = $('.itmTblCheckbox:checkbox').length;
            var totalCheckedCount = $('.itmTblCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.itemChk-All:checkbox').prop('checked', true);
            } else {
                $('.itemChk-All:checkbox').prop('checked', false);
            }

            var itemValue = $('.head8, .body8').css('display');

            if (itemValue == "none") {
                $('.head8, .body8').css('display', 'table-cell');
                $('.itemTable-def-curr').css('display', 'table-cell');
                $('.itemTable-other-curr').css('display', 'table-cell');
                $('.item-def-curr-value:checkbox').prop('checked', true);
                $('.item-sec-curr-value:checkbox').prop('checked', true);
            } else {
                $('.head8, .body8').css('display', 'none');
                $('.itemTable-def-curr').css('display', 'none');
                $('.itemTable-other-curr').css('display', 'none');
                $('.item-def-curr-value:checkbox').prop('checked', false);
                $('.item-sec-curr-value:checkbox').prop('checked', false);
            }

            if ($('.item-value').prop('checked') == false) {
                $('.item-value').val(0);
            } else {
                $('.item-value').val(1);
            }


        });

        // Show/Hide item default currency in item table
        $(".item-def-curr-value").click(function() {

            var itemTable = $('.item-table').css('display');
            if (itemTable == "none") {
                $('.item-table').css('display', 'table');
            }

            var totalCount = $('.itmTblCheckbox:checkbox').length;
            var totalCheckedCount = $('.itmTblCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.itemChk-All:checkbox').prop('checked', true);
            } else {
                $('.itemChk-All:checkbox').prop('checked', false);
            }

            var itemDefCurrValue = $('.itemTable-def-curr').css('display');

            // var itemSecCurrValue = $('.itemTable-def-curr').css('display');

            // if (itemDefCurrValue == "none" && itemSecCurrValue == "none") { $('.head8, .body8').css('display','table-cell'); }else{ $('.head8, .body8').css('display','table-cell'); }

            if (itemDefCurrValue == "none") {
                $('.itemTable-def-curr').css('display', 'table-cell');
            } else {
                $('.itemTable-def-curr').css('display', 'none');
            }

            if ($('.item-def-curr-value').prop('checked') == false) {
                $('.item-def-curr-value').val(0);
            } else {
                $('.item-def-curr-value').val(1);
            }


        });

        // Show/Hide item second currency in item table
        $(".item-sec-curr-value").click(function() {

            var itemTable = $('.item-table').css('display');
            if (itemTable == "none") {
                $('.item-table').css('display', 'table');
            }

            var totalCount = $('.itmTblCheckbox:checkbox').length;
            var totalCheckedCount = $('.itmTblCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.itemChk-All:checkbox').prop('checked', true);
            } else {
                $('.itemChk-All:checkbox').prop('checked', false);
            }

            var itemSecCurrValue = $('.itemTable-other-curr').css('display');

            // var itemDefCurrValue = $('.itemTable-def-curr').css('display');

            // if (itemDefCurrValue == "none" && itemSecCurrValue == "none") { $('.head8, .body8').css('display','none'); }else{ $('.head8, .body8').css('display','table-cell'); }

            if (itemSecCurrValue == "none") {
                $('.itemTable-other-curr').css('display', 'table-cell');
            } else {
                $('.itemTable-other-curr').css('display', 'none');
            }

            if ($('.item-sec-curr-value').prop('checked') == false) {
                $('.item-sec-curr-value').val(0);
            } else {
                $('.item-sec-curr-value').val(1);
            }


        });


        // Show/Hide status in item table
        $(".item-status").click(function() {

            var itemTable = $('.item-table').css('display');
            if (itemTable == "none") {
                $('.item-table').css('display', 'table');
            }

            var totalCount = $('.itmTblCheckbox:checkbox').length;
            var totalCheckedCount = $('.itmTblCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.itemChk-All:checkbox').prop('checked', true);
            } else {
                $('.itemChk-All:checkbox').prop('checked', false);
            }

            var itemStatus = $('.head9, .body9').css('display');

            if (itemStatus == "none") {
                $('.head9, .body9').css('display', 'table-cell');
            } else {
                $('.head9, .body9').css('display', 'none');
            }

            if ($('.item-status').prop('checked') == false) {
                $('.item-status').val(0);
            } else {
                $('.item-status').val(1);
            }


        });

        // Show/Hide payment no in item table
        $(".item-paymentNo").click(function() {

            var itemTable = $('.item-table').css('display');
            if (itemTable == "none") {
                $('.item-table').css('display', 'table');
            }

            var totalCount = $('.itmTblCheckbox:checkbox').length;
            var totalCheckedCount = $('.itmTblCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.itemChk-All:checkbox').prop('checked', true);
            } else {
                $('.itemChk-All:checkbox').prop('checked', false);
            }

            var itemPaymentNo = $('.head10, .body10').css('display');

            if (itemPaymentNo == "none") {
                $('.head10, .body10').css('display', 'table-cell');


                var itemInvNo = $('.head11, .body11').css('display');

                if (itemInvNo != "none") {
                    $('.pmntInvHash').css('display', 'table-cell');
                }

            } else {
                $('.head10, .body10').css('display', 'none');
                $('.pmntInvHash').css('display', 'none');
            }

            if ($('.item-paymentNo').prop('checked') == false) {
                $('.item-paymentNo').val(0);
            } else {
                $('.item-paymentNo').val(1);
            }


        });

        // Show/Hide invoice no in item table
        $(".item-invNo").click(function() {

            var itemTable = $('.item-table').css('display');
            if (itemTable == "none") {
                $('.item-table').css('display', 'table');
            }

            var totalCount = $('.itmTblCheckbox:checkbox').length;
            var totalCheckedCount = $('.itmTblCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.itemChk-All:checkbox').prop('checked', true);
            } else {
                $('.itemChk-All:checkbox').prop('checked', false);
            }

            var itemInvNo = $('.head11, .body11').css('display');

            if (itemInvNo == "none") {
                $('.head11, .body11').css('display', 'table-cell');

                var itemPaymentNo = $('.head10, .body10').css('display');

                if (itemPaymentNo != "none") {
                    $('.pmntInvHash').css('display', 'table-cell');
                }


            } else {
                $('.head11, .body11').css('display', 'none');
                $('.pmntInvHash').css('display', 'none');
            }



            if ($('.item-invNo').prop('checked') == false) {
                $('.item-invNo').val(0);
            } else {
                $('.item-invNo').val(1);
            }


        });



        // Show/Hide Account in item table
        $(".item-account").click(function() {

            var itemTable = $('.item-table').css('display');
            if (itemTable == "none") {
                $('.item-table').css('display', 'table');
            }

            var totalCount = $('.itmTblCheckbox:checkbox').length;
            var totalCheckedCount = $('.itmTblCheckbox:checked').length;
            if (totalCount == totalCheckedCount) {
                $('.itemChk-All:checkbox').prop('checked', true);
            } else {
                $('.itemChk-All:checkbox').prop('checked', false);
            }

            var itemAccount = $('.head12, .body12').css('display');

            if (itemAccount == "none") {
                $('.head12, .body12').css('display', 'table-cell');
            } else {
                $('.head12, .body12').css('display', 'none');
            }

            if ($('.item-account').prop('checked') == false) {
                $('.item-account').val(0);
            } else {
                $('.item-account').val(1);
            }
        });
    })

    // When click on download button
    $('.downloadBtn').click(function() {

        var headerAddress = $('.header-address').val();
        var headerLogo = $('.header-logo').val();

        var summaryIssueIn = $('.summary-issue-in').val();
        var summaryDefCur = $('.summary-default-currency').val();
        var summaryOtherCur = $('.summary-other-currency').val();
        var summaryIssueOut = $('.summary-issue-out').val();
        var summaryVariance = $('.summary-variance').val();
        var summaryProduction = $('.summary-production').val();
        var summaryAccount = $('.summary-account').val();

        var itemTaskNo = $('.item-taskNo').val();
        var itemDate = $('.item-date').val();
        var itemUser = $('.item-user').val();
        var itemSupInvNo = $('.item-sup-invNo').val();
        var itemType = $('.item-type').val();
        var itemReferTo = $('.item-referTo').val();
        var itemValue = $('.item-value').val();
        var itemDefCurrValue = $('.item-def-curr-value').val();
        var itemSecCurrValue = $('.item-sec-curr-value').val();
        var itemPaymentNo = $('.item-paymentNo').val();
        var itemInvNo = $('.item-invNo').val();
        var itemStatus = $('.item-status').val();
        var itemAccount = $('.item-account').val();

        //document.frm.submit();
        // window.location.href='history_pdf_download.php?headerAddress='+headerAddress;

        var url = 'history_pdf_download.php?headerAddress=' + headerAddress + '&headerLogo=' +
            headerLogo + '&summaryIssueIn=' + summaryIssueIn + '&summaryDefCur=' + summaryDefCur +
            '&summaryOtherCur=' + summaryOtherCur + '&summaryIssueOut=' + summaryIssueOut +
            '&summaryVariance=' + summaryVariance + '&summaryProduction=' + summaryProduction +
            '&summaryAccount=' + summaryAccount + '&itemTaskNo=' + itemTaskNo + '&itemDate=' + itemDate +
            '&itemUser=' + itemUser + '&itemSupInvNo=' + itemSupInvNo + '&itemType=' + itemType +
            '&itemReferTo=' + itemReferTo + '&itemValue=' + itemValue + '&itemDefCurrValue=' +
            itemDefCurrValue + '&itemSecCurrValue=' + itemSecCurrValue + '&itemPaymentNo=' + itemPaymentNo +
            '&itemInvNo=' + itemInvNo + '&itemStatus=' + itemStatus + '&itemAccount=' + itemAccount;

            window.open(url,'_blank');
    })
    </script>

</body>

</html>