<?php
include('../inc/dbConfig.php'); //connection details
$pgnm = 'Home';

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


if (!isset($_SESSION['id']) ||  $_SESSION['id'] < 1) {
    echo "<script>window.location.href='" . $siteUrl . "';</script>";
    exit;
}
if ($_SESSION['isAdmin'] != 1) {
    $url = $siteUrl . 'login.php';
    echo "<script>window.location.href='" . $url . "';</script>";
    exit;
}

$mobUserPermission = mobUserPermission($_SESSION['designation_id'], $_SESSION['accountId']);

// highlights the total no. of receiving order
$sql = " SELECT u.* FROM `tbl_order_assigned_users` u      
                INNER JOIN tbl_orders o ON(o.id = u.orderId) AND o.account_id = u.account_id
                LEFT JOIN tbl_mobile_time_track t ON(t.stockTakeId = u.orderId) AND t.account_id = u.account_id AND stockTakeType = 3 
                WHERE u.orderType = 1 AND o.status=1 AND u.userId = '" . $_SESSION['id'] . "'  AND u.account_id = '" . $_SESSION['accountId'] . "' AND IFNULL(t.status, 0) = 0  GROUP BY u.orderId ";

$result = mysqli_query($con, $sql);
$totalReceivingCount = mysqli_num_rows($result);

// highlights the total no. of issue out order
$sql = " SELECT u.* FROM `tbl_order_assigned_users` u
                INNER JOIN tbl_orders o ON(o.id = u.orderId) AND o.account_id = u.account_id
                left join tbl_mobile_time_track t ON(t.stockTakeId = u.orderId) AND t.account_id = u.account_id AND stockTakeType=4
                where u.orderType = 2  AND u.account_id = '" . $_SESSION['accountId'] . "' AND o.status=3 AND u.userId = '" . $_SESSION['id'] . "' AND IFNULL(t.status, 0) = 0  GROUP BY u.orderId ";

$assignQry = mysqli_query($con, $sql);
$toalIssueOutOrders = mysqli_num_rows($assignQry);
?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <meta property="og:title" content="" />
    <meta property="og:image" content="">
    <title>Queue1 Mobile</title>
    <?php include('layout/mCss.php'); ?>
</head>

<body>
    <section class="headSec">
        <div class="container">
            <?php include('layout/mHeader.php'); ?>
        </div>
    </section>
    <section class="homeList">
        <div class="container homeCont">
            <div class="row g-4 g-sm-5 row-cols-md-3 row-cols-2">
            <?php
            if ($mobUserPermission['storage_stocktaking'] == 1) { ?>
            <div class="d-flex"> 
                <a href="<?php echo $mobileSiteUrl; ?>storageStocktaking1.php" class="mblBx-Lnk w-100 m-0 text-center">
                    <img src="<?php echo $mobileSiteUrl; ?>Assets/icons/Stoctaking.svg" alt="Storage" class="fieldIcns">
                    <p class="mblFnt1">
                        <?php echo showOtherLangText('Storage Stocktaking') ?>
                    </p>
                </a>
            </div>
                <?php
            }

            if ($_SESSION['accountId'] != 4) {
                if ($mobUserPermission['outlet_stocktaking'] == 1) { ?>
                <div class="d-flex"> 
                    <a href="<?php echo $mobileSiteUrl; ?>outletStocktaking1.php" class="mblBx-Lnk w-100 m-0 text-center">
                        <img src="<?php echo $mobileSiteUrl; ?>Assets/icons/outlet_Stoctaking.svg" alt="Outlet" class="fieldIcns">
                        <p class="mblFnt1">
                            <?php echo showOtherLangText('Outlet Stocktaking') ?>
                        </p>
                    </a>
                </div>
                <?php
                }
            }

            if ($mobUserPermission['receiving_order'] == 1) { ?>
            <div class="d-flex"> 
                <a href="<?php echo $mobileSiteUrl; ?>receiveOrder1.php" class="mblBx-Lnk w-100 m-0 text-center">
                    <img src="<?php echo $mobileSiteUrl; ?>Assets/icons/Receving.svg" alt="Receving" class="fieldIcns">
                    <p class="mblFnt1"><?php echo showOtherLangText('Receiving') ?></p>
                    <?php
                    echo ($totalReceivingCount > 0) ? '<span class="position-absolute translate-middle badge rounded-pill bg-danger homeBadge">' . $totalReceivingCount . '</span>' : '';
                    ?>
                </a>
            </div>
            <?php
            }

            if ($mobUserPermission['issuing_out'] == 1) { ?>
            <div class="d-flex"> 
                <a href="<?php echo $mobileSiteUrl; ?>issueOut1.php" class="mblBx-Lnk w-100 m-0 text-center">
                    <img src="<?php echo $mobileSiteUrl; ?>Assets/icons/Issue_Out.svg" alt="Issue Out" class="fieldIcns">
                    <p class="mblFnt1"><?php echo showOtherLangText('Issuing Out') ?></p>
                    <?php
                    echo ($toalIssueOutOrders > 0) ? '<span class="position-absolute translate-middle badge rounded-pill bg-danger homeBadge">' . $toalIssueOutOrders . '</span>' : '';
                    ?>
                </a>
            </div>
            <?php
            }
            ?>
            <!--
            <a href="#" class="mblBx-Lnk w-100 m-0 text-center">
                <img src="<?php echo $mobileSiteUrl; ?>Assets/icons/Production.svg" alt="Production" class="fieldIcns">
                <p class="mblFnt1"><?php echo showOtherLangText('Production') ?></p>
            </a>-->
            <?php
            if ($_SESSION['accountId'] != 4) {
                if ($mobUserPermission['bar_control_sales'] == 1) { ?>
                <div class="d-flex"> 
                    <a href="<?php echo $mobileSiteUrl; ?>barControl1.php" class="mblBx-Lnk w-100 m-0 text-center">
                        <img src="<?php echo $mobileSiteUrl; ?>Assets/icons/Bar_Control.svg" alt="Bar Control" class="fieldIcns">
                        <p class="mblFnt1"><?php echo showOtherLangText('Bar Control Sales') ?></p>
                    </a>
                </div>
            <?php
                }
            }
            ?>
            </div>
        </div>
    </section>
    <?php include('layout/mJs.php'); ?>
</body>

</html>