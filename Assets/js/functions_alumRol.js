let tableAlumns;
let tableTareasAl;
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


