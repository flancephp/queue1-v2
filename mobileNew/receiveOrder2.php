<?php 
    include('../inc/dbConfig.php'); //connection details
    $pgnm = 'Receiving';
    $receivingStep = '2';

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

    if (isset($_GET['assignId']) && $_GET['assignId']) {
        $sql = " SELECT * FROM tbl_mobile_time_track WHERE stockTakeId = '".$_GET['stockTakeId']."' AND account_id = '".$_SESSION['accountId']."' ";
        $result = mysqli_query($con, $sql);
        $qryRow = mysqli_fetch_array($result);
        if ($qryRow['status'] == 1) {
            echo "<script>window.location.href='".$mobileSiteUrl."'receiveOrder1.php?receivingAlreadyDone=1'</script>";die();
        }
    }

    $sql = " SELECT * FROM `tbl_order_assigned_users` where orderType = 1 AND userId = '".$_SESSION['id']."' and id='".$_GET['assignId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
    $assignQry = mysqli_query($con, $sql);
    $assignRow = mysqli_fetch_array($assignQry);

    if(!$assignRow){
        header('location: receiveOrder1.php');
        exit;
    }

    $sqlSet = "SELECT o.*, count(*) totalItem FROM tbl_order_details d 
                INNER JOIN tbl_orders o 
                ON(o.id = d.ordId) AND o.account_id = d.account_id 
                WHERE o.id  = '".$assignRow['orderId']."' AND o.account_id = '".$_SESSION['accountId']."' AND d.pId > 0 GROUP BY d.ordId ";
    $resultSet = mysqli_query($con, $sqlSet);
    $orderDet = mysqli_fetch_array($resultSet);

    $sqlSet = " SELECT GROUP_CONCAT(DISTINCT(s.name)) suppliers FROM tbl_orders o 
                INNER JOIN tbl_suppliers s 
                ON(o.supplierId = s.id) AND o.account_id = s.account_id 
                WHERE o.id =".$assignRow['orderId']." AND o.account_id = '".$_SESSION['accountId']."' ";
    $resultSet = mysqli_query($con, $sqlSet);
    $supRow = mysqli_fetch_array($resultSet);

    $sql = "SELECT tp.*, od.price ordPrice, od.curPrice,  od.curAmt, od.currencyId, od.qty ordQty, od.totalAmt, od.factor ordFactor, IF(u.name!='',u.name,tp.unitP) purchaseUnit FROM tbl_order_details od 
    INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id 
    LEFT JOIN tbl_units u ON(u.id=tp.unitP) AND u.account_id = tp.account_id 
    WHERE od.ordId = '".$assignRow['orderId']."' AND tp.account_id = '".$_SESSION['accountId']."' ORDER BY tp.storageDeptId DESC  ";
    $ordQry = mysqli_query($con, $sql);  
    
    $orderSql = " SELECT * FROM tbl_orders WHERE id = '".$assignRow['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $orderResult = mysqli_query($con, $orderSql);
    $orderQry = mysqli_fetch_array($orderResult);

    
   
        $sqlQry = " SELECT SUM(totalAmt) AS totalProductAmt FROM tbl_order_details WHERE ordId='".$assignRow['orderId']."' AND account_id='".$_SESSION['accountId']."' AND pId > 0 ";
        $sqlResult = mysqli_query($con, $sqlQry);
        $resultRow = mysqli_fetch_array($sqlResult);
        $totalProductAmt = $resultRow['totalProductAmt'];

        $subTotal = getPriceWithCur($totalProductAmt,$getDefCurDet['curCode']);
        $extraCharges = getPriceWithCur($orderDet['ordAmt']-$totalProductAmt,$getDefCurDet['curCode']);
        $netTotal = getPriceWithCur($orderDet['ordAmt'],$getDefCurDet['curCode']);
    

        $subTotalOtherCur = '';
        $extraChargesOtherCur = '';
        $netTotalOtherCur = '';

        if ($orderQry['ordCurId'] > 0) 
        {
            $sqlQry = " SELECT SUM(curAmt) AS totalProductAmt FROM tbl_order_details WHERE ordId='".$assignRow['orderId']."' AND account_id='".$_SESSION['accountId']."' AND pId > 0 ";
            $sqlResult = mysqli_query($con, $sqlQry);
            $resultRow = mysqli_fetch_array($sqlResult);
            $totalProductAmt = $resultRow['totalProductAmt'];
    
    
            $subTotalOtherCur = showOtherCur($totalProductAmt,$orderQry['ordCurId']);
            $extraChargesOtherCur = showOtherCur($orderDet['ordCurAmt']-$totalProductAmt,$orderQry['ordCurId']);
            $netTotalOtherCur = showOtherCur($orderDet['ordCurAmt'],$orderQry['ordCurId']);
        } 
?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title><?php echo showOtherLangText('Receiving order') ?> 2 - Queue1 Mobile</title>
    <?php include('layout/mCss.php'); ?>
</head>
<body>
    <section class="headSec">
        <div class="container">
            <?php include('layout/mHeader.php'); ?>
            <div class="text-center">
                <a href="<?php echo $mobileSiteUrl;?>receiveOrder3.php?start=1&assignId=<?php echo $_GET['assignId'];?>&stockTakeId=<?php echo $_GET['stockTakeId']; ?>" class="stockTake-Btn"><?php echo showOtherLangText('Start Receiving'); ?></a>
            </div>
        </div>
    </section>

    <section class="storeSection">
        <div class="container">
            <div class="storeDtl receiveStore">
                <div class="inner__content bg-white">
                    <div class="row g-0 receiveRow">
                        <div class="col-md-6">
                            <p class="recQuantity"><span>0</span>/<?php echo $orderDet['totalItem'];?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="recDate"><?php echo date('d/m/Y', strtotime($orderDet['ordDateTime']));?></p>
                        </div>
                    </div>
                    <div class="mblOrd-ttl d-flex pt-2">
                        <p class="ordCol1"><?php echo showOtherLangText('Net Total') ?>:</p>
                        <p class="ordCol2"><?php echo $netTotal;?></p>
                        <?php echo $netTotalOtherCur ? '<p class="ordCol2">'.$netTotalOtherCur.'</p>' : '';?>
                    </div>
                    <div class="mblOrd-Subttl d-flex pt-1">
                        <p class="ordCol1"><?php echo showOtherLangText('Sub Total') ?>:</p>
                        <p class="ordCol2"><?php echo $subTotal;?></p>
                        <?php echo $subTotalOtherCur ? '<p class="ordCol2">'.$subTotalOtherCur.'</p>' : '';?>
                        
                    </div>
                    <div class="mblOrd-extra d-flex pt-1">
                        <p class="ordCol1"><?php echo showOtherLangText('Extra charges') ?>:</p>
                        <p class="ordCol2"><?php echo $extraCharges;?></p>
                        <?php echo $extraChargesOtherCur ? '<p class="ordCol2">'.$extraChargesOtherCur.'</p>' : '';?>
                    </div>
    
                    <div class="receiveProduct">
                        <?php
                            while($row = mysqli_fetch_array($ordQry)){
                                $productImage = 'https://cdn-icons-png.flaticon.com/512/68/68958.png';
                                if( $row['imgName'] != ''){	
                                    $productImage = $siteUrl.'uploads/'.$accountImgPath.'/products/'.$row['imgName'];
                                }
                                ?>
                                <div class="recPrd-List d-flex align-items-center mt-3">
                                    <div class="prdctImg">
                                        <img src="<?php echo $productImage;?>" alt="<?php echo $row['itemName'];?>">
                                    </div>
                                    <div class="prdctDtl">
                                        <p class="prdName"><?php echo $row['itemName'];?></p>
                                        <p class="prdBarCode"><?php echo $row['barCode'];?></p>
                                    </div>
                                    <div class="prdctHide">&nbsp;</div>
                                    <div class="prdctValue fnPrd-Val">
                                        <p class="recUnit"><span class="unitNum"><?php echo $row['ordQty'];?></span> <?php echo $row['purchaseUnit'];?></p>

                                        <p class="recPrice">
                                       
                                        <?php echo getPrice($row['totalAmt']).' '.$getDefCurDet['curCode'];?></p>

                                        
                                        <?php echo ($row['currencyId'] > 0) ? ('<p class="recPrice">'.showOtherCur($row['curAmt'], $row['currencyId']).'</p>') : '';?>    
                                        
                                    </div>
                                </div>
                                <?php
                            }
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <?php include('layout/mJs.php'); ?>
</body>
</html>