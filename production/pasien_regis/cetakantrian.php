<?php
require_once("../penghubung.inc.php");
require_once($LIB . "login.php");
require_once($LIB . "encrypt.php");
require_once($LIB . "datamodel.php");
require_once($LIB . "tampilan.php");
require_once($LIB . "currency.php");
require_once($LIB . "dateLib.php");

$dtaccess = new DataAccess();
$enc = new textEncrypt();
$auth = new CAuth();
$view = new CView($_SERVER['PHP_SELF'], $_SERVER['QUERY_STRING']);
// $depId = $auth->GetDepId();
$depNama = $auth->GetDepNama();
$depId = '9999999';

$sql = "select max(reg_antri_nomer) as nomore from klinik.klinik_reg_antrian_reguler where id_poli='1' and id_dep = " . QuoteValue(DPE_CHAR, $depId);
$noAntrian = $dtaccess->Fetch($sql);
$noantri =  ($noAntrian["nomore"] + 1);

if ($noantri < $konf["dep_no_urut_antrian_loket_satu"]) $noantri = $noantri + $konf["dep_no_urut_antrian_loket_satu"];

$dbTable = "klinik.klinik_reg_antrian_reguler";

$dbField[0] = "reg_antri_id";   // PK
$dbField[1] = "reg_antri_nomer";
$dbField[2] = "id_cust_usr";
$dbField[3] = "id_dep";
$dbField[4] = "id_poli";
$dbField[5] = "reg_antri_suara";
$dbField[6] = "reg_antri_tanggal";
$dbField[7] = "id_loket";

$byrId = $dtaccess->GetTransID();
$dbValue[0] = QuoteValue(DPE_NUMERIC, $noantri);
$dbValue[1] = QuoteValue(DPE_NUMERIC, $noantri);
$dbValue[2] = QuoteValue(DPE_CHAR, '');
$dbValue[3] = QuoteValue(DPE_CHAR, $depId);
$dbValue[4] = QuoteValue(DPE_CHAR, 1);
$dbValue[5] = QuoteValue(DPE_CHAR, '0');
$dbValue[6] = QuoteValue(DPE_DATE, date('Y-m-d'));
$dbValue[7] = QuoteValue(DPE_CHAR, "1");

$dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
$dtmodel = new DataModel($dbTable, $dbField, $dbValue, $dbKey);

$dtmodel->Insert() or die("insert  error");

unset($dbField);
unset($dtmodel);
unset($dbValue);
unset($dbKey);

//INSERT KE REKAP ANTRIAN BPJS BARU 
$dbTable = "klinik.klinik_rekap_antrian";

$dbField[0] = "rekap_antrian_id";   // PK
$dbField[1] = "id_jenis_pasien";
$dbField[2] = "rekap_antrian_jenis_pasien";
$dbField[3] = "rekap_antrian_tanggal";
$dbField[4] = "rekap_antrian_waktu";

$antrianId = $dtaccess->GetTransID();
$dbValue[0] = QuoteValue(DPE_CHAR, $antrianId);
$dbValue[1] = QuoteValue(DPE_NUMERIC, 5);
$dbValue[2] = QuoteValue(DPE_CHAR, 'B');
$dbValue[3] = QuoteValue(DPE_DATE, date('Y-m-d'));
$dbValue[4] = QuoteValue(DPE_DATE, date('H:i:s'));

$dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
$dtmodel = new DataModel($dbTable, $dbField, $dbValue, $dbKey);

$dtmodel->Insert() or die("insert  error");

unset($dbField);
unset($dtmodel);
unset($dbValue);
unset($dbKey);
?>

<!DOCTYPE html>
<html>

<head>
  <title>Cetak Antrian Pasien</title>
</head>
<style type="text/css">
  body {
    font-family: Arial, Verdana, Helvetica, sans-serif;
    margin: 0px;
    font-size: 50px;
  }

  table {
    font-size: 12px;
  }
</style>
<script type="text/javascript">
  window.print();
  window.onfocus = function() {
     window.close();
  }
</script>

<body>
  <table border="0">
    <tr>
      <td valign="top">
          <table style="border:0px solid black; width:8cm; height:5cm;">
            <tr>
              <td align="center" style="font-size:12px;" colspan="3"><?php echo " NOMOR ANTRIAN ANDA " ?></td>
            </tr>
            <tr>
            </tr>
            <tr>
              <td align="center" style="font-size:80px;" colspan="2"><?php echo $noantri; ?></td>
            </tr>
					</td>
    </tr>
    <tr>
    <tr>
      <td align="center" style="font-size:12px;" colspan="3"><?php echo date("d-m-Y H:i:s"); ?></td>
    </tr>
    <tr>
      <td align="center" style="font-size:10px;">Silahkan Menunggu Nomor Antrian Anda Dipanggil<br><br></td>
    </tr>
   
  </table>


  </div>
  </td>
  </tr>
  </table>


</body>

</html>