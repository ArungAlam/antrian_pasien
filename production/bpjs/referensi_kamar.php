<?php
  /* WAJIB */
  $consID = "19011";
  $secretKey = "2fXCD6305E";
  $kodeRS = "0197R007";
  $no_kartu = '0000109070954';
  /* WAJIB */

  /* Pembuatan Waktu BPJS */
  date_default_timezone_set('UTC');
  $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
  /* Pembuatan Waktu BPJS */

  /* Persetujuan */
  $signature = hash_hmac('sha256', $consID."&".$tStamp, $secretKey, true);
  $encodedSignature = base64_encode($signature);
  /* Persetujuan */
 
  /* Alamat API */
  $url_asli = "https://new-api.bpjs-kesehatan.go.id/aplicaresws";
  $url = "$url_asli/rest/ref/kelas"; // Lihat BPJS
  /* Alamat API */

  /* HEADER */
  $arrheader =  array(
    'X-cons-id: '.$consID,
    'X-timestamp: '.$tStamp,
    'X-signature: '.$encodedSignature,
    'Content-Type: application/json; charset=utf-8', // Lihat BPJS
  );
  /* HEADER */

  // ppkPelayanan = Kode RS BPJS

  /* ISI */
  $isi = '

  ';
  /* ISI */

  /* KIRIM */
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $arrheader);
  // curl_setopt($ch, CURLOPT_POST, TRUE); // POST
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // SELAIN POST
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
  // curl_setopt($ch, CURLOPT_POSTFIELDS, $isi); // ISI   

  $response = curl_exec($ch);
  /* KIRIM */
?>