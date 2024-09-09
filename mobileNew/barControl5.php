<?php 
    include('../inc/dbConfig.php'); //connection details
    $pgnm = 'Bar Control';
    $barControlStep = '5';

    //Get language Type 
    $getLangType = getLangType($_SESSION['language_id']);

    $checkCurPage = checkCurPage();
    if ($checkCurPage == 5) {
        echo "<script>window.location.href='".$siteUrl."'</script>";
        exit;
    }

    if( !isset($_SESSION['id']) ||  $_SESSION['id'] < 1){
        echo "<script>window.location.href='".$siteUrl."';</script>";
        exit;
    }

    if( isset( $_GET['stockTakeId'])){
        finishStockTake($_GET['stockTakeId'], 5, $_SESSION['id']);
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
                <p>Bar stock take updated successfully.</p>
                <a href="<?php echo $mobileSiteUrl;?>index.php" class="homeBtn"><i class="fa-solid fa-arrow-left"></i> <span><?php echo showOtherLangText('Home') ?></span></a>
            </div>
        </div>
    </section>
    <?php include('layout/mJs.php'); ?>
</body>
</html>