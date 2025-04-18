<?php
include('inc/dbConfig.php'); //connection details



$sql = "SELECT * FROM tbl_orders  WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
$qry = mysqli_query($con, $sql);
$ordDet = mysqli_fetch_array($qry);

$sql = "SELECT od.*, tp.itemName, tp.barCode, IF(u.name!='',u.name,tp.unitP) unitP FROM tbl_order_details od 
			INNER JOIN tbl_products tp 
				ON(od.pId = tp.id) AND od.account_id = tp.account_id
			LEFT JOIN tbl_units u 
				ON(tp.unitP = u.id) AND tp.account_id = u.account_id

			WHERE od.ordId = '" . $_GET['orderId'] . "' AND od.account_id = '" . $_SESSION['accountId'] . "'  ";

$proresultSet = mysqli_query($con, $sql);

$sqlSet = " SELECT GROUP_CONCAT(DISTINCT(s.name)) suppliers 
			FROM tbl_order_details d 
			INNER JOIN tbl_suppliers s
				ON(d.supplierId = s.id) AND d.account_id = s.account_id
			WHERE d.ordId IN( " . $_GET['orderId'] . " ) AND s.account_id = '" . $_SESSION['accountId'] . "'  GROUP BY d.ordId ";
$resultSet = mysqli_query($con, $sqlSet);
$supRow = mysqli_fetch_array($resultSet);
$suppliers = $supRow['suppliers'];

$sqlSet = " SELECT * FROM tbl_user WHERE id = '" . $ordDet['orderBy'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
$resultSet = mysqli_query($con, $sqlSet);
$orderBy = mysqli_fetch_array($resultSet);




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
$content .= '<body style="font-family: firefly, DejaVu Sans, sans-serif, Inter; color: #232859; font-weight: 400; font-size: 12px; line-height: 14px;">';
$content .= '<table style="width: 100%; border-collapse: collapse; margin-block-end: 32px;">
        <tr>
           <td> 
            <h4 style="font-weight: 600;line-height: 1.2; color: #232859;font-size: 23px;display:block;padding-right:26%;text-align:center;">' . showOtherLangText('Raw Convert Item Details') . '</h4>
           </td>
        </tr>
    </table>';


$content .= '<table style="width:100%; font-size:12px; margin-block-start: 24px; border-collapse: collapse;text-align:right;">
        <tr style="font-weight:bold;">
            <td style="padding: 8px 5px;">' . showOtherLangText('Total') . '</td>
            <td style="padding: 8px 5px;">' . showOtherLangText('Date') . '</td>
            <td style="padding: 8px 5px;">' . showOtherLangText('Order By') . '</td>
            <td style="padding: 8px 5px;">' . showOtherLangText('Task No.') . '</td>

           
           
            
            
        </tr>';
$content .= '<tr style="background-color: rgba(122, 137, 255, 0.2);">
            <td style="padding: 8px 5px;">' . getPriceWithCur($ordDet['ordAmt'], $getDefCurDet['curCode'], 0, 1) . '</td>
            <td style="padding: 8px 5px;">' . date('h:i d-m-y', strtotime($ordDet['ordDateTime'])) . '</td>
            <td style="padding: 8px 5px;">' . reverseRTLTextForPdf($orderBy['name']) . '</td>
            <td style="padding: 8px 5px;">' . $ordDet['ordNumber'] . '</td>
         </tr>

    </table>

    <table style="width:100%; padding-top:20px; font-size:12px; margin-block-start: 24px; border-collapse: collapse;text-align:right;">
        <tr style="font-weight:bold; background-color: rgba(122, 137, 255, 0.2);">
            <td style="padding: 8px 5px;">' . showOtherLangText('Total') . '</td>
            <td style="padding: 8px 5px;">' . showOtherLangText('Qty After') . '</td>
            <td style="padding: 8px 5px;">' . showOtherLangText('Qty Before') . '</td>
            <td style="padding: 8px 5px;">' . showOtherLangText('Converted Qty') . '</td>
            <td style="padding: 8px 5px;">' . showOtherLangText('P.Unit') . '</td>
            <td style="padding: 8px 5px;">' . showOtherLangText('Price') . '</td>
            <td style="padding: 8px 5px;">' . showOtherLangText('Bar Code') . '</td>
            <td style="padding: 8px 5px;">' . showOtherLangText('Item Name') . '</td>
            <td style="padding: 8px 5px;">#</td>            
        </tr>';
$i = 0;
while ($row = mysqli_fetch_array($proresultSet)) {
    $i++;
    $content .= '<tr>
        <td style="padding: 8px 5px;">' . getPriceWithCur($row['totalAmt'], $getDefCurDet['curCode'], 0, 1) . '</td>
        <td style="padding: 8px 5px;">' . ($i == 1 ? ($row['qty'] - $row['qtyReceived']) : ($row['qty'] + $row['qtyReceived'])) . '</td>
        <td style="padding: 8px 5px;">' . $row['qty'] . '</td>
        <td style="padding: 8px 5px;">' . $row['qtyReceived'] . '</td>
        <td style="padding: 8px 5px;">' . reverseRTLTextForPdf($row['unitP']) . '</td>
        <td style="padding: 8px 5px;">' . getPriceWithCur($row['price'], $getDefCurDet['curCode'], 0, 1) . '</td>
        <td style="padding: 8px 5px;">' . $row['barCode'] . '</td>
        <td style="padding: 8px 5px;">' . reverseRTLTextForPdf($row['itemName']) . '</td>

        <td style="padding: 8px 5px;">' . $i . '</td>
          
           
        </tr>';
}

 $content .= '</table>

   </body>
</html>';
 