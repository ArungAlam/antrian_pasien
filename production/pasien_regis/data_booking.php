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
$depId = $auth->GetDepId();
$userName = $auth->GetUserName();
$userId = $auth->GetUserId();

$sql = "SELECT a.reg_buffer_tanggal, a.reg_buffer_jenis_pasien, a.id_dokter, b.cust_usr_id, 
  b.cust_usr_nama, b.cust_usr_kode, b.cust_usr_tanggal_lahir, c.poli_nama, c.poli_id, d.usr_name FROM klinik.klinik_registrasi_buffer a 
  LEFT JOIN global.global_customer_user b ON a.id_cust_usr = b.cust_usr_id 
  LEFT JOIN global.global_auth_poli c ON a.id_poli = c.poli_id 
  LEFT JOIN global.global_auth_user d ON a.id_dokter = d.usr_id
  WHERE a.reg_buffer_status = 'y' AND a.reg_buffer_id = " . QuoteValue(DPE_CHAR, $_GET['id']) . " AND a.reg_buffer_tanggal >= " . QuoteValue(DPE_CHAR, date('Y-m-d'));
$data = $dtaccess->Fetch($sql);

$hasil['cust_usr_id'] = $data['cust_usr_id'];
$hasil['cust_usr_nama'] = $data['cust_usr_nama'];
$hasil['cust_usr_tanggal_lahir'] = date_db($data['cust_usr_tanggal_lahir']);
$hasil['cust_usr_kode'] = $data['cust_usr_kode'];
$hasil['reg_buffer_tanggal'] = date_db($data['reg_buffer_tanggal']);
$hasil['poli_nama'] = $data['poli_nama'];
$hasil['poli_id'] = $data['poli_id'];
$hasil['usr_name'] = $data['usr_name'];
$hasil['id_dokter'] = $data['id_dokter'];
$hasil['reg_buffer_jenis_pasien'] = $data['reg_buffer_jenis_pasien'];

echo json_encode($hasil);
