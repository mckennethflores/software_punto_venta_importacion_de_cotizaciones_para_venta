<?php
require_once "../config/Conexion.php";

class Contacto
{
    public function __construct()
    {

    }
    public function insertar($nombre,$apellido,$dni,$email,$celular,$whatsapp)
    {
        $sql = "INSERT INTO contacto (nombre,apellido,dni,email,celular,whatsapp)
        VALUES ('$nombre','$apellido','$dni','$email','$celular','$whatsapp')";
        return ejecutarConsulta($sql);

    }
    public function editar ($idcontacto,$nombre,$apellido,$dni,$email,$celular,$whatsapp)
    {
        $sql ="UPDATE contacto SET nombre='$nombre',apellido='$apellido',dni='$dni',email='$email',celular='$celular',whatsapp='$whatsapp' WHERE idcontacto ='$idcontacto'";
        return ejecutarConsulta($sql);
    }

    public function eliminar($idcontacto)
    {
        $sql = "DELETE FROM contacto WHERE idcontacto='$idcontacto' ";
        return ejecutarConsulta($sql);
    }

    public function mostrar($idcontacto)
    {
        $sql = "SELECT * FROM contacto WHERE idcontacto='$idcontacto'";
        return ejecutarConsultaSimpleFila($sql);
    }
    public function validarSunat($num_documento)
    {
        // Recojo informacion de sunat
        $llave = "5b8e0a07c0307c1e5a5c55cb";
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://servicio.fitcoders.com/v1/all?service=RUC&id=$num_documento&key=$llave",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache"
          ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $response = json_decode($response, true);

        return $response;

    }
//Listar Proveedores
    public function listar()
    {
        $sql = "SELECT * FROM contacto WHERE 1=1";
        return ejecutarConsulta($sql);
    }

}
?>