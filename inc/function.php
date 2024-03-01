<?php
session_start(); 

if (isset($_SESSION['accountId'])) 
{
	//get default currency of login account
	$getDefCurDet = getDefaultCurrencyDet($_SESSION['accountId']);
}

 $orderArr = [

 	['title'=>'Access Payment','name'=>'access_payment'],
// 	['title'=>'Extra Charges','name'=>'extra_charges'],
// 	['title'=>'Item Price','name'=>'item_price'],

 ];


$requisitionArr = [

	['title'=>'Access Invoice','name'=>'access_invoice'],
	['title'=>'Extra Charges','name'=>'extra_charges'],
	['title'=>'Item Price','name'=>'item_price'],
	['title'=>'Available Quantity','name'=>'available_quantity'],

];


$runningTaskArr = [

	['title'=>'Edit Order','name'=>'edit_order'],
	['title'=>'Edit Requisition','name'=>'edit_requisition'],
	['title'=>'Receive Order','name'=>'receive_order'],
	['title'=>'Issue Out','name'=>'issue_out'],
	['title'=>'Assign To Mobile','name'=>'assign_mobile'],
	['title'=>'Access Delete Function','name'=>'access_delete_runningtask']

];

$historyArr = [

	['title'=>'Access Xcl/Pdf File','name'=>'access_history_xcl_pdf'],
	['title'=>'Access Delete Function','name'=>'access_delete_history'],
	['title'=>'Access Accounts Detail','name'=>'access_accounts_detail']

];

$stockArr = [

	['title'=>'Convert Raw Items','name'=>'convert_raw_items'],
	['title'=>'View Stock Take','name'=>'view_stock_take'],
	['title'=>'Import Stock Take','name'=>'import_stock_take'],
	['title'=>'Access Xcl/Pdf File','name'=>'access_stock_xcl_pdf']

];

$newStockTakeArr = [

	['title'=>'Edit Stocktake','name'=>'edit_stocktake'],
	['title'=>'Delete Stocktake','name'=>'delete_stocktake']

];

$revCenterArr = [

	['title'=>'Enter Guest No','name'=>'guest_no'],
	['title'=>'Get Ezee Data','name'=>'ezee_data'],
	['title'=>'ReImport Data','name'=>'reImport_data']

];

$setupArr = [

	['title'=>'Account Setup','name'=>'account_setup'],
	['title'=>'Title','name'=>'designation'],
	['title'=>'Users','name'=>'user'],
	['title'=>'Outlets','name'=>'outlet'],
	['title'=>'Suppliers','name'=>'supplier'],
	['title'=>'Revenue Centers','name'=>'revenue_center'],
	['title'=>'Physical Storages','name'=>'physical_storage'],
	['title'=>'Departments Type','name'=>'department_type'],
	['title'=>'Categories','name'=>'category'],
	['title'=>'Units','name'=>'unit'],
	['title'=>'Items Manager','name'=>'item_manager'],
	['title'=>'Currency','name'=>'currency'],
	['title'=>'Accounts','name'=>'account'],
	['title'=>'Service Item','name'=>'service_item'],
	['title'=>'Additional Fees','name'=>'additional_fee']

];

$mobileSectionArr = [

	['title'=>'Storage Stocktaking','name'=>'storage_stocktaking'],
	['title'=>'Outlet Stocktaking','name'=>'outlet_stocktaking'],
	['title'=>'Receiving Order','name'=>'receiving_order'],
	['title'=>'Issuing Out','name'=>'issuing_out'],
	['title'=>'Bar Control Sales','name'=>'bar_control_sales']

];

$target_dir = dirname(__FILE__)."/uploads/";

$target_dir = str_replace('\inc', '', $target_dir);

$target_dir = str_replace('/inc', '', $target_dir);

if (isset($_POST['accountName']) && $_POST['accountName'] != '') 
{
	$accountName = str_replace(' ', '', $_POST['accountName']);
	$file_path = $target_dir;

	if (file_exists($file_path)) 
	{
    	mkdir($file_path.''.$accountName);

    	$new_file_path = $file_path.''.$accountName.'/';
    	if ( file_exists($new_file_path) ) 
    	{
    		$users = "users";
    		$products = "products";
    		$clientLogo = "clientLogo";
    		mkdir($new_file_path.''.$users);
    		mkdir($new_file_path.''.$products);
    		mkdir($new_file_path.''.$clientLogo);
    	}
    	
	}
}



function getAccountDetails($id)
{
	global $con;
	$sql = " SELECT * FROM tbl_client WHERE id = '".$id."' ";
	$sqlResult = mysqli_query($con,$sql);
	$sqlRow = mysqli_fetch_array($sqlResult);

	return $imgFolderName = $sqlRow['image_folder_name'];
}
if (isset($_SESSION['accountId']) && $_SESSION['accountId'] > 0) {
	$accountId = $_SESSION['accountId'];
}
elseif (isset($_GET['account_id']) && $_GET['account_id'] > 0)
{
	$accountId = $_GET['account_id'];
}
if (isset($_SESSION['accountId']) || isset($_GET['accountId'])) 
{
	$accDet = getAccountDetails($accountId);
	$accountImgPath = $accDet;
	
	$bulkImgUploadPath = $target_dir.$accountImgPath.'/'. 'products/';
}





function csv_to_array($filename='', $delimiter=',')
{
   	$header = NULL;
	$data = array();
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
		while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
		{
			if(!$header)
				$header = $row;
			else
				$data[] = array_combine($header, $row);
		}
		fclose($handle);
	}

	return $data;
}

$checkDecPlace = $getDefCurDet['decPlace'];
function showPrice($price, $curCode)
{
	global $checkDecPlace;
	echo $price ? number_format($price, $checkDecPlace).' '.$curCode : 0;
}

function getPriceWithCur($price, $curCode)
{
	global $checkDecPlace;
	return $price ? number_format($price, $checkDecPlace).' '.$curCode : 0;
}

function getNumFormtPrice($price, $curCode, $decimalPlace=0)
{
	global $checkDecPlace;
	
	$decimalPlace = $decimalPlace > 0 ? $decimalPlace : $checkDecPlace;
	return  $price ? number_format($price, $checkDecPlace).' '.$curCode : '';
}

function getPrice($price)
{
	global $checkDecPlace;
	return $price ? round($price, $checkDecPlace) : 0;
}

function get2DecimalVal($val)
{
	global $checkDecPlace;
	return $val ? round($val, $checkDecPlace) : '';
}

function getConfirmTotalQtyReq($account_id)
{
	global $con;
	//get confirmed requsitions total qty of each productd
	$productsConfirmedQtyArr = [];
	$sql = "SELECT od.pId, SUM(od.qty) totalProQty FROM tbl_order_details od INNER JOIN tbl_orders o ON(o.id = od.ordId) AND o.account_id = od.account_id WHERE  o.account_id = '".$account_id."'  AND o.ordType = '2' AND o.status = '3'  GROUP BY od.pId ";
	$newordQry = mysqli_query($con, $sql);
	while( $ordRowData = mysqli_fetch_array($newordQry) )
	{
		$productsConfirmedQtyArr[$ordRowData['pId']] = $ordRowData['totalProQty']; 
	}
	return $productsConfirmedQtyArr; 
	//end 	get confirmed requsitions total qty of each productd
}

function getConfirmTotalQtyReqWithoutSameOrdId($pId, $accountId, $orderId){
	global $con;

	$productsConfirmedQtyArr = [];
	$sql = "SELECT od.pId, SUM(od.qty) totalProQty FROM tbl_order_details od INNER JOIN tbl_orders o ON(o.id = od.ordId) AND o.account_id = od.account_id WHERE o.account_id = '".$accountId."'  AND o.ordType = '2' AND o.status = '3' AND  ordId != '".$orderId."' GROUP BY od.pId ";
    $newordQry = mysqli_query($con, $sql);
    while( $ordRowData = mysqli_fetch_array($newordQry) )
	{
		$productsConfirmedQtyArr[$ordRowData['pId']] = $ordRowData['totalProQty']; 
	}
	return $productsConfirmedQtyArr;
}

function checkSupplierForMinLevelProducts($supId)
{
	global $con;


	
	$sqlSet = " SELECT p.id pId, p.minLevel, p.maxLevel, p.factor, s.qty stockQty
	
	FROM tbl_products p 
	 		
	INNER JOIN tbl_productsuppliers ps ON(ps.productId = p.id) AND ps.account_id = p.account_id AND p.status=1
			
	LEFT JOIN tbl_stocks s ON(s.pId = p.id) AND s.account_id = p.account_id 
	  
	WHERE  ps.supplierId = '".$supId."'  AND ps.account_id = '".$_SESSION['accountId']."'  ";
	$proresultSet = mysqli_query($con, $sqlSet);
	
	
	$productsConfirmedQtyArr = getConfirmTotalQtyReq($_SESSION['accountId']);
	
	$showColor = 0;
	while($res = mysqli_fetch_array($proresultSet) )
	{
		
		$pId = $res['pId'];
		
		$totalProQty = isset($productsConfirmedQtyArr[$pId]) ? $productsConfirmedQtyArr[$pId] : 0;

		$stockQty = $res['stockQty'] - $totalProQty;

		if( ($res['minLevel'] == 0 && $stockQty < $res['minLevel'] ) || ( $res['minLevel'] > 0 && round( $stockQty/$res['factor']) < round($res['minLevel']/$res['factor']) ) )
		{
			$showColor = 1;
		}
	}
	
	return $showColor;
	
}

function getProdcutDepartments($pid)
{
	global $con;
	$sqlSet = " SELECT GROUP_CONCAT(d.name) departs FROM tbl_productdepartments dc 
	INNER JOIN tbl_department d ON(dc.deptId = d.id) AND dc.account_id = d.account_id  WHERE dc.productId = '".$pid."'  AND d.account_id = '".$_SESSION['accountId']."' GROUP BY dc.productId ";
	$resultSet = mysqli_query($con, $sqlSet);
	$resultRow = mysqli_fetch_array($resultSet);
	
	return $resultRow['departs'];
}

function getProdcutSuppls($pid)
{
	global $con;
	$sqlSet = " SELECT GROUP_CONCAT(s.name) suppls FROM tbl_productsuppliers ps
	INNER JOIN tbl_suppliers s ON(ps.supplierId = s.id) AND ps.account_id = s.account_id WHERE ps.productId = '".$pid."'  AND ps.account_id = '".$_SESSION['accountId']."' GROUP BY ps.productId ";
	$resultSet = mysqli_query($con, $sqlSet);
	$supRow = mysqli_fetch_array($resultSet);
	
	return $supRow['suppls'];
}

function getStoreDetailsById($id)
{
	global $con;
	$sqlSet = " SELECT * FROM tbl_stores WHERE id = '".$id."'  AND account_id = '".$_SESSION['accountId']."'  ";
	$resultSet = mysqli_query($con, $sqlSet);
	
	return $storageDeptRow = mysqli_fetch_array($resultSet);
}

function getStockTotalOfStore($storeId, $cond = '')
{
	global $con;
	$sql = "SELECT SUM(stockValue) totalstockValue  FROM tbl_stocks s

		INNER JOIN tbl_products tp ON(s.pId = tp.id) AND tp.status=1 AND s.account_id = tp.account_id

		LEFT JOIN tbl_category c ON (c.id = tp.catId) AND c.account_id = tp.account_id

		WHERE storageDeptId = '".$storeId."'  AND s.account_id = '".$_SESSION['accountId']."'  ".$cond;
	$resQry = mysqli_query($con, $sql);
	$stockValRow = mysqli_fetch_array($resQry);
	
	return $stockValRow['totalstockValue'];
}

function getItemHistory($pId, $params=array())
{
	global $con;
	
	$cond = '';
	if( isset($params['fromDate']) && $params['fromDate'] != '' && $params['toDate'] != '')
	{
		$cond = " AND DATE(o.setDateTime) BETWEEN '".date('Y-m-d', strtotime($_GET['fromDate']) )."' AND '".date('Y-m-d', strtotime($_GET['toDate']) )."' ";
	}
	else
	{
		$_GET['fromDate'] = date('d-m-Y', strtotime('-1 month') );
		$_GET['toDate'] = date('d-m-Y');
		
		$cond = " AND DATE(o.setDateTime) BETWEEN '".date('Y-m-d', strtotime($_GET['fromDate']) )."' AND '".date('Y-m-d', strtotime($_GET['toDate']) )."' ";
	}
	
	if( isset($params['ordType']) && $params['ordType'])
	{
	    $cond .= ' AND o.ordType = "'.$_GET['ordType'].'" ';
	    // $cond .= " ORDER BY o.ordType = "'.$_GET['ordType'].'" desc ";
	}

	if(isset($params['suppMemStoreId']) || $params['suppMemStoreId'] != '')
	{
	    $suppMemStoreId = $params['suppMemStoreId'];
	    $getSupMemStoreId = explode('_', $suppMemStoreId);
	    $getTxtById = $getSupMemStoreId[0];
	    $getId = $getSupMemStoreId[1];

	    if ($getTxtById == 'suppId') 
	    {
	        $cond .= " AND o.supplierId = '".$getId."' ";
	        // $cond .= " order by o.supplierId = '".$getId."' desc ";
	    }

	    if ($getTxtById == 'deptUserId') 
	    {
	        $cond .= " AND o.recMemberId = '".$getId."' ";
	        // $cond .= " order by o.recMemberId = '".$getId."' desc ";
	    }

	    if ($getTxtById == 'storeId') 
	    {
	        $cond .= " AND o.storeId = '".$getId."' ";
	        //$cond .= " order by o.storeId = '".$getId."' desc ";
	    }
	}


	$sqlSet = " SELECT p.proType, o.id AS ordId, o.deptId, o.ordType, o.recMemberId, o.supplierId, o.storeId, du.name AS deptUserName, st.name AS storeName, sp.name AS suppName, DATE(o.setDateTime) AS actDate, od.*   
	FROM tbl_orders o
	INNER JOIN tbl_order_details od ON(o.id = od.ordId) AND o.account_id = od.account_id
	INNER JOIN tbl_products p ON(p.id = od.pId) AND p.account_id = od.account_id	
	INNER JOIN tbl_stocks s ON(od.pId = s.pId) AND od.account_id = s.account_id	
	LEFT JOIN tbl_deptusers du ON(o.recMemberId = du.id) AND o.account_id = du.account_id
	LEFT JOIN tbl_stores st ON(o.storeId = st.id) AND o.account_id = s.account_id
	LEFT JOIN tbl_suppliers sp ON(o.supplierId = sp.id) AND o.account_id = sp.account_id						
	WHERE od.pId = '".$pId."' AND od.account_id = '".$_SESSION['accountId']."' AND o.status = 2 ".$cond." ORDER BY od.id desc ";
	
	$resultSet = mysqli_query($con, $sqlSet);
	
	$rows = [];
	
	$issuedInTot = 0;
	$issuedOutTot = 0;
	$variancesTot = 0;
	
	$issuedInQtyTot = 0;
	$issuedOutQtyTot = 0;
	$variancesQtyTot = 0;
	$convertedQtyTot = 0;
	while( $resRow = mysqli_fetch_array($resultSet) )
	{
		$actDate = $resRow['actDate'];
		
		
		$resRow['stockPrice'] = $resRow['stockPrice'] > 0 ? $resRow['stockPrice'] : $resRow['price'];
		$resRow['lastPrice'] = $resRow['lastPrice'] > 0 ? $resRow['lastPrice'] : $resRow['price'];
		
		$rows[] = $resRow;
		
		if($resRow['ordType'] == 1)//issue in
		{
			$issuedInTot += $resRow['totalAmt'];
			$issuedInQtyTot += $resRow['qtyReceived']*$resRow['factor'];
		}
		elseif($resRow['ordType'] == 2)//issue out
		{
			$issuedOutTot += $resRow['totalAmt'];
			$issuedOutQtyTot += $resRow['qty'];

		}
		elseif($resRow['ordType'] == 3)//stock take
		{
			$varaincesVal = $resRow['qtyReceived']-$resRow['qty'];
			
			$variancesQtyTot += $varaincesVal;
			$price = $resRow['stockPrice'] > 0 ? $resRow['stockPrice'] : $resRow['price'];
			$variancesTot += $varaincesVal*$price;
		}
		else//raw convert item
		{
			$convertedTot += $resRow['totalAmt'];
			$convertedQtyTot += $resRow['qtyReceived'];
		}
	}
	
	return ['resRows' => $rows, 
	'issuedInTot' => $issuedInTot, 'issuedOutTot' => $issuedOutTot, 'variancesTot' => $variancesTot,
	'issuedInQtyTot' => $issuedInQtyTot, 'issuedOutQtyTot' => $issuedOutQtyTot, 'variancesQtyTot' => $variancesQtyTot, 'variancesTot' => $variancesTot, 'cond' => $cond, 'getId' => $getId, 'storeName' => $storeName, 'deptUserName' => $deptUserName, 'suppName' => $suppName, 'convertedTot' => $convertedTot, 'convertedQtyTot' => $convertedQtyTot
	];
}

function getOrdItemVariancesAmt($ordId)
{
	global $con;
	
	
	$sqlSet = " SELECT * FROM  tbl_order_details 								
	WHERE ordId = '".$ordId."'  AND account_id = '".$_SESSION['accountId']."' ";
	$resultSet = mysqli_query($con, $sqlSet);
	
	$rows = [];
	
	
	$variancesTot = 0;
	$variancesQtyTot = 0;
	$varaincesVal = 0;
	while( $resRow = mysqli_fetch_array($resultSet) )
	{
			$varaincesVal = $resRow['qtyReceived']-$resRow['qty'];
			
			$variancesQtyTot += $varaincesVal;
			$variancesTot += ($varaincesVal*$resRow['lastPrice']);
	}
	
	return ['variancesQtyTot' => $variancesQtyTot, 'variancesTot' => $variancesTot];
}

function getOrdItemVariancesAmtNew($ordId)
{
	global $con;
	
	
	$sqlSet = " SELECT * FROM  tbl_order_details 								
	WHERE ordId = '".$ordId."'  AND account_id = '".$_SESSION['accountId']."' ";
	$resultSet = mysqli_query($con, $sqlSet);
	
	$variancesPosTot = 0;
    $variancesPosQtyTot = 0;
    $variancesNevQtyTot = 0;
    $variancesNevTot = 0;
    $varaincesVal = 0;
    while( $resRow = mysqli_fetch_array($resultSet) )
    {
        if ($resRow['qtyReceived'] < $resRow['qty'])
        {
            $varaincesVal = $resRow['qty']-$resRow['qtyReceived'];
            $variancesNevQtyTot += $varaincesVal;
            $variancesNevTot += ($varaincesVal*$resRow['lastPrice']);
        }
        elseif($resRow['qtyReceived'] > $resRow['qty'])
        {
            $varaincesVal = $resRow['qtyReceived']-$resRow['qty'];
            
            $variancesPosQtyTot += $varaincesVal;
            $variancesPosTot += ($varaincesVal*$resRow['lastPrice']);
        }
    }
	
	return ['variancesPosQtyTot' => $variancesPosQtyTot, 'variancesNevQtyTot' => $variancesNevQtyTot, 'variancesPosTot' => $variancesPosTot, 'variancesNevTot' => $variancesNevTot];


}


//show amount and qty
function showItemTypeData($type, $ordDetails, $showType=0)
{
		$amt = 0;
		if( ($ordDetails['ordType'] == 1) && $type == 1)
		{
		
			if($showType == 0)
			{
				return showPrice($ordDetails['totalAmt'], $getDefCurDet['curCode']);
				
			}
			else
			{
				return ($ordDetails['qtyReceived']*$ordDetails['factor']);
			}
		}
		
		if($ordDetails['ordType'] == 2 && $type == 2)
		{
			
			if($showType == 0)
			{
				return showPrice($ordDetails['totalAmt'], $getDefCurDet['curCode']);
				
			}
			else
			{
				return ($ordDetails['qty']);
			}
		}
		
		if($ordDetails['ordType'] == 3 && $type == 3)
		{			
			if($showType == 0)
			{
				$variancesQtyTot = $ordDetails['qtyReceived']-$ordDetails['qty'];
				$amt = $variancesQtyTot*$ordDetails['stockPrice'];
				return showPrice($amt, $getDefCurDet['curCode']);
			}
			else
			{
				return ($ordDetails['qtyReceived']-$ordDetails['qty']);
			}
		}

		if($ordDetails['ordType'] == 4 && $type == 4)
		{			
			if($showType == 0)
			{
				return showPrice($ordDetails['totalAmt'], $getDefCurDet['curCode']);
			}
			else
			{
				return ($ordDetails['qtyReceived']);
			}
		}
		
}


function getOrderDepartments($ordId)
{
	global $con;
	$sqlSet = " SELECT GROUP_CONCAT(DISTINCT(d.name)) departments FROM tbl_orders o INNER JOIN tbl_department d
	ON(d.id = o.deptId) AND d.account_id = o.account_id
	WHERE o.id = '".$ordId."' AND o.account_id = '".$_SESSION['accountId']."' ";
	$resultSet = mysqli_query($con, $sqlSet);
	$departRow = mysqli_fetch_array($resultSet);
	
	return $departRow['departments'];

}


function getOrderSuppls($ordId)
{
	global $con;
	$sqlSet = " SELECT GROUP_CONCAT(DISTINCT(s.name)) suppliers FROM tbl_orders o INNER JOIN tbl_suppliers s
	ON(o.supplierId = s.id) AND o.account_id = s.account_id
	WHERE o.id = '".$ordId."' AND o.account_id = '".$_SESSION['accountId']."' ";
	$resultSet = mysqli_query($con, $sqlSet);
	$supRow = mysqli_fetch_array($resultSet);
	
	return  $supRow['suppliers'];
}

function getProductStockTotalOfStore($pId, $storageDeptId)
{
	
	global $con;
	$sql = "SELECT SUM(stockValue) totalstockValue  FROM tbl_stocks s 
				INNER JOIN tbl_products tp ON(s.pId = tp.id) AND tp.status=1 AND s.account_id = tp.account_id
				LEFT JOIN tbl_category pc ON(pc.id = tp.parentCatId) AND pc.account_id = tp.account_id
				INNER JOIN tbl_category c ON(c.id = tp.catId) AND c.account_id = tp.account_id
				WHERE s.storageDeptId = '".$storageDeptId."' AND s.pId = '".$pId."'  AND s.account_id = '".$_SESSION['accountId']."'  ".$cond;
	$resQry = mysqli_query($con, $sql);
	$stockValRow = mysqli_fetch_array($resQry);
	
	return $stockValRow['totalstockValue'];
}

function getRecusitionTempData($userId)
{
	global $con;
	$sql=" SELECT * FROM  tbl_recusition_items_temp WHERE  `userId` = '".$userId."'  AND account_id = '".$_SESSION['accountId']."'   ";
	$result = mysqli_query($con, $sql);
	
	$proDetails = [];
	$deptId = 0;
	while($recRowsTemp = mysqli_fetch_array($result) )
	{
		$deptId = $recRowsTemp['deptId'];
		$proDetails[$recRowsTemp['deptId']][$recRowsTemp['pId']] = $recRowsTemp['qty'];
		$notesDetail[$recRowsTemp['deptId']][$recRowsTemp['pId']] = $recRowsTemp['notes'];
	}
	
	return ['deptId' => $deptId, 'proDetails' => $proDetails, 'notesDetail' => $notesDetail];
}

function getOrderTempData($userId)
{
	global $con;
	$sql=" SELECT * FROM  tbl_order_items_temp WHERE  `userId` = '".$userId."'  AND account_id = '".$_SESSION['accountId']."'   ";
	$result = mysqli_query($con, $sql);
	
	$proDetails = [];
	$supplierId = 0;
	while($recRowsTemp = mysqli_fetch_array($result) )
	{
		$supplierId = $recRowsTemp['supplierId'];
		$proDetails[$recRowsTemp['supplierId']][$recRowsTemp['pId']] = $recRowsTemp['qty'];
		$notesDetail[$recRowsTemp['supplierId']][$recRowsTemp['pId']] = $recRowsTemp['notes'];
	}
	
	return ['supplierId' => $supplierId, 'proDetails' => $proDetails, 'notesDetail' => $notesDetail];
}

