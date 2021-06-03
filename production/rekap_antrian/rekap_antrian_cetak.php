<?php
     require_once("../penghubung.inc.php");
     require_once($LIB."/login.php");
     require_once($LIB."/encrypt.php");
     require_once($LIB."/datamodel.php");
     require_once($LIB."/dateLib.php");
     require_once($LIB."/currency.php");
     require_once($LIB."/expAJAX.php");
     require_once($LIB."/tampilan.php");
     
     $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
     $dtaccess = new DataAccess();
     $enc = new textEncrypt();     
     $auth = new CAuth();
     $table = new InoTable("table","100%","left");
     $userId = $auth->GetUserId();
     $userData = $auth->GetUserData();
     $depNama = $auth->GetDepNama();
	   $depId = $auth->GetDepId();
     $thisPage = "report_setoran_loket.php";
     $printPage = "report_setoran_loket_cetak.php?";
    
   //  if (!$_POST["klinik"]) $_POST["klinik"]=$depId;
       //$_POST["klinik"] = $_GET["klinik"]; 
       
     if($_GET["klinik"]) { $_POST["klinik"]=$_GET["klinik"]; }
      else if(!$_POST["klinik"]) { $_POST["klinik"]=$depId; }
      
 
 	   // KONFIGURASI
	   $sql = "select * from global.global_departemen where dep_id =".QuoteValue(DPE_CHAR,$_POST["klinik"]);
     $rs = $dtaccess->Execute($sql);
     $konfigurasi = $dtaccess->Fetch($rs);
     $_POST["dep_id"] = $konfigurasi["dep_id"];
     $_POST["dep_bayar_reg"] = $konfigurasi["dep_bayar_reg"];
          
       $skr = date("d-m-Y");
     $time = date("H:i:s");
     
     if(!$_GET['tgl_awal']){
     $_GET['tgl_awal']  = $skr;
     }
     if(!$_GET['tgl_akhir']){
     $_GET['tgl_akhir']  = $skr;
     }
     
    $shift= $_GET["shift"];
    if(!$_GET["cust_usr_jenis"])  $_GET["cust_usr_jenis"]="0";

     $sql_where[] = "1=1";
     $sql_where2[] = "1=1";

     
     if($_POST["klinik"] && $_POST["klinik"]!="--") $sql_where[] = "a.id_dep = ".QuoteValue(DPE_CHAR,$_POST["klinik"]);
     if($_GET["tgl_awal"]) $sql_where[] = "a.reg_tanggal >= ".QuoteValue(DPE_DATE,date_db($_GET["tgl_awal"]));
     if($_GET["tgl_akhir"]) $sql_where[] = "a.reg_tanggal <= ".QuoteValue(DPE_DATE,date_db($_GET["tgl_akhir"]));


     $sql = "select * from global.global_agama "  ;
     $rs = $dtaccess->Execute($sql);
     $dataAgama = $dtaccess->FetchAll($rs);

     

     
    $sql_where[] = " reg_batal is null";
    
       // Nyari data yg di setup
     $sql = "select jenis_id,jenis_nama from global.global_jenis_pasien where jenis_flag = 'y' order by jenis_id asc";
     $dataPasien = $dtaccess->FetchAll($sql,DB_SCHEMA_GLOBAL);
     
     $tableHeader = "Report Pembayaran";

	if($_POST["btnExcel"]){
          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment; filename=report_pembayaran.xls');
     }
     
       if($_POST["btnCetak"]){

      $_x_mode = "cetak" ;
         
   }
     
     //ambil jenis pasien
     $sql = "select * from global.global_jenis_pasien where jenis_id=".QuoteValue(DPE_NUMERIC,$_GET["cust_usr_jenis"]);
     $rs = $dtaccess->Execute($sql);
     $jenisPasien = $dtaccess->Fetch($rs);
     
          //Data Klinik
          $sql = "select * from global.global_departemen where dep_id like '".$_POST["klinik"]."%' order by dep_id";
          $rs = $dtaccess->Execute($sql);
          $dataKlinik = $dtaccess->FetchAll($rs);
          
          //echo $sql;
          $sql = "select dep_nama from global.global_departemen where dep_id = '".$_GET["klinik"]."'";
          $rs = $dtaccess->Execute($sql);
          $namaKlinik = $dtaccess->Fetch($rs);
          $klinikHeader = "Klinik : ".$namaKlinik["dep_nama"];
          
        


     $sql = "select * from global.global_departemen where dep_id =".QuoteValue(DPE_CHAR,$_POST["klinik"]);
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

<?php //echo $view->RenderBody("inventori_prn.css",true, "Cetak Rekap Agama"); ?>


<script language="javascript" type="text/javascript">

window.print();

</script>

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
     font-size:        12px;
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

<table width="100%" border="1" cellpadding="1" cellspacing="0" style="border-collapse:collapse">
  <tr>
    <td align="center"><img src="<?php echo $fotoName ;?>" height="75"> </td>
    <td align="center" bgcolor="#CCCCCC" id="judul"> 
     <span class="judul2"> <strong><?php echo $konfigurasi["dep_nama"]?></strong><br></span>
		<span class="judul3">
		<?php echo $konfigurasi["dep_kop_surat_1"]?></span><br>
    <span class="judul4">       
	  <?php echo $konfigurasi["dep_kop_surat_2"]?></span></td>  
  </tr>
