<?php
require_once "../modelos/Contacto.php";

$contacto = new Contacto();
// condicion de una sola linea
$idcontacto = isset($_POST["idcontacto"])? limpiarCadena($_POST["idcontacto"]):"";
$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$apellido = isset($_POST["apellido"])? limpiarCadena($_POST["apellido"]):"";
$dni = isset($_POST["dni"])? limpiarCadena($_POST["dni"]):"";
$email = isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$celular = isset($_POST["celular"])? limpiarCadena($_POST["celular"]):"";
$whatsapp = isset($_POST["whatsapp"])? limpiarCadena($_POST["whatsapp"]):"";
//op significa Operacion
switch($_GET["op"]){
    case 'guardar':
        if(empty($idcontacto)){
            $rspta=$contacto->insertar($nombre,$apellido,$dni,$email,$celular,$whatsapp);
            echo $rspta ? "Contacto registrado" : "Contacto no se pudo registrar";
        }
        else {
            $rspta=$contacto->editar($idcontacto,$nombre,$apellido,$dni,$email,$celular,$whatsapp);
            echo $rspta ? "Contacto actualizado" : "Contacto no se pudo actualizar";
        }
    break;
    case 'eliminar':
        $rspta=$contacto->eliminar($idcontacto);
        echo $rspta ? "Contacto eliminado" : "Contacto no se puede eliminar";
    break;
    case 'mostrar':
        $rspta=$contacto->mostrar($idcontacto);
        echo json_encode($rspta);
    break;
    case 'validarSunat':
        $rspta=$contacto->validarSunat($dni);
        
       echo json_encode($rspta);
    break;
    case 'listar':
        $rspta=$contacto->listar();
        $data = Array();
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idcontacto.')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-danger" onclick="eliminar('.$reg->idcontacto.')"><i class="fa fa-trash"></i></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->apellido,
                "3"=>$reg->dni,
                "4"=>$reg->celular,
                "5"=>$reg->whatsapp,
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