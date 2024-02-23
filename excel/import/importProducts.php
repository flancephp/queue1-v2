<?php
session_start();
include('/home2/queueone/public_html/development/inc/dbConfig.php'); //connection details

use Shuchkin\SimpleXLSX;

	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', true);
	
	require_once __DIR__.'/../excelReader/SimpleXLSX.php';
if( isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != '' )
{	
	
	if ($xlsx = SimpleXLSX::parse($_FILES["uploadFile"]["tmp_name"])) {
	
	//echo "<pre>";
	//print_r($xlsx->rows());
	//echo "</pre>";
	//die;
	
		$i=0;
		foreach($xlsx->rows() as $row)
		{
				
			if($i == 0)
			{
				$i++;
				continue;
			}			

			$row = [
			'Item' => $row[0],
			'BarCode' => $row[1],
			'Category' => $row[2],
			'SubCategory' => $row[3],
			'Storage' => $row[4],
			'Supplier' => $row[5],
			'Departments' => $row[6],
			'PUnit' => $row[7],
			'Factor' => $row[8],
			'CUnit' => $row[9],
			'LastPrice' => $row[10],
			'MinLevel' => $row[11],
			'MaxLevel' => $row[12],
			'Photo' => $row[13]
			];
			
			//--------------------------
			
			
			$sql = "select * FROM tbl_products WHERE barCode = '".trim($row['BarCode'])."' AND account_id = '".$_SESSION['accountId']."'  ";
			$result = mysqli_query($con, $sql);
			$res = mysqli_fetch_array($result);
			
			$imgFiled = '';
			
			
			//photo section goes here
				if($row['Photo'] != '' && file_exists($bulkImgUploadPath. trim($row['Photo'])) )
				{
					if($res['imgName'] != ''){
						@unlink($bulkImgUploadPath.$res['imgName']);
					}
					
					$ext  = end(explode(".", $row['Photo']));
					$newFile = time().'_'.$row['BarCode'].'.'.$ext;
					
					copy($bulkImgUploadPath . $row['Photo'], $target_dir . $accountImgPath. '/'. $newFile);
					
					$imgFiled = " ,`imgName` = '".$newFile."' ";
					
					@unlink($bulkImgUploadPath . $row['Photo']);
				}
			//end photo section
				
				
				
			if($res)
			{
				$insrtOrUpdate = " UPDATE ";
				$where = " WHERE id = '".$res['id']."'  AND account_id = '".$_SESSION['accountId']."' ";
				
				$sqlSet = " DELETE FROM tbl_productdepartments WHERE productId  = '".$res['id']."'  AND account_id = '".$_SESSION['accountId']."' ";
			    mysqli_query($con, $sqlSet);
				
				$sqlSet = " DELETE FROM tbl_productsuppliers WHERE productId  = '".$res['id']."'  AND account_id = '".$_SESSION['accountId']."' ";
			    mysqli_query($con, $sqlSet);
			}
			else
			{
				$insrtOrUpdate = " INSERT INTO ";
				$where = " ";
			}
			
			$sqlSet = " SELECT * FROM tbl_category WHERE name = '".trim($row['Category'])."'  AND account_id = '".$_SESSION['accountId']."'  ";
			$resultSet = mysqli_query($con, $sqlSet);
			$parentCattRow = mysqli_fetch_array($resultSet);
			
			$sqlSet = " SELECT * FROM tbl_category WHERE name = '".trim($row['SubCategory'])."'  AND account_id = '".$_SESSION['accountId']."'  ";
			$resultSet = mysqli_query($con, $sqlSet);
			$childCattRow = mysqli_fetch_array($resultSet);
					
			$sqlSet = " SELECT * FROM tbl_stores WHERE name = '".trim($row['Storage'])."'  AND account_id = '".$_SESSION['accountId']."'  ";
			$resultSet = mysqli_query($con, $sqlSet);
			$storageDeptRow = mysqli_fetch_array($resultSet);
								
			$sqlSet = " SELECT * FROM tbl_suppliers WHERE name = '".trim($row['Supplier'])."' AND account_id = '".$_SESSION['accountId']."'   ";
			$resultSet = mysqli_query($con, $sqlSet);
			$supRow = mysqli_fetch_array($resultSet);
			
			
			$sql = $insrtOrUpdate." `tbl_products` SET
				`itemName` = '".$row['Item']."',
				`barCode` = '".$row['BarCode']."',
				`unitP` = '".$row['PUnit']."',
				`factor` = '".$row['Factor']."',
				`unitC` = '".$row['CUnit']."',
				`storageDeptId` = '".$storageDeptRow['id']."',
				`parentCatId` = '".$parentCattRow['id']."',
				`catId` = '".$childCattRow['id']."',
				`price` = '".$row['LastPrice']."',
				`minLevel` = '".$row['MinLevel']."',
				`maxLevel` = '".$row['MaxLevel']."',
				`status` = 1,
				account_id = '".$_SESSION['accountId']."' "
				.$imgFiled
				.$where;
							
				mysqli_query($con, $sql);
				
				if(!$res)
				{
					$productId = mysqli_insert_id($con);
				}
				else
				{
					$productId = $res['id'];
				}
			
			//Departments
			$deptsNamesArr = explode(',', $row['Departments']);	
			
			foreach($deptsNamesArr as $deptName)
			{
				$sqlSet = " SELECT id FROM tbl_department WHERE name = '".trim($deptName)."' AND account_id = '".$_SESSION['accountId']."'  ";
				$resultSet = mysqli_query($con, $sqlSet);
				$deptRow = mysqli_fetch_array($resultSet);
					$sql = "INSERT INTO `tbl_productdepartments` SET
					`productId` = '".$productId."',
					`deptId` = '".$deptRow['id']."',
					`account_id` = '".$_SESSION['accountId']."'  ";
					mysqli_query($con, $sql);
			}
			//End departments
			
			//Suppliers
			
			$supplierArr = explode(',', $row['Supplier']);	
			foreach($supplierArr as $supplier)
			{
				$sqlSet = " SELECT id FROM tbl_suppliers WHERE name = '".trim($supplier)."' AND account_id = '".$_SESSION['accountId']."'  ";
				$resultSet = mysqli_query($con, $sqlSet);
				$supRow = mysqli_fetch_array($resultSet);
				$sql = "INSERT INTO `tbl_productsuppliers` SET
				`productId` = '".$productId."',
				`supplierId` = '".$supRow['id']."',
				`account_id` = '".$_SESSION['accountId']."'  ";
												
				mysqli_query($con, $sql);
			}
			//End Suppliers
			
		
			//---------------------------
			
		}

		echo "<script>window.location='../../listProducts.php?imported=1'</script>";
		
	}
	
}
?>