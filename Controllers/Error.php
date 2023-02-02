<?php

require_once("Models/TCategoria.php");
require_once("Models/TProducto.php");

class Errors extends Controllers {

    use TCategoria,
        TProducto;

    public function __construct() {
        parent::__construct();
        session_start();
    }

    public function notFound() {
        $data['page_tag'] = NOMBRE_EMPESA. "- Pagina no Encontrada";
        $data['page_title'] = NOMBRE_EMPESA;
        $data['page_name'] = "error";
        $data['slider'] = $this->getCategoriasT(MENUCAT);
        $data['searchCat'] = $this->getCategorias();
        $this->views->getView($this, "error", $data);
    }

}

$notFound = new Errors();
$notFound->notFound();
?>