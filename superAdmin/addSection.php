<?php require_once('common/header.php'); 
	checkUesrLogin();
	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		$data = [];
		$data['name']  = $_POST['name'];
		$data['type']  = $_POST['type'];
		$data['status']  = $_POST['status'];
		addSection($data);
	}
	?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-sm-2">
            <?php require_once('common/nav.php');?>
        </div>
        <div class="col-md-10 col-sm-9 col-lg-10 content-clm">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-folder" aria-hidden="true"></i>
                    <span class="panel-span">Add Section</span>
                </h3>
            </div>
            <div class="panelData-Bdy">
                <form id="form-business" action="addSection.php" method="post" enctype="multipart/form-data">
                    <div class="table-responsive" style="background-color:#fff;">
                        <table class="tb-box table">
                            <tr>
                                <td>Section : </td>
                                <td>
                                    <input type="text" name="name" id="name" required class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td>Default : </td>
                                <td>
                                    <input type="radio" name="type" id="type" value="1">
                                    <label>Yes</label>
                                    &nbsp;  &nbsp;  &nbsp;
                                    <input type="radio" name="type" id="type" value="0" checked>
                                    <label>No</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Status : </td>
                                <td>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Enabled</option>
                                        <option value="0">Disabled</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- start of group button -->
                    <div class="ftBtn-Grp">
                        <button type="submit" class="btn btn-info nglb-Btn">Add</button>
                        <button class="btn btn-info nglb-Btn"
                            onclick="window.location.href= 'manageSection.php' ">Back</button>
                    </div>
                    <!-- end of group butto -->
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once('common/footer.php'); ?>