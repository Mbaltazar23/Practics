let tableAlumns;
let tableTareasAl;
let tableTareasP;
let rowTable = "";
document.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector("#tableRol").getAttribute("rolTable")) {
        let tableId = "#tableAlumnsGui";
        let option = document.querySelector("#tableRol").getAttribute("rolTable");
        //console.log(option);
        if (option != "rolGuia") {
            tableId = "#tableAlumnsPro";
            cargarTable(tableId);
        } else {
            cargarTable(tableId);
        }
    }

    if (document.querySelector("#formAlumnP")) {
        let formAlumnP = document.querySelector("#formAlumnP");
        formAlumnP.onsubmit = function (e) {
            e.preventDefault();
            let listPlanes = document.querySelector("#listPlanes").value;
            let txtDescripcionP = $("#txtDescripcionP").val();

            if (listPlanes == "" || txtDescripcionP == "") {
                swal("Atención", "Todos los campos son obligatorios.", "error");
                return false;
            } else {
                let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                let ajaxUrl = base_url + '/Planes/setDetailPlanAlum';
                let formData = new FormData(formAlumnP);
                request.open("POST", ajaxUrl, true);
                request.send(formData);
                request.onreadystatechange = function () {
                    if (request.readyState == 4 && request.status == 200) {

                        let objData = JSON.parse(request.responseText);
                        if (objData.status)
                        {
                            tableAlumns.api().ajax.reload();
                            $('#modalFormAlumnoP').modal("hide");
                            formAlumnP.reset();
                            swal("Exito !!", objData.msg, "success");
                        } else {
                            swal("Error", objData.msg, "error");
                        }
                    }
                    return false;
                };
            }
        };
    }

    if (document.querySelector("#formNotas")) {
        let formNotas = document.querySelector("#formNotas");
        formNotas.onsubmit = function (e) {
            e.preventDefault();
            let listNotas = document.querySelector("#listNotasC").value;
            let comentario = $("#txtComent").val();
            let nota1 = document.querySelector("#nota1").value;
            let nota2 = document.querySelector("#nota2").value;
            let nota3 = document.querySelector("#nota3").value;

            if (listNotas == "" || comentario == "" || comentario.length < 3) {
                swal("Atención", "Todos estos campos son obligatorios !!", "error");
                return false;
            } else if (!nota1 && !nota2 && !nota3) {
                swal("Atención", "Debe evaluar al menos con una nota !!", "error");
                return false;
            } else {
                let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                let ajaxUrl = base_url + '/Estudios/setNotaBitacora';
                let formData = new FormData(formNotas);
                request.open("POST", ajaxUrl, true);
                request.send(formData);
                request.onreadystatechange = function () {
                    if (request.readyState == 4 && request.status == 200) {

                        let objData = JSON.parse(request.responseText);
                        if (objData.status)
                        {
                            tableTareasAl.api().ajax.reload();
                            $('#modalNotasTa').modal("hide");
                            formNotas.reset();
                            swal("Exito !!", objData.msg, "success");
                        } else {
                            swal("Error", objData.msg, "error");
                        }
                    }
                    return false;
                };
            }
        };
    }

    if (document.querySelector("#formNotaDoc")) {
        let formNotaDoc = document.querySelector("#formNotaDoc");
        formNotaDoc.onsubmit = function (e) {
            e.preventDefault();
            let idDoc = document.querySelector("#idDocN").value;
            let txtNota = document.querySelector("#txtNota").value;
            let txtComentarioDoc = document.querySelector("#txtComentarioDoc").value;

            if (txtNota == "" || txtComentarioDoc == "") {
                swal("Atención", "Todos estos campos son obligatorios !!", "error");
                return false;
            } else {
                let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                let ajaxUrl = base_url + '/Estudios/setNoteDocument';
                let formData = new FormData(formNotaDoc);
                request.open("POST", ajaxUrl, true);
                request.send(formData);
                request.onreadystatechange = function () {
                    if (request.readyState == 4 && request.status == 200) {

                        let objData = JSON.parse(request.responseText);
                        if (objData.status)
                        {
                            $('#modalNotaDoc').modal("hide");
                            formNotaDoc.reset();
                            getNoteDocument(idDoc);
                            swal("Exito !!", objData.msg, "success");
                        } else {
                            swal("Error", objData.msg, "error");
                        }
                    }
                    return false;
                };
            }
        };
    }
}, false);


