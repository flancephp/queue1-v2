<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


if (!isset($_SESSION['adminidusername'])) {
    echo '<script>window.location="login.php"</script>';
}


if (isset($_POST['orderId'])) {
    $sql = "SELECT tp.*, od.price ordPrice, IF(u.name!='',u.name,tp.unitC) countingUnit, od.qty ordQty, od.totalAmt, od.factor ordFactor FROM tbl_order_details od 
INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
LEFT JOIN tbl_units u ON(u.id = tp.unitC) AND u.account_id = tp.account_id
WHERE od.ordId = '" . $_POST['orderId'] . "'   AND tp.account_id = '" . $_SESSION['accountId'] . "' ";
    $ordQry = mysqli_query($con, $sql);
}


//show details
$cmd = "SELECT * FROM tbl_orders WHERE id='" . $_POST['orderId'] . "'   AND account_id = '" . $_SESSION['accountId'] . "' ";
$ordersQry = mysqli_query($con, $cmd);
$ordersRow = mysqli_fetch_array($ordersQry);

$qry = "SELECT * FROM tbl_req_payment WHERE orderId='" . $_POST['orderId'] . "'   AND account_id = '" . $_SESSION['accountId'] . "' order by id desc limit 1  ";
$result = mysqli_query($con, $qry);
$paymentRow = mysqli_fetch_array($result);


$sqlSet = " SELECT * FROM tbl_payment_mode WHERE id='" . $paymentRow['paymentType'] . "'   AND account_id = '" . $_SESSION['accountId'] . "' ";
$resultSet = mysqli_query($con, $sqlSet);
$payModeRow = mysqli_fetch_array($resultSet);


