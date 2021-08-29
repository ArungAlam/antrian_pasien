<?php
  require_once("../penghubung.inc.php");
  require_once($LIB."/bit.php");
  require_once($LIB."/login.php");
  require_once($LIB."/encrypt.php");
  require_once($LIB."/datamodel.php");
  require_once($LIB."/dateLib.php");
  require_once($LIB."/currency.php");
  require_once($LIB."/tampilan.php");

  $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
  $dtaccess = new DataAccess();
  $enc = new textEncrypt();
  $auth = new CAuth();
  $err_code = 0;
  $userData = $auth->GetUserData();
  $userId = $auth->GetUserId();
  $userName = $auth->GetUserName();
  $depNama = $auth->GetDepNama();
  $depId = $auth->GetDepId();

  if($_GET["id_reg"] || $_GET["jenis"]  || $_GET["ket"] || $_GET["dis"] || $_GET["disper"] || $_GET["pembul"] || $_GET["total"]) {
    $sql = "select cust_usr_nama,cust_usr_kode, cust_usr_kode_tampilan, d.poli_nama,a.reg_tanggal, a.reg_waktu,
            a.id_poli,a.id_cust_usr ,a.reg_jenis_pasien , g.jkn_nama,i.jamkesda_kota_nama, 
            a.reg_tipe_layanan,a.reg_no_antrian,a.reg_when_update,a.reg_kode_trans,a.reg_kode_urut,a.reg_umur_hari,a.reg_umur_bulan,a.reg_umur,
            c.usr_name,e.jenis_nama,f.rujukan_nama, g.jkn_nama, h.perusahaan_nama
            from klinik.klinik_registrasi a join global.global_customer_user b on a.id_cust_usr = b.cust_usr_id
            left join global.global_auth_user c on c.usr_id = a.id_dokter 
            left join global.global_auth_poli d on a.id_poli = d.poli_id
            left join global.global_jenis_pasien e on a.reg_jenis_pasien = e.jenis_id
            left join global.global_rujukan f on a.reg_rujukan_id = f.rujukan_id
            left join global.global_jkn g on g.jkn_id = a.reg_tipe_jkn
            left join global.global_perusahaan h on h.perusahaan_id = a.id_perusahaan
            left join global.global_jamkesda_kota i on i.jamkesda_kota_id = a.id_jamkesda_kota
            where a.reg_id = ".QuoteValue(DPE_CHAR,$_GET["id_reg"])." and a.id_dep=".QuoteValue(DPE_CHAR,$depId);
    $dataPasien= $dtaccess->Fetch($sql);

    $_POST["id_reg"] = $_GET["id_reg"]; 
    $_POST["fol_jenis"] = $_GET["jenis"]; 
    $_POST["id_cust_usr"] = $dataPasien["id_cust_usr"];
    $_POST["cust_usr_kode"] = $dataPasien["cust_usr_kode"];
    $_POST["keterangan"] = $_GET["ket"];
    $_POST["diskon"] = $_GET["dis"];
    $_POST["diskonpersen"] = $_GET["disper"];
    $_POST["pembulatan"] = $_GET["pembul"];
    $_POST["total"] = $_GET["total"];
    $_POST["reg_jenis_pasien"] = $dataPasien["reg_jenis_pasien"];
    $_POST["reg_tanggal"] = $dataPasien["reg_tanggal"];

    $sql = "select b.poli_nama, reg_tanggal, reg_waktu from klinik.klinik_registrasi a
      left join global.global_auth_poli b on b.poli_id = a.id_poli
      where id_cust_usr = ".QuoteValue(DPE_CHAR,$dataPasien["id_cust_usr"])."
      and poli_tipe <> 'A'
      and date(reg_tanggal) < ".QuoteValue(DPE_DATE,$_POST["reg_tanggal"]);
    $sql .= " order by (reg_tanggal,reg_waktu) desc";
    $rs = $dtaccess->Execute($sql);
    $poliAkhir = $dtaccess->Fetch($rs);

    $sql = "select usr_name from klinik.klinik_folio a
            left join global.global_auth_user b on b.usr_id = a.who_when_update 
            where id_reg =".QuoteValue(DPE_CHAR,$_POST["id_reg"])." and a.id_dep =".QuoteValue(DPE_CHAR,$depId);
    $rs = $dtaccess->Execute($sql);
    $petugas = $dtaccess->Fetch($rs);
    
    $sql = "select * from global.global_jenis_pasien where jenis_flag = 'y' and jenis_id =".QuoteValue(DPE_NUMERIC,$_POST["reg_jenis_pasien"]);
    $rs = $dtaccess->Execute($sql);
    $jenisPasien = $dtaccess->Fetch($rs);

    $sql = "select a.*,b.usr_name as dokter_nama from klinik.klinik_folio a 
            left join global.global_auth_user b
            on a.id_dokter = b.usr_id where
            fol_jenis like '%T%' and id_reg = ".QuoteValue(DPE_CHAR,$_POST["id_reg"])." 
            and a.id_dep=".QuoteValue(DPE_CHAR,$depId);
    $dataFolio = $dtaccess->FetchAll($sql);
    
    $sql = "update klinik.klinik_registrasi set reg_tracer='y' where
            reg_id = ".QuoteValue(DPE_CHAR,$_GET["id_reg"]);
    $rs = $dtaccess->Execute($sql);

    $totalHarga=$dataFolio[0]["fol_total_harga"];
    
    $sql = "select * from klinik.klinik_pembayaran where pembayaran_jenis = 'T' and id_reg = ".QuoteValue(DPE_CHAR,$_POST["id_reg"])." and id_dep=".QuoteValue(DPE_CHAR,$depId);
    $dataDiskon = $dtaccess->Fetch($sql);
  } 

  /* KONIGURASI */
  $sql = "select * from global.global_departemen where dep_id =".QuoteValue(DPE_CHAR,$depId);
  $konfigurasi = $dtaccess->Fetch($sql);
  $lokasi = $ROOT."/gambar/img_cfg";  

  if ($konfigurasi["dep_height"]!=0) $panjang=$konfigurasi["dep_height"] ;
  if ($konfigurasi["dep_width"]!=0) $lebar=$konfigurasi["dep_width"] ;
  if ($konfigurasi["dep_logo"]!="n") {
    $fotoName = $lokasi."/".$konfigurasi["dep_logo"];
  } elseif($konfigurasi["dep_logo"]=="n") { 
    $fotoName = $lokasi."/default.jpg"; 
  } else { $fotoName = $lokasi."/default.jpg"; }
  /* KONIGURASI */
