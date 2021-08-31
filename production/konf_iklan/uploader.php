<?php
    require_once("../penghubung.inc.php");
    require_once($LIB."login.php");

    require_once($LIB."encrypt.php");

    require_once($LIB."datamodel.php");
    require_once($LIB."dateLib.php");
    require_once($LIB."currency.php");                                                                  
    //require_once($LIB."tampilan.php");

    // $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
    $dtaccess = new DataAccess();
   
    $tglskr = date('Y-m-d');
    $jamskr = date("H:i:s");
    $uploadPath="../video_iklan/";


 


    /* insert foto  */
         $jam_tayang = $_POST['jam_tayang'];
         $nama_iklan = $_POST['nama_iklan'];
         $hariTayang = explode(",",$_POST['hari_tayang']);
         $nama_video_upload = $_FILES["files"]["name"];
         $raw_name   = pathinfo( $_FILES["files"]["name"], PATHINFO_FILENAME );
         $cek_date   =  date("Y_m_d-h_i_s");
         $extension  = pathinfo( $_FILES["files"]["name"], PATHINFO_EXTENSION ); // jpg
         $basename   =  $cek_date.'_' .$raw_name . '.' . $extension; // 5dab1961e93a7_1571494241.jpg
         move_uploaded_file($_FILES['files']['tmp_name'], $uploadPath."".$basename); 

			/* select count */
			$sql =  "select iklan_tayang_urut from global.global_video_iklan
							 order by iklan_tayang_urut desc";
			$lastUrut  = $dtaccess->Fetch($sql);
			$urutan = $lastUrut['iklan_tayang_urut'] + 1;
    /* insert tabel */

    foreach ($hariTayang as $key => $val) {

    
          $dbTable = "global.global_video_iklan";
					$dbField[0] = "iklan_id";
					$dbField[1] = "iklan_nama";
					$dbField[2] = "iklan_video_nama";
					$dbField[3] = "iklan_video_nama_upload";
					$dbField[4] = "iklan_tayang_hari";
					$dbField[5] = "iklan_tayang_jam";
					$dbField[6] = "iklan_tayang_urut";
					$dbField[7] = "id_dep";
						
					$dbValue[0] = QuoteValue(DPE_CHAR, $dtaccess->GetTransId());
					$dbValue[1] = QuoteValue(DPE_CHAR,$nama_iklan);
					$dbValue[2] = QuoteValue(DPE_CHAR,$basename);
					$dbValue[3] = QuoteValue(DPE_CHAR,$nama_video_upload);
					$dbValue[4] = QuoteValue(DPE_CHAR,$val);
					$dbValue[5] = QuoteValue(DPE_CHAR,$jam_tayang);
					$dbValue[6] = QuoteValue(DPE_NUMERIC,$urutan + $key );
					$dbValue[7] = QuoteValue(DPE_CHAR,'9999999');
          
					$dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
					$dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);

					$dtmodel->Insert();

				}

      /*  Notif Succes */
          $result = array(
            "success" => true
						 );
           echo json_encode($result);

?>