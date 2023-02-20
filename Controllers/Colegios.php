<?php

/**
 * Description of Colegios
 *
 * @author mario
 */
class Colegios extends Controllers {

    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login']) || !isset($_SESSION["email-personal"])) {
            header('Location: ' . base_url());
            die();
        }
    }

    public function colegios() {
        $data['page_tag'] = NOMBRE_WEB . "- Colegios";
        $data['page_title'] = "Colegios";
        $data['page_name'] = "colegios";
        $data['rol-personal'] = $_SESSION['cargo-personal'];
        $data['page_functions_js'] = "functions_colegios.js";
        $this->views->getView($this, "colegios", $data);
    }

    public function getColegios() {
        $arrData = $this->model->selectColegios();
        for ($i = 0; $i < count($arrData); $i++) {
            $btnView = '';
            $btnEdit = '';
            $btnDelete = '';
            $arrData[$i]["nro"] = ($i + 1);
            if ($arrData[$i]['status'] == 1) {
                $arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $arrData[$i]['id'] . ')" title="Ver colegio"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $arrData[$i]['id'] . ')" title="Editar colegio"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $arrData[$i]['id'] . ')" title="Eliminar colegio"><i class="far fa-trash-alt"></i></button>';
            } else {
                $arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $arrData[$i]['id'] . ')" title="Ver colegio" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $arrData[$i]['id'] . ')" title="Editar colegio" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button class="btn btn-dark btn-sm" onClick="fntActivateInfo(' . $arrData[$i]['id'] . ')" title="Activar colegio"><i class="fas fa-toggle-on"></i></button>';
            }
            $arrData[$i]['options'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnDelete . '</div>';
        }
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

    public function setColegio() {
        if ($_POST) {
            if (empty($_POST['txtNombre']) || empty($_POST['txtRut']) || empty($_POST['txtTelefono'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                $intIdColegio = intval($_POST['idColegio']);
                $strRut = $_POST['txtRut'];
                $strNombre = ucwords(strClean($_POST['txtNombre']));
                $strDireccion = ucwords(strClean($_POST['txtDireccion']));
                $strTelefono = $_POST['txtTelefono'];
                $request_colegio = "";
                if ($intIdColegio == 0) {
                    //Crear
                    $request_colegio = $this->model->insertColegio($strNombre, $strRut, $strDireccion, $strTelefono);
                    $option = 1;
                } else {
                    //Actualizar
                    $request_colegio = $this->model->updateColegio($intIdColegio, $strNombre, $strRut, $strDireccion, $strTelefono);
                    $option = 2;
                }
                if ($request_colegio > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Colegio registrado Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Colegio actualizado Exitosamente !!');
                    }
                } else if ($request_colegio == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! El colegio ya existe.');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getColegio($idColegio) {
        $intIdColegio = intval($idColegio);
        if ($intIdColegio > 0) {
            $arrData = $this->model->selectColegio($intIdColegio);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getSelectColegios() {
        $htmlOptions = "";
        $arrData = $this->model->selectColegios(1);
        echo '<option value="0">Seleccione una colegio</option>';
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                if ($arrData[$i]['status'] == 1) {
                    $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['nombre'] . '</option>';
                }
            }
        }
        echo $htmlOptions;
        die();
    }

    public function setStatusColegio() {
        if ($_POST) {
            $intIdColegio = intval($_POST['idColegio']);
            $status = intval($_POST['status']);
            $requestDelete = $this->model->updateStatusColegio($intIdColegio, $status);
            if ($requestDelete == 'ok') {
                if ($status == 0) {
                    $arrResponse = array('status' => true, 'msg' => "Colegio Inhabilitado Exitosamente...");
                } else {
                    $arrResponse = array('status' => true, 'msg' => "Colegio Habiitado Exitosamente...");
                }
            } else if ($requestDelete == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'No es posible inhabilitar este colegio..');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar/activar el colegio.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getColegiosReport() {
        $listColegios = $this->model->selectColegios();
        echo json_encode($listColegios, JSON_UNESCAPED_UNICODE);
    }

}
