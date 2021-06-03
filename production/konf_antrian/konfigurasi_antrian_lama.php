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
     $depId = & $_POST["dep_id"]; 
         //      echo "masuk"; die();
		  $dbTable = "global.global_departemen";
               
               $dbField[0] = "dep_id";   // PK
               $dbField[1] = "dep_header_kanan_antrian"; 
 		         	 $dbField[2] = "dep_footer_antrian"; 
 		         	 $dbField[3] = "dep_logo_kanan_antrian";
               $dbField[4] = "dep_logo_kiri_antrian"; 
               $dbField[5] = "dep_no_urut_antrian_reguler"; 
	         	   $dbField[6] = "dep_no_urut_jkn_antrian_reguler";
               $dbField[7] = "dep_no_urut_antrian_ekse";                                                                                                    
               $dbField[8] = "dep_no_urut_jkn_antrian_ekse";
               $dbField[9] = "dep_no_urut_antrian_rehab";                                                                                                    
               $dbField[10] = "dep_no_urut_jkn_antrian_rehab";
               $dbField[11] = "dep_no_urut_antrian_rehab_ekse";                                                                                                    
               $dbField[12] = "dep_no_urut_jkn_antrian_rehab_ekse";
               $dbField[13] = "dep_no_urut_antrian_spesialis";                                                                                                    
               $dbField[14] = "dep_no_urut_jkn_antrian_spesialis";
               $dbField[15] = "dep_antrian_2_tipe";
               $dbField[16] = "dep_antrian_3_tipe";
               $dbField[17] = "dep_antrian_1_tipe";
			       
               $dbValue[0] = QuoteValue(DPE_CHAR,$depId);                                                           
               $dbValue[1] = QuoteValue(DPE_CHAR,$_POST["dep_header_kanan_antrian"]);  
 			         $dbValue[2] = QuoteValue(DPE_CHAR,$_POST["dep_footer_antrian"]); 
 			         $dbValue[3] = QuoteValue(DPE_CHAR,$_POST["dep_logo_kanan_antrian"]);
               $dbValue[4] = QuoteValue(DPE_CHAR,$_POST["dep_logo_kiri_antrian"]);
               $dbValue[5] = QuoteValue(DPE_NUMERIC,StripCurrency($_POST["dep_no_urut_antrian_reguler"]));  
			         $dbValue[6] = QuoteValue(DPE_NUMERIC,StripCurrency($_POST["dep_no_urut_jkn_antrian_reguler"])); 
			         $dbValue[7] = QuoteValue(DPE_NUMERIC,StripCurrency($_POST["dep_no_urut_antrian_ekse"]));
               $dbValue[8] = QuoteValue(DPE_NUMERIC,StripCurrency($_POST["dep_no_urut_jkn_antrian_ekse"]));
               $dbValue[9] = QuoteValue(DPE_NUMERIC,StripCurrency($_POST["dep_no_urut_antrian_rehab"]));
               $dbValue[10] = QuoteValue(DPE_NUMERIC,StripCurrency($_POST["dep_no_urut_jkn_antrian_rehab"]));
               $dbValue[11] = QuoteValue(DPE_NUMERIC,StripCurrency($_POST["dep_no_urut_antrian_rehab_ekse"]));
               $dbValue[12] = QuoteValue(DPE_NUMERIC,StripCurrency($_POST["dep_no_urut_jkn_antrian_rehab_ekse"]));
               $dbValue[13] = QuoteValue(DPE_NUMERIC,StripCurrency($_POST["dep_no_urut_antrian_spesialis"]));
               $dbValue[14] = QuoteValue(DPE_NUMERIC,StripCurrency($_POST["dep_no_urut_jkn_antrian_spesialis"]));  
               $dbValue[15] = QuoteValue(DPE_CHAR,($_POST["dep_antrian_2_tipe"]));  
               $dbValue[16] = QuoteValue(DPE_CHAR,($_POST["dep_antrian_3_tipe"]));  
               $dbValue[17] = QuoteValue(DPE_CHAR,($_POST["dep_antrian_1_tipe"]));  
               

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

       for($i=0,$n=count($_POST["video_antrian_id"]);$i<$n;$i++){
      $sql = "update global.global_video_antrian set
              urutan = '".$_POST["ubah".$i]."'
              where id_dep  =".QuoteValue(DPE_CHAR,$depId)." 
              and video_antrian_id ='".$_POST["video_antrian_id"][$i]."'";
       $rs = $dtaccess->Execute($sql);             
     
     }
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
     
	$lokasi = $ROOT."gambar/img_cfg";
	$lokasiVideo = $ROOT."lcd/";
  
	$sql = "select * from global.global_departemen where dep_id = ".QuoteValue(DPE_CHAR,$depId);
	$rs_edit = $dtaccess->Execute($sql);
	$row_edit = $dtaccess->Fetch($rs_edit);
  //echo $sql;
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
	
