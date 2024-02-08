<?php
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
  
/*   require "../modelos/Venta.php";
  $venta= new Venta();
  $rsptad = $venta->ventadetalle($_GET["id"]);

  while ($regd = $rsptad->fetch_object()){

    var_dump($regd->subtotal);
  }

   return; */
if ($_SESSION['cotizacion']==1)
{
  require_once "../config/Conexion.php";
//Incluímos el archivo Factura.php
require('Factura_paraFactura.php');

//Establecemos los datos de la empresa
$logo = "logo3.jpg";
$ext_logo = "jpg";
//$empresa = NOMBRE_EMPRESA;
/* $documento = "20601579317"; */
/* $direccion = "AV. GARCILAZO DE LA VEGA 1260 OF. 6, LIMA"; */
//$direccion_ = mb_strtolower($direccion);


/* $telefono = "938 222 552"; */
/* $email = "informes@frsystem.com.pe"; */

function formatUtf8($value){

  return mb_convert_encoding($value, 'ISO-8859-1');

}


$bcpsoles = "192-8854889-0-20";
$bcpdolares = "";
$cuentadetracciones = "00-000-000000";
//Obtenemos los datos de la cabecera de la cotizacion actual
require "../modelos/Venta.php";
$venta= new Venta();

$rsptav = $venta->ventacabecera($_GET["id"]);
//Recorremos todos los valores obtenidos
$regv = $rsptav->fetch_object();

$contacto = $regv->vende_nom_ape;
//Establecemos la configuración de la factura
$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();

$TIPO_MONEDA = $regv->tipomoneda;
//Enviamos los datos de la empresa al método addSociete de la clase Factura
/* $documento."\n" .
                  utf8_decode(""). */
$pdf->addSociete(formatUtf8(NOMBRE_EMPRESA),
"                  ".formatUtf8(ADDRESS)."\n".
                  "           ".ADDRESS2."\n" .
                  "                      Telef: ".CELULAR1."\n" .
                    "           Email : ".EMAIL,$logo,$ext_logo);
$pdf->fact_dev(formatUtf8("VENTA"), "V00"."$regv->serie_comprobante-$regv->num_comprobante", RUC);
$pdf->temporaire( "" );

/*  */
$direccion_cliente = substr($regv->direccion, 0, 94);
//Enviamos los datos del cliente al método addClientAdresse de la clase Factura
$pdf->addClientAdresse(formatUtf8(htmlspecialchars_decode($regv->cliente)),formatUtf8($direccion_cliente),$regv->num_documento,$regv->email,$regv->telefono,formatDateMysql($regv->fecha),$TIPO_MONEDA,$contacto);

//Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
$pdf->SetFont( "Arial", "B", 8);
$ITEM = formatUtf8("Itém");
$UNIDAD = formatUtf8("U/Medida");
$CODIGO = formatUtf8("Código");
$DESCRIPCION = formatUtf8("Descripción");
$CANTIDAD = formatUtf8("Cantidad");
$PRECIO = formatUtf8("Precio");
$SUBTOTAL = formatUtf8("SubTotal");
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
$rsptad = $venta->ventadetalle($_GET["id"]);
$item = 1;
while ($regd = $rsptad->fetch_object()){

  $pdf->SetFont( "Arial", "", 8);
  $line = array(
    $ITEM=>$item,
    $UNIDAD=> $regd->unidadmedida,
    $CODIGO=> strtoupper($regd->codigo),
    $DESCRIPCION=> strtoupper(formatUtf8(htmlspecialchars_decode($regd->articulo))),
    $CANTIDAD=>"$regd->cantidad",
    $PRECIO=>"$regd->precio_venta",
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
$con_letra=strtoupper($V->ValorEnLetras($regv->total_venta,$EXT_MSJ));
$pdf->addCadreTVAs("".$con_letra,$bcpsoles,$bcpdolares,$cuentadetracciones);

//Mostramos el impuesto
$pdf->addTVAs( $regv->subtotal,$regv->igv, $regv->total_venta,$MONEY);
$pdf->addCadreEurosFrancs("IGV"." $regv->igv %");

$pdf->Output("boleta-nro-".$regv->serie_comprobante."_".$regv->num_comprobante.".pdf","I");

}

else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>