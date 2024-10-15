<?php 

include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'item_manager' AND type_id = '0' AND designation_id = '".$_SESSION['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);

if ($permissionRow)
{
    echo "<script>window.location='index.php'</script>";
}

//Run ajax to check the barCode to be entered unique or not
if( isset($_POST['enteredBarCode']) )
{ 
    $sql = "SELECT * FROM tbl_products WHERE barCode = '".trim($_POST['enteredBarCode'])."'  AND account_id = '".$_SESSION['accountId']."'  ";
    $result = mysqli_query($con, $sql);
    $res = mysqli_fetch_array($result);

    echo $barCodeStatus = !empty($res) ? 1 : 0;
    die();
}


$msg = '';
if( isset($_POST['itemName']) )
{ 

    //check for barcode exist or not in our database
     $sql = "SELECT * FROM tbl_products WHERE barCode = '".trim($_POST['barCode'])."'  AND account_id = '".$_SESSION['accountId']."'  ";
    $result = mysqli_query($con, $sql);
    $res = mysqli_fetch_array($result);
    
    if ($res > 0) {
        $msg = ' '.showOtherLangText('Bar Code Already Exist').' ';
        //exit();
    }
    else
    {
        if ($_POST['maxLevel'] < $_POST['minLevel']) 
        {
            $msg1 = ' '.showOtherLangText('MaxLevel should be greater than minLevel.').' ';
        }
        else
        {
            $picField = '';
            if($_FILES["imgName"]["name"] != '')
            {
                $target_dir = dirname(__FILE__)."/uploads/".$accountImgPath.'/products/';
                $fileName = time(). basename($_FILES["imgName"]["name"]);
                $target_file = $target_dir . $fileName;

                move_uploaded_file($_FILES["imgName"]["tmp_name"], $target_file);

                $picField = " , imgName = '". $fileName."' ";


                resize_image($target_file, $target_file, 60,60);
            }

            $sql = "INSERT INTO `tbl_products` SET
            `storageDeptId` = '".$_POST['storageDeptId']."',
            `parentCatId` = '".$_POST['parentId']."',
            `catId` = '".$_POST['catId']."',
            `itemName` = '".addslashes($_POST['itemName'])."',
            `barCode` = '".$_POST['barCode']."',
            `unitP` = '".$_POST['unitP']."',
            `factor` = '".$_POST['factor']."',
            `unitC` = '".$_POST['unitC']."',
            `price` = '".$_POST['price']."',
            `minLevel` = '".$_POST['minLevel']."',
            `maxLevel` = '".$_POST['maxLevel']."',
            `status` = '".$_POST['status']."',
            `proType` = '".$_POST['proType']."',
            `rawProducts` = '".$_POST['rawProducts']."',
            `account_id` = '".$_SESSION['accountId']."' 

            ".$picField;
            mysqli_query($con, $sql);

            $productId = mysqli_insert_id($con);

            if (!empty($_POST['deptId'])) {
                foreach($_POST['deptId'] as $deptId)
                {
                    $sql = "INSERT INTO `tbl_productdepartments` SET
                    `productId` = '".$productId."',
                    `deptId` = '".$deptId."', 
                    `account_id` = '".$_SESSION['accountId']."'  ";
                    mysqli_query($con, $sql);
                }
            }

            if (!empty($_POST['supplierId'])) {

                foreach($_POST['supplierId'] as $supplierId)
                {
                    $sql = "INSERT INTO `tbl_productsuppliers` SET
                    `productId` = '".$productId."',
                    `supplierId` = '".$supplierId."',
                    `account_id` = '".$_SESSION['accountId']."'  ";
                    mysqli_query($con, $sql);
                }
            }


            if( isset($_POST['rawProducts']) && $_POST['rawProducts'] != '')
            {
                $rawProducts = $_POST['rawProducts'];
                if( !empty($rawProducts) )
                {
                    foreach($rawProducts as $proName)
                    {
                        if( !empty($proName) )
                        {
                            $proName = trim($proName);
                            $proNameFilter = explode('(', $proName);
                            $convtPid = trim( str_replace(')', '', $proNameFilter[1]) );
                            if($productId > 0 && $convtPid > 0)
                            {
                                $sql = "INSERT INTO `tbl_rawitem_products` SET
                                `rawPid` = '".$productId."',
                                `convtPid` = '".$convtPid."',
                                `account_id` = '".$_SESSION['accountId']."'  ";
                                mysqli_query($con, $sql);
                            }
                        }
                    }
                }

            }

            echo "<script>window.location='itemsManager.php?added=1'</script>";

        }
    }

}


