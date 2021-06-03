<?php
     // Library
     require_once("../penghubung.inc.php");
     require_once($LIB."tampilan.php");     
     
     // Inisialisasi Lib
	   $dtaccess = new DataAccess();
     $auth = new CAuth();
	   $depId = $auth->GetDepId();
     $depNama = $auth->GetDepNama();
     $userName = $auth->GetUserName();
	   $poliId = $auth->IdPoli();
     $tgl = date("Y-m-d"); 
     
     $sql = "select * from global.global_departemen where dep_id='$depId'";
     $depKonfig = $dtaccess->Fetch($sql); 
     
     //KONFIGURASI LOKET
     $loketAntrian='1';    
     $tableHeader="Antrian Loket 1"; 
   
          
?>

<html>
<?php require_once($LAY."header.php") ?>
<body class="nav-sm" >
  <div class="container body">
    <div class="main_container">
        <?php require_once($LAY."sidebar.php") ?>

        <!-- top navigation -->
          <?php require_once($LAY."topnav.php") ?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
								<div class="x_content">
							<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item active">
    <a class="nav-link" id="pills-loket1-tab" data-toggle="pill" href="#pills-loket1" role="tab" aria-controls="pills-loket1" aria-selected="false">Loket 1</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-loket2-tab" data-toggle="pill" href="#pills-loket2" role="tab" aria-controls="pills-loket2" aria-selected="false">loket 2</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-loket3-tab" data-toggle="pill" href="#pills-loket3" role="tab" aria-controls="pills-loket3" aria-selected="false">Loket 3</a>
  </li>
	<li class="nav-item">
    <a class="nav-link" id="pills-loket4-tab" data-toggle="pill" href="#pills-loket4" role="tab" aria-controls="pills-loket4" aria-selected="false">Loket 4</a>
  </li>
</ul>
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade active in" id="pills-loket1" role="tabpanel" aria-labelledby="pills-loket1-tab">
	<div class="col-md-6">
		<table class='table' border='1'>
		<thead>
			<tr>
					<th>Pangill</th>
					<th>Nomer Antrian</th>
					<th>Jenis</th>
			</tr>
		</thead>
		<tbody id="panggil" class="text-center">
		</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<table class='table' border='1'>
		<thead>
			<tr>
					<th>Nomer Antrian</th>
					<th>Panggil Ulang</th>
					<th>Jenis</th>
					<th>registrasi</th>
			</tr>
		</thead>
		<tbody id="regis" class="text-center">
		</tbody>
		</table>
	</div>
	</div>
  <div class="tab-pane fade" id="pills-loket2" role="tabpanel" aria-labelledby="pills-loket2-tab">
		<div class="col-md-6">
												<table class='table' border='1'>
												<thead>
													<tr>
															<th>Pangill</th>
															<th>Nomer Antrian</th>
															<th>Jenis</th>
													</tr>
												</thead>
												<tbody id="panggil2" class="text-center">
												</tbody>
												</table>
											</div>
									  	<div class="col-md-6">
												<table class='table' border='1'>
												<thead>
													<tr>
															<th>Nomer Antrian</th>
															<th>Panggil Ulang</th>
															<th>Jenis</th>
															<th>registrasi</th>
													</tr>
												</thead>
												<tbody id="regis2" class="text-center">
												</tbody>
												</table>
									 		</div>
		
		</div>
  <div class="tab-pane fade" id="pills-loket3" role="tabpanel" aria-labelledby="pills-loket3-tab">	
	            		<div class="col-md-6">
												<table class='table' border='1'>
												<thead>
													<tr>
															<th>Pangill</th>
															<th>Nomer Antrian</th>
															<th>Jenis</th>
													</tr>
												</thead>
												<tbody id="panggil3" class="text-center">
												</tbody>
												</table>
									 		</div>
											<div class="col-md-6">
												<table class='table' border='1'>
												<thead>
													<tr>
															<th>Nomer Antrian</th>
															<th>Panggil Ulang</th>
															<th>Jenis</th>
															<th>registrasi</th>
													</tr>
												</thead>
												<tbody id="regis3" class="text-center">
												
												</tbody>
												</table>
											</div>
											</div>
  <div class="tab-pane fade" id="pills-loket4" role="tabpanel" aria-labelledby="pills-loket4-tab">
												<div class="col-md-6">
													<table class='table' border='1'>
													<thead>
														<tr>
																<th>Pangill</th>
																<th>Nomer Antrian</th>
																<th>Jenis</th>
														</tr>
													</thead>
													<tbody id="panggil4" class="text-center">
													</tbody>
													</table>
											</div>
											<div class="col-md-6">
													<table class='table' border='1'>
													<thead>
														<tr>
																<th>Nomer Antrian</th>
																<th>Panggil Ulang</th>
																<th>Jenis</th>
																<th>registrasi</th>
														</tr>
													</thead>
													<tbody id="regis4" class="text-center">
													
													</tbody>
													</table>
										</div>
										
