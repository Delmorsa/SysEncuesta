 <!-- page content -->
        <div class="right_col" style="height: 100%" role="main">
          <div class="x_content">
            
            <div class="row">
              <div class="col-lg-12 mispreguntas">
                <h1> <?php echo $encabezado[0]["Titulo"]; ?></h1>
              </div>
              <div class="col-lg-12 text-center mispreguntas">
                <h2> <?php echo $encabezado[0]["Descripcion"]; ?></h2>
              </div>
            </div>
            
              <div class="form-group row mt-3 mb-5 mispreguntas">
                  <label class="control-label col-md-2 col-sm-3 ">Seleccione su area</label>
                  <div class="col-md-5 col-sm-9 ">
                  <select id="selectAreas" class="form-control">
                        <option selected value="-1" disabled>Seleccione su Área</option>
                        <?php  
                          foreach ($areas as $key) {
                            echo '<option value="'.$key["IdArea"].'">'.$key["Descripcion"].'</option>';
                          }
                        ?>                            
                  </select>
                </div>
              </div>
              <div class="row divrespuestas pl-4">
              <?php 
                foreach ($preguntas as $key ) {
                    echo '<div id="'.$key["IdPregunta"].'" class="divmispreguntas mispreguntas col-lg-12 mt-3">
                            <h3>- '.$key["Descripcion"].'</h3>
                          </div>
                    <div class="form-group" id="divcheck-'.$key["IdPregunta"].'">';
                    foreach ($respuestas as $key2) {
                      if ($key2["IdTipoPregunta"] == $key["IdTipoPregunta"]){
                        echo '<div class="mt-2 custom-control custom-radio custom-control-inline">
                                <input type="radio" value="'.$key2["valor"].'" id="radio-'.$key2["IdValorPregunta"].'-'.$key["IdPregunta"].'" name="nameradio-'.$key["IdPregunta"].'" class="custom-control-input">
                                <label class="misrespuestas custom-control-label" for="radio-'.$key2["IdValorPregunta"].'-'.$key["IdPregunta"].'">'.$key2["DescRespuesta"].'</label>
                              </div>';
                      }
                    }
                    echo  '</div>';
                  }
              ?>
              </div>
              <div class="form-group row mt-5">
                <label class="comentario">Comentario</label>
                <textarea id="txtComentario" required="required" class="form-control" name="message" data-parsley-trigger="keyup" data-parsley-minlength="20" data-parsley-maxlength="100" data-parsley-minlength-message="Come on! You need to enter at least a 20 caracters long comment.." data-parsley-validation-threshold="10"></textarea>
              </div>           

              <div class="row mt-4 mb-5">
                <div class="col-12 text-center">
                  <button type="button" id="btnGuardar" class="btn btn-success">Guardar</button>
                </div>
              </div>            
          </div>
          <div class="modal" id="loading" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
              <div class="modal-content" style="background-color:transparent;box-shadow: none; border: none;margin-top: 26vh;">
                <div class="text-center">
                  <img width="130px" src="<?php echo base_url()?>assets/img/loading.gif">
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
<script>
  $(document).ready(function(){
    $('#btnGuardar').click(function(){
      console.log("entro al click")
      $('#btnGuardar').prop('disabled',true);

      $("#loading").modal("show");
      var comentario = $('#txtComentario').val()
      $('#selectAreas').removeClass('bg-red');
      var bandera = true;
      datos = new Array(), i = 0;
      var numPreg = $('.divrespuestas>.divmispreguntas').length

      if ($('#selectAreas option:selected').val() == "-1"){
        bandera = false;
        $(window).scrollTop($('#selectAreas').offset().top);
        $('#selectAreas').addClass('bg-red');
        $("#loading").modal("hide");
        $('#btnGuardar').prop('disabled',false);
        return false;
      }
      $( ".divrespuestas>.divmispreguntas" ).each(function( index ) {
        var idPregunta = $( this ).attr('id');        
        $('#'+idPregunta).removeClass('bg-danger');
        $("#divcheck-"+idPregunta+"").find("input[type='radio']:checked").each(function () {
            //console.log($(this).attr('id'));
            var str = $(this).attr('id');
            var res = str.split("-");            
            datos[i] = [];
            datos[i][0] = res[2];
            datos[i][1] = res[1];
            i++;
        });
        console.log("each de datos")
      });

      if (datos.length < numPreg) {
        console.log("< es menor")
        $("#loading").modal("hide");
        bandera = false;
        $( ".divrespuestas>.divmispreguntas" ).each(function( index ) {
          var contestada = false;
          var ID = $( this ).attr('id');
          $("#divcheck-"+ID+"").find("input[type='radio']:checked").each(function () {
            contestada = true;
          });
          if (contestada == false) {
            $('#'+ID).addClass('bg-danger');
            $(window).scrollTop($('#'+ID).offset().top);
            $('#btnGuardar').prop('disabled',false);
            return false;
          }
        });

      }
      console.log(bandera);
      if (bandera) {//guardo los datos
        
        let form_data = {
            enc: [$("#txtComentario").val(),$('#selectAreas option:selected').val(),<?php echo $encabezado[0]["IdEncuesta"] ?>],
            datos: JSON.stringify(datos)
        };
        $.ajax({
            url: '<?php echo base_url("index.php/guardarEncuesta") ?>',
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
                window.location.href = "<?php echo base_url("index.php/tusencuestas")?>";
              });
            },error:function(){
              Swal.fire({
                type: "error",
                text: "Error inesperado, Intentelo de Nuevo",
                allowOutsideClick: false
              });
              $("#loading").modal("hide");
              $('#btnGuardar').prop('disabled',false);
            }
          });
      }
    });

    
  });
</script>