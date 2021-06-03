<?php
require_once("../penghubung.inc.php");
require_once($LIB . "login.php");
require_once($LIB . "encrypt.php");
require_once($LIB . "datamodel.php");
require_once($LIB . "tampilan.php");
require_once($LIB . "currency.php");
require_once($LIB . "dateLib.php");

$sql = "select * from global.global_departemen";
$konfigurasi = $dtaccess->Fetch($sql);

// $consID = '23800';
// $secretKEY = '0qIA81B618';
$consID = '10000';
$secretKEY = '1112';

/* Pembuatan Waktu BPJS */
date_default_timezone_set('UTC');
$tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
/* Pembuatan Waktu BPJS */
$tgl = date('Y-m-d H:i:s');
/* PERSETUJUAN */
$signature = hash_hmac('sha256', $consID . "&" . $tStamp, $secretKEY, true); // Pembuatan Tanda Tangan Untuk BPJS
$encodedSignature = base64_encode($signature); // Hasil Tanda Tangan Untuk BPJS Setelah di Encode
/* PERSETUJUAN */

/* URL */
// $url_asli = "https://new-api.bpjs-kesehatan.go.id/aplicaresws";
$url_asli = "https://dvlp.bpjs-kesehatan.go.id:8888/aplicaresws";
$url = "$url_asli/rest/bed/update/1025R014/"; // LIHAT BPJS
/* URL */

/* HEEADER */

$arrheader =  array(
  'X-cons-id: ' . $consID,
  'X-timestamp: ' . $tStamp,
  'X-signature: ' . $encodedSignature,
  'Content-Type: application/json', // LIHAT BPJS
);
/* HEEADER */

$sql = "SELECT a.kelas_id, a.kelas_nama FROM klinik.klinik_kelas a ORDER BY a.kelas_nama ASC";
$dataKelas = $dtaccess->FetchAll($sql);

foreach ($dataKelas as $value) {
  if ($value['kelas_nama'] == 'KELAS 1') $kelas = 'KL1';
  else if ($value['kelas_nama'] == 'KELAS 2') $kelas = 'KL2';
  else if ($value['kelas_nama'] == 'KELAS 3') $kelas = 'KL3';
  else if ($value['kelas_nama'] == 'VIP') $kelas = 'VIP';
  else if ($value['kelas_nama'] == 'VVIP') $kelas = 'VVP';
  else if ($value['kelas_nama'] == 'HCU') $kelas = 'HCU';
	else if ($value['kelas_nama'] == 'ISOLASI') $kelas = 'ISO';
	else if ($value['kelas_nama'] == 'ICU') $kelas = 'ICU';
	else if ($value['kelas_nama'] == 'NICU') $kelas = 'NIC';
	else if ($value['kelas_nama'] == 'PERINA') $kelas = 'NON';
  else $kelas = 'NON';

  $sql = "SELECT * FROM klinik.klinik_kamar WHERE id_kelas = " . QuoteValue(DPE_CHAR, $value['kelas_id']);
  $dataKamar = $dtaccess->FetchAll($sql);

  $bor_kamar = array();
  foreach ($dataKamar as $key => $val) {
    $sql = "SELECT count(*) AS total FROM klinik.klinik_kamar_bed WHERE bed_keterangan = 'n' AND id_kamar = " . QuoteValue(DPE_CHAR, $val['kamar_id']);
    $dataBed = $dtaccess->Fetch($sql);

    $sql = "SELECT count(*) AS total FROM klinik.klinik_kamar_bed WHERE bed_keterangan = 'n' AND bed_reserved = 'n' AND id_kamar = " . QuoteValue(DPE_CHAR, $val['kamar_id']);
    $dataBedKosong = $dtaccess->Fetch($sql);

    /* DATA */
    $data = '
        { 
          "kodekelas":"' . $kelas . '",
          "koderuang":"' . $val['kamar_kode'] . '",
          "namaruang":"' . $val['kamar_nama'] . '",
          "kapasitas":"' . $dataBed['total'] . '",
          "tersedia":"' . $dataBedKosong['total'] . '",
          "tersediapria":"' . $dataBedKosong['total'] . '", 
          "tersediawanita":"' . $dataBedKosong['total'] . '",
          "tersediapriawanita":"' . $dataBedKosong['total'] . '"
        }
      ';
    /* DATA */
    echo "update ".$tgl;
    /* PENGIRIMAN */
    $ch = curl_init($url); // inisialisasi kirim url
    curl_setopt($ch, CURLOPT_HTTPHEADER, $arrheader);
    curl_setopt($ch, CURLOPT_POST, TRUE); // POST
    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // SELAIN POST
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // DATA

    $response = curl_exec($ch);
    /* PENGIRIMAN */

    $isi_bpjs = json_decode($response, true);

    echo "<pre>";
    print_r($isi_bpjs);
    echo "</pre>";

    //insert baru jika belum ada beradsarkan response
    $metadata = $isi_bpjs["metadata"];
    $code = $metadata["code"];
    //echo $code;

    if ($code == '0' && $dataBed['total']  > 0) {
      $url = "$url_asli/rest/bed/create/1025R014/"; // LIHAT BPJS

      /* PENGIRIMAN */
      $ch = curl_init($url); // inisialisasi kirim url
      curl_setopt($ch, CURLOPT_HTTPHEADER, $arrheader);
      curl_setopt($ch, CURLOPT_POST, TRUE); // POST
      // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // SELAIN POST
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // DATA

      $responsem = curl_exec($ch);
      /* PENGIRIMAN */

      $isi_bpjsn = json_decode($responsem, true);

      echo "tambah ".$tgl;

      echo "<pre>";
      print_r($isi_bpjsn);
      echo "</pre>";
    }
		/* delete kamar bila data bed virtual smua */
		if($dataBed['total'] == 0){
			echo "del ".$tgl;
			$url = "$url_asli/rest/bed/delete/1025R014/"; // LIHAT BPJS
			$data = '
        { 
          "kodekelas":"' . $kelas . '",
          "koderuang":"' . $val['kamar_kode'] . '"
        }
      ';

      /* PENGIRIMAN */
      $ch = curl_init($url); // inisialisasi kirim url
      curl_setopt($ch, CURLOPT_HTTPHEADER, $arrheader);
      curl_setopt($ch, CURLOPT_POST, TRUE); // POST
      // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // SELAIN POST
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // DATA

      $responsem = curl_exec($ch);
      /* PENGIRIMAN */

      $isi_bpjsn = json_decode($responsem, true);

      echo "tambah ".$tgl;

      echo "<pre>";
      print_r($isi_bpjsn);
      echo "</pre>";

		}
  }
	
  // $bor[] = $bor_kamar;
}

// print_r($arrheader);
// 		$url = "$url_asli/rest/bed/read/1025R014/1/5/";
// 	  $ch = curl_init($url); // inisialisasi kirim url
//       curl_setopt($ch, CURLOPT_HTTPHEADER, $arrheader);
//       // curl_setopt($ch, CURLOPT_POST, TRUE); // POST
//       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // SELAIN POST
//       curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//       // curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // DATA

//       $responsek = curl_exec($ch);
//       /* PENGIRIMAN */

//       $isi_bpjsn = json_decode($responsek, true);

//       echo "read ".$tgl;

//       echo "<pre>";
//       print_r($isi_bpjsn);
//       echo "</pre>";
