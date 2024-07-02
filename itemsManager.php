<?php
include('inc/dbConfig.php'); //connection details

if (!isset($_SESSION['adminidusername']))
{
echo "<script>window.location='login.php'</script>";
}

//for excel file upload with Other language
use Shuchkin\SimpleXLSX;
require_once 'SimpleXLSX.php';


//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$rightSideLanguage = ($getLangType == 1) ? 1 : 0; 


$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'item_manager' AND type_id = '0' AND designation_id = '".$_SESSION['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if ($permissionRow)
{
echo "<script>window.location = 'index.php'</script>";
}

if( isset($_FILES['uploadZipFile']['name']) && $_FILES['uploadZipFile']['name'] != '' )
{

$unzip = new ZipArchive;
$out = $unzip->open($_FILES['uploadZipFile']['tmp_name']);
if ($out === TRUE) {
  $unzip->extractTo($bulkImgUploadPath);
  $unzip->close();
  $mes = ''.showOtherLangText('Images uploaded successfully.').'';
} else {
   $mes = ''.showOtherLangText('Images could not uploaded.').'';
}
	
	$handle = opendir($bulkImgUploadPath);
	while($file = readdir($handle)){
	  if($file !== '.' && $file !== '..'){
		resize_image($bulkImgUploadPath.$file, $bulkImgUploadPath.$file, 60,60);
	  }
	}



echo "<script>window.location = 'itemsManager.php?mes=".$mes."'</script>";

}


if( isset($_REQUEST['showFieldsItem']) )
{
$updateQry = " UPDATE tbl_user SET itemUserFilterFields = '".implode(',', $_REQUEST['showFieldsItem'])."' WHERE id = '".$_SESSION['id']."' AND account_id = '".$_SESSION['accountId']."'  ";
mysqli_query($con, $updateQry);
}
elseif( isset($_REQUEST['clearshowFieldsItem'])  )
{
$updateQry = " UPDATE tbl_user SET itemUserFilterFields = '' WHERE id = '".$_SESSION['id']."'  AND account_id = '".$_SESSION['accountId']."' ";
mysqli_query($con, $updateQry);
}


$sql = "select * FROM tbl_user  WHERE id = '".$_SESSION['id']."' AND account_id = '".$_SESSION['accountId']."'  ";
$result = mysqli_query($con, $sql);
$userDetails = mysqli_fetch_array($result);
$itemUserFilterFields = $userDetails['itemUserFilterFields'] ? 	explode(',',$userDetails['itemUserFilterFields']) : null;		


