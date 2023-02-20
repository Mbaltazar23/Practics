<!--Modal de la insercion/actualizacion de los alumnos a manipular-->
<div class="modal fade" id="modalFormPlanes" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formPlanes" name="formPlanes" class="form-horizontal">
                    <input type="hidden" id="idPlan" name="idPlan" value="">
                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <input class="form-control" id="txtNombrePlan" name="txtNombrePlan" type="text"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Descripcion</label>
                        <textarea class="form-control" id="txtDescripcionPlan" name="txtDescripcionPlan" type="text"></textarea>
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
<div class="modal fade" id="modalViewPlan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Datos del Plan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>Nombre:</strong></td>
                            <td id="celNombreP"></td>
                        </tr>
                        <tr>
                            <td><strong>Descripcion</strong></td>
                            <td id="celDescripcionP"></td>
                        </tr>
                        <tr>
                            <td><strong>Fecha</strong></td>
                            <td id="celFechaP"></td>
                        </tr>
                        <tr>
                            <td><strong>Hora</strong></td>
                            <td id="celHoraP"></td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td id="celEstadoP"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--Modal de la insercion/actualizacion de las tareas al plan a manipular-->
<div class="modal fade" id="modalFormPlanT" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalT"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="idPlanT" id="idPlanT"/>
                        <div class="form-group col-md-6">
                            <label class="control-label">Nombre-Plan</label>
                            <input type="text" id="NombrePlan" name="NombrePlan" class="form-control" disabled=""/>
                        </div>
                        <div class="form-group col-md-6 selectTareas">
                            <label lass="control-label">Tareas-Disponibles</label>
                            <select id="listTareas" name="listTareas" class="form-control">

                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <button id="btnAgregarTarea" class="btn btn-block btn-info">Agregar</button>
                        </div>
                    </div>
                    <table id="table-tareas" class="table table-responsive-lg" style="width:100%">
                        <thead>
                            <tr>
                                <th>NRO</th>
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



