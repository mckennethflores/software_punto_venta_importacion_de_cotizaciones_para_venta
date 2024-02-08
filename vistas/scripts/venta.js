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
	$.post("../ajax/venta.php?op=selectCliente", function(r){
	            $("#idcliente").html(r);
	            $('#idcliente').selectpicker('refresh');
	});	

	//Cargamos los vendedores
	loadSells();
}

function loadSells(){
	$.post("../ajax/venta.php?op=selectVendedor", function(r){
		 $("#idvendedor_frmPrimary").html(r);
		
	});
}

$("#frmVendedor").on("submit",function(e)
	{
		e.preventDefault(); 
	 
		guardarVendedor(e);
		
		
	});

//Función limpiar
function limpiar()
{
	$("#idcliente").val("");
	$("#cliente").val("");
	$("#serie_comprobante").val("");
	$("#num_comprobante").val("");
	$("#impuesto").val("0");

	$("#total_venta").val("");
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
					url: '../ajax/venta.php?op=listar',
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
					url: '../ajax/venta.php?op=listarArticulosVenta',
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

//Función para guardar o editar

function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento

	// console.log("se para");
	// return;
	//$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/venta.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {
			//console.log(datos);       
	          bootbox.alert(datos);	          
	          mostrarform(false);
	          listar();
	    }

	});
	limpiar();
}

function mostrar(idventa)
{
	$.post("../ajax/venta.php?op=mostrar",{idventa : idventa}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);
		mostrarDetalle(data.idventa);
		$("#idcliente").val(data.idcliente);
		$("#idcliente").selectpicker('refresh');
		$("#tipo_comprobante").val(data.tipo_comprobante);
		$("#tipo_comprobante").selectpicker('refresh');
		$("#serie_comprobante").val(data.serie_comprobante);
		$("#num_comprobante").val(data.num_comprobante);
		$("#fecha_hora").val(data.fecha);
		$("#impuesto").val(data.impuesto);
		$("#idventa").val(data.idventa);

		//Ocultar y mostrar los botones
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();
 	});

 		
}
function mostrarDetalle(idventa){
	$.post("../ajax/venta.php?op=listarDetalle&id="+idventa,function(r){
		$("#detalles").html(r);
});
}
function activar(idventa)
{
	bootbox.confirm("¿Está Seguro de activar el artículo?", function(result){
		if(result)
        {
        	$.post("../ajax/venta.php?op=activar", {idventa : idventa}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

function guardarVendedor()
{
	var formData = new FormData($("#frmVendedor")[0]);

	$.ajax({
		url: "../ajax/venta.php?op=guardarVendedor",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {         
			bootbox.alert(datos);	              
			$("#modalAddSell").modal("hide");
			loadSells();
	    }

	});
	
	limpiar();
}


//Función para anular registros
function anular(idventa)
{
	bootbox.confirm("¿Está Seguro de anular la venta?", function(result){
		if(result)
        {
        	$.post("../ajax/venta.php?op=anular", {idventa : idventa}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
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
  	if (tipo_comprobante=='Factura')
    {
        $("#impuesto").val(impuesto); 
    }
    else
    {
        $("#impuesto").val("0"); 
    }
  }

function agregarDetalle(idarticulo,articulo,precio_venta)
  {
  	var cantidad=1;
    var descuento=0;

    if (idarticulo!="")
    {
    	var subtotal=cantidad*precio_venta;
    	var fila='<tr class="filas" id="fila'+cont+'">'+
    	'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
    	'<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
    	'<td><input type="text" name="cantidad[]" id="cantidad[]" onkeyup="modificarSubototales()" value="'+cantidad+'"></td>'+
    	'<td><input type="text" name="precio_venta[]" id="precio_venta[]" onkeyup="modificarSubototales()" value="'+precio_venta+'"></td>'+
    	'<td><input type="text" name="descuento[]" value="'+descuento+'"></td>'+
    	'<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
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
  function modificarSubototales()
  {
  	var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_venta[]");
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

  function evaluar(){
  	if (detalles>0)
    {
      $("#btnGuardar").show();
    }
    else
    {
      $("#btnGuardar").hide(); 
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

function fnGetInvoice(){


		 
	let quotation = document.querySelector("#quotation").value;
   
	if (quotation.trim() != null || quotation.trim() != undefined){

		//console.log(quotation);
		//var url = "http://localhost/gruposantini";
	//	let idventa_ = "85";

		$.post(url+"/ajax/venta.php?op=searchquotation",{quotation : quotation}, function(data, status)

		{
			
			/* console.log(data);
			return; */
			data = JSON.parse(data);	

			mostrarform(true);
			// mostrarDetalle(data.idventa);
		 	$("#idcliente").val(data.idcliente);
			$("#idcliente").selectpicker('refresh');
			/*	$("#tipo_comprobante").val(data.tipo_comprobante);
			$("#tipo_comprobante").selectpicker('refresh'); */
			$("#num_comprobante").val(data.num_comprobante);
			/* $("#num_comprobante").val(data.num_comprobante);
			$("#fecha_hora").val(data.fecha);
			$("#impuesto").val(data.impuesto);
			$("#idventa").val(data.idventa); */

			//Ocultar y mostrar los botones
			$("#btnGuardar").hide();
			$("#btnCancelar").show();
			//$("#btnAgregarArt").hide();

			fnGetInvoiceDetail(quotation);

		});

	
	}else {
		
		alert("Porfavor ingrese un numero de cotización");
	}

}

function fnGetInvoiceDetail(quotation){
	//console.log("detail"+quotation);
	
	$.post(url+"/ajax/venta.php?op=searchquotationdetail",{quotation : quotation}, function(data, status)

	{
		$("#detalles").html(data);
		 //console.log(data);  
		//data = JSON.parse(data);	
		$("#btnGuardar").show();
		

	});

	/* $.post("../ajax/cotizacion.php?op=listarDetalle&id="+idcotizacion,function(r){
		$("#detalles").html(r);
}); */
}