function showTempProQty($proRows, $deptOrSupId, $pId)
{
	if( isset($proRows[$deptOrSupId][$pId]) )
	{
		return $proRows[$deptOrSupId][$pId];
	}
	
	return 0;
}

function deleteOrderTempData($userId)
{
	global $con;
	$sql = " DELETE FROM `tbl_order_items_temp`  WHERE `userId` = '".$userId."'  AND account_id = '".$_SESSION['accountId']."'  ";
	mysqli_query($con, $sql);

}

function deleteRecusitionTempData($userId)
{
	global $con;
	$sql = " DELETE FROM `tbl_recusition_items_temp`  WHERE `userId` = '".$userId."'  AND account_id = '".$_SESSION['accountId']."'  ";
	mysqli_query($con, $sql);
}

function getAllProducts($params = [])
{
	global $con;
	
	$cond = '';
	if( isset($params['proType']) )
	{
		$cond = " AND proType = '".$params['proType']."' ";
	}
	$sql=" SELECT * FROM  tbl_products WHERE  `status` = 1  AND account_id = '".$_SESSION['accountId']."'   ".$cond;
	$result = mysqli_query($con, $sql);
	
	$proDetails = [];
	while($row = mysqli_fetch_array($result) )
	{
		$proDetails[$row['id']]  = $row['itemName'];
	}
	
	return $proDetails;
}



function getProductSupplr($pid)
{
	global $con;
	
	$sqlSet = " SELECT supplierId FROM tbl_order_details WHERE supplierId !='' AND pId = '".$pid."'  AND account_id = '".$_SESSION['accountId']."'  ORDER BY id DESC LIMIT 1 ";
	$ordQry = mysqli_query($con, $sqlSet);
	$ordResult = mysqli_fetch_array($ordQry);
	
	if($ordResult['supplierId'] != '')
	{
		if( strpos($ordResult['supplierId'], ',') )
		{
			$suplIdArr = explode(',', $ordResult['supplierId']);
			return $suplIdArr[0];
		}
		
		return $ordResult['supplierId'];
	}
}

function getRawProducts($pid)
{
	global $con;
	
	$sql = " SELECT *  FROM `tbl_rawitem_products` rw INNER JOIN tbl_products p ON(p.id = rw.convtPid) AND p.account_id = rw.account_id WHERE  rw.rawPid = '".$pid."'  AND rw.account_id = '".$_SESSION['accountId']."'  ORDER BY rw.id DESC ";
	$qry = mysqli_query($con, $sql);
	
	$proRows = [];
	while( $row = mysqli_fetch_array($qry) )
	{
		$proRows[] = $row['itemName'].'('.$row['convtPid'].')';;
	}
	
	return $proRows;
}

function getRevenueOutLets($revCenterId)
{
	global $con;
	$sql = "SELECT * FROM tbl_revenue_center_departments WHERE revCenterId = '".$revCenterId."'  AND account_id = '".$_SESSION['accountId']."' ";
	$result = mysqli_query($con, $sql);
	
	$outLetIds = [];
	while($revOutLets = mysqli_fetch_array($result))
	{
		$outLetIds[] = $revOutLets['deptId'];
	}
	
	return $outLetIds;
}


function getImportedData($outLetId, $barCode = '', $fromDate='', $toDate='')
{
	global $con;
	
	$barCodeCond = '';
	$groupBy = " GROUP BY  itemType ";
	
	if($barCode)
	{
		$barCodeCond = " AND barCode = '".$barCode."' ";
		$groupBy = " GROUP BY  outLetId ";
	}
	if($fromDate != '' && $toDate != '')
	{
		 $sql = "SELECT  
				itemType,
				SUM(usageCnt) usageCnt,
				SUM(usageAvg) usageAvg,
				SUM(usagePerGuest) usagePerGuest,
				SUM(guests) guests,
				AVG(lastPrice) lastPrice,
				
				SUM(easySales) easySales,
				SUM(barControl) barControl,
				SUM(issueIn) issueIn,
				SUM(adjment) adjment,
				SUM(openStock) openStock,
				SUM(closeStock) closeStock
		 FROM tbl_daily_import_items WHERE 
				
		outLetId = '".$outLetId."' 
		".$barCodeCond."
		 AND account_id = '".$_SESSION['accountId']."' 
		AND importedOn
		 BETWEEN '".date( 'Y-m-d', strtotime($fromDate) )."' 
		 AND '".date( 'Y-m-d', strtotime($toDate) )."' ".$groupBy;
		$result = mysqli_query($con, $sql);
		
		$dataRows = [];
		while( $res = mysqli_fetch_array($result) )
		{
			if($barCode)
			{
				$dataRows = $res;
			}
			else
			{
				$dataRows[$res['itemType']] = $res;
			}
		}
		
		return $dataRows;
	}
}


function getImportedDataNew($outLetId, $barCode, $fromDate, $toDate)
{
	global $con;
	
	
		 $sql = "SELECT  
					itemType,
					usageCnt,
					usageAvg,
					usagePerGuest,
					guests,
					lastPrice,
					
					easySales,
					barControl,
					issueIn,
					adjment,
					openStock,
					closeStock
		 FROM tbl_daily_import_items WHERE 
				
		outLetId = '".$outLetId."' 
		AND barCode = '".$barCode."'
		 AND account_id = '".$_SESSION['accountId']."' 
		AND importedOn
		 BETWEEN '".date( 'Y-m-d', strtotime($fromDate) )."' 
		 AND '".date( 'Y-m-d', strtotime($toDate) )."' ";
		$result = mysqli_query($con, $sql);
		
		$dataRows = [];
		while( $res = mysqli_fetch_array($result) )
		{
			$dataRows[] = $res;
		}
		
	return $dataRows;
}

function getQtyByImportType($type, $data)
{
	return isset($data[$type]) ? $data[$type] : 0;
}

function getItemType($type)
{
	if($type == 1)
	{
		$itemType =  'Bar Control';
	}
	if($type == 2)
	{
		$itemType = 'Sales';
	}
	if($type == 3)
	{
		$itemType = 'Usage';
	}	
	
	return $itemType;
}

function getProDetailsByBarCode($barCode)
{
	global $con;
	
	
	$sql=" SELECT * FROM  tbl_products WHERE  `status` = 1  AND account_id = '".$_SESSION['accountId']."'   AND barCode = '".$barCode."' ";
	$result = mysqli_query($con, $sql);
	
	$proDetails = mysqli_fetch_array($result);
	
	return $proDetails;
}

function getStockProductByBarCode($barCode)
{
	global $con;
	
	
	$sql=" SELECT s.stockPrice, p.price lastPrice FROM  tbl_products p inner join tbl_stocks s on(p.id = s.pId) and p.account_id = s.account_id
	
	 WHERE  p.`status` = 1  AND p.account_id = '".$_SESSION['accountId']."'   AND p.barCode = '".$barCode."' ";
	$result = mysqli_query($con, $sql);
	
	$proDetails = mysqli_fetch_array($result);
	
	return $proDetails;
}


function getOutLateSalesCost($outLetId, $fromDate='', $toDate='')
{
	global $con;
	if($fromDate != '' && $toDate != '')
	{
		$sql = "select  (qty*lastPrice) totalPrice  FROM tbl_daily_import_items WHERE outLetId = '".$outLetId."'  AND account_id = '".$_SESSION['accountId']."'   AND importedOn BETWEEN '".date( 'Y-m-d', strtotime($fromDate) )."' AND '".date( 'Y-m-d', strtotime($toDate) )."'  AND imptType = 2  ";
		$result = mysqli_query($con, $sql);
		
		$total = 0;
		while($res = mysqli_fetch_array($result))
		{
			$total += $res['totalPrice'];
		}
		
		return $total;
	}
}

function getOpenAndCloseStock($outLetId, $barCode, $fromDate='', $toDate='')
{
	global $con;
	if($fromDate != '' && $toDate != '')
	{
		$sql = "select  qty  FROM tbl_daily_import_items WHERE 
					outLetId = '".$outLetId."' 
					 AND account_id = '".$_SESSION['accountId']."' 
					AND barCode = '".$barCode."' 
					AND importedOn = '".date('Y-m-d', strtotime('-1 day', strtotime($fromDate)))."'
					AND imptType = 1    ";
		$result = mysqli_query($con, $sql);
		
		$resOpenStock = mysqli_fetch_array($result);
		
		$resp['open'] = $resOpenStock['qty'] > 0 ? $resOpenStock['qty'] : getIssuedInQty($barCode);
		
		$sql = "select  qty  FROM tbl_daily_import_items WHERE 
					outLetId = '".$outLetId."' 
					 AND account_id = '".$_SESSION['accountId']."' 
					AND barCode = '".$barCode."' 
					AND importedOn = '".date('Y-m-d')."'
					AND imptType = 1    ";
		$result = mysqli_query($con, $sql);
		
		$resCloseStock = mysqli_fetch_array($result);
		$resp['close'] = $resCloseStock['qty'];
		
		return $resp;
	}
}

function getItemDetails($outLetId, $barCode, $date)
{
	global $con;

	$sql = "select * FROM tbl_daily_import_items WHERE 
				outLetId = '".$outLetId."' 
				 AND account_id = '".$_SESSION['accountId']."' 
				AND barCode = '".$barCode."' 
				AND importedOn = '".date('Y-m-d', strtotime($date))."'
				    ";
	$result = mysqli_query($con, $sql);
	
	$itemDet = mysqli_fetch_array($result);
	
	
	return $itemDet;
}

function getLastDayItemDetails($outLetId, $barCode, $date)
{
	global $con;

	$sql = "SELECT * FROM tbl_daily_import_items 
	WHERE outLetId = '".$outLetId."' AND account_id = '".$_SESSION['accountId']."' AND barCode = '".$barCode."' AND importedOn = '".$date."'  ";
				
				
	$result = mysqli_query($con, $sql);
	
	$itemDet = mysqli_fetch_array($result);
	
	
	return $itemDet;
}


function getUsageItemTotal($outLetId, $barCode, $imptType, $fromDate, $toDate)
{
	global $con;

	$sql = "select  SUM(usageCnt) usageCnt, SUM(usagePerGuest) usagePerGuest, SUM(usageAvg) usageAvg  FROM tbl_daily_import_items WHERE 
				outLetId = '".$outLetId."' 
				 AND account_id = '".$_SESSION['accountId']."' 
				AND barCode = '".$barCode."' 
				AND importedOn BETWEEN '".date('Y-m-d', strtotime($fromDate))."' AND '".date('Y-m-d', strtotime($toDate))."'
				AND imptType = '".$imptType."'    ";
	$result = mysqli_query($con, $sql);
	
	$itemDet = mysqli_fetch_array($result);
	
	return $itemDet;
}


function getIssuedInQty($barCode)
{
	global $con;
	
	$sql = "SELECT s.qty   FROM tbl_stocks s 
				INNER JOIN tbl_products p ON(p.id = s.pId) AND p.account_id = s.account_id 
				 WHERE p.barCode = '".$barCode."'  AND p.account_id = '".$_SESSION['accountId']."'  ";
	$qry = mysqli_query($con, $sql);
	$row= mysqli_fetch_array($qry);
	
	return $row['qty'];
}

/*function getIssuedInQty($barCode, $date)
{
	global $con;
	
	$sql = "SELECT od.qtyReceived   FROM tbl_orders o 
				INNER JOIN tbl_order_details od ON(od.ordId = o.id)
				INNER JOIN tbl_products p ON(p.id = od.pId) 
				 WHERE p.barCode = '".$barCode."' AND date(setDateTime) = '".$date."'  ";
	$qry = mysqli_query($con, $sql);
	$row= mysqli_fetch_array($qry);
	
	return $row['qtyReceived'];
}*/

function getAvgUsage($outLetId, $barCode, $todayUsage, $date)
{
	global $con;
	
	$sql = "select count(*) usageDays  FROM tbl_daily_import_items WHERE 
			outLetId = '".$outLetId."' 
			 AND account_id = '".$_SESSION['accountId']."' 
			AND barCode = '".$barCode."' 
			AND importedOn != '".$date."' 
			    ";
	$result = mysqli_query($con, $sql);
	$imprtRes = mysqli_fetch_array($result);
	
	$usageCnt = $todayUsage;
	
	$days = 1;
	
	if($imprtRes)
	{		
		$days += $imprtRes['usageDays'];
		
		$sql = "select usageCnt FROM tbl_daily_import_items WHERE 
			outLetId = '".$outLetId."' 
			 AND account_id = '".$_SESSION['accountId']."' 
			AND barCode = '".$barCode."' 
			AND importedOn != '".$date."' order by id desc limit 1
			    ";
		$result = mysqli_query($con, $sql);
		$imprtRes = mysqli_fetch_array($result);
		
		$usageCnt += $imprtRes['usageCnt'];		
	}
	
		$avgUsage = $usageCnt/$days;
		
	return ['usageAvg' => $avgUsage, 'usageCnt' => $usageCnt];
}


function getAvgUsageOldData($accountId, $outLetId, $barCode, $todayUsage, $date)
{
	global $con;
	
	$sql = "select count(*) usageDays  FROM tbl_daily_import_items WHERE 
			outLetId = '".$outLetId."' 
			 AND account_id = '".$accountId."' 
			AND barCode = '".$barCode."' 
			AND importedOn < '".$date."' 
			    ";
	$result = mysqli_query($con, $sql);
	$imprtRes = mysqli_fetch_array($result);
	
	$usageCnt = $todayUsage;
	
	//echo 'todayUsage='.$todayUsage;
	//echo "<br>";
	
	$days = 1;
	
	if($imprtRes)
	{		
		/*echo 'usageDays='.$imprtRes['usageDays'];
		echo "<br>";*/
	
		$days += $imprtRes['usageDays'];
		
		$sql = "select usageCnt FROM tbl_daily_import_items WHERE 
			outLetId = '".$outLetId."' 
			 AND account_id = '".$accountId."' 
			AND barCode = '".$barCode."' 
			AND importedOn < '".$date."' order by importedOn desc limit 1
			    ";
		$result = mysqli_query($con, $sql);
		$imprtRes = mysqli_fetch_array($result);
		//echo  '='.$imprtRes['usageCnt']."=<br>";
		
		$usageCnt += $imprtRes['usageCnt'];		
	}
	
	/*echo 'usageCnt='.$usageCnt.'='.$days;
		echo "<br>";*/
		
		$avgUsage = $usageCnt/$days;
		
	return ['usageAvg' => $avgUsage, 'usageCnt' => $usageCnt];
}

function getAdjustMent($outLetId, $barCode, $fromDate, $toDate)
{
	global $con;
	
	$sql = "select  SUM(qty) totalAdjust FROM tbl_daily_import_items WHERE 
			outLetId = '".$outLetId."'
			 AND account_id = '".$_SESSION['accountId']."'  
			AND barCode = '".$barCode."' 
			AND importedOn BETWEEN '".date('Y-m-d', strtotime($fromDate))."' AND '".date('Y-m-d', strtotime($toDate))."'
			AND imptType = 5 GROUP BY imptType ";
			
	$result = mysqli_query($con, $sql);
	$imprtRes = mysqli_fetch_array($result);
	
	return $imprtRes['totalAdjust'];
}

function getOutLetProducts($outLetId)
{
	global $con;
	$sql = "SELECT  p.id, p.itemName FROM tbl_outlet_items o
 					INNER JOIN tbl_products p ON(p.id = o.pId) AND p.account_id = o.account_id 
		 WHERE o.outLetId = '".$outLetId."'  AND o.account_id = '".$_SESSION['accountId']."'  ";
	$outLetItemsQry = mysqli_query($con, $sql);
	
	$proDetails = [];
	while($row = mysqli_fetch_array($outLetItemsQry) )
	{
		$proDetails[$row['id']]  = $row['itemName'];
	}
	
	return $proDetails;
}

function getIssueIn($outLetId, $pId, $date)
{
	global $con;
	
	$date = date('Y-m-d', strtotime($date) );

	$sqlSet = " SELECT  SUM(od.qty) totReceived   FROM tbl_orders o
								INNER JOIN tbl_order_details od ON(o.id = od.ordId)	AND o.account_id = od.account_id							
					 WHERE od.pId = '".$pId."'  AND o.account_id = '".$_SESSION['accountId']."'  AND DATE(setDateTime) = '".$date."' AND recMemberId = '".$outLetId."' AND status = 2  AND ordType = 2 ";
	$resultSet = mysqli_query($con, $sqlSet);
	
	$row = mysqli_fetch_array($resultSet);
	
	return $row['totReceived'];
}


function showColorBoxes($outLetId, $fromDate, $toDate)
{

	global $con;
	
	$sql = "select  SUM(closeStock) closeStock, SUM(easySales) easySales, SUM(issueIn) issueIn  FROM tbl_daily_import_items WHERE 
			outLetId = '".$outLetId."' 
			 AND account_id = '".$_SESSION['accountId']."' 
			AND (importedOn  BETWEEN '".date('Y-m-d', strtotime($fromDate))."' AND  '".date('Y-m-d', strtotime($toDate))."')  GROUP BY outLetId
			 ";
			
	$result = mysqli_query($con, $sql);
	$imprtRes = mysqli_fetch_array($result);
	
	$boxes = '';
	if($imprtRes['closeStock'])
	{
	   $boxes =  ' <span class="boxnew green" title="'.showOtherLangText('Close Stock').'"></span> ';
	}
	else
	{
		$boxes .= ' <span class="boxnew grey" title="'.showOtherLangText('Close Stock').'"></span> ';
	}
	
	if($imprtRes['easySales'])
	{
		$boxes .=  ' <span class="boxnew green" title="'.showOtherLangText('Ezee Sales').'"></span> ';
	}
	else
	{
		$boxes .= ' <span class="boxnew grey" title="'.showOtherLangText('Ezee Sales').'"></span> ';
	}
	
	if($imprtRes['issueIn'])
	{
		$boxes .=  ' <span class="boxnew green" title="'.showOtherLangText('Issue In').'"></span> ';
	}
	else
	{
		$boxes .= ' <span class="boxnew grey" title="'.showOtherLangText('Issue In').'"></span> ';
	}
	
	
	return $boxes;
	
}

function  getOutLetFormatedData($outLetId, $rows)
{
		global $con;
			
		$sql = "SELECT p.barCode, o.itemType, o.factor FROM tbl_outlet_items o 
		INNER JOIN tbl_products p ON(p.id = o.pId) AND p.account_id = o.account_id  
		WHERE o.outLetId = '".$outLetId."'  AND p.account_id = '".$_SESSION['accountId']."'   "; 
		$outLetItemsQry = mysqli_query($con, $sql);
		
		$formatedRows = [];
		$excelBarCodes = [];
		while( $outLetRes = mysqli_fetch_array($outLetItemsQry) )
		{
			$barCode = $outLetRes['barCode'];

			foreach($rows as $row)
			{
				$excelBarCodes[$row['StockTakeBarCode']] = $row['StockTakeBarCode'];
				//$excelBarCodes[$row['SalesBarCode']] = $row['SalesBarCode'];
				$excelBarCodes[$row['BCBarCode']] = $row['BCBarCode'];
				
				if($row['StockTakeBarCode'] == $barCode)
				{
					$formatedRows[$barCode]['StockTakeQty'] = $row['StockTakeQty'];
					$formatedRows[$barCode]['itemType'] = $outLetRes['itemType'];
					$formatedRows[$barCode]['factor'] = $outLetRes['factor'];//this is here because it may be only one barcode is available from these 3
					
				}
				if($row['SalesBarCode'] == $barCode)//comment this once its tested on development before making it live
				{
					$formatedRows[$barCode]['SalesQty'] = $row['SalesQty'];
					$formatedRows[$barCode]['itemType'] = $outLetRes['itemType'];
					$formatedRows[$barCode]['factor'] = $outLetRes['factor'];
				}
				if($row['BCBarCode'] == $barCode)
				{
					$formatedRows[$barCode]['BCQty'] = $row['BCQty'];
					$formatedRows[$barCode]['itemType'] = $outLetRes['itemType'];
					$formatedRows[$barCode]['factor'] = $outLetRes['factor'];
				}
			}
		}
		
		$barCodesNotFound = [];
		foreach($excelBarCodes as $barCode)
		{
			if( !isset($formatedRows[$barCode]) )
			{
				$_SESSION['barCodesNotFound'][] = $barCode;
			}
		}
		
		return ['formatedRows' => $formatedRows];
}

