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
     require_once($ROOT."lib/currency.php");
          
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
     $tahunTarif = $auth->GetTahunTarif(); 
      $plx = new expAJAX("GetDokter"); 
        
   //   $_POST["id_klinik_waktu_tunggu"]=$_GET["id_waktu_tunggu"];
   
   
   if($_GET["konsul"]) $_POST["konsul"] = $_GET["konsul"];
   if($_GET["suntik"]) $_POST["suntik"] = $_GET["suntik"];
   if($_GET["jadwal"]) $_POST["jadwal"] = $_GET["jadwal"]; 
   if($_GET["id_waktu_tunggu"]) $_POST["id_klinik_waktu_tunggu"] = $_GET["id_waktu_tunggu"];     
if($_GET["suntik"]){ 
      $_POST["id_poli"]= "suntik";
}    
       function GetDokter($id_poli=null){
        global $dtaccess,$view,$depId,$ROOT;
            
              $sql = "select * from global.global_auth_user a
                     left join global.global_auth_user_poli b on a.usr_id=b.id_usr
                     where a.id_dep =".QuoteValue(DPE_CHAR,$depId)." and (id_rol = '2' or id_rol = '5' ) 
                     and b.id_poli =".QuoteValue(DPE_CHAR,$id_poli)."  
        				     order by usr_name asc";
              $rs = $dtaccess->Execute($sql);       
        		 $dataDokter= $dtaccess->FetchAll($rs);    
     
     if($id_poli){   
          unset($dokter);
        			$dokter[0] = $view->RenderOption("--","[Silahkan Pilih Dokter]",$show,"style='font-size:15pt;'");
        			$i = 1;
        			
             for($i=0,$n=count($dataDokter);$i<$n;$i++){   
                 if($_POST["id_dokter"]==$dataDokter[$i]["usr_id"]) $show = "selected";
                 $dokter[$i+1] = $view->RenderOption($dataDokter[$i]["usr_id"],$dataDokter[$i]["usr_name"],$show,"style='font-size:15pt;'");
                 unset($show);
             }
        			$str = $view->RenderComboBox("id_dokter","id_dokter",$dokter,null,null,"style='font-size:15pt;'");
                               
        	 return $str;     
     
            }else{ echo "<font size='4'>Silahkan pilih nama Dokter</font>"; }
           }

      
