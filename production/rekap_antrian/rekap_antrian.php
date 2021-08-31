<?php
     require_once("../penghubung.inc.php");
     require_once($LIB."/login.php");
     require_once($LIB."/encrypt.php");
     require_once($LIB."/datamodel.php");
     require_once($LIB."/dateLib.php");
     require_once($LIB."/currency.php");
     require_once($LIB."/tampilan.php");
     
     $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
     $dtaccess = new DataAccess();
     $enc = new textEncrypt();     
     $auth = new CAuth();
     $table = new InoTable("table","100%","left");
     $depNama = $auth->GetDepNama();
	   $depId = $auth->GetDepId();
	   //Ambil Data Status Departemen Klinik kalau terendah(y) maka tidak keluar combo pilihan Klinik
     $depLowest = $auth->GetDepLowest();
	   $userName = $auth->GetUserName();
     $thisPage = "report_pasien.php";
     $userData = $auth->GetUserData();
    
	if(!$auth->IsAllowed("man_ganti_password",PRIV_CREATE)){
          die("Maaf anda tidak berhak membuka halaman ini....");
          exit(1);
     } else 
      if($auth->IsAllowed("man_ganti_password",PRIV_CREATE)===1){
          echo"<script>window.parent.document.location.href='".$ROOT."login/login.php?msg=Login First'</script>";
          exit(1);
     }

      
    if(!$_POST["klinik"]) $_POST["klinik"]=$depId;
	   else $_POST["klinik"] = $_POST["klinik"];      
    
     $skr = date("d-m-Y");
     if(!$_POST["tgl_awal"]) $_POST["tgl_awal"] = $skr;
     
     $sql_where[] = "1=1";
          
     if($_POST["klinik"] && $_POST["klinik"]!="--") $sql_where[] = "a.id_dep like ".QuoteValue(DPE_CHAR,"%".$_POST["klinik"]."%");
     if($_POST["tgl_awal"]) $sql_where[] = "a.reg_tanggal >= ".QuoteValue(DPE_DATE,date_db($_POST["tgl_awal"]));
     
     $tableHeader = "Rekap Antrian Pasien";
     

     $sql = "select * from global.global_departemen where dep_id =".QuoteValue(DPE_CHAR,$depId);
     $rs = $dtaccess->Execute($sql);
     $konfigurasi = $dtaccess->Fetch($rs);
     
 
     
     $sql = "select * from global.global_agama "  ;
     $rs = $dtaccess->Execute($sql);
     $dataAgama = $dtaccess->FetchAll($rs);
     
     
     
     
     if($konfigurasi["dep_lowest"]=='n'){
          $sql = "select * from global.global_departemen order by dep_id";
          $rs = $dtaccess->Execute($sql);
          $dataKlinik = $dtaccess->FetchAll($rs);
     }else if($_POST["klinik"]){
     //Data Klinik
          $sql = "select * from global.global_departemen where dep_id = '".$_POST["klinik"]."' order by dep_id";
          $rs = $dtaccess->Execute($sql);
          $dataKlinik = $dtaccess->FetchAll($rs);
     }else{
          $sql = "select * from global.global_departemen order by dep_id";
          $rs = $dtaccess->Execute($sql);
          $dataKlinik = $dtaccess->FetchAll($rs);
     }
     $sql = "select * from global.global_auth_poli";
     $rs = $dtaccess->Execute($sql);
     $dataPoli = $dtaccess->FetchAll($rs);
     
     	if($_POST["btnExcel"]){
          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment; filename=Rekap_Agama.xls');
     }
     
        if($_POST["btnCetak"]){
   //echo $_POST["ush_id"];
   //die();
      $_x_mode = "cetak" ;      
   }
   
  
     
?>


<!DOCTYPE html>
<html lang="en">
  <?php require_once($LAY."header.php") ?>

  <body class="nav-sm">
    <div class="container body">
      <div class="main_container">
        <?php require_once($LAY."sidebar.php") ?>

        <!-- top navigation -->
          <?php require_once($LAY."topnav.php") ?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
			<div class="clearfix"></div>
			<!-- row filter -->
			<div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Rekap Antrian</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
				  <form name="frmView" action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST" >
				  

			<div class="col-md-4 col-sm-6 col-xs-12">
                        <label class="control-label col-md-12 col-sm-12 col-xs-12">Periode Tanggal (DD-MM-YYYY)</label>
                        <div class='input-group date' id='datepicker'>
							<input name="tgl_awal" type='text' class="form-control" 
							value="<?php if ($_POST['tgl_awal']) { echo $_POST['tgl_awal']; } else { echo date('d-m-Y'); } ?>"  />
							<span class="input-group-addon">
								<span class="fa fa-calendar">
								</span>
							</span>
						</div>	           			 
			
                             			 
				    </div>
				    				    
					<div class="col-md-4 col-sm-6 col-xs-12">
                        <label class="control-label col-md-12 col-sm-12 col-xs-12">&nbsp;</label>						
						<input type="submit" name="btnLanjut" value="Lanjut" class="pull-right btn btn-primary">
               			<!--<input type="submit" name="btnExcel" value="Export Excel" class="pull-right btn btn-success">  -->
               			<input type="submit" name="btnCetak" id="btnCetak" value="Cetak" class="pull-right btn btn-primary">
				    </div>
					<div class="clearfix"></div>
					<? if($_POST['btnLanjut'] || $_GET['edt'] || $_GET['tambah'] || $_GET['Kembali'] || $_GET["id_tahun_tarif"]){?>
					<?}?>
					<? if ($_x_mode == "Edit"){ ?>
					<?php echo $view->RenderHidden("kategori_tindakan_id","kategori_tindakan_id",$biayaId);?>
					<? } ?>
					
					
					</form>
                  </div>
                </div>
              </div>
            </div>
			<!-- //row filter -->


              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
					   <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>                                                     
                               <th class="column-title">Tanggal</th>
                               <th class="column-title">BPJS Baru</th>
                               <th class="column-title">BPJS Lama</th>
                               <th class="column-title">Online Umum</th>
                               <th class="column-title">Online BPJS</th>
                               <th class="column-title">Umum</th>
                               <th class="column-title">Poli TB Dots</th>
                        </tr>
                      </thead>
                      <tbody>
