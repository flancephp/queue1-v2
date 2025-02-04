<?php

if (isset($_POST['language'])) 
{

	$whereCond = [
		'language_name' => $_POST['language']
	];

	$languageName = fetchTableDetails('tbl_language', $whereCond);

	//Check $_POST['language'] exist in table or not
	if (count($languageName) == '0') 
	{
		//If $_POST['language'] not exist in table
		$fieldsArr = [
			'language_name' => $_POST['language'],
			'language_type' => $_POST['languageType'],
			'created_on' => date('Y-m-d h:i:s')
		];

		insert('tbl_language', $fieldsArr);

		$_SESSION['added'] = "Language has been successfully added";

		echo "<script>window.location='manageLanguage.php'</script>";
	}
	else
	{
		//page redirect $_POST['language'] already exist in table
		$_SESSION['warning'] = "This language already exist in our records";
		echo "<script>window.location='addLanguage.php'</script>";
		die;
	}
}


?>