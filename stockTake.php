<?php
include('inc/dbConfig.php'); //connection details

//for excel file upload with Other language
use Shuchkin\SimpleXLSX;
require_once 'SimpleXLSX.php';

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

if( isset($_POST['storeId']) )
{
    $_SESSION['storeId'] = $_POST['storeId'];   
}

if( isset($_GET['processId']) && $_GET['stockTakeId'])
{   
    $_SESSION['processId'] = $_GET['processId'];
    $_SESSION['storeId'] = $_GET['stockTakeId'];
}

$fileDataRows = [];
$barCodesArr = [];

if( isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != '' )
{

    $xlsx = SimpleXLSX::parse($_FILES["uploadFile"]["tmp_name"]);

    $notFoundProducts = [];

    $i=0;
    foreach($xlsx->rows() as $row)
    {
        if($i == 0)
        {
            $i++;
            continue;
        }           

        $rows[] = [
        'BarCode' => $row[0],
        'StockTake' => $row[1]
        ];
    }

//----------------------------------------------

    if( is_array($rows) && !empty($rows) )
    {       

        $_SESSION['stockTake'] = $rows;

        $jsonData =  json_encode($rows);
        $pageName = 'stockTake';

        $insQry = " INSERT INTO tbl_log SET 
        accountId = '".$_SESSION['accountId']."',
        section = '".$pageName."',
        subSection = '',
        logData = '".$jsonData."',
        userId = '".$_SESSION['id']."',
        date = '".date('Y-m-d H:i:s')."'  ";
        mysqli_query($con, $insQry);
        
    }
}

$backLink = 'stockView.php';
if( isset($_SESSION['processId']) && isset($_SESSION['storeId']) )
{   
    $backLink = 'viewMobileStockTake.php?stockTakeId='.$_SESSION['storeId'];

    $sql=" SELECT t.*, p.barCode BarCode FROM  tbl_mobile_items_temp t 
    INNER JOIN tbl_products p ON(p.id = t.pId) AND (p.account_id = t.account_id)

    WHERE t.processId = '".$_SESSION['processId']."'  AND t.account_id = '".$_SESSION['accountId']."'  AND t.`stockTakeType` = 1 AND t.status=1 ";
    $result = mysqli_query($con, $sql);

    $pIdArr = [];
    while($stockTakeRes = mysqli_fetch_array($result) )
    {
        $fileDataRows[$stockTakeRes['BarCode']] = $stockTakeRes['qty'];
        $pIdArr[] = $stockTakeRes['pId'];
    }

    $sql = "SELECT p.*, s.qty stockQty, IF(u.name!='',u.name,p.unitC) unitC  FROM tbl_stocks s 
    INNER JOIN tbl_products p ON(s.pId = p.id) AND (s.account_id = p.account_id) 
    LEFT JOIN tbl_units u ON(u.id=p.unitC) AND (u.account_id = p.account_id)
    WHERE p.id IN( ".implode(',', $pIdArr)." )  AND p.account_id = '".$_SESSION['accountId']."' ";
    $importQry = mysqli_query($con, $sql);

}

if( isset($_SESSION['stockTake']) )
{
    if( is_array($_SESSION['stockTake']) && !empty($_SESSION['stockTake']) )
    {
        foreach($_SESSION['stockTake'] as $row)
        {

            $row['BarCode'] = trim($row['BarCode']);

            $fileDataRows[$row['BarCode']] = trim($row['StockTake']);

            $sqlSet = " SELECT * FROM tbl_products WHERE barCode = '".$row['BarCode']."' AND storageDeptId = '".$_SESSION['storeId']."'   AND account_id = '".$_SESSION['accountId']."' ";
           $resultSet = mysqli_query($con, $sqlSet);
           $productRes = mysqli_fetch_array($resultSet);
            if(!$productRes)
            {
               $notFoundProducts[] = $row['BarCode'];
            }
            else
            {
               $barCodesArr[] = $row['BarCode'];
            }
        }


        $sql = "SELECT p.*, s.qty stockQty, IF(u.name!='',u.name,p.unitC) unitC FROM tbl_stocks s 
        INNER JOIN tbl_products p ON(s.pId = p.id) AND s.account_id = p.account_id 
        LEFT JOIN tbl_units u ON(u.id=p.unitC) AND (u.account_id = p.account_id)
        WHERE p.barCode IN( ".implode(',', $barCodesArr)." ) AND p.account_id = '".$_SESSION['accountId']."'  ";
        $importQry = mysqli_query($con, $sql);


        if (!isset($_SESSION['newStockTakeVal'])) 
        {
            $_SESSION['newStockTakeVal'] = $_SESSION['stockTake'];
        }
        unset($_SESSION['stockTake']);

    }
}


