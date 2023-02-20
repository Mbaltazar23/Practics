<!--Modal de la insercion/actualizacion de los tutores a manipular-->
<div class="modal fade" id="modalFormTutores" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTutor" name="formTutor" class="form-horizontal">
                    <input type="hidden" id="idTutor" name="idTutor" value="">
                    <div class="form-group">
                        <label class="control-label">Rut</label>
                        <input class="form-control" id="txtRutT" name="txtRutT" type="text"/>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Nombre</label>
                            <input class="form-control" id="txtNombreT" name="txtNombreT" type="text"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Apellido</label>
                            <input class="form-control" id="txtApellidoT" name="txtApellidoT" type="text"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Correo</label>
                        <input class="form-control" id="txtCorreoT" name="txtCorreoT" type="text"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Telefono</label>
                        <input class="form-control" id="txtTelefonoT" name="txtTelefonoT" type="text" maxlength="12" value="+569"/>
                    </div>
                    <div class="modal-footer">
                        <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fas fa-check-circle"></i><span id="btnText"></span></button>&nbsp;&nbsp;
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Modal donde se vera el detalle del tutor registrado-->
<div class="modal fade" id="modalViewTutor" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Datos del Profesor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>Rut:</strong></td>
                            <td id="celRutT"></td>
                        </tr>
                        <tr>
                            <td><strong>Nombres:</strong></td>
                            <td id="celNombresT"></td>
                        </tr>
                        <tr>
                            <td><strong>Correo:</strong></td>
                            <td id="celCorreoT"></td>
                        </tr>
                        <tr>
                            <td><strong>Telefono:</strong></td>
                            <td id="celTelefonoT"></td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td id="celEstadoT"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!--Modal donde se liste con Datatable los alumnos vinculadoa al guia que esten registrados-->
<div class="modal fade" id="modalListAlumnosP" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <center><h4 class="modal-title" id="titleModalA"></h4></center>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">  
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped table-responsive-lg" id="tableAlumnsPro" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Rut</th>
                                        <th>Nombre</th>
                                        <th>Telefono</th>
                                        <th>Status</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="modalFormAlumnoP" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalP"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAlumnP" name="formAlumnP" class="form-horizontal">
                    <input type="hidden" id="idAlumnP" name="idAlumnP" value="">
                    <input type="hidden" id="idPlanA" name="idPlanA" value="">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="control-label">RUT</label>
                            <input class="form-control" id="txtRutAluP" name="txtRutAluP" type="text" readonly/>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Nombres</label>
                            <input class="form-control" id="txtNombreAluP" name="txtNombreAluP" type="text" readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Curso</label>
                        <input class="form-control" id="txtCursoAluP" name="txtCursoAluP" type="text" readonly/>
                    </div>
                    <div class="form-group selectPlanes">
                        <label class="control-label">Plan</label>
                        <select class="form-control" id="listPlanes" name="listPlanes"></select> 
                    </div>
                    <div class="form-group">
                        <label class="control-label">Descripcion</label>
                        <textarea class="form-control" id="txtDescripcionP" name="txtDescripcionP" type="text"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fas fa-check-circle"></i><span id="btnTextP"></span></button>&nbsp;&nbsp;
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Modal para la carga de datos del alumno y sus tareas para dejarlas visibles-->
<div class="modal fade" id="modalAlumPlanT" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alumno con el <span id="planAlum"></span> y sus Tareas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="row">
                        <div class="container">
                            <input type="hidden" id="idPlanT" name="idPlanT"/>
                            <table id="tableDetalleP" class="table table-bordered table-responsive-lg" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Alumno</th>
                                        <th>Especialidad</th>
                                        <th>Curso</th>
                                        <th>Profesor</th>
                                        <th>Guia</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <table id="tableTareasP" class="table table-responsive-lg" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>TAREA NRO</th>
                                        <th>NOMBRE</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->