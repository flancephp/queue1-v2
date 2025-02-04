<?php 
require_once('common/header.php');
checkUesrLogin();
?>
 <div class="container-fluid">
  <div class="row">
    <div class="col-md-2 col-sm-3 col-lg-2">
      <?php require_once('common/nav.php');?>
    </div>
    <div class="col-md-10 col-sm-9 col-lg-10">
      <div id="content">
          <div class="container-fluid">
            <div class="pull-right"><a href="addAccount.php" data-toggle="tooltip" title="Add" class="btn btn-primary"><i class="fa fa-plus"></i></a> 
              <button type="button" data-toggle="tooltip" title="delete" class="btn btn-danger" onclick="confirm('Are you sure ?') ? $('#form-category').submit() : false;"><i class="fa fa-trash-o"></i></button>
            </div>
          </div>
        <div class="container-fluid">
          
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><i class="fa fa-list"></i><span class="panel-span">Account</span></h3>
            </div>
            <div class="panel-body">
              <?php 
              if( isset($_SESSION['added']) || isset($_SESSION['updated']) )
              {
                if(isset($_SESSION['added']))
                {
                  $successMsg = $_SESSION['added'];
                }
                elseif ( isset($_SESSION['updated']) )
                {
                  $successMsg = $_SESSION['updated'];
                }
                  ?>
                  <div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i><strong>Success!</strong> <?php echo $successMsg;?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
                  <?php 
                  unset($_SESSION['added']);
                  unset($_SESSION['updated']); 
              } 

              ?>
              <div class="error"></div>
              <form method="post" enctype="multipart/form-data" id="form-account">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover table-striped">
                    <thead>
                      <tr>
                      <th style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                      <th>S. no.</th>
                      <th>Account no.</th>
                      <th>Account Name</th>
                      <th>Client Name</th>
                      <th>No. of users</th>
                      <th>Status</th>
                      <th>Join Date</th>
                      <th>Total Activity</th>
                      <th>Account Type</th>
                      <th>Fee</th>
                      <th>Payment Type</th>
                      <th>Balance</th>
                      <th>Action</th>
                      <th>Users/ designation</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php $count=1; 
                      if(getAllclients()){
                      foreach(getAllclients() as $clients) { ?>
                        <tr>
                        <td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $clients['id']; ?>"/></td>
                        <td class="text-center"><?php echo $count; ?></td>
                        <td class="text-center"><?php $count++; echo $clients['accountNumber']; ?></td>
                        <td class="text-center"><?php echo $clients['accountName']; ?></td>
                        <td class="text-center"><?php echo $clients['name']; ?></td>

                        <td class="text-center"><?php echo $clients['max_user']; ?></td>
                        <td class="text-center"><?php foreach($status as $key=>$sts){ if($clients['status']==$key){ echo $sts; }} ?></td>
                        <td class="text-center"><?php echo $clients['created_date']; ?></td>
                        <td class="text-center">Total Activity</td>
                        <td class="text-center"><?php foreach($accountTypes as $key=>$accountType){ if($clients['accountType']==$key){ echo $accountType; }}  ?></td>
                        <td class="text-center"><?php echo $clients['fees']; ?></td>
                        <td class="text-center"><?php foreach($paymentTypes as $key=>$paymentType){ if( $clients['paymentType']==$key){ echo $paymentType; }}  ?></td>
                        <td class="text-center"><?php echo $clients['fees']; ?></td>
                        <td class="text-center">
                           <a href="editAccount.php?account_id=<?php echo $clients['id']; ?>" class="btn btn-primary"  data-toggle="edit" title="View"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> 
                          <a class="btn btn-danger delete_client" client_id="<?php echo $clients['id']; ?>"><i class="fa fa-trash-o"></i></a>
                        </td>
                        <td class="text-center">

                        <a href="listStorageUsers.php?account_id=<?php echo $clients['id']; ?>" class="btn btn-primary"  data-toggle="tooltip" title="User" ><span class="fa fa-users" aria-hidden="true"></span></a>
                        <a href="listDesignation.php?account_id=<?php echo $clients['id']; ?>" class="btn btn-primary"  data-toggle="tooltip" title="Designation" ><span class="fa fa-list" aria-hidden="true"></span></a>
                        </td>
                      </tr>
                      <?php } }
                      else { ?>
                      <tr>
                        <td class="text-center" colspan="4">No results!</td>
                      </tr>
                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

</div>
<script type="text/javascript">
  $(".delete_client").on("click",function (){
    
   var client_id =  $(this).attr("client_id");
   if(confirm('Are you sure ?')){
    $(this).parent().parent("tr").remove();
    $.ajax({
      url: 'common/ajaxData.php',
      type: 'POST',
      dataType: 'json',
      data:{cient_id:client_id,"role":'clientDelete'},
      success: function(json){
        $(".alert").remove();
        if(json['success']){
          
				  $('.error').before('<div class="alert alert-danger alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
      }
    });
   }
  });
</script>

<?php require_once('common/footer.php'); ?>