if( isset($_POST['stockTakeData']) && count($_POST['stockTakeData']) > 0 )
{

$sqlSet = " SELECT ordNumber FROM tbl_orders WHERE account_id = '".$_SESSION['accountId']."'  ORDER BY id DESC LIMIT 1 ";
$ordQry = mysqli_query($con, $sqlSet);
$ordResult = mysqli_fetch_array($ordQry);
$ordNumber = $ordResult['ordNumber'] > 0 ? ($ordResult['ordNumber']+1) : 100000;

$qry = " INSERT INTO `tbl_orders` SET 
storeId = '".$_SESSION['storeId']."',
`ordType` = 3,
`ordNumber` = '".$ordNumber."',
`orderBy`  = '".$_SESSION['id']."',
`ordDateTime` = '".date('Y-m-d h:i:s')."',
`setDateTime` = '".date('Y-m-d h:i:s')."',
`ordAmt` = 0,
`status` = 2,
`account_id` = '".$_SESSION['accountId']."' ";
mysqli_query($con, $qry);
$ordId = mysqli_insert_id($con);

$ordAmt = 0;
foreach($_POST['stockTakeData'] as $productId=>$stockTake)
{   

    $sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$productId."'  AND account_id = '".$_SESSION['accountId']."'  ";
    $result = mysqli_query($con, $sql);
    $stockRow = mysqli_fetch_array($result);



$insertQry = " INSERT INTO `tbl_order_details` (`id`, `account_id`,`ordId`, `pId`, `qty`, `qtyReceived`, `price`, `lastPrice`, `stockPrice`, `stockQty`) VALUES  (NULL, 
    '".$_SESSION['accountId']."', 
    '".$ordId."', 
    '".$productId."', 
    '".$stockRow['qty']."',
    '".$stockTake."',
    '".$stockRow['lastPrice']."',
    '".$stockRow['lastPrice']."',
    '".$stockRow['stockPrice']."',
    '".$stockTake."') ";
mysqli_query($con, $insertQry);
            
                
    $upQry = " UPDATE  `tbl_stocks` SET
     `qty` = ".$stockTake.",
      `stockValue` = ".($stockTake*$stockRow['stockPrice'])." 
    WHERE id = '".$stockRow['id']."'   AND account_id = '".$_SESSION['accountId']."'  ";
    mysqli_query($con, $upQry);

}

if( isset($_SESSION['processId']) || (isset($_GET['stockedit']) && $_GET['stockedit'] == 1) )
{

$sql = " DELETE FROM tbl_mobile_time_track WHERE id = '".$_SESSION['processId']."' AND account_id = '".$_SESSION['accountId']."'  ";
mysqli_query($con, $sql);

$sql = " DELETE FROM tbl_mobile_items_temp WHERE processId = '".$_SESSION['processId']."' AND account_id = '".$_SESSION['accountId']."' ";
mysqli_query($con, $sql);

unset($_SESSION['processId']);          
}

echo "<script>window.location = 'stockView.php?stockTake=1'</script>";

}


