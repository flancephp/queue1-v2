<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

if(isset($_POST['decPlace']))
{
	 $sql = " UPDATE `tbl_currency` SET 
	`decPlace`='".$_POST['decPlace']."'
	WHERE is_default = '".$_GET['currencyType']."' AND account_id = '".$_SESSION['accountId']."' ";
	mysqli_query($con, $sql);
    
	echo "<script>window.location='manageCurrency.php?mainCurEdit=1'</script>";
}

$curQry = " SELECT * FROM tbl_currency WHERE is_default='".$_GET['currencyType']."' AND account_id = '".$_SESSION['accountId']."' ";
$curResult = mysqli_query($con,$curQry);
$curDetRow = mysqli_fetch_array($curResult);

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Edit Main Currency - Queue1</title>
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
            <?php require_once('nav.php');?>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('Edit Main Currency'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Edit Main Currency'); ?></h1>
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
                                        <label for="currency" class="form-label"><?php echo showOtherLangText('Main Currency') ?></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="currency" value="Dollar" disabled>
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="currencyCode" class="form-label"><?php echo showOtherLangText('Currency Code') ?></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="currencyCode" value="$" disabled>
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="noDecimal" class="form-label"><?php echo showOtherLangText('No. of Decimal Place') ?></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" required class="form-control" name="decPlace" value="<?php echo $curDetRow['decPlace'];?>" autocomplete="off" id="decPlace" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')" onchange="this.setCustomValidity('')" placeholder="4">
                                    </div>
                                </div>

                            </form>
                        </div>

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