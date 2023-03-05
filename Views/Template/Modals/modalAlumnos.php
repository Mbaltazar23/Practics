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


<!--Modal que carga el plan del alumno seleccionado y mostrar las tareas que tiene activas o subida su bitacora-->
<div class="modal fade" id="modalPlanAlumV" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <center><h5 class="modal-title" id="titleP_Alum"></h5></center>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="form-row">
                        <input type="hidden" id="idPla"/>
                        <div class="form-group col-md-6">
                            <p><strong>Alumno:</strong>&nbsp;&nbsp;<span id="alumnoP"></span></p>
                            <p><strong>Plan en uso:</strong>&nbsp;&nbsp;<span id="nombrePlanP"></span></p>
                        </div>
                        <table id="tableTareasA" class="table table-responsive-lg" style="width:100%">
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
    </div>
</div>

<!--Modal para mostrar la evaluacion para la tarea que se seleccione del alumno-->
<div class="modal fade" id="modalNotasTa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <center><h5 class="modal-title" id="titleNT"></h5></center>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formNotas" name="formNotas" class="form-horizontal">
                    <input type="hidden" id="idNote" name="idNote" value="">
                    <input type="hidden" id="idBit" name="idBit" value="">
                    <input type="hidden" id="idTareaT" name="idTareaT">
                    <input type="hidden" id="idPlanN" name="idPlanN">
                    <div class="form-group">
                        <label class="control-label">Notas a evaluar</label>
                        <select class="form-control" id="listNotasC" name="listNotasC" onchange="mostrarCamposDeNotas(this.value)">
                            <option value='0'>Seleccione la cantidad de notas</option>
                            <option value='1'>1</option>
                            <option value='2'>2</option>
                            <option value='3'>3</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="control-label">Tarea realizada</label>
                            <textarea class="form-control" id="txtTarea" name="txtTarea"></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Bitacora subida</label>
                            <textarea class="form-control" rows="2" id="txtBita" name="txtBita"></textarea>
                        </div>
                    </div>
                    <div id="formNotes" class="hidden">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label" for="nota1">Nota 1</label>
                                <input type="text" id="nota1" name="nota1" class="form-control" onchange="convertirADecimal('nota1')"/>
                            </div>
                            <div class="form-group col-md-4" >
                                <label class="control-label" for="nota2">Nota 2</label>
                                <input type="text" id="nota2" name="nota2" class="form-control" onchange="convertirADecimal('nota2')" />
                            </div>
                            <div class="form-group col-md-4" >
                                <label class="control-label" for="nota3">Nota 3</label>
                                <input type="text" id="nota3" name="nota3" class="form-control" onchange="convertirADecimal('nota3')"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Comentarios/Sugerencias</label>
                            <textarea class="form-control hidden" id="txtComent" name="txtComent"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btnActionFormTa" class="btn btn-primary" type="submit"><i class="fas fa-check-circle"></i><span id="btnTextT"></span></button>&nbsp;&nbsp;
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Modal para mirar las notas que fueron obtenidas de -->
<div class="modal fade" id="modalViewCal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Datos de la Tarea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>Nro:</strong></td>
                            <td id="celNroCal"></td>
                        </tr>
                        <tr>
                            <td><strong>Nombre:</strong></td>
                            <td id="celNombreTCal"></td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td id="celEstadoTCal"></td>
                        </tr>
                        <tr>
                            <td><strong>Notas:</strong></td>
                            <td id="celNotasCal"></td>
                        </tr>
                        <tr>
                            <td><strong>Promedio</strong></td>
                            <td id="celPromedioCal"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalViewDoc" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <center><h5 class="modal-title" id="titleD"></h5></center>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <input type="hidden" id="idDc"/>
                    <tbody>
                        <tr>
                            <td><strong>Titulo:</strong></td>
                            <td id="celTitulo"></td>
                        </tr>
                        <tr>
                            <td><strong>Texto:</strong></td>
                            <td id="celTexto"></td>
                        </tr>

                        <tr>
                            <td><strong>Imagenes:</strong></td>
                            <td id="celImagenes"></td>
                        </tr>    
                        <tr>
                            <td><strong>Acciones:</strong></td>
                            <td id="celBTN">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--Modal para la insercion de la nota y del comentario del documento a subir-->

<div class="modal fade" id="modalNotaDoc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <center><h5 class="modal-title" id="titleND"></h5></center>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formNotaDoc" name="formNotaDoc" class="form-horizontal">
                    <input type="hidden" id="idDocN" name="idDocN"/>
                    <input type="hidden" id="idNoteD" name="idNoteD"/>
                      <div class="form-group">
                        <label class="control-label">Nota a evaluar</label>
                        <input class="form-control" id="txtNota" name="txtNota" type="text" onchange="convertirADecimal('txtNota')"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Comentario/Sugerencia</label>
                        <textarea class="form-control" id="txtComentarioDoc" name="txtComentarioDoc" rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button id="btnActionFormDoc" class="btn btn-primary" type="submit"><i class="fas fa-check-circle"></i><span id="btnTextDoc">&nbsp;&nbsp;</span></button>&nbsp;&nbsp;
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;&nbsp;Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!--Modal para ver los datos del alumno y sus tareas ya calificadas-->
<div class="modal fade" id="modalAlumTareasCal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alumno con el <span id="planAlumN"></span> y sus Tareas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="row">
                        <div class="container">
                            <table id="tableDetalleN" class="table table-bordered table-responsive-lg" style="width:100%">
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
                            <table id="tableTareasN" class="table table-responsive-lg" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>NRO</th>
                                        <th>NOMBRE</th>
                                        <th>STATUS</th>
                                        <th>BITACORA</th>
                                        <th>NOTAS</th>
                                        <th>PROMEDIO</th>
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
