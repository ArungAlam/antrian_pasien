<?php
     // Library
     require_once("../penghubung.inc.php");
     require_once($LIB."login.php");
     require_once($LIB."encrypt.php");
     require_once($LIB."datamodel.php");
     require_once($LIB."tampilan.php");     
     require_once($LIB."currency.php");
     require_once($LIB."dateLib.php");
     
     // Inisialisasi Lib
     $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
	   $dtaccess = new DataAccess();
     $auth = new CAuth();
     $tableAntri = new InoTable("table","100%","center");
	   $depId = $auth->GetDepId();
     $depNama = $auth->GetDepNama();
     $userName = $auth->GetUserName();
	   $poliId = $auth->IdPoli();
     $nextPage = "proses_perawatan_simpel2.php?id=1";
     $tgl = date("Y-m-d"); 
     // Ajax
     $plx = new expAJAX("GetLoket1,GetLoket2,GetLoket3,GetLoket4,GetLoket5");
     
     $sql = "select * from global.global_departemen where dep_id='$depId'";
     $depKonfig = $dtaccess->Fetch($sql); 
     
     //KONFIGURASI LOKET
     $loketAntrian='1';    
     //NGGA JALAN DI SEARCHING AJA YANG '1' GANTI '2' ya dst
     
    function GetLoket1($status) 
    {
          global $dtaccess, $view, $tableAntri, $ROOT,$ROOT, $poliId, $depId, $nextPage, $tgl, $loket;
          
          $sql = " select b.cust_usr_nama , a.reg_antri_suara, a.reg_antri_nomer ,reg_antri_id  ,a.id_loket , a.id_poli,
              klinik_waktu_tunggu_id, d.id_reg from  klinik.klinik_reg_antrian_reguler a
              left join global.global_customer_user b on a.id_cust_usr = b.cust_usr_id
              left join klinik.klinik_antrian_reguler c on c.id_dep = a.id_dep
              left join klinik.klinik_waktu_tunggu d on d.klinik_waktu_tunggu_id=a.id_klinik_waktu_tunggu
              where a.reg_antri_suara = 'A' and a.id_loket='1'
              and a.id_dep = ".QuoteValue(DPE_CHAR,$depId)." and a.reg_antri_tanggal = ".QuoteValue(DPE_DATE,$tgl)."
              order by reg_antri_nomer asc"; 
      $dataTable = $dtaccess->FetchAll($sql);
          $dataTable = $dtaccess->FetchAll($sql); 
      
      $counter=0;
	    $counterHeader = 0;       
      
     
          $tbHeader[0][$counterHeader][TABLE_ISI] = "Nomor Antrian";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "5%";
          $counterHeader++;
          
          $tbHeader[0][$counterHeader][TABLE_ISI] = "Loket";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "15%";
          $counterHeader++;
      
     for($i=0,$counter=0,$n=count($dataTable);$i<$n;$i++,$counter=0){
			
        
        $tbContent[$i][$counter][TABLE_ISI] = "<b>".$dataTable[$i]["reg_antri_nomer"]."</b>";
			  $tbContent[$i][$counter][TABLE_ALIGN] = "center";
        $counter++;
        
         if($dataTable[$i]["id_loket"]==1) $loket="LOKET 1";
        if($dataTable[$i]["id_loket"]==2) $loket="LOKET 2";
        if($dataTable[$i]["id_loket"]==3) $loket="LOKET 3";
        
        $tbContent[$i][$counter][TABLE_ISI] = "&nbsp;".$loket;
			  $tbContent[$i][$counter][TABLE_ALIGN] = "left";
        $counter++;
     }
     
       return $tableAntri->RenderView($tbHeader,$tbContent,$tbBottom);
		
	}
  
   function GetLoket2($status) 
   {
     global $dtaccess, $view, $tableAntri, $ROOT,$ROOT, $poliId, $depId, $nextPage, $tgl;
     
     $sql = "select * from global.global_departemen where dep_id='$depId'";
     $depKonfig = $dtaccess->Fetch($sql); 
              
      $sql = " select b.cust_usr_nama , a.reg_antri_suara, a.reg_antri_nomer ,reg_antri_id  ,a.id_loket , a.id_poli,
              klinik_waktu_tunggu_id, d.id_reg from  klinik.klinik_reg_antrian_reguler a
              left join global.global_customer_user b on a.id_cust_usr = b.cust_usr_id
              left join klinik.klinik_antrian_reguler c on c.id_dep = a.id_dep
              left join klinik.klinik_waktu_tunggu d on d.klinik_waktu_tunggu_id=a.id_klinik_waktu_tunggu
              where a.reg_antri_suara = 'A' and a.id_loket='2'
              and a.id_dep = ".QuoteValue(DPE_CHAR,$depId)." and a.reg_antri_tanggal = ".QuoteValue(DPE_DATE,$tgl)."
              order by reg_antri_nomer asc"; 
      $dataTable = $dtaccess->FetchAll($sql);
       
      
      $counter=0;
	    $counterHeader = 0;       
     
          $tbHeader[0][$counterHeader][TABLE_ISI] = "Nomor";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "5%";
          $counterHeader++;
          
          $tbHeader[0][$counterHeader][TABLE_ISI] = "Loket";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "15%";
          $counterHeader++;
          
          
     for($i=0,$counter=0,$n=count($dataTable);$i<$n;$i++,$counter=0){
     
      
        $tbContent[$i][$counter][TABLE_ISI] = '<a href="next_antrian_1.php?id_antri='.$dataTable[$i]["reg_antri_nomer"].'"><b>'.$dataTable[$i]["reg_antri_nomer"].'</b></a>';
			  $tbContent[$i][$counter][TABLE_ALIGN] = "center";
        $counter++;
                
        if($dataTable[$i]["id_loket"]==1) $loket="LOKET 1";
        if($dataTable[$i]["id_loket"]==2) $loket="LOKET 2";
        if($dataTable[$i]["id_loket"]==3) $loket="LOKET 3";
        
        $tbContent[$i][$counter][TABLE_ISI] = "&nbsp;".$loket;
			  $tbContent[$i][$counter][TABLE_ALIGN] = "left";
        $counter++;
        
     }
     
       return $tableAntri->RenderView($tbHeader,$tbContent,$tbBottom);
		
	}
  
   function GetLoket3($status) 
   {
     global $dtaccess, $view, $tableAntri, $ROOT,$ROOT, $poliId, $depId, $nextPage, $tgl;
     
     $sql = "select * from global.global_departemen where dep_id='$depId'";
     $depKonfig = $dtaccess->Fetch($sql); 
              
      $sql = " select b.cust_usr_nama , a.reg_antri_suara, a.reg_antri_nomer ,reg_antri_id  ,a.id_loket , a.id_poli,
              klinik_waktu_tunggu_id, d.id_reg from  klinik.klinik_reg_antrian_reguler a
              left join global.global_customer_user b on a.id_cust_usr = b.cust_usr_id
              left join klinik.klinik_antrian_reguler c on c.id_dep = a.id_dep
              left join klinik.klinik_waktu_tunggu d on d.klinik_waktu_tunggu_id=a.id_klinik_waktu_tunggu
              where a.reg_antri_suara = 'A' and a.id_loket='3'
              and a.id_dep = ".QuoteValue(DPE_CHAR,$depId)." and a.reg_antri_tanggal = ".QuoteValue(DPE_DATE,$tgl)."
              order by reg_antri_nomer asc"; 
      $dataTable = $dtaccess->FetchAll($sql);
       
      
      $counter=0;
	    $counterHeader = 0;       
     
          $tbHeader[0][$counterHeader][TABLE_ISI] = "Nomor";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "5%";
          $counterHeader++;
          
          $tbHeader[0][$counterHeader][TABLE_ISI] = "Loket";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "15%";
          $counterHeader++;
          
          
     for($i=0,$counter=0,$n=count($dataTable);$i<$n;$i++,$counter=0){
     
      
        $tbContent[$i][$counter][TABLE_ISI] = '<a href="next_antrian_1.php?id_antri='.$dataTable[$i]["reg_antri_nomer"].'"><b>'.$dataTable[$i]["reg_antri_nomer"].'</b></a>';
			  $tbContent[$i][$counter][TABLE_ALIGN] = "center";
        $counter++;
                
        if($dataTable[$i]["id_loket"]==1) $loket="LOKET 1";
        if($dataTable[$i]["id_loket"]==2) $loket="LOKET 2";
        if($dataTable[$i]["id_loket"]==3) $loket="LOKET 3";
        
        $tbContent[$i][$counter][TABLE_ISI] = "&nbsp;".$loket;
			  $tbContent[$i][$counter][TABLE_ALIGN] = "left";
        $counter++;
        
     }
     
       return $tableAntri->RenderView($tbHeader,$tbContent,$tbBottom);
		
	}
  
   function GetLoket4($status) 
   {
     global $dtaccess, $view, $tableAntri, $ROOT,$ROOT, $poliId, $depId, $nextPage, $tgl;
     
     $sql = "select * from global.global_departemen where dep_id='$depId'";
     $depKonfig = $dtaccess->Fetch($sql); 
              
      $sql = " select b.cust_usr_nama , a.reg_antri_suara, a.reg_antri_nomer ,reg_antri_id  ,a.id_loket , a.id_poli,
              klinik_waktu_tunggu_id, d.id_reg from  klinik.klinik_reg_antrian_reguler a
              left join global.global_customer_user b on a.id_cust_usr = b.cust_usr_id
              left join klinik.klinik_antrian_reguler c on c.id_dep = a.id_dep
              left join klinik.klinik_waktu_tunggu d on d.klinik_waktu_tunggu_id=a.id_klinik_waktu_tunggu
              where a.reg_antri_suara = 'A' and a.id_loket='4'
              and a.id_dep = ".QuoteValue(DPE_CHAR,$depId)." and a.reg_antri_tanggal = ".QuoteValue(DPE_DATE,$tgl)."
              order by reg_antri_nomer asc"; 
      $dataTable = $dtaccess->FetchAll($sql);
       
      
      $counter=0;
	    $counterHeader = 0;       
     
          $tbHeader[0][$counterHeader][TABLE_ISI] = "Nomor";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "5%";
          $counterHeader++;
          
          $tbHeader[0][$counterHeader][TABLE_ISI] = "Loket";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "15%";
          $counterHeader++;
          
          
     for($i=0,$counter=0,$n=count($dataTable);$i<$n;$i++,$counter=0){
     
      
        $tbContent[$i][$counter][TABLE_ISI] = '<a href="next_antrian_1.php?id_antri='.$dataTable[$i]["reg_antri_nomer"].'"><b>'.$dataTable[$i]["reg_antri_nomer"].'</b></a>';
			  $tbContent[$i][$counter][TABLE_ALIGN] = "center";
        $counter++;
                
        if($dataTable[$i]["id_loket"]==1) $loket="LOKET 1";
        if($dataTable[$i]["id_loket"]==2) $loket="LOKET 2";
        if($dataTable[$i]["id_loket"]==3) $loket="LOKET 3";
        if($dataTable[$i]["id_loket"]==4) $loket="LOKET 4";
        if($dataTable[$i]["id_loket"]==5) $loket="LOKET 5";
        
        $tbContent[$i][$counter][TABLE_ISI] = "&nbsp;".$loket;
			  $tbContent[$i][$counter][TABLE_ALIGN] = "left";
        $counter++;
        
     }
     
       return $tableAntri->RenderView($tbHeader,$tbContent,$tbBottom);
		
	}
  
  
   function GetLoket5($status) 
   {
     global $dtaccess, $view, $tableAntri, $ROOT,$ROOT, $poliId, $depId, $nextPage, $tgl;
     
     $sql = "select * from global.global_departemen where dep_id='$depId'";
     $depKonfig = $dtaccess->Fetch($sql); 
              
      $sql = " select b.cust_usr_nama , a.reg_antri_suara, a.reg_antri_nomer ,reg_antri_id  ,a.id_loket , a.id_poli,
              klinik_waktu_tunggu_id, d.id_reg from  klinik.klinik_reg_antrian_reguler a
              left join global.global_customer_user b on a.id_cust_usr = b.cust_usr_id
              left join klinik.klinik_antrian_reguler c on c.id_dep = a.id_dep
              left join klinik.klinik_waktu_tunggu d on d.klinik_waktu_tunggu_id=a.id_klinik_waktu_tunggu
              where a.reg_antri_suara = 'A' and a.id_loket='5'
              and a.id_dep = ".QuoteValue(DPE_CHAR,$depId)." and a.reg_antri_tanggal = ".QuoteValue(DPE_DATE,$tgl)."
              order by reg_antri_nomer asc"; 
      $dataTable = $dtaccess->FetchAll($sql);
       
      
      $counter=0;
	    $counterHeader = 0;       
     
          $tbHeader[0][$counterHeader][TABLE_ISI] = "Nomor";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "5%";
          $counterHeader++;
          
          $tbHeader[0][$counterHeader][TABLE_ISI] = "Loket";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "15%";
          $counterHeader++;
          
          
     for($i=0,$counter=0,$n=count($dataTable);$i<$n;$i++,$counter=0){
     
      
        $tbContent[$i][$counter][TABLE_ISI] = '<a href="next_antrian_1.php?id_antri='.$dataTable[$i]["reg_antri_nomer"].'"><b>'.$dataTable[$i]["reg_antri_nomer"].'</b></a>';
			  $tbContent[$i][$counter][TABLE_ALIGN] = "center";
        $counter++;
                
        if($dataTable[$i]["id_loket"]==1) $loket="LOKET 1";
        if($dataTable[$i]["id_loket"]==2) $loket="LOKET 2";
        if($dataTable[$i]["id_loket"]==3) $loket="LOKET 3";
        if($dataTable[$i]["id_loket"]==4) $loket="LOKET 4";
        if($dataTable[$i]["id_loket"]==5) $loket="LOKET 5";
        
        $tbContent[$i][$counter][TABLE_ISI] = "&nbsp;".$loket;
			  $tbContent[$i][$counter][TABLE_ALIGN] = "left";
        $counter++;
        
     }
     
       return $tableAntri->RenderView($tbHeader,$tbContent,$tbBottom);
		
	}  
  

	
	
  if($_GET["id_antri"]) 
  {
      $sql = "delete from klinik.klinik_reg_antrian_reguler where id_dep = ".QuoteValue(DPE_CHAR,$depId);
    	$dtaccess->Execute($sql,DB_SCHEMA_KLINIK);    
  }  
  
  if($_POST["btnReset"]) 
  {
     $sql = "delete from klinik.klinik_reg_antrian_reguler where id_dep = ".QuoteValue(DPE_CHAR,$depId);
     $rs = $dtaccess->Execute($sql);
   }   
   
          
