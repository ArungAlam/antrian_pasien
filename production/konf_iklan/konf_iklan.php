<?php

    require_once("../penghubung.inc.php");
     require_once($LIB."login.php");
     require_once($LIB."encrypt.php");
     require_once($LIB."datamodel.php");
     require_once($LIB."currency.php");
     require_once($LIB."dateLib.php");
     require_once($LIB."expAJAX.php");
     require_once($LIB."tampilan.php");	
     
     // INISIALISASY LIBRARY
     $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
     $dtaccess = new DataAccess();   
	   $auth = new CAuth();
	   $depNama = $auth->GetDepNama();
	   $userName = $auth->GetUserName();
	   $depId = $auth->GetDepId();
     
     if(!$auth->IsAllowed("man_pengaturan_konf_antrian",PRIV_READ)){
          die("access_denied");
          exit(1);

     } elseif($auth->IsAllowed("man_pengaturan_konf_antrian",PRIV_READ)===1){
          echo"<script>window.parent.document.location.href='".$ROOT."login.php?msg=Session Expired'</script>";
          exit(1);
     } 

     $sql = "select * from global.global_video_iklan order by iklan_tayang_urut asc";
     $rs = $dtaccess->Execute($sql);
     $foto = $dtaccess->FetchAll($rs);

     
	$lokasi = $ROOT."lcd";
	$lokasiVideo = $ROOT."lcd/";
  $arrHari = array('minggu','senin','selasa','rabu','kamis','jumat','sabtu');

	
?>


<!DOCTYPE html>
<html lang="en">
  <?php require_once($LAY."header.php"); ?>
	<link rel="stylesheet" href="plugin/fancy_fileupload.css" type="text/css" media="all" />


<script type="text/javascript">

	

