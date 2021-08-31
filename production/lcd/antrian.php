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
  $auth = new CAuth();
  $depId = "9999999";

  // KONFIHURASI
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

<html>
  <head>
    <script src="<?php echo $ROOT;?>lib/script/antri/jquery.min.js"></script>
		<script src="terbilang.js"></script>
    <script type="text/javascript">
		 var delay = 2000;
      function panggil(isi) { 
        // var suara = document.createElement('audio');
        // suara.setAttribute('src', 'suara/'+isi+'.wav');
        
        // suara.addEventListener('ended', function() {
        //   this.play();
        // }, false);

        // setTimeout(function(){
        //   suara.play();

        //   setTimeout(function(){
        //     suara.pause();
        //     suara.currentTime = 0;
        //   }, 800);
        // }, 100);\
        var audio = $("#audio")[0];
        var tracks = {
            list: isi, //Put any tracks you want in this array
            index: 0,
            next: function() {
                //if (this.index == this.list.length - 1) this.index = 0;
               // else {
                    this.index += 1;
               // }
            },
            play: function() {
                return this.list[this.index];
            }
        }

        audio.onended = function() {
            tracks.next();
            audio.src = tracks.play();
            audio.load();
            audio.play();
        }

        audio.src = tracks.play();
        audio.src = tracks.pause();
      }

      function data() {
        $.get('data_antrian.php', function(data) {
          console.log(data);
          var text = data.split("-");
					if(text[1] == ''){ delay = 2000; }else{ delay = 11000 ;};
					console.log(delay);
          if (text[1] == 'A' || text[1] == 'D') {
						$('#asuransi').html(`${text[1]}${text[2]} ` );
						$('#ket_asuransi').html(`di Loket ${text[4]}` );
					}else if (text[1] == 'B' || text[1] == 'E') {
						$('#bpjs').html(`${text[1]}${text[2]} ` );
						$('#ket_bpjs').html(`di Loket ${text[4]}` );
					}
          else if (text[1] == 'C' || text[1] == 'F') {
						$('#priority').html(`${text[1]}${text[2]} ` );
						$('#ket_priority').html(`di Loket ${text[4]}` );
					}else if (text[1] == 'J') {
						$('#jkn_mobile').html(`${text[1]}${text[2]} ` );
						$('#ket_jkn_mobile').html(`di Loket ${text[4]}` );
					}

          if (text[3] == 'n') {
            // var isi =  text[1]+text[2];
            var isi =  `${text[2]}`;
						console.log("cek "+text[1]);
						console.log("cek2 "+text[2]);
						var terbilang1 = terbilang(isi).trim();
            var hasil = terbilang1.split(" ");
            // var list_panggil = ['suara/(.wav', 'suara/-.wav'];
            var list_panggil = ['suara/dingdong.wav', 'suara/-.wav'];
						/* panggil huruf */
						list_panggil.push('suara/'+text[1]+'.wav');
						/* panggil 0 */
						// 	while(isi.indexOf("0") == 0){
						// 	 list_panggil.push('suara/0.wav');
						// 	isi = isi.substring(1, isi.length);
						// }
						
            $.each(hasil, function(index, val) {
							 list_panggil.push('suara/'+val+'.wav');
            //   // setTimeout( function() {
            //   // }, index * 900 );
            });
						console.log(hasil);
            list_panggil.push('suara/diloket.wav');
            list_panggil.push('suara/'+text[4]+'.wav');
            // list_panggil.push('suara/).wav');

            console.log(list_panggil);
            panggil(list_panggil);
          }
        });
      }

			function panggilNolDiDepan(nilai){
				var list_panggil = [];
			
			return nilai;
			}

			function chk_fn(){
				// Your code here
			
				clearInterval(chkh);
				data();
				
        $('#loaddiv-1').fadeOut('slow').fadeIn("slow");
        $('#loaddiv-2').fadeOut('slow').fadeIn('slow');
        $('#loaddiv-3').fadeOut('slow').fadeIn('slow');
        $('#loaddiv-4').fadeOut('slow').fadeIn("slow");
			
				chkh = setInterval(chk_fn, delay);
			}

			var chkh = setInterval(chk_fn, delay);

			// function periodicall() {
			// 	 data();
				
      //   $('#loaddiv-1').fadeOut('slow').fadeIn("slow");
      //   $('#loaddiv-2').fadeOut('slow').fadeIn('slow');
      //   $('#loaddiv-3').fadeOut('slow').fadeIn('slow');
      //   $('#loaddiv-4').fadeOut('slow').fadeIn("slow");
			// 		setTimeout(periodicall, delay);
			// };
			// periodicall();

      // setInterval(function() {
      //   data();
				
      //   $('#loaddiv-1').fadeOut('slow').fadeIn("slow");
      //   $('#loaddiv-2').fadeOut('slow').fadeIn('slow');
      //   $('#loaddiv-3').fadeOut('slow').fadeIn('slow');
      //   $('#loaddiv-4').fadeOut('slow').fadeIn("slow");
      // },13000);
   </script>                                                                                                

    <style type="text/css">
      * {
        font-weight: bold;
      }

      body {
        margin:0;
        padding:0;
        background: url('bg.jpg');
        background-size: 100%;
        -moz-background-size: 100%;
      }

      #header {
        margin:0;
        width:100%;
        height:120px;
        background: #f34b72;
			  box-shadow :0px 10px 10px #086c39;
      }

      h1 {
        text-transform: uppercase;
        text-decoration: none;
        line-height: 40px;
        margin-right: 60px;
        margin-top: 0;
        color: #fff;
        font-size: 35px;
        font-weight: bold;
        text-align: center;
        border: none;
			
      }

      h3 {
        color:#fff;
        margin:0;max-width: 100%;
        padding: 5px 10px;
        background: #f34b72;
        text-align: center;
        font-size: 30px;
        border-radius: 10px 10px 0 0;
        -moz-border-radius: 10px 10px 0 0;
        text-transform: uppercase;
      }

      .marquee {
        font-size: 50px;
        font-weight: bold;
        text-transform: uppercase;
        position: absolute ;
        /* bottom: 0;  */
        width: 100%;
        left: 0;
        color:#19b666;
				text-shadow: 2px 3px 4px rgba(31, 31, 31, 0.7);
				/* text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black; */
      }

      .nomor {
        max-width: 100%;
        height: 130px;
        padding:2px;
        margin-bottom: 13px;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        -moz-border-radius: 10px;
        background: #fafafa;
        box-shadow:
      }

      .nam{
        line-height: 65px;
        font-size: 75px;
        font-weight: bold;
        /* padding-top: 10px; */
      }
			.ket{
        font-size: 20px;
        font-weight: bold;
      }
				h4,h2{
				color:#fff;
			}
    </style>
  </head>
  <body >  
    <audio id="audio" autoplay="autoplay">
      <source src="" />
    </audio>  
    <form name="frmView" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
      <div id="header">
        <table border="0" width="100%" height="100px">
          <tr>
            <td width="20%" rowspan="2" align="center"><img src="<?php echo $bg?>" height="80px" width="80px" style="border-radius: 150px;"></td>
            <td width="60%"><h1><?php echo $konfigurasi["dep_header_kanan_antrian"];?></h1></td>
            <td width="20%"><h2 id="day"><?=date("d-m-Y")?></h2></td>
          </tr>
					<tr>
            <td width="60%" align="center"><h4><?php echo $konfigurasi["dep_kop_surat_1"];?></h4></td>
            <td width="20%" ><h2 id="jam"><?=date("H:i")?></h2></td>
					
					</tr>
        </table>
      </div>

      <table border="0" width="100%">
        <tr rowspan="2">
          <td width="35%" style="padding-left: 25px; padding-top: 25px; padding-right: 25px">
            <div id="loaddiv-1" align="center" class="nomor">
              <div width="100%">
                <h3> BPJS</h3>
                <font color='black' align="center">
                  <div class="nam" id="bpjs">-</div>
                </font>
              </div>
							<div class="ket" id="ket_bpjs">-</div>
            </div>
            <div id="loaddiv-2" align="center" class="nomor">
              <div width="100%">
                <h3> PRIORITY</h3>
                <font color='black' align="center">
                  <div class="nam" id="priority">-</div>
                </font>
              </div>
							<div class="ket" id="ket_priority">-</div>
            </div> 
            <div id="loaddiv-3" align="center" class="nomor">
              <div width="100%">
                <h3> ASURANSI</h3>
                <font color='black' align="center">
                  <div class="nam" id="asuransi">-</div>
                </font>
              </div>
							<div class="ket" id="ket_asuransi">-</div>
							
            </div>
            <div id="loaddiv-4" align="center" class="nomor">
              <div width="100%">
                <h3> JKN MOBILE</h3>
                <font color='black' align="center">
                  <div class="nam" id="jkn_mobile">-</div>
                </font>
              </div>
								<div class="ket" id="ket_jkn_mobile">-</div>
            </div>
          </td>
          <td width="65%" style="align-content: center;padding-right: 25px">
            <video id="myVideo" controls autoplay style="width: 100%; max-height:550px" >
              <source  id="mp4Source" src="<?php echo $videoSrc?>" type="video/mp4">
            </video>
          </td>
        </tr>
        <tr>
          <td><marquee class="marquee"><?php echo $konfigurasi["dep_footer_antrian"];?></marquee></td>
        </tr>
      </table>
    </form>

    <script>
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
    </script>
		<script src="moment.js"></script>
		<script>
			$(function(){
        var vid = document.getElementById("myVideo");
            vid.muted = false;
						 	console.log("bersuara");
        var suara = document.getElementById("audio");
            suara.muted = false; 
				setTimeout(() => {
					console.log("muted");
							 vid.muted = true;
						}, 300);
				setInterval(() => {
					var now = moment();
					var day = now.format('MMMM Do YYYY')
					var jam = now.format('HH:mm:ss');
					$('#jam').html(jam);
					$('#day').html(day);
					
				}, 1000);
			});
		</script>
  </body>
</html>