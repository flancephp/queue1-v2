<?php 

include('inc/dbConfig.php'); //connection details

 //Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

// Get full address and their logo of client
$sql = " SELECT c.name AS countryName, cl.* FROM tbl_client cl
LEFT JOIN tbl_country c ON(cl.country=c.id)
WHERE cl.id = '".$_SESSION['accountId']."' ";
$result = mysqli_query($con, $sql);
$clientDetRow = mysqli_fetch_array($result);




   
        $sql = "SELECT * FROM tbl_orders  WHERE id = '".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."'  ";
            
        $qry = mysqli_query($con, $sql);
        $ordDet = mysqli_fetch_array($qry);
                          
        $sql = "SELECT od.*, tp.itemName, tp.barCode, tp.imgName, IF(u.name!='',u.name,tp.unitP) purchaseUnit FROM tbl_order_details od 
        INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
        LEFT JOIN tbl_units u ON(u.id = tp.unitP) AND u.account_id = tp.account_id
        WHERE od.ordId = '".$_POST['orderId']."' AND od.account_id = '".$_SESSION['accountId']."'  ";

        $proresultSet = mysqli_query($con, $sql);

        $sql = "SELECT cif.itemName, cif.unit, tod.* FROM tbl_order_details tod 
        INNER JOIN tbl_custom_items_fee cif ON(tod.customChargeId = cif.id) AND tod.account_id = cif.account_id
        WHERE tod.ordId = '".$_POST['orderId']."' AND tod.account_id = '".$_SESSION['accountId']."'  and tod.customChargeType=1 ORDER BY cif.itemName ";
        $otherChrgQry=mysqli_query($con, $sql);
        
        $sqlSet = " SELECT GROUP_CONCAT(DISTINCT(s.name)) suppliers FROM tbl_order_details d 
        INNER JOIN tbl_orders o
        ON(o.id = d.ordId) AND o.account_id = d.account_id
        INNER JOIN tbl_suppliers s
        ON(o.supplierId = s.id) AND o.account_id = s.account_id
        WHERE d.ordId IN( ".$_POST['orderId']." ) AND s.account_id = '".$_SESSION['accountId']."'  GROUP BY d.ordId ";
        $resultSet = mysqli_query($con, $sqlSet);
        $supRow = mysqli_fetch_array($resultSet);
        $suppliers = $supRow['suppliers'];
        
        $sqlSet = " SELECT * FROM tbl_user WHERE id = '".$ordDet['orderBy']."'  AND account_id = '".$_SESSION['accountId']."'  ";
        $resultSet = mysqli_query($con, $sqlSet);
        $orderBy = mysqli_fetch_array($resultSet);
        
        

        $sqlSet = " SELECT j.*, u.name, d.designation_name FROM tbl_order_journey j 
        INNER JOIN tbl_user u ON(u.id = j.userBy)
        INNER JOIN tbl_designation d ON(u.designation_id = d.id) AND u.account_id = d.account_id
        
         WHERE j.orderId = '".$_POST['orderId']."'  AND j.account_id = '".$_SESSION['accountId']."'  ";
        $orderJourneyQry = mysqli_query($con, $sqlSet);


        if($ordDet['ordCurId'] > 0)
        {
            $res = mysqli_query($con, " SELECT * from tbl_currency WHERE id='".$ordDet['ordCurId']."'  AND account_id = '".$_SESSION['accountId']."' ");
            $curDet = mysqli_fetch_array($res);
        }   
$content  = '<form action="ordershare_pdf_download.php" target="_blank" method="get">
    <input type="hidden" name="history_pdf_page" value="0" id="history_pdf_page"/> 
    <input type="hidden" name="orderId" value="'.$_POST['orderId'].'" />
        <input type="hidden" name="isSupDet" value="'.$_POST['isSupDet'].'" />
        <input type="hidden" name="ordCurId" id="ordCurId" value="'.$ordDet['ordCurId'].'" />';
