<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 




if (isset($_GET['orderId'])) {

  $sql = "SELECT tp.*, od.price ordPrice, od.curPrice ordCurPrice,  IF(u.name!='',u.name,tp.unitP) purchaseUnit, od.qty ordQty, od.totalAmt, od.factor ordFactor, od.qtyReceived FROM tbl_order_details od 
INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
LEFT JOIN tbl_units u ON(u.id = tp.unitP) AND u.account_id = tp.account_id
WHERE od.ordId = '" . $_GET['orderId'] . "'  AND tp.account_id = '" . $_SESSION['accountId'] . "'  ";
  $ordQry = mysqli_query($con, $sql);
}

$cmd = "SELECT * FROM tbl_orders WHERE id='" . $_GET['orderId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ";
$ordersQry = mysqli_query($con, $cmd);
$ordersRow = mysqli_fetch_array($ordersQry);

$sqlQry = " SELECT * FROM tbl_currency WHERE id='" . $ordersRow['ordCurId'] . "' ";
$sqlResult = mysqli_query($con, $sqlQry);
$sqlResultRow = mysqli_fetch_array($sqlResult);
$otherCurCode = $sqlResultRow['curCode'];

$qry = "SELECT * FROM tbl_payment WHERE orderId='" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' order by id limit 1 ";
$result = mysqli_query($con, $qry);
$paymentRow = mysqli_fetch_array($result);

$sqlSet = " SELECT * FROM tbl_payment_mode WHERE id='" . $paymentRow['paymentType'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ";
$resultSet = mysqli_query($con, $sqlSet);
$payModeRow = mysqli_fetch_array($resultSet);


$qry = "SELECT * FROM tbl_supplier_payment_info WHERE  orderId='" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
$resultSet = mysqli_query($con, $qry);
$checkRow = mysqli_fetch_array($resultSet);

$content = '<!DOCTYPE html>
<html lang="he">
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="preconnect" href="https://fonts.googleapis.com/">
      <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
      <link href="./PDF Design_files/css2" rel="stylesheet">

      <title>' . showOtherLangText('Payment PDF') . '</title>
      <style>
        th { text-align: left; }
        font-family: "DejaVu Sans Mono", monospace;

      </style>
  </head>
 <body style="font-family: firefly, DejaVu Sans, sans-serif, Inter; color: #232859; font-weight: 400; font-size: 12px; line-height: 14px;">
    <div style="margin: auto; max-width: 800px; position: relative; border-radius: 5px;">
     
      <div style="padding: 1% 4% ;">
        <!-- Header Section -->
      <table style="width: 100%; border-collapse: collapse;">
        <tr>
         <td style="text-align: left;width:50%;">';
$clientQry = " SELECT * FROM tbl_client WHERE id = '" . $_SESSION['accountId'] . "' ";
$clientResult = mysqli_query($con, $clientQry);
$clientResultRow = mysqli_fetch_array($clientResult);

if ($clientResultRow['logo'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/clientLogo/" . $clientResultRow['logo'])) {

  $content .= '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/clientLogo/' . $clientResultRow['logo'] . '" width="100" style="object-fit: contain;">';
}
$content .= '</td>

          <td style="text-align: right;width:50%;">';

if (isset($_GET['orderId']) && $paymentRow['paymentStatus'] == 1) {

  $content .= '<p style="font-size:40px;font-weight:700;line-height:40px;color:#596BF3 !important; margin-bottom: 0;text-transform: uppercase;">
                            ' . showOtherLangText('PAID') . '</p>';
} elseif ($_GET['paymentStatus'] == 2) {

  $content .= '<p style="font-size:40px;font-weight:700;line-height:40px;color:#596BF3 !important; margin-bottom: 0;text-transform: uppercase;">
                            ' . showOtherLangText('REFUNDED') . '</p>';
} else {

  $content .= '<p style="font-size:40px;font-weight:700;line-height:40px;color:#596BF3 !important; margin-bottom: 0;text-transform: uppercase;">
                            ' . showOtherLangText('PENDING') . '</p>';
}
$content .= '<p style="font-size:40px;font-weight:700;line-height:40px;color:#666C85;margin-top: 0rem;text-transform: uppercase;">' .  showOtherLangText('PAYMENT') . '</p>';
$content .= '</td>
         
        </tr>
      </table>

<!-- Supplier Invoice and Payment Info -->
<table style="width: 100%;border-collapse: collapse;text-align: right;">
  <tr>
    ';
$sql = " SELECT * FROM tbl_country WHERE id = '" . $clientResultRow['country'] . "' ";
$resSet = mysqli_query($con, $sql);
$resultRow = mysqli_fetch_array($resSet);
$content .= '<td>
      <table style=" text-align: left; width: 100%; border-collapse: collapse;">
        <tbody>
          <tr>
            <td style="padding:0;font-size:16px;line-height:20px;color:#666C85 !important;">' . reverseRTLTextForPdf($clientResultRow['accountName']) . '</td>
          </tr>
          <tr>
            <td style="padding:0;font-size:16px;line-height:20px;color:#666C85 !important;">' . reverseRTLTextForPdf($clientResultRow['address_one']) . ',' . reverseRTLTextForPdf($clientResultRow['address_two']) . '</td>
          </tr>
          <tr>
            <td style="padding:0;font-size:16px;line-height:20px;color:#666C85 !important;">' . reverseRTLTextForPdf($clientResultRow['city']) . ',' . reverseRTLTextForPdf($resultRow['name']) . '</td>
          </tr>
          <tr>
            <td style="padding:0;font-size:16px;line-height:20px;color:#666C85 !important;">' . reverseRTLTextForPdf($clientResultRow['email']) . '</td>
          </tr>
          <tr>
            <td style="padding:0;font-size:16px;line-height:20px;color:#666C85 !important;">' . reverseRTLTextForPdf($clientResultRow['phone']) . '</td>
          </tr>
        </tbody>
      </table>
    </td>

    <td style="width: 50%;text-align: right;">
      <table style="width: 100%; border-collapse: collapse;text-align: right;">
        <tbody>
          <tr>
                      <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;color:#666C85 !important;">' . $checkRow['supplierInvoice'] . '</td>

            <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;"># ' . showOtherLangText('Supplier Invoice') . ' </td>
          </tr>
          <tr>
                      <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;color:#666C85 !important;">' . setPaymentId($paymentRow['id']) . '</td>

            <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;"># ' . showOtherLangText('Payment') . ' </td>
          </tr>
          <tr>
                      <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;color:#666C85 !important;">' . $ordersRow['ordNumber'] . '</td>

            <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;"># ' . showOtherLangText('Task') . ' </td>
          </tr>
          <tr>
                      <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;color:#666C85 !important;">' . date('h:i:s Y-m-d', strtotime($paymentRow['paymentDateTime'])) . '</td>

            <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;"># ' . showOtherLangText('Date') . '</td>
          </tr>
        </tbody>
      </table>
    </td>
  </tr>
</table>';


$content .= '<br>
        <div style="text-align: right;">
          <p style="font-size:16px;line-height:19.36px;text-align:right; margin-bottom: .5rem;">:' . showOtherLangText('Payment To') . '</p>
          <p style="margin: 0; font-size:16px;line-height:19.36px;text-align:right;color:#666C85; margin-bottom: 0;">' . reverseRTLTextForPdf($checkRow['supplierName']) . '</p>
          <p style="margin: 0; font-size:16px;line-height:19.36px;text-align:right;color:#666C85; margin-bottom: 0;">' . reverseRTLTextForPdf($checkRow['supplierAddress']) . '</p>
          <p style="margin: 0; font-size:16px;line-height:19.36px;text-align:right;color:#666C85; margin-bottom: 0;">' . reverseRTLTextForPdf($checkRow['supplierEmail']) . '</p>
          <p style="margin: 0; font-size:16px;line-height:19.36px;text-align:right;color:#666C85; margin-bottom: 0;">' . $checkRow['supplierPhone'] . '</p>
        </div>';
$content .= '<br>
        <table style="width: 100%; margin-top: 16px; border-collapse: collapse;text-align: right;">
          <thead style="border-radius:10px !important;">
            <tr style="color:white;height:48px;background:#A9B0C0 !important;background-color: #f5f5f5;">';



if ($ordersRow['ordCurId'] > 0) {


  $content .= '<th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; font-weight: 700; background:#7A89FF;padding-left:.75rem;text-align: right;">' . ' (' . $getDefCurDet['curCode'] . ')' . showOtherLangText('Total')  . '</th>';
} else {


  $content .= '<th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; font-weight: 700; background:#7A89FF;padding-left:.5rem;text-align: right;">' . ' (' . $getDefCurDet['curCode'] . ')' . showOtherLangText('Total')  . '</th>';
}

if ($ordersRow['ordCurId'] > 0) {


  $content .= '<th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; background:#7A89FF;padding-left:.5rem;text-align: right;">
                                                                ' . '(' . $sqlResultRow['curCode'] . showOtherLangText('Price') . '</th>';
} else {
  $content .= '<th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; background:#7A89FF;padding-left:.5rem;text-align: right;">
                                                                ' . ' (' . $getDefCurDet['curCode'] . ')' . showOtherLangText('Price') .  '</th>';
}




$content .= ' <th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; padding-left:.5rem;text-align: right;">' . showOtherLangText('Receive Quantity') . '</th>
                <th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; padding-left:.5rem;text-align: right;">' . showOtherLangText('Order Quantity') . '</th>
                <th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; padding-left:.5rem;text-align: right;">' . showOtherLangText('Unit') . '</th>
                <th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; padding-left:.5rem;width: 30%;text-align: right;">' . showOtherLangText('Item') . '</th>

                <th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; padding-right:.5rem;text-align: right;">#</th>
             ';


$content .= '</tr>
          </thead><tbody>';
$sql = "SELECT cif.itemName, cif.unit,cif.amt, tod.* FROM tbl_order_details tod 
            INNER JOIN tbl_custom_items_fee cif ON(tod.customChargeId = cif.id) AND tod.account_id = cif.account_id
            WHERE tod.ordId = '" . $_GET['orderId'] . "'  AND tod.account_id = '" . $_SESSION['accountId'] . "'  and tod.customChargeType=1 ORDER BY cif.itemName ";
$resultSet = mysqli_query($con, $sql);
$x = 0;
while ($showCif = mysqli_fetch_array($resultSet)) {
  $x++;
  $content .= '<tr style="height:48px;">';

  if ($ordersRow['ordCurId'] > 0) {


    $content .=  '<td style="font-size: 12px; padding: .5rem .5rem .5rem .75rem;">' . showOtherCur($showCif['amt'] * $sqlResultRow['amt'], $ordersRow['ordCurId'], 0, 1) . '</td>';
  } else {
    $content .=  '<td style="font-size: 12px; padding: .5rem;">' . getPriceWithCur($showCif['amt'], $getDefCurDet['curCode'], 0, 1) . '</td>';
  }







  $content .=  '
                    <td style="font-size: 12px; padding: .5rem;">1</td>
                    <td style="font-size: 12px; padding: .5rem;">1</td>
                    <td style="font-size: 12px; padding: .5rem;">' . reverseRTLTextForPdf($showCif['unit']) . '</td>
                    <td style="font-size: 12px; padding: .5rem; width: 30%;">' . reverseRTLTextForPdf($showCif['itemName']) . '</td>

                    <td style="font-size: 12px; padding: .5rem;">' . $x . '</td>';


  $content .=  '</tr>';
}
$sum = 0;

while ($row = mysqli_fetch_array($ordQry)) {
  $x++;
  $content .= '<tr style="height:48px;">';


  if ($ordersRow['ordCurId'] > 0) {



    $content .=  '<td style="font-size: 12px; font-weight: 700; padding:.5rem .5rem .5rem .75rem;">' . showOtherCur($row['ordQty'] * $row['ordCurPrice'] * $row['factor'], ($ordersRow['ordCurId']), 0, 1) . '</td>';
  } else {


    $content .=  '<td style="font-size: 12px; font-weight: 700; padding:.5rem;">' . getPriceWithCur($row['ordPrice'] * $row['ordQty'] * $row['factor'], $getDefCurDet['curCode'], 0, 1) . '</td>';
  }

  if ($ordersRow['ordCurId'] > 0) {

    $content .=  '<td style="font-size: 12px; padding: .5rem .5rem .5rem .75rem;">' . showOtherCur($row['ordCurPrice'] * $row['factor'], ($ordersRow['ordCurId']), 0, 1) . '</td>';
  } else {

    $content .=  '<td style="font-size: 12px; padding: .5rem;">' . getPriceWithCur($row['ordPrice'] * $row['factor'], $getDefCurDet['curCode'], 0, 1) . '</td>';
  }



  $content .=  '<td style="font-size: 12px; padding: .5rem;">' . $row['qtyReceived'] . '</td>
                <td style="font-size: 12px; padding: .5rem;">' . $row['ordQty'] . '</td>
                <td style="font-size: 12px; padding: .5rem;">' . $row['purchaseUnit'] . '</td>
                <td style="font-size: 12px; padding: .5rem; width: 30%;">' . reverseRTLTextForPdf($row['itemName']) . '</td>

                <td style="font-size: 12px; padding: .5rem;">' . $x . '</td> ';
}
$content .=  '</tbody>
        </table>

        <div  style="width:100%;height:3px;background:#7A89FF;margin:.5rem 0;margin-top: 20px;"></div>

        <br>
  <table style=" width:100%; border-collapse: collapse;text-align: right;">
  <tr>';

$content .= '
    <td style="vertical-align: top; width:60%;">
      <table style="margin-top:-1rem;min-width:350px;height: 38px; border-collapse: collapse;width:100%;">
        <tbody>';
$curAmtVal = 0;
if ($ordRow['ordCurId'] > 0) {
  $curDet = getCurrencyDet($ordRow['ordCurId']);
  $curAmtVal = $curDet['amt'];
}




//get the sum of all product and item level charges  
$sqlSet = "SELECT SUM(totalAmt) as sum1, SUM(curAmt) AS sum2 from tbl_order_details where ordId='" . $_GET['orderId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  AND (customChargeType='1' OR customChargeType='0')";
$resultSet = mysqli_query($con, $sqlSet);
$chargeRow = mysqli_fetch_array($resultSet);
$chargePrice = $chargeRow['sum1'];
$totalPriceOther = ($chargeRow['sum2']);


$ordCount = "SELECT * from tbl_order_details where ordId='" . $_GET['orderId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' AND customChargeType='2' ";
$ordCountResult = mysqli_query($con, $ordCount);
$ordCountRow = mysqli_num_rows($ordCountResult);

if ($ordCountRow > 0) {
  if ($ordersRow['ordCurId'] > 0) {

    $content .= '<tr style="color:#666C85; font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
                    <th style="font-size:16px; color: #232859;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">' . showOtherCur($totalPriceOther, $ordersRow['ordCurId'], 0, 1) . '</th>
  
        <th style="font-size:16px;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">' . showOtherLangText('Sub Total') . '</th>
            </tr>';
  } else {
    $content .= '<tr style="color:#666C85; font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
                    <th style="font-size:16px; color: #232859;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">' . getPriceWithCur($chargePrice, $getDefCurDet['curCode'], 0, 1) . '</th>
    
        <th style="font-size:16px;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">' . showOtherLangText('Sub Total') . '</th>
             </tr>';
  }
}


//Starts order level fixed discount charges
$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
  INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
  WHERE od.ordId = '" . $_GET['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

$ordQry = mysqli_query($con, $sql);

$fixedCharges = 0;
$fixedChargesOther = 0;

$sql = " SELECT SUM(od.totalAmt) AS totalFixedCharges, tp.feeName FROM tbl_order_details od 
  INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
  WHERE od.ordId = '" . $_GET['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName   ";
$sumQry = mysqli_query($con, $sql);
$totalSum = mysqli_fetch_array($sumQry);

$totalFixedCharges = $totalSum['totalFixedCharges'];


while ($row = mysqli_fetch_array($ordQry)) //show here order level charges
{
  $fixedCharges = $row['price'];
  $fixedChargesOther = $row['curAmt'];
  $totalFixedChargesOther = $fixedChargesOther;

  if ($ordersRow['ordCurId'] > 0) {
    $content .= '<tr style="color:#666C85; font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
                    <th style="font-size:16px; color: #232859;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">' . showOtherCur($fixedChargesOther, $ordersRow['ordCurId'], 0, 1) . '</th>
   
        <th style="font-size:16px;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">' . reverseRTLTextForPdf($row['feeName']) . '</th>
             </tr>';
  } else {
    $content .= '<tr style="color:#666C85; font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
                    <th style="font-size:16px; color: #232859;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">' . getPriceWithCur($fixedCharges, $getDefCurDet['curCode'], 0, 1) . '</th>
    
        <th style="font-size:16px;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">' . reverseRTLTextForPdf($row['feeName']) . '</th>
             </tr>';
  }
} //Ends order lelvel fixed discount charges



//Start order level discoutns

$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
      INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
      WHERE od.ordId = '" . $_GET['orderId'] . "' AND od.account_id = '" . $_SESSION['accountId'] . "' and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
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
    }
  }
} //End of order level discount

//Starts order level tax discount charges
$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
      INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
      WHERE od.ordId = '" . $_GET['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
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
  }
} //end order level tax discount charges



$sqlSet = "SELECT SuM(totalAmt) AS totalFixedDiscount, SUM(curAmt) AS totalFixedDiscountOther FROM tbl_order_details od 
        INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
        WHERE od.ordId = '" . $_GET['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";
$resultSet = mysqli_query($con, $sqlSet);
$fixedDiscountRow = mysqli_fetch_array($resultSet);
$totalFixedDiscount = $fixedDiscountRow['totalFixedDiscount'];
$totalFixedDiscountOther = $fixedDiscountRow['totalFixedDiscountOther'];

$netTotalAmt = ($chargePrice + $totalTaxCharges + $totalDiscountPercent + $totalFixedDiscount);
$netTotalAmtOther = ($totalPriceOther + $totalTaxChargesOther + $totalDiscountPercentOther + $totalFixedDiscountOther);

$content .= '</tbody>';
if ($ordersRow['ordCurId'] > 0) {




  $content .= '<tbody>
          <tr style="background:#7A89FF;color:white;font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
                      <th style="font-size:18px;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">' . showOtherCur($netTotalAmtOther, $ordersRow['ordCurId'], 0, 1) . '</th>
  
          <th style="font-size:18px;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">' . ' (' . $getDefCurDet['curCode'] . ')' . showOtherLangText('Grand Total') . '</th>
          </tr>
        </tbody>
        <tbody>
          <tr style="font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
            <th style="font-size:18px;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">' . getPriceWithCur($netTotalAmt, $getDefCurDet['curCode'], 0, 1) . '</th>
                     <th style="font-size:18px;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">' . ' (' . $getDefCurDet['curCode'] . ')' . showOtherLangText('Grand Total') . '</th>

            </tr>
        </tbody>';
} else {
  $content .= '<tbody><tr><td colspan="2" style="padding:4px;"></td></tr><tr style="background:#7A89FF;color:white;font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
            <th style="font-size:18px;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">' . getPriceWithCur($netTotalAmt, $getDefCurDet['curCode'], 0, 1) . '</th>
                     <th style="font-size:18px;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">' . ' (' . $getDefCurDet['curCode'] . ')' . showOtherLangText('Grand Total') . '</th>

            </tr>
        </tbody>';
}
$content .= '</table>
    </td>';


$content .= '<td style="vertical-align: top; width:40%; ">
    <p style="margin: 0px; font-size:16px;line-height:19.36px;text-align:right; margin-bottom: 0;">' . showOtherLangText('Payment Method') . ':</p>';
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
  $content .= '<p style="margin: 0px; font-size:16px;line-height:19.36px;text-align:right;color:#666C85; margin-bottom: 0;">' . reverseRTLTextForPdf($payModeRow['modeName']) . '</p>';
  $content .= '<p style="margin: 0px; font-size:16px;line-height:19.36px;text-align:right;color:#666C85; margin-bottom: 0;">' . reverseRTLTextForPdf($accDet['accountName']) . '</p>';
}

$content .= '  </td></tr>
</table>

        <br>
        <br>
        <br>
      </div>
       <div  style="position:absolute;right:0;top:0;bottom:0;width:9px;border-radius:0px 10px 10px 0px;z-index:1;background-color:#596BF3;"></div>
    </div>
  </body>
</html>';


//echo $content;