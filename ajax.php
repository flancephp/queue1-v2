<?php include('inc/dbConfig.php'); //connection details


//Item note starts here
if ($_POST['actionType'] == 'updateProductNote') {

	$sql = "SELECT * from tbl_revoutletnotes WHERE outLetId = '" . $_POST['outLetId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  AND pId = '" . $_POST['pId'] . "' AND noteDate = '" . date('Y-m-d', strtotime($_POST['fromDate'])) . "' ";
	$qry = mysqli_query($con, $sql);
	$res = mysqli_fetch_array($qry);

	if ($res) {
		$qry = " UPDATE tbl_revoutletnotes SET  
				notes = '" . $_POST['notes'] . "'
				WHERE  outLetId = '" . $_POST['outLetId'] . "' 
				AND account_id = '" . $_SESSION['accountId'] . "'
				AND pId = '" . $_POST['pId'] . "' AND noteDate = '" . date('Y-m-d', strtotime($_POST['fromDate'])) . "'  ";
		mysqli_query($con, $qry);
	} else {
		$qry = " INSERT INTO tbl_revoutletnotes SET  
				outLetId = '" . $_POST['outLetId'] . "',
				pId = '" . $_POST['pId'] . "',
				noteDate = '" . date('Y-m-d', strtotime($_POST['fromDate'])) . "',
				notes = '" . $_POST['notes'] . "',
				account_id = '" . $_SESSION['accountId'] . "' ";
		mysqli_query($con, $qry);
	}
}
//end item notes

if (isset($_POST['pId']) && $_POST['pId'] > 0  && $_POST['deptId'] > 0 && $_SESSION['id'] > 0) {

	$sql = " SELECT * FROM  tbl_recusition_items_temp WHERE deptId = '" . $_POST['deptId'] . "' AND `userId` = '" . $_SESSION['id'] . "' AND pId='" . $_POST['pId'] . "' ";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	if ($row) {

		if (empty($_POST['qty'])) {
			$sql = " DELETE FROM `tbl_recusition_items_temp`  WHERE `id` = '" . $row['id'] . "'  ";
		} else {
			$sql = " UPDATE `tbl_recusition_items_temp` SET `qty` = " . $_POST['qty'] . " WHERE `id` = '" . $row['id'] . "'  ";
		}
	} elseif ($_POST['qty'] > 0) {

		$sql = "INSERT INTO `tbl_recusition_items_temp` SET
		deptId = '" . $_POST['deptId'] . "'
		,`userId` = '" . $_SESSION['id'] . "'
		, `pId` = '" . $_POST['pId'] . "' 
		, `qty` = '" . $_POST['qty'] . "' ";
	}

	mysqli_query($con, $sql);


	$sql = " SELECT * FROM  tbl_recusition_items_temp WHERE deptId = '" . $_POST['deptId'] . "' AND `userId` = '" . $_SESSION['id'] . "'  ";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	if ($row) {
		echo '1';
	} else {
		echo '0';
	}
}



if (isset($_POST['rawItem']) && $_POST['rawItem'] > 0) {
	$sql = " SELECT r.convtPid, p.itemName FROM  tbl_rawitem_products r INNER JOIN tbl_products p ON(p.id = r.convtPid) WHERE r.rawPid = '" . $_POST['rawItem'] . "'  ";
	$result = mysqli_query($con, $sql);

	$select = '<select name="convertItem" id="convertItem" onchange="showQty();" class="form-control" style="width:300px;"
	
	  oninvalid="this.setCustomValidity(' . showOtherLangText('Please select an item in the list.') . ')"
                        required
	>';

	$select .= '<option value="">' . showOtherLangText('Select') . '</option>';
	while ($row = mysqli_fetch_array($result)) {
		$select .= '<option value="' . $row['convtPid'] . '">' . $row['itemName'] . '</option>';
	}
	$select .= '</select>';

	$response['selectData'] = $select;

	$sql = " SELECT qty FROM tbl_stocks WHERE pId = '" . $_POST['rawItem'] . "'  ";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);

	$response['stockQty'] = $row['qty'] > 0 ? $row['qty'] : 0;

	echo json_encode($response);
}


if (isset($_POST['rawItemId']) && $_POST['rawItemId'] > 0) {
	$sql = "SELECT s.*  FROM tbl_stocks s 
								INNER JOIN tbl_products p ON(s.pId = p.id)  WHERE p.id = '" . $_POST['rawItemId'] . "' ";
	$qry = mysqli_query($con, $sql);
	$res = mysqli_fetch_array($qry);

	if ($res) {
		echo round((($res['stockPrice'] * $_POST['qtyToCont']) / $_POST['qtyCont']), 2);
	} else {
		echo '0';
	}
}

if (isset($_POST['convertItem']) && $_POST['convertItem'] > 0) {
	$sql = "SELECT s.*  FROM tbl_stocks s 
								INNER JOIN tbl_products p ON(s.pId = p.id)  WHERE p.id = '" . $_POST['convertItem'] . "' ";
	$qry = mysqli_query($con, $sql);
	$res = mysqli_fetch_array($qry);

	if ($res) {
		echo $res['qty'];
	} else {
		echo '0';
	}
}


//show price in dollar
if (isset($_POST['currencyId']) && $_POST['currencyId'] > 0 && $_POST['price'] > 0) {
	$sql = "SELECT *  FROM tbl_currency WHERE id = '" . $_POST['currencyId'] . "' ";
	$qry = mysqli_query($con, $sql);
	$res = mysqli_fetch_array($qry);

	if ($res) {
		echo getPrice(($_POST['price'] / $res['amt']), 4);
	} else {
		echo ' ' . showOtherLangText('Something wrong') . ' ';
	}
}


//Outlet report ajax in adjustment area
if (isset($_POST['itemNameVal']) && $_POST['itemNameVal']) {

	$proName = trim($_POST['itemNameVal']);
	$proNameFilter = explode('(', $proName);
	$pId = trim(str_replace(')', '', $proNameFilter[1]));

	$sql = "SELECT *  FROM tbl_outlet_items WHERE pId = '" . $pId . "' AND outLetId = '" . $_POST['outLetId'] . "' AND  account_id = '" . $_SESSION['accountId'] . "' ";
	$qry = mysqli_query($con, $sql);
	$res = mysqli_fetch_array($qry);

	echo $res['itemType'];
}
