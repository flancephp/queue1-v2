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

					$sqlSet=" SELECT * FROM  tbl_order_details_temp WHERE ordId = '".$_POST['orderId']."'  AND account_id = '".$_SESSION['accountId']."' and pId= '".$_POST['pId']."' ";
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
							,`account_id` = '".$_SESSION['accountId']."'
							,`editOrdNewItemStatus` = '".$_POST['editOrdNewItemStatus']."'

							";

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
						`editOrdNewItemStatus` = '".$_POST['editOrdNewItemStatus']."',
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
					$resHtml ='<div class="grd-ttl"><table width="100%">
					<tr>
					</tr>';

					//to find order level charge
                  	$ordCount="SELECT * from tbl_order_details_temp where ordId='".$_POST['orderId']."'  AND account_id = '".$_SESSION['accountId']."' AND customChargeType='2' ";
                    $ordCountResult = mysqli_query($con, $ordCount);
                    $ordCountRow = mysqli_num_rows($ordCountResult);

                    if ($ordCountRow > 0)
                    { 

						$resHtml .='<tr class="value">
						<td>&nbsp;</td><td>'.showOtherLangText('Sub Total').'</td><td>'.getNumFormtPrice($chargePrice, $getDefCurDet['curCode']).'</td>';

						if ($_POST['currencyId']) 
							{
								$resHtml .='<td>'.showOtherCur($chargePriceOther, $_POST['currencyId']).'</td>';
							}

							$resHtml .='</tr>';
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

							$resHtml .='<tr> <td align="center">
							<a title="'.showOtherLangText('Delete').'" href="javascript:void(0)" onClick="getDelNumb('.$fixRow['id'].', '.$_POST['orderId'].', '.$_POST['supplierId'].');" style="color:#808080" class="glyphicon glyphicon-trash"></a>
							</td> <td>'.$feeName.'</td><td>'.getNumFormtPrice($fixRow['price'], $getDefCurDet['curCode']).'</td>';


										if ($_POST['curAmtVal']) 
											{
												$resHtml .='<td> '.showOtherCur($fixRow['curAmt'], $_POST['currencyId']).'</td>';
											}


												$resHtml .='</tr>';

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


							$resHtml .='<tr> <td align="center"><a title="'.showOtherLangText('Delete').'" href="javascript:void(0)" onClick="getDelNumb('.$perRow['id'].', '.$_POST['orderId'].', '.$_POST['supplierId'].');" style="color:#808080" class="glyphicon glyphicon-trash"></a></td> <td>'.$feeName.' '.$perRow['price'].' %</td><td> '.getNumFormtPrice($perChargeTotal, $getDefCurDet['curCode']).'</td>';


										if ($_POST['curAmtVal']) 
											{
												$resHtml .='<td> '.showOtherCur($perChargeTotalOther, $_REQUEST['currencyId']).'</td>';
											}



							$resHtml .='</tr>';
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

								$resHtml .='<tr> <td align="center"><a title="'.showOtherLangText('Delete').'" href="javascript:void(0)" onClick="getDelNumb('.$taxRow['id'].', '.$_POST['orderId'].', '.$_POST['supplierId'].');" style="color:#808080" class="glyphicon glyphicon-trash"></a></td> <td>'.$feeName.' '.$taxRow['price'].' %</td><td> '.getNumFormtPrice($taxPerChargesTotal, $getDefCurDet['curCode']).'</td>';

									if ($_POST['curAmtVal']) 
											{
												$resHtml .='<td> '.showOtherCur($taxPerChargesTotalOther, $_REQUEST['currencyId']).'</td>';
											}


								$resHtml .='</tr>';

							}
							//calculating net value here
							$totalTaxCharges= ( ($chargePrice+$totalFixedCharges+$totalPerCharges)*$taxCharges/100);//calculating total tax value 
							$totalTaxChargesOther= ( ($chargePriceOther+$totalFixedChargesOther+$totalPerChargesOther)*$taxCharges/100);

							$netTotalValue= ($chargePrice+$totalFixedCharges+$totalPerCharges+$totalTaxCharges);
							$netTotalValueOther= ($chargePriceOther+$totalFixedChargesOther+$totalPerChargesOther+$totalTaxChargesOther);

							$brdBtm =  ($ordCountRow > 0) ? '2px solid #939393' : '';
							$resHtml .='<tr class="nt-value"  style="border-top:'.$brdBtm.'"><td>&nbsp;</td><td>'.showOtherLangText('Grand Total').'</td><td> '.getNumFormtPrice($netTotalValue, $getDefCurDet['curCode']).'</td>';

									if ($_POST['curAmtVal']) 
											{
												$resHtml .='<td> '.showOtherCur($netTotalValueOther, $_POST['currencyId']).'</td>';
											}

							$resHtml .='</tr>';
					


						$resHtml .= '</table></div>';

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