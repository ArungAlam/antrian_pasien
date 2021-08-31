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
$tableHeader = 'Registrasi Online';

$sql = "select * from global.global_departemen where dep_id =" . QuoteValue(DPE_CHAR, $depId);
$konfigurasi = $dtaccess->Fetch($sql);

$lokasi = $ROOT . "../.." . $konfigurasi['dep_dir'] . "/production/gambar/img_cfg";
$fotoName = $lokasi . "/" . $konfigurasi["dep_logo"];

if ($_POST['cetak']) {
  /* UPDATE KLINIK REGISTRASI BUFFER */
  $sql = "update klinik.klinik_registrasi_buffer set reg_buffer_status = 'n' where reg_buffer_id = " . QuoteValue(DPE_CHAR, $_POST['kode_booking']);
  $rs = $dtaccess->Execute($sql);
  /* UPDATE KLINIK REGISTRASI BUFFER */

  require_once('reg_kode_trans.php'); // Menentukan reg_kode_trans

  /* INSERT PEMBAYARAN */
  $dbTable = "klinik.klinik_pembayaran";

  $dbField[0] = "pembayaran_id";   // PK
  $dbField[1] = "pembayaran_create";
  $dbField[2] = "pembayaran_who_create";
  $dbField[3] = "pembayaran_tanggal";
  $dbField[4] = "id_cust_usr";
  $dbField[5] = "pembayaran_total";
  $dbField[6] = "id_dep";
  $dbField[7] = "pembayaran_flag";
  $dbField[8] = "pembayaran_yg_dibayar";
  $dbField[9] = "id_reg";

  $byrId = $dtaccess->GetTransID();
  $regId = $dtaccess->GetTransID();

  $dbValue[0] = QuoteValue(DPE_CHARKEY, $byrId);
  $dbValue[1] = QuoteValue(DPE_DATE, date("Y-m-d H:i:s"));
  $dbValue[2] = QuoteValue(DPE_CHAR, $_POST['nama_pasien']);
  $dbValue[3] = QuoteValue(DPE_DATE, date_db($_POST['tgl_regitrasi']));
  $dbValue[4] = QuoteValue(DPE_CHAR, $_POST['cust_usr_id']);
  $dbValue[5] = QuoteValue(DPE_NUMERIC, 0);
  $dbValue[6] = QuoteValue(DPE_CHAR, $depId);
  $dbValue[7] = QuoteValue(DPE_CHAR, 'n');
  $dbValue[8] = QuoteValue(DPE_NUMERIC, '0.00');
  $dbValue[9] = QuoteValue(DPE_CHAR, $regId);

  $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
  $dtmodel = new DataModel($dbTable, $dbField, $dbValue, $dbKey);

  $dtmodel->Insert() or die("insert  error");

  unset($dbField);
  unset($dtmodel);
  unset($dbValue);
  unset($dbKey);
  /* INSERT PEMBAYARAN */

  /* INSERT REGISTRASI */
  $dbTable = "klinik.klinik_registrasi";

  $dbField[0] = "reg_id";   // PK
  $dbField[1] = "reg_tanggal";
  $dbField[2] = "reg_waktu";
  $dbField[3] = "id_cust_usr";
  $dbField[4] = "reg_status";
  $dbField[5] = "reg_who_update";
  $dbField[6] = "reg_when_update";
  $dbField[7] = "reg_jenis_pasien";
  $dbField[8] = "reg_status_pasien";
  $dbField[9] = "reg_tipe_rawat";
  $dbField[10] = "id_poli";
  $dbField[11] = "id_dep";
  $dbField[12] = "id_dokter";
  $dbField[13] = "id_pembayaran";
  $dbField[14] = "id_poli_asal";
  $dbField[15] = "reg_umur";
  $dbField[16] = "reg_umur_bulan";
  $dbField[17] = "reg_umur_hari";
  $dbField[18] = "reg_kelas";
  $dbField[19] = "reg_tracer_registrasi";
  $dbField[20] = "reg_tracer_barcode";
  $dbField[21] = "reg_tracer_barcode_besar";
  $dbField[22] = "reg_tracer_riwayat";
  $dbField[23] = "reg_tracer";
  $dbField[24] = "reg_kode_trans";
  $dbField[25] = "reg_tanggal_pulang";
  $dbField[26] = "reg_waktu_pulang";
  $dbField[27] = "reg_tipe_jkn";
  $dbField[28] = "reg_utama";
  $dbField[29] = "id_perusahaan";

  $dbValue[0] = QuoteValue(DPE_CHAR, $regId);
  $dbValue[1] = QuoteValue(DPE_DATE, date_db($_POST['tgl_regitrasi']));
  $dbValue[2] = QuoteValue(DPE_DATE, date("H:i:s"));
  $dbValue[3] = QuoteValue(DPE_CHAR, $_POST['cust_usr_id']);
  $dbValue[4] = QuoteValue(DPE_CHAR, 'E0');
  $dbValue[5] = QuoteValue(DPE_CHAR, $_POST['nama_pasien']);
  $dbValue[6] = QuoteValue(DPE_DATE, date("Y-m-d H:i:s"));
  $dbValue[7] = QuoteValue(DPE_NUMERICKEY, $_POST["jenis_pasien"]);
  $dbValue[8] = QuoteValue(DPE_CHAR, 'L');
  $dbValue[9] = QuoteValue(DPE_CHAR, 'J');
  $dbValue[10] = QuoteValue(DPE_CHAR, $_POST["poli_klinik"]);
  $dbValue[11] = QuoteValue(DPE_CHAR, $depId);
  $dbValue[12] = QuoteValue(DPE_CHAR, $_POST["dokter"]);
  $dbValue[13] = QuoteValue(DPE_CHAR, $byrId);
  $dbValue[14] = QuoteValue(DPE_CHAR, $_POST["poli_klinik"]);
  $dbValue[15] = QuoteValue(DPE_NUMERIC, $diff->y);
  $dbValue[16] = QuoteValue(DPE_NUMERIC, $diff->m);
  $dbValue[17] = QuoteValue(DPE_NUMERIC, $diff->d);
  $dbValue[18] = QuoteValue(DPE_CHAR, $konfigurasi["dep_konf_kelas_tarif_irj"]); # ikut conf rs
  $dbValue[19] = QuoteValue(DPE_CHAR, $_POST["cetak_reg"]);
  $dbValue[20] = QuoteValue(DPE_CHAR, $_POST["cetak_barcode_k"]);
  $dbValue[21] = QuoteValue(DPE_CHAR, $_POST["cetak_barcode_b"]);
  $dbValue[22] = QuoteValue(DPE_CHAR, $_POST["cetak_ringkasan"]);
  $dbValue[23] = QuoteValue(DPE_CHAR, $_POST["cetak_tracer"]);
  $dbValue[24] = QuoteValue(DPE_CHAR, $kodeTrans);
  $dbValue[25] = QuoteValue(DPE_DATE, date_db($_POST['tgl_regitrasi']));
  $dbValue[26] = QuoteValue(DPE_DATE, date("H:i:s"));
  $dbValue[27] = QuoteValue(DPE_CHAR, '');
  $dbValue[28] = QuoteValue(DPE_CHAR, $regId);
  $dbValue[29] = QuoteValue(DPE_CHAR, $_POST["perusahaan"]);

  $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
  $dtmodel = new DataModel($dbTable, $dbField, $dbValue, $dbKey);

  $dtmodel->Insert() or die("insert  error");

  unset($dtmodel);
  unset($dbField);
  unset($dbValue);
  unset($dbKey);
  /* INSERT REGISTRASI */

  /* INSERT PERAWATAN */
  $dbTable = " klinik.klinik_perawatan";

  $dbField[0] = "rawat_id";   // PK
  $dbField[1] = "id_reg";
  $dbField[2] = "id_cust_usr";
  $dbField[3] = "rawat_waktu_kontrol";
  $dbField[4] = "rawat_tanggal";
  $dbField[5] = "rawat_flag";
  $dbField[6] = "rawat_flag_komen";
  $dbField[7] = "id_poli";
  $dbField[8] = "id_dep";
  $dbField[9] = "rawat_perawat_who_update";
  $dbField[10] = "rawat_waktu";

  $rawat_id = $dtaccess->GetTransID();

  $dbValue[0] = QuoteValue(DPE_CHAR, $rawat_id);   // PK
  $dbValue[1] = QuoteValue(DPE_CHAR, $regId);
  $dbValue[2] = QuoteValue(DPE_CHAR, $_POST["cust_usr_id"]);
  $dbValue[3] = QuoteValue(DPE_CHAR, date("H:i:s"));
  $dbValue[4] = QuoteValue(DPE_DATE, date_db($_POST['tgl_regitrasi']));
  $dbValue[5] = QuoteValue(DPE_CHAR, 'M');
  $dbValue[6] = QuoteValue(DPE_CHAR, 'RAWAT JALAN');
  $dbValue[7] = QuoteValue(DPE_CHAR, $_POST["poli_klinik"]);
  $dbValue[8] = QuoteValue(DPE_CHAR, $depId);
  $dbValue[9] = QuoteValue(DPE_CHAR, $_POST['nama_pasien']);
  $dbValue[10] = QuoteValue(DPE_DATE, date("Y-m-d H:i:s"));

  $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
  $dtmodel = new DataModel($dbTable, $dbField, $dbValue, $dbKey, DB_SCHEMA_KLINIK);

  $dtmodel->Insert() or die("insert  error");

  unset($dtmodel);
  unset($dbValue);
  unset($dbField);
  unset($dbKey);
  /* INSERT PERAWATAN */

  if ($konfigurasi["dep_konf_reg"] == 'y') require_once("insert_biaya_registrasi.php"); // INSERT BIAYA REGISTRASI
  if ($konfigurasi["dep_konf_kons"] == 'y') require_once("insert_biaya_pemeriksaan.php"); // INSERT TINDAKAN PEMERIKSAAN

  /* INSERT WAKTU TUNGGU */
  $dbTable = "klinik.klinik_waktu_tunggu";

  $dbField[0] = "klinik_waktu_tunggu_id";   // PK
  $dbField[1] = "id_reg";
  $dbField[2] = "id_cust_usr";
  $dbField[3] = "klinik_waktu_tunggu_when_create";
  $dbField[4] = "klinik_waktu_tunggu_who_create";
  $dbField[5] = "klinik_waktu_tunggu_status";
  $dbField[6] = "klinik_waktu_tunggu_status_keterangan";
  $dbField[7] = "id_poli";
  $dbField[8] = "id_waktu_tunggu_status";

  $waktuTungguId = $dtaccess->GetTransID();

  $dbValue[0] = QuoteValue(DPE_CHAR, $waktuTungguId);
  $dbValue[1] = QuoteValue(DPE_CHAR, $regId);
  $dbValue[2] = QuoteValue(DPE_CHAR, $_POST['cust_usr_id']);
  $dbValue[3] = QuoteValue(DPE_DATE, date('Y-m-d H:i:s'));
  $dbValue[4] = QuoteValue(DPE_CHAR, $_POST['nama_pasien']);
  $dbValue[5] = QuoteValue(DPE_CHAR, 'E0');
  $dbValue[6] = QuoteValue(DPE_CHAR, "Pasien di Registrasi");
  $dbValue[7] = QuoteValue(DPE_CHAR, $_POST["poli_klinik"]);
  $dbValue[8] = QuoteValue(DPE_CHAR, 'E0');

  $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
  $dtmodel = new DataModel($dbTable, $dbField, $dbValue, $dbKey);

  $dtmodel->Insert() or die("insert  error");

  unset($dtmodel);
  unset($dbField);
  unset($dbValue);
  unset($dbKey);
  /* INSERT WAKTU TUNGGU */

  /* INSERT INACBG */
  $dbTable = "klinik.klinik_inacbg";

  $dbField[0] = "inacbg_id";   // PK
  $dbField[1] = "inacbg_pasien_nama";
  $dbField[2] = "id_cust_usr";
  $dbField[3] = "id_pembayaran";
  $dbField[4] = "id_reg";
  $dbField[5] = "inacbg_check";
  $dbField[6] = "inacbg_when_update";
  $dbField[7] = "inacbg_tanggal_masuk";
  $dbField[8] = "inacbg_waktu_masuk";

  $inacbg_id = $dtaccess->GetTransID();
  $dbValue[0] = QuoteValue(DPE_CHAR, $inacbg_id);
  $dbValue[1] = QuoteValue(DPE_CHAR, strtoupper($_POST['nama_pasien']));
  $dbValue[2] = QuoteValue(DPE_CHAR, $_POST['cust_usr_id']);
  $dbValue[3] = QuoteValue(DPE_CHAR, $byrId);
  $dbValue[4] = QuoteValue(DPE_CHAR, $regId);
  $dbValue[5] = QuoteValue(DPE_CHAR, 'k');
  $dbValue[6] = QuoteValue(DPE_DATE, date('Y-m-d H:i:s'));
  $dbValue[7] = QuoteValue(DPE_DATE, date_db($_POST['tgl_regitrasi']));
  $dbValue[8] = QuoteValue(DPE_DATE, date('H:i:s'));

  $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
  $dtmodel = new DataModel($dbTable, $dbField, $dbValue, $dbKey);
  $dtmodel->Insert() or die("insert  error");

  unset($dtmodel);
  unset($dbField);
  unset($dbValue);
  unset($dbKey);
  /* INSERT INACBG */

  $sql = "SELECT * FROM global.global_customer_user where cust_usr_id = " . QuoteValue(DPE_CHAR, $_POST['cust_usr_id']);
  $data_pasien_api = $dtaccess->Fetch($sql);

  $sql = "SELECT poli_nama FROM global.global_auth_poli WHERE poli_id = " . QuoteValue(DPE_CHAR, $_POST["poli_klinik"]);
  $data_poli_api = $dtaccess->Fetch($sql);

  $sql = "SELECT usr_name, kode_nama FROM global.global_auth_user WHERE usr_id = " . QuoteValue(DPE_CHAR, $_POST["dokter"]);
  $data_dokter_api = $dtaccess->Fetch($sql);

  $sql = "select * from  global.global_jenis_pasien a";
  $sql .= " where jenis_id=" . QuoteValue(DPE_CHAR, $_POST["jenis_pasien"]);
  //echo $sql;
  $rs = $dtaccess->Execute($sql);
  $jenisPasien = $dtaccess->Fetch($rs);

  $instalasi = 'Rawat Jalan';
  $data_pasien = array(
    'Nama' => $data_pasien_api['cust_usr_nama'],
    'Foto' => $data_pasien_api['cust_usr_foto'],
    'TanggalLahir' => $data_pasien_api['cust_usr_tanggal_lahir'],
    'Umur' => $data_pasien_api["cust_usr_umur"],
    'Alamat' => $data_pasien_api['cust_usr_alamat'],
    'NoHP' => $data_pasien_api['cust_usr_no_hp'],
    'Gender' => $data_pasien_api['cust_usr_jenis_kelamin'],
    'NIK' => $data_pasien_api['cust_usr_no_identitas'],
    'NoRM' => $data_pasien_api['cust_usr_kode']
  );

  $data_registrasi = array(
    'Instalasi' => $instalasi,
    'No_registrasi' => $kodeTrans,
    'TanggalRegistrasi' => date('Y-m-d'),
    'WaktuRegistrasi' => date("H:i:s"),
    'PetugasRegistrasi' => $_POST['nama_pasien'],
    'NamaPoli' => $data_poli_api["poli_nama"],
    'DPJP' => $data_dokter_api["usr_name"],
    'JenisPasien' => $jenisPasien['jenis_nama']
  );

  $skr = date('Y-m-d');
  $time_now = date('Y-m-d H:i:s');
  $hari = date('N');
  if ($hari == '7') {
    $hari = '0';
  }

  /* Cari no urut pasien */
  $sql = "select count(antrian_id) as jml  
             from klinik.klinik_nomer_antrian b
             left join  klinik.klinik_jadwal_dokter a on a.id_dokter = b.id_dokter
             where DATE(b.when_create)=" . QuoteValue(DPE_DATE, $skr) . " 
             and b.id_dokter=" . QuoteValue(DPE_CHAR, $_POST['dokter']) . "
             and  a.jadwal_dokter_hari =" . QuoteValue(DPE_CHAR, $hari) . "
              and  a.id_poli =" . QuoteValue(DPE_CHAR,  $_POST['poli_klinik']);
  $nomer_pasien = $dtaccess->Fetch($sql);
  $nomer_antrian =  $nomer_pasien["jml"] + 1;

  $kode_dokter = $data_dokter_api['kode_nama'];
  $nomer = strtoupper($kode_dokter) . "-" . $nomer_antrian;

  /* Insert ke table */
  $dbTable = "klinik.klinik_nomer_antrian";

  $dbField[0] = "antrian_id";   // PK
  $dbField[1] = "no_antrian_pasien";
  $dbField[2] = "id_reg";
  $dbField[3] = "id_poli";
  $dbField[4] = "status_antrian";
  $dbField[5] = "when_create";
  $dbField[6] = "id_dokter";

  $dbValue[0] = QuoteValue(DPE_CHAR, $dtaccess->GetTransID());
  $dbValue[1] = QuoteValue(DPE_CHAR, $nomer);
  $dbValue[2] = QuoteValue(DPE_CHAR, $regId);
  $dbValue[3] = QuoteValue(DPE_CHAR, $_POST['poli_klinik']);
  $dbValue[4] = QuoteValue(DPE_CHAR, 'A');
  $dbValue[5] = QuoteValue(DPE_DATE, $time_now);
  $dbValue[6] = QuoteValue(DPE_DATE, $_POST['dokter']);


  $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
  $dtmodel = new DataModel($dbTable, $dbField, $dbValue, $dbKey);
  $dtmodel->Insert() or die("insert  error");

  unset($dtmodel);
  unset($dbField);
  unset($dbValue);
  unset($dbKey);
?>
  <script>
    window.open("cetak_registrasi_online.php?id=<?= $_POST['kode_booking'] ?>", "_blank");
    window.location.href = "antri_tambah.php";
  </script>
<?php
}
?>

