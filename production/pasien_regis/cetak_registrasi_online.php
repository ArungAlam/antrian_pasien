<?php
  require_once("../penghubung.inc.php");
  require_once($LIB."login.php");
  require_once($LIB."encrypt.php");
  require_once($LIB."datamodel.php");
  require_once($LIB."tampilan.php");     
  require_once($LIB."currency.php");
  require_once($LIB."dateLib.php");

  $dtaccess = new DataAccess();
  $enc = new textEncrypt();                                 
  $auth = new CAuth();
  $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
  // $depId = $auth->GetDepId();
  $depNama = $auth->GetDepNama();
  $depId = '9999999';

  $sql = "SELECT a.reg_buffer_tanggal, b.cust_usr_nama, b.cust_usr_kode, b.cust_usr_tanggal_lahir, c.poli_nama, d.usr_name FROM klinik.klinik_registrasi_buffer a LEFT JOIN global.global_customer_user b ON a.id_cust_usr = b.cust_usr_id LEFT JOIN global.global_auth_poli c ON a.id_poli = c.poli_id LEFT JOIN global.global_auth_user d ON a.id_dokter = d.usr_id WHERE a.reg_buffer_status = 'n' AND a.reg_buffer_id = ".QuoteValue(DPE_CHAR, $_GET['id'])." AND a.reg_buffer_tanggal >= ".QuoteValue(DPE_CHAR, date('Y-m-d'));
  $data = $dtaccess->Fetch($sql);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Cetak Registrasi Online</title>
  </head>
  <script type="text/javascript">
    window.print();
    window.close();
  </script>
  <body>
    <hr style="width:100%;">
    <center>
      <table style="width: 6cm;">
        <tbody>
          <tr>
            <td style="font-size: 14px" align="center" colspan="3"><u>REGISTRASI ONLINE</u></td>
          </tr>
          <tr>
            <td style="font-size: 11px" width="31%">Nama Pasien</td>
            <td style="font-size: 11px" width="9%"> : </td>
            <td style="font-size: 11px" width="60%"><?=$data['cust_usr_nama']?></td>
          </tr>
          <tr>
            <td style="font-size: 11px">No RM</td>
            <td style="font-size: 11px"> : </td>
            <td style="font-size: 11px"><?=$data['cust_usr_kode']?></td>
          </tr>
          <tr>
            <td style="font-size: 11px">Tanggal Lahir</td>
            <td style="font-size: 11px"> : </td>
            <td style="font-size: 11px"><?=date_db($data['cust_usr_tanggal_lahir'])?></td>
          </tr>
          <tr>
            <td style="font-size: 11px">Klinik</td>
            <td style="font-size: 11px"> : </td>
            <td style="font-size: 11px"><?=$data['poli_nama']?></td>
          </tr>
          <tr>
            <td style="font-size: 11px">Dokter</td>
            <td style="font-size: 11px"> : </td>
            <td style="font-size: 11px"><?=$data['usr_name']?></td>
          </tr>
          <tr>
            <td style="font-size: 11px">Waktu</td>
            <td style="font-size: 11px"> : </td>
            <td style="font-size: 11px"><?=date_db($data['reg_buffer_tanggal'])?></td>
          </tr>
        </tbody>
      </table>
    </center>
    <hr style="width: 100%;">
  </body>
</html>