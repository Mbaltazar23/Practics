<?php

/**
 *
 * @author mario
 */
class PlanesModel extends Mysql {

    private $idPlan;
    private $idPlanAlu;
    private $intIdColegio;
    private $nombrePlan;
    private $idPersona;
    private $idTarea;
    private $descripcionPlan;
    private $option;
    private $status;

    public function __construct() {
        parent::__construct();
    }

    public function selectPlanes($option = NULL) {
        $this->intIdColegio = $_SESSION["userData"]["detalleRol"]["colegio_id"];
        $this->option = $option != NULL ? "AND pl.status != 0" : "";
        $sql = "SELECT pl.id, pl.nombre, pl.descripcion as descripcionPlan, pl.status,
                DATE_FORMAT(pl.created_at, '%d/%m/%Y') as fecha, DATE_FORMAT(pl.created_at, '%H:%i:%s') as hora 
                FROM plan pl WHERE pl.colegio_id = $this->intIdColegio $this->option";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertPlan(string $nombre, string $descripcion, int $idcolegio) {
        $this->nombrePlan = $nombre;
        $this->descripcionPlan = $descripcion;
        $this->intIdColegio = $idcolegio;
        $return = 0;
        $sql = "SELECT * FROM plan WHERE nombre = '{$this->nombrePlan}' AND colegio_id = $this->intIdColegio";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $query_insert = "INSERT INTO plan(nombre,descripcion,colegio_id) VALUES (?,?,?)";
            $arrData = array($this->nombrePlan,
                $this->descripcionPlan,
                $this->intIdColegio);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updatePlan(string $nombre, string $descripcion, int $idPlan, int $idColegio) {
        $this->nombrePlan = $nombre;
        $this->descripcionPlan = $descripcion;
        $this->idPlan = $idPlan;
        $this->intIdColegio = $idColegio;
        $sql = "SELECT * FROM plan WHERE nombre = '$this->nombrePlan' AND id != $this->idPlan";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $sqlU = "UPDATE plan SET nombre = ?, descripcion = ? WHERE id = $this->idPlan";
            $arrData = array($this->nombrePlan,
                $this->descripcionPlan);
            $request = $this->update($sqlU, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }

    public function selectPlan(int $idPlan) {
        $this->idPlan = $idPlan;
        $sql = "SELECT pl.id, pl.nombre, pl.descripcion as descripcionPlan, pl.status,
                DATE_FORMAT(pl.created_at, '%d/%m/%Y') as fecha, DATE_FORMAT(pl.created_at, '%H:%i:%s') as hora 
                FROM plan pl WHERE pl.id = $this->idPlan";
        $request = $this->select($sql);
        return $request;
    }

    public function updateStatusPlan(int $idPlan, int $status) {
        $this->idPlan = $idPlan;
        $this->status = $status;
        $sqlPlan = "SELECT * FROM alumno_plan WHERE plan_id = $this->idPlan";
        $requestPlan = $this->select_all($sqlPlan);
        if (empty($requestPlan)) {
            $sqlU = "UPDATE plan SET status = ? WHERE id = $this->idPlan";
            $arrData = array($this->status);
            $request = $this->update($sqlU, $arrData);
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

    /* aqui estaran las funciones para la insercion de las tareas al plan */

    public function insertDetailPlan(int $idPlan, int $idTarea) {
        $this->idPlan = $idPlan;
        $this->idTarea = $idTarea;
        $return = 0;
        $sql = "SELECT * FROM tarea_plan WHERE plan_id = $this->idPlan AND tarea_id = $this->idTarea";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $query_insert = "INSERT INTO tarea_plan(plan_id, tarea_id) VALUES (?,?)";
            $arrData = array($this->idPlan,
                $this->idTarea);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function deleteDetailTarea(int $idPlan, int $idTarea) {
        $this->idPlan = $idPlan;
        $this->idTarea = $idTarea;
        $sql = "SELECT * FROM subida WHERE tarea_id = $this->idTarea";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sqlDel = "DELETE FROM tarea_plan WHERE plan_id = $this->idPlan AND tarea_id = $this->idTarea";
            $request = $this->delete($sqlDel);
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

    public function updateStatusDetailTarea(int $idPlan, int $idTarea, int $status) {
        $this->idPlan = $idPlan;
        $this->idTarea = $idTarea;
        $this->status = $status;
        $sql = "SELECT * FROM subida WHERE tarea_id = $this->idTarea";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sqlDel = "UPDATE tarea_plan SET status = ? WHERE plan_id = $this->idPlan AND tarea_id = $this->idTarea";
            $arrData = array($this->status);
            $request = $this->update($sqlDel, $arrData);
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

    public function selectTareasPlan(int $idplan) {
        $this->idPlan = $idplan;
        $sql = "SELECT ta.id, ta.nombre FROM tarea ta INNER JOIN tarea_plan dt ON ta.id = dt.tarea_id "
                . "WHERE dt.plan_id = $this->idPlan";
        $request = $this->select_all($sql);
        return $request;
    }

    /* Funciones que permitiran vincular a un alumno al plan */

    public function insertDetailAlumnPlan(int $idplan, int $idperson, string $descripcion) {
        $this->idPlan = $idplan;
        $this->idPersona = $idperson;
        $this->descripcionPlan = $descripcion;
        $sql = "SELECT * FROM alumno_plan WHERE alumno_id = $this->idPersona AND plan_id = $this->idPlan";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_insert = "INSERT INTO alumno_plan(alumno_id, plan_id, detalleplan) VALUES (?,?,?)";
            $arrData = array($this->idPersona,
                $this->idPlan,
                $this->descripcionPlan);
            $request_insert = $this->insert($query_insert, $arrData);
            if ($request_insert) {
                $sqlS = "SELECT * FROM alumno WHERE id = $this->idPersona";
                $alumn = $this->select($sqlS);
                $sqlU = "UPDATE persona SET status = ? WHERE id = " . $alumn["persona_id"];
                $arr = array(2);
                $this->update($sqlU, $arr);
            }
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateDetailPlanAlum(int $idplan, int $idperson, string $descripcion, int $idplanAlum) {
        $this->idPlan = $idplan;
        $this->idPersona = $idperson;
        $this->descripcionPlan = $descripcion;
        $this->idPlanAlu = $idplanAlum;
        $sql = "SELECT * FROM alumno_plan WHERE alumno_id = $this->idPersona AND id != $this->idPlanAlu";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sqlU = "UPDATE alumno_plan SET alumno_id = ?, plan_id = ? , detalleplan = ? WHERE id = $this->idPlanAlu";
            $arrData = array($this->idPersona,
                $this->idPlan,
                $this->descripcionPlan);
            $request = $this->update($sqlU, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }

    public function selectDetailPlanAlum(int $idAlum) {
        $this->idPersona = $idAlum;
        $sql = "SELECT al.id, per.nombre, per.apellido, es.nombre as especialidad, cs.nombre as curso, dp.detalleplan,
               dp.id as iddetalleplan, dp.id as planid, pl.nombre as nombreP FROM alumno_plan dp INNER JOIN alumno al ON dp.alumno_id = al.id 
               INNER JOIN persona per ON al.persona_id = per.id INNER JOIN especialidad es ON al.especialidad_id = es.id 
               INNER JOIN curso cs ON al.curso_id = cs.id INNER JOIN plan pl ON dp.plan_id = pl.id WHERE dp.alumno_id = $this->idPersona";
        $request = $this->select($sql);
        return $request;
    }

}
