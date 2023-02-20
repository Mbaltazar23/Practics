<?php

/**
 * Description of Tutores
 *
 * @author mario
 */
class Tutores extends Controllers {

    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login']) || !isset($_SESSION["email-personal"])) {
            header('Location: ' . base_url());
            die();
        }
    }

    public function tutores() {
        $data['page_tag'] = NOMBRE_WEB . "- Tutores";
        $data['page_title'] = "Tutores";
        $data['page_name'] = "tutores";
        $data['rol-personal'] = $_SESSION['cargo-personal'];
        $data['page_functions_js'] = "functions_tutors.js";
        $this->views->getView($this, "tutores", $data);
    }

    public function getTutores() {
        $listTutores = $this->model->selectTutors();
        for ($i = 0; $i < count($listTutores); $i++) {
            $btnView = '';
            $btnEdit = '';
            $btnDelAc = '';
            $btnAlumns = '';
            if ($listTutores[$i]["status"] == 1) {
                $listTutores[$i]["status"] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $listTutores[$i]['id'] . ')" title="Ver Tutor"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary btn-sm" onClick="fntEditInfo(this,' . $listTutores[$i]['id'] . ')" title="Editar Tutor"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelAc = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $listTutores[$i]['id'] . ')" title="Eliminar Tutor"><i class="far fa-trash-alt"></i></button>';
                $btnAlumns = '<button class="btn btn-dark btn-sm" onClick="fntViewAlumns(' . $listTutores[$i]['id'] . ')" title="Ver Alumnos Tutor"><i class="far fa-address-book"></i></button>';
            } else {
                $listTutores[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $listTutores[$i]['id'] . ')" title="Ver Tutor" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $listTutores[$i]['id'] . ')" title="Editar Tutor" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnAlumns = '<button class="btn btn-secondary btn-sm" onClick="fntViewAlumns(' . $listTutores[$i]['id'] . ')" title="Ver Alumnos Tutor" disabled><i class="far fa-address-book"></i></button>';
                $btnDelAc = '<button class="btn btn-secondary btn-sm" onClick="fntActivateInfo(' . $listTutores[$i]['id'] . ')" title="Activar Tutor"><i class="fas fa-toggle-on"></i></button>';
            }
            $listTutores[$i]["nombres%"] = $listTutores[$i]["nombre"] . ' ' . $listTutores[$i]["apellido"];
            $listTutores[$i]['options'] = '<div class="text-center">' . $btnView . '  ' . $btnEdit . ' ' . $btnAlumns . ' ' . $btnDelAc . '</div>';
        }
        echo json_encode($listTutores, JSON_UNESCAPED_UNICODE);
    }

    public function getSelectTutors() {
        $htmlOptions = "";
        $arrData = $this->model->selectTutors(1);
        echo '<option value="0">Seleccione un Profesor</option>';
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['idtutor'] . '">' . $arrData[$i]['nombre'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function setTutor() {
        if ($_POST) {
            if (empty($_POST["txtRutT"]) || empty($_POST["txtNombreT"]) || empty($_POST["txtApellidoT"]) || empty($_POST["txtCorreoT"] || empty($_POST["txtTelefonoT"]))) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o mal procesados...');
            } else {
                $idTutor = intval($_POST["idTutor"]);
                $txtRut = $_POST["txtRutT"];
                $txtNombre = ucwords(strClean($_POST["txtNombreT"]));
                $txtApellido = ucwords(strClean($_POST["txtApellidoT"]));
                $txtCorreo = ucwords(strClean($_POST["txtCorreoT"]));
                $txtTelefono = $_POST["txtTelefonoT"];
                $IdColegio = intval($_SESSION["userData"]["detalleRol"]["colegio_id"]);
                $txtPass = md5($txtNombre);
                $request_tutor = "";

                if ($idTutor == 0) {
                    $option = 1;
                    $request_tutor = $this->model->insertTutor($txtRut, $txtNombre, $txtApellido, $txtCorreo, $txtPass, ROLPROFE, $txtTelefono, $IdColegio);
                } else {
                    $option = 2;
                    $request_tutor = $this->model->updateTutor($txtRut, $txtNombre, $txtApellido, $txtCorreo, $txtTelefono, $idTutor);
                }

                if ($request_tutor > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Profesor registrado Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Profesor actualizado Exitosamente !!');
                    }
                } else if ($request_tutor == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! este Profesor ya existe...');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getTutor($idTutor) {
        $IntIdTutor = intval($idTutor);
        if ($IntIdTutor > 0) {
            $arrData = $this->model->selectTutor($IntIdTutor);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function setStatusTutor() {
        if ($_POST) {
            $IntIdTutor = intval($_POST["idTutor"]);
            $status = intval($_POST['status']);
            $requestDelete = $this->model->updateStatusTutor($IntIdTutor, $status);
            if ($requestDelete == 'ok') {
                if ($status == 0) {
                    $arrResponse = array('status' => true, 'msg' => "Profesor Inhabilitado Exitosamente...");
                } else {
                    $arrResponse = array('status' => true, 'msg' => "Profesor Habilitado Exitosamente...");
                }
            } else if ($requestDelete == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'No es posible inhabilitar a este profesor..');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar/activar a este profesor.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getTutorsReport() {
        $listTutores = $this->model->selectTutors();
        echo json_encode($listTutores, JSON_UNESCAPED_UNICODE);
    }

}
