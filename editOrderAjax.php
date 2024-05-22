<?php 
include('inc/dbConfig.php'); //connection details

//notes for each extra charges
if (isset($_POST['itemNotes']))
{

	$upQry = " UPDATE  `tbl_order_details_temp` SET
	`note` = '".$_POST['itemNotes']."'
	
	WHERE ordId = '".$_POST['orderId']."' AND id = '".$_POST['itemNotesId']."' AND account_id = '".$_SESSION['accountId']."'  ";
	mysqli_query($con, $upQry);
}

//notes for each products
if (isset($_POST['notes']))
{

	$upQry = " UPDATE  `tbl_order_details_temp` SET
	`note` = '".$_POST['notes']."'
	
	WHERE ordId = '".$_POST['orderId']."' AND pId = '".$_POST['pId']."' AND account_id = '".$_SESSION['accountId']."'  ";
	mysqli_query($con, $upQry);
}

if( isset($_POST['pId']) && $_POST['pId'] > 0  && $_POST['orderId'] && isset($_POST['qty']) )
{ 
				if($_POST['qty'] > 0)
				{

							if( isset($_POST['currencyId']) )
							{ 
								$curDet = getCurrencyDet($_POST['currencyId']);
							}

							$otherCurAmt = 0;
							$currencyId = 0;
							if( !empty($curDet) )
							{
								$otherCurAmt = $curDet['amt'];
								$currencyId = $curDet['id'];
								$curCode = $curDet['curCode'];
							}

							//orderNetValue($_POST['orderId'],$currencyId);

						$sqlSet=" SELECT * FROM tbl_products WHERE id= '".$_POST['pId']."' AND account_id = '".$_SESSION['accountId']."'  ";
						$resultSet= mysqli_query($con, $sqlSet);
						$prodRow= mysqli_fetch_array($resultSet);
						$factor= $prodRow['factor'];
						$price = $prodRow['price']*$prodRow['factor'];	
						$productPrice = $prodRow['price']*$prodRow['factor']*$_POST['qty'];
						$productPriceOther = $prodRow['price']*$prodRow['factor']*$_POST['qty']*$_POST['curAmtVal'];

					$sqlSet=" SELECT * FROM  tbl_order_details WHERE ordId = '".$_POST['orderId']."'  AND account_id = '".$_SESSION['accountId']."' and pId= '".$_POST['pId']."' ";
					$resultSet= mysqli_query($con, $sqlSet);
					$prodRow= mysqli_fetch_array($resultSet);
					if(!$prodRow)
					{
						$sql = "INSERT INTO `tbl_order_details_temp` SET
							`ordId` = '".$_POST['orderId']."'
							, `pId` = '".$_POST['pId']."' 
							,`price` = '".$price."'
							,`factor` = '".$factor."'
							,`qty` = '".$_POST['qty']."'
							,`totalAmt` = '".$productPrice."'
							,`currencyId` = '".$currencyId."' 
							,`curPrice` = '".($price*$otherCurAmt)."' 
							,`curAmt` = '".($productPriceOther)."'
							,`account_id` = '".$_SESSION['accountId']."'  ";

							mysqli_query($con, $sql); //die($sql);

						$updateQry = " UPDATE  `tbl_orders` SET 
						`ordCurId` = '".$currencyId."'
						WHERE id=".$_POST['orderId']." ";
						mysqli_query($con, $updateQry);
					}
					else
					{

						

						$upQry = " UPDATE  `tbl_order_details_temp` SET
						`price` = '".$price."', 
						
						`qty` = '".$_POST['qty']."', 
						`totalAmt` = '".$productPrice."',
						`curAmt` = '".($productPriceOther)."'
						
						WHERE ordId = '".$_POST['orderId']."' AND pId = '".$_POST['pId']."' AND account_id = '".$_SESSION['accountId']."'  ";
						mysqli_query($con, $upQry);

						$updateQry = " UPDATE  `tbl_orders` SET 
						`ordCurId` = '".$currencyId."'
						WHERE id=".$_POST['orderId']."  ";
						mysqli_query($con, $updateQry);
					}
				}
				 else
				{ 
					 $Qry = " DELETE FROM  `tbl_order_details_temp` WHERE ordId = '".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."'  AND pId = '".$_POST['pId']."' ";
					      mysqli_query($con, $Qry);
				}


	//for custom item fee charges
	$customCharge=0;
	if( isset($_POST['itemCharges'][1]) && count($_POST['itemCharges'][1]) > 0  )
	{ 
		$itemIds = implode(',', $_POST['itemCharges'][1]);

		$sqlSet = " SELECT * FROM tbl_custom_items_fee WHERE id IN(".$itemIds.")  AND account_id = '".$_SESSION['accountId']."'  ";
		$resRows = mysqli_query($con, $sqlSet);
		while( $row = mysqli_fetch_array($resRows) ) //start custom item charge loop
		{
			$customCharge += $row['amt'];
		}

	}//end of custom item fee charges
	$totalChargePrice= ($productPrice+ $customCharge);//total sum value of custom charge and product charge 
	
	//get the sum of all product and item level charges 
	$sqlSet="SELECT SUM(totalAmt) AS totalAmt, SUM(curAmt) AS totalAmtOther FROM tbl_order_details_temp WHERE ordId='".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."'  AND (customChargeType='1' OR customChargeType='0')";
	$resultSet = mysqli_query($con, $sqlSet);
	$chargeRow = mysqli_fetch_array($resultSet);	
	$chargePrice=$chargeRow['totalAmt'];
	$chargePriceOther=$chargeRow['totalAmtOther'];
					
     //--------------------------------------------------------------------------------------------
					$resHtml ='<div class="container">
                                            <div class="prcTable" >';

					//to find order level charge
                  	$ordCount="SELECT * from tbl_order_details_temp where ordId='".$_POST['orderId']."'  AND account_id = '".$_SESSION['accountId']."' AND customChargeType='2' ";
                    $ordCountResult = mysqli_query($con, $ordCount);
                    $ordCountRow = mysqli_num_rows($ordCountResult);

                    if ($ordCountRow > 0)
                    { 

						$resHtml .='<div class="price justify-content-between">
                                                    <div class="p-2 delIcn text-center"></div>
                                                    <div class="p-2 txnmRow">
                                                        <p>'.showOtherLangText('Sub Total').'</p>
                                                    </div><div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p>'.getNumFormtPrice($chargePrice, $getDefCurDet['curCode']).'</p>
                                                        </div>';

						if ($_POST['currencyId']) 
							{
								$resHtml .='<div class="p-2 otherCurr">
                                                            <p>'.showOtherCur($chargePriceOther, $_POST['currencyId']).'</p>
                                                        </div>';
							}

							$resHtml .='</div>
                                                </div>';
					}

							//show here order level fixed/ percent/ tax charges		
					$taxCharges=0;
					$fixedCharges=0;
					$perCharges=0;
					
					 //Starts order level fixed discount charges
					$sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details_temp od 
					INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
					WHERE od.ordId = '".$_POST['orderId']."' AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

						$ordQry = mysqli_query($con, $sql);

						while( $fixRow = mysqli_fetch_array($ordQry) ) 
						{ 
							$feeName = $fixRow['feeName'];
							$fixedCharges += $fixRow['price'];
							$fixedChargesOther += $fixRow['curAmt'];

							$resHtml .='<div class="price justify-content-between taxRow">
                                                    <div class="p-2 delIcn text-center">
							<a title="'.showOtherLangText('Delete').'" href="javascript:void(0)" onClick="getDelNumb('.$fixRow['id'].', '.$_POST['orderId'].', '.$_POST['supplierId'].');" style="color:#808080" class="glyphicon glyphicon-trash"><i class="fa-solid fa-trash-can"></i></a>
							</div> <div class="p-2 txnmRow"><p>'.$feeName.'</p>
                                                    </div><div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p>'.getNumFormtPrice($fixRow['price'], $getDefCurDet['curCode']).'</p>
                                                        </div>';


										if ($_POST['curAmtVal']) 
											{
												$resHtml .='<div class="p-2 otherCurr">
                                                            <p> '.showOtherCur($fixRow['curAmt'], $_POST['currencyId']).'</p>
                                                        </div>';
											}


												$resHtml .='</div>
                                                </div>';

						}

						//start order level item percent charges
					      $sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details_temp od 
					      INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
					      WHERE od.ordId = '".$_POST['orderId']."' AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
			               $ordQry = mysqli_query($con, $sql);
						while($perRow = mysqli_fetch_array($ordQry))
						{

							$feeName = $perRow['feeName'];
							$perCharges += $perRow['price'];
							$perChargeTotal = ($chargePrice*$perRow['price']/100);
							$perChargeTotalOther = ($chargePriceOther*$perRow['price']/100);


							$resHtml .='<div class="price justify-content-between taxRow">
                                                    <div class="p-2 delIcn text-center"><a title="'.showOtherLangText('Delete').'" href="javascript:void(0)" onClick="getDelNumb('.$perRow['id'].', '.$_POST['orderId'].', '.$_POST['supplierId'].');" class="glyphicon glyphicon-trash"><i class="fa-solid fa-trash-can"></i></a>
                                                    </div> <div class="p-2 txnmRow">
                                                        <p>'.$feeName.' '.$perRow['price'].' %</p>
                                                    </div><div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p> '.getNumFormtPrice($perChargeTotal, $getDefCurDet['curCode']).'</p>
                                                        </div>';


										if ($_POST['curAmtVal']) 
											{
												$resHtml .='<div class="p-2 otherCurr">
                                                            <p> '.showOtherCur($perChargeTotalOther, $_REQUEST['currencyId']).'</p>
                                                        </div>';
											}



							$resHtml .='</div>
                                                </div>';
						}


						$totalFixedCharges= $fixedCharges;//calculating total fixed charge
						$totalFixedChargesOther= $fixedChargesOther;

						$totalPerCharges= ($chargePrice*$perCharges/100);//calculating total per charge
						$totalPerChargesOther= ($chargePriceOther*$perCharges/100);

							//start order level item tax charges
							 $sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details_temp od 
							     INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
							     WHERE od.ordId = '".$_POST['orderId']."' AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
			     					$ordQry = mysqli_query($con, $sql);
							while( $taxRow = mysqli_fetch_array($ordQry) ) 
							{ 
								$feeName = $taxRow['feeName'];
								$taxCharges += $taxRow['price'];
								$taxPerChargesTotal = ( ($chargePrice+$totalFixedCharges+$totalPerCharges )*$taxRow['price']/100 );
								$taxPerChargesTotalOther = ( ($chargePriceOther+$totalFixedChargesOther+$totalPerChargesOther )*$taxRow['price']/100 );

								$resHtml .='<div class="price justify-content-between taxRow">
                                                    <div class="p-2 delIcn text-center"><a title="'.showOtherLangText('Delete').'" href="javascript:void(0)" onClick="getDelNumb('.$taxRow['id'].', '.$_POST['orderId'].', '.$_POST['supplierId'].');"  class="glyphicon glyphicon-trash"><i class="fa-solid fa-trash-can"></i></a>
                                                    </div><div class="p-2 txnmRow">
                                                        <p>'.$feeName.' '.$taxRow['price'].' %</p>
                                                    </div><div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p> '.getNumFormtPrice($taxPerChargesTotal, $getDefCurDet['curCode']).'</p>
                                                        </div>';

									if ($_POST['curAmtVal']) 
											{
												$resHtml .='<div class="p-2 otherCurr">
                                                            <p>'.showOtherCur($taxPerChargesTotalOther, $_REQUEST['currencyId']).'</p>
                                                        </div>';
											}


								$resHtml .='</div>
                                                </div>';

							}
							//calculating net value here
							$totalTaxCharges= ( ($chargePrice+$totalFixedCharges+$totalPerCharges)*$taxCharges/100);//calculating total tax value 
							$totalTaxChargesOther= ( ($chargePriceOther+$totalFixedChargesOther+$totalPerChargesOther)*$taxCharges/100);

							$netTotalValue= ($chargePrice+$totalFixedCharges+$totalPerCharges+$totalTaxCharges);
							$netTotalValueOther= ($chargePriceOther+$totalFixedChargesOther+$totalPerChargesOther+$totalTaxChargesOther);
							$grandtotalcss = '';
                              if ($ordCountRow == 0)
                                            { 
    $grandtotalcss =  'style="border-top: 0px;"';  
} 
							$resHtml .='<div '.$grandtotalcss.' class="price justify-content-between grdTtl-Row">
                                                    <div class="p-2 delIcn text-center"></div>
                                                    <div class="p-2 txnmRow">
                                                        <p>'.showOtherLangText('Grand Total').'</p>
                                                    </div><div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p> '.getNumFormtPrice($netTotalValue, $getDefCurDet['curCode']).'</p>
                                                        </div>';

									if ($_POST['curAmtVal']) 
											{
												$resHtml .='<div class="p-2 otherCurr">
                                                            <p> '.showOtherCur($netTotalValueOther, $_POST['currencyId']).'</p>
                                                        </div>';
											}

							
					


						$resHtml .= '</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';

						orderNetValue($_POST['orderId'],$_POST['currencyId']);

						$sqlSet=" SELECT * FROM tbl_products WHERE id= '".$_POST['pId']."'  AND account_id = '".$_SESSION['accountId']."' ";
						$resultSet= mysqli_query($con, $sqlSet);
						$prodRow= mysqli_fetch_array($resultSet);
						
						$productPrice = getNumFormtPrice($prodRow['price']*$prodRow['factor']*$_POST['qty'],$getDefCurDet['curCode']);

						if ($_POST['currencyId'] > 0)
						{
							$trimedProductPrice = trim($productPrice,$curCode);

						}else{

							$trimedProductPrice = trim($productPrice,$getDefCurDet['curCode']);
						}

						$replacedProductPrice = str_replace(',', '', $trimedProductPrice);

						$otherCurAmt = showOtherCur($otherCurAmt*$replacedProductPrice, $curDet['id']);
						
						$responseArr = ['resHtml'=>$resHtml, 'productPrice'=>$productPrice, 'totalPriceOther'=>$otherCurAmt];
						
						echo json_encode($responseArr);

	//--------------------------------------------------------------------------------------------	
		
}




?>