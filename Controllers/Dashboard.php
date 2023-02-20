<?php

class Dashboard extends Controllers {

    public function __construct() {
        parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/home');
            die();
        }
    }

    public function dashboard() {
        $data['page_tag'] = NOMBRE_WEB . " - ".DASHBOARD;
        $data['page_title'] = NOMBRE_WEB . " - dashboard";
        $data['page_name'] = "dashboard";
        $data['cardsPanel'] = $this->model->generarCardPanel();
        $data['encabezado'] = isset($_SESSION['login']) ? $_SESSION["encabezado"] : "";
        $data['rol'] = $_SESSION["cargo-personal"] != ROLADMIN ? $_SESSION["cargo-personal"] . ' - ' . "<strong>Colegio : " . $_SESSION["userData"]["detalleRol"]["nombreCole"] . "</strong>": $_SESSION["cargo-personal"];
        $data['page_functions_js'] = "functions_dashboard.js";
        $this->views->getView($this, "dashboard", $data);
    }

    public function profile() {
        $data['page_tag'] = NOMBRE_WEB . " - Perfil";
        $data['page_title'] = NOMBRE_WEB . " - perfil";
        $data['page_name'] = "profile";
        $data['page_functions_js'] = "functions_perfil.js";
        $this->views->getView($this, "profile", $data);
    }

}

?>