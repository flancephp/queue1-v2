<?php
//columns
$newOrderColumns = [
	'' . showOtherLangText('Photo') . '',
	'' . showOtherLangText('BarCode') . '',

	'' . showOtherLangText('Item') . '',
	'' . showOtherLangText('Type') . '',

	'' . showOtherLangText('Unit.C/F/Unit.S') . '',

	'' . showOtherLangText('Stock Price') . '',

	'' . showOtherLangText('Open Stock') . '',
	'' . showOtherLangText('Issues In') . '',
	'' . showOtherLangText('Adjust') . '',

	'' . showOtherLangText('Sales') . '',

	'' . showOtherLangText('Bar Control Sales') . '',

	'' . showOtherLangText('Close Stock') . '',
	'' . showOtherLangText('Usage') . '',


	'' . showOtherLangText('Variance') . '',


	'' . showOtherLangText('Usage per Guest') . '',
	'' . showOtherLangText('Avg Usage') . '',
	'' . showOtherLangText('Usage Levels') . '',


	'' . showOtherLangText('Requisition') . '',


	'' . showOtherLangText('Notes') . '',

];

$sql = "SELECT * FROM tbl_user_outlet_report_fields  WHERE userId = '" . $_SESSION['id'] . "' AND outLetId = '" . $_GET['outLetId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
$result = mysqli_query($con, $sql);
$outLetFldDet = mysqli_fetch_array($result);
$outLetUserFilterFields = $outLetFldDet['outLetUserFilterFields'] ? 	explode(',', $outLetFldDet['outLetUserFilterFields']) : $newOrderColumns;


if (isset($_REQUEST['showFields'])) {
	if ($outLetFldDet) {
		$updateQry = " UPDATE tbl_user_outlet_report_fields SET outLetUserFilterFields = '" . implode(',', $_REQUEST['showFields']) . "' WHERE id = '" . $outLetFldDet['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
		mysqli_query($con, $updateQry);
	} else {
		$updateQry = " INSERT INTO tbl_user_outlet_report_fields SET outLetUserFilterFields = '" . implode(',', $_REQUEST['showFields']) . "',
								userId = '" . $_SESSION['id'] . "' , outLetId = '" . $_GET['outLetId'] . "',  account_id = '" . $_SESSION['accountId'] . "' 
						   ";
		mysqli_query($con, $updateQry);
	}

	echo "<script>window.location='outlet_report_saleCenter.php?outLetId=" . $_GET['outLetId'] . "&fromDate=" . $_GET['fromDate'] . "&toDate=" . $_GET['toDate'] . "'</script>";
} elseif (isset($_REQUEST['clearshowFields'])) {
	$updateQry = " UPDATE tbl_user_outlet_report_fields SET outLetUserFilterFields = '' WHERE userId = '" . $_SESSION['id'] . "' AND outLetId = '" . $_GET['outLetId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'   ";
	mysqli_query($con, $updateQry);
}

$sql = "SELECT d.*, c.outLetType FROM tbl_deptusers d INNER JOIN tbl_revenue_center_departments c ON(c.deptId = d.id) AND c.account_id = d.account_id  WHERE c.id = '" . $_GET['outLetId'] . "'  AND d.account_id = '" . $_SESSION['accountId'] . "'  ";
$result = mysqli_query($con, $sql);
$outLetDet = mysqli_fetch_array($result);

//$outLetType = $outLetDet['outLetType'];

if (!isset($_GET['fromDate'])) {
	$_GET['fromDate'] = date('d-m-Y');
	$_GET['toDate'] = date('d-m-Y');
}


$condOutType = '';
if (isset($_POST['outLetType'])  && !empty($_POST['outLetType'])) {

	$condOutType = " AND ItemType IN(" . implode(',', $_POST['outLetType']) . ") ";
}



$sql = "SELECT o.pId, o.outLetId, o.itemType outletItemType, o.minQty outletMinQty, o.maxQty outletMaxQty, o.factor, o.notes, p.itemName, p.barCode saleBarcode, p.imgName, p.stockPrice itemLastPrice, IF(u.name!='',u.name,p.unitC) countingUnit, IF(ut.name!='',ut.name,o.subUnit) subUnit, i.*

 FROM tbl_outlet_items o

INNER JOIN tbl_products p ON(p.id = o.pId) AND (p.account_id = o.account_id) AND o.status=1 " . $condOutType . "

LEFT JOIN tbl_units u ON (u.id = p.unitC) 
LEFT JOIN tbl_units ut ON (ut.id = o.subUnit) AND (ut.account_id = o.account_id) AND o.outLetId = '" . $_GET['outLetId'] . "'



LEFT JOIN tbl_daily_import_items i ON(p.barCode = i.barCode) AND (p.account_id = i.account_id) AND i.outLetId=o.outLetId 

WHERE o.outLetId = '" . $_GET['outLetId'] . "' AND o.account_id = '" . $_SESSION['accountId'] . "'   GROUP BY o.pId order by i.id asc  ";
$outLetItemsQry = mysqli_query($con, $sql);


//echo "<br><br>";
//Get last row of each item for close stock for date range
if ($_GET['fromDate'] && $_GET['toDate']) {
	$sql = "SELECT  i.*, p.stockPrice itemLastPrice, p.barCode saleBarcode
	
	 FROM tbl_outlet_items o
	
	INNER JOIN tbl_products p ON(p.id = o.pId) AND (p.account_id = o.account_id) AND o.status=1 " . $condOutType . "
	
	
	INNER JOIN tbl_daily_import_items i ON(p.barCode = i.barCode) AND (p.account_id = i.account_id) AND i.outLetId=o.outLetId AND i.importedOn	 BETWEEN '" . date('Y-m-d', strtotime($_GET['fromDate'])) . "' AND '" . date('Y-m-d', strtotime($_GET['toDate'])) . "' 
	
	
	WHERE o.outLetId = '" . $_GET['outLetId'] . "' AND o.account_id = '" . $_SESSION['accountId'] . "'  order by i.importedOn   ";
	$outLetItemsForCountsTotals = mysqli_query($con, $sql);

	$itemFirstReportRowArr = [];

	$itemLastReportRowArr = [];

	$itemsTotalsArr = [];

	$importedDatesArr = [];

	while ($rowOfReport = mysqli_fetch_array($outLetItemsForCountsTotals)) {
		$barCode = $rowOfReport['barCode'];

		$itemsTotalsArr[$barCode]['usageNoOfDays'] += 1;
		$itemsTotalsArr[$barCode]['usagePerDayTot'] += $rowOfReport['usagePerDay'];
		$itemsTotalsArr[$barCode]['usagePerGuestTot'] += $rowOfReport['usagePerGuest'];
		$itemsTotalsArr[$barCode]['easySalesTot'] += $rowOfReport['easySales'];
		$itemsTotalsArr[$barCode]['barControlTot'] += $rowOfReport['barControl'];

		$itemsTotalsArr[$barCode]['issueInTot'] += $rowOfReport['issueIn'];
		$itemsTotalsArr[$barCode]['adjmentTot'] += $rowOfReport['adjment'];

		if ($rowOfReport['adjForEnptyBottle'] == 1) {
			$itemsTotalsArr[$barCode]['adjForEnptyBottle'] = $rowOfReport['adjForEnptyBottle'];
		}


		if (!isset($itemFirstReportRowArr[$rowOfReport['barCode']])) {
			$itemFirstReportRowArr[$rowOfReport['barCode']] = $rowOfReport;
		}

		$itemLastReportRowArr[$rowOfReport['barCode']] = $rowOfReport;
	}
}
//End


//get Totals of easy sales amount
$dailyImportQrysql = "select SUM(sales) totalsSalesAmt FROM tbl_daily_import 
			 WHERE  outLetId = '" . $_GET['outLetId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' AND importDate	 BETWEEN '" . date('Y-m-d', strtotime($_GET['fromDate'])) . "' AND '" . date('Y-m-d', strtotime($_GET['toDate'])) . "' GROUP BY outLetId  ";
$dailyImportQry = mysqli_query($con, $dailyImportQrysql);

$dailyImportArr = mysqli_fetch_array($dailyImportQry);

$easySalesAmt = ($dailyImportArr['totalsSalesAmt'] ? $dailyImportArr['totalsSalesAmt'] : 0);
//end		 get Totals of easy sales amount	\


$sql = "SELECT guests, sales, importDate importDate FROM  tbl_daily_import

WHERE outLetId = '" . $_GET['outLetId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' AND
	importDate  between '" . date('Y-m-d', strtotime($_GET['fromDate'])) . "' AND '" . date('Y-m-d', strtotime($_GET['toDate'])) . "'  ";
$imptQry = mysqli_query($con, $sql);

$datesArr = [];
$salesTot = 0;
while ($imprtRes = mysqli_fetch_array($imptQry)) {
	$salesTot += $imprtRes['sales'];

	if (!in_array($row['importDate'], $datesArr)) {
		$guestsTotal += $imprtRes['guests'];
		$datesArr[] = $imprtRes['importDate'];
	}
}


///////////////////////////////////////////
//Adjustment && add notes related code goes here
$proRows = getOutLetProducts($_GET['outLetId']);

if (isset($_POST['saveBtn'])) {
	$proName = trim($_POST['item']);
	$proNameFilter = explode('(', $proName);
	$pId = trim(str_replace(')', '', $proNameFilter[1]));

	if ($pId && $_POST['notes']) {

		$sql = "SELECT * from tbl_revoutletnotes WHERE outLetId = '" . $_POST['outLetId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  AND pId = '" . $pId . "' AND noteDate = '" . date('Y-m-d', strtotime($_POST['fromDate'])) . "' ";
		$qry = mysqli_query($con, $sql);
		$res = mysqli_fetch_array($qry);

		if ($res) {
			$qry = " UPDATE tbl_revoutletnotes SET  
				notes = '" . $_POST['notes'] . "'
				WHERE  outLetId = '" . $_POST['outLetId'] . "' 
				AND account_id = '" . $_SESSION['accountId'] . "'
				AND pId = '" . $pId . "' AND noteDate = '" . date('Y-m-d', strtotime($_POST['fromDate'])) . "'  ";
			mysqli_query($con, $qry);
		} else {

			$qry = " INSERT INTO tbl_revoutletnotes SET  
				outLetId = '" . $_POST['outLetId'] . "',
				pId = '" . $pId . "',
				noteDate = '" . date('Y-m-d', strtotime($_POST['fromDate'])) . "',
				notes = '" . $_POST['notes'] . "',
				account_id = '" . $_SESSION['accountId'] . "' ";
			mysqli_query($con, $qry);
		}

		$url = "outlet_report_saleCenter.php?note=1&outLetId=" . $_POST['outLetId'] . "&fromDate=" . $_POST['fromDate'] . "&toDate=" . $_POST['toDate'] . "&account_id=" . $_SESSION['account_id'];

		echo "<script>window.location ='" . $url . "'</script>";
	}
	//End of add Notes part


	//start of add adjustment part				
	$sql = "SELECT barCode FROM tbl_products  
	WHERE id = '" . $pId . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
	$proQry = mysqli_query($con, $sql);
	$proRes = mysqli_fetch_array($proQry);

	$date = date('Y-m-d', strtotime($_POST['adjDate']));

	$sql = "SELECT * FROM tbl_daily_import_items 
	WHERE outLetId = '" . $_POST['outLetId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'	AND barCode = '" . $proRes['barCode'] . "' AND importedOn = '" . $date . "'  ";
	$qry = mysqli_query($con, $sql);
	$imptRes = mysqli_fetch_array($qry);

	if ($imptRes) {

		$updateCond = '';
		if ($_POST['adjForEnptyBottle'] == 1) {

			$usage = ($imptRes['openStock'] + $imptRes['issueIn'] + $imptRes['barControl']) - ($imptRes['closeStock'] + $_POST['qty']);


			$usageAvgArr = getAvgUsage($_POST['outLetId'], $proRes['barCode'], $usage, $date);
			$usageAvg = $usageAvgArr['usageAvg'];
			$usageCnt = $usageAvgArr['usageCnt'];

			$updateCond = " ,usagePerDay = '" . $usage . "',
						usageCnt = '" . $usageCnt . "',
						usageAvg = '" . $usageAvg . "' ";
		}

		$qry = " UPDATE tbl_daily_import_items SET  
				adjment = '" . $_POST['qty'] . "',
				adjForEnptyBottle = '" . $_POST['adjForEnptyBottle'] . "'
				" . $updateCond . "
			  WHERE id = '" . $imptRes['id'] . "' 
			  AND account_id = '" . $_SESSION['accountId'] . "'   ";
		mysqli_query($con, $qry);


		//insertion into log table  
		$allPostData = array(['adjustmentQty=>' . $_POST['qty']], ['outLetId=>' . $_POST['outLetId']], ['itemName=>' . $_POST['item']]);
		$jsonData =  json_encode($allPostData);
		$pageName = 'Revenue Center';
		$subSection = 'adjustment';
		$insQry = " INSERT INTO tbl_log SET 
					accountId = '" . $_SESSION['accountId'] . "',
					section = '" . $pageName . "',
					subSection = '$subSection',
					logData = '" . $jsonData . "',
					userId = '" . $_SESSION['id'] . "',
					date = '" . date('Y-m-d H:i:s') . "'  ";
		mysqli_query($con, $insQry);
		//end of insertion into log table  

		$url = "outlet_report_saleCenter.php?adj=1&outLetId=" . $_POST['outLetId'] . "&fromDate=" . $_POST['fromDate'] . "&toDate=" . $_POST['toDate'] . "&account_id=" . $_SESSION['accountId'];

		echo "<script>window.location ='" . $url . "'</script>";
	} else {
		$url = "outlet_report_saleCenter.php?error=1&outLetId=" . $_POST['outLetId'] . "&fromDate=" . $_POST['fromDate'] . "&toDate=" . $_POST['toDate'] . "&account_id=" . $_SESSION['accountId'];

		echo "<script>window.location ='" . $url . "'</script>";
	}
}


$typeOptions = '<select class="form-control" name="outLetType" onchange="this.form.submit()">';
$typeOptions .= '<option value="">' . showOtherLangText('Type') . '</option>';

$typeOptions .= '<option value="1" ' . ($_GET['outLetType'] == 1 ? 'selected="selected"' : '') . '>Bar Control</option>';
$typeOptions .= '<option value="2" ' . ($_GET['outLetType'] == 2 ? 'selected="selected"' : '') . '>Sales</option>';
$typeOptions .= '<option value="3" ' . ($_GET['outLetType'] == 3 ? 'selected="selected"' : '') . '>Usage</option>';

$typeOptions .= '</select>';
