<?php
include('inc/dbConfig.php'); //connection details

if (!isset($_SESSION['adminidusername']))
{
	echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'category' AND type_id = '0' AND designation_id = '".$_SESSION['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if ($permissionRow)
{
  echo "<script>window.location='index.php'</script>";
}


if(isset($_GET['delId']) && $_GET['delId'])
{

	$sql = "select * FROM tbl_category WHERE parentId = '".$_GET['delId']."'  AND account_id = '".$_SESSION['accountId']."'  ";

	$result = mysqli_query($con, $sql);

	$subCatExist = mysqli_fetch_array($result);

	if(!$subCatExist)
	{

		$sql = "DELETE FROM tbl_category  WHERE id='".$_GET['delId']."' AND account_id = '".$_SESSION['accountId']."'  ";

		mysqli_query($con, $sql);
		
		echo "<script>window.location='categories.php?delete=1&deptId=".$_GET['deptId']." '</script>";

	}
	else
	{
		echo "<script>window.location='categories.php?error=1&deptId=".$_GET['deptId']." '</script>";
	}
	
 

}

//////////////////Pagination goes here/////////////////////////////////////////

$sql = "select * FROM tbl_category WHERE parentId = 0  AND account_id = '".$_SESSION['accountId']."' order by id desc ";
$result = mysqli_query($con, $sql);

  
if( isset($_POST['category']) && $_POST['category'] )
{

	$sql = "SELECT * FROM tbl_category WHERE name = '".trim($_POST['category'])."' AND account_id = '".$_SESSION['accountId']."'  ";
    
	$result = mysqli_query($con, $sql);

	$res = mysqli_num_rows($result);

	if($res > 0)
	{
		
	echo "<script>window.location='categories.php?error_already_exist=1&parentId=".$_POST['parentId']."'</script>";
	exit;

	}

	$sql = "INSERT INTO `tbl_category` SET

	`name` = '".$_POST['category']."',
	`parentId` = '".$_POST['parentId']."',
	`account_id` = '".$_SESSION['accountId']."'  ";

	mysqli_query($con, $sql);

	$catId = mysqli_insert_id($con);

	

	$red = $_POST['parentId'] > 0 ? 'categories.php?add=1&parentId='.$_POST['parentId'] : 'categories.php?add=1';

	echo "<script>window.location='".$red."'</script>";
	
}

elseif( isset($_POST['category']) )

{

	$error = ' '.showOtherLangText('Please enter or select all required data').' ';

}

if( isset($_POST['editCategory']) && $_POST['editCategory']  && $_POST['id'] > 0)
{

	$sql = "select * FROM tbl_category WHERE name = '".trim($_POST['editCategory'])."' AND id != '".$_POST['id']."'  AND account_id = '".$_SESSION['accountId']."' ";

	$result = mysqli_query($con, $sql);

	$res = mysqli_fetch_array($result);

	if($res)
	{
			
		echo "<script>window.location='categories.php?error_already_exist=1&id=".$_POST['id']."&parentId=".$_POST['parentId']."'</script>";

	}


	$sql = "UPDATE  `tbl_category` SET

	`name` = '".$_POST['editCategory']."'	WHERE id = '".$_POST['id']."' 
	AND account_id = '".$_SESSION['accountId']."'  ";

	mysqli_query($con, $sql);


	$red = $_POST['parentId'] > 0 ? 'categories.php?edit=1&parentId='.$_POST['parentId'] : 'categories.php?edit=1';

			
		
	echo "<script>window.location='".$red."'</script>";
	
	
}
elseif(isset($_POST['category']))
{

	$_GET['id'] = $_POST['id'];

	$error = ' '.showOtherLangText('Please enter/select all required data.').' ';

}

// $sql = "SELECT * FROM tbl_category WHERE id = '".$_REQUEST['parentId']."' ";

// $result = mysqli_query($con, $sql);

// $catRes = mysqli_fetch_array($result);

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Categories - Queue1</title>
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
                            <h1 class="h1"><?php echo showOtherLangText('Categories'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Categories'); ?></h1>
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
                    <div class="container">
                    <?php if(isset($_GET['edit']) || isset($_GET['add']) || isset($_GET['delete'])) {?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p><?php 

echo isset($_GET['edit']) ? ' '.showOtherLangText('Category Edited Successfully').' ' : '';

echo isset($_GET['add']) ? ' '.showOtherLangText('Category Added Successfully').' ' : '';

echo isset($_GET['delete']) ? ' '.showOtherLangText('Category Deleted Successfully').' ' : '';

?>
                                </p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                            <?php if(isset($_GET['error']) || isset($_GET['error_already_exist'])) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo isset($_GET['error']) ? ' '.showOtherLangText('Category can not be deleted as it has sub categories').' ' : '';
 ?><?php echo isset($_GET['error_already_exist']) ? ' '.showOtherLangText('Category already exist').' ' : '';
 ?></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="setup.php" class="btn btn-primary mb-usrBkbtn"><span class="mb-UsrBtn"><i
                                                class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                </div>
                            </div>
                            <div class="usrAd-Btn">
                                <div class="btnBg">
                                    <a href="javascript:void(0)" class="btn btn-primary mb-usrBkbtn"
                                        data-bs-toggle="modal" data-bs-target="#add-Category"><span class="mb-UsrBtn"><i
                                                class="fa-solid fa-plus"></i>
                                            <span class="nstdSpan"><?php echo showOtherLangText('Category');?></span></span> <span class="dsktp-Btn"><?php echo showOtherLangText('Add Category');?></span></a>
                                </div>
                            </div>
                        </div>


                        <div class="catgryTable">
                            <div class="container">
                                <!-- Table Head Start -->
                                <div class="catgryTbl-Head align-items-center itmTable">
                                    <div class="catgryTbl-Cnt d-flex align-items-center">
                                        <div class="tb-head catgryNum-Clm">
                                            <div class="d-flex align-items-center">
                                                <p>#</p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" onclick="sortRows(1);" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" onclick="sortRows(0);" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="tb-head catgryName-Clm">
                                            <p><?php echo showOtherLangText('Category'); ?></p>
                                        </div>
                                    </div>
                                    <div class="catgryTbl-Icns">
                                        <div class="tb-head outOpt-Clm text-center">
                                            <p><?php echo showOtherLangText('Options'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Table Head End -->
                                 <div id="myRecords">
                                <!-- Table Body Start -->
                                <?php 
    								$x= 0;
    								while($row = mysqli_fetch_array($result))
    								{
    									$color = ($x%2 == 0)? 'white': '#FFFFCC';
    									$x++;
    									?>
                                <div class="userTask">
                                    <div class="catgryTbl-body align-items-center itmBody">
                                        <div class="catgryTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy catgryNum-Clm">
                                                <p class="userNumber"><span class="mb-UsrSpan">No. </span><?php echo $x;?></p>
                                            </div>
                                            <div class="tb-bdy catgryName-Clm">
                                                <p class="userName"><?php echo $row['name'];?></p>
                                            </div>
                                        </div>
                                        <div class="catgryTbl-Icns">
                                            <div class="tb-bdy catgryOpt-Clm d-flex align-items-center">
                                                <a href="javascript:void(0)" data-editid="<?php echo $row['id'];?>" data-name="<?php echo $row['name'];?>" class="editicon userLink edt_CatLnk"
                                                    data-bs-toggle="modal" data-bs-target="#edit-Category">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)"  onClick="getDelNumb('<?php echo $row['id'];?>', '<?php echo $_GET['deptId'];?>');" class="userLink dlt_CatLnk">
                                                    <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                                <a href="subCategories.php?parentId=<?php echo $row['id'];?>&deptId=<?php echo $_GET['deptId'];?>" class="userLink sub_CatLnk">
                                                    <img src="Assets/icons/setting.svg" alt="Sub-Category"
                                                        class="usrLnk-Img">
                                                    <p class="subCat-Lnk"><?php echo showOtherLangText('Sub Category'); ?><span class="dsk-HdCtgry">.</span><span
                                                            class="mb-HdCtgry"></span></p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 

    								}

    								?>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

            </div>
        </div>
    </div>


    <!-- Add Category Popup Start -->
    <form role="form" action="" method="post" class="addUser-Form row container glbFrm-Cont">
    <div class="modal" tabindex="-1" id="add-Category" aria-labelledby="add-CategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php echo showOtherLangText('Add Category');?></h1>
                </div>
                <div class="modal-body">
                    <input type="text" required class="form-control" id="category" name="category" placeholder="<?php echo showOtherLangText('Category');?>*">
                </div>
                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="submit" class="btn btn-primary"><?php echo showOtherLangText('Add'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    <!-- Add Category Popup End -->

    <!-- Edit Category Popup Start -->
    <form role="form" action=""  class="addUser-Form row container glbFrm-Cont" method="post">
    <div class="modal" tabindex="-1" id="edit-Category" aria-labelledby="edit-CategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php echo showOtherLangText('Edit Category'); ?></h1>
                </div>
                <div class="modal-body">
                    
                    <input type="hidden" name="id" id="edit-id" value="" /> 
                    <input type="text" class="form-control" name="editCategory" id="editCategory" required placeholder="<?php echo showOtherLangText('Category'); ?>*">
                   
                </div>
                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="submit" class="btn btn-primary"><?php echo showOtherLangText('Save'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Category Popup End -->

    <div id="dialog" style="display: none;">
    <?php echo showOtherLangText('Are you sure to delete this record?') ?>  
</div>

<?php require_once('footer.php');?>
<link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
   <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
   <div class="modal" tabindex="-1" id="delete-popup" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php echo showOtherLangText('Are you sure to delete this record?') ?> </h1>
                </div>
                
                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="button" data-bs-dismiss="modal" class="btn sub-btn std-btn"><?php echo showOtherLangText('No'); ?></button>
                    </div>
                    <div class="btnBg">
                        <button type="button" onclick="" class="deletelink btn sub-btn std-btn"><?php echo showOtherLangText('Yes'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
$( document ).ready(function() {
      $('.editicon').click(function(){
        
        var editIdValue = this.getAttribute("data-editid");
        var editnameValue = this.getAttribute("data-name");
        $('#editCategory').val(editnameValue);
        $('#edit-id').val(editIdValue);
      });
});  
  function getDelNumb(delId){
var newOnClick = "window.location.href='categories.php?delId=" + delId + "'";

      $('.deletelink').attr('onclick', newOnClick);
     $('#delete-popup').modal('show');

 }  

 jQuery.fn.orderBy = function(keySelector) {
        return this.sort(function(a, b) {
            a = keySelector.apply(a);
            b = keySelector.apply(b);
            if (a > b) return 1;
            if (a < b) return -1;
            return 0;
        });
    };

    // Function to sort and reorder the .userTask elements
    function sortRows(sort) {
        var uu = $(".userTask").orderBy(function() {
             var number = +$(this).find(".userNumber").text().replace('No. ', '');
             return sort === 1 ? number : -number; 
        }).appendTo("#myRecords");


    }
</script> 