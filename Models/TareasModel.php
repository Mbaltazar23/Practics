<?php

class TareasModel extends Mysql {

    private $idTarea;
    private $idPlan;
    private $idAlum;
    private $intIdColegio;
    private $nombreTarea;
    private $statusTarea;
    private $option;

    // status = 1 : Activo, status = 2 : Visible, status = 3: Subida status = 4 Evaluada

    public function __construct() {
        parent::__construct();
    }

    public function selectTareas($opcion = NULL) {
        $this->option = $opcion != NULL ? "AND status != 0" : "";
        $this->intIdColegio = $_SESSION["userData"]["detalleRol"]["colegio_id"];
        $sql = "SELECT * FROM tarea WHERE colegio_id = $this->intIdColegio $this->option";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectTareasPlan(int $idPlan) {
        $this->idPlan = $idPlan;
        $this->option = $_SESSION["cargo-personal"] != ROLPROFE && $_SESSION["cargo-personal"] != ROLGUIA ? "" : "AND (dt.status <= 2)";
        $sql = "SELECT ta.id, ta.nombre,dt.status FROM tarea_plan dt
                INNER JOIN tarea ta ON dt.tarea_id = ta.id WHERE dt.plan_id = $this->idPlan $this->option";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectTareasPlanActiv(int $idPlan) {
        $this->idPlan = $idPlan;
        $sql = "SELECT ta.id, ta.nombre,dt.status FROM tarea_plan dt
                INNER JOIN tarea ta ON dt.tarea_id = ta.id WHERE dt.plan_id = $this->idPlan AND dt.status = 2 ";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectTareasAlu(int $idAlumn) {
        $this->idAlum = $idAlumn;
        $sql = "SELECT ta.id , ta.nombre, dt.status FROM tarea_plan dt INNER JOIN tarea ta ON dt.tarea_id = ta.id 
                 INNER JOIN plan pl ON dt.plan_id = pl.id INNER JOIN alumno_plan dtp ON pl.id = dtp.plan_id 
                  WHERE dtp.alumno_id = $this->idAlum AND dt.status != 1";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertTarea(string $nombre, int $idColegio) {
        $this->nombreTarea = $nombre;
        $this->intIdColegio = $idColegio;
        $return = 0;
        $sql = "SELECT * FROM tarea WHERE nombre = '{$this->nombreTarea}' AND colegio_id = $this->intIdColegio";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_insert = "INSERT INTO tarea(nombre,colegio_id) VALUES (?,?)";
            $arrData = array($this->nombreTarea,
                $this->intIdColegio);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateTarea(string $nombre, int $idtarea) {
        $this->nombreTarea = $nombre;
        $this->idTarea = $idtarea;
        $sql = "SELECT * FROM tarea WHERE nombre = '{$this->nombreTarea}' AND id != $this->idTarea";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sqlU = "UPDATE tarea SET nombre = ? WHERE id = $this->idTarea";
            $arrData = array($this->nombreTarea);
            $request = $this->update($sqlU, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }

    public function selectTarea(int $idTarea) {
        $this->idTarea = $idTarea;
        if ($_SESSION["cargo-personal"] != ROLALU) {
            $sql = "SELECT * FROM tarea WHERE id = $this->idTarea";
        } else {
            $sql = "SELECT ta.id, ta.nombre,dt.status FROM tarea_plan dt 
                    INNER JOIN tarea ta ON dt.tarea_id = ta.id WHERE dt.tarea_id = $this->idTarea";
        }
        $request = $this->select($sql);
        return $request;
    }

    public function updateStatusTarea(int $idtarea, int $status) {
        $this->statusTarea = $status;
        $this->idTarea = $idtarea;
        $sql = "SELECT * FROM tarea_plan WHERE tarea_id = $this->idTarea";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE tarea SET status = ? WHERE id = $this->idTarea";
            $arrData = array($this->statusTarea);
            $request = $this->update($sql, $arrData);
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
