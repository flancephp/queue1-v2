<?php include('inc/dbConfig.php'); //connection details

//update notes valuefrom here
if (isset($_POST['notes']))
{
	
	$sql=" SELECT * FROM tbl_recusition_items_temp WHERE deptId = '".$_POST['deptId']."'  AND account_id = '".$_SESSION['accountId']."'  AND `userId` = '".$_SESSION['id']."' AND pId='".$_POST['pId']."' ";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	
	if (!$row)
	{
		$insQry = " INSERT INTO tbl_recusition_items_temp SET `notes`='".$_POST['notes']."',
		`deptId` = '".$_POST['deptId']."',
		`userId` = '".$_SESSION['id']."',
		`pId` = '".$_POST['pId']."',
		`account_id` = '".$_SESSION['accountId']."' ";
		mysqli_query($con, $insQry);

	}else{

		$upQry = " UPDATE  `tbl_recusition_items_temp` SET
		`notes` = '".$_POST['notes']."'
	
		WHERE deptId = '".$_POST['deptId']."' AND `userId` = '".$_SESSION['id']."' AND pId = '".$_POST['pId']."' AND account_id = '".$_SESSION['accountId']."'  ";
		mysqli_query($con, $upQry);

	}

	$sql=" SELECT * FROM  tbl_recusition_items_temp WHERE deptId = '".$_POST['deptId']."'  AND account_id = '".$_SESSION['accountId']."'  AND `userId` = '".$_SESSION['id']."' AND pId='".$_POST['pId']."' ";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	if($row['notes'])
	echo $row['notes'];
	else
	echo $_POST['notes'];
	
}