if($_POST["btnSave"]){
       $_POST["reg_tipe_layanan"]=1;
       $_POST["reg_shift"]=1;
       $_POST["reguler"]=1;

     
 //       echo "tunggu".$_POST["id_klinik_waktu_tunggu"]."<br>";       
 //      echo "jadwal".$_POST["jadwal"]."<br>";
 //      echo $_POST["cust_usr_id"]."<br>"; //die();
     //cari data pasiennya
      
        $sql = "select a.*,reg_jenis_pasien, b.reg_bayar,b.reg_status, b.reg_no_sep, b.reg_tipe_jkn, b.reg_tipe_jkn, b.id_jamkesda_kota, b.id_perusahaan as id_corporate  
                from global.global_customer_user a
    				left join klinik.klinik_registrasi b on b.id_cust_usr = a.cust_usr_id 
    				where a.cust_usr_id = ".QuoteValue(DPE_CHAR,$_POST["cust_usr_id"]).
            " order by b.reg_when_update desc, b.reg_tanggal desc"; 
        $rs = $dtaccess->Execute($sql);		
    		$dataPasien = $dtaccess->Fetch($rs);
 
       //       echo $sql; die();
        $_POST["cust_usr_id"] = $dataPasien["cust_usr_id"];
        $_POST["vcust_usr_kode"] = $dataPasien["cust_usr_kode"];
    		$_POST["cust_nama"] = htmlspecialchars($dataPasien["cust_nama"]);
    		$_POST["cust_usr_nama"] = htmlspecialchars($dataPasien["cust_usr_nama"]);
    		$_POST["cust_usr_tempat_lahir"] = $dataPasien["cust_usr_tempat_lahir"];
    		$_POST["cust_usr_tanggal_lahir"] = format_date($dataPasien["cust_usr_tanggal_lahir"]);
        $tglLahir=explode("-",format_date($dataPasien["cust_usr_tanggal_lahir"]));
        $_POST["tgl"]=$tglLahir[0];
        $_POST["bln"]=$tglLahir[1];
        $_POST["thn"]=$tglLahir[2];    
    		$_POST["cust_usr_jenis_kelamin"] = $dataPasien["cust_usr_jenis_kelamin"]; 
    		$_POST["cust_usr_suami"] = htmlspecialchars($dataPasien["cust_usr_suami"]);
    		$_POST["vcust_usr_alamat"] = htmlspecialchars($dataPasien["cust_usr_alamat"]);
    		$_POST["cust_usr_no_identitas"] = $dataPasien["cust_usr_no_identitas"];
    		    		
    		$_POST["cust_usr_jenis"] = $dataPasien["cust_usr_jenis"];
    		
    		
    		  $_POST["cust_usr_no_hp"] = $dataPasien["cust_usr_no_hp"]; 
        	$_POST["cust_usr_email"] = $dataPasien["cust_usr_email"];
        	$_POST["cust_usr_foto"] = $dataPasien["cust_usr_foto"];
        	$_POST["cust_usr_huruf"] = $dataPasien["cust_usr_huruf"];
        	$_POST["cust_usr_huruf_urut"] = $dataPasien["cust_usr_huruf_urut"];
        	$_POST["id_usr"] =  $dataPasien["id_usr"];
        	$_POST["id_info"] =  $dataPasien["id_info"];
    		  
        	$_POST["cust_usr_baru"]=$dataPasien["cust_usr_baru"];
          $_POST["cust_usr_agama"]=$dataPasien["cust_usr_agama"];
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
        	$_POST["reg_status"] =  $dataPasien["reg_status"];
        	$_POST["reg_asal"]=$dataPasien["cust_usr_asal"];
          $_POST["cust_usr_penanggung_jawab_pendidikan"] =  $dataPasien["cust_usr_penanggung_jawab_pendidikan"];
          $_POST["cust_usr_penanggung_jawab_pekerjaan"] =  $dataPasien["cust_usr_penanggung_jawab_pekerjaan"];
          $_POST["cust_usr_no_jaminan"] =  $dataPasien["cust_usr_no_jaminan"];
          
          $_POST["cust_usr_penanggung_jawab_status"] =  $dataPasien["cust_usr_penanggung_jawab_status"];                               
          $_POST["cust_usr_penanggung_jawab"] =  $dataPasien["cust_usr_penanggung_jawab"];                               
          $_POST["reg_no_sep"] =  $dataPasien["reg_no_sep"];
          $_POST["reg_tipe_jkn"] =  $dataPasien["reg_tipe_jkn"];
          $_POST["id_jamkesda_kota"] =  $dataPasien["id_jamkesda_kota"];
          $_POST["cust_usr_jkn"] =  $dataPasien["cust_usr_jkn"];
          $_POST["id_corporate"] =  $dataPasien["id_perusahaan"];
          $_POST["cust_usr_jkn_asal"] =  $dataPasien["cust_usr_jkn_asal"];
          $_POST["reg_tgl_rujukan"] =  $dataPasien["reg_tgl_rujukan"];
          $_POST["hak_kelas_inap"] =  $dataPasien["hak_kelas_inap"];
          $_POST["reg_tgl_sep"] =  $dataPasien["reg_tgl_sep"];
          $_POST["reg_diagnosa_awal"] =  $dataPasien["reg_diagnosa_awal"];

          //hitung umur pasien
         $skr = date('Y-m-d'); 
        $umurPasien=DateDiff($dataPasien["cust_usr_tanggal_lahir"],$skr);
         $umurTahun = $umurPasien/365;
         $umurbulan = ($umurTahun-floor($umurTahun))*12;
         $umurHari = ($umurbulan-floor($umurbulan))*30;
          
        	$_POST["tahun"] = floor($umurTahun);
        	$_POST["bulan"]=floor($umurbulan);
        	$_POST["hari"]=round($umurHari);                    
        //KOTA DAN PROPINSI
          $_POST["id_prop"] = $dataPasien["id_prop"];
          $sql = "select lokasi_nama from global.global_lokasi where 
                  lokasi_propinsi = ".QuoteValue(DPE_CHAR,$_POST["id_prop"])." and
                  lokasi_kabupatenkota = ".QuoteValue(DPE_CHAR,0)." and 
                  lokasi_kecamatan = ".QuoteValue(DPE_CHAR,0)." and   
                  lokasi_kelurahan = ".QuoteValue(DPE_CHAR,0);
        	$rs_prop = $dtaccess->Execute($sql);
        	$row_prop = $dtaccess->Fetch($rs_prop);
          $_POST["prop_nama"]=$row_prop["lokasi_nama"];

           
  		   $_POST["id_kota"] = $dataPasien["id_kota"];
         $sql = "select lokasi_nama from global.global_lokasi where 
                  lokasi_propinsi = ".QuoteValue(DPE_CHAR,$_POST["id_prop"])." and
                  lokasi_kabupatenkota = ".QuoteValue(DPE_CHAR,$_POST["id_kota"])." and 
                  lokasi_kecamatan = ".QuoteValue(DPE_CHAR,0)." and   
                  lokasi_kelurahan = ".QuoteValue(DPE_CHAR,0);

        	$rs_kota = $dtaccess->Execute($sql);
        	$row_kota = $dtaccess->Fetch($rs_kota);
          $_POST["kota_nama"]=$row_kota["lokasi_nama"];

 		   $_POST["id_kec"] = $dataPasien["id_kecamatan"];
         $sql = "select lokasi_nama from global.global_lokasi where 
                  lokasi_propinsi = ".QuoteValue(DPE_CHAR,$_POST["id_prop"])." and
                  lokasi_kabupatenkota = ".QuoteValue(DPE_CHAR,$_POST["id_kota"])." and 
                  lokasi_kecamatan = ".QuoteValue(DPE_CHAR,$_POST["id_kec"])." and   
                  lokasi_kelurahan = ".QuoteValue(DPE_CHAR,0);
        	$rs_kec = $dtaccess->Execute($sql);
        	$row_kec = $dtaccess->Fetch($rs_kec);
          $_POST["kec_nama"]=$row_kec["lokasi_nama"];

 		   $_POST["id_kel"] = $dataPasien["id_kelurahan"];
         $sql = "select lokasi_nama from global.global_lokasi where 
                  lokasi_propinsi = ".QuoteValue(DPE_CHAR,$_POST["id_prop"])." and
                  lokasi_kabupatenkota = ".QuoteValue(DPE_CHAR,$_POST["id_kota"])." and 
                  lokasi_kecamatan = ".QuoteValue(DPE_CHAR,$_POST["id_kec"])." and   
                  lokasi_kelurahan = ".QuoteValue(DPE_CHAR,$_POST["id_kel"]);
        	$rs_kel = $dtaccess->Execute($sql);
        	$row_kel = $dtaccess->Fetch($rs_kel);
          $_POST["kel_nama"]=$row_kel["lokasi_nama"];

     	// KONFIGURASI
      $sql = "select * from global.global_departemen where dep_id = ".QuoteValue(DPE_CHAR,$depId);
    	$rs_edit = $dtaccess->Execute($sql);
    	$row_edit = $dtaccess->Fetch($rs_edit);
    	$dtaccess->Clear($rs_edit);
    	//echo $sql;
    	$_POST["dep_id"] = $row_edit["dep_id"];
      $_POST["dep_cet_status"] = $row_edit["dep_cet_status"];
      $_POST["dep_cet_barcode"] = $row_edit["dep_cet_barcode"]; 
      $_POST["dep_cet_kartu"] = $row_edit["dep_cet_kartu"];
      $_POST["dep_website"] = $row_edit["dep_website"];
      $_POST["dep_konf_no_depan"] =  $row_edit["dep_konf_no_depan"];
      $_POST["dep_sms"] =  $row_edit["dep_sms"];
      $_POST["dep_konf_reg"] = $row_edit["dep_konf_reg"];
      $_POST["dep_konf_kons"] = $row_edit["dep_konf_kons"];
      $_POST["dep_is_symbol"] = $row_edit["dep_is_symbol"];
      $_POST["dep_cet_antrian"] = $row_edit["dep_cet_antrian"];
      $_POST["dep_konf_reg_poli"] = $row_edit["dep_konf_reg_poli"];
 //     echo $_POST["dep_konf_reg"]."<br>";
      $_POST["dep_konf_loket_antrian_poli"] = $row_edit["dep_konf_loket_antrian_poli"];
      $_POST["dep_konf_kode_sub_instalasi"] = $row_edit["dep_konf_kode_sub_instalasi"];

          $_POST["dep_konf_kode_poli"]=$row_edit["dep_konf_kode_poli"];
          $_POST["dep_konf_urut_registrasi"]=$row_edit["dep_konf_urut_registrasi"];
          $_POST["dep_konf_urut_pasien"]=$row_edit["dep_konf_urut_pasien"];
          $_POST["dep_konf_kode_instalasi"] = $row_edit["dep_konf_kode_instalasi"];
          
      $login = explode(".", $_POST["dep_website"]);
      $web = substr($_POST["dep_website"], 4);
      $loginEmail = $login[1].".".$login[2]; 
      
       $konfigurasi_cetak_status = $_POST["dep_cet_status"];
      $konfigurasi_cetak_barcode = $_POST["dep_cet_barcode"];
      $konfigurasi_cetak_kartu = $_POST["dep_cet_kartu"];   
      $cetak_antrian= $_POST["dep_cet_antrian"];
         
         $tglLahir= explode("-",$_POST["cust_usr_tanggal_lahir"]);
         $ultah = $tglLahir[0].'-'.$tglLahir[1];
 
          $sql = "select * from global.global_lokasi where lokasi_kode like'".$_POST["kel"]."'";
          $lokasi = $dtaccess->Fetch($sql);

          $dbTable = "global.global_customer_user";
          
          $dbField[0] = "cust_usr_id";   // PK
          $dbField[1] = "cust_usr_nama";
          $dbField[2] = "cust_usr_tempat_lahir";
          $dbField[3] = "cust_usr_tanggal_lahir";
          $dbField[4] = "cust_usr_suami"; 
          $dbField[5] = "cust_usr_alamat";                 
          $dbField[6] = "cust_usr_jenis_kelamin";      
          $dbField[7] = "cust_usr_who_update";
          $dbField[8] = "cust_usr_when_update";
          $dbField[9] = "cust_usr_kode";
          $dbField[10] = "cust_usr_jenis";
          $dbField[11] = "cust_usr_no_hp";
          $dbField[12] = "id_dep";
          $dbField[13] = "cust_usr_umur";
          $dbField[14] = "id_usr";
          $dbField[15] = "cust_usr_email";
          $dbField[16] = "cust_usr_foto";
          $dbField[17] = "cust_usr_baru";  // cek buat pasien reistrasi di jadwal //
          $dbField[18] = "cust_usr_ultah";  // cek buat pasien reistrasi di jadwal //
          $dbField[19] = "id_info";
          $dbField[20] = "cust_usr_negara";
          $dbField[21] = "id_dokter";
          $dbField[22] = "cust_usr_no_telp";
          $dbField[23] = "cust_usr_asal_negara";
          $dbField[24] = "cust_usr_pekerjaan";
          $dbField[25] = "id_sender_lucky";
          $dbField[26] = "id_perusahaan";        
          $dbField[27] = "id_kecamatan";
          $dbField[28] = "id_kelurahan";
          $dbField[29] = "id_pekerjaan";
          $dbField[30] = "id_pendidikan";
          $dbField[31] = "cust_usr_nama_kk";
	  		  $dbField[32] = "cust_usr_dusun";
	  		  $dbField[33] = "cust_usr_asal";                
          $dbField[34] = "cust_usr_no_identitas";
          $dbField[35] = "id_prop";
          $dbField[36] = "id_kota";
          $dbField[37] = "cust_usr_penanggung_jawab";
          $dbField[38] = "cust_usr_penanggung_jawab_status";
          $dbField[39] = "cust_usr_no_jaminan";  
          $dbField[40] = "cust_usr_penanggung_jawab_pendidikan";   
          $dbField[41] = "cust_usr_penanggung_jawab_pekerjaan";  
          $dbField[42] = "cust_usr_jkn_asal";
          $dbField[43] = "cust_usr_jkn";
          $dbField[44] = "cust_usr_nik";
          $dbField[45] = "cust_usr_agama";

          $_POST["cust_usr_tanggal_lahir"]=$_POST["tgl"]."-".$_POST["bln"]."-".$_POST["thn"];
          
          // New
			    // buat ngecek tanda (') nyari nama pasien e --
          $stringss = explode("\'", $_POST["cust_usr_nama"]);
          $namaPasien = substr($_POST["cust_usr_nama"], -1);             
          $petik = "'";
          
          $namaAslie = $stringss[0];
          $namaPlusPetik = $namaAslie.$petik;
          $pasienPetik1 = $stringss[0]."'".$stringss[1];
          $pasienPetik2 = $stringss[0]."'".$stringss[1]."'".$stringss[2];
          $pasienPetik3 = $stringss[0]."'".$stringss[1]."'".$stringss[2]."'".$stringss[3];

        $userCustId = $_POST["cust_usr_id"];
          //if(!$_POST["cust_usr_id"]) $userCustId =  $_POST["vr_cust_usr_id"]; //$dtaccess->GetTransID("global.global_customer_user","cust_usr_id");
          
          $dbValue[0] = QuoteValue(DPE_CHAR,$userCustId);
          if($stringss[0] && $stringss[1] && $stringss[2] && $stringss[3]) {
             $dbValue[1] = QuoteValue(DPE_CHAR,$pasienPetik3);
          } elseif($stringss[0] && $stringss[1] && $stringss[2]) {
             $dbValue[1] = QuoteValue(DPE_CHAR,$pasienPetik2);
          } elseif($stringss[0] && $stringss[1]) {
             $dbValue[1] = QuoteValue(DPE_CHAR,$pasienPetik1);
          } elseif($stringss[0] && $namaPasien==$petik) {
             $dbValue[1] = QuoteValue(DPE_CHAR,$namaPlusPetik);
          } else {
             $dbValue[1] = QuoteValue(DPE_CHAR,$namaAslie);
          }
          //echo $dbValue[0];
          //exit();
          $dbValue[2] = QuoteValue(DPE_CHAR,$_POST["cust_usr_tempat_lahir"]);
          $dbValue[3] = QuoteValue(DPE_DATE,$dataPasien["cust_usr_tanggal_lahir"]);
          $dbValue[4] = QuoteValue(DPE_CHAR,$_POST["cust_usr_suami"]);
          $dbValue[5] = QuoteValue(DPE_CHAR,$_POST["vcust_usr_alamat"]);
          $dbValue[6] = QuoteValue(DPE_CHAR,$_POST["cust_usr_jenis_kelamin"]);
          $dbValue[7] = QuoteValue(DPE_CHAR,$userData["name"]);
          $dbValue[8] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
          $dbValue[9] = QuoteValue(DPE_CHAR,$_POST["vcust_usr_kode"]);
          $dbValue[10] = QuoteValue(DPE_CHAR,$_POST["cust_usr_jenis"]);
          $dbValue[11] = QuoteValue(DPE_CHAR,$_POST["cust_usr_no_hp"]);
          $dbValue[12] = QuoteValue(DPE_CHAR,$depId);
          $dbValue[13] = QuoteValue(DPE_CHAR,$_POST["tahun"]."~".$_POST["bulan"]."~".$_POST["hari"]);
          $dbValue[14] = QuoteValue(DPE_CHAR,$usrId);  
          $dbValue[15] = QuoteValue(DPE_CHAR,$_POST["cust_usr_email"]);
          $dbValue[16] = QuoteValue(DPE_CHAR,$_POST["cust_usr_foto"]);
          $dbValue[17] = QuoteValue(DPE_CHAR,'');
          $dbValue[18] = QuoteValue(DPE_CHAR,$ultah);
          $dbValue[19] = QuoteValue(DPE_CHAR,$_POST["id_info"]);
          $dbValue[20] = QuoteValue(DPE_CHAR,$_POST["cust_usr_negara"]);
          $dbValue[21] = QuoteValue(DPE_CHAR,$_POST["id_dokter"]);
          $dbValue[22] = QuoteValue(DPE_CHAR,$_POST["cust_usr_no_telp"]);
          $dbValue[23] = QuoteValue(DPE_CHAR,$_POST["cust_usr_asal_negara"]);
          $dbValue[24] = QuoteValue(DPE_CHAR,'');
          $dbValue[25] = QuoteValue(DPE_CHAR,'');
          $dbValue[26] = QuoteValue(DPE_CHAR,$_POST["id_corporate"]);
          $dbValue[27] = QuoteValue(DPE_CHAR,$lokasi["lokasi_kecamatan"]);
          $dbValue[28] = QuoteValue(DPE_CHAR,$lokasi["lokasi_kelurahan"]);
          $dbValue[29] = QuoteValue(DPE_CHAR,$_POST["id_pekerjaan"]);
          $dbValue[30] = QuoteValue(DPE_CHAR,$_POST["id_pendidikan"]);
          $dbValue[31] = QuoteValue(DPE_CHAR,$_POST["cust_usr_nama_kk"]);
	  		  $dbValue[32] = QuoteValue(DPE_CHAR,$_POST["cust_usr_dusun"]);
	  		  $dbValue[33] = QuoteValue(DPE_CHAR,$_POST["reg_asal"]);
	  		  $dbValue[34] = QuoteValue(DPE_CHAR,$_POST["cust_usr_no_identitas"]);  		  
	  		  $dbValue[35] = QuoteValue(DPE_CHAR,$lokasi["lokasi_propinsi"]);
	  		  $dbValue[36] = QuoteValue(DPE_CHAR,$lokasi["lokasi_kabupatenkota"]);          
          $dbValue[37] = QuoteValue(DPE_CHAR,$_POST["cust_usr_penanggung_jawab"]);
          $dbValue[38] = QuoteValue(DPE_CHAR,$_POST["cust_usr_penanggung_jawab_status"]);
          $dbValue[39] = QuoteValue(DPE_CHAR,$_POST["cust_usr_no_jaminan"]);
          $dbValue[40] = QuoteValue(DPE_CHAR,$_POST["cust_usr_penanggung_jawab_pendidikan"]);
          $dbValue[41] = QuoteValue(DPE_CHAR,$_POST["cust_usr_penanggung_jawab_pekerjaan"]);
          $dbValue[42] = QuoteValue(DPE_CHAR,$_POST["cust_usr_jkn_asal"]);
          $dbValue[43] = QuoteValue(DPE_CHAR,$_POST["reg_tipe_jkn"]);
          $dbValue[44] = QuoteValue(DPE_CHAR,$_POST["cust_usr_nik"]);
          $dbValue[45] = QuoteValue(DPE_CHAR,$_POST["cust_usr_agama"]);
        
//           print_r($dbValue);    echo "<br>";          

          $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
          $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
 
    				$dtmodel->Update() or die("update  error");
            
          unset($dtmodel);
          unset($dbField);
          unset($dbValue);
          unset($dbKey);
          
          $sql = "select reg_id, id_pembayaran from klinik.klinik_registrasi where id_cust_usr=".QuoteValue(DPE_CHAR,$userCustId)." 
                  and reg_shift=".QuoteValue(DPE_CHAR,$_POST["reg_shift"])." and (reg_utama is null or reg_utama='') 
                  and reg_tanggal=".QuoteValue(DPE_DATE,date("Y-m-d"))." and reg_jenis_pasien=".QuoteValue(DPE_NUMERIC,$_POST["cust_usr_jenis"]);
          $rs = $dtaccess->Execute($sql);
          $reg = $dtaccess->Fetch($rs);
      //     echo $sql;
          // ---- insert ke registrasi ----
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
          $dbField[9] = "id_poli";
          $dbField[10] = "id_dep";
          $dbField[11] = "reg_no_antrian";
          $dbField[12] = "reg_status_cetak_kartu";
          $dbField[13] = "id_jam";
          $dbField[14] = "id_dokter";
          $dbField[15] = "id_info";
          $dbField[16] = "reg_asal";
          $dbField[17] = "reg_umur";
          $dbField[18] = "reg_umur_hari";
          $dbField[19] = "reg_kartu";
          $dbField[20] = "reg_program";
          $dbField[21] = "reg_rujukan_id";         
          $dbField[22] = "id_prop";
          $dbField[23] = "id_kota";
          $dbField[24] = "reg_shift";
          $dbField[25] = "reg_tipe_layanan";
          $dbField[26] = "reg_umur_bulan";
          $dbField[27] = "reg_kode_trans";
          $dbField[28] = "reg_kode_urut";
          $dbField[29] = "reg_sebab_sakit";
          $dbField[30] = "id_instalasi";
          $dbField[31] = "reg_kelengkapan_dokumen";
          $dbField[32] = "reg_jkn_bersyarat";
          $dbField[33] = "reg_urut";     
          $dbField[34] = "reg_tipe_rawat";    
          if($reg["reg_id"]){
            $dbField[35] = "reg_utama";
            $dbField[36] = "id_pembayaran";
            if($_POST["cust_usr_jenis"]=='18'){
            $dbField[37] = "id_jamkesda_kota";
            } elseif($_POST["cust_usr_jenis"]=='7') { 
            $dbField[37] = "id_perusahaan";
            } elseif($_POST["cust_usr_jenis"]=='5' || $_POST["cust_usr_jenis"]=='26'){
            $dbField[37] = "reg_no_sep";
            $dbField[38] = "reg_tipe_jkn";
            } elseif($_POST["cust_usr_jenis"]=='25'){
            $dbField[37] = "reg_tipe_paket";
            }
          } else {
            if($_POST["cust_usr_jenis"]=='18'){
            $dbField[35] = "id_jamkesda_kota";
            } elseif($_POST["cust_usr_jenis"]=='7') { 
            $dbField[35] = "id_perusahaan";
            } elseif($_POST["cust_usr_jenis"]=='5' || $_POST["cust_usr_jenis"]=='26'){
            $dbField[35] = "reg_no_sep";
            $dbField[36] = "reg_tipe_jkn";
            } elseif($_POST["cust_usr_jenis"]=='25'){
            $dbField[35] = "reg_tipe_paket";
            }
          }
          
          if(!$_POST["reg_status_pasien"]) $_POST["reg_status_pasien"] ='L';
          $status = 'M0';   //status di UGD
          if($_POST["btnSave"]) $statusPasien =$_POST["reg_status_pasien"];
          else $statusPasien = 'L';

          $regId = $dtaccess->GetTransID();
          
          if ($_POST["dep_konf_loket_antrian_poli"]=='n') { //apabila cetak antrian tidak per klinik
                   $sql = "select max(reg_no_antrian) as nomore from klinik.klinik_registrasi where reg_tanggal = ".QuoteValue(DPE_DATE,date("Y-m-d"))." and id_dep = ".QuoteValue(DPE_CHAR,$depId);          
          } else {
          
                    if($_POST["jadwal"]){
                    if(!empty($_POST['id_biaya'])){
                    foreach($_POST['id_biaya'] as $key){
                    
                    //cari poli dari biaya yang ada
                    $sql = "select id_poli from klinik.klinik_biaya where biaya_id = ".QuoteValue(DPE_CHAR,$key);
                    $polibiaya = $dtaccess->Fetch($sql);
                   // echo $sql; die();      
                    }}else{
                       $sql = "select b.id_poli from klinik.klinik_penjadwalan a
                               left join klinik.klinik_biaya b on a.id_biaya = b.biaya_id
                               where a.id_cust_usr =".QuoteValue(DPE_CHAR,$_POST["cust_usr_id"])." 
                               and penjadwalan_tanggal= ".QuoteValue(DPE_DATE,$skr)."  
                  				     order by b.id_poli asc";
                       $rs = $dtaccess->Execute($sql);       
                  		 $polibiaya= $dtaccess->Fetch($rs); 
                    }
                    $sql = "select max(reg_no_antrian) as nomore from klinik.klinik_registrasi where reg_tanggal = ".QuoteValue(DPE_DATE,date("Y-m-d"))." and id_poli = ".QuoteValue(DPE_CHAR,$polibiaya["id_poli"])." and id_dep = ".QuoteValue(DPE_CHAR,$depId);                    
                          
                    //echo  $sql; die();
                    
                    }else{
                   $sql = "select max(reg_no_antrian) as nomore from klinik.klinik_registrasi where reg_tanggal = ".QuoteValue(DPE_DATE,date("Y-m-d"))." and id_poli = ".QuoteValue(DPE_CHAR,$_POST["id_poli"])." and id_dep = ".QuoteValue(DPE_CHAR,$depId);
          }
          }
          //$sql = "select max(reg_no_antrian) as nomore from klinik.klinik_registrasi where reg_tipe_layanan='".$_POST["reg_tipe_layanan"]."' and reg_tanggal = ".QuoteValue(DPE_DATE,date("Y-m-d"))." and id_poli = ".QuoteValue(DPE_CHAR,$_POST["id_poli"])." and id_dep = ".QuoteValue(DPE_CHAR,$depId);         
          $noAntrian = $dtaccess->Fetch($sql);
    	    $noantri =  ($noAntrian["nomore"]+1);
//echo $sql."<br>";          
          //kode registrasi
          //ambil kode app
          $sql = "select app_no_reg from global.global_app where app_id='1'";  //kode rawat jalan
          $appNoReg = $dtaccess->Fetch($sql);
    	    $kodeApp =  $appNoReg["app_no_reg"];
          
          if($_POST["jadwal"]){

          $sql ="select b.id_dokter from klinik.klinik_penjadwalan a
                  left join klinik.klinik_registrasi b on a.id_reg =b.reg_id
                  where a.id_cust_usr =".QuoteValue(DPE_CHAR,$_POST["cust_usr_id"])." 
                  and penjadwalan_tanggal= ".QuoteValue(DPE_DATE,$skr);
          $rs = $dtaccess->Execute($sql);       
          $dokterjadwal= $dtaccess->Fetch($rs);         
         // die();
          $_POST["id_poli"]=$polibiaya["id_poli"];
          $_POST["id_dokter"]=$dokterjadwal["id_dokter"];                    
          }
          //ambil kode poli
          $sql = "select poli_kode, id_instalasi, poli_tipe, id_sub_instalasi from global.global_auth_poli where poli_id=".QuoteValue(DPE_CHAR,$_POST["id_poli"]);
          $poliKodeFetch = $dtaccess->Fetch($sql);
    	    $kodePoli =  $poliKodeFetch["poli_kode"];
          $instalasiId =  $poliKodeFetch["id_instalasi"];
          $tipePoli = $poliKodeFetch["poli_tipe"];
          $subInsId = $poliKodeFetch["id_sub_instalasi"];
//          echo $sql."<br>";
          $sql = "select * from global.global_auth_instalasi where instalasi_id=".QuoteValue(DPE_CHAR,$instalasiId);
          $rs = $dtaccess->Execute($sql);
          $dataIns = $dtaccess->Fetch($rs);
          $kodeIns = $dataIns["instalasi_kode"];
  //        echo $sql."<br>";          
          $sql = "select * from global.global_auth_sub_instalasi where sub_instalasi_id=".QuoteValue(DPE_CHAR,$subInsId);
          $rs = $dtaccess->Execute($sql);
          $dataSubIns = $dtaccess->Fetch($rs);
          $kodeSubIns = $dataSubIns["sub_instalasi_kode"];
    //      echo $sql."<br>";
          //ambil kode registrasi
          $sql = "select max(reg_kode_urut) as nomorurut from klinik.klinik_registrasi";
          $noUrut = $dtaccess->Fetch($sql);
    	    $kodeUrutReg =  $noUrut["nomorurut"]+1;
      //    echo $sql."<br>";
          $noantriReg = str_pad($noantri,4,"0",STR_PAD_LEFT);
        //  echo "noantrireg".$noantriReg."<br>";
       //   echo "kode_instal ".$_POST["dep_konf_kode_instalasi"]."<br>";
       //   echo "kode_sub_inst ".$_POST["dep_konf_kode_sub_instalasi"]."<br>";
       //   echo "kode_poli ".$_POST["dep_konf_kode_poli"]."<br>";
       //   echo "kode_urut reg ".$_POST["dep_konf_urut_registrasi"]."<br>";
       //   echo "kode_urut pas ".$_POST["dep_konf_urut_pasien"]."<br>";
       //   echo  "kodeSubIns".$kodeSubIns."<br>";
          if($_POST["dep_konf_kode_sub_instalasi"]=="y"){
            if($kodeSubIns){
              if($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans = $kodeIns.".".$kodeSubIns.".".$kodePoli.".".$kodeUrutReg.".".$noantriReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans = $kodeSubIns.".".$kodePoli.".".$kodeUrutReg.".".$noantriReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans = $kodeIns.".".$kodeSubIns.".".$kodeUrutReg.".".$noantriReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans = $kodeIns.".".$kodeSubIns.".".$kodePoli.".".$noantriReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans = $kodeIns.".".$kodeSubIns.".".$kodePoli.".".$kodeUrutReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans = $kodeSubIns.".".$kodeUrutReg.".".$noantriReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans = $kodeSubIns.".".$kodePoli.".".$noantriReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans = $kodeSubIns.".".$kodePoli.".".$kodeUrutReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans = $kodeSubIns.".".$noantriReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans = $kodeSubIns.".".$kodeUrutReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans = $kodeIns.".".$kodeSubIns.".".$noantriReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans = $kodeIns.".".$kodeSubIns.".".$kodeUrutReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans = $kodeIns.".".$kodeSubIns.".".$kodePoli;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans = $kodeIns.".".$kodeSubIns;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans = $kodeSubIns.".".$kodePoli;
              }
            } else {
              if($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans = $kodeIns.".01.".$kodePoli.".".$kodeUrutReg.".".$noantriReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans = "01.".$kodePoli.".".$kodeUrutReg.".".$noantriReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans = $kodeIns.".01.".$kodeUrutReg.".".$noantriReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans = $kodeIns.".01.".$kodePoli.".".$noantriReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans = $kodeIns.".01.".$kodePoli.".".$kodeUrutReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans = "01.".$kodeUrutReg.".".$noantriReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans = "01.".$kodePoli.".".$noantriReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans = "01.".$kodePoli.".".$kodeUrutReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans = "01.".$noantriReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans = "01.".$kodeUrutReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans = $kodeIns.".01.".$noantriReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans = $kodeIns.".01.".$kodeUrutReg;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans = $kodeIns.".01.".$kodePoli;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans = $kodeIns.".01";
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans = "01.".$kodePoli;
              }
            }
          } else {
          if($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
            $kodeTrans = $kodeIns.".".$kodePoli.".".$kodeUrutReg.".".$noantriReg;
          } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
            $kodeTrans = $kodePoli.".".$kodeUrutReg.".".$noantriReg;
          } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
            $kodeTrans = $kodeIns.".".$kodeUrutReg.".".$noantriReg;
          } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
            $kodeTrans = $kodeIns.".".$kodePoli.".".$noantriReg;
          } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
            $kodeTrans = $kodeIns.".".$kodePoli.".".$kodeUrutReg;
          } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
            $kodeTrans = $kodeUrutReg.".".$noantriReg;
          } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
            $kodeTrans = $kodePoli.".".$noantriReg;
          } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
            $kodeTrans = $kodePoli.".".$kodeUrutReg;
          } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
            $kodeTrans = $noantriReg;
          } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
            $kodeTrans = $kodeUrutReg;
          } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
            $kodeTrans = $kodeIns.".".$noantriReg;
          } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
            $kodeTrans = $kodeIns.".".$kodeUrutReg;
          } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
            $kodeTrans = $kodeIns.".".$kodePoli;
          } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
            $kodeTrans = $kodeIns;
          } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
            $kodeTrans = $kodePoli;
          }
          } 
          
          //echo "kode reg ".$kodeTrans."<br>";
    	    //$kodeTrans = $kodeApp.".".$kodePoli.".".$kodeUrutReg.".".$noantriReg;   
         
          $dbValue[0] = QuoteValue(DPE_CHAR,$regId);
          $dbValue[1] = QuoteValue(DPE_DATE,date("Y-m-d"));
          $dbValue[2] = QuoteValue(DPE_DATE,date("H:i:s"));
          $dbValue[3] = QuoteValue(DPE_CHAR,$userCustId);
          $dbValue[4] = QuoteValue(DPE_CHAR,$status);
          $dbValue[5] = QuoteValue(DPE_CHAR,$userData["name"]);
          $dbValue[6] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
          $dbValue[7] = QuoteValue(DPE_NUMERICKEY,$_POST["cust_usr_jenis"]);
          $dbValue[8] = QuoteValue(DPE_CHAR,$statusPasien);
          $dbValue[9] = QuoteValue(DPE_CHAR,$_POST["id_poli"]);
          $dbValue[10] = QuoteValue(DPE_CHAR,$depId);
          $dbValue[11] = QuoteValue(DPE_NUMERIC,$noantri);
          $dbValue[12] = QuoteValue(DPE_CHAR,'n');
          $dbValue[13] = QuoteValue(DPE_CHAR,$_POST["id_jam"]);
          $dbValue[14] = QuoteValue(DPE_CHAR,$_POST["id_dokter"]);
          $dbValue[15] = QuoteValue(DPE_CHAR,$_POST["id_info"]);
          $dbValue[16] = QuoteValue(DPE_CHAR,$_POST["reg_asal"]);
          $dbValue[17] = QuoteValue(DPE_NUMERIC,$_POST["tahun"]);
          $dbValue[18] = QuoteValue(DPE_NUMERIC,$_POST["hari"]);
          $dbValue[19] = QuoteValue(DPE_CHAR,$_POST["cust_usr_no_jaminan"]);
          $dbValue[20] = QuoteValue(DPE_CHAR,$_POST["id_prog"]);
          $dbValue[21] = QuoteValue(DPE_CHAR,$_POST["reg_rujukan_id"]);
          $dbValue[22] = QuoteValue(DPE_CHAR,$_POST["id_prop"]);
          $dbValue[23] = QuoteValue(DPE_CHAR,$_POST["id_kota"]);
          $dbValue[24] = QuoteValue(DPE_CHAR,$_POST["reg_shift"]);
          $dbValue[25] = QuoteValue(DPE_CHAR,$_POST["reg_tipe_layanan"]);
          $dbValue[26] = QuoteValue(DPE_NUMERIC,$_POST["bulan"]);
          $dbValue[27] = QuoteValue(DPE_CHAR,$kodeTrans); 
          $dbValue[28] = QuoteValue(DPE_NUMERIC,$kodeUrutReg);  
          $dbValue[29] = QuoteValue(DPE_CHAR,$_POST["reg_sebab_sakit"]);
          $dbValue[30] = QuoteValue(DPE_CHAR,$instalasiId);
          $dbValue[31] = QuoteValue(DPE_CHAR,$_POST["reg_kelengkapan_dokumen"]);
          $dbValue[32] = QuoteValue(DPE_CHAR,$_POST["reg_jkn_bersyarat"]);
          $dbValue[33] = QuoteValue(DPE_NUMERIC,'1');                   
          $dbValue[34] = QuoteValue(DPE_CHAR,'J');  
          if($reg["reg_id"]){
            $dbValue[35] = QuoteValue(DPE_CHAR,$reg["reg_id"]);
            $dbValue[36] = QuoteValue(DPE_CHAR,$reg["id_pembayaran"]);
            if($_POST["cust_usr_jenis"]=='18'){ 
            $dbValue[37] = QuoteValue(DPE_CHAR,$_POST["id_jamkesda_kota"]);
            } elseif($_POST["cust_usr_jenis"]=='7') {
            $dbValue[37] = QuoteValue(DPE_CHAR,$_POST["id_corporate"]);
            } elseif($_POST["cust_usr_jenis"]=='5' || $_POST["cust_usr_jenis"]=='26'){                               
            $dbValue[37] = QuoteValue(DPE_CHAR,$_POST["reg_no_sep"]);
            $dbValue[38] = QuoteValue(DPE_CHAR,$_POST["reg_tipe_jkn"]);
            } elseif($_POST["cust_usr_jenis"]=='25'){                               
            $dbValue[37] = QuoteValue(DPE_CHAR,$_POST["fasilitas"]);
            }
          } else {
            if($_POST["cust_usr_jenis"]=='18'){ 
            $dbValue[35] = QuoteValue(DPE_CHAR,$_POST["id_jamkesda_kota"]);
            } elseif($_POST["cust_usr_jenis"]=='7') {
            $dbValue[35] = QuoteValue(DPE_CHAR,$_POST["id_corporate"]);
            } elseif($_POST["cust_usr_jenis"]=='5' || $_POST["cust_usr_jenis"]=='26'){                               
            $dbValue[35] = QuoteValue(DPE_CHAR,$_POST["reg_no_sep"]);
            $dbValue[36] = QuoteValue(DPE_CHAR,$_POST["reg_tipe_jkn"]);
            } elseif($_POST["cust_usr_jenis"]=='25'){                               
            $dbValue[35] = QuoteValue(DPE_CHAR,$_POST["fasilitas"]);
            }
          }
           
        //  print_r($dbValue);
        //  die();
          $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
          $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
          
