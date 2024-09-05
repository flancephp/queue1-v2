<?php 
include('inc/dbConfig.php'); //connection details


//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$sql = "SELECT p.*, pc.name parentCatName, IFNULL(c.name, 'z') childCatName, s.qty stockQty, s.stockValue, IF(uc.name!='', uc.name,p.unitC) countingUnit, IF(up.name!='', up.name,p.unitC) purchaseUnit
FROM tbl_products p
LEFT JOIN tbl_units uc 
    ON(uc.id = p.unitC) AND uc.account_id=p.account_Id
LEFT JOIN tbl_units up 
    ON(up.id = p.unitP) AND up.account_id=p.account_Id
LEFT JOIN tbl_category pc 
    ON(pc.id = p.parentCatId)
LEFT JOIN tbl_category c 
    ON(c.id = p.catId) 
LEFT JOIN tbl_stocks s 
    ON(s.pId = p.id) 
WHERE p.id = '".$_GET['id']."'  AND p.account_id = '".$_SESSION['accountId']."'  GROUP BY p.id  ";
$result = mysqli_query($con, $sql);
$res = mysqli_fetch_array($result);


$resItemHistory = getItemHistory($_GET['id'], $_GET);

$typeArr = [
    1 => ['lable' => ''.showOtherLangText('Issued In').'', 'style'=> 'color:Green !important;font-weight:bold;' ],
    2 => ['lable' => ''.showOtherLangText('Issued Out').'', 'style'=> 'color:red !important;font-weight:bold;' ],
    3 => ['lable' => ''.showOtherLangText('Stock Take').'', 'style'=> 'color:blue !important;font-weight:bold;' ],
    4 => ['lable' => ''.showOtherLangText('Raw Item Convert').'', 'style'=> 'color:Maroon !important;font-weight:bold;' ],

];



$typeOptions =  '<ul class="dropdown-menu ordertype" >';
$typeOptions .= '<li><a class="dropdown-item stockTake-pr" href="itemHistoryView.php?id='.$_GET['id'].'">View All</a></li>';

													foreach($typeArr as $typeKey => $typeVal)
													{

                                                        $style = isset($typeVal['style']) ? $typeVal['style'] : '';

                                                        $sel = isset($_GET['ordType']) && $_GET['ordType'] == $typeKey  ? 'selected' : '';

                                                        $typeOptions .= '<li data-id="'.$typeKey.'" data-value="'.$typeKey.'"><a class="dropdown-item '.$sel.'" style="'.$style.'"   href="javascript:void(0)">'.$typeVal['lable'].'</a>
                                                        </li>';
													}
													$typeOptions .= '</ul>';


$sqlQry = " SELECT s.*, od.pId AS pId FROM  tbl_suppliers s 
                                                    INNER JOIN tbl_orders o ON (s.id=o.supplierId)
                                                    INNER JOIN tbl_order_details od ON (o.id=od.ordId)

                                                    WHERE o.status = '2' AND pId = '".$_GET['id']."' ".$resItemHistory['cond']." AND s.account_id = '".$_SESSION['accountId']."' GROUP BY pId ORDER BY s.name ";
                                                    $resultStore = mysqli_query($con, $sqlQry);

