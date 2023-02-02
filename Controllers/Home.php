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
            if (empty($_POST['correo']) || empty($_POST['clave'])) {
                $arrResponse = array('status' => false, 'msg' => 'Error de datos');
            } else {
                $strCorreo = ucwords(strClean($_POST['correo']));
                $strPassword = md5($_POST["clave"]);
                $requestUser = $this->model->loginUser($strCorreo, $strPassword);
                if (empty($requestUser)) {
                    $arrResponse = array('status' => false, 'msg' => 'El usuario o la contraseña es incorrecto.');
                } else {
                    $_SESSION['login'] = true;
                    $arrData = $this->model->sessionLogin($requestUser['idpersona']);
                    $_SESSION["email-personal"] = $arrData["correo"];
                    $_SESSION["nombres"] = $arrData["nombres"] . " " . $arrData["apellidos"];
                    $_SESSION["idPersona"] = $arrData["idpersona"];
                    $_SESSION["titulo"] = $arrData["titulo"];
                    $_SESSION["encabezado"] = $arrData["encabezado"];
                    $_SESSION["cargo-personal"] = $arrData["rol"];
                    $arrResponse = array('status' => true, 'msg' => 'ok', 'personal' => $requestUser);
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getUser($idPersonal) {
        $IntIdPersonal = intval($idPersonal);
        if ($IntIdPersonal > 0) {
            $arrData = $this->model->getUser($IntIdPersonal);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                if ($arrData['avatar'] != "") {
                    $arrData['url_portada'] = media() . '/img/perfil/' . $arrData['avatar'];
                }
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function setSesion($idPerson) {
        $IntIdPersonal = intval($idPerson);
        if ($IntIdPersonal > 0) {
            $session = $this->model->desconectarSession($IntIdPersonal);
            echo $session;
        }
    }

    public function setPasswordUser() {
        if ($_POST) {
            if (empty($_POST["txtPassword"]) || empty($_POST["txtPassword02"])) {
                $arrResponse = array('status' => false, 'msg' => 'Error de datos');
            } else {
                $idPersonal = intval($_POST["idPerPass"]);
                $password = md5($_POST["txtPassword"]);

                $request_pass = $this->model->setPerfilLogin($idPersonal, $password);
                if ($request_pass) {
                    $arrResponse = array("status" => true, "msg" => 'Perfil actualizado Exitosamente !!');
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

    public function searchPersons() {
        if ($_POST) {
            $idPerson = intval($_SESSION["idPersona"]);
            $nameSearch = strClean($_POST["searchTerm"]);
            $requestChat = $this->model->selectPersonsChat($idPerson, $nameSearch);
            echo json_encode($requestChat);
        }
    }

    public function searchPersonsActives() {
        if ($_GET) {
            $idPerson = intval($_SESSION["idPersona"]);
            $requestPersons = $this->model->selectPersonsActive($idPerson);
            echo json_encode($requestPersons);
        }
    }

    public function setChatPerson() {
        if ($_POST) {
            $strMessage = strClean($_POST["message"]);
            $intIdEnvio = intval($_SESSION["idPersona"]);
            $intIdRecept = intval($_POST["incoming_id"]);

            $request_chat = $this->model->insertChat($intIdEnvio, $intIdRecept, $strMessage);

            echo $request_chat;
        }
    }

    public function getChats() {
        if ($_POST) {
            $intIdRecept = intval($_SESSION["idPersona"]);
            $intIdEnvio = intval($_POST["incoming_id"]);

            $requestMessages = $this->model->selectMessages($intIdEnvio, $intIdRecept);
            echo json_encode($requestMessages);
        }
    }

}

?>