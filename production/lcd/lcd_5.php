<?php
     require_once("../penghubung.inc.php");
     require_once($LIB."login.php");
     require_once($LIB."encrypt.php");
     require_once($LIB."datamodel.php");
     require_once($LIB."tampilan.php");     
     require_once($LIB."currency.php");
     require_once($LIB."dateLib.php");   

     $dtaccess = new DataAccess();     
     $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
     $auth = new CAuth();    
     $depId = $auth->GetDepId();    

 
     
     
     // KONFIHURASI
     $sql = "select * from global.global_departemen where dep_id =".QuoteValue(DPE_CHAR,$depId);
     $rs = $dtaccess->Execute($sql);
     $konfigurasi = $dtaccess->Fetch($rs);  
     
     if ($konfigurasi["dep_height"]!=0) $panjang=$konfigurasi["dep_height"] ;
     if ($konfigurasi["dep_width"]!=0) $lebar=$konfigurasi["dep_width"] ;
     $fotoName = $ROOT."/gambar/img_cfg/".$konfigurasi["dep_logo"]; 
     $bg = $ROOT."/gambar/img_cfg/".$konfigurasi["dep_logo"];
     $lokasi = $ROOT."/gambar/img_cfg";
     $lokasiSikita = $ROOT."/gambar/";

?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<html>
<script src="<?php echo $ROOT;?>lib/script/antri/jquery.min.js"></script>
<script language="javascript">
var auto_refresh = setInterval(
function()
{
$('#loaddiv-1').fadeOut('slow').load('loket_lcd_5.php').fadeIn("slow");
//$('#loaddiv1').load('antri_loket_1.php');


}, 8000);

</script>
<script language="javascript">
function Logout()
{
    if(confirm('Are You Sure to LogOut?')) window.parent.document.location.href='<?php echo $ROOT;?>logout.php';
    else return false;
}
</script>                                                                                                   

<style type="text/css">
*{ font-weight: bold;}
body{ margin:0; padding:0; background: url(bg.jpg); background-size: 100%;-moz-background-size: 100%;}
#header{margin:0;  width:90%; height:90%;background: #001835;}
#kiri{width: 100%;  }
#tombol{ width: 48%; float: center; padding: 10px;}
.left{ width:270px; height:80px; background:url(<?php echo $lokasi."/".$konfigurasi["dep_logo_kiri_antrian"];?>)no-repeat; background-size: 230px 60px; float:left;position: absolute; left: 0; top: 10;}
.center{ max-width:100%; float:left; text-align:center; background: #000;}
.right{ width:230px; height:800px; background-size: 220px 76px; float:right; position: absolute; right: 0; top: 0;}
.nom, .nam{ float: center; line-height: 100px; font-size: 10em; font-weight: bold; padding-top: 10px; align: center;}
.nom{ width: 120px; text-align:center}
h1{ text-transform: uppercase; text-decoration: none; line-height: 80px; margin-right: 60px; color: #fff; font-size: 40px; font-weight: bold; text-align: center; border: none;}
marquee{ font-size: 60px; font-weight: bold; text-transform: uppercase; position: absolute ; bottom: 10; width: 100%;}
.nomor{f max-width: 100%; height: 90%; padding:4px; margin-bottom: 10px;  border: 2px solid #e0e0e0; border-radius: 15px; -moz-border-radius: 12px; background: #FFFFFF;
box-shadow: }
h3{color:#fff; margin:0;max-width: 100%; padding: 5px 10px; background: #193c5d; text-align: center; font-size: 45px;border-radius: 10px 10px 0 0; -moz-border-radius: 10px 10px 0 0; text-transform: uppercase;}
label{ position: fixed; padding-left: 20%;}
img.pp{ border-radius:0 0 10px 0; -moz-border-radius:0 0 10px 0; float: right; display: block; height: 100px; padding-top: 10px}
marquee{ position: absolute; bottom: 0; left: 0; color: #fff;}

.button, submit, reset { 
    display: inline-block; 
    outline: none; 
    cursor: pointer; 
    text-align: center; 
    text-decoration: none; 
    height: 50px
    width: 100px
    font: 20px/100% Arial, Helvetica, sans-serif; 
    padding: .5em 2em .55em; 
    text-shadow: 0 1px 1px rgba(0,0,0,.3); 
    -webkit-border-radius: .5em; 
    -moz-border-radius: .5em; 
    border-radius: .5em; 
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.2); 
    -moz-box-shadow: 0 1px 2px rgba(0,0,0,.2); 
    box-shadow: 0 1px 2px rgba(0,0,0,.2); 
} 
.button:hover { 
    text-decoration: none; 
} 
.button:active { 
    position: relative; 
    top: 1px; 
}
</style>

</head>

<body >   
<form name="frmView" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">


<div id="kiri">
  <div id="loaddiv-1" align="center" class="nomor"></div> 
</div> 
</form>
</body>
</html>