function cargarTable(idTable) {
    tableAlumns = $(idTable).dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Alumnos/getAlumnosRol",
            "dataSrc": ""
        },
        "columns": [
            {"data": "rut"},
            {"data": "nombres%"},
            {"data": "fono"},
            {"data": "curso"},
            {"data": "status"},
            {"data": "options"}
        ],
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    });
}

/* Funciones para el perfil "Profesor" */

function fntViewInfo(idalu) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Alumnos/getAlumno/' + idalu;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {

                let estado = objData.data.status == 1 ?
                        '<span class="badge badge-success">Activo</span>' :
                        '<span class="badge badge-dark">Con Plan</span>';
                $("#celPlan").hide();

                document.querySelector("#celRutA").innerHTML = objData.data.rut;
                document.querySelector("#celNombreA").innerHTML = objData.data.nombre + " " + objData.data.apellido;
                document.querySelector("#celEspecialidadA").innerHTML = objData.data.especialidad;
                document.querySelector("#celCursoA").innerHTML = objData.data.curso;
                document.querySelector("#celTelefonosA").innerHTML = objData.data.fono02 != "+569" ? objData.data.fono + " - " + objData.data.fono02 : objData.data.fono;
                document.querySelector("#labalTelefonoA").innerHTML = objData.data.fono02 != "+569" ? "Telefonos" : "Telefono";
                document.querySelector("#celCursoA").innerHTML = objData.data.curso;
                document.querySelector("#celTutorA").innerHTML = objData.data.detailTutor.nombre + " " + objData.data.detailTutor.apellido;
                document.querySelector("#celGuiaA").innerHTML = objData.data.detailGuia.nombre + " " + objData.data.detailGuia.apellido;
                document.querySelector("#celEstadoA").innerHTML = estado;
                $('#modalViewAlumno').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}


function fntViewDoc(idAlum) {
    document.querySelector("#titleD").innerHTML = "Datos del Documento Subido";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Estudios/getDocumentCalf/' + idAlum;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                let htmlImage = "";

                document.querySelector("#idDc").value = objData.data.id;
                document.querySelector("#celTitulo").innerHTML = objData.data.titulo;
                document.querySelector("#celTexto").innerHTML = objData.data.texto;

                if (objData.data.imagesDoc.length > 0) {
                    let images = objData.data.imagesDoc;
                    for (let p = 0; p < images.length; p++) {
                        htmlImage += `<img src="${images[p].url_image}" width="140" height="90" style="padding: 5px"/>`;
                    }
                    $("#modalViewDoc .modal-dialog").addClass("modal-lg");
                } else {
                    htmlImage = "No se cuentan con imagenes registradas";

                    $("#modalViewDoc .modal-dialog").removeClass("modal-lg");
                }

                getNoteDocument(objData.data.id);

                document.querySelector("#celImagenes").innerHTML = htmlImage;
            }
        }
    };
    $("#modalViewDoc").modal('show');
}