//insert data as issueIn in | OutletReport
function addIssueOutInReport($productsRow, $outLetId)
{
	global $con;
	
	
	$pIds = array_keys($productsRow);
	
  $sql = "SELECT p.barCode, o.itemType, o.factor, o.outLetId, p.id pId, s.stockPrice, p.price lastPrice FROM tbl_outlet_items o 
				INNER JOIN tbl_products p ON(p.id = o.pId) AND p.account_id = o.account_id
				
				INNER JOIN tbl_stocks s ON(p.id = s.pId) AND p.account_id = s.account_id
				  
				INNER JOIN tbl_revenue_center_departments rcd ON(rcd.id = o.outLetId) AND rcd.account_id = o.account_id
			WHERE rcd.deptId = '".$outLetId."'  AND p.account_id = '".$_SESSION['accountId']."'  
			  AND p.id IN(".implode(',', $pIds).") ";
			  				
	$outLetItemsQry = mysqli_query($con, $sql);
		
	$formatedRows = [];
	$date = date('Y-m-d');
	while( $outLetRes = mysqli_fetch_array($outLetItemsQry) )
	{ 
		
		
		$pId = $outLetRes['pId'];
		$issueIn = $outLetRes['factor']*$productsRow[$pId]['qty'];
		$barCode = $outLetRes['barCode'];
		
		if( $issueIn < 1)
		{
			continue;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////
		
		$itemType = $outLetRes['itemType'] == 3 ? 2 : 1;//for non usage type 1 and usage type 2

			
		$sql = "SELECT * FROM tbl_daily_import_items  WHERE outLetId = '".$outLetRes['outLetId']."'  AND account_id = '".$_SESSION['accountId']."'  AND importedOn = '".$date."' AND barCode = '".$barCode."'   ";
		$qry = mysqli_query($con, $sql);
		$checkRow = mysqli_fetch_array($qry);
		
		$sql = "SELECT * FROM tbl_daily_import  WHERE outLetId = '".$outLetRes['outLetId']."'  AND account_id = '".$_SESSION['accountId']."'  AND importDate = '".$date."' ";
		$qry = mysqli_query($con, $sql);
		$mainImprtRow = mysqli_fetch_array($qry);
		$guests = $mainImprtRow['guests'];
		
		
		$stockPrice = getPrice($outLetRes['stockPrice']/$outLetRes['factor']);//getPrice($outLetRes['price']/$outLetRes['factor']);
		$lastPrice = $stockPrice;
		
		$usage = '';
		$usageAvg = '';
		$usagePerGuest = '';
		$usageCnt = '';
		if($checkRow)
		{
				$issueIn += $checkRow['issueIn'];
				
				$usage = ($checkRow['openStock']+$issueIn)-($checkRow['closeStock'])+$checkRow['barControl'];
				
				if($usage > 0)
				{
					$usagePerGuest = ($itemType == 2 && $guests > 0) ? $usage/$guests : '';
					$usageAvgArr = getAvgUsage($outLetRes['outLetId'], $barCode, $usage, $date);
					
					$usageAvg = $usageAvgArr['usageAvg'];
					$usageCnt = $usageAvgArr['usageCnt'];
				}
				
			
			//End
			//usageCnt is sum of total usage including today
			$qry = " UPDATE tbl_daily_import_items SET  
						lastPrice = '".$stockPrice."',
						stockPrice = '".$stockPrice."',
						issueIn = '".$issueIn."',
						usagePerDay = '".$usage."',
						usageCnt = '".$usageCnt."',
						usageAvg = '".$usageAvg."',
						usagePerGuest = '".$usagePerGuest."'
				  WHERE id = '".$checkRow['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";
			mysqli_query($con, $qry);
		}
		else
		{
			$qry = "INSERT INTO tbl_daily_import_items SET 
			outLetId = '".$outLetRes['outLetId']."',
			importedOn = '".$date."',
			barCode = '".$barCode."',
			issueIn = '".$issueIn."',
			usagePerDay = '".$usage."',
			usageCnt = '".$usageCnt."',
			usageAvg = '".$usageAvg."',
			usagePerGuest = '".$usagePerGuest."',
			itemType = '".$itemType."',
			stockPrice = '".$stockPrice."',
			lastPrice = '".$stockPrice."',
			account_id= '".$_SESSION['accountId']."'
			 ";
			mysqli_query($con, $qry);
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////
	}
	
	
}

function correctIssueOutInReport($productsRow, $outLetId, $setDateTimeDate)
{
	global $con;
	
	$pIds = array_keys($productsRow);
	
    $sql = "SELECT p.barCode, o.itemType, o.factor, o.outLetId, p.id pId, p.price FROM tbl_outlet_items o 
				INNER JOIN tbl_products p ON(p.id = o.pId) AND p.account_id = o.account_id  
				INNER JOIN tbl_revenue_center_departments rcd ON(rcd.id = o.outLetId) AND rcd.account_id = o.account_id
			WHERE rcd.deptId = '".$outLetId."'  AND p.account_id = '".$_SESSION['accountId']."'  AND o.itemType=3 AND p.id IN(".implode(',', $pIds).")  "; 
	$outLetItemsQry = mysqli_query($con, $sql);
		
	$formatedRows = [];
	$date = $setDateTimeDate;
	while( $outLetRes = mysqli_fetch_array($outLetItemsQry) )
	{
		$pId = $outLetRes['pId'];
		$issueIn = $outLetRes['factor']*$productsRow[$pId]['qty'];
		$barCode = $outLetRes['barCode'];
		
		///////////////////////////////////////////////////////////////////////////////////////////////
		
		$itemType = $outLetRes['itemType'] == 3 ? 2 : 1;//for non usage type 1 and usage type 2

		$qry = "INSERT INTO tbl_daily_import_items SET 
		outLetId = '".$outLetRes['outLetId']."',
		importedOn = '".$date."',
		barCode = '".$barCode."',
		issueIn = '".$issueIn."',
		itemType = '".$itemType."',
		account_id= '".$_SESSION['accountId']."';
		 ";
		 
		mysqli_query($con, $qry);
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
	}
	
}

function showValue($value)
{
	return $value ? $value : '';
}

function insertUpdateEasyDailyData($masterData)
{
	global $con;
	
		$outLetId = $masterData['outLetId'];
		$sales = $masterData['sales'];
		$date = date( 'Y-m-d', strtotime($masterData['date']) );
		
		$sql = "SELECT * FROM tbl_daily_import  WHERE outLetId = '".$outLetId."'  AND account_id = '".$_SESSION['accountId']."'  AND importDate = '".$date."' ";
		$qry = mysqli_query($con, $sql);
		$checkRow = mysqli_fetch_array($qry);

		if($checkRow)
		{
			
			$qry = " UPDATE tbl_daily_import SET
							 
							sales = '".$sales."'
					WHERE id = '".$checkRow['id']."'  AND account_id = '".$_SESSION['accountId']."'   ";
			mysqli_query($con, $qry);
			
			$parentId = $checkRow['id'];
		}
		else
		{
			$qry = " INSERT INTO tbl_daily_import SET
		    outLetId = '".$outLetId."', importDate = '".$date."', sales = '".$sales."', account_id='".$_SESSION['accountId']."' ";
			mysqli_query($con, $qry);
			$parentId = mysqli_insert_id($con);
		}
			
	return 	$parentId;
}


function checkEasyBarCodesEasy($outLetIdArr, $itemRow)
{
	global $con;
		
	$barCode = 	$itemRow['ItemCode'];	
	
	$sql = "SELECT p.barCode, p.price, o.itemType, o.factor, p.account_id FROM tbl_outlet_items o 
		INNER JOIN tbl_products p ON(p.id = o.pId) AND p.account_id = o.account_id  
	WHERE o.outLetId IN(".implode(',',$outLetIdArr).") AND p.barCode = '".$barCode."' AND p.account_id = '".$_SESSION['accountId']."'  limit 1  "; 
	$outLetItemsQry = mysqli_query($con, $sql);
	$proDetails = mysqli_fetch_array($outLetItemsQry);

	if( empty($proDetails) )
	{
		$_SESSION['barCodesNotFound'][$barCode] = $barCode;
	}
}


//outletReport
function insertUpdateDailyDataItemsEasy($outLetId, $parentId, $date, $itemRow)
{
	global $con;
		
	
		$barCode = 	$itemRow['ItemCode'];	
		
		$sql = "SELECT p.barCode, s.stockPrice, o.itemType, o.factor FROM tbl_outlet_items o 
			INNER JOIN tbl_products p ON(p.id = o.pId)  AND p.account_id = o.account_id
			INNER JOIN tbl_stocks s ON(p.id = s.pId)  AND p.account_id = s.account_id
		WHERE o.outLetId = '".$outLetId."'  AND p.account_id = '".$_SESSION['accountId']."' AND p.barCode = '".$barCode."' limit 1  "; 
		$outLetItemsQry = mysqli_query($con, $sql);
		$proDetails = mysqli_fetch_array($outLetItemsQry);

		if( empty($proDetails) )
		{
			//$_SESSION['barCodesNotFound'][] = $barCode;
			return true;
		}
		$easySales	 = $itemRow['BaseQuantity'];
		
		$stockPrice = getPrice($proDetails['stockPrice']/$proDetails['factor']);//need to discuss here
		
		$sql = "SELECT * FROM tbl_daily_import_items  WHERE outLetId = '".$outLetId."' AND importedOn = '".$date."' AND barCode = '".$barCode."'   AND account_id = '".$_SESSION['accountId']."'  ";
		$qry = mysqli_query($con, $sql);
		$checkRow = mysqli_fetch_array($qry);

		if($checkRow)
		{
			$qry = " UPDATE tbl_daily_import_items SET  
						
						easySales = '".$easySales."',
						lastPrice = '".$stockPrice."',
						stockPrice = '".$stockPrice."'
						
				  WHERE id = '".$checkRow['id']."' AND account_id = '".$_SESSION['accountId']."'  ";
			mysqli_query($con, $qry);
		}
		else
		{
		
			$qry = "INSERT INTO tbl_daily_import_items SET 
			outLetId = '".$outLetId."',
			parentId = '".$parentId."', 
			importedOn = '".$date."',
			barCode = '".$barCode."',
			easySales = '".$easySales."',
			lastPrice = '".$stockPrice."',
			stockPrice = '".$stockPrice."',
			account_id = '".$_SESSION['accountId']."', 
			itemType = '1'
			 ";
			mysqli_query($con, $qry);
		}
	
	
}


//Once we are importing excel data //outletReport
function insertUpdateOutLetReport($outLetId, $formatedRows, $outLetDet)
{
	global $con;
	//--------insert master data -----------------------------
		$date = date( 'Y-m-d', strtotime($outLetDet['Date']) );
		
		$sql = "SELECT * FROM tbl_daily_import  WHERE outLetId = '".$outLetId."' AND importDate = '".$date."'  AND account_id = '".$_SESSION['accountId']."'  ";
		$qry = mysqli_query($con, $sql);
		$checkRow = mysqli_fetch_array($qry);

		if($checkRow)
		{
			/*$qry = " UPDATE tbl_daily_import SET
							 
							sales = '".$outLetDet['Sales']."', guests = '".$outLetDet['Guests']."'
					WHERE id = '".$checkRow['id']."' ";*/
					
			$qry = " UPDATE tbl_daily_import SET
			sales = '".$outLetDet['Sales']."',				 
			guests = '".$outLetDet['Guests']."'
			WHERE id = '".$checkRow['id']."'  AND account_id = '".$_SESSION['accountId']."' ";
			mysqli_query($con, $qry);
			
			$parentId = $checkRow['id'];
		}
		else
		{
			/*$qry = " INSERT INTO tbl_daily_import SET
		 outLetId = '".$outLetId."', importDate = '".date( 'Y-m-d', strtotime($outLetDet['Date']) )."', sales = '".$outLetDet['Sales']."', guests = '".$outLetDet['Guests']."' ";
		 */
		$qry = " INSERT INTO tbl_daily_import SET
		sales = '".$outLetDet['Sales']."',
		 outLetId = '".$outLetId."', account_id = '".$_SESSION['accountId']."',  importDate = '".date( 'Y-m-d', strtotime($outLetDet['Date']) )."', guests = '".$outLetDet['Guests']."' ";
			mysqli_query($con, $qry);
			$parentId = mysqli_insert_id($con);
		}
		
		$guests = $outLetDet['Guests'];
		
	//--------end --------------------------------------------
		
	foreach($formatedRows as $barCode=>$row)
	{
					
			$factor = $row['itemType'] == 1 ? $row['factor'] : 1;//if item type bar control then pick actual factory else just 1 to have no impact

			$closeStock	 = $row['StockTakeQty']*$factor;
			$easySales	 = $row['SalesQty'];
			$barControl	 = $row['BCQty'];
			
			$proDetails = getStockProductByBarCode($barCode);
			
			$proDetails['stockPrice'] = $proDetails['stockPrice']/$row['factor'];
			$stockPrice = getPrice($proDetails['stockPrice']);
			
			//$issueIn   = $row['factor']*getIssueIn($outLetId, $proDetails['id'], $date);//here regardless of item type *factory will be
			
			$lastDate = date('Y-m-d', strtotime('-1 day', strtotime($date)) );
			$itemDetails = getLastDayItemDetails($outLetId, $barCode, $lastDate );
			$openStock = $itemDetails['closeStock'] > 0 ? $itemDetails['closeStock']: 0;			
			
			$adjustment = 0;
			
			$itemType = 1;//for non usage type

			//get usage updated
			$usage =  ($openStock-$closeStock)+$barControl;//here issue in is not there is there would be then it will add in below one
			$usageAvg = $usage;
			$usagePerGuest = '';
			$usageCnt = $usage;
			
			$sql = "SELECT * FROM tbl_daily_import_items  WHERE outLetId = '".$outLetId."'  AND account_id = '".$_SESSION['accountId']."'  AND importedOn = '".$date."' AND barCode = '".$barCode."'   ";
			$qry = mysqli_query($con, $sql);
			$checkRow = mysqli_fetch_array($qry);
				
				$itemType = $row['itemType'] == 3 ? 2 : 1;//For 2 usage type, 1 others
				if($checkRow)
				{
					$issueIn = isset($checkRow['issueIn']) ? $checkRow['issueIn'] : 0;
					$usage += $issueIn;
				}
			
			$usagePerGuest =  ($row['itemType'] == 3 && $guests > 0) ? ($usage/$guests) : '';
			$usageAvgArr = getAvgUsage($outLetId, $barCode, $usage, $date);
			$usageAvg = $usageAvgArr['usageAvg'];
			$usageCnt = $usageAvgArr['usageCnt'];
			
			if($checkRow)
			{
				$easySales = $easySales > 0 ? $easySales : $checkRow['easySales'];
				
				$qry = " UPDATE tbl_daily_import_items SET  
							closeStock = '".$closeStock."',
							barControl = '".$barControl."',
							lastPrice = '".$stockPrice."',
							stockPrice = '".$stockPrice."',
							usagePerDay = '".$usage."',
							usageCnt = '".$usageCnt."',
							usageAvg = '".$usageAvg."',
							usagePerGuest = '".$usagePerGuest."',
							guests = '".$guests."',
							openStock = '".$openStock."',
							easySales = '".$easySales."',
							closeStockDone = 1
							
					  WHERE id = '".$checkRow['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";
				mysqli_query($con, $qry);
			}
			else
			{
				$qry = "INSERT INTO tbl_daily_import_items SET 
				outLetId = '".$outLetId."',
				parentId = '".$parentId."', 
				importedOn = '".$date."',
				barCode = '".$barCode."',
				closeStock = '".$closeStock."',
				barControl = '".$barControl."',
				lastPrice = '".$stockPrice."',
				stockPrice = '".$stockPrice."',
				usagePerDay = '".$usage."',
				usageCnt = '".$usageCnt."',
				usageAvg = '".$usageAvg."',
				usagePerGuest = '".$usagePerGuest."',
				guests = '".$guests."',
				openStock = '".$openStock."',
				itemType = '".$itemType."',
				account_id = '".$_SESSION['accountId']."',
				easySales = '".$easySales."',
				closeStockDone = 1
				 ";
				mysqli_query($con, $qry);
			}
		}
}//End Once we are importing excel data




function getOutLetByName($name)
{
	global $con;
	$sql = "SELECT * FROM tbl_deptusers 
		 WHERE name = '".$name."'  AND account_id = '".$_SESSION['accountId']."'  ";
	$outLetQry = mysqli_query($con, $sql);
	
	$row = mysqli_fetch_array($outLetQry);
	
	return $row;
}

function getRevOutLetDetByName($name)
{
	global $con;
	$sql = "SELECT du.name, rcd.id FROM tbl_deptusers du INNER JOIN tbl_revenue_center_departments rcd ON(du.id = rcd.deptId) AND du.account_id = rcd.account_id WHERE du.name = '".trim($name)."'  AND du.account_id = '".$_SESSION['accountId']."'  ";
	$outLetQry = mysqli_query($con, $sql);
	
	$row = mysqli_fetch_array($outLetQry);
	
	return $row;
}


function getTotalGuestsVal($fromDate, $toDate)
{	
	global $con;
	
	$sql = "SELECT  rc.id, rc.name, du.name as outletName, rcd.id as outLetId, rcd.outLetType FROM tbl_revenue_center_departments rcd
		INNER JOIN tbl_deptusers du ON(du.id = rcd.deptId) AND du.account_id = rcd.account_id
		INNER JOIN tbl_revenue_center rc ON(rc.id = rcd.revCenterId) AND rc.account_id = rcd.account_id
		INNER JOIN tbl_outlet_items o ON(o.outLetId = rcd.id) AND o.account_id = rcd.account_id
		WHERE du.account_id = '".$_SESSION['accountId']."'
		GROUP BY du.id order by rc.id desc ";
		$result = mysqli_query($con, $sql);
		
	$dates = [];	
	$guestsArr = [];		
	$guests = 0;						
	while($revRow = mysqli_fetch_array($result) )
	{
		$rcId = $revRow['id'];
		$sql = "SELECT * FROM tbl_daily_import 
		WHERE  outLetId = '".$revRow['outLetId']."'  AND account_id = '".$_SESSION['accountId']."'   AND importDate BETWEEN '".date( 'Y-m-d', strtotime($fromDate) )."' AND '".date( 'Y-m-d', strtotime($toDate) )."' order by id desc ";
					 	
		$outLetQry = mysqli_query($con, $sql);
		
		while( $row = mysqli_fetch_array($outLetQry) )
		{
		
				if( !isset($guestsArr[$rcId][$row['importDate']]) )
				{
					$guestsArr[$rcId][$row['importDate']] = $row['guests'];
					$guests += $row['guests'];
				}
		}
		
		
	}
	return $guests;
}

function getToken($mecId)
{	

	 $url = 'https://api.ipos247.com/v1/token';
	 $dataArr = ['merchant_id' => $mecId];
	 
	 //$data = '{"merchant_id":"3676523932f9fc21cc-8225-11eb-b"}';
	 //$data = '{"merchant_id":"21930"}';
	 $data = json_encode($dataArr);
	 $curl = curl_init();
   
	 curl_setopt($curl, CURLOPT_POST, 1);
	 if ($data)
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
       
   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   // EXECUTE:
    $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   
   
   $tokenArr = json_decode($result, true);
   
   return $tokenArr['token']; 
}

function getPosData($mecId, $date)
{

	$url = 'https://api.ipos247.com/v1/inventorydata';
	$data = json_encode($date);
	$token = getToken($mecId);
	
	$curl = curl_init();
  
	 curl_setopt($curl, CURLOPT_POST, 1);
	 if ($data)
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'X-EO-Auth-Key: '.$token,
      'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}
/////////////////
function getCurrencyDet($id)
{
	global $con;
	
	$sql = "SELECT * FROM tbl_currency 
	WHERE  id = '".$id."' AND account_id = '".$_SESSION['accountId']."'  ";
	$qry = mysqli_query($con, $sql);
	
	return mysqli_fetch_array($qry);


	
}

function getDefaultCurrencyDet($accountId)
{
	global $con;
	
	$sql = "SELECT * FROM tbl_currency 
	WHERE  is_default = '1' AND account_id = '".$accountId."'  ";
	$qry = mysqli_query($con, $sql);
	
	return mysqli_fetch_array($qry);
	
}

function getNumberFormat($val, $num)
{
	return number_format($val, $num);
}

function showOtherCur($amt, $curId)
{
	$curDet = getCurrencyDet($curId);
	
	return $amt ?  getNumberFormat($amt, $curDet['decPlace']) .' '.html_entity_decode($curDet['curCode'])  : '';
	
}

function getMappedOutLetsByHotelId($hotelId)
{
	global $con;
	$sql = "SELECT * FROM  `tbl_map_outlets`  WHERE `hotelId` = '".$hotelId."'  AND account_id = '".$_SESSION['accountId']."'  ";
	$qry = mysqli_query($con, $sql);
	
	$mapedOutLets = [];
	while($outLetRow = mysqli_fetch_array($qry))
	{
		$mapedOutLets[] = $outLetRow['outLetId'];
	}
	
	return $mapedOutLets;
}

function getSaleAmtEasy($outLetId, $easyCategories)
{
	global $con;
	
	$sql = "SELECT * FROM  `tbl_map_outletcats` mp INNER JOIN tbl_map_category c ON(c.id = mp.revCatId) AND c.account_id = mp.account_id WHERE mp.`revOutLetId` = '".$outLetId."'  AND mp.account_id = '".$_SESSION['accountId']."' ";
	$qry = mysqli_query($con, $sql);
	
	$easyCatNamesArr = [];
	foreach($easyCategories as $catRes)
	{
		$catName = trim($catRes['Name']);
		$easyCatNamesArr[$catName] = $catRes['Value'];
	}
		
	$outLetSaleAmt = 0;
	while($mapCatRow = mysqli_fetch_array($qry))
	{
		$catName = trim($mapCatRow['catName']);
		if( isset($easyCatNamesArr[$catName]) )
		{
			$outLetSaleAmt += $easyCatNamesArr[$catName];
		}
	}
	
	return $outLetSaleAmt;
}

function getNoteDet($outLetId, $pId, $noteDate)
{
	global $con;
	
	$sql = "SELECT * FROM tbl_revoutletnotes 
	WHERE  outLetId = '".$outLetId."' AND pId = '".$pId."'  AND account_id = '".$_SESSION['accountId']."' AND noteDate = '".date('Y-m-d', strtotime($noteDate) )."' ";
	$qry = mysqli_query($con, $sql);
	
	$res = mysqli_fetch_array($qry);
	
	return isset($res['notes']) ? $res['notes'] : '';
}

function getStorageItemsCount($storageDeptId)
{
	global $con;
	$sql = "SELECT count(s.id) totalItems FROM tbl_stocks s 
				INNER JOIN tbl_products tp ON(s.pId = tp.id) AND s.account_id = tp.account_id AND tp.status=1
				WHERE storageDeptId = '".$storageDeptId."'  AND s.account_id = '".$_SESSION['accountId']."'  ";
	$resQry = mysqli_query($con, $sql);
	$stockRow = mysqli_fetch_array($resQry);
	
	return ($stockRow['totalItems'] ? $stockRow['totalItems'] : 0);
}

function getOutLetItemsCount($outLetId, $isBarContol=0)
{
	global $con;
	
	$cond = '';
	if($isBarContol == 1)
	{
		$cond = " AND o.itemType = 1 ";
	}
	
	$sql = "select count(*) totalItems FROM tbl_outlet_items o
	 INNER JOIN tbl_products p ON(p.id = o.pId) AND p.account_id = o.account_id 
				
			 WHERE o.outLetId = '".$outLetId."' AND p.account_id = '".$_SESSION['accountId']."' ".$cond."  GROUP BY o.outLetId  ";
	$outLetItemsQry = mysqli_query($con, $sql);
	
	$outLetRow = mysqli_fetch_array($outLetItemsQry);
	
	return ($outLetRow['totalItems'] ? $outLetRow['totalItems'] : 0);
}

function trackStockProcessTime($stockTakeId, $stockTakeType, $userId, $updateTime=0)
{
	global $con;
	
	$sql=" SELECT * FROM  tbl_mobile_time_track WHERE stockTakeId = '".$stockTakeId."' AND account_id = '".$_SESSION['accountId']."'  AND `userId` = '".$userId."' AND `stockTakeType` = '".$stockTakeType."' AND status = '0' ";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	if ($row) {
		
			$sql = " UPDATE `tbl_mobile_time_track` SET  `end_time` = '".date('Y-m-d h:i:s')."'  WHERE `id` = '".$row['id']."' AND account_id = '".$_SESSION['accountId']."'   ";
			mysqli_query($con, $sql);

	}else{
		
		 $sql = "INSERT INTO `tbl_mobile_time_track` (`id`, `account_id`, `stockTakeId`, `userId`, `stockTakeType`, `start_time`) VALUES (NULL, '".$_SESSION['accountId']."', '".$stockTakeId."', '".$userId."', '".$stockTakeType."', '".date('Y-m-d h:i:s')."' ) ";

		mysqli_query($con, $sql);
		return mysqli_insert_id($con);
	}
	
}

function finishStockTake($stockTakeId, $stockTakeType, $userId)
{
	global $con, $getDefCurDet;
	
	
	$sql = " UPDATE `tbl_mobile_items_temp` SET  status=1 
	WHERE stockTakeId = '".$stockTakeId."'  AND account_id = '".$_SESSION['accountId']."'  AND `stockTakeType` = '".$stockTakeType."' AND status=0 ";
	mysqli_query($con, $sql);
	
	$sql = " UPDATE `tbl_mobile_time_track` SET  status=1  WHERE stockTakeId = '".$stockTakeId."' AND account_id = '".$_SESSION['accountId']."' AND `stockTakeType` = '".$stockTakeType."' AND status=0 ";
	mysqli_query($con, $sql);

	//receive order part start here
	if($stockTakeType == 3)
	{
		$sql = " SELECT * FROM tbl_orders WHERE id = '".$stockTakeId."' AND account_id = '".$_SESSION['accountId']."' ";
		$res = mysqli_query($con, $sql);
		$resRowOld = mysqli_fetch_array($res);

		$sql = " SELECT * FROM `tbl_mobile_items_temp`
		WHERE stockTakeId = '".$stockTakeId."'  AND account_id = '".$_SESSION['accountId']."' AND `userId` = '".$userId."' AND `stockTakeType` = '".$stockTakeType."' AND status=1 ";
		$result = mysqli_query($con, $sql);

		while($resultSet = mysqli_fetch_array($result))
		{
			$sel = " SELECT * FROM tbl_order_details WHERE ordId='".$stockTakeId."' AND account_id = '".$_SESSION['accountId']."' AND pId = '".$resultSet['pId']."' ";
			$res = mysqli_query($con, $sel);
			$resRow = mysqli_fetch_array($res);

			$cur = " SELECT * FROM tbl_currency WHERE id = '".$resRow['currencyId']."' AND account_id = '".$_SESSION['accountId']."' ";
			$curRes = mysqli_query($con, $cur);
			$curRow = mysqli_fetch_array($curRes);

			$curAmt = $curRow['amt'];

			if ($resRow['currencyId'] > 0 && $resultSet['curId'] > 0)
			{
				
				$qry = " UPDATE `tbl_order_details` SET 
				`price`='".(($resultSet['amt']/$resRow['factor'])/$curAmt)."', 
				`qtyReceived`='".$resultSet['qty']."', 
				`totalAmt`='".(($resultSet['amt']*$resultSet['qty'])/$curAmt)."',
				`curPrice`='".($resultSet['amt']/$resRow['factor'])."',
				`curAmt`='".($resultSet['amt']*$resultSet['qty'])."' 
				WHERE ordId = '".$stockTakeId."' AND account_id = '".$_SESSION['accountId']."' AND pId = '".$resultSet['pId']."' ";
				mysqli_query($con, $qry);

				$qry = " UPDATE `tbl_order_details_temp` SET 
				`price`='".(($resultSet['amt']/$resRow['factor'])/$curAmt)."', 
				`qtyReceived`='".$resultSet['qty']."', 
				`totalAmt`='".(($resultSet['amt']*$resultSet['qty'])/$curAmt)."',
				`curPrice`='".($resultSet['amt']/$resRow['factor'])."',
				`curAmt`='".($resultSet['amt']*$resultSet['qty'])."' 
				WHERE ordId = '".$stockTakeId."' AND account_id = '".$_SESSION['accountId']."' AND pId = '".$resultSet['pId']."' ";
				mysqli_query($con, $qry);
			}
			elseif($resRow['currencyId'] > 0)
			{
				$qry = " UPDATE `tbl_order_details` SET 
				`price`='".($resultSet['amt']/$resRow['factor'])."', 
				`qtyReceived`='".$resultSet['qty']."', 
				`totalAmt`='".($resultSet['amt']*$resultSet['qty'])."',
				`curPrice`='".(($resultSet['amt']/$resRow['factor'])*$curAmt)."',
				`curAmt`='".(($resultSet['amt']*$resultSet['qty'])*$curAmt)."' 
				WHERE ordId = '".$stockTakeId."' AND account_id = '".$_SESSION['accountId']."' AND pId = '".$resultSet['pId']."' ";
				mysqli_query($con, $qry);

				$qry = " UPDATE `tbl_order_details_temp` SET 
				`price`='".($resultSet['amt']/$resRow['factor'])."', 
				`qtyReceived`='".$resultSet['qty']."', 
				`totalAmt`='".($resultSet['amt']*$resultSet['qty'])."',
				`curPrice`='".(($resultSet['amt']/$resRow['factor'])*$curAmt)."',
				`curAmt`='".(($resultSet['amt']*$resultSet['qty'])*$curAmt)."' 
				WHERE ordId = '".$stockTakeId."' AND account_id = '".$_SESSION['accountId']."' AND pId = '".$resultSet['pId']."' ";
				mysqli_query($con, $qry);
			}
			else
			{
				$qry = " UPDATE `tbl_order_details` SET 
				`price`='".($resultSet['amt']/$resRow['factor'])."', 
				`qtyReceived`='".$resultSet['qty']."', 
				`totalAmt`='".($resultSet['amt']*$resultSet['qty'])."'
				
				WHERE ordId = '".$stockTakeId."' AND account_id = '".$_SESSION['accountId']."' AND pId = '".$resultSet['pId']."' ";
				mysqli_query($con, $qry);

				$qry = " UPDATE `tbl_order_details_temp` SET 
				`price`='".($resultSet['amt']/$resRow['factor'])."', 
				`qtyReceived`='".$resultSet['qty']."', 
				`totalAmt`='".($resultSet['amt']*$resultSet['qty'])."',
				`curPrice`='".(($resultSet['amt']/$resRow['factor'])*$curAmt)."',
				`curAmt`='".(($resultSet['amt']*$resultSet['qty'])*$curAmt)."' 
				WHERE ordId = '".$stockTakeId."' AND account_id = '".$_SESSION['accountId']."' AND pId = '".$resultSet['pId']."' ";
				mysqli_query($con, $qry);
			}
			

		}
		//Insert totalAmt value in order_journey tbl according to (qty*price) when mobile user change qty from mobile
		
		$ordNetTotArr = receiveOrdTotal($userId,'',$stockTakeId, 1);

		$diffPrice =  $ordNetTotArr['ordAmt']-$resRowOld['ordAmt'];
		$notes = 'Order edited(Price Diff: '.getPriceWithCur($diffPrice, $getDefCurDet['curCode']) .' )';

		$qry = " INSERT INTO `tbl_order_journey` SET 
		`account_id` = '".$_SESSION['accountId']."',
		`orderId` = '".$stockTakeId."',
		`userBy`  = '".$userId."',
		`ordDateTime` = '".date('Y-m-d h:i:s')."',
		`amount` = '".$ordNetTotArr['ordAmt']."',
		`otherCur` = '".$ordNetTotArr['ordCurAmt']."',
		`otherCurId` = '".$resRowOld['ordCurId']."',
		`orderType` = '".$resRowOld['ordType']."',
		`notes` = '".$notes."',
		`action` = 'Mobile Received' ";
		mysqli_query($con, $qry);
		
		//this column will be used to check to show/hide edit order page in running task
		$qry = " UPDATE `tbl_orders` SET 
				`orderReceivedByMobUser`=1 
				
				WHERE id = '".$stockTakeId."' AND account_id = '".$_SESSION['accountId']."'  ";
				mysqli_query($con, $qry);

	}


	//out let stock take then do this
	if($stockTakeType == 2 || $stockTakeType == 5)
	{
		reportStockTakeFromMobile($stockTakeId, $userId, $stockTakeType);
	}
	//End outlet stock take
	
	//issue out then do this
	if($stockTakeType == 4)
	{
		issueOutMobile($stockTakeId, $userId);
	}
	//End outlet stock take

}


function getMobileStockTakeTotalCount()
{
	global $con;
	
	// $sql=" SELECT count(*) totalPendingStockTake FROM  tbl_mobile_time_track WHERE stockTakeId = '".$storageId."'  AND account_id = '".$_SESSION['accountId']."'   AND `stockTakeType` = 1 AND status=1 ";
	$sql=" SELECT *
	FROM  tbl_mobile_time_track t 
		inner join tbl_mobile_items_temp it 
			ON(t.id = it.processId) AND t.account_id = it.account_id AND it.status=1
			INNER JOIN tbl_user u 
			ON(t.userId = u.id) AND t.account_id = u.account_id 
	WHERE  t.account_id = '".$_SESSION['accountId']."'   AND t.stockTakeType = 1 AND t.status=1 GROUP BY it.processId ";
	$result = mysqli_query($con, $sql);
	$row = mysqli_num_rows($result);
	
	return  ( ($row > 0) ? $row : '0');
	
}

function getMobileStockTakeCount($storageId)
{
	global $con;
	
	// $sql=" SELECT count(*) totalPendingStockTake FROM  tbl_mobile_time_track WHERE stockTakeId = '".$storageId."'  AND account_id = '".$_SESSION['accountId']."'   AND `stockTakeType` = 1 AND status=1 ";
	$sql=" SELECT *
	FROM  tbl_mobile_time_track t 
		inner join tbl_mobile_items_temp it 
			ON(t.id = it.processId) AND t.account_id = it.account_id AND it.status=1
			INNER JOIN tbl_user u 
			ON(t.userId = u.id) AND t.account_id = u.account_id 
	WHERE t.stockTakeId = '".$storageId."'  AND t.account_id = '".$_SESSION['accountId']."'   AND t.stockTakeType = 1 AND t.status=1 GROUP BY it.processId ";
	$result = mysqli_query($con, $sql);
	$row = mysqli_num_rows($result);
	
	return  ( ($row > 0) ? ('<span class="mblCnt">'.$row).'</span>' : '');
	
}

function getRevenueOutLetDetailsById($outLetId)
{
	global $con;
	$sql = "SELECT du.name, rcd.id, rcd.revCenterId, rcd.outLetType FROM tbl_deptusers du INNER JOIN tbl_revenue_center_departments rcd ON(du.id = rcd.deptId) AND du.account_id = rcd.account_id WHERE rcd.id = '".$outLetId."'  AND rcd.account_id = '".$_SESSION['accountId']."'  ";
	$outLetQry = mysqli_query($con, $sql);
	
	$row = mysqli_fetch_array($outLetQry);
	
	return $row;
}

function reportStockTakeFromMobile($stockTakeId, $userId, $stockTakeType)
{
	global $con;
			
	$sql = "SELECT tp.barCode , o.itemType, o.factor, s.account_id, s.qty StockTakeQty FROM tbl_mobile_items_temp s 
		INNER JOIN tbl_products tp 
		ON(s.pId = tp.id) AND s.account_id = tp.account_id AND tp.status=1 AND s.stockTakeId = '".$stockTakeId."' AND s.userId = '".$userId."' AND s.stockTakeType='".$stockTakeType."' AND s.status=1
		INNER JOIN tbl_outlet_items o ON(o.pId = s.pId) AND o.account_id = s.account_id AND o.outLetId = s.stockTakeId
		WHERE  s.account_id = '".$_SESSION['accountId']."' 
	    GROUP BY tp.id   ";
	$stockMainQry = mysqli_query($con, $sql); 

	$formatedRows = [];
	while( $stockTakeRes = mysqli_fetch_array($stockMainQry) )
	{
		$barCode = $stockTakeRes['barCode'];
		$formatedRows[$barCode]['StockTakeQty'] = $stockTakeRes['StockTakeQty'];
		$formatedRows[$barCode]['itemType'] = $stockTakeRes['itemType'];
		$formatedRows[$barCode]['factor'] = $stockTakeRes['factor'];//this is here because it may be only one barcode is available from these 3
	}
	
	$sql = " DELETE FROM `tbl_mobile_items_temp`  
					 WHERE stockTakeId = '".$stockTakeId."' AND account_id = '".$_SESSION['accountId']."'  AND `userId` = '".$userId."' AND `stockTakeType` = '".$stockTakeType."' AND status=1 ";
	mysqli_query($con, $sql);
	
	$sql = " DELETE FROM `tbl_mobile_time_track`    WHERE stockTakeId = '".$stockTakeId."' AND account_id = '".$_SESSION['accountId']."'  AND `userId` = '".$userId."' AND `stockTakeType` = '".$stockTakeType."' AND status=1 ";
	mysqli_query($con, $sql);
	
	if($stockTakeType == 5)//bar control
	{	
		updateReportForBarControlByOutLet($stockTakeId, $formatedRows);
	}
	else
	{
		updateReportForStockTakeByOutLet($stockTakeId, $formatedRows);
	}

	
}


//This is for mobile bar control
function updateReportForBarControlByOutLet($outLetId, $formatedRows)
{
	global $con;
	
	//--------insert master data -----------------------------
		$date = date('Y-m-d', strtotime('-1 day'));
		
		$sql = "SELECT * FROM tbl_daily_import  WHERE outLetId = '".$outLetId."'  AND account_id = '".$_SESSION['accountId']."' AND importDate = '".$date."' ";
		$qry = mysqli_query($con, $sql);
		$checkRow = mysqli_fetch_array($qry);

		if($checkRow)
		{			
			$parentId = $checkRow['id'];
		}
		else
		{
			 $qry = " INSERT INTO tbl_daily_import SET
			 outLetId = '".$outLetId."', account_id = '".$_SESSION['accountId']."',  importDate = '".$date."' ";
			mysqli_query($con, $qry);
			$parentId = mysqli_insert_id($con);
		}
		
		
	//--------end --------------------------------------------
		
	foreach($formatedRows as $barCode=>$row)
	{
			
			$proDetails = getStockProductByBarCode($barCode);
			$stockPrice = getPrice($proDetails['stockPrice'])/$row['factor'];
						
			$itemType = 1;//for non usage type

			//get old data
			
			$sql = "SELECT * FROM tbl_daily_import_items  WHERE outLetId = '".$outLetId."' AND account_id = '".$_SESSION['accountId']."'  AND importedOn = '".$date."' AND barCode = '".$barCode."'   ";
			$qry = mysqli_query($con, $sql);
			$checkRow = mysqli_fetch_array($qry);
			//End
			
			
			if($checkRow)
			{
			
				//update usage as well---------------------------------------------
						
					$usage = ($checkRow['openStock']+$checkRow['issueIn'])-$checkRow['closeStock'];
					$barControl = $row['StockTakeQty'];
					$usage += $barControl;						
					
					$usageAvgArr = getAvgUsage($outLetId, $barCode, $usage, $date);
					$usageAvg = $usageAvgArr['usageAvg'];
					$usageCnt = $usageAvgArr['usageCnt'];
			
			//end update usage -------------------------------------------------
				
				
				
				
				//easySales = '".$easySales."',
				 $qry = " UPDATE tbl_daily_import_items SET  
							barControl = '".$row['StockTakeQty']."',
							usagePerDay = '".$usage."',
							usageCnt = '".$usageCnt."',
							usageAvg = '".$usageAvg."',
							
							lastPrice = '".$stockPrice."',
							stockPrice = '".$stockPrice."'
							
					  WHERE id = '".$checkRow['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";
				mysqli_query($con, $qry);
			}
			else
			{
			//easySales = '".$easySales."',
				$qry = "INSERT INTO tbl_daily_import_items SET 
				outLetId = '".$outLetId."',
				parentId = '".$parentId."', 
				importedOn = '".$date."',
				barCode = '".$barCode."',
				barControl = '".$row['StockTakeQty']."',
				lastPrice = '".$stockPrice."',
				stockPrice = '".$stockPrice."',
				itemType = '".$itemType."',
				account_id = '".$_SESSION['accountId']."' 
				 ";
				mysqli_query($con, $qry);
			}
		}
}


//This is for mobile outlet stock take | outletReport
function updateReportForStockTakeByOutLet($outLetId, $formatedRows)
{
	global $con; 
	
	//--------insert master data -----------------------------
		$date = date('Y-m-d', strtotime('-1 day'));
		
		$sql = "SELECT * FROM tbl_daily_import  WHERE outLetId = '".$outLetId."' AND account_id = '".$_SESSION['accountId']."'  AND importDate = '".$date."' ";
		$qry = mysqli_query($con, $sql);
		$checkRow = mysqli_fetch_array($qry);

		$guests = 0;
		if($checkRow)
		{			
			$parentId = $checkRow['id'];
			$guests = $checkRow['guests'];
			
		}
		else
		{
			 $qry = " INSERT INTO tbl_daily_import SET
			 outLetId = '".$outLetId."', importDate = '".$date."', account_id = '".$_SESSION['accountId']."'  ";
			mysqli_query($con, $qry);
			$parentId = mysqli_insert_id($con);
		}
		
		
	//--------end --------------------------------------------
		
	foreach($formatedRows as $barCode=>$row)
	{
					
			$factor = $row['itemType'] == 1 ? $row['factor'] : 1;//if item type bar control then pick actual factory else just 1 to have no impact


			$closeStock	 = $row['StockTakeQty']*$factor;
			
			$proDetails = getStockProductByBarCode($barCode);//get last price and stock price
			
			$stockPrice = getPrice($proDetails['stockPrice']/$row['factor']);
			$lastPrice = $stockPrice;
						
			$lastDate = date('Y-m-d', strtotime('-1 day', strtotime($date)) );
			$itemDetails = getLastDayItemDetails($outLetId, $barCode, $lastDate );
			$openStock = $itemDetails['closeStock'] > 0 ? $itemDetails['closeStock']: 0;			
						
			$itemType = 1;//for non usage type

			//get usage updated
			$usage = ($openStock-$closeStock);//issue in will be added below
			
			$sql = "SELECT * FROM tbl_daily_import_items  WHERE outLetId = '".$outLetId."'  AND account_id = '".$_SESSION['accountId']."' AND importedOn = '".$date."' AND barCode = '".$barCode."'   ";
			$qry = mysqli_query($con, $sql);
			$checkRow = mysqli_fetch_array($qry);
			
			$itemType = $row['itemType'] == 3 ? 2 : 1;//For 2 usage type, 1 others
			if($checkRow)
			{
				$issueIn = isset($checkRow['issueIn']) ? $checkRow['issueIn'] : 0;
				$barControl = isset($checkRow['barControl']) ? $checkRow['barControl'] : 0;
				$usage += $issueIn + $barControl;						
			}
			//End
			
			$usagePerGuest =  ($row['itemType'] == 3 && $guests > 0) ? ($usage/$guests) : '';
			$usageAvgArr = getAvgUsage($outLetId, $barCode, $usage, $date);
			$usageAvg = $usageAvgArr['usageAvg'];
			$usageCnt = $usageAvgArr['usageCnt'];
				
			if($checkRow)
			{
				//easySales = '".$easySales."',
				 $qry = " UPDATE tbl_daily_import_items SET  
							closeStock = '".$closeStock."',
							lastPrice = '".$lastPrice."',
							stockPrice = '".$stockPrice."',
							usagePerDay = '".$usage."',
							usageCnt = '".$usageCnt."',
							usageAvg = '".$usageAvg."',
							usagePerGuest = '".$usagePerGuest."',
							openStock = '".$openStock."',
							closeStockDone = 1
					  WHERE id = '".$checkRow['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";
				mysqli_query($con, $qry);
			}
			else
			{
			//easySales = '".$easySales."',
				$qry = "INSERT INTO tbl_daily_import_items SET 
				outLetId = '".$outLetId."',
				parentId = '".$parentId."', 
				importedOn = '".$date."',
				barCode = '".$barCode."',
				closeStock = '".$closeStock."',
				lastPrice = '".$lastPrice."',
				stockPrice = '".$stockPrice."',
				usagePerDay = '".$usage."',
				usageCnt = '".$usageCnt."',
				usageAvg = '".$usageAvg."',
				usagePerGuest = '".$usagePerGuest."',
				openStock = '".$openStock."',
				itemType = '".$itemType."',
				account_id = '".$_SESSION['accountId']."',
				closeStockDone = 1
				 ";
				mysqli_query($con, $qry);
			}
		}
}

function issueOutMobile($orderId, $userId)
{
	global $con;
	
	$sql=" SELECT * FROM  tbl_mobile_items_temp WHERE stockTakeId = '".$orderId."'  AND account_id = '".$_SESSION['accountId']."' AND stockTakeType=4  AND status=1 AND userId = '".$userId."' ";
	$result = mysqli_query($con, $sql);

	$sql = " SELECT * FROM tbl_orders WHERE id = '".$orderId."' AND account_id = '".$_SESSION['accountId']."' ";
	$res = mysqli_query($con, $sql);
	$resRowOld = mysqli_fetch_array($res);

	$productIds = [];
	while($row = mysqli_fetch_array($result) )
	{	

		$sqlQry = " SELECT * FROM tbl_order_details WHERE ordId='".$orderId."' AND account_id='".$_SESSION['accountId']."' AND pId='".$row['pId']."' ";
		$orderDetailsResult = mysqli_query($con, $sqlQry);
		$orderDetailsResRow = mysqli_fetch_array($orderDetailsResult);

		if ($orderDetailsResRow['qty'] > $row['qty']) {
	        $tempQty = ($orderDetailsResRow['qty'] - $row['qty']);
	        $sql = " UPDATE tbl_stocks SET tempQty = (tempQty - '".$tempQty."') WHERE pId = '".$row['pId']."' AND account_id = '".$_SESSION['accountId']."' ";
	        mysqli_query($con, $sql);

	    }
	    elseif($orderDetailsResRow['qty'] < $row['qty']){
	        $tempQty = ($row['qty'] - $orderDetailsResRow['qty']);
	        $sql = " UPDATE tbl_stocks SET tempQty = (tempQty + '".$tempQty."') WHERE pId = '".$row['pId']."' AND account_id = '".$_SESSION['accountId']."' ";
	        mysqli_query($con, $sql);
	    }

		$sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$row['pId']."' AND account_id = '".$_SESSION['accountId']."'  ";
		$stkQry = mysqli_query($con, $sql);
		$stkRow = mysqli_fetch_array($stkQry);
		
		$qry = " UPDATE `tbl_order_details` SET 
		`price`='".$stkRow['stockPrice']."', 
		`qty`='".$row['qty']."',
		`lastPrice` = '".$stkRow['lastPrice']."',
		`stockPrice` = '".$stkRow['stockPrice']."',
		`stockQty` = '".($stkRow['qty'] - $row['qty'])."',
		`totalAmt`='".($stkRow['stockPrice']*$row['qty'])."' WHERE ordId = '".$orderId."' AND account_id = '".$_SESSION['accountId']."' AND pId = '".$row['pId']."' ";
		mysqli_query($con, $qry);

		$qry = " UPDATE `tbl_order_details_temp` SET 
		`price`='".$stkRow['stockPrice']."', 
		`qty`='".$row['qty']."',
		`lastPrice` = '".$stkRow['lastPrice']."',
		`stockPrice` = '".$stkRow['stockPrice']."',
		`stockQty` = '".($stkRow['qty'] - $row['qty'])."',
		`totalAmt`='".($stkRow['stockPrice']*$row['qty'])."' WHERE ordId = '".$orderId."' AND account_id = '".$_SESSION['accountId']."' AND pId = '".$row['pId']."' ";
		mysqli_query($con, $qry);
		
		$productIds[$row['pId']]['qty'] = $row['qty'];

	}

		$ordNetTotal = requisitionTotalValue($orderId, 1);

		
		$diffPrice =  $ordNetTotal-$resRowOld['ordAmt'];
		$notes = 'Requisition edited(Price Diff: '.$diffPrice .' )';

		$qry = " INSERT INTO `tbl_order_journey` SET 
		`account_id` = '".$_SESSION['accountId']."',
		`orderId` = '".$orderId."',
		`userBy`  = '".$userId."',
		`ordDateTime` = '".date('Y-m-d h:i:s')."',
		`amount` = '".$ordNetTotal."',
		`orderType` = '".$resRowOld['ordType']."',
		`notes` = '".$notes."',
		`action` = 'Mobile IssueOut' ";
		mysqli_query($con, $qry);


		$updateQry = " UPDATE tbl_orders SET ordAmt = '".$ordNetTotal."'
		WHERE id = '".$orderId."' AND account_id = '".$_SESSION['accountId']."' ";
		mysqli_query($con, $updateQry);	

}


function showOrderCharge($chargeDet)
{
	
	if( $chargeDet['feeType'] == 1)
	{
		showPrice($chargeDet['amt'], $getDefCurDet['curCode'], 2);
	}
	else
	{
		echo $chargeDet['amt'].'%';
	}
}

function insertCustomCharges($ordId, $currencyId, $otherCurAmt, $ordAmt,$noteArr,$supplierId )
{
	global $con;
	
	$chargesTot = 0;
	$chargesTotOtherCur = 0;
	if( isset($_SESSION['itemCharges'][1]) && count($_SESSION['itemCharges'][1]) > 0  )//custom charge items
	{
		$itemIds = implode(',', $_SESSION['itemCharges'][1]);
					
		$sqlSet = " SELECT * FROM tbl_custom_items_fee WHERE id IN(".$itemIds.") AND account_id = '".$_SESSION['accountId']."'   ";
		$resRows = mysqli_query($con, $sqlSet);
		$values = [];
		while( $row = mysqli_fetch_array($resRows) ) 
		{
			$note= isset($noteArr[$row['id']]) ? $noteArr[$row['id']] : '';
			$values[] .= " (NULL, '".$_SESSION['accountId']."',  '".$ordId."', '".$note."', '1', 
						'".($row['id'])."',
						'".($row['amt'])."',
						'".($row['amt'])."',
						'1',
						'".($row['amt'])."',
						'".$currencyId."',
						'".($row['amt']*$otherCurAmt)."'
						
						) ";
						
			$chargesTot += $row['amt'];
			$ordAmt += $row['amt'];
			$chargesTotOtherCur += ($row['amt']*$otherCurAmt);
		}
		
		$insertQry = " INSERT INTO `tbl_order_details` (`id`, `account_id`, `ordId`, `note`, `customChargeType`, `customChargeId`, `price`, `curPrice`, `qty`, `totalAmt`, `currencyId`, `curAmt`) VALUES  ".implode(',', $values);
		mysqli_query($con, $insertQry);

	}
	
	if( isset($_SESSION['itemCharges'][3]) && count($_SESSION['itemCharges'][3]) > 0  )//add order charges 
	{
		$itemIds = implode(',', $_SESSION['itemCharges'][3]);
		
		$sqlSet = " SELECT * FROM tbl_order_fee WHERE id IN(".$itemIds.")  AND account_id = '".$_SESSION['accountId']."'   ";
		$resRows = mysqli_query($con, $sqlSet);
		$values = [];
		while( $row = mysqli_fetch_array($resRows) ) 
		{
			$values[] .= " (NULL, '".$_SESSION['accountId']."',  '".$ordId."', '2', 
						'".($row['id'])."',
						'".($row['amt'])."',
						'".($row['amt'])."',
						'1',
						'".($row['amt'])."',
						'".$currencyId."',
						'".($row['amt']*$otherCurAmt)."'
						
						) ";
						
			$chargesTot += $row['amt'];
			$chargesTotOtherCur += ($row['amt']*$otherCurAmt);
		}
		
		$insertQry = " INSERT INTO `tbl_order_details` (`id`, `account_id`, `ordId`, `customChargeType`, `customChargeId`, `price`, `curPrice`, `qty`, `totalAmt`, `currencyId`, `curAmt`) VALUES  ".implode(',', $values);
		mysqli_query($con, $insertQry);
		
	}	
	
	// if( isset($_SESSION['itemCharges']) )
	// {
	// 	unset($_SESSION['itemCharges']);
	// }
	
	return array('chargesTot' -> $chargesTot, 'chargesTotOtherCur' -> $chargesTotOtherCur);
}

function insertReqCustomCharges($ordId, $ordAmt, $noteArr,$deptId )
{
	global $con;
	
	$chargesTot = 0;
	if( isset($_SESSION['itemCharges'][1]) && count($_SESSION['itemCharges'][1]) > 0  )//custom charge items
	{
		$itemIds = implode(',', $_SESSION['itemCharges'][1]);
					
		$sqlSet = " SELECT * FROM tbl_custom_items_fee WHERE id IN(".$itemIds.")  AND account_id = '".$_SESSION['accountId']."'  ";
		$resRows = mysqli_query($con, $sqlSet);
		$values = [];
		while( $row = mysqli_fetch_array($resRows) ) 
		{
			$note= isset($noteArr[$row['id']]) ? $noteArr[$row['id']] : '';

			$values[] .= " (NULL, '".$_SESSION['accountId']."', '".$ordId."', '".$note."', '1', 
						'".($row['id'])."',
						'".($row['amt'])."',
						'1',
						'".($row['amt'])."'
						
						) ";
						
			$chargesTot += $row['amt'];
			$ordAmt += $row['amt'];
		}
		$insertQry = " INSERT INTO `tbl_order_details` (`id`, `account_id`, `ordId`, `note`, `customChargeType`, `customChargeId`, `price`,  `qty`, `totalAmt`) VALUES  ".implode(',', $values);
		mysqli_query($con, $insertQry);
	}
	
	if( isset($_SESSION['itemCharges'][3]) && count($_SESSION['itemCharges'][3]) > 0  )//add order charges 
	{
		$itemIds = implode(',', $_SESSION['itemCharges'][3]);
		
		$sqlSet = " SELECT * FROM tbl_order_fee WHERE id IN(".$itemIds.")  AND account_id = '".$_SESSION['accountId']."'   ";
		$resRows = mysqli_query($con, $sqlSet);
		$values = [];
		while( $row = mysqli_fetch_array($resRows) ) 
		{
			$values[] .= " (NULL, '".$_SESSION['accountId']."',  '".$ordId."', '2', 
						'".($row['id'])."',
						'".($row['amt'])."',
						'1',
						'".($row['amt'])."'
						) ";
						
			$chargesTot += $row['amt'];
		}
		
		$insertQry = " INSERT INTO `tbl_order_details` (`id`, `account_id`, `ordId`, `customChargeType`, `customChargeId`, `price`, `qty`, `totalAmt`) VALUES  ".implode(',', $values);
		mysqli_query($con, $insertQry);
		
	}	

	return array('chargesTot' -> $chargesTot );
}



