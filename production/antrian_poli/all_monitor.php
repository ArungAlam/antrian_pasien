<?php
 /** LIBRARY */
 require_once("../penghubung.inc.php");
 require_once($LIB."login.php");
 require_once($LIB."encrypt.php");
 require_once($LIB."datamodel.php");
 require_once($LIB."currency.php");
 require_once($LIB."tampilan.php");
 require_once($LIB."expAJAX.php");

/** INITIAL LIBRARY  */
 $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
 $dtaccess = new DataAccess();
 $enc = new textEncrypt();     
 $auth = new CAuth();
 $table = new InoTable("table","100%","left");
 $tables = new inoTable("table","800","left");
 $depId = $auth->GetDepId();
 $depNama = $auth->GetDepNama();
 $userName = $auth->GetUserName(); 
 $depLowest = $auth->GetDepLowest();
 $tahunTarif = $auth->GetTahunTarif();

 $id_ruang = $_GET['id'];



 ?>

<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" type="text/css" href="box.css">
    <link rel="stylesheet" type="text/css" href="bootstraps/bootstrap.min.css">
    <body >
      <div class="col-md-12 down">
        <div class="row">
          <!-- dokter -->
          <div class="col-md-4 text-center">
            <div class="col bulat">
              <h4> Nama Dokter </h4>
            </div>
            <div id="dokter" class="down">
              
            </div>
          </div>
          <!--Ruang  -->
          <div class="col-md-3 text-center">
            <div class="col bulat">
              <h4> Ruang </h4>
            </div>
            <div id="ruang"  class="down">
              
            </div>
          </div>
          <!-- CAll -->
          <div class="col-md-2 text-center">
            <div class="col bulat">
              <h4>Antrian kini </h4>
            </div >
            <div id="call"  class="down">

            </div>
          </div>
          <!-- CALL -->
          <div class="col-md-3 text-center">
            <div class="col bulat">
              <h4>Antrian berikutnya</h4>
            </div>
            <div id="next"  class="down">

            </div>
            
          </div>

        </div>
      </div>
      
    
    <form name="frmEdit" action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST" class="">
    <!-- row -->
    
     <div class="clearfix"></div>
     <div>
     </div>

     <script type="text/javascript" src="jquery.min.js"></script>
     <script type="text/javascript" src="bootstraps/bootstrap.min.js"></script>
      <script>

        /*Tiap 5 detik Refresh  */
        $(document).ready(function() {
            setInterval(function() {

              get_antrian_all();
              
            }, 5000);

          });
        
        
          function get_antrian_all() {
            $.getJSON(`get_antrian_all.php`, function(nilai) {
              $("#dokter").html('');
              $("#ruang").html('');
              $("#next").html('');
              $("#call").html('');
              $.each(nilai, function(index, val) {
                if(!val.call){
                  val.call = '--'
                };
                if(!val.next){
                  val.next = '--'
                };
                  $("#dokter").append(
                    `<div class="col ">
                    <h4>${val.dokter}</h4>
                  </div>`);
                  $("#ruang").append(
                    `<div class="col ">
                    <h4>${val.ruang}</h4>
                  </div>`);
                  $("#call").append(
                    `<div class="col ">
                    <h4>${val.call}</h4>
                  </div>`);
                  $("#next").append(
                    `<div class="col ">
                    <h4>${val.next}</h4>
                  </div>`);


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