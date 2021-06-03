<?php 
  /* WAJIB */
  $consID = "19840";
  $secretKey = "6eJBCC6014";
  $nik = "3519022805000001";
  $tgl = date('Y-m-d');
  /* WAJIB */

  /* Pembuatan Waktu BPJS */
  date_default_timezone_set('UTC');
  $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
  /* Pembuatan Waktu BPJS */
  
  $signature = hash_hmac('sha256', $consID."&".$tStamp, $secretKey, true); // Pembuatan Tanda Tangan Untuk BPJS
 
  $encodedSignature = base64_encode($signature); // Hasil Tanda Tangan Untuk BPJS Setelah di Encode

  $url = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest"; // Alamat API
  $url_detail = "$url/Peserta/nik/$nik/tglSEP/$tgl";

  /* FUN PENGIRIMAN BPJS */
  $arrheader =  array(
    'X-cons-id: '.$consID,
    'X-timestamp: '.$tStamp,
    'X-signature: '.$encodedSignature,
    'Content-Type: application/json; charset=utf-8',
  );
  
  $ch = curl_init($url_detail); // inisialisasi kirim url
  curl_setopt($ch, CURLOPT_HTTPHEADER, $arrheader);
  // curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);    

  $response = curl_exec($ch);

  // echo "<pre>";
  // print_r (json_decode($response));
  // echo "</pre>";

  $isi = json_decode($response, true);

  echo "<pre>";
  print_r ($isi);
  echo "</pre>";

  // if ($isi['metaData']['code'] == 200) {
  //   echo 'Nama = '.$isi['response']['peserta']['nama'];
  // }
?>