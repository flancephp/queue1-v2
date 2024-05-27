<?php
include_once('inc/dbConfig.php'); //connection details

//Excel stock take part only goes here
if(isset($_SESSION['newStockTakeVal']))
{
	echo "cameHere1===";
	if( is_array($_SESSION['newStockTakeVal']) && !empty($_SESSION['newStockTakeVal']) )
	{
		foreach($_SESSION['newStockTakeVal'] as $row)
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

			$sql = "SELECT p.*, s.qty stockQty  FROM tbl_stocks s 

			INNER JOIN tbl_products p ON(s.pId = p.id) AND s.account_id = p.account_id WHERE p.barCode IN( ".implode(',', $barCodesArr)." )   AND p.account_id = '".$_SESSION['accountId']."' ";

			$importQry = mysqli_query($con, $sql);

	}

}
///////////////////////////////////////////////
// Mobile stock take part goes here
if( isset($_SESSION['processId']) && isset($_SESSION['storeId']) )
{	
	$sql=" SELECT t.*, p.barCode BarCode FROM  tbl_mobile_items_temp t 
	INNER JOIN tbl_products p ON(p.id = t.pId) AND (p.account_id = t.account_id)

	WHERE t.processId = '".$_SESSION['processId']."'  AND t.account_id = '".$_SESSION['accountId']."'  AND t.`stockTakeType` = 1 AND t.status=1 ";
	$result = mysqli_query($con, $sql);

	$pIdArr = [];
	while($stockTakeRes = mysqli_fetch_array($result) )
	{
		$fileDataRows[$stockTakeRes['BarCode']] = $stockTakeRes['qty'];
		$pIdArr[] = $stockTakeRes['pId'];
	}

	$sql = "SELECT p.*, s.qty stockQty, IF(u.name!='',u.name,p.unitC) unitC  FROM tbl_stocks s 
	INNER JOIN tbl_products p ON(s.pId = p.id) AND (s.account_id = p.account_id) 
	LEFT JOIN tbl_units u ON(u.id=p.unitC) AND (u.account_id = p.account_id)
	WHERE p.id IN( ".implode(',', $pIdArr)." )  AND p.account_id = '".$_SESSION['accountId']."' ";
	$importQry = mysqli_query($con, $sql);
}



 include 'pdf/fpdf.php';

 include 'pdf/exfpdf.php';

 include 'pdf/easyTable.php';



 $pdf=new exFPDF();

 $pdf->AddPage(); 

 $pdf->SetFont('helvetica','',10);



 $table1=new easyTable($pdf, 2);

 $table1->easyCell('Stock Take', 'font-size:30; font-style:B; font-color:#00bfff;');

 //$table1->easyCell('', 'img:Pics/fpdf.png, w80; align:R;');

 $table1->printRow();





 $table1->endTable(5);



//====================================================================





 $table=new easyTable($pdf, '{15, 100, 40, 30, 20, 30}','align:C{CLCCCC};border:1; border-color:#a1a1a1; ');



 $table->rowStyle('align:{CCCCCC};valign:M; font-family:times; font-style:B;');





$colsArr = 

		  [

		  1 => ''.showOtherLangText('S.no').'',

			2 => ''.showOtherLangText('Item').'',

			3 => ''.showOtherLangText('Bar Code').'',

			4 => ''.showOtherLangText('Unit').'',

			5 => ''.showOtherLangText('Qty').'',

			6 => ''.showOtherLangText('Stock Take').'',

			7 => ''.showOtherLangText('Variances').'',

		  ];

	foreach($colsArr as $colHeading)

	{				  

		$table->easyCell($colHeading);

	}

	

 $table->printRow();

 

 

 //------------------------------------------------------------------------------------------------------------------------------------

 $i=0;

while($row = mysqli_fetch_array($importQry))
{	
	$bgcolor='';
    if($i%2)
    {
      $bgcolor='bgcolor:#C0C0C0;';
    }

	$i++;			

	
 	$colsValArr = 

  	[
  	1 => $i,

		2 => $row['itemName'],

		3 => $row['barCode'],

		4 => $row['unitC'],

		5 => $row['stockQty'],

		6 => $fileDataRows[$row['barCode']],

		7 => $fileDataRows[$row['barCode']]-$row['stockQty'],
  	];

		// $table->rowStyle('valign:M;border:LRB;paddingY:2;'.$bgcolor);	

		foreach($colsValArr as $key=>$columnVal)
		{				
			$table->easyCell($columnVal);
		}

    	$table->printRow();

}
//------------------------------------------------------------------------------------------------------------------------------------
 $table->endTable();


 $pdf->Output(); 


?>