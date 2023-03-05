<!--Modal de la insercion/actualizacion de las superviciones-->
<div class="modal fade" id="modalFormSupervisiones" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formSupervisions" name="formSupervisions" class="form-horizontal">
                    <input type="hidden" id="idSupervision" name="idSupervision" value="">
                    <div class="form-group">
                        <label class="control-label">Fecha</label>
                        <input class="form-control" id="txtFecha" name="txtFecha" type="date" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Supervicion</label>
                        <textarea class="form-control" id="txtSupervicion" name="txtSupervicion" ></textarea>
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

<!--Modal donde se vera el detalle de la categoria registrada-->
<div class="modal fade" id="modalViewSupervision" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Datos de la Supervicion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>Nro:</strong></td>
                            <td id="celNro"></td>
                        </tr>
                        <tr>
                            <td><strong>Supervicion:</strong></td>
                            <td id="celNombre"></td>
                        </tr>
                        <tr class="rowTeacher">
                            <td><strong>Profesor:</strong></td>
                            <td id="celProfe"></td>
                        </tr>
                        <tr>
                            <td><strong>Fecha:</strong></td>
                            <td id="celFecha"></td>
                        </tr>
                        <tr>
                            <td><strong>Fecha:</strong></td>
                            <td id="celHora"></td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td id="celEstado"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
