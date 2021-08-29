<?php 
	require_once "api.php";
	
	$key = $_GET["noka__"]; 

	$bpjs = new Bpjs();
	echo $bpjs->listRujukan($key);
?>