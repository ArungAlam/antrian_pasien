<?php
  require_once("../penghubung.inc.php");
  require_once($LIB."login.php");
  require_once($LIB."encrypt.php");
  require_once($LIB."datamodel.php");
  require_once($LIB."currency.php");
  require_once($LIB."dateLib.php");
  require_once($LIB."expAJAX.php");

  $dtaccess = new DataAccess();
  
  /***
   * status_pasien_tabel
      A  = sedang antri
      P  = Panggil Pasien 
      L  = Layani
      S  = Sudah di Layani  
    */ 
  /* Cari pasien yang berstatus P */
  $sql ="select c.usr_name ,d.poli_nama,d.poli_id ,usr_id,e.pgw_foto as foto
			       from klinik.klinik_jadwal_dokter a
             left join global.global_auth_user c on c.usr_id = a.id_dokter
             left join global.global_auth_poli d on d.poli_id = a.id_poli
             left join hris.hris_pegawai e on e.pgw_id = c.id_pgw
             where a.id_ruangan=".QuoteValue(DPE_CHAR,$_GET['id_ruangan']);
  $data = $dtaccess->Fetch($sql);

  echo json_encode($data);
?>