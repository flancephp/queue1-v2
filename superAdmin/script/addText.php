<?php

if (isset($_POST['main_text'])) 
{

	$whereCond = [
		'main_text' => $_POST['main_text']
	];

	$mainTextName = fetchTableDetails('tbl_site_main_text', $whereCond);

	//Check $_POST['main_text'] exist in table or not
	if (count($mainTextName) == '0') 
	{
		
		//If $_POST['main_text'] not exist in table
		$mainText = $_POST['main_text'];
		$mainTextKey = str_replace(" ","_",$mainText);

		$fieldsArr = [
			'main_text' => $_POST['main_text'],
			'main_text_key' => $mainTextKey,
			'created_on' => date('Y-m-d h:i:s')
		];
		
		insert('tbl_site_main_text', $fieldsArr);

		echo "<script>window.location='languageSetup.php?added=1'</script>";
	}
	else
	{
		//page redirect $_POST['main_text'] already exist in table
		echo "<script>window.location='addText.php?error=1'</script>";
	}
}



?>