$suppMemStoreOptions = '<ul class="dropdown-menu referto" >';
$suppMemStoreOptions .= '<li><a class="dropdown-item stockTake-pr" href="itemHistoryView.php?id='.$_GET['id'].'">View All</a></li>';

                                while($suppRow = mysqli_fetch_array($resultStore))
                                                    {

                                                        $sel = isset($_GET['suppMemStoreId']) && $_GET['suppMemStoreId'] == 'suppId_' . $suppRow['id'] ? 'selected' : '';

                                                     
                                                        $suppMemStoreOptions .= '<li data-id="suppId_'.$suppRow['id'].'" data-value="'.trim($suppRow['name']).'"><a class="dropdown-item isuIn-grReq '.$sel.'"   href="javascript:void(0)">'.trim($suppRow['name']).'</a>
                                                          </li>';
                                                    }
    $sqlSet = " SELECT du.*, od.pId AS pId FROM  tbl_deptusers du
                                                    INNER JOIN tbl_orders o ON (du.id=o.recMemberId)
                                                    INNER JOIN tbl_order_details od ON (o.id=od.ordId)
                                                    WHERE o.status = '2' AND pId = '".$_GET['id']."' ".$resItemHistory['cond']." AND du.account_id = '".$_SESSION['accountId']."' GROUP BY pId ORDER BY du.name ";
                                                    $resultSet = mysqli_query($con, $sqlSet);

                                                    while($deptUserRow = mysqli_fetch_array($resultSet) )
                                                    {
                                           $sel = isset($_GET['suppMemStoreId']) && $_GET['suppMemStoreId'] == 'deptUserId_' . $deptUserRow['id'] ? 'selected' : '';

                                                $suppMemStoreOptions .= '<li data-id="deptUserId_'.$deptUserRow['id'].'" data-value="'.trim($deptUserRow['name']).'" ><a class="dropdown-item isuOut-rdSup '.$sel.'" href="javascript:void(0)">'.trim($deptUserRow['name']).'</a></li>';
                                                    }

                                                    // for store type

                                                    $sqlSet = " SELECT st.*, od.pId AS pId FROM  tbl_stores st
                                                    INNER JOIN tbl_orders o ON (st.id=o.storeId)
                                                    INNER JOIN tbl_order_details od ON (o.id=od.ordId)
                                                    WHERE o.status = '2' AND pId = '".$_GET['id']."' ".$resItemHistory['cond']."  AND st.account_id = '".$_SESSION['accountId']."' GROUP BY pId ORDER BY st.name ";
                                                    $resultSet = mysqli_query($con, $sqlSet);


                                                    while($storeRow = mysqli_fetch_array($resultSet) )
                                                    {
                                               $sel = isset($_GET['suppMemStoreId']) && $_GET['suppMemStoreId'] == 'storeId_' . $storeRow['id'] ? 'selected' : '';

                                        $suppMemStoreOptions .= '<li data-id="storeId_'.$storeRow['id'].'" data-value="'.trim($storeRow['name']).'" ><a class="dropdown-item stockTake-pr '.$sel.'" href="javascript:void(0)">'.trim($storeRow['name']).'</a></li>';
                                                    }

                                                $suppMemStoreOptions .= '</ul>';

$plusvariance = $minusvariance = '';
$plusqtytot =  $minusqtytot = '';
foreach($resItemHistory['resRows'] as $item){
   // $variancesQtyTot = '';$amt = '';
           if($showType == 0)
            {
                 $variancesQtyTot = $item['qtyReceived']-$item['qty'];

                 $amt = $variancesQtyTot*$item['stockPrice'];
                if($variancesQtyTot>0)
                {
                $plusqtytot += $variancesQtyTot;
                } else {
                $minusqtytot += $variancesQtyTot;
                }

                if($amt>0)
                {
                    $plusvariance += $amt;
                } else {
                    $minusvariance += $amt;
                }
            }
           
           
}


