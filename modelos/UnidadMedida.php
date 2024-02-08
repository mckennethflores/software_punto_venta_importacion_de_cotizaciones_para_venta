<?php
require_once "../config/Conexion.php";

class UnidadMedida
{
    public function __construct()
    {

    }
    public function insertar($indice)
    {
        $sql = "INSERT INTO unidadmedida (indice)  VALUES ('$indice')";
    //  echo $sql;
      return ejecutarConsulta($sql);

    }
    public function editar($id,$indice)
    {
        $sql ="UPDATE unidadmedida SET indice='$indice'";
      // echo $sql;
      return ejecutarConsulta($sql);
    }

    public function desactivar($id)
    {
        $sql = "UPDATE unidadmedida SET condicion='0' WHERE id='$id' ";
        return ejecutarConsulta($sql);
    }

 
    public function activar($id)
    {
        $sql = "UPDATE unidadmedida SET condicion='1' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($id)
    {
        $sql = "SELECT * FROM unidadmedida WHERE id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar()
    {
        $sql = "SELECT * from unidadmedida";
        return ejecutarConsulta($sql);
    }
    //funcion que muestra en el selec todos los registros 
    public function select()
    {
        $sql = "SELECT * FROM unidadmedida WHERE condicion = 1";
        return ejecutarConsulta($sql);
    }
}
?>