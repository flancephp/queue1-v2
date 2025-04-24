<?php
include('inc/dbConfig.php'); //connection details

if (!isset($_SESSION['adminidusername'])) {
    echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


include_once('script/editDesignation.php');


?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Edit title - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/style1.css">
    <style>
        /* .fs-13{font-weight: 400;}
       .fs-13 {
        font-size: 15px;
        } */
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
                            <h1 class="h1"><?php echo showOtherLangText('Edit Title'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Edit Title'); ?></h1>
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
                    <form name="frm" id="frm" method="post" action="" class="container">
                        <div class="container">
                            <?php if (isset($_GET['added']) || isset($_GET['edit']) || isset($_GET['delete']) || isset($_GET['imported']) || isset($_GET['mes'])) { ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <p>
                                        <?php

                                        echo isset($_GET['edit']) ? ' ' . showOtherLangText('Item Edited Successfully') . ' ' : '';
                                        echo isset($_GET['added']) ? ' ' . showOtherLangText('Item Added Successfully') . ' ' : '';
                                        echo isset($_GET['imported']) ? ' ' . showOtherLangText('Item imported Successfully') . ' ' : '';
                                        echo isset($_GET['mes']) ? $_GET['mes'] : '';

                                        if (isset($_GET['delete']) && $_GET['delete'] == 1) {
                                            echo ' ' . showOtherLangText('Item Deleted Successfully') . ' ';
                                        } elseif (isset($_GET['delete']) && $_GET['delete'] == 2) {
                                            echo ' ' . showOtherLangText('This item is in stock or ordered by someone so cannot be deleted') . ' ';
                                        }

                                        ?>
                                    </p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            <?php } ?>
                            <?php if (isset($_GET['error'])) { ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <p><?php echo showOtherLangText('You can not create the same title name as this name already created'); ?></p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            <?php } ?>
                            <div class="usrBtns d-flex align-items-center justify-content-between">
                                <div class="usrBk-Btn">
                                    <div class="btnBg">
                                        <a href="listDesignation.php" class="btn btn-primary mb-usrBkbtn"><span
                                                class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span
                                                class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                    </div>
                                </div>
                                <div class="usrAd-Btn">
                                    <button type="submit" class="btn btn-primary mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-regular fa-floppy-disk"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Save'); ?></span></button>

                                </div>
                            </div>

                            <div class="edtSup-Div w-100">
                                <div class="addUser-Form acntSetup-Form">

                                    <div class="row align-items-center acntStp-Row w-50">
                                        <div class="col-md-2">
                                            <label for="Name" class="form-label"><?php echo showOtherLangText('Name'); ?>:<span
                                                    class="requiredsign">*</span></label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" value="<?php echo $designationName; ?>" name="designation_name" class="form-control"

                                                onchange="this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" required />
                                        </div>
                                    </div>


                                    <div id="titletype">

                                        <div style="display: none;" class="row align-items-center acntStp-Row w-50">
                                            <div class="col-md-2">
                                                <label for="Type" class="form-label d-block"><?php echo showOtherLangText('Title Type'); ?></label>
                                            </div>
                                            <div class="col-md-10">
                                                <span class="d-inline-flex align-items-center gap-2">
                                                    <input type="radio" name="title_type" class="form-check-input m-0" name="type_web" id="type_web" data-bs-toggle="collapse" value="0" data-bs-target="#web">
                                                    <label for="type_web" class="form-label mb-0"><?php echo showOtherLangText('Web'); ?></label>
                                                </span>
                                                <span class="ps-3 d-inline-flex align-items-center gap-2">
                                                    <input type="radio" name="title_type" class="form-check-input m-0" value="1" id="type_mobile" data-bs-toggle="collapse" data-bs-target="#mobile">
                                                    <label for="type_mobile" class="form-label mb-0"><?php echo showOtherLangText('Mobile'); ?></label>
                                                </span>

                                            </div>
                                        </div>


                                        <div class="webItem pt-4 collapse " id="web" data-bs-parent="#titletype">

                                            <div>
                                                <div class="checkbox-list border-bottom pt-3 pb-2">

                                                    <input type="checkbox" name="section_check[]" class="form-check-input" id="new-order-section" <?php echo (in_array('1', $selectedSectionArr) && in_array('0', $checkSectionVersion)) ? 'checked="checked"' : ''; ?> value="1">
                                                    <label class="medium" for="new_order" data-bs-toggle="collapse" data-bs-target="#newOrder"><?php echo showOtherLangText('New Order') ?></label>
                                                </div>

                                                <div class="collapse py-4" id="newOrder">
                                                    <p class="supplier-text bold pb-2"><?php echo showOtherLangText('Supplier'); ?></p>
                                                    <div class="supplier-text pb-3">
                                                        <input type="checkbox" class="supplierCheckall form-check-input" class="form-check-input" id="supplierCheckall">
                                                        <label for="suppliercheckall" class="fs-13 semibold"><?php echo showOtherLangText('Check All') ?></label>
                                                    </div>


                                                    <div class="row title-listing-item">
                                                        <?php
                                                        while ($supplierRow = mysqli_fetch_array($supplierRes)) {
                                                        ?>
                                                            <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                                <div class="supplier-inner-listbox">
                                                                    <input type="checkbox" name="new_supplierCheck[]" id="supplierCheckbox"
                                                                        class="supplierCheckbox  form-check-input" value="<?php echo $supplierRow['id'] ?>" <?php echo in_array($supplierRow['id'], $supIdsArr) ? 'checked="checked"' : '' ?>>
                                                                    <label><?php echo $supplierRow['name'] ?></label>
                                                                </div>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>

                                                    <div class="row  py-3 mt-2">
                                                        <div class="col-md-2 col-4"></div>
                                                        <div class="col-md-10 col-8">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <div>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        id="checkallOrder" name="check_all">
                                                                    <label class="fs-13 semibold"><?php echo showOtherLangText('Check All'); ?></label>
                                                                </div>
                                                                <div>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        id="uncheckallOrder" name="uncheck_all">
                                                                    <label class="fs-13 semibold"><?php echo showOtherLangText('Uncheck All'); ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    foreach ($orderArr as $orderArrSubRow) {

                                                        $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = '" . $orderArrSubRow['name'] . "' AND designation_id = '" . $_GET['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
                                                        $accessOrderRes = mysqli_query($con, $sql);
                                                        $accessOrderRow = mysqli_fetch_array($accessOrderRes);
                                                    ?>
                                                        <div class="row">
                                                            <div class="col-md-2 col-4">
                                                                <p class="semibold fs-13"><?php echo showOtherLangText($orderArrSubRow['title']); ?>:</p>
                                                            </div>
                                                            <div class="col-md-10 col-8">

                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div class="order-radio enableOrder">
                                                                        <input type="radio" name="<?php echo $orderArrSubRow['name']; ?>"
                                                                            class="order-enable form-check-input" value="1" <?php echo $accessOrderRow['type_id'] == 1 ? 'checked="checked"' : ''; ?>>
                                                                        <label class="fs-13"><?php echo showOtherLangText('Enable'); ?></label>
                                                                    </div>
                                                                    <div class="order-radio desableOrder">
                                                                        <input type="radio" name="<?php echo $orderArrSubRow['name']; ?>"
                                                                            class="order-disable form-check-input" <?php echo $accessOrderRow['type_id'] == 0 ? 'checked="checked"' : ''; ?> value="0">
                                                                        <label class="fs-13"><?php echo showOtherLangText('Disable'); ?></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php

                                                    }

                                                    ?>

                                                </div>
                                            </div>


                                            <div>
                                                <div class="checkbox-list border-bottom pt-3 pb-2">
                                                    <input type="checkbox" name="section_check[]" class="form-check-input" id="new-requisition-section" value="2" <?php echo (in_array('2', $selectedSectionArr) && in_array('0', $checkSectionVersion)) ? 'checked="checked"' : ''; ?>>
                                                    <label data-bs-toggle="collapse" data-bs-target="#show-requisition-detail" class="medium" for="new-requisition-section"><?php echo showOtherLangText('New Requisition'); ?></label>
                                                </div>

                                                <div class="collapse py-4" id="show-requisition-detail">

                                                    <p class="bold pb-2"><?php echo showOtherLangText('Members'); ?></p>


                                                    <div class="supplier-text pb-3">
                                                        <input type="checkbox" class="form-check-input" id="memberCheckall">
                                                        <label for="memberCheckall" class="fs-13 semibold"><?php echo showOtherLangText('Check All') ?></label>
                                                    </div>

                                                    <div class="row  title-listing-item">
                                                        <?php
                                                        while ($deptUserRow = mysqli_fetch_array($deptUserRes)) {
                                                        ?>
                                                            <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6 ">
                                                                <div class="member-inner-listbox">
                                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="<?php echo $deptUserRow['id'] ?>" <?php echo in_array($deptUserRow['id'], $memIdsArr) ? 'checked="checked"' : '' ?>>
                                                                    <label><?php echo $deptUserRow['name'] ?></label>
                                                                </div>
                                                            </div>
                                                        <?php
                                                        }

                                                        ?>



                                                    </div>


                                                    <?php
                                                    $checkAll = true;
                                                    $unCheckAll = true;
                                                    $radioOptions = '';
                                                    foreach ($requisitionArr as $requisitionArrSubRow) {
                                                        $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = '" . $requisitionArrSubRow['name'] . "' AND designation_id = '" . $_GET['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
                                                        $accessReqRes = mysqli_query($con, $sql);
                                                        $accessReqRow = mysqli_fetch_array($accessReqRes);

                                                        ////echo '=' . $accessReqRow['type_id'] . '=<br>';
                                                        if ($accessReqRow['type_id'] == 0) {
                                                            $checkAll = false;
                                                        }

                                                        if ($accessReqRow['type_id'] == 1) {
                                                            $unCheckAll = false;
                                                        }



                                                        $radioOptions .= '<div class="row align-items-center pb-2">
                                                            <div class="col-md-2 col-4">
                                                                <p class="semibold fs-13">' . showOtherLangText($requisitionArrSubRow['title']) . ':</p>
                                                            </div>
                                                            <div class="col-md-10 col-8">

                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div class="requisition-radio enableRequisition">
                                                                        <input type="radio" name="' . $requisitionArrSubRow['name'] . '" class="requisition-enable form-check-input" value="1"  ' . ($accessReqRow['type_id'] == 1 ? 'checked="checked"' : "") . '>
                                                                        <label class="fs-13">' . showOtherLangText('Enable') . '</label>
                                                                    </div>
                                                                    <div class="requisition-radio desableRequisition">
                                                                        <input type="radio" name="' . $requisitionArrSubRow['name'] . '" class="requisition-disable form-check-input" ' . ($accessReqRow['type_id'] == 0 ? 'checked="checked"' : '') . ' value="0">
                                                                        <label class="fs-13">' . showOtherLangText('Disable') . '</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>';
                                                    }
                                                    ?>

                                                    <div class="row pb-2  py-3 mt-2">
                                                        <div class="col-md-2 col-4"></div>
                                                        <div class="col-md-10 col-8">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <div>
                                                                    <input type="checkbox" class="form-check-input" id="checkallRequisition" name="check_all" <?php echo $checkAll ? 'checked="checked"' : ''; ?>>
                                                                    <label class="fs-13 semibold"><?php echo showOtherLangText('Check All'); ?></label>
                                                                </div>
                                                                <div>
                                                                    <input type="checkbox" class="form-check-input" id="uncheckallRequisition" name="uncheck_all" <?php echo $unCheckAll ? 'checked="checked"' : ''; ?>>
                                                                    <label class="fs-13 semibold"><?php echo showOtherLangText('Uncheck All'); ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php

                                                    //show here all check boxes
                                                    echo $radioOptions;
                                                    ?>
                                                </div>

                                            </div>




                                            <div>
                                                <div class="checkbox-list border-bottom pt-3 pb-2">
                                                    <input type="checkbox" name="section_check[]" class="form-check-input" id="runningTask-section" <?php echo (in_array('3', $selectedSectionArr) && in_array('0', $checkSectionVersion)) ? 'checked="checked"' : ''; ?> value="3">
                                                    <label data-bs-toggle="collapse" data-bs-target="#show-runningTask-detail" class="medium" for="runningTask-section"><?php echo showOtherLangText('Running Tasks'); ?></label>
                                                </div>
                                                <div class="collapse py-4" id="show-runningTask-detail">

                                                    <?php
                                                    $checkAll = true;
                                                    $unCheckAll = true;
                                                    $radioOptions = '';
                                                    foreach ($runningTaskArr as $runningTaskSubRow) {
                                                        $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = '" . $runningTaskSubRow['name'] . "' AND designation_id = '" . $_GET['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
                                                        $accessRunningtaskRes = mysqli_query($con, $sql);
                                                        $AccessRunningtaskRow = mysqli_fetch_array($accessRunningtaskRes);

                                                        if ($AccessRunningtaskRow['type_id'] == 0) {
                                                            $checkAll = false;
                                                        }

                                                        if ($AccessRunningtaskRow['type_id'] == 1) {
                                                            $unCheckAll = false;
                                                        }

                                                        $radioOptions .= '<div class="row align-items-center pb-2">
                                                            <div class="col-md-2 col-4">
                                                                <p class="semibold fs-13">' . showOtherLangText($runningTaskSubRow['title']) . ':</p>
                                                            </div>
                                                            <div class="col-md-10 col-8">

                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div class="runningtask-radio enableRunningtask">
                                                                        <input type="radio" name="' . $runningTaskSubRow['name'] . '" class="runningtask-enable form-check-input" value="1" ' . ($AccessRunningtaskRow['type_id'] == 1 ? 'checked="checked"' : '') . '>
                                                                        <label class="fs-13">' . showOtherLangText('Enable') . '</label>
                                                                    </div>
                                                                    <div class="runningtask-radio desableRunningtask">
                                                                        <input type="radio" name="' . $runningTaskSubRow['name'] . '" class="runningtask-disable form-check-input" ' . ($AccessRunningtaskRow['type_id'] == 0 ? 'checked="checked"' : '') . ' value="0">
                                                                        <label class="fs-13">' . showOtherLangText('Disable') . '</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>';
                                                    } ?>


                                                    <div class="row pb-2">
                                                        <div class="col-md-2 col-4"></div>
                                                        <div class="col-md-10 col-8">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <div>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        id="checkallRunningtask" name="check_all" <?php echo $checkAll ? 'checked="checked"' : ''; ?>
                                                                        <label class="fs-13 semibold"><?php echo showOtherLangText('Check All'); ?></label>
                                                                </div>
                                                                <div>
                                                                    <input type="checkbox" class="form-check-input" id="uncheckallRunningtask" name="uncheck_all" <?php echo $unCheckAll ? 'checked="checked"' : ''; ?>>
                                                                    <label class="fs-13 semibold"><?php echo showOtherLangText('Uncheck All'); ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>





                                                    <?php
                                                    //show radio options here for running tasks
                                                    echo $radioOptions;
                                                    ?>
                                                </div>


                                            </div>


                                            <div>
                                                <div class="checkbox-list border-bottom pt-3 pb-2">
                                                    <input type="checkbox" class="form-check-input" id="history-section" <?php echo (in_array('4', $selectedSectionArr) && in_array('0', $checkSectionVersion)) ? 'checked="checked"' : ''; ?> name="section_check[]" value="4">
                                                    <label data-bs-toggle="collapse" data-bs-target="#show-history-detail" class="medium" for="t_history"><?php echo showOtherLangText('History'); ?></label>
                                                </div>
                                                <div class="collapse py-4" id="show-history-detail">
                                                    <?php
                                                    $checkAll = true;
                                                    $unCheckAll = true;
                                                    $radioOptions = '';
                                                    foreach ($historyArr as $historySubRow) {


                                                        $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = '" . $historySubRow['name'] . "' AND designation_id = '" . $_GET['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
                                                        $accesshistoryRes = mysqli_query($con, $sql);
                                                        $accesshistoryRow = mysqli_fetch_array($accesshistoryRes);

                                                        if ($accesshistoryRow['type_id'] == 0) {
                                                            $checkAll = false;
                                                        }

                                                        if ($accesshistoryRow['type_id'] == 1) {
                                                            $unCheckAll = false;
                                                        }

                                                        $radioOptions .= '<div class="row align-items-center pb-2">
                                                            <div class="col-md-2 col-4">
                                                                <p class="semibold fs-13">' . showOtherLangText($historySubRow['title']) . ':</p>
                                                            </div>
                                                            <div class="col-md-10 col-8">

                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div class="history-radio enableHistory">
                                                                        <input type="radio" name="' . $historySubRow['name'] . '" ' . ($accesshistoryRow['type_id'] == 1 ? 'checked="checked"' : '') . ' class="form-check-input history-enable" value="1">
                                                                        <label class="fs-13">' . showOtherLangText('Enable') . '</label>
                                                                    </div>
                                                                    <div class="history-radio desableHistory">
                                                                        <input type="radio" name="' . $historySubRow['name'] . '" ' . ($accesshistoryRow['type_id'] == 0 ? 'checked="checked"' : '') . ' class="form-check-input history-disable" value="0">
                                                                        <label class="fs-13">' . showOtherLangText('Disable') . '</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>';
                                                    }
                                                    ?>

                                                    <div class="row pb-2">
                                                        <div class="col-md-2 col-4"></div>
                                                        <div class="col-md-10 col-8">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <div>
                                                                    <input type="checkbox" class="form-check-input" id="checkallHistory" name="check_all" <?php echo $checkAll ? 'checked="checked"' : ''; ?>>
                                                                    <label class="fs-13 semibold"><?php echo showOtherLangText('Check All'); ?></label>
                                                                </div>
                                                                <div>
                                                                    <input type="checkbox" class="form-check-input" id="uncheckallHistory" name="uncheck_all" <?php echo $unCheckAll ? 'checked="checked"' : ''; ?>>
                                                                    <label class="fs-13 semibold"><?php echo showOtherLangText('Uncheck All'); ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php

                                                    //history radio opitons
                                                    echo $radioOptions;
                                                    ?>


                                                </div>



                                                <div>
                                                    <div class="checkbox-list border-bottom pt-3 pb-2">
                                                        <input type="checkbox" name="section_check[]" class="form-check-input" id="stock-section" value="5" <?php echo (in_array('5', $selectedSectionArr) && in_array('0', $checkSectionVersion)) ? 'checked="checked"' : ''; ?>>
                                                        <label data-bs-toggle="collapse" data-bs-target="#show-stock-detail" class="medium" for="stock1"><?php echo showOtherLangText('Stock'); ?></label>
                                                    </div>
                                                    <div class="collapse py-4" id="show-stock-detail">

                                                        <p class="supplier-text bold pb-2"><?php echo showOtherLangText('Store Access'); ?>:</p>

                                                        <div class="supplier-text pb-3">
                                                            <input type="checkbox" class="storeCheckAll form-check-input" class="form-check-input" id="storeCheckAll">
                                                            <label for="storeCheckAll" class="fs-13 semibold"><?php echo showOtherLangText('Check All') ?></label>
                                                        </div>

                                                        <div class="row title-listing-item">

                                                            <?php

                                                            while ($storeRow = mysqli_fetch_array($storeRes)) {
                                                            ?>
                                                                <div class="strAcs-inner-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                                    <input type="checkbox" name="store_check[]" class="stock-section form-check-input" value="<?php echo $storeRow['id'] ?>" <?php echo in_array($storeRow['id'], $storeIdsArr) ? 'checked="checked"' : '' ?>>
                                                                    <label><?php echo $storeRow['name']; ?></label>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>





                                                        </div>

                                                        <?php
                                                        $checkAll = true;
                                                        $unCheckAll = true;
                                                        $radioOptions = '';
                                                        foreach ($stockArr as $stockSubRow) {
                                                            $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = '" . $stockSubRow['name'] . "' AND designation_id = '" . $_GET['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
                                                            $accessStockRes = mysqli_query($con, $sql);
                                                            $accessStockRow = mysqli_fetch_array($accessStockRes);

                                                            if ($accessStockRow['type_id'] == 0) {
                                                                $checkAll = false;
                                                            }

                                                            if ($accessStockRow['type_id'] == 1) {
                                                                $unCheckAll = false;
                                                            }

                                                            $radioOptions .= '<div class="row align-items-center pb-2">
                                                                <div class="col-md-2 col-4">
                                                                    <p class="semibold fs-13">' . showOtherLangText($stockSubRow['title']) . ':</p>
                                                                </div>
                                                                <div class="col-md-10 col-8">

                                                                    <div class="d-flex align-items-center gap-3">
                                                                        <div class="stock-radio enableStock">
                                                                            <input type="radio" name="' . $stockSubRow['name'] . '" class="stock-enable form-check-input" value="1" ' . ($accessStockRow['type_id'] == 1 ? 'checked="checked"' : '') . '>
                                                                            <label class="fs-13">' . showOtherLangText('Enable') . '</label>
                                                                        </div>
                                                                        <div class="stock-radio desableStock">
                                                                            <input type="radio" name="' . $stockSubRow['name'] . '" class="form-check-input stock-disable" value="0" ' . ($accessStockRow['type_id'] == 0 ? 'checked="checked"' : '') . '>
                                                                            <label class="fs-13">' . showOtherLangText('Disable') . '</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>';
                                                        }
                                                        ?>
                                                        <div class="row pb-2 mt-2  py-3 ">
                                                            <div class="col-md-2 col-4"></div>
                                                            <div class="col-md-10 col-8">
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div>
                                                                        <input type="checkbox" class="form-check-input" id="checkallStock" name="check_all" <?php echo $checkAll ? 'checked="checked"' : ''; ?>>
                                                                        <label class="fs-13 semibold"><?php echo showOtherLangText('Check All'); ?></label>
                                                                    </div>
                                                                    <div>
                                                                        <input type="checkbox" class="form-check-input" id="uncheckallStock" name="uncheck_all" <?php echo $unCheckAll ? 'checked="checked"' : ''; ?>>
                                                                        <label class="fs-13 semibold"><?php echo showOtherLangText('Uncheck All'); ?></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php

                                                        //stock radion options
                                                        echo $radioOptions;
                                                        ?>



                                                    </div>

                                                </div>


                                                <!--
                                                <div>
                                                    <div class="checkbox-list border-bottom pt-3 pb-2" data-bs-toggle="collapse" data-bs-target="#show-stocktake-detail">
                                                        <input type="checkbox" name="section_check[]" class="form-check-input" id="new-stocktake-section" <?php echo (in_array('6', $selectedSectionArr) && in_array('0', $checkSectionVersion)) ? 'checked="checked"' : ''; ?> value="6">
                                                        <label class="medium" for="new-stocktake-section"><?php echo showOtherLangText('New Stocktake'); ?></label>
                                                    </div>
                                                    <div class="collapse py-4" id="show-stocktake-detail">

                                                        <?php
                                                        $checkAll = true;
                                                        $unCheckAll = true;
                                                        $radioOptions = '';
                                                        foreach ($newStockTakeArr as $newStockTakeSubRow) {
                                                            $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = '" . $newStockTakeSubRow['name'] . "' AND designation_id = '" . $_GET['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
                                                            $accessNewStockTakeRes = mysqli_query($con, $sql);
                                                            $accessNewStockTakeRow = mysqli_fetch_array($accessNewStockTakeRes);

                                                            if ($accessNewStockTakeRow['type_id'] == 0) {
                                                                $checkAll = false;
                                                            }

                                                            if ($accessNewStockTakeRow['type_id'] == 1) {
                                                                $unCheckAll = false;
                                                            }

                                                            $radioOptions .= '<div class="row align-items-center pb-2">
                                                                <div class="col-md-2 col-4">
                                                                    <p class="semibold fs-13"> ' . showOtherLangText($newStockTakeSubRow['title']) . '</p>
                                                                </div>
                                                                <div class="col-md-10 col-8">

                                                                    <div class="d-flex align-items-center gap-3">
                                                                        <div class="newStockTake-radio enableNewStockTake">
                                                                            <input type="radio" name="' . $newStockTakeSubRow['name'] . '" class="form-check-input" value="1" ' . ($accessNewStockTakeRow['type_id'] == 1 ? 'checked="checked"' : '') . '>
                                                                            <label class="fs-13">' . showOtherLangText('Enable') . '</label>
                                                                        </div>
                                                                        <div class="newStockTake-radio desableNewStockTake">
                                                                            <input type="radio" name="' . $newStockTakeSubRow['name'] . '" class="form-check-input" ' . ($accessNewStockTakeRow['type_id'] == 0 ? 'checked="checked"' : '') . ' value="0">
                                                                            <label class="fs-13">' . showOtherLangText('Disable') . '</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>';
                                                        }
                                                        ?>
                                                        <div class="row pb-2">
                                                            <div class="col-md-2 col-4"></div>
                                                            <div class="col-md-10 col-8">
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div class="newStockTake-radio enableNewStockTake">
                                                                        <input type="checkbox" class="form-check-input" id="checkallNewStockTake" name="check_all" <?php echo $checkAll ? 'checked="checked"' : ''; ?>>
                                                                        <label class="fs-13 semibold"><?php echo showOtherLangText('Check All'); ?></label>
                                                                    </div>
                                                                    <div class="newStockTake-radio desableNewStockTake">
                                                                        <input type="checkbox" class="form-check-input" id="uncheckallNewStockTake" name="uncheck_all" <?php echo $unCheckAll ? 'checked="checked"' : ''; ?>>
                                                                        <label class="fs-13 semibold"><?php echo showOtherLangText('Uncheck All'); ?></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php

                                                        //new stock take radio options

                                                        echo $radioOptions;
                                                        ?>
                                                    </div>

                                                </div>-->


                                                <div>
                                                    <div class="checkbox-list border-bottom pt-3 pb-2">
                                                        <input type="checkbox" name="section_check[]" class="form-check-input" <?php echo (in_array('7', $selectedSectionArr) && in_array('0', $checkSectionVersion)) ? 'checked="checked"' : ''; ?> id="revenueCenter-section" value="7">
                                                        <label data-bs-toggle="collapse" data-bs-target="#show-revenueCenter-detail" class="medium" for="revenue_center"><?php echo showOtherLangText('Revenue Center'); ?></label>
                                                    </div>
                                                    <div class="collapse py-4" id="show-revenueCenter-detail">

                                                        <?php
                                                        $checkAll = true;
                                                        $unCheckAll = true;
                                                        $radioOptions = '';
                                                        foreach ($revCenterArr as $revCenterSubRow) {
                                                            $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = '" . $revCenterSubRow['name'] . "' AND designation_id = '" . $_GET['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
                                                            $accessRevCenterRes = mysqli_query($con, $sql);
                                                            $accessRevCenterRow = mysqli_fetch_array($accessRevCenterRes);
                                                            //  print_r($accessRevCenterRow);

                                                            if ($accessRevCenterRow['type_id'] == 0) {
                                                                $checkAll = false;
                                                            }

                                                            if ($accessRevCenterRow['type_id'] == 1) {
                                                                $unCheckAll = false;
                                                            }

                                                            $radioOptions .= '<div class="row align-items-center pb-2">
                                                                <div class="col-md-2 col-4">
                                                                    <p class="semibold fs-13">' . showOtherLangText($revCenterSubRow['title']) . ':</p>
                                                                </div>
                                                                <div class="col-md-10 col-8">

                                                                    <div class="d-flex align-items-center gap-3">
                                                                        <div class="revenueCenter-radio enableRevenueCenter">
                                                                            <input type="radio" name="' . $revCenterSubRow['name'] . '" class="revenueCenter-enable form-check-input" value="1" ' . ($accessRevCenterRow['type_id'] == 1 ? 'checked="checked"' : '') . '>
                                                                            <label class="fs-13">' . showOtherLangText('Enable') . '</label>
                                                                        </div>
                                                                        <div class="revenueCenter-radio desableRevenueCenter">
                                                                            <input type="radio" name="' . $revCenterSubRow['name'] . '" class="revenueCenter-disable form-check-input" ' . ($accessRevCenterRow['type_id'] == 0 ? 'checked="checked"' : '') . ' value="0">
                                                                            <label class="fs-13">' . showOtherLangText('Disable') . '</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>';
                                                        }
                                                        ?>
                                                        <div class="row pb-2">
                                                            <div class="col-md-2 col-4"></div>
                                                            <div class="col-md-10 col-8">
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div>
                                                                        <input type="checkbox" class="form-check-input" id="checkallRevenueCenter" name="check_all" <?php echo $checkAll ? 'checked="checked"' : ''; ?>>
                                                                        <label class="fs-13 semibold"><?php echo showOtherLangText('Check All'); ?></label>
                                                                    </div>
                                                                    <div>
                                                                        <input type="checkbox" class="form-check-input" id="uncheckallRevenueCenter" name="uncheck_all" <?php echo $unCheckAll ? 'checked="checked"' : ''; ?>>
                                                                        <label class="fs-13 semibold"><?php echo showOtherLangText('Uncheck All'); ?></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php


                                                        //revenue center

                                                        echo $radioOptions;
                                                        ?>
                                                    </div>

                                                </div>


                                                <div>
                                                    <div class="checkbox-list border-bottom pt-3 pb-2">
                                                        <input type="checkbox" name="section_check[]" class="form-check-input" id="setup-section" <?php echo (in_array('8', $selectedSectionArr) && in_array('0', $checkSectionVersion)) ? 'checked="checked"' : ''; ?> value="8">
                                                        <label data-bs-toggle="collapse" data-bs-target="#show-setup-detail" class="medium" for="setup1"><?php echo showOtherLangText('Setup'); ?></label>
                                                    </div>
                                                    <div class="collapse py-4" id="show-setup-detail">
                                                        <?php
                                                        $checkAll = true;
                                                        $unCheckAll = true;
                                                        $radioOptions = '';
                                                        foreach ($setupArr as $setupSubRow) {
                                                            $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = '" . $setupSubRow['name'] . "' AND designation_id = '" . $_GET['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
                                                            $accessSetupRes = mysqli_query($con, $sql);
                                                            $accessSetupRow = mysqli_fetch_array($accessSetupRes);


                                                            if ($accessRevCenterRow['type_id'] == 0) {
                                                                $checkAll = false;
                                                            }

                                                            if ($accessRevCenterRow['type_id'] == 1) {
                                                                $unCheckAll = false;
                                                            }

                                                            $radioOptions .= '<div class="row align-items-center pb-2">
                                                                <div class="col-md-2 col-4">
                                                                    <p class="semibold fs-13"> ' . showOtherLangText($setupSubRow['title']) . ':</p>
                                                                </div>
                                                                <div class="col-md-10 col-8">

                                                                    <div class="d-flex align-items-center gap-3">
                                                                        <div class="stock-radio enableSetup">
                                                                            <input type="radio" name="' . $setupSubRow['name'] . '" class="form-check-input setup-enable" value="1" ' . ($accessSetupRow['type_id'] == 1 ? 'checked="checked"' : '') . '>
                                                                            <label class="fs-13">' . showOtherLangText('Enable') . '</label>
                                                                        </div>
                                                                        <div class="stock-radio desableSetup">
                                                                            <input type="radio" name="' . $setupSubRow['name'] . '" class="form-check-input setup-disable" ' . ($accessSetupRow['type_id'] == 0 ? 'checked="checked"' : '') . ' value="0">
                                                                            <label class="fs-13">' . showOtherLangText('Disable') . '</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>';
                                                        }
                                                        ?>

                                                        <div class="row pb-2">
                                                            <div class="col-md-2 col-4"></div>
                                                            <div class="col-md-10 col-8">
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div>
                                                                        <input type="checkbox" class="form-check-input" id="checkallSetup" name="check_all" <?php echo $checkAll ? 'checked="checked"' : ''; ?>>
                                                                        <label class="fs-13 semibold"><?php echo showOtherLangText('Check All'); ?></label>
                                                                    </div>
                                                                    <div>
                                                                        <input type="checkbox" class="form-check-input" id="uncheckallSetup" name="uncheck_all" <?php echo $unCheckAll ? 'checked="checked"' : ''; ?>>
                                                                        <label class="fs-13 semibold"><?php echo showOtherLangText('Uncheck All'); ?></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php

                                                        //setup radio options
                                                        echo $radioOptions;
                                                        ?>













                                                    </div>

                                                </div>




                                            </div>


                                        </div>

                                        <div class="collapse mobileItem" id="mobile" data-bs-parent="#titletype">

                                            <div>
                                                <div class="checkbox-list border-bottom pt-3 pb-2">
                                                    <input type="checkbox" class="form-check-input" name="section_check[]" id="mobile-section" value="1" <?php echo (in_array('1', $selectedSectionArr) && in_array('1', $checkSectionVersion)) ? 'checked="checked"' : ''; ?>>
                                                    <label class="medium" for="revenue_center"><?php echo showOtherLangText('Mobile Section'); ?></label>
                                                </div>
                                                <div class="py-4">
                                                    <div class="row pb-2">
                                                        <div class="col-md-2 col-4"></div>
                                                        <div class="col-md-10 col-8">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <div class="runningtask-radio">
                                                                    <input type="checkbox" class="form-check-input" id="checkallMobileSection" name="check_all" checked="">
                                                                    <label class="fs-13 semibold"><?php echo showOtherLangText('Check All'); ?></label>
                                                                </div>
                                                                <div class="runningtask-radio">
                                                                    <input type="checkbox" class="form-check-input" id="uncheckallMobileSection" name="uncheck_all">
                                                                    <label class="fs-13 semibold"><?php echo showOtherLangText('Uncheck All'); ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php
                                                    foreach ($mobileSectionArr as $mobileSectionSubRow) {
                                                        $sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = '" . $mobileSectionSubRow['name'] . "' AND designation_id = '" . $_GET['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
                                                        $accessMobileSectionRes = mysqli_query($con, $sql);
                                                        $AccessMobileSectionRow = mysqli_fetch_array($accessMobileSectionRes);
                                                    ?>
                                                        <div class="row align-items-center pb-2">
                                                            <div class="col-md-2 col-4">
                                                                <p class="semibold fs-13"><?php echo showOtherLangText($mobileSectionSubRow['title']) ?>:</p>
                                                            </div>
                                                            <div class="col-md-10 col-8">

                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div class="stock-radio enableMobileSection">
                                                                        <input type="radio" name="<?php echo $mobileSectionSubRow['name'] ?>" class="mobile-section-enable form-check-input" value="1" <?php echo $AccessMobileSectionRow['type_id'] == 1 ? 'checked="checked"' : ''; ?>>
                                                                        <label class="fs-13"><?php echo showOtherLangText('Enable'); ?></label>
                                                                    </div>
                                                                    <div class="stock-radio desableMobileSection">
                                                                        <input type="radio" name="<?php echo $mobileSectionSubRow['name'] ?>" class="mobile-section-disable form-check-input" <?php echo $AccessMobileSectionRow['type_id'] == 0 ? 'checked="checked"' : ''; ?> value="0">
                                                                        <label class="fs-13"><?php echo showOtherLangText('Disable'); ?></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>

                                                </div>

                                            </div>


                                        </div>


                                    </div>
                                </div>

                            </div>
                    </form>
                </section>

            </div>
        </div>
    </div>

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
</body>

<?php
include('title_js_code.php');
?>

</html>