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
     $tablePerawatanMsk = new InoTable("table","100%","left");
     $auth = new CAuth();    
	   $depId = $auth->GetDepId();
     $loketId = 1; 
	   $poliId = 1;  // POLI JKN //
     $lokasi = $ROOT."gambar/foto_pasien";
          $sql = "select * from global.global_departemen where dep_id='$depId'";
          $DataDepartemen = $dtaccess->Fetch($sql); 
          // ambil data suara dulu //     
          $sql = "select * from global.global_suara order by suara_id asc";
          $DataSuara = $dtaccess->FetchAll($sql); 
          
          
                                       
          for($i=0,$n=count($DataSuara);$i<$n;$i++) {
          // cek data antrian sesuai poli nya//
         // $sql = "select * from klinik.klinik_antrian_sms where id_dep = ".QuoteValue(DPE_CHAR,$depId)." and id_poli = ".QuoteValue(DPE_CHAR,$poliId)." 
           //       order by antri_id asc";
          //$DataAntrian = $dtaccess->Fetch($sql);
            // cek jika ada pasien yg siap akan masuk ke poli gigi //  
            if($DataSuara[$i]["suara_id"]==$DataAntrian["antri_nomer"] && $DataAntrian["antri_suara"]=="A" && $DataAntrian["id_poli"]==$poliId) {
                $lokasi = $ROOT."antrian_reguler/suara_4"; // link folder suara poli umum .ogg //
                $File = $lokasi."/".$DataSuara[$i]["suara_nama"];  // link data suara poli umum //
                // tampikan suaranya //
                $hasil = "<audio autoplay=\"autoplay\" >
                          <source src='".$File."' type='audio/ogg' />
                          Your browser does not support HTML5 audio.
                          </audio>";            
                
                
                // update flag suara antrian //
              //  $sql = "update klinik.klinik_antrian_sms set antri_suara = 'M' where antri_id = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_id"])." 
              //  and id_dep =".QuoteValue(DPE_CHAR,$depId);
              //  $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);  
                
            /*    if($DataDepartemen["dep_antrian_1_tipe"]=="J"){ 
                $sql = "update klinik.klinik_reg_antrian_jkn_reguler set reg_antri_jkn_suara = 'M' where reg_antri_jkn_nomer = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_nomer"])." 
                and id_dep =".QuoteValue(DPE_CHAR,$depId);
                $dtaccess->Execute($sql,DB_SCHEMA_KLINIK); 
                }else if($DataDepartemen["dep_antrian_1_tipe"]=="U"){
                $sql = "update klinik.klinik_reg_antrian_reguler set reg_antri_suara = 'M' where reg_antri_nomer = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_nomer"])." 
                and id_dep =".QuoteValue(DPE_CHAR,$depId);
                $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
                
                }  */
                             
                    
                // cek data buffer 1 , apa ada yg di buffer nya //
                /*if($DataAntrian["antri_buffer_1"]!='0') { 
                
                  $sql = "update klinik.klinik_antrian set antri_suara = 'A' , antri_nomer = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_buffer_1"])." , id_poli = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_buffer_poli_1"])." where antri_id = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_id"])." and id_dep =".QuoteValue(DPE_CHAR,$depId);
                  $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);

                  $sql = "update klinik.klinik_antrian set antri_buffer_1 = ".QuoteValue(DPE_NUMERIC,'0')." , antri_buffer_poli_1 = ".QuoteValue(DPE_NUMERIC,'0')." where antri_id = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_id"])." and id_dep =".QuoteValue(DPE_CHAR,$depId);
                  $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
                }  
                
                // cek data buffer 2 , apa ada yg di buffer nya //
                if($DataAntrian["antri_buffer_2"]!='0') { 
                
                  $sql = "update klinik.klinik_antrian set antri_buffer_1 = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_buffer_2"])." , antri_buffer_poli_1 = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_buffer_poli_2"])." where antri_id = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_id"])." and id_dep =".QuoteValue(DPE_CHAR,$depId);
                  $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);

                  $sql = "update klinik.klinik_antrian set antri_buffer_2 = ".QuoteValue(DPE_NUMERIC,'0')." , antri_buffer_poli_2 = ".QuoteValue(DPE_NUMERIC,'0')." where antri_id = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_id"])." and id_dep =".QuoteValue(DPE_CHAR,$depId);
                  $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
                }
                
                // cek data buffer 3 , apa ada yg di buffer nya //
                if($DataAntrian["antri_buffer_3"]!='0') { 
                
                  $sql = "update klinik.klinik_antrian set antri_buffer_2 = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_buffer_3"])." , antri_buffer_poli_2 = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_buffer_poli_3"])." where antri_id = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_id"])." and id_dep =".QuoteValue(DPE_CHAR,$depId);
                  $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);

                  $sql = "update klinik.klinik_antrian set antri_buffer_3 = ".QuoteValue(DPE_NUMERIC,'0')." , antri_buffer_poli_3 = ".QuoteValue(DPE_NUMERIC,'0')." where antri_id = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_id"])." and id_dep =".QuoteValue(DPE_CHAR,$depId);
                  $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
                } */
                
             }  else if($DataAntrian["antri_suara"]=="M") {
             
             
                  if($DataAntrian["antri_buffer_1"]!='0') {
                    
              /*      $sql = "update klinik.klinik_antrian_sms set antri_suara = 'A' , antri_nomer = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_buffer_1"])." , id_poli = ".QuoteValue(DPE_CHAR,$DataAntrian["antri_buffer_poli_1"])." where antri_id = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_id"])." and id_dep =".QuoteValue(DPE_CHAR,$depId);
                    $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
                    
                    $sql = "update klinik.klinik_antrian_sms set antri_buffer_1 = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_buffer_2"])." , antri_buffer_poli_1 = ".QuoteValue(DPE_CHAR,$DataAntrian["antri_buffer_poli_2"])." where antri_id = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_id"])." and id_dep =".QuoteValue(DPE_CHAR,$depId);
                    $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
                    
                    $sql = "update klinik.klinik_antrian_sms set antri_buffer_2 = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_buffer_3"])." , antri_buffer_poli_2 = ".QuoteValue(DPE_CHAR,$DataAntrian["antri_buffer_poli_3"])." where antri_id = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_id"])." and id_dep =".QuoteValue(DPE_CHAR,$depId);
                    $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
                    
                    $sql = "update klinik.klinik_antrian_sms set antri_buffer_3 = ".QuoteValue(DPE_NUMERIC,'0')." , antri_buffer_poli_3 = ".QuoteValue(DPE_CHAR,'0')." where antri_id = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_id"])." and id_dep =".QuoteValue(DPE_CHAR,$depId);
                    $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);          */
                  
                  } else if($DataAntrian["antri_buffer_2"]!='0') { 
                    
                   /* 
                    $sql = "update klinik.klinik_antrian_sms set antri_buffer_1 = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_buffer_2"])." , antri_buffer_poli_1 = ".QuoteValue(DPE_CHAR,$DataAntrian["antri_buffer_poli_2"])." where antri_id = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_id"])." and id_dep =".QuoteValue(DPE_CHAR,$depId);
                    $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
                    
                    $sql = "update klinik.klinik_antrian_sms set antri_buffer_2 = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_buffer_3"])." , antri_buffer_poli_2 = ".QuoteValue(DPE_CHAR,$DataAntrian["antri_buffer_poli_3"])." where antri_id = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_id"])." and id_dep =".QuoteValue(DPE_CHAR,$depId);
                    $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
                    
                    $sql = "update klinik.klinik_antrian_sms set antri_buffer_3 = ".QuoteValue(DPE_NUMERIC,'0')." , antri_buffer_poli_3 = ".QuoteValue(DPE_CHAR,'0')." where antri_id = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_id"])." and id_dep =".QuoteValue(DPE_CHAR,$depId);
                    $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);  */
                  
                  } else if($DataAntrian["antri_buffer_3"]!='0') { 
                    
                        /*
                    $sql = "update klinik.klinik_antrian_sms set antri_buffer_2 = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_buffer_3"])." , antri_buffer_poli_2 = ".QuoteValue(DPE_CHAR,$DataAntrian["antri_buffer_poli_3"])." where antri_id = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_id"])." and id_dep =".QuoteValue(DPE_CHAR,$depId);
                    $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
                    
                    $sql = "update klinik.klinik_antrian_sms set antri_buffer_3 = ".QuoteValue(DPE_NUMERIC,'0')." , antri_buffer_poli_3 = ".QuoteValue(DPE_CHAR,'0')." where antri_id = ".QuoteValue(DPE_NUMERIC,$DataAntrian["antri_id"])." and id_dep =".QuoteValue(DPE_CHAR,$depId);
                    $dtaccess->Execute($sql,DB_SCHEMA_KLINIK); */
                  
                  }
                                           
             }
 				
 				 }   /*
 			        $sql = "select b.cust_usr_nama,a.reg_antri_nomer,a.id_poli from  klinik.klinik_reg_antrian_sms a 
                  left join  global.global_customer_user b on b.cust_usr_id = a.id_cust_usr
                  where a.id_dep = ".QuoteValue(DPE_CHAR,$depId)."
                  and a.reg_antri_suara ='A'
                  and a.id_loket= ".QuoteValue(DPE_CHAR,$loketId)."
                  and a.antri_aktif= 'y'                  
                  order by a.reg_antri_nomer desc";
          $dataTable = $dtaccess->FetchAll($sql);  */
       // echo $sql; 
          if($dataTable[0]["cust_usr_foto"]) { 
              $fotoName = $lokasi."/".$dataTable[0]["cust_usr_foto"];
          } else { 
              $fotoName = $lokasi."/default.jpg";
          }
          $spacer = $lokasi."/spacer.gif";
          
          $counterHeader = 0;

          $tbHeader[0][$counterHeader][TABLE_ISI] = "POLI PENGOBATAN";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "5%";
          $tbHeader[0][$counterHeader][TABLE_COLSPAN] = "2";
          $counterHeader++;

         if ($dataTable)
          {   
              for($i=0,$n=count($dataTable),$counter=0;$i<$n;$i++,$counter=0) {
              
                   $tbContent[0][$counter][TABLE_ISI] =  '<blink><font size="25" weight="bold"><strong>'.$dataTable[0]["reg_antri_nomer"].'</strong></font>.&nbsp;&nbsp;<font size="6">'.substr($dataTable[0]["cust_usr_nama"], 0, 7).'&nbsp;</font></blink>&nbsp;&nbsp;&nbsp;&nbsp;';
                   $tbContent[0][$counter][TABLE_ALIGN] = "left";
                   $tbContent[0][$counter][TABLE_VALIGN] = "center";
                   $tbContent[0][$counter][TABLE_COLSPAN] = "3";
                   $counter++;
                   
              }
         }
          else
          {
                 $tbContent[0][0][TABLE_ISI] =  '<font size="25"><strong>&nbsp;</strong></font>';
                 $tbContent[0][0][TABLE_ALIGN] = "left";
                 $tbContent[0][0][TABLE_VALIGN] = "center";
                 $tbContent[0][0][TABLE_COLSPAN] = "3";
                 $counter++;
          }
  
  
        if($dataTable[0]["id_poli"]==1) $poliNama=" NO";
        if($dataTable[0]["id_poli"]==2) $poliNama=" NO";
        //if($dataTable[0]["id_poli"]==3) $poliNama="PASIEN JKN";  
        
  
?>

<html>
<head>
<style type="text/css">
</style>
</head>
<body>                                                 
<?php echo $hasil;?>
<div width="100%">
<h3> ANTRIAN LOKET 4</h3><?php //if($dataTable[0]["reg_antri_nomer"]) echo '<img class="pp" src="'.$fotoName.'" />'; ?>
<!-- <div class="nom">

&nbsp;<?php echo $poliNama;?>&nbsp;&nbsp;<?php if($dataTable[0]["reg_antri_nomer"]) echo $dataTable[0]["reg_antri_nomer"];?></div>-->
&nbsp;<font color='red' align="center"><div class="nam"><?php if($dataTable[0]["reg_antri_nomer"]) echo $dataTable[0]["reg_antri_nomer"];?></font></div>

</div>
