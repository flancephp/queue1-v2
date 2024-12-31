<?php
include('inc/dbConfig.php'); //connection details

if (isset($_GET['orderId'])) {
  $sql = "SELECT tp.*, od.price ordPrice, IF(u.name!='',u.name,tp.unitC) countingUnit, od.qty ordQty, od.totalAmt, od.factor ordFactor FROM tbl_order_details od 
INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
LEFT JOIN tbl_units u ON(u.id = tp.unitC) AND u.account_id = tp.account_id
WHERE od.ordId = '" . $_GET['orderId'] . "'   AND tp.account_id = '" . $_SESSION['accountId'] . "' ";
  $ordQry = mysqli_query($con, $sql);
}


//show details
$cmd = "SELECT * FROM tbl_orders WHERE id='" . $_GET['orderId'] . "'   AND account_id = '" . $_SESSION['accountId'] . "' ";
$ordersQry = mysqli_query($con, $cmd);
$ordersRow = mysqli_fetch_array($ordersQry);

$qry = "SELECT * FROM tbl_req_payment WHERE orderId='" . $_GET['orderId'] . "'   AND account_id = '" . $_SESSION['accountId'] . "' order by id desc limit 1  ";
$result = mysqli_query($con, $qry);
$paymentRow = mysqli_fetch_array($result);


$sqlSet = " SELECT * FROM tbl_payment_mode WHERE id='" . $paymentRow['paymentType'] . "'   AND account_id = '" . $_SESSION['accountId'] . "' ";
$resultSet = mysqli_query($con, $sqlSet);
$payModeRow = mysqli_fetch_array($resultSet);


$qry = "SELECT * FROM tbl_requisition_payment_info WHERE  orderId='" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
$resultSet = mysqli_query($con, $qry);
$paymentInfoRow = mysqli_fetch_array($resultSet);




$content = '<!DOCTYPE html>
<html>

  <head>


      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="preconnect" href="https://fonts.googleapis.com/">
      <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
      <link href="./PDF Design_files/css2" rel="stylesheet">

      <title>' . showOtherLangText('Invoice PDF') . '</title>
      <style>
        th { text-align: right; }
        font-family: "DejaVu Sans Mono", monospace;

      </style>
  </head>
 <body style="font-family: firefly, DejaVu Sans, sans-serif, Inter; color: #232859; font-weight: 400; font-size: 12px; line-height: 14px;">
    <div style="margin: auto; max-width: 800px; position: relative; border-radius: 5px;text-align: right;">
      ';

if (isset($_GET['orderId']) && $paymentRow['paymentStatus'] == 1) {

  $content .= '<div  style="text-align: right;position:absolute;right:0;top:0;bottom:0;width:9px;border-radius:0px 10px 10px 0px;z-index:1;background-color:#42AD7F;"></div>
      <div style="padding: 1% 4%;">
        <!-- Header Section -->
      <table style="width: 100%; border-collapse: collapse;text-align: right;">
        <tr>
          <td><p style="font-size:40px;font-weight:700;line-height:40px;text-align: right;color:#42AD7F !important; margin-bottom: 0;text-transform: uppercase;">' . showOtherLangText('RECEIVED') . '</p>';
} elseif ($_GET['paymentStatus'] == 2) {
  $content .= '<div  style="text-align: right;position:absolute;right:0;top:0;bottom:0;width:9px;border-radius:0px 10px 10px 0px;z-index:1;background-color:#42AD7F;"></div>
      <div style="padding: 1% 4%;">
        <!-- Header Section -->
      <table style="width: 100%; border-collapse: collapse;text-align: right;">
        <tr>
          <td><p style="font-size:40px;font-weight:700;line-height:40px;text-align: right;color:#42AD7F !important; margin-bottom: 0;text-transform: uppercase;">' . showOtherLangText('REFUNDED') . '</p>';
} else {
  $content .= '<div  style="position:absolute;right:0;top:0;bottom:0;width:9px;border-radius:0px 10px 10px 0px;z-index:1;background-color:#E99624;"></div>
      <div style="padding: 1% 4%;">
        <!-- Header Section -->
      <table style="width: 100%; border-collapse: collapse;text-align: right;">
        <tr>
          <td><p style="font-size:40px;font-weight:700;line-height:40px;text-align: right;color:#E99624 !important; margin-bottom: 0;text-transform: uppercase;">' . showOtherLangText('PENDING') . '</p>';
}
$content .= '<p style="font-size:40px;font-weight:700;line-height:40px;text-align: right;color:#666C85;margin-top: 0rem;text-transform: uppercase;">' . showOtherLangText('INVOICE') . '</p>';
$content .= '</td>
          <td style="text-align: right;">';
