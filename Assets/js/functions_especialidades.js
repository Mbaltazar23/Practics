let tableEspecialidades;
let rowTable = "";
document.addEventListener('DOMContentLoaded', function () {
    tableEspecialidades = $('#tableEspecialidades').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Especialidades/getEspecialidades",
            "dataSrc": ""
        },
        "columns": [
            {"data": "nro"},
            {"data": "nombre"},
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

    //NUEVO ESPECIALIDAD
    let formEspecialidades = document.querySelector("#formEspecialidades");
    formEspecialidades.onsubmit = function (e) {
        e.preventDefault();
        let nombreCategoria = $("#txtNombre").val();

        if (nombreCategoria == "") {
            swal("Atención", "Debe ingresar el nombre al curso.", "error");
            return false;
        } else {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Especialidades/setEspecialidad';
            let formData = new FormData(formEspecialidades);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        tableEspecialidades.api().ajax.reload();
                        $('#modalFormEspecialidades').modal("hide");
                        formEspecialidades.reset();
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

function fntViewInfo(nro, idEspecialidad) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Especialidades/getEspecialidad/' + idEspecialidad;
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
                document.querySelector("#celNombre").innerHTML = objData.data.nombre;
                document.querySelector("#celFecha").innerHTML = objData.data.fecha;
                document.querySelector("#celHora").innerHTML = objData.data.hora;
                document.querySelector("#celEstado").innerHTML = estado;
                $('#modalViewEspecialidad').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntEditInfo(element, idEspecialidad) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').innerHTML = "Actualizar Especialidad";
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "  Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Especialidades/getEspecialidad/' + idEspecialidad;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idEspecialidad").value = objData.data.id;
                document.querySelector("#txtNombre").value = objData.data.nombre.toString().toLowerCase();
                $('#modalFormEspecialidades').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntDelInfo(idEspecialidad) {
    swal({
        title: "Inhabilitar Especialidad",
        text: "¿Realmente quiere inhabilitar esta especialidad?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Especialidades/setStatusEspecialidad';
            let strData = "idEspecialidad=" + idEspecialidad + "&status=0";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Inhabilitado!", objData.msg, "success");
                        tableEspecialidades.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function fntActivateInfo(idEspecialidad) {
    swal({
        title: "Habilitar Especialidad",
        text: "¿Realmente quiere habilitar esta especialidad?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Especialidades/setStatusEspecialidad';
            let strData = "idEspecialidad=" + idEspecialidad + "&status=1";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Habilitado!", objData.msg, "success");
                        tableEspecialidades.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function openModal() {
    document.querySelector('#idEspecialidad').value = "";
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "  Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Especialidad";
    document.querySelector("#formEspecialidades").reset();
    $('#modalFormEspecialidades').modal('show');
}


function generarReportEspecialidades() {
    $.post(base_url + "/Especialidades/getEspecialidadesReport",
            function (response) {
                var fecha = new Date();
                var especialidades = JSON.parse(response);
                //console.log(tecnicos);
                let estado = "";
                var pdf = new jsPDF();
                pdf.text(20, 20, "Reportes de las Especialidades Registrados");
                var data = [];
                var columns = ["NRO", "NOMBRE", "FECHA/HORA", "ESTADO"];
                for (let i = 0; i < especialidades.length; i++) {
                    if (especialidades[i].status == 1) {
                        estado = 'ACTIVO';
                    } else {
                        estado = 'INACTIVO';
                    }
                    data[i] = [(i + 1), especialidades[i].nombre, especialidades[i].fecha + " " + especialidades[i].hora, estado];
                }
                pdf.autoTable(columns, data,
                        {margin: {top: 40}}
                );
                pdf.text(20, 190, "Fecha de Creacion : " + fecha.getDate() + "/" + (fecha.getMonth() + 1) + "/" + fecha.getFullYear());
                pdf.save('ReporteEspecialidades.pdf');
                swal('Exito', "Reporte Imprimido Exitosamente..", 'success');
            }
    );
}
