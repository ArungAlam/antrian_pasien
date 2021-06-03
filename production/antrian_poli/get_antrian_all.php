<?php
require_once("../penghubung.inc.php");
require_once($LIB . "login.php");
require_once($LIB . "encrypt.php");
require_once($LIB . "datamodel.php");
require_once($LIB . "currency.php");
require_once($LIB . "dateLib.php");
require_once($LIB . "expAJAX.php");
require_once($LIB . "tampilan.php");

$view = new CView($_SERVER['PHP_SELF'], $_SERVER['QUERY_STRING']);
$dtaccess = new DataAccess();
$enc = new textEncrypt();
$auth = new CAuth();
$depId = $auth->GetDepId();
$depLowest = $auth->GetDepLowest();
$tahunTarif = $auth->GetTahunTarif();
$depNama = $auth->GetDepNama();
$userName = $auth->GetUserName();
$skr = date('Y-m-d');

/***
 * status_pasien_tabel
      A  = sedang antri
      P  = Panggil Pasien 
      L  = Layani
      S  = Sudah di Layani  
 */
/* Cari pasien yang berstatus P */
$sql = "select c.usr_name ,d.poli_nama,d.poli_id ,usr_id,ruangan_nama
			       from klinik.klinik_jadwal_dokter a
             left join global.global_auth_user c on c.usr_id = a.id_dokter
             left join global.global_auth_poli d on d.poli_id = a.id_poli
             left join klinik.klinik_ruangan e on e.ruangan_id = a.id_ruangan
             where a.id_ruangan is not null";
$Ruang = $dtaccess->FetchAll($sql);

$jml_ruang = count($Ruang);
$data = [];
for ($i = 0; $i < $jml_ruang; $i++) {
  /* get data pasien di panggil */
  $sql = "select no_antrian_pasien  from klinik.klinik_nomer_antrian where status_antrian=" . QuoteValue(DPE_CHAR, 'P') . " and id_poli=" . QuoteValue(DPE_CHAR, $Ruang[$i]['poli_id']) . " 
            and id_dokter =" . QuoteValue(DPE_CHAR, $Ruang[$i]['usr_id']) . " and 
        DATE(when_create) =" . QuoteValue(DPE_DATE, $skr) . " 
        order by when_create desc";
  $no_panggil = $dtaccess->Fetch($sql);

  /* get data pasien di panggil */
  $sql = "select no_antrian_pasien  from klinik.klinik_nomer_antrian 
         where status_antrian=" . QuoteValue(DPE_CHAR, 'A') . " and id_poli=" . QuoteValue(DPE_CHAR, $Ruang[$i]['poli_id']) . " 
            and id_dokter =" . QuoteValue(DPE_CHAR, $Ruang[$i]['usr_id']) . " and 
            DATE(when_create) =" . QuoteValue(DPE_DATE, $skr) . " 
        order by when_create ASC";
  $no_next = $dtaccess->Fetch($sql);

  $data[$i]['call'] = $no_panggil['no_antrian_pasien'];
  $data[$i]['next'] = $no_next['no_antrian_pasien'];
  $data[$i]['poli'] = $Ruang[$i]['poli_nama'];
  $data[$i]['dokter'] = $Ruang[$i]['usr_name'];
  $data[$i]['ruang'] = $Ruang[$i]['ruangan_nama'];
}

echo json_encode($data);
