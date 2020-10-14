var tabla;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	

		$.post("../ajax/precios.php?op=MARCA_PROD", function(r){
			$("#MARCA_PROD").html(r);
			$('#MARCA_PROD').selectpicker('refresh');
		});

		$.post("../ajax/precios.php?op=MARCA_VEHI", function(r){
			$("#MARCA_VEHI").html(r);
			$('#MARCA_VEHI').selectpicker('refresh');
		});

		$.post("../ajax/precios.php?op=MODELO_VEHI", function(r){
			$("#MODELO_VEHI").html(r);
			$('#MODELO_VEHI').selectpicker('refresh');
		});

		$.post("../ajax/precios.php?op=FAMILIA", function(r){
			$("#FAMILIA").html(r);
			$('#FAMILIA').selectpicker('refresh');
		});

		//listar();
}

function reset(){
	$("#MARCA_PROD").val("");
	$('#MARCA_PROD').selectpicker('refresh');

	$("#MARCA_VEHI").val("");
	$('#MARCA_VEHI').selectpicker('refresh');

	$("#MODELO_VEHI").val("");
	$('#MODELO_VEHI').selectpicker('refresh');

	$("#FAMILIA").val("");
	$('#FAMILIA').selectpicker('refresh');

	$("#linea").val("");
	$('#linea').selectpicker('refresh');
}

//Función limpiar
function limpiar()
{
	$("#idcategoria").val("");
	$("#nombre").val("");
	$("#descripcion").val("");
}

//Función mostrar formulario
function mostrarform(flag)
{
	limpiar();
	if (flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
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

	var MARCA_PROD=$('#MARCA_PROD').val();
	var MARCA_VEHI=$('#MARCA_VEHI').val();
	var MODELO_VEHI=$('#MODELO_VEHI').val();
	var FAMILIA=$('#FAMILIA').val();
	var ruc=$('#ruc').val();
	var vendedor=$('#vendedor').val();
	var linea=$('#linea').val();


	if(vendedor==="" || ruc===""){
		bootbox.alert("ERROR DE USUARIO O CLIENTE");
	}else{
if(MARCA_PROD==="" &&
		MARCA_VEHI==="" &&
		MODELO_VEHI==="" &&
		FAMILIA==="" &&
		linea===""){
			bootbox.alert("DEBE SELECIONAR UNA LINEA PARA GENERAR EL REPORTE GENERAL");
	}else{

	$('body').loading();
    $.ajax({
        url:'../ajax/precios.php?op=listar',
        type: 'get',
        data: { MARCA_PROD:MARCA_PROD ,MARCA_VEHI:MARCA_VEHI, MODELO_VEHI:MODELO_VEHI ,FAMILIA:FAMILIA,ruc:ruc,vendedor:vendedor,linea:linea  },
        success:function(data){
            $('body').loading('stop');
		 console.log(data);

		 if(data=="GENERAL"){
			bootbox.alert(" SOLO PUEDE EMITIR UN REPORTE GENERAL POR MES");
			}else if (data=="ESPECIALISTA"){
			bootbox.alert(" SOLO PUEDE EMITIR DOS REPORTES ESPECIALISTA POR MES");
			}else if(data){
				var resp=data.split("___");
				console.log(resp[1]);
				bootbox.alert("SE GENERO Y SE ENVIO EL REPORTE ");
			}

        }
    }).fail(function( jqXHR, textStatus, errorThrown ) {
        $('body').loading('stop');
         if ( console && console.log ) {
            bootbox.alert('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
         }
    });
	}
	}
}
//Función para guardar o editar

function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/categoria.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
	          bootbox.alert(datos);	          
	          mostrarform(false);
	          tabla.ajax.reload();
	    }

	});

	limpiar();
}

function mostrar(idcategoria)
{
	$.post("../ajax/categoria.php?op=mostrar",{idcategoria : idcategoria}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);

		$("#nombre").val(data.nombre);
		$("#descripcion").val(data.descripcion);
 		$("#idcategoria").val(data.idcategoria);

 	})
}

//Función para desactivar registros
function desactivar(idcategoria)
{
	bootbox.confirm("¿Está Seguro de desactivar la Categoría?", function(result){
		if(result)
        {
        	$.post("../ajax/categoria.php?op=desactivar", {idcategoria : idcategoria}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para activar registros
function activar(idcategoria)
{
	bootbox.confirm("¿Está Seguro de activar la Categoría?", function(result){
		if(result)
        {
        	$.post("../ajax/categoria.php?op=activar", {idcategoria : idcategoria}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}


init();