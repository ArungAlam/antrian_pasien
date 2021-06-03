<?php
     require_once("penghubung.inc.php");
     require_once($ROOT."lib/bit.php");
     require_once($ROOT."lib/login.php");
     require_once($ROOT."lib/encrypt.php");
     require_once($ROOT."lib/datamodel.php");
     require_once($ROOT."lib/dateLib.php");
     require_once($ROOT."lib/currency.php");
     require_once($ROOT."lib/tampilan.php");
     
     
     $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
	   $dtaccess = new DataAccess();
     $enc = new textEncrypt();
     $auth = new CAuth();
     $err_code = 0;
     $userData = $auth->GetUserData();
     $userId = $auth->GetUserId();
     $userName = $auth->GetUserName();
     $depNama = $auth->GetDepNama();
	   $depId = $auth->GetDepId();

     $_x_mode = "New";
     $thisPage = "kasir_view.php";
     
     
     //array tipe layanan
     $layanan["1"] = "Reguler";
	   $layanan["2"] = "Eksekutif";
     
	if($_GET["id_reg"] || $_GET["jenis"]  || $_GET["ket"] || $_GET["dis"] || $_GET["disper"] || $_GET["pembul"] || $_GET["total"]) {
		$sql = "select cust_usr_nama,cust_usr_kode,cust_usr_tanggal_lahir,cust_usr_no_hp,b.cust_usr_jenis_kelamin,cust_usr_alamat, d.poli_nama,
            ((current_date - cust_usr_tanggal_lahir)/365) as umur,  a.id_poli,a.id_cust_usr ,a.reg_jenis_pasien , 
            a.reg_tipe_layanan,a.reg_no_antrian,a.reg_when_update,a.reg_kode_trans,a.reg_kode_urut,a.reg_umur_hari,a.reg_umur_bulan,a.reg_umur,
            c.usr_name,e.jenis_nama,f.rujukan_nama, g.jkn_nama, h.dep_kota, i.reg_antri_nomer
            from klinik.klinik_registrasi a join global.global_customer_user b on a.id_cust_usr = b.cust_usr_id
            left join global.global_auth_user c on c.usr_id = a.id_dokter 
            left join global.global_auth_poli d on a.id_poli = d.poli_id
            left join global.global_jenis_pasien e on a.reg_jenis_pasien = e.jenis_id
            left join global.global_rujukan f on a.reg_rujukan_id = f.rujukan_id
            left join global.global_jkn g on g.jkn_id = a.reg_tipe_jkn
            left join global.global_departemen h on h.dep_id = a.id_dep
            left join klinik.klinik_poli_antrian i on i.id_reg=a.reg_id
            where a.reg_id = ".QuoteValue(DPE_CHAR,$_GET["id_reg"])." and a.id_dep=".QuoteValue(DPE_CHAR,$depId);
    //echo $sql;      

    $dataPasien= $dtaccess->Fetch($sql);
		$_POST["id_reg"] = $_GET["id_reg"]; 
		$_POST["fol_jenis"] = $_GET["jenis"]; 
		$_POST["id_cust_usr"] = $dataPasien["id_cust_usr"];
		$_POST["cust_usr_kode"] = $dataPasien["cust_usr_kode"];
		$_POST["keterangan"] = $_GET["ket"];
		$_POST["diskon"] = $_GET["dis"];
		$_POST["diskonpersen"] = $_GET["disper"];
		$_POST["pembulatan"] = $_GET["pembul"];
		$_POST["total"] = $_GET["total"];
    $_POST["reg_jenis_pasien"] = $dataPasien["reg_jenis_pasien"];
    
    // nyari petugas yg bayar --
    $sql = "select usr_name from klinik.klinik_folio a
            left join global.global_auth_user b on b.usr_id = a.who_when_update 
            where id_reg =".QuoteValue(DPE_CHAR,$_POST["id_reg"])." and a.id_dep =".QuoteValue(DPE_CHAR,$depId);
    $rs = $dtaccess->Execute($sql);
    $petugas = $dtaccess->Fetch($rs);
    
     //ambil jenis pasien
     $sql = "select * from global.global_jenis_pasien where jenis_flag = 'y' and jenis_id =".QuoteValue(DPE_NUMERIC,$_POST["reg_jenis_pasien"]);
     $rs = $dtaccess->Execute($sql);
     $jenisPasien = $dtaccess->Fetch($rs);

    $sql = "select a.*,b.usr_name as dokter_nama from klinik.klinik_folio a 
            left join global.global_auth_user b
            on a.id_dokter = b.usr_id where
            fol_jenis like '%T%' and id_reg = ".QuoteValue(DPE_CHAR,$_POST["id_reg"])." 
            and a.id_dep=".QuoteValue(DPE_CHAR,$depId);
		$dataFolio = $dtaccess->FetchAll($sql);
		
/*		for($i=0,$n=count($dataFolio);$i<$n;$i++) { 
          if($dataFolio[$i]["fol_jumlah"]){
           if($dataFolio[$i]["fol_jumlah"]!='0.00') $total = $dataFolio[$i]["fol_jumlah"]*$dataFolio[$i]["fol_total_harga"]; else $total = $dataFolio[$i]["fol_total_harga"];
          }else{
            $total = $dataFolio[$i]["fol_total_harga"];
          }
          $totalHarga+=$total;
		} */
		
    $totalHarga=$dataFolio[0]["fol_total_harga"];
    
    $sql = "select * from klinik.klinik_pembayaran where pembayaran_jenis = 'T' and id_reg = ".QuoteValue(DPE_CHAR,$_POST["id_reg"])." and id_dep=".QuoteValue(DPE_CHAR,$depId);
		$dataDiskon = $dtaccess->Fetch($sql);

    /*
     $sql_terapi = "select a.terapi_jumlah_item,a.terapi_dosis,a.id_dep,a.terapi_harga_jual,a.terapi_total_harga,
                    b.item_nama from  klinik.klinik_perawatan_terapi a
                    left join logistik.logistik_item b on b.item_id = a.id_item
			              where a.id_reg = ".QuoteValue(DPE_CHAR,$_POST["id_reg"])." and id_dep=".QuoteValue(DPE_CHAR,$depId)." and terapi_flag = 'K'"; 
		 $rs_edit = $dtaccess->Execute($sql_terapi,DB_SCHEMA_KLINIK);
     $dataPerawatan = $dtaccess->FetchAll($rs_edit);
     */

   }
     
     
     		// KONFIGURASI
	   $sql = "select * from global.global_departemen where dep_id =".QuoteValue(DPE_CHAR,$depId);
     $rs = $dtaccess->Execute($sql);
     $konfigurasi = $dtaccess->Fetch($rs);
     $lokasi = $ROOT."/gambar/img_cfg";  
     
     if ($konfigurasi["dep_height"]!=0) $panjang=$konfigurasi["dep_height"] ;
     if ($konfigurasi["dep_width"]!=0) $lebar=$konfigurasi["dep_width"] ;
     
  if($konfigurasi["dep_logo"]!="n") {
  $fotoName = $lokasi."/".$konfigurasi["dep_logo"];
  } elseif($konfigurasi["dep_logo"]=="n") { 
  $fotoName = $lokasi."/default.jpg"; 
  } else { $fotoName = $lokasi."/default.jpg"; }

