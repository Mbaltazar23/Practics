let tablePlanes;
let tableTareasP;
let rowTable = "";
document.addEventListener('DOMContentLoaded', function () {
    tablePlanes = $('#tablePlanes').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Planes/getPlanes",
            "dataSrc": ""
        },
        "columns": [
            {"data": "nombre"},
            {"data": "descripcionPlan"},
            {"data": "fecha"},
            {"data": "hora"},
            {"data": "status"},
            {"data": "options"}
        ],
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    });

    //NUEVO PLAN
    let formPlanes = document.querySelector("#formPlanes");
    formPlanes.onsubmit = function (e) {
        e.preventDefault();
        let nombre = $("#txtNombrePlan").val();
        let descripcion = $("#txtDescripcionPlan").val();
        if (nombre == "" || descripcion == "" || descripcion.length < 5) {
            swal("Atención", "Todos los campos son obligatorios.", "error");
            return false;
        } else {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Planes/setPlan';
            let formData = new FormData(formPlanes);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        tablePlanes.api().ajax.reload();
                        $('#modalFormPlanes').modal("hide");
                        formPlanes.reset();
                        swal("Exito !!", objData.msg, "success");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
                return false;
            };
        }
    };
    fntTareas();
}, false);

function fntTareas() {
    if (document.querySelector("#listTareas")) {
        let ajaxUrl = base_url + '/Tareas/getSelectTareas';
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                $('.selectTareas select').html(request.responseText).fadeIn();
            }
        };
    }
}

