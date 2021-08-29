<?php
require_once "../penghubung.inc.php";
require_once $LIB . "/login.php";
require_once $LIB . "/datamodel.php";
require_once $LIB . "/dateLib.php";

$dtaccess = new DataAccess();
$sql = "select reg_id , cust_usr_nama, cust_usr_kode from klinik.klinik_registrasi a 
				left join global.global_customer_user b on a.id_cust_usr = b.cust_usr_id
				where  reg_tracer = 'n' order by reg_tanggal desc";
$data = $dtaccess->Fetch($sql);


echo json_encode($data);


