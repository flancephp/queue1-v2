<?php require_once('common/header.php');
checkUesrLogin();
  if(isset($_GET['id'])){
   $section = getSectionById($_GET['id']);
   if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
      $data = [];
      $data['name']  = $_POST['name'];
      $data['type']  = $_POST['type'];
      $data['status']  = $_POST['status'];
      editSection($data,$_GET['id']);
    }
  }
 ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-sm-2">
            <?php require_once('common/nav.php');?>
        </div>

        <div class="col-md-10 col-sm-9 col-lg-10 content-clm">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil" aria-hidden="true"></i>
                    <span class="panel-span">Edit Section</span>
                </h3>
            </div>
            <div class="panelData-Bdy">
                <form id="form-section" action="editSection.php?id=<?php echo $_GET['id']; ?>" method="post"
                    enctype="multipart/form-data">
                    <div class="table-responsive">
                        <table class="tb-box table">
                            <tr>
                                <td>Section : </td>
                                <td>
                                    <input type="text" name="name" id="name" value="<?php echo $section['name']; ?>"
                                        class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td>Default : </td>
                                <td>
                                    <input type="radio" name="type" id="type" value="1"
                                        <?php if($section['is_default']==1){ echo "checked"; } ?>>
                                    <label>Yes</label>
                                    &nbsp; &nbsp; &nbsp;
                                    <input type="radio" name="type" id="type" value="0"
                                        <?php if($section['is_default']==0){ echo "checked"; } ?>>
                                    <label>No</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Status : </td>
                                <td>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" <?php if($section['status']==1){ echo "selected"; } ?>>Enabled
                                        </option>
                                        <option value="0" <?php if($section['status']==0){ echo "selected"; } ?>>
                                            Disabled</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- start of group button -->
                    <div class="ftBtn-Grp">
                        <button type="submit" class="btn btn-info nglb-Btn">Update</button>
                        <button class="btn btn-info nglb-Btn" type="button" 
                            onclick="window.location.href= 'manageSection.php' ">Back</button>
                    </div>
                    <!-- end of group butto -->
                </form>
            </div>
        </div>
    </div>


</div>

</div>

</body>

</html>