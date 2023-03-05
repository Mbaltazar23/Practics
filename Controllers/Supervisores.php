<?php

class Supervisores extends Controllers {

    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login']) || !isset($_SESSION["email-personal"])) {
            header('Location: ' . base_url());
            die();
        }
    }

    public function supervisores() {
        if ($_SESSION['cargo-personal'] !== ROLADMIN) {
            header('Location: ' . base_url());
            die();
        }
        $data['page_tag'] = NOMBRE_WEB . "- Supervisores Colegios";
        $data['page_title'] = "Supervisores";
        $data['page_name'] = "supervisores";
        $data['rol-personal'] = $_SESSION['cargo-personal'];
        $data['page_functions_js'] = "functions_supervisors.js";
        $this->views->getView($this, "supervisores", $data);
    }

    public function getSupervisores() {
        $arrData = $this->model->selectSupervisors();
        for ($i = 0; $i < count($arrData); $i++) {
            $btnView = '';
            $btnEdit = '';
            $btnDelete = '';
            $btnSchool = '';
            $arrData[$i]["nro"] = ($i + 1);
            if ($arrData[$i]['status'] == 1) {
                $arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $arrData[$i]['id'] . ')" title="Ver supervisor"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $arrData[$i]['id'] . ')" title="Editar supervisor"><i class="fas fa-pencil-alt"></i></button>';
                $btnSchool = '<button class="btn btn-dark btn-sm" onClick="fntSchoolA(' . $arrData[$i]['id'] . ')" title="Vincular Colegio"><i class="fas fa-school"></i></button>';
                $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $arrData[$i]['id'] . ')" title="Eliminar supervisor"><i class="far fa-trash-alt"></i></button>';
            } else if ($arrData[$i]['status'] == 2) {
                $arrData[$i]['status'] = '<span class="badge badge-dark">Vinculado</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $arrData[$i]['id'] . ')" title="Ver supervisor"><i class="far fa-eye"></i></button>';
                $btnSchool = '<button class="btn btn-dark btn-sm" onClick="fntSchoolU(' . $arrData[$i]['id'] . ')" title="Editar Colegio"><i class="fas fa-school"></i></button>';
                $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,' . $arrData[$i]['id'] . ')" title="Editar supervisor"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelSchool(' . $arrData[$i]['id'] . ')" title="Eliminar Colegio"><i class="far fa-trash-alt"></i></button>';
            } else {
                $arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $arrData[$i]['id'] . ')" title="Ver supervisor" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $arrData[$i]['id'] . ')" title="Editar supervisor" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnSchool = '<button class="btn btn-secondary btn-sm" onClick="fntSchoolA(' . $arrData[$i]['id'] . ')" title="Vincular Colegio" disabled><i class="fas fa-school"></i></button>';
                $btnDelete = '<button class="btn btn-dark btn-sm" onClick="fntActivateInfo(' . $arrData[$i]['id'] . ')" title="Activar supervisor"><i class="fas fa-check"></i></button>';
            }
            $arrData[$i]['options'] = '<div class="text-center">' . $btnView . ' ' . $btnEdit . ' ' . $btnSchool . ' ' . $btnDelete . '</div>';
        }
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function setSupervisor() {
        if ($_POST) {
            if (empty($_POST['txtEmail']) || empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtDni'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                $intIdAdmin = intval($_POST['idSupervisor']);
                $strNombre = ucwords($_POST['txtNombre']);
                $strApellido = ucwords($_POST['txtApellido']);
                $strEmail = ucwords($_POST['txtEmail']);
                $strPassword = md5(ucwords($_POST['txtNombre']) . 1234);
                $strDni = $_POST['txtDni'];
                $strDireccion = isset($_POST["txtDireccion"]) ? strClean(ucfirst($_POST['txtDireccion'])) : "";
                $strRole = ROLADMINCOLE;
                $request_admin = "";
                if ($intIdAdmin == 0) {
                    //Crear
                    $request_admin = $this->model->insertSupervisor($strNombre, $strApellido, $strEmail, $strPassword, $strDni, $strDireccion, $strRole);
                    $option = 1;
                } else {
                    //Actualizar
                    $request_admin = $this->model->updateSupervisor($intIdAdmin, $strNombre, $strApellido, $strEmail, $strPassword, $strDni, $strDireccion);
                    $option = 2;
                }
                if ($request_admin > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Supervisor registrado Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Supervisor actualizado Exitosamente !!');
                    }
                } else if ($request_admin == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! El supervisor ya existe.');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getSupervisor($idadmin) {
        $intIdadmin = intval($idadmin);
        if ($intIdadmin > 0) {
            $arrData = $this->model->selectSupervisor($intIdadmin);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function setStatusSupervisor() {
        if ($_POST) {
            $intIdadmin = intval($_POST['idSupervisor']);
            $status = intval($_POST['status']);
            $requestDelete = $this->model->updateStatusSupervisor($intIdadmin, $status);
            if ($requestDelete == 'ok') {
                if ($status == 0) {
                    $arrResponse = array('status' => true, 'msg' => "Supervisor Inhabilitado Exitosamente...");
                } else {
                    $arrResponse = array('status' => true, 'msg' => "Supervisor Habilitado Exitosamente...");
                }
            } else if ($requestDelete == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'No es posible inhabilitar este supervisor..');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar/activar el supervisor.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function setDetailColegio() {
        if ($_POST) {
            if (empty($_POST['listColegios']) || empty($_POST["txtTelefono"])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                $intIdColegio = intval($_POST['listColegios']);
                $intIdVinCulo = intval($_POST["idVinCol"]);
                $intIdUsuario = intval($_POST["idSupervisorC"]);
                $strTelefono = isset($_POST["txtTelefono"]) ? $_POST["txtTelefono"] : "+569";
                $request_detail_colegio = "";
                if ($intIdVinCulo == 0) {
                    $request_detail_colegio = $this->model->insertDetailSchool($intIdUsuario, $intIdColegio, $strTelefono);
                    $option = 1;
                } else {
                    $request_detail_colegio = $this->model->updateDetailSchool($intIdUsuario, $intIdColegio, $strTelefono, $intIdVinCulo);
                    $option = 2;
                }

                if ($request_detail_colegio > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Colegio vinculado Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Colegio actualizado Exitosamente !!');
                    }
                } else if ($request_detail_colegio == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! El colegio ya existe.');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function removeSchoolSupervisor() {
        if ($_POST) {
            $intIdadmin = intval($_POST['idSupervisor']);
            $requestDelete = $this->model->removeDetailSchool($intIdadmin);
            if ($requestDelete == 'ok') {
                $arrResponse = array('status' => true, 'msg' => "Colegio Removido Exitosamente...");
            } else if ($requestDelete == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'No es posible remover este colegio por ya estar en uso..');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar/activar el supervisor.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getSupervisorReport() {
        $arrData = $this->model->selectSupervisors();
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }

}
