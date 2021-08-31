<?php 
    	require_once("penghubung.inc.php");
    	require_once($ROOT."lib/bit.php");
    	require_once($ROOT."lib/login.php");
    	require_once($ROOT."lib/encrypt.php");
    	require_once($ROOT."lib/datamodel.php");
    	require_once($ROOT."lib/barcode.php");
	   require_once($ROOT."lib/expAJAX.php");
    	require_once($ROOT."lib/tampilan.php");
    	
    	$dtaccess = new DataAccess();
    	$enc = new textEncrypt();                                 
    	$auth = new CAuth();
    	$view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
    	$depId = $auth->GetDepId();
		$depNama = $auth->GetDepNama();
	    $plx = new expAJAX("");      
	// -- authentifikasi ---- //	
 /*	if(!$auth->IsAllowed("kas_cet_foto",PRIV_CREATE)){
          die("access_denied");
          exit(1);
     } else if($auth->IsAllowed("kas_cet_foto",PRIV_CREATE)===1){
          echo"<script>window.parent.document.location.href='".$ROOT."login.php?msg=Login First'</script>";
          exit(1);
     } */

	//$barcode = new InoBarcode();

	if($_GET["id_reg"]) { 
	
	$_POST["id_reg"] = $_GET["id_reg"];
	
	$sql = "select a.cust_usr_jenis_kelamin, a.cust_usr_tanggal_lahir, a.cust_usr_kode,
	a.cust_usr_foto,a.cust_usr_nama,a.cust_usr_alamat as alamat1,a.cust_usr_suami,poli_antrian_urut,
	((current_date - a.cust_usr_tanggal_lahir)/365) as umur, c.id_poli, d.poli_nama, 
	c.reg_status_cetak_kartu,a.cust_usr_nama_kk
  		from   global.global_customer_user a  
  		left join  klinik.klinik_registrasi c on c.id_cust_usr = a.cust_usr_id
  		left join   global.global_auth_poli d on d.poli_id = c.id_poli
  		where c.reg_id = ".QuoteValue(DPE_CHAR,$_POST["id_reg"]);
  	
   $rs = $dtaccess->Execute($sql,DB_SCHEMA_GLOBAL);
   $dataPasien = $dtaccess->Fetch($rs);
   $poliantriurut = $dataPasien["poli_antrian_urut"];
	//var_dump($dataPasien);
	//echo $sql;
	//echo "data".$dataPasien["cust_usr_nama"];
	if($dataPasien["cust_usr_foto"]){
  	$fotoPasien = $ROOT."/gambar/foto_pasien/".$dataPasien["cust_usr_foto"];
  	} else {
  		$fotoPasien = $ROOT."/gambar/foto_pasien/default.jpg"; 
		}
		
	//update status 
    $sql = "update klinik.klinik_registrasi set reg_status_cetak_kartu = 'y' where id_cust_usr = ".QuoteValue(DPE_CHAR,$_POST["cust_usr_id"])." and id_dep=".QuoteValue(DPE_CHAR,$depId);
    $dtaccess->Execute($sql);
    
	}
	
	// KONFIHURASI
	$sql = "select * from global.global_departemen where dep_id =".QuoteValue(DPE_CHAR,$depId);
    $rs = $dtaccess->Execute($sql);
    $konfigurasi = $dtaccess->Fetch($rs);
     
    if ($konfigurasi["dep_height"]!=0) $panjang=$konfigurasi["dep_height"] ;
    if ($konfigurasi["dep_width"]!=0) $lebar=$konfigurasi["dep_width"] ;
    $fotoName = $ROOT."/gambar/img_cfg/".$konfigurasi["dep_logo"];	
    $bg = $ROOT."/gambar/img_cfg/".$konfigurasi["dep_logo"];
     
    $sql = "select * from global.global_konfigurasi_kartu where id_dep =".QuoteValue(DPE_CHAR,$depId);
    $rs = $dtaccess->Execute($sql);
    $konfKartu = $dtaccess->Fetch($rs);
    $fotoKiri = $ROOT."kasir/images/konfigurasi_kartu/".$konfKartu["konf_kartu_pic_kiri"];
    $fotoKanan = $ROOT."kasir/images/konfigurasi_kartu/".$konfKartu["konf_kartu_pic_kanan"];
    $fotoBelakangKiri = $ROOT."kasir/images/konfigurasi_kartu/".$konfKartu["konf_kartu_pic_belakang_kiri"];
    $fotoBelakangKanan = $ROOT."kasir/images/konfigurasi_kartu/".$konfKartu["konf_kartu_pic_belakang_kanan"];
  
	// --- bagian barcode --- //
	define (__TRACE_ENABLED__,false);
	define (__DEBUG_ENABLED__,false);  
									   
	/*require($ROOT."lib/barcode/barcode.php");		   
	require($ROOT."lib/barcode/i25object.php");
	require($ROOT."lib/barcode/c39object.php");
	require($ROOT."lib/barcode/c128aobject.php");
	require($ROOT."lib/barcode/c128bobject.php");
	require($ROOT."lib/barcode/c128cobject.php");*/ 
							  
	// Default value //
	if (!isset($output))  $output   = "png";
	if (isset($_GET["id"])) $barcode  = $dataPasien["cust_usr_kode"];
	if (!isset($type))    $type     = "C39";
	if (!isset($width))   $width    = "90";
	if (!isset($height))  $height   = "60";
	if (!isset($xres))    $xres     = "1";
	if (!isset($font))    $font     = "1";

	$border = "off";
	$drawtext = "on";
	$stretchtext = "on";
	//------------------------------------// 
					
	if (isset($barcode) && strlen($barcode)>0) {    
		$style  = BCS_ALIGN_CENTER;					       
		$style |= ($output  == "png" ) ? BCS_IMAGE_PNG  : 0; 
		$style |= ($output  == "jpeg") ? BCS_IMAGE_JPEG : 0; 
		$style |= ($border  == "on"  ) ? BCS_BORDER 	  : 0; 
		$style |= ($drawtext== "on"  ) ? BCS_DRAW_TEXT  : 0; 
		$style |= ($stretchtext== "on" ) ? BCS_STRETCH_TEXT  : 0; 
		$style |= ($negative== "on"  ) ? BCS_REVERSE_COLOR  : 0; 

		$obj = new C39Object(250, 120, $style, $barcode);
		
		if ($obj) {
			if ($obj->DrawObject($xres)) {
				$check_error = 0;
			} else {
				$check_error = 1;
			}
		}
	}
