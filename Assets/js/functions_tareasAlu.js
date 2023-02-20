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
        "order": [[0, "desc"]]
    });

    let formTareasB = document.querySelector("#formTareasB");
    formTareasB.onsubmit = function (e) {
        e.preventDefault();
        let txtBitacora = $("#txtBitacora").val();

        if (txtBitacora == "") {
            swal("Error !!", "Debe ingresar una Bitacora para la tarea...", "error");
            return false;
        } else {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Tareas/setBitacora';
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
                let estado = objData.data.detalleSub.status == 1 ?
                        '<span class="badge badge-success">Activo</span>' :
                        '<span class="badge badge-dark">Subido</span>';
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


function fntBicA(nro, idTarea) {
    document.querySelector('#titleModalB').textContent = "Subir Bitacora";
    document.querySelector('#btnActionFormB').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnTextB').textContent = "  Guardar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Tareas/getTarea/' + idTarea;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idTareaB").value = objData.data.idtarea;
                document.querySelector("#txtNro").value = nro;
                document.querySelector("#txtNombreT").value = objData.data.nombre;
                document.querySelector("#idSub").value = "";
                document.querySelector("#txtBitacora").value = "";
                $("#modalFormBitacora").modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}


function fntBicUp(nro, idTarea) {
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
                document.querySelector("#txtNro").value = nro;
                document.querySelector("#idTareaB").value = objData.data.idtarea;
                document.querySelector("#txtNombreT").value = objData.data.nombre;
                document.querySelector("#idSub").value = objData.data.detalleSub.idsubida;
                document.querySelector("#txtBitacora").value = objData.data.detalleSub.bitacora;
                $("#modalFormBitacora").modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntDelTarea(idTarea) {
    swal({
        title: "Eliminar Bitacora",
        text: "¿Realmente quiere remover esta bitacora?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Tareas/removeBitacoraTarea';
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