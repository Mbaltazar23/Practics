<?php

class TareasModel extends Mysql {

    private $idTarea;
    private $idPlan;
    private $idAlum;
    private $intIdColegio;
    private $idBitacora;
    private $nombreTarea;
    private $bitacoraTarea;
    private $statusTarea;
    private $option;

    // status = 1 : Activo, status = 2 : Visible, status = 3: Subida

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
        $sql = "SELECT * FROM tarea WHERE id = $this->idTarea";
        $request = $this->select($sql);
        $sqlP = "SELECT * FROM tarea_plan WHERE tarea_id = $this->idTarea";
        $requestP = $this->select($sqlP);
        if ($requestP["status"] == 3) {
            $sqlSub = "SELECT * FROM subida WHERE tarea_id = $this->idTarea";
            $requestSub = $this->select($sqlSub);
            $request["detalleSub"] = $requestSub;
        }
        return $request;
    }

    public function insertBitacoraTarea(int $idtarea, int $idpersona, string $bitacora) {
        $this->idTarea = $idtarea;
        $this->idAlum = $idpersona;
        $this->bitacoraTarea = $bitacora;
        $sql = "SELECT * FROM subida WHERE bitacora = '$this->bitacoraTarea' AND tareaid =$this->idTarea";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_insert = "INSERT INTO subida(bitacora, personaid, tareaid) VALUES (?,?,?)";
            $arrData = array($this->bitacoraTarea,
                $this->idAlum,
                $this->idTarea);
            $request_insert = $this->insert($query_insert, $arrData);

            $sqlSelectPlan = "SELECT * FROM detalleplan WHERE personaid = $this->idAlum";
            $requestP = $this->select($sqlSelectPlan);
            $this->idPlan = $requestP["planid"];
            $sqlU = "UPDATE detalletarea SET status = ? WHERE tareaid = $this->idTarea AND planid = $this->idPlan";
            $arr = array(3); //el 3 representara que fue subida la tarea;
            $this->update($sqlU, $arr);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateBitacoraTarea(int $idtarea, int $idpersona, string $bitacora, int $idBitacora) {
        $this->idTarea = $idtarea;
        $this->idAlum = $idpersona;
        $this->bitacoraTarea = $bitacora;
        $this->idBitacora = $idBitacora;
        $sql = "SELECT * FROM subida WHERE tareaid = $this->idTarea AND idsubida != $this->idBitacora";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_update = "UPDATE subida SET bitacora = ?, personaid = ?,
                        tareaid = ? WHERE idsubida = $this->idBitacora";
            $arrData = array($this->bitacoraTarea,
                $this->idAlum,
                $this->idTarea);
            $request = $this->update($query_update, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }

    public function removeBitacoraTarea(int $idtarea, int $idpersona) {
        $this->idTarea = $idtarea;
        $this->idAlum = $idpersona;
        $sqlSub = "SELECT * FROM subida WHERE tarea_id = $this->idTarea AND alumno_id = $this->idAlum";
        $requesSub = $this->select($sqlSub);
        $this->idBitacora = $requesSub["id"];

        $sqlS = "SELECT * FROM evaluacion WHERE subida_id = $this->idBitacora";
        $request = $this->select_all($sqlS);
        if (empty($request)) {
            $sqlD = "DELETE FROM subida WHERE id = $this->idBitacora";
            $request = $this->delete($sqlD);
            if ($request) {
                $request = 'ok';
            } else {
                $request = 'error';
            }
            $sqlSelectPlan = "SELECT * FROM alumno_plan WHERE alumno_id = $this->idAlum";
            $requestP = $this->select($sqlSelectPlan);
            $this->idPlan = $requestP["plan_id"];
            $sqlU = "UPDATE tarea_plan SET status = ? WHERE tarea_id = $this->idTarea AND plan_id = $this->idPlan";
            $arr = array(2); //el 3 representara que fue subida la tarea;
            $this->update($sqlU, $arr);
        } else {
            $request = 'exist';
        }
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
