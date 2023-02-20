<?php
headerLogin($data);
if (isset($_SESSION["login"])) {
    header('Location: ' . base_url() . '/dashboard');
}
?>
<section class="material-half-bg">
    <div class="cover"></div>
</section>
<section class="login-content">
    <div class="logo">
        <h1><?= $data['page_title']; ?></h1>
    </div>
    <div class="login-box">
        <div id="divLoading" >
            <div>
                <img src="<?= media(); ?>/images/loading.svg" alt="Loading">
            </div>
        </div>
        <form class="login-form" name="formLogin" id="formLogin" action="">
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>INICIAR SESIÓN</h3>
            <div class="form-group">
                <label class="control-label">USUARIO</label>
                <input id="txtEmail" name="txtEmail" class="form-control" type="email" placeholder="Email" autofocus>
            </div>
            <div class="form-group">
                <label class="control-label">CONTRASEÑA</label>
                <input id="txtPassword" name="txtPassword" class="form-control" type="password" placeholder="Contraseña">
            </div>
            <div class="form-group">
                <div class="utility">
    <!--             <p class="semibold-text mb-2"><a href="#" data-toggle="flip">¿Olvidaste tu contraseña?</a></p>-->
                </div>
            </div>
            <div id="alertLogin" class="text-center"></div>
            <div class="form-group btn-container">
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> INICIAR SESIÓN</button>
            </div>
        </form>
        <!--        <form id="formRecetPass" name="formRecetPass" class="forget-form" action="">
                  <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>¿Olvidaste contraseña?</h3>
                  <div class="form-group">
                    <label class="control-label">EMAIL</label>
                    <input id="txtEmailReset" name="txtEmailReset" class="form-control" type="email" placeholder="Email">
                  </div>
                  <div class="form-group btn-container">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-unlock fa-lg fa-fw"></i>REINICIAR</button>
                  </div>
                  <div class="form-group mt-3">
                    <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Iniciar sesión</a></p>
                  </div>
                </form>-->
    </div>
</section>
<?php
footerLogin($data);
?>