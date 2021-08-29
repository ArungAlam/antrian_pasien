<?php
require_once "../penghubung.inc.php";
require_once $LIB . "/login.php";
require_once $LIB . "/datamodel.php";
require_once $LIB . "/dateLib.php";
// require_once "api_print.php";


$dtaccess = new DataAccess();
$id_reg = $_POST['id_reg'];

/* ambil data */
$sql = "select a.reg_tanggal , reg_waktu,
				b.cust_usr_kode_tampilan as norm,cust_usr_nama, cust_usr_umur as umur, b.cust_usr_kode,b.cust_usr_alamat as alamat, 
				c.poli_nama, d.jenis_nama as jenis_bayar,
				e.usr_name as dokter, c.poli_tipe
		from klinik.klinik_registrasi a 
				left join global.global_customer_user b on a.id_cust_usr = b.cust_usr_id
				left join global.global_auth_poli c on a.id_poli = c.poli_id
				left join global.global_jenis_pasien d on a.reg_jenis_pasien = d.jenis_id
				left join global.global_auth_user e on e.usr_id = a.id_dokter
					where  reg_tracer = 'n' and  a.reg_jenis_pasien = '5' and poli_tipe != 'L' and poli_tipe != 'R' and UPPER(poli_nama) != 'POLI LOKET' and  poli_tipe != 'A' order by reg_tanggal desc";
$data = $dtaccess->Fetch($sql);
/* update reg tracer */
$sql = "update klinik.klinik_registrasi set reg_tracer='y' where
            reg_id ='$id_reg'";
$rs = $dtaccess->Execute($sql);
// print_r($data);
/*  kirim ke direc print */
   if($data['norm'] !=''){
		 	echo "kirim";
 			// $result = CallAPI('POST','192.168.0.27/dextra/api/tracer',$data);
	 }
