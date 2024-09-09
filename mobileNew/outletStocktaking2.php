<?php
    include('../inc/dbConfig.php'); //connection details
    $pgnm = 'Outlet Stocktacking';
    $outletStocktackingStep = '2';

    //Get language Type 
    $getLangType = getLangType($_SESSION['language_id']);

    $checkCurPage = checkCurPage();
    if ($checkCurPage == 2) {
        echo "<script>window.location.href='".$siteUrl."'</script>";
        exit;
    }

    if( !isset($_SESSION['id']) ||  $_SESSION['id'] < 1){
        echo "<script>window.location.href='".$siteUrl."';</script>";
        exit;
    }

    if(isset($_GET['revenueId'])){
        $sql = "select  rc.id, rc.account_id, rc.name, du.name as outletName, rcd.id as outLetId, rcd.outLetType FROM tbl_revenue_center_departments rcd 
        INNER JOIN tbl_deptusers du ON(du.id = rcd.deptId)  AND du.account_id = rcd.account_id 
        INNER JOIN tbl_revenue_center rc ON(rc.id = rcd.revCenterId) AND  rc.account_id = rcd.account_id 
        INNER JOIN tbl_outlet_items o ON(o.outLetId = rcd.id) AND o.account_id = rcd.account_id AND o.status=1 
        WHERE rc.account_id = '".$_SESSION['accountId']."' AND rc.id = '".$_GET['revenueId']."'
        GROUP BY rc.id order by rc.id desc ";
            
        $revQry = mysqli_query($con, $sql);

        $revRow = mysqli_fetch_array($revQry);
        $revenueCenterId = $revRow['id'];
        $revenueCenterName = $revRow['name'];

        if(isset($_GET['outLetId'])){
            $outletSql = "SELECT du.name as outletName, du.account_id, rcd.id as outLetId  FROM tbl_revenue_center_departments rcd 
                        INNER JOIN tbl_deptusers du ON(du.id = rcd.deptId) AND du.account_id = rcd.account_id 
                        INNER JOIN tbl_revenue_center rc ON(rc.id = rcd.revCenterId) AND rc.account_id = rcd.account_id  AND rc.id = ".$revenueCenterId." 
                        WHERE  du.account_id = ".$_SESSION['accountId']." AND rcd.id = ".$_GET['outLetId']."
                        GROUP BY rcd.id order by du.name";
                        
            $outletQry = mysqli_query($con, $outletSql);

            $outletRow = mysqli_fetch_array($outletQry);
            $outletId = $outletRow['outLetId'];
            $outletName = $outletRow['outletName'];
            $totalItems = getOutLetItemsCount($outletId);
        }
    }
?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title><?php echo showOtherLangText('Start') ?> - Queue1 Mobile</title>
    <?php include('layout/mCss.php'); ?>
</head>
<body>
    <section class="headSec">
        <div class="container">
            <?php include('layout/mHeader.php'); ?>
            <div class="text-center">
                <a href="<?php echo $mobileSiteUrl;?>outletStocktaking3.php?revenueId=<?php echo $_GET['revenueId'];?>&stockTakeId=<?php echo $_GET['outLetId'];?>&start=1" class="stockTake-Btn"><?php echo showOtherLangText('Start StockTaking') ?></a>
            </div>
        </div>
    </section>
    <section class="storeSection">
        <div class="container">
            <div class="storeDtl">
                <div class="inner__content bg-white"> 
                    <h3 class="mblFnt2">Outlet Stocktaking</h3>
                    <div class="d-flex justify-content-between align-items-center flex-wrap pt-3">
                        <div class="stkData">
                            <p class="strHead">Date</p>
                            <p class="storeDate"><?php echo date('d/m/Y');?></p>
                        </div>
                        <div class="stkData">
                            <p class="strHead">Time</p>
                            <p class="storeTime"><?php echo date('h:i');?></p>
                        </div>
                        <div class="stkData">
                            <p class="strHead">Revenue Center</p>
                            <p class="storeType"><?php echo $revenueCenterName;?></p>
                        </div>
                        <div class="stkData">
                            <p class="strHead">Outlet</p>
                            <p class="storeType"><?php echo $outletName;?></p>
                        </div>
                        <div class="stkData">
                            <p class="strHead">Total Items To Count</p>
                            <p class="storeCount"><?php echo $totalItems;?>/<?php echo $totalItems;?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include('layout/mJs.php'); ?>
</body>
</html>