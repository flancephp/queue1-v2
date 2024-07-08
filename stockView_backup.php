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
//$suppOptions .= '<option value="">'.showOtherLangText('Supplier').'</option>';
while($departRow = mysqli_fetch_array($resultSet) )
{
$sel = isset($_GET['suppId']) && $_GET['suppId'] == $departRow['id']  ? 'selected' : '';
$suppOptions .= '<li data-id="'.$departRow['id'].'" data-value="'.$departRow['name'].'"><a class="dropdown-item '.$sel.'" href="javascript:void(0)">'.$departRow['name'].'</a>
                                                </li>';
}
$suppOptions .= '</ul>';



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
                                    <h1 class="h1">Stock View</h1>
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

                    <!-- <div class="alrtMessage">
                        <div class="container">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p><strong>Hello User!</strong> Your item overwrite successfully.</p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>

                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><strong>Hello User!</strong> error while overwrite item.</p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    </div> -->
                    <form name="frm" id="frm" method="get" action="">
                        <input type="hidden" name="subCatId" id="subCatId" value="" />
                        <input type="hidden" name="suppId" id="suppId" value="" />
                         
                     </form>
                    <div class="stkView">
                        <div class="container">
                            <div class="row">
                                <div class="storeCol">
                                    <div class="store text-center d-flex">

                                        <a href="javascript:void(0)" class="dskAll-str">
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
                                                    <h2 class="h2"><?php echo $storageDeptRow['name'];?></h2>
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
                                    <div class="row stkRow">
                                        <div class="col-md-6 stockFeat brdLft">
                                            <a href="javascript:void(0)" class="tabFet">
                                                <span class="prdItm"></span>
                                                <p class="btn2">Produce Item</p>
                                            </a>
                                        </div>

                                        <div class="col-md-6 stockFeat dropStk">
                                            <a href="javascript:void(0)" class="dropdown-toggle tabFet" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="edIt"></span>
                                                <p class="btn2 d-flex justify-content-center align-items-center">
                                                    <span>Stock take</span> <i class="fa-solid fa-angle-down"></i>
                                                </p>
                                            </a>

                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="stockTake.php">Stock take1</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Stock take2</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Other</a></li>
                                            </ul>
                                        </div>
                                    </div>
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
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/chkColumn.svg" alt="Check Column">
                                            </a>
                                        </div>
                                        <div class="chkStore">
                                            <a href="stock_excel.php" target="_blank">
                                                <img src="Assets/icons/stock-xcl.svg" alt="Stock Xcl">
                                            </a>
                                        </div>
                                        <div class="chkStore">
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#stock_pdf">
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
                                <div class="stkNamcol d-flex align-items-center">
                                    <div class="tb-head stkItmclm">
                                        <div class="d-flex align-items-center">
                                            <p><?php echo showOtherLangText('Item'); ?></p>
                                            <span class="dblArrow">
                                                <a onclick="sortTableByColumn('.newStockTask', '.stkItmclm','asc');" href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a onclick="sortTableByColumn('.newStockTask', '.stkItmclm','desc');" href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="tb-head stkQtyclm">
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
                            </div>
                            <div class="mbHde align-items-center supData-Head">
                                <div class="tb-head supStkclm">
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
                                <div class="tb-head supStkclm">
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
                     
                         //$catNames =  $row['parentCatName'].'<br/>/'. ($row['childCatName'] == 'z' ? '' :  $row['childCatName']);
                         $catNames = ($row['childCatName'] == 'z' ? '' :  $row['childCatName']); ?>
                             <div class="newStockTask"><div class="d-flex align-items-center border-bottom itmBody">
                                <div class="mbShw d-flex align-items-center">
                                    <div class="tb-bdy stkImgcol">
                                        <?php echo $img; ?>
                                    </div>
                                    <div class="stkNamcol d-flex align-items-center">
                                        <div class="tb-bdy stkItmclm">
                                            <a href="itemHistoryView.php?id=<?php echo $row['id']; ?>" class="itm-Profile"><?php echo $row['itemName']; ?></a>
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
                                </div>
                                <div class="mbHde align-items-center suppData-stk">
                                    <div class="tb-bdy supStkclm">
                                        <p><?php echo $catNames; ?></p>
                                    </div>
                                    <div class="tb-bdy supStkclm">
                                        <p><?php echo $supNames; ?></p>
                                    </div>
                                </div>
                            </div></div>
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
            <div class="modal-content p-2">
                <div class="modal-header pb-3">
                    <div class="d-md-flex align-items-center justify-content-between w-100 ">
                        <div class="d-flex align-items-start w-100 gap-3 w-auto mb-md-0 mb-2 modal-head-btn">
                        
                                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#modalfiltertop">
                                    <i class="fa fa-filter"></i>
                                </button>
                            
                                <div class="collapse" id="modalfiltertop">
                                    <div class="d-flex gap-3 modal-head-row">
                                    

                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        Headers<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="form-check-input" value="1">
                                                <span class="fs-13">Check All</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="2">
                                                <span class="fs-13">Address</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="logo" class="form-check-input" value="4">
                                                <span class="fs-13">Logo</span>
                                            </li>
                                        </ul>
                                    </div>                                  

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        Summary<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="form-check-input" value="1">
                                                <span class="fs-13">Check All</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="issuedIn" class="form-check-input" value="2">
                                                <span class="fs-13">Stores</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="defaultCurrency" class="form-check-input" value="3">
                                                <span class="fs-13">Total Price</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="otherCurrency" class="form-check-input" value="4">
                                                <span class="fs-13">Stock take</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        Item Table<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="form-check-input" value="1">
                                                <span class="fs-13">Check All</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="taskNo" class="form-check-input" value="2">
                                                <span class="fs-13">Photo</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="date" class="form-check-input" value="3">
                                                <span class="fs-13">Item1</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="user" class="form-check-input" value="4">
                                                <span class="fs-13">Barcode</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="supInvoiceNo" class="form-check-input" value="5">
                                                <span class="fs-13">Quantity</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="type" class="form-check-input" value="6">
                                                <span class="fs-13">Requests Qty</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="referTo" class="form-check-input" value="7">
                                                <span class="fs-13">Available Qty</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="value" class="form-check-input" value="8">
                                                <span class="fs-13">Last price</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="defaultCurrencyTotal" class="form-check-input" value="9">
                                                <span class="fs-13">Stock price</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="secondCurrencyTotal" class="form-check-input" value="10">
                                                <span class="fs-13">Stock value</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="status" class="form-check-input" value="12">
                                                <span class="fs-13">Sub Category</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="paymentNo" class="form-check-input" value="12">
                                                <span class="fs-13">Supplier</span>
                                            </li>
                                        </ul>
                                    </div>

                                    </div>
                                
                                </div>

                        

                        </div>
                        <a href="stockviewpdf.html" target=" " class="btn"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
                    </div>
                </div> 
                <div class="modal-body px-2 py-3">
                    <div class="row pb-3">
                        <div class="col-6">
                            <div class="modal-address ">
                                <h6 class="semibold fs-14">Our Zazibar</h6>
                                <div class="fs-13 ">
                                    <p>P.o Box 4146</p>
                                    <p>Jambiani</p>
                                    <p>Zanzibar, TANZANIA</p>
                                    <p>inventory@our-zanzibar.com</p>
                                    <p>+255743419217</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <div class="modal-logo">
                                <img src="Assets/icons/logo_Q.svg" alt="Logo">
                            </div>
                        </div>
                    </div>

                    <div class="model-title-with-date">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h6 class="semibold">Stock View</h6>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-end fs-15">07-05-2024</p>
                            </div>
                        </div>
                    </div>

                    <div class="summery-row">
                        <div class="row">
                            <div class="storesection pe-6 w-100">
                                <div class="modal-table fs-12 w-100">
                                  
                                    <div class="table-row thead">
                                        <div class="table-cell">Store</div>
                                        <div class="table-cell">Total price</div>
                                        <div class="table-cell">Stock take</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Drinks Store </div>
                                        <div class="table-cell">24,000 $</div>
                                        <div class="table-cell">2</div>
                                    </div>
                        
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="overflowTable"> 
                        <div class="modal-table fs-12 w-100 mt-4">
                            <div class="table-row thead">
                                <div class="table-cell">#</div>
                                <div class="table-cell">Photo</div>
                                <div class="table-cell">Item<br>/Barcode</div>
                                <div class="table-cell">Quantity</div>
                                <div class="table-cell">Req Qty</div>
                                <div class="table-cell">Avail Qty</div>
                                <div class="table-cell">Last price</div>
                                <div class="table-cell">Stock price</div>
                                <div class="table-cell">Stock value</div>
                                <div class="table-cell">Sub Category</div>
                                <div class="table-cell">Supplier</div>
                            </div>
                            <div class="table-row">
                                <div class="table-cell">1</div>
                                <div class="table-cell table-cell-photo"><img src="Assets/images/Apple.png" alt="Outlet_Photo"></div>
                                <div class="table-cell">Apple <br>777666</div>
                                <div class="table-cell font-bold">50 Kg</div>
                                <div class="table-cell">0 Kg</div>
                                <div class="table-cell font-bold">50 Kg</div>
                                <div class="table-cell">10 $</div>
                                <div class="table-cell">10 $</div>
                                <div class="table-cell">500 $</div>
                                <div class="table-cell">Fruits</div>
                                <div class="table-cell">Agora</div>
                            </div>
                            <div class="table-row">
                                <div class="table-cell">2</div>
                                <div class="table-cell table-cell-photo"><img src="Assets/images/Apple.png" alt="Outlet_Photo"></div>
                                <div class="table-cell">Apple <br>777666</div>
                                <div class="table-cell font-bold">50 Kg</div>
                                <div class="table-cell">0 Kg</div>
                                <div class="table-cell font-bold">50 Kg</div>
                                <div class="table-cell">10 $</div>
                                <div class="table-cell">10 $</div>
                                <div class="table-cell">500 $</div>
                                <div class="table-cell">Fruits</div>
                                <div class="table-cell">Agora</div>
                            </div>
                            <div class="table-row">
                                <div class="table-cell">3</div>
                                <div class="table-cell table-cell-photo"><img src="Assets/images/Apple.png" alt="Outlet_Photo"></div>
                                <div class="table-cell">Apple <br>777666</div>
                                <div class="table-cell font-bold">50 Kg</div>
                                <div class="table-cell">0 Kg</div>
                                <div class="table-cell font-bold">50 Kg</div>
                                <div class="table-cell">10 $</div>
                                <div class="table-cell">10 $</div>
                                <div class="table-cell">500 $</div>
                                <div class="table-cell">Fruits</div>
                                <div class="table-cell">Agora</div>
                            </div>
                            <div class="table-row">
                                <div class="table-cell">4</div>
                                <div class="table-cell table-cell-photo"><img src="Assets/images/Apple.png" alt="Outlet_Photo"></div>
                                <div class="table-cell">Apple <br>777666</div>
                                <div class="table-cell font-bold">50 Kg</div>
                                <div class="table-cell">0 Kg</div>
                                <div class="table-cell font-bold">50 Kg</div>
                                <div class="table-cell">10 $</div>
                                <div class="table-cell">10 $</div>
                                <div class="table-cell">500 $</div>
                                <div class="table-cell">Fruits</div>
                                <div class="table-cell">Agora</div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <!-- ===== Stock pdf popup new in div format======= -->

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
    <script>
    $(function() {
    

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

   // function sortRows(sort) {
   //      var uu = $(".newStockTask").orderBy(function() {
   //           var number = +$(this).find(".stkItmclm").text().replace('No. ', '');
   //           return sort === 1 ? number : -number; 
   //      }).appendTo("#myRecords");


   //  }

  

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
  </script>
</body>

</html>