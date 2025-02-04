<?php 
require_once('common/header.php');
include('script/addAccount.php');
checkUesrLogin();
?>

<div class="container-fluid">
  <div class="row">
  
    <div class="col-md-2 col-sm-3 col-lg-2"> <?php require_once('common/nav.php'); ?> </div>
    <div class="col-md-10 col-sm-9 col-lg-10 content-clm">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-user-plus" aria-hidden="true"></i><span class="panel-span">Add Account</span></h3>
      </div>
      <div class="form-body">
        <form action="addAccount.php" method="post" enctype="multipart/form-data">
          <div class="row">


            <?php if(isset($_SESSION['warning'])){?>
                <div class="alert alert-warning alert-dismissible"><i class="fa fa-check-circle"></i><strong>Warning!</strong> <?php echo $_SESSION['warning'];?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
              <?php unset($_SESSION['warning']); } ?>

            <!-- Start of left side form -->
            <div class="col-md-6 col-sm-6">
              <div class="txt-caption">
                <h5>Account Setup</h5>
              </div>
              <table class="table">
                <tr>
                  <td>Account Name</td>
                  <td>
                    <input type="text" id="accountName" name="accountName" value="<?php $_POST['accountName']; ?>" class="form-control"  autocomplete="new-password" required>
                  </td>
                </tr>
                <tr>
                  <td>Account Type</td>
                  <td>
                    <select id="accountType" name="accountType" class="form-control">
                      <option value="">Select</option>
                      <?php foreach($accountTypes as $key=>$accountType){

                        $sel = $_POST['accountType'] == $key ? 'selected="selected"' : '';
                      
                        echo '<option value="'.$key.'" '.$sel.'>'.$accountType.'</option>';
                            
                      } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Monthly Fee</td>
                  <td>
                    <input type="text" name="fee" class="form-control" value="<?php echo $_POST['fee'] ?>">
                  </td>
                </tr>
                <tr>
                  <td>Payment Type</td>
                  <td>
                    <select id="paymentType" name="paymentType" class="form-control">
                    <option value="">Select</option>
                      <?php foreach($paymentTypes as $key=>$paymentType){

                        $sel = $_POST['paymentType'] == $key ? 'selected="selected"' : '';

                        echo "<option value=".$key." ".$sel.">".$paymentType."</option>";

                      } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Status</td>
                  <td>
                    <select id="status" name="status" class="form-control">
                      <option value="1">Active</option>
                      <option value="0">Deactive</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Language</td>
                  <td>
                    <select id="language" name="language_id" class="form-control" required>
                      <option value="">Select</option>
                      <?php foreach(getAllLanguages() as $language){

                          $sel = $_POST['language_id'] == $language['id'] ? 'selected="selected"' : '';

                          echo "<option value=".$language['id']." ".$sel.">".$language['language_name']."</option>";
                      } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Currency</td>
                  <td>
                    <select id="currency" name="currency_id" class="form-control" required>
                    <option value="">Select</option>
                      <?php foreach(getAllCurrency() as $currency){

                          $sel = $_POST['currency_id'] == $currency['id'] ? 'selected="selected"' : '';
                          echo "<option value=".$currency['id']." ".$sel.">".$currency['currency']."</option>";
                      } ?>
                    </select>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Business Type</td>
                  <td>
                    <select id="businessType" name="businessType" class="form-control">
                      <option value="1">Select</option>
                      <?php foreach(getAllbusiness() as $business){

                          $sel = $_POST['businessType'] == $business['id'] ? 'selected="selected"' : '';
                          echo "<option value=".$business['id']." ".$sel.">".$business['name']."</option>";
                      } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Logo</td>
                  <td>
                    <input type="file" id="logo" name="logo" class="form-control" onchange="previewFile()" />
                  </td>
                </tr>

                <tr>
                  <td>&nbsp;</td>
                  <td id="showImg" style="display: none;">
                    <img src="<?php echo $_POST['logo']; ?>" width="100px">
                  </td>
                </tr>
                <!-- Ezee section start here -->
                <tr>
                  <td class="txt-caption">
                    <h5>Ezee Setup</h5>
                  </td>
                </tr>
                <tr>
                  <td>Hotel Id</td>
                  <td>
                    <input type="text" id="hotel_id" name="hotel_id" class="form-control" value="<?php echo $_POST['hotel_id'] ?>">
                  </td>
                </tr>
                <tr>
                  <td>Merchant Id</td>
                  <td>
                    <input type="text" id="merchant_id" name="merchant_id" class="form-control" value="<?php echo $_POST['merchant_id'] ?>">
                  </td>
                </tr>
                <!-- End of ezee section -->
              </table>
            </div>
            <!-- End of left side form -->
            <!-- Start of right side form -->
            <div class="col-md-6 col-sm-6">
              <div class="txt-caption">
                <h5>Account Owner Info</h5>
              </div>
              <table class="table" id="showHideExistingTable">
                <tr>
                  <td>
                    <label id="chooseClientTxt">Choose Existing Client</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    <select id="client_id" name="client_id" id="chooseClient" class="form-control">
                      <option value="">Select Existing Client</option>
                      <?php foreach(getAllOwnerInfo() as $ownerInfo){

                        $sel = $_POST['client_id'] == $ownerInfo['account_id'] ? 'selected="selected"' : '';

                        echo "<option value=".$ownerInfo['account_id']." ".$sel.">".$ownerInfo['name']."</option>";
                      } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td class="text-center m-all">
                    <label>OR</label>
                  </td>
                <tr>
                <tr>
                  <td class="text-center m-all ml-o pd-o">
                    <label class="addClientText" id="addClientText">+ADD NEW CLIENT</label>
                  </td>
                </tr>
              </table>
              <div>
                <hr class="sprtrAcnt">
              </div>
              <!-- Hidden form section start from here -->
              <div id="second-form" class="second-form" style="display:none;">
                <!-- close form icon -->
                <div class="close-form">&#10006;</div>
                <table class="table">
                  <tr>
                    <td>Name</td>
                    <td>
                      <input type="text" id="name" name="name" class="form-control" value="<?php echo $_POST['name'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <td>Address 1</td>
                    <td>
                    <input type="text" id="addressStreet1" name="addressStreet1" class="form-control" value="<?php echo $_POST['addressStreet1'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <td>Address 2</td>
                    <td>
                    <input type="text" id="addressStreet2" name="addressStreet2" class="form-control" value="<?php echo $_POST['addressStreet2'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <td>Zip</td>
                    <td>
                      <input type="text" id="zip" name="zip" class="form-control" value="<?php echo $_POST['zip'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <td>City</td>
                    <td>
                      <input type="text" id="city" name="city" class="form-control" value="<?php echo $_POST['city'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <td>Country</td>
                    <td>
                      <select id="country" name="country" class="form-control">
                        <option value="">Select</option>
                        <?php foreach(getAllcountries() as $country){

                          $sel = $_POST['country'] == $country['id'] ? 'selected="selected"' : '';

                          echo "<option value=".$country['id']." ".$sel.">".$country['name']."</option>";
                        } ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Username</td>
                    <td>
                      <input type="text" id="username" name="username" class="form-control" value="<?php echo $_POST['username'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <td>Password</td>
                    <td>
                      <input type="password" id="password" name="password" class="form-control" value="<?php echo $_POST['password'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <td>Email</td>
                    <td>
                      <input type="text" id="email" name="email" class="form-control" value="<?php echo $_POST['email'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <td>Phone</td>
                    <td>
                      <input type="text" id="phone" name="phone" class="form-control" value="<?php echo $_POST['phone'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <td>Tax Id</td>
                    <td>
                      <input type="text" id="taxId" name="taxId" class="form-control" value="<?php echo $_POST['taxId'] ?>">
                    </td>
                  </tr>
                </table>
              </div>
              <!-- Hidden form section end here -->
              <!-- section area start here -->
              <div class="txt-caption checkboxSection">
                <table style="width: max-content;">
                  <div class="txt-caption">
                    <h5>Sections</h5>
                  </div>
                  <tr>
                      <td>
                        <input type="checkbox" name="checked_user" id="checked_user" value="1"  />&nbsp; <h5 class="fade-color">Max User</h5>&nbsp;&nbsp;&nbsp;
                        <input type="text" name="max_user" id="max_user" value="1" style="width:36px" disabled/>
                      </td>
      
                    </tr>
                  <?php foreach(getAllsections() as $sections){?>  
                    <tr>
                      <td>
                        <input type="checkbox" name="sections[]" value="<?php echo $sections['id']; ?>" <?php if($sections['is_defalt']){ echo "checked"; } ?> />&nbsp; <h5 class="fade-color"><?php echo $sections['name']; ?></h5>
                      </td>
                    </tr>
                      <?php  } ?>
                </table>
              </div>
              <!-- end of section area -->
            </div>
            <!-- start of group button -->
            <div class="col-md-12 col-sm-12 button-group">
              <a class="btn btn-info fl-left nglb-Btn" href="accountsManager.php">Back</a>
              <input type="submit" name="submitBtn" class="btn btn-info fl-right nglb-Btn" value="Done" />
            </div>
            <!-- end of group butto -->
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of HTML part -->

