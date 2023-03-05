<?php

class AlumnosModel extends Mysql {

    private $intIdAlumno;
    private $intIdColegio;
    private $rutAlu;
    private $strNombre;
    private $strApellido;
    private $strEspecialidad;
    private $strCurso;
    private $intTelefono;
    private $intTelefono02;
    private $rolAlu;
    private $strEmail;
    private $strPassword;
    private $intIdTutor;
    private $intIdGuia;
    private $intStatus;
    private $option;

    // Status =>  1: activo, 2: con plan, 0: inactivo

    public function __construct() {
        parent::__construct();
    }

    public function selectAlumns($opcion = NULL) {
        $this->intIdColegio = $_SESSION["userData"]["detalleRol"]["colegio_id"];
        $this->option = $opcion != NULL ? " AND per.status = 1 " : "";
        $sql = "SELECT per.id, da.id as idalum, per.rut, per.nombre, per.correo, per.apellido, per.status,
             DATE_FORMAT(per.created_at, '%d/%m/%Y') as fecha, DATE_FORMAT(per.created_at, '%H:%i:%s') AS hora,
             e.nombre as especialidad,cs.nombre as curso, da.fono, da.fono02 FROM persona per 
             INNER JOIN alumno da ON per.id = da.persona_id INNER JOIN especialidad e ON da.especialidad_id = e.id 
             INNER JOIN curso cs ON da.curso_id = cs.id WHERE cs.colegio_id = $this->intIdColegio $this->option";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectAlumnsRol($objectPerson) {
        $this->option = "";
        if ($objectPerson["rol"] == ROLGUIA) {
            $this->intIdGuia = $objectPerson["id"];
            $this->option = "WHERE da.guia_id = $this->intIdGuia AND per.status = 2";
        } else {
            $this->intIdTutor = $objectPerson["id"];
            $this->option = "WHERE da.profesor_id = $this->intIdTutor AND per.status != 0";
        }
        $sql = "SELECT per.id, da.id as idalum, per.rut, per.nombre, per.correo, per.apellido, per.status,
             DATE_FORMAT(per.created_at, '%d/%m/%Y') as fecha, DATE_FORMAT(per.created_at, '%H:%i:%s') AS hora,
             e.nombre as especialidad,cs.nombre as curso, da.fono, da.fono02 FROM persona per 
             INNER JOIN alumno da ON per.id = da.persona_id INNER JOIN especialidad e ON da.especialidad_id = e.id 
             INNER JOIN curso cs ON da.curso_id = cs.id $this->option";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectAlumnsRols(int $id, string $rolPerfil) {
        $this->option = "";
        if ($rolPerfil == ROLGUIA) {
            $this->intIdGuia = $id;
            $this->option = "WHERE da.guia_id = $this->intIdGuia AND per.status = 2";
        } else {
            $this->intIdTutor = $id;
            $this->option = "WHERE da.profesor_id = $this->intIdTutor AND per.status != 0";
        }
        $sql = "SELECT per.id, da.id as idalum, per.rut, per.nombre, per.correo, per.apellido, per.status,
             DATE_FORMAT(per.created_at, '%d/%m/%Y') as fecha, DATE_FORMAT(per.created_at, '%H:%i:%s') AS hora,
             e.nombre as especialidad,cs.nombre as curso, da.fono, da.fono02 FROM persona per 
             INNER JOIN alumno da ON per.id = da.persona_id INNER JOIN especialidad e ON da.especialidad_id = e.id 
             INNER JOIN curso cs ON da.curso_id = cs.id $this->option";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectAlumnsReportRol($objectPerson) {
        $this->option = "";
        if ($objectPerson["rol"] == ROLGUIA) {
            $this->intIdGuia = $objectPerson["id"];
            $this->option = "WHERE da.guia_id = $this->intIdGuia AND per.status = 2";
        } else {
            $this->intIdTutor = $objectPerson["id"];
            $this->option = "WHERE da.profesor_id = $this->intIdTutor AND per.status != 0";
        }
        $sql = "SELECT per.id,per.rut, per.nombre, per.correo, per.apellido, per.status,
             DATE_FORMAT(per.created_at, '%d/%m/%Y') as fecha, DATE_FORMAT(per.created_at, '%H:%i:%s') AS hora,
             e.nombre as especialidad,cs.nombre as curso, da.fono, da.fono02 FROM persona per 
             INNER JOIN alumno da ON per.id = da.persona_id INNER JOIN especialidad e ON da.especialidad_id = e.id 
             INNER JOIN curso cs ON da.curso_id = cs.id $this->option";
        $request = $this->select_all($sql);
        if (count($request) > 0) {
            $requestPlan = "";
            for ($i = 0; $i < count($request); $i++) {
                $idAlumn = $request[$i]["id"];
                $sqlPlan = "SELECT dp.detalleplan, dp.id, dp.plan_id ,pl.nombre as nombreP FROM alumno_plan dp 
                         INNER JOIN alumno al ON dp.alumno_id = al.id INNER JOIN plan pl ON dp.plan_id = pl.id
                          WHERE al.persona_id = $idAlumn";
                $requestPlan = $this->select_all($sqlPlan);

                if (count($requestPlan) > 0) {
                    $requestTareas = "";
                    for ($j = 0; $j < count($requestPlan); $j++) {
                        $idPlan = $requestPlan[$j]["id"];
                        $sqlTareas = "SELECT ta.id,ta.nombre FROM tarea ta 
                                 INNER JOIN tarea_plan dt ON ta.id = dt.tarea_id WHERE dt.plan_id = $idPlan AND (dt.status = 2 OR dt.status = 3)";
                        $requestTareas = $this->select_all($sqlTareas);
                    }
                }
                $request[$i]["planAlum"] = count($requestPlan) > 0 ? $requestPlan : "";
                $request[$i]["tareasPlan"] = count($requestTareas) > 0 ? $requestTareas : "";
            }
        }

        return $request;
    }