?>

<html>
<head>

<title>Bukti Registrasi</title>

<style>
@media print {
     #tableprint { display:none; }
}

#splitBorder tr td table{
border-collapse:collapse;
}

#splitBorder tr td table tr td {
border:1px solid black;
}

body {
     font-family:      Verdana, Arial, Helvetica, sans-serif;
     font-size:        10px;
     margin: 5px;
     margin-top:		  0px;
     margin-left:	  0px;
}

.menubody{
     background-image:    url(gambar/background_01.gif);
     background-position: left;
}
.menutop {
     font-family: Arial;
     font-size: 11px;
     color:               #FFFFFF;
     background-color:    #000e98;
     background-image:     url(gambar/bg_topmenu.png);
     background-repeat:	repeat-x;
     font-weight: bold;
     text-transform: uppercase;
     text-align: center;
     height: 25px;
     background-position: left top;
     cursor:pointer;
}

.menubottom {
     background-image:    	 url(gambar/submenu_bg.png);
     background-repeat:   	no-repeat;
}

.menuleft {
     font-family:      		Arial, Helvetica, sans-serif;
     font-size:        		12px;
     color:					#333333;
     background-image:    	 url(gambar/submenu_btn.png);
     background-repeat:   	repeat-y;
     font-weight: 			bolder;
}

