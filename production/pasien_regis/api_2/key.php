<?php
      // Library
     require_once("../penghubung.inc.php");
     require_once($LIB."login.php");
     require_once($LIB."datamodel.php");
     require_once($LIB."dateLib.php");
     require_once($LIB."currency.php");
     require_once($LIB."encrypt.php");
     require_once($LIB."expAJAX.php"); 
     require_once($LIB."tampilan.php");
	 
	    
     $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
	   $dtaccess = new DataAccess();
     $auth = new CAuth();
	   $depId = $auth->GetDepId();
     $userName = $auth->GetUserName();

     $sql = "select dep_alamat_ip_inacbg from global.global_departemen where dep_id = ".QuoteValue(DPE_CHAR,$depId);
     $rs = $dtaccess->Execute($sql);
     $konfigurasi = $dtaccess->Fetch($rs);
     
 $key = "75f7ee670b83878000e89321c86b40e065dbd667934c24c4967be04ed33817ac";
 //$key =  $konfigurasi["dep_alamat_ip_inacbg"]
 $url = "http://10.45.8.119/emrapi/api/registrasi_pasien?id_reg=10";
 
// echo $url;
?>