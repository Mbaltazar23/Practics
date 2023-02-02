<!--Aqui estara el modal para iniciar sesion-->
<div class="modal fade" id="modalLoginTienda" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-header">
                    <h3>Inicie Sesion en nuestra pagina</h3> 
                </div>
                <div class="container-fluid">
                    <div class="ee-login">
                        <!-- Login Form -->
                        <div class="text-center">
                            <form name="formLogin" id="formLogin">
                                <input type="hidden" name="OpcionS" id="OpcionS"/>
                                <div class="row">
                                    <div class="col-12 mb-30"><input type="text" id="txtEmail" name="txtEmail" placeholder="Ingrese su correo.."></div>
                                    <div class="col-12 mb-20"><input type="password" id="txtPassword" name="txtPassword" placeholder="Ingrese su contraseña..."></div>
                                    <div class="col-12 modal-footer">
                                        <input type="submit" value="Iniciar Sesion">
                                    </div>
                                </div>
                            </form>
                            <h5>No tiene una Cuenta? Haga Click aqui en &nbsp;<a onclick="openModalRegister()">Registrese</a></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!---->
<div class="modal fade" id="modalRegisterTienda" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">                
                <div class="modal-header">
                    <h3>Registrese como nuevo Cliente</h3> 
                </div>
                <div class="container ee-register" style="margin: 0%;padding-bottom: 0%">
                    <form action="" class="checkout-form" id="formRegister" name="formRegister">
                        <div class="row">
                            <input type="hidden" name="OpcionSR" id="OpcionSR"/>
                            <div class="col-md-6 col-12 mb-20">
                                <input type="text" placeholder="Nombres.." id="txtNombreRegister" name="txtNombreRegister">
                            </div>
                            <div class="col-md-6 col-12 mb-20">
                                <input type="text" placeholder="Apellidos..." id="txtApellidoRegister" name="txtApellidoRegister">
                            </div>
                            <div class="col-md-6 col-12 mb-20">
                                <input type="email" placeholder="Correo..." id="txtEmailRegister" name="txtEmailRegister">
                            </div>
                            <div class="col-md-6 col-12 mb-20">
                                <input type="text" placeholder="Telefono..." id="txtTelefonoRegister" name="txtTelefonoRegister" maxlength="12" value="+569">
                            </div>
                            <div class="col-md-6 col-12 mb-20">
                                <input type="password" placeholder="Ingrese su password.."id="txtPassRegister" name="txtPassRegister" maxlength="8"/>
                            </div>
                            <div class="col-md-6 col-12 mb-20">
                                <input type="password" placeholder="Repita su password..." id="txtPass2Register" name="txtPass2Register" maxlength="8">
                            </div>
                            <div class="col-12 mb-20">
                                <input type="text" placeholder="Comentario..." id="txtComentRegister" name="txtComentRegister">
                            </div>
                            <div class="col-12 modal-footer" style="margin: 0%; padding-top: 0%;">
                                <input type="submit" value="Registrese">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




