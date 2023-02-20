<?php

class Home extends Controllers {

    public function __construct() {
        parent::__construct();
        session_start();
    }

    public function home() {
        $data['page_tag'] = NOMBRE_WEB . " - Inicio";
        $data['page_title'] = NOMBRE_WEB;
        $data['page_name'] = "home";
        $data['page_functions_js'] = "functions_login.js";
        $this->views->getView($this, "home", $data);
    }

    public function login() {
        if ($_POST) {
            if (empty($_POST['txtEmail']) || empty($_POST['txtPassword'])) {
                $arrResponse = array('status' => false, 'msg' => 'Error de datos');
            } else {
                $strCorreo = ucwords(strClean($_POST['txtEmail']));
                $strPassword = md5($_POST["txtPassword"]);
                $requestUser = $this->model->loginUser($strCorreo, $strPassword);
                if (empty($requestUser)) {
                    $arrResponse = array('status' => false, 'msg' => 'El usuario o la contraseña es incorrecto.');
                } else {
                    $_SESSION['login'] = true;
                    $arrData = $this->model->sessionLogin($requestUser['id']);
                    $_SESSION["email-personal"] = $arrData["correo"];
                    $_SESSION["nombres"] = $arrData["nombre"] . " " . $arrData["apellido"];
                    $_SESSION["idPersona"] = $arrData["id"];
                    $_SESSION["titulo"] = $arrData["titulo"];
                    $_SESSION["encabezado"] = $arrData["encabezado"];
                    $_SESSION["cargo-personal"] = $arrData["rol"];
                    $arrResponse = array('status' => true, 'msg' => 'ok', 'personal' => $arrData);
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getUser() {
        $IntIdPersonal = intval($_SESSION["idPersona"]);
        if ($IntIdPersonal > 0) {
            $arrData = $this->model->getUser($IntIdPersonal);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                if ($arrData['avatar'] != "") {
                    $arrData['url_portada'] = media() . '/images/perfil/' . $arrData["avatar"];
                } else {
                    $arrData['url_portada'] = media() . '/images/perfil/logo_icono.jpg';
                }
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function setSesion() {
        $IntIdPersonal = intval($_SESSION["idPersona"]);
        if ($IntIdPersonal > 0) {
            $session = $this->model->desconectarSession($IntIdPersonal);
            echo $session;
        }
    }

    public function setPasswordUser() {
        if ($_POST) {
            if (empty($_POST["txtRut"]) || empty($_POST["txtNombre"]) || empty($_POST["txtApellido"])) {
                $arrResponse = array('status' => false, 'msg' => 'Error de datos');
            } else {
                $idPersonal = intval($_POST["idPerPass"]);
                $txtRut = $_POST["txtRut"];
                $txtNombre = strClean(ucwords($_POST["txtNombre"]));
                $txtApellido = strClean(ucwords($_POST["txtApellido"]));
                $txtEmail = strClean(ucwords($_POST["txtEmail"]));
                $txtDireccion = strClean($_POST["txtDireccion"]);
                $password = empty($_POST["txtPassword"]) ? "" : md5($_POST["txtPassword"]);

                $request_pass = $this->model->setPerfilLogin($idPersonal, $txtRut, $txtNombre, $txtApellido, $txtEmail, $txtDireccion, $password);
                if ($request_pass) {
                    $arrResponse = array("status" => true, "msg" => 'Perfil actualizado Exitosamente !!');
                    $this->model->sessionLogin($idPersonal);
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function setPortadaPerfil() {
        if ($_POST) {
            if (empty($_POST['idpersona'])) {
                $arrResponse = array('status' => false, 'msg' => 'Error de dato.');
            } else {
                $idPersona = intval($_POST['idpersona']);
                $foto = $_FILES['foto'];
                $imgNombre = 'prf_' . md5(date('d-m-Y H:m:s')) . '.jpg';
                $request_image = $this->model->insertPortada($idPersona, $imgNombre);
                if ($request_image) {
                    $uploadImage = uploadImage($foto, $imgNombre, 'perfil');
                    $arrResponse = array('status' => true, 'imgname' => $imgNombre, 'msg' => 'Portada Subida Exitosamente !!');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error de carga.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function delPortada() {
        if ($_POST) {
            if (empty($_POST['idpersona']) || empty($_POST['file'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                //Eliminar de la DB
                $idPersona = intval($_POST['idpersona']);
                $imgNombre = strClean($_POST['file']);
                $request_image = $this->model->insertPortada($idPersona, "");

                if ($request_image) {
                    $deleteFile = deleteFile($imgNombre, 'perfil');
                    $arrResponse = array('status' => true, 'msg' => 'Portada Eliminada !!');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al eliminar');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

}

?>