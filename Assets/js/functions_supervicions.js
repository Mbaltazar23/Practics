let tableSuperviciones;
let rowTable = "";
document.addEventListener('DOMContentLoaded', function () {
    tableSuperviciones = $('#tableSuperviciones').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Supervisiones/getSupervicions",
            "dataSrc": ""
        },
        "columns": [
            {"data": "nro"},
            {"data": "texto"},
            {"data": "fecha"},
            {"data": "hora"},
            {"data": "status"},
            {"data": "options"}
        ],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "asc"]]
    });

    //NUEVO SUPERVICION
    let formSuperviciones = document.querySelector("#formSupervisions");
    formSuperviciones.onsubmit = function (e) {
        e.preventDefault();
        let nombreCategoria = $("#txtSupervicion").val();
        let txtFecha = $("#txtFecha").val();

        if (nombreCategoria == "" || txtFecha == "") {
            swal("Atención", "Debe ingresar la supervicion a registrar.", "error");
            return false;
        } else {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Supervisiones/setSupervision';
            let formData = new FormData(formSuperviciones);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        tableSuperviciones.api().ajax.reload();
                        $('#modalFormSupervisiones').modal("hide");
                        formSuperviciones.reset();
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


function fntViewInfo(nro, idSupervicion) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Supervisiones/getSupervision/' + idSupervicion;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                let estado = objData.data.status == 1 ?
                        '<span class="badge badge-success">Activo</span>' :
                        '<span class="badge badge-danger">Inactivo</span>';

                document.querySelector("#celNro").innerHTML = nro;
                document.querySelector("#celNombre").innerHTML = objData.data.texto;
                document.querySelector("#celFecha").innerHTML = objData.data.fecha;
                document.querySelector("#celHora").innerHTML = objData.data.hora;
                document.querySelector("#celEstado").innerHTML = estado;
                $('#modalViewSupervision').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntEditInfo(element, idSupervicion) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').innerHTML = "Actualizar Supervisión";
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "  Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Supervisiones/getSupervision/' + idSupervicion;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idSupervision").value = objData.data.id;
                document.querySelector("#txtSupervicion").value = objData.data.texto.toString().toLowerCase();
                document.querySelector("#txtFecha").value = objData.data.fech;
                $('#modalFormSupervisiones').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntDelInfo(idSupervicion) {
    swal({
        title: "Inhabilitar Supervisión",
        text: "¿Realmente quiere inhabilitar esta supervisión?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Supervisiones/setStatusSupervicion';
            let strData = "idSupervision=" + idSupervicion + "&status=0";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Inhabilitado!", objData.msg, "success");
                        tableSuperviciones.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function fntActivateInfo(idSupervicion) {
    swal({
        title: "Habilitar Supervisión",
        text: "¿Realmente quiere habilitar esta supervisión?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Supervisiones/setStatusSupervicion';
            let strData = "idSupervision=" + idSupervicion + "&status=1";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Habilitado!", objData.msg, "success");
                        tableSuperviciones.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function openModal() {
    document.querySelector('#idSupervision').value = "";
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "  Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Supervicion";
    document.querySelector("#formSupervisions").reset();
    $('#modalFormSupervisiones').modal('show');
    setFechaBtn();
}



function setFechaBtn() {
    let fechaActual = new Date().toISOString().split('T')[0];
    document.querySelector("#txtFecha").setAttribute("min", fechaActual);
    document.querySelector("#txtFecha").value = "";
}