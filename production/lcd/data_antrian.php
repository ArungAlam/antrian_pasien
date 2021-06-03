<?php
	require_once("../penghubung.inc.php");
  require_once($LIB."login.php");
  require_once($LIB."encrypt.php");
  require_once($LIB."datamodel.php");
  require_once($LIB."tampilan.php");     
  require_once($LIB."currency.php");
  require_once($LIB."dateLib.php");

  $dtaccess = new DataAccess();     
  $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
  $auth = new CAuth();

  $sql = "SELECT * FROM klinik.klinik_reg_antrian_reguler WHERE waktu_panggil IS NOT NULL AND antri_aktif = 'y' AND reg_panggil = 'n' 
        AND reg_antri_tanggal = ".QuoteValue(DPE_DATE, date('Y-m-d'))." ORDER BY waktu_panggil asc";
  $rs = $dtaccess->Execute($sql);
  $data = $dtaccess->Fetch($rs);

  if ($data['reg_panggil'] == 'n') {
    $sql = "UPDATE klinik.klinik_reg_antrian_reguler SET reg_panggil = 'y' WHERE reg_antri_id = ".QuoteValue(DPE_CHAR, $data["reg_antri_id"]);
    $dtaccess->Execute($sql);
  }

  echo '-'.$data['reg_antri_suara'].'-'.sprintf("%03d", $data['reg_antri_nomer']).'-'.$data['reg_panggil'].'-'.$data['id_loket'];
?>