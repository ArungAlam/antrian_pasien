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
  $tablePerawatanMsk = new InoTable("table","100%","left");
  $auth = new CAuth();

  $sql = "SELECT * FROM klinik.klinik_reg_antrian_reguler WHERE id_poli = '1' AND antri_aktif = 'y' AND reg_antri_tanggal = ".QuoteValue(DPE_DATE, date('Y-m-d'))." ORDER BY reg_antri_suara DESC, reg_antri_nomer DESC";
  $rs = $dtaccess->Execute($sql);
  $data = $dtaccess->Fetch($rs);

  $panggil = $data['reg_panggil'];

  if ($panggil == 'n') {
    $sql = "UPDATE klinik.klinik_reg_antrian_reguler SET reg_panggil = 'y' WHERE reg_antri_id = ".QuoteValue(DPE_CHAR, $data["reg_antri_id"]);
    // $dtaccess->Execute($sql);
  }
?>

<html>
  <head>
  </head>
  <body>                                                
    <div width="100%">
      <h3> BPJS</h3>
      <font color='black' align="center">
        <div class="nam"><?= $data['reg_antri_suara'].sprintf("%03d", $data['reg_antri_nomer']) ?></div>
      </font>
    </div>
    <script src="<?php echo $ROOT;?>lib/script/antri/jquery.min.js"></script>
    <script type="text/javascript">
      function panggil(isi) {
        var suara = document.createElement('audio');
        suara.setAttribute('src', 'suara/'+isi+'.wav');
        
        suara.addEventListener('ended', function() {
          this.play();
        }, false);

        setTimeout(function(){
          suara.play();

          setTimeout(function(){
            suara.pause();
            suara.currentTime = 0;
          }, 800);
        }, 500);
      }

      $(function () {
        if ('<?= $panggil ?>' == 'n') {
          var isi =  '<?= sprintf("%03d", $data['reg_antri_nomer']) ?>';
          var hasil = isi.split("");
          $.each(hasil, function(index, val) {
            setTimeout( function() {
              panggil(val);
            }, index * 1000 );
          });
        }
      });
    </script>
  </body>
</html>