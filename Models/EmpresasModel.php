<?php

class EmpresasModel extends Mysql {

    private $idEmpresa;
    private $idColegio;
    private $nombreEmpresa;
    private $rutEmpresa;
    private $razonSocialEmpresa;
    private $statusEmpresa;

    public function __construct() {
        parent::__construct();
    }

    public function selectEmpresas($option = NULL) {
        $this->idColegio = $_SESSION["userData"]["detalleRol"]["colegio_id"];
        $this->statusEmpresa = $option != NULL ? "AND em.status != 0" : "";
        $sql = "SELECT em.id, em.nombre, em.rut, em.rubro, em.status FROM empresa em "
                . "INNER JOIN colegio_empresa cem ON em.id = cem.empresa_id WHERE cem.colegio_id = $this->idColegio $this->statusEmpresa";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectEmpresa(int $idEmpresa) {
        $this->idEmpresa = $idEmpresa;
        $sql = "SELECT * FROM empresa WHERE id = $this->idEmpresa";
        $request = $this->select($sql);
        return $request;
    }

    public function insertEmpresa(string $nombre, string $rut, string $razonsocial, int $idcolegio) {
        $this->nombreEmpresa = $nombre;
        $this->rutEmpresa = $rut;
        $this->razonSocialEmpresa = $razonsocial;
        $this->idColegio = $idcolegio;
        $return = 0;
        $sql = "SELECT * FROM empresa em INNER JOIN colegio_empresa cem ON em.id = cem.colegio_id 
                 WHERE em.nombre = '$this->nombreEmpresa' AND em.rut = '$this->rutEmpresa' AND cem.colegio_id = $this->idColegio";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_insert = "INSERT INTO empresa(nombre,rut,rubro) VALUES (?,?,?)";
            $arrData = array($this->nombreEmpresa,
                $this->rutEmpresa,
                $this->razonSocialEmpresa);
            $request_insert = $this->insert($query_insert, $arrData);
            if ($request_insert) {
                $sqlI = "INSERT INTO colegio_empresa(empresa_id,colegio_id) VALUES(?,?)";
                $arrD = array($request_insert,
                    $this->idColegio);
                $this->insert($sqlI, $arrD);
            }
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateEmpresa(string $nombre, string $rut, string $razonsocial, int $idEmpresa) {
        $this->nombreEmpresa = $nombre;
        $this->rutEmpresa = $rut;
        $this->razonSocialEmpresa = $razonsocial;
        $this->idEmpresa = $idEmpresa;
        $sql = "SELECT * FROM empresa WHERE nombre = '$this->nombreEmpresa' AND id != $this->idEmpresa";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE empresa SET nombre = ?, rut=? ,rubro=? WHERE id= $this->idEmpresa";
            $arrData = array($this->nombreEmpresa,
                $this->rutEmpresa,
                $this->razonSocialEmpresa);
            $request = $this->update($sql, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }

    public function getPersonsEmpresa(int $idEmpresa) {
        $this->idEmpresa = $idEmpresa;
        $sql = "SELECT per.id ,per.nombre, g.fono, per.correo, em.nombre as nombreEmpresa, per.status, g.cargo,g.fono FROM guia g 
                INNER JOIN persona per ON g.persona_id = per.id INNER JOIN empresa em ON g.empresa_id = em.id 
                WHERE em.id = $this->idEmpresa";
        $request = $this->select_all($sql);
        return $request;
    }

    public function updateStatusEmpresa(int $idempresa, int $status) {
        $this->idEmpresa = $idempresa;
        $this->statusEmpresa = $status;
        $sql = "SELECT * FROM guia WHERE empresa_id = $this->idEmpresa";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE empresa SET status = ? WHERE id = $this->idEmpresa";
            $arrData = array($this->statusEmpresa);
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

    //SELECT DE un futuro Reporte de empresas con ot registradas : 
    //SELECT DISTINCT empresa.idEmpresa, empresa.nombreEmpresa, empresa.rutEmpresa, empresa.razonSocialEmpresa, COUNT(detalleorden.detalleOrden) as Ot FROM empresa 
    //INNER JOIN detallecontactos ON empresa.idEmpresa = detallecontactos.detalleEmpresa 
    //INNER JOIN detalleorden ON detalleorden.detalleContacto = detallecontactos.detalleContactoEmpresa GROUP BY empresa.idEmpresa
}
