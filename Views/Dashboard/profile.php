<?php
headerDashboard($data);
getModal('modalFormPersonal', $data)
?>
<main class="app-content">
    <div class="row user">
        <div class="col-md-12">
            <div class="profile">
                <div class="info"><img class="user-img" src="<?= media(); ?>/images/perfil/<?= $_SESSION["userData"]["imgPerfil"] ?>">
                    <h4 id="nameUser"></h4>
                    <p id="rolUser"></p>
                </div>
                <div class="cover-image"></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="tile p-0">
                <ul class="nav flex-column nav-tabs user-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#user-timeline" data-toggle="tab">Datos personales</a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                <div class="tab-pane active" id="user-timeline">
                    <div class="timeline-post">
                        <div class="post-media">
                            <div class="content">
                                <h5>DATOS PERSONALES &nbsp;<button class="btn btn-sm btn-info" type="button" onclick="openModalPerfil();"><i class="fas fa-pencil-alt" aria-hidden="true"></i></button></h5>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Nombres:</td>
                                    <td id="celNombre"></td>
                                </tr>
                                <tr>
                                    <td>Apellido:</td>
                                    <td id="celApellido"></td>
                                </tr>
                                <tr>
                                    <td>Email (Usuario):</td>
                                    <td id="celEmail"></td>
                                </tr>
                                <tr>
                                    <td>Rol:</td>
                                    <td id="celRol"></td>
                                </tr>
                                <tr>
                                    <td>Direccion:</td>
                                    <td id="celDireccion"></td>
                                </tr>
                                <tr>
                                    <td>Avatar</td>
                                    <td id="celAvatar"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php footerDashboard($data); ?>