if( isset($_POST['pId']) && !isset($_POST['notes']))
{

	$sql=" SELECT * FROM  tbl_recusition_items_temp WHERE deptId = '".$_POST['deptId']."' AND `userId` = '".$_SESSION['id']."' AND pId='".$_POST['pId']."' AND account_id='".$_SESSION['accountId']."' ";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	
	if ($row)
	{
		
		if($_POST['qty'] < 1)
		{
		  $sql = " DELETE FROM `tbl_recusition_items_temp`  WHERE `id` = '".$row['id']."' AND account_id = '".$_SESSION['accountId']."' ";
		}
		else
		{
		 $sql = " UPDATE `tbl_recusition_items_temp` SET `qty` = ".$_POST['qty']." WHERE `id` = '".$row['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";
		}
		
		
	}elseif($_POST['qty'] > 0){
		
		$sql = "INSERT INTO `tbl_recusition_items_temp` SET
		deptId = '".$_POST['deptId']."'
		,`userId` = '".$_SESSION['id']."'
		, `pId` = '".$_POST['pId']."' 
		, `qty` = '".$_POST['qty']."'
		, `account_id` = '".$_SESSION['accountId']."'  ";
	}

	mysqli_query($con, $sql);
	
	
	$sql=" SELECT * FROM  tbl_recusition_items_temp WHERE deptId = '".$_POST['deptId']."' AND `userId` = '".$_SESSION['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";
	$result = mysqli_query($con, $sql);
			while( $row = mysqli_fetch_array($result) )
			{
				$sqlSet=" SELECT * FROM tbl_stocks WHERE pId= '".$row['pId']."' AND account_id = '".$_SESSION['accountId']."'  ";
				$resultSet= mysqli_query($con, $sqlSet);
				$stckRow= mysqli_fetch_array($resultSet);

				$productPrice+= $stckRow['stockPrice']*$row['qty'];
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
	$resHtml ='<div class="container"><div class="prcTable">';
	if( isset($_SESSION['itemCharges'][3]) && count($_SESSION['itemCharges'][3]) > 0  )
	{
		$resHtml .='<div class="price justify-content-between"><div class="p-2 delIcn text-center"></div>';
		$resHtml .='<div class="p-2 txnmRow"><p>'.showOtherLangText('Sub Total').'</p>
                                                </div><div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                        <p> '.getNumFormtPrice($totalChargePrice,$getDefCurDet['curCode']).'</p>';
		$resHtml .='</div></div></div>';
	}
	//show here order level fixed/ percent/ tax charges		
	$taxCharges=0;
	$fixedCharges=0;
	$perCharges=0;
	if( isset($_SESSION['itemCharges'][3]) && count($_SESSION['itemCharges'][3]) > 0  )
	{
		$itemIds = implode(',', $_SESSION['itemCharges'][3]);

		//start order level item fixed charges
		$sqlSet = " SELECT * FROM tbl_order_fee WHERE id IN(".$itemIds.")  AND account_id = '".$_SESSION['accountId']."' AND feeType ='2' ";
		$resRows = mysqli_query($con, $sqlSet);

		while( $fixRow = mysqli_fetch_array($resRows) ) 
		{
			$feeName = $fixRow['feeName'];
			$fixedCharges += $fixRow['amt'];

			$resHtml .='<div class="price justify-content-between taxRow">
                                                <div class="p-2 delIcn text-center">

			<a title="'.showOtherLangText('Delete').'" href="javascript:void(0)" onClick="getDelNumb('.$fixRow['id'].', 3)" style="color:#808080" class="glyphicon glyphicon-trash"><i class="fa-solid fa-trash-can"></i></a>

			</div><div class="p-2 txnmRow"><p>'.$feeName.'</p></div><div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                        <p> '.getNumFormtPrice($fixRow['amt'],$getDefCurDet['curCode']).'</p>
                                                    </div>
                                                </div>
                                            </div>';

		}

		//start order level item percent charges
		$sqlSet = " SELECT * FROM tbl_order_fee WHERE id IN(".$itemIds.") AND account_id = '".$_SESSION['accountId']."' AND feeType = '3'  ";
		$resRows = mysqli_query($con, $sqlSet);
		while($perRow = mysqli_fetch_array($resRows))
		{

			$feeName = $perRow['feeName'];
			$perCharges += $perRow['amt'];
			$perChargeTotal = ($totalChargePrice*$perRow['amt']/100);


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
		$sqlSetQry = " SELECT * FROM tbl_order_fee WHERE id IN(".$itemIds.")  AND account_id = '".$_SESSION['accountId']."' AND feeType ='1' ";
		$resultRows = mysqli_query($con, $sqlSetQry);
		//calculating tax charges
		$totalFixedCharges= $fixedCharges;//calculating total fixed charge
		$totalPerCharges= ($totalChargePrice*$perCharges/100);//calculating total per charge

		while( $taxRow = mysqli_fetch_array($resultRows) ) 
		{ 
			$feeName = $taxRow['feeName'];
			$taxCharges += $taxRow['amt'];
			$taxPerChargesTotal = ( ($totalChargePrice+$totalFixedCharges+$totalPerCharges )*$taxRow['amt']/100 );

		$resHtml .='<div class="price justify-content-between taxRow">
                                                <div class="p-2 delIcn text-center"><a title="'.showOtherLangText('Delete').'" href="javascript:void(0)" onClick="getDelNumb('.$taxRow['id'].', 3)" style="color:#808080" class="glyphicon glyphicon-trash"><i class="fa-solid fa-trash-can"></i></a></a></div> <div class="p-2 txnmRow">
                                                    <p>'.$feeName.' '.$taxRow['amt'].' %</p></div><div class="d-flex align-items-center justify-content-end curRow">
                                                    <div class="p-2">
                                                        <p> '.getNumFormtPrice($perChargeTotal,$getDefCurDet['curCode']).'</p>
                                                    </div>
                                                </div>
                                            </div>';

		}
		
	}//end of if condition

	//calculating net value here
	$totalTaxCharges= ( ($totalChargePrice+$totalFixedCharges+$totalPerCharges)*$taxCharges/100);//calculating total tax value 
	$netTotalValue= ($totalChargePrice+$totalFixedCharges+$totalPerCharges+$totalTaxCharges);

   		$stylegrand = '';
             if(!isset($_SESSION['itemCharges'][3]) || count($_SESSION['itemCharges'][3]) == 0)
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

//--------------------------------------------------------------------------------------------



}//end of add new requisition