$clientQry = " SELECT * FROM tbl_client WHERE id = '" . $_SESSION['accountId'] . "' ";
$clientResult = mysqli_query($con, $clientQry);
$clientResultRow = mysqli_fetch_array($clientResult);

if ($clientResultRow['logo'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/clientLogo/" . $clientResultRow['logo'])) {

  $content .= '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/clientLogo/' . $clientResultRow['logo'] . '" width="100" style="object-fit: contain;">';
}
$content .= '</td>
        </tr>
      </table>

<!-- Supplier Invoice and Payment Info -->
<table style="width: 100%; margin-top: 10px; border-collapse: collapse;">
  <tr>';

$sql = " SELECT * FROM tbl_country WHERE id = '" . $clientResultRow['country'] . "' ";
$resSet = mysqli_query($con, $sql);
$resultRow = mysqli_fetch_array($resSet);
$content .= '<td>
      <table style=" text-align: left; width: 100%; border-collapse: collapse;">
        <tbody>
          <tr>
            <td style="padding:0;font-size:16px;line-height:19px;color:#666C85 !important;">' . reverseRTLTextForPdf($clientResultRow['accountName']) . '</td>
          </tr>
          <tr>
            <td style="padding:0;font-size:16px;line-height:19px;color:#666C85 !important;">' . reverseRTLTextForPdf($clientResultRow['address_one']) . ',' . reverseRTLTextForPdf($clientResultRow['address_two']) . '</td>
          </tr>
          <tr>
            <td style="padding:0;font-size:16px;line-height:19px;color:#666C85 !important;">' . reverseRTLTextForPdf($clientResultRow['city']) . ',' . reverseRTLTextForPdf($resultRow['name']) . '</td>
          </tr>
          <tr>
            <td style="padding:0;font-size:16px;line-height:19px;color:#666C85 !important;">' . reverseRTLTextForPdf($clientResultRow['email']) . '</td>
          </tr>
          <tr>
            <td style="padding:0;font-size:16px;line-height:19px;color:#666C85 !important;">' . ($clientResultRow['phone']) . '</td>
          </tr>
        </tbody>
      </table>
    </td>';




$content .= ' <td style="width: 50%;" style="vertical-align:top;">
    <table style="width: 100%; border-collapse: collapse;text-align: right;">
      <tbody>
        <tr>
                    <td style="padding:0;height:30px;font-size:16px;line-height:16px;color:#666C85 !important;">' . getinvoiceNumber($paymentInfoRow['invoiceNumber']) . '</td>

          <td style="padding:0;height:30px;font-size:16px;line-height:16px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;"># ' . showOtherLangText('Invoice') . ' </td>
        </tr>
        <tr>
                    <td style="padding:0;height:30px;font-size:16px;line-height:16px;color:#666C85 !important;">' . $ordersRow['ordNumber'] . '</td>

          <td style="padding:0;height:30px;font-size:16px;line-height:16px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;"># ' . showOtherLangText('Task') . ' </td>
        </tr>
        <tr>';

$content .= '<td style="padding:0;height:30px;font-size:16px;line-height:16px;color:#666C85 !important;">';
if ($paymentRow['paymentStatus'] == 1) {

  $content .= date('h:i:s Y-m-d', strtotime($paymentRow['paymentDateTime']));
} else {

  $content .=  date('h:i:s Y-m-d', strtotime($ordersRow['ordDateTime']));
}
$content .= '</td>';
$content .= '<td style="padding:0;height:30px;font-size:16px;line-height:16px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;"># ' . showOtherLangText('Date') . '</td>
</tr>         
      </tbody>
    </table>
  </td>';



$content .= '</tr>
</table>


        <div style="padding-right:1rem;">
          <p style="font-size:16px;line-height:19.36px;text-align: right; margin-bottom: .5rem;">:' . showOtherLangText('Invoice To') . '</p>
          <p style="margin: 0; font-size:16px;line-height:19.36px;text-align: right;color:#666C85; margin-bottom: 0;">' . reverseRTLTextForPdf($paymentInfoRow['invoiceName']) . '</p>
          <p style="margin: 0; font-size:16px;line-height:19.36px;text-align: right;color:#666C85; margin-bottom: 0;">' . reverseRTLTextForPdf($paymentInfoRow['invoiceAddress']) . '</p>
          <p style="margin: 0; font-size:16px;line-height:19.36px;text-align: right;color:#666C85; margin-bottom: 0;">' . reverseRTLTextForPdf($paymentInfoRow['invoiceEmail']) . '</p>
          <p style="margin: 0; font-size:16px;line-height:19.36px;text-align: right;color:#666C85; margin-bottom: 0;">' . $paymentInfoRow['invoicePhone'] . '</p>
        </div>

        <table style="width: 100%; margin-top: 16px; border-collapse: collapse;">
          <thead style="border-radius:10px !important; ">
            <tr style="max-height:38px;background: #A9B0C0;">
                <th style="color:white;background: #7A89FF !important; font-weight: 700; font-size: 12px;  font-weight: 700; background:#7A89FF;padding:8px 8px;text-align: left;border-top-right-radius:10px;border-bottom-right-radius:10px;">' . $getDefCurDet['curCode'] . ' ' . showOtherLangText('Total')  . '</th>
                <th style="color:white;background: #7A89FF !important; font-weight: 700; font-size: 12px;  background:#7A89FF;padding:8px 8px 10px 16px;text-align: left;">' . $getDefCurDet['curCode'] . ' ' . showOtherLangText('Price') .  '</th>
                <th style="color:white;font-weight: 700; font-size: 12px;  padding: 8px 8px;text-align: left;">' . showOtherLangText('Quantity') . '</th>
                <th style="color:white;font-weight: 700; font-size: 12px;  padding: 8px 8px;text-align: left;">' . showOtherLangText('Unit') . '</th>
                <th style="color:white;font-weight: 700; font-size: 12px;  padding: 8px 8px;width: 30%;text-align: left;">' . showOtherLangText('Item') . '</th>


                <th style="color:white;font-weight: 700; font-size: 12px;  padding: 8px 8px;border-top-left-radius:10px;border-bottom-left-radius:10px;">#</th>
            </tr>
          </thead>';
$content .= '<tbody>';
$sql = "SELECT cif.itemName, cif.unit,cif.amt, tod.* FROM tbl_order_details tod 
                                INNER JOIN tbl_custom_items_fee cif ON(tod.customChargeId = cif.id) AND tod.account_id = cif.account_id
                                WHERE tod.ordId = '" . $_GET['orderId'] . "'  AND tod.account_id = '" . $_SESSION['accountId'] . "'  and tod.customChargeType=1 ORDER BY cif.itemName ";
$resultSet = mysqli_query($con, $sql);
$x = 0;
while ($showCif = mysqli_fetch_array($resultSet)) {
  $x++;
  $content .= '<tr style="height:48px;">
                    <td style="font-size: 12px; font-weight: 700; padding:8px;border-bottom: 1px solid #DFE0E8;">' . getPriceWithCur($showCif['amt'], $getDefCurDet['curCode'], 0, 1) . '</td>
                    <td style="font-size: 12px; padding: 8px 8px 8px 16px;border-bottom: 1px solid #DFE0E8;">' . getPriceWithCur($showCif['amt'], $getDefCurDet['curCode'], 0, 1) . '</td>
                    <td style="font-size: 12px; padding: 8px;border-bottom: 1px solid #DFE0E8;">1</td>
                    <td style="font-size: 12px; padding: 8px;border-bottom: 1px solid #DFE0E8;">' . reverseRTLTextForPdf($showCif['unit']) . '</td>
                    <td style="font-size: 12px; padding: 8px; width: 30%;border-bottom: 1px solid #DFE0E8;">' . reverseRTLTextForPdf($showCif['itemName']) . '</td>


                    <td style="font-size: 12px; padding: 8px;text-align:center;border-bottom: 1px solid #DFE0E8;">' . $x . '</td>
               </tr>';
}

$sum = 0;
while ($row = mysqli_fetch_array($ordQry)) {

  $x++;
  $totalPrice = round($row['price'], 2);
  $Quantity = $row['ordQty'];
  $bill_total = ($totalPrice * $Quantity);
  $sum = $sum + $bill_total;
  $orderId = $_GET['orderId'];
  $content .= '<tr style="height:48px;">
                <td style=" font-size: 12px; font-weight: 700; padding: 8px;border-bottom: 1px solid #DFE0E8;">' . getPriceWithCur($row['ordQty'] * $row['ordPrice'], $getDefCurDet['curCode'], 0, 1) . '</td>
                <td style=" font-size: 12px; padding: 8px 8px 8px 16px;border-bottom: 1px solid #DFE0E8;">' . getPriceWithCur($row['ordPrice'], $getDefCurDet['curCode'], 0, 1) . '</td>
                <td style=" font-size: 12px; padding: 8px;border-bottom: 1px solid #DFE0E8;">' . $row['ordQty'] . '</td>
                <td style=" font-size: 12px; padding: 8px;border-bottom: 1px solid #DFE0E8;">' . reverseRTLTextForPdf($row['countingUnit']) . '</td>
                <td style=" font-size: 12px; padding: 8px;width: 30%;border-bottom: 1px solid #DFE0E8;">' . reverseRTLTextForPdf($row['itemName']) . '</td>
                <td style=" font-size: 12px; padding: 8px;text-align:center;border-bottom: 1px solid #DFE0E8;">' . $x . '</td>
            </tr>';
}
$content .= '</tbody>
        </table>

        <div  style="width:100%;height:3px;background:#7A89FF;margin:8px 0;margin-top: 20px;"></div>

        <br>
  <table style=" width:100%; border-collapse: collapse;">
  <tr>
    
    <td style="vertical-align: top; width:40%;">
      <table style="margin-top:-1rem;min-width:350px;height: 38px; border-collapse: collapse;width:100%;">
        <tbody>';
$sqlSet = "SELECT SUM(totalAmt) as sum1 from tbl_order_details where ordId='" . $_GET['orderId'] . "'   AND account_id = '" . $_SESSION['accountId'] . "' AND (customChargeType='1' OR customChargeType='0')";
$resultSet = mysqli_query($con, $sqlSet);
$chargeRow = mysqli_fetch_array($resultSet);
$chargePrice = $chargeRow['sum1'];

$ordCount = "SELECT * from tbl_order_details where ordId='" . $_GET['orderId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' AND customChargeType='2' ";
$ordCountResult = mysqli_query($con, $ordCount);
$ordCountRow = mysqli_num_rows($ordCountResult);

if ($ordCountRow > 0) {
  $content .= '<tr style="color:#666C85; font-size:18px;line-height:21.78px;text-align: right;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
            <th style="font-size:16px; color: #232859;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">' . getPriceWithCur($chargePrice, $getDefCurDet['curCode'], 0, 1) . '</th>
                      <th style="font-size:16px;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;width:70%">' . showOtherLangText('Sub Total') . '</th>

            </tr>';
}
$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
                            INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                            WHERE od.ordId = '" . $_GET['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

$ordQry = mysqli_query($con, $sql);

$fixedCharges = 0;

$sql = " SELECT SUM(od.totalAmt) AS totalFixedCharges, tp.feeName FROM tbl_order_details od 
                            INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                            WHERE od.ordId = '" . $_GET['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName   ";
$sumQry = mysqli_query($con, $sql);
$totalSum = mysqli_fetch_array($sumQry);

$totalFixedCharges = $totalSum['totalFixedCharges'];


while ($row = mysqli_fetch_array($ordQry)) //show here order level charges
{
  $fixedCharges = $row['price'];

  $content .= '<tr style="color:#666C85; font-size:18px;line-height:21.78px;text-align: right;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
                <th style="font-size:16px; color: #232859;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">' . getPriceWithCur($fixedCharges, $getDefCurDet['curCode'], 0, 1) . '</th>
       
    <th style="font-size:16px;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">' . reverseRTLTextForPdf($row['feeName']) . '</th>
          </tr>';
}

$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
          INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
          WHERE od.ordId = '" . $_GET['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
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
    $content .= '<tr style="color:#666C85; font-size:18px;line-height:21.78px;text-align: right;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
                    <th style="font-size:16px; color: #232859;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;text-align: right;">' . getPriceWithCur($discountPercent, $getDefCurDet['curCode'], 0, 1) . '</th>
   
        <th style="font-size:16px;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;text-align: right;">% ' .  $row['totalAmt'] . reverseRTLTextForPdf($row['feeName']) .  ' </th>
          </tr>';
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
  $totalTaxCharges += (($chargePrice + $totalFixedCharges + $totalDiscountPercent) * $tax / 100);
  $content .= '<tr style="color:#666C85; font-size:18px;line-height:21.78px;text-align: right;height:38px;border:none !important;font-weight: bold; max-height: 38px;text-align: right;">
                <th style="font-size:16px; color: #232859;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;text-align: right;">' . getPriceWithCur($taxCharges, $getDefCurDet['curCode'], 0, 1) . '</th>
       
    <th style="font-size:16px;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;text-align: right;">% ' . $row['price'] . ' ' . reverseRTLTextForPdf($row['feeName']) .  '</th>
          </tr>';
} //Ends order lelvel tax discount charges

$sqlSet = "SELECT SuM(totalAmt) AS totalFixedDiscount FROM tbl_order_details od 
            INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
            WHERE od.ordId = '" . $_GET['orderId'] . "'   AND od.account_id = '" . $_SESSION['accountId'] . "' and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";
$resultSet = mysqli_query($con, $sqlSet);
$fixedDiscountRow = mysqli_fetch_array($resultSet);
$totalFixedDiscount = $fixedDiscountRow['totalFixedDiscount'];

$netTotalAmt = ($chargePrice + $totalTaxCharges + $totalDiscountPercent + $totalFixedDiscount);
$content .= '</tbody><tbody>';

$content .= '<tr><td colspan="2" style="padding:4px;"></td></tr><tr style="text-align: right;height:38px;border:none !important;font-weight: bold; max-height: 38px;margin-top:10px;">
            <th style="background:#7A89FF;color:white;font-size:18px;line-height:21.78px;padding:6px 12px !important;border-radius:0 10px 10px 0 !important;text-align: right;">' . getPriceWithCur($netTotalAmt, $getDefCurDet['curCode'], 0, 1) . '</th>
            
<th style="background:#7A89FF;color:white;font-size:18px;line-height:21.78px;padding:6px 12px !important;border-radius:10px 0 0 10px !important;text-align: right;">' . showOtherLangText('Grand Total') . '</th>
          </tr>';

$content .=  '</tbody>
      </table>
    </td>

    <td style="vertical-align: top; width:60%; ">
      <p style="margin: 0px; font-size:16px;line-height:19.36px;text-align: right; margin-bottom: 0;">:' . showOtherLangText('Payment Method') . '</p>';
$sqlSet = " SELECT * FROM  tbl_accounts WHERE id='" . $paymentRow['bankAccountId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
$resultSet = mysqli_query($con, $sqlSet);
$accDet = mysqli_fetch_array($resultSet);
$content .= '<p style="margin: 0px; font-size:16px;line-height:19.36px;text-align: right;color:#666C85; margin-bottom: 0;">' . reverseRTLTextForPdf($payModeRow['modeName']) . '</p>';
$content .= '<p style="margin: 0px; font-size:16px;line-height:19.36px;text-align: right;color:#666C85; margin-bottom: 0;">' . reverseRTLTextForPdf($accDet['accountName']) . '</p>';
$content .= '</td>


  </tr>
</table>

      </div>
    </div>
  </body>
</html>';


//$content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');
