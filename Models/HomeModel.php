<?php

class HomeModel extends Mysql {

    private $intIdUsuario;
    private $intIdUsuario02;
    private $strUsuario;
    private $strPassword;
    private $strSearch;
    private $strMessage;
    private $strFoto;

    public function __construct() {
        parent::__construct();
    }

    public function loginUser(string $usuario, string $password) {
        $this->strUsuario = $usuario;
        $this->strPassword = $password;
        $sql = "SELECT per.idpersona,  per.nombres, per.rut FROM persona per  
               WHERE per.correo = '$this->strUsuario' and per.password = '$this->strPassword'";
        $request = $this->select($sql);
        return $request;
    }

    public function sessionLogin(int $iduser) {
        $this->intIdUsuario = $iduser;
        //BUSCAR PERSONAL 
        $sql = "SELECT per.idpersona,per.nombres, per.rut, per.correo,per.apellidos, per.status, per.password, per.avatar, per.fecha,
               r.nombre as rol FROM persona per INNER JOIN rol r ON r.idrol = per.rolid WHERE per.idpersona = $this->intIdUsuario";
        $request = $this->select($sql);

        //cambiar sesion al loguearse 

        $sqlU = "UPDATE persona SET sesion = ? WHERE idpersona = " . $request["idpersona"];
        $arrData = array("Activo");
        $this->update($sqlU, $arrData);

        //cargamos el rol del usuario
        $sqlRol = "";
        if ($request["rol"] == ROLADMIN) {
            $request["encabezado"] = "Este sera el apartado del admin que podra mirar varias funciones";
            $request["titulo"] = $request["rol"];
        } else if ($request["rol"] == ROLPROFE) {
            $request["encabezado"] = "Este sera el apartado del profesor que podra mirar estas funciones";
            $request["titulo"] = $request["rol"];
            $sqlRol = "SELECT tu.idtutor, tu.fono, r.nombre as Rol FROM tutor tu INNER JOIN persona per ON per.idpersona = tu.personaid "
                    . "INNER JOIN rol r ON per.rolid = r.idrol WHERE per.idpersona = " . $request["idpersona"];
        } else if ($request["rol"] == ROLGUIA) {
            $request["encabezado"] = "Este sera el apartado del profesor guia que podra mirar estas funciones";
            $request["titulo"] = $request["rol"];
            $sqlRol = "SELECT  g.idguia,g.cargo, g.fono,em.rut, em.nombre,r.nombre as Rol FROM guia g INNER JOIN persona per ON per.idpersona = g.personaid "
                    . "INNER JOIN rol r ON per.rolid = r.idrol INNER JOIN empresa em ON em.idempresa = g.empresaid WHERE per.idpersona = " . $request["idpersona"];
        } else {
            $request["encabezado"] = "Este sera el apartado del alumno que podra mirar estas funciones";
            $request["titulo"] = $request["rol"];
            $sqlRol = "SELECT al.idalumno, al.especialidad, al.curso, al.fono, al.fono02 FROM alumno al "
                    . "INNER JOIN persona per ON per.idpersona = al.personaid WHERE per.idpersona = " . $request["idpersona"];
        }
        $requestRol = $sqlRol != "" ? $this->select($sqlRol) : "";
        $request["statusLog"] = true;
        $request["detalleRol"] = $requestRol;
        $request["imgAvatar"] = $request["avatar"] != "" ? media() . "/images/perfil/" . $request["avatar"] : media() . "/images/perfil/perfil-portada.jpg";
        $_SESSION['userData'] = $request;

        return $request;
    }

    public function getUser(int $iduser) {
        $this->intIdUsuario = $iduser;
        //BUSCAR PERSONAL 
        $sql = "SELECT per.idpersona,per.nombres, per.rut, per.correo,per.apellidos, per.status, per.password, per.avatar,per.sesion,
               r.nombre as rol FROM persona per INNER JOIN rol r ON r.idrol = per.rolid WHERE per.idpersona = $this->intIdUsuario";
        $request = $this->select($sql);
        return $request;
    }

    public function setPerfilLogin(int $idpersonal, string $password) {
        $this->intIdUsuario = $idpersonal;
        $this->strPassword = $password;
        $sql = "UPDATE persona SET password = ?  WHERE idpersona = $this->intIdUsuario";
        $arrData = array($this->strPassword);
        $request = $this->update($sql, $arrData);
        if ($request) {
            return true;
        } else {
            return false;
        }
    }

