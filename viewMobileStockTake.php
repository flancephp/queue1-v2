<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

if (!isset($_SESSION['adminidusername'])) {

    echo "<script>window.location='login.php'</script>";
    exit;
}

if (isset($_GET['delId']) && $_GET['delId']) {

    $sql = "DELETE FROM tbl_mobile_time_track WHERE id = '" . $_GET['delId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
    $result = mysqli_query($con, $sql);

    $sql = "DELETE FROM tbl_mobile_items_temp WHERE processId = '" . $_GET['delId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
    mysqli_query($con, $sql);

    echo "<script>window.location='viewMobileStockTake.php?delete=1&stockTakeId=" . $_GET['stockTakeId'] . "'</script>";
    exit;
}


//////////////////Pagination goes here/////////////////////////////////////////
// $sql=" SELECT t.*, u.name FROM  tbl_mobile_time_track t INNER JOIN tbl_user u ON(u.id = t.userId) AND u.account_id = t.account_id WHERE t.stockTakeId = '".$_GET['stockTakeId']."'  AND t.`stockTakeType` = 1 AND t.status=1 AND t.account_id = '".$_SESSION['accountId']."'  ";

$sql = " SELECT u.name, t.* 
FROM  tbl_mobile_time_track t 
    INNER JOIN tbl_mobile_items_temp it
            ON(t.id = it.processId) AND t.account_id = it.account_id AND it.status=1  
    INNER JOIN tbl_user u 
            ON(t.userId = u.id) AND t.account_id = u.account_id

WHERE t.stockTakeId = '" . $_GET['stockTakeId'] . "'  AND t.stockTakeType = 1 AND t.status=1 AND t.account_id = '" . $_SESSION['accountId'] . "' GROUP BY it.processId ";
$result = mysqli_query($con, $sql);


?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Queue1.com</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">
    <style>
        @media(min-width:992px) {

            .itmBody {
                padding-top: 0;
                padding-bottom: 0;
            }
        }

        @media(min-width:992px) and (max-width:1599px) {
            .suplrTbl-body {
                padding: 0;
            }

            .suppTbl-Head {
                padding: 0 0px 10px;
            }

            .supTbl-IcnCol {
                width: 20%;
            }

            .supTbl-EmCol {
                width: 35%;
            }
        }

        @media(max-width:767px) {

            .supTbl-NmCol,
            .supTbl-IcnCol {
                width: 100%;
            }

            .supOpt-Clm {
                justify-content: center;
            }
        }
    </style>
</head>

<body class="mb-Bgbdy">

    <div class="container-fluid newOrder">
        <div class="row">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                <?php require_once('nav.php'); ?>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('View Mobile Stock Take'); ?></h1>
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
                                    <!-- <h1 class="h1"><?php echo showOtherLangText('Manage Suppliers') ?></h1> -->
                                    <h1 class="h1"><?php echo showOtherLangText('View Mobile Stock Take'); ?></h1>
                                </div>
                            </div>
                            <?php require_once('header.php'); ?>
                        </div>
                    </div>
                </section>

                <section id="landScape">
                    <div class="container">
                        <h1 class="h1 text-center">For better experience, Please use portrait view.</h1>
                    </div>
                </section>

                <section class="ordDetail userDetail">
                    <div class="container">
                        <?php if (isset($_GET['added']) || isset($_GET['update']) || isset($_GET['delete'])) { ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p>
                                    <?php
                                    echo isset($_GET['update']) ? ' ' . showOtherLangText('Supplier Edited Successfully') . ' ' : '';

                                    echo isset($_GET['added']) ? ' ' . showOtherLangText('Supplier Added Successfully') . ' ' : '';

                                    echo isset($_GET['delete']) ? ' ' . showOtherLangText('Mobile stock take Deleted Successfully') . ' ' : '';
                                    ?>
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        <?php } ?>
                        <?php if (isset($_GET['err'])) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo isset($_GET['err']) ? ' ' . showOtherLangText('Supplier cannot be Deleted as it has been assigned in order') . ' ' : ''; ?></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php } ?>
                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <!-- sub-btn std-btn -->
                                    <a href="stockView.php?filterByStorage=<?php echo $_GET['stockTakeId']; ?>" class="btn btn-primary mb-usrBkbtn"><span class="mb-UsrBtn"><i
                                                class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                </div>
                            </div>
                            <div class="usrAd-Btn">
                                <div class="btnBg">

                                </div>
                            </div>
                        </div>

                        <div class="suplrTable">
                            <div class="container">
                                <!-- Table Head Start -->
                                <div class="suppTbl-Head align-items-center itmTable">
                                    <div class="supTbl-NmCol d-flex align-items-center">
                                        <div class="tb-head supNum-Clm">
                                            <div class="d-flex align-items-center">
                                                <p><?php echo showOtherLangText('#') ?></p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" onclick="sortRows(1);" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" onclick="sortRows(0);" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="tb-head supName-Clm">
                                            <p><?php echo showOtherLangText('User'); ?></p>
                                        </div>
                                        <div class="tb-head supAdd-Clm">
                                            <p><?php echo showOtherLangText('Stock Take Time'); ?></p>
                                        </div>
                                    </div>
                                    <div class="supTbl-EmCol d-flex align-items-center">


                                    </div>
                                    <div class="supTbl-IcnCol">
                                        <div class="tb-head supOpt-Clm">
                                            <p><?php echo showOtherLangText('Options') ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div id="myRecords">
                                    <?php

                                    $x = 0;
                                    while ($row = mysqli_fetch_array($result)) {

                                        $color = ($x % 2 == 0) ? 'white' : '#FFFFCC';
                                        $x++;

                                        $startDate = date('h:i:s A', strtotime($row['start_time']));
                                        $endDate = date('h:i:s A', strtotime($row['end_time']));

                                        if ($getLangType == '1') {
                                            if (strpos($startDate, AM)) {
                                                //$startDate = str_replace('AM', 'בבוקר', $startDate);
                                                $startDate = str_replace('AM', '' . showOtherLangText('AM') . '', $startDate);
                                            } else if (strpos($startDate, PM)) {
                                                //$startDate = str_replace('PM', 'אחר הצהריים', $startDate);
                                                $startDate = str_replace('PM', '' . showOtherLangText('PM') . '', $startDate);
                                            }

                                            if (strpos($endDate, AM)) {
                                                //$endDate = str_replace('AM', 'בבוקר', $endDate);
                                                $endDate = str_replace('AM', '' . showOtherLangText('AM') . '', $startDate);
                                            } else if (strpos($endDate, PM)) {
                                                //$endDate = str_replace('PM', 'אחר הצהריים', $endDate);
                                                $endDate = str_replace('PM', '' . showOtherLangText('PM') . '', $startDate);
                                            }
                                        }

                                    ?>
                                        <div class="suplrTask">
                                            <div class="suplrTbl-body itmBody">
                                                <div class="supTbl-NmCol d-flex align-items-center">
                                                    <div class="tb-bdy supNum-Clm">
                                                        <p class="suplNumber"><span class="mb-UsrSpan">No. </span><?php echo $x; ?></p>
                                                    </div>
                                                    <div class="tb-bdy supName-Clm">
                                                        <p class="suplName"><?php echo $row['name']; ?></p>
                                                    </div>
                                                    <div class="tb-bdy supAdd-Clm">
                                                        <p class="suplAdrs"><?php echo $startDate . ' - ' . $endDate ?></p>
                                                    </div>
                                                </div>
                                                <div class="supTbl-EmCol align-items-center">
                                                    <div class="tb-head supEml-Clm">
                                                        <p><?php echo $row['email']; ?></p>
                                                    </div>
                                                    <div class="tb-head supPhn-Clm">
                                                        <p><?php echo $row['phone']; ?></p>
                                                    </div>
                                                </div>
                                                <div class="supTbl-IcnCol">
                                                    <div class="tb-bdy supOpt-Clm d-flex align-items-center">
                                                        <a href="stockTake.php?processId=<?php echo $row['id']; ?>&stockTakeId=<?php echo $_GET['stockTakeId']; ?>" class="userLink sub_CatLnk">
                                                            <img src="Assets/icons/setting.svg" alt="Sub-Category"
                                                                class="usrLnk-Img">
                                                            <p class="subCat-Lnk"><?php echo showOtherLangText('View Approve'); ?><span class="dsk-HdCtgry">.</span></p>
                                                        </a>
                                                        <a href="javascript:void(0)" class="userLink"
                                                            onClick="getDelNumb('<?php echo $row['id']; ?>');">
                                                            <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="align-items-center mbTask-Splr">
                                                <a href="javascript:void(0)" class="suplrLink"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </div>
                                        </div>
                                    <?php

                                    }

                                    ?>
                                    <!-- Table Body End -->
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

            </div>
        </div>
    </div>
    <div id="dialog" style="display: none;">
        <?php echo showOtherLangText('Are you sure to delete this record?') ?>
    </div>
    <?php require_once('footer.php'); ?>
    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <div class="modal" tabindex="-1" id="delete-popup" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php echo showOtherLangText('Are you sure to delete this record?') ?> </h1>
                </div>

                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="button" data-bs-dismiss="modal" class="btn sub-btn std-btn"><?php echo showOtherLangText('No'); ?></button>
                    </div>
                    <div class="btnBg">
                        <button type="button" onclick="" class="deletelink btn sub-btn std-btn"><?php echo showOtherLangText('Yes'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    function getDelNumb(delId) {
        var newOnClick = "window.location.href='viewMobileStockTake.php?delId=" + delId + "&stockTakeId=<?php echo $_GET['stockTakeId']; ?>'";

        $('.deletelink').attr('onclick', newOnClick);
        $('#delete-popup').modal('show');

    }

    jQuery.fn.orderBy = function(keySelector) {
        return this.sort(function(a, b) {
            a = keySelector.apply(a);
            b = keySelector.apply(b);
            console.log('a', a, 'b', b);
            if (a > b) return 1;
            if (a < b) return -1;
            return 0;
        });
    };

    // Function to sort and reorder the .userTask elements
    function sortRows(sort) {
        var uu = $(".suplrTask").orderBy(function() {
            var number = +$(this).find(".suplNumber").text().replace('No. ', '');
            return sort === 1 ? number : -number;
        }).appendTo("#myRecords");


    }
    <?php if (isset($_GET['delete'])) { ?>
        let url = location.href;
        let qryparameter = 'delete';
        let newurl = removeQueryParameter(url, qryparameter);
        history.pushState(null, "", newurl);
    <?php } ?>
</script>