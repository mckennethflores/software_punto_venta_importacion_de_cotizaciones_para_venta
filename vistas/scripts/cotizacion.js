var tabla;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	});
	
	//Cargamos los items al select proveedor
	$.post("../ajax/cotizacion.php?op=selectCliente", function(r){
	            $("#idcliente").html(r);
	            $('#idcliente').selectpicker('refresh');
	});
}
function guardarInfop(){
	var subtotal = $("#subtotal").val();
	var igv = $("#igv").val();
	var total = $("#total_cotizacion").val();
	$.post("../ajax/cotizacion.php?op=actualizarC", {subtotal_POST:subtotal,igv_POST:igv,total_POST:total}, function(e){
		bootbox.alert(e);
    });
			
}
function removeItemQuotation(idQuotation,idDetQuotation){
	//console.log("demo"+idQuotation+"-"+idDetQuotation); return;
	
	bootbox.confirm("¿Está Seguro de eliminar el item?", function(result){
		if(result)
        {
			// idcotacion es iddetallecotizacion y viceversa ,es al reves 
        	$.post("../ajax/cotizacion.php?op=delete", {idDetQuotation_POST:idDetQuotation}, function(e){
			
			bootbox.alert(e);
			deleteExistingTable();
	        mostrarDetalle(idQuotation);
			listar();
        	});
        }
	});
}
function deleteExistingTable(){
	var tabla = document.getElementById("detalles");
	if (tabla) {
        // Elimina todas las filas de la tabla
        while (tabla.rows.length > 0) {
          tabla.deleteRow(0);
        }
        console.log("Contenido de la tabla eliminado");
      } else {
        console.log("No se encontró la tabla con el ID especificado");
    }
}
function UpdateQuotation(){

	var idQuotation = $("#idcotizacion").val();
	var subtotal = $("#subtotal").val();
	var igv = $("#igv").val();
	var total = $("#total_cotizacion").val();
/* 

	if(idQuotation == undefined || idQuotation == '' || idQuotation == null){
		bootbox.alert("Porfavor, Seleccione el idQuotation");
		return;
	}
	if(subtotal == undefined || subtotal == '' || subtotal == null){
		bootbox.alert("Porfavor, Seleccione el subtotal");
		return;
	}
	if(igv == undefined || igv == '' || igv == null){
		bootbox.alert("Porfavor, Seleccione el igv");
		return;
	}
	if(total == undefined || total == '' || total == null){
		bootbox.alert("Porfavor, Seleccione el total");
		return;
	}
	console.log(idQuotation);
	console.log(subtotal);
	console.log(igv);
	console.log(total); */

	/* updateRows(e); */

	$.post("../ajax/cotizacion.php?op=updateQuotationAjax", {idQuotation_POST: idQuotation,subtotal_POST:subtotal,igv_POST:igv,total_POST:total}, function(e){
		bootbox.alert(e);
    });

}

$("#frmContacto").on("submit",function(e)
{
		e.preventDefault(); 
	 
		guardarContacto(e);

		//frmContacto.reset();
		
});
	
//Función limpiar
function limpiar()
{
	
	$("#idcliente").val("");
	$("#idcliente").selectpicker('refresh');
	$('#filaContacto').remove();
	$("#idcotizacion").val("");
	$("#subtotal").val("");
	$("#igv").val("");
	$("#btnBuscarContacto").prop("disabled",false);

	$("#cliente").val("");
	$("#serie_comprobante").val("");
	$("#num_comprobante").val("");
	$("#impuesto").val("0");

	$("#total_cotizacion").val("");
	$(".filas").remove();
	$("#total").html("0");

	//Obtenemos la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_hora').val(today);

    //Marcamos el primer tipo_documento
    $("#tipo_comprobante").val("Boleta");
	$("#tipo_comprobante").selectpicker('refresh');
}