//Edit custom charge

function editCustomCharge($ordId, $feeType, $customChargeId, $SupplierIdOrdept, $isEditOrder=0)
{
	global $con;
	//echo $page;

	$sqlSet = " SELECT * FROM tbl_orders WHERE id = '".$ordId."' AND account_id = '".$_SESSION['accountId']."'   ";
	$resultSet = mysqli_query($con, $sqlSet);
	$ordRow = mysqli_fetch_array($resultSet);

	if ($ordRow['ordType']=='1') {
		
		$supplierId = $SupplierIdOrdept;

	}else{

		$deptId = $SupplierIdOrdept;
	}
	
	$ordCurId = $ordRow['ordCurId'];

	$curAmt = 0;
	if($ordCurId > 0)
	{ 
		$curDet = getCurrencyDet($ordCurId);
		$curAmt = $curDet['amt'];
	}
	
	if( $feeType == 1 )//item charges
	{
		$sqlSet = " SELECT * FROM tbl_custom_items_fee WHERE id='".$customChargeId."' AND account_id='".$_SESSION['accountId']."'  ";
		$resRows = mysqli_query($con, $sqlSet);
	    $row = mysqli_fetch_array($resRows);
	    $customChargeType = 1;
	}
	elseif( $feeType == 3 )//order item charges
	{ 
		$sqlSet = " SELECT * FROM tbl_order_fee WHERE id='".$customChargeId."' AND account_id='".$_SESSION['accountId']."'  ";
		$resRows = mysqli_query($con, $sqlSet);
		$row = mysqli_fetch_array($resRows);
		$customChargeType =2;
	}

	if($isEditOrder==1)
	{
		 $sql=" SELECT * FROM tbl_order_details_temp WHERE ordId='".$ordId."' AND customChargeId='".$customChargeId."' AND customChargeType='".$customChargeType."'  AND account_id = '".$_SESSION['accountId']."' ";
			$sqlSet= mysqli_query($con, $sql);	
			$sqlSetRow= mysqli_fetch_array($sqlSet);

			if ( !isset($sqlSetRow['customChargeId']) )
			{ 
					
					$values[] = " (NULL, 
						'".$_SESSION['accountId']."',
						'".$ordId."',
						'".$note."',
						'".$customChargeType."', 
						'".($row['id'])."',
						'".($row['amt'])."',
						'1',
						'".($row['amt'])."',
						'".($ordRow['ordCurId'])."',
						'".($row['amt']*$curAmt)."',
						'".($row['amt']*$curAmt)."'
						) ";
			 

					$insertQry = " INSERT INTO `tbl_order_details_temp` (`id`, `account_id`, `ordId`, `note`, `customChargeType`, `customChargeId`, `price`, `qty`, `totalAmt`, `currencyId`, `curPrice`, `curAmt`) VALUES  ".implode(',', $values);
					mysqli_query($con, $insertQry); 

			}

		}else{	
				
				$sql=" SELECT * FROM tbl_order_details WHERE ordId='".$ordId."' AND customChargeId='".$customChargeId."' AND customChargeType='".$customChargeType."'  AND account_id = '".$_SESSION['accountId']."' ";
				$sqlSet= mysqli_query($con, $sql);	
				$sqlSetRow= mysqli_fetch_array($sqlSet);

				if ( !isset($sqlSetRow['customChargeId']) )
				{ 
						
						$values[] = " (NULL, 
							'".$_SESSION['accountId']."',
							'".$ordId."',
							'".$note."',
							'".$customChargeType."', 
							'".($row['id'])."',
							'".($row['amt'])."',
							'1',
							'".($row['amt'])."',
							'".($ordRow['ordCurId'])."',
							'".($row['amt']*$curAmt)."',
							'".($row['amt']*$curAmt)."'
							) ";


						$insertQry = " INSERT INTO `tbl_order_details` (`id`, `account_id`, `ordId`, `note`, `customChargeType`, `customChargeId`, `price`, `qty`, `totalAmt`, `currencyId`, `curPrice`, `curAmt`) VALUES  ".implode(',', $values);
						mysqli_query($con, $insertQry);

				}
			}
	

}





