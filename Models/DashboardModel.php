<?php

class DashboardModel extends Mysql {

    public function __construct() {
        parent::__construct();
        session_start();
    }

    public function verificarTitle01GraficaPersonal(string $rol) {
        $titulo = "";
        if ($rol == "Jefa") {
            $titulo = "Cantidad de productos vendidos en el Mes/año";
        } else if ($rol == "Administrador de Empresas" || $rol == "Contador Auditor") {
            $titulo = "Cantidad de Pedidos generados por los Clientes registrados en el Mes/Año";
        } else if ($rol == "Analista-financiero") {
            $titulo = "Cantidad de Pedidos realizados en el Mes/año";
        }
        return $titulo;
    }

    public function verificarTitle02GraficaPersonal(string $rol) {
        $titulo = "";
        if ($rol == "Jefa") {
            $titulo = "Cantidad de Clientes registrados en el Mes/Año";
        } else if ($rol == "Administrador de Empresas" || $rol == "Contador Auditor") {
            $titulo = "Ventas Acumuladas en el Mes/Año";
        } else if ($rol == "Analista-financiero") {
            $titulo = "Cantidad de Productos vendidos y las Ventas Acumuladas en el Mes/Año";
        }
        return $titulo;
    }

    public function verificarId01GraficaPersonal(string $rol) {
        $id = "";
        if ($rol == "Jefa") {
            $id = "insumoG";
        } else if ($rol == "Administrador de Empresas" || $rol == "Contador Auditor") {
            $id = "recibosA-C";
        } else if ($rol == "Analista-financiero") {
            $id = "CantVentasG";
        }
        return $id;
    }

    public function verificarId02GraficaPersonal(string $rol) {
        $id = "";
        if ($rol == "Jefa") {
            $id = "clientesG";
        } else if ($rol == "Administrador de Empresas" || $rol == "Contador Auditor") {
            $id = "pagosA-C";
        } else if ($rol == "Analista-financiero") {
            $id = "InsumosVentasG";
        }
        return $id;
    }

    public function ganaciasPedidos() {
        $sql = "SELECT SUM(pedido.montoTotalPedido) as totalPedido FROM pedido WHERE pedido.estadoPedido != 0";
        $request = $this->select($sql);
        $total = $request['totalPedido'];
        return $total;
    }

    public function cantClientes() {
        $sql = "SELECT COUNT(*) as total FROM persona WHERE estadoPersona != 0 AND rolPersona = " . ROLCLIENTE;
        $request = $this->select($sql);
        $total = $request['total'];
        return $total;
    }

    public function cantProductos() {
        $sql = "SELECT COUNT(*) as total FROM producto WHERE estadoPro != 0 AND categoriaPro !=" . IDCATCUPON;
        $request = $this->select($sql);
        $total = $request['total'];
        return $total;
    }

    public function cantPedidos() {
        $sql = "SELECT COUNT(*) as total FROM pedido";
        $request = $this->select($sql);
        $total = $request['total'];
        return $total;
    }

    public function selectPagosMes(int $anio, int $mes) {
        $sql = "SELECT p.tipopagoPedido, tp.nombrePago, COUNT(p.tipopagoPedido) as cantidad, "
                . "SUM(p.montoTotalPedido) as total FROM pedido p INNER JOIN tipopago tp ON p.tipopagoPedido = tp.idTipoPago "
                . "WHERE MONTH(p.fechaPedido) = $mes AND YEAR(p.fechaPedido) = $anio GROUP BY tipopagoPedido";
        $pagos = $this->select_all($sql);
        $meses = Meses();
        $arrData = array('anio' => $anio, 'mes' => $meses[intval($mes - 1)], 'tipospago' => $pagos);
        return $arrData;
    }

    public function productsPrefers() {
        $sql = "SELECT producto.idproducto,producto.nombrePro, producto.precioPro, producto.rutaPro,categoria.nombreCategoria as categoria, "
                . "DATE_FORMAT(preferencia.fechaPreferencias, '%d-%m-%Y') AS fechaActual FROM producto INNER JOIN preferencia ON preferencia.productoPreferencia = producto.idproducto "
                . "INNER JOIN categoria ON producto.categoriaPro = categoria.idcategoria "
                . "WHERE preferencia.fechaPreferencias IN (SELECT MAX(preferencia.fechaPreferencias) FROM preferencia GROUP BY preferencia.productoPreferencia) "
                . "AND (estadoPreferencia = 2 OR estadoPreferencia = 3) AND categoria.idcategoria != ".IDCATCUPON." GROUP BY producto.idproducto";
        $request = $this->select_all($sql);
        return $request;
    }

    public function graficaClientes() {
        $sql = "SELECT ELT(MONTH(persona.fechaPersona), 'Enero', 'Febrero', 'Marzo', 'Abril','Mayo','Junio','Julio', 'Agosto',"
                . " 'Septiembre', 'Octubre', 'Noviembre','Diciembre') as mesRegistro,MAX(DATE_FORMAT(persona.fechaPersona, '%H:%i:%s')) as HoraVista,"
                . " DATE_FORMAT(persona.fechaPersona, '%Y') as Anioregistro,rol.nombreRol, MAX(DATE_FORMAT(persona.fechaPersona, '%d-%m-%Y')) as fechaRegistro,"
                . "COUNT(persona.idPersona) as registroClientes FROM persona INNER JOIN rol ON persona.rolPersona = rol.idRol WHERE rol.idRol = 5 "
                . "GROUP BY mesRegistro ASC ORDER BY MONTH(persona.fechaPersona)";
        $request = $this->select_all($sql);
        return $request;
    }

