<?php 
    include('../inc/dbConfig.php'); //connection details
    $pgnm = 'Issue Out';
    $issueOutStep = '6';

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
            echo "<script>window.location.href='".$mobileSiteUrl."'issueOut1.php?issueOutAlreadyDone=1'</script>"; die(); 
        }

        $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['stockTakeId']."' AND account_id = '".$_SESSION['accountId']."' ";
        $result = mysqli_query($con, $sql);
        $qryRow = mysqli_fetch_array($result);
        
        if ($qryRow['status'] == 2) {
            echo "<script>window.location.href='".$mobileSiteUrl."'issueOut1.php?issueOutDone=1'</script>";die();
        }
    
    }

    if( isset( $_GET['stockTakeId'] ) )
    {
        finishStockTake($_GET['stockTakeId'], 4, $_SESSION['id']);

        //get all value of temp data for insert into tbl_log
        $sql=" SELECT * FROM  tbl_mobile_items_temp WHERE stockTakeId = '".$_GET['stockTakeId']."' AND `userId` = '".$_SESSION['id']."' AND status=1  AND account_id = '".$_SESSION['accountId']."'  ";
        $result = mysqli_query($con, $sql);
        $dataRows[] = '';
    
        while($row = mysqli_fetch_array($result)){
            $dataRows[]=("stockTakeType:".$row['stockTakeType']. "stockTakeId:".$row['stockTakeId']. "pId:".$row['pId']. "qty:".$row['qty']);
        }
        
        //insertion into log table  
        $allPostData = $dataRows;
        $jsonData =  json_encode($allPostData);
        $pageName = 'Mobile';
        $subSection = 'Issuing Out';

        $insQry = " INSERT INTO tbl_log SET 
                        accountId = '".$_SESSION['accountId']."',
                        section = '".$pageName."',
                        subSection = '".$subSection."',
                        logData = '".$jsonData."',
                        userId = '".$_SESSION['id']."',
                        date = '".date('Y-m-d H:i:s')."'  ";

        mysqli_query($con, $insQry);
        //end of insertion into log table            
    }

    $sqlSet = " SELECT o.*, count(*) totalItem FROM tbl_order_details d INNER JOIN tbl_orders o ON(o.id = d.ordId) AND o.account_id = d.account_id WHERE o.id  = '".$_GET['stockTakeId']."' AND o.account_id = '".$_SESSION['accountId']."' AND d.pId > 0  GROUP BY d.ordId ";
    $resultSet = mysqli_query($con, $sqlSet);
    $orderDet = mysqli_fetch_array($resultSet);

    $sqlSet = " SELECT * FROM tbl_deptusers WHERE id = '".$orderDet['recMemberId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
    $resultSet = mysqli_query($con, $sqlSet);
    $mebRes = mysqli_fetch_array($resultSet);

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
                <p>OutLet stock take updated successfully.</p>
                <a href="<?php echo $mobileSiteUrl;?>index.php" class="homeBtn"><i class="fa-solid fa-arrow-left"></i> <span><?php echo showOtherLangText('Home') ?></span></a>
            </div>
        </div>
    </section>
    <?php include('layout/mJs.php'); ?>
</body>
</html>