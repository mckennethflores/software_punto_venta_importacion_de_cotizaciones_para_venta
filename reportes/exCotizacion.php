<?php
 $direccion = "Lima";
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1) 
  session_start();

if (!isset($_SESSION["nombre"]))
{
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}
else
{
if ($_SESSION['cotizacion']==1)
{
  require_once "../config/Conexion.php";
//Incluímos el archivo Factura.php
require('Factura.php');

//Establecemos los datos de la empresa
$logo = "logo3.png";
$ext_logo = "png";

$direccion_ = mb_strtolower($direccion);

$email = "informes@frsystem.com.pe";


$bcpsoles = "192-8854889-0-20";
$bcpdolares = "";
$cuentadetracciones = "00-000-000000";

require_once "../modelos/Cotizacion.php";
$cotizacion= new Cotizacion();
$rsptac = $cotizacion->cotizacioncabecera($_GET["id"]);

$regc = $rsptac->fetch_object();

$contacto = $regc->nombre. ' '.$regc->apellido;
//Establecemos la configuración de la factura
$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();

$TIPO_MONEDA = $regc->tipomoneda;

$leftAlign = "           ";
$pdf->addSociete(utf8_decode(NOMBRE_EMPRESA),
                  utf8_decode(ADDRESS)."\n".
                  utf8_decode($leftAlign."TELEF.: ").CELULAR1."\n" .
                  "EMAIL: ".EMAIL,$logo,$ext_logo);
$pdf->fact_dev(utf8_decode("COTIZACIÓN"), "C00"."$regc->serie_comprobante-$regc->num_comprobante", RUC);
$pdf->temporaire( "" );

/*  */
$direccion_cliente = substr($regc->direccion, 0, 94);

$pdf->addClientAdresse(utf8_decode(htmlspecialchars_decode($regc->cliente)),
utf8_decode($direccion_cliente),$regc->num_documento,$regc->email,$regc->telefono,formatDateMysql($regc->fecha),$TIPO_MONEDA,$contacto," Observacion");

//Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
$pdf->SetFont( "Arial", "B", 8);
$ITEM = utf8_decode("Itém");
$UNIDAD = utf8_decode("U/Medida");
$CODIGO = utf8_decode("Código");
$DESCRIPCION = utf8_decode("Descripción");
$CANTIDAD = utf8_decode("Cantidad");
$PRECIO = utf8_decode("Precio");
$SUBTOTAL = utf8_decode("SubTotal");
    $cols=array(
      $ITEM=>8,
      $UNIDAD=>14,
      $CODIGO=>15,
      $DESCRIPCION=>87,
      $CANTIDAD=>16,
      $PRECIO=>25,
      $SUBTOTAL=>25);
    $pdf->addCols($cols);
    $cols=array($ITEM=>"C",
    $UNIDAD=>"C",
    $CODIGO=>"C",
    $DESCRIPCION=>"L",
    $CANTIDAD=>"C",
    $PRECIO=>"R",
    $SUBTOTAL=>"R");
    $pdf->addLineFormat($cols);


//Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
$y= 75;

//Obtenemos todos los detalles de la venta actual
$rsptad = $cotizacion->cotizaciondetalle($_GET["id"]);
$item = 1;
while ($regd = $rsptad->fetch_object()){

  $pdf->SetFont( "Arial", "", 8);
  $line = array(
    $ITEM=>$item,
    $UNIDAD=> $regd->unidadmedida,
    $CODIGO=> strtoupper($regd->codigo),
    $DESCRIPCION=> strtoupper(utf8_decode(htmlspecialchars_decode($regd->articulo))),
    $CANTIDAD=>"$regd->cantidad",
    $PRECIO=>"$regd->precio_cotizacion",
    $SUBTOTAL=> "$regd->subtotal");
            $size = $pdf->addLine( $y, $line );
            $y   += $size + 5;
            $item++;
}

//Convertimos el total en letras
require_once "Letras.php";
$V=new EnLetras(); 

if($TIPO_MONEDA == "DOLARES"){
  $EXT_MSJ = "DOLARES AMERICANOS";
  $MONEY = "$ ";
}else{
  $EXT_MSJ = "SOLES";
  $MONEY = "S/ ";
}
$con_letra=strtoupper($V->ValorEnLetras($regc->total_cotizacion,$EXT_MSJ));
$pdf->addCadreTVAs(NOMBRE_EMPRESA,$con_letra,$bcpsoles,$bcpdolares,$cuentadetracciones);

//Mostramos el impuesto
$pdf->addTVAs( $regc->subtotal,$regc->igv, $regc->total_cotizacion,$MONEY);
$pdf->addCadreEurosFrancs("IGV"." 18%");

$pdf->Output("cotizacion-nro-".$regc->serie_comprobante."_".$regc->num_comprobante.".pdf","I");

}

else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>