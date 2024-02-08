<?php
require_once "../config/Conexion.php";

class Ingreso
{
    public function __construct()
    {

    }
    public function insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_compra,$idarticulo,$cantidad,$precio_compra,$precio_venta)
    {
        $sql = "INSERT INTO ingreso (idproveedor,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_compra,estado)
        VALUES ('$idproveedor','$idusuario','$tipo_comprobante','$serie_comprobante','$num_comprobante','$fecha_hora','$impuesto','$total_compra', 'Aceptado')";
    
        $idingresonew=ejecutarConsulta_retornarID($sql);
        $num_elementos=0;
        $sw=true;
        while($num_elementos < count($idarticulo))
        {
            $sql_detalle = "INSERT INTO detalle_ingreso(idingreso,idarticulo,cantidad,precio_compra,precio_venta) VALUES('$idingresonew','$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_compra[$num_elementos]','$precio_venta[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos= $num_elementos+1;
        }

        return $sw;

    }


/*     DROP TRIGGER IF EXISTS `trig_actualizarStock`;CREATE DEFINER=`root`@`localhost`
     TRIGGER `trig_actualizarStock` BEFORE INSERT ON `movimiento`
      FOR EACH ROW BEGIN -- Declaras una variable para almacenar el nuevo valor de stock_pro DECLARE nuevo_stock_pro INT;
       -- Si tipomovimientoid es '1', entonces... IF
        NEW.tipomovimientoid = '1' THEN
         -- Calculas el nuevo valor de stock_pro sumando la cantidad actual en stock y la nueva cantidad 
         SET nuevo_stock_pro = (SELECT stock_pro FROM producto WHERE idpro = NEW.productoid) + NEW.cantidad;
          -- actualizo el stock incrementandolo UPDATE producto SET stock_pro = stock_pro + NEW.cantidad WHERE producto.idpro = NEW.productoid;
           -- inserto el valor directamente al kardex INSERT INTO kardex (tipomovimientoid,ingreso,salida,saldo) VALUES ( 1,NEW.cantidad,0,nuevo_stock_pro);
            ELSE UPDATE producto SET stock_pro = stock_pro - NEW.cantidad WHERE producto.idpro = NEW.productoid; END IF; END */
    public function activar($idingreso)
    {
        $sql = "UPDATE ingreso SET estado='Aceptado' WHERE idingreso='$idingreso' ";
        return ejecutarConsulta($sql);
    }
    public function anular($idingreso)
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

    public function mostrar($idingreso)
    {
        $sql = "SELECT i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor,p.nombre as proveedor,u.idusuario,u.nombre as usuario,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p  ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario = u.idusuario WHERE idingreso='$idingreso'";
        return ejecutarConsultaSimpleFila($sql);
    }
//al hacer click en el ojo este codigo trae el detalle
    public function listarDetalle($idingreso)
    {
        $sql="SELECT di.idingreso,di.idarticulo,a.nombre,di.cantidad,di.precio_compra,di.precio_venta FROM detalle_ingreso di inner join articulo a on di.idarticulo=a.idarticulo where di.idingreso='$idingreso'";
        return ejecutarConsulta($sql);
    }

    public function listar()
    {
        $sql = "SELECT i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor,p.nombre as proveedor,u.idusuario,u.nombre as usuario,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p  ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario = u.idusuario ORDER BY i.idingreso desc";
        return ejecutarConsulta($sql);
    }
   
}
?>