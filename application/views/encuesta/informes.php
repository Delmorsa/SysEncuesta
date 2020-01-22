<!-- page content -->
        <div class="right_col" role="main">
            <div class="x_content">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2 class="text-bold">Resultados de la encuesta</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><button onclick="location.reload()" class="btn btn-success"><i class="fa fa-refresh" style="color:white;"></i></button>
                      </li>
                     <!--<li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Settings 1</a>
                            <a class="dropdown-item" href="#">Settings 2</a>
                          </div>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>-->
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12"><div class="col-3 col-sm-3 col-md-3">
                      <select id="selectAreas" class="form-control">
                            <option selected value="-1" disabled>Seleccione un area</option>
                            <option value="-1">Todos</option>
                            <?php
                              if(!$areas){
                                echo '<option disabled>No hay datos disponibles</option>';
                              }else{
                                foreach ($areas as $key) {
                                  echo '<option value="'.$key["IdArea"].'">'.$key["Descripcion"].'</option>';
                                }
                              }
                            ?>
                      </select>
                    </div>
                          <div class="col-3 col-sm-3 col-md-3">
                          <select id="selectEncuestas" class="form-control">
                                <option selected value="-1" disabled>Seleccione una encuesta</option>
                                <?php
                                  if(!$encuestas){
                                    echo '<option disabled>No hay datos disponibles</option>';
                                  }else{
                                    foreach ($encuestas as $key) {
                                      echo '<option value="'.$key["IdEncuesta"].'">'.$key["Titulo"].'</option>';
                                    }
                                  }
                                ?>
                          </select>
                        </div>
                        <div class="col-5 col-sm-5 col-md-5">
                        <select id="selectPreguntas" class="form-control">
                              <option selected value="-1" disabled>Seleccione una pregunta</option>
                        </select>
                      </div>
                        <div class="col-1 col-sm-1 col-md-1">
                						<button id="btnFiltrar" class="btn btn-success btn-block">
                							<i class="fa fa-search"></i>
                						</button>
                					</div>
                      </div>
                      </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12 col-sm-12">
                <div class="x_panel">
                  <h2 class="text-center">
                      Usuarios encuestados: <span class="badge bg-green" style="color:white;" id="preguntaResultados">0</span>
                      <p  style="color:black !important;" id="preguntaEnc">----------</p>
                    </h2>
                </div>
              </div>

              <div class="col-md-7 col-sm-7">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Respuestas</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div id="canvasChart">

                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-5 col-sm-5">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Areas</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div id="canvasChart2">

                    </div>
                    <br><br>

                    <button type="button" id="btnDetalles"  class="btn btn-block btn-info">
                      Detalles <i class="fa fa-desktop"></i>
                    </button>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
        <!-- /page content -->
        <div id="modalDetalles" data-backdrop="static" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">

              <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Respuestas por Area</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                  <div class="" id="tablaDetalles">

                  </div>
              </div>
              <!---<div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>-->

            </div>
          </div>
        </div>
