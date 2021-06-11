<?php
  require_once("../penghubung.inc.php");
  require_once($LIB."login.php");
  require_once($LIB."encrypt.php");
  require_once($LIB."datamodel.php");
  require_once($LIB."currency.php");
  require_once($LIB."dateLib.php");
  require_once($LIB."expAJAX.php");

  $dtaccess = new DataAccess();
  $enc = new textEncrypt();     
  $auth = new CAuth();
  $depId = $auth->GetDepId();
  $depLowest = $auth->GetDepLowest();
  $tahunTarif = $auth->GetTahunTarif();
  $depNama = $auth->GetDepNama();
  $userName = $auth->GetUserName();
  $skr = date('Y-m-d');

  /***
   * status_pasien_tabel
      A  = sedang antri
      P  = Panggil Pasien 
      L  = Layani
      S  = Sudah di Layani  
    */ 
  /* Cari pasien yang berstatus P */
  $sql ="select count(no_antrian_pasien) as jml  from klinik.klinik_nomer_antrian where  status_antrian=".QuoteValue(DPE_CHAR,'S')."  and id_poli=".QuoteValue(DPE_CHAR,$_GET['id_poli'])." and id_dokter =".QuoteValue(DPE_CHAR,$_GET['id_dokter'])." and 
  DATE(when_create) =".QuoteValue(DPE_DATE,$skr);
  $nomer_antrian = $dtaccess->Fetch($sql);

  echo json_encode($nomer_antrian);
?>