<?
               $sql_where_jml1 = "select count(rekap_antrian_id) as total from klinik.klinik_rekap_antrian 
               where 
               id_jenis_pasien = ".QuoteValue(DPE_NUMERIC,5)." AND
               rekap_antrian_jenis_pasien = ".QuoteValue(DPE_CHAR,'B')." AND
               rekap_antrian_tanggal = ".QuoteValue(DPE_DATE,date_db($_POST['tgl_awal']));
               $dataAntrianPasien1 = $dtaccess->Fetch($sql_where_jml1);  
               
               $sql_where_jml2 = "select count(rekap_antrian_id) as total from klinik.klinik_rekap_antrian 
               where 
               id_jenis_pasien = ".QuoteValue(DPE_NUMERIC,5)." AND 
               rekap_antrian_jenis_pasien = ".QuoteValue(DPE_CHAR,'L')."  AND
               rekap_antrian_tanggal = ".QuoteValue(DPE_DATE,date_db($_POST['tgl_awal']));
               $dataAntrianPasien2 = $dtaccess->Fetch($sql_where_jml2);
               
               $sql_where_jml3 = "select count(rekap_antrian_id) as total from klinik.klinik_rekap_antrian 
               where 
               id_jenis_pasien = ".QuoteValue(DPE_NUMERIC,2)." AND 
               rekap_antrian_jenis_pasien = ".QuoteValue(DPE_CHAR,'o')." AND 
               rekap_antrian_tanggal = ".QuoteValue(DPE_DATE,date_db($_POST['tgl_awal']));
               $dataAntrianPasien3 = $dtaccess->Fetch($sql_where_jml3);
               
               $sql_where_jml4 = "select count(rekap_antrian_id) as total from klinik.klinik_rekap_antrian 
               where 
               id_jenis_pasien = ".QuoteValue(DPE_NUMERIC,5)." AND 
               rekap_antrian_jenis_pasien = ".QuoteValue(DPE_CHAR,'o')." AND 
               rekap_antrian_tanggal = ".QuoteValue(DPE_DATE,date_db($_POST['tgl_awal']));
               $dataAntrianPasien4 = $dtaccess->Fetch($sql_where_jml4);
               
               $sql_where_jml5 = "select count(rekap_antrian_id) as total from klinik.klinik_rekap_antrian 
               where 
               id_jenis_pasien = ".QuoteValue(DPE_NUMERIC,2)." AND 
               rekap_antrian_jenis_pasien = ".QuoteValue(DPE_CHAR,'L')." AND 
               rekap_antrian_tanggal = ".QuoteValue(DPE_DATE,date_db($_POST['tgl_awal']));
               $dataAntrianPasien5 = $dtaccess->Fetch($sql_where_jml5);
               
               $sql_where_jml6 = "select count(rekap_antrian_id) as total from klinik.klinik_rekap_antrian 
               where 
               id_jenis_pasien = ".QuoteValue(DPE_NUMERIC,2)." AND 
               rekap_antrian_jenis_pasien = ".QuoteValue(DPE_CHAR,'t')." AND 
               rekap_antrian_tanggal = ".QuoteValue(DPE_DATE,date_db($_POST['tgl_awal']));
               $dataAntrianPasien6 = $dtaccess->Fetch($sql_where_jml6);


?>                         
                          <tr class="even pointer">
                            <td class=" "><?php echo $_POST['tgl_awal'];?></td>
                            <td class=" "><?php echo $dataAntrianPasien1["total"];?></td>
                            <td class=" "><?php echo $dataAntrianPasien2["total"];?></td>
                            <td class=" "><?php echo $dataAntrianPasien3["total"];?></td>
                            <td class=" "><?php echo $dataAntrianPasien4["total"];?></td>
                            <td class=" "><?php echo $dataAntrianPasien5["total"];?></td>
                            <td class=" "><?php echo $dataAntrianPasien6["total"];?></td>
                          </tr>
                           
                      </tbody>
                    </table>					
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
          <?php require_once($LAY."footer.php") ?>
        <!-- /footer content -->
      </div>
    </div>

<?php require_once($LAY."js.php") ?>

  </body>
</html>

</script>
<?php if(!$_POST["btnExcel"]) { ?>

<br />
<?php } ?>
<script language="JavaScript">

window.onload = function() { TampilCombo(); }


<?php if($_x_mode=="cetak"){ ?>	
  window.open('rekap_antrian_cetak.php?tgl_awal=<?php echo $_POST["tgl_awal"];?>&tgl_akhir=<?php echo $_POST["tgl_akhir"];?>&jenis=<?php echo $_POST["id_jenis_pasien"];?>&tipe_jenis=<?php echo $_POST["rekap_antrian_jenis_pasien"];?>', '_blank');
<?php } ?>

</script>


