<?php

include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

// Get full address and their logo of client
$sql = " SELECT c.name AS countryName, cl.* FROM tbl_client cl
LEFT JOIN tbl_country c ON(cl.country=c.id)
WHERE cl.id = '" . $_SESSION['accountId'] . "' ";
$result = mysqli_query($con, $sql);
$clientDetRow = mysqli_fetch_array($result);





$sql = "SELECT * FROM tbl_orders  WHERE id = '" . $_POST['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";

$qry = mysqli_query($con, $sql);
$ordDet = mysqli_fetch_array($qry);

$sql = "SELECT od.*, tp.itemName, tp.barCode, tp.imgName, IF(u.name!='',u.name,tp.unitP) purchaseUnit FROM tbl_order_details od 
INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
LEFT JOIN tbl_units u ON(u.id = tp.unitP) AND u.account_id = tp.account_id
WHERE od.ordId = '" . $_POST['orderId'] . "' AND od.account_id = '" . $_SESSION['accountId'] . "'  ";

$proresultSet = mysqli_query($con, $sql);

$sql = "SELECT cif.itemName, cif.unit, tod.* FROM tbl_order_details tod 
INNER JOIN tbl_custom_items_fee cif ON(tod.customChargeId = cif.id) AND tod.account_id = cif.account_id
WHERE tod.ordId = '" . $_POST['orderId'] . "' AND tod.account_id = '" . $_SESSION['accountId'] . "'  and tod.customChargeType=1 ORDER BY cif.itemName ";
$otherChrgQry = mysqli_query($con, $sql);

$sqlSet = " SELECT GROUP_CONCAT(DISTINCT(s.name)) suppliers FROM tbl_order_details d 
INNER JOIN tbl_orders o
ON(o.id = d.ordId) AND o.account_id = d.account_id
INNER JOIN tbl_suppliers s
ON(o.supplierId = s.id) AND o.account_id = s.account_id
WHERE d.ordId IN( " . $_POST['orderId'] . " ) AND s.account_id = '" . $_SESSION['accountId'] . "'  GROUP BY d.ordId ";
$resultSet = mysqli_query($con, $sqlSet);
$supRow = mysqli_fetch_array($resultSet);
$suppliers = $supRow['suppliers'];

$sqlSet = " SELECT * FROM tbl_user WHERE id = '" . $ordDet['orderBy'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
$resultSet = mysqli_query($con, $sqlSet);
$orderBy = mysqli_fetch_array($resultSet);



$sqlSet = " SELECT j.*, u.name, d.designation_name FROM tbl_order_journey j 
INNER JOIN tbl_user u ON(u.id = j.userBy)
INNER JOIN tbl_designation d ON(u.designation_id = d.id) AND u.account_id = d.account_id

 WHERE j.orderId = '" . $_POST['orderId'] . "'  AND j.account_id = '" . $_SESSION['accountId'] . "'  ";
$orderJourneyQry = mysqli_query($con, $sqlSet);


if ($ordDet['ordCurId'] > 0) {
    $res = mysqli_query($con, " SELECT * from tbl_currency WHERE id='" . $ordDet['ordCurId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ");
    $curDet = mysqli_fetch_array($res);
}


$content  = '<form action="ordershare_pdf_download.php" target="_blank" method="get">
<input type="hidden" name="getLangType" value="' . $getLangType . '" id="history_pdf_page"/> 
<input type="hidden" name="history_pdf_page" value="0" id="history_pdf_page"/> 
<input type="hidden" name="orderId" value="' . $_POST['orderId'] . '" />
<input type="hidden" name="isSupDet" value="' . $_POST['isSupDet'] . '" />
<input type="hidden" name="ordCurId" id="ordCurId" value="' . $ordDet['ordCurId'] . '" />';
$content .= '<div class="modal-header position-relative">
                      <div class="mb-modal-close-icon">  <button type="button" class="btn-close m-0 d-lg-none" data-bs-dismiss="modal" aria-label="Close" fdprocessedid="mvv0xh"></button></div>
                        <div class="d-md-flex align-items-center justify-content-between w-100 ">
                            <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#modalfiltertop">
                                <i class="fa fa-filter"></i>
                            </button>
                             
                            <div class="d-inline-flex align-items-center gap-2">
                                <button type="submit" class="btn btn-primary dwnBtn"><span class="align-middle">' . showOtherLangText('Press') . '</span> <i class="fa-solid fa-download ps-1"></i></button> 
                            </div>
                        </div>
                        <div class="collapse" id="modalfiltertop">
                            <div class="d-flex flex-wrap gap-1 gap-md-3 modal-head-row">
                            

                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">' . showOtherLangText('Headers') . '
                                <i class="fa-solid fa-angle-down px-1"></i>
                                </button>
                                <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                    <li>
                                        <input type="checkbox" checked="checked" name="checkAll" class="headChk-All form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Check All') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" name="address" class="form-check-input header-address headCheckbox" value="1" onClick="hideCheckbox(\'adrsClm\')">
                                        <span class="fs-13">' . showOtherLangText('Address') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" name="orderDetails" class="form-check-input headCheckbox"
                                            onClick="hideCheckbox(\'orderDet\')" value="1">
                                        <span class="fs-13">' . showOtherLangText('Order details') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" name="logo" class="form-check-input headCheckbox" 
                                            onClick="hideCheckbox(\'logoClm\')"
                                            value="1">
                                        <span class="fs-13">' . showOtherLangText('Logo') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" name="currentDate" class="form-check-input headCheckbox"
                                            onClick="hideCheckbox(\'curDate\')"
                                            value="1">
                                        <span class="fs-13">' . showOtherLangText('Current Date') . '</span>
                                    </li>
                                </ul>
                            </div>                                  

                            <div class=" dropdown">
                                <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">' . showOtherLangText('Summary') . '
                                <i class="fa-solid fa-angle-down px-1"></i>
                                </button>
                                <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                    <li>
                                        <input type="checkbox" checked="checked" name="checkAllSummary" class="smryChk-All form-check-input"
                                        onClick="hideCheckbox(\'smryDiv\')" value="1">
                                        <span class="fs-13">' . showOtherLangText('Check All') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox"  name="defaultCurrencyAmount" onClick="showHideByClassSummary(\'smryDef_Val\');" class="smryCheckbox summary-default-currency form-check-input" 
                                            ' . (($_POST['isSupDet'] == 1) ? '' : 'checked="checked"') . '
                                            value="1">
                                        <span class="fs-13">' . showOtherLangText('Default Currency Amount') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" ' . (($_POST['isSupDet'] == 1) ? '' : 'checked="checked"') . ' ' . (($ordDet['ordCurId'] == 0) ? 'disabled' : '') . ' onClick="showHideByClassSummary(\'smryOtr_Val\');" name="secondCurrency" class="form-check-input smryCheckbox summary-second-currency" value="1">
                                        <span class="fs-13">' . showOtherLangText('Second Currency Amount') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" value="1" onclick="showHideByClassSummary(\'smrySuplr\')"  name="supplierInvoice" class="smryCheckbox summary-payment form-check-input" value="1" ' . (($_POST['isSupDet'] == 1 || $_POST['page'] == 'order') ? '' : 'checked="checked"') . '>
                                        <span class="fs-13">' . showOtherLangText('Supplier Invoice') . ' #</span>
                                    </li>
                                    <li>
                                        <input type="checkbox"  name="payment" class="smryCheckbox summary-payment form-check-input" onClick="showHideByClassSummary(\'smryPayment\')" ' . (($_POST['isSupDet'] == 1 || $_POST['page'] == 'order') ? '' : 'checked="checked"') . ' value="1">
                                        <span class="fs-13">' . showOtherLangText('Payment') . '#</span>
                                    </li>
                                </ul>
                            </div>

                            <div class=" dropdown">
                                <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">' . showOtherLangText('Item Table') . '
                                <i class="fa-solid fa-angle-down px-1"></i>
                                </button>
                                <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                    <li>
                                        <input type="checkbox" checked="checked" name="checkAll" 
                                            onClick="hideCheckbox(\'itemDiv\')" class="itemChk-All form-check-input" value="1" >
                                        <span class="fs-13">' . showOtherLangText('Check All') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox"  name="photo" onClick="showHideByClassItems(\'photo\');" class="itmTblCheckbox item-name form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Photo') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked"
                                        onClick="showHideByClassItems(\'itmProd\');" name="itemName" class="itmTblCheckbox item-name form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Item Name') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" ' . (($_POST['isSupDet'] == 1) ? '' : 'checked="checked"') . '
                                        onClick="showHideByClassItems(\'itmCode\');" name="barcode" class="itmTblCheckbox item-barcode form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Barcode') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" ' . (($_POST['isSupDet'] == 1) ? '' : 'checked="checked"') . ' onClick="showHideByClassItems(\'itmPrc\');" class="itmTblCheckbox item-price form-check-input" name="price" class="form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Price') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" ' . (($_POST['isSupDet'] == 1) ? '' : 'checked="checked"') . ' ' . (($ordDet['ordCurId'] == 0) ? 'disabled' : '') . ' onClick="showHideByClassItems(\'otherCurPrice\');" class="form-check-input itmTblCheckbox" name="secondCurrencyPrice" class="form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Second Currency Price') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" onClick="showHideByClassItems(\'itmPrcunit\');" name="unit" class="itmTblCheckbox item-unit form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Unit') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" onClick="showHideByClassItems(\'itmPurqty\');" name="qty" class="itmTblCheckbox item-purchase-qty form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Qty') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" onClick="showHideByClassItems(\'itmRecqty\');"  ' . (($_POST['isSupDet'] == 1) ? '' : 'checked="checked"') . ' name="receivedQty" class="itmTblCheckbox item-receive-qty form-check-input" value="1">
                                        <span class=" item-receive-qty fs-13">' . showOtherLangText('Received Qty') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" ' . (($_POST['isSupDet'] == 1) ? '' : 'checked="checked"') . ' onClick="showHideByClassItems(\'itmTotal\');" name="total" class="itmTblCheckbox item-total item-total form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Total') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" ' . (($_POST['isSupDet'] == 1) ? '' : 'checked="checked"') . ' ' . (($ordDet['ordCurId'] == 0) ? 'disabled' : '') . ' onClick="showHideByClassItems(\'otherCurTotal\');" name="secondCurrencyTotal" class="itmTblCheckbox form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Second Currency Total') . '</span>
                                    </li>
                                    <li>
                                        <input type="checkbox" checked="checked" name="note" onClick="showHideByClassItems(\'itmNote\');" class="itmTblCheckbox item-note form-check-input" value="1">
                                        <span class="fs-13">' . showOtherLangText('Note') . '</span>
                                    </li>
                                </ul>
                            </div>

                            <div class=" dropdown">
                                <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">' . showOtherLangText('Task Record') . '
                                <i class="fa-solid fa-angle-down px-1"></i>
                                </button>
                                <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                    <li>
                                        <input type="checkbox" name="taskRecord" class="form-check-input" id="taskRecord" ' . (($_POST['isSupDet'] == 1) ? '' : 'checked="checked"') . ' onClick="hideCheckbox(\'taskDiv\')" value="1">
                                        <span class="fs-13">' . showOtherLangText('Task Record') . '</span>
                                    </li>
                                </ul>
                            </div>

                            </div>
                        
                        </div>
                    </div></form>';
$content  .= '<div class="modal-body px-2 py-3 tble-design-fix-p">
                        <div class="row pb-3">
                            <div class="col-md-4 col-7 order-md-1">
                                <div id="adrsClm" class="headerTxt modal-address ">
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
                            <div class="col-md-4 col-5 order-md-3 text-end">
                                <div id="logoClm" class="headerTxt modal-logo">';
if (!isset($_GET['pdfDownload']) || $clientDetRow["logo"] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/clientLogo/" . $clientDetRow["logo"])) {
    $content .= '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/clientLogo/' . $clientDetRow['logo'] . '" style="object-fit: scale-down; height: 50px; width: auto;">';
} else {
    $content .= '<img src="' . $siteUrl . 'uploads/pdf-logo-sample.png" alt="Logo" style="object-fit: scale-down; height: 50px; width: auto;">';
}
$content .= '</div>
                                <div id="curDate"  class="headerTxt modal-date pt-1">
                                    <p>' . date('d/m/Y') . '</p>
                                </div>
                            </div>
                            <div  class="col-md-4 order-md-2">
                                <h4 id="orderDet" class="headerTxt text-center semibold">' . showOtherLangText('Order Details') . '</h4>
                            </div>
                        </div>';

$content .=   '
                 <div class="show-smry-cls remove__parent__padding px-2">
    <div class="modal-table fs-12 w-100">
        <div class="table-row header-row">
            <div class="table-cell medium">' . showOtherLangText('Task No.') . '</div>
            <div class="table-cell medium">' . showOtherLangText('Supplier') . '</div>
            <div style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '" class="amountSections smryHead sumBreakupAmtText table-cell left__align__text medium order-dtl-total-head">
                <div class="modal-table">
                    <div class="table-cell py-0"></div>
                    <div class="table-cell py-0 text-end">' . showOtherLangText('Total') . '</div>
                    <div class="table-cell py-0"></div>
                </div> 
            </div>
        </div>
        <div class="table-row thead">
            <div class="table-cell">' . $ordDet['ordNumber'] . '</div>
            <div class="table-cell">' . $suppliers . '</div>
            <div class="table-cell left__align__text">
                <div class="table-row">
                    <div class="table-cell py-0 "></div>
                    <div class="table-cell py-0 pe-0"><span class="smryDef_Val smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . getNumFormtPrice($ordDet['ordAmt'], $getDefCurDet['curCode']) . '</span></div>
                    <div class="table-cell py-0 " style="padding-right:0px;"><span class="smryOtr_Val amountSections  smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . showOtherCur($ordDet['ordCurAmt'], $ordDet['ordCurId']) . '</span></div>
                </div>
            </div>
        </div>
        <div class="SummaryItems table-row">
            <div class="table-cell">
                <div class="sub-table w-100 table-amount-val-same-alignment">
                    <div class="table-row">
                        <div class="table-cell"><span class="smrySuplr smryHead" style="' . (($_POST['isSupDet'] == 1 || $_POST['page'] == 'order') ? 'display:none;' : '') . '"> # ' . showOtherLangText('Supplier Invoice') . '</span></div>
                        <div class="table-cell"><span  class="smrySuplr smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . $ordDet['invNo'] . '</span></div>
                    </div>';
if ($ordDet['paymentId'] > 0) {
    $content .=   '<div class="table-row">
                        <div class="table-cell"><span class="smryPayment smryHead" style="' . (($_POST['isSupDet'] == 1 || $_POST['page'] == 'order') ? 'display:none;' : '') . '"># ' . showOtherLangText('Payment') . '</span></div>
                        <div class="table-cell"><span class="smryPayment smryHead" style="' . (($_POST['isSupDet'] == 1 || $_POST['page'] == 'order') ? 'display:none;' : '') . '">' . $ordDet['paymentId'] . '</span></div>
                       </div>';
}
$content .=  '</div>
            </div>
           <div class="table-cell">
                <div class="sub-table w-100">
                    <div class="table-row">
                        <div class="table-cell"><span class="smryDef_Val amountSections smryHead">&nbsp;</span></div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell"><span class="smryDef_Val amountSections smryHead">&nbsp;</span></div>
                    </div>
                    <div class="table-row">
                        <div class="table-cell"><span class="smryDef_Val amountSections smryHead">&nbsp;</span></div>
                    </div>
                </div>
            </div>';
$sqlSet = "SELECT SUM(totalAmt) AS sum1, SUM(curAmt) AS totalAmtOther FROM tbl_order_details WHERE ordId='" . $_REQUEST['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  AND (customChargeType='1' OR customChargeType='0')";
$resultSet = mysqli_query($con, $sqlSet);
$chargeRow = mysqli_fetch_array($resultSet);
$chargePrice = $chargeRow['sum1'];
$chargePriceOther = $chargeRow['totalAmtOther'];


//to find order level charge
$ordCount = "SELECT * from tbl_order_details where ordId='" . $_REQUEST['orderId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' AND customChargeType='2' ";
$ordCountResult = mysqli_query($con, $ordCount);
$ordCountRow = mysqli_num_rows($ordCountResult);
$showGrandTotal = false;

if ($ordCountRow > 0) {

    $showGrandTotal = true;
    $content .= '<div class="table-cell">
                <div class="sub-table w-100">
                    <div class="table-row">
                        <div class="table-cell" style="width:33.33%"><span class="sumBreakupAmtText amountSections smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . showOtherLangText('Sub Total') . '</span></div>
                        <div class="table-cell" style="width:33.33%"><span class="smryDef_Val amountSections smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . getPriceWithCur($chargePrice, $getDefCurDet['curCode']) . '
                                </span></div>';
    if ($ordDet['ordCurId'] > 0) {
        $content .= '<div class="table-cell" style="width:33.33%"><span class="smryOtr_Val amountSections  smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . showOtherCur($chargePriceOther, $ordDet['ordCurId']) . '
                                </span></div>';
    }
    $content .= '</div>';
}
$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
                     INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                     WHERE od.ordId = '" . $_REQUEST['orderId'] . "' AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

$ordQry = mysqli_query($con, $sql);


$fixedCharges = 0;
$fixedChargesOther = 0;
while ($row = mysqli_fetch_array($ordQry)) //show here order level charges
{

    $fixedCharges += $row['price'];
    $fixedChargesOther += $row['curAmt'];

    $content .= '<div class="table-row">
                        <div class="table-cell" style="width:33.33%"><span class="sumBreakupAmtText amountSections smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . $row['feeName'] . '</span></div>
                        <div class="table-cell" style="width:33.33%"><span class="smryDef_Val amountSections smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . getPriceWithCur($row['price'], $getDefCurDet['curCode']) . '
                                    </span></div>
                        <div class="table-cell" style="width:33.33%"><span class="smryOtr_Val amountSections  smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . showOtherCur($row['curAmt'], $ordDet['ordCurId']) . '
                                </span></div>
                      </div>';
}

$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
                     INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                     WHERE od.ordId = '" . $_REQUEST['orderId'] . "' AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
$ordQry = mysqli_query($con, $sql);

$perCharges = 0;
$perChargesOther = 0;
while ($row = mysqli_fetch_array($ordQry)) //show here order level charges
{

    $perCharges += $row['price'];
    $perChargesOther = $row['curAmt'];


    $calDiscount = ($chargePrice * $row['price'] / 100);
    $calDiscountOther = ($chargePriceOther * $row['price'] / 100);
    $content .= '<div class="table-row">
                        <div class="table-cell" style="width:33.33%"><span class="sumBreakupAmtText amountSections smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . $row['feeName'] . '</span></div>
                        <div class="table-cell" style="width:33.33%"><span class="smryDef_Val amountSections smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . getPriceWithCur($calDiscount, $getDefCurDet['curCode']) . '
                 </span></div>
                 <div class="table-cell" style="width:33.33%"><span class="smryOtr_Val amountSections  smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . showOtherCur($calDiscountOther, $ordDet['ordCurId']) . '
                 </span></div>
                      </div>';
}

$totalCalDiscount = ($chargePrice * $perCharges / 100); //total discount feeType=3
$totalCalDiscountOther = ($chargePriceOther * $perCharges / 100);


$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
                     INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                     WHERE od.ordId = '" . $_REQUEST['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "' and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
$ordQry = mysqli_query($con, $sql);

$taxCharges = 0;
$totalTaxChargesOther = 0;
while ($row = mysqli_fetch_array($ordQry)) //show here order level charges
{
    $taxCharges += $row['price'];
    $totalTaxChargesOther += $row['curAmt'];

    $calTax = (($chargePrice + $fixedCharges + $totalCalDiscount) * $row['price'] / 100);
    $calTaxOther = (($chargePriceOther + $fixedChargesOther + $totalCalDiscountOther) * $row['price'] / 100);
    $content .= '<div class="table-row">
                        <div class="table-cell" style="width:33.33%"><span class="sumBreakupAmtText amountSections smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . $row['feeName'] . '</span></div>
                        <div class="table-cell" style="width:33.33%"><span class="smryDef_Val amountSections smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . getPriceWithCur($calTax, $getDefCurDet['curCode']) . '
                    </span></div>
                    <div class="table-cell" style="width:33.33%"><span class="smryOtr_Val amountSections  smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . showOtherCur($calTaxOther, $ordDet['ordCurId']) . '
                    </span></div>
                      </div>';
}

$totalTax = (($chargePrice + $fixedCharges + $totalCalDiscount) * $taxCharges / 100); //total tax feeType=1
$totalTaxOther = (($chargePriceOther + $fixedChargesOther + $totalCalDiscountOther) * $taxCharges / 100);


$netTotalAmt = ($chargePrice + $fixedCharges + $totalCalDiscount + $totalTax);
$netTotalAmtOther = ($chargePriceOther + $fixedChargesOther + $totalCalDiscountOther + $totalTaxOther);

if ($showGrandTotal) {
    $content .= '<div class="table-row">
                        <div class="table-cell"><span class="sumBreakupAmtText amountSections smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . showOtherLangText('Grand Total') . '</span></div>
                        <div class="table-cell"><span class="smryDef_Val amountSections smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . getPriceWithCur($netTotalAmt, $getDefCurDet['curCode']) . '
                    </span></div> <div class="table-cell"><span class="smryOtr_Val amountSections  smryHead" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">' . showOtherCur($netTotalAmtOther, $ordDet['ordCurId']) . '
                    </span></div>';
}
$content .= '</div>

                        </div>
                        </div>
                        </div>
                        </div>
                        </div>';


$content .= '';

$content .= '</div>
                                        </div>
                                    </div>
                                </div></div>';



$content .=     '<div id="itemDiv" class="px-2"><div class="modal-table fs-12 w-100 mt-">
                            <div class="table-row thead">';
$content .=    '<div class="table-cell">#</div>';
$content .=    '<div class="table-cell"><span style="display:none"   class="photo">' . showOtherLangText('Photo') . '</span></div>';
$content .=   '<div class="table-cell"><span   class="itmProd">' . showOtherLangText('Item') . '</span></div>';
$content .=   '<div class="table-cell"><span style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '"  class="itmCode">' . showOtherLangText('Barcode') . '</span></div>';
$content .=   '<div class="table-cell"><span style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '"  class="itmPrc">' . showOtherLangText('Price') . '(' . $getDefCurDet['curCode'] . ')</div>';
if ($ordDet['ordCurId'] > 0) {
    $content .=   '<div  class="table-cell"><span style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '"  class="otherCurPrice">' . showOtherLangText('Price') . '(' . $curDet['curCode'] . ')</span></div>';
}
$content .=   '<div class="table-cell"><span   class="itmPrcunit">' . showOtherLangText('Unit') . '</span></div>';
$content .=   '<div class="table-cell"><span   class="itmPurqty">' . showOtherLangText('Qty') . '</span></div>';
$content .=   '<div class="table-cell"><span style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '"  class="itmRecqty">' . showOtherLangText('Rec Qty.') . '</span></div>';
$content .=   '<div class="table-cell"><span style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '"  class="itmTotal">' . showOtherLangText('Total') . '(' . $getDefCurDet['curCode'] . ')</span></div>';
if ($ordDet['ordCurId'] > 0) {
    $content .=   '<div class="table-cell"><span style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '"  class="otherCurTotal">' . showOtherLangText('Total') . '(' . $curDet['curCode'] . ')</span></div>';
}
$content .=   '<div class="table-cell"><span   class="itmNote">' . showOtherLangText('Note') . '</span></div>';
$content .=   '</div>';
$i = 0;
while ($row = mysqli_fetch_array($otherChrgQry)) {
    $i++;
    $content .= '<div class="table-row">';
    $content .= '<div class="table-cell">' . $i . '</div>';
    $img = '';
    $content .= '<div class="table-cell"><span class="photo" style="display:none;" >' . $img . '</span></div>';
    $content .= '<div class="table-cell"><span class="itmProd">' . $row['itemName'] . '</span></div>';
    $content .=   '<div class="table-cell"><span   style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '" class="itmCode"></div>';

    $content .=  '<div class="table-cell"><span style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '"   class="itmPrc">' . getPriceWithCur($row['price'], $getDefCurDet['curCode']) . '</span></div>';
    if ($ordDet['ordCurId'] > 0) {
        $content .=  '<div class="table-cell"><span style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '"   class="otherCurPrice">' . getPriceWithCur(($row['curAmt']), $curDet['curCode'], $curDet['decPlace']) . '</span></div>';
    }
    $content .=  '<div class="table-cell"><span    class="itmPrcunit">' . $row['unit'] . '</span></div>';
    $content .=  '<div class="table-cell"><span    class="itmPurqty">1</span></div>';
    $content .=  '<div class="table-cell"><span style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '"   class="itmRecqty">1</span></div>';


    $content .= '<div class="table-cell"><span style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '"   class="itmTotal">' . getPriceWithCur($row['price'], $getDefCurDet['curCode']) . '</span></div>';

    if ($ordDet['ordCurId'] > 0) {
        $content .= '<div class="table-cell">
<span style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '"   class="otherCurTotal">' . getPriceWithCur($row['curAmt'], $curDet['curCode'], $curDet['decPlace']) . '</span></div';
    }
    $content .= '<div class="table-cell"><span  class="itmNote">' . $row['note'] . '</span></div>';
    $content .=   '</div>';
}

while ($row = mysqli_fetch_array($proresultSet)) {
    $i++;
    $content .= '<div class="table-row">';
    $content .= '<div class="table-cell">' . $i . '</div>';
    $img = '';
    if ($row['imgName'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/products/" . $row['imgName'])) {
        $img = '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/products/' . $row['imgName'] . '" width="60" height="60">';
    }
    $content .= '<div class="table-cell"><span class="photo" style="display:none;" >' . $img . '</span></div>';
    $content .= '<div class="table-cell"><span class="itmProd">' . $row['itemName'] . '</span></div>';
    $content .= '<div class="table-cell"><span   style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '" class="itmCode">' . $row['barCode'] . '</span></div>';
    $content .= '<div class="table-cell"><span style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '"   class="itmPrc">' . getPriceWithCur($row['price'] * $row['factor'], $getDefCurDet['curCode']) . '</span></div>';
    if ($ordDet['ordCurId'] > 0) {
        $content .= '<div class="table-cell"><span style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '"   class="otherCurPrice">' . getPriceWithCur(($row['curPrice'] * $row['factor']), $curDet['curCode'], $curDet['decPlace']) . '</span></div>';
    }
    $content .= '<div class="table-cell"><span    class="itmPrcunit">' . $row['purchaseUnit'] . '</span></div>';
    $content .= '<div class="table-cell"><span    class="itmPurqty">' . $row['qty'] . '</span></div>';
    $content .= '<div class="table-cell"><span style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '"   class="itmRecqty">' . $row['qtyReceived'] . '</span></div>';
    $content .= '<div class="table-cell"><span style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '"   class="itmTotal">' . getPriceWithCur($row['totalAmt'], $getDefCurDet['curCode']) . '</span></div>';
    if ($ordDet['ordCurId'] > 0) {
        $content .= '<div class="table-cell">
                        <span style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '"   class="otherCurTotal">' . getPriceWithCur($row['curAmt'], $curDet['curCode'], $curDet['decPlace']) . '</span></div>';
    }
    $content .= '<div class="table-cell"><span  class="itmNote">' . $row['note'] . '</span></div>';
    $content .= '</div>';
}


$content .= '</div></div>';

$content .=  '<div id="taskDiv" class="px-2" style="' . (($_POST['isSupDet'] == 1) ? 'display:none;' : '') . '">
                           <div class="modal-table fs-12 w-100 mt-4">
                            <div class="table-row thead">
                                <div class="table-cell">' . showOtherLangText('Status') . '</div>
                                <div class="table-cell">' . showOtherLangText('Date') . '</div>
                                <div class="table-cell">' . showOtherLangText('User') . '</div>
                                <div class="table-cell">' . showOtherLangText('Price') . '($)</div>';
if ($curDet['curCode'] != '') {
    $content .=  '<div class="table-cell">' . showOtherLangText('Price') . '(' . $curDet['curCode'] . ')</div>';
}
$content .=  '<div class="table-cell">' . showOtherLangText('Note') . '</div>
                            </div>';
while ($orderJourney = mysqli_fetch_array($orderJourneyQry)) {

    $content .=  '<div class="table-row">
                                <div class="table-cell">
        ' . showOtherLangText(ucfirst($orderJourney['action'])) . '</div>
                                <div class="table-cell">' . date('d/m/Y
        h:iA', strtotime($orderJourney['ordDateTime'])) . '</div>
                                <div class="table-cell">
        ' . ucfirst($orderJourney['name']) . '(' . ucfirst($orderJourney['designation_name']) . ')</div>
                                <div class="table-cell">' .
        getPriceWithCur($orderJourney['amount'], $getDefCurDet['curCode']) . '</div>';
    if ($curDet['curCode'] != '') {
        $content .=        '<div class="table-cell">' .
            getPriceWithCur($orderJourney['otherCur'], $curDet['curCode'], $curDet['decPlace']) . '</div>';
    }
    $content .= '<div class="table-cell">
        ' . ucfirst($orderJourney['notes']) . '</div>
                            </div>';
}

$content .=    '</div>
                    </div></div>';

echo $content;
