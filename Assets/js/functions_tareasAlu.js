let tableTareas;
let rowTable = "";
document.addEventListener('DOMContentLoaded', function () {
    tableTareas = $('#tableTareasAlu').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Tareas/getTareasAlu",
            "dataSrc": ""
        },
        "columns": [
            {"data": "nro"},
            {"data": "nombre"},
            {"data": "status"},
            {"data": "options"}
        ],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "asc"]]
    });

    let formTareasB = document.querySelector("#formTareasB");
    formTareasB.onsubmit = function (e) {
        e.preventDefault();
        let txtBitacora = $("#txtBitacora").val();
        let fecha = $("#txtFecha")
        if (txtBitacora == "" || fecha == "") {
            swal("Error !!", "Debe ingresar una Bitacora y Fecha para la tarea...", "error");
            return false;
        } else {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Estudios/setBitacora';
            let formData = new FormData(formTareasB);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        tableTareas.api().ajax.reload();
                        $('#modalFormBitacora').modal("hide");
                        formTareasB.reset();
                        swal("Exito !!", objData.msg, "success");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
                return false;
            };
        }
    };

    if (document.querySelector(".btnAddImage")) {
        let btnAddImage = document.querySelector(".btnAddImage");
        btnAddImage.onclick = function () {
            let key = Date.now();
            let newElement = document.createElement("div");
            if ($("#containerImages > div").length >= 4)
            {
                swal("Error !!", "Ya llego al limite de imagenes a subir...", "error");
                return false;
            } else {
                newElement.id = "div" + key;
                newElement.innerHTML = `
            <div class="prevImage"></div>
            <input type="file" name="foto" id="img${key}" class="inputUploadfile">
            <label for="img${key}" class="btnUploadfile"><i class="fas fa-upload "></i></label>
            <button class="btnDeleteImage notblock" type="button" onclick="fntDelItem('#div${key}')"><i class="fas fa-trash-alt"></i></button>`;
                // console.log($("#containerImages").find("img").length);
                document.querySelector("#containerImages").appendChild(newElement);
                document.querySelector("#div" + key + " .btnUploadfile").click();
                fntInputFile();
            }
        };
    }

    if (document.querySelector("#formDoc")) {
        let formDoc = document.querySelector("#formDoc");
        formDoc.onsubmit = function (e) {
            e.preventDefault();
            let txtTitulo = document.querySelector("#txtTitulo").value;
            let txtDocumento = document.querySelector("#txtDocumento").value;
            if (txtTitulo == "" || txtDocumento == "") {
                swal("Error !!", "Debe ingresar un titulo y un parrafo para subirlo...", "error");
                return false;
            } else {
                let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                let ajaxUrl = base_url + '/Estudios/setDocumentacion';
                let formData = new FormData(formDoc);
                request.open("POST", ajaxUrl, true);
                request.send(formData);
                request.onreadystatechange = function () {
                    if (request.readyState == 4 && request.status == 200) {
                        let objData = JSON.parse(request.responseText);
                        if (objData.status)
                        {
                            $('#modalFormDocument').modal("hide");
                            formDoc.reset();
                            swal("Exito !!", objData.msg, "success");
                            validateDocumentacion();
                        } else {
                            swal("Error", objData.msg, "error");
                        }
                    }
                    return false;
                };
            }
        };
    }

    validateDocumentacion();

}, false);

