<?php
	require_once("../penghubung.inc.php");
	require_once($LIB."upload.php");
  require_once($LIB."login.php");
  require_once($LIB."datamodel.php");
  
  // INISIALISASY LIBRARY
  $dtaccess = new DataAccess(); 
  $auth = new CAuth();
  $depId = $auth->GetDepId();  
  
	$fileElementName = "fileToUpload3";
	$lokasi = $ROOT."lcd/";
  //echo $lokasi;
  //die();   
  $arr_mime = array("video/mp4","video/x-flv","video/x-ms-wmv","video/mpeg","video/3gpp","video/x-msvideo","video/x-matroska");
	
	$error = InoUpload($_FILES[$fileElementName],$lokasi,null,$newName,$arr_mime); 

	$msg .= "Upload Success...";
	$msg .= " File Name: " . $_FILES[$fileElementName]['name'] . ", ";
	$msg .= " File Size: " . @filesize($_FILES[$fileElementName]['tmp_name']);

	echo "{";
	echo				"error: '" . $error . "',\n";
	echo				"msg: '" . $msg . "',\n";
	echo				"file: '" . $newName . "'\n";
	echo "}";
  
  if(!$error){
  $dbTable = "global.global_video_antrian";
  $dbField[0] = "video_antrian_id";
  $dbField[1] = "video_antrian_nama";
  $dbField[2] = "id_dep";
  $dbField[3] = "urutan";
    
  $videoId = $dtaccess->GetTransId();
  $dbValue[0] = QuoteValue(DPE_NUMERIC,$videoId);
  $dbValue[1] = QuoteValue(DPE_CHAR,$newName);
  $dbValue[2] = QuoteValue(DPE_CHAR,$depId);
  $dbValue[3] = QuoteValue(DPE_NUMERIC,$videoId);
  //print_r($dbValue); die();
  
  $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
	$dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
	
  $dtmodel->Insert() or die("insert  error");	 
	
	unset($dtmodel);
	unset($dbField);
	unset($dbValue);
	unset($dbKey);
  }
?>
