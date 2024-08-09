<?php
include_once('inc/dbConfig.php'); //connection details



$header  = 

	[

		1 => ''.showOtherLangText('Number').'',

		2 => ''.showOtherLangText('Date').'',

		3 => ''.showOtherLangText('User').'',

		4 => ''.showOtherLangText('Type').'',

		5 => ''.showOtherLangText('Refer To').'',

		6 => ''.showOtherLangText('Invoice No').'',

		7 => ''.showOtherLangText('Value').'',

		8 => ''.showOtherLangText('Status').'',

		9 => ''.showOtherLangText('Account').'',

	];

	

	if( isset($_SESSION['getVals']['ordType']) )
	{
     switch($_SESSION['getVals']['ordType'])
     {    
        case 3: //stockTake
        unset( $header[6] );
        unset( $header[8] );
        unset( $header[9] );
        break;

        case 4: //raw convert item
        unset( $header[5] );
        unset( $header[6] );
        unset( $header[8] );
        unset( $header[9] );
        break;
      }
	}

	if( isset($_SESSION['getVals']['suppMemStoreId']) && $getTxtById == 'storeId')
	{   
	   unset( $header[6] );
	   unset( $header[8] );
	   unset( $header[9] );
	}	  

// if( isset($_SESSION['showFields']) )
// {
// 	 foreach($header as $key=>$val)
// 	 {
// 		if( !in_array($key, $_SESSION['showFields']) )
// 		{
// 			unset($header[$key]);
// 		}
// 	 }
// }



$cond = '';

if( isset($_SESSION['getVals']['fromDate']) && $_SESSION['getVals']['fromDate'] != '' && $_SESSION['getVals']['toDate'] != '')
{
	$cond = " AND DATE(setDateTime) BETWEEN '".date('Y-m-d', strtotime($_SESSION['getVals']['fromDate']) )."'   AND '".date('Y-m-d', strtotime($_SESSION['getVals']['toDate']) )."' ";
}

// Order type filter
if( isset($_SESSION['getVals']['ordType']) && $_SESSION['getVals']['ordType'])
{
	$cond .= " AND o.ordType = '".$_SESSION['getVals']['ordType']."' AND o.account_id = '".$_SESSION['accountId']."'  ";
}


// supplier || Member || store Filter
if( isset($_SESSION['getVals']['suppMemStoreId']) && $_SESSION['getVals']['suppMemStoreId'])
{
	$suppMemStoreId = $_SESSION['getVals']['suppMemStoreId'];
	$getSupMemStoreId = explode('_', $suppMemStoreId);
	$getTxtById = $getSupMemStoreId[0];
	$getId = $getSupMemStoreId[1];

	if ($getTxtById == 'suppId') {
      $cond .= " AND o.supplierId = ".$getId."  ";
 	}

 	if ($getTxtById == 'deptUserId') {
   	$cond .= " AND o.recMemberId = ".$getId."  ";
 	}

 	if ($getTxtById == 'storeId') {
   	$cond .= " AND o.storeId = ".$getId."  ";
 	}
}

// user filter
if( isset($_SESSION['getVals']['userId']) && $_SESSION['getVals']['userId'])
{
	$cond .= " AND o.orderBy = '".$_SESSION['getVals']['userId']."' ";
}

// Payment Status Filter
if ($_SESSION['getVals']['statusType'] == 1 ) {
    
   $cond .= "AND o.paymentStatus = 1 AND o.ordType = 1 ";
}
if ($_SESSION['getVals']['statusType'] == 2 ) {
    
   $cond .= "AND o.paymentStatus = 1 AND o.ordType = 2 ";
}
if ($_SESSION['getVals']['statusType'] == 3 ) {
    
   $cond .= "AND (o.paymentStatus = 2 OR o.paymentStatus = 0) AND o.ordType IN(2,1) ";
}