?>

<!DOCTYPE html>
<html>
<head>
  <title>Cetak Treacer</title>
  <style type="text/css">
    body {
      font-family: Verdana, Arial, Helvetica, sans-serif;
      font-size: 12px;
      margin: 0px;
      padding: 0px 0px 0px 0px;
      letter-spacing: 4px;
      page-break-inside: inherit;
    }

    #table1 {
      width: 100%;
    }

    td {
      font-family: Verdana, Arial, Helvetica, sans-serif;
      font-size: 10px;
			font-weight: bold;
    }
  </style>
</head>
<body onload="window.print();">
  <table style="width: 8cm">
    <tr><td>&nbsp;</td></tr>
    <tr><td>INSTALASI REKAM MEDIK</td></tr>
  </table>
  <table style="width: 8cm">
    <tr><td><?= $dataPasien['cust_usr_kode_tampilan'] ?></td></tr>
    <tr><td><?= $dataPasien['cust_usr_nama'] ?></td></tr>
    <tr><td><?= $dataPasien['cust_usr_alamat'] ?></td></tr>
    <tr><td><?= $dataPasien['jenis_nama'] ?></td></tr>
    <tr><td><?= $dataPasien['poli_nama'] ?></td></tr>
    <tr><td><?= $dataPasien['usr_name'] ?></td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td><?= date_db($dataPasien['reg_tanggal']).' / '.$dataPasien['reg_waktu'] ?></td></tr>
    <tr><td><?= date('H:i:s') ?></td></tr>
  </table>
  <?php exit() ?>
</body>
</html>