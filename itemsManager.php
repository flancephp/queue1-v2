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
echo "<script>window.location='index.php'</script>";
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



echo "<script>window.location='listProducts.php?mes=".$mes."'</script>";

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

echo "<script>window.location='listProducts.php?imported=1'</script>";

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

echo "<script>window.location='listProducts.php?delete=1'</script>";
}
else
{

echo "<script>window.location='listProducts.php?delete=2'</script>";

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
							
$subCatOptions = '<select class="form-control" name="subCatId" onchange="this.form.submit()">';
$subCatOptions .= '<option value="">'.showOtherLangText('Sub Category').'</option>';
while($catRow = mysqli_fetch_array($resultSet) )
{
$sel = isset($_GET['subCatId']) && $_GET['subCatId'] == $catRow['id']  ? 'selected="selected"' : '';
$subCatOptions .= '<option value="'.$catRow['id'].'" '.$sel.'>'.$catRow['name'].'</option>';
}
$subCatOptions .= '</select>';


$sqlSet = " SELECT * FROM  tbl_suppliers WHERE account_id = '".$_SESSION['accountId']."'  ORDER BY name ";
$resultSet = mysqli_query($con, $sqlSet);
							
$suppOptions = '<select class="form-control" name="suppId" onchange="this.form.submit()">';
$suppOptions .= '<option value="">'.showOtherLangText('Supplier').'</option>';
while($departRow = mysqli_fetch_array($resultSet) )
{
$sel = isset($_GET['suppId']) && $_GET['suppId'] == $departRow['id']  ? 'selected="selected"' : '';
$suppOptions .= '<option value="'.$departRow['id'].'"  '.$sel.'>'.$departRow['name'].'</option>';
}
$suppOptions .= '</select>';


$sqlSet = " SELECT * FROM  tbl_stores WHERE account_id = '".$_SESSION['accountId']."'  ORDER BY name ";
$resultSet = mysqli_query($con, $sqlSet);
							
$storageOptions = '<select class="form-control" name="storeId" onchange="this.form.submit()">';
$storageOptions .= '<option value="">'.showOtherLangText('Storage').'</option>';
while($storeRow = mysqli_fetch_array($resultSet) )
{
$sel = isset($_GET['storeId']) && $_GET['storeId'] == $storeRow['id']  ? 'selected="selected"' : '';
$storageOptions .= '<option value="'.$storeRow['id'].'" '.$sel.'>'.$storeRow['name'].'</option>';
}
$storageOptions .= '</select>';


$sqlSet = " SELECT * FROM  tbl_department WHERE account_id = '".$_SESSION['accountId']."'  ORDER BY name ";
$resultSet = mysqli_query($con, $sqlSet);
							
$deprtOptions = '<select class="form-control" name="deptId" onchange="this.form.submit()">';
$deprtOptions .= '<option value="">'.showOtherLangText('Department').'</option>';
while($deprtRow = mysqli_fetch_array($resultSet) )
{
$sel = isset($_GET['deptId']) && $_GET['deptId'] == $deprtRow['id']  ? 'selected="selected"' : '';
$deprtOptions .= '<option value="'.$deprtRow['id'].'" '.$sel.'>'.$deprtRow['name'].'</option>';
}
$deprtOptions .= '</select>';
//---------------------------------------------
?>
<!DOCTYPE html>
<html lang="en">

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
                <nav class="navbar d-flex flex-wrap align-items-stretch">
                    <div>
                        <div class="logo">
                            <img src="Assets/icons/logo_Q.svg" alt="Logo" class="lg-Img">
                            <div class="clsBar" id="clsBar">
                                <a href="javascript:void(0)"><i class="fa-solid fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <div class="nav-bar">
                            <ul class="nav flex-column h2">
                                <li class="nav-item dropdown dropend">
                                    <a class="nav-link text-center dropdown-toggle" aria-current="page" href="index.php"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="Assets/icons/new_task.svg" alt="Task" class="navIcon">
                                        <img src="Assets/icons/new_task_hv.svg" alt="Task" class="mb_navIcn">
                                        <p>New Task</p>
                                    </a>
                                    <ul class="dropdown-menu nwSub-Menu" aria-labelledby="navbarDropdown">
                                        <li><a class="nav-link nav_sub" aria-current="page" href="index.php">
                                                <img src="Assets/icons/new_order.svg" alt="New order"
                                                    class="navIcon align-middle">
                                                <img src="Assets/icons/new_order_hv.svg" alt="New order"
                                                    class="mb_nvSubIcn align-middle">
                                                <span class="align-middle">New Order</span>
                                            </a>
                                        </li>
                                        <li><a class="nav-link nav_sub" aria-current="page" href="newRequisition.php">
                                                <img src="Assets/icons/new_req.svg" alt="Req"
                                                    class="navIcon align-middle">
                                                <img src="Assets/icons/new_req_hv.svg" alt="Req"
                                                    class="mb_nvSubIcn align-middle">
                                                <span class="align-middle">New Requisition</span></a>
                                        </li>
                                        <li><a class="nav-link nav_sub" aria-current="page" href="javascript:void(0)">
                                                <img src="Assets/icons/new_stock.svg" alt="Stock"
                                                    class="navIcon align-middle">
                                                <img src="Assets/icons/new_stock_hv.svg" alt="Stock"
                                                    class="mb_nvSubIcn align-middle">
                                                <span class="align-middle">New Stocktake</span></a>
                                        </li>
                                        <li><a class="nav-link nav_sub" aria-current="page" href="javascript:void(0)">
                                                <img src="Assets/icons/new_prod.svg" alt="Product"
                                                    class="navIcon align-middle">
                                                <img src="Assets/icons/new_prod_hv.svg" alt="Product"
                                                    class="mb_nvSubIcn align-middle">
                                                <span class="align-middle">New Production</span></a>
                                        </li>
                                        <li><a class="nav-link nav_sub" aria-current="page" href="javascript:void(0)">
                                                <img src="Assets/icons/new_payment.svg" alt="Payment"
                                                    class="navIcon align-middle">
                                                <img src="Assets/icons/new_payment_hv.svg" alt="Payment"
                                                    class="mb_nvSubIcn align-middle">
                                                <span class="align-middle">New Payment</span></a>
                                        </li>
                                        <li><a class="nav-link nav_sub" aria-current="page" href="javascript:void(0)">
                                                <img src="Assets/icons/new_invoice.svg" alt="Invoice"
                                                    class="navIcon align-middle">
                                                <img src="Assets/icons/new_invoice_hv.svg" alt="Invoice"
                                                    class="mb_nvSubIcn align-middle">
                                                <span class="align-middle">New Invoice</span></a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-center" href="runningTask.php">
                                        <img src="Assets/icons/run_task.svg" alt="Run Task" class="navIcon">
                                        <img src="Assets/icons/run_task_hv.svg" alt="Run Task"
                                            class="navIcon mb_navIcn">
                                        <p>Running Tasks</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-center" href="history.php">
                                        <img src="Assets/icons/office.svg" alt="office" class="navIcon">
                                        <img src="Assets/icons/office_hv.svg" alt="office" class="mb_navIcn">
                                        <p>Office</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-center" href="stockView.php">
                                        <img src="Assets/icons/storage.svg" alt="storage" class="navIcon">
                                        <img src="Assets/icons/storage_hv.svg" alt="storage" class="mb_navIcn">
                                        <p>Storage</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-center" href="revenueCenter.php">
                                        <img src="Assets/icons/revenue_center.svg" alt="Revenue" class="navIcon">
                                        <img src="Assets/icons/revenue_center_hv.svg" alt="Revenue" class="mb_navIcn">
                                        <p>Revenue Centers</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="nav-bar lgOut">
                        <ul class="nav flex-column h2">
                            <li class="nav-item">
                                <a class="nav-link active text-center" href="setup.php">
                                    <img src="Assets/icons/setup.svg" alt="setup" class="navIcon">
                                    <img src="Assets/icons/setup_hv.svg" alt="setup" class="mb_navIcn">
                                    <p>Setup</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-center" href="javascript:void(0)">
                                    <img src="Assets/icons/logout.svg" alt="logout" class="navIcon">
                                    <img src="Assets/icons/logout_hv.svg" alt="logout" class="mb_navIcn">
                                    <p>Log Out</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1">Items Manager</h1>
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
                                    <h1 class="h1">Items Manager</h1>
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
                    <?php if(isset($_GET['added']) || isset($_GET['edit']) || isset($_GET['delete'])) {?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p><?php 
			
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
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                            <?php if(isset($_GET['error'])) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo showOtherLangText('User can not be deleted as it is being used in order'); ?></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                        <div class="row itm-Manage">
                            <div class="col-md-6 bkItm-MngBtn">
                                <div class="btnBg">
                                    <a href="setup.php" class="sub-btn std-btn mb-usrBkbtn"><span class="mb-UsrBtn"><i
                                                class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                </div>
                            </div>
                            <div class="mbItm-MngIcns">

                            </div>
                            <div class="col-md-6 fetItm-Mng">
                                <div class="itmLnk-Row">
                                    <div class="itmFeat-Div itmMng-Feature">
                                        <a href="addProduct.php" class="itm-Feature">
                                            <span class="add-Itm"></span>
                                            <p class="btn2 ad-Span"><?php echo showOtherLangText('Add Item'); ?></p>
                                        </a>
                                    </div>

                                    <div class="itmFeat-Div itmMng-Feature">
                                        <a href="javascript:void(0)" class="dropdown-toggle itm-Feature" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="impItem"></span>
                                            <p class="btn2 d-flex justify-content-center align-items-center">
                                                <span><?php echo showOtherLangText('Import Items List') ?></span> <i class="fa-solid fa-angle-down"></i>
                                            </p>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item ent-Gstno" href="javascript:void(0)"><?php echo showOtherLangText('Import Items List') ?></a>
                                            </li>
                                            <li><a class="dropdown-item gt-Pos" href="javascript:void(0)">Upload Photos
                                                    (zip file)</a>
                                            </li>
                                            <li><a class="dropdown-item dwn-Sample" href="<?php echo $rightSideLanguage == 1 ? 'excelSampleFile/hebrew/import-product-hebrew-lang.xlsx' : 'excelSampleFile/english/import-product-english-lang.xlsx'; ?>"> <i
                                                        class="fa-solid fa-arrow-down"></i> <span><?php echo showOtherLangText('Download Sample File'); ?></span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="itmMng-Src">
                        <div class="container">
                        <div class="row">
                                <div class="col-md-6">
                                    <!-- Search Box Start -->
                                    <div class="input-group srchBx">
                                        <input type="search" class="form-control" placeholder="Search" id="srch"
                                            aria-label="Search">
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
                                            <a href="javascript:void(0)">
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
                            <div class="align-items-center drpItm-MngClm drpHead-ItmMng">
                                <div class="tb-head drpDwn-ItmMng subCat-ItmMng">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span>Sub Catagory</span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0)">Sub Catagory
                                                        1</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Sub Catagory
                                                        2</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Sub Catagory
                                                        3</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Sub Catagory
                                                        4</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tb-head drpDwn-ItmMng supItm-Mng">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span>Supplier</span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0)">Supplier 1</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Supplier 2</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Supplier 3</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Supplier 4</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tb-head drpDwn-ItmMng deptItm-Mng">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span>Department</span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0)">Department 1</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Department 2</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Department 3</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Department 4</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tb-head drpDwn-ItmMng strgItm-Mng">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span>Storage</span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0)">Storage 1</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Storage 2</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Storage 3</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Storage 4</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

                    <!-- Item Table Body Start -->
                    <div id="boxscroll">
                        <div class="container cntTable">
                            <div class="d-flex align-items-center border-bottom itmBody itemManager-Task">
                                <div class="itmTask-Mng align-items-center">
                                    <div class="tb-bdy imgItm-MngClm">
                                        <img src="Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="align-items-center brItm-MngClm">
                                        <div class="tb-bdy item-MngClm">
                                            <p>Tomato</p>
                                        </div>
                                        <div class="tb-bdy brCode-MngClm">
                                            <p><span class="mb-CodeItm">Code</span> <strong>18</strong> kg</p>
                                        </div>
                                        <div class="tb-bdy unit-MngClm bdyCol-Itm">
                                            <p><span class="mb-UnitItm">Unit P/F/C</span> 2.2$</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center drpItm-MngClm">
                                        <div class="tb-bdy drpDwn-ItmMng subCat-ItmMng">
                                            <p>Vegetables</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng supItm-Mng">
                                            <p>Mbero shop</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng deptItm-Mng">
                                            <p>Kitchen</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng strgItm-Mng">
                                            <p>Vegetables/Diary</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center prcItm-MngClm">
                                        <div class="tb-bdy lastItm-PrcCol bdyCol-Itm">
                                            <p><span class="mbLst-ItmMng">L. Price</span> 1$</p>
                                        </div>
                                        <div class="tb-bdy stockItm-PrcCol bdyCol-Itm">
                                            <p><span class="mbLst-ItmMng">S. Price</span> 1$</p>
                                        </div>
                                    </div>
                                    <div class="icnItm-MngClm">
                                        <div class="tb-bdy usrOpt-Clm d-flex align-items-center">
                                            <a href="javascript:void(0)" class="userLink">
                                                <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                            </a>
                                            <a href="javascript:void(0)" class="userLink">
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

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
</body>

</html>