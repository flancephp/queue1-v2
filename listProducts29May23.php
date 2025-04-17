<?php
session_start();
include('inc/dbConfig.php'); //connection details

if (!isset($_SESSION['adminidusername']))
{
	echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

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
		  $mes = 'Images uploaded successfully.';
		} else {
		   $mes = 'Images could not uploaded.';
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


//code to import products from csv file
$fileDataRows = [];
if( isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != '' )
{

use Shuchkin\SimpleXLSX;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require_once __DIR__.'/excelReader/SimpleXLSX.php';

echo '<h1>Parse books.xslx</h1><pre>';
if ($xlsx = SimpleXLSX::parse('books.xlsx')) {
    print_r($xlsx->rows());
} else {
    echo SimpleXLSX::parseError();
}
echo '<pre>';

/*

	$rows = csv_to_array($_FILES['uploadFile']['tmp_name'], ',');
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
*/

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
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Queue1.com</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="shortcut icon" href="favicon_16.ico" />
    <link rel="bookmark" href="favicon_16.ico" />
    <!-- site css -->
    <link rel="stylesheet" href="dist/css/site.min.css">
    <link rel="stylesheet" href="css/custom.css">

    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,800,700,400italic,600italic,700italic,800italic,300italic"
        rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>

    <script type="text/javascript" src="dist/js/site.min.js"></script>
    <link rel="stylesheet" href="css/theme.blue.css">

    <link rel="stylesheet" type="text/css" href="css/newstyle1.php"> <!-- New CSS -->

    <script src="js/jquery.tablesorter.js"></script>


    <script id="js">
    $(function() {

        // BONUS TIP: disable a column using jQuery data directly
        // but do it before the table initializes
        // this code disables the sort on the "Date" column


        $("table").tablesorter({
            theme: 'blue',

            headers: {
                // disable sorting of the first & second column - before we would have to had made two entries
                // note that "first-name" is a class on the span INSIDE the first column th cell
                /*'.Number, .SettledDate' : {
                  // disable it by setting the property sorter to false
                  sorter: true
                }*/
            }
        });
    });
    </script>
</head>

<body onLoad="setup()">
    <div class="container-fluid">
        <div class="row">
            <?php require_once('header.php');?>
        </div>

        <div class="row">
            <div class="col-md-2 reverse-nav-section">
                <?php require_once('nav.php');?>
            </div>
            <div class="col-md-10 newClm-10">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo showOtherLangText('Items Manager'); ?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="content-row">

                            <div class="row table-responsive">
                                <?php $color = (isset($_GET['delete']) && $_GET['delete'] > 0) ? 'color: #FF0000' : 'color: #038B00';?>
                                <h6 style="<?php echo $color ?>"><?php 
					
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
					
					?></h6>




                                <form action="listProducts.php" id="upload_form" name="upload_form" method="post"
                                    enctype="multipart/form-data">

                                    <table cellpadding="5" cellspacing="5" align="center" style="text-align: center;">
                                        <tr>
                                            <td>
                                                <button class="btn btn-primary" type="button" style="width: 149px;"
                                                    onClick="window.location.href='addProduct.php?catId=<?php echo $_GET['catId'];?>&&deptId=<?php echo $_GET['deptId'];?>'"><?php echo showOtherLangText('Add
                                                    Product'); ?></button>

                                                &nbsp;&nbsp;&nbsp;&nbsp;

                                                <input type="button" id="btnFileUpload" class="btn btn-primary stay-btn"
                                                    value="Import Items List" style="width: 149px;"
                                                    onClick="return uploadFile();" />
                                                <!--<button  class="btn btn-primary"  id="browse">Import Items List</button>-->

                                                <input type="file" id="uploadFile" name="uploadFile"
                                                    style="display:none">

                                                &nbsp;&nbsp;&nbsp;&nbsp;

                                                <input type="button" id="btnZipFileUpload"
                                                    class="btn btn-primary stay-btn" value="Upload Zip File"
                                                    style="width: 149px;" onClick="return uploadZip();" />
                                                <!--<button  class="btn btn-primary"  id="browse">Import Items List</button>-->

                                                <input type="file" id="uploadZipFile" name="uploadZipFile"
                                                    style="display:none">

                                                &nbsp;&nbsp;&nbsp;&nbsp;

                                                <button class="btn btn-primary" type="button" style="width: 149px;"
                                                    onClick="window.location.href='setup.php'"><?php echo showOtherLangText('Back'); ?></button>

                                            </td>

                                        </tr>
                                    </table>
                                    <div style="text-align: center; padding-top: 10px;"> <a href="importList.csv"
                                            target="_blank"><?php echo showOtherLangText('Download Sample File'); ?></a>
                                    </div><br>


                                    <div class="lst-row">

                                        <div class="itm-srch"><input type="text" name="search" class="form-control"
                                                id="search" onKeyUp="myFunction()"
                                                placeholder="<?php echo showOtherLangText('Search Item'); ?>" value="">
                                        </div>

                                        <div class="itm-img">
                                            <img src="dist/img/checklist.svg" alt="filterbtn" class="flDwn-Icn"
                                                id="filterBtn"
                                                title="<?php echo showOtherLangText('Filter Column List') ?>">

                                            <a href="item_excel.php" target="_blank">
                                                <img src="dist/img/excel.svg" alt="excel" class="flDwn-Icn"
                                                    title="<?php echo showOtherLangText('Download Excel') ?>">
                                            </a>
                                        </div>


                                    </div>



                                </form>


                                <form action="listProducts.php" method="get">
                                    <div class="lstPrd-Div">

                                        <p><?php echo showOtherLangText('Total No. Of Rows'); ?>:
                                            <b><?php echo mysqli_num_rows($mainQry); ?></b></p>

                                        <table class="table table-striped" id="tableId">
                                            <thead>
                                                <th>#</th>

                                                <?php
						 $colsArr = 
						  [
						  	1 => ['lable' => 'Photo', 'width' => '5%', ],
							2 => ['lable' => 'Item', 'width' => '10%'],
							3 => ['lable' => 'Bar Code', 'width' => '10%'],
							4 => ['lable' => 'Unit.P/F/Unit.C', 'width' => '10%'],
							
							
							5 => ['lable' => 'Category', 'width' => '10%', 'style'=> 'font-size:11px;'],
							6 => ['lable' => 'Supplier', 'width' => '10%', 'style'=> 'font-size:11px;'],
							7 => ['lable' => 'Departments', 'width' => '10%', 'style'=> 'font-size:11px;'],
							8 => ['lable' => 'Storage', 'width' => '10%', 'style'=> 'font-size:11px;'],
							
							9 => ['lable' => 'Min Qty', 'width' => '5%'],
							
							10 => ['lable' => 'Max Qty', 'width' => '5%'],
							11 => ['lable' => 'Last Price', 'width' => '5%', 'style'=> 'color:#990000'],
							12 => ['lable' => 'Stock Price', 'width' => '5%', 'style'=> 'color:#990000'],
						  ];
						  
						  foreach($colsArr as $key=>$colArr)
						  {
						  		if($key == 5)
								{
									$col = $subCatOptions;
								}
								elseif($key == 6)
								{
									$col = $suppOptions;
								}
								elseif($key == 7)
								{
									$col = $deprtOptions;
								}
								elseif($key == 8)
								{
									$col = $storageOptions;
								}
								else
								{
									$col = $colArr['lable'];
								}
								
						  		$style = isset($colArr['style']) ? $colArr['style'] : '';
							  	echo ( isset($itemUserFilterFields) && !in_array($key, $itemUserFilterFields) ) ? '' : '<th style="'.$style.'">'.$col.'</th>';
						  }
						 ?>

                                                <th><?php echo showOtherLangText('Actions'); ?></th>
                                            </thead>
                                            <tbody>

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
								if( $row['imgName'] != '' && file_exists( dirname(__FILE__)."/uploads/". $accountImgPath .'/'.$row['imgName'] ) )
								 {	
									$img =  '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/'.$row['imgName'].'" width="60" height="60">';
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
									11 => getPrice($row['price']) .' $',
									12 => getPrice($row['stockPrice']) .' $',
								  ];
								  
								
						?>
                                                <tr>
                                                    <td><?php echo $x; ?></td>
                                                    <?php
							foreach($colsValArr as $key=>$calVal)
							{
							  
							  	$calVal = $key == 2 ? '<a href="javascript:void(0)">'.$calVal.'</a>' : $calVal;
								echo ( isset($itemUserFilterFields) && !in_array($key, $itemUserFilterFields) ) ? '' : '<td>'.$calVal.'</td>';
								
							 }
							?>
                                                    <td>
                                                        <a
                                                            href="editProduct.php?id=<?php echo $row['id'];?>"><?php echo showOtherLangText('Edit'); ?></a>
                                                        |
                                                        <a href="listProducts.php?delId=<?php echo $row['id'];?>"
                                                            onClick="return confirm('<?php echo showOtherLangText('Are you sure to delete this record?'); ?>')"><?php echo showOtherLangText('Delete'); ?></a>

                                                    </td>
                                                </tr>
                                                <?php 
							}
						?>

                                            </tbody>
                                        </table>
                                    </div>
                                </form>

                                <div>
                                    <button class="btn btn-primary" type="button" style="width: 149px;"
                                        onClick="window.location.href='addProduct.php?catId=<?php echo $_GET['catId'];?>&&deptId=<?php echo $_GET['deptId'];?>'"><?php echo showOtherLangText('Add Product'); ?></button>
                                    &nbsp;
                                    <button class="btn btn-primary" type="button" style="width: 149px;"
                                        onClick="window.location.href='setup.php'"><?php echo showOtherLangText('Back'); ?></button>
                                </div>


                            </div>

                        </div>




                    </div><!-- panel body -->


                </div>
            </div><!-- content -->

        </div>
    </div>

    <div id="myModal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">

            <div>

                <div class="mdlHead-Popup">
                    <span><strong><?php echo showOtherLangText('Check columns to show in list:'); ?></strong></span>
                    <span class="close">&times;</span>
                </div>

                <form method="post" action="" class="shortList-ChkAll">

                    <strong class="checkAllSectionBox">
                        <input type="checkbox" class="CheckAllOptions" id="CheckAllOptions">
                        <label>
                            <?php echo showOtherLangText('Check All') ?>
                        </label>
                    </strong>
                    <br>
                    <?php foreach($colsArr as $key=>$colArr){
							
								if( isset($itemUserFilterFields) )
								{
									$sel =  in_array($key, $itemUserFilterFields) ? ' checked="checked" ' : '';
								}
								
							?>
                    <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsItem[]"
                        <?php echo $sel;?> value="<?php echo $key;?>">&nbsp;<?php echo $colArr['lable'];?><br>
                    <?php } ?>
                    <p><button type="submit" name="btnSubmit" class="btn">Show</button>&nbsp;
                        <?php if( isset($itemUserFilterFields) ) {?>
                        <button type="button" name="btnSubmit" class="btn"
                            onClick="window.location.href='listProducts.php?clearshowFieldsItem=1'"><?php echo showOtherLangText('Clear Filter'); ?></button>
                        <?php } ?>

                    </p>
                </form>
            </div>

        </div>

    </div>

    <?php require_once('footer.php');?>

    <script>
    function myFunction() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("search");
        filter = input.value.toUpperCase();
        table = document.getElementById("tableId");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the  query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[2];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
    </script>

    <script>
    //check/uncheck filter button
    $(document).ready(function() {

        var totalCount = $('.optionCheck').length;

        var totalCheckedCount = $('.optionCheck:checked').length;

        if (totalCount == totalCheckedCount) {

            $('#CheckAllOptions').prop('checked', true);
        } else {
            $('#CheckAllOptions').prop('checked', false);
        }
    });

    $("#CheckAllOptions").on('click', function() {

        $('.optionCheck:checkbox').not(this).prop('checked', this.checked);
    });

    $(".optionCheck").on('click', function() {

        var totalCount = $('.optionCheck').length;

        var totalCheckedCount = $('.optionCheck:checked').length;

        if (totalCount == totalCheckedCount) {

            $('#CheckAllOptions').prop('checked', true);
        } else {
            $('#CheckAllOptions').prop('checked', false);
        }
    });
    </script>

    <script type="text/javascript">
    var fileupload = document.getElementById("uploadFile");
    var button = document.getElementById("btnFileUpload");
    button.onclick = function() {
        fileupload.click();
    };
    fileupload.onchange = function() {
        document.getElementById('upload_form').submit();
    };



    function uploadZip() {
        var fileupload1 = document.getElementById("uploadZipFile");
        var button1 = document.getElementById("btnZipFileUpload");
        button1.onclick = function() {
            fileupload1.click();
        };
        fileupload1.onchange = function() {
            document.getElementById('upload_form').submit();
        };
    }
    </script>

    <script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("filterBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }


    function download(frm, type) {
        if (type == 'pdf') {
            var page = 'stock_pdf.php';
        } else {
            var page = 'stock_excel.php';
        }
        document.getElementById('downloadType').value = type;
        document.frm.target = "_blank";
        document.frm.action = page;

        document.frm.submit();


        document.getElementById('downloadType').value = '';
    }
    </script>
</body>

</html>