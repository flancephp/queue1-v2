<?php 
    include('../inc/dbConfig.php'); //connection details
    $pgnm = 'Storage Stocktaking';
    $storageStocktakingStep = '4';

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

    if( isset( $_GET['finish'])){
        trackStockProcessTime($_GET['storageId'], 1, $_SESSION['id'], 1);
    }

    $totalItems = getStorageItemsCount($_GET['storageId']);

    //get temp data
    $sql=" SELECT * FROM  tbl_mobile_items_temp WHERE stockTakeId = '".$_GET['storageId']."' AND stockTakeType=1 AND `userId` = '".$_SESSION['id']."' AND status=0  AND account_id = '".$_SESSION['accountId']."'  ";
    $result = mysqli_query($con, $sql);
    $tempItemCnt = mysqli_num_rows($result);
    
    if($tempItemCnt < 1){
        header('location: storageStocktaking2.php?storageId='.$_GET['storageId']);
        exit;
    }

    $cond = " AND  storageDeptId = '".$_GET['storageId']."' ";
    $storageDeptRow = getStoreDetailsById($_GET['storageId']);

    $sql=" SELECT * FROM  tbl_mobile_time_track WHERE stockTakeId = '".$_GET['storageId']."' AND `userId` = '".$_SESSION['id']."' AND `stockTakeType` = 1 AND status=0  AND account_id = '".$_SESSION['accountId']."' ";
    $result = mysqli_query($con, $sql);
    $timeTrackRes = mysqli_fetch_array($result);

    $startDate = date('h:i:s A', strtotime($timeTrackRes['start_time']));
    $endDate = date('h:i:s A', strtotime($timeTrackRes['end_time']));

    if($getLangType == '1')
    {
        if(strpos($startDate, AM)){
            //$startDate = str_replace('AM', 'בבוקר', $startDate);
            $startDate = str_replace('AM', ''.showOtherLangText('AM').'', $startDate);
        }
        else if(strpos($startDate, PM)){
            //$startDate = str_replace('PM', 'אחר הצהריים', $startDate);
            $startDate = str_replace('PM', ''.showOtherLangText('PM').'', $startDate);
        }
        
        if(strpos($endDate, AM)){
            //$endDate = str_replace('AM', 'בבוקר', $endDate);
            $endDate = str_replace('AM', ''.showOtherLangText('AM').'', $startDate);
        }
        else if(strpos($endDate, PM)){
            //$endDate = str_replace('PM', 'אחר הצהריים', $endDate);
            $endDate = str_replace('PM', ''.showOtherLangText('PM').'', $startDate);
        }
    }
?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title><?php echo showOtherLangText('finish and upload') ?> - Queue1 Mobile</title>
    <?php include('layout/mCss.php'); ?>
</head>
<body>
    <section class="headSec">
        <div class="container">
            <?php include('layout/mHeader.php'); ?>
            <div class="d-flex align-items-center justify-content-between pt-3 upldClm">
                <a href="<?php echo $mobileSiteUrl;?>storageStocktaking5.php?storageId=<?php echo $_GET['storageId'];?>" class="eyeBtn"><i class="fa-solid fa-eye"></i></a>

                <a href="<?php echo $mobileSiteUrl;?>storageStocktaking6.php?storageId=<?php echo $_GET['storageId'];?>" class="fnshBtn"><?php echo showOtherLangText('Finish and Upload') ?> <img src="Assets/icons/download.svg" alt="Finish"></a>
            </div>
        </div>
    </section>
    <section class="finishSection">
        <div class="container">
            <div class="scflMsg">
                <p class="text-center">All <span class="numItems"><?php echo $tempItemCnt;?></span> items counted successfully</p>
            </div>

            <div class="itmCount-Dtl">
                <div class="row align-tems-center taskDetail">
                    <div class="col-md-6">
                        <p><?php echo showOtherLangText('Outlet') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><?php echo isset($storageDeptRow['name']) ? $storageDeptRow['name'] : ' '.showOtherLangText('Select Storage').' ';?></p>
                    </div>
                </div>
                <div class="row align-tems-center taskDetail pt-3">
                    <div class="col-md-6">
                        <p><?php echo showOtherLangText('Date') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="fw-500"><?php echo date('d/m/Y');?></span></p>
                    </div>
                </div>
                <div class="row align-tems-center taskDetail pt-3">
                    <div class="col-md-6">
                        <p><?php echo showOtherLangText('User') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="fw-500"><?php echo $_SESSION['name'];?></p>
                    </div>
                </div>
                <div class="row align-tems-center taskDetail pt-3">
                    <div class="col-md-6">
                        <p><?php echo showOtherLangText('Start time') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="fw-500"><?php echo $startDate;?></p>
                    </div>
                </div>
                <div class="row align-tems-center taskDetail pt-3">
                    <div class="col-md-6">
                        <p><?php echo showOtherLangText('Finish time') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="fw-500"><?php echo $endDate;?></p>
                    </div>
                </div>
                <?php
                    $to_time = strtotime($timeTrackRes['start_time']);
					$from_time = strtotime($timeTrackRes['end_time']);
					$diff = round(abs($to_time - $from_time) / 60)." ".showOtherLangText('min');
                ?>
                <div class="row align-tems-center taskDetail pt-3">
                    <div class="col-md-6">
                        <p><?php echo showOtherLangText('Total Time') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="fw-500"><?php echo $diff;?></p>
                    </div>
                </div>
                <div class="row align-tems-center taskDetail pt-3">
                    <div class="col-md-6">
                        <p><?php echo showOtherLangText('Total items') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="fw-500"><?php echo $totalItems;?></p>
                    </div>
                </div>
                <div class="row align-tems-center taskDetail pt-3">
                    <div class="col-md-6">
                        <p><?php echo showOtherLangText('Counted') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="fw-500"><?php echo $tempItemCnt;?></p>
                    </div>
                </div>
                <div class="row align-tems-center taskDetail pt-3">
                    <div class="col-md-6">
                        <p><?php echo showOtherLangText('Not counted') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="fw-500"><?php echo $totalItems-$tempItemCnt;?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include('layout/mJs.php'); ?>
</body>
</html>