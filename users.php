<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

if ( !isset($_SESSION['adminidusername']))
{
echo "<script>window.location='login.php'</script>";
}

$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = 'user' AND type_id = '0' AND designation_id = '".$_SESSION['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
$permissionRes = mysqli_query($con, $sql);
$permissionRow = mysqli_fetch_array($permissionRes);
if ($permissionRow)
{
echo "<script>window.location='index.php'</script>";
}

if( isset($_GET['delId']) && $_GET['delId'] )
{

    $sql = "SELECT * FROM tbl_orders WHERE orderBy ='".$_GET['delId']."' AND account_id = '".$_SESSION['accountId']."'  ";
    $sqlResult = mysqli_query($con, $sql);

    $sql = "SELECT * FROM tbl_mobile_time_track WHERE userId ='".$_GET['delId']."' AND account_id = '".$_SESSION['accountId']."'  ";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($sqlResult) > '0' || mysqli_num_rows($result) > '0') 
    {
        echo "<script>window.location='users.php?error=1'</script>";
    }
    else
    {
        $sql = "DELETE FROM tbl_user WHERE id='".$_GET['delId']."' AND account_id = '".$_SESSION['accountId']."'  ";
        mysqli_query($con, $sql);

        echo "<script>window.location='users.php?delete=1'</script>";
    }

}

$sql = "SELECT * FROM tbl_user WHERE isAdmin = 0 AND isOwner = 0 AND account_id = '".$_SESSION['accountId']."'  ";
$result = mysqli_query($con, $sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Users - Queue1</title>
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
                            <h1 class="h1">Users</h1>
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
                                    <h1 class="h1">Users</h1>
                                </div>
                            </div>
                            <div class="user d-flex align-items-center">
                                <img src="Assets/images/user.png" alt="user">
                                <p class="body3 m-0 d-inline-block"><?php echo showOtherLangText('Storage Users') ?></p>
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
                <div class="alrtMessage">
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
                        </div>
                    </div>
                    <div class="container">
                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="setup.php" class="sub-btn std-btn mb-usrBkbtn"><span class="mb-UsrBtn"><i
                                                class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn">Back</span></a>
                                </div>
                            </div>
                            <div class="usrAd-Btn">
                                <div class="btnBg">
                                    <a href="addUser.php" class="sub-btn std-btn mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><img src="Assets/icons/add-user.svg" alt="Add User"
                                                class="ad-UsrImg"></span>
                                        <span class="dsktp-Btn">Add User</span></a>
                                </div>
                            </div>
                        </div>

                        <div class="usrTable">
                            <div class="container">
                                <!-- Table Head Start -->
                                <div class="usrTbl-Head align-items-center itmTable">
                                    <div class="usrTbl-Cnt d-flex align-items-center">
                                        <div class="tb-head usrNum-Clm">
                                            <div class="d-flex align-items-center">
                                                <p>#</p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="tb-head usrName-Clm">
                                            <p><?php echo showOtherLangText('Name') ?></p>
                                        </div>
                                        <div class="tb-head usrTtl-Clm">
                                            <p><?php echo showOtherLangText('Designation Title') ?></p>
                                        </div>
                                        <div class="tb-head usrTtl-Clm">
                                            <p><?php echo showOtherLangText('User Type') ?></p>
                                        </div>
                                    </div>
                                    <div class="usrTbl-Icns">
                                        <div class="tb-head usrOpt-Clm text-center">
                                            <p><?php echo showOtherLangText('Actions') ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php 
				$x= 0;

				while($row = mysqli_fetch_array($result))
				{
					$color = ($x%2 == 0)? 'white': '#FFFFCC';
					$x++;

					$sql = " SELECT * FROM tbl_designation WHERE id = '".$row['designation_id']."' AND account_id = '".$_SESSION['accountId']."' ";
					$resSet = mysqli_query($con, $sql);
					$resRow = mysqli_fetch_array($resSet);
					$designationName = $resRow['designation_name']
					?>
                                <div class="userTask">
                                    <div class="usrTbl-body align-items-center itmBody">
                                        <div class="usrTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy usrNum-Clm">
                                                <p class="userNumber"><span class="mb-UsrSpan">No. </span><?php echo $x;?></p>
                                            </div>
                                            <div class="tb-bdy usrName-Clm">
                                                <p class="userName"><?php echo $row['username'];?><?php echo $row['password'];?></p>
                                            </div>
                                            <div class="tb-bdy usrTtl-Clm">
                                                <p class="userTittle"><?php echo $designationName;?></p>
                                            </div>
                                            <div class="tb-bdy usrTtl-Clm">
                                                <p class="userTittle"><?php echo $row['userType'] == 0 ? 'Web User' : 'Mobile User' ;?></p>
                                            </div>
                                        </div>
                                        <div class="usrTbl-Icns">
                                            <div class="tb-bdy usrOpt-Clm d-flex align-items-center">
                                                <a href="editUser.php?id=<?php echo $row['id'];?>" class="userLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" onClick="getDelNumb('<?php echo $row['id'];?>');" class="userLink">
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
                </section>

            </div>
        </div>
    </div>
    <div id="dialog" style="display: none;">
    <?php echo showOtherLangText('Are you sure to delete this record?') ?>  
   </div>
    <?php require_once('footer.php');?>
    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
   <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
</body>

</html>
<script>  
 function getDelNumb(delId){

    $( "#dialog" ).dialog({  
        autoOpen  : false,
        modal     : true,
        //title     : "Title",
        buttons   : {
          '<?php echo showOtherLangText('Yes') ?>' : function() {
            //Do whatever you want to do when Yes clicked
            $(this).dialog('close');
            window.location.href='users.php?delId='+delId;
          },

          '<?php echo showOtherLangText('No') ?>' : function() {
            //Do whatever you want to do when No clicked
            $(this).dialog('close');
          }
       }    
    });

    $( "#dialog" ).dialog( "open" );
    $('.custom-header-text').remove();
    $('.ui-dialog-content').prepend('<div class="custom-header-text"><span><?php echo showOtherLangText('Queue1.com Says') ?></span></div>');
}  
</script>