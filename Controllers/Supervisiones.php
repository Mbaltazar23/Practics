<?php

/**
 * Description of Supervisiones
 *
 * @author mario
 */
class Supervisiones extends Controllers {

    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login']) || !isset($_SESSION["email-personal"])) {
            header('Location: ' . base_url());
            die();
        }
    }

    public function supervisiones() {
        if ($_SESSION['cargo-personal'] !== ROLPROFE) {
            header('Location: ' . base_url());
            die();
        }
        $data['page_tag'] = NOMBRE_WEB . "- Supervisiónes del Profesor " . $_SESSION["userData"]["nombre"];
        $data['page_title'] = "Supervisiónes";
        $data['page_name'] = "supervisiónes";
        $data['rol-personal'] = $_SESSION['cargo-personal'];
        $data['page_functions_js'] = "functions_supervicions.js";
        $this->views->getView($this, "supervisiones", $data);
    }

    public function supervisionesAd() {
        if ($_SESSION['cargo-personal'] !== ROLADMINCOLE) {
            header('Location: ' . base_url());
            die();
        }
        $data['page_tag'] = NOMBRE_WEB . " - Supervisiónes";
        $data['page_title'] = "Supervisiónes";
        $data['page_name'] = "supervisiónes";
        $data['rol-personal'] = $_SESSION['cargo-personal'];
        $data['page_functions_js'] = "functions_supervicionsAd.js";
        $this->views->getView($this, "supervisionesAd", $data);
    }

    public function getSupervicions() {
        $listSupervicions = $this->model->selectSupervisions($_SESSION["userData"]["detalleRol"]["id"]);
        for ($i = 0; $i < count($listSupervicions); $i++) {
            $btnView = '';
            $btnEdit = '';
            $btnDelAc = '';
            $listSupervicions[$i]["nro"] = $i + 1;
            if ($listSupervicions[$i]["status"] == 1) {
                $listSupervicions[$i]["status"] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . ($i + 1) . ',' . $listSupervicions[$i]['id'] . ')" title="Ver Supervicion"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary btn-sm" onClick="fntEditInfo(this,' . $listSupervicions[$i]['id'] . ')" title="Editar Supervicion"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelAc = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $listSupervicions[$i]['id'] . ')" title="Eliminar Supervicion "><i class="far fa-trash-alt"></i></button>';
            } else {
                $listSupervicions[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $listSupervicions[$i]['id'] . ')" title="Ver Supervicion" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $listSupervicions[$i]['id'] . ')" title="Editar Supervicion" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnDelAc = '<button class="btn btn-secondary btn-sm" onClick="fntActivateInfo(' . $listSupervicions[$i]['id'] . ')" title="Activar Supervicion"><i class="fas fa-toggle-on"></i></button>';
            }

            $listSupervicions[$i]['options'] = '<div class="text-center">' . $btnView . '  ' . $btnEdit . ' ' . $btnDelAc . '</div>';
        }
        echo json_encode($listSupervicions, JSON_UNESCAPED_UNICODE);
    }

    public function getSupervisionsAd() {
        $listSupervicions = $this->model->selectSupervisions();
        for ($i = 0; $i < count($listSupervicions); $i++) {
            $listSupervicions[$i]["nro"] = $i + 1;

            $listSupervicions[$i]["status"] = '<span class="badge badge-success">Activo</span>';

            $listSupervicions[$i]["nombres"] = $listSupervicions[$i]["nombre"] . ' ' . $listSupervicions[$i]["apellido"];
            $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewSus(' . ($i + 1) . ',' . $listSupervicions[$i]['id'] . ')" title="Ver Supervicion"><i class="far fa-eye"></i></button>';

            $listSupervicions[$i]['options'] = '<div class="text-center">' . $btnView . '</div>';
        }
        echo json_encode($listSupervicions, JSON_UNESCAPED_UNICODE);
    }

    public function setSupervision() {
        if ($_POST) {
            if (empty($_POST["txtSupervicion"]) || empty($_POST["txtFecha"])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o mal procesados...');
            } else {
                $idSupervici = intval($_POST["idSupervision"]);
                $strSupervicion = ucwords(strClean($_POST["txtSupervicion"]));
                $txtFecha = $_POST["txtFecha"];
                $IdProfe = intval($_SESSION["userData"]["detalleRol"]["id"]);
                $request_supervicions = "";

                if ($idSupervici == 0) {
                    $option = 1;
                    $request_supervicions = $this->model->insertSupervision($IdProfe, $strSupervicion, $txtFecha);
                } else {
                    $option = 2;
                    $request_supervicions = $this->model->updateSupervision($idSupervici, $strSupervicion, $txtFecha);
                }

                if ($request_supervicions > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Supervicion registrada Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Supervicion actualizada Exitosamente !!');
                    }
                } else if ($request_supervicions == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => 'La Supervicion ya existe');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible agregar los datos');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getSupervision($idSupervicion) {
        $IntIdSupervicion = intval($idSupervicion);
        if ($IntIdSupervicion > 0) {
            $arrData = $this->model->selectSupervision($IntIdSupervicion);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function setStatusSupervicion() {
        if ($_POST) {
            $IntIdSupervision = intval($_POST["idSupervision"]);
            $status = intval($_POST['status']);
            $requestDelete = $this->model->updateStatusSupervision($IntIdSupervision, $status);
            if ($requestDelete == 'ok') {
                if ($status == 0) {
                    $arrResponse = array('status' => true, 'msg' => "Supervisión Inhabilitada Exitosamente !!");
                } else {
                    $arrResponse = array('status' => true, 'msg' => "Supervisión Habiitada Exitosamente !!");
                }
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al activar el curso .');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getReportSupervicions() {
        $listSupervicions = $this->model->selectSupervisions();
        echo json_encode($listSupervicions, JSON_UNESCAPED_UNICODE);
    }

}
