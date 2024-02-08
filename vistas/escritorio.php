<?php
ob_start();
session_start();

if(!isset($_SESSION["nombre"]))
{
  header("Location: login.html");
}
else
{
require_once("header.php");

if ($_SESSION['escritorio']==1)
{
    require_once "../modelos/Consultas.php";
    $consulta= new consultas();
    $rsptac= $consulta->totalcomprahoy();
    $regc= $rsptac->fetch_object();
    $totalc=$regc->total_compra;

    $rsptacaptot= $consulta->capitalTotal();
    $regcaptot= $rsptacaptot->fetch_object();
    $totalcaptot=$regcaptot->capital_total;

    $rsptav= $consulta->totalventahoy();
    $regv= $rsptav->fetch_object();
    $totalv=$regv->total_venta;

    $comprasdiez=$consulta->comprasultimosdiezdias();
    $fechasc='';
    $totalesc='';
    while($regfechac=$comprasdiez->fetch_object()) {
        $fechasc=$fechasc.'"'.$regfechac->fecha.'",';
        $totalesc=$totalesc.'"'.$regfechac->total.'",';
    }
    //substr quita la ultima coma
    $fechasc=substr($fechasc, 0, -1);
    $totalesc=substr($totalesc, 0, -1);

    // Grafico para ventas en los ultimos 12 meses 
    $ventasdoce=$consulta->ventasultimosdocemeses();
    $fechasv='';
    $totalesv='';
    while($regfechav=$ventasdoce->fetch_object()) {
        $fechasv=$fechasv.'"'.$regfechav->fecha.'",';
        $totalesv=$totalesv.'"'.$regfechav->total.'",';
    }
    //substr quita la ultima coma
    $fechasv=substr($fechasv, 0, -1);
    $totalesv=substr($totalesv, 0, -1);
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
                          <h1 class="box-title">Escritorio</h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body" >

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h4><b>S/ <?php echo number_format($totalc,2,SPD,SPM); ?></b></h4>
                                        <p>Compras Hoy</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                        <h5 >    <a href="ingreso.php" class="small-box-footer">Compras <i class="fa fa-arrow-circle-right"></i></a></h5>
                                    </div>                        
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h4><b>S/ <?php echo number_format($totalv,2,SPD,SPM); ?></b></h4>
                                        <p>Ventas</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                     <h5 ><a href="venta.php" class="small-box-footer">Ventas <i class="fa fa-arrow-circle-right"></i></a></h5>  
                                    </div>                        
                            </div>
                        </div>
                    </div>
                    <div class="panel-body" >

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="small-box bg-green">
                                    <div class="inner">
                                        <h4><b>S/ <?php echo number_format($totalcaptot,2,SPD,SPM); ?></b></h4>
                                        <p>Capital Total</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                       
                                    </div>                        
                            </div>
                        </div>
                        
                    </div>
                    <div class="panel-body " >
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                Compras en los 10 ultimos días
                            </div>
                            <div class="box-body">
                                <canvas id="compras"width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                               Ventas en los ultimos 12 meses
                            </div>
                            <div class="box-body">
                                <canvas id="ventas"width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>        
                       </div>                   
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
<?php
}
else
{
  require 'noacceso.php';
}
require_once("footer.php");
?>
<script type="text/javascript" src="../public/js/Chart.min.js"></script>
<script type="text/javascript" src="../public/js/Chart.bundle.min.js"></script>
<script>

var ctx = document.getElementById("compras").getContext('2d');
var compras = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php echo $fechasc ?>],
        datasets: [{
            label: '# Compras S/ hechas en ultimos 10 días',
            data: [<?php echo $totalesc ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)'                
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'                
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});


// ventas grafico 

var ctx = document.getElementById("ventas").getContext('2d');
var ventas = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php echo $fechasv ?>],
        datasets: [{
            label: '# ventas S/ hechas en ultimos 12 meses',
            data: [<?php echo $totalesv ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'             
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'                               
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>
<?php
}
ob_end_flush();
?>