<?php include('inc/dbConfig.php'); //connection details
//test

if (!isset($_SESSION['adminidusername'])) {
    echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'account_setup' AND designation_Section_permission_id = '8' AND designation_id = '" . $_SESSION['designation_id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if (!$permissionRow) {
    echo "<script>window.location='index.php'</script>";
    exit;
}

$selQry = " SELECT * FROM tbl_client WHERE id = '" . $_SESSION['accountId'] . "' ";
$resResult = mysqli_query($con, $selQry);
$resRow = mysqli_fetch_array($resResult);


if (isset($_POST['submitBtn'])) {

    $picField = '';
    if ($_FILES["logo"]["name"] != '') {
        $target_dir = dirname(__FILE__) . "/uploads/" . $accountImgPath . '/clientLogo/';
        $fileName = time() . basename($_FILES["logo"]["name"]);
        $target_file = $target_dir . $fileName;

        move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file);

        $picField = " , logo = '" . $fileName . "' ";

        if ($resRow['logo'] != '' && file_exists($target_dir . $resRow['logo'])) {
            @unlink($target_dir . $resRow['logo']);
        }
    }

    $sql = "UPDATE `tbl_client` SET
`accountName` = '" . $_POST['accountName'] . "',
`name` = '" . $_POST['ownerName'] . "',
`address_one` = '" . $_POST['address1'] . "',
`address_two` = '" . $_POST['address2'] . "',
`city` = '" . $_POST['city'] . "',
`country` = '" . $_POST['country'] . "',
`zipCode` = '" . $_POST['zipCode'] . "',
`email` = '" . $_POST['email'] . "',
`phone` = '" . $_POST['phone'] . "',
`taxId` = '" . $_POST['taxId'] . "',
`created_date` = '" . date('Y-m-d H:i:s') . "'	
" . $picField . "

WHERE id='" . $_SESSION['accountId'] . "' ";

    mysqli_query($con, $sql);

    echo "<script>window.location='accountSetup.php?updated=1'</script>";
}

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Account setup - Queue1</title>
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
        @media screen and (max-width: 767px) {
            html[dir=rtl] .acntLg-Upld {
                padding-right: 10px;
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
                            <h1 class="h1"><?php echo showOtherLangText('Account setup'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Account Name'); ?></h1>
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
                    <h6 style="color:#038B00"><?php

                                                echo isset($_GET['updated']) ? ' ' . showOtherLangText('Account Updated Successfully') . ' ' : '';

                                                ?></h6>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="container">
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
                                        <button type="submit" name="submitBtn" class="btn btn-primary mb-usrBkbtn"><span
                                                class="mb-UsrBtn"><i class="fa-solid fa-plus"></i></span>
                                            <span class="dsktp-Btn"><?php echo showOtherLangText('Save'); ?></span></button>

                                    </div>
                                </div>
                            </div>

                            <div class="acntStp">
                                <div class="addUser-Form acntSetup-Form row ac-setup">
                                    <div class="acnt-Div">
                                        <div class="row align-items-center acntStp-Row">
                                            <div class="col-md-3">
                                                <label for="accountName" class="form-label"><?php echo showOtherLangText('Account Name'); ?><span class="requiredsign">*</span></label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="accountName" id="accountName" value="<?php echo $resRow['accountName']; ?>"
                                                    autocomplete="off"
                                                    placeholder="Our Zanz" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"  onChange="this.setCustomValidity('')" required>
                                            </div>
                                        </div>

                                        <div class="row align-items-center acntStp-Row">
                                            <div class="col-md-3">
                                                <label for="ownerName" class="form-label"><?php echo showOtherLangText('Owner Name'); ?><span class="requiredsign">*</span></label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" value="<?php echo $resRow['name']; ?>" name="ownerName"
                                                    class="form-control" id="ownerName" placeholder="Queue" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"  onChange="this.setCustomValidity('')" required>
                                            </div>
                                        </div>

                                        <div class="row align-items-center acntStp-Row">
                                            <div class="col-md-3">
                                                <label for="streetAdd-1" class="form-label"><?php echo showOtherLangText('Street address 1'); ?><span class="requiredsign">*</span></label>
                                            </div>
                                            <div class="col-md-9">
                                                <textarea class="form-control" id="address1" name="address1" value=""
                                                    placeholder="P.o Box 4146" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"  onChange="this.setCustomValidity('')" required><?php echo $resRow['address_one']; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="row align-items-center acntStp-Row">
                                            <div class="col-md-3">
                                                <label for="streetAdd-2" class="form-label"><?php echo showOtherLangText('Street address 2'); ?></label>
                                            </div>
                                            <div class="col-md-9">
                                                <textarea class="form-control" id="address2" name="address2"
                                                    placeholder="Jambiani" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"><?php echo $resRow['address_two']; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="row align-items-center acntStp-Row">
                                            <div class="col-md-3">
                                                <label for="inputZip" class="form-label"><?php echo showOtherLangText('Zip'); ?></label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" value="<?php echo $resRow['zipCode']; ?>" class="form-control" name="zipCode"
                                                    id="zipCode" placeholder="4146" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')">
                                            </div>
                                        </div>

                                        <div class="row align-items-center acntStp-Row">
                                            <div class="col-md-3">
                                                <label for="inputCity" class="form-label"><?php echo showOtherLangText('City'); ?><span class="requiredsign">*</span></label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" value="<?php echo $resRow['city']; ?>" name="city" id="city"
                                                    placeholder="Zanzibar" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"  onChange="this.setCustomValidity('')" required>
                                            </div>
                                        </div>

                                        <div class="row align-items-center acntStp-Row">
                                            <div class="col-md-3">
                                                <label for="selectCountry" class="form-label"><?php echo showOtherLangText('Country'); ?><span class="requiredsign">*</span></label>
                                            </div>
                                            <div class="col-md-9">
                                                <select name="country" id="country" class="form-control"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')"
                                                    onchange="this.setCustomValidity('')" required>
                                                    <option value=""><?php echo showOtherLangText('Select'); ?>
                                                    </option>
                                                    <?php
                                                    $contryQry = " SELECT * FROM tbl_country ";
                                                    $contryRes = mysqli_query($con, $contryQry);
                                                    while ($contryResRow = mysqli_fetch_array($contryRes)) {
                                                        $sel = $resRow['country'] == $contryResRow['id'] ? 'selected = "selected"' : ''; ?>


                                                        <option value="<?php echo $contryResRow['id']; ?>"
                                                            <?php echo $sel; ?>><?php echo $contryResRow['name']; ?>
                                                        </option>

                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row align-items-center acntStp-Row">
                                            <div class="col-md-3">
                                                <label for="email" class="form-label"><?php echo showOtherLangText('Email'); ?><span class="requiredsign">*</span></label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="email" class="form-control" name="email" id="email" value="<?php echo $resRow['email']; ?>"
                                                    placeholder="inventory@our-zanzibar.com" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"  onChange="this.setCustomValidity('')" required>
                                            </div>
                                        </div>

                                        <div class="row align-items-center acntStp-Row">
                                            <div class="col-md-3">
                                                <label for="phone" class="form-label"><?php echo showOtherLangText('Phone'); ?></label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="tel" class="form-control" name="phone" id="phone" value="<?php echo $resRow['phone']; ?>"
                                                    placeholder="+255743419217" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"  onChange="this.setCustomValidity('')" required>
                                            </div>
                                        </div>

                                        <div class="row align-items-center acntStp-Row">
                                            <div class="col-md-3">
                                                <label for="taxId" class="form-label"><?php echo showOtherLangText('Tax Id'); ?></label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="taxId" id="taxId" value="<?php echo $resRow['taxId']; ?>"
                                                    placeholder="1001/4567892" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"  onChange="this.setCustomValidity('')" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="acntLg-Upld">

                                        <div class="text-center">
                                            <div class="preview_img">
                                                <div class="main_img">
                                                    <picture>
                                                        <?php

                                                        if ($resRow['logo'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . '/clientLogo/' . $resRow['logo'])) {

                                                            echo '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/clientLogo/' . $resRow['logo'] . '" class="previewImg" width="100" height="100" style="object-fit: scale-down;">';
                                                        } else {
                                                            echo '<img src="' . $_POST['imgName'] . '" class="previewImg previewBlankImg" width="100" height="100" style="display:none;object-fit: scale-down;" >';
                                                        }
                                                        ?>
                                                    </picture>
                                                </div>
                                            </div>
                                            <div class="labelDiv">
                                                <label class="imgUploadCss" id="imgLabel" for="imgUpload"><?php echo showOtherLangText('Upload Logo') ?> <img
                                                        src="Assets/icons/Import.svg" alt="Import"
                                                        class="importBtn"></label>

                                                <input onchange="previewFile()" name="logo" class="imgHidden"
                                                    id="imgUpload" type="file">

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
    <?php require_once('footer.php'); ?>
    <script type="text/javascript" src="Assets/js/accountsetup.js"></script>
</body>

</html>
<script>
    function previewFile() {
        var preview = document.querySelector('.previewImg');
        var file = document.querySelector('input[type=file]').files[0];
        var reader = new FileReader();

        reader.onloadend = function() {
            $('.previewImg').show();
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
        }

    }

    function removeRow(id) {
        $('#' + id).remove();
    }
</script>