<!DOCTYPE html>
<html>
<?php require_once($LAY . "header.php") ?>
<style type="text/css">
  body {
    margin: 0;
    padding: 0;
    background: url('../lcd/bg.jpg');
    background-size: 100%;
    -moz-background-size: 100%;
  }

  .judul {
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

  #header {
    margin: 0;
    width: 100%;
    height: 100px;
    background: #108a49;
  }
</style>

<body>
  <div id="header">
    <table border="0" width="100%" height="100px">
      <tr>
        <td width="20%" align="center"><img src="<?= $fotoName; ?>" height="80px" width="80px" style="border-radius: 150px;"></td>
        <td width="60%">
          <h1 class="judul"><?= $konfigurasi['dep_title'] ?></h1>
        </td>
        <td width="20%"></td>
      </tr>
    </table>
  </div>
  <div class="container body col-md-6" style="padding-top: 2%;">
    <form name="frmFind" method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>">
      <input type="hidden" name="cust_usr_id" id="cust_usr_id" value="">
      <input type="hidden" name="jenis_pasien" id="jenis_pasien" value="">
      <input type="hidden" name="poli_klinik" id="poli_klinik" value="">
      <input type="hidden" name="dokter" id="dokter" value="">
      <div class="main_container">
        <div class="right_col col-md-offset-2 col-md-8" role="main">
          <div class="x_title">
            <center>
              <h1><?= $tableHeader ?></h1>
            </center>
            <div class="clearfix"></div>
          </div>
          <div class="x_panel">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <h3 class="col-md-4 col-sm-4 col-xs-4">Kode Booking</h3>
              <div class="col-md-8 col-sm-8 col-xs-8 input-group">
                <input type="text" name="kode_booking" value="" placeholder="Kode Booking" class="form-control" style="height: 50px" onkeyup="fun_booking(value.length, value)">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="main_container" id="detail">
        <div class="right_col col-md-offset-2 col-md-8" role="main">
          <div class="x_panel">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <label class="control-label col-md-4 col-sm-4 col-xs-4">No RM</label>
              <div class="col-md-8 col-sm-8 col-xs-8 input-group">
                <input type="text" class="form-control" name="no_rm" id="no_rm" value="" placeholder="No. RM" readonly="">
              </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <label class="control-label col-md-4 col-sm-4 col-xs-4">Nama Pasien</label>
              <div class="col-md-8 col-sm-8 col-xs-8 input-group">
                <input type="text" class="form-control" name="nama_pasien" id="nama_pasien" value="" placeholder="Nama Pasien" readonly="">
              </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <label class="control-label col-md-4 col-sm-4 col-xs-4">Tanggal Lahir</label>
              <div class="col-md-8 col-sm-8 col-xs-8 input-group">
                <input type="text" class="form-control" name="tgl_lahir" id="tgl_lahir" value="" placeholder="Tanggal Lahir" readonly="">
              </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <label class="control-label col-md-4 col-sm-4 col-xs-4">Poli Klinik</label>
              <div class="col-md-8 col-sm-8 col-xs-8 input-group">
                <input type="text" class="form-control" name="poli" id="poli" value="" placeholder="Poli Klinik" readonly="">
              </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <label class="control-label col-md-4 col-sm-4 col-xs-4">Dokter</label>
              <div class="col-md-8 col-sm-8 col-xs-8 input-group">
                <input type="text" class="form-control" name="dokter_nama" id="dokter_nama" value="" placeholder="Dokter" readonly="">
              </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <label class="control-label col-md-4 col-sm-4 col-xs-4">Tanggal Pemeriksaan</label>
              <div class="col-md-8 col-sm-8 col-xs-8 input-group">
                <input type="text" class="form-control" name="tgl_regitrasi" id="tgl_regitrasi" value="" placeholder="Tanggal Pemeriksaan" readonly="">
              </div>
            </div>
            <input type="submit" name="cetak" value="Cetak" class="btn btn-success pull-right col-md-3 col-sm-3 col-xs-3">
            <a href="antri_tambah.php" title="Kembali" class="btn btn-danger pull-right col-md-3 col-sm-3 col-xs-3">Kembali</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</body>

</html>

<script type="text/javascript">
  function fun_booking(panjang, isi) {
    if (panjang == 14) {
      $.getJSON('data_booking.php', {
        id: isi
      }, function(json, data) {
        $('#no_rm').val(json.cust_usr_kode);
        $('#cust_usr_id').val(json.cust_usr_id);
        $('#nama_pasien').val(json.cust_usr_nama);
        $('#tgl_lahir').val(json.cust_usr_tanggal_lahir);
        $('#poli').val(json.poli_nama);
        $('#poli_klinik').val(json.poli_id);
        $('#dokter_nama').val(json.usr_name);
        $('#dokter').val(json.id_dokter);
        $('#tgl_regitrasi').val(json.reg_buffer_tanggal);
        $('#jenis_pasien').val(json.reg_buffer_jenis_pasien);
      });
    }
  }
</script>