<script>
//show add new client form--------------

$("#checked_user").click(function(){
 if($(this).prop('checked')==true){
  $("#max_user").attr('disabled', false);
 } else {
  $("#max_user").attr('disabled', true)
 }
});

$(document).ready(function(){

   if($(this).prop('checked')==true){
    $("#max_user").attr('disabled', false);
   }
   else
   {
    $("#max_user").attr('disabled', true)
   }

  //user click on add new client text
  $('#addClientText').click(function(){
    //Hide choose existing client text and their dropdown And add new client text 
    $('#showHideExistingTable, #chooseClientTxt, .sprtrAcnt').css("display", "none");
    $('#client_id').val('');//reset value of dropdown
    $('#second-form').css("display", "block");//add new client form open
  });

  $('.close-form').click(function(){

    $('#second-form').css("display", "none");

    $('#showHideExistingTable, #chooseClientTxt').css("display", "table");

    $('.sprtrAcnt').css("display", "block");

    $('#name').val('');
    $('#addressStreet1').val('');
    $('#addressStreet2').val('');
    $('#zip').val('');
    $('#city').val('');
    $('#country').val('');
    $('#username').val('');
    $('#password').val('');
    $('#email').val('');
    $('#phone').val('');
    $('#taxId').val('');
  });
});//End
</script>

<script>
function previewFile() {
  $('#showImg').css('display','block');
  var preview = document.querySelector('img');
  var file = document.querySelector('input[type=file]').files[0];
  var reader = new FileReader();

  reader.onloadend = function() {
    preview.src = reader.result;
  }

  if (file) {
    reader.readAsDataURL(file);
  } 
  else
  {
    preview.src = "";
  }

}
</script>