.menuleft_bawah {
     font-family:      		Arial, Helvetica, sans-serif;
     font-size:        		8px;
     color:					#333333;
     background-image:    	 url(gambar/submenu_btn_bawah.png);	
     font-weight: 			bold;	
}

.img-button {
     cursor:     pointer;
     border:     0px;
}

.menuleft a:link, a:visited, a:active {
     font-family:      Arial, Helvetica, sans-serif;
     font-size:        12px;
     text-decoration:  none;
     color:            #333333;
}

.menuleft a:hover {
     font-family:      Arial, Helvetica, sans-serif;
     font-size:        12px;
     text-decoration:  none;
     color:            #6600CC;
}

table {
     font-family:    Verdana, Arial, Helvetica, sans-serif;
     font-size:      12px;
	padding:0px;
	border-color:#000000;
	border-collapse : collapse;
	border-style:solid;
	}

#tablesearch{
	display:none;
}

.passDisable{
     color: #0F2F13;
     border: 1px solid #f1b706;
     background-color: #ffff99;
}

.tabaktif {
     font-family: Verdana, Arial, Helvetica, sans-serif;
     font-size: 10px;
     color:               #E60000;
     background-color:    #ffe232;
     background-image:     url(gambar/tbl_subheadertab.png);
     background-repeat:	repeat-x;
     font-weight: bolder;
     height: 18;
     text-transform: capitalize;
}

.tabpasif {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color:               #000000;
	background-color:    #ffe232;
	background-image:     url(gambar/tbl_subheader2.png);
	background-repeat:	repeat-x;
	font-weight: bolder;
	height: 18;
	text-transform: capitalize;
}

.caption {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-style: normal;
}

a:link, a:visited, a:active {
    font-family:      Verdana, Arial, Helvetica, sans-serif;
    font-size:        10px;
    text-decoration:  none;
    color:            #1F457E;

}

a:hover {
    font-family:      Verdana, Arial, Helvetica, sans-serif;
    font-size:        10px;
    text-decoration:  underline;
    color:            #8897AE;
}

.titlecaption {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-style: oblique;
	font-weight: bolder;

}

.tableheader {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color:               #333333;
	font-weight: bold;
	text-transform: uppercase;
}

.tablesmallheader {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;	
	font-weight: bold;
	height: 18px;
	background-position: left top;
}

.tablecontent {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: lighter;	
	height: 18px;
}

.tablecontent-odd {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: lighter;
	height: 18px;
}

.tablecontent-kosong {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: bold;
	color: #FC0508;
	height: 18px;
}

.tablecontent-medium {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight: lighter;
	background-color:    #fff5b3;
	height: 18px;
}

.tablecontent-gede {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 23px;
	font-weight: lighter;
	background-color:    #fff5b3;
	height: 18px;
}

.tablecontent-odd {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: lighter;
	height: 18px;
}

.tablecontent-odd-kosong {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #FC0508;
	font-weight: lighter;
	height: 18px;
}

.tablecontent-odd-medium {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight: lighter;
	height: 18px;
}

.tablecontent-odd-gede {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 23px;
	font-weight: lighter;
	height: 18px;
}

