<?php

/**
 * @author mario
 */
class Guias extends Controllers {

    //put your code here
    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login']) || !isset($_SESSION["email-personal"])) {
            header('Location: ' . base_url());
            die();
        }
    }

    public function guias() {
        if ($_SESSION['cargo-personal'] !== ROLADMINCOLE) {
            header('Location: ' . base_url());
            die();
        }
        $data['page_tag'] = NOMBRE_WEB . "- Guias";
        $data['page_title'] = "Guias";
        $data['page_name'] = "guias";
        $data['rol-personal'] = $_SESSION['cargo-personal'];
        $data['page_functions_js'] = "functions_guias.js";
        $this->views->getView($this, "guias", $data);
    }

    public function getGuias() {
        $listGuias = $this->model->selectGuias();
        for ($i = 0; $i < count($listGuias); $i++) {
            $btnView = '';
            $btnEdit = '';
            $btnDelAc = '';
            $btnAlumns = '';
            if ($listGuias[$i]["status"] == 1) {
                $listGuias[$i]["status"] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $listGuias[$i]['id'] . ')" title="Ver Guia"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary btn-sm" onClick="fntEditInfo(this,' . $listGuias[$i]['id'] . ')" title="Editar Guia"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelAc = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $listGuias[$i]['id'] . ')" title="Eliminar Guia"><i class="far fa-trash-alt"></i></button>';
                $btnAlumns = '<button class="btn btn-dark btn-sm" onClick="fntViewAlumns(' . $listGuias[$i]['id'] . ')" title="Ver Alumnos Guia"><i class="far fa-address-book"></i></button>';
            } else {
                $listGuias[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $listGuias[$i]['id'] . ')" title="Ver Guia" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $listGuias[$i]['id'] . ')" title="Editar Guia" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnAlumns = '<button class="btn btn-secondary btn-sm" onClick="fntViewAlumns(' . $listGuias[$i]['id'] . ')" title="Ver Alumnos Guia" disabled><i class="far fa-address-book"></i></button>';
                $btnDelAc = '<button class="btn btn-secondary btn-sm" onClick="fntActivateInfo(' . $listGuias[$i]['id'] . ')" title="Activar Guia"><i class="fas fa-toggle-on"></i></button>';
            }
            $listGuias[$i]["nombres%"] = $listGuias[$i]["nombre"] . ' ' . $listGuias[$i]["apellido"];
            $listGuias[$i]['options'] = '<div class="text-center">' . $btnView . '  ' . $btnEdit . ' ' . $btnAlumns . ' ' . $btnDelAc . '</div>';
        }
        echo json_encode($listGuias, JSON_UNESCAPED_UNICODE);
    }

    public function getSelectGuias() {
        $htmlOptions = "";
        $arrData = $this->model->selectGuias(1);
        echo '<option value="0">Seleccione un Guia</option>';
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['idguia'] . '">' . $arrData[$i]['nombre'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function setGuia() {
        if ($_POST) {
            if (empty($_POST["txtRutG"]) || empty($_POST["txtNombreG"]) || empty($_POST["txtApellidoG"]) || empty($_POST["txtCorreoG"] || empty($_POST["txtTelefonoG"]) || empty($_POST["listEmpresas"]))) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o mal procesados...');
            } else {
                $idGuia = intval($_POST["idGuia"]);
                $txtRut = $_POST["txtRutG"];
                $txtNombre = ucwords(strClean($_POST["txtNombreG"]));
                $txtApellido = ucwords(strClean($_POST["txtApellidoG"]));
                $txtCorreo = ucwords(strClean($_POST["txtCorreoG"]));
                $txtTelefono = $_POST["txtTelefonoG"];
                $listEmpresas = intval($_POST["listEmpresas"]);
                $Ocupacion = ucfirst(strClean($_POST["txtOcupacionG"]));
                $txtPass = md5(ucwords($txtNombre));
                $IdColegio = intval($_SESSION["userData"]["detalleRol"]["colegio_id"]);

                $request_guia = "";

                if ($idGuia == 0) {
                    $option = 1;
                    $request_guia = $this->model->insertGuia($txtRut, $txtNombre, $txtApellido, $txtCorreo, $txtPass, ROLGUIA, $txtTelefono, $Ocupacion, $listEmpresas, $IdColegio);
                } else {
                    $option = 2;
                    $request_guia = $this->model->updateGuia($txtRut, $txtNombre, $txtApellido, $txtCorreo, $txtTelefono, $Ocupacion, $listEmpresas, $idGuia);
                }

                if ($request_guia > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Guia registrado Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Guia actualizado Exitosamente !!');
                    }
                } else if ($request_guia == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! este Guia ya existe...');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getGuia($idGuia) {
        $IntIdGuia = intval($idGuia);
        if ($IntIdGuia > 0) {
            $arrData = $this->model->selectGuia($IntIdGuia);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function setStatusGuia() {
        if ($_POST) {
            $IntIdGuia = intval($_POST["idGuia"]);
            $status = intval($_POST['status']);
            $requestDelete = $this->model->updateStatusGuia($IntIdGuia, $status);
            if ($requestDelete == 'ok') {
                if ($status == 0) {
                    $arrResponse = array('status' => true, 'msg' => "Guia Inhabilitado Exitosamente...");
                } else {
                    $arrResponse = array('status' => true, 'msg' => "Guia Habilitado Exitosamente...");
                }
            } else if ($requestDelete == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'No es posible inhabilitar a este Guia..');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar/activar a este Guia.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getGuiasReport() {
        $listGuias = $this->model->selectGuias();
        echo json_encode($listGuias, JSON_UNESCAPED_UNICODE);
    }

}
