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






if(isset($_POST['pId']) && $_POST['orderId'] && isset($_POST['qty']))
{

	// if($_POST['qty'] > 0)
	if($_POST['qty'] > 0)
	{

		$sqlSet=" SELECT * FROM tbl_stocks WHERE pId= '".$_POST['pId']."'  AND account_id = '".$_SESSION['accountId']."' ";
		$resultSet= mysqli_query($con, $sqlSet);
		$stckRow= mysqli_fetch_array($resultSet);

		$price = $stckRow['price'];	
		$productPrice = $stckRow['stockPrice']*$_POST['qty'];
		

		$sqlSet=" SELECT * FROM  tbl_order_details_temp WHERE ordId = '".$_POST['orderId']."' and pId= '".$_POST['pId']."'  AND account_id = '".$_SESSION['accountId']."' ";
		$resultSet= mysqli_query($con, $sqlSet);
		$prodRow= mysqli_fetch_array($resultSet);

		if(!$prodRow)
		{
			$sql = "INSERT INTO `tbl_order_details_temp` SET
				`ordId` = '".$_POST['orderId']."',
				`pId` = '".$_POST['pId']."' ,
				`price` = '".$price."',
				`qty` = '".$_POST['qty']."',
				`totalAmt` = '".$productPrice."',
				`account_id` = '".$_SESSION['accountId']."'   ";
				mysqli_query($con, $sql);

		}else{ 

			$upQry = " UPDATE  `tbl_order_details_temp` SET
			`price` = '".$price."', 
			`qty` = '".$_POST['qty']."', 
			`totalAmt` = '".$productPrice."'
			
			WHERE ordId = '".$_POST['orderId']."' AND pId = '".$_POST['pId']."' AND account_id = '".$_SESSION['accountId']."'  ";
			mysqli_query($con, $upQry);
		}


	 }
	  else{ 

	 		$Qry = " DELETE FROM  `tbl_order_details_temp` WHERE ordId = '".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."'  AND pId = '".$_POST['pId']."' ";
	 		mysqli_query($con, $Qry);
	 }


		//for custom item fee charges
		$customCharge=0;
		if( isset($_SESSION['itemCharges'][1]) && count($_SESSION['itemCharges'][1]) > 0  )
		{
			$itemIds = implode(',', $_SESSION['itemCharges'][1]);

			$sqlSet = " SELECT * FROM tbl_custom_items_fee WHERE id IN(".$itemIds.")  AND account_id = '".$_SESSION['accountId']."'  ";
			$resRows = mysqli_query($con, $sqlSet);
			while( $row = mysqli_fetch_array($resRows) ) //start custom item charge loop
			{
				$customCharge += $row['amt'];
			}

		}//end of custom item fee charges

		$totalChargePrice= ($productPrice+ $customCharge);//total sum value of custom charge and product charge 	

		//--------------------------------------------------------------------------------------------
		//get the sum of all product and item level charges 
		$sqlSet="SELECT SUM(totalAmt) AS totalAmt FROM tbl_order_details_temp WHERE ordId='".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."'  AND (customChargeType='1' OR customChargeType='0')";
		$resultSet = mysqli_query($con, $sqlSet);
		$chargeRow = mysqli_fetch_array($resultSet);	
		$chargePrice=$chargeRow['totalAmt'];

		$resHtml ='<div class="container"><div class="prcTable">';
		
		//to find order level charge
	   	$ordCount="SELECT * from tbl_order_details_temp where ordId='".$_POST['orderId']."'  AND account_id = '".$_SESSION['accountId']."' AND customChargeType='2' ";
	    $ordCountResult = mysqli_query($con, $ordCount);
	    $ordCountRow = mysqli_num_rows($ordCountResult);

	    if ($ordCountRow > 0)
	    { 
			$resHtml .='<div class="price justify-content-between"><div class="p-2 delIcn text-center"></div>';
		$resHtml .='<div class="p-2 txnmRow"><p>'.showOtherLangText('Sub Total').'</p>
                                                </div><div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                        <p> '.getNumFormtPrice($chargePrice,$getDefCurDet['curCode']).'</p>';
		$resHtml .='</div></div></div>';
		}

		//show here order level fixed/ percent/ tax charges		
		$taxCharges=0;
		$fixedCharges=0;
		$perCharges=0;
		if( isset($_SESSION['itemCharges'][3]) && count($_SESSION['itemCharges'][3]) > 0  )
		{
			$itemIds = implode(',', $_SESSION['itemCharges'][3]);
		}
			//start order level item fixed charges
			$sql = "SELECT od.*, tp.feeName FROM tbl_order_details_temp od 
			INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
			WHERE od.ordId = '".$_POST['orderId']."' AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";
			$resRows = mysqli_query($con, $sql);
			while( $fixRow = mysqli_fetch_array($resRows) ) 
			{
				$feeName = $fixRow['feeName'];
				$fixedCharges += $fixRow['price'];

				$resHtml .='<div class="price justify-content-between taxRow">
                                                <div class="p-2 delIcn text-center">

			<a title="'.showOtherLangText('Delete').'" href="javascript:void(0)" onClick="getDelNumb('.$fixRow['id'].', 3)" style="color:#808080" class="glyphicon glyphicon-trash"><i class="fa-solid fa-trash-can"></i></a>

			</div><div class="p-2 txnmRow"><p>'.$feeName.'</p></div><div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                        <p> '.getNumFormtPrice($fixRow['price'],$getDefCurDet['curCode']).'</p>
                                                    </div>
                                                </div>
                                            </div>';

			}

	//Starts order level per discount charges
	$sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details_temp od 
		   INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
		   WHERE od.ordId = '".$_POST['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."' and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
	$resRows = mysqli_query($con, $sql);
	while($perRow = mysqli_fetch_array($resRows))
	{
		$feeName = $perRow['feeName'];
		$perCharges += $perRow['price'];
		$perChargeTotal = ($chargePrice*$perRow['price']/100);


		$resHtml .='<div class="price justify-content-between taxRow">
                                                <div class="p-2 delIcn text-center"><a title="'.showOtherLangText('Delete').'" href="javascript:void(0)" onClick="getDelNumb('.$perRow['id'].', 3)" style="color:#808080" class="glyphicon glyphicon-trash"><i class="fa-solid fa-trash-can"></i></a></a></div> <div class="p-2 txnmRow">
                                                    <p>'.$feeName.' '.$perRow['amt'].' %</p></div><div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                        <p> '.getNumFormtPrice($perChargeTotal,$getDefCurDet['curCode']).'</p>
                                                    </div>
                                                </div>
                                            </div>';
	}

        //start order level item tax charges
		$sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details_temp od 
			    INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
			    WHERE od.ordId = '".$_POST['orderId']."' AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
       	$resultRows = mysqli_query($con, $sql);
		//calculating tax charges
		$totalFixedCharges= $fixedCharges;//calculating total fixed charge
		$totalPerCharges= ($chargePrice*$perCharges/100);//calculating total per charge

		while( $taxRow = mysqli_fetch_array($resultRows) ) 
		{ 
			$feeName = $taxRow['feeName'];
			$taxCharges += $taxRow['price'];
			$taxPerChargesTotal = ( ($chargePrice+$totalFixedCharges+$totalPerCharges )*$taxRow['price']/100 );

			$resHtml .='<div class="price justify-content-between taxRow">
                                                <div class="p-2 delIcn text-center"><a title="'.showOtherLangText('Delete').'" href="javascript:void(0)" onClick="getDelNumb('.$taxRow['id'].', 3)" style="color:#808080" class="glyphicon glyphicon-trash"><i class="fa-solid fa-trash-can"></i></a></a></div> <div class="p-2 txnmRow">
                                                    <p>'.$feeName.' '.$taxRow['amt'].' %</p></div><div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                        <p> '.getNumFormtPrice($taxPerChargesTotal,$getDefCurDet['curCode']).'</p>
                                                    </div>
                                                </div>
                                            </div>';

		}
		//calculating net value here
		$totalTaxCharges= ( ($chargePrice+$totalFixedCharges+$totalPerCharges)*$taxCharges/100);//calculating total tax value 
		$netTotalValue= ($chargePrice+$totalFixedCharges+$totalPerCharges+$totalTaxCharges);


		$brdBtm =  ($ordCountRow > 0) ? '2px solid #939393' : '';

		$stylegrand = '';
             if($ordCountRow == 0)
                                            {
           $stylegrand = 'style="border-top: 0px;"';  
      } 
		$resHtml .='<div '.$stylegrand.' class="price justify-content-between grdTtl-Row">
                                                <div class="p-2 delIcn text-center"></div>
                                                <div class="p-2 txnmRow">
                                                    <p>'.showOtherLangText('Grand Total').'</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                 <p>'.getPriceWithCur($netTotalValue,$getDefCurDet['curCode']).'</p>
                                                    </div>
                                                </div>
                                            </div>';

		
		$sqlSet=" SELECT * FROM tbl_stocks WHERE pId= '".$_POST['pId']."' AND account_id = '".$_SESSION['accountId']."'  ";
		$resultSet= mysqli_query($con, $sqlSet);
		$stckRow= mysqli_fetch_array($resultSet);
		
		$productPrice = getNumFormtPrice($stckRow['stockPrice']*$_POST['qty'],$getDefCurDet['curCode']);
		
		$responseArr = ['resHtml'=>$resHtml, 'productPrice'=>$productPrice];
		
		echo json_encode($responseArr);




}


?>