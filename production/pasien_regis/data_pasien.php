<?php
//LIBRARY 
require_once("../penghubung.inc.php");
require_once($LIB . "login.php");
require_once($LIB . "encrypt.php");
require_once($LIB . "datamodel.php");
require_once($LIB . "currency.php");
require_once($LIB . "dateLib.php");

//INISIALISAI AWAL LIBRARY
$dtaccess = new DataAccess();
$enc = new textEncrypt();
$auth = new CAuth();
$userData = $auth->GetUserData();
$depNama = $auth->GetDepNama();
$depId = $auth->GetDepId();
$userName = $auth->GetUserName();
$userId = $auth->GetUserId();

$kode = ($_GET["cust_usr_kode"]) ? $_GET["cust_usr_kode"] : $_POST["cust_usr_kode"];

$sql = "SELECT cust_usr_nama, cust_usr_tanggal_lahir, cust_usr_id, cust_usr_kode, cust_usr_no_jaminan FROM global.global_customer_user WHERE ";
if ($kode) $sql .= "cust_usr_kode = " . QuoteValue(DPE_CHAR, $kode);
elseif ($_GET['cust_usr_no_jaminan']) $sql .= "cust_usr_no_jaminan = " . QuoteValue(DPE_CHAR, $_GET['cust_usr_no_jaminan']);
$data = $dtaccess->Fetch($sql);
$hasil = array();
$hasil['cust_usr_nama'] = $data['cust_usr_nama'];
$hasil['cust_usr_tanggal_lahir'] = date_db($data['cust_usr_tanggal_lahir']);
$hasil['cust_usr_id'] = $data['cust_usr_id'];
$hasil['cust_usr_kode'] = $data['cust_usr_kode'];
$hasil['cust_usr_no_jaminan'] = $data['cust_usr_no_jaminan'];

echo json_encode($hasil);
