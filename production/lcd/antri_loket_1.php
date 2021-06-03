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
     $tableAntri= new InoTable("table1","center");
     $auth = new CAuth();    
     $lokasi = $ROOT."gambar/foto_pasien";
	   $depId = $auth->GetDepId(); 
     //$loketId = $auth->Idloket();
     $loketId = 1;//$_GET["loket"];
          
          $sql = "select * from  klinik.klinik_reg_antrian_reguler 
                  where a.id_dep = ".QuoteValue(DPE_CHAR,$depId)."
                  and a.reg_antri_suara like='A'
                  and a.id_loket= ".QuoteValue(DPE_CHAR,$loketId)."
                  order by a.reg_antri_nomer desc";
          $dataAntri = $dtaccess->FetchAll($sql);  
          return $sql; 
          $counterHeader = 0;                                                                  

          $tbHeader[0][$counterHeader][TABLE_ISI] = "ANTRIAN";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "5%";
          $counterHeader++;     
                for($i=0,$n=count($dataAntri),$counter=0;$i<$n;$i++,$counter=0) {
                
               if($dataAntri[0]["reg_antri_id"] || $dataAntri[1]["reg_antri_id"] || $dataAntri[2]["reg_antri_id"] || $dataAntri[3]["reg_antri_id"] || $dataAntri[4]["reg_antri_id"])   {

               if($dataAntri[0]["reg_antri_id"]) { 
                      /* if($dataAntri[0]["cust_usr_foto"]) { 
                            $fotoName0 = $lokasi."/".$dataAntri[0]["cust_usr_foto"];
                        } else { 
                            $fotoName0 = $lokasi."/default.jpg";
                        }   */
               $tbContent[0][0][TABLE_ISI] =  '&nbsp;<font size="5"><strong>'.$dataAntri[0]["reg_antri_nomer"]."</font></strong>.&nbsp;&nbsp;&nbsp;<font size='5'>".substr($dataAntri[0]["cust_usr_nama"], 0, 7)."&nbsp; </font>";
               }else{
               $tbContent[0][0][TABLE_ISI] =  '&nbsp';
               }
               $tbContent[0][0][TABLE_ALIGN] = "left";

               if($dataAntri[1]["reg_antri_id"]) {
                        if($dataAntri[1]["cust_usr_foto"]) { 
                            $fotoName1 = $lokasi."/".$dataAntri[1]["cust_usr_foto"];
                        } else { 
                            $fotoName1 = $lokasi."/default.jpg";
                        }
               $tbContent[1][0][TABLE_ISI] =  '&nbsp;<font size="5"><strong>'.$dataAntri[1]["reg_antri_nomer"]."</font></strong>.&nbsp;&nbsp;&nbsp;<font size='5'>".substr($dataAntri[1]["cust_usr_nama"], 0, 7)."&nbsp;</font>";
               }else{
               $tbContent[1][0][TABLE_ISI] =  '&nbsp';
               }
               $tbContent[1][0][TABLE_ALIGN] = "left";

               if($dataAntri[2]["reg_antri_id"]) {
                        if($dataAntri[2]["cust_usr_foto"]) { 
                            $fotoName2 = $lokasi."/".$dataAntri[2]["cust_usr_foto"];
                        } else { 
                            $fotoName2 = $lokasi."/default.jpg";
                        }
               $tbContent[2][0][TABLE_ISI] =  '&nbsp;<font size="5"><strong>'.$dataAntri[2]["reg_antri_nomer"]."</font></strong>.&nbsp;&nbsp;&nbsp;<font size='5'>".substr($dataAntri[2]["cust_usr_nama"], 0, 7)."&nbsp;</font>";
               }else{
               $tbContent[2][0][TABLE_ISI] =  '&nbsp';
               }
               $tbContent[2][0][TABLE_ALIGN] = "left";

               if($dataAntri[3]["reg_antri_id"]) {
                        if($dataAntri[3]["cust_usr_foto"]) { 
                            $fotoName2 = $lokasi."/".$dataAntri[3]["cust_usr_foto"];
                        } else { 
                            $fotoName2 = $lokasi."/default.jpg";
                        }
               $tbContent[3][0][TABLE_ISI] =  '&nbsp;<font size="5"><strong>'.$dataAntri[3]["reg_antri_nomer"]."</font></strong>.&nbsp;&nbsp;&nbsp;<font size='5'>".substr($dataAntri[3]["cust_usr_nama"], 0, 7)."&nbsp;</font>";
               }else{
               $tbContent[3][0][TABLE_ISI] =  '&nbsp';
               }
               $tbContent[3][0][TABLE_ALIGN] = "left";

               if($dataAntri[4]["reg_antri_id"]) {
                        if($dataAntri[4]["cust_usr_foto"]) { 
                            $fotoName2 = $lokasi."/".$dataAntri[4]["cust_usr_foto"];
                        } else { 
                            $fotoName2 = $lokasi."/default.jpg";
                        }
               $tbContent[4][0][TABLE_ISI] =  '&nbsp;<font size="5"><strong>'.$dataAntri[4]["reg_antri_nomer"]."</font></strong>.&nbsp;&nbsp;&nbsp;<font size='5'>".substr($dataAntri[4]["cust_usr_nama"], 0, 7)."&nbsp;</font>";
               }else{
               $tbContent[4][0][TABLE_ISI] =  '&nbsp';
               }
               $tbContent[4][0][TABLE_ALIGN] = "left";
               
              
               }
          }
   
?>
<html>
<head></head>
<body>
<table border="1" width="100%">
<tr>
<td>
<?php echo $tableAntri->RenderView($tbHeader,$tbContent,$tbBottom); ?>
</td>
</tr>
</table>
