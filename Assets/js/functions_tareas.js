let tableTareas;
let rowTable = "";
document.addEventListener('DOMContentLoaded', function () {
    tableTareas = $('#tableTareas').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Tareas/getTareas",
            "dataSrc": ""
        },
        "columns": [
            {"data": "nro"},
            {"data": "nombre"},
            {"data": "status"},
            {"data": "options"}
        ],
        "iDisplayLength": 10,
        "order": [[0, "asc"]]
    });

    //NUEVO TAREA
    let formTareas = document.querySelector("#formTareas");
    formTareas.onsubmit = function (e) {
        e.preventDefault();
        let nombre = $("#txtNombreTarea").val();

        if (nombre == "") {
            swal("Atención", "Todos los campos son obligatorios.", "error");
            return false;
        } else {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Tareas/setTarea';
            let formData = new FormData(formTareas);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        tableTareas.api().ajax.reload();
                        $('#modalFormTareas').modal("hide");
                        formTareas.reset();
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
                let estado = objData.data.status == 1 ?
                        '<span class="badge badge-success">Activo</span>' :
                        '<span class="badge badge-danger">Inactivo</span>';

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

function fntEditInfo(element, Idtarea) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').textContent = "Actualizar Tarea";
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').textContent = "  Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Tareas/getTarea/' + Idtarea;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idTarea").value = objData.data.id;
                document.querySelector("#txtNombreTarea").value = objData.data.nombre.toString().toLowerCase();
                $('#modalFormTareas').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntDelInfo(idrol) {
    swal({
        title: "Inhabilitar Tarea",
        text: "¿Realmente quiere inhabilitar esta tarea?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Tareas/setStatusTareas';
            let strData = "idTarea=" + idrol + "&status=0";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Inhabilitado!", objData.msg, "success");
                        tableTareas.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function fntActivateInfo(idtarea) {
    swal({
        title: "Habilitar Tarea",
        text: "¿Realmente quiere habilitar esta tarea?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Tareas/setStatusTareas';
            let strData = "idTarea=" + idtarea + "&status=1";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Habilitado!", objData.msg, "success");
                        tableTareas.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function openModal() {
    document.querySelector('#idTarea').value = "";
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').textContent = "  Guardar";
    document.querySelector('#titleModal').textContent = "Nueva Tarea";
    document.querySelector("#formTareas").reset();
    $('#modalFormTareas').modal('show');
}


function generarReportTareas() {
    $.post(base_url + "/Tareas/getTareasReport",
            function (response) {
                var fecha = new Date();
                var tareas = JSON.parse(response);
                //console.log(tecnicos);
                if (tareas.length > 0) {
                    let estado = "";
                    var pdf = new jsPDF();
                    pdf.text(20, 20, "Reportes de los Tareas Registrados");
                    var data = [];
                    var columns = ["NRO", "NOMBRE", "ESTADO"];
                    for (let i = 0; i < tareas.length; i++) {
                        if (tareas[i].status == 1) {
                            estado = 'ACTIVO';
                        } else {
                            estado = 'INACTIVO';
                        }
                        data[i] = [(i + 1), tareas[i].nombre, estado];
                    }
                    pdf.autoTable(columns, data,
                            {margin: {top: 40}}
                    );
                    pdf.text(20, 190, "Fecha de Creacion : " + fecha.getDate() + "/" + (fecha.getMonth() + 1) + "/" + fecha.getFullYear());
                    pdf.save('ReporteTareas.pdf');
                    swal('Exito', "Reporte Imprimido Exitosamente..", 'success');
                } else {
                    swal("Error !!", "No se cuentan con tareas registradas !!", "error");
                }
            }
    );
}