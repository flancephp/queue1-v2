<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


if (!isset($_SESSION['adminidusername'])) {
    echo '<script>window.location="login.php"</script>';
}

$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'outlet' AND designation_Section_permission_id = '8' AND designation_id = '" . $_SESSION['designation_id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if (!$permissionRow) {
    echo "<script>window.location='index.php'</script>";
}

if (isset($_GET['delId']) && $_GET['delId']) {


    $sql = " SELECT * FROM tbl_revenue_center_departments WHERE deptId = '" . $_GET['delId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $result = mysqli_query($con, $sql);
    $resultRow = mysqli_fetch_array($result);

    $sqlQry = " SELECT * FROM tbl_outlet_items WHERE outLetId = '" . $resultRow['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $resultSet = mysqli_query($con, $sqlQry);
    $resultRowSet = mysqli_fetch_array($resultSet);
    if ($resultRowSet > 0) {
        echo "<script>window.location='manageOutlets.php?err=1'</script>";
    } else {

        $sql = "DELETE FROM tbl_deptusers  WHERE id='" . $_GET['delId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
        mysqli_query($con, $sql);

        $sql = "DELETE FROM tbl_revenue_center_departments  WHERE deptId='" . $_GET['delId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
        mysqli_query($con, $sql);

        $sql = "DELETE FROM tbl_map_outlets  WHERE OutLetId='" . $resultRow['id'] . "' AND revId = '" . $resultRow['revCenterId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
        mysqli_query($con, $sql);

        $sql = "DELETE FROM tbl_map_outletcats  WHERE revOutLetId='" . $resultRow['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
        mysqli_query($con, $sql);

        $sql = "DELETE FROM tbl_designation_sub_section_permission WHERE type_id='" . $_GET['delId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' AND type = 'member' AND designation_id = '" . $_SESSION['designation_id'] . "' AND designation_Section_permission_id = '2' ";
        mysqli_query($con, $sql);

        echo '<script>window.location="manageOutlets.php?delete=1&deptId=' . $_GET['deptId'] . '"</script>';
    }
}


?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Manage Outlets - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/style1.css">

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
                            <h1 class="h1"><?php echo showOtherLangText('Manage Outlets'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Manage Outlets'); ?></h1>
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
                                <p><?php
                                    echo isset($_GET['edit']) ? ' ' . showOtherLangText('OutLet Edited Successfully') . ' ' : '';

                                    echo isset($_GET['added']) ? ' ' . showOtherLangText('OutLet Added Successfully') . ' ' : '';

                                    echo isset($_GET['delete']) ? ' ' . showOtherLangText('OutLet Deleted Successfully') . ' ' : '';
                                    ?>
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        <?php } ?>
                        <?php if (isset($_GET['err'])) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo isset($_GET['err']) ? ' ' . showOtherLangText('OutLet can not be deleted as it has some items') . ' ' : '';
                                    ?></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        <?php } ?>
                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="setup.php" class="btn btn-primary mb-usrBkbtn"><span class="mb-UsrBtn"><i
                                                class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Back') ?></span></a>
                                </div>
                            </div>
                            <div class="usrAd-Btn">
                                <div class="btnBg">
                                    <a href="addOutlet.php" class="btn btn-primary mb-usrBkbtn res__w__auto"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-plus"></i>
                                            <span class="nstdSpan">Outlet</span></span> <span class="dsktp-Btn">
                                            <?php echo showOtherLangText('Add') ?></span></a>
                                </div>
                            </div>
                        </div>

                        <div class="outletTable">
                            <div class="container pb-5">
                                <!-- Table Head Start -->
                                <div class="usrTbl-Head align-items-center itmTable">
                                    <div class="outTbl-Cnt d-flex align-items-center">
                                        <div class="tb-head outNum-Clm">
                                            <div class="d-flex align-items-center">
                                                <p>#</p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" onclick="sortRows(1);" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" onclick="sortRows(0);" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="tb-head outName-Clm">
                                            <p><?php echo showOtherLangText('Name'); ?></p>
                                        </div>
                                    </div>
                                    <div class="outTbl-Icns">
                                        <div class="tb-head outOpt-Clm text-center">
                                            <p><?php echo showOtherLangText('Options'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div id="myRecords">
                                    <!-- Table Head End -->

                                    <!-- Table Body Start -->
                                    <?php

                                    $pageNo = $pn - 1;

                                    $x = isset($_GET['page']) ? (($pageNo * $limit)) : 0;

                                    $sql = " SELECT * FROM tbl_deptusers WHERE account_id = '" . $_SESSION['accountId'] . "'  order by name ";
                                    $result = mysqli_query($con, $sql);
                                    while ($row = mysqli_fetch_array($result)) {
                                        $selQry = " SELECT * FROM tbl_revenue_center_departments WHERE deptId = '" . $row['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
                                        $selResult = mysqli_query($con, $selQry);
                                        $selResultRow = mysqli_fetch_array($selResult);

                                        $color = ($x % 2 == 0) ? 'white' : '#FFFFCC';

                                        $x++;

                                    ?>
                                        <div class="userTask">
                                            <div class="usrTbl-body align-items-center itmBody">
                                                <div class="outTbl-Cnt d-flex align-items-center">
                                                    <div class="tb-bdy outNum-Clm">
                                                        <p class="userNumber"><span class="mb-UsrSpan">No. </span><strong><?php echo $x; ?></strong></p>
                                                    </div>
                                                    <div class="tb-bdy outName-Clm">
                                                        <p class="userName"><span><?php echo $row['name']; ?></span></p>
                                                    </div>
                                                </div>
                                                <div class="outTbl-Icns">
                                                    <div class="tb-bdy outOpt-Clm d-flex align-items-center">
                                                        <a href="editOutlet.php?id=<?php echo $row['id']; ?>&deptId=<?php echo $row['deptId']; ?>&revCenDeptId=<?php echo $selResultRow['id']; ?>" class="userLink">
                                                            <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                        </a>
                                                        <a href="javascript:void(0)" onClick="getDelNumb('<?php echo $row['id']; ?>','<?php echo $row['deptId']; ?>');" class="userLink">
                                                            <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php

                                    }

                                    ?>
                                </div>
                                <!-- Table Body End -->

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
        var newOnClick = "window.location.href='manageOutlets.php?delId=" + delId + "'";

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
        var uu = $(".userTask").orderBy(function() {
            var number = +$(this).find(".userNumber").text().replace('No. ', '');
            return sort === 1 ? number : -number;
        }).appendTo("#myRecords");


    }
</script>