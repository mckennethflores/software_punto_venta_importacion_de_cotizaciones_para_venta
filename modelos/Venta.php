<?php
require_once "../config/Conexion.php";

class Venta
{
    public function __construct()
    {

    }
                    //insertar($idcliente,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$subtotal_obtengodecotizacion,$igv_obtengodecotizacion,$total_venta_obtengodecotizacion,$_POST["idarticulo_obtengoDeCotizacion"],$_POST["cantidad_obtengoDeCotizacion"],$_POST["precio_venta_obtengoDeCotizacion"],$_POST["descuento_obtengoDeCotizacion"]);
    public function insertar($idcliente,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$subtotal_obtengodecotizacion,$igv_obtengodecotizacion,$total_venta_obtengodecotizacion,$idarticulo_obtengoDeCotizacion,$cantidad_obtengoDeCotizacion,$precio_venta_obtengoDeCotizacion,$descuento_obtengoDeCotizacion,$idcontacto_tabla)
    {
        $sql = "INSERT INTO venta (idcliente,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,subtotal,igv,total_venta,estado,idvendedor)
        VALUES ('$idcliente','$idusuario','$tipo_comprobante','$serie_comprobante','$num_comprobante','$fecha_hora','$subtotal_obtengodecotizacion','$igv_obtengodecotizacion','$total_venta_obtengodecotizacion', 'Aceptado',$idcontacto_tabla)";
        
        
        //echo $sql; return;
        //var_dump($precio_venta_obtengoDeCotizacion);
        $idventanew=ejecutarConsulta_retornarID($sql);
        $num_elementos=0;
        $sw=true;
        while($num_elementos < count($idarticulo_obtengoDeCotizacion))
        {
            $sql_detalle = "INSERT INTO detalle_venta(idventa,idarticulo,cantidad,precio_venta,descuento) VALUES('$idventanew','$idarticulo_obtengoDeCotizacion[$num_elementos]','$cantidad_obtengoDeCotizacion[$num_elementos]','$precio_venta_obtengoDeCotizacion[$num_elementos]','$descuento_obtengoDeCotizacion[$num_elementos]')";
            //echo $sql_detalle."<br/>";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos= $num_elementos+1;
        }

        return $sw;

    }

/*     public function insertar($idcliente,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_venta,$idarticulo,$cantidad,$precio_venta,$descuento)
    {
        $sql = "INSERT INTO venta (idcliente,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_venta,estado)
        VALUES ('$idcliente','$idusuario','$tipo_comprobante','$serie_comprobante','$num_comprobante','$fecha_hora','$impuesto','$total_venta', 'Aceptado')";


       // echo $sql; return;
    
        $idventanew=ejecutarConsulta_retornarID($sql);
        $num_elementos=0;
        $sw=true;
        while($num_elementos < count($idarticulo))
        {
            $sql_detalle = "INSERT INTO detalle_venta(idventa,idarticulo,cantidad,precio_venta,descuento) VALUES('$idventanew','$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]')";
            
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos= $num_elementos+1;
        }

        return $sw;

    } */