//          if($dataReg["reg_status"]{0}==STATUS_SELESAI || !$dataReg) { 
               $dtmodel->Insert() or die("insert error"); 
//          }
          //echo $cek_nya."<br />";
          unset($dtmodel);
          unset($dbField);
          unset($dbValue);                          
          unset($dbKey);
          
          $lunas = 'n';
          
          if(!$reg["reg_id"]){
          // Insert Biaya Pembayaran //
              $dbTable = "klinik.klinik_pembayaran";
              $dbField[0] = "pembayaran_id";   // PK
              $dbField[1] = "pembayaran_create";
              $dbField[2] = "pembayaran_who_create";
              $dbField[3] = "pembayaran_tanggal";
              $dbField[4] = "id_reg";
              $dbField[5] = "id_cust_usr";
              $dbField[6] = "pembayaran_total";
              $dbField[7] = "id_dep";
              $dbField[8] = "pembayaran_flag";
              $dbField[9] = "pembayaran_yg_dibayar";
              
               $byrId = $dtaccess->GetTransID();
               $dbValue[0] = QuoteValue(DPE_CHARKEY,$byrId);
               $dbValue[1] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
               $dbValue[2] = QuoteValue(DPE_CHAR,$userName);
               $dbValue[3] = QuoteValue(DPE_DATE,date("Y-m-d"));
               $dbValue[4] = QuoteValue(DPE_CHAR,$regId);
               $dbValue[5] = QuoteValue(DPE_CHAR,$userCustId);
               $dbValue[6] = QuoteValue(DPE_NUMERIC,$beaNominale);
               $dbValue[7] = QuoteValue(DPE_CHAR,$depId);
               $dbValue[8] = QuoteValue(DPE_CHAR,$lunas);
               if($lunas=='y') {
               $dbValue[9] = QuoteValue(DPE_NUMERIC,$beaNominale);
               } else {
               $dbValue[9] = QuoteValue(DPE_NUMERIC,'0.00');
               }
//               print_r($dbValue); echo "pembayaran <br>";
               //die();
               $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
               $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
               
               $dtmodel->Insert() or die("insert  error");
               
               unset($dbField);
               unset($dtmodel);
               unset($dbValue);
               unset($dbKey);
               
               $sql = "update klinik.klinik_registrasi set id_pembayaran = ".
               QuoteValue(DPE_CHAR,$byrId)." where reg_id = ".
               QuoteValue(DPE_CHAR,$regId);
               $rs = $dtaccess->Execute($sql);
            }
            
            if($tipePoli=="L"){
              $dbTable = "laboratorium.lab_pemeriksaan";
               
               $dbField[0] = "pemeriksaan_id";   // PK
               $dbField[1] = "id_reg";
               $dbField[2] = "pemeriksaan_pasien_nama";
               $dbField[3] = "id_dokter";
               $dbField[4] = "pemeriksaan_create";
               $dbField[5] = "pemeriksaan_umur";
               $dbField[6] = "pemeriksaan_alamat";
               $dbField[7] = "pemeriksaan_rawatinap";
               $dbField[8] = "id_cust_usr";
               $dbField[9] = "who_update";
               $dbField[10] = "pemeriksaan_tgl";
			
               $pemeriksaanId = $dtaccess->GetTransID(); 
               $dbValue[0] = QuoteValue(DPE_CHAR,$pemeriksaanId);
               $dbValue[1] = QuoteValue(DPE_CHAR,$regId);
               if($stringss[0] && $stringss[1] && $stringss[2] && $stringss[3]) {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$pasienPetik3);
               } elseif($stringss[0] && $stringss[1] && $stringss[2]) {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$pasienPetik2);
               } elseif($stringss[0] && $stringss[1]) {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$pasienPetik1);
               } elseif($stringss[0] && $namaPasien==$petik) {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$namaPlusPetik);
               } else {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$namaAslie);
               }
               $dbValue[3] = QuoteValue(DPE_CHAR,$_POST["id_dokter"]);
               $dbValue[4] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
               $dbValue[5] = QuoteValue(DPE_CHAR,$_POST["tahun"]."~".$_POST["bulan"]."~".$_POST["hari"]);
               $dbValue[6] = QuoteValue(DPE_CHAR,$_POST["vcust_usr_alamat"]);
               $dbValue[7] = QuoteValue(DPE_CHAR,'n');
               $dbValue[8] = QuoteValue(DPE_CHAR,$userCustId);
               $dbValue[9] = QuoteValue(DPE_CHAR,$userName);
               $dbValue[10] = QuoteValue(DPE_DATE,date("Y-m-d"));
              
               $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
               $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
   
               $dtmodel->Insert() or die("insert  error");
               
               unset($dtmodel);
               unset($dbField);
               unset($dbValue);
               unset($dbKey);
               
               $sql = "update klinik.klinik_registrasi set reg_lab='y' where reg_id=".QuoteValue(DPE_CHAR,$regId);
               $dtaccess->Execute($sql);
            } elseif($tipePoli=="R"){
              $dbTable = "radiologi.radiologi_pemeriksaan";
               
               $dbField[0] = "pemeriksaan_id";   // PK
               $dbField[1] = "id_reg";
               $dbField[2] = "pemeriksaan_pasien_nama";
               $dbField[3] = "id_dokter";
               $dbField[4] = "pemeriksaan_create";
               $dbField[5] = "pemeriksaan_umur";
               $dbField[6] = "pemeriksaan_alamat";
               $dbField[7] = "pemeriksaan_rawatinap";
               $dbField[8] = "id_cust_usr";
               $dbField[9] = "who_update";
               $dbField[10] = "pemeriksaan_tgl";

               $pemeriksaanId = $dtaccess->GetTransID(); 
               $dbValue[0] = QuoteValue(DPE_CHAR,$pemeriksaanId);
               $dbValue[1] = QuoteValue(DPE_CHAR,$regId);
               if($stringss[0] && $stringss[1] && $stringss[2] && $stringss[3]) {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$pasienPetik3);
               } elseif($stringss[0] && $stringss[1] && $stringss[2]) {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$pasienPetik2);
               } elseif($stringss[0] && $stringss[1]) {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$pasienPetik1);
               } elseif($stringss[0] && $namaPasien==$petik) {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$namaPlusPetik);
               } else {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$namaAslie);
               }
               $dbValue[3] = QuoteValue(DPE_CHAR,$_POST["id_dokter"]);
               $dbValue[4] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
               $dbValue[5] = QuoteValue(DPE_CHAR,$_POST["tahun"]."~".$_POST["bulan"]."~".$_POST["hari"]);
               $dbValue[6] = QuoteValue(DPE_CHAR,$_POST["vcust_usr_alamat"]);
               $dbValue[7] = QuoteValue(DPE_CHAR,'n');
               $dbValue[8] = QuoteValue(DPE_CHAR,$userCustId);
               $dbValue[9] = QuoteValue(DPE_CHAR,$userName);
               $dbValue[10] = QuoteValue(DPE_DATE,date("Y-m-d"));

               $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
               $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
   
                    $dtmodel->Insert() or die("insert  error");
               
               unset($dtmodel);
               unset($dbField);
               unset($dbValue);
               unset($dbKey);
               
              $sql = "update klinik.klinik_registrasi set reg_radiologi='y' where reg_id=".QuoteValue(DPE_CHAR,$regId);
              $dtaccess->Execute($sql);
            }
          
            // insert folio (pendaftaran otomatis)
            if($_POST["dep_konf_reg"]=='y'){       
                if($_POST["reguler"] || $_POST["reg_tipe_layanan"]=="1") 
                {
                 $regTipeAntrian = 'R';
                } 
                else if ($_POST["eksekutif"] || $_POST["reg_tipe_layanan"]=="2")
                {
                 $regTipeAntrian = 'E';
                }
                else     //Rehab Medik
                {
                  $regTipeAntrian = 'H';
                }
                $sql = "select a.*,b.*  from  klinik.klinik_biaya_registrasi a left join klinik.klinik_biaya b 
                      on a.id_biaya = b.biaya_id where 
                      a.biaya_registrasi_tipe=".QuoteValue(DPE_CHAR,$regTipeAntrian)." and   
                      a.id_tipe_biaya=".QuoteValue(DPE_CHAR,$_POST["reg_tipe_layanan"])." and 
                      a.id_shift=".QuoteValue(DPE_CHAR,$_POST["reg_shift"])." and 
                      a.id_dep=".QuoteValue(DPE_CHAR,$depId)." and a.id_tahun_tarif=".QuoteValue(DPE_CHAR,$tahunTarif);
                
                if($_POST["dep_konf_reg_poli"]=="y") $sql .= " and a.id_poli=".QuoteValue(DPE_CHAR,$_POST["id_poli"]);
//               echo $sql;
               //die();
               $rs = $dtaccess->Execute($sql);
                $daftar = $dtaccess->Fetch($rs);
               
               // Panggil Persentase Jamkesda
          	   $sqlJamkesda = "	select a.id_jamkesda_kota, b.jamkesda_kota_nama, b.jamkesda_kota_persentase_kota, 
                              b.jamkesda_kota_persentase_prov from klinik.klinik_registrasi a 
          						        left join global.global_jamkesda_kota b on a.id_jamkesda_kota=b.jamkesda_kota_id 
          						        where reg_id = ".QuoteValue(DPE_CHAR,$regId);
      					$dataJamkesda = $dtaccess->Fetch($sqlJamkesda);
      					$jamkesdaNama=$dataJamkesda["jamkesda_kota_nama"];
      					$jamkesdaPesentaseKota=$dataJamkesda["jamkesda_kota_persentase_kota"];
      					$jamkesdaPesentaseProv=$dataJamkesda["jamkesda_kota_persentase_prov"];
                
                $jaminDinkesProv=(StripCurrency($daftar["biaya_total"])*StripCurrency($jamkesdaPesentaseProv)/100);
					      $jaminDinkesKota=(StripCurrency($daftar["biaya_total"])*StripCurrency($jamkesdaPesentaseKota)/100);
					      //$totalJaminan=StripCurrency($jaminDinkesKota)+StripCurrency($jaminDinkesProv);
                //$hrsBayar = StripCurrency($totalTindNom)-StripCurrency($totalJaminan);
  
               //if ($dataBiayaKarcis["biaya_total"]!='0.00') { 
               // $lunas = ($_POST["cust_usr_jenis"]==PASIEN_BAYAR_SWADAYA || $_POST["cust_usr_jenis"]==7)?'n':'y';  
//                  echo  $sqlJamkesda;
             //######tambahan ferina ########//
             //cari data biaya registrasi hari ini
             $sql = "select * from klinik.klinik_folio a
                     left join klinik.klinik_biaya_registrasi b on a.id_biaya = b.id_biaya
                     left join klinik.klinik_registrasi c on a.id_reg = c.reg_id
                      where c.reg_tanggal =".QuoteValue(DPE_DATE,date('Y-m-d'))."
                     and c.id_cust_usr =".QuoteValue(DPE_CHAR,$userCustId);
             $rs = $dtaccess->Execute($sql);
             $dataBiayaRegNow = $dtaccess->Fetch($rs);
          //   echo "Query cari biaya reg hari ini ".$sql."<br>";
           //   die();
             $sql = "select count(reg_id) as totalreg from klinik.klinik_registrasi
                     where reg_tanggal =".QuoteValue(DPE_DATE,date('Y-m-d'))."
                     and id_cust_usr =".QuoteValue(DPE_CHAR,$userCustId);
             $rs = $dtaccess->Execute($sql);
             $dataRegNow = $dtaccess->Fetch($rs);
           //  echo "Query cari reg hari ini ".$sql."<br>";
           //   die();
                     
             if($dataRegNow["totalreg"]=='1'){
             $sql = "select id_poli from klinik.klinik_registrasi
                     where reg_tanggal =".QuoteValue(DPE_DATE,date('Y-m-d'))."
                     and id_cust_usr =".QuoteValue(DPE_CHAR,$userCustId);
             $rs = $dtaccess->Execute($sql);
             $dataPoliRegNow = $dtaccess->Fetch($rs);             
            // echo "Query cari biaya reg hari ini ".$sql."<br>";
             // die();

             } 
                    
            if((!$dataBiayaRegNow && $_POST["id_poli"]<>"suntik") || ($daftar && $dataRegNow["totalreg"]=="1" && $dataPoliRegNow["id_poli"]<>"suntik")) {
               //#### tambahan ferina ####//

               $dbTable = "klinik.klinik_folio";
              $dbField[0] = "fol_id";   // PK
              $dbField[1] = "id_reg";
              $dbField[2] = "fol_nama";
              $dbField[3] = "fol_nominal";
              $dbField[4] = "fol_jenis";
              $dbField[5] = "id_cust_usr";
              $dbField[6] = "fol_waktu";
              $dbField[7] = "fol_lunas";
              $dbField[8] = "id_biaya";
              $dbField[9] = "id_poli";
              $dbField[10] = "fol_jenis_pasien";
              $dbField[11] = "id_dep";
              $dbField[12] = "who_when_update";
              $dbField[13] = "id_dokter";
              $dbField[14] = "fol_total_harga";
              $dbField[15] = "fol_jumlah";
              $dbField[16] = "fol_nominal_satuan"; 
              $dbField[17] = "fol_hrs_bayar";
              $dbField[18] = "fol_dijamin";
              $dbField[19] = "id_pembayaran";
              if($_POST["cust_usr_jenis"]=="18"){
              $dbField[20] = "fol_dijamin1";
              $dbField[21] = "fol_dijamin2";
              }
               
			         $folId = $dtaccess->GetTransID();
               $dbValue[0] = QuoteValue(DPE_CHAR,$folId);
               $dbValue[1] = QuoteValue(DPE_CHAR,$regId);
               $dbValue[2] = QuoteValue(DPE_CHAR,$daftar["biaya_nama"]);
               $dbValue[3] = QuoteValue(DPE_NUMERIC,StripCurrency($daftar["biaya_total"]));
               $dbValue[4] = QuoteValue(DPE_CHAR,$daftar["biaya_jenis"]);
               $dbValue[5] = QuoteValue(DPE_CHAR,$userCustId);
               $dbValue[6] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
               $dbValue[7] = QuoteValue(DPE_CHAR,'n');
               $dbValue[8] = QuoteValue(DPE_CHAR,$daftar["biaya_id"]);
               $dbValue[9] = QuoteValue(DPE_CHAR,$_POST["id_poli"]);
               $dbValue[10] = QuoteValue(DPE_NUMERICKEY,$_POST["cust_usr_jenis"]);
               $dbValue[11] = QuoteValue(DPE_CHAR,$depId);
               $dbValue[12] = QuoteValue(DPE_CHAR,$userId);
               $dbValue[13] = QuoteValue(DPE_CHAR,$_POST["id_dokter"]);
               $dbValue[14] = QuoteValue(DPE_NUMERIC,StripCurrency($daftar["biaya_total"]));
               $dbValue[15] = QuoteValue(DPE_NUMERIC,'1');
               $dbValue[16] = QuoteValue(DPE_NUMERIC,StripCurrency($daftar["biaya_total"]));
               if($_POST["cust_usr_jenis"]=="5" || $_POST["cust_usr_jenis"]=="7" || $_POST["cust_usr_jenis"]=="18" || $_POST["cust_usr_jenis"]=='26'){
               $dbValue[17] = QuoteValue(DPE_NUMERIC,StripCurrency(0));
               } else {
               $dbValue[17] = QuoteValue(DPE_NUMERIC,StripCurrency($daftar["biaya_total"]));
               } 
               if($_POST["cust_usr_jenis"]=="5" || $_POST["cust_usr_jenis"]=="7" || $_POST["cust_usr_jenis"]=="18" || $_POST["cust_usr_jenis"]=='26'){
               $dbValue[18] = QuoteValue(DPE_NUMERIC,StripCurrency($daftar["biaya_total"]));
               } else {
               $dbValue[18] = QuoteValue(DPE_NUMERIC,StripCurrency(0));
               }
               if(!$reg["reg_id"]){
               $dbValue[19] = QuoteValue(DPE_CHAR,$byrId);
               } else {
               $dbValue[19] = QuoteValue(DPE_CHAR,$reg["id_pembayaran"]);
               }
               if($_POST["cust_usr_jenis"]=="18"){
               $dbValue[20] = QuoteValue(DPE_NUMERIC,StripCurrency($jaminDinkesProv));
               $dbValue[21] = QuoteValue(DPE_NUMERIC,StripCurrency($jaminDinkesKota));
               }
               
//               print_r($dbValue); echo "folio <br>";
               $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
               $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
               
               $dtmodel->Insert() or die("insert error"); 

               unset($dtmodel);
               unset($dbField);
               unset($dbValue);                      
               unset($dbKey);

               //masukkan pelaksana
                 //masukkan dokter dahulu
                $dbTable = "klinik.klinik_folio_pelaksana";
    					
    						$dbField[0] = "fol_pelaksana_id";   // PK
    						$dbField[1] = "id_fol";
    						$dbField[2] = "id_usr";
    						$dbField[3] = "fol_pelaksana_tipe";            
    						  							  
    						$dbValue[0] = QuoteValue(DPE_CHAR,$dtaccess->GetTransID());
    						$dbValue[1] = QuoteValue(DPE_CHAR,$folId);
    						if($_POST["id_dokter"]){
                $dbValue[2] = QuoteValue(DPE_CHAR,$_POST["id_dokter"]);
                } else {
    						$dbValue[2] = QuoteValue(DPE_CHAR,$userId);
                }
    						$dbValue[3] = QuoteValue(DPE_CHAR,'1');
    						 
    						$dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    						$dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey,DB_SCHEMA_KLINIK);
    						
    						$dtmodel->Insert() or die("insert error"); 
    						
    						unset($dtmodel);
    						unset($dbField);
    						unset($dbValue);
    						unset($dbKey); 

                 //masukkan pelaksana dahulu
                $dbTable = "klinik.klinik_folio_pelaksana";
    					
    						$dbField[0] = "fol_pelaksana_id";   // PK
    						$dbField[1] = "id_fol";
    						$dbField[2] = "id_usr";
    						$dbField[3] = "fol_pelaksana_tipe";
    						  							  
    						$dbValue[0] = QuoteValue(DPE_CHAR,$dtaccess->GetTransID());
    						$dbValue[1] = QuoteValue(DPE_CHAR,$folId);
    						if($_POST["id_dokter"]){
                $dbValue[2] = QuoteValue(DPE_CHAR,$_POST["id_dokter"]);
                } else {
    						$dbValue[2] = QuoteValue(DPE_CHAR,$userId);
                }
    						$dbValue[3] = QuoteValue(DPE_CHAR,'2');
    						 
    						$dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    						$dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey,DB_SCHEMA_KLINIK);
    						
    						$dtmodel->Insert() or die("insert error"); 
    						
    						unset($dtmodel);
    						unset($dbField);
    						unset($dbValue);
    						unset($dbKey);

          			$sql = "select * from  klinik.klinik_biaya_split where id_biaya = ".QuoteValue(DPE_CHAR,$daftar["biaya_id"])." 
                        and bea_split_nominal > 0";
                $dataSplitKarcis = $dtaccess->FetchAll($sql);
          			
          			for($i=0,$n=count($dataSplitKarcis);$i<$n;$i++) {
          				$dbTable = "klinik.klinik_folio_split";
          			
          				$dbField[0] = "folsplit_id";   // PK
          				$dbField[1] = "id_fol";
          				$dbField[2] = "id_split";
          				// JIKA pasien gratis dan SKTM //
                  if($_POST["cust_usr_jenis"]=='6') {
                  $dbField[3] = "folsplit_nominal";
          				} else {
                  $dbField[3] = "folsplit_nominal";
                  }
                  	  
          				$dbValue[0] = QuoteValue(DPE_CHAR,$dtaccess->GetTransID());
          				$dbValue[1] = QuoteValue(DPE_CHAR,$folId);
          				$dbValue[2] = QuoteValue(DPE_CHAR,$dataSplitKarcis[$i]["id_split"]);
          				// JIKA pasien gratis dan SKTM //
                  if($_POST["cust_usr_jenis"]=='6') {
                  $dbValue[3] = QuoteValue(DPE_NUMERIC,'0.00');
          				} else {
                  $dbValue[3] = QuoteValue(DPE_NUMERIC,$dataSplitKarcis[$i]["bea_split_nominal"]);
                  }
          				$dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
          				$dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
          				
          				$dtmodel->Insert() or die("insert error"); 
          				unset($dtmodel);
          				unset($dbField);
          				unset($dbValue);
          				unset($dbKey);
          			}
             //######tambahan ferina ########//
            }
               //#### tambahan ferina ####//

            }

            $sql_rawat = "select * from klinik.klinik_perawatan 
                     where id_reg = ".QuoteValue(DPE_CHAR,$regId)." 
                     and id_dep =".QuoteValue(DPE_CHAR,$depId);
            $dataPerawat= $dtaccess->Fetch($sql_rawat);
