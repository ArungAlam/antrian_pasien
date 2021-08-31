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
    $uploadPath="../lcd/";



    /* insert foto  */
         $filename =  date("h_i_s");
         $extension  = pathinfo( $_FILES["files"]["name"], PATHINFO_EXTENSION ); // jpg
         $basename   = $filename . '.' . $extension; // 5dab1961e93a7_1571494241.jpg
         move_uploaded_file($_FILES['files']['tmp_name'], $uploadPath."".$basename); 

			/* select count */
			$sql =  "select urutan from global.global_video_antrian 
							 order by urutan desc";
			$lastUrut  = $dtaccess->Fetch($sql);
			$urutan = $lastUrut['urutan'] + 1;
    /* insert tabel */

          $dbTable = "global.global_video_antrian";
					$dbField[0] = "video_antrian_id";
					$dbField[1] = "video_antrian_nama";
					$dbField[2] = "id_dep";
					$dbField[3] = "urutan";
						
					$dbValue[0] = QuoteValue(DPE_CHAR, $dtaccess->GetTransId());
					$dbValue[1] = QuoteValue(DPE_CHAR,$basename);
					$dbValue[2] = QuoteValue(DPE_CHAR,'9999999');
					$dbValue[3] = QuoteValue(DPE_NUMERIC,$urutan);
					
					$dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
					$dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);

					 $dtmodel->Insert();

				

      /*  Notif Succes */
          $result = array(
            "success" => true
						 );
           echo json_encode($result);

?>