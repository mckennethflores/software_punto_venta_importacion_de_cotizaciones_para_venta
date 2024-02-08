<?php
require_once "../config/Conexion.php";

class Articulo
{
    public function __construct()
    {

    }
    
    public function insertar($idcategoria,$codigo,$nombre,$stock,$descripcion,$unidadmedidaid,$imagen)
    {
        $sql = "INSERT INTO articulo (idcategoria,codigo,nombre,stock,descripcion,unidadmedidaid,imagen,condicion)
        VALUES ('$idcategoria','$codigo','$nombre','$stock', '$descripcion','$unidadmedidaid','$imagen', '1')";
    //  echo $sql;
      return ejecutarConsulta($sql);

    }
    public function editar($idarticulo,$idcategoria,$codigo,$nombre,$stock,$descripcion,$unidadmedidaid,$imagen)
    {
        $sql ="UPDATE articulo SET idcategoria='$idcategoria',codigo='$codigo',nombre='$nombre',
        stock='$stock',descripcion='$descripcion',unidadmedidaid='$unidadmedidaid',imagen='$imagen' WHERE idarticulo ='$idarticulo';";
      // echo $sql;
      return ejecutarConsulta($sql);
    }

    public function desactivar($idarticulo)
    {
        $sql = "UPDATE articulo SET condicion='0' WHERE idarticulo='$idarticulo' ";
        return ejecutarConsulta($sql);
    }
    public function eliminar($idarticulo)
    {
        $sql = "DELETE FROM articulo WHERE idarticulo='$idarticulo' ";
        //echo $sql;
       return ejecutarConsulta($sql);
    }
 
    public function activar($idarticulo)
    {
        $sql = "UPDATE articulo SET condicion='1' WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($idarticulo)
    {
        $sql = "SELECT * FROM articulo WHERE idarticulo='$idarticulo'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar()
    {
        $sql = "SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria";
        return ejecutarConsulta($sql);
    }
    // REGISTROS ACTIVOS
    public function listarActivos()
    {
        $sql = "SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,a.descripcion,a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1'";
        return ejecutarConsulta($sql);
    }
    public function listarActivosVenta()
    {
        $sql="SELECT a.idarticulo,a.idcategoria,a.unidadmedidaid,um.id,um.indice as unidadmedida,c.nombre as categoria,a.codigo,a.nombre,a.stock,(SELECT precio_venta FROM detalle_ingreso WHERE idarticulo=a.idarticulo order by iddetalle_ingreso desc limit 0,1) as precio_venta,a.descripcion,a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria INNER JOIN unidadmedida um ON a.unidadmedidaid=um.id WHERE a.condicion='1'";
        return ejecutarConsulta($sql); 
    }
/*     public function listarActivosCotizacion()
    {
        $sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo,a.nombre,a.stock,(SELECT precio_venta FROM detalle_ingreso WHERE idarticulo=a.idarticulo order by iddetalle_ingreso desc limit 0,1) as precio_venta,a.descripcion,a.imagen,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1'";
        return ejecutarConsulta($sql); 
    } */
        public function selectarticulo()
    {
        $sql="SELECT * FROM articulo";
        return ejecutarConsulta($sql); 
    }

}
?>