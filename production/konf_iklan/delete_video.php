<?php
  require_once("../penghubung.inc.php");
  require_once($LIB."login.php");
  require_once($LIB."encrypt.php");
  require_once($LIB."datamodel.php");
  require_once($LIB."currency.php");
  require_once($LIB."dateLib.php");
  require_once($LIB."expAJAX.php");
  require_once($LIB."tampilan.php");

  $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
  $dtaccess = new DataAccess();
  $enc = new textEncrypt();     
  $auth = new CAuth();
  $depId = $auth->GetDepId();
  $depLowest = $auth->GetDepLowest();
  $tahunTarif = $auth->GetTahunTarif();
  $depNama = $auth->GetDepNama();
  $userName = $auth->GetUserName();
  $skr = date('Y-m-d');

 $id = $_POST['id'];

 $sql = "select * from global.global_video_iklan where iklan_id = '$id'";
 $data = $dtaccess->Fetch($sql);
 
 
 /* hapus  data video */
 unlink("../lcd/".$data['iklan_video_nama']);

 /* hapus  db */
 $sql = "delete from global.global_video_iklan where iklan_id = '$id'";
 $result = $dtaccess->Fetch($sql);

  echo json_encode($result);
?>