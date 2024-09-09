<?php
    include('../inc/dbConfig.php'); //connection details
    $pgnm = 'Outlet Stocktacking';
    $outletStocktackingStep = '6';

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

    if( isset( $_GET['stockTakeId'] ) )
    {
        //get all value of temp data for insert into tbl_log
        $sql=" SELECT * FROM  tbl_mobile_items_temp WHERE stockTakeId = '".$_GET['stockTakeId']."' AND `userId` = '".$_SESSION['id']."' AND status=0  AND account_id = '".$_SESSION['accountId']."'  ";
        $result = mysqli_query($con, $sql);
        $dataRows[] = '';
        
        while($row = mysqli_fetch_array($result)){
            $dataRows[]=("stockTakeType:".$row['stockTakeType']. "stockTakeId:".$row['stockTakeId']. "pId:".$row['pId']. "qty:".$row['qty']);                  
        }

        //insertion into log table  
        $allPostData = $dataRows;
        $jsonData =  json_encode($allPostData);
        $pageName = 'Mobile';
        $subSection = 'Outlet StockTaking';
        $insQry = " INSERT INTO tbl_log SET 
                    accountId = '".$_SESSION['accountId']."',
                    section = '".$pageName."',
                    subSection = '".$subSection."',
                    logData = ('".$jsonData."' AND outLetId='".$_GET['revenueId']."'),
                    userId = '".$_SESSION['id']."',
                    date = '".date('Y-m-d H:i:s')."'  ";
                    mysqli_query($con, $insQry);
                    //end of insertion into log table  
    }

    if( isset( $_GET['stockTakeId'])){
        finishStockTake($_GET['stockTakeId'], 2, $_SESSION['id']);
    }

    $stockTakeRow = getRevenueOutLetDetailsById($_GET['stockTakeId']);
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
    <section class="fnlSection">
        <div class="container">
            <div class="msgBx-Stock text-center">
                <h4>Congradulations,</h4>
                <p>Outlet stock take updated successfully</p>
                <a href="<?php echo $mobileSiteUrl;?>index.php" class="homeBtn"><i class="fa-solid fa-arrow-left"></i> <span><?php echo showOtherLangText('Home') ?></span></a>
            </div>
        </div>
    </section>
    <?php include('layout/mJs.php'); ?>
</body>
</html>