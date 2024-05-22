<?php include('inc/dbConfig.php'); //connection details

//update notes valuefrom here
if (isset($_POST['notes']))
{
	$sql=" SELECT * FROM  tbl_order_items_temp WHERE supplierId = '".$_POST['supplierId']."'  AND account_id = '".$_SESSION['accountId']."'  AND `userId` = '".$_SESSION['id']."' AND pId='".$_POST['pId']."' ";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);

	if (!$row)
	{
		$insQry = " INSERT INTO tbl_order_items_temp SET `notes`='".$_POST['notes']."',
		`supplierId` = '".$_POST['supplierId']."',
		`userId` = '".$_SESSION['id']."',
		`pId` = '".$_POST['pId']."',
		`account_id` = '".$_SESSION['accountId']."' ";
		mysqli_query($con, $insQry);

	}else{

		$upQry = " UPDATE  `tbl_order_items_temp` SET
		`notes` = '".$_POST['notes']."'
	
		WHERE supplierId = '".$_POST['supplierId']."' AND `userId` = '".$_SESSION['id']."' AND pId = '".$_POST['pId']."' AND account_id = '".$_SESSION['accountId']."'  ";
		mysqli_query($con, $upQry);

	}

	$sql=" SELECT * FROM  tbl_order_items_temp WHERE supplierId = '".$_POST['supplierId']."'  AND account_id = '".$_SESSION['accountId']."'  AND `userId` = '".$_SESSION['id']."' AND pId='".$_POST['pId']."' ";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	echo $row['notes'];
	
}



