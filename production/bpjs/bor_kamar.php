<?php 
  //LIBRARY 
  require_once("../penghubung.inc.php");
  require_once($LIB."login.php");
  require_once($LIB."encrypt.php"); 
  require_once($LIB."datamodel.php");
  require_once($LIB."tampilan.php");     
  require_once($LIB."currency.php");
  require_once($LIB."dateLib.php");

  $sql = "select * from global.global_departemen";
  $konfigurasi = $dtaccess->Fetch($sql);

  $consID = '23800';
  $secretKEY = '0qIA81B618';

  /* Pembuatan Waktu BPJS */
  date_default_timezone_set('UTC');
  $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
  /* Pembuatan Waktu BPJS */
  
  /* PERSETUJUAN */
  $signature = hash_hmac('sha256', $consID."&".$tStamp, $secretKEY, true); // Pembuatan Tanda Tangan Untuk BPJS
  $encodedSignature = base64_encode($signature); // Hasil Tanda Tangan Untuk BPJS Setelah di Encode
  /* PERSETUJUAN */

  /* URL */
  $url_asli = "https://new-api.bpjs-kesehatan.go.id/aplicaresws";
  $url = "$url_asli/rest/bed/read/0212R014/1/1000"; // LIHAT BPJS
  /* URL */

  /* HEEADER */
  $arrheader =  array(
    'X-cons-id: '.$consID,
    'X-timestamp: '.$tStamp,
    'X-signature: '.$encodedSignature,
    'Content-Type: application/json', // LIHAT BPJS
  );
  /* HEEADER */
  
  /* PENGIRIMAN */
  $ch = curl_init($url); // inisialisasi kirim url
  curl_setopt($ch, CURLOPT_HTTPHEADER, $arrheader);
  // curl_setopt($ch, CURLOPT_POST, TRUE); // POST
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // SELAIN POST
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  // curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // DATA

  $response = curl_exec($ch);
  /* PENGIRIMAN */

  $isi_bpjs = json_decode($response, true);

  foreach ($isi_bpjs as $key => $value) {
    if ($key == 'response') {
      foreach ($value as $val) {
        foreach ($val as $key => $v) {
          $isi[$key]['kelas'] = $v['namakelas'];
          $isi[$key]['ruang'] = $v['namaruang'];
          $isi[$key]['kapasitas'] = $v['kapasitas'];
          $isi[$key]['kapasitas'] = $v['kapasitas'];
          $isi[$key]['tersedia'] = $v['tersedia'];
        }
      }
    }
  }

  echo "<pre>";
  print_r ($isi_bpjs);
  echo "</pre>";
?>