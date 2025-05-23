<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'edit_requisition' AND type_id = '0' AND designation_id = '".$_SESSION['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if ($permissionRow)
{
echo "<script>window.location='index.php'</script>";
}


// Get column permission of requisition
$getColumnPermission = get_column_permission($_SESSION['designation_id'], $_SESSION['accountId'], 2);

if (!isset($_SESSION['ordDeptId']))
{
    $selQry = " SELECT * FROM tbl_orders WHERE id='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."' ";
    $ordResult = mysqli_query($con, $selQry);
    $ordRow = mysqli_fetch_array($ordResult);

    $_SESSION['ordDeptId'] = $ordRow['deptId'];
}

if (isset($_GET['orderId'])) 
{

    $selQry = " SELECT * FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."' ";
    $tempOrdDetResult = mysqli_query($con, $selQry);
    $tempOrdDetRow = mysqli_fetch_array($tempOrdDetResult);
    
    if ( empty($tempOrdDetRow) )
    {
            $selQry = " SELECT * FROM tbl_order_details WHERE ordId='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."' ";
            $ordDetResult = mysqli_query($con, $selQry);
            while($ordDetRow = mysqli_fetch_array($ordDetResult))
            {
                $values[] .= " (NULL, 
                '".$_SESSION['accountId']."',
                '".$_GET['orderId']."', 
                '".$ordDetRow['pId']."',
                '".$ordDetRow['factor']."',
                '".$ordDetRow['price']."',
                '".$ordDetRow['requestedQty']."',
                '".$ordDetRow['qty']."',
                '".$ordDetRow['qtyReceived']."',
                '".$ordDetRow['totalAmt']."',
                '".$ordDetRow['note']."',
                '".$ordDetRow['lastPrice']."',
                '".$ordDetRow['stockPrice']."',
                '".$ordDetRow['stockQty']."',
                '".$ordDetRow['curPrice']."',
                '".$ordDetRow['currencyId']."',
                '".$ordDetRow['curAmt']."',
                '".$ordDetRow['customChargeId']."',
                '".$ordDetRow['customChargeType']."') ";
        
            }
            
            $insertQry = " INSERT INTO `tbl_order_details_temp` (`id`, `account_id`, `ordId`, `pId`, `factor`, `price`, `requestedQty`, `qty`, `qtyReceived`, `totalAmt`,`note`, `lastPrice`, `stockPrice`, `stockQty`, `curPrice`, `currencyId`, `curAmt`, `customChargeId`, `customChargeType`) VALUES  ".implode(',', $values);
                mysqli_query($con, $insertQry);
    }

}



//Update order
if(isset($_POST['updateOrder']))
{

    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $resRowOld = mysqli_fetch_array($res);
    
    
    foreach($_POST['productIds'] as $productId)
    {
            
            if( isset($_POST['qty'][$productId]) && $_POST['qty'][$productId] < 1 )
            {
                 $Qry = " DELETE FROM  `tbl_order_details` WHERE ordId = '".$_GET['orderId']."' AND pId = '".$productId."' ";
                 mysqli_query($con, $Qry);
            }
            elseif( isset($_POST['qty'][$productId]) && $_POST['qty'][$productId] > 0 )
            {
            
                $prodPrice = str_replace(',', '', $_POST['price'][$productId]);
                //check stock qty
                $sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$productId."'  AND account_id = '".$_SESSION['accountId']."' ";
                $stkQry = mysqli_query($con, $sql);
                $stkRow = mysqli_fetch_array($stkQry);
            
            
                //Add new product in tbl_order_details on update button
                $sqlQry = " SELECT * FROM tbl_order_details WHERE ordId='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."' AND pId='".$productId."' ";
                $orderDetailsResult = mysqli_query($con, $sqlQry);
                $orderDetailsResRow = mysqli_fetch_array($orderDetailsResult);
            
                    if ( !isset($orderDetailsResRow['pId']) )
                    {
                        $sql = "INSERT INTO `tbl_order_details` SET
                        `ordId` = '".$_GET['orderId']."',
                        `pId` = '".$productId."',
                        `price` = '".$prodPrice."',
                        `requestedQty` = '".$_POST['qty'][$productId]."',
                        `qty` = '".$_POST['qty'][$productId]."',
                        `totalAmt` = '".($prodPrice*$_POST['qty'][$productId])."',
                        `note` = '".$_POST['notes'][$productId]."',
                        `lastPrice` = '".$stkRow['lastPrice']."',
                        `stockPrice` = '".$stkRow['stockPrice']."',
                        `stockQty` = '".($stkRow['qty'] - $_POST['qty'][$productId])."',
                        `account_id` = '".$_SESSION['accountId']."'   ";
                        mysqli_query($con, $sql);
                       
                    }
                    else
                    {    
                        $upQry = " UPDATE  `tbl_order_details` SET
                        `price` = '".$prodPrice."', 
                        `qty` = '".$_POST['qty'][$productId]."', 
                        `totalAmt` = '".($prodPrice*$_POST['qty'][$productId])."', 
                        `note` = '".$_POST['notes'][$productId]."',
                        `lastPrice` = '".$stkRow['lastPrice']."',
                        `stockPrice` = '".$stkRow['stockPrice']."',
                        `stockQty` = '".($stkRow['qty'] - $_POST['qty'][$productId])."'
                        WHERE ordId = '".$_GET['orderId']."' AND pId = '".$productId."'  AND account_id = '".$_SESSION['accountId']."' ";
                        
                        mysqli_query($con, $upQry);
                    }
                
                }//end if
            
            }//End foreach

    $sql=" SELECT * FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' and customChargeType > 0 ";
    $sqlSet= mysqli_query($con, $sql);  
    while($tempOrdDetRow = mysqli_fetch_array($sqlSet))
    {
    
            $sql=" SELECT * FROM tbl_order_details WHERE ordId='".$tempOrdDetRow['ordId']."' AND account_id = '".$_SESSION['accountId']."' and customChargeId='".$tempOrdDetRow['customChargeId']."' and customChargeType='".$tempOrdDetRow['customChargeType']."'  ";
            $ordQryData= mysqli_query($con, $sql);  
            $ordDetRowCheck = mysqli_fetch_array($ordQryData);
            
            if ( $ordDetRowCheck )
            { 
            
            $updateQry = " UPDATE tbl_order_details SET note='".$tempOrdDetRow['note']."' WHERE ordId='".$tempOrdDetRow['ordId']."' AND account_id = '".$_SESSION['accountId']."' and customChargeId='".$tempOrdDetRow['customChargeId']."' and customChargeType='".$tempOrdDetRow['customChargeType']."' ";
            mysqli_query($con, $updateQry); 
            
            }
            else
            {
            
            
            $insertQry = " INSERT INTO `tbl_order_details`
            SET `account_id` = '".$tempOrdDetRow['account_id']."',
            `ordId` = '".$tempOrdDetRow['ordId']."',
            `note` = '".$tempOrdDetRow['note']."',
            `customChargeType` = '".$tempOrdDetRow['customChargeType']."',
            `customChargeId` = '".$tempOrdDetRow['customChargeId']."',
            `price` = '".$tempOrdDetRow['price']."',
            `qty` = '1',
            `totalAmt` = '".$tempOrdDetRow['totalAmt']."' ";
            
            mysqli_query($con, $insertQry); 
            
            }
    
    }//end while


        //set order net value
        requisitionTotalValue($_GET['orderId']);
        

        //Insert few data in order journey tbl to show journey 
    $sql = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $res = mysqli_query($con, $sql);
    $resRow = mysqli_fetch_array($res);
//echo $resRowOld['ordAmt'] .'-'. $resRow['ordAmt'];
//echo "<br>";
    $diffPrice =  $resRow['ordAmt']-$resRowOld['ordAmt'];
     $notes = 'Requisition edited(Price Diff: '.$diffPrice .' )';

    $qry = " INSERT INTO `tbl_order_journey` SET 
    `account_id` = '".$_SESSION['accountId']."',
    `orderId` = '".$resRow['id']."',
    `userBy`  = '".$_SESSION['id']."',
    `ordDateTime` = '".date('Y-m-d h:i:s')."',
    `amount` = '".$resRow['ordAmt']."',
    `orderType` = '".$resRow['ordType']."',
    `notes` = '".$notes."',
    `action` = 'edit' ";
    mysqli_query($con, $qry);

        
        //delete order_details_temp data after form submit
        $delQry=" DELETE FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id='".$_SESSION['accountId']."' ";
        mysqli_query($con, $delQry);
        
        echo '<script>window.location="runningOrders.php?updated=1&orderId='.$_GET['orderId'].'"</script>';

}//end udpdate order

