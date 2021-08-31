<?php
	/* Pembuatan Waktu BPJS */
  date_default_timezone_set('UTC');
  $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
  /* Pembuatan Waktu BPJS */

  /* Persetujuan */
  $signature = hash_hmac('sha256', $konfigurasi['dep_consID']."&".$tStamp, $konfigurasi['dep_secret_key_bpjs'], true);
  $encodedSignature = base64_encode($signature);
  /* Persetujuan */
 
 	/* Alamat API */
 	$url_asli = "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest";
  $url = "$url_asli/SEP/1.1/insert"; // Lihat BPJS
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
        "t_sep": {
          "noKartu": "'.$_POST['kepesertaan'].'",
          "tglSep": "'.date('Y-m-d').'",
          "ppkPelayanan": "'.$konfigurasi['dep_id_bpjs'].'",
          "jnsPelayanan": "2",
          "klsRawat": "3",
          "noMR": "'.$_POST['no_rm'].'",
          "rujukan": {
            "asalRujukan": "1",
            "tglRujukan": "2017-10-17",
            "noRujukan": "1234567",
            "ppkRujukan": "00010001"
          },
          "catatan": "",
          "diagAwal": "A00.1",
          "poli": {
            "tujuan": "IGD",
            "eksekutif": "0"
          },
          "cob": {
            "cob": "0"
          },
          "katarak": {
            "katarak": "0"
          },
          "jaminan": {
            "lakaLantas": "0",
            "penjamin": {
              "penjamin": "1",
              "tglKejadian": "2018-08-06",
              "keterangan": "kll",
              "suplesi": {
                "suplesi": "0",
                "noSepSuplesi": "0301R0010718V000001",
                "lokasiLaka": {
                  "kdPropinsi": "03",
                  "kdKabupaten": "0050",
                  "kdKecamatan": "0574"
                }
              }
            }
          },
          "skdp": {
            "noSurat": "000002",
            "kodeDPJP": "31661"
          },
          "noTelp": "081919999",
          "user": "RUMKITBAN"
        }
      }
    }
  ';
 	/* ISI */

 	/* KIRIM */
 	$ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $arrheader);
  curl_setopt($ch, CURLOPT_POST, TRUE); // POST
  // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // SELAIN POST
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
  curl_setopt($ch, CURLOPT_POSTFIELDS, $isi); // ISI   

  $response = curl_exec($ch);
 	/* KIRIM */

 	echo "<pre>";
 	print_r (json_decode($response, true));
 	echo "</pre>";
?>