<?php
include_once('inc/dbConfig.php'); //connection details

if( isset($_SESSION['processId']) && isset($_SESSION['storeId']) )
{	
	$backLink = 'viewMobileStockTake.php?stockTakeId='.$_SESSION['storeId'];
	
	
	$sql=" SELECT t.*, p.barCode BarCode  FROM  tbl_mobile_items_temp t 
	INNER JOIN tbl_products p ON(p.id = t.pId) AND p.account_id = t.account_id 
	WHERE t.processId = '".$_SESSION['processId']."'  AND t.account_id = '".$_SESSION['accountId']."'  AND t.`stockTakeType` = 1 AND t.status=1 ";
	$result = mysqli_query($con, $sql);
	
	$pIdArr = [];
	while($stockTakeRes = mysqli_fetch_array($result) )
	{
		$fileDataRows[$stockTakeRes['BarCode']] = $stockTakeRes['qty'];
		$pIdArr[] = $stockTakeRes['pId'];
	}
	
$sql = "SELECT p.*, s.qty stockQty, IF(u.name!='',u.name,p.unitC) unitC  FROM tbl_stocks s 
INNER JOIN tbl_products p ON(s.pId = p.id) AND s.account_id = p.account_id 
LEFT JOIN tbl_units u ON(u.id=p.unitC) AND (u.account_id = p.account_id)
WHERE p.id IN( ".implode(',', $pIdArr)." )  AND p.account_id = '".$_SESSION['accountId']."' ";
	$importQry = mysqli_query($con, $sql);
}

elseif( isset($_SESSION['stockTake']) )

{

	if( is_array($_SESSION['stockTake']) && !empty($_SESSION['stockTake']) )

	{

		foreach($_SESSION['stockTake'] as $row)

		{

			$fileDataRows[$row['BarCode']] = $row['StockTake'];

			
			$sqlSet = " SELECT * FROM tbl_products WHERE barCode = '".$row['BarCode']."' AND storageDeptId = '".$_SESSION['storeId']."'   AND account_id = '".$_SESSION['accountId']."'  ";

			$resultSet = mysqli_query($con, $sqlSet);

			$productRes = mysqli_fetch_array($resultSet);

			if(!$productRes)

			{

				$notFoundProducts[] = $row['BarCode'];

			}

			else

			{

				$barCodesArr[] = $row['BarCode'];

			}

		}

			$sql = "SELECT p.*, s.qty stockQty, IF(u.name!='',u.name,p.unitC) unitC  FROM tbl_stocks s 
			INNER JOIN tbl_products p ON(s.pId = p.id) AND s.account_id = p.account_id 
			LEFT JOIN tbl_units u ON(u.id=p.unitC) AND (u.account_id = p.account_id)
			WHERE p.barCode IN( ".implode(',', $barCodesArr)." )  AND p.account_id = '".$_SESSION['accountId']."'  ";
			$importQry = mysqli_query($con, $sql);

	}

}

$header = 

		  [

			1 => ''.showOtherLangText('Item').'',

			2 => ''.showOtherLangText('Bar Code').'',

			3=> ''.showOtherLangText('Unit').'',

			4 => ''.showOtherLangText('Qty').'',

			5 => ''.showOtherLangText('Stock Take').'',

			6 => ''.showOtherLangText('Variances').'',

		  ];

header('Content-Type: text/csv; charset=utf-8');

header('Content-Disposition: attachment; filename=stockTake.csv');

$output = fopen('php://output', 'w');

fprintf($output, chr(0xEF) .chr(0xBB) .chr(0xBF));

fputcsv($output, $header);


//------------------------------------------------------------------------------------------------------------------------------------

while($row = mysqli_fetch_array($importQry))

{ 

	 $csvRow = 

	  [

		1 => $row['itemName'],

		2 => $row['barCode'],

		3 => $row['unitC'],

		4 => $row['stockQty'],

		5 => $fileDataRows[$row['barCode']],

		6 => $fileDataRows[$row['barCode']]-$row['stockQty'],

	  ];

	fputcsv($output, $csvRow);
}

//------------------------------------------------------------------------------------------------------------------------------------

die();

?>

