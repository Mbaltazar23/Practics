<?php

/*
 * Description of EstudiosModel
 *
 * @author mario
 */

class EstudiosModel extends Mysql {

    // status en tarea_plan 
    //  1 => inactiva  2 => activa (alumno y profesores) 3 => subida/recibida  (alumno /profesores)  4 => calificada (alumno y profesores)
    private $idTarea;
    private $idAlum;
    private $idPlan;
    private $idBit;
    private $idNote;
    private $idDoc;
    private $bitacoraTarea;
    private $fechaBitacora;
    private $nota01;
    private $nota02;
    private $nota03;
    private $promedio;
    private $comentarioNota;
    private $titulo;
    private $texto;

    public function __construct() {
        parent::__construct();
    }

    public function getBitacora(int $idTarea, int $idAlumn) {
        $this->idTarea = $idTarea;
        $this->idAlum = $idAlumn;
        $sql = "SELECT * FROM bitacora WHERE tarea_id = $this->idTarea AND  alumno_id = $this->idAlum";
        $request = $this->select($sql);
        return $request;
    }

    public function insertBitacoraTarea(int $idtarea, int $idalumn, int $idAlumP, string $bitacora, string $fecha) {
        $this->idTarea = $idtarea;
        $this->idAlum = $idalumn;
        $this->bitacoraTarea = $bitacora;
        $this->idPlan = $idAlumP;
        $this->fechaBitacora = $fecha;
        $sql = "SELECT * FROM bitacora WHERE texto = '$this->bitacoraTarea' AND tarea_id = $this->idTarea";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_insert = "INSERT INTO bitacora(texto, alumno_id, tarea_id, fecha) VALUES (?,?,?,?)";
            $arrData = array($this->bitacoraTarea,
                $this->idAlum,
                $this->idTarea,
                $this->fechaBitacora);
            $request_insert = $this->insert($query_insert, $arrData);

            $sqlU = "UPDATE tarea_plan SET status = ? WHERE tarea_id = $this->idTarea AND plan_id = $this->idPlan";
            $arr = array(3); //el 3 representara que fue subida la tarea;
            $this->update($sqlU, $arr);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateBitacoraTarea(int $idtarea, string $bitacora, int $idBitacora, string $fecha) {
        $this->idTarea = $idtarea;
        $this->bitacoraTarea = $bitacora;
        $this->idBitacora = $idBitacora;
        $this->fechaBitacora = $fecha;
        $sql = "SELECT * FROM bitacora WHERE tarea_id = $this->idTarea AND id != $this->idBitacora";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_update = "UPDATE bitacora SET texto = ?,
                        tarea_id = ?, fecha = ? WHERE id = $this->idBitacora";
            $arrData = array($this->bitacoraTarea,
                $this->idTarea,
                $this->fechaBitacora);
            $request = $this->update($query_update, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }

    public function removeBitacoraTarea(int $idtarea, int $idPlan) {
        $this->idTarea = $idtarea;
        $this->idPlan = $idPlan;
        $sql = "SELECT b.id as idBit , b.texto, b.fecha FROM bitacora b 
                INNER JOIN nota nt ON b.id = nt.bitacora_id WHERE b.tarea_id = $this->idTarea";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sqlSl = "SELECT b.id as idBit , b.texto, b.fecha FROM bitacora b WHERE b.tarea_id = $this->idTarea";
            $requestBit = $this->select($sqlSl);
            $sqlD = "DELETE FROM bitacora WHERE id = " . $requestBit["idBit"];
            $request = $this->delete($sqlD);
            if ($request) {
                $request = 'ok';
            } else {
                $request = 'error';
            }
            $sqlU = "UPDATE tarea_plan SET status = ? WHERE tarea_id = $this->idTarea AND plan_id = $this->idPlan";
            $arr = array(2); //el 3 representara que fue subida la tarea;
            $this->update($sqlU, $arr);
        } else {
            $request = 'exist';
        }
        return $request;
    }

    /* Funciones de estudios para el perfil guia */

    public function selectTareasActive(int $idPlan) {
        $this->idPlan = $idPlan;
        $this->option = "AND (dt.status = 2 OR dt.status = 3 OR dt.status = 4)";
        $sql = "SELECT ta.id, ta.nombre,dt.status FROM tarea_plan dt
                INNER JOIN tarea ta ON dt.tarea_id = ta.id WHERE dt.plan_id = $this->idPlan $this->option";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectTareaBit(int $idTarea) {
        $this->idTarea = $idTarea;
        $sql = "SELECT t.plan_id ,t.tarea_id ,ta.nombre, t.status, bt.id as idBit, bt.texto FROM bitacora bt INNER JOIN tarea_plan t ON bt.tarea_id = t.tarea_id 
               INNER JOIN tarea ta ON bt.tarea_id = ta.id WHERE t.tarea_id = $this->idTarea";
        $request = $this->select($sql);
        return $request;
    }

    public function selectNotaBit(int $idBitacora) {
        $this->idBit = $idBitacora;
        $sql = "SELECT * FROM nota nt WHERE nt.bitacora_id = $this->idBit";
        $request = $this->select($sql);
        return $request;
    }

    public function insertNotes(int $idTarea, int $idPlan, int $idBit, float $nota01, float $nota02, float $nota03, string $comentario) {
        $this->idTarea = $idTarea;
        $this->idPlan = $idPlan;
        $this->idBit = $idBit;
        $this->comentarioNota = $comentario;

        $notas = array();

        if (!empty($nota01)) {
            $notas[] = $nota01;
            $this->nota01 = $nota01;
        }

        if (!empty($nota02)) {
            $notas[] = $nota02;
            $this->nota02 = $nota02;
        }

        if (!empty($nota03)) {
            $notas[] = $nota03;
            $this->nota03 = $nota03;
        }

        $this->promedio = $promedio = count($notas) > 0 ? array_sum($notas) / count($notas) : 0;

        $variableStringConsulta = "INSERT INTO nota(nota01, nota02, nota03, promedio, bitacora_id, comentario) VALUES (?,?,?,?,?,?)";
        $arregloDatos = array(
            $this->nota01,
            $this->nota02,
            $this->nota03,
            $this->promedio,
            $this->idBit,
            $this->comentarioNota
        );

        $request = $this->insert($variableStringConsulta, $arregloDatos);

        if ($request) {
            $sqlU = "UPDATE tarea_plan SET status = ? WHERE tarea_id = $this->idTarea AND plan_id = $this->idPlan";
            $arr = array(4); //el 4 representara que fue evaluada la tarea;
            $this->update($sqlU, $arr);
        }

        return $request;
    }

    public function updateNotes(int $idNote, float $nota01, float $nota02, float $nota03, string $comentario) {
        $this->idNote = $idNote;
        $this->comentarioNota = $comentario;

        $notas = array();

        if (!empty($nota01)) {
            $notas[] = $nota01;
            $this->nota01 = $nota01;
        }

        if (!empty($nota02)) {
            $notas[] = $nota02;
            $this->nota02 = $nota02;
        }

        if (!empty($nota03)) {
            $notas[] = $nota03;
            $this->nota03 = $nota03;
        }

        $this->promedio = $promedio = count($notas) > 0 ? array_sum($notas) / count($notas) : 0;

        $sql = "UPDATE nota SET nota01 = ?, nota02 = ?, nota03 = ?, promedio = ? , comentario = ? WHERE id = $this->idNote";
        $arregloDatos = array(
            $this->nota01,
            $this->nota02,
            $this->nota03,
            $this->promedio,
            $this->comentarioNota
        );

        $request = $this->update($sql, $arregloDatos);

        return $request;
    }

    public function deleteNote(int $idTarea) {
        $this->idTarea = $idTarea;
        $sql = "SELECT t.plan_id ,t.tarea_id ,ta.nombre, t.status, bt.id as idBit, bt.texto FROM bitacora bt 
               INNER JOIN tarea_plan t ON bt.tarea_id = t.tarea_id  INNER JOIN tarea ta ON bt.tarea_id = ta.id 
               WHERE t.tarea_id = $this->idTarea";
        $request = $this->select($sql);
        if (!empty($request)) {
            $sqlDel = "DELETE FROM nota WHERE bitacora_id = " . $request["idBit"];
            $requestDel = $this->delete($sqlDel);
            if ($requestDel) {
                $response = 'ok';
                $sqlU = "UPDATE tarea_plan SET status = ? WHERE tarea_id = $this->idTarea AND plan_id = " . $request["plan_id"];
                $arr = array(3); //el 3 representara que fue evaluada la tarea;
                $this->update($sqlU, $arr);
            } else {
                $response = 'error';
            }
        }
        return $response;
    }

    /* Funciones para el perfil Alumno */

    public function selectDocumentacionPlan(int $idAlumP) {
        $this->idPlan = $idAlumP;
        $sql = "SELECT * FROM documento WHERE alumno_plan_id = $this->idPlan";
        $request = $this->select($sql);
        return $request;
    }

    public function selectNoteDoc(int $idDoc) {
        $this->idDoc = $idDoc;
        $sql = "SELECT cal.id, cal.nota, cal.documento_id, cal.comentario, DATE_FORMAT(cal.created_at, '%d/%m/%Y') as fecha,
               DATE_FORMAT(cal.created_at, '%H:%i') as hora FROM calificacion cal
               WHERE cal.documento_id = $this->idDoc";
        $request = $this->select($sql);
        return $request;
    }

    public function selectImagesDoc(int $idAlumP) {
        $this->idPlan = $idAlumP;
        $sql = "SELECT id, nombre, created_at FROM imagen WHERE alumno_plan_id = $this->idPlan";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertDocumentacion(int $idAlumP, string $titulo, string $cuerpoDocumento) {
        $this->idPlan = $idAlumP;
        $this->titulo = $titulo;
        $this->texto = $cuerpoDocumento;
        $sql = "SELECT * FROM documento WHERE titulo = '$this->titulo' AND alumno_plan_id = $this->idPlan";
        $request = $this->select_all($sql);
        $return = 0;
        if (empty($request)) {
            $sqlInsert = "INSERT INTO documento(titulo, texto, alumno_plan_id) VALUES (?,?,?)";
            $arrData = array($this->titulo,
                $this->texto,
                $this->idPlan);
            $request_insert = $this->insert($sqlInsert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateDocumentacion(int $idDoc, string $titulo, string $cuerpoDocumento) {
        $this->idDoc = $idDoc;
        $this->titulo = $titulo;
        $this->texto = $cuerpoDocumento;
        $sql = "SELECT * FROM documento WHERE titulo = '$this->titulo' AND id != $this->idDoc";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE documento SET titulo = ? ,texto= ? WHERE id = $this->idDoc";
            $arrData = array($this->titulo,
                $this->texto);
            $request = $this->update($sql, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }

    public function insertImage(int $idAlumP, string $nombre) {
        $this->idPlan = $idAlumP;
        $this->titulo = $nombre;
        $query_insert = "INSERT INTO imagen(nombre, alumno_plan_id) VALUES (?,?)";
        $arrData = array($this->titulo,
            $this->idPlan);
        $request = $this->insert($query_insert, $arrData);
        return $request;
    }

    public function deleteImage(int $idAlumP, string $nombre) {
        $this->idPlan = $idAlumP;
        $this->titulo = $nombre;
        $query = "DELETE FROM imagen WHERE alumno_plan_id = $this->idPlan AND nombre = '$this->titulo'";
        $request_delete = $this->delete($query);
        return $request_delete;
    }

    public function removeDocumentacion(int $idAlumP, int $idDoc) {
        $this->idPlan = $idAlumP;
        $this->idDoc = $idDoc;
        $sql = "SELECT * FROM calificacion c 
               INNER JOIN documento dc ON c.documento_id = dc.id 
              WHERE dc.alumno_plan_id = $this->idPlan";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sqlDelImage = "DELETE FROM imagen WHERE alumno_plan_id = $this->idPlan";
            $this->delete($sqlDelImage);
            $sqlDelDoc = "DELETE FROM documento WHERE id = $this->idDoc";
            $requestDel = $this->delete($sqlDelDoc);
            if ($requestDel) {
                $request = 'ok';
            } else {
                $request = 'error';
            }
        } else {
            $request = "exist";
        }
        return $request;
    }

    public function selectCalTarea(int $idTarea) {
        $this->idTarea = $idTarea;
        $sql = "SELECT t.plan_id ,t.tarea_id ,ta.nombre, t.status, n.nota01,
               n.nota02, n.nota03, n.promedio FROM bitacora bt INNER JOIN tarea_plan t 
              ON bt.tarea_id = t.tarea_id INNER JOIN tarea ta ON bt.tarea_id = ta.id 
              INNER JOIN nota n ON bt.id = n.bitacora_id WHERE t.tarea_id = $this->idTarea";
        $request = $this->select($sql);
        return $request;
    }

    /* Funciones para el perfil "Profesor" */

    public function selectDocAlum(int $idAlum) {
        $this->idAlum = $idAlum;
        $sql = "SELECT doc.id, doc.titulo, doc.texto , doc.alumno_plan_id FROM documento doc 
               INNER JOIN alumno_plan al ON doc.alumno_plan_id = al.id INNER JOIN alumno a ON al.alumno_id = a.id 
               WHERE a.persona_id  = $this->idAlum";
        $request = $this->select($sql);
        return $request;
    }

    public function insertNotaDoc(int $idDoc, string $comentario, float $nota) {
        $this->idDoc = $idDoc;
        $this->comentarioNota = $comentario;
        $this->nota01 = $nota;
        $sql = "SELECT * FROM calificacion 
               WHERE comentario = '$this->comentarioNota' AND documento_id = $this->idDoc";
        $request = $this->select_all($sql);
        $return = 0;
        if (empty($request)) {
            $sqlInsert = "INSERT INTO calificacion(nota, comentario, documento_id) VALUES (?,?,?)";
            $arrData = array($this->nota01,
                $this->comentarioNota,
                $this->idDoc);
            $request_insert = $this->insert($sqlInsert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateNotaDoc(int $idNotaDoc, string $comentario, float $nota) {
        $this->idNote = $idNotaDoc;
        $this->comentarioNota = $comentario;
        $this->nota01 = $nota;
        $sql = "SELECT * FROM calificacion WHERE comentario = '$this->comentarioNota' AND id != $this->idNote";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE calificacion SET comentario = ? ,nota= ? WHERE id = $this->idNote";
            $arrData = array($this->comentarioNota,
                $this->nota01);
            $request = $this->update($sql, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }

    public function removeNoteDocumentation(int $idDoc) {
        $this->idDoc = $idDoc;
        $request = 0;
        $sqlDelDoc = "DELETE FROM calificacion WHERE documento_id = $this->idDoc";
        $requestDel = $this->delete($sqlDelDoc);
        if ($requestDel) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }

    public function selectAlumNotes(int $idAlum) {
        $this->idAlum = $idAlum;
        $sql = "SELECT per.id, per.rut, per.nombre, per.apellido, per.correo, pl.nombre as nombreP, es.nombre as especialidad, cs.nombre as curso, al.fono,
               al.fono02, dp.id as iddetalleplan, dp.plan_id, guiaPersona.nombre as nombreGuia, guiaPersona.apellido as apellidoGuia, profesorPersona.nombre as nombreProfesor,
               profesorPersona.apellido as apellidoProfesor FROM alumno_plan dp INNER JOIN alumno al ON dp.alumno_id = al.id 
               INNER JOIN persona per ON al.persona_id = per.id INNER JOIN plan pl ON dp.plan_id = pl.id INNER JOIN especialidad es ON al.especialidad_id = es.id 
               INNER JOIN curso cs ON al.curso_id = cs.id LEFT JOIN guia ON guia.id = al.guia_id 
               LEFT JOIN persona guiaPersona ON guia.persona_id = guiaPersona.id LEFT JOIN profesor ON profesor.id = al.profesor_id 
               LEFT JOIN persona profesorPersona ON profesor.persona_id = profesorPersona.id WHERE al.persona_id = $this->idAlum";
        $request = $this->select($sql);


        return $request;
    }

    public function selectTareasAlum(int $idPlan) {
        $this->idPlan = $idPlan;
        $sqlTareasN = "SELECT t.plan_id ,t.tarea_id ,ta.nombre, t.status,bt.texto as bitacora, n.nota01, n.nota02, n.nota03, n.promedio,
                     n.comentario FROM bitacora bt INNER JOIN tarea_plan t ON bt.tarea_id = t.tarea_id INNER JOIN tarea ta ON bt.tarea_id = ta.id 
                     INNER JOIN nota n ON bt.id = n.bitacora_id WHERE t.plan_id = $this->idPlan";
        $requestTareas = $this->select_all($sqlTareasN);
        return $requestTareas;
    }

}
