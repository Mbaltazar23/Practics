<?php

/**
 *
 * @author mario
 */
class ColegiosModel extends Mysql {

    public $intIdColegio;
    public $strRut;
    public $strNombre;
    public $strDireccion;
    public $strTelefono;
    public $intStatus;

    public function __construct() {
        parent::__construct();
    }

    public function selectColegios($option = NULL) {
        $this->intStatus = $option != NULL ? "WHERE c.status != 0" : "";
        $sql = "SELECT c.id, c.nombre, DATE_FORMAT(c.created_at, '%d/%m/%Y') as fecha, 
            DATE_FORMAT(c.created_at, '%H:%i:%s') as hora, c.rut, c.direccion, c.telefono, c.status
            FROM colegio c $this->intStatus";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectColegio(int $idColegio) {
        $this->intIdColegio = $idColegio;
        $sql = "SELECT c.id, c.nombre,c.direccion, c.telefono, c.status, c.rut, DATE_FORMAT(c.created_at, '%d/%m/%Y') as fecha, 
            DATE_FORMAT(c.created_at, '%H:%i:%s') as hora FROM colegio c WHERE c.id = $this->intIdColegio";
        $request = $this->select($sql);
        return $request;
    }

    public function insertColegio(string $nombre, string $rut, string $direccion, string $telefono) {
        $this->strNombre = $nombre;
        $this->strRut = $rut;
        $this->strDireccion = $direccion;
        $this->strTelefono = $telefono;
        $return = 0;
        $sql = "SELECT * FROM colegio WHERE rut = '{$this->strRut}' ";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_insert = "INSERT INTO colegio (nombre, rut, direccion, telefono) VALUES (?,?,?,?)";
            $arrData = array($this->strNombre,
                $this->strRut,
                $this->strDireccion,
                $this->strTelefono);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateColegio(int $id, string $nombre, string $rut, string $direccion, string $telefono) {
        $this->intIdcolegio = $id;
        $this->strNombre = $nombre;
        $this->strRut = $rut;
        $this->strDireccion = $direccion;
        $this->strTelefono = $telefono;
        $sql = "SELECT * FROM colegio WHERE id != $this->intIdcolegio AND nombre = '$this->strNombre'";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_update = "UPDATE colegio SET nombre = ?, rut = ?, direccion = ?, telefono = ? WHERE id = $this->intIdcolegio";
            $arrData = array($this->strNombre,
                $this->strRut,
                $this->strDireccion,
                $this->strTelefono);
            $request_update = $this->update($query_update, $arrData);
            $return = $request_update;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateStatusColegio($id, $status) {
        $this->intIdcolegio = $id;
        $this->intStatus = $status;
        $sql = "SELECT * FROM persona_colegio WHERE colegio_id = $this->intIdcolegio";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_update = "UPDATE colegio SET status = ? WHERE id = $this->intIdcolegio";
            $arrData = array($this->intStatus);
            $request = $this->update($query_update, $arrData);
            if ($request) {
                $request = 'ok';
            } else {
                $request = 'error';
            }
        } else {
            $request = 'exist';
        }
        return $request;
    }

}
