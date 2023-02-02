<?php
headerLogin($data);
?>
<div class="login-header box-shadow">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="brand-logo">
            <a href="login.html">
                <img src="<?= media() ?>/images/deskapp-logo.svg" alt="">
            </a>
        </div>
    </div>
</div>
<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 col-lg-7">
                <img src="<?= media() ?>/images/login-page-img.png" alt="">
            </div>
            <div class="col-md-6 col-lg-5">
                <div class="login-box bg-white box-shadow border-radius-10">
                    <div class="login-title">
                        <h2 class="text-center text-primary">Acceder a Practics</h2>
                    </div>
                    <form id="formLogin">
                        <div class="input-group custom">
                            <input type="text" class="form-control form-control-lg" id="correo" name="correo" placeholder="Ingrese su Correo..">
                            <div class="input-group-append custom">
                                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                            </div>
                        </div>
                        <div class="input-group custom">
                            <input type="password" class="form-control form-control-lg" id="clave" name="clave" placeholder="Ingrese su Password..">
                            <div class="input-group-append custom">
                                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                            </div>
                        </div>
                        <div class="row pb-30">
                            <!--  <div class="col-6">
                                 <div class="forgot-password"><a href="forgot-password.html">Forgot Password</a></div>
                             </div>-->
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="input-group mb-0">
                                    <!--
                                            use code for form submit
                                            <input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
                                    -->
                                    <input class="btn btn-primary btn-lg btn-block" value="Iniciar Sesion" type="submit"/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
footerLogin($data);
?>