//start of add new order
if( isset($_POST['pId']) && $_POST['pId'] > 0  && $_REQUEST['supplierId'] > 0 && $_SESSION['id'] > 0  && !isset($_POST['notes']) )
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


	$sql=" SELECT * FROM  tbl_order_items_temp WHERE supplierId = '".$_REQUEST['supplierId']."'  AND account_id = '".$_SESSION['accountId']."'  AND `userId` = '".$_SESSION['id']."' AND pId='".$_POST['pId']."' ";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);

	if ($row) {
		
		if($_POST['qty'] < 1)
		{
			$sql = " DELETE FROM `tbl_order_items_temp`  WHERE `id` = '".$row['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";
		}
		else
		{
			
			$sql = " UPDATE `tbl_order_items_temp` SET `qty` = ".$_POST['qty']." WHERE `id` = '".$row['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";
			mysqli_query($con, $sql);

		}
		
		
		if(isset($_POST['notes']))
		{
		
			$sql = " UPDATE `tbl_order_items_temp` SET `notes` = ".$_POST['notes']." WHERE `id` = '".$row['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";
			mysqli_query($con, $sql);
		}
		
		
	}elseif($_POST['qty'] > 0){
		
		$sql = "INSERT INTO `tbl_order_items_temp` SET
		supplierId = '".$_POST['supplierId']."'
		,`userId` = '".$_SESSION['id']."'
		, `pId` = '".$_POST['pId']."' 
		, `qty` = '".$_POST['qty']."'
		, `account_id` = '".$_SESSION['accountId']."'  ";
	}

	mysqli_query($con, $sql);
	
	
	$sql=" SELECT * FROM  tbl_order_items_temp WHERE supplierId = '".$_REQUEST['supplierId']."' AND account_id = '".$_SESSION['accountId']."'  AND `userId` = '".$_SESSION['id']."'  ";
	$result = mysqli_query($con, $sql);
	while( $row = mysqli_fetch_array($result) )
	{
		$sqlSet=" SELECT * FROM tbl_products WHERE id= '".$row['pId']."'  AND account_id = '".$_SESSION['accountId']."' ";
		$resultSet= mysqli_query($con, $sqlSet);
		$prodRow= mysqli_fetch_array($resultSet);

		$productPrice += $prodRow['price']*$prodRow['factor']*$row['qty'];
	}

	//for custom item fee charges
	$customCharge=0;
	if( isset($_SESSION['itemCharges'][1]) && count($_SESSION['itemCharges'][1]) > 0  )
	{
		$itemIds = implode(',', $_SESSION['itemCharges'][1]);

		$sqlSet = " SELECT * FROM tbl_custom_items_fee WHERE id IN(".$itemIds.") AND account_id = '".$_SESSION['accountId']."'   ";
		$resRows = mysqli_query($con, $sqlSet);
		while( $row = mysqli_fetch_array($resRows) ) //start custom item charge loop
		{
			$customCharge += $row['amt'];
		}

	}//end of custom item fee charges
	$totalChargePrice= ($productPrice+ $customCharge);//total sum value of custom charge and product charge 

	if ($_POST['currencyId']) {
		$sql= " SELECT * FROM tbl_currency WHERE id='".$_POST['currencyId']."' AND account_id = '".$_SESSION['accountId']."'  ";
		$resultSet= mysqli_query($con, $sql);
		$curRow= mysqli_fetch_array($resultSet);
		$totalChargePriceOther= ($totalChargePrice*$curRow['amt']);
	}
	
	//--------------------------------------------------------------------------------------------
	$resHtml ='<div class="container">
                                        <div class="prcTable">';
       if( isset($_SESSION['itemCharges'][3]) && count($_SESSION['itemCharges'][3]) > 0  )
	{
       $resHtml .=                           '<div class="price justify-content-between">
                                                <div class="p-2 delIcn text-center"></div>
                                                <div class="p-2 txnmRow">
                                                    <p>'.showOtherLangText('Sub Total').'</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                        <p>'.getNumFormtPrice($totalChargePrice, $getDefCurDet['curCode']).'</p>
                                                    </div>';
           if ($_POST['currencyId']) 
		{
			 $resHtml .=                           '<div class="p-2 otherCurr">
                                                        <p>'.showOtherCur($totalChargePriceOther, $_POST['currencyId']).'</p>
                                                    </div>';
		}
          
           $resHtml .=                      '</div>
                                            </div>';
         } 

       $taxCharges=0;
	$fixedCharges=0;
	$perCharges=0;
	if( isset($_SESSION['itemCharges'][3]) && count($_SESSION['itemCharges'][3]) > 0  )
	{
		$itemIds = implode(',', $_SESSION['itemCharges'][3]);
					 //start order level item fixed charges
		$sqlSet = " SELECT * FROM tbl_order_fee WHERE id IN(".$itemIds.") AND account_id = '".$_SESSION['accountId']."'  AND feeType =2 ";
		$resRows = mysqli_query($con, $sqlSet);

		while( $fixRow = mysqli_fetch_array($resRows) ) 
		{
			$feeName = $fixRow['feeName'];
			$fixedCharges += $fixRow['amt'];
			$fixedChargesOther += ($fixRow['amt']*$curRow['amt']);

       $resHtml .=        '<div class="price justify-content-between taxRow">
                                                <div class="p-2 delIcn text-center">
                                                    <a onClick="getDelNumb('.$fixRow['id'].', 3, '.$_POST['currencyId'].');" href="javascript:void(0)">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </a>
                                                </div>
                                                <div class="p-2 txnmRow">
                                                    <p>'.$feeName.'</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                        <p>'.getNumFormtPrice($fixRow['amt'], $getDefCurDet['curCode']).'</p>
                                                    </div>';
                     $resHtml .=        '<div class="p-2 otherCurr">
                                                        <p>'.showOtherCur($fixedChargesOther, $_POST['currencyId']).'</p>
                                                    </div>';
                     $resHtml .=       '</div>
                                            </div>';
            }
        
        //start order level item percent charges
		$sqlSet = " SELECT * FROM tbl_order_fee WHERE id IN(".$itemIds.") AND account_id = '".$_SESSION['accountId']."'  AND feeType = 3  ";
		$resRows = mysqli_query($con, $sqlSet);
		while($perRow = mysqli_fetch_array($resRows))
		{

			$feeName = $perRow['feeName'];
			$perCharges += $perRow['amt'];
			$perChargeTotal = ($totalChargePrice*$perRow['amt']/100);
			$perChargeTotalOther = ($totalChargePriceOther*$perRow['amt']/100);

             $resHtml .=        '<div class="price justify-content-between taxRow">
                                                <div class="p-2 delIcn text-center">
                                                    <a onClick="getDelNumb('.$perRow['id'].', 3, '.$_POST['currencyId'].');" href="javascript:void(0)">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </a>
                                                </div>
                                                <div class="p-2 txnmRow">
                                                    <p>'.$feeName.' '.$perRow['amt'].' %</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                        <p>'.getNumFormtPrice($perChargeTotal, $getDefCurDet['curCode']).'</p>
                                                    </div>';
                                                    if ($_POST['currencyId']) 
								{
                     $resHtml .=        '<div class="p-2 otherCurr">
                                                        <p>'.showOtherCur($perChargeTotalOther, $_POST['currencyId']).'</p>
                                                    </div>';
                                                }
                     $resHtml .=       '</div>
                                            </div>';
		}

		$sqlSetQry = " SELECT * FROM tbl_order_fee WHERE id IN(".$itemIds.")  AND account_id = '".$_SESSION['accountId']."' AND feeType =1 ";
		$resultRows = mysqli_query($con, $sqlSetQry);
					//calculating tax charges
					$totalFixedCharges= $fixedCharges;//calculating total fixed charge
					$totalFixedChargesOther= $fixedChargesOther;
					$totalPerCharges= ($totalChargePrice*$perCharges/100);//calculating total per charge
					$totalPerChargesOther= ($totalChargePriceOther*$perCharges/100);

					while( $taxRow = mysqli_fetch_array($resultRows) ) 
					{ 
						$feeName = $taxRow['feeName'];
						$taxCharges += $taxRow['amt'];
						$taxPerChargesTotal = ( ($totalChargePrice+$totalFixedCharges+$totalPerCharges )*$taxRow['amt']/100 );
						$taxPerChargesTotalOther = ( ($totalChargePriceOther+$totalFixedChargesOther+$totalPerChargesOther )*$taxRow['amt']/100 );
                      $resHtml .=        '<div class="price justify-content-between taxRow">
                                                <div class="p-2 delIcn text-center">
                                                    <a onClick="getDelNumb('.$fixRow['id'].', 3, '.$_POST['currencyId'].');" href="javascript:void(0)">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </a>
                                                </div>
                                                <div class="p-2 txnmRow">
                                                    <p>'.$feeName.' '.$taxRow['amt'].' %</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                        <p> '.getNumFormtPrice($taxPerChargesTotal, $getDefCurDet['curCode']).'</p>
                                                    </div>';
                                                    if ($_POST['currencyId']) 
								{
                     $resHtml .=        '<div class="p-2 otherCurr">
                                                        <p>'.showOtherCur($taxPerChargesTotalOther, $_POST['currencyId']).'</p>
                                                    </div>';
                                                }
                     $resHtml .=       '</div>
                                            </div>';

						}

				}


             $totalTaxCharges= ( ($totalChargePrice+$totalFixedCharges+$totalPerCharges)*$taxCharges/100);//calculating total tax value 
					$totalTaxChargesOther= ( ($totalChargePriceOther+$totalFixedChargesOther+$totalPerChargesOther)*$taxCharges/100);
					$netTotalValue= ($totalChargePrice+$totalFixedCharges+$totalPerCharges+$totalTaxCharges);
					$netTotalValueOther= ($totalChargePriceOther+$totalFixedChargesOther+$totalPerChargesOther+$totalTaxChargesOther);
					$stylegrand = '';
             if(!isset($_SESSION['itemCharges'][3]) || count($_SESSION['itemCharges'][3]) == 0)
                                            {
           $stylegrand = 'style="border-top: 0px;"';  
} 
             $resHtml .= '<div '.$stylegrand.' class="price justify-content-between grdTtl-Row">
                                                <div class="p-2 delIcn text-center"></div>
                                                <div class="p-2 txnmRow">
                                                    <p>'.showOtherLangText('Grand Total').'</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                        <p> '.getNumFormtPrice($netTotalValue, $getDefCurDet['curCode']).'</p>
                                                    </div>';
                        if ($_POST['currencyId']) 
					{
                        $resHtml .= '<div class="p-2 otherCurr">
                                                        <p>'.showOtherCur($netTotalValueOther, $_POST['currencyId']).'</p>
                                                    </div>';
                    }
                        $resHtml .= '</div>
                                            </div>
                                        </div>
                                    </div>';
	
				 
				 
			$sqlSet=" SELECT * FROM tbl_products WHERE id= '".$_POST['pId']."'  AND account_id = '".$_SESSION['accountId']."' ";
			$resultSet= mysqli_query($con, $sqlSet);
			$prodRow= mysqli_fetch_array($resultSet);
			
			$productPrice = getNumFormtPrice($prodRow['price']*$prodRow['factor']*$_POST['qty'], $getDefCurDet['curCode']);

			if ($_POST['currencyId'] > 0)
			{
				$trimedProductPrice = trim($productPrice,$curCode);

			}else{

				$trimedProductPrice = trim($productPrice, $getDefCurDet['curCode']);
			}

			$replacedProductPrice = str_replace(',', '', $trimedProductPrice);

			$otherCurAmt = showOtherCur($otherCurAmt*$replacedProductPrice, $curDet['id']);
			
			$responseArr = ['resHtml'=>$resHtml, 'productPrice'=>$productPrice, 'totalPriceOther'=>$otherCurAmt];
			
			echo json_encode($responseArr);
	//--------------------------------------------------------------------------------------------	

			}//end of add new order





			?>