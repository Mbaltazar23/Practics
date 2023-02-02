<?php
headerTienda($data);
?>
<div class="container text-center">
    <main class="app-content">
        <div class="page-error tile">
            <br><br>
            <img src="<?= media() ?>/tienda/images/error404.png" alt="img-error"/>
            <p><a class="btn btn-dark" href="javascript:window.history.back();">Regresar</a></p>
        </div>
    </main>
</div>
<?php footerTienda($data); ?>

