<?php

/**
 * Description of GuiasModel
 *
 * @author mario
 */
class GuiasModel extends Mysql {

    private $idGuia;
    private $intIdColegio;
    private $rutGuia;
    private $strNombre;
    private $strApellido;
    private $strCorreo;
    private $intRol;
    private $strPassword;
    private $telefono;
    private $intStatus;
    private $idEmpresa;
    private $cargoGuia;
    private $option;

    public function __construct() {
        parent::__construct();
    }

    public function selectGuias($opcion = null) {
        $this->intIdColegio = $_SESSION["userData"]["detalleRol"]["colegio_id"];
        $this->option = $opcion != null ? "AND per.status != 0" : "";
        $sql = "SELECT dg.id as idguia, per.id,per.rut, per.nombre, per.correo, per.apellido, per.status, 
              DATE_FORMAT(per.created_at, '%d/%m/%Y') as fecha, DATE_FORMAT(per.created_at, '%H:%i:%s') AS hora, 
              em.nombre as nombreE, dg.cargo, dg.fono FROM persona per INNER JOIN guia dg ON per.id = dg.persona_id 
              INNER JOIN empresa em ON dg.empresa_id = em.id INNER JOIN persona_colegio pc ON per.id = pc.persona_id 
              WHERE pc.colegio_id = $this->intIdColegio $this->option";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertGuia(string $rut, string $nombre, string $apellido, string $correo, string $pass, string $rol, string $telefono, string $cargo, int $idempresa, int $idColegio) {
        $this->rutGuia = $rut;
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->strCorreo = $correo;
        $this->strPassword = $pass;
        $this->intRol = $rol;
        $this->telefono = $telefono;
        $this->cargoGuia = $cargo;
        $this->idEmpresa = $idempresa;
        $this->intIdColegio = $idColegio;
        $return = 0;
        $sql = "SELECT * FROM persona per INNER JOIN persona_colegio pc ON per.id = pc.persona_id "
                . "WHERE per.rut = '{$this->rutGuia}' AND nombre = '{$this->strNombre}' ";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_insert = "INSERT INTO persona(rut, nombre, apellido, correo, password, rol) VALUES (?,?,?,?,?,?)";
            $arrData = array($this->rutGuia,
                $this->strNombre,
                $this->strApellido,
                $this->strCorreo,
                $this->strPassword,
                $this->intRol);
            $request_insert = $this->insert($query_insert, $arrData);

            $sqlGuia = "INSERT INTO guia(persona_id,empresa_id,cargo,fono) VALUES (?,?,?,?)";
            $arrDataGuia = array($request_insert,
                $this->idEmpresa,
                $this->cargoGuia,
                $this->telefono);
            $request_detail = $this->insert($sqlGuia, $arrDataGuia);

            $sqlI = "INSERT INTO persona_colegio(persona_id,colegio_id,telefono) VALUES(?,?,?)";
            $arrD = array($request_insert,
                $this->intIdColegio,
                $this->telefono);
            $this->insert($sqlI, $arrD);

            $return = $request_detail;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateGuia(string $rut, string $nombre, string $apellido, string $correo, string $telefono, string $cargo, int $idempresa, int $idGuia) {
        $this->rutGuia = $rut;
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->strCorreo = $correo;
        $this->telefono = $telefono;
        $this->cargoGuia = $cargo;
        $this->idEmpresa = $idempresa;
        $this->idGuia = $idGuia;
        $sql = "SELECT * FROM persona WHERE rut = '{$this->rutGuia}' AND id != '{$this->idGuia}' ";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sqlUpdate = "UPDATE persona SET rut=?, nombre=?, correo=?, apellido=? WHERE id = $this->idGuia";
            $arrData = array($this->rutGuia,
                $this->strNombre,
                $this->strCorreo,
                $this->strApellido);
            $request = $this->update($sqlUpdate, $arrData);

            $sqlDetail = "UPDATE guia SET empresa_id= ?, cargo = ?, fono = ? WHERE persona_id = $this->idGuia";
            $arrDataDetail = array($this->idEmpresa,
                $this->cargoGuia,
                $this->telefono);
            $request_detail = $this->update($sqlDetail, $arrDataDetail);
            $return = $request_detail;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function selectGuia(int $idGuia) {
        $this->idGuia = $idGuia;
        $sql = "SELECT per.id, dg.id as idguia, per.rut, per.nombre, per.rol, per.correo, per.apellido, per.status, per.rol,"
                . "DATE_FORMAT(per.created_at, '%d/%m/%Y') as fecha, DATE_FORMAT(per.created_at, '%H:%i:%s') AS hora,em.nombre as nombreE, "
                . "dg.cargo, dg.fono, em.id as idempresa FROM persona per INNER JOIN guia dg ON per.id = dg.persona_id "
                . "INNER JOIN empresa em ON dg.empresa_id = em.id WHERE per.id = $this->idGuia";
        $request = $this->select($sql);
        return $request;
    }

    public function updateStatusGuia(int $idGuia, int $status) {
        $this->idGuia = $idGuia;
        $this->intStatus = $status;
        $sql = "SELECT * FROM guia g INNER JOIN alumno al ON al.guia_id = g.id WHERE g.persona_id = $this->idGuia";
        $requestDetail = $this->select_all($sql);
        if (empty($requestDetail)) {
            $sql = "UPDATE persona SET status = ? WHERE id = $this->idGuia";
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
