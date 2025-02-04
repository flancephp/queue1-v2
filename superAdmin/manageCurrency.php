<?php 
require_once('common/header.php'); 
checkUesrLogin();
?>

 <div class="container-fluid">
     <div class="row">
         <div class="col-md-2 col-sm-3 col-lg-2">
             <?php require_once('common/nav.php');?>
         </div>
         <div class="col-md-10 col-sm-9 col-lg-10 content-clm">
             <div class="container-fluid">
                 <div class="pull-right"><a href="addCurrency.php" data-toggle="tooltip" title="Add"
                         class="btn btn-primary"><i class="fa fa-plus"></i></a>
                     <button type="button" data-toggle="tooltip" title="delete" class="btn btn-danger"
                         onclick="confirm('Are you sure ?') ? $('#form-currency').submit() : false;"><i
                             class="fa fa-trash-o"></i></button>
                 </div>
                 <!-- <h3>Currency</h3> -->
             </div>
             <div class="panel-heading">
                 <h3 class="panel-title"><i class="fa fa-usd" aria-hidden="true"></i>
                     <span class="panel-span">Currency</span>
                 </h3>
             </div>
             <div class="panelData-Bdy">

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
                 <div class="table-responsive">

                     <table class="table  table-bordered table-hover table-striped">

                         <tr class="bc-color">
                             <th style="width: 1px;" class="text-center"><input type="checkbox"
                                     onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                             <th scope="col">S.no.</th>
                             <th scope="col">Currency</th>
                             <th scope="col">Currency value</th>
                             <th scope="col">Status</th>
                             <th scope="col">Action</th>
                         </tr>
                         <?php if(getAllCurrency()){
					  $count =1;
            foreach(getAllCurrency() as $currency) { ?>
                         <tr>

                             <td class="text-center"><input type="checkbox" name="selected[]"
                                     value="<?php echo $business['id']; ?>" /></td>
                             <td><?php echo $count++;?></td>
                             <td><?php echo $currency['currency'];?></td>
                             <td><?php echo $currency['amt'];?></td>
                             <td><?php echo($currency['status'])?'Active':'Deactive'; ?></td>
                             <td class="text-center">
                                 <a href="editCurrency.php?id=<?php echo $currency['id']; ?>" class="btn btn-primary"
                                     data-toggle="edit" title="View"><span class="glyphicon glyphicon-edit"
                                         aria-hidden="true"></span></a>
                                 <a class="btn btn-danger delete_currency" data-id="<?php echo $currency['id']; ?>"><i
                                         class="fa fa-trash-o"></i></a>
                             </td>
                         </tr>
                         <?php } } else { ?>
                         <tr>
                             <td class="text-center" colspan="6">No results!</td>
                         </tr>
                         <?php } ?>
                     </table>

                 </div>
             </div>
         </div>

     </div>

 </div>

 <script type="text/javascript">
$(".delete_currency").on("click", function() {

    var id = $(this).attr("data-id");
    if (confirm('Are you sure ?')) {
        $(this).parent().parent("tr").remove();
        $.ajax({
            url: 'common/ajaxData.php',
            type: 'POST',
            dataType: 'json',
            data: {
                currency_id: id,
                "role": 'currencyDelete'
            },
            success: function(json) {
                $(".alert").remove();
                if (json['success']) {

                    $('.error').before(
                        '<div class="alert alert-danger alert-dismissible"><i class="fa fa-check-circle"></i> ' +
                        json['success'] +
                        '<button type="button" class="close" data-dismiss="alert">&times;</button></div>'
                    );
                }
            }
        });
    }
});
 </script>
 <?php require_once('common/footer.php'); ?>