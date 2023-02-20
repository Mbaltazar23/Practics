<?php

/**
 * Description of EspecialidadesModel
 *
 * @author mario
 */
class EspecialidadesModel extends Mysql {

    public $intIdEspecialidad;
    public $strNombre;
    public $intIdColegio;
    public $intStatus;

    public function __construct() {
        parent::__construct();
    }

    public function selectEspecialidades($option = NULL) {
        $this->intIdColegio = $_SESSION["userData"]["detalleRol"]["colegio_id"];
        $this->intStatus = $option != NULL ? "AND es.status != 0" : "";
        $sql = "SELECT es.id, es.nombre, DATE_FORMAT(es.created_at, '%d/%m/%Y') as fecha, DATE_FORMAT(es.updated_at, '%H:%i:%s') as hora,
               es.status FROM especialidad es INNER JOIN colegio_especialidad ce ON es.id = ce.especialidad_id 
               WHERE ce.colegio_id = $this->intIdColegio $this->intStatus";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectEspecialidad(int $idEspecialidad) {
        $this->intIdEspecialidad = $idEspecialidad;
        $sql = "SELECT es.id, es.nombre, DATE_FORMAT(es.created_at, '%d/%m/%Y') as fecha, DATE_FORMAT(es.updated_at, '%H:%i:%s') as hora, es.status 
               FROM especialidad es WHERE es.id = $this->intIdEspecialidad";
        $request = $this->select($sql);
        return $request;
    }

    public function insertEspecialidad(string $nombre, int $idcolegio) {
        $this->strNombre = $nombre;
        $this->intIdColegio = $idcolegio;
        $return = 0;
        $sql = "SELECT * FROM especialidad es 
               INNER JOIN colegio_especialidad ce ON es.id = ce.especialidad_id 
               WHERE es.nombre = '{$this->strNombre}' AND ce.colegio_id = $this->intIdColegio";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_insert = "INSERT INTO especialidad(nombre) VALUES (?)";
            $arrData = array($this->strNombre);
            $request_insert = $this->insert($query_insert, $arrData);
            if ($request_insert) {
                $sqlCole = "INSERT INTO colegio_especialidad (especialidad_id, colegio_id) VALUES (?,?)";
                $arrD = array($request_insert, $this->intIdColegio);
                $this->insert($sqlCole, $arrD);
            }
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateEspecialidad(int $id, string $nombre) {
        $this->intIdEspecialidad = $id;
        $this->strNombre = $nombre;
        $sql = "SELECT * FROM especialidad WHERE id != $this->intIdEspecialidad AND nombre = '$this->strNombre'";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_update = "UPDATE especialidad SET nombre = ? WHERE id = $this->intIdEspecialidad";
            $arrData = array($this->strNombre);
            $request_update = $this->update($query_update, $arrData);
            return $request_update;
        } else {
            return "exist";
        }
    }

    public function updateStatusEspecialidad(int $id, int $status) {
        $this->intIdEspecialidad = $id;
        $this->intStatus = $status;
        $sql = "SELECT * FROM alumno WHERE especialidad_id = $this->intIdEspecialidad";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_update = "UPDATE especialidad SET status = ? WHERE id = $this->intIdEspecialidad";
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
