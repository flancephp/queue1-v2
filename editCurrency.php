<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'currency' AND designation_Section_permission_id = '8' AND designation_id = '" . $_SESSION['designation_id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if (!$permissionRow) {
    echo "<script>window.location='index.php'</script>";
    exit;
}

if (isset($_POST['currency'])) {


    $sqlSet = " SELECT * FROM tbl_currency WHERE `currency`='" . $_POST['currency'] . "' AND id != '" . $_POST['id'] . "' AND account_id='" . $_SESSION['accountId'] . "' ";
    $resultSet = mysqli_query($con, $sqlSet);
    $resultSetRow = mysqli_num_rows($resultSet);

    if ($resultSetRow > 0) {

        $errorMes = ' ' . showOtherLangText('This currency already exists.') . ' ';
        echo "<script>window.location='editCurrency.php?errorMes=" . $errorMes . "&id=" . $_POST['id'] . "';</script>";
        exit;
    }

    $sql = "UPDATE `tbl_currency` SET 
	`currency` = '" . $_POST['currency'] . "',
	`amt` = '" . $_POST['amt'] . "',
	`curCode` = '" . $_POST['curCode'] . "',
	`decPlace` = '" . $_POST['decPlace'] . "'
	
	WHERE id='" . $_POST['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' 	";
    mysqli_query($con, $sql);

    echo "<script>window.location='manageCurrency.php?edit=1'</script>";
}

$res = mysqli_query($con, " select * from tbl_currency WHERE id='" . $_GET['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ");
$det = mysqli_fetch_array($res);
?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Edit Currency - Queue1</title>
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
                            <h1 class="h1"><?php echo showOtherLangText('Edit Currency'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Edit Currency'); ?></h1>
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

                    <?php if (isset($_GET['errorMes'])) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <p><?php
                                echo isset($_GET['errorMes']) ? ' ' . $_GET['errorMes'] . ' ' : '';
                                ?>
                            </p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>

                    <div class="container">
                        <form class="addUser-Form acntSetup-Form" role="form" action="" method="post" enctype="multipart/form-data">
                            <div class="usrBtns d-flex align-items-center justify-content-between">
                                <div class="usrBk-Btn">
                                    <div class="btnBg">
                                        <a href="manageCurrency.php" class="btn btn-primary mb-usrBkbtn"><span
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
                                        <label for="currency" class="form-label"><?php echo showOtherLangText('Currency') ?>:<span class="requiredsign">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
                                        <input type="text" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" onChange="this.setCustomValidity('')" required class="form-control" value="<?php echo $det['currency']; ?>" name="currency" id="currency" placeholder="EUR">
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="currencyCode" class="form-label"><?php echo showOtherLangText('Currency Code') ?>:<span class="requiredsign">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" onChange="this.setCustomValidity('')" required class="form-control" name="curCode" value="<?php echo $det['curCode']; ?>" id="currencyCode" placeholder="€">
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="amountAgainst" class="form-label"><?php echo showOtherLangText('Amount Against') . ' (' . $getDefCurDet['curCode'] . '1)'; ?>:<span class="requiredsign">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" onChange="this.setCustomValidity('')" required class="form-control" name="amt" value="<?php echo $det['amt']; ?>" id="amountAgainst" placeholder="0.89">
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="noDecimal" class="form-label"><?php echo showOtherLangText('No. of Decimal Place') ?>:<span class="requiredsign">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" onChange="this.setCustomValidity('')" required class="form-control" value="<?php echo $det['decPlace']; ?>" name="decPlace" id="decPlace" placeholder="4">
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