.tablecontent-telat {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FC0508;
	font-weight: lighter;
	background-color:    #fff5b3;
	height: 18px;
}

.tablecontent-odd-telat {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #FC0508;
	font-weight: lighter;
	height: 18px;
}

.inputField
{
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #0F2F13;
	border: 1px solid #1A5321;
	background-color: #EBF4A8;
}


.content {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	background-color:    #E7E6FF;
	height: 18px;
}

.content-odd {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	height: 18px;
}

.subheader {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color:               #000000;
	background-color:    #FFFFFF;
	font-weight: bolder;
	height: 18;
	text-transform: capitalize;
}

.subheader-print {
    font-family:        Verdana, Arial, Helvetica, sans-serif;
    font-size:          10px;
    color:              #000000;
    font-weight:        bolder;
    height:             18;
}

.staycontent {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: lighter;
}

.button, submit, reset {
    display:none;
    visibility:hidden;
}

select, option {
	font-family:	Verdana, Arial, Helvetica, sans-serif;
	font-size:		10px;
	text-indent:	2px;
	margin: 2px;
	left: 0px;
	clip:  rect(auto auto auto auto);
	border-top: 0px;
	border-right: 0px;
	border-bottom: 0px;
	border-left: 0px;
}

input, textarea {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	border: 1px solid #f1b706;
	text-indent:	2px;
	margin: 2px;
	left: 0px;
	width: auto;
	vertical-align: middle;
}

.subtitlecaption {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-style: normal;
	font-weight: 500;
}

.inputcontent {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: lighter;
	background : #E6EDFB url(../none);
	border: none;
	text-align: right;
}

.hlink {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}

.navActive {
	color:  #cc0000;
}

fieldset {
	border: thin solid #2F2F2F;
}

.whiteborder {
	border: none;
	margin: 0px 0px;
	padding: 0px 0px;
	border-collapse : collapse;
}

.adaborder {
	border-left: none;
	border-top: none;
	border-bottom: none;
	border-right: solid #999999 1px;
	margin: 0px 0px;
	padding: 0px 0px;
	border-collapse : separate;
}

.divcontent {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: lighter;
	background-color:    #E7E6FF;
	border-bottom: solid #999999 1px;
	border-right: solid #999999 1px;
}

.curedit {
	text-align: right;
}
 
#div_cetak{ display: block; }

#tblSearching{ display: none; }

#printMessage {
    display: none;
}

#noborder.tablecontent {
border-style: none;
}

#noborder.tablecontent-odd {
border-style: none;
}
.noborder {
border-style: none;
}
 
    body {
	   font-family:      Arial, Verdana, Helvetica, sans-serif;
	   margin: 0px;
	    font-size: 10px;
    }
    
    .tableisi {
	   font-family:      Verdana, Arial, Helvetica, sans-serif;
	   font-size:        10px;
	    border: none #000000 0px; 
	    padding:4px;
	    border-collapse:collapse;
    }
    
    
    .tableisi td {
	    border: solid #000000 1px; 
	    padding:4px;
    }
    
    .tablenota {
	   font-family:      Verdana, Arial, Helvetica, sans-serif;
	   font-size:        10px;
	    border: solid #000000 1px; 
	    padding:4px;
	    border-collapse:collapse;
    }
    
    .tablenota .judul  {
	    border: solid #000000 1px; 
	    padding:4px;
    }
    
    .tablenota .isi {
	    border-right: solid black 1px;
	    padding:4px;
    }
    
    .ttd {
	    height:50px;
    }
    
    .judul {
	    font-size:      14px;
	    font-weight: bolder;
	    border-collapse:collapse;
    }
    
    
    .judul {
	    font-size:      14px;
	    font-weight: bolder;
	    border-collapse:collapse;
    }
    
    
    .judul1 {
	    font-size: 14px;
	    font-weight: bolder;
    }
    .judul2 {
	    font-size: 14px;
	    font-weight: bolder;
    }
    .judul3 {
	    font-size: 18px;
	    font-weight: normal;
    }
    
    .judul4 {
	    font-size: 12px;
	    font-weight: bold;
	    background-color : #CCCCCC;
	    text-align : center;
    }
    .judul5 {
	    font-size: 16px;
	    font-weight: bold;
	    background-color : #d6d6d6;
	    text-align : center;
	    color : #000000;
    } 
    .judul6 {
	    font-size: 12px;
	    font-weight: bold;
	    text-align : center;
	    color : #000000;
    }  
