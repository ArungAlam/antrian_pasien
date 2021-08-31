<?php
require_once "helper.php";

class Bpjs {

	private $uri		= 'https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest';
	private $cons_id	= '19011';
	private $secret 	= '2fXCD6305E';
	private $rs_code 	= '3175064';
	private $rs_ppkCode = '0197R007';

	public function signature()
	{
		date_default_timezone_set('UTC');
  		$time=time();

		$data = "$this->cons_id&$time";
   		$signature = base64_encode(hash_hmac('sha256', utf8_encode($data), utf8_encode($this->secret), true));

		return $signature;
	}

	public function conf()
	{
		return array(
			'cons_id' => $this->cons_id,
			'rs_code' => $this->rs_code
		);
	}

	public function callAPI($method, $url, $data, $cType){
		$curl = curl_init();
		date_default_timezone_set('UTC');

	   	# HEADER :
	   	$arrheader =  array(
			'X-cons-id: '. $this->cons_id,
			'X-timestamp: '. time(),
			'X-signature: '.$this->signature(),
			//'Content-Type: application/json'
		);
	    if ($cType) $arrheader[] = 'Content-Type: ' .$cType;
	    if ($data) $arrheader[] = 'Content-Length: ' .strlen($data);

		// OPTIONS:
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $arrheader);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		switch ($method){
	      case "POST":
	         curl_setopt($curl, CURLOPT_POST, 1);
	         if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	      break;
	      case "PUT":
	         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
	         if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
	      break;
	      case "DELETE":
	         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
	         if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
	      break;
	      default:
	       // if ($data) $url = sprintf("%s?%s", $data, http_build_query($data));
	   	}

