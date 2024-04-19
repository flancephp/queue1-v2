<?php include('inc/dbConfig.php'); //connection details


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

if (isset($_POST['userType']) && $_POST['userType'] !='') {

	$sqlQry = " SELECT * FROM tbl_designation WHERE account_id = '".$accountId."' AND is_mobile = '".$_POST['userType']."' ";
	$designationRes = mysqli_query($con, $sqlQry);

	$selTitle = '<select name="designation_title" class="form-control" id="designationTitle" required>';

		$selTitle .= '<option value="">'. showOtherLangText('Select Designation Title').'</option>';

		while($desRow = mysqli_fetch_array($designationRes))
		{
				$selTitle .= '<option value='.$desRow['id'].'>'.$desRow['designation_name'].'</option>';
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

	if($_FILES["imgName"]["name"] != '')
	{

		$target_dir = dirname(__FILE__)."/uploads/".$accountImgPath.'/users/';
		$fileName = time(). basename($_FILES["imgName"]["name"]);
		$target_file = $target_dir . $fileName;
		
		move_uploaded_file($_FILES["imgName"]["tmp_name"], $target_file);
		
		resize_image($target_file, $target_file, 100,100);
      
	}

	$sql = "INSERT INTO `tbl_user` SET
	`designation_id` = '".$_POST['designation_title']."',
	`name` = '".$_POST['user_name']."',
	`username` = '".$_POST['user_name']."',
	`userType` = '".$userType."',
	`email` = '".$_POST['email']."',
	`phone` = '".$_POST['phone']."',
	`status` = '1',
	`password` = '".$_POST['password']."',
	`logo` = '".$fileName."',
	`account_id` = '".$accountId."' ";
	mysqli_query($con, $sql);
	
	echo "<script>window.location='users.php?added=1'</script>";
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Add User - Queue1</title>
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
                            <h1 class="h1"><?php echo showOtherLangText('Add User') ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Add User') ?></h1>
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
                    <form name="frm" id="frm" class="addUser-Form row" method="post" enctype="multipart/form-data" action="">
                               
                    <div class="container">
                    <?php if(isset($_GET['added']) || isset($_GET['edit']) || isset($_GET['delete'])) {?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p><?php 
                     echo isset($_GET['added']) ? ' '.showOtherLangText('User Added Successfully').' ' : '';
                     echo isset($_GET['edit']) ? ' '.showOtherLangText('User Edited Successfully').' ' : '';
                     echo isset($_GET['delete']) ? ' '.showOtherLangText('User Deleted Successfully').' ' : ''; ?>
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                            <?php if(isset($_GET['error'])) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo showOtherLangText('User can not be deleted as it is being used in order'); ?></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
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
                        <!-- <div class="adUsr-Div">
                            <form class="addUser-Form row">
                                <input type="text" class="form-control" name="user_name" id="user_name" placeholder="<?php //echo showOtherLangText('User Name') ?>">

                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Select title</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>

                                <input type="password" class="form-control" id="inputPassword" placeholder="Password">
                            </form>
                        </div> -->
                        <div class="adUsr-Div">
                        <input type="text" class="form-control" id="user_name" required name="user_name" placeholder="<?php echo showOtherLangText('User Name') ?>">
                                
                                <label for="receiveInvoice" class="form-label"><?php echo showOtherLangText('User Type') ?></label><input type="radio" name="mobile_user" class="userTypeWeb" value="0" onclick="get_mobile_User(this.value)" autocomplete="new-password" required>
								<label><?php echo showOtherLangText('Web') ?></label>
                                <input type="radio" name="mobile_user" class="userTypeMob" value="1" onclick="get_mobile_User(this.value)" autocomplete="new-password" required>
								<label><?php echo showOtherLangText('Mobile') ?></label>
								
                                <div class="setTitle"></div>
                                <input type="password" required class="form-control" id="password" name="password" placeholder="Password">
                                <input type="email" class="form-control" id="email" name="email" autocomplete="new-password" placeholder="<?php echo showOtherLangText('Email') ?>">
                                <input type="text" class="form-control" id="phone" name="phone" autocomplete="new-password" placeholder="<?php echo showOtherLangText('Phone') ?>">
                                <div>
													<div><?php echo showOtherLangText('Photo') ?>:</div>
													<div>
														<input type="file" name="imgName" class="form-control" id="logo" onchange="previewFile()" autocomplete="new-password" style="display:none;">
														<button type="button" id="upload-img-btn" onclick="document.getElementById('logo').click();"><?php echo showOtherLangText('Click to upload your Image') ?></button>
													</div>
													<div>
														<img src="<?php echo $_POST['imgName']; ?>" class="previewImg" width="100px">
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
		
		$('.userTypeWeb').prop('checked', true);

		if ($('.userTypeWeb').is(':checked')) {

				var userType = $('.userTypeWeb').val();
				$.ajax({
					method:"POST",
					url: "addUser.php",
					data: {userType:userType}
				})
				.done(function(val){
					$('.setTitle').html(val);
				})
		}

	});



	function get_mobile_User(mobileUserVal){
		$.ajax({
			method:"POST",
			url: "addUser.php",
			data: {userType:mobileUserVal}
		})
		.done(function(val){
			$('.setTitle').html(val);
		})

	}
</script>	

<script>
function previewFile() {
  var preview = document.querySelector('.previewImg');
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