</style>




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

<!--<table border="0" cellpadding="2" cellspacing="0" align="left">
    <tr>
      <td class="tablecontent"><font size="2"><?php// echo $konfigurasi["dep_nama"]?></font></td>
    </tr>
    <tr>
      <td class="tablecontent"><?php// echo $konfigurasi["dep_kop_surat_1"]?></td>
    </tr>
    <tr>
      <td class="tablecontent"><?php// echo $konfigurasi["dep_kop_surat_2"]?></td>
    </tr>
    <tr>
      <td class="tablecontent"><?php// echo $konfigurasi["dep_kop_surat_3"]?></td>
    </tr>
  </table>  
  <br><br>-->
  <table width="100%" border="0" cellpadding="1" cellspacing="0" style="border-collapse:collapse">
    <tr>
	<br><br><br>
      <td align="left">BUKTI REGISTRASI</td>  
    </tr>                                                    
    <tr>
      <td align="left">Klinik <?php echo $dataPasien["poli_nama"];?> (<?php echo $layanan[$dataPasien["reg_tipe_layanan"]];?>)</td>  
    </tr>
    <!--<tr>
      <td align="center">.....................................................................................................................................................................................</td>  
    </tr> -->                                                    
  </table>
  
<table width="100%" border="0" cellpadding="1" cellspacing="0" style="border-collapse:collapse"> 
  
  <tr>
	<td width="7%">No. Registrasi </td>
    <td align="left" width="30%">: <?php echo $dataPasien["reg_kode_trans"];?></td> 
  </tr>
  <tr>
	<td width="7%">Waktu Registrasi </td>
    <td align="left" width="30%">: <?php echo FormatTimestamp($dataPasien["reg_when_update"]);?></td> 
  </tr>
  <tr>
	<td width="7%">No. Antrian </td>
    <td align="left" width="30%">: <?php echo $dataPasien["reg_antri_nomer"];?></td> 
  </tr>
  <tr>
<!-- <td align="left" width="7%">No. RM</td>  
    <td align="center" width="1%">:</td>--> 
	<td width="7%">No. RM </td>
    <td align="left" width="30%">: <?php echo $dataPasien["cust_usr_kode"];?></td>  
<!--    <td align="center" width="5%">&nbsp;</td>
    <td align="left" width="10%">Asal pasien</td>  
    <td align="center" width="1%">:</td> 
	
    <td align="left" width="20%"><?php //echo $dataPasien["rujukan_nama"];?></td>-->   
  </tr>
  <tr>
<!--    <td align="left" width="7%">Nama</td>  
    <td align="center" width="1%">:</td> --> 
	<td width="7%">Nama Pasien </td>
    <td align="left" width="30%">: <?php echo $dataPasien["cust_usr_nama"];?></td>  
<!--    <td align="center" width="5%">&nbsp;</td>
    <td align="left" width="10%">No. Reg</td>  
    <td align="center" width="1%">:</td>  
    <td align="left" width="20%"><?php //echo $dataPasien["reg_kode_trans"];?></td>  -->
  </tr>
  <tr>
<!--    <td align="left" width="7%">Tgl. Lahir</td>  
    <td align="center" width="1%">:</td>  -->
	<td width="7%">Tanggal Lahir </td>
    <td align="left" width="30%">: <?php echo format_date($dataPasien["cust_usr_tanggal_lahir"]);?>
	&nbsp;<?php echo $dataPasien["reg_umur"];?> thn / <?php echo "&nbsp;".$dataPasien["reg_umur_bulan"];?> bln / <?php echo "&nbsp;".$dataPasien["reg_umur_hari"];?> hr</td>  
    
    
  </tr>
    <tr>