function fntViewInfo(nro, idTarea) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Tareas/getTarea/' + idTarea;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                let estado = "";
                if (objData.data.status == 2) {
                    estado = '<span class="badge badge-success">Pendiente</span>';
                } else if (objData.data.status == 3) {
                    estado = '<span class="badge badge-dark">Subida</span>';
                } else {
                    estado = '<span class="badge badge-warning">Calificada</span>';
                }

                document.querySelector("#celNroT").innerHTML = nro;
                document.querySelector("#celNombreT").innerHTML = objData.data.nombre;
                document.querySelector("#celEstadoT").innerHTML = estado;
                $('#modalViewTarea').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntViewInfoCal(nro, idTarea) {
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


function fntBicA(idTarea) {
    document.querySelector('#titleModalB').textContent = "Subir Bitacora";
    document.querySelector('#btnActionFormB').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnTextB').textContent = "  Subir";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Tareas/getTarea/' + idTarea;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idTareaB").value = objData.data.id;
                document.querySelector("#txtNombreT").value = objData.data.nombre;
                document.querySelector("#idSub").value = "";
                document.querySelector("#txtBitacora").value = "";
                $("#modalFormBitacora").modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
    let fechaActual = new Date().toISOString().split('T')[0];
    document.querySelector("#txtFecha").setAttribute("min", fechaActual);
    document.querySelector("#txtFecha").value = "";

}


function fntBicUp(idTarea) {
    document.querySelector('#titleModalB').textContent = "Actualizar Bitacora";
    document.querySelector('#btnActionFormB').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnTextB').textContent = "  Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Tareas/getTarea/' + idTarea;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idTareaB").value = objData.data.id;
                document.querySelector("#txtNombreT").value = objData.data.nombre;
                document.querySelector("#idSub").value = objData.data.detalleSub.id;
                document.querySelector("#txtFecha").value = objData.data.detalleSub.fecha;
                document.querySelector("#txtBitacora").value = objData.data.detalleSub.texto;
                $("#modalFormBitacora").modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
    let fechaActual = new Date().toISOString().split('T')[0];
    document.querySelector("#txtFecha").setAttribute("min", fechaActual);
}

function fntDelBit(idTarea) {
    swal({
        title: "Eliminar Bitacora",
        text: "¿Realmente quiere remover esta bitacora?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Estudios/removeBitacoraTarea';
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
                        tableTareas.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}


function validateDocumentacion() {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Estudios/getDocumentation';
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                if (objData.data != "") {
                    document.getElementById('btnDelDoc').classList.remove('hidden');
                    document.getElementById('txtBtnDoc').innerHTML = '<i class="fas fa-pencil-alt"></i> &nbsp;Actualizar';
                    let noteDocuments = objData.data.NoteDocument;
                    let txtDel = document.getElementById('txtDel');
                    let btnDelN = document.getElementById('btnDelN');
                    if (noteDocuments) {
                        txtDel.innerHTML = '<i class="fas fa-star-half-alt"></i> &nbsp;Ver Nota';
                        btnDelN.onclick = function () {
                            verNota(objData.data);
                        };
                    } else {
                        txtDel.innerHTML = '<i class="fas fa-trash"></i> &nbsp; Remover';
                        btnDelN.onclick = function () {
                            removerDocumentacion(objData.data.id);
                        };
                    }
                } else {
                    document.getElementById('btnDelDoc').classList.add('hidden');
                    document.getElementById('txtBtnDoc').innerHTML = '<i class="fas fa-upload"></i> &nbsp;Subir';
                }
            }
        }
    };

}

function getDocumentacion() {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Estudios/getDocumentation';
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                if (objData.data != "") {
                    updatedDocumentation(objData.data);
                } else {
                    registerDocumentation();
                }
            }
        }
    };
}


function registerDocumentation() {
    document.querySelector("#titleDoc").innerHTML = "Subir Documentacion";
    document.querySelector("#btnTextD").innerHTML = "  Subir";
    document.querySelector('#btnActionFormD').classList.replace("btn-primary", "btn-info");
    document.querySelector("#formDoc").reset();
    $("#modalFormDocument").modal('show');
}

function updatedDocumentation(arrDocumentation) {
    document.querySelector("#titleDoc").innerHTML = "Actualizar Documentacion";
    document.querySelector("#btnTextD").innerHTML = "  Actualizar";
    document.querySelector('#btnActionFormD').classList.replace("btn-info", "btn-primary");
    document.querySelector("#idDoc").value = arrDocumentation.id;
    document.querySelector("#txtTitulo").value = arrDocumentation.titulo.toString().toLowerCase();
    document.querySelector("#txtDocumento").value = arrDocumentation.texto.toString().toLowerCase();
    $("#modalFormDocument").modal('show');
}

