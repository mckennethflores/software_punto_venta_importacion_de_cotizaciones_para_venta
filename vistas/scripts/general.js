function getSunat(){
	var num_documento = $("#num_documento").val();
	//console.log(num_documento);
	var formData = new FormData($("#formulario")[0]);
	$.ajax({
		url: "../ajax/persona.php?op=validarSunat&idDocumento="+ num_documento,
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function(datos) //datos mensaje de archivo categoria ajax
		{

			data = JSON.parse(datos); // convierte los datos que se esta recibiendo de la url a un objeto javascrit
var direccion = data.item.direccion;
var distrito = data.item.distrito;
var provincia = data.item.provincia;
		$("#nombre").val(data.item.razon_social);
		$("#direccion").val(direccion+ '-'+ distrito+ '-'+ provincia);
		/* 	console.log(data);	  
			console.log(datos);	   */
		}
	});
}
function getReniec(){
	var num_documento = $("#dni").val();
	 
	var formData = new FormData($("#frmContacto")[0]);
	$.ajax({
		url: "../ajax/persona.php?op=validarReniec&idDocumento="+ num_documento,
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function(datos) //datos mensaje de archivo categoria ajax
		{

			data = JSON.parse(datos); // convierte los datos que se esta recibiendo de la url a un objeto javascrit
var nombre = data.item.nombre;
var paterno = data.item.paterno;
var materno = data.item.materno;
		$("#nombre").val(data.item.nombre);
		$("#apellido").val(paterno+ ' '+ materno);
			console.log(data);	  
			console.log(datos);	  
		}
	});
}