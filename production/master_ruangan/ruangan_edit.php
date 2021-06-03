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
     $err_code = 0;
     $depNama = $auth->GetDepNama(); 
     $depId = $auth->GetDepId();
     $userName = $auth->GetUserName();
     $backPage = "ruangan_view.php";
     $tableHeader = "&nbsp; Master Ruangan";
/* deklarasi */
     if($_GET['id']){ $id = $_GET['id']; }else{ $id = $_POST['id'];}
/*  edit */
     if(isset($id)){

      $sql = "select * from klinik.klinik_ruangan where ruangan_id = ".QuoteValue(DPE_CHAR,$id);
      $edit = $dtaccess->Fetch($sql);

   }
/* save  */
  if($_POST['btnSave']|| $_POST['btnUpdate'] ){
    $dbTable = "klinik.klinik_ruangan";
               
    $dbField[0] = "ruangan_id";   // PK
    $dbField[1] = "ruangan_nama";
    $dbField[2] = "id_dep";

    if(!$id){ $id = $dtaccess->GetTransId(); }

    $dbValue[0] = QuoteValue(DPE_CHAR,$id);
    $dbValue[1] = QuoteValue(DPE_CHAR,$_POST["ruangan_nama"]);
    $dbValue[2] = QuoteValue(DPE_CHAR,$depId);

    $dbKey[0] = 0; // -- set key buat clause wherenya , valuenya = index array buat field / value
    $dtmodel = new DataModel($dbTable,$dbField,$dbValue,$dbKey);


    if ($_POST["btnSave"]) {
        $dtmodel->Insert() or die("insert  error");	
    
    } else if ($_POST["btnUpdate"]) {
        $dtmodel->Update() or die("update  error");	
    }
    
    header("location:".$backPage);
    exit();  
  }
/* Hapus */
    if($_GET['hapus']){

      $sql="delete from klinik.klinik_ruangan where ruangan_id   = ".QuoteValue(DPE_CHAR,$id);
      $dtaccess->Execute($sql);

      header("location:".$backPage);
      exit();  
    }


?>

<!DOCTYPE html>
<html lang="en">
  <?php require_once($LAY."header.php"); ?>
  <body class="nav-md">
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
                <h3>Managemen</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Master Ruangan</h2>
                    <span class="pull-right"></span>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
					<form id="demo-form2" method="POST" class="form-horizontal form-label-left" action="<?php echo $_SERVER["PHP_SELF"]?>">
                      <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Nama Ruangan</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                          <input type="text" name="ruangan_nama" class="form-control" value="<?=$edit['ruangan_nama']?>"> 
                        </div>
                      </div>
                      
                      
                      <div class="ln_solid"></div>                      
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-6">
                          <button class="btn btn-danger" type="button" onClick="window.history.back()">Kembali</button>
                          <?php if(!$_GET['id']){?>
                          <input class="btn btn-primary" name="btnSave" type="submit"  value=" Save"> 
                          <?php }else{ ?>
                          <input class="btn btn-success"  name="btnUpdate" type="submit"  value=" Update " >
                          <?php } ?>

                        </div>
                      </div>
                      <!-- Data Store -->
                      <input type="hidden" name="id" value="<?=$id?>">
                    </form>
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
