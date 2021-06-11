<?php
  require_once("../penghubung.inc.php");
  require_once($LIB."login.php");
  require_once($LIB."encrypt.php");
  require_once($LIB."datamodel.php");
  require_once($LIB."currency.php");
  require_once($LIB."dateLib.php");

  $dtaccess = new DataAccess();
  
  $sql="select * from global.global_auth_user  where id_rol= 2 order
  by usr_name";
  $data = $dtaccess->FetchAll($sql);

  function generateRandomString($length) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
 }

  $jml = count($data);
 $string ='aaaa';
  for ($i=0; $i < $jml ; $i++) { 
    
    $pecah = explode(" ", $data[$i]['usr_name']);
    $kode1 = substr($pecah[0],0,1);     
    

      if(!$pecah[1]){
        $kode2 = substr($pecah[0],1,1); 
      } else{
        $kode2 = substr($pecah[1],0,1); 
      }
      if(!$pecah[2]){
        $kode3 = substr($pecah[0],2,1);
      }else{
        $kode3 = substr($pecah[2],0,1);
      }
     $fixkode = $kode1."". $kode2."".$kode3;
      /* jika data = dengan sebelum nya ambil kode ke 4 */
      if($string == $fixkode ){
        $kode2 = generateRandomString(1);
        $kode3 = generateRandomString(1);
        $fixkode =  $kode1."". $kode2."".$kode3;
      }

      echo strtoupper($fixkode);
      echo "<br/>";

      $sql="update global.global_auth_user 
      set  kode_nama=".QuoteValue(DPE_CHAR,strtoupper($fixkode))."
      where usr_id=".QuoteValue(DPE_CHAR,$data[$i]['usr_id']);
      $rs= $dtaccess->Execute($sql);
      $string =  $fixkode;
    }



?>