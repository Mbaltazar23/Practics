<?php

class Errors extends Controllers {

    public function __construct() {
        parent::__construct();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/');
            die();
        }
    }

    public function notFound() {
        $data['page_tag'] = NOMBRE_WEB . "- Pagina no Encontrada";
        $data['page_title'] = NOMBRE_WEB;
        $data['page_name'] = "error";
        $this->views->getView($this, "error", $data);
    }

}

$notFound = new Errors();
$notFound->notFound();
?>