<?php
     require_once("penghubung.inc.php");
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
     $user="its";
     $password="itsthok";
     $port="5432";
     $dbname="simpus_ngariboyo";

     $link = pg_connect("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password);
      
     $q = strtoupper($_GET["q"]);
     //echo $q;
     
      // nyari data ee dulu -- trus di while --
      $result = pg_query($link, "select cust_usr_kode,cust_usr_foto, cust_usr_nama, cust_usr_umur,cust_usr_alamat, kec_nama, kel_nama from global.global_customer_user a 
                                  left join global.global_kecamatan b on a.id_kecamatan = b.kec_id 
                                  left join global.global_kelurahan c on a.id_kelurahan = c.kel_id
                                  where UPPER(cust_usr_nama) like '".$q."%' ");   
       while($hasil = pg_fetch_assoc($result)) {
       if($hasil['cust_usr_foto']) $foto = $hasil['cust_usr_foto'];
       else $foto = "default.jpg";
       // tk masukkan array lagi --
       $umur=split('~',$hasil["cust_usr_umur"]);
       $hasilUmur=$umur[0]." th";
       $items = array($hasil['cust_usr_nama']=>$hasil['cust_usr_kode']."~".$hasil['cust_usr_alamat']."~".$hasilUmur."~".$hasil['kec_nama']."~".$hasil['kel_nama']."~".$foto);
       
       foreach ($items as $key=>$value) {
    	     if (strpos(strtoupper($key), $q) !== false) {
    		      //echo $items;
              echo "$key|$value\n";
    	     }
       }
       
       }

?>