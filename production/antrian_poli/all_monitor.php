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

  $sql = "select * from global.global_departemen where dep_id =".QuoteValue(DPE_CHAR,$depId);
  $rs = $dtaccess->Execute($sql);
  $konfigurasi = $dtaccess->Fetch($rs);

  $bg = $ROOT."/gambar/img_cfg/".$konfigurasi["dep_logo"];

  $sql= "select * from global.global_video_antrian order by urutan asc";
  $video = $dtaccess->Fetch($sql);

  $videoSrc = $video['video_antrian_nama'];
  $videopertama =  $video['video_antrian_id'];

  $sql= "select * from global.global_video_antrian order by urutan asc";
  $videos = $dtaccess->FetchAll($sql);

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
               <a class="headline-poli" id="jam">00:00:00</a>
              </div>
              <div class="col">
               <a class="headline-tgl" id="day">15 AGUSTUS 2021</a>
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
            <video id="myVideo" controls autoplay style="width: 100%; max-height:550px" >
              <source  id="mp4Source" src="<?php echo $videoSrc?>" type="video/mp4">
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
     <script type="text/javascript" src="assets/js/moment.js"></script>
     <script>

        /*Tiap 5 detik Refresh  */
        $(document).ready(function() {
            
            /* antrian_poli  */
            setInterval(function() {
              get_antrian_all();
            }, 5000);
            /* konfig video */
            var vid = document.getElementById("myVideo");
                vid.muted = false;
                  console.log("bersuara"); 
            setTimeout(() => {
              console.log("muted");
                  vid.muted = true;
						}, 300);


            /* moment js */
            setInterval(() => {
              var now = moment();
              var day = now.format('Do MMMM YYYY')
              var jam = now.format('HH:mm:ss');
              $('#jam').html(jam);
              $('#day').html(day);
					
			      });

          });

          var videos = [];
          <?php foreach ($videos as $key => $value) { ?>
            videos.push('<?=$value['video_antrian_nama']?>');
          <?php } ?>
          var count = 0 ;
          var max_list = videos.length - 1; 
          var player = document.getElementById('myVideo');
          var mp4Vid = document.getElementById('mp4Source');
          player.addEventListener('ended', myHandler, false);

          function myHandler(e) {
            if (!e) {
              e = window.event;
            }
            count++;
            mp4Vid.src = videos[count] ;
            if(count == max_list){
              count = -1;
            }
            player.load();
            player.play();
          }
        
        
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