//             echo $sql_rawat;

                        if(!$dataPerawat){
 
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
              $dbField[9] = "rawat_who_update";
              $dbField[10] = "rawat_waktu";         
              
              $_POST["rawat_id"] = $dtaccess->GetTransID();          
              $dbValue[0] = QuoteValue(DPE_CHAR,$_POST["rawat_id"]);   // PK
              $dbValue[1] = QuoteValue(DPE_CHAR,$regId);
              $dbValue[2] = QuoteValue(DPE_CHAR,$userCustId);
              $dbValue[3] = QuoteValue(DPE_CHAR,date("H:i:s"));
              $dbValue[4] = QuoteValue(DPE_DATE,date("Y-m-d"));
              $dbValue[5] = QuoteValue(DPE_CHAR,'M'); 
              $dbValue[6] = QuoteValue(DPE_CHAR,'RAWAT JALAN'); 
              $dbValue[7] = QuoteValue(DPE_CHAR,$_POST["id_poli"]); 
              $dbValue[8] = QuoteValue(DPE_CHAR,$depId);
              $dbValue[9] = QuoteValue(DPE_CHAR,$userData["name"]);
              $dbValue[10] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
//              print_r($dbValue);
              $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
              $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey,DB_SCHEMA_KLINIK);
              $dtmodel->Insert() or die("insert  error");	
          
               unset($dtmodel);
               unset($dbValue);
               unset($dbKey);
          
            }
           if($_POST["jadwal"]){

           $sql = "select b.*,a.id_biaya,a.id_reg from klinik.klinik_penjadwalan a
                     left join klinik.klinik_biaya b on a.id_biaya = b.biaya_id
                     where a.id_cust_usr =".QuoteValue(DPE_CHAR,$_POST["cust_usr_id"])." 
                     and penjadwalan_tanggal= ".QuoteValue(DPE_DATE,$skr)."
                     and b.id_poli <> ".QuoteValue(DPE_CHAR,$_POST["id_poli"])."   
        				     order by b.id_poli asc";
             $rs = $dtaccess->Execute($sql);       
        		 $dataBiayaJadwal= $dtaccess->FetchAll($rs);
        // echo $sql;
       // die();  
         
         $sql = "select reg_id,id_pembayaran from klinik.klinik_registrasi where reg_tanggal= ".QuoteValue(DPE_DATE,$skr)." and (reg_utama='' or reg_utama is null)
                 and id_cust_usr =".QuoteValue(DPE_CHAR,$_POST["cust_usr_id"])."";
         $rs = $dtaccess->Execute($sql);
         $regutamaToday = $dtaccess->Fetch($rs);
         
          for($a=0,$b=count($dataBiayaJadwal);$a<$b;$a++){

           if($dataBiayaJadwal[$a]["id_poli"] <> $dataBiayaJadwal[$a-1]["id_poli"]) { 

           $sql = "select id_dokter from klinik.klinik_registrasi where reg_id = ".QuoteValue(DPE_CHAR,$dataBiayaJadwal[$a]["id_reg"]);
           $rs = $dtaccess->Execute($sql);
           $datadokregsebelum = $dtaccess->Fetch($rs);
           
           $_POST["id_dokter"]= $datadokregsebelum["id_dokter"];
           
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
          $dbField[9] = "id_poli";
          $dbField[10] = "id_dep";
          $dbField[11] = "reg_no_antrian";
          $dbField[12] = "reg_status_cetak_kartu";
          $dbField[13] = "id_jam";
          $dbField[14] = "id_dokter";
          $dbField[15] = "id_info";
          $dbField[16] = "reg_asal";
          $dbField[17] = "reg_umur";
          $dbField[18] = "reg_umur_hari";
          $dbField[19] = "reg_kartu";
          $dbField[20] = "reg_program";
          $dbField[21] = "reg_rujukan_id";         
          $dbField[22] = "id_prop";
          $dbField[23] = "id_kota";
          $dbField[24] = "reg_shift";
          $dbField[25] = "reg_tipe_layanan";
          $dbField[26] = "reg_umur_bulan";
          $dbField[27] = "reg_kode_trans";
          $dbField[28] = "reg_kode_urut";
          $dbField[29] = "reg_sebab_sakit";
          $dbField[30] = "id_instalasi";
          $dbField[31] = "reg_kelengkapan_dokumen";
          $dbField[32] = "reg_jkn_bersyarat";
          $dbField[33] = "reg_urut";     
          $dbField[34] = "reg_tipe_rawat";    
            $dbField[35] = "reg_utama";
            $dbField[36] = "id_pembayaran";
            if($_POST["cust_usr_jenis"]=='18'){
            $dbField[37] = "id_jamkesda_kota";
            } elseif($_POST["cust_usr_jenis"]=='7') { 
            $dbField[37] = "id_perusahaan";
            } elseif($_POST["cust_usr_jenis"]=='5' || $_POST["cust_usr_jenis"]=='26'){
            $dbField[37] = "reg_no_sep";
            $dbField[38] = "reg_tipe_jkn";
            } elseif($_POST["cust_usr_jenis"]=='25'){
            $dbField[37] = "reg_tipe_paket";
            }

          
          if(!$_POST["reg_status_pasien"]) $_POST["reg_status_pasien"] ='L';
          $status2 = 'M0';   //status di UGD
          if($_POST["btnSave"]) $statusPasien2 =$_POST["reg_status_pasien"];
          else $statusPasien2 = 'L';

          $regId2 = $dtaccess->GetTransID();
          
          //kode registrasi
          //ambil kode app
          $sql = "select app_no_reg from global.global_app where app_id='1'";  //kode rawat jalan
          $appNoReg2 = $dtaccess->Fetch($sql);
    	    $kodeApp2 =  $appNoReg2["app_no_reg"];
          
          
          //ambil kode poli
          $sql = "select poli_kode, id_instalasi, poli_tipe, id_sub_instalasi from global.global_auth_poli where poli_id=".QuoteValue(DPE_CHAR,$dataBiayaJadwal[$a]["id_poli"]);
          $poliKodeFetch2 = $dtaccess->Fetch($sql);
    	    $kodePoli2 =  $poliKodeFetch2["poli_kode"];
          $instalasiId2 =  $poliKodeFetch2["id_instalasi"];
          $tipePoli2 = $poliKodeFetch2["poli_tipe"];
          $subInsId2 = $poliKodeFetch2["id_sub_instalasi"];
          
          $sql = "select * from global.global_auth_instalasi where instalasi_id=".QuoteValue(DPE_CHAR,$instalasiId2);
          $rs = $dtaccess->Execute($sql);
          $dataIns2 = $dtaccess->Fetch($rs);
          $kodeIns2 = $dataIns2["instalasi_kode"];
          
          $sql = "select * from global.global_auth_sub_instalasi where sub_instalasi_id=".QuoteValue(DPE_CHAR,$subInsId2);
          $rs = $dtaccess->Execute($sql);
          $dataSubIns2 = $dtaccess->Fetch($rs);
          $kodeSubIns2 = $dataSubIns2["sub_instalasi_kode"];

          //ambil kode registrasi
          $sql = "select max(reg_kode_urut) as nomorurut from klinik.klinik_registrasi";
          $noUrut2 = $dtaccess->Fetch($sql);
    	    $kodeUrutReg2 =  $noUrut2["nomorurut"]+1;
           
          $sql = "select max(reg_no_antrian) as nomore from klinik.klinik_registrasi where reg_tanggal = ".QuoteValue(DPE_DATE,date("Y-m-d"))." 
                  and id_poli = ".QuoteValue(DPE_CHAR,$dataBiayaJadwal[$a]["id_poli"])." and id_dep = ".QuoteValue(DPE_CHAR,$depId);
          $noUrut2 = $dtaccess->Fetch($sql);
    	    $noantri2 =  $noUrut2["nomore"]+1;
          $noantriReg2 = str_pad($noantri2,4,"0",STR_PAD_LEFT);
          
          if($_POST["dep_konf_kode_sub_instalasi"]=="y"){
            if($kodeSubIns2){
              if($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans2 = $kodeIns2.".".$kodeSubIns2.".".$kodePoli2.".".$kodeUrutReg2.".".$noantriReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans2 = $kodeSubIns2.".".$kodePoli2.".".$kodeUrutReg2.".".$noantriReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans2 = $kodeIns2.".".$kodeSubIns2.".".$kodeUrutReg2.".".$noantriReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans2 = $kodeIns2.".".$kodeSubIns2.".".$kodePoli2.".".$noantriReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans2 = $kodeIns2.".".$kodeSubIns2.".".$kodePoli2.".".$kodeUrutReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans2 = $kodeSubIns2.".".$kodeUrutReg2.".".$noantriReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans2 = $kodeSubIns2.".".$kodePoli2.".".$noantriReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans2 = $kodeSubIns2.".".$kodePoli2.".".$kodeUrutReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans2 = $kodeSubIns2.".".$noantriReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans2 = $kodeSubIns2.".".$kodeUrutReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans2 = $kodeIns2.".".$kodeSubIns2.".".$noantriReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans2 = $kodeIns2.".".$kodeSubIns2.".".$kodeUrutReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans2 = $kodeIns2.".".$kodeSubIns2.".".$kodePoli2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans2 = $kodeIns2.".".$kodeSubIns2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans2 = $kodeSubIns2.".".$kodePoli2;
              }
            } else {
              if($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans2 = $kodeIns2.".01.".$kodePoli2.".".$kodeUrutReg2.".".$noantriReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans2 = "01.".$kodePoli2.".".$kodeUrutReg2.".".$noantriReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans2 = $kodeIns2.".01.".$kodeUrutReg2.".".$noantriReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans2 = $kodeIns2.".01.".$kodePoli2.".".$noantriReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans2 = $kodeIns2.".01.".$kodePoli2.".".$kodeUrutReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans2 = "01.".$kodeUrutReg2.".".$noantriReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans2 = "01.".$kodePoli2.".".$noantriReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans2 = "01.".$kodePoli2.".".$kodeUrutReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans2 = "01.".$noantriReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans2 = "01.".$kodeUrutReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
                $kodeTrans2 = $kodeIns2.".01.".$noantriReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans2 = $kodeIns2.".01.".$kodeUrutReg2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans2 = $kodeIns2.".01.".$kodePoli2;
              } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans2 = $kodeIns2.".01";
              } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
                $kodeTrans2 = "01.".$kodePoli2;
              }
            }
          } else {
          if($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
            $kodeTrans2 = $kodeIns2.".".$kodePoli2.".".$kodeUrutReg2.".".$noantriReg2;
          } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
            $kodeTrans2 = $kodePoli2.".".$kodeUrutReg2.".".$noantriReg2;
          } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
            $kodeTrans2 = $kodeIns2.".".$kodeUrutReg2.".".$noantriReg2;
          } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
            $kodeTrans2 = $kodeIns2.".".$kodePoli2.".".$noantriReg2;
          } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
            $kodeTrans2 = $kodeIns2.".".$kodePoli2.".".$kodeUrutReg2;
          } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="y"){
            $kodeTrans2 = $kodeUrutReg2.".".$noantriReg2;
          } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
            $kodeTrans2 = $kodePoli2.".".$noantriReg2;
          } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
            $kodeTrans2 = $kodePoli2.".".$kodeUrutReg2;
          } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
            $kodeTrans2 = $noantriReg2;
          } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
            $kodeTrans2 = $kodeUrutReg2;
          } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="y"){
            $kodeTrans2 = $kodeIns2.".".$noantriReg2;
          } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="y" && $_POST["dep_konf_urut_pasien"]=="n"){
            $kodeTrans2 = $kodeIns2.".".$kodeUrutReg2;
          } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
            $kodeTrans2 = $kodeIns2.".".$kodePoli2;
          } elseif($_POST["dep_konf_kode_instalasi"]=="y" && $_POST["dep_konf_kode_poli"]=="n" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
            $kodeTrans2 = $kodeIns2;
          } elseif($_POST["dep_konf_kode_instalasi"]=="n" && $_POST["dep_konf_kode_poli"]=="y" && $_POST["dep_konf_urut_registrasi"]=="n" && $_POST["dep_konf_urut_pasien"]=="n"){
            $kodeTrans2 = $kodePoli2;
          }
          } 
          
    	    //$kodeTrans = $kodeApp.".".$kodePoli.".".$kodeUrutReg.".".$noantriReg;   
         
          $dbValue[0] = QuoteValue(DPE_CHAR,$regId2);
          $dbValue[1] = QuoteValue(DPE_DATE,date("Y-m-d"));
          $dbValue[2] = QuoteValue(DPE_DATE,date("H:i:s"));
          $dbValue[3] = QuoteValue(DPE_CHAR,$userCustId);
          $dbValue[4] = QuoteValue(DPE_CHAR,$status2);
          $dbValue[5] = QuoteValue(DPE_CHAR,$userData["name"]);
          $dbValue[6] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
          $dbValue[7] = QuoteValue(DPE_NUMERICKEY,$_POST["cust_usr_jenis"]);
          $dbValue[8] = QuoteValue(DPE_CHAR,$statusPasien2);
          $dbValue[9] = QuoteValue(DPE_CHAR,$dataBiayaJadwal[$a]["id_poli"]);
          $dbValue[10] = QuoteValue(DPE_CHAR,$depId);
          $dbValue[11] = QuoteValue(DPE_NUMERIC,$noantri2);
          $dbValue[12] = QuoteValue(DPE_CHAR,'n');
          $dbValue[13] = QuoteValue(DPE_CHAR,$_POST["id_jam"]);
          $dbValue[14] = QuoteValue(DPE_CHAR,$_POST["id_dokter"]);
          $dbValue[15] = QuoteValue(DPE_CHAR,$_POST["id_info"]);
          $dbValue[16] = QuoteValue(DPE_CHAR,$_POST["reg_asal"]);
          $dbValue[17] = QuoteValue(DPE_NUMERIC,$_POST["tahun"]);
          $dbValue[18] = QuoteValue(DPE_NUMERIC,$_POST["hari"]);
          $dbValue[19] = QuoteValue(DPE_CHAR,$_POST["cust_usr_no_jaminan"]);
          $dbValue[20] = QuoteValue(DPE_CHAR,$_POST["id_prog"]);
          $dbValue[21] = QuoteValue(DPE_CHAR,$_POST["reg_rujukan_id"]);
          $dbValue[22] = QuoteValue(DPE_CHAR,$_POST["id_prop"]);
          $dbValue[23] = QuoteValue(DPE_CHAR,$_POST["id_kota"]);
          $dbValue[24] = QuoteValue(DPE_CHAR,$_POST["reg_shift"]);
          $dbValue[25] = QuoteValue(DPE_CHAR,$_POST["reg_tipe_layanan"]);
          $dbValue[26] = QuoteValue(DPE_NUMERIC,$_POST["bulan"]);
          $dbValue[27] = QuoteValue(DPE_CHAR,$kodeTrans2); 
          $dbValue[28] = QuoteValue(DPE_NUMERIC,$kodeUrutReg2);  
          $dbValue[29] = QuoteValue(DPE_CHAR,$_POST["reg_sebab_sakit"]);
          $dbValue[30] = QuoteValue(DPE_CHAR,$instalasiId2);
          $dbValue[31] = QuoteValue(DPE_CHAR,$_POST["reg_kelengkapan_dokumen"]);
          $dbValue[32] = QuoteValue(DPE_CHAR,$_POST["reg_jkn_bersyarat"]);
          $dbValue[33] = QuoteValue(DPE_NUMERIC,'1');                   
          $dbValue[34] = QuoteValue(DPE_CHAR,'J');  
            $dbValue[35] = QuoteValue(DPE_CHAR,$regutamaToday["reg_id"]);
            $dbValue[36] = QuoteValue(DPE_CHAR,$regutamaToday["id_pembayaran"]);
            if($_POST["cust_usr_jenis"]=='18'){ 
            $dbValue[37] = QuoteValue(DPE_CHAR,$_POST["id_jamkesda_kota"]);
            } elseif($_POST["cust_usr_jenis"]=='7') {
            $dbValue[37] = QuoteValue(DPE_CHAR,$_POST["id_corporate"]);
            } elseif($_POST["cust_usr_jenis"]=='5' || $_POST["cust_usr_jenis"]=='26'){                               
            $dbValue[37] = QuoteValue(DPE_CHAR,$_POST["reg_no_sep"]);
            $dbValue[38] = QuoteValue(DPE_CHAR,$_POST["reg_tipe_jkn"]);
            } elseif($_POST["cust_usr_jenis"]=='25'){                               
            $dbValue[37] = QuoteValue(DPE_CHAR,$_POST["fasilitas"]);
            }

           
