<?php

if (isset($_GET['id']))
{

  $whereCond = [
    'id' => $_GET['id']
  ];

  $fetchTableDetails = fetchTableDetails('tbl_language', $whereCond);

}


if (isset($_POST['language'])) 
{
	$sql = " SELECT * FROM tbl_language WHERE id != '".$_GET['id']."' AND language_name = '".$_POST['language']."' ";
	$qry = mysqli_query($con, $sql);

	//Check $_POST['name'] exist in table or not
	if ( mysqli_fetch_array($qry) ) 
	{
		//page redirect $_POST['name'] already exist in table
		echo "<script>window.location='editLanguage.php?error=1'</script>";
	}
	else
	{
		//If $_POST['name'] not exist in table
		$fieldsArr = [
			'language_name' => $_POST['language'],
			'language_type' => $_POST['languageType'],
			'created_on' => date('Y-m-d h:i:s')
		];

		$whereArr = [
			'id' => $_GET['id']
		];

		updateTable('tbl_language', $fieldsArr, $whereArr);

		$_SESSION['updated'] = "Language has been successfully updated";

		echo "<script>window.location='manageLanguage.php?edit=1'</script>";	
	}
}



?>