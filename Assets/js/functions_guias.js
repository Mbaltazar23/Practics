let tableGuias, tableAlumns;
let rowTable = "";
document.addEventListener('DOMContentLoaded', function () {
    tableGuias = $('#tableGuias').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Guias/getGuias",
            "dataSrc": ""
        },
        "columns": [
            {"data": "rut"},
            {"data": "nombres%"},
            {"data": "correo"},
            {"data": "nombreE"},
            {"data": "status"},
            {"data": "options"}
        ],
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    });

    // NUEVO GUIA
    let formGuia = document.querySelector("#formGuia");
    formGuia.onsubmit = function (e) {
        e.preventDefault();
        let nombre = $("#txtNombreG").val();
        let apellido = $("#txtApellidoG").val();
        let correo = $("#txtCorreoG").val();
        let empresa = $("#listEmpresas").val();
        let telefono = $("#txtTelefonoG").val();
        let regexCorreo = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
        var regulTele = /^(\+?56)?(\s?)(0?9)(\s?)[9876543]\d{7}$/;


        if (nombre == "" || apellido == "" || correo == "" || telefono == "" || empresa == 0) {
            swal("Atención", "Todos los campos son obligatorios.", "error");
            return false;
        } else if (!regexCorreo.test(correo)) {
            swal("Error !!", "El correo ingresado no es valido..", "error");
            return false;
        } else if (!regulTele.test(telefono)) {
            swal("Error !!", "El telefono ingresado no es valido", "error");
            return false;
        } else {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Guias/setGuia';
            let formData = new FormData(formGuia);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        $('#modalFormGuia').modal("hide");
                        formGuia.reset();
                        tableGuias.api().ajax.reload();
                        swal("Exito !!", objData.msg, "success");
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
                return false;
            };
        }
    };
    fntEmpresas();
}, false);

function fntEmpresas() {
    let ajaxUrl = base_url + '/Empresas/getSelectEmpresas';
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            $('.selectEmpresas select').html(request.responseText).fadeIn();
        }
    };
}

function fntViewInfo(idGuia) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Guias/getGuia/' + idGuia;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
                let estado = objData.data.status == 1 ?
                        '<span class="badge badge-success">Activo</span>' :
                        '<span class="badge badge-danger">Inactivo</span>';

                document.querySelector("#celRutG").innerHTML = objData.data.rut;
                document.querySelector("#celNombresG").innerHTML = objData.data.nombre + " " + objData.data.apellido;
                document.querySelector("#celCorreoG").innerHTML = objData.data.correo;
                document.querySelector("#celTelefonoG").innerHTML = objData.data.fono;
                document.querySelector("#celOcupacionG").innerHTML = objData.data.cargo;
                document.querySelector("#celEmpresaG").innerHTML = objData.data.nombreE;
                document.querySelector("#celEstadoG").innerHTML = estado;
                $('#modalViewGuia').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntEditInfo(element, idGuia) {
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').textContent = "Actualizar Guia";
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').textContent = "  Actualizar";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Guias/getGuia/' + idGuia;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#idGuia").value = objData.data.id;
                document.querySelector("#txtRutG").value = objData.data.rut;
                document.querySelector("#txtNombreG").value = objData.data.nombre.toString().toLowerCase();
                document.querySelector("#txtApellidoG").value = objData.data.apellido.toString().toLowerCase();
                document.querySelector("#txtCorreoG").value = objData.data.correo.toString().toLowerCase();
                document.querySelector("#txtTelefonoG").value = objData.data.fono;
                document.querySelector("#listEmpresas").value = objData.data.idempresa;
                document.querySelector("#txtOcupacionG").value = objData.data.cargo;
                validadorRut('txtRutG');
                $('#modalFormGuia').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

function fntViewAlumns(idGuia) {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + '/Guias/getGuia/' + idGuia;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
                //console.log(objData);
                document.querySelector("#titleModalA").innerHTML = "Alumnos del Guia " + objData.data.nombre;
                cargarTable(objData.data.idguia, objData.data.rol);
            }
        }
    };
}

