<?php
    include('../inc/dbConfig.php'); //connection details
    $pgnm = 'Bar Control';
    $barControlStep = '2';

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

    if( $_GET['revenueId'] < 1 || $_GET['stockTakeId'] < 1 ){
        header('location: barControl1.php');
        exit;
    }

    if( isset( $_GET['start'] ) ){
        trackStockProcessTime($_GET['stockTakeId'], 5, $_SESSION['id']);
    }

    //get temp data
    $sql=" SELECT * FROM  tbl_mobile_items_temp WHERE stockTakeId = '".$_GET['stockTakeId']."' AND stockTakeType=5 AND `userId` = '".$_SESSION['id']."'  AND account_id = '".$_SESSION['accountId']."'   ";
    $result = mysqli_query($con, $sql);
    $tempItemRows = [];
    
    while($row = mysqli_fetch_array($result) ){
        $tempItemRows[$row['pId']] = $row['qty'];
    }	

    $totalItems = getOutLetItemsCount($_GET['stockTakeId']);
    $stockTakeRow = getRevenueOutLetDetailsById($_GET['stockTakeId']);

    $sql = "SELECT IF(u.name!='',u.name,o.subUnit) subUnit, p.* FROM tbl_outlet_items o 
    INNER JOIN tbl_products p ON(p.id = o.pId) AND (p.account_id=o.account_id) AND o.status=1 
    LEFT JOIN tbl_units u ON(u.id = o.subUnit) AND (u.account_id=o.account_id) 
    WHERE o.outLetId = '".$_GET['stockTakeId']."' AND p.account_id = '".$_SESSION['accountId']."'  AND o.itemType=1 order by p.itemName ";
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
            <div class="row align-items-center pt-3">
                <div class="col-md-6 mblSrchClm d-flex align-items-center">
                    <div class="input-group srchBx" style="border-color: rgb(213, 214, 221);">
                        <input type="search" class="form-control" placeholder="<?php echo showOtherLangText('Search') ?>" id="search" aria-label="Search" onKeyUp="myFunction()">
                        <div class="input-group-append">
                            <button class="btn" type="button" style="background-color: rgb(122, 137, 255);">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="resetClm">
                        <a href="javascript:void(0)" class="resetBtn" onClick="showMsg();"><i class="fa-solid fa-ellipsis"></i></a>
                    </div>
                </div>
                <div class="col-md-6 mblFnsClm d-flex justify-content-end">
                    <div class="d-flex align-items-center fnshGrp">
                        <p class="countValue">
                            <span class="countedItm" id="countedId">
                                <?php echo count($tempItemRows);?>
                            </span>
                            <span>/<?php echo $totalItems;?></span>
                        </p>
                        <a href="<?php echo $mobileSiteUrl;?>barControl3.php?stockTakeId=<?php echo $_GET['stockTakeId'];?>&revenueId=<?php echo $_GET['revenueId'];?>&finish=1" class="finishBtn"><?php echo showOtherLangText('Finish') ?> <span><i class="fa-solid fa-chevron-right"></i></span> </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="prdctSection">
        <div class="container">
            <table id="tableId" style="width: 100%;">
                <?php 
			        while($res = mysqli_fetch_array($stockMainQry)){
                        $productImage = 'https://cdn-icons-png.flaticon.com/512/68/68958.png';
                        if( $res['imgName'] != ''){
                            $productImage = $siteUrl.'uploads/'.$accountImgPath.'/products/'.$res['imgName'];
                        }
                        ?>
                        <tr>
                            <td>
                                <div class="productList d-flex align-items-center mt-2">
                                    <div class="prdctImg">
                                        <img src="<?php echo $productImage;?>" alt="Product">
                                    </div>
                                    <div class="prdctDtl">
                                        <p class="prdName"><?php echo $res['itemName'];?></p>
                                        <p class="prdBarCode"><?php echo $res['barCode'];?></p>
                                    </div>
                                    <div class="prdctHide">&nbsp;</div>
                                    <div class="prdctValue">
                                        <input type="text" value="<?php echo isset($tempItemRows[$res['id']]) ? $tempItemRows[$res['id']] : '';?>" class="form-control prdForm input-style" placeholder="" onChange="saveQty('<?php echo $res['id'];?>', this.value);">
                                        <p class="prdUnit"><?php echo $res['subUnit'];?></p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                ?>
            </table>
        </div>
    </section>
    <?php include('layout/mJs.php'); ?>
    <script language="javascript" type="text/javascript">
        function showMsg(){
            let rs = confirm('<?php echo showOtherLangText('Are you sure to Reset this page counting data?') ?>');

            if(rs){
                deleteCount();
            }
        }

        function saveQty(pId, qty) {
            $.ajax({
                method: "POST",
                url: "ajax.php",
                data: {
                    stockTakeId: '<?php echo $_GET['stockTakeId'];?>',
                    pId: pId,
                    qty: qty,
                    stockTakeType: '5'
                }
            })
            .done(function(counted) {

                $('#countedId').text(counted);
            });
        }

        function myFunction() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("search");
            filter = input.value.toUpperCase();
            table = document.getElementById("tableId");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the  query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function resetData() {
            $('#search').val('');
            myFunction();
        }

        function deleteCount() {
            // if (!confirm('<?php echo showOtherLangText('Are you sure to Reset this page counting data?') ?>')) {
            //     return false;
            // }

            resetData();

            $('.input-style').val('');
            $('.qtyCnt').text('');

            $.ajax({
                method: "POST",
                url: "ajax.php",
                data: {
                    resetCount: '1',
                    stockTakeId: '<?php echo $_GET['stockTakeId'];?>',
                    stockTakeType: '5'
                }
            })
            .done(function() {
                $('#countedId').text('0');
            });
        }
    </script>
</body>
</html>