//          print_r($dbValue); echo "registrasi <br>";
       //   die();
          $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
          $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
          
//          if($dataReg["reg_status"]{0}==STATUS_SELESAI || !$dataReg) { 
               $dtmodel->Insert() or die("insert error"); 
//          }
          //echo $cek_nya."<br />";
          unset($dtmodel);
          unset($dbField);
          unset($dbValue);                          
          unset($dbKey);          

 if($tipePoli2=="L"){
              $dbTable = "laboratorium.lab_pemeriksaan";
               
               $dbField[0] = "pemeriksaan_id";   // PK
               $dbField[1] = "id_reg";
               $dbField[2] = "pemeriksaan_pasien_nama";
               $dbField[3] = "id_dokter";
               $dbField[4] = "pemeriksaan_create";
               $dbField[5] = "pemeriksaan_umur";
               $dbField[6] = "pemeriksaan_alamat";
               $dbField[7] = "pemeriksaan_rawatinap";
               $dbField[8] = "id_cust_usr";
               $dbField[9] = "who_update";
               $dbField[10] = "pemeriksaan_tgl";
			
               $pemeriksaanId = $dtaccess->GetTransID(); 
               $dbValue[0] = QuoteValue(DPE_CHAR,$pemeriksaanId);
               $dbValue[1] = QuoteValue(DPE_CHAR,$regId2);
               if($stringss[0] && $stringss[1] && $stringss[2] && $stringss[3]) {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$pasienPetik3);
               } elseif($stringss[0] && $stringss[1] && $stringss[2]) {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$pasienPetik2);
               } elseif($stringss[0] && $stringss[1]) {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$pasienPetik1);
               } elseif($stringss[0] && $namaPasien==$petik) {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$namaPlusPetik);
               } else {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$namaAslie);
               }
               $dbValue[3] = QuoteValue(DPE_CHAR,$_POST["id_dokter"]);
               $dbValue[4] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
               $dbValue[5] = QuoteValue(DPE_CHAR,$_POST["tahun"]."~".$_POST["bulan"]."~".$_POST["hari"]);
               $dbValue[6] = QuoteValue(DPE_CHAR,$_POST["vcust_usr_alamat"]);
               $dbValue[7] = QuoteValue(DPE_CHAR,'n');
               $dbValue[8] = QuoteValue(DPE_CHAR,$userCustId);
               $dbValue[9] = QuoteValue(DPE_CHAR,$userName);
               $dbValue[10] = QuoteValue(DPE_DATE,date("Y-m-d"));
              
               $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
               $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
   
               $dtmodel->Insert() or die("insert  error");
               
               unset($dtmodel);
               unset($dbField);
               unset($dbValue);
               unset($dbKey);
               
               $sql = "update klinik.klinik_registrasi set reg_lab='y' where reg_id=".QuoteValue(DPE_CHAR,$regId2);
               $dtaccess->Execute($sql);
            } elseif($tipePoli2=="R"){
              $dbTable = "radiologi.radiologi_pemeriksaan";
               
               $dbField[0] = "pemeriksaan_id";   // PK
               $dbField[1] = "id_reg";
               $dbField[2] = "pemeriksaan_pasien_nama";
               $dbField[3] = "id_dokter";
               $dbField[4] = "pemeriksaan_create";
               $dbField[5] = "pemeriksaan_umur";
               $dbField[6] = "pemeriksaan_alamat";
               $dbField[7] = "pemeriksaan_rawatinap";
               $dbField[8] = "id_cust_usr";
               $dbField[9] = "who_update";
               $dbField[10] = "pemeriksaan_tgl";

               $pemeriksaanId = $dtaccess->GetTransID(); 
               $dbValue[0] = QuoteValue(DPE_CHAR,$pemeriksaanId);
               $dbValue[1] = QuoteValue(DPE_CHAR,$regId2);
               if($stringss[0] && $stringss[1] && $stringss[2] && $stringss[3]) {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$pasienPetik3);
               } elseif($stringss[0] && $stringss[1] && $stringss[2]) {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$pasienPetik2);
               } elseif($stringss[0] && $stringss[1]) {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$pasienPetik1);
               } elseif($stringss[0] && $namaPasien==$petik) {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$namaPlusPetik);
               } else {
                 $dbValue[2] = QuoteValue(DPE_CHAR,$namaAslie);
               }
               $dbValue[3] = QuoteValue(DPE_CHAR,$_POST["id_dokter"]);
               $dbValue[4] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
               $dbValue[5] = QuoteValue(DPE_CHAR,$_POST["tahun"]."~".$_POST["bulan"]."~".$_POST["hari"]);
               $dbValue[6] = QuoteValue(DPE_CHAR,$_POST["vcust_usr_alamat"]);
               $dbValue[7] = QuoteValue(DPE_CHAR,'n');
               $dbValue[8] = QuoteValue(DPE_CHAR,$userCustId);
               $dbValue[9] = QuoteValue(DPE_CHAR,$userName);
               $dbValue[10] = QuoteValue(DPE_DATE,date("Y-m-d"));

               $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
               $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
   
                    $dtmodel->Insert() or die("insert  error");
               
               unset($dtmodel);
               unset($dbField);
               unset($dbValue);
               unset($dbKey);
               
              $sql = "update klinik.klinik_registrasi set reg_radiologi='y' where reg_id=".QuoteValue(DPE_CHAR,$regId2);
              $dtaccess->Execute($sql);

            }          
          } //  die();
          }
          
              //cari reg_id berdasarkan polinya
              $sql = "select a.penjadwalan_id,b.*,c.*,d.* from klinik.klinik_penjadwalan a
                     left join klinik.klinik_biaya b on a.id_biaya = b.biaya_id
                     left join global.global_customer_user c on a.id_cust_usr = c.cust_usr_id
                     left join global.global_auth_poli d on b.id_poli = d.poli_id
                     where a.id_cust_usr =".QuoteValue(DPE_CHAR,$_POST["cust_usr_id"])." 
                     and penjadwalan_tanggal= ".QuoteValue(DPE_DATE,$skr)." 
                     and (a.id_biaya <> 'injp' and a.id_biaya <> 'injl')   
        				     order by b.id_poli asc";
             $rs = $dtaccess->Execute($sql);       
        		 $dataBiayaJdwl= $dtaccess->FetchAll($rs);
             
              for($c=0,$d=count($dataBiayaJdwl);$c<$d;$c++){
              
                  $sql = "select reg_id, id_pembayaran,id_dokter from klinik.klinik_registrasi where id_cust_usr=".QuoteValue(DPE_CHAR,$userCustId)." 
                          and reg_shift=".QuoteValue(DPE_CHAR,$_POST["reg_shift"])." and id_poli = ".QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["id_poli"])."
                          and reg_tanggal=".QuoteValue(DPE_DATE,date("Y-m-d"))." and reg_jenis_pasien=".QuoteValue(DPE_NUMERIC,$_POST["cust_usr_jenis"]);
                    $rs = $dtaccess->Execute($sql);
                    $regToday = $dtaccess->Fetch($rs);              
              
              $dbTable = "klinik.klinik_folio";
              
              $dbField[0] = "fol_id";   // PK
              $dbField[1] = "id_reg";
              $dbField[2] = "fol_nama";
              $dbField[3] = "fol_nominal";
              $dbField[4] = "fol_jenis";
              $dbField[5] = "id_cust_usr";
              $dbField[6] = "fol_waktu";
              $dbField[7] = "fol_lunas";
              $dbField[8] = "id_biaya";
              $dbField[9] = "id_poli";
              $dbField[10] = "fol_jenis_pasien";
              $dbField[11] = "id_dep";
              $dbField[12] = "who_when_update";
              $dbField[13] = "id_dokter";
              $dbField[14] = "fol_total_harga";
              $dbField[15] = "fol_jumlah";
              $dbField[16] = "fol_nominal_satuan"; 
              $dbField[17] = "fol_hrs_bayar";
              $dbField[18] = "fol_dijamin";
              $dbField[19] = "id_pembayaran";
              if($_POST["cust_usr_jenis"]=="18"){
              $dbField[20] = "fol_dijamin1";
              $dbField[21] = "fol_dijamin2";
              }
               
			         $folId2 = $dtaccess->GetTransID();
               $dbValue[0] = QuoteValue(DPE_CHAR,$folId2);
               $dbValue[1] = QuoteValue(DPE_CHAR,$regToday["reg_id"]);
               $dbValue[2] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["biaya_nama"]);
               $dbValue[3] = QuoteValue(DPE_NUMERIC,StripCurrency($dataBiayaJdwl[$c]["biaya_total"]));
               $dbValue[4] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["biaya_jenis"]);
               $dbValue[5] = QuoteValue(DPE_CHAR,$userCustId);
               $dbValue[6] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
               $dbValue[7] = QuoteValue(DPE_CHAR,'n');
               $dbValue[8] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["biaya_id"]);
               $dbValue[9] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["id_poli"]);
               $dbValue[10] = QuoteValue(DPE_NUMERICKEY,$_POST["cust_usr_jenis"]);
               $dbValue[11] = QuoteValue(DPE_CHAR,$depId);
               $dbValue[12] = QuoteValue(DPE_CHAR,$userId);
               $dbValue[13] = QuoteValue(DPE_CHAR,$regToday["id_dokter"]);
               $dbValue[14] = QuoteValue(DPE_NUMERIC,StripCurrency($dataBiayaJdwl[$c]["biaya_total"]));
               $dbValue[15] = QuoteValue(DPE_NUMERIC,'1');
               $dbValue[16] = QuoteValue(DPE_NUMERIC,StripCurrency($dataBiayaJdwl[$c]["biaya_total"]));
               if($_POST["cust_usr_jenis"]=="5" || $_POST["cust_usr_jenis"]=="7" || $_POST["cust_usr_jenis"]=="18" || $_POST["cust_usr_jenis"]=='26'){
               $dbValue[17] = QuoteValue(DPE_NUMERIC,StripCurrency(0));
               } else {
               $dbValue[17] = QuoteValue(DPE_NUMERIC,StripCurrency($dataBiayaJdwl[$c]["biaya_total"]));
               } 
               if($_POST["cust_usr_jenis"]=="5" || $_POST["cust_usr_jenis"]=="7" || $_POST["cust_usr_jenis"]=="18" || $_POST["cust_usr_jenis"]=='26'){
               $dbValue[18] = QuoteValue(DPE_NUMERIC,StripCurrency($dataBiayaJdwl[$c]["biaya_total"]));
               } else {
               $dbValue[18] = QuoteValue(DPE_NUMERIC,StripCurrency(0));
               }
               $dbValue[19] = QuoteValue(DPE_CHAR,$regToday["id_pembayaran"]);
               if($_POST["cust_usr_jenis"]=="18"){
               $dbValue[20] = QuoteValue(DPE_NUMERIC,StripCurrency($jaminDinkesProv));
               $dbValue[21] = QuoteValue(DPE_NUMERIC,StripCurrency($jaminDinkesKota));
               }
               
