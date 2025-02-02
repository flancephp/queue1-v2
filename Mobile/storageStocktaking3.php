<?php
    include('../inc/dbConfig.php'); //connection details
    $pgnm = 'Storage Stocktaking';
    $storageStocktakingStep = '3';

    //Get language Type 
    $getLangType = getLangType($_SESSION['language_id']);

    $checkCurPage = checkCurPage();
    if ($checkCurPage == 1) {
        echo "<script>window.location.href='".$siteUrl."'</script>";
        exit;
    }

    if( $_GET['storageId'] < 1){
        header('location: storageStocktaking1.php');
        exit;
    }

    if( isset( $_GET['start'])){
        trackStockProcessTime($_GET['storageId'], 1, $_SESSION['id']);
    }

    $totalItems = getStorageItemsCount($_GET['storageId']);

    //get temp data
    $sql=" SELECT * FROM  tbl_mobile_items_temp WHERE stockTakeId = '".$_GET['storageId']."' AND stockTakeType=1 AND `userId` = '".$_SESSION['id']."' AND status=0  AND account_id = '".$_SESSION['accountId']."'  ";
    $result = mysqli_query($con, $sql);
    $tempItemRows = [];
    
    while($row = mysqli_fetch_array($result)){
        $tempItemRows[$row['pId']] = $row['qty'];
    }	

    $cond = " where  storageDeptId = '".$_GET['storageId']."' ";
    $storageDeptRow = getStoreDetailsById($_GET['storageId']);

    $sql = "SELECT tp.*, s.qty stockQty, IF(u.name!='',u.name,tp.unitC) countingUnit FROM tbl_stocks s 
    INNER JOIN tbl_products tp ON(s.pId = tp.id) AND s.account_id = tp.account_id AND tp.status=1  AND tp.account_id = '".$_SESSION['accountId']."'
    LEFT JOIN tbl_units u ON(u.id=tp.unitC) AND (u.account_id = tp.account_id) 
    ". $cond . " GROUP BY tp.id ORDER by tp.catId  ";

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
                        <p class="countValue"><span class="countedItm" id="countedId"><?php echo count($tempItemRows);?></span><span>/<?php echo $totalItems;?></span></p>
                        <a href="<?php echo $mobileSiteUrl;?>storageStocktaking4.php?storageId=<?php echo $_GET['storageId'];?>&finish=1" class="finishBtn"><?php echo showOtherLangText('Finish') ?> <span><i class="fa-solid fa-chevron-right"></i></span> </a>
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
                                    <p class="prdName ellipsis"><?php echo $res['itemName'];?></p>
                                    <p class="prdBarCode"><?php echo $res['barCode'];?></p>
                                </div>
                                <div class="prdctHide">&nbsp;</div>
                                <div class="prdctValue">
                                    <input type="text" id="item<?php echo $res['id'];?>" value="" class="form-control prdForm" placeholder="" onmouseout="saveQty('<?php echo $res['id'];?>', this.value);" />
                                    <input type="hidden" id="itemSaved<?php echo $res['id'];?>" value="<?php echo isset($tempItemRows[$res['id']]) ? $tempItemRows[$res['id']] : 0;?>" class="input-style" />
                                    <p class="prdUnit"><strong style="color:#00ad4c;text-align:center;cursor: pointer;" class="qtyCnt" id="qtyCnt<?php echo $res['id'];?>" onclick="toggleQty('<?php echo $res['id'];?>')"><?php echo isset($tempItemRows[$res['id']]) ? $tempItemRows[$res['id']] : '';?></strong> <?php echo $res['countingUnit'];?></p>
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
    <script type="text/javascript">
        function toggleQty(id){
            var qtySaved = $('#itemSaved'+id).val();
            $('#item'+id).val(qtySaved);
            $('#item'+id).focus();
            $('#qtyCnt'+id).text('');
            $('#itemSaved'+id).val('');
        }

        function showMsg(){
            let rs = confirm('<?php echo showOtherLangText('Are you sure to Reset this page counting data?') ?>');

            if(rs){
                deleteCount();
            }
            /*$("#dialog").dialog({  
                autoOpen  : false,
                modal     : true,
                //title     : "Title",
                buttons   : {
                    '<?php echo showOtherLangText('Yes') ?>' : function() {
                        //Do whatever you want to do when Yes clicked
                        deleteCount();
                        $(this).dialog('close');
                    },
                    '<?php echo showOtherLangText('No') ?>' : function() {
                        //Do whatever you want to do when No clicked
                        $(this).dialog('close');
                    }
                }    
            });

            $( "#dialog" ).dialog( "open" );
            $('.custom-header-text').remove();
            $('.ui-dialog-content').prepend('<div class="custom-header-text"><span><?php echo showOtherLangText('Queue1.com Says') ?></span></div>');*/
        }

        function saveQty(pId, qty) {
            var newQty = parseInt(qty);
            if (isNaN(newQty)) {
                var newQty = 0;
            }

            if(newQty == 0 || newQty == '')
            {
                return true;
            }
            var savedQty = $('#itemSaved' + pId).val();
            savedQty = Number(savedQty);

            var updatedQty = newQty + savedQty;
            if (updatedQty > 0){
                var updatedQty = updatedQty;
            }
            else{
                var updatedQty = 0;
            }
    
            $.ajax({
                method: "POST",
                url: "ajax.php",
                data: {
                    stockTakeId: '<?php echo $_GET['storageId'];?>',
                    pId: pId,
                    qty: updatedQty,
                    stockTakeType: '1'
                }
            })
            .done(function(counted) {
                $('#countedId').text(counted);
                $('#qtyCnt' + pId).text(updatedQty);
                $('#item' + pId).val('');
                $('#itemSaved' + pId).val(updatedQty);
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
                    }
                    else {
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
            // if (!confirm('<?php echo showOtherLangText('Are you sure to Reset this page counting data') ?>')) {
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
                    stockTakeId: '<?php echo $_GET['storageId'];?>',
                    stockTakeType: '1'
                }
            })
            .done(function() {
                $('#countedId').text('0');
            });
        }
    </script>
</body>
</html>