<?php
    include('../inc/dbConfig.php'); //connection details
    $pgnm = 'Outlet Stocktacking';
    $outletStocktackingStep = '1';

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

    $sql = "select  rc.id, rc.account_id, rc.name, du.name as outletName, rcd.id as outLetId, rcd.outLetType FROM tbl_revenue_center_departments rcd 
    INNER JOIN tbl_deptusers du ON(du.id = rcd.deptId)  AND du.account_id = rcd.account_id 
    INNER JOIN tbl_revenue_center rc ON(rc.id = rcd.revCenterId) AND  rc.account_id = rcd.account_id 
    INNER JOIN tbl_outlet_items o ON(o.outLetId = rcd.id) AND o.account_id = rcd.account_id AND o.status=1 
    WHERE rc.account_id = '".$_SESSION['accountId']."' 
    GROUP BY rc.id order by rc.id desc ";
            
    $revQry = mysqli_query($con, $sql);

    $totalItems = 0;
    if( isset($_GET['outLetId']) )
    {
        $totalItems = getOutLetItemsCount($_GET['outLetId']);
        $outLetDet = getRevenueOutLetDetailsById($_GET['outLetId']);
        $_GET['revenueId'] =  $outLetDet['revCenterId'];
    }

    if( isset($_GET['revenueId']) )
    {
        $sql = "select rcd.account_id, du.name, rcd.id  FROM tbl_revenue_center_departments rcd
            INNER JOIN tbl_deptusers du ON(du.id = rcd.deptId) AND  du.account_id = rcd.account_id
            INNER JOIN tbl_revenue_center rc ON(rc.id = rcd.revCenterId) AND rc.account_id = rcd.account_id  AND rc.id = '".$_GET['revenueId']."'
            WHERE  rcd.account_id = '".$_SESSION['accountId']."' 
            GROUP BY rcd.id order by du.name  ";
            
        $outLetQry = mysqli_query($con, $sql);
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
        </div>
    </section>
    <section class="strgList">
        <div class="container">
            <?php
                while($revRow = mysqli_fetch_array($revQry)){
                    $revenueCenterId = $revRow['id'];
                    $revenueCenterName = $revRow['name'];

                    $outletSql = "SELECT du.name as outletName, du.account_id, rcd.id as outLetId  FROM tbl_revenue_center_departments rcd 
                    INNER JOIN tbl_deptusers du ON(du.id = rcd.deptId) AND du.account_id = rcd.account_id 
                    INNER JOIN tbl_revenue_center rc ON(rc.id = rcd.revCenterId) AND rc.account_id = rcd.account_id  AND rc.id = ".$revenueCenterId." 
                    WHERE  du.account_id = ".$_SESSION['accountId']." 
                    GROUP BY rcd.id order by du.name";
                    
                    $outletQry = mysqli_query($con, $outletSql);

                    while($outletRow = mysqli_fetch_array($outletQry)){
                        $outletId = $outletRow['outLetId'];
                        $outletName = $outletRow['outletName'];
                        $totalItems = getOutLetItemsCount($outletId);
                        ?>
                        <a href="<?php echo $mobileSiteUrl;?>outletStocktaking2.php?revenueId=<?php echo $revenueCenterId;?>&outLetId=<?php echo $outletId;?>" class="strgProduct mt-3">
                            <div class="inner__content bg-white">
                                <div class="row g-0">
                                    <div class="col-md-6 storeClm">
                                        <p class="storeName ellipsis"><?php echo $revenueCenterName;?></p>
                                        <p class="storeName ellipsis"><?php echo $outletName;?></p>
                                        <p class="prdCode"># </p>
                                    </div>
                                    <div class="col-md-6 countClm">
                                        <p class="PrdDate"><?php echo date('d/m/Y');?></p>
                                        <p class="prdItm-Count">&nbsp;</p>
                                        <p class="prdItm-Count"><?php echo $totalItems;?>/<?php echo $totalItems;?> <?php echo showOtherLangText('Item') ?></p>
                                    </div>
                                </div> 
                            </div>
                        </a>
                        <?php
                    }
                }
            ?>
        </div>
    </section>
    <?php include('layout/mJs.php'); ?>
</body>
</html>