function ajaxFileUpload()
	{
	
		$.ajaxFileUpload
		(
			{
				url:'konfigurasi_pic.php',
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'JSON',
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else                               
						{
							alert(data.msg);
              document.getElementById('dep_logo_kiri_antrian').value= data.file; 
              //document.img_cfg.src='<?php echo $lokasi."/";?>'+data.file;
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
		// setTimeout(() => {
		// 	location.reload();
		// }, 2000);
	
		return false;


	}

function show_urutan(id,nama,urut,hari,jam){
		$('#id_iklan').val(id);
		$('#iklan_vido_nama_upload').val(nama);
		$('#iklan_tayang_urut').val(urut);
		// $('#viklan_tayang_jam').val(jam);
		// $('#viklan_tayang_hari').val(hari);
		$('#myModal').modal('show');


}

function proses_urut() {
			var urut = $('#iklan_tayang_urut').val();
			var id =$('#id_iklan').val();
			$.post('proses_urut.php',{id:id,urut:urut});
		 
		 setTimeout(() => {
			 location.reload();
		 }, 500); 
}

</script>


  <body class="nav-sm">
    <div class="container body">
      <div class="main_container">
        
		<?php require_once($LAY."sidebar.php"); ?>

        <!-- top navigation -->
		<?php require_once($LAY."topnav.php"); ?>
		<!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Manajemen</h3>
              </div>
            </div>

            <div class="clearfix"></div>
            <div class="row"> <!-- ==== BARIS ===== -->
			<!-- ==== kolom kiri ===== -->
			<!-- ==== mulai form ===== -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Konfigurasi iklan</h2>
                    <span class="pull-right"></span>
                    <div class="clearfix"></div>
                  </div>				  
                  <div class="x_content">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Nama Iklan</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="text" id="nama_iklan" class="form-control" onchange="isi_inputan_hidden()">
						            </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Hari Tayang</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                        <!-- <select name="" id="hari_tayang"> -->
                        <?php foreach ($arrHari as $key => $val) {?>
                          <!-- <option value="<?=$key?>"><?=$val?></option> -->
                          <input type="checkbox" name="hari_tayang_x[]" onchange="isi_inputan_hidden()" id="hari_tayang[<?=$key?>]" value="<?=$key?>">
                          <label for=""><?=$val?></label>
                        <?php }?>
                        </select>
						            </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" >Jam Tayang</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="text" id="jam_tayang" class="form-control" onchange="isi_inputan_hidden()">
						            </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" >Upload Video</label>
                      <div class="col-md-8 col-sm-8 col-xs-12">
                        <label ><h4 class="text-center"> Tarik Video ke bawah sini !!! </h4></label>
                        <div class="container">
                            <input id="thefiles" type="file" name="files" accept="video/mp4,video/x-m4v,video/*" >
                            <h4 id="notif"></h4>
                        </div>
                      </div>    
                    </div>
                </div>
			<!-- ==== panel putih ===== -->
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Galery Video</h2>
                    <span class="pull-right"></span>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
					<table width="100%" border="0">
					<tr>
					<th>Urutan</th>
					<th class='text-center' >Nama</th>
					<th class='text-center' >Hari Tayang</th>
					<th class='text-center' >Jam Tayang</th>
					<th   class='text-center' >Aksi</th>
					</tr>
								<?php for ($i=0; $i <count($foto) ; $i++) { ?>
          <tr>
					 		<td><?=$foto[$i]['iklan_tayang_urut'];?></td>
              <td class='text-center' ><?=$foto[$i]['iklan_video_nama_upload'];?>	</td>
              <td class='text-center' ><?= $arrHari[$foto[$i]['iklan_tayang_hari']];?>	</td>
              <td class='text-center' ><?=$foto[$i]['iklan_tayang_jam'];?>	</td>
							<td class='text-center'>
								<a href="#" class="btn btn-danger btn-xs" onclick= "hapusVideo('<?= $foto[$i]['iklan_id'] ; ?>' ,event)"><i class="fa fa-trash  fa-2x"></i></a> ||
								<a href="#"  class="btn btn-info  btn-xs" onclick= "show_urutan('<?= $foto[$i]['iklan_id'] ?>','<?= $foto[$i]['iklan_video_nama_upload'] ; ?>','<?=$foto[$i]['iklan_tayang_urut'];?>' ,event)"><i class="fa fa-edit  fa-2x"></i></a>
							</td>

          </tr>   
								<?php }  ?>
    
					</table>
					  
                  </div>
                </div>
			<!-- ==== // panel putih ===== -->
      
              </div>
			  <!-- ==== // kolom kiri ===== -->
            </div> <!-- ==== // BARIS ===== -->
          </div>
        </div>
        <!-- /page content -->

					<!-- Modal -->
					<div id="myModal" class="modal fade" role="dialog">
						<div class="modal-dialog modal-lg">

							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header  ">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									 <h4 class="modal-title text-center ">Ganti Urutan</h4>
								</div>
								<div class="modal-body ">
									<form action="" id="myForm">
										<div class="form-group"><label class="col-md-3 col-sm-3 col-xs-12">Nama Video</label>
											<div class="col-md-9 col-sm-9 col-xs-12">
												<input type="text" id="iklan_vido_nama_upload"  value="" class="form-control" readonly>
											</div>		
										</div>
										<br>
										<br>
                    <!-- <div class="form-group"><label class="col-md-3 col-sm-3 col-xs-12">Hari Tayang</label>
											<div class="col-md-9 col-sm-9 col-xs-12">
												<input type="text" id="hari_tayang"  value="" class="form-control" readonly>
											</div>		
										</div>
										<br>
										<br>
                    <div class="form-group"><label class="col-md-3 col-sm-3 col-xs-12">jam Tayang</label>
											<div class="col-md-9 col-sm-9 col-xs-12">
												<input type="text" id="jam_tayang"  value="" class="form-control" readonly>
											</div>		
										</div>
										<br>
										<br> -->
										<div class="form-group"><label class="col-md-3 col-sm-3 col-xs-12">Urutan</label>
											<div class="col-md-9 col-sm-9 col-xs-12">
												<input type="text" id="iklan_tayang_urut"  value="" class="form-control">
											</div>		
										</div>
										<br>
										<br>
										
										<input type="hidden" name="id_iklan" id="id_iklan">
									</form>
								</div>
								<div class="modal-footer ">
										<button type="button" class="btn btn-default" data-dismiss="modal" onclick="" >Close</button>
										<button type="submit" class="btn btn-success" data-dismiss="modal" onclick="proses_urut()">Submit</button>
								</div>
							</div>

						</div>
					</div>
					<!-- End Modal -->

        <!-- footer content -->
          <?php require_once($LAY."footer.php") ?>
				<script type="text/javascript" src="plugin/jquery.ui.widget.js"></script>
        <script type="text/javascript" src="plugin/jquery.fileupload.js"></script>
        <script type="text/javascript" src="plugin/jquery.iframe-transport.js"></script>
        <script type="text/javascript" src="plugin/jquery.fancy-fileupload.js"></script>
        <!-- /footer content -->
				<script>

						/* Limit file upload */
						$('#thefiles').FancyFileUpload({
								url : 'uploader.php',
								params : {
									action : 'fileuploader',
                  nama_iklan : '',
                  hari_tayang : '',
                  jam_tayang  : ''
								},
								maxfilesize : 160000000,   //byte  150mb
								uploadcompleted : function(e, data) {
									// console.log(e,data);
									$('#notif').append('upload tersimpan');
									setTimeout(() => {
											location.reload();
										}, 2000);
	
									}
							});

              function isi_inputan_hidden(){
                $("input[name='nama_iklan']").val($('#nama_iklan').val());
                $("input[name='jam_tayang']").val($('#jam_tayang').val());
                  var sel = $('input[type=checkbox]:checked').map(function(_, el) {
                      return $(el).val();
                  }).get();
                  
                  $("input[name='hari_tayang']").val(sel);
              }

							function hapusVideo(id,e){
								var x = confirm("Are you sure you want to delete?");
								if (x)
								  $.post('delete_video.php',{id :id},function(data) {
										location . reload();
										});
									else
										return false;

							}

						$(function(){
							$('.ff_fileupload_hidden').css('display','none');
						})
					</script>
      </div>
    </div>




<?php require_once($LAY."js.php") ?>

  </body>
</html>
