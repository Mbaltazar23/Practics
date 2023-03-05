<?php

/**
 * Description of SupervisionesModel
 *
 * @author mario
 */
class SupervisionesModel extends Mysql {

    private $idSuperv;
    private $idProfeSup;
    private $idColegio;
    private $fechaSup;
    private $textoSup;
    private $statusSup;

    public function __construct() {
        parent::__construct();
    }

    public function selectSupervisions(int $idProfe = NULL) {
        if ($idProfe != NULL) {
            $this->idProfeSup = $idProfe;
            $sql = "SELECT sv.id, sv.texto, DATE_FORMAT(sv.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(sv.updated_at, '%H:%i:%s') as hora,
                    per.nombre, per.apellido,pr.fono, sv.status FROM supervicion sv INNER JOIN profesor pr ON sv.profesor_id = pr.id 
                    INNER JOIN persona per ON pr.persona_id = per.id WHERE pr.id = $this->idProfeSup";
        } else {
            $this->idColegio = $_SESSION["userData"]["detalleRol"]["colegio_id"];            
            $sql = "SELECT sv.id, sv.texto, DATE_FORMAT(sv.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(sv.updated_at, '%H:%i:%s') as hora,
                   per.nombre, per.apellido,pr.fono, sv.status FROM supervicion sv INNER JOIN profesor pr ON sv.profesor_id = pr.id 
                   INNER JOIN persona per ON pr.persona_id = per.id INNER JOIN persona_colegio pc ON per.id = pc.persona_id 
                   WHERE sv.status != 0 AND pc.colegio_id = $this->idColegio";
        }
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectSupervision(int $idsupervision) {
        $this->idSuperv = $idsupervision;
        $sql = "SELECT sv.id, sv.texto, DATE_FORMAT(sv.fecha, '%d/%m/%Y') as fecha, sv.fecha as fech, DATE_FORMAT(sv.updated_at, '%H:%i:%s') as hora,
                    per.nombre, per.apellido, pr.fono, sv.status FROM supervicion sv INNER JOIN profesor pr ON sv.profesor_id = pr.id 
                    INNER JOIN persona per ON pr.persona_id = per.id WHERE sv.id = $this->idSuperv";
        $request = $this->select($sql);
        return $request;
    }

    public function insertSupervision(int $idProfe, string $comentario,string $fecha) {
        $this->idProfeSup = $idProfe;
        $this->fechaSup = $fecha;
        $this->textoSup = $comentario;
        $sql = "SELECT * FROM supervicion WHERE profesor_id = $this->idProfeSup AND texto = '$this->textoSup'";
        $request = $this->select_all($sql);
        $return = 0;
        if (empty($request)) {
            $query_insert = "INSERT INTO supervicion(texto, profesor_id, fecha) VALUES (?,?,?)";
            $arrData = array($this->textoSup,
                $this->idProfeSup,
                $this->fechaSup);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateSupervision(int $idsupervision, string $comentario, string $fecha) {
        $this->idSuperv = $idsupervision;
        $this->fechaSup = $fecha;
        $this->textoSup = $comentario;
        $sql = "SELECT * FROM supervicion WHERE id != $this->idSuperv AND texto = '$this->textoSup'";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_update = "UPDATE supervicion SET texto = ?, fecha = ? WHERE id = $this->idSuperv";
            $arrData = array($this->textoSup,
                $this->fechaSup);
            $request_update = $this->update($query_update, $arrData);
            return $request_update;
        } else {
            return "exist";
        }
    }

    public function updateStatusSupervision(int $idSupervicion, int $status) {
        $this->idSuperv = $idSupervicion;
        $this->statusSup = $status;
        $query_update = "UPDATE supervicion SET status = ? WHERE id = $this->idSuperv";
        $arrData = array($this->statusSup);
        $request = $this->update($query_update, $arrData);
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }

}
