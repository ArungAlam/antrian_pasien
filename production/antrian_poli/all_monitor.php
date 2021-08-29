<?php
 /** LIBRARY */
 require_once("../penghubung.inc.php");
 require_once($LIB."login.php");
 require_once($LIB."encrypt.php");
 require_once($LIB."datamodel.php");
 require_once($LIB."currency.php");

/** INITIAL LIBRARY  */
 $dtaccess = new DataAccess();
 $id_ruang = $_GET['id'];

 ?>

<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" type="text/css" href="assets/css/box_exp.css">
    <link rel="stylesheet" href="assets/css/adminlte.min.css">
    <body >
    <div class="col-md-12">
        <div class="headline">
          <div class=row>
            <div class="col-md-2">
              <img src="assets/img/logo_exp.png" alt="" class="gambar-logo">
            </div>
            <div class="col-md-2">
              <div class="col">
                 <a class="logo-name">EXPPRESSA</a>
              </div>
              <div class="col">
                 <a class="logo-name-detail">IT HEALTH CARE</a>
              </div>
            </div>
            <div class="col-md-3 offset-md-3 ">
              <div class="row">
                <div class="col-md-11 text-right">
                  <a class="headline-poli" id="nama_poli">Semua Monitor</a>
                </div>
                <div class="col-md-1">
                  <div class="verline"></div>
                </div>
              </div>
            </div>
            

            <div class="col-md-2">
              <div class="col">
               <a class="headline-poli">00:00:00</a>
              </div>
              <div class="col">
               <a class="headline-tgl">15 AGUSTUS 2021</a>
              </div>
            </div>

              

          </div>
        </div>   
      </div>
      </div>
    <div class="container">
      <!-- headline -->
      
      <div class="row">
      <div class="col-md">
        <div class="papan besar text-center">
            <div class="papan-atas">
              <h1 id="call1">012</h1>
            </div>
            <div class="papan-bawah">
              <h2  style="padding-top:20px !important"id="ruang1"> Ruang 1</h2>
              <h2 id="dokter1"></h2>
            </div>
          </div>
        </div>
        <div class="col-md">
                    <video controls
                width="100%"
                height="100%"
                muted>
                <source src="/media/cc0-videos/flower.webm"
                        type="video/webm">
                <source src="/media/cc0-videos/flower.mp4"
                        type="video/mp4">
                This browser does not support the HTML5 video element.
            </video>
        </div>
      </div>
    </div>
      
      <div class="col-md-12 down">
        <div class="row">
          <div class="col-md text-center">
            <div class="papan">
              <div class="papan-atas">
                <h4 id="call2"></h4>
              </div>
              <div class="papan-bawah">
              <h6 id="ruang2">Ruang 2</h6>
              <h6 id="dokter2"></h6>
              </div>
            </div>
          </div>
          <div class="col-md text-center">
            <div class="papan">
              <div class="papan-atas">
                <h4 id="call3"></h4>
              </div>
              <div class="papan-bawah">
              <h6 id="ruang3">Ruang 3</h6>
              <h6 id="dokter3"></h6>
              </div>
            </div>
          </div>
          <div class="col-md text-center">
            <div class="papan">
              <div class="papan-atas">
                 <h4 id="call4"></h4>
              </div>
              <div class="papan-bawah">
              <h6 id="ruang4"> Ruang 4</h6>
              <h6 id="dokter4"></h6>
              </div>
            </div>
          </div>
          <div class="col-md text-center">
            <div class="papan">
              <div class="papan-atas">
                <h4 id="call5"></h4>
              </div>
              <div class="papan-bawah">
              <h6 id="ruang5">Ruang 5</h6>
              <h6 id="dokter5"></h6>
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="col-md-12 text-berjalan" >
        <marquee behavior="" direction="left">Running Text</marquee>
      </div>
    
     <div class="clearfix"></div>
     <div>
     </div>
     <script type="text/javascript" src="assets/js/jquery.min.js"></script>
     <script>

        /*Tiap 5 detik Refresh  */
        $(document).ready(function() {
            setInterval(function() {

              get_antrian_all();
              
            }, 5000);

          });
        
        
          function get_antrian_all() {
            $.getJSON(`get_antrian_all.php`, function(nilai) {
              for (let index = 1; index < 6; index++) {
                $("#dokter"+index).html('');
                $("#ruang"+index).html('');
                $("#call"+index).html(''); 
              }
              $.each(nilai, function(index, val) {
                if(!val.call){
                  val.call = '--'
                };
                if(!val.next){
                  val.next = '--'
                };
                var inden = index+1;
                  $("#dokter"+inden).append(val.dokter);
                  $("#ruang"+inden).append(val.ruang);
                  $("#call"+inden).append(val.call); 
                });
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