function orderNetValue($ordId,$currencyId){

	global $con;

	$sqlSet="SELECT SUM(totalAmt) AS totalAmt, SUM(curAmt) AS totalAmtOther FROM tbl_order_details WHERE ordId='".$ordId."'  AND account_id = '".$_SESSION['accountId']."' AND (customChargeType='1' OR customChargeType='0')";
	$resultSet = mysqli_query($con, $sqlSet);
	$chargeRow = mysqli_fetch_array($resultSet);	
	$chargePrice=$chargeRow['totalAmt'];
	$chargePriceOther=$chargeRow['totalAmtOther'];
														
									
//start order lelvel fixed discount charges
	$sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details od 
			INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
			WHERE od.ordId = '".$ordId."'  AND od.account_id = '".$_SESSION['accountId']."' and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

	$ordQry = mysqli_query($con, $sql);

	$fixedCharges = 0;
	$fixedChargesOther = 0;
	while($row = mysqli_fetch_array($ordQry))//show here order level charges
		{
			$fixedCharges += $row['price'];
			$fixedChargesOther += $row['curAmt'];
					
	 	} //Ends order lelvel fixed discount charges



	  		//Starts order level per discount charges
			$sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details od 
			      INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
			      WHERE od.ordId ='".$ordId."'  AND od.account_id='".$_SESSION['accountId']."'  AND od.customChargeType=2 AND tp.feeType=3 ORDER BY tp.feeName ";
			$ordQry = mysqli_query($con, $sql);

			$perCharges = 0;
			$perChargesOther = 0;
			while($row = mysqli_fetch_array($ordQry))//show here order level charges
				{
					$perCharges += $row['price'];
					$calDiscount = ($chargePrice*$row['price']/100);
					$calDiscountOther = ($chargePriceOther*$row['price']/100);

			     } //Ends order lelvel per discount charges

			       $totalCalDiscount =($chargePrice*$perCharges/100);//total discount feeType=3
			       $totalCalDiscountOther = ($chargePriceOther*$perCharges/100);

			       //start order lelvel tax discount charges
			    $sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details od 
			       INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
			       WHERE od.ordId = '".$ordId."'  AND od.account_id = '".$_SESSION['accountId']."' AND od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
			       $ordQry = mysqli_query($con, $sql);

			       $taxCharges = 0;
			       $totalTaxChargesOther = 0;

				while($row = mysqli_fetch_array($ordQry))//show here order level charges
				{   $currencyId = $row['currencyId'];
					$taxCharges += $row['price'];
					$calTax = (($chargePrice+ $fixedCharges+$totalCalDiscount)*$row['price']/100);
					$calTaxOther = (($chargePriceOther+ $fixedChargesOther+$totalCalDiscountOther)*$row['price']/100);
					
			      } //Ends order lelvel tax discount charges

			      $totalTax =(($chargePrice+$fixedCharges+$totalCalDiscount)*$taxCharges/100);

			      $totalTaxOther =(($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther)*$taxCharges/100);

			       $totalDiscountOther = (($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther)*$totalTaxChargesOther/100);

			    $netTotalAmt= ($chargePrice+$fixedCharges+$totalCalDiscount+$totalTax);
			  	$netTotalAmtOther= ($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther+$totalTaxOther);


				$updateQry = " UPDATE `tbl_orders` SET  
				`ordCurId`= '".$currencyId."',
				`ordAmt` =  '".$netTotalAmt."',
				`ordCurAmt` = '".$netTotalAmtOther."'
				WHERE id = '".$ordId."' AND account_id = '".$_SESSION['accountId']."'   ";
				mysqli_query($con, $updateQry);
		
		return array($netTotalAmt, $netTotalAmtOther);


}

function getOrdNetTotalUsd($allCurNetTotArr)
{


	if( isset($allCurNetTotArr[0]))
	{
		return $allCurNetTotArr[0];
	}
	return '';

}

function getOrdNetTotalOther($allCurNetTotArr)
{

	if( isset($allCurNetTotArr[1]))
	{
		return $allCurNetTotArr[1];
	}

	return '';

}


function orderNetTotal($supplierId,$id,$qty,$currencyId,$pId){

	global $con;

$sql=" SELECT * FROM  tbl_order_items_temp WHERE supplierId = '".$supplierId."' AND `userId` = '".$id."' AND account_id = '".$_SESSION['accountId']."'  ";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	if ($row) {

		if(  empty($qty) )
		{
				
		}
		else
		{
			$sql = " UPDATE `tbl_order_items_temp` SET `qty` = ".$qty." WHERE `id` = '".$row['id']."'   AND account_id = '".$_SESSION['accountId']."' ";

			mysqli_query($con, $sql);
		}


	}elseif($qty > 0){

	$sql = "INSERT INTO `tbl_order_items_temp` SET
		supplierId = '".$supplierId."'
		,`userId` = '".$id."'
		, `pId` = '".$pId."' 
		, `qty` = '".$qty."'
		, `account_id`= '".$_SESSION['accountId']."'  ";

		mysqli_query($con, $sql);
	}

	


	$sql=" SELECT * FROM  tbl_order_items_temp WHERE supplierId = '".$supplierId."'  AND account_id = '".$_SESSION['accountId']."' AND `userId` = '".$id."'  ";
	$result = mysqli_query($con, $sql);
	while( $row = mysqli_fetch_array($result) )
	{
		$sqlSet=" SELECT * FROM tbl_products WHERE id= '".$row['pId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
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
	$totalChargePriceOther = ($totalChargePrice*$curDet['amt']);
	
	//show here order level fixed/ percent/ tax charges		
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
					$fixedChargesOther += ($fixRow['amt']*$curDet['amt']);
				}

		//start order level item percent charges
					$sqlSet = " SELECT * FROM tbl_order_fee WHERE id IN(".$itemIds.")  AND account_id = '".$_SESSION['accountId']."'  AND feeType = 3  ";
					$resRows = mysqli_query($con, $sqlSet);
					while($perRow = mysqli_fetch_array($resRows))
					{

						$feeName = $perRow['feeName'];
						$perCharges += $perRow['amt'];
						$perChargesOther += $perRow['amt'];
						$perChargeTotal = ($totalChargePrice*$perRow['amt']/100);
						$perChargeTotalOther = ($totalChargePriceOther*$perRow['amt']/100);
					}

                   //start order level item tax charges
						$sqlSetQry = " SELECT * FROM tbl_order_fee WHERE id IN(".$itemIds.") AND account_id = '".$_SESSION['accountId']."'  AND feeType =1 ";
						$resultRows = mysqli_query($con, $sqlSetQry);
					//calculating tax charges
					$totalFixedCharges= $fixedCharges;//calculating total fixed charge
					$totalPerCharges= ($totalChargePrice*$perCharges/100);//calculating total per charge
					$totalFixedChargesOther= $fixedChargesOther;//calculating total fixed charge other
					$totalPerChargesOther= ($totalChargePriceOther*$perCharges/100);//calculating total per charge other

					while( $taxRow = mysqli_fetch_array($resultRows) ) 
					{ 
						$feeName = $taxRow['feeName'];
						$taxCharges += $taxRow['amt'];
						$taxChargesOther += $taxRow['amt'];
						$taxPerChargesTotal = ( ($totalChargePrice+$totalFixedCharges+$totalPerCharges )*$taxRow['amt']/100 );
						$taxPerChargesTotalOther = ( ($totalChargePriceOther+$totalFixedChargesOther+$totalPerChargesOther )*$taxRow['amt']/100 );
						


						 }
					//calculating net value here
					$totalTaxCharges= ( ($totalChargePrice+$totalFixedCharges+$totalPerCharges)*$taxCharges/100);//calculating total tax value 
					$totalTaxChargesOther= ( ($totalChargePriceOther+$totalFixedChargesOther+$totalPerChargesOther)*$taxChargesOther/100);//calculating total tax value other 
					 $netTotalValue= ($totalChargePrice+$totalFixedCharges+$totalPerCharges+$totalTaxCharges);
					$netTotalValueOther= ($totalChargePriceOther+$totalFixedChargesOther+$totalPerChargesOther+$totalTaxChargesOther);
				
				


					}

			}


function receiveOrdTotal($id,$invNo,$orderId, $excludeUpdate=0){
		global $con;
       	//get the sum of all product and item level charges 
       	$sqlSet="SELECT SUM(totalAmt) AS totalAmt, SUM(curAmt) AS totalAmtOther FROM tbl_order_details WHERE ordId='".$orderId."'  		AND account_id = '".$_SESSION['accountId']."' AND (customChargeType='1' OR customChargeType='0')"; 
        $resultSet = mysqli_query($con, $sqlSet);
		$chargeRow = mysqli_fetch_array($resultSet);
		$chargePrice = $chargeRow['totalAmt'];
		$chargePriceOther = $chargeRow['totalAmtOther'];
                  
	  	//Starts order level fixed discount charges
		$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
		INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
		WHERE od.ordId = '".$orderId."'  AND od.account_id = '".$_SESSION['accountId']."' and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

		$ordQry = mysqli_query($con, $sql);
				
		$fixedCharges = 0;
		$fixedChargesOther = 0;
		while($row = mysqli_fetch_array($ordQry))
		{
			$fixedCharges += $row['price'];
			$fixedChargesOther += $row['curAmt'];
		} 
				  
			
	  	//Starts order level per discount charges
		$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
		INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
		WHERE od.ordId = '".$orderId."' AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
		$ordQry = mysqli_query($con, $sql);
				
		$perCharges = 0;
		$perChargesOther = 0;
		while($row = mysqli_fetch_array($ordQry))//show here order level charges
		{
			$perCharges += $row['price'];
			$perChargesOther = $row['curAmt'];

			$calDiscount = ($chargePrice*$row['price']/100);
			$calDiscountOther = ($chargePriceOther*$row['price']/100);
		}
				 
			$totalCalDiscount =($chargePrice*$perCharges/100);//total discount feeType=3
			$totalCalDiscountOther = ($chargePriceOther*$perCharges/100); 
             
		$sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
		INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
		WHERE od.ordId = '".$orderId."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
		$ordQry = mysqli_query($con, $sql);
				
		$taxCharges = 0;
		$totalTaxChargesOther = 0;
		while($row = mysqli_fetch_array($ordQry))//show here order level charges
		{
			$taxCharges += $row['price'];
			$totalTaxChargesOther += $row['curAmt'];

			$calTax = (($chargePrice+ $fixedCharges+$totalCalDiscount)*$row['price']/100);
			$calTaxOther = (($chargePriceOther+ $fixedChargesOther+$totalCalDiscountOther)*$row['price']/100);
				
		} 
				  
				  
		$totalTax =(($chargePrice+$fixedCharges+$totalCalDiscount)*$taxCharges/100);//total tax feeType=1
		$totalTaxOther =(($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther)*$taxCharges/100);

		$netTotalAmt= ($chargePrice+$fixedCharges+$totalCalDiscount+$totalTax);
		$netTotalAmtOther= ($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther+$totalTaxOther);

		if($excludeUpdate == 0)
		{
			$updateQry = " UPDATE `tbl_orders` SET status=2,
			`receivedBy` = '".$id."', 
			`invNo` = '".$invNo."', 
			`ordAmt` =  '".$netTotalAmt."',
			`ordCurAmt` = '".$netTotalAmtOther."',
			`setDateTime` = '".date('Y-m-d h:i:s')."' WHERE id = '".$orderId."'  AND account_id = '".$_SESSION['accountId']."'  ";
			mysqli_query($con, $updateQry);
		}
		else{
			return ['ordAmt'=>$netTotalAmt, 'ordCurAmt'=>$netTotalAmtOther];
		}

}



function requisitionTotalValue($ordId, $exludeUpdate=0){

	global $con;
    //get the sum of all product and item level charges 
	$sqlSet="SELECT SUM(totalAmt) AS totalAmt FROM tbl_order_details WHERE ordId='".$ordId."' AND account_id = '".$_SESSION['accountId']."'  AND (customChargeType='1' OR customChargeType='0')";
	$resultSet = mysqli_query($con, $sqlSet);
	$chargeRow = mysqli_fetch_array($resultSet);	
	$chargePrice=$chargeRow['totalAmt'];
						
	//Starts order level fixed discount charges
	$sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details od 
			INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
			WHERE od.ordId = '".$ordId."' AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

			$ordQry = mysqli_query($con, $sql);

			$fixedCharges = 0;
			$fixedChargesOther = 0;
				while($row = mysqli_fetch_array($ordQry))//show here order level charges
				{
					$fixedCharges += $row['price'];

			    } //Ends order lelvel fixed discount charges



	  		     //Starts order level per discount charges
			      $sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details od 
			      INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
			      WHERE od.ordId = '".$ordId."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
			      $ordQry = mysqli_query($con, $sql);

			      $perCharges = 0;
				while($row = mysqli_fetch_array($ordQry))//show here order level charges
				{
					$perCharges += $row['price'];

					$calDiscount = ($chargePrice*$row['price']/100);

					
			    } //Ends order lelvel per discount charges

			       $totalCalDiscount =($chargePrice*$perCharges/100);//total discount feeType=3


			       $sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details od 
			       INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
			       WHERE od.ordId = '".$ordId."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
			       $ordQry = mysqli_query($con, $sql);


			       $taxCharges = 0;
				while($row = mysqli_fetch_array($ordQry))//show here order level charges
				{
					$taxCharges += $row['price'];
					$calTax = (($chargePrice+ $fixedCharges+$totalCalDiscount)*$row['price']/100);

				
			    } //Ends order lelvel tax discount charges

			       $totalTax =(($chargePrice+$fixedCharges+$totalCalDiscount)*$taxCharges/100);//total tax feeType=1
			       $netTotalAmt= ($chargePrice+$fixedCharges+$totalCalDiscount+$totalTax);

			      
				   if($exludeUpdate == 0)
				   {
						$updateQry = " UPDATE `tbl_orders` SET `ordAmt` = '".$netTotalAmt."' WHERE id = '".$ordId."'  AND account_id = '".$_SESSION['accountId']."'   ";
						mysqli_query($con, $updateQry);
				   }
				   else
				   {
					 return $netTotalAmt;
				   }
}


function resize_image($image_name, $out_image, $w, $h) {
    
		$source_url_parts = pathinfo($image_name);
		$filename = $source_url_parts['filename'];
		$extension = strtolower($source_url_parts['extension']);


                if($extension=='jpg')
                {
				
					// Load image file
					$image = imagecreatefromjpeg($image_name);
					  
					// Use imagescale() function to scale the image
					$img = imagescale( $image, 60, 60 );
					  
					
					
					imagejpeg($img,$out_image);
					
					
                }
                else if($extension == 'png')
                {
                    // Load image file
					$image = imagecreatefrompng($image_name);
					  
					// Use imagescale() function to scale the image
					$img = imagescale( $image, 60, 60 );
					  
					
					
					imagepng($img,$out_image);
                }
                else if($_FILES['logo_image']['type']=='image/gif')
                {
                    // Load image file
					$image = imagecreatefromgif($image_name);
					  
					// Use imagescale() function to scale the image
					$img = imagescale( $image, 60, 60 );
					  
					
					
					imagegif($img,$out_image);
                }
                
}



