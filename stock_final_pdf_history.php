<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


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

$totalPosNegText = '<span style="color:black">' . getPriceWithCur($posSumTotal, $getDefCurDet['curCode']) . '&nbsp;</span>' .
  '<span style="color:red">' . getPriceWithCur($negSumTotal, $getDefCurDet['curCode']) . '&nbsp;</span>';


$sqlSet = " SELECT * FROM tbl_user WHERE id = '" . $ordDet['orderBy'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
$resultSet = mysqli_query($con, $sqlSet);
$orderBy = mysqli_fetch_array($resultSet);

$storageDeptRow = getStoreDetailsById($ordDet['storeId']);




$content = '
<!DOCTYPE html>
<html lang="en">

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
</head>

<body style="font-family: \'Inter\', sans-serif;color: #232859; font-weight: 400;font-size: 12px; line-height: 14px;">
  <table style="width: 100%; border-collapse: collapse; margin-block-end: 20px;">
    <tr>
      <td>
        <h4
          style="font-weight: 600;text-align: center; line-height: 1.2; color: #232859; font-size: 22px; margin-bottom: 0;">';
$content .= showOtherLangText('Stock take details');
$content .=  '</h4>
        <h4
          style="font-weight: 600;text-align: center; line-height: 1.2; color: #232859; font-size: 12px; margin-top: 0;">
          ' . $storageDeptRow['name'] . '
        </h4>
      </td>
    </tr>
  </table>';


$content .=  '<table style="width:100%; font-size:12px; margin-block-start: 20px; border-collapse: collapse;">
    <tr style="font-weight:bold;">
      <td style="padding: 8px 5px;">' . showOtherLangText('Task No.') . '</td>
      <td style="padding: 8px 5px;">' . showOtherLangText('Order By') . '</td>
      <td style="padding: 8px 5px;">' . showOtherLangText('Date') . '</td>
      <td style="padding: 8px 5px;">' . showOtherLangText('Total') . '</td>
    </tr>';
$content .=  '<tr style="background-color: rgba(122, 137, 255, 0.2);">
      <td style="padding: 8px 5px;">' . $ordDet['ordNumber'] . '</td>
      <td style="padding: 8px 5px;">' . $orderBy['name'] . '</td>
      <td style="padding: 8px 5px;">' . date('d-m-y h:i', strtotime($ordDet['ordDateTime'])) . '</td>
      <td style="padding: 8px 5px;">' . $totalPosNegText . '</td>
        </tr>';
$content .= '</table>';

$content .= '<table style="width:100%; font-size:12px;padding-top:20px; margin-block-start: 24px; border-collapse: collapse;">
    <tr style="font-weight:bold; background-color: rgba(122, 137, 255, 0.2);">
      <td style="padding: 8px 5px;">' . showOtherLangText('#') . '</td>
      <td style="padding: 8px 5px;">' . showOtherLangText('Item Name') . '</td>
      <td style="padding: 8px 5px;">' . showOtherLangText('Bar Code') . '</td>
      <td style="padding: 8px 5px;">' . showOtherLangText('Unit') . '</td>
      <td style="padding: 8px 5px;">' . showOtherLangText('Stock Qty') . '</td>
      <td style="padding: 8px 5px;">' . showOtherLangText('Stock Take') . '</td>
      <td style="padding: 8px 5px;">' . showOtherLangText('Variances') . '</td>
      <td style="padding: 8px 5px;">' . showOtherLangText('Total') . '</td>
    </tr>';
$i = 0;
foreach ($resultRows as $row) {
  $i++;
  $content .= '<tr>
      <td style="padding: 8px 5px;">' . $i . '</td>
      <td style="padding: 8px 5px;">' . $row['itemName'] . '</td>
      <td style="padding: 8px 5px;">' . $row['barCode'] . '</td>
      <td style="padding: 8px 5px;">' . $row['unitC'] . '</td>
      <td style="padding: 8px 5px;">' . $row['qty'] . '</td>
      <td style="padding: 8px 5px;">' . $row['qtyReceived'] . '</td>
      <td style="padding: 8px 5px;">
            ' . ($row['qtyReceived'] - $row['qty']) . '</td>
      <td style="padding: 8px 5px;">' . getPriceWithCur(($row['qtyReceived'] - $row['qty']) * $row['stockPrice'], $getDefCurDet['curCode']) . '</td>
      </tr>';
}



$content .= '</table>

      </body>

      </html>';
