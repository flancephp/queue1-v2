<?php 
//session_start();
include('inc/dbConfig.php'); //connection details
	
	
	$sqlSet = " SELECT ordNumber FROM tbl_orders WHERE account_id = '".$_SESSION['accountId']."'  ORDER BY id DESC LIMIT 1 ";
	$ordQry = mysqli_query($con, $sqlSet);
	$ordResult = mysqli_fetch_array($ordQry);

	$ordNumber = $ordResult['ordNumber'] > 0 ? ($ordResult['ordNumber']+1) : 100000;
	
	
	$sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$_SESSION['convertItemsPost']['rawItem']."' AND account_id = '".$_SESSION['accountId']."'  ";
	$result = mysqli_query($con, $sql);
	$stockRow = mysqli_fetch_array($result);
	
	$ordPrice = $_SESSION['convertItemsPost']['qtyToConvert']*$stockRow['lastPrice'];
		
	$qry = " INSERT INTO `tbl_orders` SET 
				`ordType` = 4,
				`ordNumber` = '".$ordNumber."',
				`supplierId` = '".getProductSupplr($_SESSION['convertItemsPost']['rawItem'])."',
				`orderBy`  = '".$_SESSION['id']."',
				`ordDateTime` = '".date('Y-m-d h:i:s')."',
				`setDateTime` = '".date('Y-m-d h:i:s')."',
				`ordAmt` = '".$ordPrice."',
				`account_id` = '".$_SESSION['accountId']."', 
				`status` = 2	";
				
	mysqli_query($con, $qry);
	$ordId = mysqli_insert_id($con);
	
	$ordAmt = 0;
		
	//For raw products only stock qty needs to decrease
	
		$insertQry = " INSERT INTO `tbl_order_details` (`account_id`, `ordId`, `pId`, `qty` , `qtyReceived`, `price`, `totalAmt`) VALUES 
						   (
						   	'".$_SESSION['accountId']."', 
							'".$ordId."',
							'".$_SESSION['convertItemsPost']['rawItem']."',
							'".$stockRow['qty']."',
							'".$_SESSION['convertItemsPost']['qtyToConvert']."',
							'".$stockRow['lastPrice']."',
							'".($ordPrice)."'
						) ";
		mysqli_query($con, $insertQry);	
			
						
			 $upQry = " UPDATE  `tbl_stocks` SET
			 `qty` = ".($stockRow['qty']-$_SESSION['convertItemsPost']['qtyToConvert'])."
			 WHERE id = '".$stockRow['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";
			mysqli_query($con, $upQry);
			
			
			
			$upQry = " UPDATE  `tbl_stocks` SET
									`stockPrice` = (stockValue/qty) 
							 WHERE id = '".$stockRow['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";
					mysqli_query($con, $upQry);
	//End
	
	//Queries for to converted item
	
		$sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$_SESSION['convertItemsPost']['convertItem']."'  AND account_id = '".$_SESSION['accountId']."' ";
		$result = mysqli_query($con, $sql);
		$stockRow = mysqli_fetch_array($result);
		
		if(!$stockRow)
		{
			$upQry = " INSERT INTO  `tbl_stocks` SET
			 `pId` = '".$_SESSION['convertItemsPost']['convertItem']."', 
			 `qty` = ".$_SESSION['convertItemsPost']['convertedQty'].",
			 `account_id` = '".$_SESSION['accountId']."', 
			 `lastPrice` = '".$_SESSION['convertItemsPost']['unitPrice']."',
			 `stockValue` = '".($_SESSION['convertItemsPost']['convertedQty']*$_SESSION['convertItemsPost']['unitPrice'])."'  ";
			mysqli_query($con, $upQry);
			
			$sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$_SESSION['convertItemsPost']['convertItem']."'  AND account_id = '".$_SESSION['accountId']."' ";
			$result = mysqli_query($con, $sql);
			$stockRow = mysqli_fetch_array($result);
		}	
		else
		{
			$upQry = " UPDATE  `tbl_stocks` SET
			`qty` = ".($stockRow['qty']+$_SESSION['convertItemsPost']['convertedQty']).",
			`lastPrice` = ".($_SESSION['convertItemsPost']['unitPrice']).",
			`stockValue` = ( stockValue + ".($ordPrice)." )
			WHERE id = '".$stockRow['id']."' AND account_id = '".$_SESSION['accountId']."'   ";
			mysqli_query($con, $upQry);
		}
		
		$insertQry = " INSERT INTO `tbl_order_details` ( `account_id`, `ordId`, `pId`, `qty`, `qtyReceived` , `price`, `totalAmt` ,`factor`) VALUES  (  
							'".$_SESSION['accountId']."', 
							'".$ordId."', 
							
							'".$_SESSION['convertItemsPost']['convertItem']."', 
							'".$stockRow['qty']."',
							'".$_SESSION['convertItemsPost']['convertedQty']."',
							'".$_SESSION['convertItemsPost']['unitPrice']."',
							'".$ordPrice."',
							'1'
						) ";
		mysqli_query($con, $insertQry);				
						
		
		$upQry = " UPDATE  `tbl_stocks` SET
									`stockPrice` = (stockValue/qty) 
							 WHERE id = '".$stockRow['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";
					mysqli_query($con, $upQry);
		
		$sql = "SELECT *  FROM tbl_stocks  WHERE id = '".$stockRow['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";
				$stkQry = mysqli_query($con, $sql);
				$stkRow = mysqli_fetch_array($stkQry);
				
				//update last price and stock
				 $upQry = " UPDATE  `tbl_products` SET
					 `price` = '".$stkRow['lastPrice']."',
					 `stockPrice` = '".$stkRow['stockPrice']."'
				WHERE id = '".$stockRow['pId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
				mysqli_query($con, $upQry);
				//update last price			
				
				$upQry = " UPDATE  `tbl_order_details` SET
			 `lastPrice` = '".$stkRow['lastPrice']."', 
			 `stockPrice` = '".$stkRow['stockPrice']."',
			 `stockQty` = '".$stkRow['qty']."'
			
		  		WHERE ordId = '".$ordId."' AND pId = '".$stockRow['pId']."'  AND account_id = '".$_SESSION['accountId']."' ";
				mysqli_query($con, $upQry);
				
				
	//End
	$url = 'stockView.php?convertRawItem=1';
	header('Location: '.$url);

	
	exit;

?>