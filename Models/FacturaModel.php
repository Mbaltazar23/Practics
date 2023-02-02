<?php

class FacturaModel extends Mysql {

    public function __construct() {
        parent::__construct();
    }

    public function selectPedido(int $idpedido, $idpersona = NULL) {
        $busqueda = "";
        if ($idpersona != NULL) {
            $busqueda = " AND p.personaIdPedido = " . $idpersona;
        }
        $request = array();
        $sql = "SELECT p.idPedido,p.personaIdPedido,DATE_FORMAT(p.fechaPedido, '%d/%m/%Y') as fecha,"
                . " p.montoTotalPedido,p.subtotalPedido, tp.nombrePago, tp.idTipoPago, p.statusPedido, p.costoEnvioPedido,"
                . "d.nombreDireccion, c.nombreComuna FROM pedido p INNER JOIN tipopago tp "
                . "ON p.tipopagoPedido = tp.idTipoPago INNER JOIN direccion d ON p.direccionPedido = d.idDireccion "
                . "INNER JOIN comuna c ON d.comunaIdDireccion = c.idComuna WHERE p.idPedido =  $idpedido " . $busqueda;
        $requestPedido = $this->select($sql);
        if (!empty($requestPedido)) {
            $idpersona = $requestPedido['personaIdPedido'];
            $sql_cliente = "SELECT idPersona,
                                   nombrePersona,
                                   apellidoPersona,
                                   telefonoPersona,
                                   emailPersona 
			     FROM persona WHERE idPersona = $idpersona ";
            $requestcliente = $this->select($sql_cliente);
            $sql_detalle = "SELECT p.idproducto,
				   p.nombrePro as producto,
                                   p.categoriaPro,
				   d.precioDetalle,
                                   d.cantidadDetalle
			           FROM detallepedido d
				   INNER JOIN producto p
				   ON d.productoIdDetalle = p.idproducto
			    WHERE d.pedidoIdDetalle = $idpedido";
            $requestProductos = $this->select_all($sql_detalle);
            //buscamos el identificador del pedido segun el que se haya seleccionado 
            $sqlPedidos = "SELECT * FROM pedido WHERE estadoPedido != 0 AND personaIdPedido = " . $idpersona;
            $requestPedidosTemo = $this->select_all($sqlPedidos);
            $idTemp = "";
            for ($i = 0; $i < count($requestPedidosTemo); $i++) {
                if ($idpedido == $requestPedidosTemo[$i]["idPedido"]) {
                    $idTemp = ($i + 1);
                }
            }
            $requestPedido["idCliPed"] = $idTemp;

            
            $request = array('cliente' => $requestcliente,
                'orden' => $requestPedido,
                'detalle' => $requestProductos
            );
        }
        return $request;
    }

    public function selectPedidos($idpersona = NULL) {
        $where = "";
        if ($idpersona != null) {
            $where = " WHERE p.personaIdPedido = " . $idpersona;
        }
        $sql = "SELECT p.idPedido, DATE_FORMAT(p.fechaPedido, '%d/%m/%Y') as fecha,"
                . " p.montoTotalPedido, tp.nombrePago, tp.idTipoPago, p.statusPedido, "
                . "d.nombreDireccion, c.nombreComuna FROM pedido p INNER JOIN tipopago tp "
                . "ON p.tipopagoPedido = tp.idTipoPago INNER JOIN direccion d ON p.direccionPedido = d.idDireccion "
                . "INNER JOIN comuna c ON d.comunaIdDireccion = c.idComuna $where";
        $request = $this->select_all($sql);
        return $request;
    }

}

?>
