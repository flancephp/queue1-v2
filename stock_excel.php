<?php
include_once('inc/dbConfig.php'); //connection details

$cond = '';

if( isset($_SESSION['getVals']['filterByStorage']) && ($_SESSION['getVals']['filterByStorage']) != '' )
{
	$cond = " AND tp.storageDeptId = '".$_SESSION['getVals']['filterByStorage']."' ";
}

if( isset($_SESSION['getVals']['subCatId']) && $_SESSION['getVals']['subCatId'])
{
	$cond .= " AND c.id = '".$_SESSION['getVals']['subCatId']."' ";
}

if( isset($_SESSION['getVals']['suppId']) && $_SESSION['getVals']['suppId'])
{

	$cond .= " AND tp.ID IN(SELECT productId FROM tbl_productsuppliers WHERE supplierId = '".$_SESSION['getVals']['suppId']."' AND account_id = '".$_SESSION['accountId']."' ) ";
}





$mainSqlQry = "SELECT 
tp.*, 
s.qty stockQty, 
s.lastPrice stockLastPrice, 
s.stockPrice sPrice, 
s.stockValue, 
pc.name parentCatName, 
IFNULL(c.name, 'z') childCatName, 
IF(uc.name!='', uc.name,tp.unitC) countingUnit,
IF(up.name!='', up.name,tp.unitP) purchaseUnit 

	FROM tbl_stocks s 

		INNER JOIN tbl_products tp ON(s.pId = tp.id) AND tp.status = 1 AND  s.account_id = tp.account_id

		LEFT JOIN tbl_units up ON(up.id = tp.unitP) AND up.account_id = tp.account_id

		LEFT JOIN tbl_units uc ON(uc.id = tp.unitC) AND uc.account_id = tp.account_id

		LEFT JOIN tbl_category pc ON(pc.id = tp.parentCatId) AND pc.account_id = tp.account_id

		LEFT JOIN tbl_category c ON(c.id = tp.catId) AND c.account_id = tp.account_id 

	   ". $cond . " WHERE tp.account_id = '".$_SESSION['accountId']."' GROUP BY tp.id ". $orderBy;

$stockQry = mysqli_query($con, $mainSqlQry);



$header  = 

		 [

			2 => ''.showOtherLangText('Item').'',

			3 => ''.showOtherLangText('Bar Code').'',

			4 => ''.showOtherLangText('Unit.P').'',

			5 => ''.showOtherLangText('Factor').'',

			6 => ''.showOtherLangText('Unit.C').'',

			7 => ''.showOtherLangText('Category').'',

			8 => ''.showOtherLangText('Supplier').'',

			9 => ''.showOtherLangText('Departments').'',

			10 => ''.showOtherLangText('Min Qty').'',

			11 => ''.showOtherLangText('Max Qty').'',

			12 => ''.showOtherLangText('Qty').'',

			13 => ''.showOtherLangText('Last Price').'',

			14 => ''.showOtherLangText('Stock Value').'',

			15 => ''.showOtherLangText('Stock Price').'',

		  ];



if( isset($_SESSION['showFieldsStock']) )

{

	foreach($header as $key=>$val)

	{

		if( !in_array($key, $_SESSION['showFieldsStock']) )

		{

			unset($header[$key]);

		}

	}

}



header('Content-Type: text/csv; charset=utf-8');

header('Content-Disposition: attachment; filename=stock.csv');

//echo "\xEF\xBB\xBF"; // UTF-8 BOM

$output = fopen('php://output', 'w');
fprintf($output, chr(0xEF) .chr(0xBB) .chr(0xBF));

fputcsv($output, $header);







//------------------------------------------------------------------------------------------------------------------------------------

while($row = mysqli_fetch_array($stockQry) )
{
	$deptNames = getProdcutDepartments($row['id']);

	$supNames = getProdcutSuppls($row['id']);

	$storageDeptRow = getStoreDetailsById($row['storageDeptId']);	

	 $catNames = ($row['childCatName'] == 'z' ? '' :  $row['childCatName']);

	 $csvRow = 

	  [

		2 => $row['itemName'],

		3 => $row['barCode'],

		4 => $row['purchaseUnit'],

		5 => $row['factor'],

		6 => $row['countingUnit'],

		7 => $catNames,

		8 => $supNames,

		9 => $deptNames,

		10 => $row['minLevel'],

		11 => $row['maxLevel'],

		12 => $row['stockQty'],

		13 => getPrice($row['stockLastPrice']) .' '.$getDefCurDet['curCode'],

		14 => getPrice($row['stockValue']) .' '.$getDefCurDet['curCode'],

		15 => getPrice($row['sPrice']) .' '.$getDefCurDet['curCode']

	  ];

		

	if( isset($_SESSION['showFieldsStock']) )
	{
		foreach($csvRow as $key=>$val)
		{
			if( !in_array($key, $_SESSION['showFieldsStock']) )
			{
				unset($csvRow[$key]);
			}

		}

	}		



	fputcsv($output, $csvRow);

}

//------------------------------------------------------------------------------------------------------------------------------------

die();

?>

