<?php
require_once "../config/Conexion.php";

class Consultas
{
    public function __construct()
    {

    }

    public function comprasfecha($fecha_inicio,$fecha_fin)
    {
        $sql = "SELECT DATE(i.fecha_hora) as fecha,u.nombre as usuario, p.nombre as proveedor,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE DATE(i.fecha_hora)>= '$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin'";
        return ejecutarConsulta($sql);
    }
    public function ventasfecha($fecha_inicio,$fecha_fin)
    {
        $sql = "SELECT DATE(v.fecha_hora) as fecha,u.nombre as usuario, p.nombre as cliente,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>= '$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin'";
        return ejecutarConsulta($sql);
    }
    public function ventasfechacliente($fecha_inicio,$fecha_fin,$idcliente)
    {
        $sql = "SELECT DATE(v.fecha_hora) as fecha,u.nombre as usuario, p.nombre as cliente,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>= '$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idcliente='$idcliente'";
        return ejecutarConsulta($sql);
    }
    public function ventasfechaproductoarticulo($fecha_inicio,$fecha_fin,$idarticulo)
    {
        $sql = "SELECT v.idventa,v.fecha_hora,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') AS fecha_hora_ ,v.num_comprobante,v.serie_comprobante,v.total_venta,dv.iddetalle_venta,dv.idventa,dv.idarticulo,a.idarticulo,a.nombre, dv.cantidad,dv.precio_venta,dv.descuento FROM `detalle_venta` as dv INNER JOIN articulo AS a ON dv.idarticulo = a.idarticulo INNER JOIN venta as v ON dv.idventa = v.idventa
        WHERE DATE(v.fecha_hora)>= '$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND a.idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }
    public function ventasfechaproducto($fecha_inicio,$fecha_fin)
    {
        $sql = "SELECT v.idventa,v.fecha_hora, DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') AS fecha_hora_ ,v.num_comprobante,v.serie_comprobante,v.total_venta,dv.iddetalle_venta,dv.idventa,dv.idarticulo,a.idarticulo,a.nombre, dv.cantidad,dv.precio_venta,dv.descuento FROM `detalle_venta` as dv INNER JOIN articulo AS a ON dv.idarticulo = a.idarticulo INNER JOIN venta as v ON dv.idventa = v.idventa
        WHERE DATE(v.fecha_hora)>= '$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin'";
        return ejecutarConsulta($sql);
    }

    public function totalcomprahoy()
    {
        //curdate Obtiene la fecha actual
        $sql="SELECT IFNULL(SUM(total_compra),0) as total_compra FROM ingreso WHERE DATE(fecha_hora)=curdate()";
        return ejecutarConsulta($sql);
    } 
    public function capitalTotal()
    {
        //curdate Obtiene la fecha actual
        $sql="SELECT IFNULL(SUM(total_compra),0) as capital_total FROM ingreso WHERE 1=1;";
        return ejecutarConsulta($sql);
    }

    public function totalventahoy()
    {
        //curdate Obtiene la fecha actual
       // $sql="SELECT IFNULL(SUM(total_venta),0) as total_venta FROM venta WHERE DATE(fecha_hora)=curdate()";
        $sql="SELECT IFNULL(SUM(total_venta),0) as total_venta FROM venta WHERE estado='Aceptado'";
        return ejecutarConsulta($sql);
    }     
    public function comprasultimosdiezdias()
    {
        //curdate Obtiene la fecha actual
        //CONCAT concatenar
        $sql="SELECT CONCAT(DAY(fecha_hora),'-',MONTH(fecha_hora)) as fecha,SUM(total_compra) as total FROM ingreso GROUP BY fecha_hora ORDER BY fecha_hora DESC limit 0,10 ";
        return ejecutarConsulta($sql);
    }
    public function ventasultimosdocemeses()
    {
        //%M convierte fecha_hora a un formato de mes
        $sql="SELECT DATE_FORMAT(fecha_hora, '%M') as fecha,SUM(total_venta) as total FROM venta GROUP BY MONTH(fecha_hora) ORDER BY fecha_hora DESC limit 0,12";
        return ejecutarConsulta($sql);
    }
}

?>