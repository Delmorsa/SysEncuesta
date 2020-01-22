<script type="text/javascript">
$(document).ready(function() {
    $("#btnDetalles").hide();
});

$("#selectEncuestas").change(function(){
  let idEncuesta = $("#selectEncuestas option:selected").val();
  getDataPreguntas(idEncuesta);
});

function getDataPreguntas(IdEncuesta){
  $("#selectPreguntas").children().remove();
  $.ajax({
    type: "GET",
    url: '<?php echo base_url("index.php/getPregPorEnc/")?>'+IdEncuesta,
    dataType: "json",
    success: function(data){
      $.each(data,function(key, registro) {
        $("#selectPreguntas").append('<option value='+registro.id+'>'+registro.nombre+'</option>');
      });
    },
    error: function(data) {
      alert('error');
    }
  });
}

function mostrarGraficas(){
  $("#preguntaEnc").html("");
  $("#canvasChart").html("")
  /*******************************************************/
  let Arr = new Array(); let Arr1 = new Array(); let Arr2 = new Array();
  let idpregunta = $("#selectPreguntas option:selected").val();
  let idarea = $("#selectAreas option:selected").val();
    $.ajax({
      url: "resultadosEncuesta/"+idpregunta+"/"+idarea,
      type: "get",
      dataType: "json",
      data: '',
      success: function(data1){
        $("#canvasChart").html("<canvas id='chart' width='100%' height='40px'></canvas>")
        //let obj = jQuery.parseJSON(data1);
        paramNombres = [];
				paramDatos = [];
				bgColor = [];
				bgBorder = [];

        $.each(data1, function(i,item){
          $.each(item, function(it,ite){
            Arr[it] = ite;
          });

          var r = Math.random() * 255;
          r = Math.round(r);

          var g = Math.random() * 255;
          g = Math.round(g);

          var b = Math.random() * 255;
          b = Math.round(b);
          bgColor.push('rgba('+r+','+g+','+b+', 0.7)');
          bgBorder.push('rgba('+r+','+g+','+b+', 1)');
        });

        for(let key in Arr){
          Arr1 = key;
          Arr2 = Arr[key];
          paramNombres.push(Arr1.toString());
          paramDatos.push(Arr2.toString());
        }

        var ctx = document.getElementById('chart').getContext('2d');
				    var myChart = new Chart(ctx, {
					   type: 'bar',
					    data: {
					        labels: paramNombres,
					        datasets: [{
					            label: ["Respuestas"],
					            data: paramDatos,
					            backgroundColor: bgColor,
					            borderColor: bgBorder,
					            borderWidth: 1
					        }]
					    },
              options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
					});
          $("#preguntaEnc").html($("#selectPreguntas option:selected").text());
      }
    });
}

function cantUsuariosEncuestados(){
  let usuarios = 0, idpregunta = $("#selectPreguntas option:selected").val();
  let idarea = $("#selectAreas option:selected").val();
  $("#preguntaResultados").html(usuarios);
  $("#preguntaResultados").html("");

  $.ajax({
    url: 'cantUsersEncuesta/'+idpregunta+"/"+idarea,
    type: 'POST'
  })
  .done(function(data) {
    let obj = jQuery.parseJSON(data);
    $.each(obj, function(index, el) {
      usuarios = el["usuarios"];
    });
      $("#preguntaResultados").html(usuarios);
  })
  .fail(function() {
    console.log("error");
    $("#preguntaResultados").html("0");
  })
  .always(function() {

  });

}

function respuestasPorAreas(){
  $("#canvasChart2").html("")
  let idpregunta = $("#selectPreguntas option:selected").val();
  $.ajax({
    url: 'respPorAreas/'+idpregunta,
    type: 'POST',
    dataType: 'json',
    success: function(msg){
      $("#canvasChart2").html("<canvas id='chart2'></canvas>");
      paramNombres = [];
				paramDatos = [];
				bgColor = [];
				bgBorder = [];
				for (var i=0; i<=6; i++) {
							//console.log(i);
							var r = Math.random() * 255;
							r = Math.round(r);

							var g = Math.random() * 255;
							g = Math.round(g);

							var b = Math.random() * 255;
							b = Math.round(b);
							bgColor.push('rgba('+r+','+g+','+b+', 0.7)');
							bgBorder.push('rgba('+r+','+g+','+b+', 1)');
						}
				$.each(msg, function(i,item){
					paramNombres.push(item["Area"]);
					paramDatos.push(item["Respuestas"]);
					bgColor.push('rgba('+r+','+g+','+b+', 0.8)');
					bgBorder.push('rgba('+r+','+g+','+b+', 1)');
				});
				var ctx2 = document.getElementById('chart2').getContext('2d');
				    var myChart = new Chart(ctx2, {
					    type: 'pie',
					    data: {
					        labels: paramNombres,
					        datasets: [{
					            label: paramNombres,
					            data: paramDatos,
					            backgroundColor: bgColor,
					            borderColor: bgBorder,
					            borderWidth: 1
					        }]
					    },
					    /*options: {
					        scales: {
					            yAxes: [{
					                ticks: {
					                    beginAtZero:true
					                }
					            }]
					        }
					    }*/
					});
    }
  });

}

$("#btnFiltrar").click(function(){
    mostrarGraficas();
    respuestasPorAreas();
    cantUsuariosEncuestados();
    $("#btnDetalles").show();
});

$("#btnDetalles").click(function(){
  let Arr = []; let Arr1 = new Array(); let Arr2 = new Array();
  let cont = 0;
  let idpregunta = $("#selectPreguntas option:selected").val();
  $.ajax({
    url: "detalleEncuestasAreas/"+ idpregunta,
    type: "POST",
    async: true,
    success: function(data){
      let thead = '', tbody = '';
      let obj = jQuery.parseJSON(data);
      let contador = 0;
      $.each(obj,function(index, el) {
        $.each(el, function(it,ite){
          Arr[it] = ite;
        });
      });

      for(let key in Arr){
        contador++;
        thead += "<th class='center'>"+key+"</th>";
      }

      for (var i = 0; i < obj.length; i++) {
        //console.log("----- Cliente " + (i+1) );
        tbody += "<tr>";
         for(var propiedad in obj[i]){
           if(obj[i][propiedad] == null){
             obj[i][propiedad] = 0;
           }
           tbody += "<td>"+obj[i][propiedad]+"</td>";
             //console.log(propiedad+" = ",obj[i][propiedad]);
         }
         tbody += "</tr>";
        // console.log("-----");
      }

      var detalle = new Array();
      for (var i = 0; i <= contador; i++) {
        detalle[0] = [];
        detalle[0][i] = Arr2[i];
      }

      $("#tablaDetalles").html("<table id='tblDetalles' class='table table-condensed table-striped table-bordered'><thead><tr class='primary'>"+
          thead +"</tr></thead><tbody>"+tbody+"</tbody></table>");
    }
  });
  $("#modalDetalles").modal('show');
});
</script>