//               print_r($dbValue); echo "folio <br>";
               $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
               $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
               
               $dtmodel->Insert() or die("insert error"); 

               unset($dtmodel);
               unset($dbField);
               unset($dbValue);                      
               unset($dbKey);

               //masukkan pelaksana
                 //masukkan dokter dahulu
                $dbTable = "klinik.klinik_folio_pelaksana";
    					
    						$dbField[0] = "fol_pelaksana_id";   // PK
    						$dbField[1] = "id_fol";
    						$dbField[2] = "id_usr";
    						$dbField[3] = "fol_pelaksana_tipe";            
    						  							  
    						$dbValue[0] = QuoteValue(DPE_CHAR,$dtaccess->GetTransID());
    						$dbValue[1] = QuoteValue(DPE_CHAR,$folId2);
    						if($regToday["id_dokter"]){
                $dbValue[2] = QuoteValue(DPE_CHAR,$regToday["id_dokter"]);
                } else {
    						$dbValue[2] = QuoteValue(DPE_CHAR,$userId);
                }
    						$dbValue[3] = QuoteValue(DPE_CHAR,'1');
    						 
    						$dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    						$dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey,DB_SCHEMA_KLINIK);
    						
    						$dtmodel->Insert() or die("insert error"); 
    						
    						unset($dtmodel);
    						unset($dbField);
    						unset($dbValue);
    						unset($dbKey); 

                 //masukkan pelaksana dahulu
                $dbTable = "klinik.klinik_folio_pelaksana";
    					
    						$dbField[0] = "fol_pelaksana_id";   // PK
    						$dbField[1] = "id_fol";
    						$dbField[2] = "id_usr";
    						$dbField[3] = "fol_pelaksana_tipe";
    						  							  
    						$dbValue[0] = QuoteValue(DPE_CHAR,$dtaccess->GetTransID());
    						$dbValue[1] = QuoteValue(DPE_CHAR,$folId2);
    						if($regToday["id_dokter"]){
                $dbValue[2] = QuoteValue(DPE_CHAR,$regToday["id_dokter"]);
                } else {
    						$dbValue[2] = QuoteValue(DPE_CHAR,$userId);
                }
    						$dbValue[3] = QuoteValue(DPE_CHAR,'2');
    						 
    						$dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    						$dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey,DB_SCHEMA_KLINIK);
    						
    						$dtmodel->Insert() or die("insert error"); 
    						
    						unset($dtmodel);
    						unset($dbField);
    						unset($dbValue);
    						unset($dbKey);

          			$sql = "select * from  klinik.klinik_biaya_split where id_biaya = ".QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["biaya_id"])." 
                        and bea_split_nominal > 0";
                $dataSplitJadwal = $dtaccess->FetchAll($sql);
          			
          			for($i=0,$n=count($dataSplitJadwal);$i<$n;$i++) {
          				$dbTable = "klinik.klinik_folio_split";
          			
          				$dbField[0] = "folsplit_id";   // PK
          				$dbField[1] = "id_fol";
          				$dbField[2] = "id_split";
          				// JIKA pasien gratis dan SKTM //
                  if($_POST["cust_usr_jenis"]=='6') {
                  $dbField[3] = "folsplit_nominal";
          				} else {
                  $dbField[3] = "folsplit_nominal";
                  }
                  	  
          				$dbValue[0] = QuoteValue(DPE_CHAR,$dtaccess->GetTransID());
          				$dbValue[1] = QuoteValue(DPE_CHAR,$folId2);
          				$dbValue[2] = QuoteValue(DPE_CHAR,$dataSplitJadwal[$i]["id_split"]);
          				// JIKA pasien gratis dan SKTM //
                  if($_POST["cust_usr_jenis"]=='6') {
                  $dbValue[3] = QuoteValue(DPE_NUMERIC,'0.00');
          				} else {
                  $dbValue[3] = QuoteValue(DPE_NUMERIC,$dataSplitJadwal[$i]["bea_split_nominal"]);
                  }
          				$dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
          				$dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
          				
          				$dtmodel->Insert() or die("insert error"); 
          				unset($dtmodel);
          				unset($dbField);
          				unset($dbValue);
          				unset($dbKey);
          			}
          
          if($dataBiayaJdwl[$c]["poli_tipe"]=='L'){
          //cari idpoli labnya
          $sql = "select * from laboratorium.lab_pemeriksaan where id_reg = ".QuoteValue(DPE_CHAR,$regToday["reg_id"]);
          $rs = $dtaccess->Execute($sql);
          $labToday = $dtaccess->Fetch($rs);
          
           if(!$labToday){
           
           $dbTable = "laboratorium.lab_pemeriksaan";
               
               $dbField[0] = "pemeriksaan_id";   // PK
               $dbField[1] = "id_reg";
               $dbField[2] = "pemeriksaan_pasien_nama";
               $dbField[3] = "id_dokter";
               $dbField[4] = "pemeriksaan_create";
               $dbField[5] = "pemeriksaan_umur";
               $dbField[6] = "pemeriksaan_alamat";
               $dbField[7] = "pemeriksaan_rawatinap";
               $dbField[8] = "id_cust_usr";
               $dbField[9] = "who_update";
               $dbField[10] = "pemeriksaan_tgl";
			
               $pemeriksaanId2 = $dtaccess->GetTransID(); 
               $dbValue[0] = QuoteValue(DPE_CHAR,$pemeriksaanId2);
               $dbValue[1] = QuoteValue(DPE_CHAR,$regId2);
               $dbValue[2] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["cust_usr_nama"]);
               $dbValue[3] = QuoteValue(DPE_CHAR,$regToday["id_dokter"]);
               $dbValue[4] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
               $dbValue[5] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["cust_usr_umur"]);
               $dbValue[6] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["cust_usr_alamat"]);
               $dbValue[7] = QuoteValue(DPE_CHAR,'n');
               $dbValue[8] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["cust_usr_id"]);
               $dbValue[9] = QuoteValue(DPE_CHAR,$userName);
               $dbValue[10] = QuoteValue(DPE_DATE,date("Y-m-d"));
              
               $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
               $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
   
               $dtmodel->Insert() or die("insert  error");
               
               unset($dtmodel);
               unset($dbField);
               unset($dbValue);
               unset($dbKey);
           
           }else{
            $pemeriksaanId2 = $labToday["pemeriksaan_id"];
           }
           
              $dbTable = "laboratorium.lab_pemeriksaan_detail";
               
               $dbField[0] = "periksa_det_id";   // PK
               $dbField[1] = "id_pemeriksaan";     
               $dbField[2] = "periksa_det_total";
               $dbField[3] = "who_update"; 
               $dbField[4] = "id_cust_usr";
               $dbField[5] = "nama_pemeriksaan";
               $dbField[6] = "id_biaya";
               $dbField[7] = "when_create";
               $dbField[8] = "detail_kode";
               $dbField[9] = "pemeriksaan_nilai_normal";

			
               $pemeriksaandetId = $dtaccess->GetTransID();   
               $dbValue[0] = QuoteValue(DPE_CHAR,$pemeriksaandetId);
               $dbValue[1] = QuoteValue(DPE_CHAR,$pemeriksaanId2);
               $dbValue[2] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["biaya_total"]);
               $dbValue[3] = QuoteValue(DPE_CHAR,$userName);
               $dbValue[4] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["cust_usr_id"]);
               $dbValue[5] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["biaya_nama"]);
               $dbValue[6] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["biaya_id"]);
               $dbValue[7] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
               $dbValue[8] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["biaya_kode"]);
               $dbValue[9] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["biaya_keterangan"]);

              
               $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
               $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
   
               $dtmodel->Insert() or die("insert  error");	
               
               unset($dtmodel);
               unset($dbField);
               unset($dbValue);
               unset($dbKey);
               
                $sql = "select * from klinik.klinik_biaya where biaya_kode like  '".$dataBiayaJdwl[$c]["biaya_kode"]."%' and biaya_id<>".QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["biaya_id"]);
                $dataAnak= $dtaccess->FetchAll($sql);
                
                for($i=0,$n=count($dataAnak);$i<$n;$i++) { 
                $dbTable = "laboratorium.lab_pemeriksaan_detail";
               
               $dbField[0] = "periksa_det_id";   // PK
               $dbField[1] = "id_pemeriksaan";     
               $dbField[2] = "who_update"; 
               $dbField[3] = "id_cust_usr";
               $dbField[4] = "nama_pemeriksaan";
               $dbField[5] = "id_biaya";
               $dbField[6] = "when_create";
               $dbField[7] = "detail_kode"; 
               if($dataAnak[$i]["biaya_keterangan"]){
               $dbField[8] = "pemeriksaan_nilai_normal";
               }   

               $pemeriksaandetAnakId = $dtaccess->GetTransID();   
               $dbValue[0] = QuoteValue(DPE_CHAR,$pemeriksaandetAnakId);
               $dbValue[1] = QuoteValue(DPE_CHAR,$pemeriksaanId2);
               $dbValue[2] = QuoteValue(DPE_CHAR,$userName);
               $dbValue[3] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["cust_usr_id"]);
               $dbValue[4] = QuoteValue(DPE_CHAR,$dataAnak[$i]["biaya_nama"]);
               $dbValue[5] = QuoteValue(DPE_CHAR,$dataAnak[$i]["biaya_id"]);
               $dbValue[6] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
               $dbValue[7] = QuoteValue(DPE_CHAR,$dataAnak[$i]["biaya_kode"]);   
               if($dataAnak[$i]["biaya_keterangan"]){
               $dbValue[8] = QuoteValue(DPE_CHAR,$dataAnak[$i]["biaya_keterangan"]); 
               } 
               $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
               $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
   
               $dtmodel->Insert() or die("insert  error");	
               
               unset($dtmodel);
               unset($dbField);
               unset($dbValue);
               unset($dbKey);
               
               }
          
          }
          
          if($dataBiayaJdwl[$c]["poli_tipe"]=='R'){
          //cari idpoli labnya
          $sql = "select * from radiologi.radiologi_pemeriksaan where id_reg = ".QuoteValue(DPE_CHAR,$regToday["reg_id"]);
          $rs = $dtaccess->Execute($sql);
          $radToday = $dtaccess->Fetch($rs);
          
          if(!$radToday){
              $dbTable = "radiologi.radiologi_pemeriksaan";
               
               $dbField[0] = "pemeriksaan_id";   // PK
               $dbField[1] = "id_reg";
               $dbField[2] = "pemeriksaan_pasien_nama";
               $dbField[3] = "id_dokter";
               $dbField[4] = "pemeriksaan_create";
               $dbField[5] = "pemeriksaan_umur";
               $dbField[6] = "pemeriksaan_alamat";
               $dbField[7] = "pemeriksaan_rawatinap";
               $dbField[8] = "id_cust_usr";
               $dbField[9] = "who_update";
               $dbField[10] = "pemeriksaan_tgl";

               $pemeriksaanId3 = $dtaccess->GetTransID(); 
               $dbValue[0] = QuoteValue(DPE_CHAR,$pemeriksaanId3);
               $dbValue[1] = QuoteValue(DPE_CHAR,$regToday["reg_id"]);
               $dbValue[2] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["cust_usr_nama"]);
               $dbValue[3] = QuoteValue(DPE_CHAR,$regToday["id_dokter"]);
               $dbValue[4] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
               $dbValue[5] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["cust_usr_umur"]);
               $dbValue[6] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["cust_usr_alamat"]);
               $dbValue[7] = QuoteValue(DPE_CHAR,'n');
               $dbValue[8] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["cust_usr_id"]);
               $dbValue[9] = QuoteValue(DPE_CHAR,$userName);
               $dbValue[10] = QuoteValue(DPE_DATE,date("Y-m-d"));

               $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
               $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
   
                    $dtmodel->Insert() or die("insert  error");
               
               unset($dtmodel);
               unset($dbField);
               unset($dbValue);
               unset($dbKey);          
          }else{
           $pemeriksaanId3 = $radToday["pemeriksaan_id"];
          }
          
           $dbTable = "radiologi.radiologi_resume";
               
               $dbField[0] = "resume_id";   // PK
               $dbField[1] = "id_pemeriksaan";     
               $dbField[2] = "periksa_res_total";
               $dbField[3] = "who_update"; 
               $dbField[4] = "id_cust_usr";
               $dbField[5] = "nama_pemeriksaan";
               $dbField[6] = "id_biaya";
               $dbField[7] = "when_create";
               $dbField[8] = "id_reg";
               $dbField[9] = "resume_tanggal";
               


               $resumeId = $dtaccess->GetTransID();   
               $dbValue[0] = QuoteValue(DPE_CHAR,$resumeId);
               $dbValue[1] = QuoteValue(DPE_CHAR,$pemeriksaanId3);
               $dbValue[2] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["biaya_total"]);
               $dbValue[3] = QuoteValue(DPE_CHAR,$userName);
               $dbValue[4] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["cust_usr_id"]);
               $dbValue[5] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["biaya_nama"]);
               $dbValue[6] = QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["biaya_id"]); 
               $dbValue[7] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
               $dbValue[8] = QuoteValue(DPE_CHAR,$regToday["reg_id"]);
               $dbValue[9] = QuoteValue(DPE_DATE,date("Y-m-d"));
              
              
               $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
               $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
   
               $dtmodel->Insert() or die("insert  error");	
               
               unset($dtmodel);
               unset($dbField);
               unset($dbValue);
               unset($dbKey);
               
          }
          // penjadwalannya biar nggak bisa dipanggil di antrian lagi
             $sql = "update klinik.klinik_penjadwalan set is_proses ='y'
                     where penjadwalan_id = ".QuoteValue(DPE_CHAR,$dataBiayaJdwl[$c]["penjadwalan_id"]);
             $rs = $dtaccess->Execute($sql);
              }
              }
            


          //insert biaya pemeriksaan 
          if($_POST["dep_konf_kons"]=='y'){
              $sql = "select a.*,b.*  from  klinik.klinik_biaya_pemeriksaan a left join klinik.klinik_biaya b 
                      on a.id_biaya = b.biaya_id where a.id_tipe_biaya=".QuoteValue(DPE_CHAR,$_POST["reg_tipe_layanan"])." and 
                      a.id_shift=".QuoteValue(DPE_CHAR,$_POST["reg_shift"])." and a.id_poli=".QuoteValue(DPE_CHAR,$_POST["id_poli"])."
                      and a.id_tahun_tarif=".QuoteValue(DPE_CHAR,$tahunTarif)." and a.id_dep=".QuoteValue(DPE_CHAR,$depId);
              //echo $sql;
              //die();
              $periksa = $dtaccess->Fetch($sql);

               // Panggil Persentase Jamkesda
          	   $sqlJamkesda = "	select a.id_jamkesda_kota, b.jamkesda_kota_nama, b.jamkesda_kota_persentase_kota, 
                              b.jamkesda_kota_persentase_prov from klinik.klinik_registrasi a 
          						        left join global.global_jamkesda_kota b on a.id_jamkesda_kota=b.jamkesda_kota_id 
          						        where reg_id = ".QuoteValue(DPE_CHAR,$regId);
      					$dataJamkesdaPeriksa = $dtaccess->Fetch($sqlJamkesda);
      					$jamkesdaNama=$dataJamkesdaPeriksa["jamkesda_kota_nama"];
      					$jamkesdaPesentaseKota=$dataJamkesdaPeriksa["jamkesda_kota_persentase_kota"];
      					$jamkesdaPesentaseProv=$dataJamkesdaPeriksa["jamkesda_kota_persentase_prov"];
                
                $jaminDinkesProv=(StripCurrency($periksa["biaya_total"])*StripCurrency($jamkesdaPesentaseProv)/100);
					      $jaminDinkesKota=(StripCurrency($periksa["biaya_total"])*StripCurrency($jamkesdaPesentaseKota)/100);
					      //$totalJaminan=StripCurrency($jaminDinkesKota)+StripCurrency($jaminDinkesProv);
                //$hrsBayar = StripCurrency($totalTindNom)-StripCurrency($totalJaminan);
  
               //if ($dataBiayaKarcis["biaya_total"]!='0.00') { 
               // $lunas = ($_POST["cust_usr_jenis"]==PASIEN_BAYAR_SWADAYA || $_POST["cust_usr_jenis"]==7)?'n':'y';  

             //######tambahan ferina ########//
            if($periksa) {
               //#### tambahan ferina ####//

               $dbTable = "klinik.klinik_folio";
              $dbField[0] = "fol_id";   // PK
              $dbField[1] = "id_reg";
              $dbField[2] = "fol_nama";
              $dbField[3] = "fol_nominal";
              $dbField[4] = "fol_jenis";
              $dbField[5] = "id_cust_usr";
              $dbField[6] = "fol_waktu";
              $dbField[7] = "fol_lunas";
              $dbField[8] = "id_biaya";
              $dbField[9] = "id_poli";
              $dbField[10] = "fol_jenis_pasien";
              $dbField[11] = "id_dep";
              $dbField[12] = "who_when_update";
              $dbField[13] = "id_dokter";
              $dbField[14] = "fol_total_harga";
              $dbField[15] = "fol_jumlah";
              $dbField[16] = "fol_nominal_satuan"; 
              $dbField[17] = "fol_hrs_bayar";
              $dbField[18] = "fol_dijamin";
              $dbField[19] = "id_pembayaran";
              if($_POST["cust_usr_jenis"]=="18"){
              $dbField[20] = "fol_dijamin1";
              $dbField[21] = "fol_dijamin2";
              }
               
			         $folId2 = $dtaccess->GetTransID();
               $dbValue[0] = QuoteValue(DPE_CHAR,$folId2);
               $dbValue[1] = QuoteValue(DPE_CHAR,$regId);
               $dbValue[2] = QuoteValue(DPE_CHAR,$periksa["biaya_nama"]);
               $dbValue[3] = QuoteValue(DPE_NUMERIC,StripCurrency($periksa["biaya_total"]));
               $dbValue[4] = QuoteValue(DPE_CHAR,$periksa["biaya_jenis"]);
               $dbValue[5] = QuoteValue(DPE_CHAR,$userCustId);
               $dbValue[6] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
               $dbValue[7] = QuoteValue(DPE_CHAR,'n');
               $dbValue[8] = QuoteValue(DPE_CHAR,$periksa["biaya_id"]);
               $dbValue[9] = QuoteValue(DPE_CHAR,$_POST["id_poli"]);
               $dbValue[10] = QuoteValue(DPE_NUMERICKEY,$_POST["cust_usr_jenis"]);
               $dbValue[11] = QuoteValue(DPE_CHAR,$depId);
               $dbValue[12] = QuoteValue(DPE_CHAR,$userId);
               $dbValue[13] = QuoteValue(DPE_CHAR,$_POST["id_dokter"]);
               $dbValue[14] = QuoteValue(DPE_NUMERIC,StripCurrency($periksa["biaya_total"]));
               $dbValue[15] = QuoteValue(DPE_NUMERIC,'1');
               $dbValue[16] = QuoteValue(DPE_NUMERIC,StripCurrency($periksa["biaya_total"]));
               if($_POST["cust_usr_jenis"]=="5" || $_POST["cust_usr_jenis"]=="7" || $_POST["cust_usr_jenis"]=="18" || $_POST["cust_usr_jenis"]=='26'){
               $dbValue[17] = QuoteValue(DPE_NUMERIC,StripCurrency(0));
               } else {
               $dbValue[17] = QuoteValue(DPE_NUMERIC,StripCurrency($periksa["biaya_total"]));
               } 
               if($_POST["cust_usr_jenis"]=="5" || $_POST["cust_usr_jenis"]=="7" || $_POST["cust_usr_jenis"]=="18" || $_POST["cust_usr_jenis"]=='26'){
               $dbValue[18] = QuoteValue(DPE_NUMERIC,StripCurrency($periksa["biaya_total"]));
               } else {
               $dbValue[18] = QuoteValue(DPE_NUMERIC,StripCurrency(0));
               }
               if(!$reg["reg_id"]){
               $dbValue[19] = QuoteValue(DPE_CHAR,$byrId);
               } else {
               $dbValue[19] = QuoteValue(DPE_CHAR,$reg["id_pembayaran"]);
               }
               if($_POST["cust_usr_jenis"]=="18"){
               $dbValue[20] = QuoteValue(DPE_NUMERIC,StripCurrency($jaminDinkesProv));
               $dbValue[21] = QuoteValue(DPE_NUMERIC,StripCurrency($jaminDinkesKota));
               }
               
               
               $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
               $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
               
               $dtmodel->Insert() or die("insert error"); 

               unset($dtmodel);
               unset($dbField);
               unset($dbValue);                      
               unset($dbKey);

               $dbTable = "klinik.klinik_perawatan_tindakan";
              $dbField[0] = "rawat_tindakan_id";   // PK
              $dbField[1] = "id_rawat";
              $dbField[2] = "id_tindakan";
              $dbField[3] = "rawat_tindakan_total";
              $dbField[4] = "id_dep";                
              $dbField[5] = "rawat_tindakan_jumlah";
              
              $rawatTindId = $dtaccess->GetTransID();
              $dbValue[0] = QuoteValue(DPE_CHARKEY,$rawatTindId);
                   $dbValue[1] = QuoteValue(DPE_CHARKEY,$_POST["rawat_id"]);
                   $dbValue[2] = QuoteValue(DPE_CHAR,$periksa["biaya_id"]);
                   $dbValue[3] = QuoteValue(DPE_NUMERIC,StripCurrency($periksa["biaya_total"]));
                   $dbValue[4] = QuoteValue(DPE_CHAR,$depId);
                   $dbValue[5] = QuoteValue(DPE_NUMERIC,'1');
                  // print_r ($dbValue);
                  // die();
                   $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
                   $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
    
                   $dtmodel->Insert() or die("insert  error");
                   
                   unset($dtmodel);
                   unset($dbValue);
                   unset($dbKey);

               //masukkan pelaksana
                 //masukkan dokter dahulu
                $dbTable = "klinik.klinik_folio_pelaksana";
    					
    						$dbField[0] = "fol_pelaksana_id";   // PK
    						$dbField[1] = "id_fol";
    						$dbField[2] = "id_usr";
    						$dbField[3] = "fol_pelaksana_tipe";            
    						  							  
    						$dbValue[0] = QuoteValue(DPE_CHAR,$dtaccess->GetTransID());
    						$dbValue[1] = QuoteValue(DPE_CHAR,$folId2);
    						if($_POST["id_dokter"]){
                $dbValue[2] = QuoteValue(DPE_CHAR,$_POST["id_dokter"]);
                } else {
    						$dbValue[2] = QuoteValue(DPE_CHAR,$userId);
                }
    						$dbValue[3] = QuoteValue(DPE_CHAR,'1');
    						 
    						$dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    						$dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey,DB_SCHEMA_KLINIK);
    						
    						$dtmodel->Insert() or die("insert error"); 
    						
    						unset($dtmodel);
    						unset($dbField);
    						unset($dbValue);
    						unset($dbKey);
                
                $dbTable = "klinik.klinik_perawatan_tindakan_pelaksana";
    					
    						$dbField[0] = "rawat_tindakan_pelaksana_id";   // PK
    						$dbField[1] = "id_rawat_tindakan";
    						$dbField[2] = "id_usr";
    						$dbField[3] = "rawat_tindakan_pelaksana_tipe";            
    						  							  
    						$dbValue[0] = QuoteValue(DPE_CHAR,$dtaccess->GetTransID());
    						$dbValue[1] = QuoteValue(DPE_CHAR,$rawatTindId);
    						if($_POST["id_dokter"]){
                $dbValue[2] = QuoteValue(DPE_CHAR,$_POST["id_dokter"]);
                } else {
    						$dbValue[2] = QuoteValue(DPE_CHAR,$userId);
                }
    						$dbValue[3] = QuoteValue(DPE_CHAR,'1');
    						 
    						$dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    						$dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey,DB_SCHEMA_KLINIK);
    						
    						$dtmodel->Insert() or die("insert error"); 
    						
    						unset($dtmodel);
    						unset($dbField);
    						unset($dbValue);
    						unset($dbKey); 

                 //masukkan pelaksana dahulu
                $dbTable = "klinik.klinik_folio_pelaksana";
    					
    						$dbField[0] = "fol_pelaksana_id";   // PK
    						$dbField[1] = "id_fol";
    						$dbField[2] = "id_usr";
    						$dbField[3] = "fol_pelaksana_tipe";
    						  							  
    						$dbValue[0] = QuoteValue(DPE_CHAR,$dtaccess->GetTransID());
    						$dbValue[1] = QuoteValue(DPE_CHAR,$folId2);
    						if($_POST["id_dokter"]){
                $dbValue[2] = QuoteValue(DPE_CHAR,$_POST["id_dokter"]);
                } else {
    						$dbValue[2] = QuoteValue(DPE_CHAR,$userId);
                }
    						$dbValue[3] = QuoteValue(DPE_CHAR,'2');
    						 
    						$dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    						$dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey,DB_SCHEMA_KLINIK);
    						
    						$dtmodel->Insert() or die("insert error"); 
    						
    						unset($dtmodel);
    						unset($dbField);
    						unset($dbValue);
    						unset($dbKey);
                
                $dbTable = "klinik.klinik_perawatan_tindakan_pelaksana";
    					
    						$dbField[0] = "rawat_tindakan_pelaksana_id";   // PK
    						$dbField[1] = "id_rawat_tindakan";
    						$dbField[2] = "id_usr";
    						$dbField[3] = "rawat_tindakan_pelaksana_tipe";            
    						  							  
    						$dbValue[0] = QuoteValue(DPE_CHAR,$dtaccess->GetTransID());
    						$dbValue[1] = QuoteValue(DPE_CHAR,$rawatTindId);
    						if($_POST["id_dokter"]){
                $dbValue[2] = QuoteValue(DPE_CHAR,$_POST["id_dokter"]);
                } else {
    						$dbValue[2] = QuoteValue(DPE_CHAR,$userId);
                }
    						$dbValue[3] = QuoteValue(DPE_CHAR,'2');
    						 
    						$dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    						$dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey,DB_SCHEMA_KLINIK);
    						
    						$dtmodel->Insert() or die("insert error"); 
    						
    						unset($dtmodel);
    						unset($dbField);
    						unset($dbValue);
    						unset($dbKey);

          			$sql = "select * from  klinik.klinik_biaya_split where id_biaya = ".QuoteValue(DPE_CHAR,$periksa["biaya_id"])." 
                        and bea_split_nominal > 0";
                $dataSplitKarcis2 = $dtaccess->FetchAll($sql);
          			
          			for($i=0,$n=count($dataSplitKarcis2);$i<$n;$i++) {
          				$dbTable = "klinik.klinik_folio_split";
          			
          				$dbField[0] = "folsplit_id";   // PK
          				$dbField[1] = "id_fol";
          				$dbField[2] = "id_split";
          				// JIKA pasien gratis dan SKTM //
                  if($_POST["cust_usr_jenis"]=='6') {
                  $dbField[3] = "folsplit_nominal";
          				} else {
                  $dbField[3] = "folsplit_nominal";
                  }
                  	  
          				$dbValue[0] = QuoteValue(DPE_CHAR,$dtaccess->GetTransID());
          				$dbValue[1] = QuoteValue(DPE_CHAR,$folId2);
          				$dbValue[2] = QuoteValue(DPE_CHAR,$dataSplitKarcis2[$i]["id_split"]);
          				// JIKA pasien gratis dan SKTM //
                  if($_POST["cust_usr_jenis"]=='6') {
                  $dbValue[3] = QuoteValue(DPE_NUMERIC,'0.00');
          				} else {
                  $dbValue[3] = QuoteValue(DPE_NUMERIC,$dataSplitKarcis2[$i]["bea_split_nominal"]);
                  }
          				$dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
          				$dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
          				
          				$dtmodel->Insert() or die("insert error"); 
          				unset($dtmodel);
          				unset($dbField);
          				unset($dbValue);
          				unset($dbKey);
          			}
             //######tambahan ferina ########//
            }
               //#### tambahan ferina ####//
          
          }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
          // --- end insert folio ---
          
          $sql = "select reg_tipe_jkn, id_poli, reg_tipe_layanan from klinik.klinik_registrasi where reg_id=".QuoteValue(DPE_CHAR,$regId);
          $dataJKN = $dtaccess->Fetch($sql);
          
         $konfigurasi_cetak_kartu;
          $konfigurasi_cetak_status;
          $konfigurasi_cetak_barcode;
          $cetak_antrian;
            
      
     $sql = "select * from klinik.klinik_antrian_poli where id_dep = ".QuoteValue(DPE_CHAR,$depId)." order by antri_id asc";
     $rs = $dtaccess->Execute($sql);
     $dataAntrian = $dtaccess->FetchAll($rs);
     
     //print_r($dataAntrian);
     //die();
     
     for($i=0,$n=count($dataAntrian);$i<$n;$i++) {
     
          if(!$dataAntrian[2]["antri_nomer"]) {
            $sql = "update klinik.klinik_antrian_poli set antri_nomer = ".QuoteValue(DPE_NUMERIC,$noantri)." , id_cust_usr = ".QuoteValue(DPE_CHAR,$userCustId)." where antri_id = ".QuoteValue(DPE_NUMERIC,$dataAntrian[2]["antri_id"])." and id_dep = ".QuoteValue(DPE_CHAR,$depId);
            $rs = $dtaccess->Execute($sql);
           }
           elseif(!$dataAntrian[3]["antri_nomer"]) {
            $sql = "update klinik.klinik_antrian_poli set antri_nomer = ".QuoteValue(DPE_NUMERIC,$noantri)." , id_cust_usr = ".QuoteValue(DPE_CHAR,$userCustId)." where antri_id = ".QuoteValue(DPE_NUMERIC,$dataAntrian[3]["antri_id"])." and id_dep = ".QuoteValue(DPE_CHAR,$depId);
            $rs = $dtaccess->Execute($sql);
           }
           elseif(!$dataAntrian[4]["antri_nomer"]) {
            $sql = "update klinik.klinik_antrian_poli set antri_nomer = ".QuoteValue(DPE_NUMERIC,$noantri)." , id_cust_usr = ".QuoteValue(DPE_CHAR,$userCustId)." where antri_id = ".QuoteValue(DPE_NUMERIC,$dataAntrian[4]["antri_id"])." and id_dep = ".QuoteValue(DPE_CHAR,$depId);
            $rs = $dtaccess->Execute($sql);
           }
           
           // jika data di antrian kosong wkt registrasi lgsng masuk antrian
           if($dataAntrian[2]["antri_nomer"] && !$dataAntrian[2]["id_cust_usr"]) {
            $sql = "update klinik.klinik_antrian_poli set id_cust_usr = ".QuoteValue(DPE_CHAR,$userCustId)." where antri_id = ".QuoteValue(DPE_NUMERIC,$dataAntrian[2]["antri_id"])." and id_dep = ".QuoteValue(DPE_CHAR,$depId);
            $rs = $dtaccess->Execute($sql);
           }
           elseif($dataAntrian[3]["antri_nomer"] && !$dataAntrian[3]["id_cust_usr"]) {
            $sql = "update klinik.klinik_antrian_poli set id_cust_usr = ".QuoteValue(DPE_CHAR,$userCustId)." where antri_id = ".QuoteValue(DPE_NUMERIC,$dataAntrian[3]["antri_id"])." and id_dep = ".QuoteValue(DPE_CHAR,$depId);
            $rs = $dtaccess->Execute($sql);
           }
           elseif($dataAntrian[4]["antri_nomer"] && !$dataAntrian[4]["id_cust_usr"]) {
            $sql = "update klinik.klinik_antrian_poli set id_cust_usr = ".QuoteValue(DPE_CHAR,$userCustId)." where antri_id = ".QuoteValue(DPE_NUMERIC,$dataAntrian[4]["antri_id"])." and id_dep = ".QuoteValue(DPE_CHAR,$depId);
            $rs = $dtaccess->Execute($sql);
           }    
     }            
     
      // jika masuknya dari antrian penjadwalan --
      if($_POST["penjadwalan_id"]) {
      
            $sql = "update klinik.klinik_jadwal set jadwal_flag = 'y' , id_reg = ".QuoteValue(DPE_CHAR,$regId)." where jadwal_id = ".QuoteValue(DPE_CHAR,$_POST["penjadwalan_id"]);
            $rs = $dtaccess->Execute($sql);
      }
       		
  	  if($_POST["id_klinik_waktu_tunggu"]){
      $sql = "update klinik.klinik_waktu_tunggu set antri_poli=".QuoteValue(DPE_DATE,date("Y-m-d H:i:s")).",
              id_reg=".QuoteValue(DPE_CHAR,$regId).", id_cust_usr=".QuoteValue(DPE_CHAR,$userCustId).",
              cetak_sep=".QuoteValue(DPE_DATE,date("Y-m-d H:i:s")).", reg_no_sep=".QuoteValue(DPE_CHAR,$_POST["reg_no_sep"])."
              where klinik_waktu_tunggu_id=".QUoteValue(DPE_CHAR,$_POST["id_klinik_waktu_tunggu"]);
      $dtaccess->Execute($sql);
//      echo $sql;
      } else {
         $dbTable = "klinik.klinik_waktu_tunggu";
         $dbField[0] = "klinik_waktu_tunggu_id";   // PK
         $dbField[1] = "cetak_antrian";
         $dbField[2] = "klinik_waktu_tunggu_create";
         $dbField[3] = "antri_poli";
         $dbField[4] = "id_reg";
         $dbField[5] = "id_cust_usr";
         $dbField[6] = "cetak_sep";
         $dbField[7] = "reg_no_sep";
              
         $_POST["id_klinik_waktu_tunggu"] = $dtaccess->GetTransID();
         $dbValue[0] = QuoteValue(DPE_CHAR,$_POST["id_klinik_waktu_tunggu"]);
         $dbValue[1] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
         $dbValue[2] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
         $dbValue[3] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
         $dbValue[4] = QuoteValue(DPE_CHAR,$regId);
         $dbValue[5] = QuoteValue(DPE_CHAR,$userCustId);
         $dbValue[6] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
         $dbValue[7] = QuoteValue(DPE_CHAR,$_POST["reg_no_sep"]);

         $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
         $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
         
         $dtmodel->Insert() or die("insert  error");
         
         unset($dbField);
         unset($dtmodel);
         unset($dbValue);
         unset($dbKey);
      }
      
      $sql = "update klinik.klinik_registrasi set id_klinik_waktu_tunggu=".QuoteValue(DPE_CHAR,$_POST["id_klinik_waktu_tunggu"])."
              where reg_id=".QuoteValue(DPE_CHAR,$regId);
      $dtaccess->Execute($sql);
