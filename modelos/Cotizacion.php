<?php
require_once "../config/Conexion.php";

class Cotizacion
{
    public function __construct()
    {

    }
    public function insertar($idcliente,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$subtotal,$igv,$total_cotizacion,$idarticulo,$unidadmedida,$cantidad,$precio_cotizacion,$descuento,$tipoMoneda,$idcontacto_tabla = 1)
    {

        $sql = "INSERT INTO cotizacion (idcliente,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,subtotal,igv,total_cotizacion,estado,tipomoneda,idcontacto)
        VALUES ('$idcliente','$idusuario','$tipo_comprobante','$serie_comprobante','0','$fecha_hora','$impuesto',$subtotal,$igv,$total_cotizacion, 'Aceptado','$tipoMoneda','$idcontacto_tabla')";
        // echo $sql;
      //return;
        $idcotizacionnew=ejecutarConsulta_retornarID($sql);
    
        $sqlupdt="UPDATE cotizacion SET num_comprobante='000$idcotizacionnew' WHERE idcotizacion='$idcotizacionnew' ";
        ejecutarConsulta($sqlupdt);

        $num_elementos=0;
        $sw=true;
        while($num_elementos < count($idarticulo))
        {
            $sql_detalle = "INSERT INTO detalle_cotizacion(idcotizacion,idarticulo,unidadmedida,cantidad,precio_cotizacion,descuento) VALUES('$idcotizacionnew','$idarticulo[$num_elementos]','$unidadmedida[$num_elementos]','$cantidad[$num_elementos]','$precio_cotizacion[$num_elementos]','$descuento[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos= $num_elementos+1;
        }

        return $sw;


    }
    
    public function updateRows($idcotizacion,$idarticulo,$unidadmedida,$cantidad,$precio_cotizacion,$descuento,$subtotal,$igv,$total_cotizacion)
    {

        // ELIMINAR LOS ITEMAS AGREGADOS
        $delete_sql_hijo = "DELETE FROM detalle_cotizacion WHERE idcotizacion = '$idcotizacion'";
        ejecutarConsulta($delete_sql_hijo);
       
        // INSERTO LOS ITEMS
        $num_elementos=0;
        $sw=true;

        while($num_elementos < count($idarticulo))
        {
            $sql_detalle = "INSERT INTO detalle_cotizacion(idcotizacion,idarticulo,unidadmedida,cantidad,precio_cotizacion,descuento) VALUES('$idcotizacion','$idarticulo[$num_elementos]','$unidadmedida[$num_elementos]','$cantidad[$num_elementos]','$precio_cotizacion[$num_elementos]','$descuento[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos= $num_elementos+1;
          // echo $sql_detalle."<br>";
        }
        
        $sqlC="UPDATE cotizacion SET subtotal='$subtotal',igv='$igv',total_cotizacion='$total_cotizacion' WHERE idcotizacion='$idcotizacion' ";
        /* echo $sqlC;
        return;     */
        return ejecutarConsulta($sqlC);

    }
   
    
    public function activar($idcotizacion)
    {
        $sql="UPDATE cotizacion SET estado='Aceptado' WHERE idcotizacion='$idcotizacion' ";
        return ejecutarConsulta($sql);
    }
    public function anular($idcotizacion)
    {
        $sql = "SELECT * FROM detalle_cotizacion WHERE idcotizacion = '$idcotizacion'";
        $sql1 = ejecutarConsulta($sql);

        while ($reg = mysqli_fetch_array($sql1)) {

            $idarticulo = $reg['idarticulo'];
            $cantidad = $reg['cantidad'];
            
          /*   $sql3 = "UPDATE articulo
            SET stock = stock + $cantidad
            WHERE idarticulo = '$idarticulo';";
            ejecutarConsulta($sql3); */
            
        }
        $delete_sql_hijo = "DELETE FROM detalle_cotizacion WHERE idcotizacion = '$idcotizacion'";
        ejecutarConsulta($delete_sql_hijo);

        $delete_sql_padre = "DELETE FROM cotizacion WHERE idcotizacion = '$idcotizacion'";
        return ejecutarConsulta($delete_sql_padre);
    }

    public function delete($idDetQuotation)
    {
        //ELIMINAR
        $delete_item = "DELETE FROM detalle_cotizacion WHERE iddetalle_cotizacion = '$idDetQuotation'";
        return ejecutarConsulta($delete_item);

    }

    public function updateQuotation($idQuotation,$subtotal_add,$igv_add,$total_cotizacion)
    {

        $sqlC="UPDATE cotizacion SET subtotal='$subtotal_add',igv='$igv_add',total_cotizacion='$total_cotizacion' WHERE idcotizacion='$idQuotation' ";
        echo $sqlC;
        ejecutarConsulta($sqlC);
    }

