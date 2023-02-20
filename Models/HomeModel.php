<?php

class HomeModel extends Mysql {

    private $intIdUsuario;
    private $intIdUsuario02;
    private $strUsuario;
    private $strRut;
    private $strNombre;
    private $strApellido;
    private $strPassword;
    private $strSearch;
    private $strDireccion;
    private $strMessage;
    private $strFoto;

    public function __construct() {
        parent::__construct();
    }

    public function loginUser(string $usuario, string $password) {
        $this->strUsuario = $usuario;
        $this->strPassword = $password;
        $sql = "SELECT per.id,  per.nombre, per.rut FROM persona per  
               WHERE per.correo = '$this->strUsuario' AND per.password = '$this->strPassword'";
        $request = $this->select($sql);
        return $request;
    }

    public function sessionLogin(int $iduser) {
        $this->intIdUsuario = $iduser;
        //BUSCAR PERSONAL 
        $sql = "SELECT per.id, per.nombre, per.rut, per.correo, per.apellido, per.status, per.password, per.avatar,
               per.rol FROM persona per WHERE per.id = $this->intIdUsuario";
        $request = $this->select($sql);

        //cambiar sesion al loguearse 

        $sqlU = "UPDATE persona SET session = ? WHERE id = " . $request["id"];
        $arrData = array("Activo");
        $this->update($sqlU, $arrData);


        //cargamos el rol del usuario
        $sqlRol = "";
        switch ($request["rol"]) {
            case ROLADMIN:
                $request["encabezado"] = "Este sera el apartado del admin que podra mirar varias funciones";
                $request["titulo"] = $request["rol"];
                break;
            case ROLADMINCOLE:
                $request["encabezado"] = "Este sera el apartado del admin que estara afiliado al colegio al que trabajara";
                $request["titulo"] = $request["rol"];
                $sqlRol = "SELECT sp.colegio_id, c.nombre as nombreCole, DATE_FORMAT(per.created_at, '%d/%m/%Y') as fecha,
                    DATE_FORMAT(per.created_at, '%H:%i:%s') AS hora FROM persona_colegio sp INNER JOIN persona per ON sp.persona_id = per.id 
                    INNER JOIN colegio c ON sp.colegio_id = c.id WHERE sp.persona_id = " . $request["id"];
                break;
            case ROLPROFE:
                $request["encabezado"] = "Este sera el apartado del profesor que podra mirar estas funciones";
                $request["titulo"] = $request["rol"];
                $sqlRol = "SELECT pro.id, pro.fono, per.rol, sp.colegio_id, c.nombre as nombreCole FROM profesor pro INNER JOIN persona per 
                        ON pro.persona_id = per.id INNER JOIN persona_colegio sp ON per.id = sp.persona_id 
                        INNER JOIN colegio c ON sp.colegio_id = c.id WHERE per.id = " . $request["id"];
                break;
            case ROLGUIA:
                $request["encabezado"] = "Este sera el apartado del profesor guia que podra mirar estas funciones";
                $request["titulo"] = $request["rol"];
                $sqlRol = "SELECT g.id ,g.cargo, g.fono,em.rut, em.nombre, per.rol, sp.colegio_id, c.nombre as nombreCole FROM guia g 
                    INNER JOIN persona per ON g.persona_id = per.id INNER JOIN persona_colegio sp ON per.id = sp.persona_id 
                    INNER JOIN colegio c ON sp.colegio_id = c.id INNER JOIN empresa em ON em.id = g.empresa_id 
                    WHERE per.id = " . $request["id"];
                break;
            default:
                $request["encabezado"] = "Este sera el apartado del alumno que podra mirar estas funciones";
                $request["titulo"] = $request["rol"];
                $sqlRol = "SELECT al.id, es.nombre as especialidad, cs.nombre as curso, al.fono, al.fono02, per.rol, sp.colegio_id, c.nombre as nombreCole,
                        p.nombre as nombrePlan FROM alumno al INNER JOIN persona per ON per.id = al.persona_id 
                        INNER JOIN especialidad es ON al.especialidad_id = es.id INNER JOIN curso cs ON al.curso_id = cs.id 
                        INNER JOIN persona_colegio sp ON per.id = sp.persona_id INNER JOIN colegio c ON sp.colegio_id = c.id 
                        INNER JOIN alumno_plan ap ON al.id = ap.alumno_id INNER JOIN plan p ON ap.plan_id = p.id WHERE per.id = " . $request["id"];
                break;
        }


        $requestRol = $sqlRol != "" ? $this->select($sqlRol) : "";
        $request["statusLog"] = true;
        $request["detalleRol"] = $requestRol;
        $request["imgPerfil"] = $request["avatar"] != "" ? $request["avatar"] : "logo_icono.jpg";
        $_SESSION['userData'] = $request;

        return $request;
    }

    public function getUser(int $iduser) {
        $this->intIdUsuario = $iduser;
        //BUSCAR PERSONAL 
        $sql = "SELECT per.id, per.rut, per.nombre,per.apellido, per.correo,per.apellido,per.status, per.password, per.avatar,per.session,
               per.rol, per.direccion FROM persona per WHERE per.id = $this->intIdUsuario";
        $request = $this->select($sql);

        return $request;
    }

    public function setPerfilLogin(int $idUsuario, string $rut, string $nombre, string $apellido, string $email, string $direccion, string $password) {
        $this->intIdUsuario = $idUsuario;
        $this->strNombre = $nombre;
        $this->strRut = $rut;
        $this->strDireccion = $direccion;
        $this->strApellido = $apellido;
        $this->strUsuario = $email;
        $this->strPassword = $password;

        if ($this->strPassword != "") {
            $sql = "UPDATE persona SET rut =? ,nombre=?, apellido=?, correo = ?, password= ?, direccion =?
						WHERE id = $this->intIdUsuario ";
            $arrData = array($this->strRut,
                $this->strNombre,
                $this->strApellido,
                $this->strUsuario,
                $this->strPassword,
                $this->strDireccion);
        } else {
            $sql = "UPDATE persona SET rut =? ,nombre=?, apellido=? , correo = ?, direccion = ?
						WHERE id = $this->intIdUsuario ";
            $arrData = array($this->strRut,
                $this->strNombre,
                $this->strApellido,
                $this->strUsuario,
                $this->strDireccion);
        }
        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function insertPortada(int $idpersonal, string $foto) {
        $this->intIdUsuario = $idpersonal;
        $this->strFoto = $foto;
        $sql = "UPDATE persona SET avatar = ? WHERE id = $this->intIdUsuario";
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