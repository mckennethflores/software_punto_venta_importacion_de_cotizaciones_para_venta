<?php
if (strlen(session_id()) < 1)
  session_start();
require_once "../modelos/Venta.php";

$venta = new Venta();
// condicion de una sola linea
$idventa = isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$idcliente = isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario = $_SESSION["idusuario"];
$tipo_comprobante = isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$serie_comprobante = isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"]):"";
$num_comprobante = isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora = isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto = isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$total_venta = isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";



//$idarticulo_obtengoDeCotizacion = isset($_POST["idarticulo_obtengoDeCotizacion"])? limpiarCadena($_POST["idarticulo_obtengoDeCotizacion"]):"";



//Vendedor
$idvendedor = isset($_POST["idvendedor"])? intval($_POST["idvendedor"]):"";
$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$apellido = isset($_POST["apellido"])? limpiarCadena($_POST["apellido"]):"";
$dni = isset($_POST["dni"])? limpiarCadena($_POST["dni"]):"";
$email = isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$celular = isset($_POST["celular"])? limpiarCadena($_POST["celular"]):"";
$whatsapp = isset($_POST["whatsapp"])? limpiarCadena($_POST["whatsapp"]):"";

$idvendedor_tabla = isset($_POST["idvendedor_tabla"])? limpiarCadena($_POST["idvendedor_tabla"]):"";

$nombre_vendedor = isset($_POST["nombre_vendedor"])? limpiarCadena($_POST["nombre_vendedor"]):"";

$serie_comprobante = '1';


$quotation = isset($_POST["quotation"])? limpiarCadena($_POST["quotation"]):"";


$idcontacto_tabla = isset($_POST["idcontacto_tabla"])? limpiarCadena($_POST["idcontacto_tabla"]):"";
//$idvendedor_frmPrimary = isset($_POST["idvendedor_frmPrimary"])? limpiarCadena($_POST["idvendedor_frmPrimary"]):"";


$subtotal_obtengodecotizacion = isset($_POST["txtSubTotal_obtengoDeCotizacion"])? limpiarCadena($_POST["txtSubTotal_obtengoDeCotizacion"]):"";
$igv_obtengodecotizacion = isset($_POST["txtIgv_obtengoDeCotizacion"])? limpiarCadena($_POST["txtIgv_obtengoDeCotizacion"]):"";
$total_venta_obtengodecotizacion = isset($_POST["txtTotal_venta_obtengoDeCotizacion"])? limpiarCadena($_POST["txtTotal_venta_obtengoDeCotizacion"]):"";