?>
<br /><br />

<!DOCTYPE html>
<html lang="en">
  <?php require_once($LAY."header.php"); ?>
<!--  
<link rel="stylesheet" type="text/css" href="<?php echo $ROOT;?>lib/script/jquery/fancybox/jquery.fancybox-1.3.4.css" />
<script src="<?php echo $ROOT;?>lib/script/jquery/fancybox/jquery.easing-1.3.pack.js"></script>
<script src="<?php echo $ROOT;?>lib/script/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"></script> -->
<!--script type="text/javascript">
$(document).ready(function() {
    $("a[rel=sepur]").fancybox({
'width' : '60%',
'height' : '110%',
'autoScale' : false,
'transitionIn' : 'none',
'transitionOut' : 'none',
'type' : 'iframe'      
});
}); 
</script-->

<script type="text/javascript">

	
	/*function ajaxFileUpload(fileupload,hidval,img)
	{                     
  	var lokasi = Array();
    
		lokasi['img_cfg'] = '<?php echo $lokasi;?>';
    lokasi['video_cfg'] = '<?php echo $lokasiVideo;?>';   
                
		$("#loading")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});
		$.ajaxFileUpload
		(
			{
				url:fileupload,
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'json',
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
						
                                   document.getElementById(hidval).value= data.file;
                                   document.getElementById(img).src=lokasi[img]+'/'+data.file;
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
      
      {
				url:fileupload,
				secureuri:false,
				fileElementId:'fileToUpload2',
				dataType: 'json',
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
						
                                   document.getElementById(hidval).value= data.file;
                                   document.getElementById(img).src=lokasi[img]+'/'+data.file;
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
		
		return false;
	}*/
  