function getOrderPaymentLink($orderId) {
       global $con;
		
	 	$sql= " SELECT * FROM tbl_payment WHERE orderId='".$orderId."' AND account_id = '".$_SESSION['accountId']."' order by id LIMIT 1   ";
	    $resultSet= mysqli_query($con, $sql);
		$pmntRows= mysqli_fetch_array($resultSet);
		$countRow = mysqli_num_rows($resultSet);
		
		if ( $countRow==0 || empty($pmntRows['paymentStatus']) )
		 { ?>

<div style="width: 24%;">
    <a class="supPayDtl nrmPayDtl"
        href="supplierPaymentDetail.php?page=history&action=pay&orderId=<?php echo $orderId;?>"><?php echo showOtherLangText('Pay') ?></a>
</div>
<div style="border: none; background: transparent; width: 19%;">
    <span class="supPdtl">&nbsp;</span>
</div>

<?php }


		if ($pmntRows['paymentStatus']==1)
		 { 
			?>
<div style="width:24%;">
    <a class="supPayDtl" style="background:#8DB5DA"
        href="supplierPaymentDetail.php?page=history&action=p&orderId=<?php echo $orderId;?>"><?php echo showOtherLangText('Pay') ?></a>
</div>

<div style="width:2%;">
    <strong>|</strong>
</div>
<div style="width:10%; text-align: center;">
    <a class="supPdtl" href="javascript:void(0)" onclick="openSupPaymentPopup('<?php echo $orderId ?>')"><img
            src="./uploads/p-new.svg" alt="p" style="height: 14px;width: auto;"></a>
</div>
<div style="width:2%;">
    <strong>|</strong>
</div>

<?php }

}


function checkActions($getArr) 
{
	global $con;

	if ( $getArr['orderId'] > 0 && $getArr['page']=='history' ) 
		{
	 	
		 	$sql= " SELECT * FROM tbl_payment WHERE orderId='".$getArr['orderId']."' AND account_id = '".$_SESSION['accountId']."' order by id desc LIMIT 1  ";
		 	$resultSet= mysqli_query($con, $sql);
		 	while($pmntRow= mysqli_fetch_array($resultSet))
		 	{ 
		 		
		 		if ($getArr['action']=='p') 
		 			{ ?>
<script>
window.location = 'supplierSuccessPayment.php?orderId=<?php echo $pmntRow['orderId']; ?>';
</script>
<?php
		 	
		    		}
		 	}

		}

}




function getrequisitionPaymentLink($orderId) {
        global $con;

        $selQry = " SELECT * FROM tbl_orders WHERE id = '".$orderId."' AND account_id = '".$_SESSION['accountId']."' ";
        $selRes = mysqli_query($con, $selQry);
        $selResRow = mysqli_fetch_array($selRes);
        $recMemberId = $selResRow['recMemberId'];

        $selQry = " SELECT * FROM tbl_deptusers WHERE id = '".$recMemberId."' AND account_id = '".$_SESSION['accountId']."' ";
        $selRes = mysqli_query($con, $selQry);
        $selResRow = mysqli_fetch_array($selRes);

        if ($selResRow['receive_inv'] > 0)
        {
	
		 	$sql= " SELECT * FROM tbl_req_payment WHERE orderId='".$orderId."' AND account_id = '".$_SESSION['accountId']."' order by id LIMIT 1 ";
		    $resultSet= mysqli_query($con, $sql);
			$pmntRows= mysqli_fetch_array($resultSet);
			$countRow = mysqli_num_rows($resultSet);

			if ( $countRow==0 || (empty($pmntRows['paymentStatus']) && $pmntRows['issueInvoice']==0) )
			 { ?>

<div style="width:24%;">
    <a class="supPayDtl reqInv"
        href="requisitionPaymentDetail.php?page=history&action=pay&orderId=<?php echo $orderId;?>"><?php echo showOtherLangText('Inv') ?></a>
</div>
<div style="border: none; width: 40%;">
    <span class="supPdtl reqInv">&nbsp;</span>
</div>

<?php 
			}

			//$invImg =  ($_SESSION['languageType'] == 1) ? "inv-heb.svg" : "inv-new.svg";


			if ($pmntRows['issueInvoice']==1 && $pmntRows['paymentStatus']!=1)
			{
				
				echo '<div style="width:24%;">
				<a class="supPayDtl reqInv" href="requisitionPaymentDetail.php?page=history&action=pay&orderId='.$orderId.'">'.showOtherLangText('Inv').'</a>
				</div>';
				echo '<div style="width:2%;">
						<strong>|</strong>
					  </div>
					  <div style="width:10%; text-align: center;"><a class="supPdtl" href="javascript:void(0)" onclick="openReqPaymentPopup('.$orderId.')"><img src="./uploads/inv-new.svg" alt="inv" style="height: 14px;width: auto;"></a>
					   </div>';
				echo '<div style="border: none; background: transparent; width: 20%;"><span>&nbsp;</span></div>';
			}


			if ($pmntRows['paymentStatus']==1)
			{ ?>

<div style="width: 24%;"><a class="supPayDtl reqInv" style="background:#8DB5DA"
        href="requisitionPaymentDetail.php?page=history&action=I&orderId=<?php echo $orderId;?>"><?php echo showOtherLangText('Inv') ?></a>
</div>
<div style="width: 1%;"><strong>|</strong></div>
<div style="width: 10%; text-align: center;">
    <a class="supPdtl" href="javascript:void(0)" onclick="openReqPaymentPopup('<?php echo $orderId ?>')"><img
            src="./uploads/inv-new.svg" alt="inv" style="height: 14px;width: auto;"></a>
</div>
<div style="border: none; background: transparent; width: 20%;"><span>&nbsp;</span></div>

<?php 
			}

		}
		else
		{
			echo '<div style="width: 70%;"><span>&nbsp;</span></div>';
		}

	}



function checkRequisitionActions($getArr) {

	 global $con;

	 if ( $getArr['orderId'] > 0 && $getArr['page']=='history' ) {
	 	
	 	$sql= " SELECT * FROM tbl_req_payment WHERE orderId='".$getArr['orderId']."' AND account_id = '".$_SESSION['accountId']."' order by id desc LIMIT 1  ";
	 	$resultSet= mysqli_query($con, $sql);
	 	while($pmntRow= mysqli_fetch_array($resultSet)){ 
	 		
	 	if ($getArr['action']=='I') { ?>
<script>
window.location = 'requisitionSuccessPayment.php?orderId=<?php echo $pmntRow['orderId']; ?>';
</script>
<?php
	 	
	    }


	 	}


	 }

}

//paymentId
function setPaymentId($paymentId){
		global $con;
		
		$paymentIdLen = strlen($paymentId);
		if ($paymentIdLen <= 6) {

		$paymentIdNumb=(6-$paymentIdLen);
		switch ($paymentIdNumb) {
		case "5":
	
	   		$returnPaymentId = "00000".$paymentId;
	  
		break;

		case "4":
			$returnPaymentId = "0000".$paymentId;
	  
		break;

		case "3":
			$returnPaymentId = "000".$paymentId;
	   
		break;

		case "2":
	    	$returnPaymentId = "00".$paymentId;
	  
		break;

		case "1":
			$returnPaymentId = "0".$paymentId;
	
		break;

		default:
     
	  		$returnPaymentId = $paymentId;

		}	       
	}

	return $returnPaymentId;


}

function getinvoiceNumber($invoiceNumber){

	global $con;
	$invoiceNumberLen = strlen($invoiceNumber);

	if ($invoiceNumberLen <= 6) {
		
		$invNumb=(6-$invoiceNumberLen);
		

		switch ($invNumb) {
		case "5":
	
	   		$returninvoiceNumber = "00000".$invoiceNumber;
	  
		break;

		case "4":
			$returninvoiceNumber = "0000".$invoiceNumber;
	  
		break;

		case "3":
			$returninvoiceNumber = "000".$invoiceNumber;
	   
		break;

		case "2":
	    	$returninvoiceNumber = "00".$invoiceNumber;
	  
		break;

		case "1":
			$returninvoiceNumber = "0".$invoiceNumber;
	
		break;

		default:
     
	  		$returninvoiceNumber = $invoiceNumber;

		}	       
	}

	return $returninvoiceNumber;
}


function getRunningProductsQty($accountId)
{
	global $con;
	//Check stock qty with item qty
	$sql = "SELECT SUM(od.qty) totalProQty, od.pId FROM tbl_order_details od INNER JOIN tbl_orders o ON(o.id = od.ordId) AND o.account_id = od.account_id WHERE  o.account_id = '".$accountId."' AND o.ordType = 2 AND o.status != 2 GROUP BY od.pId ";
	$ordQry = mysqli_query($con, $sql);
	
	$runningProQty = [];
	while( $ordRow = mysqli_fetch_array($ordQry) )
	{
		$runningProQty[$ordRow['id']] = $ordRow['totalProQty'];
	}
	return $runningProQty;
}

function addAutoFillReqReport($ordId, $account_id, $recMemberId)
{
	global $con;
	
 	$sql = "SELECT p.barCode, d.* FROM tbl_order_details d inner join tbl_products p ON(p.id=d.pId)
			WHERE d.ordId = '".$ordId."'  AND d.account_id = '".$account_id."' and d.autoFillQty > 0 "; 
	$ordDetailsQry = mysqli_query($con, $sql);

	$date = date('Y-m-d', strtotime('-1 day') );
	while( $ordDetRes = mysqli_fetch_array($ordDetailsQry) )
	{ 

		if ($ordDetRes['qty'] == $ordDetRes['autoFillQty']) {
			$autoFillQty = $ordDetRes['autoFillQty'];
		}
		if ($ordDetRes['qty'] > $ordDetRes['autoFillQty']) {
			$autoFillQty = $ordDetRes['autoFillQty'];
		}
		if ($ordDetRes['qty'] < $ordDetRes['autoFillQty']) {
			$autoFillQty = $ordDetRes['qty'];
		}

		
			
		$sqlSet = " SELECT * FROM tbl_revenue_center_departments WHERE deptId = '".$recMemberId."' AND account_id = '".$account_id."' ";
		$result = mysqli_query($con, $sqlSet);
		$resultSet = mysqli_fetch_array($result);
		$outLetId = $resultSet['id'];
	
		$sql = "SELECT * FROM tbl_daily_import_items  WHERE outLetId = '".$outLetId."'  AND account_id = '".$account_id."'  AND importedOn = '".$date."' AND barCode = '".$ordDetRes['barCode']."'   ";
		$qry = mysqli_query($con, $sql);
		$checkRow = mysqli_fetch_array($qry);
		
		if($checkRow)
		{
			$qry = " UPDATE tbl_daily_import_items SET  
			requisition =(requisition + '".$autoFillQty."') 	
			WHERE id = '".$checkRow['id']."'  AND account_id = '".$account_id."'  ";
			mysqli_query($con, $qry);
		}
		
	}

}

//////////////////////////////////////////////////////
function section_permission($userId, $accountId){

	global $con;

	$sql = " SELECT dsp.designation_section_list_id designation_section_list_id , u.* FROM tbl_user u
	INNER JOIN tbl_designation_section_permission dsp ON(u.designation_id = dsp.designation_id) AND u.account_id = dsp.account_id WHERE u.id = '".$userId."' AND u.account_id = '".$accountId."' ";
	$userRes = mysqli_query($con, $sql);
	$sectionPermissionByDesignationId = [];
	while($userRow = mysqli_fetch_array($userRes))
	{
		$sectionPermissionByDesignationId[] = $userRow['designation_section_list_id'];
	}

	return $sectionPermissionByDesignationId;

}


function get_supplier_permission($designation_id, $accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND type = 'order_supplier' AND designation_section_permission_id = '1' AND account_id = '".$accountId."' ";
	$result = mysqli_query($con, $sql);

	return $resultRow = mysqli_fetch_array($result);
}

function get_access_payment_permission($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND type = 'access_payment' AND designation_section_permission_id = '1' AND account_id = '".$_SESSION['accountId']."' ";
	$subSestionPermissionRes = mysqli_query($con, $sql);
	$subSestionPaymentPermissionRow = mysqli_fetch_array($subSestionPermissionRes);

	return $subSestionPaymentPermissionRow;
}


function get_member_permission($designation_id, $accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND type = 'member' AND designation_section_permission_id = '2' AND account_id = '".$accountId."' ";
	$result = mysqli_query($con, $sql);

	return $resultRow = mysqli_fetch_array($result);
}

function get_access_invoice_permission($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND type = 'access_invoice' AND designation_section_permission_id = '2' AND account_id = '".$accountId."' ";
	$subSestionPermissionResInv = mysqli_query($con, $sql);
	$subSestionPermissionInvoiceRow = mysqli_fetch_array($subSestionPermissionResInv);

	return $subSestionPermissionInvoiceRow;
}

//Strart Getting Running Orders Action Like (Edit Order, Edit Requisition, Receive Order, Assign Order Of Both OrderType, Delete Order Of Both OrderType) 
function get_editOrder_permission($designation_id, $accountId, $orderId, $orderReceivedByMobUser=0){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '3' AND account_id = '".$accountId."' AND type = 'edit_order' ";
	$editOrderPermissionRes = mysqli_query($con, $sql);
	$editOrderPermissionRow = mysqli_fetch_array($editOrderPermissionRes);

	if ($editOrderPermissionRow['type_id'] == 1 && $orderReceivedByMobUser == 0) 
	{
		echo '<a href="editOrder.php?orderId='.$orderId.'" class="glyphicon glyphicon-list-alt"  style="color:#808080; width: 8%;display: inline-block;text-align: center;" title="'.showOtherLangText('Edit').'"></a>|';
	}
	else
	{
		echo '<span style="display:inline-block;width: 9%;">&nbsp;</span>';
	}

}

function get_editRequisition_permission($designation_id,$accountId,$orderId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND account_id = '".$accountId."' AND designation_section_permission_id = '3' AND type = 'edit_requisition' ";
	$editOrderPermissionRes = mysqli_query($con, $sql);
	$editOrderPermissionRow = mysqli_fetch_array($editOrderPermissionRes);

	if ($editOrderPermissionRow['type_id'] == 1) 
	{
		echo '<a href="editRecusation.php?orderId='.$orderId.'" class="glyphicon glyphicon-list-alt" style="color:#808080; width: 8%;display: inline-block;text-align: center;" title="'.showOtherLangText('Edit').'"></a>|';
	}
	else
	{
		echo '<span style="display:inline-block;width: 9%;">&nbsp;</span>';
	}

}

function get_receiveOrder_permission($designation_id, $accountId, $orderId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '3' AND account_id = '".$accountId."' AND type = 'receive_order' ";
	$editOrderPermissionRes = mysqli_query($con, $sql);
	$editOrderPermissionRow = mysqli_fetch_array($editOrderPermissionRes);

	if ($editOrderPermissionRow['type_id'] == 1) 
	{
		echo '<a style="width: 22%;display: inline-block;text-align: center;" href="receiveOrder.php?orderId='.$orderId.'">'.showOtherLangText('Receive').'</a>|';
	}
	else
	{
		echo '<span style="display: inline-block;width: 23%;">&nbsp;</span>';
	}

}

function get_assignOrder_permission($designation_id, $accountId, $orderId, $orderType){

	global $con;

	$sql = " SELECT * FROM 
	tbl_designation_sub_section_permission WHERE
	designation_id = '".$designation_id."' AND designation_section_permission_id = '3' AND account_id ='".$accountId."' AND type = 'assign_mobile' ";
	$editOrderPermissionRes = mysqli_query($con, $sql);
	$editOrderPermissionRow = mysqli_fetch_array($editOrderPermissionRes);

	if ($editOrderPermissionRow['type_id'] == 1) 
	{
		
		if ($orderType == 1) 
		{
			echo '<a style="width: 21%;display: inline-block;text-align: center;" href="javascript:void(0)" class="assignBtn" onClick="openAssignPopup('.$orderType.', '.$orderId.')" title="'.showOtherLangText('Assign Order To Mobile Users').'">'.showOtherLangText('Assign').'</a>|';
		}
		
		if ($orderType == 2) {
			
			echo '|<a style="width: 21%;display: inline-block;text-align: center;" href="javascript:void(0)" class="assignBtn" onClick="openAssignPopup('.$orderType.', '.$orderId.')" title="'.showOtherLangText('Assign Order To Mobile Users').'">'.showOtherLangText('Assign').'</a>|';
		}	
	}
	else
	{
		if ($orderType == 1) {

			echo '<span style="display:inline-block; width: 21%;">&nbsp;</span>';

		}elseif ($orderType == 2) {
			
			echo '<span style="display:inline-block; width: 22%;">&nbsp;</span>';
		}
		
	}
	
}

function get_issueOut_permission($designation_id, $accountId, $orderId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '3' AND account_id = '".$accountId."' AND type = 'issue_out' ";
	$issueOutPermissionRes = mysqli_query($con, $sql);
	$issueOutPermissionRow = mysqli_fetch_array($issueOutPermissionRes);

	if ($issueOutPermissionRow['type_id'] == 1) 
	{
		?>
<a style="width: 21%;display: inline-block;text-align: center;" href="javascript:void(0)" class="issueOutBtn"
    onclick="cnfIssueOut(<?php echo $orderId ?>)"><?php echo showOtherLangText('Issue Out') ?></a>
<?php
	}
	else
	{
	    echo '<span style="display:inline-block; width: 21%;">&nbsp;</span>';
	}
}

function get_deleteOrder_permission($designation_id, $accountId, $orderId, $orderType){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '3' AND account_id = '".$accountId."' AND type = 'access_delete_runningtask' ";
	$editOrderPermissionRes = mysqli_query($con, $sql);
	$editOrderPermissionRow = mysqli_fetch_array($editOrderPermissionRes);

	if ($editOrderPermissionRow['type_id'] == 1) 
	{
		if ($orderType == 1) {
			?>
<!-- | <a title="<?php echo showOtherLangText('Delete') ?>" href="runningOrders.php?canId=<?php echo $orderId;?>&type=1" style="color:#808080;width: 8%;display: inline-block;text-align: center;" class="glyphicon glyphicon-trash" onClick="return confirm('<?php echo showOtherLangText('Are you sure to cancel this Order?') ?>')"></a> -->

| <a title="<?php echo showOtherLangText('Delete') ?>" href="javascript:void(0)"
    style="color:#808080;width: 8%;display: inline-block;text-align: center;" class="glyphicon glyphicon-trash"
    onClick="getDelNumb('<?php echo $orderId;?>', '1');"></a>

<?php
		}
		
		if ($orderType == 2) {
			?>
<!-- <a href="runningOrders.php?canId=<?php echo $orderId;?>&type=2"  class="glyphicon 
				glyphicon-trash" title="<?php echo showOtherLangText('Delete') ?>"
				style="color:#808080;width: 8%;display:
				inline-block;text-align: center;"
				onClick="return confirm('<?php echo showOtherLangText('Are you sure to cancel this Requisition?') ?>')"></a> -->

<a title="<?php echo showOtherLangText('Delete') ?>" href="javascript:void(0)"
    style="color:#808080;width: 8%;display: inline-block;text-align: center;" class="glyphicon glyphicon-trash"
    onClick="getDelNumb('<?php echo $orderId;?>', '2');"></a>

<?php
		}		

		if ($orderType == 3) {
			?>
<!-- <a href="runningOrders.php?canId=<?php echo $orderId;?>&type=3"  class="glyphicon 
				glyphicon-trash" title="<?php echo showOtherLangText('Delete') ?>"
				style="color:#808080;width: 8%;display:
				inline-block;text-align: center;"
				onClick="return confirm('<?php echo showOtherLangText('Are you sure to cancel this Requisition?') ?>')"></a> -->

<a title="<?php echo showOtherLangText('Delete') ?>" href="javascript:void(0)"
    style="color:#808080;width: 8%;display: inline-block;text-align: center;" class="glyphicon glyphicon-trash"
    onClick="getDelNumb('<?php echo $orderId;?>', '3');"></a>

<?php
		}	

	}
	else
	{
		echo '<span style="width: 8%;">&nbsp;</span>';
	}

}//END Of Getting Running Orders Action Like (Edit Order, Edit Requisition, Receive Order, Assign Order Of Both OrderType, Delete Order Of Both OrderType)

function access_history_xcl_pdf_file($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '4' AND account_id = '".$accountId."' AND type = 'access_history_xcl_pdf' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}


function access_history_accounts_detail($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '4' AND account_id = '".$accountId."' AND type = 'access_accounts_detail' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}


function access_delete_history_file($designation_id,$accountId,$orderId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '4' AND account_id = '".$accountId."' AND type = 'access_delete_history' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	if ($accessPermissionRow['type_id'] == 1) 
	{

		?>
<div style="width:2%;">
    <strong>|</strong>
</div>
<div style="width: 10%; text-align: center;">
    <!-- <a href="history.php?delOrderId=<?php echo $orderId;?>#del"
	        onClick="return confirm('<?php echo showOtherLangText('Are you sure to Delete this Record?') ?>');" style="color:#000 !important;" class="glyphicon glyphicon-trash" title="<?php echo showOtherLangText('Delete') ?>"></a> -->

    <a href="javascript:void(0)" onClick="getDelNumb('<?php echo $orderId;?>');" style="color:#000 !important;"
        class="glyphicon glyphicon-trash" title="<?php echo showOtherLangText('Delete') ?>"></a>

</div>

<?php
	}
	else
	{
		?>
<span style="width: 11%;">&nbsp;</span>
<?php
	}

}

function get_store_permission($designation_id, $accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND type = 'stock' AND designation_section_permission_id = '5' AND account_id = '".$accountId."' ";
	$result = mysqli_query($con, $sql);

	return $resultRow = mysqli_fetch_array($result);
}

function access_raw_item_convert($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '5' AND account_id = '".$accountId."' AND type = 'convert_raw_items' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	if ($accessPermissionRow['type_id'] == 1) 
	{

		?>
<a href="javascript:void(0);" onClick="openPopup();" class="btn btn-primary stay-btn"
    style="margin-top: 2px; width: 160px;"><?php echo showOtherLangText('Convert Raw Items') ?></a><br>

<!-- <a href="javascript:void(0);" onClick="convertRawPopup();" class="btn btn-primary stay-btn cnvtRawItm" style="margin-top: 2px; width: 160px;"><?php echo showOtherLangText('Convert Raw Items') ?></a><br> -->

<?php
	}

}


function access_view_stockTake($designation_id,$accountId,$filterByStorage){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '5' AND account_id = '".$accountId."' AND type = 'view_stock_take' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	if ($accessPermissionRow['type_id'] == 1) 
	{

		
	    if(isset($filterByStorage) && $filterByStorage > 0)
        {
            ?>
<a href="viewMobileStockTake.php?stockTakeId=<?php echo $filterByStorage ?>" class="btn btn-primary stay-btn"
    style="width: 160px;margin-top:2px; "><?php echo showOtherLangText('View Stock Take') ?></a>

<?php 

        }
        else
        {
			?>
<input type="button" id="btnFileUpload" disabled="disabled" class="btn" style="width: 160px;margin-top:2px;"
    value="<?php echo showOtherLangText('View Stock Take') ?>" /><br>

<?php
		}

	}

}


function access_import_stockTake($designation_id,$accountId,$filterByStorage, $rightSideLanguage){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '5' AND account_id = '".$accountId."' AND type = 'import_stock_take' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	if ($accessPermissionRow['type_id'] == 1) 
	{

	    if(isset($filterByStorage) && $filterByStorage > 0)
	    {
	    	?>


<span style="font-size:30px;color:blue">

    <a href="<?php echo $rightSideLanguage == 1 ? 'excelSampleFile/hebrew/importStockTakeList-hebrew-lang.xlsx' : 'excelSampleFile/english/importStockTakeList-english-lang.xlsx' ?>"
        target="_blank" title="<?php echo showOtherLangText('Download sample file') ?>">&#8595;</a>

</span>&nbsp;

<input type="button" id="btnFileUpload" class="btn btn-primary stay-btn" style="width: 160px;"
    value="<?php echo showOtherLangText('Import Stock Take') ?>" />

<input type="file" id="uploadFile" name="uploadFile" style="display:none"><br>

<?php  
        }
        else
        {
        	?>
<span style="font-size:22px;color:#AAB2BD; margin-left: 8px;">&#8595;</span>&nbsp;
<input type="button" id="btnFileUpload" disabled="disabled" class="btn" style="width: 160px;color:black;"
    value="<?php echo showOtherLangText('Import Stock Take') ?>" /><br>

<?php
		}

	}

}