		// EXECUTE:
		$result = curl_exec($curl);
		if(!$result){ die(http_response_code(408)); }
		curl_close($curl);
		return $result;
	}

	public function cekKepesertaan($param, $tglSEP)
	{
		$lenght = lenght($param);
		if ($_GET['tglSep']) {
			$tglSEP = nice_date($tglSEP, 'Y-m-d');
		} else {
			$tglSEP = date("Y-m-d");
		}

		if ($lenght < 16 && $lenght <= 13 && $lenght > 8 ) { # NO JAMINAN
		  $completeurl = "$this->uri/Peserta/nokartu/".$param."/tglSEP/".$tglSEP;
		}else{ //if($lenght <= 16 && $lenght > 13 && $lenght > 8 ) { # NIK
		  $completeurl = "$this->uri/Peserta/nik/".$param."/tglSEP/".$tglSEP;
		}
		
		// echo $completeurl;

		$response = $this->callAPI('GET',$completeurl, false, 'application/json'); //kirim request
		//$response = $this->xrequest($completeurl); //kirim request
		return $response;
	}

	public function createSep()
	{		
		#poli eksekutif
		(!empty($_POST["poli_eksekutif"])) ? $rpe = $_POST["poli_eksekutif"] : $rpe = '0';
		#penjamin
		if (count($_POST["laka_penjamin"]) > 0) {
			$penjamin = implode(',', $_POST["laka_penjamin"]);
		} else {
			$penjamin = $_POST["laka_penjamin"];
		}
		//$noRujukan = str_pad($this->reg_no_rujukan,19,"0",STR_PAD_LEFT);

        $datastring = 
        '
        {
           "request": {
              "t_sep": {
                 "noKartu": "'.$_POST["noKartu"].'",
                 "tglSep": "'.date_db($_POST["tglSep"]).'",
                 "ppkPelayanan": "'.$this->rs_ppkCode.'",
                 "jnsPelayanan": "'.$_POST["jnsPelayanan"].'",
                 "klsRawat": "'.$_POST["klsRawat"].'",
                 "noMR": "'.$_POST["noMR"].'",
                 "rujukan": {
                    "asalRujukan": "'.$_POST["rujukan_asalRujukan"].'",
                    "tglRujukan": "'.date_db($_POST["rujukan_tglRujukan"]).'",
                    "noRujukan": "'.$_POST["rujukan_noRujukan"].'",
                    "ppkRujukan": "'.$_POST["rujukan_ppkRujukan"].'"
                 },
                 "catatan": "'.$_POST["catatan"].'",
                 "diagAwal": "'.$_POST["diagAwal"].'",
                 "poli": {
                    "tujuan": "'.$_POST["poli_tujuan"].'",
                    "eksekutif": "'.$rpe.'"
                 },
                 "cob": {
                    "cob": "'.$_POST["cob"].'"
                 },
                 "katarak": {
                    "katarak": "'.$_POST["katarak"].'"
                 },
                 "jaminan": {
                    "lakaLantas": "'.$_POST["jaminan_lakaLantas"].'",
                    "penjamin": {
                        "penjamin": "'.$penjamin.'",
                        "tglKejadian": "'.date_db($_POST["laka_tglKejadian"]).'",
                        "keterangan": "'.$_POST["laka_keterangan"].'",
                        "suplesi": {
                            "suplesi": "0",
                            "noSepSuplesi": "'.$_POST["laka_noSepSuplesi"].'",
                            "lokasiLaka": {
                                "kdPropinsi": "'.$_POST["laka_kdPropinsi"].'",
                                "kdKabupaten": "'.$_POST["laka_kdKabupaten"].'",
                                "kdKecamatan": "'.$_POST["laka_kdKecamatan"].'"
                                }
                        }
                    }
                 },
                 "skdp": {
                    "noSurat": "'.$_POST["skdp_noSurat"].'",
                    "kodeDPJP": "'.$_POST["skdp_noDPJP"].'"
                 },
                 "noTelp": "'.$_POST["noTelp"].'",
                 "user": "'.loginData('name').'"
              }
           }
        }                 
        ';


        $completeurl = $this->uri."/SEP/1.1/insert";
		$response = $this->callAPI('POST', $completeurl, $datastring, 'Application/x-www-form-urlencoded');
		return $response;
	}

	public function updateSep()
	{
		#poli eksekutif
		(!empty($_POST["poli_eksekutif"])) ? $rpe = $_POST["poli_eksekutif"] : $rpe = '0';	
		#penjamin
		if (count($_POST["laka_penjamin"]) > 0) {
			$penjamin = implode(',', $_POST["laka_penjamin"]);
		} else {
			$penjamin = $_POST["laka_penjamin"];
		}

        $datastring = 
        '                                            
	    {
	       "request": {
	          "t_sep": {
	             "noSep": "'.$_POST['noSep'].'",
	             "klsRawat": "'.$_POST['klsRawat'].'",
	             "noMR": "'.$_POST['cust_usr_kode'].'",
	             "rujukan": {
                    "asalRujukan": "'.$_POST["rujukan_asalRujukan"].'",
                    "tglRujukan": "'.date_db($_POST["rujukan_tglRujukan"]).'",
                    "noRujukan": "'.$_POST["rujukan_noRujukan"].'",
                    "ppkRujukan": "'.$_POST["rujukan_ppkRujukan"].'"
                 },
	             "catatan": "'.$_POST['catatan'].'",
	             "diagAwal": "'.$_POST['diagAwal'].'",
	             "poli": {
	                "eksekutif": "'.$_POST['poli_eksekutif'].'"
	             },
	             "cob": {
	                "cob": "'.$_POST['cob'].'"
	             },
	             "katarak":{
	                "katarak":"'.$_POST['katarak'].'"
	             },
	             "skdp":{
	                "noSurat":"'.$_POST['skdp_noSurat'].'",
	                "kodeDPJP":"'.$_POST['skdp_noDPJP'].'"            
	             },
	             "jaminan": {
	                "lakaLantas":"'.$_POST['jaminan_lakaLantas'].'",
	                "penjamin":
	                {
	                    "penjamin":"'.$penjamin.'",
	                    "tglKejadian":"'.date_db($_POST['laka_tglKejadian']).'",				
	                    "keterangan":"'.$_POST['laka_keterangan'].'",
	                    "suplesi":
	                        {
	                            "suplesi":"'.$_POST['laka_suplesi'].'",
	                            "noSepSuplesi":"'.$_POST['laka_noSepSuplesi'].'",
	                            "lokasiLaka": 
	                                {
	                                "kdPropinsi":"'.$_POST['laka_kdPropinsi'].'",
	                                "kdKabupaten":"'.$_POST['laka_kdKabupaten'].'",
	                                "kdKecamatan":"'.$_POST['laka_kdKecamatan'].'"
	                                }
	                        }					
	                }
	             },             
	             "noTelp": "'.$_POST['cust_usr_no_hp'].'",
	             "user": "'.loginData('name').'"
	          }
	       }
	    }                               
        ';

        $completeurl = $this->uri."/SEP/1.1/Update";
       // echo $completeurl;
		$response = $this->callAPI('PUT', $completeurl, $datastring, 'Application/x-www-form-urlencoded');
		return $response;
	}

	public function deleteSep($sep)
	{
		$datastring ='{
	       "request": {
	          "t_sep": {
	             "noSep": "'.$sep.'",
	             "user": "'.loginData('name').'"
	          }
	       }
	    }';

	    $completeurl = $this->uri."/SEP/Delete";
	    // print_r($datastring);
	   // echo $completeurl;
		$response = $this->callAPI('DELETE', $completeurl, $datastring, 'Application/x-www-form-urlencoded');
		return $response;
	}

	public function updateTanggalPulang($no_sep, $tgl)
	{
		$datastring ='{
	       "request": {
	          "t_sep": {
	            "noSep": "'.$no_sep.'",
	            "tglPulang": "'.$tgl.'",
	            "user": "'.loginData('name').'",
	          }
	       }
	    }';

	    $completeurl = $this->uri."/SEP/updtglplg";
		$response = $this->callAPI('PUT', $completeurl, $datastring, 'Application/x-www-form-urlencoded');
		return $response;
	}

	public function pengajuanSEP()
	{
		$datastring ='{
	       "request": {
	          "t_sep": {
	            "noKartu": "'.$_POST['noKartu'].'",
	            "tglSep": "'.$_POST['tglSep'].'",
	            "jnsPelayanan": "'.$_POST['jnsPelayanan'].'",
	            "keterangan": "'.$_POST['keterangan'].'",
	            "user": "'.loginData('name').'"
	          }
	       }
	    }';

	  $completeurl = $this->uri."/SEP/pengajuanSEP";
		$response = $this->callAPI('POST', $completeurl, $datastring, 'Application/x-www-form-urlencoded');
		return $response;
	}

	public function cariSEP($no_sep)
	{
		$url = $this->conf->dep_alamat_ip_peserta; //"http://dvlp.bpjs-kesehatan.go.id:8081/Vclaim-rest";
		$completeurl = "$url/SEP/".$no_sep;

		$response = $this->callAPI('GET', $completeurl, false, 'application/json'); //kirim request
		return $response;
	}

	public function cekRujukan($key, $faskes, $tipe_param)
	{
		switch($faskes) {
			case 1:
			  if($tipe_param == '2'):
			  	$completeurl = "$this->uri/Rujukan/Peserta/".$key;
			  else:
			  	$completeurl = "$this->uri/Rujukan/".$key;
			  endif;
			break;
			case 2:
			  if($tipe_param == '2'):
			  	$completeurl = "$this->uri/Rujukan/RS/Peserta/".$key;
			  else:
			  	$completeurl = "$this->uri/Rujukan/RS/".$key;
			  endif;
			break;
			default:
			$completeurl = "$this->uri/Rujukan/".$key;
		}

		//return $completeurl;
		$response = $this->callAPI('GET', $completeurl, false, 'application/json'); //kirim request
		return $response;
	}

	public function listRujukan($key)
	{
		$completeurl = "$this->uri/Rujukan/RS/List/Peserta/".$key;
		$response = $this->callAPI('GET', $completeurl, false, 'application/json'); //kirim request
		return $response;
	}

	public function refPoli($param)
	{
		$completeurl = "$this->uri/referensi/poli/".$param;

		$response = $this->callAPI('GET', $completeurl, false, 'application/json'); //kirim request
		return $response;
	}

	public function refDiagnosa($param)
	{
		$completeurl = "$this->uri/referensi/diagnosa/".$param;

		$response = $this->callAPI('GET', $completeurl, false, 'application/json'); //kirim request
		return $response;
	}

	public function sepSuplesi($noKartu, $tglPelayanan)
	{
		$completeurl = "$this->uri/sep/JasaRaharja/Suplesi/".$noKartu."/tglPelayanan/".date_db($tglPelayanan);

		$response = $this->callAPI('GET', $completeurl, false, 'application/json'); //kirim request
		return $response;
	}

	public function refFaskes($param, $jenis)
	{
		$completeurl = "$this->uri/referensi/faskes/".$param."/".$jenis;

		$response = $this->callAPI('GET', $completeurl, false, 'application/json'); //kirim request
		return $response;
		
	}

	public function refRuangRawat()
	{
		$url = $this->conf->dep_alamat_ip_peserta; //"http://dvlp.bpjs-kesehatan.go.id:8081/Vclaim-rest";
		$completeurl = "$url/referensi/ruangrawat";
		
		$response = $this->callAPI('GET', $completeurl, false, 'application/json'); //kirim request
		return $response;
	}

	public function refKelasRawat()
	{
		$url = $this->conf->dep_alamat_ip_peserta; //"http://dvlp.bpjs-kesehatan.go.id:8081/Vclaim-rest";
		$completeurl = "$url/referensi/kelasrawat";

		$response = $this->callAPI('GET',$completeurl, false, 'application/json'); //kirim request
		return $response;
	}

	public function refPropinsi()
	{
		$completeurl = "$this->uri/referensi/propinsi";

		$response = $this->callAPI('GET', $completeurl, false, 'application/json'); //kirim request
		return $response;
	}

	public function refKabupaten($propinsi)
	{
		$completeurl = "$this->uri/referensi/kabupaten/propinsi/".$propinsi;

		$response = $this->callAPI('GET',$completeurl, false, 'application/json'); //kirim request
		return $response;
	}

	public function refKecamatan($kabupaten)
	{
		$completeurl = "$this->uri/referensi/kecamatan/kabupaten/".$kabupaten;

		$response = $this->callAPI('GET',$completeurl, false, 'application/json'); //kirim request
		return $response;
	}

	public function refDPJP($jnsPelayanan, $tglPelayanan, $kodeSpesialis='')
	{
		$completeurl = "$this->uri/referensi/dokter/pelayanan/$jnsPelayanan/tglPelayanan/".date_db($tglPelayanan)."/Spesialis/$kodeSpesialis";

		//return $completeurl;
		$response = $this->callAPI('GET',$completeurl, false, 'application/json'); //kirim request
		return $response;
	}

	public function rujukanStore($data)
	{
		$datastring ='                                                    
	    {
	       "request": {
	          "t_rujukan": {
	             "noSep": "'.$data["no_sep"].'",
	             "tglRujukan": "'.date_db($data["tgl_rujukan"]).'",
	             "ppkDirujuk": "'.$data["ppk_dirujuk"].'",
	             "jnsPelayanan": "'.$data["jns_pelayanan"].'",
	             "catatan": "'.$data["catatan"].'",
	             "diagRujukan": "'.$data["diag_rujukan"].'",
	             "tipeRujukan": "'.$data["tipe_rujukan"].'",
	             "poliRujukan": "'.$data["poli_rujukan"].'",
	             "user": "'.loginData('name').'"
	          }
	       }
	    }';

	    $completeurl = $this->uri."/Rujukan/insert";
		$response = $this->callAPI('POST', $completeurl, $datastring, 'Application/x-www-form-urlencoded');
		return $response;
	}

	public function rujukanDestroy($x)
	{
		$datastring ='{
	       "request": {
	          "t_rujukan": {
	             "noRujukan": "'.$x.'",
	             "user": "'.loginData('name').'"
	          }
	       }
	    }';

	    $completeurl = $this->uri."Rujukan/delete";

		$response = $this->callAPI('DELETE', $completeurl, $datastring, 'Application/x-www-form-urlencoded');
		return $response;
	}

}

/* End of file bpjs.php */
/* Location: .//D/RSPI/bpjs/controllers/bpjs.php */