</div>
  
								
								</div>
                  
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
				
				
     <!-- footer content -->
     <?php require_once($LAY."footer.php") ?>
     <!-- /footer content -->

    </div>
	</div>
	<script>
	 $(document).ready(function() {
            setInterval(function() {
								get_antrian()
						   	get_regis()
								 get_antrian2()
						   	get_regis2()
								 get_antrian3()
						   	get_regis3()
								 get_antrian4()
						   	get_regis4()
            }, 3000);

          });


	function get_antrian() {
		 $.getJSON(`get_antrian.php`, function(data) {
              $("#panggil").html('');
							

              $.each(data, function(index, val) {
                  $("#panggil").append(
                    `<tr><td><a onclick="call(${val.id})"> <i class="fa fa-volume-control-phone fa-2x"></i> </a></td>
										 <td>${val.nomer}</td>
										 <td>${val.jenis_nama}</td> </tr>
								`);

                });
            });
	 }
	 	function get_antrian2() {
		 $.getJSON(`get_antrian2.php`, function(data) {
              $("#panggil2").html('');
							

              $.each(data, function(index, val) {
                  $("#panggil2").append(
                    `<tr><td><a onclick="call2(${val.id})"> <i class="fa fa-volume-control-phone fa-2x"></i> </a></td>
										 <td>${val.nomer}</td>
										 <td>${val.jenis_nama}</td> </tr>
								`);

                });
            });
	 }
	 	function get_antrian3() {
		 $.getJSON(`get_antrian3.php`, function(data) {
              $("#panggil3").html('');
							

              $.each(data, function(index, val) {
                  $("#panggil3").append(
                    `<tr><td><a onclick="call3(${val.id})"> <i class="fa fa-volume-control-phone fa-2x"></i> </a></td>
										 <td>${val.nomer}</td>
										 <td>${val.jenis_nama}</td> </tr>
								`);

                });
            });
	 }
	 	function get_antrian4() {
		 $.getJSON(`get_antrian4.php`, function(data) {
              $("#panggil4").html('');
							

              $.each(data, function(index, val) {
                  $("#panggil4").append(
                    `<tr><td><a onclick="call4(${val.id})"> <i class="fa fa-volume-control-phone fa-2x"></i> </a></td>
										 <td>${val.nomer}</td>
										 <td>${val.jenis_nama}</td> </tr>
								`);

                });
            });
	 }
	 	function get_regis() {
		 $.getJSON(`get_regis.php`, function(data) {
              $("#regis").html('');

              $.each(data, function(index, val) {
                  $("#regis").append(
                    `<tr>
										 <td>${val.nomer}</td>
										 <td><a onclick="call(${val.id})"> <i class="fa fa-volume-control-phone fa-2x"></i> </a>
										 <td>${val.jenis_nama}</td> 
										 <td><a href='/dextra_billing/production/data_pasien/registrasi_pasien_awal.php' target="_blank"> <i class="fa fa-pencil fa-2x"></i> </a></td>
										 </tr>
								`);

                });
            });
	 }
	 	function get_regis2() {
		 $.getJSON(`get_regis2.php`, function(data) {
              $("#regis2").html('');

              $.each(data, function(index, val) {
                  $("#regis2").append(
                    `<tr>
										 <td>${val.nomer}</td>
										 <td><a onclick="call(${val.id})"> <i class="fa fa-volume-control-phone fa-2x"></i> </a>
										 <td>${val.jenis_nama}</td> 
										 <td><a href='/dextra_billing/production/data_pasien/registrasi_pasien_awal.php' target="_blank"> <i class="fa fa-pencil fa-2x"></i> </a></td>
										 </tr>
								`);

                });
            });
	 }
	 	function get_regis3() {
		 $.getJSON(`get_regis3.php`, function(data) {
              $("#regis3").html('');

              $.each(data, function(index, val) {
                  $("#regis3").append(
                    `<tr>
										 <td>${val.nomer}</td>
										 <td><a onclick="call(${val.id})"> <i class="fa fa-volume-control-phone fa-2x"></i> </a>
										 <td>${val.jenis_nama}</td> 
										 <td><a href='/dextra_billing/production/data_pasien/registrasi_pasien_awal.php' target="_blank"> <i class="fa fa-pencil fa-2x"></i> </a></td>
										 </tr>
								`);

                });
            });
	 }
	 	function get_regis4() {
		 $.getJSON(`get_regis4.php`, function(data) {
              $("#regis4").html('');

              $.each(data, function(index, val) {
                  $("#regis4").append(
                    `<tr>
										 <td>${val.nomer}</td>
										 <td><a onclick="call(${val.id})"> <i class="fa fa-volume-control-phone fa-2x"></i> </a>
										 <td>${val.jenis_nama}</td> 
										 <td><a href='/dextra_billing/production/data_pasien/registrasi_pasien_awal.php' target="_blank"> <i class="fa fa-pencil fa-2x"></i> </a></td>
										 </tr>
								`);

                });
            });
	 }

		function call(id) {
					$.getJSON(`get_call.php?id=${id}`)
		}

		function call2(id) {
					$.getJSON(`get_call.php?id=${id}`)
		}
		function call3(id) {
					$.getJSON(`get_call.php?id=${id}`)
		}
		function call4(id) {
					$.getJSON(`get_call.php?id=${id}`)
		}
	</script>
</body>
<?php require_once($LAY."js.php") ?>                                                  

</html>                                   