<?php 

if (isset($_POST['submitBtn']))
{
  $accountName = $_POST['accountName'];
  $accountType = $_POST['accountType'];
  $paymentType = $_POST['paymentType'];
  $fee = $_POST['fee'];
  $status = $_POST['status'];
  $language_id = $_POST['language_id'];
  $currency_id = $_POST['currency_id'];
  $businessType = $_POST['businessType'];
  $hotel_id = $_POST['hotel_id'];
  $merchant_id = $_POST['merchant_id'];

  $logo = '';
  if($_FILES["logo"]["name"] != '')
  {
    $imgFolderName = str_replace(' ', '', $accountName);
    $target_dir = dirname(__FILE__)."/uploads/";

    $target_dir = str_replace('\superAdmin\script', '', $target_dir);

    $target_dir = str_replace('/superAdmin/script', '', $target_dir);

    $target_dir = $target_dir.''.$imgFolderName.'/clientLogo/';

    $fileName = time(). basename($_FILES["logo"]["name"]);
    $target_file = $target_dir . $fileName;
    move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file);         
    resize_image($target_file, $target_file, 100,100);
    $logo  = $fileName;
  }

  $client_id = $_POST['client_id'];
  
  if($_POST['client_id'] == '')
  {
    $name = $_POST['name'];
    $address_one = $_POST['addressStreet1'];
    $address_two = $_POST['addressStreet2'];
    $zipCode = $_POST['zip'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $taxId = $_POST['taxId'];
    $userName = $_POST['username'];
    $password = $_POST['password'];
  }
  elseif($_POST['client_id'] > 0)
  {
    $client_id = getClientDetById($_POST['client_id']);
    $name = $client_id['name'];
    $address_one = $client_id['address_one'];
    $address_two = $client_id['address_two'];
    $zipCode = $client_id['zipCode'];
    $city = $client_id['city'];
    $country = $client_id['country'];
    $email = $client_id['email'];
    $phone = $client_id['phone'];
    $taxId = $client_id['taxId'];

    $userDet = getUserDetByClientEmail($email);
    $userName = $userDet['username'];
    $password = $userDet['password'];
  }

  $max_user = $_POST['max_user'];

  //check if accountName are already exist in our record then throw error msg to the user.
  $sql = " SELECT * FROM tbl_client WHERE `accountName` = '".$accountName."' ";
  $result = mysqli_query($con,$sql);
  $count = mysqli_num_rows($result);

  if ($count > 0) 
  {
    $_SESSION['warning'] = "This account name already exist in our records";
    echo "<script>window.location='addAccount.php'</script>";
    die;
  }

  $sql = "INSERT INTO `tbl_client` SET 
  `accountName` = '".$accountName."',
  `accountType` = '".$accountType."',
  `fees` = '".$fee."',
  `paymentType` = '".$paymentType."',
  `status` = '".$status."',
  `language_id` = '".$language_id."',
  `currency_id` = '".$currency_id."',
  `businessType` = '".$businessType."',
  `name` = '".$name."',
  `address_one` = '".$address_one."',
  `address_two` = '".$address_two."',
  `zipCode` = '".$zipCode."',
  `city` = '".$city."',
  `country` = '".$country."',
  `email` = '".$email."', 
  `phone` = '".$phone."',
  `logo` = '".$logo."',
  `image_folder_name` = '".str_replace(' ', '', $accountName)."',
  `taxId` = '".$taxId."',
  `max_user` = '".$max_user."'";
  mysqli_query($con, $sql);
  $last_id = mysqli_insert_id($con);

  $accountNumber = '100'.($last_id);
  $sql = " UPDATE tbl_client SET `accountNumber` = '".$accountNumber."' WHERE id = '".$last_id."' ";
  mysqli_query($con, $sql);

  $sql = "INSERT INTO `tbl_user` SET 
  `account_id` = '".$last_id."',
  `name` = '".$name."',
  `email` = '".$email."',
  `username` = '".$userName."',
  `password` = '".$password."',
  `isOwner` = '1',
  `isAdmin` = '1',
  `status` = '1'";
  mysqli_query($con, $sql);
  
  foreach($_POST['sections'] as $section){
    $sql = "INSERT INTO `tbl_client_section_permission` SET 
    `section_id` = '".$section."',
    `account_id` = '".$last_id."'";
    mysqli_query($con, $sql);
  }

  $sql = "INSERT INTO `tbl_client_ezee_setup` SET 
  `account_id` = '".$last_id."',
  `hotel_id` = '".$_POST['hotel_id']."',
  `merchant_id` = '".$_POST['merchant_id']."',
  `status` = '1'";
  mysqli_query($con, $sql);

  $_SESSION['added'] = "Client has been successfully added";

  echo "<script>window.location='accountsManager.php'</script>";
  
}


?>