</table>
<br>

 <table border="0" colspan="7" cellpadding="2" cellspacing="0" style="align:center" width="100%">     
    <tr>
      <td width="70%" style="text-align:center;font-size:20px;font-family:sans-serif;font-weight:bold;" class="tablecontent">Rekap Antrian Pasien</td>   
    </tr>
   
    <tr>
      <td width="30%" style="text-align:center;font-size:12px;font-family:sans-serif;font-weight:bold;" class="tablecontent">Periode : <?php echo $_GET["tgl_awal"];?></td>
    </tr>
  </table>
<BR>
     <table width="100%" border="1" cellpadding="1" cellspacing="1">
          <tr>
               <td class="tablesmallheader"  width="20%" align="center">Tanggal</td>
               <td class="tablesmallheader"  width="10%" align="center">BPJS Lama</td>	
               <td class="tablesmallheader"  width="10%" align="center">BPJS Lama</td>
               <td class="tablesmallheader"  width="10%" align="center">Online Umum</td>
               <td class="tablesmallheader"  width="10%" align="center">Online BPJS</td>
               <td class="tablesmallheader"  width="10%" align="center">Umum</td>
               <td class="tablesmallheader"  width="10%" align="center">Poli TB Dots</td>		
          </tr>
          <?
                         $sql_where_jml1 = "select count(rekap_antrian_id) as total from klinik.klinik_rekap_antrian 
               where 
               id_jenis_pasien = ".QuoteValue(DPE_NUMERIC,5)." AND
               rekap_antrian_jenis_pasien = ".QuoteValue(DPE_CHAR,'B')." AND
               rekap_antrian_tanggal = ".QuoteValue(DPE_DATE,date_db($_GET['tgl_awal']));
               $dataAntrianPasien1 = $dtaccess->Fetch($sql_where_jml1);  
               
               $sql_where_jml2 = "select count(rekap_antrian_id) as total from klinik.klinik_rekap_antrian 
               where 
               id_jenis_pasien = ".QuoteValue(DPE_NUMERIC,5)." AND 
               rekap_antrian_jenis_pasien = ".QuoteValue(DPE_CHAR,'L')."  AND
               rekap_antrian_tanggal = ".QuoteValue(DPE_DATE,date_db($_GET['tgl_awal']));
               $dataAntrianPasien2 = $dtaccess->Fetch($sql_where_jml2);
               
               $sql_where_jml3 = "select count(rekap_antrian_id) as total from klinik.klinik_rekap_antrian 
               where 
               id_jenis_pasien = ".QuoteValue(DPE_NUMERIC,2)." AND 
               rekap_antrian_jenis_pasien = ".QuoteValue(DPE_CHAR,'o')." AND 
               rekap_antrian_tanggal = ".QuoteValue(DPE_DATE,date_db($_GET['tgl_awal']));
               $dataAntrianPasien3 = $dtaccess->Fetch($sql_where_jml3);
               
               $sql_where_jml4 = "select count(rekap_antrian_id) as total from klinik.klinik_rekap_antrian 
               where 
               id_jenis_pasien = ".QuoteValue(DPE_NUMERIC,5)." AND 
               rekap_antrian_jenis_pasien = ".QuoteValue(DPE_CHAR,'o')." AND 
               rekap_antrian_tanggal = ".QuoteValue(DPE_DATE,date_db($_GET['tgl_awal']));
               $dataAntrianPasien4 = $dtaccess->Fetch($sql_where_jml4);
               
               $sql_where_jml5 = "select count(rekap_antrian_id) as total from klinik.klinik_rekap_antrian 
               where 
               id_jenis_pasien = ".QuoteValue(DPE_NUMERIC,2)." AND 
               rekap_antrian_jenis_pasien = ".QuoteValue(DPE_CHAR,'L')." AND 
               rekap_antrian_tanggal = ".QuoteValue(DPE_DATE,date_db($_GET['tgl_awal']));
               $dataAntrianPasien5 = $dtaccess->Fetch($sql_where_jml5);
               
               $sql_where_jml6 = "select count(rekap_antrian_id) as total from klinik.klinik_rekap_antrian 
               where 
               id_jenis_pasien = ".QuoteValue(DPE_NUMERIC,2)." AND 
               rekap_antrian_jenis_pasien = ".QuoteValue(DPE_CHAR,'t')." AND 
               rekap_antrian_tanggal = ".QuoteValue(DPE_DATE,date_db($_GET['tgl_awal']));
               $dataAntrianPasien6 = $dtaccess->Fetch($sql_where_jml6);
    
             
          
          ?>
            <? //if ($dataPasienJml["total"]>0) { ?> <!-- -->
            <tr>
                <td class="tablecontent"  align="center">&nbsp;&nbsp;<?php echo $_GET['tgl_awal'];?></td>
                <td class="tablecontent" align="right"><?php echo $dataAntrianPasien1["total"];?></td>	     
              	<td class="tablecontent" align="right"><?php echo $dataAntrianPasien2["total"];?></td>
                <td class="tablecontent" align="right"><?php echo $dataAntrianPasien3["total"];?></td>
                <td class="tablecontent" align="right"><?php echo $dataAntrianPasien4["total"];?></td>
                <td class="tablecontent" align="right"><?php echo $dataAntrianPasien5["total"];?></td>
                <td class="tablecontent" align="right"><?php echo $dataAntrianPasien6["total"];?></td>     	     
            </tr> 
           
        <!--
         <tr>
               <td class="tablecontent"  align="center">TOTAL</td>
               <td class="tablecontent" align="center"><?php echo $totalIRJ;?></td>	     
               <td class="tablecontent" align="center"><?php echo $totalIGD;?></td>	     
               <td class="tablecontent" align="center"><?php echo $totalIRNA;?></td>	        
          </tr>  -->
          
     </table>
<?php //echo $view->RenderBodyEnd(); ?>
