<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


$rightSideLanguage = ($getLangType == 1) ? 1 : 0;

//check page permission
$checkPermission = permission_denied_for_section_pages($_SESSION['designation_id'],$_SESSION['accountId']);

if (!in_array('5',$checkPermission))
{
    echo "<script>window.location='index.php'</script>";
}

if (isset($_SESSION['checkDublicateClick']) && $_SESSION['checkDublicateClick'] == 1) {
    unset($_SESSION['checkDublicateClick']);
}

//get access of Xcel and pdf file
$showExcelPdfFile = access_stock_xcl_pdf_file($_SESSION['designation_id'],$_SESSION['accountId']);


if (isset($_SESSION['processId'])) {
    unset($_SESSION['processId']);
}

if( isset($_REQUEST['showFieldsStock']) )
{
    $updateQry = " UPDATE tbl_user SET stockUserFilterFields = '".implode(',', $_REQUEST['showFieldsStock'])."' WHERE id = '".$_SESSION['id']."' AND account_id = '".$_SESSION['accountId']."'  ";
    mysqli_query($con, $updateQry);
}
elseif( isset($_REQUEST['clearshowFieldsStock'])  )
{
    $updateQry = " UPDATE tbl_user SET stockUserFilterFields = '' WHERE id = '".$_SESSION['id']."' AND account_id = '".$_SESSION['accountId']."'  ";
    mysqli_query($con, $updateQry);
}

$sql = "SELECT * FROM tbl_user  WHERE id = '".$_SESSION['id']."' AND account_id = '".$_SESSION['accountId']."'  ";
$result = mysqli_query($con, $sql);
$userDetails = mysqli_fetch_array($result);
$stockUserFilterFields = $userDetails['stockUserFilterFields'] ?    explode(',',$userDetails['stockUserFilterFields']) : null;