//  		  echo $sql;die();

      $sql = "select max(reg_antri_nomer) as nomore from klinik.klinik_poli_antrian where id_dep = ".QuoteValue(DPE_CHAR,$depId)."
              and id_poli=".QuoteValue(DPE_CHAR,$_POST["id_poli"])." and reg_antri_tanggal=".QuoteValue(DPE_DATE,date('Y-m-d'));          
      $noAntrian = $dtaccess->Fetch($sql);
      $noantri =  ($noAntrian["nomore"]+1);
      //if ($noantri<700) $noantri=$noantri+700;
      //if ($noantri<$konf["dep_no_urut_antrian_reguler"]) $noantri=$noantri+$konf["dep_no_urut_antrian_reguler"];
               
      $dbTable = "klinik.klinik_poli_antrian";
      $dbField[0] = "reg_antri_id";   // PK
      $dbField[1] = "reg_antri_nomer";
      $dbField[2] = "id_cust_usr";
      $dbField[3] = "id_dep";
      $dbField[4] = "id_poli";    
      $dbField[5] = "reg_antri_suara";   
      $dbField[6] = "reg_antri_tanggal"; 
      $dbField[7] = "id_klinik_waktu_tunggu"; 
      $dbField[8] = "id_reg";  
          
       $antriId = $dtaccess->GetNewID("klinik.klinik_poli_antrian","reg_antri_id");
       $dbValue[0] = QuoteValue(DPE_NUMERIC,$antriId);
       $dbValue[1] = QuoteValue(DPE_NUMERIC,$noantri);
       $dbValue[2] = QuoteValue(DPE_CHAR,$userCustId);
       $dbValue[3] = QuoteValue(DPE_CHAR,$depId);
       $dbValue[4] = QuoteValue(DPE_CHAR,$_POST["id_poli"]);
       $dbValue[5] = QuoteValue(DPE_CHAR,'0');
       $dbValue[6] = QuoteValue(DPE_DATE,date('Y-m-d'));
       $dbValue[7] = QuoteValue(DPE_CHAR,$_POST["id_klinik_waktu_tunggu"]);
       $dbValue[8] = QuoteValue(DPE_CHAR,$regId);
  
       $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
       $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
       
       $dtmodel->Insert() or die("insert  error");
       
       unset($dbField);
       unset($dtmodel);
       unset($dbValue);
       unset($dbKey);
       
      $sql = "update klinik.klinik_registrasi set reg_antrian_poli='M0' where reg_id=".QuoteValue(DPE_CHAR,$regId);
      $dtaccess->Execute($sql);
      
	      $cetak_antrianlama='y';
                                
          }
          
//combo poli
$sql = "select * from global.global_auth_poli where id_dep = ".QuoteValue(DPE_CHAR,$depId);
if($_GET["suntik"]){
$sql .= " and poli_tipe='J' ";
}else{
$sql .= " and (poli_tipe='J' or poli_tipe='L' or poli_tipe='R') ";
}
$sql .= " order by poli_tipe asc, poli_nama asc";
$rs = $dtaccess->Execute($sql);
$dataPoli = $dtaccess->FetchAll($rs);

    $sql = "select * from global.global_auth_user a";
 if($_GET["konsul"]){ 
     $sql .=" left join global.global_auth_user_poli b on a.usr_id=b.id_usr ";
     }
     $sql .=" where a.id_dep =".QuoteValue(DPE_CHAR,$depId)." and (a.id_rol = '2' or a.id_rol = '5' )  
        	 order by usr_name asc";
    $rs = $dtaccess->Execute($sql);       
  $dataDokterAll= $dtaccess->FetchAll($rs);
//echo $sql;  
  //jadwal
   

?>

<script src="<?php echo $ROOT;?>lib/script/antri/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="assets/css/styles.css" />

<script type="text/javascript" src="<?php echo $ROOT;?>lib/script/jquery-1.2.6.min.js"></script>   


<script type="text/javascript" src="<?php echo $ROOT;?>lib/script/jquery/autocomplete/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="<?php echo $ROOT;?>lib/script/jquery/autocomplete/jquery.autocomplete.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo $ROOT;?>lib/script/jquery/fancybox/jquery.fancybox-1.3.4.css" />
<script src="<?php echo $ROOT;?>lib/script/jquery/fancybox/jquery.easing-1.3.pack.js"></script>
<script src="<?php echo $ROOT;?>lib/script/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"></script>

<script language="javascript">        
var _wnd_stat;

function BukaStatWindow(url,judul)
{
    if(!_wnd_stat) {
			_wnd_stat = window.open(url,judul,'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=200,height=200,left=100,top=100');
	} else {
		if (_wnd_stat.closed) {
			_wnd_stat = window.open(url,judul,'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=200,height=200,left=100,top=100');
		} else {
			_wnd_stat.focus();
		}
	}
     return false;
}

<?php if($cetak_antrianlama=="y"){ ?>
        //if(confirm('Cetak no antrian?'))
		BukaStatWindow('cetakantrian_paslama.php?id=<?php echo $noantri;?>&noantri=<?php echo $noantri;?>&id_reg=<?php echo $regId;?>','No Antrian');
    document.location.href='antri_tambah.php?klinik=<?php echo $depId;?>';
<?php } ?> 

function ProsesCetakAntrian(id) {
  BukaWindow('cetakantrian.php','Cetak Antrian');
	//document.location.href='<?php echo $thisPage;?>';
}
 
function Logout()
{
    if(confirm('Are You Sure to LogOut?')) window.parent.document.location.href='<?php echo $ROOT;?>logout.php';
    else return false;
}
</script>
<script type="text/javascript">
<? $plx->Run(); ?>
function CariDokter(id_poli)
{  
	document.getElementById('dokter_view').innerHTML = GetDokter(id_poli,'type=r');
}
function CariJadwal(cust_usr_id)
{  
	document.getElementById('jadwal_view').innerHTML = GetJadwal(cust_usr_id,'type=r');
}

<?php if($cetak_antrian=="y"){ ?>
  BukaStatWindow('cetakantrian.php?id=<?php echo $userCustId;?>&id_reg=<?php echo $regId;?>&reg=<?php echo $kodeTrans;?>&noantri=<?php echo $noantri;?>','No Antrian');
  document.location.href='<?php echo "antri_tambah.php?klinik=".$depId;?>';
<?php } ?>

