<?php

/**
 * Description of TutorModel
 *
 * @author mario
 */
class TutoresModel extends Mysql {

    private $idTutor;
    private $intIdColegio;
    private $rutTutor;
    private $strNombre;
    private $strApellido;
    private $strCorreo;
    private $intRol;
    private $strPassword;
    private $telefono;
    private $intStatus;
    private $option;

    public function __construct() {
        parent::__construct();
    }

    public function selectTutors($opcion = NULL) {
        $this->intIdColegio = $_SESSION["userData"]["detalleRol"]["colegio_id"];
        $this->option = $opcion != NULL ? "AND per.status != 0" : "";
        $sql = "SELECT pro.id as idtutor,per.id,per.rut, per.nombre, per.correo, per.apellido, per.status,
                DATE_FORMAT(per.created_at, '%d/%m/%Y') as fecha, DATE_FORMAT(per.created_at, '%H:%i:%s') AS hora, pro.fono FROM persona per 
                  INNER JOIN profesor pro ON per.id = pro.persona_id INNER JOIN persona_colegio pc ON per.id = pc.persona_id 
              WHERE pc.colegio_id = $this->intIdColegio $this->option";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertTutor(string $rut, string $nombre, string $apellido, string $correo, string $pass, string $rol, string $telefono, int $idcolegio) {
        $this->rutTutor = $rut;
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->strCorreo = $correo;
        $this->strPassword = $pass;
        $this->intRol = $rol;
        $this->telefono = $telefono;
        $this->intIdColegio = $idcolegio;
        $return = 0;
        $sql = "SELECT * FROM persona per INNER JOIN persona_colegio pc ON per.id = pc.persona_id "
                . "WHERE per.rut = '{$this->rutTutor}' AND per.nombre = '{$this->strNombre}' ";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_insert = "INSERT INTO persona(rut, nombre, apellido, correo, password, rol) VALUES (?,?,?,?,?,?)";
            $arrData = array($this->rutTutor,
                $this->strNombre,
                $this->strApellido,
                $this->strCorreo,
                $this->strPassword,
                $this->intRol);
            $request_insert = $this->insert($query_insert, $arrData);

            $sqlTutor = "INSERT INTO profesor(persona_id, fono) VALUES (?,?)";
            $arrDataTut = array($request_insert,
                $this->telefono);
            $request_detail = $this->insert($sqlTutor, $arrDataTut);
            $return = $request_detail;
            
            $sqlI = "INSERT INTO persona_colegio(persona_id,colegio_id,telefono) VALUES(?,?,?)";
            $arrD = array($request_insert,
                $this->intIdColegio,
                $this->telefono);
            $this->insert($sqlI, $arrD);
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateTutor(string $rut, string $nombre, string $apellido, string $correo, string $telefono, int $idTutor) {
        $this->rutTutor = $rut;
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->strCorreo = $correo;
        $this->telefono = $telefono;
        $this->idTutor = $idTutor;
        $sql = "SELECT * FROM persona WHERE rut = '{$this->rutTutor}' AND id != '{$this->idTutor}' ";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sqlUpdate = "UPDATE persona SET rut =?, nombre = ?, correo = ?, apellido=? WHERE id = $this->idTutor";
            $arrData = array($this->rutTutor,
                $this->strNombre,
                $this->strCorreo,
                $this->strApellido);
            $request = $this->update($sqlUpdate, $arrData);

            $sqlDetail = "UPDATE profesor SET fono= ? WHERE persona_id = $this->idTutor";
            $arrDataDetail = array($this->telefono);
            $request_detail = $this->update($sqlDetail, $arrDataDetail);
            $return = $request_detail;
            
            
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function selectTutor(int $idTutor) {
        $this->idTutor = $idTutor;
        $sql = "SELECT pro.id as idtutor, per.id,per.rut, per.nombre, per.correo, per.apellido, per.status, per.rol,
                DATE_FORMAT(per.created_at, '%d/%m/%Y') as fecha, DATE_FORMAT(per.created_at, '%H:%i:%s') AS hora, pro.fono FROM persona per "
                . "INNER JOIN profesor pro ON per.id = pro.persona_id WHERE per.id = $this->idTutor";
        $request = $this->select($sql);
        return $request;
    }

    public function updateStatusTutor(int $idTutor, int $status) {
        $this->idTutor = $idTutor;
        $this->intStatus = $status;
        $sql = "SELECT * FROM profesor p INNER JOIN alumno al ON al.profesor_id = p.id WHERE p.persona_id = $this->idTutor";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE persona SET status = ? WHERE id = $this->idTutor";
            $arrData = array($this->intStatus);
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
