<?php

    require_once("../penghubung.inc.php");
     require_once($LIB."login.php");
     require_once($LIB."encrypt.php");
     require_once($LIB."datamodel.php");
     require_once($LIB."currency.php");
     require_once($LIB."dateLib.php");
     require_once($LIB."expAJAX.php");
     require_once($LIB."tampilan.php");	
     
     // INISIALISASY LIBRARY
     $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
     $dtaccess = new DataAccess();   
	   $auth = new CAuth();
	   $depNama = $auth->GetDepNama();
	   $userName = $auth->GetUserName();
	   $depId = $auth->GetDepId();
     $viewPage = "konfigurasi_edit.php";
     
        if(!$auth->IsAllowed("man_pengaturan_konf_antrian",PRIV_READ)){
          die("access_denied");
          exit(1);

     } elseif($auth->IsAllowed("man_pengaturan_konf_antrian",PRIV_READ)===1){
          echo"<script>window.parent.document.location.href='".$ROOT."login.php?msg=Session Expired'</script>";
          exit(1);
     } 

     $sql = "select * from global.global_video_antrian order by video_antrian_id asc";
     $rs = $dtaccess->Execute($sql);
     $dataVideoAnt = $dtaccess->FetchAll($rs);


     if ($_POST["btnSave"] || $_POST["btnUpdate"] ) {
     
         
		  $dbTable = "global.global_departemen";
               
               $dbField[0] = "dep_id";   // PK
               $dbField[1] = "dep_header_kanan_antrian"; 
 		       $dbField[2] = "dep_footer_antrian"; 
 		       $dbField[3] = "dep_logo_kanan_antrian";
               $dbField[4] = "dep_logo_kiri_antrian"; 
               
               $dbField[5] = "dep_antrian_2_tipe";
               $dbField[6] = "dep_antrian_3_tipe";
               $dbField[7] = "dep_antrian_1_tipe";
               $dbField[8] = "dep_nama_antrian_loket_satu";
               $dbField[9] = "dep_nama_antrian_loket_dua";
               $dbField[10] = "dep_nama_antrian_loket_tiga";
               $dbField[11] = "dep_nama_antrian_loket_empat";
               $dbField[12] = "dep_nama_antrian_loket_lima";
               $dbField[13] = "dep_nama_antrian_loket_enam";
               $dbField[14] = "dep_no_urut_antrian_loket_satu";
               $dbField[15] = "dep_no_urut_antrian_loket_dua";
               $dbField[16] = "dep_no_urut_antrian_loket_tiga";
               $dbField[17] = "dep_no_urut_antrian_loket_empat";
               $dbField[18] = "dep_no_urut_antrian_loket_lima";
               $dbField[19] = "dep_no_urut_antrian_loket_enam";
               $dbField[20] = "dep_waktu_awal_antrian_pagi";
               $dbField[21] = "dep_waktu_akhir_antrian_pagi";
               $dbField[22] = "dep_waktu_awal_antrian_sore";
               $dbField[23] = "dep_waktu_akhir_antrian_sore";
               
               
               
			       
               $dbValue[0] = QuoteValue(DPE_CHAR,$depId);                                                           
               $dbValue[1] = QuoteValue(DPE_CHAR,$_POST["dep_header_kanan_antrian"]);  
	           $dbValue[2] = QuoteValue(DPE_CHAR,$_POST["dep_footer_antrian"]); 
 			   $dbValue[3] = QuoteValue(DPE_CHAR,$_POST["dep_logo_kanan_antrian"]);
               $dbValue[4] = QuoteValue(DPE_CHAR,$_POST["dep_logo_kiri_antrian"]);
			         
               $dbValue[5] = QuoteValue(DPE_CHAR,($_POST["dep_antrian_2_tipe"]));  
               $dbValue[6] = QuoteValue(DPE_CHAR,($_POST["dep_antrian_3_tipe"]));  
               $dbValue[7] = QuoteValue(DPE_CHAR,($_POST["dep_antrian_1_tipe"]));  
               
               $dbValue[8] = QuoteValue(DPE_CHAR,($_POST["dep_nama_antrian_loket_satu"]));
               $dbValue[9] = QuoteValue(DPE_CHAR,($_POST["dep_nama_antrian_loket_dua"]));
               $dbValue[10] = QuoteValue(DPE_CHAR,($_POST["dep_nama_antrian_loket_tiga"]));
               $dbValue[11] = QuoteValue(DPE_CHAR,($_POST["dep_nama_antrian_loket_empat"]));
               $dbValue[12] = QuoteValue(DPE_CHAR,($_POST["dep_nama_antrian_loket_lima"]));
               $dbValue[13] = QuoteValue(DPE_CHAR,($_POST["dep_nama_antrian_loket_enam"]));
               
               $dbValue[14] = QuoteValue(DPE_CHAR,($_POST["dep_no_urut_antrian_loket_satu"]));
               $dbValue[15] = QuoteValue(DPE_CHAR,($_POST["dep_no_urut_antrian_loket_dua"]));
               $dbValue[16] = QuoteValue(DPE_CHAR,($_POST["dep_no_urut_antrian_loket_tiga"]));
               $dbValue[17] = QuoteValue(DPE_CHAR,($_POST["dep_no_urut_antrian_loket_empat"]));
               $dbValue[18] = QuoteValue(DPE_CHAR,($_POST["dep_no_urut_antrian_loket_lima"]));
               $dbValue[19] = QuoteValue(DPE_CHAR,($_POST["dep_no_urut_antrian_loket_enam"]));
               $dbValue[20] = QuoteValue(DPE_CHAR,($_POST["dep_waktu_awal_antrian_pagi"]));
               $dbValue[21] = QuoteValue(DPE_CHAR,($_POST["dep_waktu_akhir_antrian_pagi"]));
               $dbValue[22] = QuoteValue(DPE_CHAR,($_POST["dep_waktu_awal_antrian_sore"]));
               $dbValue[23] = QuoteValue(DPE_CHAR,($_POST["dep_waktu_akhir_antrian_sore"]));
               
               

              //print_r($dbValue);
              //die();
          		$dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
          		$dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
          		$dtmodel->Update() or die("update  error");	 
          		$simpan=1;
          		
          		unset($dtmodel);
          		unset($dbField);
          		unset($dbValue);
          		unset($dbKey);
       /*
       for($i=0,$n=count($_POST["video_antrian_id"]);$i<$n;$i++){
      $sql = "update global.global_video_antrian set
              urutan = '".$_POST["ubah".$i]."'
              where id_dep  =".QuoteValue(DPE_CHAR,$depId)." 
              and video_antrian_id ='".$_POST["video_antrian_id"][$i]."'";
       $rs = $dtaccess->Execute($sql);             
     
     }  */
    $xml= new SimpleXMLElement('<playlist></playlist>');
//    $xml->father['name']= 'Fathers name'; // creates automatically a father tag with attribute name
    
  for($i=0,$n=count($dataVideoAnt);$i<$n;$i++){
    $son= $xml->addChild('playitem'); // uses the first father tag
    $caption = explode(".",$dataVideoAnt[$i]["video_antrian_nama"]);
    
    $son['caption']= "".$caption[0]."";
    $son['path']= "".$dataVideoAnt[$i]["video_antrian_nama"]."";
    $son['image']= "video.jpg";
    $son['options']= "";
    $son['clickurl']= "";
    $son['clicktarget']= "_blank";
    $son['endurl']= "";
    $son['styleoftarget']= "browser";
    $son['endtarget']= "";
    }

    
      //$xml->formatOutput = true;
      //echo "<xmp>". $xml->saveXML() ."</xmp>";
      $xml->asXML($ROOT."lcd/medialist.xml") or die("Error");
      
     }
     
	$lokasi = $ROOT."lcd";
	$lokasiVideo = $ROOT."lcd/";

	
  
	$sql = "select * from global.global_departemen where dep_id = ".QuoteValue(DPE_CHAR,$depId);
	$rs_edit = $dtaccess->Execute($sql);
	$row_edit = $dtaccess->Fetch($rs_edit);

  //$dtaccess->Clear($rs_edit);
	//echo $sql;
	        $_POST["dep_id"] = $row_edit["dep_id"];
          $_POST["dep_header_kanan_antrian"] = $row_edit["dep_header_kanan_antrian"];
          $_POST["dep_footer_antrian"] = $row_edit["dep_footer_antrian"];
          $_POST["dep_logo_kanan_antrian"] = $row_edit["dep_logo_kanan_antrian"];    
          $_POST["dep_video_antrian"] = $row_edit["dep_video_antrian"]; 
          $_POST["dep_logo_kiri_antrian"] = $row_edit["dep_logo_kiri_antrian"];
          $_POST["dep_no_urut_antrian_reguler"] = $row_edit["dep_no_urut_antrian_reguler"];
          $_POST["dep_no_urut_jkn_antrian_reguler"] = $row_edit["dep_no_urut_jkn_antrian_reguler"];
          $_POST["dep_no_urut_antrian_ekse"] = $row_edit["dep_no_urut_antrian_ekse"];
          $_POST["dep_no_urut_jkn_antrian_ekse"] = $row_edit["dep_no_urut_jkn_antrian_ekse"];
          $_POST["dep_no_urut_antrian_rehab"] = $row_edit["dep_no_urut_antrian_rehab"];
          $_POST["dep_no_urut_jkn_antrian_rehab"] = $row_edit["dep_no_urut_jkn_antrian_rehab"];
          $_POST["dep_no_urut_antrian_rehab_ekse"] = $row_edit["dep_no_urut_antrian_rehab_ekse"];
          $_POST["dep_no_urut_jkn_antrian_rehab_ekse"] = $row_edit["dep_no_urut_jkn_antrian_rehab_ekse"];
          $_POST["dep_no_urut_antrian_spesialis"] = $row_edit["dep_no_urut_antrian_spesialis"];
          $_POST["dep_no_urut_jkn_antrian_spesialis"] = $row_edit["dep_no_urut_jkn_antrian_spesialis"];
          
          $_POST["dep_nama_antrian_loket_satu"] = $row_edit["dep_nama_antrian_loket_satu"];
          $_POST["dep_nama_antrian_loket_dua"] = $row_edit["dep_nama_antrian_loket_dua"];
          $_POST["dep_nama_antrian_loket_tiga"] = $row_edit["dep_nama_antrian_loket_tiga"];
          $_POST["dep_nama_antrian_loket_empat"] = $row_edit["dep_nama_antrian_loket_empat"];
          $_POST["dep_nama_antrian_loket_lima"] = $row_edit["dep_nama_antrian_loket_lima"];
          $_POST["dep_nama_antrian_loket_enam"] = $row_edit["dep_nama_antrian_loket_enam"];
          
          $_POST["dep_no_urut_antrian_loket_satu"] = $row_edit["dep_no_urut_antrian_loket_satu"];
          $_POST["dep_no_urut_antrian_loket_dua"] = $row_edit["dep_no_urut_antrian_loket_dua"];
          $_POST["dep_no_urut_antrian_loket_tiga"] = $row_edit["dep_no_urut_antrian_loket_tiga"];
          $_POST["dep_no_urut_antrian_loket_empat"] = $row_edit["dep_no_urut_antrian_loket_empat"];
          $_POST["dep_no_urut_antrian_loket_lima"] = $row_edit["dep_no_urut_antrian_loket_lima"];
          $_POST["dep_no_urut_antrian_loket_enam"] = $row_edit["dep_no_urut_antrian_loket_enam"];
          
          
          $_POST["dep_antrian_1_tipe"] = $row_edit["dep_antrian_1_tipe"]; 
          $_POST["dep_antrian_2_tipe"] = $row_edit["dep_antrian_2_tipe"]; 
          $_POST["dep_antrian_3_tipe"] = $row_edit["dep_antrian_3_tipe"]; 
    //$view->CreatePost($row_edit);
	
  if (!$_POST["dep_logo_kanan_antrian"])
     {
        $_POST["dep_logo_kanan_antrian"] = "default.jpg";
        
     }
     $fotoName = $lokasi."/".$_POST["dep_logo_kanan_antrian"];
  
  if (!$_POST["dep_logo_kiri_antrian"])
     {
        $_POST["dep_logo_kiri_antrian"] = "default.jpg";
        
     }
     $fotoGambar = $lokasi."/".$_POST["dep_logo_kiri_antrian"];
     
   if (!$_POST["dep_video_antrian"])
     {
        $_POST["dep_video_antrian"] = "default.jpg";
        
     }
     $videoName = $lokasiVideo."/".$_POST["dep_video_antrian"];  
     
     $tableHeader = "Manajemen - Konfigurasi Antrian";


		 $sql ="select * from global.global_video_antrian order by urutan";
     $foto = $dtaccess->FetchAll($sql);
	   $uploadPath ="../lcd/";
		
	
