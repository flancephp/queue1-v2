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



$msg = '';
if(isset($_POST['itemName']))
{

    $sql = "SELECT * FROM tbl_products
    WHERE barCode = '".trim($_POST['barCode'])."' AND id != '".$_GET['id']."' AND account_id = '".$_SESSION['accountId']."'  ";
    $result = mysqli_query($con, $sql);
    $res = mysqli_fetch_array($result);

    if($res)
    {
        echo "<script>window.location='editProduct.php?error=1&id=".$_POST['id']."'</script>";
    }
    else
    {

        if ($_POST['maxLevel'] < $_POST['minLevel']) 
        {
            $msg = ' '.showOtherLangText('MaxLevel should be greater than minLevel.').' ';
        }
        else
        {
            $picField = '';
            if($_FILES["imgName"]["name"] != '')
            {
                $target_dir = dirname(__FILE__)."/uploads/".$accountImgPath.'/products/';
                $fileName = time().basename($_FILES["imgName"]["name"]);
                $target_file = $target_dir . $fileName;

                move_uploaded_file($_FILES["imgName"]["tmp_name"], $target_file);

                $picField = " , imgName = '".$fileName."' ";

                    if($res['imgName'] != '' && file_exists($target_dir.$res['imgName']) )
                    {
                        @unlink($target_dir.$res['imgName']);
                    }
            }

            $sql = "UPDATE `tbl_products` SET
        	`storageDeptId` = '".$_POST['storageDeptId']."',
        	`parentCatId` = '".$_POST['parentId']."',
        	`catId` = '".$_POST['catId']."',
        	`itemName` = '".addslashes($_POST['itemName'])."',
        	`barCode` = '".$_POST['barCode']."',
        	`unitP` = '".$_POST['unitP']."',
        	`factor` = '".$_POST['factor']."',
        	`unitC` = '".$_POST['unitC']."',
        	`price` = '".$_POST['price']."',
        	`stockPrice` = '".$_POST['stockPrice']."',
        	`minLevel` = '".$_POST['minLevel']."',
        	`maxLevel` = '".$_POST['maxLevel']."',
        	`status` = '".$_POST['status']."',
        	`proType` = '".$_POST['proType']."',
        	`rawProducts` = '".$_POST['rawProducts']."'
        	".$picField."
        				
        	WHERE id =  '".$_POST['id']."' AND account_id = '".$_SESSION['accountId']."'  ";
            mysqli_query($con, $sql);

            //update stock price
            if($_POST['stockPrice'] > 0)
            {
                $sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$_POST['id']."' AND account_id = '".$_SESSION['accountId']."'  ";
                $stkQry = mysqli_query($con, $sql);
                $stkRow = mysqli_fetch_array($stkQry);
                if($stkRow)
                {
                    $upQry = " UPDATE  `tbl_stocks` SET
                    `stockPrice` = ".$_POST['stockPrice'].",
                    `stockValue` = ".($_POST['stockPrice']*$stkRow['qty'])."
                    WHERE id = '".$stkRow['id']."'  AND account_id = '".$_SESSION['accountId']."'  ";
                    mysqli_query($con, $upQry);

                }	
            }

            //update last price
            if($_POST['price'] > 0)
            {
                $sql = "SELECT *  FROM tbl_stocks  WHERE pId = '".$_POST['id']."'  AND account_id = '".$_SESSION['accountId']."' ";
                $stkQry = mysqli_query($con, $sql);
                $stkRow = mysqli_fetch_array($stkQry);
                if($stkRow)
                {
                    $upQry = " UPDATE  `tbl_stocks` SET
                    `lastPrice` = '".$_POST['price']."'
                    WHERE id = '".$stkRow['id']."' AND account_id = '".$_SESSION['accountId']."'   ";
                    mysqli_query($con, $upQry);
                }	
            }//End


            $sql = " DELETE  FROM `tbl_productdepartments` WHERE  productId = '".$_POST['id']."'  AND account_id = '".$_SESSION['accountId']."' ";
            mysqli_query($con, $sql);

            foreach($_POST['deptId'] as $deptId)
            {
                $sql = "INSERT INTO `tbl_productdepartments` SET
                `productId` = '".$_POST['id']."',
                `deptId` = '".$deptId."',
                `account_id` = '".$_SESSION['accountId']."'  ";
                mysqli_query($con, $sql);
            }

            $sql = " DELETE  FROM `tbl_productsuppliers` WHERE  productId = '".$_POST['id']."' AND account_id = '".$_SESSION['accountId']."'  ";
            mysqli_query($con, $sql);

            foreach($_POST['supplierId'] as $supplierId)
            {
                $sql = "INSERT INTO `tbl_productsuppliers` SET
                `productId` = '".$_POST['id']."',
                `supplierId` = '".$supplierId."',
                `account_id` = '".$_SESSION['accountId']."'  ";
                mysqli_query($con, $sql);
            }


            $sql = " DELETE  FROM `tbl_rawitem_products` WHERE  rawPid = '".$_POST['id']."' AND account_id = '".$_SESSION['accountId']."'  ";
            mysqli_query($con, $sql);

            if( isset($_POST['rawProducts']) && $_POST['rawProducts'] != '')
            {
                $rawProducts = $_POST['rawProducts'];
                if( !empty($rawProducts) )
                {
                    foreach($rawProducts as $proName)
                    {
                        if( !empty($proName) )
                        {
                        	
                        	$proNameFilter = explode('(', $proName);
                        	$cnt = count($proNameFilter)-1;
                        	$convtPid = trim( str_replace(')', '', $proNameFilter[$cnt]) );
                        	
                        	if($_POST['id'] > 0 && $convtPid > 0)
                        	{
                        		 $sql = "INSERT INTO `tbl_rawitem_products` SET
                        		`rawPid` = '".$_POST['id']."',
                        		`convtPid` = '".$convtPid."',
                        		`account_id` = '".$_SESSION['accountId']."'  ";
                               
                        		mysqli_query($con, $sql);
                        	}
                        }
                    }
                }
            }

            echo "<script>window.location='itemsManager.php?edit=1'</script>";
        }
    }
}