    public function insertAlum(string $rut, string $nombre, string $apellido, int $especialidad, int $curso, string $telefono, string $telefono02, string $rolid, string $correo, string $pass, int $idtutor, int $idguia, int $idcolegio) {
        $this->rutAlu = $rut;
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->strEspecialidad = $especialidad;
        $this->strCurso = $curso;
        $this->intTelefono = $telefono;
        $this->intTelefono02 = $telefono02;
        $this->rolAlu = $rolid;
        $this->strEmail = $correo;
        $this->strPassword = $pass;
        $this->intIdTutor = $idtutor;
        $this->intIdGuia = $idguia;
        $this->intIdColegio = $idcolegio;
        $return = 0;
        $sql = "SELECT * FROM persona per INNER JOIN persona_colegio pc ON per.id = pc.persona_id 
                WHERE per.correo =  '{$this->strEmail}' AND per.nombre = '{$this->strNombre}' AND pc.colegio_id = $this->intIdColegio";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_insert = "INSERT INTO persona(rut, nombre, apellido, correo, password,rol) VALUES (?,?,?,?,?,?)";
            $arrData = array($this->rutAlu,
                $this->strNombre,
                $this->strApellido,
                $this->strEmail,
                $this->strPassword,
                $this->rolAlu);
            $request_insert = $this->insert($query_insert, $arrData);

            $sqlAlu = "INSERT INTO alumno(persona_id, guia_id, profesor_id, curso_id, especialidad_id, fono, fono02)  VALUES (?,?,?,?,?,?,?)";
            $arrDataAlu = array($request_insert,
                $this->intIdGuia,
                $this->intIdTutor,
                $this->strCurso,
                $this->strEspecialidad,
                $this->intTelefono,
                $this->intTelefono02);
            $this->insert($sqlAlu, $arrDataAlu);
            $return = $request_insert;
            $sqlI = "INSERT INTO persona_colegio(persona_id,colegio_id,telefono) VALUES(?,?,?)";
            $arrD = array($request_insert,
                $this->intIdColegio,
                empty($telefono) ? $this->intTelefono02 : $this->intTelefono);
            $this->insert($sqlI, $arrD);
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateAlumn(string $rut, string $nombre, string $apellido, int $especialidad, int $curso, string $telefono, string $telefono02, string $correo, int $idtutor, int $idguia, int $idAlumn) {
        $this->rutAlu = $rut;
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->strEspecialidad = $especialidad;
        $this->strCurso = $curso;
        $this->intTelefono = $telefono;
        $this->intTelefono02 = $telefono02;
        $this->strEmail = $correo;
        $this->intIdTutor = $idtutor;
        $this->intIdGuia = $idguia;
        $this->intIdAlumno = $idAlumn;
        $sql = "SELECT * FROM persona WHERE rut = '{$this->rutAlu}' AND id != '{$this->intIdAlumno}' ";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sqlUpdate = "UPDATE persona SET rut = ?, nombre =?, correo=?, apellido = ? WHERE id = $this->intIdAlumno";
            $arrData = array($this->rutAlu,
                $this->strNombre,
                $this->strEmail,
                $this->strApellido);
            $request = $this->update($sqlUpdate, $arrData);

            $sqlAlu = "UPDATE alumno SET guia_id = ?, profesor_id = ?, curso_id = ?,
                     especialidad_id = ?, fono = ?, fono02 = ? WHERE persona_id = $this->intIdAlumno";
            $arrDataAlu = array($this->intIdGuia,
                $this->intIdTutor,
                $this->strCurso,
                $this->strEspecialidad,
                $this->intTelefono,
                $this->intTelefono02);
            $this->update($sqlAlu, $arrDataAlu);
            $return = $request;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function selectAlumno(int $idAlum) {
        $this->intIdAlumno = $idAlum;
        $sql = "SELECT per.id, da.id as idalum, per.rut, per.nombre, per.correo, per.apellido, per.status,
             DATE_FORMAT(per.created_at, '%d/%m/%Y') as fecha, DATE_FORMAT(per.created_at, '%H:%i:%s') AS hora,
             e.nombre as especialidad ,e.id as idesp,cs.nombre as curso, cs.id as idcurso, da.fono, da.fono02, da.guia_id, da.profesor_id FROM persona per 
             INNER JOIN alumno da ON per.id = da.persona_id INNER JOIN especialidad e ON da.especialidad_id = e.id 
             INNER JOIN curso cs ON da.curso_id = cs.id WHERE per.id = $this->intIdAlumno";
        $request = $this->select($sql);
        //cargamos los datos del guia
        $IntIdGuia = $request["guia_id"];
        $sqlGuia = "SELECT per.id,per.rut, per.nombre, per.correo, per.apellido, per.status, DATE_FORMAT(per.created_at, '%d/%m/%Y') as fecha,
                DATE_FORMAT(per.created_at, '%H:%i:%s') AS hora,em.nombre as nombreE, dg.cargo, dg.fono FROM persona per 
                INNER JOIN guia dg ON per.id = dg.persona_id INNER JOIN empresa em ON dg.empresa_id = em.id WHERE dg.id = $IntIdGuia";
        $arrGuia = $this->select($sqlGuia);

        //cargamos los datos del tutor
        $IntIdTutor = $request["profesor_id"];
        $sqlTutor = "SELECT per.id,per.rut, per.nombre, per.correo, per.apellido, per.status, DATE_FORMAT(per.created_at, '%d/%m/%Y') as fecha,
                 DATE_FORMAT(per.created_at, '%H:%i:%s') AS hora, dt.fono FROM persona per INNER JOIN profesor dt ON per.id = dt.persona_id 
                 WHERE dt.id = $IntIdTutor";
        $arrTutor = $this->select($sqlTutor);


        $request["detailGuia"] = $arrGuia;
        $request["detailTutor"] = $arrTutor;
        return $request;
    }

    public function selectPlanAlum(int $idAlum) {
        $this->intIdAlumno = $idAlum;
        $sql = "SELECT per.rut, al.id as idalum, per.id, per.nombre, per.apellido, per.correo, dp.detalleplan, dp.id as iddetalleplan, dp.plan_id ,pl.nombre as nombreP,
                DATE_FORMAT(per.created_at, '%d/%m/%Y') as fecha, DATE_FORMAT(per.created_at, '%H:%i:%s') AS hora, es.nombre as especialidad, 
                cs.nombre as curso, al.fono, al.fono02,al.guia_id, al.profesor_id FROM alumno_plan dp INNER JOIN alumno al ON dp.alumno_id = al.id 
                INNER JOIN persona per ON al.persona_id = per.id INNER JOIN plan pl ON dp.plan_id = pl.id 
                INNER JOIN especialidad es ON al.especialidad_id = es.id INNER JOIN curso cs ON al.curso_id = cs.id 
                WHERE al.persona_id = $this->intIdAlumno";
        $request = $this->select($sql);

        //cargamos los datos del guia
        $IntIdGuia = $request["guia_id"];
        $sqlGuia = "SELECT per.id,per.rut, per.nombre, per.correo, per.apellido, per.status, DATE_FORMAT(per.created_at, '%d/%m/%Y') as fecha,
                DATE_FORMAT(per.created_at, '%H:%i:%s') AS hora,em.nombre as nombreE, dg.cargo, dg.fono FROM persona per 
                INNER JOIN guia dg ON per.id = dg.persona_id INNER JOIN empresa em ON dg.empresa_id = em.id WHERE dg.id = $IntIdGuia";
        $arrGuia = $this->select($sqlGuia);

        //cargamos los datos del tutor
        $IntIdTutor = $request["profesor_id"];
        $sqlTutor = "SELECT per.id,per.rut, per.nombre, per.correo, per.apellido, per.status, DATE_FORMAT(per.created_at, '%d/%m/%Y') as fecha,
                 DATE_FORMAT(per.created_at, '%H:%i:%s') AS hora, dt.fono FROM persona per INNER JOIN profesor dt ON per.id = dt.persona_id 
                 WHERE dt.id = $IntIdTutor";
        $arrTutor = $this->select($sqlTutor);


        $request["detailGuia"] = $arrGuia;
        $request["detailTutor"] = $arrTutor;
        return $request;
    }

    public function updateStatusAlu(int $idAlum, int $status) {
        $this->intIdAlumno = $idAlum;
        $this->intStatus = $status;
        $sql = "SELECT al.persona_id, al.fono, pla.alumno_id FROM alumno al 
            INNER JOIN alumno_plan pla ON al.id = pla.alumno_id WHERE al.persona_id = $this->intIdAlumno";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE persona SET status = ? WHERE id = $this->intIdAlumno";
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
