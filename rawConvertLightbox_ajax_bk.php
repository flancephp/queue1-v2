<<<<<<< HEAD
<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


$sql = "SELECT * FROM tbl_orders  WHERE id = '" . $_POST['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
$qry = mysqli_query($con, $sql);
$ordDet = mysqli_fetch_array($qry);

$sql = "SELECT od.*, tp.itemName, tp.barCode, IF(u.name!='',u.name,tp.unitP) unitP FROM tbl_order_details od 
			INNER JOIN tbl_products tp 
				ON(od.pId = tp.id) AND od.account_id = tp.account_id
			LEFT JOIN tbl_units u 
				ON(tp.unitP = u.id) AND tp.account_id = u.account_id

			WHERE od.ordId = '" . $_POST['orderId'] . "' AND od.account_id = '" . $_SESSION['accountId'] . "'  ";

$proresultSet = mysqli_query($con, $sql);

$sqlSet = " SELECT GROUP_CONCAT(DISTINCT(s.name)) suppliers 
			FROM tbl_order_details d 
			INNER JOIN tbl_suppliers s
				ON(d.supplierId = s.id) AND d.account_id = s.account_id
			WHERE d.ordId IN( " . $_POST['orderId'] . " ) AND s.account_id = '" . $_SESSION['accountId'] . "'  GROUP BY d.ordId ";
$resultSet = mysqli_query($con, $sqlSet);
$supRow = mysqli_fetch_array($resultSet);
$suppliers = $supRow['suppliers'];

$sqlSet = " SELECT * FROM tbl_user WHERE id = '" . $ordDet['orderBy'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
$resultSet = mysqli_query($con, $sqlSet);
$orderBy = mysqli_fetch_array($resultSet);

$content  .= '<form action="rawConvert_pdf_download.php" target="_blank" method="get"><div class="modal-header">   <div class="mb-modal-close-icon">  <button type="button" class="btn-close m-0 d-lg-none" data-bs-dismiss="modal" aria-label="Close" fdprocessedid="mvv0xh"></button></div> ';
$content  .= '<input type="hidden" name="orderId" value="' . $_POST['orderId'] . '"/>
                <input type="hidden" name="getLangType" value="' . $getLangType . '" />';

$content  .= '<div class="d-flex align-items-center justify-content-end w-100">
                       <button type="submit" class="btn btn-primary dwnBtn"><span>' . showOtherLangText('Press') . '</span> <i class="fa-solid fa-download ps-1"></i></button>
                       </div>
                </div>
                <div class="modal-body px-2 py-3">
                    <div class="test-center">
                        <h4 class="text-center semibold headerTxt">' . showOtherLangText('Raw Convert Item Details') . '</h4>
                    </div><div class="table-responsiv">';

$content  .=  '<table class="modal-table fs-12 w-100 mt-2 mt-lg-4">
                        <thead style="background-color: white !important;">
                            <tr>
                                <th>' . showOtherLangText('Task No.') . '</th>
                                <th>' . showOtherLangText('Order By') . '</th>
                                <th>' . showOtherLangText('Date') . '</th>
                                <th>' . showOtherLangText('Total') . '</th>
                            </tr>
                        </thead>';
$content  .=  '<tbody style="background-color: rgb(122 137 255 / 20%);">';
$content  .=  '<tr>
                                <td>' . $ordDet['ordNumber'] . '</td>
                                <td>' . $orderBy['name'] . '</td>
                                <td>' . date('d-m-y h:i', strtotime($ordDet['ordDateTime'])) . '</td>
                                <td>' . getPriceWithCur($ordDet['ordAmt'], $getDefCurDet['curCode']) . '</td>
                            </tr>';
$content  .= '</tbody>
                    </table> 
                    <table class="modal-table fs-12 w-100 mt-lg-4">
                        <thead>
                            <tr>
                                <th>' . showOtherLangText('#') . '</th>
                                <th>' . showOtherLangText('Item Name') . '</th>
                                <th>' . showOtherLangText('Bar Code') . '</th>
                                <th>' . showOtherLangText('Price') . '</th>
                                <th>' . showOtherLangText('P.Unit') . '</th>
                                <th>' . showOtherLangText('Converted Qty') . '</th>
                                <th>' . showOtherLangText('Qty Before') . '</th>
                                <th>' . showOtherLangText('Qty After') . '</th>
                                <th>' . showOtherLangText('Total') . '</th>
                            </tr>
                        </thead>
                        <tbody>';
$i = 0;
while ($row = mysqli_fetch_array($proresultSet)) {
    $i++;
    $content  .= '<tr>
                                <td>' . $i . '</td>
                                <td>' . $row['itemName'] . '</td>
                                <td>' . $row['barCode'] . '</td>
                                <td><span class="itmProd">' . getPriceWithCur($row['price'], $getDefCurDet['curCode']) . '</span></td>
                                <td><span class="itmProd">' . $row['unitP'] . '</span></td>
                                <td><span class="itmProd">' . $row['qtyReceived'] . '</span></td>
                                <td><span class="itmProd">' . $row['qty'] . '</span></td>
                                <td><span class="itmProd">' . ($i == 1 ? ($row['qty'] - $row['qtyReceived']) : ($row['qty'] + $row['qtyReceived'])) . '</span></td>
                                <td><span class="itmProd">' . getPriceWithCur($row['totalAmt'], $getDefCurDet['curCode']) . '</span></td>
                            </tr>';
}
$content  .= '</tbody>
                    </table>

                </div> </div>';
echo $content;
=======
<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


$sql = "SELECT * FROM tbl_orders  WHERE id = '" . $_POST['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
$qry = mysqli_query($con, $sql);
$ordDet = mysqli_fetch_array($qry);

$sql = "SELECT od.*, tp.itemName, tp.barCode, IF(u.name!='',u.name,tp.unitP) unitP FROM tbl_order_details od 
			INNER JOIN tbl_products tp 
				ON(od.pId = tp.id) AND od.account_id = tp.account_id
			LEFT JOIN tbl_units u 
				ON(tp.unitP = u.id) AND tp.account_id = u.account_id

			WHERE od.ordId = '" . $_POST['orderId'] . "' AND od.account_id = '" . $_SESSION['accountId'] . "'  ";

$proresultSet = mysqli_query($con, $sql);

$sqlSet = " SELECT GROUP_CONCAT(DISTINCT(s.name)) suppliers 
			FROM tbl_order_details d 
			INNER JOIN tbl_suppliers s
				ON(d.supplierId = s.id) AND d.account_id = s.account_id
			WHERE d.ordId IN( " . $_POST['orderId'] . " ) AND s.account_id = '" . $_SESSION['accountId'] . "'  GROUP BY d.ordId ";
$resultSet = mysqli_query($con, $sqlSet);
$supRow = mysqli_fetch_array($resultSet);
$suppliers = $supRow['suppliers'];

$sqlSet = " SELECT * FROM tbl_user WHERE id = '" . $ordDet['orderBy'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
$resultSet = mysqli_query($con, $sqlSet);
$orderBy = mysqli_fetch_array($resultSet);

$content  .= '<form action="rawConvert_pdf_download.php" target="_blank" method="get"><div class="modal-header">   <div class="mb-modal-close-icon">  <button type="button" class="btn-close m-0 d-lg-none" data-bs-dismiss="modal" aria-label="Close" fdprocessedid="mvv0xh"></button></div> ';
$content  .= '<input type="hidden" name="orderId" value="' . $_POST['orderId'] . '"/>
                <input type="hidden" name="getLangType" value="' . $getLangType . '" />';

$content  .= '<div class="d-flex align-items-center justify-content-end w-100">
                       <button type="submit" class="btn btn-primary dwnBtn"><span>' . showOtherLangText('Press') . '</span> <i class="fa-solid fa-download ps-1"></i></button>
                       </div>
                </div>
                <div class="modal-body px-2 py-3">
                    <div class="test-center">
                        <h4 class="text-center semibold headerTxt">' . showOtherLangText('Raw Convert Item Details') . '</h4>
                    </div><div class="table-responsiv">';

$content  .=  '<table class="modal-table fs-12 w-100 mt-2 mt-lg-4">
                        <thead style="background-color: white !important;">
                            <tr>
                                <th>' . showOtherLangText('Task No.') . '</th>
                                <th>' . showOtherLangText('Order By') . '</th>
                                <th>' . showOtherLangText('Date') . '</th>
                                <th>' . showOtherLangText('Total') . '</th>
                            </tr>
                        </thead>';
$content  .=  '<tbody style="background-color: rgb(122 137 255 / 20%);">';
$content  .=  '<tr>
                                <td>' . $ordDet['ordNumber'] . '</td>
                                <td>' . $orderBy['name'] . '</td>
                                <td>' . date('d-m-y h:i', strtotime($ordDet['ordDateTime'])) . '</td>
                                <td>' . getPriceWithCur($ordDet['ordAmt'], $getDefCurDet['curCode']) . '</td>
                            </tr>';
$content  .= '</tbody>
                    </table> 
                    <table class="modal-table fs-12 w-100 mt-3 mt-lg-4">
                        <thead>
                            <tr>
                                <th>' . showOtherLangText('#') . '</th>
                                <th>' . showOtherLangText('Item Name') . '</th>
                                <th>' . showOtherLangText('Bar Code') . '</th>
                                <th>' . showOtherLangText('Price') . '</th>
                                <th>' . showOtherLangText('P.Unit') . '</th>
                                <th>' . showOtherLangText('Converted Qty') . '</th>
                                <th>' . showOtherLangText('Qty Before') . '</th>
                                <th>' . showOtherLangText('Qty After') . '</th>
                                <th>' . showOtherLangText('Total') . '</th>
                            </tr>
                        </thead>
                        <tbody>';
$i = 0;
while ($row = mysqli_fetch_array($proresultSet)) {
    $i++;
    $content  .= '<tr>
                                <td>' . $i . '</td>
                                <td>' . $row['itemName'] . '</td>
                                <td>' . $row['barCode'] . '</td>
                                <td><span class="itmProd">' . getPriceWithCur($row['price'], $getDefCurDet['curCode']) . '</span></td>
                                <td><span class="itmProd">' . $row['unitP'] . '</span></td>
                                <td><span class="itmProd">' . $row['qtyReceived'] . '</span></td>
                                <td><span class="itmProd">' . $row['qty'] . '</span></td>
                                <td><span class="itmProd">' . ($i == 1 ? ($row['qty'] - $row['qtyReceived']) : ($row['qty'] + $row['qtyReceived'])) . '</span></td>
                                <td><span class="itmProd">' . getPriceWithCur($row['totalAmt'], $getDefCurDet['curCode']) . '</span></td>
                            </tr>';
}
$content  .= '</tbody>
                    </table>

                </div> </div>';
echo $content;
>>>>>>> faaf518a7b2f7fa387a7d721ce1dda3041c227c6