$sqlSet = " SELECT * FROM tbl_stores WHERE id = '".$_SESSION['storeId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
$storeQry = mysqli_query($con, $sqlSet);
$storeRow = mysqli_fetch_array($storeQry);

$error = '';
if( isset($_POST['btnSbt']) )
{   

    if( !isset($_SESSION['stockTakeLoggedIn']) )
    { 
        //-------------------------------------------------------------------
            $query ="SELECT * FROM tbl_user WHERE password = '" . $_POST['pass'] . "' AND status = 1 AND account_id = '".$_SESSION['accountId']."'  ";
            $result = mysqli_query($con, $query);
            $res = mysqli_fetch_array($result);
            
            if ( !empty($res) ) {

                $_SESSION['stockTakeLoggedIn'] = 1;
            
            }
            else
            {
                  $url = "stockTake.php?passworderr=1";
                 header("Location:$url");
            }
    }
    
    
    if( isset($_SESSION['stockTake']) &&  isset($_SESSION['stockTakeLoggedIn']) )
    {
        if( is_array($_SESSION['stockTake']) && !empty($_SESSION['stockTake']) )
        {
            foreach($_SESSION['stockTake'] as $key=>$row)
            {
                if( trim($row['BarCode']) == $_POST['barCode'] )
                {   
                    $_SESSION['stockTake'][$key] = ['BarCode'=>$row['BarCode'], 'StockTake'=>$_POST['itemCount']];
                }
            }
        }
        
        $url = "stockTake.php?stockedit=1";
    
         header("Location:$url");
        
        exit;
    }
    
    if( isset($_SESSION['processId']) && isset($_SESSION['storeId']) &&  isset($_SESSION['stockTakeLoggedIn']) )
    {   
        
        $sql=" SELECT t.*, p.barCode BarCode  FROM  tbl_mobile_items_temp t 
                                INNER JOIN tbl_products p ON(p.id = t.pId) AND p.account_id = t.account_id 
                        WHERE t.processId = '".$_SESSION['processId']."' AND t.account_id = '".$_SESSION['accountId']."'   AND t.`stockTakeType` = 1 AND t.status=1 ";
        $result = mysqli_query($con, $sql);
        
        $pIdArr = [];
        while($stockTakeRes = mysqli_fetch_array($result) )
        {
            
            if( trim($stockTakeRes['BarCode']) == $_POST['barCode'] )
            {   
                 $sql=" UPDATE  tbl_mobile_items_temp  SET qty = '".$_POST['itemCount']."'
                        WHERE processId = '".$_SESSION['processId']."'  AND account_id = '".$_SESSION['accountId']."'  AND `stockTakeType` = 1 AND status=1 AND pId='".$stockTakeRes['pId']."' ";
                mysqli_query($con, $sql);
                
                
                $url = "stockTake.php?stockedit=1";
    
                header("Location:$url");
                
                exit;
            }
        }
        
        
    }
        
    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Queue1.com</title>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Manage Suppliers') ?></h1>
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

                <section class="ordDetail userDetail">
                    <div class="container">
                    <?php if(isset($_GET['stockedit']) || isset($_GET['update']) || isset($_GET['delete'])) {?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p><?php 

echo isset($_GET['stockedit']) ? ' '.showOtherLangText('Stock Take Count edited successfully').' ' : '';
 ?>
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                            <?php if(isset($_GET['passworderr'])) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo isset($_GET['passworderr']) ? ' '.showOtherLangText('Password Error').' ' : '';
 ?></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                             <form method="post" action="">



                    <input type="hidden" name="storeId" value="<?php echo $_SESSION['storeId'];?>" />

                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="<?php echo $backLink; ?>" class="sub-btn std-btn mb-usrBkbtn"><span class="mb-UsrBtn"><i
                                                class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Back') ?></span></a>
                                </div>
                            </div>
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                  <h3 style="
    text-align: center;
"> <?php echo showOtherLangText('Stock Take'); ?></h3>
                                </div>
                                <div class="btnBg">
                                  <strong><?php echo date('m/d/Y');?>
                        <?php echo $storeRow['name'];?></strong></a>
                                </div>
                            </div>
                            <div class="usrAd-Btn">
                                <div class="btnBg">
                                    <button type="submit" class="sub-btn std-btn mb-usrBkbtn" style="width: 165px;"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-plus"></i>
                                            <span class="nstdSpan">Supplier</span></span> <span class="dsktp-Btn"><?php echo showOtherLangText('Overwrite and Save') ?></span></button>
                                </div>
                            </div>

                        </div>
                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <?php if( isset($_GET['showVar']) ) 
                            { ?>
                                <div class="btnBg">
                                  <a style="color:#000000; text-decoration:underline" href="stockTake.php"><?php echo showOtherLangText('Show All Variances') ?></a>
                                </div>
                            <?php } else {?>
                                <div class="btnBg">
                                  <a style="color:#000000; text-decoration:underline" href="stockTake.php?showVar"><?php echo showOtherLangText('Hide (0) Variances') ?></a>
                                </div>
                            <?php } ?>
                            </div>
                            
                            <div class="itmMng-Src usrAd-Btn">
                                <div class="btnBg">
                                    <div class="d-flex align-items-center itmMng-xlIcn">
                                        <div class="chkStore">
                                            <a href="stocktake_excel.php" target="_blank">
                                                <img src="Assets/icons/stock-xcl.svg" alt="Stock Xcl">
                                            </a>
                                        </div>
                                        <div class="chkStore">
                                            <a href="stocktake_pdf_download.php" target="_blank">
                                                <img src="Assets/icons/stock-pdf.svg" alt="Stock PDF">
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="suplrTable">
                            <div class="container">
                                <!-- Table Head Start -->
                                <div class="suppTbl-Head align-items-center itmTable">
                                    <div class="supTbl-NmCol d-flex align-items-center">
                                        <div class="tb-head supNum-Clm">
                                            <div class="d-flex align-items-center">
                                                <p><?php echo showOtherLangText('#') ?></p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" onclick="sortRows(1);" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" onclick="sortRows(0);" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="tb-head supName-Clm">
                                            <p><?php echo showOtherLangText('Photo'); ?></p>
                                        </div>
                                        <div class="tb-head supAdd-Clm">
                                            <p><?php echo showOtherLangText('Item') ?></p>
                                        </div>
                                        <div class="tb-head supEml-Clm">
                                            <p><?php echo showOtherLangText('Bar Code') ?></p>
                                        </div>
                                    </div>
                                    <div class="supTbl-EmCol d-flex align-items-center">
                                        
                                        <div class="tb-head supPhn-Clm">
                                            <p><?php echo showOtherLangText('Unit'); ?></p>
                                        </div>
                                        <div class="tb-head supPhn-Clm">
                                            <p><?php echo showOtherLangText('Qty'); ?></p>
                                        </div>
                                         <div class="tb-head supPhn-Clm">
                                            <p><?php echo showOtherLangText('Stock Take'); ?></p>
                                        </div>
                                         <div class="tb-head supPhn-Clm">
                                            <p><?php echo showOtherLangText('Variances'); ?></p>
                                        </div>
                                    </div>
                                    <div class="supTbl-IcnCol">
                                        <div class="tb-head supOpt-Clm">
                                            <p><?php echo showOtherLangText('Options') ?></p>
                                        </div>
                                    </div>
                                </div>
                                 <div id="myRecords">
                                <?php 

								$x= 0;

							 while($row = mysqli_fetch_array($importQry))

								{

									$x++;
                        
                                    if( isset($_GET['showVar']) && ($fileDataRows[$row['barCode']]-$row['stockQty']) == 0 )
                                    {
                                        continue;
                                    }

									?>
                                    <input type="hidden" name="stockTakeData[<?php echo $row['id'];?>]"
                                value="<?php echo $fileDataRows[$row['barCode']];?>" />
                                <div class="suplrTask">
                                    <div class="suplrTbl-body itmBody">
                                        <div class="supTbl-NmCol d-flex align-items-center">
                                            <div class="tb-bdy supNum-Clm">
                                                <p class="suplNumber"><span class="mb-UsrSpan">No. </span><?php echo $x;?></p>
                                            </div>
                                            <div class="tb-bdy supName-Clm">
                                                <p class="suplName"><?php 
                     if( $row['imgName'] != ''  && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$row['imgName'] )  )
                     {  
                        echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$row['imgName'].'" width="60" height="60">';
                        //echo '<img src="'.$siteUrl.'uploads/'.$row['imgName'].'" width="60" height="60">';
                     }
                    ?></p>
                                            </div>
                                            <div class="tb-bdy supAdd-Clm">
                                                <p class="suplAdrs"><?php echo $row['itemName'];?></p>
                                            </div>
                                            <div class="tb-head supEml-Clm">
                                                <p><?php echo $row['barCode'];?></p>
                                            </div>
                                        </div>
                                        <div class="supTbl-EmCol align-items-center">
                                            
                                            <div class="tb-head supPhn-Clm">
                                                <p><?php echo $row['unitC'];?></p>
                                            </div>
                                             <div class="tb-head supPhn-Clm">
                                                <p><?php echo $row['stockQty'];?></p>
                                            </div>
                                            <div class="tb-head supPhn-Clm">
                                                <p><?php echo $fileDataRows[$row['barCode']];?></p>
                                            </div>
                                            <div class="tb-head supPhn-Clm">
                                                <p><?php echo $fileDataRows[$row['barCode']]-$row['stockQty'];?></p>
                                            </div>
                                        </div>
                                        <div class="supTbl-IcnCol">
                                            <div class="tb-bdy supOpt-Clm d-flex align-items-center">
                                                <a onclick="editStockTake('<?php echo $row['barCode'];?>');" class="userLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask-Splr">
                                        <a href="javascript:void(0)" class="suplrLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <?php 

								}

								?>
                                <!-- Table Body End -->
                               </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </section>

            </div>
        </div>
    </div>
    <div id="dialog" style="display: none;">
        <?php echo showOtherLangText('Are you sure to delete this record?') ?>
    </div>
    <form action="" name="addServiceFeeFrm" class="addUser-Form row container glbFrm-Cont" id="addServiceFeeFrm" method="post" autocomplete="off">
    <div class="modal" tabindex="-1" id="new-service-item" aria-labelledby="add-CategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php echo showOtherLangText('Service Name'); ?></h1>
                </div>
                <div class="modal-body">
                    <input type="text" required class="form-control" id="itemName" name="itemName" placeholder="<?php echo showOtherLangText('Service Name');?> *" autocomplete="off"
                                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                            onChange="this.setCustomValidity('')" required>
                    <input type="number" required class="form-control" id="feeAmt" name="itemFeeAmt" placeholder="<?php echo showOtherLangText('Amount').' '.$getDefCurDet['curCode']; ?> *" autocomplete="off"
                                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                            onChange="this.setCustomValidity('')" required>
                    <input type="text" required class="form-control" id="unit" name="unit" placeholder="<?php echo showOtherLangText('Unit'); ?> *" autocomplete="off"
                                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                            onChange="this.setCustomValidity('')" required>
                </div>
                  <div>
                    <div class="feeSave">
                        <input type="checkbox" class="optionCheck" id="visibility" name="visibility" value="1">
                        <span class="subTittle1" style="vertical-align:text-top;"><?php echo showOtherLangText('save to fixed service item
list'); ?></span><br>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="submit" id="addFee" name="addFee" class="btn sub-btn std-btn"><?php echo showOtherLangText('Add'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    <?php require_once('footer.php');?>
    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
   <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
   <form action="" name="addServiceFeeFrm" class="addUser-Form row container glbFrm-Cont" id="addServiceFeeFrm" method="post" autocomplete="off">
   <div class="modal" tabindex="-1" id="order_details" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">

            <div id="order_details_supplier" class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php echo showOtherLangText('Stock take edit'); ?></h1>
                </div>
               
                <form method="post" id="stocksubmit" action="editStockTakeItemCount.php?barCode=<?php echo $_GET['barCode']; ?>" autocomplete="off">
            
                 <input type="hidden" name="barCode" id="barCode" value="<?php echo $_GET['barCode'];?>"/>
                 <input type="hidden" name="returnUrl" value="<?php echo $_GET['returnUrl'];?>"/>
                <div class="modal-body">
                    <?php 
                    if( !isset($_SESSION['stockTakeLoggedIn']) )
                    {
                    ?><input type="password" required class="form-control" id="pass" name="pass" placeholder="<?php echo showOtherLangText('Password'); ?> *" autocomplete="off"
                                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                            onChange="this.setCustomValidity('')" required>
                    <?php } ?>
                    <input type="text" required class="form-control" id="itemCount" name="itemCount" placeholder="<?php echo showOtherLangText('Stock take');?> *" autocomplete="off"
                                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                            onChange="this.setCustomValidity('')" required>
                   
                </div>
                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="submit" name="btnSbt" onclick="" class="deletelink btn sub-btn std-btn"><?php echo showOtherLangText('Save'); ?></button>
                    </div>
                    <div class="btnBg">
                      <button type="button" data-bs-dismiss="modal" class="btn sub-btn std-btn"><?php echo showOtherLangText('Cancel'); ?></button>
                    </div>
                </div>
                    </form>
            </div>
        </div>
    </div>
    </form>
   
</body>

</html>
<script>  
//  function getDelNumb(delId){
// var newOnClick = "window.location.href='manageSuppliers.php?delId=" + delId + "'";

//       $('.deletelink').attr('onclick', newOnClick);
//      $('#delete-popup').modal('show');

//  }  

 jQuery.fn.orderBy = function(keySelector) {
        return this.sort(function(a, b) {
            a = keySelector.apply(a);
            b = keySelector.apply(b);
            console.log('a',a,'b',b);
            if (a > b) return 1;
            if (a < b) return -1;
            return 0;
        });
    };

    // Function to sort and reorder the .userTask elements
    function sortRows(sort) {
        var uu = $(".suplrTask").orderBy(function() {
             var number = +$(this).find(".suplNumber").text().replace('No. ', '');
             return sort === 1 ? number : -number; 
        }).appendTo("#myRecords");


    }
     function getDelNumb(canId, ordType){
var newOnClick = "window.location.href='runningOrders.php?canId=" + canId + "&type=" + ordType+ "'";

      $('.deletelink').attr('onclick', newOnClick);
     $('#delete-popup').modal('show');

 }
    function editStockTake(barCode) {
     
        $('#barCode').val(barCode);
        let url = 'stockTake.php?barCode='+barCode;
        $('#addServiceFeeFrm').attr('action',url);
        $('#order_details').modal('show');

       

}
 <?php if(isset($_GET['passworderr'])) { ?> 
        let url = location.href;
        let qryparameter = 'passworderr';
       let newurl = removeQueryParameter(url,qryparameter);
       history.pushState(null, "", newurl);
      <?php } ?> 
      <?php if(isset($_GET['stockedit'])) { ?> 
        let url = location.href;
        let qryparameter = 'stockedit';
       let newurl = removeQueryParameter(url,qryparameter);
       history.pushState(null, "", newurl);
      <?php } ?> 
  
</script>
