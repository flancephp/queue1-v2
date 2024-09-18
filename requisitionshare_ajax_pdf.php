<?php
include('inc/dbConfig.php'); //connection details
//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


// test
// Get full address and their logo of client
$sql = " SELECT c.name AS countryName, cl.* FROM tbl_client cl
LEFT JOIN tbl_country c ON(cl.country=c.id)
WHERE cl.id = '".$_SESSION['accountId']."' ";
$result = mysqli_query($con, $sql);
$clientDetRow = mysqli_fetch_array($result);

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

// Get column permission of requisition
$getColumnPermission = get_column_permission($_SESSION['designation_id'], $_SESSION['accountId'], 2);


$sql = "SELECT * FROM tbl_orders  WHERE id = '".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."'  ";
		$qry = mysqli_query($con, $sql);
		$ordDet = mysqli_fetch_array($qry);
       

        $sqlCustomItems = "SELECT cif.itemName, cif.unit,cif.amt, tod.* FROM tbl_order_details tod 
        INNER JOIN tbl_custom_items_fee cif ON(tod.customChargeId = cif.id) AND tod.account_id = cif.account_id
        WHERE tod.ordId = '".$_POST['orderId']."'  AND tod.account_id = '".$_SESSION['accountId']."' and tod.customChargeType=1 ORDER BY cif.itemName ";
        $otherChrgQry=mysqli_query($con, $sqlCustomItems);  

		$sql = "SELECT od.*, tp.itemName, tp.barCode, tp.imgName, IF(u.name!='', u.name, tp.unitC) countingUnit FROM tbl_order_details od 
		INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
		LEFT JOIN tbl_units u ON(u.id = tp.unitC) AND u.account_id = tp.account_id
		WHERE od.ordId = '".$_POST['orderId']."' AND od.account_id = '".$_SESSION['accountId']."' and od.qty > 0 ";
		$proresultSet = mysqli_query($con, $sql);

		
		$sqlSet = " SELECT GROUP_CONCAT(DISTINCT(d.name)) departments FROM tbl_orders o 
		INNER JOIN tbl_department d
			ON(d.id = o.deptId) AND d.account_id = o.account_id
				WHERE o.id=".$ordDet['id']."  AND d.account_id = '".$_SESSION['accountId']."' ";
		$resultSet = mysqli_query($con, $sqlSet);
		$departRow = mysqli_fetch_array($resultSet);
		
		$sqlSet = " SELECT * FROM tbl_deptusers WHERE id = '".$ordDet['recMemberId']."' AND account_id = '".$_SESSION['accountId']."'   ";
		$resultSet = mysqli_query($con, $sqlSet);
		$mebRes = mysqli_fetch_array($resultSet);
		
		$sqlSet = " SELECT * FROM tbl_user WHERE id = '".$ordDet['orderBy']."' AND account_id = '".$_SESSION['accountId']."'   ";
		$resultSet = mysqli_query($con, $sqlSet);
		$orderBy = mysqli_fetch_array($resultSet);

        $orderJourneyQrySql = " SELECT j.*, u.name, d.designation_name FROM tbl_order_journey j 
        INNER JOIN tbl_user u ON(u.id = j.userBy)
        INNER JOIN tbl_designation d ON(u.designation_id = d.id) AND u.account_id = d.account_id
        
         WHERE j.orderId = '".$_REQUEST['orderId']."'  AND j.account_id = '".$_SESSION['accountId']."'  ";
		$orderJourneyQry = mysqli_query($con, $orderJourneyQrySql);                 

    

        //$content  .= '<div style="width:750px" class="container mnPdf_Dv">';

        $content  .= '<form action="req_pdf_download.php" target="_blank" method="get">
        <input type="hidden" name="history_pdf_page" value="0" id="history_pdf_page"/>  
        <input type="hidden" name="orderId" value="'.$_POST['orderId'].'" />
    
    
        <div class="modal-header filter-header-top position-relative">
                                   <div class="mb-modal-close-icon">  <button type="button" class="btn-close m-0 d-lg-none" data-bs-dismiss="modal" aria-label="Close" fdprocessedid="mvv0xh"></button></div>
                                <div class="d-flex align-items-center justify-content-between w-100 ">
                                    <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#modalfiltertop">
                                        <i class="fa fa-filter"></i>
                                    </button>
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <button type="submit" class="btn btn-primary dwnBtn"><span class="align-middle">'.showOtherLangText('Press').'</span> <i class="fa-solid fa-download ps-1"></i></button>
                                     </div>
                                </div>
                                <div class="collapse" id="modalfiltertop">
                                    <div class="d-flex gap-1 gap-md-3 flex-wrap modal-head-row">
                                    

                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        '.showOtherLangText('Headers').'<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="headerCheckAll headChk-All form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Check All').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox"  name="address" class="headCheckbox header-address form-check-input" value="1" onClick="hideCheckbox(\'adrsClm\')">
                                                <span class="fs-13">'.showOtherLangText('Address').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="orderDetails" class="headCheckbox header-address form-check-input" value="1" onClick="hideCheckbox(\'orderDetailsText\')">
                                                <span class="fs-13">'.showOtherLangText('Requisition details').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox"  name="logo" class="form-check-input headCheckbox header-logo" value="1" onClick="hideCheckbox(\'logoClm\')">
                                                <span class="fs-13">'.showOtherLangText('Logo').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox"  name="currentDate" class="headCheckbox header-currentDate form-check-input" value="1" onClick="hideCheckbox(\'currentDate\')">
                                                <span class="fs-13">'.showOtherLangText('Current Date').'</span>
                                            </li>
                                        </ul>
                                    </div>                                  

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">'.showOtherLangText('Summary').'
                                        <i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="smryChk-All form-check-input" 
                                                onClick="hideCheckbox(\'smryDiv\')" value="1">
                                                <span class="fs-13">'.showOtherLangText('Check All').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="taskNo" class="smryCheckbox form-check-input"
                                                onClick="showHideByClassSummary(\'taskNo\');" value="1">
                                                <span class="fs-13">'.showOtherLangText('Task No.').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="department"
                                                onClick="showHideByClassSummary(\'department\');"  class="smryCheckbox form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Department').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="member"
                                                onClick="showHideByClassSummary(\'member\');" class="smryCheckbox form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Member').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="amount" 
                                                onClick="showHideByClassSummary(\'amountSections\');" class="smryCheckbox form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Amounts').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" '.($ordDet['invNo'] != '' ? 'checked="checked"' : 'disabled').' name="Invoice" 
                                                onClick="showHideByClassSummary(\'smrySuplr\')" class="smryCheckbox summary-invoice  form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('Invoice').'</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">'.showOtherLangText('
                                        Item Table').'<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" onClick="hideCheckbox(\'itemDiv\')" name="checkAll" class="itemChk-All form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('
                                        Check All').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox"  name="photo" onClick="showHideByClassItems(\'photo\');" class="itmTblCheckbox item-name form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('
                                        Photo').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="itemName" onClick="showHideByClassItems(\'itmProd\');" class="itmTblCheckbox item-name form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('
                                        Item Name').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="barcode" class="form-check-input itmTblCheckbox item-barcode" onClick="showHideByClassItems(\'itmCode\');" value="1">
                                        <span class="fs-13">'.showOtherLangText('
                                        Barcode').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="price" class="form-check-input itmTblCheckbox item-price" onClick="showHideByClassItems(\'itmPrc\');" value="1">
                                                <span class="fs-13">'.showOtherLangText('
                                        Price').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="unit" onClick="showHideByClassItems(\'itmPrcunit\');" class="itmTblCheckbox item-unit form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('
                                        Unit').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="reqQty" onClick="showHideByClassItems(\'itmRecqty\');" class="itmTblCheckbox item-purchase-qty form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('
                                        Requested Qty').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked"
                                                onClick="showHideByClassItems(\'itmPurqty\');"  name="qty" class="itmTblCheckbox item-receive-qty form-check-input" value="1">
                                                <span class="fs-13">'.showOtherLangText('
                                        Qty').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="total" class="form-check-input itmTblCheckbox item-total"
                                                onClick="showHideByClassItems(\'itmTotal\');" value="1">
                                                <span class="fs-13">'.showOtherLangText('
                                        Total').'</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="note" class="itmTblCheckbox item-note form-check-input"
                                                onClick="showHideByClassItems(\'itmNote\');" value="1">
                                                <span class="fs-13">'.showOtherLangText('
                                        Note').'</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">'.showOtherLangText('
                                        Task Record').'<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="taskRecord" class="form-check-input"
                                                onClick="hideCheckbox(\'taskDiv\')"
                                                id="taskRecord" value="1">
                                                <span class="fs-13">'.showOtherLangText('
                                        Task Record').'</span>
                                            </li>
                                        </ul>
                                    </div>

                                    </div>
                                
                                </div>
                            </div></form>'; 
                      $content  .= '<div class="modal-body px-2 py-3">
                                <div class="row pb-3">
                                    <div class="col-md-4">
                                        <div id="adrsClm"  style="display:none;" class="headerTxt modal-address">
                                            <h6 class="semibold fs-14">'.$clientDetRow['accountName'].'</h6>
                                            <div class="fs-13 ">
                                                <p>'.$clientDetRow['address_one'].'</p>
                                                <p>'.$clientDetRow['address_two'].'</p>
                                                <p>'.$clientDetRow['city'].', '.$clientDetRow['countryName'].'</p>
                                                <p>'.$clientDetRow['email'].'</p>
                                                <p>'.$clientDetRow['phone'].'</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h4 id="orderDetailsText" class="headerTxt orderDetailsText text-center semibold">'.showOtherLangText('Requisition Details').'</h4>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <div class="modal-logo"><div id="logoClm" class="headerTxt" style="display:none;">';
                                 if( file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/clientLogo/".$clientDetRow["logo"] ))
                                {  
                                    $content .= '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/clientLogo/'.$clientDetRow['logo'].'" style="object-fit: scale-down; height: 50px; width: auto;">';
                                }
                                else
                                {
                                    $content .= '<img src="'.$siteUrl.'uploads/pdf-logo-sample.png" alt="Logo" style="object-fit: scale-down; height: 50px; width: auto;">';
                                }
                                   $content  .=     '</div></div>
                                        <div class="modal-date pt-2">
                                            <p style="display:none;" class="headerTxt currentDate" id="currentDate">'.date('d/m/Y').'</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="overflow-aut">
                                <div class="modal-table fs-12 w-100">
                                    <div class="table-row header-row">
                                        <div class="table-cell medium"><span  class="taskNo smryHead">'.showOtherLangText('Task No.').'</span></div>
                                        <div class="table-cell medium"><span  class="department smryHead">'.showOtherLangText('Department').'</span></div>
                                        <div class="table-cell medium"><span  class="member smryHead">'.showOtherLangText('Member').'</span></div>
                                        <div class="table-cell medium order-req-dtl-total-head">
                                            <div class="d-flex">
                                                <div class="col-7"></div>
                                                <div class="col-5">
                                                    <span  class="total amountSections smryHead">'.showOtherLangText('Total').'</span>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="table-row thead">
                                        <div class="table-cell"><span class="taskNo smryHead">'.$ordDet['ordNumber'].'</span></div>
                                        <div class="table-cell"><span  class="department smryHead">'.$departRow['departments'].'</span></div>
                                        <div class="table-cell"><span  class="member smryHead">'.$mebRes['name'].'</span></div>
                                        <div class="table-cell table-amount-same-alignment">
                                            <div class="d-flex">
                                                <div class="col-7"></div>
                                                <div class="col-5"><span  class="total amountSections smryHead">'.getNumFormtPrice($ordDet['ordAmt'], $getDefCurDet['curCode']).'</span></div>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="table-row">';
                                     if( $ordDet['invNo'] != '')
                                    {
                                       $content .= '<div class="table-cell" style="width: 25%;">
                                            <div class="sub-table w-100" style="">
                                                <div class="table-row">
                                                    <div class="table-cell"># Invoice</div>
                                                    <div class="table-cell">'.$ordDet['invNo'].'</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-cell" style="width: 25%;"></div>
                                        <div class="table-cell" style="width: 25%;"></div>
                                        <div class="table-cell" style="width: 25%;"></div>';
                                    }

                                     $content .= '</div><div class="table-row"><div class="table-cell"></div><div class="table-cell"></div><div class="table-cell"></div>  <div class="table-cell">';
                                     $sqlSet="SELECT SUM(totalAmt) AS sum1, SUM(curAmt) AS totalAmtOther FROM tbl_order_details WHERE ordId='".$_REQUEST['orderId']."' AND account_id = '".$_SESSION['accountId']."'  AND (customChargeType='1' OR customChargeType='0')";
                                 $resultSet = mysqli_query($con, $sqlSet);
                                 $chargeRow = mysqli_fetch_array($resultSet);   
                                 $chargePrice=$chargeRow['sum1'];
                                 $chargePriceOther=$chargeRow['totalAmtOther'];
                                 
         
                                 //to find order level charge
                                 $ordCount="SELECT * from tbl_order_details where ordId='".$_REQUEST['orderId']."'  AND account_id = '".$_SESSION['accountId']."' AND customChargeType='2' ";
                                 $ordCountResult = mysqli_query($con, $ordCount);
                                 $ordCountRow = mysqli_num_rows($ordCountResult);
                                 $showGrandTotal = false;
    
                                 if ($ordCountRow > 0)
                                 { 
    
                                    $showGrandTotal = true;
                                     $content .= '<div class="d-flex set__left__padding"> 
                                     <div class="col-7"><span class="amountSections smryHead" >'.showOtherLangText('Sub Total').'</span></div>
                                     <div class="col-5"><span class="smryDef_Val amountSections smryHead" >'.getPriceWithCur($chargePrice, $getDefCurDet['curCode']).'
                                         
                                    </div></div>';
                                }
                                 $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
                                 INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                                 WHERE od.ordId = '".$_REQUEST['orderId']."' AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";
         
                                 $ordQry = mysqli_query($con, $sql);
                         
                     
                         $fixedCharges = 0;
                         $fixedChargesOther = 0;
                         while($row = mysqli_fetch_array($ordQry))//show here order level charges
                         {
                                 $fixedCharges += $row['price'];
                                 $fixedChargesOther += $row['curAmt'];
                                   $content .= '<div class="d-flex set__left__padding"> 
                                   <div class="col-7"><span class="amountSections smryHead" >'.$row['feeName'].'</span></div>
                                   <div class="col-5"><span class="smryDef_Val amountSections smryHead" >'.getPriceWithCur($row['price'], $getDefCurDet['curCode']).'
                                         
                                    </div></div>';
                          }

                           $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
                                 INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                                 WHERE od.ordId = '".$_REQUEST['orderId']."' AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
                                 $ordQry = mysqli_query($con, $sql);
                         
                         $perCharges = 0;
                         $perChargesOther = 0;
                         while($row = mysqli_fetch_array($ordQry))//show here order level charges
                         {
                             
                                 $perCharges += $row['price'];
                             $perChargesOther = $row['curAmt'];
         
         
                             $calDiscount = ($chargePrice*$row['price']/100);
                             $calDiscountOther = ($chargePriceOther*$row['price']/100);
                              $content .= '<div class="d-flex set__left__padding"> 
                              <div class="col-7"><span class="amountSections smryHead" >'.$row['feeName'].'</span></div>
                              <div class="col-5"><span class="smryDef_Val amountSections smryHead" >'.getPriceWithCur($calDiscount, $getDefCurDet['curCode']).'</span></div>
                                         
                                    </div>';


                           } 

                              $totalCalDiscount =($chargePrice*$perCharges/100);//total discount feeType=3
                             $totalCalDiscountOther = ($chargePriceOther*$perCharges/100); 
                      
                       
                         $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
                                 INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                                 WHERE od.ordId = '".$_REQUEST['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."' and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
                         $ordQry = mysqli_query($con, $sql);
                         
                         $taxCharges = 0;
                         $totalTaxChargesOther = 0;
                         while($row = mysqli_fetch_array($ordQry))//show here order level charges
                         {
                             $taxCharges += $row['price'];
                             $totalTaxChargesOther += $row['curAmt'];
         
                             $calTax = (($chargePrice+ $fixedCharges+$totalCalDiscount)*$row['price']/100);
                             $calTaxOther = (($chargePriceOther+ $fixedChargesOther+$totalCalDiscountOther)*$row['price']/100);
                              $content .= '<div class="d-flex set__left__padding"> 
                              <div class="col-7"><span class="amountSections smryHead" >'.$row['feeName'].'</span></div>
                              <div class="col-5"><span class="smryDef_Val amountSections smryHead" >'.getPriceWithCur($calTax, $getDefCurDet['curCode']).'
          </span></div>
                                         
                                    </div>';

                               }

                                 $totalTax =(($chargePrice+$fixedCharges+$totalCalDiscount)*$taxCharges/100);//total tax feeType=1
                              $totalTaxOther =(($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther)*$taxCharges/100);
         
         
                             $netTotalAmt= ($chargePrice+$fixedCharges+$totalCalDiscount+$totalTax);
                             $netTotalAmtOther= ($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther+$totalTaxOther);
         
                             if($showGrandTotal)
                             {
                              $content .= '<div class="d-flex set__left__padding"> 
                              <div class="col-7"><span class="amountSections smryHead" >'.showOtherLangText('Grand Total').'</span></div>
                              <div class="col-5"><span class="smryDef_Val amountSections smryHead" >'.getPriceWithCur($netTotalAmt, $getDefCurDet['curCode']).'
          </span></div>
                                         
                                    </div>';
                             }


                                $content .= '</div></div></div></div>';

                             $content .= '<div class="overflow-aut"><div class="modal-table fs-12 w-100 mt-4"><div class="table-row thead">';
                              $content .= '<div class="table-cell">#</div>';
                              $content .= '<div class="table-cell"><span style="display:none;"   class="photo">'.showOtherLangText('Photo').'</span></div>';
                              $content .= '<div class="table-cell"><span   class="itmProd">'.showOtherLangText('Item').'</span></div>';
                              $content .= '<div class="table-cell"><span   class="itmCode">'.showOtherLangText('Barcode').'</span></div>';
                              $content .= '<div class="table-cell"><span   class="itmPrc">Price('.$getDefCurDet['curCode'].')</span></div>';
                              $content .= '<div class="table-cell"><span   class="itmPrcunit">'.showOtherLangText('C.Unit').'</span></div>';
                              $content .= '<div class="table-cell"><span   class="itmRecqty">'.showOtherLangText('Req Qty').'</span></div>';
                              $content .= '<div class="table-cell"><span   class="itmPurqty">'.showOtherLangText('Qty').'</span></div>';
                              $content .= '<div class="table-cell"><span   class="itmTotal">'.showOtherLangText('Total').'('.$getDefCurDet['curCode'].')</span></div>';
                              $content .= '<div class="table-cell"><span   class="itmNote">'.showOtherLangText('Note').'</span></div>';
                              $content .= '</div>';
                              while($row = mysqli_fetch_array($proresultSet) )
                        {
                          $i++;
                              $content .=  '<div class="table-row">';
                              $content .=  '<div class="table-cell">'.$i.'</div>';
                               $img = '';
                if( $row['imgName'] != '' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$row['imgName'] )  )
                 {  
                    $img = '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$row['imgName'].'" width="60" height="60">';
                 }
                        $content .=  '<div class="table-cell"><span class="photo" style="display:none;" >'.$img.'</span></div>';
                        $content .=  '<div class="table-cell"><span class="itmProd">'.$row['itemName'].'</span></div>';
                        $content .=  '<div class="table-cell"><span    class="itmCode">'.$row['barCode'].'</span></div>';
                        if($getColumnPermission['item_price'] == 1){
                        $content .=   '<div class="table-cell"> <span    class="itmPrc">'.getPriceWithCur($row['price'], $getDefCurDet['curCode']).'</span></div>';
                        }
                        $content .=   '<div class="table-cell"><span    class="itmPrcunit">'.$row['countingUnit'].'</span></div>';
                        $content .=   '<div class="table-cell"><span    class="itmRecqty">'.$row['requestedQty'].'</span></div>';
                        $content .=   '<div class="table-cell"><span    class="itmPurqty">'.$row['qty'].'</span></div>';
                        if($getColumnPermission['item_price'] == 1){
                        $content .=   '<div class="table-cell"> <span    class="itmTotal">'.getPriceWithCur($row['totalAmt'], $getDefCurDet['curCode']).'</span></div>';
                        }

                        $content .=   '<div class="table-cell"><span  class="itmNote">'.$row['note'].'</span></div>';
                        $content .=   '</div>';
                        }
                                    
                                $content .=  '</div>

                                <div id="taskDiv" ><div class="modal-table fs-12 w-100 mt-4">
                                    <div class="table-row thead">
                                        <div class="table-cell">'.showOtherLangText('Status').'</div>
                                        <div class="table-cell">'.showOtherLangText('Date').'</div>
                                        <div class="table-cell">'.showOtherLangText('User').'</div>
                                        <div class="table-cell">'.showOtherLangText('Price').'($)</div>
                                        <div class="table-cell">'.showOtherLangText('Note').'</div>
                                    </div>';
                                     while($orderJourney = mysqli_fetch_array($orderJourneyQry) )
                                    {
                                    $content .= '<div class="table-row">
                                        <div class="table-cell">'.showOtherLangText(ucfirst($orderJourney['action'])).'</div>
                                        <div class="table-cell">'.date('d/m/Y
                    h:iA', strtotime($orderJourney['ordDateTime']) ).'</div>
                                        <div class="table-cell">'.ucfirst($orderJourney['name']).'('.ucfirst($orderJourney['designation_name']).')</div>
                                        <div class="table-cell">'.
                    getPriceWithCur($orderJourney['amount'], $getDefCurDet['curCode']).'</div>
                                        <div class="table-cell">'.ucfirst($orderJourney['notes']).'</div>
                                    </div>';
                                    }
                                $content .= '</div></div>
                            </div>';
    
    echo $content .='</div>';