// for account of payment
if (isset($_SESSION['getVals']['accountNo']) && $_SESSION['getVals']['accountNo'] != '')
{
   if ($_SESSION['getVals']['statusType'] == '') {
      $cond .= " AND o.bankAccountId = '".$_SESSION['getVals']['accountNo']."' AND o.paymentStatus = 1 ";
   }
   if ($_SESSION['getVals']['statusType'] == 1) {
      $cond .= " AND o.bankAccountId = '".$_SESSION['getVals']['accountNo']."' AND o.paymentStatus = 1 AND o.ordType = 1 ";
   }
   if ($_SESSION['getVals']['statusType'] == 2) {
      $cond .= " AND o.bankAccountId = '".$_SESSION['getVals']['accountNo']."' AND o.paymentStatus = 1 AND o.ordType = 2 ";
   }
    
}


// Date sorting
if ($_SESSION['getVals']['dateType'] =='') {
   $cond .= " GROUP BY o.id ORDER BY o.id desc ";
}
if ($_SESSION['getVals']['dateType'] !='' && $_SESSION['getVals']['dateType'] == 1) {
   $cond .= " GROUP BY o.id ORDER BY o.ordDateTime desc ";
}
if ($_SESSION['getVals']['dateType'] !='' && $_SESSION['getVals']['dateType'] == 2) {
   $cond .= " GROUP BY o.id ORDER BY o.setDateTime desc ";
}
if ($_SESSION['getVals']['dateType'] !='' && $_SESSION['getVals']['dateType'] == 3) {
   $cond .= " GROUP BY o.id ORDER BY o.paymentDateTime desc, o.setDateTime desc ";
}


//Access Invoice permission for user
$accessInvoicePermission = get_access_invoice_permission($_SESSION['designation_id'],$_SESSION['accountId']);
//Access payment permission for user
$accessPaymentPermission = get_access_payment_permission($_SESSION['designation_id'],$_SESSION['accountId']);



$mainSqlQry = " SELECT o.*, 
od.pId,
du.name AS deptUserName,
s.name AS storeName,
sp.name AS suppName,
ac.accountName,
du.receive_inv
FROM tbl_order_details od 

INNER JOIN tbl_orders o 
	ON(o.id = od.ordId) AND o.account_id = od.account_id
LEFT JOIN tbl_accounts ac ON
	(o.bankAccountId = ac.id) AND o.account_id = ac.account_id
LEFT JOIN tbl_deptusers du ON
	(o.recMemberId = du.id) AND o.account_id = du.account_id
LEFT JOIN tbl_stores s ON
	(o.storeId = s.id) AND o.account_id = s.account_id
LEFT JOIN tbl_suppliers sp ON
	(o.supplierId = sp.id) AND o.account_id = sp.account_id

WHERE o.status = 2 AND o.account_id='".$_SESSION['accountId']."'  ".$cond." ";

$historyQry = mysqli_query($con, $mainSqlQry);





header('Content-Type: text/csv; charset=utf-8');

header('Content-Disposition: attachment; filename=history.csv');
//echo "\xEF\xBB\xBF"; // UTF-8 BOM

$output = fopen('php://output', 'w');
fprintf($output, chr(0xEF) .chr(0xBB) .chr(0xBF));

fputcsv($output, $header);







//------------------------------------------------------------------------------------------------------------------------------------



while($orderRow = mysqli_fetch_array($historyQry) )