function ajaxFileUpload()
	{
		$("#loading")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});
		$.ajaxFileUpload
		(
			{
				url:'konfigurasi_pic.php',
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'json',
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
              document.img_cfg.src='<?php echo $lokasi."/";?>'+data.file;
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
		
		return false;
	}

/*
function ajaxFileUpload2()
	{
		$("#loading2")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});
		$.ajaxFileUpload
		(
			{
				url:'konfigurasi_pic2.php',
				secureuri:false,
				fileElementId:'fileToUpload2',
				dataType: 'json',
				success: function (data2, status2)
				{
					if(typeof(data2.error) != 'undefined')
					{
						if(data2.error != '')
						{
							alert(data2.error);
						}else                               
						{
							alert(data2.msg);                                    
              document.getElementById('dep_logo_kiri_antrian').value= data2.file;
              document.img_cfg2.src='<?php echo $lokasi."/";?>'+data2.file;
						}
					}
				},
				error: function (data2, status2, e)
				{
					alert(e);
				}
			}
		)
		
		return false;
	}
  
  function ajaxFileUpload3()
	{
		$("#loading3")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});
		$.ajaxFileUpload
		(
			{
				url:'konfigurasi_pic3.php',
				secureuri:false,
				fileElementId:'fileToUpload3',
				dataType: 'json',
				success: function (data3, status3)
				{
					if(typeof(data3.error) != 'undefined')
					{
						if(data3.error != '')
						{
							alert(data3.error);
						}else                               
						{
							alert(data3.msg);                                    
              document.getElementById('dep_video_antrian').value= data3.file;
              document.img_cfg3.src='<?php echo $lokasiVideo."/";?>'+data3.file;
						}
					}
				},
				error: function (data3, status3, e)
				{
					alert(e);
				}
			}
		)
		
		return false;
	}       */
  
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



<body>

<div id="body">
<div id="scroller">
<form name="frmEdit" method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>">
<table width="100%"> 
<tr>
<td>
 
<?php if($simpan) { ?>
<font color="red"><strong>Konfigurasi telah disimpan, klik tombol KELUAR pada MENU UTAMA agar perubahan Konfigurasi terjadi.</strong></font>
<?php } ?>


 
<fieldset>
  <legend><strong>Konfigurasi Antrian Display</strong></legend>
     <table width="100%"  border="0">
          <tr>
               <td align="left" class="tablesmallheader" width="15%"><strong>&nbsp;Header Antrian</strong>&nbsp;</td>
               <td align="center" class="tablesmallheader" width="1%">:</td>
               <td width="49%" colspan="2">
               <textarea name="dep_header_kanan_antrian" id="dep_header_kanan_antrian" maxlength="255" size="50"><?php echo $_POST["dep_header_kanan_antrian"];?></textarea>
               </td>
               <td align="center" width="20%">
                      </td>
          </tr>                                            
          <tr>
               <td align="left" class="tablesmallheader" width="15%"><strong>&nbsp;Footer Bawah Antrian</strong>&nbsp;</td>
               <td align="center" class="tablesmallheader" width="1%">:</td>
               <td width="49%" colspan="2">
               <textarea name="dep_footer_antrian" id="dep_footer_antrian" maxlength="255" size="50"><?php echo $_POST["dep_footer_antrian"];?></textarea>
               </td>
          </tr>  
                
              <tr>      
               <td align="left" class="tablesmallheader" width="15%"><strong>&nbsp;Logo Header Kiri(230x60)</strong>&nbsp;</td>
               <td align="center" class="tablesmallheader" width="1%">:</td>
               <td align="left" width="20%">
               <table width="100%" border="1" cellpadding="2" cellspacing="2">                          
                    <tr>  
                       <td>                      
                            <img hspace="2" height="80" name="img_cfg" id="img_cfg" src="<?php echo $fotoGambar;?>" valign="middle" border="1">
                            <input type="hidden" name="dep_logo_kiri_antrian" id="dep_logo_kiri_antrian" value="<?php echo $_POST["dep_logo_kiri_antrian"];?>">
                            <input align="left" id="fileToUpload" type="file" size="25" name="fileToUpload" class="inputField">
                            <button class="submit" id="buttonUpload" onClick="return ajaxFileUpload();">Upload Logo</button>
                            <span id="loading" style="display:none;"><img width="26" height="16"  id="imgloading" src="<?php echo $ROOT;?>gambar/loading.gif"></span>

                      </td>
                    </tr>                     
                   </table>
              </td>
          </tr>  
          <!--
          <tr>
               <td align="left" class="tablesmallheader" width="15%"><strong>&nbsp;Video Antrian (.mp4)</strong>&nbsp;</td>
               <td align="center" class="tablesmallheader" width="1%">:</td>
               <td align="left" width="20%">
                   <table width="100%" border="0" cellpadding="2" cellspacing="2">
                    <tr>
                       <td>
                            <img hspace="2" height="80" name="img_cfg3" id="img_cfg3" src="<?php echo $videoName;?>" valign="middle" border="1">
                            <input type="hidden" name="dep_video_antrian" id="dep_video_antrian" value="<?php echo $_POST["dep_video_antrian"];?>">
                            <input align="left" id="fileToUpload3" type="file" size="25" name="fileToUpload3" class="inputField">
                            <button class="submit" id="buttonUpload" onClick="return ajaxFileUpload3();">Upload Video</button>
                            <span id="loading3" style="display:none;"><img width="26" height="16"  id="imgloading3" src="<?php echo $ROOT;?>gambar/loading.gif"></span>

                      </td>
                    </tr>                     
                   </table>
              </td>
          </tr>   -->   
          
     </table>
     </fieldset>
     
<fieldset>
<legend><strong>Konfigurasi No. Urut Antrian</strong></legend>
<table width="100%" border="1" > 
     <tr>
      <td class="tablecontent" width="20%" align="right">&nbsp;&nbsp;No. Urut Pasien Umum Antrian Reguler&nbsp;</td>        
      <td class="tablecontent" width="70%" align="left"><?php echo $view->RenderTextBox("dep_no_urut_antrian_reguler","dep_no_urut_antrian_reguler","10","100",currency_format($_POST["dep_no_urut_antrian_reguler"]),"inputField", null,true);?></td>
     </tr>
     <tr>
      <td class="tablecontent" width="20%" align="right">&nbsp;&nbsp;No. Urut Pasien JKN Antrian Reguler&nbsp;</td>        
      <td class="tablecontent" width="70%" align="left"><?php echo $view->RenderTextBox("dep_no_urut_jkn_antrian_reguler","dep_no_urut_jkn_antrian_reguler","10","100",currency_format($_POST["dep_no_urut_jkn_antrian_reguler"]),"inputField", null,true);?></td>
     </tr>
     <tr>
      <td class="tablecontent" width="20%" align="right">&nbsp;&nbsp;Konfigurasi tipe pasien loket 1&nbsp;</td>        
      <td class="tablecontent" width="70%" align="left">
      <select name="dep_antrian_1_tipe" id="dep_antrian_1_tipe"> 
          <option value="J" <?php if($_POST["dep_antrian_1_tipe"]=="J"){echo "selected";}?>>Antrian JKN</option>
          <option value="U" <?php if($_POST["dep_antrian_1_tipe"]=="U"){echo "selected";}?>>Antrian Umum</option>
      </select>
      </td>
     </tr>
     <tr>
      <td class="tablecontent" width="20%" align="right">&nbsp;&nbsp;Konfigurasi tipe pasien loket 2&nbsp;</td>        
      <td class="tablecontent" width="70%" align="left">
      <select name="dep_antrian_2_tipe" id="dep_antrian_2_tipe"> 
          <option value="J" <?php if($_POST["dep_antrian_2_tipe"]=="J"){echo "selected";}?>>Antrian JKN</option>
          <option value="U" <?php if($_POST["dep_antrian_2_tipe"]=="U"){echo "selected";}?>>Antrian Umum</option>
      </select>
      </td>
     </tr>
     <tr>
      <td class="tablecontent" width="20%" align="right">&nbsp;&nbsp;Konfigurasi tipe pasien loket 3&nbsp;</td>        
      <td class="tablecontent" width="70%" align="left">
      <select name="dep_antrian_3_tipe" id="dep_antrian_3_tipe"> 
          <option value="J" <?php if($_POST["dep_antrian_3_tipe"]=="J"){echo "selected";}?>>Antrian JKN</option>
          <option value="U" <?php if($_POST["dep_antrian_3_tipe"]=="U"){echo "selected";}?>>Antrian Umum</option>
      </select>
      </td>
     </tr>
<!--
     <tr>
      <td class="tablecontent" width="20%" align="right">&nbsp;&nbsp;No. Urut Pasien Umum Antrian Eksekutif&nbsp;</td>        
      <td class="tablecontent" width="70%" align="left"><?php echo $view->RenderTextBox("dep_no_urut_antrian_ekse","dep_no_urut_antrian_ekse","10","100",currency_format($_POST["dep_no_urut_antrian_ekse"]),"inputField", null,true);?></td>
     </tr>
     <tr>
      <td class="tablecontent" width="20%" align="right">&nbsp;&nbsp;No. Urut Pasien JKN Antrian Eksekutif&nbsp;</td>        
      <td class="tablecontent" width="70%" align="left"><?php echo $view->RenderTextBox("dep_no_urut_jkn_antrian_ekse","dep_no_urut_jkn_antrian_ekse","10","100",currency_format($_POST["dep_no_urut_jkn_antrian_ekse"]),"inputField", null,true);?></td>
     </tr>
     <tr>
      <td class="tablecontent" width="20%" align="right">&nbsp;&nbsp;No. Urut Pasien Umum Antrian Rehab Medik&nbsp;</td>        
      <td class="tablecontent" width="70%" align="left"><?php echo $view->RenderTextBox("dep_no_urut_antrian_rehab","dep_no_urut_antrian_rehab","10","100",currency_format($_POST["dep_no_urut_antrian_rehab"]),"inputField", null,true);?></td>
     </tr>
     <tr>
      <td class="tablecontent" width="20%" align="right">&nbsp;&nbsp;No. Urut Pasien JKN Antrian Rehab Medik&nbsp;</td>        
      <td class="tablecontent" width="70%" align="left"><?php echo $view->RenderTextBox("dep_no_urut_jkn_antrian_rehab","dep_no_urut_jkn_antrian_rehab","10","100",currency_format($_POST["dep_no_urut_jkn_antrian_rehab"]),"inputField", null,true);?></td>
     </tr>
     <tr>
      <td class="tablecontent" width="20%" align="right">&nbsp;&nbsp;No. Urut Pasien Umum Antrian Rehab Medik Eksekutif&nbsp;</td>        
      <td class="tablecontent" width="70%" align="left"><?php echo $view->RenderTextBox("dep_no_urut_antrian_rehab_ekse","dep_no_urut_antrian_rehab_ekse","10","100",currency_format($_POST["dep_no_urut_antrian_rehab_ekse"]),"inputField", null,true);?></td>
     </tr>
     <tr>
      <td class="tablecontent" width="20%" align="right">&nbsp;&nbsp;No. Urut Pasien JKN Antrian Rehab Medik Eksekutif&nbsp;</td>        
      <td class="tablecontent" width="70%" align="left"><?php echo $view->RenderTextBox("dep_no_urut_jkn_antrian_rehab_ekse","dep_no_urut_jkn_antrian_rehab_ekse","10","100",currency_format($_POST["dep_no_urut_jkn_antrian_rehab_ekse"]),"inputField", null,true);?></td>
     </tr>
     <tr>
      <td class="tablecontent" width="20%" align="right">&nbsp;&nbsp;No. Urut Pasien Umum Antrian Spesialis&nbsp;</td>        
      <td class="tablecontent" width="70%" align="left"><?php echo $view->RenderTextBox("dep_no_urut_antrian_spesialis","dep_no_urut_antrian_spesialis","10","100",currency_format($_POST["dep_no_urut_antrian_spesialis"]),"inputField", null,true);?></td>
     </tr>
     <tr>
      <td class="tablecontent" width="20%" align="right">&nbsp;&nbsp;No. Urut Pasien Umum Antrian Spesialis&nbsp;</td>        
      <td class="tablecontent" width="70%" align="left"><?php echo $view->RenderTextBox("dep_no_urut_jkn_antrian_spesialis","dep_no_urut_jkn_antrian_spesialis","10","100",currency_format($_POST["dep_no_urut_jkn_antrian_spesialis"]),"inputField", null,true);?></td>
     </tr>  -->
</table>
</fieldset>
<fieldset>
<legend><strong>Konfigurasi No. Urut Video Antrian</strong></legend>
<table width="100%" border="1" >

          <tr>
               <td align="left" class="tablesmallheader" width="1%">No</td>
               <td align="center" class="tablesmallheader" width="40%">Nama Video</td>
               <td align="center" class="tablesmallheader" width="20%">Urutan</td>
               <td align="center" class="tablesmallheader" width="5%">Hapus</td>
          </tr>
        <?php for($i=0,$n=count($dataVideoAnt);$i<$n;$i++) {  ?>
          <tr>
              <td align="left" ><?php echo $i+1;?></td>
              <td align="left" ><?php echo $dataVideoAnt[$i]["video_antrian_nama"];?></td>
              <input type="hidden" name="video_antrian_id[<?php echo $i;?>]" id="video_antrian_id<?php echo $i;?>" value="<?php echo $dataVideoAnt[$i]["video_antrian_id"] ;?>" /></td>
              <td align="center">
             <?php echo $view->RenderTextBox("ubah$i","ubah$i","10","100",$dataVideoAnt[$i]["urutan"],"inputField", null,true);?>            
              </td>
                <td align="center" >                
                <a href="hapus.php?id=<?php echo $dataVideoAnt[$i]["video_antrian_id"];?>"><img hspace="2" src="<?php echo $ROOT; ?>gambar/hapus.png" class="button" alt="Edit" title="Edit" border="0"></a>
              </td>

          </tr>   
    
<?php }  ?>
     
     </table>
</fieldset>

     <table width="100%" border="0" >
          <tr>
               <td colspan="2" align="center">
                    <?php echo $view->RenderButton(BTN_SUBMIT,($_x_mode == "Edit")?"btnUpdate":"btnSave","btnSave","Simpan","submit",false);?><!--,"onClick=\"javascript:return CheckDataSave(this.form);\"");?>  -->    
               </td>
          </tr>
     </table>
     </td>
     </tr>  
</table>
<?php echo $view->RenderHidden("dep_id","dep_id",$depId);?> 
</form>
<?php require_once($LAY."footer.php") ?>
        <!-- /footer content -->
      </div>
    </div>

<?php require_once($LAY."js.php") ?>

  </body>
</html>
