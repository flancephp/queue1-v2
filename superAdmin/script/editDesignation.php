<?php 

$accountId = $_GET['account_id'];
$sqlQry = " SELECT * FROM tbl_suppliers WHERE account_id = '".$accountId."' ";
$supplierRes = mysqli_query($con, $sqlQry);

$sqlQry = " SELECT * FROM tbl_deptusers WHERE account_id = '".$accountId."' ";
$deptUserRes = mysqli_query($con, $sqlQry);

$sqlQry = " SELECT * FROM tbl_stores WHERE account_id = '".$accountId."' ";
$storeRes = mysqli_query($con, $sqlQry);

$sqlQry = " SELECT * FROM tbl_designation WHERE account_id = '".$accountId."' AND id = '".$_GET['id']."' ";
$designationRes = mysqli_query($con, $sqlQry);
$designationRow = mysqli_fetch_array($designationRes);
$designationName = $designationRow['designation_name'];

$sqlQry = " SELECT * FROM tbl_designation_section_permission WHERE account_id = '".$accountId."' AND designation_id = '".$_GET['id']."' ";
$des_sec_permission_Res = mysqli_query($con, $sqlQry);
$selectedSectionArr = [];
while ($des_sec_permission_Row = mysqli_fetch_array($des_sec_permission_Res)) 
{
	$selectedSectionArr[] = $des_sec_permission_Row['designation_section_list_id'];
}
///////////////////////////////////////////////////

