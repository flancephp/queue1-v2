<?php include('inc/dbConfig.php'); //connection details

if (!isset($_SESSION['adminidusername'])) {
    echo "<script>window.location='login.php'</script>";
}


include_once('script/addDesignation.php');

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

//check page permission
$accessInvoicePermission = get_access_invoice_permission($_SESSION['designation_id'], $_SESSION['accountId']);

$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'designation' AND designation_Section_permission_id = '8' AND designation_id = '" . $_SESSION['designation_id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if (!$permissionRow) {
    echo "<script>window.location='index.php'</script>";
    exit;
}


?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Add Title - Queue1</title>
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
                            <h1 class="h1"><?php echo showOtherLangText('Add Title'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Add Title'); ?></h1>
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
                    <form name="frm" id="frm" method="post" action="" class="container pb-5">
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
                                    <p><?php echo showOtherLangText('This title already exists.'); ?></p>
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
                                    <div class="btnBg">
                                        <button type="submit" class="btn btn-primary mb-usrBkbtn"><span
                                                class="mb-UsrBtn"><i class="fa-regular fa-floppy-disk"></i></span> <span
                                                class="dsktp-Btn"><?php echo showOtherLangText('Save'); ?></span></button>
                                    </div>
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
                                            <input type="text" name="designation_name" class="form-control" value=""

                                                onchange="this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" required />
                                        </div>
                                    </div>


                                    <div id="titletype">

                                        <div class="row align-items-center acntStp-Row w-50">
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
                                                    <input type="checkbox" name="section_check[]" class="form-check-input" id="new_order" value="1" data-bs-toggle="collapse" data-bs-target="#newOrder">
                                                    <label class="medium" for="new_order"><?php echo showOtherLangText('New Order') ?></label>
                                                </div>

                                                <div class="collapse py-4" id="newOrder">
                                                    <p class="supplier-text bold pb-2"><?php echo showOtherLangText('Supplier'); ?></p>
                                                    <div class="supplier-text pb-3">
                                                        <input type="checkbox" class="supplierCheckall form-check-input" class="form-check-input" id="supplierCheckall">
                                                        <label for="suppliercheckall" class="fs-13 semibold"><?php echo showOtherLangText('Check all') ?></label>
                                                    </div>


                                                    <div class="row title-listing-item">
                                                        <?php
                                                        while ($supplierRow = mysqli_fetch_array($supplierRes)) {
                                                        ?>
                                                            <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                                <div class="supplier-inner-listbox">
                                                                    <input type="checkbox" name="new_supplierCheck[]"
                                                                        class="supplierCheckbox  form-check-input" value="<?php echo $supplierRow['id'] ?>">
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
                                                    ?>
                                                        <div class="row">
                                                            <div class="col-md-2 col-4">
                                                                <p class="semibold fs-13"><?php echo showOtherLangText($orderArrSubRow['title']); ?>:</p>
                                                            </div>
                                                            <div class="col-md-10 col-8">

                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div class="order-radio enableOrder">
                                                                        <input type="radio" name="<?php echo $orderArrSubRow['name']; ?>"
                                                                            class="form-check-input" value="1" checked>
                                                                        <label class="fs-13"><?php echo showOtherLangText('Enable'); ?></label>
                                                                    </div>
                                                                    <div class="order-radio desableOrder">
                                                                        <input type="radio" name="<?php echo $orderArrSubRow['name']; ?>"
                                                                            class="form-check-input" value="0">
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
                                                    <input type="checkbox" name="section_check[]" class="form-check-input" id="new-requisition-section" value="2" data-bs-toggle="collapse" data-bs-target="#newRequisition">
                                                    <label class="medium" for="new-requisition-section"><?php echo showOtherLangText('New Requisition'); ?></label>
                                                </div>

                                                <div class="collapse py-4" id="newRequisition">

                                                    <p class="bold pb-2"><?php echo showOtherLangText('Members'); ?></p>


                                                    <div class="supplier-text pb-3">
                                                        <input type="checkbox" class="form-check-input" id="memberCheckall">
                                                        <label for="memberall" class="fs-13 semibold"><?php echo showOtherLangText('Check All'); ?></label>
                                                    </div>

                                                    <div class="row  title-listing-item">
                                                        <?php
                                                        while ($deptUserRow = mysqli_fetch_array($deptUserRes)) {
                                                        ?>
                                                            <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6 ">
                                                                <div class="member-inner-listbox">
                                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="<?php echo $deptUserRow['id'] ?>">
                                                                    <label><?php echo $deptUserRow['name'] ?></label>
                                                                </div>
                                                            </div>
                                                        <?php
                                                        }

                                                        ?>



                                                    </div>


                                                    <div class="row pb-2  py-3 mt-2">
                                                        <div class="col-md-2 col-4"></div>
                                                        <div class="col-md-10 col-8">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <div>
                                                                    <input type="checkbox" class="form-check-input" id="checkallRequisition" name="check_all" checked>
                                                                    <label class="fs-13 semibold"><?php echo showOtherLangText('Check All'); ?></label>
                                                                </div>
                                                                <div>
                                                                    <input type="checkbox" class="form-check-input" id="uncheckallRequisition" name="uncheck_all">
                                                                    <label class="fs-13 semibold"><?php echo showOtherLangText('Uncheck all'); ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    foreach ($requisitionArr as $requisitionArrSubRow) {
                                                    ?>
                                                        <div class="row align-items-center pb-2">
                                                            <div class="col-md-2 col-4">
                                                                <p class="semibold fs-13"><?php echo showOtherLangText($requisitionArrSubRow['title']); ?>:</p>
                                                            </div>
                                                            <div class="col-md-10 col-8">

                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div class="requisition-radio enableRequisition">
                                                                        <input type="radio" name="<?php echo $requisitionArrSubRow['name']; ?>" class="form-check-input" value="1" checked>
                                                                        <label class="fs-13"><?php echo showOtherLangText('Enable'); ?></label>
                                                                    </div>
                                                                    <div class="requisition-radio desableRequisition">
                                                                        <input type="radio" name="<?php echo $requisitionArrSubRow['name']; ?>" class="form-check-input" value="0">
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
                                                    <input type="checkbox" name="section_check[]" class="form-check-input" id="runningTask-section" value="3" data-bs-toggle="collapse" data-bs-target="#runningtasks">
                                                    <label class="medium" for="running_tasks"><?php echo showOtherLangText('Running Tasks'); ?></label>
                                                </div>
                                                <div class="collapse py-4" id="runningtasks">


                                                    <div class="row pb-2">
                                                        <div class="col-md-2 col-4"></div>
                                                        <div class="col-md-10 col-8">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <div>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        id="checkallRunningtask" name="check_all" checked>
                                                                    <label class="fs-13 semibold"><?php echo showOtherLangText('Check All'); ?></label>
                                                                </div>
                                                                <div>
                                                                    <input type="checkbox" class="form-check-input" id="uncheckallRunningtask" name="uncheck_all">
                                                                    <label class="fs-13 semibold"><?php echo showOtherLangText('Uncheck All'); ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    foreach ($runningTaskArr as $runningTaskSubRow) {
                                                    ?>

                                                        <div class="row align-items-center pb-2">
                                                            <div class="col-md-2 col-4">
                                                                <p class="semibold fs-13"><?php echo showOtherLangText($runningTaskSubRow['title']); ?>:</p>
                                                            </div>
                                                            <div class="col-md-10 col-8">

                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div class="runningtask-radio enableRunningtask">
                                                                        <input type="radio" name="<?php echo $runningTaskSubRow['name']; ?>" class="form-check-input" value="1" checked>
                                                                        <label class="fs-13"><?php echo showOtherLangText('Enable'); ?></label>
                                                                    </div>
                                                                    <div class="runningtask-radio desableRunningtask">
                                                                        <input type="radio" name="<?php echo $runningTaskSubRow['name']; ?>" class="form-check-input" value="0">
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
                                                    <input type="checkbox" class="form-check-input" id="t_history" name="section_check[]" value="4" data-bs-toggle="collapse" data-bs-target="#history">
                                                    <label class="medium" for="t_history"><?php echo showOtherLangText('History'); ?></label>
                                                </div>
                                                <div class="collapse py-4" id="history">


                                                    <div class="row pb-2">
                                                        <div class="col-md-2 col-4"></div>
                                                        <div class="col-md-10 col-8">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <div>
                                                                    <input type="checkbox" class="form-check-input" id="checkallHistory" name="check_all" checked>
                                                                    <label class="fs-13 semibold"><?php echo showOtherLangText('Check All'); ?></label>
                                                                </div>
                                                                <div>
                                                                    <input type="checkbox" class="form-check-input" id="uncheckallHistory" name="uncheck_all">
                                                                    <label class="fs-13 semibold"><?php echo showOtherLangText('Uncheck All'); ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php

                                                    foreach ($historyArr as $historySubRow) {
                                                    ?>
                                                        <div class="row align-items-center pb-2">
                                                            <div class="col-md-2 col-4">
                                                                <p class="semibold fs-13"><?php echo showOtherLangText($historySubRow['title']); ?>:</p>
                                                            </div>
                                                            <div class="col-md-10 col-8">

                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div class="history-radio enableHistory">
                                                                        <input type="radio" name="<?php echo $historySubRow['name']; ?>" class="form-check-input history-enable" value="1" checked>
                                                                        <label class="fs-13"><?php echo showOtherLangText('Enable'); ?></label>
                                                                    </div>
                                                                    <div class="history-radio desableHistory">
                                                                        <input type="radio" name="<?php echo $historySubRow['name']; ?>" class="form-check-input history-disable" value="0">
                                                                        <label class="fs-13"><?php echo showOtherLangText('Disable'); ?></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>


                                                </div>



                                                <div>
                                                    <div class="checkbox-list border-bottom pt-3 pb-2">
                                                        <input type="checkbox" name="section_check[]" class="form-check-input" id="stock-section" value="5" data-bs-toggle="collapse" data-bs-target="#stock">
                                                        <label class="medium" for="stock1"><?php echo showOtherLangText('Stock'); ?></label>
                                                    </div>
                                                    <div class="collapse py-4" id="stock">

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
                                                                    <input type="checkbox" name="store_check[]" id="stock-section" class="stock-section form-check-input" value="<?php echo $storeRow['id'] ?>">
                                                                    <label><?php echo $storeRow['name']; ?></label>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>





                                                        </div>


                                                        <div class="row pb-2 mt-2  py-3 ">
                                                            <div class="col-md-2 col-4"></div>
                                                            <div class="col-md-10 col-8">
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div>
                                                                        <input type="checkbox" class="form-check-input" id="checkallStock" name="check_all" checked>
                                                                        <label class="fs-13 semibold"><?php echo showOtherLangText('Check All'); ?></label>
                                                                    </div>
                                                                    <div>
                                                                        <input type="checkbox" class="form-check-input" id="uncheckallStock" name="uncheck_all">
                                                                        <label class="fs-13 semibold"><?php echo showOtherLangText('Uncheck All'); ?></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php

                                                        foreach ($stockArr as $stockSubRow) {
                                                        ?>
                                                            <div class="row align-items-center pb-2">
                                                                <div class="col-md-2 col-4">
                                                                    <p class="semibold fs-13"><?php echo showOtherLangText($stockSubRow['title']) ?>:</p>
                                                                </div>
                                                                <div class="col-md-10 col-8">

                                                                    <div class="d-flex align-items-center gap-3">
                                                                        <div class="stock-radio enableStock">
                                                                            <input type="radio" name="<?php echo $stockSubRow['name'] ?>" class="form-check-input" value="1" checked>
                                                                            <label class="fs-13"><?php echo showOtherLangText('Enable'); ?></label>
                                                                        </div>
                                                                        <div class="stock-radio desableStock">
                                                                            <input type="radio" name="<?php echo $stockSubRow['name'] ?>" class="form-check-input" value="0">
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
                                                    <div class="checkbox-list border-bottom pt-3 pb-2" data-bs-toggle="collapse" data-bs-target="#revenuecenter">
                                                        <input type="checkbox" name="section_check[]" class="form-check-input" id="revenueCenter-section" value="7" data-bs-toggle="collapse" data-bs-target="#revenuecenter">
                                                        <label class="medium" for="revenue_center"><?php echo showOtherLangText('Revenue Center'); ?></label>
                                                    </div>
                                                    <div class="collapse py-4" id="revenuecenter">


                                                        <div class="row pb-2">
                                                            <div class="col-md-2 col-4"></div>
                                                            <div class="col-md-10 col-8">
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div>
                                                                        <input type="checkbox" class="form-check-input" id="checkallRevenueCenter" name="check_all" checked>
                                                                        <label class="fs-13 semibold"><?php echo showOtherLangText('Check All'); ?></label>
                                                                    </div>
                                                                    <div>
                                                                        <input type="checkbox" class="form-check-input" id="uncheckallRevenueCenter" name="uncheck_all">
                                                                        <label class="fs-13 semibold"><?php echo showOtherLangText('Uncheck All'); ?></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php

                                                        foreach ($revCenterArr as $RevCenterSubRow) {
                                                        ?>
                                                            <div class="row align-items-center pb-2">
                                                                <div class="col-md-2 col-4">
                                                                    <p class="semibold fs-13"><?php echo showOtherLangText($RevCenterSubRow['title']) ?>:</p>
                                                                </div>
                                                                <div class="col-md-10 col-8">

                                                                    <div class="d-flex align-items-center gap-3">
                                                                        <div class="revenueCenter-radio enableRevenueCenter">
                                                                            <input type="radio" name="<?php echo $RevCenterSubRow['name'] ?>" class="form-check-input" value="1" checked>
                                                                            <label class="fs-13"><?php echo showOtherLangText('Enable'); ?></label>
                                                                        </div>
                                                                        <div class="revenueCenter-radio desableRevenueCenter">
                                                                            <input type="radio" name="<?php echo $RevCenterSubRow['name'] ?>" class="form-check-input" value="0">
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
                                                        <input type="checkbox" name="section_check[]" class="form-check-input" id="setup1" value="8" data-bs-toggle="collapse" data-bs-target="#setup">
                                                        <label class="medium" for="setup1"><?php echo showOtherLangText('Setup'); ?></label>
                                                    </div>
                                                    <div class="collapse py-4" id="setup">


                                                        <div class="row pb-2">
                                                            <div class="col-md-2 col-4"></div>
                                                            <div class="col-md-10 col-8">
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div>
                                                                        <input type="checkbox" class="form-check-input" id="checkallSetup" name="check_all" checked>
                                                                        <label class="fs-13 semibold"><?php echo showOtherLangText('Check All'); ?></label>
                                                                    </div>
                                                                    <div>
                                                                        <input type="checkbox" class="form-check-input" id="uncheckallSetup" name="uncheck_all">
                                                                        <label class="fs-13 semibold"><?php echo showOtherLangText('Uncheck All'); ?></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php

                                                        foreach ($setupArr as $setupSubRow) {
                                                        ?>
                                                            <div class="row align-items-center pb-2">
                                                                <div class="col-md-2 col-4">
                                                                    <p class="semibold fs-13"> <?php echo showOtherLangText($setupSubRow['title']) ?>:</p>
                                                                </div>
                                                                <div class="col-md-10 col-8">

                                                                    <div class="d-flex align-items-center gap-3">
                                                                        <div class="stock-radio enableSetup">
                                                                            <input type="radio" name="<?php echo $setupSubRow['name'] ?>" class="form-check-input" value="1" checked>
                                                                            <label class="fs-13"><?php echo showOtherLangText('Enable'); ?></label>
                                                                        </div>
                                                                        <div class="stock-radio desableSetup">
                                                                            <input type="radio" name="<?php echo $setupSubRow['name'] ?>" class="form-check-input" value="0">
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

                                        <div class="collapse mobileItem" id="mobile" data-bs-parent="#titletype">

                                            <div>
                                                <div class="checkbox-list border-bottom pt-3 pb-2">
                                                    <input type="checkbox" class="form-check-input" name="section_check[]" id="mobile-section" value="1">
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
                                                    ?>
                                                        <div class="row align-items-center pb-2">
                                                            <div class="col-md-2 col-4">
                                                                <p class="semibold fs-13"><?php echo showOtherLangText($mobileSectionSubRow['title']) ?>:</p>
                                                            </div>
                                                            <div class="col-md-10 col-8">

                                                                <div class="d-flex align-items-center gap-3">
                                                                    <div class="stock-radio enableMobileSection">
                                                                        <input type="radio" name="<?php echo $mobileSectionSubRow['name'] ?>" class="form-check-input" value="1" checked="">
                                                                        <label class="fs-13"><?php echo showOtherLangText('Enable'); ?></label>
                                                                    </div>
                                                                    <div class="stock-radio desableMobileSection">
                                                                        <input type="radio" name="<?php echo $mobileSectionSubRow['name'] ?>" class="form-check-input" value="0">
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