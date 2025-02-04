<?php
require_once('common/header.php');
checkUesrLogin();

$clientDet = getClientDetById($_GET['account_id']);

if(isset($_GET['delId']) && $_GET['delId'] > 0)
{
	$sql = "SELECT * FROM tbl_orders WHERE orderBy ='".$_GET['delId']."' AND account_id = '".$_GET['account_id']."'  ";
	$sqlResult = mysqli_query($con, $sql);

	if (mysqli_num_rows($sqlResult) > '0') 
	{
        $_SESSION['warning'] = "Can't delete few order has been made by this user";
		echo "<script>window.location='listStorageUsers.php?account_id=".$_GET['account_id']." '</script>";
        die;
	}
	else
	{
		$sql = "DELETE FROM tbl_user WHERE id='".$_GET['delId']."' AND account_id = '".$_GET['account_id']."'  ";
		mysqli_query($con, $sql);
		
        $_SESSION['deleted'] = "User has been successfully deleted";
		echo "<script>window.location='listStorageUsers.php?account_id=".$_GET['account_id']." '</script>";
        die;
	}
	
}

$sql = "SELECT * FROM tbl_user WHERE isAdmin = 0 AND account_id = '".$_GET['account_id']."'  ";
$result = mysqli_query($con, $sql);
$countRow = mysqli_num_rows($result);

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php require_once('common/nav.php');?>
        </div>
        <div class="col-md-10 col-sm-9 col-lg-10 content-clm">
            <div class="pull-right">
                <?php
                if ($clientDet['max_user'] == $countRow) 
                {
                    ?>
                    <a onclick="return confirm('You can not add user. Your limit of max user has been exceeded');" data-toggle="tooltip" title="Add User" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                    <?php
                }
                else
                {
                    ?>
                    <a href="addStoreUser.php?account_id=<?php echo $_GET['account_id']; ?>" data-toggle="tooltip" title="Add User" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                    <?php
                }
                ?>
                
                <a href="accountsManager.php" data-bs-toggle="tooltip" title="Back" class="btn btn-default" data-bs-original-title="Back" aria-label="Back"><i class="fa fa-reply"></i></a>
            </div>


            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-users" aria-hidden="true"></i>
                    <span class="panel-span">Users</span>
                </h3>
            </div>

            <div class="panelData-Bdy">
                <?php 
                  if( isset($_SESSION['added']) || isset($_SESSION['updated']) || isset($_SESSION['warning']) || isset($_SESSION['deleted']) )
                  {
                    if(isset($_SESSION['added']))
                    {
                      $successMsg = $_SESSION['added'];
                      $color = "alert-success";
                    }
                    elseif (isset($_SESSION['updated']))
                    {
                      $successMsg = $_SESSION['updated'];
                      $color = "alert-success";
                    }
                    elseif (isset($_SESSION['warning']))
                    {
                      $successMsg = $_SESSION['warning'];
                      $color = "alert-warning";
                    }
                    elseif (isset($_SESSION['deleted']))
                    {
                      $successMsg = $_SESSION['deleted'];
                      $color = "alert-danger";
                    }

                      ?>
                      <div class="alert <?php echo $color; ?> alert-dismissible"><i class="fa fa-check-circle"></i><strong>Success!</strong> <?php echo $successMsg;?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
                      <?php 
                      unset($_SESSION['added']);
                      unset($_SESSION['updated']);
                      unset($_SESSION['warning']);
                      unset($_SESSION['deleted']); 
                  } 

                  ?>
                <div class="content-row">
                    <div class="row table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>s. no.</th>
                                    <th>Name</th>
                                    <th>Designation Title</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
								$x= 0;
								while($row = mysqli_fetch_array($result))
								{
									$color = ($x%2 == 0)? 'white': '#FFFFCC';
									$x++;

									$sql = " SELECT * FROM tbl_designation WHERE id = '".$row['designation_id']."' AND account_id = '".$_GET['account_id']."' ";
									$resSet = mysqli_query($con, $sql);
									$resRow = mysqli_fetch_array($resSet);
									$designationName = $resRow['designation_name']
									?>
                                <tr>
                                    <td><?php echo $x;?></td>
                                    <td><?php echo $row['username'];?></td>
                                    <td><?php echo $row['designation_id'] > 0 ? $designationName :  'Mobile';?>
                                    </td>
                                    <td>
                                        <a href="editStoreUser.php?id=<?php echo $row['id'];?>&account_id=<?php echo $_GET['account_id'] ?>" class="btn btn-primary" data-toggle="edit" title="Edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>

                                        <a class="btn btn-danger delete_client" title="Delete" href="listStorageUsers.php?delId=<?php echo $row['id'];?>&account_id=<?php echo $_GET['account_id'] ?>"onClick="return confirm('Are you sure ?');">
                                            <i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                                <?php 
								}
								?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('common/footer.php');?>