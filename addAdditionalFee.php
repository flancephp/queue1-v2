<?php include('inc/dbConfig.php'); //connection details


if (!isset($_SESSION['adminidusername'])) {
    echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'additional_fee' AND designation_Section_permission_id = '8' AND designation_id = '" . $_SESSION['designation_id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if (!$permissionRow) {
    echo "<script>window.location='index.php'</script>";
    exit;
}

if (isset($_POST['feeName'])) {

    $sql = " SELECT * FROM tbl_order_fee WHERE `feeName` = '" . $_POST['feeName'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
    $feeQrycheck = mysqli_query($con, $sql);
    $feeResCheck = mysqli_fetch_array($feeQrycheck);

    if ($feeResCheck) {
        $error = 'This fee already exist.';
    } else {


        if ($_POST['isPopup'] != '') {
            $showHideInList = isset($_POST['visibility']) ? 1 : 0;
        } else {

            $showHideInList = 1;
        }


        $sql = "INSERT INTO `tbl_order_fee` SET 
			`feeName` = '" . $_POST['feeName'] . "',
			`feeType` = '" . $_POST['feeType'] . "',
			`amt` = '" . $_POST['amt'] . "',
			`visibility` = '" . $showHideInList . "',
			`account_id` = '" . $_SESSION['accountId'] . "' 
			";

        mysqli_query($con, $sql);

        $chargeId = mysqli_insert_id($con);

        if (!empty($_POST['parentPage'])) {
            $redPage = $_POST['parentPage'] . '.php';
            if ($_POST['parentPage'] ==  'addOrder') {
                $_SESSION['itemChargesOrd'][3][$chargeId] = $chargeId;
            } elseif ($_POST['parentPage'] ==  'editOrder') {
                editCustomCharge($_POST['orderId'], 3, $chargeId);
                $redPage .= '?orderId=' . $_POST['orderId'];
            } elseif ($_POST['parentPage'] ==  'addRecusation') {
                $_SESSION['itemChargesReq'][3][$chargeId] = $chargeId;
            } elseif ($_POST['parentPage'] ==  'editRecusation') {
                editCustomCharge($_POST['orderId'], 3, $chargeId);
                $redPage .= '?orderId=' . $_POST['orderId'];
            } elseif ($_POST['parentPage'] ==  'supplierPaymentDetail') {
                editCustomCharge($_POST['orderId'], 3, $chargeId);
                $redPage .= '?orderId=' . $_POST['orderId'] . '&supplierInvoice=' . $_REQUEST['supplierInvoice'] . '&supplierName=' . $_REQUEST['supplierName'] . '&supplierAddress=' . $_REQUEST['supplierAddress'] . '&supplierContact=' . $_REQUEST['supplierContact'];
            } elseif ($_POST['parentPage'] ==  'requisitionPaymentDetail') {
                editCustomCharge($_POST['orderId'], 3, $chargeId);
                $redPage .= '?orderId=' . $_POST['orderId'] . '&invoiceNumber=' . $_REQUEST['invoiceNumber'] . '&invoiceName=' . $_REQUEST['invoiceName'] . '&invoiceAddress=' . $_REQUEST['invoiceAddress'] . '&invoiceContact=' . $_REQUEST['invoiceContact'];
            }
            echo "<script language='javascript'>";
            echo ("window.opener.location.href = '" . $redPage . "';");
            echo ("window.close();");
            echo "</script>";
            die;
        }
?>
        <script>
            window.location = 'manageAdditionalFee.php?added=1';
        </script>
<?php
    }
}


?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Add Additional Fee - Queue1</title>
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
                            <h1 class="h1"><?php echo showOtherLangText('Add Additional Fee'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Add Additional Fee'); ?></h1>
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
                        <?php if (isset($error)) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo isset($error) ? ' ' . $error . ' ' : ''; ?>
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        <?php } ?>
                        <form role="form" action="" method="post" enctype="multipart/form-data" class="addUser-Form acntSetup-Form">
                            <input type="hidden" name="orderId" value="<?php echo $_GET['orderId']; ?>" />

                            <input type="hidden" name="parentPage" value="<?php echo $_GET['parentPage']; ?>" />
                            <div class="usrBtns d-flex align-items-center justify-content-between">
                                <div class="usrBk-Btn">
                                    <div class="btnBg">
                                        <a href="manageAdditionalFee.php" class="btn btn-primary mb-usrBkbtn"><span
                                                class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span
                                                class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                    </div>
                                </div>
                                <div class="usrAd-Btn">
                                    <div class="btnBg">
                                        <button type="submit" class="btn btn-primary mb-usrBkbtn"><span
                                                class="mb-UsrBtn"><i class="fa-regular fa-floppy-disk"></i></span> <span
                                                class="dsktp-Btn "><?php echo showOtherLangText('Save'); ?></span></button>
                                    </div>
                                </div>
                            </div>

                            <div class="edtSup-Div">


                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="feeName" class="form-label"><?php echo showOtherLangText('Fee Name'); ?></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" class="form-control" name="feeName" id="feeName"
                                            value="<?php echo isset($_POST['feeName']) ? $_POST['feeName'] : ''; ?>" placeholder="<?php echo showOtherLangText('Ace Transport charge'); ?>">
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="feeType" class="form-label"><?php echo showOtherLangText('Fee Type'); ?></label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-select" name="feeType" id="feeType" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')" onChange="this.setCustomValidity('')" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')">
                                            <option value="2"><?php echo showOtherLangText('Fixed Fee'); ?>
                                            </option>
                                            <option value="3"><?php echo showOtherLangText('Percentage Fee'); ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="feePercentage" class="form-label"><?php echo showOtherLangText('Amount') . ' ' . $getDefCurDet['curCode']; ?></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" class="form-control" name="amt" value="<?php echo isset($_POST['amt']) ? $_POST['amt'] : ''; ?>" id="amt" placeholder="<?php echo showOtherLangText('10'); ?>">
                                    </div>
                                </div>
                                <?php
                                if (isset($_GET['isPopup'])) {
                                ?>
                                    <div class="row align-items-center acntStp-Row">
                                        <div class="col-md-12">
                                            <input type="checkbox" name="taxfee" id="taxfee" class="form-check-input">
                                            <label for="taxfee" class="form-label"><?php echo showOtherLangText('Tax fee'); ?></label>
                                        </div>
                                    </div>
                                <?php
                                } else {
                                ?>
                                    <div class="row align-items-center acntStp-Row">
                                        <div class="col-md-12">
                                            <input type="checkbox" name="taxfee" id="taxfee" class="form-check-input">
                                            <label for="taxfee" class="form-label"><?php echo showOtherLangText('Tax fee'); ?></label>
                                        </div>
                                    </div>
                                <?php } ?>
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