<?php
include('inc/dbConfig.php'); //connection details
if (!isset($_SESSION['adminidusername'])) {
	echo "<script>window.location='login.php'</script>";
}



//for excel file upload with Other language
use Shuchkin\SimpleXLSX;

require_once 'SimpleXLSX.php';

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$rightSideLanguage = ($getLangType == 1) ? 1 : 0;

if (isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != '') {

	$xlsx = SimpleXLSX::parse($_FILES["uploadFile"]["tmp_name"]);

	// echo "<pre>";
	// print_r($xlsx->rows());
	// echo "</pre>";
	//die;
	$notFoundProducts = [];

	$i = 0;
	foreach ($xlsx->rows() as $allOutLetsRow) {
		if ($i == 0) {
			$i++;
			continue;
		}

		$rows[] = [
			'OutletName' => $allOutLetsRow[0],
			'Date' => $allOutLetsRow[1],
			'Sales' => $allOutLetsRow[2],
			'Guests' => $allOutLetsRow[3],
			'StockTakeBarCode' => $allOutLetsRow[4],
			'StockTakeQty' => $allOutLetsRow[5],
			'SalesBarCode' => $allOutLetsRow[6],
			'SalesQty' => $allOutLetsRow[7],
			'BCBarCode' => $allOutLetsRow[8],
			'BCQty' => $allOutLetsRow[9]
		];
	}


	//----------------------------------------------

	if (is_array($rows) && !empty($rows)) {

		unset($_SESSION['barCodesNotFound']);

		$keepOutLetWiseData = [];
		$outLetNamesArr = [];
		foreach ($rows as $allOutLetsRow) {
			if (!empty(trim($allOutLetsRow['OutletName'])) && !in_array($allOutLetsRow['OutletName'], $outLetNamesArr)) {
				$outletName = trim($allOutLetsRow['OutletName']);
				$outLetDetails = getRevOutLetDetByName($outletName);

				if (!$outLetDetails) {
					echo "<script>window.location='revenueCenterReport.php?error=1'</script>";
				}

				$outLetId = $outLetDetails['id'];
				$outLetNamesArr[] = $outletName;
			}
			if ($allOutLetsRow['Sales'] != '' || $allOutLetsRow['StockTakeBarCode'] != '' || $allOutLetsRow['SalesBarCode'] != '' || $allOutLetsRow['BCBarCode'] != '') {
				$keepOutLetWiseData[$outLetId][] = $allOutLetsRow;
			}
		} //end


		foreach ($keepOutLetWiseData as $outLetId => $outLetRows) {
			$dataRows = getOutLetFormatedData($outLetId, $outLetRows);

			$formatedRows = $dataRows['formatedRows'];

			insertUpdateOutLetReport($outLetId, $formatedRows, $outLetRows[0]);
		}

		echo "<script>window.location='revenueCenterReport.php?import=1'</script>";
	}
}

//////////////////End excel file upload/////////////////////////////////////////
