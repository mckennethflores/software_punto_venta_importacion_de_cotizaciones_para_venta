<?php 
require_once "global.php";

$conexion = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);

mysqli_query( $conexion, 'SET NAMES "'.DB_ENCODE.'"');

//Si tenemos un posible error en la conexión lo mostramos
if (mysqli_connect_errno())
{
	printf("Falló conexión a la base de datos: %s\n",mysqli_connect_error());
	exit();
}

 
if (!function_exists('ejecutarConsulta'))
{
	function ejecutarConsulta($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);
		return $query;
	}

	function ejecutarConsultaSimpleFila($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);		
		$row = $query->fetch_assoc();
		return $row;
	}

	function ejecutarConsulta_retornarID($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);		
		return $conexion->insert_id;			
	}

	function limpiarCadena($str)
	{
		global $conexion;
		$str = mysqli_real_escape_string($conexion,trim($str));
		return htmlspecialchars($str);
	}
	function formatDateMysql($date){
		$date = str_replace('/', '-', $date );
		$newDate = date("d-m-Y", strtotime($date));
		return $newDate;
	}

	function isHaveNumCelular($texto){
		$reg_exp = getRegExpNumCelular();
		return preg_match($reg_exp,$texto);
	}

	function replaceNumCelular($texto){
		
		$reg_exp = getRegExpNumCelular();

		$texto = preg_replace($reg_exp, '********', $texto);
		return $texto;
	}
	
	function getRegExpNumCelular(){
		
		$partExpresion = "([ \t|\-\_\.\`\~\!\@\#\$\%\^\&\*\(\)\=\+\/\{\}\[\]\;\:\'\"\<\>\,\?|a-z|A-Z]){0,4}[0-9]{1}";

		$reg_exp = "/([0-9]{1}".$partExpresion.$partExpresion.$partExpresion.$partExpresion.$partExpresion.")/";

		return  $reg_exp;

	}
	function dep($data){
    
		$format = print_r('<pre>');
		$format .= print_r($data);
		return $format;
	}
	
}
?>