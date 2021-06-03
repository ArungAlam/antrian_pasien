<?php
     //LIBRARY 
     require_once("../penghubung.inc.php");
     require_once($ROOT."lib/bit.php");
     require_once($ROOT."lib/login.php");
     require_once($ROOT."lib/encrypt.php");
     require_once($ROOT."lib/datamodel.php");
     require_once($ROOT."lib/dateLib.php");
     require_once($ROOT."lib/expAJAX.php");
     require_once($ROOT."lib/upload.php");
     require_once($ROOT."lib/tampilan.php");
     
     //INISIALISAI AWAL LIBRARY
     $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
   	 $dtaccess = new DataAccess();
     $enc = new textEncrypt();
     $auth = new CAuth();
     $err_code = 0;
     //$tableMskJadwal= new InoTable("table","100%","center");
     $date = date('Y-m-d H:i:s');
     $userData = $auth->GetUserData();
     $depNama = $auth->GetDepNama();
	   $depId = $auth->GetDepId();
	   $userName = $auth->GetUserName();
     $userId = $auth->GetUserId();
     $tgl = date("Y-m-d");
     $plx = new expAJAX("CheckKode,GetReg,GetJumlah1,GetJumlah2,GetJumlah3,GetJumlah4");  
     $cetakPage = "cetakkartu.php";   
     
     //AUTHENTIFIKASI 
     /*
 	   if(!$auth->IsAllowed("tampilan_pasien",PRIV_CREATE)){
          die("access_denied");
          exit(1);
     } else 
      if($auth->IsAllowed("tampilan_pasien",PRIV_CREATE)===1){
          echo"<script>window.parent.document.location.href='".$ROOT."login.php?msg=Login First'</script>";
          exit(1);
     } */

     //variable awal
     $_x_mode = "New";
     $thisPage = "registrasi.php";
     $viewPage = "pegawai_view.php";
     $findPage = "pasien_find.php?";
     $cariPage = "kk_find.php?"; 
     $bayarPage = "../kasir/kasir_loket.php";
		
	     //AJAX / JQUERY
       function CheckKode($kode,$custUsrId=null){
       	global $dtaccess;
            
            $sql = "SELECT a.cust_usr_id FROM   global.global_customer_user a 
                    WHERE upper(a.cust_usr_kode) = ".QuoteValue(DPE_CHAR,strtoupper($kode));
                      
            if($custUsrId) $sql .= " and a.cust_usr_id <> ".QuoteValue(DPE_CHAR,$custUsrId);
            
            $rs = $dtaccess->Execute($sql);
            $dataAdaKode = $dtaccess->Fetch($rs);           
  			return $dataAdaKode["cust_usr_id"];
       }       
       function GetReg($kode){
       	global $dtaccess;
            
            $sql = "SELECT reg_id FROM  klinik.klinik_registrasi a
                    join global.global_customer_user b on b.cust_usr_id = a.id_cust_usr  
                    WHERE reg_status not like ".QuoteValue(DPE_CHAR,STATUS_SELESAI."%")." 
                    and upper(b.cust_usr_kode) = ".QuoteValue(DPE_CHAR,strtoupper($kode));
                      
            if($custUsrId) $sql .= " and a.cust_usr_id <> ".QuoteValue(DPE_CHAR,$custUsrId);
            
            $rs = $dtaccess->Execute($sql);
            $data = $dtaccess->Fetch($rs);
  			return $data["reg_id"];
       }
       
       function GetJumlah1() {
	  global $dtaccess, $depId;
	  
	   $sql = "select count(reg_antri_id) as total from klinik.klinik_reg_antrian_reguler where id_dep = ".QuoteValue(DPE_CHAR,$depId)." 
            and id_poli='1' 
            and reg_antri_suara='0'";
     $rs = $dtaccess->Execute($sql);
     $dataLoket1 = $dtaccess->Fetch($rs);
     
	 return "Jumlah Antrian Umum : ".$dataLoket1["total"];
	}

	function GetJumlah2() {
	  global $dtaccess, $depId;
	  
	   $sql = "select count(reg_antri_id) as total from klinik.klinik_reg_antrian_reguler where id_dep = ".QuoteValue(DPE_CHAR,$depId)." 
            and id_poli='2' 
            and reg_antri_suara='0'";
     $rs = $dtaccess->Execute($sql);
     $dataLoket1 = $dtaccess->Fetch($rs);
     
	 return "Jumlah Antrian : ".$dataLoket1["total"];
	}
  
	function GetJumlah3() {
	  global $dtaccess, $depId;
	  
	   $sql = "select count(reg_antri_jkn_id) as total from klinik.klinik_reg_antrian_jkn_reguler where id_dep = ".QuoteValue(DPE_CHAR,$depId)." 
            and id_poli='2' 
            and reg_antri_jkn_suara='0'";
     $rs = $dtaccess->Execute($sql);
     $dataLoket1 = $dtaccess->Fetch($rs);
     
	 return "Jumlah Antrian JKN : ".$dataLoket1["total"];
	}    
   
	function GetJumlah4() {
	  global $dtaccess, $depId;
	  
	   $sql = "select count(reg_antri_id) as total from klinik.klinik_reg_antrian_reguler where id_dep = ".QuoteValue(DPE_CHAR,$depId)." 
            and id_poli='4' 
            and reg_antri_suara='0'";
     $rs = $dtaccess->Execute($sql);
     $dataLoket1 = $dtaccess->Fetch($rs);
     
	 return "Jumlah Antrian : ".$dataLoket1["total"];
	}   
  
       
    $lokasi = $ROOT."gambar/foto_pasien";
    $lokTakeFoto = $ROOT."gambar/foto_pasien";
    //$lokpas = "uploads/original1";
            
    if($_POST["jadwal_id"])  $jadwalId = $_POST["jadwal_id"];
           
    //AMBIL DATA AWAL UNTUK EDIT
	  if($_POST["btnLanjut"]) {
    
    		$sql = "select a.* from global.global_customer_user a 
    					where a.cust_usr_kode = ".QuoteValue(DPE_CHAR,$_POST["cust_usr_kode"]); 
    		$rs = $dtaccess->Execute($sql);
			$checkPasien = $dtaccess->Fetch($rs);   
        //echo $sql;
    // jika paasien daftar di penjadwalan //
    if($checkPasien["cust_usr_baru"]) {
    
		$_POST["cust_usr_id"] = $checkPasien["cust_usr_id"]; 
		$_POST["cust_usr_nama"] = htmlspecialchars($checkPasien["cust_usr_nama"]); 
		$_POST["cust_usr_tempat_lahir"] = $checkPasien["cust_usr_tempat_lahir"]; 
		$_POST["cust_usr_tanggal_lahir"] = format_date($checkPasien["cust_usr_tanggal_lahir"]); 
		$_POST["cust_usr_jenis_kelamin"] = $checkPasien["cust_usr_jenis_kelamin"]; 
		$_POST["cust_usr_suami"] = htmlspecialchars($checkPasien["cust_usr_suami"]); 
		$_POST["cust_usr_alamat"] = htmlspecialchars($checkPasien["cust_usr_alamat"]);
		$_POST["cust_usr_jenis"] = $checkPasien["reg_jenis_pasien"];
		$_POST["cust_usr_no_identitas"] = $checkPasien["cust_usr_no_identitas"];
		$_POST["cust_usr_no_jamkesmas"] = $checkPasien["cust_usr_no_jamkesmas"];
		$_POST["cust_usr_no_jamkesda"] = $checkPasien["cust_usr_no_jamkesda"];
		$_POST["cust_usr_no_sktm"] = $checkPasien["cust_usr_no_sktm"];
		$_POST["cust_usr_no_askes"] = $checkPasien["cust_usr_no_askes"];
		
    	$_POST["cust_usr_no_hp"] = $checkPasien["cust_usr_no_hp"]; 
    	$_POST["cust_usr_email"] = $checkPasien["cust_usr_email"];
    	$_POST["cust_usr_foto"] = $checkPasien["cust_usr_foto"];
    	$_POST["cust_usr_huruf"] = $checkPasien["cust_usr_huruf"];
    	$_POST["cust_usr_huruf_urut"] = $checkPasien["cust_usr_huruf_urut"];
    	$_POST['id_usr'] =  $checkPasien["id_usr"];
    	$_POST['id_info'] =  $checkPasien["id_info"];
    	$umurPasien=explode("~",$checkPasien["cust_usr_umur"]);
    	$_POST["tahun"]=$umurPasien[0];
    	$_POST["bulan"]=$umurPasien[1];
    	$_POST["hari"]=$umurPasien[2];
    	$_POST["cust_usr_baru"]=$checkPasien["cust_usr_baru"]; 
    	$_POST["cust_usr_negara"]=$checkPasien["cust_usr_negara"];
    	$_POST["cust_usr_asal_negara"]=$checkPasien["cust_usr_asal_negara"];
    	$_POST["cust_usr_pekerjaan"]=$checkPasien["cust_usr_pekerjaan"];
    	$_POST["cust_usr_no_telp"]=$checkPasien["cust_usr_no_telp"];
    	$_POST["id_sender_lucky"]=$checkPasien["id_sender_lucky"];
    	$_POST["id_kecamatan"]=$checkPasien["id_kecamatan"];
    	$_POST["id_kelurahan"]=$checkPasien["id_kelurahan"];
    	$_POST["id_pekerjaan"]=$checkPasien["id_pekerjaan"];
    	$_POST["id_pendidikan"]=$checkPasien["id_pendidikan"];
    	$_POST["cust_usr_nama_kk"]=$checkPasien["cust_usr_nama_kk"];
    	$_POST["cust_usr_dusun"]=$checkPasien["cust_usr_dusun"];
    	$_POST["reg_asal"]=$checkPasien["cust_usr_asal"];

    // jika pasien lama di check, tp jika pasien baru lgsng aja //
    } else if(!$checkPasien["cust_usr_baru"]) {
    
		$sql = "select a.*,reg_jenis_pasien, b.reg_status, b.reg_tanggal  
            from global.global_customer_user a
				left join klinik.klinik_registrasi b on b.id_cust_usr = a.cust_usr_id 
				where a.cust_usr_kode = ".QuoteValue(DPE_CHAR,$_POST["cust_usr_kode"])." order by b.reg_when_update desc, b.reg_tanggal desc"; 
		$rs = $dtaccess->Execute($sql);		
		$dataPasien = $dtaccess->Fetch($rs);

        if(!$dataPasien) {
        	echo "<font color='green' size='4'><strong><blink>HINT : MAAF, PASIEN BELUM TERDAFTAR</blink></strong></font>";
         die();
          
        }else{
        
         if($dataPasien['reg_status']{0}!='E'){
         	$nowStatus = $rawatStatus[$dataPasien['reg_status']{1}];
            $nowPasien = $rawatStatus[$dataPasien['reg_status']{0}];
            echo "<blink><font color='green' size='4'><strong>Hint : Pasien ".$dataPasien['cust_usr_kode']." Sedang Berada di  ".strtoupper($nowStatus)." ".strtoupper($nowPasien)." , harap selesaikan PEMERIKSAAN terlebih dahulu</strong></font></blink>";
            die(); 
         }
        } 
    $_POST["cust_usr_id"] = $dataPasien["cust_usr_id"];
		$_POST["cust_nama"] = htmlspecialchars($dataPasien["cust_nama"]);
		$_POST["cust_usr_nama"] = htmlspecialchars($dataPasien["cust_usr_nama"]);
		$_POST["cust_usr_tempat_lahir"] = $dataPasien["cust_usr_tempat_lahir"];
		$_POST["cust_usr_tanggal_lahir"] = format_date($dataPasien["cust_usr_tanggal_lahir"]);    
		$_POST["cust_usr_jenis_kelamin"] = $dataPasien["cust_usr_jenis_kelamin"]; 
		$_POST["cust_usr_suami"] = htmlspecialchars($dataPasien["cust_usr_suami"]);
		$_POST["cust_usr_alamat"] = htmlspecialchars($dataPasien["cust_usr_alamat"]);
		$_POST["cust_usr_no_identitas"] = $dataPasien["cust_usr_no_identitas"];
		$_POST["cust_usr_no_jamkesmas"] = $dataPasien["cust_usr_no_jamkesmas"];
		$_POST["cust_usr_no_jamkesda"] = $dataPasien["cust_usr_no_jamkesda"];  
		$_POST["cust_usr_no_sktm"] = $dataPasien["cust_usr_no_sktm"];
		$_POST["cust_usr_no_askes"] = $dataPasien["cust_usr_no_askes"];
		
		$_POST["cust_usr_jenis"] = $dataPasien["cust_usr_jenis"];
		
		
		//$_POST["cust_usr_jenis"] = $dataPasien["reg_jenis"];
		  $_POST["cust_usr_no_hp"] = $dataPasien["cust_usr_no_hp"]; 
    	$_POST["cust_usr_email"] = $dataPasien["cust_usr_email"];
    	$_POST["cust_usr_foto"] = $dataPasien["cust_usr_foto"];
    	$_POST["cust_usr_huruf"] = $dataPasien["cust_usr_huruf"];
    	$_POST["cust_usr_huruf_urut"] = $dataPasien["cust_usr_huruf_urut"];
    	$_POST["id_usr"] =  $dataPasien["id_usr"];
    	$_POST["id_info"] =  $dataPasien["id_info"];
		  $umurPasien=explode("~",$dataPasien["cust_usr_umur"]);
    	$_POST["tahun"]=$umurPasien[0];
    	$_POST["bulan"]=$umurPasien[1];
    	$_POST["hari"]=$umurPasien[2];
    	$_POST["cust_usr_baru"]=$dataPasien["cust_usr_baru"];
    	$_POST["cust_usr_negara"]=$dataPasien["cust_usr_negara"];
    	$_POST["cust_usr_asal_negara"]=$dataPasien["cust_usr_asal_negara"];
    	$_POST["cust_usr_pekerjaan"]=$dataPasien["cust_usr_pekerjaan"];
    	$_POST["cust_usr_no_telp"]=$dataPasien["cust_usr_no_telp"];
    	$_POST["id_sender_lucky"] =  $dataPasien["id_sender_lucky"];
    	$_POST["id_kecamatan"]=$dataPasien["id_kecamatan"];
    	$_POST["id_kelurahan"]=$dataPasien["id_kelurahan"];
    	$_POST["id_pekerjaan"]=$dataPasien["id_pekerjaan"];
    	$_POST["id_pendidikan"]=$dataPasien["id_pendidikan"];
    	$_POST["cust_usr_nama_kk"]=$dataPasien["cust_usr_nama_kk"];
    	$_POST["cust_usr_dusun"]=$dataPasien["cust_usr_dusun"];
    	//$_POST["cust_usr_foto_gigi"] = $dataPasien["cust_usr_foto_gigi"];
    	$_POST["reg_status"] =  $dataPasien["reg_status"];
    	$_POST["reg_asal"]=$dataPasien["cust_usr_asal"];
   }
   
    // jika ada jam antrinya --  
    if($_POST["jams"])  $_POST["id_jam"] = $_POST["jams"];
    // Jika ada dokternya --
    if($_POST["id_dok"])  $_POST["id_dokter"] = $_POST["id_dok"]; 
    
    $_x_mode = "Edit";
    
    $sql = "select * from global.global_perusahaan where perusahaan_id = ".QuoteValue(DPE_CHAR,$_POST["ush_id"]);
    $rs = $dtaccess->Execute($sql);
    $ushId = $dtaccess->Fetch($rs);
    
    $_POST["ush_nama"] = $ushId["perusahaan_nama"];
    $_POST["corporate_id"] = $ushId["perusahaan_id"];
    
	}//end lanjut

   	if($_POST["btnBack"]) {           
      	header("location:antrian.php");
        	exit();            
                     
      }
  
  if ($_POST["hidSave"]) $_POST["btnSave"]=1;
  
     
  	// ADD / EDIT DATA
	// ----- update data ----- //
