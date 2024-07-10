<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


if ( !isset($_SESSION['adminidusername']))
{
echo '<script>window.location="login.php"</script>';
}   

if (isset($_GET['orderId'])) {


$sql = "SELECT tp.*, od.price ordPrice, IF(u.name!='',u.name,tp.unitC) countingUnit, od.qty ordQty, od.totalAmt, od.factor ordFactor FROM tbl_order_details od 
INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
LEFT JOIN tbl_units u ON(u.id = tp.unitC) AND u.account_id = tp.account_id
WHERE od.ordId = '".$_GET['orderId']."'   AND tp.account_id = '".$_SESSION['accountId']."' ";
$ordQry = mysqli_query($con, $sql);

}
//show details
$cmd="SELECT * FROM tbl_orders WHERE id='".$_GET['orderId']."'   AND account_id = '".$_SESSION['accountId']."' ";
$ordersQry = mysqli_query($con, $cmd);
$ordersRow = mysqli_fetch_array($ordersQry);

$qry = "SELECT * FROM tbl_req_payment WHERE orderId='".$_GET['orderId']."'   AND account_id = '".$_SESSION['accountId']."' order by id desc limit 1  "; 
$result = mysqli_query($con, $qry);
$paymentRow = mysqli_fetch_array($result);

$curQry = " SELECT * FROM tbl_currency WHERE id='".$paymentRow['currencyId']."' AND account_id = '".$_SESSION['accountId']."' ";
$curResult = mysqli_query($con, $curQry);
$curRow = mysqli_fetch_array($curResult);

$currencyCode = $curRow['curCode'];


$sqlSet = " SELECT * FROM tbl_payment_mode WHERE id='".$paymentRow['paymentType']."'   AND account_id = '".$_SESSION['accountId']."' ";
$resultSet = mysqli_query($con, $sqlSet);
$payModeRow = mysqli_fetch_array($resultSet);


$qry = "SELECT * FROM tbl_requisition_payment_info WHERE  orderId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
$resultSet = mysqli_query($con, $qry);
$paymentInfoRow = mysqli_fetch_array($resultSet);


