<?php
$hotelsArr = [21866 => '3676523932f9fc21cc-8225-11eb-b', 21930 => '44036096186cee9400-d661-11eb-b'];

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

function showPrice($price, $decimal=4)
{
	echo $price ? number_format($price, $decimal).' $' : 0;
}

function getNumFormtPrice($price, $decimal=4)
{
	return  $price ? number_format($price, $decimal).' $' : 0;
}

function getPrice($price, $round=4)
{

	return $price ? round($price, $round) : 0;
}

function get2DecimalVal($val)
{
	return $val ? round($val, 2) : 0;
}

function checkSupplierForMinLevelProducts($supId)
{
	global $con;
	
	
		 $sqlSet = " SELECT p.minLevel, p.maxLevel, s.qty FROM tbl_products p  LEFT JOIN tbl_stocks s ON(s.pId = p.id) AND p.status=1 WHERE  p.id IN( SELECT  productId FROM tbl_productsuppliers WHERE supplierId = '".$supId."' )  ";
		$proresultSet = mysqli_query($con, $sqlSet);
		
		$showColor = 0;
		while($res = mysqli_fetch_array($proresultSet) )
		{
			if($res['minLevel'] >= $res['qty'] && $res['maxLevel'] > 0)
			{
				$showColor = $res['maxLevel']-$res['qty'];
			}
		}
		
		return $showColor;
	
}

function getProdcutDepartments($pid)
{
	global $con;
	$sqlSet = " SELECT GROUP_CONCAT(d.name) departs FROM tbl_productdepartments dc 
								INNER JOIN tbl_department d ON(dc.deptId = d.id) WHERE dc.productId = '".$pid."' GROUP BY dc.productId ";
	$resultSet = mysqli_query($con, $sqlSet);
	$resultRow = mysqli_fetch_array($resultSet);
	
	return $resultRow['departs'];
}

function getProdcutSuppls($pid)
{
	global $con;
	$sqlSet = " SELECT GROUP_CONCAT(s.name) suppls FROM tbl_productsuppliers ps
								INNER JOIN tbl_suppliers s ON(ps.supplierId = s.id) WHERE ps.productId = '".$pid."' GROUP BY ps.productId ";
	$resultSet = mysqli_query($con, $sqlSet);
	$supRow = mysqli_fetch_array($resultSet);
	
	return $supRow['suppls'];
}

function getStoreDetailsById($id)
{
	global $con;
	$sqlSet = " SELECT * FROM tbl_stores WHERE id = '".$id."'  ";
	$resultSet = mysqli_query($con, $sqlSet);
	
	return $storageDeptRow = mysqli_fetch_array($resultSet);
}

function getStockTotalOfStore($storeId, $cond = '')
{
	global $con;
	$sql = "SELECT SUM(stockValue) totalstockValue  FROM tbl_stocks s 
				INNER JOIN tbl_products tp ON(s.pId = tp.id) AND tp.status=1
				INNER JOIN tbl_category pc ON(pc.id = tp.parentCatId)
				INNER JOIN tbl_category c ON(c.id = tp.catId)
				WHERE storageDeptId = '".$storeId."' ".$cond;
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

	$sqlSet = " SELECT o.id ordId, o.ordType, DATE(o.setDateTime) actDate, od.*   FROM tbl_orders o
								INNER JOIN tbl_order_details od ON(o.id = od.ordId)								
					 WHERE od.pId = '".$pId."' AND status = 2 ". $cond . "  ORDER BY od.id desc  ";
	$resultSet = mysqli_query($con, $sqlSet);
	
	$rows = [];
	
	$issuedInTot = 0;
	$issuedOutTot = 0;
	$variancesTot = 0;
	
	$issuedInQtyTot = 0;
	$issuedOutQtyTot = 0;
	$variancesQtyTot = 0;
	while( $resRow = mysqli_fetch_array($resultSet) )
	{
		$actDate = $resRow['actDate'];
		
		$rows[] = $resRow;
		
		if($resRow['ordType'] == 1 || $resRow['ordType'] == 4)
		{
			$issuedInTot += $resRow['totalAmt'];
			$issuedInQtyTot += $resRow['qtyReceived']*$resRow['factor'];
		}
		elseif($resRow['ordType'] == 2)
		{
			$issuedOutTot += $resRow['totalAmt'];
			$issuedOutQtyTot += $resRow['qty'];

		}
		elseif($resRow['ordType'] == 3)
		{
			$varaincesVal = $resRow['qtyReceived']-$resRow['qty'];
			
			$variancesQtyTot += $varaincesVal;
			$variancesTot += $varaincesVal*$resRow['lastPrice'];
		}
	}
	
	return ['resRows' => $rows, 
	'issuedInTot' => $issuedInTot, 'issuedOutTot' => $issuedOutTot, 'variancesTot' => $variancesTot,
	'issuedInQtyTot' => $issuedInQtyTot, 'issuedOutQtyTot' => $issuedOutQtyTot, 'variancesQtyTot' => $variancesQtyTot, 'variancesTot' => $variancesTot
	];
}

