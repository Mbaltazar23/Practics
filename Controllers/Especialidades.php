<?php

/**
 * Description of Especialidades
 *
 * @author mario
 */
class Especialidades extends Controllers {

    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login']) || !isset($_SESSION["email-personal"])) {
            header('Location: ' . base_url());
            die();
        }
    }

    public function especialidades() {
        if ($_SESSION['cargo-personal'] !== ROLADMINCOLE) {
            header('Location: ' . base_url());
            die();
        }
        $data['page_tag'] = NOMBRE_WEB . "- Especialidades del Colegio " . $_SESSION["userData"]["detalleRol"]["nombreCole"];
        $data['page_title'] = "Especialidades";
        $data['page_name'] = "especialidades";
        $data['rol-personal'] = $_SESSION['cargo-personal'];
        $data['page_functions_js'] = "functions_especialidades.js";
        $this->views->getView($this, "especialidades", $data);
    }

    public function getEspecialidades() {
        $listEspecialidades = $this->model->selectEspecialidades();
        for ($i = 0; $i < count($listEspecialidades); $i++) {
            $btnView = '';
            $btnEdit = '';
            $btnDelAc = '';
            $listEspecialidades[$i]["nro"] = $i + 1;
            if ($listEspecialidades[$i]["status"] == 1) {
                $listEspecialidades[$i]["status"] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . ($i + 1) . ',' . $listEspecialidades[$i]['id'] . ')" title="Ver Especialidad"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary btn-sm" onClick="fntEditInfo(this,' . $listEspecialidades[$i]['id'] . ')" title="Editar Especialidad"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelAc = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $listEspecialidades[$i]['id'] . ')" title="Eliminar Especialidad "><i class="far fa-trash-alt"></i></button>';
            } else {
                $listEspecialidades[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $listEspecialidades[$i]['id'] . ')" title="Ver Especialidad" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $listEspecialidades[$i]['id'] . ')" title="Editar Especialidad" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnDelAc = '<button class="btn btn-secondary btn-sm" onClick="fntActivateInfo(' . $listEspecialidades[$i]['id'] . ')" title="Activar Especialidad"><i class="fas fa-toggle-on"></i></button>';
            }

            $listEspecialidades[$i]['options'] = '<div class="text-center">' . $btnView . '  ' . $btnEdit . ' ' . $btnDelAc . '</div>';
        }
        echo json_encode($listEspecialidades, JSON_UNESCAPED_UNICODE);
    }

    public function setEspecialidad() {
        if ($_POST) {
            if (empty($_POST["txtNombre"])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o mal procesados...');
            } else {
                $idEspecialidad = intval($_POST["idEspecialidad"]);
                $strNombre = ucwords(strClean($_POST["txtNombre"]));
                $IdColegio = intval($_SESSION["userData"]["detalleRol"]["colegio_id"]);
                $request_especialidad = "";

                if ($idEspecialidad == 0) {
                    $option = 1;
                    $request_especialidad = $this->model->insertEspecialidad($strNombre, $IdColegio);
                } else {
                    $option = 2;
                    $request_especialidad = $this->model->updateEspecialidad($idEspecialidad, $strNombre);
                }

                if ($request_especialidad > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Especialidad registrada Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Especialidad actualizada Exitosamente !!');
                    }
                } else if ($request_especialidad == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => 'La especialidad ya existe');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible agregar los datos');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getEspecialidad($idEspecialidad) {
        $IntIdEspecialidad = intval($idEspecialidad);
        if ($IntIdEspecialidad > 0) {
            $arrData = $this->model->selectEspecialidad($IntIdEspecialidad);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getSelectEspecialidades() {
        $htmlOptions = "";
        $arrData = $this->model->selectEspecialidades(1);
        echo '<option value="0">Seleccione una Especialidad</option>';
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['nombre'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function setStatusEspecialidad() {
        if ($_POST) {
            $IntIdEspecialidad = intval($_POST["idEspecialidad"]);
            $status = intval($_POST['status']);
            $requestDelete = $this->model->updateStatusEspecialidad($IntIdEspecialidad, $status);
            if ($requestDelete == 'ok') {
                if ($status == 0) {
                    $arrResponse = array('status' => true, 'msg' => "Especialidad Inhabilitada Exitosamente !!");
                } else {
                    $arrResponse = array('status' => true, 'msg' => "Especialidad Habiitada Exitosamente !!");
                }
            } else if ($requestDelete == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'Esta especialidad ya esta en uso por Alumnos');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al activar el curso .');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getEspecialidadesReport() {
        $listEspecialidades = $this->model->selectEspecialidades();
        echo json_encode($listEspecialidades, JSON_UNESCAPED_UNICODE);
    }

}
