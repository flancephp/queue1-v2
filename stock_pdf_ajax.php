<?php
include_once('inc/dbConfig.php'); //connection details

$cond = '';
$storeCond = '';



if( isset($_SESSION['filterByStorage']) && ($_SESSION['filterByStorage']) != '' )
{
    $cond = "  AND tp.storageDeptId = '".$_SESSION['filterByStorage']."' ";
    $storeCond = " AND s.id = '".$_SESSION['filterByStorage']."' ";
}

if( isset($_SESSION['getVals']['subCatId']) && $_SESSION['getVals']['subCatId'])
{
    $cond .= " AND c.id = '".$_SESSION['getVals']['subCatId']."'  ";
}

if( isset($_SESSION['getVals']['suppId']) && $_SESSION['getVals']['suppId'])
{


    $sql = " SELECT productId FROM tbl_productsuppliers WHERE supplierId = '".$_SESSION['getVals']['suppId']."'  AND account_id = '".$_SESSION['accountId']."' ";
    $sqlRes = mysqli_query($con, $sql);
    
    $tpIdArr=[];
    while($resRow = mysqli_fetch_array($sqlRes))
    {    
        $tpIdArr[] = $resRow['productId'];   
    }

    $tpId = implode(',', $tpIdArr);


    $cond .= " AND tp.ID IN(".$tpId.") ";
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



WHERE tp.account_id ='".$_SESSION['accountId']."' ". $cond . " GROUP BY tp.id ORDER by s.id DESC ";

$stockMainQry = mysqli_query($con, $sql);

//End stock lists

//get store permission

if (!empty(get_store_permission($_SESSION['designation_id'], $_SESSION['accountId']))) {
    
    $storeCond .= " AND dssp.designation_id = '".$_SESSION['designation_id']."' AND dssp.type = 'stock' AND dssp.designation_section_permission_id = '5'  ";
}

//Store details queries goes here
$sqlSet = " SELECT DISTINCT(s.id),s.* FROM tbl_stores s

LEFT JOIN tbl_designation_sub_section_permission dssp
ON(s.id=dssp.type_id) AND s.account_id=dssp.account_Id 

WHERE s.account_id = '".$_SESSION['accountId']."' ".$storeCond."   ";
$storeQry = mysqli_query($con, $sqlSet);


$sql = "SELECT SUM(stockValue) totalstockValue  FROM tbl_stocks s 

INNER JOIN tbl_products tp ON(s.pId = tp.id) AND tp.status=1 AND s.account_id = tp.account_id

LEFT JOIN tbl_category c ON (c.id = tp.catId) AND c.account_id = tp.account_id

LEFT JOIN tbl_stores st ON(st.id = tp.storageDeptId) AND st.account_id = tp.account_id

LEFT JOIN tbl_designation_sub_section_permission dssp
ON(st.id=dssp.type_id) AND st.account_id=dssp.account_Id

WHERE 1=1 AND s.account_id = '".$_SESSION['accountId']."' ".$storeCond." ".$cond;
$resQry = mysqli_query($con, $sql);
$storeResRow = mysqli_fetch_array($resQry);

// Get full address and their logo of client
$sql = " SELECT c.name AS countryName, cl.* FROM tbl_client cl
LEFT JOIN tbl_country c ON(cl.country=c.id)
WHERE cl.id = '".$_SESSION['accountId']."' ";
$result = mysqli_query($con, $sql);
$clientDetRow = mysqli_fetch_array($result);


$sql = "SELECT * FROM tbl_user  WHERE id = '".$_SESSION['id']."' AND account_id = '".$_SESSION['accountId']."'  ";
$result = mysqli_query($con, $sql);
$userDetails = mysqli_fetch_array($result);
$stockUserFilterFields = $userDetails['stockUserFilterFields'] ?    explode(',',$userDetails['stockUserFilterFields']) : null;

$stockTakeCnt = getMobileStockTakeCount($_SESSION['filterByStorage'], 1);

$content = '<div class="modal-header pb-3">
                    <div class="d-md-flex align-items-center justify-content-between w-100 ">
                        <div class="d-flex align-items-start w-100 gap-3 w-auto mb-md-0 mb-2 modal-head-btn">
                        
                                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#modalfiltertop">
                                    <i class="fa fa-filter"></i>
                                </button>
                            
                                <div class="collapse" id="modalfiltertop">
                                    <div class="d-flex gap-3 modal-head-row">
                                    

                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        '. showOtherLangText('Headers') .'<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="form-check-input" value="1">
                                                <span class="fs-13">'. showOtherLangText('Check All') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="2">
                                                <span class="fs-13">'. showOtherLangText('Address') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="logo" class="form-check-input" value="4">
                                                <span class="fs-13">'. showOtherLangText('Logo') .'</span>
                                            </li>
                                        </ul>
                                    </div>                                  

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        Summary<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="form-check-input" value="1">
                                                <span class="fs-13">'. showOtherLangText('Check All') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="issuedIn" class="form-check-input" value="2">
                                                <span class="fs-13">'. showOtherLangText('Stores') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="defaultCurrency" class="form-check-input" value="3">
                                                <span class="fs-13">'. showOtherLangText('Total Price') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="otherCurrency" class="form-check-input" value="4">
                                                <span class="fs-13">'. showOtherLangText('Stock Take') .'</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        Item Table<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="form-check-input" value="1">
                                                <span class="fs-13">'. showOtherLangText('Check All') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="taskNo" class="form-check-input" value="2">
                                                <span class="fs-13">'. showOtherLangText('Photo') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="date" class="form-check-input" value="3">
                                                <span class="fs-13">'. showOtherLangText('Item') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="user" class="form-check-input" value="4">
                                                <span class="fs-13">'. showOtherLangText('BarCode') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="supInvoiceNo" class="form-check-input" value="5">
                                                <span class="fs-13">'. showOtherLangText('Quantity') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="type" class="form-check-input" value="6">
                                                <span class="fs-13">'. showOtherLangText('Requests Qty') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="referTo" class="form-check-input" value="7">
                                                <span class="fs-13">'. showOtherLangText('Available Qty') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="value" class="form-check-input" value="8">
                                                <span class="fs-13">'. showOtherLangText('Last Price') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="defaultCurrencyTotal" class="form-check-input" value="9">
                                                <span class="fs-13">'. showOtherLangText('Stock Price') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="secondCurrencyTotal" class="form-check-input" value="10">
                                                <span class="fs-13">'. showOtherLangText('Stock Value') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="status" class="form-check-input" value="12">
                                                <span class="fs-13">'. showOtherLangText('Sub Category') .'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="paymentNo" class="form-check-input" value="12">
                                                <span class="fs-13">'. showOtherLangText('Supplier') .'</span>
                                            </li>
                                        </ul>
                                    </div>

                                    </div>
                                
                                </div>

                        

                        </div>
                        <a href="stockviewpdf.html" target=" " class="btn"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
                    </div>
                </div> 
                <div class="modal-body px-2 py-3">
                    <div class="row pb-3">
                        <div class="col-6">
                            <div class="modal-address ">
                                <h6 class="semibold fs-14">'. $clientDetRow['accountName'] .'</h6>
                                <div class="fs-13 ">
                                    <p>'. $clientDetRow['address_one'] .'</p>
                                    <p>'. $clientDetRow['address_two'] .'</p>
                                    <p>'. $clientDetRow['city'].', '.$clientDetRow['countryName'] .'</p>
                                    <p>'. $clientDetRow['email'].'</p>
                                    <p>'. $clientDetRow['phone'].'</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <div class="modal-logo">';
                                if($clientDetRow['logo'] !='' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath.'/clientLogo/'.$clientDetRow['logo'] ))
                        {  
                            $content .=  '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/clientLogo/'.$clientDetRow['logo'].'" width="100" height="100">';
                        }
                        else
                        {
                            $content .=  '<img src="uploads/pdf-logo-sample.png" alt="Logo">';
                        }
                       $content .=   '</div>
                        </div>
                    </div>

                    <div class="model-title-with-date">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h6 class="semibold">'. showOtherLangText('Stock View') .'</h6>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-end fs-15">'. date('d/m/Y') .'</p>
                            </div>
                        </div>
                    </div>

                    <div class="summery-row">
                        <div class="row">
                            <div class="storesection pe-6 w-100">
                                <div class="modal-table fs-12 w-100">
                                  
                                    <div class="table-row thead">
                                        <div class="table-cell">'. showOtherLangText('Stores') .'</div>
                                        <div class="table-cell">'. showOtherLangText('Total Price') .'</div>';
                                      if($stockTakeCnt > 0)
                                     {
                                     $content .= '<div class="table-cell">'. showOtherLangText('Stock Take') .'</div>';
                                     }
                                    $content .= '</div>';
                                    while($storageDeptRow = mysqli_fetch_array($storeQry))
                                    {
                                    $totalstockValue=getStockTotalOfStore($storageDeptRow['id'], $cond);
                                    $content .= '<div class="table-row">
                                           <div class="table-cell">'. $storageDeptRow['name'] .' </div>
                                          <div class="table-cell">'. getPriceWithCur($totalstockValue,$getDefCurDet['curCode']).'</div>';
                                    $content .= '<div class="table-cell">'. getMobileStockTakeCount($storageDeptRow['id']).'</div>
                                         </div>';
                                    }
                        
                                $content .= '</div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="overflowTable"> 
                        <div class="modal-table fs-12 w-100 mt-4">
                            <div class="table-row thead">
                                <div class="table-cell">#</div>
                                <div class="table-cell"><span class="item2 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(1, $stockUserFilterFields) ? 'style="display:none;"' : '').'>Photo</span></div>';
                         $content .=  '<div class="table-cell"><span class="item3 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(2, $stockUserFilterFields) ? 'style="display:none;"' : '').'>'.showOtherLangText('Item') .'</span><span class="slashItem itemSectionPart">/</span><span class="itemSectionPart item4" '.(isset($stockUserFilterFields) && !in_array(3, $stockUserFilterFields) ? 'style="display:none;"' : '').'>'. showOtherLangText('BarCode').'</span></div>';

                        $content .=  '<div class="table-cell"><span class="item5 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(12, $stockUserFilterFields) ? 'style="display:none;"' : '').'>Quantity</span></div>';
                          $content .=  '<div class="table-cell"><span class="item6 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(13, $stockUserFilterFields) ? 'style="display:none;"' : '').'>Req Qty</span></div>';
                           $content .= '<div class="table-cell"><span class="item7 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(14, $stockUserFilterFields) ? 'style="display:none;"' : '').'>Avail Qty</span></div>';
                            $content .= '<div class="table-cell"><span class="item8 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(15, $stockUserFilterFields) ? 'style="display:none;"' : '').'>Last Price</span></div>';
                            $content .= '<div class="table-cell"><span class="item9 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(17, $stockUserFilterFields) ? 'style="display:none;"' : '').'>Stock Price</span></div>';
                            $content .= '<div class="table-cell"><span class="item10 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(16, $stockUserFilterFields) ? 'style="display:none;"' : '').'>Stock Value</span></div>';
                             $content .= '<div class="table-cell"><span class="item11 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(7, $stockUserFilterFields) ? 'style="display:none;"' : '').'>Sub Category</span></div>';
                             $content .= '<div class="table-cell"><span class="item12 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(8, $stockUserFilterFields) ? 'style="display:none;"' : '').'>Supplier</span></div>';
                             $content .= '</div>';
                              //get confirmed requsitions total qty of each productd
                            $productsConfirmedQtyArr = getConfirmTotalQtyReq($_SESSION['accountId']);
                        //end get confirmed requsitions total qty of each productd

                        $_SESSION['stockRows'] = [];
                        $i=0;
                        while($row = mysqli_fetch_array($stockMainQry) )
                        {   
                        
                            $_SESSION['stockRows'][] = $row;
                            
                            $i++;
                            $totalProQty = isset($productsConfirmedQtyArr[$row['id']]) ? $productsConfirmedQtyArr[$row['id']] : 0;
                            if ($totalProQty > 0) 
                            {
                                $totalTempProQty = $totalProQty;
                            }
                            else
                            {
                                $totalTempProQty = 0;
                            }

                            $supNames = $row['suppls'];
                            $catNames = ($row['childCatName'] == 'z' ? '' :  $row['childCatName']);

                             $img = '';
                            if( $row['imgName'] != '' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$row['imgName'] )  )
                             {  
                                $img = '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$row['imgName'].'" width="40" height="40">';
                             }
                             $content .= '<div class="table-row">
                                <div class="table-cell">'. $i .'</div>
                                <div class="table-cell table-cell-photo"><span class="item2 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(1, $stockUserFilterFields) ? 'style="display:none;"' : '').'>'. $img.'</span></div>
                                <div class="table-cell"><span class="item3 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(2, $stockUserFilterFields) ? 'style="display:none;"' : '').'>'. $row['itemName'] .'</span> <br> 
                                <span class="item4 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(3, $stockUserFilterFields) ? 'style="display:none;"' : '').'>'. $row['barCode'] .'</span></div>
                                <div class="table-cell font-bold"><span class="item5 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(12, $stockUserFilterFields) ? 'style="display:none;"' : '').'>'. $row['stockQty']." ". $row['countingUnit'] .'</span></div>
                                <div class="table-cell"><span class="item6 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(13, $stockUserFilterFields) ? 'style="display:none;"' : '').'>'. $totalTempProQty ." ". $row['countingUnit'] .'</span></div>
                                <div class="table-cell font-bold"><span class="item7 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(14, $stockUserFilterFields) ? 'style="display:none;"' : '').'>'. ($row['stockQty']-$totalTempProQty) ." ". $row['countingUnit'] .'</span></div>
                                <div class="table-cell"><span class="item8 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(15, $stockUserFilterFields) ? 'style="display:none;"' : '').'>'. getPrice($row['stockLastPrice']) ." ".$getDefCurDet['curCode'] .'</span></div>
                                <div class="table-cell"><span class="item9 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(17, $stockUserFilterFields) ? 'style="display:none;"' : '').'>'. getPrice($row['sPrice']) ." ".$getDefCurDet['curCode'] .'</span></div>
                                <div class="table-cell"><span class="item10 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(16, $stockUserFilterFields) ? 'style="display:none;"' : '').'>'. getPrice($row['stockValue']) ." ".$getDefCurDet['curCode'] .'</span></div>
                                <div class="table-cell"><span class="item11 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(7, $stockUserFilterFields) ? 'style="display:none;"' : '').'>'. $catNames.'</span></div>
                                <div class="table-cell"><span class="item12 itemSectionPart" '.(isset($stockUserFilterFields) && !in_array(8, $stockUserFilterFields) ? 'style="display:none;"' : '').'>'. $supNames.'</span></div>
                            </div>';
                        }
                            
                      $content .=   '</div>
                    </div>
                </div>';
echo $content;