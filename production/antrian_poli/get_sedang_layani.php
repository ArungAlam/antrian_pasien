<?php
  require_once("../penghubung.inc.php");
  require_once($LIB."login.php");
  require_once($LIB."encrypt.php");
  require_once($LIB."datamodel.php");
  require_once($LIB."currency.php");
  require_once($LIB."dateLib.php");
  require_once($LIB."expAJAX.php");

  $dtaccess = new DataAccess();
  $skr = date('Y-m-d');

  /***
   * status_pasien_tabel
      A  = sedang antri
      P  = Panggil Pasien 
      L  = Layani
      S  = Sudah di Layani  
    */ 

  /* Cari pasien yang berstatus P */
  $sql ="select no_antrian_pasien  from klinik.klinik_nomer_antrian where status_antrian=".QuoteValue(DPE_CHAR,'L')." and id_poli=".QuoteValue(DPE_CHAR,$_GET['id_poli'])." and id_dokter =".QuoteValue(DPE_CHAR,$_GET['id_dokter'])." and 
  DATE(when_create) =".QuoteValue(DPE_DATE,$skr)." 
  order by when_create ASC";
  $nomer_antrian = $dtaccess->Fetch($sql);

  echo json_encode($nomer_antrian);
?>