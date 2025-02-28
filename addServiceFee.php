<?php include('inc/dbConfig.php'); //connection details


if (!isset($_SESSION['adminidusername'])) {
    echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'service_item' AND designation_Section_permission_id = '8' AND designation_id = '" . $_SESSION['designation_id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if (!$permissionRow) {
    echo "<script>window.location='index.php'</script>";
    exit;
}

if (isset($_POST['itemName'])) {

    if ($_POST['isPopup'] != '') {
        $showHideInList = isset($_POST['visibility']) ? 1 : 0;
    } else {
        $showHideInList = 1;
    }

    $sql = " SELECT * FROM tbl_custom_items_fee WHERE `itemName` = '" . $_POST['itemName'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $feeQrycheck = mysqli_query($con, $sql);
    $feeResCheck = mysqli_fetch_array($feeQrycheck);

    if ($feeResCheck) {
        $error = 'This fee already exist.';
    } else {

        $sql = "INSERT INTO `tbl_custom_items_fee` SET 
			`itemName` = '" . $_POST['itemName'] . "',
			`unit` = '" . $_POST['unit'] . "',
			`amt` = '" . $_POST['amt'] . "',
			`visibility` = '" . $showHideInList . "',
			`account_id` = '" . $_SESSION['accountId'] . "' 
			";
        mysqli_query($con, $sql);
        $chargeId = mysqli_insert_id($con);


        if (!empty($_POST['parentPage'])) {
            $redPage = $_POST['parentPage'] . '.php';
            if ($_POST['parentPage'] ==  'addOrder') {
                $_SESSION['itemChargesOrd'][1][$chargeId] = $chargeId;
            } elseif ($_POST['parentPage'] ==  'editOrder') {
                editCustomCharge($_POST['orderId'], 1, $chargeId);
                $redPage .= '?orderId=' . $_POST['orderId'];
            } elseif ($_POST['parentPage'] ==  'addRecusation') {
                $_SESSION['itemChargesReq'][1][$chargeId] = $chargeId;
            } elseif ($_POST['parentPage'] ==  'editRecusation') {
                editCustomCharge($_POST['orderId'], 1, $chargeId);
                $redPage .= '?orderId=' . $_POST['orderId'];
            } elseif ($_POST['parentPage'] ==  'requisitionPaymentDetail') {
                editCustomCharge($_POST['orderId'], 1, $chargeId);
                $redPage .= '?orderId=' . $_POST['orderId'] . '&invoiceNumber=' . $_REQUEST['invoiceNumber'] . '&invoiceName=' . $_REQUEST['invoiceName'] . '&invoiceAddress=' . $_REQUEST['invoiceAddress'] . '&invoiceContact=' . $_REQUEST['invoiceContact'];
            } elseif ($_POST['parentPage'] ==  'supplierPaymentDetail') {
                editCustomCharge($_POST['orderId'], 1, $chargeId);
                $redPage .= '?orderId=' . $_POST['orderId'] . '&supplierInvoice=' . $_REQUEST['supplierInvoice'] . '&supplierName=' . $_REQUEST['supplierName'] . '&supplierAddress=' . $_REQUEST['supplierAddress'] . '&supplierContact=' . $_REQUEST['supplierContact'];
            }
            echo "<script language='javascript'>";
            echo ("window.opener.location.href = '" . $redPage . "';");
            echo ("window.close();");
            echo "</script>";

            die;
        }

        echo "<script>window.location ='manageServiceFee.php?added=1'</script>";
    } //end else here
}



?>

<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Add Service Fee - Queue1</title>
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
                            <h1 class="h1"><?php echo showOtherLangText('Add Service Fee'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Add Service Fee'); ?></h1>
                                </div>
                            </div>
                            <?php require_once('header.php'); ?>
                        </div>
                    </div>
                </section>

                <section id="landScape">
                    <div class="container">
                        <h1 class="h1 text-center"><?php echo showOtherLangText('For better experience, Please use portrait view.'); ?></h1>
                    </div>
                </section>

                <section class="ordDetail userDetail">



                    <?php

                    if (isset($error) && $error != '') {
                        echo '
                                                                         <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                                         <p>' . showOtherLangText($error) . '</p>
                                                                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                                         </div>
                                                                    ';
                    }

                    ?>


                    <div class="container">

                        <form role="form" action="" method="post" class="addUser-Form acntSetup-Form" enctype="multipart/form-data">
                            <div class="usrBtns d-flex align-items-center justify-content-between">
                                <div class="usrBk-Btn">
                                    <div class="btnBg">
                                        <a href="manageServiceFee.php" class="btn btn-primary mb-usrBkbtn"><span
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

                            <div class="edtSup-Div">


                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="feeName" class="form-label"><?php echo showOtherLangText('Fee Name'); ?>:<span class="requiredsign">*</span></label>
                                    </div>
                                    <div class="col-md-9">

                                        <input placeholder="<?php echo showOtherLangText('Ace Transport charge'); ?>" type="text"
                                            class="form-control" name="itemName" id="itemName"
                                            value="<?php echo isset($_POST['itemName']) ? $_POST['itemName'] : ''; ?>"
                                            autocomplete="off"

                                            onChange="this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" required />


                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="feeAmount" class="form-label"><?php echo showOtherLangText('Fee Amount'); ?> <?php echo $getDefCurDet['curCode']; ?>:<span class="requiredsign">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input placeholder="<?php echo showOtherLangText('20'); ?>" type="text" class="form-control" name="amt" id="amt"
                                            value="<?php echo isset($_POST['amt']) ? $_POST['amt'] : ''; ?>"
                                            autocomplete="off"

                                            onChange="this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" required />
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="unit" class="form-label"><?php echo showOtherLangText('Unit'); ?>:<span class="requiredsign">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input placeholder="<?php echo showOtherLangText('Trip'); ?>" type="text" class="form-control" name="unit" id="unit"
                                            autocomplete="off" onChange="this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" required />
                                    </div>
                                </div>


                            </div>
                        </form>
                    </div>
                </section>

            </div>
        </div>
    </div>

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
</body>

</html>