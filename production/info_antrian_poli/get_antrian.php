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
 

  $data = []; 
      /* get data pasien di panggil */
         $sql ="select no_antrian_pasien  from klinik.klinik_nomer_antrian 
         where (status_antrian=".QuoteValue(DPE_CHAR,'P')." or status_antrian=".QuoteValue(DPE_CHAR,'L').")
          and no_antrian_pasien like '%".$_GET["kode"]."%'
           and DATE(when_create) =".QuoteValue(DPE_DATE,$skr)." 
        order by when_create desc";
        $no_panggil = $dtaccess->Fetch($sql);

      /* get data pasien di panggil */
         $sql ="select no_antrian_pasien  from klinik.klinik_nomer_antrian 
         where status_antrian=".QuoteValue(DPE_CHAR,'A')."  
         and no_antrian_pasien like '%".$_GET["kode"]."%' 
         and  DATE(when_create) =".QuoteValue(DPE_DATE,$skr)." 
        order by when_create ASC";
        $no_next = $dtaccess->Fetch($sql);

  $data[$i]['call'] = $no_panggil['no_antrian_pasien'];
  $data[$i]['next'] = $no_next['no_antrian_pasien'];

  

  echo json_encode($data);
?>