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
  
  #config
  $sql = "SELECT * FROM global.global_departemen WHERE dep_id = '$depId'";
  $konfigurasi = $dtaccess->Fetch($sql);

  $sql_pasien = "SELECT a.*, b.*, c.poli_nama FROM klinik.klinik_registrasi a LEFT JOIN global.global_customer_user b ON a.id_cust_usr = b.cust_usr_id LEFT JOIN global.global_auth_poli c ON a.id_poli = c.poli_id WHERE reg_id = ".QuoteValue(DPE_CHAR, $_GET['id']);
  $data =  $dtaccess->Fetch($sql_pasien);
?>

<html>
  <head>
    <title>S E P</title>
  </head>
  <style>
    .judul{
      font-family: arial;
      font-size: 11px;
    }
    .isi{
      font-family: arial;
      font-size: 10px;
    }
  </style>
  <body onload="window.print(); window.close();">
    <table style="height: 6cm; width: 22cm;">
      <tbody> 
        <tr>
          <td rowspan="2"><img src="../gambar/bpjs.png" alt="BPJS" style="width: 150px; height: 30px;"></td>
          <td align="center" colspan="4" class="judul">SURAT ELIGIBILITAS PESERTA</td>
          <td rowspan="2" style="width: 4cm;">&nbsp;</td>
        </tr>
        <tr>
          <td align="center" colspan="4" class="judul"><?=$konfigurasi['dep_nama'] ?></td>
        </tr>
        <tr>
          <td valign="center" width="14%" class="isi">No. Kode RS</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="35%" class="isi"><?=$konfigurasi['dep_id_bpjs'];?></td>
          <td valign="center" width="14%" class="isi">Kelas RS</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="20%" class="isi"><b><?=$konfigurasi['dep_tipe_rs'];?></b></td>
        </tr>
        <tr>
          <td valign="center" width="14%" class="isi">No. SEP</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="35%" class="isi"><?= $data['reg_no_sep'];?></td>
          <td valign="center" width="14%" class="isi">No. RM</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="20%" class="isi"><b><?=$data['cust_usr_kode'];?><b></td>
        </tr>
        <tr>
          <td valign="center" width="14%" class="isi">Tgl. SEP</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="35%" class="isi"><?=date_db($data['reg_tanggal']) ?></td>
          <td valign="center" width="14%" class="isi">No. Reg</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="20%" class="isi"><?=$data['reg_kode_trans'];?></td>
        </tr>
        <tr>
          <td valign="center" width="14%" class="isi">No. Kartu</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="35%" class="isi"><?= $data['cust_usr_no_jaminan'];?></td>
          <td valign="center" width="14%" class="isi">Peserta</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="20%" class="isi">PBI</td>
        </tr>
        <tr>
          <td valign="center" width="14%" class="isi">Nama Peserta</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="35%" class="isi"><?=$data['cust_usr_nama'];?></td>
          <td valign="center" colspan="3"></td>
        </tr>
        <tr>
          <td valign="center" width="14%" class="isi">Tgl. Lahir</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="35%" class="isi"><?=date_db($data['cust_usr_tanggal_lahir']);?></td>
          <td valign="center" width="14%" class="isi">COB</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="20%" class="isi">Tidak</td>
        </tr>
        <tr>
          <td valign="center" width="14%" class="isi">Jns. Kelamin</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="35%" class="isi"><?=($data['cust_usr_jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan');?></td>
          <td valign="center" width="14%" class="isi">Jns. Rawat</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="20%" class="isi">Rawat Jalan</td>
        </tr>
        <tr>
          <td valign="center" width="14%" class="isi">Poli Tujuan</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="35%" class="isi"><?=$data['poli_nama'];?></td>
          <td valign="center" width="14%" class="isi">Kls. Rawat</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="20%" class="isi">3</td>
        </tr>
        <tr>
          <td valign="center" width="14%" class="isi">Asal Faskes</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="35%" class="isi"><?=$data['reg_ppk_rujukan'];?></td>
          <td valign="center" colspan="3"></td>
        </tr>
        <tr>
          <td valign="center" width="14%" class="isi">Diagnosa Awal</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="35%" class="isi"><?=$data['reg_diagnosa_awal'];?></td>
          <td align="center" class="isi" colspan="3">Pasien/ Keluarga Pasien</td>
        </tr>
        <tr>
          <td valign="center" width="14%" class="isi">Catatan</td>
          <td valign="center" width="1%" class="isi"> : </td>
          <td valign="center" width="20%" class="isi"></td>
          <td valign="center" colspan="3"></td>
        </tr>
        <tr>
          <td valign="center" colspan="6" class="isi"><sub><i>* Saya Menyetujui BPJS Kesehatan menggunakan informasi medis pasien jika diperlukan.</i></sub></td>
        </tr>
        <tr>
          <td valign="center" colspan="3" class="isi"><sub><i>* SEP bukan sebagai bukti penjaminan peserta.</i></sub></td>
          <td align="center" class="isi" colspan="3">______________________</td>
        </tr>
      </tbody>
    </table>
  </body>
</html>