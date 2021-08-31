<?php
 require_once("../penghubung.inc.php");
 require_once($LIB."login.php");
 require_once($LIB."datamodel.php");
 
  $dtaccess = new DataAccess();
  $depId ='9999999';
  $skr = date('Y-m-d');

	$sql = "select * from global.global_departemen where dep_id='$depId'";
	$depKonfig = $dtaccess->Fetch($sql);

  /***
   * status_pasien_tabel
      A  = sedang antri
      P  = Panggil Pasien 
      L  = Layani
      S  = Sudah di Layani  
    */ 
  /* Cari pasien yang berstatus P */
  $sql = " select b.cust_usr_nama , a.reg_antri_suara, a.reg_antri_nomer ,reg_antri_id  ,a.id_loket , a.id_poli,
              klinik_waktu_tunggu_id, d.id_reg from  klinik.klinik_reg_antrian_reguler a
              left join global.global_customer_user b on a.id_cust_usr = b.cust_usr_id
              left join klinik.klinik_antrian_reguler c on c.id_dep = a.id_dep
              left join klinik.klinik_waktu_tunggu d on d.klinik_waktu_tunggu_id=a.id_klinik_waktu_tunggu
              where a.reg_antri_suara = 'A' and a.id_loket='4'
              and a.id_dep = '$depId' and a.reg_antri_tanggal = '$skr'
              order by reg_antri_nomer asc"; 
   $dataRaw = $dtaccess->FetchAll($sql);
	$jenisNama = $depKonfig["dep_nama_antrian_loket_empat"];

  $n = count($dataRaw);
  $data = []; 
  for ($i=0; $i < $n ; $i++) { 
      $data[$i]['id'] = $dataRaw[$i]['reg_antri_id'];
      $data[$i]['nomer']=$dataRaw[$i]['reg_antri_nomer'];
      $data[$i]['jenis_nama'] = $jenisNama;

   }

  echo json_encode($data);
?>