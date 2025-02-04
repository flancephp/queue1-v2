<?php
include('../../inc/dbConfig.php');
$json = [];
if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['role'] == 'clientDelete'){
  delete('tbl_client',array('id'=>$_POST['cient_id']));
  delete('tbl_user',array('account_id'=>$_POST['cient_id']));
  $json['success'] = "Success! Client has been successfully deleted";
  echo json_encode($json);
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['role'] == 'sectionDelete'){
  delete('tbl_sections',array('id'=>$_POST['section_id']));
  $json['success'] = "Success! Section has been successfully deleted";
  echo json_encode($json);
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['role'] == 'businessDelete'){
  delete('tbl_business',array('id'=>$_POST['business_id']));
  $json['success'] = "Success! Business has been successfully deleted";
  echo json_encode($json);
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['role'] == 'languageDelete'){
  delete('tbl_language',array('id'=>$_POST['language_id']));
  $json['success'] = "Success! Language has been successfully deleted";
  echo json_encode($json);
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['role'] == 'currencyDelete'){
  delete('tbl_currency',array('id'=>$_POST['currency_id']));
  $json['success'] = "Success! Currency has been successfully deleted";
  echo json_encode($json);
}