//code to import products from csv && excel file
$fileDataRows = [];
if( isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != '' )
{

$xlsx = SimpleXLSX::parse($_FILES["uploadFile"]["tmp_name"]);

$i=0;
foreach($xlsx->rows() as $row)
{
if($i == 0)
{
	$i++;
	continue;
}			

$rows[] = [
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
}

//----------------------------------


if( is_array($rows) && !empty($rows) )
{

foreach($rows as $row)
{
	
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
		account_id = '".$_SESSION['accountId']."' 
		
		"
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
	
}

echo "<script>window.location = 'itemsManager.php?imported=1'</script>";

}
}//end //code to import products from csv file

if( isset($_GET['delId']) && $_GET['delId'] )
{
$sqlSet = " SELECT s.* FROM tbl_order_details od INNER JOIN tbl_stocks s ON(s.pId=od.pId) AND s.account_id=od.account_id WHERE s.pId = '".$_GET['delId']."' AND s.account_id = '".$_SESSION['accountId']."' ";
$resultSet = mysqli_query($con, $sqlSet);
$stockRow = mysqli_fetch_array($resultSet); 
if(!$stockRow)
{	
$sql = " DELETE FROM tbl_products  WHERE id='".$_GET['delId']."'  AND account_id = '".$_SESSION['accountId']."' ";
mysqli_query($con, $sql);

echo "<script>window.location = 'itemsManager.php?delete=1'</script>";
}
else
{

echo "<script>window.location = 'itemsManager.php?delete=2'</script>";

}
}

//------------------------------Filter--------------------
$cond = '';
if( isset($_GET['storeId']) && ($_GET['storeId']) != '' )
{
$cond = " AND p.storageDeptId = '".$_GET['storeId']."'  AND p.account_id = '".$_SESSION['accountId']."' ";
}
if( isset($_GET['subCatId']) && $_GET['subCatId'])
{
$cond .= " AND c.id = '".$_GET['subCatId']."' AND c.account_id = '".$_SESSION['accountId']."'  ";
}
if( isset($_GET['suppId']) && $_GET['suppId'])
{
$cond .= " AND p.id IN(SELECT productId FROM tbl_productsuppliers WHERE supplierId = '".$_GET['suppId']."' AND account_id = '".$_SESSION['accountId']."' ) ";
}
if( isset($_GET['deptId']) && $_GET['deptId'])
{
$cond .= " AND p.id IN(SELECT productId FROM tbl_productdepartments WHERE deptId = '".$_GET['deptId']."' AND account_id = '".$_SESSION['accountId']."' ) ";
}

if($cond != '')
{
$_SESSION['getVals'] = $_GET;
}
else
{
unset($_SESSION['getVals']);
}
//------------------------------End Filter--------------------

$sql = "
SELECT
p.*,
pc.name parentCatName,
IFNULL(c.name, 'z') childCatName,
s.name storeName,
IF(uc.name!='', uc.name,p.unitC) countingUnit,
IF(up.name!='', up.name,p.unitP) purchaseUnit,
GROUP_CONCAT(distinct d.name) departs,
GROUP_CONCAT( distinct ts.name) suppls
FROM
tbl_products p
LEFT JOIN tbl_stocks st ON
(p.id=st.pId) AND p.account_id = st.account_id
LEFT JOIN tbl_units up ON
(up.id = p.unitP) AND up.account_id = p.account_id
LEFT JOIN tbl_units uc ON
(uc.id = p.unitC) AND uc.account_id = p.account_id
LEFT JOIN tbl_category pc ON
(pc.id = p.parentCatId) AND pc.account_id = p.account_id
LEFT JOIN tbl_category c ON
(c.id = p.catId) AND c.account_id = p.account_id
LEFT JOIN tbl_stores s ON
(s.id = p.storageDeptId) AND s.account_id = p.account_id

LEFT JOIN tbl_productdepartments dc  ON(dc.productId=p.id) AND dc.account_id = p.account_id	
LEFT JOIN tbl_department d ON(dc.deptId = d.id) AND dc.account_id = d.account_id  

LEFT JOIN tbl_productsuppliers ps ON(ps.productId=p.id) AND ps.account_id = p.account_id	
LEFT JOIN tbl_suppliers ts ON(ps.supplierId = ts.id) AND ps.account_id = ts.account_id		
					
WHERE p.account_id = '".$_SESSION['accountId']."'  ".$cond."
GROUP BY p.id ORDER BY p.id DESC ";
$mainQry = mysqli_query($con, $sql);

//---------------------------------------------

$sqlSet = " SELECT * FROM  tbl_category	 WHERE parentId > 0  AND account_id = '".$_SESSION['accountId']."' ORDER BY name ";
$resultSet = mysqli_query($con, $sqlSet);
							
$subCatOptions = '<a class="dropdown-toggle body3" data-bs-toggle="dropdown"
aria-expanded="false">
<span id="subcategoryText">Sub Catagory</span> <i class="fa-solid fa-angle-down"></i>
</a>';
$subCatOptions .= '<ul class="dropdown-menu subcat">';
$subCatOptions .= '<li data-id="" data-value="Sub Catagory"><a class="dropdown-item" href="javascript:void(0)">Sub Catagory</a></li>';
while($catRow = mysqli_fetch_array($resultSet) )
{
$sel = isset($_GET['subCatId']) && $_GET['subCatId'] == $catRow['id']  ? 'selected' : '';
 $subCatOptions .=       '<li data-id="'.$catRow['id'].'" data-value="'.$catRow['name'].'"><a class="dropdown-item '.$sel.'" href="javascript:void(0)">'.$catRow['name'].'</a></li>';
}
$subCatOptions .= '</ul>';


$sqlSet = " SELECT * FROM  tbl_suppliers WHERE account_id = '".$_SESSION['accountId']."'  ORDER BY name ";
$resultSet = mysqli_query($con, $sqlSet);
							
$suppOptions = '<a class="dropdown-toggle body3" data-bs-toggle="dropdown"
aria-expanded="false">
<span id="supplierText">'.showOtherLangText('Supplier').'</span> <i class="fa-solid fa-angle-down"></i>
</a>';
$suppOptions .= '<ul class="dropdown-menu sort_supplier">';
$suppOptions .= '<li data-id="" data-value=""><a class="dropdown-item" href="javascript:void(0)">'.showOtherLangText('Supplier').'</a></li>';
while($departRow = mysqli_fetch_array($resultSet) )
{
$sel = isset($_GET['suppId']) && $_GET['suppId'] == $departRow['id']  ? 'selected' : '';
$suppOptions .= '<li data-id="'.$departRow['id'].'" data-value="'.$departRow['name'].'"><a class="dropdown-item '.$sel.'" href="javascript:void(0)">'.$departRow['name'].'</a></li>';
}
$suppOptions .= '</ul>';


$sqlSet = " SELECT * FROM  tbl_stores WHERE account_id = '".$_SESSION['accountId']."'  ORDER BY name ";
$resultSet = mysqli_query($con, $sqlSet);
							
$storageOptions = '<a class="dropdown-toggle body3" data-bs-toggle="dropdown"
aria-expanded="false">
<span id="storageTxt">'.showOtherLangText('Storage').'</span> <i class="fa-solid fa-angle-down"></i>
</a>';
$storageOptions .= '<ul class="dropdown-menu sort_storage">';
$storageOptions .= '<li data-id="" data-value=""><a class="dropdown-item" href="javascript:void(0)">'.showOtherLangText('Storage').'</a></li>';
while($storeRow = mysqli_fetch_array($resultSet) )
{
$sel = isset($_GET['storeId']) && $_GET['storeId'] == $storeRow['id']  ? 'selected' : '';
$storageOptions .= '<li data-id="'.$storeRow['id'].'" data-value="'.$storeRow['name'].'"><a class="dropdown-item '.$sel.'" href="javascript:void(0)">'.$storeRow['name'].'</a></li>';
}
$storageOptions .= '</ul>';


$sqlSet = " SELECT * FROM  tbl_department WHERE account_id = '".$_SESSION['accountId']."'  ORDER BY name ";
$resultSet = mysqli_query($con, $sqlSet);
							
$deprtOptions = '<a class="dropdown-toggle body3" data-bs-toggle="dropdown"
aria-expanded="false">
<span id="departmentTxt">'.showOtherLangText('Department').'</span> <i class="fa-solid fa-angle-down"></i>
</a>';
$deprtOptions .= '<ul class="dropdown-menu sort_department">';
$deprtOptions .= '<li data-id="" data-value="" ><a class="dropdown-item '.$sel.'" href="javascript:void(0)">'.showOtherLangText('Department').'</a></li>';
while($deprtRow = mysqli_fetch_array($resultSet) )
{
$sel = isset($_GET['deptId']) && $_GET['deptId'] == $deprtRow['id']  ? 'selected' : '';
$deprtOptions .= '<li data-id="'.$deprtRow['id'].'" data-value="'.$deprtRow['name'].'" ><a class="dropdown-item '.$sel.'" href="javascript:void(0)">'.$deprtRow['name'].'</a></li>';
}
$deprtOptions .= '</ul>';
//---------------------------------------------
?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Items Manager - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">

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
                            <h1 class="h1">
                                <?php echo showOtherLangText('Items Manager'); ?>
                            </h1>
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
                                    <h1 class="h1">
                                        <?php echo showOtherLangText('Items Manager'); ?>
                                    </h1>
                                </div>
                            </div>
                            <div class="user d-flex align-items-center">
                                <img src="Assets/images/user.png" alt="user">
                                <p class="body3 m-0 d-inline-block">User</p>
                            </div>
                            <div class="acc-info">
                                <img src="Assets/icons/Q.svg" alt="Logo" class="q-Logo">
                                <div class="dropdown d-flex">
                                    <a class="dropdown-toggle body3" data-bs-toggle="dropdown">
                                        <span> Account</span> <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 1</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 2</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 3</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 4</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="landScape">
                    <div class="container">
                        <h1 class="h1 text-center">For better experience, Please use portrait view.</h1>
                    </div>
                </section>

                <section class="ordDetail userDetail itmMngDetail">
                    <div class="container">
                        <?php if(isset($_GET['added']) || isset($_GET['edit']) || (isset($_GET['delete']) && $_GET['delete'] == 1) || isset($_GET['imported']) || isset($_GET['mes']) ) {?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <p>
                                <?php 
			
			echo isset($_GET['edit']) ? ' '.showOtherLangText('Item Edited Successfully').' ' : '';
			echo isset($_GET['added']) ? ' '.showOtherLangText('Item Added Successfully').' ' : '';
			echo isset($_GET['imported']) ? ' '.showOtherLangText('Item imported Successfully').' ' : '';
			echo isset($_GET['mes']) ? $_GET['mes'] : '';
			
			if( isset($_GET['delete']) && $_GET['delete'] == 1 )
			{
				echo ' '.showOtherLangText('Item Deleted Successfully').' ';
			}
			elseif( isset($_GET['delete']) && $_GET['delete'] == 2 )
			{
				echo ' '.showOtherLangText('This item is in stock or ordered by someone so cannot be deleted').' ';
			}
			
			?>

                            </p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php } ?>
                        <?php if(isset($_GET['error'])) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <p>
                                <?php echo showOtherLangText('User can not be deleted as it is being used in order'); ?>
                            </p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php } ?>
                        <?php if(isset($_GET['delete']) && $_GET['delete']==2) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <p>
                                <?php echo showOtherLangText('This item is in stock or ordered by someone so cannot be deleted'); ?>
                            </p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php } ?>
                        <div class="row itm-Manage justify-content-between">
                            <div class="col-md-6 bkItm-MngBtn">
                                <div class="btnBg">
                                    <a href="setup.php" class="sub-btn std-btn mb-usrBkbtn"><span class="mb-UsrBtn"><i
                                                class="fa-solid fa-arrow-left"></i></span> <span class="dsktp-Btn">
                                            <?php echo showOtherLangText('Back'); ?>
                                        </span></a>
                                </div>
                                <div class="mbItm-MngIcns"> </div>
                            </div>




                            <div class="col-md-6 fetItm-Mng">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="addProduct.php" class="btn btn-white flex-column" style="max-width: 90px;">
                                        <span class="">
                                            <svg width="30" height="30" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 31 30">
                                                <path
                                                    d="M28.4 12.7 16.7 8l-1.9 1.2m13.6 3.5v5.8m0-5.8-7.5 4.6M9.3 18.5v-3m11.6 1.8V29m0-11.7L15.1 15l-3-1.2m-2.8 4.7v5.8L20.9 29l7.5-4.7v-5.8"
                                                    stroke="#8C8FA7" stroke-width="2" stroke-linejoin="round" />
                                                <circle cx="7.9" cy="8.5" r="6.5" stroke="#8C8FA7" stroke-width="2" />
                                                <path d="M4.4 8.5h7M7.9 5v7" stroke="#8C8FA7" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                        <span>
                                            <?php echo showOtherLangText('Add Item'); ?>
                                        </span>
                                    </a>
                                    <!-- <div class="itmFeat-Div update itmMng-Feature">
                                    </div> -->
                                    

                                    <!-- <div class="itmFeat-Div itmMng-Feature"> -->
                                        <form action="itemsManager.php" id="upload_form" name="upload_form"
                                            method="post" enctype="multipart/form-data">
                                            <div class="dropdown"> 
                                                <a 
                                                    href="javascript:void(0)" 
                                                    class="dropdown-toggle btn btn-white flex-column position-relative" 
                                                    style="max-width: 110px;"
                                                    id="triggerImportDrop" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                >
                                                    <span class="pe-2">
                                                        <svg width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 31 30"><path d="M25.7 7.7q2.5-.1 3.2.4l.7.7q.5.8.4 3.2v11.5q.2 3.3-.7 4.1-.9 1-4.2.8H18q-3.3.2-4.2-.8c-.7-.7-.7-1.8-.7-4.1V12q-.1-2.4.4-3.2l.7-.7q.7-.5 3.1-.4" stroke="#8C8FA7" stroke-width="2"/><path d="M17.9 7.7c0-1.3 1-2.4 2.4-2.4h2.4a2.4 2.4 0 1 1 0 4.8h-2.4Q18 10 17.9 7.7Z" stroke="#8C8FA7" stroke-width="2"/><path d="M17.9 16.2H25M17.9 21h4.8" stroke="#8C8FA7" stroke-width="2" stroke-linecap="round"/><path d="m5 19-.7.6.7.7.7-.7zM6 7a1 1 0 1 0-2 0zM.3 15.5l4 4 1.4-1.4-4-4zm5.4 4 4-4-1.4-1.4-4 4zM6 19V7H4v12z" fill="#8C8FA7"/></svg>
                                                    </span>
                                                    <span style="white-space: initial;">
                                                        <?php echo showOtherLangText('Import Items List') ?>
                                                    </span> <i class="fa-solid fa-angle-down position-absolute top-50 translate-middle-y" style="right: .5rem;"></i>
                                                    <!-- <p class="btn2 d-flex justify-content-center align-items-center">
                                                    </p> -->
                                                </a>
                                                <ul class="dropdown-menu p-1" id="dropdownMenu" aria-labelledby="triggerImportDrop">
                                                    <li id="btnFileUpload">
                                                        <a class="dropdown-item py-2 ent-Gstno" onClick="return uploadFile();" href="javascript:void(0)">
                                                            <?php echo showOtherLangText('Import Items List') ?>
                                                        </a>
                                                        <input type="file" id="uploadFile" name="uploadFile"
                                                            style="display:none">
                                                    </li> 
                                                    <li>
                                                        <a class="dropdown-item py-2 gt-Pos" id="btnZipFileUpload" onClick="return uploadZip();"  href="javascript:void(0)">Upload Photos (zip file)</a>
                                                        <input type="file" id="uploadZipFile" name="uploadZipFile"
                                                            style="display:none">
                                                    </li>  
                                                    <li>
                                                        <a class="dropdown-item py-2 dwn-Sample" href="<?php echo $rightSideLanguage == 1 ? 'excelSampleFile/hebrew/import-product-hebrew-lang.xlsx' : 'excelSampleFile/english/import-product-english-lang.xlsx'; ?>">
                                                            <i class="fa-solid fa-arrow-down"></i> 
                                                            <span>
                                                                <?php echo showOtherLangText('Download Sample File'); ?>
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </form>
                                    <!-- </div> -->
                                     
                                </div>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        var dropdownMenu = document.getElementById('dropdownMenu');
                                    
                                        // Prevent the dropdown from closing when clicking inside it
                                        dropdownMenu.addEventListener('click', function(event) {
                                        event.stopPropagation(); // Prevents the click event from bubbling up to the document
                                        });
                                    
                                        // Close the dropdown if the user clicks outside of it
                                        window.addEventListener('click', function(event) {
                                        var dropdownToggle = document.getElementById('triggerImportDrop');
                                        if (!dropdownToggle.contains(event.target)) {
                                            var dropdownMenu = document.querySelector('.dropdown-menu');
                                            if (dropdownMenu.classList.contains('show')) {
                                            dropdownMenu.classList.remove('show');
                                            }
                                        }
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>

                    <div class="itmMng-Src">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Search Box Start -->
                                    <div class="input-group srchBx">
                                        <input type="search" onKeyUp="myFunction()" id="Search" class="form-control"
                                            placeholder="<?php echo showOtherLangText('Search Item'); ?>" name="search"
                                            id="search" aria-label="Search">
                                        <div class="input-group-append">
                                            <button class="btn" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Search Box End -->
                                </div>
                                <!-- Filter Btn Start -->
                                <div class="itmMng-filtBtn">
                                    <div class="itmFilt-Mng">
                                        <a href="javascript:void(0)" class="head-Filter itmMng-FilterBtn">
                                            <img src="Assets/icons/filter.svg" alt="Filter">
                                        </a>
                                    </div>
                                </div>
                                <!-- Filter Btn End -->
                                <div class="col-md-6 expStrdt d-flex justify-content-end align-items-end">
                                    <div class="d-flex align-items-center itmMng-xlIcn">
                                        <div class="chkStore">
                                            <a href="item_excel.php" target="_blank">
                                                <img src="Assets/icons/stock-xcl.svg" alt="Stock Xcl">
                                            </a>
                                        </div>
                                        <div class="chkStore">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/stock-pdf.svg" alt="Stock PDF">
                                            </a>
                                        </div>

                                    </div>
                                </div>

                                <div class="clnItm-MngDrp"></div>

                            </div>
                        </div>
                    </div>
                    <form action="itemsManager.php" id="form_sort" method="get">
                        <div class="container itmMng-Tblhead position-relative">
                            <!-- Item Table Head Start -->
                            <div class="d-flex align-items-center itmTable">
                                <div class="tb-head imgItm-MngClm">
                                    <div class="d-flex align-items-center">
                                        <p>Photo</p>
                                        <span class="dblArrow">
                                            <a href="javascript:void(0)" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-up"></i></a>
                                            <a href="javascript:void(0)" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-down"></i></a>
                                        </span>
                                    </div>
                                </div>
                                <div class="align-items-center brItm-MngClm">
                                    <div class="tb-head d-flex align-items-center item-MngClm">
                                        <p>Item</p>
                                        <span class="dblArrow">
                                            <a href="javascript:void(0)" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-up"></i></a>
                                            <a href="javascript:void(0)" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-down"></i></a>
                                        </span>
                                    </div>
                                    <div class="tb-head d-flex align-items-center brCode-MngClm">
                                        <p>Bar Code</p>
                                        <span class="dblArrow">
                                            <a href="javascript:void(0)" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-up"></i></a>
                                            <a href="javascript:void(0)" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-down"></i></a>
                                        </span>
                                    </div>
                                    <div class="tb-head d-flex align-items-center unit-MngClm">
                                        <p class="untPf">Unit P/F/ <br> Unit C.</p>
                                        <span class="dblArrow">
                                            <a href="javascript:void(0)" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-up"></i></a>
                                            <a href="javascript:void(0)" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-down"></i></a>
                                        </span>
                                    </div>
                                </div>
                                <!-- <form action="listProducts.php"  method="get"> -->
                                <div class="align-items-center drpItm-MngClm drpHead-ItmMng">
                                    <div class="tb-head drpDwn-ItmMng subCat-ItmMng">
                                        <div class="d-flex align-items-center">
                                            <div id="subcat_dropdown" class="dropdown d-flex position-relative">
                                                <?php echo $subCatOptions; ?>

                                            </div>
                                        </div>
                                        <input type="hidden" name="subCatId" id="subCatId"
                                            value="<?php if(isset($_GET['subCatId'])) { echo $_GET['subCatId']; } ?>">
                                    </div>
                                    <div class="tb-head drpDwn-ItmMng supItm-Mng">
                                        <div class="d-flex align-items-center">
                                            <div id="supplier_dropdown" class="dropdown d-flex position-relative">
                                                <?php echo $suppOptions; ?>
                                            </div>
                                        </div>
                                        <input type="hidden" name="suppId" id="suppId"
                                            value="<?php if(isset($_GET['suppId'])) { echo $_GET['suppId']; } ?>">
                                    </div>
                                    <div class="tb-head drpDwn-ItmMng deptItm-Mng">
                                        <div class="d-flex align-items-center">
                                            <div id="department_dropdown" class="dropdown d-flex position-relative">
                                                <?php echo $deprtOptions; ?>
                                            </div>
                                        </div>
                                        <input type="hidden" name="deptId" id="deptId"
                                            value="<?php if(isset($_GET['deptId'])) { echo $_GET['deptId']; } ?>">
                                    </div>
                                    <div class="tb-head drpDwn-ItmMng strgItm-Mng">
                                        <div class="d-flex align-items-center">
                                            <div id="storage_dropdown" class="dropdown d-flex position-relative">
                                                <?php echo $storageOptions; ?>
                                            </div>
                                        </div>
                                        <input type="hidden" name="storeId" id="storeId"
                                            value="<?php if(isset($_GET['storeId'])) { echo $_GET['storeId']; } ?>">
                                    </div>
                                </div>
                                <!-- </form> -->
                                <div class="align-items-center prcItm-MngClm">
                                    <div class="tb-head lastItm-PrcCol">
                                        <div class="d-flex align-items-center">
                                            <p>Last <br>
                                                Price</p>
                                            <span class="dblArrow">
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="tb-head stockItm-PrcCol">
                                        <div class="d-flex align-items-center">
                                            <p>Stock <br>
                                                Price</p>
                                            <span class="dblArrow">
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="icnItm-MngClm">
                                    &nbsp;
                                </div>
                            </div>
                            <!-- Item Table Head End -->
                        </div>
                    </form>

                    <div id="boxscroll">
                        <div class="container cntTable">
                            <?php 
					$x= 0;
					while($row = mysqli_fetch_array($mainQry))
					{
						$color = ($x%2 == 0)? 'white': '#FFFFCC';
						$x++;
						
						$deptNames = $row['departs'];//getProdcutDepartments($row['id']);
						$supNames = $row['suppls'];//getProdcutSuppls($row['id']);
						
						$catNames = ($row['childCatName'] == 'z' ? '' :  $row['childCatName']);
						
						/*$sqlSet = " SELECT * FROM tbl_stores WHERE id = '".$row['storageDeptId']."' ";
						$resultSet = mysqli_query($con, $sqlSet);
						$storeRes = mysqli_fetch_array($resultSet);*/
						$storeName = $row['storeName'];
							

						$img = '';
                       // echo '<pre>';
                       // print_r($row['barCode']);
                       // exit;
                       
						if( $row['imgName'] != '' && file_exists( dirname(__FILE__)."/uploads/". $accountImgPath .'/products/'.$row['imgName'] ) )
						 {	
						 	
							$img =  '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$row['imgName'].'" width="60" height="60">';
						 }
						  
						 $colsValArr = 
						  [
							1 => $img,
							2 => $row['itemName'],
							3 => $row['barCode'],
							4 => $row['purchaseUnit'] .'/'.$row['factor'].'/'.$row['countingUnit'],
							
							5 => $catNames,
							6 => $supNames,
							7 => str_replace(',', '<br>', $deptNames),
							8 => $storeName,
							9 => ($row['minLevel']),
							10 => ($row['maxLevel']),
							11 => getPrice($row['price']) .' '.$getDefCurDet['curCode'],
							12 => getPrice($row['stockPrice']) .' '.$getDefCurDet['curCode'],
						  ];
						  
						
				?>
                            <div class="target d-flex align-items-center border-bottom itmBody itemManager-Task">
                                <div class="itmTask-Mng align-items-center">
                                    <div class="tb-bdy imgItm-MngClm">
                                        <?php echo $img; ?>
                                    </div>
                                    <div class="align-items-center brItm-MngClm">
                                        <div class="tb-bdy item-MngClm">
                                            <p>
                                                <?php echo $row['itemName']; ?>
                                            </p>
                                        </div>
                                        <div class="tb-bdy brCode-MngClm">
                                            <p>
                                                <?php echo $row['barCode']; ?>
                                            </p>
                                        </div>
                                        <div class="tb-bdy unit-MngClm bdyCol-Itm">
                                            <p><span class="mb-UnitItm">Unit P/F/C</span>
                                                <?php echo $row['purchaseUnit'] .'/'.$row['factor'].'/'.$row['countingUnit']; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="align-items-center drpItm-MngClm">
                                        <div class="tb-bdy drpDwn-ItmMng subCat-ItmMng">
                                            <p>
                                                <?php echo $catNames; ?>
                                            </p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng supItm-Mng">
                                            <p>
                                                <?php echo $supNames; ?>
                                            </p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng deptItm-Mng">
                                            <p>
                                                <?php echo $deptNames; ?>
                                            </p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng strgItm-Mng">
                                            <p>
                                                <?php echo $storeName; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="align-items-center prcItm-MngClm">
                                        <div class="tb-bdy lastItm-PrcCol bdyCol-Itm">
                                            <p><span class="mbLst-ItmMng">L. Price</span>
                                                <?php echo getPrice($row['price']) .' '.$getDefCurDet['curCode']; ?>
                                            </p>
                                        </div>
                                        <div class="tb-bdy stockItm-PrcCol bdyCol-Itm">
                                            <p><span class="mbLst-ItmMng">S. Price</span>
                                                <?php echo getPrice($row['stockPrice']) .' '.$getDefCurDet['curCode']; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="icnItm-MngClm">
                                        <div class="tb-bdy usrOpt-Clm d-flex align-items-center">
                                            <a href="editProduct.php?id=<?php echo $row['id'];?>" class="userLink">
                                                <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                            </a>
                                            <a href="javascript:void(0)"
                                                onClick="getDelNumb('<?php echo $row['id'];?>')" class="userLink">
                                                <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <a href="javascript:void(0)" class="statusLink mb-itmMngLink"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="align-items-center mbTask">
                                <a href="javascript:void(0)" class="statusLink mb-itmMngLink"><i
                                        class="fa-solid fa-angle-down"></i></a>
                            </div>
                        </div>


                    </div>
            </div>

            <!-- Item Table Body End -->


            </section>

        </div>
    </div>
    </div>


    <div id="dialog" style="display: none;">
        <?php echo showOtherLangText('Are you sure to delete this record?') ?>
    </div>

    <?php require_once('footer.php');?>
    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <div class="modal" tabindex="-1" id="delete-popup" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1">
                        <?php echo showOtherLangText('Are you sure to delete this record?') ?>
                    </h1>
                </div>

                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="button" data-bs-dismiss="modal" class="btn sub-btn std-btn">
                            <?php echo showOtherLangText('No'); ?>
                        </button>
                    </div>
                    <div class="btnBg">
                        <button type="button" onclick="" class="deletelink btn sub-btn std-btn">
                            <?php echo showOtherLangText('Yes'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    $(document).ready(function () {
        $(".subcat").on("click", "a", function (e) {
            var $this = $(this).parent();
            $("#subcategoryText").text($this.data("value"));
            $("#subCatId").val($this.data("id"));
            $("#form_sort").submit();
        });
        $(".sort_supplier").on("click", "a", function (e) {
            var $this = $(this).parent();
            $("#supplierText").text($this.data("value"));
            $("#suppId").val($this.data("id"));
            $("#form_sort").submit();
        });
        $(".sort_department").on("click", "a", function (e) {
            var $this = $(this).parent();
            $("#departmentTxt").text($this.data("value"));
            $("#deptId").val($this.data("id"));
            $("#form_sort").submit();
        });
        $(".sort_storage").on("click", "a", function (e) {
            var $this = $(this).parent();
            $("#storageTxt").text($this.data("value"));
            $("#storeId").val($this.data("id"));
            $("#form_sort").submit();
        });
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('subCatId')) {
            var subcatid = urlParams.get('subCatId');
            if (subcatid !== '') {
                $("#subcategoryText").text($("#subcat_dropdown .selected").text());
            }
            var suppId = urlParams.get('suppId');
            if (suppId !== '') {
                $("#supplierText").text($("#supplier_dropdown .selected").text());
            }
            var deptId = urlParams.get('deptId');
            if (deptId !== '') {
                $("#departmentTxt").text($("#department_dropdown .selected").text());
            }
            var storeId = urlParams.get('storeId');
            if (storeId !== '') {
                $("#storageTxt").text($("#storage_dropdown .selected").text());
            }
        }

    });
    function myFunction() {
        // Declare variables
        var input = document.getElementById("Search");
        var filter = input.value.toLowerCase();
        var nodes = document.querySelectorAll('.target');

        for (i = 0; i < nodes.length; i++) {
            if (nodes[i].innerText.toLowerCase().includes(filter)) {
                nodes[i].style.setProperty("display", "block", "important");
            } else {
                nodes[i].style.setProperty("display", "none", "important");
            }
        }


    }

    $("#Search").on("search", function (evt) {
        if ($(this).val().length == 0) {
            resetData();
        }
    });

    function resetData() {

        $('#search').val('');
        myFunction();
    }
</script>
<script>
    function getDelNumb(delId) {

        var newOnClick = "window.location.href='itemsManager.php?delId=" + delId + "'";

        $('.deletelink').attr('onclick', newOnClick);
        $('#delete-popup').modal('show');
    }
</script>
<script type="text/javascript">
    var fileupload = document.getElementById("uploadFile");
    var button = document.getElementById("btnFileUpload");
    button.onclick = function () {
        fileupload.click();
    };
    fileupload.onchange = function () {

        var rightSideLanguage = '<?php echo $rightSideLanguage ?>';
        //if (rightSideLanguage == 1) {

        //document.getElementById('upload_form').action = 'excel/import/importProducts.php'; 
        //}

        document.getElementById('upload_form').submit();
    };



    function uploadZip() {
        var fileupload1 = document.getElementById("uploadZipFile");
        var button1 = document.getElementById("btnZipFileUpload");
        button1.onclick = function () {
            fileupload1.click();
        };
        fileupload1.onchange = function () {
            document.getElementById('upload_form').submit();
        };
    }

</script>