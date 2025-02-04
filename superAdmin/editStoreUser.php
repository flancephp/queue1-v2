<?php 
require_once('common/header.php');
checkUesrLogin();


$sqlQry = " SELECT * FROM tbl_user WHERE account_id = '".$_GET['account_id']."' AND id = '".$_GET['id']."' ";
$userRes = mysqli_query($con, $sqlQry);
$userRow = mysqli_fetch_array($userRes);

$sqlQry = " SELECT * FROM tbl_designation WHERE account_id = '".$_GET['account_id']."' ";
$designationRes = mysqli_query($con, $sqlQry);

if( isset($_POST['user_name']) )
{
    if ($_POST['mobile_user'] == 1) {
        $userType = '1';
        $designation_id = '0';
    }
    else
    {
        $userType = '0';
        $designation_id = $_POST['designation_title'];
    }
    $logo = '';
    if($_FILES["imgName"]["name"] != '')
    {
        $imgFolderName = getAccountDetails($_GET['account_id']);

        $target_dir = dirname(__FILE__)."/uploads/";

        $target_dir = str_replace('\superAdmin', '', $target_dir);

        $target_dir = str_replace('/superAdmin', '', $target_dir);

        $target_dir = $target_dir.''.$imgFolderName.'/users/';

        $fileName = time(). basename($_FILES["imgName"]["name"]);
        $target_file = $target_dir . $fileName;

        move_uploaded_file($_FILES["imgName"]["tmp_name"], $target_file);

        resize_image($target_file, $target_file, 100,100);
        $logo = $fileName;

        //Delete previous logo of this user if another logo uploaded again by user
        if($userRow['logo'] != '' && file_exists($target_dir.$userRow['logo']) )
        {
          @unlink($target_dir.$userRow['logo']);
        }
    }
    else
    {
        if ($userRow['logo'] != '') {
            $logo = $userRow['logo'];
        }

        $sql = "UPDATE `tbl_user` SET
        `designation_id` = '".$designation_id."',
        `userType` = '".$userType."',
        `username` = '".$_POST['user_name']."',
        `password` = '".$_POST['password']."',
        `name` = '".$_POST['user_name']."',
        `email` = '".$_POST['email']."',
        `phone` = '".$_POST['phone']."',
        `logo` = '".$logo."',
        `isOwner` = '0',
        `isAdmin` = '0',
        `status` = '1'
        WHERE id = '".$_GET['id']."' AND account_id = '".$_GET['account_id']."' ";

        mysqli_query($con, $sql);
    }
        $_SESSION['updated'] = "User has been successfully updated";

        echo "<script>window.location='listStorageUsers.php?account_id=".$_GET['account_id']." '</script>";

    }


?>

<div class="container-fluid">
<div class="row">
<div class="col-md-2">
<?php require_once('common/nav.php');?>
</div>

<div class="col-md-10 col-sm-9 col-lg-10 content-clm">
<div class="panel-heading">
<h3 class="panel-title"><i class="fa fa-pencil" aria-hidden="true"></i>
<span class="panel-span">EditUser</span>
</h3>
</div>
<div class="panelData-Bdy">
<div class="content-row">
<div class="row table-responsive">

<form name="frm" id="frm" method="post" enctype="multipart/form-data" action="">

<div class="upper-main-div">

<div class="upper-left-div">
<table cellpadding="5" cellspacing="5">
<tbody>

<tr>
<td>User Name:</td>
<td>
<input type="text" name="user_name" class="form-control"
value="<?php echo $userRow['name'] ?>">
</td>
</tr>

<tr>
<td>Mobile User:</td>
<td>
<div style="display:inline-flex;">
<div style="width: 100px;">
    <input type="radio" name="mobile_user" id="mobileUser"
        value="1"
        <?php echo ($userRow['userType'] == '1') ? 'checked="checked"' : ''; ?>
        onclick="get_mobile_User(this.value)">
    <label>Yes</label>
</div>

<div style="width: 100px;">
    <input type="radio" name="mobile_user" id="mobileUser"
        value="0"
        <?php echo ($userRow['userType'] != '1') ? 'checked="checked"' : ''; ?>
        onclick="get_mobile_User(this.value)">
    <label>No</label>
</div>
</div>
</td>
</tr>


