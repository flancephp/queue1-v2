<?php 
$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'Designation' AND type_id = '0' AND designation_id = '".$_SESSION['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if ($permissionRow)
{
   // echo "<script>window.location='index.php'</script>";
	//exit;
}


$accountId = $_SESSION['accountId'];

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

$designationType = $designationRow['is_mobile'];

$sqlQry = " SELECT * FROM tbl_designation_section_permission WHERE account_id = '".$accountId."' AND designation_id = '".$_GET['id']."'  ";
$des_sec_permission_Res = mysqli_query($con, $sqlQry);
$selectedSectionArr = [];
$checkSectionVersion = [];
while ($des_sec_permission_Row = mysqli_fetch_array($des_sec_permission_Res)) 
{
	$selectedSectionArr[] = $des_sec_permission_Row['designation_section_list_id'];

	$checkSectionVersion[] = $des_sec_permission_Row['is_mobile'];
}


///////////////////////////////////////////////////

if (isset($_POST['designation_name'])) 
{	
	$sql = " SELECT * FROM tbl_designation WHERE id != '".$_GET['id']."' AND designation_name = '".$_POST['designation_name']."' AND account_id = '".$accountId."'  ";
	$sqlResult = mysqli_query($con, $sql);
	if( mysqli_fetch_array($sqlResult) ) 
	{
		echo "<script>window.location='editDesignation.php?error=1&id=".$_GET['id']."'</script>";
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
					'is_mobile' => $designationType,
					'account_id' => $accountId

			];
 			insert('tbl_designation_section_permission', $fieldsArr);
 
 			//mobile Section
			if ($sectionCheckKey == 1 && $designationType == '1') 
			{
				foreach ($mobileSectionArr as $key => $mobileSectionEachRow) 
				{
					
					$name = $mobileSectionEachRow['name'];

					if( isset($_POST[$name]) )
					{

						$fieldsArr = [
							'designation_id' => $designationId,
							'designation_section_permission_id' => $sectionCheckKey,
							'type' => $name,
							'type_id' => $_POST[$name],
							'is_mobile' => 1,
							'account_id' => $accountId

						];

		 				insert('tbl_designation_sub_section_permission', $fieldsArr);
				    }

				}
			
			}//end of mobile section check key 1

			//New Order Section
			if ($sectionCheckKey == 1 && $designationType == '0' ) 
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

				foreach ($orderArr as $key => $orderArrEachRow) 
				{
					
					$name = $orderArrEachRow['name'];

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

				foreach ($requisitionArr as $key => $requisitionArrEachRow) 
				{
					
					$name = $requisitionArrEachRow['name'];

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
		
		echo "<script>window.location='listDesignation.php?edit=1'</script>";
	}
}


	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'order_supplier' AND designation_id = '".$_GET['id']."' AND account_id = '".$_SESSION['accountId']."' ";
	$sqlQrySupRes = mysqli_query($con, $sql);
	while($sqlQrySupRow = mysqli_fetch_array($sqlQrySupRes))
	{
		$supIdsArr[] = $sqlQrySupRow['type_id'];
	}


	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'access_payment' AND designation_id = '".$_GET['id']."' AND account_id = '".$_SESSION['accountId']."' ";
	$accessPaymentRes = mysqli_query($con, $sql);
	$accessPaymentRow = mysqli_fetch_array($accessPaymentRes);


	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'member' AND designation_id = '".$_GET['id']."' AND account_id = '".$_SESSION['accountId']."' ";
	$sqlQryMemRes = mysqli_query($con, $sql);
	while($sqlQryMemRow = mysqli_fetch_array($sqlQryMemRes))
	{
		$memIdsArr[] = $sqlQryMemRow['type_id'];
	}



	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'access_invoice' AND designation_id = '".$_GET['id']."' AND account_id = '".$_SESSION['accountId']."' ";
	$accessInvoiceRes = mysqli_query($con, $sql);
	$AccessInvoiceRow = mysqli_fetch_array($accessInvoiceRes);




	$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'stock' AND designation_id = '".$_GET['id']."' AND account_id = '".$_SESSION['accountId']."' ";
	$sqlQryStoreRes = mysqli_query($con, $sql);
	while($sqlQryStoreRow = mysqli_fetch_array($sqlQryStoreRes))
	{
		$storeIdsArr[] = $sqlQryStoreRow['type_id'];
	}


?>