if (isset($_POST['designation_name'])) 
{	
	$sql = " SELECT * FROM tbl_designation WHERE id != '".$_GET['id']."' AND designation_name = '".$_POST['designation_name']."' AND account_id = '".$accountId."'  ";
	$sqlResult = mysqli_query($con, $sql);
	if( mysqli_fetch_array($sqlResult) ) 
	{
		$_SESSION['warning'] = "The Designation name already exist in our records";
		echo "<script>window.location='editDesignation.php?account_id=".$_GET['account_id']." '</script>";
		die;
	}
	else
	{
		
		$fieldsArr = ['designation_name' => $_POST['designation_name']];
		$whereCond = ['id' => $_GET['id'], 'account_id' => $accountId];
 		updateTable('tbl_designation', $fieldsArr, $whereCond);

		$designationId = $_GET['id'];
		
		$whereCond = ['designation_id' => $designationId, 'account_id' => $accountId];
 		delete('tbl_designation_section_permission', $whereCond);

 		delete('tbl_designation_sub_section_permission', $whereCond);
		


		foreach($_POST['section_check'] as $sectionCheckKey) 
		{
			$fieldsArr = [
					'designation_id' => $designationId,
					'designation_section_list_id' => $sectionCheckKey,
					'account_id' => $accountId

			];
 			insert('tbl_designation_section_permission', $fieldsArr);
 

			//New Order Section
			if ($sectionCheckKey == 1) 
			{
				$sectionCheckValue = 'order_supplier';

				foreach($_POST['new_supplierCheck'] as $supplierCheckedKey)
				{
					$fieldsArr = [
					'designation_id' => $designationId,
					'designation_section_permission_id' => $sectionCheckKey,
					'type' => $sectionCheckValue,
					'type_id' => $supplierCheckedKey,
					'account_id' => $accountId
					];

					insert('tbl_designation_sub_section_permission', $fieldsArr);
				}

				if (isset($_POST['access_payment'])) 
				{
					$type = 'access_payment';
					$type_id = $_POST['access_payment'];

					$fieldsArr = [
					'designation_id' => $designationId,
					'designation_section_permission_id' => $sectionCheckKey,
					'type' => $type,
					'type_id' => $type_id,
					'account_id' => $accountId
					];

					insert('tbl_designation_sub_section_permission', $fieldsArr);
				}

			}//end of section check key 1

			//New Requisition Section
			if ($sectionCheckKey == 2) 
			{
				$sectionCheckValue = 'member';

				foreach($_POST['new_memberCheck'] as $memberCheckedKey)
				{
					$fieldsArr = [
					'designation_id' => $designationId,
					'designation_section_permission_id' => $sectionCheckKey,
					'type' => $sectionCheckValue,
					'type_id' => $memberCheckedKey,
					'account_id' => $accountId
					];

					insert('tbl_designation_sub_section_permission', $fieldsArr);
				}

				if (isset($_POST['access_invoice'])) 
				{
					$type = 'access_invoice';
					$type_id = $_POST['access_invoice'];

					$fieldsArr = [
					'designation_id' => $designationId,
					'designation_section_permission_id' => $sectionCheckKey,
					'type' => $type,
					'type_id' => $type_id,
					'account_id' => $accountId
					];

					insert('tbl_designation_sub_section_permission', $fieldsArr);
				}
			}//end of section check key 2

			//Running Task Section
			if ($sectionCheckKey == 3) 
			{
				foreach ($runningTaskArr as $key => $runningTaskEachRow) 
				{
					$name = $runningTaskEachRow['name'];
					
					if( isset($_POST[$name]) )
					{
						$fieldsArr = [
						'designation_id' => $designationId,
						'designation_section_permission_id' => $sectionCheckKey,
						'type' => $name,
						'type_id' => $_POST[$name],
						'account_id' => $accountId
						];

						insert('tbl_designation_sub_section_permission', $fieldsArr);
						
				    }

				}
			
			}//end of section check key 3

			//History Section
			if ($sectionCheckKey == 4) 
			{
				foreach ($historyArr as $key => $historyEachRow) 
				{
					$name = $historyEachRow['name'];
					
					if( isset($_POST[$name]) )
					{
						$fieldsArr = [
						'designation_id' => $designationId,
						'designation_section_permission_id' => $sectionCheckKey,
						'type' => $name,
						'type_id' => $_POST[$name],
						'account_id' => $accountId
						];

						insert('tbl_designation_sub_section_permission', $fieldsArr);
						
					}
				}
				
			}//end of section check key 4

			//Stock Section
			if ($sectionCheckKey == 5) 
			{

				foreach($_POST['store_check'] as $stockCheckedKey)
				{
					$sectionCheckValue = 'stock';

					$fieldsArr = [
						'designation_id' => $designationId,
						'designation_section_permission_id' => $sectionCheckKey,
						'type' => $sectionCheckValue,
						'type_id' => $stockCheckedKey,
						'account_id' => $accountId
					];

					insert('tbl_designation_sub_section_permission', $fieldsArr);
	
				}
				
				foreach ($stockArr as $key => $stockArrEachRow) 
				{
					$name = $stockArrEachRow['name'];

					if( isset($_POST[$name]) )
					{
						$fieldsArr = [
						'designation_id' => $designationId,
						'designation_section_permission_id' => $sectionCheckKey,
						'type' => $name,
						'type_id' => $_POST[$name],
						'account_id' => $accountId
						];

						insert('tbl_designation_sub_section_permission', $fieldsArr);
					}
				}
			}//end of section check key 5
			
			//New Stocktake Section
			if ($sectionCheckKey == 6) 
			{
				foreach ($newStockTakeArr as $key => $newStockTakeEachRow) 
				{
					$name = $newStockTakeEachRow['name'];

					if( isset($_POST[$name]) )
					{
						$fieldsArr = [
						'designation_id' => $designationId,
						'designation_section_permission_id' => $sectionCheckKey,
						'type' => $name,
						'type_id' => $_POST[$name],
						'account_id' => $accountId
						];

						insert('tbl_designation_sub_section_permission', $fieldsArr);
							
					}
				}
			}//end of section check key 6

			//Revenue Center Section
			if ($sectionCheckKey == 7) 
			{
				foreach ($revCenterArr as $key => $revCenterArrEachRow) 
				{
					$name = $revCenterArrEachRow['name'];

					if( isset($_POST[$name]) )
					{
						$fieldsArr = [
						'designation_id' => $designationId,
						'designation_section_permission_id' => $sectionCheckKey,
						'type' => $name,
						'type_id' => $_POST[$name],
						'account_id' => $accountId
						];

						insert('tbl_designation_sub_section_permission', $fieldsArr);
						
					}
				}
			}//end of section check key 7

			//Setup Section
			if ($sectionCheckKey == 8) 
			{
				foreach ($setupArr as $key => $setupArrEachRow) 
				{
					$name = $setupArrEachRow['name'];

					if( isset($_POST[$name]) )
					{
						$fieldsArr = [
						'designation_id' => $designationId,
						'designation_section_permission_id' => $sectionCheckKey,
						'type' => $name,
						'type_id' => $_POST[$name],
						'account_id' => $accountId
						];

						insert('tbl_designation_sub_section_permission', $fieldsArr);
					}
				}
			}//end of section check key 8
		}//End of section_check foreach
		$_SESSION['updated'] = "Designation Successfully updated"; 
		echo "<script>window.location='listDesignation.php?account_id=".$_GET['account_id']." '</script>";
		die;
	}
}


	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'order_supplier' AND designation_id = '".$_GET['id']."' AND account_id = '".$accountId."' ";
	$sqlQrySupRes = mysqli_query($con, $sql);
	while($sqlQrySupRow = mysqli_fetch_array($sqlQrySupRes))
	{
		$supIdsArr[] = $sqlQrySupRow['type_id'];
	}


	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'access_payment' AND designation_id = '".$_GET['id']."' AND account_id = '".$accountId."' ";
	$accessPaymentRes = mysqli_query($con, $sql);
	$accessPaymentRow = mysqli_fetch_array($accessPaymentRes);


	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'member' AND designation_id = '".$_GET['id']."' AND account_id = '".$accountId."' ";
	$sqlQryMemRes = mysqli_query($con, $sql);
	while($sqlQryMemRow = mysqli_fetch_array($sqlQryMemRes))
	{
		$memIdsArr[] = $sqlQryMemRow['type_id'];
	}



	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'access_invoice' AND designation_id = '".$_GET['id']."' AND account_id = '".$accountId."' ";
	$accessInvoiceRes = mysqli_query($con, $sql);
	$AccessInvoiceRow = mysqli_fetch_array($accessInvoiceRes);




	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'stock' AND designation_id = '".$_GET['id']."' AND account_id = '".$accountId."' ";
	$sqlQryStoreRes = mysqli_query($con, $sql);
	while($sqlQryStoreRow = mysqli_fetch_array($sqlQryStoreRes))
	{
		$storeIdsArr[] = $sqlQryStoreRow['type_id'];
	}


?>