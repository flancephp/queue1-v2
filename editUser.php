<?php 
include('inc/dbConfig.php'); //connection details

if (!isset($_SESSION['adminidusername']))
{
	echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'user' AND type_id = '0' AND designation_id = '".$_SESSION['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if ($permissionRow)
{
    echo "<script>window.location='index.php'</script>";
}


$accountId = $_SESSION['accountId'];

$sqlQry = " SELECT * FROM tbl_user WHERE account_id = '".$accountId."' AND id = '".$_GET['id']."' ";
$userRes = mysqli_query($con, $sqlQry);
$userRow = mysqli_fetch_array($userRes);


if (isset($_POST['userType']) && $_POST['userType'] !='') {

	$sqlQry = " SELECT * FROM tbl_designation WHERE account_id = '".$accountId."' AND is_mobile = '".$_POST['userType']."' ";
	$designationRes = mysqli_query($con, $sqlQry);

	$sql = " SELECT * FROM tbl_user WHERE id = '".$_POST['id']."' AND account_id = '".$_SESSION['accountId']."' ";
	$result = mysqli_query($con, $sql);
	$returnResult = mysqli_fetch_array($result);
	$titleId = $returnResult['designation_id'];

	$selTitle = '<select name="designation_title" class="form-control" id="designationTitle">';

		$selTitle .= '<option value="">'. showOtherLangText('Select Designation Title').'</option>';

		while($desRow = mysqli_fetch_array($designationRes))
		{

			$sel = $desRow['id'] == $returnResult['designation_id'] ? 'selected="selected"' : '';
			$selTitle .= '<option value='.$desRow['id'].' '.$sel.'>'.$desRow['designation_name'].'</option>';
		}
														
	$selTitle .= '</select>';

	echo $selTitle;
	
	die;
}


if( isset($_POST['user_name']) )
{

	if ($_POST['mobile_user'] == 1) {
		$userType = '1';
	}
	else
	{
		$userType = '0';
	}

	if ($_SESSION['id'] == $_GET['id']) 
	{
		if ($_SESSION['designation_id'] != $_POST['designation_title']) 
		{
			unset($_SESSION['designation_id']);
			$_SESSION['designation_id'] = $_POST['designation_title'];
		}
	}
		

	if($_FILES["imgName"]["name"] != '')
	{

		$target_dir = dirname(__FILE__)."/uploads/".$accountImgPath.'/users/';
		$fileName = time(). basename($_FILES["imgName"]["name"]);
		$target_file = $target_dir . $fileName;
		
		move_uploaded_file($_FILES["imgName"]["tmp_name"], $target_file);
		
		resize_image($target_file, $target_file, 100,100);

		if ( $userRow['logo'] != '' && file_exists($target_dir.$userRow['logo']) ) 
		{
			@unlink($target_dir.$userRow['logo']);
		}


		$sql = "UPDATE `tbl_user` SET
		`designation_id` = '".$_POST['designation_title']."',
		`name` = '".$_POST['user_name']."',
		`username` = '".$_POST['user_name']."',
		`userType` = '".$userType."',
		`email` = '".$_POST['email']."',
		`phone` = '".$_POST['phone']."',
		`status` = '1',
		`password` = '".$_POST['password']."',
		`logo` = '".$fileName."',
		`account_id` = '".$accountId."' 
		WHERE id = '".$_GET['id']."' AND account_id = '".$accountId."' ";
		
		mysqli_query($con, $sql);
	}
	else
	{
		$sql = "UPDATE `tbl_user` SET
		`designation_id` = '".$_POST['designation_title']."',
		`name` = '".$_POST['user_name']."',
		`username` = '".$_POST['user_name']."',
		`userType` = '".$userType."',
		`email` = '".$_POST['email']."',
		`phone` = '".$_POST['phone']."',
		`status` = '1',
		`password` = '".$_POST['password']."',
		`account_id` = '".$accountId."' 
		WHERE id = '".$_GET['id']."' AND account_id = '".$accountId."' ";
		
		mysqli_query($con, $sql);
	}

	echo "<script>window.location='users.php?edit=1'</script>";
	
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Edit User - Queue1</title>
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
                            <h1 class="h1"><?php echo showOtherLangText('Edit User'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Edit User'); ?></h1>
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
                <form name="frm" id="frm" method="post" class="addUser-Form row" enctype="multipart/form-data" action="">
                    <div class="container">
                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="users.php" class="sub-btn std-btn mb-usrBkbtn"><span class="mb-UsrBtn"><i
                                                class="fa-solid fa-arrow-left"></i></span> <span
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

                        <div class="adUsr-Div">
                          <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo $userRow['name'] ?>" placeholder="<?php echo showOtherLangText('User Name'); ?>">
                                <div>
													<div><?php echo showOtherLangText('User Type'); ?>:</div>
													<div>
														<div style="display:inline-flex;">									<div style="width: 100px;">
																<input type="radio" name="mobile_user" class="userTypeWeb" value="0" <?php echo ($userRow['userType'] != '1') ? 'checked="checked"' : ''; ?>  onclick="get_mobile_User(this.value)">
																<label><?php echo showOtherLangText('Web'); ?></label>
															</div>

															<div style="width: 100px;">
																<input type="radio" name="mobile_user" class="userTypeMob" value="1" <?php echo ($userRow['userType'] == '1') ? 'checked="checked"' : ''; ?> onclick="get_mobile_User(this.value)">
																<label><?php echo showOtherLangText('Mobile'); ?></label>
															</div>

														</div>
													</div>
												</div>
                                    <div class="setTitle"></div>
                               

                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $userRow['email'] ?>" autocomplete="new-password" placeholder="<?php echo showOtherLangText('Email') ?>">
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $userRow['phone'] ?>" autocomplete="new-password" placeholder="<?php echo showOtherLangText('Phone') ?>">
                                <div>
													<div><?php echo showOtherLangText('Photo') ?>:</div>
													<div>
														<input type="file" name="imgName" class="form-control" id="logo" onchange="previewFile()" autocomplete="new-password" style="display:none;">
														<button type="button" id="upload-img-btn" onclick="document.getElementById('logo').click();"><?php echo showOtherLangText('Click to upload your Image') ?></button>
													</div>
													<!-- <div>
														<img src="<?php //echo $_POST['imgName']; ?>" class="previewImg" width="100px">
													</div> -->
												</div>
                                                <div align="center" style="padding-left: 218px;padding-top: 43px;">
														<?php 
                                                      	if( $userRow['logo'] != '' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath.'/users/'.$userRow['logo'] ) )
														{	
														 	echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/users/'.$userRow['logo'].'" width="100" class="previewImg" height="100">';
														}
														else
														{
															echo '<img src="'.$_POST['imgName'].'" class="previewBlankImg" width="100" height="100" style="display:none;" >'; 
														}
														?>
															
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
    
<script>
  $(document).ready(function() {
    $("#show_hide_password i").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_password i').addClass( "fa-eye" );
        }
    });
	});
