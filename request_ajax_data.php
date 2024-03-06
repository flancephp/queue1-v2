<?php 
include('inc/dbConfig.php'); //connection details



if( isset($_POST['parentId']) && $_POST['parentId'] > 0 )

{

	$sqlSet = " SELECT * FROM tbl_category WHERE parentId = '".$_POST['parentId']."'   AND account_id = '".$_SESSION['accountId']."'   ";

	$resultSet = mysqli_query($con, $sqlSet);

	

	$options = '';

	while( $catDepartsR = mysqli_fetch_array($resultSet) )

	{

		$options .= '<option value="'.$catDepartsR['id'].'">'.$catDepartsR['name'].'</option>';

	}

	

	echo $options != '' ? $options : '<option value="">'.showOtherLangText('Select').'</option>';

}

if( isset($_POST['ordstorageDeptId']) && $_POST['ordstorageDeptId'] > 0 )

{

	$sqlSet = " SELECT * FROM tbl_users WHERE deptId = '".$_POST['ordstorageDeptId']."'   AND account_id = '".$_SESSION['accountId']."'  ";

	$resultSet = mysqli_query($con, $sqlSet);

	

	$options = '';

	while( $user = mysqli_fetch_array($resultSet) )

	{

		$options .= '<option value="'.$user['id'].'">'.$user['name'].'</option>';

	}

	

	echo $options != '' ? $options : '<option value="">'.showOtherLangText('Select').'</option>';

}

if( isset($_POST['barCode']) && $_POST['barCode'] != '' )

{

	$sqlSet = " SELECT * FROM tbl_products WHERE barCode = '".$_POST['barCode']."'   AND account_id = '".$_SESSION['accountId']."'   ";

	$resultSet = mysqli_query($con, $sqlSet);

	

	$productRes = mysqli_fetch_array($resultSet);

	

	echo json_encode($productRes);

	die;

}

else

{

	echo '<option value="">'.showOtherLangText('Select').'</option>';

}

?>

