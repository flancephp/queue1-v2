<?php

//Main query to display all details from database in page.
$sql = "SELECT *,
cl.phone As clientPhone,
cl.name As clientName,
cl.logo As clientLogo,
cl.status As clientStatus 
FROM tbl_client cl 
  LEFT JOIN tbl_user tu 
    ON (cl.id = tu.account_id) 
  LEFT JOIN tbl_client_ezee_setup ces 
    ON (tu.account_id = ces.account_id) 
WHERE cl.id='".$_GET['account_id']."' AND tu.isAdmin=1";
$execute = mysqli_query($con, $sql);
$clients = mysqli_fetch_array($execute);

$sql = "SELECT * FROM tbl_client_section_permission
WHERE account_id='".$_GET['account_id']."'";
$execute = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($execute)) 
{
  $sectionRows[] = $row;
}
///////////////////////////////////////////////////////

//start updating all the related table

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $accountName = $_POST['accountName'];
  $accountType = $_POST['accountType'];
  $paymentType = $_POST['paymentType'];
  $status = $_POST['status'];
  $language_id = $_POST['language_id'];
  $currency_id = $_POST['currency_id'];
  $fee = $_POST['fee'];
  $businessType = $_POST['businessType'];
  $hotel_id = $_POST['hotel_id'];
  $merchant_id = $_POST['merchant_id'];
  $client_id = $_POST['client_id'];

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
    $logo = $fileName;

    $clientDet = getClientDetById($_GET['account_id']);//fetch client detail

    //Delete previous logo of this client if another logo uploaded again by client
    if($clientDet['logo'] != '' && file_exists($target_dir.$clientDet['logo']) )
    {
      @unlink($target_dir.$clientDet['logo']);
    }
  }
  else
  {
    $clientDet = getClientDetById($_GET['account_id']);//fetch client detail
    if ($clientDet['logo'] != '') {
      $logo = $clientDet['logo'];
    }

  }

  	$addressStreet1 = $_POST['addressStreet1'];
  	$addressStreet2 = $_POST['addressStreet2'];
  	$zipCode = $_POST['zipCode'];
  	$city = $_POST['city'];
  	$country = $_POST['country'];
  	$email = $_POST['email'];
  	$phone = $_POST['phone'];
  	$taxId = $_POST['taxId'];
  	$userName = $_POST['username'];
  	$password = $_POST['password'];
  	$max_user = $_POST['max_user'];


  	$sql = "UPDATE `tbl_client` SET
  	`accountName` = '".$accountName."',
  	`accountType` = '".$accountType."',
  	`paymentType` = '".$paymentType."',
  	`fees` = '".$fee."',
  	`status` = '".$status."',
  	`language_id` = '".$language_id."',
  	`currency_id` = '".$currency_id."',
  	`businessType` = '".$businessType."',
  	`address_one` = '".$addressStreet1."',
  	`address_two` = '".$addressStreet2."',
  	`zipCode` = '".$zipCode."',
  	`city` = '".$city."',
  	`country` = '".$country."',
  	`email` = '".$email."', 
  	`phone` = '".$phone."',
  	`taxId` = '".$taxId."',
  	`logo`  = '".$logo."',
  	`max_user` = '".$max_user."'
  	WHERE id='".$_GET['account_id']."' ";
  	mysqli_query($con, $sql);

  	$sql = "UPDATE `tbl_client_ezee_setup` SET 
  	`hotel_id` = '".$hotel_id."',
  	`merchant_id` = '".$merchant_id."'
  	WHERE account_id='".$_GET['account_id']."' ";
  	mysqli_query($con, $sql);

  	$sql = "UPDATE `tbl_user` SET 
  	`email` = '".$email."',
  	`username` = '".$userName."',
  	`password` = '".$password."' 
  	WHERE account_id = '".$_GET['account_id']."' ";
  	mysqli_query($con, $sql);

    $sql = " DELETE FROM tbl_client_section_permission 
    WHERE account_id='".$_GET['account_id']."' ";
    mysqli_query($con, $sql);

    foreach($_POST['sections'] as $section)
    {
      $sql = "INSERT INTO `tbl_client_section_permission` SET 
      `section_id` = '".$section."',
      `account_id` = '".$_GET['account_id']."' ";
      mysqli_query($con, $sql);
    }

    $_SESSION['updated'] = "Client has been successfully Updated";

  	echo "<script>window.location='accountsManager.php'</script>";
    
}

?>