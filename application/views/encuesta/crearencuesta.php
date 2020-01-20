 <!-- page content -->
<div class="right_col" style="height: 100%" role="main">
	<div class="x_content card">
		<div class="row">
			<div class="col-12 text-center mispreguntas "><h2>Crear encuesta</h2></div>			
		</div>
		<form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
                <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="txtNombre">Nombre Encuesta <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 ">
                    	<input type="text" id="txtNombre" required="required" class="form-control ">
                	</div>
                </div>
                <div class="item form-group">
                	<label class="col-form-label col-md-3 col-sm-3 label-align" for="txtDescripcion">Descripción <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 ">
                    	<textarea type="text" id="txtDescripcion" name="last-name" required="required" class="form-control"></textarea>
                    </div>
                </div>
                <div class="item form-group">
                	<label class="col-form-label col-md-3 col-sm-3 label-align">Área de la encuesta (Opcional)</label>
		          	<div class="col-md-6 col-sm-6 ">
		            <select id="selectAreas" class="form-control">
		                <option selected value="-1" disabled>Seleccione Área</option>
		                    <?php
		                    foreach ($areas as $key) {
		                    	echo '<option value="'.$key["IdArea"].'">'.$key["Descripcion"].'</option>';
		                    }
		                	?>
		            	</select>
		        	</div>
                </div>
                <div class="item form-group">
                	<div class="col-md-6 col-sm-6 offset-md-3">                          
                    	<button id="btnGuardar" type="button" class="btn btn-success">Guardar</button>
					</div>
        		</div>        		
        		<div class="ln_solid"></div>        
        </form>		
        <div class="x_content card">
        	<div class="form-group mt-2">
                	<div class="col-md-6 col-sm-6">                          
                    	<button data-toggle="modal" data-target="#modalAgregar" type="button" class="btn btn-success">Agregar pregunta</button>
					</div>
        		</div>
	    	<div class="col-md-12 col-sm-12  ">
                <div class="x_panel">                  

                  <div class="x_content">
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action" id="tblPreguntas">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">Pregunta </th>
                            <th class="column-title">Descripción </th>
                            <th class="column-title"></th>
                            <th class="column-title">Tipo </th>                            
                            <th class="column-title no-link last"><span class="nobr">Acción</span>
                            </th>                            
                          </tr>
                        </thead>
                        <tbody>
                          
                        </tbody>
                      </table>
                    </div>							
						
                  </div>
                </div>
              </div>
	    </div><!--end div tabla-->
    </div>
    
</div> <!-- end page content -->

<div class="modal" id="modalAgregar" tabindex="-1" role="dialog"><!--modal agregar pregunta-->
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Agregar Pregunta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
                <div class="item form-group">
                        <label class="col-form-label col-md-4 col-sm-4 label-align" for="txtPregunta">Pregunta: <span class="required">*</span>
                    </label>
                    <div class="col-md-8 col-sm-8 ">
                    	<input type="text" id="txtPregunta" required="required" class="form-control ">
                	</div>
                </div>
                <div class="item form-group">
                        <label class="col-form-label col-md-4 col-sm-4 label-align" for="txtPregunta">Descripción: <span class="required">*</span>
                    </label>
                    <div class="col-md-8 col-sm-8 ">
                    	<textarea type="text" id="txtDescPregunta" required="required" class="form-control "></textarea>
                	</div>
                </div>
                <div class="item form-group">
                    <label class="col-form-label col-md-4 col-sm-4 label-align">Tipo Pregunta<span class="required">*</span></label>
		          	<div class="col-md-8 col-sm-8 ">
		            <select id="selectTipo" class="form-control">
		                <option selected value="-1" disabled>Seleccione tipo</option>
		                    <?php  
		                    foreach ($tiposPreg as $key) {
		                    	echo '<option value="'.$key["IdTipoPregunta"].'">'.$key["TipoPregunta"]." (".$key["Descripcion"].')</option>';
		                    }
		                	?>                            
		            	</select>
		        	</div>
                </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" id="btnAgregar" class="btn btn-primary">Agregar</button>
      </div>
    </div>
  </div>
</div><!--end modal agregar pregunta-->

