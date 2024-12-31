<?php
include('inc/dbConfig.php'); //connection details


if (!isset($_SESSION['adminidusername'])) {
    echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'account' AND designation_Section_permission_id = '8' AND designation_id = '" . $_SESSION['designation_id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if (!$permissionRow) {
    echo "<script>window.location='index.php'</script>";
    exit;
}

if (isset($_POST['accountName'])) {

    $error = '';
    if (isset($_POST['balanceAmt']) &&  isset($_POST['pass'])) {
        $query = "SELECT * FROM tbl_user WHERE id = '" . $_SESSION['id'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' AND password = '" . $_POST['pass'] . "' AND status = 1 ";
        $result = mysqli_query($con, $query);
        $res = mysqli_fetch_array($result);

        if (empty($res)) {
            $error = ' ' . showOtherLangText('Invalid Password') . ' ';
        }
    }

    if ($error == '') {

        $sql = "UPDATE  `tbl_accounts` SET 
		`accountName` = '" . $_POST['accountName'] . "',
		`accountNumber` = '" . $_POST['accountNumber'] . "',
		`currencyId` = '" . $_POST['currencyId'] . "',
		`balanceAmt` = '" . $_POST['balanceAmt'] . "'

		WHERE id = '" . $_POST['id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' 	";

        mysqli_query($con, $sql);

        echo "<script>window.location='manageAccounts.php?update=1'</script>";
    }
}

$res = mysqli_query($con, " select * from tbl_accounts WHERE id='" . $_GET['id'] . "' ");
$det = mysqli_fetch_array($res);
?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Edit Account - Queue1</title>
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
                            <h1 class="h1"><?php echo showOtherLangText('Edit Account'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Edit Account'); ?></h1>
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
                        <form role="form" class="addUser-Form acntSetup-Form" action="" method="post" enctype="multipart/form-data">
                            <div class="usrBtns d-flex align-items-center justify-content-between">
                                <div class="usrBk-Btn">
                                    <div class="btnBg">
                                        <a href="manageAccounts.php" class="btn btn-primary mb-usrBkbtn"><span
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
                                        <label for="accountName" class="form-label"><?php echo showOtherLangText('Account  Name') ?>:<span class="requiredsign">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="accountName" id="accountName"
                                            placeholder="Main Sale USD" value="<?php echo isset($det['accountName']) ? $det['accountName'] : ''; ?>"  oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" onChange="this.setCustomValidity('')" required >
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="accountNumber" class="form-label"><?php echo showOtherLangText('Account Number'); ?>:<span class="requiredsign">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text"  class="form-control" value="<?php echo isset($det['accountNumber']) ? $det['accountNumber'] : ''; ?>" name="accountNumber" id="accountNumber" placeholder="0003" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" onChange="this.setCustomValidity('')"  required >
                                    </div>
                                </div>


                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="accountCurrency" class="form-label"><?php echo showOtherLangText('Account Currency') ?>:<span class="requiredsign">*</span></label>
                                    </div>
                                    <div class="col-md-9">

                                        <?php
                                        $sqlSet = " SELECT * FROM tbl_currency WHERE account_id = '" . $_SESSION['accountId'] . "'  order by id  ";
                                        $resultSet = mysqli_query($con, $sqlSet);
                                        ?>
                                        <select  name="currencyId" id="currencyId" class="form-select" aria-label="Default select example" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')" onChange="this.setCustomValidity('')" required
                                            class="form-control">
                                            <option value=""><?php echo showOtherLangText('Select'); ?></option>
                                            <?php while ($cur = mysqli_fetch_array($resultSet)) {
                                                $sel = $det['currencyId'] == $cur['id'] ? 'selected="selected"' : '';
                                            ?>
                                                <option value="<?php echo $cur['id']; ?>" <?php echo $sel; ?>><?php echo $cur['currency']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="accountBalance" class="form-label"><?php echo showOtherLangText('Balance') ?>:<span class="requiredsign">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
                                        <input type="text" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" onChange="this.setCustomValidity('')" required value="<?php echo $det['balanceAmt']; ?>" class="form-control" name="balanceAmt" id="balanceAmt"
                                            placeholder="3220.7939">
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