//add item fee & custom fee modal box 
$sql = " SELECT * FROM tbl_custom_items_fee WHERE visibility='1' AND account_id='".$_SESSION['accountId']."' ";
$result = mysqli_query($con, $sql);

$sqlQry = " SELECT * FROM tbl_order_fee WHERE visibility='1' AND account_id='".$_SESSION['accountId']."' ";
$ordFeeFetch = mysqli_query($con, $sqlQry);


//Add item charges in list
if( isset($_GET['itemCharges']) && $_GET['itemCharges'] > 0 && $_GET['feeType']=='1' )
{
    editCustomCharge($_GET['orderId'],$_GET['feeType'], $_GET['itemCharges'],$_SESSION['ordDeptId'], 1);
}

//Add order charges in list
if( isset($_GET['itemCharges']) && $_GET['itemCharges'] > 0 && $_GET['feeType']=='3' )
{
    editCustomCharge($_GET['orderId'],$_GET['feeType'], $_GET['itemCharges'],$_SESSION['ordDeptId'], 1);
}


if( !empty($_POST['itemName']) )
{ 
    $showHideInList = isset($_POST['visibility']) ? 1 : 0;
    
    $sql = "INSERT INTO `tbl_custom_items_fee` SET 
    `itemName` = '".$_POST['itemName']."',
    `unit` = '".$_POST['unit']."',
    `amt` = '".$_POST['itemFeeAmt']."',
    `visibility` = '".$showHideInList."',
    `account_id` = '".$_SESSION['accountId']."' 
    ";
    mysqli_query($con, $sql);
    $itemCharges = mysqli_insert_id($con);
    
    editCustomCharge($_GET['orderId'],1,$itemCharges,$_SESSION['ordDeptId'], 1);
    
    echo '<script>window.location="editRequisition.php?orderId='.$_GET['orderId'].'"</script>';

}


if( !empty($_POST['feeName']) )
{ 
    $showHideInList = isset($_POST['visibility']) ? 1 : 0;
    
    $sql = "INSERT INTO `tbl_order_fee` SET 
    `feeName` = '".$_POST['feeName']."',
    `feeType` = '".$_POST['feeType']."',
    `amt` = '".$_POST['amt']."',
    `visibility` = '".$showHideInList."',
    `account_id` = '".$_SESSION['accountId']."' 
    ";
    
    mysqli_query($con, $sql);
    
    $itemCharges = mysqli_insert_id($con);
    
    editCustomCharge($_GET['orderId'],3,$itemCharges,$_SESSION['ordDeptId'], 1);
    
    echo '<script>window.location="editRequisition.php?orderId='.$_GET['orderId'].'"</script>';

}
//end

//delete item level / order level charges
if( isset($_GET['delId']) && $_GET['orderId'])
{ 

    $sql=" SELECT * FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' and id='".$_GET['delId']."' ";
    $sqlSet= mysqli_query($con, $sql);  
    $tempOrdDetRow = mysqli_fetch_array($sqlSet);
    
    $sql= " DELETE FROM tbl_order_details WHERE ordId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' and customChargeId='".$tempOrdDetRow['customChargeId']."' and customChargeType='".$tempOrdDetRow['customChargeType']."' ";
    $resultSet= mysqli_query($con, $sql);
    
    $sql= " DELETE FROM tbl_order_details_temp WHERE id='".$_GET['delId']."' AND account_id = '".$_SESSION['accountId']."' ";
    $resultSet= mysqli_query($con, $sql);
    
    
    echo '<script>window.location="editRequisition.php?orderId='.$_GET['orderId'].'&delete=1"</script>';

}//end 

$cond = '';

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Edit Requisition - Queue1</title>
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

    <div class="container-fluid newOrder">
        <div class="row">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                <?php require_once('nav.php');?>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('Edit Requisition'); ?></h1>
                        </div>
                        <div class="col-md-8 d-flex align-items-center justify-content-end">
                            <div class="mbPage">
                                <div class="mb-nav" id="mb-nav">
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                        aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"><i class="fa-solid fa-bars"></i></span>
                                    </button>
                                </div>
                                <div class="mbpg-name">
                                    <h1 class="h1"><?php echo showOtherLangText('Edit Requisition'); ?></h1>
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
                <?php