?>

<script type="text/javascript">
<? $plx->Run(); ?>

var mTimer;
function timer(){     
     clearInterval(mTimer);      
     GetLoket1(0,'target=antri_kiri');  
     GetLoket2(0,'target=antri_kanan');
     GetLoket3(0,'target=antri_kiri2');
     GetLoket4(0,'target=antri_kanan2'); 
     GetLoket5(0,'target=antri_kiri3');  
     mTimer = setTimeout("timer()", 2000);
}

timer();

</script>

<?php require_once($LAY."header.php") ?>
<body class="nav-sm" onLoad="setTimeout('delayer()',1000)">
  <div class="container body">
    <div class="main_container">
        <?php require_once($LAY."sidebar.php") ?>

        <!-- top navigation -->
          <?php require_once($LAY."topnav.php") ?>
        <!-- /top navigation -->

        <!-- page content -->
        <form name="frmEdit" method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>" enctype="multipart/form-data">
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h1>RESET ANTRIAN</h1>
                   <ul class="nav navbar-right panel_toolbox">
                      <button id="btnReset" name="btnReset" type="submit" value="Reset Antrian" class="btn btn-primary" border="0" />Reset Antrian</button>
                    </ul>
              </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                  <div class="x_content">
                    <div class="table-responsive">
                      <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <tr class="even pointer">
                             <td width="50%"> <div id="antri_kiri" style="float:left;height:50%;width:100%;overflow:auto"><?php echo GetLoket1(STATUS_ANTRI); ?></div></td>
                             <td width="1%">&nbsp;</td>
                             <td width="50%"> <div id="antri_kanan" style="float:left;height:50%;width:100%;overflow:auto"><?php echo GetLoket2(STATUS_ANTRI); ?></div></td>	
                          </tr>
                          <tr class="even pointer">
                             <td width="50%"> <div id="antri_kiri2" style="float:left;height:50%;width:100%;overflow:auto"><?php echo GetLoket3(STATUS_ANTRI); ?></div></td>
                             <td width="1%">&nbsp;</td>
                             <td width="50%"> <div id="antri_kanan2" style="float:left;height:50%;width:100%;overflow:auto"><?php echo GetLoket4(STATUS_ANTRI); ?></div></td>	
                          </tr>
                          <tr class="even pointer">
                             <td width="50%"> <div id="antri_kiri3" style="float:left;height:50%;width:100%;overflow:auto"><?php echo GetLoket5(STATUS_ANTRI); ?></div></td>
                             <td width="1%">&nbsp;</td>
                             <td width="50%">&nbsp;></td>	
                          </tr>

                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
          </form>
          
     <!-- footer content -->
     <?php require_once($LAY."footer.php") ?>
     <!-- /footer content -->
    </div>
</div>
<?php require_once($LAY."js.php") ?>                                                  

                                                