$cond = '';
$stockCond = '';
if( isset($_GET['filterByStorage']) && ($_GET['filterByStorage']) != '' )
{
    $cond = " AND tp.storageDeptId = '".$_GET['filterByStorage']."' ";

    $_SESSION['filterByStorage'] = $_GET['filterByStorage'];
}
if( isset($_GET['subCatId']) && $_GET['subCatId'])
{
$cond .= " AND c.id = '".$_GET['subCatId']."'  ";
$stockCond .= " AND c.id = '".$_GET['subCatId']."'  ";
}
if( isset($_GET['suppId']) && $_GET['suppId'])
{
    $sql = " SELECT productId FROM tbl_productsuppliers WHERE supplierId = '".$_GET['suppId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $sqlRes = mysqli_query($con, $sql);
    
    $tpIdArr=[];
    while($resRow = mysqli_fetch_array($sqlRes))
    {    
        $tpIdArr[] = $resRow['productId'];   
    }
    $tpId = implode(',', $tpIdArr);
    

    $cond .= " AND tp.ID IN(".$tpId.") "; 


    $sql = " SELECT productId FROM tbl_productsuppliers WHERE supplierId = '".$_GET['suppId']."'  AND account_id = '".$_SESSION['accountId']."' ";
    $sqlRes = mysqli_query($con, $sql);
    
    $tpIdArr=[];
    while($resRow = mysqli_fetch_array($sqlRes))
    {    
        $tpIdArr[] = $resRow['productId'];   
    }
    $tpId = implode(',', $tpIdArr);

    $stockCond .= " AND tp.ID IN(".$tpId.") ";

//$cond .= " AND tp.ID IN(SELECT productId FROM tbl_productsuppliers WHERE supplierId = '".$_GET['suppId']."' AND account_id = '".$_SESSION['accountId']."' ) ";

//$stockCond .= " AND tp.ID IN(SELECT productId FROM tbl_productsuppliers WHERE supplierId = '".$_GET['suppId']."'  AND account_id = '".$_SESSION['accountId']."'  ) ";
}
if (!empty(get_store_permission($_SESSION['designation_id'], $_SESSION['accountId']))) {
    
    $cond .= " AND dssp.designation_id = '".$_SESSION['designation_id']."' AND dssp.type = 'stock' AND dssp.designation_section_permission_id = '5'  ";
}

if($cond != '')
{
  $_SESSION['getVals'] = $_GET;
}
else
{
  unset($_SESSION['getVals']);
}

//==============================================//

$sql = "SELECT
tp.*,
s.qty stockQty,
s.lastPrice stockLastPrice,
s.stockPrice sPrice,
s.stockValue,
pc.name parentCatName,
IFNULL(c.name, 'z') childCatName,
IF(uc.name!='', uc.name,tp.unitC) countingUnit,
IF(up.name!='', up.name,tp.unitP) purchaseUnit,
GROUP_CONCAT(DISTINCT d.name) departs ,
GROUP_CONCAT(DISTINCT sp.name) suppls
FROM
tbl_stocks s

INNER JOIN tbl_products tp ON
(s.pId = tp.id) AND tp.status = 1 AND s.account_id = tp.account_id
LEFT JOIN tbl_units up ON
    (up.id = tp.unitP) AND up.account_id = tp.account_id
LEFT JOIN tbl_units uc ON
    (uc.id = tp.unitC) AND uc.account_id = tp.account_id
LEFT JOIN tbl_category pc ON
(pc.id = tp.parentCatId) AND pc.account_id = tp.account_id
LEFT JOIN tbl_category c ON
(c.id = tp.catId) AND c.account_id = tp.account_id

LEFT JOIN tbl_productdepartments dc ON(dc.productId = tp.id)  AND dc.account_id = tp.account_id
LEFT JOIN tbl_department d ON(dc.deptId = d.id) AND dc.account_id = d.account_id

LEFT JOIN tbl_productsuppliers ps ON(ps.productId = tp.id) AND ps.account_id = tp.account_id
LEFT JOIN tbl_suppliers sp ON(ps.supplierId = sp.id) AND ps.account_id = sp.account_id

LEFT JOIN tbl_stores st ON(st.id = tp.storageDeptId) AND st.account_id = tp.account_id

LEFT JOIN tbl_designation_sub_section_permission dssp
ON(st.id=dssp.type_id) AND st.account_id=dssp.account_Id

WHERE tp.account_id ='".$_SESSION['accountId']."' ". $cond . " GROUP BY tp.id ORDER by s.id DESC ";

$stockMainQry = mysqli_query($con, $sql);
//End stock lists


//get store permission
$storeCond = '';
if (!empty(get_store_permission($_SESSION['designation_id'], $_SESSION['accountId']))) {
    
    $storeCond .= " AND dssp.designation_id = '".$_SESSION['designation_id']."' AND dssp.type = 'stock' AND dssp.designation_section_permission_id = '5'  ";
}

//Store details queries goes here
$sqlSet = " SELECT DISTINCT(s.id),s.* FROM tbl_stores s

LEFT JOIN tbl_designation_sub_section_permission dssp
ON(s.id=dssp.type_id) AND s.account_id=dssp.account_Id 

WHERE s.account_id = '".$_SESSION['accountId']."' ".$storeCond."  ";
$storeQry = mysqli_query($con, $sqlSet);


$sql = "SELECT SUM(stockValue) totalstockValue  FROM tbl_stocks s 

INNER JOIN tbl_products tp ON(s.pId = tp.id) AND tp.status=1 AND s.account_id = tp.account_id

LEFT JOIN tbl_category c ON (c.id = tp.catId) AND c.account_id = tp.account_id

LEFT JOIN tbl_stores st ON(st.id = tp.storageDeptId) AND st.account_id = tp.account_id

LEFT JOIN tbl_designation_sub_section_permission dssp
ON(st.id=dssp.type_id) AND st.account_id=dssp.account_Id

WHERE 1=1 AND s.account_id = '".$_SESSION['accountId']."' ".$storeCond." ".$stockCond;
$resQry = mysqli_query($con, $sql);
$storeResRow = mysqli_fetch_array($resQry);
//End



//---------------------------------------------

$sqlSet = " SELECT c.* FROM  tbl_category c

INNER JOIN tbl_products tp ON
(c.id = tp.catId) AND tp.status = 1 AND c.account_id = tp.account_id

WHERE c.parentId > 0 AND c.account_id = '".$_SESSION['accountId']."' GROUP BY tp.catId ORDER BY c.name ";
$resultSet = mysqli_query($con, $sqlSet);
                            

$subCatOptions = '<ul class="dropdown-menu subcat_opt">';
$subCatOptions .= '<li data-id="" data-value=""><a class="dropdown-item" href="javascript:void(0)">'.showOtherLangText('Sub Catagory').'</a></li>';
while($catRow = mysqli_fetch_array($resultSet) )
{
$sel = isset($_GET['subCatId']) && $_GET['subCatId'] == $catRow['id']  ? 'selected' : '';

$subCatOptions .= '<li data-id="'.$catRow['id'].'" data-value="'.$catRow['name'].'"><a class="dropdown-item '.$sel.'" href="javascript:void(0)">'.$catRow['name'].'</a></li>';
}
$subCatOptions .= '</ul>';


$sqlSet = " SELECT * FROM  tbl_suppliers 
WHERE account_id = '".$_SESSION['accountId']."' ORDER BY name ";
$resultSet = mysqli_query($con, $sqlSet);
                            
$suppOptions = '<ul class="dropdown-menu supplier_opt">';
$suppOptions .= '<li data-id="" data-value=""><a class="dropdown-item" href="javascript:void(0)">'.showOtherLangText('Supplier').'</a></li>';
while($departRow = mysqli_fetch_array($resultSet) )
{
$sel = isset($_GET['suppId']) && $_GET['suppId'] == $departRow['id']  ? 'selected' : '';
$suppOptions .= '<li data-id="'.$departRow['id'].'" data-value="'.$departRow['name'].'"><a class="dropdown-item '.$sel.'" href="javascript:void(0)">'.$departRow['name'].'</a>
                                                </li>';
}
$suppOptions .= '</ul>';
 $convertrawitems = false;
if( isset($_POST['rawItem']) && $_POST['rawItem'] > 0 )
{
    $convertrawitems = true;
    $_SESSION['convertItemsPost'] = $_POST;
    
    $sql = "SELECT p.*, s.qty stockQty  FROM tbl_stocks s 
                                INNER JOIN tbl_products p ON(s.pId = p.id) AND s.account_id = p.account_id WHERE p.id = '".$_POST['rawItem']."'  AND p.account_id = '".$_SESSION['accountId']."'   ";
    $qryRawItem = mysqli_query($con, $sql);
    $rawItemRow = mysqli_fetch_array($qryRawItem);
    
    $sql = "SELECT p.*, s.qty stockQty  FROM tbl_stocks s 
                                RIGHT JOIN tbl_products p ON(s.pId = p.id) AND s.account_id = p.account_id WHERE p.id = '".$_POST['convertItem']."'  AND p.account_id = '".$_SESSION['accountId']."' ";
    $qryConvertItem = mysqli_query($con, $sql);
    $convertItemRow = mysqli_fetch_array($qryConvertItem);  
}

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Stock View - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">

</head>

<body>
<?php
$storeId = isset($_GET['filterByStorage']) && ($_GET['filterByStorage']) != '' ? 1 : '';
?>
<input type="hidden" name="storeName" id="storeName" value="<?php echo $storeId;?>"/>

    <div class="container-fluid newOrder">
        <div class="row">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                <?php require_once('nav.php');?>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('Stock View'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Stock View'); ?></h1>
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

                <section class="ordDetail stockView">

                    <form name="frm" id="frm" method="get" action="">
                        <input type="hidden" name="subCatId" id="subCatId" value="<?php echo $_GET['subCatId']; ?>" />
                        <input type="hidden" name="suppId" id="suppId" value="<?php echo $_GET['suppId']; ?>" />
                        <input type="hidden" name="filterByStorage" id="filterByStorage" value="<?php echo $_GET['filterByStorage']; ?>" />
                         
                     </form>
                    <div class="stkView">
                        <div class="container">
                            <?php if(isset($_GET['stockTake']) || isset($_GET['convertRawItem'])) {?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p><?php 

echo isset($_GET['stockTake']) ? ' '.showOtherLangText('Item overwrite successfully').' ' : '';
echo isset($_GET['convertRawItem']) ? ' '.showOtherLangText('Raw Item converted successfully').' ' : '';
                              ?>
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                            <div class="row">
                                <div class="storeCol">
                                    <div class="store text-center d-flex">

                                        <a href="javascript:void(0)"  class="dskAll-str">
                                            <div class="allStr">
                                                <h2 class="h2"><?php echo showOtherLangText('All Stores') ?></h2>
                                            </div>
                                        </a>
                                         <?php if (mysqli_num_rows($storeQry) > 1) 
                            { 
                                ?>
                                        <a href="javascript:void(0)" class="allStore">
                                            <div class="othStr">
                                                <div class="storeCont">
                                                    <h2 class="h2"><?php echo showOtherLangText('All Stores') ?></h2>
                                                    <hr>
                                                    <p class="body2"><?php showPrice($storeResRow['totalstockValue'],$getDefCurDet['curCode']);?></p>
                                                </div>
                                            </div>
                                        </a>
                                 <?php
                            }  

                            while($storageDeptRow = mysqli_fetch_array($storeQry))
                            {
                                $totalstockValue=getStockTotalOfStore($storageDeptRow['id'], $stockCond);               
                            ?>
                                        <a href="stockView.php?filterByStorage=<?php echo $storageDeptRow['id'];?>" class="otrStock <?php echo $_GET['filterByStorage'] == $storageDeptRow['id'] ? 'stockActive-br' : '';?>">
                                            <div class="othStr">
                                                <div class="storeCont">
                                                    <h2 class="h2"><?php echo getMobileStockTakeCount($storageDeptRow['id']); ?><br><?php echo $storageDeptRow['name'];?></h2>
                                                    <hr>
                                                    <p class="body2"><?php showPrice($totalstockValue,$getDefCurDet['curCode']);?></p>
                                                </div>
                                            </div>
                                        </a>
                            <?php 
                            }
                             ?>

                                        

                                    </div>
                                </div>
                                <!-- <div class="strRemcol"></div> -->
                                <div class="strfetCol text-center">
                                     <form action="stockTake.php" id="upload_form" name="upload_form" method="post"
                                    enctype="multipart/form-data"> <div class="row stkRow">
                                       

                                    <input type="hidden" name="storeId"
                                        value="<?php echo $_GET['filterByStorage'];?>" />
                                        <div class="col-md-3 stockFeat brdLft">
                                            
                                            <?php  access_raw_item_convert($_SESSION['designation_id'],$_SESSION['accountId']); ?>
                                        </div>
                                        <div class="col-md-3 stockFeat brdLft">
                                            <?php  access_view_stockTake($_SESSION['designation_id'],$_SESSION['accountId'],$_GET['filterByStorage']);  ?>
                                        </div>
                                         <div class="col-md-3 stockFeat dropStk">
                                            
                                            
    <?php access_import_stockTake($_SESSION['designation_id'],$_SESSION['accountId'],$_GET['filterByStorage'], $rightSideLanguage);  ?>
                                           
                                        </div>
                                    </div></form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="stkSrcicn">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 mbCol-stk">
                                    <!-- Search Box Start -->
                                    <div class="input-group srchBx">
                                        <input type="search" class="form-control" onKeyUp="myFunction()" placeholder="Search" name="search" id="search"
                                            aria-label="Search">
                                        <div class="input-group-append">
                                            <button class="btn" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Search Box End -->
                                    <!-- Feature Btn Start -->
                                    <div class="fetBtn">
                                        <a href="javascript:void(0)">
                                            <img src="Assets/icons/dashboard.svg" alt="dashboard">
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Feature Btn End -->
                                <div class="col-md-6 expStrdt d-flex justify-content-end align-items-end">
                                    <div class="d-flex justify-content-end align-items-center">

                                    <div class="chkStore">
                                    <a href="javascript:void(0)"  style="height: 20px;" class="cstBtn-Sale hideBtn-Info ">
                                            <img src="Assets/icons/info.svg" style="height: 100%;" alt="Information" class="cstBtn-Img">
                                        </a>

                                    </div>
                                        <div class="chkStore">
                                            <a href="javascript:void(0)" >
                                                <img src="Assets/icons/chkColumn.svg"    class="flDwn-Icn" id="filterBtn" title="Filter column list" alt="Check Column">
                                            </a>
                                        </div>
                                        <div class="chkStore">
                                            <a href="stock_excel.php" target="_blank">
                                                <img src="Assets/icons/stock-xcl.svg" alt="Stock Xcl">
                                            </a>
                                        </div>
                                        <div class="chkStore">
                                            <!-- <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#stock_pdf"> -->
                                                <a href="javascript:void(0);" onclick="showLightboxStockPdf();">
                                                <img src="Assets/icons/stock-pdf.svg" alt="Stock PDF">
                                            </a>
                                        </div>

                                    </div>
                                </div>
                                <div class="hdfetCol text-center">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container mb-srcBox">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Search Box Start -->
                            <div class="mbClone-src">

                            </div>
                            <!-- Search Box End -->

                            <!-- Feature Btn Start -->
                            <div class="filtBtn">
                                <a href="javascript:void(0)">
                                    <img src="Assets/icons/filter.svg" alt="Filter">
                                </a>
                            </div>
                            <!-- Feature Btn End -->
                        </div>
                        <!-- Filter Part Mobile Start -->
                        <div class="filter-mb">

                        </div>
                        <!-- Filter Part Mobile End -->
                    </div>

                    <div class="container stkTblhead position-relative">
                        <!-- Item Table Head Start -->
                        <div class="d-flex align-items-center itmTable">
                            <div class="mbShw d-flex align-items-center">
                                <div class="tb-bdy stkImgcol"></div>
                                <div class="stkNamcol d-flex align-items-center" style="width: 20%;">
                                    <div class="tb-head stkItmclm">
                                        <div class="d-flex align-items-center w-20">
                                            <p><?php echo showOtherLangText('Item'); ?></p>
                                            <span class="dblArrow">
                                                <a onclick="sortTableByColumn('.newStockTask', '.stkItmclm','asc');" href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a onclick="sortTableByColumn('.newStockTask', '.stkItmclm','desc');" href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <div class="mbShw d-flex align-items-center tbl-header">
                                <div class="stkNamcol d-flex align-items-center">
                                    <div class="tb-head flipClm-Otlt stkQtyclm">
                                        <div class="d-flex align-items-center">
                                            <p><?php echo showOtherLangText('Quantity');?></p>
                                            <span class="dblArrow">
                                                <a onclick="sortTableByColumn('.newStockTask', '.stkQtybdy','asc');" href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a onclick="sortTableByColumn('.newStockTask', '.stkQtybdy','desc');" href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="tb-head flipClm-Otlt stkQtyclm">
                                        <div class="d-flex align-items-center">
                                            <p><?php echo showOtherLangText('Available <br> Qty');?></p>
                                            <span class="dblArrow">
                                                <a onclick="sortTableByColumn('.newStockTask', '.stkQtybdy','asc');" href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a onclick="sortTableByColumn('.newStockTask', '.stkQtybdy','desc');" href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="tb-head flipClm-Otlt stkQtyclm">
                                        <div class="d-flex align-items-center">
                                            <p><?php echo showOtherLangText('Requested <br> Qty');?></p>
                                            <span class="dblArrow">
                                                <a onclick="sortTableByColumn('.newStockTask', '.stkQtybdy','asc');" href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a onclick="sortTableByColumn('.newStockTask', '.stkQtybdy','desc');" href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="stkPrcol d-flex align-items-center">
                                    <div class="tb-head lstPrcol">
                                        <div class="d-flex align-items-center">
                                            <p><?php echo showOtherLangText('Last <br>Price'); ?></p>
                                            <span class="dblArrow">
                                                <a onclick="sortTableByColumn('.newStockTask', '.mb-Last','asc');" href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a onclick="sortTableByColumn('.newStockTask', '.mb-Last','desc');" href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="tb-head lstPrcol">
                                        <div class="d-flex align-items-center">
                                            <p><?php echo showOtherLangText('Stock <br>
                                                Price'); ?></p>
                                            <span class="dblArrow">
                                                <a onclick="sortTableByColumn('.newStockTask', '.mb-Stock','asc');" href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a onclick="sortTableByColumn('.newStockTask', '.mb-Stock','desc');" href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="tb-head lstPrcol">
                                        <div class="d-flex align-items-center">
                                            <p><?php echo showOtherLangText('Stock <br>
                                                Value'); ?></p>
                                            <span class="dblArrow">
                                                <a onclick="sortTableByColumn('.newStockTask', '.mb-Value','asc');" href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a onclick="sortTableByColumn('.newStockTask', '.mb-Value','desc');" href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="tb-head supStkclm" style="width: auto;">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span id="subcatText"><?php echo showOtherLangText('Sub Catagory'); ?></span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            
                                           <?php echo $subCatOptions; ?> 
                                        </div>
                                    </div>
                                </div>
                                <div class="tb-head supStkclm" style="width: auto;">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span id="SupplierText"><?php echo showOtherLangText('Supplier'); ?></span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            <?php echo $suppOptions; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <!-- Item Table Head End -->
                    </div>
                    <!-- Item Table Body Start -->
                    <div id="boxscroll">
                        <div class="container cntTable">

                            <?php



                    //get confirmed requsitions total qty of each productd
                    $productsConfirmedQtyArr = getConfirmTotalQtyReq($_SESSION['accountId']);
                    //end   get confirmed requsitions total qty of each productd
                  
                    $x= 0; 
                    while($row = mysqli_fetch_array($stockMainQry))
                    {
                        $x++;

                        $totalProQty = isset($productsConfirmedQtyArr[$row['id']]) ? $productsConfirmedQtyArr[$row['id']] : 0;
                        
                        if ($totalProQty > 0) {

                            $totalTempProQty = $totalProQty;

                        }else{

                            $totalTempProQty = 0;
                        }  $deptNames = $row['departs'];
                        $supNames = $row['suppls'];
                        
                        
                        $img = '';
                        if( $row['imgName'] != '' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$row['imgName'] )  )
                         {  
                            $img = '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$row['imgName'].'" class="imgItm">';
                         }

                         
                     
                         $catNames = ($row['childCatName'] == 'z' ? '' :  $row['childCatName']); ?>
                             <div class="newStockTask"><div class="d-flex align-items-center border-bottom itmBody">
                                <div class="mbShw d-flex align-items-center">
                                    <div class="tb-bdy stkImgcol" style="width: 12%;" >
                                        <?php echo $img; ?>
                                    </div>
                                    <div class="stkNamcol d-flex align-items-center" style="width: 28%;">
                                        <div class="tb-bdy stkItmclm">
                                            <a href="itemHistoryView.php?id=<?php echo $row['id']; ?>" class="itm-Profile"><?php echo $row['itemName']; ?></a>
                                        </div>
                                    </div>
                                  
                                    <div class="stkNamcol d-flex align-items-center" style="width: 45%;">
                                        <div class="tb-bdy stkQtyclm stkQtybdy">
                                            <p><?php echo $row['stockQty']; ?></p>
                                        </div>
                                        <div class="tb-bdy stkQtyclm stkQtybdy">
                                            <p><?php echo $row['stockQty']; ?></p>
                                        </div>
                                        <div class="tb-bdy stkQtyclm stkQtybdy">
                                            <p><?php echo $row['stockQty']; ?></p>
                                        </div>
                                    </div>
                                    <div class="stkPrcol d-flex align-items-center">
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Last">
                                            <p><span class="mbLst-value">Last</span><?php echo getPrice($row['stockLastPrice']) .' '.$getDefCurDet['curCode']; ?></p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Stock">
                                            <p><span class="mbLst-value">Stock</span><?php echo getPrice($row['sPrice']) .' '.$getDefCurDet['curCode']; ?></p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Value">
                                            <p><span class="mbLst-value">Value</span><?php echo getPrice($row['stockValue']) .' '.$getDefCurDet['curCode']; ?></p>
                                        </div>
                                    </div>
                                
                                <div class="mbHde align-items-center suppData-stk" style="width: 40%;">
                                    <div class="tb-bdy supStkclm">
                                        <p><?php echo $catNames; ?></p>
                                    </div>
                                    <div class="tb-bdy supStkclm">
                                        <p><?php echo $supNames; ?></p>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                            <?php } ?>
                      </div>
                    <!-- Item Table Body End -->
                </section>
                
            </div>
        </div>
    </div>



    <!-- ===== Stock view pdf popup new in div format======= -->
    <div class="modal" tabindex="-1" id="stock_pdf" aria-labelledby="stock_pdfModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md site-modal">
            <div id="stock_details" class="modal-content p-2">
                
                
            </div>
        </div>
    </div>

   
   <div  class="modal addUser-Form row container glbFrm-Cont" tabindex="-1" id="qty_in_stock" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">

            <div id="qty_in_stock_ajax_content" class="modal-content">

                
            </div>
        </div>
    </div>

    <div id="myModal" class="modal" style=" align-items:center">
        <div style="width:750px; padding:0;" class="container mnPdf_Dv">             
            <!-- Modal content -->
            <div class="modal-content">

                <div>

                    <div class="mdlHead-Popup" style="display: flex; align-items:center; justify-content:space-between; ">
                        <span><strong>Check columns to show in list:</strong></span>
                        <span class="close" style="font-size: 2rem; cursor:pointer ">×</span>
                    </div>

                    <form method="post" action="" class="shortList-ChkAll">

                        <strong class="checkAllSectionBox" style="max-width: 750px;">
                            <input type="checkbox" class="CheckAllOptions" id="CheckAllOptions">
                            <label>
                                Check all
                            </label>
                        </strong>

                        <br>

                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="1">&nbsp;Photo<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="2">&nbsp;Item<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="3">&nbsp;Bar code<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="4">&nbsp;Unit.p<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="5">&nbsp;Factor<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="6">&nbsp;Unit.c<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="7">&nbsp;Category<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="8">&nbsp;Supplier<br>
                                                <hr>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="9">&nbsp;Departments<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="10">&nbsp;Min qty<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="11">&nbsp;Max qty<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="12">&nbsp;Qty<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="13">&nbsp;Tmp qty<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="14">&nbsp;Availabe qty<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="15">&nbsp;Last price<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="16">&nbsp;Stock value<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="17">&nbsp;Stock price<br>
                                                <p style="display: flex; gap:.5rem;">
                                                 <button type="submit" name="btnSubmit" style="background-color: #7a89fe; border-radius: 10px; border: none; color:white; " class="btn gray-btn">Show</button>&nbsp;
                                                 <button type="button" name="btnSubmit" style="background-color: #7a89fe; border-radius: 10px; border: none; color:white; " class="btn gray-btn" onclick="window.location.href='stock.php?clearshowFieldsStock=1'">Clear filter</button>
                                                </p>
                    </form>
                </div>

            </div>
        </div>
    </div>
    

    
  
    <div class="modal addUser-Form row container glbFrm-Cont" id="convert_raw_items" tabindex="-1" aria-labelledby="issueout2label" aria-hidden="true"> <form method="post" id="frmconvert" name="frmconvert" action="convertRawItemsConfirmSubmit.php">
                <div class="modal-dialog modal-dialog-centered modal-550">
                    <div class="modal-content">

                    <div class="modal-body  fs-15">
                        <div class="pb-3">
                            <h2>Convert raw item</h2>
                          
                        </div>

                       <table class="issueout2-table w-100 fs-13">
                            <tr class="semibold">
                                <th>Photo</th>
                                <th>Item</th>
                                <th>Converted Qyt</th>
                                <th>Unit</th>
                                <th>Qyt Before</th>
                                <th>Qyt After</th>
                                <th>Price</th>
                                <th>Converted Qyt Value</th>
                            </tr>
                            <tr class="semibold">
                                <td><?php 
                             
                             if( $rawItemRow['imgName'] != '' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$rawItemRow['imgName'] ) )
                             {  
                                echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$rawItemRow['imgName'].'" width="60" height="60">';
                               
                             }
                            ?></td>
                                <td><?php echo $rawItemRow['itemName']!==''?$rawItemRow['itemName']:'';?></td>
                            <td><?php echo $_POST['qtyToConvert'];?></td>
                            <td><?php echo $rawItemRow['unitC'];?></td>
                            
                            <td><?php echo $rawItemRow['stockQty'];?></td>
                            
                            <td><?php echo ($rawItemRow['stockQty']-$_POST['qtyToConvert']);?></td>
                            
                            <td><?php 
                            if($rawItemRow['price']!=='') { echo showPrice($rawItemRow['price'],$getDefCurDet['curCode']); } ?></td>
                            <td><?php showPrice($_POST['qtyToConvert']*$rawItemRow['price'],$getDefCurDet['curCode'])?></td>
                            </tr>
                            <tr class="semibold">
                                <td><?php 
                             if( $convertItemRow['imgName'] != '' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$convertItemRow['imgName'] ) )
                             {  
                                echo '<img width="60" height="60" src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$convertItemRow['imgName'].'" >';
                                
                             }
                            ?></td>
                                <td><?php echo $convertItemRow['itemName'];?></td>
                            <td><?php echo $_POST['convertedQty'];?></td>
                            <td><?php echo $convertItemRow['unitC'];?></td>
                            
                            <td><?php echo $convertItemRow['stockQty'] > 0 ? $convertItemRow['stockQty'] : 0;?></td>
                            
                            <td><?php echo $convertItemRow['stockQty']+$_POST['convertedQty'];?></td>
                            
                            <td><?php echo showPrice($_POST['unitPrice'],$getDefCurDet['curCode']);?></td>
                            <td><?php echo showPrice($_POST['qtyToConvert']*$rawItemRow['price'],$getDefCurDet['curCode']);?></td>
                            </tr>
                             
                            
                       </table>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onClick="approveConvert();" >Approve</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                    </div>
                </div>
                </form>
                </div>
    
    
    <!-- ===== Stock pdf popup new in div format======= -->

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>

    <script>

// Revenue Center (Sale & Cost) Flip Column Part Start
$(document).ready(function () {
  $(".cstBtn-Sale").on("click", function () {
    $(this).toggleClass("actvSale-Cst");

    if ($(window).width() < 992 && $(".hideBtn-Prc").hasClass("actvSale-Cst")) {
      $(".itmInfo-Otlt").css("width", "66%");
      $(".flipInfo-Clm .itmInfo-Otlt").css("width", "100%");
      $(".mblStock-Sale").css("text-align", "center");
      $(".flipInfo-Clm .mblStock-Sale").css("text-align", "end");
    } else {
      $(".itmInfo-Otlt").css("width", "100%");
      $(".mblStock-Sale").css("text-align", "end");
    }

    if (
      $(window).width() < 992 &&
      $(".hideBtn-Info").hasClass("actvSale-Cst")
    ) {
      $(".outletTask").css("display", "none");
    } else {
      $(".outletTask").css("display", "flex");
    }

    if ($(".hideBtn-Prc").hasClass("actvSale-Cst")) {
      $(".currItm-Info").slideDown();
      $(".mblAvr-Usg").slideDown();
      $(".currItm-Info").css("transition", "height 0.11s ease-in-out");
      $(".currItm-Info").css("display", "flex");
    } else {
      $(".currItm-Info").slideUp();
      $(".mblAvr-Usg").slideUp();
    }

    if ($(".hideBtn-Info").hasClass("actvSale-Cst")) {
      $(".flipClm-Otlt").fadeOut();
      $(".infoBtn-Hide").fadeOut();
      $(".flipClm-Hide").delay(500).fadeIn();
      $(".flipInfo-Clm").delay(500).fadeIn();
    } else {
      $(".flipClm-Otlt").delay(500).fadeIn();
      $(".infoBtn-Hide").delay(500).fadeIn();
      $(".flipClm-Hide").fadeOut();
      $(".flipInfo-Clm").fadeOut();
    }
  });

  if ($(window).width() < 992) {
    $(".outletNote").prop("disabled", true);
  } else {
    $(".outletNote").prop("disabled", false);
  }

  $(".slCst-Lnk").click(function () {
    $(this).addClass("myClik");
    if ($(".outletNote").is(":disabled")) {
      $(this).parent().siblings().find(".outletNote").prop("disabled", true);
      $(this).parent().siblings().find(".mbHide-Note").css("display", "none");
    } else {
      $(this).parent().siblings().find(".outletNote").prop("disabled", false);
      $(this).parent().siblings().find(".mbHide-Note").css("display", "block");
    }
  });
});

// Revenue Center (Sale & Cost) Flip Column Part End

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

    <script>   
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("filterBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
        modal.style.display = "flex";
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

    <script>
    $(function() {

       <?php if(isset($_GET['allstore'])) { ?> 
        $(".allStore").css("display", "flex");
        $(".storeCol").css("background", "#7A89FE");
        $(".dskAll-str").hide();

       <?php } ?>
       <?php if($convertrawitems==true){ ?>
        $('#convert_raw_items').modal('show');
    <?php } ?>
      $(".subcat_opt").on("click", "a", function(e){
        var $this = $(this).parent();
         $("#subcatText").text($this.data("value"));
        $("#subCatId").val($this.data("id"));
        $('#frm').submit();
      });

      $(".supplier_opt").on("click", "a", function(e){
        var $this = $(this).parent();
         $("#SupplierText").text($this.data("value"));
        $("#suppId").val($this.data("id"));
        $('#frm').submit();
      });

      var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('subCatId')) {
        var subCatId = urlParams.get('subCatId');
            if(subCatId!=='')
            {
                $("#subcatText").text($(".subcat_opt .selected").text());
            }
        }
        if (urlParams.has('suppId')) {
        var suppId = urlParams.get('suppId');
            if(suppId!=='')
            {
                $("#SupplierText").text($(".supplier_opt .selected").text());
            }
        }

      });
    function myFunction() {
        // Declare variables
        var input = document.getElementById("search");
       var filter = input.value.toLowerCase();
      var nodes = document.querySelectorAll('.newStockTask');
      
  for (i = 0; i < nodes.length; i++) {
    if (nodes[i].innerText.toLowerCase().includes(filter)) {
      nodes[i].style.setProperty("display", "block", "important");
    } else {
      nodes[i].style.setProperty("display", "none", "important");
    }
  }

  
    }
      $("#search").on("search", function(evt){
    if($(this).val().length == 0){
        resetData();
    }
    });

    function resetData() {

        $('#search').val('');
        myFunction();
   }

  function showLightboxStockPdf()
    {


        var storeVal = $("#storeName").val();
        if(storeVal == 0)
        {
            alert('Please select any store.');
            return false;
        }


        $.ajax({
            method: "POST",
            url: "stock_pdf_ajax.php"

            
        })
        .done(function(htmlRes) {
            $('#stock_details').html(htmlRes);
           $('#stock_pdf').modal('show');
        });
    }

  

function sortTableByColumn(table,field,order) {
       sortElementsByText(table,field,order);
    }

     function sortElementsByText(container, textElement, order) {
        var elements = $(container).get();

        elements.sort(function(a, b) {
            var textA = $(a).find(textElement).text().trim();
            var textB = $(b).find(textElement).text().trim();
            // Check if textA and textB are numbers
            var isNumA = !isNaN(parseFloat(textA)) && isFinite(textA);
            var isNumB = !isNaN(parseFloat(textB)) && isFinite(textB);

            if (isNumA && isNumB) {
                // If both are numbers, compare them as numbers
                return order === 'asc' ? parseFloat(textA) - parseFloat(textB) : parseFloat(textB) - parseFloat(textA);
            } else {
                // Otherwise, compare them as strings
                return order === 'asc' ? textA.localeCompare(textB) : textB.localeCompare(textA);
            }
        });

        $.each(elements, function(index, element) {
            $('.cntTable').append(element);
        });
    }

    function showHideByClass(targetId) {

    if ($('.' + targetId).is(":visible")) {
        $('.' + targetId).css('display', 'none');
        
    } else {
        if(targetId=='item2' || targetId=='item3' || targetId=='item4' || targetId=='item5'|| targetId=='item6'|| targetId=='item7'|| targetId=='item8'|| targetId=='item9'|| targetId=='item10'|| targetId=='item11'|| targetId=='item12')
        {
        $('.itemSectionPartMain').css('display', 'block');
        }
        if(targetId=='store-name' || targetId=='store-totalPrice' || targetId=='store-stockTakeCount')
        {    
        $('.summarySectionPartMain').css('display', 'block');
        }
        $('.' + targetId).css('display', 'block');
    }
    
    if(targetId == 'item3' || targetId == 'item4' )
    {
        
        if (  !$('.item3').is(":visible") || !$('.item4').is(":visible") ) {
        
            $('.slashItem').css('display', 'none');

        } else if ( $('.item3').is(":visible") && $('.item4').is(":visible") ) {
            $('.slashItem').css('display', 'block');
        }
    }



   }

   $('body').on('click', '.headChk-All', function() {


    if( $(".headChk-All:checked").length == 1  ) 
    {
        $("#header").prop('checked', true);
        $(".header-address").prop('checked', true);
        $(".header-logo").prop('checked', true);

        $('.address-section').css('display', 'block');
        $('.logo-section').css('display', 'block');
        
    }
    else
    {
        $("#header").prop('checked', false);
        $(".header-address").prop('checked', false);
        $(".header-logo").prop('checked', false);


        $('.address-section').css('display', 'none');
        $('.logo-section').css('display', 'none');
        
    }

     });

   $('body').on('click', '.smryChk-All', function() {


    if( $(".smryChk-All:checked").length == 1  ) 
    {
        //$("#summary").prop('checked', true);
        
        $('.summaryPart').show();
        $(".smryCheckbox").prop('checked', true);
        
    }
    else
    {
        $(".smryCheckbox").prop('checked', false);
        $('.summaryPart').hide();
        //$("#summary").prop('checked', false);
        
    }

});

   $('body').on('click', '.itemChk-All', function() {


    if( $(".itemChk-All:checked").length == 1  ) 
    {
        $("#itemTable").prop('checked', true);
        $('.itemSectionPart').css('display', 'block');
        $(".itmTblCheckbox").prop('checked', true);
        
    }
    else
    {
        $("#itemTable").prop('checked', false);
        $('.itemSectionPart').css('display', 'none');
        $(".itmTblCheckbox").prop('checked', false);
        
    }

});

    function editStockTake(barCode) {
     
        $.ajax({
            method: "POST",
            url: "convertRawItems.php"
        })
        .done(function(htmlRes) {
            $('#qty_in_stock_ajax_content').html(htmlRes);
           $('#qty_in_stock').modal('show');
        });
    }
       function approveConvert()
{
   /// $('#sbtId').html('Please wait...');
    
    document.getElementById('frmconvert').action = "convertRawItemsConfirmSubmit.php"; //Will set it

    document.getElementById("frmconvert").submit();
    
}
  </script>
  <script type="text/javascript">
    window.onload = function() {
        var fileupload = document.getElementById("uploadFile");
        var button = document.getElementById("btnFileUpload");
        button.onclick = function() {
            fileupload.click();
        };
        fileupload.onchange = function() {
            document.getElementById('upload_form').submit();
        };
    };
    </script>
  <style>
.strfetCol { width:23%; }
.stkItmclm {width: 100%;}
.stkTblhead .stkItmclm, .stkTblhead .stkItmclm { width: 100%;}
.stkTblhead .stkNamcol, .cntTable .stkNamcol { width: 50%;}
.stockView .lstPrcol{width: 33%;}
.stkTblhead .stkPrcol, .cntTable .stkPrcol {width: 50%;}
.stkTblhead .mbShw, .cntTable .mbShw {width: 100%;}
  </style>
}
</body>

</html>