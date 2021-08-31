<?php
	// LIBRARY
		 require_once("../penghubung.inc.php");
     require_once($LIB."login.php");
     require_once($LIB."encrypt.php");
     require_once($LIB."datamodel.php");
     require_once($LIB."dateLib.php");
     require_once($LIB."currency.php");
     require_once($LIB."tampilan.php");
			$dtaccess = new DataAccess();
			
			//INISIALISAI AWAL LIBRARY
			$view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
			$dtaccess = new DataAccess();
			$enc = new textEncrypt();
			$auth = new CAuth();
	  	$depId = $auth->GetDepId();
		 	$userName = $auth->GetUserName();
			$userId = $auth->GetUserId();
			$tahunTarif = $auth->GetTahunTarif();
			$userLogin = $auth->GetUserData();
		

		/* deklasri */
			$id_video = $_POST['id'];
			$urut = $_POST['urut'];
		/* update urutan */
				$sql="update global.global_video_antrian set urutan = '$urut'
								where  video_antrian_id = '$id_video'";
				$dtaccess->Execute($sql);
	

		
	 exit();      

?>