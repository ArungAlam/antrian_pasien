<?php
//LIBRARY 
require_once("../penghubung.inc.php");
require_once($LIB . "login.php");
require_once($LIB . "encrypt.php");
require_once($LIB . "datamodel.php");
require_once($LIB . "tampilan.php");
require_once($LIB . "currency.php");
require_once($LIB . "dateLib.php");

//INISIALISAI AWAL LIBRARY
$view = new CView($_SERVER['PHP_SELF'], $_SERVER['QUERY_STRING']);
$dtaccess = new DataAccess();
$enc = new textEncrypt();
$auth = new CAuth();
$userData = $auth->GetUserData();
$depNama = $auth->GetDepNama();
// $depId = $auth->GetDepId();
$userName = $auth->GetUserName();
$userId = $auth->GetUserId();
$err_code = 0;
$depId = '9999999';


$date = date('Y-m-d H:i:s');
$tgl = date("Y-m-d");
$cetakPage = "cetakkartu.php";
$tableHeader = "KIOSK PASIEN";

$sql = "select * from global.global_departemen where dep_id =" . QuoteValue(DPE_CHAR, $depId);
$konfigurasi = $dtaccess->Fetch($sql);

$lokasi = $ROOT . "../.." . $konfigurasi['dep_dir'] . "/production/gambar/img_cfg";
$fotoName = $lokasi . "/" . $konfigurasi["dep_logo"];

$sql = "select max(reg_antri_nomer) as nomore from klinik.klinik_reg_antrian_reguler where id_poli='1' and id_dep = " . QuoteValue(DPE_CHAR, $depId);
$noAntrian = $dtaccess->Fetch($sql);
$noantri =  ($noAntrian["nomore"] + 1);

if ($noantri < $konf["dep_no_urut_antrian_loket_satu"]) $noantri = $noantri + $konf["dep_no_urut_antrian_loket_satu"];
?>

<!DOCTYPE html>
<html>

<head>
  <title><?= $tableHeadar ?></title>
</head>
<style type="text/css">
  * {
    font-weight: bold;
  }

  body {
    margin: 0;
    padding: 0;
    background: url('../lcd/bg.jpg');
    background-size: 100%;
    -moz-background-size: 100%;
  }

  h1 {
    text-transform: uppercase;
    text-decoration: none;
    line-height: 80px;
    margin-right: 60px;
    margin-top: 0;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    text-align: center;
    border: none;
  }

  h3 {
    color: #fff;
    margin: 0;
    max-width: 100%;
    padding: 5px 10px;
    background: #108a49;
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

  #header {
    margin: 0;
    width: 100%;
    height: 100px;
    background: #108a49;
  }

  #kiri {
    padding-top: 5%;
    padding-left: 10px;
  }

  .nomor {
    width: 50%;
    height: 250px;
    padding: 2px;
    margin-bottom: 13px;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    -moz-border-radius: 10px;
    background: #fafafa;
  }

  marquee {
    font-size: 50px;
    font-weight: bold;
    text-transform: uppercase;
    position: absolute;
    bottom: 0;
    width: 100%;
    position: absolute;
    bottom: 0;
    left: 0;
    color: #fff;
  }
</style>
<script>

function pindah() {
	window.location.href= '../pasien/antri_tambah.php'
	
}
</script>

<body>
  <form name="frmView" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <div id="header">
      <table border="0" width="100%" height="100px">
        <tr>
          <td width="20%" align="center"><img src="<?= $fotoName; ?>" height="80px" width="80px" style="border-radius: 150px;"></td>
          <td width="60%">
            <h1><?= $konfigurasi['dep_title'] ?></h1>
          </td>
          <td width="20%"></td>
        </tr>
      </table>
    </div>
    <table width="90%" align="center" style="padding-top: 5%;">
      <tr>
        <td align="center" width="50%">
          <div>
            <div align="center" class="nomor">
              <h3>PASIEN BARU</h3>
              <a href="cetakantrian.php" title="Pasien Lama" onclick="pindah()" target="_BLANK"><button type="button" name="btnLoketSatu" id="btnLoketSatu" style="font-size:2em; width: 100%;height:75px; margin-top:50px;">PASIEN BARU KLIK DISINI</button></a>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td align="center" width="50%">
          <div>
            <div align="center" class="nomor">
              <h3>PASIEN LAMA</h3>
              <a href="pasien_lama.php" title=""><button type="button" style="font-size:2em; width: 100%;height:75px; margin-top:50px;">PASIEN LAMA KLIK DISINI</button></a>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td align="center">
          <div>
            <div align="center" class="nomor">
              <h3>REGISTRASI ONLINE</h3>
              <a href="registrasi_online.php" title=""><button type="button" style="font-size:2em; width: 100%;height:75px; margin-top:50px; ">REGISTRASI ONLINE KLIK DISINI</button></a>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td align="center">
          <div>
            <div align="center" class="nomor">
              <h3>PASIEN KONTROL</h3>
              <a href="pasien_kontrol.php" title=""><button type="button" style="font-size:2em; width: 100%;height:75px; margin-top:50px;">PASIEN KONTROL KLIK DISINI</button></a>
            </div>
          </div>
        </td>
      </tr>
    </table>
    <marquee><?= $konfigurasi['dep_footer_antrian'] ?></marquee>
  </form>
</body>

</html>