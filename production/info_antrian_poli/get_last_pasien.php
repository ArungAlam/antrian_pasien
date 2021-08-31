<?php
   require_once("../penghubung.inc.php");
  require_once($LIB."datamodel.php");

   /** INITIAL LIBRARY  */
    $dtaccess = new DataAccess();
    $skr  = date('Y-m-d');

  /***
   * status_pasien_tabel
      A  = sedang antri
      P  = Panggil Pasien 
      L  = Layani
      S  = Sudah di Layani  
    */ 
 

   
      /* get data pasien di panggil */
         $sql ="select no_antrian_pasien,cust_usr_nama   from klinik.klinik_nomer_antrian a
         left join klinik.klinik_registrasi b on b.reg_id = a.id_reg
         left join global.global_customer_user c on c.cust_usr_id = b.id_cust_usr
         where  status_antrian=".QuoteValue(DPE_CHAR,'S')."
          and no_antrian_pasien like '%".$_GET["kode"]."%'
           and DATE(a.when_create) =".QuoteValue(DPE_DATE,$skr)." order by when_create desc Limit 3";
        $last_pasien = $dtaccess->FetchAll($sql);

     
  $data = [];
  foreach ($last_pasien as $key => $value) {
    $data[$key]['nama'] = $value['cust_usr_nama'];
    $data[$key]['nomer'] = $value['no_antrian_pasien'];
   
  }
 

  

  echo json_encode($data);
?>