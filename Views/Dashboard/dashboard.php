<?php
headerDashboard($data);
$cardsPanel = $data["cardsPanel"];
?>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i>&nbsp;<?= DASHBOARD . ' - ' . NOMBRE_WEB ?> </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="<?= base_url() ?>/dashboard/"> Panel Administrativo</a></li>
        </ul>
    </div>
    <div class="row">
        <?php foreach ($cardsPanel as $card): ?>
            <div class="col-md-6 col-lg-3">
                <div class="linkw">
                    <div class="widget-small <?php echo $card['color']; ?> coloured-icon"><i class="icon <?php echo $card['icono']; ?> fa-3x"></i>
                        <div class="info">
                            <h6><?php echo $card['titulo']; ?></h6>
                            <p><b><?php echo $card['valor']; ?></b></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="tile mb-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="bs-component">
                    <div class="jumbotron">
                        <h6 class="display-4"><?= $data['rol'] ?></h6>
                        <p><?= $data["encabezado"] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php footerDashboard($data); ?>
