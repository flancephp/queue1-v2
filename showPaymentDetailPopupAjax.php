<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);




if (isset($_POST['orderId'])) {

    $sql = "SELECT tp.*, od.price ordPrice, od.curPrice ordCurPrice,  IF(u.name!='',u.name,tp.unitP) purchaseUnit, od.qty ordQty, od.totalAmt, od.factor ordFactor, od.qtyReceived FROM tbl_order_details od 
INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
LEFT JOIN tbl_units u ON(u.id = tp.unitP) AND u.account_id = tp.account_id
WHERE od.ordId = '" . $_POST['orderId'] . "'  AND tp.account_id = '" . $_SESSION['accountId'] . "' order by tp.catId  ";
    $ordQry = mysqli_query($con, $sql);
}

$cmd = "SELECT * FROM tbl_orders WHERE id='" . $_POST['orderId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ";
$ordersQry = mysqli_query($con, $cmd);
$ordersRow = mysqli_fetch_array($ordersQry);

$sqlQry = " SELECT * FROM tbl_currency WHERE id='" . $ordersRow['ordCurId'] . "' ";
$sqlResult = mysqli_query($con, $sqlQry);
$sqlResultRow = mysqli_fetch_array($sqlResult);
$otherCurCode = $sqlResultRow['curCode'];

$qry = "SELECT * FROM tbl_payment WHERE orderId='" . $_POST['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' order by id limit 1 ";
$result = mysqli_query($con, $qry);
$paymentRow = mysqli_fetch_array($result);

$sqlSet = " SELECT * FROM tbl_payment_mode WHERE id='" . $paymentRow['paymentType'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ";
$resultSet = mysqli_query($con, $sqlSet);
$payModeRow = mysqli_fetch_array($resultSet);


$qry = "SELECT * FROM tbl_supplier_payment_info WHERE  orderId='" . $_POST['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
$resultSet = mysqli_query($con, $qry);
$checkRow = mysqli_fetch_array($resultSet);




$content .= '<div class="modal-header"> 
                      <div class="mb-modal-close-icon">  <button type="button" class="btn-close m-0 d-lg-none" data-bs-dismiss="modal" aria-label="Close" fdprocessedid="mvv0xh"></button></div>
                    <div class="d-flex align-items-center justify-content-end w-100">
                        <a href="payment_pdf_download.php?getLangType=' . $getLangType . '&orderId=' . $_POST['orderId'] . '" class="btn btn-primary dwnBtn" target="_blank"><span class="align-middle">' . showOtherLangText('Press') . '</span> <i class="fa-solid fa-download ps-1"></i></a>
                    </div>
                </div>
                <div class="modal-body p-0">
                    <!--payment paid Details Popup Start -->
                    <div class="payment-status-left payment-paid position-relative">
                        <div class="border-line"></div>
                        <div class="modal-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center text-uppercase">';
if (isset($_POST['orderId']) && $paymentRow['paymentStatus'] == 1) {

    // $content .= ' <div>
    //     <h3 style="color: #ababab !important; margin-bottom: 0;font-size: 40px;line-height: 40px;font-weight: 800;">
    //         '. showOtherLangText('PAID').'</h3>
    // </div>';
    $content .= '<div>
                                    <p class="f-01 mb-0 payment-status-text">' . showOtherLangText('PAID') . '</p>';
} elseif ($_GET['paymentStatus'] == 2) {


    //     $content .= ' <div>
    //     <h3 style="color: #ababab !important; margin-bottom: 0;font-size: 40px;line-height: 40px;font-weight: 800;">
    //         '. showOtherLangText('REFUNDED').'</h3>
    // </div>';
    $content .= '<div>
                                    <p class="f-01 mb-0 payment-status-text">' . showOtherLangText('REFUNDED') . '</p>';
} else {

    //     $content .= ' <div>
    //     <h3 style="color: #ababab !important; margin-bottom: 0;font-size: 40px;line-height: 40px;font-weight: 800;">
    //         '. showOtherLangText('PENDING').'</h3>
    // </div>';
    $content .= '<div>
                                    <p class="f-01 mb-0 payment-status-text">' . showOtherLangText('PENDING') . '</p>';
}

$content .=  '<p class="f-01">' . showOtherLangText('PAYMENT') . '</p>
                                </div>
                                <div>';

$clientQry = " SELECT * FROM tbl_client WHERE id = '" . $_SESSION['accountId'] . "' ";
$clientResult = mysqli_query($con, $clientQry);
$clientResultRow = mysqli_fetch_array($clientResult);

if ($clientResultRow['logo'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/clientLogo/" . $clientResultRow['logo'])) {

    $content .=  '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . "/clientLogo/" . $clientResultRow['logo'] . '" width="100" height="100" style="object-fit: contain;">';
}
$content .=   '</div>
                            </div>

                            <div class="mt-4 row g-0 payment-tabel-header">
                                <div class="col-sm-6">
                                    <table class="table1 table01 info__table">
                                        <tbody>
                                            <tr>
                                                <td class="font-wt">' . showOtherLangText('Supplier Invoice') . ' #
                                                </td>
                                                <td class="in-val-p">' . $checkRow['supplierInvoice'] . '</td>
                                            </tr>

                                            <tr>
                                                <td class="font-wt">
                                                    ' . showOtherLangText('Payment') . ' #
                                                </td>
                                                <td class="in-val-p">' . setPaymentId($paymentRow['id']) . '</td>
                                            </tr>

                                            <tr>
                                                <td class="font-wt">' . showOtherLangText('Task') . ' #</td>
                                                <td class="in-val-p">' . $ordersRow['ordNumber'] . '</td>
                                            </tr>

                                            <tr>
                                                <td class="font-wt">' . showOtherLangText('Date') . ' #
                                                </td>
                                                <td class="in-val-p">' . $paymentRow['paymentDateTime'] . '</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>';

$sql = " SELECT * FROM tbl_country WHERE id = '" . $clientResultRow['country'] . "' ";
$resSet = mysqli_query($con, $sql);
$resultRow = mysqli_fetch_array($resSet);

$content .= '<div class="col-md-6">
                                <table class="w-100">
                                    <tbody class="table1 table01 fl-right cmp-dtl text-start text-md-end">
                                        <tr>
                                            <td>' . $clientResultRow['accountName'] . '</td>
                                        </tr>
                                        <tr>
                                            <td>' . $clientResultRow['address_one'] . ',' . $clientResultRow['address_two'] . '</td>
                                        </tr>
                                        <tr>
                                            <td>' . $clientResultRow['city'] . ',' . $resultRow['name'] . '</td>
                                        </tr>
                                        <tr>
                                            <td>' . $clientResultRow['email'] . '</td>
                                        </tr>
                                        <tr>
                                            <td>' . $clientResultRow['phone'] . '</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>

                            <br>
                            <div class="paymentto-p mt-3">
                                <p class="f-02 mb-2">' . showOtherLangText('Payment To') . ':</p>
                                <p class="f-03 in-val-p mb-1">' . $checkRow['supplierName'] . '</p>
                                <p class="f-03 in-val-p mb-1">' . nl2br($checkRow['supplierAddress']) . '</p>
                                <p class="f-03 in-val-p mb-1">' . $checkRow['supplierEmail'] . '</p>
                                <p class="f-03 in-val-p mb-1">' . $checkRow['supplierPhone'] . '</p>
                            </div>
                            <br>
                            <div class="table-responsive">
                            <table class="modal-table fs-12 w-100 mt-md-4 payment__table">
                                <thead>
                                    <tr class="tr-bg-1">
                                        <th>#</th>
                                        <th style="width: 30%;">' . showOtherLangText('Item') . '</th>
                                        <th>' . showOtherLangText('Unit') . '</th>
                                        <th>' . showOtherLangText('Order Quantity') . '</th>
                                        <th>' . showOtherLangText('Receive Quantity') . '</th>';
if ($ordersRow['ordCurId'] > 0) {


    $content .= ' <th class="th-bg-1">
                                ' . showOtherLangText('Price') . '(' . $sqlResultRow['curCode'] . ')
                            </th>';
} else {

    $content .= ' <th class="th-bg-1">' . showOtherLangText('Price') . '(' . $getDefCurDet['curCode'] . ')</th>';
}
if ($ordersRow['ordCurId'] > 0) {

    $content .= ' <th class="th-bg-1">
                                    ' . showOtherLangText('Total') . '(' . $sqlResultRow['curCode'] . ')
                                </th>';
} else {

    $content .= '<th class="th-bg-1">' . showOtherLangText('Total') . '(' . $getDefCurDet['curCode'] . ')</th>';
}
$content .= '</tr>
                                </thead>
                                <tbody class="tabel-body-p">';

$sql = "SELECT cif.itemName, cif.unit,cif.amt, tod.* FROM tbl_order_details tod 
                        INNER JOIN tbl_custom_items_fee cif ON(tod.customChargeId = cif.id) AND tod.account_id = cif.account_id
                        WHERE tod.ordId = '" . $_POST['orderId'] . "'  AND tod.account_id = '" . $_SESSION['accountId'] . "'  and tod.customChargeType=1 ORDER BY cif.itemName ";
$resultSet = mysqli_query($con, $sql);
$x = 0;
while ($showCif = mysqli_fetch_array($resultSet)) {
    $x++;

    $content .= '<tr>
                                        <td>' . $x . '</td>
                                        <td style="width: 30%;">' . $showCif['itemName'] . '</td>
                                        <td>' . $showCif['unit'] . '</td>
                                        <td>1</td>
                                        <td>1</td>';
    if ($ordersRow['ordCurId'] > 0) {

        $content .= '<td class="pay-dt">
                                ' . showOtherCur($showCif['amt'] * $sqlResultRow['amt'], $ordersRow['ordCurId']) . '
                            </td>';
    } else {

        $content .= ' <td class="pay-dt">' . getPriceWithCur($showCif['amt'], $getDefCurDet['curCode']) . '</td>';
    }

    if ($ordersRow['ordCurId'] > 0) {


        $content .= '<td>
                                ' . showOtherCur($showCif['amt'] * $sqlResultRow['amt'], $ordersRow['ordCurId']) . '
                                </td>';
    } else {

        $content .= '<td>
                                ' . getPriceWithCur($showCif['amt'], $getDefCurDet['curCode']) . '
                                 </td>';
    }
    $content .= '</tr>';
}

$sum = 0;

while ($row = mysqli_fetch_array($ordQry)) {
    $x++;
    $content .= '<tr>
                                        <td>' . $x . '</td>
                                        <td style="width: 30%;">' . $row['itemName'] . '</td>
                                        <td>' . $row['purchaseUnit'] . '</td>
                                        <td>' . $row['ordQty'] . '</td>
                                        <td>' . $row['qtyReceived'] . '</td>';
    if ($ordersRow['ordCurId'] > 0) {

        $content .= '<td>
                                ' . showOtherCur($row['ordCurPrice'] * $row['factor'], ($ordersRow['ordCurId'])) . '
                            </td>';
    } else {

        $content .= ' <td>
                                ' . getPriceWithCur($row['ordPrice'] * $row['factor'], $getDefCurDet['curCode']) . '
                            </td>';
    }

    if ($ordersRow['ordCurId'] > 0) {


        $content .= '<td>
                                ' . showOtherCur($row['ordQty'] * $row['ordCurPrice'] * $row['factor'], ($ordersRow['ordCurId'])) . '
                            </td>';
    } else {

        $content .= '<td>
                                    ' . getPriceWithCur($row['ordPrice'] * $row['ordQty'] * $row['factor'], $getDefCurDet['curCode']) . '
                                       </td>';
    }

    $content .= '</tr>';
}
$content .= '</tbody>
                            </table></div> 
                            <br>
                            <div class="row gy-4 gx-0 justify-content-between total__table__res__row__reverse">
                                <div class=" data__col col-md-4"><div class="table1">
                                    <p class="f-02 mb-2 ">' . showOtherLangText('Payment Method') . '</p>';
$sqlSet = " SELECT * FROM tbl_payment WHERE orderId='" . $paymentRow['orderId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' order by id limit 1 ";

$result = mysqli_query($con, $sqlSet);
$resultQryRow = mysqli_fetch_array($result);

if (isset($resultQryRow['bankAccountId'])) {
    $sqlSet = " SELECT * FROM tbl_payment_mode where id='" . $resultQryRow['paymentType'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
    $resultSet = mysqli_query($con, $sqlSet);
    $payModeRow = mysqli_fetch_array($resultSet);

    $sqlSet = " SELECT * FROM  tbl_accounts WHERE id='" . $resultQryRow['bankAccountId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
    $resultSet = mysqli_query($con, $sqlSet);
    $accDet = mysqli_fetch_array($resultSet);

    $curQry = " SELECT * FROM tbl_currency WHERE id='" . $resultQryRow['currencyId'] . "' AND account_id='" . $_SESSION['accountId'] . "' ";
    $curresult = mysqli_query($con, $curQry);
    $curRow = mysqli_fetch_array($curresult);

    if ($resultQryRow['currencyId'] > 0) {

        $curCode = $curRow['curCode'];
        $curName = $curRow['currency'];
    } else {

        $curCode = "$";
        $curName = "USD";
    }
    $content .=  '<p class="f-03 in-val-p mb-1">' . $payModeRow['modeName'] . '</p>';
    $content .=  '<p class="f-03 in-val-p mb-1">' . $accDet['accountName'] . '</p><br>';
}

$content .= '</div></div>

                                <!-- grand totale  here -->
                                <div class="table__col">
                                <table class="grand-total-tabel w-100">
                                    <tbody>';
$sqlSet = "SELECT SUM(totalAmt) as sum1, SUM(curAmt) AS sum2 from tbl_order_details where ordId='" . $_POST['orderId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  AND (customChargeType='1' OR customChargeType='0')";
$resultSet = mysqli_query($con, $sqlSet);
$chargeRow = mysqli_fetch_array($resultSet);
$chargePrice = $chargeRow['sum1'];
$totalPriceOther = ($chargeRow['sum2']);


$ordCount = "SELECT * from tbl_order_details where ordId='" . $_POST['orderId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' AND customChargeType='2' ";
$ordCountResult = mysqli_query($con, $ordCount);
$ordCountRow = mysqli_num_rows($ordCountResult);

if ($ordCountRow > 0) {
    if ($ordersRow['ordCurId'] > 0) {

        $content .= '<tr>
                            <td>' . showOtherLangText('Sub Total') . '</td>
                            <td style="font-weight: bold;">
                               ' . showOtherCur($totalPriceOther, $ordersRow['ordCurId']) . '
                            </td>
                        </tr>';
    } else {

        $content .= '<tr>
                            <td>' . showOtherLangText('Sub Total') . '</td>
                            <td style="font-weight: bold;">
                                ' .  getPriceWithCur($chargePrice, $getDefCurDet['curCode']) . '</td>
                        </tr>';
    }
}
//Starts order level fixed discount charges
$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
    INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
    WHERE od.ordId = '" . $_POST['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

$ordQry = mysqli_query($con, $sql);

$fixedCharges = 0;
$fixedChargesOther = 0;

$sql = " SELECT SUM(od.totalAmt) AS totalFixedCharges, tp.feeName FROM tbl_order_details od 
    INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
    WHERE od.ordId = '" . $_POST['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName   ";
$sumQry = mysqli_query($con, $sql);
$totalSum = mysqli_fetch_array($sumQry);

$totalFixedCharges = $totalSum['totalFixedCharges'];


while ($row = mysqli_fetch_array($ordQry)) //show here order level charges
{
    $fixedCharges = $row['price'];
    $fixedChargesOther = $row['curAmt'];
    $totalFixedChargesOther = $fixedChargesOther;

    if ($ordersRow['ordCurId'] > 0) {
        $content .= '<tr>
                                        <td>' . $row['feeName'] . '</td>
                                        <td>' . showOtherCur($fixedChargesOther, $ordersRow['ordCurId']) . '</td>
                                    </tr>';
    } else {

        $content .= '<tr>
                                        <td>' . $row['feeName'] . '</td>
                                        <td>' . getPriceWithCur($fixedCharges, $getDefCurDet['curCode']) . '</td>
                                    </tr>';
    }
}

//Start order level discoutns

$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
      INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
      WHERE od.ordId = '" . $_POST['orderId'] . "' AND od.account_id = '" . $_SESSION['accountId'] . "' and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
$ordQry = mysqli_query($con, $sql);

$perCharges = 0;
$itemIds = [];
$totalCharges = 0;
while ($row = mysqli_fetch_array($ordQry)) //show here order level charges
{
    $itemIds[] = $row['customChargeId'];
    $totalCharges = $row['totalAmt'];
    $perCharges += $row['totalAmt'];

    $discountPercent = $chargePrice * $totalCharges / 100;
    $discountPercentOther = $totalPriceOther * $totalCharges / 100;
    $totalDiscountPercent = $chargePrice * $perCharges / 100;
    $totalDiscountPercentOther = $totalPriceOther * $perCharges / 100;
    if ($row) {
        if ($ordersRow['ordCurId'] > 0) {
            $content .= '<tr>
                                        <td>' . $row['feeName'] . '</td>
                                        <td>' . showOtherCur($discountPercentOther, $ordersRow['ordCurId']) . '</td>
                                    </tr>';
        } else {
            $content .= '<tr>
                                        <td>' . $row['feeName'] . '</td>
                                        <td>' . getPriceWithCur($discountPercent, $getDefCurDet['curCode']) . '</td>
                                    </tr>';
        }
    }
} //End of order level discount

//Starts order level tax discount charges
$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
            INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
            WHERE od.ordId = '" . $_POST['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
$ordQry = mysqli_query($con, $sql);

$taxCharges = 0;
while ($row = mysqli_fetch_array($ordQry)) //show here order level charges
{
    $tax = $row['price'];

    $taxCharges = (($chargePrice + $totalFixedCharges + $totalDiscountPercent) * $tax / 100);
    $taxChargesOther = (($totalPriceOther + $totalFixedChargesOther + $totalDiscountPercentOther) * $tax / 100);
    $totalTaxCharges += (($chargePrice + $totalFixedCharges + $totalDiscountPercent) * $tax / 100);
    $totalTaxChargesOther += (($totalPriceOther + $totalFixedChargesOther + $totalDiscountPercentOther) * $tax / 100);

    if ($ordersRow['ordCurId'] > 0) {
        $content .= '<tr>
                                        <td>' . $row['feeName'] . '</td>
                                        <td>' . showOtherCur($taxChargesOther, $ordersRow['ordCurId']) . '</td>
                                    </tr>';
    } else {
        $content .= '<tr>
                                        <td>' . $row['feeName'] . '</td>
                                        <td>' . getPriceWithCur($taxCharges, $getDefCurDet['curCode']) . '</td>
                                    </tr>';
    }
} //end order level tax discount charges
$sqlSet = "SELECT SuM(totalAmt) AS totalFixedDiscount, SUM(curAmt) AS totalFixedDiscountOther FROM tbl_order_details od 
        INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
        WHERE od.ordId = '" . $_POST['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";
$resultSet = mysqli_query($con, $sqlSet);
$fixedDiscountRow = mysqli_fetch_array($resultSet);
$totalFixedDiscount = $fixedDiscountRow['totalFixedDiscount'];
$totalFixedDiscountOther = $fixedDiscountRow['totalFixedDiscountOther'];

$netTotalAmt = ($chargePrice + $totalTaxCharges + $totalDiscountPercent + $totalFixedDiscount);
$netTotalAmtOther = ($totalPriceOther + $totalTaxChargesOther + $totalDiscountPercentOther + $totalFixedDiscountOther);


$defaultClass = 'class="grand-total active__th__row"';
if ($ordersRow['ordCurId'] > 0) {
    $defaultClass = '';

    $content .= '<tr class="grand-total active__th__row" style=" max-height: 38px;">
                                        <th>' . showOtherLangText('Grand Total') . ' (' . $sqlResultRow['curCode'] . ')</th>
                                        <th>' . showOtherCur($netTotalAmtOther, $ordersRow['ordCurId']) . '</th>
                                    </tr>
                                    <tr></tr>';
}


$content .= '<tr ' . $defaultClass . '  style=" max-height: 38px;">
                                        <th class="total__th" style="padding: .5rem .625rem;">' . showOtherLangText('Grand Total') . ' (' . $getDefCurDet['curCode'] . ')</th>
                                        <th class="total__th" style="padding: .5rem .625rem;">' . getPriceWithCur($netTotalAmt, $getDefCurDet['curCode']) . '</th>
                                    </tr>
                                    <tr></tr>';

$content .= '</tbody></table>
                            </div>
                            </div>

                            <br>
                            <br>
                            <br>
                        </div>';
echo $content;
