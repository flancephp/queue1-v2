<?php include('inc/dbConfig.php'); //connection details

if (!isset($_SESSION['adminidusername'])) {
    echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'revenue_center' AND designation_Section_permission_id = '8' AND designation_id = '" . $_SESSION['designation_id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if (!$permissionRow) {
    echo "<script>window.location='index.php'</script>";
    exit;
}

if (isset($_POST['name'])) {

    $sqlSet = " SELECT * FROM tbl_revenue_center WHERE name='" . $_POST['name'] . "' AND account_id='" . $_SESSION['accountId'] . "' ";
    $resultSet = mysqli_query($con, $sqlSet);
    $resultSetRow = mysqli_num_rows($resultSet);

    if ($resultSetRow == 0) {

        $sql = "INSERT INTO `tbl_revenue_center` SET 
`name` = '" . $_POST['name'] . "',
`email` = '" . $_POST['email'] . "',
`address` = '" . $_POST['address'] . "',
`phone` = '" . $_POST['phone'] . "', 
`account_id` = '" . $_SESSION['accountId'] . "' 	";
        mysqli_query($con, $sql);

        $revCenterId = mysqli_insert_id($con);



        $sql = "INSERT INTO `tbl_easymapping` SET
`hotelId` = '" . $_POST['hotelId'] . "',
`revId` = '" . $revCenterId . "',
`account_id` = '" . $_SESSION['accountId'] . "'  ";
        mysqli_query($con, $sql);

        $mapId = mysqli_insert_id($con);



        if (!empty($_POST['catNames'])) {
            foreach ($_POST['catNames'] as $catName) {
                $selQry = "SELECT * FROM tbl_map_category WHERE catName = '" . $catName . "' AND mapId = '" . $mapId . "' AND revId = '" . $revCenterId . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
                $result = mysqli_query($con, $selQry);
                $resRow = mysqli_fetch_array($result);
                if ($resRow == 0) {

                    $sql = "INSERT INTO `tbl_map_category` SET
`hotelId` = '" . $_POST['hotelId'] . "',
`mapId` = '" . $mapId . "',
`revId` = '" . $revCenterId . "',
`catName` = '" . $catName . "',
`account_id` = '" . $_SESSION['accountId'] . "'  ";

                    mysqli_query($con, $sql);
                }
            }
        }

        echo '<script>window.location="revenueCenterSetup.php?added=1"</script>';
    } else {

        echo '<script>window.location="addRevenueCenter.php?error=1"</script>';
    }
}


?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Add Revenue Center - Queue1</title>
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
                            <h1 class="h1"><?php echo showOtherLangText('Add Revenue Center'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Add Revenue Center'); ?></h1>
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
                    <form class="container" id="frmEasy" name="frmEasy" action="" method="post" enctype="multipart/form-data">
                        <div class="container">

                            <?php if (isset($_GET['error'])) { ?>

                                <div class="alert alert-danger alert-dismissible fade show lg__left__margin mb-0  mt-3" role="alert">
                                    <p><?php echo showOtherLangText('This Revenue center already exists.'); ?></p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div><br />

                            <?php } ?>

                            <div class="usrBtns d-flex align-items-center justify-content-between">
                                <div class="usrBk-Btn">
                                    <div class="btnBg">
                                        <a href="revenueCenterSetup.php" class="btn btn-primary mb-usrBkbtn"><span
                                                class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span
                                                class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                    </div>
                                </div>
                                <div class="usrAd-Btn">
                                    <div class="btnBg">

                                        <button type="submit" class="btn btn-primary mb-usrBkbtn"><span class="mb-UsrBtn"><i
                                                    class="fa-solid fa-plus"></i></span> <span
                                                class="dsktp-Btn"><?php echo showOtherLangText('Save'); ?></span></a></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="acntStp">
                            <div class="addUser-Form acntSetup-Form edtRev-cntr row" id="frmEasy" name="frmEasy">
                                <div class="col-md-6">
                                    <div class="row align-items-center acntStp-Row">
                                        <div class="col-md-3">
                                            <label for="Name" class="form-label"><?php echo showOtherLangText('Name') ?>:<span class="requiredsign">*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" value="" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" onChange="this.setCustomValidity('')" required class="form-control" name="name" id="name" placeholder="<?php echo showOtherLangText('Casa') ?>">
                                        </div>
                                    </div>
                                    <div class="row align-items-center acntStp-Row">
                                        <div class="col-md-3">
                                            <label for="ezzeAddress" class="form-label"><?php
                                                                                        if (in_array($_SESSION['accountId'], $allowedRevenueCenterAccounts)) {

                                                                                            echo showOtherLangText('Assign Ezee Address') . ' :';
                                                                                        } ?></label>
                                        </div>
                                        <div class="col-md-9">
                                            <?php
                                            if ($_SESSION['accountId'] == 1 && in_array($_SESSION['accountId'], $allowedRevenueCenterAccounts)) { ?>

                                                <select name="hotelId" id="hotelId" class="form-select" aria-label="Default select example">
                                                    <option value=""><?php echo showOtherLangText('Select Hotel'); ?>
                                                    </option>
                                                    <option value="21866"
                                                        <?php echo $_GET['hotelId'] == 21866 ? 'selected="selected"' : ''; ?>>
                                                        <?php echo showOtherLangText('Fun Beach Hotel(21866)'); ?>
                                                    </option>
                                                    <option value="21930"
                                                        <?php echo $_GET['hotelId'] == 21930 ? 'selected="selected"' : ''; ?>>
                                                        <?php echo showOtherLangText('Casa Del Mar Hotel(21930)'); ?>
                                                    </option>
                                                </select>

                                            <?php }  ?>


                                            <?php
                                            if ($_SESSION['accountId'] == 3 && in_array($_SESSION['accountId'], $allowedRevenueCenterAccounts)) { ?>

                                                <select name="hotelId" id="hotelId" class="form-select" aria-label="Default select example">
                                                    <option value=""><?php echo showOtherLangText('Select Hotel'); ?>
                                                    </option>
                                                    <option value="29624"
                                                        <?php echo $_GET['hotelId'] == 29624 ? 'selected="selected"' : ''; ?>>
                                                        <?php echo showOtherLangText('Mnarani Hotel(29624)'); ?>
                                                    </option>

                                                </select>

                                            <?php }  ?>


                                        </div>
                                    </div>
                                    <div class="row align-items-start acntStp-Row">
                                        <div class="col-md-3">
                                            <label for="" class="form-label"><?php echo showOtherLangText('Set Ezee Category') ?></label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="setEze-Ctgry">
                                                <input type="text" class="form-control" name="catNames[]" id="" placeholder="<?php echo showOtherLangText('Hot Drinks') ?>">
                                                <a href="javascript:void(0)" class="stEze-Lnk"><i
                                                        class="fa-solid fa-minus"></i></a>
                                            </div>
                                            <div id="additionalContent">

                                            </div>
                                            <a href="javascript:void(0)" class="stEze-addLnk"><i
                                                    class="fa-solid fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row align-items-center acntStp-Row">
                                        <div class="col-md-3">
                                            <label for="address" class="form-label"><?php echo showOtherLangText('Address') ?>:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <textarea class="form-control" style="resize: vertical;" placeholder="<?php echo showOtherLangText('Main') ?>" name="address" id="address" value="" cols="20" rows="2" autocomplete="off"></textarea>
                                        </div>
                                    </div>
                                    <div class="row align-items-center acntStp-Row">
                                        <div class="col-md-3">
                                            <label for="email" class="form-label"><?php echo showOtherLangText('Email') ?>:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="email" class="form-control" name="email" id="email"
                                                placeholder="<?php echo showOtherLangText('casa@our-zanzibar.com') ?>">
                                        </div>
                                    </div>
                                    <div class="row align-items-center acntStp-Row">
                                        <div class="col-md-3">
                                            <label for="phone" class="form-label"><?php echo showOtherLangText('Phone number') ?>:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="tel" class="form-control" name="phone" id="phone" placeholder="<?php echo showOtherLangText('+99994341000') ?>">
                                        </div>
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

</html>
<?php $x = 0;
$errorMes = showOtherLangText('This field should not be left blank.'); ?>

<script>
    function removeRow(id) {
        $('#' + id).remove();
    }
    $(document).ready(function() {
        var x = <?php echo $x; ?>;
        $(".stEze-addLnk").on("click", function() {
            x++;
            var newContent = '<div id="' + x + '" class="setEze-Ctgry"><input type="text" id="tags' + x + '" name="catNames[]" class="form-control" id="" placeholder="<?php echo showOtherLangText('Hot Drinks') ?>">' +
                '<a href="javascript:void(0)" onclick="removeRow(' + x + ')" class="stEze-Lnk"><i class="fa-solid fa-minus"></i></a>' +
                '</div>';
            // Append the new content to the specified element
            $("#additionalContent").append(newContent);
        });



    });
</script>