    public function graficaProductosVendidos() {
        $sql = "SELECT producto.nombrePro,SUM(detallepedido.cantidadDetalle) as CantidadVendida,"
                . "MAX(DATE_FORMAT(pedido.fechaPedido,'%d-%m-%Y')) as fechaVenta FROM detallepedido INNER JOIN producto ON detallepedido.productoIdDetalle = producto.idproducto "
                . "INNER JOIN pedido ON detallepedido.pedidoIdDetalle = pedido.idPedido WHERE producto.categoriaPro != " . IDCATCUPON . " GROUP BY producto.idproducto ORDER BY fechaPedido AND pedido.estadoPedido = 1";
        $request = $this->select_all($sql);
        return $request;
    }

    public function graficaGananciasPedidos() {
        $sql = "SELECT ELT(MAX(MONTH(pedido.fechaPedido)), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',"
                . " 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') AS mesPago, MAX(DATE_FORMAT(pedido.fechaPedido, '%d-%m-%Y')) as ultimaFecha,"
                . " SUM(pedido.montoTotalPedido) as totalP FROM pedido WHERE pedido.estadoPedido = 1 GROUP BY MONTH(pedido.fechaPedido)";
        $request = $this->select_all($sql);
        return $request;
    }

    public function graficaCantPedidosClientes() {
        $sql = "SELECT DISTINCT COUNT(pedido.idPedido) as CantRecibos, persona.nombrePersona,persona.telefonoPersona, MAX(DATE_FORMAT(pedido.fechaPedido, '%d-%m-%Y')) as fechaRecibo,"
                . "pedido.estadoPedido FROM pedido INNER JOIN persona ON pedido.personaIdPedido = persona.idPersona "
                . "WHERE pedido.estadoPedido = 1 GROUP BY persona.idPersona";
        $request = $this->select_all($sql);
        return $request;
    }

    public function graficaCantPedidosMes() {
        $sql = "SELECT ELT(MAX(MONTH(pedido.fechaPedido)), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio','Agosto',"
                . " 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre') AS mesVenta, COUNT(pedido.idPedido) AS idVen, MAX(DATE_FORMAT(pedido.fechaPedido, '%d-%m-%Y')) AS ultimaFecha "
                . "FROM pedido WHERE pedido.estadoPedido = 1 GROUP BY MONTH(pedido.fechaPedido)";
        $request = $this->select_all($sql);
        return $request;
    }

    public function graficaCantProductsPedidos() {
        $sql = "SELECT MAX(DATE_FORMAT(pedido.fechaPedido, '%d-%m-%Y')) as fechaRegistro, producto.nombrePro,
                producto.categoriaPro,SUM(detallepedido.cantidadDetalle) as CantidadVendida,detallepedido.precioDetalle as ValorVenta,
                SUM(detallepedido.cantidadDetalle) * detallepedido.precioDetalle AS totalV FROM detallepedido INNER JOIN producto 
                ON detallepedido.productoIdDetalle = producto.idproducto INNER JOIN pedido ON detallepedido.pedidoIdDetalle = pedido.idPedido 
                GROUP BY producto.idproducto ORDER BY MONTH(fechaPedido) AND pedido.estadoPedido != 0";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectVentasMes(int $anio, int $mes) {
        $totalVentasMes = 0;
        $arrVentaDias = array();
        $dias = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
        $n_dia = 1;
        for ($i = 0; $i < $dias; $i++) {
            $date = date_create($anio . "-" . $mes . "-" . $n_dia);
            $fechaVenta = date_format($date, "Y-m-d");
            $sql = "SELECT DAY(fechaPedido) AS dia, COUNT(idPedido) AS cantidad, SUM(montoTotalPedido) AS total "
                    . "FROM pedido WHERE DATE(fechaPedido) = '$fechaVenta' AND statusPedido = 'Pendiente' ";
            $ventaDia = $this->select($sql);
            $ventaDia['dia'] = $n_dia;
            $ventaDia['total'] = $ventaDia['total'] == "" ? 0 : $ventaDia['total'];
            $totalVentasMes += $ventaDia['total'];
            array_push($arrVentaDias, $ventaDia);
            $n_dia++;
        }
        $meses = Meses();
        $arrData = array('anio' => $anio, 'mes' => $meses[intval($mes - 1)], 'total' => $totalVentasMes, 'ventas' => $arrVentaDias);
        return $arrData;
    }

    public function selectVentasAnio(int $anio) {
        $arrMVentas = array();
        $arrMeses = Meses();
        for ($i = 1; $i <= 12; $i++) {
            $arrData = array('anio' => '', 'no_mes' => '', 'mes' => '', 'venta' => '');
            $sql = "SELECT $anio AS anio, $i AS mes, ROUND(SUM(montoTotalPedido)) AS venta 
						FROM pedido 
						WHERE MONTH(fechaPedido)= $i AND YEAR(fechaPedido) = $anio AND statusPedido = 'Pendiente' 
						GROUP BY MONTH(fechaPedido) ";
            $ventaMes = $this->select($sql);
            $arrData['mes'] = $arrMeses[$i - 1];
            if (empty($ventaMes)) {
                $arrData['anio'] = $anio;
                $arrData['no_mes'] = $i;
                $arrData['venta'] = 0;
            } else {
                $arrData['anio'] = $ventaMes['anio'];
                $arrData['no_mes'] = $ventaMes['mes'];
                $arrData['venta'] = round($ventaMes['venta']);
            }
            array_push($arrMVentas, $arrData);
            # code...
        }
        $arrVentas = array('anio' => $anio, 'meses' => $arrMVentas);
        return $arrVentas;
    }

}

?>