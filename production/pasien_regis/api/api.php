<?php
require_once "helper.php";
class Bpjs {

	private $uri		= 'https://dvlp.bpjs-kesehatan.go.id/Vclaim-rest/';
	private $cons_id	= '24785';
	private $secret 	= '9gO4BA981';
	private $rs_code 	= '3573261';
	private $rs_ppkCode = '0212R014';

	public function signature()
	{
		date_default_timezone_set('UTC');
  		$time=time();

		$data = "$this->cons_id&$time";
   		$signature = base64_encode(hash_hmac('sha256', utf8_encode($data), utf8_encode($this->secret), true));

		return $signature;
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
		// #penjamin
		// if (count($this->reg_lakalantas_penjamin) > 0) {
		// 	$penjamin = implode(',', $this->reg_lakalantas_penjamin);
		// } else {
		// 	$penjamin = $this->reg_lakalantas_penjamin;
		// }
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
                        "penjamin": "'.null.'",
                        "tglKejadian": "'.null.'",
                        "keterangan": "'.null.'",
                        "suplesi": {
                            "suplesi": "0",
                            "noSepSuplesi": "'.null.'",
                            "lokasiLaka": {
                                "kdPropinsi": "'.null.'",
                                "kdKabupaten": "'.null.'",
                                "kdKecamatan": "'.null.'"
                                }
                        }
                    }
                 },
                 "skdp": {
                    "noSurat": "'.null.'",
                    "kodeDPJP": "'.null.'"
                 },
                 "noTelp": "'.$_POST["noTelp"].'",
                 "user": "test"
              }
           }
        }                 
        ';

      	// print_r($datastring); die;

        $completeurl = $this->uri."/SEP/1.1/insert";
       // echo $completeurl;
		$response = $this->callAPI('POST', $completeurl, $datastring, 'Application/x-www-form-urlencoded');
		return $response;
	}

	public function updateSepOld($sep)
	{
		#conf dep
		$url = $this->conf->dep_alamat_ip_peserta; //"http://dvlp.bpjs-kesehatan.go.id:8081/Vclaim-rest";

		$datastring ='{
	        "request": {
	          "t_sep": {
	             "noSep": "'.$sep.'",
	             "klsRawat": "'.$this->reg_kelas_rawat_bpjs.'",
	             "noMR": "'.$this->cust_usr_kode.'",
	             "rujukan": {
	                "asalRujukan": "'.$this->reg_tipe_faskes.'",
	                "tglRujukan": "'.$this->reg_tgl_rujukan.'",
	                "noRujukan": "'.$this->reg_no_rujukan.'",
	                "ppkRujukan": "'.$this->reg_ppk_rujukan.'"
	             },
	             "catatan": "'.$this->catatan_bpjs.'",
	             "diagAwal": "'.$this->reg_diagnosa_awal.'",
	             "poli": {
	                "eksekutif": "'.$this->reg_poli_eksekutif.'"
	             },
	             "cob": {
	                "cob": "'.$this->reg_cob.'"
	             },
	             "jaminan": {
	                "lakaLantas": "'.$this->reg_lakalantas.'",
	                "penjamin": "'.$this->reg_lakalantas_penjamin.'",
	                "lokasiLaka": "'.$this->reg_lakalantas_lokasi.'"
	             },
	             "noTelp": "'.$this->cust_usr_no_hp.'",
	             "user": "'.$this->usr_name.'"
	          }
	       }
	    }';

	    $completeurl = $url."/SEP/update";
		$response = $this->callAPI('PUT', $completeurl, $datastring, 'Application/x-www-form-urlencoded');
		return $response;
	}

	public function updateSEP($noSep)
	{
		#require
		$this->load->model('poli_klinik/poli_model', 'poli');
		#conf dep
		$url = $this->conf->dep_alamat_ip_peserta; //"http://dvlp.bpjs-kesehatan.go.id:8081/Vclaim-rest";

		#cek poli bpjs
		$select = array('poli_bpjs');
		$where = array('poli_id' => $this->id_poli);
		$poli = $this->poli->read($select, $where, null, null, null)->row();
		#poli eksekutif
		(!empty($this->reg_poli_eksekutif)) ? $rpe = $this->reg_poli_eksekutif : $rpe = '0';
	
        $datastring = 
        '                                            
	    {
	       "request": {
	          "t_sep": {
	             "noSep": "'.$noSep.'",
	             "klsRawat": "'.$this->reg_kelas_rawat_bpjs.'",
	             "noMR": "'.$this->cust_usr_kode.'",
	             "catatan": "'.$this->catatan_bpjs.'",
	             "diagAwal": "'.$this->reg_diagnosa_awal.'",
	             "poli": {
	                "eksekutif": "'.$this->reg_poli_eksekutif.'"
	             },
	             "cob": {
	                "cob": "'.$this->reg_cob.'"
	             },
	             "katarak":{
	                "katarak":"'.$this->reg_katarak.'"
	             },
	             "skdp":{
	                "noSurat":"",
	                "kodeDPJP":""            
	             },
	             "jaminan": {
	                "lakaLantas":"'.$this->reg_lakalantas.'",
	                "penjamin":
	                {
	                    "penjamin":"'.implode(",", $this->reg_lakalantas_penjamin).'",
	                    "tglKejadian":"'.$this->reg_lakalantas_tanggal.'",				
	                    "keterangan":"'.$this->reg_lakalantas_keterangan.'",
	                    "suplesi":
	                        {
	                            "suplesi":"'.$this->reg_lakalantas_suplesi.'",
	                            "noSepSuplesi":"'.$this->reg_lakalantas_suplesi_sep.'",
	                            "lokasiLaka": 
	                                {
	                                "kdPropinsi":"'.$this->reg_lakalantas_propinsi.'",
	                                "kdKabupaten":"'.$this->reg_lakalantas_kabupaten.'",
	                                "kdKecamatan":"'.$this->reg_lakalantas_kecamatan.'"
	                                }
	                        }					
	                }
	             },             
	             "noTelp": "'.$this->cust_usr_no_hp.'",
	             "user": "'.$this->usr_name.'"
	          }
	       }
	    }                               
        ';

      	print_r($datastring); die;

        $completeurl = $url."/SEP/1.1/Update";
       // echo $completeurl;
		$response = $this->callAPI('PUT', $completeurl, $datastring, 'Application/x-www-form-urlencoded');
		return $response;
	}

	public function deleteSep($sep)
	{
		#conf dep
		$url = $this->conf->dep_alamat_ip_peserta; //"http://dvlp.bpjs-kesehatan.go.id:8081/Vclaim-rest";

		$datastring ='{
	       "request": {
	          "t_sep": {
	             "noSep": "'.$sep.'",
	             "user": "'.$this->usr_name.'"
	          }
	       }
	    }';

	    $completeurl = $url."/SEP/delete";
		$response = $this->callAPI('DELETE', $completeurl, $datastring, 'Application/x-www-form-urlencoded');
		return $response;
	}

	public function updateTanggalPulang($no_sep)
	{
		#conf dep
		$url = $this->conf->dep_alamat_ip_peserta; //"http://dvlp.bpjs-kesehatan.go.id:8081/Vclaim-rest";

		$datastring ='{
	       "request": {
	          "t_sep": {
	            "noSep": "'.$no_sep.'",
	            "tglPulang": "'.$this->reg_tanggal_pulang.' '.$this->reg_waktu_pulang.'",
                "ppkPelayanan": "'.$this->conf->dep_kode_ppk.'",
	          }
	       }
	    }';

	    $completeurl = $url."/SEP/updtglplg";
		$response = $this->callAPI('PUT', $completeurl, $datastring, 'Application/x-www-form-urlencoded');
		return $response;
	}

	public function pengajuanSEP()
	{
		#conf dep
		$url = $this->conf->dep_alamat_ip_peserta; //"http://dvlp.bpjs-kesehatan.go.id:8081/Vclaim-rest";

		$datastring ='{
	       "request": {
	          "t_sep": {
	            "noKartu": "'.$this->cust_usr_no_jaminan.'",
	            "tglSep": "'.$this->reg_tgl_sep.'",
	            "jnsPelayanan": "'.$this->reg_jenis_layanan.'",
	            "keterangan": "'.$this->keterangan.'",
	            "user": "'.$this->usr_name.'"
	          }
	       }
	    }';

	    $completeurl = $url."/SEP/updtglplg";
		$response = $this->callAPI('PUT', $completeurl, $datastring, 'Application/x-www-form-urlencoded');
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

}

/* End of file bpjs.php */
/* Location: .//D/RSPI/bpjs/controllers/bpjs.php */