function getOrdItemVariancesAmt($ordId)
{
	global $con;
	
	
	$sqlSet = " SELECT *   FROM  tbl_order_details 								
					 WHERE ordId = '".$ordId."'    ";
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

function showItemTypeData($type, $ordDetails)
{
		$amt = 0;
		if( ($ordDetails['ordType'] == 1 || $ordDetails['ordType'] == 4) && $type == 1)
		{
			$amt = $ordDetails['totalAmt'];
			
			return showPrice($amt);
		}
		
		if($ordDetails['ordType'] == 2 && $type == 2)
		{
			$amt = $ordDetails['totalAmt'];
			return showPrice($amt);
		}
		
		if($ordDetails['ordType'] == 3 && $type == 3)
		{
			$variancesQtyTot = $ordDetails['qtyReceived']-$ordDetails['qty'];
			$amt = $variancesQtyTot*$ordDetails['lastPrice'];
			return showPrice($amt);
		}
		
		
}


function getOrderDepartments($ordId)
{
	global $con;
	$sqlSet = " SELECT GROUP_CONCAT(DISTINCT(d.name)) departments FROM tbl_order_details o INNER JOIN tbl_department d
										ON(d.id = o.deptId)
									 WHERE o.ordId IN( ".$ordId." ) GROUP BY o.ordId ";
	$resultSet = mysqli_query($con, $sqlSet);
	$departRow = mysqli_fetch_array($resultSet);
	
	return $departRow['departments'];
}


function getOrderSuppls($ordId)
{
	global $con;
	$sqlSet = " SELECT GROUP_CONCAT(DISTINCT(s.name)) suppliers FROM tbl_order_details d INNER JOIN tbl_suppliers s
										ON(d.supplierId = s.id)
									 WHERE d.ordId IN( ".$ordId." ) GROUP BY d.ordId ";
	$resultSet = mysqli_query($con, $sqlSet);
	$supRow = mysqli_fetch_array($resultSet);
	
	return  $supRow['suppliers'];
}

function getProductStockTotalOfStore($pId, $storageDeptId)
{
	
	global $con;
	$sql = "SELECT SUM(stockValue) totalstockValue  FROM tbl_stocks s 
				INNER JOIN tbl_products tp ON(s.pId = tp.id) AND tp.status=1
				INNER JOIN tbl_category pc ON(pc.id = tp.parentCatId)
				INNER JOIN tbl_category c ON(c.id = tp.catId)
				WHERE storageDeptId = '".$storageDeptId."' AND s.pId = '".$pId."' ".$cond;
	$resQry = mysqli_query($con, $sql);
	$stockValRow = mysqli_fetch_array($resQry);
	
	return $stockValRow['totalstockValue'];
}

function getRecusitionTempData($userId)
{
	global $con;
	$sql=" SELECT * FROM  tbl_recusition_items_temp WHERE  `userId` = '".$userId."'   ";
	$result = mysqli_query($con, $sql);
	
	$proDetails = [];
	$deptId = 0;
	while($recRowsTemp = mysqli_fetch_array($result) )
	{
		$deptId = $recRowsTemp['deptId'];
		$proDetails[$recRowsTemp['deptId']][$recRowsTemp['pId']] = $recRowsTemp['qty'];
	}
	
	return ['deptId' => $deptId, 'proDetails' => $proDetails];
}

function getOrderTempData($userId)
{
	global $con;
	$sql=" SELECT * FROM  tbl_order_items_temp WHERE  `userId` = '".$userId."'   ";
	$result = mysqli_query($con, $sql);
	
	$proDetails = [];
	$supplierId = 0;
	while($recRowsTemp = mysqli_fetch_array($result) )
	{
		$supplierId = $recRowsTemp['supplierId'];
		$proDetails[$recRowsTemp['supplierId']][$recRowsTemp['pId']] = $recRowsTemp['qty'];
	}
	
	return ['supplierId' => $supplierId, 'proDetails' => $proDetails];
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
	$sql = " DELETE FROM `tbl_order_items_temp`  WHERE `userId` = '".$userId."'  ";
	mysqli_query($con, $sql);
}

function deleteRecusitionTempData($userId)
{
	global $con;
	$sql = " DELETE FROM `tbl_recusition_items_temp`  WHERE `userId` = '".$userId."'  ";
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
	$sql=" SELECT * FROM  tbl_products WHERE  `status` = 1   ".$cond;
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
	
	$sqlSet = " SELECT supplierId FROM tbl_order_details WHERE supplierId !='' AND pId = '".$pid."' ORDER BY id DESC LIMIT 1 ";
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
	
	$sql = " SELECT *  FROM `tbl_rawitem_products` rw INNER JOIN tbl_products p ON(p.id = rw.convtPid) WHERE  rw.rawPid = '".$pid."' ORDER BY rw.id DESC ";
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
	$sql = "select * FROM tbl_revenue_center_departments WHERE revCenterId = '".$revCenterId."' ";
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
	
	
	$sql=" SELECT * FROM  tbl_products WHERE  `status` = 1  AND barCode = '".$barCode."' ";
	$result = mysqli_query($con, $sql);
	
	$proDetails = mysqli_fetch_array($result);
	
	return $proDetails;
}

function getOutLateSalesCost($outLetId, $fromDate='', $toDate='')
{
	global $con;
	if($fromDate != '' && $toDate != '')
	{
		$sql = "select  (qty*lastPrice) totalPrice  FROM tbl_daily_import_items WHERE outLetId = '".$outLetId."'  AND importedOn BETWEEN '".date( 'Y-m-d', strtotime($fromDate) )."' AND '".date( 'Y-m-d', strtotime($toDate) )."'  AND imptType = 2  ";
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
					AND barCode = '".$barCode."' 
					AND importedOn = '".date('Y-m-d', strtotime('-1 day', strtotime($fromDate)))."'
					AND imptType = 1    ";
		$result = mysqli_query($con, $sql);
		
		$resOpenStock = mysqli_fetch_array($result);
		
		$resp['open'] = $resOpenStock['qty'] > 0 ? $resOpenStock['qty'] : getIssuedInQty($barCode);
		
		$sql = "select  qty  FROM tbl_daily_import_items WHERE 
					outLetId = '".$outLetId."' 
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

	$sql = "select  *  FROM tbl_daily_import_items WHERE 
				outLetId = '".$outLetId."' 
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

	$sql = "select  *  FROM tbl_daily_import_items WHERE 
				outLetId = '".$outLetId."' 
				AND barCode = '".$barCode."' 
				AND importedOn = '".$date."'  ";
				
				
	$result = mysqli_query($con, $sql);
	
	$itemDet = mysqli_fetch_array($result);
	
	
	return $itemDet;
}


function getUsageItemTotal($outLetId, $barCode, $imptType, $fromDate, $toDate)
{
	global $con;

	$sql = "select  SUM(usageCnt) usageCnt, SUM(usagePerGuest) usagePerGuest, SUM(usageAvg) usageAvg  FROM tbl_daily_import_items WHERE 
				outLetId = '".$outLetId."' 
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
				INNER JOIN tbl_products p ON(p.id = s.pId) 
				 WHERE p.barCode = '".$barCode."'  ";
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

function getAvgUsage($outLetId, $barCode, $usagePerGuest)
{
	global $con;
	
	$sql = "select  count(*) usageDays, SUM(usagePerGuest) totalUsage FROM tbl_daily_import_items WHERE 
			outLetId = '".$outLetId."' 
			AND barCode = '".$barCode."' 
			AND itemType = 2    ";
	$result = mysqli_query($con, $sql);
	$imprtRes = mysqli_fetch_array($result);
	
	if($imprtRes)
	{
		$usagePerGuest += $imprtRes['totalUsage'];
		
		$days = $imprtRes['usageDays']+1;
		
		return $imprtRes['usageDays'] > 0 ? ( $usagePerGuest/$days ) : $usagePerGuest;
	}
	
	return $usagePerGuest;
	
}

function getAdjustMent($outLetId, $barCode, $fromDate, $toDate)
{
	global $con;
	
	$sql = "select  SUM(qty) totalAdjust FROM tbl_daily_import_items WHERE 
			outLetId = '".$outLetId."' 
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
 					INNER JOIN tbl_products p ON(p.id = o.pId) 
		 WHERE o.outLetId = '".$outLetId."'  ";
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
								INNER JOIN tbl_order_details od ON(o.id = od.ordId)								
					 WHERE od.pId = '".$pId."' AND DATE(setDateTime) = '".$date."' AND recMemberId = '".$outLetId."' AND status = 2  AND ordType = 2 ";
	$resultSet = mysqli_query($con, $sqlSet);
	
	$row = mysqli_fetch_array($resultSet);
	
	return $row['totReceived'];
}


function showColorBoxes($outLetId, $fromDate, $toDate)
{

	global $con;
	
	$sql = "select  SUM(closeStock) closeStock, SUM(easySales) easySales, SUM(issueIn) issueIn  FROM tbl_daily_import_items WHERE 
			outLetId = '".$outLetId."' 
			AND (importedOn  BETWEEN '".date('Y-m-d', strtotime($fromDate))."' AND  '".date('Y-m-d', strtotime($toDate))."')  GROUP BY outLetId
			 ";
			
	$result = mysqli_query($con, $sql);
	$imprtRes = mysqli_fetch_array($result);
	
	$boxes = '';
	if($imprtRes['closeStock'])
	{
	   $boxes =  ' <span class="boxnew green" title="Close Stock"></span> ';
	}
	else
	{
		$boxes .= ' <span class="boxnew grey" title="Close Stock"></span> ';
	}
	
	if($imprtRes['easySales'])
	{
		$boxes .=  ' <span class="boxnew green" title="Ezee Sales"></span> ';
	}
	else
	{
		$boxes .= ' <span class="boxnew grey" title="Ezee Sales"></span> ';
	}
	
	if($imprtRes['issueIn'])
	{
		$boxes .=  ' <span class="boxnew green" title="Issue IN"></span> ';
	}
	else
	{
		$boxes .= ' <span class="boxnew grey" title="Issue IN"></span> ';
	}
	
	
	return $boxes;
	
}

function  getOutLetFormatedData($outLetId, $rows)
{
		global $con;
			
		$sql = "select p.barCode, o.itemType, o.factor FROM tbl_outlet_items o 
				INNER JOIN tbl_products p ON(p.id = o.pId)  
			WHERE o.outLetId = '".$outLetId."'  "; 
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
				/*if($row['SalesBarCode'] == $barCode)
				{
					$formatedRows[$barCode]['SalesQty'] = $row['SalesQty'];
					$formatedRows[$barCode]['itemType'] = $outLetRes['itemType'];
					$formatedRows[$barCode]['factor'] = $outLetRes['factor'];
				}*/
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

function addIssueOutInReport($productsRow, $outLetId)
{
	global $con;
	
	$pIds = array_keys($productsRow);
	
    $sql = "select p.barCode, o.itemType, o.factor, o.outLetId, p.id pId, p.price  FROM tbl_outlet_items o 
				INNER JOIN tbl_products p ON(p.id = o.pId)  
				INNER JOIN tbl_revenue_center_departments rcd ON(rcd.id = o.outLetId)
			WHERE rcd.deptId = '".$outLetId."' AND p.id IN(".implode(',', $pIds).")  "; 
	$outLetItemsQry = mysqli_query($con, $sql);
		
	$formatedRows = [];
	$date = date('Y-m-d');
	while( $outLetRes = mysqli_fetch_array($outLetItemsQry) )
	{
		$pId = $outLetRes['pId'];
		$issueIn = $outLetRes['factor']*$productsRow[$pId]['qty'];
		$barCode = $outLetRes['barCode'];
		
		///////////////////////////////////////////////////////////////////////////////////////////////
		
		$itemType = $outLetRes['itemType'] == 3 ? 2 : 1;//for non usage type 1 and usage type 2

			
		$sql = "SELECT * FROM tbl_daily_import_items  WHERE outLetId = '".$outLetRes['outLetId']."' AND importedOn = '".$date."' AND barCode = '".$barCode."'   ";
		$qry = mysqli_query($con, $sql);
		$checkRow = mysqli_fetch_array($qry);
		
		$lastPrice = round($outLetRes['price']/$outLetRes['factor'], 4);
		
		$usage = '';
		$usageAvg = '';
		$usagePerGuest = '';
		if($checkRow)
		{
			$issueIn += $checkRow['issueIn'];
			//get usage updated
			
			if($itemType == 2)
			{
				$usage = ($checkRow['openStock']+$issueIn)-($checkRow['closeStock']+$checkRow['adjment']);
				if($usage <= 0)
				{
					$usage = 0;
					$usagePerGuest = 0;
					$usageAvg = 0;
				}
				else
				{
					$usagePerGuest = $usage/$checkRow['guests'];
					$usageAvg = getAvgUsage($outLetRes['outLetId'], $barCode, $usagePerGuest);
				}
				
			}
			//End
			
			 $qry = " UPDATE tbl_daily_import_items SET  
						lastPrice = '".$lastPrice."',
						issueIn = '".$issueIn."',
						usageCnt = '".$usage."',
						usageAvg = '".$usageAvg."',
						usagePerGuest = '".$usagePerGuest."'
				  WHERE id = '".$checkRow['id']."'  ";
			mysqli_query($con, $qry);
		}
		else
		{
			$qry = "INSERT INTO tbl_daily_import_items SET 
			outLetId = '".$outLetRes['outLetId']."',
			importedOn = '".$date."',
			barCode = '".$barCode."',
			issueIn = '".$issueIn."',
			usageCnt = '".$usage."',
			usageAvg = '".$usageAvg."',
			usagePerGuest = '".$usagePerGuest."',
			itemType = '".$itemType."',
			lastPrice = '".$lastPrice."'
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
	
    echo $sql = "select p.barCode, o.itemType, o.factor, o.outLetId, p.id pId, p.price  FROM tbl_outlet_items o 
				INNER JOIN tbl_products p ON(p.id = o.pId)  
				INNER JOIN tbl_revenue_center_departments rcd ON(rcd.id = o.outLetId)
			WHERE rcd.deptId = '".$outLetId."' AND o.itemType=3 AND p.id IN(".implode(',', $pIds).")  "; 
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
		echo "<br>";
		echo $qry = "INSERT INTO tbl_daily_import_items SET 
		outLetId = '".$outLetRes['outLetId']."',
		importedOn = '".$date."',
		barCode = '".$barCode."',
		issueIn = '".$issueIn."',
		itemType = '".$itemType."',
		 ";
		 echo "<br>";
		//mysqli_query($con, $qry);
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
	}
	
}

function getRevenueTotals($outLetId, $fromDate, $toDate)
{
	global $con;
	
	$sql = "SELECT  *
				 FROM tbl_daily_import_items WHERE 
	outLetId = '".$outLetId."' 
	AND importedOn
	 BETWEEN '".date( 'Y-m-d', strtotime($fromDate) )."' 
	 AND '".date( 'Y-m-d', strtotime($toDate) )."' ORDER BY importedOn ";
	$result = mysqli_query($con, $sql);
	
	$openCloseData = [];
	$guestsTot = 0;
	$datesArr = [];
	$usageVarCnt = 0;
	$allVariance = 0;
	$usageAmt = 0;
	while( $dailyImportedData = mysqli_fetch_array($result) )
	{
		if( !in_array($dailyImportedData['importedOn'], $datesArr) )
		{
			$datesArr[] = $dailyImportedData['importedOn'];
			$guestsTot += $dailyImportedData['guests'];
		}
		
		
		$sql = "SELECT o.itemType FROM tbl_outlet_items o INNER JOIN tbl_products p ON(p.id = o.pId)  WHERE p.barCode = '".$dailyImportedData['barCode']."' limit 1   ";
		$qry = mysqli_query($con, $sql);
		$proDet = mysqli_fetch_array($qry);
		$itemType = $proDet['itemType'];
		
		
		//all columns data is here
		$itemLastPrice = $dailyImportedData['lastPrice'];
		$issueIn += $dailyImportedData['issueIn'];
		$adjustment += $dailyImportedData['adjment'];
		$openStock += $dailyImportedData['openStock'];
		$closeStock += $dailyImportedData['closeStock'];
		
		
		if($itemType == 3)
		{
			$usageVarCnt++;
			$usage += $dailyImportedData['usageCnt'];
			$usagePerGuest += $dailyImportedData['usagePerGuest'];
			$usageAvg += $dailyImportedData['usageAvg'];
			$usageVariance += $dailyImportedData['usageAvg'] > 0 ? ( ($dailyImportedData['usagePerGuest']/$dailyImportedData['usageAvg'])*100) : 0;
			
			$usageTotalAmount += ( ($dailyImportedData['openStock']*$itemLastPrice) + ($dailyImportedData['issueIn']*$itemLastPrice) )-($dailyImportedData['closeStock']*$itemLastPrice);//usage total
			
		}
		else
		{
			$salesEzee += $dailyImportedData['easySales'];
			$itemLastPrice = $dailyImportedData['lastPrice'];
			
			if($itemType == 1)//bar
			{
				$barCont +=  $dailyImportedData['barControl'];
				$barVaraince = $dailyImportedData['easySales']-$dailyImportedData['barControl'];
				
				$varTot += $barVaraince;
				
				$varTotAmt += ($barVaraince)*$itemLastPrice;
				
				$allVarianceAmt += (float) ($barVaraince)*$dailyImportedData['lastPrice'];
				$easySalesVariance = ($dailyImportedData['closeStock']+$dailyImportedData['adjment'])-($dailyImportedData['openStock']+$dailyImportedData['issueIn']);

				$variances += $easySalesVariance;
				
			}
			elseif($itemType == 2)//sales
			{
				$easySalesVariance = ($dailyImportedData['easySales']+$dailyImportedData['closeStock']+$dailyImportedData['adjment'])-($dailyImportedData['openStock']+$dailyImportedData['issueIn']);
				
				$variances += $easySalesVariance;
				$allVarianceAmt += (float) $dailyImportedData['lastPrice']*( $easySalesVariance );//sales variance
				
				//echo $dailyImportedData['closeStock'];
				//echo "**<br>";
				
				$costSalesAmt += ( ($dailyImportedData['openStock']*$itemLastPrice) + ($dailyImportedData['issueIn']*$itemLastPrice) ) - ( ($dailyImportedData['closeStock']*$itemLastPrice) + ($dailyImportedData['adjment']*$itemLastPrice) );
				
			}
		}
		
		
		
		$openStockAmt += $dailyImportedData['openStock']*$itemLastPrice;
		$closeStockAmt += $dailyImportedData['closeStock']*$itemLastPrice;
		
		$issueInAmt += $dailyImportedData['issueIn']*$itemLastPrice;
		$salesAmt += $dailyImportedData['salesEzee']*$itemLastPrice;
		$adjAmt += $dailyImportedData['adjment']*$itemLastPrice;
		
		$usageAmt += $dailyImportedData['usageCnt']*$itemLastPrice;
		$barContAmt += $dailyImportedData['barControl']*$itemLastPrice;
		
			//$variances += ($dailyImportedData['closeStock']+$dailyImportedData['adjment']+$dailyImportedData['salesEzee'])-($dailyImportedData['openStock']+$dailyImportedData['issueIn']);//sales variance

	
		
							
	}//end loop here
	
	
	
	//echo ( $barContAmt .'+'. $costSalesAmt .'+'. $usageAmt);
	//get total -----------------------------------------------------------------------------------------------------------------
	
	//$variances = ($closeStock+$adjustment+$salesEzee)-($openStock+$issueIn);//sales variance	
	//$variances = ($closeStock+$adjustment)-($openStock+$issueIn);//sales variance
	
	//$varTot = $salesEzee-$barCont;
	//end total ------------------------------------------------------------------------------------------------------------------	
	$costAmt += ( $barContAmt + $costSalesAmt + $usageAmt);
	
	return [
			
			'guestsTot' => $guestsTot,
			'issueIn' => $issueIn,
			'adjustment' => $adjustment,
			'openStock' => $openStock,
			'closeStock' => $closeStock,
			
			'usage' => $usage,
			'usagePerGuest' => $usagePerGuest,
			'usageAvg' => $usageAvg,
			'usageVariance' => $usageVariance,
			'usageVarCnt' => $usageVarCnt,
			
			
			'variances' => $variances,
			'varTot' => $varTot,
			
			'cost' => $costAmt,
			'openStockAmt' => $openStockAmt,
			'closeStockAmt' => $closeStockAmt,
			'issueInAmt' => $issueInAmt,
			'salesAmt' => $salesAmt,
			'adjAmt' => $adjAmt,
			'varTotAmt' => $varTotAmt,
			'allVarianceAmt' => $allVarianceAmt,
			'usageAmt' => $usageAmt,
			'usageTotalAmount' => $usageTotalAmount
			
		];
	
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
		
		$sql = "SELECT * FROM tbl_daily_import  WHERE outLetId = '".$outLetId."' AND importDate = '".$date."' ";
		$qry = mysqli_query($con, $sql);
		$checkRow = mysqli_fetch_array($qry);

		if($checkRow)
		{
			$qry = " UPDATE tbl_daily_import SET
							 
							sales = '".$sales."'
					WHERE id = '".$checkRow['id']."' ";
			mysqli_query($con, $qry);
			
			$parentId = $checkRow['id'];
		}
		else
		{
			$qry = " INSERT INTO tbl_daily_import SET
		 outLetId = '".$outLetId."', importDate = '".$date."', sales = '".$sales."' ";
			mysqli_query($con, $qry);
			$parentId = mysqli_insert_id($con);
		}
			
	return 	$parentId;
}


function checkEasyBarCodesEasy($outLetIdArr, $itemRow)
{
	global $con;
		
	$barCode = 	$itemRow['ItemCode'];	
	
	$sql = "select p.barCode, p.price, o.itemType, o.factor FROM tbl_outlet_items o 
		INNER JOIN tbl_products p ON(p.id = o.pId)  
	WHERE o.outLetId IN(".implode(',',$outLetIdArr).") AND p.barCode = '".$barCode."' limit 1  "; 
	$outLetItemsQry = mysqli_query($con, $sql);
	$proDetails = mysqli_fetch_array($outLetItemsQry);

	if( empty($proDetails) )
	{
		$_SESSION['barCodesNotFound'][$barCode] = $barCode;
	}
}

function insertUpdateDailyDataItemsEasy($outLetId, $parentId, $date, $itemRow)
{
	global $con;
		
	
		$barCode = 	$itemRow['ItemCode'];	
		
		$sql = "select p.barCode, p.price, o.itemType, o.factor FROM tbl_outlet_items o 
			INNER JOIN tbl_products p ON(p.id = o.pId)  
		WHERE o.outLetId = '".$outLetId."' AND p.barCode = '".$barCode."' limit 1  "; 
		$outLetItemsQry = mysqli_query($con, $sql);
		$proDetails = mysqli_fetch_array($outLetItemsQry);
	
		if( empty($proDetails) )
		{
			//$_SESSION['barCodesNotFound'][] = $barCode;
			return true;
		}
		$easySales	 = $itemRow['BaseQuantity'];
					
		$lastPrice = round($proDetails['price']/$proDetails['factor'], 4);//need to discuss here
		
		$sql = "SELECT * FROM tbl_daily_import_items  WHERE outLetId = '".$outLetId."' AND importedOn = '".$date."' AND barCode = '".$barCode."'   ";
		$qry = mysqli_query($con, $sql);
		$checkRow = mysqli_fetch_array($qry);
		if($checkRow)
		{
			 $qry = " UPDATE tbl_daily_import_items SET  
						
						easySales = '".$easySales."',
						lastPrice = '".$lastPrice."'
						
				  WHERE id = '".$checkRow['id']."'  ";
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
			lastPrice = '".$lastPrice."',
			itemType = '1'
			 ";
			mysqli_query($con, $qry);
		}
	
}


function insertUpdateOutLetReport($outLetId, $formatedRows)
{
	global $con;
	//--------insert master data -----------------------------
		$date = date( 'Y-m-d' );
		
		$sql = "SELECT * FROM tbl_daily_import  WHERE outLetId = '".$outLetId."' AND importDate = '".$date."' ";
		$qry = mysqli_query($con, $sql);
		$checkRow = mysqli_fetch_array($qry);

		if($checkRow)
		{			
			$parentId = $checkRow['id'];
		}
		else
		{
			
		 $qry = " INSERT INTO tbl_daily_import SET
		 outLetId = '".$outLetId."', importDate = '".$date."'  ";
			mysqli_query($con, $qry);
			$parentId = mysqli_insert_id($con);
		}
		
		
	//--------end --------------------------------------------
		
	foreach($formatedRows as $barCode=>$row)
	{
					
			$factor = $row['itemType'] == 1 ? $row['factor'] : 1;//if item type bar control then pick actual factory else just 1 to have no impact

			$closeStock	 = $row['StockTakeQty']*$factor;
			//$easySales	 = $row['SalesQty'];
			$barControl	 = $row['BCQty'];
			
			$proDetails = getProDetailsByBarCode($barCode);
			
			$lastPrice = round($proDetails['price']/$row['factor'], 4);
			
			//$issueIn   = $row['factor']*getIssueIn($outLetId, $proDetails['id'], $date);//here regardless of item type *factory will be
			
			$lastDate = date('Y-m-d', strtotime('-1 day', strtotime($date)) );
			$itemDetails = getLastDayItemDetails($outLetId, $barCode, $lastDate );
			$openStock = $itemDetails['closeStock'] > 0 ? $itemDetails['closeStock']: 0;			
			
			$adjustment = 0;
			
			$itemType = 1;//for non usage type

			//get usage updated
			$usage = '';
			$usageAvg = '';
			$usagePerGuest = '';
			
			$sql = "SELECT * FROM tbl_daily_import_items  WHERE outLetId = '".$outLetId."' AND importedOn = '".$date."' AND barCode = '".$barCode."'   ";
			$qry = mysqli_query($con, $sql);
			$checkRow = mysqli_fetch_array($qry);
			
			if($row['itemType'] == 3)
			{
				$sql = "SELECT * FROM tbl_daily_import_items  WHERE outLetId = '".$outLetId."'    AND barCode = '".$barCode."' limit 1   ";
				$qry = mysqli_query($con, $sql);
				$checkRec = mysqli_fetch_array($qry);
				
				$itemType = 2;//For usage type
				if($checkRec)
				{
					$issueIn = isset($checkRow['issueIn']) ? $checkRow['issueIn'] : 0;
					$usage = ($openStock+$issueIn)-($closeStock+$adjustment);
					$usagePerGuest = $usage/$guests;
					$usageAvg = getAvgUsage($outLetId, $barCode, $usagePerGuest);
				}
			}
			//End
			
			
			if($checkRow)
			{
				//easySales = '".$easySales."',
				 $qry = " UPDATE tbl_daily_import_items SET  
							closeStock = '".$closeStock."',
							
							barControl = '".$barControl."',
							lastPrice = '".$lastPrice."',
							usageCnt = '".$usage."',
							usageAvg = '".$usageAvg."',
							usagePerGuest = '".$usagePerGuest."',
							guests = '".$guests."',
							openStock = '".$openStock."'
					  WHERE id = '".$checkRow['id']."'  ";
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
				
				barControl = '".$barControl."',
				lastPrice = '".$lastPrice."',
				usageCnt = '".$usage."',
				usageAvg = '".$usageAvg."',
				usagePerGuest = '".$usagePerGuest."',
				guests = '".$guests."',
				openStock = '".$openStock."',
				itemType = '".$itemType."'
				 ";
				mysqli_query($con, $qry);
			}
		}
}

function getOutLetByName($name)
{
	global $con;
	$sql = "SELECT * FROM tbl_deptusers 
		 WHERE name = '".$name."'  ";
	$outLetQry = mysqli_query($con, $sql);
	
	$row = mysqli_fetch_array($outLetQry);
	
	return $row;
}

function getRevOutLetDetByName($name)
{
	global $con;
	$sql = "SELECT du.name, rcd.id FROM tbl_deptusers du INNER JOIN tbl_revenue_center_departments rcd ON(du.id = rcd.deptId) WHERE du.name = '".trim($name)."'  ";
	$outLetQry = mysqli_query($con, $sql);
	
	$row = mysqli_fetch_array($outLetQry);
	
	return $row;
}


function getTotalGuestsVal($fromDate, $toDate)
{	
	global $con;
	
	 $sql = "SELECT * FROM tbl_daily_import 
		 		WHERE  importDate
				 BETWEEN '".date( 'Y-m-d', strtotime($fromDate) )."' 
				 AND '".date( 'Y-m-d', strtotime($toDate) )."'  ";
				 
				
	$outLetQry = mysqli_query($con, $sql);
	
	$guestsArr = [];
	while( $row = mysqli_fetch_array($outLetQry) )
	{
		$guestsArr[$row['importDate']] =  $row['guests'];
	}
	
	return array_sum($guestsArr);
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

function getCurrencyDet($id)
{
	global $con;
	
	$sql = "SELECT * FROM tbl_currency 
		 		WHERE  id = '".$id."' ";
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
	return $amt ? getNumberFormat(getPrice($amt, $curDet['decPlace']), $curDet['decPlace']) .' '.html_entity_decode($curDet['curCode']) : '';
}

function getMappedOutLetsByHotelId($hotelId)
{
	global $con;
	$sql = "SELECT * FROM  `tbl_map_outlets`  WHERE `hotelId` = '".$hotelId."' ";
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
	
	$sql = "SELECT * FROM  `tbl_map_outletcats` mp INNER JOIN tbl_map_category c ON(c.id = mp.revCatId) WHERE mp.`revOutLetId` = '".$outLetId."' ";
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
		 		WHERE  outLetId = '".$outLetId."' AND pId = '".$pId."' AND noteDate = '".date('Y-m-d', strtotime($noteDate) )."' ";
	$qry = mysqli_query($con, $sql);
	
	$res = mysqli_fetch_array($qry);
	
	return isset($res['notes']) ? $res['notes'] : '';
}

function getStorageItemsCount($storageDeptId)
{
	global $con;
	$sql = "SELECT count(s.id) totalItems FROM tbl_stocks s 
				INNER JOIN tbl_products tp ON(s.pId = tp.id) AND tp.status=1
				WHERE storageDeptId = '".$storageDeptId."'  ";
	$resQry = mysqli_query($con, $sql);
	$stockRow = mysqli_fetch_array($resQry);
	
	return ($stockRow['totalItems'] ? $stockRow['totalItems'] : 0);
}

function getOutLetItemsCount($outLetId)
{
	global $con;
	
	$sql = "select count(*) totalItems FROM tbl_outlet_items o
	 INNER JOIN tbl_products p ON(p.id = o.pId) 
				
			 WHERE o.outLetId = '".$outLetId."' GROUP BY o.outLetId  ";
	$outLetItemsQry = mysqli_query($con, $sql);
	
	$outLetRow = mysqli_fetch_array($outLetItemsQry);
	
	return ($outLetRow['totalItems'] ? $outLetRow['totalItems'] : 0);
}

function trackStockProcessTime($stockTakeId, $stockTakeType, $userId, $updateTime=0)
{
	global $con;
	
	$sql=" SELECT * FROM  tbl_mobile_time_track WHERE stockTakeId = '".$stockTakeId."' AND `userId` = '".$userId."' AND `stockTakeType` = '".$stockTakeType."'  ";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	if ($row) {
		
		if(  $updateTime == 1 )
		{
			$sql = " UPDATE `tbl_mobile_time_track` SET  `end_time` = '".date('Y-m-d h:i:s')."'  WHERE `id` = '".$row['id']."'  ";
			mysqli_query($con, $sql);
		}

	}elseif(!$row && $updateTime == 0){
		
		 $sql = "INSERT INTO `tbl_mobile_time_track` (`id`, `stockTakeId`, `userId`, `stockTakeType`, `start_time`) VALUES (NULL, '".$stockTakeId."', '".$userId."', '".$stockTakeType."', '".date('Y-m-d h:i:s')."' ) ";

		mysqli_query($con, $sql);
		return mysqli_insert_id($con);
	}
	
}

function finishStockTake($stockTakeId, $stockTakeType, $userId)
{
	global $con;
	
	
	$sql = " UPDATE `tbl_mobile_items_temp` SET  status=1 
					 WHERE stockTakeId = '".$stockTakeId."' AND `userId` = '".$userId."' AND `stockTakeType` = '".$stockTakeType."' AND status=0 ";
	mysqli_query($con, $sql);
	
	$sql = " UPDATE `tbl_mobile_time_track` SET  status=1  WHERE stockTakeId = '".$stockTakeId."' AND `userId` = '".$userId."' AND `stockTakeType` = '".$stockTakeType."' AND status=0 ";
	mysqli_query($con, $sql);
	
	//out let stock take then do this
	if($stockTakeType == 2 || $stockTakeType == 5)
	{
		reportStockTakeFromMobile($stockTakeId, $userId, $stockTakeType);
	}
	//End outlet stock take
	
	//issue out then do this
	if($stockTakeType == 4)
	{
		issueOutMobile($stockTakeId);
	}
	//End outlet stock take
	
}

function getMobileStockTakeCount($storageId)
{
	global $con;
	
	$sql=" SELECT count(*) totalPendingStockTake FROM  tbl_mobile_time_track WHERE stockTakeId = '".$storageId."'  AND `stockTakeType` = 1 AND status=1 ";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	
	return  ( ($row['totalPendingStockTake'] > 0) ? ('<span class="mblCnt">'.$row['totalPendingStockTake']).'</span>' : '');
}

function getRevenueOutLetDetailsById($outLetId)
{
	global $con;
	$sql = "SELECT du.name, rcd.id, rcd.revCenterId FROM tbl_deptusers du INNER JOIN tbl_revenue_center_departments rcd ON(du.id = rcd.deptId) WHERE rcd.id = '".$outLetId."'  ";
	$outLetQry = mysqli_query($con, $sql);
	
	$row = mysqli_fetch_array($outLetQry);
	
	return $row;
}

function reportStockTakeFromMobile($stockTakeId, $userId, $stockTakeType)
{
	global $con;
			
	 $sql = "SELECT tp.barCode , o.itemType, o.factor, s.qty StockTakeQty FROM tbl_mobile_items_temp s 
		INNER JOIN tbl_products tp 
			ON(s.pId = tp.id) AND tp.status=1 AND s.stockTakeId = '".$stockTakeId."' AND s.userId = '".$userId."' AND s.stockTakeType='".$stockTakeType."' AND s.status=1
		INNER JOIN tbl_outlet_items o ON(o.pId = s.pId)
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
					 WHERE stockTakeId = '".$stockTakeId."' AND `userId` = '".$userId."' AND `stockTakeType` = '".$stockTakeType."' AND status=1 ";
	mysqli_query($con, $sql);
	
	$sql = " DELETE FROM `tbl_mobile_time_track`    WHERE stockTakeId = '".$stockTakeId."' AND `userId` = '".$userId."' AND `stockTakeType` = '".$stockTakeType."' AND status=1 ";
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
		
		$sql = "SELECT * FROM tbl_daily_import  WHERE outLetId = '".$outLetId."' AND importDate = '".$date."' ";
		$qry = mysqli_query($con, $sql);
		$checkRow = mysqli_fetch_array($qry);

		if($checkRow)
		{			
			$parentId = $checkRow['id'];
		}
		else
		{
			 $qry = " INSERT INTO tbl_daily_import SET
			 outLetId = '".$outLetId."', importDate = '".$date."' ";
			mysqli_query($con, $qry);
			$parentId = mysqli_insert_id($con);
		}
		
		
	//--------end --------------------------------------------
		
	foreach($formatedRows as $barCode=>$row)
	{
			
			$proDetails = getProDetailsByBarCode($barCode);
			$lastPrice = round($proDetails['price']/$row['factor'], 4);
						
			$itemType = 1;//for non usage type

			//get old data
			
			$sql = "SELECT * FROM tbl_daily_import_items  WHERE outLetId = '".$outLetId."' AND importedOn = '".$date."' AND barCode = '".$barCode."'   ";
			$qry = mysqli_query($con, $sql);
			$checkRow = mysqli_fetch_array($qry);
			//End
			
			
			if($checkRow)
			{
				//easySales = '".$easySales."',
				 $qry = " UPDATE tbl_daily_import_items SET  
							barControl = '".$row['StockTakeQty']."',
							lastPrice = '".$lastPrice."',
							
					  WHERE id = '".$checkRow['id']."'  ";
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
				lastPrice = '".$lastPrice."',
				itemType = '".$itemType."'
				 ";
				mysqli_query($con, $qry);
			}
		}
}


//This is for mobile stock take
function updateReportForStockTakeByOutLet($outLetId, $formatedRows)
{
	global $con;
	
	//--------insert master data -----------------------------
		$date = date('Y-m-d', strtotime('-1 day'));
		
		$sql = "SELECT * FROM tbl_daily_import  WHERE outLetId = '".$outLetId."' AND importDate = '".$date."' ";
		$qry = mysqli_query($con, $sql);
		$checkRow = mysqli_fetch_array($qry);

		if($checkRow)
		{			
			$parentId = $checkRow['id'];
		}
		else
		{
			 $qry = " INSERT INTO tbl_daily_import SET
			 outLetId = '".$outLetId."', importDate = '".$date."' ";
			mysqli_query($con, $qry);
			$parentId = mysqli_insert_id($con);
		}
		
		
	//--------end --------------------------------------------
		
	foreach($formatedRows as $barCode=>$row)
	{
					
			$factor = $row['itemType'] == 1 ? $row['factor'] : 1;//if item type bar control then pick actual factory else just 1 to have no impact

			$closeStock	 = $row['StockTakeQty']*$factor;
			
			$proDetails = getProDetailsByBarCode($barCode);
			
			$lastPrice = round($proDetails['price']/$row['factor'], 4);
						
			$lastDate = date('Y-m-d', strtotime('-1 day', strtotime($date)) );
			$itemDetails = getLastDayItemDetails($outLetId, $barCode, $lastDate );
			$openStock = $itemDetails['closeStock'] > 0 ? $itemDetails['closeStock']: 0;			
			
			$adjustment = 0;
			
			$itemType = 1;//for non usage type

			//get usage updated
			$usage = '';
			$usageAvg = '';
			$usagePerGuest = '';
			
			$sql = "SELECT * FROM tbl_daily_import_items  WHERE outLetId = '".$outLetId."' AND importedOn = '".$date."' AND barCode = '".$barCode."'   ";
			$qry = mysqli_query($con, $sql);
			$checkRow = mysqli_fetch_array($qry);
			
			if($row['itemType'] == 3)
			{
				$sql = "SELECT * FROM tbl_daily_import_items  WHERE outLetId = '".$outLetId."'    AND barCode = '".$barCode."' limit 1   ";
				$qry = mysqli_query($con, $sql);
				$checkRec = mysqli_fetch_array($qry);
				
				$itemType = 2;//For usage type
				if($checkRec)
				{
					$issueIn = isset($checkRow['issueIn']) ? $checkRow['issueIn'] : 0;
					$usage = ($openStock+$issueIn)-($closeStock+$adjustment);
					$usagePerGuest = $usage/$guests;
					$usageAvg = getAvgUsage($outLetId, $barCode, $usagePerGuest);
				}
			}
			//End
			
			
			if($checkRow)
			{
				//easySales = '".$easySales."',
				 $qry = " UPDATE tbl_daily_import_items SET  
							closeStock = '".$closeStock."',
							lastPrice = '".$lastPrice."',
							usageCnt = '".$usage."',
							usageAvg = '".$usageAvg."',
							usagePerGuest = '".$usagePerGuest."',
							openStock = '".$openStock."'
					  WHERE id = '".$checkRow['id']."'  ";
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
				usageCnt = '".$usage."',
				usageAvg = '".$usageAvg."',
				usagePerGuest = '".$usagePerGuest."',
				openStock = '".$openStock."',
				itemType = '".$itemType."'
				 ";
				mysqli_query($con, $qry);
			}
		}
}

function issueOutMobile($orderId)
{
	global $con;
	
	$sql=" SELECT * FROM  tbl_mobile_items_temp WHERE stockTakeId = '".$orderId."' AND stockTakeType=4  AND status=1  ";
	$result = mysqli_query($con, $sql);
	
	$productIds = [];
	while($row = mysqli_fetch_array($result) )
	{		
		$sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$row['pId']."' ";
		$stkQry = mysqli_query($con, $sql);
		$stkRow = mysqli_fetch_array($stkQry);
			
		$qry = " UPDATE `tbl_order_details` SET 
		`price`='".$stkRow['stockPrice']."', 
		`qty`='".$row['qty']."', 
		`totalAmt`='".($stkRow['stockPrice']*$row['qty'])."' WHERE ordId = '".$orderId."' AND pId = '".$row['pId']."' ";
		mysqli_query($con, $qry);
		
		$productIds[$row['pId']]['qty'] = $row['qty'];
	}	

}

function issueOutMobileBk($orderId)
{
	global $con;
	
	$sql=" SELECT * FROM  tbl_mobile_items_temp WHERE stockTakeId = '".$orderId."' AND stockTakeType=4  AND status=1  ";
	$result = mysqli_query($con, $sql);
	
	$productIds = [];
	while($row = mysqli_fetch_array($result) )
	{		
		$sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$row['pId']."' ";
		$stkQry = mysqli_query($con, $sql);
		$stkRow = mysqli_fetch_array($stkQry);
			
		$qry = " UPDATE `tbl_order_details` SET 
		`price`='".$stkRow['stockPrice']."', 
		`qty`='".$row['qty']."', 
		`totalAmt`='".($stkRow['stockPrice']*$row['qty'])."' WHERE ordId = '".$orderId."' AND pId = '".$row['pId']."' ";
		mysqli_query($con, $qry);
		
		$productIds[$row['pId']]['qty'] = $row['qty'];
	}	


		$updateQry = " UPDATE `tbl_orders` SET  status=2, setDateTime = '".date('Y-m-d h:i:s')."', `ordAmt` = ( select SUM(totalAmt) from tbl_order_details WHERE ordId = '".$orderId."' ) WHERE id = '".$orderId."'  ";
		mysqli_query($con, $updateQry);
		
	
			
		$sql = "SELECT * FROM tbl_order_details WHERE ordId = '".$orderId."' ";
		$ordQry = mysqli_query($con, $sql);
		while($ordRow = mysqli_fetch_array($ordQry) )
		{
			$upQry = " UPDATE  `tbl_stocks` SET
			 `qty` = (qty - ".$ordRow['qty']."),
			 `stockValue` = ( stockValue - ".$ordRow['totalAmt']." )
			 WHERE pId = '".$ordRow['pId']."'  ";
			mysqli_query($con, $upQry);
			
		}
		
		//add details in report
		$sqlSet = " SELECT * FROM tbl_orders where id = '".$orderId."'  ";
		$ordQry = mysqli_query($con, $sqlSet);
		$ordResult = mysqli_fetch_array($ordQry);
	
		addIssueOutInReport($productIds, $ordResult['recMemberId']);
		//end add report for issue in
		
	$sql=" DELETE FROM  tbl_mobile_items_temp WHERE stockTakeId = '".$orderId."' AND stockTakeType=4  AND status=1  ";
	mysqli_query($con, $sql);
	
	$sql=" DELETE FROM  tbl_order_assigned_users WHERE stockTakeId = '".$orderId."' AND stockTakeType=4  AND status=1  ";
	mysqli_query($con, $sql);
	
	$sql=" DELETE FROM  tbl_order_assigned_users WHERE orderId = '".$orderId."' AND orderType=2   ";
	mysqli_query($con, $sql);
}

?>