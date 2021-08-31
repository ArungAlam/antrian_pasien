<?php
require_once("../penghubung.inc.php");
require_once($LIB . "login.php");
require_once($LIB . "encrypt.php");
require_once($LIB . "datamodel.php");
require_once($LIB . "tampilan.php");
require_once($LIB . "currency.php");
require_once($LIB . "dateLib.php");

$dtaccess = new DataAccess();
$enc = new textEncrypt();
$auth = new CAuth();
$view = new CView($_SERVER['PHP_SELF'], $_SERVER['QUERY_STRING']);
$depId = $auth->GetDepId();
$depNama = $auth->GetDepNama();
$plx = new expAJAX("");


if ($_GET["id"]) {

	$_POST["cust_usr_id"] = $_GET["id"];

	$sql = "select a.cust_usr_jenis_kelamin, a.cust_usr_tanggal_lahir, a.cust_usr_kode,
	a.cust_usr_foto,a.cust_usr_nama,a.cust_usr_alamat as alamat1,a.cust_usr_suami,
	((current_date - a.cust_usr_tanggal_lahir)/365) as umur, c.id_poli, d.poli_nama, 
	c.reg_status_cetak_kartu,a.cust_usr_nama_kk
  		from   global.global_customer_user a  
  		left join  klinik.klinik_registrasi c on c.id_cust_usr = a.cust_usr_id
  		left join   global.global_auth_poli d on d.poli_id = c.id_poli
  		where a.cust_usr_id = " . QuoteValue(DPE_CHAR, $_POST["cust_usr_id"]);

	$rs = $dtaccess->Execute($sql, DB_SCHEMA_GLOBAL);
	$dataPasien = $dtaccess->Fetch($rs);

	//var_dump($dataPasien);
	//echo $sql;
	//echo "data".$dataPasien["cust_usr_nama"];
	if ($dataPasien["cust_usr_foto"]) {
		$fotoPasien = $ROOT . "/gambar/foto_pasien/" . $dataPasien["cust_usr_foto"];
	} else {
		$fotoPasien = $ROOT . "/gambar/foto_pasien/default.jpg";
	}

	//update status 
	$sql = "update klinik.klinik_registrasi set reg_status_cetak_kartu = 'y' where id_cust_usr = " . QuoteValue(DPE_CHAR, $_POST["cust_usr_id"]) . " and id_dep=" . QuoteValue(DPE_CHAR, $depId);
	$dtaccess->Execute($sql);
}

$sql = "select * from klinik.klinik_reg_antrian_reguler where reg_antri_id =" . QuoteValue(DPE_CHAR, $_GET["id"]);
$rs = $dtaccess->Execute($sql);
$antri = $dtaccess->Fetch($rs);

$jenis[1] = 'BPJS';
$jenis[2] = 'PRIORITY';
$jenis[3] = 'ASURANSI';
$jenis[4] = 'JKN MOBILE';

if ($antri['reg_antri_suara'] == 'A' || $antri['reg_antri_suara'] == 'B' || $antri['reg_antri_suara'] == 'C') $poli = 'POLIKLINIK PAGI';
else if ($antri['reg_antri_suara'] == 'D' || $antri['reg_antri_suara'] == 'F' || $antri['reg_antri_suara'] == 'E') $poli = 'POLIKLINIK SORE';

$sql = "select id_poli from klinik.klinik_reg_antrian_jkn_reguler where reg_antri_jkn_id =" . QuoteValue(DPE_CHAR, $_GET["id"]);
$rs = $dtaccess->Execute($sql);
$antriJkn = $dtaccess->Fetch($rs);

if ($antri["id_poli"] == 1) $poliNama = "PASIEN REGULER";
if ($antriJkn["id_poli"] == 2) $poliNama = "PASIEN JKN PBI";
if ($antriJkn["id_poli"] == 3) $poliNama = "PASIEN JKN PBI";


// KONFIHURASI
$sql = "select * from global.global_departemen where dep_id =" . QuoteValue(DPE_CHAR, $depId);
$rs = $dtaccess->Execute($sql);
$konfigurasi = $dtaccess->Fetch($rs);

if ($konfigurasi["dep_height"] != 0) $panjang = $konfigurasi["dep_height"];
if ($konfigurasi["dep_width"] != 0) $lebar = $konfigurasi["dep_width"];
$fotoName = $ROOT . "/gambar/img_cfg/" . $konfigurasi["dep_logo"];
$bg = $ROOT . "/gambar/img_cfg/" . $konfigurasi["dep_logo"];

$sql = "select * from global.global_konfigurasi_kartu where id_dep =" . QuoteValue(DPE_CHAR, $depId);
$rs = $dtaccess->Execute($sql);
$konfKartu = $dtaccess->Fetch($rs);
$fotoKiri = $ROOT . "kasir/images/konfigurasi_kartu/" . $konfKartu["konf_kartu_pic_kiri"];
$fotoKanan = $ROOT . "kasir/images/konfigurasi_kartu/" . $konfKartu["konf_kartu_pic_kanan"];
$fotoBelakangKiri = $ROOT . "kasir/images/konfigurasi_kartu/" . $konfKartu["konf_kartu_pic_belakang_kiri"];
$fotoBelakangKanan = $ROOT . "kasir/images/konfigurasi_kartu/" . $konfKartu["konf_kartu_pic_belakang_kanan"];


	include 'api_print.php';
	$data = array(
		'dep_nama' => $depNama,
		'poli_ket' => $poli,
		'poli_nama' => $jenis[$antri['id_poli']],
		'no_antri' => $antri['reg_antri_suara'].sprintf("%03d", $antri['reg_antri_nomer'])
	)	;

	$ip = my_ip();
	$result = CallAPI("POST", $ip."/dextra/api/antrian", $data);
?>
