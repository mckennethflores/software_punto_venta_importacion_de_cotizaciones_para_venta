<?php
require_once "../modelos/Articulo.php";

$articulo = new Articulo();
// condicion de una sola linea
$idarticulo = isset($_POST["idarticulo"])? limpiarCadena($_POST["idarticulo"]):"";
$idcategoria = isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
$codigo = isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$nombre = isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$stock = isset($_POST["stock"])? limpiarCadena($_POST["stock"]):"";
$descripcion = isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$unidadmedidaid = isset($_POST["unidadmedidaid"])? limpiarCadena($_POST["unidadmedidaid"]):"";
$imagen = isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";


//op significa Operacion
switch($_GET["op"]){
    case 'guardaryeditar':
    // if (usuario no ha seleccionado ningun archivo o no existe ningun archivo dentro del objeto)
    if(!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
    {
        $imagen =$_POST["imagenactual"];

    }
    else
    {
        // $ext = $extension
        $ext = explode(".", $_FILES["imagen"]["name"]);
        if($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
        {
            $imagen = round(microtime(true)) . '.' . end($ext);
            move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/articulos/" .$imagen);
        }
    }
        if(empty($idarticulo)){

           // $nombre_ = isHaveNumCelular($nombre);

            $rspta=$articulo->insertar($idcategoria,$codigo,$nombre,$stock,$descripcion,$unidadmedidaid,$imagen);
         // echo $rspta;
         echo $rspta ? "Artículo registrado" : "Artículo no se pudo registrar";
        }
        else {
            $rspta=$articulo->editar($idarticulo,$idcategoria,$codigo,$nombre,$stock,$descripcion,$unidadmedidaid,$imagen);
            echo $rspta ? "Artículo actualizado" : "Artículo no se pudo actualizar";
        }
    break;
    case 'desactivar':
        $rspta=$articulo->desactivar($idarticulo);
        echo $rspta ? "Artículo Desactivado" : "Artículo no se puede desactivar";
    break;
    case 'eliminar':
        $rspta=$articulo->eliminar($idarticulo);
       // echo $rspta;
       echo $rspta ? "Artículo Eliminado" : "Artículo no se puede Eliminar";
    break;
    case 'activar':
        $rspta=$articulo->activar($idarticulo);
        echo $rspta ? "Artículo activado" : "Artículo no se pudo activar";      
    break;
    case 'mostrar':
        $rspta=$articulo->mostrar($idarticulo);
        echo json_encode($rspta);
    break;
    case 'listar':
        $rspta=$articulo->listar();
        $data = Array();
        while ($reg=$rspta->fetch_object()){
            $data[]=array(
                "0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-eye"></i></button>'.
                ' <button class="btn btn-alert" onclick="desactivar('.$reg->idarticulo.')"><i class="fa fa-close"></i></button>'.
                ' <button class="btn btn-danger" onclick="eliminar('.$reg->idarticulo.')"><i class="fa fa-close"></i></button>':
                '<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-eye"></i></button>'.
                ' <button class="btn btn-primary" onclick="activar('.$reg->idarticulo.')"><i class="fa fa-check"></i></button>'.
                ' <button class="btn btn-danger" onclick="eliminar('.$reg->idarticulo.')"><i class="fa fa-close"></i></button>',
                "1"=>$reg->nombre,
                "2"=>$reg->categoria,
                "3"=>$reg->codigo,
                "4"=>$reg->stock,
                "5"=>'<img width="50" height="50" src="../files/articulos/'.$reg->imagen.'">',
                "6"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
            );

        }
        $results= array(
            "sEcho"=>1, //info para datatables
            "iTotalRecords"=>count($data),
            "iTotalDisplayRecords"=>count($data),
            "aaData"=>$data);
        echo json_encode($results);
    break;
    case "selectCategoria":
        require_once "../modelos/Categoria.php";
        $categoria = new Categoria();
        $rspta = $categoria->select();

        while($reg = $rspta->fetch_object())
            {
                echo '<option value=' . $reg->idcategoria . '>' .  $reg->nombre . '</option>';
            }
    break;
    
    case "selectArticulo":
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $rspta = $articulo->selectarticulo();
            echo '<option value="0">Todos</option>';
        while($reg = $rspta->fetch_object())
            {
                echo '<option value=' . $reg->idarticulo . '>' .  $reg->nombre . '</option>';
            }
    break;
    case "selectUnidadMedida":
        require_once "../modelos/UnidadMedida.php";
        $unidadmedida = new UnidadMedida();
        $rspta = $unidadmedida->select();
            echo '<option value="0" selected disabled >Seleccione</option>';
        while($reg = $rspta->fetch_object())
            {
                echo '<option value=' . $reg->id . '>' .  $reg->indice . '</option>';
            }
    break;


}
?>