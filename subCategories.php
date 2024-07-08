<?php
include('inc/dbConfig.php'); //connection details

if ( !isset($_SESSION['adminidusername'])) {

	echo "<script>window.location='login.php'</script>";

}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


if( isset($_GET['delId']) && $_GET['delId'] )

{

	$sql = "DELETE FROM tbl_category  WHERE id='".$_GET['delId']."'  AND account_id = '".$_SESSION['accountId']."'  ";

	mysqli_query($con, $sql);
	
	echo "<script>window.location='subCategories.php?delete=1&parentId=".$_GET['parentId']."'</script>";

}

/////////////////Pagination goes here/////////////////////////////////////////

$sql = "SELECT * FROM tbl_category WHERE id = '".$_REQUEST['parentId']."' AND account_id = '".$_SESSION['accountId']."'   ";

$result = mysqli_query($con, $sql);

$catRes = mysqli_fetch_array($result);


$sql = "SELECT * FROM tbl_category WHERE parentId = '".$_GET['parentId']."' AND account_id = '".$_SESSION['accountId']."'   ";

$result = mysqli_query($con, $sql);


if( isset($_POST['addSub-Category']) && $_POST['addSub-Category'] )
{

	$sql = "SELECT * FROM tbl_category WHERE name = '".trim($_POST['addSub-Category'])."' AND account_id = '".$_SESSION['accountId']."'  ";

	$result = mysqli_query($con, $sql);

	$res = mysqli_num_rows($result);

	if($res > 0)
	{
		
	echo "<script>window.location='subCategories.php?error=1&parentId=".$_POST['parentId']."'</script>";
	exit;

	}

	$sql = "INSERT INTO `tbl_category` SET

	`name` = '".$_POST['addSub-Category']."',
	`parentId` = '".$_POST['parentId']."',
	`account_id` = '".$_SESSION['accountId']."'  ";

	mysqli_query($con, $sql);

	$catId = mysqli_insert_id($con);

	

	$red = $_POST['parentId'] > 0 ? 'subCategories.php?add=1&parentId='.$_POST['parentId'] : 'subCategories.php?add=1';

	echo "<script>window.location='".$red."'</script>";
	
}

if( isset($_POST['editSub-Category']) && $_POST['editSub-Category']  && $_POST['id'] > 0)
{

	$sql = "select * FROM tbl_category WHERE name = '".trim($_POST['editSub-Category'])."' AND id != '".$_POST['id']."'  AND account_id = '".$_SESSION['accountId']."' ";

	$result = mysqli_query($con, $sql);

	$res = mysqli_fetch_array($result);

	if($res)
	{
			
		echo "<script>window.location='subCategories.php?error=1&id=".$_POST['id']."&parentId=".$_POST['parentId']."'</script>";

	}


	$sql = "UPDATE  `tbl_category` SET

	`name` = '".$_POST['editSub-Category']."'	WHERE id = '".$_POST['id']."' 
	AND account_id = '".$_SESSION['accountId']."'  ";

	mysqli_query($con, $sql);


	$red = $_POST['parentId'] > 0 ? 'subCategories.php?edit=1&parentId='.$_POST['parentId'] : 'subCategories.php?edit=1';

			
		
	echo "<script>window.location='".$red."'</script>";
	
	
}
elseif(isset($_POST['category']))
{

	$_GET['id'] = $_POST['id'];

	$error = ' '.showOtherLangText('Please enter/select all required data.').' ';

}


