<?php
headerDashboard($data);
getModal('modalPlanes', $data);
?>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1>
                <i class="fas fa-folder"></i> <?= NOMBRE_WEB . ' - ' . $data['page_title'] ?> &nbsp;&nbsp;
                <div class="btn-group" role="group">
                    <div class="btn-group" role="group">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-plus-circle"></i>&nbsp;&nbsp;Acciones</button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item"  onclick="openModal();">Nuevo</a>
                            <a class="dropdown-item" onclick="generarReportPlanes()">Generar Reporte</a>
                        </div>
                    </div>
                </div>
            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>/planes"><?= $data['page_title'] ?></a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-responsive-lg" id="tablePlanes" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripcion</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
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