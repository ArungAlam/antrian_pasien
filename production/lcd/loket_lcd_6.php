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
     $loketId = 6; 
	   $poliId = 6;  // POLI JKN //
     $lokasi = $ROOT."gambar/foto_pasien";

 			        $sql = "select b.cust_usr_nama,a.reg_antri_nomer,a.id_poli from  klinik.klinik_reg_antrian_reguler a 
                  left join  global.global_customer_user b on b.cust_usr_id = a.id_cust_usr
                  where a.id_dep = ".QuoteValue(DPE_CHAR,$depId)."
                  and a.reg_antri_suara ='A'
                  and a.id_loket= ".QuoteValue(DPE_CHAR,$loketId)."
                  and a.antri_aktif= 'y'                  
                  order by a.reg_antri_nomer desc";
          $dataTable = $dtaccess->FetchAll($sql);
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
  
  
        if($dataTable[0]["id_poli"]==1) $poliNama="NO";
        if($dataTable[0]["id_poli"]==2) $poliNama="NO";
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
<h3> LOKET 6 </h3><?php //if($dataTable[0]["reg_antri_nomer"]) echo '<img class="pp" src="'.$fotoName.'" />'; ?>
<!-- <div class="nom">

&nbsp;<?php echo $poliNama;?>&nbsp;&nbsp;<?php if($dataTable[0]["reg_antri_nomer"]) echo $dataTable[0]["reg_antri_nomer"];?></div>-->
&nbsp;<font color='black' align="center"><div class="nam"><?php if($dataTable[0]["reg_antri_nomer"]) echo $dataTable[0]["reg_antri_nomer"];?></font></div>

</div>