?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Manage Sub Categories - Queue1</title>
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
                            <h1 class="h1"><?php echo showOtherLangText('Manage Sub Categories') ?>&nbsp;<?php echo isset($catRes['name']) ? '('.$catRes['name'].')' : '' ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Manage Sub Categories') ?>&nbsp;<?php echo isset($catRes['name']) ? '('.$catRes['name'].')' : '' ?></h1>
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
                            <?php if(isset($_GET['error'])) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><?php echo isset($_GET['error']) ? ' '.showOtherLangText('Category already exist.').' ' : '';
 ?></p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php } ?>
                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="categories.php" class="btn btn-primary mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                </div>
                            </div>
                            <div class="usrAd-Btn">
                                <div class="btnBg">
                                    <a href="javascript:void(0)" class="btn btn-primary mb-usrBkbtn"
                                        data-bs-toggle="modal" data-bs-target="#add-Sub_category"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-plus"></i>
                                            <span class="nstdSpan">Sub. Category</span></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Add Sub Category'); ?></span></a>
                                </div>
                            </div>
                        </div>

                        <div class="sub-CatTable">
                            <div class="container">
                                <!-- Table Head Start -->
                                <div class="sub-CatTbl-Head align-items-center itmTable">
                                    <div class="sub-CatTbl-Cnt d-flex align-items-center">
                                        <div class="tb-head sub-CatNum-Clm">
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
                                        <div class="tb-head sub-CatName-Clm">
                                            <p><?php echo showOtherLangText('Category') ?></p>
                                        </div>
                                    </div>
                                    <div class="sub-CatTbl-Icns">
                                        <div class="tb-head outOpt-Clm text-center">
                                            <p><?php echo showOtherLangText('Options') ?></p>
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
                                    <div class="sub-CatTbl-body align-items-center itmBody">
                                        <div class="sub-CatTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy sub-CatNum-Clm">
                                                <p class="userNumber"><span class="mb-UsrSpan">No. </span><?php echo $x;?></p>
                                            </div>
                                            <div class="tb-bdy sub-CatName-Clm">
                                                <p class="userName"><?php echo $row['name'];?></p>
                                            </div>
                                        </div>
                                        <div class="catgryTbl-Icns">
                                            <div class="tb-bdy sub-CatOpt-Clm d-flex align-items-center">
                                                <a href="javascript:void(0)" data-editid="<?php echo $row['id'];?>" data-parentid="<?php echo $_GET['parentId'];?>" data-name="<?php echo $row['name'];?>" class="userLink editicon" data-bs-toggle="modal"
                                                    data-bs-target="#edit-Sub_Category">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" onClick="getDelNumb('<?php echo $row['id'];?>', '<?php echo $_GET['parentId'];?>');" class="userLink">
                                                    <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 

}

?>
                                <!-- Table Body End -->
                            </div>
                            </div>
                        </div>

                    </div>
                </section>

            </div>
        </div>
    </div>


    <!-- Add Sub-Category Popup Start -->
    <form role="form" action="" method="post" class="addUser-Form row">
    <div class="modal" tabindex="-1" id="add-Sub_category" aria-labelledby="add-Sub_categoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1"><?php echo showOtherLangText('Add Sub Category'); ?></h1>
                </div>
                <div class="modal-body">
                <input type="hidden" name="parentId" class="form-control"
                            value="<?php echo $_REQUEST['parentId'];?>">
                <input type="text" class="form-control" id="addSub-Category" name="addSub-Category" required placeholder="Sub Category*">
                </div>
                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="submit" class="btn sub-btn std-btn"><?php echo showOtherLangText('Add'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    <!-- Add Sub-Category Popup End -->

    <!-- Edit Sub-Category Popup Start -->
    <form role="form" action=""  class="addUser-Form row container glbFrm-Cont" method="post">
    <div class="modal" tabindex="-1" id="edit-Sub_Category" aria-labelledby="edit-Sub_CategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1">Edit Sub Category</h1>
                </div>
                <div class="modal-body">
                   
                    <input type="hidden" name="id" id="edit-id" value="" /> 
                    <input type="hidden" name="parentId" id="parent-id" value="" />   
                    <input type="text"required class="form-control" id="editSub-Category" value="" name="editSub-Category" placeholder="Sub Category*">
                    
                </div>
                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="submit" class="btn sub-btn std-btn">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    <!-- Edit Sub-Category Popup End -->


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
        var editparentid = this.getAttribute("data-parentid");
        $('#editSub-Category').val(editnameValue);
        $('#edit-id').val(editIdValue);
        $('#parent-id').val(editparentid);
      });
});    
 function getDelNumb(delId){
var newOnClick = "window.location.href='subCategories.php?delId=" + delId + "'";

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