    public function insertPortada(int $idpersonal, string $foto) {
        $this->intIdUsuario = $idpersonal;
        $this->strFoto = $foto;
        $sql = "UPDATE persona SET avatar = ? WHERE idpersona = $this->intIdUsuario";
        $arrData = array($this->strFoto);
        $request = $this->update($sql, $arrData);
        if ($request) {
            return true;
        } else {
            return false;
        }
    }

    //funciones para el control de chat a diseñar

    public function selectPersonsChat(int $idpersona, string $searchName) {
        $this->intIdUsuario = $idpersona;
        $this->strSearch = $searchName;
        $request = "";
        if ($this->strSearch != "") {
            $sqlPersons = "SELECT * FROM persona WHERE NOT idpersona = $this->intIdUsuario "
                    . "AND (nombres LIKE '%$this->strSearch%' OR apellidos LIKE '%$this->strSearch%')";
            $request = $this->select_all($sqlPersons);
        }
        if ($request != "") {
            for ($i = 0; $i < count($request); $i++) {
                $idperson = $request[$i]["idpersona"];
                $sqlMsj = "SELECT * FROM mensaje WHERE (personaid_en = $idperson OR personaid_rec = $idperson) "
                        . "AND (personaid_rec = $idperson OR personaid_en = $idperson) ORDER BY idenvio DESC LIMIT 1";
                $messages = $this->select_all($sqlMsj);
                $request[$i]["messages"] = "";
                $request[$i]["personaRecep"] = "";
                if (count($messages) > 0) {
                    for ($j = 0; $j < count($messages); $j++) {
                        if ($request[$i]["idpersona"] == $messages[$j]["personaid_rec"]) {
                            $request[$i]["messages"] .= $messages[$j]["texto"];
                            $request[$i]["personaRecep"] .= $messages[$j]["personaid_en"];
                        }
                    }
                }
            }
        }

        return $request;
    }

    public function selectPersonsActive(int $idpersona) {
        $this->intIdUsuario = $idpersona;
        $sqlPersons = "SELECT * FROM persona WHERE NOT idpersona = $this->intIdUsuario ORDER BY idpersona DESC";
        $request = $this->select_all($sqlPersons);
        if (count($request) > 0) {
            for ($i = 0; $i < count($request); $i++) {
                $idperson = $request[$i]["idpersona"];
                $sqlMsj = "SELECT * FROM mensaje WHERE (personaid_en = $idperson OR personaid_rec = $idperson) "
                        . "AND (personaid_rec = $idperson OR personaid_en = $idperson) ORDER BY idenvio DESC LIMIT 1";
                $messages = $this->select_all($sqlMsj);
                $request[$i]["messages"] = "";
                $request[$i]["personaRecep"] = "";
                if (count($messages) > 0) {
                    for ($j = 0; $j < count($messages); $j++) {
                        if ($request[$i]["idpersona"] == $messages[$j]["personaid_rec"]) {
                            $request[$i]["messages"] .= $messages[$j]["texto"];
                            $request[$i]["personaRecep"] .= $messages[$j]["personaid_en"];
                        }
                    }
                }
            }
        } else {
            $request = "";
        }

        return $request;
    }

    public function desconectarSession($idpersona) {
        $this->intIdUsuario = $idpersona;
        $sqlU = "UPDATE persona SET sesion = ? WHERE idpersona = $this->intIdUsuario";
        $arrData = array("Inactivo");
        $request = $this->update($sqlU, $arrData);
        return $request;
    }

    public function insertChat(int $idpersonaEnv, int $idpersonaRecep, string $mensaje) {
        $this->intIdUsuario = $idpersonaEnv;
        $this->intIdUsuario02 = $idpersonaRecep;
        $this->strMessage = $mensaje;

        $sql = "INSERT INTO mensaje(personaid_en, personaid_rec, texto) VALUES (?,?,?)";

        $arrData = array($this->intIdUsuario,
            $this->intIdUsuario02,
            $this->strMessage);

        $request = $this->insert($sql, $arrData);
        return $request;
    }

    public function selectMessages(int $idpersonaEnv, int $idpersonaRecep) {
        $this->intIdUsuario = $idpersonaEnv;
        $this->intIdUsuario02 = $idpersonaRecep;
        $sql = "SELECT * FROM mensaje msg LEFT JOIN persona per ON per.idpersona = msg.personaid_rec"
                . " WHERE (msg.personaid_rec = $this->intIdUsuario02 AND msg.personaid_en = $this->intIdUsuario) "
                . "OR (msg.personaid_rec = $this->intIdUsuario AND msg.personaid_en = $this->intIdUsuario02) "
                . "ORDER BY msg.idenvio";
        $request = $this->select_all($sql);
        return $request;
    }

}

?>