/*	if ($_POST["btnSave"] || $_POST["btnUpdate"]) {
     
		if($_POST["btnUpdate"]){
      	$userCustId = & $_POST["cust_usr_id"];
         $usrId = & $_POST["id_usr"];
         $_x_mode = "Edit";  
      }    

   
     
      	if($_POST["cust_usr_kode"]) {      
        		$sql = "select cust_usr_id  from global.global_customer_user where cust_usr_kode=".QuoteValue(DPE_CHAR,$_POST["cust_usr_kode"])." 
                    and id_dep=".QuoteValue(DPE_CHAR,$depId);
        		$idPasien = $dtaccess->Fetch($sql);   
     }   
     
     
     
          
          $sql = "select max(reg_antri_nomer) as nomore from klinik.klinik_reg_antrian where id_dep = ".QuoteValue(DPE_CHAR,$depId);          
          $noAntrian = $dtaccess->Fetch($sql);
    	    $noantri =  ($noAntrian["nomore"]+1);
    	    
    	    $dbTable = "klinik.klinik_reg_antrian";
              $dbField[0] = "reg_antri_id";   // PK
              $dbField[1] = "reg_antri_nomer";
              $dbField[2] = "id_cust_usr";
              $dbField[3] = "id_dep";
              $dbField[4] = "id_poli";    
              $dbField[5] = "reg_antri_suara";   
              $dbField[6] = "reg_antri_tanggal";    
              
                   $byrId = $dtaccess->GetTransID();
                   $dbValue[0] = QuoteValue(DPE_NUMERIC,$noantri);
                   $dbValue[1] = QuoteValue(DPE_NUMERIC,$noantri);
                   $dbValue[2] = QuoteValue(DPE_CHAR,$idPasien["cust_usr_id"]);
                   $dbValue[3] = QuoteValue(DPE_CHAR,$depId);
                   $dbValue[4] = QuoteValue(DPE_CHAR,$_POST["id_poli"]);
                   $dbValue[5] = QuoteValue(DPE_CHAR,'0');
                   $dbValue[6] = QuoteValue(DPE_DATE,date('Y-m-d'));

                   $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
                   $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
                   
                   $dtmodel->Insert() or die("insert  error");
                   
                   unset($dbField);
                   unset($dtmodel);
                   unset($dbValue);
                   unset($dbKey);
    	      	
     
     $sql = "select * from klinik.klinik_antrian where id_dep = ".QuoteValue(DPE_CHAR,$depId)." order by antri_id asc";
     $rs = $dtaccess->Execute($sql);
     $dataAntrian = $dtaccess->FetchAll($rs);
     
     //print_r($dataAntrian);
     //die();
  
  		      $cetak_antrian='y';
         
      if($konfigurasi_cetak_kartu=='n' || !$konfigurasi_cetak_kartu){             

  		      header('location: antrian.php?id='.$userCustId.'&noantri='.$noantri);
      
         exit();  
      }   
       
    	}*/        
      
      
      if($_POST["btnBaru"]){
        $sql = "select * from global.global_departemen where dep_id=".QuoteValue(DPE_CHAR,$depId);
        $rs = $dtaccess->Execute($sql);
        $konf = $dtaccess->Fetch($rs);
      
      $sql = "select max(reg_antri_nomer) as nomore from klinik.klinik_reg_antrian_reguler where id_dep = ".QuoteValue(DPE_CHAR,$depId);          
          $noAntrian = $dtaccess->Fetch($sql);
    	    $noantri =  ($noAntrian["nomore"]+1);
          //if ($noantri<700) $noantri=$noantri+700;
          if ($noantri<$konf["dep_no_urut_antrian_reguler"]) $noantri=$noantri+$konf["dep_no_urut_antrian_reguler"];
    	    
          $dbTable = "klinik.klinik_waktu_tunggu";
              $dbField[0] = "klinik_waktu_tunggu_id";   // PK
              $dbField[1] = "cetak_antrian";
              $dbField[2] = "klinik_waktu_tunggu_create";
              
                   $tungguBaruId = $dtaccess->GetTransID();
                   $dbValue[0] = QuoteValue(DPE_CHAR,$tungguBaruId);
                   $dbValue[1] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
                   $dbValue[2] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));

                   $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
                   $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
                   
                   $dtmodel->Insert() or die("insert  error");
                   
                   unset($dbField);
                   unset($dtmodel);
                   unset($dbValue);
                   unset($dbKey);
                   
    	    $dbTable = "klinik.klinik_reg_antrian_reguler";
              $dbField[0] = "reg_antri_id";   // PK
              $dbField[1] = "reg_antri_nomer";
              $dbField[2] = "id_cust_usr";
              $dbField[3] = "id_dep";
              $dbField[4] = "id_poli";    
              $dbField[5] = "reg_antri_suara";   
              $dbField[6] = "reg_antri_tanggal"; 
              $dbField[7] = "id_klinik_waktu_tunggu";   
              
                   $byrId = $dtaccess->GetTransID();
                   $dbValue[0] = QuoteValue(DPE_NUMERIC,$noantri);
                   $dbValue[1] = QuoteValue(DPE_NUMERIC,$noantri);
                   $dbValue[2] = QuoteValue(DPE_CHAR,$idPasien["cust_usr_id"]);
                   $dbValue[3] = QuoteValue(DPE_CHAR,$depId);
                   $dbValue[4] = QuoteValue(DPE_CHAR,1);
                   $dbValue[5] = QuoteValue(DPE_CHAR,'0');
                   $dbValue[6] = QuoteValue(DPE_DATE,date('Y-m-d'));
                   $dbValue[7] = QuoteValue(DPE_CHAR,$tungguBaruId);

                   $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
                   $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
                   
                   $dtmodel->Insert() or die("insert  error");
                   
                   unset($dbField);
                   unset($dtmodel);
                   unset($dbValue);
                   unset($dbKey);
      
       $cetak_antrian='y';
      
      }
      
      
     /* if($_POST["btnLama"]){
      
      
      $sql = "select max(reg_antri_nomer) as nomore from klinik.klinik_reg_antrian where id_dep = ".QuoteValue(DPE_CHAR,$depId);          
          $noAntrian = $dtaccess->Fetch($sql);
    	    $noantri =  ($noAntrian["nomore"]+1);
    	    
    	    $dbTable = "klinik.klinik_reg_antrian";
              $dbField[0] = "reg_antri_id";   // PK
              $dbField[1] = "reg_antri_nomer";
              $dbField[2] = "id_cust_usr";
              $dbField[3] = "id_dep";
              $dbField[4] = "id_poli";    
              $dbField[5] = "reg_antri_suara";   
              $dbField[6] = "reg_antri_tanggal";    
              
                   $byrId = $dtaccess->GetTransID();
                   $dbValue[0] = QuoteValue(DPE_NUMERIC,$noantri);
                   $dbValue[1] = QuoteValue(DPE_NUMERIC,$noantri);
                   $dbValue[2] = QuoteValue(DPE_CHAR,$idPasien["cust_usr_id"]);
                   $dbValue[3] = QuoteValue(DPE_CHAR,$depId);
                   $dbValue[4] = QuoteValue(DPE_CHAR,2);
                   $dbValue[5] = QuoteValue(DPE_CHAR,'0');
                   $dbValue[6] = QuoteValue(DPE_DATE,date('Y-m-d'));

                   $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
                   $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
                   
                   $dtmodel->Insert() or die("insert  error");
                   
                   unset($dbField);
                   unset($dtmodel);
                   unset($dbValue);
                   unset($dbKey);
      
       $cetak_antrian='y';
      
      }    */
      
      if($_POST["btnAskes"]){
        $sql = "select * from global.global_departemen where dep_id=".QuoteValue(DPE_CHAR,$depId);
        $rs = $dtaccess->Execute($sql);
        $konf = $dtaccess->Fetch($rs);
      
      $sql = "select max(reg_antri_nomer) as nomore from klinik.klinik_reg_antrian_reguler where id_dep = ".QuoteValue(DPE_CHAR,$depId);          
          $noAntrian = $dtaccess->Fetch($sql);
          $noantri =  ($noAntrian["nomore"]+1);
    	    //if ($noantri<150) $noantri=$noantri+150;
          if ($noantri<$konf["dep_no_urut_antrian_reguler"]) $noantri=$noantri+$konf["dep_no_urut_antrian_reguler"];
          
          $dbTable = "klinik.klinik_waktu_tunggu";
              $dbField[0] = "klinik_waktu_tunggu_id";   // PK
              $dbField[1] = "cetak_antrian";
              $dbField[2] = "klinik_waktu_tunggu_create";
              
                   $tungguId = $dtaccess->GetTransID();
                   $dbValue[0] = QuoteValue(DPE_CHAR,$tungguId);
                   $dbValue[1] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
                   $dbValue[2] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));

                   $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
                   $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
                   
                   $dtmodel->Insert() or die("insert  error");
                   
                   unset($dbField);
                   unset($dtmodel);
                   unset($dbValue);
                   unset($dbKey);
          /*
    	    $dbTable = "klinik.klinik_reg_antrian_reguler";
              $dbField[0] = "reg_antri_id";   // PK
              $dbField[1] = "reg_antri_nomer";
              $dbField[2] = "id_cust_usr";
              $dbField[3] = "id_dep";
              $dbField[4] = "id_poli";    
              $dbField[5] = "reg_antri_suara";   
              $dbField[6] = "reg_antri_tanggal"; 
              $dbField[7] = "id_klinik_waktu_tunggu";   
              
                   $byrId = $dtaccess->GetTransID();
                   $dbValue[0] = QuoteValue(DPE_NUMERIC,$noantri);
                   $dbValue[1] = QuoteValue(DPE_NUMERIC,$noantri);
                   $dbValue[2] = QuoteValue(DPE_CHAR,$idPasien["cust_usr_id"]);
                   $dbValue[3] = QuoteValue(DPE_CHAR,$depId);
                   $dbValue[4] = QuoteValue(DPE_CHAR,1);
                   $dbValue[5] = QuoteValue(DPE_CHAR,'0');
                   $dbValue[6] = QuoteValue(DPE_DATE,date('Y-m-d'));
                   $dbValue[7] = QuoteValue(DPE_CHAR,$tungguId);

                   $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
                   $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
                   
                   $dtmodel->Insert() or die("insert  error");
                   
                   unset($dbField);
                   unset($dtmodel);
                   unset($dbValue);
                   unset($dbKey);
            */
       //$cetak_antrian='y';
       $regpaslamaPage = "reg_pasien_lama.php?konsul=1&id_waktu_tunggu=".$tungguId;
       header("location:".$regpaslamaPage);
       exit();
      }

      if($_POST["btnAskes2"]){
        $sql = "select * from global.global_departemen where dep_id=".QuoteValue(DPE_CHAR,$depId);
        $rs = $dtaccess->Execute($sql);
        $konf = $dtaccess->Fetch($rs);
      
      $sql = "select max(reg_antri_nomer) as nomore from klinik.klinik_reg_antrian_reguler where id_dep = ".QuoteValue(DPE_CHAR,$depId);          
          $noAntrian = $dtaccess->Fetch($sql);
          $noantri =  ($noAntrian["nomore"]+1);
    	    //if ($noantri<150) $noantri=$noantri+150;
          if ($noantri<$konf["dep_no_urut_antrian_reguler"]) $noantri=$noantri+$konf["dep_no_urut_antrian_reguler"];
          
          $dbTable = "klinik.klinik_waktu_tunggu";
              $dbField[0] = "klinik_waktu_tunggu_id";   // PK
              $dbField[1] = "cetak_antrian";
              $dbField[2] = "klinik_waktu_tunggu_create";
              
                   $tungguId = $dtaccess->GetTransID();
                   $dbValue[0] = QuoteValue(DPE_CHAR,$tungguId);
                   $dbValue[1] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
                   $dbValue[2] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));

                   $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
                   $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
                   
                   $dtmodel->Insert() or die("insert  error");
                   
                   unset($dbField);
                   unset($dtmodel);
                   unset($dbValue);
                   unset($dbKey);
        /*  
    	    $dbTable = "klinik.klinik_reg_antrian_reguler";
              $dbField[0] = "reg_antri_id";   // PK
              $dbField[1] = "reg_antri_nomer";
              $dbField[2] = "id_cust_usr";
              $dbField[3] = "id_dep";
              $dbField[4] = "id_poli";    
              $dbField[5] = "reg_antri_suara";   
              $dbField[6] = "reg_antri_tanggal"; 
              $dbField[7] = "id_klinik_waktu_tunggu";   
              
                   $byrId = $dtaccess->GetTransID();
                   $dbValue[0] = QuoteValue(DPE_NUMERIC,$noantri);
                   $dbValue[1] = QuoteValue(DPE_NUMERIC,$noantri);
                   $dbValue[2] = QuoteValue(DPE_CHAR,$idPasien["cust_usr_id"]);
                   $dbValue[3] = QuoteValue(DPE_CHAR,$depId);
                   $dbValue[4] = QuoteValue(DPE_CHAR,1);
                   $dbValue[5] = QuoteValue(DPE_CHAR,'0');
                   $dbValue[6] = QuoteValue(DPE_DATE,date('Y-m-d'));
                   $dbValue[7] = QuoteValue(DPE_CHAR,$tungguId);

                   $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
                   $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
                   
                   $dtmodel->Insert() or die("insert  error");
                   
                   unset($dbField);
                   unset($dtmodel);
                   unset($dbValue);
                   unset($dbKey);
          */
       //$cetak_antrian='y';
       $regpaslamaPage = "reg_pasien_lama.php?suntik=1&id_waktu_tunggu=".$tungguId;
       header("location:".$regpaslamaPage);
       exit();
      }
      if($_POST["btnAskes3"]){
        $sql = "select * from global.global_departemen where dep_id=".QuoteValue(DPE_CHAR,$depId);
        $rs = $dtaccess->Execute($sql);
        $konf = $dtaccess->Fetch($rs);
      
      $sql = "select max(reg_antri_nomer) as nomore from klinik.klinik_reg_antrian_reguler where id_dep = ".QuoteValue(DPE_CHAR,$depId);          
          $noAntrian = $dtaccess->Fetch($sql);
          $noantri =  ($noAntrian["nomore"]+1);
    	    //if ($noantri<150) $noantri=$noantri+150;
          if ($noantri<$konf["dep_no_urut_antrian_reguler"]) $noantri=$noantri+$konf["dep_no_urut_antrian_reguler"];
          
          $dbTable = "klinik.klinik_waktu_tunggu";
              $dbField[0] = "klinik_waktu_tunggu_id";   // PK
              $dbField[1] = "cetak_antrian";
              $dbField[2] = "klinik_waktu_tunggu_create";
              
                   $tungguId = $dtaccess->GetTransID();
                   $dbValue[0] = QuoteValue(DPE_CHAR,$tungguId);
                   $dbValue[1] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
                   $dbValue[2] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));

                   $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
                   $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
                   
                   $dtmodel->Insert() or die("insert  error");
                   
                   unset($dbField);
                   unset($dtmodel);
                   unset($dbValue);
                   unset($dbKey);
        /*  
    	    $dbTable = "klinik.klinik_reg_antrian_reguler";
              $dbField[0] = "reg_antri_id";   // PK
              $dbField[1] = "reg_antri_nomer";
              $dbField[2] = "id_cust_usr";
              $dbField[3] = "id_dep";
              $dbField[4] = "id_poli";    
              $dbField[5] = "reg_antri_suara";   
              $dbField[6] = "reg_antri_tanggal"; 
              $dbField[7] = "id_klinik_waktu_tunggu";   
              
                   $byrId = $dtaccess->GetTransID();
                   $dbValue[0] = QuoteValue(DPE_NUMERIC,$noantri);
                   $dbValue[1] = QuoteValue(DPE_NUMERIC,$noantri);
                   $dbValue[2] = QuoteValue(DPE_CHAR,$idPasien["cust_usr_id"]);
                   $dbValue[3] = QuoteValue(DPE_CHAR,$depId);
                   $dbValue[4] = QuoteValue(DPE_CHAR,1);
                   $dbValue[5] = QuoteValue(DPE_CHAR,'0');
                   $dbValue[6] = QuoteValue(DPE_DATE,date('Y-m-d'));
                   $dbValue[7] = QuoteValue(DPE_CHAR,$tungguId);

                   $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
                   $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
                   
                   $dtmodel->Insert() or die("insert  error");
                   
                   unset($dbField);
                   unset($dtmodel);
                   unset($dbValue);
                   unset($dbKey);
          */
       //$cetak_antrian='y';
       $regpaslamaPage = "reg_pasien_lama.php?jadwal=1&id_waktu_tunggu=".$tungguId;
       header("location:".$regpaslamaPage);
       exit();
      }
