 <!-- page content -->
        <div class="right_col" style="height: 100%" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left text-black">
                <h3>Encuestas Encuesta</h3>
              </div>              
            </div>
            <div class="x_content">
                    <table class="table" id="tblEncuestas">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Título</th>
                          <th>Descripción</th>
                          <th>Fecha</th>
                          <th>Resolver</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          if (count($encuestas)>0) {
                            $i =1 ;
                            foreach ($encuestas as $key ) {
                              echo '<tr>
                                    <td>'.$i.'</td>
                                    <td>'.$key["Titulo"].'</td>
                                    <td>'.$key["Descripcion"].'</td>
                                    <td>'.date('Y-m-d',strtotime($key["Fecha"])).'</td>
                                    <td style="text-align:center;" class="text-danger">
                                      <a href="'.base_url('index.php/resolverencuesta/'.$key["IdEncuesta"]).'">
                                        <i class="fa fa-pencil"></i>
                                      </a>
                                    </td>
                              </tr>';
                              $i++;
                            }
                          }
                        ?>                  
                      </tbody>
                    </table>

                  </div>
          </div>
        </div>
        <!-- /page content -->

        <script>
          $(document).ready(function() {
              $('#tblEncuestas').DataTable({
                "info": true,
                "sort": true,
                "destroy": true,
                "searching": true,
                "paginate": true,
                "lengthMenu": [
                  [10,20,50,100, -1],
                  [10,20,50,100, "Todo"]
                ],
                "order": [
                  [0, "desc"]
                ],
                "language": {
                  "info": "Registro _START_ a _END_ de _TOTAL_ entradas",
                  "infoEmpty": "Registro 0 a 0 de 0 entradas",
                  "zeroRecords": "No se encontro coincidencia",
                  "infoFiltered": "(filtrado de _MAX_ registros en total)",
                  "emptyTable": "NO HAY DATOS DISPONIBLES",
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
          });
        </script>