{

	// supplier Store Member name
	if ($orderRow['suppName'] =='' && $orderRow['storeName'] =='')
   {
      $suppMemStoreId = $orderRow['deptUserName'];
   }
   elseif ($orderRow['deptUserName'] =='' && $orderRow['storeName'] =='') {
      $suppMemStoreId = $orderRow['suppName'];
   }
   else
   {
      $suppMemStoreId = $orderRow['storeName'];
   }



   //Payment status
   if ($orderRow['paymentStatus']==1 && $orderRow['ordType']==1)
   {
      $paymentStatus = ''.showOtherLangText('Paid').'';
   }
   elseif($orderRow['paymentStatus']==1 && $orderRow['ordType']==2) 
   {
      $paymentStatus = ''.showOtherLangText('Received').'';  
   }
   elseif($orderRow['ordType']==3 || $orderRow['ordType']==4)
   {
      $paymentStatus = ' ';  
   }
   elseif(($orderRow['ordType'] == 2 && $orderRow['receive_inv'] == 0) || $orderRow['ordType'] == 2 && $accessInvoicePermission['type_id'] == 0)
   {
      $paymentStatus = ' ';  
   }
   elseif($orderRow['ordType'] == 1 && $accessPaymentPermission['type_id']==0)
   {
      $paymentStatus = ' ';  
   }
   else
   {
      $paymentStatus = ''.showOtherLangText('Pending').'';
   }


   	// for date time filter
	if ($_SESSION['getVals']['dateType'] == 1)
	{
	   $dateType = ($orderRow['ordDateTime'] != '0000-00-00 00:00:00' ? date('d/m/y h:i', strtotime($orderRow['ordDateTime']) ) : '');
	}
	elseif($_SESSION['getVals']['dateType'] == 2)
	{
	   $dateType = ($orderRow['setDateTime'] != '0000-00-00 00:00:00' ? date('d/m/y h:i', strtotime($orderRow['setDateTime']) ) : '');
	}
	elseif($_SESSION['getVals']['dateType'] == 3)
	{
	   $dateType = ( $orderRow['paymentDateTime'] != '0000-00-00 00:00:00' ? date('d/m/y h:i', strtotime($orderRow['paymentDateTime']) ) : date('d/m/y h:i', strtotime($orderRow['setDateTime'])) );
	}
	else
	{
	   $dateType = ($orderRow['setDateTime'] != '0000-00-00 00:00:00' ? date('d/m/y h:i', strtotime($orderRow['setDateTime']) ) : '');
	}
	

	$sqlSet = " SELECT * FROM tbl_user WHERE id = '".$orderRow['orderBy']."'  AND account_id = '".$_SESSION['accountId']."'  ";

	$resultSet = mysqli_query($con, $sqlSet);

	$userArr = mysqli_fetch_array($resultSet);

	$user = $userArr['name']; 

	$curDet =  getCurrencyDet($orderRow['ordCurId']);

	if($orderRow['ordType'] == 1)
	{
		$ordType = ' '.showOtherLangText('Issued In').' ';
	}
	elseif($orderRow['ordType'] == 2)
	{
		$ordType = ' '.showOtherLangText('Issued Out').' ';
	}
	elseif($orderRow['ordType'] == 3)
	{
		$variances = getOrdItemVariancesAmt($orderRow['id']);

		$variancesTotAmt = $variances['variancesTot'];

		$ordType = ' '.showOtherLangText('Stock Take').' ';
	}
	else
	{
		$variances = getOrdItemVariancesAmt($orderRow['id']);

		$variancesTotAmt = $variances['variancesTot'];

		$ordType = ' '.showOtherLangText('Raw Item Convert').' ';
	}

	

	$row = 

			  [

				1 => $orderRow['ordNumber'],

				2 => $dateType,

				3 => $user,

				4 => $ordType,

				5 => $suppMemStoreId,

				6 => "\t".$orderRow['invNo'],

				7 => ($orderRow['ordAmt'] > 0 ? getPrice($orderRow['ordAmt']) .' '.$getDefCurDet['curCode'] : getPrice($variancesTotAmt).' '.$getDefCurDet['curCode'])
				."\n".
               	($orderRow['ordCurAmt'] > 0 ? showOtherCur($orderRow['ordCurAmt'], $curDet['id']) : ''),

				8 => $paymentStatus,
				
				9 => ($orderRow['paymentStatus']==1 ? $orderRow['accountName'] : ''),

			  ];

			  

	if( isset($_SESSION['getVals']['ordType']) )
	{
     switch($_SESSION['getVals']['ordType'])
     {    
        case 3: //stockTake
        unset( $row[6] );
        unset( $row[8] );
        unset( $row[9] );
        break;

        case 4: //raw convert item
        unset( $row[5] );
        unset( $row[6] );
        unset( $row[8] );
        unset( $row[9] );
        break;
      }
	}

	if( isset($_SESSION['getVals']['suppMemStoreId']) && $getTxtById == 'storeId')
	{   
	   unset( $row[6] );
	   unset( $row[8] );
	   unset( $row[9] );
	}
	
	fputcsv($output, $row);

}

//------------------------------------------------------------------------------------------------------------------------------------

die();

?>