function getNoteDocument(idDoc) {
    document.querySelector("#celBTN").innerHTML = "";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Estudios/getNoteDocument/' + idDoc;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
                let objDoc = objData.data.documento_id;
                document.querySelector("#celBTN").innerHTML = `
                  <div class="btn-group" role="group">
                    <div class="btn-group" role="group">
                        <button class="btn btn-primary dropdown-toggle" type="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-plus-circle"></i>&nbsp;&nbsp;Nota del Documento</button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" onclick="viewFormNoteUpdate(${objDoc})">Actualizar Nota</a>
                            <a class="dropdown-item" onclick="removerNota()">Remover Nota</a>
                        </div>
                    </div>
                   </div>`;
            } else {
                document.querySelector("#celBTN").innerHTML = `
                  <div class="btn-group" role="group">
                    <div class="btn-group" role="group">
                        <button class="btn btn-primary dropdown-toggle" type="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-plus-circle"></i>&nbsp;&nbsp;Nota del Documento</button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" onclick="viewFormNoteRegister()">Calificar</a>
                        </div>
                    </div>
                   </div>`;
            }
        }
    };
}

function viewFormNoteRegister() {
    let idDoc = document.querySelector("#idDc").value;
    document.querySelector("#titleND").innerHTML = "Nueva Nota del Documento";
    document.querySelector("#btnActionFormDoc").classList.replace("btn-primary", "btn-info");
    document.querySelector("#formNotaDoc").reset();
    document.querySelector("#idDocN").value = idDoc;
    document.querySelector("#idNoteD").value = "";
    document.querySelector("#btnTextDoc").innerHTML = "   Evaluar";
    $("#modalNotaDoc").modal('show');
}

function viewFormNoteUpdate(id) {
    document.querySelector("#titleND").innerHTML = "Actualizar Nota del Documento";
    document.querySelector("#btnActionFormDoc").classList.replace("btn-info", "btn-primary");
    document.querySelector("#btnTextDoc").innerHTML = "   Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Estudios/getNoteDocument/' + id;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#idNoteD").value = objData.data.id;
                document.querySelector("#idDocN").value = objData.data.documento_id;
                document.querySelector("#txtNota").value = objData.data.nota;
                document.querySelector("#txtComentarioDoc").value = objData.data.comentario.toString().toLowerCase();
                $("#modalNotaDoc").modal('show');
            }
        }
    };
}

function removerNota() {
    let idDoc = document.querySelector("#idDc").value;
    swal({
        title: "Eliminar Nota",
        text: "¿Realmente quiere borrar la nota del documento subido ?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Estudios/delNoteDoc';
            let strData = "idDoc=" + idDoc;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Exito !!", objData.msg, "success");
                        getNoteDocument(idDoc);
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function fntViewNoteP(idAlum) {
    $("#tableDetalleN tbody").empty();
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Estudios/getAlumnTarea/' + idAlum;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
                //console.log(objData.data);
                let objPlan = objData.data;
                document.querySelector("#planAlumN").textContent = objPlan.nombreP;
                $('#tableDetalleN tbody').append(`<tr>
                                            <td>${objPlan.nombre + " " + objPlan.apellido}</td>
                                            <td>${objPlan.especialidad}</td>
                                            <td>${objPlan.curso}</td>
                                            <td>${objPlan.nombreGuia + " " + objPlan.apellidoGuia}</td>
                                            <td>${objPlan.nombreProfesor + " " + objPlan.apellidoProfesor}</td>
                                        </tr>`);
                cargarTareasCal(objData.data.plan_id);
            }
        }
    };
    $("#modalAlumTareasCal").modal('show');
}

function cargarTareasCal(idPlan) {
    tableTareasAl = $('#tableTareasN').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "lengthChange": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Estudios/getTareasCal/" + idPlan,
            "dataSrc": ""
        },
        "columns": [
            {"data": "nro"},
            {"data": "nombre"},
            {"data": "status"},
            {"data": "bitacora"},
            {"data": "notas"},
            {"data": "promedio"}
        ],
        "resonsieve": "true",
        "searching": false,
        "bDestroy": true,
        "iDisplayLength": 3,
        "order": [[0, "asc"]]
    });
}

/* Funciones para el perfil guia*/

