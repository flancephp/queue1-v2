<?php 
    include('../inc/dbConfig.php'); //connection details
    $pgnm = 'Storage Stocktaking';
    $storageStocktakingStep = '2';

    //Get language Type 
    $getLangType = getLangType($_SESSION['language_id']);

    $checkCurPage = checkCurPage();
    if ($checkCurPage == 1) {
        echo "<script>window.location.href='".$siteUrl."'</script>";
        exit;
    }

    if( !isset($_SESSION['id']) ||  $_SESSION['id'] < 1){
        echo "<script>window.location.href='".$siteUrl."';</script>";
        exit;
    }

    $totalItems = 0;
    if(isset($_GET['storageId'])){
        $storageId = $_GET['storageId'];

        $totalItems = getStorageItemsCount($storageId);
        $storageDeptRow = getStoreDetailsById($storageId);

        $sqlSet = " SELECT * FROM tbl_stores WHERE id = $storageId AND account_id = '".$_SESSION['accountId']."'  order by name ";
        $storeQry = mysqli_query($con, $sqlSet);

        $storage = mysqli_fetch_array($storeQry);
        $storageId = $storage['id'];
        $storageName = $storage['name'];
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
                <a href="<?php echo $mobileSiteUrl;?>storageStocktaking3.php?storageId=<?php echo $storageId;?>&start=1" class="stockTake-Btn"><?php echo showOtherLangText('Start StockTaking') ?></a>
            </div>
        </div>
    </section>
    <section class="storeSection">
        <div class="container">
            <div class="storeDtl">
                <div class="inner__content bg-white"> 
                    <h3 class="mblFnt2"><?php echo showOtherLangText('Storage Stocktaking') ?></h3>
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
                            <p class="strHead">Storage</p>
                            <p class="storeType"><?php echo $storageName;?></p>
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