?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Add Product - Queue1</title>
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
                            <h1 class="h1"><?php echo showOtherLangText('Add Product') ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Add Product') ?></h1>
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
                    <?php if(isset($_GET['added']) || isset($_GET['edit']) || isset($_GET['delete'])) {?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p>
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                            <?php if(isset($msg) && $msg!='') { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo $msg; ?></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                            <?php if(isset($msg1) && $msg1!='') { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo $msg1; ?></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                    <form role="form" action="" method="post" class="container" enctype="multipart/form-data">
                        <div class="row mb-3 mb-md-0">
                            <div class="col-md-6 bkOutlet-Btn">
                                <div class="btnBg">
                                    <a href="itemsManager.php" class="btn btn-primary"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                </div>
                            </div>
                            <div class="col-md-6 addOutlet-Btn">
                                <div class="itmLnk-Row">
                                    <!-- <div class="btnBg">
                                        <a href="javascript:void(0)" class="btn btn-primary"><span
                                                class="mb-UsrBtn"><i class="fa-solid fa-plus"></i>
                                                <span class="nstdSpan">Item</span></span> <span class="dsktp-Btn">Add
                                                Item</span></a>
                                    </div> -->
                                    <div class="btnBg">
                                        <button type="submit" class="btn btn-primary"><span
                                                class="mb-UsrBtn"><i class="fa-regular fa-floppy-disk"></i></span> <span
                                                class="dsktp-Btn"><?php echo showOtherLangText('Save'); ?></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container itmMng-Src outletFrm pb-5">
                    <?php if(isset($_GET['added']) || isset($_GET['edit']) || isset($_GET['delete'])) {?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p>
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                            <?php if(isset($msg) && $msg!='') { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo $msg; ?></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                        <div class="row">
                            <div class="col-md-12 oltCol-8">
                                <div class="">
                                    <div class="addUser-Form acntSetup-Form row">
                                        <div class="col-md-6 nmOutlet">
                                            <div class="row align-items-center acntStp-Row1">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Item'); ?><span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="text" class="form-control" name="itemName" id="itemName"
                                                    value="<?php echo $_POST['itemName']; ?>" autocomplete="off"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                    onchange="this.setCustomValidity('')" required />
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Bar Code'); ?><span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="text" class="form-control" name="barCode" id="barCode"
                                                    value="<?php echo $_POST['barCode'];?>" autocomplete="off"
                                                    onfocusout="checkBarCode(this.value)"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                    onchange="this.setCustomValidity('')" required />
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row chkOlt-Row1">
                                                <div class="col-md-4">
                                                    <label for="receiveInvoice" class="form-label"><?php echo showOtherLangText('Status') ?><span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                <div class="rdoBtn-New d-flex gap-2">
                                                    <span class="fs-13 d-flex align-items-center gap-1">
                                                    <input type="radio" class="form-check-input mt-0" name="status" id="status"
                                                        value="1" autocomplete="off" checked="checked"
                                                        oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                        onchange="this.setCustomValidity('')" required />
                                                    <?php echo showOtherLangText('Active') ?>
                                                    </span>
                                                    <span class="fs-13 d-flex align-items-center gap-1 ms-2">
                                                    <input type="radio" class="form-check-input mt-0" name="status" id="status"
                                                        value="0" autocomplete="off"
                                                        oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                        onchange="this.setCustomValidity('')" required />
                                                    <?php echo showOtherLangText('InActive') ?>
                                                    </span>
                                                </div>
                                             </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row chkOlt-Row1">
                                                <div class="col-md-4">
                                                    <label for="receiveInvoice" class="form-label"><?php echo showOtherLangText('Type') ?><span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                <div class="rdoBtn-New d-flex gap-2">
                                                    <span class="fs-13 d-flex align-items-center gap-1">    
                                                    <input type="radio" class="form-check-input mt-0" name="proType" value="1"
                                                        onClick="showOtherItems(1);" autocomplete="off"
                                                        checked="checked"
                                                        oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                        onchange="this.setCustomValidity('')" required />
                                                    <?php echo showOtherLangText('Normal') ?>
                                                    </span>
                                                    <span class="fs-13 d-flex align-items-center gap-1"> 
                                                    <input type="radio" class="form-check-input mt-0" name="proType" value="2"
                                                            onClick="showOtherItems(2);" autocomplete="off"
                                                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                            onchange="this.setCustomValidity('')" required />
                                                        <?php echo showOtherLangText('Dividable') ?>
                                                    </span>
                                                    <span class="fs-13 d-flex align-items-center gap-1"> 
                                                        <input type="radio" class="form-check-input mt-0" name="proType"
                                                            value="3" onClick="showOtherItems(3);" autocomplete="off"
                                                            id="rawType"
                                                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                            onchange="this.setCustomValidity('')" required />
                                                        <?php echo showOtherLangText('Raw') ?>
                                                    </span>
                                                </div>
                                             </div>
                                            </div>
                                            <div style="display:none;" id="chooseProducts" class="row align-items-start acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label pt-2"><?php echo showOtherLangText('Select product'); ?><span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8 after-add-more">
                                                    <div class=" d-flex gap-3">
                                                    <input type="text" name="rawProducts[]" id="tags" class="frmctrl form-control" />
                                                    <a class="btn btn-success add-more">+</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Images'); ?><span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="file" class="form-control" name="imgName" id="logo"
                                                    onchange="previewFile()" autocomplete="off" style="display:none;" />
                                                <button type="button" id="upload-img-btn" class="sub-btn"
                                                    onclick="document.getElementById('logo').click();"><?php echo showOtherLangText('Click to upload your Image') ?></button>
                                            </div> 
                                                    <img src="<?php echo $_POST['picField']; ?>" style="width: 100px;margin-top:.65rem;" width="100px"
                                                    class="previewImg">
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Storage'); ?><span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                <select name="storageDeptId" id="storageDeptId" class="form-control"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')"
                                                    onchange="this.setCustomValidity('')" required>
                                                    <option value=""><?php echo showOtherLangText('Select'); ?></option>
                                                    <?php
                                                    
                                                    $sqlSet = " SELECT * FROM tbl_stores  WHERE account_id = '".$_SESSION['accountId']."'  ";
                                                    $resultSet = mysqli_query($con, $sqlSet);
                                                    while($departRow = mysqli_fetch_array($resultSet))
                                                    {

                                                        $sel = $_REQUEST['storageDeptId'] == $departRow['id'] ? 'selected="selected"' : '';
                                                        ?>

                                                                                <option value="<?php echo $departRow['id'];?>" <?php echo $sel;?>>
                                                                                    <?php echo $departRow['name'];?> </option>

                                                                                <?php 
                                                    }
                                                    ?>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Parent category'); ?><span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                <?php
				$sqlSet = " SELECT * FROM tbl_category WHERE parentId = 0  AND account_id = '".$_SESSION['accountId']."'  and id != '".$_GET['id']."'  ";
				$resultSet = mysqli_query($con, $sqlSet);
				?>

                                                <select name="parentId" id="parentId" class="form-control"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')"
                                                    onchange="this.setCustomValidity('')" required>
                                                    <option value=""><?php echo showOtherLangText('Select'); ?></option>
                                                    <?php 
					while($catRows = mysqli_fetch_array($resultSet))
					{
						$sel = $_REQUEST['parentId'] == $catRows['id'] ? 'selected = "selected"' : '';
				 ?>
                                                    <option value="<?php echo $catRows['id'];?>" <?php echo $sel;?>>
                                                        <?php echo $catRows['name'];?></option>
                                                    <?php 
					} 

					?>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Subcategory'); ?><span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                <select name="catId" id="catId" class="form-control"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')"
                                                    onchange="this.setCustomValidity('')" required>

                                                    <option value=""><?php echo showOtherLangText('Select'); ?></option>

                                                    <?php
					if(isset($_POST['catId'])) 
					{ 
						$sqlSet = " SELECT * FROM tbl_category WHERE id='".$_POST['catId']."' AND account_id = '".$_SESSION['accountId']."' ";
						$resultSet = mysqli_query($con, $sqlSet);
						$resultSetRow = mysqli_fetch_array($resultSet);

                        $sel = $_POST['catId'] == $resultSetRow['id'] ? 'selected = "selected"' : '';
							?>

                                                    <option value="<?php echo $_POST['catId'];?>" <?php echo $sel; ?>>
                                                        <?php echo $resultSetRow['name'];?>
                                                    </option>

                                                    <?php }  ?>

                                                </select>
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Purchase unit'); ?><span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                <?php
                                                $sqlSet = " SELECT * FROM tbl_units WHERE account_id = '".$_SESSION['accountId']."' ";
                                                $resultSet = mysqli_query($con, $sqlSet);
                                                ?>
                                                <select name="unitP" id="unitP" class="form-control"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')"
                                                    onchange="this.setCustomValidity('')" required>
                                                    <option value=""><?php echo showOtherLangText('Select'); ?></option>
                                                    <?php 
                                                    while($unitsRows = mysqli_fetch_array($resultSet))
                                                    {	
                                                    $sel = $_REQUEST['unitP'] == $unitsRows['id'] ? 'selected = "selected"' : '';
                                                    ?>
                                                    <option value="<?php echo $unitsRows['id'];?>" <?php echo $sel; ?>>
                                                        <?php echo $unitsRows['name'];?></option>

                                                    <?php 
                                                    } 

                                                    ?>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Factor'); ?><span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="text" class="form-control" name="factor" id="factor"
                                                    value="<?php echo $_POST['factor']; ?>" autocomplete="off"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                    onchange="this.setCustomValidity('')" required />
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Counting unit'); ?><span class="requiredsign">*</span></label>
                                                </div>
                                                <?php
                                                $sqlSet = " SELECT * FROM tbl_units WHERE account_id = '".$_SESSION['accountId']."' ";
                                                $resultSet = mysqli_query($con, $sqlSet);
                                                ?>
                                                <div class="col-md-8">
                                                <select name="unitC" id="unitC" class="form-control"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')"
                                                    onchange="this.setCustomValidity('')" required>
                                                    <option value=""><?php echo showOtherLangText('Select'); ?></option>
                                                    <?php 
					while($unitsRows = mysqli_fetch_array($resultSet))
					{	
                        $sel = $_REQUEST['unitC'] == $unitsRows['id'] ? 'selected = "selected"' : '';
						?>
                                                    <option value="<?php echo $unitsRows['id'];?>" <?php echo $sel; ?>>
                                                        <?php echo $unitsRows['name'];?></option>

                                                    <?php 
					} ?>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('C.Price').'('.$getDefCurDet['curCode'].')'; ?><span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="text" class="form-control" name="price" id="price"
                                                    value="<?php echo $_POST['price']; ?>" autocomplete="off"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                    onchange="this.setCustomValidity('')" required />
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Min Level') ?><span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="text" class="form-control" name="minLevel" id="minLevel"
                                                    value="<?php echo $_POST['minLevel']; ?>" autocomplete="off"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                    onchange="this.setCustomValidity('')" required />
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Max Level') ?><span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="text" class="form-control" name="maxLevel" id="maxLevel"
                                                    value="<?php echo $_POST['maxLevel']; ?>" autocomplete="off"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                    onchange="this.setCustomValidity('')" required />
                                                </div>
                                            </div>
                                            <div class="outletChk" style="display:none;">
                                                <div class="row align-items-center acntStp-Row">
                                                    <div class="col-md-4">
                                                        <label for="revenueCenter" class="form-label"><?php echo showOtherLangText('Revenue Center'); ?><span class="requiredsign">*</span></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="cstmSelect  fs-14">
                                                        <?php
									$revCenQry = "SELECT id, name FROM tbl_revenue_center WHERE account_id= '".$_SESSION['accountId']."' ORDER BY name ";

									$revCenResult = mysqli_query($con, $revCenQry);

									?>
                                    <select name="revCenter" id="revCenter" class="form-select selectOption"
                                                                aria-label="Default select example" id="selectRvcntr"
                                                    onchange="getrevCenter();">

                                                    <option value=""><?php echo showOtherLangText('Select'); ?></option>

                                                    <?php
											while($revCenRow = mysqli_fetch_array($revCenResult))
											{

												echo '<option value="'.$revCenRow['id'].'">'.$revCenRow['name'].'</option>';

											} 
											?>
                                                </select>
                                                           
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center acntStp-Row">
                                                    <div class="col-md-4">
                                                        <label for="outletType" class="form-label"><?php echo showOtherLangText('Outlet Type'); ?><span class="requiredsign">*</span></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="cstmSelect  fs-14">
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
                                        <div class="col-md-6 setOutlet ps-lg-4">
                                            <div class="row align-items-start acntStp-Row chkOlt-Row">
                                                <!-- <div class="col-md-4">
                                                    <label for="setOutlet" class="form-label"><?php echo showOtherLangText('Use Revenue Center Address'); ?><span class="requiredsign">*</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="checkbox" id="addressCheck" class="form-check-input" name="addressCheck" value=""
                                            onclick="showRevCenterAddress();">
                                                </div> -->
                                            </div>
                                            <div>
                                                <div class="row  acntStp-Row">
                                                    <div class="col-md-3">
                                                        <label for="revenueCenter" class="form-label"><?php echo showOtherLangText('Supplier'); ?><span class="requiredsign">*</span></label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div class="cstmSelect  fs-14">
                                                        <span class="semibold d-flex align-items-center gap-1 mb-2">
                                                    <input type="checkbox" class="CheckAllOptions form-check-input mt-0" id="CheckAllOptions">
                                                    <label>
                                                        <?php echo showOtherLangText('Check All') ?>
                                                    </label>
                                                </span>

                                                <?php
		$sqlSet = " SELECT * FROM  tbl_suppliers WHERE `account_id` = '".$_SESSION['accountId']."' ORDER BY name  ";
		$resultSet = mysqli_query($con, $sqlSet);
		while( $supRow = mysqli_fetch_array($resultSet) ){

            $sel = $_REQUEST['supplierId'] == $supRow['id'] ? 'checked="checked"' : '';
		?>
                                                <span class="d-flex align-items-center gap-1 mb-1 fs-13">
                                                <input type="checkbox" id="supplierOptionCheck"
                                                    class="supplierOptionCheck form-check-input mt-0"  name="supplierId[]"
                                                    value="<?php echo $supRow['id'];?>" <?php echo $sel; ?> >
                                                    <label ><?php echo $supRow['name'];?></label>
                                                </span>

                                                <?php } ?> 
                                                       </div>
                                                    </div>
                                                </div>
                                                <div class="row acntStp-Row">
                                                    <div class="col-md-3">
                                                        <label for="outletType" class="form-label"><?php echo showOtherLangText('Department'); ?><span class="requiredsign">*</span></label>
                                                    </div>
                                                    <div class="col-md-9">

                                                    <span class="semibold d-flex align-items-center gap-1 mb-2 fs-14">
                                                    <input type="checkbox" class="CheckAllDeptOptions form-check-input mt-0"
                                                        id="CheckAllDeptOptions">
                                                    <label>
                                                        <?php echo showOtherLangText('Check All') ?>
                                                    </label>
                                                    </span>
                                                <?php
	
		$sqlSet = " SELECT * FROM tbl_department WHERE `account_id` = '".$_SESSION['accountId']."' ORDER BY name  ";
		$resultSet = mysqli_query($con, $sqlSet);
		while( $departRows = mysqli_fetch_array($resultSet) ){
						?>                      <span class="d-flex align-items-center gap-1 mb-1 fs-13">
                                                <input type="checkbox" id="deptOptionCheck" class="deptOptionCheck form-check-input mt-0"
                                                    name="deptId[]" value="<?php echo $departRows['id'];?>" >
                                                <?php echo $departRows['name'];?>
                                                </span>

                                                <?php 
		} 

		?>
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
    <div id="dialog" style="display: none;">
        <?php echo showOtherLangText('Select Revenue Center First') ?>
    </div>
    <?php require_once('footer.php');?>
    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
   <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>s
</body>

</html>
<script>
    var x = 0;
    

    $(document).ready(function() {

    var availableTags = [
        <?php 
    $proRows = getAllProducts();
    foreach($proRows as $pId=>$pName){
        $pName = str_replace("'", '', $pName);
        $pName = preg_replace('/[^\w\s]+/u','' , $pName);

        $itemName = $pName.'('.$pId.')';
        echo "\"$itemName\",";
    }
    ?>
    ];

    $("#tags").autocomplete({
        source: availableTags
    });

    var totalCount = $('.supplierOptionCheck').length;

    var totalCheckedCount = $('.supplierOptionCheck:checked').length;

    if (totalCount == totalCheckedCount) {

        $('#CheckAllOptions').prop('checked', true);
    } else {
        $('#CheckAllOptions').prop('checked', false);
    }
    });

    $("#CheckAllOptions").on('click', function() {

    $('.supplierOptionCheck:checkbox').not(this).prop('checked', this.checked);
    });
    
    $("#CheckAllDeptOptions").on('click', function() {

$('.deptOptionCheck:checkbox').not(this).prop('checked', this.checked);
});

    $(".supplierOptionCheck").on('click', function() {

        var totalCount = $('.supplierOptionCheck').length;

        var totalCheckedCount = $('.supplierOptionCheck:checked').length;

        if (totalCount == totalCheckedCount) {

            $('#CheckAllOptions').prop('checked', true);
        } else {
            $('#CheckAllOptions').prop('checked', false);
        }
    });

$(".deptOptionCheck").on('click', function() {

var totalCount = $('.deptOptionCheck').length;

var totalCheckedCount = $('.deptOptionCheck:checked').length;

if (totalCount == totalCheckedCount) {

    $('#CheckAllDeptOptions').prop('checked', true);
} else {
    $('#CheckAllDeptOptions').prop('checked', false);
}
});

     $('#parentId').change(function() {
        var parentId = $(this).val();
        $.ajax({
                method: "POST",
                url: "request_ajax_data.php",
                data: {
                    parentId: parentId
                }
            })
            .done(function(msg) {
                var $el = $("#catId");
                $el.empty(); // remove old options
                $el.append(msg);
            });

    });
    function showOtherItems(proType) {
        if (proType == 3) {
            $('#chooseProducts').show();
        } else {
            $('#chooseProducts').hide();
        }
    }
    var x = 0;
    $("body").on("click", ".add-more", function() {
            x++;
            var html = $(".after-add-more").append('<span id="' + x +
                '"><div class="mt-4 d-flex gap-3"><input type="text" id="tags' + x +
                '" name="rawProducts[]"  class="frmctrl form-control" />  <a class="btn btn-danger remove" onclick="removeRow(' +
                x + ')"><i class="fas fa-close fs-12"></i></a></div></span>');


            var availableTags = [
                <?php 
            $proRows = getAllProducts();
            foreach($proRows as $pId=>$pName){

                $pName = str_replace("'", '', $pName);

                $pName = preg_replace('/[^\w\s]+/u','' , $pName);

                $itemName = $pName.'('.$pId.')';

                echo "\"$itemName\",";
            }
            ?>
            ];
            $("#tags" + x).autocomplete({
                source: availableTags
            });

        });
</script>
<script>
    function previewFile() {
        var preview = document.querySelector('.previewImg');
        var file = document.querySelector('input[type=file]').files[0];
        var reader = new FileReader();

        reader.onloadend = function() {
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
