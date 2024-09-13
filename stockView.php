<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

// comment 

$rightSideLanguage = ($getLangType == 1) ? 1 : 0;

//check page permission

// test comments
$checkPermission = permission_denied_for_section_pages($_SESSION['designation_id'], $_SESSION['accountId']);

if (!in_array('5', $checkPermission)) {
    echo "<script>window.location='index.php'</script>";
}

if (isset($_SESSION['checkDublicateClick']) && $_SESSION['checkDublicateClick'] == 1) {
    unset($_SESSION['checkDublicateClick']);
}

//test
//get access of Xcel and pdf file
$showExcelPdfFile = access_stock_xcl_pdf_file($_SESSION['designation_id'], $_SESSION['accountId']);


if (isset($_SESSION['processId'])) {
    unset($_SESSION['processId']);
}

if (isset($_REQUEST['showFieldsStock'])) {
    $updateQry = " UPDATE tbl_user SET stockUserFilterFields = '" . implode(',', $_REQUEST['showFieldsStock']) . "' WHERE id = '" . $_SESSION['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";

    mysqli_query($con, $updateQry);
} elseif (isset($_REQUEST['clearshowFieldsStock'])) {
    $updateQry = " UPDATE tbl_user SET stockUserFilterFields = '' WHERE id = '" . $_SESSION['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
    mysqli_query($con, $updateQry);
}

$sql = "SELECT * FROM tbl_user  WHERE id = '" . $_SESSION['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
$result = mysqli_query($con, $sql);
$userDetails = mysqli_fetch_array($result);
$stockUserFilterFields = $userDetails['stockUserFilterFields'] ?    explode(',', $userDetails['stockUserFilterFields']) : null;



$cond = '';
$stockCond = '';
if (isset($_GET['filterByStorage']) && ($_GET['filterByStorage']) != '') {
    $cond = " AND tp.storageDeptId = '" . $_GET['filterByStorage'] . "' ";

    $_SESSION['filterByStorage'] = $_GET['filterByStorage'];
}
if (isset($_GET['subCatId']) && $_GET['subCatId']) {
    $cond .= " AND c.id = '" . $_GET['subCatId'] . "'  ";
    $stockCond .= " AND c.id = '" . $_GET['subCatId'] . "'  ";
}
if (isset($_GET['suppId']) && $_GET['suppId']) {
    $sql = " SELECT productId FROM tbl_productsuppliers WHERE supplierId = '" . $_GET['suppId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $sqlRes = mysqli_query($con, $sql);

    $tpIdArr = [];
    while ($resRow = mysqli_fetch_array($sqlRes)) {
        $tpIdArr[] = $resRow['productId'];
    }
    $tpId = implode(',', $tpIdArr);


    $cond .= " AND tp.ID IN(" . $tpId . ") ";


    $sql = " SELECT productId FROM tbl_productsuppliers WHERE supplierId = '" . $_GET['suppId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ";
    $sqlRes = mysqli_query($con, $sql);

    $tpIdArr = [];
    while ($resRow = mysqli_fetch_array($sqlRes)) {
        $tpIdArr[] = $resRow['productId'];
    }
    $tpId = implode(',', $tpIdArr);

    $stockCond .= " AND tp.ID IN(" . $tpId . ") ";

    //$cond .= " AND tp.ID IN(SELECT productId FROM tbl_productsuppliers WHERE supplierId = '".$_GET['suppId']."' AND account_id = '".$_SESSION['accountId']."' ) ";

    //$stockCond .= " AND tp.ID IN(SELECT productId FROM tbl_productsuppliers WHERE supplierId = '".$_GET['suppId']."'  AND account_id = '".$_SESSION['accountId']."'  ) ";
}
if (!empty(get_store_permission($_SESSION['designation_id'], $_SESSION['accountId']))) {

    $cond .= " AND dssp.designation_id = '" . $_SESSION['designation_id'] . "' AND dssp.type = 'stock' AND dssp.designation_section_permission_id = '5'  ";
}

if ($cond != '') {
    $_SESSION['getVals'] = $_GET;
} else {
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

WHERE tp.account_id ='" . $_SESSION['accountId'] . "' " . $cond . " GROUP BY tp.id ORDER by s.id DESC ";

$stockMainQry = mysqli_query($con, $sql);
$stockMainQry_hide = mysqli_query($con, $sql);
//End stock lists


//get store permission
$storeCond = '';
if (!empty(get_store_permission($_SESSION['designation_id'], $_SESSION['accountId']))) {

    $storeCond .= " AND dssp.designation_id = '" . $_SESSION['designation_id'] . "' AND dssp.type = 'stock' AND dssp.designation_section_permission_id = '5'  ";
}

//Store details queries goes here
$sqlSet = " SELECT DISTINCT(s.id),s.* FROM tbl_stores s

LEFT JOIN tbl_designation_sub_section_permission dssp
ON(s.id=dssp.type_id) AND s.account_id=dssp.account_Id 

WHERE s.account_id = '" . $_SESSION['accountId'] . "' " . $storeCond . "  ";
$storeQry = mysqli_query($con, $sqlSet);


$sql = "SELECT SUM(stockValue) totalstockValue  FROM tbl_stocks s 

INNER JOIN tbl_products tp ON(s.pId = tp.id) AND tp.status=1 AND s.account_id = tp.account_id

LEFT JOIN tbl_category c ON (c.id = tp.catId) AND c.account_id = tp.account_id

LEFT JOIN tbl_stores st ON(st.id = tp.storageDeptId) AND st.account_id = tp.account_id

LEFT JOIN tbl_designation_sub_section_permission dssp
ON(st.id=dssp.type_id) AND st.account_id=dssp.account_Id

WHERE 1=1 AND s.account_id = '" . $_SESSION['accountId'] . "' " . $storeCond . " " . $stockCond;
$resQry = mysqli_query($con, $sql);
$storeResRow = mysqli_fetch_array($resQry);
//End



//---------------------------------------------

$sqlSet = " SELECT c.* FROM  tbl_category c

INNER JOIN tbl_products tp ON
(c.id = tp.catId) AND tp.status = 1 AND c.account_id = tp.account_id

WHERE c.parentId > 0 AND c.account_id = '" . $_SESSION['accountId'] . "' GROUP BY tp.catId ORDER BY c.name ";
$resultSet = mysqli_query($con, $sqlSet);


$subCatOptions = '<ul class="dropdown-menu subcat_opt">';
$subCatOptions .= '<li data-id="" data-value=""><a class="dropdown-item" href="javascript:void(0)">' . showOtherLangText('Sub Catagory') . '</a></li>';
while ($catRow = mysqli_fetch_array($resultSet)) {
    $sel = isset($_GET['subCatId']) && $_GET['subCatId'] == $catRow['id']  ? 'selected' : '';

    $subCatOptions .= '<li data-id="' . $catRow['id'] . '" data-value="' . $catRow['name'] . '"><a class="dropdown-item ' . $sel . '" href="javascript:void(0)">' . $catRow['name'] . '</a></li>';
}
$subCatOptions .= '</ul>';


$sqlSet = " SELECT * FROM  tbl_suppliers 
WHERE account_id = '" . $_SESSION['accountId'] . "' ORDER BY name ";
$resultSet = mysqli_query($con, $sqlSet);

$suppOptions = '<ul class="dropdown-menu supplier_opt">';
$suppOptions .= '<li data-id="" data-value=""><a class="dropdown-item" href="javascript:void(0)">' . showOtherLangText('Supplier') . '</a></li>';
while ($departRow = mysqli_fetch_array($resultSet)) {
    $sel = isset($_GET['suppId']) && $_GET['suppId'] == $departRow['id']  ? 'selected' : '';
    $suppOptions .= '<li data-id="' . $departRow['id'] . '" data-value="' . $departRow['name'] . '"><a class="dropdown-item ' . $sel . '" href="javascript:void(0)">' . $departRow['name'] . '</a>
                                                </li>';
}
$suppOptions .= '</ul>';
$convertrawitems = false;
if (isset($_POST['rawItem']) && $_POST['rawItem'] > 0) {
    $convertrawitems = true;
    $_SESSION['convertItemsPost'] = $_POST;

    $sql = "SELECT p.*, s.qty stockQty, s.stockPrice, IF(u.name!='',u.name,p.unitC) unitC FROM tbl_stocks s 
    INNER JOIN tbl_products p 
        ON(s.pId = p.id) AND s.account_id = p.account_id 
    LEFT JOIN tbl_units u 
        ON(p.unitC = u.id) AND p.account_id = u.account_id

    WHERE p.id = '" . $_POST['rawItem'] . "'  AND p.account_id = '" . $_SESSION['accountId'] . "'   ";
    $qryRawItem = mysqli_query($con, $sql);
    $rawItemRow = mysqli_fetch_array($qryRawItem);

    $sql = "SELECT p.*, s.qty stockQty, s.stockPrice,  IF(u.name!='',u.name,p.unitC) unitC 
    FROM tbl_stocks s 
    RIGHT JOIN tbl_products p 
        ON(s.pId = p.id) AND s.account_id = p.account_id 
    LEFT JOIN tbl_units u 
        ON(p.unitC = u.id) AND p.account_id = u.account_id
    WHERE p.id = '" . $_POST['convertItem'] . "'  AND p.account_id = '" . $_SESSION['accountId'] . "' ";
    $qryConvertItem = mysqli_query($con, $sql);
    $convertItemRow = mysqli_fetch_array($qryConvertItem);
}

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Stock View - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/style1.css">
    <style>
        .strfetCol { width: 310px; } 
        .storeCol { width: calc(100% - 350px); }

        @media(max-width:991px) {
            .storeCol {
                width: 100% !important;
            }
        }
        

        @media (max-width: 767px) {
            .storeCol .col-4.stockFeat {
                width: 33.333% !important;
            }
        }

        @media screen and (min-width: 1600px) {

            .container,
            .container-lg,
            .container-md,
            .container-sm,
            .container-xl,
            .container-xxl {
                max-width: 1320px;
            }
        }

        @media screen and (min-width: 1800px) {

            .container,
            .container-lg,
            .container-md,
            .container-sm,
            .container-xl,
            .container-xxl {
                max-width: 1640px;
            }
        }

        #stock_pdf .table-row>.table-cell:nth-child(4),
        #stock_pdf .table-row>.table-cell:nth-child(5),
        #stock_pdf .table-row>.table-cell:nth-child(6),
        #stock_pdf .table-row>.table-cell:nth-child(7),
        #stock_pdf .table-row>.table-cell:nth-child(8),
        #stock_pdf .table-row>.table-cell:nth-child(9) {
            white-space: nowrap;
        }

        .modal .form-control {
            width: 100% !important;
            margin: 0% !important;
        }
        .counter__col { width: 55px; }
        .stkItmclm { width: 100%; } 
        .stkTblhead .stkItmclm,
        .stkTblhead .stkItmclm,
        .stkTblhead .stkbarclm { width: 61%;  } 
        .stkTblhead .stkNamcol,
        .cntTable .stkNamcol { width: 50%; } 
        .page2bdy .stkTblhead .stkNamcol{width: 65% !important;}
        #page2bdy   .stkTblhead .stkNamcol { width: 63%; } 
        .page2bdy .cntTable .stkNamcol { width: 63% !important;}


        .cntTable1 .stkNamcol {
            width: 65%;
            justify-content: space-evenly;
        }

 

        .stkTblhead .stkPrcol {
            width: 25%;
        }

        .cntTable .stkPrcol {
            width: 50%;
        }
        .page2bdy   .cntTable .stkPrcol {
            width: 33%;
        }

        .small-font{font-size:9px;}
        @media (min-width:767px) {
            
            .mbShw .stkPrcol {
                width: 35% !important;
            }

            .stockView .cntTable {
                padding: 0 54px !important;
            }

            .stkTblhead {
                padding: 0 54px !important;
            }

            #page2head.stkTblhead .mbShw,
            .page2bdy .cntTable .mbShw {
                width: 80%;
            }

            #page2head.stkTblhead .mbHde,
            .page2bdy .cntTable .mbHde {
                width: 20%;
            }

            .cntTable .stkNamcol>div:nth-child(1),.stkTblhead .stkNamcol>div:nth-child(1) {width: 40%;}
            .cntTable .stkNamcol>div:nth-child(2),.stkTblhead .stkNamcol>div:nth-child(2) {width: 25%;}
            .cntTable .stkNamcol>div:nth-child(3),.stkTblhead .stkNamcol>div:nth-child(3) {width: 15%;}
            .cntTable .stkNamcol>div:nth-child(4),.stkTblhead .stkNamcol>div:nth-child(4) {width: 15%;}
            .cntTable .stkNamcol>div:nth-child(5),.stkTblhead .stkNamcol>div:nth-child(5) {width: 15%;}

            #page1head .stkNamcol>div:nth-child(2),
            #page1head .stkNamcol>div:nth-child(3),
            #page1head .stkNamcol>div:nth-child(4),
            .page1bdy .cntTable .stkNamcol>div:nth-child(2),
            .page1bdy .cntTable .stkNamcol>div:nth-child(3),
            .page1bdy .cntTable .stkNamcol>div:nth-child(4){
                width: 20% !important;
            }
            #page1head .mbShw .stkPrcol .lstPrcol {width: 33% !important;}
            #page1bdy .mbShw .stkPrcol .lstPrcol {width: 50% !important;}
        }
        #upload_form .dropStk ul{ opacity: 1 !important;     inset: 13px 0px auto auto !important;}
        .md__33 { width: 33%; }
        @media (max-width: 991px) {
            .cntTable .stkPrcol { width: 30%; } 
            .page2bdy .cntTable .stkPrcol { width: 33%;display:block !important; }
            .md__33{ width: 100%; }
            #upload_form { max-width:240px; }
            #upload_form .stockFeat .tabFet { width: 100%;min-height: 57px; }
            #upload_form .dropStk p span { white-space: break-spaces; }
        }
        @media (max-width: 767px) { 
            .md__33{ line-height: 20px; }
            .cntTable .stkNamcol { width: 50%; }
            .stockView .lstPrcol { width: 100% !important;} 
            .mbShw { width: 100% !important; }
            .tb-bdy.stkQtyclm.stkQtybdy, .tb-bdy.stkQtyclm.stkreqbdy, .tb-bdy.stkQtyclm.stkavabdy{ padding-top: 5px;padding-bottom: 5px; }
            .stkNamcol .tb-bdy .itm-Profile{
                line-height:28px;
            }
            .mbLst-value { padding-right: 6px; }
            #upload_form { max-width:225px; }

            
        }

        .cntTable1 .stkPrcol {
            width: 25%;
        }

        .w-55 {
            width: 45%;
        }

        .storeCont .h2 {
            overflow: hidden;
            text-overflow: ellipsis;
            -webkit-line-clamp: 2;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            white-space: normal;
        }

        .itemSectionPart .modal-table .table-row .table-cell:first-child {
            min-width: 40px;
        }

        .tb-bdy.stkImgcol .imgItm {
            height: 60px;
            width: 50px;
        }

        .tb-bdy.stkImgcol {
            width: 10%;
            max-width: 90px;
        }

        .stkTblhead .tb-head {
            color: #7a89fe;
        }
 /*        .stkItmclm {
            color: #000 !important;
            font-size: 11px;
    line-height: 15px;
    font-weight: 500;
        } */

        .tb-bdy {
            padding: 5px 8px;
        }
        .tb-bdy {
                font-size: 16px;
                font-weight: 400;
            }
            .stkQtybdy{}
         .stkQtybdy span{font-weight: 600;}

        @media screen and (min-width: 1600px) {
            .tb-bdy {
                padding: 2px 8px;
            }
        }

        #page2head .mbShw .stkPrcol .lstPrcol {width: 33%;} 
        .storeCont .h2 {
            overflow: hidden !important;
        }
        .mblCnt {
            border-radius: 20px !important;
            border: 2px solid #FFF;
            background: #7A89FE !important;
            box-shadow: 0px -1px 3px 0px rgba(16, 41, 104, 0.54);
            font-size: 18px;
            top: -10%;
            left: -10%;
            position: absolute !important;
            padding: 4px 8px;
            color: #fff;
        }

    /*     @media (min-width: 768px) {
            .stkView .container:not(.large) { max-width: 720px; }
        } 
        @media (min-width: 992px) {
        .stkView .container:not(.large) {max-width: 960px;}
        }

        @media (min-width: 1350px) {
        .stkView  .container:not(.large) {max-width: 1140px;}
        }
 */

        




/* @media (min-width: 1200px) {
  .stkView .container {
    max-width: 1000px !important;
  }
}

@media (min-width: 1400px) {
  .stkView .container {
    max-width: 1140px  !important;
  }
}
      */   

      /* 04-09-24 sudarshan */
      @media (min-width: 767px) {
    #page2head .stkTblhead .mbShw, .page2bdy .cntTable .mbShw {
        width: 85% !important;
    }
    #page2head.stkTblhead .mbShw, .page2bdy .cntTable .mbShw {
        width: 85% !important;
}
    #page2head .stkTblhead .mbHde, .page2bdy .cntTable .mbHde {
        width: 15%;
    }
    #page2head.stkTblhead .mbHde, .page2bdy .cntTable .mbHde {
        width: 15%;
    }
    
}
.stkPrcol11:has(.tb-bdy[style*="display: none"]) .tb-bdy:not([style*="display: none"]) {
    width: 50% !important;
}
@media (max-width:767px) {
    .small-font{font-size:12px;}
    .counter__col {
        display: none;
    }
    .stockView .lstPrcol.lstPrcol {
    width: 100% !important;padding-bottom: 6px;
    }
    .tb-bdy.stkImgcol {
    width: 20%;
    max-width: 90px;
    }
    .mb-srcBox > div {
    gap: .5rem;
    }
    .mbClone-src {
    flex-grow: 1;
    }
    .supStkclm .dropdown-toggle {
        width: 100%;
    }
    .stkPrcol11:has(.tb-bdy[style*="display: none"]) .tb-bdy:not([style*="display: none"]) {
    width: 100% !important; line-height:20px;
}
}


#page2head .stkPrcol:has(.tb-head[style*="display: none"]) .tb-head:not([style*="display: none"]) {
    width: 50% !important;
}