</script>

<script>
	$(document).ready(function(){

		if ($('.userTypeWeb').is(':checked')) {
			var userType = $('.userTypeWeb').val();
		}
		else if ($('.userTypeMob').is(':checked')){
			var userType = $('.userTypeMob').val();
		}
			$.ajax({
				method:"POST",
				url: "editUser.php",
				data: {userType:userType, id:'<?php echo $_GET['id'] ?>'}
			})
			.done(function(val){
				$('.setTitle').html(val);
			})
		

	});

	function get_mobile_User(mobileUserVal){
		$.ajax({
			method:"POST",
			url: "editUser.php",
			data: {userType:mobileUserVal, id:'<?php echo $_GET['id'] ?>'}
		})
		.done(function(val){
			$('.setTitle').html(val);
		})

	}
</script>	

<script>
function previewFile() {

	var logo = '<?php echo $userRow['logo'] ?>';

	if (logo == '') 
	{
		$('.previewBlankImg').css('display','block');
		var preview = document.querySelector('.previewBlankImg');
	}
	else
	{
		var preview = document.querySelector('.previewImg');
	}
  
  var file    = document.querySelector('input[type=file]').files[0];
  var reader  = new FileReader();

  reader.onloadend = function () {
    preview.src = reader.result;
  }

  if (file) {
    reader.readAsDataURL(file);
  }else {
    preview.src = "";
  }

}
</script>
</body>

</html>