function removerDocumentacion(idDoc) {
    swal({
        title: "Eliminar Documentacion",
        text: "¿Realmente quiere borrar el documento subido ?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Estudios/delDocumentacion';
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
                        validateDocumentacion();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function mostrarFormImages() {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Estudios/getDocumentation';
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                let objDocument = objData.data;
                document.querySelector("#nombreIMG").innerHTML = "Subir imagenes a la Documentacion";
                document.querySelector("#idPA").value = objDocument.alumno_plan_id;
                document.querySelector("#txtTit").innerHTML = objDocument.titulo;
                document.querySelector("#txtText").innerHTML = objDocument.texto;
                let htmlImage = "";
                //obtenemos las imagenes que se hayan registrado en el Gasto
                if (objDocument.imagesDoc.length > 0) {
                    let Images = objDocument.imagesDoc;
                    for (let o = 0; o < Images.length; o++) {
                        let key = Date.now() + o;
                        htmlImage += `<div id="div${key}">
                            <div class="prevImage">
                            <img src="${Images[o].url_image}" width="120" height="60"></img>
                            </div>
                            <br>
                            <button type="button" class="btnDeleteImage" onclick="fntDelItem('#div${key}')" imgname="${Images[o].nombre}">
                            <i class="fas fa-trash-alt"></i></button></div>`;
                    }
                }
                document.querySelector("#containerImages").innerHTML = htmlImage;
                document.querySelector("#containerGallery").classList.remove("notblock");
            }
        }
    };
    $("#modalDocumentIMG").modal('show');
}

function fntInputFile() {
    let inputUploadfile = document.querySelectorAll(".inputUploadfile");
    inputUploadfile.forEach(function (inputUploadfile) {
        inputUploadfile.addEventListener('change', function () {
            let idAlumP = document.querySelector("#idPA").value;
            let parentId = this.parentNode.getAttribute("id");
            let idFile = this.getAttribute("id");
            let uploadFoto = document.querySelector("#" + idFile).value;
            let fileimg = document.querySelector("#" + idFile).files;
            let prevImg = document.querySelector("#" + parentId + " .prevImage");
            let nav = window.URL || window.webkitURL;

            if (uploadFoto != '') {
                let type = fileimg[0].type;
                let name = fileimg[0].name;
                if (type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png') {
                    prevImg.innerHTML = "Archivo no válido";
                    uploadFoto.value = "";
                    return false;
                } else {
                    let objeto_url = nav.createObjectURL(this.files[0]);
                    prevImg.innerHTML = `<img class="loading" src="${base_url}/Assets/images/loading.svg" >`;

                    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                    let ajaxUrl = base_url + '/Estudios/setImage';
                    let formData = new FormData();
                    formData.append("idAlumP", idAlumP);
                    formData.append("foto", this.files[0]);
                    request.open("POST", ajaxUrl, true);
                    request.send(formData);
                    request.onreadystatechange = function () {
                        if (request.readyState != 4)
                            return;
                        if (request.status == 200) {
                            let objData = JSON.parse(request.responseText);
                            if (objData.status) {
                                prevImg.innerHTML = `<img src="${objeto_url}" width="140" height="60">`;
                                document.querySelector("#" + parentId + " .btnDeleteImage").setAttribute("imgname", objData.imgname);
                                document.querySelector("#" + parentId + " .btnUploadfile").classList.add("notblock");
                                document.querySelector("#" + parentId + " .btnDeleteImage").classList.remove("notblock");
                                swal("Exito !!", objData.msg, "success");
                            } else {
                                swal("Error", objData.msg, "error");
                            }
                        }
                    };
                }
            }
        });
    });
}


function fntDelItem(element) {
    swal({
        title: "Borrar Imagen",
        text: "¿Realmente quiere borrar la imagen subida?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let nameImg = document.querySelector(element + ' .btnDeleteImage').getAttribute("imgname");
            let idAlumP = document.querySelector("#idPA").value;
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Estudios/delFile';
            let formData = new FormData();
            formData.append('idAlumP', idAlumP);
            formData.append("file", nameImg);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState != 4)
                    return;
                if (request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        let itemRemove = document.querySelector(element);
                        itemRemove.parentNode.removeChild(itemRemove);
                        swal("Exito !!", objData.msg, "success");
                    } else {
                        swal("", objData.msg, "error");
                    }
                }
            };
        }
    });
}


function verNota(objDocNote) {
    document.querySelector("#celTit").innerHTML = objDocNote.titulo;
    document.querySelector("#celText").innerHTML = objDocNote.texto;
    document.querySelector("#celNotaD").innerHTML = objDocNote.NoteDocument.nota;
    document.querySelector("#celComentariosD").innerHTML = objDocNote.NoteDocument.comentario;
    document.querySelector("#celFechaD").innerHTML = objDocNote.NoteDocument.fecha +" a las "+objDocNote.NoteDocument.hora;
    $("#modalDocumentNote").modal('show');
}