<tr>
<td id="designationTitleHead">
Designation Title:</td>
<td>
<select name="designation_title" class="form-control"
id="designationTitle">

<option value="">
    Select Designation Title
</option>

<?php
		while ($designationRow = mysqli_fetch_array($designationRes)) {

			$sel = ($userRow['designation_id'] == $designationRow['id']) ? 'selected="selected"' : '';
			?>

<option value="<?php echo $designationRow['id'];?>"
    <?php echo $sel ?>>
    <?php echo $designationRow['designation_name'];?>
</option>

<?php
			
		}
	?>
</select>
</td>
</tr>

<tr>
<td>Password</td>
<td>
<div class="password-section" id="show_hide_password">
<input class="form-control" type="password" name="password"
    value="<?php echo $userRow['password'] ?>">
<div class="eye-button">
    <i class="fa fa-eye-slash" aria-hidden="true"></i>
</div>
</div>
</td>
</tr>

</tbody>
</table>
</div>

<div class="upper-center-div">&nbsp;</div>

<div class="upper-right-div">
<table cellpadding="5" cellspacing="5">
<tbody>

<tr>
<td>Email:</td>
<td>
<input type="email" name="email" class="form-control"
value="<?php echo $userRow['email'] ?>">
</td>
</tr>

<tr>
<td>Phone:</td>
<td>
<input type="text" name="phone" class="form-control"
value="<?php echo $userRow['phone'] ?>">
</td>
</tr>

<tr>
<td>Photo:</td>
<td>
<input type="file" name="imgName" class="form-control"
onchange="previewFile()">
</td>
<td align="center" style="padding-left:40px;">
<?php
$dir = dirname(__FILE__)."/uploads/".$accountImgPath.'/users/'.$userRow['logo'];

$dir = str_replace('\superAdmin', '', $dir);
$dir = str_replace('/superAdmin', '', $dir);

if( $userRow['logo'] != '' && file_exists($dir) )
{  
  //$imgSrc = $siteUrl.'uploads/'.$accountImgPath.'/clientLogo/'.$userRow['logo'];
  echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/users/'.$userRow['logo'].'" width="100" height="100">';
}


?>

</td>
</tr>

</tbody>
</table>
</div>

</div>

<div style="margin-top: 30px;">
<div class="ftBtn-Grp">
<button class="btn btn-info nglb-Btn" type="submit">Save</button>

<button class="btn btn-info nglb-Btn" type="button"
onClick="window.location.href='listStorageUsers.php?account_id=<?php echo $_GET['account_id'] ?>'">Back</button>
</div>
</div>

</form>

</div>
</div>
</div><!-- panel body -->
</div>
</div><!-- content -->
</div>



<script>
$(document).ready(function() {
    $("#show_hide_password i").on('click', function(event) {
        event.preventDefault();
        if ($('#show_hide_password input').attr("type") == "text") {
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass("fa-eye-slash");
            $('#show_hide_password i').removeClass("fa-eye");
        } 
        else if ($('#show_hide_password input').attr("type") == "password") {
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass("fa-eye-slash");
            $('#show_hide_password i').addClass("fa-eye");
        }
    });
});
</script>

<script>
$(document).ready(function() {
    var checkedValue = $('#mobileUser:checked').val();

    if (checkedValue == 1) {
        document.getElementById("designationTitle").style.display = "none";
        document.getElementById("designationTitleHead").style.display = "none";
    }
    if (checkedValue == 0) {
        document.getElementById("designationTitle").style.display = "block";
        document.getElementById("designationTitleHead").style.display = "block";
    }
})
</script>

<script>
function get_mobile_User(mobileUserVal) {
    if (mobileUserVal == 1) {
        document.getElementById("designationTitle").style.display = "none";
        document.getElementById("designationTitleHead").style.display = "none";
    }
    if (mobileUserVal == 0) {
        document.getElementById("designationTitle").style.display = "block";
        document.getElementById("designationTitleHead").style.display = "block";
    }
}
</script>

<script>
function previewFile() {
    var preview = document.querySelector('img');
    var file = document.querySelector('input[type=file]').files[0];
    var reader = new FileReader();

    reader.onloadend = function() {
        preview.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    } 
    else
    {
        preview.src = "";
    }

}
</script>

<?php require_once('footer.php');?>

</body>

</html>