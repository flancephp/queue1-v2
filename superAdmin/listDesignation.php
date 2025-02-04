<?php 
require_once('common/header.php');
checkUesrLogin();


if( isset($_GET['delId']) && $_GET['delId'] > 0 )
{
    $sql = " SELECT * FROM tbl_user WHERE account_id = '".$_GET['account_id']."' AND designation_id = '".$_GET['delId']."' ";
    $checkUserRes = mysqli_query($con, $sql);
    $checkUserRow = mysqli_fetch_array($checkUserRes);
    if ($checkUserRow > 0) {

        $_SESSION['warning'] = "Can't delete designation";
        echo '<script>window.location="listDesignation.php?account_id='.$_GET['account_id'].' "</script>';
        die;
    }
    else
    {
        $sqlQry = " DELETE FROM tbl_designation WHERE id = '".$_GET['delId']."' ";
        mysqli_query($con, $sqlQry);

        $sqlQry = " DELETE FROM tbl_designation_section_permission WHERE designation_id = '".$_GET['delId']."' ";
        mysqli_query($con, $sqlQry);

        $sqlQry = " DELETE FROM tbl_designation_sub_section_permission WHERE designation_id = '".$_GET['delId']."' ";
        mysqli_query($con, $sqlQry);
        $_SESSION['deleted'] = "Designation Successfully deleted";
        echo '<script>window.location="listDesignation.php?account_id='.$_GET['account_id'].' "</script>';
        die;
    }
}

$sqlQry = " SELECT * FROM tbl_designation WHERE account_id =  '".$_GET['account_id']."' ";
$designationRes = mysqli_query($con, $sqlQry);

?>    
<div class="container-fluid">
<div class="row">
<div class="col-md-2">
<?php require_once('common/nav.php');?>
</div>
<div class="col-md-10 newClm-10">
<div class="panel panel-default">
<div class="panel-heading">
<div class="pull-right" style="margin: -8px 0px 0px 0px;">
    <a href="addDesignation.php?account_id=<?php echo $_GET['account_id']; ?>" data-toggle="tooltip" title="Add" class="btn btn-primary"><i class="fa fa-plus"></i></a> 
    <a href="accountsManager.php" data-bs-toggle="tooltip" title="" class="btn btn-info" data-bs-original-title="Back" aria-label="Back"><i class="fa fa-reply"></i></a>
</div>
<h3 class="panel-title">Manage Designations</h3>
</div>

<div class="panel-body">

<?php 
  if( isset($_SESSION['added']) || isset($_SESSION['updated']) || isset($_SESSION['warning']) || isset($_SESSION['deleted']) )
  {
    if(isset($_SESSION['added']))
    {
      $successMsg = $_SESSION['added'];
      $color = "alert-success";
    }
    elseif ( isset($_SESSION['updated']) )
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
        <!-- Alert Message part start here	 -->
        <table class="table table-striped" cellpadding="5" cellspacing="5">
            <thead>
                <tr>
                    <th>S. no.</th>
                    <th>Designation Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
            $x = '';
            while ($designationRow = mysqli_fetch_array($designationRes)) 
            {
                $x++;
                $designationName = $designationRow['designation_name'];
                ?>

                <tr>
                    <td><?php echo $x; ?></td>
                    <td><?php echo $designationName; ?></td>
                    <td>
                        <a
                            href="editDesignation.php?id=<?php echo $designationRow['id'];?>&account_id=<?php echo $_GET['account_id'];?>">Edit</a>
                        |

                        <a href="listDesignation.php?delId=<?php echo $designationRow['id'];?>&account_id=<?php echo $_GET['account_id'];?>"
                            onClick="return confirm('Are you sure ?')">Delete</a>
                    </td>

                </tr>


                <?php
            }
            ?>

            </tbody>
        </table>
    </div>
</div>
</div><!-- panel body -->
</div>
</div><!-- content -->
</div>
</div>

<?php require_once('common/footer.php');?>