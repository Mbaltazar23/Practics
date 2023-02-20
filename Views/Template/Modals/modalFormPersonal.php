<!--Modal de la sesion del rol junto al cambio de contraseña que se haria-->
<div class="modal fade" id="modalFormPassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >Actualizar Datos del Perfil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formPassword" method="POST">
                    <input type="hidden" id="foto_actual" name="foto_actual" value="">
                    <input type="hidden" id="foto_remove" name="foto_remove" value="0">
                    <input type="hidden" id="idPerPass" name="idPerPass">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="txtRut">Rut</label>
                            <input type="text" class="form-control" id="txtRut" name="txtRut"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txtNombre">Nombre</label>
                            <input type="text" class="form-control" id="txtNombre" name="txtNombre">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="txtApellido">Apellidos</label>
                            <input type="text" class="form-control" id="txtApellido" name="txtApellido">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="txtEmail">Email</label>
                            <input type="email" class="form-control" id="txtEmail" name="txtEmail">
                        </div>
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
                    <div class="form-group">
                        <label class="txtComentario">Direccion</label>
                        <textarea class="form-control" id="txtDireccion" name="txtDireccion" rows="2"></textarea>
                    </div> 
                    <div class="col-md-6">
                        <div class="photo">
                            <label for="foto" id="panelFoto"></label>
                            <div class="prevPhoto">
                                <span class="delPhoto notBlock">X</span>
                                <label for="foto"></label>
                                <div>

                                </div>
                            </div>
                            <div class="upimg">
                                <input type="file" name="foto" id="foto">
                            </div>
                            <div id="form_alert"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>