?><!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Item Profile - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/style1.css">
    <style type="text/css">
    .isuIn-mrReq {color: #800000 !important;}
    .itm-profileDtl {height: fit-content;}
    .itmVw-qtyClm, .itmVw-qtybdClm {width: 10%;}
    .dt-Member, .tbdy-dtMbr {width: 40%;}
    .isu-Varance, .tbdy-isuVar {width: 30%;}
    .last-Stock, .tbdy-lstStk {width: 20%;}
    .dt-Member >div ,
    .tbdy-dtMbr >div {
        width: 33.33% !important;
    }
    </style>
    <!-- Links for datePicker and dialog popup -->

</head>

<body>

    <div class="container-fluid newOrder">
        <div class="row">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                 <?php require_once('nav.php');?>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('Item Profile'); ?></h1>
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
                                    <h1 class="h1">Item Profile</h1>
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
                 
              
                <section class="itmhstDetail">

                    <section class="itm-profileDtl">
                        <div class="btnBg">
                            <a href="stockView.php" class="btn btn-primary">Back</a>
                        </div>
                        <div class="itemImage">
                            <div class="itemImage-bg">
                                <?php 
                                
                                                                if( $res['imgName'] != '' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$res['imgName'] ) )
                                                             {  
                                                                echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$res['imgName'].'" >';
                                                             }
                                                             else
                                                             {
                                                                echo '<img src="dist/img/barCode.png" class="itm-brcode">';
                                                             }
                                                         
                                                            ?>
                            </div>
                        </div>

                        <div class="view-tblHead d-flex align-items-center">
                            <div class="vw-clm-8">
                                <p class="productName"><?php echo $res['itemName'];?></p>
                            </div>
                            <div class="vw-clm-4">
                                <p class="productQty"><?php echo $res['stockQty'];?><?php echo ' '.$res['countingUnit'];?></p>
                            </div>
                        </div>

                        <div class="view-tblBody d-flex align-items-center mt-2">
                            <div class="vw-clm-8">
                                <p class="vwBdy-ttl"><?php echo showOtherLangText('Bar Code'); ?></p>
                                <p class="vwBdy-ttl"><?php echo showOtherLangText('Last Purchase Price'); ?></p>
                                <p class="vwBdy-ttl"><?php echo showOtherLangText('Stock Price'); ?></p>
                            </div>
                            <div class="vw-clm-4">
                                <p class="viewBarcode"><?php echo $res['barCode'];?></p>
                                <p class="viewLst-prc"><?php showPrice($res['price'],$getDefCurDet['curCode']);?></p>
                                <p class="viewStock-prc"><?php showPrice($res['stockPrice'],$getDefCurDet['curCode']);?></p>
                            </div>
                        </div>

                        <div class="view-tblBody d-flex align-items-center mt-3">
                            <div class="vw-clm-8">
                                <p class="vwBdy-ttl"><?php echo showOtherLangText('Category'); ?></p>
                                <p class="vwBdy-ttl"><?php echo showOtherLangText('Sub Category'); ?></p>
                                <p class="vwBdy-ttl"><?php echo showOtherLangText('Department'); ?></p>
                                <p class="vwBdy-ttl"><?php echo showOtherLangText('Supplier'); ?></p>
                            </div>
                            <div class="vw-clm-4">
                                <p class="viewCategory"><?php echo $res['parentCatName'];?></p>
                                <p class="viewSub-category"><?php echo $res['childCatName'];?></p>
                                <p class="viewDepartment"><?php echo  getProdcutDepartments($res['id']);?></p>
                                <p class="viewSupplier"><?php echo getProdcutSuppls($res['id']);?></p>
                            </div>
                        </div>

                        <div class="itmUnit-tbl">
                            <div class="heading-Unt d-flex align-items-center">
                                <div class="prhead-Unit">
                                    <p><?php echo showOtherLangText('Purchase Unit'); ?></p>
                                </div>
                                <div class="arhead-Unit">
                                    <p>@</p>
                                </div>
                                <div class="prhead-Unit">
                                    <p><?php echo showOtherLangText('Counting Unit'); ?></p>
                                </div>
                            </div>

                            <div class="body-Unt d-flex align-items-center">
                                <div class="prhead-Unit">
                                    <p class="purchase-Unit"><?php echo $res['purchaseUnit'];?></p>
                                </div>
                                <div class="arhead-Unit">
                                    <p class="num-Unit"><?php echo $res['factor'];?></p>
                                </div>
                                <div class="prhead-Unit">
                                    <p class="count-Unit"><?php echo $res['countingUnit'];?></p>
                                </div>
                            </div>
                        </div>

                    </section>

                    <section class="ordDetail itm-viewDtl">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-8 itmVw-fetBtnClm">
                                    <div class="hstCal">
                                        <div class="his-featBtn">
                                            <div class="cal-Ender">
                                                <a href="javascript:void(0)">
                                                    <i class="fa-regular fa-calendar"></i>
                                                </a>
                                            </div>
                                            <!-- Filter Btn Start -->
                                            <div class="his-filtBtn">
                                                <a href="javascript:void(0)" class="head-Filter itmVw-Filter">
                                                    <img src="Assets/icons/filter.svg" alt="Filter">
                                                </a>
                                            </div>
                                            <!-- Filter Btn End -->
                                        </div>
                                        <!-- Date Box Start -->
                                        <form name="frm" id="frm" method="get" action="">
                                        <input type="hidden" name="id" id="id" value="<?php echo $_GET['id'];?>" />
                                        <div class="prtDate">
                                            <div class="hstDate">
                                                <input type="text" size="10" class="datepicker" placeholder="15/01/2023"
                                                    name="fromDate" id="fromDate" autocomplete="off" value="<?php echo $_GET['fromDate'];?>">
                                                <span>-</span>
                                                <input type="text" size="10" class="datepicker" placeholder="15/02/2023" name="toDate" id="toDate" autocomplete="off" value="<?php echo $_GET['toDate'];?>">
                                            </div>
                                            <div class="reloadBtn">
                                                <a onClick="document.frm.submit();" href="javascript:void(0)"><i
                                                        class="fa-solid fa-arrows-rotate"></i></a>
                                            </div>
                                            <div class="reloadBtn">
                                                <a onClick="window.location.href='itemHistoryView.php?id=<?php echo $_GET['id']; ?>'" href="javascript:void(0)"><i class="fa-solid fa-xmark"></i></a>
                                            </div>
                                        </div>
                                        </form>
                                        <!-- Date Box End -->
                                    </div>

                                </div>
                                <!--<div class="col-md-4 expStrdt d-flex justify-content-end align-items-end">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div class="chkStore">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/history-stock.svg" alt="history stock">
                                            </a>
                                        </div>
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
                                        <div class="chkStore itmVw-chkClm">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/chkColumn.svg" alt="Check Column">
                                            </a>
                                        </div>
                                    </div>
                                </div>-->
                            </div>
                        </div>
                        <!-- Mobile Date Box Start -->
                        <div class="container mb-hisDate">
                            <div class="date-flx"></div>
                        </div>
                        <!-- Mobile Date Box End -->

                        <div class="container mb-hisDtl tb-itmVwdtl">
                            <div class="row">
                                <div class="col-md-5 is-Incol">
                                    <p class="gr-Out"><?php echo showOtherLangText('Issued In'); ?></p>
                                    <p class="ttlAmount"><?php showPrice($resItemHistory['issuedInTot'],$getDefCurDet['curCode']);?></p>
                                </div>
                                <div class="col-md-5 is-Outcol">
                                    <p class="rd-In"><?php echo showOtherLangText('Issue Out'); ?></p>
                                    <p class="ttlAmount-rec"><?php showPrice($resItemHistory['issuedOutTot'],$getDefCurDet['curCode']);?></p>
                                </div>
                                <div class="col-md-2 maxBtn">
                                    <a href="javascript:void(0)" class="maxLink">
                                        <img src="Assets/icons/maximize.svg" alt="Maximize">
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="container detailPrice dtlitmVw-Prc">
                            <div class="row">
                                <div class="tab-mbDtl">
                                    <a href="javascript:void(0)" class="tab-revLnk"><i
                                            class="fa-solid fa-arrow-left"></i></a>
                                </div>
                                <div class="issueDtl itmHis-Dtlview">
                                    <div class="itmView-isuTbl d-flex justify-content-center text-center">
                                        <div class="itmVw-isuIn">
                                            <p class="gr-Out"><?php echo showOtherLangText('Issued In'); ?></p>
                                            <p class="viewItm-isuIn"><?php showPrice($resItemHistory['issuedInTot'],$getDefCurDet['curCode']);?></p>
                                            <p class="isuIn-countUnt"><?php echo $resItemHistory['issuedInQtyTot'];?> <?php echo $res['countingUnit'];?></p>
                                        </div>
                                        <div class="itmVw-isuOut">
                                            <p class="rd-In"><?php echo showOtherLangText('Issued Out'); ?></p>
                                            <p class="viewItm-isuOut"><?php showPrice($resItemHistory['issuedOutTot'],$getDefCurDet['curCode']);?></p>
                                            <p class="isuOut-countUnt"><?php echo $resItemHistory['issuedOutQtyTot'];?> <?php echo $res['countingUnit'];?></p>
                                        </div>
                                        <div style="min-width: fit-content;" class="itmVw-varPtive">
                                            <p class="Vw-varHead"><?php echo showOtherLangText('Variances'); ?></p>
                                            <div style="display: flex; gap:5px; justify-content: space-evenly">
                                                <p class="viewItm-varPlus varDif"><?php echo $minusvariance!=''? showPrice($minusvariance,$getDefCurDet['curCode']):'0';?></p>
                                                <p class="varPlus-countUnt"><?php echo $plusvariance!=''? showPrice($plusvariance,$getDefCurDet['curCode']):'0';?></p>
                                            </div>
                                            <div style="display: flex; gap:5px; justify-content: space-evenly">
                                                <p class="viewItm-varPlus varDif"><?php echo $minusqtytot!=''?$minusqtytot.' '.$res['countingUnit']:'0';?></p>
                                                <p class="varPlus-countUnt"><?php echo $plusqtytot!=''?$plusqtytot.' '.$res['countingUnit']:'&nbsp;';?></p>
                                            </div>
                                        </div>
                                        <div class="itmVw-varNtive">
                                            <p class="Vw-varHead"><?php echo showOtherLangText('Converted'); ?></p>
                                            <p class="viewItm-varMinus "><?php echo $resItemHistory['convertedQtyTot'];?><?php echo $res['countingUnit'];?></p>
                                            <p class="varMinus-countUnt ">
                                <?php showPrice($resItemHistory['convertedTot'],$getDefCurDet['curCode']);?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container position-relative itmView-Table">
                            <!-- Item Table Head Start -->
                            <div class="d-flex align-items-center itmTable">
                                <div class="tb-head d-flex align-items-center dt-Member gap-1">
                                    <div class="fltVw-itm">
                                        <div class="tab-mbFltr">
                                            <a href="javascript:void(0)" class="tab-lnkFltr"><i
                                                    class="fa-solid fa-arrow-left"></i></a>
                                        </div>
                                        <div class="tab-Filttxt">
                                            <p>Filter</p>
                                        </div>
                                        <div class="tab-itmVwchk">

                                        </div>
                                    </div>
                                    <div class="itmVw-dateClm">
                                        <p><?php echo showOtherLangText('Date'); ?></p>
                                    </div>
                                    <div class="itmVw-memberClm">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span id="ordertypetext"><?php echo showOtherLangText('Type'); ?></span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            <?php echo $typeOptions;?>
                                        </div>
                                    </div>

                                    <div class="itmVw-memberClm">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span id="refertotext"><?php echo showOtherLangText('Refer To'); ?></span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            <?php echo $suppMemStoreOptions;?>
                                        </div>
                                    </div>
                                </div>
                                <div class="tb-head d-flex align-items-center isu-Varance">
                                    <div class="itmVw-inClm">
                                        <p><?php echo showOtherLangText('Issue In'); ?></p>
                                    </div>
                                    <div class="itmVw-outClm">
                                        <p><?php echo showOtherLangText('Issue Out'); ?></p>
                                    </div>
                                    <div class="itmVw-varClm">
                                        <p class="bl-Head"><?php echo showOtherLangText('Variances'); ?></p>
                                    </div>
                                </div>
                                <div class="tb-head d-flex align-items-center last-Stock">
                                    <div class="itmVw-lstClm">
                                        <p><?php echo showOtherLangText('Last <br> Price'); ?></p>
                                    </div>
                                    <div class="itmVw-stkClm">
                                        <p><?php echo showOtherLangText('Stock <br> Price'); ?></p>
                                    </div>
                                </div>
                                <div class="tb-head itmVw-qtyClm">
                                    <p><?php echo showOtherLangText('Stock
Qty'); ?></p>
                                </div>
                            </div>
                            <!-- Item Table Head End -->
                        </div>

                        <div id="boxscroll">
                            <div class="container position-relative">
                                <!-- Item Table Body Start -->
                                   <?php
                                        
                                foreach($resItemHistory['resRows'] as $row)
                                { 

                                    $assignTo = '';
                                    if($row['ordType'] == 1)//order
                                   {
                                       $assignTo = '<span style="color:Green;font-weight:bold;">'.showOtherLangText('Issued In').'</span>'; 
                                   }
                                   elseif($row['ordType'] == 2)//requisition
                                   {
                                       $assignTo = '<span style="color:Red;font-weight:bold;">'.showOtherLangText('Issued Out').'</span>'; 
                                   }
                                   elseif($row['ordType'] == 3)//Stock Take
                                   {
                                       $assignTo = '<span style="color:Blue;font-weight:bold;">'.showOtherLangText('Stock Take').'</span>'; 
                                   }
                                   else
                                   {
                                       $assignTo = '<span style="color:Maroon;font-weight:bold;">'.showOtherLangText('Raw Item Convert').'</span>'; 
                                   }

                                    $suppMemStoreName = '';
                                    if($row['deptUserName'] =='' && $row['storeName'] =='')
                                    {
                                        $suppMemStoreName = '<p class="isuIn-Mem">'.$row['suppName'].'</p>'; 
                                    }
                                    if($row['suppName'] =='' && $row['storeName'] =='')
                                    {
                                        $suppMemStoreName = '<p class="isuOut-Mem">'.$row['deptUserName'].'</p>'; 
                                    }
                                    if($row['suppName'] =='' && $row['deptUserName'] =='')
                                    {
                                        $suppMemStoreName = '<p class="varStk-Mem">'.$row['storeName'].'</p>'; 
                                    }   
    
         ?>
                                <div class="itmVw-Task border-bottom">
                                    <div class="mb-itmVwbarOut"></div>
                                    <div class="align-items-center itmBody">
                                        <div class="tb-bdy d-flex align-items-center tbdy-dtMbr gap-1">
                                            <div class="itmVw-datebdClm">
                                                <p><?php echo date('d/m/y', strtotime($row['actDate']));?></p>
                                            </div>
                                            <div class="itmVw-memberbdClm">
                                                <?php echo $assignTo;?>
                                            </div>
                                            <div class="itmVw-referto">
                                                <?php echo $suppMemStoreName;?>
                                            </div>
                                        </div>
                                        <div class="tb-bdy d-flex align-items-center tbdy-isuVar">
                                            <div class="itmVw-inbdClm">
                                                <p class="mb-isuHead">Issue In</p>
                                                 <p><?php echo showItemTypeData(1, $row)!=''?showItemTypeData(1, $row).' '.$getDefCurDet['curCode']:''; ?></p>
                                                <p class="ctUnit-var"><?php echo showItemTypeData(1, $row, 1);?></p>
                                            </div>
                                            <div class="itmVw-outbdClm">
                                                <p class="mb-isuHead">Issue Out</p>
                                               <p><?php echo showItemTypeData(2, $row)!=''?showItemTypeData(2, $row).' '.$getDefCurDet['curCode']:''; ?></p>
                                                <p class="ctUnit-var"><?php echo showItemTypeData(2, $row, 1);?></p>
                                            </div>
                                            <div class="itmVw-varbdClm"><p><?php echo showItemTypeData(3, $row)!=''?showItemTypeData(3, $row).' '.$getDefCurDet['curCode']:''; ?></p>
                                                <p class="ctUnit-var"><?php echo showItemTypeData(3, $row, 1);?></p>
                                        </div>
                                        </div>
                                        <div class="tb-bdy align-items-center tbdy-lstStk">
                                            <div class="d-flex align-items-center tabDiv-prc">
                                                <div class="itmVw-lstbdClm">
                                                    <p class="mb-isuHead">Last Price</p>
                                                    <p><?php showPrice($row['lastPrice'],$getDefCurDet['curCode']);?></p>
                                                </div>
                                                <div class="itmVw-stkbdClm">
                                                    <p class="mb-isuHead">Stock Price</p>
                                                    <p><?php showPrice($row['stockPrice'],$getDefCurDet['curCode']);?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tb-bdy itmVw-qtybdClm">
                                            <p><?php echo $row['stockQty'];?></p>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </section>

                </section>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script type="text/javascript" src="cdn_js/jquery-ui-1.12.1.js"></script>
    <script>
$(function() {
    $(".datepicker").datepicker({
        dateFormat: 'dd-mm-yy'
    });
    
    $(".referto").on("click", "a", function(e){
        var $this = $(this).parent();
         $("#refertotext").text($this.data("value"));
         var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();
        window.location.href = "itemHistoryView.php?id=" + $("#id").val() + "&fromDate="+fromDate+ "&toDate="+toDate+ "&suppMemStoreId="+$this.data("id");
      });

      $(".ordertype").on("click", "a", function(e){
        var $this = $(this).parent();
         $("#ordertypetext").text($this.data("value"));
         var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();
        window.location.href = "itemHistoryView.php?id=" + $("#id").val() + "&fromDate="+fromDate+ "&toDate="+toDate+ "&ordType="+$this.data("id");
      });

      var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('suppMemStoreId')) {
        var dateTypeID = urlParams.get('suppMemStoreId');
            if(dateTypeID!=='')
            {
                $("#refertotext").text($(".referto .selected").text());
            }
        }

    });
  </script>
</body>

</html>