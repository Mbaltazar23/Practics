<?php

class Login extends Controllers {

    public function __construct() {
        session_start();
        session_regenerate_id(true);
        parent::__construct();
    }

    public function loginUser() {
        //dep($_POST);
        if ($_POST) {
            if (empty($_POST['txtEmail']) || empty($_POST['txtPassword'])) {
                $arrResponse = array('status' => false, 'msg' => 'Error de datos');
            } else {
                $strUsuario = ucwords(strClean($_POST['txtEmail']));
                $strPassword = md5($_POST['txtPassword']);
                $requestUser = $this->model->loginUser($strUsuario, $strPassword);
                if (empty($requestUser)) {
                    $arrResponse = array('status' => false, 'msg' => 'El usuario o la contraseña es incorrecto.');
                } else {
                    $arrData = $requestUser;
                    if ($arrData['estadoPersona'] != 0) {
                        $_SESSION['idUser'] = $arrData['idpersona'];
                        $_SESSION['login'] = true;
                        $arrData = $this->model->sessionLogin($_SESSION['idUser']);
                        $_SESSION['rol'] = $arrData['nombreRol'];
                        sessionUser($_SESSION['idUser']);
                        suscripcionCli($_SESSION['idUser']);
                        $arrResponse = array('status' => true, 'msg' => 'ok', 'userData' => $arrData);
                    } else {
                        $arrResponse = array('status' => false, 'msg' => 'Usuario inactivo.');
                    }
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

}

?>