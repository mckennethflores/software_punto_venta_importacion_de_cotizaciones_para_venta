<?php
require_once "../modelos/Consultas.php";

$consulta = new Consultas();
 
switch($_GET["op"]){
    case 'comprasfecha':
    $fecha_inicio=$_REQUEST["fecha_inicio"];
    $fecha_fin=$_REQUEST["fecha_fin"];

        $rspta=$consulta->comprasfecha($fecha_inicio,$fecha_fin);
        $data = Array();
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->fecha,
                "1"=>$reg->usuario,
                "2"=>$reg->proveedor,
                "3"=>$reg->tipo_comprobante,
                "4"=>$reg->serie_comprobante.' '.$reg->num_comprobante,
                "5"=>$reg->total_compra,
                "6"=>$reg->impuesto,
                "7"=>($reg->estado=='Aceptado')?'
                <span class="label bg-green">Aceptado</span>':
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

    case 'ventasfechacliente':
    $fecha_inicio=$_REQUEST["fecha_inicio"];
    $fecha_fin=$_REQUEST["fecha_fin"];
    $idcliente=$_REQUEST["idcliente"];
        //validacion
        if((int)$idcliente == 0){
            $rspta=$consulta->ventasfecha($fecha_inicio,$fecha_fin);
        }else{
            $rspta=$consulta->ventasfechacliente($fecha_inicio,$fecha_fin,$idcliente);
        }
        
        $data = Array();
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->fecha,
                "1"=>$reg->usuario,
                "2"=>$reg->cliente,
                "3"=>$reg->tipo_comprobante,
                "4"=>$reg->serie_comprobante.' '.$reg->num_comprobante,
                "5"=>$reg->total_venta,
                "6"=>$reg->impuesto,
                "7"=>($reg->estado=='Aceptado')?'
                <span class="label bg-green">Aceptado</span>':
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

    case 'ventasfechaproducto':
    $fecha_inicio=$_REQUEST["fecha_inicio"];
    $fecha_fin=$_REQUEST["fecha_fin"];
    $idarticulo=$_REQUEST["idarticulo"];
        //validacion
        if((int)$idarticulo == 0){
            $rspta=$consulta->ventasfechaproducto($fecha_inicio,$fecha_fin);
        }else{
            $rspta=$consulta->ventasfechaproductoarticulo($fecha_inicio,$fecha_fin,$idarticulo);
        }
        
        $data = Array();
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>$reg->fecha_hora_,
                "1"=>$reg->nombre,
                "2"=>$reg->num_comprobante.' - '.$reg->serie_comprobante,
                "3"=>$reg->cantidad,
                "4"=>$reg->total_venta
            );

        }
        $results= array(
            "sEcho"=>1, //info para datatables
            "iTotalRecords"=>count($data),
            "iTotalDisplayRecords"=>count($data),
            "aaData"=>$data);
        echo json_encode($results);
    break;    

}
?>