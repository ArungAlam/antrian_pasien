<?php
	/* WAJIB */
  $consID = "19011";
  $secretKey = "2fXCD6305E";
  $kodeRS = "0197R007";
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
 	$url_asli = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest";
  $url = "$url_asli/Rujukan/insert"; // Lihat BPJS
 	/* Alamat API */

 	/* HEADER */
  $arrheader =  array(
    'X-cons-id: '.$consID,
    'X-timestamp: '.$tStamp,
    'X-signature: '.$encodedSignature,
    'Content-Type: Application/x-www-form-urlencoded', // Lihat BPJS
  );
 	/* HEADER */

 	// ppkPelayanan = Kode RS BPJS

 	/* ISI */
	$isi = '
    {
       "request": {
          "t_rujukan": {
             "noSep": "0197R0071219V000603",
             "tglRujukan": "2019-12-23",
             "ppkDirujuk": "0301R002",
             "jnsPelayanan": "1",
             "catatan": "test",
             "diagRujukan": "A00.1",
             "tipeRujukan": "1",
             "poliRujukan": "INT",
             "user": "Coba Ws"
          }
       }
    }
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

 	echo "<pre>";
 	print_r (json_decode($response, true));
 	echo "</pre>";
?>