/*      if($_POST["btnAskes"]){
        $sql = "select * from global.global_departemen where dep_id=".QuoteValue(DPE_CHAR,$depId);
        $rs = $dtaccess->Execute($sql);
        $konf = $dtaccess->Fetch($rs);
      
      $sql = "select max(reg_antri_jkn_nomer) as nomore from klinik.klinik_reg_antrian_jkn_reguler where id_dep = ".QuoteValue(DPE_CHAR,$depId);          
          $noAntrian = $dtaccess->Fetch($sql);
          $noantri =  ($noAntrian["nomore"]+1);
    	    //if ($noantri<150) $noantri=$noantri+150;
          if ($noantri<$konf["dep_no_urut_jkn_antrian_reguler"]) $noantri=$noantri+$konf["dep_no_urut_jkn_antrian_reguler"];
          
          $dbTable = "klinik.klinik_waktu_tunggu";
              $dbField[0] = "klinik_waktu_tunggu_id";   // PK
              $dbField[1] = "cetak_antrian";
              $dbField[2] = "klinik_waktu_tunggu_create";
              
                   $tungguId = $dtaccess->GetTransID();
                   $dbValue[0] = QuoteValue(DPE_CHAR,$tungguId);
                   $dbValue[1] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
                   $dbValue[2] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));

                   $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
                   $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
                   
                   $dtmodel->Insert() or die("insert  error");
                   
                   unset($dbField);
                   unset($dtmodel);
                   unset($dbValue);
                   unset($dbKey);
          
    	    $dbTable = "klinik.klinik_reg_antrian_jkn_reguler";
              $dbField[0] = "reg_antri_jkn_id";   // PK
              $dbField[1] = "reg_antri_jkn_nomer";
              $dbField[2] = "id_cust_usr";
              $dbField[3] = "id_dep";
              $dbField[4] = "id_poli";    
              $dbField[5] = "reg_antri_jkn_suara";   
              $dbField[6] = "reg_antri_tanggal";
              $dbField[7] = "id_klinik_waktu_tunggu";    
              
                   $byrId = $dtaccess->GetTransID();
                   $dbValue[0] = QuoteValue(DPE_NUMERIC,$noantri);
                   $dbValue[1] = QuoteValue(DPE_NUMERIC,$noantri);
                   $dbValue[2] = QuoteValue(DPE_CHAR,$idPasien["cust_usr_id"]);
                   $dbValue[3] = QuoteValue(DPE_CHAR,$depId);
                   $dbValue[4] = QuoteValue(DPE_CHAR,2);
                   $dbValue[5] = QuoteValue(DPE_CHAR,'0');
                   $dbValue[6] = QuoteValue(DPE_DATE,date('Y-m-d'));
                   $dbValue[7] = QuoteValue(DPE_CHAR,$tungguId);

                   $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
                   $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
                   
                   $dtmodel->Insert() or die("insert  error");
                   
                   unset($dbField);
                   unset($dtmodel);
                   unset($dbValue);
                   unset($dbKey);
      
       //$cetak_antrian='y';
       $regpaslamaPage = "reg_pasien_lama.php?id_waktu_tunggu=".$tungguId;
       header("location:".$regpaslamaPage);
       exit();
      }  */
         
     if($_POST["custTambah"])   { unset ($_POST["cust_usr_kode"]); unset($_POST["cust_usr_nama"]); unset($_POST["cust_usr_alamat"]); } // apbila tekan tombol tambah maka hilangkan isi pada cust_usr_kode
   
   
     // check kode pasien --
     if ($_POST["cust_usr_kode"]) {
  
     $sql = "select cust_usr_id,cust_usr_kode from global.global_customer_user
             where cust_usr_kode = ".QuoteValue(DPE_CHAR,$_POST["cust_usr_kode"]);
     $rs_edit = $dtaccess->Execute($sql,DB_SCHEMA_GLOBAL);
     $row_edit = $dtaccess->Fetch($rs_edit);
		
		 $_POST["cust_usr_kode"] = $row_edit["cust_usr_kode"];
		 $_POST["cust_usr_id"] = $row_edit["cust_usr_id"];
		 
     }      
     // buat batal registrasi     
     if ($_POST["btnDel"]) {         

     $custUsrId = & $_POST["cust_usr_id"];       
		 $sql = "delete from  global.global_customer_user where cust_usr_id = ".QuoteValue(DPE_CHAR,$custUsrId)." and id_dep=".QuoteValue(DPE_CHAR,$depId);	
     $dtaccess->Execute($sql,DB_SCHEMA_GLOBAL); 		
     $reset=1;
                   
  		header("location:registrasi.php");
  		exit();      
  		
     }
     
     
     // --- cari poli ---
     $sql = "select poli_nama,poli_id, id_biaya from global.global_auth_poli where poli_flag='y' and poli_id<>'5' order by poli_id";
     $rs = $dtaccess->Execute($sql,DB_SCHEMA_GLOBAL);
     $dataPoli = $dtaccess->FetchAll($rs);

    // KONFIGURASI
     $sql = "select * from global.global_departemen where dep_id =".QuoteValue(DPE_CHAR,$depId);
     $rs = $dtaccess->Execute($sql);
     $konfigurasi = $dtaccess->Fetch($rs);  
     
     if ($konfigurasi["dep_height"]!=0) $panjang=$konfigurasi["dep_height"] ;
     if ($konfigurasi["dep_width"]!=0) $lebar=$konfigurasi["dep_width"] ;
     $fotoName = $ROOT."/gambar/img_cfg/".$konfigurasi["dep_logo"]; 
     $bg = $ROOT."/gambar/img_cfg/".$konfigurasi["dep_logo"];
     $lokasi = $ROOT."/gambar/img_cfg";
     $lokasiSikita = $ROOT."/gambar/";     

