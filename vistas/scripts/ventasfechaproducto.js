var tabla;
 
function init(){
    listar();
	//Cargamos los items al select proveedor
	$.post("../ajax/articulo.php?op=selectArticulo", function(r){
        $("#idarticulo").html(r);
        $('#idarticulo').selectpicker('refresh');
});	
}
 
 

function listar(){
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var idarticulo = $("#idarticulo").val();

	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing": true,
		"aServerSide": true,
		dom: 'Bfrtip',
		buttons:	[
			 
			'excelHtml5',
			'pdf'
		],
		"ajax":
			{
                url: '../ajax/consultas.php?op=ventasfechaproducto',
                data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin,idarticulo: idarticulo},
				type: "get",
				dataType: "json",
				error: function (e){
					console.log(e.responseText);
                }
			},	
		"bDestroy": true,
		"iDisplayLength": 50, // Paginacion c/ cuantos registros
		"order": [[ 0, "desc" ]] // Ordenar data
	}).DataTable();

}

init();