    public function activar($idventa)
    {
        $sql="UPDATE venta SET estado='Aceptado' WHERE idventa='$idventa' ";
        return ejecutarConsulta($sql);
    }
    public function anular($idventa)
    {
        $sql = "SELECT * FROM detalle_venta WHERE idventa = '$idventa'";
        $sql1 = ejecutarConsulta($sql);

        while ($reg = mysqli_fetch_array($sql1)) {

            $idarticulo = $reg['idarticulo'];
            $cantidad = $reg['cantidad'];
            
            $sql3 = "UPDATE articulo
            SET stock = stock + $cantidad
            WHERE idarticulo = '$idarticulo';";
            ejecutarConsulta($sql3);
            
        }
        $delete_sql_hijo = "DELETE FROM detalle_venta WHERE idventa = '$idventa'";
        ejecutarConsulta($delete_sql_hijo);

        $delete_sql_padre = "DELETE FROM venta WHERE idventa = '$idventa'";
        ejecutarConsulta($delete_sql_padre);
    }
    public function anular2($idingreso)
    {
        
        $sql = "SELECT * FROM detalle_ingreso WHERE idingreso = '$idingreso'";

        $sql1 = ejecutarConsulta($sql);
	 
        while ($reg = mysqli_fetch_array($sql1)) {

            $idarticulo = $reg['idarticulo'];
            $cantidad = $reg['cantidad'];
            
            $sql3 = "UPDATE articulo
            SET stock = stock - $cantidad
            WHERE idarticulo = '$idarticulo';";
            ejecutarConsulta($sql3);
            
        }
        $delete_sql_hijo = "DELETE FROM detalle_ingreso WHERE idingreso = '$idingreso'";
        ejecutarConsulta($delete_sql_hijo);

        $delete_sql_padre = "DELETE FROM ingreso WHERE idingreso = '$idingreso'";
        ejecutarConsulta($delete_sql_padre);
    }
    public function mostrar($idventa)
    {
        $sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa'";
        return ejecutarConsultaSimpleFila($sql);
    }
//al hacer click en el ojo este codigo trae el detalle
    public function listarDetalle($idventa)
    {
        $sql="SELECT dv.iddetalle_venta, dv.idventa,dv.idarticulo,a.nombre,dv.cantidad,dv.precio_venta,dv.descuento,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal FROM detalle_venta dv inner join articulo a on dv.idarticulo=a.idarticulo where dv.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    public function listar()
    {
        $sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario ORDER BY v.idventa desc";
        return ejecutarConsulta($sql);
    }
// Reporte de ventas para pdf factura
    public function ventacabecera($idventa){
        $sql="SELECT
        v.idventa,
        v.idcliente,
        p.nombre AS cliente,
        p.direccion,
        p.tipo_documento,
        p.num_documento,
        p.email,
        p.telefono,
        v.idusuario,
        u.nombre AS usuario,
        v.tipo_comprobante,
        v.serie_comprobante,
        v.num_comprobante,
        date(v.fecha_hora) AS fecha,
        v.impuesto,
        v.total_venta,
        v.subtotal,
        v.igv,
        v.idvendedor,
        contacto.idcontacto AS idvendedor2,
        CONCAT(contacto.nombre, ' ', contacto.apellido) AS vende_nom_ape
        FROM
        venta AS v
        INNER JOIN persona AS p ON v.idcliente = p.idpersona
        INNER JOIN usuario AS u ON v.idusuario = u.idusuario
        INNER JOIN contacto ON contacto.idcontacto = v.idvendedor
        WHERE v.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }
    public function insertarVendedor($nombre,$apellido,$dni,$email,$celular,$whatsapp)
    {

        $sql_consulta="SELECT * from contacto WHERE nombre = '$nombre' and apellido = '$apellido'";
        $request = ejecutarConsulta($sql_consulta);

        while ($reg = mysqli_fetch_array($request)) {

            $idvendedor = $reg['idcontacto'];          
        }

        if(empty($idvendedor))
        {
        $sql = "INSERT INTO contacto (nombre,apellido,dni,email,celular,whatsapp,tipo)
        VALUES ('$nombre','$apellido','$dni','$email','$celular','$whatsapp','2')";
        $request_insert = ejecutarConsulta($sql);

        $return = $request_insert;
        }else
        {
            $return = "exist";
        }

        return $return;
    }

    public function selectVendedor()
    {
        $sql="SELECT * from contacto WHERE idcontacto>0 AND tipo = '2' ";
        return ejecutarConsulta($sql);
    }

    public function buscarVendedor($nombre)
    {
        $sql="SELECT idcontacto,nombre,apellido,dni,email,celular,whatsapp,tipo from contacto WHERE
                    nombre LIKE '%$nombre%'; ";
        return ejecutarConsulta($sql);
    }
    
    public function ventadetalle($idventa){
        $sql="SELECT a.nombre as articulo,a.codigo,d.cantidad,d.precio_venta,d.descuento,(d.cantidad*d.precio_venta-d.descuento) as subtotal FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo=a.idarticulo  WHERE d.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

}
?>