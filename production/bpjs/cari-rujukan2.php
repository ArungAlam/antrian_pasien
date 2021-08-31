<?php
require_once '../sep/sys/api.php';
	/* WAJIB */
  $consID = "19011";
  $secretKey = "2fXCD6305E";
  $kodeRS = "0197R007";
  // $no_kartu = $_POST['kepesertaan'];
  $no_kartu = '0002169321614';
  // $no_kartu = $_POST['kepesertaan'];
  $no_rujukan = '12334';
  $dmy = date('Y-m-d');
	/* WAJIB */

	/* Pembuatan Waktu BPJS */
  date_default_timezone_set('UTC');
  $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
  /* Pembuatan Waktu BPJS */

  /* Persetujuan */
  /* Persetujuan */
  // $signature = hash_hmac('sha256', $konfigurasi['dep_consID']."&".$tStamp, $konfigurasi['dep_secret_key_bpjs'], true);
  // $encodedSignature = base64_encode($signature);
  $signature = hash_hmac('sha256', $consID."&".$tStamp, $secretKey, true);
  $encodedSignature = base64_encode($signature);
  
  /* Persetujuan */
  /* Persetujuan */
 
 	/* Alamat API */
 	$url_asli = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest";
 	// $url_asli = "https://dvlp.bpjs-kesehatan.go.id:8080/new-vclaim-rest";
  $url = "$url_asli/Peserta/nokartu/$no_kartu/tglSEP/$dmy"; // Lihat Rujukan
 	/* Alamat API */

 	/* HEADER */
  $arrheader =  array(
    'X-cons-id: '.$consID,
    'X-timestamp: '.$tStamp,
    'X-signature: '.$encodedSignature,
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36",
"Accept-Language:en-US,en;q=0.5"
  );
 	/* HEADER */

 	// ppkPelayanan = Kode RS BPJS

 	/* ISI */
	$isi = '

  ';
 	/* ISI */
echo $url;
print_r($arrheader);
 	/* KIRIM */
 	$ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $arrheader);
  curl_setopt($ch, CURLOPT_VERBOSE, true );
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  // curl_setopt($ch, CURLOPT_POST, TRUE); // POST
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // SELAIN POST
  
  // curl_setopt($ch, CURLOPT_POSTFIELDS, $isi); // ISI   

  $response = curl_exec($ch);
  $data = json_decode($response);

  /* Check for 404 (file not found). */
//   $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//   switch ($httpCode) {
//     case 200:
//         $error_status = "200: Success";
//         return ($data);
//         break;
//     case 404:
//         $error_status = "404: API Not found";
//         break;
//     case 500:
//         $error_status = "500: servers replied with an error.";
//         break;
//     case 502:
//         $error_status = "502: servers may be down or being upgraded. Hopefully they'll be OK soon!";
//         break;
//     case 503:
//         $error_status = "503: service unavailable. Hopefully they'll be OK soon!";
//         break;
//     default:
//         $error_status = "Undocumented error: " . $httpCode . " : " . curl_error($ch);
//         break;
// }
// curl_close($ch);
// echo $error_status;
var_dump($response);
var_dump($data);

die;
 	/* KIRIM */
	// echo $bpjs->cekKepesertaan($no_kartu, $dmy);
   die();
?>