function CheckSimpan(frm) {
     
     if(!document.getElementById('cust_usr_kode').value) {
         alert('Kode RM harap diisi');
         document.getElementById('cust_usr_kode').focus();
         return false;
     }     
    if(!document.getElementById('cust_usr_nama').value) {
             alert('Nama Pasien harap diisi');
             document.getElementById('cust_usr_nama').focus();
             return false;
         }
    if(!document.getElementById('id_poli').value) {
             alert('Klinik / Poli harap dipilih');
             document.getElementById('id_poli').focus();
             return false;
         }
     if(!document.getElementById('id_dokter').value || document.getElementById('id_dokter').value=='--') {
         alert('Dokter harap diplih');
         document.getElementById('id_dokter').focus();
         return false;
     } 
         
          
}
</script>
<style type="text/css">
*{ font-weight: bold;}
body{ margin:0; padding:0; background: url(bg.jpg); background-size: 100%;-moz-background-size: 100%;}
#header{margin:0;  width:100%; height:80px;background: #001835;}
#kiri{padding-top: 10%; padding-left: 25%; width: 48%; }
#tombol{ width: 48%; float: left; padding: 10px;}
.left{ width:270px; height:80px; background:url(<?php echo $lokasi."/".$konfigurasi["dep_logo_kiri_antrian"];?>)no-repeat; background-size: 230px 60px; float:left;position: absolute; left: 0; top: 10;}
.center{ max-width:100%; float:left; text-align:center; background: #000;}
.right{ width:230px; height:80px; background:url(<?php echo $lokasiSikita."/logo_sikita.png";?>) no-repeat;background-size: 220px 76px; float:right; position: absolute; right: 0; top: 0;}
.nom, .nam{ float: left; line-height: 100px; font-size: 50px; font-weight: bold; padding-top: 10px;}
.nom{ width: 120px; text-align: left;}
h1{ text-transform: uppercase; text-decoration: none; line-height: 80px; margin-right: 60px; color: #fff; font-size: 40px; font-weight: bold; text-align: center; border: none;}
marquee{ font-size: 50px; font-weight: bold; text-transform: uppercase; position: absolute ; bottom: 0; width: 100%;}
.nomor{ max-width: 100%; height: 165px; padding:2px; margin-bottom: 13px;  border: 1px solid #e0e0e0; border-radius: 10px; -moz-border-radius: 10px; background: #fafafa;
box-shadow: }
h3{color:#fff; margin:0;max-width: 100%; padding: 5px 10px; background: #193c5d; text-align: center; font-size: 40px;border-radius: 10px 10px 0 0; -moz-border-radius: 10px 10px 0 0; text-transform: uppercase;}
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
<style type="text/css">
input.largerCheckbox
{
width: 30px;
height: 30px;
}
</style>
<body >
   
<br><br><br><br>
<form name="frmFind" method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>" enctype="multipart/form-data" autocomplete="off"/>

<table align ="center" width="75%" border="0" cellpadding="4" cellspacing="0">
<tr>
		<td colspan="2" align="left" class="tableheader"><h3>
    <?php if($_GET["konsul"]) { ?>DATA REGISTRASI PASIEN KONSUL<?php }else{ ?>DATA REGISTRASI PASIEN TERJADWAL <?php } ?>
    </h3></td>
	</tr>
<tr>
		<td width="25%" align="left" class="tablecontent" style="font-size:15pt;">No RM</td>
		<td width="75%" align="left" class="tablecontent-odd">
    <input type="text" name="cust_usr_kode" id="cust_usr_kode" size="30" maxlength="30" style="height:50px;font-size:15pt;" value="<?php echo $_POST["cust_usr_kode"];?>" onKeyDown="return tabOnEnter(this, event);" onClick="return tabOnEnter(this, event);"/>    
    <input type="hidden" id="cust_usr_id" name="cust_usr_id" value="<?php echo $_POST["cust_usr_id"];?>" />    
    </td>
</tr>
<tr>
		<td width="25%" align="left" class="tablecontent" style="font-size:15pt;">Nama Pasien</td>
		<td width="75%" align="left" class="tablecontent-odd">
    <input type="text" name="cust_usr_nama" id="cust_usr_nama" size="50" maxlength="50" style="height:50px;font-size:15pt;" value="<?php echo $_POST["cust_usr_nama"];?>" onKeyDown="return tabOnEnter(this, event);" onClick="return tabOnEnter(this, event);"/>
    </td>
</tr>
<tr>
		<td width="25%" align="left" class="tablecontent" style="font-size:15pt;">Tanggal Lahir Pasien</td>
		<td width="75%" align="left" class="tablecontent-odd">
    <input type="text" name="cust_usr_tanggal_lahir" id="cust_usr_tanggal_lahir" size="10" readonly="readonly" maxlength="10" style="height:50px;font-size:15pt;" value="<?php echo $_POST["cust_usr_tanggal_lahir"];?>" onKeyDown="return tabOnEnter(this, event);"/>
    </td>
</tr>
<tr>
		<td width="25%" align="left" class="tablecontent" style="font-size:15pt;">Alamat Pasien</td>
		<td width="75%" align="left" class="tablecontent-odd">
    <input type="text" name="cust_usr_alamat" id="cust_usr_alamat" size="75" readonly="readonly" maxlength="75" style="height:50px;font-size:15pt;" value="<?php echo $_POST["cust_usr_alamat"];?>" onKeyDown="return tabOnEnter(this, event);"/>
    </td>
</tr>
<?php if($_GET["suntik"]){ ?>
<tr>
	<td width="25%" class="tablecontent" style="font-size:15pt;">Nama Dokter</td>
  		<td width="75%" class="tablecontent-odd" style="font-size:15pt;">
         <select name="id_dokter" id="id_dokter" style="font-size:15pt;" onKeyDown="return tabOnEnter(this, event);">			
				<?php for($i=0,$n=count($dataDokterAll);$i<$n;$i++){ ?>
          <option  style="font-size:15pt;" value="<?php echo $dataDokterAll[$i]["usr_id"];?>" <?php if($dataDokterAll[$i]["usr_id"]==$_POST["id_dokter"]) echo "selected"; ?>><?php echo $dataDokterAll[$i]["usr_name"];?></option>
				    <?php } ?>
			       </select>
      </td>	   	    
</tr>
<?php } ?>
<?php if($_GET["konsul"]){ ?>
<tr>
		<td width="25%" align="left" class="tablecontent" style="font-size:15pt;">Klinik Tujuan</td>
		  <td width="75%" class="tablecontent-odd" ><select name="id_poli" id="id_poli" style="font-size:15pt;" onChange="CariDokter(id_poli.value)">		
    	 <option style="font-size:15pt;" value="">Pilih Klinik</option>
				<?php for($i=0,$n=count($dataPoli);$i<$n;$i++){ 
            unset($spacer); 
		
          	$length = (strlen($dataPoli[$i]["poli_tree"])/TREE_LENGTH_CHILD)-1; 
          	for($j=0;$j<$length;$j++) $spacer .= ".&nbsp;.&nbsp;";
        ?>
         	<option style="font-size:15pt;" value="<?php echo $dataPoli[$i]["poli_id"];?>" <?php if($dataPoli[$i]["poli_id"]==$_POST["id_poli"]) echo "selected"; ?>><?php echo $spacer.$dataPoli[$i]["poli_nama"];?></option>
				<?php } ?>
			</select><font color="red">*</font>
    </td>
</tr>
<tr>
	<td width="25%" class="tablecontent" style="font-size:15pt;">Nama Dokter</td>
  		<td width="75%" class="tablecontent-odd" style="font-size:15pt;">
      <?php if($_POST["id_dokter"]) { ?>
         <select name="id_dokter" id="id_dokter" style="font-size:15pt;" onKeyDown="return tabOnEnter(this, event);">			
				<?php for($i=0,$n=count($dataDokterAll);$i<$n;$i++){ ?>
          <option  style="font-size:15pt;" value="<?php echo $dataDokterAll[$i]["usr_id"];?>" <?php if($dataDokterAll[$i]["usr_id"]==$_POST["id_dokter"]) echo "selected"; ?>><?php echo $dataDokterAll[$i]["usr_name"];?></option>
				    <?php } ?>
			       </select>
			    <?php } else { ?>
        <div style="font-size:15pt;" id="dokter_view"></div>
  		<? } ?>
      </td>	   	    
</tr>
<?php } ?> 
<?php if($_GET["jadwal"]){ ?>
<tr>
	<td align="center" colspan="2">
  <input type="submit" name="btnLanjut" id="btnLanjut" value="Lanjut" class="tombol" style="font-size:2em;width=200px;height:75px" />
<input type="button" name="btnBack" id="btnBack" value="Kembali" class="tombol" style="font-size:2em;width=200px;height:75px" onClick="document.location.href='antri_tambah.php?klinik=<?php echo $_POST["klinik"];?>'"/>  
  </td>  		    
</tr>
<?php } ?>

<?php if($_POST["btnLanjut"]){
//$_POST["id_klinik_waktu_tunggu"]=$_GET["id_klinik_waktu_tunggu"];
//$_POST["jadwal"]=$_GET["jadwal"];
//echo "waktu tunggu ".$_POST["id_klinik_waktu_tunggu"]." jadwal ".$_POST["jadwal"]; die();

$skr = date('Y-m-d');
            //hapus penjadwalan yang salah
            $sql = "delete from klinik.klinik_penjadwalan where 
                    id_cust_usr =".QuoteValue(DPE_CHAR,$_POST["cust_usr_id"])." and
                    id_biaya ='' and (penjadwalan_tanggal = ".QuoteValue(DPE_DATE,'1900-01-01')." or penjadwalan_tanggal is null)";
              $rs = $dtaccess->Execute($sql);

            //hapus penjadwalan injeksi yang kosong
            $sql = "delete from klinik.klinik_penjadwalan where 
                    id_cust_usr =".QuoteValue(DPE_CHAR,$_POST["cust_usr_id"])." and
                    penjadwalan_keterangan ='' and
                    (id_biaya ='injl' or id_biaya ='injp') and 
                    (penjadwalan_tanggal = ".QuoteValue(DPE_DATE,'1900-01-01')." or penjadwalan_tanggal is null)";
              $rs = $dtaccess->Execute($sql); 
            //hapus biaya yang sama
              $sql = "select distinct(id_biaya),penjadwalan_id from klinik.klinik_penjadwalan
                     where id_cust_usr =".QuoteValue(DPE_CHAR,$_POST["cust_usr_id"])." and penjadwalan_tanggal= ".QuoteValue(DPE_DATE,$skr)."
                     and is_proses = 'n' ";
              $rs = $dtaccess->Execute($sql);       
        		  $dataBiayaJadwalDobel= $dtaccess->FetchAll($rs);
                  for($i=0,$n=count($dataBiayaJadwalDobel);$i<$n;$i++){
                  $sql = "select count(penjadwalan_id) as jadwal from klinik.klinik_penjadwalan
                          where id_biaya = ".QuoteValue(DPE_CHAR,$dataBiayaJadwalDobel[$i]["id_biaya"])." and id_cust_usr =".QuoteValue(DPE_CHAR,$_POST["cust_usr_id"])." 
                          and penjadwalan_tanggal= ".QuoteValue(DPE_DATE,$skr)." and is_proses = 'n'";
                  $rs = $dtaccess->Execute($sql);
                  $biayadobel = $dtaccess->Fetch($rs);
                  if($biayadobel["jadwal"]=='2'){
                  $sql = "delete from klinik.klinik_penjadwalan
                          where id_biaya = ".QuoteValue(DPE_CHAR,$dataBiayaJadwalDobel[$i]["id_biaya"])."
                          and id_cust_usr =".QuoteValue(DPE_CHAR,$_POST["cust_usr_id"])." 
                          and penjadwalan_tanggal= ".QuoteValue(DPE_DATE,$skr)." and is_proses = 'n' 
                          and penjadwalan_id <>".QuoteValue(DPE_DATE,$dataBiayaJadwalDobel[$i]["penjadwalan_id"]);
                   $rs = $dtaccess->Execute($sql);        
                  }
                  
                  }                                                                    
              $sql = "select * from klinik.klinik_penjadwalan a
                     left join klinik.klinik_biaya b on a.id_biaya = b.biaya_id
                     left join global.global_auth_poli c on b.id_poli=c.poli_id
                     where a.id_cust_usr =".QuoteValue(DPE_CHAR,$_POST["cust_usr_id"])." and penjadwalan_tanggal= ".QuoteValue(DPE_DATE,$skr)."
                     and a.is_proses = 'n' 
        				     order by a.id_sex, a.penjadwalan_urut asc";
              $rs = $dtaccess->Execute($sql);       
        		 $dataBiayaJadwal1= $dtaccess->FetchAll($rs);
             
//echo $sql; die();             
?>

<tr>
<td colspan="2">
<table width="100%">
<tr>
<td width="25%" align="left" class="tablecontent" style="font-size:15pt;">Tindakan Terjadwal</td>
    <td align="left" class="tablecontent" style="font-size:15pt;" > Tindakan </td>
    <td align="left" class="tablecontent" style="font-size:15pt;" > Klinik </td>
    <td align="left" class="tablecontent" style="font-size:15pt;" > Sisa Antrian </td>    
</tr>
<?php for($i=0,$n=count($dataBiayaJadwal1);$i<$n;$i++){ 
$sql = "select max(reg_antri_nomer) as nomorakhir from klinik.klinik_poli_antrian where id_dep = ".QuoteValue(DPE_CHAR,$depId)."
              and id_poli=".QuoteValue(DPE_CHAR,$dataBiayaJadwal1[$i]["id_poli"])." and reg_antri_tanggal=".QuoteValue(DPE_DATE,date('Y-m-d'));          
$noMaxAntrian = $dtaccess->Fetch($sql);
//echo $sql;
$sql = "select max(reg_antri_nomer) as nomore from klinik.klinik_poli_antrian where id_dep = ".QuoteValue(DPE_CHAR,$depId)."
              and id_poli=".QuoteValue(DPE_CHAR,$dataBiayaJadwal1[$i]["id_poli"])." and reg_antri_tanggal=".QuoteValue(DPE_DATE,date('Y-m-d'))."
              and reg_antri_suara='M'";          
$noPanggilAntrian = $dtaccess->Fetch($sql);
//echo $sql;
$sisaantrian =  $noMaxAntrian["nomorakhir"]-$noPanggilAntrian["nomore"];
?>
<tr>
	<td align="right" class="tablecontent" style="font-size:15pt;">
  <input type="checkbox" name="id_biaya[]" id="id_biaya[]" value="<?php echo $dataBiayaJadwal1[$i]["biaya_id"];?>"></td>
  <td class="tablecontent-odd" align="left" style="font-size:15pt;">  
    <?php if($dataBiayaJadwal1[$i]["biaya_id"]=="pxp" || $dataBiayaJadwal1[$i]["biaya_id"]=="pxl"){ ?>
  <?php echo "PX/Screening"; ?>
  <?php } elseif($dataBiayaJadwal1[$i]["biaya_id"]=="injl" || $dataBiayaJadwal1[$i]["biaya_id"]=="injp"){ ?>
  <?php echo "Injeksi"; ?>
  <?php } else { ?>
  <?php echo $dataBiayaJadwal1[$i]["biaya_nama"]; ?>
  <?php } ?>
  <input type="hidden" name="id_poli<?php echo $i;?>" id="id_poli<?php echo $i;?>" value="<?php echo $dataBiayaJadwal1[$i]["id_poli"];?>" />
  </td>
  <td class="tablecontent-odd" align="left" style="font-size:15pt;">
  <?php echo $dataBiayaJadwal1[$i]["poli_nama"];?>
  </td>
  <td class="tablecontent-odd" align="left" style="font-size:15pt;">
  <?php echo $sisaantrian;?>
  </td>
    		    
</tr>
<?php } ?>
</table>
</td></tr>
<input type="hidden" name="id_klinik_waktu_tunggu" id="id_klinik_waktu_tunggu" value="<?php echo $_POST["id_klinik_waktu_tunggu"];?>" />
<input type="hidden" name="jadwal" id="jadwal" value="<?php echo $_POST["jadwal"];?>" />
<?php } ?>   
<?php if($_GET["konsul"] || $_GET["suntik"] || $_POST["btnLanjut"]){ ?>
<tr>
<td align="center" colspan="2">
     <input type="submit" name="btnSave" id="btnSave" value="Daftar" class="tombol" style="font-size:2em;width=200px;height:75px" onClick="javascript:return CheckSimpan(document.frmFind);"/>
     <input type="button" name="btnBack" id="btnBack" value="Kembali" class="tombol" style="font-size:2em;width=200px;height:75px" onClick="document.location.href='antri_tambah.php?klinik=<?php echo $_POST["klinik"];?>'"/>

</td>
</tr>
<?php } ?>
</table>
<input type="hidden" name="id_klinik_waktu_tunggu" id="id_klinik_waktu_tunggu" value="<?php echo $_POST["id_klinik_waktu_tunggu"];?>" />
<input type="hidden" name="konsul" id="konsul" value="<?php echo $_POST["konsul"];?>" />
<input type="hidden" name="suntik" id="suntik" value="<?php echo $_POST["suntik"];?>" />
<input type="hidden" name="jadwal" id="jadwal" value="<?php echo $_POST["jadwal"];?>" />
</form>
<script type="text/javascript">
// untuk autocomplete no rm
  function findValue(li) {
  	if( li == null ) return alert("Tidak Ada Yang Cocok!");

  	// if coming from an AJAX call, let's use the CityId as the value
  	if( !!li.extra ) var sValue = li.extra[0];

  	// otherwise, let's just display the value in the text box
  	else var sValue = li.selectValue;
    var values =  sValue.split('~');

  //	alert(values);
    document.getElementById('cust_usr_nama').value=values[0];
    document.getElementById('cust_usr_id').value=values[1]; 
    document.getElementById('cust_usr_tanggal_lahir').value=values[2];
  document.getElementById('cust_usr_alamat').value=values[3];   
    document.getElementById('cust_usr_kode').focus();
  }

  function selectItem(li) {
    	findValue(li);
  }

  function formatItem(row) {
  
  var alamat = row[1].split('~');
 // alert(row[0]);
  if(row[0]) {
  document.getElementById('cust_usr_nama').value=alamat[0];
  document.getElementById('cust_usr_id').value=alamat[1];
  document.getElementById('cust_usr_tanggal_lahir').value=alamat[2];
  document.getElementById('cust_usr_alamat').value=alamat[3];    
  } 
  return "<font size='4'><b>"+ row[0] + " ( "+ alamat[0] + "," + " "+ alamat[2] + "," + " "+ alamat[3] + ")</b></font>";
     
  }
  
  function findValue2(li) {
  	if( li == null ) return alert("No match!");

  	// if coming from an AJAX call, let's use the CityId as the value
  	if( !!li.extra ) var sValue = li.extra[0];

  	// otherwise, let's just display the value in the text box
  	else var sValue = li.selectValue;
    var values =  sValue.split('~');

  	//alert("The value you selected was: " + sValue);
    document.getElementById('cust_usr_kode').value=values[0];
    document.getElementById('cust_usr_id').value=values[1];
    document.getElementById('cust_usr_tanggal_lahir').value=values[2];
  document.getElementById('cust_usr_alamat').value=values[3];
    document.getElementById('cust_usr_nama').focus();
  }

  function selectItem2(li) {
    	findValue2(li);
  }

  function formatItem2(row) {
  
  var alamat = row[1].split('~');
  
  if(row[0]) {
  document.getElementById('cust_usr_kode').value=alamat[0];
  document.getElementById('cust_usr_id').value=alamat[1];
  document.getElementById('cust_usr_tanggal_lahir').value=alamat[2];
  document.getElementById('cust_usr_alamat').value=alamat[3];    

  } 
  return "<font size='4'><b>"+ row[0] + " ( "+ alamat[0] + "," + " "+ alamat[2] + "," + " "+ alamat[3] + ")</b></font>";
     
  }

  function lookupAjax() {
    	var oSuggest = $("#cust_usr_kode")[0].autocompleter;      
    	var oSuggest2 = $("#cust_usr_nama")[0].autocompleter;
      oSuggest.findValue();
      oSuggest2.findValue2();
    	return false;
  }

  function lookupLocal() {
    	var oSuggest = $("#CityLocal")[0].autocompleter;
    	oSuggest.findValue();
    	return false;
  }
  
  
    $("#cust_usr_kode").autocomplete(
      "autocompletenama.php",
      {
  			delay:10,
  			minChars:2,
  			matchSubset:1,
  			matchContains:1,
  			cacheLength:10,
  			onItemSelect:selectItem,
  			onFindValue:findValue,
  			formatItem:formatItem,
  			autoFill:true
  		}
    );
      
    $("#cust_usr_nama").autocomplete(
      "autocompletekode.php",
      {
  			delay:10,
  			minChars:2,
  			matchSubset:1,
  			matchContains:1,
  			cacheLength:10,
  			onItemSelect:selectItem2,
  			onFindValue:findValue2,
  			formatItem:formatItem2,
  			autoFill:true
  		}
    );    
</script>

</body>

