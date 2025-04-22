<?php
include('inc/dbConfig.php'); //connection details


//Get language Type 
//$getLangType = getLangType($_SESSION['language_id']);


$sql = "SELECT * FROM tbl_orders  WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
$qry = mysqli_query($con, $sql);
$ordDet = mysqli_fetch_array($qry);

$sql = "SELECT od.*, tp.itemName, tp.barCode, IF(u.name!='',u.name,tp.unitC) unitC FROM tbl_order_details od 
            INNER JOIN tbl_products tp 
                ON(od.pId = tp.id) AND od.account_id = tp.account_id
            LEFT JOIN tbl_units u 
                ON(tp.unitC = u.id) AND tp.account_id = u.account_id
            WHERE od.ordId = '" . $_GET['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "' order by tp.catId ";

$proresultSet = mysqli_query($con, $sql);

$sumTotal = 0;
$posSumTotal = 0;
$negSumTotal = 0;
$resultRows = [];
while ($row = mysqli_fetch_array($proresultSet)) {
    $resultRows[] = $row;
    $sumTotal = ($row['qtyReceived'] - $row['qty']) * $row['stockPrice'];

    if ($sumTotal > 0) {
        $posSumTotal += $sumTotal;
    } elseif ($sumTotal < 0) {
        $negSumTotal += $sumTotal;
    }
}

$totalPosNegText = '<span style="color:black">' .
    '<span style="color:red">' . getPriceWithCur($negSumTotal, $getDefCurDet['curCode'], 0, 1) . '&nbsp;</span>' . getPriceWithCur($posSumTotal, $getDefCurDet['curCode'], 0, 1) . '&nbsp;</span>';


$sqlSet = " SELECT * FROM tbl_user WHERE id = '" . $ordDet['orderBy'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
$resultSet = mysqli_query($con, $sqlSet);
$orderBy = mysqli_fetch_array($resultSet);

$storageDeptRow = getStoreDetailsById($ordDet['storeId']);




$content = '
<!DOCTYPE html>
<html lang="he">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>' . showOtherLangText('Order Details PDF') . '</title>
  <link rel="preconnect" href="https://fonts.googleapis.com/">
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
  <link href="./PDF Design_files/css2" rel="stylesheet">
  <style>
        @page { margin: 10px 10px; }
        th {
            text-align:left;
        }
    </style>
</head>';

$content .= '<body style="' . ($_GET['getLangType'] == '1'
    ? 'font-family: firefly, DejaVu Sans, sans-serif, Inter; color: #232859; font-weight: 400; font-size: 12px; line-height: 14px;'
    : 'font-family: \'Inter\', sans-serif; color: #232859; font-weight: 400; font-size: 12px; line-height: 14px;') . '">';


$content .= '<table style="width: 100%; border-collapse: collapse; margin-block-end: 20px;">
    <tr>
      <td>
    

        <h4
          style="font-weight: 600;text-align: center; line-height: 1.2; color: #232859; font-size: 22px; margin-bottom: 0;padding-right:16%;">' . showOtherLangText('Stock take details') . '</h4> 
        <h4
          style="font-weight: 600;text-align: center; line-height: 1.2; color: #232859; font-size: 12px; margin-top: 0;margin-left:-12%;">
          ' . reverseRTLTextForPdf($storageDeptRow['name']) . '
        </h4>
        
      </td>
    </tr>
  </table>';


$content .=  '<table style="width:100%; font-size:12px; margin-block-start: 20px; border-collapse: collapse; text-align:right;">
    <tr style="font-weight:bold;">
     <td style="padding: 8px 5px;">' . showOtherLangText('Total') . '</td>
      <td style="padding: 8px 5px;">' . showOtherLangText('Date') . '</td>
      <td style="padding: 8px 5px;">' . showOtherLangText('Order By') . '</td>
      <td style="padding: 8px 5px;">' . showOtherLangText('Task No.') . '</td>
      
     
     
    </tr>';
$content .=  '<tr style="background-color: rgba(122, 137, 255, 0.2);">
<td style="padding: 8px 5px;">' . $totalPosNegText . '</td>
<td style="padding: 8px 5px;">' . date('h:i d-m-y', strtotime($ordDet['ordDateTime'])) . '</td>
 <td style="padding: 8px 5px;">' . reverseRTLTextForPdf($orderBy['name']) . '</td>
<td style="padding: 8px 5px;">' . $ordDet['ordNumber'] . '</td>
     
      
      
        </tr>';
$content .= '</table>';

$content .= '<table style="width:100%; font-size:12px;padding-top:20px; margin-block-start: 24px; border-collapse: collapse;text-align:right;">
    <tr style="font-weight:bold; background-color: rgba(122, 137, 255, 0.2);">

        <td style="padding: 8px 5px;">' . showOtherLangText('Total') . '</td>
        <td style="padding: 8px 5px;">' . showOtherLangText('Variances') . '</td>
        <td style="padding: 8px 5px;">' . showOtherLangText('Stock Take') . '</td>
        <td style="padding: 8px 5px;">' . showOtherLangText('Stock Qty') . '</td>
        <td style="padding: 8px 5px;">' . showOtherLangText('Unit') . '</td>
        <td style="padding: 8px 5px;">' . showOtherLangText('Bar Code') . '</td>
        <td style="padding: 8px 5px;">' . showOtherLangText('Item Name') . '</td>
        <td style="padding: 8px 5px;">' . showOtherLangText('#') . '</td>
     
    </tr>';
$i = 0;
foreach ($resultRows as $row) {
    $i++;
    $bgColor = ($i % 2 != 0) ? 'white' : '#F9F9FB';
    $varAmt = ($row['qtyReceived'] - $row['qty']);
    $varAmt = $varAmt < 0 ? (str_replace('-', '', $varAmt) . '-') : $varAmt;

    $content .= '
    <tr style="background-color: ' . $bgColor . ';">
          <td style="padding: 8px 5px;border-bottom:1px solid #DFE0E8;">' . getPriceWithCur(($row['qtyReceived'] - $row['qty']) * $row['stockPrice'], $getDefCurDet['curCode'], 0, 1) . '</td>

          <td style="padding: 8px 5px;border-bottom:1px solid #DFE0E8;">
            ' . $varAmt . '</td>
        <td style="padding: 8px 5px;border-bottom:1px solid #DFE0E8;">' . $row['qtyReceived'] . '</td>
        <td style="padding: 8px 5px;border-bottom:1px solid #DFE0E8;">' . $row['qty'] . '</td>
        <td style="padding: 8px 5px;border-bottom:1px solid #DFE0E8;">' . reverseRTLTextForPdf($row['unitC']) . '</td>
        <td style="padding: 8px 5px;border-bottom:1px solid #DFE0E8;">' . $row['barCode'] . '</td>
        <td style="padding: 8px 5px;border-bottom:1px solid #DFE0E8;font-weight:700;">' . reverseRTLTextForPdf($row['itemName']) . '</td>
        <td style="padding: 8px 5px;border-bottom:1px solid #DFE0E8;">' . $i . '</td>
      
    </tr>';
}



$content .= '</table>

      </body>

      </html>';
