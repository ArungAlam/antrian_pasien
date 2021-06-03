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
  $userData = $auth->GetUserData();
  $depNama = $auth->GetDepNama();
  $depId = $auth->GetDepId();
  $userName = $auth->GetUserName();
  $userId = $auth->GetUserId();

	if (date('N')=='7') $ntgl='0';
  else $ntgl=date('N');

  /* SQL DOKTER */
  $sql = "select a.usr_name,a.usr_id,b.id_poli,c.rol_jabatan from global.global_auth_user a left join global.global_auth_user_poli b on a.usr_id = b.id_usr left join global.global_auth_role c on a.id_rol = c.rol_id left join klinik.klinik_jadwal_dokter d on a.usr_id = d.id_dokter";
  $sql .= " where c.rol_jabatan = 'D'  and b.id_poli=d.id_poli";   
  $sql .= " and usr_status = 'y'";    
  $sql .= " and b.id_poli =".QuoteValue(DPE_CHAR,$_POST['poli_id']);
  $sql .= " and (d.jadwal_dokter_jam_mulai < ".QuoteValue(DPE_DATE,date("H:i:s"))." or d.jadwal_dokter_jam_mulai > ".QuoteValue(DPE_DATE,date("H:i:s")).")";
  $sql .= " and d.jadwal_dokter_jam_selesai >".QuoteValue(DPE_DATE,date("H:i:s"));
  $sql .= " and d.jadwal_dokter_hari = ".QuoteValue(DPE_CHAR,$ntgl);
  $sql .= " order by usr_name asc"; 
  $dataDokter = $dtaccess->FetchAll($sql);
  /* SQL DOKTER */

  $rowCount =  count($dataDokter);

  /* PILIHAN DOKTER */
  if($rowCount > 0){
    echo '<option value="">Pilih dokter</option>';
    for($i=0,$n=count($dataDokter);$i<$n;$i++){ 
      echo '<option value="'.$dataDokter[$i]["usr_id"].'">'.$dataDokter[$i]["usr_name"].'</option>';
    }
  }else{
    echo '<option value="">Tidak Ada Dokter</option>';
  }
  /* PILIHAN DOKTER */
?>