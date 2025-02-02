<?php
include('../inc/dbConfig.php'); //connection details

if (!isset($_SESSION['id']) ||  $_SESSION['id'] < 1) {
	echo "<script>window.location.href='" . $siteUrl . "';</script>";
	exit;
}


if (isset($_POST['pId']) && $_POST['pId'] > 0  && $_POST['stockTakeId'] > 0 && $_SESSION['id'] > 0) {

	$sql = " SELECT * FROM  tbl_mobile_items_temp WHERE stockTakeId = '" . $_POST['stockTakeId'] . "' AND `stockTakeType` = '" . $_POST['stockTakeType'] . "' AND `userId` = '" . $_SESSION['id'] . "' AND pId='" . $_POST['pId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' AND status=0 ";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);

	$amt = isset($_POST['amt']) ? $_POST['amt'] : 0;
	$curId = isset($_POST['curId']) ? $_POST['curId'] : 0;

	if ($row) {

		if ($_POST['qty'] == '') {

			$sql = " DELETE FROM `tbl_mobile_items_temp`  WHERE `id` = '" . $row['id'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
			mysqli_query($con, $sql);
		} else {

			$sql = " UPDATE `tbl_mobile_items_temp` SET `qty` = " . $_POST['qty'] . ", `amt` = '" . $amt . "', `curId` = '" . $curId . "' WHERE `id` = '" .
				$row['id'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
			mysqli_query($con, $sql);
		}

		$processId = $row['processId'];
	} elseif ($_POST['qty'] != '') {
		$sql = " SELECT id FROM  tbl_mobile_time_track WHERE stockTakeId = '" . $_POST['stockTakeId'] . "' AND `userId` = '" . $_SESSION['id'] . "' AND `stockTakeType` = '" . $_POST['stockTakeType'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  AND status = '0' ";
		$result = mysqli_query($con, $sql);
		$row = mysqli_fetch_array($result);
		//$processId = 0;
		if ($row) {
			$processId = $row['id'];
		}

		// if($_POST['stockTakeType'] == 4)
		// {
		// 	$sql = "SELECT SUM(od.qty) totalProQty FROM tbl_order_details od INNER JOIN tbl_orders o 
		// 			ON(o.id = od.ordId) AND o.account_id = od.account_id 
		// 		WHERE od.pId = '".$_POST['pId']."' AND o.id != '".$_POST['stockTakeId']."' AND od.account_id = '".$_SESSION['accountId']."' AND o.ordType = 2 AND o.status != 2 GROUP BY od.pId ";
		// 	$ordQry = mysqli_query($con, $sql);
		// 	$ordRow = mysqli_fetch_array($ordQry);

		// 	$sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$_POST['pId']."'  AND account_id = '".$_SESSION['accountId']."' ";
		// 	$stkQry = mysqli_query($con, $sql);
		// 	$stkRow = mysqli_fetch_array($stkQry);

		// 	$stockQty =  $stkRow['qty'] - $ordRow['totalProQty'];
		// }							

		// if( ($_POST['stockTakeType'] != 4) || ($stockQty >= $_POST['qty']) && $processId > 0 )
		// {
		if (($_POST['stockTakeType'] == 4) || $processId > 0) {
			$sql = "INSERT INTO `tbl_mobile_items_temp` SET
						stockTakeId = '" . $_POST['stockTakeId'] . "'
						,`account_id` = '" . $_SESSION['accountId'] . "' 
						,`userId` = '" . $_SESSION['id'] . "'
						, `pId` = '" . $_POST['pId'] . "' 
						, `qty` = '" . $_POST['qty'] . "'
						, `stockTakeType` = '" . $_POST['stockTakeType'] . "'
						, `processId` = '" . $processId . "'
						, `amt` = '" . $amt . "'
						, `curId` = '" . $curId . "'
					";

			mysqli_query($con, $sql);
		}
	}




	if ($processId > 0) {
		$sql = " SELECT count(*) totalRows FROM  tbl_mobile_items_temp WHERE stockTakeId = '" . $_POST['stockTakeId'] . "' AND `userId` = '" . $_SESSION['id'] . "' AND `stockTakeType` = '" . $_POST['stockTakeType'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' AND status=0 AND processId = '" . $processId . "'  ";
		$result = mysqli_query($con, $sql);
		$row = mysqli_fetch_array($result);

		echo $row['totalRows'];
	} else {
		echo '0';
	}
}

if (isset($_POST['revId'])) {
	$sql = "SELECT du.name as outletName, du.account_id, rcd.id as outLetId  FROM tbl_revenue_center_departments rcd
		INNER JOIN tbl_deptusers du ON(du.id = rcd.deptId) AND du.account_id = rcd.account_id 
		INNER JOIN tbl_revenue_center rc ON(rc.id = rcd.revCenterId) AND rc.account_id = rcd.account_id  AND rc.id = '" . $_POST['revId'] . "'
		WHERE  du.account_id = '" . $_SESSION['accountId'] . "' 
		
		GROUP BY rcd.id order by du.name  ";

	$qry = mysqli_query($con, $sql);

	$options = '<option value="">' . showOtherLangText('Select') . '</option>';
	while ($res = mysqli_fetch_array($qry)) {
		$options .= "<option value='" . $res['outLetId'] . "'>" . $res['outletName'] . "</option>";
	}

	echo $options;
}


if (isset($_POST['resetCount'])   && $_POST['stockTakeId'] > 0  && $_POST['stockTakeType'] > 0 && $_SESSION['id'] > 0) {
	$sql = " DELETE FROM `tbl_mobile_items_temp`  WHERE stockTakeId = '" . $_POST['stockTakeId'] . "' AND `userId` = '" . $_SESSION['id'] . "' AND `stockTakeType` = '" . $_POST['stockTakeType'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
	mysqli_query($con, $sql);

	//$sql=" DELETE FROM  tbl_mobile_time_track WHERE stockTakeId = '".$_POST['stockTakeId']."' AND `userId` = '".$_SESSION['id']."' AND `stockTakeType` = '".$_POST['stockTakeType']."'  AND account_id = '".$_SESSION['accountId']."'  ";
	//$result = mysqli_query($con, $sql);
}
