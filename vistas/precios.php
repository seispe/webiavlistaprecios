<?php


require 'header.php'; ini_set('memory_limit', '-1'); 
ini_set("pcre.backtrack_limit","99999999999999999");


  $RUC=$_GET['cli'];
  $VENDEDOR=$_GET['ven'];

?>

<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">LISTA DE PRECIOS</h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">


                    <div class="row">

                    <input  type='hidden' id="ruc" name="ruc"  value="<?php echo $RUC; ?>"></input>
                    <input  type='hidden' id="vendedor" value="<?php echo $VENDEDOR; ?>" name="vendedor" ></input>


                    <div class="form-inline col-lg-3 col-md-3 col-sm-6 col-xs-12">
                          <label>Marca producto</label>
                          <select name="MARCA_PROD" multiple=true id="MARCA_PROD" class="form-control selectpicker" data-live-search="true" >                         	
                          </select>                          
                    </div>

                    <div class="form-inline col-lg-3 col-md-3 col-sm-6 col-xs-12">
                          <label>Marca vehículo</label>
                          <select name="MARCA_VEHI" id="MARCA_VEHI" class="form-control selectpicker" data-live-search="true" onchange="cargar()" >                         	
                          </select>                          
                    </div>

                    <div class="form-inline col-lg-3 col-md-3 col-sm-6 col-xs-12">
                          <label>Modelo vehículo</label>
                          <select name="MODELO_VEHI" id="MODELO_VEHI" class="form-control selectpicker" data-live-search="true" >                         	
                          </select>                          
                    </div>

                    <div class="form-inline col-lg-3 col-md-3 col-sm-6 col-xs-12">
                          <label>Familia</label>
                          <select name="FAMILIA" id="FAMILIA" class="form-control selectpicker" data-live-search="true" >                         	
                          </select>                          
                    </div>

                    <div class="form-inline col-lg-4 col-md-4 col-sm-4 col-xs-12">
                          <label>Línea</label>
                          <select name="linea" id="linea" class="form-control selectpicker" data-live-search="true" > 
                            <option value="">--SELECCIONE--</option>                        	
                            <option value="carroceria">CARROCERIA</option>                        	
                            <option value="motor">MOTOR</option>                        	
                          </select>                          
                    </div>

                    <div class="form-inline col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <br>
                         <button class="btn btn-primary btn-block" onclick="listar()"> GENERAR Y ENVIAR </button>                          
                    </div>
                    <div class="form-inline col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <br>
                         <button class="btn btn-info btn-block" onclick="reset()"> LIMPIAR FILTROS </button>                          
                    </div>

                    </div>

                    <div class="row">
                      <BR>
                      <BR>
                      <BR>
                      <BR>
                      <BR>
                      <BR>
                      <BR>
                      <BR>
                      <BR>
                    
                    </div>

                    <br>
                    <br>
                    <br>
                   
                   
                    <!-- <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover ">
                          <thead>
                            <th>CODIGO_EMPRESA</th>
                            <th>DESCRIPCION</th>
                            <th>MARCA_PROD</th>
                            <th>MARCA_VEHI</th>
                            <th>MODELO_VEHI</th>
                            <th>FAMILIA</th>
                            <th>PRECIO_MAY_IVA</th>
                            <th>CANTIDAD</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                          <th>CODIGO_EMPRESA</th>
                            <th>DESCRIPCION</th>
                            <th>MARCA_PROD</th>
                            <th>MARCA_VEHI</th>
                            <th>MODELO_VEHI</th>
                            <th>FAMILIA</th>
                            <th>PRECIO_MAY_IVA</th>
                            <th>CANTIDAD</th>
                          </tfoot>
                        </table> -->
                    </div>
                    
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
<?php


require 'footer.php';
?>

<script type="text/javascript" src="scripts/precios.js"></script>