function cargarTable(idGuia, Rol) {
    tableAlumns = $("#tableAlumnsGui").dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Alumnos/getAlumnosRoles",
            "dataSrc": "",
            "data": {id: idGuia,
                Rol: Rol},
            'type': 'POST'

        },
        "columns": [
            {"data": "rut"},
            {"data": "nombres%"},
            {"data": "fono"},
            {"data": "status"},
            {"data": "options"}
        ],
        "iDisplayLength": 10,
        "bDestroy": true,
        "order": [[0, "desc"]]
    });
    $("#modalListAlumnosG").modal('show');
}


function fntPlanAlumT(idalu) {
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
                                            <td>${objPlan.detailGuia.nombre + " " + objPlan.detailGuia.nombre}</td>
                                            <td>${objPlan.detailTutor.nombre + " " + objPlan.detailGuia.nombre}</td>
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
            "url": " " + base_url + "/Tareas/getTareasPlanActive/" + idPlan,
            "dataSrc": ""
        },
        "columns": [
            {"data": "nro"},
            {"data": "nombre"},
            {"data": "status"}
        ],
        "resonsieve": "true",
        "searching": false,
        "bDestroy": true,
        "iDisplayLength": 3,
        "order": [[0, "asc"]]
    });
}


function fntDelInfo(idGuia) {
    swal({
        title: "Inhabilitar Guia",
        text: "¿Realmente quiere inhabilitar a este guia?",
        icon: "warning",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Guias/setStatusGuia';
            let strData = "idGuia=" + idGuia + "&status=0";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Inhabilitado!", objData.msg, "success");
                        tableGuias.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function fntActivateInfo(idGuia) {
    swal({
        title: "Habilitar Guia",
        text: "¿Realmente quiere habilitar este guia?",
        icon: "info",
        dangerMode: true,
        buttons: true
    }).then((isClosed) => {
        if (isClosed) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Guias/setStatusGuia';
            let strData = "idGuia=" + idGuia + "&status=1";
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Habilitado!", objData.msg, "success");
                        tableGuias.api().ajax.reload();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            };
        }
    });
}

function openModal() {
    document.querySelector('#idGuia').value = "";
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').textContent = "  Guardar";
    document.querySelector('#titleModal').textContent = "Nuevo Guia";
    document.querySelector("#formGuia").reset();
    validadorRut('txtRutG');
    $('#modalFormGuia').modal('show');
}


function generarReportGuias() {
    $.post(base_url + "/Guias/getGuiasReport", function (response) {
        var fecha = new Date();
        var guias = JSON.parse(response);
        if (guias.length > 0) {
            // console.log(tecnicos);
            let estado = "";
            var pdf = new jsPDF();
            pdf.text(20, 20, "Reportes de los Guias Registrados");
            var data = [];
            var columns = ["RUT", "NOMBRES", "CORREO", "EMPRESA", "ESTADO"];
            for (let i = 0; i < guias.length; i++) {
                if (guias[i].status == 1) {
                    estado = 'ACTIVO';
                } else {
                    estado = 'INACTIVO';
                }
                data[i] = [
                    guias[i].nombre,
                    guias[i].nombres + " " + guias[i].apellidos,
                    guias[i].correo,
                    guias[i].nombreE,
                    estado
                ];
            }
            pdf.autoTable(columns, data, {
                margin: {
                    top: 40
                }
            });
            pdf.text(20, 190, "Fecha de Creacion : " + fecha.getDate() + "/" + (
                    fecha.getMonth() + 1
                    ) + "/" + fecha.getFullYear());
            pdf.save('ReporteRoles.pdf');
            swal('Exito', "Reporte Imprimido Exitosamente..", 'success');
        } else {
            swal("Error !!", "No se cuentan con maestros guias registrados !!", "error");
        }
    });
}