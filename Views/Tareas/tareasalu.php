<?php
headerDashboard($data);
getModal('modalTareas', $data);
?>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1>
                <i class="fas fa-building"></i> <?= NOMBRE_WEB . ' - ' . $data['page_title'] ?> &nbsp;&nbsp;
                <div class="btn-group" role="group">
                    <div class="btn-group" role="group">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-plus-circle"></i>&nbsp;&nbsp;Documentacion</button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" onclick="getDocumentacion()"><span id="txtBtnDoc"></span></a>
                            <div id="btnDelDoc">
                                <a class="dropdown-item" onclick="mostrarFormImages()"><i class="fas fa-image"></i>&nbsp;&nbsp;Imagenes</a>
                                <a class="dropdown-item" id="btnDelN"><span id="txtDel"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>/tareas/tareasalu"><?= $data['page_name']; ?></a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-responsive-sm" id="tableTareasAlu" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nro</th>
                                    <th>Nombre</th>
                                    <th>Status</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
footerDashboard($data);
?>