<?php 
    include('../inc/dbConfig.php'); //connection details
    $pgnm = 'Issue Out';
    $issueOutStep = '5';

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
            echo "<script>window.location.href='".$mobileSiteUrl."'issueOut1.php?issueOutDone=1'</script>";die();
        }
    }


    //get temp data
    $sql=" SELECT * FROM  tbl_mobile_items_temp WHERE stockTakeId = '".$_GET['stockTakeId']."' AND stockTakeType=4 AND `userId` = '".$_SESSION['id']."' AND status=0  AND account_id = '".$_SESSION['accountId']."'  ";
    $result = mysqli_query($con, $sql);
    $tempItemRows = [];
    
    while($row = mysqli_fetch_array($result) )
    {
        $tempItemRows[$row['pId']] = $row['qty'];
    }	

			
    $sqlSet = " SELECT o.*, count(*) totalItem FROM tbl_order_details d INNER JOIN tbl_orders o ON(o.id = d.ordId) AND o.account_id = d.account_id WHERE o.id  = '".$_GET['stockTakeId']."'  AND o.account_id = '".$_SESSION['accountId']."' AND d.pId > 0  GROUP BY d.ordId ";
    $resultSet = mysqli_query($con, $sqlSet);
    $orderDet = mysqli_fetch_array($resultSet);

    $sqlSet = " SELECT * FROM tbl_deptusers WHERE id = '".$orderDet['recMemberId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
    $resultSet = mysqli_query($con, $sqlSet);
    $mebRes = mysqli_fetch_array($resultSet);


    $sql = "SELECT tp.*, od.id ordDetId,  od.price ordPrice, od.curPrice,  od.qty ordQty, od.totalAmt, od.factor ordFactor, IF(u.name!='',u.name,tp.unitC) countingUnit  FROM tbl_order_details od 
	INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
	LEFT JOIN tbl_units u ON(u.id=tp.unitC) AND u.account_id = tp.account_id
    WHERE od.ordId = '".$_GET['stockTakeId']."' AND tp.account_id = '".$_SESSION['accountId']."' ORDER BY tp.storageDeptId DESC ";
    $ordQry = mysqli_query($con, $sql);
							
?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title><?php echo showOtherLangText('Issue OutOrder') ?> 5 - Queue1 Mobile</title>
    <?php include('layout/mCss.php'); ?>
</head>
<body>
    <section class="headSec">
        <div class="container">
            <?php include('layout/mHeader.php'); ?>
            <div class="text-center">
                <a href="<?php echo $mobileSiteUrl;?>issueOut6.php?finish=1&stockTakeId=<?php echo $_GET['stockTakeId'];?>&assignId=<?php echo $_GET['assignId']?>" class="fnshBtn fnshUpldBtn"><?php echo showOtherLangText('Finish and Upload') ?> <img src="Assets/icons/download.svg" alt="Finish"></a>
            </div>
        </div>
    </section>

    <section class="prdctSection">
        <div class="container">
            <?php
			    $x=0;
				while($row = mysqli_fetch_array($ordQry)){
					$x++;
					$curDet = getCurrencyDet($row['curAmt']);
                    $productImage = 'https://cdn-icons-png.flaticon.com/512/68/68958.png"';
                    if( $row['imgName'] != ''){	
                        $productImage = $siteUrl.'uploads/'.$accountImgPath.'/products/'.$row['imgName'];
                    }
				    ?> 
                    <div class="productList d-flex align-items-center mt-2">
                        <div class="prdctImg">
                            <img src="<?php echo $productImage;?>" alt="Product">
                        </div>
                        <div class="prdctDtl">
                            <p class="prdName"><?php echo $row['itemName'];?></p>
                            <p class="prdBarCode"><?php echo $row['barCode'];?></p>
                        </div>
                        <div class="prdctHide">&nbsp;</div>
                        <div class="prdctValue fnPrd-Val">
                            <p class="prdUnit"><span class="unitNum"><?php echo isset($tempItemRows[$row['id']]) ? $tempItemRows[$row['id']] : $row['ordQty'];?></span> <?php echo $row['countingUnit'];?></p>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div>
    </section>
    <?php include('layout/mJs.php'); ?>
</body>
</html>