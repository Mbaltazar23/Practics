<?php

/**
 * Description of Cursos
 *
 * @author mario
 */
class Cursos extends Controllers {

    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login']) || !isset($_SESSION["email-personal"])) {
            header('Location: ' . base_url());
            die();
        }
    }

    public function cursos() {
        if ($_SESSION['cargo-personal'] !== ROLADMINCOLE) {
            header('Location: ' . base_url());
            die();
        }
        $data['page_tag'] = NOMBRE_WEB . "- Cursos del Colegio " . $_SESSION["userData"]["detalleRol"]["nombreCole"];
        $data['page_title'] = "Cursos";
        $data['page_name'] = "cursos";
        $data['rol-personal'] = $_SESSION['cargo-personal'];
        $data['page_functions_js'] = "functions_cursos.js";
        $this->views->getView($this, "cursos", $data);
    }

    public function getCursos() {
        $listCursos = $this->model->selectCursos();
        for ($i = 0; $i < count($listCursos); $i++) {
            $btnView = '';
            $btnEdit = '';
            $btnDelAc = '';
            $listCursos[$i]["nro"] = $i + 1;
            if ($listCursos[$i]["status"] == 1) {
                $listCursos[$i]["status"] = '<span class="badge badge-success">Activo</span>';
                $btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo(' . ($i + 1) . ',' . $listCursos[$i]['id'] . ')" title="Ver Curso"><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-primary btn-sm" onClick="fntEditInfo(this,' . $listCursos[$i]['id'] . ')" title="Editar Curso"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelAc = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo(' . $listCursos[$i]['id'] . ')" title="Eliminar Curso "><i class="far fa-trash-alt"></i></button>';
            } else {
                $listCursos[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
                $btnView = '<button class="btn btn-secondary btn-sm" onClick="fntViewInfo(' . $listCursos[$i]['id'] . ')" title="Ver Curso" disabled><i class="far fa-eye"></i></button>';
                $btnEdit = '<button class="btn btn-secondary  btn-sm" onClick="fntEditInfo(this,' . $listCursos[$i]['id'] . ')" title="Editar Curso" disabled><i class="fas fa-pencil-alt"></i></button>';
                $btnDelAc = '<button class="btn btn-secondary btn-sm" onClick="fntActivateInfo(' . $listCursos[$i]['id'] . ')" title="Activar Curso"><i class="fas fa-toggle-on"></i></button>';
            }

            $listCursos[$i]['options'] = '<div class="text-center">' . $btnView . '  ' . $btnEdit . ' ' . $btnDelAc . '</div>';
        }
        echo json_encode($listCursos, JSON_UNESCAPED_UNICODE);
    }

    public function setCurso() {
        if ($_POST) {
            if (empty($_POST["txtNombre"])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o mal procesados...');
            } else {
                $idCurso = intval($_POST["idCurso"]);
                $strNombre = ucwords(strClean($_POST["txtNombre"]));
                $IdColegio = intval($_SESSION["userData"]["detalleRol"]["colegio_id"]);
                $request_curso = "";

                if ($idCurso == 0) {
                    $option = 1;
                    $request_curso = $this->model->insertCurso($strNombre, $IdColegio);
                } else {
                    $option = 2;
                    $request_curso = $this->model->updateCurso($idCurso, $strNombre);
                }

                if ($request_curso > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Curso registrado Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Curso actualizado Exitosamente !!');
                    }
                } else if ($request_curso == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => 'El curso ya existe');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible agregar los datos');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getCurso($idCurso) {
        $IntIdCurso = intval($idCurso);
        if ($IntIdCurso > 0) {
            $arrData = $this->model->selectCurso($IntIdCurso);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getSelectCursos() {
        $htmlOptions = "";
        $arrData = $this->model->selectCursos(1);
        echo '<option value="0">Seleccione un Curso</option>';
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['id'] . '">' . $arrData[$i]['nombre'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function setStatusCurso() {
        if ($_POST) {
            $IntIdCurso = intval($_POST["idCurso"]);
            $status = intval($_POST['status']);
            $requestDelete = $this->model->updateStatusCurso($IntIdCurso, $status);
            if ($requestDelete == 'ok') {
                if ($status == 0) {
                    $arrResponse = array('status' => true, 'msg' => "Curso Inhabilitado Exitosamente !!");
                } else {
                    $arrResponse = array('status' => true, 'msg' => "Curso Habiitado Exitosamente !!");
                }
            } else if ($requestDelete == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'Este curso ya esta en uso por Alumnos');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al activar el curso .');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getCursosReport() {
        $listCursos = $this->model->selectCursos();
        echo json_encode($listCursos, JSON_UNESCAPED_UNICODE);
    }

}