function fntViewInfoP(idalu) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Alumnos/getAlumnoP/' + idalu;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                let objGuia = objData.data.detailGuia;
                let objTutor = objData.data.detailTutor;

                let estado = objData.data.status == 1 ?
                        '<span class="badge badge-success">Activo</span>' :
                        '<span class="badge badge-dark">Con Plan</span>';

                $("#celPlan").show();

                document.querySelector("#celRutA").innerHTML = objData.data.rut;
                document.querySelector("#celNombreA").innerHTML = objData.data.nombre + " " + objData.data.apellido;
                document.querySelector("#celEspecialidadA").innerHTML = objData.data.especialidad;
                document.querySelector("#celCursoA").innerHTML = objData.data.curso;
                document.querySelector("#celTelefonosA").innerHTML = objData.data.fono02 != "+569" ? objData.data.fono + " - " + objData.data.fono02 : objData.data.fono;
                document.querySelector("#labalTelefonoA").innerHTML = objData.data.fono02 != "+569" ? "Telefonos" : "Telefono";
                document.querySelector("#celCursoA").innerHTML = objData.data.curso;
                document.querySelector("#celPlanAlum").innerHTML = objData.data.nombreP;
                document.querySelector("#celTutorA").innerHTML = objTutor.nombre + " " + objTutor.apellido;
                document.querySelector("#celGuiaA").innerHTML = objGuia.nombre + " " + objGuia.apellido;
                document.querySelector("#celEstadoA").innerHTML = estado;
                $('#modalViewAlumno').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}


function fntPlanAlumV(idalu) {
    $("#tableDetalleP tbody").empty();
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Alumnos/getAlumnoP/' + idalu;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                let objPlan = objData.data;
                //console.log(objPlan);

                document.querySelector("#idPlanT").value = objPlan.plan_id;

                document.querySelector("#planAlum").textContent = objPlan.nombreP;

                $('#tableDetalleP tbody').append(`<tr>
                                            <td>${objPlan.nombre + " " + objPlan.apellido}</td>
                                            <td>${objPlan.especialidad}</td>
                                            <td>${objPlan.curso}</td>
                                            <td>${objPlan.detailGuia.nombre + " " + objPlan.detailGuia.apellido}</td>
                                            <td>${objPlan.detailTutor.nombre + " " + objPlan.detailGuia.apellido}</td>
                                        </tr>`);
                cargarTareasPlan(objPlan.plan_id);
                $("#modalAlumPlanT").modal('show');
            }
        }
    };
}

function cargarTareasPlan(idPlan) {
    tableTareasAl = $('#tableTareasP').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "lengthChange": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Tareas/getTareasPlan/" + idPlan,
            "dataSrc": ""
        },
        "columns": [
            {"data": "nro"},
            {"data": "nombre"},
            {"data": "status"},
            {"data": "options"}
        ],
        "resonsieve": "true",
        "searching": false,
        "bDestroy": true,
        "iDisplayLength": 3,
        "order": [[0, "asc"]]
    });
}


function fntPlanTareasV(idalum) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Alumnos/getAlumnoP/' + idalum;
    document.querySelector("#titleP_Alum").innerHTML = "Tareas subidas del Alumno";
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                let objPlan = objData.data;
                document.querySelector("#alumnoP").innerHTML = objPlan.nombre + " " + objPlan.apellido;
                document.querySelector("#nombrePlanP").innerHTML = objPlan.nombreP;
                cargarTareasP(objPlan.plan_id);
            }
        }
    };
    $("#modalPlanAlumV").modal('show');
}

function cargarTareasP(idPlan) {
    tableTareasAl = $('#tableTareasA').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "lengthChange": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Estudios/getTareasActives/" + idPlan,
            "dataSrc": ""
        },
        "columns": [
            {"data": "nro"},
            {"data": "nombre"},
            {"data": "status"},
            {"data": "options"}
        ],
        "resonsieve": "true",
        "searching": false,
        "bDestroy": true,
        "iDisplayLength": 3,
        "order": [[0, "asc"]]
    });
}

