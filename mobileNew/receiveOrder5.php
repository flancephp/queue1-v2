<?php
include('../inc/dbConfig.php'); //connection details
$pgnm = 'Receiving';
$receivingStep = '5';

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$checkCurPage = checkCurPage();
if ($checkCurPage == 3) {
    echo "<script>window.location.href='" . $siteUrl . "'</script>";
    exit;
}

if (!isset($_SESSION['id']) ||  $_SESSION['id'] < 1) {
    echo "<script>window.location.href='" . $siteUrl . "';</script>";
    exit;
}

if (isset($_GET['assignId']) && $_GET['assignId']) {
    $sql = " SELECT * FROM tbl_mobile_time_track WHERE stockTakeId = '" . $_GET['stockTakeId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $result = mysqli_query($con, $sql);
    $qryRow = mysqli_fetch_array($result);

    if ($qryRow['status'] == 1) {
        echo "<script>window.location.href='" . $mobileSiteUrl . "'receiveOrder1.php?receivingDone=1'</script>";
        die();
    }

    $sql = " SELECT * FROM tbl_orders WHERE id = '" . $_GET['stockTakeId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $result = mysqli_query($con, $sql);
    $qryRow = mysqli_fetch_array($result);

    if ($qryRow['status'] == 2) {
        echo "<script>window.location.href='" . $mobileSiteUrl . "'receiveOrder1.php?receivingAlreadyDone=1'</script>";
        die();
    }
}

//get temp data
$sql = " SELECT * FROM  tbl_mobile_items_temp WHERE stockTakeId = '" . $_GET['stockTakeId'] . "' AND stockTakeType=3 AND `userId` = '" . $_SESSION['id'] . "' AND status=0  AND account_id = '" . $_SESSION['accountId'] . "'  ";
$result = mysqli_query($con, $sql);
$tempItemRows = [];

while ($row = mysqli_fetch_array($result)) {
    $tempItemRows[$row['pId']]['qty'] = $row['qty'];
    $tempItemRows[$row['pId']]['amt'] = $row['amt'];
    $tempItemRows[$row['pId']]['curId'] = $row['curId'];
}

$sqlSet = " SELECT o.*, count(*) totalItem FROM tbl_order_details d 
                INNER JOIN tbl_orders o 
                ON(o.id = d.ordId) AND o.account_id = d.account_id 
                WHERE o.id  = '" . $_GET['stockTakeId'] . "'  AND o.account_id = '" . $_SESSION['accountId'] . "' AND d.pId > 0  GROUP BY d.ordId ";
$resultSet = mysqli_query($con, $sqlSet);
$orderDet = mysqli_fetch_array($resultSet);

$sqlSet = " SELECT GROUP_CONCAT(DISTINCT(s.name)) suppliers FROM tbl_orders o 
                INNER JOIN tbl_suppliers s 
                ON(o.supplierId = s.id) AND o.account_id = s.account_id 
                WHERE o.id =" . $_GET['stockTakeId'] . " AND s.account_id = '" . $_SESSION['accountId'] . "' ";
$resultSet = mysqli_query($con, $sqlSet);
$supRow = mysqli_fetch_array($resultSet);

$sql = "SELECT tp.*, od.curAmt, od.currencyId, od.id ordDetId, od.price ordPrice, od.curPrice, od.qty ordQty, od.totalAmt, od.factor ordFactor, IF(u.name!='',u.name,tp.unitP) purchaseUnit FROM tbl_order_details od 
    INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id 
    LEFT JOIN tbl_units u ON(u.id=tp.unitp) AND u.account_id = tp.account_id 
    WHERE od.ordId = '" . $_GET['stockTakeId'] . "' AND tp.account_id = '" . $_SESSION['accountId'] . "' ORDER BY tp.storageDeptId DESC ";
$ordQry = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title><?php echo showOtherLangText('Receiving order') ?> 5 - Queue1 Mobile</title>
    <?php include('layout/mCss.php'); ?>
</head>

<body>
    <section class="headSec">
        <div class="container">
            <?php include('layout/mHeader.php'); ?>
            <div class="text-center">
                <a href="<?php echo $mobileSiteUrl; ?>receiveOrder6.php?finish=1&assignId=<?php echo $_GET['assignId']; ?>&stockTakeId=<?php echo $_GET['stockTakeId']; ?>" class="fnshBtn fnshUpldBtn"><?php echo showOtherLangText('Finish and Upload') ?> <img src="Assets/icons/download.svg" alt="Finish"></a>
            </div>
        </div>
    </section>

    <section class="prdctSection">
        <div class="container">

            <?php

            $ordCurId = $orderDet['ordCurId'];
            $sqlSet = " SELECT * FROM tbl_currency where id = '" . $ordCurId . "'  ";
            $resultSet = mysqli_query($con, $sqlSet);
            $curRes = mysqli_fetch_array($resultSet);

            ?>

            <?php
            $x = 0;
            while ($row = mysqli_fetch_array($ordQry)) {
                $x++;

                $productImage = 'https://cdn-icons-png.flaticon.com/512/68/68958.png';
                if ($row['imgName'] != '') {
                    $productImage = $siteUrl . 'uploads/' . $accountImgPath . '/products/' . $row['imgName'];
                }
            ?>
                <div class="productList d-flex align-items-center mt-3">
                    <div class="prdctImg">
                        <img src="<?php echo $productImage; ?>" alt="Product">
                    </div>
                    <div class="prdctDtl">
                        <p class="prdName"><?php echo $row['itemName']; ?></p>
                        <p class="prdBarCode"><?php echo $row['barCode']; ?></p>
                    </div>
                    <div class="prdctHide">&nbsp;</div>
                    <div class="prdctValue fnPrd-Val">
                        <p class="prdUnit">
                            <span class="unitNum">
                                <?php echo isset($tempItemRows[$row['id']]) ? $tempItemRows[$row['id']]['qty'] : $row['ordQty']; ?>
                            </span>
                            <?php echo $row['purchaseUnit']; ?>
                        </p>

                        <p class="recOrd-Price">



                            <?php

                            ///echo $getDefCurDet['id'] . '==' . $curDet['id'];

                            if (isset($tempItemRows[$row['id']])) { //if single currency

                                if ($row['currencyId'] > 0) { //two currency

                                    $price = $tempItemRows[$row['id']]['amt'] * $tempItemRows[$row['id']]['qty'];

                                    $defaultPriceVal = $getDefCurDet['id'] == $tempItemRows[$row['id']]['curId'] ? ($price) : ($price / $curRes['amt']);
                                    $ootherPriceVal = $getDefCurDet['id'] == $tempItemRows[$row['id']]['curId'] ? ($price * $curRes['amt']) : ($price);


                                    echo '<p class="recOrd-Price">' . getPrice($defaultPriceVal) . ' ' . $getDefCurDet['curCode'] . '</p>';

                                    echo '<p class="recOrd-Price">' . getPrice($ootherPriceVal) . ' ' . $curRes['curCode'] . '</p>';
                                } else { //if single currency
                                    $price = $tempItemRows[$row['id']]['amt'] * $tempItemRows[$row['id']]['qty'];

                                    echo '<p class="recOrd-Price">' . getPrice($price) . ' ' . $getDefCurDet['curCode'] . '</p>';
                                }
                            } else { //if multi currency

                                echo  '<p class="recOrd-Price">' . getPrice($row['totalAmt']) . ' ' . $getDefCurDet['curCode'] . '</p>';

                                echo ($row['currencyId'] > 0) ? ('<p class="recOrd-Price">' . showOtherCur($row['curAmt'], $row['currencyId']) . '</p>') : '';
                            } ?>




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