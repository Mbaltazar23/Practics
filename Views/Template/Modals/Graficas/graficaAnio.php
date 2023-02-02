<?php
if ($grafica = "ventasAnio") {
    $ventasAnio = $data;
    ?>
    <script>
        Highcharts.chart('graficaAnio', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Ganancias del año <?= $ventasAnio['anio'] ?> '
            },
            subtitle: {
                text: 'Estadística de las ganancias por mes'
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Ganancias de este mes: <b>{point.y:.1f} millions</b>'
            },
            series: [{
                    name: 'Population',
                    data: [
    <?php
    foreach ($ventasAnio['meses'] as $mes) {
        echo "['" . $mes['mes'] . "'," . $mes['venta'] . "],";
    }
    ?>
                    ],
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:.1f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
        });
    </script>

<?php } ?>