function fntTareaCal(idTarea) {
    document.querySelector("#titleNT").innerHTML = " Evaluar Tarea";
    document.querySelector("#btnTextT").innerHTML = "  Evaluar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Estudios/getTareaBitacora/' + idTarea;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                let objTarea = objData.data;
//console.log(objTarea);
                document.querySelector("#formNotas").reset();
                document.querySelector("#idNote").value = "";
                document.querySelector("#idBit").value = objTarea.idBit;
                document.querySelector("#idTareaT").value = objTarea.tarea_id;
                document.querySelector("#idPlanN").value = objTarea.plan_id;
                document.querySelector("#txtTarea").value = objTarea.nombre;
                document.querySelector("#txtBita").value = objTarea.texto;
            }
        }
    };
    document.querySelector("#listNotasC").value = "0";
    ocultarCampos();
    $("#modalNotasTa").modal('show');
}

function fntTareaCalUp(idTarea) {
    document.querySelector("#titleNT").innerHTML = " Actualizar Nota";
    document.querySelector("#btnTextT").innerHTML = "  Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Estudios/getTareaBitacora/' + idTarea;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                //console.log(objData.data);
                let notasCount = 0;
                let objTarea = objData.data;
                document.querySelector("#idBit").value = objTarea.idBit;
                document.querySelector("#idTareaT").value = objTarea.tarea_id;
                document.querySelector("#idPlanN").value = objTarea.plan_id;
                document.querySelector("#txtTarea").value = objTarea.nombre;
                document.querySelector("#txtBita").value = objTarea.texto;
                let detalleNota = objTarea.detalleNota;

                if (detalleNota) {
                    document.querySelector("#idNote").value = detalleNota.id;
                    let notasCount = 0;
                    for (let key in detalleNota) {
                        if (detalleNota.hasOwnProperty(key) && detalleNota[key] !== null && key.startsWith("nota")) {
                            notasCount++;
                        }
                    }
                    //console.log(notasCount);
                    // Mostrar los campos de notas
                    mostrarCamposDeNotas(notasCount);
                    document.querySelector("#listNotasC").value = notasCount;
                    // mostrar los valores de las notas correspondientes
                    document.getElementById("nota1").value = detalleNota.nota01 || "";
                    document.getElementById("nota2").value = detalleNota.nota02 || "";
                    document.getElementById("nota3").value = detalleNota.nota03 || "";
                    if (detalleNota.texto) {
                        document.getElementById("txtBita").value = detalleNota.texto;
                    }
                    if (detalleNota.comentario) {
                        document.getElementById("txtComent").value = detalleNota.comentario;
                    }
                }
            }
        }
    };
    $("#modalNotasTa").modal('show');
}

