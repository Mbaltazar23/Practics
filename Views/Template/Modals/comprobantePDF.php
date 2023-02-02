<?php
$cliente = $data['cliente'];
$orden = $data['orden'];
$detalle = $data['detalle'];
$idCupon = "";

foreach ($detalle as $productCa) {
    if ($productCa["categoriaPro"] == IDCATCUPON) {
        $idCupon = $productCa["categoriaPro"];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
    <head> 
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Factura</title>
        <link href="<?= media(); ?>/css/stylePDF.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <table class="tbl-hader">
            <tbody>
                <tr>
                    <td class="wd33">
                        <img src="<?= media() ?>/tienda/images/logo.jpg" alt="Logo">
                    </td>
                    <td class="text-center wd33">
                        <h4><strong><?= NOMBRE_EMPESA ?></strong></h4>
                    </td>
                    <td class="text-right wd33">
                        <p>N° Orden <strong><?= $orden["idCliPed"] ?></strong><br>
                            Fecha: <?= $orden['fecha'] ?>  <br>
                            Método Pago: <?= $orden['nombrePago'] ?> <br>
                            Comuna: <?= $orden["nombreComuna"] ?><br>
                            Status : <?= $orden['statusPedido'] ?>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table class="tbl-cliente">
            <tbody>
                <tr>
                    <td>Nombre:</td>
                    <td><?= $cliente['nombrePersona'] . ' ' . $cliente['apellidoPersona'] ?></td>
                    <td>Dirección:</td>
                    <td><?= $orden['nombreDireccion'] ?></td>
                </tr>
                <tr>
                    <td class="wd10">Teléfono:</td>
                    <td class="wd40"><?= $cliente['telefonoPersona'] ?></td>
                    <td>Cupon Aplicado: </td>
                    <?php if (!empty($idCupon)) { ?>
                        <td>Si</td> 
                    <?php } else { ?>
                        <td>No</td>
                    <?php } ?>
                </tr>
                <tr>
                    <td>Correo</td>
                    <td><?= $cliente["emailPersona"]; ?></td>
                </tr>
            </tbody>
        </table>
        <br>
        <table class="tbl-detalle">
            <thead>
                <tr>
                    <th class="wd55">Producto</th>
                    <th class="wd15 text-right">Precio</th>
                    <th class="wd15 text-center">Cantidad</th>
                    <th class="wd15 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $subtotal = 0;
                foreach ($detalle as $producto) {
                    if ($producto["categoriaPro"] != IDCATCUPON) {
                        $importe = $producto['precioDetalle'] * $producto['cantidadDetalle'];
                        $subtotal = $subtotal + $importe;
                    } else {
                        continue;
                    }
                    ?>
                    <tr>
                        <td><?= $producto['producto'] ?></td>
                        <td class="text-right"><?= SMONEY . ' ' . formatMoney($producto['precioDetalle']) ?></td>
                        <td class="text-center"><?= $producto['cantidadDetalle'] ?></td>
                        <td class="text-right"><?= SMONEY . ' ' . formatMoney($importe) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right">Subtotal:</td>
                    <td class="text-right"><?= SMONEY . ' ' . formatMoney($subtotal) ?></td>
                </tr>
                <?php
                if (!empty($idCupon)) {
                    $Descuento = $orden['montoTotalPedido'] - ($subtotal + $orden['costoEnvioPedido']);
                    $Desc = $Descuento < 0 ? -1 * $Descuento : $Descuento;
                    ?>
                    <tr>
                        <td colspan="3" class="text-right">Monto Descontado:</td>
                        <td class="text-right"><?= SMONEY . ' ' . formatMoney($Desc); ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Nuevo Subtotal:</td>
                        <td class="text-right"><?= SMONEY . ' ' . formatMoney($orden['subtotalPedido']); ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="3" class="text-right">Envío:</td>
                    <td class="text-right"><?= SMONEY . ' ' . formatMoney($orden['costoEnvioPedido']); ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right">Total:</td>
                    <td class="text-right"><?= SMONEY . ' ' . formatMoney($orden['montoTotalPedido']); ?></td>
                </tr>
            </tfoot>
        </table>
        <div class="text-center">
            <p>Si tienes preguntas sobre tu pedido, <br> pongase en contacto con nombre, teléfono y Email</p>
            <h4>¡Gracias por tu compra!</h4>
        </div>
    </body>
</html>
