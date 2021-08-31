<?php
     require_once("../penghubung.inc.php");
     require_once($LIB."login.php");
     require_once($LIB."encrypt.php");
     require_once($LIB."datamodel.php");
     require_once($LIB."currency.php");
     require_once($LIB."dateLib.php");
     require_once($LIB."expAJAX.php");
     require_once($LIB."tampilan.php");		
     
     // INISIALISASY LIBRARY
     $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
     $dtaccess = new DataAccess();   
	   $auth = new CAuth();
	   $depNama = $auth->GetDepNama();
	   $userName = $auth->GetUserName();
	   $depId = $auth->GetDepId();
     $viewPage = "konfigurasi_antrian.php";

  if($_GET["id"]){

  $sql = "select video_antrian_nama from global.global_video_antrian where video_antrian_id = ".QuoteValue(DPE_CHAR,$_GET["id"]);
  $rs = $dtaccess->Execute($sql);
  $namavideodidel = $dtaccess->Fetch($rs);
 // echo $sql;

   $cmdhapus = "rm ";  

     //hapus file di linux
    if(strtoupper(substr(PHP_OS, 0, 3)) === 'LIN'){
   shell_exec($cmdhapus."".$_SERVER["DOCUMENT_ROOT"]."/rspi/production/lcd/".$namavideodidel["video_antrian_nama"]);
   
   //hapus selain linux
   }else{
  //  exec("del ".$_SERVER['SERVER_NAME']."display/module".$namavideodidel["video_bor_nama"]);
    exec("del ".$_SERVER["DOCUMENT_ROOT"]."/rspi/production/lcd/".$namavideodidel["video_antrian_nama"]);
   }

  $sql = "delete from global.global_video_antrian where video_antrian_id = ".QuoteValue(DPE_CHAR,$_GET["id"]);
  $rs = $dtaccess->Execute($sql);

     $sql = "select * from global.global_video_antrian where id_dep = ".QuoteValue(DPE_CHAR,$depId)." order by urutan asc";
     $rs = $dtaccess->Execute($sql);
     $dataVideoBOR = $dtaccess->FetchAll($rs);
  
      $xml= new SimpleXMLElement('<playlist></playlist>');
//    $xml->father['name']= 'Fathers name'; // creates automatically a father tag with attribute name

  for($i=0,$n=count($dataVideoBOR);$i<$n;$i++){
    $son= $xml->addChild('playitem'); // uses the first father tag
    $caption = explode(".",$dataVideoBOR[$i]["video_antrian_nama"]);
    
    $son['caption']= "".$caption[0]."";
    $son['path']= "".$dataVideoBOR[$i]["video_bor_nama"]."";
    $son['image']= "video.jpg";
    $son['options']= "";
    $son['clickurl']= "";
    $son['clicktarget']= "_blank";
    $son['endurl']= "";
    $son['styleoftarget']= "browser";
    $son['endtarget']= "";
    }

    
//$xml->formatOutput = true;
//echo "<xmp>". $xml->saveXML() ."</xmp>";
	 
$xml->asXML($ROOT."lcd/medialist.xml") or die("Error");

  header("Location:$viewPage");
//  exit;
  }   

?>