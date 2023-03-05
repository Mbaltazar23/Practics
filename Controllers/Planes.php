<?php

class Planes extends Controllers {

    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login']) || !isset($_SESSION["email-personal"])) {
            header('Location: ' . base_url());
            die();
        }
    }

    public function planes() {
        if ($_SESSION['cargo-personal'] !== ROLADMINCOLE) {
            header('Location: ' . base_url());
            die();
        }
        $data['page_tag'] = NOMBRE_WEB . " - Planes";
        $data['page_title'] = "Planes";
        $data['page_name'] = "planes";
        $data['rol-personal'] = $_SESSION['cargo-personal'];
        $data['page_functions_js'] = "functions_planes.js";
        $this->views->getView($this, "planes", $data);
    }

    public function getPlanes() {
        $listPlanes = $this->model->selectPlanes();
        for ($i = 0; $i < count($listPlanes); $i++) {
            $btnView = '';
            $btnEdit = '';
            $btnTareas = '';
            $btnDelAc = '';
            if ($listPlanes[$i]["status"] == 1) {
                $listPlanes[$i]["status"] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $listPlanes[$i]['id'] . ')" title="Ver Plan"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary btn-sm" onClick="fntEditInfo(this,' . $listPlanes[$i]['id'] . ')" title="Editar Plan"><i class="fas fa-pencil-alt"></i></button>';
                $btnTareas = '<button class="btn btn-dark btn-sm" onClick="fntTareasPlan(this,' . $listPlanes[$i]['id'] . ')" title="Añadir Tareas"><i class="fas fa-book" aria-hidden="true"></i></button>';
                $btnDelAc = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $listPlanes[$i]['id'] . ')" title="Eliminar Plan"><i class="far fa-trash-alt"></i></button>';
            } else if ($listPlanes[$i]["status"] == 2) {
                $listPlanes[$i]["status"] = '<span class="badge badge-dark">En uso</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $listPlanes[$i]['id'] . ')" title="Ver Plan"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary btn-sm" onClick="fntEditInfo(this,' . $listPlanes[$i]['id'] . ')" title="Editar Plan"><i class="fas fa-pencil-alt"></i></button>';
                $btnTareas = '<button class="btn btn-dark btn-sm" onClick="fntTareasPlan(this,' . $listPlanes[$i]['id'] . ')" title="Ver Tareas"><i class="fas fa-book" aria-hidden="true"></i></button>';
                $btnDelAc = '<button class="btn btn-danger btn-sm" title="Plan en Uso"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></button>';
            } else {
                $listPlanes[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $listPlanes[$i]['id'] . ')" title="Ver Plan" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $listPlanes[$i]['id'] . ')" title="Editar Plan" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnTareas = '<button class="btn btn-secondary btn-sm" onClick="fntTareasPlan(this,' . $listPlanes[$i]['id'] . ')" title="Añadir Tareas" disabled><i class="fas fa-book" aria-hidden="true"></i></button>';
                $btnDelAc = '<button class="btn btn-secondary btn-sm" onClick="fntActivateInfo(' . $listPlanes[$i]['id'] . ')" title="Activar Plan"><i class="fas fa-toggle-on"></i></button>';
            }
            $listPlanes[$i]['options'] = '<div class="text-center">' . $btnView . '  ' . $btnEdit . ' ' . $btnTareas . ' ' . $btnDelAc . '</div>';
        }
        echo json_encode($listPlanes, JSON_UNESCAPED_UNICODE);
    }

    public function getSelectPlanes() {
        $htmlOptions = "";
        $arrData = $this->model->selectPlanes(1);
        echo '<option value="0">Seleccione un Plan</option>';
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['nombre'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function setPlan() {
        if ($_POST) {
            if (empty($_POST["txtNombrePlan"])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o mal procesados...');
            } else {
                $idPlan = intval($_POST["idPlan"]);
                $nombrePlan = ucwords(strClean($_POST["txtNombrePlan"]));
                $descripcionPlan = ucfirst(strClean($_POST["txtDescripcionPlan"]));
                $IdColegio = intval($_SESSION["userData"]["detalleRol"]["colegio_id"]);
                $request_plan = "";

                if ($idPlan == 0) {
                    $option = 1;
                    $request_plan = $this->model->insertPlan($nombrePlan, $descripcionPlan, $IdColegio);
                } else {
                    $option = 2;
                    $request_plan = $this->model->updatePlan($nombrePlan, $descripcionPlan, $idPlan);
                }

                if ($request_plan > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Plan registrado Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Plan actualizado Exitosamente !!');
                    }
                } else if ($request_plan == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! este plan ya existe...');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getPlan($idPlan) {
        $IntIdPlan = intval($idPlan);
        if ($IntIdPlan > 0) {
            $arrData = $this->model->selectPlan($IntIdPlan);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getPlanAlu($idAlum) {
        $IntIdAlumn = intval($idAlum);
        if ($idAlum > 0) {
            $arrData = $this->model->selectDetailPlanAlum($IntIdAlumn);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function setStatusPlanes() {
        if ($_POST) {
            $IntIdPlan = intval($_POST["idPlan"]);
            $status = intval($_POST['status']);

            $requestDelete = $this->model->updateStatusPlan($IntIdPlan, $status);

            if ($requestDelete == 'ok') {
                if ($status == 0) {
                    $arrResponse = array('status' => true, 'msg' => "Plan Inhabilitado Exitosamente...");
                } else {
                    $arrResponse = array('status' => true, 'msg' => "Plan Habiitado Exitosamente...");
                }
            } else if ($requestDelete == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'No es posible inhabilitar este plan..');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar/activar este plan.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getPlanesReport() {
        $listPlanes = $this->model->selectPlanes();
        echo json_encode($listPlanes, JSON_UNESCAPED_UNICODE);
    }

    public function setDetailPlan() {
        if ($_POST) {
            if (empty($_POST["idTarea"]) || empty($_POST["idPlan"])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o mal procesados...');
            } else {
                $idTarea = intval($_POST["idTarea"]);
                $idPlan = intval($_POST["idPlan"]);

                $request_detailPlan = $this->model->insertDetailPlan($idPlan, $idTarea);

                if ($request_detailPlan > 0) {
                    $arrResponse = array('status' => true, 'msg' => 'Tarea agregada Exitosamente !!');
                } else if ($request_detailPlan == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! esta tarea ya existe...');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function setDetailPlanAlum() {
        if ($_POST) {
            if (empty($_POST["idAlumnP"]) || empty($_POST["listPlanes"])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o mal procesados...');
            } else {
                $idPlanA = intval($_POST["idPlanA"]);
                $idAlum = intval($_POST["idAlumnP"]);
                $idPlan = intval($_POST["listPlanes"]);
                $txtDescripcionP = strClean(ucwords($_POST["txtDescripcionP"]));
                $request_detailAlu = "";
                if ($idPlanA == 0) {
                    $option = 1;
                    $request_detailAlu = $this->model->insertDetailAlumnPlan($idPlan, $idAlum, $txtDescripcionP);
                } else {
                    $option = 2;
                    $request_detailAlu = $this->model->updateDetailPlanAlum($idPlan, $idAlum, $txtDescripcionP, $idPlanA);
                }

                if ($request_detailAlu > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Alumno vinculado al Plan Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Alumno con Plan Actualizado Exitosamente !!');
                    }
                } else if ($request_detailAlu == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! este alumno ya vinculado...');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function deleteDetailPlan() {
        if ($_POST) {
            $IntIdPlan = intval($_POST["idPlan"]);
            $IntIdTarea = intval($_POST['idTarea']);
            $requestDelete = $this->model->deleteDetailTarea($IntIdPlan, $IntIdTarea);
            if ($requestDelete == 'ok') {
                $arrResponse = array('status' => true, 'msg' => "Tarea Eliminada Exitosamente...");
            } else if ($requestDelete == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'Esta tarea ya fue ocupada..');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar/activar esta tarea.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function setStatusDetailTarea() {
        if ($_POST) {
            $IntIdPlan = intval($_POST["idPlan"]);
            $IntIdTarea = intval($_POST['idTarea']);
            $status = intval($_POST["status"]);
            $requestDelete = $this->model->updateStatusDetailTarea($IntIdPlan, $IntIdTarea, $status);
            if ($requestDelete == 'ok') {
                if ($status == 2) {
                    $arrResponse = array('status' => true, 'msg' => "Tarea Visible Exitosamente...");
                } else {
                    $arrResponse = array('status' => true, 'msg' => "Tarea Oculta Exitosamente...");
                }
            } else if ($requestDelete == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'Esta tarea ya fue ocupada..');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar/activar esta tarea.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

}
