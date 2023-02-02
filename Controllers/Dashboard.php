<?php

class Dashboard extends Controllers {

    public function __construct() {
        parent::__construct();
        if (empty($_SESSION['login']) || empty($_SESSION['cargo-personal'])) {
            header('Location: ' . base_url() . '/home');
            die();
        }
    }

    public function dashboard() {
        $data['page_tag'] = NOMBRE_WEB . " - Dashboard";
        $data['page_title'] = NOMBRE_WEB . " - dashboard";
        $data['page_name'] = "dashboard";
        $data['page_functions_js'] = "functions_dashboard.js";
        $this->views->getView($this, "dashboard", $data);
    }

    public function profile() {
        $data['page_tag'] = NOMBRE_WEB . " - Perfil";
        $data['page_title'] = NOMBRE_WEB . " - perfil";
        $data['page_name'] = "profile";
        $data['page_functions_js'] = "functions_profile.js";
        $this->views->getView($this, "profile", $data);
    }

}

?>