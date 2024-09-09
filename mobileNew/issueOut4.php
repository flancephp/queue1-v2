<?php 
    include('../inc/dbConfig.php'); //connection details
    $pgnm = 'Issue Out';
    $issueOutStep = '4';

    //Get language Type 
    $getLangType = getLangType($_SESSION['language_id']);

    $checkCurPage = checkCurPage();
    if ($checkCurPage == 4) {
        echo "<script>window.location.href='".$siteUrl."'</script>";
        exit;
    }

    if( !isset($_SESSION['id']) ||  $_SESSION['id'] < 1){
        echo "<script>window.location.href='".$siteUrl."';</script>";
        exit;
    }

    if (isset($_GET['assignId']) && $_GET['assignId']) { 
        $sql = " SELECT * FROM tbl_mobile_time_track WHERE stockTakeId = '".$_GET['stockTakeId']."' AND account_id = '".$_SESSION['accountId']."' ";
        $result = mysqli_query($con, $sql);
        $qryRow = mysqli_fetch_array($result);
        
        if ($qryRow['status'] == 1) {
            echo "<script>window.location.href='".$mobileSiteUrl."'issueOut1.php?issueOutAlreadyDone=1'</script>";die();  
        }

        $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['stockTakeId']."' AND account_id = '".$_SESSION['accountId']."' ";
        $result = mysqli_query($con, $sql);
        $qryRow = mysqli_fetch_array($result);
        
        if ($qryRow['status'] == 2) {
            echo "<script>window.location.href='issueOut1.php?issueOutDone=1'</script>";die();
        }
    }

    if( isset( $_GET['finish'] ) ){
        trackStockProcessTime($_GET['stockTakeId'], 4, $_SESSION['id'], 1);
    }

    //get temp data
    $sql=" SELECT * FROM  tbl_mobile_items_temp WHERE stockTakeId = '".$_GET['stockTakeId']."' AND stockTakeType=4 AND `userId` = '".$_SESSION['id']."' AND status=0  AND account_id = '".$_SESSION['accountId']."'  ";
    $result = mysqli_query($con, $sql);
    $tempItemRows = [];
    
    while($row = mysqli_fetch_array($result) ){
        $tempItemRows[$row['pId']] = $row['qty'];
    }	

    $tempItemCnt = count($tempItemRows);

			
    $sqlSet = " SELECT o.*, count(*) totalItem FROM tbl_order_details d INNER JOIN tbl_orders o ON(o.id = d.ordId) AND o.account_id = d.account_id WHERE o.id  = '".$_GET['stockTakeId']."'  AND o.account_id = '".$_SESSION['accountId']."' AND d.pId > 0 GROUP BY d.ordId ";
    $resultSet = mysqli_query($con, $sqlSet);
    $orderDet = mysqli_fetch_array($resultSet);
    $totalItems = $orderDet['totalItem'];

    $sqlSet = " SELECT o.*, count(*) totalItem FROM tbl_order_details d INNER JOIN tbl_orders o ON(o.id = d.ordId) AND o.account_id = d.account_id WHERE o.id  = '".$_GET['stockTakeId']."'  AND o.account_id = '".$_SESSION['accountId']."' AND d.pId > 0 GROUP BY d.ordId ";
    $resultSet = mysqli_query($con, $sqlSet);
    $orderDet = mysqli_fetch_array($resultSet);

    $sqlSet = " SELECT * FROM tbl_deptusers WHERE id = '".$orderDet['recMemberId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
    $resultSet = mysqli_query($con, $sqlSet);
    $mebRes = mysqli_fetch_array($resultSet);

    $sql=" SELECT * FROM  tbl_mobile_time_track WHERE stockTakeId = '".$_GET['stockTakeId']."' AND `userId` = '".$_SESSION['id']."' AND `stockTakeType` = 4 AND status=0  AND account_id = '".$_SESSION['accountId']."' ";
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
    <title><?php echo showOtherLangText('Issue OutOrder') ?> 4 - Queue1 Mobile</title>
    <?php include('layout/mCss.php'); ?>
</head>
<body>
    <section class="headSec">
        <div class="container">
            <?php include('layout/mHeader.php'); ?>
            <div class="d-flex align-items-center justify-content-between pt-3 upldClm">
                <a href="<?php echo $mobileSiteUrl;?>issueOut5.php?assignId=<?php echo $_GET['assignId']; ?>&stockTakeId=<?php echo $_GET['stockTakeId'];?>"" class="eyeBtn"><i class="fa-solid fa-eye"></i></a>
                <a href="<?php echo $mobileSiteUrl;?>issueOut6.php?finish=1&stockTakeId=<?php echo $_GET['stockTakeId'];?>&assignId=<?php echo $_GET['assignId']?>" class="fnshBtn"><?php echo showOtherLangText('Finish and Upload') ?> <img src="Assets/icons/download.svg" alt="Finish"></a>
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
                        <p><?php echo showOtherLangText('Order Number') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><?php echo $orderDet['ordNumber'];?></p>
                    </div>
                </div>

                <div class="row align-tems-center taskDetail pt-3">
                    <div class="col-md-6">
                        <p><?php echo showOtherLangText('Date') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="fw-500">
                            <?php echo date('d/m/Y', strtotime($orderDet['ordDateTime'])); ?>
                        </p>
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
					$diff =  round(abs($to_time - $from_time) / 60)." ".showOtherLangText('min');
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