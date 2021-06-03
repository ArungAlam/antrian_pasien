<?php
 require_once("../penghubung.inc.php");
 require_once($LIB."login.php");
 require_once($LIB."datamodel.php");
 
  $dtaccess = new DataAccess();
  $depId ='9999999';
  $skr = date('Y-m-d');

	$id=$_GET['id'];
	$loket = '2';
	$sql = "select * from global.global_departemen where dep_id='$depId'";
	$depKonfig = $dtaccess->Fetch($sql);

  $sql = "select * from klinik.klinik_antrian_reguler where id_dep = ".QuoteValue(DPE_CHAR,$depId)." order by antri_id asc";
     
     $rs = $dtaccess->Execute($sql);
     $dataAntrian = $dtaccess->FetchAll($rs);
     
     // buat flag A jika gak ada antrian //
         $sql = "update klinik.klinik_antrian_reguler set antri_suara='A' 
         where antri_id = ".QuoteValue(DPE_NUMERIC,$dataAntrian[0]["antri_id"])." and id_dep = ".QuoteValue(DPE_CHAR,$depId);    		
         $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
     
    		 
    		 // ambil data utk di update ke klinik antrian //
    		 $sql = "select id_cust_usr, reg_antri_nomer, id_poli, id_klinik_waktu_tunggu from klinik.klinik_reg_antrian_reguler 
                where id_dep =".QuoteValue(DPE_CHAR,$depId)." and reg_antri_id = ".QuoteValue(DPE_CHAR,$id);
    		 $dataPasien = $dtaccess->Fetch($sql);
    		 
    		 // Cek data Data Antrian di klinik antrian //
    		 $sql = "select antri_id from klinik.klinik_antrian_reguler where id_dep =".QuoteValue(DPE_CHAR,$depId)." order by antri_id asc";
    		 $dataAntrian = $dtaccess->FetchAll($sql);
    		 
    		 // update data klinik antrian jika ngga ada antrian buffer //
    		 $sql = "update klinik.klinik_antrian_reguler set id_poli = ".QuoteValue(DPE_CHAR,$loket)." ,  id_loket = ".QuoteValue(DPE_CHAR,$loket)." ,
                id_cust_usr = ".QuoteValue(DPE_CHAR,$dataPasien["id_cust_usr"])." , antri_nomer = ".QuoteValue(DPE_NUMERIC,$dataPasien["reg_antri_nomer"])." 
                where antri_id = ".QuoteValue(DPE_CHAR,$dataAntrian[0]["antri_id"])." and id_dep = ".QuoteValue(DPE_CHAR,$depId);
    		 $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
    		 
		
    		 // UPDATE STATUS PASIENNYA //
    		 $sql = "update klinik.klinik_reg_antrian_reguler set reg_panggil='y', reg_antri_suara = 'A', antri_aktif='y', id_loket=".QuoteValue(DPE_CHAR,$loket)." 
                where reg_antri_id = ".QuoteValue(DPE_CHAR,$id)." and id_dep = ".QuoteValue(DPE_CHAR,$depId);
              //  return $sql;
    		 $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);

    		 $sql = "update klinik.klinik_reg_antrian_reguler set antri_aktif ='n' where id_loket=".QuoteValue(DPE_CHAR,$loket)." 
                and reg_antri_id <> ".QuoteValue(DPE_CHAR,$id)." and id_dep = ".QuoteValue(DPE_CHAR,$depId);
    		 $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
         
         //update waktu tunggu 
    		 $sql = "update klinik.klinik_waktu_tunggu set panggil_antrian=".QuoteValue(DPE_DATE,date("Y-m-d H:i:s")).", 
                input_rm = ".QuoteValue(DPE_DATE,date("Y-m-d H:i:s"))." where klinik_waktu_tunggu_id = ".QuoteValue(DPE_CHAR,$dataPasien["id_klinik_waktu_tunggu"]);
    		 $dtaccess->Execute($sql,DB_SCHEMA_KLINIK);
    		 
?>