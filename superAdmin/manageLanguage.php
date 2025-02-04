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
            <div class="pull-right">

                <button data-toggle="tooltip" title="Add Language" class="btn btn-primary"
                    onclick="window.location.href= 'addLanguage.php' "><i class="fa fa-plus"></i></button>
                <button data-toggle="tooltip" title="Back" class="btn btn-default"
                    onclick="window.location.href= 'accountsManager.php' "><i class="fa fa-reply"></i></button>

            </div>

            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-language" aria-hidden="true"></i>
                    <span class="panel-span">Manage Language</span>
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

                    <table class="table table-bordered">

                        <tr class="bc-color">
                            <th scope="col">Serial no.</th>
                            <th scope="col">Language</th>
                            <th scope="col">Language Type</th>
                            <th scope="col">Action</th>
                        </tr>

                        <?php
            $fetchTableAllRowData = fetchTableDetailsWithoutCond('tbl_language');
            $x = 0;
            while ($row = mysqli_fetch_array($fetchTableAllRowData)) 
            {
              $x++;
              $languageType = $row['language_type'] == '1' ? 'Right' : 'Left';
              ?>

                        <tr>
                            <td><?php echo $x ?></td>
                            <td><?php echo $row['language_name'] ?></td>
                            <td><?php echo $languageType ?></td>
                            <td class="action">
                                <a href="editLanguage.php?id=<?php echo $row['id'];?>" class="btn btn-primary" data-toggle="edit" title="Edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>

                                <a class="btn btn-danger delete_language" language_id="<?php echo $row['id']; ?>"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>


                        <?php
            }
            ?>
                    </table>

                </div>
            </div>
        </div>

    </div>

</div>

<script type="text/javascript">
  $(".delete_language").on("click",function (){
    
   var language_id =  $(this).attr("language_id");
   if(confirm('Are you sure ?')){
    $(this).parent().parent("tr").remove();
    $.ajax({
      url: 'common/ajaxData.php',
      type: 'POST',
      dataType: 'json',
      data:{language_id:language_id,"role":'languageDelete'},
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