// --- End bagian barcode --- // */

?>
<html>
<head>

<title>Cetak Kartu Pasien</title>

<style type="text/css">
body {
    font-family:Arial, Verdana, Helvetica, sans-serif;
    margin: 0px;
    font-size:50px;
}

#dv_nama {
	position:absolute;
	top:0px;
	left:50px;
	z-index:1;
	font-size: 14px;
	font-weight:bolder;
}


#dv_kode {
	position:absolute;
	top:35px;
	left:50px;
	z-index:1;
	font-size: 14px;
	font-weight:bolder;
}


#dv_alamat {
	position:absolute;
	top:50px;
	left:50px;
	z-index:1;
	font-size: 11px;
}

#dv_barcode {
	position:absolute;
	top:77px;
	left:20px;
	z-index:1;
}

#dv_foto {
	position:absolute;
	top:23px;
	left:230px;
	z-index:1;
}

table{
font-size:12px;
}

</style>

<?php echo $view->InitUpload(); ?>

<script>
$(document).ready( function() {
	window.print(); 
  window.close();
});    
      
</script>

</head>
<body>
	<table border="0">
		<tr>
			<td valign="top">
				<div style="border:0px solid black; width:8.5cm; height:5.3cm;">
					<table style="border: 2px solid black; width:9cm;">   
					  	<tr>
							<td align="center"><?php echo $depNama;?></td>
						</tr>
            <tr>
							<td align="center">N O.    A N T R I A N</td>
						</tr>
					</table>
					<table style="width:9cm; height:4cm; border: 2px solid black;">   
					 <tr>
							<td align="left" style="font-size:14px;">Nama : <?php echo $dataPasien["cust_usr_nama"];?></td>
              <td align="left" style="font-size:14px;">RM : <?php echo $dataPasien["cust_usr_kode"];?></td>
						</tr>
          	<tr>
							<td align="center" style="font-size:150px;" colspan="2"><?php echo $_GET["noantri"];?></td>
						</tr>
						<tr>
							<td align="center" style="font-size:16px;"> <?php  
              if($poliantriurut=="1"){ echo $dataPasien["poli_nama"]." (107) ";}
              elseif($poliantriurut=="2"){echo $dataPasien["poli_nama"]." (102) ";}
              elseif($poliantriurut=="3"){echo $dataPasien["poli_nama"]." (109) ";}
              elseif($poliantriurut=="4"){echo $dataPasien["poli_nama"]." (110) ";}
              elseif($poliantriurut=="5"){echo $dataPasien["poli_nama"]." (109) ";}
              elseif($poliantriurut=="6"){echo $dataPasien["poli_nama"]." (102,103,104) ";}
              elseif($poliantriurut=="7"){echo $dataPasien["poli_nama"]." (104) ";}
              elseif($poliantriurut=="8"){echo $dataPasien["poli_nama"]." (109) ";}
              elseif($poliantriurut=="9"){echo $dataPasien["poli_nama"]." (101) ";}
              elseif($poliantriurut=="10"){echo $dataPasien["poli_nama"]." (101) ";}
              else{echo $dataPasien["poli_nama"]." (107) ";};?></td>
							<td align="center" style="font-size:16px;"><?php echo date("d-m-Y H:i:s"); ?></td>
        		</tr>
          </table>

</div>
</td>
</tr>
</table>
</div>

</body>
</html>
