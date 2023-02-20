<!--Modal de la insercion/actualizacion de los alumnos a manipular-->
<div class="modal fade" id="modalFormAlumno" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAlumns" name="formAlumns" class="form-horizontal">
                    <input type="hidden" id="idAlumn" name="idAlumn" value="">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Rut</label>
                            <input class="form-control" id="txtRutAlu" name="txtRutAlu" type="text" maxlength="12"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Nombre</label>
                            <input class="form-control" id="txtNombreAlu" name="txtNombreAlu" type="text"/>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Apellido</label>
                            <input class="form-control" id="txtApellidoAlu" name="txtApellidoAlu" type="text"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Correo</label>
                            <input class="form-control" id="txtCorreoAlu" name="txtCorreoAlu" type="text"/>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 selectEspecialidades">
                            <label class="control-label">Especialidad</label>
                            <select class="form-control" id="listEspecialidad" name="listEspecialidad" ></select>
                        </div>
                        <div class="form-group col-md-6 selectCursos">
                            <label class="control-label">Curso</label>
                            <select class="form-control" id="listCurso" name="listCurso"></select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 selectProfesores">
                            <label class="control-label">Profesor</label>
                            <select class="form-control" id="listProfesors" name="listProfesors"></select> 
                        </div>
                        <div class="form-group col-md-6 selectGuias">
                            <label class="control-label">Guia</label>
                            <select class="form-control" id="listGuia" name="listGuia"></select> 
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Telefono</label>
                            <input type="text" class="form-control" id="txtTelefono01" name="txtTelefono01" maxlength="12" value="+569" />
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Telefono N°2</label>
                            <input type="text"  class="form-control" id="txtTelefono02" name="txtTelefono02" maxlength="12" value="+569"/>
                        </div>
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


<!--Modal donde se vera el detalle del alumno registrado-->
<div class="modal fade" id="modalViewAlumno" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Datos del Alumno</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>Rut:</strong></td>
                            <td id="celRutA"></td>
                        </tr>
                        <tr>
                            <td><strong>Nombres:</strong></td>
                            <td id="celNombreA"></td>
                        </tr>
                        <tr>
                            <td><strong>Especialidad:</strong></td>
                            <td id="celEspecialidadA"></td>
                        </tr>
                        <tr>
                            <td><strong>Curso:</strong></td>
                            <td id="celCursoA"></td>
                        </tr>
                        <tr>
                            <td><strong id="labalTelefonoA">:</strong></td>
                            <td id="celTelefonosA"></td>
                        </tr>
                        <tr id="celPlan">
                            <td><strong>Plan</strong></td>
                            <td id="celPlanAlum"></td>
                        </tr>
                        <tr>
                            <td><strong>Profesor :</strong></td>
                            <td id="celTutorA"></td>
                        </tr>
                        <tr>
                            <td><strong>Guia :</strong></td>
                            <td id="celGuiaA"></td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td id="celEstadoA"></td>
                        </tr>
                    </tbody>
                </table>
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
                                        <th>ACCIONES</th>
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