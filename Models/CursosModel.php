<?php

class CursosModel extends Mysql {

    public $intIdCurso;
    public $strNombre;
    public $intIdColegio;
    public $intStatus;

    public function __construct() {
        parent::__construct();
    }

    public function selectCursos($option = NULL) {
        $this->intIdColegio = $_SESSION["userData"]["detalleRol"]["colegio_id"];
        $this->intStatus = $option != NULL ? "AND status != 0" : "";
        $sql = "SELECT id, nombre, DATE_FORMAT(created_at, '%d/%m/%Y') as fecha, 
            DATE_FORMAT(created_at, '%H:%i:%s') as hora, status FROM curso WHERE colegio_id = $this->intIdColegio $this->intStatus";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectCurso(int $idCurso) {
        $this->intIdCurso = $idCurso;
        $sql = "SELECT id, nombre,DATE_FORMAT(created_at, '%d/%m/%Y') as fecha, 
            DATE_FORMAT(created_at, '%H:%i:%s') as hora, status FROM curso WHERE id = $this->intIdCurso";
        $request = $this->select($sql);
        return $request;
    }

    public function insertCurso(string $nombre, int $idcolegio) {
        $this->strNombre = $nombre;
        $this->intIdColegio = $idcolegio;
        $return = 0;
        $sql = "SELECT * FROM curso WHERE nombre = '{$this->strNombre}' ";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_insert = "INSERT INTO curso(nombre,colegio_id) VALUES (?,?)";
            $arrData = array($this->strNombre,
                $this->intIdColegio);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateCurso(int $id, string $nombre) {
        $this->intIdCurso = $id;
        $this->strNombre = $nombre;
        $sql = "SELECT * FROM curso WHERE id != $this->intIdCurso AND nombre = '$this->strNombre'";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_update = "UPDATE curso SET nombre = ? WHERE id = $this->intIdCurso";
            $arrData = array($this->strNombre);
            $request_update = $this->update($query_update, $arrData);
            return $request_update;
        } else {
            return "exist";
        }
    }

    public function updateStatusCurso(int $id, int $status) {
        $this->intIdCurso = $id;
        $this->intStatus = $status;
        $sql = "SELECT * FROM alumno WHERE curso_id = $this->intIdCurso";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_update = "UPDATE curso SET status = ? WHERE id = $this->intIdCurso";
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
