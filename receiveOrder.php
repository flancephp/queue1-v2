<?php
include('inc/dbConfig.php'); //connection details

//for excel file upload with Other language
use Shuchkin\SimpleXLSX;
require_once 'SimpleXLSX.php';

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


$rightSideLanguage = ($getLangType == 1) ? 1 : 0; 


$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'receive_order' AND type_id = '0' AND designation_id = '".$_SESSION['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if ($permissionRow)
{
    echo "<script>window.location='index.php'</script>";
}

//update invoice number when user change it
if(isset($_POST['invoiceNumber']) && isset($_POST['orderId'])) 
{
    $sqlSet = " SELECT * FROM tbl_orders where id = '".$_POST['orderId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
    $ordQry = mysqli_query($con, $sqlSet);
    $ordResult = mysqli_fetch_array($ordQry);
    if($ordResult['status'] != 2)
    {
        $updateQry = " UPDATE `tbl_orders` SET
        `invNo` = '".$_POST['invoiceNumber']."' 
        WHERE id = '".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
        mysqli_query($con, $updateQry);
    }
    

}//end


$sqlSet = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
$resultSet = mysqli_query($con, $sqlSet);
$ordRow = mysqli_fetch_array($resultSet);

// get currency detail by order
$curDetData = getCurrencyDet($ordRow['ordCurId']);

$fileDataRows = [];
if( isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != '' )
{
    $xlsx = SimpleXLSX::parse($_FILES["uploadFile"]["tmp_name"]);


    $i=0;
    foreach($xlsx->rows() as $row)
    {
        if($i == 0)
        {
            $i++;
            continue;
        }           

        $rows[] = [
        'supplierId' => $row[0],
        'barCode' => $row[1],
        'qty' => $row[2],
        'price' => $row[3]
        ];
    }

//----------------------------------
    if( is_array($rows) && !empty($rows) )
    {
        foreach($rows as $row)
        {
            
            $fileDataRows[$row['barCode']]['qty'] = trim($row['qty']);
            $fileDataRows[$row['barCode']]['price'] = isset($row['price']) ? trim($row['price']) : 0;
            $fileDataRows[$row['barCode']]['supplierId'] = trim($row['supplierId']);
        }
        
    }
}
elseif( isset($_POST['updateReceiving']) )
{
    $sqlSet = " SELECT * FROM tbl_orders where id = '".$_POST['orderId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
    $ordQry = mysqli_query($con, $sqlSet);
    $ordResult = mysqli_fetch_array($ordQry);
    if($ordResult['status'] == 2)
    {
        echo "<script>window.location='receiveOrder.php?orderId=".$_POST['orderId']."&error=alreadyreceived'</script>";die();
    }

    foreach($_POST['productIds'] as $productId)//update existing products
    {
        $price = $_POST['price'][$productId]/$_POST['factor'][$productId];

        $priceOther = str_replace(",","",$_POST['priceOther'][$productId]);

        $priceOther = $ordRow['ordCurId'] > 0 ? ($priceOther/$_POST['factor'][$productId]) : 0;

        $upQry = " UPDATE  `tbl_order_details` SET
        `qtyReceived` = '".$_POST['qty'][$productId]."', 
        `price` = '".$price."', 
        `curPrice` = '".($priceOther)."', 
        `totalAmt` = '".($_POST['factor'][$productId]*$price*$_POST['qty'][$productId])."',
        `curAmt` = '".($_POST['factor'][$productId]*$priceOther*$_POST['qty'][$productId])."' 
        WHERE ordId = '".$_GET['orderId']."' AND pId = '".$productId."'  AND account_id = '".$_SESSION['accountId']."'  ";
        mysqli_query($con, $upQry);
        

        $sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$productId."'  AND account_id = '".$_SESSION['accountId']."'  ";
        $stkQry = mysqli_query($con, $sql);
        $stkRow = mysqli_fetch_array($stkQry);
        if($stkRow)
        {
            $upQry = " UPDATE  `tbl_stocks` SET
            `qty` = (qty + ".($_POST['factor'][$productId]*$_POST['qty'][$productId])."),
            `lastPrice` = '".$price."',
            `stockValue` = ( stockValue + ".($_POST['factor'][$productId]*$price*$_POST['qty'][$productId])." )
            WHERE id = '".$stkRow['id']."' AND account_id = '".$_SESSION['accountId']."' ";
            mysqli_query($con, $upQry);


            $upQry = " UPDATE  `tbl_stocks` SET
            `stockPrice` = (stockValue/qty) 
            WHERE id = '".$stkRow['id']."' AND account_id = '".$_SESSION['accountId']."'    ";
            mysqli_query($con, $upQry);
        }   
        else
        {
            $upQry = " INSERT INTO  `tbl_stocks` SET
            `pId` = '".$productId."', 
            `qty` = ".($_POST['factor'][$productId]*$_POST['qty'][$productId]).",
            `lastPrice` = '".$price."',
            `stockValue` = '".($_POST['factor'][$productId]*$_POST['qty'][$productId]*$price)."',
            `stockPrice` = '".$price."',
            `account_id` = '".$_SESSION['accountId']."'  
            ";
            mysqli_query($con, $upQry);
        }       

        $sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$productId."' AND account_id = '".$_SESSION['accountId']."'   ";
        $stkQry = mysqli_query($con, $sql);
        $stkRow = mysqli_fetch_array($stkQry);

        //update last price
        $upQry = " UPDATE  `tbl_products` SET
        `price` = '".$stkRow['lastPrice']."',
        `stockPrice` = '".$stkRow['stockPrice']."'
        WHERE id = '".$productId."'  AND account_id = '".$_SESSION['accountId']."'   ";
        mysqli_query($con, $upQry); 


        $upQry = " UPDATE  `tbl_order_details` SET
        `lastPrice` = '".$stkRow['lastPrice']."', 
        `stockPrice` = '".$stkRow['stockPrice']."',
        `stockQty` = '".$stkRow['qty']."'

        WHERE ordId = '".$_GET['orderId']."' AND pId = '".$productId."'  AND account_id = '".$_SESSION['accountId']."'  ";
        mysqli_query($con, $upQry);


} // end of foreach loop

if( isset($_POST['barCode']) && !empty($_POST['barCode']) )
{
    $i=0;
    foreach($_POST['barCode'] as $barCode)//update existing products
    {
        $sqlSet = " SELECT * FROM tbl_products WHERE barCode = '".$barCode."' AND account_id = '".$_SESSION['accountId']."'   and id not in(".implode(',', $_POST['productIds']).")  ";

        $resultSet = mysqli_query($con, $sqlSet);
        $productRes = mysqli_fetch_array($resultSet);
        if($productRes)
        {

            $Qry = " INSERT INTO  `tbl_order_details` SET
            `ordId` = '".$_GET['orderId']."',
            `pId` = '".$productRes['id']."', 
            `price` = '".($_POST['qtyReceivedPrice'][$i]/$productRes['factor'])."', 
            `factor` = '".$productRes['factor']."', 
            `qty` = 0,
            `note` = '',
            `qtyReceived` = '".$_POST['qtyReceived'][$i]."', 
            `totalAmt` = '".($_POST['qtyReceivedPrice'][$i]*$_POST['qtyReceived'][$i])."',
            `account_id` = '".$_SESSION['accountId']."'   
            ";
            mysqli_query($con, $Qry);

            $sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$productRes['id']."' AND account_id = '".$_SESSION['accountId']."'   ";
            $stkQry = mysqli_query($con, $sql);
            $stkRow = mysqli_fetch_array($stkQry);
            if($stkRow)
            {
                $upQry = " UPDATE  `tbl_stocks` SET
                `qty` = (qty + ".($productRes['factor']*$_POST['qtyReceived'][$i])."),
                `lastPrice` = '".($_POST['qtyReceivedPrice'][$i]/$productRes['factor'])."',
                `stockValue` = ( stockValue + ".($_POST['qtyReceivedPrice'][$i]*$_POST['qtyReceived'][$i])." )
                WHERE id = '".$stkRow['id']."'  AND account_id = '".$_SESSION['accountId']."'   ";
                mysqli_query($con, $upQry);

                $upQry = " UPDATE  `tbl_stocks` SET
                `stockPrice` = (stockValue/qty) 
                WHERE id = '".$stkRow['id']."'  AND account_id = '".$_SESSION['accountId']."'   ";
                mysqli_query($con, $upQry);
            }   
            else
            {
                $upQry = " INSERT INTO  `tbl_stocks` SET
                `pId` = '".$productRes['id']."', 
                `qty` = '".($productRes['factor']*$_POST['qtyReceived'][$i])."',
                `lastPrice` = '".($_POST['qtyReceivedPrice'][$i]/$productRes['factor'])."',
                `stockPrice` = '".($_POST['qtyReceivedPrice'][$i]/$productRes['factor'])."',
                `stockValue` = '".($_POST['qtyReceivedPrice'][$i]*$_POST['qtyReceived'][$i])."',
                `account_id` = '".$_SESSION['accountId']."'  

                ";
                mysqli_query($con, $upQry);
            }       

            $sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$productRes['id']."' AND account_id = '".$_SESSION['accountId']."'   ";
            $stkQry = mysqli_query($con, $sql);
            $stkRow = mysqli_fetch_array($stkQry);

                            //update last price and stock
            $upQry = " UPDATE  `tbl_products` SET
            `price` = '".$stkRow['lastPrice']."',
            `stockPrice` = '".$stkRow['stockPrice']."'
            WHERE id = '".$productRes['id']."' AND account_id = '".$_SESSION['accountId']."'    ";
            mysqli_query($con, $upQry);
                                            //update last price         

            $upQry = " UPDATE  `tbl_order_details` SET
            `lastPrice` = '".$stkRow['lastPrice']."', 
            `stockPrice` = '".$stkRow['stockPrice']."',
            `stockQty` = '".$stkRow['qty']."'

            WHERE ordId = '".$_GET['orderId']."' AND pId = '".$productRes['id']."' AND account_id = '".$_SESSION['accountId']."'   ";
            mysqli_query($con, $upQry);

            $i++;

        } // end if condition   

    }//end for each barcode

} // end if barcode condition   
    
    //update net value in orders tbl
    $id=$_SESSION['id'];
    $invNo =$_POST['invNo'];
    $orderId=$_GET['orderId'];
    receiveOrdTotal($id,$invNo,$orderId);
    
    // $sql=" DELETE  FROM  tbl_mobile_items_temp WHERE stockTakeId = '".$_GET['orderId']."' AND stockTakeType=3  AND status=1  AND account_id = '".$_SESSION['accountId']."'    ";
    // mysqli_query($con, $sql);
    
    // $sql=" DELETE  FROM  tbl_mobile_time_track WHERE stockTakeId = '".$_GET['orderId']."' AND stockTakeType=3  AND status=1  AND account_id = '".$_SESSION['accountId']."'    ";
    // mysqli_query($con, $sql);
    
    // $sql = " DELETE FROM `tbl_order_assigned_users` where  orderId = '".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."'   ";
    // mysqli_query($con, $sql);

    $sql = " SELECT SUM(totalAmt) AS totalAmt FROM 
    tbl_order_details WHERE ordId = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $resRow = mysqli_fetch_array($res);

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $ordRow = mysqli_fetch_array($res);

    $diffPrice = ($ordRow['ordAmt'] - $ordResult['ordAmt']);
    $notes = 'Order Received(Price Diff: '.getPriceWithCur($diffPrice, $getDefCurDet['curCode']).' )';
    
    $qry = " INSERT INTO `tbl_order_journey` SET 
    `account_id` = '".$_SESSION['accountId']."',
    `orderId` = '".$_GET['orderId']."',
    `userBy`  = '".$_SESSION['id']."',
    `ordDateTime` = '".date('Y-m-d h:i:s')."',
    `amount` = '".$ordRow['ordAmt']."',
    `otherCur` = '".$ordRow['ordCurAmt']."',
    `otherCurId` = '".$ordRow['ordCurId']."',
    `invoiceNo` = '".$ordResult['invNo']."',
    `orderType` = '".$ordResult['ordType']."',
    `notes` = '".$notes."',
    `action` = 'Receive' ";
    mysqli_query($con, $qry);

    echo "<script>window.location = 'history.php?updated=1'</script>";

} // end of elseif condition


$sql = "SELECT cif.itemName, cif.unit, tod.* FROM tbl_order_details tod 
INNER JOIN tbl_custom_items_fee cif ON(tod.customChargeId = cif.id) AND tod.account_id = cif.account_id
WHERE tod.ordId = '".$_GET['orderId']."' AND tod.account_id = '".$_SESSION['accountId']."'   and tod.customChargeType=1 ORDER BY cif.itemName ";
$otherChrgQry=mysqli_query($con, $sql); 


    $sql = "SELECT tp.*, od.price ordPrice, od.curPrice ordCurPrice, od.ordId, od.currencyId,  od.curPrice curPrice, od.curAmt curAmt, od.qty ordQty, od.totalAmt, od.factor ordFactor, IF(u.name!='',u.name,tp.unitP) purchaseUnit FROM tbl_order_details od 
    INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
    LEFT JOIN tbl_units u ON(u.id = tp.unitP) AND u.account_id = tp.account_id
    WHERE od.ordId = '".$_GET['orderId']."' AND tp.account_id = '".$_SESSION['accountId']."'   ";
    $orderQry = mysqli_query($con, $sql);
    

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Receive Order - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">
</head>

<body>
    <main>
        <div class="container-fluid newOrder">
            <div class="row">
                <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                    <?php require_once('nav.php');?>
                </div>
                <div class="cntArea">
                    <section class="usr-info">
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-end">
                                <h1 class="h1"><?php echo showOtherLangText('Receive Order'); ?></h1>
                            </div>
                            <div class="col-md-8 d-flex align-items-center justify-content-end">
                                <div class="mbPage">
                                    <div class="mb-nav" id="mb-nav">
                                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#navbarSupportedContent"
                                            aria-controls="navbarSupportedContent" aria-expanded="false"
                                            aria-label="Toggle navigation">
                                            <span class="navbar-toggler-icon"><i class="fa-solid fa-bars"></i></span>
                                        </button>
                                    </div>
                                    <div class="mbpg-name">
                                        <h1 class="h1"><?php echo showOtherLangText('Receive Order'); ?></h1>
                                    </div>
                                </div>
                                <div class="user d-flex align-items-center">
                                    <img src="Assets/images/user.png" alt="user">
                                    <p class="body3 m-0 d-inline-block">User</p>
                                </div>
                                <div class="acc-info">
                                    <img src="Assets/icons/Q.svg" alt="Logo" class="q-Logo">
                                    <!-- <h1>Q</h1> -->
                                    <div class="dropdown d-flex">
                                        <a class="dropdown-toggle body3" data-bs-toggle="dropdown">
                                            <span> Account</span> <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0)">Account 1</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Account 2</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Account 3</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Account 4</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="landScape">
                        <div class="container">
                            <h1 class="h1 text-center">For better experience, Please use portrait view.</h1>
                        </div>
                    </section>

                    <section class="ordDetail">
                        <div class="tpBar-grn"></div>
                        <div class="stcPart">
                            <div class="container nwOrder-Div rcvOrder">
                                <div class="row">
                                    <div class="sltSupp nwOrd-Num">
                                        <div class="ord-Box">
                                            <div class="ordNum">
                                                <h4 class="subTittle1"><span>Order#:</span> <span><?php echo $ordRow['ordNumber'];?></span></h4>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ordInfo rcvInfo newFeatures">
                                        <div class="container">
                                            <div class="mbFeature">
                                                <div class="row gx-3 justify-content-end">

                                                    <div class="col-md-4 text-center">
                                                        <div class="row featRow">

                                                            <div
                                                                class="col-md-6 ordFeature dropdown drpCurr brdLft position-relative">
                                                                <a href="javascript:void(0)" class="tabFet">
                                                                    <span class="importFile"></span>
                                                                    <p class="btn2">Import
                                                                        Receiving File</p>
                                                                </a>

                                                            </div>
                                                            <div class="col-md-6 ordFeature drpFee position-relative">
                                                                <a href="javascript:void(0)" class="tabFet">
                                                                    <span class="sampleFile"></span>
                                                                    <p class="btn2">Download
                                                                        Sample File</p>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 text-end smBtn nwNxt-Btn">
                                        <div class="btnBg">
                                            <a href="javascript:void(0)" class="btn sub-btn std-btn">Receive</a>
                                        </div>
                                        <div class="btnBg mt-3">
                                            <a href="runningOrders.php" class="btn sub-btn std-btn">Back</a>
                                        </div>
                                        <div class="fetBtn">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/dashboard.svg" alt="dashboard">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container topOrder rcvOrder">
                                <div class="row">
                                    <div class="suppDetl">
                                        <div class="recvInv">
                                            <p><?php echo showOtherLangText('Supplier'); ?>: <span class="supName"><?php
                                    $sqlSet = " SELECT GROUP_CONCAT(DISTINCT(s.name)) suppliers FROM tbl_orders o 

                                    INNER JOIN tbl_suppliers s ON(o.supplierId = s.id) AND o.account_id = s.account_id

                                    WHERE o.id =".$_GET['orderId']." AND s.account_id = '".$_SESSION['accountId']."' ";

                                    $resultSet = mysqli_query($con, $sqlSet);
                                    $supRow = mysqli_fetch_array($resultSet);
                                    echo  $supRow['suppliers'];

                                    ?></span></p>
                                            <div class="d-flex gap-2 align-items-center">
                                                <p class="flex-fill flex-grow-0 flex-shrink-0"><?php echo showOtherLangText('Invoice No'); ?>: </p>
                                               <input class="form-control invNum" type="text" name="invNo" id="invNo"
                                                autocomplete="off" value="<?php echo $ordRow['invNo'] ?>"
                                                oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                onChange="getInvNo(),this.setCustomValidity('')" required />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ordInfo rcvInfo">
                                        <div class="container">
                                            <div class="prcTable">
                                                <div class="price justify-content-between">
                                                    <div class="p-2 delIcn text-center"></div>
                                                    <div class="p-2 txnmRow">
                                                        <p>Total</p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p>23,990 $</p>
                                                        </div>
                                                        <div class="p-2 otherCurr">
                                                            <p>23,990 €</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="price justify-content-between taxRow">
                                                    <div class="p-2 delIcn text-center">
                                                        <a href="javascript:void(0)">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </a>
                                                    </div>
                                                    <div class="p-2 txnmRow">
                                                        <p>VAT 19%</p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p>11.362 $</p>
                                                        </div>
                                                        <div class="p-2 otherCurr">
                                                            <p>11.362 €</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="price justify-content-between grdTtl-Row">
                                                    <div class="p-2 delIcn text-center"></div>
                                                    <div class="p-2 txnmRow">
                                                        <p>Grand Total</p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p>23,990 $</p>
                                                        </div>
                                                        <div class="p-2 otherCurr">
                                                            <p>23,990 €</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container recPrice position-relative">
                            <!-- Item Table Head Start -->
                            <div class="d-flex align-items-center itmTable">
                                <div class="prdtImg tb-head">
                                    <p><?php echo showOtherLangText('Image'); ?></p>
                                </div>
                                <div class="recItm-Name tb-head">
                                    <p><?php echo showOtherLangText('Item Ordered'); ?></p>
                                </div>
                                <div class="recQty-Code d-flex align-items-center">
                                    <div class="recItm-brCode tb-head">
                                        <p><?php echo showOtherLangText('Bar Code'); ?></p>
                                    </div>
                                    <div class="qty-Ordred tb-head">
                                        <p><?php echo showOtherLangText('Qty Ordered'); ?></p>
                                    </div>
                                </div>
                                <div class="recPrc-Unit d-flex align-items-center">
                                    <div class="recItm-Unit tb-head">
                                        <p><?php echo showOtherLangText('P Unit'); ?></p>
                                    </div>
                                    <div class="qty-Rcvd tb-head">
                                        <p><?php echo showOtherLangText('Qty Received'); ?></p>
                                    </div>
                                         <?php 
            if($ordRow['ordCurId'] > 0)
            {
                $res = mysqli_query($con, " select * from tbl_currency WHERE id='".$ordRow['ordCurId']."' ");
                $curDet = mysqli_fetch_array($res);

                ?> 
                                    <div class="recCr-Type d-flex align-items-center">
                                        <div class="dflt-RecPrc tb-head">
                                            <p><?php echo showOtherLangText('P.Price'); ?>(<?php echo $getDefCurDet['curCode'] ?>)</p>
                                        </div>
                                        <div class="othr-RecPrc tb-head">
                                            <p><?php echo showOtherLangText('P.Price'); ?>(<?php echo $curDet['curCode'];?>)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="recTtlPrc-Type d-flex align-items-center">
                                    <div class="ttlDft-RecPrc tb-head">
                                        <p><?php echo showOtherLangText('Total'); ?>(<?php echo $getDefCurDet['curCode'] ?>)</p>
                                    </div>
                                    <div class="ttlOtr-RecPrc tb-head">
                                        <p><?php echo showOtherLangText('Total'); ?>(<?php echo $curDet['curCode'];?>)</p>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="recCr-Type d-flex align-items-center">
                                        <div class="dflt-RecPrc tb-head">
                                            <p><?php echo showOtherLangText('P.Price'); ?>(<?php echo $getDefCurDet['curCode'] ?>)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="recTtlPrc-Type d-flex align-items-center">
                                    <div class="ttlDft-RecPrc tb-head">
                                        <p><?php echo showOtherLangText('Total'); ?>(<?php echo $getDefCurDet['curCode'];?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            </div>
                            <!-- Item Table Head End -->
                        </div>


                        <div id="boxscroll">
                            <div class="container cntTable">
                                <!-- Item Table Body Start -->
                                <?php   

        $y = 0;
        while ($showCif= mysqli_fetch_array($otherChrgQry))
            {  
                $y++;
                ?>
                                <div class="newOrdTask recOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody recOrd-TblBody">
                                        <div class="prdtImg tb-bdy">
                                            <!-- <img src="Assets/images/Apple.png" alt="Item" class="ordItm-Img"> -->
                                        </div>
                                        <div class="recItm-Name tb-bdy">
                                            <p class="recive-Item"><?php echo $showCif['itemName'];?></p>
                                        </div>
                                        <div class="recQty-Code cloneQty-Code align-items-center">
                                            <div class="recItm-brCode tb-bdy">
                                                <p></p>
                                            </div>
                                            <div class="qty-Ordred tb-bdy">
                                                <p><strong>1</strong><span class="tabOn-Qty">On stock</span></p>
                                            </div>
                                        </div>
                                        <div class="recPrc-Unit d-flex">
                                            <div class="tab-RecItm"></div>
                                            <div class="recItm-Unit tb-bdy">
                                                <p>1</p>
                                            </div>
                                            <div class="qty-Rcvd tb-bdy">
                                               &nbsp;
                                            </div>
                                            <div class="recCr-Type d-flex align-items-center">
                                                <!-- <div class="dflt-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                                <div class="othr-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div> -->
                                                <?php if($showCif['currencyId'] > 0){ ?>

                                        <div class="qty-Rcvd tb-bdy">
                                               &nbsp;
                                            </div>
                                        <div class="qty-Rcvd tb-bdy">
                                               &nbsp;
                                            </div>

                                        <?php }else{ ?>

                                        <td><strong><?php echo showPrice($showCif['price'],$getDefCurDet['curCode']);?></strong>
                                        </td>

                                        <?php } ?>
                                            </div>
                                        </div>
                                        <div class="recTtlPrc-Type d-flex align-items-center">
                                            <div class="tabTtl-Price"></div>
                                            <div class="ttlDft-RecPrc tb-bdy">
                                                <p>2.6144 $</p>
                                            </div>
                                            <div class="ttlOtr-RecPrc tb-bdy">
                                                <p>2.6144 €</p>
                                            </div>
                                        </div>
                                        <div class="recBr-Hide">
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <?php   }


    $x= 0;
    $subTotalAmtDol = 0;
    $subTotalAmtOther = 0;
    while($row = mysqli_fetch_array($orderQry))
    {   

        $x++;
        $y++;

        $sql=" SELECT * FROM  tbl_mobile_items_temp WHERE stockTakeId = '".$ordRow['id']."'
        AND account_id = '".$_SESSION['accountId']."'   AND stockTakeType=3  AND status=1 AND pId = '".$row['id']."'  ";
        $itemTempQry = mysqli_query($con, $sql);
        $itemTempRes = mysqli_fetch_array($itemTempQry);

        if( isset($fileDataRows[$row['barCode']]) )
        { 
            $receivedRow = $fileDataRows[$row['barCode']];
            $qtyVal = $receivedRow['qty'];
            $ordQty = $receivedRow['qty'];
            $boxPrice =$receivedRow['price'] > 0 ? $receivedRow['price'] : $row['ordFactor']*$row['ordPrice'];

            $boxPriceOther = $receivedRow['priceOther'] > 0 ? $receivedRow['priceOther'] : $row['ordFactor']*$row['curPrice'];

            unset($fileDataRows[$row['barCode']]);

        }
        elseif($itemTempRes)
        { 
            $qtyVal = $itemTempRes['qty'];
            $ordQty = $itemTempRes['qty'];

            $boxPrice = ($itemTempRes['curId'] > 0 && $curDet['is_default'] == 0 && $curDet['amt']) ? ($itemTempRes['amt']/$curDet['amt']) : $itemTempRes['amt'];
            $boxPriceOther = $itemTempRes['curId'] > 0 ? $itemTempRes['amt'] : ( ($itemTempRes['amt']*$curDet['amt']) );
            $subTotalAmtDol += ($boxPrice*$qtyVal);
            $subTotalAmtOther += ($boxPriceOther*$qtyVal);
        }
        else
        { 
            $boxPrice = ($row['ordPrice']*$row['ordFactor']);
            $boxPriceOther = ($row['ordCurPrice']*$row['ordFactor']);           
            $ordQty = $row['ordQty'];
            $qtyVal = $row['ordQty'];   
        }




        ?>

                                    <input type="hidden" name="productIds[]" id="productId<?php echo $x;?>"
                                        value="<?php echo $row['id'];?>" />
                                    <input type="hidden" id="<?php echo $x;?>" value="<?php echo $row['price'];?>" />
                                    <input type="hidden" name="factor[<?php echo $row['id'];?>]"
                                        id="factor<?php echo $x;?>" value="<?php echo $row['factor'];?>" />

                                     <div class="newOrdTask recOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody recOrd-TblBody">
                                        <div class="prdtImg tb-bdy">
                                            <?php $img = '';
            if( $row['imgName'] != ''  && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$row['imgName'] )  )
            {   
                echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$row['imgName'].'" width="60" height="60">';
                                    //echo  '<img src="'.$siteUrl.'uploads/'.$row['imgName'].'" width="60" height="60">';
            }?>
                                        </div>
                                        <div class="recItm-Name tb-bdy">
                                            <p class="recive-Item"><?php echo $row['itemName'];?></p>
                                        </div>
                                        <div class="recQty-Code cloneQty-Code align-items-center">
                                            <div class="recItm-brCode tb-bdy">
                                                <p><?php echo $row['barCode'];?></p>
                                            </div>
                                            <div class="qty-Ordred tb-bdy">
                                                <p><?php echo $row['ordQty'];?> <span class="tabOn-Qty">On stock</span></p>
                                            </div>
                                        </div>
                                        <div class="recPrc-Unit d-flex">
                                            <div class="tab-RecItm"></div>
                                            <div class="recItm-Unit tb-bdy">
                                                <p><?php echo $row['purchaseUnit'];?></p>
                                            </div>
                                            <div class="qty-Rcvd tb-bdy">
                                                <input type="text"  class="form-control qty-itm recQty-Receive" id="qty<?php echo $x;?>"
                                                    name="qty[<?php echo $row['id'];?>]" autocomplete="off"
                                                    onChange="showTotal('<?php echo $x;?>')"
                                                    value="<?php echo  $ordQty;?>" size="5">
                                            </div>
                                            <div class="recCr-Type d-flex align-items-center">
                                                <div class="dflt-RecPrc tb-bdy">
                                                    <input class="form-control qty-itm" type="text" id="priceId<?php echo $x;?>"
                                                    name="price[<?php echo $row['id'];?>]" autocomplete="off"
                                                    value="<?php echo getPrice($boxPrice);?>" size="5"
                                                    onChange="showTotal('<?php echo $x;?>')">
                                                <?php echo $getDefCurDet['curCode'] ?>
                                                </div>
                                                <!-- <div class="othr-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div> -->
                                            </div>
                                        </div>
                                        <div class="recTtlPrc-Type d-flex align-items-center">
                                            <div class="tabTtl-Price"></div>
                                            <div class="ttlDft-RecPrc tb-bdy">
                                                <p id="totalPrice<?php echo $x;?>"><?php echo showPrice($boxPrice*$qtyVal,$getDefCurDet['curCode']);?></p>
                                            </div>
                                            <?php 
                if( isset($curDet) )
                {
                    $newCurAmt = ($boxPriceOther*$qtyVal); ?>
                                            <div class="ttlOtr-RecPrc tb-bdy">
                                                <p id="totalPriceOther<?php echo $x; ?>"><?php echo showOtherCur($newCurAmt, $curDet['id']); ?></p>
                                            </div>
                                      <?php      }
                ?>
                                        </div>
                                        <div class="recBr-Hide">
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                        <?php   }

            

        $notFoundProducts = [];
        if( isset($fileDataRows) && !empty($fileDataRows) )
        {
            foreach($fileDataRows as $barCode=>$receivedRow)
            {
                $x++;
                
                
                $sql = "SELECT tp.*, IF(u.name!='',u.name,tp.unitP) purchaseUnit FROM tbl_products tp 
    LEFT JOIN tbl_units u ON(u.id = tp.unitP) AND u.account_id = tp.account_id
    WHERE tp.barCode = '".$barCode."' AND tp.account_id = '".$_SESSION['accountId']."'   ";
                $resultSet = mysqli_query($con, $sql);
                $productRes = mysqli_fetch_array($resultSet);
    
    
                if(!$productRes || $productRes['barCode'] == '')
                {
                    continue;
                }

                $boxPrice = $receivedRow['price'] > 0 ? $receivedRow['price'] : $productRes['factor']*$productRes['price'];
                $qty = $receivedRow['qty'];
                ?>
                                    <tr><input type="hidden" id="factor<?php echo $x;?>" name="factor[]">
                                        <input type="hidden" id="supplierId<?php echo $x;?>" name="supplierId[]"
                                            size="5" value="<?php echo $receivedRow['supplierId'];?>">
                                
                                 <div class="newOrdTask recOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody recOrd-TblBody">
                                        <div class="prdtImg tb-bdy">
                                            <img src="Assets/images/Apple.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="recItm-Name tb-bdy">
                                            <p class="recive-Item">Apple</p>
                                        </div>
                                        <div class="recQty-Code cloneQty-Code align-items-center">
                                            <div class="recItm-brCode tb-bdy">
                                                <p>9781462570123</p>
                                            </div>
                                            <div class="qty-Ordred tb-bdy">
                                                <p>10 <span class="tabOn-Qty">On stock</span></p>
                                            </div>
                                        </div>
                                        <div class="recPrc-Unit d-flex">
                                            <div class="tab-RecItm"></div>
                                            <div class="recItm-Unit tb-bdy">
                                                <p>Kg</p>
                                            </div>
                                            <div class="qty-Rcvd tb-bdy">
                                                <input type="text" class="form-control qty-itm recQty-Receive"
                                                    placeholder="1">
                                            </div>
                                            <div class="recCr-Type d-flex align-items-center">
                                                <div class="dflt-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                                <div class="othr-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="recTtlPrc-Type d-flex align-items-center">
                                            <div class="tabTtl-Price"></div>
                                            <div class="ttlDft-RecPrc tb-bdy">
                                                <p>2.6144 $</p>
                                            </div>
                                            <div class="ttlOtr-RecPrc tb-bdy">
                                                <p>2.6144 €</p>
                                            </div>
                                        </div>
                                        <div class="recBr-Hide">
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                 <?php }
            } ?>


                                <!-- Item Table Body End -->
                            </div>

                        </div>
                    </section>


                </div>
            </div>
        </div>
    </main>

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
</body>

</html>