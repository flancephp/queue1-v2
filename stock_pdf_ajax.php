<?php
include_once('inc/dbConfig.php'); //connection details

$cond = '';
$storeCond = '';

$getLangType = getLangType($_SESSION['language_id']);


if (isset($_SESSION['filterByStorage']) && ($_SESSION['filterByStorage']) != '') {
    $cond = "  AND tp.storageDeptId = '" . $_SESSION['filterByStorage'] . "' ";
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


    $cond .= " AND tp.ID IN(" . $tpId . ") ";
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



WHERE tp.account_id ='" . $_SESSION['accountId'] . "' " . $cond . " GROUP BY tp.id ORDER by s.id DESC ";

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

$stockTakeCnt = getMobileStockTakeCount($_SESSION['filterByStorage'], 1);

$content = '<form method="get" action="stock_pdf_download.php" target="_blank"><div class="modal-header ">
<input type="hidden" name="getLangType" value="' . $getLangType . '"/> 

                    <div class="mb-modal-close-icon">  <button type="button" class="btn-close m-0 d-lg-none" data-bs-dismiss="modal" aria-label="Close" fdprocessedid="mvv0xh"></button></div>
                    <div class="d-md-flex align-items-center justify-content-between w-100 ">
                       
                        <div class="d-flex align-items-start w-100 gap-3 w-auto mb-md-0 mb-2 modal-head-btn">
                             
                                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#modalfiltertop">
                                    <i class="fa fa-filter"></i>
                                </button>
                                 
                                <div class="collapse dView1" id="modalfiltertop">
                                    <div class="d-flex gap-3 modal-head-row">
                                    

                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        ' . showOtherLangText('Headers') . '<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" class="headChk-All form-check-input" value="1">
                                                <span class="fs-13">' . showOtherLangText('Check All') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" id="headChk-Bx" name="address" class="headCheckbox header-address form-check-input" checked="checked" onclick="showHideByClass(\'address-section\')"
                                                    value="1">
                                                <span class="fs-13">' . showOtherLangText('Address') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" id="headChk-Bx" name="logo" class="headCheckbox header-logo form-check-input" value="1" onclick="showHideByClass(\'logo-section\')">
                                                <span class="fs-13">' . showOtherLangText('Logo') . '</span>
                                            </li>
                                        </ul>
                                    </div>                                  

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        ' . showOtherLangText('Summary') . '<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" id="summary"class="smryChk-All form-check-input" value="1">
                                                <span class="fs-13">' . showOtherLangText('Check All') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="store" id="smryChk-Bx" class="form-check-input smryCheckbox summary-store" value="1" onclick="showHideByClass(\'store-name\')">
                                                <span class="fs-13">' . showOtherLangText('Stores') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" d="smryChk-Bx" id="smryChk-Bx"name="totalPrice" class="smryCheckbox summary-totalPrice form-check-input" onclick="showHideByClass(\'store-totalPrice\')" value="1">
                                                <span class="fs-13">' . showOtherLangText('Total Price') . '</span>
                                            </li>';
if ($stockTakeCnt > 0) {
    $content .=  '<li>
                                                <input type="checkbox" checked="checked" 
                                                   id="smryChk-Bx" name="stockTake" class="smryCheckbox summary-stockTake form-check-input" 
                                                   onclick="showHideByClass(\'store-stockTakeCount\')" value="1">
                                                <span class="fs-13">' . showOtherLangText('Stock Take') . '</span>
                                            </li>';
}
$content .=  '</ul>
                                    </div>

                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        ' . showOtherLangText('Item Table') . '<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" class="itemChk-All form-check-input" value="1">
                                                <span class="fs-13">' . showOtherLangText('Check All') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" ' . (isset($stockUserFilterFields) && !in_array(1, $stockUserFilterFields) ? '' : 'checked="checked"') . ' name="photo" class="itmTblCheckbox item-photo form-check-input" onclick="showHideByClass(\'item2\')" value="1">
                                                <span class="fs-13">' . showOtherLangText('Photo') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox"  name="itemName" ' . (isset($stockUserFilterFields) && !in_array(2, $stockUserFilterFields) ? '' : 'checked="checked"') . 'class="itmTblCheckbox item-item form-check-input" 
                                                 onclick="showHideByClass(\'item3\')" value="1">
                                                <span class="fs-13">' . showOtherLangText('Item') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox"  name="barCode" ' . (isset($stockUserFilterFields) && !in_array(10, $stockUserFilterFields) ? '' : 'checked="checked"') . ' class="itmTblCheckbox item-barCode form-check-input" onclick="showHideByClass(\'item4\')" value="1">
                                                <span class="fs-13">' . showOtherLangText('BarCode') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox"  name="qty" ' . (isset($stockUserFilterFields) && !in_array(3, $stockUserFilterFields) ? '' : 'checked="checked"') . ' class="itmTblCheckbox item-quantity form-check-input" onclick="showHideByClass(\'item5\')" value="1">
                                                <span class="fs-13">' . showOtherLangText('Quantity') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox"  name="reqQty" ' . (isset($stockUserFilterFields) && !in_array(5, $stockUserFilterFields) ? '' : 'checked="checked"') . ' onclick="showHideByClass(\'item6\')" class="itmTblCheckbox item-requestsQty form-check-input" value="1">
                                                <span class="fs-13">' . showOtherLangText('Requests Qty') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="avlQty" ' . (isset($stockUserFilterFields) && !in_array(4, $stockUserFilterFields) ? '' : 'checked="checked"') . ' onclick="showHideByClass(\'item7\')" class="itmTblCheckbox item-availableQty form-check-input" value="1">
                                                <span class="fs-13">' . showOtherLangText('Available Qty') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="lastPrice" ' . (isset($stockUserFilterFields) && !in_array(6, $stockUserFilterFields) ? '' : 'checked="checked"') . '  class="itmTblCheckbox item-lastPrice form-check-input" onclick="showHideByClass(\'item8\')" value="1">
                                                <span class="fs-13">' . showOtherLangText('Last Price') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="stockPrice" ' . (isset($stockUserFilterFields) && !in_array(7, $stockUserFilterFields) ? '' : 'checked="checked"') . ' onclick="showHideByClass(\'item9\')" class="itmTblCheckbox item-stockPrice form-check-input" value="1">
                                                <span class="fs-13">' . showOtherLangText('Stock Price') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="stockValue" ' . (isset($stockUserFilterFields) && !in_array(18, $stockUserFilterFields) ? '' : 'checked="checked"') . ' onclick="showHideByClass(\'item10\')" class="itmTblCheckbox item-stockValue form-check-input" value="1">
                                                <span class="fs-13">' . showOtherLangText('Stock Value') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="subCat" ' . (isset($stockUserFilterFields) && !in_array(8, $stockUserFilterFields) ? '' : 'checked="checked"') . ' onclick="showHideByClass(\'item11\')" class="itmTblCheckbox item-subCategory form-check-input" value="1">
                                                <span class="fs-13">' . showOtherLangText('Sub Category') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="suplr" ' . (isset($stockUserFilterFields) && !in_array(9, $stockUserFilterFields) ? '' : 'checked="checked"') . ' class="form-check-input itmTblCheckbox item-supplier" onclick="showHideByClass(\'item12\')"  value="1">
                                                <span class="fs-13">' . showOtherLangText('Supplier') . '</span>
                                            </li>
                                        </ul>
                                    </div>

                                    </div>
                                
                                </div>

                        
                        
                        </div>
                        <button type="submit"
                    class="btn btn-primary dwnBtn"><span class="align-middle">' . showOtherLangText('Press') . '</span> <i class="fa-solid fa-download ps-1"></i></button>
                    </div>

                    <div class="collapse mView1" id="modalfiltertop">
                                    <div class="d-flex gap-3 modal-head-row">
                                    

                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        ' . showOtherLangText('Headers') . '<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" class="headChk-All form-check-input" value="1">
                                                <span class="fs-13">' . showOtherLangText('Check All') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" id="headChk-Bx" name="address" class="headCheckbox header-address form-check-input" checked="checked" onclick="showHideByClass(\'address-section\')"
                                                    value="1">
                                                <span class="fs-13">' . showOtherLangText('Address') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" id="headChk-Bx" name="logo" class="headCheckbox header-logo form-check-input" value="1" onclick="showHideByClass(\'logo-section\')">
                                                <span class="fs-13">' . showOtherLangText('Logo') . '</span>
                                            </li>
                                        </ul>
                                    </div>                                  

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        ' . showOtherLangText('Summary') . '<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" id="summary"class="smryChk-All form-check-input" value="1">
                                                <span class="fs-13">' . showOtherLangText('Check All') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="store" id="smryChk-Bx" class="form-check-input smryCheckbox summary-store" value="1" onclick="showHideByClass(\'store-name\')">
                                                <span class="fs-13">' . showOtherLangText('Stores') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" d="smryChk-Bx" id="smryChk-Bx"name="totalPrice" class="smryCheckbox summary-totalPrice form-check-input" onclick="showHideByClass(\'store-totalPrice\')" value="1">
                                                <span class="fs-13">' . showOtherLangText('Total Price') . '</span>
                                            </li>';
if ($stockTakeCnt > 0) {
    $content .=  '<li>
                                                <input type="checkbox" checked="checked" 
                                                   id="smryChk-Bx" name="stockTake" class="smryCheckbox summary-stockTake form-check-input" 
                                                   onclick="showHideByClass(\'store-stockTakeCount\')" value="1">
                                                <span class="fs-13">' . showOtherLangText('Stock Take') . '</span>
                                            </li>';
}
$content .=  '</ul>
                                    </div>

                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        ' . showOtherLangText('Item Table') . '<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" class="itemChk-All form-check-input" value="1">
                                                <span class="fs-13">' . showOtherLangText('Check All') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" ' . (isset($stockUserFilterFields) && !in_array(1, $stockUserFilterFields) ? '' : 'checked="checked"') . ' name="photo" class="itmTblCheckbox item-photo form-check-input" onclick="showHideByClass(\'item2\')" value="1">
                                                <span class="fs-13">' . showOtherLangText('Photo') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox"  name="itemName" ' . (isset($stockUserFilterFields) && !in_array(2, $stockUserFilterFields) ? '' : 'checked="checked"') . 'class="itmTblCheckbox item-item form-check-input" 
                                                 onclick="showHideByClass(\'item3\')" value="1">
                                                <span class="fs-13">' . showOtherLangText('Item') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox"  name="barCode" ' . (isset($stockUserFilterFields) && !in_array(10, $stockUserFilterFields) ? '' : 'checked="checked"') . ' class="itmTblCheckbox item-barCode form-check-input" onclick="showHideByClass(\'item4\')" value="1">
                                                <span class="fs-13">' . showOtherLangText('BarCode') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox"  name="qty" ' . (isset($stockUserFilterFields) && !in_array(3, $stockUserFilterFields) ? '' : 'checked="checked"') . ' class="itmTblCheckbox item-quantity form-check-input" onclick="showHideByClass(\'item5\')" value="1">
                                                <span class="fs-13">' . showOtherLangText('Quantity') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox"  name="reqQty" ' . (isset($stockUserFilterFields) && !in_array(5, $stockUserFilterFields) ? '' : 'checked="checked"') . ' onclick="showHideByClass(\'item6\')" class="itmTblCheckbox item-requestsQty form-check-input" value="1">
                                                <span class="fs-13">' . showOtherLangText('Requests Qty') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="avlQty" ' . (isset($stockUserFilterFields) && !in_array(4, $stockUserFilterFields) ? '' : 'checked="checked"') . ' onclick="showHideByClass(\'item7\')" class="itmTblCheckbox item-availableQty form-check-input" value="1">
                                                <span class="fs-13">' . showOtherLangText('Available Qty') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="lastPrice" ' . (isset($stockUserFilterFields) && !in_array(6, $stockUserFilterFields) ? '' : 'checked="checked"') . '  class="itmTblCheckbox item-lastPrice form-check-input" onclick="showHideByClass(\'item8\')" value="1">
                                                <span class="fs-13">' . showOtherLangText('Last Price') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="stockPrice" ' . (isset($stockUserFilterFields) && !in_array(7, $stockUserFilterFields) ? '' : 'checked="checked"') . ' onclick="showHideByClass(\'item9\')" class="itmTblCheckbox item-stockPrice form-check-input" value="1">
                                                <span class="fs-13">' . showOtherLangText('Stock Price') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="stockValue" ' . (isset($stockUserFilterFields) && !in_array(18, $stockUserFilterFields) ? '' : 'checked="checked"') . ' onclick="showHideByClass(\'item10\')" class="itmTblCheckbox item-stockValue form-check-input" value="1">
                                                <span class="fs-13">' . showOtherLangText('Stock Value') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="subCat" ' . (isset($stockUserFilterFields) && !in_array(8, $stockUserFilterFields) ? '' : 'checked="checked"') . ' onclick="showHideByClass(\'item11\')" class="itmTblCheckbox item-subCategory form-check-input" value="1">
                                                <span class="fs-13">' . showOtherLangText('Sub Category') . '</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" name="suplr" ' . (isset($stockUserFilterFields) && !in_array(9, $stockUserFilterFields) ? '' : 'checked="checked"') . ' class="form-check-input itmTblCheckbox item-supplier" onclick="showHideByClass(\'item12\')"  value="1">
                                                <span class="fs-13">' . showOtherLangText('Supplier') . '</span>
                                            </li>
                                        </ul>
                                    </div>

                                    </div>
                                
                                </div>
                </div>
                <div class="modal-body px-2 py-3">
                    <div class="row pb-3">
                        <div class="col-6">
                            <div class="address-section modal-address ">
                                <h6 class="semibold fs-14">' . $clientDetRow['accountName'] . '</h6>
                                <div class="fs-13 ">
                                    <p>' . $clientDetRow['address_one'] . '</p>
                                    <p>' . $clientDetRow['address_two'] . '</p>
                                    <p>' . $clientDetRow['city'] . ', ' . $clientDetRow['countryName'] . '</p>
                                    <p>' . $clientDetRow['email'] . '</p>
                                    <p>' . $clientDetRow['phone'] . '</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <div class="modal-logo logo-section">';
if ($clientDetRow['logo'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . '/clientLogo/' . $clientDetRow['logo'])) {
    $content .=  '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/clientLogo/' . $clientDetRow['logo'] . '" style="object-fit: scale-down; height: 50px; width: auto;">';
} else {
    $content .=  '<img width="100" height="100" src="uploads/pdf-logo-sample.png" alt="Logo">';
}
$content .=   '</div>
                        </div>
                    </div>

                    <div class="model-title-with-date">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h6 class="semibold">' . showOtherLangText('Stock View') . '</h6>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-end fs-15">' . date('d/m/Y') . '</p>
                            </div>
                        </div>
                    </div>

                    <div class="summery-row summaryPart summarySectionPartMain">
                        <div class="row">
                            <div class="storesection pe-6 w-100">
                                <div class="modal-table fs-12 w-100">
                                  
                                    <div class="table-row thead">
                                        <div class="table-cell "><span class="store-name summaryPart">' . showOtherLangText('Stores') . '</span></div>
                                        <div class="table-cell"><span class="store-totalPrice summaryPart">' . showOtherLangText('Total Price') . '</span></div>';
if ($stockTakeCnt > 0) {
    $content .= '<div class="table-cell"><span class="store-stockTakeCount summaryPart">' . showOtherLangText('Stock Take') . '</span></div>';
}
$content .= '</div>';

if (!isset($_SESSION['filterByStorage']) || ($_SESSION['filterByStorage']) == '') {

    $totalstockValue = getStockTotalOfStore($storageDeptRow['id'], $cond);
    $content .= '<div class="table-row">
                                                            <div class="table-cell"><span class="store-name summaryPart"> ' . showOtherLangText('All Stores') . '</span> </div>
                                                            <div class="table-cell"><span class="store-totalPrice summaryPart">' . getPriceWithCur($storeResRow['totalstockValue'], $getDefCurDet['curCode']) . '</div>';
    if ($stockTakeCnt > 0) {
        $content .= '<div class="table-cell"><span class="store-stockTakeCount summaryPart"> ' . getMobileStockTakeTotalCount() . '</span></div>
                                                           ';
    }

    $content .= '</div>';
}

while ($storageDeptRow = mysqli_fetch_array($storeQry)) {

    $totalstockValue = getStockTotalOfStore($storageDeptRow['id'], $cond);
    $content .= '<div class="table-row">
                                            <div class="table-cell"><span class="store-name summaryPart">' . $storageDeptRow['name'] . '</span> </div>
                                            <div class="table-cell"><span class="store-totalPrice summaryPart">' . getPriceWithCur($totalstockValue, $getDefCurDet['curCode']) . '</div>';
    if ($stockTakeCnt > 0) {
        $content .= '<div class="table-cell"><span class="store-stockTakeCount summaryPart">' . getMobileStockTakeCount($storageDeptRow['id'], 0, '') . '</span></div>
                                            ';
    }

    $content .= '</div>';
}

$content .= '</div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="overflowTable itemSectionPartMain itemSectionPart"> 
                        <div class="modal-table fs-12 w-100 mt-4">
                            <div class="table-row thead">
                                <div class="table-cell"><span class="item1 itemSectionPart">#</span></div>
                                <div class="table-cell"><span class="item2 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(1, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . showOtherLangText('Photo') . '</span></div>';
$content .=  '<div class="table-cell"><span class="item3 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(2, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . showOtherLangText('Item') . '</span><span class="slashItem itemSectionPart">/</span><span class="itemSectionPart item4" ' . (isset($stockUserFilterFields) && !in_array(10, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . showOtherLangText('BarCode') . '</span></div>';

$content .=  '<div class="table-cell"><span class="item5 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(3, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . showOtherLangText('Quantity') . '</span></div>';
$content .=  '<div class="table-cell"><span class="item6 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(5, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . showOtherLangText('Req Qty') . '</span></div>';
$content .= '<div class="table-cell"><span class="item7 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(4, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . showOtherLangText('Avail Qty') . '</span></div>';
$content .= '<div class="table-cell"><span class="item8 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(6, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . showOtherLangText('Last Price') . '</span></div>';
$content .= '<div class="table-cell"><span class="item9 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(7, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . showOtherLangText('Stock Price') . '</span></div>';
$content .= '<div class="table-cell"><span class="item10 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(18, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . showOtherLangText('Stock Value') . '</span></div>';
$content .= '<div class="table-cell"><span class="item11 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(8, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . showOtherLangText('Sub Category') . '</span></div>';
$content .= '<div class="table-cell"><span class="item12 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(9, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . showOtherLangText('Supplier') . '</span></div>';
$content .= '</div>';
//get confirmed requsitions total qty of each productd
$productsConfirmedQtyArr = getConfirmTotalQtyReq($_SESSION['accountId']);
//end get confirmed requsitions total qty of each productd

$_SESSION['stockRows'] = [];
$i = 0;
while ($row = mysqli_fetch_array($stockMainQry)) {

    $_SESSION['stockRows'][] = $row;

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
        $img = '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/products/' . $row['imgName'] . '" alt="Outlet_Photo">';
    }
    $content .= '<div class="table-row">
                                <div class="table-cell">' . $i . '</div>
                                <div class="table-cell table-cell-photo"><span class="item2 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(1, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . $img . '</span></div>
                                <div class="table-cell"><span class="item3 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(2, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . $row['itemName'] . '</span> <br> 
                                <span class="item4 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(10, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . $row['barCode'] . '</span></div>
                                <div class="table-cell font-bold"><span class="item5 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(3, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . $row['stockQty'] . " " . $row['countingUnit'] . '</span></div>
                                <div class="table-cell"><span class="item6 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(5, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . $totalTempProQty . " " . $row['countingUnit'] . '</span></div>
                                <div class="table-cell font-bold"><span class="item7 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(4, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . ($row['stockQty'] - $totalTempProQty) . " " . $row['countingUnit'] . '</span></div>
                                <div class="table-cell"><span class="item8 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(6, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . getPrice($row['stockLastPrice']) . " " . $getDefCurDet['curCode'] . '</span></div>
                                <div class="table-cell"><span class="item9 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(7, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . getPrice($row['sPrice']) . " " . $getDefCurDet['curCode'] . '</span></div>
                                <div class="table-cell"><span class="item10 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(18, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . getPrice($row['stockValue']) . " " . $getDefCurDet['curCode'] . '</span></div>
                                <div class="table-cell"><span class="item11 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(8, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . $catNames . '</span></div>
                                <div class="table-cell"><span class="item12 itemSectionPart" ' . (isset($stockUserFilterFields) && !in_array(9, $stockUserFilterFields) ? 'style="display:none;"' : '') . '>' . $supNames . '</span></div>
                            </div>';
}

$content .=   '</div>
                    </div>
                </div></form>';
echo $content;
