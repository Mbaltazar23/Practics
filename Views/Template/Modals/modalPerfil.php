<!-- Modal -->
<div class="modal fade" id="modalFormPerfil" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header headerUpdate">
                <h5 class="modal-title" id="titleModal">Actualizar Datos del Perfil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formPerfil" name="formPerfil" class="form-horizontal">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="txtNombre">Nombres</label>
                            <input type="text" class="form-control" id="txtNombre" name="txtNombre" value="<?= $_SESSION['userData']['nombrePersona']; ?>" readonly="">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txtApellido">Apellidos</label>
                            <input type="text" class="form-control" id="txtApellido" name="txtApellido" value="<?= $_SESSION['userData']['apellidoPersona']; ?>" required="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="txtTelefono">Teléfono</label>
                            <input type="text" class="form-control" id="txtTelefono" name="txtTelefono" value="<?= !empty($_SESSION['userData']['telefonoPersona']) ? $_SESSION['userData']['telefonoPersona'] : "+569" ?>" required="" onkeypress="return controlTag(event);"  maxlength="12" >
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txtEmail">Email</label>
                            <input type="email" class="form-control" id="txtEmail" name="txtEmail" value="<?= $_SESSION['userData']['emailPersona']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="txtComentario">Comentario</label>
                        <textarea class="form-control" id="txtComentario" name="txtComentario" rows="2"><?= $_SESSION['userData']['comentarioPersona']; ?></textarea>
                    </div> 
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="txtPassword">Password</label>
                            <input type="password" class="form-control" id="txtPassword" name="txtPassword" >
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txtPasswordConfirm">Confirmar Password</label>
                            <input type="password" class="form-control" id="txtPasswordConfirm" name="txtPasswordConfirm" >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btnActionForm" class="btn btn-info" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Actualizar</span></button>&nbsp;&nbsp;
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>