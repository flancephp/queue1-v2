<?php require_once('common/header.php'); 
checkUesrLogin();
  if(isset($_GET['id'])){
    $business = getBusinessById($_GET['id']);
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
       $data = [];
       $data['name']  = $_POST['name'];
       $data['status']  = $_POST['status'];
       editBusiness($data,$_GET['id']);
     }
  }
  ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-sm-3 col-lg-2">
            <?php require_once('common/nav.php');?>
        </div>

        <div class="col-md-10 col-sm-9 col-lg-10 content-clm">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil" aria-hidden="true"></i>
                    <span class="panel-span">Edit Business</span>
                </h3>
            </div>
            <div class="panelData-Bdy">
                <form id="form-section" action="editBusiness.php?id=<?php echo $_GET['id']; ?>" method="post"
                    enctype="multipart/form-data">
                    <div class="table-responsive">
                        <table class="tb-box table">
                            <tr>
                                <td>Business : </td>
                                <td>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="<?php echo $business['name']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>Status : </td>
                                <td>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" <?php if($business['status']==1){ echo "selected"; } ?>>
                                            Enabled</option>
                                        <option value="0" <?php if($business['status']==0){ echo "selected"; } ?>>
                                            Disabled</option>
                                    </select>
                                </td>
                            </tr>


                        </table>
                    </div>
                    <!-- start of group button -->
                    <div class="ftBtn-Grp">
                        <button type="submit" class="btn btn-info nglb-Btn">Update</button>
                        <a href="manageBusiness.php" class="btn btn-info nglb-Btn">Back</a>
                    </div>
                    <!-- end of group butto -->
                </form>
            </div>
        </div>


    </div>

</div>


<?php require_once('common/footer.php'); ?>