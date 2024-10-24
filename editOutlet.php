<?php
include('inc/dbConfig.php'); //connection details

if (!isset($_SESSION['adminidusername']))
{
echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

//Access Invoice permission for user
$accessInvoicePermission = get_access_invoice_permission($_SESSION['designation_id'],$_SESSION['accountId']);

$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'outlet' AND type_id = '0' AND designation_id = '".$_SESSION['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if ($permissionRow)
{
echo "<script>window.location='index.php'</script>";
}

$deptUserQry = " SELECT * FROM tbl_deptusers WHERE id='".$_GET['id']."'  AND deptId='".$_GET['deptId']."' ";
$deptUserResult = mysqli_query($con, $deptUserQry);
$deptUserRow = mysqli_fetch_array($deptUserResult);

$sql = " SELECT * FROM tbl_revenue_center_departments WHERE id = '".$_GET['revCenDeptId']."' AND deptId = '".$_GET['id']."' AND account_Id = '".$_SESSION['accountId']."' ";
$sqlResult = mysqli_query($con, $sql);
$sqlResultRow = mysqli_fetch_array($sqlResult);

$revCenQry = " SELECT * FROM tbl_revenue_center WHERE id='".$sqlResultRow['revCenterId']."' ";
$revCenResult = mysqli_query($con, $revCenQry);
$revCenRow = mysqli_fetch_array($revCenResult);

$selQry = " SELECT * FROM tbl_outlet_items WHERE outLetId = '".$_GET['revCenDeptId']."' AND account_id = '".$_SESSION['accountId']."' GROUP BY outLetId ";
$result = mysqli_query($con, $selQry);
$checkRow = mysqli_num_rows($result);

$mappingQry = " SELECT * FROM tbl_easymapping WHERE revId = '".$revCenRow['id']."' ";
$mapResult = mysqli_query($con, $mappingQry);
$mapRow = mysqli_fetch_array($mapResult);

if (($revCenRow['email'] == $deptUserRow['email']) && ($revCenRow['address'] == $deptUserRow['address']) && ($revCenRow['phone'] == $deptUserRow['phone']) && !empty($deptUserRow['email']) && !empty($deptUserRow['address']) && !empty($deptUserRow['phone']))
{
$sameDetails = '1';
}
else
{
$sameDetails = '0';
}



if( isset($_GET['delId']) && $_GET['delId'] )
{
    // check that item is present in our report or not. if not then only delete.
    $selQry = " SELECT dii.* FROM tbl_outlet_items oi 
    INNER JOIN tbl_products p ON(oi.pId=p.id) AND (oi.account_id=p.account_id)
    INNER JOIN tbl_daily_import_items dii ON(p.barCode=dii.barCode AND oi.outLetId=dii.outLetId) AND (p.account_id=dii.account_id)
    WHERE oi.id='".$_GET['delId']."' AND oi.account_id='".$_SESSION['accountId']."' GROUP BY (dii.outLetId) ";
    $result = mysqli_query($con, $selQry);

    if (mysqli_num_rows($result) > 0)
    {
        echo '<script>window.location="editOutlet.php?id='.$_GET['id'].'&revCenDeptId='.$_GET['revCenDeptId'].'&deptId='.$_GET['deptId'].'&itemReportAvailable=1"</script>';
    }
    else
    {
        $sql = "DELETE FROM tbl_outlet_items  WHERE id='".$_GET['delId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
        mysqli_query($con, $sql);
    }

}

// form data save start from here
if(isset($_POST['name']) && isset($_POST['deptId']))
{
  
// user can update there outlet name and department name from here
 $updateQry = " UPDATE tbl_deptusers SET 
`deptId` = '".$_POST['deptId']."',
`name` = '".$_POST['name']."',
`receive_inv` = '".$_POST['receiveInv']."',
`email` = '".$_POST['email']."',
`address` = '".$_POST['address']."',
`phone` = '".$_POST['phone']."'
WHERE id='".$_GET['id']."' AND account_id='".$_SESSION['accountId']."' ";

mysqli_query($con, $updateQry);

if(isset($_POST['revCenter']) && $_POST['revCenter'] > 0 && isset($_POST['outLetType']) && $_POST['outLetType'] > 0)
{
// If isset revenue center and outlet type then check first either this revenue center and outlet type present in below mentioned table or not if not then insert else update revenue center department table with new post data.

$sqlQry = "SELECT rcd.* FROM  tbl_revenue_center_departments rcd 
LEFT JOIN tbl_outlet_items oi ON(oi.outLetId=rcd.id) AND oi.account_id=rcd.account_id
LEFT JOIN tbl_daily_import_items dii ON(dii.outLetId=rcd.id) AND dii.account_id=oi.account_id
WHERE rcd.deptId = '".$_GET['id']."' AND rcd.account_id = '".$_SESSION['accountId']."' 	";
$revCenRes = mysqli_query($con, $sqlQry);
$revCen = mysqli_fetch_array($revCenRes);
$outLetId = $revCen['id'];

if(!$revCen)
{
	$sql = "INSERT INTO `tbl_revenue_center_departments` SET
	`revCenterId` = '".$_POST['revCenter']."',
	`deptId` = '".$_GET['id']."',
	`outLetType` = '".$_POST['outLetType']."',
	`account_id` = '".$_SESSION['accountId']."'  ";
	mysqli_query($con, $sql);

	$outLetId = mysqli_insert_id($con);
}
else
{
	$updateQry = "UPDATE `tbl_revenue_center_departments` SET
	`revCenterId` = '".$_POST['revCenter']."',
	`deptId` = '".$_GET['id']."',
	`outLetType` = '".$_POST['outLetType']."',
	`account_id` = '".$_SESSION['accountId']."' 
	WHERE id = '".$_GET['revCenDeptId']."'  ";
	mysqli_query($con, $updateQry);

	if ( $_POST['outLetType'] == 1 ) 
	{
		// sales = 1 and costs = 2; 
		$updateQry = "UPDATE `tbl_easymapping` SET
		`revId` = '".$_POST['revCenter']."', 
		WHERE id = '".$mapRow['id']."'  ";
		mysqli_query($con, $updateQry);
	}
	
}

$sqlSet = " SELECT * FROM tbl_easymapping WHERE revId = '".$_POST['revCenter']."' AND account_id='".$_SESSION['accountId']."' ";
$resultSet = mysqli_query($con, $sqlSet);
$resultRowSet = mysqli_fetch_array($resultSet);

$hotelId = $resultRowSet['hotelId'];
$mapId = $resultRowSet['id'];

$sqlSet = " SELECT * FROM tbl_map_outlets WHERE revId = '".$revCenRow['id']."' AND outLetId = '".$outLetId."'  AND account_id='".$_SESSION['accountId']."' ";
$resultSet = mysqli_query($con, $sqlSet);
$resultRowSet = mysqli_fetch_array($resultSet);

if (!$resultRowSet)
{
	$sql = "INSERT INTO `tbl_map_outlets` SET
	`hotelId` = '".$hotelId."',
	`mapId` = '".$mapId."',
	`revId` = '".$_POST['revCenter']."',
	`outLetId` = '".$outLetId."',
	`account_id` = '".$_SESSION['accountId']."'  ";
	mysqli_query($con, $sql);
}
else
{
	$updateQry = "UPDATE `tbl_map_outlets` SET
	`hotelId` = '".$hotelId."',
	`mapId` = '".$mapId."',
	`revId` = '".$_POST['revCenter']."',
	`outLetId` = '".$outLetId."',
	`account_id` = '".$_SESSION['accountId']."' 
	WHERE revId = '".$revCenRow['id']."' AND outLetId = '".$outLetId."'  AND account_id='".$_SESSION['accountId']."'  ";
	mysqli_query($con, $updateQry);
}


$outLetId = (isset($_GET['revCenDeptId']) && $_GET['revCenDeptId'] == $outLetId) ? $_GET['revCenDeptId'] : $outLetId;


if(isset($_POST['catIds']) && !empty($_POST['catIds']))
{

	$sql = "SELECT * FROM  `tbl_map_outletcats`  WHERE `revOutLetId` = '".$outLetId."'  AND account_id = '".$_SESSION['accountId']."' 	";
	$qry = mysqli_query($con, $sql);
	$revCenMap = mysqli_fetch_array($qry);

	if($revCenMap)
	{
		$delQry =  "DELETE FROM tbl_map_outletcats WHERE `revOutLetId` = '".$outLetId."' AND account_id = '".$_SESSION['accountId']."'  ";
		mysqli_query($con, $delQry);
	}


	foreach($_POST['catIds'] as $catId)
	{
	 	$sql = "INSERT INTO `tbl_map_outletcats` SET
		`revOutLetId` = '".$outLetId."',
		`revCatId` = '".$catId."',
		`account_id` = '".$_SESSION['accountId']."'  ";
		mysqli_query($con, $sql);
	}

}


}

if ($_POST['setOutlet'] < 1) {

$selQry = " SELECT * FROM tbl_daily_import_items WHERE outLetId = '".$sqlResultRow['id']."' AND account_id = '".$_SESSION['accountId']."' ";
$selResult = mysqli_query($con, $selQry);

if (mysqli_num_rows($selResult) < 1)
{
	$updateQry = " UPDATE tbl_deptusers SET 
	`deptId` = '".$_POST['deptId']."',
	`name` = '".$_POST['name']."',
	`receive_inv` = '".$_POST['receiveInv']."',
	`email` = ' ',
	`address` = ' ',
	`phone` = ' '
	WHERE id='".$_GET['id']."' AND account_id='".$_SESSION['accountId']."' ";
	mysqli_query($con, $updateQry);

	$delQry = "DELETE FROM tbl_map_outletcats WHERE revOutLetId = '".$outLetId."' AND account_id = '".$_SESSION['accountId']."' ";
	mysqli_query($con, $delQry);

	$delQry = "DELETE FROM tbl_map_outlets WHERE outLetId = '".$outLetId."' AND revId = '".$_POST['revCenter']."' AND account_id = '".$_SESSION['accountId']."' ";
	mysqli_query($con, $delQry);

	$delQry = "DELETE FROM tbl_revenue_center_departments WHERE revCenterId = '".$_POST['revCenter']."' AND deptId = '".$_GET['id']."' AND account_id = '".$_SESSION['accountId']."' ";
	mysqli_query($con, $delQry);

	$delQry = "DELETE FROM tbl_outlet_items WHERE outLetId = '".$outLetId."' AND account_id = '".$_SESSION['accountId']."' ";
	mysqli_query($con, $delQry);
}
}

echo '<script>window.location="editOutlet.php?update=1&id='.$_POST['deptUserId'].'&deptId='.$_POST['deptId'].'&revCenDeptId='.$outLetId.'"</script>';
}


//$_POST['revenueCenterId'] comes by ajax when changes revenue center
if (isset($_POST['revenueCenterAddress']) && $_POST['revenueCenterAddress'] > 0) 
{

$revCenterQry = " SELECT * FROM `tbl_revenue_center` WHERE id = '".$_POST['revenueCenterAddress']."'  AND account_id = '".$_SESSION['accountId']."' ";
$revCenterRes = mysqli_query($con, $revCenterQry);
$revCenterRow = mysqli_fetch_array($revCenterRes);

$email = $revCenterRow['email'];
$address = $revCenterRow['address'];
$phone = $revCenterRow['phone'];

$updateQry = " UPDATE tbl_deptusers SET 
`email` = '".$email."',
`address` = '".$address."',
`phone` = '".$phone."'
WHERE id='".$_POST['id']."' AND account_id='".$_SESSION['accountId']."' ";
mysqli_query($con, $updateQry);

$responseArr = ['email'=>$email, 'address'=>$address, 'phone'=>$phone];

echo json_encode($responseArr);
die;

}


//$_POST['revenueCenterId'] comes by ajax when changes revenue center
if (isset($_POST['revenueCenterId']) && $_POST['revenueCenterId'] > 0) 
{
$mainMapQry = " SELECT * FROM `tbl_map_category` WHERE revId = '".$_POST['revenueCenterId']."'  AND account_id = '".$_SESSION['accountId']."' ";
$mainMapRes = mysqli_query($con, $mainMapQry);

$strcat='';
while($catRes = mysqli_fetch_array($mainMapRes))
{ 
$strcat .= "<input type='checkbox' name='catIds[]' value='".$catRes['id']."' > ".$catRes['catName']." ";
}
echo $strcat;
die;

}


$sql = "SELECT o.*, p.itemName, p.imgName, p.barCode, IF(u.name!='',u.name,o.subUnit) subUnit FROM tbl_outlet_items o 
LEFT JOIN tbl_units u ON(u.id=o.subUnit) AND u.account_id = o.account_id
INNER JOIN tbl_products p ON(p.id = o.pId) AND p.account_id = o.account_id 
WHERE o.outLetId = '".$_GET['revCenDeptId']."' AND o.account_id = '".$_SESSION['accountId']."'   ";
$outLetItemsQry = mysqli_query($con, $sql);

if( isset($_GET['revCenDeptId']) )
{
$sql = "SELECT * FROM  tbl_revenue_center_departments  WHERE  id='".$_GET['revCenDeptId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
$qry = mysqli_query($con, $sql);
$outLetDet = mysqli_fetch_array($qry);
}


////////////////////////////////////////////
//call ajax data to show all the details of product when user click on edit button.
if (isset($_POST['itemRowId']) && $_POST['itemRowId'] > 0 && $_POST['ajaxData'] == 1) {
	
	$sql = " SELECT o.*, p.itemName, IF(u.name!='',o.subUnit,u.name) unit FROM tbl_outlet_items o 
	LEFT JOIN tbl_units u ON(u.id=o.subUnit) AND u.account_id = o.account_id 
	INNER JOIN tbl_products p ON(p.id = o.pId) AND p.account_id = o.account_id 
	WHERE o.id = '".$_POST['itemRowId']."' AND o.account_id = '".$_SESSION['accountId']."'  ";
	$qry = mysqli_query($con, $sql);
	$itemDet = mysqli_fetch_array($qry);
	$itemName = $itemDet['itemName'].'('.$itemDet['pId'].')';;
	$factor = $itemDet['factor'];
	$minQty = $itemDet['minQty'];
	$maxQty = $itemDet['maxQty'];
	$itemType = $itemDet['itemType'];
	$unit = $itemDet['subUnit'];

	$responseArr = ['itemName'=>$itemName, 'factor'=>$factor, 'minQty'=>$minQty,'maxQty'=>$maxQty,'itemType'=>$itemType,'unit'=>$unit];
			
	echo json_encode($responseArr);
	die;
}

//code for modal box add outlet Items/ edit outlet Items 
if( isset($_POST['outLetId']) && !empty($_POST['outLetId']) )
{
	$proName = trim($_POST['itemName']);
	$proNameFilter = explode('(', $proName);

	$pId = trim( str_replace(')', '', $proNameFilter[sizeof($proNameFilter)-1]) );

	$cond = '';
	if (isset($_POST['itemRowId']) && $_POST['itemRowId'] > 0)
	{
		$cond = "AND id != '".$_POST['itemRowId']."' ";
	}
	
	$selQry = " SELECT * FROM tbl_outlet_items WHERE `outLetId` = '".$_POST['outLetId']."' AND `pId` = '".$pId."' AND account_id = '".$_SESSION['accountId']."' ".$cond." ";
	$result = mysqli_query($con, $selQry);
	$resultRow = mysqli_fetch_array($result);
    
	if ($resultRow)
	{	
		echo "<script>window.location= 'editOutlet.php?error=1&revCenDeptId=".$_POST['outLetId']."&id=".$_GET['id']."&deptId=".$_GET['deptId']."'</script>";

	}
	else
	{
		if (isset($_POST['itemRowId']) && $_POST['itemRowId'] > 0)
		{
			$sql = "UPDATE `tbl_outlet_items` SET
			`outLetId` = '".$_POST['outLetId']."',
			`pId` = '".$pId."', 
			`itemType` = '".$_POST['itemType']."', 
			`subUnit` = '".$_POST['subUnit']."', 
			`factor` = '".$_POST['factor']."', 
			`minQty` = '".$_POST['minQty']."', 
			`maxQty` = '".$_POST['maxQty']."'
			WHERE id = '".$_POST['itemRowId']."'  AND account_id = '".$_SESSION['accountId']."' ";
			mysqli_query($con, $sql);

			echo "<script>window.location= 'editOutlet.php?edit=1&revCenDeptId=".$_POST['outLetId']."&id=".$_GET['id']."&deptId=".$_GET['deptId']."'</script>";
		}
		else
		{

			$sql = "INSERT INTO `tbl_outlet_items` SET
			`outLetId` = '".$_POST['outLetId']."',
			`pId` = '".$pId."', 
			`itemType` = '".$_POST['itemType']."', 
			`subUnit` = '".$_POST['subUnit']."', 
			`factor` = '".$_POST['factor']."', 
			`minQty` = '".$_POST['minQty']."', 
			`maxQty` = '".$_POST['maxQty']."', 
			`daysCount` = '".$_POST['daysCount']."', 
			`creationDate` = NOW(),
			`account_id` = '".$_SESSION['accountId']."' ";
			mysqli_query($con, $sql);

			echo "<script>window.location= 'editOutlet.php?added=1&revCenDeptId=".$_POST['outLetId']."&id=".$_GET['id']."&deptId=".$_GET['deptId']."'</script>";
		}	
	}
}
?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Edit Outlet - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/style1.css">
    <style>
        @media screen and (max-width: 767px) {
            .chkOlt-Row .col-md-8.phone__number { width: 100%;order: 2;padding-right: .75rem; }
            .chkOlt-Row .col-md-4.phone__number { width: 100%;order: 1;padding-left: calc(var(--bs-gutter-x) * 0.5); }
        }
    </style>
</head>

<body class="mb-Bgbdy">

    <div class="container-fluid newOrder">
        <div class="row">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
            <?php require_once('nav.php');?>
            </div>
            
            <div class="cntArea">
                <section class="usr-info">
                
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('Edit Outlet'); ?></h1>
                        </div>
                        <div class="col-md-8 d-flex align-items-center justify-content-end">
                            <div class="mbPage">
                                <div class="mb-nav" id="mb-nav">
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                        aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"><i class="fa-solid fa-bars"></i></span>
                                    </button>
                                </div>
                                <div class="mbpg-name">
                                    <h1 class="h1"><?php echo showOtherLangText('Edit Outlet'); ?></h1>
                                </div>
                            </div>
                             <?php require_once('header.php'); ?>
                        </div>
                    </div>
                </section>

                <section id="landScape">
                    <div class="container">
                        <h1 class="h1 text-center">For better experience, Please use portrait view.</h1>
                    </div>
                </section>

                <section class="ordDetail userDetail itmMngDetail">
                   <form action="" method="post" id="outletItemFrm" name="outletItemFrm"> <div >
                   <input type="hidden" name="deptUserId" id="deptUserId"
                                    value="<?php echo $_GET['id']; ?>">
                                <input type="hidden" name="checkProduct" id="availableProductInOutlet"
                                    value="<?php echo $checkRow; ?>"> 
                   <div class="container">
                   <?php if(isset($_GET['added']) || isset($_GET['edit'])|| isset($_GET['update']) || isset($_GET['itemReportAvailable']) || isset($_GET['delId'])) {?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p><?php 

echo isset($_GET['added']) ? ' '.showOtherLangText('Item Added Successfully').' ' : '';


echo isset($_GET['edit']) ? ' '.showOtherLangText('Item edited Successfully').' ' : '';

echo isset($_GET['delId']) ? ' '.showOtherLangText('Item deleted Successfully').' ' : '';

echo isset($_GET['itemReportAvailable']) ? ' '.showOtherLangText('Item cannot be deleted as it is in report').' ' : '';

echo isset($_GET['update']) ? ' '.showOtherLangText('OutLet Updated Successfully').' ' : '';

?>
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                            <?php if(isset($_GET['error'])) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo isset($_GET['error']) ? ' '.showOtherLangText('Product already exist in this outlet').' ' : '';
 ?></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                        <div class="row">
                            <div class="col-md-6 bkOutlet-Btn">
                                <div class="btnBg">
                                    <a href="manageOutlets.php" class="btn btn-primary mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                </div>
                            </div>
                            <div class="col-md-6 addOutlet-Btn">
                                <div class="itmLnk-Row">
                                    <a href="addCategory.php" class="btn btn-primary mb-usrBkbtn res__w__auto" data-bs-toggle="modal" data-bs-target="#add-Item">
                                        <span class="mb-UsrBtn"><i class="fa-solid fa-plus"></i><span class="nstdSpan"><?php echo showOtherLangText('Add Item'); ?></span></span>
                                        <span class="dsktp-Btn"><?php echo showOtherLangText('Add Item'); ?></span>
                                    </a> 
                                    <button type="submit" class="btn btn-primary mb-usrBkbtn">
                                        <span class="mb-UsrBtn"><i class="fa-regular fa-floppy-disk"></i></span> 
                                        <span class="dsktp-Btn"><?php echo showOtherLangText('Save'); ?></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
</div>
                    <div class="container itmMng-Src outletFrm">
                     <div class="row">
             <!--  <div class="col-xl-8 oltCol-8"> --> <div>
                                <div class="acntStp padRight40">
                                    <div class="addUser-Form acntSetup-Form row">
                                        <div class="acnt-Div">

                                        


                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Name'); ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input required type="text" class="form-control" name="name" id="name"
                                                value="<?php echo $deptUserRow['name']; ?>"
                                                        placeholder="Casa Kitchen">
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Department" class="form-label"><?php echo showOtherLangText('Department'); ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="cstmSelect">
                                                       <select name="deptId" id="deptId" class="form-select selectOption" required>

                                                <option value=""><?php echo showOtherLangText('Select'); ?></option>

                                                <?php $deptQry = "SELECT * FROM tbl_department WHERE account_id = '".$_SESSION['accountId']."' ORDER BY name ";
								$deptResult = mysqli_query($con, $deptQry);
								while($deptRow = mysqli_fetch_array($deptResult))
								{
									$sel = $deptUserRow['deptId'] == $deptRow['id'] ? 'selected="selected" ' : '';
									echo '<option value="'.$deptRow['id'].'" '.$sel.' >'.$deptRow['name'].'</option>';
								} 
								?>                 </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            if ($accessInvoicePermission['type_id']==1) 
                                            {
                                            ?>
                                            <div class="row align-items-center acntStp-Row chkOlt-Row">
                                                <div class="col-md-4">
                                                    <label for="receiveInvoice" class="form-label"><?php echo showOtherLangText('Receive Invoice'); ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                   <input class="form-check-input" type="checkbox" id="receiveInv" name="receiveInv"
                                        onClick="changeRevInvStatus()"
                                        value="<?php echo $deptUserRow['receive_inv']; ?>"
                                        <?php echo $deptUserRow['receive_inv'] > 0 ? 'checked': ''; ?>>
                                                </div>
                                            </div>
                                            <?php
                                            }
                                            ?>
                                            <?php
                                            //if ($_SESSION['accountId'] != 4) 
                                            //{
                                            ?>
                                            <div class="row align-items-center acntStp-Row chkOlt-Row">
                                                <div class="col-md-4">
                                                    <label for="setOutlet" class="form-label"><?php echo showOtherLangText('Set as Outlet'); ?></label>
                                                </div>
                                                <div class="col-md-8">
                                         <input class="form-check-input" type="checkbox" id="setOutlet" name="setOutlet"
                                        value="<?php echo $sqlResultRow > 0 ? '1': '0'; ?>"
                                        <?php echo $sqlResultRow > 0 ? 'checked': ''; ?>
                                        onClick="showRevOutLetItems();" />
                                                </div>
                                            </div>
                                            <?php        
                                           // }
                                            ?>
                                            <div class="outletChk" style="display:block;">
                                                <div class="row align-items-center acntStp-Row">
                                                    <div class="col-md-4">
                                                        <label for="revenueCenter" class="form-label"><?php echo showOtherLangText('Revenue Center'); ?></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="cstmSelect">
                                                        <?php
									$revCenQry = "SELECT id, name FROM tbl_revenue_center WHERE account_id= '".$_SESSION['accountId']."' ORDER BY name ";

									$revCenResult = mysqli_query($con, $revCenQry);

									?>
                                    <select name="revCenter" id="revCenter" class="form-select selectOption"
                                                                aria-label="Default select example"
                                                onChange="getrevCenter();">

                                                <option value=""><?php echo showOtherLangText('Select'); ?></option>

                                                <?php
								$revCenQry = "SELECT id, name FROM tbl_revenue_center WHERE account_id= '".$_SESSION['accountId']."' ORDER BY name ";
								$revCenResult = mysqli_query($con, $revCenQry);
								while($revCenRow = mysqli_fetch_array($revCenResult))
								{
									$sel = $sqlResultRow['revCenterId'] == $revCenRow['id']  ? 'selected="selected" ' : '';
									echo '<option value="'.$revCenRow['id'].'" '.$sel.' >'.$revCenRow['name'].'</option>';

								} 
								?>
                                            </select>
                                                           
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center acntStp-Row">
                                                    <div class="col-md-4">
                                                        <label for="outletType" class="form-label"><?php echo showOtherLangText('Outlet Type'); ?></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="cstmSelect">
                                                            
                                                        <select name="outLetType" id="outLetType" class="form-select selectOption"
                                                    onchange="getOutletType();">

                                                <option value=""><?php echo showOtherLangText('Select'); ?></option>
                                                <option value="1"
                                                    <?php echo $sqlResultRow['outLetType'] == 1 ? ' selected="selected" ' : '';?>>
                                                    <?php echo showOtherLangText('Sales'); ?></option>
                                                <option value="2"
                                                    <?php echo $sqlResultRow['outLetType'] == 2 ? ' selected="selected" ' : '';?>>
                                                    <?php echo showOtherLangText('Costs'); ?></option>

                                                         </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                 <div class="dropContent" id="dropContent">
                                                <div class="AssignEzeeCategory row align-items-start acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="setOutlet" class="form-label"><?php echo showOtherLangText('Assign Ezee Category'); ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                   <span id="showCatIds" class=AsignCategory><?php
						$sql = " SELECT * FROM tbl_map_category 
						WHERE revId='".$sqlResultRow['revCenterId']."' AND account_id = '".$_SESSION['accountId']."' ";
						$mapCatResult = mysqli_query($con, $sql);

						while($mapCatRow = mysqli_fetch_array($mapCatResult))
						{

							$sql = " SELECT * FROM tbl_map_outletcats 
							WHERE revCatId='".$mapCatRow['id']."'  AND revOutLetId = '".$_GET['revCenDeptId']."' AND account_id = '".$_SESSION['accountId']."' ";
							$mapOutCatResult = mysqli_query($con, $sql);
							$mapOutCatRow = mysqli_fetch_array($mapOutCatResult);

							$sel = $mapOutCatRow['revCatId'] ? ' checked="checked" ' : '';

							echo "<div class='asCategory'><input class='form-check-input ezeeCatList' type='checkbox' name='catIds[]' value='".$mapCatRow['id']."' '".$sel."' >&nbsp;<label for='revenueCenter' class='form-label'>".$mapCatRow['catName']." </label></div>&nbsp;&nbsp;";

						}
						?></span>
                                                </div>
                                                </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="acntLg-Upld setOutlet ">
                                        <?php
                                        if ($_SESSION['accountId'] != 4) 
                                        {
                                     	?>
                                            
                                            <?php        
                                            }
                                            ?>
                                            <div class="padLeft40" style="width:100%;">

                                            <div class="row align-items-center acntStp-Row">
                                            <div class="col-md-4 colDisable">&nbsp;</div>                                        
                                                    <div class="col-8">
                                                    <input type="checkbox" id="addressCheck" class="form-check-input" name="addressCheck" value=""
                                                    onclick="showRevCenterAddress();">
                                                    <span style="padding-left:7px;"><label for="setOutlet" class="form-label"><?php echo showOtherLangText('Use Revenue Center Address'); ?></label></span>
                                                    </div>                 
                                            </div>  
                                                <div class="row align-items-center acntStp-Row">
                                                    <div class="col-md-4">
                                                        <label for="revenueCenter" class="form-label"><?php echo showOtherLangText('Address'); ?></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="cstmSelect">
                                                        <textarea class="form-control" style="resize: vertical;" placeholder="Main" name="address" id="address" value="" cols="20" rows="2" autocomplete="off"><?php echo $deptUserRow['address']; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center acntStp-Row">
                                                    <div class="col-md-4">
                                                        <label for="outletType" class="form-label"><?php echo showOtherLangText('Email'); ?></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                    <input type="email" class="form-control" value="<?php echo $deptUserRow['email']; ?>" name="email" id="email" 
                                                        placeholder="Casa Kitchen">
                                                    </div>
                                                </div>
                                                <div class="row align-items-center acntStp-Row chkOlt-Row">
                                                    <div class="col-md-4 phone__number">
                                                        <label for="asgnEzcat" class="form-label"><?php echo showOtherLangText('Phone number'); ?></label>
                                                    </div>
                                                    <div class="col-md-8 phone__number">
                                                    <input type="text" class="form-control" value="<?php echo $deptUserRow['phone']; ?>" name="phone" id="phone" 
                                                        placeholder="Casa Kitchen">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 oltCol-4"></div>
                        </div>
                    </div>
                    </form>
                    <div class="container">
                        <p class="subTittle1 flowItm"><?php echo showOtherLangText('Follow Items List'); ?></p>
                    </div>
                    <div class="container outlet-Tblhead position-relative">
                        <!-- Item Table Head Start -->
                        <div class="d-flex align-items-center itmTable">
                            <div class="tb-head imgOlt-Clm">
                                <p><?php echo showOtherLangText('Photo'); ?></p>
                            </div>
                            <div class="align-items-center shItmOlt-Clm">
                                <div class="tb-head itmOlt-Clm">
                                    <p><?php echo showOtherLangText('Item'); ?></p>
                                </div>
                                <div class="tb-head brCdOlt-Clm">
                                    <p><?php echo showOtherLangText('Bar Code'); ?></p>
                                </div>
                                <div class="tb-head typOlt-Clm">
                                    <p><?php echo showOtherLangText('Type'); ?></p>
                                </div>
                            </div>
                            <div class="align-items-center unitOlt-Clm">
                                <div class="tb-head sbUnOlt-Clm">
                                    <p><?php echo showOtherLangText('Sub Unit'); ?></p>
                                </div>
                                <div class="tb-head fctOlt-Clm">
                                    <p><?php echo showOtherLangText('Factor'); ?></p>
                                </div>
                                <div class="tb-head minOlt-Clm">
                                    <p><?php echo showOtherLangText('Min Qty'); ?></p>
                                </div>
                                <div class="tb-head maxOlt-Clm">
                                    <p><?php echo showOtherLangText('Max Qty'); ?></p>
                                </div>
                            </div>
                            <div class="icnOlt-Clm">
                                &nbsp;
                            </div>
                        </div>
                        <!-- Item Table Head End -->
                    </div>

                    <!-- Item Table Body Start -->
                    <div id="boxscroll">
                        <div class="container cntTable adOtlTable">
                        <?php 

                        $x= 0;
                        while($outLetItemRow = mysqli_fetch_array($outLetItemsQry))
                        { 

                        $color = ($x%2 == 0)? 'white': '#FFFFCC';
                        $x++;

                        ?>
                            <div class="d-flex align-items-center border-bottom itmBody outletBdy-Task">
                                <div class="itmTask-Mng oltTsk-Dtl align-items-center">
                                    <div class="tb-bdy imgOlt-Clm">
                                    <?php $img = '';

                                    if($outLetItemRow['imgName'] != ''  && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$outLetItemRow['imgName']))
                                    {	
                                    echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$outLetItemRow['imgName'].'" width="60" height="60">';

                                    }?>
                                    </div>
                                    <div class="align-items-center shItmOlt-Clm">
                                        <div class="tb-bdy itmOlt-Clm">
                                            <p><?php echo $outLetItemRow['itemName'];?></p>
                                        </div>
                                        <div class="tb-bdy brCdOlt-Clm">
                                            <p><?php echo $outLetItemRow['barCode'];?></p>
                                        </div>
                                        <div class="tb-bdy typOlt-Clm">
                                            <p><?php echo $outLetItemRow['itemType'] == 1 ? 'Bar Control' : ($outLetItemRow['itemType'] == 2 ? 'Sales' : 'Usage');?></p>
                                        </div>
                                    </div>
                                    <div class="unitOlt-Clm">
                                        <div class="d-flex align-items-center flex-wrap mblFlx-Olt">
                                            <div class="tb-bdy sbUnOlt-Clm">
                                                <p><span class="mblOlt-head">Sub Unit</span> <?php echo $outLetItemRow['subUnit'];?></p>
                                            </div>
                                            <div class="tb-bdy fctOlt-Clm">
                                                <p><span class="mblOlt-head">Factor</span><?php echo $outLetItemRow['factor'];?></p>
                                            </div>
                                            <div class="tb-bdy minOlt-Clm">
                                                <p><span class="mblOlt-head">Min Qyt</span><?php echo $outLetItemRow['minQty'];?></p>
                                            </div>
                                            <div class="tb-bdy maxOlt-Clm">
                                                <p><span class="mblOlt-head">Max Qyt</span><?php echo $outLetItemRow['maxQty'];?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="icnOlt-Clm">
                                        <div class="tb-bdy d-flex align-items-center justify-content-end">
                                            <a href="javascript:void(0)" data-editid="<?php echo $outLetItemRow['id'];?>" class="userLink editicon" data-bs-toggle="modal"
                                                    data-bs-target="#edit-PhyStorage">
                                                <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                            </a>
                                            <a href="javascript:void(0)" onClick="getDelNumb('<?php echo $outLetItemRow['id'];?>', '<?php echo $_GET['id'];?>', '<?php echo $_GET['revCenDeptId'];?>', '<?php echo $_GET['deptId'];?>');" class="userLink">
                                                <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <a href="javascript:void(0)" class="statusLink mb-oltLnk"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                            </div>
                            <?php 
					}

					?>
                            
                            
                            
                            
                        </div>
                    </div>
                    <!-- Item Table Body End -->

                  
                </section>

            </div>
        </div>
    </div>
    <!-- Add Storage Popup Start -->
    <form role="form" action="" method="post" id="outletItemFrm" name="outletItemFrm" class="addUser-Form row">
    <div class="modal" tabindex="-1" id="add-Item" aria-labelledby="add-PhyStorageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php echo showOtherLangText('Add Follow Item')?></h1>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="itemName" id="tags" required placeholder="<?php echo showOtherLangText('Assign Item'); ?>">
                    <input type="hidden" name="outLetId" value="<?php echo $_GET['revCenDeptId'];?>" />
                <input type="hidden" name="itemRowId" id="itemRowId" value="" />
                <select name="itemType" id="itemType" class="form-control" required>
                                <option value=""><?php echo showOtherLangText('Select'); ?> <?php echo showOtherLangText('Item Type'); ?>
                                </option>
                                <?php 
							if($outLetDet['outLetType'] == 2)
							{
								echo '<option value="3">'.showOtherLangText('Usage').'</option>';
							}
							else
							{
								echo '<option value="1">'.showOtherLangText('Bar Control').'</option>
								<option value="2">'.showOtherLangText('Sales').'</option>
								<option value="3">'.showOtherLangText('Usage').'</option>';
							}
							?>
                            </select>
                            <select class="form-control" name="subUnit" id="subUnit" required>
                                <option value=""><?php echo showOtherLangText('Select'); ?> <?php echo showOtherLangText('Sub Unit'); ?>
                                </option>

                                <?php 
							$untQry = " SELECT * FROM tbl_units WHERE account_id='".$_SESSION['accountId']."' ";
							$untResult = mysqli_query($con, $untQry);
							while($untRow = mysqli_fetch_array($untResult))
							{
								echo "<option value='".$untRow['id']."'>".$untRow['name']."</option>";
							}

							?>
                            </select>
                            <input type="text" class="form-control" name="factor" id="factor" placeholder="<?php echo showOtherLangText('Factor'); ?>" required />
                            <input type="text" class="form-control" name="minQty" id="minQty" placeholder="<?php echo showOtherLangText('Min Qty'); ?>" value="" />
                            <input type="text" class="form-control" name="maxQty" id="maxQty" placeholder="<?php echo showOtherLangText('Max Qty'); ?>" value="" />
                </div>
                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="submit" class="btn sub-btn std-btn"><?php echo showOtherLangText('Save'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    <!-- Edit Storage Popup Start -->
    <!-- <form role="form" action=""  class="addUser-Form row container glbFrm-Cont" method="post">
    <div class="modal" tabindex="-1" id="edit-PhyStorage" aria-labelledby="edit-PhyStorageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php //echo showOtherLangText('Edit store'); ?></h1>
                </div>
                <div class="modal-body">
                <input type="hidden" name="id" id="edit-id" value="" /> 
                        <input type="text" class="form-control" id="editStore" required name="name" placeholder="Name">
                   
                </div>
                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="submit" class="btn sub-btn std-btn"><?php //echo showOtherLangText('Save'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form> -->

    <form role="form" action=""  class="addUser-Form row container glbFrm-Cont" method="post">
    <div class="modal" tabindex="-1" id="edit-PhyStorage" aria-labelledby="edit-PhyStorageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php echo showOtherLangText('Edit Follow Item')?></h1>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="itemName" id="edittags" required placeholder="<?php echo showOtherLangText('Assign Item'); ?>">
                    <input type="hidden" name="outLetId" value="<?php echo $_GET['revCenDeptId'];?>" />
                <input type="hidden" name="itemRowId" id="itemRowId" value="" />
                <select name="itemType" id="itemType" class="form-control" required>
                                <option value=""><?php echo showOtherLangText('Select'); ?> <?php echo showOtherLangText('Item Type'); ?>
                                </option>
                                <?php 
							if($outLetDet['outLetType'] == 2)
							{
								echo '<option value="3">'.showOtherLangText('Usage').'</option>';
							}
							else
							{
								echo '<option value="1">'.showOtherLangText('Bar Control').'</option>
								<option value="2">'.showOtherLangText('Sales').'</option>
								<option value="3">'.showOtherLangText('Usage').'</option>';
							}
							?>
                            </select>
                            <select class="form-control" name="subUnit" id="subUnit" required>
                                <option value=""><?php echo showOtherLangText('Select'); ?> <?php echo showOtherLangText('Sub Unit'); ?>
                                </option>

                                <?php 
							$untQry = " SELECT * FROM tbl_units WHERE account_id='".$_SESSION['accountId']."' ";
							$untResult = mysqli_query($con, $untQry);
							while($untRow = mysqli_fetch_array($untResult))
							{
								echo "<option value='".$untRow['id']."'>".$untRow['name']."</option>";
							}

							?>
                            </select>
                            <input type="text" class="form-control" name="factor" id="factor" placeholder="<?php echo showOtherLangText('Factor'); ?>" required />
                            <input type="text" class="form-control" name="minQty" id="minQty" placeholder="<?php echo showOtherLangText('Min Qty'); ?>" value="" />
                            <input type="text" class="form-control" name="maxQty" id="maxQty" placeholder="<?php echo showOtherLangText('Max Qty'); ?>" value="" />
                </div>
                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="submit" class="btn sub-btn std-btn"><?php echo showOtherLangText('Save'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    <!-- Add Storage Popup End -->
    <div id="dialog1" style="display: none;">
    <?php echo showOtherLangText('Are you sure to delete this record?') ?>  
</div>
<div id="outletItemModalPopup" class="modal"></div>
    <?php require_once('footer.php');?>
<link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
   <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
</body>

</html>
<script>
    $( document ).ready(function() {
      $('.editicon').click(function(){
        
        var editIdValue = this.getAttribute("data-editid");
        openModal(editIdValue);
       
      });
    });  
    function getDelNumb(delId, id, revCenDeptId, deptId) {

        $("#dialog1").dialog({
            autoOpen: false,
            modal: true,
            //title     : "Title",
            buttons: {
                '<?php echo showOtherLangText('Yes') ?>': function() {
                    //Do whatever you want to do when Yes clicked
                    $(this).dialog('close');
                    window.location.href = 'editOutlet.php?delId=' + delId + '&id=' + id +
                        '&revCenDeptId=' + revCenDeptId + '&deptId=' + deptId;
                },

                '<?php echo showOtherLangText('No') ?>': function() {
                    //Do whatever you want to do when No clicked
                    $(this).dialog('close');
                }
            }
        });

        $("#dialog1").dialog("open");
        $('.custom-header-text').remove();
        $('.ui-dialog-content').prepend(
            '<div class="custom-header-text"><span><?php echo showOtherLangText('Queue1.com Says') ?></span></div>');
    }


    function showMsg() {

        $("#dialog").dialog({
            autoOpen: false,
            modal: true,
            //title     : "Title",
            buttons: {
                '<?php echo showOtherLangText('Ok') ?>': function() {
                    //Do whatever you want to do when Yes clicked
                    $(this).dialog('close');
                }

            }
        });

        $("#dialog").dialog("open");
        $('.custom-header-text').remove();
        $('.ui-dialog-content').prepend(
            '<div class="custom-header-text"><span><?php echo showOtherLangText('Queue1.com Says') ?></span></div>');
    }
    </script>

    <script>
    $(document).ready(function() {

        var setOutLet = $('#setOutlet').val();
        var btnBox = $('#btnBox').css('display');

        if (setOutLet == '0') {

            $('#dropContent').css('display', 'none');
            $('#btnBox').css('display', 'block');

        } else {

            $('#dropContent').css('display', 'block');
            $('#btnBox').css('display', 'none');
            $("#togleBtnBox").append(
                '<button class="btn btn-primary" type="submit">Save</button>&nbsp;<a href="listDepartmentUsers.php" class="btn btn-primary">Back</a>'
            );

        }

    });

    function changeRevInvStatus() {

        var revInv = $("#receiveInv").val();

        if (revInv == '' || revInv == '0') {

            $("#receiveInv").val(1);
        } else {
            $("#receiveInv").val(0);
        }

    }

    $(document).ready(function() {

        var outLetType = $('#outLetType').val();

        if (outLetType == 1) {

            $('.AssignEzeeCategory').show();

        } else {

            $('.AssignEzeeCategory').hide();
        }

    });

    function getOutletType() {

        var outLetType = $('#outLetType').val();

        if (outLetType == 1) {

            $('.AssignEzeeCategory').show();

        } else {

            $('.AssignEzeeCategory').hide();
        }

    }


    $(document).ready(function() {

        var revenueCenterAddress = $('#revCenter').val();
        var addressCheck = $('#addressCheck').val();
        var id = $('#deptUserId').val();

        if (revenueCenterAddress != '' && addressCheck == 1) {

            $.ajax({
                    method: "POST",
                    url: "editOutlet.php",
                    dataType: 'json',
                    data: {
                        id,
                        revenueCenterAddress,
                        addressCheck
                    }
                })
                .done(function(responseObj) {
                    $('#email').val(responseObj.email);
                    $('#address').val(responseObj.address);
                    $('#phone').val(responseObj.phone);
                });

        }

    })





    function showRevCenterAddress() {
        var revenueCenterAddress = $('#revCenter').val();
        var addressCheck = $('#addressCheck').val();
        var addressCheckboxFlag = $("#addressCheck").prop('checked');
        if (revenueCenterAddress != '' && addressCheckboxFlag==true) {

            $('#addressCheck').val(1);

            $.ajax({
                    method: "POST",
                    url: "editOutlet.php",
                    dataType: 'json',
                    data: {
                        revenueCenterAddress: revenueCenterAddress,
                        addressCheck: addressCheck
                    }
                })
                .done(function(responseObj) {
                    $('#email').val(responseObj.email);
                    $('#address').val(responseObj.address);
                    $('#phone').val(responseObj.phone);
                });

        } else {

            $('#addressCheck').val(0);
            var email = $('#email').val();
            var address = $('#address').val();
            var phone = $('#phone').val();

            $("#email").val('');
            $("#phone").val('');
            $("#address").val('');

        }

    }

    //for save btn and back button
    function showRevOutLetItems() {
        var availableProductInOutlet = $('#availableProductInOutlet').val();

        if (availableProductInOutlet == 1) {
            // confirm('You cannot uncheck as it has some item in outlet');
            showMsg();
            $('#setOutlet').prop('checked', true); // Unchecks it
            return true;
        } else {
            var dropContent = document.getElementById('dropContent').style.display;
            var btnBox = $('#btnBox').css('display');
            var outLetType = $('#outLetType').val();

            if (dropContent == 'none' && outLetType == '') {
                $('#saveBtn, #backBtn').remove();
                $("#togleBtnBox").append(
                    '<button class="btn btn-primary" id="saveBtn" type="submit">Save</button>&nbsp;<a href="listDepartmentUsers.php" class="btn btn-primary" id="backBtn">Back</a>'
                );
            }
            if (dropContent == '' || dropContent == 'none') {

                document.getElementById('dropContent').style.display = 'block';
                $('#btnBox').css('display', 'none');
            } else {
                document.getElementById('dropContent').style.display = 'none';
                $('#btnBox').css('display', 'block');
            }
        }

    }



    $("#setOutlet").change(function() {

        var outLetCheck = $("#setOutlet").val();
        var availableProductInOutlet = $('#availableProductInOutlet').val();

        if (outLetCheck == 1 && availableProductInOutlet == 0) {
            $("#setOutlet").val(0);
        } else {
            $("#setOutlet").val(1);
        }

    });


    function getrevCenter() {
        var revenueCenterId = $('#revCenter').val();
        if (revenueCenterId != '') {
            $.ajax({
                    method: "POST",
                    data: {
                        revenueCenterId: revenueCenterId
                    }
                })
                .done(function(htmlRes) {
                    $('#showCatIds').html(htmlRes);
                });
        }
    }

    // function openPopup(id = '') {

    // var w = 700;
    // var h = 550;
    // var title = '<?php echo showOtherLangText('Convert Raw Item'); ?>';
    // var url =
    //     'addOutLetItems.php?outLetId=<?php echo $_GET['revCenDeptId'];?>&deptUserId=<?php echo $_GET['id'];?>&deptUserDeptId=<?php echo $_GET['deptId'];?>';

    // if (id != '') {
    //     var url = 'editOutLetItems.php?id=' + id +
    //         '&outLetId=<?php echo $_GET['revCenDeptId'];?>&deptUserId=<?php echo $_GET['id'];?>&deptUserDeptId=<?php echo $_GET['deptId'];?>';
    // }

    // var left = (screen.width / 2) - (w / 2);
    // var top = (screen.height / 2) - (h / 6);
    // return window.open(url, title,
    //     'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' +
    //     w + ', height=' + h + ', top=' + top + ', left=' + left);
    // }
    </script>

    <script>
    $(document).ready(function() {

        var availableTags = [
            <?php 
	$proRows = getAllProducts();
   
    foreach($proRows as $pId=>$pName){

       
        
        $pName = str_replace("'", '', $pName);
        $pName = preg_replace('/[^\w\s]+/u','' , $pName);

        $itemName = $pName.'('.$pId.')';
         echo "\"$itemName\",";
       
	}
    ?>
        ];

        $("#tags").autocomplete({
            source: availableTags
        });
        $("#edittags").autocomplete({
            source: availableTags
        });

    });

    function openModal(itemRowId) {
        $.ajax({
                method: "POST",
                url: "editOutlet.php",
                dataType: 'json',

                data: {
                    itemRowId: itemRowId,
                    ajaxData: 1
                }
            })
            .done(function(responseObj) {
                $('#edit-PhyStorage #edittags').val(responseObj.itemName);
                $('#edit-PhyStorage #itemType').val(responseObj.itemType);
                $('#edit-PhyStorage #subUnit').val(responseObj.unit);
                $('#edit-PhyStorage #factor').val(responseObj.factor);
                $('#edit-PhyStorage #minQty').val(responseObj.minQty);
                $('#edit-PhyStorage #maxQty').val(responseObj.maxQty);
            });

        $('#edit-PhyStorage #itemRowId').val(itemRowId);
        ///outletItemModalPopup.style.display = "block";
    }

    $('.addItemBtn').click(function() {
        outletItemModalPopup.style.display = "block";
        $('#itemRowId').val('');
        $('#tags').val('');
        $('#itemType').val('');
        $('#subUnit').val('');
        $('#factor').val('');
        $('#minQty').val('');
        $('#maxQty').val('');
    })

    $('.backBtn').click(function() {
        outletItemModalPopup.style.display = "none";

        $('#itemRowId').val('');
        $('#tags').val('');
        $('#itemType').val('');
        $('#subUnit').val('');
        $('#factor').val('');
        $('#minQty').val('');
        $('#maxQty').val('');
    })

    window.onclick = function(event) {
        if (event.target == outletItemModalPopup) {
            outletItemModalPopup.style.display = "none";
        }
    }
    </script>