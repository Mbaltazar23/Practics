<!--Modal de la insercion/actualizacion de los tareas a manipular-->
<div class="modal fade" id="modalFormTareas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTareas" name="formTareas" class="form-horizontal">
                    <input type="hidden" id="idTarea" name="idTarea" value="">
                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <input class="form-control" id="txtNombreTarea" name="txtNombreTarea" type="text"/>
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

<!--Modal donde se vera el detalle de la tarea registrada-->
<div class="modal fade" id="modalViewTarea" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <td id="celNroT"></td>
                        </tr>
                        <tr>
                            <td><strong>Nombre:</strong></td>
                            <td id="celNombreT"></td>
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


<div class="modal fade" id="modalFormBitacora" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalB"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTareasB" name="formTareasB" class="form-horizontal">
                    <input type="hidden" id="idTareaB" name="idTareaB" value="">
                    <input type="hidden" id="idSub" name="idSub" value="">
                    <div class="form-group">
                        <label class="control-label">Nro</label>
                        <input class="form-control" id="txtNro" name="txtNro" type="text" disabled/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <input class="form-control" id="txtNombreT" name="txtNombreT" type="text" disabled/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Bitacora a subir</label>
                        <textarea class="form-control" id="txtBitacora" name="txtBitacora" type="text"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button id="btnActionFormB" class="btn btn-primary" type="submit"><i class="fas fa-check-circle"></i><span id="btnTextB"></span></button>&nbsp;&nbsp;
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>