function fntViewInfo(idPlan) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Planes/getPlan/' + idPlan;
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


                document.querySelector("#celNombreP").innerHTML = objData.data.nombre;
                document.querySelector("#celDescripcionP").innerHTML = objData.data.descripcionPlan;
                document.querySelector("#celFechaP").innerHTML = objData.data.fecha;
                document.querySelector("#celHoraP").innerHTML = objData.data.hora;

                document.querySelector("#celEstadoP").innerHTML = estado;
                $('#modalViewPlan').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntEditInfo(element, IdPlan) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').textContent = "Actualizar Plan";
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').textContent = "  Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Planes/getPlan/' + IdPlan;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idPlan").value = objData.data.id;
                document.querySelector("#txtNombrePlan").value = objData.data.nombre.toString().toLowerCase();
                document.querySelector("#txtDescripcionPlan").value = objData.data.descripcionPlan.toString().toLowerCase();
                $('#modalFormPlanes').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntDelInfo(idtarea) {
    swal({
        title: "Inhabilitar Plan",
        text: "¿Realmente quiere inhabilitar este plan?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Planes/setStatusPlanes';
            let strData = "idPlan=" + idtarea + "&status=0";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Inhabilitado!", objData.msg, "success");
                        tablePlanes.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function fntActivateInfo(idplan) {
    swal({
        title: "Habilitar Plan",
        text: "¿Realmente quiere habilitar este plan?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Planes/setStatusPlanes';
            let strData = "idPlan=" + idplan + "&status=1";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Habilitado!", objData.msg, "success");
                        tablePlanes.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function openModal() {
    document.querySelector('#idPlan').value = "";
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').textContent = "  Guardar";
    document.querySelector('#titleModal').textContent = "Nuevo Plan";
    document.querySelector("#formPlanes").reset();
    $('#modalFormPlanes').modal('show');
}


function fntTareasPlan(element, idPlan) {
    rowTable = element.parentNode.parentNode.parentNode;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Planes/getPlan/' + idPlan;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                document.querySelector("#idPlanT").value = objData.data.id;
                document.querySelector("#NombrePlan").value = objData.data.nombre;
                document.querySelector("#titleModalT").innerHTML = "Tareas para el " + objData.data.nombre;
                document.querySelector("#listTareas").value = "0";
                loadTareas(objData.data.id);
                $('#modalFormPlanT').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntViewTareas(element, idPlan) {
    rowTable = element.parentNode.parentNode.parentNode;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Planes/getPlan/' + idPlan;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
                let objTareas = objData.data.detailTareas;
                if (objTareas.length > 0) {

                    let estado = objData.data.status == 1 ?
                            '<span class="badge badge-success">Activo</span>' :
                            '<span class="badge badge-danger">Inactivo</span>';

                    document.querySelector("#nombreAlu").innerHTML = objData.data.nombre + " " + objData.data.apellido;
                    document.querySelector("#nombrePlan").innerHTML = objData.data.nombre;
                    document.querySelector("#correoAlu").innerHTML = objData.data.correo;
                    document.querySelector("#especialidadAlu").innerHTML = objData.data.especialidad;
                    document.querySelector("#cursoAlu").innerHTML = objData.data.curso;
                    document.querySelector("#profesorAlu").innerHTML = objData.data.detailTutor.nombre + " " + objData.data.detailTutor.apellido;
                    document.querySelector("#guiaAlu").innerHTML = objData.data.detailGuia.nombre + " " + objData.data.detailGuia.apellido;
                    document.querySelector("#statusP").innerHTML = estado;

                    //console.log(objTareas);
                    loadTareasActivas(objData.data.id);
                    $('#modalPlanTareasView').modal('show');
                } else {
                    swal("Error", objData.msgTareas, "error");
                }
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function loadTareas(idPlan) {
    tableTareasP = $('#table-tareas').dataTable({
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

$("#btnAgregarTarea").click(function (e) {
    e.preventDefault();
    let id_tarea = $("#listTareas option:selected").val();
    let idPlan = $("#idPlanT").val();
    let mensaje = "";
    let error = false;
    if (id_tarea < 1) {
        mensaje = "Debe seleccionar una tarea ..";
        error = true;
    }

    if (error == true) {
        swal('Oops...', mensaje, 'error');
        return false;
    } else {
        let request = (window.XMLHttpRequest) ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url + '/Planes/setDetailPlan';
        let formData = new FormData();
        request.open("POST", ajaxUrl, true);
        formData.append("idTarea", id_tarea);
        formData.append("idPlan", idPlan);
        request.send(formData);
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                let objData = JSON.parse(request.responseText);
                if (objData.status) {
                    swal("Exito !!", objData.msg, "success");
                    tableTareasP.api().ajax.reload();
                    tablePlanes.api().ajax.reload();
                    document.querySelector("#listTareas").value = "0";
                } else {
                    swal("Error", objData.msg, "error");
                }
            }
        };
    }
});

function fntDelTarea(idTarea) {
    swal({
        title: "Eliminar Tarea",
        text: "¿Realmente quiere eliminar esta tarea del plan?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let idPlan = document.querySelector("#idPlanT").value;
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Planes/deleteDetailPlan';
            let strData = "idTarea=" + idTarea + "&idPlan=" + idPlan;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("Exito !!", objData.msg, "success");
                        tableTareasP.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function generarReportPlanes() {
    $.post(base_url + "/Planes/getPlanesReport",
            function (response) {
                var fecha = new Date();
                var planes = JSON.parse(response);
                //console.log(tecnicos);
                if (planes.length > 0) {
                    let estado = "";
                    var pdf = new jsPDF();
                    pdf.text(20, 20, "Reportes de los Planes Registrados");
                    var data = [];
                    var columns = ["NOMBRE", "DESCRIPCION", "FECHA/HORA", "ESTADO"];
                    for (let i = 0; i < planes.length; i++) {
                        if (planes[i].status == 1) {
                            estado = 'ACTIVO';
                        } else {
                            estado = 'INACTIVO';
                        }
                        data[i] = [planes[i].nombre, planes[i].descripcionPlan, planes[i].fecha + " " + planes[i].hora, estado];
                    }
                    pdf.autoTable(columns, data,
                            {margin: {top: 40}}
                    );
                    pdf.text(20, 190, "Fecha de Creacion : " + fecha.getDate() + "/" + (fecha.getMonth() + 1) + "/" + fecha.getFullYear());
                    pdf.save('ReportePlanes.pdf');
                    swal('Exito', "Reporte Imprimido Exitosamente..", 'success');
                } else {
                    swal("Error !!", "No se cuentan con planes registradas !!", "error");
                }
            }
    );
}


