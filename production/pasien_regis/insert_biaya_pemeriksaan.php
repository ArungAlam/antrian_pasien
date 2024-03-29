<?php
  // LIBRARY
  require_once("../penghubung.inc.php");
  require_once($LIB."login.php");
  require_once($LIB."encrypt.php");
  require_once($LIB."datamodel.php");
  require_once($LIB."dateLib.php");
  require_once($LIB."tampilan.php");
  require_once($LIB."currency.php");

  //INISIALISAI AWAL LIBRARY
  $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
  $dtaccess = new DataAccess();
  $enc = new textEncrypt();
  $auth = new CAuth();
  $depId = $auth->GetDepId();
  $userName = $auth->GetUserName();
  $userId = $auth->GetUserId();
  $tahunTarif = $auth->GetTahunTarif();
  $userLogin = $auth->GetUserData();
  $userName = $auth->GetUserName();

  if($_POST["layanan"]=='1') $regTipeAntrian = 'R';
  else if ($_POST["layanan"]=='2') $regTipeAntrian = 'E';
  else $regTipeAntrian = 'H';

  /* Pengecekan Tindakan Pememriksaan */
  $sql = "select a.*,b.*,c.*  from  klinik.klinik_biaya_pemeriksaan a left join klinik.klinik_biaya_tarif c on c.biaya_tarif_id = a.id_biaya_tarif left join klinik.klinik_biaya b on c.id_biaya = b.biaya_id where 1=1 and a.id_poli = ".QuoteValue(DPE_CHAR,$_POST["poli_klinik"]);
  $sql .= "and c.id_jenis_pasien = ".QuoteValue(DPE_NUMERICKEY,$_POST["jenis_pasien"]);
  $periksa = $dtaccess->Fetch($sql);
  /* Pengecekan Tindakan Pememriksaan */
  
  if($periksa) {
    /* INSERT BIAYA TINDAKAN */
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
    $dbField[20] = "tindakan_tanggal";
    $dbField[21] = "tindakan_waktu";
    $dbField[22] = "id_biaya_tarif"; 

    $folId2 = $dtaccess->GetTransID();

    $dbValue[0] = QuoteValue(DPE_CHAR,$folId2);
    $dbValue[1] = QuoteValue(DPE_CHAR,$regId);
    $dbValue[2] = QuoteValue(DPE_CHAR,$periksa["biaya_nama"]);
    $dbValue[3] = QuoteValue(DPE_NUMERIC,StripCurrency($periksa["biaya_total"]));
    $dbValue[4] = QuoteValue(DPE_CHAR,$periksa["biaya_jenis"]);
    $dbValue[5] = QuoteValue(DPE_CHAR,$_POST["cust_usr_id"]);
    $dbValue[6] = QuoteValue(DPE_DATE,date("Y-m-d H:i:s"));
    $dbValue[7] = QuoteValue(DPE_CHAR,'n');
    $dbValue[8] = QuoteValue(DPE_CHAR,$periksa["biaya_id"]);
    $dbValue[9] = QuoteValue(DPE_CHAR,$_POST["poli_klinik"]);
    $dbValue[10] = QuoteValue(DPE_NUMERICKEY,$_POST["jenis_pasien"]);
    $dbValue[11] = QuoteValue(DPE_CHAR,$depId);
    $dbValue[12] = QuoteValue(DPE_CHAR,$_POST['nama_pasien']);
    $dbValue[13] = QuoteValue(DPE_CHAR,$_POST["dokter"]);
    $dbValue[14] = QuoteValue(DPE_NUMERIC,StripCurrency($periksa["biaya_total"]));
    $dbValue[15] = QuoteValue(DPE_NUMERIC,'1');
    $dbValue[16] = QuoteValue(DPE_NUMERIC,StripCurrency($periksa["biaya_total"]));
    if($_POST["jenis_pasien"]=="5" || $_POST["jenis_pasien"]=="7" || $_POST["jenis_pasien"]=="18" || $_POST["jenis_pasien"]=='26') $dbValue[17] = QuoteValue(DPE_NUMERIC,StripCurrency(0));
    else $dbValue[17] = QuoteValue(DPE_NUMERIC,StripCurrency($periksa["biaya_total"]));
    
    if($_POST["jenis_pasien"]=="5" || $_POST["jenis_pasien"]=="7" || $_POST["jenis_pasien"]=="18" || $_POST["jenis_pasien"]=='26') $dbValue[18] = QuoteValue(DPE_NUMERIC,StripCurrency($periksa["biaya_total"]));
    else $dbValue[18] = QuoteValue(DPE_NUMERIC,StripCurrency(0));
    $dbValue[19] = QuoteValue(DPE_CHAR,$byrId);
    $dbValue[20] = QuoteValue(DPE_DATE,date('Y-m-d'));
    $dbValue[21] = QuoteValue(DPE_DATE,date('H:i:s'));
    $dbValue[22] = QuoteValue(DPE_CHAR,$periksa["biaya_tarif_id"]);

    $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
    $dtmodel->Insert() or die("insert error"); 

    unset($dtmodel);
    unset($dbField);
    unset($dbValue);                      
    unset($dbKey);
    /* INSERT BIAYA TINDAKAN */

    /* INSERT PERAWATAN TINDAKAN */
    $dbTable = "klinik.klinik_perawatan_tindakan";

    $dbField[0] = "rawat_tindakan_id";   // PK
    $dbField[1] = "id_rawat";
    $dbField[2] = "id_tindakan";
    $dbField[3] = "rawat_tindakan_total";
    $dbField[4] = "id_dep";
    $dbField[5] = "rawat_tindakan_jumlah";

    $rawatTindId = $dtaccess->GetTransID();
    $dbValue[0] = QuoteValue(DPE_CHARKEY,$rawatTindId);
    $dbValue[1] = QuoteValue(DPE_CHARKEY,$rawat_id);
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
    /* INSERT PERAWATAN TINDAKAN */

    /* INSERT PELAKSANA 1 */
    $dbTable = "klinik.klinik_folio_pelaksana";
    					
    $dbField[0] = "fol_pelaksana_id";   // PK
    $dbField[1] = "id_fol";
    $dbField[2] = "id_usr";
    $dbField[3] = "fol_pelaksana_tipe";            

    $dbValue[0] = QuoteValue(DPE_CHAR,$dtaccess->GetTransID());
    $dbValue[1] = QuoteValue(DPE_CHAR,$folId2);
    if($_POST["dokter"])  $dbValue[2] = QuoteValue(DPE_CHAR,$_POST["dokter"]);
    else $dbValue[2] = QuoteValue(DPE_CHAR,$userId);
    $dbValue[3] = QuoteValue(DPE_CHAR,'1');

    $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey,DB_SCHEMA_KLINIK);

    $dtmodel->Insert() or die("insert error"); 

    unset($dtmodel);
    unset($dbField);
    unset($dbValue);
    unset($dbKey);
    /* INSERT PELAKSANA 1 */
                
    /* INSERT PELAKSANA 2 */
    $dbTable = "klinik.klinik_folio_pelaksana";

    $dbField[0] = "fol_pelaksana_id";   // PK
    $dbField[1] = "id_fol";
    $dbField[2] = "id_usr";
    $dbField[3] = "fol_pelaksana_tipe";

    $dbValue[0] = QuoteValue(DPE_CHAR,$dtaccess->GetTransID());
    $dbValue[1] = QuoteValue(DPE_CHAR,$folId2);
    if($_POST["dokter"]){
    $dbValue[2] = QuoteValue(DPE_CHAR,$_POST["dokter"]);
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
    /* INSERT PELAKSANA 2 */

    /* INSERT TINDAKAN PELAKSANA 1 */
    $dbTable = "klinik.klinik_perawatan_tindakan_pelaksana";

    $dbField[0] = "rawat_tindakan_pelaksana_id";   // PK
    $dbField[1] = "id_rawat_tindakan";
    $dbField[2] = "id_usr";
    $dbField[3] = "rawat_tindakan_pelaksana_tipe";

    $dbValue[0] = QuoteValue(DPE_CHAR,$dtaccess->GetTransID());
    $dbValue[1] = QuoteValue(DPE_CHAR,$rawatTindId);
    if($_POST["dokter"]) $dbValue[2] = QuoteValue(DPE_CHAR,$_POST["dokter"]);
    else $dbValue[2] = QuoteValue(DPE_CHAR,$userId);
    $dbValue[3] = QuoteValue(DPE_CHAR,'1');

    $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey,DB_SCHEMA_KLINIK);
    $dtmodel->Insert() or die("insert error"); 

    unset($dtmodel);
    unset($dbField);
    unset($dbValue);
    unset($dbKey); 
    /* INSERT TINDAKAN PELAKSANA 1 */
                
    /* INSERT TINDAKAN PELAKSANA 2 */
    $dbTable = "klinik.klinik_perawatan_tindakan_pelaksana";

    $dbField[0] = "rawat_tindakan_pelaksana_id";   // PK
    $dbField[1] = "id_rawat_tindakan";
    $dbField[2] = "id_usr";
    $dbField[3] = "rawat_tindakan_pelaksana_tipe";            

    $dbValue[0] = QuoteValue(DPE_CHAR,$dtaccess->GetTransID());
    $dbValue[1] = QuoteValue(DPE_CHAR,$rawatTindId);
    if($_POST["dokter"]) $dbValue[2] = QuoteValue(DPE_CHAR,$_POST["dokter"]);
    else $dbValue[2] = QuoteValue(DPE_CHAR,$userId);
    $dbValue[3] = QuoteValue(DPE_CHAR,'2');

    $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey,DB_SCHEMA_KLINIK);
    $dtmodel->Insert() or die("insert error"); 

    unset($dtmodel);
    unset($dbField);
    unset($dbValue);
    unset($dbKey);
    /* INSERT TINDAKAN PELAKSANA 2 */

    /* SQL SPLIT */
    $sql = "select * from  klinik.klinik_biaya_split where id_biaya = ".QuoteValue(DPE_CHAR,$periksa["biaya_tarif_id"])." and bea_split_nominal > 0";
    $dataSplitKarcis2 = $dtaccess->FetchAll($sql);
    /* SQL SPLIT */
          			
    for($i=0,$n=count($dataSplitKarcis2);$i<$n;$i++) {
      /* INSERT FOLIO SPLIT */
      $dbTable = "klinik.klinik_folio_split";

      $dbField[0] = "folsplit_id";   // PK
      $dbField[1] = "id_fol";
      $dbField[2] = "id_split";
      if($_POST["jenis_pasien"]=='6') $dbField[3] = "folsplit_nominal";
      else $dbField[3] = "folsplit_nominal";
                  	  
      $dbValue[0] = QuoteValue(DPE_CHAR,$dtaccess->GetTransID());
      $dbValue[1] = QuoteValue(DPE_CHAR,$folId2);
      $dbValue[2] = QuoteValue(DPE_CHAR,$dataSplitKarcis2[$i]["id_split"]);
      if($_POST["jenis_pasien"]=='6') $dbValue[3] = QuoteValue(DPE_NUMERIC,'0.00');
      else $dbValue[3] = QuoteValue(DPE_NUMERIC,$dataSplitKarcis2[$i]["bea_split_nominal"]);
      
      $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
			$dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);
			$dtmodel->Insert() or die("insert error"); 

			unset($dtmodel);
			unset($dbField);
			unset($dbValue);
			unset($dbKey);
      /* INSERT FOLIO SPLIT */
		}
  }
?>