//Función mostrar formulario
function mostrarform(flag)
{
	limpiar();
	if (flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		listarArticulos();

		$("#btnGuardar").hide();
		$("#btnUpdate").hide(); 
		$("#btnCancelar").show();
		$("#btnAgregarArt").show();
		detalles=0;
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//Función cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
}

//Función Listar
function listar()
{
	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		        //    'copyHtml5',
		            'excelHtml5',
		        //    'csvHtml5',
		            'pdf'
		        ],
		"ajax":
				{
					url: '../ajax/cotizacion.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 20,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}


//Función ListarArticulos
function listarArticulos()
{
	tabla=$('#tblarticulos').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		            
		        ],
		"ajax":
				{
					url: '../ajax/cotizacion.php?op=listarArticulosCotizacion',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}

function setearTipoMoneda(){
	var tipo_moneda=$("#tipo_moneda option:selected").text();
	  console.log(tipo_moneda);
  	if (tipo_moneda=='DOLARES')
    {
		 $("#tipoMoneda").val(tipo_moneda);
    }
    else 
    {
        $("#tipoMoneda").val(tipo_moneda);
    }
}
//Función para guardar o editar

function guardaryeditar(e){

	e.preventDefault();

	
	var idcliente = $("#idcliente").val();
	var idcontacto_tabla = $("#idcontacto_tabla").val();
	var total_cotizacion = $("#total_cotizacion").val();

	if(idcliente == null) {
		bootbox.alert("Porfavor, Seleccione el Cliente");
		return;
	}
	if(idcontacto_tabla == undefined || idcontacto_tabla == '' || idcontacto_tabla == null){
		bootbox.alert("Porfavor, Seleccione el contacto");
		return;
	}
	if(total_cotizacion == 0 || total_cotizacion == null){
		bootbox.alert("Porfavor, Vuelve agregar los productos, no se agrego el monto total");
		return;
	}


		setearTipoMoneda();
		//$("#btnGuardar").prop("disabled",true);
		var formData = new FormData($("#formulario")[0]);

		$.ajax({
			url: "../ajax/cotizacion.php?op=guardaryeditar",
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,

			success: function(datos)
			{                
				console.log(datos);    
				bootbox.alert(datos);	          
				mostrarform(false);
				listar();
			}

		});
		limpiar();
	
}

function guardarContacto()
{
	var formData = new FormData($("#frmVendedor")[0]);

	$.ajax({
		url: "../ajax/cotizacion.php?op=guardarContacto",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
			bootbox.alert(datos);	              
			$("#modalAddCustomer").modal("hide");
	         
	    }

	});
	limpiar();
}

$('#btnBuscarContacto').click(function () {
	buscarContacto();
 });
function buscarContacto()
{	
	tabla=$('#detalleBusqueda').dataTable(
		{
			"aProcessing": true,//Activamos el procesamiento del datatables
			"aServerSide": true,//Paginación y filtrado realizados por el servidor
			dom: 'Bfrtip',//Definimos los elementos del control de tabla
			buttons: [		          
						
					],
			"ajax":
					{
						url: '../ajax/cotizacion.php?op=buscarContacto',
						type : "POST",
						dataType : "json",						
						error: function(e){
							console.log(e.responseText);	
						}
					},
			"bDestroy": true,
			"iDisplayLength": 10,//Paginación
			"order": [[ 0, "desc" ]]//Ordenar (columna,orden)
		}).DataTable();
}
function mostrarContactoDetalle(idcontacto,nombre,apellido)
  {

    if (idcontacto !="")
    {
     
    	var fila='<tr class="filas2" id="filaContacto">'+
    	'<td><input type="hidden" name="idcontacto_tabla" value="'+idcontacto+'" id="idcontacto_tabla"  > <input type="text" name="nombre_tabla" id="nombre_tabla" value="'+nombre+'"></td>'+
    	'<td><input type="text" name="apellido_tabla" id="apellido_tabla" value="'+apellido+'"></td>'+
    	'</tr>';
    	cont++;
     
    	$('#tablaDetalleContacto').append(fila);

	 
		$("#modalSearchCustomer").modal("hide");
	 
		$("#btnBuscarContacto").prop("disabled",true);
    }
    else
    {
    	bootbox.alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
  }

function mostrar(idcotizacion)
{
	$.post("../ajax/cotizacion.php?op=mostrar",{idcotizacion : idcotizacion}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);
		mostrarDetalle(data.idcotizacion);
		$("#idcliente").val(data.idcliente);
		$("#idcliente").selectpicker('refresh');
		$("#tipo_comprobante").val('COTIZACION');
		$("#tipo_comprobante").selectpicker('refresh');
		$("#serie_comprobante").val(data.serie_comprobante);
		$("#num_comprobante").val(data.num_comprobante);
		$("#fecha_hora").val(data.fecha);
		$("#impuesto").val(data.impuesto);
		$("#idcotizacion").val(data.idcotizacion);
		
		var btnGuardar = document.getElementById("btnGuardar");
			btnGuardar.innerHTML = "Actualizar";
			btnGuardar.classList.remove("btn-primary");
			btnGuardar.classList.add("btn-success");

		var btnAgregarArt = document.getElementById("btnAgregarArt");
			btnAgregarArt.classList.add("oculto");

		var fila='<tr class="filas2" id="filaContacto">'+
    	'<td><input type="hidden" name="idcontacto_tabla" value="'+data.idcontacto_con+'" id="idcontacto_tabla"><input type="text" name="nombre_tabla" id="nombre_tabla" value="'+data.nombre+'"></td>'+
    	'<td><input type="text" name="apellido_tabla" id="apellido_tabla" value="'+data.apellido+'"></td>'+
    	'</tr>';
    
     
    	$('#tablaDetalleContacto').append(fila);

		//Ocultar y mostrar los botones

		$("#btnGuardar").show();
		$("#btnCancelar").show();
	 
 	});
}
function mostrarDetalle(idcotizacion){
	$.post("../ajax/cotizacion.php?op=listarDetalle&id="+idcotizacion,function(r){
		$("#detalles").html(r);
	});
}
function activar(idcotizacion)
{
	bootbox.confirm("¿Está Seguro de activar el artículo?", function(result){
		if(result)
        {
        	$.post("../ajax/cotizacion.php?op=activar", {idcotizacion : idcotizacion}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para anular registros
function anular(idcotizacion)
{
	bootbox.confirm("¿Está Seguro de eliminar la cotizacion?", function(result){
		if(result)
        {
        	$.post("../ajax/cotizacion.php?op=anular", {idcotizacion : idcotizacion}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
				mostrarform(false);
				listar();
        	});	
        }
	});
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto=18;
var cont=0;
var detalles=0;
//$("#guardar").hide();
$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto()
  {
	 
  	var tipo_comprobante=$("#tipo_comprobante option:selected").text();
	  console.log(tipo_comprobante);
  	if (tipo_comprobante=='COTIZACION')
    {
       $("#impuesto").val(impuesto); 
		console.log("cotizacion:"+impuesto);
    }
    else 
    {
        $("#impuesto").val("0"); 
    }
  }

function agregarDetalle(idarticulo,unidadmedida,articulo,precio_cotizacion)
  {
  	var cantidad=1;
    var descuento=0;

    if (idarticulo!="")
    {
    	var subtotal=cantidad*precio_cotizacion;
    	var fila='<tr class="filas" id="fila'+cont+'">'+
    	'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
    	'<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
    	'<td><input type="hidden" name="unidadMedida[]"  id="unidadMedida[]" value="'+unidadmedida+'">'+unidadmedida+'</td>'+
		'<td><input type="text" name="cantidad[]" id="cantidad[]" onkeyup="modificarSubototales()"  value="'+cantidad+'"></td>'+
    	'<td><input type="text" name="precio_cotizacion[]" id="precio_cotizacion[]" onkeyup="modificarSubototales()"  value="'+precio_cotizacion+'"></td>'+
    	'<td><input type="text" name="descuento[]" id="descuento" value="'+descuento+'"></td>'+
    	'<td><input type="text" name="subtotal" id="subtotal" value="'+subtotal+'"></td>'+

    	'<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
    	'</tr>';
    	cont++;
    	detalles=detalles+1;
    	$('#detalles').append(fila);
    	modificarSubototales();
    }
    else
    {
    	bootbox.alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
  }

  function modificarSubototales()
  {
  	var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_cotizacion[]");
    var desc = document.getElementsByName("descuento[]");
    var sub = document.getElementsByName("subtotal");

    for (var i = 0; i <cant.length; i++) {
    	var inpC=cant[i];
    	var inpP=prec[i];
    	var inpD=desc[i];
    	var inpS=sub[i];

    	inpS.value=(inpC.value * inpP.value)-inpD.value;
		let lSubTotal =inpS.value;

    	document.getElementsByName("subtotal")[i].value = lSubTotal;
    }
    calcularTotales();
  }

  function modifySubtotalUpdate(){
	var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_cotizacion[]");
    var desc = document.getElementsByName("descuento[]");
    var sub = document.getElementsByName("subtotal");

    for (var i = 0; i <cant.length; i++) {
    	var inpC=cant[i];
    	var inpP=prec[i];
    	var inpD=desc[i];
    	var inpS=sub[i];
		

    	inpS.value=(inpC.value * inpP.value)-inpD.value;
		let lSubTotal =inpS.value;
    	document.getElementsByName("subtotal")[i].value = lSubTotal;
    }
    calcularTotales();
  }

  function calcularTotales(){
  	var sub = document.getElementsByName("subtotal");
	//console.log(sub.length);
	//return;
	var v_subtotal= 0;
	var total = 0;
	var monto_base= 0;
  	for (var i = 0; i <sub.length; i++) {
		var subtotal = parseFloat(document.getElementsByName("subtotal")[i].value);
		//console.log(subtotal);
		//cambio incluido igv

		/* 
		var v_subtotal =v_subtotal+subtotal;
		var igv = 0.18*v_subtotal;
		total = parseFloat(v_subtotal)+igv;
		 */
		
		var subtotalReal =subtotal/1.18;
		var monto_base =monto_base+subtotalReal;
		var v_subtotal =v_subtotal+subtotal;
		var igv = v_subtotal-monto_base;
		
		total = parseFloat(monto_base)+igv;
/* 		console.log(subtotalReal);
		console.log(igv); */
		console.log("monto_base"+monto_base);
		console.log("igv"+igv);
		console.log("v_subtotal"+v_subtotal);
		
	}

/* 	console.log(v_subtotal.toFixed(2));
	console.log(igv);
	console.log(total); */
	
	$("#idSubtotal").val(monto_base.toFixed(2));
	$("#igv").val(igv.toFixed(2));
	$("#total_cotizacion").val(v_subtotal.toFixed(2));
	$("#total").html("S/ " + v_subtotal.toFixed(2));
	
	/* 	$("#subtotal").html("S/. " + subtotal.toFixed(2)); */
    evaluar();
  }
/*   onkeyup="myFunction()" */
  function evaluar(){
  	if (detalles>0)
    {
      $("#btnGuardar").show();
    }
    else
    {
      $("#btnGuardar").show(); 
      cont=0;
    }
  }

  

  function eliminarDetalle(indice){
  	$("#fila" + indice).remove();
  	calcularTotales();
  	detalles=detalles-1;
  	evaluar()
  }


init();

$('#formulario  input[type=radio]').on('change', function() {

	let consinigv = $('input[name=consinigv]:checked', '#formulario').val();
	if(consinigv == 'conigv'){
	//	console.log('Agregar Igv');
	 
		let  total = document.querySelector("#total_cotizacion").value;
		let  igv = document.querySelector("#igv").value;
		
		res = total - igv;
		let montobase = res/1.18;
		let igvnuevo = montobase*0.18;
	/* 	console.log('Total: '+total + '|||'+ 'IGV: '+igv+ '|||'+ ' TotalNuevo: '+res+ '|||'+ ' IGVNuevo: '+0.00);
		console.log('montobase: '+montobase.toFixed(2) + '|||'+ 'IGVNuevo: '+igvnuevo.toFixed(2)); */
		
		$("#subtotal").val(montobase.toFixed(2));
		$("#igv").val(igvnuevo.toFixed(2));
		$("#total").html("S/ " + res.toFixed(2));
		$("#total_cotizacion").val(res);

	}else{
		/* console.log('Quitar Igv'); */
		calcularTotales();
	}
 });