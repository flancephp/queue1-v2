<?php include('inc/dbConfig.php'); //connection details

if (!isset($_SESSION['adminidusername']))
{
echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'supplier' AND type_id = '0' AND designation_id = '".$_SESSION['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if ($permissionRow)
{
echo "<script>window.location='index.php'</script>";
}

if( isset($_POST['name']) )
{
   
if( $_POST['id'] > 0 )
{

$sql = "SELECT * FROM tbl_suppliers WHERE name = '".trim($_POST['name'])."' AND id != '".$_POST['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";

$result = mysqli_query($con, $sql);

$res = mysqli_fetch_array($result);



if($res)
{

echo '<script>window.location="addEditSupplier.php?error=1&id='.$_POST['id'].'"</script>';
}


$sql = "UPDATE `tbl_suppliers` SET
`name` = '".$_POST['name']."',
`address` = '".$_POST['address']."',
`email` = '".$_POST['email']."',
`phone` = '".$_POST['phone']."'
WHERE id = '".$_POST['id']."'
AND account_id = '".$_SESSION['accountId']."'  ";

}
else
{

$sql = "SELECT * FROM tbl_suppliers WHERE name = '".trim($_POST['name'])."'  AND account_id = '".$_SESSION['accountId']."'  ";
$result = mysqli_query($con, $sql);
$res = mysqli_fetch_array($result);


if($res)
{

echo '<script>window.location="addEditSupplier.php?error=1&id='.$_POST['id'].'"</script>';
die;

}

$sql = "INSERT INTO `tbl_suppliers` SET 
        `name` = '".$_POST['name']."',
		`address` = '".$_POST['address']."',
		`email` = '".$_POST['email']."',
		`phone` = '".$_POST['phone']."', 
		`account_id` = '".$_SESSION['accountId']."' 	 ";
        mysqli_query($con, $sql);
        $supplierId = mysqli_insert_id($con);


//Insert supplier Details in designation sub section permission table
$sql = "INSERT INTO `tbl_designation_sub_section_permission` SET 
        `designation_id` = '".$_SESSION['designation_id']."',
        `designation_Section_permission_id` = '1',
        `type` = 'order_supplier',
        `type_id` = '".$supplierId."', 
        `account_id` = '".$_SESSION['accountId']."'      ";
mysqli_query($con, $sql);



echo '<script>window.location="manageSuppliers.php?added=1"</script>';
}

if (isset($_GET['id']))
{

$sql = "UPDATE `tbl_suppliers` SET

		`name` = '".$_POST['name']."',
	 	`address` = '".$_POST['address']."',
	 	`email` = '".$_POST['email']."',
	 	`phone` = '".$_POST['phone']."'
		WHERE id = '".$_GET['id']."'
		AND account_id = '".$_SESSION['accountId']."'  ";
		mysqli_query($con, $sql);
		
		
echo '<script>window.location="manageSuppliers.php?update='.$_POST['id'].'"</script>';


}


}



$sql = "SELECT * FROM tbl_suppliers WHERE id = '".$_GET['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";

$result = mysqli_query($con, $sql);

$res = mysqli_fetch_array($result);



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Add Supplier - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">

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
                            <h1 class="h1"><?php echo (isset($_GET['id']) && $_GET['id'] > 0) ? ' '.showOtherLangText('Edit').' ' : ' '.showOtherLangText('Add').' ';?>
                            <?php echo showOtherLangText('Supplier');?>
                            </h1>
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
                                    <h1 class="h1"><?php echo (isset($_GET['id']) && $_GET['id'] > 0) ? ' '.showOtherLangText('Edit').' ' : ' '.showOtherLangText('Add').' ';?> Supplier</h1>
                                </div>
                            </div>
                            <div class="user d-flex align-items-center">
                                <img src="Assets/images/user.png" alt="user">
                                <p class="body3 m-0 d-inline-block">User</p>
                            </div>
                            <div class="acc-info">
                                <img src="Assets/icons/Q.svg" alt="Logo" class="q-Logo">
                                <div class="dropdown d-flex">
                                    <a class="dropdown-toggle body3" data-bs-toggle="dropdown">
                                        <span> Account</span> <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 1</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 2</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 3</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 4</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="landScape">
                    <div class="container">
                        <h1 class="h1 text-center">For better experience, Please use portrait view.</h1>
                    </div>
                </section>

                <section class="ordDetail userDetail">
                <form class="addUser-Form acntSetup-Form" role="form" action="" method="post" class="container">
                <input type="hidden" name="id" value="<?php echo $res['id'];?>" />
                    <div class="container">
                    <?php if(isset($_GET['error'])) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo isset($_GET['error']) ? ' '.showOtherLangText('Supplier already exist').' ' : '';
 ?></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="manageSuppliers.php" class="sub-btn std-btn mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                </div>
                            </div>
                            <div class="usrAd-Btn">
                                <div class="btnBg">
                                    <button type="submit" class="btn sub-btn std-btn mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-regular fa-floppy-disk"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Save'); ?></span></button>
                                </div>
                            </div>
                        </div>

                        <div class="edtSup-Div">
                           <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="supplierName" class="form-label"><?php echo showOtherLangText('Supplier Name'); ?></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" value="<?php echo $res['name'];?>" id="name" name="name"
                                            placeholder="Logitech">
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="supplierAddress" class="form-label"><?php echo showOtherLangText('Address'); ?></label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea class="form-control" id="address" name="address"
                                            placeholder="DC Janakpuri"><?php echo $res['address'];?></textarea>
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="supplierEmail" class="form-label"><?php echo showOtherLangText('Email'); ?></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="email" value="<?php echo $res['email'];?>" class="form-control" id="email" name="email"
                                            placeholder="Logitech@gmail.com">
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="supplierPhone" class="form-label"><?php echo showOtherLangText('Phone'); ?></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="tel" value="<?php echo $res['phone'];?>" class="form-control" id="phone" name="phone"
                                            placeholder="+99994341000">
                                    </div>
                                </div>

                           
                        </div>
                    </div>
                    </form>
                </section>

            </div>
        </div>
    </div>

    <?php require_once('footer.php');?>
</body>

</html>