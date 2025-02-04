<?php

$accountId = $_GET['account_id'];
$sqlQry = " SELECT * FROM tbl_suppliers WHERE account_id = '".$accountId."' ";
$supplierRes = mysqli_query($con, $sqlQry);


$sqlQry = " SELECT * FROM tbl_deptusers WHERE account_id = '".$accountId."' ";
$deptUserRes = mysqli_query($con, $sqlQry);

$sqlQry = " SELECT * FROM tbl_stores WHERE account_id = '".$accountId."' ";
$storeRes = mysqli_query($con, $sqlQry);



if (isset($_POST['designation_name'])) 
{	
	$sql = " SELECT * FROM tbl_designation WHERE designation_name = '".$_POST['designation_name']."' AND account_id = '".$accountId."'  ";
	$sqlResult = mysqli_query($con, $sql);
	if( mysqli_fetch_array($sqlResult) ) 
	{
		$_SESSION['warning'] = "Designation name already exist can't be created of the same name";
		echo "<script>window.location='addDesignation.php?account_id=".$_GET['account_id']."'</script>";
		die;
	}
	else
	{
		$fieldsArr = [
			'designation_name' => $_POST['designation_name'],
			'account_id' => $accountId

		];

 		insert('tbl_designation', $fieldsArr);

		$designationId = mysqli_insert_id($con);

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
		$_SESSION['added'] = "Designation Successfully added";
		echo "<script>window.location='listDesignation.php?account_id=".$_GET['account_id']." '</script>";
		die;
	}
}

$sqlQry = " SELECT * FROM tbl_suppliers WHERE account_id = '".$accountId."' ";
$supplierRes = mysqli_query($con, $sqlQry);


$sqlQry = " SELECT * FROM tbl_deptusers WHERE account_id = '".$accountId."' ";
$deptUserRes = mysqli_query($con, $sqlQry);

$sqlQry = " SELECT * FROM tbl_stores WHERE account_id = '".$accountId."' ";
$storeRes = mysqli_query($con, $sqlQry);


?>