function fntDelCal(idTarea) {
    swal({
        title: "Eliminar Nota",
        text: "¿Realmente quiere remover esta nota?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Estudios/removeNoteBitacora';
            let strData = "idTarea=" + idTarea;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Exito !!", objData.msg, "success");
                        tableTareasAl.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}


function fntViewCal(nro, idTarea) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Estudios/getCalificacion/' + idTarea;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                //console.log(objData.data);
                let notas = '';

                for (const [key, value] of Object.entries(objData.data)) {
                    if (key.includes('nota') && value !== null) {
                        notas += `<li> ${value}</li>`;
                    }
                }
                document.querySelector("#celNroCal").innerHTML = nro;
                document.querySelector("#celNombreTCal").innerHTML = objData.data.nombre;
                document.querySelector("#celEstadoTCal").innerHTML = '<span class="badge badge-warning">Calificada</span>';
                document.querySelector('#celNotasCal').innerHTML = `<ul>${notas}</ul>`;
                document.querySelector("#celPromedioCal").innerHTML = objData.data.promedio;
                $('#modalViewCal').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function mostrarCamposDeNotas(numNotas) {
    // Mostrar o esconder el div de notas
    const formNotes = document.getElementById("formNotes");
    const txtComent = document.getElementById("txtComent");
    if (numNotas > 0) {
        formNotes.classList.remove('hidden');
        txtComent.classList.remove('hidden');

        // Mostrar los campos de notas necesarios
        for (let i = 1; i <= 3; i++) {
            const nota = document.getElementById(`nota${i}`);
            const notaLabel = document.querySelector(`label[for="nota${i}"]`);

            if (i <= numNotas) {
                nota.classList.remove('hidden');
                notaLabel.classList.remove('hidden');
            } else {
                nota.classList.add('hidden');
                notaLabel.classList.add('hidden');
            }
        }
    } else {
        formNotes.classList.add('hidden');
        txtComent.classList.add('hidden');

        // Ocultar todos los campos de notas
        for (let i = 1; i <= 3; i++) {
            const nota = document.getElementById(`nota${i}`);
            const notaLabel = document.querySelector(`label[for="nota${i}"]`);
            nota.classList.add('hidden');
            notaLabel.classList.add('hidden');
        }
    }
}

function ocultarCampos() {
    document.getElementById("formNotes").classList.add('hidden');
    document.getElementById("txtComent").classList.add('hidden');
    document.getElementById(`nota1`).classList.add('hidden');
    document.getElementById(`nota2`).classList.add('hidden');
    document.getElementById(`nota3`).classList.add('hidden');
    document.querySelector(`label[for="nota1"]`).classList.add('hidden');
    document.querySelector(`label[for="nota2"]`).classList.add('hidden');
    document.querySelector(`label[for="nota3"]`).classList.add('hidden');
}



function fntActiveTarea(idtarea) {
    swal({
        title: "Habilitar Tarea",
        text: "¿Realmente quiere habilitar esta tarea?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let idPlan = document.querySelector("#idPlanT").value;
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Planes/setStatusDetailTarea';
            let strData = "idTarea=" + idtarea + "&status=2&idPlan=" + idPlan;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Habilitado!", objData.msg, "success");
                        tableTareasAl.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function fntHideTarea(idtarea) {
    swal({
        title: "Ocultar Tarea",
        text: "¿Realmente quiere ocultar esta tarea?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let idPlan = document.querySelector("#idPlanT").value;
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Planes/setStatusDetailTarea';
            let strData = "idTarea=" + idtarea + "&status=1&idPlan=" + idPlan;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Exito !", objData.msg, "success");
                        tableTareasAl.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function generarReportRolAlumns() {
    $.post(base_url + "/Alumnos/getAlumnoReportRol",
            function (response) {
                var fecha = new Date();
                var Alumnos = JSON.parse(response);
                if (Alumnos.length > 0) {
                    console.log(Alumnos);
                    let estado = "";
                    var pdf = new jsPDF();
                    pdf.text(20, 20, "Reportes de los Alumnos Registrados");
                    var data = [];
                    let telefons = "";
                    var columns = ["RUT", "NOMBRES", "TELEFONO", "CURSO", "ESTADO"];
                    for (let i = 0; i < Alumnos.length; i++) {
                        if (Alumnos[i].status == 1) {
                            estado = 'ACTIVO';
                        } else {
                            estado = 'CON PLAN';
                        }
                        telefons = Alumnos[i].fono02 != "+569" ? Alumnos[i].fono + " - " + Alumnos[i].fono02 : Alumnos[i].fono;
                        data[i] = [Alumnos[i].rut, Alumnos[i].nombre + " " + Alumnos[i].apellido, telefons, Alumnos[i].curso, estado];
                    }
                    pdf.autoTable(columns, data,
                            {margin: {top: 40}}
                    );
                    pdf.text(20, 190, "Fecha de Creacion : " + fecha.getDate() + "/" + (fecha.getMonth() + 1) + "/" + fecha.getFullYear());
                    pdf.save('ReporteAlumnos.pdf');
                    swal('Exito', "Reporte Imprimido Exitosamente..", 'success');
                } else {
                    swal("Error !!", "No se cuentan con alumnos afiliados a usted !!", "error");
                }
            }
    );
}


