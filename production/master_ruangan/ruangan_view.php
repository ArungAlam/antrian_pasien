<?php
     require_once("../penghubung.inc.php");
     require_once($LIB."bit.php");
     require_once($LIB."login.php");
     require_once($LIB."encrypt.php");
     require_once($LIB."datamodel.php");
     require_once($LIB."currency.php");
     require_once($LIB."dateLib.php");
     require_once($LIB."expAJAX.php");
	   require_once($LIB."tampilan.php");	
     
     $view = new CView($_SERVER['PHP_SELF'],$_SERVER['QUERY_STRING']);
     $dtaccess = new DataAccess();
     $enc = new textEncrypt();   
     $auth = new CAuth();
     $table = new InoTable("table","100%","left");
	   $depId = $auth->GetDepId();
	   $depNama = $auth->GetDepNama();
	   $userName = $auth->GetUserName();
	   $depLowest = $auth->GetDepLowest();
     
     $editPage = "ruangan_edit.php";
     $thisPage = "ruangan_view.php";

       if(!$auth->IsAllowed("man_ganti_password",PRIV_CREATE)){
          die("Maaf anda tidak berhak membuka halaman ini....");
          exit(1);
     } else 
      if($auth->IsAllowed("man_ganti_password",PRIV_CREATE)===1){
          echo"<script>window.parent.document.location.href='".$ROOT."login/login.php?msg=Login First'</script>";
          exit(1);
     } 	 
      
     $sql = "select * from  klinik.klinik_ruangan order by ruangan_nama asc";
     $rs = $dtaccess->Execute($sql);
     $dataTable = $dtaccess->FetchAll($rs);
     //echo $sql;
     

     //*-- config table ---*//
     $tableHeader = "&nbsp; Master Ruangan ";
      
     $tombolAdd = '<input type="button" name="btnAdd" value="Tambah" class="btn btn-primary" onClick="document.location.href=\''.$editPage.'\'"></button>';

     $status['y'] = 'Dipakai';
     $status['n'] = 'Tidak Dipakai';
?>
<!DOCTYPE html>
<html lang="en">
  <?php require_once($LAY."header.php") ?>
  <style>
   #datatable thead, 
   #datatable th,
   #datatable td
     {text-align: center;
     }
  </style>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php require_once($LAY."sidebar.php") ?>

        <!-- top navigation -->
          <?php require_once($LAY."topnav.php") ?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $tableHeader; ?></h2>
                    <span class="pull-right"><?php echo $tombolAdd; ?></span>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
					           <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead >
                        <tr>
                              <th> No </th>
                              <th > Nama Ruangan </th>
                              <th > Edit </th>
                              <th > Hapus </th>
                        </tr>
                     
                      </thead>
                      <tbody>
                        <?php foreach ($dataTable as $key => $value) {?>
                        <tr>
                          <td> <?=$key+1?> </td>
                          <td> <?=$value['ruangan_nama']?> </td>
                          <td> <a href='ruangan_edit.php?id=<?=$value['ruangan_id']?>'><i class='fa fa-pencil fa-2x'></i> </a> </td>
                          <td>  <a href='ruangan_edit.php?hapus=1&id=<?=$value['ruangan_id']?>'><i class='fa fa-trash fa-2x'></i></a> </td>
                        </tr>
                        <?php }?>
                        
                      </tbody>
                    </table>
					
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
    

<?php require_once($LAY."js.php") ?>

  </body>
</html>     