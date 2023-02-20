<?php

class DashboardModel extends Mysql {

    public function __construct() {
        parent::__construct();
        session_start();
    }

    public function cantColegiosVisibles() {
        $sql = "SELECT COUNT(*) as cantColegios FROM colegio WHERE status != 0";
        $request = $this->select($sql);
        return $request["cantColegios"];
    }

    public function cantAdminsVisibles() {
        $sql = "SELECT COUNT(*) as cantAdmins FROM persona
                WHERE rol = '" . ROLADMINCOLE . "' AND status = 1";
        $request = $this->select($sql);
        return $request["cantAdmins"];
    }

    public function cantiSupervisoresActivos() {
        $sql = "SELECT COUNT(*) as cantidad_admin_colegios FROM persona 
                INNER JOIN persona_colegio ON persona.id = persona_colegio.persona_id
                WHERE rol = '" . ROLADMINCOLE . "'
               AND status = 2";
        $request = $this->select($sql);
        return $request["cantidad_admin_colegios"];
    }

    public function cantGuiasOf_Alumnos(int $idColegio) {
        $sqlG = "SELECT SUM(total_guias) AS cantidad_total_guias FROM (
                  SELECT COUNT(DISTINCT guia_id) AS total_guias
                FROM alumno INNER JOIN persona ON alumno.persona_id = persona.id 
                INNER JOIN persona_colegio ON persona.id = persona_colegio.persona_id 
                WHERE persona_colegio.colegio_id = ".$idColegio." ) subconsulta";
        $sumGuias = $this->select($sqlG);
        return $sumGuias["cantidad_total_guias"];
    }

    public function cantProfesOf_Alumnos(int $idColegio) {
        $sql = "SELECT SUM(total_profesores) AS cantidad_total_profesores FROM ( 
                SELECT COUNT(DISTINCT profesor_id) AS total_profesores FROM alumno 
                INNER JOIN persona ON alumno.persona_id = persona.id 
                INNER JOIN persona_colegio ON persona.id = persona_colegio.persona_id 
                WHERE persona_colegio.colegio_id = ".$idColegio." ) subconsulta";
        $sumProfes = $this->select($sql);
        return $sumProfes["cantidad_total_profesores"];
    }

    public function cantidadCursosConAlumnos(int $idColegio) {
        $sql = "SELECT SUM(total_cursos) AS cantidad_total_cursos FROM (
                 SELECT COUNT(DISTINCT curso_id) AS total_cursos
                FROM alumno INNER JOIN persona ON alumno.persona_id = persona.id 
                INNER JOIN persona_colegio ON persona.id = persona_colegio.persona_id 
                WHERE persona_colegio.colegio_id = ".$idColegio." ) subconsulta";
        $sumCurs = $this->select($sql);
        return $sumCurs["cantidad_total_cursos"];
    }

    public function cantidadAlumnosPlan(int $idColegio) {
        $sql = "SELECT SUM(total_alumnos_Plan) AS cantidad_total_alumn 
		FROM ( SELECT COUNT(DISTINCT alumno_id) AS total_alumnos_Plan FROM alumno_plan 
		INNER JOIN alumno ON alumno_plan.alumno_id = alumno.id INNER JOIN persona ON alumno.persona_id = persona.id 
		INNER JOIN persona_colegio ON persona.id = persona_colegio.persona_id 
		WHERE persona_colegio.colegio_id = ".$idColegio.") subconsulta";
        $sumAlumns = $this->select($sql);
        return $sumAlumns["cantidad_total_alumn"];
    }

    public function cantidadAlumnosProfe(int $idProfe) {
        $sql = "SELECT COUNT(*) AS cantidad_alumnos FROM alumno WHERE profesor_id = " . $idProfe;
        $request = $this->select($sql);
        return $request["cantidad_alumnos"];
    }

    public function cantidadAlumnosGuia(int $idGuia) {
        $sql = "SELECT COUNT(*) AS cantidad_alumnos FROM alumno WHERE guia_id = " . $idGuia;
        $request = $this->select($sql);
        return $request["cantidad_alumnos"];
    }

    public function cantidadTareasVisiblesAlumno(int $idAlumn) {
        $sql = "SELECT COUNT(tp.id) AS cantidad_tareas FROM alumno_plan ap 
               JOIN tarea_plan tp ON tp.plan_id = ap.plan_id WHERE ap.alumno_id = " . $idAlumn . " AND tp.status = 2";
        $request = $this->select($sql);
        return $request["cantidad_tareas"];
    }

    public function generarCardPanel() {
        $cardsPanel = "";
        if ($_SESSION["cargo-personal"] == ROLADMIN) {
            //obtener los datos
            $cantColegiosVisibles = $this->cantColegiosVisibles();
            $cantAdmins = $this->cantAdminsVisibles();
            $cantAdminsColeg = $this->cantiSupervisoresActivos();

            $cardsPanel = array(
                array(
                    "color" => "primary",
                    "icono" => "fas fa-graduation-cap",
                    "titulo" => "Colegios Disponibles",
                    "valor" => $cantColegiosVisibles
                ),
                array(
                    "color" => "info",
                    "icono" => "fas fa-user-tie",
                    "titulo" => "Supervisores Disponibles",
                    "valor" => $cantAdmins
                ),
                array(
                    "color" => "warning",
                    "icono" => "fas fa-cog",
                    "titulo" => "Supervisores en uso",
                    "valor" => $cantAdminsColeg
            ));
        } else if ($_SESSION["cargo-personal"] == ROLADMINCOLE) {
            // Obtener los datos necesarios
            $guiasCant = $this->cantGuiasOf_Alumnos($_SESSION["userData"]["detalleRol"]["colegio_id"]);
            $profesoresCant = $this->cantProfesOf_Alumnos($_SESSION["userData"]["detalleRol"]["colegio_id"]);
            $cantidadCursosConAlumnos = $this->cantidadCursosConAlumnos($_SESSION["userData"]["detalleRol"]["colegio_id"]);
            $cantidadAlumsPlan = $this->cantidadAlumnosPlan($_SESSION["userData"]["detalleRol"]["colegio_id"]);
            $cardsPanel = array(
                array(
                    "color" => "primary",
                    "icono" => "fas fa-chalkboard-teacher",
                    "titulo" => "Profesores en uso",
                    "valor" => $profesoresCant
                ),
                array(
                    "color" => "info",
                    "icono" => "fas fa-user-tie",
                    "titulo" => "Profesores Guias en uso",
                    "valor" => $guiasCant
                ),
                array(
                    "color" => "danger",
                    "icono" => "fas fa-graduation-cap",
                    "titulo" => "Cursos en uso",
                    "valor" => $cantidadCursosConAlumnos
                ),
                array(
                    "color" => "warning",
                    "icono" => "fas fa-laptop-code",
                    "titulo" => "Alumnos ya con plan de estudio",
                    "valor" => $cantidadAlumsPlan
                )
            );
        } else if ($_SESSION["cargo-personal"] == ROLGUIA || $_SESSION["cargo-personal"] == ROLPROFE) {
            $cantAlumns = $_SESSION["cargo-personal"] != ROLGUIA ? $this->cantidadAlumnosProfe($_SESSION['userData']['detalleRol']["id"]) :
                    $this->cantidadAlumnosGuia($_SESSION['userData']['detalleRol']["id"]);
            $cardsPanel = array(
                array(
                    "color" => "primary",
                    "icono" => "fas fa-graduation-cap",
                    "titulo" => "Alumnos a su cargo",
                    "valor" => $cantAlumns
            ));
        } else {
            $cantTareas = $this->cantidadTareasVisiblesAlumno($_SESSION['userData']['detalleRol']["id"]);
            $cardsPanel = array(
                array(
                    "color" => "primary",
                    "icono" => "fas fa-laptop-code",
                    "titulo" => "Tareas a realizar",
                    "valor" => $cantTareas
            ));
        }
        return $cardsPanel;
    }

}

?>