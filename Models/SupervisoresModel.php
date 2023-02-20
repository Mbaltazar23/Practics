<?php

/**
 * Description of SupervisoresModel
 *
 * @author mario
 */
class SupervisoresModel extends Mysql {

    public $intIdUsuario;
    public $intIdColegio;
    public $intIdVin;
    public $strNombre;
    public $strApellido;
    public $strEmail;
    public $strPassword;
    public $strDni;
    public $strDireccion;
    public $strTelefono;
    public $intStatus;
    public $strRole;

    public function __construct() {
        parent::__construct();
    }

    public function selectSupervisors($option = NULL) {
        $this->intStatus = $option != NULL ? " AND usr.status != 0" : "";
        $this->strRole = ROLADMINCOLE;
        $sql = "SELECT usr.id, usr.nombre, usr.correo, usr.rut, usr.direccion, usr.status, usr.rol
                FROM persona usr WHERE usr.rol = '$this->strRole' $this->intStatus";
        $request = $this->select_all($sql);
        for ($i = 0; $i < count($request); $i++) {
            $this->intIdUsuario = $request[$i]["id"];
            if ($request[$i]["status"] != 1) {
                $sqlSchool = "SELECT cu.id as idVin, c.id,c.rut,c.nombre, cu.telefono FROM persona_colegio cu 
                    INNER JOIN colegio c ON cu.colegio_id = c.id WHERE cu.persona_id = $this->intIdUsuario";
                $requesSch = $this->select($sqlSchool);
                $request[$i]["school"] = $requesSch;
            }
        }
        return $request;
    }

    public function selectSupervisor(int $idUser) {
        $this->intIdUsuario = $idUser;
        $sql = "SELECT usr.id, usr.nombre, usr.apellido, usr.correo, usr.rut, usr.direccion, DATE_FORMAT(usr.created_at, '%d/%m/%Y') as fecha, 
               DATE_FORMAT(usr.created_at, '%H:%i:%s') as hora, usr.status, usr.rol FROM persona usr WHERE usr.id = $this->intIdUsuario";
        $request = $this->select($sql);
        if ($request["status"] != 1) {
            $sqlSchool = "SELECT cu.id as idVin, c.id,c.rut,c.nombre, cu.telefono FROM persona_colegio cu 
                    INNER JOIN colegio c ON cu.colegio_id = c.id WHERE cu.persona_id = $this->intIdUsuario";
            $requesSch = $this->select($sqlSchool);
            $request["school"] = $requesSch;
        }

        return $request;
    }

    public function insertSupervisor(string $nombre, string $apellido, string $email, string $password, string $dni, string $direccion, string $role) {
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->strEmail = $email;
        $this->strPassword = $password;
        $this->strDni = $dni;
        $this->strDireccion = $direccion;
        $this->strRole = $role;
        $return = 0;
        $sql = "SELECT * FROM persona WHERE correo = '{$this->strEmail}' AND rut = '{$this->strDni}'";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_insert = "INSERT INTO persona(nombre, apellido,correo,password,rut,direccion,rol) VALUES(?,?,?,?,?,?,?)";
            $arrData = array($this->strNombre,
                $this->strApellido,
                $this->strEmail,
                $this->strPassword,
                $this->strDni,
                $this->strDireccion,
                $this->strRole);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateSupervisor(int $idusuario, string $nombre, string $apellido, string $email, string $password, string $dni, string $direccion) {
        $this->intIdUsuario = $idusuario;
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->strEmail = $email;
        $this->strPassword = $password;
        $this->strDni = $dni;
        $this->strDireccion = $direccion;
        $sql = "SELECT * FROM usuarios WHERE id != $this->intIdUsuario AND correo = '{$this->strEmail}'";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_update = "UPDATE usuarios SET nombre = ?, apellido=?, correo = ?, password = ?, rut = ?,"
                    . " direccion = ? WHERE id = $this->intIdUsuario";
            $arrData = array($this->strNombre,
                $this->strApellido,
                $this->strEmail,
                $this->strPassword,
                $this->strDni,
                $this->strDireccion);
            $request = $this->update($query_update, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }

    public function insertDetailSchool(int $idUsuario, int $idColegio, string $telefono) {
        $this->intIdUsuario = $idUsuario;
        $this->intIdColegio = $idColegio;
        $this->strTelefono = $telefono;
        $return = 0;
        $sql = "SELECT * FROM persona_colegio WHERE persona_id = $this->intIdUsuario AND colegio_id = $this->intIdColegio";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_insert = "INSERT INTO persona_colegio(persona_id, colegio_id, telefono) VALUES (?,?,?)";
            $arrData = array($this->intIdUsuario,
                $this->intIdColegio,
                $this->strTelefono);
            $return = $this->insert($query_insert, $arrData);
            $sqlUpdate = "UPDATE persona SET status = ? WHERE id = $this->intIdUsuario";
            $arrD = array(2);
            $this->update($sqlUpdate, $arrD);
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateDetailSchool(int $idUsuario, int $idColegio, string $telefono, int $idVin) {
        $this->intIdUsuario = $idUsuario;
        $this->intIdColegio = $idColegio;
        $this->strTelefono = $telefono;
        $this->intIdVin = $idVin;
        $sql = "SELECT * FROM persona_colegio WHERE colegio_id = $this->intIdColegio AND id != $this->intIdVin";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_update = "UPDATE persona_colegio SET colegio_id = ?, persona_id = ?, telefono = ? WHERE id = $this->intIdVin";
            $arrData = array($this->intIdColegio,
                $this->intIdUsuario,
                $this->strTelefono);
            $request = $this->update($query_update, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }

    public function removeDetailSchool(int $idUsuario) {
        $this->intIdUsuario = $idUsuario;
        $sql = "SELECT pc.persona_id, pc.colegio_id, cs.nombre FROM persona_colegio pc 
                INNER JOIN colegio c ON pc.colegio_id = c.id INNER JOIN curso cs ON c.id = cs.colegio_id 
                WHERE pc.persona_id = $this->intIdUsuario";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_delete = "DELETE FROM persona_colegio WHERE persona_id = $this->intIdUsuario";
            $request = $this->delete($query_delete);
            if ($request) {
                $request = 'ok';
                $sqlUpdate = "UPDATE persona SET status = ? WHERE id = $this->intIdUsuario";
                $arrData = array(1);
                $this->update($sqlUpdate, $arrData);
            } else {
                $request = 'error';
            }
        } else {
            $request = 'exist';
        }
        return $request;
    }

    public function updateStatusSupervisor(int $idpersona, int $status) {
        $this->intIdUsuario = $idpersona;
        $this->intStatus = $status;
        $sql = "SELECT * FROM persona_colegio WHERE persona_id = $this->intIdUsuario";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_delete = "UPDATE persona SET status = ? WHERE id = $this->intIdUsuario";
            $arrData = array($this->intStatus);
            $request = $this->update($query_delete, $arrData);
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