//op significa Operacion
switch($_GET["op"]){
    case 'guardaryeditar':
        if(empty($idventa))
        {

            if(empty($idcontacto_tabla))
            {
                echo "Registre el contacto";
            }else
            {
            //var_dump($_POST["idarticulo_obtengoDeCotizacion"]);

            $rspta=$venta->insertar($idcliente,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$subtotal_obtengodecotizacion,$igv_obtengodecotizacion,$total_venta_obtengodecotizacion,$_POST["idarticulo_obtengoDeCotizacion"],$_POST["cantidad_obtengoDeCotizacion"],$_POST["precio_venta_obtengoDeCotizacion"],$_POST["descuento_obtengoDeCotizacion"],$idcontacto_tabla);
            //echo $rspta; 
            echo $rspta ? "Venta registrada" : "No se registraron todos los datos de la venta satisfactoriamente";
            }


        }
        else
        {

        }
    break;
    case 'anular':
    
        $rspta=$venta->anular($idventa);
        echo $rspta ? "Venta anulada" : "Venta no se puedo anular";
    break;

    case 'mostrar':
        $rspta=$venta->mostrar($idventa);
        echo json_encode($rspta);
    break;
    case 'listarDetalle':
        //Obtiene el idventa
        $id=$_GET['id']; 

        $rspta = $venta->listarDetalle($id);
        $total=0;
        echo '
        <thead style="background-color:#A9D0F5">
        <th>Opciones</th>
        <th>Artículo</th>
        <th>Cantidad</th>
        <th>Precio Venta</th>
        <th>Descuento</th>
        <th>Subtotal</th>
        </thead>';
        while ($reg = $rspta->fetch_object())
        {
            echo '<tr class="filas"><td></td><td>'.$reg->nombre. ' - '. $reg->iddetalle_venta.'</td><td>'.$reg->cantidad.'</td><td>'.$reg->precio_venta.'</td><td>'.$reg->descuento.'</td><td>'.$reg->subtotal.'</td></tr>';
            $total =$total+($reg->precio_venta*$reg->cantidad-$reg->descuento);
        }
        echo '
        <tfoot>
            <th>TOTAL</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th><h4 id="total">S/ '. $total.'</h4> <input type="hidden" name="total_venta" id="total_venta"> </th>
        </tfoot>';
    break;
    case 'listar':
        $rspta=$venta->listar();
        $data = Array();
        while ($reg=$rspta->fetch_object()){
            if($reg->tipo_comprobante=='Ticket')
            {
                $url='../reportes/exTicket.php?id=';
            }
            else{
                $url='../reportes/exFactura.php?id=';

            }
            $data[]=array(
                "0"=>($reg->estado=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>'.
                    ' <button class="btn btn-danger" onclick="anular('.$reg->idventa.')"><i class="fa fa-close"></i></button>'
                    .' <a class="btn btn-info" target="_blank" href="'.$url.$reg->idventa.'"> <i class="fa fa-print"></a>':
                    ' <button class="btn btn-danger" onclick="anular('.$reg->idventa.')"><i class="fa fa-close"></i></button>'.'<button class="btn btn-warning" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>',
 
                "1"=>$reg->fecha,
                "2"=>$reg->cliente . " - ".$reg->idventa,
                "3"=>$reg->usuario,
                "4"=>$reg->tipo_comprobante,
                "5"=>$reg->serie_comprobante. '-' .$reg->num_comprobante,
                "6"=>number_format($reg->total_venta,2,SPD,SPM),
                "7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
                '<span class="label bg-red">Anulado</span>'
            );

        }
        $results= array(
            "sEcho"=>1, //info para datatables
            "iTotalRecords"=>count($data),
            "iTotalDisplayRecords"=>count($data),
            "aaData"=>$data);
        echo json_encode($results);
    break;
    case 'selectCliente':
        require_once "../modelos/Persona.php";
        $persona = new Persona();
        $rspta = $persona->listarC();
        echo '<option value="0" >Seleccione</option>';
        echo '<option value="0">Todos</option>';
        while ($reg = $rspta->fetch_object())
            {
              echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
            }
    break;
    
    case 'selectVendedor':
        require_once "../modelos/Venta.php";
        $vendedor = new Venta();
        $rspta = $vendedor->selectVendedor();
        echo '<option value="0" >Seleccione</option>';
        while ($reg = $rspta->fetch_object())
            {
              echo '<option value=' . $reg->idcontacto . '>' . $reg->nombre . ' '. $reg->apellido . '</option>';
            }
    break;
    
    case 'listarArticulosVenta':
    require_once "../modelos/Articulo.php";
    $articulo=new Articulo();
    $rspta=$articulo->listarActivosVenta();
    $data = Array();
    while ($reg=$rspta->fetch_object()){
        $data[]=array(         
            "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\',\''.$reg->precio_venta.'\')"><span class="fa fa-plus"></span></button>',
            "1"=>$reg->nombre,
            "2"=>$reg->categoria,
            "3"=>$reg->codigo,
            "4"=>$reg->stock,
            "5"=>$reg->precio_venta,
            "6"=>'<img width="50" height="50" src="../files/articulos/'.$reg->imagen.'">'
        );

    }
    $results= array(
        "sEcho"=>1, //info para datatables
        "iTotalRecords"=>count($data),
        "iTotalDisplayRecords"=>count($data),
        "aaData"=>$data);
    echo json_encode($results);   

    break;

    case 'searchquotation':

        require_once "../modelos/Cotizacion.php";
        $cotizacion = new Cotizacion();

        $rspta = $cotizacion->searchquotation($quotation);
        echo json_encode($rspta);
        
    break;
    case 'searchquotationdetail':

        require_once "../modelos/Cotizacion.php";
        $cotizacion = new Cotizacion();

        $rspta = $cotizacion->searchquotationdetail($quotation);

        $total=0;
        echo '
        <thead style="background-color:#A9D0F5">
        <th>Opciones</th>
        <th>Artículo</th>
        <th>Cantidad</th>
        <th>Precio Venta</th>
        <th>Descuento</th>
        <th>Subtotal</th>
        </thead>';

        $count = 1;
        while ($reg = $rspta->fetch_object())
        {
            echo '<tr class="filas" id="fila'.$count.'">
                <td></td>
                <td><input type="hidden" name="idarticulo_obtengoDeCotizacion[]" value="'.$reg->idarticulo.'"> '.$reg->articulo.'</td>
                <td><input type="text" name="cantidad_obtengoDeCotizacion[]" id="cantidad[]" value="'.$reg->cantidad.'"></td>
                <td><input type="text" name="precio_venta_obtengoDeCotizacion[]" id="precio_venta[]" value="'.$reg->precio_cotizacion.'"></td>
                <td><input type="text" name="descuento_obtengoDeCotizacion[]" value="'.$reg->descuento.'"></td>
                <td>'.$reg->subtotal.'</td>
                <td></td>
            </tr>';

            $total =$total+($reg->precio_cotizacion*$reg->cantidad-$reg->descuento);
            $subtotal_real = $reg->subtotal/1.18;
            $monto_base = $monto_base+$subtotal_real;
            $v_subtotal = $v_subtotal+$reg->subtotal;
            $igv = $v_subtotal-$monto_base;

            $count++;
        }
        function addTwoDecimals($number)
        {
           return  number_format((float)$number, 2, '.', '');
        }

        echo '
                <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>SubTotal</td>
                    <td><input placeholder="SubTotal" id="idSubtotal" name="txtSubTotal_obtengoDeCotizacion" value="'.addTwoDecimals( $monto_base)  .'" readonly></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>IGV</td>
                    <td><input placeholder="IGV" id="igv" name="txtIgv_obtengoDeCotizacion" value="'. addTwoDecimals($igv).'" readonly></td>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>TOTAL</th>
                    <th>
                        <h4 class="total" id="total">S/ '. addTwoDecimals($v_subtotal).'</h4>
                        <input type="hidden" id="total_venta" name="txtTotal_venta_obtengoDeCotizacion" value="'. addTwoDecimals($v_subtotal).'">
                    </th>
                </tr>
        </tfoot>';
        //echo json_encode($data);
        
    break;

    case 'guardarVendedor':

        if(empty($idvendedor)){
            $option = 1;
            $rspta=$venta->insertarVendedor($nombre,$apellido,$dni,$email,$celular,$whatsapp);

        }

        if($rspta > 0 )
		{
            if($option == 1){
                echo "Vendedor registrado";
            }else{
                echo "Vendedor no se pudo registrar";
            }       
        }else if($rspta == 'exist'){
            echo "El Vendedor registrado ya existe";
        }
    break;


}
?>