?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<html>
<script src="<?php echo $ROOT;?>lib/script/antri/jquery.min.js"></script>

<script language="javascript">        
var _wnd_stat;


function BukaStatWindow(url,judul)
{
    if(!_wnd_stat) {
			_wnd_stat = window.open(url,judul,'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=200,height=200,left=100,top=100');
	    this.window.focus();
      } else {
		if (_wnd_stat.closed) {
			_wnd_stat = window.open(url,judul,'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=200,height=200,left=100,top=100');
		this.window.focus();
    } else {
		_wnd_stat.focus();
		} 
	} 
     return false;
}

<?php if($cetak_antrian=="y"){ ?>
        //if(confirm('Cetak no antrian?'))
		BukaStatWindow('cetakantrian.php?id=<?php echo $noantri;?>&noantri=<?php echo $noantri;?>','No Antrian');
    <?php } ?> 

function ProsesCetakAntrian(id) {
  BukaWindow('cetakantrian.php','No Antrian');
	//document.location.href='<?php echo $thisPage;?>';
}
 
function Logout()
{
    if(confirm('Are You Sure to LogOut?')) window.parent.document.location.href='<?php echo $ROOT;?>logout.php';
    else return false;
}
</script>

<style type="text/css">
*{ font-weight: bold;}
body{ margin:0; padding:0; background: url(bg.jpg); background-size: 100%;-moz-background-size: 100%;}
#header{margin:0;  width:100%; height:80px;background: #108a49;}
#kiri{padding-top: 2%; padding-left: 25%; width: 48%; }
#tombol{ width: 48%; float: left; padding: 10px;}
.left{ width:270px; height:80px; background:url(<?php echo $lokasi."/".$konfigurasi["dep_logo_kiri_antrian"];?>)no-repeat; background-size: 230px 60px; float:left;position: absolute; left: 0; top: 10;}
.center{ max-width:100%; float:left; text-align:center; background: #000;}
.right{ width:230px; height:80px; no-repeat;background-size: 220px 76px; float:right; position: absolute; right: 0; top: 0;}
.nom, .nam{ float: left; line-height: 100px; font-size: 50px; font-weight: bold; padding-top: 10px;}
.nom{ width: 120px; text-align: left;}
h1{ text-transform: uppercase; text-decoration: none; line-height: 80px; margin-right: 60px; margin-top: 0; color: #fff; font-size: 40px; font-weight: bold; text-align: center; border: none;}
marquee{ font-size: 50px; font-weight: bold; text-transform: uppercase; position: absolute ; bottom: 0; width: 100%;}
.nomor{ max-width: 100%; height: 165px; padding:2px; margin-bottom: 13px;  border: 1px solid #e0e0e0; border-radius: 10px; -moz-border-radius: 10px; background: #fafafa;
box-shadow: }
h3{color:#fff; margin:0;max-width: 100%; padding: 5px 10px; background: #108a49; text-align: center; font-size: 40px;border-radius: 10px 10px 0 0; -moz-border-radius: 10px 10px 0 0; text-transform: uppercase;}
label{ position: fixed; padding-left: 45%;}
img.pp{ border-radius:0 0 10px 0; -moz-border-radius:0 0 10px 0; float: right; display: block; height: 100px; padding-top: 10px}
marquee{ position: absolute; bottom: 0; left: 0; color: #fff;}

.button, submit, reset { 
    display: inline-block; 
    outline: none; 
    cursor: pointer; 
    text-align: center; 
    text-decoration: none; 
    height: 50px
    width: 100px
    font: 20px/100% Arial, Helvetica, sans-serif; 
    padding: .5em 2em .55em; 
    text-shadow: 0 1px 1px rgba(0,0,0,.3); 
    -webkit-border-radius: .5em; 
    -moz-border-radius: .5em; 
    border-radius: .5em; 
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.2); 
    -moz-box-shadow: 0 1px 2px rgba(0,0,0,.2); 
    box-shadow: 0 1px 2px rgba(0,0,0,.2); 
} 
.button:hover { 
    text-decoration: none; 
} 
.button:active { 
    position: relative; 
    top: 1px; 
}
</style>

</head>

<body >   
<form name="frmView" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
<div id="header">
  <div class="left">&nbsp;</div>
  <h1><?php echo $konfigurasi["dep_header_kanan_antrian"];?></h1>
  <div class="right">&nbsp;</div>
</div>

<div id="kiri">
  <div id="loaddiv-1" align="center" class="nomor"><h3>PERAWATAN</h3>
  <input type="submit" name="btnBaru" id="btnBaru" style="font-size:2em;width=200px;height:75px" value="PERAWATAN KLIK DISINI" class="tombol"/></div>

  <div id="loaddiv-2" align="center" class="nomor"><h3>APOTIK</h3>
  <input type="submit" name="btnAskes" id="btnAskes" style="font-size:2em;width=200px;height:75px" value="APOTIK KLIK DISINI" class="tombol"/></div> 

  <div id="loaddiv-3" align="center" class="nomor"><h3>PERAWATAN TERJADWAL</h3>
  <input type="submit" name="btnAskes3" id="btnAskes3" style="font-size:2em;width=200px;height:75px" value="PASIEN TERJADWAL KLIK DISINI" class="tombol"/></div>   

</div>
<marquee><?php echo $konfigurasi["dep_footer_antrian"];?></marquee>
</form>
</body>
</html>

