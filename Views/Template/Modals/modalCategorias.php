<!-- Modal FormCategorias-->
<div class="modal fade" id="modalFormCategorias" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nueva Categoría</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCategoria" name="formCategoria" class="form-horizontal">
                    <input type="hidden" id="idCategoria" name="idCategoria" value="">
                    <div class="form-group">
                        <label class="control-label">Nombre <span class="required">*</span></label>
                        <input class="form-control" id="txtNombre" name="txtNombre" type="text"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Descripción <span class="required">*</span></label>
                        <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="2"></textarea>
                    </div> 
                    <div class="modal-footer">
                        <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalViewCategoria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header header-primary">
                <h5 class="modal-title" id="titleModal">Datos de la categoría</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Nombres:</td>
                            <td id="celNombre"></td>
                        </tr>
                        <tr>
                            <td>Descripción:</td>
                            <td id="celDescripcion"></td>
                        </tr>
                        <tr>
                            <td>Fecha Creada:</td>
                            <td id="celFecha"></td>
                        </tr>
                        <tr>
                            <td>Estado:</td>
                            <td id="celEstado"></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

