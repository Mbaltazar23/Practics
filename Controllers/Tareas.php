<?php

class Tareas extends Controllers {

    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login']) || !isset($_SESSION["email-personal"])) {
            header('Location: ' . base_url());
            die();
        }
    }

    public function tareas() {
        if ($_SESSION["cargo-personal"] != ROLADMINCOLE) {
            header('Location: ' . base_url() . '/');
            die();
        }
        $data['page_tag'] = NOMBRE_WEB . "- Tareas";
        $data['page_title'] = "Tareas";
        $data['page_name'] = "tareas";
        $data['Tarea-personal'] = $_SESSION['cargo-personal'];
        $data['page_functions_js'] = "functions_tareas.js";
        $this->views->getView($this, "tareas", $data);
    }

    public function tareasalu() {
        if ($_SESSION["cargo-personal"] == ROLADMINCOLE) {
            header('Location: ' . base_url() . '/tareas');
            die();
        }
        $data['page_tag'] = NOMBRE_WEB . "- Tareas del Alumno " . $_SESSION['userData']["nombre"] . "con el ".$_SESSION["userData"]["detalleRol"]["nombrePlan"];
        $data['page_title'] = "Alumno " . $_SESSION['userData']["nombre"] . " y su ".$_SESSION["userData"]["detalleRol"]["nombrePlan"];
        $data['page_name'] = "tareas";
        $data['Tarea-personal'] = $_SESSION['cargo-personal'];
        $data['page_functions_js'] = "functions_tareasAlu.js";
        $this->views->getView($this, "tareasalu", $data);
    }

    public function getSelectTareas() {
        $htmlOptions = "";
        $arrData = $this->model->selectTareas(1);
        echo '<option value="0">Seleccione una Tarea</option>';
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['nombre'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function getTareas() {
        $listTareas = $this->model->selectTareas();
        for ($i = 0; $i < count($listTareas); $i++) {
            $listTareas[$i]['nro'] = $i + 1;
            $btnView = '';
            $btnEdit = '';
            $btnDelAc = '';
            if ($listTareas[$i]["status"] == 1) {
                $listTareas[$i]["status"] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $listTareas[$i]['nro'] . ',' . $listTareas[$i]['id'] . ')" title="Ver Tarea"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary btn-sm" onClick="fntEditInfo(this,' . $listTareas[$i]['id'] . ')" title="Editar Tarea"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelAc = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $listTareas[$i]['id'] . ')" title="Eliminar Tarea"><i class="far fa-trash-alt"></i></button>';
            } else {
                $listTareas[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $listTareas[$i]['nro'] . ',' . $listTareas[$i]['id'] . ')" title="Ver Tarea" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary btn-sm" onClick="fntEditInfo(this,' . $listTareas[$i]['id'] . ')" title="Editar Tarea" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnDelAc = '<button class="btn btn-secondary btn-sm" onClick="fntActivateInfo(' . $listTareas[$i]['id'] . ')" title="Activar Tarea"><i class="fas fa-toggle-on"></i></button>';
            }
            $listTareas[$i]['options'] = '<div class="text-center">' . $btnView . '  ' . $btnEdit . ' ' . $btnDelAc . '</div>';
        }
        echo json_encode($listTareas, JSON_UNESCAPED_UNICODE);
    }

    public function getTareasAlu() {
        $listTareas = $this->model->selectTareasAlu($_SESSION["userData"]["detalleRol"]["id"]);
        for ($i = 0; $i < count($listTareas); $i++) {
            $listTareas[$i]["nro"] = $i + 1;
            $btnView = '';
            $btnBic = '';
            $btnDel = '';
            if ($listTareas[$i]["status"] == 2) {
                $listTareas[$i]["status"] = '<span class="badge badge-success">Activa</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $listTareas[$i]['nro'] . ',' . $listTareas[$i]['id'] . ')" title="Ver Tarea"><i class="far fa-eye"></i></button>';
                $btnBic = '<button class="btn btn-dark btn-sm" onClick="fntBicA(' . ($i + 1) . ',' . $listTareas[$i]['id'] . ')" title="Subir Bitacora"><i class="fas fa-solid fa-upload"></i></button>';
                $btnDel = '<button class="btn btn-secondary btn-sm" title="Remover Bitacora" disabled><i class="fas fa-solid fa-trash"></i></button>';
            } else {
                $listTareas[$i]['status'] = '<span class="badge badge-dark">Subida</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . $listTareas[$i]['nro'] . ',' . $listTareas[$i]['id'] . ')" title="Ver Tarea"><i class="far fa-eye"></i></button>';
                $btnBic = '<button class="btn btn-dark btn-sm" onClick="fntBicUp(' . ($i + 1) . ',' . $listTareas[$i]['id'] . ')" title="Actualizar Bitacora"><i class="fas fa-solid fa-upload"></i></button>';
                $btnDel = '<button class="btn btn-danger btn-sm" onClick="fntDelTarea(' . $listTareas[$i]['id'] . ')" title="Remover Bitacora"><i class="fas fa-solid fa-trash"></i></button>';
            }
            $listTareas[$i]['options'] = '<div class="text-center">' . $btnView . ' ' . $btnBic . ' ' . $btnDel . '</div>';
        }
        echo json_encode($listTareas, JSON_UNESCAPED_UNICODE);
    }

    public function getTareasPlan($idPlan) {
        $listTareas = $this->model->selectTareasPlan($idPlan);
        for ($i = 0; $i < count($listTareas); $i++) {
            $btnDelAc = '';
            $disabledBTN = ($_SESSION["cargo-personal"] == ROLADMINCOLE && $listTareas[$i]["status"] == 1) ? "" : "disabled";
            if ($_SESSION["cargo-personal"] == ROLGUIA) {
                if ($listTareas[$i]["status"] == 1) {
                    $listTareas[$i]["status"] = '<span class="badge badge-dark">OFF</span>';

                    $btnDelAc = '<button class="btn btn-primary btn-sm" onClick="fntActiveTarea(' . $listTareas[$i]['id'] . ')" title="Visualizar Tarea"><i class="fas fa-toggle-on"></i></button>';
                } else {
                    $listTareas[$i]["status"] = '<span class="badge badge-danger">ON</span>';

                    $btnDelAc = '<button class="btn btn-danger btn-sm" onClick="fntHideTarea(' . $listTareas[$i]['id'] . ')" title="Ocultar Tarea"><i class="fas fa-minus-circle"></i></button>';
                }
            } else {
                $btnDelAc = $_SESSION["cargo-personal"] != ROLADMINCOLE || $_SESSION["cargo-personal"] != ROLPROFE ?
                        '<button class="btn btn-danger btn-sm" onClick="fntDelTarea(' . $listTareas[$i]['id'] . ')" title="Eliminar Tarea" ' . $disabledBTN . '><i class="far fa-trash-alt"></i></button>' :
                        '<button class="btn btn-danger btn-sm" title="Tarea Visible" disabled><i class="fas fa-ban"></i></button>';
                if ($listTareas[$i]["status"] == 1) {
                    $listTareas[$i]["status"] = '<span class="badge badge-success">En Espera</span>';
                } else {
                    $listTareas[$i]["status"] = '<span class="badge badge-dark">Activa</span>';
                }
            }
            $listTareas[$i]['options'] = '<div class="text-center">' . $btnDelAc . '</div>';
            $listTareas[$i]["nro"] = $i + 1;
        }
        echo json_encode($listTareas, JSON_UNESCAPED_UNICODE);
    }

    public function getTareasPlanActive($idPlan) {
        $listTareas = $this->model->selectTareasPlanActiv($idPlan);
        for ($i = 0; $i < count($listTareas); $i++) {
            $listTareas[$i]["nro"] = $i + 1;
            if ($listTareas[$i]["status"] == 2) {
                $listTareas[$i]["status"] = '<span class="badge badge-success">Activa</span>';
            } else {
                $listTareas[$i]["status"] = '<span class="badge badge-dark">Enviada</span>';
            }
        }
        echo json_encode($listTareas, JSON_UNESCAPED_UNICODE);
    }

    public function setTarea() {
        if ($_POST) {
            if (empty($_POST["txtNombreTarea"])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o mal procesados...');
            } else {
                $idTarea = intval($_POST["idTarea"]);
                $nombreT = ucwords(strClean($_POST["txtNombreTarea"]));
                $IdColegio = intval($_SESSION["userData"]["detalleRol"]["colegio_id"]);
                $request_tarea = "";

                if ($idTarea == 0) {
                    $option = 1;
                    $request_tarea = $this->model->insertTarea($nombreT, $IdColegio);
                } else {
                    $option = 2;
                    $request_tarea = $this->model->updateTarea($nombreT, $idTarea);
                }

                if ($request_tarea > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Tarea registrado Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Tarea actualizado Exitosamente !!');
                    }
                } else if ($request_tarea == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! este tarea ya existe...');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getTarea($idTarea) {
        $IntIdTarea = intval($idTarea);
        if ($IntIdTarea > 0) {
            $arrData = $this->model->selectTarea($IntIdTarea);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function setStatusTareas() {
        if ($_POST) {
            $IntIdTarea = intval($_POST["idTarea"]);
            $status = intval($_POST['status']);
            $requestDelete = $this->model->updateStatusTarea($IntIdTarea, $status);
            if ($requestDelete == 'ok') {
                if ($status == 0) {
                    $arrResponse = array('status' => true, 'msg' => "Tarea Inhabilitada Exitosamente...");
                } else {
                    $arrResponse = array('status' => true, 'msg' => "Tarea Habiitada Exitosamente...");
                }
            } else if ($requestDelete == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'No es posible inhabilitar este rol..');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar/activar este rol.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function setBitacora() {
        if ($_POST) {
            if (empty($_POST["txtBitacora"])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o mal procesados...');
            } else {
                $idTarea = intval($_POST["idTareaB"]);
                $idBitacora = intval($_POST["idSub"]);
                $txtBitacora = strClean(ucfirst($_POST["txtBitacora"]));
                $idUsuario = intval($_SESSION["idPersona"]);
                $request_bitacora = "";

                if ($idBitacora == 0) {
                    $request_bitacora = $this->model->insertBitacoraTarea($idTarea, $idUsuario, $txtBitacora);
                    $option = 1;
                } else {
                    $request_bitacora = $this->model->updateBitacoraTarea($idTarea, $idUsuario, $txtBitacora, $idBitacora);
                    $option = 2;
                }

                if ($request_bitacora > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Bitacora subida Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Bitacora actualizada Exitosamente !!');
                    }
                } else if ($request_bitacora == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! esta bitacora ya existe...');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function removeBitacoraTarea() {
        if ($_POST) {
            $intIdTarea = intval($_POST['idTarea']);
            $intIdUser = $_SESSION["idPersona"];
            $requestDelete = $this->model->removeBitacoraTarea($intIdTarea, $intIdUser);
            if ($requestDelete == 'ok') {
                $arrResponse = array('status' => true, 'msg' => "Bitacora Removida Exitosamente...");
            } else if ($requestDelete == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'No es posible remover esta bitacora subida..');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar/activar la bitacoro.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getTareasReport() {
        $listTareas = $this->model->selectTareas();
        echo json_encode($listTareas, JSON_UNESCAPED_UNICODE);
    }

}
