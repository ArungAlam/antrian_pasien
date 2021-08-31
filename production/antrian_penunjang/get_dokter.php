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


  $sql ="select a.id_dokter , b.usr_name
  from klinik.klinik_jadwal_dokter a
  left join global.global_auth_user b on b.usr_id = a.id_dokter 
  where a.jadwal_dokter_hari =".QuoteValue(DPE_NUMERIC,$_GET['day'])." 
  and   a.id_poli =".QuoteValue(DPE_CHAR,$_GET['id']);

  $data = $dtaccess->FetchAll($sql);

  echo json_encode($data);
?>