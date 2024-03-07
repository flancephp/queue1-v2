<?php
include_once('inc/dbConfig.php'); //connection details


$mainSqlQry = " SELECT tp.*, pc.name parentCatName, IFNULL(c.name, 'z')childCatName, IF(uc.name!='', uc.name,tp.unitC) countingUnit,
IF(up.name!='', up.name,tp.unitP) purchaseUnit FROM  tbl_products tp 

LEFT JOIN tbl_units up ON
    (up.id = tp.unitP) AND up.account_id = tp.account_id

LEFT JOIN tbl_units uc ON
    (uc.id = tp.unitC) AND uc.account_id = tp.account_id

LEFT JOIN tbl_category pc ON(pc.id = tp.parentCatId) AND pc.account_id = tp.account_id

LEFT JOIN tbl_category c ON(c.id = tp.catId)  AND c.account_id = tp.account_id
where tp.account_id = '".$_SESSION['accountId']."'

	    GROUP BY tp.id order by tp.id desc  ";

$stockQry = mysqli_query($con, $mainSqlQry);



$header  = 

	[

		2 => ''.showOtherLangText('Item').'',

		3 => ''.showOtherLangText('BarCode').'',

		4 => ''.showOtherLangText('Category').'',

		5 => ''.showOtherLangText('SubCategory').'',

		6 => ''.showOtherLangText('Storage').'',

		7 => ''.showOtherLangText('Supplier').'',

		8 => ''.showOtherLangText('Departments').'',

		9 => ''.showOtherLangText('Unit.P').'',

		10 => ''.showOtherLangText('Factor').'',

		11 => ''.showOtherLangText('Unit.C').'',

		12 => ''.showOtherLangText('LastPrice').'',

		13 => ''.showOtherLangText('MinLevel').'',

		14 => ''.showOtherLangText('MaxLevel').''

	];







header('Content-Type: text/csv; charset=utf-8');

header('Content-Disposition: attachment; filename=ItemList.csv');

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

	 

	 $csvRow =  [

			2 => $row['itemName'],

			3 => "\t".$row['barCode'],

			4 => $row['parentCatName'],

			5 => $catNames,

			6 => $storageDeptRow['name'],

			

			7 => $supNames,

			8 => $deptNames,

			9 => $row['purchaseUnit'],

			

			10 => $row['factor'],

			

			11 => $row['countingUnit'],

			12 => getPrice($row['price']).' '.$getDefCurDet['curCode'],

			13 => $row['minLevel'],

			14 => $row['maxLevel']

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



?>

