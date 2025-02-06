<?php
include('../inc/dbConfig.php'); //connection details
$pgnm = 'Receiving';
$receivingStep = '3';

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

$sql = " SELECT * FROM `tbl_order_assigned_users` where orderType = 1 AND userId = '" . $_SESSION['id'] . "' and id='" . $_GET['assignId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
$assignQry = mysqli_query($con, $sql);
$assignRow = mysqli_fetch_array($assignQry);

if (isset($_GET['start'])) {
    trackStockProcessTime($assignRow['orderId'], 3, $_SESSION['id']);
}

//get temp data
$sql = " SELECT * FROM  tbl_mobile_items_temp WHERE stockTakeId = '" . $assignRow['orderId'] . "' AND stockTakeType=3 AND `userId` = '" . $_SESSION['id'] . "' AND status=0  AND account_id = '" . $_SESSION['accountId'] . "'  ";
$result = mysqli_query($con, $sql);
$tempItemRows = [];
while ($row = mysqli_fetch_array($result)) {
    $tempItemRows[$row['pId']]['qty'] = $row['qty'];
    $tempItemRows[$row['pId']]['amt'] = $row['amt'];
    $tempItemRows[$row['pId']]['curId'] = $row['curId'];
}

$sqlSet = " SELECT o.*, count(*) totalItem FROM tbl_order_details d INNER JOIN tbl_orders o ON(o.id = d.ordId) AND o.account_id = d.account_id
    WHERE o.id  = '" . $assignRow['orderId'] . "'  AND o.account_id = '" . $_SESSION['accountId'] . "' AND d.pId > 0 GROUP BY d.ordId ";
$resultSet = mysqli_query($con, $sqlSet);
$orderDet = mysqli_fetch_array($resultSet);

$sqlSet = " SELECT GROUP_CONCAT(DISTINCT(s.name)) suppliers FROM tbl_orders o 
    INNER JOIN tbl_suppliers s ON(o.supplierId = s.id) AND o.account_id = s.account_id WHERE o.id =" . $assignRow['orderId'] . " AND o.account_id = '" . $_SESSION['accountId'] . "'  ";
$resultSet = mysqli_query($con, $sqlSet);
$supRow = mysqli_fetch_array($resultSet);

$sql = "SELECT tp.*, od.currencyId, od.id ordDetId,  od.price ordPrice, od.curPrice,  od.qty ordQty, od.totalAmt, od.factor ordFactor, od.curAmt, IF(u.name!='',u.name,tp.unitP) purchaseUnit FROM tbl_order_details od INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id LEFT JOIN tbl_units u ON(u.id=tp.unitp) AND u.account_id = tp.account_id WHERE od.ordId = '" . $assignRow['orderId'] . "'  AND tp.account_id = '" . $_SESSION['accountId'] . "' ORDER BY tp.storageDeptId DESC ";
$ordQry = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title><?php echo showOtherLangText('Receiving order') ?> 3 - Queue1 Mobile</title>
    <?php include('layout/mCss.php'); ?>
</head>

<body>
    <section class="headSec">
        <div class="container">
            <?php include('layout/mHeader.php'); ?>

            <div class="row align-items-center pt-3">
                <div class="col-md-6 mblSrchClm d-flex align-items-center">
                    <div class="input-group srchBx" style="border-color: rgb(213, 214, 221);">
                        <input type="search" id="search" class="form-control" placeholder="<?php echo showOtherLangText('Search') ?>" aria-label="Search" onKeyUp="myFunction()">
                        <div class="input-group-append">
                            <button class="btn" type="button" style="background-color: rgb(122, 137, 255);">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <!--<div class="resetClm">
                        <a href="javascript:void(0)" class="resetBtn" onClick="resetData();"><i class="fa-solid fa-ellipsis"></i></a>
                    </div>-->
                </div>
                <div class="col-md-6 mblFnsClm d-flex justify-content-end">
                    <div class="d-flex align-items-center fnshGrp">
                        <p class="countValue">
                            <span class="countedItm" id="countedId"><?php echo count($tempItemRows); ?></span>
                            <span>/ <?php echo $orderDet['totalItem']; ?></span>
                        </p>
                        <a href="<?php echo $mobileSiteUrl; ?>receiveOrder4.php?finish=1&assignId=<?php echo $_GET['assignId']; ?>&stockTakeId=<?php echo $assignRow['orderId']; ?>" class="finishBtn">
                            <?php echo showOtherLangText('Finish') ?>
                            <span><i class="fa-solid fa-chevron-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="storeSection">
        <div class="container">
            <div class="storeDtl receiveStore">
                <div class="inner__content bg-white">
                    <div class="row g-0 receiveRow">
                        <div class="col-md-6">
                            <?php
                            $ordCurId = $orderDet['ordCurId'];
                            $sqlSet = " SELECT * FROM tbl_currency where id = '" . $ordCurId . "' AND account_id = '" . $_SESSION['accountId'] . "'   ";
                            $resultSet = mysqli_query($con, $sqlSet);
                            $curRes = mysqli_fetch_array($resultSet);
                            //$_GET['currencyId'] = ( isset($_GET['currencyId']) && !$_GET['currencyId'] ) ? '' : $ordCurId;
                            $_GET['currencyId'] = (isset($_GET['currencyId'])) ? '' . $_GET['currencyId'] . '' : $ordCurId;
                            ?>
                            <form class="width" action="receiveOrder3.php?start=1" method="get" id="frm" name="frm">
                                <input type="hidden" value="1" name="start" />
                                <input type="hidden" value="<?php echo $_GET['assignId']; ?>" name="assignId" />
                                <input type="hidden" value="<?php echo $_GET['stockTakeId']; ?>" name="stockTakeId" />

                                <select name="currencyId" id="currencyId" onChange="this.form.submit();" class="select-box">
                                    <option value="<?php echo $getDefCurDet['id'] ?>"
                                        <?php echo  $_GET['currencyId'] == $getDefCurDet['id'] ? 'selected="selected"' : ''; ?>> <?php echo $getDefCurDet['curCode'] ?>
                                    </option>
                                    <?php
                                    $sel = $_GET['currencyId'] == $curRes['id'] ? 'selected="selected"' : '';
                                    echo !empty($curRes) ? '<option value="' . $curRes['id'] . '" ' . $sel . '>' . $curRes['curCode'] . '</option>' : '';
                                    ?>

                                </select>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <p class="recDate"><?php echo date('d/m/Y', strtotime($orderDet['ordDateTime'])); ?></p>
                        </div>
                    </div>
                    <div class="recPrdt-Div">
                        <table id="tableId" style="width: 100%;">


                            <?php
                            $_GET['currencyId'] = (isset($_GET['currencyId'])) ? '' . $_GET['currencyId'] . '' : $ordCurId;

                            $ordCurId = $orderDet['ordCurId'];
                            $sqlSet = " SELECT * FROM tbl_currency where id = '" . $_GET['currencyId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'   ";
                            $resultSet = mysqli_query($con, $sqlSet);
                            $curRes = mysqli_fetch_array($resultSet);
                            //$_GET['currencyId'] = ( isset($_GET['currencyId']) && !$_GET['currencyId'] ) ? '' : $ordCurId;


                            $curRes = $_GET['currencyId'] ? $curRes : '';

                            $x = 0;
                            while ($row = mysqli_fetch_array($ordQry)) {
                                $x++;

                                $currencyId = (isset($_GET['currencyId']) && $_GET['currencyId'] > 0) ? $_GET['currencyId'] : $row['currencyId'];

                                $curDet = getCurrencyDet($currencyId);

                                if (isset($tempItemRows[$row['id']])) {


                                    $amtVal = $tempItemRows[$row['id']]['amt'];
                                    if (isset($_GET['currencyId']) && $_GET['currencyId'] == '' && ($tempItemRows[$row['id']]['curId'] > 0)) {
                                        $amtVal = ($amtVal / $curDet['amt']);
                                    } elseif (isset($_GET['currencyId']) && $_GET['currencyId'] > 0 && ($tempItemRows[$row['id']]['curId'] == 0)) {
                                        $amtVal =  ($amtVal * $curDet['amt']);
                                    }
                                } else {
                                    $amtVal =  $row['ordPrice'];

                                    if (isset($_GET['currencyId']) && $_GET['currencyId'] > 0 && $curDet['is_default'] == 0) {
                                        $amtVal =  $row['curPrice'];
                                    }

                                    $amtVal = ($amtVal * $row['factor']);
                                }

                                if ($curDet['id'] > 0) {
                                    $amtVal = getPrice($amtVal, $curDet['decPlace']);
                                } else {
                                    $amtVal = getPrice($amtVal);
                                }

                                $productImage = 'https://cdn-icons-png.flaticon.com/512/68/68958.png';
                                if ($row['imgName'] != '') {
                                    $productImage = $siteUrl . 'uploads/' . $accountImgPath . '/products/' . $row['imgName'];
                                }
                            ?>
                                <tr>
                                    <td>
                                        <div class="recPrd-List d-flex align-items-center mt-3">
                                            <div class="prdctImg">
                                                <img src="<?php echo $productImage; ?>" alt="<?php echo $row['itemName']; ?>">
                                            </div>
                                            <div class="prdctDtl rcvPrd-Dtl">
                                                <p class="prdName ellipsis"><?php echo $row['itemName']; ?></p>
                                                <p class="prdBarCode"><?php echo $row['barCode']; ?></p>
                                            </div>
                                            <div class="prdctHide">&nbsp;</div>
                                            <div class="prdctValue rcvPrdVal">
                                                <div class="receiveQty">
                                                    <input type="text" id="qty<?php echo $x; ?>" value="<?php echo isset($tempItemRows[$row['id']]) ? $tempItemRows[$row['id']]['qty'] : $row['ordQty']; ?>" class="form-control prdForm" placeholder="1" onChange="saveQty('<?php echo $row['id']; ?>', '<?php echo $x; ?>');">
                                                    <p class="recQty-Unit"><?php echo $row['purchaseUnit']; ?></p>
                                                </div>
                                                <div class="receiveQty">
                                                    <input type="text" name="amt" id="amt<?php echo $x; ?>" value="<?php echo $amtVal; ?>" class="form-control prdForm" placeholder="1" onChange="saveQty('<?php echo $row['id']; ?>', '<?php echo $x; ?>');">
                                                    <p class="recQty-Prc"><?php echo !empty($curRes) ? $curRes['curCode'] : $getDefCurDet['curCode']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include('layout/mJs.php'); ?>
    <script language="javascript" type="text/javascript">
        function saveQty(pId, slNo) {
            var qty = $('#qty' + slNo).val();
            var amt = $('#amt' + slNo).val();
            var curId = $('#currencyId').val();

            curId = curId > 0 ? curId : 0;

            $.ajax({
                    method: "POST",
                    url: "ajax.php",
                    data: {
                        stockTakeId: '<?php echo $assignRow['orderId']; ?>',
                        pId: pId,
                        qty: qty,
                        amt: amt,
                        stockTakeType: '3',
                        curId: curId
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
    </script>
</body>

</html>