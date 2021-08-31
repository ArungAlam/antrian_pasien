<?php
     require_once("penghubung.inc.php");
     require_once($ROOT."lib/login.php");     
     require_once($ROOT."lib/datamodel.php");  
     require_once($ROOT."lib/tampilan.php");     

     $dtaccess = new DataAccess();     
     $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
     $auth = new CAuth();    
     $depId = $auth->GetDepId();    
     
   /*  if(!$auth->IsAllowed("antrian_pemeriksaan",PRIV_READ)){
          die("access_denied");
          exit(1);
     } else if($auth->IsAllowed("antrian_pemeriksaan",PRIV_READ)===1){
          echo"<script>window.parent.document.location.href='".$ROOT."login.php?msg=Login First'</script>";
          exit(1);
     }   */
     
     // KONFIHURASI
     $sql = "select * from global.global_departemen where dep_id =".QuoteValue(DPE_CHAR,$depId);
     $rs = $dtaccess->Execute($sql);
     $konfigurasi = $dtaccess->Fetch($rs);  
     
     if ($konfigurasi["dep_height"]!=0) $panjang=$konfigurasi["dep_height"] ;
     if ($konfigurasi["dep_width"]!=0) $lebar=$konfigurasi["dep_width"] ;
     $fotoName = $ROOT."/gambar/img_cfg/".$konfigurasi["dep_logo"]; 
     $bg = $ROOT."/gambar/img_cfg/".$konfigurasi["dep_logo"];
     $lokasi = $ROOT."/gambar/img_cfg";

?>
<?php echo $view->RenderBody("inventori.css",false,"ANTRIAN"); ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<html>
<script src="<?php echo $ROOT;?>lib/script/antri/jquery.min.js"></script>
<script>
var auto_refresh = setInterval(
function()
{
$('#loaddiv-1').fadeOut('slow').load('loket_4.php').fadeIn("slow");
//$('#loaddiv1').load('antri_loket_1.php');

$('#loaddiv-2').fadeOut('slow').load('loket_5.php').fadeIn('slow');
//$('#loaddiv2').load('antri_loket_2.php');

$('#loaddiv-3').fadeOut('slow').load('loket_6.php').fadeIn('slow');
//$('#loaddiv3').load('antri_loket_3.php');

//$('#loaddiv-4').fadeOut('slow').load('loket_4.php').fadeIn('slow');
//$('#loaddiv4').load('antri_loket_4.php');

}, 10000);

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
#header{margin:0;  width:100%; height:80px;background: #001835;}
#kiri{padding:10px; width: 48%; float: left;}
#kanan{ width: 48%; float: left; padding: 10px;}
#tombol{ width: 48%; float: left; padding: 10px;}
.left{ height:80px; width:60px;background:url('magetanlog.png')no-repeat; background-size: 60px 80px; float:left;position: absolute; left: 0; top: 0;}
.center{ max-width:100%; float:left; text-align:center; background: #000;}
.right{ height:80px; width:60px; background:url(<?php echo $lokasi."/".$konfigurasi["dep_logo_kanan_antrian"];?>) no-repeat;background-size: 60px 80px; float:right; position: absolute; right: 0; top: 0;}
.nom, .nam{ float: left; line-height: 100px; font-size: 50px; font-weight: bold; padding-top: 10px;}
.nom{ width: 120px; text-align: left;}
h1{ text-transform: uppercase; text-decoration: none; line-height: 80px; margin-right: 60px; color: #fff; font-size: 40px; font-weight: bold; text-align: center; border: none;}
marquee{ font-size: 50px; font-weight: bold; text-transform: uppercase; position: absolute ; bottom: 0; width: 100%;}
.nomor{ max-width: 100%; height: 165px; padding:2px; margin-bottom: 13px;  border: 1px solid #e0e0e0; border-radius: 10px; -moz-border-radius: 10px; background: #fafafa;
box-shadow: }
h3{color:#fff; margin:0;max-width: 100%; padding: 5px 10px; background: #193c5d; text-align: center; font-size: 40px;border-radius: 10px 10px 0 0; -moz-border-radius: 10px 10px 0 0; text-transform: uppercase;}
label{ position: fixed; padding-left: 45%;}
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
<div id="header">
  <div class="left">&nbsp;</div>
  <h1><?php echo $konfigurasi["dep_header_kanan_antrian"];?></h1>
  <div class="right">&nbsp;</div>
</div>

<div id="kiri">
  <div id="loaddiv-1" class="nomor"></div>
  <div id="loaddiv-2" align="center" class="nomor"></div> 
  <div id="loaddiv-3" class="nomor"><label><img src="<?php echo $ROOT;?>gambar/loadings.gif" alt="" /></label></div>
 <!--<div id="loaddiv-4" align="center" class="nomor"></div>-->
</div>
<div id="kanan">
<script type="text/javascript" src="swfobject.js"></script>
<div align="center" class="style3" id="flashcontent"></div>
<script type="text/javascript">
var so = new SWFObject('mwplayer.swf','player','600','540','9','#000000');
so.addParam('wmode','opaque');
so.addParam('quality','high');
so.addParam('allowfullscreen','true');
so.addParam('allowscriptaccess','always');
so.addParam('flashvars','playerOpts=pauseAtFirstFrame*false*b||autoChooseNext*true*b');
so.write("flashcontent");
</script>

</div>
<marquee><?php echo $konfigurasi["dep_footer_antrian"];?></marquee>
</form>
</body>
</html>