//refund related code goes here 
if ( isset($_POST['approveBtn']) && ($_POST['refund']=="refundAmt" || $_POST['refund']=="refundAll") )
{


if ($_POST['refund']=="refundAll")
{

        $cmd="SELECT * FROM tbl_order_details WHERE ordId='".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."' AND pId > 0 ";
        $ordersQry = mysqli_query($con, $cmd);

        while($ordersRow = mysqli_fetch_array($ordersQry))
        {
        $qty = $ordersRow['qty'];
        $factor = $ordersRow['factor'];
        $upQry = " UPDATE `tbl_stocks` SET
        `qty` = (qty + $qty),
        `stockValue` = ( stockValue + ".$ordersRow['totalAmt']." )
        WHERE pId = '".$ordersRow['pId']."' AND account_id = '".$_SESSION['accountId']."'   ";
        mysqli_query($con, $upQry);
        }

        

}

$refund = $_POST['refund']=="refundAll" ? 'Amount and Qty refund' : 'Amount refund only';

$sql = " SELECT * FROM tbl_orders WHERE id = '".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
        $res = mysqli_query($con, $sql);
        $orderDetRow = mysqli_fetch_array($res);
    
        $qry = " INSERT INTO `tbl_order_journey` SET 
            `account_id` = '".$_SESSION['accountId']."',
            `orderId` = '".$orderDetRow['id']."',
            `userBy`  = '".$_SESSION['id']."',
            `amount` = '".$orderDetRow['ordAmt']."',
            `orderType` = '".$orderDetRow['ordType']."',
            `ordDateTime` = '".date('Y-m-d h:i:s')."',
            `notes` = '".$refund."',
            `action` = 'Invoice Refunded' ";
            mysqli_query($con, $qry);
            

$sqlSet = " SELECT tp.amount, tp.bankAccountId,tp.currencyId, c.curCode AS curCode, ac.* FROM tbl_req_payment tp 
INNER JOIN tbl_accounts ac ON(tp.bankAccountId = ac.id) AND tp.account_id = ac.account_id
LEFT JOIN tbl_currency c ON(tp.currencyId = c.id) AND tp.account_id = c.account_id
WHERE tp.orderId = '".$_POST['orderId']."' order by tp.id limit 1 "; 
$resultSqlSet= mysqli_query($con, $sqlSet);
$resultRow= mysqli_fetch_array($resultSqlSet);

$currCode = $resultRow['curCode'];
$bankAccountId = $resultRow['bankAccountId'];

$amount = $resultRow['amount'];
if ($resultRow['currencyId'] > 0)
{
$trimedAmount = trim($amount,$currCode);

}else{

$trimedAmount = trim($amount,$getDefCurDet['curCode']);
}

$replacedAmount = str_replace(',', '', $trimedAmount);

$updateQry= " UPDATE tbl_accounts SET balanceAmt=(balanceAmt-$replacedAmount ) WHERE id='".$bankAccountId."'  AND account_id = '".$_SESSION['accountId']."'  ";
$resultQry= mysqli_query($con, $updateQry);

$updtQry = " UPDATE tbl_orders SET paymentStatus='0', bankAccountId = '0', paymentDateTime = '' WHERE id='".$_POST['orderId']."' ";
$resultQry= mysqli_query($con, $updtQry);


$qry = "SELECT * FROM tbl_req_payment WHERE orderId='".$_POST['orderId']."' AND account_id = '".$_SESSION['accountId']."' AND paymentStatus = 1 order by id limit 1 "; 
$result = mysqli_query($con, $qry);
$paymentRow = mysqli_fetch_array($result);

if (count($paymentRow) > 0) 
{

$insertQry = "INSERT INTO `tbl_req_payment_history` SET
parentPaymentId = '".$paymentRow['id']."',
account_id = '".$paymentRow['account_id']."',
orderId = '".$paymentRow['orderId']."',
bankAccountId = '".$paymentRow['bankAccountId']."',
currencyId = '".$paymentRow['currencyId']."',
ordTotAmt = '".$paymentRow['ordTotAmt']."',
amount = '".$paymentRow['amount']."',
paymentType = '".$paymentRow['paymentType']."',
paymentStatus = '".$paymentRow['paymentStatus']."',
paymentDateTime = '".$paymentRow['paymentDateTime']."' ";

mysqli_query($con, $insertQry);




$updateQry = " UPDATE tbl_req_payment SET 
currencyId = '',
ordTotAmt = '',
paymentType = '',
paymentStatus = '',
paymentDateTime = ''
WHERE orderId = '".$_GET['orderId']."' ";
$updatePaymentResult = mysqli_query($con, $updateQry);
$updatePaymentRow = mysqli_fetch_array($updatePaymentResult);

}

echo '<script>window.location = "history.php?orderId='.$_POST['orderId'].'&paymentStatus=2"</script>';

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Queue1.com</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/module-A.css">


    <style>
        .modal-md {
            max-width: 800px;
        }

        .modal-address p {
            padding-bottom: 2px;
        }

        .site-modal .modal-content {
            background: #f0f0f0;
        }

        .site-modal .modal-body {
            background: #ffffff;
        }


        .site-modal thead tr th {
            font-weight: 500;
            padding: 8px 5px;
        }

        .site-modal tr.thead td {
            font-weight: 500;
            padding: 8px 5px;
        }

        .site-modal .thead {
            border-top: 0;
        }

        .site-modal .thead+tr {
            border-top: 0;
        }

        .site-modal thead,
        .site-modal .thead {
            background-color: rgb(122 137 255 / 20%);
        }

        .site-modal tbody tr+tr {
            border-top: 1px solid #ddd;
        }

        .site-modal tbody tr td {
            padding: 5px 5px;
        }

        .modal-header .btn {
            background-color: #7a89ff;
            color: #fff;
            border-radius: 6px;
            font-size: 14px;
        }

        .modal-header .btn {
            background-color: #7a89ff;
            color: #fff;
            border-radius: 6px;
            font-size: 14px;
            border-color: #7a89ff;
        }

        .modal-head-row .btn {
            font-size: 13px;
            height: 34px;
        }

        .modal-head-row ul.dropdown-menu {
            box-shadow: 0 0 10px #d5d5d5;
        }

        .modal-head-row .btn i {
            font-size: 10px;
        }

        /* input select custon css for theis page  */
        .form-control {
            background-color: white;
            padding: .5rem;
        }

        .form-info tr,
        .payDetail tr {
            height: 38px !important;
        }

        .inv-detail tr {
            min-height: 38px !important;
        }

        .wht-btn:hover,
        .wht-btn:focus,
        .wht-btn:active {
            border-color: #fff;
            background: #fff;
            color: #000;
            border: 1px solid #000000;
        }

        .final-btn:hover {
            background: #ED7D31;
            border: 2px solid #AE5A21;
        }

        .wht-btn {
            font-size: 12px;
            line-height: 15px;
            font-weight: 400;
            color: #000;
            background: linear-gradient(180deg, #F0EFEE 0%, #DCDCDC 100%);
            border: 1px solid #000000;
            border-radius: 4px;
            padding: 7px 20px;
        }

        .final-btn {
            font-size: 12px;
            line-height: 15px;
            font-weight: 700;
            background: #ED7D31;
            border: 2px solid #ED7D31;
            border-radius: 4px;
            padding: 7px 15px;
            transition: all .1s ease-in-out;
            color: white;
        }
    </style>

</head>

<body>

    <div class="container-fluid newOrder">
        <div class="row">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                <?php require_once('nav.php'); ?>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('History'); ?></h1>
                        </div>
                        <div class="col-md-8 d-flex align-items-center justify-content-end">
                            <div class="mbPage">
                                <div class="mb-nav" id="mb-nav">
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"><i class="fa-solid fa-bars"></i></span>
                                    </button>
                                </div>
                                <div class="mbpg-name">
                                    <!-- <h1 class="h1"> History </h1> -->
                                </div>
                            </div>
                            <div class="user d-flex align-items-center">
                                <img src="Assets/images/user.png" alt="user">
                                <p class="body3 m-0 d-inline-block">User</p>
                            </div>
                            <div class="acc-info">
                                <img src="Assets/icons/Q.svg" alt="Logo" class="q-Logo">
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


                <div class="payment-status-main">
                    <!--payment pending Details Popup Start -->
                    <div class="payment-status-left recived-invoice position-relative">
                        <div class="border-line"></div>
                        <div class="modal-body p-5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <!-- <p class="f-01 mb-0 payment-status-text">RECEIVED</p> -->
                                          <?php 
                if (isset($_GET['orderId']) && $paymentRow['paymentStatus']==1 ) 
                    { ?>

                                <p class="f-01 mb-0 payment-status-text">
                                        <?php echo showOtherLangText('RECEIVED'); ?></h3>
                                </p>

                                <?php 
                    }
                    elseif($_GET['paymentStatus']==2) 
                    { 
                        ?>

                                 <p class="f-01 mb-0 payment-status-text">
                                        <?php echo showOtherLangText('REFUND'); ?></h3>
                                </p>

                                <?php 
                    }
                    else
                    { 
                        ?>

                                <p class="f-01 mb-0 payment-status-text">
                                        <?php echo showOtherLangText('PENDING'); ?></h3>
                                </p>

                                <?php 
                    } 

                    ?>
                                    <p class="f-01"><?php echo showOtherLangText('INVOICE'); ?></p>
                                </div>
                                <div>
                                   <?php

$clientQry = " SELECT * FROM tbl_client WHERE id = '".$_SESSION['accountId']."' ";
$clientResult = mysqli_query($con, $clientQry);
$clientResultRow = mysqli_fetch_array($clientResult);

if($clientResultRow['logo'] !='' && file_exists(dirname(__FILE__)."/uploads/".$accountImgPath."/clientLogo/".$clientResultRow['logo']))
{   
    echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/clientLogo/'.$clientResultRow['logo'].'" width="100" height="100" style="margin-top: 25px;">';
}
            ?>
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-between payment-tabel-header">
                                <table class="table1 table01">
                                    <tbody>
                                        <tr>
                                            <td class="font-wt">
                                                <?php echo showOtherLangText('Invoice'); ?> #
                                            </td>
                                            <td><?php echo getinvoiceNumber($paymentInfoRow['invoiceNumber']); ?></td>
                                        </tr>

                                        <tr>
                                            <td class="font-wt"><?php echo showOtherLangText('Task'); ?> #</td>
                                            <td><?php echo $ordersRow['ordNumber'] ?></td>
                                        </tr>

                                        <tr>
                                            <td class="font-wt"><?php echo showOtherLangText('Date'); ?> #
                                            </td>
                                            <td><?php
                            if ($paymentRow['paymentStatus'] ==1 ) {
                    
                                echo $paymentRow['paymentDateTime'];

                            }else{

                                echo $ordersRow['ordDateTime'];
                            }
                            ?></td>
                                        </tr>

                                    </tbody>
                                </table>
                        <?php

                        $sql = " SELECT * FROM tbl_country WHERE id = '".$clientResultRow['country']."' ";
                        $resSet = mysqli_query($con, $sql);
                        $resultRow = mysqli_fetch_array($resSet);
                         ?>
                                <table>
                                    <tbody class="table1 table01 fl-right cmp-dtl text-end">
                                        <tr>
                                            <td><?php echo $clientResultRow['accountName']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo $clientResultRow['address_one'].','.$clientResultRow['address_two'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo $clientResultRow['city'].','.$resultRow['name'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo $clientResultRow['email']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo $clientResultRow['phone']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                            <br>
                            <div class="">
                                <p class="f-02 mb-0"><?php echo showOtherLangText('Invoice To'); ?>: </p>
                                <p class="f-03 mb-0"><?php echo $paymentInfoRow['invoiceName'] ?></p>
                                <p class="f-03 mb-0"><?php echo $paymentInfoRow['invoiceAddress']; ?></p>
                                <p class="f-03 mb-0"><?php echo $paymentInfoRow['invoiceEmail']; ?></p>
                                <p class="f-03 mb-0"><?php echo $paymentInfoRow['invoicePhone']; ?></p>
                            </div>
                            <br>

                            <table class="modal-table fs-12 w-100 mt-4">
                                <thead style="background: #A9B0C0 !important;">
                                    <tr class="tr-bg-1">
                                        <th>#</th>
                                        <th style="width: 30%;"><?php echo showOtherLangText('Item'); ?></th>
                                        <th><?php echo showOtherLangText('Unit'); ?></th>
                                        <th><?php echo showOtherLangText('Quantity'); ?></th>
                                       
                                        <th class="th-bg-1"><?php echo showOtherLangText('Price').' '.$getDefCurDet['curCode']; ?></th>
                                        <th class="th-bg-1"><?php echo showOtherLangText('Total').' '.$getDefCurDet['curCode']; ?></th>
                                    </tr>
                                </thead>
                                <tbody class="tabel-body-p">
                                       <?php 
                        $sql = "SELECT cif.itemName, cif.unit,cif.amt, tod.* FROM tbl_order_details tod 
                        INNER JOIN tbl_custom_items_fee cif ON(tod.customChargeId = cif.id) AND tod.account_id = cif.account_id
                        WHERE tod.ordId = '".$_GET['orderId']."'  AND tod.account_id = '".$_SESSION['accountId']."'  and tod.customChargeType=1 ORDER BY cif.itemName ";
                        $resultSet=mysqli_query($con, $sql);   
                        $x=0;
                        while ($showCif= mysqli_fetch_array($resultSet))
                        {
                            $x++;
                            ?>
                                    <tr>
                                        <td><?php echo $x; ?></td>
                                        <td style="width: 30%;"><?php echo $showCif['itemName'];?></td>
                                        <td><?php echo $showCif['unit'];?></td>
                                        <td>1</td>
                                        <td><?php showprice($showCif['amt'],$getDefCurDet['curCode']);?></td>
                                        <td><?php showprice($showCif['amt'],$getDefCurDet['curCode']);?></td>
                                    </tr>
                         <?php  
                        } 

                        while($row = mysqli_fetch_array($ordQry))
                        {

                            $x++;


                            ?>
                             <tr>
                                        <td><?php echo $x; ?></td>
                                        <td style="width: 30%;"><?php echo $row['itemName'] ?></td>
                                        <td><?php echo $row['countingUnit'] ?></td>
                                        <td><?php echo $row['ordQty'] ?></td>
                                        <td><?php showPrice($row['ordPrice'],$getDefCurDet['curCode']);?></td>
                                        <td><?php showPrice($row['ordQty']*$row['ordPrice'],$getDefCurDet['curCode']); ?></td>
                                    </tr>

                             <?php } ?>           
                                </tbody>
                            </table>
                            <div class="divider-blue"></div>
                            <br>
                            <div class="tabel-body-p-footer">
                                <div class="table1 ">
                                    <p class="f-02 mb-0"><?php echo showOtherLangText('Payment Method'); ?>:</p>
                                     <?php 
                $sqlSet = " SELECT * FROM  tbl_accounts WHERE id='".$paymentRow['bankAccountId']."' AND account_id = '".$_SESSION['accountId']."'  ";
                $resultSet = mysqli_query($con, $sqlSet);
                $accDet = mysqli_fetch_array($resultSet); 
                ?>
                                    <p class="f-03 mb-0"><?php echo $payModeRow['modeName'];?></p>
                                    <p class="f-03 mb-0"><?php echo $accDet['accountName'];?></p>
                                </div>

                                <!-- grand totale  here -->
                                <table class="grand-total-tabel">
                                         <?php 

  //get the sum of all product and item level charges  
                            $sqlSet="SELECT SUM(totalAmt) as sum1 from tbl_order_details where ordId='".$_GET['orderId']."'   AND account_id = '".$_SESSION['accountId']."' AND (customChargeType='1' OR customChargeType='0')";
                            $resultSet = mysqli_query($con, $sqlSet);
                            $chargeRow = mysqli_fetch_array($resultSet);    
                            $chargePrice=$chargeRow['sum1'];                    

                            $ordCount="SELECT * from tbl_order_details where ordId='".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."' AND customChargeType='2' ";
                            $ordCountResult = mysqli_query($con, $ordCount);
                            $ordCountRow = mysqli_num_rows($ordCountResult);

                            if ($ordCountRow > 0)
                                { ?>
                                    <tr>
                                        <td><?php echo showOtherLangText('Sub Total'); ?></td>
                                        <td><?php showPrice($chargePrice,$getDefCurDet['curCode']);?></td>
                                    </tr>
                                     <?php
                                }

                        //Starts order level fixed discount charges
                                $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
                                INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                                WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

                                $ordQry = mysqli_query($con, $sql);

                                $fixedCharges = 0;

                                $sql = " SELECT SUM(od.totalAmt) AS totalFixedCharges, tp.feeName FROM tbl_order_details od 
                                INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                                WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName   ";
                                $sumQry = mysqli_query($con, $sql);
                                $totalSum= mysqli_fetch_array($sumQry);

                                $totalFixedCharges=$totalSum['totalFixedCharges'];


    while($row = mysqli_fetch_array($ordQry))//show here order level charges
    {
        $fixedCharges =$row['price'];

        ?>
                                    <tr>
                                        <td><?php echo $row['feeName'];?></td>
                                        <td><?php showprice($fixedCharges,$getDefCurDet['curCode']);?></td>
                                    </tr>
     <?php
      } //Ends order lelvel fixed discount charges
      ?>
       <?php
//Start order level discoutns

      $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
      INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
      WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
      $ordQry = mysqli_query($con, $sql);

      $perCharges = 0;
      $itemIds = [];
      $totalCharges = 0;
            while($row = mysqli_fetch_array($ordQry))//show here order level charges
            {
                $itemIds[] = $row['customChargeId'];              
                $totalCharges = $row['totalAmt'];
                $perCharges += $row['totalAmt'];

                $discountPercent=$chargePrice*$totalCharges/100;
                $totalDiscountPercent=$chargePrice*$perCharges/100;
                if($row)
                {
                    ?>
                                      <tr>
                                        <td><?php echo $row['feeName'];?>
                                                <?php echo $row['price'] ?> %</td>
                                        <td><?php showprice($discountPercent,$getDefCurDet['curCode']); ?></td>
                                    </tr>
                <?php
                    }
            }//End of order level discount

//Starts order level tax discount charges
            $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
            INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
            WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
            $ordQry = mysqli_query($con, $sql);

            $taxCharges = 0;
    while($row = mysqli_fetch_array($ordQry))//show here order level charges
    {
        $tax = $row['price'];

        $taxCharges=(($chargePrice+$totalFixedCharges+$totalDiscountPercent)*$tax/100);
        $totalTaxCharges += (($chargePrice+$totalFixedCharges+$totalDiscountPercent)*$tax/100);


        ?>
         <tr>
                                        <td><?php echo $row['feeName'];?>
                                                <?php echo $row['price'] ?> %</td>
                                        <td id="netOrderLevelTaxCharges"><?php showprice($taxCharges,$getDefCurDet['curCode']) ?></td>
                                    </tr>
        <?php
        } //Ends order lelvel tax discount charges
        
        $sqlSet = "SELECT SuM(totalAmt) AS totalFixedDiscount FROM tbl_order_details od 
        INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
        WHERE od.ordId = '".$_GET['orderId']."'   AND od.account_id = '".$_SESSION['accountId']."' and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";
        $resultSet = mysqli_query($con, $sqlSet);
        $fixedDiscountRow = mysqli_fetch_array($resultSet); 
        $totalFixedDiscount= $fixedDiscountRow['totalFixedDiscount'];

        $netTotalAmt= ($chargePrice+ $totalTaxCharges+$totalDiscountPercent+$totalFixedDiscount);


        ?>  <tr class="grand-total" style=" max-height: 38px;">
                                        <th><?php echo showOtherLangText('Grand Total'); ?></th>
                                        <th><?php showprice($netTotalAmt,$getDefCurDet['curCode']); ?></th>
                                    </tr>
                                    <tr></tr>
                                </table>

                            </div>

                            <br>
                            <br>
                            <br>
                        </div>
                    </div>
                    <!-- payment Detail Popup End -->

                    <div class="payment-status-right">
                        <div class="bill-form container">
                            <div class="bill-form-brd">
                                <form action="" method="post" id="frm" name="frm" class="mtop" enctype="multipart/form-data">
                                    <div class="row table-responsive">
                                        <h6 class="bill-head">
                                            <img src="https://queue1.net/qa1/uploads/hand.png" alt="Payment-hand">
                                            Payment
                                        </h6>
                                    </div>

                                    <div class="inv-top">
                                        <table class="mr-btm">
                                            <tbody class="payDetail">
                                                <tr>
                                                    <td><?php echo showOtherLangText('Invoice'); ?> #</td>
                                                    <td>
                                                        <input type="text" style="cursor: text; background:none;" class="form-control form-control-1" onchange="getVal();" name="paymentId" id="paymentId" autocomplete="off" value="<?php echo getinvoiceNumber($paymentInfoRow['invoiceNumber']); ?>" readonly="">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Task'); ?> #</td>
                                                    <td>
                                                        <input type="text" style="cursor: text; background:none;" class="form-control form-control-1" name="ordNumber" value="<?php echo $ordersRow['ordNumber'] ?>" autocomplete="off" readonly="">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Date'); ?> #</td>
                                                    <td class="payDate">
                                                    <?php 
            if ($paymentRow['paymentStatus'] ==1 ) {

                echo $paymentRow['paymentDateTime'];

            }else{

                echo $ordersRow['ordDateTime'];
            }
            
             ?></td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="inv-detail mt-3">
                                        <table cellpadding="0" cellspacing="0" width="100%">
                                            <tbody class="frm-info">
                                                <tr class="mb-adrs">
                                                    <td><?php echo showOtherLangText('Invoice To'); ?> :</td>
                                                    <td><?php echo $paymentInfoRow['invoiceName'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo showOtherLangText('Address'); ?> :</td>
                                                    <td>
                                                   <?php echo $paymentInfoRow['invoiceAddress'] ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo showOtherLangText('Email'); ?></td>
                                                    <td>
                                                   <?php echo $paymentInfoRow['invoiceEmail'] ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo showOtherLangText('Phone'); ?></td>
                                                    <td>
                                                    <?php echo $paymentInfoRow['invoicePhone'] ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="divider-1"></div>


                                    <div class="invTotal">
                                        <table>
                                            <tbody>
                                                <tr class="payDetail">
                                                    <td><?php echo showOtherLangText('Total Amount'); ?></td>
                                                    <td class="text-center">
                                                    <?php showprice($netTotalAmt,$getDefCurDet['curCode']); ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <input type="hidden" class="form-control" name="TotalAmt" id="TotalAmt" value="90.9088" autocomplete="off">
                                    <input type="hidden" class="form-control" name="currencyId" id="currencyId" value="" autocomplete="off">
                                    <?php 
$orderId=$paymentRow['orderId'];

$sql= " SELECT * FROM tbl_req_payment  WHERE orderId='".$orderId."'  AND account_id = '".$_SESSION['accountId']."'   order by id limit 1  ";
$resultSetQry = mysqli_query($con, $sql); 
$payRow = mysqli_fetch_array($resultSetQry);


$sqlSet = " SELECT * FROM tbl_payment_mode WHERE id='".$payRow['paymentType']."'  AND account_id = '".$_SESSION['accountId']."' ";
$resultSet = mysqli_query($con, $sqlSet);
$payModeRow = mysqli_fetch_array($resultSet);

?>
                                    <div class="paySelect">
                                        <table cellpadding="0" cellspacing="0" width="100%" style="margin-top: 20px;">
                                            <tbody class="form-info">
                                                <tr class="" style="height: 48px;">
                                                    <td><?php echo showOtherLangText('Payment Type'); ?></td>
                                                    <td><?php echo $payModeRow['modeName'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo showOtherLangText('Account'); ?></td>
                                                    <td><?php
            $sqlSet = " SELECT * FROM  tbl_accounts WHERE id='".$payRow['bankAccountId']."'  AND account_id = '".$_SESSION['accountId']."' ";
            $resultSet = mysqli_query($con, $sqlSet);
            $accRow = mysqli_fetch_array($resultSet);

            $balanceAmt = $accRow['balanceAmt'];

            if ($currencyCode =='')
            {
                $totalPaidAmt = trim($payRow['amount'],$getDefCurDet['curCode']);

            }else{

               $totalPaidAmt = trim($payRow['amount'],$currencyCode);
           }

           $totalPaidAmt = str_replace(',', '', $totalPaidAmt);

           if ($payRow['paymentStatus']==0)
           {
             $totalBalanceAmt = ($balanceAmt+$totalPaidAmt);

         }else{

            $totalBalanceAmt = ($balanceAmt);

        }

        ?></td>
                                                </tr>


                                                <tr>
                                                    <td><?php echo showOtherLangText('Account No.'); ?></td>
                                                    <td><?php echo $accRow['accountNumber'] ?></td>
                                                </tr>
                                                   <?php
    if ($paymentRow['currencyId'] ==0) 
        { ?>
                                                <tr>
                                                    <td><?php echo showOtherLangText('Balance'); ?></td>
                                                    <td><?php showPrice($totalBalanceAmt,$getDefCurDet['curCode']) ?></td>
                                                </tr>
                 <?php }else{ ?>
                                <tr>
                                                    <td><?php echo showOtherLangText('Balance'); ?></td>
                                                    <td><?php echo showOtherCur($totalBalanceAmt, $payRow['currencyId']) ?></td>
                                                </tr>
                    <?php } ?>
                                                <tr>
                                                    <td><?php echo showOtherLangText('Currency'); ?> </td>
                                                  <?php  
            $sqlSet = " SELECT * FROM tbl_currency WHERE id='".$payRow['currencyId']."'  AND account_id = '".$_SESSION['accountId']."' ";
            $resultSet = mysqli_query($con, $sqlSet);
            $currRow = mysqli_fetch_array($resultSet);

            if ($currRow['id']==0)
            {
                $curCode = $getDefCurDet['curCode']; 
                ?>
                            <td><?php echo $getDefCurDet['currency'] ?></td>

                            <?php }else{
                $curCode = $currRow['curCode'];
                ?>
                            <td><?php echo $currRow['currency'] ?></td>

                            <?php } ?>

                        </tr>

                                              <tr>
                            <td><?php echo showOtherLangText('Amount'); ?></td>
                            <td><?php echo $payRow['amount'];?></td>
                        </tr>

                                            </tbody>
                                        </table>

                                    </div>

                                    <?php

                                    if ($payRow['paymentStatus']==1 ) { ?>
                                    <div class="allBtn my-3 d-flex justify-content-between align-items-center w-100">
                                        <div>
                                            <button onClick="window.location.href='history.php'" class="btn wht-btn" type="button"><?php echo showOtherLangText('Back'); ?></button>
                                        </div>
                                        
                                        <div>
                                            <a id="refundBtn"
    href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#refund_model" class="btn final-btn" type="submit" name="submitBtn"><?php echo showOtherLangText('Refund'); ?></a>
                                        </div>
                                    </div>
                                <?php }else{ ?>
                                      <div class="allBtn my-3 d-flex justify-content-between align-items-center w-100">
                                        <div>
                                            <button onClick="redirectToParentPage()" class="btn wht-btn" type="button"><?php echo showOtherLangText('Cancel'); ?></button>
                                        </div>
                                        
                                        <div>
                                            <button class="btn final-btn" type="button" onClick="redirectToHistory()" name="submitBtn"><?php echo showOtherLangText('Done'); ?></button>
                                        </div>
                                    </div>
                                  <?php  }  ?>

                                </form>

                                <!-- start of add fee (item/order level) form -->
                                <!-- Modal box form start from here -->
                                <form action="" name="addServiceFrm" id="addServiceFrm" method="post" autocomplete="off">
                                    <!-- The Modal 1 -->
                                    <div id="myModal" class="modal">
                                        <!-- Modal content -->
                                        <div class="modal-content openModal">
                                            <div class="mdlHead-Popup">
                                                <p>Add service item</p>
                                                <span class="close">×</span>
                                            </div>

                                            <table cellpadding="0" cellspacing="0" width="100%">

                                                <tbody>
                                                    <tr>
                                                        <td>

                                                            <table cellpadding="0" cellspacing="0">

                                                                <tbody>
                                                                    <tr>
                                                                        <td class="feePopup">Service name </td>
                                                                        <td><input type="text" class="form-control" name="itemName" id="itemName" value="" autocomplete="off">
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="feePopup">Amount</td>
                                                                        <td><input type="text" class="form-control" name="itemFeeAmt" id="feeAmt" value="" autocomplete="off">
                                                                        </td>
                                                                    </tr>


                                                                    <tr>
                                                                        <td class="feePopup">Unit</td>
                                                                        <td><input type="text" class="form-control" name="unit" id="unit" autocomplete="off">
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>

                                                        </td>


                                                    </tr>
                                                </tbody>
                                            </table>

                                            <div>
                                                <div class="feeSave feeNrm">
                                                    <input type="checkbox" id="visibility" name="visibility" value="1">
                                                    <label for="visibility">
                                                        Save to fixed service item list</label><br>
                                                </div>
                                            </div>



                                            <div>
                                                <div><button class="btn wht-btn" type="submit">Add</button>&nbsp; &nbsp;
                                                    <button class="btn wht-btn" id="backBtn" type="button">Back</button>
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </form>


                                <form action="" name="addFeeFrm" id="addFeeFrm" method="post" autocomplete="off">

                                    <!-- The Modal 2 -->
                                    <div id="myModal2" class="modal">

                                        <!-- Modal content -->
                                        <div class="modal-content openModal">
                                            <div class="mdlHead-Popup">
                                                <p>Add fee</p>
                                                <span class="close2">×</span>
                                            </div>


                                            <table cellpadding="0" cellspacing="0" width="100%">

                                                <tbody>
                                                    <tr>
                                                        <td>

                                                            <table cellpadding="0" cellspacing="0">


                                                                <tbody>
                                                                    <tr>
                                                                        <td class="feePopup">Fee name </td>
                                                                        <td><input type="text" class="form-control" name="feeName" id="feeName" value="" style="width:250px;" autocomplete="off">
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="feePopup">Fee type </td>
                                                                        <td>
                                                                            <select class="form-control" name="feeType" id="typeOfFee">
                                                                                <option value="2">
                                                                                    Fixed fee </option>
                                                                                <option value="3">
                                                                                    Percentage fee </option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="feePopup feeFixed" id="feeFixed">
                                                                            <span>Fee amount</span>
                                                                        </td>
                                                                        <td class="feePopup feePercent" id="feePercent" style="display: none;">
                                                                            <span>Fee percentage %</span>
                                                                        </td>
                                                                        <td>
                                                                            <div class="stepper "><input type="number" class="form-control stepper-input" name="amt" id="amt" value="" style="width:250px;" autocomplete="off"><span class="stepper-arrow up">Up</span><span class="stepper-arrow down">Down</span></div>

                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>


                                            <div class="fxdFee">
                                                <div class="feeSave">
                                                    <input type="checkbox" name="feeType" id="feeType" value="1">
                                                    <label for="feeType">This is a tax fee</label><br>
                                                </div>
                                                <div class="feeSave feeNrm">
                                                    <input type="checkbox" id="visibility" name="visibility" value="1">
                                                    <label for="visibility">Save to fixed service item list </label><br>
                                                </div>

                                            </div>


                                            <!-- Modal form add/back button -->
                                            <div class="modal-footer" style="border-top: 1px solid #ffffff;">
                                                <button class="btn wht-btn" type="submit" name="AddBtn">Add</button>&nbsp; &nbsp;
                                                <button class="btn wht-btn" type="button" id="backBtn2">Back</button>
                                            </div>

                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    </div>
    <form action="" method="post" id="frm" name="frm">
    <input type="hidden" name="orderId" value="<?php echo $_GET['orderId'] ?>">
    <!-- View ckecknox Popup Start -->
    <div class="modal" tabindex="-1" id="refund_model" aria-labelledby="refund_model" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md site-modal">
            <div class="modal-content ">
                <div>
                    <div class="mdlHead-Popup d-flex justify-content-between align-items-center">
                        <span>
                            <strong><?php echo showOtherLangText('Please select one of the following to refund'); ?>:</strong>
                        </span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <br>

                    <div class="form-group  d-flex payModal-Flex">
<input type="radio" name="refund" value="refundAmt" id="refundAmt">
<div style="color: #000; width: 94%;">
    <p><?php echo showOtherLangText('Refund the amount to account only.'); ?>
    </p>
</div>
</div>
                         <br> <br><p>
                            <button type="submit" name="approveBtn" id="approveBtn" class="btn btn-secondary dropdown-toggle fs-13 py-2" style="border:1px solid #7a89ff; background-color: #7a89ff; color: #fff;"><?php echo showOtherLangText('Approve'); ?></button>
                            
                            <a class="btn btn-secondary dropdown-toggle fs-13 py-2" id="cancelBtn" style="border:1px solid #7a89ff; background-color: #7a89ff; color: #fff;"><?php echo showOtherLangText('Cancel'); ?></a>

                          
                        </p>              
                   
                </div>

            </div>

        </div>
    </div>
    </form>
    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
    <!-- Links for datePicker and dialog popup -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script type="text/javascript" src="cdn_js/jquery-ui-1.12.1.js"></script>
    <script>
        $(function() {
            $(".datepicker").datepicker({
                dateFormat: 'dd-mm-yy'
            });

            $(".date_type").on("click", "a", function(e) {
                var $this = $(this).parent();
                $("#dateTypeText").text($this.data("value"));
                $("#dateType").val($this.data("id"));
                $('#frm').submit();
            });

            $(".type_dropdown").on("click", "a", function(e) {
                var $this = $(this).parent();
                $("#TypeText").text($this.data("value"));
                $("#ordType").val($this.data("id"));
                $('#frm').submit();
            });

            $(".referto_dropdown").on("click", "a", function(e) {
                var $this = $(this).parent();
                $("#refertotext").text($this.data("value"));
                $("#suppMemStoreId").val($this.data("id"));
                $('#frm').submit();
            });

            $(".status_type").on("click", "a", function(e) {
                var $this = $(this).parent();
                $("#statusText").text($this.data("value"));
                $("#statusType").val($this.data("id"));
                $('#frm').submit();
            });

            $(".account_dropdown").on("click", "a", function(e) {
                var $this = $(this).parent();
                $("#accountTxt").text($this.data("value"));
                $("#accountNo").val($this.data("id"));
                $('#frm').submit();
            });

            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('dateType')) {
                var dateTypeID = urlParams.get('dateType');
                if (dateTypeID !== '') {
                    $("#dateTypeText").text($(".date_type .selected").text());
                }
            }
            if (urlParams.has('ordType')) {
                var ordTypeID = urlParams.get('ordType');
                if (ordTypeID !== '') {
                    $("#TypeText").text($(".type_dropdown .selected").text());
                }
            }
            if (urlParams.has('suppMemStoreId')) {
                var suppMemStoreId = urlParams.get('suppMemStoreId');
                if (suppMemStoreId !== '') {
                    $("#refertotext").text($(".referto_dropdown .selected").text());
                }
            }
            if (urlParams.has('statusType')) {
                var statusType = urlParams.get('statusType');
                if (statusType !== '') {
                    $("#statusText").text($(".status_type .selected").text());
                }
            }
            if (urlParams.has('accountNo')) {
                var accountNo = urlParams.get('accountNo');
                if (accountNo !== '') {
                    $("#accountTxt").text($(".account_dropdown .selected").text());
                }
            }

        });

        function getDelNumb(delOrderId) {

            $("#dialog").dialog({
                autoOpen: false,
                modal: true,
                //title     : "Title",
                buttons: {
                    '<?php echo showOtherLangText('Yes') ?>': function() {
                        //Do whatever you want to do when Yes clicked
                        $(this).dialog('close');
                        window.location.href = 'history.php?delOrderId=' + delOrderId;
                    },

                    '<?php echo showOtherLangText('No') ?>': function() {
                        //Do whatever you want to do when No clicked
                        $(this).dialog('close');
                    }
                }
            });

            $("#dialog").dialog("open");
            $('.custom-header-text').remove();
            $('.ui-dialog-content').prepend('<div class="custom-header-text"><span><?php echo showOtherLangText('Queue1.com Says') ?></span></div>');
        }

        $('.date-box-search').click(function() {

            $('#frm').submit();

        });
    </script>
    <script>
function redirectToHistory() {
    var pageName = "history.php?orderId=<?php echo $_GET['orderId'];?>&reqPaymentStatus=1";
    window.location.href = pageName;
}
</script>
    <script>
function redirectToParentPage() {
var pageName = "requisitionPaymentDetail.php?orderId=<?php echo $_GET['orderId'];?>&cancelPayment=1";
window.location.href = pageName
}
</script>
    <div id="dialog" style="display: none;">
        <?php echo showOtherLangText('Are you sure to delete this record?') ?>
    </div>
</body>

</html>