<!--    <td align="left" width="7%">Tgl. Lahir</td>  
    <td align="center" width="1%">:</td>  -->
	<td width="7%">Cara Bayar  </td>
    <?php if($dataPasien["reg_jenis_pasien"]=="5"){?>
    <td align="left" width="30%">: <?php echo $dataPasien["jenis_nama"]." - ".$dataPasien["jkn_nama"];?></td>
    <?php } else { ?>
    <td align="left" width="30%">: <?php echo $dataPasien["jenis_nama"];?></td>
    <?php } ?>  
  </tr>
  <tr>
<!--    <td align="left" width="7%">Tgl. Lahir</td>  
    <td align="center" width="1%">:</td>  -->
	<td width="7%">Nama Dokter </td>
    <td align="left" width="30%">: <?php echo $dataPasien["usr_name"];?></td>  
  </tr>
 <!-- <tr>
    <td align="left" width="7%">Umur</td>  
    <td align="center" width="1%">:</td>  
    <td align="left" width="20%">&nbsp;<?php echo $dataPasien["reg_umur"];?> thn / <?php echo "&nbsp;".$dataPasien["reg_umur_bulan"];?> bln / <?php echo "&nbsp;".$dataPasien["reg_umur_hari"];?> hr</td>  
    <td align="center" width="5%">&nbsp;</td>
    <td align="left" width="10%">Kelas</td>  
    <td align="center" width="1%">:</td>  
    <td align="left" width="20%">&nbsp;</td>  
  </tr>-->
  <!--<tr>
    <td align="left"> Alamat</td>  
    <td align="left" ><?php echo $dataPasien["cust_usr_alamat"];?></td>  
    <td align="center" width="5%">&nbsp;</td>
	</tr>
	<tr>
	<td align="left"> Tgl. Reg</td>  
    <td align="left" ><?php echo FormatTimestamp($dataPasien["reg_when_update"]);?></td>
	</tr>
	<tr>
    <td align="left"> Cara Bayar</td>    
    <td align="left" width="20%"><?php echo $dataPasien["jenis_nama"];?></td>  
  </tr>-->

<!--</table>
<br><br>
<table width="100%" border="0" cellpadding="1" cellspacing="0" style="border-collapse:collapse">
  <tr>
      <td colspan="4" align="left">.....................................................................................................................................................................................</td>  
  </tr>                                                    
  <tr>
    <td width="1%" align="center">No.</td>
    <td width="20%" align="center">Uraian</td>
    <td width="5%" align="left">Qty</td>
    <td align="center">Pelaksana</td>
  </tr>
  <tr>
      <td colspan="4" align="left">.....................................................................................................................................................................................</td>  
  </tr>                                                    

    <?php //for($i=0,$n=count($dataFolio);$i<$n;$i++) {?>
     <tr>
         <td align="left"><?php //echo $i+1;?></td>
         <td width="20%" align="left"><?php //echo $dataFolio[$i]["fol_nama"];?></td>
         <td width="5%" align="left"><?php //echo round($dataFolio[$i]["fol_jumlah"]);?></td>
         <td align="left" width="20%"><?php //echo $dataFolio[$i]["dokter_nama"];?></td>
     </tr>
     <?php //$totalPembayaran += $dataFolio[$i]["fol_nominal"]; ?>
    <?php// } ?>

</table>-->
<!--
<div style="position:fixed;top:430px;left:60px;">
<table width="70%" border="0">
  <tr>
  <br><br>
    <td align="center"><?php echo $dataPasien["dep_kota"].", ". date("d-m-Y");?></td>
  </tr>
  <tr>
    <td align="center">Petugas,</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">(<?php echo $userName;?> )</td>
  </tr>
  </table>
</div>  -->
</body>
</html>
