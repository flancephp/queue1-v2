<?php
    include('../inc/dbConfig.php'); //connection details
    $pgnm = 'Outlet Stocktacking';
    $outletStocktackingStep = '5';

    //Get language Type 
    $getLangType = getLangType($_SESSION['language_id']);

    $checkCurPage = checkCurPage();
    if ($checkCurPage == 2) {
        echo "<script>window.location.href='".$siteUrl."'</script>";
        exit;
    }

    if( !isset($_SESSION['id']) ||  $_SESSION['id'] < 1){
        echo "<script>window.location.href='".$siteUrl."';</script>";
        exit;
    }

    $totalItems = getOutLetItemsCount($_GET['stockTakeId']);
    $stockTakeRow = getRevenueOutLetDetailsById($_GET['stockTakeId']);

    $sql = "SELECT IF(u.name!='',u.name,IF(o.itemType=1, tp.unitC, o.subUnit)) subUnit, s.qty stockQty, tp.* FROM tbl_mobile_items_temp s 
    INNER JOIN tbl_products tp ON(s.pId = tp.id) AND s.account_id = tp.account_id AND tp.status=1 AND s.stockTakeId = '".$_GET['stockTakeId']."' AND s.userId='".$_SESSION['id']."' AND s.stockTakeType=2
    INNER JOIN tbl_outlet_items o ON(o.pId = s.pId) AND o.account_id = s.account_id and o.outLetId='".$_GET['stockTakeId']."' AND o.status=1
    LEFT JOIN tbl_units u ON(u.id=IF(o.itemType=1, tp.unitC, o.subUnit)) AND u.account_id = o.account_id
    WHERE tp.account_id = '".$_SESSION['accountId']."'  ORDER by tp.itemName  ";
        
    $stockMainQry = mysqli_query($con, $sql);
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
                <a href="<?php echo $mobileSiteUrl;?>outletStocktaking6.php?stockTakeId=<?php echo $_GET['stockTakeId'];?>" class="fnshBtn fnshUpldBtn"><?php echo showOtherLangText('Finish and Upload') ?> <img src="<?php echo $mobileSiteUrl;?>Assets/icons/download.svg" alt="Finish"></a>
            </div>
        </div>
    </section>
    <section class="prdctSection">
        <div class="container">
            <?php 
			    while($res = mysqli_fetch_array($stockMainQry)){
                    $productImage = 'https://cdn-icons-png.flaticon.com/512/68/68958.png';

                    if( $res['imgName'] != ''){
                        $productImage = $siteUrl.'uploads/'.$accountImgPath.'/products/'.$res['imgName'];
                    }
			        ?>
                    <div class="productList d-flex align-items-center mt-2">
                        <div class="prdctImg">
                            <img src="<?php echo $productImage;?>" alt="Product">
                        </div>
                        <div class="prdctDtl">
                            <p class="prdName"><?php echo $res['itemName'];?></p>
                            <p class="prdBarCode"><?php echo $res['barCode'];?></p>
                        </div>
                        <div class="prdctHide">&nbsp;</div>
                        <div class="prdctValue fnPrd-Val">
                            <p class="prdUnit"><span class="unitNum"><?php echo  $res['stockQty'];?></span> <?php echo $res['subUnit'];?></p>
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