.stkNamcol01, .page2head .cntTable .stkNamcol {
    width: 63% !important;
}
.stockView .lstPrcol {
    width: 33.33% !important;
}

.dskAll-str{width: 45px;}

.modal-footer .btnBg{
    padding-right:16px;
}
.modal-footer .btnBg{
    padding-right:16px;
}
.justify-content-start{}

.justify-content-start .btn{margin-right:20px;}


      
    </style>
</head>

<body>
    <?php
    $storeId = isset($_GET['filterByStorage']) && ($_GET['filterByStorage']) != '' ? 1 : '';
    ?>
    <input type="hidden" name="storeName" id="storeName" value="<?php echo $storeId; ?>" />

    <div class="container-fluid newOrder">
        <div class="row g-0 flex-nowrap">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                <?php require_once('nav.php'); ?>
            </div>
            <div class="cntArea cntAreartl">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('Stock View'); ?></h1>
                        </div>
                        <div class="col-md-8 d-flex align-items-center justify-content-end">
                            <div class="mbPage">
                                <div class="mb-nav" id="mb-nav">
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"><i class="fa-solid fa-bars"></i></span>
                                    </button>
                                </div>
                                <div class="mbpg-name">
                                    <h1 class="h1"><?php echo showOtherLangText('Stock View'); ?></h1>
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

                <section class="ordDetail stockView">

                    <form name="frm" id="frm" method="get" action="">
                        <input type="hidden" name="subCatId" id="subCatId" value="<?php echo $_GET['subCatId']; ?>" />
                        <input type="hidden" name="suppId" id="suppId" value="<?php echo $_GET['suppId']; ?>" />
                        <input type="hidden" name="filterByStorage" id="filterByStorage" value="<?php echo $_GET['filterByStorage']; ?>" />
                    </form>
                    <div class="stkView">
                        <div class="container large">
                            <?php if (isset($_GET['stockTake']) || isset($_GET['convertRawItem'])) { ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <p>
                                        <?php
                                        echo isset($_GET['stockTake']) ? ' ' . showOtherLangText('Item overwrite successfully') . ' ' : '';
                                        echo isset($_GET['convertRawItem']) ? ' ' . showOtherLangText('Raw Item converted successfully') . ' ' : '';
                                        ?>
                                    </p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php } ?>
                            <div class="row g-0">
                                <div class="storeCol" style="background:transparent !important;">
                                    <div class="store text-center d-flex">

                                        <a href="javascript:void(0)" class="dskAll-str">
                                            <div class="allStr">
                                                <h2 class="h2"><?php echo showOtherLangText('All Stores') ?></h2>
                                            </div>
                                        </a>
                                        <?php if (mysqli_num_rows($storeQry) > 1) {  ?>
                                            <a href="javascript:void(0)" class="allStore">
                                                <div class="othStr">
                                                    <div class="storeCont">
                                                        <h2 class="h2"><?php echo showOtherLangText('All Stores') ?></h2>
                                                        <hr>
                                                        <p class="body2">
                                                            <?php showPrice($storeResRow['totalstockValue'], $getDefCurDet['curCode']); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php
                                        }

                                        while ($storageDeptRow = mysqli_fetch_array($storeQry)) {
                                            $totalstockValue = getStockTotalOfStore($storageDeptRow['id'], $stockCond);
                                        ?>
                                            <a href="stockView.php?filterByStorage=<?php echo $storageDeptRow['id']; ?>" class="otrStock <?php echo $_GET['filterByStorage'] == $storageDeptRow['id'] ? 'stockActive-br' : ''; ?>">
                                                <div class="othStr">
                                                    <div class="storeCont">
                                                        <h2 class="h2">
                                                            <?php echo getMobileStockTakeCount($storageDeptRow['id']); ?> <?php echo $storageDeptRow['name']; ?>
                                                        </h2>
                                                        <hr>
                                                        <p class="body2">
                                                            <?php showPrice($totalstockValue, $getDefCurDet['curCode']); ?>
                                                        </p>
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
                                    <form action="stockTake.php" id="upload_form" name="upload_form" method="post" enctype="multipart/form-data">
                                        <div class="row stkRow g-0">
                                            <input type="hidden" name="storeId" value="<?php echo $_GET['filterByStorage']; ?>" />
                                            <div class="col-4 stockFeat p-0 p-lg-3 brdLft">

                                                <?php access_raw_item_convert($_SESSION['designation_id'], $_SESSION['accountId']); ?>
                                            </div>
                                            <div class="col-4 stockFeat p-0 p-lg-3 brdLft">
                                                <?php access_view_stockTake($_SESSION['designation_id'], $_SESSION['accountId'], $_GET['filterByStorage']);  ?>
                                            </div>
                                            <div class="col-4 stockFeat p-0 p-lg-3 dropStk d-flex">


                                                <?php access_import_stockTake($_SESSION['designation_id'], $_SESSION['accountId'], $_GET['filterByStorage'], $rightSideLanguage);  ?>

                                            </div>
                                        </div>
                                    </form>
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
                                        <input type="search" class="form-control" onKeyUp="myFunction()" placeholder="<?php echo showOtherLangText('Search') ?>" name="search" id="search" aria-label="Search">
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
                                            <a href="javascript:void(0)" style="height: 20px;" id="toggle-page-btn" class="cstBtn-Sale toggle-page-btn hideBtn-Info ">
                                                <img src="Assets/icons/info.svg" style="height: 100%;" alt="Information" class="cstBtn-Img">
                                            </a>

                                        </div>
                                        <div class="chkStore">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/chkColumn.svg" class="flDwn-Icn" id="filterBtn" title="Filter column list" alt="Check Column">
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
                                <div class="hdfetCol text-center px-0">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container mb-srcBox mb-3 mb-md-0">
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

                    <div class="container stkTblhead  position-relative tbl-head-page-1" id="page1head">
                        <!-- Item Table Head Start -->
                        <div class="d-none d-md-flex align-items-center itmTable" style="min-height: 70px;">
                            <div class="mbShw d-flex align-items-center">
                                <div class="tb-bdy counter__col"><?php echo mysqli_num_rows($stockMainQry) > 0 ? mysqli_num_rows($stockMainQry) : ''; ?></div>
                                <?php if (isset($stockUserFilterFields) && !in_array(1, $stockUserFilterFields)) { ?>
                                <?php } else { ?>
                                    <div class="tb-bdy stkImgcol"><?php echo showOtherLangText('Photo'); ?></div>
                                <?php } ?>
                                <div class="stkNamcol d-flex align-items-center">
                                    <div class="tb-head stkItmclm skt-font">
                                        <div class="d-flex align-items-center w-20">
                                            <?php if (isset($stockUserFilterFields) && !in_array(2, $stockUserFilterFields)) { ?>
                                            <?php } else { ?>
                                                <p style="color:#1c2047 !important; font-weight: 500;"><?php echo showOtherLangText('Item'); ?></p>
                                                <span class="dblArrow">
                                                    <a onclick="sortTableByColumn('.newStockTask', '.stkItmclm','asc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a onclick="sortTableByColumn('.newStockTask', '.stkItmclm','desc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="tb-head w-55  stkQtyclm">
                                        <div class="d-flex justify-content-en align-items-center">
                                            <?php if (isset($stockUserFilterFields) && !in_array(3, $stockUserFilterFields)) { ?>
                                            <?php } else { ?>
                                                <p><?php echo showOtherLangText('Qty'); ?></p>
                                                <span class="dblArrow">
                                                    <a onclick="sortTableByColumn('.newStockTask', '.stkQtybdy','asc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a onclick="sortTableByColumn('.newStockTask', '.stkQtybdy','desc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="tb-head w-55 stkQtyclm">
                                        <div class="d-flex justify-content-en align-items-center">
                                            <?php if (isset($stockUserFilterFields) && !in_array(5, $stockUserFilterFields)) { ?>
                                            <?php } else { ?>
                                                <p><?php echo showOtherLangText('Req Qty'); ?></p>
                                                <span class="dblArrow">
                                                    <a onclick="sortTableByColumn('.newStockTask', '.stkreqbdy','asc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a onclick="sortTableByColumn('.newStockTask', '.stkreqbdy','desc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="tb-head w-55  stkavabdy">
                                        <div class="d-flex justify-content-en align-items-center">
                                            <?php if (isset($stockUserFilterFields) && !in_array(4, $stockUserFilterFields)) { ?>
                                            <?php } else { ?>
                                                <p><?php echo showOtherLangText('Avail Qty'); ?></p>
                                                <span class="dblArrow">
                                                    <a onclick="sortTableByColumn('.newStockTask', '.stkavabdy','asc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a onclick="sortTableByColumn('.newStockTask', '.stkavabdy','desc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>

                                </div>
                                <div class="stkPrcol d-flex align-items-center">
                                    <div class="tb-head lstPrcol">
                                        <div class="d-flex align-items-center">
                                            <?php if (isset($stockUserFilterFields) && !in_array(6, $stockUserFilterFields)) { ?>
                                            <?php } else { ?>
                                                <p><?php echo showOtherLangText('Last Price'); ?></p>
                                                <span class="dblArrow">
                                                    <a onclick="sortTableByColumn('.newStockTask', '.mb-Last','asc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a onclick="sortTableByColumn('.newStockTask', '.mb-Last','desc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="tb-head stockPriceCol stockPriceCol lstPrcol">
                                        <div class="d-flex align-items-center">
                                            <?php if (isset($stockUserFilterFields) && !in_array(7, $stockUserFilterFields)) { ?>
                                            <?php } else { ?> <p><?php echo showOtherLangText('Stock  Price'); ?></p>
                                                <span class="dblArrow">
                                                    <a onclick="sortTableByColumn('.newStockTask', '.mb-Stock','asc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a onclick="sortTableByColumn('.newStockTask', '.mb-Stock','desc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="tb-head stockValDefault lstPrcol">
                                        <div class="d-flex align-items-center">
                                        <?php if (isset($stockUserFilterFields) && !in_array(18, $stockUserFilterFields)) { ?>
                                        <?php } else { ?> <p><?php echo showOtherLangText('Stock Value'); ?></p>
                                            <span class="dblArrow">
                                                <a onclick="sortTableByColumn('.newStockTask1', '.stockValDefault','asc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                <a onclick="sortTableByColumn('.newStockTask1', '.stockValDefault','desc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        <?php } ?>
                                        </div>
                                    </div>
                                    

                                </div>

                            </div>
                            <div class="mbHde align-items-center supData-Head">
                                <div class="tb-head supStkclm">
                                    <div class="d-flex align-items-center">
                                        <?php if (isset($stockUserFilterFields) && !in_array(8, $stockUserFilterFields)) { ?>
                                        <?php } else { ?>
                                            <div class="dropdown d-flex position-relative w-100">
                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span id="subcatText"><?php echo showOtherLangText('Sub Catagory'); ?></span>
                                                    <i class="fa-solid fa-angle-down"></i>
                                                </a>

                                                <?php echo $subCatOptions; ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="tb-head supStkclm">
                                    <div class="d-flex align-items-center">
                                        <?php if (isset($stockUserFilterFields) && !in_array(9, $stockUserFilterFields)) { ?>
                                        <?php } else { ?><div class="dropdown d-flex position-relative w-100">
                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span id="SupplierText"><?php echo showOtherLangText('Supplier'); ?></span>
                                                    <i class="fa-solid fa-angle-down"></i>
                                                </a>
                                                <?php echo $suppOptions; ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Item Table Head End -->
                    </div>
                    <div class="container  stkTblhead position-relative tbl-head-page-1 page2head1" id="page2head" style="display: none;">
                        <!-- Item Table Head Start -->
                        <div class="d-none d-md-flex align-items-center itmTable" style="min-height: 70px;">
                            <div class="mbShw d-flex align-items-center">
                                <div class="tb-bdy counter__col"><?php echo mysqli_num_rows($stockMainQry) > 0 ? mysqli_num_rows($stockMainQry) : ''; ?></div>
                                <div class="tb-bdy stkImgcol">
                                    <p><?php echo showOtherLangText('Photo'); ?></p>
                                </div>
                                <div class="stkNamcol stkNamcol01 d-flex align-items-center">
                                    <div class="tb-head stkItmclm">
                                        <div class="d-inline-flex align-items-center" style="color:#1c2047 !important;">
                                            <p><?php echo showOtherLangText('Item'); ?></p>
                                            <span class="dblArrow">
                                                <a onclick="sortTableByColumn('.newStockTask', '.stkItmclm','asc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up" style="color: #000;"></i></a>
                                                <a onclick="sortTableByColumn('.newStockTask', '.stkItmclm','desc');" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down" style="color: #000;"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="tb-head stkbarclm">
                                        <div class="d-flex align-items-center w-20">
                                            <?php if (isset($stockUserFilterFields) && !in_array(10, $stockUserFilterFields)) { ?>
                                            <?php } else { ?> <p><?php echo showOtherLangText('Barcode'); ?></p>
                                                <span class="dblArrow">
                                                    <a onclick="sortTableByColumn('.newStockTask1', '.stkItmclm','asc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a onclick="sortTableByColumn('.newStockTask1', '.stkItmclm','desc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="tb-head w-55  stkbarclm">
                                        <div class="d-flex justify-content-en align-items-center">
                                            <?php if (isset($stockUserFilterFields) && !in_array(11, $stockUserFilterFields)) { ?>
                                            <?php } else { ?> <p><?php echo showOtherLangText('Unit.p'); ?></p>
                                                <span class="dblArrow">
                                                    <a onclick="sortTableByColumn('.newStockTask1', '.unit_p','asc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a onclick="sortTableByColumn('.newStockTask1', '.unit_p','desc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="tb-head w-55  stkQtyclm">
                                        <div class="d-flex justify-content-en align-items-center">
                                            <?php if (isset($stockUserFilterFields) && !in_array(12, $stockUserFilterFields)) { ?>
                                            <?php } else { ?><p><?php echo showOtherLangText('Factor'); ?></p>
                                                <span class="dblArrow">
                                                    <a onclick="sortTableByColumn('.newStockTask1', '.factor','asc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a onclick="sortTableByColumn('.newStockTask1', '.factor','desc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="tb-head w-55 stkQtyclm">
                                        <div class="d-flex justify-content-en align-items-center">
                                            <?php if (isset($stockUserFilterFields) && !in_array(13, $stockUserFilterFields)) { ?>
                                            <?php } else { ?><p><?php echo showOtherLangText('Unit.c'); ?></p>
                                                <span class="dblArrow">
                                                    <a onclick="sortTableByColumn('.newStockTask1', '.unit_c','asc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a onclick="sortTableByColumn('.newStockTask1', '.unit_c','desc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="stkPrcol d-flex align-items-center">
                                    <div class="tb-head tmp_qty tmp_qty lstPrcol">
                                        <div class="d-flex align-items-center">
                                            <?php if (isset($stockUserFilterFields) && !in_array(14, $stockUserFilterFields)) { ?>
                                            <?php } else { ?><p><?php echo showOtherLangText('Tmp qty'); ?></p>
                                                <span class="dblArrow">
                                                    <a onclick="sortTableByColumn('.newStockTask1', '.tmp_qty','asc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a onclick="sortTableByColumn('.newStockTask1', '.tmp_qty','desc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="tb-head lstPrcol">
                                        <div class="d-flex align-items-center">
                                            <?php if (isset($stockUserFilterFields) && !in_array(15, $stockUserFilterFields)) { ?>
                                            <?php } else { ?><p><?php echo showOtherLangText('Department'); ?></p>
                                                <span class="dblArrow">
                                                    <a onclick="sortTableByColumn('.newStockTask1', '.department','asc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a onclick="sortTableByColumn('.newStockTask1', '.department','desc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="tb-head lstPrcol">
                                        <div class="d-flex align-items-center">
                                            <?php if (isset($stockUserFilterFields) && !in_array(16, $stockUserFilterFields)) { ?>
                                            <?php } else { ?><p><?php echo showOtherLangText('Min Qty'); ?></p>
                                                <span class="dblArrow">
                                                    <a onclick="sortTableByColumn('.newStockTask1', '.min_qty','asc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                    <a onclick="sortTableByColumn('.newStockTask1', '.min_qty','desc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mbHde align-items-center supData-Head">
                                <div class="tb-head supStkclm">
                                    <div class="d-flex align-items-center">
                                        <?php if (isset($stockUserFilterFields) && !in_array(17, $stockUserFilterFields)) { ?>
                                        <?php } else { ?> <p><?php echo showOtherLangText('Max Qty'); ?></p>
                                            <span class="dblArrow">
                                                <a onclick="sortTableByColumn('.newStockTask1', '.max_qty','asc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                <a onclick="sortTableByColumn('.newStockTask1', '.max_qty','desc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="tb-head stk_val stk_val supStkclm">
                                    <div class="d-flex align-items-center">
                                        <?php if (isset($stockUserFilterFields) && !in_array(18, $stockUserFilterFields)) { ?>
                                        <?php } else { ?> <p><?php echo showOtherLangText('Stock Value'); ?></p>
                                            <span class="dblArrow">
                                                <a onclick="sortTableByColumn('.newStockTask1', '.stk_val','asc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-up"></i></a>
                                                <a onclick="sortTableByColumn('.newStockTask1', '.stk_val','desc',1);" href="javascript:void(0)" class="d-block aglStock"><i class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Item Table Head End -->
                    </div>
                    <!-- Item Table Body Start 1 -->
                    <div id="boxscroll " class="page1bdy" style="display: block;">
                        <div class="container cntTable cntTableData">
                            <?php

                            //get confirmed requsitions total qty of each productd
                            $productsConfirmedQtyArr = getConfirmTotalQtyReq($_SESSION['accountId']);
                            //end   get confirmed requsitions total qty of each productd

                            $x = 0;
                            while ($row = mysqli_fetch_array($stockMainQry)) {
                                $x++;

                                $totalProQty = isset($productsConfirmedQtyArr[$row['id']]) ? $productsConfirmedQtyArr[$row['id']] : 0;

                                if ($totalProQty > 0) {

                                    $totalTempProQty = $totalProQty;
                                } else {

                                    $totalTempProQty = 0;
                                }
                                $deptNames = $row['departs'];
                                $supNames = $row['suppls'];


                                $img = '';
                                if ($row['imgName'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/products/" . $row['imgName'])) {
                                    $img = '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/products/' . $row['imgName'] . '" class="imgItm">';
                                }

                                $catNames = ($row['childCatName'] == 'z' ? '' :  $row['childCatName']); ?>
                                <div class="newStockTask">
                                    <div class="d-flex align-items-center border-bottom itmBody">
                                        <div class="mbShw d-flex align-items-center">
                                            <div class="tb-bdy counter__col">
                                                   <?php echo $x; ?>
                                                </div>
                                            <?php if (isset($stockUserFilterFields) && !in_array(1, $stockUserFilterFields)) { ?>
                                            <?php } else { ?>
                                                <div class="tb-bdy stkImgcol">
                                                    <?php echo $img; ?>
                                                </div>
                                            <?php } ?>

                                            <div class="stkNamcol d-md-flex align-items-center">
                                                <div class="tb-bdy stkItmclm">
                                                    <?php if (isset($stockUserFilterFields) && !in_array(2, $stockUserFilterFields)) { ?>
                                                    <?php } else { ?>
                                                        <a href="itemHistoryView.php?id=<?php echo $row['id']; ?>" class="itm-Profile"><?php echo $row['itemName']; ?></a>
                                                    <?php } ?>
                                                </div>
                                                <div class="tb-bdy stkQtyclm stkQtybdy">
                                                    <span class="d-md-none small-font" >Qty:</span>
                                                    <?php if (isset($stockUserFilterFields) && !in_array(3, $stockUserFilterFields)) { ?>
                                                    <?php } else { ?>
                                                        <span><?php echo $row['stockQty']; ?></span>
                                                    <?php } ?>
                                                </div>
                                                <div class="tb-bdy stkQtyclm stkreqbdy">
                                                    <span class="d-md-none small-font">Req qty:</span>
                                                    <?php if (isset($stockUserFilterFields) && !in_array(5, $stockUserFilterFields)) { ?>
                                                    <?php } else { ?>
                                                        <span><?php echo $totalTempProQty; ?></span>
                                                    <?php } ?>
                                                </div>
                                                <div class="tb-bdy stkQtyclm stkavabdy">
                                                    <span class="d-md-none small-font"><?php echo showOtherLangText('Avail Qty'); ?>:</span>
                                                    <?php if (isset($stockUserFilterFields) && !in_array(4, $stockUserFilterFields)) { ?>
                                                    <?php } else { ?>
                                                        <span><?php echo ($row['stockQty'] - $totalTempProQty); ?></span>
                                                    <?php } ?>
                                                </div>

                                            </div>
                                            <div class="stkPrcol d-md-flex align-items-center">
                                                <div class="tb-bdy lstPrcol stkPrcbdy mb-Last">
                                                    <?php if (isset($stockUserFilterFields) && !in_array(6, $stockUserFilterFields)) { ?>
                                                    <?php } else { ?> <p><span class="mbLst-value">Last</span><?php echo getPrice($row['stockLastPrice']) . ' ' . $getDefCurDet['curCode']; ?>
                                                        </p>
                                                    <?php } ?>
                                                </div>

                                                <div class="tb-bdy lstPrcol stkPrcbdy mb-Stock">
                                                    <?php if (isset($stockUserFilterFields) && !in_array(7, $stockUserFilterFields)) { ?>
                                                    <?php } else { ?>
                                                        <p><span class="mbLst-value">Stock</span><?php echo getPrice($row['sPrice']) . ' ' . $getDefCurDet['curCode']; ?>
                                                        </p>
                                                    <?php } ?>
                                                </div>

                                                <div class="tb-bdy lstPrcol stkPrcbdy mb-Last">
                                                    <?php if (isset($stockUserFilterFields) && !in_array(18, $stockUserFilterFields)) { ?>
                                                    <?php } else { ?>
                                                        <p><?php echo getPrice($row['stockValue']) . ' ' . $getDefCurDet['curCode']; ?>
                                                        </p>
                                                    <?php } ?>
                                                </div>

                                            </div>

                                          
                                        </div>
                                        <div class="mbHde align-items-center suppData-stk">
                                            <div class="tb-bdy supStkclm">
                                                <?php if (isset($stockUserFilterFields) && !in_array(8, $stockUserFilterFields)) { ?>
                                                <?php } else { ?>

                                                    <p><?php echo $catNames; ?></p>

                                                <?php } ?>
                                            </div>
                                            <div class="tb-bdy supStkclm">
                                                <?php if (isset($stockUserFilterFields) && !in_array(9, $stockUserFilterFields)) { ?>
                                                <?php } else { ?>

                                                    <p><?php echo $supNames; ?></p>

                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- Item Table Body End -->
                    <!-- Item Table Body Start -->
                    <div id="boxscroll" class="page2bdy" style="display: none; margin-top:0px;">
                        <div class="container cntTable cntTableData1">

                            <?php
                            //get confirmed requsitions total qty of each productd
                            $productsConfirmedQtyArr = getConfirmTotalQtyReq($_SESSION['accountId']);
                            //end   get confirmed requsitions total qty of each productd

                            $x = 0;
                            while ($row = mysqli_fetch_array($stockMainQry_hide)) {
                                $x++;

                                $totalProQty = isset($productsConfirmedQtyArr[$row['id']]) ? $productsConfirmedQtyArr[$row['id']] : 0;

                                if ($totalProQty > 0) {

                                    $totalTempProQty = $totalProQty;
                                } else {

                                    $totalTempProQty = 0;
                                }
                                $deptNames = $row['departs'];
                                $supNames = $row['suppls'];


                                $catNames = ($row['childCatName'] == 'z' ? '' :  $row['childCatName']);

                                $img = '';
                                if ($row['imgName'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/products/" . $row['imgName'])) {
                                    $img = '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/products/' . $row['imgName'] . '" class="imgItm">';
                                }

                            ?>
                                <div class="newStockTask1">
                                    <div class="d-flex align-items-center border-bottom itmBody">
                                        <div class="mbShw d-flex align-items-center">
                                            <div class="tb-bdy counter__col"><?php echo $x; ?></div>
                                            <?php if (isset($stockUserFilterFields) && !in_array(1, $stockUserFilterFields)) { ?>
                                            <?php } else { ?>
                                                <div class="tb-bdy stkImgcol">
                                                    <?php echo $img; ?>
                                                </div>
                                            <?php } ?>
                                            <!-- <div class="tb-bdy stkImgcol">
                                            <?php //echo $row['barCode']; 
                                            ?>
                                        </div> -->
                                            <div class="stkNamcol d-flex align-items-center">
                                                <div class="tb-bdy">
                                                    <?php if (isset($stockUserFilterFields) && !in_array(2, $stockUserFilterFields)) { ?>
                                                    <?php } else { ?>
                                                        <a href="itemHistoryView.php?id=<?php echo $row['id']; ?>" class="itm-Profile"><?php echo $row['itemName']; ?></a>
                                                    <?php } ?>
                                                </div>
                                                <div class="tb-bdy stkItmclm">
                                                    <?php if (isset($stockUserFilterFields) && !in_array(10, $stockUserFilterFields)) { ?>
                                                    <?php } else { ?>
                                                        <?php echo $row['barCode']; ?>
                                                    <?php } ?>
                                                </div>
                                                <div class="tb-bdy stkQtyclm stkQtybdy unit_p">
                                                    <?php if (isset($stockUserFilterFields) && !in_array(11, $stockUserFilterFields)) { ?>
                                                    <?php } else { ?>
                                                        <p><?php echo $row['purchaseUnit']; ?></p>
                                                    <?php } ?>
                                                </div>
                                                <div class="factor tb-bdy stkQtyclm stkQtybdy">
                                                    <?php if (isset($stockUserFilterFields) && !in_array(12, $stockUserFilterFields)) { ?>
                                                    <?php } else { ?>
                                                        <p><?php echo $row['factor']; ?></p>
                                                    <?php } ?>
                                                </div>
                                                <div class="unit_c tb-bdy stkQtyclm stkQtybdy">
                                                    <?php if (isset($stockUserFilterFields) && !in_array(13, $stockUserFilterFields)) { ?>
                                                    <?php } else { ?>
                                                        <p><?php echo $row['countingUnit']; ?></p>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="stkPrcol stkPrcol11 d-flex align-items-center">
                                                <div class="tmp_qty tb-bdy lstPrco stkPrcbdy mb-Last md__33">
                                                    <?php if (isset($stockUserFilterFields) && !in_array(14, $stockUserFilterFields)) { ?>
                                                    <?php } else { ?>
                                                        <p>
                                                        <span class="mbLst-value">Last</span> 
                                                        <?php echo getPrice($row['stockLastPrice']) . ' ' . $getDefCurDet['curCode']; ?>
                                                        </p>
                                                    <?php } ?>
                                                </div>
                                                <div class="department tb-bdy lstPrco stkPrcbdy mb-Stock md__33">
                                                    <?php if (isset($stockUserFilterFields) && !in_array(15, $stockUserFilterFields)) { ?>
                                                    <?php } else { ?>
                                                        <p><span class="mbLst-value">Stock</span><?php echo $deptNames; ?></p>
                                                    <?php } ?>
                                                </div>
                                                <div class="min_qty tb-bdy lstPrco stkPrcbdy mb-Value md__33">
                                                    <?php if (isset($stockUserFilterFields) && !in_array(16, $stockUserFilterFields)) { ?>
                                                    <?php } else { ?>
                                                        <p><span class="mbLst-value">Value</span><?php echo $row['minLevel']; ?>
                                                        </p>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mbHde align-items-center suppData-stk">
                                            <div class="max_qty tb-bdy supStkclm">
                                                <?php if (isset($stockUserFilterFields) && !in_array(17, $stockUserFilterFields)) { ?>
                                                <?php } else { ?>
                                                    <p><?php echo $row['maxLevel']; ?></p>
                                                <?php } ?>
                                            </div>
                                            <div class="stk_val tb-bdy supStkclm">
                                                <?php if (isset($stockUserFilterFields) && !in_array(18, $stockUserFilterFields)) { ?>
                                                <?php } else { ?>
                                                    <p><?php echo getPrice($row['stockValue']) . ' ' . $getDefCurDet['curCode']; ?>
                                                    </p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- Item Table Body End -->
                </section>

            </div>
        </div>
    </div>



    <!-- ===== Stock view pdf popup new in div format======= -->
    <div class="modal" tabindex="-1" id="stock_pdf" aria-labelledby="stock_pdfModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-m site-modal" style="max-width:1000px;">
            <div id="stock_details" class="modal-content p-2">


            </div>
        </div>
    </div>
    <?php
    $colsArr = [
        1 => ['lable' => '' . showOtherLangText('Photo') . ''],
        2 => ['lable' => '' . showOtherLangText('Item') . ''],
        3 => ['lable' => '' . showOtherLangText('Qty') . ''],
        4 => ['lable' => '' . showOtherLangText('Available Quantity') . ''],
        5 => ['lable' => '' . showOtherLangText('Request Quantity') . ''],
        6 => ['lable' => '' . showOtherLangText('Last Price') . ''],
        7 => ['lable' => '' . showOtherLangText('Stock Price') . ''],
        8 => ['lable' => '' . showOtherLangText('Sub-Category') . ''],
        9 => ['lable' => '' . showOtherLangText('Supplier') . ''],

        10 => ['lable' => '' . showOtherLangText('Barcode') . ''],
        11 => ['lable' => '' . showOtherLangText('Unit.p') . ''],
        12 => ['lable' => '' . showOtherLangText('Factor') . ''],
        13 => ['lable' => '' . showOtherLangText('Unit.c') . ''],
        14 => ['lable' => '' . showOtherLangText('Tmp qty') . ''],
        15 => ['lable' => '' . showOtherLangText('Department') . ''],
        16 => ['lable' => '' . showOtherLangText('Min Qty') . ''],
        17 => ['lable' => '' . showOtherLangText('Max Qty') . ''],
        18 => ['lable' => '' . showOtherLangText('Stock Value') . ''],
    ];
    ?>

    <div class="modal addUser-Form glbFrm-Cont" tabindex="-1" id="qty_in_stock" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">

            <div id="qty_in_stock_ajax_content" class="modal-content">


            </div>
        </div>
    </div>


    <div id="myModal" class="modal" style=" align-items:center">
        <div style="width:750px; padding:0;" class="container mnPdf_Dv">
            <!-- Modal content -->
            <div class="modal-content" style="overflow-x:hidden;">

                <div>

                    <div class="mdlHead-Popup" style="display: flex; align-items:center; justify-content:space-between; ">
                        <span><strong><?php echo showOtherLangText('Check columns to show in list') ?>:</strong></span>
                        <span class="close" style="font-size: 2rem; cursor:pointer ">×</span>
                    </div>

                    <form method="post" action="" class="shortList-ChkAll">

                        <strong class="checkAllSectionBox" style="max-width: 750px;">
                            <input type="checkbox" class="CheckAllOptions" id="CheckAllOptions">
                            <label>
                                <?php echo showOtherLangText('Check All') ?>
                            </label>
                        </strong>

                        <br>

                        <!-- <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="1">&nbsp;Photo<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="2">&nbsp;Item<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="3">&nbsp;Quantity<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="4">&nbsp;Available Quantity<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="5">&nbsp;Request Quantity<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="6">&nbsp;Last Price<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="7">&nbsp;Stock Price<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="8">&nbsp;Sub-Category<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="8">&nbsp;Supplier<br>
                                                <hr style="margin: .5rem -2rem .5rem -2rem;">
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="9">&nbsp;Barcode<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="10">&nbsp;Unit.p<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="11">&nbsp;Factor<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="12">&nbsp;Unit.c<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="13">&nbsp;Tmp qty<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="14">&nbsp;Department<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="15">&nbsp;Min Qty<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="16">&nbsp;Max Qty<br>
                                                <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" checked="checked" value="17">&nbsp;Stock Value<br> -->
                        <?php foreach ($colsArr as $key => $colArr) {

                            if (isset($stockUserFilterFields)) {
                                $sel =  in_array($key, $stockUserFilterFields) ? ' checked="checked" ' : '';
                            }

                        ?>
                            <input type="checkbox" id="optionCheck" class="optionCheck" name="showFieldsStock[]" <?php echo $sel; ?> value="<?php echo $key; ?>">&nbsp;<?php echo $colArr['lable']; ?><br>
                            <?php if ($key == 9) { ?>
                                <hr style="margin: .5rem -2rem .5rem -2rem;">
                            <?php } ?>
                        <?php } ?>
                        <p style="display: flex; gap:.5rem;" class="mt-3">
                            <button type="submit" name="btnSubmit" style="background-color: #7a89fe; border-radius: 10px; border: none; color:white; " class="btn btn-primary gray-btn"><?php echo showOtherLangText('Show'); ?></button>&nbsp;
                            <button type="button" name="btnSubmit" style="background-color: #7a89fe; border-radius: 10px; border: none; color:white; " class="btn btn-primary gray-btn" onclick="window.location.href='stockView.php?clearshowFieldsStock=1'"><?php echo showOtherLangText('Clear Filter'); ?></button>
                        </p>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal addUser-Form row glbFrm-Cont" id="convert_raw_items" tabindex="-1" aria-labelledby="issueout2label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:950px;">
            <div class="modal-content">
                <form method="post" id="frmconvert" name="frmconvert" action="convertRawItemsConfirmSubmit.php">

                    <div class="modal-body  fs-15">
                        <div class="pb-3">
                            <h2>Convert raw item</h2> 
                        </div>
                        <div class="table-responsive"> 
                            <table class="issueout2-table w-100 fs-13" style="min-width:700px;">
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
    
                                        if ($rawItemRow['imgName'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/products/" . $rawItemRow['imgName'])) {
                                            echo '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/products/' . $rawItemRow['imgName'] . '" width="60" height="60">';
                                        }
                                        ?></td>
                                    <td><?php echo $rawItemRow['itemName'] !== '' ? $rawItemRow['itemName'] : ''; ?></td>
                                    <td><?php echo $_POST['qtyToConvert']; ?></td>
                                    <td><?php echo $rawItemRow['unitC']; ?></td>
    
                                    <td><?php echo $rawItemRow['stockQty']; ?></td>
    
                                    <td><?php echo ($rawItemRow['stockQty'] - $_POST['qtyToConvert']); ?></td>
    
                                    <td><?php
                                        if ($rawItemRow['price'] !== '') {
                                            echo showPrice($rawItemRow['price'], $getDefCurDet['curCode']);
                                        } ?>
                                    </td>
                                    <td><?php showPrice($_POST['qtyToConvert'] * $rawItemRow['price'], $getDefCurDet['curCode']) ?>
                                    </td>
                                </tr>
                                <tr class="semibold">
                                    <td><?php
                                        if ($convertItemRow['imgName'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/products/" . $convertItemRow['imgName'])) {
                                            echo '<img width="60" height="60" src="' . $siteUrl . 'uploads/' . $accountImgPath . '/products/' . $convertItemRow['imgName'] . '" >';
                                        }
                                        ?></td>
                                    <td><?php echo $convertItemRow['itemName']; ?></td>
                                    <td><?php echo $_POST['convertedQty']; ?></td>
                                    <td><?php echo $convertItemRow['unitC']; ?></td>
    
                                    <td><?php echo $convertItemRow['stockQty'] > 0 ? $convertItemRow['stockQty'] : 0; ?></td>
    
                                    <td><?php echo $convertItemRow['stockQty'] + $_POST['convertedQty']; ?></td>
    
                                    <td><?php echo showPrice($_POST['unitPrice'], $getDefCurDet['curCode']); ?></td>
                                    <td><?php echo showPrice($_POST['qtyToConvert'] * $rawItemRow['price'], $getDefCurDet['curCode']); ?>
                                    </td>
                                </tr>
    
    
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-start">
                        <button type="button" class="btn btn-primary" onClick="approveConvert();">Approve</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- ===== Stock pdf popup new in div format======= -->

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
    <script>
        $(function() {

            <?php if (isset($_GET['allstore'])) { ?>
                // $(".allStore").css("display", "flex");
                // $(".storeCol").css("background", "#7A89FE");
                // $(".dskAll-str").hide();

            <?php } ?>
            <?php if (isset($_GET['convertRawItem'])) { ?>
                // history.pushState(null, "", location.href.split("?")[0]);

            <?php } ?>
            <?php if ($convertrawitems == true) { ?>
                $('#convert_raw_items').modal('show');
            <?php } ?>
            $(".subcat_opt").on("click", "a", function(e) {
                var $this = $(this).parent();
                $("#subcatText").text($this.data("value"));
                $("#subCatId").val($this.data("id"));
                $('#frm').submit();
            });

            $(".supplier_opt").on("click", "a", function(e) {
                var $this = $(this).parent();
                $("#SupplierText").text($this.data("value"));
                $("#suppId").val($this.data("id"));
                $('#frm').submit();
            });

            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('subCatId')) {
                var subCatId = urlParams.get('subCatId');
                if (subCatId !== '') {
                    $("#subcatText").text($(".subcat_opt .selected").text());
                }
            }
            if (urlParams.has('suppId')) {
                var suppId = urlParams.get('suppId');
                if (suppId !== '') {
                    $("#SupplierText").text($(".supplier_opt .selected").text());
                }
            }

        });

        function myFunction() {
            // Declare variables
            var input = document.getElementById("search");
            var filter = input.value.toLowerCase();

            if ($(".actvSale-Cst")[0]) {
                var nodes = document.querySelectorAll('.newStockTask1');
            } else {
                var nodes = document.querySelectorAll('.newStockTask');
            }

            for (i = 0; i < nodes.length; i++) {
                if (nodes[i].innerText.toLowerCase().includes(filter)) {
                    nodes[i].style.setProperty("display", "block", "important");
                } else {
                    nodes[i].style.setProperty("display", "none", "important");
                }
            }


        }
        $("#search").on("search", function(evt) {
            if ($(this).val().length == 0) {
                resetData();
            }
        });

        function resetData() {

            $('#search').val('');
            myFunction();
        }

        function showLightboxStockPdf() {


            var storeVal = $("#storeName").val();
            if (storeVal == 0) {
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



        function sortTableByColumn(table, field, order, displayArea) {
            sortElementsByText(table, field, order, displayArea);
        }

        function sortElementsByText(container, textElement, order, displayArea) {
            var elements = $(container).get();

            elements.sort(function(a, b) {
                var textA = $(a).find(textElement).text().trim();
                var textB = $(b).find(textElement).text().trim();
                // Check if textA and textB are numbers
                var isNumA = !isNaN(parseFloat(textA)) && isFinite(textA);
                var isNumB = !isNaN(parseFloat(textB)) && isFinite(textB);

                if (isNumA && isNumB) {
                    // If both are numbers, compare them as numbers
                    return order === 'asc' ? parseFloat(textA) - parseFloat(textB) : parseFloat(textB) - parseFloat(
                        textA);
                } else {
                    // Otherwise, compare them as strings
                    return order === 'asc' ? textA.localeCompare(textB) : textB.localeCompare(textA);
                }
            });

            $.each(elements, function(index, element) {
                if (displayArea == 1) {
                    $('.cntTableData1').append(element);
                } else {
                    $('.cntTableData').append(element);
                }
            });
        }

        function showHideByClass(targetId) {

            if ($('.' + targetId).is(":visible")) {
                $('.' + targetId).css('display', 'none');

            } else {
                if (targetId == 'item2' || targetId == 'item3' || targetId == 'item4' || targetId == 'item5' || targetId ==
                    'item6' || targetId == 'item7' || targetId == 'item8' || targetId == 'item9' || targetId == 'item10' ||
                    targetId == 'item11' || targetId == 'item12') {
                    $('.itemSectionPartMain').css('display', 'block');
                }
                if (targetId == 'store-name' || targetId == 'store-totalPrice' || targetId == 'store-stockTakeCount') {
                    $('.summarySectionPartMain').css('display', 'block');
                }
                $('.' + targetId).css('display', 'block');
            }

            if (targetId == 'item3' || targetId == 'item4') {

                if (!$('.item3').is(":visible") || !$('.item4').is(":visible")) {

                    $('.slashItem').css('display', 'none');

                } else if ($('.item3').is(":visible") && $('.item4').is(":visible")) {
                    $('.slashItem').css('display', 'block');
                }
            }



        }

        $('body').on('click', '.headChk-All', function() {


            if ($(".headChk-All:checked").length == 1) {
                $("#header").prop('checked', true);
                $(".header-address").prop('checked', true);
                $(".header-logo").prop('checked', true);

                $('.address-section').css('display', 'block');
                $('.logo-section').css('display', 'block');

            } else {
                $("#header").prop('checked', false);
                $(".header-address").prop('checked', false);
                $(".header-logo").prop('checked', false);


                $('.address-section').css('display', 'none');
                $('.logo-section').css('display', 'none');

            }

        });

        $('body').on('click', '.smryChk-All', function() {


            if ($(".smryChk-All:checked").length == 1) {
                //$("#summary").prop('checked', true);

                $('.summaryPart').show();
                $(".smryCheckbox").prop('checked', true);

            } else {
                $(".smryCheckbox").prop('checked', false);
                $('.summaryPart').hide();
                //$("#summary").prop('checked', false);

            }

        });

        $('body').on('click', '.itemChk-All', function() {


            if ($(".itemChk-All:checked").length == 1) {
                $("#itemTable").prop('checked', true);
                $('.itemSectionPart').css('display', 'block');
                $(".itmTblCheckbox").prop('checked', true);

            } else {
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

        function approveConvert() {
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
        document.getElementById('toggle-page-btn').addEventListener('click', function() {
            var page1head = document.getElementById('page1head');
            var page2head = document.getElementById('page2head');
            var page1bdy = document.querySelector('.page1bdy');
            var page2bdy = document.querySelector('.page2bdy');

            if (page1head.style.display === 'none') {
                page1head.style.display = 'block';
                page2head.style.display = 'none';
                page1bdy.style.display = 'block';
                page2bdy.style.display = 'none';
            } else {
                page1head.style.display = 'none';
                page2head.style.display = 'block';
                page1bdy.style.display = 'none';
                page2bdy.style.display = 'block';
            }
        });
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


            //show/hide extra details on info
            $(".cstBtn-Sale").on("click", function () 
            {       
                if ($(".hideBtn-Info").hasClass("actvSale-Cst")) 
                {
                    $(".tmp_qty").hide();
                    $(".stk_val").hide();
                    
                } else 
                {
                    $(".tmp_qty").show();
                    $(".stk_val").show();
                }
                 
            });//end show/hide extra details

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

        if (location.href.includes('?convertRawItem')) {
            history.pushState({}, null, location.href.split('?')[0]);
        }
        if (location.href.includes('?stockTake')) {
            history.pushState({}, null, location.href.split('?')[0]);
        }
    </script>

    
</body>

</html>