    public function mostrar($idcotizacion)
    {
        $sql="SELECT c.idcotizacion,DATE(c.fecha_hora) as fecha,c.idcliente,c.idcontacto,con.idcontacto as idcontacto_con, con.nombre,con.apellido,p.nombre as cliente,u.idusuario,u.nombre as usuario,c.tipo_comprobante,c.serie_comprobante,c.num_comprobante,c.total_cotizacion,c.impuesto,c.estado FROM cotizacion c 
        INNER JOIN persona p ON c.idcliente=p.idpersona 
        INNER JOIN usuario u ON c.idusuario=u.idusuario 
        INNER JOIN contacto con ON c.idcontacto=con.idcontacto 
        WHERE c.idcotizacion='$idcotizacion'";
        return ejecutarConsultaSimpleFila($sql);
    }
//al hacer click en el ojo este codigo trae el detalle
    public function listarDetalle($idcotizacion)
    {
        $sql="SELECT dc.iddetalle_cotizacion, dc.idcotizacion,dc.idarticulo,a.nombre,dc.unidadmedida,dc.cantidad,dc.precio_cotizacion,dc.descuento,(dc.cantidad*dc.precio_cotizacion-dc.descuento) as subtotal FROM detalle_cotizacion dc inner join articulo a on dc.idarticulo=a.idarticulo where dc.idcotizacion='$idcotizacion'";
        return ejecutarConsulta($sql);
    }

    public function listar()
    {
        $sql="SELECT c.idcotizacion, c.fecha_hora as fecha,c.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario,c.tipo_comprobante,c.serie_comprobante,c.num_comprobante,c.total_cotizacion,c.impuesto,c.estado,c.tipomoneda FROM cotizacion c INNER JOIN persona p ON c.idcliente=p.idpersona INNER JOIN usuario u ON c.idusuario=u.idusuario ORDER BY c.idcotizacion desc";
        return ejecutarConsulta($sql);
    }
// Reporte de cotizacions para pdf factura
    public function cotizacioncabecera($idcotizacion){
        $sql="SELECT c.idcotizacion, c.idcliente, p.nombre AS cliente, p.direccion, p.tipo_documento, p.num_documento, p.email, p.telefono, c.idusuario, u.nombre AS usuario, c.tipo_comprobante, c.serie_comprobante, c.num_comprobante, c.fecha_hora AS fecha, c.impuesto,c.subtotal,c.igv, c.total_cotizacion, c.tipomoneda, ctc.nombre, ctc.apellido, ctc.whatsapp, ctc.celular FROM cotizacion c INNER JOIN persona p ON c.idcliente = p.idpersona INNER JOIN contacto ctc ON c.idcontacto = ctc.idcontacto INNER JOIN usuario u ON c.idusuario = u.idusuario WHERE c.idcotizacion='$idcotizacion'";
        return ejecutarConsulta($sql);
    }

    public function cotizaciondetalle($idcotizacion){
        $sql="SELECT a.nombre as articulo,a.codigo,dc.unidadmedida, dc.cantidad,dc.precio_cotizacion,dc.descuento,(dc.cantidad*dc.precio_cotizacion-dc.descuento) as subtotal FROM detalle_cotizacion dc INNER JOIN articulo a ON dc.idarticulo=a.idarticulo  WHERE dc.idcotizacion='$idcotizacion'";
        return ejecutarConsulta($sql);
    }
    public function insertarContacto($nombre,$apellido,$dni,$email,$celular,$whatsapp)
    {

        $sql_consulta="SELECT * from contacto WHERE nombre = '$nombre' and apellido = '$apellido'";
        $request = ejecutarConsulta($sql_consulta);

        while ($reg = mysqli_fetch_array($request)) {

            $idcontacto = $reg['idcontacto'];          
        }

        if(empty($idcontacto))
        {
        $sql = "INSERT INTO contacto (nombre,apellido,dni,email,celular,whatsapp,tipo)
        VALUES ('$nombre','$apellido','$dni','$email','$celular','$whatsapp','1')";
        $request_insert = ejecutarConsulta($sql);

        $return = $request_insert;
        }else
        {
            $return = "exist";
        }

        return $return;
    }
    public function buscarContacto($nombre)
    {
        $sql="SELECT idcontacto,nombre,apellido,dni,email,celular,whatsapp,tipo from contacto WHERE
                    nombre LIKE '%$nombre%'; ";
        return ejecutarConsulta($sql);
    }
    public function searchquotation($idcotizacion){
        $sql = "SELECT * FROM cotizacion WHERE idcotizacion = '$idcotizacion'";
       // echo $sql;
       return ejecutarConsultaSimpleFila($sql);
    }
    public function searchquotationdetail($idcotizacion){
        $sql = "SELECT a.nombre as articulo,a.codigo, a.idarticulo,dc.unidadmedida, dc.cantidad,dc.precio_cotizacion as precio_cotizacion,dc.descuento,(dc.cantidad*dc.precio_cotizacion-dc.descuento) as subtotal FROM detalle_cotizacion dc INNER JOIN articulo a ON dc.idarticulo=a.idarticulo  WHERE dc.idcotizacion='$idcotizacion'";
       // echo $sql;
       return ejecutarConsulta($sql);
    }
}
?>