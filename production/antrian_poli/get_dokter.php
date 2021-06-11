<?php
  require_once("../penghubung.inc.php");
  require_once($LIB."login.php");
  require_once($LIB."encrypt.php");
  require_once($LIB."datamodel.php");
  require_once($LIB."currency.php");
  require_once($LIB."dateLib.php");

  $dtaccess = new DataAccess();
  $depId = $auth->GetDepId();


  $sql ="select a.id_dokter , b.usr_name
  from klinik.klinik_jadwal_dokter a
  left join global.global_auth_user b on b.usr_id = a.id_dokter 
  where a.jadwal_dokter_hari =".QuoteValue(DPE_NUMERIC,$_GET['day'])." 
  and   a.id_poli =".QuoteValue(DPE_CHAR,$_GET['id']);

  $data = $dtaccess->FetchAll($sql);

  echo json_encode($data);
?>