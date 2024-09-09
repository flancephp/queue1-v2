<?php 
    include('../inc/dbConfig.php'); //connection details
    $pgnm = 'Receiving';
    $receivingStep = '1';

    //Get language Type 
    $getLangType = getLangType($_SESSION['language_id']);

    $checkCurPage = checkCurPage();
    if ($checkCurPage == 3) {
        echo "<script>window.location.href='".$siteUrl."'</script>";
        exit;
    }

    if( !isset($_SESSION['id']) ||  $_SESSION['id'] < 1){
        echo "<script>window.location.href='".$siteUrl."';</script>";
        exit;
    }

    $sql = " SELECT u.* FROM `tbl_order_assigned_users` u
            INNER JOIN tbl_orders o ON(o.id = u.orderId) AND o.account_id = u.account_id 
            left join tbl_mobile_time_track t ON(t.stockTakeId = u.orderId) AND t.account_id = u.account_id AND stockTakeType=3 
            where u.orderType = 1 AND o.status=1 AND u.userId = '".$_SESSION['id']."' AND u.account_id = '".$_SESSION['accountId']."'  AND IFNULL(t.status, 0) = 0  GROUP BY u.orderId ";
    $assignQry = mysqli_query($con, $sql);
    $toalOrders = mysqli_num_rows($assignQry);
?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title><?php echo showOtherLangText('Receiving order') ?> - Queue1 Mobile</title>
    <?php include('layout/mCss.php'); ?>
</head>
<body>
    <section class="headSec">
        <div class="container">
            <?php include('layout/mHeader.php'); ?>
            <div class="text-center">
                <p class="orderNum"><?php echo $toalOrders?> <?php echo showOtherLangText('orders') ?></p>
                <br>
                <p>
                    <?php
                        if (isset($_GET['receivingAlreadyDone']) && $_GET['receivingAlreadyDone'] == 1){
                            echo "<h6 style='color: green; margin-left: 20px;'>".showOtherLangText('selected order already has been done by other assigned user')."</h6>";
                        }
                        elseif(isset($_GET['receivingDone']) && $_GET['receivingDone'] == 1){
                            echo "<h6 style='color: green; margin-left: 20px;'>".showOtherLangText('selected order already has been received')."</h6>";
                        }
                    ?>
                </p>
            </div>
        </div>
    </section>
    
    <section class="strgList">
        <div class="container">
            <?php
                if($toalOrders > 0){
                    while($assignRow = mysqli_fetch_array($assignQry)){
                        $sqlSet = " SELECT GROUP_CONCAT(DISTINCT(s.name)) suppliers FROM tbl_orders o INNER JOIN tbl_suppliers s ON(o.supplierId = s.id) AND o.account_id = s.account_id WHERE o.id=".$assignRow['orderId']." AND o.account_id = '".$_SESSION['accountId']."'  ";
                            
                        $resultSet = mysqli_query($con, $sqlSet);
                        $supRow = mysqli_fetch_array($resultSet);
                            
                        $sqlSet = " SELECT o.*, count(*) totalItem FROM tbl_order_details d INNER JOIN tbl_orders o ON(o.id = d.ordId) AND o.account_id = d.account_id WHERE o.id  = '".$assignRow['orderId']."'  AND o.account_id = '".$_SESSION['accountId']."' AND d.pId > 0  GROUP BY d.ordId ";
                            
                        $resultSet = mysqli_query($con, $sqlSet);
                        $orderDet = mysqli_fetch_array($resultSet);

                        $total = ($orderDet['ordCurId'] > 0) ? showOtherCur($orderDet['ordCurAmt'], $orderDet['ordCurId']) : getPrice($orderDet['ordAmt']).' '.$getDefCurDet['curCode'];
                    ?>
                    <a href="javascript:void(0);" class="strgProduct receivePrd mt-3" onClick="redirectToNext('<?php echo $assignRow['id'];?>', '<?php echo $assignRow['orderId'];?>');">
                        <div class="bg-white inner__content"> 
                            <div class="row g-0">
                                <div class="col-md-6 storeClm">
                                    <p class="storeName ttlStr-Cost mb-1"><?php echo showOtherLangText('Total') ?>: <span><?php echo $total;?></span></p>
                                    <p class="prdCode"># <?php echo $orderDet['ordNumber'];?></p>
                                </div>
                                <div class="col-md-6 countClm">
                                    <p class="PrdDate"><?php echo date('d/m/Y', strtotime($orderDet['ordDateTime']));?></p>
                                    <p class="prdItm-Count"><?php echo $orderDet['totalItem'];?> <?php echo showOtherLangText('Item') ?></p>
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
    <script>
	    function redirectToNext(assignId, orderId){
	  		location.href = 'receiveOrder2.php?assignId='+assignId+'&stockTakeId='+orderId;
	    }
	</script>
</body>
</html>