$content .= '<div class="modal-header pb-3">
                                <div class="d-md-flex align-items-center justify-content-between w-100 ">
                                    <div class="d-flex align-items-start w-100 gap-3 w-auto mb-md-0 mb-2 modal-head-btn">
                                    
                                            <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#modalfiltertop">
                                                <i class="fa fa-filter"></i>
                                            </button>
                                        
                                            <div class="collapse" id="modalfiltertop">
                                                <div class="d-flex gap-3 modal-head-row">
                                                

                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Headers<i class="fa-solid fa-angle-down ps-1"></i>
                                                    </button>
                                                    <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="checkAll" class="form-check-input" value="1">
                                                            <span class="fs-13">Check All</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="address" class="form-check-input" value="2">
                                                            <span class="fs-13">Address</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="orderDetails" class="form-check-input" value="3">
                                                            <span class="fs-13">Order details</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="logo" class="form-check-input" value="4">
                                                            <span class="fs-13">Logo</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="currentDate" class="form-check-input" value="5">
                                                            <span class="fs-13">Current Date</span>
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
                                                            <span class="fs-13">Check All</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="defaultCurrencyAmount" class="form-check-input" value="2">
                                                            <span class="fs-13">Default Currency Amount</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="secondCurrencyAmount" class="form-check-input" value="3">
                                                            <span class="fs-13">Second Currency Amount</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="supplierInvoice" class="form-check-input" value="4">
                                                            <span class="fs-13">Supplier Invoice #</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="payment" class="form-check-input" value="5">
                                                            <span class="fs-13">Payment #</span>
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
                                                            <span class="fs-13">Check All</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="Photo" class="form-check-input" value="2">
                                                            <span class="fs-13">Photo</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="itemName" class="form-check-input" value="3">
                                                            <span class="fs-13">Item Name</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="barcode" class="form-check-input" value="4">
                                                            <span class="fs-13">Barcode</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="price" class="form-check-input" value="5">
                                                            <span class="fs-13">Price</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="secondCurrencyPrice" class="form-check-input" value="6">
                                                            <span class="fs-13">Second Currency Price</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="unit" class="form-check-input" value="7">
                                                            <span class="fs-13">Unit</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="qty" class="form-check-input" value="8">
                                                            <span class="fs-13">Qty</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="receivedQty" class="form-check-input" value="9">
                                                            <span class="fs-13">Received Qty</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="total" class="form-check-input" value="10">
                                                            <span class="fs-13">Total</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="secondCurrencyTotal" class="form-check-input" value="11">
                                                            <span class="fs-13">Second Currency Total</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="note" class="form-check-input" value="12">
                                                            <span class="fs-13">Note</span>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class=" dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Task Record<i class="fa-solid fa-angle-down ps-1"></i>
                                                    </button>
                                                    <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="taskRecord" class="form-check-input" value="1">
                                                            <span class="fs-13">Task Record</span>
                                                        </li>
                                                    </ul>
                                                </div>

                                                </div>
                                            
                                            </div>

                                    

                                    </div>
                                    <a href="#" class="btn"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
                                </div>
                            </div></form>';
             $content  .= '<div class="modal-body px-2 py-3">
                                <div class="row pb-3">
                                    <div class="col-md-4">
                                        <div class="modal-address ">
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
                                        <h4 class="text-center semibold">'.showOtherLangText('Order Details').'</h4>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <div class="modal-logo">';
                                        if(!isset($_GET['pdfDownload']) || $clientDetRow["logo"] !='' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/clientLogo/".$clientDetRow["logo"] ))
                                        {  
                                            $content .= '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/clientLogo/'.$clientDetRow['logo'].'" style="object-fit: scale-down; height: 50px; width: auto;">';
                                        }
                                        else
                                        {
                                            $content .= '<img src="'.$siteUrl.'uploads/pdf-logo-sample.png" alt="Logo" style="object-fit: scale-down; height: 50px; width: auto;">';
                                        }
                                     $content .='</div>
                                        <div class="modal-date pt-1">
                                            <p>'.date('d/m/Y').'</p>
                                        </div>
                                    </div>
                                </div>';

                         $content .=   '<div class="modal-table fs-12 w-100">
                                    <div class="table-row header-row">
                                        <div class="table-cell medium">'.showOtherLangText('Task No.').'</div>
                                        <div class="table-cell medium">'.showOtherLangText('Supplier').'</div>
                                        <div class="table-cell medium">'.showOtherLangText('Total').'</div>
                                    </div>
                                    <div class="table-row thead">
                                        <div class="table-cell">'.$ordDet['ordNumber'].'</div>
                                        <div class="table-cell">'.$suppliers.'</div>
                                        <div class="table-row">
                                            <div class="table-cell">'. getNumFormtPrice($ordDet['ordAmt'], $getDefCurDet['curCode']).'</div>
                                            <div class="table-cell">'. showOtherCur($ordDet['ordCurAmt'], $ordDet['ordCurId']).'</div>
                                        </div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell" style="width: 30%;">
                                            <div class="sub-table w-100" style="">
                                                <div class="table-row">
                                                    <div class="table-cell"># Supplier Invoice</div>
                                                    <div class="table-cell">'.$ordDet['invNo'].'</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-cell" style="width: 35%;"></div>
                                        <div class="table-cell" style="width: 35%;"></div>
                                    </div>
                                </div>';

                         $content .=     '<div class="modal-table fs-12 w-100 mt-4">
                                    <div class="table-row thead">
                                        <div class="table-cell">#</div>
                                        <div class="table-cell">Item</div>
                                        <div class="table-cell">Barcode</div>
                                        <div class="table-cell">Price</div>
                                        <div class="table-cell">P. Unit</div>
                                        <div class="table-cell">P. Qty.</div>
                                        <div class="table-cell">Rec Qty.</div>
                                        <div class="table-cell">Total($)</div>
                                        <div class="table-cell">Note</div>
                                    </div>';
                                    $i = 0;
                                    while($row = mysqli_fetch_array($otherChrgQry) )
                                    {
                                        $i++;  
                                $content .= '<div class="table-row">
                                        <div class="table-cell">'.$i.'</div>
                                        <div class="table-cell">'.$row['itemName'].'</div>
                                        <div class="table-cell">'.$row['barCode'].'</div>
                                        <div class="table-cell">'.getPriceWithCur($row['price']*$row['factor'], $getDefCurDet['curCode']).'</div>
                                        <div class="table-cell">'.$row['purchaseUnit'].'</div>
                                        <div class="table-cell">'.$row['qty'].'</div>
                                        <div class="table-cell">'.$row['qtyReceived'].'</div>
                                        <div class="table-cell">'.getPriceWithCur($row['totalAmt'], $getDefCurDet['curCode']).'</div>
                                        <div class="table-cell">'.$row['note'].'</div>
                                    </div>';
                                    }

                                    while($row = mysqli_fetch_array($proresultSet) )
                                    {
                                        $i++;  
                                $content .= '<div class="table-row">
                                        <div class="table-cell">'.$i.'</div>
                                        <div class="table-cell">'.$row['itemName'].'</div>
                                        <div class="table-cell">'.$row['barCode'].'</div>
                                        <div class="table-cell">'.getPriceWithCur($row['price']*$row['factor'], $getDefCurDet['curCode']).'</div>
                                        <div class="table-cell">'.$row['purchaseUnit'].'</div>
                                        <div class="table-cell">'.$row['qty'].'</div>
                                        <div class="table-cell">'.$row['qtyReceived'].'</div>
                                        <div class="table-cell">'.getPriceWithCur($row['totalAmt'], $getDefCurDet['curCode']).'</div>
                                        <div class="table-cell">'.$row['note'].'</div>
                                    </div>';
                                    }
          
                                    
                             $content .= '</div>

                                <div class="modal-table fs-12 w-100 mt-4">
                                    <div class="table-row thead">
                                        <div class="table-cell">Status</div>
                                        <div class="table-cell">Date</div>
                                        <div class="table-cell">User</div>
                                        <div class="table-cell">Price($)</div>
                                        <div class="table-cell">Price(Tsh)</div>
                                        <div class="table-cell">Note</div>
                                    </div>';
                               while($orderJourney = mysqli_fetch_array($orderJourneyQry) )
                              {
                              $content .=  '<div class="table-row">
                                        <div class="table-cell">
                '.ucfirst($orderJourney['action']).'</div>
                                        <div class="table-cell">'.date('d/m/Y
                h:iA', strtotime($orderJourney['ordDateTime']) ).'</div>
                                        <div class="table-cell">
                '.ucfirst($orderJourney['name']).'('.ucfirst($orderJourney['designation_name']).')</div>
                                        <div class="table-cell">'.
                getPriceWithCur($orderJourney['amount'], $getDefCurDet['curCode']).'</div>';
                 if($curDet['curCode'] != '')
            {
                $content .=        '<div class="table-cell">'.
                getPriceWithCur($orderJourney['otherCur'], $curDet['curCode']).'</div>';
             } 
                $content .= '<div class="table-cell">
                '.ucfirst($orderJourney['notes']).'</div>
                                    </div>';
                                }
                                    
                              $content .=    '</div>
                            </div>';
                      $content1 .=  '<div class="table-row thead">
                                        <div class="table-cell">'.$ordDet['ordNumber'].'</div>
                                        <div class="table-cell">'.$suppliers.'</div>
                                        <div class="table-cell">Our Zazibar</div>
                                        <div class="table-cell p-0">
                                            <div class="sub-table w-100" style="display: table;">
                                                <div class="table-row">
                                                    <div class="table-cell" style="width: 40%;"></div>
                                                    <div class="table-cell">'. getNumFormtPrice($ordDet['ordAmt'], $getDefCurDet['curCode']).'</div>
                                                    <div class="table-cell" style="width: 20%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell" style="width: 25%;"></div>
                                        <div class="table-cell" style="width: 25%;"></div>
                                        <div class="table-cell" style="width: 16%;"></div>
                                        <div class="table-cell" style="width: 34%;">
                                            <div class="sub-table w-100" style="display: table;">
                                                <div class="table-row">
                                                    <div class="table-cell">Grand Total</div>
                                                    <div class="table-cell">157 $</div>
                                                    <div class="table-cell">138.888 €</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-table fs-12 w-100 mt-4">
                                    <div class="table-row thead">
                                        <div class="table-cell">#</div>
                                        <div class="table-cell">Item</div>
                                        <div class="table-cell">Barcode</div>
                                        <div class="table-cell">Price</div>
                                        <div class="table-cell">P. Unit</div>
                                        <div class="table-cell">P. Qty.</div>
                                        <div class="table-cell">Rec Qty.</div>
                                        <div class="table-cell">Total($)</div>
                                        <div class="table-cell">Note</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">1</div>
                                        <div class="table-cell">Bitter Lemon 300 Ml</div>
                                        <div class="table-cell">569856285</div>
                                        <div class="table-cell">5.32 $</div>
                                        <div class="table-cell">box</div>
                                        <div class="table-cell">3</div>
                                        <div class="table-cell">3</div>
                                        <div class="table-cell">13.32 $</div>
                                        <div class="table-cell">Lorem Ipsum dolor sit amet</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">1</div>
                                        <div class="table-cell">Bitter Lemon 300 Ml</div>
                                        <div class="table-cell">569856285</div>
                                        <div class="table-cell">5.32 $</div>
                                        <div class="table-cell">box</div>
                                        <div class="table-cell">3</div>
                                        <div class="table-cell">3</div>
                                        <div class="table-cell">13.32 $</div>
                                        <div class="table-cell">Lorem Ipsum dolor sit amet</div>
                                    </div>
                                </div>

                                <div class="modal-table fs-12 w-100 mt-4">
                                    <div class="table-row thead">
                                        <div class="table-cell">Status</div>
                                        <div class="table-cell">Date</div>
                                        <div class="table-cell">User</div>
                                        <div class="table-cell">Price($)</div>
                                        <div class="table-cell">Price(Tsh)</div>
                                        <div class="table-cell">Note</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">344.62 $</div>
                                        <div class="table-cell">844,318.02</div>
                                        <div class="table-cell">Added new order</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">344.62 $</div>
                                        <div class="table-cell">844,318.02</div>
                                        <div class="table-cell">Added new order</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">344.62 $</div>
                                        <div class="table-cell">844,318.02</div>
                                        <div class="table-cell">Added new order</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">344.62 $</div>
                                        <div class="table-cell">844,318.02</div>
                                        <div class="table-cell">Added new order</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">344.62 $</div>
                                        <div class="table-cell">844,318.02</div>
                                        <div class="table-cell">Added new order</div>
                                    </div>
                                </div>
                            </div>';
                            echo $content ;


?>
                      