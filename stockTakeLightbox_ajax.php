<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


$sql = "SELECT * FROM tbl_orders  WHERE id = '" . $_POST['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
$qry = mysqli_query($con, $sql);
$ordDet = mysqli_fetch_array($qry);

$sql = "SELECT od.*, tp.itemName, tp.barCode, IF(u.name!='',u.name,tp.unitC) unitC FROM tbl_order_details od 
			INNER JOIN tbl_products tp 
				ON(od.pId = tp.id) AND od.account_id = tp.account_id
			LEFT JOIN tbl_units u 
				ON(tp.unitC = u.id) AND tp.account_id = u.account_id
			WHERE od.ordId = '" . $_POST['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "' order by tp.catId ";

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

$content  .= '<form action="stockTake_pdf_download_history.php" target="_blank" method="get"><div class="modal-header"> 
<input type="hidden" name="getLangType" value="' . $getLangType . '" />

                        <div class="mb-modal-close-icon">  <button type="button" class="btn-close m-0 d-lg-none" data-bs-dismiss="modal" aria-label="Close" fdprocessedid="mvv0xh"></button></div>
                        <input type="hidden" name="orderId" value="' . $_POST['orderId'] . '"/>  <div class="d-flex align-items-center justify-content-end w-100">
                        <button type="submit" class="btn btn-primary dwnBtn"><span>' . showOtherLangText('Press') . '</span> <i class="fa-solid fa-download ps-1"></i></a></button>
                    </div>
                </div></form>';
$content  .= '<div class="modal-body px-2 py-3">
                    <div class="test-center">
                        <h4 class="text-center semibold">' . showOtherLangText('Stock take details
                ') . '<br></h4>
                        <h4 style="font-size: 12px; text-align:center; margin-top:-.5rem;">' . $storageDeptRow['name'] . '</h4>
                    </div>';

$content  .=  '<table class="modal-table fs-12 w-100 mt-4">
                        <thead style="background-color: white !important;">
                            <tr>';
$content  .=  '<th>' . showOtherLangText('Task No.') . '</th>';
$content  .=  '<th>' . showOtherLangText('Order By') . '</th>';
$content  .=  '<th>' . showOtherLangText('Date') . '</th>';
$content  .=  '<th>' . showOtherLangText('Total') . '</th>';
$content  .=  '</tr></thead>
                        <tbody style="background-color: rgb(122 137 255 / 20%);">';
$content  .=  '<tr>
                                <td>' . $ordDet['ordNumber'] . '</td>
                                <td>' . $orderBy['name'] . '</td>
                                <td>' . date('d-m-y h:i', strtotime($ordDet['ordDateTime'])) . '</td>
                                <td>' . $totalPosNegText . '</td>
                    </tr>';
$content  .=  '</tbody>
                    </table>';


$content  .= ' 
                    <table class="modal-table fs-12 w-100 mt-4 table__striped__bg">
                        <thead>
                            <tr>
                                <th>' . showOtherLangText('#') . '</th>
                                <th>' . showOtherLangText('Item Name') . '</th>
                                <th>' . showOtherLangText('Bar Code') . '</th>
                                <th>' . showOtherLangText('Unit') . '</th>
                                <th>' . showOtherLangText('Stock Qty') . '</th>
                                <th>' . showOtherLangText('Stock Take') . '</th>
                                <th>' . showOtherLangText('Variances') . '</th>
                                <th>' . showOtherLangText('Total') . '</th>
                            </tr>
                        </thead>';
$content  .= '<tbody>';
$i = 0;
foreach ($resultRows as $row) {
    $i++;
    $content  .= '<tr>
                                <td>' . $i . '</td>
                                <td style="font-weight:bold;">' . $row['itemName'] . '</td>
                                <td>' . $row['barCode'] . '</td>
                                <td>' . $row['unitC'] . '</td>
                                <td>' . $row['qty'] . '</td>
                                <td>' . $row['qtyReceived'] . '</td>
                                <td>' . ($row['qtyReceived'] - $row['qty']) . '</td>
                                <td>' . getPriceWithCur(($row['qtyReceived'] - $row['qty']) * $row['stockPrice'], $getDefCurDet['curCode']) . '</td>
                  </tr>';
}

$content  .= '</tbody>
                    </table>

                </div>';
echo $content;