?>


<!DOCTYPE html>
<html lang="en">
  <?php require_once($LAY."header.php"); ?>
	<link rel="stylesheet" href="plugin/fancy_fileupload.css" type="text/css" media="all" />


<script type="text/javascript">

	

function ajaxFileUpload()
	{
	
		$.ajaxFileUpload
		(
			{
				url:'konfigurasi_pic.php',
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'JSON',
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else                               
						{
							alert(data.msg);
              document.getElementById('dep_logo_kiri_antrian').value= data.file; 
              //document.img_cfg.src='<?php echo $lokasi."/";?>'+data.file;
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
		// setTimeout(() => {
		// 	location.reload();
		// }, 2000);
	
		return false;


	}

function show_urutan(id,nama,urut){
		$('#id_video').val(id);
		$('#nama_video').val(nama);
		$('#urutan_video').val(urut);
		$('#myModal').modal('show');


}

function proses_urut() {
			var urut = $('#urutan_video').val();
			var id =$('#id_video').val();
			$.post('proses_urut.php',{id:id,urut:urut});
		 
		 setTimeout(() => {
			 location.reload();
		 }, 500); 
}

  
function CheckDataSave(frm)
{ 
     
     if(!frm.dep_nama.value){
		alert('Header1 Harus Diisi');
		frm.dep_nama.focus();
          return false;
	}
	
	if(!frm.dep_kop_surat_1.value){
		alert('Header2 Harus Diisi');
		frm.dep_kop_surat_1.focus();
          return false;       
  }
    
   if(!frm.dep_kop_surat_2.value){
		alert('Header3 Harus Diisi');
		frm.dep_kop_surat_2.focus();
          return false;
	}
	
	if(!frm.dep_website.value){
		alert('Nama Website Klinik harus di isi');
		frm.dep_website.focus();
          return false;
	} 
  
      if(!frm.pgw_nama.value){
		alert('Nama Dokter harus Diisi');
		frm.pgw_nama.focus();
          return false;
	  } 
	
	return true;	
}	


</script>

<script language="javascript" type="text/javascript">
function CheckPasien(frm)
{ 
     if(!frm.jenis_nama.value){
		alert('Nama Untuk Jenis Pasien Harus Diisi');
		frm.jenis_nama.focus();
          return false;
	}
     	return true;      
}

function CheckGigi(frm)
{ 
     if(!frm.gigi_nama.value){
		alert('Nama Gigi Harus Diisi');
		frm.gigi_nama.focus();
          return false;
	}
     	return true;      
}

function CheckLabel(frm)
{ 
     if(!frm.cust_ket_nama.value){
		alert('Nama Label Harus Diisi');
		frm.cust_ket_nama.focus();
          return false;
	}
     	return true;      
}

function CheckShift1(frm)
{ 
     if(!frm.dep_shift_1.value){
		alert('Jam Shift Harus Diisi');
		frm.dep_shift_1.focus();
          return false;
	}
     	return true;      
}

function CheckShift2(frm)
{ 
     if(!frm.dep_shift_2.value){
		alert('Jam Shift Harus Diisi');
		frm.dep_shift_2.focus();
          return false;
	}
     	return true;      
}

function CheckShift3(frm)
{ 
     if(!frm.dep_shift_3.value){
		alert('Jam Shift Harus Diisi');
		frm.dep_shift_3.focus();
          return false;
	}
     	return true;      
}

</script>

  <body class="nav-sm">
    <div class="container body">
      <div class="main_container">
        
		<?php require_once($LAY."sidebar.php"); ?>

        <!-- top navigation -->
		<?php require_once($LAY."topnav.php"); ?>
		<!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Manajemen</h3>
              </div>
            </div>

            <div class="clearfix"></div>
			    <?php if($simpan) { ?>
					<font color="red"><strong>Konfigurasi telah disimpan, klik tombol KELUAR pada MENU UTAMA agar perubahan Konfigurasi terjadi.</strong></font>
				<?php } ?>
            <div class="row"> <!-- ==== BARIS ===== -->
			<!-- ==== kolom kiri ===== -->
			<!-- ==== mulai form ===== -->
			<form id="demo-form2" method="POST" enctype="multipart/form-data"  class="form-horizontal form-label-left" action="<?php echo $_SERVER["PHP_SELF"]?>">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Konfigurasi Antrian</h2>
                    <span class="pull-right"></span>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
				  
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Header Antrian
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <textarea name="dep_header_kanan_antrian" id="dep_header_kanan_antrian" maxlength="255" size="50"><?php echo $_POST["dep_header_kanan_antrian"];?></textarea>

						            </div>
                      </div>
					  
					            <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Footer Bawah Antrian
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                          <textarea name="dep_footer_antrian" id="dep_footer_antrian" maxlength="255" size="50"><?php echo $_POST["dep_footer_antrian"];?></textarea>
						            </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Waktu Antrian Pagi</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" class="form-control" name="dep_waktu_awal_antrian_pagi" id="dep_waktu_awal_antrian_pagi" value="<?php echo $row_edit["dep_waktu_awal_antrian_pagi"];?>">
                        </div>
                        <div class="col-md- col-sm-1 col-xs-12">
                          <label> - </label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" class="form-control" name="dep_waktu_akhir_antrian_pagi" id="dep_waktu_akhir_antrian_pagi" value="<?php echo $row_edit["dep_waktu_akhir_antrian_pagi"];?>">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Waktu Antrian Sore</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" class="form-control" name="dep_waktu_awal_antrian_sore" id="dep_waktu_awal_antrian_sore" value="<?php echo $row_edit["dep_waktu_awal_antrian_sore"];?>">
                        </div>
                        <div class="col-md- col-sm-1 col-xs-12">
                          <label> - </label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" class="form-control" name="dep_waktu_akhir_antrian_sore" id="dep_waktu_akhir_antrian_sore" value="<?php echo $row_edit["dep_waktu_akhir_antrian_sore"];?>">
                        </div>
                      </div>
                      
                  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Upload Video
                        </label>
                        <!-- <div class="col-md-8 col-sm-8 col-xs-12">
                            
                            <input type="hidden" name="dep_logo_kiri_antrian" id="dep_logo_kiri_antrian" value="<?php echo $_POST["dep_logo_kiri_antrian"];?>">
                            <input align="left" id="fileToUpload" type="file" size="25" name="fileToUpload" class="inputField">
                            <button class="submit" id="buttonUpload" onClick="return ajaxFileUpload();">Upload Video</button>
                            <span id="loading" style="display:none;"><img width="26" height="16"  id="imgloading" src="<?php echo $ROOT;?>gambar/loading.gif"></span>
  					            </div> -->
												<div class="col-md-8 col-sm-8 col-xs-12">
												<label for=""><h4 class="text-center"> Tarik Video ke bawah sini !!! </h4></label>
														<div class="container">
																<input id="thefiles" type="file" name="files" accept="video/mp4,video/x-m4v,video/*" multiple>
																<h4 id="notif"></h4>
														</div>
												</div>




                      </div>
                  </div>  
                  
                </div>
			<!-- ==== panel putih ===== -->
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Galery Video</h2>
                    <span class="pull-right"></span>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
					<table width="100%" border="0">
					<tr>
					<th>Urutan</th>
					<th class='text-center' >Nama</th>
					<th   class='text-center' >Aksi</th>
					</tr>
								<?php for ($i=0; $i <count($foto) ; $i++) { ?>
          <tr>
					 		<td><?=$foto[$i]['urutan'];?></td>
              <td class='text-center' ><?=$foto[$i]['video_antrian_nama'];?>	</td>
							<td class='text-center'>
								<a href="#" class="btn btn-danger btn-xs" onclick= "hapusVideo('<?= $foto[$i]['video_antrian_id'] ; ?>' ,event)"><i class="fa fa-trash  fa-2x"></i></a> ||
								<a href="#"  class="btn btn-info  btn-xs" onclick= "show_urutan('<?= $foto[$i]['video_antrian_id'] ?>','<?= $foto[$i]['video_antrian_nama'] ; ?>','<?=$foto[$i]['urutan'];?>' ,event)"><i class="fa fa-edit  fa-2x"></i></a>
							</td>

          </tr>   
								<?php }  ?>
    
					</table>
					  
                  </div>
                </div>
			<!-- ==== // panel putih ===== -->
      <!-- ==== KHUSUS BUTTON ===== -->
          <div class="x_content">
	            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                   <?php echo $view->RenderButton(BTN_SUBMIT,($_x_mode == "Edit")?"btnUpdate":"btnSave","btnSave","Simpan","submit",false);?><!--,"onClick=\"javascript:return CheckDataSave(this.form);\"");?>  -->    
  
                </div>
              </div>
          </div>
	  <!-- ==== // KHUSUS BUTTON ===== -->
              </div>
			  <!-- ==== // kolom kiri ===== -->


			<!-- ==== kolom kanan ===== -->
			<!-- ==== mulai form ===== -->
			<form id="demo-form2" method="POST" class="form-horizontal form-label-left" action="<?php echo $_SERVER["PHP_SELF"]?>">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Nama Loket Antrian</h2>
                    <span class="pull-right"></span>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
				  
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Antrian 1
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                            <input type="text" class="form-control" name="dep_nama_antrian_loket_satu" id="dep_nama_antrian_loket_satu" value="<?php echo $_POST["dep_nama_antrian_loket_satu"];?>">
						</div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Antrian 2
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                            <input type="text" class="form-control" name="dep_nama_antrian_loket_dua" id="dep_nama_antrian_loket_dua" value="<?php echo $_POST["dep_nama_antrian_loket_dua"];?>">
						</div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Antrian 3
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                            <input type="text" class="form-control" name="dep_nama_antrian_loket_tiga" id="dep_nama_antrian_loket_tiga" value="<?php echo $_POST["dep_nama_antrian_loket_tiga"];?>">
						</div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Antrian 4
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                            <input type="text" class="form-control" name="dep_nama_antrian_loket_empat" id="dep_nama_antrian_loket_empat" value="<?php echo $_POST["dep_nama_antrian_loket_empat"];?>">
						</div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Antrian 5
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                            <input type="text" class="form-control" name="dep_nama_antrian_loket_lima" id="dep_nama_antrian_loket_lima" value="<?php echo $_POST["dep_nama_antrian_loket_lima"];?>">
						</div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Antrian 6
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                            <input type="text" class="form-control" name="dep_nama_antrian_loket_enam" id="dep_nama_antrian_loket_enam" value="<?php echo $_POST["dep_nama_antrian_loket_enam"];?>">
						</div>
                      </div>
					  
					  
                  </div>  
                  
                </div>
			<!-- ==== panel putih ===== -->
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Konfigurasi Nomer Loket Pasien</h2>
                    <span class="pull-right"></span>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
					<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Awal Nomer Loket 1
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                            <input type="text" class="form-control" name="dep_no_urut_antrian_loket_satu" id="dep_no_urut_antrian_loket_satu" value="<?php echo $_POST["dep_no_urut_antrian_loket_satu"];?>">
						</div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Awal Nomer Loket 2
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                            <input type="text" class="form-control" name="dep_no_urut_antrian_loket_dua" id="dep_no_urut_antrian_loket_dua" value="<?php echo $_POST["dep_no_urut_antrian_loket_dua"];?>">
						</div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Awal Nomer Loket 3
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                            <input type="text" class="form-control" name="dep_no_urut_antrian_loket_tiga" id="dep_no_urut_antrian_loket_tiga" value="<?php echo $_POST["dep_no_urut_antrian_loket_tiga"];?>">
						</div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Awal Nomer Loket 4
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                            <input type="text" class="form-control" name="dep_no_urut_antrian_loket_empat" id="dep_no_urut_antrian_loket_empat" value="<?php echo $_POST["dep_no_urut_antrian_loket_empat"];?>">
						</div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Awal Nomer Loket 5
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                            <input type="text" class="form-control" name="dep_no_urut_antrian_loket_lima" id="dep_no_urut_antrian_loket_lima" value="<?php echo $_POST["dep_no_urut_antrian_loket_lima"];?>">
						</div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Awal Nomer Loket 6
                        </label>
                        <div class="col-md-8 col-sm-8 col-xs-12">

                            <input type="text" class="form-control" name="dep_no_urut_antrian_loket_enam" id="dep_no_urut_antrian_loket_enam" value="<?php echo $_POST["dep_no_urut_antrian_loket_enam"];?>">
						</div>
                      </div>
                  </div>
                </div>
			<!-- ==== // panel putih ===== -->
      <!-- ==== KHUSUS BUTTON ===== -->
          <div class="x_content">
	            <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                   <?php echo $view->RenderButton(BTN_SUBMIT,($_x_mode == "Edit")?"btnUpdate":"btnSave","btnSave","Simpan","submit",false);?><!--,"onClick=\"javascript:return CheckDataSave(this.form);\"");?>  -->    
  
                </div>
              </div>
          </div>
	  <!-- ==== // KHUSUS BUTTON ===== -->
              </div>
			  <!-- ==== // kolom kanan ===== -->
			  
			</form>	<!-- ==== Akhir form ===== -->
			<!-- ==== // kolom kanan ===== -->
            </div> <!-- ==== // BARIS ===== -->
          </div>
        </div>
        <!-- /page content -->

					<!-- Modal -->
					<div id="myModal" class="modal fade" role="dialog">
						<div class="modal-dialog modal-lg">

							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header  ">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									 <h4 class="modal-title text-center ">Ganti Urutan</h4>
								</div>
								<div class="modal-body ">
									<form action="" id="myForm">
										<div class="form-group"><label class="col-md-3 col-sm-3 col-xs-12">Nama Video</label>
											<div class="col-md-9 col-sm-9 col-xs-12">
												<input type="text" id="nama_video"  value="" class="form-control">
											</div>		
										</div>
										<br>
										<br>
										<div class="form-group"><label class="col-md-3 col-sm-3 col-xs-12">Urutan</label>
											<div class="col-md-9 col-sm-9 col-xs-12">
												<input type="text" id="urutan_video"  value="" class="form-control">
											</div>		
										</div>
										<br>
										<br>
										
										<input type="hidden" name="id_video" id="id_video">
									</form>
								</div>
								<div class="modal-footer ">
										<button type="button" class="btn btn-default" data-dismiss="modal" onclick="" >Close</button>
										<button type="submit" class="btn btn-success" data-dismiss="modal" onclick="proses_urut()">Submit</button>
								</div>
							</div>

						</div>
					</div>
					<!-- End Modal -->

        <!-- footer content -->
          <?php require_once($LAY."footer.php") ?>
				<script type="text/javascript" src="plugin/jquery.ui.widget.js"></script>
        <script type="text/javascript" src="plugin/jquery.fileupload.js"></script>
        <script type="text/javascript" src="plugin/jquery.iframe-transport.js"></script>
        <script type="text/javascript" src="plugin/jquery.fancy-fileupload.js"></script>
        <!-- /footer content -->
				<script>

						/* Limit file upload */
						$('#thefiles').FancyFileUpload({
								url : 'uploader.php',
								params : {
									action : 'fileuploader'
								},
								maxfilesize : 160000000,   //byte  150mb
								uploadcompleted : function(e, data) {
									// console.log(e,data);
									$('#notif').append('upload tersimpan');
									setTimeout(() => {
											location.reload();
										}, 2000);
	
									}
							});

							function hapusVideo(id,e){
								var x = confirm("Are you sure you want to delete?");
								if (x)
								  $.post('delete_video.php',{id :id},function(data) {
										location . reload();
										});

										
									
									else
										return false;

							}

							// function get_foto(id_reg, id_resume){
							// 	var path = '<?=$uploadPath?>';
							// 	$.getJSON('get_foto.php?id_reg='+id_reg+'&id_resume='+id_resume, function(nilai) {
							// 	// alert(nilai);
							// 	$("#gallery").html(``);
							// 	$.each(nilai, function(index, val) {
							// 		$("#gallery").append(
							// 			`	Nama : ${val.video_antrian_nama};`);
							// 	});
							// });

							// }
							// function hapus_foto(id){
							// 	var id_reg =$('#id_reg').val();
							// 	var id_resume =$('#id_resume').val();
							// 	var result = confirm("Want to delete?");
							// 	if (result) {
							// 			//Logic to delete the item
							// 			$.getJSON('hapus_foto.php?id_foto='+id, function(nilai) {
							// 						if(nilai.success == true){
							// 							get_foto(id_reg,id_resume);
							// 						};
							// 				});
							// 	}

							// }

						$(function(){
							$('.ff_fileupload_hidden').css('display','none');
						})
					</script>
      </div>
    </div>




<?php require_once($LAY."js.php") ?>

  </body>
</html>
