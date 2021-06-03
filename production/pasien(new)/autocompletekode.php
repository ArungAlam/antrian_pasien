<?php
     require_once("../penghubung.inc.php");
     require_once($ROOT."lib/login.php");
     require_once($ROOT."lib/datamodel.php"); 
     require_once($ROOT."lib/tampilan.php");

     $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
	   $dtaccess = new DataAccess();
	   $enc = new textEncrypt();     
     $auth = new CAuth();
	   $depNama = $auth->GetDepNama();
	   $depId = $auth->GetDepId();
     
     
     $host="localhost";
     $user=$enc->Decode(DB_USER);
     $password=$enc->Decode(DB_PASSWORD);
     $port="5432";
     $dbname = DB_NAME;

     $link = pg_connect("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password);
      
     $q = strtoupper($_GET["q"]);
     //
     //echo $q;
     
      // nyari data ee dulu -- trus di while --
      $result = pg_query($link, "select cust_usr_id, cust_usr_nama, cust_usr_kode, cust_usr_alamat, cust_usr_tanggal_lahir from global.global_customer_user where UPPER(cust_usr_nama) like '%".$q."%' ");   
       while($hasil = pg_fetch_assoc($result)) {
    //   echo $result;
       // tk masukkan array lagi --
       $items = array($hasil['cust_usr_nama']=>$hasil['cust_usr_kode']."~".$hasil['cust_usr_id']."~".$hasil['cust_usr_tanggal_lahir']."~".$hasil['cust_usr_alamat']);       

       foreach ($items as $key=>$value) {
    	     if (strpos(strtoupper($key), $q) !== false) {
    		    //  echo $items;
              echo "$key|$value\n";
    	     }
       }
       
       }

?>
