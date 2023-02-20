<?php

class Empresas extends Controllers {

    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login']) || !isset($_SESSION["email-personal"])) {
            header('Location: ' . base_url());
            die();
        }
    }

    public function Empresas() {
        $data['page_tag'] = NOMBRE_WEB . "- Empresas";
        $data['page_title'] = "Empresas";
        $data['page_name'] = "empresas";
        $data['rol-personal'] = $_SESSION['cargo-personal'];
        $data['page_functions_js'] = "functions_empresa.js";
        $this->views->getView($this, "empresas", $data);
    }

    public function getSelectEmpresas() {
        $htmlOptions = "";
        $arrData = $this->model->selectEmpresas(1);
        echo '<option value="0">Seleccione una Empresa</option>';
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['nombre'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function getEmpresas() {
        $listEmpresas = $this->model->selectEmpresas();
        for ($i = 0; $i < count($listEmpresas); $i++) {
            $btnDis = '';
            $listPersons = $this->model->getPersonsEmpresa($listEmpresas[$i]["id"]);
            if (count($listPersons) <= 0) {
                $btnDis = "disabled";
            }
            $btnView = "";
            $btnEdit = "";
            $btnDelAct = "";
            $btnlistPersons = "";
            if ($listEmpresas[$i]["status"] == 1) {
                $listEmpresas[$i]['status'] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $listEmpresas[$i]['id'] . ')" title="Ver empresa"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $listEmpresas[$i]['id'] . ')" title="Editar empresa"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelAct = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $listEmpresas[$i]['id'] . ')" title="Eliminar empresa"><i class="far fa-trash-alt"></i></button>';
                $btnlistPersons = '<button class="btn btn-dark btn-sm" onClick="getPersons(' . $listEmpresas[$i]['id'] . ')" title="Ver contactos afiliados" ' . $btnDis . '><i class="far fa-building"></i></button>';
            } else {
                $listEmpresas[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $listEmpresas[$i]['id'] . ')" title="Ver empresa" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $listEmpresas[$i]['id'] . ')" title="Editar empresa" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnDelAct = '<button class="btn btn-dark btn-sm" onClick="fntActivateInfo(' . $listEmpresas[$i]['id'] . ')" title="Activar empresa"><i class="fas fa-toggle-on"></i></button>';
                $btnlistPersons = '<button class="btn btn-secondary btn-sm" onClick="getPersons(' . $listEmpresas[$i]['id'] . ')" title="Ver contactos afiliados" disabled><i class="far fa-building"></i></button>';
            }

            $listEmpresas[$i]['options'] = '<div class="text-center">' . $btnView . '  ' . $btnEdit . '  ' . $btnlistPersons . ' ' . $btnDelAct . '</div>';
        }
        echo json_encode($listEmpresas, JSON_UNESCAPED_UNICODE);
    }

    public function setEmpresa() {
        if ($_POST) {
            if (empty($_POST["txtNombreEmpresa"]) || empty($_POST["txtRutEmpresa"]) || empty($_POST["txtOcupacion"])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o mal procesados...');
            } else {
                $idEmpresa = intval($_POST["idEmpresa"]);
                $txtNombreEmpresa = ucwords(strClean($_POST["txtNombreEmpresa"]));
                $txtRutEmpresa = $_POST["txtRutEmpresa"];
                $ocupacion = ucfirst(strClean($_POST["txtOcupacion"]));
                $IdColegio = intval($_SESSION["userData"]["detalleRol"]["colegio_id"]);
                $request_empresa = "";

                if ($idEmpresa == 0) {
                    $option = 1;
                    $request_empresa = $this->model->insertEmpresa($txtNombreEmpresa, $txtRutEmpresa, $ocupacion,$IdColegio);
                } else {
                    $option = 2;
                    $request_empresa = $this->model->updateEmpresa($txtNombreEmpresa, $txtRutEmpresa, $ocupacion, $idEmpresa);
                }

                if ($request_empresa > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Empresa registrada Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Empresa actualizada Exitosamente !!');
                    }
                } else if ($request_empresa == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! esta empresa ya existe...');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getEmpresa($idEmpresa) {
        $IntidEmpresa = intval($idEmpresa);
        if ($IntidEmpresa > 0) {
            $arrData = $this->model->selectEmpresa($IntidEmpresa);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getPersonsEmpresa($idEmpresa) {
        $intIdEmpresa = intval($idEmpresa);
        $listPersons = $this->model->getPersonsEmpresa($intIdEmpresa);

        echo json_encode($listPersons, JSON_UNESCAPED_UNICODE);
    }

    public function setStatusEmpresa() {
        if ($_POST) {
            $IntIdEmpresa = intval($_POST["idEmpresa"]);
            $status = intval($_POST['status']);
            $requestDelete = $this->model->updateStatusEmpresa($IntIdEmpresa, $status);
            if ($requestDelete == 'ok') {
                if ($status == 0) {
                    $arrResponse = array('status' => true, 'msg' => "Empresa Inhabilitada Exitosamente...");
                } else {
                    $arrResponse = array('status' => true, 'msg' => "Empresa Habiitada Exitosamente...");
                }
            } else if ($requestDelete == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'No es posible inhabilitar esta empresa..');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar/activar a esta empresa.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getEmpresasReport() {
        $listEmpresas = $this->model->selectEmpresas();
        for ($i = 0; $i < count($listEmpresas); $i++) {
            $listPersons = $this->model->getPersonsEmpresa($listEmpresas[$i]["id"]);
            if (count($listPersons) > 0) {
                for ($j = 0; $j < count($listPersons); $j++) {
                    $listEmpresas[$i]["listPersons"] = $listPersons[$j]["nombre"] . ", \n";
                }
            } else {
                $listEmpresas[$i]["listPersons"] = "No hay guias afiliados";
            }
        }
        echo json_encode($listEmpresas, JSON_UNESCAPED_UNICODE);
    }

}
