<?php
 /** LIBRARY */
 require_once("../penghubung.inc.php");
 require_once($LIB."login.php");
 require_once($LIB."encrypt.php");
 require_once($LIB."datamodel.php");
 require_once($LIB."currency.php");
 require_once($LIB."expAJAX.php");

/** INITIAL LIBRARY  */
 $dtaccess = new DataAccess();
 $
 $lokasi_foto= '../../../bunda_sejati/production/img_dokter/';

 $id_ruang = $_GET['id'];

 $sql= "select ruangan_nama as nama from klinik.klinik_ruangan where ruangan_id ='".$id_ruang."'";
 $ruang = $dtaccess->Fetch($sql);


 ?>

<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" type="text/css" href="box.css">
    <link rel="stylesheet" type="text/css" href="bootstraps/bootstrap.min.css">
    <body >
        
           
    <div class="container">
      <div class="row">
        
        <div class="col-md align-items-center">
          <h2 class="down"><?=$ruang['nama']?></h2>
          <br/>
          <h2 id="nama_poli">Tidak ada</h2>
        </div>
        <div class="col-md">
              <img src="img-foto" id="foto_dokter" class="gambar">
        </div>
      </div>
    </div>
      <div class="col-md-12 dokter text-center">
         <h2 id="nama_dokter">Tidak ada</h2>
      </div>
      <div class="col-md-12 down">
        <div class="row">
          <div class="col-md text-center">
            <div class="col bulat">
              <h4>Antrian saat ini</h4>
            </div>
            <div class="col bulat">
              <h4 id="no_antrian">kosong</h4>
            </div>
          </div>

          <div class="col-md text-center">
            <div class="col bulat">
              <h4>Antrian Berikutnya</h4>
            </div>
            <div class="col bulat">
              <h4 id="no_antrian_next">kosong</h4>
            </div>
          </div>

          <div class="col-md text-center">
            <div class="col bulat">
              <h4>Selesai di layani</h4>
            </div>
            <div class="col bulat">
              <h4 id="jml_selesai">kosong</h4>
            </div>
          </div>

        </div>
      </div>
      
    
    <form name="frmEdit" action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST" class="">
    <!-- row -->
    
     <div class="clearfix"></div>
     <div>
     <input type="hidden" id="reset" name="reset" value="">
     <input type="hidden" id="id_poli" name="id_poli" value="<?=$_GET['id_poli']?>">
     <input type="hidden" id="id_ruang" name="id_ruang" value="<?=$_GET['id']?>">
     <input type="hidden" id="id_dokter"  value="">
     </form>
     </div>

      <!-- Sound -->  
      <div>
        <audio id="myAudio"  src="1.ogg"></audio>
      </div>
      <!-- Sound -->


     <script type="text/javascript" src="jquery.min.js"></script>
     <script type="text/javascript" src="bootstraps/bootstrap.min.js"></script>
      <script>

        
        function reset(){
          $('#reset').val('oke');

        }
        function blinkFont()
        {
        $(".blink-on").css("color","blue");
        $(".blink-on").css("background-color","white");
        setTimeout("setblinkFont()",500)
        }

        function setblinkFont()
        {
        $(".blink-on").css("color","white");
        $(".blink-on").css("background-color","blue");
        setTimeout("blinkFont()",500)
        }

        /*Tiap 10 detik Refresh  */
        $(document).ready(function() {
            setInterval(function() {
              cari_poli();
              ambil_data_call();
              ambil_data_next();
              ambil_data_layani();
            }, 10000);

          });
        
        
          function ambil_data_call() {
            var id_poli = $('#id_poli').val();
            var id_dokter = $('#id_dokter').val();
            $.getJSON(`get_pasien_panggil.php?id_poli=${id_poli}&id_dokter=${id_dokter}`, function(nilai) {
              // console.log(nilai.no_antrian_pasien);
                if(nilai){

                  var LongNomor = nilai.no_antrian_pasien.length;
                  var nol = '0';
                  if(LongNomor == '1' ){  
                    var no_antrian = nol.concat(nilai.no_antrian_pasien);
                  }else{
                    var no_antrian = nilai.no_antrian_pasien;
                  }

                  
                  $('#no_antrian').html(`Antrian No. <b>${no_antrian} </b>`)
                  /* Script Sound bunyi 2 detik */
                  document.getElementById('myAudio').play();
                    setTimeout(function(){
                      document.getElementById('myAudio').pause();
                      document.getElementById('myAudio').currentTime = 0;
                         }, 2000);
                  
                }else{
                  ambil_sedang_layani();
                }
             });
          }
          function ambil_data_next() {
            var id_poli = $('#id_poli').val();
            var id_dokter = $('#id_dokter').val();
            $.getJSON(`get_pasien_next.php?id_poli=${id_poli}&id_dokter=${id_dokter}`, function(nilai) {
              // console.log(nilai.no_antrian_pasien);
                if(nilai){

                  var LongNomor = nilai.no_antrian_pasien.length;
                  var nol = '0';
                  if(LongNomor == '1' ){  
                    var no_antrian = nol.concat(nilai.no_antrian_pasien);
                  }else{
                    var no_antrian = nilai.no_antrian_pasien;
                  }

                  
                  $('#no_antrian_next').html(`Antrian No. <b>${no_antrian} </b>`)
             
                }else{
                  $('#no_antrian_next').html(`--`)
                }
            });
          }

          function ambil_sedang_layani() {
            var id_poli = $('#id_poli').val();
            var id_dokter = $('#id_dokter').val();
            $.getJSON(`get_sedang_layani.php?id_poli=${id_poli}&id_dokter=${id_dokter}`, function(nilai) {
              // console.log(nilai.no_antrian_pasien);
                if(nilai){

                  var LongNomor = nilai.no_antrian_pasien.length;
                  var nol = '0';
                  if(LongNomor == '1' ){  
                    var no_antrian = nol.concat(nilai.no_antrian_pasien);
                  }else{
                    var no_antrian = nilai.no_antrian_pasien;
                  }

                  
                  $('#no_antrian').html(`Antrian No. <b>${no_antrian} </b>`)
             
                }else{
                  $('#no_antrian').html(`--`)
                }
            });
          }

          function cari_poli(){
            var id_ruang = $('#id_ruang').val();
               $.getJSON('get_poli.php?id_ruangan='+id_ruang, function(nilai) {
                  if(nilai){
                    $('#id_poli').val(nilai.poli_id);
                    $('#nama_dokter').html(nilai.usr_name);
                    $('#nama_poli').html(nilai.poli_nama);
                    $('#id_dokter').val(nilai.usr_id);
                    var lokasi ='<?= $lokasi_foto?>';
                    var img = lokasi+''+nilai.foto;

                    $('#foto_dokter').attr('src',img );

                  }else{
                    $('#id_poli').val('');
                    $('#nama_dokter').html('Tidak sedang di gunakan');
                    $('#nama_poli').html('Tidak sedang di gunakan');
                    $('#id_dokter').val('');

                  }
                

            });
              

          }

          function ambil_data_layani() {
            var id_poli = $('#id_poli').val();
            var id_dokter = $('#id_dokter').val();
            $.getJSON(`get_sudah_dilayani.php?id_poli=${id_poli}&id_dokter=${id_dokter}`, function(nilai) {
                if(nilai){

                  $('#jml_selesai').html(`${nilai.jml}`)
             
                }
            });
              
          }
     </script>
  </body>
  <!-- <footer>
    <div class="text-right lisensi">
    <a href="http://www.freepik.com">Designed by Freepik</a>
    </div>
  </footer> -->
</html>