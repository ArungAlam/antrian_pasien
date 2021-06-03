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
     $plx = new expAJAX("GetAntri,GetMasuk,SetAntrian");
     
     $sql = "select * from global.global_departemen where dep_id='$depId'";
     $depKonfig = $dtaccess->Fetch($sql); 
     
     //KONFIGURASI LOKET
     $loketAntrian='2';    
     //NGGA JALAN DI SEARCHING AJA YANG '1' GANTI '2' ya dst
     
     //NAMA HEADER
     $tableHeader="Antrian Loket 2"; 
     
    function GetAntri($status) 
    {
          global $dtaccess, $view, $tableAntri, $ROOT,$ROOT, $poliId, $depId, $nextPage, $tgl, $loket, $depKonfig;
          
          $sql = " select b.cust_usr_nama , a.reg_antri_suara, a.reg_antri_nomer, a.reg_antri_id  ,a.id_poli
                  from  klinik.klinik_reg_antrian_reguler a
                  left join global.global_customer_user b on a.id_cust_usr = b.cust_usr_id
                  left join klinik.klinik_antrian_reguler c on c.id_dep = a.id_dep
                  where a.reg_antri_suara = '0' and a.id_loket='2' 
                  and a.id_dep = ".QuoteValue(DPE_CHAR,$depId)." and a.reg_antri_tanggal = ".QuoteValue(DPE_DATE,$tgl)."
                  order by reg_antri_nomer asc";
          $dataTable = $dtaccess->FetchAll($sql); 
					print_r($dataTable); 
					
      
      $sql = "select * from global.global_departemen where dep_id='$depId'";
     $depKonfig = $dtaccess->Fetch($sql); 
     
      $counter=0;
	    $counterHeader = 0;       
      
          $tbHeader[0][$counterHeader][TABLE_ISI] = "Proses";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "5%";
          $counterHeader++;
     
          $tbHeader[0][$counterHeader][TABLE_ISI] = "Nomor Antrian";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "5%";
          $counterHeader++;
          
          $tbHeader[0][$counterHeader][TABLE_ISI] = "Jenis";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "15%";
          $counterHeader++;
      
     for($i=0,$counter=0,$n=count($dataTable);$i<$n;$i++,$counter=0){
			
        $tbContent[$i][$counter][TABLE_ISI] = '<a href="next_antrian_2.php?id_antri='.$dataTable[$i]["reg_antri_id"].'"><img hspace="2" width="32" height="32" src="'.$ROOT.'gambar/icon/edit.png" alt="Proses" title="Proses" border="0" ></a>';
			  $tbContent[$i][$counter][TABLE_ALIGN] = "center";
        $counter++;
        
        $tbContent[$i][$counter][TABLE_ISI] = "<b>".$dataTable[$i]["reg_antri_nomer"]."</b>";
			  $tbContent[$i][$counter][TABLE_ALIGN] = "center";
        $counter++;
        
        //if($dataTable[$i]["id_poli"]==1) $jenisNama="BPJS BARU";
        //if($dataTable[$i]["id_poli"]==2) $jenisNama="UMUM";
        //if($dataTable[$i]["id_poli"]==3) $jenisNama="ONLINE UMUM";
        //if($dataTable[$i]["id_poli"]==4) $jenisNama="BPJS LAMA";
        //if($dataTable[$i]["id_poli"]==5) $jenisNama="ONLINE BPJS";
        
        $jenisNama = $depKonfig["dep_nama_antrian_loket_dua"];
        
        $tbContent[$i][$counter][TABLE_ISI] = "&nbsp;".$jenisNama;
			  $tbContent[$i][$counter][TABLE_ALIGN] = "left";
        $counter++;
     }
     
       return $tableAntri->RenderView($tbHeader,$tbContent,$tbBottom);
		
	}
  
   function GetMasuk($status) 
   {
     global $dtaccess, $view, $tableAntri, $ROOT,$ROOT, $poliId, $depId, $nextPage, $tgl, $depKonfig;
     
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
          
          $tbHeader[0][$counterHeader][TABLE_ISI] = "Jenis";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "15%";
          $counterHeader++;
          
          $tbHeader[0][$counterHeader][TABLE_ISI] = "Loket";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "15%";
          $counterHeader++;
          
          $tbHeader[0][$counterHeader][TABLE_ISI] = "Registrasi";
          $tbHeader[0][$counterHeader][TABLE_WIDTH] = "5%";
          $counterHeader++;
          
     for($i=0,$counter=0,$n=count($dataTable);$i<$n;$i++,$counter=0){
     
      
        $tbContent[$i][$counter][TABLE_ISI] = '<a href="next_antrian_2.php?id_antri='.$dataTable[$i]["reg_antri_nomer"].'"><b>'.$dataTable[$i]["reg_antri_nomer"].'</b></a>';
			  $tbContent[$i][$counter][TABLE_ALIGN] = "center";
        $counter++;
        
        if($dataTable[$i]["id_poli"]==1) $jenisNama="BPJS BARU";
        if($dataTable[$i]["id_poli"]==2) $jenisNama="UMUM";
        if($dataTable[$i]["id_poli"]==3) $jenisNama="ONLINE UMUM";       
        if($dataTable[$i]["id_poli"]==4) $jenisNama="BPJS LAMA";
        if($dataTable[$i]["id_poli"]==5) $jenisNama="ONLINE BPJS";
        
        
        $tbContent[$i][$counter][TABLE_ISI] = "&nbsp;".$jenisNama;
			  $tbContent[$i][$counter][TABLE_ALIGN] = "left";
        $counter++;
        
        if($dataTable[$i]["id_loket"]==1) $loket="LOKET 1";
        if($dataTable[$i]["id_loket"]==2) $loket="LOKET 2";
        if($dataTable[$i]["id_loket"]==3) $loket="LOKET 3";
        if($dataTable[$i]["id_loket"]==4) $loket="LOKET 4";
        if($dataTable[$i]["id_loket"]==5) $loket="LOKET 5";
        
        $tbContent[$i][$counter][TABLE_ISI] = "&nbsp;".$loket;
			  $tbContent[$i][$counter][TABLE_ALIGN] = "left";
        $counter++;
        
        if(!$dataTable[$i]["id_reg"] && $dataTable[$i]["id_loket"]=="2"){   
        $tbContent[$i][$counter][TABLE_ISI] = '<a target = "_blank" href="'.$depKonfig["dep_dir"].'/production/data_pasien/registrasi_pasien_awal.php?id_klinik_waktu_tunggu='.$dataTable[$i]["klinik_waktu_tunggu_id"].'&id_loket='.$dataTable[$i]["id_loket"].'&reguler=1"><img hspace="2" width="32" height="32" src="'.$ROOT.'gambar/icon/cari.png" alt="Proses" title="Proses" border="0" ></a>';
			  } else {
        $tbContent[$i][$counter][TABLE_ISI] = "&nbsp;";
        }
        $tbContent[$i][$counter][TABLE_ALIGN] = "center";
        $counter++;
        
     }
     
       return $tableAntri->RenderView($tbHeader,$tbContent,$tbBottom);
		
	}
  

	
     function SetAntrian($id,$loket) 
     {
		 global $dtaccess, $depId;
     
     $sql = "select * from global.global_departemen where dep_id='$depId'";
     $depKonfig = $dtaccess->Fetch($sql); 
    // return $id."-".$loket;  
    // cek data nomer antriannya dahulu //
		 $sql = "select * from klinik.klinik_antrian_reguler where id_dep = ".QuoteValue(DPE_CHAR,$depId)." order by antri_id asc";
     $rs = $dtaccess->Execute($sql);
     $dataAntrian = $dtaccess->FetchAll($rs);
     // buat flag A jika gak ada antrian //
         $sql = "update klinik.klinik_antrian_reguler set antri_suara='A' where 
         antri_id = ".QuoteValue(DPE_NUMERIC,$dataAntrian[0]["antri_id"])." and id_dep = ".QuoteValue(DPE_CHAR,$depId);    		 
         $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
     
    		 
    		 // ambil data utk di update ke klinik antrian //
    		 $sql = "select id_cust_usr, reg_antri_nomer, id_poli, id_klinik_waktu_tunggu from klinik.klinik_reg_antrian_reguler 
                where id_dep =".QuoteValue(DPE_CHAR,$depId)." and reg_antri_id = ".QuoteValue(DPE_CHAR,$id);
    		 $dataPasien = $dtaccess->Fetch($sql);
    		 
    		 // Cek data Data Antrian di klinik antrian //
    		 $sql = "select antri_id from klinik.klinik_antrian_reguler where id_dep =".QuoteValue(DPE_CHAR,$depId)." order by antri_id asc";
    		 $dataAntrian = $dtaccess->FetchAll($sql);
    		 
    		 // update data klinik antrian jika ngga ada antrian buffer //
    		 $sql = "update klinik.klinik_antrian_reguler set id_poli = ".QuoteValue(DPE_CHAR,$loket)." , 
                id_loket = ".QuoteValue(DPE_CHAR,$loket)." , 
                id_cust_usr = ".QuoteValue(DPE_CHAR,$dataPasien["id_cust_usr"])." , antri_nomer = ".QuoteValue(DPE_NUMERIC,$dataPasien["reg_antri_nomer"])." 
                where antri_id = ".QuoteValue(DPE_CHAR,$dataAntrian[0]["antri_id"])." and id_dep = ".QuoteValue(DPE_CHAR,$depId);
    		 $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
    		 
		
    		 // UPDATE STATUS PASIENNYA //
    		 $sql = "update klinik.klinik_reg_antrian_reguler set reg_panggil='y', reg_antri_suara = 'A', antri_aktif='y', id_loket=".QuoteValue(DPE_CHAR,$loket)." 
                where reg_antri_id = ".QuoteValue(DPE_CHAR,$id)." and id_dep = ".QuoteValue(DPE_CHAR,$depId);
    		 $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);

    		 $sql = "update klinik.klinik_reg_antrian_reguler set antri_aktif ='n' where id_loket=".QuoteValue(DPE_CHAR,$loket)." 
                and reg_antri_id <> ".QuoteValue(DPE_CHAR,$id)." and id_dep = ".QuoteValue(DPE_CHAR,$depId);
    		 $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
         
         //update waktu tunggu 
    		 $sql = "update klinik.klinik_waktu_tunggu set panggil_antrian=".QuoteValue(DPE_DATE,date("Y-m-d H:i:s")).", 
                input_rm = ".QuoteValue(DPE_DATE,date("Y-m-d H:i:s"))." where klinik_waktu_tunggu_id = ".QuoteValue(DPE_CHAR,$dataPasien["id_klinik_waktu_tunggu"]);
    		 $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
    		 
		  return true;
	}
	
	
  if($_GET["id_antri"]) 
  {
     $sql = "select * from klinik.klinik_reg_antrian_reguler where reg_antri_id = ".QuoteValue(DPE_CHAR,$_GET["id_antri"])." and id_dep = ".QuoteValue(DPE_CHAR,$depId);
     $rs = $dtaccess->Execute($sql);
     $dataPasienAntriReg = $dtaccess->Fetch($rs);
     
     if($dataPasienAntriReg["id_poli"]==1) $poliNama="BARU";
     if($dataPasienAntriReg["id_poli"]==2) $poliNama="LAMA";
  }                  
   
          