function access_stock_xcl_pdf_file($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '5' AND account_id = '".$accountId."' AND type = 'access_stock_xcl_pdf' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}


function access_guest($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '7' AND account_id = '".$accountId."' AND type = 'guest_no' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}


function access_ezee_Data($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '7' AND account_id = '".$accountId."' AND type = 'ezee_data' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}


function access_import_revenueCenter_data($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '7' AND account_id = '".$accountId."' AND type = 'reImport_data' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}

function access_account_setup($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '8' AND account_id = '".$accountId."' AND type = 'account_setup' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}

function access_designation($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '8' AND account_id = '".$accountId."' AND type = 'designation' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}

function access_user($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '8' AND account_id = '".$accountId."' AND type = 'user' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}

function access_outlet($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '8' AND account_id = '".$accountId."' AND type = 'outlet' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}

function access_supplier($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '8' AND account_id = '".$accountId."' AND type = 'supplier' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}

function access_revenue_center($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '8' AND account_id = '".$accountId."' AND type = 'revenue_center' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}

function access_physical_storage($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '8' AND account_id = '".$accountId."' AND type = 'physical_storage' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}


function access_department_type($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '8' AND account_id = '".$accountId."' AND type = 'department_type' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}

function access_category($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '8' AND account_id = '".$accountId."' AND type = 'category' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}

function access_unit($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '8' AND account_id = '".$accountId."' AND type = 'unit' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}

function access_item_manager($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '8' AND account_id = '".$accountId."' AND type = 'item_manager' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}

function access_currency($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '8' AND account_id = '".$accountId."' AND type = 'currency' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}

function access_account($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '8' AND account_id = '".$accountId."' AND type = 'account' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}

function access_service_item($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '8' AND account_id = '".$accountId."' AND type = 'service_item' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}


function access_additional_fee($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND designation_section_permission_id = '8' AND account_id = '".$accountId."' AND type = 'additional_fee' ";
	$accessPermission = mysqli_query($con, $sql);
	$accessPermissionRow = mysqli_fetch_array($accessPermission);

	return $accessPermissionRow;

}


function permission_denied_for_section_pages($designation_id,$accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_section_permission WHERE designation_id = '".$designation_id."' AND account_id = '".$accountId."' ";
	$result = mysqli_query($con, $sql);
	$pagePermissionByDesignationId = [];
	while($resultRow = mysqli_fetch_array($result))
	{
		$pagePermissionByDesignationId[] = $resultRow['designation_section_list_id'];
	}

	return $pagePermissionByDesignationId;

}

//Start Getting All Status of Order Type (ORDER)
function get_all_type_of_status_of_orderType($orderType,$orderId,$orderStatus,$checkOrdAssing,$timeTrackDet){

	Global $con;

	if ($orderType == 1) {//order

		if ($orderStatus == 1 && !$checkOrdAssing && !$timeTrackDet)
		{
			?>

<span style="color:red;">
    <strong><?php echo showOtherLangText('Ordered') ?></strong>
</span>

<?php

		}
		elseif ($orderStatus == 1 && $checkOrdAssing && !$timeTrackDet)
		{
			?>

<span>
    <!-- <strong><?php echo showOtherLangText('Assigned') ?></strong> -->
    <strong>
        <a href="javascript:void(0)" style="color:green;" class="assignedBtn"
            onClick="openAssignPopup('<?php echo $orderType ?>', '<?php echo $orderId ?>')"
            title="<?php echo showOtherLangText('Assign / Unassigned Mobile Users') ?>"><?php echo showOtherLangText('Assigned') ?></a>
    </strong>
</span>

<?php

		}
		elseif($checkOrdAssing && !$timeTrackDet )
		{
			?>

<span>
    <!-- <strong><?php echo showOtherLangText('Assigned') ?></strong> -->
    <strong>
        <a href="javascript:void(0)" style="color:green;" class="assignedBtn"
            onClick="openAssignPopup('<?php echo $orderType ?>', '<?php echo $orderId ?>')"
            title="<?php echo showOtherLangText('Assign / Unassigned Mobile Users') ?>"><?php echo showOtherLangText('Assigned') ?></a>
    </strong>
</span>

<?php

		}
		elseif($timeTrackDet)
		{
			?>

<span style="color:red;" title="<?php echo showOtherLangText('Mobile Operator Stock Take Done') ?>">
    <strong><?php echo showOtherLangText('Done') ?></strong>
</span>

<?php

		}
		else
		{
			?>
<span style="color:green;">
    <strong><?php echo showOtherLangText('Temp Order') ?></strong>
</span>

<?php

		}

	}
}

//Start Getting All Status of Requisition Type (Requisition)
function get_all_type_of_status_of_requisitionType($orderType,$orderId,$orderStatus,$checkOrdAssing,$timeTrackDet){

	Global $con;

	if($orderType == 2)//recusition
	{
		if ($orderStatus == 3 && !$checkOrdAssing && !$timeTrackDet)
		{
			?>

<span style="color:red;"><strong><?php echo showOtherLangText('Requested') ?></strong></span>

<?php

		}
		elseif($orderStatus == 3 && $checkOrdAssing && !$timeTrackDet)
		{
			?>

<span>
    <!-- <strong><?php echo showOtherLangText('Assigned') ?></strong> -->
    <strong>
        <a href="javascript:void(0)" style="color:green;" class="assignedBtn"
            onClick="openAssignPopup('<?php echo $orderType ?>', '<?php echo $orderId ?>')"
            title="<?php echo showOtherLangText('Assign / Unassigned Mobile Users') ?>"><?php echo showOtherLangText('Assigned') ?></a>
    </strong>
</span>

<?php

		}
		elseif($checkOrdAssing && !$timeTrackDet)
		{
			?>

<span>
    <!-- <strong><?php echo showOtherLangText('Assigned') ?></strong> -->
    <strong>
        <a href="javascript:void(0)" style="color:green;" class="assignedBtn"
            onClick="openAssignPopup('<?php echo $orderType ?>', '<?php echo $orderId ?>')"
            title="<?php echo showOtherLangText('Assign / Unassigned Mobile Users') ?>"><?php echo showOtherLangText('Assigned') ?></a>
    </strong>
</span>

<?php

		}
		elseif($timeTrackDet) 
		{
			?>

<span style="color:red;"
    title="<?php echo showOtherLangText('Mobile Operator Stock Take Done') ?>"><strong><?php echo showOtherLangText('Done') ?></strong></span>

<?php

		}
		else
		{
			?>

<span style="color:green;">
    <strong><?php echo showOtherLangText('Temp Requisition') ?></strong>
</span>

<?php
		}
		
	}
}

function get_all_type_of_status_of_new_stockTake($orderType,$orderStatus){

	Global $con;

	if ($orderType == 3) 
	{
		?>
<span style="color:green;">
    <strong><?php echo showOtherLangText('Schedule') ?></strong>
</span>
<?php
	}
}


//Start Getting All Order Action Of Order Type = 1 (ORDER)
function get_all_order_action_of_order_type($orderType,$orderStatus,$checkOrdAssing,$timeTrackDet,$orderId,$designation_id,$accountId, $orderReceivedByMobUser=0){

	global $con;

	if ($orderType == 1) {//order

	$memRes['name'] = '';

		if ($orderStatus == 1 && !$checkOrdAssing && !$timeTrackDet) 
		{
			get_receiveOrder_permission($designation_id,$accountId,$orderId);
			
			get_assignOrder_permission($designation_id,$accountId,$orderId,$orderType);
		
			get_editOrder_permission($designation_id,$accountId,$orderId, $orderReceivedByMobUser);
			
			
		}elseif ($orderStatus == 1 && $checkOrdAssing && !$timeTrackDet)
		{

			get_receiveOrder_permission($designation_id,$accountId,$orderId);
			?>
<span style="width: 20%;display: inline-block;text-align: center;">&nbsp;</span>
<?php
			
			
				get_editOrder_permission($designation_id,$accountId,$orderId, $orderReceivedByMobUser);
			

		}elseif($timeTrackDet) {

			get_receiveOrder_permission($designation_id,$accountId,$orderId);
			?>
<span style="width: 20%;display: inline-block;text-align: center;">&nbsp;</span>
<?php 
			
				get_editOrder_permission($designation_id,$accountId,$orderId, $orderReceivedByMobUser);
			

		}
		else
		{
			?>

<a style="width: 20%;display: inline-block;text-align: center;"
    href="runningOrders.php?orderId=<?php echo $orderId ?>&confirm=1"><?php echo showOtherLangText('Confirm') ?></a>

<span style="width: 23%;display: inline-block;text-align: center;">&nbsp;</span>

<?php
			
				get_editOrder_permission($designation_id,$accountId,$orderId, $orderReceivedByMobUser);
			

		}
	}
}


//Start Getting All Order Action Of Order Type = 2 (Requisition)
function get_all_order_action_of_requisition_type($orderType,$orderStatus,$checkOrdAssing,$timeTrackDet,$orderId,$designation_id,$accountId)
{

	global $con;

	if($orderType == 2)//recusition
	{

		if ($orderStatus == 3 && !$checkOrdAssing && !$timeTrackDet) 
		{

			get_issueOut_permission($designation_id, $accountId, $orderId);
			?>
<!-- <a style="width: 21%;display: inline-block;text-align: center;" href="javascript:void(0)" class="issueOutBtn" onclick="cnfIssueOut(<?php echo $orderId ?>)"><?php echo showOtherLangText('Issue Out') ?></a> -->
<?Php

			get_assignOrder_permission($designation_id,$accountId,$orderId,$orderType); 

			get_editRequisition_permission($designation_id,$accountId,$orderId);

		}
		elseif($orderStatus == 3 && $checkOrdAssing && !$timeTrackDet)
		{

			get_issueOut_permission($designation_id, $accountId, $orderId);
			?>
<!-- <a style="width: 20%;display: inline-block;text-align: center;" href="javascript:void(0)" class="issueOutBtn" onclick="cnfIssueOut(<?php echo $orderId ?>)"><?php echo showOtherLangText('Issue Out') ?></a> -->

<span style="width: 23%;display: inline-block;text-align: center;">&nbsp;</span>

<?php

			get_editRequisition_permission($designation_id,$accountId,$orderId);

		}
		elseif($timeTrackDet) 
		{
			get_issueOut_permission($designation_id, $accountId, $orderId);
			?>
<!-- <a style="width: 20%;display: inline-block;text-align: center;" href="javascript:void(0)" class="issueOutBtn" onclick="cnfIssueOut(<?php echo $orderId ?>)"><?php echo showOtherLangText('Issue Out') ?></a> -->

<span style="width: 23%;display: inline-block;text-align: center;">&nbsp;</span>

<?php

			get_editRequisition_permission($designation_id,$accountId,$orderId);


		}
		else
		{
			?>
<a style="width: 20%;display: inline-block;text-align: center;"
    href="runningOrders.php?orderId=<?php echo $orderId ?>&confirm=3"><?php echo showOtherLangText('Confirm') ?></a>

<span style="width: 23%;display: inline-block;text-align: center;">&nbsp;</span>

<?php

			get_editRequisition_permission($designation_id,$accountId,$orderId);

		}

	}
}

function insert($tbl, $fieldsArr)
{
	global $con;
	
	$setValues = '';
	foreach($fieldsArr as $fieldName=>$value)
	{
		$setValues .= $fieldName .'='. "'$value'" .',';
	}
	
	$setValues = rtrim($setValues, ',');
	
	$sql = " INSERT INTO ".$tbl." SET ".$setValues.";" ;
	
	mysqli_query($con, $sql);
	
}

function updateTable($tbl, $fieldsArr, $whereArr)
{
	global $con;
	
	$setValues = '';
	$whereCond = '';
	foreach($fieldsArr as $fieldName=>$value)
	{
		$setValues .= $fieldName .'='. "'$value'" .',';
	}
	
	$setValues = rtrim($setValues, ',');

	foreach($whereArr as $whereFieldName=>$whereFieldValue)
	{
		$whereCond .= $whereFieldName .'='. "'$whereFieldValue'" .' AND ';
	}
	
	$whereCond = rtrim($whereCond, ' AND ');
	
	$sql = " UPDATE  ".$tbl." SET ".$setValues." WHERE ".$whereCond.";" ;
	
	mysqli_query($con, $sql);
	 
}

function delete($tbl, $whereArr)
{
	global $con;

	$whereCond = '';
	foreach($whereArr as $whereFieldName=>$whereFieldValue)
	{
		$whereCond .= $whereFieldName .'='. "'$whereFieldValue'" .' AND ';
	}

	$whereCond = rtrim($whereCond, ' AND ');
	
	$sql = " DELETE FROM  ".$tbl."  WHERE ".$whereCond.";" ;
	
	mysqli_query($con, $sql);
	
}

function fetchTableDetailsWithoutCond($tbl)
{
	global $con;
	
	$sql = " SELECT * FROM  ".$tbl.";" ;
	
	return  mysqli_query($con, $sql);
	
}

function fetchQry($tbl, $whereArr)
{
	global $con;

	$whereCond = '';
	foreach($whereArr as $whereFieldName=>$whereFieldValue)
	{
		$whereCond .= $whereFieldName .'='. "'$whereFieldValue'" .' AND ';
	}

	$whereCond = rtrim($whereCond, ' AND ');
	
	$sql = " SELECT * FROM  ".$tbl."  WHERE ".$whereCond.";" ;
	
	return mysqli_query($con, $sql);
}

function fetchTableDetails($tbl, $whereArr)
{
	global $con;

	$whereCond = '';
	foreach($whereArr as $whereFieldName=>$whereFieldValue)
	{
		$whereCond .= $whereFieldName .'='. "'$whereFieldValue'" .' AND ';
	}

	$whereCond = rtrim($whereCond, ' AND ');
	
	$sql = " SELECT * FROM  ".$tbl."  WHERE ".$whereCond.";" ;
	
	$qry =  mysqli_query($con, $sql);
	
	return mysqli_fetch_array($qry);
}

function getLangType($langId){

	global $con;

    $sql = "SELECT * FROM tbl_language WHERE id = '".$langId."' ";
    $sqlQry = mysqli_query($con, $sql);
    $langRow = mysqli_fetch_array($sqlQry);
    $_SESSION['languageType'] = $langRow['language_type'];

    return $_SESSION['languageType'];

}

function getOtherText($language_id)
{

	global $con;

	$sql = " SELECT l.language_name, l.language_type, smt.main_text_key, solt.* FROM tbl_language l

	INNER JOIN tbl_site_other_language_text solt 
			ON(l.id=solt.other_language_id)
	INNER JOIN tbl_site_main_text smt 
			ON(smt.id=solt.site_main_text_id) 
	WHERE other_language_id = '".$language_id."' ";

	$sqlQry = mysqli_query($con, $sql);
	$getOtherText = [];
	while($sqlResult = mysqli_fetch_array($sqlQry))
	{
		$mainTextKey = strtolower($sqlResult['main_text_key']);
		$getOtherText[$mainTextKey] = $sqlResult['other_language_text'];
	}
	return $getOtherText;
}


if( isset($_SESSION['language_id']) )
{
	$getOtherTextArr = getOtherText($_SESSION['language_id']);
}

function showOtherLangText($mainLangText){

	global $con, $getOtherTextArr;
	$mainLangText = strtolower($mainLangText);
	$mainLangKey = str_replace(' ','_', $mainLangText);

	return isset($getOtherTextArr[$mainLangKey]) && $getOtherTextArr[$mainLangKey] ? $getOtherTextArr[$mainLangKey] : ucfirst(str_replace('_',' ', $mainLangKey));
}


//give permission to mobile user of each individual pages to access

// function checkmobUserAccessPer($accountId){

// 	global $con;

// 	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE AND account_id = '".$accountId."' ";
// 	$permissionRes = mysqli_query($con, $sql);


// }

function mobUserPermission($designation_id, $accountId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type IN('storage_stocktaking','outlet_stocktaking','receiving_order','issuing_out','bar_control_sales' ) AND designation_id = '".$designation_id."' AND account_id = '".$accountId."' ";
	$permissionRes = mysqli_query($con, $sql);

	$checkPermission = [];
	while($permissionRow = mysqli_fetch_array($permissionRes))
	{
	   $checkPermission[$permissionRow['type']] = $permissionRow['type_id'];
	}

	return $checkPermission;
}

if (isset($_SESSION['accountId'])) 
{
	$checkPermissionArr = mobUserPermission($_SESSION['designation_id'], $_SESSION['accountId']);
}
function checkCurPage(){

	global $con, $checkPermissionArr;

	$curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

	$stockTakeArr = ['stockTake1.php','stockTake2.php','stockTake3.php','stockTake4.php','stockTake5.php'];
	
	if (in_array($curPageName, $stockTakeArr)) {
		
		$currentpage = 'stockTake.php';

		if ($currentpage == 'stockTake.php' && $checkPermissionArr['storage_stocktaking'] == 0) 
		{
			return 1;
		}
	}


	$outLetStockTakeArr = ['outLetStockTake1.php','outLetStockTake2.php','outLetStockTake3.php','outLetStockTake4.php','outLetStockTake5.php','outLetStockTake6.php'];
	if (in_array($curPageName, $outLetStockTakeArr)) {
		
		$currentpage = 'outLetStockTake.php';

		if ($currentpage == 'outLetStockTake.php' && $checkPermissionArr['outlet_stocktaking'] == 0) 
		{
		   return 2;
		}
	}

	$receiveOrderArr = ['receiveOrder1.php','receiveOrder2.php','receiveOrder3.php','receiveOrder4.php','receiveOrder5.php','receiveOrder6.php'];
	if (in_array($curPageName, $receiveOrderArr)) {
		
		$currentpage = 'receiveOrder.php';

		if ($currentpage == 'receiveOrder.php' && $checkPermissionArr['receiving_order'] == 0) 
		{
		   return 3;
		}
	}


	$issuingOutArr = ['issueOutOrder1.php','issueOutOrder2.php','issueOutOrder3.php','issueOutOrder4.php','issueOutOrder5.php','issueOutOrder6.php'];
	if (in_array($curPageName, $issuingOutArr)) {
		
		$currentpage = 'issueOutOrder.php';

		if ($currentpage == 'issueOutOrder.php' && $checkPermissionArr['issuing_out'] == 0) 
		{
		   return 4;
		}
	}

	$barStockTakeArr = ['barStockTake1.php','barStockTake2.php','barStockTake3.php','barStockTake4.php','barStockTake5.php'];
	if (in_array($curPageName, $barStockTakeArr)) {
		
		$currentpage = 'barStockTake.php';

		if ($currentpage == 'barStockTake.php' && $checkPermissionArr['bar_control_sales'] == 0) 
		{
		   return 5;
		}
	}

}


function get_column_permission($designation_id, $accountId, $sectionId){

	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."'  AND designation_section_permission_id = '".$sectionId."' AND account_id = '".$accountId."' ";
	$subSestionPermissionResInv = mysqli_query($con, $sql);
	
	
	$typesPerArr = [];
	while($subSestionPermissionInvoiceRow = mysqli_fetch_array($subSestionPermissionResInv) )
	{
		$type = $subSestionPermissionInvoiceRow['type'];
		$typesPerArr[$type] = $subSestionPermissionInvoiceRow['type_id'];
	}
	
	return $typesPerArr;

}






////////////////////// Superadmin related function goes here.
$accountTypes = ['0'=>'Free','1'=>'Monthly','2'=>'Yearly'];
$paymentTypes = ['0'=>'Free','1'=>'Online Fix','2'=>'Bank Transfer'];
$status = ['0'=>'Deactive','1'=>'Active'];


function getAllLanguages(){
	global $con;
    $sql = "SELECT id,language_name FROM tbl_language ORDER BY language_name";
    $execute = mysqli_query($con, $sql);
    $languages = [];
    while ($row = mysqli_fetch_assoc($execute)) {
       $languages[] = $row;
    }
    return $languages;
}
function getAllCurrency(){
	global $con;
    $sql = "SELECT id,currency,amt,status FROM tbl_currency ORDER BY currency";
    $execute = mysqli_query($con, $sql);
    $currencies = [];
    while ($row = mysqli_fetch_assoc($execute)) {
       $currencies[] = $row;
    }
    return $currencies;
}
function getAllbusiness(){
	global $con;
    $sql = "SELECT id,name,status FROM tbl_business ORDER BY name";
    $execute = mysqli_query($con, $sql);
    $business = [];
    while ($row = mysqli_fetch_assoc($execute)) {
       $business[] = $row;
    }
    return $business;
}

function getAllclients(){
	global $con;
    $sql = "SELECT * FROM tbl_client";
    $execute = mysqli_query($con, $sql);
    $clients = [];
    while ($row = mysqli_fetch_assoc($execute)) {
       $clients[] = $row;
    }
    return $clients;
}

function getAllOwnerInfo(){
	global $con;
    $sql = "SELECT * FROM tbl_user WHERE isOwner = '1' ";
    $execute = mysqli_query($con, $sql);
    $ownerInfo = [];
    while ($row = mysqli_fetch_assoc($execute)) {
       $ownerInfo[] = $row;
    }
    return $ownerInfo;
}
function getAllcountries(){
	global $con;
    $sql = "SELECT id,name FROM tbl_country";
    $execute = mysqli_query($con, $sql);
    $countries = [];
    while ($row = mysqli_fetch_assoc($execute)) {
       $countries[] = $row;
    }
    return $countries;
}

function getAllsections(){
	global $con;
    $sql = "SELECT id,name,is_default,status FROM tbl_sections";
    $execute = mysqli_query($con, $sql);
    $sections = [];
    while ($row = mysqli_fetch_assoc($execute)) {
       $sections[] = $row;
    }
    return $sections;
}

function getClientDetById($id){

	global $con;

	$sql = " SELECT * FROM tbl_client WHERE id = '".$id."' ";
	$result = mysqli_query($con, $sql);
	$getRow = mysqli_fetch_array($result);

	return $getRow;
}

function getUserDetByClientEmail($email){

	global $con;

	$sql = " SELECT * FROM tbl_user WHERE email = '".$email."' ";
	$result = mysqli_query($con, $sql);
	$getRow = mysqli_fetch_array($result);

	return $getRow;
}


function editClientDetails($userId,$data){ 
    global $con;
    $sql = " DELETE FROM tbl_user WHERE account_id='".$userId."' AND username='".$data['username']."' AND `is_admin` = '1'";
    mysqli_query($con, $sql);
    $sql = "INSERT INTO `tbl_user` SET 
    `account_id` = '".$userId."',
    `name` = '".$data['name']."',
    `email` = '".$data['email']."',
    `username` = '".$data['username']."',
    `password` = '".$data['password']."',
    `isAdmin` = '1',
    `status` = '1'";
    mysqli_query($con, $sql);
    $sql = " DELETE FROM tbl_client_section_permission WHERE account_id='".$userId."'";
    mysqli_query($con, $sql);
    foreach($data['sections'] as $section){
        $sql = "INSERT INTO `tbl_client_section_permission` SET 
        `section_id` = '".$section."',
        `account_id` = '".$userId."'";
        mysqli_query($con, $sql);
    }
    $sql = " DELETE FROM tbl_client_ezee_setup WHERE account_id='".$userId."'";
    mysqli_query($con, $sql);
    $sql = "INSERT INTO `tbl_client_ezee_setup` SET 
    `account_id` = '".$userId."',
    `hotel_id` = '".$data['hotel_id']."',
    `merchant_id` = '".$data['merchant_id']."',
    `status` = '1'";
     mysqli_query($con, $sql);
     
}	

function getTotalTaskNo($accountId, $fromDate, $toDate)
{
	global $con;

	$dateCond = '';
	if (isset($fromDate) && $fromDate != '' && $toDate != '') {
		$dateCond = "AND DATE(ordDateTime) BETWEEN '".date('Y-m-d', strtotime($fromDate) )."' AND '".date('Y-m-d', strtotime($toDate) )."'";
	}
	
	$sqlQry = " SELECT count(ordNumber) AS totalOrdNumber FROM tbl_orders WHERE account_id = '".$accountId."' ".$dateCond." ";
	$executeQry = mysqli_query($con,$sqlQry);
	$returnTaskCount = mysqli_fetch_array($executeQry);
	$totalOrdNumber = $returnTaskCount['totalOrdNumber'];

	return $totalOrdNumber;
}



