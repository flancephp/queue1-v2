<?php
include('inc/dbConfig.php'); //connection details

//start of add new order
if( isset($_POST['pId']) && $_POST['pId'] > 0  && $_SESSION['id'] > 0 )

{
	if( isset($_POST['currencyId']) )
	{
		$curDet = getCurrencyDet($_POST['currencyId']);
	}

	$otherCurAmt = 0;
	$currencyId = 0;
	if(!empty($curDet))
	{
		$otherCurAmt = $curDet['amt'];
		$currencyId = $curDet['id'];
	}

	if (isset($_POST['otherAmt']) && $_POST['otherAmt']==1)
	{

		$price = ($_POST['priceId']/$_POST['factor']);
		$curPrice = ($_POST['unitPrice']/$_POST['factor']);
		$totalAmt = ($_POST['priceId']*$_POST['qty']);
		$curAmt = ($_POST['factor']*$curPrice*$_POST['qty']);


		$totalPriceOther = ($_POST['qty'] * $_POST['unitPrice']);
		$totalPriceOther = showOtherCur($totalPriceOther,$currencyId);

		$totalPrice = ( ($_POST['qty'] * $_POST['unitPrice']) / $_POST['curAmtVal'] );
		$totalPrice = getNumFormtPrice($totalPrice,$getDefCurDet['curCode']);

		$priceId = getPrice($_POST['unitPrice'] / $_POST['curAmtVal']);

	}
	else
	{

		$price = ($_POST['unitPrice']/$_POST['factor']);
		$curPrice = ($_POST['priceIdOther']/$_POST['factor']);
		$totalAmt = ($_POST['unitPrice']*$_POST['qty']);
		$curAmt = ($_POST['factor']*$curPrice*$_POST['qty']);


		$totalPriceOther = ($_POST['qty'] * $_POST['unitPrice'] * $_POST['curAmtVal']);
		$totalPriceOther = showOtherCur($totalPriceOther,$currencyId);
		$priceIdOther = showOtherCur($_POST['priceIdOther'],$currencyId);
		$priceIdOther = trim($priceIdOther,$curDet['curCode']);
	}

		$upQry = " UPDATE  `tbl_order_details` SET 
		`qtyReceived` = '".$_POST['qty']."', 
		`price` = '".$price."', 
		`curPrice` = '".$curPrice."', 
		`totalAmt` = '".$totalAmt."',
		`curAmt` = '".$curAmt."' 
		WHERE ordId = '".$_POST['orderId']."' AND pId = '".$_POST['pId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
		mysqli_query($con, $upQry);


	//get the sum of all product and item level charges 
	$sqlSet="SELECT SUM(totalAmt) AS totalAmt, SUM(curAmt) AS totalAmtOther FROM tbl_order_details WHERE ordId='".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."'  AND (customChargeType='1' OR customChargeType='0')";
	$resultSet = mysqli_query($con, $sqlSet);
	$chargeRow = mysqli_fetch_array($resultSet);	

	$chargePrice=$chargeRow['totalAmt'];
	$chargePriceOther=$chargeRow['totalAmtOther'];

	//--------------------------------------------------------------------------------------------
	$resHtml ='<div class="container"><div class="prcTable">';

	//to find order level charge
	$ordCount="SELECT * from tbl_order_details where ordId='".$_POST['orderId']."'  AND account_id = '".$_SESSION['accountId']."' AND customChargeType='2' ";
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
                                                            <p>'.getNumFormtPrice($chargePrice,$getDefCurDet['curCode']).'</p>
                                                        </div>';

		if ($_POST['currencyId']) 
		{
			$resHtml .='<div class="p-2 otherCurr">
                                                            <p>'.showOtherCur($chargePriceOther, $_POST['currencyId']).'</p>
                                                        </div>';
		}

		$resHtml .='</div></div>';
	}

	//show here order level fixed/ percent/ tax charges		
	$taxCharges=0;
	$fixedCharges=0;
	$perCharges=0;

	//Starts order level fixed discount charges
	$sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details od 
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
                                                       
                                                    </div>
                                                    <div class="p-2 txnmRow">
                                                        <p>'.$feeName.'</p>
                                                    </div><div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p>'.getNumFormtPrice($fixRow['price'],$getDefCurDet['curCode']).'</p>
                                                        </div>';


		if ($_POST['curAmtVal']) 
		{
			$resHtml .='<div class="p-2 otherCurr">
                                                            <p> '.showOtherCur($fixRow['curAmt'], $_POST['currencyId']).'</p>
                                                        </div>';
		}


		$resHtml .=' </div>
                                                </div>';

	}

	//start order level item percent charges
	$sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details od 
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
                                                    <div class="p-2 delIcn text-center">
                                                        
                                                    </div>
                                                    <div class="p-2 txnmRow">
                                                        <p>'.$feeName.' '.$perRow['price'].' %</p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p>'.getNumFormtPrice($perChargeTotal,$getDefCurDet['curCode']).'</p>
                                                        </div>';


		if ($_POST['curAmtVal']) 
		{
			$resHtml .='<div class="p-2 otherCurr">
                                                            <p>'.showOtherCur($perChargeTotalOther, $_REQUEST['currencyId']).'</p>
                                                        </div>';
		}



		$resHtml .='</div></div>';
	}


	$totalFixedCharges= $fixedCharges;//calculating total fixed charge
	$totalFixedChargesOther= $fixedChargesOther;

	$totalPerCharges= ($chargePrice*$perCharges/100);//calculating total per charge
	$totalPerChargesOther= ($chargePriceOther*$perCharges/100);

		//start order level item tax charges
	$sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details od 
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
                                                    <div class="p-2 delIcn text-center">
                                                        
                                                    </div>
                                                    <div class="p-2 txnmRow">
                                                        <p>'.$feeName.' '.$taxRow['price'].' %</div>
                                                    <div class="d-flex align-items-center justify-content-end curRow"> '.getNumFormtPrice($taxPerChargesTotal,$getDefCurDet['curCode']).'</p>
                                                    </div>';

		if ($_POST['curAmtVal']) 
		{
			$resHtml .='<div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p> '.showOtherCur($taxPerChargesTotalOther, $_REQUEST['currencyId']).'</p>
                                                        </div>';
		}


		$resHtml .= '</div>';

	}
		//calculating net value here
		$totalTaxCharges= ( ($chargePrice+$totalFixedCharges+$totalPerCharges)*$taxCharges/100);//calculating total tax value 
		$totalTaxChargesOther= ( ($chargePriceOther+$totalFixedChargesOther+$totalPerChargesOther)*$taxCharges/100);

		$netTotalValue= ($chargePrice+$totalFixedCharges+$totalPerCharges+$totalTaxCharges);
		$netTotalValueOther= ($chargePriceOther+$totalFixedChargesOther+$totalPerChargesOther+$totalTaxChargesOther);
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
                                                            <p> '.getNumFormtPrice($netTotalValue,$getDefCurDet['curCode']).'</p>
                                                        </div>';

		if ($_POST['curAmtVal']) 
		{
			$resHtml .='<div class="p-2 otherCurr">
                                                            <p>'.showOtherCur($netTotalValueOther, $_POST['currencyId']).'</p>
                                                        </div>';
		}

		$resHtml .='</div>';



		$resHtml .= '</div>
                                            </div>
                                        </div>
                                    </div>';

		//echo $resHtml;

		if (isset($_POST['otherAmt']) && $_POST['otherAmt']==1) {
			
			$responseArr = ['resHtml'=>$resHtml, 'totalPrice'=> $totalPrice, 'priceId'=> $priceId, 'totalPriceOther'=>$totalPriceOther];
						
			echo json_encode($responseArr);
		}else{

			$responseArr = ['resHtml'=>$resHtml, 'priceIdOther'=> $priceIdOther, 'totalPriceOther'=>$totalPriceOther];
						
			echo json_encode($responseArr);
		}

		
	//--------------------------------------------------------------------------------------------	

}//end of add new order





		?>