$qry = "SELECT * FROM tbl_requisition_payment_info WHERE  orderId='" . $_POST['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
$resultSet = mysqli_query($con, $qry);
$paymentInfoRow = mysqli_fetch_array($resultSet);




if ($paymentRow['paymentStatus'] == 1) {
    $classname = 'recived-invoice';
} else if ($paymentRow['paymentStatus'] == 2) {
    $classname = 'payment-paid';
} else {
    $classname = 'payment-pending';
}



$content .= '<div class="modal-header pb-3"> 
                      <div class="mb-modal-close-icon position-static w-100">  <button type="button" class="btn-close m-0 d-lg-none" data-bs-dismiss="modal" aria-label="Close" fdprocessedid="mvv0xh"></button></div>
                    <div class="d-flex align-items-center justify-content-end w-100">
                        <a href="inv_pdf_download.php?getLangType=' . $getLangType . '&orderId=' . $_POST['orderId'] . '"" class="btn btn-primary" target="_blank"><span class="align-middle">' . showOtherLangText('Press') . '</span> <i class="fa-solid fa-download ps-1"></i></a>
                        
                    </div>
                </div>
                <div class="modal-body m-0 p-0">
                    <!-- invoice Details Start -->
                    <div id="invoice-Details" class="payment-status-left ' . $classname . ' position-relative">
                        <div class="border-line"></div>
                        <div class=" p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>';
if (isset($_POST['orderId']) && $paymentRow['paymentStatus'] == 1) {


    $content .= '<p class="f-01 mb-0 payment-status-text text-uppercase">' . showOtherLangText('RECEIVED') . '</p>';
} elseif ($_GET['paymentStatus'] == 2) {
    $content .= '<p class="f-01 mb-0 payment-status-text text-uppercase">' . showOtherLangText('REFUNDED') . '</p>';
} else {

    $content .= '<p class="f-01 mb-0 payment-status-text text-uppercase">' . showOtherLangText('PENDING') . '</p>';
}

$content .= '<p class="f-01 text-uppercase">' . showOtherLangText('INVOICE') . '</p>';
$content .= '</div><div>';
$clientQry = " SELECT * FROM tbl_client WHERE id = '" . $_SESSION['accountId'] . "' ";
$clientResult = mysqli_query($con, $clientQry);
$clientResultRow = mysqli_fetch_array($clientResult);

if ($clientResultRow['logo'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/clientLogo/" . $clientResultRow['logo'])) {

    $content .=  '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . "/clientLogo/" . $clientResultRow['logo'] . '" width="100" height="100" style="object-fit: contain;">';
}
$content .= '</div></div>

                            <div class="mt-1 mt-md-4 row g-2 g-md-0 payment-tabel-header">
                                <div class="col-md-6">
                                    <table class="table1 table01">
                                        <tbody>
                                            <tr>
                                                <td class="font-wt">
                                                    ' . showOtherLangText('Invoice') . ' #
                                                </td>
                                                <td class="in-val-p dark__td__color">' . getinvoiceNumber($paymentInfoRow['invoiceNumber']) . '</td>
                                            </tr>

                                            <tr>
                                                <td class="font-wt">' . showOtherLangText('Task') . ' #</td>
                                                <td class="in-val-p dark__td__color">' . $ordersRow['ordNumber'] . '</td>
                                            </tr>

                                            <tr>
                                                <td class="font-wt">' . showOtherLangText('Date') . ' #
                                                </td><td class="in-val-p dark__td__color">';
if ($paymentRow['paymentStatus'] == 1) {
    $content .=   $paymentRow['paymentDateTime'];
} else {
    $content .=   $ordersRow['ordDateTime'];
}
$content .= '</td>
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
                            <div class="invoiceto-p">
                                <p class="f-02 mb-2">' . showOtherLangText('Invoice To') . ': </p>
                                <p class="f-03 in-val-p mb-1">' . $paymentInfoRow['invoiceName'] . '</p>
                                <p class="f-03 in-val-p mb-1">' . nl2br($paymentInfoRow['invoiceAddress']) . '</p>
                                <p class="f-03 in-val-p mb-1">' . $paymentInfoRow['invoiceEmail'] . '</p>
                                <p class="f-03 in-val-p mb-1">' . $paymentInfoRow['invoicePhone'] . '</p>
                            </div>
                            <br>
                            <div class="table-responsive"> 
                            <table class="modal-table payment__table fs-12 w-100 mt-2 mt-sm-3 mt-lg-4">
                                <thead>
                                    <tr class="tr-bg-1">
                                        <th>#</th>
                                        <th style="width: 30%;">' . showOtherLangText('Item') . '</th>
                                        <th>' . showOtherLangText('Unit') . '</th>
                                        <th>' . showOtherLangText('Quantity') . '</th>
                                        <th class="th-bg-1">' . showOtherLangText('Price') . ' ' . $getDefCurDet['curCode'] . '</th>
                                        <th class="th-bg-1">' . showOtherLangText('Total') . ' ' . $getDefCurDet['curCode'] . '</th>
                                    </tr>
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
                                        <td>' . getPriceWithCur($showCif['amt'], $getDefCurDet['curCode']) . '</td>
                                        <td>' . getPriceWithCur($showCif['amt'], $getDefCurDet['curCode']) . '</td>
                                        </tr>';
}

$sum = 0;
while ($row = mysqli_fetch_array($ordQry)) {

    $x++;
    $totalPrice = round($row['price'], 2);
    $Quantity = $row['ordQty'];
    $bill_total = ($totalPrice * $Quantity);
    $sum = $sum + $bill_total;
    $orderId = $_POST['orderId'];

    $content .= '<tr>
                                        <td>' . $x . '</td>
                                        <td style="width: 30%;">' . $row['itemName'] . '</td>
                                        <td>' . $row['countingUnit'] . '</td>
                                        <td>' . $row['ordQty'] . '</td>
                                        <td>' . getPriceWithCur($row['ordPrice'], $getDefCurDet['curCode']) . '</td>
                                        <td>' . getPriceWithCur($row['ordQty'] * $row['ordPrice'], $getDefCurDet['curCode']) . '</td>
                                        </tr>';
}

$content .= '</tbody>
                            </table>
                            </div>
                            <br>
                            <div class="tabel-body-p-footer row g-3 total__table__res__row__reverse">
                                <div class="table1 col-md-4 data__col">
                                    <p class="f-02 mb-1">' . showOtherLangText('Payment Method') . ':</p>';
$sqlSet = " SELECT * FROM  tbl_accounts WHERE id='" . $paymentRow['bankAccountId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
$resultSet = mysqli_query($con, $sqlSet);
$accDet = mysqli_fetch_array($resultSet);
$content .= '<p class="f-03 mb-1 in-val-p">' . $payModeRow['modeName'] . '</p>
                                    <p class="f-03 mb-1 in-val-p">' . $accDet['accountName'] . '</p>';
$content .= '</div>

                               <div class="table__col large"> 
                                <table class="grand-total-tabel m-0 w-100"><tbody>';
$sqlSet = "SELECT SUM(totalAmt) as sum1 from tbl_order_details where ordId='" . $_POST['orderId'] . "'   AND account_id = '" . $_SESSION['accountId'] . "' AND (customChargeType='1' OR customChargeType='0')";
$resultSet = mysqli_query($con, $sqlSet);
$chargeRow = mysqli_fetch_array($resultSet);
$chargePrice = $chargeRow['sum1'];

$ordCount = "SELECT * from tbl_order_details where ordId='" . $_POST['orderId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' AND customChargeType='2' ";
$ordCountResult = mysqli_query($con, $ordCount);
$ordCountRow = mysqli_num_rows($ordCountResult);

if ($ordCountRow > 0) {
    $content .= '<tr>
                                    <td>' . showOtherLangText('Sub Total') . '</td>
                                    <td>' . getPriceWithCur($chargePrice, $getDefCurDet['curCode']) . '</td>
                                    </tr>';
}

//Starts order level fixed discount charges
$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
                            INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                            WHERE od.ordId = '" . $_POST['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

$ordQry = mysqli_query($con, $sql);

$fixedCharges = 0;

$sql = " SELECT SUM(od.totalAmt) AS totalFixedCharges, tp.feeName FROM tbl_order_details od 
                            INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                            WHERE od.ordId = '" . $_POST['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName   ";
$sumQry = mysqli_query($con, $sql);
$totalSum = mysqli_fetch_array($sumQry);

$totalFixedCharges = $totalSum['totalFixedCharges'];


while ($row = mysqli_fetch_array($ordQry)) //show here order level charges
{
    $fixedCharges = $row['price'];
    $content .= '<tr>
                                    <td>' . $row['feeName'] . '</td>
                                    <td>' . getPriceWithCur($fixedCharges, $getDefCurDet['curCode']) . '</td>
                                    </tr>';
} //Ends order lelvel fixed discount charges

//Start order level discoutns

$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
          INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
          WHERE od.ordId = '" . $_POST['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
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
    $totalDiscountPercent = $chargePrice * $perCharges / 100;
    if ($row) {
        $content .= '<tr>
                                    <td>' . $row['feeName'] .  $row['totalAmt'] . ' %</td>
                                    <td>' . getPriceWithCur($discountPercent, $getDefCurDet['curCode']) . '</td>
                                    </tr>';
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
    $totalTaxCharges += (($chargePrice + $totalFixedCharges + $totalDiscountPercent) * $tax / 100);
    $content .= '<tr>
                                    <td>' . $row['feeName'] . $row['price'] . ' %</td>
                                    <td>' . getPriceWithCur($taxCharges, $getDefCurDet['curCode']) . '</td>
                                    </tr>';
} //Ends order lelvel tax discount charges

$sqlSet = "SELECT SuM(totalAmt) AS totalFixedDiscount FROM tbl_order_details od 
            INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
            WHERE od.ordId = '" . $_POST['orderId'] . "'   AND od.account_id = '" . $_SESSION['accountId'] . "' and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";
$resultSet = mysqli_query($con, $sqlSet);
$fixedDiscountRow = mysqli_fetch_array($resultSet);
$totalFixedDiscount = $fixedDiscountRow['totalFixedDiscount'];

$netTotalAmt = ($chargePrice + $totalTaxCharges + $totalDiscountPercent + $totalFixedDiscount);

$content .= '<tr class="grand-total" style=" max-height: 38px;">
                                            <th style="background: #7A89FF;color:#fff;">' . showOtherLangText('Grand Total') . '</th>
                                            <th style="background: #7A89FF;color:#fff;">' . getPriceWithCur($netTotalAmt, $getDefCurDet['curCode']) . '</th>
                                    </tr>
                                        <tr></tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
 
                            <br>
                        </div>
                    </div>
                    <!-- invoice Detail End -->
                </div>';
echo $content;
