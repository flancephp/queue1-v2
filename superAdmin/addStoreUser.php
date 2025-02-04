<?php 
include('common/header.php');
checkUesrLogin();

if(isset($_POST['user_name']))
{
    if ($_POST['mobile_user'] == 1) 
    {
        $userType = '1';
        $designation_id = '0';
    }
    else
    {
        $userType = '0';
        $designation_id = $_POST['designation_title'];
    }

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
    }

    $sql = "INSERT INTO `tbl_user` SET
    `account_id` = '".$_GET['account_id']."',
    `designation_id` = '".$designation_id."',
    `userType` = '".$userType."',
    `username` = '".$_POST['user_name']."',
    `password` = '".$_POST['password']."',
    `name` = '".$_POST['user_name']."',
    `email` = '".$_POST['email']."',
    `phone` = '".$_POST['phone']."',
    `isOwner` = '0',
    `isAdmin` = '0',
    `status` = '1',
    `logo` = '".$fileName."' ";
    mysqli_query($con, $sql);

    $_SESSION['added'] = "User has been successfully added";

    echo "<script>window.location='listStorageUsers.php?account_id=".$_GET['account_id']." '</script>";
}


$sqlQry = " SELECT * FROM tbl_designation WHERE account_id = '".$_GET['account_id']."' ";
$designationRes = mysqli_query($con, $sqlQry);


?>

<div class="container-fluid">
<div class="row">
<div class="col-md-2 reverse-nav-section">
<?php require_once('common/nav.php');?>
</div>

<div class="col-md-10 col-sm-9 col-lg-10 content-clm">


<div class="panel-heading">
<h3 class="panel-title"><i class="fa fa-user-plus" aria-hidden="true"></i>
<span class="panel-span">Add User</span>
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
                        autocomplete="new-password" required>
                </td>
            </tr>

            <tr>
                <td>Mobile User:</td>
                <td>
                    <div style="display:inline-flex;">
                        <div style="width: 100px;">
                            <input type="radio" name="mobile_user" id="mobileUser"
                                value="1" onclick="get_mobile_User(this.value)"
                                autocomplete="new-password">
                            <label>Yes</label>
                        </div>

                        <div style="width: 100px;">
                            <input type="radio" name="mobile_user" id="mobileUser"
                                value="0" onclick="get_mobile_User(this.value)"
                                autocomplete="new-password">
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

								?>

                        <option value="<?php echo $designationRow['id'];?>">
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
                            autocomplete="new-password" required>
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
                        autocomplete="new-password">
                </td>
            </tr>

            <tr>
                <td>Phone:</td>
                <td>
                    <input type="text" name="phone" class="form-control"
                        autocomplete="new-password">
                </td>
            </tr>

            <tr>
                <td>Photo:</td>
                <td>
                    <input type="file" name="imgName" class="form-control"
                        onchange="previewFile()" autocomplete="new-password">
                </td>
                <td id="showImg" style="display: none;">
                    <img src="<?php echo $_POST['imgName']; ?>" width="100px">
                </td>
            </tr>

        </tbody>
    </table>
</div>

</div>

<div style="margin-top: 30px;">
<div class="ftBtn-Grp">
    <button class="btn btn-info nglb-Btn"
        type="submit">Save</button>

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
} else if ($('#show_hide_password input').attr("type") == "password") {
$('#show_hide_password input').attr('type', 'text');
$('#show_hide_password i').removeClass("fa-eye-slash");
$('#show_hide_password i').addClass("fa-eye");
}
});
});
</script>

<script>
function get_mobile_User(mobileUserVal) {
//alert(mobileUserVal);
if (mobileUserVal == 1) {
//alert('value => 1');
document.getElementById("designationTitle").style.display = "none";
document.getElementById("designationTitleHead").style.display = "none";

}
if (mobileUserVal == 0) {
//alert('value => 0');
document.getElementById("designationTitle").style.display = "block";
document.getElementById("designationTitleHead").style.display = "block";
}

}
</script>

<script>
function previewFile() {
$("#showImg").css("display","block");    
var preview = document.querySelector('img');
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
</script>

<?php require_once('footer.php');?>

</body>

</html>