function getTotalIssueIn($accountId,$fromDate,$toDate){

	global $con;

	$dateCond = '';
	if (isset($fromDate) && $fromDate != '' && $toDate != '') 
	{
		$dateCond = "AND DATE(ordDateTime) BETWEEN '".date('Y-m-d', strtotime($fromDate) )."' AND '".date('Y-m-d', strtotime($toDate) )."'";
	}

	$sqlQry = " SELECT count(ordType) AS totalIssueIn FROM tbl_orders WHERE account_id = '".$accountId."' AND ordType = '1' ".$dateCond." ";
 	$executeQry = mysqli_query($con,$sqlQry);
 	$returnIssueInCount = mysqli_fetch_array($executeQry);
 	$totalIssueIn = $returnIssueInCount['totalIssueIn'];

 	return $totalIssueIn;
}

function getTotalIssueOut($accountId, $fromDate, $toDate){
	global $con;

	$dateCond = '';
	if (isset($fromDate) && $fromDate != '' && $toDate != '') {
		$dateCond = "AND DATE(ordDateTime) BETWEEN '".date('Y-m-d', strtotime($fromDate) )."' AND '".date('Y-m-d', strtotime($toDate) )."'";
	}

	$sqlQry = " SELECT count(ordType) AS totalIssueOut FROM tbl_orders WHERE account_id = '".$accountId."' AND ordType = '2' ".$dateCond." ";
 	$executeQry = mysqli_query($con,$sqlQry);
 	$returnIssueOutCount = mysqli_fetch_array($executeQry);
 	$totalIssueOut = $returnIssueOutCount['totalIssueOut'];

 	return $totalIssueOut;
}

function getTotalStockTake($accountId, $fromDate, $toDate){
	global $con;

	$dateCond = '';
	if (isset($fromDate) && $fromDate != '' && $toDate != '') {
		$dateCond = "AND DATE(ordDateTime) BETWEEN '".date('Y-m-d', strtotime($fromDate) )."' AND '".date('Y-m-d', strtotime($toDate) )."'";
	}

	$sqlQry = " SELECT count(ordType) AS totalStockTake FROM tbl_orders WHERE account_id = '".$accountId."' AND ordType = '3' ".$dateCond." ";
 	$executeQry = mysqli_query($con,$sqlQry);
 	$returnStockTakeCount = mysqli_fetch_array($executeQry);
 	$totalStockTake = $returnStockTakeCount['totalStockTake'];

 	return $totalStockTake;
} 

function getOutLetStockTake($accountId, $fromDate, $toDate){
	global $con;

	$dateCond = '';
	if (isset($fromDate) && $fromDate != '' && $toDate != '') {
		$dateCond = "AND DATE(importDate) BETWEEN '".date('Y-m-d', strtotime($fromDate) )."' AND '".date('Y-m-d', strtotime($toDate) )."'";
	}

	$sqlQry = " SELECT count(*) AS totalOutLetStockTake FROM tbl_daily_import WHERE account_id = '".$accountId."' ".$dateCond." ";
 	$executeQry = mysqli_query($con,$sqlQry);
 	$returnOutLetStockTakeCount = mysqli_fetch_array($executeQry);
 	$totalOutLetStockTake = $returnOutLetStockTakeCount['totalOutLetStockTake'];

 	return $totalOutLetStockTake;
}

function getTotalProductByAccId($accountId){
	global $con;

	$sqlQry = " SELECT count(*) AS totalProduct FROM tbl_products WHERE account_id = '".$accountId."' ";
 	$executeQry = mysqli_query($con,$sqlQry);
 	$returnTotalProduct = mysqli_fetch_array($executeQry);
 	$totalProduct = $returnTotalProduct['totalProduct'];

 	return $totalProduct;
}


function addCurrency($data){ 
    global $con;
    $sql = "INSERT INTO `tbl_currency` SET 
    `account_id` = '1',
    `currency` = '".$data['currency']."',
    `amt` = '".$data['value']."',
    `curCode` = '".$data['currencyCode']."',
    `symbol_left` = '".$data['symbol_left']."',
	`symbol_right` = '".$data['symbol_right']."',
    `decPlace` = '".$data['decimal_place']."',
	`is_default` = '0',
	`status` = '".$data['status']."'";
    mysqli_query($con, $sql);  
	$_SESSION['added'] = "Currency has been successfully added";
	echo "<script>window.location='manageCurrency.php'</script>";   
}
function addBusiness($data){ 
    global $con;
    $sql = "INSERT INTO `tbl_business` SET 
    `name` = '".$data['name']."',
    `status` = '".$data['status']."'";
    mysqli_query($con, $sql);  
	$_SESSION['added'] = "Business has been successfully added";
	echo "<script>window.location='manageBusiness.php'</script>";   
}
function addSection($data){ 
    global $con;
    $sql = "INSERT INTO `tbl_sections` SET 
    `name` = '".$data['name']."',
	`is_default` = '".$data['type']."',
    `status` = '".$data['status']."'";
    mysqli_query($con, $sql);  
	$_SESSION['added'] = "Sections has been successfully added";
	echo "<script>window.location='manageSection.php'</script>";   
}
function getSectionById($id){
	global $con;
    $sql = "SELECT * FROM tbl_sections WHERE id='$id'";
    $execute = mysqli_query($con, $sql);
   	return mysqli_fetch_assoc($execute);
}
function editSection($data,$id){ 
    global $con;
    $sql = "UPDATE `tbl_sections` SET 
    `name` = '".$data['name']."',
	`is_default` = '".$data['type']."',
    `status` = '".$data['status']."' WHERE id='$id'";

    mysqli_query($con, $sql);  
	$_SESSION['updated'] = "Sections has been successfully Updated";
	echo "<script>window.location='manageSection.php'</script>";
     
}
function getBusinessById($id){
	global $con;
    $sql = "SELECT * FROM tbl_business WHERE id='$id'";
    $execute = mysqli_query($con, $sql);
   	return mysqli_fetch_assoc($execute);
}
function editBusiness($data,$id){ 
    global $con;
    $sql = "UPDATE `tbl_business` SET 
    `name` = '".$data['name']."',
    `status` = '".$data['status']."' WHERE id='$id'";

    mysqli_query($con, $sql);  
	$_SESSION['updated'] = "Business has been successfully Updated";
	echo "<script>window.location='manageBusiness.php'</script>";
}
function getCurrencyById($id){
	global $con;
    $sql = "SELECT * FROM tbl_currency WHERE id='$id'";
    $execute = mysqli_query($con, $sql);
   	return mysqli_fetch_assoc($execute);
}

function editCurrency($data,$id){ 
    global $con;
	$sql = "UPDATE `tbl_currency` SET 
    `account_id` = '1',
    `currency` = '".$data['currency']."',
    `amt` = '".$data['value']."',
    `curCode` = '".$data['currencyCode']."',
    `symbol_left` = '".$data['symbol_left']."',
	`symbol_right` = '".$data['symbol_right']."',
    `decPlace` = '".$data['decimal_place']."',
	`is_default` = '0',
	`status` = '".$data['status']."' WHERE id='$id'";
    mysqli_query($con, $sql);  
	$_SESSION['updated'] = "Currency has been successfully Updated";
	echo "<script>window.location='manageCurrency.php'</script>";
}
function checkUesrLogin(){
	if(!isset($_SESSION['superAdminEmail'])){
		echo "<script>window.location='login.php'</script>";
	} 
}

function getUsersPermissions($id){
	global $con;
    
	$sql = "SELECT * FROM tbl_client_section_permission csp LEFT JOIN tbl_sections ts ON(csp.section_id=ts.id) WHERE csp.account_id='".$id."' AND ts.status=1";
	
	$execute = mysqli_query($con, $sql);
	$section = [];
    while ($row = mysqli_fetch_assoc($execute)) {
        $section[] = strtolower($row['name']);
    }
	return $section;
}



function checkreqpermissionRow($designation_id,$accountId,$rowId){
	global $con;

	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND type = 'member' AND account_id = '".$accountId."' and type_id = '".$rowId."' and designation_section_permission_id=2 ";
                    
    $checkSubPerQry = mysqli_query($con, $sql);
    return $checkSubPerQry;               
}

function checkorderPermissionRow($designation_id,$accountId,$rowId){
	global $con;
	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND type = 'order_supplier' AND account_id = '".$accountId."' and type_id = '".$rowId."' and designation_section_permission_id=1 ";
	$checkSubPerQry = mysqli_query($con, $sql);
	return $checkSubPerQry;
}

function checkIfPermissionToNewOrderSec($designation_id,$accountId){
	global $con;
	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND type = 'order_supplier' AND account_id = '".$accountId."' AND designation_section_permission_id=1 ";

	return $checkSubPerQry = mysqli_query($con, $sql);
}

function checkIfPermissionToNewReqSec($designation_id,$accountId){
	global $con;
	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE designation_id = '".$designation_id."' AND type = 'member' AND account_id = '".$accountId."' AND designation_section_permission_id=2 ";

	return $checkSubPerQry = mysqli_query($con, $sql);
}

function get_user($id) {
	global $con;

	$sql = " SELECT * FROM tbl_user WHERE id = '".$id."' AND account_id = '".$_SESSION['accountId']."' ";
	$res = mysqli_query($con, $sql);
	$resultRow = mysqli_fetch_array($res);
	return $resultRow['name'];
}


function getUserNameAndTitle($id) {
	global $con;

	$sql = " SELECT u.name, d.designation_name FROM tbl_user u INNER JOIN tbl_designation d ON(u.designation_id = d.id) AND u.account_id = d.account_id  WHERE u.id = '".$id."' AND u.account_id = '".$_SESSION['accountId']."' ";
	$res = mysqli_query($con, $sql);
	$resultRow = mysqli_fetch_array($res);
	return $resultRow['name'].'('.$resultRow['designation_name'].')';
}

function order_journey($accountId, $orderId, $action){
	global $con; 

	$sql = " SELECT * FROM tbl_order_journey WHERE account_id = '".$accountId."' AND orderId = '".$orderId."' AND action = '".$action."' ";
    $resQry = mysqli_query($con, $sql);
    $getDataArr = [];
    while($rowQry = mysqli_fetch_array($resQry)){
    	$getDataArr[] = $rowQry;
    }
    return $getDataArr;
}


function ActiveReqAutoFill($cond){

	global $con;

	$sqlSet = " SELECT p.* FROM tbl_products p 
    INNER JOIN tbl_productdepartments pd ON
        (p.id = pd.productId) AND p.account_id = pd.account_id
    INNER JOIN tbl_stocks s ON
        (s.pId = p.id) AND s.account_id = p.account_id 
    WHERE p.account_id = '".$_SESSION['accountId']."' ".$cond. " AND p.status=1 GROUP BY(id)  ORDER BY p.itemName ";
    $proList = mysqli_query($con, $sqlSet);

    $autoFillArr = [];
    while ($proListRow = mysqli_fetch_array($proList)) 
    {
        $date = date('Y-m-d', strtotime('-1 day'));

        $sql = " SELECT oi.minQty minQty, oi.maxQty maxQty, oi.pId, oi.factor proFactor, dii.* FROM tbl_daily_import_items dii
        INNER JOIN tbl_outlet_items oi ON(oi.outLetId = dii.outLetId) AND (oi.account_id = dii.account_id) 
        INNER JOIN tbl_revenue_center_departments rcd ON(rcd.id = dii.outLetId) AND (rcd.account_id = dii.account_id)   
        WHERE importedOn = '".$date."' AND dii.account_id = '".$_SESSION['accountId']."' AND rcd.deptId = '".$_SESSION['recMemberId']."' AND dii.barCode = '".$proListRow['barCode']."' AND oi.pId = '".$proListRow['id']."' ";

        $sqlResult = mysqli_query($con, $sql);

        $sqlResRow = mysqli_fetch_array($sqlResult);
	

        $requisition = $sqlResRow['requisition'];

        $closeStock = $sqlResRow['closeStock'];//ceil($sqlResRow['closeStock']/$sqlResRow['proFactor']);
		$closeStockDone = $sqlResRow['closeStockDone'];

        $minQty = $sqlResRow['minQty'];
        $maxQty = $sqlResRow['maxQty'];

        if ($requisition < 1)
        {
		
		
            $checkAutoFillQty = ($closeStock < $minQty) && ($closeStockDone) ? ($maxQty-$closeStock) : '';
			$checkAutoFillQty = $checkAutoFillQty && $sqlResRow['proFactor'] ? ceil($checkAutoFillQty/$sqlResRow['proFactor']) : '';
            $autoFillArr[$proListRow['id']] = $checkAutoFillQty;
        }
        else
        {
            $checkAutoFillQty =(($maxQty-$closeStock)-($requisition*$sqlResRow['proFactor']));
			$checkAutoFillQty = $checkAutoFillQty && $sqlResRow['proFactor'] ? ceil($checkAutoFillQty/$sqlResRow['proFactor']) : '';
            $autoFillArr[$proListRow['id']] = $checkAutoFillQty;
        }
        
    }

    foreach ($autoFillArr as $key => $value) {
    	if ($value > 0) {
    		return $value;
    	}
    	
    }
}


//Check stock quantity while issue out
function checkStockQtyRequisition($orderId, $accountId)
{
	global $con;

	$errorMes = '';
	
	$sql = "SELECT p.id AS pId, p.itemName, s.qty AS qtyInStock, od.qty AS orderedQty FROM tbl_order_details od 
		INNER JOIN tbl_stocks s ON(od.pId = s.pId) AND od.account_id = s.account_id
		INNER JOIN tbl_products p ON(p.id = s.pId) AND s.account_id = p.account_id

	    WHERE od.ordId = '".$orderId."'  AND od.account_id = '".$accountId."' AND s.qty < od.qty ";
	$ordQry = mysqli_query($con, $sql);	

	$sql = " SELECT * FROM tbl_orders WHERE id = '".$orderId."' AND account_id = '".$_SESSION['accountId']."' ";
	$result = mysqli_query($con, $sql);
	$qryRow = mysqli_fetch_array($result);		
	
	$productNames = [];
	while($ordRow = mysqli_fetch_array($ordQry) )
	{ 	
		//$productNames[] = "<div><strong>".'"'. $ordRow['itemName'] .'"'."</strong> ".showOtherLangText('max available Quantity in stock is')." <strong>".$ordRow['qtyInStock'].".</strong> ".showOtherLangText('Please edit it.')." <input type='hidden' name='orderId' value='".$orderId."'><input type='text' name='reqQty[".$ordRow['pId']."]' value='".$ordRow['orderedQty']."'></div>";

		$productNames[] = "<tr><td>". $ordRow['itemName'] ."</td>
							<td>". $ordRow['qtyInStock'] ."</td>
							<input type='hidden' name='orderId' value='".$orderId."'>
							<td><input type='text' class='form-control' name='reqQty[".$ordRow['pId']."]' value='".$ordRow['orderedQty']."'></td></tr>";
	}
	
	//print_r($productNames);
	$errorMes =   showOtherLangText('Below items has less stocks than added in this requisition so please edit it:');
	
	$errorMes .= "<br><br>";

	$errorMes .= "<p># ".$qryRow['ordNumber']."</p>";

	$errorMes .= "<table class='issueOut-qtyTbl'>
					<thead>
						<tr>
							<th style='width: 60%;'>". showOtherLangText('Item') ."</th>
							<th style='width: 20%;'>". showOtherLangText('S.Qty') ."</th>
							<th style='width: 20%;'>". showOtherLangText('Qty') ."</th>
						</tr>
					</thead>
					<tbody>";
	
	$errorMes .= implode('',$productNames);

	$errorMes .= "</tbody>
					</table>";

	//$errorMes .= "<br><br>";

	$errorMes .= '<div class="allBtn popupBtn">
					<div><input type="submit" name="approveBtn" class="btn btn-primary approveBtn" value="'.showOtherLangText('Approve').'"></div>
					<div><button type="button" class="btn btn-primary backBtn" id="backBtn">'.showOtherLangText('Cancel').'</button></div>
				</div>';
	
	echo '<div style="text-align:center;">' . $errorMes . '</div>';
			

}//End Check stock quantity while issue out


function getRevenueTotals($outLetId, $fromDate, $toDate)
{

	global $con;


		//Get last row of each item for close stock for date range
		
		/*$sql = "SELECT  i.*, o.itemType as outLetItemType
			
			 FROM tbl_outlet_items o
			
			INNER JOIN tbl_products p ON(p.id = o.pId) AND (p.account_id = o.account_id)
			
			
			INNER JOIN tbl_daily_import_items i ON(p.barCode = i.barCode) AND (p.account_id = i.account_id) AND i.outLetId=o.outLetId AND i.importedOn	 BETWEEN '".date( 'Y-m-d', strtotime($fromDate) )."' AND '".date( 'Y-m-d', strtotime($toDate) )."' 
						
			WHERE o.outLetId = '".$outLetId."' AND o.account_id = '".$_SESSION['accountId']."'    ";*/
			
			$sql = "SELECT  *
			
			 FROM  tbl_daily_import_items WHERE outLetId = '".$outLetId."' AND account_id = '".$_SESSION['accountId']."' AND  importedOn	 BETWEEN '".date( 'Y-m-d', strtotime($fromDate) )."' AND '".date( 'Y-m-d', strtotime($toDate) )."' 
						
			   ";
			$reportQry = mysqli_query($con, $sql);
			
			$itemFirstReportRowArr = [];
		
			$itemLastReportRowArr = [];
			
			$itemsTotalsArr = [];
			
			$importedDatesArr = [];
			$adjForEnptyBottleArr = [];
			while( $rowOfReport = mysqli_fetch_array($reportQry) )
			{
				$barCode = $rowOfReport['barCode'];
				
				$itemsTotalsArr[$barCode]['usageCntTot'] += $rowOfReport['usageCnt'];
				$itemsTotalsArr[$barCode]['usageTotal'] += $rowOfReport['usagePerDay'];
				$itemsTotalsArr[$barCode]['usagePerGuestTot'] += $rowOfReport['usagePerGuest'];
				$itemsTotalsArr[$barCode]['easySalesTot'] += $rowOfReport['easySales'];
				$itemsTotalsArr[$barCode]['barControlTot'] += $rowOfReport['barControl'];
				
				$itemsTotalsArr[$barCode]['issueInTot'] += $rowOfReport['issueIn'];
				$itemsTotalsArr[$barCode]['adjmentTot'] += $rowOfReport['adjment'];
				
				if($rowOfReport['adjForEnptyBottle'] == 1)
				{
					$itemsTotalsArr[$barCode]['adjmentEmptBotleTot'] += $rowOfReport['adjment'];
				}
				
				if( !isset($itemFirstReportRowArr[$rowOfReport['barCode']]) )
				{
					$itemFirstReportRowArr[$rowOfReport['barCode']] = $rowOfReport;
				}
		
				$itemLastReportRowArr[$rowOfReport['barCode']] = $rowOfReport;
				
			}
			
		//End
		
		//get easy data
		$sql = "SELECT guests, sales, importDate importDate FROM  tbl_daily_import
		
		WHERE outLetId = '".$outLetId."' AND account_id = '".$_SESSION['accountId']."' AND
			importDate  between '".date('Y-m-d', strtotime($fromDate) )."' AND '".date('Y-m-d', strtotime($toDate) )."'  ";
		$imptQry = mysqli_query($con, $sql);
		
		//$datesArr = [];
		$salesTotal = 0;
		$guestsTotal = 0;
		while($imprtRes = mysqli_fetch_array($imptQry))
		{
			$salesTotal += $imprtRes['sales'];
			
			if( !in_array($row['importDate'], $datesArr) )
			{
				$guestsTotal += $imprtRes['guests'];
				//$datesArr[] = $imprtRes['importDate'];
			}
		}


		$usageCnt = 0;
		$usageAvg = 0;
		$usagePerGuest = 0;
			
		$issueIn = 0;
		$adjustment = 0;
		$sales = 0;
		$barControl = 0;
					
		if( !empty($itemLastReportRowArr) )
		{
			foreach($itemLastReportRowArr as $dailyImportedData)
			{
					$x++;
						
					$stockPrice = $dailyImportedData['stockPrice'];
	
					
					//all columns data is here
					$usageCnt = $dailyImportedData['usageCntTot'];
					$usageAvg = $dailyImportedData['usageAvg'];
					$usagePerGuest = $dailyImportedData['usagePerGuest'];
						
					$issueIn = $dailyImportedData['issueIn'];
					$adjustment = $dailyImportedData['adjment'];
					$sales = $dailyImportedData['easySales'];
					$barControl = $dailyImportedData['barControl'];
					$closeStockDone = $dailyImportedData['closeStockDone'];
				
					//get last row of each itme to get close stock
					if(  ($fromDate != $toDate)  &&  isset($dailyImportedData['barCode']) )
					{
					
						$barCode = $dailyImportedData['barCode'];
						
						$itemFirstRowDetailsArr = $itemFirstReportRowArr[$barCode];
						$openStock = $itemFirstRowDetailsArr['openStock'];
						//$stockPrice = $itemFirstRowDetailsArr['stockPrice'];
						
						$itemLastRowDetailsArr = $itemLastReportRowArr[$barCode];
						
						$stockPrice = $itemLastRowDetailsArr['stockPrice'];
						
						$closeStock = $itemLastRowDetailsArr['closeStock'];
						
						
						$itemsTotalCntArr = $itemsTotalsArr[$barCode];
						
						$usageCnt = $itemsTotalCntArr['usageCntTot'];
						$usageAvg = $itemLastRowDetailsArr['usageAvg'];
						$usagePerGuest = $itemsTotalCntArr['usagePerGuestTot'];
						
						$issueIn = $itemsTotalCntArr['issueInTot'];
						$adjustment = $itemsTotalCntArr['adjmentTot'];
						$sales = $itemsTotalCntArr['easySalesTot'];
						$barControl = $itemsTotalCntArr['barControlTot'];
						$adjmentEmptBotleTot = isset($itemsTotalCntArr['adjmentEmptBotleTot']) ? $itemsTotalCntArr['adjmentEmptBotleTot'] : 0;
															
					}
					else
					{
						$openStock = $dailyImportedData['openStock'];					
						$closeStock = $dailyImportedData['closeStock'];
						$adjmentEmptBotleTot = $dailyImportedData['adjForEnptyBottle'] == 1 ? $dailyImportedData['adjment'] : 0;

					}
					
					$closeStockAdj = $closeStock+$adjmentEmptBotleTot;
					
					$usage = ($closeStock || $closeStockDone) ? (($openStock+$issueIn) - ($closeStockAdj) ) : 0;//(Open Stock+Issue In) - (Close Stock)
					$usage =  $usage+$barControl;//for bar control
					
					$usageTotal += $usage*$stockPrice;//do in case bottle are sold or missing
	
					if($dailyImportedData['itemType'] == 1)//non usage item
					{
						$varAdjutmet = $adjustment-$adjmentEmptBotleTot;//remove bottles adjustment from non bottles and then calculate variance
						
						$varience = $closeStockDone > 0 ? ( ($sales+$varAdjutmet) - $usage ) : '';//(Sales+Adjustment) - (Usage )
						$varienceTotal += $varience*$stockPrice;
					}
					
					//end Get totals for top
			}//end foreach here
		}//end if	
	
	$outLetDataArr =
	[
		'salesTotal' => $salesTotal,
		'guestsTotal' => $guestsTotal,
		'usageTotal' => $usageTotal,
		'usagePer' => 0,
		'varience' => $varienceTotal,
		'usagePerGuest' => 0,
		'usageLevel' => 0,
	];
	
	return $outLetDataArr;
}

?>