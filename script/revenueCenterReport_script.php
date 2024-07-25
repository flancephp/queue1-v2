<?php
//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


$rightSideLanguage = ($getLangType == 1) ? 1 : 0; 

if (!isset($_SESSION['adminidusername'])) 
{
echo "<script>window.location='login.php'</script>";
}

//check page permission
$checkPermission = permission_denied_for_section_pages($_SESSION['designation_id'],$_SESSION['accountId']);

if (!in_array('7',$checkPermission))
{
echo "<script>window.location='index.php'</script>";
}

$accessGuestData = access_guest($_SESSION['designation_id'],$_SESSION['accountId']);

$accessEzeeData = access_ezee_data($_SESSION['designation_id'],$_SESSION['accountId']);

$accessImportRevCenterData = access_import_revenueCenter_data($_SESSION['designation_id'],$_SESSION['accountId']);

if( !isset($_GET['fromDate'])  )
{
$_GET['fromDate'] = date('d-m-Y', strtotime('-1 days') );
$_GET['toDate'] = date('d-m-Y', strtotime('-1 days') );
}


if( isset($_GET['delId']) && $_GET['delId'] )
{

	$sql = "select * FROM tbl_category WHERE parentId = '".$_GET['delId']."' AND account_id = '".$_SESSION['accountId']."'   ";
	$result = mysqli_query($con, $sql);
	$subCatExist = mysqli_fetch_array($result);

if(!$subCatExist)
	{
		$sql = "DELETE FROM tbl_category  WHERE id='".$_GET['delId']."'  AND account_id = '".$_SESSION['accountId']."' ";
		mysqli_query($con, $sql);
		
	echo "<script>window.location='listDepartmentCategories.php?delete=1&deptId=".$_GET['deptId']."'</script>";
	}
	else
	{
	echo "<script>window.location='listDepartmentCategories.php?error=1&deptId=".$_GET['deptId']."'</script>";
	}


}


$cond = '';
if( isset($_GET['centerId']) && $_GET['centerId'] > 0)
{
	$cond = " AND rc.id = '".$_GET['centerId']."'  ";
}

$sql = "SELECT  rc.id, rc.name, du.name as outletName, rcd.id as outLetId, rcd.outLetType FROM tbl_revenue_center_departments rcd
INNER JOIN tbl_deptusers du ON(du.id = rcd.deptId)  AND du.account_id = rcd.account_id
INNER JOIN tbl_revenue_center rc ON(rc.id = rcd.revCenterId)  AND rc.account_id = rcd.account_id ".$cond."
INNER JOIN tbl_outlet_items o ON(o.outLetId = rcd.id) AND o.account_id = rcd.account_id
WHERE rcd.account_id = '".$_SESSION['accountId']."' 
GROUP BY du.id order by rc.id desc ";

$getRevenueReport = mysqli_query($con, $sql);


$sql = "SELECT rc.* FROM tbl_revenue_center_departments rcd
INNER JOIN tbl_revenue_center rc ON(rc.id = rcd.revCenterId) AND  rc.account_id = rcd.account_id
INNER JOIN tbl_deptusers du ON(du.id = rcd.deptId)  AND du.account_id = rcd.account_id
INNER JOIN tbl_outlet_items o ON(o.outLetId = rcd.id) AND o.account_id = rcd.account_id
WHERE rc.account_id= '".$_SESSION['accountId']."'
GROUP BY rc.id order by rc.name desc ";
		
$resultSet = mysqli_query($con, $sql);
							
$optionsRevCenters = '<select class="form-control" name="centerId" onchange="this.form.submit()">';
$optionsRevCenters .= '<option value="">'.showOtherLangText('Revenue Center').'</option>';
while($revRow = mysqli_fetch_array($resultSet) )
{
$sel = isset($_GET['centerId']) && $_GET['centerId'] == $revRow['id']  ? 'selected="selected"' : '';
$optionsRevCenters .= '<option value="'.$revRow['id'].'" '.$sel.'>'.$revRow['name'].'</option>';
}
$optionsRevCenters .= '</select>';


//value insert of guest modal form start from here
if( isset($_POST['saveBtn']) && $_POST['adjDate'] && $_POST['centerId'] > 0 && $_POST['guest'] > 0)
{	
	$date = date('Y-m-d', strtotime($_POST['adjDate']));
    
	$sql = "SELECT  rc.id, rc.name, du.name as outletName, rcd.id as outLetId, rcd.outLetType 
	FROM tbl_revenue_center_departments rcd
	INNER JOIN tbl_deptusers du ON(du.id = rcd.deptId) AND du.account_id = rcd.account_id
	INNER JOIN tbl_revenue_center rc ON(rc.id = rcd.revCenterId) AND rc.account_id = rcd.account_id
	INNER JOIN tbl_outlet_items o ON(o.outLetId = rcd.id) AND o.account_id = rcd.account_id
		
	WHERE rc.id = '".$_POST['centerId']."' AND rc.account_id = '".$_SESSION['accountId']."' GROUP BY du.id order by rc.id desc ";
	$result = mysqli_query($con, $sql);
											
	while($row = mysqli_fetch_array($result) )
	{
		
		$sql = "SELECT  * FROM tbl_daily_import WHERE outLetId = '".$row['outLetId']."'  AND account_id = '".$_SESSION['accountId']."' AND  importDate = '".$date."' limit 1  ";
		$qry = mysqli_query($con, $sql);
		$imptRes = mysqli_fetch_array($qry);		
			
		if($imptRes)
		{
			$qry = " UPDATE tbl_daily_import 
			SET guests = '".$_POST['guest']."' 
			WHERE id = '".$imptRes['id']."' AND account_id = '".$_SESSION['accountId']."' ";
			mysqli_query($con, $qry);
		}
		else
		{
		 	$qry = " INSERT INTO tbl_daily_import 
			 SET guests = '".$_POST['guest']."', outLetId = '".$row['outLetId']."', importDate = '".$date."', account_id='".$_SESSION['accountId']."' ";
			mysqli_query($con, $qry);
		}
	}
		
	//insertion into log table  
	$allPostData = array(['guest=>'.$_POST['guest']], ['outLetId=>'.$_POST['centerId']]);
	$jsonData =  json_encode($allPostData);
	$pageName = 'Revenue Center';
	$subSection = 'guest no';
	
	$insQry = " INSERT INTO tbl_log SET 
	`accountId` = '".$_SESSION['accountId']."',
	`section` = '".$pageName."',
	`subSection` = '$subSection',
	`logData` = '".$jsonData."',
	`userId` = '".$_SESSION['id']."',
	`date` = '".date('Y-m-d H:i:s')."'  ";
	mysqli_query($con, $insQry);
	//end of insertion into log table  

	$url = "revenueCenterReport.php?guest=1&fromDate=".$_POST['fromDate']."&toDate=".$_POST['toDate']."&account_id=".$_SESSION['account_id'];
	echo "<script>window.location='".$url."'</script>";
} 
?>