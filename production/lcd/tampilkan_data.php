<?php

     require_once("penghubung.inc.php");
     require_once($ROOT."lib/login.php");     
     require_once($ROOT."lib/datamodel.php"); 
     
     $dtaccess = new DataAccess();

     $sql = "select * from global.global_suara";
     $Datasuara = $dtaccess->FetchAll($sql);
     
     print_r($Datasuara);

?>
