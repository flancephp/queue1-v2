<?php
include('../inc/dbConfig.php'); //connection details
$pgnm = 'Storage Stocktaking';
$storageStocktakingStep = '1';

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$checkCurPage = checkCurPage();
if ($checkCurPage == 1) {
    echo "<script>window.location.href='" . $siteUrl . "'</script>";
    exit;
}

if (!isset($_SESSION['id']) ||  $_SESSION['id'] < 1) {
    echo "<script>window.location.href='" . $siteUrl . "';</script>";
    exit;
}

$sqlSet = " SELECT * FROM tbl_stores WHERE account_id = '" . $_SESSION['accountId'] . "'  order by name ";
$storeQry = mysqli_query($con, $sqlSet);

$totalItems = 0;
if (isset($_GET['storageId'])) {
    $totalItems = getStorageItemsCount($_GET['storageId']);
    $storageDeptRow = getStoreDetailsById($_GET['storageId']);
}
?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

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
            if (mysqli_num_rows($storeQry) > 0) {
                while ($storage = mysqli_fetch_array($storeQry)) {
                    $storageId = $storage['id'];
                    $storageName = $storage['name'];
                    $totalItems = getStorageItemsCount($storageId);
            ?>
                    <a href="<?php echo $mobileSiteUrl; ?>storageStocktaking2.php?storageId=<?php echo $storageId; ?>" class="strgProduct mt-3">
                        <div class="inner__content bg-white">
                            <div class="row g-0">
                                <div class="col-md-6 storeClm">
                                    <p class="storeName mb-1 ellipsis"><?php echo $storageName; ?></p>
                                    <p class="prdCode"># </p>
                                </div>
                                <div class="col-md-6 countClm">
                                    <p class="PrdDate"><?php echo date('d/m/Y'); ?></p>
                                    <p class="prdItm-Count"><?php echo $totalItems; ?>/<?php echo $totalItems; ?> <?php echo showOtherLangText('Item') ?></p>
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