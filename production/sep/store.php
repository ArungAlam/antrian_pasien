<?php
  // LIBRARY
  require_once("../penghubung.inc.php");
  require_once($LIB."login.php");
  require_once($LIB."encrypt.php");
  require_once($LIB."datamodel.php");
  require_once($LIB."dateLib.php");
  require_once($LIB."tampilan.php");
 
  //INISIALISAI AWAL LIBRARY
  $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
  $dtaccess = new DataAccess();
  $enc = new textEncrypt();
  $auth = new CAuth();
  $depId = $auth->GetDepId();
  $userName = $auth->GetUserName();
  $userId = $auth->GetUserId();
  $userLogin = $auth->GetUserData();                 

  #update no jaminan + telp
  $sql = "UPDATE global.global_customer_user SET cust_usr_no_jaminan = ".QuoteValue(DPE_CHAR, $_POST["noKartu"]);
  $sql .= ", cust_usr_no_hp =".QuoteValue(DPE_CHAR, $_POST["cust_usr_no_hp"]);
  $sql .= " WHERE cust_usr_id = ".QuoteValue(DPE_CHAR, $_POST["id_cust_usr"]);
  $dtaccess->execute($sql);

  #insert klinik sep      
  $dbTable = "klinik.klinik_sep";

  $dbField[0] = "sep_id";   // PK
  $dbField[1] = "sep_reg_id";
  $dbField[2] = "sep_cust_usr_id";
  $dbField[3] = "jns_pelayanan";
  $dbField[4] = "no_kartu";
  $dbField[5] = "jenis_peserta_txt";
  $dbField[6] = "tipe_jkn";
  $dbField[7] = "poli_tujuan";
  $dbField[8] = "rujukan_asal_rujukan";
  $dbField[9] = "rujukan_ppk_rujukan_txt";
  $dbField[10] = "rujukan_ppk_rujukan";
  $dbField[11] = "rujukan_no_rujukan";
  $dbField[12] = "skdp_no_surat";
  $dbField[13] = "skdp_no_dpjp";
  $dbField[14] = "kls_rawat_txt";
  $dbField[15] = "cob";
  $dbField[16] = "katarak";
  $dbField[17] = "diag_awal_txt";
  $dbField[18] = "diag_awal";
  $dbField[19] = "jaminan_lakalantas";
  $dbField[20] = "catatan";
  $dbField[21] = "laka_suplesi";
  $dbField[22] = "laka_nosep_suplesi";
  $dbField[23] = "laka_tgl_kejadian";
  $dbField[24] = "laka_kdpropinsi";
  $dbField[25] = "laka_kdkabupaten";
  $dbField[26] = "laka_kdkecamatan";
  $dbField[27] = "laka_keterangan";
  $dbField[28] = "no_sep";
  $dbField[29] = "tgl_sep";
  $dbField[30] = "rujukan_tgl_rujukan";
  $dbField[31] = "poli_eksekutif";
  $dbField[32] = "kls_rawat";
  $dbField[33] = "laka_penjamin";
  $dbField[34] = "user_nama";
  $dbField[35] = "poli_tujuan_txt";

  if(count($_POST["laka_penjamin"]) > 1) { 
   $penjamin = implode(',', $_POST["laka_penjamin"]); 
  } else {
    $penjamin = $_POST["laka_penjamin"];
  }

  $sep_id = $dtaccess->GetTransID(); 
  $dbValue[0] = QuoteValue(DPE_CHAR, $sep_id);
  $dbValue[1] = QuoteValue(DPE_CHAR, $_POST["id_reg"]);
  $dbValue[2] = QuoteValue(DPE_CHAR, $_POST["id_cust_usr"]);
  $dbValue[3] = QuoteValue(DPE_CHAR, $_POST["jnsPelayanan"]);
  $dbValue[4] = QuoteValue(DPE_CHAR, $_POST["noKartu"]);
  $dbValue[5] = QuoteValue(DPE_CHAR, $_POST["jenisPeserta_txt"]);
  $dbValue[6] = QuoteValue(DPE_CHAR, $_POST["tipe_jkn"]);
  $dbValue[7] = QuoteValue(DPE_CHAR, $_POST["poli_tujuan"]);
  $dbValue[8] = QuoteValue(DPE_CHAR, $_POST["rujukan_asalRujukan"]);
  $dbValue[9] = QuoteValue(DPE_CHAR, $_POST["rujukan_ppkRujukan_txt"]);
  $dbValue[10] = QuoteValue(DPE_CHAR, $_POST["rujukan_ppkRujukan"]);
  $dbValue[11] = QuoteValue(DPE_CHAR, $_POST["rujukan_noRujukan"]);
  $dbValue[12] = QuoteValue(DPE_CHAR, $_POST["skdp_noSurat"]);
  $dbValue[13] = QuoteValue(DPE_CHAR, $_POST["skdp_noDPJP"]);
  $dbValue[14] = QuoteValue(DPE_CHAR, $_POST["klsRawat_txt"]);
  $dbValue[15] = QuoteValue(DPE_CHAR, $_POST["cob"]);
  $dbValue[16] = QuoteValue(DPE_CHAR, $_POST["katarak"]);
  $dbValue[17] = QuoteValue(DPE_CHAR, $_POST["diagAwal_txt"]);
  $dbValue[18] = QuoteValue(DPE_CHAR, $_POST["diagAwal"]);
  $dbValue[19] = QuoteValue(DPE_CHAR, $_POST["jaminan_lakaLantas"]);
  $dbValue[20] = QuoteValue(DPE_CHAR, $_POST["catatan"]);
  $dbValue[21] = QuoteValue(DPE_CHAR, $_POST["laka_suplesi"]);
  $dbValue[22] = QuoteValue(DPE_CHAR, $_POST["laka_noSepSuplesi"]);
  $dbValue[23] = QuoteValue(DPE_CHAR, date_db($_POST["laka_tglKejadian"]));
  $dbValue[24] = QuoteValue(DPE_CHAR, $_POST["laka_kdPropinsi"]);
  $dbValue[25] = QuoteValue(DPE_CHAR, $_POST["laka_kdKabupaten"]);
  $dbValue[26] = QuoteValue(DPE_CHAR, $_POST["laka_kdKecamatan"]);
  $dbValue[27] = QuoteValue(DPE_CHAR, $_POST["laka_keterangan"]);
  $dbValue[28] = QuoteValue(DPE_CHAR, $_POST["noSep"]);
  $dbValue[29] = QuoteValue(DPE_CHAR, date_db($_POST["tglSep"]));
  $dbValue[30] = QuoteValue(DPE_CHAR, date_db($_POST["rujukan_tglRujukan"]));
  $dbValue[31] = QuoteValue(DPE_CHAR, (!empty($_POST["poli_eksekutif"])) ? $_POST["poli_eksekutif"] : '0');
  $dbValue[32] = QuoteValue(DPE_CHAR, $_POST["klsRawat"]);
  $dbValue[33] = QuoteValue(DPE_CHAR,  $penjamin);
  $dbValue[34] = QuoteValue(DPE_CHAR, $userName);
  $dbValue[35] = QuoteValue(DPE_CHAR, $_POST["poli_tujuan_txt"]);
  $dbKey[0] = 0; 
  
  $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
  $dtmodel->Insert(); 
  unset($dtmodel);
  unset($dbField);
  unset($dbValue);
  unset($dbKey);

  $response['success'] = $_POST['id_reg'];

  echo json_encode($response);

  exit();        

?>