<script>
	$(document).ready(function(){
		//$('#modalAgregar').modal('show');
	});

	$('#btnAgregar').click(function(){
		let table = $("#tblPreguntas").DataTable();
		let pregunta = $('#txtPregunta').val();
		let idtipoPregunta = $("#selectTipo option:selected").val();
		let tipoPregunta = $("#selectTipo option:selected").text();
		let descripcion = $('#txtDescPregunta').val();
		let bandera = true;

		if (pregunta.length < 10) {
			Swal.fire({
   				text: "Pregunta demasiado corta (Min 10 caracteres)",
   				type: "warning",
   				allowOutsideClick: false
   			});
   			bandera = false;
   			return;
		}
		if (idtipoPregunta == '-1') {
			Swal.fire({
   				text: "Seleccione un tipo de pregunta valida",
   				type: "warning",
   				allowOutsideClick: false
   			});
   			bandera = false;
		}
		if (descripcion.length<10) {
			Swal.fire({
   				text: "Ingrese una descripcion para esta pregunta (10 caracteres minimo)",
   				type: "warning",
   				allowOutsideClick: false
   			});
   			bandera = false;	
		}
		if (bandera) {
		let t = $('#tblPreguntas').DataTable({
			"info": false,
			"sort": false,
			"destroy": true,
			"searching": false,
			"paginate": false,
			"lengthMenu": [
				[10,20,50,100, -1],
				[10,20,50,100, "Todo"]
			],
			"order": [
				[0, "desc"]
			],
			"columnDefs": [
                    {
                        "targets": [ 2 ],
                        "visible": false
                    }
                ],
			"language": {
				"info": "Registro _START_ a _END_ de _TOTAL_ entradas",
				"infoEmpty": "Registro 0 a 0 de 0 entradas",
				"zeroRecords": "No se encontro coincidencia",
				"infoFiltered": "(filtrado de _MAX_ registros en total)",
				"emptyTable": "No hay preguntas agregadas",
				"lengthMenu": '_MENU_ ',
				"search": 'Buscar:  ',
				"loadingRecords": "",
				"processing": "Procesando datos  <i class='fa fa-spin fa-refresh'></i>",
				"paginate": {
					"first": "Primera",
					"last": "Última ",
					"next": "Siguiente",
					"previous": "Anterior"
				}
			}
			});

		t.row.add([   				
			pregunta,
			descripcion,
			idtipoPregunta,
			tipoPregunta,
			'<i class="rowDelete fa text-danger fa-trash"></i>'
   		]).draw(false);
		$('#modalAgregar').modal('hide');
		}
	});

			
    $('#tblPreguntas').on("click", "tr .rowDelete", function(){
		var table = $('#tblPreguntas').DataTable();
        var d = table.row( $(this).parents('tr') ).data();
        table.row($(this).parents('tr')).remove().draw(false);
                
    });

	$('#modalAgregar').on('hidden.bs.modal', function (e) {
		//alert("asdsad")
	  	$('#txtPregunta').val('');
		$("#selectTipo").val('-1');
		$('#txtDescPregunta').val('');
	})

	$('#btnGuardar').click(function(){
		try{
		var datos = new Array(), i = 0;
		var mensaje = '', tipo = '';
		var bandera = true;
		var tabla = $("#tblPreguntas").DataTable();
		
		$('#btnGuardar').prop('disabled',true);

		var nombre = $('#txtNombre').val();
		var descripcion = $('#txtDescripcion').val();
		var idarea = $("#selectAreas option:selected").val();

		if (nombre.length<10) {
			Swal.fire({
   				text: "Ingrese un nombre de encuesta valido (10 caracteres minimo)",
   				type: "warning",
   				allowOutsideClick: false
   			});
   			bandera = false;
   			$('#btnGuardar').prop('disabled',false);
   			return;
		}
		if (descripcion.length<10) {
			Swal.fire({
   				text: "Ingrese una descripción de encuesta valida (10 caracteres minimo)",
   				type: "warning",
   				allowOutsideClick: false
   			});
   			bandera = false;
   			$('#btnGuardar').prop('disabled',false);
   			return;
		}
		if(tabla.data().length <1){
			Swal.fire({
   				text: "Ingrese al menos 1 preguntas para la encuesta",
   				type: "warning",
   				allowOutsideClick: false
   			});
   			bandera = false;
   			$('#btnGuardar').prop('disabled',false);
   			return;
		}
		if (bandera) {//guardo todo

			tabla.rows().eq(0).each(function(i, index){
					let row = tabla.row(index);
					let data = row.data();
					datos[i] = [];
					datos[i][0] = data[0];
					datos[i][1] = data[1];
					datos[i][2] = data[2];
					i++;
				});
			let form_data = {
				enc: [nombre,descripcion,idarea],
				datos: JSON.stringify(datos)//datos
			};

			$.ajax({
					url: '<?php echo base_url("index.php/guardarEncuestaNueva")?>',
					type: 'POST',
					data: form_data,
					success: function(data)
					{
						$("#loading").modal("hide");
						let obj = jQuery.parseJSON(data);
						$.each(obj, function(index, val) {
							mensaje = val["mensaje"];
							tipo = val["tipo"];
						});
						Swal.fire({
							type: tipo,
							text: mensaje,
							allowOutsideClick: false
						}).then((result)=>{
							if (tipo == 'success') {
								//window.location.href = "reporte_10";
							}
						});
					},error:function(){
						Swal.fire({
							type: "error",
							text: "Error inesperado, Intentelo de Nuevo",
							allowOutsideClick: false
						});
						$("#loading").modal("hide");
					}
				});
			
   			$('#btnGuardar').prop('disabled',false);
		}
		
		}catch(err){
			$('#btnGuardar').prop('disabled',false);
			Swal.fire({
   				text: err,
   				type: "warning",
   				allowOutsideClick: false
   			});
		}
	});//end guardado

</script>