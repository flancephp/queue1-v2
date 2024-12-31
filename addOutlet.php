<?php include('inc/dbConfig.php'); //connection details


if (!isset($_SESSION['adminidusername'])) {
    echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

//Access Invoice permission for user
$accessInvoicePermission = get_access_invoice_permission($_SESSION['designation_id'], $_SESSION['accountId']);

$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'outlet' AND designation_Section_permission_id = '8' AND designation_id = '" . $_SESSION['designation_id'] . "' AND account_id = '" . $_SESSION['accountId'] . "' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if (!$permissionRow) {
    echo "<script>window.location='index.php'</script>";
    exit;
}

if (isset($_POST['name']) && isset($_POST['deptId']) && $_POST['deptId'] > 0) {


    $checkQry = " SELECT * FROM tbl_deptusers WHERE name='" . $_POST['name'] . "' AND account_id='" . $_SESSION['accountId'] . "' ";
    $resultSet = mysqli_query($con, $checkQry);
    $resultRow = mysqli_num_rows($resultSet);

    if ($resultRow == 0) {

        $sql = "ALTER TABLE `tbl_deptusers` ADD `isAddressCheck` TINYINT(1) NOT NULL COMMENT '0=no, 1=yes' AFTER `newOldId`";

        mysqli_query($con, $sql);

        $sql = "INSERT INTO `tbl_deptusers` SET
		`deptId` = '" . $_POST['deptId'] . "',
		`name` = '" . $_POST['name'] . "',
		`receive_inv` = '" . $_POST['revInv'] . "',
		`email` = '" . $_POST['email'] . "',
		`address` = '" . $_POST['address'] . "',
		`phone` = '" . $_POST['phone'] . "', 
        `isAddressCheck` = '" . $_POST['addressCheck'] . "',
		`account_id` = '" . $_SESSION['accountId'] . "'  ";
        mysqli_query($con, $sql);
        $deptId = mysqli_insert_id($con);


        //Insert Department Details in designation sub section permission table
        $sql = "INSERT INTO `tbl_designation_sub_section_permission` 
        SET 
            `designation_id` = '" . $_SESSION['designation_id'] . "',
            `designation_Section_permission_id` = '2',
            `type` = 'member',
            `type_id` = '" . $deptId . "', 
            `account_id` = '" . $_SESSION['accountId'] . "' ";
        mysqli_query($con, $sql);
    } else {

        echo '<script>window.location="addOutlet.php?error=' . $_POST['name'] . '&deptId=' . $_POST['deptId'] . '"</script>';
    }



    if (isset($_POST['revCenter']) && $_POST['revCenter'] > 0 && isset($_POST['outLetType']) && $_POST['outLetType'] > 0) {

        $sql = "SELECT * FROM  `tbl_revenue_center_departments`  WHERE `deptId` = '" . $deptId . "' AND account_id = '" . $_SESSION['accountId'] . "' 	";
        $qry = mysqli_query($con, $sql);
        $revCen = mysqli_fetch_array($qry);
        if (!$revCen) {

            $sql = "INSERT INTO `tbl_revenue_center_departments` SET
			`revCenterId` = '" . $_POST['revCenter'] . "',
			`deptId` = '" . $deptId . "',
			`outLetType` = '" . $_POST['outLetType'] . "',
			`account_id` = '" . $_SESSION['accountId'] . "'  ";
            mysqli_query($con, $sql);

            $outLetId = mysqli_insert_id($con);
        }


        $sqlSet = " SELECT * FROM tbl_easymapping WHERE revId = '" . $_POST['revCenter'] . "' AND account_id='" . $_SESSION['accountId'] . "' ";
        $resultSet = mysqli_query($con, $sqlSet);
        $resultRowSet = mysqli_fetch_array($resultSet);

        $hotelId = $resultRowSet['hotelId'];
        $mapId = $resultRowSet['id'];

        $sql = "INSERT INTO `tbl_map_outlets` SET
		`hotelId` = '" . $hotelId . "',
		`mapId` = '" . $mapId . "',
		`revId` = '" . $_POST['revCenter'] . "',
		`outLetId` = '" . $outLetId . "',
		`account_id` = '" . $_SESSION['accountId'] . "'  ";
        mysqli_query($con, $sql);


        if (isset($_POST['catIds'])) {

            foreach ($_POST['catIds'] as $catId) {

                $sql = "INSERT INTO `tbl_map_outletcats` SET
				`revOutLetId` = '" . $outLetId . "',
				`revCatId` = '" . $catId . "',
				`account_id` = '" . $_SESSION['accountId'] . "'  ";
                mysqli_query($con, $sql);
            }
        }
    }

    echo '<script>window.location="manageOutlets.php?added=1&deptId=' . $_POST['deptId'] . '"</script>';
}


//$_POST['revenueCenterId'] comes by ajax when changes revenue center
if (isset($_POST['revenueCenterId']) && $_POST['revenueCenterId'] > 0) {

    $mainMapQry = " SELECT * FROM `tbl_map_category` WHERE revId = '" . $_POST['revenueCenterId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ";
    $mainMapRes = mysqli_query($con, $mainMapQry);

    $strcat = '';
    while ($catRes = mysqli_fetch_array($mainMapRes)) {

        $strcat .= "<input type='checkbox' name='catIds[]' value='" . $catRes['id'] . "' > " . $catRes['catName'] . " ";
    }
    echo $strcat;
    die;
}


//$_POST['revenueCenterId'] comes by ajax when changes revenue center
if (isset($_POST['revenueCenterAddress']) && $_POST['revenueCenterAddress'] > 0) {

    $revCenterQry = " SELECT * FROM `tbl_revenue_center` WHERE id = '" . $_POST['revenueCenterAddress'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ";
    $revCenterRes = mysqli_query($con, $revCenterQry);
    $revCenterRow = mysqli_fetch_array($revCenterRes);

    $email = $revCenterRow['email'];
    $address = $revCenterRow['address'];
    $phone = $revCenterRow['phone'];

    $responseArr = ['email' => $email, 'address' => $address, 'phone' => $phone];

    echo json_encode($responseArr);
    die;
}


$deptQry = "SELECT * FROM tbl_department WHERE account_id = '" . $_SESSION['accountId'] . "' ORDER BY name ";

$deptResult = mysqli_query($con, $deptQry);

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Add Outlet - Queue1</title>
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
            .chkOlt-Row .col-md-8.phone__number {
                width: 100%;
                order: 2;
                padding-right: .75rem;
            }

            .chkOlt-Row .col-md-4.phone__number {
                width: 100%;
                order: 1;
                padding-left: calc(var(--bs-gutter-x) * 0.5);
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
                            <h1 class="h1"><?php echo showOtherLangText('Add Outlet'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Add Outlet'); ?></h1>
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

                <section class="ordDetail userDetail itmMngDetail">
                    <div class="container">

                        <?php if (isset($_GET['error'])) { ?>

                            <div class="alert alert-danger alert-dismissible fade show lg__left__margin mb-0  mt-3" role="alert">
                                <p><?php echo showOtherLangText('Already exists, try another name.'); ?></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div><br />

                        <?php } ?>

                        <form role="form" action="" method="post" class="container">
                            <div class="row">
                                <div class="col-md-6 bkOutlet-Btn">
                                    <div class="btnBg">
                                        <a href="manageOutlets.php" class="btn btn-primary mb-usrBkbtn"><span
                                                class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span
                                                class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                    </div>
                                </div>
                                <div class="col-md-6 addOutlet-Btn">
                                    <div class="itmLnk-Row">
                                        <!-- <div class="btnBg">
                                        <a href="javascript:void(0)" class="btn btn-primary mb-usrBkbtn"><span
                                                class="mb-UsrBtn"><i class="fa-solid fa-plus"></i>
                                                <span class="nstdSpan">Item</span></span> <span class="dsktp-Btn">Add
                                                Item</span></a>
                                    </div> -->
                                        <div class="btnBg">
                                            <button type="submit" class="btn btn-primary mb-usrBkbtn"><span
                                                    class="mb-UsrBtn"><i class="fa-regular fa-floppy-disk"></i></span> <span
                                                    class="dsktp-Btn"><?php echo showOtherLangText('Save'); ?></span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>



                    <div class="container itmMng-Src outletFrm">
                        <div class="row">
                            <div class="col-md-12 oltCol-8">
                                <div class="acntStp">
                                    <div class="addUser-Form acntSetup-Form row">
                                        <div class="acnt-Div padRight40">


                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Name'); ?>:<span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="name" id="name" value="" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" onChange="this.setCustomValidity('')" required
                                                        placeholder="<?php echo showOtherLangText('Casa Kitchen'); ?>">
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Department" class="form-label"><?php echo showOtherLangText('Department'); ?>:<span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="cstmSelect">
                                                        <select name="deptId" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')" onChange="this.setCustomValidity('')" required id="deptId" class="form-select selectOption">
                                                            <option value=""><?php echo showOtherLangText('Select'); ?></option>
                                                            <?php
                                                            while ($deptRow = mysqli_fetch_array($deptResult)) {
                                                                echo '<option value="' . $deptRow['id'] . '">' . $deptRow['name'] . '</option>';
                                                            }
                                                            ?>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row chkOlt-Row">
                                                <div class="col-md-4">
                                                    <label for="receiveInvoice" class="form-label"><?php echo showOtherLangText('Receive Invoice'); ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input class="form-check-input" type="checkbox" id="revInv" name="revInv" value="">
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row chkOlt-Row">
                                                <div class="col-md-4">
                                                    <label for="setOutlet" class="form-label"><?php echo showOtherLangText('Set as Outlet'); ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="setOtlt">
                                                </div>
                                            </div>
                                            <div class="outletChk" style="display:none;">
                                                <div class="row align-items-center acntStp-Row">
                                                    <div class="col-md-4">
                                                        <label for="revenueCenter" class="form-label"><?php echo showOtherLangText('Revenue Center'); ?>:<span class="requiredsign">*</span></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="cstmSelect">
                                                            <?php
                                                            $revCenQry = "SELECT id, name FROM tbl_revenue_center WHERE account_id= '" . $_SESSION['accountId'] . "' ORDER BY name ";

                                                            $revCenResult = mysqli_query($con, $revCenQry);

                                                            ?>
                                                            <select name="revCenter" id="revCenter" class="form-select selectOption"
                                                                aria-label="Default select example" id="selectRvcntr" 
                                                                onchange="getrevCenter();" >

                                                                <option value=""><?php echo showOtherLangText('Select'); ?></option>

                                                                <?php
                                                                while ($revCenRow = mysqli_fetch_array($revCenResult)) {

                                                                    echo '<option value="' . $revCenRow['id'] . '">' . $revCenRow['name'] . '</option>';
                                                                }
                                                                ?>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center acntStp-Row">
                                                    <div class="col-md-4">
                                                        <label for="outletType" class="form-label"><?php echo showOtherLangText('Outlet Type'); ?>:<span class="requiredsign">*</span></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="cstmSelect">
                                                            <select name="outLetType" id="outLetType" class="form-select selectOption" 
                                                                onchange="getOutletType();">

                                                                <option value=""><?php echo showOtherLangText('Select'); ?></option>
                                                                <option value="1"><?php echo showOtherLangText('Sales'); ?></option>
                                                                <option value="2"><?php echo showOtherLangText('Cost'); ?></option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="acnt-Div">

                                            <div class="setupOutletsp1 padLeft40">

                                                <div class="row align-items-center acntStp-Row">
                                                    <div class="col-md-4 colDisable">&nbsp;</div>
                                                    <div class="col-8">
                                                        <input type="checkbox" id="addressCheck" class="form-check-input" name="addressCheck" value=""
                                                            onclick="showRevCenterAddress();">
                                                        <span style="padding-left:7px;"><label for="setOutlet" class="form-label"><?php echo showOtherLangText('Use Revenue Center Address'); ?></label></span>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center acntStp-Row">

                                                    <div class="col-md-4">
                                                        <label for="revenueCenter" class="form-label"><?php echo showOtherLangText('Address'); ?>:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="cstmSelect">
                                                            <textarea class="form-control" style="resize: vertical;" placeholder="<?php echo showOtherLangText('Main'); ?>" name="address" id="address" value="" cols="20" rows="2" autocomplete="off"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center acntStp-Row">
                                                    <div class="col-md-4">
                                                        <label for="outletType" class="form-label"><?php echo showOtherLangText('Email'); ?>:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="email" class="form-control" name="email" id="email"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                                <div class="row align-items-center acntStp-Row chkOlt-Row">
                                                    <div class="col-md-4 phone__number">
                                                        <label for="asgnEzcat" class="form-label"><?php echo showOtherLangText('Phone number'); ?>:</label>
                                                    </div>
                                                    <div class="col-md-8 phone__number">
                                                        <input type="text" class="form-control" name="phone" id="phone"
                                                            placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 oltCol-4"></div>
                        </div>
                    </div>

            </div>
            </form>
        </div>
        <!-- Item Table Body End -->


        </section>

    </div>
    </div>
    </div>

    <?php require_once('footer.php'); ?>
    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

    <div class="modal" tabindex="-1" id="note-popup" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php echo showOtherLangText('Please select Revenue Center first.') ?> </h1>
                </div>

                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="button" data-bs-dismiss="modal" class="btn sub-btn std-btn"><?php echo showOtherLangText('Ok'); ?></button>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>

</html>
<script>
    function showMsg() {

        $('#note-popup').modal('show');
    }
</script>

<script>
    function getOutletType() {

        var outLetType = $('#outLetType').val();
        if (outLetType == 1) {

            $('.AssignEzeeCategory').show();

        } else {

            $('.AssignEzeeCategory').hide();
        }

    }


    function getrevCenter() {

        var revenueCenterId = $('#revCenter').val();

        if (revenueCenterId != '') {

            $.ajax({
                    method: "POST",
                    //url: "addDepartmentUser.php",
                    data: {
                        revenueCenterId: revenueCenterId
                    }
                })
                .done(function(htmlRes) {
                    $('#showCatIds').html(htmlRes);
                });

        }


    }


    $("#revInv").change(function() {

        var revInv = $("#revInv").val();

        if (revInv == '' || revInv == '0') {

            $("#revInv").val(1);

        } else {

            $("#revInv").val(0);

        }

    })



    function showRevOutLetItems() {

        var dropContent = document.getElementById('dropContent').style.display;

        if (dropContent == '' || dropContent == 'none') {

            document.getElementById('dropContent').style.display = 'block';

        } else {

            document.getElementById('dropContent').style.display = 'none';

        }

    }


    function showRevCenterAddress() {

        var revenueCenterAddress = $('#revCenter').val();
        var addressCheck = $('#addressCheck').val();

        if (revenueCenterAddress == '') {
            // confirm('<?php echo showOtherLangText('Select revenue center first'); ?>');
            //function to show a dialog box
            showMsg();
            $('#addressCheck').prop('checked', false);
            return false;
        }

        if (revenueCenterAddress != '' && (addressCheck == '' || addressCheck == 0)) {

            $('#addressCheck').val(1);


            $.ajax({
                    method: "POST",
                    url: "addOutlet.php",
                    dataType: 'json',
                    data: {
                        revenueCenterAddress: revenueCenterAddress,
                        addressCheck: addressCheck
                    }
                })
                .done(function(responseObj) {
                    $('#email').val(responseObj.email);
                    $('#address').val(responseObj.address);
                    $('#phone').val(responseObj.phone);
                });

        } else {

            $('#addressCheck').val(0);
            var email = $('#email').val();
            var address = $('#address').val();
            var phone = $('#phone').val();

            $("#email").val('');
            $("#phone").val('');
            $("#address").val('');

        }

    }


    function openPopup(id = '') {

        var w = 700;
        var h = 550;
        var title = '<?php echo showOtherLangText('Convert Raw Item'); ?>';
        var url = 'addOutLetItems.php?outLetId=<?php echo $_GET['outLetId']; ?>';

        if (id != '') {
            var url = 'editOutLetItems.php?id=' + id + '&outLetId=<?php echo $_GET['outLetId']; ?>';
        }

        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 6);
        return window.open(url, title,
            'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' +
            w + ', height=' + h + ', top=' + top + ', left=' + left);
    }
</script>