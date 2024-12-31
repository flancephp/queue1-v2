<?php
include('inc/dbConfig.php'); //connection details

if (!isset($_SESSION['adminidusername'])) {
    echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'unit' AND designation_Section_permission_id = '8' AND designation_id = '" . $_SESSION['designation_id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if (!$permissionRow) {
    echo "<script>window.location='index.php'</script>";
}



if (isset($_GET['delId']) && $_GET['delId']) {
    $selQry = " SELECT * FROM tbl_products WHERE (unitP = '" . $_GET['delId'] . "' || unitC = '" . $_GET['delId'] . "') AND account_id = '" . $_SESSION['accountId'] . "' ";

    $res = mysqli_query($con, $selQry);
    $resRow = mysqli_fetch_array($res);


    $selQry = " SELECT * FROM tbl_outlet_items WHERE subUnit = '" . $_GET['delId'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";

    $result = mysqli_query($con, $selQry);
    $resultRow = mysqli_fetch_array($result);

    if ($resRow > 0 || $resultRow > 0) {

        echo '<script>window.location="manageUnits.php?err=1"</script>';
    } else {

        $sql = "DELETE FROM tbl_units  WHERE id='" . $_GET['delId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";

        mysqli_query($con, $sql);

        echo '<script>window.location="manageUnits.php?delete=1"</script>';
    }
}



$sql = " SELECT * FROM tbl_units WHERE account_id = '" . $_SESSION['accountId'] . "'  order by name ";
$result = mysqli_query($con, $sql);


if (isset($_POST['name'])) {

    $unitQry = " SELECT * FROM tbl_units WHERE name='" . $_POST['name'] . "' AND account_id='" . $_SESSION['accountId'] . "' ";
    $unitResult = mysqli_query($con, $unitQry);
    $unitRow = mysqli_num_rows($unitResult);

    if ($unitRow == 0) {
        $insQry = " INSERT INTO tbl_units SET name='" . $_POST['name'] . "', account_id='" . $_SESSION['accountId'] . "' ";
        mysqli_query($con, $insQry);
        echo '<script>window.location="manageUnits.php?added=1"</script>';
    } else {
        echo '<script>window.location="manageUnits.php?error=1&name=' . $_POST['name'] . ' "</script>';
    }
}

if (isset($_POST['editUnit']) && isset($_POST['id']) && $_POST['id'] > 0) {

    $unitQry = " SELECT * FROM tbl_units WHERE id ='" . $_POST['id'] . "' AND account_id='" . $_SESSION['accountId'] . "' ";

    $unitResult = mysqli_query($con, $unitQry);
    $unitRow = mysqli_num_rows($unitResult);

    if ($unitRow > 0) {

        $updateQry = " UPDATE tbl_units SET name='" . $_POST['editUnit'] . "' WHERE id='" . $_POST['id'] . "' AND account_id='" . $_SESSION['accountId'] . "' ";
        mysqli_query($con, $updateQry);


        echo '<script>window.location="manageUnits.php?edit=1"</script>';
    }
}


?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Manage Units - Queue1</title>
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
                            <h1 class="h1"><?php echo showOtherLangText('Manage Units') ?></h1>
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
                                    <h1 class="h1">Manage Units</h1>
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
                        <?php if (isset($_GET['added']) || isset($_GET['update']) || isset($_GET['edit']) || isset($_GET['delete'])) { ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p><?php

                                    echo isset($_GET['edit']) ? ' ' . showOtherLangText('Unit Edited Successfully') . ' ' : '';

                                    echo isset($_GET['added']) ? ' ' . showOtherLangText('Unit Added Successfully') . ' ' : '';

                                    echo isset($_GET['delete']) ? ' ' . showOtherLangText('Unit Deleted Successfully') . ' ' : '';

                                    ?>
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        <?php } ?>
                        <?php if (isset($_GET['err'])) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo isset($_GET['err']) ? ' ' . showOtherLangText('Unit cannot be delete as it is being used in product') . ' ' : '';
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
                                            class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                </div>
                            </div>
                            <div class="usrAd-Btn">
                                <div class="btnBg">
                                    <a href="javascript:void(0)" class="btn btn-primary mb-usrBkbtn"
                                        data-bs-toggle="modal" data-bs-target="#add-Unit"><span class="mb-UsrBtn"><i
                                                class="fa-solid fa-plus"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Add'); ?></span></a>
                                </div>
                            </div>
                        </div>

                        <div class="mng-UntTable">
                            <div class="container pb-5">
                                <!-- Table Head Start -->
                                <div class="mng-UntTbl-Head align-items-center itmTable">
                                    <div class="mng-UntTbl-Cnt d-flex align-items-center">
                                        <div class="tb-head mng-UntNum-Clm">
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
                                        <div class="tb-head mng-UntName-Clm">
                                            <p><?php echo showOtherLangText('Name') ?></p>
                                        </div>
                                    </div>
                                    <div class="mng-UntTbl-Icns">
                                        <div class="tb-head outOpt-Clm text-center">
                                            <p><?php echo showOtherLangText('Options') ?></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Table Head End -->
                                <div id="myRecords">
                                    <!-- Table Body Start -->
                                    <?php

                                    $pageNo = $pn - 1;

                                    $x = isset($_GET['page']) ? (($pageNo * $limit)) : 0;

                                    while ($row = mysqli_fetch_array($result)) {

                                        $color = ($x % 2 == 0) ? 'white' : '#FFFFCC';
                                        $x++;
                                    ?>
                                        <div class="userTask">
                                            <div class="mng-UntTbl-body align-items-center itmBody">
                                                <div class="mng-UntTbl-Cnt d-flex align-items-center">
                                                    <div class="tb-bdy mng-UntNum-Clm">
                                                        <p class="userNumber"><span class="mb-UsrSpan">No. </span><strong><?php echo $x; ?></strong></p>
                                                    </div>
                                                    <div class="tb-bdy mng-UntName-Clm">
                                                        <p class="userName"><span><?php echo $row['name']; ?></span></p>
                                                    </div>
                                                </div>
                                                <div class="mng-UntTbl-Icns">
                                                    <div class="tb-bdy mng-UntOpt-Clm d-flex align-items-center">
                                                        <a href="javascript:void(0)" data-editid="<?php echo $row['id']; ?>" data-name="<?php echo $row['name']; ?>" class="userLink editicon" data-bs-toggle="modal"
                                                            data-bs-target="#edit-Unit">
                                                            <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                        </a>
                                                        <a href="javascript:void(0)" class="userLink editicon" onClick="getDelNumb('<?php echo $row['id']; ?>');">
                                                            <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                        </a>
                                                    </div>
                                                </div>
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


    <!-- Add Unit Popup Start -->
    <div class="modal" tabindex="-1" id="add-Unit" aria-labelledby="add-UnitLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php echo showOtherLangText('Add Unit') ?></h1>
                </div>
                <form class="addUser-Form row" id="unitfrm" name="unitfrm" action="" method="post">
                    <div class="modal-body">

                        <input oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" onChange="this.setCustomValidity('')" required type="text" class="form-control" name="name" id="name" value="" placeholder="<?php echo showOtherLangText('Name') ?>*">

                    </div>
                    <div class="modal-footer">
                        <div class="btnBg">
                            <button type="submit" class="btn sub-btn std-btn"><?php echo showOtherLangText('Add') ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Unit Popup End -->

    <!-- Edit Unit Popup Start -->
    <form role="form" action="" class="addUser-Form row container glbFrm-Cont" method="post">
        <div class="modal" tabindex="-1" id="edit-Unit" aria-labelledby="edit-UnitLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h1 class="modal-title h1"><?php echo showOtherLangText('Edit Unit'); ?></h1>
                    </div>
                    <div class="modal-body">

                        <input type="text" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"  onChange="this.setCustomValidity('')" required class="form-control" id="editUnit" name="editUnit" placeholder="<?php echo showOtherLangText('Name') ?>*">

                        <input type="hidden" name="id" id="edit-id" value="" />
                    </div>
                    <div class="modal-footer">
                        <div class="btnBg">
                            <button type="submit" class="btn sub-btn std-btn"><?php echo showOtherLangText('Save'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Unit Popup End -->
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
    $(document).ready(function() {
        $('.editicon').click(function() {

            var editIdValue = this.getAttribute("data-editid");
            var editnameValue = this.getAttribute("data-name");
            $('#editUnit').val(editnameValue);
            $('#edit-id').val(editIdValue);
        });
    });

    function getDelNumb(delId) {
        var newOnClick = "window.location.href='manageUnits.php?delId=" + delId + "'";

        $('.deletelink').attr('onclick', newOnClick);
        $('#delete-popup').modal('show');

    }

    jQuery.fn.orderBy = function(keySelector) {
        return this.sort(function(a, b) {
            a = keySelector.apply(a);
            b = keySelector.apply(b);
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