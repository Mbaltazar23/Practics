<?php

class Alumnos extends Controllers {

    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login']) || !isset($_SESSION["email-personal"])) {
            header('Location: ' . base_url());
            die();
        }
    }

    public function alumnos() {
        if ($_SESSION["cargo-personal"] != ROLGUIA && $_SESSION["cargo-personal"] != ROLPROFE) {
            $data['page_tag'] = NOMBRE_WEB . "- Alumnos";
            $data['page_title'] = "Alumnos";
            $data['page_name'] = "alumnos";
            $data['rol-personal'] = $_SESSION['cargo-personal'];
            $data['page_functions_js'] = "functions_alumns.js";
            $this->views->getView($this, "alumnos", $data);
        } else {
            $rol = $_SESSION["cargo-personal"] == ROLGUIA ? "alumnosguia" : "alumnosprofe";
            header('Location: ' . base_url() . '/alumnos/' . $rol);
        }
    }

    public function alumnosguia() {
        if ($_SESSION["cargo-personal"] == ROLADMINCOLE) {
            header('Location: ' . base_url() . '/alumnos');
            die();
        }
        $data['page_tag'] = NOMBRE_WEB . " - Alumnos del Guia " . $_SESSION['userData']["nombre"];
        $data['page_title'] = "Alumnos del Guia " . $_SESSION['userData']["nombre"];
        $data['page_name'] = "alumnos-guia";
        $data['rol-table'] = "rolGuia";
        $data['rol-personal'] = $_SESSION['cargo-personal'];
        $data['page_functions_js'] = "functions_alumRol.js";
        $this->views->getView($this, "alumnosguia", $data);
    }

    public function alumnosprofe() {
        if ($_SESSION["cargo-personal"] == ROLADMINCOLE) {
            header('Location: ' . base_url() . '/alumnos');
            die();
        }
        $data['page_tag'] = NOMBRE_WEB . "- Alumnos del Profe " . $_SESSION['userData']["nombre"];
        $data['page_title'] = "Alumnos del Profe " . $_SESSION['userData']["nombre"];
        $data['page_name'] = "alumnos-profe";
        $data['rol-table'] = "rolProfe";
        $data['rol-personal'] = $_SESSION['cargo-personal'];
        $data['page_functions_js'] = "functions_alumRol.js";
        $this->views->getView($this, "alumnosprofe", $data);
    }

    public function getSelectAlumnos() {
        $htmlOptions = "";
        $arrData = $this->model->selectAlumns(1);
        echo '<option value="0">Seleccione un Alumno</option>';
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['idalum'] . '">' . $arrData[$i]['nombre'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function getAlumnos() {
        $listAlumns = $this->model->selectAlumns();
        for ($i = 0; $i < count($listAlumns); $i++) {
            $btnView = '';
            $btnEdit = '';
            $btnDelAc = '';
            if ($listAlumns[$i]["status"] == 1) {
                $listAlumns[$i]["status"] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $listAlumns[$i]['id'] . ')" title="Ver Alumno"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary btn-sm" onClick="fntEditInfo(this,' . $listAlumns[$i]['id'] . ')" title="Editar Alumno"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelAc = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $listAlumns[$i]['id'] . ')" title="Eliminar Alumno"><i class="far fa-trash-alt"></i></button>';
            } else if ($listAlumns[$i]["status"] == 2) {
                $listAlumns[$i]["status"] = '<span class="badge badge-dark">Con Plan</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $listAlumns[$i]['id'] . ')" title="Ver Alumno"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary btn-sm" onClick="fntEditInfo(this,' . $listAlumns[$i]['id'] . ')" title="Editar Alumno"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelAc = '<button class="btn btn-secondary btn-sm" title="Alumno Con plan" disabled><i class="fas fa-ban" aria-hidden="true"></i></button>';
            } else {
                $listAlumns[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $listAlumns[$i]['id'] . ')" title="Ver Alumno" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $listAlumns[$i]['id'] . ')" title="Editar rol" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnDelAc = '<button class="btn btn-secondary btn-sm" onClick="fntActivateInfo(' . $listAlumns[$i]['id'] . ')" title="Activar Alumno"><i class="fas fa-toggle-on"></i></button>';
            }
            $listAlumns[$i]["nombres%"] = $listAlumns[$i]["nombre"] . ' ' . $listAlumns[$i]["apellido"];
            $listAlumns[$i]['options'] = '<div class="text-center">' . $btnView . '  ' . $btnEdit . ' ' . $btnDelAc . '</div>';
        }
        echo json_encode($listAlumns, JSON_UNESCAPED_UNICODE);
    }

    public function getAlumnosRol() {
        $listAlumns = $this->model->selectAlumnsRol($_SESSION['userData']['detalleRol']);

        for ($i = 0; $i < count($listAlumns); $i++) {
            $btnView = '';
            $btnPlan = '';
            $btnAlumCalf = '';
            if ($listAlumns[$i]["status"] == 1) {
                $listAlumns[$i]["status"] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $listAlumns[$i]['id'] . ')" title="Ver Alumno"><i class="far fa-eye"></i></button>';
                $btnPlan = '<button class="btn btn-secondary btn-sm" onClick="fntViewDoc(' . $listAlumns[$i]['id'] . ')" title="Ver Documentacion" disabled><i class="fas fa-book"></i></button>';
                $btnAlumCalf = '<button class="btn btn-secondary btn-sm" onClick="fntViewNoteP(' . $listAlumns[$i]['id'] . ')" title="Ver Nota del Plan" disabled><i class="fas fa-star"></i></button>';

                if ($_SESSION["cargo-personal"] == ROLGUIA) {
                    $btnPlan = '<button class="btn btn-dark btn-sm" onClick="fntPlanAlumV(' . $listAlumns[$i]['id'] . ')" title="Ver Plan"><i class="far fa-address-book"></i></button>';
                }
            } else if ($listAlumns[$i]["status"] == 2) {
                $listAlumns[$i]["status"] = '<span class="badge badge-dark">Con Plan</span>';

                if ($_SESSION["cargo-personal"] == ROLPROFE) {
                    $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $listAlumns[$i]['id'] . ')" title="Ver Alumno"><i class="far fa-eye"></i></button>';
                    $btnPlan = '<button class="btn btn-primary btn-sm" onClick="fntViewDoc(' . $listAlumns[$i]['id'] . ')" title="Ver Documentacion"><i class="fas fa-book"></i></button>';
                    $btnAlumCalf = '<button class="btn btn-dark btn-sm" onClick="fntViewNoteP(' . $listAlumns[$i]['id'] . ')" title="Ver Nota del Plan"><i class="fas fa-star"></i></button>';
                } else if ($_SESSION["cargo-personal"] == ROLGUIA) {
                    $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfoP(' . $listAlumns[$i]['id'] . ')" title="Ver Alumno"><i class="far fa-eye"></i></button>';
                    $btnPlan = '<button class="btn btn-dark btn-sm" onClick="fntPlanAlumV(' . $listAlumns[$i]['id'] . ')" title="Ver Plan"><i class="far fa-address-book"></i></button>';
                    $btnAlumCalf = '<button class="btn btn-primary btn-sm" onClick="fntPlanTareasV(' . $listAlumns[$i]['id'] . ')" title="Ver Tareas subidas"><i class="fas fa-graduation-cap"></i></button>';
                }
            } else {
                $listAlumns[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $listAlumns[$i]['id'] . ')" title="Ver Alumno" disabled><i class="far fa-eye"></i></button>';
            }

            $listAlumns[$i]["nombres%"] = $listAlumns[$i]["nombre"] . ' ' . $listAlumns[$i]["apellido"];
            $listAlumns[$i]['options'] = '<div>' . $btnView . ' ' . $btnPlan . ' ' . $btnAlumCalf . '</div>';
        }

        echo json_encode($listAlumns, JSON_UNESCAPED_UNICODE);
    }

    public function getAlumnosRoles() {
        $id = intval($_POST["id"]);
        $rol = strClean($_POST["Rol"]);
        $listAlumns = $this->model->selectAlumnsRols($id, $rol);
        for ($i = 0; $i < count($listAlumns); $i++) {
            $btnView = '';
            $btnPlan = '';
            if ($listAlumns[$i]["status"] == 1) {
                $listAlumns[$i]["status"] = '<span class="badge badge-success">Activo</span>';
                $btnPlan = '<button class="btn btn-dark btn-sm" onClick="fntPlanAlum(' . $listAlumns[$i]['id'] . ')" title="Añadir Plan"><i class="far fa-address-book"></i></button>';
            } else if ($listAlumns[$i]["status"] == 2) {
                $listAlumns[$i]["status"] = '<span class="badge badge-dark">Con Plan</span>';
                $btnPlan = '<button class="btn btn-dark btn-sm" ' . ($rol != ROLPROFE ?
                        'onClick="fntPlanAlumT(' . $listAlumns[$i]['id'] . ')"' : 'onClick="fntPlanAlumU(' . $listAlumns[$i]['id'] . ')"') .
                        'title="Actualizar Plan Alumno"><i class="far fa-address-book"></i></button>';
            } else {
                $listAlumns[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $listAlumns[$i]['id'] . ')" title="Ver Alumno" disabled><i class="far fa-eye"></i></button>';
            }
            $listAlumns[$i]["nombres%"] = $listAlumns[$i]["nombre"] . ' ' . $listAlumns[$i]["apellido"];
            $listAlumns[$i]['options'] = '<div class="text-center">' . $btnPlan . '</div>';
        }
        echo json_encode($listAlumns, JSON_UNESCAPED_UNICODE);
    }

    public function setAlumno() {
        if ($_POST) {
            if (empty($_POST["txtRutAlu"]) || empty($_POST["txtCorreoAlu"]) || empty($_POST["txtNombreAlu"]) || empty($_POST["txtApellidoAlu"]) || empty($_POST["listEspecialidad"]) || empty($_POST["listCurso"]) || empty($_POST["txtTelefono01"])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o mal procesados...');
            } else {
                $idAlumn = intval($_POST["idAlumn"]);
                $txtRutAlu = $_POST["txtRutAlu"];
                $txtCorreoAlu = ucwords(strClean($_POST["txtCorreoAlu"]));
                $txtNombreAlu = ucwords(strClean($_POST["txtNombreAlu"]));
                $txtApellidoAlu = ucwords(strClean($_POST["txtApellidoAlu"]));
                $listEspecialidad = intval($_POST["listEspecialidad"]);
                $listCurso = intval($_POST["listCurso"]);
                $txtTelefono01 = $_POST["txtTelefono01"];
                $txtTelefono02 = $_POST["txtTelefono02"];
                $listProfesors = intval($_POST["listProfesors"]);
                $listGuia = intval($_POST["listGuia"]);
                $IdColegio = intval($_SESSION["userData"]["detalleRol"]["colegio_id"]);
                $txtPass = md5("Practis.");

                $request_alumn = "";

                if ($idAlumn == 0) {
                    $option = 1;
                    $request_alumn = $this->model->insertAlum($txtRutAlu, $txtNombreAlu, $txtApellidoAlu, $listEspecialidad, $listCurso, $txtTelefono01, $txtTelefono02, ROLALU, $txtCorreoAlu, $txtPass, $listProfesors, $listGuia, $IdColegio);
                } else {
                    $option = 2;
                    $request_alumn = $this->model->updateAlumn($txtRutAlu, $txtNombreAlu, $txtApellidoAlu, $listEspecialidad, $listCurso, $txtTelefono01, $txtTelefono02, $txtCorreoAlu, $listProfesors, $listGuia, $idAlumn);
                }

                if ($request_alumn > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Alumno registrado Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Alumno actualizado Exitosamente !!');
                    }
                } else if ($request_alumn == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! este Alumno ya existe...');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getAlumno($idAlumn) {
        $IntIdAlumn = intval($idAlumn);
        if ($IntIdAlumn > 0) {
            $arrData = $this->model->selectAlumno($IntIdAlumn);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getAlumnoP($idAlumn) {
        $IntIdAlumn = intval($idAlumn);
        if ($IntIdAlumn > 0) {
            $arrData = $this->model->selectPlanAlum($IntIdAlumn);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function setStatusAlumn() {
        if ($_POST) {
            $IntIdAlum = intval($_POST["idAlum"]);
            $status = intval($_POST['status']);
            $requestDelete = $this->model->updateStatusAlu($IntIdAlum, $status);
            if ($requestDelete == 'ok') {
                if ($status == 0) {
                    $arrResponse = array('status' => true, 'msg' => "Alumno Inhabilitado Exitosamente...");
                } else {
                    $arrResponse = array('status' => true, 'msg' => "Alumno Habilitado Exitosamente...");
                }
            } else if ($requestDelete == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'No es posible inhabilitar a este Alumno..');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar/activar a este Alumno.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getAlumnosReport() {
        $listAlumns = $this->model->selectAlumns();
        echo json_encode($listAlumns, JSON_UNESCAPED_UNICODE);
    }

    public function getAlumnoReportRol() {
        $listAlumns = $this->model->selectAlumnsRol($_SESSION['userData']['detalleRol']);
        echo json_encode($listAlumns, JSON_UNESCAPED_UNICODE);
    }

    public function getAlumnoReportRolProfe() {
        $listAlumns = $this->model->selectAlumnsReportRol($_SESSION['userData']['detalleRol']);
        echo json_encode($listAlumns, JSON_UNESCAPED_UNICODE);
    }

}