$sqlSet = " SELECT * FROM tbl_orders WHERE id = '".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
$resultSet = mysqli_query($con, $sqlSet);
$ordRow = mysqli_fetch_array($resultSet);
?>
                <section class="ordDetail">
                    <div class="tpBar-grn tpBar-red"></div>
                    <form action="" id="frm" name="frm" method="post" autocomplete="off">
                        <div class="stcPart position-relative">
                            <div class="container cntTable pt-4 pt-lg-5">
                                <!-- topOrder edtReq nwOrder-Div-->
                                <div class="sltSupp nwOrd-Num position start-0 p-0" style="top:2rem;">
                                    <div class="ord-Box w-100 ms-0">
                                        <div class="ordNum m-0">
                                            <h4 class="subTittle1"><span>Order#:</span>
                                                <span><?php echo $ordRow['ordNumber'];?></span>
                                            </h4>
                                        </div>
                                        <!-- <div class="ordDate">
                                        <h4 class="subTittle1">23/05/2022</h4>
                                    </div> -->
                                    </div>

                                    <input type="hidden" name="updateOrder" class="btn btn-primary"
                                        value="<?php echo showOtherLangText('Update Requisition'); ?>" />
                                    <div class="ord-Box w-100 ms-0">
                                        <div class="ordNum m-0">
                                            <h4 class="subTittle1">
                                                <span><?php echo showOtherLangText('Requisition By'); ?>:</span> <span><?php
                                        $sqlSet = " SELECT o.recMemberId, du.* FROM tbl_orders o  
                                        INNER JOIN tbl_deptusers du ON(o.recMemberId=du.id) AND o.account_id=du.account_id
                                        WHERE o.deptId = '".$_SESSION['ordDeptId']."'  AND o.account_id = '".$_SESSION['accountId']."' AND o.id='".$_GET['orderId']."' ";
                                        $resultSet = mysqli_query($con, $sqlSet);

                                        $deptUserRow = mysqli_fetch_array($resultSet);

                                        echo $deptUserRow['name'];

                                        ?></span>
                                            </h4>
                                        </div>

                                    </div>
                                </div>

                                <div class="row gy-2 gx-0 gx-lg-4">
                                    <!-- <div class="ordInfo edtreqInfo newFeatures"> -->
                                    <div class="col-lg-9 col-xl-10">
                                        <div class="mbFeature">
                                            <div class="">
                                                <div class="text-center">
                                                    <div class="featRow ms-auto w-100" style="max-width:118px;">
                                                        <div class="ordFeature w-100 drpFee position-relative">
                                                            <a href="javascript:void(0)" class="dropdown-toggle tabFet"
                                                                role="button" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <span class="fee"></span>
                                                                <p class="btn2">
                                                                    <?php echo showOtherLangText('Fee'); ?> <i
                                                                        class="fa-solid fa-angle-down"></i>
                                                                </p>
                                                            </a>

                                                            <ul class="item dropdown-menu"
                                                                style="left: -34% !important;">
                                                                <li class="innerDrop  dropdown">
                                                                    <a class="dropdown-item"
                                                                        href="javascript:void(0)"><?php echo showOtherLangText('Service Item'); ?></a>

                                                                    <ul class="subitem submenu list-unstyled">
                                                                        <?php
                                                                    //add item fee & custom fee modal box 
                                                                    $sql = " SELECT * 
                                                                    FROM tbl_custom_items_fee 
                                                                    WHERE visibility='1' AND account_id='".$_SESSION['accountId']."' ";
                                                                    $customItemsResult = mysqli_query($con, $sql);

                                                                    //$liCount = 0;
                                                                    while ($resultRow = mysqli_fetch_array($customItemsResult)) 
                                                                    {
                                                                        //$liCount++;
                                                                        echo "<li class='innerLi'><a class='dropdown-item' tabindex='-1' href='editRequisition.php?orderId=".$_GET['orderId']."&feeType=1&itemCharges=".$resultRow['id']." ' >".$resultRow['itemName']."</a></li>";
                                                                    } 
                                                                    ?>
                                                                    </ul>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        class="sub-btn std-btn mb-usrBkbtn"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#new-service-item"
                                                                        href="javascript:void(0)"><?php echo showOtherLangText('New Service Item'); ?></a>
                                                                </li>
                                                                <li class="innerDrop dropdown">
                                                                    <a class="item dropdown-item"
                                                                        href="javascript:void(0)"><?php echo showOtherLangText('Fee'); ?></a>
                                                                    <ul class="subitem submenu large list-unstyled">
                                                                        <?php
                                                                        //add item fee & custom fee modal box 
                                                                        $sqlQry = " SELECT * FROM tbl_order_fee WHERE visibility='1' AND account_id='".$_SESSION['accountId']."' ";
                                                                        $ordFeeFetch = mysqli_query($con, $sqlQry);
                                                                        //$innerLiCount = 0;
                                                                        while ($resultRow = mysqli_fetch_array($ordFeeFetch))
                                                                        {
                                                                            // $innerLiCount++;
                                                                            echo "<li class='innerLi'><a class='dropdown-item' tabindex='-1' href='editRequisition.php?orderId=".$_GET['orderId']."&feeType=3&itemCharges=".$resultRow['id']."'>".$resultRow['feeName']."</a> ";
                                                                        } 
                                                                        ?>
                                                                    </ul>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#new-fees-item"><?php echo showOtherLangText('New Fee') ?></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="smBtn nwNxt-Btn"> -->
                                    <div class="col-lg-3 col-xl-2">
                                        <div class="d-flex flex-row flex-lg-column gap-3 justify-content-between ps-lg-4 ps-xl-5"> 
                                            <div class="btnBg">
                                                <a href="javascript:void(0)" class="btn sub-btn form-submit-btn w-lg-100">
                                                    <span class="align-middle">Update</span> <i class="fa-solid fa-angle-right"></i>
                                                </a>
                                            </div>
                                            <div class="btnBg">
                                                <a href="runningOrders.php" class="sub-btn std-btn update w-lg-100">Back</a>
                                            </div>
                                            <div class="fetBtn">
                                                <a href="javascript:void(0)">
                                                    <img src="Assets/icons/dashboard.svg" alt="dashboard">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container cntTable mt-lg-4">
                                <div class="row g-4">
                                    <div class="sltSupp">
                                        <!-- Select Supplier -->
                                        <!-- <div class="btn-group glb-btn">
                                        <button type="button"
                                            class="btn body3 drp-btn dropdown-toggle dropdown-toggle-split d-flex align-items-center justify-content-between"
                                            data-bs-toggle="dropdown" aria-expanded="false"><span>Bar</span> <i
                                                class="fa-solid fa-angle-down"></i></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0)">Kitchen</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">House Keeping</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Others</a>
                                            </li>
                                        </ul>
                                    </div> -->

                                        <!-- <div class="btn-group glb-btn">
                                        <button type="button"
                                            class="btn body3 drp-btn dropdown-toggle dropdown-toggle-split d-flex align-items-center justify-content-between"
                                            data-bs-toggle="dropdown" aria-expanded="false"><span>Requisition By:</span>
                                            <i class="fa-solid fa-angle-down"></i></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0)">Requisition 1</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Requisition 2</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Requisition 3</a>
                                            </li>
                                        </ul>
                                    </div> -->

                                        <div class="input-group srchBx">
                                            <input type="search" id="search1" class="form-control" placeholder="Search"
                                                onKeyUp="myFunction('search1', 'newOrdTask', 1)" aria-label="Search">
                                            <div class="input-group-append">
                                                <button class="btn" type="button">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                    <?php
                                        if ($getColumnPermission['item_price'] == 1) {
                                    ?>
                                    <div class="ordInfo edtreqInfo" id="totalOrdArea">
                                        <div class="">
                                            <div class="prcTable">
                                                <?php 
                                                //get the sum of all product and item level charges 
                                                $sqlSet="SELECT SUM(totalAmt) AS totalAmt FROM tbl_order_details_temp WHERE ordId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."'  AND (customChargeType='1' OR customChargeType='0')";
                                                $resultSet = mysqli_query($con, $sqlSet);
                                                $chargeRow = mysqli_fetch_array($resultSet);    
                                                $chargePrice=$chargeRow['totalAmt'];

                                                //to find order level charge
                                                $ordCount="SELECT * from tbl_order_details_temp where ordId='".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."' AND customChargeType='2' ";
                                                $ordCountResult = mysqli_query($con, $ordCount);
                                                $ordCountRow = mysqli_num_rows($ordCountResult);

                                                if ($ordCountRow > 0)
                                                { ?>
                                                <div class="price justify-content-between">
                                                    <div class="p-2 delIcn text-center"></div>
                                                    <div class="p-2 txnmRow">
                                                        <p><?php echo showOtherLangText('Sub Total'); ?></p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p><?php echo getPriceWithCur($chargePrice,$getDefCurDet['curCode']);?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php }//Starts order level fixed discount charges
                                                $sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details_temp od 
                                                INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                                                WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."' and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

                                                $ordQry = mysqli_query($con, $sql);

                                                $fixedCharges = 0;
                                                $fixedChargesOther = 0;
                                                while($row = mysqli_fetch_array($ordQry))//show here order level charges
                                                {
                                                    $fixedCharges += $row['price'];
                                                    ?>
                                                <div class="price justify-content-between taxRow">
                                                    <div class="p-2 delIcn text-center">
                                                        <a title="<?php echo showOtherLangText('Delete') ?>"
                                                            href="javascript:void(0)"
                                                            onClick="getDelNumb('<?php echo $row['id'] ?>', '<?php echo $row['ordId'] ?>');">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </a>
                                                    </div>
                                                    <div class="p-2 txnmRow">
                                                        <p><?php echo $row['feeName'];?></p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="px-2 text-end">
                                                            <p><?php echo getPriceWithCur($row['price'],$getDefCurDet['curCode']);?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                } //Ends order lelvel fixed discount charges

                                                //Starts order level per discount charges
                                                $sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details_temp od 
                                                INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                                                WHERE od.ordId = '".$_GET['orderId']."' AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
                                                $ordQry = mysqli_query($con, $sql);

                                                $perCharges = 0;
                                                while($row = mysqli_fetch_array($ordQry))//show here order level charges
                                                {
                                                    $perCharges += $row['price'];
                                                    $calDiscount = ($chargePrice*$row['price']/100);
                                                    ?>
                                                <div class="price justify-content-between taxRow">
                                                    <div class="p-2 delIcn text-center">
                                                        <a title="<?php echo showOtherLangText('Delete') ?>"
                                                            href="javascript:void(0)"
                                                            onClick="getDelNumb('<?php echo $row['id'] ?>', '<?php echo $row['ordId'] ?>');">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </a>
                                                    </div>
                                                    <div class="p-2 txnmRow">
                                                        <p><?php echo $row['feeName'];?><?php echo $row['price'] ?> %
                                                        </p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p><?php echo getPriceWithCur($calDiscount,$getDefCurDet['curCode']);?>
                                                            </p>
                                                        </div>

                                                    </div>
                                                </div>


                                                <?php
                                                } //Ends order lelvel per discount charges

                                                $totalCalDiscount =($chargePrice*$perCharges/100);//total discount feeType=3

                                                $sql = "SELECT od.*, tp.feeName, tp.feeType FROM tbl_order_details_temp od 
                                                INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                                                WHERE od.ordId = '".$_GET['orderId']."' AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
                                                $ordQry = mysqli_query($con, $sql);


                                                $taxCharges = 0;
                                                while($row = mysqli_fetch_array($ordQry))//show here order level charges
                                                {
                                                    $taxCharges += $row['price'];
                                                    $calTax = (($chargePrice+ $fixedCharges+$totalCalDiscount)*$row['price']/100);

                                                    ?><div class="price justify-content-between taxRow">
                                                    <div class="p-2 delIcn text-center">
                                                        <a title="<?php echo showOtherLangText('Delete') ?>"
                                                            href="javascript:void(0)"
                                                            onClick="getDelNumb('<?php echo $row['id'] ?>', '<?php echo $row['ordId'] ?>');">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </a>
                                                    </div>
                                                    <div class="p-2 txnmRow">
                                                        <p><?php echo $row['feeName'];?> <?php echo $row['price'] ?> %
                                                        </p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p><?php echo getPriceWithCur($calTax,$getDefCurDet['curCode']);?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                    } //Ends order lelvel tax discount charges

                                                    $totalTax =(($chargePrice+$fixedCharges+$totalCalDiscount)*$taxCharges/100);//total tax feeType=1
                                                    $netTotalAmt= ($chargePrice+$fixedCharges+$totalCalDiscount+$totalTax);

                                                    ?>
                                                <div <?php if ($ordCountRow == 0)
                                                    { 
                                                    echo 'style="border-top: 0px;"';  
                                                    }  ?>class="price justify-content-between grdTtl-Row">
                                                    <div class="p-2 delIcn text-center"></div>
                                                    <div class="p-2 txnmRow">
                                                        <p><?php echo showOtherLangText('Grand Total'); ?></p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p><?php echo getPriceWithCur($netTotalAmt,$getDefCurDet['curCode']) ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                    }
                                                    else
                                                    {
                                                        echo '<td style="width: 40%; align: right"></td>';
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
                            $totalCustomCharges = 0;
                            $sql = "SELECT tp.itemName, tp.unit, od.* FROM tbl_order_details_temp od 
                            INNER JOIN tbl_custom_items_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                            WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."' and od.customChargeType=1 ORDER BY tp.itemName ";
                            $chrgQry = mysqli_query($con, $sql);


                            $sql = "SELECT tp.*,
                            od.totalAmt AS ordPrice, 
                            od.qty AS ordQty,
                            od.requestedQty AS requestedQty, 
                            od.note, 
                            s.qty AS stockQty, 
                            s.tempQty,
                            IF(u.name!='', u.name,tp.unitC) countingUnit 
                            FROM tbl_order_details_temp od 
                            INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id AND tp.status=1
                            INNER JOIN tbl_stocks s ON(s.pId = tp.id) AND s.account_id=tp.account_Id
                            LEFT JOIN tbl_units u ON(u.id = tp.unitC) AND u.account_id = tp.account_id 
                            WHERE od.ordId = '".$_GET['orderId']."' AND tp.account_id = '".$_SESSION['accountId']."' and od.qty > 0  ORDER BY tp.itemName ";
                            $orderQry = mysqli_query($con, $sql);
                        
                        ?>
                        <div class="container nordPrice position-relative">
                            <!-- Item Table Head Start -->
                            <div class="d-flex align-items-center itmTable">
                                <div class="prdtImg tb-head">
                                    <p><?php echo showOtherLangText('Image'); ?></p>
                                </div>
                                <div class="prdtCnt-Fst d-flex align-items-center">
                                    <div class="Itm-Name tb-head">
                                        <p><?php echo showOtherLangText('Item'); ?></p>
                                    </div>
                                    <div class="Itm-brCode tb-head">
                                        <p><?php echo showOtherLangText('Bar Code'); ?></p>
                                    </div>
                                    <div class="prdtCr-Unit d-flex align-items-center">
                                        <div class="crncy-Type w-50 ">
                                            <?php echo '<div class="tb-head"><p>'.showOtherLangText('Price').'</p>
                                                </div>'; ?>
                                        </div>
                                        <div class="itm-Unit tb-head">
                                            <p><?php echo showOtherLangText('C Unit'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    if ($getColumnPermission['available_quantity'] == 1) 
                                    {
                                ?>
                                <div class="prdtStk-Qty tb-head">
                                    <p><?php echo showOtherLangText('S.Qty'); ?></p>
                                </div>
                                <div class="prdtStk-Qty tb-head">
                                    <p><?php echo showOtherLangText('A.Qty'); ?></p>
                                </div>
                                <?php
                    }
                    ?>
                                <div class="prdtCnt-Scnd d-flex align-items-center">
                                    <div class="itm-Quantity tb-head">
                                        <p><?php echo showOtherLangText('Qty'); ?></p>
                                    </div>
                                    <?php echo $getColumnPermission['item_price'] == 1 ? '<div class="ttlCr-Type w-50">
                                            <div class="ps-xl-5 tb-head">
                                                <p>'.showOtherLangText('Total').'</p>
                                            </div></div>' : ''; ?>
                                </div>
                                <div class="prdt-Hide">
                                    <div class="prdt-Note tb-head">
                                        <div class="mb-brCode" style="display: none;"></div>
                                        <p><?php echo showOtherLangText('Note'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <!-- Item Table Head End -->
                        </div>

                        <div id="boxscroll">
                            <div class="container cntTable">
                                <!-- Item Table Body Start -->
                                <?php 
                                    $fixedCharges = 0;
                                    $perCharges = 0;
                                    $x = 0;
                                    $y = 0;
                                    while($row = mysqli_fetch_array($chrgQry))//show here order level charges
                                    {
                                        $totalCustomCharges += $row['price'];
                                        $x++;
                                        $y++;
                                ?>
                                <div class="newReqTask">
                                    <div class="d-flex align-items-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="prdtImg tb-bdy">
                                            <a title="<?php echo showOtherLangText('Delete') ?>"
                                                href="javascript:void(0)"
                                                onClick="getDelNumb('<?php echo $row['id'] ?>', '<?php echo $row['ordId'] ?>');"
                                                style="color:#808080" class="glyphicon glyphicon-trash"> <i
                                                    class="fa-solid fa-trash-can"></i></a>
                                        </div>
                                        <div class="prdtCnt-Fst d-flex align-items-center">
                                            <div class="Itm-Name tb-bdy">
                                                <p><?php echo $row['itemName'];?></p>
                                            </div>
                                            <div class="Itm-brCode tb-bdy">
                                                <p class="ord-brCode"></p>
                                            </div>
                                            <div class="prdtCr-Unit d-flex">
                                                <div class="crncy-Type w-50">
                                                    <div class="tb-head">
                                                        <p><?php echo getPriceWithCur($row['price'],$getDefCurDet['curCode']); ?>
                                                        </p>
                                                    </div>

                                                    <input type="hidden" name="price[<?php echo $row['id'];?>]"
                                                        id="<?php echo $x;?>"
                                                        value="<?php echo getPriceWithCur($row['stockPrice'],$getDefCurDet['curCode']);?>" />

                                                </div>
                                                <div class="itm-Unit tb-bdy">
                                                    <p><?php echo $row['unit'];?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                    if ($getColumnPermission['available_quantity'] == 1) 
                    {
                        ?>
                                        <div class="prdtStk-Qty tb-bdy">
                                            <p class="ord-StockQty"> <span class="tabOn-Stk">On stock</span></p>
                                        </div>
                                        <div class="prdtStk-Qty tb-bdy">
                                            <p class="ord-StockQty">1 <span class="tabOn-Stk">On stock</span></p>
                                        </div>
                                        <?php
                    }
                    ?>
                                        <div class="prdtCnt-Scnd d-flex align-items-center">

                                            <div class="itm-Quantity tb-bdy">
                                                1<span class="mbl-ReqStk">On stock</span>
                                            </div>
                                            <div class="ttlCr-Type w-50 ps-xl-5">
                                                <?php echo getPriceWithCur($row['price'],$getDefCurDet['curCode']); ?>

                                            </div>

                                        </div>
                                        <div class="prdt-Hide">
                                            <div class="prdt-Note tb-bdy">
                                                <div class="mb-brCode" style="display: none;"></div>
                                                <input type="text" class="note-itm form-control" autocomplete="off"
                                                    name="itemNotes[<?php echo $row['id'];?>]"
                                                    id="itemNotes<?php echo $x;?>" placeholder="Note"
                                                    onChange="getItemNotesVal('<?php echo $x;?>', '<?php echo $row['id'];?>');"
                                                    value="<?php echo $row['note'];?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>

                                </div>
                                <?php 
                } // end of extra charge loop / section
                ?><?php 
    $pidArr = [];
    $x = 0;
    while($row = mysqli_fetch_array($orderQry))
    {
        $pidArr[] = $row['id'];
        $x++;
        $y++;

        //Check stock qty with item qty
        $getTotalQtyWithOutSameOrdId = getConfirmTotalQtyReqWithoutSameOrdId($row['id'], $_SESSION['accountId'], $_GET['orderId']);

        $totalProQty = isset($getTotalQtyWithOutSameOrdId[$row['id']]) ? $getTotalQtyWithOutSameOrdId[$row['id']] : 0;

        $availableQty = $row['stockQty'] - $totalProQty;
        ?>
                                <input type="hidden" name="productIds[]" value="<?php echo $row['id'];?>" />
                                <input type="hidden" name="totalPriceShowTop[]" id="totalPriceShowTop<?php echo $x;?>"
                                    value="<?php echo ($row['ordPrice']);?>" />
                                <input type="hidden" name="factor[<?php echo $row['id'];?>]" id="factor<?php echo $x;?>"
                                    value="<?php echo $row['factor'];?>" />
                                <div class="newReqTask">
                                    <div class="d-flex align-items-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="prdtImg tb-bdy">
                                            <?php $img = '';
                        if( $row['imgName'] != '' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$row['imgName'] ) )
                        {   
                            echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$row['imgName'].'" class="ordItm-Img" >';
                        }
                        ?>
                                        </div>
                                        <div class="prdtCnt-Fst d-flex align-items-center">
                                            <div class="Itm-Name tb-bdy">
                                                <p><?php echo $row['itemName'];?></p>
                                            </div>
                                            <div class="Itm-brCode tb-bdy">
                                                <p class="ord-brCode"><?php echo $row['barCode'];?></p>
                                            </div>
                                            <div class="prdtCr-Unit d-flex">
                                                <div class="crncy-Type w-50">
                                                    <?php echo $getColumnPermission['item_price'] == 1 ? ' <div class=" tb-bdy">
                                                            <p>'.getPriceWithCur($row['stockPrice'],$getDefCurDet['curCode']).'</p>
                                                        </div>' : ''; ?>

                                                    <input type="hidden" name="price[<?php echo $row['id'];?>]"
                                                        id="<?php echo $x;?>"
                                                        value="<?php echo getPriceWithCur($row['stockPrice'],$getDefCurDet['curCode']);?>" />

                                                </div>
                                                <div class="itm-Unit tb-bdy">
                                                    <p><?php echo $row['countingUnit'];?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                    if ($getColumnPermission['available_quantity'] == 1) 
                    {
                        ?>
                                        <div class="prdtStk-Qty tb-bdy">
                                            <p class="ord-StockQty"><?php echo $row['stockQty'] ;?> <span
                                                    class="tabOn-Stk">On stock</span></p>
                                        </div>
                                        <div class="prdtStk-Qty tb-bdy">
                                            <p class="ord-StockQty"><?php echo $availableQty ;?> <span
                                                    class="tabOn-Stk">On stock</span></p>
                                        </div>
                                        <?php
                    }
                    ?>
                                        <div class="prdtCnt-Scnd d-flex align-items-center">
                                            <div class="itm-Quantity tb-bdy">
                                                <input type="text" class="form-control qty-itm" id="qty<?php echo $x;?>"
                                                    name="qty[<?php echo $row['id'];?>]" autocomplete="off"
                                                    onChange="showTotal(this.value, '<?php echo $x;?>', '<?php echo $availableQty > 0 ? $availableQty : 0 ;?>', '<?php echo $row['id'];?>')"
                                                    value="<?php echo $row['ordQty'];?>">
                                            </div>
                                            <div class="ttlCr-Type w-50 ps-xl-5">
                                                <?php echo $getColumnPermission['item_price'] == 1 ? '<span id="totalPrice'.$x.'">'.getPriceWithCur($row['ordPrice'],$getDefCurDet['curCode']).'</span>' : ''; ?>

                                            </div>

                                        </div>
                                        <div class="prdt-Hide">
                                            <div class="prdt-Note tb-bdy">
                                                <div class="mb-brCode" style="display: none;"></div>
                                                <input type="text" class="form-control note-itm" autocomplete="off"
                                                    placeholder="Note" name="notes[<?php echo $row['id'];?>]"
                                                    id="notes<?php echo $row['id'];?>"
                                                    onchange="getnotesVal('<?php echo $row['id'] ?>');"
                                                    placeholder="Note" value="<?php echo $row['note'];?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>

                                </div>
                                <?php 
    }
    ?>


                                <!-- Item Table Body End -->
                            </div>
                        </div>

                        <div>

                            <div class="container pt-5 topOrder ">
                                <div class="row g-4 align-items-end">
                                    <div class="col-md-5">
                                        <p class="fs-14 pb-3"><?php echo showOtherLangText('Add New Items'); ?></p>
                                        <div class="input-group srchBx"> 
                                            <input type="search" class="form-control" placeholder="Search Item"
                                                name="search2" class="form-control" id="search2"
                                                onKeyUp="myFunction('search2', 'newOrdTask1', 2)"
                                                placeholder="<?php echo showOtherLangText('Search Item'); ?>"
                                                aria-label="Search"
                                            >
                                            <div class="input-group-append">
                                                <button class="btn" type="button" style="background-color: rgb(122, 137, 255);">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-7"> 
                                        <form
                                            action="editRequisition.php?orderId=<?php echo $_GET['orderId'];?>&deptId=<?php echo $ordRow['deptId'];?>"
                                            method="post" autocomplete="off" id="frm_add_new_items" name="frm_add_new_items"
                                        >
                                            <div class="btnBg text-center text-md-end">
                                                <!-- <a href="javascript:void(0);" class="sub-btn std-btn add_new_items_in_req"><?php// echo showOtherLangText('Add New Items In Requisition'); ?></a> -->
                                                <a href="javascript:void(0);" class="btn btn-primary add_new_items_in_req"><?php echo showOtherLangText('Add New Items In Requisition'); ?></a>
                                            </div>
                                        </form>
                                    </div>
                                </div> 
                            </div><!--.//container-->

            <?php 
        if($_GET['orderId'] != '')
        {
            if( !empty( $pidArr ) )
            {
                $cond .= " WHERE p.id NOT IN(".implode(',', $pidArr).") ";
            }


        $sqlSet = " SELECT s.qty stockQty, p.*, IF(u.name!='',u.name,p.unitC) countingUnit FROM tbl_products p
        LEFT JOIN tbl_units u ON(u.id=p.unitC) AND u.account_id=p.account_Id 
        INNER JOIN tbl_productdepartments pd ON(p.id = pd.productId) AND p.account_id = pd.account_id AND pd.deptId = '".$_SESSION['ordDeptId']."' AND p.status=1
        INNER JOIN tbl_stocks s ON(s.pId = p.id)  AND s.account_id=p.account_id
        ".$cond. " AND  p.account_id = '".$_SESSION['accountId']."' GROUP BY(p.id) ORDER BY p.itemName ";
        $proresultSet = mysqli_query($con, $sqlSet); ?>
            <div class="container nordPrice position-relative">
                <!-- Item Table Head Start -->
                <div class="d-flex align-items-center itmTable">
                    <div class="prdtImg tb-head">
                        <p><?php echo showOtherLangText('Image'); ?></p>
                    </div>
                    <div class="prdtCnt-Fst d-flex align-items-center">
                        <div class="Itm-Name tb-head">
                            <p><?php echo showOtherLangText('Item'); ?></p>
                        </div>
                        <div class="Itm-brCode tb-head">
                            <p><?php echo showOtherLangText('Bar Code'); ?></p>
                        </div>
                        <div class="prdtCr-Unit d-flex align-items-center">
                            <div class="crncy-Type w-50 ">
                                <?php echo '<div class="tb-head"><p>'.showOtherLangText('Price').'</p>
                                                </div>'; ?>
                            </div>
                            <div class="itm-Unit tb-head">
                                <p><?php echo showOtherLangText('C Unit'); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                    if ($getColumnPermission['available_quantity'] == 1) 
                    {
                        ?>
                    <div class="prdtStk-Qty tb-head">
                        <p><?php echo showOtherLangText('S.Qty'); ?></p>
                    </div>
                    <div class="prdtStk-Qty tb-head">
                        <p><?php echo showOtherLangText('A.Qty'); ?></p>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="prdtCnt-Scnd d-flex align-items-center">
                        <div class="itm-Quantity tb-head">
                            <p><?php echo showOtherLangText('Qty'); ?></p>
                        </div>
                        <?php echo $getColumnPermission['item_price'] == 1 ? '<div class="ttlCr-Type w-50">
                                            <div class="ps-xl-5 tb-head">
                                                <p>'.showOtherLangText('Total').'</p>
                                            </div></div>' : ''; ?>
                    </div>
                    <div class="prdt-Hide">
                        <div class="prdt-Note tb-head">
                            <div class="mb-brCode" style="display: none;"></div>
                            <p><?php echo showOtherLangText('Note'); ?></p>
                        </div>
                    </div>
                </div>
                <!-- Item Table Head End -->
            </div>

            <div id="boxscroll">
                <div class="container cntTable  ">
                    <!-- Item Table Body Start -->



                    <?php 
            //get confirmed requsitions total qty of each productd
            $productsConfirmedQtyArr = getConfirmTotalQtyReq($_SESSION['accountId']);
            //end get confirmed requsitions total qty of each productd

            $x= $x+1;
            $i = 0;
            while($row = mysqli_fetch_array($proresultSet))
            {

                $x++;
                $y++;
                $i=1;

                //Check stock qty with item qty
                $totalProQty = isset($productsConfirmedQtyArr[$row['id']]) ? $productsConfirmedQtyArr[$row['id']] : 0;

                $availableQty = $row['stockQty'] - $totalProQty;
                ?>
                    <div class="newOrdTask">
                        <div class="d-flex align-items-center border-bottom itmBody newOrd-CntPrt">
                            <div class="prdtImg tb-bdy">
                                <?php $img = '';
                        if( $row['imgName'] != '' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$row['imgName'] ) )
                        {   
                            echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$row['imgName'].'" class="ordItm-Img" >';
                        }
                        ?>
                            </div>
                            <div class="prdtCnt-Fst d-flex align-items-center">
                                <div class="Itm-Name tb-bdy">
                                    <p><?php echo $row['itemName'];?></p>
                                </div>
                                <div class="Itm-brCode tb-bdy">
                                    <p class="ord-brCode"><?php echo $row['barCode'];?></p>
                                </div>
                                <div class="prdtCr-Unit d-flex">
                                    <div class="crncy-Type w-50">
                                        <?php echo $getColumnPermission['item_price'] == 1 ? ' <div class=" tb-bdy">
                                                            <p>'.getPriceWithCur($row['stockPrice'],$getDefCurDet['curCode']).'</p>
                                                        </div>' : ''; ?>

                                        <input type="hidden" name="price[<?php echo $row['id'];?>]"
                                            id="<?php echo $x;?>"
                                            value="<?php echo getPriceWithCur($row['stockPrice'],$getDefCurDet['curCode']);?>" />

                                    </div>
                                    <div class="itm-Unit tb-bdy">
                                        <p><?php echo $row['countingUnit'];?></p>
                                    </div>
                                </div>
                            </div>
                            <?php
                    if ($getColumnPermission['available_quantity'] == 1) 
                    {
                        ?>
                            <div class="prdtStk-Qty tb-bdy">
                                <p class="ord-StockQty"><?php echo $row['stockQty'] ;?> <span class="tabOn-Stk">On
                                        stock</span></p>
                            </div>
                            <div class="prdtStk-Qty tb-bdy">
                                <p class="ord-StockQty"><?php echo $availableQty ;?> <span class="tabOn-Stk">On
                                        stock</span></p>
                            </div>
                            <?php
                    }
                    ?>
                            <div class="prdtCnt-Scnd d-flex align-items-center">
                                <div class="itm-Quantity tb-bdy">
                                    <input type="text" class="form-control qty-itm editQty-Rec"
                                        name="qty[<?php echo $row['id'];?>]" id="qty<?php echo $x;?>" autocomplete="off"
                                        onChange="showTotal(this.value, '<?php echo $x;?>', '<?php echo $availableQty > 0 ? $availableQty : 0 ;?>', '<?php echo $row['id'];?>')"
                                        value="">
                                </div>
                                <div class="ttlCr-Type w-50 ps-xl-5">
                                    <?php echo $getColumnPermission['item_price'] == 1 ? '<div class=" tb-bdy"><p id="totalPrice'.$x.'">0</p></div>' : ''; ?>

                                </div>

                            </div>
                            <div class="prdt-Hide">
                                <div class="prdt-Note tb-bdy">
                                    <div class="mb-brCode" style="display: none;"></div>
                                    <input type="text" class="form-control note-itm" autocomplete="off"
                                        name="notes[<?php echo $row['id'];?>]" id="notes<?php echo $row['id'];?>"
                                        onchange="getnotesVal('<?php echo $row['id'] ?>');" placeholder="Note"
                                        value="<?php echo $row['note'];?>">
                                </div>
                            </div>
                        </div>
                        <div class="mbLnk-Order">
                            <a href="javascript:void(0)" class="orderLink">
                                <i class="fa-solid fa-angle-down"></i>
                            </a>
                        </div>

                    </div>

                    <?php } ?>


                </div>
            </div>

            <div class="container pb-4 topOrder">
                <div class="btnBg mt-3">
                    <a href="javascript:void(0);"
                        class="add_new_items_in_req sub-btn std-btn"><?php echo showOtherLangText('Add New Items In Requisition'); ?></a>
                </div>
            </div>
        </div>
        <?php } ?>
        </section>
        </form>

    </div>
    </div>
    </div>

    <form action="" name="addNewFee" class="addUser-Form row container glbFrm-Cont" id="addNewFee" method="post"
        autocomplete="off">
        <div class="modal" tabindex="-1" id="new-fees-item" aria-labelledby="add-CategoryLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h1 class="modal-title h1"><?php echo showOtherLangText('Add Fee'); ?></h1>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="currencyPopupForm" value="<?php echo $_SESSION['currencyId'] ?>">
                        <input type="text" class="form-control" name="feeName" id="feeName" value="" autocomplete="off"
                            placeholder="<?php echo showOtherLangText('Fee Name'); ?>"
                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                            onChange="this.setCustomValidity('')" required />
                        <select class="form-control" name="feeType" id="typeOfFee"
                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')"
                            onChange="this.setCustomValidity('')" required>
                            <option value="2"><?php echo showOtherLangText('Fixed Fee'); ?></option>
                            <option value="3"><?php echo showOtherLangText('Percentage Fee'); ?>
                            </option>
                        </select>
                        <input type="text" class="form-control" id="amt" name="amt" value="" autocomplete="off"
                            placeholder="<?php echo showOtherLangText('Fee Amount').' '.$getDefCurDet['curCode']; ?>"
                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                            onChange="this.setCustomValidity('')" required />

                    </div>
                    <div class="feeSave">
                        <input type="checkbox" class="optionCheck" name="feeType" id="feeType" value="1">
                        <span class="subTittle1" style="vertical-align:text-top;">
                            <?php echo showOtherLangText('Tax fee'); ?></span><br>

                    </div>
                    <div>
                        <div class="feeSave">
                            <input type="checkbox" class="optionCheck" id="visibility" name="visibility" value="1">
                            <span class="subTittle1" style="vertical-align:text-top;"> <?php echo showOtherLangText('save to fixed service item
list'); ?></span><br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btnBg">
                            <button type="submit" id="feesave_add" name="feesave_add"
                                class="btn sub-btn std-btn"><?php echo showOtherLangText('Add'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form action="" name="addServiceFeeFrm" class="addUser-Form row container glbFrm-Cont" id="addServiceFeeFrm"
        method="post" autocomplete="off">
        <div class="modal" tabindex="-1" id="new-service-item" aria-labelledby="add-CategoryLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h1 class="modal-title h1"><?php echo showOtherLangText('Service Name'); ?></h1>
                    </div>
                    <div class="modal-body">
                        <input type="text" required class="form-control" id="itemName" name="itemName"
                            placeholder="<?php echo showOtherLangText('Service Name');?>" autocomplete="off"
                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                            onChange="this.setCustomValidity('')" required>
                        <input type="number" required class="form-control" id="feeAmt" name="itemFeeAmt"
                            placeholder="<?php echo showOtherLangText('Amount').' '.$getDefCurDet['curCode']; ?>"
                            autocomplete="off"
                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                            onChange="this.setCustomValidity('')" required>
                        <input type="text" required class="form-control" id="unit" name="unit"
                            placeholder="<?php echo showOtherLangText('Unit'); ?>" autocomplete="off"
                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                            onChange="this.setCustomValidity('')" required>
                    </div>
                    <div>
                        <div class="feeSave">
                            <input type="checkbox" class="optionCheck" id="visibility" name="visibility" value="1">
                            <span class="subTittle1" style="vertical-align:text-top;"> <?php echo showOtherLangText('save to fixed service item
list'); ?></span><br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btnBg">
                            <button type="submit" id="addFee" name="addFee"
                                class="btn sub-btn std-btn"><?php echo showOtherLangText('Add'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="dialog" style="display: none;">
            <?php echo showOtherLangText('Are you sure to delete this charges?') ?>
        </div>
        <?php require_once('footer.php');?>
        <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>




        <div class="modal" tabindex="-1" id="delete-popup" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h1 class="modal-title h1">
                            <?php echo showOtherLangText('Are you sure to delete this record?') ?> </h1>
                    </div>

                    <div class="modal-footer">
                        <div class="btnBg">
                            <button type="button" data-bs-dismiss="modal"
                                class="btn sub-btn std-btn"><?php echo showOtherLangText('No'); ?></button>
                        </div>
                        <div class="btnBg">
                            <button type="button" onclick=""
                                class="deletelink btn sub-btn std-btn"><?php echo showOtherLangText('Yes'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>
<script type="text/javascript">
function myFunction(searchId, tableId, searchBoxNo) {
    var input = document.getElementById(searchId);
    var filter = input.value.toLowerCase();
    if (searchBoxNo == 1) {
        //console.log('searchId',searchBoxNo);
        var nodes = document.querySelectorAll('.newReqTask');
    } else {
        // console.log('searchId',searchBoxNo);
        var nodes = document.querySelectorAll('.newOrdTask');
    }
    console.log('searchid', searchId, tableId, searchBoxNo, nodes.length);
    for (i = 0; i < nodes.length; i++) {
        if (nodes[i].innerText.toLowerCase().includes(filter)) {
            nodes[i].style.setProperty("display", "block", "important");
        } else {
            nodes[i].style.setProperty("display", "none", "important");
        }
    }
}

function showTotal(qty, indexVal, availableQty, pId) {
    var availableQty = parseFloat(availableQty);
    // if (qty > availableQty) {
    //     showMsg1();
    //     $('#qty' + indexVal).val();
    //     return false;
    // }

    //call ajax to save qty changes in database
    if (isNaN(qty) || qty == '') {
        qty = 0;

    }

    $.ajax({
            method: "POST",
            url: "editRequisitionAjax.php",
            dataType: 'json',
            data: {
                orderId: '<?php echo $_GET['orderId']; ?>',
                deptId: '<?php echo $_SESSION['ordDeptId'];?>',
                pId: pId,
                qty: qty
            }
        })
        .done(function(responseObj) {

            $('#totalOrdArea').html(responseObj.resHtml);

            $('#totalPrice' + indexVal).html(responseObj.productPrice);
        });


} //end 

$("#search1").on("search", function(evt) {
    if ($(this).val().length == 0) {
        resetData(1);
    }
});

$("#search2").on("search", function(evt) {
    if ($(this).val().length == 0) {
        resetData(2);
    }
});

function resetData(formID) {
    if (formID == 1) {
        $('#search1').val('');
        myFunction('search1', 'tableId1', 1);
    } else {
        $('#search2').val('');
        myFunction('search2', 'tableId1', 2);
    }
}
$('.form-submit-btn').click(function() {
    $('#frm').submit();
});

function getDelNumb(delId, orderId) {
    var newOnClick = "window.location.href='editRequisition.php?delId=" + delId + "&orderId=" + orderId + "'";



    $('.deletelink').attr('onclick', newOnClick);
    $('#delete-popup').modal('show');

}

// function getDelNumb(delId, orderId) {

//         $("#dialog").dialog({
//             autoOpen: false,
//             modal: true,
//             //title     : "Title",
//             buttons: {
//                 '<?php echo showOtherLangText('Yes') ?>': function() {
//                     //Do whatever you want to do when Yes clicked
//                     $(this).dialog('close');
//                     window.location.href = 'editRequisition.php?delId=' + delId + '&orderId=' + orderId;
//                 },

//                 '<?php echo showOtherLangText('No') ?>': function() {
//                     //Do whatever you want to do when No clicked
//                     $(this).dialog('close');
//                 }
//             }
//         });

//         $("#dialog").dialog("open");
//         $('.custom-header-text').remove();
//         $('.ui-dialog-content').prepend(
//             '<div class="custom-header-text"><span><?php echo showOtherLangText('Queue1.com Says') ?></span></div>');
//     }

//for each charges notes
function getItemNotesVal(indexVal, itemNotesId) {

    var itemNotes = $('#itemNotes' + indexVal).val();

    $.ajax({
            method: "POST",
            url: "editRequisitionAjax.php",

            data: {
                itemNotes: itemNotes,
                itemNotesId: itemNotesId,
                orderId: '<?php echo $_GET['orderId'] ?>'
            }
        })
        .done(function(responseObj) {


        });


} //end 

//for each products note 
function getnotesVal(pId) {

    var notes = $('#notes' + pId).val();

    $.ajax({
            method: "POST",
            url: "editRequisitionAjax.php",

            data: {
                notes: notes,
                pId: pId,
                orderId: '<?php echo $_GET['orderId'] ?>'
            }
        })
        .done(function(responseObj) {


        });


} //end 

$('.add_new_items_in_req').click(function() {

    $('#frm_add_new_items').submit();

});
</script>
<style>
.subitem {
    display: none;
}
</style>
<script>
$('.item').on('mouseover', 'li', function() {
    $(this).children(".subitem").show().end().siblings().find('.subitem').hide();
}).on('mouseleave', function() {
    $('.subitem', this).hide();
});
</script>