?>

<script type="text/javascript">
<? $plx->Run(); ?>

var mTimer;
function timer(){     
     clearInterval(mTimer);      
     GetAntri(0,'target=antri_kiri');  
     GetMasuk(0,'target=antri_kanan');  
     mTimer = setTimeout("timer()", 2000);
}

timer();

</script>

<script type="text/javascript">

function ProsesAntrian(id,loket) {
//alert(id);
	SetAntrian(id,loket,'type=r');
}

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
          <?php if($_GET["id_antri"]) { ?>
            <div class="page-title">
              <div class="title_left">
                <h1>NOMOR ANTRIAN : <? echo "<b>".$dataPasienAntriReg["reg_antri_nomer"]."</b>&nbsp;-&nbsp;".$poliNama;?></h1>
                   <ul class="nav navbar-right panel_toolbox">
                      <button id="btnPanggil" name="btnPanggil" type="submit" value="PANGGIL LOKET 2" class="btn btn-primary" onClick="ProsesAntrian('<? echo $_GET["id_antri"]?>','2')" border="0" />Panggil Loket 2</button>                      <li class="dropdown">
                    </ul>
              </div>
            </div>
             <?php } ?>
            <div class="clearfix"></div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                  <div class="x_content">
                    <div class="table-responsive">
                      <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <tr class="even pointer">
                             <td width="50%"> <div id="antri_kiri" style="float:left;height:50%;width:100%;overflow:auto"><?php echo GetAntri(STATUS_ANTRI); ?></div></td>
                             <td width="1%">&nbsp;</td>
                             <td width="50%"> <div id="antri_kanan" style="float:left;height:50%;width:100%;overflow:auto"><?php echo GetMasuk(STATUS_ANTRI); ?></div></td>	
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

                                                