<?php
  //LIBRARY 
  require_once("../penghubung.inc.php");
  require_once($LIB."login.php");
  require_once($LIB."encrypt.php");
  require_once($LIB."datamodel.php");
  require_once($LIB."tampilan.php");
  require_once($LIB."currency.php");
  require_once($LIB."dateLib.php");

  //INISIALISAI AWAL LIBRARY
  $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
  $dtaccess = new DataAccess();
  $enc = new textEncrypt();
  $auth = new CAuth();
  $err_code = 0;
  $userName = $auth->GetUserName();
  $userId = $auth->GetUserId();
  $depId = "9999999";
  $tgl = date('Y-m-d');
  $waktu = date('H:i:s');

  $sql = "select * from global.global_departemen where dep_id =".QuoteValue(DPE_CHAR,$depId);
  $rs = $dtaccess->Execute($sql);
  $konfigurasi = $dtaccess->Fetch($rs); 

  $bg = $ROOT."/gambar/img_cfg/".$konfigurasi["dep_logo"];

  // BPJS 05:00 - 12:00 => B
  // UMUM 05:00 - 12:00 => C
  // ASURANSI 05:00 - 12:00 => A
  // BPJS 12:01 - 00:00 => E
  // UMUM 05:00 - 12:00 => F
  // ASURANSI 05:00 - 12:00 => D
  // ONLINE => G

  // BPJS => id_poli = 1
  // UMUM => id_poli = 2
  // ASURANSI => id_poli = 3
  // ONLINE => id_poli = 4

	// print_r($konfigurasi); die();

  if ($_POST["btnLoketSatu"]) { // BPJS
    if ($waktu >= $konfigurasi['dep_waktu_awal_antrian_pagi'] && $waktu <= $konfigurasi['dep_waktu_akhir_antrian_pagi']) $suara = 'B';
    else if ($waktu > $konfigurasi['dep_waktu_awal_antrian_sore'] && $waktu <= $konfigurasi['dep_waktu_akhir_antrian_sore']) $suara = 'E'; 

    $sql = "SELECT MAX(reg_antri_nomer) AS nomore FROM klinik.klinik_reg_antrian_reguler WHERE reg_antri_tanggal = ".QuoteValue(DPE_DATE, $tgl)." AND reg_antri_suara = ".QuoteValue(DPE_CHAR, $suara)." AND id_poli = '1'";
    $rs = $dtaccess->Execute($sql);
    $noAntrian = $dtaccess->Fetch($rs);

    $noantri =  ($noAntrian["nomore"]+1);

    $dbTable = "klinik.klinik_reg_antrian_reguler";

    $dbField[0] = "reg_antri_id";   // PK
    $dbField[1] = "reg_antri_nomer";
    $dbField[2] = "id_cust_usr";
    $dbField[3] = "id_dep";
    $dbField[4] = "id_poli";
    $dbField[5] = "reg_antri_suara";
    $dbField[6] = "reg_antri_tanggal";
    $dbField[7] = "id_loket";

    $byrId = str_shuffle(date('YmdHis'));
    $dbValue[0] = QuoteValue(DPE_NUMERIC,$byrId);
    $dbValue[1] = QuoteValue(DPE_NUMERIC,$noantri);
    $dbValue[2] = QuoteValue(DPE_CHAR,$idPasien["cust_usr_id"]);
    $dbValue[3] = QuoteValue(DPE_CHAR,$depId);
    $dbValue[4] = QuoteValue(DPE_CHAR,'1');
    $dbValue[5] = QuoteValue(DPE_CHAR,$suara);
    $dbValue[6] = QuoteValue(DPE_DATE,$tgl);
    $dbValue[7] = QuoteValue(DPE_CHAR,"");

    $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);

    $dtmodel->Insert() or die("insert  error");

    unset($dbField);
    unset($dtmodel);
    unset($dbValue);
    unset($dbKey);

    //INSERT KE REKAP ANTRIAN BPJS BARU 
    $dbTable = "klinik.klinik_rekap_antrian";

    $dbField[0] = "rekap_antrian_id";   // PK
    $dbField[1] = "id_jenis_pasien";
    $dbField[2] = "rekap_antrian_jenis_pasien";
    $dbField[3] = "rekap_antrian_tanggal";
    $dbField[4] = "rekap_antrian_waktu";

    $antrianId = $dtaccess->GetTransID();
    $dbValue[0] = QuoteValue(DPE_CHAR,$antrianId);
    $dbValue[1] = QuoteValue(DPE_NUMERIC,5);
    $dbValue[2] = QuoteValue(DPE_CHAR,'B');
    $dbValue[3] = QuoteValue(DPE_DATE,$tgl);
    $dbValue[4] = QuoteValue(DPE_DATE,$waktu);

    $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);

    $dtmodel->Insert() or die("insert  error");

    unset($dbField);
    unset($dtmodel);
    unset($dbValue);
    unset($dbKey);

    $cetak_antrian='y';
  }

  if ($_POST["btnLoketDua"]) { // UMUM
    if ($waktu >= $konfigurasi['dep_waktu_awal_antrian_pagi'] && $waktu <= $konfigurasi['dep_waktu_akhir_antrian_pagi']) $suara = 'C';
    else if ($waktu > $konfigurasi['dep_waktu_awal_antrian_sore'] && $waktu <= $konfigurasi['dep_waktu_akhir_antrian_sore']) $suara = 'F'; 

    $sql = "SELECT MAX(reg_antri_nomer) AS nomore FROM klinik.klinik_reg_antrian_reguler WHERE reg_antri_tanggal = ".QuoteValue(DPE_DATE, $tgl)." AND reg_antri_suara = ".QuoteValue(DPE_CHAR, $suara)." AND id_poli = '2'";
    $rs = $dtaccess->Execute($sql);
    $noAntrian = $dtaccess->Fetch($rs);

    $noantri =  ($noAntrian["nomore"]+1);

    $dbTable = "klinik.klinik_reg_antrian_reguler";

    $dbField[0] = "reg_antri_id";   // PK
    $dbField[1] = "reg_antri_nomer";
    $dbField[2] = "id_cust_usr";
    $dbField[3] = "id_dep";
    $dbField[4] = "id_poli";
    $dbField[5] = "reg_antri_suara";
    $dbField[6] = "reg_antri_tanggal";
    $dbField[7] = "id_loket";

    
    // $byrId = date('YmdHis');
    $byrId = str_shuffle(date('YmdHis'));
    $dbValue[0] = QuoteValue(DPE_NUMERIC,$byrId);
    $dbValue[1] = QuoteValue(DPE_NUMERIC,$noantri);
    $dbValue[2] = QuoteValue(DPE_CHAR,$idPasien["cust_usr_id"]);
    $dbValue[3] = QuoteValue(DPE_CHAR,$depId);
    $dbValue[4] = QuoteValue(DPE_CHAR,'2');
    $dbValue[5] = QuoteValue(DPE_CHAR,$suara);
    $dbValue[6] = QuoteValue(DPE_DATE,$tgl);
    $dbValue[7] = QuoteValue(DPE_CHAR,"");

    $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);

    $dtmodel->Insert() or die("insert  error");

    unset($dbField);
    unset($dtmodel);
    unset($dbValue);
    unset($dbKey);

    //INSERT KE REKAP ANTRIAN BPJS BARU 
    $dbTable = "klinik.klinik_rekap_antrian";

    $dbField[0] = "rekap_antrian_id";   // PK
    $dbField[1] = "id_jenis_pasien";
    $dbField[2] = "rekap_antrian_jenis_pasien";
    $dbField[3] = "rekap_antrian_tanggal";
    $dbField[4] = "rekap_antrian_waktu";

    $antrianId = $dtaccess->GetTransID();
    $dbValue[0] = QuoteValue(DPE_CHAR,$antrianId);
    $dbValue[1] = QuoteValue(DPE_NUMERIC,2);
    $dbValue[2] = QuoteValue(DPE_CHAR,'B');
    $dbValue[3] = QuoteValue(DPE_DATE,$tgl);
    $dbValue[4] = QuoteValue(DPE_DATE,$waktu);

    $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);

    $dtmodel->Insert() or die("insert  error");

    unset($dbField);
    unset($dtmodel);
    unset($dbValue);
    unset($dbKey);

    $cetak_antrian='y';
  }

  if ($_POST["btnLoketTiga"]) { // ASURANSI
    if ($waktu >= $konfigurasi['dep_waktu_awal_antrian_pagi'] && $waktu <= $konfigurasi['dep_waktu_akhir_antrian_pagi']) $suara = 'A';
    else if ($waktu > $konfigurasi['dep_waktu_awal_antrian_sore'] && $waktu <= $konfigurasi['dep_waktu_akhir_antrian_sore']) $suara = 'D'; 

    $sql = "SELECT MAX(reg_antri_nomer) AS nomore FROM klinik.klinik_reg_antrian_reguler WHERE reg_antri_tanggal = ".QuoteValue(DPE_DATE, $tgl)." AND reg_antri_suara = ".QuoteValue(DPE_CHAR, $suara)." AND id_poli = '3'";
    $rs = $dtaccess->Execute($sql);
    $noAntrian = $dtaccess->Fetch($rs);

    $noantri =  ($noAntrian["nomore"]+1);

    $dbTable = "klinik.klinik_reg_antrian_reguler";

    $dbField[0] = "reg_antri_id";   // PK
    $dbField[1] = "reg_antri_nomer";
    $dbField[2] = "id_cust_usr";
    $dbField[3] = "id_dep";
    $dbField[4] = "id_poli";
    $dbField[5] = "reg_antri_suara";
    $dbField[6] = "reg_antri_tanggal";
    $dbField[7] = "id_loket";

    
    // $byrId = date('YmdHis');
    $byrId = str_shuffle(date('YmdHis'));
    $dbValue[0] = QuoteValue(DPE_NUMERIC,$byrId);
    $dbValue[1] = QuoteValue(DPE_NUMERIC,$noantri);
    $dbValue[2] = QuoteValue(DPE_CHAR,$idPasien["cust_usr_id"]);
    $dbValue[3] = QuoteValue(DPE_CHAR,$depId);
    $dbValue[4] = QuoteValue(DPE_CHAR,'3');
    $dbValue[5] = QuoteValue(DPE_CHAR,$suara);
    $dbValue[6] = QuoteValue(DPE_DATE,$tgl);
    $dbValue[7] = QuoteValue(DPE_CHAR,"");

    $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);

    $dtmodel->Insert() or die("insert  error");

    unset($dbField);
    unset($dtmodel);
    unset($dbValue);
    unset($dbKey);

    //INSERT KE REKAP ANTRIAN BPJS BARU 
    $dbTable = "klinik.klinik_rekap_antrian";

    $dbField[0] = "rekap_antrian_id";   // PK
    $dbField[1] = "id_jenis_pasien";
    $dbField[2] = "rekap_antrian_jenis_pasien";
    $dbField[3] = "rekap_antrian_tanggal";
    $dbField[4] = "rekap_antrian_waktu";

    $antrianId = $dtaccess->GetTransID();
    $dbValue[0] = QuoteValue(DPE_CHAR,$antrianId);
    $dbValue[1] = QuoteValue(DPE_NUMERIC,7);
    $dbValue[2] = QuoteValue(DPE_CHAR,'B');
    $dbValue[3] = QuoteValue(DPE_DATE,$tgl);
    $dbValue[4] = QuoteValue(DPE_DATE,$waktu);

    $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);

    $dtmodel->Insert() or die("insert  error");

    unset($dbField);
    unset($dtmodel);
    unset($dbValue);
    unset($dbKey);

    $cetak_antrian='y';
  }

  if ($_POST["btnLoketEmpat"]) { // ONLINE
    $suara = 'G'; 

    $sql = "SELECT MAX(reg_antri_nomer) AS nomore FROM klinik.klinik_reg_antrian_reguler WHERE reg_antri_tanggal = ".QuoteValue(DPE_DATE, $tgl)." AND reg_antri_suara = ".QuoteValue(DPE_CHAR, $suara)." AND id_poli = '4'";
    $rs = $dtaccess->Execute($sql);
    $noAntrian = $dtaccess->Fetch($rs);

    $noantri =  ($noAntrian["nomore"]+1);

    $dbTable = "klinik.klinik_reg_antrian_reguler";

    $dbField[0] = "reg_antri_id";   // PK
    $dbField[1] = "reg_antri_nomer";
    $dbField[2] = "id_cust_usr";
    $dbField[3] = "id_dep";
    $dbField[4] = "id_poli";
    $dbField[5] = "reg_antri_suara";
    $dbField[6] = "reg_antri_tanggal";
    $dbField[7] = "id_loket";

    // $byrId = date('YmdHis');
     $byrId = str_shuffle(date('YmdHis'));
    $dbValue[0] = QuoteValue(DPE_NUMERIC,$byrId);
    $dbValue[1] = QuoteValue(DPE_NUMERIC,$noantri);
    $dbValue[2] = QuoteValue(DPE_CHAR,$idPasien["cust_usr_id"]);
    $dbValue[3] = QuoteValue(DPE_CHAR,$depId);
    $dbValue[4] = QuoteValue(DPE_CHAR,'4');
    $dbValue[5] = QuoteValue(DPE_CHAR,$suara);
    $dbValue[6] = QuoteValue(DPE_DATE,$tgl);
    $dbValue[7] = QuoteValue(DPE_CHAR,"");

    $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);

    $dtmodel->Insert() or die("insert  error");

    unset($dbField);
    unset($dtmodel);
    unset($dbValue);
    unset($dbKey);

    //INSERT KE REKAP ANTRIAN BPJS BARU 
    $dbTable = "klinik.klinik_rekap_antrian";

    $dbField[0] = "rekap_antrian_id";   // PK
    $dbField[1] = "id_jenis_pasien";
    $dbField[2] = "rekap_antrian_jenis_pasien";
    $dbField[3] = "rekap_antrian_tanggal";
    $dbField[4] = "rekap_antrian_waktu";

    $antrianId = $dtaccess->GetTransID();
    $dbValue[0] = QuoteValue(DPE_CHAR,$antrianId);
    $dbValue[1] = QuoteValue(DPE_NUMERIC,2);
    $dbValue[2] = QuoteValue(DPE_CHAR,'O');
    $dbValue[3] = QuoteValue(DPE_DATE,$tgl);
    $dbValue[4] = QuoteValue(DPE_DATE,$waktu);

    $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);

    $dtmodel->Insert() or die("insert  error");

    unset($dbField);
    unset($dtmodel);
    unset($dbValue);
    unset($dbKey);

    $cetak_antrian='y';
  }
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script src="<?php echo $ROOT;?>lib/script/antri/jquery.min.js"></script>
    <script language="javascript">        
      var _wnd_stat;

      function BukaStatWindow(url,judul) {
        if(!_wnd_stat) {
    			_wnd_stat = window.open(url,judul,'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=no,height=200,left=100,top=100');
      	} else {
      		if (_wnd_stat.closed) {
      			wnd_stat = window.open(url,judul,'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=no,height=200,left=100,top=100');
      		} else {
      			_wnd_stat.focus();
      		}
      	}
        return false;
      }

      <?php if($cetak_antrian=="y"){ ?>
      		// BukaStatWindow('cetakantrian.php?id=<?php echo $byrId;?>','No Antrian');
					$.getJSON('cetakantrian1.php?id=<?php echo $byrId;?>');
      <?php } ?> 

      <?php if($cetak_sms=="y"){ ?>
      		// BukaStatWindow('cetak.php?id=<?php echo $noantri;?>&poli=<?php echo $polii;?>&noantri=<?php echo $noantri;?>','No Antrian');
      <?php } ?>
    </script>

    <style type="text/css"> 
      * { 
        font-weight: bold;
      }
      
      body { 
        margin:0;
        padding:0;
        background: url('../lcd/bg.jpg');
        background-size: 100%;
        -moz-background-size: 100%;
      }

      #header {
        margin:0;
        width:100%;
        height:120px;
        background: #f34b72;
				box-shadow :0px 10px 10px #086c39;
      }

      #tombol {
        width: 48%;
        float: left;
        padding: 10px;
      }

      .left {
        width:270px;
        height:80px;
        background:url(<?php echo $lokasi."/".$konfigurasi["dep_logo_kiri_antrian"];?>)no-repeat;
        background-size: 230px 60px;
        float:left;position: absolute;
        left: 0;
        top: 10;
      }

      h1 {
        text-transform: uppercase;
        text-decoration: none;
        line-height: 40px;
        margin-right: 60px;
        margin-top: 0;
        color: #fff;
        font-size: 35px;
        font-weight: bold;
        text-align: center;
        border: none;
      }

      marquee {
        font-size: 50px;
        font-weight: bold;
        text-transform: uppercase;
        position: absolute;
        bottom: 0;
        width: 100%;
        left: 0;
				 color:#19b666;
				text-shadow: 2px 3px 4px rgba(31, 31, 31, 0.7);
      }

      .nomor {
        max-width: 100%;
        height: 250px;
        padding:2px;
        margin-bottom: 13px;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        -moz-border-radius: 10px;
        background: #fafafa;
        box-shadow:
      }

      h3 {
        color:#fff;
        margin:0;
        max-width: 100%;
        padding: 5px 10px;
        background: #f34b72;
        text-align: center;
        font-size: 40px;
        border-radius: 10px 10px 0 0;
        -moz-border-radius: 10px 10px 0 0;
        text-transform: uppercase;
      }

      label {
        position: fixed;
        padding-left: 45%;
      }

      .grid-container {
        display: grid;
        grid-column-gap: 70px;
        grid-template-columns: auto auto ;
        padding: 10px;
      	padding-top:70px !important;
      }
			h4,h2{
				color:#fff;
			}
			.bayangan-keren{
				font-size: 40px;
				height: 65%;
				width:80%;
				box-shadow: 0px 10px 10px #086c39;
				box-shadow: 0px 10px 10px #086c39;
				margin-top: 10px;
			}
    </style>
  </head>
  <body>
    <form name="frmView" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
      <div id="header">
        <table border="0" width="100%" height="100px">
          <tr>
            <td  width="20%" rowspan="2" align="center"><img src="<?=$bg?>" height="80px" width="80px" style="border-radius: 150px;"></td>
            <td width="60%"><h1><?php echo $konfigurasi["dep_header_kanan_antrian"];?></h1></td>
            <td width="20%"><h2><?=date("d-m-Y")?></h2></td>
          </tr>
					<tr>
            <td width="60%" align="center"><h4><?php echo $konfigurasi["dep_kop_surat_1"];?></h4></td>
            <td width="20%" ><h2><?=date("H:i")?></h2></td>
					
					</tr>
        </table>
      </div>
      <div class="grid-container">
        <div align="left">
          <div id="loaddiv-1" align="center" class="nomor"><h3><?php echo $konfigurasi["dep_nama_antrian_loket_satu"];?></h3>
            <input type="submit" name="btnLoketSatu" id="btnLoketSatu" class="bayangan-keren" value="<?php echo $konfigurasi["dep_nama_antrian_loket_satu"];?> KLIK DISINI" class="tombol"/>
          </div>

          <div id="loaddiv-2" align="center" class="nomor" style="padding-top:30px"><h3><?php echo $konfigurasi["dep_nama_antrian_loket_dua"];?></h3>
            <input type="submit" name="btnLoketDua" id="btnLoketDua"  class="bayangan-keren" value="<?php echo $konfigurasi["dep_nama_antrian_loket_dua"];?> KLIK DISINI" class="tombol"/>
          </div> 
        </div>
        <div align="left">
          <div id="loaddiv-3" align="center" class="nomor"><h3><?php echo $konfigurasi["dep_nama_antrian_loket_tiga"];?></h3>
            <input type="submit" name="btnLoketTiga" id="btnLoketTiga" class="bayangan-keren" value="<?php echo $konfigurasi["dep_nama_antrian_loket_tiga"];?> KLIK DISINI" class="tombol"/>
          </div>

         <!-- <div id="loaddiv-4" align="center" class="nomor" style="padding-top:30px"><h3><?php echo $konfigurasi["dep_nama_antrian_loket_empat"];?></h3>
            <input type="submit" name="btnLoketEmpat" id="btnLoketEmpat"  class="bayangan-keren" value="<?php echo $konfigurasi["dep_nama_antrian_loket_empat"];?> KLIK DISINI" class="tombol"/>\
          </div>-->
        </div> 
      </div>

      <marquee><?php echo $konfigurasi["dep_footer_antrian"];?></marquee>
    </form>
  </body>
</html>