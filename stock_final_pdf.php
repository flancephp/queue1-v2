<?php
include_once('inc/dbConfig.php'); //connection details

$cond = '';
$storeCond = '';
$getLangType = getLangType($_SESSION['language_id']);
if (isset($_SESSION['filterByStorage']) && ($_SESSION['filterByStorage']) != '') {
    $cond = " AND tp.storageDeptId = '" . $_SESSION['filterByStorage'] . "' ";
    $storeCond = " AND s.id = '" . $_SESSION['filterByStorage'] . "' ";
}

if (isset($_SESSION['getVals']['subCatId']) && $_SESSION['getVals']['subCatId']) {
    $cond .= " AND c.id = '" . $_SESSION['getVals']['subCatId'] . "'  ";
}

if (isset($_SESSION['getVals']['suppId']) && $_SESSION['getVals']['suppId']) {


    $sql = " SELECT productId FROM tbl_productsuppliers WHERE supplierId = '" . $_SESSION['getVals']['suppId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ";
    $sqlRes = mysqli_query($con, $sql);

    $tpIdArr = [];
    while ($resRow = mysqli_fetch_array($sqlRes)) {
        $tpIdArr[] = $resRow['productId'];
    }

    $tpId = implode(',', $tpIdArr);


    $cond .= " AND tp.id IN(" . $tpId . ") ";
}


$sql = "SELECT
tp.*,
s.qty stockQty,
s.lastPrice stockLastPrice,
s.stockPrice sPrice,
s.stockValue,
pc.name parentCatName,
IFNULL(c.name, 'z') childCatName,
IF(uc.name!='', uc.name,tp.unitC) countingUnit,
IF(up.name!='', up.name,tp.unitP) purchaseUnit,
GROUP_CONCAT(DISTINCT sp.name) suppls
FROM
tbl_stocks s

INNER JOIN tbl_products tp ON
(s.pId = tp.id) AND tp.status = 1 AND s.account_id = tp.account_id
LEFT JOIN tbl_units up ON
    (up.id = tp.unitP) AND up.account_id = tp.account_id
LEFT JOIN tbl_units uc ON
    (uc.id = tp.unitC) AND uc.account_id = tp.account_id
LEFT JOIN tbl_category pc ON
(pc.id = tp.parentCatId) AND pc.account_id = tp.account_id
LEFT JOIN tbl_category c ON
(c.id = tp.catId) AND c.account_id = tp.account_id


LEFT JOIN tbl_productsuppliers ps ON(ps.productId = tp.id) AND ps.account_id = tp.account_id
LEFT JOIN tbl_suppliers sp ON(ps.supplierId = sp.id) AND ps.account_id = sp.account_id

WHERE tp.account_id ='" . $_SESSION['accountId'] . "' " . $cond . " GROUP BY tp.id ORDER by tp.catId DESC, s.id DESC ";

$stockMainQry = mysqli_query($con, $sql);
//End stock lists

//get store permission
if (!empty(get_store_permission($_SESSION['designation_id'], $_SESSION['accountId']))) {

    $storeCond .= " AND dssp.designation_id = '" . $_SESSION['designation_id'] . "' AND dssp.type = 'stock' AND dssp.designation_section_permission_id = '5'  ";
}

//Store details queries goes here
$sqlSet = " SELECT DISTINCT(s.id),s.* FROM tbl_stores s

LEFT JOIN tbl_designation_sub_section_permission dssp
ON(s.id=dssp.type_id) AND s.account_id=dssp.account_Id 

WHERE s.account_id = '" . $_SESSION['accountId'] . "' " . $storeCond . "   ";
$storeQry = mysqli_query($con, $sqlSet);


$sql = "SELECT SUM(stockValue) totalstockValue  FROM tbl_stocks s 

INNER JOIN tbl_products tp ON(s.pId = tp.id) AND tp.status=1 AND s.account_id = tp.account_id

LEFT JOIN tbl_category c ON (c.id = tp.catId) AND c.account_id = tp.account_id

LEFT JOIN tbl_stores st ON(st.id = tp.storageDeptId) AND st.account_id = tp.account_id

LEFT JOIN tbl_designation_sub_section_permission dssp
ON(st.id=dssp.type_id) AND st.account_id=dssp.account_Id

WHERE 1=1 AND s.account_id = '" . $_SESSION['accountId'] . "' " . $storeCond . " " . $cond;
$resQry = mysqli_query($con, $sql);
$storeResRow = mysqli_fetch_array($resQry);

// Get full address and their logo of client
$sql = " SELECT c.name AS countryName, cl.* FROM tbl_client cl
LEFT JOIN tbl_country c ON(cl.country=c.id)
WHERE cl.id = '" . $_SESSION['accountId'] . "' ";
$result = mysqli_query($con, $sql);
$clientDetRow = mysqli_fetch_array($result);


$sql = "SELECT * FROM tbl_user  WHERE id = '" . $_SESSION['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
$result = mysqli_query($con, $sql);
$userDetails = mysqli_fetch_array($result);
$stockUserFilterFields = $userDetails['stockUserFilterFields'] ?    explode(',', $userDetails['stockUserFilterFields']) : null;





$content = '<!DOCTYPE html>';
$content .= '<html lang="en">';
$content .=   '<head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>' . showOtherLangText('Stock PDF') . '</title>
      <link rel="preconnect" href="https://fonts.googleapis.com/">
      <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
      <link href="./PDF Design_files/css2" rel="stylesheet">
      <style>
         @page { margin: 10px 10px; }
         th, td { border:0;vertical-align:top; } 
      </style>
   </head>';
$content .= '<body style="' . ($getLangType == '1'
    ? 'font-family: firefly, DejaVu Sans, sans-serif, Inter; color: #232859; font-weight: 400; font-size: 12px; line-height: 14px;'
    : 'font-family: \'Inter\', sans-serif; color: #232859; font-weight: 400; font-size: 12px; line-height: 14px;') . '">';
$content .=   '<table style="width: 100%; border-spacing: 0; padding-bottom: 16px;">
         <tbody>
            <tr valign="top">';
$content .= '<td width="50%">';
if ($_GET['address'] == 1 || $_GET['logo'] == 1) {
    if ($_GET['address'] == 1) {
        $content .= '<table style="width: 100%; border-spacing: 0;">
                     <tbody>
                        <tr>
                           <td style="font-size: 14px; line-height: 16px; font-weight: 600;">' . $clientDetRow['accountName'] . '</td>
                        </tr>
                     </tbody>
                  </table>
                  <table style="width: 100%; border-spacing: 0; margin-top: 5px;">
                     <tbody>
                        <tr>
                           <td style="font-size: 13px; line-height: 15px; font-weight: 400;">
                              ' . $clientDetRow['address_one'] . ' <br>' . $clientDetRow['address_two'] . '
                              ' . $clientDetRow['city'] . ', ' . $clientDetRow['countryName'] . '<br>
                              ' . $clientDetRow['email'] . '<br>
                              ' . $clientDetRow['phone'] . '
                           </td>
                        </tr>
                     </tbody>
                  </table>';
    }
}
$content .= '</td>';
$content .= '<td width="50%" align="right">';
if ($_GET['logo'] == 1) {

    if ($clientDetRow["logo"] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/clientLogo/" . $clientDetRow["logo"])) {
        $content .= '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/clientLogo/' . $clientDetRow['logo'] . '" style="object-fit: scale-down; height: 60px; width: auto;">';
    } else {
        $content .= '<img src="' . $siteUrl . 'uploads/pdf-logo-sample.png" alt="Logo" style="object-fit: scale-down; height: 60px; width: auto;">';
    }
}
$content .= '</td>';
$content .= '</tr>
         </tbody>
      </table>
      <table style="width: 100%; border-collapse: collapse; margin-block-end: 32px;">
        <tr>
            <td style="width: 50%;padding-block: 20px;">
                <div style="display: flex; align-items: center;">
                    <div style="flex: 1;">
                        <h6 style="font-weight: 600; font-size: 16px; margin: 0;font-family:Arial, sans-serif;">' . showOtherLangText('Stock Report') . '</h6>
                    </div>
                    <div style="flex: 1; text-align: right; font-size: 15px;">' . date('d-m-Y') . '</div>
                </div>
            </td>
        </tr>
    </table>';
$totalStockTakeCnt = getMobileStockTakeCount($_SESSION['filterByStorage'], 1);

$_GET['stockTake'] = $totalStockTakeCnt > 0 ? 1 : 0;
$content .= '<table style="font-size:12px;" width="100%">
        <tr style="vertical-align: baseline;">
            <td style="width: 55%;">
                <table style="width:100%; margin-right:1px; font-size:12px; border-collapse: collapse;border:0;">';

$content .= '<tr style="background-color: rgba(122, 137, 255, 0.2); font-weight:bold;">';
if ($_GET['store'] == 1) {
    $content .= '<td style="padding: 8px 5px;border:0;">' . showOtherLangText('stores') . '</td>';
}
if ($_GET['totalPrice'] == 1) {
    $content .= '<td style="padding: 8px 5px;border:0;">' . showOtherLangText('Total Price') . '</td>';
}

if ($_GET['stockTake'] == 1) {
    $content .= '<td style="padding: 8px 5px;">' . showOtherLangText('Stock Take') . '</td>';
}
if (!isset($_SESSION['filterByStorage']) || ($_SESSION['filterByStorage']) == '') //hide if particular storage is selected
{
    $content .= '</tr>';
    if ($_GET['store'] == 1 || $_GET['totalPrice'] == 1 || $_GET['stockTake'] == 1) {
        $content .= '<tr>';
        if ($_GET['store'] == 1) {
            $content .=  '<td style="padding: 8px 5px;">' . showOtherLangText('All stores') . '</td>';
        }
        if ($_GET['totalPrice'] == 1) {
            $content .=  '<td style="padding: 8px 5px;">' . getPriceWithCur($storeResRow['totalstockValue'], $getDefCurDet['curCode']) . '</td>';
        }
        if ($_GET['stockTake'] == 1) {
            $content .=  '<td style="padding: 8px 5px;">' . getMobileStockTakeTotalCount() . '</td>';
        }
        $content .= '</tr>';
    }
}
if ($_GET['store'] == 1 || $_GET['totalPrice'] == 1 || $_GET['stockTake'] == 1) {
    while ($storageDeptRow = mysqli_fetch_array($storeQry)) {
        $totalstockValue = getStockTotalOfStore($storageDeptRow['id'], $cond);

        $content .= '<tr>';
        if ($_GET['store'] == 1) {
            $content .=  '<td style="padding: 8px 5px;">' . $storageDeptRow['name'] . '</td>';
        }
        if ($_GET['totalPrice'] == 1) {
            $content .=  '<td style="padding: 8px 5px;">' . getPriceWithCur($totalstockValue, $getDefCurDet['curCode']) . '</td>';
        }
        if ($_GET['stockTake'] == 1) {
            $content .=  '<td style="padding: 8px 5px;">' . $totalStockTakeCnt . '</td>';
        }
        $content .= '</tr>';
    }
}
$content .= '</table>
            </td>
            
        </tr>
    </table>';
if ($_GET['photo'] == 1 || $_GET['itemName'] == 1 || $_GET['barCode'] == 1 || $_GET['qty'] == 1 || $_GET['reqQty'] == 1 || $_GET['avlQty'] == 1 || $_GET['lastPrice'] == 1 || $_GET['stockPrice'] == 1 || $_GET['stockValue'] == 1 || $_GET['subCat'] == 1 || $_GET['suplr'] == 1) {
    $content .= '<table style="width:100%; font-size:12px; margin-block-start: 24px; border-collapse: collapse;">';
    $content .= '<tr style="font-weight:bold; background-color: rgba(122, 137, 255, 0.2);">';
    $headerArr =
        [
            1 => '' . showOtherLangText('#') . '',

            2 => '' . showOtherLangText('Photo') . '',

            3 => '' . showOtherLangText('Item') . '/ ' . showOtherLangText('BarCode') . '',

            4 => '' . showOtherLangText('Quantity') . '',

            5 => '' . showOtherLangText('Req Qty') . '',

            6 => '' . showOtherLangText('Avail Qty') . '',

            7 => '' . showOtherLangText('Last Price') . '',

            8 => '' . showOtherLangText('Stock Price') . '',

            9 => '' . showOtherLangText('Stock Value') . '',

            10 => '' . showOtherLangText('Sub Category') . '',

            11 => '' . showOtherLangText('Supplier') . '',

        ];

    // Logic to show/hide column of the item table from previous page checked value
    if ($_GET['photo'] == 0) {
        unset($headerArr[2]);
    }
    if ($_GET['itemName'] == 0 && $_GET['barCode'] == 0) {
        unset($headerArr[3]);
    }
    if ($_GET['qty'] == 0) {
        unset($headerArr[4]);
    }
    if ($_GET['reqQty'] == 0) {
        unset($headerArr[5]);
    }
    if ($_GET['avlQty'] == 0) {
        unset($headerArr[6]);
    }
    if ($_GET['lastPrice'] == 0) {
        unset($headerArr[7]);
    }
    if ($_GET['stockPrice'] == 0) {
        unset($headerArr[8]);
    }
    if ($_GET['stockValue'] == 0) {
        unset($headerArr[9]);
    }
    if ($_GET['subCat'] == 0) {
        unset($headerArr[10]);
    }
    if ($_GET['suplr'] == 0) {
        unset($headerArr[11]);
    }

    // Item Table column Header
    foreach ($headerArr as $key => $header) {
        if ($key == '1') {
            $content .= '<td style="padding: 8px 5px;border:0;" class="head' . $key . '">' . $header . '</td>';
        } elseif ($key == '2') {
            $content .= '<td style="padding: 5px 8px;border:0;" class="head' . $key . '">' . $header . '</td>';
        } elseif ($key == '3' && $_GET['itemName'] == 0 && $_GET['barCode'] == 1) {

            $content .= '<td style="padding: 5px 8px;text-align: left;width:50px;border:0;" class="head' . $key . '">' . showOtherLangText('BarCode') . '</td>';
        } elseif ($key == '3' && $_GET['itemName'] == 1 && $_GET['barCode'] == 0) {

            $content .= '<td style="padding: 5px 8px;text-align: left;width:50px;border:0;" class="head' . $key . '">' . showOtherLangText('Item') . '</td>';
        } else {
            $content .= '<td style="padding: 5px 8px;text-align: left;border:0;" class="head' . $key . '">' . $header . '</td>';
        }
    }
    $content .= '</tr>';
    //get confirmed requsitions total qty of each productd
    $productsConfirmedQtyArr = getConfirmTotalQtyReq($_SESSION['accountId']);
    //end get confirmed requsitions total qty of each productd

    $i = 0;
    while ($row = mysqli_fetch_array($stockMainQry))
    //foreach($_SESSION['stockRows'] as $row)
    {
        $i++;
        $totalProQty = isset($productsConfirmedQtyArr[$row['id']]) ? $productsConfirmedQtyArr[$row['id']] : 0;
        if ($totalProQty > 0) {
            $totalTempProQty = $totalProQty;
        } else {
            $totalTempProQty = 0;
        }

        $supNames = $row['suppls'];
        $catNames = ($row['childCatName'] == 'z' ? '' :  $row['childCatName']);

        $img = '';
        if ($row['imgName'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/products/" . $row['imgName'])) {
            $img = '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/products/' . $row['imgName'] . '" width="40" height="40">';
        } else {
            $img = '<img src="' . $siteUrl . 'uploads/sample-prod-img.jpg" width="40" height="40">';
        }
        $content .= '<tr>';
        $content .= '<td style="padding: 8px 5px;border:0;">' . $i . '</td>';
        if ($_GET['photo'] == 1) {
            $content .= '<td style="padding: 8px 5px;border:0;">' . $img . '</td>';
        }
        if ($_GET['itemName'] == 1 && $_GET['barCode'] == 1) {
            $content .= '<td style="padding: 8px 5px;border:0;">' . $row['itemName'] . ' <br>' . $row['barCode'] . '</td>';
        } elseif ($_GET['itemName'] == 1 || $_GET['barCode'] == 1) {
            $data = $_GET['itemName'] == 1 ? $row['itemName'] : $row['barCode'];
            $content .= '<td style="padding: 8px 5px;border:0;">' . $data . '</td>';
        }
        if ($_GET['qty'] == 1) {
            $content .= '<td style="padding: 8px 5px; font-weight: 600;border:0;">' . $row['stockQty'] . ' ' . $row['countingUnit'] . '</td>';
        }
        if ($_GET['reqQty'] == 1) {
            $content .= '<td style="padding: 8px 5px;border:0;">' . $totalTempProQty . ' ' . $row['countingUnit'] . '</td>';
        }
        if ($_GET['avlQty'] == 1) {
            $content .= '<td style="padding: 8px 5px; font-weight: 600;border:0;">' . ($row['stockQty'] - $totalTempProQty) . ' ' . $row['countingUnit'] . '</td>';
        }
        if ($_GET['lastPrice'] == 1) {
            $content .= '<td style="padding: 8px 5px;border:0;">' . getPrice($row['stockLastPrice']) . ' ' . $getDefCurDet['curCode'] . '</td>';
        }
        if ($_GET['stockPrice'] == 1) {
            $content .= '<td style="padding: 8px 5px;border:0;">' . getPrice($row['sPrice']) . ' ' . $getDefCurDet['curCode'] . '</td>';
        }
        if ($_GET['stockValue'] == 1) {
            $content .= '<td style="padding: 8px 5px;border:0;">' . getPrice($row['stockValue']) . ' ' . $getDefCurDet['curCode'] . '</td>';
        }
        if ($_GET['subCat'] == 1) {
            $content .= '<td style="padding: 8px 5px;border:0;">' . $catNames . '</td>';
        }
        if ($_GET['suplr'] == 1) {
            $content .= '<td style="padding: 8px 5px;border:0;">' . $supNames . '</td>';
        }
        $content .= '</tr>';
    }

    $content .= '</table>';
}
$content .= '</body>
     </html>';
