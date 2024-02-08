<?php
require_once "../modelos/UnidadMedida.php";

$unidadmedida = new UnidadMedida();
// condicion de una sola linea
$id = isset($_POST["id"])? limpiarCadena($_POST["id"]):"";
$indice = isset($_POST["indice"])? limpiarCadena($_POST["indice"]):"";
//op significa Operacion
switch($_GET["op"]){
    case 'guardaryeditar':
        if(empty($id)){
            $rspta=$unidadmedida->insertar($indice);
            echo $rspta ? "Unidad de medida registrada" : "Unidad de medida no se pudo registrar";
        }
        else {
            $rspta=$unidadmedida->editar($id,$indice);
            echo $rspta ? "Unidad de medida actualizada" : "Unidad de medida no se pudo actualizar";
        }
    break;
    case 'desactivar':
        $rspta=$unidadmedida->desactivar($id);
        echo $rspta ? "Unidad de medida Desactivada" : "Unidad de medida no se puede desactivar";
    break;
    case 'activar':
        $rspta=$unidadmedida->activar($id);
        echo $rspta ? "Unidad de medida activada" : "Unidad de medida no se pudo activar";      
    break;
    case 'mostrar':
        $rspta=$unidadmedida->mostrar($id);
        echo json_encode($rspta);
    break;
    case 'listar':
        $rspta=$unidadmedida->listar();
        $data = Array();
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->id.')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-danger" onclick="desactivar('.$reg->id.')"><i class="fa fa-close"></i></button>':
                '<button class="btn btn-warning" onclick="mostrar('.$reg->id.')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-primary" onclick="activar('.$reg->id.')"><i class="fa fa-check"></i></button>',
                "1"=>$reg->indice,
                "2"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
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