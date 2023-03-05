<?php

class Estudios extends Controllers {

    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login']) || !isset($_SESSION["email-personal"])) {
            header('Location: ' . base_url());
            die();
        }
    }

    /* Funciones para el perfil "GUIA" */

    public function getTareasActives($idPlan) {
        $intIdPlan = intval($idPlan);
        if ($intIdPlan > 0) {
            $listTareas = $this->model->selectTareasActive($intIdPlan);
            for ($i = 0; $i < count($listTareas); $i++) {
                $btnCalf = '';
                $btnDel = '';
                $btnNote = '';
                $listTareas[$i]["nro"] = $i + 1;
                if ($listTareas[$i]["status"] == 2) {
                    $listTareas[$i]["status"] = '<span class="badge badge-dark">Activa</span>';
                    $btnCalf = '<button class="btn btn-secondary btn-sm" onClick="fntTareaCal(' . $listTareas[$i]['id'] . ')" title="Espera Bitacora" disabled><i class="fas fa-star"></i></button>';
                    $btnDel = '<button class="btn btn-secondary btn-sm" onClick="fntDelCal(' . $listTareas[$i]['id'] . ')" title="Remover Nota" disabled><i class="fas fa-solid fa-trash"></i></button>';
                    $btnNote = '<button class="btn btn-secondary btn-sm" onClick="fntViewCal(' . ($i + 1) . "," . $listTareas[$i]['id'] . ')" title="Ver Notas" disabled><i class="fas fa-book"></i></button>';
                } else if ($listTareas[$i]["status"] == 3) {
                    $listTareas[$i]["status"] = '<span class="badge badge-primary">Pendiente</span>';
                    $btnCalf = '<button class="btn btn-dark btn-sm" onClick="fntTareaCal(' . $listTareas[$i]['id'] . ')" title="Evaluar Tarea"><i class="fas fa-star"></i></button>';
                    $btnDel = '<button class="btn btn-secondary btn-sm" onClick="fntDelCal(' . $listTareas[$i]['id'] . ')" title="Remover Nota" disabled><i class="fas fa-solid fa-trash"></i></button>';
                    $btnNote = '<button class="btn btn-secondary btn-sm" onClick="fntViewCal(' . ($i + 1) . "," . $listTareas[$i]['id'] . ')" title="Ver Notas" disabled><i class="fas fa-book"></i></button>';
                } else {
                    $listTareas[$i]["status"] = '<span class="badge badge-danger">Calificada</span>';
                    $btnCalf = '<button class="btn btn-dark btn-sm" onClick="fntTareaCalUp(' . $listTareas[$i]['id'] . ')" title="Actualizar Nota"><i class="fas fa-star"></i></button>';
                    $btnDel = '<button class="btn btn-danger btn-sm" onClick="fntDelCal(' . $listTareas[$i]['id'] . ')" title="Remover Nota"><i class="fas fa-solid fa-trash"></i></button>';
                    $btnNote = '<button class="btn btn-primary btn-sm" onClick="fntViewCal(' . ($i + 1) . "," . $listTareas[$i]['id'] . ')" title="Ver Notas"><i class="fas fa-book"></i></button>';
                }
                $listTareas[$i]['options'] = '<div class="text-center">' . $btnCalf . ' ' . $btnDel . ' ' . $btnNote . '</div>';
            }
        }
        echo json_encode($listTareas, JSON_UNESCAPED_UNICODE);
    }

    public function getTareaBitacora($idTarea) {
        $IntIdTarea = intval($idTarea);
        if ($IntIdTarea > 0) {
            $arrData = $this->model->selectTareaBit($IntIdTarea);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $selectNotas = $this->model->selectNotaBit($arrData["idBit"]);
                if (!empty($selectNotas)) {
                    $arrData["detalleNota"] = $selectNotas;
                }
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function setNotaBitacora() {
        if ($_POST) {
            if (empty($_POST["listNotasC"]) || empty($_POST["nota1"])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o mal procesados...');
            } else {
                $idNote = intval($_POST["idNote"]);
                $idBit = intval($_POST["idBit"]);
                $idTarea = intval($_POST["idTareaT"]);
                $idPlan = intval($_POST["idPlanN"]);
                $nota1 = !empty($_POST["nota1"]) ? floatval(str_replace(',', '.', $_POST["nota1"])) : 0;
                $nota2 = !empty($_POST["nota2"]) ? floatval(str_replace(',', '.', $_POST["nota2"])) : 0;
                $nota3 = !empty($_POST["nota3"]) ? floatval(str_replace(',', '.', $_POST["nota3"])) : 0;


                $txtComentario = strClean(ucfirst($_POST["txtComent"]));
                $request_nota = "";

                if ($idNote == 0) {
                    $request_nota = $this->model->insertNotes($idTarea, $idPlan, $idBit, $nota1, $nota2, $nota3, $txtComentario);
                    $option = 1;
                } else {
                    $request_nota = $this->model->updateNotes($idNote, $nota1, $nota2, $nota3, $txtComentario);
                    $option = 2;
                }

                if ($request_nota > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Nota registrada Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Nota actualizada Exitosamente !!');
                    }
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function removeNoteBitacora() {
        if ($_POST) {
            $intIdTarea = intval($_POST['idTarea']);
            $requestDelete = $this->model->deleteNote($intIdTarea);
            if ($requestDelete == 'ok') {
                $arrResponse = array('status' => true, 'msg' => "Nota Removida Exitosamente...");
            } else if ($requestDelete == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'No es posible remover esta nota subida..');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar/activar la nota.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    /* Funciones para el perfil "ALUMNO" */

    public function setBitacora() {
        if ($_POST) {
            if (empty($_POST["txtBitacora"]) || empty($_POST["txtFecha"])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o mal procesados...');
            } else {
                $idTarea = intval($_POST["idTareaB"]);
                $idBitacora = intval($_POST["idSub"]);
                $txtBitacora = strClean(ucfirst($_POST["txtBitacora"]));
                $txtFecha = $_POST["txtFecha"];
                $idUsuario = intval($_SESSION["userData"]["detalleRol"]["id"]);
                $idPlanA = intval($_SESSION["userData"]["detalleRol"]["idPlanA"]);
                $request_bitacora = "";

                if ($idBitacora == 0) {
                    $request_bitacora = $this->model->insertBitacoraTarea($idTarea, $idUsuario, $idPlanA, $txtBitacora, $txtFecha);
                    $option = 1;
                } else {
                    $request_bitacora = $this->model->updateBitacoraTarea($idTarea, $txtBitacora, $idBitacora, $txtFecha);
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
            $intIdUserPlan = intval($_SESSION["userData"]["detalleRol"]["idPlanA"]);
            $requestDelete = $this->model->removeBitacoraTarea($intIdTarea, $intIdUserPlan);
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

    public function getDocumentation() {
        $IntIdPlan = intval($_SESSION["userData"]["detalleRol"]["idAlumPlan"]);
        if ($IntIdPlan > 0) {
            $arrData = $this->model->selectDocumentacionPlan($IntIdPlan);

            if (empty($arrData)) {
                $arrResponse = array('status' => true, 'data' => '');
            } else {
                $Note = $this->model->selectNoteDoc($arrData["id"]);

                $arrData["NoteDocument"] = $Note;

                $arrImg = $this->model->selectImagesDoc($IntIdPlan);
                if (count($arrImg) > 0) {
                    for ($i = 0; $i < count($arrImg); $i++) {
                        $arrImg[$i]['url_image'] = media() . '/images/documentation/' . $arrImg[$i]['nombre'];
                    }
                }
                $arrData['imagesDoc'] = $arrImg;
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function getCalificacion($idTarea) {
        $IntIdTarea = intval($idTarea);
        if ($IntIdTarea > 0) {
            $arrData = $this->model->selectCalTarea($IntIdTarea);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {

                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function setDocumentacion() {
        if ($_POST) {
            if (empty($_POST["txtTitulo"])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o mal procesados...');
            } else {
                $idPlanA = intval($_SESSION["userData"]["detalleRol"]["idPlanA"]);
                $idDoc = intval($_POST["idDoc"]);
                $titulo = strClean(ucfirst($_POST["txtTitulo"]));
                $parrafos = strClean(ucfirst($_POST["txtDocumento"]));
                $request_document = "";

                if ($idDoc == 0) {
                    $request_document = $this->model->insertDocumentacion($idPlanA, $titulo, $parrafos);
                    $option = 1;
                } else {
                    $request_document = $this->model->updateDocumentacion($idDoc, $titulo, $parrafos);
                    $option = 2;
                }

                if ($request_document > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Documentacion subida Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Documentacion actualizada Exitosamente !!');
                    }
                } else if ($request_document == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención!  ya existe una documentacion subida...');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function delDocumentacion() {
        if ($_POST) {
            $intIdDoc = intval($_POST["idDoc"]);
            $intIdUserPlan = intval($_SESSION["userData"]["detalleRol"]["idAlumPlan"]);

            $arrImg = $this->model->selectImagesDoc($intIdUserPlan);
            if (count($arrImg) > 0) {
                for ($i = 0; $i < count($arrImg); $i++) {
                    $imgNombre = $arrImg[$i]['nombre'];
                    deleteFile($imgNombre, "documentation");
                }
            }
            $requestDelete = $this->model->removeDocumentacion($intIdUserPlan, $intIdDoc);
            if ($requestDelete == 'ok') {
                $arrResponse = array('status' => true, 'msg' => "Documentacion Removida Exitosamente...");
            } else if ($requestDelete == 'error') {
                $arrResponse = array('status' => false, 'msg' => 'No es posible remover esta documentacion subida..');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar/activar la documentacion.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function setImage() {
        if ($_POST) {
            if (empty($_POST['idAlumP'])) {
                $arrResponse = array('status' => false, 'msg' => 'Error de dato.');
            } else {
                $idAlumP = intval($_POST['idAlumP']);
                $foto = $_FILES['foto'];
                $imgNombre = 'doc_' . md5(date('d-m-Y H:m:s')) . '.jpg';
                $request_image = $this->model->insertImage($idAlumP, $imgNombre);
                if ($request_image) {
                    $uploadImage = uploadImage($foto, $imgNombre, "documentation");
                    $arrResponse = array('status' => true, 'imgname' => $imgNombre, 'msg' => 'Imagen subida Exitosamente !!');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error de carga.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function delFile() {
        if ($_POST) {
            if (empty($_POST['idAlumP']) || empty($_POST['file'])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
            } else {
                //Eliminar de la DB
                $idAlumP = intval($_POST['idAlumP']);
                $imgNombre = strClean($_POST['file']);
                $request_image = $this->model->deleteImage($idAlumP, $imgNombre);

                if ($request_image) {
                    $deleteFile = deleteFile($imgNombre, "documentation");
                    $arrResponse = array('status' => true, 'msg' => 'Imagen Eliminada Exitosamente !!');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Error al eliminar');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    /* Funciones para el perfil "Profesor" */

    public function getDocumentCalf($idAlum) {
        $intIdAlum = intval($idAlum);
        if ($intIdAlum > 0) {
            $arrData = $this->model->selectDocAlum($intIdAlum);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.', 'data' => '');
            } else {
                $arrImg = $this->model->selectImagesDoc($arrData["alumno_plan_id"]);

                if (count($arrImg) > 0) {
                    for ($i = 0; $i < count($arrImg); $i++) {
                        $arrImg[$i]['url_image'] = media() . '/images/documentation/' . $arrImg[$i]['nombre'];
                    }
                }
                $arrData['imagesDoc'] = $arrImg;
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getNoteDocument(int $idDoc) {
        $intIdDoc = intval($idDoc);
        if ($intIdDoc > 0) {
            $arrData = $this->model->selectNoteDoc($intIdDoc);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function setNoteDocument() {
        if ($_POST) {
            if (empty($_POST["txtNota"])) {
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos o mal procesados...');
            } else {
                $idDoc = intval($_POST["idDocN"]);
                $idNoteD = intval($_POST["idNoteD"]);
                $txtNota = floatval(str_replace(',', '.', $_POST["txtNota"]));
                $txtComentarioDoc = strClean(ucfirst($_POST["txtComentarioDoc"]));
                $request_notaDoc = "";

                if ($idNoteD == 0) {
                    $request_notaDoc = $this->model->insertNotaDoc($idDoc, $txtComentarioDoc, $txtNota);
                    $option = 1;
                } else {
                    $request_notaDoc = $this->model->updateNotaDoc($idNoteD, $txtComentarioDoc, $txtNota);
                    $option = 2;
                }

                if ($request_notaDoc > 0) {
                    if ($option == 1) {
                        $arrResponse = array('status' => true, 'msg' => 'Nota subida Exitosamente !!');
                    } else {
                        $arrResponse = array('status' => true, 'msg' => 'Nota actualizada Exitosamente !!');
                    }
                } else if ($request_notaDoc == 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención!  ya existe una documentacion subida...');
                } else {
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function delNoteDoc() {
        if ($_POST) {
            $intIdDoc = intval($_POST['idDoc']);
            $requestDelete = $this->model->removeNoteDocumentation($intIdDoc);
            if ($requestDelete == 'ok') {
                $arrResponse = array('status' => true, 'msg' => "Nota Removida Exitosamente...");
            } else if ($requestDelete == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'No es posible remover esta nota subida..');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar/activar la nota.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getAlumnTarea($idAlum) {
        $IntIdAlum = intval($idAlum);
        if ($IntIdAlum > 0) {
            $arrData = $this->model->selectAlumNotes($IntIdAlum);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getTareasCal($idPlan) {
        $listTareas = $this->model->selectTareasAlum($idPlan);
        for ($i = 0; $i < count($listTareas); $i++) {
            $listTareas[$i]["nro"] = $i + 1;
            $listTareas[$i]["status"] = '<span class="badge badge-warning">Calificado</span>';
            // Validar si nota2 o nota3 están nulas
            $nota1 = $listTareas[$i]["nota01"];
            $nota2 = $listTareas[$i]["nota02"];
            $nota3 = $listTareas[$i]["nota03"];
            $notas = "<li>" . $nota1 . "</li>" . ($nota2 ? "<li>" . $nota2 . "</li>" : "") . "  " . ($nota3 ? "<li>" . $nota3 . "</li>" : "");

            $listTareas[$i]["notas"] = $notas;
        }
        echo json_encode($listTareas, JSON_UNESCAPED_UNICODE);
    }

}