// Main query goes here
$sql = "SELECT p.* FROM tbl_products p 
LEFT JOIN tbl_stocks s 
    ON (s.pId=p.id) AND s.account_id=p.account_id
        WHERE p.id = '".$_GET['id']."' AND p.account_id = '".$_SESSION['accountId']."'  ";
$result = mysqli_query($con, $sql);
$res = mysqli_fetch_array($result);
$convertedProducts = '';
if( $res['proType'] == 3)
{
    $convertedProducts = getRawProducts($_GET['id']);
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
                            <h1 class="h1"><?php echo showOtherLangText('Edit Product'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Edit Product'); ?></h1>
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

                <section class="ordDetail userDetail itmMngDetail">
                    <div class="container">
                    <form role="form" action="" method="post" class="container" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $res['id'];?>" />
                        <div class="row">
                            <div class="col-md-6 bkOutlet-Btn">
                                <div class="btnBg">
                                    <a href="itemsManager.php" class="sub-btn std-btn mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                </div>
                            </div>
                            <div class="col-md-6 addOutlet-Btn">
                                <div class="itmLnk-Row">
                                    <!-- <div class="btnBg">
                                        <a href="javascript:void(0)" class="sub-btn std-btn mb-usrBkbtn"><span
                                                class="mb-UsrBtn"><i class="fa-solid fa-plus"></i>
                                                <span class="nstdSpan">Item</span></span> <span class="dsktp-Btn">Add
                                                Item</span></a>
                                    </div> -->
                                    <div class="btnBg">
                                        <button type="submit" class="btn sub-btn std-btn mb-usrBkbtn"><span
                                                class="mb-UsrBtn"><i class="fa-regular fa-floppy-disk"></i></span> <span
                                                class="dsktp-Btn"><?php echo showOtherLangText('Save'); ?></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container itmMng-Src outletFrm">
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
                            <div class="col-md-8 oltCol-8">
                                <div class="acntStp">
                                    <div class="addUser-Form acntSetup-Form row">
                                        <div class="acnt-Div nmOutlet">
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Item'); ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="text" class="form-control" name="itemName" id="itemName"
                                                    value="<?php echo $res['itemName'];?>" autocomplete="off"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                    onchange="this.setCustomValidity('')" required />
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Bar Code'); ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="text" class="form-control" name="barCode" id="barCode"
                                                    value="<?php echo $res['barCode'];?>" autocomplete="off"
                                                    onfocusout="checkBarCode(this.value)"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                    onchange="this.setCustomValidity('')" required />
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row chkOlt-Row">
                                                <div class="col-md-4">
                                                    <label for="receiveInvoice" class="form-label"><?php echo showOtherLangText('Status') ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                <div class="rdoBtn-New"><input type="radio" name="status" id="status"
                                                        value="1" autocomplete="off" checked="checked"
                                                        oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                        onchange="this.setCustomValidity('')" <?php echo $res['status'] == 1 ? 'checked="checked"' : '';?> required />
                                                    <?php echo showOtherLangText('Active') ?>
                                                    <input type="radio" name="status" id="status"
                                                        value="0" autocomplete="off"
                                                        oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                        onchange="this.setCustomValidity('')" <?php echo $res['status'] != 1 ? 'checked="checked"' : '';?> required />
                                                    <?php echo showOtherLangText('InActive') ?>
                                                </div>
                                             </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row chkOlt-Row">
                                                <div class="col-md-4">
                                                    <label for="receiveInvoice" class="form-label"><?php echo showOtherLangText('Type') ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                <div class="rdoBtn-New">
                                                    <input type="radio" name="proType" value="1"
                                                        onClick="showOtherItems(1);" autocomplete="off"
                                                        <?php echo $res['proType'] == 1 ? 'checked="checked"' : '';?>
                                                        oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                        onchange="this.setCustomValidity('')"  required />
                                                    <?php echo showOtherLangText('Normal') ?>
                                                    <input type="radio" name="proType" value="2"
                                                            onClick="showOtherItems(2);" autocomplete="off"
                                                            <?php echo $res['proType'] == 2 ? 'checked="checked"' : '';?>
                                                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                            onchange="this.setCustomValidity('')"  required />
                                                        <?php echo showOtherLangText('Dividable') ?>
                                                        <input type="radio" name="proType"
                                                            value="3" onClick="showOtherItems(3);" autocomplete="off"
                                                            id="rawType"
                                                            <?php echo $res['proType'] == 3 ? 'checked="checked"' : '';?>
                                                            oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                            onchange="this.setCustomValidity('')"  required />
                                                        <?php echo showOtherLangText('Raw') ?>
                                                </div>
                                             </div>
                                            </div>
                                            <div <?php echo $res['proType'] == 3 ? '' : 'style="display:none"';?> id="chooseProducts" class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Select product'); ?></label>
                                                </div>
                                                <div class="col-md-8 after-add-more">
                                                <?php
			$i=0;
			if($res['proType'] == 3 && $convertedProducts)
			{
				
			 	foreach($convertedProducts as $proName)
				{
					$i++;
					if($i == 1)
					{
						echo  '<input type="text"    name="rawProducts[]" id="tags" value="'.$proName.'" class="frmctrl" /> 
						<a class="btn btn-success add-more">+</a>';
					}
					else
					{ 
						echo '<span id="'.$i.'"><br><br><input type="text" id="tags'.$i.'"  value="'.$proName.'"  name="rawProducts[]"  class="frmctrl" /> 
						 <a class="btn btn-danger remove" onclick="removeRow('.$i.')">X</a></span>';
					}
				}
			}else
			{
				echo '<input type="text"  name="rawProducts[]" id="tags"  class="frmctrl" />  <a class="btn btn-success add-more">+</a>';
			} ?>
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Images'); ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="file" class="form-control" name="imgName" id="logo"
                                                    onchange="previewFile()" autocomplete="off" style="display:none;" />
                                                <button type="button" id="upload-img-btn"
                                                    onclick="document.getElementById('logo').click();"><?php echo showOtherLangText('Click to upload your Image') ?></button>
                                            </div> 
                                                    <img src="<?php echo $_POST['picField']; ?>" style="width: 100px;" width="100px"
                                                    class="previewImg">
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Storage'); ?></label>
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

                                                        $sel =  ($res['storageDeptId'] == $departRow['id']) ? 'selected = "selected"' : '';
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
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Parent category'); ?></label>
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
						$sel = $res['parentCatId'] == $catRows['id'] ? 'selected = "selected"' : '';
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
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Subcategory'); ?></label>
                                                </div>
                                                
                                                <?php
	$sqlSet = " SELECT * FROM tbl_category WHERE parentId = '".$res['parentCatId']."' AND account_id = '".$_SESSION['accountId']."'  ";
	$resultSet = mysqli_query($con, $sqlSet);
	?>
                                                <div class="col-md-8">
                                                <select name="catId" id="catId" class="form-control" required>
                                                    <?php while( $catRows = mysqli_fetch_array($resultSet) ){
				$sel = $res['catId'] == $catRows['id'] ? 'selected = "selected"' : '';
		 ?>
                                                    <option value="<?php echo $catRows['id'];?>" <?php echo $sel;?>>
                                                        <?php echo $catRows['name'];?></option>
                                                    <?php } ?>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Purchase unit'); ?></label>
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
                                                    $sel = $res['unitP'] == $unitsRows['id'] ? 'selected = "selected"' : '';
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
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Factor'); ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="text" class="form-control" name="factor" id="factor"
                                                    value="<?php echo $res['factor'];?>" autocomplete="off"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                    onchange="this.setCustomValidity('')" required />
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Counting unit'); ?></label>
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
                        $sel = $res['unitC'] == $unitsRows['id'] ? 'selected = "selected"' : '';
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
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('C.Price').'('.$getDefCurDet['curCode'].')'; ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="text" class="form-control" name="price" id="price"
                                                    value="<?php echo getPrice($res['price']);?>" autocomplete="off"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                    onchange="this.setCustomValidity('')" required />
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Stock Price').'('.$getDefCurDet['curCode'].')'; ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="text" class="form-control" name="stockPrice" id="stockPrice"
                                                    value="<?php echo getPrice($res['stockPrice']);?>" autocomplete="off"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                    onchange="this.setCustomValidity('')" required />
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Min Level') ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="text" class="form-control" name="minLevel" id="minLevel"
                                                    value="<?php echo $res['minLevel'];?>" autocomplete="off"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                    onchange="this.setCustomValidity('')" required />
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label"><?php echo showOtherLangText('Max Level') ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="text" class="form-control" name="maxLevel" id="maxLevel"
                                                    value="<?php echo $res['maxLevel'];?>" autocomplete="off"
                                                    oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please fill out this field.') ?>')"
                                                    onchange="this.setCustomValidity('')" required />
                                                </div>
                                            </div>
                                            <div class="outletChk" style="display:none;">
                                                <div class="row align-items-center acntStp-Row">
                                                    <div class="col-md-4">
                                                        <label for="revenueCenter" class="form-label"><?php echo showOtherLangText('Revenue Center'); ?></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="cstmSelect">
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
                                                        <label for="outletType" class="form-label"><?php echo showOtherLangText('Outlet Type'); ?></label>
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
                                        <div class="acntLg-Upld setOutlet">
                                            <div class="row align-items-center acntStp-Row chkOlt-Row">
                                                <!-- <div class="col-md-4">
                                                    <label for="setOutlet" class="form-label"><?php echo showOtherLangText('Use Revenue Center Address'); ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                <input type="checkbox" id="addressCheck" class="form-check-input" name="addressCheck" value=""
                                            onclick="showRevCenterAddress();">
                                                </div> -->
                                            </div>
                                            <div>
                                                <div class="row align-items-center acntStp-Row">
                                                    <div class="col-md-4">
                                                        <label for="revenueCenter" class="form-label"><?php echo showOtherLangText('Supplier'); ?></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="cstmSelect">
                                                        <strong class="checkAllSectionBox">
                                                    <input type="checkbox" class="CheckAllOptions" id="CheckAllOptions">
                                                    <label>
                                                        <?php echo showOtherLangText('Check All') ?>
                                                    </label>
                                                </strong>

                                                <?php
        $sqlSet = " SELECT GROUP_CONCAT(supplierId) supplierIds FROM tbl_productsuppliers WHERE productId = '".$_GET['id']."'  AND account_id = '".$_SESSION['accountId']."' GROUP BY productId ";
        $resultSet = mysqli_query($con, $sqlSet);
        $proSuppls = mysqli_fetch_array($resultSet);

		$sqlSet = " SELECT * FROM  tbl_suppliers WHERE `account_id` = '".$_SESSION['accountId']."' ORDER BY name  ";
		$resultSet = mysqli_query($con, $sqlSet);
		while( $supRow = mysqli_fetch_array($resultSet) ){
            $supIdsArr = explode(',', $proSuppls['supplierIds']);
            $sel = in_array($supRow['id'], $supIdsArr) ? 'checked="checked"' : '';
		?>
                                                <input type="checkbox" id="supplierOptionCheck"
                                                    class="supplierOptionCheck" name="supplierId[]"
                                                    value="<?php echo $supRow['id'];?>" <?php echo $sel; ?> >
                                                <?php echo $supRow['name'];?><br>

                                                <?php } ?> 
                                                       </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center acntStp-Row">
                                                    <div class="col-md-4">
                                                        <label for="outletType" class="form-label"><?php echo showOtherLangText('Department'); ?></label>
                                                    </div>
                                                    <div class="col-md-8">
                                                    <strong class="checkAllSectionBox">
                                                    <input type="checkbox" class="CheckAllDeptOptions"
                                                        id="CheckAllDeptOptions">
                                                    <label>
                                                        <?php echo showOtherLangText('Check All') ?>
                                                    </label>
                                                </strong><br>
                                                <?php
	    
        $sqlSet = " SELECT GROUP_CONCAT(deptId) deptIds FROM tbl_productdepartments WHERE productId = '".$_GET['id']."' AND account_id = '".$_SESSION['accountId']."'  GROUP BY productId ";
        $resultSet = mysqli_query($con, $sqlSet);
        $proDepartsR = mysqli_fetch_array($resultSet);

		$sqlSet = " SELECT * FROM tbl_department WHERE `account_id` = '".$_SESSION['accountId']."' ORDER BY name  ";
		$resultSet = mysqli_query($con, $sqlSet);
		while( $departRows = mysqli_fetch_array($resultSet) ){
            $deprtIdsArr = explode(',', $proDepartsR['deptIds']);
            $sel = in_array($departRows['id'], $deprtIdsArr) ? 'checked="checked"' : '';
						?>
                                                <input type="checkbox" id="deptOptionCheck" class="deptOptionCheck"
                                                    name="deptId[]" <?php echo $sel; ?> value="<?php echo $departRows['id'];?>" >
                                                <?php echo $departRows['name'];?><br>

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
        $itemName = $pName.'('.$pId.')';
        echo "'$itemName'".',';
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
                '"><br><br><input type="text" id="tags' + x +
                '" name="rawProducts[]"  class="frmctrl" />  <a class="btn btn-danger remove" onclick="removeRow(' +
                x + ')">X</a></span>');


            var availableTags = [
                <?php 
            $proRows = getAllProducts();
            foreach($proRows as $pId=>$pName){
                $itemName = $pName.'('.$pId.')';
                echo "'$itemName'".',';
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
